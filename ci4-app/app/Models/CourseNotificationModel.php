<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseNotificationModel extends Model
{
    protected $returnType = 'array';

    public function scheduleForCourse(int $courseId): ?array
    {
        if (! $this->hasCoreTables()) {
            return null;
        }

        $row = $this->db->table('lms_course_notification_schedules')
            ->where('cos_id', $courseId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function saveScheduleForCourse(int $courseId, array $input, array $user): array
    {
        if (! $this->hasCoreTables()) {
            return ['ok' => true, 'message' => 'Course notification tables are not installed.'];
        }

        $enabled = (int) ($input['notify_enabled'] ?? 0) === 1;
        $now = date('Y-m-d H:i:s');
        $existing = $this->scheduleForCourse($courseId);

        if (! $enabled) {
            if ($existing) {
                $this->db->table('lms_course_notification_schedules')
                    ->where('cn_id', $existing['cn_id'])
                    ->update([
                        'enabled' => 0,
                        'status' => $existing['status'] === 'sent' ? 'sent' : 'canceled',
                        'updated_by' => (string) ($user['u_id'] ?? $user['emp_id'] ?? ''),
                        'updated_at' => $now,
                    ]);
            }

            return ['ok' => true, 'message' => 'Course notification disabled.'];
        }

        $channels = $this->channels($input['notify_channels'] ?? ['system', 'email']);
        if ($channels === []) {
            return ['ok' => false, 'message' => 'Select at least one notification channel.'];
        }

        $sendAt = $this->dateTimeOrNow((string) ($input['notify_send_at'] ?? ''));
        $audienceType = (string) ($input['notify_audience_type'] ?? 'all');
        if (! in_array($audienceType, ['all', 'departments', 'users'], true)) {
            $audienceType = 'all';
        }

        $departments = $this->ids($input['notify_department_ids'] ?? []);
        $users = $this->ids($input['notify_user_ids'] ?? []);
        if ($audienceType === 'departments' && $departments === []) {
            return ['ok' => false, 'message' => 'Select at least one department for course notification.'];
        }
        if ($audienceType === 'users' && $users === []) {
            return ['ok' => false, 'message' => 'Select at least one learner for course notification.'];
        }

        $payload = [
            'cos_id' => $courseId,
            'enabled' => 1,
            'channels' => implode(',', $channels),
            'audience_type' => $audienceType,
            'target_departments' => implode(',', $departments),
            'target_users' => implode(',', $users),
            'send_at' => $sendAt,
            'status' => 'pending',
            'updated_by' => (string) ($user['u_id'] ?? $user['emp_id'] ?? ''),
            'updated_at' => $now,
            'dispatched_at' => null,
        ];

        if ($existing) {
            $this->db->table('lms_course_notification_schedules')
                ->where('cn_id', $existing['cn_id'])
                ->update($payload);
        } else {
            $payload['created_by'] = (string) ($user['u_id'] ?? $user['emp_id'] ?? '');
            $payload['created_at'] = $now;
            $this->db->table('lms_course_notification_schedules')->insert($payload);
        }

        return ['ok' => true, 'message' => 'Course notification schedule saved.'];
    }

    public function dispatchDue(int $limit = 25, int $recipientLimit = 250): array
    {
        if (! $this->hasCoreTables()) {
            return ['schedules' => 0, 'system' => 0, 'email' => 0, 'failed' => 0];
        }

        $schedules = $this->db->table('lms_course_notification_schedules')
            ->where('enabled', 1)
            ->where($this->inClause('status', ['pending', 'waiting_course', 'processing']), null, false)
            ->where('send_at <=', date('Y-m-d H:i:s'))
            ->orderBy('send_at', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $summary = ['schedules' => 0, 'system' => 0, 'email' => 0, 'failed' => 0];
        foreach ($schedules as $schedule) {
            $summary['schedules']++;
            $result = $this->dispatchSchedule($schedule, false, $recipientLimit);
            $summary['system'] += $result['system'];
            $summary['email'] += $result['email'];
            $summary['failed'] += $result['failed'];
        }

        return $summary;
    }

    public function retrySchedule(int $scheduleId): array
    {
        if (! $this->hasCoreTables()) {
            return ['ok' => false, 'message' => 'Course notification tables are not installed.'];
        }

        $schedule = $this->db->table('lms_course_notification_schedules')
            ->where('cn_id', $scheduleId)
            ->get()
            ->getRowArray();

        if (! $schedule) {
            return ['ok' => false, 'message' => 'Notification schedule not found.'];
        }

        $result = $this->dispatchSchedule($schedule, true, 500);
        return [
            'ok' => $result['failed'] === 0,
            'message' => 'Retry completed. System: ' . $result['system'] . ', Email: ' . $result['email'] . ', Failed: ' . $result['failed'] . '.',
        ];
    }

    public function schedulesWithLogSummary(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        if (! $this->hasCoreTables()) {
            return [];
        }

        $builder = $this->scheduleSummaryBuilder($filters)
            ->select('s.*, lms_cos.ccode, lms_cos.cname_eng, lms_cos.cname_th, lms_cos.cname_jp')
            ->select("SUM(CASE WHEN l.status = 'sent' THEN 1 ELSE 0 END) AS sent_count", false)
            ->select("SUM(CASE WHEN l.status = 'failed' THEN 1 ELSE 0 END) AS failed_count", false)
            ->select('COUNT(l.cnl_id) AS log_count')
            ->groupBy('s.cn_id')
            ->orderBy('s.updated_at', 'DESC')
            ->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function scheduleCount(array $filters = []): int
    {
        if (! $this->hasCoreTables()) {
            return 0;
        }

        return (int) $this->scheduleSummaryBuilder($filters)
            ->select('COUNT(DISTINCT s.cn_id) AS total', false)
            ->get()
            ->getRow('total');
    }

    public function logsForSchedule(int $scheduleId, array $filters = [], int $limit = 100, int $offset = 0): array
    {
        if (! $this->hasCoreTables()) {
            return [];
        }

        $builder = $this->logBuilder($scheduleId, $filters)
            ->select('l.*, lms_emp.fullname_en, lms_emp.fullname_th')
            ->orderBy('l.created_at', 'DESC')
            ->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function logCount(int $scheduleId, array $filters = []): int
    {
        if (! $this->hasCoreTables()) {
            return 0;
        }

        return (int) $this->logBuilder($scheduleId, $filters)
            ->select('COUNT(l.cnl_id) AS total')
            ->get()
            ->getRow('total');
    }

    public function sendTestEmail(string $recipientEmail): array
    {
        $recipientEmail = trim($recipientEmail);
        if ($recipientEmail === '') {
            return ['ok' => false, 'message' => 'Recipient email is required.'];
        }

        $email = service('email');
        $settings = $this->mailSettings();
        if ($settings) {
            $email->initialize($settings);
        }

        $fromEmail = $settings['fromEmail'] ?? ($settings['SMTPUser'] ?? '');
        if ($fromEmail !== '') {
            $email->setFrom($fromEmail, $settings['fromName'] ?? 'LMS');
        }

        $email->setTo($recipientEmail);
        $email->setSubject('LMS notification email test');
        $email->setMessage('<p>This is a test email from the LMS course notification dispatcher.</p><p>Sent at ' . date('Y-m-d H:i:s') . '</p>');

        $sent = $email->send(false);
        $debug = $sent ? 'Email sent.' : $email->printDebugger(['headers']);
        $email->clear(true);

        return ['ok' => $sent, 'message' => $debug];
    }

    private function dispatchSchedule(array $schedule, bool $failedOnly = false, int $recipientLimit = 250): array
    {
        $course = $this->course((int) $schedule['cos_id']);
        if (! $course) {
            $this->markSchedule($schedule, 'failed');
            return ['system' => 0, 'email' => 0, 'failed' => 1];
        }
        if (! $this->courseReadyForNotification($course)) {
            $this->markSchedule($schedule, 'waiting_course', false);
            return ['system' => 0, 'email' => 0, 'failed' => 0];
        }

        $channels = $this->channels(explode(',', (string) $schedule['channels']));
        if ($failedOnly) {
            $recipients = $this->recipients($schedule, (int) $course['com_id'], 500);
            $recipients = $this->failedRecipients((int) $schedule['cn_id'], $recipients, $channels);
        } else {
            $recipients = $this->recipientsMissingSentLogs($schedule, (int) $course['com_id'], $channels, $recipientLimit);
        }
        $result = ['system' => 0, 'email' => 0, 'failed' => 0];

        foreach ($recipients as $recipient) {
            $retryChannels = $recipient['_retry_channels'] ?? $channels;
            if (in_array('system', $channels, true) && in_array('system', $retryChannels, true)) {
                if (! $this->hasSentLog((int) $schedule['cn_id'], (int) $recipient['emp_id'], 'system')) {
                    $ok = $this->createSystemNotification($schedule, $course, $recipient);
                    $result[$ok ? 'system' : 'failed']++;
                }
            }

            if (in_array('email', $channels, true) && in_array('email', $retryChannels, true)) {
                if (! $this->hasSentLog((int) $schedule['cn_id'], (int) $recipient['emp_id'], 'email')) {
                    $ok = $this->sendEmailNotification($schedule, $course, $recipient);
                    $result[$ok ? 'email' : 'failed']++;
                }
            }
        }

        $remaining = count($this->recipientsMissingSentLogs($schedule, (int) $course['com_id'], $channels, 1));
        $this->markSchedule($schedule, $remaining > 0 ? 'processing' : 'sent');
        return $result;
    }

    private function failedRecipients(int $scheduleId, array $recipients, array $channels): array
    {
        $failedRows = $this->db->table('lms_course_notification_logs')
            ->select('emp_id, channel')
            ->where('cn_id', $scheduleId)
            ->where('status', 'failed')
            ->where($this->inClause('channel', $channels), null, false)
            ->get()
            ->getResultArray();

        if ($failedRows === []) {
            return [];
        }

        $failedChannelsByEmployee = [];
        foreach ($failedRows as $row) {
            $failedChannelsByEmployee[(int) $row['emp_id']][] = (string) $row['channel'];
        }

        $retryRecipients = [];
        foreach ($recipients as $recipient) {
            $employeeId = (int) $recipient['emp_id'];
            if (! isset($failedChannelsByEmployee[$employeeId])) {
                continue;
            }
            $recipient['_retry_channels'] = array_values(array_unique($failedChannelsByEmployee[$employeeId]));
            $retryRecipients[] = $recipient;
        }

        return $retryRecipients;
    }

    private function createSystemNotification(array $schedule, array $course, array $recipient): bool
    {
        [$title, $message] = $this->notificationText($course, $recipient);

        $this->db->table('lms_notifications')->insert([
            'emp_id' => (int) $recipient['emp_id'],
            'type' => 'new_course',
            'ref_id' => (int) $course['cos_id'],
            'title' => $title,
            'message' => $message,
            'url' => 'coursemain/detail/' . (int) $course['cos_id'],
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->log($schedule, $course, $recipient, 'system', 'sent', 'System notification created.');
        return true;
    }

    private function sendEmailNotification(array $schedule, array $course, array $recipient): bool
    {
        $emailAddress = trim((string) ($recipient['email'] ?? ''));
        if ($emailAddress === '') {
            $this->log($schedule, $course, $recipient, 'email', 'failed', 'Recipient has no email address.');
            return false;
        }

        $email = service('email');
        $settings = $this->mailSettings();
        if ($settings) {
            $email->initialize($settings);
        }

        $fromEmail = $settings['fromEmail'] ?? ($settings['SMTPUser'] ?? '');
        if ($fromEmail !== '') {
            $email->setFrom($fromEmail, $settings['fromName'] ?? 'LMS');
        }

        $title = $this->courseTitle($course);
        $url = site_url('coursemain/detail/' . (int) $course['cos_id']);
        [$subject, $body] = $this->emailContent($course, $recipient, $title, $url);

        $email->setTo($emailAddress);
        $email->setSubject($subject);
        $email->setMessage($body);

        $sent = $email->send(false);
        $this->log($schedule, $course, $recipient, 'email', $sent ? 'sent' : 'failed', $sent ? 'Email sent.' : $email->printDebugger(['headers']));
        $email->clear(true);

        return $sent;
    }

    private function mailSettings(): array
    {
        if (! $this->db->tableExists('lms_setting_mail')) {
            return ['mailType' => 'html'];
        }

        $row = $this->db->table('lms_setting_mail')
            ->where('sm_id', 1)
            ->get()
            ->getRowArray();

        if (! $row) {
            return ['mailType' => 'html'];
        }

        $port = (int) ($row['sm_port'] ?? 25);
        $host = (string) ($row['sm_host'] ?? '');
        $crypto = $port === 465 ? 'ssl' : ($port === 587 ? 'tls' : '');

        return [
            'protocol' => 'smtp',
            'SMTPHost' => $host,
            'SMTPUser' => (string) ($row['sm_username'] ?? ''),
            'SMTPPass' => (string) ($row['sm_password'] ?? ''),
            'SMTPPort' => $port,
            'SMTPCrypto' => $crypto,
            'fromEmail' => (string) ($row['sm_emailsender'] ?? $row['sm_username'] ?? ''),
            'fromName' => (string) ($row['sm_sender'] ?? 'LMS'),
            'mailType' => 'html',
            'charset' => 'UTF-8',
        ];
    }

    private function recipients(array $schedule, int $companyId, int $limit = 250): array
    {
        $builder = $this->db->table('lms_emp')
            ->select('lms_emp.emp_id, lms_emp.email, lms_emp.fullname_th, lms_emp.fullname_en, lms_emp.lang, lms_usp.useri, lms_usp.dep_id, lms_usp.u_id')
            ->join('lms_usp', 'lms_usp.emp_id = lms_emp.emp_id')
            ->where('lms_emp.emp_isDelete', '0')
            ->where('lms_emp.status', '1')
            ->where('lms_emp.com_id', $companyId)
            ->where('lms_usp.u_isDelete', '0')
            ->groupStart()
                ->where('lms_usp.inactivedate', '0000-00-00')
                ->orWhere('lms_usp.inactivedate', '0000-00-00 00:00:00')
                ->orWhere('lms_usp.inactivedate >', date('Y-m-d H:i:s'))
            ->groupEnd();

        if ($schedule['audience_type'] === 'departments') {
            $builder->where($this->inClause('lms_usp.dep_id', $this->ids(explode(',', (string) $schedule['target_departments']))), null, false);
        }
        if ($schedule['audience_type'] === 'users') {
            $builder->where($this->inClause('lms_emp.emp_id', $this->ids(explode(',', (string) $schedule['target_users']))), null, false);
        }

        return $builder->orderBy('lms_emp.emp_id', 'ASC')->limit($limit)->get()->getResultArray();
    }

    private function course(int $courseId): ?array
    {
        $row = $this->db->table('lms_cos')
            ->select('lms_cos.*, lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->select('CAST(MAX(lms_cos_detail.date_start) AS CHAR) AS date_start, CAST(MAX(lms_cos_detail.date_end) AS CHAR) AS date_end')
            ->join('lms_company', 'lms_company.com_id = lms_cos.com_id', 'left')
            ->join('lms_cos_detail', 'lms_cos_detail.cos_id = lms_cos.cos_id AND lms_cos_detail.cosde_isDelete = 0', 'left')
            ->where('lms_cos.cos_id', $courseId)
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos.cos_id')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function courseTitle(array $course): string
    {
        foreach (['cname_eng', 'cname_th', 'cname_jp'] as $field) {
            $value = trim((string) ($course[$field] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return 'Course #' . (int) ($course['cos_id'] ?? 0);
    }

    private function markSchedule(array $schedule, string $status, bool $setDispatchedAt = true): void
    {
        $payload = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($setDispatchedAt) {
            $payload['dispatched_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('lms_course_notification_schedules')
            ->where('cn_id', $schedule['cn_id'])
            ->update($payload);
    }

    private function log(array $schedule, array $course, array $recipient, string $channel, string $status, string $message): void
    {
        $this->db->table('lms_course_notification_logs')->insert([
            'cn_id' => (int) $schedule['cn_id'],
            'cos_id' => (int) $course['cos_id'],
            'emp_id' => (int) ($recipient['emp_id'] ?? 0),
            'channel' => $channel,
            'recipient_email' => (string) ($recipient['email'] ?? ''),
            'status' => $status,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function scheduleSummaryBuilder(array $filters)
    {
        $builder = $this->db->table('lms_course_notification_schedules s')
            ->join('lms_cos', 'lms_cos.cos_id = s.cos_id', 'left')
            ->join('lms_course_notification_logs l', 'l.cn_id = s.cn_id', 'left');

        if (! empty($filters['course_id'])) {
            $builder->where('s.cos_id', (int) $filters['course_id']);
        }
        if (! empty($filters['status'])) {
            $builder->where('s.status', (string) $filters['status']);
        }
        if (! empty($filters['date_from'])) {
            $builder->where('s.send_at >=', date('Y-m-d 00:00:00', strtotime((string) $filters['date_from'])));
        }
        if (! empty($filters['date_to'])) {
            $builder->where('s.send_at <=', date('Y-m-d 23:59:59', strtotime((string) $filters['date_to'])));
        }
        if (! empty($filters['channel'])) {
            $builder->where('l.channel', (string) $filters['channel']);
        }
        if (! empty($filters['log_status'])) {
            $builder->where('l.status', (string) $filters['log_status']);
        }

        return $builder;
    }

    private function logBuilder(int $scheduleId, array $filters)
    {
        $builder = $this->db->table('lms_course_notification_logs l')
            ->join('lms_emp', 'lms_emp.emp_id = l.emp_id', 'left')
            ->where('l.cn_id', $scheduleId);

        if (! empty($filters['channel'])) {
            $builder->where('l.channel', (string) $filters['channel']);
        }
        if (! empty($filters['log_status'])) {
            $builder->where('l.status', (string) $filters['log_status']);
        }
        if (! empty($filters['date_from'])) {
            $builder->where('l.created_at >=', date('Y-m-d 00:00:00', strtotime((string) $filters['date_from'])));
        }
        if (! empty($filters['date_to'])) {
            $builder->where('l.created_at <=', date('Y-m-d 23:59:59', strtotime((string) $filters['date_to'])));
        }

        return $builder;
    }

    private function recipientsMissingSentLogs(array $schedule, int $companyId, array $channels, int $limit): array
    {
        $recipients = $this->recipients($schedule, $companyId, $limit);
        return array_values(array_filter($recipients, function (array $recipient) use ($schedule, $channels): bool {
            foreach ($channels as $channel) {
                if (! $this->hasSentLog((int) $schedule['cn_id'], (int) $recipient['emp_id'], $channel)) {
                    return true;
                }
            }
            return false;
        }));
    }

    private function hasSentLog(int $scheduleId, int $employeeId, string $channel): bool
    {
        return $this->db->table('lms_course_notification_logs')
            ->where('cn_id', $scheduleId)
            ->where('emp_id', $employeeId)
            ->where('channel', $channel)
            ->where('status', 'sent')
            ->countAllResults() > 0;
    }

    private function notificationText(array $course, array $recipient): array
    {
        $title = 'New course: ' . $this->courseTitle($course);
        $message = 'A new course is available for learning.';

        return [$title, $message];
    }

    private function emailContent(array $course, array $recipient, string $title, string $url): array
    {
        $template = $this->emailTemplate();
        if (! $template) {
            return [
                'New course available: ' . $title,
                view('emails/new_course_notification', [
                    'recipient' => $recipient,
                    'course' => $course,
                    'courseTitle' => $title,
                    'courseUrl' => $url,
                ]),
            ];
        }

        $lang = strtolower((string) ($recipient['lang'] ?? 'english'));
        $subject = str_contains($lang, 'thai') ? (string) $template['smf_subject_th'] : (string) $template['smf_subject_en'];
        $body = str_contains($lang, 'thai') ? (string) $template['smf_message_th'] : (string) $template['smf_message_en'];
        if ($subject === '') {
            $subject = (string) ($template['smf_subject_en'] ?: $template['smf_subject_th']);
        }
        if ($body === '') {
            $body = (string) ($template['smf_message_en'] ?: $template['smf_message_th']);
        }

        return [
            $this->replaceTemplateTokens($subject, $course, $recipient, $title, $url, $template),
            $this->replaceTemplateTokens($body, $course, $recipient, $title, $url, $template),
        ];
    }

    private function emailTemplate(): ?array
    {
        if (! $this->db->tableExists('lms_sendmail_form')) {
            return null;
        }

        $row = $this->db->table('lms_sendmail_form')
            ->where('smf_show', 1)
            ->where($this->inClause('smf_type', [12, 10]), null, false)
            ->orderBy('FIELD(smf_type, 12, 10)', '', false)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function replaceTemplateTokens(string $value, array $course, array $recipient, string $title, string $url, array $template): string
    {
        $image = '';
        if (! empty($template['smf_importimage'])) {
            $image = '<img src="' . base_url('uploads/formatmail_img/' . $template['smf_importimage']) . '" style="max-width:800px">';
        }

        $period = $this->periodLabel((string) ($course['date_start'] ?? ''), (string) ($course['date_end'] ?? ''));
        $expire = $this->isEmptyDate((string) ($course['date_end'] ?? '')) ? '' : date('d F Y H:i', strtotime((string) $course['date_end']));
        $fullname = trim((string) ($recipient['fullname_en'] ?: $recipient['fullname_th'] ?: $recipient['useri'] ?: 'Learner'));

        return strtr($value, [
            '#fullname' => $fullname,
            '#username' => (string) ($recipient['useri'] ?? ''),
            '#email' => (string) ($recipient['email'] ?? ''),
            '#coursename' => $title,
            '#link_frontend' => $url,
            '#date' => date('d F Y'),
            '#time' => date('H:i'),
            '#perioddate' => $period,
            '#expiredate' => $expire,
            '#durationofstudy' => (string) ($course['cos_hour'] ?? ''),
            '#companyname' => (string) ($course['com_code'] ?? $course['com_name_eng'] ?? $course['com_name_th'] ?? ''),
            '#image' => $image,
        ]);
    }

    private function channels($channels): array
    {
        if (! is_array($channels)) {
            $channels = [$channels];
        }

        return array_values(array_unique(array_intersect(array_map('strval', $channels), ['system', 'email'])));
    }

    private function ids($value): array
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        return array_values(array_unique(array_filter(array_map('intval', $value))));
    }

    private function dateTimeOrNow(string $value): string
    {
        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s');
    }

    private function periodLabel(string $start, string $end): string
    {
        if ($this->isEmptyDate($start) && $this->isEmptyDate($end)) {
            return 'Unlimited time';
        }

        return ($this->isEmptyDate($start) ? 'Anytime' : date('d F Y H:i', strtotime($start)))
            . ' - '
            . ($this->isEmptyDate($end) ? 'No end' : date('d F Y H:i', strtotime($end)));
    }

    private function isEmptyDate(string $value): bool
    {
        return trim($value) === '' || str_starts_with($value, '0000-00-00');
    }

    private function courseReadyForNotification(array $course): bool
    {
        return (string) ($course['cos_approve'] ?? '') === '1'
            && (string) ($course['cos_public'] ?? '') === '1'
            && (string) ($course['cos_status'] ?? '') === '1'
            && (string) ($course['cos_isDelete'] ?? '') === '0';
    }

    private function hasCoreTables(): bool
    {
        return $this->db->tableExists('lms_course_notification_schedules')
            && $this->db->tableExists('lms_course_notification_logs')
            && $this->db->tableExists('lms_notifications');
    }

    private function inClause(string $column, array $values): string
    {
        $values = array_values(array_unique(array_filter($values, static fn ($value): bool => $value !== '' && $value !== null)));
        if ($values === []) {
            return '1 = 0';
        }

        $escaped = array_map(fn ($value): string => $this->db->escape($value), $values);
        return $column . ' IN (' . implode(',', $escaped) . ')';
    }
}
