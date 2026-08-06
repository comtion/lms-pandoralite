<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\CourseModel;

class DashboardModel extends Model
{
    protected $returnType = 'array';

    public function summaryForUser(array $user, string $lang = 'english'): array
    {
        return [
            'branding' => $this->branding(),
            'banners' => $this->banners($user),
            'course_total' => $this->courseTotal($user),
            'enroll' => $this->countEnroll($user),
            'in_process' => $this->countInProcess($user),
            'success' => $this->countSuccess($user),
            'not_start' => $this->countNotStarted($user),
            'device_usage' => $this->deviceUsage($user),
            'course_select' => $this->courseSelect($user),
            'course_status' => $this->courseStatusOverview($user),
            'company_analytics' => $this->companyAnalytics(),
            'approval_courses' => $this->approvalCourses($user, $lang),
            'approval_surveys' => $this->approvalSurveys($user, $lang),
            'approval_course_groups' => $this->approvalCourseGroups($user),
            'public_surveys' => $this->publicSurveys($user, $lang),
            'learner_courses' => $this->learnerCourses($user, $lang),
            'notifications' => $this->recentNotifications($user),
        ];
    }

    public function recentNotifications(array $user, int $limit = 8): array
    {
        if (empty($user['emp_id']) || ! $this->db->tableExists('lms_notifications')) {
            return ['unread' => 0, 'items' => []];
        }

        $employeeId = (int) $user['emp_id'];
        $items = $this->db->table('lms_notifications')
            ->where('emp_id', $employeeId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $unread = $this->db->table('lms_notifications')
            ->where('emp_id', $employeeId)
            ->where('is_read', 0)
            ->countAllResults();

        return ['unread' => $unread, 'items' => $items];
    }

    public function branding(): array
    {
        return $this->db->table('lms_about')
            ->where('da_id', '1')
            ->get()
            ->getRowArray() ?: [];
    }

    public function banners(array $user): array
    {
        $builder = $this->db->table('lms_ban')
            ->where('hidden', '1')
            ->orderBy('id', 'DESC')
            ->limit(8);

        if (($user['com_admin'] ?? '') === 'com_associated' && ! empty($user['com_id'])) {
            $builder->where('com_id', $user['com_id']);
        } else {
            $builder->where('com_id', '3');
        }

        $banners = $builder->get()->getResultArray();

        return $banners !== [] ? $banners : [['banner' => 'banner_default.png']];
    }

    public function courseTotal(array $user): int
    {
        if (empty($user['posi_id'])) {
            return 0;
        }

        return $this->db->table('lms_cos')
            ->select('lms_cos.cos_id')
            ->where(
                "lms_cos.cos_id in (
                    select lms_cos_detail.cos_id
                    from lms_cos_detail
                    where lms_cos_detail.cosde_id in (
                        select lms_cos_detail_ug.cosde_id
                        from lms_cos_detail_ug
                        where lms_cos_detail_ug.posi_id = " . $this->db->escape($user['posi_id']) . "
                    )
                    and lms_cos_detail.cosde_status = '1'
                    and " . $this->courseAvailabilityWhere('lms_cos_detail') . '
                )',
                null,
                false
            )
            ->countAllResults();
    }

    public function countEnroll(array $user): int
    {
        return $this->enrollBase($user)
            ->where('cosen_status', '1')
            ->countAllResults();
    }

    public function countInProcess(array $user): int
    {
        return $this->enrollBase($user)
            ->where('cosen_status', '1')
            ->where('cosen_status_sub', '2')
            ->where("CAST(cosen_firsttime AS CHAR) != '0000-00-00 00:00:00'", null, false)
            ->countAllResults();
    }

    public function countSuccess(array $user): int
    {
        return $this->enrollBase($user)
            ->where('cosen_status', '1')
            ->where('cosen_status_sub', '1')
            ->where("CAST(cosen_firsttime AS CHAR) != '0000-00-00 00:00:00'", null, false)
            ->where("CAST(cosen_finishtime AS CHAR) != '0000-00-00 00:00:00'", null, false)
            ->countAllResults();
    }

    public function countNotStarted(array $user): int
    {
        return $this->enrollBase($user)
            ->where('cosen_status', '1')
            ->where("CAST(cosen_firsttime AS CHAR) = '0000-00-00 00:00:00'", null, false)
            ->countAllResults();
    }

    public function deviceUsage(array $user): array
    {
        $builder = $this->db->table('lms_lg')
            ->select("SUM(device LIKE '%PC%') AS pc,SUM(device LIKE '%Mobile%') AS mobile,SUM(device LIKE '%Tablet%') AS tablet", false);
        if (in_array((string) ($user['ug_id'] ?? ''), ['2', '6'], true) && ! empty($user['com_id'])) {
            $builder->where('emp_id IN (SELECT emp_id FROM lms_emp WHERE com_id=' . $this->db->escape($user['com_id']) . ')', null, false);
        }
        $row = $builder->get()->getRowArray() ?: [];
        $pc = (int) ($row['pc'] ?? 0);
        $mobile = (int) ($row['mobile'] ?? 0);
        $tablet = (int) ($row['tablet'] ?? 0);
        $total = $pc + $mobile + $tablet;

        return [
            'pc' => $total > 0 ? round(($pc / $total) * 100, 2) : 0,
            'mobile' => $total > 0 ? round(($mobile / $total) * 100, 2) : 0,
            'tablet' => $total > 0 ? round(($tablet / $total) * 100, 2) : 0,
        ];
    }

    public function courseSelect(array $user): array
    {
        if (empty($user['com_id'])) {
            return [];
        }

        return $this->db->table('lms_cos')
            ->select('cos_id, ccode, cname_th, cname_eng')
            ->where('com_id', $user['com_id'])
            ->where('cos_status', '1')
            ->orderBy('cos_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function courseStatusOverview(array $user): array
    {
        if ((string) ($user['ug_id'] ?? '') === '1') {
            return $this->globalCourseStatus();
        }

        return [
            'total' => $this->courseTotal($user),
            'ongoing' => $this->countEnroll($user),
            'incoming' => $this->countNotStarted($user),
            'closed' => $this->countSuccess($user),
        ];
    }

    public function companyAnalytics(): array
    {
        $now = $this->db->escape(date('Y-m-d H:i'));
        return $this->db->table('lms_company')
            ->select('lms_company.*')
            ->select("(SELECT COUNT(*) FROM lms_emp e JOIN lms_usp u ON u.emp_id=e.emp_id WHERE e.com_id=lms_company.com_id AND e.emp_isDelete='0' AND (u.inactivedate > {$now} OR CAST(u.inactivedate AS CHAR)='0000-00-00')) AS usertotal", false)
            ->select("(SELECT COUNT(*) FROM lms_cos c WHERE c.com_id=lms_company.com_id AND c.cos_approve='1' AND c.cos_public='1' AND c.cos_isDelete='0' AND EXISTS (SELECT 1 FROM lms_cosincg x JOIN lms_cog g ON g.cg_id=x.cg_id WHERE x.course_id=c.cos_id AND g.cg_status='1' AND g.cg_approve='1' AND g.cg_isDelete='0')) AS coursetotal", false)
            ->select("(SELECT COUNT(*) FROM lms_sv s WHERE s.com_id=lms_company.com_id AND s.sv_public='1' AND s.sv_approve='1' AND s.sv_isDelete='0') AS surveytotal", false)
            ->where('com_isDelete', '0')
            ->where('com_status', '1')
            ->where('com_id !=', '2')
            ->orderBy('com_name_eng', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function approvalCourses(array $user, string $lang): array
    {
        $approverCourseGroupIds = $this->approvedCourseGroupIds($user);

        if ($approverCourseGroupIds === [] || empty($user['com_id'])) {
            return [];
        }

        $courses = $this->db->table('lms_cos')
            ->where('com_id', $user['com_id'])
            ->whereIn('cos_id', static function ($builder) use ($approverCourseGroupIds) {
                $builder->select('course_id')
                    ->from('lms_cosincg')
                    ->whereIn('cg_id', $approverCourseGroupIds);
            })
            ->where('cos_approve', '0')
            ->where('cos_public', '1')
            ->where('cos_isDelete', '0')
            ->get()
            ->getResultArray();

        foreach ($courses as &$course) {
            $courseLang = explode(',', (string) ($course['cos_lang'] ?? ''));
            $course['isTH'] = in_array('th', $courseLang, true) ? '1' : '0';
            $course['isENG'] = in_array('eng', $courseLang, true) ? '1' : '0';
            $course['isJP'] = in_array('jp', $courseLang, true) ? '1' : '0';
            $course['display_name'] = $this->localizedValue($course, $lang, 'cname');
            $course['user_creator'] = $this->creatorName($course['cos_createby'] ?? null, $lang);
        }

        return $courses;
    }

    public function approvalSurveys(array $user, string $lang): array
    {
        if (empty($user['com_id']) || empty($user['emp_id'])) {
            return [];
        }

        $surveys = $this->db->table('lms_sv')
            ->where('com_id', $user['com_id'])
            ->where('sv_public', '1')
            ->where('sv_approve', '0')
            ->where('sv_isDelete', '0')
            ->where('sv_status', '1')
            ->get()
            ->getResultArray();

        $visible = [];

        foreach ($surveys as $survey) {
            $approvers = $this->splitCsv($survey['sv_userapprove'] ?? '');

            if (! in_array((string) $user['emp_id'], $approvers, true)) {
                continue;
            }

            if ($this->countSurveyQuestions((int) $survey['sv_id']) === 0) {
                continue;
            }

            $survey['display_title'] = $this->localizedValue($survey, $lang, 'sv_title');
            $survey['user_creator'] = $this->creatorName($survey['sv_createby'] ?? null, $lang);
            $visible[] = $survey;
        }

        return $visible;
    }

    public function approvalCourseGroups(array $user): array
    {
        if (empty($user['com_id']) || empty($user['u_id'])) {
            return [];
        }

        $groups = $this->db->table('lms_cog')
            ->where('com_id', $user['com_id'])
            ->where('cg_isDelete', '0')
            ->get()
            ->getResultArray();

        return array_values(array_filter($groups, static function (array $group) use ($user): bool {
            $approvers = array_filter(array_map('trim', explode(',', (string) ($group['cg_approve_by'] ?? ''))));

            return in_array((string) $user['u_id'], $approvers, true)
                && (string) ($group['cg_approve'] ?? '') === '2';
        }));
    }

    public function publicSurveys(array $user, string $lang): array
    {
        if (empty($user['emp_id']) || empty($user['posi_id'])) {
            return [];
        }

        $now = date('Y-m-d H:i');
        $surveys = $this->db->table('lms_sv')
            ->where('sv_public', '1')
            ->where('sv_approve', '1')
            ->where('sv_isDelete', '0')
            ->where(
                "((CAST(sv_open AS CHAR) = '0000-00-00 00:00:00' AND CAST(sv_end AS CHAR) = '0000-00-00 00:00:00') OR (" . $this->db->escape($now) . ' BETWEEN sv_open AND sv_end))',
                null,
                false
            )
            ->get()
            ->getResultArray();

        $visible = [];

        foreach ($surveys as $survey) {
            $transaction = $this->db->table('lms_sv_tc')
                ->where('sv_id', $survey['sv_id'])
                ->where('emp_id', $user['emp_id'])
                ->where('svtc_isDelete', '0')
                ->get()
                ->getRowArray();

            if ($transaction) {
                if ((string) ($transaction['svtc_finishtime'] ?? '') !== '0000-00-00 00:00:00') {
                    continue;
                }
            } elseif ($this->db->table('lms_sv_pm')->where('sv_id', $survey['sv_id'])->where('posi_id', $user['posi_id'])->countAllResults() === 0) {
                continue;
            }

            if ($this->countSurveyQuestions((int) $survey['sv_id']) === 0) {
                continue;
            }

            $survey['display_title'] = $this->localizedValue($survey, $lang, 'sv_title');
            $survey['display_end'] = $this->formatSurveyEnd($survey['sv_end'] ?? '', $lang);
            $visible[] = $survey;
        }

        return $visible;
    }

    public function learnerCourses(array $user, string $lang): array
    {
        $courseModel = new CourseModel();
        $courses = $courseModel->myCourses($user, $lang, 12);
        $ongoing = [];
        $incoming = [];
        $completed = [];

        foreach ($courses as $course) {
            if ((string) ($course['cosen_status_sub'] ?? '') === '1') {
                $completed[] = $course;
                continue;
            }

            $first = (string) ($course['cosen_firsttime'] ?? '');
            if ($first === '' || str_starts_with($first, '0000-00-00')) {
                $incoming[] = $course;
                continue;
            }

            $ongoing[] = $course;
        }

        return [
            'all' => $courses,
            'ongoing' => $ongoing,
            'incoming' => $incoming,
            'completed' => $completed,
        ];
    }

    private function countDevice(array $user, string $device): int
    {
        $builder = $this->db->table('lms_lg')
            ->select('lms_lg.id')
            ->like('lms_lg.device', $device);

        if (in_array((string) ($user['ug_id'] ?? ''), ['2', '6'], true) && ! empty($user['com_id'])) {
            $builder->where(
                'lms_lg.emp_id in (select lms_emp.emp_id from lms_emp where lms_emp.com_id = ' . $this->db->escape($user['com_id']) . ')',
                null,
                false
            );
        }

        return $builder->countAllResults();
    }

    private function globalCourseStatus(): array
    {
        $courses = $this->db->table('lms_cos')
            ->select('lms_cos.cos_id,lms_cos_detail.date_start,lms_cos_detail.date_end')
            ->join('lms_cos_detail', 'lms_cos_detail.cos_id=lms_cos.cos_id', 'left')
            ->where('cos_approve', '1')
            ->where('cos_public', '1')
            ->where('cos_isDelete', '0')
            ->where("EXISTS (SELECT 1 FROM lms_cosincg x JOIN lms_cog g ON g.cg_id=x.cg_id WHERE x.course_id=lms_cos.cos_id AND g.cg_status='1' AND g.cg_approve='1' AND g.cg_isDelete='0')", null, false)
            ->get()
            ->getResultArray();

        $status = [
            'total' => 0,
            'ongoing' => 0,
            'incoming' => 0,
            'closed' => 0,
        ];

        foreach ($courses as $course) {
            $status['total']++;
            $bucket = $this->courseDateBucket($course);
            $status[$bucket]++;
        }

        return $status;
    }

    private function courseDateBucket(?array $detail): string
    {
        if (! $detail) {
            return 'ongoing';
        }

        $start = (string) ($detail['date_start'] ?? '');
        $end = (string) ($detail['date_end'] ?? '');
        $now = date('Y-m-d H:i');

        if ($start !== '' && $start !== '0000-00-00 00:00:00' && date('Y-m-d H:i', strtotime($start)) > $now) {
            return 'incoming';
        }

        if ($end !== '' && $end !== '0000-00-00 00:00:00' && date('Y-m-d H:i', strtotime($end)) < $now) {
            return 'closed';
        }

        return 'ongoing';
    }

    private function courseHasApprovedGroup(int $courseId): bool
    {
        return $this->db->table('lms_cosincg')
            ->join('lms_cog', 'lms_cosincg.cg_id = lms_cog.cg_id')
            ->where('lms_cosincg.course_id', $courseId)
            ->where('lms_cog.cg_status', '1')
            ->where('lms_cog.cg_approve', '1')
            ->where('lms_cog.cg_isDelete', '0')
            ->countAllResults() > 0;
    }

    private function activeUserCount(int $companyId): int
    {
        return $this->db->table('lms_emp')
            ->where('com_id', $companyId)
            ->where('emp_isDelete', '0')
            ->where(
                "emp_id in (select emp_id from lms_usp where (inactivedate > " . $this->db->escape(date('Y-m-d H:i')) . " or CAST(inactivedate AS CHAR) = '0000-00-00'))",
                null,
                false
            )
            ->countAllResults();
    }

    private function approvedPublicCourseCount(int $companyId): int
    {
        $courses = $this->db->table('lms_cos')
            ->select('cos_id')
            ->where('com_id', $companyId)
            ->where('cos_approve', '1')
            ->where('cos_public', '1')
            ->where('cos_isDelete', '0')
            ->get()
            ->getResultArray();

        $count = 0;

        foreach ($courses as $course) {
            if ($this->courseHasApprovedGroup((int) $course['cos_id'])) {
                $count++;
            }
        }

        return $count;
    }

    private function approvedPublicSurveyCount(int $companyId): int
    {
        return $this->db->table('lms_sv')
            ->where('com_id', $companyId)
            ->where('sv_public', '1')
            ->where('sv_approve', '1')
            ->where('sv_isDelete', '0')
            ->countAllResults();
    }

    private function approvedCourseGroupIds(array $user): array
    {
        if (empty($user['com_id']) || empty($user['u_id'])) {
            return [];
        }

        $groups = $this->db->table('lms_cog')
            ->select('cg_id, cg_approve_by')
            ->where('com_id', $user['com_id'])
            ->where('cg_isDelete', '0')
            ->where('cg_approve', '1')
            ->where('cg_status', '1')
            ->get()
            ->getResultArray();

        $ids = [];

        foreach ($groups as $group) {
            if (in_array((string) $user['u_id'], $this->splitCsv($group['cg_approve_by'] ?? ''), true)) {
                $ids[] = (int) $group['cg_id'];
            }
        }

        return $ids;
    }

    private function countSurveyQuestions(int $surveyId): int
    {
        return $this->db->table('lms_svde')
            ->where('sv_id', $surveyId)
            ->where('svde_isDelete', '0')
            ->countAllResults();
    }

    private function creatorName(mixed $userId, string $lang): string
    {
        if ($userId === null || $userId === '') {
            return '';
        }

        $creator = $this->db->table('lms_usp')
            ->select('lms_emp.fullname_th, lms_emp.fullname_en')
            ->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id')
            ->where('lms_usp.u_id', $userId)
            ->get()
            ->getRowArray();

        if (! $creator) {
            return '';
        }

        return $lang === 'thai' ? ($creator['fullname_th'] ?? '') : ($creator['fullname_en'] ?? '');
    }

    private function localizedValue(array $row, string $lang, string $prefix): string
    {
        $keys = match ($lang) {
            'thai' => [$prefix . '_th', $prefix . '_eng', $prefix . '_jp'],
            'japan' => [$prefix . '_jp', $prefix . '_eng', $prefix . '_th'],
            default => [$prefix . '_eng', $prefix . '_th', $prefix . '_jp'],
        };

        foreach ($keys as $key) {
            if (! empty($row[$key])) {
                return (string) $row[$key];
            }
        }

        return '';
    }

    private function formatSurveyEnd(string $value, string $lang): string
    {
        if ($value === '' || $value === '0000-00-00 00:00:00') {
            return 'Unlimited';
        }

        if ($lang !== 'thai') {
            return date('d F Y H:i', strtotime($value));
        }

        $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        $timestamp = strtotime($value);

        return date('d', $timestamp) . ' ' . $thaiMonths[(int) date('m', $timestamp)] . ' ' . ((int) date('Y', $timestamp) + 543) . ' ' . date('H:i', $timestamp);
    }

    private function splitCsv(mixed $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $value)), static fn (string $item): bool => $item !== ''));
    }

    private function enrollBase(array $user)
    {
        return $this->db->table('lms_cos_enroll')
            ->select('cosen_id')
            ->where('emp_id', $user['emp_id'] ?? 0);
    }

    private function courseAvailabilityWhere(string $alias): string
    {
        $now = date('Y-m-d H:i');

        return "((" . $alias . ".date_start IS NULL OR CAST(" . $alias . ".date_start AS CHAR) = '' OR CAST(" . $alias . ".date_start AS CHAR) = '0000-00-00 00:00:00' OR " . $alias . ".date_start <= " . $this->db->escape($now) . ')'
            . ' AND (' . $alias . ".date_end IS NULL OR CAST(" . $alias . ".date_end AS CHAR) = '' OR CAST(" . $alias . ".date_end AS CHAR) = '0000-00-00 00:00:00' OR " . $alias . '.date_end >= ' . $this->db->escape($now) . '))';
    }
}
