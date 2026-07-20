<?php
class Footer_model extends CI_model {
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

  public function getfooter()
  {
    $sess = $this->session->userdata("user");
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->db->from('lms_about');
    $this->db->where('da_id','1');
    $query = $this->db->get();
    $fetch = $query->row_array();
    $arr = array();
    if(empty($sess)){
        $fetch['da_logo_top'] = $fetch['da_logo_top']!=""?$fetch['da_logo_top']:REAL_PATH."/images/logo.png";
        $fetch['da_logo_footer'] = $fetch['da_logo_footer']!=""?$fetch['da_logo_footer']:REAL_PATH."/images/logo_white.png";
      array_push($arr, $fetch);
    }else{
      $fetch_usp = $this->func_query->query_row('lms_usp','lms_emp','lms_usp.emp_id = lms_emp.emp_id','','u_id="'.$sess['u_id'].'"');
      $fetch_chk = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_usp['com_id'].'"');
      $fetch_uspgp = $this->func_query->query_row('lms_usp_gp','','','','ug_id="'.$fetch_usp['ug_id'].'"');
      
      $output = array();
      if(countArray($fetch_chk)>0&&$fetch_usp['com_id']!="2"){
        $output['da_title_th'] = $fetch['da_title_th'];
        $output['da_title_en'] = $fetch['da_title_en'];
        $output['da_company_th'] = $fetch_chk['com_name_th'];
        $output['da_company_en'] = $fetch_chk['com_name_eng'];
        $output['da_address_th'] = $fetch_chk['com_add_th'];
        $output['da_address_en'] = $fetch_chk['com_add_eng'];
        $output['da_contact_main'] = $fetch_chk['com_tel'];
        $output['da_privacy_policy_th'] = $fetch['da_privacy_policy_th'];
        $output['da_privacy_policy_en'] = $fetch['da_privacy_policy_en'];
        $output['da_privacy_policy_jp'] = $fetch['da_privacy_policy_jp'];
        $output['da_contact_fax'] = $fetch_chk['com_fax'];
        $output['da_email_a'] = '';
        $output['da_email_b'] = $fetch_chk['com_mail'];
        $output['da_website'] = $fetch['da_website'];
        $output['fetch_usp'] = $fetch_usp;
        $output['fetch_uspgp'] = $fetch_uspgp;
        $output['da_facebook'] = "";
        $output['da_twitter'] = "";
        $output['da_copyright'] = $fetch['da_copyright'];
        $output['da_logo_top'] = $fetch_chk['com_logo_top']!=""?REAL_PATH."/uploads/logo/".$fetch_chk['com_logo_top']:REAL_PATH."/images/logo.png";
        $output['da_logo_footer'] = $fetch_chk['com_logo_footer']!=""?REAL_PATH."/uploads/logo/".$fetch_chk['com_logo_footer']:REAL_PATH."/images/logo_white.png";
        array_push($arr, $output);
      }else{
        $fetch['da_logo_top'] = $fetch['da_logo_top']!=""?$fetch['da_logo_top']:REAL_PATH."/images/logo.png";
        $fetch['da_logo_footer'] = $fetch['da_logo_footer']!=""?$fetch['da_logo_footer']:REAL_PATH."/images/logo_white.png";
        $fetch['da_website'] = $fetch['da_website'];
        $fetch['fetch_usp'] = $fetch_usp;
        $fetch['fetch_uspgp'] = $fetch_uspgp;
        array_push($arr, $fetch);
      }
    }
    return $arr;
  }

  public function updatefooter($newfoot,$id)
  {
    $this->db->from('lms_foot');
    $this->db->where('id',$id);
    $this->db->set('foot',$newfoot);
    $this->db->update('lms_foot');
  }


}
