<?php

namespace App\Models;

use CodeIgniter\Model;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

class UserAdminModel extends Model
{
    protected $returnType = 'array';

    public function users(int $limit = 100): array
    {
        return $this->db->table('lms_usp')
            ->select('lms_usp.u_id, lms_usp.useri, lms_usp.login, lms_usp.u_status, lms_usp.ug_id, lms_usp.dep_id')
            ->select('lms_emp.emp_id, lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en, lms_emp.email, lms_emp.status, lms_emp.com_id')
            ->select('lms_company.com_code, lms_company.com_name_eng, lms_company.com_name_th')
            ->select('lms_usp_gp.ug_name_en, lms_usp_gp.ug_name_th')
            ->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->join('lms_usp_gp', 'lms_usp_gp.ug_id = lms_usp.ug_id', 'left')
            ->where('lms_usp.u_isDelete', '0')
            ->where('lms_emp.emp_isDelete', '0')
            ->orderBy('lms_usp.u_id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function createUser(array $input, array $actor): array
    {
        $payload = $this->payload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $emp = $payload['employee'];
        $login = $payload['login'];
        if ($this->db->table('lms_emp')->where('emp_c', $emp['emp_c'])->where('emp_isDelete', '0')->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Employee code already exists.'];
        }
        if ($this->db->table('lms_usp')->where('useri', $login['useri'])->where('u_isDelete', '0')->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Username already exists.'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->db->table('lms_emp')->insert($emp + [
            'emp_isDelete' => 0,
            'emp_createby' => (string) ($actor['u_id'] ?? ''),
            'emp_createdate' => $now,
            'emp_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'emp_modifieddate' => $now,
            'emp_firsttime' => 1,
        ]);
        $employeeId = (int) $this->db->insertID();

        $password = trim((string) ($input['password'] ?? ''));
        if ($password === '') {
            $password = $this->temporaryPassword();
        }

        $this->db->table('lms_usp')->insert($login + [
            'emp_id' => $employeeId,
            'userp' => hash('sha256', $password),
            'last_act' => '0000-00-00 00:00:00',
            'st_on' => 'offline',
            'login' => '1',
            'u_lockdate' => '0000-00-00 00:00:00',
            'firsttime' => 1,
            'u_firstdate' => '0000-00-00 00:00:00',
            'expiredate' => (new \DateTimeImmutable('+90 days'))->format('Y-m-d H:i:s'),
            'inactivedate' => '0000-00-00',
            'dummy_status' => 0,
            'img_profile' => 'default_profile.jpg',
            'bgpic_user' => '',
            'usp_point' => 0,
            'confirm_status' => 1,
            'u_status' => 1,
            'u_isDelete' => 0,
            'u_createby' => (string) ($actor['u_id'] ?? ''),
            'u_createdate' => $now,
            'u_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'u_modifieddate' => $now,
            'lang_last' => $emp['lang'],
        ]);
        $userId = (int) $this->db->insertID();
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'User creation failed.'];
        }

        $this->recordLog($actor, 'user', 'Create user: ' . $login['useri']);

        return ['ok' => true, 'message' => 'User created.', 'u_id' => $userId, 'emp_id' => $employeeId, 'temporary_password' => $password];
    }

    public function updateUser(int $userId, array $input, array $actor): array
    {
        $record = $this->userForEdit($userId);
        if (! $record) {
            return ['ok' => false, 'message' => 'User not found.'];
        }

        $payload = $this->payload($input, $userId, (int) $record['emp_id']);
        if (! $payload['ok']) {
            return $payload;
        }

        $emp = $payload['employee'];
        $login = $payload['login'];
        if ($this->db->table('lms_emp')->where('emp_c', $emp['emp_c'])->where('emp_id !=', $record['emp_id'])->where('emp_isDelete', '0')->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Employee code already exists.'];
        }
        if ($this->db->table('lms_usp')->where('useri', $login['useri'])->where('u_id !=', $userId)->where('u_isDelete', '0')->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Username already exists.'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->db->table('lms_emp')->where('emp_id', $record['emp_id'])->update($emp + [
            'emp_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'emp_modifieddate' => $now,
        ]);

        $loginUpdate = $login + [
            'u_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'u_modifieddate' => $now,
            'lang_last' => $emp['lang'],
        ];
        $password = trim((string) ($input['password'] ?? ''));
        if ($password !== '') {
            $loginUpdate['userp'] = hash('sha256', $password);
            $loginUpdate['firsttime'] = 1;
            $loginUpdate['expiredate'] = (new \DateTimeImmutable('+90 days'))->format('Y-m-d H:i:s');
        }

        $this->db->table('lms_usp')->where('u_id', $userId)->update($loginUpdate);
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'User update failed.'];
        }

        $this->recordLog($actor, 'user', 'Update user: ' . $login['useri']);

        return ['ok' => true, 'message' => 'User updated.'];
    }

    public function setUserStatus(int $userId, int $status, array $actor): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        $record = $this->userForEdit($userId);
        if (! $record) {
            return ['ok' => false, 'message' => 'User not found.'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->db->table('lms_emp')->where('emp_id', $record['emp_id'])->update([
            'status' => (string) $status,
            'emp_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'emp_modifieddate' => $now,
        ]);
        $this->db->table('lms_usp')->where('u_id', $userId)->update([
            'u_status' => $status,
            'login' => (string) $status,
            'u_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'u_modifieddate' => $now,
        ]);
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'User status update failed.'];
        }

        $this->recordLog($actor, 'user', 'Set user status: ' . $record['useri'] . ' => ' . $status);

        return ['ok' => true, 'message' => 'User status updated.'];
    }

    public function userForEdit(int $userId): ?array
    {
        $row = $this->db->table('lms_usp')
            ->select('lms_usp.*, lms_emp.*')
            ->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id', 'left')
            ->where('lms_usp.u_id', $userId)
            ->where('lms_usp.u_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function companies(): array
    {
        return $this->db->table('lms_company')
            ->select('com_id, com_code, com_name_eng, com_name_th')
            ->where('com_isDelete', '0')
            ->where('com_status', '1')
            ->orderBy('com_code', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function departments(): array
    {
        return $this->db->table('lms_depart')
            ->select('dep_id, com_id, dep_name_en, dep_name_th')
            ->where('dep_isDelete', '0')
            ->where('dep_status', '1')
            ->orderBy('dep_name_en', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function groups(): array
    {
        return $this->db->table('lms_usp_gp')
            ->select('ug_id, ug_code, ug_name_en, ug_name_th')
            ->where('ug_isDelete', '0')
            ->where('ug_status', '1')
            ->orderBy('ug_name_en', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function unlockByUserId(int $userId, array $actor): array
    {
        $target = $this->userById($userId);
        if (! $target) {
            return ['ok' => false, 'message' => 'User not found'];
        }

        $this->db->table('lms_usp')
            ->where('u_id', $userId)
            ->where('u_isDelete', '0')
            ->update([
                'login' => '1',
                'u_lockdate' => '0000-00-00 00:00:00',
                'u_modifiedby' => (string) ($actor['u_id'] ?? ''),
                'u_modifieddate' => date('Y-m-d H:i:s'),
            ]);

        $this->recordLog($actor, 'user', 'Unlock user: ' . $target['useri']);

        return ['ok' => true, 'message' => 'Unlocked ' . $target['useri']];
    }

    public function resetPasswordByUserId(int $userId, array $actor): array
    {
        $target = $this->userById($userId);
        if (! $target) {
            return ['ok' => false, 'message' => 'User not found'];
        }

        $password = $this->temporaryPassword();
        $expires = (new \DateTimeImmutable('+90 days'))->format('Y-m-d H:i:s');

        $this->db->table('lms_usp')
            ->where('u_id', $userId)
            ->where('u_isDelete', '0')
            ->update([
                'userp' => hash('sha256', $password),
                'login' => '1',
                'firsttime' => 1,
                'expiredate' => $expires,
                'u_lockdate' => '0000-00-00 00:00:00',
                'u_modifiedby' => (string) ($actor['u_id'] ?? ''),
                'u_modifieddate' => date('Y-m-d H:i:s'),
            ]);

        $this->recordLog($actor, 'user', 'Reset password: ' . $target['useri']);
        $mail = $this->sendPasswordResetEmail($target, $password);

        return [
            'ok' => true,
            'message' => 'Password reset for ' . $target['useri'],
            'temporary_password' => $password,
            'email_sent' => $mail['sent'],
            'email_error' => $mail['error'],
        ];
    }

    public function userById(int $userId): ?array
    {
        $row = $this->db->table('lms_usp')
            ->select('lms_usp.u_id, lms_usp.useri, lms_usp.login, lms_usp.u_lockdate, lms_usp.firsttime, lms_usp.emp_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_emp.email, lms_emp.lang')
            ->select('lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->where('lms_usp.u_id', $userId)
            ->where('lms_usp.u_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function payload(array $input, ?int $userId = null, ?int $employeeId = null): array
    {
        $employeeCode = trim((string) ($input['emp_c'] ?? ''));
        $username = trim((string) ($input['useri'] ?? ''));
        $firstEn = trim((string) ($input['fname_en'] ?? ''));
        $lastEn = trim((string) ($input['lname_en'] ?? ''));
        $firstTh = trim((string) ($input['fname_th'] ?? $firstEn));
        $lastTh = trim((string) ($input['lname_th'] ?? $lastEn));
        $companyId = (int) ($input['com_id'] ?? 0);
        $groupId = (int) ($input['ug_id'] ?? 0);

        if ($employeeCode === '' || $username === '' || $firstEn === '' || $lastEn === '' || $companyId <= 0 || $groupId <= 0) {
            return ['ok' => false, 'message' => 'Employee code, username, English name, company, and user group are required.'];
        }

        $status = (int) ($input['status'] ?? 1);
        $status = in_array($status, [0, 1], true) ? $status : 1;
        $lang = trim((string) ($input['lang'] ?? 'english')) ?: 'english';

        return [
            'ok' => true,
            'employee' => [
                'emp_c' => $employeeCode,
                'prefix_th' => trim((string) ($input['prefix_th'] ?? '')),
                'fname_th' => $firstTh,
                'lname_th' => $lastTh,
                'fullname_th' => trim($firstTh . ' ' . $lastTh),
                'prefix_en' => trim((string) ($input['prefix_en'] ?? '')),
                'fname_en' => $firstEn,
                'lname_en' => $lastEn,
                'fullname_en' => trim($firstEn . ' ' . $lastEn),
                'address_en' => (string) ($input['address_en'] ?? ''),
                'gender' => (string) ($input['gender'] ?? ''),
                'birthdate' => (string) ($input['birthdate'] ?? ''),
                'phone' => (string) ($input['phone'] ?? ''),
                'work_phone' => (string) ($input['work_phone'] ?? ''),
                'emp_nationality' => (string) ($input['emp_nationality'] ?? ''),
                'address_th' => (string) ($input['address_th'] ?? ''),
                'employ_date' => $this->dateOrNull($input['employ_date'] ?? null),
                'depart_date' => $this->dateOrNull($input['depart_date'] ?? null),
                'status' => (string) $status,
                'email' => trim((string) ($input['email'] ?? '')),
                'is_manager' => (int) ($input['is_manager'] ?? 0),
                'emp_manage_a' => (string) ($input['emp_manage_a'] ?? ''),
                'emp_manage_b' => (string) ($input['emp_manage_b'] ?? ''),
                'lang' => $lang,
                'com_id' => $companyId,
            ],
            'login' => [
                'useri' => $username,
                'dep_id' => (int) ($input['dep_id'] ?? 0),
                'posi_id' => (int) ($input['posi_id'] ?? 0),
                'ug_id' => $groupId,
                'u_status' => $status,
                'login' => (string) $status,
            ],
        ];
    }

    private function dateOrNull($value): string
    {
        $value = trim((string) $value);
        return $value === '' ? '0000-00-00' : $value;
    }

    private function temporaryPassword(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $password;
    }

    private function sendPasswordResetEmail(array $target, string $password): array
    {
        $email = trim((string) ($target['email'] ?? ''));
        if ($email === '') {
            $result = ['sent' => false, 'error' => 'Target user has no email address.'];
            $this->recordEmailLog($target, $result, 2);
            return $result;
        }

        $setting = $this->db->table('lms_setting_mail')->where('sm_id', 1)->get()->getRowArray();
        $template = $this->db->table('lms_sendmail_form')
            ->where('smf_type', 2)
            ->where('smf_show', '1')
            ->get()
            ->getRowArray();
        if (! $setting || ! $template) {
            $result = ['sent' => false, 'error' => 'Email setting or reset-password template not found.'];
            $this->recordEmailLog($target, $result, 2);
            return $result;
        }

        $lang = (string) ($target['lang'] ?? 'english');
        $subject = $lang === 'thai' ? ($template['smf_subject_th'] ?: $template['smf_subject_en']) : ($template['smf_subject_en'] ?: $template['smf_subject_th']);
        $body = $lang === 'thai' ? ($template['smf_message_th'] ?: $template['smf_message_en']) : ($template['smf_message_en'] ?: $template['smf_message_th']);
        $replacements = [
            '#fullname' => $lang === 'thai' ? ($target['fullname_th'] ?: $target['fullname_en']) : ($target['fullname_en'] ?: $target['fullname_th']),
            '#username' => (string) ($target['useri'] ?? ''),
            '#email' => $email,
            '#password' => $password,
            '#companyname' => (string) ($target['com_code'] ?: ($target['com_name_eng'] ?? '')),
            '#coursename' => '',
            '#link_frontend' => site_url('login'),
            '#date' => date('d F Y'),
            '#time' => date('H:i'),
            '#image' => '',
        ];
        $subject = strtr($subject, $replacements);
        $body = strtr($body, $replacements);

        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = (string) $setting['sm_host'];
            $mail->Port = (int) $setting['sm_port'];
            $mail->SMTPAuth = filter_var($setting['sm_smtpauth'], FILTER_VALIDATE_BOOLEAN);
            $mail->Username = (string) $setting['sm_username'];
            $mail->Password = (string) $setting['sm_password'];
            $mail->Timeout = 8;
            if ((int) $setting['sm_port'] === 587) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ((int) $setting['sm_port'] === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }
            $mail->setFrom((string) $setting['sm_emailsender'], (string) $setting['sm_sender']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body)));
            $mail->send();

            $result = ['sent' => true, 'error' => ''];
        } catch (MailException|\Throwable $exception) {
            $result = ['sent' => false, 'error' => $exception->getMessage()];
        }

        $this->recordEmailLog($target, $result, 2, ['subject' => $subject, 'to' => $email]);
        return $result;
    }

    private function recordEmailLog(array $target, array $result, int $type, array $extra = []): void
    {
        if (! $this->db->tableExists('lms_lg_email')) {
            return;
        }

        $this->db->table('lms_lg_email')->insert([
            'lgm_date' => date('Y-m-d'),
            'lgm_send' => 1,
            'lgm_send_complete' => $result['sent'] ? 1 : 0,
            'lgm_send_error' => $result['sent'] ? 0 : 1,
            'lgm_json' => json_encode([
                'type' => $type,
                'u_id' => $target['u_id'] ?? null,
                'emp_id' => $target['emp_id'] ?? null,
                'username' => $target['useri'] ?? '',
                'email' => $target['email'] ?? '',
                'sent' => $result['sent'],
                'error' => $result['error'],
            ] + $extra, JSON_UNESCAPED_UNICODE),
        ]);
    }

    private function recordLog(array $actor, string $type, string $message): void
    {
        $this->db->table('lms_lg')->insert([
            'log_type' => $type,
            'emp_id' => $actor['emp_id'] ?? null,
            'massage' => $message,
            'ip' => service('request')->getIPAddress(),
            'device' => substr((string) service('request')->getUserAgent(), 0, 250),
            'log_time' => date('Y-m-d H:i:s'),
        ]);
    }
}
