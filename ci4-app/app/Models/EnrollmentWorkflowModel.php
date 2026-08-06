<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentWorkflowModel extends Model
{
    public function policy(int $courseId, array $user): ?array
    {
        $course = $this->course($courseId, $user);
        if (! $course) return null;
        return $this->db->table('lms_enrollment_policies')->where(['cos_id' => $courseId, 'com_id' => $course['com_id']])->get()->getRowArray()
            ?? ['cos_id' => $courseId, 'com_id' => $course['com_id'], 'enrollment_mode' => 'approval', 'capacity' => null, 'waitlist_enabled' => 1, 'allow_reenroll' => 0];
    }

    public function savePolicy(int $courseId, array $user, array $input): bool
    {
        $course = $this->course($courseId, $user);
        if (! $course) return false;
        $mode = in_array($input['enrollment_mode'] ?? '', ['open', 'approval', 'assigned', 'closed'], true) ? $input['enrollment_mode'] : 'approval';
        return $this->db->table('lms_enrollment_policies')->replace([
            'cos_id' => $courseId, 'com_id' => (int) $course['com_id'], 'enrollment_mode' => $mode,
            'capacity' => max(0, (int) ($input['capacity'] ?? 0)) ?: null,
            'waitlist_enabled' => isset($input['waitlist_enabled']) ? 1 : 0,
            'starts_at' => $this->dateTime($input['starts_at'] ?? null), 'expires_at' => $this->dateTime($input['expires_at'] ?? null),
            'allow_reenroll' => isset($input['allow_reenroll']) ? 1 : 0,
            'updated_by' => (string) $user['u_id'], 'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function request(int $courseId, array $user, int $employeeId, string $type = 'self'): array
    {
        $this->db->transBegin();
        try {
            $course = $this->course($courseId, $user);
            if (! $course) throw new \RuntimeException('Course not found.');
            $policy = $this->db->query('SELECT * FROM lms_enrollment_policies WHERE cos_id=? FOR UPDATE', [$courseId])->getRowArray()
                ?? ['enrollment_mode' => 'approval', 'capacity' => null, 'waitlist_enabled' => 1, 'starts_at' => null, 'expires_at' => null];
            if ($type === 'self' && $employeeId !== (int) $user['emp_id']) throw new \RuntimeException('Invalid learner identity.');
            if ($policy['enrollment_mode'] === 'closed' || ($policy['enrollment_mode'] === 'assigned' && $type === 'self')) throw new \RuntimeException('Enrollment is not open for self-service.');
            $now = date('Y-m-d H:i:s');
            if (! empty($policy['starts_at']) && $policy['starts_at'] > $now) throw new \RuntimeException('Enrollment has not opened.');
            if (! empty($policy['expires_at']) && $policy['expires_at'] < $now) throw new \RuntimeException('Enrollment has closed.');
            $active = $this->db->table('lms_enrollment_requests')->where(['com_id' => $course['com_id'], 'cos_id' => $courseId, 'emp_id' => (string) $employeeId])->whereIn('status', ['pending', 'approved', 'waitlisted'])->countAllResults();
            if ($active > 0) throw new \RuntimeException('An active enrollment request already exists.');
            $status = $policy['enrollment_mode'] === 'open' || $type === 'assigned' ? 'approved' : 'pending';
            if ($status === 'approved' && $this->isFull($courseId, $policy, $course)) $status = $policy['waitlist_enabled'] ? 'waitlisted' : 'capacity_full';
            $this->db->table('lms_enrollment_requests')->insert([
                'com_id' => (int) $course['com_id'], 'cos_id' => $courseId, 'emp_id' => (string) $employeeId,
                'request_type' => $type, 'status' => $status, 'requested_by' => (string) $user['u_id'], 'requested_at' => $now,
                'starts_at' => $policy['starts_at'] ?? null, 'expires_at' => $policy['expires_at'] ?? null, 'created_at' => $now, 'updated_at' => $now,
            ]);
            $requestId = (int) $this->db->insertID();
            if ($status === 'approved') $this->activateEnrollment($courseId, $employeeId, $user);
            if ($status === 'waitlisted') $this->joinWaitlist($courseId, (int) $course['com_id'], $employeeId);
            $this->db->transCommit();
            return ['ok' => true, 'request_id' => $requestId, 'status' => $status];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    public function pending(array $user, int $limit = 100): array
    {
        return $this->db->table('lms_enrollment_requests r')->select('r.*,c.ccode,c.cname_th,c.cname_eng,e.emp_c,e.fullname_th,e.fullname_en')
            ->join('lms_cos c', 'c.cos_id=r.cos_id')->join('lms_emp e', 'e.emp_id=CAST(r.emp_id AS UNSIGNED)', 'left')
            ->where(['r.com_id' => (int) $user['com_id'], 'r.status' => 'pending'])->orderBy('r.requested_at', 'ASC')->limit(min(max($limit, 1), 500))->get()->getResultArray();
    }

    public function decide(int $requestId, array $user, string $decision, string $reason): array
    {
        $this->db->transBegin();
        try {
            $request = $this->db->query("SELECT * FROM lms_enrollment_requests WHERE request_id=? AND com_id=? AND status='pending' FOR UPDATE", [$requestId, (int) $user['com_id']])->getRowArray();
            if (! $request) throw new \RuntimeException('Pending request not found.');
            if (! in_array($decision, ['approve', 'reject'], true)) throw new \InvalidArgumentException('Invalid decision.');
            if ($decision === 'reject' && trim($reason) === '') throw new \InvalidArgumentException('Rejection reason is required.');
            $course = $this->db->table('lms_cos')->where(['cos_id' => $request['cos_id'], 'com_id' => $user['com_id']])->get()->getRowArray();
            $policy = $this->policy((int) $request['cos_id'], $user) ?? [];
            $status = $decision === 'approve' ? 'approved' : 'rejected';
            if ($status === 'approved' && $this->isFull((int) $request['cos_id'], $policy, $course)) $status = ! empty($policy['waitlist_enabled']) ? 'waitlisted' : 'capacity_full';
            $now = date('Y-m-d H:i:s');
            $this->db->table('lms_enrollment_requests')->where('request_id', $requestId)->update(['status' => $status, 'reviewed_by' => (string) $user['u_id'], 'reviewed_at' => $now, 'decision_reason' => $reason, 'updated_at' => $now]);
            if ($status === 'approved') $this->activateEnrollment((int) $request['cos_id'], (int) $request['emp_id'], $user);
            if ($status === 'waitlisted') $this->joinWaitlist((int) $request['cos_id'], (int) $user['com_id'], (int) $request['emp_id']);
            (new NotificationCenterModel())->create((int) $user['com_id'], (int) $request['emp_id'], 'enrollment', 'Enrollment ' . $status, $reason, 'coursemain/detail/' . (int) $request['cos_id'], (int) $request['cos_id'], $status === 'rejected' ? 'high' : 'normal');
            $this->db->transCommit();
            return ['ok' => true, 'status' => $status];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function course(int $courseId, array $user): ?array
    {
        $builder = $this->db->table('lms_cos')->where(['cos_id' => $courseId, 'cos_isDelete' => '0']);
        if ((string) ($user['ug_viewdata'] ?? '') !== '1' && (string) ($user['u_id'] ?? '') !== '1') $builder->where('com_id', (int) $user['com_id']);
        return $builder->get()->getRowArray();
    }

    private function isFull(int $courseId, array $policy, array $course): bool
    {
        $capacity = (int) ($policy['capacity'] ?? 0) ?: (int) ($course['seat_count'] ?? 0);
        return $capacity > 0 && $this->db->table('lms_cos_enroll')->where(['cos_id' => $courseId, 'cosen_status' => '1', 'cosen_isDelete' => '0'])->countAllResults() >= $capacity;
    }

    private function activateEnrollment(int $courseId, int $employeeId, array $actor): void
    {
        if ($this->db->table('lms_cos_enroll')->where(['cos_id' => $courseId, 'emp_id' => $employeeId, 'cosen_isDelete' => '0'])->countAllResults() > 0) return;
        $now = date('Y-m-d H:i:s'); $by = (string) $actor['u_id'];
        $this->db->table('lms_cos_enroll')->insert([
            'cosen_lang' => 'eng', 'cos_id' => $courseId, 'emp_id' => $employeeId, 'cosen_score' => 0, 'cosen_score_per' => 0,
            'cosen_grade' => '', 'cosen_reward' => 0, 'cosen_pfm' => 0, 'cosen_timerequest' => $now, 'emp_approver_a' => 0,
            'cosen_enroll_status_a' => 0, 'emp_approver_b' => 0, 'cosen_enroll_status_b' => 0, 'cosen_status' => 1,
            'cosen_status_sub' => 0, 'cosen_cancelnote' => '', 'cosen_firsttime' => '0000-00-00 00:00:00',
            'cosen_finishtime' => '0000-00-00 00:00:00', 'cosen_rating' => 0, 'cosen_isDelete' => 0,
            'cosen_createby' => $by, 'cosen_createdate' => $now, 'cosen_modifiedby' => $by, 'cosen_modifieddate' => $now, 'cosen_round' => 1,
        ]);
        $this->db->table('lms_log_enroll')->insert(['cosen_id' => (int) $this->db->insertID()]);
    }

    private function joinWaitlist(int $courseId, int $companyId, int $employeeId): void
    {
        $maximum = $this->db->table('lms_enrollment_waitlist')->selectMax('position_no')->where(['com_id' => $companyId, 'cos_id' => $courseId])->get()->getRowArray();
        $position = (int) ($maximum['position_no'] ?? 0) + 1;
        $this->db->table('lms_enrollment_waitlist')->ignore(true)->insert(['com_id' => $companyId, 'cos_id' => $courseId, 'emp_id' => (string) $employeeId, 'position_no' => $position, 'status' => 'waiting', 'joined_at' => date('Y-m-d H:i:s')]);
    }

    private function dateTime(mixed $value): ?string
    {
        if (! $value) return null;
        $date = \DateTime::createFromFormat('Y-m-d\TH:i', (string) $value) ?: \DateTime::createFromFormat('Y-m-d H:i:s', (string) $value);
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }
}
