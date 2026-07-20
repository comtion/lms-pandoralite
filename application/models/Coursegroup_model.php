<?php
class Coursegroup_model extends CI_Model {

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

  public function rechk_permission_cg(){
    $user = $this->session->userdata('user');
    date_default_timezone_set("Asia/Bangkok");
    $this->db->distinct();
    $this->db->select('lms_cos_detail.cos_id');
    $this->db->from('lms_cos_detail');
    $this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
    $this->db->where('lms_cos_detail_ug.ug_id',$user['ug_id']);
    $this->db->where('lms_cos_detail.status', '1' );
      $where = $this->course_availability_where('lms_cos_detail');
      $this->db->where($where);
    $query = $this->db->get();
    $fetch = $query->result_array();
    $cg_id = array();
    if(countArray($fetch)>0){
      foreach ($fetch as $key => $value) {
          $this->db->select('cg_id');
          $this->db->from('lms_cosincg');
          $this->db->where('course_id',$value['cos_id']);
          $this->db->where('status_cg','1');
          $query_cos = $this->db->get();
          $row_cos = $query_cos->row_array();
          if(countArray($row_cos)>0){
            if(!in_array($row_cos['cg_id'], $cg_id)){
              array_push($cg_id, $row_cos['cg_id']);
            }
          }
      }
    }
    return $cg_id;
  }
  public function rechk_permission_course_people(){
    $user = $this->session->userdata('user');
    date_default_timezone_set("Asia/Bangkok");
    $this->db->distinct();
    $this->db->select('lms_cos_detail.cos_id');
    $this->db->from('lms_cos_detail');
    $this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
    $this->db->where('lms_cos_detail_ug.ug_id',$user['ug_id']);
    $this->db->where('lms_cos_detail.status', '1' );
      $where = $this->course_availability_where('lms_cos_detail');
      $this->db->where($where);
    $query = $this->db->get();
    $fetch = $query->result_array();
    $cg_id = array();
    if(countArray($fetch)>0){
      foreach ($fetch as $key => $value) {
              array_push($cg_id, $value['cos_id']);
      }
    }
    return $cg_id;
  }
  public function rechk_permission_cos(){
    $user = $this->session->userdata('user');
    date_default_timezone_set("Asia/Bangkok");
    $this->db->distinct();
    $this->db->select('lms_cos_detail.cos_id');
    $this->db->from('lms_cos_detail');
    $this->db->where('lms_cos_detail.status', '1' );
      $where = $this->course_availability_where('lms_cos_detail');
      $this->db->where($where);
    $query = $this->db->get();
    $fetch = $query->result_array();
    $cg_id = array();
    if(countArray($fetch)>0){
      foreach ($fetch as $key => $value) {
              array_push($cg_id, $value['cos_id']);
      }
    }
    return $cg_id;
  }
  public function getAllCoursegroup( $wg_id = "" , $txt_search = ""){
    $user = $this->session->userdata('user');
    date_default_timezone_set("Asia/Bangkok");
    $ar_return = array();
    if($user['com_admin']=="CUSTOMER"||$user['Is_admin']=="0"){
      $this->db->from('lms_wkg');
      $this->db->order_by('c_date', 'ASC');
      $this->db->where('wstatus', '1' );
      $this->db->where('com_id', $user['com_id'] );
      $query_loop = $this->db->get();
      $fetch_loop = $query_loop->result_array();
      foreach ($fetch_loop as $key) {
          $this->db->from('lms_cog');
          $this->db->where('wg_id', $key['id'] );
          $this->db->where('cg_status', '1' );
          if($txt_search!=""){
            $where = "(cgtitle_th like '%".$txt_search."%' OR cgtitle_en like '%".$txt_search."%')";
            $this->db->where($where);
          }
          $this->db->order_by('c_date', 'ASC');
          $query = $this->db->get();
          $ar = $query->result_array();
          if(countArray($ar)>0){
            foreach ($ar as $key => $value) {
            array_push($ar_return, $ar[$key]);
            }
          }
      }
    }else{
      $this->db->from('lms_cog');
      $this->db->where('cg_status', '1' );
      if( $wg_id != "" ){
        $this->db->where('wg_id', $wg_id );
      }
      if($txt_search!=""){
        $where = "(cgtitle_th like '%".$txt_search."%' OR cgtitle_en like '%".$txt_search."%')";
        $this->db->where($where);
      }
      $this->db->order_by('c_date', 'ASC');
      $query = $this->db->get();
      $ar_return = $query->result_array();
    }
    
    return $ar_return;
  }

  public function create_coursegroup($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cog');
          $this->db->where('wg_id', $data['wg_id']);
          $this->db->where('cgcode', $data['cgcode']);
          $this->db->where('cgtitle_th', $data['cgtitle_th']);
          $this->db->where('cgtitle_en', $data['cgtitle_en']);
          $this->db->where('cg_status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data['c_date'] = date("Y-m-d H:i");
            $data['c_by'] = $user['emp_c'];
            $this->db->insert('lms_cog', $data);
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

  public function update_coursegroup($data,$id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('id', $id);
          
          	if ($this->db->update('lms_cog', $data)) {
			    return "2";
			}else{
				return "0";
			}
  }


  public function rechkUsecoursegroup( $id ){
    $this->db->from('lms_cosincg');
    $this->db->where('cg_id', $id );
    $this->db->where('status_cg', '1' );
    $query = $this->db->get();
    return $query->result_array();
  }
}
