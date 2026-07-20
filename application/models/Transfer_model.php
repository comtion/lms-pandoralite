<?php
class Transfer_model extends CI_model {
  public function __construct()
  {
    // Call the CI_Model constructor
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

  public function create_course($data,$cos_id_ori,$cg_id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cos');
          $this->db->where('com_id', $data['com_id']);
          $this->db->where('ccode', $data['ccode']);
          $this->db->where('cname_th', $data['cname_th']);
          $this->db->where('cname_en', $data['cname_en']);
          $this->db->where('status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_cos', $data);
            $id = $this->db->insert_id();

            for($a=0;$a<countArray($cg_id);$a++){
              if($cg_id[$a]!=""){
                  $data_detail['course_id'] = $id;
                  $data_detail['cg_id'] = $cg_id[$a];
                  $this->db->insert('lms_cosincg', $data_detail);
              }
            }
            $arr = array(
              'cos_id'=>$id,
              'cos_id_ori'=>$cos_id_ori,
              'com_id'=>$data['com_id'],
              'tfcos_datetime'=>date('Y-m-d H:i')
            );
            $this->db->insert('lms_transfer_cos',$arr);
            return $id;
          }else{
            return "Error";
          }
  }
}
