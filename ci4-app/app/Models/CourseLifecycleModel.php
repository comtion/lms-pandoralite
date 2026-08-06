<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseLifecycleModel extends Model
{
    private const TRANSITIONS = [
        'draft' => ['submitted', 'archived'], 'submitted' => ['reviewing', 'draft'],
        'reviewing' => ['approved', 'rejected'], 'rejected' => ['draft', 'submitted', 'archived'],
        'approved' => ['scheduled', 'published', 'draft', 'archived'],
        'scheduled' => ['published', 'closed', 'archived'], 'published' => ['closed', 'archived'],
        'closed' => ['published', 'archived'], 'archived' => ['draft'],
    ];

    public function detail(int $courseId, array $user): ?array
    {
        $course = $this->scopedCourse($courseId, $user);
        if (! $course) return null;
        $row = $this->db->table('lms_course_lifecycle')->where('cos_id', $courseId)->get()->getRowArray();
        if (! $row) {
            $row = ['cos_id' => $courseId, 'com_id' => (int) $course['com_id'], 'lifecycle_status' => 'draft', 'version_no' => 1, 'updated_at' => date('Y-m-d H:i:s')];
            $this->db->table('lms_course_lifecycle')->insert($row);
        }
        $row['course'] = $course;
        $row['checklist'] = $this->checklist($course);
        $row['history'] = $this->db->table('lms_course_lifecycle_history')->where(['cos_id' => $courseId, 'com_id' => (int) $course['com_id']])->orderBy('changed_at', 'DESC')->limit(100)->get()->getResultArray();
        $row['allowed_transitions'] = self::TRANSITIONS[$row['lifecycle_status']] ?? [];
        return $row;
    }

    public function transition(int $courseId, array $user, string $to, string $reason): array
    {
        $this->db->transBegin();
        try {
            $course = $this->scopedCourse($courseId, $user);
            if (! $course) throw new \RuntimeException('Course not found.');
            $current = $this->db->query('SELECT * FROM lms_course_lifecycle WHERE cos_id=? FOR UPDATE', [$courseId])->getRowArray();
            if (! $current) {
                $this->db->table('lms_course_lifecycle')->insert(['cos_id' => $courseId, 'com_id' => (int) $course['com_id'], 'lifecycle_status' => 'draft', 'version_no' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                $current = $this->db->query('SELECT * FROM lms_course_lifecycle WHERE cos_id=? FOR UPDATE', [$courseId])->getRowArray();
            }
            $from = $current['lifecycle_status'];
            if (! in_array($to, self::TRANSITIONS[$from] ?? [], true)) throw new \InvalidArgumentException("Invalid lifecycle transition: {$from} to {$to}.");
            $checklist = $this->checklist($course);
            if (in_array($to, ['submitted', 'approved', 'scheduled', 'published'], true) && ! $checklist['valid']) {
                throw new \InvalidArgumentException('Course is not ready: ' . implode(', ', $checklist['errors']));
            }
            if ($to === 'rejected' && trim($reason) === '') throw new \InvalidArgumentException('Rejection reason is required.');
            $now = date('Y-m-d H:i:s'); $actor = (string) $user['u_id'];
            $update = ['lifecycle_status' => $to, 'updated_at' => $now];
            if ($to === 'submitted') $update += ['submitted_by' => $actor, 'submitted_at' => $now];
            if (in_array($to, ['approved', 'rejected'], true)) $update += ['reviewed_by' => $actor, 'reviewed_at' => $now, 'rejection_reason' => $to === 'rejected' ? $reason : null];
            if ($to === 'published') $update += ['published_by' => $actor, 'published_at' => $now];
            if ($to === 'closed') $update['closed_at'] = $now;
            if ($to === 'archived') $update += ['archived_by' => $actor, 'archived_at' => $now];
            if ($to === 'draft' && $from !== 'draft') $update['version_no'] = (int) $current['version_no'] + 1;
            $this->db->table('lms_course_lifecycle')->where(['cos_id' => $courseId, 'com_id' => (int) $course['com_id']])->update($update);
            $this->db->table('lms_course_lifecycle_history')->insert([
                'cos_id' => $courseId, 'com_id' => (int) $course['com_id'], 'from_status' => $from, 'to_status' => $to,
                'version_no' => $update['version_no'] ?? (int) $current['version_no'], 'reason' => $reason,
                'changed_by' => $actor, 'changed_at' => $now,
            ]);
            $owner = $this->db->table('lms_usp')->select('emp_id')->where('u_id', $course['cos_createby'])->get()->getRowArray();
            if ($owner) {
                (new NotificationCenterModel())->create((int) $course['com_id'], (int) $owner['emp_id'], 'course_lifecycle', 'Course status updated', "Course {$course['ccode']} is now {$to}.", "managecourse/courses_all/{$courseId}/edit", $courseId, $to === 'rejected' ? 'high' : 'normal');
            }
            $this->db->transCommit();
            return ['ok' => true, 'status' => $to];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function scopedCourse(int $courseId, array $user): ?array
    {
        $builder = $this->db->table('lms_cos')->where(['cos_id' => $courseId, 'cos_isDelete' => '0']);
        if ((string) ($user['ug_viewdata'] ?? '') !== '1' && (string) ($user['u_id'] ?? '') !== '1') $builder->where('com_id', (int) ($user['com_id'] ?? 0));
        return $builder->get()->getRowArray();
    }

    private function checklist(array $course): array
    {
        $errors = [];
        if (trim((string) ($course['cname_th'] ?? '')) === '' && trim((string) ($course['cname_eng'] ?? '')) === '' && trim((string) ($course['cname_jp'] ?? '')) === '') $errors[] = 'course title';
        if (empty($course['cos_pic'])) $errors[] = 'cover image';
        if ($this->db->table('lms_les')->where(['cos_id' => $course['cos_id'], 'les_isDelete' => '0'])->countAllResults() === 0) $errors[] = 'active lesson';
        return ['valid' => $errors === [], 'errors' => $errors];
    }
}
