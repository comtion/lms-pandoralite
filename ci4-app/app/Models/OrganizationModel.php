<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model
{
    protected $returnType = 'array';

    public function createCompany(array $input, array $actor): array
    {
        $code = strtoupper(trim((string) ($input['com_code'] ?? '')));
        $nameEn = trim((string) ($input['com_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['com_name_th'] ?? $nameEn));

        if ($code === '' || $nameEn === '') {
            return ['ok' => false, 'message' => 'Company code and English name are required.'];
        }

        if (strlen($code) > 5) {
            return ['ok' => false, 'message' => 'Company code must be 5 characters or fewer.'];
        }

        $exists = $this->db->table('lms_company')
            ->where('com_code', $code)
            ->where('com_isDelete', '0')
            ->countAllResults();

        if ($exists > 0) {
            return ['ok' => false, 'message' => 'Company code already exists.'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        $this->db->table('lms_company')->insert([
            'com_code' => $code,
            'com_name_th' => $nameTh,
            'com_name_eng' => $nameEn,
            'com_emaildomain' => trim((string) ($input['com_emaildomain'] ?? '')),
            'com_add_th' => trim((string) ($input['com_add_th'] ?? '')),
            'com_add_eng' => trim((string) ($input['com_add_eng'] ?? '')),
            'com_tel' => trim((string) ($input['com_tel'] ?? '')),
            'com_fax' => trim((string) ($input['com_fax'] ?? '')),
            'com_mail' => trim((string) ($input['com_mail'] ?? '')),
            'com_wctitle_th' => '',
            'com_wctitle_eng' => '',
            'com_wctitle_jp' => '',
            'com_wcmessage_th' => '',
            'com_wcmessage_eng' => '',
            'com_wcmessage_jp' => '',
            'com_admin' => 'com_associated',
            'com_bgpic_user' => '',
            'com_logo_top' => '',
            'com_logo_footer' => '',
            'com_status' => 1,
            'com_isDelete' => 0,
            'com_createby' => (string) ($actor['u_id'] ?? ''),
            'com_createdate' => $now,
            'com_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'com_modifieddate' => $now,
        ]);
        $companyId = (int) $this->db->insertID();
        $this->seedDefaultCourseTypes($companyId, $now);
        $this->recordLog($actor, 'organization', 'Create company: ' . $code);
        $this->db->transComplete();

        return ['ok' => $this->db->transStatus(), 'message' => $this->db->transStatus() ? 'Company created.' : 'Company create failed.'];
    }

    public function createDepartment(array $input, array $actor): array
    {
        $companyId = (int) ($input['com_id'] ?? 0);
        $nameEn = trim((string) ($input['dep_name_en'] ?? ''));
        $nameTh = trim((string) ($input['dep_name_th'] ?? $nameEn));

        if ($companyId <= 0 || $nameEn === '') {
            return ['ok' => false, 'message' => 'Company and English department name are required.'];
        }

        $exists = $this->db->table('lms_depart')
            ->where('com_id', $companyId)
            ->where('dep_name_en', $nameEn)
            ->where('dep_isDelete', '0')
            ->countAllResults();

        if ($exists > 0) {
            return ['ok' => false, 'message' => 'Department already exists for this company.'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('lms_depart')->insert([
            'dep_name_th' => $nameTh,
            'dep_name_en' => $nameEn,
            'com_id' => $companyId,
            'dep_remark' => trim((string) ($input['dep_remark'] ?? '')),
            'dep_status' => 1,
            'dep_isDelete' => 0,
            'dep_createdate' => $now,
            'dep_createby' => (string) ($actor['u_id'] ?? ''),
            'dep_modifiedby' => (string) ($actor['u_id'] ?? ''),
            'dep_modifieddate' => $now,
        ]);
        $this->recordLog($actor, 'organization', 'Create department: ' . $nameEn);

        return ['ok' => true, 'message' => 'Department created.'];
    }

    public function company(int $companyId): ?array
    {
        $row = $this->db->table('lms_company')
            ->where('com_id', $companyId)
            ->where('com_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function department(int $departmentId): ?array
    {
        $row = $this->db->table('lms_depart')
            ->where('dep_id', $departmentId)
            ->where('dep_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function updateCompany(int $companyId, array $input, array $actor): array
    {
        $company = $this->company($companyId);
        if (! $company) {
            return ['ok' => false, 'message' => 'Company not found.'];
        }

        $code = strtoupper(trim((string) ($input['com_code'] ?? '')));
        $nameEn = trim((string) ($input['com_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['com_name_th'] ?? $nameEn));

        if ($code === '' || $nameEn === '') {
            return ['ok' => false, 'message' => 'Company code and English name are required.'];
        }

        if (strlen($code) > 5) {
            return ['ok' => false, 'message' => 'Company code must be 5 characters or fewer.'];
        }

        $duplicate = $this->db->table('lms_company')
            ->where('com_code', $code)
            ->where('com_id !=', $companyId)
            ->where('com_isDelete', '0')
            ->countAllResults();

        if ($duplicate > 0) {
            return ['ok' => false, 'message' => 'Company code already exists.'];
        }

        $this->db->table('lms_company')
            ->where('com_id', $companyId)
            ->update([
                'com_code' => $code,
                'com_name_th' => $nameTh,
                'com_name_eng' => $nameEn,
                'com_emaildomain' => trim((string) ($input['com_emaildomain'] ?? '')),
                'com_add_th' => trim((string) ($input['com_add_th'] ?? '')),
                'com_add_eng' => trim((string) ($input['com_add_eng'] ?? '')),
                'com_tel' => trim((string) ($input['com_tel'] ?? '')),
                'com_fax' => trim((string) ($input['com_fax'] ?? '')),
                'com_mail' => trim((string) ($input['com_mail'] ?? '')),
                'com_modifiedby' => (string) ($actor['u_id'] ?? ''),
                'com_modifieddate' => date('Y-m-d H:i:s'),
            ]);

        $this->recordLog($actor, 'organization', 'Update company: ' . $code);

        return ['ok' => true, 'message' => 'Company updated.'];
    }

    public function updateDepartment(int $departmentId, array $input, array $actor): array
    {
        $department = $this->department($departmentId);
        if (! $department) {
            return ['ok' => false, 'message' => 'Department not found.'];
        }

        $companyId = (int) ($input['com_id'] ?? 0);
        $nameEn = trim((string) ($input['dep_name_en'] ?? ''));
        $nameTh = trim((string) ($input['dep_name_th'] ?? $nameEn));

        if ($companyId <= 0 || $nameEn === '') {
            return ['ok' => false, 'message' => 'Company and English department name are required.'];
        }

        $duplicate = $this->db->table('lms_depart')
            ->where('com_id', $companyId)
            ->where('dep_name_en', $nameEn)
            ->where('dep_id !=', $departmentId)
            ->where('dep_isDelete', '0')
            ->countAllResults();

        if ($duplicate > 0) {
            return ['ok' => false, 'message' => 'Department already exists for this company.'];
        }

        $this->db->table('lms_depart')
            ->where('dep_id', $departmentId)
            ->update([
                'dep_name_th' => $nameTh,
                'dep_name_en' => $nameEn,
                'com_id' => $companyId,
                'dep_remark' => trim((string) ($input['dep_remark'] ?? '')),
                'dep_modifiedby' => (string) ($actor['u_id'] ?? ''),
                'dep_modifieddate' => date('Y-m-d H:i:s'),
            ]);

        $this->recordLog($actor, 'organization', 'Update department: ' . $nameEn);

        return ['ok' => true, 'message' => 'Department updated.'];
    }

    public function setCompanyStatus(int $companyId, int $status, array $actor): array
    {
        return $this->setStatus('lms_company', 'com_id', $companyId, 'com_status', $status, $actor, 'company');
    }

    public function setDepartmentStatus(int $departmentId, int $status, array $actor): array
    {
        return $this->setStatus('lms_depart', 'dep_id', $departmentId, 'dep_status', $status, $actor, 'department');
    }

    public function activeCompanies(): array
    {
        return $this->db->table('lms_company')
            ->select('com_id, com_code, com_name_eng, com_name_th')
            ->where('com_isDelete', '0')
            ->where('com_status', '1')
            ->orderBy('com_code', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function setStatus(string $table, string $idField, int $id, string $statusField, int $status, array $actor, string $label): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        $this->db->table($table)
            ->where($idField, $id)
            ->update([
                $statusField => $status,
                str_replace('_status', '_modifiedby', $statusField) => (string) ($actor['u_id'] ?? ''),
                str_replace('_status', '_modifieddate', $statusField) => date('Y-m-d H:i:s'),
            ]);

        $this->recordLog($actor, 'organization', 'Set ' . $label . ' status: ' . $id . ' => ' . $status);

        return ['ok' => true, 'message' => ucfirst($label) . ' status updated.'];
    }

    private function seedDefaultCourseTypes(int $companyId, string $now): void
    {
        foreach ([
            ['tc_name_th' => 'E-learning', 'tc_name_en' => 'E-learning', 'tc_lesson' => 1, 'tc_pretest' => 1, 'tc_questionnaire' => 1, 'tc_qrcode' => 0, 'tc_student_enroll' => 1, 'tc_courselearner' => 1],
            ['tc_name_th' => 'Classroom', 'tc_name_en' => 'Classroom', 'tc_lesson' => 0, 'tc_pretest' => 0, 'tc_questionnaire' => 0, 'tc_qrcode' => 1, 'tc_student_enroll' => 0, 'tc_courselearner' => 1],
        ] as $type) {
            $type['com_id'] = $companyId;
            $type['tc_createdate'] = $now;
            $type['tc_modifeddate'] = $now;
            $type['tc_status'] = 1;
            $type['tc_color'] = '';
            $type['tc_doccos'] = 0;
            $this->db->table('lms_typecos')->insert($type);
        }
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
