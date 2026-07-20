<?php
class Workgroup_model extends CI_Model {

  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }
  public function loadDB(){ $this->load->database(); }
  public function closeDB(){ $this->db->close(); }

  private function course_availability_where($alias = 'lms_cos_detail')
  {
    $now = date('Y-m-d H:i');
    return "((".$alias.".date_start IS NULL OR CAST(".$alias.".date_start AS CHAR) = '' OR ".$alias.".date_start = '0000-00-00 00:00:00' OR ".$alias.".date_start <= '".$now."')"
      ." AND (".$alias.".date_end IS NULL OR CAST(".$alias.".date_end AS CHAR) = '' OR ".$alias.".date_end = '0000-00-00 00:00:00' OR ".$alias.".date_end >= '".$now."'))";
  }

  public function rechk_permission_wg(){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_cos_detail.cos_id');
    $this->db->from('lms_cos_detail');
    $this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
    $this->db->where('lms_cos_detail_ug.ug_id',$user['ug_id']);
    $this->db->where('lms_cos_detail.status', '1' );
      $where = $this->course_availability_where('lms_cos_detail');
      $this->db->where($where);/*
    $this->db->where('lms_cos_detail.date_start <=', date('Y-m-d') );
    $this->db->where('lms_cos_detail.date_end >=', date('Y-m-d') );*/
    $query = $this->db->get();
    $fetch = $query->result_array();
    $wg_id = array();
    if(countArray($fetch)>0){
      foreach ($fetch as $key => $value) {
          $this->db->select('wg_id');
          $this->db->from('lms_cos');
          $this->db->where('id',$value['cos_id']);
          $query_cos = $this->db->get();
          $row_cos = $query_cos->row_array();
          if(countArray($row_cos)>0){
            if(!in_array($row_cos['wg_id'], $wg_id)){
              array_push($wg_id, $row_cos['wg_id']);
            }
          }
      }
    }
    return $wg_id;
  }
  public function getAllWorkgroup( $id = "",$txt_search="", $wcode_set = array()){
    $user = $this->session->userdata('user');
   // $role = $user['role'];
    $ar_return = array();
    if( sizeof( $wcode_set ) == 0 ){
      $this->db->from('lms_wkg');
      $this->db->order_by('c_date', 'DESC');
      $this->db->where('wstatus', '1' );
      if($id!=""){
        $this->db->where('id', $id );
      }
      if($txt_search!=""){
        $where = "(wtitle_th like '%".$txt_search."%' OR wtitle_en like '%".$txt_search."%')";
        $this->db->where($where);
      }
      if($user['com_admin']=="CUSTOMER"){
        $this->db->where('com_id', $user['com_id'] );
      }else{
        if($user['Is_admin']=="0"){
          $this->db->where('com_id', $user['com_id'] );
        }
      }
      $query = $this->db->get();
      $ar_return = $query->result_array();
    }else{
        foreach( $wcode_set as $set ){
            $this->db->from('lms_wkg');
            $this->db->where('id', $set );
            $this->db->where('wstatus', '1' );
            if($user['com_admin']=="CUSTOMER"){
              $this->db->where('com_id', $user['com_id'] );
            }
            $this->db->order_by('c_date', 'DESC');
            $query = $this->db->get();
            foreach( $query->result_array() as $row ){
              if(!in_array( $row , $ar_return )){
                array_push( $ar_return ,  $row );
              }
            }
        }
    }
    return $ar_return;
  }
  public function getWorkgroup( $id ){
    $this->db->from('lms_wkg');
    $this->db->where('id', $id );
    $this->db->order_by('c_date', 'DESC');
    $query = $this->db->get();
    return $query->result_array();
  }
  public function selectSurvey( $cos_id ){
    $this->db->from('lms_survey');
    $this->db->where('cos_id', $cos_id );
    $this->db->where('sv_status','1');
    $query = $this->db->get();
    return $query->result_array();
  }
  public function getCourseGroup( $course_id ){
    $this->db->from('lms_cosincg');
    $this->db->where('course_id', $course_id );
    $this->db->where('status_cg','1');
    $query = $this->db->get();
    return $query->result_array();
  }

        public function getCostype($com_id="") {
          $ar_return = array();
            $this->db->select('tc_id,tc_name_th,tc_name_en');
            $this->db->distinct();
            $this->db->where('tc_status','1');
            $this->db->where('com_id',$com_id);
            $query = $this->db->get('lms_typecos');
            $ar_return = $query->result_array();
          return $ar_return;
        }
  public function rechkUseWorkgroup( $id ){
    $this->db->from('lms_cog');
    $this->db->where('wg_id', $id );
    $this->db->where('cg_status', '1' );
    $query = $this->db->get();
    return $query->result_array();
  }

  public function rechkComIdWorkgroup(){
    $this->db->distinct();
    $this->db->select('lms_wkg.com_id');
    $this->db->from('lms_wkg');
    $this->db->where('wstatus', '1');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectComId($com_id){
    $this->db->distinct();
    $this->db->select('lms_wkg.wtitle_th,lms_wkg.wtitle_en,lms_wkg.id');
    $this->db->from('lms_wkg');
    $this->db->where('wstatus', '1');
    $this->db->where('com_id', $com_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectWorkgoup($wg_id){
    $this->db->distinct();
    $this->db->select('lms_cog.cgtitle_th,lms_cog.cgtitle_en,lms_cog.id');
    $this->db->from('lms_cog');
    $this->db->where('cg_status', '1');
    $this->db->where('wg_id', $wg_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectCondition($com_id){
    $this->db->distinct();
    $this->db->select('lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cos_id');
    $this->db->from('lms_cos');
    $this->db->where('status', '1');
    $this->db->where('com_id', $com_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectquize($com_id,$quiz_lang){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_qiz_exp.qize_name,lms_qiz_exp.qize_id');
    $this->db->from('lms_qiz_exp');
    $this->db->where('lms_qiz_exp.com_id', $com_id);
    $this->db->where('lms_qiz_exp.qize_lang', $quiz_lang);
    $this->db->where('lms_qiz_exp.qize_status', '1');
    $this->db->where('lms_qiz_exp.qize_isDelete', '0');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectcos($com_id){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_cos.cos_id,lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cname_jp');
    $this->db->from('lms_cos');
    $this->db->where('lms_cos.com_id', $com_id);
    $this->db->where('cos_isDelete','0');
    $this->db->where('cos_public','1');
    $this->db->where('cos_approve','1');
    if($user['ug_viewdata']=="2"){
    $this->db->where('lms_cos.com_id', $user['com_id']);
    }else if($user['ug_viewdata']=="3"){
      $this->db->where('lms_cos.cos_createby', $user['u_id']);
    }else{
    $this->db->where('lms_cos.com_id', $com_id);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectcos_report($com_id){
    $user = $this->session->userdata('user');
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->db->distinct();
    $this->db->select('lms_cos.cos_id,lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cname_jp');
    $this->db->from('lms_cos');
   /* $this->db->where('lms_cos.com_id', $com_id);*/
    $this->db->where('cos_isDelete','0');
    $this->db->where('cos_public','1');
    $this->db->where('cos_approve','1');
    /*if($user['ug_viewdata']=="2"){
    $this->db->where('lms_cos.com_id', $user['com_id']);
    }else if($user['ug_viewdata']=="3"){
      $this->db->where('lms_cos.cos_createby', $user['u_id']);
    }else{
    $this->db->where('lms_cos.com_id', $com_id);
    }*/
    $query = $this->db->get();
    $fetch = $query->result_array();
    if(countArray($fetch)>0){
        foreach ($fetch as $key => $value) {
            $where = 'lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where (lms_emp.emp_manage_a="'.$user['emp_c'].'" or lms_emp.emp_manage_b="'.$user['emp_c'].'") and lms_emp.emp_isDelete="0") and lms_cos_enroll.cosen_isDelete="0" and lms_cos_enroll.cos_id="'.$value['cos_id'].'"';
            $fetch_chk = $this->func_query->numrows('lms_cos_enroll','','','',$where);
            if($fetch_chk==0){
                unset($fetch[$key]);
            }
        }
    }
    return $fetch;
  }

  public function selectDropdownCourses(){
    $user = $this->session->userdata('user');
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->db->distinct();
    $this->db->select('lms_cos.cos_id,lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cname_jp');
    $this->db->from('lms_cos');
   /* $this->db->where('lms_cos.com_id', $com_id);*/
    $this->db->where('cos_isDelete','0');
    $this->db->where('cos_public','1');
    $this->db->where('cos_approve','1');
    /*if($user['ug_viewdata']=="2"){
    $this->db->where('lms_cos.com_id', $user['com_id']);
    }else if($user['ug_viewdata']=="3"){
      $this->db->where('lms_cos.cos_createby', $user['u_id']);
    }else{
    $this->db->where('lms_cos.com_id', $com_id);
    }*/
    if ($user['com_admin'] == "com_associated") {
      $this->db->where('lms_cos.com_id', $user['com_id']);
    } else {
      if ($user['ug_viewdata'] != "1") {
        $this->db->where('lms_cos.com_id', $user['com_id']);
      }
    }
		if ($user['ug_viewdata'] == "3") {
      $this->db->where('lms_cos.cos_createby', $user['u_id']);
		}
    $query = $this->db->get();
    $fetch = $query->result_array();
    if(countArray($fetch)>0){
        foreach ($fetch as $key => $value) {
            $where = 'lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where lms_emp.emp_isDelete="0") and lms_cos_enroll.cosen_isDelete="0" and lms_cos_enroll.cos_id="'.$value['cos_id'].'"';
            $fetch_chk = $this->func_query->numrows('lms_cos_enroll','','','',$where);
            if($fetch_chk==0){
                unset($fetch[$key]);
            }
        }
    }
    return $fetch;
  }

  public function selectQuestionnaire(){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_questionnaire.qn_title_th,lms_questionnaire.qn_title_en,lms_questionnaire.qn_id');
    $this->db->from('lms_questionnaire');
    $this->db->where('lms_questionnaire.qn_status', '1');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectcos_id($com_id){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_cos.cos_id,lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cname_jp');
    $this->db->from('lms_cos');
    $this->db->where('lms_cos.status', '1');
    $this->db->where('lms_cos.com_id', $com_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectfil($les_id){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->from('lms_fil');
    $this->db->where('lms_fil.lessons_id', $les_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function selectQuiz($cos_id){
    $user = $this->session->userdata('user');
    $this->db->distinct();
    $this->db->select('lms_qiz.quiz_name_th,lms_qiz.quiz_name_en,lms_qiz.qiz_id');
    $this->db->from('lms_qiz');
    $this->db->where('lms_qiz.quiz_status', '1');
    $this->db->where('lms_qiz.cos_id', $cos_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function create_workgroup($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_wkg');
          $this->db->where('com_id', $data['com_id']);
          $this->db->where('wcode', $data['wcode']);
          $this->db->where('wtitle_th', $data['wtitle_th']);
          $this->db->where('wtitle_en', $data['wtitle_en']);
          $this->db->where('wstatus', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data['c_date'] = date("Y-m-d H:i");
            $data['c_by'] = $user['emp_c'];
            $data['u_date'] = date("Y-m-d H:i");
            $data['u_by'] = $user['emp_c'];
            $this->db->insert('lms_wkg', $data);
            $id = $this->db->insert_id();
            if($id!=""){
              return "2";
            }else{
              return "3";
            }
          }else{
            return "1";
          }
  }

  public function update_workgroup($data,$id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('id', $id);
          $this->db->update('lms_wkg', $data);
          return "2";
  }
}
