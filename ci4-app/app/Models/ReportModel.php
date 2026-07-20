<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $returnType = 'array';

    public function learnerRows(array $user, string $lang, array $filters = [], int $limit = 500): array
    {
        $builder = $this->db->table('lms_cos_enroll')
            ->select('lms_cos_enroll.cosen_id, lms_cos_enroll.cos_id, lms_cos_enroll.emp_id, lms_cos_enroll.cosen_status_sub')
            ->select('CAST(lms_cos_enroll.cosen_firsttime AS CHAR) AS cosen_firsttime, CAST(lms_cos_enroll.cosen_finishtime AS CHAR) AS cosen_finishtime')
            ->select('lms_cos_enroll.cosen_score, lms_cos_enroll.cosen_score_per, lms_cos_enroll.cosen_grade')
            ->select('lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp, lms_cos.cos_hour')
            ->select('lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en')
            ->select('lms_company.com_id, lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_cos', 'lms_cos.cos_id = lms_cos_enroll.cos_id', 'left')
            ->join('lms_emp', 'lms_emp.emp_id = lms_cos_enroll.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->where('lms_cos_enroll.cosen_isDelete', '0')
            ->where('lms_cos.cos_isDelete', '0')
            ->where('lms_emp.emp_isDelete', '0')
            ->orderBy('lms_cos_enroll.cosen_id', 'DESC')
            ->limit($limit);

        if (! $this->canViewAll($user)) {
            $builder->where('lms_emp.com_id', $user['com_id'] ?? 0);
        }
        if (! empty($filters['com_id'])) {
            $builder->where('lms_emp.com_id', (int) $filters['com_id']);
        }
        if (! empty($filters['cos_id'])) {
            $builder->where('lms_cos_enroll.cos_id', (int) $filters['cos_id']);
        }
        if (($filters['status'] ?? '') !== '') {
            $builder->where('lms_cos_enroll.cosen_status_sub', (int) $filters['status']);
        }
        if (! empty($filters['date_start']) && ! empty($filters['date_end'])) {
            $builder->where('lms_cos_enroll.cosen_finishtime >=', $filters['date_start'] . ' 00:00:00')
                ->where('lms_cos_enroll.cosen_finishtime <=', $filters['date_end'] . ' 23:59:59');
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['learner_name'] = $lang === 'thai'
                ? ($row['fullname_th'] ?: $row['fullname_en'])
                : ($row['fullname_en'] ?: $row['fullname_th']);
            $row['company_name'] = $lang === 'thai'
                ? ($row['com_name_th'] ?: $row['com_name_eng'])
                : ($row['com_name_eng'] ?: $row['com_name_th']);
            $row['status_label'] = match ((string) $row['cosen_status_sub']) {
                '1' => 'Completed',
                '2' => 'In progress',
                default => 'Not started',
            };
        }

        return $rows;
    }

    public function courseSummaryRows(array $user, string $lang, array $filters = [], int $limit = 1000): array
    {
        $builder = $this->db->table('lms_cos')
            ->select('lms_cos.cos_id, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp, lms_cos.cos_hour, lms_cos.cos_status, lms_cos.cos_approve')
            ->select('lms_company.com_id, lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->select('COUNT(DISTINCT lms_cos_enroll.cosen_id) AS enrolled_count')
            ->select('SUM(CASE WHEN lms_cos_enroll.cosen_status_sub = 1 THEN 1 ELSE 0 END) AS completed_count')
            ->select('SUM(CASE WHEN lms_cos_enroll.cosen_status_sub = 2 THEN 1 ELSE 0 END) AS in_progress_count')
            ->select('SUM(CASE WHEN lms_cos_enroll.cosen_status_sub = 0 OR lms_cos_enroll.cosen_status_sub IS NULL THEN 1 ELSE 0 END) AS not_started_count')
            ->select('AVG(NULLIF(lms_cos_enroll.cosen_score_per, 0)) AS avg_score')
            ->join('lms_company', 'lms_company.com_id = lms_cos.com_id', 'left')
            ->join('lms_cos_enroll', 'lms_cos_enroll.cos_id = lms_cos.cos_id AND lms_cos_enroll.cosen_isDelete = 0', 'left')
            ->where('lms_cos.cos_isDelete', '0')
            ->groupBy('lms_cos.cos_id')
            ->orderBy('lms_cos.cos_id', 'DESC')
            ->limit($limit);

        if (! $this->canViewAll($user)) {
            $builder->where('lms_cos.com_id', $user['com_id'] ?? 0);
        }
        if (! empty($filters['com_id'])) {
            $builder->where('lms_cos.com_id', (int) $filters['com_id']);
        }
        if (! empty($filters['cos_id'])) {
            $builder->where('lms_cos.cos_id', (int) $filters['cos_id']);
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['company_name'] = $lang === 'thai'
                ? ($row['com_name_th'] ?: $row['com_name_eng'])
                : ($row['com_name_eng'] ?: $row['com_name_th']);
            $enrolled = (int) ($row['enrolled_count'] ?? 0);
            $completed = (int) ($row['completed_count'] ?? 0);
            $row['completion_rate'] = $enrolled > 0 ? round(($completed / $enrolled) * 100, 2) : 0.0;
            $row['avg_score'] = round((float) ($row['avg_score'] ?? 0), 2);
        }

        return $rows;
    }

    public function scormRows(array $user, string $lang, array $filters = [], int $limit = 5000): array
    {
        $builder = $this->db->table('lms_scm_val')
            ->select('lms_scm_val.id, lms_scm_val.scm_id, lms_scm_val.emp_id, lms_scm_val.var_name, lms_scm_val.var_value')
            ->select('lms_scm.lessons_id, lms_les.les_name_th, lms_les.les_name_eng, lms_les.les_name_jp')
            ->select('lms_cos.cos_id, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en')
            ->select('lms_company.com_id, lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_scm', 'lms_scm.id = lms_scm_val.scm_id', 'left')
            ->join('lms_les', 'lms_les.les_id = lms_scm.lessons_id', 'left')
            ->join('lms_cos', 'lms_cos.cos_id = lms_les.cos_id', 'left')
            ->join('lms_emp', 'lms_emp.emp_id = lms_scm_val.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->where('lms_cos.cos_isDelete', '0')
            ->where('lms_emp.emp_isDelete', '0')
            ->orderBy('lms_scm_val.id', 'DESC')
            ->limit($limit);

        if (! $this->canViewAll($user)) {
            $builder->where('lms_emp.com_id', $user['com_id'] ?? 0);
        }
        if (! empty($filters['com_id'])) {
            $builder->where('lms_emp.com_id', (int) $filters['com_id']);
        }
        if (! empty($filters['cos_id'])) {
            $builder->where('lms_cos.cos_id', (int) $filters['cos_id']);
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['lesson_title'] = $this->localized($row, $lang, 'les_name');
            $row['learner_name'] = $lang === 'thai'
                ? ($row['fullname_th'] ?: $row['fullname_en'])
                : ($row['fullname_en'] ?: $row['fullname_th']);
            $row['company_name'] = $lang === 'thai'
                ? ($row['com_name_th'] ?: $row['com_name_eng'])
                : ($row['com_name_eng'] ?: $row['com_name_th']);
        }

        return $rows;
    }

    public function certificateRows(array $user, string $lang, array $filters = [], int $limit = 5000): array
    {
        $builder = $this->db->table('lms_certificate')
            ->select('lms_certificate.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en')
            ->select('lms_company.com_id, lms_company.com_code, lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_cos', 'lms_cos.cos_id = lms_certificate.cos_id', 'left')
            ->join('lms_emp', 'lms_emp.emp_id = lms_certificate.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->where('lms_cos.cos_isDelete', '0')
            ->where('lms_emp.emp_isDelete', '0')
            ->orderBy('lms_certificate.cert_id', 'DESC')
            ->limit($limit);

        if (! $this->canViewAll($user)) {
            $builder->where('lms_emp.com_id', $user['com_id'] ?? 0);
        }
        if (! empty($filters['com_id'])) {
            $builder->where('lms_emp.com_id', (int) $filters['com_id']);
        }
        if (! empty($filters['cos_id'])) {
            $builder->where('lms_certificate.cos_id', (int) $filters['cos_id']);
        }
        if (! empty($filters['date_start']) && ! empty($filters['date_end'])) {
            $builder->where('lms_certificate.cert_date >=', $filters['date_start'])
                ->where('lms_certificate.cert_date <=', $filters['date_end']);
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['learner_name'] = $lang === 'thai'
                ? ($row['fullname_th'] ?: $row['fullname_en'])
                : ($row['fullname_en'] ?: $row['fullname_th']);
            $row['company_name'] = $lang === 'thai'
                ? ($row['com_name_th'] ?: $row['com_name_eng'])
                : ($row['com_name_eng'] ?: $row['com_name_th']);
        }

        return $rows;
    }

    public function companies(array $user): array
    {
        $builder = $this->db->table('lms_company')
            ->select('com_id, com_code, com_name_th, com_name_eng')
            ->where('com_isDelete', '0')
            ->orderBy('com_code', 'ASC');

        if (! $this->canViewAll($user)) {
            $builder->where('com_id', $user['com_id'] ?? 0);
        }

        return $builder->get()->getResultArray();
    }

    public function courses(): array
    {
        return $this->db->table('lms_cos')
            ->select('cos_id, ccode, cname_th, cname_eng')
            ->where('cos_isDelete', '0')
            ->orderBy('cos_id', 'DESC')
            ->limit(500)
            ->get()
            ->getResultArray();
    }

    private function canViewAll(array $user): bool
    {
        return (string) ($user['ug_viewdata'] ?? '') === '1' || (string) ($user['u_id'] ?? '') === '1' || (string) ($user['com_admin'] ?? '') !== 'com_associated';
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
}
