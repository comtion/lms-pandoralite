<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditlog_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadDB()
    {
        $this->load->database();
    }

    public function closeDB()
    {
        $this->db->close();
    }

    public function getFilters()
    {
        $tables = $this->db
            ->select('audit_table')
            ->from('lms_audit_logs')
            ->where('audit_table IS NOT NULL', NULL, FALSE)
            ->group_by('audit_table')
            ->order_by('audit_table', 'ASC')
            ->get()
            ->result_array();

        $actions = $this->db
            ->select('audit_action')
            ->from('lms_audit_logs')
            ->group_by('audit_action')
            ->order_by('audit_action', 'ASC')
            ->get()
            ->result_array();

        return array(
            'tables' => $tables,
            'actions' => $actions,
        );
    }

    public function getRecords($filters = array(), $limit = 500)
    {
        $this->db->select('audit_id,audit_action,audit_table,audit_row_key,audit_controller,audit_method,audit_uri,audit_ip,audit_user_id,audit_emp_id,audit_com_id,audit_username,audit_user_display,audit_created_at,audit_sql,audit_changed_values');
        $this->db->from('lms_audit_logs');
        $this->applyFilters($filters);
        $this->db->order_by('audit_created_at', 'DESC');
        $this->db->order_by('audit_id', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result_array();
    }

    public function getRecord($auditId)
    {
        return $this->db
            ->from('lms_audit_logs')
            ->where('audit_id', (int) $auditId)
            ->get()
            ->row_array();
    }

    public function getRecordHistory($table, $rowKey, $filters = array(), $limit = 200)
    {
        $this->db->from('lms_audit_logs');
        $this->db->where('audit_table', $table);
        $this->db->like('audit_row_key', $rowKey);
        if (!empty($filters['com_id'])) {
            $this->db->where('audit_com_id', $filters['com_id']);
        }
        $this->db->order_by('audit_created_at', 'DESC');
        $this->db->order_by('audit_id', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result_array();
    }

    protected function applyFilters($filters)
    {
        if (!empty($filters['com_id'])) {
            $this->db->where('audit_com_id', $filters['com_id']);
        }
        if (!empty($filters['table'])) {
            $this->db->where('audit_table', $filters['table']);
        }
        if (!empty($filters['action'])) {
            $this->db->where('audit_action', $filters['action']);
        }
        if (!empty($filters['row_key'])) {
            $this->db->like('audit_row_key', $filters['row_key']);
        }
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $this->db->group_start();
            $this->db->like('audit_username', $keyword);
            $this->db->or_like('audit_user_display', $keyword);
            $this->db->or_like('audit_uri', $keyword);
            $this->db->or_like('audit_sql', $keyword);
            $this->db->or_like('audit_changed_values', $keyword);
            $this->db->group_end();
        }
        if (!empty($filters['date_start'])) {
            $this->db->where('audit_created_at >=', $filters['date_start']);
        }
        if (!empty($filters['date_end'])) {
            $this->db->where('audit_created_at <=', $filters['date_end']);
        }
    }
}
