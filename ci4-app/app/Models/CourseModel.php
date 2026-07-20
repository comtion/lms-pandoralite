<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $returnType = 'array';

    public function courseGroups(array $user, string $lang, int $limit = 100): array
    {
        $rows = $this->db->table('lms_cog')
            ->select('lms_cog.*, lms_company.com_name_th, lms_company.com_name_eng, COUNT(lms_cosincg.course_id) AS course_count')
            ->join('lms_company', 'lms_company.com_id = lms_cog.com_id', 'left')
            ->join('lms_cosincg', 'lms_cosincg.cg_id = lms_cog.cg_id AND lms_cosincg.status_cg = 1', 'left')
            ->where('lms_cog.cg_isDelete', '0')
            ->groupBy('lms_cog.cg_id')
            ->orderBy('lms_cog.c_date', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn ($row) => $this->decorateCourseGroup($row, $lang), $rows);
    }

    public function courseGroupForEdit(int $groupId): ?array
    {
        $row = $this->db->table('lms_cog')
            ->where('cg_id', $groupId)
            ->where('cg_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function createCourseGroup(array $input, array $user): array
    {
        $payload = $this->courseGroupPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'];
        $data += [
            'cgthumb' => '',
            'c_date' => date('Y-m-d H:i:s'),
            'c_by' => $this->actorCode($user),
            'u_date' => date('Y-m-d H:i:s'),
            'u_by' => $this->actorCode($user),
            'cg_isDelete' => 0,
        ];

        $this->db->table('lms_cog')->insert($data);
        $groupId = (int) $this->db->insertID();
        $this->recordLog($user, 'course_group', 'Create course group: ' . $data['cgcode'] . ' (' . $groupId . ')');

        return ['ok' => true, 'message' => 'Course group created.', 'group_id' => $groupId];
    }

    public function updateCourseGroup(int $groupId, array $input, array $user): array
    {
        $current = $this->courseGroupForEdit($groupId);
        if (! $current) {
            return ['ok' => false, 'message' => 'Course group not found.'];
        }
        if ($this->isApprovedCourseGroupLocked($current, $user)) {
            return ['ok' => false, 'message' => 'Approved course groups can only be edited by Super Admin.'];
        }

        $payload = $this->courseGroupPayload($input, $groupId);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'];
        $data['u_date'] = date('Y-m-d H:i:s');
        $data['u_by'] = $this->actorCode($user);

        $this->db->table('lms_cog')->where('cg_id', $groupId)->update($data);
        $this->recordLog($user, 'course_group', 'Update course group: ' . $data['cgcode'] . ' (' . $groupId . ')');

        return ['ok' => true, 'message' => 'Course group saved.'];
    }

    public function setCourseGroupStatus(int $groupId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true) || ! $this->courseGroupForEdit($groupId)) {
            return ['ok' => false, 'message' => 'Invalid course group or status.'];
        }

        $this->db->table('lms_cog')->where('cg_id', $groupId)->update([
            'cg_status' => $status,
            'u_date' => date('Y-m-d H:i:s'),
            'u_by' => $this->actorCode($user),
        ]);
        $this->recordLog($user, 'course_group', 'Set course group status: ' . $groupId . ' => ' . $status);

        return ['ok' => true, 'message' => 'Course group status updated.'];
    }

    public function setCourseGroupApproval(int $groupId, int $approval, string $rejectReason, array $user): array
    {
        if (! in_array($approval, [0, 1, 2], true) || ! $this->courseGroupForEdit($groupId)) {
            return ['ok' => false, 'message' => 'Invalid course group or approval state.'];
        }

        $this->db->table('lms_cog')->where('cg_id', $groupId)->update([
            'cg_approve' => $approval,
            'cg_reject' => $approval === 0 ? $rejectReason : '',
            'u_date' => date('Y-m-d H:i:s'),
            'u_by' => $this->actorCode($user),
        ]);
        $this->recordLog($user, 'course_group', 'Set course group approval: ' . $groupId . ' => ' . $approval);

        return ['ok' => true, 'message' => $approval === 1 ? 'Course group approved.' : 'Course group approval updated.'];
    }

    public function archiveCourseGroup(int $groupId, array $user): array
    {
        $current = $this->courseGroupForEdit($groupId);
        if (! $current) {
            return ['ok' => false, 'message' => 'Course group not found.'];
        }
        if ($this->isApprovedCourseGroupLocked($current, $user)) {
            return ['ok' => false, 'message' => 'Approved course groups can only be deleted by Super Admin.'];
        }

        $this->db->table('lms_cog')->where('cg_id', $groupId)->update([
            'cg_isDelete' => 1,
            'u_date' => date('Y-m-d H:i:s'),
            'u_by' => $this->actorCode($user),
        ]);
        $this->recordLog($user, 'course_group', 'Archive course group: ' . $groupId);

        return ['ok' => true, 'message' => 'Course group archived.'];
    }

    public function isSuperAdmin(array $user): bool
    {
        if ((int) ($user['ug_id'] ?? 0) === 1) {
            return true;
        }

        $role = strtolower(trim((string) (($user['ug_name_en'] ?? '') . ' ' . ($user['ug_name_th'] ?? ''))));

        return str_contains($role, 'super admin') || str_contains($role, 'superadmin');
    }

    public function isApprovedCourseGroupLocked(array $group, array $user): bool
    {
        return (string) ($group['cg_approve'] ?? '') === '1' && ! $this->isSuperAdmin($user);
    }

    public function adminCourses(array $user, string $lang, int $limit = 100): array
    {
        $builder = $this->baseCourseBuilder($lang)
            ->select('COUNT(lms_cos_enroll.cosen_id) AS enrolled_count')
            ->join('lms_cos_enroll', 'lms_cos_enroll.cos_id = lms_cos.cos_id AND lms_cos_enroll.cosen_isDelete = 0', 'left')
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos.cos_id')
            ->orderBy('lms_cos.cos_id', 'DESC')
            ->limit($limit);

        return array_map(fn ($row) => $this->decorateCourse($row, $lang), $builder->get()->getResultArray());
    }

    public function publicCourses(array $user, string $lang, int $limit = 100): array
    {
        $builder = $this->baseCourseBuilder($lang)
            ->select('COUNT(lms_cos_enroll.cosen_id) AS enrolled_count')
            ->join('lms_cos_enroll', 'lms_cos_enroll.cos_id = lms_cos.cos_id AND lms_cos_enroll.cosen_isDelete = 0', 'left')
            ->where('lms_cos.cos_approve', '1')
            ->where('lms_cos.cos_public', '1')
            ->where('lms_cos.cos_status', '1')
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos.cos_id')
            ->orderBy('lms_cos.cos_id', 'DESC')
            ->limit($limit);

        return array_map(fn ($row) => $this->decorateCourse($row, $lang), $builder->get()->getResultArray());
    }

    public function myCourses(array $user, string $lang, int $limit = 100): array
    {
        $builder = $this->baseCourseBuilder($lang)
            ->select('lms_cos_enroll.cosen_id, lms_cos_enroll.cosen_status, lms_cos_enroll.cosen_status_sub, CAST(lms_cos_enroll.cosen_firsttime AS CHAR) AS cosen_firsttime, CAST(lms_cos_enroll.cosen_finishtime AS CHAR) AS cosen_finishtime, lms_cos_enroll.cosen_score_per')
            ->join('lms_cos_enroll', 'lms_cos_enroll.cos_id = lms_cos.cos_id')
            ->where('lms_cos_enroll.emp_id', $user['emp_id'] ?? 0)
            ->where('lms_cos_enroll.cosen_isDelete', '0')
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos_enroll.cosen_id')
            ->orderBy('lms_cos_enroll.cosen_id', 'DESC')
            ->limit($limit);

        return array_map(fn ($row) => $this->decorateCourse($row, $lang, true), $builder->get()->getResultArray());
    }

    public function dashboardCounts(array $user): array
    {
        return [
            'groups' => $this->db->table('lms_cog')->where('cg_isDelete', '0')->countAllResults(),
            'courses' => $this->db->table('lms_cos')->where('cos_isDelete', '0')->countAllResults(),
            'public' => $this->db->table('lms_cos')
                ->where('cos_isDelete', '0')
                ->where('cos_public', '1')
                ->where('cos_approve', '1')
                ->countAllResults(),
            'my' => $this->db->table('lms_cos_enroll')
                ->where('emp_id', $user['emp_id'] ?? 0)
                ->where('cosen_isDelete', '0')
                ->countAllResults(),
        ];
    }

    public function courseForEdit(int $courseId): ?array
    {
        $row = $this->db->table('lms_cos')
            ->where('cos_id', $courseId)
            ->where('cos_isDelete', '0')
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

    public function courseTypes(?int $companyId = null): array
    {
        $builder = $this->db->table('lms_typecos')
            ->select('tc_id, com_id, tc_name_en, tc_name_th')
            ->where('tc_status', '1')
            ->orderBy('tc_name_en', 'ASC');

        if ($companyId) {
            $builder->where('com_id', $companyId);
        }

        return $builder->get()->getResultArray();
    }

    public function departments(?int $companyId = null): array
    {
        $builder = $this->db->table('lms_depart')
            ->select('dep_id, com_id, dep_name_th, dep_name_en')
            ->where('dep_isDelete', '0')
            ->orderBy('dep_name_en', 'ASC')
            ->orderBy('dep_name_th', 'ASC');

        if ($companyId) {
            $builder->where('com_id', $companyId);
        }

        return $builder->get()->getResultArray();
    }

    public function activeLearners(?int $companyId = null, int $limit = 500): array
    {
        $builder = $this->db->table('lms_emp')
            ->select('lms_emp.emp_id, lms_emp.email, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri, lms_usp.dep_id')
            ->join('lms_usp', 'lms_usp.emp_id = lms_emp.emp_id')
            ->where('lms_emp.emp_isDelete', '0')
            ->where('lms_emp.status', '1')
            ->where('lms_usp.u_isDelete', '0')
            ->groupStart()
                ->where('lms_usp.inactivedate', '0000-00-00')
                ->orWhere('lms_usp.inactivedate', '0000-00-00 00:00:00')
                ->orWhere('lms_usp.inactivedate >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->orderBy('lms_emp.fullname_en', 'ASC')
            ->orderBy('lms_emp.emp_id', 'ASC')
            ->limit($limit);

        if ($companyId) {
            $builder->where('lms_emp.com_id', $companyId);
        }

        return $builder->get()->getResultArray();
    }

    public function assignableCourseGroups(?int $companyId, string $lang): array
    {
        $builder = $this->db->table('lms_cog')
            ->where('cg_isDelete', '0')
            ->where('cg_status', '1')
            ->orderBy('cgtitle_en', 'ASC')
            ->orderBy('cg_id', 'ASC');

        if ($companyId) {
            $builder->where('com_id', $companyId);
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['title'] = $this->courseGroupTitle($row, $lang);
        }

        return $rows;
    }

    public function courseGroupIds(int $courseId): array
    {
        $rows = $this->db->table('lms_cosincg')
            ->select('cg_id')
            ->where('course_id', $courseId)
            ->where('status_cg', '1')
            ->get()
            ->getResultArray();

        return array_map('intval', array_column($rows, 'cg_id'));
    }

    public function syncCourseGroups(int $courseId, array $groupIds, array $user): array
    {
        $course = $this->courseForEdit($courseId);
        if (! $course) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $groupIds = array_values(array_unique(array_filter(array_map('intval', $groupIds))));
        if ($groupIds !== []) {
            $validRows = $this->db->table('lms_cog')
                ->select('cg_id')
                ->whereIn('cg_id', $groupIds)
                ->where('com_id', (int) $course['com_id'])
                ->where('cg_isDelete', '0')
                ->where('cg_status', '1')
                ->get()
                ->getResultArray();
            $groupIds = array_map('intval', array_column($validRows, 'cg_id'));
        }

        $this->db->transStart();
        $this->db->table('lms_cosincg')
            ->where('course_id', $courseId)
            ->update(['status_cg' => 0]);

        foreach ($groupIds as $groupId) {
            $exists = $this->db->table('lms_cosincg')
                ->where('course_id', $courseId)
                ->where('cg_id', $groupId)
                ->countAllResults();

            if ($exists > 0) {
                $this->db->table('lms_cosincg')
                    ->where('course_id', $courseId)
                    ->where('cg_id', $groupId)
                    ->update(['status_cg' => 1]);
            } else {
                $this->db->table('lms_cosincg')->insert([
                    'course_id' => $courseId,
                    'cg_id' => $groupId,
                    'status_cg' => 1,
                ]);
            }
        }
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'Course group assignment failed.'];
        }

        $this->recordLog($user, 'course_group_assignment', 'Sync course groups for course: ' . $courseId . ' => ' . implode(',', $groupIds));

        return ['ok' => true, 'message' => 'Course groups updated.'];
    }

    public function createCourse(array $input, array $user): array
    {
        $data = $this->coursePayload($input, $user);
        if (! $data['ok']) {
            return $data;
        }

        $payload = $data['payload'];
        $exists = $this->db->table('lms_cos')
            ->where('ccode', $payload['ccode'])
            ->where('cos_isDelete', '0')
            ->countAllResults();

        if ($exists > 0) {
            return ['ok' => false, 'message' => 'Course code already exists.'];
        }

        $now = date('Y-m-d H:i:s');
        $payload += [
            'cos_pic' => '',
            'condition' => '',
            'cos_rating' => 0,
            'cos_approveby' => '',
            'cos_approvedate' => '0000-00-00 00:00:00',
            'cos_isDelete' => 0,
            'cos_createby' => (string) ($user['u_id'] ?? ''),
            'cos_createdate' => $now,
            'cos_modifiedby' => (string) ($user['u_id'] ?? ''),
            'cos_modifieddate' => $now,
            'cos_expire_noti' => '0',
            'is_survey_required' => (int) ($input['is_survey_required'] ?? 0),
        ];

        $this->db->table('lms_cos')->insert($payload);
        $courseId = (int) $this->db->insertID();
        $notification = (new CourseNotificationModel())->saveScheduleForCourse($courseId, $input, $user);
        if (! $notification['ok']) {
            return $notification + ['course_id' => $courseId];
        }
        $this->recordLog($user, 'course', 'Create course: ' . $payload['ccode'] . ' (' . $courseId . ')');

        return ['ok' => true, 'message' => 'Course created.', 'course_id' => $courseId];
    }

    public function updateCourse(int $courseId, array $input, array $user): array
    {
        $course = $this->courseForEdit($courseId);
        if (! $course) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $data = $this->coursePayload($input, $user, $courseId);
        if (! $data['ok']) {
            return $data;
        }

        $payload = $data['payload'];
        $exists = $this->db->table('lms_cos')
            ->where('ccode', $payload['ccode'])
            ->where('cos_id !=', $courseId)
            ->where('cos_isDelete', '0')
            ->countAllResults();

        if ($exists > 0) {
            return ['ok' => false, 'message' => 'Course code already exists.'];
        }

        $payload['cos_modifiedby'] = (string) ($user['u_id'] ?? '');
        $payload['cos_modifieddate'] = date('Y-m-d H:i:s');
        $payload['is_survey_required'] = (int) ($input['is_survey_required'] ?? 0);

        $this->db->table('lms_cos')->where('cos_id', $courseId)->update($payload);
        $notification = (new CourseNotificationModel())->saveScheduleForCourse($courseId, $input, $user);
        if (! $notification['ok']) {
            return $notification + ['course_id' => $courseId];
        }
        $this->recordLog($user, 'course', 'Update course: ' . $payload['ccode'] . ' (' . $courseId . ')');

        return ['ok' => true, 'message' => 'Course updated.'];
    }

    public function setCourseStatus(int $courseId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        $course = $this->courseForEdit($courseId);
        if (! $course) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $this->db->table('lms_cos')
            ->where('cos_id', $courseId)
            ->update([
                'cos_status' => $status,
                'cos_modifiedby' => (string) ($user['u_id'] ?? ''),
                'cos_modifieddate' => date('Y-m-d H:i:s'),
            ]);
        $this->recordLog($user, 'course', 'Set course status: ' . $course['ccode'] . ' => ' . $status);

        return ['ok' => true, 'message' => 'Course status updated.'];
    }

    public function lessonsForCourse(int $courseId, string $lang): array
    {
        $rows = $this->db->table('lms_les')
            ->where('cos_id', $courseId)
            ->where('les_isDelete', '0')
            ->orderBy('les_sequences', 'ASC')
            ->orderBy('les_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'les_name');
            $row['description'] = $this->localized($row, $lang, 'les_info');
        }

        return $rows;
    }

    public function lessonForEdit(int $lessonId): ?array
    {
        $row = $this->db->table('lms_les')
            ->where('les_id', $lessonId)
            ->where('les_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function createLesson(int $courseId, array $input, array $user): array
    {
        if (! $this->courseForEdit($courseId)) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $payload = $this->lessonPayload($input, $user);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'];
        $data['cos_id'] = $courseId;
        if ((int) $data['les_sequences'] <= 0) {
            $last = $this->db->table('lms_les')
                ->selectMax('les_sequences')
                ->where('cos_id', $courseId)
                ->where('les_isDelete', '0')
                ->get()
                ->getRowArray();
            $data['les_sequences'] = ((int) ($last['les_sequences'] ?? 0)) + 1;
        }

        $now = date('Y-m-d H:i:s');
        $data += [
            'les_isDelete' => 0,
            'les_createby' => (string) ($user['u_id'] ?? ''),
            'les_createdate' => $now,
            'les_modifiedby' => (string) ($user['u_id'] ?? ''),
            'les_modifieddate' => $now,
        ];

        $this->db->table('lms_les')->insert($data);
        $lessonId = (int) $this->db->insertID();
        $this->recordLog($user, 'lesson', 'Create lesson: ' . $lessonId . ' of course ' . $courseId);

        return ['ok' => true, 'message' => 'Lesson created.', 'lesson_id' => $lessonId];
    }

    public function updateLesson(int $lessonId, array $input, array $user): array
    {
        $lesson = $this->lessonForEdit($lessonId);
        if (! $lesson) {
            return ['ok' => false, 'message' => 'Lesson not found.'];
        }

        $payload = $this->lessonPayload($input, $user);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'];
        $data['les_modifiedby'] = (string) ($user['u_id'] ?? '');
        $data['les_modifieddate'] = date('Y-m-d H:i:s');

        $this->db->table('lms_les')->where('les_id', $lessonId)->update($data);
        $this->recordLog($user, 'lesson', 'Update lesson: ' . $lessonId);

        return ['ok' => true, 'message' => 'Lesson updated.', 'course_id' => (int) $lesson['cos_id']];
    }

    public function setLessonStatus(int $lessonId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        $lesson = $this->lessonForEdit($lessonId);
        if (! $lesson) {
            return ['ok' => false, 'message' => 'Lesson not found.'];
        }

        $this->db->table('lms_les')
            ->where('les_id', $lessonId)
            ->update([
                'les_status' => $status,
                'les_modifiedby' => (string) ($user['u_id'] ?? ''),
                'les_modifieddate' => date('Y-m-d H:i:s'),
            ]);
        $this->recordLog($user, 'lesson', 'Set lesson status: ' . $lessonId . ' => ' . $status);

        return ['ok' => true, 'message' => 'Lesson status updated.', 'course_id' => (int) $lesson['cos_id']];
    }

    public function mediaForLesson(int $lessonId, string $lang): array
    {
        return $this->lessonMedia($lessonId, $lang);
    }

    public function documentsForLesson(int $lessonId, string $lang): array
    {
        return $this->lessonDocuments($lessonId, $lang);
    }

    public function scormForLesson(int $lessonId): ?array
    {
        return $this->lessonScorm($lessonId);
    }

    public function createLessonMedia(int $lessonId, array $input, array $user): array
    {
        $lesson = $this->lessonForEdit($lessonId);
        if (! $lesson) {
            return ['ok' => false, 'message' => 'Lesson not found.'];
        }

        $payload = $this->mediaPayload($input);
        if (! $payload['ok']) {
            return $payload + ['course_id' => (int) $lesson['cos_id']];
        }

        $data = $payload['payload'];
        $data['lessons_id'] = $lessonId;
        $this->db->table('lms_med')->insert($data);
        $mediaId = (int) $this->db->insertID();
        $this->recordLog($user, 'lesson_media', 'Create lesson media: ' . $mediaId . ' of lesson ' . $lessonId);

        return ['ok' => true, 'message' => 'Lesson media created.', 'course_id' => (int) $lesson['cos_id']];
    }

    public function updateLessonMedia(int $mediaId, array $input, array $user): array
    {
        $media = $this->mediaForEdit($mediaId);
        if (! $media) {
            return ['ok' => false, 'message' => 'Media not found.', 'lesson_id' => 0, 'course_id' => 0];
        }

        $payload = $this->mediaPayload($input);
        if (! $payload['ok']) {
            return $payload + ['lesson_id' => (int) $media['lessons_id'], 'course_id' => (int) $media['cos_id']];
        }

        $this->db->table('lms_med')->where('id', $mediaId)->update($payload['payload']);
        $this->recordLog($user, 'lesson_media', 'Update lesson media: ' . $mediaId);

        return ['ok' => true, 'message' => 'Lesson media updated.', 'lesson_id' => (int) $media['lessons_id'], 'course_id' => (int) $media['cos_id']];
    }

    public function createLessonDocument(int $lessonId, array $input, array $user): array
    {
        $lesson = $this->lessonForEdit($lessonId);
        if (! $lesson) {
            return ['ok' => false, 'message' => 'Lesson not found.'];
        }

        $payload = $this->documentPayload($input);
        if (! $payload['ok']) {
            return $payload + ['course_id' => (int) $lesson['cos_id']];
        }

        $data = $payload['payload'];
        $data['lessons_id'] = $lessonId;
        $this->db->table('lms_fil')->insert($data);
        $documentId = (int) $this->db->insertID();
        $this->recordLog($user, 'lesson_document', 'Create lesson document: ' . $documentId . ' of lesson ' . $lessonId);

        return ['ok' => true, 'message' => 'Lesson document created.', 'course_id' => (int) $lesson['cos_id']];
    }

    public function updateLessonDocument(int $documentId, array $input, array $user): array
    {
        $document = $this->documentForEdit($documentId);
        if (! $document) {
            return ['ok' => false, 'message' => 'Document not found.', 'lesson_id' => 0, 'course_id' => 0];
        }

        $payload = $this->documentPayload($input);
        if (! $payload['ok']) {
            return $payload + ['lesson_id' => (int) $document['lessons_id'], 'course_id' => (int) $document['cos_id']];
        }

        $this->db->table('lms_fil')->where('id', $documentId)->update($payload['payload']);
        $this->recordLog($user, 'lesson_document', 'Update lesson document: ' . $documentId);

        return ['ok' => true, 'message' => 'Lesson document updated.', 'lesson_id' => (int) $document['lessons_id'], 'course_id' => (int) $document['cos_id']];
    }

    public function saveLessonScorm(int $lessonId, array $input, array $user): array
    {
        $lesson = $this->lessonForEdit($lessonId);
        if (! $lesson) {
            return ['ok' => false, 'message' => 'Lesson not found.', 'course_id' => 0];
        }

        if (! empty($input['upload_error'])) {
            return ['ok' => false, 'message' => (string) $input['upload_error'], 'course_id' => (int) $lesson['cos_id']];
        }

        $path = trim((string) ($input['path'] ?? ''));
        if ($path === '') {
            return ['ok' => false, 'message' => 'SCORM path is required.', 'course_id' => (int) $lesson['cos_id']];
        }

        $existing = $this->db->table('lms_scm')
            ->where('lessons_id', $lessonId)
            ->get()
            ->getRowArray();

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'zip') {
            $scormId = $existing ? (int) $existing['id'] : $this->createScormPlaceholder($lessonId);
            $extracted = $this->extractScormZip($path, $lessonId, $scormId);
            if (! $extracted['ok']) {
                return ['ok' => false, 'message' => $extracted['message'], 'course_id' => (int) $lesson['cos_id']];
            }
            $path = $extracted['path'];
            $existing = ['id' => $scormId];
        }

        if ($existing) {
            $this->db->table('lms_scm')->where('id', $existing['id'])->update(['path' => $path]);
            $this->recordLog($user, 'lesson_scorm', 'Update lesson SCORM: ' . $existing['id']);
        } else {
            $this->db->table('lms_scm')->insert(['lessons_id' => $lessonId, 'path' => $path]);
            $this->recordLog($user, 'lesson_scorm', 'Create lesson SCORM: ' . (int) $this->db->insertID() . ' of lesson ' . $lessonId);
        }

        return ['ok' => true, 'message' => 'SCORM path saved.', 'course_id' => (int) $lesson['cos_id']];
    }

    private function createScormPlaceholder(int $lessonId): int
    {
        $this->db->table('lms_scm')->insert(['lessons_id' => $lessonId, 'path' => '']);
        return (int) $this->db->insertID();
    }

    public function courseDetail(int $courseId, array $user, string $lang): ?array
    {
        $row = $this->baseCourseBuilder($lang)
            ->select('lms_cos.cdesc_th, lms_cos.cdesc_eng, lms_cos.cdesc_jp, lms_cos.max_score, lms_cos.goal_score, lms_cos.cos_hour, lms_cos.condition')
            ->select('COUNT(lms_cos_enroll.cosen_id) AS enrolled_count')
            ->join('lms_cos_enroll', 'lms_cos_enroll.cos_id = lms_cos.cos_id AND lms_cos_enroll.cosen_isDelete = 0', 'left')
            ->where('lms_cos.cos_id', $courseId)
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos.cos_id')
            ->get()
            ->getRowArray();

        if (! $row) {
            return null;
        }

        $course = $this->decorateCourse($row, $lang);
        $course['full_description'] = $this->localized($row, $lang, 'cdesc');
        $course['lessons'] = $this->lessons($courseId, $user, $lang);
        $course['quizzes'] = $this->quizzes($courseId, $lang);
        $course['surveys'] = $this->courseSurveys($courseId, $lang);
        $course['documents'] = $this->courseDocuments($courseId, $lang);
        $course['enrollment'] = $this->enrollment($courseId, $user);

        return $course;
    }

    public function enrollCourse(int $courseId, array $user, string $lang): array
    {
        $existing = $this->enrollment($courseId, $user);
        if ($existing) {
            return ['ok' => true, 'cosen_id' => (int) $existing['cosen_id'], 'message' => 'Already enrolled'];
        }

        $course = $this->db->table('lms_cos')
            ->where('cos_id', $courseId)
            ->where('cos_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $course) {
            return ['ok' => false, 'message' => 'Course not found'];
        }

        $activeSeats = $this->db->table('lms_cos_enroll')
            ->where('cos_id', $courseId)
            ->where('cosen_isDelete', '0')
            ->where('cosen_status', '1')
            ->countAllResults();

        if ((int) ($course['seat_count'] ?? 0) > 0 && $activeSeats >= (int) $course['seat_count']) {
            return ['ok' => false, 'message' => 'Course seat limit is full'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->db->table('lms_cos_enroll')->insert([
            'cosen_lang' => $this->courseLang($lang),
            'cos_id' => $courseId,
            'emp_id' => $user['emp_id'] ?? null,
            'cosen_score' => 0,
            'cosen_score_per' => 0,
            'cosen_grade' => '',
            'cosen_reward' => 0,
            'cosen_pfm' => 0,
            'cosen_timerequest' => $now,
            'emp_approver_a' => 0,
            'cosen_enroll_status_a' => 0,
            'emp_approver_b' => 0,
            'cosen_enroll_status_b' => 0,
            'cosen_status' => 1,
            'cosen_status_sub' => 0,
            'cosen_cancelnote' => '',
            'cosen_firsttime' => '0000-00-00 00:00:00',
            'cosen_finishtime' => '0000-00-00 00:00:00',
            'cosen_rating' => 0,
            'cosen_isDelete' => 0,
            'cosen_createby' => (string) ($user['u_id'] ?? ''),
            'cosen_createdate' => $now,
            'cosen_modifiedby' => (string) ($user['u_id'] ?? ''),
            'cosen_modifieddate' => $now,
            'cosen_round' => 1,
        ]);
        $id = (int) $this->db->insertID();
        $this->db->table('lms_log_enroll')->insert(['cosen_id' => $id]);
        $this->db->transComplete();

        return ['ok' => $this->db->transStatus(), 'cosen_id' => $id, 'message' => $this->db->transStatus() ? 'Enrolled' : 'Enroll failed'];
    }

    public function startCourse(int $courseId, array $user, string $lang): array
    {
        $enrollment = $this->enrollment($courseId, $user);
        if (! $enrollment) {
            $created = $this->enrollCourse($courseId, $user, $lang);
            if (! $created['ok']) {
                return $created;
            }
            $enrollment = $this->enrollment($courseId, $user);
        }

        $now = date('Y-m-d H:i:s');
        $update = [
            'cosen_lang' => $this->courseLang($lang),
            'cosen_status' => 1,
            'cosen_status_sub' => (string) ($enrollment['cosen_status_sub'] ?? '') === '1' ? 1 : 2,
            'cosen_modifiedby' => (string) ($user['u_id'] ?? ''),
            'cosen_modifieddate' => $now,
        ];

        if ($this->isEmptyDate((string) ($enrollment['cosen_firsttime'] ?? ''))) {
            $update['cosen_firsttime'] = $now;
        }

        $this->db->table('lms_cos_enroll')
            ->where('cosen_id', $enrollment['cosen_id'])
            ->update($update);

        return ['ok' => true, 'cosen_id' => (int) $enrollment['cosen_id'], 'message' => 'Started'];
    }

    public function lessonDetail(int $lessonId, array $user, string $lang): ?array
    {
        $lesson = $this->db->table('lms_les')
            ->where('les_id', $lessonId)
            ->where('les_status', '1')
            ->where('les_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $lesson) {
            return null;
        }

        $course = $this->courseDetail((int) $lesson['cos_id'], $user, $lang);
        if (! $course) {
            return null;
        }

        $this->startCourse((int) $lesson['cos_id'], $user, $lang);
        $enrollment = $this->enrollment((int) $lesson['cos_id'], $user);

        $lesson['title'] = $this->localized($lesson, $lang, 'les_name');
        $lesson['description'] = $this->localized($lesson, $lang, 'les_info');
        $lesson['media'] = $this->lessonMedia($lessonId, $lang);
        $lesson['documents'] = $this->lessonDocuments($lessonId, $lang);
        $lesson['scorm'] = $this->lessonScorm($lessonId);
        $lesson['course'] = $course;
        $lesson['enrollment'] = $enrollment;
        $lesson['tracking'] = $enrollment ? $this->lessonTracking($lessonId, (int) $enrollment['cosen_id'], $user) : null;

        return $lesson;
    }

    public function markLessonComplete(int $lessonId, array $user, string $lang): array
    {
        $lesson = $this->lessonDetail($lessonId, $user, $lang);
        if (! $lesson || empty($lesson['enrollment'])) {
            return ['ok' => false, 'message' => 'Lesson not available'];
        }

        $enrollment = $lesson['enrollment'];
        $existing = $this->lessonTracking($lessonId, (int) $enrollment['cosen_id'], $user);
        $data = [
            'cosen_id' => $enrollment['cosen_id'],
            'emp_id' => $user['emp_id'] ?? null,
            'les_id' => $lessonId,
            'learn_status' => '2',
        ];

        if ($existing) {
            $this->db->table('lms_les_tc')->where('lestc_id', $existing['lestc_id'])->update(['learn_status' => '2']);
        } else {
            $this->db->table('lms_les_tc')->insert($data);
        }

        foreach (($lesson['media'] ?? []) as $media) {
            $exists = $this->db->table('lms_med_tc')
                ->where('med_id', $media['id'])
                ->where('cosen_id', $enrollment['cosen_id'])
                ->where('emp_id', $user['emp_id'] ?? 0)
                ->countAllResults();
            if ($exists === 0) {
                $this->db->table('lms_med_tc')->insert([
                    'med_id' => $media['id'],
                    'emp_id' => $user['emp_id'] ?? null,
                    'cosen_id' => $enrollment['cosen_id'],
                    'medtc_datetime' => date('Y-m-d H:i:s'),
                    'medtc_volume' => 50,
                    'medtc_status' => 2,
                ]);
            }
        }

        $progress = (new ProgressModel())->recalculate((int) $lesson['cos_id'], $user, $lang);

        return [
            'ok' => true,
            'message' => ! empty($progress['completed']) ? 'Lesson completed. Course completed.' : 'Lesson completed',
            'progress' => $progress,
        ];
    }

    private function lessons(int $courseId, array $user, string $lang): array
    {
        $rows = $this->db->table('lms_les')
            ->select('lms_les.*, lms_les_tc.learn_status')
            ->join('lms_les_tc', 'lms_les_tc.les_id = lms_les.les_id AND lms_les_tc.emp_id = ' . (int) ($user['emp_id'] ?? 0), 'left')
            ->where('lms_les.cos_id', $courseId)
            ->where('lms_les.les_status', '1')
            ->where('lms_les.les_isDelete', '0')
            ->orderBy('lms_les.les_sequences', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'les_name');
            $row['description'] = $this->localized($row, $lang, 'les_info');
            $row['media'] = $this->lessonMedia((int) $row['les_id'], $lang);
            $row['documents'] = $this->lessonDocuments((int) $row['les_id'], $lang);
            $row['scorm'] = $this->lessonScorm((int) $row['les_id']);
            $row['status_label'] = (string) ($row['learn_status'] ?? '') === '2' ? 'Completed' : 'Available';
        }

        return $rows;
    }

    private function coursePayload(array $input, array $user, ?int $courseId = null): array
    {
        $code = strtoupper(trim((string) ($input['ccode'] ?? '')));
        $nameEn = trim((string) ($input['cname_eng'] ?? ''));
        $nameTh = trim((string) ($input['cname_th'] ?? $nameEn));
        $companyId = (int) ($input['com_id'] ?? 0);
        $typeId = (int) ($input['tc_id'] ?? 0);

        if ($code === '' || $nameEn === '' || $companyId <= 0 || $typeId <= 0) {
            return ['ok' => false, 'message' => 'Course code, company, type, and English name are required.'];
        }

        if (strlen($code) > 15) {
            return ['ok' => false, 'message' => 'Course code must be 15 characters or fewer.'];
        }

        $languages = $input['cos_lang'] ?? ['eng'];
        if (! is_array($languages)) {
            $languages = [$languages];
        }
        $languages = array_values(array_intersect($languages, ['th', 'eng', 'jp']));
        if ($languages === []) {
            $languages = ['eng'];
        }

        return [
            'ok' => true,
            'payload' => [
                'ccode' => $code,
                'cos_lang' => implode(',', $languages),
                'com_id' => $companyId,
                'cname_th' => $nameTh,
                'cdesc_th' => trim((string) ($input['cdesc_th'] ?? '')),
                'cname_eng' => $nameEn,
                'cdesc_eng' => trim((string) ($input['cdesc_eng'] ?? '')),
                'sub_description_th' => trim((string) ($input['sub_description_th'] ?? '')),
                'sub_description_eng' => trim((string) ($input['sub_description_eng'] ?? '')),
                'cname_jp' => trim((string) ($input['cname_jp'] ?? '')),
                'cdesc_jp' => trim((string) ($input['cdesc_jp'] ?? '')),
                'sub_description_jp' => trim((string) ($input['sub_description_jp'] ?? '')),
                'max_score' => (float) ($input['max_score'] ?? 100),
                'goal_score' => (float) ($input['goal_score'] ?? 80),
                'seat_count' => max(0, (int) ($input['seat_count'] ?? 0)),
                'cos_typegrading' => (int) ($input['cos_typegrading'] ?? 2),
                'tc_id' => $typeId,
                'cos_public' => (int) ($input['cos_public'] ?? 0),
                'cos_hour' => max(0, (int) ($input['cos_hour'] ?? 0)),
                'cos_approve' => (int) ($input['cos_approve'] ?? 0),
                'cos_status' => (int) ($input['cos_status'] ?? 1),
            ],
        ];
    }

    private function courseGroupPayload(array $input, ?int $groupId = null): array
    {
        $companyId = (int) ($input['com_id'] ?? 0);
        $code = strtoupper(trim((string) ($input['cgcode'] ?? '')));
        $titleEn = trim((string) ($input['cgtitle_en'] ?? ''));
        $titleTh = trim((string) ($input['cgtitle_th'] ?? $titleEn));
        $titleJp = trim((string) ($input['cgtitle_jp'] ?? ''));

        if ($companyId <= 0 || $code === '' || $titleEn === '') {
            return ['ok' => false, 'message' => 'Company, group code, and English title are required.'];
        }

        $companyExists = $this->db->table('lms_company')
            ->where('com_id', $companyId)
            ->where('com_isDelete', '0')
            ->countAllResults() > 0;
        if (! $companyExists) {
            return ['ok' => false, 'message' => 'Company not found.'];
        }

        $duplicate = $this->db->table('lms_cog')
            ->where('cgcode', $code)
            ->where('cg_isDelete', '0');
        if ($groupId !== null) {
            $duplicate->where('cg_id !=', $groupId);
        }
        if ($duplicate->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Course group code already exists.'];
        }

        return [
            'ok' => true,
            'payload' => [
                'com_id' => $companyId,
                'cgcode' => $code,
                'cgtitle_th' => $titleTh,
                'cgdesc_th' => trim((string) ($input['cgdesc_th'] ?? '')),
                'cgtitle_en' => $titleEn,
                'cgdesc_en' => trim((string) ($input['cgdesc_en'] ?? '')),
                'cgtitle_jp' => $titleJp,
                'cgdesc_jp' => trim((string) ($input['cgdesc_jp'] ?? '')),
                'cg_approve_by' => trim((string) ($input['cg_approve_by'] ?? '')),
                'cg_reject' => trim((string) ($input['cg_reject'] ?? '')),
                'cg_status' => (int) ($input['cg_status'] ?? 1),
                'cg_approve' => (int) ($input['cg_approve'] ?? 2),
            ],
        ];
    }

    private function lessonPayload(array $input, array $user): array
    {
        $nameEn = trim((string) ($input['les_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['les_name_th'] ?? $nameEn));
        if ($nameEn === '') {
            return ['ok' => false, 'message' => 'English lesson name is required.'];
        }

        $languages = $input['les_lang'] ?? ['eng'];
        if (! is_array($languages)) {
            $languages = [$languages];
        }
        $languages = array_values(array_intersect($languages, ['th', 'eng', 'jp']));
        if ($languages === []) {
            $languages = ['eng'];
        }

        return [
            'ok' => true,
            'payload' => [
                'les_lang' => implode(',', $languages),
                'les_name_th' => $nameTh,
                'les_info_th' => trim((string) ($input['les_info_th'] ?? '')),
                'les_name_eng' => $nameEn,
                'les_info_eng' => trim((string) ($input['les_info_eng'] ?? '')),
                'les_name_jp' => trim((string) ($input['les_name_jp'] ?? '')),
                'les_info_jp' => trim((string) ($input['les_info_jp'] ?? '')),
                'les_type' => (int) ($input['les_type'] ?? 1),
                'scm_type' => (int) ($input['scm_type'] ?? 0),
                'time_start' => $this->dateOrZero((string) ($input['time_start'] ?? '')),
                'time_end' => $this->dateOrZero((string) ($input['time_end'] ?? '')),
                'les_status' => (int) ($input['les_status'] ?? 1),
                'les_sequences' => max(0, (int) ($input['les_sequences'] ?? 0)),
            ],
        ];
    }

    private function dateOrZero(string $value): string
    {
        if (trim($value) === '') {
            return '0000-00-00 00:00:00';
        }

        $time = strtotime($value);
        return $time ? date('Y-m-d H:i:s', $time) : '0000-00-00 00:00:00';
    }

    private function mediaForEdit(int $mediaId): ?array
    {
        $row = $this->db->table('lms_med')
            ->select('lms_med.*, lms_les.cos_id')
            ->join('lms_les', 'lms_les.les_id = lms_med.lessons_id')
            ->where('lms_med.id', $mediaId)
            ->where('lms_les.les_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function documentForEdit(int $documentId): ?array
    {
        $row = $this->db->table('lms_fil')
            ->select('lms_fil.*, lms_les.cos_id')
            ->join('lms_les', 'lms_les.les_id = lms_fil.lessons_id')
            ->where('lms_fil.id', $documentId)
            ->where('lms_les.les_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function mediaPayload(array $input): array
    {
        if (! empty($input['upload_error'])) {
            return ['ok' => false, 'message' => (string) $input['upload_error']];
        }

        $nameEn = trim((string) ($input['med_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['med_name_th'] ?? $nameEn));
        $video = trim((string) ($input['video'] ?? ''));
        $type = trim((string) ($input['type'] ?? 'upload'));

        if ($nameEn === '' || $video === '') {
            return ['ok' => false, 'message' => 'Media English name and source are required.'];
        }

        if (! in_array($type, ['upload', 'url'], true)) {
            $type = 'upload';
        }

        return [
            'ok' => true,
            'payload' => [
                'med_name_th' => $nameTh,
                'med_name_eng' => $nameEn,
                'med_name_jp' => trim((string) ($input['med_name_jp'] ?? '')),
                'thumbnail_med' => trim((string) ($input['thumbnail_med'] ?? '')),
                'type' => $type,
                'video' => $video,
            ],
        ];
    }

    private function documentPayload(array $input): array
    {
        if (! empty($input['upload_error'])) {
            return ['ok' => false, 'message' => (string) $input['upload_error']];
        }

        $nameEn = trim((string) ($input['name_file_eng'] ?? ''));
        $nameTh = trim((string) ($input['name_file_th'] ?? $nameEn));
        $path = trim((string) ($input['path_file'] ?? ''));

        if ($nameEn === '' || $path === '') {
            return ['ok' => false, 'message' => 'Document English name and file path are required.'];
        }

        return [
            'ok' => true,
            'payload' => [
                'path_file' => $path,
                'name_file_th' => $nameTh,
                'name_file_eng' => $nameEn,
                'name_file_jp' => trim((string) ($input['name_file_jp'] ?? '')),
            ],
        ];
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

    private function actorCode(array $user): string
    {
        return (string) ($user['emp_c'] ?? $user['u_id'] ?? '');
    }

    private function lessonMedia(int $lessonId, string $lang): array
    {
        $rows = $this->db->table('lms_med')
            ->where('lessons_id', $lessonId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'med_name');
            $row['url'] = $this->assetUrl((string) ($row['video'] ?? ''), (string) ($row['type'] ?? 'upload'), 'uploads/media/');
        }

        return $rows;
    }

    private function lessonDocuments(int $lessonId, string $lang): array
    {
        $rows = $this->db->table('lms_fil')
            ->where('lessons_id', $lessonId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'name_file');
            $row['url'] = $this->assetUrl((string) ($row['path_file'] ?? ''), 'upload', 'uploads/document/');
        }

        return $rows;
    }

    private function lessonScorm(int $lessonId): ?array
    {
        $row = $this->db->table('lms_scm')
            ->where('lessons_id', $lessonId)
            ->get()
            ->getRowArray();

        if (! $row) {
            return null;
        }

        $row['package_url'] = $this->assetUrl((string) ($row['path'] ?? ''), 'upload', 'uploads/scorm/');
        $row['url'] = site_url('scorm/load/' . (int) $row['id']);
        $row['player_url'] = $row['url'];
        return $row;
    }

    private function extractScormZip(string $zipName, int $lessonId, int $scormId): array
    {
        $base = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'scorm';
        $zipPath = $base . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $zipName), DIRECTORY_SEPARATOR);
        if (! is_file($zipPath)) {
            return ['ok' => false, 'message' => 'Uploaded SCORM ZIP not found.'];
        }

        $targetName = 'scorm_' . $lessonId . '_' . $scormId;
        $target = $base . DIRECTORY_SEPARATOR . $targetName;
        if (is_dir($target)) {
            $this->removeDirectory($target);
        }
        if (! mkdir($target, 0775, true) && ! is_dir($target)) {
            return ['ok' => false, 'message' => 'Unable to create SCORM directory.'];
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['ok' => false, 'message' => 'Unable to open SCORM ZIP.'];
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            if (! $this->safeZipEntry($entry)) {
                $zip->close();
                $this->removeDirectory($target);
                return ['ok' => false, 'message' => 'Unsafe file path found in SCORM ZIP.'];
            }

            $destination = $target . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $entry);
            if (str_ends_with($entry, '/')) {
                if (! is_dir($destination)) {
                    mkdir($destination, 0775, true);
                }
                continue;
            }

            $directory = dirname($destination);
            if (! is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $stream = $zip->getStream($entry);
            if (! $stream) {
                continue;
            }
            $output = fopen($destination, 'wb');
            if ($output) {
                stream_copy_to_stream($stream, $output);
                fclose($output);
            }
            fclose($stream);
        }

        $zip->close();
        @unlink($zipPath);

        return ['ok' => true, 'path' => $targetName];
    }

    private function safeZipEntry(string $entry): bool
    {
        $entry = str_replace('\\', '/', $entry);
        return $entry !== ''
            && ! str_starts_with($entry, '/')
            && ! preg_match('#(^|/)\.\.(/|$)#', $entry)
            && ! preg_match('#^[A-Za-z]:/#', $entry);
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($directory);
    }

    private function assetUrl(string $path, string $type, string $base): string
    {
        if ($path === '') {
            return '';
        }

        if ($type === 'url' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return site_url($base . ltrim($path, '/'));
    }

    private function quizzes(int $courseId, string $lang): array
    {
        $rows = $this->db->table('lms_qiz')
            ->where('cos_id', $courseId)
            ->where('quiz_isDelete', '0')
            ->where('quiz_status', '1')
            ->orderBy('qiz_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'quiz_name');
            $row['type_label'] = (string) $row['quiz_type'] === '1' ? 'Pre-test' : 'Post-test';
        }

        return $rows;
    }

    private function courseSurveys(int $courseId, string $lang): array
    {
        $rows = $this->db->table('lms_survey')
            ->where('cos_id', $courseId)
            ->where('sv_isDelete', '0')
            ->where('sv_status', '1')
            ->orderBy('sv_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'sv_title');
        }

        return $rows;
    }

    private function courseDocuments(int $courseId, string $lang): array
    {
        $rows = $this->db->table('lms_cos_fil')
            ->where('cos_id', $courseId)
            ->where('fil_isDelete', '0')
            ->where('fil_status', '1')
            ->orderBy('fil_cos_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'name_file');
            $row['url'] = $row['path_file'] ? site_url('uploads/document/' . $row['path_file']) : '';
        }

        return $rows;
    }

    private function enrollment(int $courseId, array $user): ?array
    {
        $row = $this->db->table('lms_cos_enroll')
            ->where('cos_id', $courseId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_isDelete', '0')
            ->orderBy('cosen_id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $row) {
            return null;
        }

        $row['learning_label'] = $this->learningStatusLabel($row);
        return $row;
    }

    private function lessonTracking(int $lessonId, int $enrollmentId, array $user): ?array
    {
        $row = $this->db->table('lms_les_tc')
            ->where('les_id', $lessonId)
            ->where('cosen_id', $enrollmentId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function courseLang(string $lang): string
    {
        return match ($lang) {
            'thai' => 'th',
            'japan' => 'jp',
            default => 'eng',
        };
    }

    private function baseCourseBuilder(string $lang)
    {
        return $this->db->table('lms_cos')
            ->select('lms_cos.cos_id, lms_cos.ccode, lms_cos.cos_lang, lms_cos.com_id, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp, lms_cos.sub_description_th, lms_cos.sub_description_eng, lms_cos.sub_description_jp, lms_cos.cos_pic, lms_cos.seat_count, lms_cos.cos_public, lms_cos.cos_approve, lms_cos.cos_status, CAST(lms_cos.cos_createdate AS CHAR) AS cos_createdate')
            ->select('lms_company.com_name_th, lms_company.com_name_eng')
            ->select('CAST(MAX(lms_cos_detail.date_start) AS CHAR) AS date_start, CAST(MAX(lms_cos_detail.date_end) AS CHAR) AS date_end')
            ->join('lms_company', 'lms_company.com_id = lms_cos.com_id', 'left')
            ->join('lms_cos_detail', 'lms_cos_detail.cos_id = lms_cos.cos_id AND lms_cos_detail.cosde_isDelete = 0', 'left');
    }

    private function decorateCourseGroup(array $row, string $lang): array
    {
        $row['title'] = $this->localized($row, $lang, 'cgtitle');
        $row['description'] = $this->localized($row, $lang, 'cgdesc');
        $row['company_name'] = $lang === 'thai' ? ($row['com_name_th'] ?: $row['com_name_eng']) : ($row['com_name_eng'] ?: $row['com_name_th']);
        $row['status_label'] = (string) $row['cg_status'] === '1' ? 'Active' : 'Inactive';
        $row['approval_label'] = (string) $row['cg_approve'] === '1' ? 'Approved' : 'Waiting';
        $row['thumb_url'] = base_url('uploads/course_group/' . ($row['cgthumb'] ?: 'default_image.jpg'));

        return $row;
    }

    private function decorateCourse(array $row, string $lang, bool $withLearningStatus = false): array
    {
        $row['title'] = $this->localized($row, $lang, 'cname');
        $row['description'] = $this->localized($row, $lang, 'sub_description');
        $row['company_name'] = $lang === 'thai' ? ($row['com_name_th'] ?: $row['com_name_eng']) : ($row['com_name_eng'] ?: $row['com_name_th']);
        $row['status_label'] = $this->courseStatusLabel($row);
        $row['period_label'] = $this->periodLabel($row['date_start'] ?? null, $row['date_end'] ?? null);
        $row['image_url'] = base_url('uploads/course/' . ($row['cos_pic'] ?: 'default_profile.jpg'));
        $row['seat_label'] = ((int) ($row['seat_count'] ?? 0)) > 0 ? (string) $row['seat_count'] : 'Unlimited';

        if ($withLearningStatus) {
            $row['learning_label'] = $this->learningStatusLabel($row);
        }

        return $row;
    }

    private function localized(array $row, string $lang, string $prefix): string
    {
        $order = match ($lang) {
            'thai' => ['th', 'eng', 'jp'],
            'japan' => ['jp', 'eng', 'th'],
            default => ['eng', 'th', 'jp'],
        };

        foreach ($order as $suffix) {
            $value = trim((string) ($row[$prefix . '_' . $suffix] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return '-';
    }

    private function courseGroupTitle(array $row, string $lang): string
    {
        $order = match ($lang) {
            'thai' => ['cgtitle_th', 'cgtitle_en', 'cgtitle_jp'],
            'japan' => ['cgtitle_jp', 'cgtitle_en', 'cgtitle_th'],
            default => ['cgtitle_en', 'cgtitle_th', 'cgtitle_jp'],
        };

        foreach ($order as $column) {
            $value = trim((string) ($row[$column] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return '-';
    }

    private function courseStatusLabel(array $row): string
    {
        if ((string) ($row['cos_approve'] ?? '') !== '1') {
            return 'Waiting approval';
        }

        if ((string) ($row['cos_status'] ?? '') !== '1') {
            return 'Inactive';
        }

        return (string) ($row['cos_public'] ?? '') === '1' ? 'Published' : 'Private';
    }

    private function learningStatusLabel(array $row): string
    {
        if ((string) ($row['cosen_status_sub'] ?? '') === '1') {
            return 'Success';
        }

        $first = (string) ($row['cosen_firsttime'] ?? '');
        if ($first === '' || str_starts_with($first, '0000-00-00')) {
            return 'Not started';
        }

        return 'In progress';
    }

    private function periodLabel(?string $start, ?string $end): string
    {
        $start = (string) $start;
        $end = (string) $end;

        if ($this->isEmptyDate($start) && $this->isEmptyDate($end)) {
            return 'Unlimited';
        }

        return ($this->isEmptyDate($start) ? 'Anytime' : substr($start, 0, 10))
            . ' - '
            . ($this->isEmptyDate($end) ? 'No end' : substr($end, 0, 10));
    }

    private function isEmptyDate(string $value): bool
    {
        return $value === '' || str_starts_with($value, '0000-00-00');
    }
}
