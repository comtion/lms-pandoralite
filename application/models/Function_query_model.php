<?php
class Function_query_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $sess = $this->session->userdata("user");
    if (!isset($sess["emp_id"])) {
      $empIdCookie = $this->input->cookie('emp_id');
      if ($empIdCookie != "") {
        $this->getUserDataByEmpId($empIdCookie);
      }
    }
  }

  public function loadDB()
  {
    $this->load->database();
  }

  public function closeDB()
  {
    $this->db->close();
  }

  public function getUserDataByEmpId($empId)
  {
    date_default_timezone_set("Asia/Bangkok");

    $this->db->from('lms_usp');
    $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
    $this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
    $this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
    $this->db->where('lms_usp.emp_id', $empId);
    $this->db->where('lms_emp.status', '1');
    $this->db->where('lms_emp.emp_isDelete', '0');
    $this->db->where('lms_usp.u_isDelete', '0');
    $query = $this->db->get();

    if ($query->num_rows() > 0 ) {
      $sessionData = $query->row_array();
      if (!($sessionData['emp_firsttime'] == "1" || $sessionData['firsttime'] == 1)) {
        
        $this->session->set_userdata('username_firsttime', '');
        $this->session->set_userdata('firsttime', false);
        $this->session->set_userdata('passexpire', false);

        $langLast = $sessionData['lang_last'] != "" ? $sessionData['lang_last'] : "english";

        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

        
        $this->session->set_userdata('user', $sessionData);
        $this->session->set_userdata('lang', $langLast);

        if ($sessionData['lang'] == "thai") {
          $name = $sessionData['fullname_th'];
        } else {
          $name = $sessionData['fullname_en'];
        }

        $this->session->set_userdata('name', $name);

        $this->load->helper('cookie');
        setcookie("emp_id", $sessionData["emp_id"]);
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
        
  public function raw_query($sql_command){
    date_default_timezone_set("Asia/Bangkok");
    $query = $this->db->query($sql_command);
    return $query->result_array();
  }
  
  public function raw_row_query($sql_command){
    date_default_timezone_set("Asia/Bangkok");
    $query = $this->db->query($sql_command);
    return $query->row_array();
  }

  public function query_row($table_name = "", $join_name = "", $join_com = "", $join = "", $where_com = "", $order_by = "", $select = "")
  {
    date_default_timezone_set("Asia/Bangkok");
    $this->db->from($table_name);
    if ($join_name != "" && $join_com != "") {
      $this->db->join($join_name, $join_com, $join);
    }
    if ($select != "") {
      $this->db->select($select);
    }
    if ($where_com != "") {
      $this->db->where($where_com);
    }
    if ($order_by != "") {
      $this->db->order_by($order_by);
    }
    $query = $this->db->get();
    return $query->row_array();
  }

  public function query_result($table_name = "", $join_name = "", $join_com = "", $join = "", $where_com = "", $order_by = "", $select = "", $limit = "", $group_by = "")
  {
    date_default_timezone_set("Asia/Bangkok");
    $this->db->from($table_name);
    if ($select != "") {
      $this->db->distinct();
      $this->db->select($select);
    }
    if ($group_by != "") {
      $this->db->group_by($group_by);
    }
    if ($limit != "") {
      $this->db->limit(intval($limit));
    }
    if ($join_name != "" && $join_com != "") {
      $this->db->join($join_name, $join_com, $join);
    }
    if ($where_com != "") {
      $this->db->where($where_com);
    }
    if ($order_by != "") {
      $this->db->order_by($order_by);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  public function numrows($table_name = "", $join_name = "", $join_com = "", $join = "", $where_com = "", $order_by = "", $select = "")
  {
    date_default_timezone_set("Asia/Bangkok");
    $this->db->from($table_name);
    if ($join_name != "" && $join_com != "") {
      $this->db->join($join_name, $join_com, $join);
    }
    if ($select != "") {
      $this->db->distinct();
      $this->db->select($select);
    }
    if ($where_com != "") {
      $this->db->where($where_com);
    }
    if ($order_by != "") {
      $this->db->order_by($order_by);
    }
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function updateData($table_name = "", $where = "", $data = array()) {
      $this->db->where($where);
      $this->db->update($table_name, $data);
      return "2";
  }

  public function insertData($table_name = "", $data = array()) {
      $this->db->insert($table_name, $data);
      return $this->db->insert_id();
  }
}
