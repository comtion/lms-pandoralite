<?php
class Blog_model extends CI_Model {
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

    /*These Function is Open/Close the database.*/
    public function loadDB(){
      $this->load->database();
    }

    public function closeDB(){
      $this->db->close();
    }

    public function createB($data){
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $this->db->set('time_created', $time);
      $this->db->set('time_edited', $time);
      $this->db->insert('lms_blg', $data);
    }

    public function editB($id, $data){
      $this->db->from('lms_blg');
      $this->db->where('id', $id);
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $this->db->set('time_edited', $time);
      $this->db->update('lms_blg', $data);
    }

    public function sethide($id,$cm){
      $this->db->from('lms_blg');
      $this->db->where('id', $id);
      $data = array(
        'approve' => $cm
      );
      $this->db->update('lms_blg', $data);
    }

    public function deleteB($id){
      $this->db->where('id', $id);
      $this->db->delete('lms_blg');
    }

    public function getBData($role,$lang){
      $this->db->from('lms_blg');
      $this->db->where('lang', $lang);
      if(!in_array($role, array("superadmin", "admin")))
      {
        $this->db->where('approve', "yes");
      }
      $this->db->order_by('id',"desc");
      $query = $this->db->get();
      return $query->result_array();
    }

    public function getmyBData($emp_c,$role,$lang){
      $this->db->from('lms_blg');
      $this->db->where('emp_c', $emp_c);
      $this->db->where('lang', $lang);
      $this->db->order_by('id',"desc");
      $query = $this->db->get();
      return $query->result_array();
    }

    public function getBlog($id)
    {
      $this->db->from('lms_blg');
      $this->db->where('id', $id);
      $query = $this->db->get();
      return $query->result_array();
    }

    public function getnameE($emp_c,$lang)
    {
      $this->db->from('lms_emp');
      $this->db->where('emp_c', $emp_c);
      $this->db->where('lang', $lang);
      $query = $this->db->get();
      return $query->result_array();
    }

    public function isApproved($id) {
      $this->db->from('lms_blg');
      $this->db->where('id', $id);
      $this->db->where('approve', 'yes');
      $query = $this->db->get();
      $row = $query->row_array();

      if(empty($row)) {
        return FALSE;
      }
      return TRUE;
    }

    public function checkmyblog($emp_c,$id)
    {
      $this->db->from('lms_blg');
      $this->db->where('id', $id);
      $this->db->where('emp_c', $emp_c);
      $query = $this->db->get();
      $row = $query->row_array();

      if(empty($row)) {
        return FALSE;
      }
      return TRUE;
    }

}
