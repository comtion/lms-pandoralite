<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewdoc extends CI_Controller {

  public function fileview($id,$type,$cos_id){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang,$lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->fetch->loadDB();
    if(($id==""||$type=="")&&$type!="qrcode"){
        redirect(base_url().'dashboard') ;
    }
    if(in_array($type, array('course_filedemo','lesson_filedemo'))){
      $arr['page'] = 'managecourse/courses_demo/'.$cos_id;//'dashboard';//
    }else{
      $arr['page'] = 'coursemain/detail/'.$cos_id;
    }
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $sess = $this->session->userdata("user");
      if(empty($sess)&&$type!="qrcode"){
        redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
      }
    $arr['emp_c'] = $sess['emp_c'];
    $arr['com_admin'] = $sess['com_admin'];
    $arr['com_id'] = $sess['com_id'];
    $arr['lang'] = $lang;
    $arr['user'] = $sess;
    $this->load->model('Home_model', 'home', FALSE);
    $this->home->loadDB();
    $this->load->model('Setting_model', 'setting', FALSE);
    $this->setting->loadDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();

    $this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
        date_default_timezone_set("Asia/Bangkok");
        $date_now = date('Y-m-d H:i');
          $arr['arr_permission'] = $this->manage->chk_permission_page();
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['submenu'] = array();
      $arr['submenu_b'] = array();
      foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
        $li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
        if(countArray($li_arr_sub)){
          $arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
          foreach ($li_arr_sub as $key_sub => $value_sub) {
            $li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
            if(countArray($li_arr_sub_b)>0){
              $arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
            }
          }
        }
      }
      $path = "";
      $filname = "";
      if(in_array($type, array('course_filedemo','course_file'))){
          $fetch_cosfile = $this->func_query->query_row('lms_cos_fil','','','','fil_cos_id="'.$id.'"');
          if(countArray($fetch_cosfile)>0){
            $path = $fetch_cosfile['path_file'];
            if($lang=="thai"){ 
              $filname = $fetch_cosfile['name_file_th']!=""?$fetch_cosfile['name_file_th']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else if($lang=="english"){ 
              $filname = $fetch_cosfile['name_file_eng']!=""?$fetch_cosfile['name_file_eng']:$fetch_cosfile['name_file_th'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else{
              $filname = $fetch_cosfile['name_file_jp']!=""?$fetch_cosfile['name_file_jp']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_th'];
            }
          }
      }else{
          $fetch_cosfile = $this->func_query->query_row('lms_fil','','','','id="'.$id.'"');
          if(countArray($fetch_cosfile)>0){
            $path = $fetch_cosfile['path_file'];
            if($lang=="thai"){ 
              $filname = $fetch_cosfile['name_file_th']!=""?$fetch_cosfile['name_file_th']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else if($lang=="english"){ 
              $filname = $fetch_cosfile['name_file_eng']!=""?$fetch_cosfile['name_file_eng']:$fetch_cosfile['name_file_th'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else{
              $filname = $fetch_cosfile['name_file_jp']!=""?$fetch_cosfile['name_file_jp']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_th'];
            }
          }
      }
      if($path==""){
        redirect(base_url().'dashboard') ;
      }
      $arr['id'] = $id;
      $arr['path'] = $path;
      $arr['filname'] = $filname;
      $arr['type'] = $type;


    $this->home->closeDB();
    $this->foot->closeDB();
    $this->load->view('frontend/document_viewer', $arr );
  }
  
  public function PDF($id,$type)
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
    $arr['page'] = "viewdoc/PDF/".$id."/".$type;
    $this->load->model('User_model', 'login', TRUE);
    $this->login->loadDB();
    $this->load->model('Lesson_model', 'lesson', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Setting_model', 'setting', FALSE);
    $this->setting->loadDB();
    $this->lesson->loadDB();
    $this->db->where('id',$id);
    $this->db->from('lms_fil');
    $query = $this->db->get();
    $fetch = $query->row_array();
    $arr['file_name'] = $fetch['path_file'] ?? '';

      $path = "";
      $filname = "";
      if(in_array($type, array('course_filedemo','course_file'))){
          $fetch_cosfile = $this->func_query->query_row('lms_cos_fil','','','','fil_cos_id="'.$id.'"');
          if(countArray($fetch_cosfile)>0){
            $path = base_url().'/uploads/document/'.$fetch_cosfile['path_file'];
            if($lang=="thai"){ 
              $filname = $fetch_cosfile['name_file_th']!=""?$fetch_cosfile['name_file_th']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else if($lang=="english"){ 
              $filname = $fetch_cosfile['name_file_eng']!=""?$fetch_cosfile['name_file_eng']:$fetch_cosfile['name_file_th'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else{
              $filname = $fetch_cosfile['name_file_jp']!=""?$fetch_cosfile['name_file_jp']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_th'];
            }
          }
      }else if(in_array($type, array('qrcode'))){
          $data_query = $this->func_query->query_row('lms_qrcode','','','','qr_id = "'.$id.'"');
          if(countArray($data_query)>0){
              $filname = $data_query['qr_name'];
              $path = base_url().'uploads/file_forqrcode/'.$data_query['qr_path'];
          }
      }else if(in_array($type, array('usermanual'))){
            $sess = $this->session->userdata("user");
            $data_fetch = $this->setting->fetch_data_ECT();

            if(in_array($sess['ug_id'],array('1'))){
              if($data_fetch['da_manual_sa_th']!=""||$data_fetch['da_manual_sa_eng']!=""){
                $file_name = $lang=="thai"?$data_fetch['da_manual_sa_th']:$data_fetch['da_manual_sa_eng'];
              }
            }else if(in_array($sess['ug_id'],array('2','6'))){
              if($data_fetch['da_manual_gr_th']!=""||$data_fetch['da_manual_gr_eng']!=""){
                $file_name = $lang=="thai"?$data_fetch['da_manual_gr_th']:$data_fetch['da_manual_gr_eng'];
              }
            }else if(in_array($sess['ug_id'],array('7'))){
              if($data_fetch['da_manual_is_th']!=""||$data_fetch['da_manual_is_eng']!=""||$data_fetch['da_manual_is_center_th']!=""||$data_fetch['da_manual_is_center_eng']!=""){
                $file_name = $lang=="thai"?$data_fetch['da_manual_is_th']:$data_fetch['da_manual_is_eng'];
                if(!in_array($sess['com_code'],array('IMAT'))){
                $file_name = $lang=="thai"?$data_fetch['da_manual_is_center_th']:$data_fetch['da_manual_is_center_eng'];
                }
              }
            }else if(in_array($sess['ug_id'],array('9'))){
              if($data_fetch['da_manual_is_affiliate_th']!=""||$data_fetch['da_manual_is_affiliate_eng']!=""){
                $file_name = $lang=="thai"?$data_fetch['da_manual_is_affiliate_th']:$data_fetch['da_manual_is_affiliate_eng'];
              }


              if(in_array($sess['com_admin'],array('com_central'))){
                $file_name = $lang=="thai"?$data_fetch['da_manual_is_center_th']:$data_fetch['da_manual_is_center_eng'];
              }
            }else if(in_array($sess['ug_id'],array('4','5','8','14'))){
              if($data_fetch['da_manual_ln_th']!=""||$data_fetch['da_manual_ln_eng']!=""){
                $file_name = $lang=="thai"?$data_fetch['da_manual_ln_th']:$data_fetch['da_manual_ln_eng'];
              }
            }
              $filname = $file_name;
              $path = $file_name;
      }else{
          $fetch_cosfile = $this->func_query->query_row('lms_fil','','','','id="'.$id.'"');
          if(countArray($fetch_cosfile)>0){
            $path = base_url().'/uploads/document/'.$fetch_cosfile['path_file'];
            if($lang=="thai"){ 
              $filname = $fetch_cosfile['name_file_th']!=""?$fetch_cosfile['name_file_th']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else if($lang=="english"){ 
              $filname = $fetch_cosfile['name_file_eng']!=""?$fetch_cosfile['name_file_eng']:$fetch_cosfile['name_file_th'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_jp'];
            }else{
              $filname = $fetch_cosfile['name_file_jp']!=""?$fetch_cosfile['name_file_jp']:$fetch_cosfile['name_file_eng'];
              $filname = $filname!=""?$filname:$fetch_cosfile['name_file_th'];
            }
          }
      }
    if($type!="qrcode"){
      if($this->login->checkSession($arr['page'])){
          if($path==""){
            redirect(base_url().'dashboard') ;
          }
          $arr['file_name'] = $path;
          $arr['filname'] = $filname;
          $this->load->view('frontend/viewfilepdf',$arr);
      }
    }else{
      /*if($path==""){
        redirect(base_url().'dashboard') ;
      }*/
      $arr['file_name'] = $path;
      $arr['filname'] = $filname;
      $this->load->view('frontend/viewfilepdf',$arr);
    }
  }

}
?>