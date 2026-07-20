<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuleModel extends Model
{
    protected $returnType = 'array';

    public function dataFor(string $path, string $lang): array
    {
        $config = $this->configFor($path);
        $rows = $this->rows($config);

        return [
            'summary' => $this->summary($path),
            'records' => $this->decorateRows($rows, $config, $lang),
            'columns' => $config['columns'],
            'source' => $config['table'],
            'mode' => $config['mode'] ?? 'overview',
            'companies' => in_array(($config['mode'] ?? ''), ['department'], true) ? (new OrganizationModel())->activeCompanies() : [],
        ];
    }

    public function hasConfiguredModule(string $path): bool
    {
        return array_key_exists($path, $this->moduleMap());
    }

    private function configFor(string $path): array
    {
        $map = $this->moduleMap();

        return $map[$path] ?? ['table' => 'lms_menu', 'title' => ['mu_name_en', 'mu_name_th'], 'columns' => ['mu_id', 'title', 'mu_path', 'mu_status'], 'where' => ['mu_path' => $path], 'order' => ['mu_id', 'DESC']];
    }

    private function moduleMap(): array
    {
        return [
            'surveylink' => ['table' => 'lms_sv', 'title' => ['sv_title_eng', 'sv_title_th'], 'columns' => ['sv_id', 'title', 'sv_public', 'sv_approve', 'sv_status'], 'where' => ['sv_isDelete' => '0'], 'order' => ['sv_id', 'DESC'], 'mode' => 'survey'],
            'survey/list_survey' => ['table' => 'lms_sv', 'title' => ['sv_title_eng', 'sv_title_th'], 'columns' => ['sv_id', 'title', 'sv_public', 'sv_approve', 'sv_status'], 'where' => ['sv_isDelete' => '0'], 'order' => ['sv_id', 'DESC'], 'mode' => 'survey'],
            'survey' => ['table' => 'lms_sv', 'title' => ['sv_title_eng', 'sv_title_th'], 'columns' => ['sv_id', 'title', 'sv_public', 'sv_approve', 'sv_status'], 'where' => ['sv_isDelete' => '0', 'sv_status' => '1'], 'order' => ['sv_id', 'DESC'], 'mode' => 'survey'],
            'survey/report_survey' => ['table' => 'lms_sv_tc', 'title' => ['svtc_id'], 'columns' => ['svtc_id', 'sv_id', 'emp_id', 'svtc_status', 'svtc_datetime'], 'order' => ['svtc_id', 'DESC'], 'mode' => 'survey'],
            'qrcode/create' => ['table' => 'lms_qrcode', 'title' => ['qr_name'], 'columns' => ['qr_id', 'qr_name', 'qr_type', 'qr_path', 'qr_status'], 'where' => ['qr_isDelete' => '0'], 'order' => ['qr_id', 'DESC'], 'mode' => 'asset'],
            'users' => ['table' => 'lms_emp', 'title' => ['fullname_en', 'fullname_th'], 'columns' => ['emp_id', 'emp_c', 'title', 'email', 'status'], 'where' => ['emp_isDelete' => '0'], 'order' => ['emp_id', 'DESC'], 'mode' => 'user'],
            'manage/userdata' => ['table' => 'lms_emp', 'title' => ['fullname_en', 'fullname_th'], 'columns' => ['emp_id', 'emp_c', 'title', 'email', 'status'], 'where' => ['emp_isDelete' => '0'], 'order' => ['emp_id', 'DESC'], 'mode' => 'user'],
            'manage/companydata' => ['table' => 'lms_company', 'title' => ['com_name_eng', 'com_name_th'], 'columns' => ['com_id', 'com_code', 'title', 'com_mail', 'com_status'], 'where' => ['com_isDelete' => '0'], 'order' => ['com_id', 'DESC'], 'mode' => 'company'],
            'manage/departmentdata' => ['table' => 'lms_depart', 'title' => ['dep_name_en', 'dep_name_th'], 'columns' => ['dep_id', 'title', 'com_id', 'dep_status'], 'where' => ['dep_isDelete' => '0'], 'order' => ['dep_id', 'DESC'], 'mode' => 'department'],
            'dashboard/unlockAcc' => ['table' => 'lms_usp', 'title' => ['useri'], 'columns' => ['u_id', 'title', 'login', 'u_lockdate', 'st_on', 'last_act'], 'where' => ['u_isDelete' => '0', 'login' => '0'], 'order' => ['u_lockdate', 'DESC'], 'mode' => 'unlock'],
            'dashboard/resetPass' => ['table' => 'lms_usp', 'title' => ['useri'], 'columns' => ['u_id', 'title', 'login', 'firsttime', 'expiredate', 'st_on'], 'where' => ['u_isDelete' => '0'], 'order' => ['u_id', 'DESC'], 'mode' => 'reset_password'],
            'report' => ['table' => 'lms_cos_enroll', 'title' => ['cosen_id'], 'columns' => ['cosen_id', 'cos_id', 'emp_id', 'cosen_status_sub', 'cosen_score_per'], 'where' => ['cosen_isDelete' => '0'], 'order' => ['cosen_id', 'DESC'], 'mode' => 'report'],
            'report/loadreport_company' => ['table' => 'lms_company', 'title' => ['com_name_eng', 'com_name_th'], 'columns' => ['com_id', 'com_code', 'title', 'com_status'], 'where' => ['com_isDelete' => '0'], 'order' => ['com_id', 'DESC'], 'mode' => 'report'],
            'generalreport' => ['table' => 'lms_cos_enroll', 'title' => ['cosen_id'], 'columns' => ['cosen_id', 'cos_id', 'emp_id', 'cosen_status_sub', 'cosen_score_per'], 'where' => ['cosen_isDelete' => '0'], 'order' => ['cosen_id', 'DESC'], 'mode' => 'report'],
            'report/learnerReport' => ['table' => 'lms_cos_enroll', 'title' => ['cosen_id'], 'columns' => ['cosen_id', 'cos_id', 'emp_id', 'cosen_status_sub', 'cosen_firsttime'], 'where' => ['cosen_isDelete' => '0'], 'order' => ['cosen_id', 'DESC'], 'mode' => 'report'],
            'report/loadreport_coursename' => ['table' => 'lms_cos', 'title' => ['cname_eng', 'cname_th'], 'columns' => ['cos_id', 'ccode', 'title', 'cos_public', 'cos_approve', 'cos_status'], 'where' => ['cos_isDelete' => '0'], 'order' => ['cos_id', 'DESC'], 'mode' => 'report'],
            'report/loadreport_student' => ['table' => 'lms_emp', 'title' => ['fullname_en', 'fullname_th'], 'columns' => ['emp_id', 'emp_c', 'title', 'email', 'status'], 'where' => ['emp_isDelete' => '0'], 'order' => ['emp_id', 'DESC'], 'mode' => 'report'],
            'report/loadreport_survey' => ['table' => 'lms_sv_tc', 'title' => ['svtc_id'], 'columns' => ['svtc_id', 'sv_id', 'emp_id', 'svtc_status', 'svtc_datetime'], 'order' => ['svtc_id', 'DESC'], 'mode' => 'report'],
            'report/loadreport_personal' => ['table' => 'lms_cos_enroll', 'title' => ['cosen_id'], 'columns' => ['cosen_id', 'cos_id', 'emp_id', 'cosen_status_sub', 'cosen_score_per'], 'where' => ['cosen_isDelete' => '0'], 'order' => ['cosen_id', 'DESC'], 'mode' => 'report'],
            'log/view' => ['table' => 'lms_lg', 'title' => ['massage'], 'columns' => ['id', 'log_type', 'emp_id', 'title', 'ip', 'log_time'], 'order' => ['id', 'DESC'], 'mode' => 'log'],
            'log/logEmail' => ['table' => 'lms_lg_email', 'title' => ['lgm_id'], 'columns' => ['lgm_id', 'lgm_date', 'lgm_send', 'lgm_send_complete', 'lgm_send_error'], 'order' => ['lgm_id', 'DESC'], 'mode' => 'log'],
            'log/logImportUsers' => ['table' => 'lms_lg_import', 'title' => ['lgi_id'], 'columns' => ['lgi_id', 'lgi_import_by', 'lgi_datetime', 'lgi_new_user', 'lgi_duplicate_user', 'lgi_remove_user'], 'order' => ['lgi_id', 'DESC'], 'mode' => 'log'],
            'setting' => ['table' => 'lms_about', 'title' => ['da_title_en', 'da_title_th'], 'columns' => ['da_id', 'title', 'da_company_en', 'da_contact_main', 'da_email_a'], 'order' => ['da_id', 'DESC'], 'mode' => 'setting'],
            'general_setting' => ['table' => 'lms_about', 'title' => ['da_title_en', 'da_title_th'], 'columns' => ['da_id', 'title', 'da_company_en', 'da_contact_main', 'da_email_a'], 'order' => ['da_id', 'DESC'], 'mode' => 'setting'],
            'setting/ManageECT' => ['table' => 'lms_about', 'title' => ['da_title_en', 'da_title_th'], 'columns' => ['da_id', 'title', 'da_company_en', 'da_contact_main', 'da_email_a'], 'order' => ['da_id', 'DESC'], 'mode' => 'setting'],
            'setting/ManageBanner' => ['table' => 'lms_ban', 'title' => ['banner'], 'columns' => ['id', 'title', 'hidden', 'emp_c', 'time_created'], 'order' => ['id', 'DESC'], 'mode' => 'asset'],
            'setting/ManageBannerCourse' => ['table' => 'lms_ban_cos', 'title' => ['bc_name_eng', 'bc_name_th'], 'columns' => ['bc_id', 'title', 'bc_type', 'bc_status', 'bc_image'], 'where' => ['bc_isDelete' => '0'], 'order' => ['bc_id', 'DESC'], 'mode' => 'asset'],
            'coursetype/loadCourseType' => ['table' => 'lms_typecos', 'title' => ['tc_name_en', 'tc_name_th'], 'columns' => ['tc_id', 'title', 'tc_lesson', 'tc_pretest', 'tc_status'], 'order' => ['tc_id', 'DESC'], 'mode' => 'setting'],
            'manage_email' => ['table' => 'lms_setting_mail', 'title' => ['sm_sender'], 'columns' => ['sm_id', 'sm_host', 'sm_port', 'title', 'sm_emailsender'], 'order' => ['sm_id', 'DESC'], 'mode' => 'setting'],
            'setting/setting_email' => ['table' => 'lms_setting_mail', 'title' => ['sm_sender'], 'columns' => ['sm_id', 'sm_host', 'sm_port', 'title', 'sm_emailsender'], 'order' => ['sm_id', 'DESC'], 'mode' => 'setting'],
            'setting/format_email' => ['table' => 'lms_sendmail_form', 'title' => ['smf_subject_en', 'smf_subject_th'], 'columns' => ['smf_id', 'smf_type', 'title', 'smf_show', 'smf_createdate'], 'order' => ['smf_id', 'DESC'], 'mode' => 'setting'],
            'setting/ManageFAQ' => ['table' => 'lms_faq', 'title' => ['title'], 'columns' => ['id', 'title', 'lang', 'emp_c', 'time_created'], 'order' => ['id', 'DESC'], 'mode' => 'setting'],
            'setting/ManageMenu' => ['table' => 'lms_menu', 'title' => ['mu_name_en', 'mu_name_th'], 'columns' => ['mu_id', 'title', 'mu_path', 'mu_parent', 'mu_status'], 'order' => ['mu_num', 'ASC'], 'mode' => 'setting'],
            'manage/groupuserdata' => ['table' => 'lms_usp_gp', 'title' => ['ug_name_en', 'ug_name_th'], 'columns' => ['ug_id', 'ug_code', 'title', 'Is_admin', 'ug_status'], 'where' => ['ug_isDelete' => '0'], 'order' => ['ug_id', 'DESC'], 'mode' => 'group'],
            'certificate/certificateall' => ['table' => 'lms_certificate', 'title' => ['cert_file'], 'columns' => ['cert_id', 'cos_id', 'emp_id', 'title', 'cert_date'], 'order' => ['cert_id', 'DESC'], 'mode' => 'course'],
            'learning_system' => ['table' => 'lms_qiz', 'title' => ['quiz_name_eng', 'quiz_name_th'], 'columns' => ['qiz_id', 'cos_id', 'title', 'quiz_type', 'quiz_status'], 'where' => ['quiz_isDelete' => '0'], 'order' => ['qiz_id', 'DESC'], 'mode' => 'course'],
            'questionnaire/create' => ['table' => 'lms_questionnaire', 'title' => ['qn_title_eng', 'qn_title_th'], 'columns' => ['qn_id', 'com_id', 'title', 'qn_status', 'qn_createdate'], 'where' => ['qn_isDelete' => '0'], 'order' => ['qn_id', 'DESC'], 'mode' => 'course'],
            'quiz/create_template' => ['table' => 'lms_qiz', 'title' => ['quiz_name_eng', 'quiz_name_th'], 'columns' => ['qiz_id', 'cos_id', 'title', 'quiz_type', 'quiz_status'], 'where' => ['quiz_isDelete' => '0'], 'order' => ['qiz_id', 'DESC'], 'mode' => 'course'],
            'setting/usermanual' => ['table' => 'lms_about', 'title' => ['da_title_en', 'da_title_th'], 'columns' => ['da_id', 'title', 'da_manual_sa_eng', 'da_manual_is_eng', 'da_manual_ln_eng'], 'order' => ['da_id', 'DESC'], 'mode' => 'asset'],
        ];
    }

    private function rows(array $config): array
    {
        $builder = $this->db->table($config['table']);
        foreach (($config['where'] ?? []) as $field => $value) {
            $builder->where($field, $value);
        }
        if (! empty($config['order'])) {
            $builder->orderBy($config['order'][0], $config['order'][1]);
        }

        return $builder->limit(100)->get()->getResultArray();
    }

    private function decorateRows(array $rows, array $config, string $lang): array
    {
        return array_map(function (array $row) use ($config) {
            $row['title'] = $this->firstValue($row, $config['title']);
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    $row[$key] = mb_strimwidth(strip_tags($value), 0, 120, '...');
                }
            }
            return $row;
        }, $rows);
    }

    private function firstValue(array $row, array $fields): string
    {
        foreach ($fields as $field) {
            $value = trim((string) ($row[$field] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return '-';
    }

    private function summary(string $path): array
    {
        return [
            ['label' => 'Companies', 'value' => $this->count('lms_company', ['com_isDelete' => '0'])],
            ['label' => 'Users', 'value' => $this->count('lms_emp', ['emp_isDelete' => '0'])],
            ['label' => 'Courses', 'value' => $this->count('lms_cos', ['cos_isDelete' => '0'])],
            ['label' => 'Surveys', 'value' => $this->count('lms_sv', ['sv_isDelete' => '0'])],
        ];
    }

    private function count(string $table, array $where): int
    {
        $builder = $this->db->table($table);
        foreach ($where as $field => $value) {
            $builder->where($field, $value);
        }

        return $builder->countAllResults();
    }
}
