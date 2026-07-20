<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->output->set_content_type('application/json; charset=utf-8');
    }

    // GET /api/learners/email?q=virong
    public function searchEmail()
    {
        date_default_timezone_set("Asia/Bangkok");
        $q = trim($this->input->get('q', true) ?? '');
        $limit = (int)($this->input->get('limit', true) ?? 10);
        if ($limit < 1 || $limit > 50) $limit = 10;

        if ($q === '' || mb_strlen($q) < 2) {
            echo json_encode(['ok'=>true,'items'=>[]]); return;
        }

        // ทำให้ match แบบ case-insensitive และ “ขึ้นต้น” มาก่อน “มีอยู่ที่ไหนก็ได้”
        // NOTE: ใช้ binding ป้องกัน SQL injection
        $sql = "
            SELECT DISTINCT lms_usp.useri AS email
            FROM lms_usp
            INNER JOIN lms_emp ON lms_emp.emp_id = lms_usp.emp_id
            WHERE lms_emp.emp_isDelete = 0
              AND (lms_emp.depart_date IS NULL OR lms_emp.depart_date > NOW())
              AND lms_usp.u_status = 1
              AND lms_usp.u_isDelete = 0
              AND (lms_usp.inactivedate > '" . date('Y-m-d H:i') . "' or lms_usp.inactivedate = '0000-00-00 00:00:00')
              AND (
                    lms_usp.useri LIKE ?  -- prefix match (ไวและได้คะแนนมากกว่า)
                 OR lms_usp.useri LIKE ?  -- contains match (พิมพ์บางส่วนที่กลางคำ)
                  )
            ORDER BY 
                CASE 
                    WHEN lms_usp.useri LIKE ? THEN 0  -- prefix มาก่อน
                    ELSE 1
                END,
                lms_usp.useri
            LIMIT {$limit}
        ";

        $termPrefix   = $q . '%';
        $termContains = '%' . $q . '%';

        $rows = $this->db->query($sql, [$termPrefix, $termContains, $termPrefix])->result_array();
        $items = array_map(function($r){ return $r['email']; }, $rows);

        echo json_encode(['ok'=>true, 'items'=>$items]);
    }
}
