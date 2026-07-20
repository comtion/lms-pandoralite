<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditlog extends CI_Controller
{
    public function view()
    {
        $arr['page'] = 'auditlog/view';
        $user = $this->session->userdata('user');
        if ($user == null) {
            redirect(base_url().'dashboard/login?redirect='.$arr['page']);
        }

        $arr['emp_c'] = isset($user['emp_c']) ? $user['emp_c'] : '';
        $arr['com_admin'] = isset($user['com_admin']) ? $user['com_admin'] : '';
        $arr['com_id'] = isset($user['com_id']) ? $user['com_id'] : '';
        $arr['user_data'] = $user;
        $arr['user'] = $user;

        $lang = $this->session->userdata('lang') == null ? 'english' : $this->session->userdata('lang');
        $this->lang->load($lang, $lang);
        $arr['lang'] = $lang;

        $this->load->model('Manage_model', 'manage', FALSE);
        $this->load->model('Auditlog_model', 'auditlog', FALSE);
        $this->manage->loadDB();

        $arr['arr_permission'] = $this->manage->chk_permission_page();
        $arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
        $arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');

        if ($arr['btn_view'] !== '1' && (!isset($user['ug_id']) || (string) $user['ug_id'] !== '1')) {
            redirect(base_url().'dashboard');
        }

        $arr['main_menu'] = $this->manage->checkmenu();
        $arr['title'] = $this->manage->get_namemenu($arr['page']);
        $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
        $arr['title'] = $arr['title'] !== '' ? $arr['title'] : 'Audit Log';
        $arr['title_main'] = $arr['title_main'] !== '' ? $arr['title_main'] : 'System';
        $arr['submenu'] = array();
        $arr['submenu_b'] = array();

        foreach ($arr['main_menu'] as $value_mainmenu) {
            $li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
            if (countArray($li_arr_sub)) {
                $arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
                foreach ($li_arr_sub as $value_sub) {
                    $li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
                    if (countArray($li_arr_sub_b) > 0) {
                        $arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
                    }
                }
            }
        }

        $this->auditlog->loadDB();
        $filters = $this->auditlog->getFilters();
        $arr['audit_tables'] = $filters['tables'];
        $arr['audit_actions'] = $filters['actions'];
        $this->auditlog->closeDB();

        $this->load->model('Footer_model', 'foot', FALSE);
        $this->foot->loadDB();
        $arr['foote'] = $this->foot->getfooter();
        $this->foot->closeDB();

        $this->load->view('frontend/auditlog', $arr);
    }

    public function fetch()
    {
        $this->requireAuditAccess();

        $this->load->model('Auditlog_model', 'auditlog', FALSE);
        $this->auditlog->loadDB();
        $records = $this->auditlog->getRecords($this->inputFilters());
        $this->auditlog->closeDB();

        $data = array();
        foreach ($records as $row) {
            $actor = trim((string) $row['audit_user_display']);
            if ($actor === '') {
                $actor = trim((string) $row['audit_username']);
            }
            if ($actor === '') {
                $actor = 'System/Unknown';
            }

            $historyButton = '';
            if (trim((string) $row['audit_row_key']) !== '') {
                $historyButton = ' <button type="button" class="btn btn-sm btn-outline-secondary audit-history" data-table="'.html_escape($row['audit_table']).'" data-row-key="'.html_escape((string) $row['audit_row_key']).'"><i class="mdi mdi-history"></i> History</button>';
            }

            $data[] = array(
                $row['audit_id'],
                html_escape($row['audit_created_at']),
                html_escape($row['audit_action']),
                html_escape($row['audit_table']),
                html_escape((string) $row['audit_row_key']),
                html_escape($actor),
                html_escape(trim($row['audit_controller'].'/'.$row['audit_method'], '/')),
                html_escape((string) $row['audit_ip']),
                '<button type="button" class="btn btn-sm btn-outline-info audit-detail" data-id="'.(int) $row['audit_id'].'"><i class="mdi mdi-eye"></i> Detail</button>'.$historyButton,
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('data' => $data)));
    }

    public function detail($auditId = 0)
    {
        $this->requireAuditAccess();

        $this->load->model('Auditlog_model', 'auditlog', FALSE);
        $this->auditlog->loadDB();
        $record = $this->auditlog->getRecord($auditId);
        $this->auditlog->closeDB();

        if (!$record) {
            show_404();
            return;
        }
        $scope = $this->scopeFilters();
        if (isset($scope['com_id']) && (string) $record['audit_com_id'] !== (string) $scope['com_id']) {
            show_error('Permission denied', 403);
        }

        $record['audit_old_values'] = $this->decodeJson($record['audit_old_values']);
        $record['audit_new_values'] = $this->decodeJson($record['audit_new_values']);
        $record['audit_changed_values'] = $this->decodeJson($record['audit_changed_values']);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function history()
    {
        $this->requireAuditAccess();

        $table = trim((string) $this->input->get('table'));
        $rowKey = trim((string) $this->input->get('row_key'));
        if ($table === '' || $rowKey === '') {
            show_error('table and row_key are required', 400);
        }

        $this->load->model('Auditlog_model', 'auditlog', FALSE);
        $this->auditlog->loadDB();
        $records = $this->auditlog->getRecordHistory($table, $rowKey, $this->scopeFilters());
        $this->auditlog->closeDB();

        foreach ($records as &$record) {
            $record['audit_old_values'] = $this->decodeJson($record['audit_old_values']);
            $record['audit_new_values'] = $this->decodeJson($record['audit_new_values']);
            $record['audit_changed_values'] = $this->decodeJson($record['audit_changed_values']);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('data' => $records), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function exportCsv()
    {
        $this->requireAuditAccess();

        $this->load->model('Auditlog_model', 'auditlog', FALSE);
        $this->auditlog->loadDB();
        $records = $this->auditlog->getRecords($this->inputFilters(), 50000);
        $this->auditlog->closeDB();

        $filename = 'audit_log_'.date('Ymd_His').'.csv';
        $lines = array();
        $headers = array(
            'audit_id',
            'created_at',
            'action',
            'table',
            'row_key',
            'user_id',
            'emp_id',
            'com_id',
            'username',
            'user_display',
            'controller',
            'method',
            'uri',
            'ip',
            'sql',
            'changed_values',
        );
        $lines[] = $this->csvLine($headers);

        foreach ($records as $row) {
            $lines[] = $this->csvLine(array(
                $row['audit_id'],
                $row['audit_created_at'],
                $row['audit_action'],
                $row['audit_table'],
                $row['audit_row_key'],
                $row['audit_user_id'],
                $row['audit_emp_id'],
                isset($row['audit_com_id']) ? $row['audit_com_id'] : '',
                $row['audit_username'],
                $row['audit_user_display'],
                $row['audit_controller'],
                $row['audit_method'],
                $row['audit_uri'],
                $row['audit_ip'],
                isset($row['audit_sql']) ? $row['audit_sql'] : '',
                isset($row['audit_changed_values']) ? $row['audit_changed_values'] : '',
            ));
        }

        $this->output
            ->set_content_type('text/csv; charset=utf-8')
            ->set_header('Content-Disposition: attachment; filename="'.$filename.'"')
            ->set_output("\xEF\xBB\xBF".implode("\r\n", $lines));
    }

    protected function requireAuditAccess()
    {
        $user = $this->session->userdata('user');
        if ($user == null) {
            show_error('Authentication required', 401);
        }

        $this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
        $canView = $this->manage->chk_permission('auditlog/view', 'ru_view');
        $this->manage->closeDB();

        if ($canView !== '1' && (!isset($user['ug_id']) || (string) $user['ug_id'] !== '1')) {
            show_error('Permission denied', 403);
        }
    }

    protected function inputFilters()
    {
        $dateStart = trim((string) $this->input->get('date_start'));
        $timeStart = trim((string) $this->input->get('time_start'));
        $dateEnd = trim((string) $this->input->get('date_end'));
        $timeEnd = trim((string) $this->input->get('time_end'));

        return array(
            'table' => trim((string) $this->input->get('table')),
            'action' => trim((string) $this->input->get('action')),
            'row_key' => trim((string) $this->input->get('row_key')),
            'keyword' => trim((string) $this->input->get('keyword')),
            'date_start' => $dateStart !== '' ? $dateStart.' '.($timeStart !== '' ? $timeStart : '00:00').':00' : '',
            'date_end' => $dateEnd !== '' ? $dateEnd.' '.($timeEnd !== '' ? $timeEnd : '23:59').':59' : '',
        ) + $this->scopeFilters();
    }

    protected function scopeFilters()
    {
        $user = $this->session->userdata('user');
        if (isset($user['ug_id']) && (string) $user['ug_id'] === '1') {
            return array();
        }
        if (isset($user['com_id']) && $user['com_id'] !== '') {
            return array('com_id' => $user['com_id']);
        }

        return array('com_id' => '__NO_COMPANY_SCOPE__');
    }

    protected function decodeJson($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    protected function csvLine($values)
    {
        $escaped = array();
        foreach ($values as $value) {
            $value = (string) $value;
            $escaped[] = '"'.str_replace('"', '""', $value).'"';
        }

        return implode(',', $escaped);
    }
}
