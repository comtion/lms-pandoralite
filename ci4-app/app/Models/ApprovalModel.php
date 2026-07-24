<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class ApprovalModel extends Model
{
    public function decide(string $type, int $id, string $decision, string $note, array $actor): array
    {
        $validation = $this->validateDecision($type, $id, $decision, $note);
        if ($validation !== null) {
            return $validation;
        }

        try {
            return match ($type) {
                'course' => $this->decideCourse($id, $decision, $note, $actor),
                'survey' => $this->decideSurvey($id, $decision, $note, $actor),
                'course_group' => $this->decideCourseGroup($id, $decision, $note, $actor),
            };
        } catch (Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Approval decision failed: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'message' => 'The approval could not be saved. Please try again.'];
        }
    }

    public function decideMany(string $type, array $ids, string $decision, string $note, array $actor): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), static fn (int $id): bool => $id > 0)));
        if ($ids === [] || count($ids) > 100) {
            return ['ok' => false, 'message' => 'Select between 1 and 100 approval items.', 'succeeded' => 0, 'failed' => count($ids)];
        }

        if ($decision === 'reject' && trim($note) === '') {
            return ['ok' => false, 'message' => 'A rejection reason is required.', 'succeeded' => 0, 'failed' => count($ids)];
        }

        $succeeded = 0;
        $messages = [];
        foreach ($ids as $id) {
            $result = $this->decide($type, $id, $decision, $note, $actor);
            if ($result['ok']) {
                $succeeded++;
            } else {
                $messages[] = '#' . $id . ': ' . $result['message'];
            }
        }

        $failed = count($ids) - $succeeded;
        return [
            'ok' => $succeeded > 0 && $failed === 0,
            'partial' => $succeeded > 0 && $failed > 0,
            'succeeded' => $succeeded,
            'failed' => $failed,
            'message' => sprintf('%d item(s) updated; %d failed.', $succeeded, $failed)
                . ($messages !== [] ? ' ' . implode(' ', array_slice($messages, 0, 5)) : ''),
        ];
    }

    public function history(array $actor, int $limit = 100): array
    {
        $companyId = (int) ($actor['com_id'] ?? 0);
        if ($companyId < 1) {
            return [];
        }

        $items = [];
        $sources = [
            ['history' => 'lms_cos_approve', 'master' => 'lms_cos', 'key' => 'cos_id', 'state' => 'cosa_approve', 'note' => 'cosa_note', 'by' => 'cosa_createby', 'at' => 'cosa_createdate', 'type' => 'course', 'company' => 'com_id'],
            ['history' => 'lms_sv_approve', 'master' => 'lms_sv', 'key' => 'sv_id', 'state' => 'sva_approve', 'note' => 'sva_note', 'by' => 'sva_createby', 'at' => 'sva_createdate', 'type' => 'survey', 'company' => 'com_id'],
            ['history' => 'lms_cog_approve', 'master' => 'lms_cog', 'key' => 'cg_id', 'state' => 'coga_approve', 'note' => 'coga_note', 'by' => 'coga_createby', 'at' => 'coga_createdate', 'type' => 'course_group', 'company' => 'com_id'],
        ];

        foreach ($sources as $source) {
            if (! $this->db->tableExists($source['history'])) {
                continue;
            }
            $rows = $this->db->table($source['history'] . ' h')
                ->select('h.' . $source['key'] . ' item_id, h.' . $source['state'] . ' approval_state, h.' . $source['note'] . ' approval_note, h.' . $source['by'] . ' approved_by, h.' . $source['at'] . ' approved_at, e.fullname_th, e.fullname_en')
                ->join($source['master'] . ' m', 'm.' . $source['key'] . ' = h.' . $source['key'])
                ->join('lms_usp u', 'u.u_id = h.' . $source['by'], 'left')
                ->join('lms_emp e', 'e.emp_id = u.emp_id', 'left')
                ->where('m.' . $source['company'], $companyId)
                ->orderBy('h.' . $source['at'], 'DESC')
                ->limit($limit)
                ->get()
                ->getResultArray();
            foreach ($rows as $row) {
                $row['type'] = $source['type'];
                $row['title'] = $this->itemTitle($source['type'], (int) $row['item_id']);
                $row['approver'] = trim((string) ($row['fullname_en'] ?: ($row['fullname_th'] ?: $row['approved_by'])));
                $items[] = $row;
            }
        }

        usort($items, static fn (array $a, array $b): int => strcmp((string) $b['approved_at'], (string) $a['approved_at']));
        return array_slice($items, 0, $limit);
    }

    private function decideCourse(int $id, string $decision, string $note, array $actor): array
    {
        $this->db->transBegin();
        $item = $this->lockedRow('lms_cos', 'cos_id', $id);
        if (! $item || ! $this->canApproveCourse($item, $actor)) {
            $this->db->transRollback();
            return $this->notPending('course');
        }

        $approved = $decision === 'approve';
        $now = date('Y-m-d H:i:s');
        $actorId = (string) $actor['u_id'];
        $this->db->table('lms_cos_approve')->insert([
            'cos_id' => $id, 'cosa_approve' => $approved ? 1 : 0, 'cosa_note' => trim($note),
            'cosa_createby' => $actorId, 'cosa_createdate' => $now,
        ]);
        $this->db->table('lms_cos')->where('cos_id', $id)->update([
            'cos_public' => $approved ? 1 : 0, 'cos_approve' => $approved ? 1 : 0,
            'cos_approveby' => $actorId, 'cos_approvedate' => $now,
        ]);
        if ($approved) {
            $this->activateCourseSchedule($id);
            $this->scheduleLegacyJob('lms_job_cosnoti', 'cos_id', 'jcosnoti_datejob', $id, $this->courseStartDate($id));
        }
        return $this->finishDecision('course', $id, $item, $approved, $note, $actor);
    }

    private function decideSurvey(int $id, string $decision, string $note, array $actor): array
    {
        $this->db->transBegin();
        $item = $this->lockedRow('lms_sv', 'sv_id', $id);
        if (! $item || ! $this->canApproveSurvey($item, $actor)) {
            $this->db->transRollback();
            return $this->notPending('survey');
        }

        $approved = $decision === 'approve';
        $now = date('Y-m-d H:i:s');
        $actorId = (string) $actor['u_id'];
        $this->db->table('lms_sv_approve')->insert([
            'sv_id' => $id, 'sva_approve' => $approved ? 1 : 0, 'sva_note' => trim($note),
            'sva_createby' => $actorId, 'sva_createdate' => $now,
        ]);
        $this->db->table('lms_sv')->where('sv_id', $id)->update([
            'sv_public' => $approved ? 1 : 0, 'sv_approve' => $approved ? 1 : 0,
            'sv_approveby' => $actorId, 'sv_approvedate' => $now,
            'sv_modifiedby' => $actorId, 'sv_modifieddate' => $now,
        ]);
        if ($approved) {
            $this->scheduleLegacyJob('lms_job_svnoti', 'sv_id', 'jsvnoti_datejob', $id, (string) ($item['sv_open'] ?? ''));
        }
        return $this->finishDecision('survey', $id, $item, $approved, $note, $actor);
    }

    private function decideCourseGroup(int $id, string $decision, string $note, array $actor): array
    {
        $this->db->transBegin();
        $item = $this->lockedRow('lms_cog', 'cg_id', $id);
        if (! $item || ! $this->canApproveCourseGroup($item, $actor)) {
            $this->db->transRollback();
            return $this->notPending('course group');
        }

        $approved = $decision === 'approve';
        $now = date('Y-m-d H:i:s');
        $actorId = (string) $actor['u_id'];
        $this->db->table('lms_cog_approve')->insert([
            'cg_id' => $id, 'coga_approve' => $approved ? 1 : 0, 'coga_note' => trim($note),
            'coga_createby' => $actorId, 'coga_createdate' => $now,
        ]);
        $this->db->table('lms_cog')->where('cg_id', $id)->update([
            'cg_approve' => $approved ? 1 : 0, 'cg_reject' => $approved ? '' : trim($note),
            'u_by' => $actorId, 'u_date' => $now,
        ]);
        return $this->finishDecision('course_group', $id, $item, $approved, $note, $actor);
    }

    private function finishDecision(string $type, int $id, array $item, bool $approved, string $note, array $actor): array
    {
        $title = $this->titleFromRow($type, $item);
        $creator = (string) ($item[$type === 'course' ? 'cos_createby' : ($type === 'survey' ? 'sv_createby' : 'c_by')] ?? '');
        $this->notifyCreator($creator, $type . '_approval', $id, ucfirst(str_replace('_', ' ', $type)) . ($approved ? ' approved' : ' rejected'), $title . ($note !== '' ? ': ' . trim($note) : ''), $this->ownerUrl($type, $id));
        $this->recordLegacyLog($actor, $type, ($approved ? 'Approve ' : 'Reject ') . str_replace('_', ' ', $type) . ': ' . $title . ' (' . $id . ')');
        $this->db->transCommit();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'The approval could not be saved. Please try again.'];
        }

        $this->sendOwnerEmail($creator, $type, $id, $approved, $title, $note);
        return ['ok' => true, 'message' => ucfirst(str_replace('_', ' ', $type)) . ($approved ? ' approved.' : ' rejected and returned to the owner.')];
    }

    private function validateDecision(string $type, int $id, string $decision, string $note): ?array
    {
        if (! in_array($type, ['course', 'survey', 'course_group'], true) || $id < 1 || ! in_array($decision, ['approve', 'reject'], true)) {
            return ['ok' => false, 'message' => 'Invalid approval request.'];
        }
        if ($decision === 'reject' && trim($note) === '') {
            return ['ok' => false, 'message' => 'A rejection reason is required.'];
        }
        if (mb_strlen($note) > 2000) {
            return ['ok' => false, 'message' => 'The approval note must not exceed 2,000 characters.'];
        }
        return null;
    }

    private function lockedRow(string $table, string $key, int $id): ?array
    {
        $row = $this->db->query("SELECT * FROM {$table} WHERE {$key} = ? FOR UPDATE", [$id])->getRowArray();
        return $row ?: null;
    }

    private function canApproveCourse(array $item, array $actor): bool
    {
        if ((int) ($item['com_id'] ?? 0) !== (int) ($actor['com_id'] ?? 0)
            || (string) ($item['cos_public'] ?? '') !== '1' || (string) ($item['cos_approve'] ?? '') !== '0'
            || (string) ($item['cos_isDelete'] ?? '') !== '0') {
            return false;
        }
        return $this->db->table('lms_cosincg x')->join('lms_cog g', 'g.cg_id=x.cg_id')
            ->where('x.course_id', $item['cos_id'])->where('g.cg_approve', 1)->where('g.cg_isDelete', 0)
            ->where("FIND_IN_SET(" . $this->db->escape((string) ($actor['u_id'] ?? '')) . ", REPLACE(g.cg_approve_by, ' ', '')) >", 0, false)
            ->countAllResults() > 0;
    }

    private function canApproveSurvey(array $item, array $actor): bool
    {
        $approvers = array_filter(array_map('trim', explode(',', (string) ($item['sv_userapprove'] ?? ''))));
        return (int) ($item['com_id'] ?? 0) === (int) ($actor['com_id'] ?? 0)
            && (string) ($item['sv_public'] ?? '') === '1' && (string) ($item['sv_approve'] ?? '') === '0'
            && (string) ($item['sv_isDelete'] ?? '') === '0' && (string) ($item['sv_status'] ?? '') === '1'
            && in_array((string) ($actor['emp_id'] ?? ''), $approvers, true);
    }

    private function canApproveCourseGroup(array $item, array $actor): bool
    {
        $approvers = array_filter(array_map('trim', explode(',', (string) ($item['cg_approve_by'] ?? ''))));
        return (int) ($item['com_id'] ?? 0) === (int) ($actor['com_id'] ?? 0)
            && (string) ($item['cg_approve'] ?? '') === '2' && (string) ($item['cg_isDelete'] ?? '') === '0'
            && in_array((string) ($actor['u_id'] ?? ''), $approvers, true);
    }

    private function activateCourseSchedule(int $courseId): void
    {
        if (! $this->db->tableExists('lms_course_notification_schedules')) {
            return;
        }
        $this->db->table('lms_course_notification_schedules')->where('cos_id', $courseId)->where('enabled', 1)
            ->whereIn('status', ['waiting_course', 'canceled'])->update(['status' => 'pending', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    private function scheduleLegacyJob(string $table, string $key, string $dateField, int $id, string $date): void
    {
        $timestamp = strtotime($date);
        if (! $this->db->tableExists($table) || ! $timestamp || date('Y-m-d', $timestamp) <= date('Y-m-d')) {
            return;
        }
        if ($this->db->table($table)->where($key, $id)->where($dateField, date('Y-m-d', $timestamp))->countAllResults() === 0) {
            $this->db->table($table)->insert([$key => $id, $dateField => date('Y-m-d', $timestamp)]);
        }
    }

    private function courseStartDate(int $courseId): string
    {
        if (! $this->db->tableExists('lms_cos_detail')) {
            return '';
        }
        $row = $this->db->table('lms_cos_detail')->select('date_start')->where('cos_id', $courseId)
            ->where('cosde_status', 1)->where('cosde_isDelete', 0)->orderBy('cosde_id', 'DESC')->get()->getRowArray();
        return (string) ($row['date_start'] ?? '');
    }

    private function notifyCreator(string $creatorUserId, string $type, int $referenceId, string $title, string $message, string $url): void
    {
        $account = $this->ownerAccount($creatorUserId);
        if (! $account || empty($account['emp_id']) || ! $this->db->tableExists('lms_notifications')) {
            return;
        }
        if ($this->db->table('lms_notifications')->where('emp_id', $account['emp_id'])->where('type', $type)->where('ref_id', $referenceId)->where('title', $title)->countAllResults() > 0) {
            return;
        }
        $this->db->table('lms_notifications')->insert([
            'emp_id' => (int) $account['emp_id'], 'type' => $type, 'ref_id' => $referenceId,
            'title' => $title, 'message' => $message, 'url' => $url, 'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'), 'read_at' => null,
        ]);
    }

    private function sendOwnerEmail(string $creatorUserId, string $type, int $id, bool $approved, string $title, string $note): void
    {
        try {
            $owner = $this->ownerAccount($creatorUserId);
            if (! $owner || trim((string) ($owner['email'] ?? '')) === '' || ! $this->db->tableExists('lms_sendmail_form')) {
                return;
            }
            $templateType = $type === 'survey' ? ($approved ? 8 : 9) : ($approved ? 5 : 6);
            $template = $this->db->table('lms_sendmail_form')->where('smf_type', $templateType)->where('smf_show', 1)->get()->getRowArray();
            if (! $template) {
                return;
            }
            $lang = (string) ($owner['lang'] ?? 'english');
            $suffix = $lang === 'thai' ? 'th' : 'en';
            $replacements = [
                '#fullname' => $owner['fullname_' . $suffix] ?? $owner['fullname_en'] ?? '',
                '#username' => $owner['useri'] ?? '', '#email' => $owner['email'],
                '#coursename' => $title, '#message' => $note, '#date' => date('d F Y'),
                '#time' => date('H:i'), '#link_frontend' => site_url($this->ownerUrl($type, $id)),
            ];
            $subject = strtr((string) ($template['smf_subject_' . $suffix] ?: $template['smf_subject_en']), $replacements);
            $body = strtr((string) ($template['smf_message_' . $suffix] ?: $template['smf_message_en']), $replacements);
            $email = service('email');
            $settings = $this->mailSettings();
            if ($settings !== []) {
                $email->initialize($settings);
                $email->setFrom($settings['fromEmail'], $settings['fromName']);
            }
            $sent = $email->setTo((string) $owner['email'])->setSubject($subject)->setMessage($body)->send(false);
            if (! $sent) {
                log_message('error', 'Approval email delivery failed for {email}: {debug}', [
                    'email' => $owner['email'],
                    'debug' => $email->printDebugger(['headers']),
                ]);
            }
            $email->clear(true);
        } catch (Throwable $e) {
            log_message('error', 'Approval email failed: {message}', ['message' => $e->getMessage()]);
        }
    }

    private function mailSettings(): array
    {
        if (! $this->db->tableExists('lms_setting_mail')) {
            return [];
        }
        $row = $this->db->table('lms_setting_mail')->where('sm_id', 1)->get()->getRowArray();
        if (! $row || trim((string) ($row['sm_host'] ?? '')) === '') {
            return [];
        }
        $port = (int) ($row['sm_port'] ?? 25);
        return [
            'protocol' => 'smtp',
            'SMTPHost' => (string) $row['sm_host'],
            'SMTPUser' => (string) ($row['sm_username'] ?? ''),
            'SMTPPass' => (string) ($row['sm_password'] ?? ''),
            'SMTPPort' => $port,
            'SMTPCrypto' => $port === 465 ? 'ssl' : ($port === 587 ? 'tls' : ''),
            'SMTPAuth' => (string) ($row['sm_smtpauth'] ?? '1') === '1',
            'fromEmail' => (string) ($row['sm_emailsender'] ?: $row['sm_username']),
            'fromName' => (string) ($row['sm_sender'] ?: 'LMS'),
            'mailType' => 'html',
            'charset' => 'UTF-8',
        ];
    }

    private function ownerAccount(string $userId): ?array
    {
        if ($userId === '') {
            return null;
        }
        $row = $this->db->table('lms_usp u')->select('u.u_id,u.useri,u.emp_id,e.email,e.lang,e.fullname_th,e.fullname_en')
            ->join('lms_emp e', 'e.emp_id=u.emp_id')->where('u.u_id', $userId)->where('u.u_isDelete', 0)->where('e.emp_isDelete', 0)->get()->getRowArray();
        return $row ?: null;
    }

    private function recordLegacyLog(array $actor, string $type, string $message): void
    {
        if (! $this->db->tableExists('lms_lg')) {
            return;
        }
        $this->db->table('lms_lg')->insert([
            'log_type' => $type, 'emp_id' => (int) ($actor['emp_id'] ?? 0), 'massage' => $message,
            'ip' => service('request')->getIPAddress(), 'device' => (string) service('request')->getUserAgent(),
            'log_time' => date('Y-m-d H:i:s'),
        ]);
    }

    private function notPending(string $type): array
    {
        return ['ok' => false, 'message' => 'This ' . $type . ' is no longer pending or you are not an assigned approver.'];
    }

    private function ownerUrl(string $type, int $id): string
    {
        return match ($type) {
            'course' => 'managecourse/courses_all/' . $id . '/edit',
            'survey' => 'managecourse/surveys/' . $id . '/edit',
            default => 'managecourse/course_groups',
        };
    }

    private function itemTitle(string $type, int $id): string
    {
        $map = [
            'course' => ['lms_cos', 'cos_id'], 'survey' => ['lms_sv', 'sv_id'], 'course_group' => ['lms_cog', 'cg_id'],
        ];
        $row = $this->db->table($map[$type][0])->where($map[$type][1], $id)->get()->getRowArray();
        return $row ? $this->titleFromRow($type, $row) : '#' . $id;
    }

    private function titleFromRow(string $type, array $row): string
    {
        $fields = match ($type) {
            'course' => ['cname_eng', 'cname_th', 'cname_jp'],
            'survey' => ['sv_title_eng', 'sv_title_th', 'sv_title_jp'],
            default => ['cgtitle_en', 'cgtitle_th', 'cgtitle_jp'],
        };
        foreach ($fields as $field) {
            if (trim((string) ($row[$field] ?? '')) !== '') {
                return trim((string) $row[$field]);
            }
        }
        return ucfirst(str_replace('_', ' ', $type));
    }
}
