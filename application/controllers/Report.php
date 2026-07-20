<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
      parent::__construct();
      error_reporting(0);
      if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
        $this->session->set_userdata('lang', $_GET["lang"]);
      }
    }
    
    public function loadreport_company(){
        $arr['page'] = "report/loadreport_company";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['title'] = $this->manage->get_namemenu($arr['page']);
      $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }
          $arr['company_select'] = $this->manage->getCompany();
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_general_company', $arr );
        }
    }

    public function loadreport_coursename(){
        $arr['page'] = "report/loadreport_coursename";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Function_query_model', 'func_query', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          
          $arr['main_menu'] = $this->manage->checkmenu();
          $arr['title'] = $this->manage->get_namemenu($arr['page']);
          $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }
          $arr['company_select'] = $this->manage->getCompany();
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_general_coursename', $arr );
        }
    }

    public function learnerReport() {
      $arr['page'] = "report/learnerReport";
      $this->load->model('User_model', 'login', true);
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
      $this->lang->load($lang,$lang);

      $arr['lang'] = $lang;
      $this->load->model('User_model', 'login', true);
      $this->load->model('Log_model', 'lg', false);
      $this->load->model('Footer_model', 'foot', false);
      $this->load->model('Manage_model', 'manage', false);

      $this->manage->loadDB();
      $this->login->loadDB();
      $this->lg->loadDB();
      $this->foot->loadDB();
      $arr['arr_permission'] = $this->manage->chk_permission_page();
      if($this->login->checkSession($arr['page'])){
        $user = $this->session->userdata('user');
        $arr['emp_c'] = $user['emp_c'];
        $arr['com_admin'] = $user['com_admin'];
        $arr['com_id'] = $user['com_id'];
        if($lang=="thai"){
          $arr['com_name'] = $user['com_name_th'];
        }else{
          $arr['com_name'] = $user['com_name_eng'];
        }
        $arr['user'] = $user;
        
        $arr['main_menu'] = $this->manage->checkmenu();
        $arr['title'] = $this->manage->get_namemenu($arr['page']);
        $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
        
        $arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
        $arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');
        if($arr['btn_view']!="1"){
          redirect(base_url().'dashboard') ;
        }
        $arr['company_select'] = $this->manage->getCompany();
        $arr['foote'] = $this->foot->getfooter();
        $this->load->view('frontend/learnerReport', $arr );
      }
    }

    public function loadreport_student(){
        $arr['page'] = "report/loadreport_student";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);
        $this->load->model('Function_query_model', 'func_query', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['title'] = $this->manage->get_namemenu($arr['page']);
      $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['countemployee'] = $this->func_query->numrows('lms_emp','','','','emp_manage_a="'.$user['emp_c'].'" or emp_manage_b="'.$user['emp_c'].'" and emp_isDelete="0"');
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_edit'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }
          $arr['company_select'] = $this->manage->getCompany();
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_general_student', $arr );
        }
    }

    public function loadreport_personal(){
        $arr['page'] = "report/loadreport_personal";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['title'] = $this->manage->get_namemenu($arr['page']);
      $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_general_personal', $arr );
        }
    }

    public function loadreport_survey(){
        $arr['page'] = "report/loadreport_survey";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['title'] = $this->manage->get_namemenu($arr['page']);
      $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['company_select'] = $this->manage->getCompany();
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_survey', $arr );
        }
    }

    public function loadreport_survey_detail($sv_id){
        $arr['page'] = "report/loadreport_survey";
        $this->load->model('User_model', 'login', true);
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
        $this->lang->load($lang,$lang);

        $arr['lang'] = $lang;
        $this->load->model('User_model', 'login', true);
        $this->load->model('Course_model', 'course', true);
        $this->load->model('Log_model', 'lg', false);
        $this->load->model('Footer_model', 'foot', false);
        $this->load->model('Manage_model', 'manage', false);
        $this->load->model('Function_query_model', 'func_query', false);

        $this->manage->loadDB();
        $this->login->loadDB();
        $this->course->loadDB();
        $this->lg->loadDB();
        $this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        if($this->login->checkSession($arr['page'])){
          $user = $this->session->userdata('user');
          $arr['emp_c'] = $user['emp_c'];
          $arr['com_admin'] = $user['com_admin'];
          $arr['com_id'] = $user['com_id'];
          if($lang=="thai"){
            $arr['com_name'] = $user['com_name_th'];
          }else{
            $arr['com_name'] = $user['com_name_eng'];
          }
          $arr['user'] = $user;
          $arr['sv_id'] = $sv_id;
          
      $arr['main_menu'] = $this->manage->checkmenu();
      $arr['title'] = $this->manage->get_namemenu($arr['page']);
      $arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
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
          $arr['company_select'] = $this->manage->getCompany();
          $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
          $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
          if($arr['btn_view']!="1"){
            redirect(base_url().'dashboard') ;
          }

          $result = $this->manage->query_data_onupdate($sv_id,'lms_survey','sv_id');
          $result['survey_detail'] = $this->func_query->query_result('lms_survey_de','','','','sv_id="'.$sv_id.'" and svde_isDelete="0"');
          $survey_user = $this->manage->query_multi_data_onupdate($sv_id,'lms_qn_user','sv_id');

          $arr['data1'] = $this->manage->getSVQ($sv_id,1);
          $arr['data2'] = $this->manage->getSVQ($sv_id,2);
          $arr['data3'] = $this->manage->getSVQ($sv_id,3);
          $arr['data4'] = $this->manage->getSVQ($sv_id,4);
          $arr['data5'] = $this->manage->getSVQ($sv_id,5);
                $arr['data1'] = $this->func_query->query_result('lms_survey_de','lms_qn_user_de','lms_survey_de.svde_id = lms_qn_user_de.svde_id','','lms_survey_de.sv_id="'.$sv_id.'" and lms_qn_user_de.qnude_var="1"');
                $arr['data2'] = $this->func_query->query_result('lms_survey_de','lms_qn_user_de','lms_survey_de.svde_id = lms_qn_user_de.svde_id','','lms_survey_de.sv_id="'.$sv_id.'" and lms_qn_user_de.qnude_var="2"');
                $arr['data3'] = $this->func_query->query_result('lms_survey_de','lms_qn_user_de','lms_survey_de.svde_id = lms_qn_user_de.svde_id','','lms_survey_de.sv_id="'.$sv_id.'" and lms_qn_user_de.qnude_var="3"');
                $arr['data4'] = $this->func_query->query_result('lms_survey_de','lms_qn_user_de','lms_survey_de.svde_id = lms_qn_user_de.svde_id','','lms_survey_de.sv_id="'.$sv_id.'" and lms_qn_user_de.qnude_var="4"');
                $arr['data5'] = $this->func_query->query_result('lms_survey_de','lms_qn_user_de','lms_survey_de.svde_id = lms_qn_user_de.svde_id','','lms_survey_de.sv_id="'.$sv_id.'" and lms_qn_user_de.qnude_var="5"');
          $result['survey_count'] = countArray($survey_user);
          $arr['result_data'] = $result;
          $arr['foote'] = $this->foot->getfooter();
          $this->load->view('frontend/report_survey_detail', $arr );
        }
    }
    public function fetch_detail( $survey_id ){
    $this->load->model('Report_model', 'report', true);
    $this->report->loadDB();
    $query = $this->report->fetch_Suggestion($survey_id);
    //print_r($query);
    $num = 1;
      $draw = intval($this->input->get("draw"));
      $start = intval($this->input->get("start"));
      $length = intval($this->input->get("length"));



      $data = [];
      $count = 0;

      foreach($query as $r) {
          $data[] = array(
              $num,
              $r->qnude_suggestion
          );
      $num++;
      $count++;
      }


      $result = array(
               "draw" => $draw,
                 "recordsTotal" => $count,
                 "recordsFiltered" => $count,
                 "data" => $data
            );


      echo json_encode($result);
      exit();
  }

  public function fetch_detail_head( $scode ){
    $this->load->model('Report_model', 'report', true);
    $this->report->loadDB();
    $query = $this->report->fetch_Suggestion_head($scode);
    //print_r($query);
    $num = 1;
      $draw = intval($this->input->get("draw"));
      $start = intval($this->input->get("start"));
      $length = intval($this->input->get("length"));



      $data = [];
      $count = 0;

      foreach($query as $r) {
          $data[] = array(
              $num,
              $r->qnu_suggestion
          );
      $num++;
      $count++;
      }


      $result = array(
               "draw" => $draw,
                 "recordsTotal" => $count,
                 "recordsFiltered" => $count,
                 "data" => $data
            );


      echo json_encode($result);
      exit();
  }

    public function fetch_course_survey(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_course_survey($user, $_REQUEST['com_id']) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }


    public function fetch_course_company(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_course_company($user, $_REQUEST['com_id']) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }

    public function fetch_coursename_company(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_coursename_company($user, $_REQUEST['com_id'], $_REQUEST['cg_id']) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }

    public function fetch_coursename_detail(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_coursename_detail($user,$_REQUEST['cos_id'],$_REQUEST['com_id']) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }

    public function update_pointof_manager(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();

      $phprating = $_REQUEST['phprating'];
      $emp_id = $_REQUEST['emp_id'];
      $cos_id = $_REQUEST['cos_id'];
      for ($i=0; $i < countArray($cos_id); $i++) { 
        $data = array(
          'cosen_pfm' => $phprating[$i]
        );
        $this->db->where('emp_id',$emp_id[$i]);
        $this->db->where('cos_id',$cos_id[$i]);
        $this->db->update('lms_cos_enroll',$data);
      }
      //print_r($cos_id);
    }

    public function fetchLearnerReport() {
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $com_id = isset($_GET['com_id']) ? $_GET['com_id'] : '';
      $cos_id = isset($_GET['cos_id']) ? $_GET['cos_id'] : '';
      $cosen_status_sub = isset($_GET['cosen_status_sub']) ? $_GET['cosen_status_sub'] : '';
      $time_start = isset($_GET['time_start']) ? $_GET['time_start'] : '';
      $time_end = isset($_GET['time_end']) ? $_GET['time_end'] : '';

      $date_start = isset($_GET['date_start'])&&$_GET['date_start']!=""?$_GET['date_start']." ".$time_start:"";
      $date_end = isset($_GET['date_end'])&&$_GET['date_end']!=""?$_GET['date_end']." ".$time_end:"";

      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetchLearnerReport($user,$com_id,$cos_id,$cosen_status_sub,$date_start,$date_end) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }


    public function fetch_course_student(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';
      $dep_id = isset($_REQUEST['dep_id']) ? $_REQUEST['dep_id'] : '';
      $time_start = isset($_REQUEST['time_start']) ? $_REQUEST['time_start'] : '';
      $time_end = isset($_REQUEST['time_end']) ? $_REQUEST['time_end'] : '';
      $date_start = isset($_REQUEST['date_start'])&&$_REQUEST['date_start']!=""?$_REQUEST['date_start']." ".$time_start:"";
      $date_end = isset($_REQUEST['date_end'])&&$_REQUEST['date_end']!=""?$_REQUEST['date_end']." ".$time_end:"";
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_course_student($user,$com_id,$dep_id,$_REQUEST['cos_id'],$_REQUEST['course_status'],$_REQUEST['cosen_status_sub'],$date_start,$date_end) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }

    public function fetch_course_personal(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->report->loadDB();
      $time_start = isset($_REQUEST['time_start']) ? $_REQUEST['time_start'] : '';
      $time_end = isset($_REQUEST['time_end']) ? $_REQUEST['time_end'] : '';
      $date_start = isset($_REQUEST['date_start'])&&$_REQUEST['date_start']!=""?$_REQUEST['date_start']." ".$time_start:"";
      $date_end = isset($_REQUEST['date_end'])&&$_REQUEST['date_end']!=""?$_REQUEST['date_end']." ".$time_end:"";

      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->report->fetch_course_personal($user, $_REQUEST['course_status'], $_REQUEST['cosen_status_sub'], $date_start, $date_end) : array();
      $draw = intval($this->input->get("draw"));
      $count = countArray($query);
      $result = array(
        "draw" 				      => $draw,
        "recordsTotal" 		  => $count,
        "recordsFiltered" 	=> $count,
        "data" 				      => $query,
        "error"           	=> $isError
      );
      echo json_encode($result);
      exit();
    }

    public function fetch_detail_answer(){
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang,$lang);
      $this->load->model('Report_model', 'report', true);
      $this->load->model('Function_query_model', 'func_query', true);
      $this->report->loadDB();
      $cosen_id = isset($_REQUEST['cosen_id']) ? $_REQUEST['cosen_id'] : '';
      $fetch_chk_enroll = $this->func_query->query_row('lms_cos_enroll','','','','cosen_id="'.$cosen_id.'"');
      if(countArray($fetch_chk_enroll)>0){
        ?>
        <style type="text/css">
          .tbquery th, .tbquery td { border: 1px solid #ddd!important } 
        </style>
        <?php
        $fetch_chk_pretest = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$fetch_chk_enroll['cos_id'].'" and quiz_type="1" and quiz_isDelete="0" and lms_qiz.qiz_id in (select lms_ques.qiz_id from lms_ques where lms_ques.ques_type in ("sa","sub"))','','','','lms_qiz.qiz_id');// and quiz_type="1"
        if(countArray($fetch_chk_pretest)>0){
          $numloop = 1;
          foreach ($fetch_chk_pretest as $key_pretest => $value_pretest) {

                  if($lang=="thai"){ 
                    $quiz_name = $value_pretest['quiz_name_th']!=""?$value_pretest['quiz_name_th']:$value_pretest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_jp'];
                  }else if($lang=="english"){ 
                    $quiz_name = $value_pretest['quiz_name_eng']!=""?$value_pretest['quiz_name_eng']:$value_pretest['quiz_name_th'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_jp'];
                  }else{
                    $quiz_name = $value_pretest['quiz_name_jp']!=""?$value_pretest['quiz_name_jp']:$value_pretest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_th'];
                  }
          ?>
          <h5><?php echo label('preExam').": ".$quiz_name; ?></h5>

          <div class="table-responsive">

                <table id="myTablePretest<?php echo $numloop;$numloop++; ?>" width="100%" style="" class="table table-bordered  table-striped tbquery">
                  <thead>
                    <tr>
                      <th width="10%"><center></center></th>
                      <th width="30%"><center><?php echo label('question'); ?></center></th>
                      <th width="30%"><center><?php echo label('answer'); ?></center></th>
                      <th width="30%"><center><?php echo label('sv_b_comment'); ?></center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $fetch_question = $this->func_query->query_result('lms_ques','lms_ques_tc','lms_ques_tc.ques_id = lms_ques.ques_id','','lms_ques.qiz_id="'.$value_pretest['qiz_id'].'" and lms_ques.ques_isDelete="0" and lms_ques_tc.cosen_id="'.$cosen_id.'" and lms_ques.ques_type in ("sa","sub")','lms_ques.ques_id ASC','','','');
                      if(countArray($fetch_question)>0){
                        $num = 1;
                        foreach ($fetch_question as $key_question => $value_question) {
                                  if($lang=="thai"){ 
                                    $ques_name = $value_question['ques_name_th']!=""?$value_question['ques_name_th']:$value_question['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_jp'];
                                  }else if($lang=="english"){ 
                                    $ques_name = $value_question['ques_name_eng']!=""?$value_question['ques_name_eng']:$value_question['ques_name_th'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_jp'];
                                  }else{
                                    $ques_name = $value_question['ques_name_jp']!=""?$value_question['ques_name_jp']:$value_question['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_th'];
                                  }
                                  if(in_array($value_question['ques_type'], array("sa","sub"))){
                                    $tc_answer = $value_question['tc_answer']!=""?$value_question['tc_answer']:"<center>-</center>";
                                  }else{
                                    $fetch_multi = $this->func_query->query_row('lms_ques_mul','','','','ques_id="'.$value_question['ques_id'].'"');
                                    if(countArray($fetch_multi)>0&&$value_question['tc_answer']!=""){
                                      if($fetch_chk_enroll['cosen_lang']=="thai"){
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_th'])&&$fetch_multi[$value_question['tc_answer'].'_th']!=""?$fetch_multi[$value_question['tc_answer'].'_th']:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_jp'];
                                      }else if($fetch_chk_enroll['cosen_lang']=="english"){
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_eng'])&&$fetch_multi[$value_question['tc_answer'].'_eng']!=""?$fetch_multi[$value_question['tc_answer'].'_eng']:$fetch_multi[$value_question['tc_answer'].'_th'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                      }else{
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_jp'])&&$fetch_multi[$value_question['tc_answer'].'_jp']!=""?$fetch_multi[$value_question['tc_answer'].'_jp']:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_th'];
                                      }
                                    }else{
                                        $tc_answer = "<center>-</center>";
                                    }
                                  }
                          ?>
                    <tr>
                      <th width="10%"><center><?php echo $num;$num++; ?></center></th>
                      <th width="30%"><?php echo $ques_name; ?></th>
                      <th width="30%"><?php echo isset($value_question['tc_answer'])?$tc_answer:"<center>-</center>"; ?></th>
                      <th width="30%"><center><?php echo isset($value_question['tc_note'])&&$value_question['tc_note']!=""?$value_question['tc_note']:"<center>-</center>"; ?></center></th>
                    </tr>
                          <?php
                        }
                      }
                    ?>
                  </tbody>
                </table>
          </div><hr>
          <script type="text/javascript">
            $('#myTablePretest<?php echo $numloop;$numloop++; ?>').DataTable();
          </script>
          <?php 
          }
        }
        $fetch_chk_posttest = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$fetch_chk_enroll['cos_id'].'" and quiz_type="2" and quiz_isDelete="0" and lms_qiz.qiz_id in (select lms_ques.qiz_id from lms_ques where lms_ques.ques_type in ("sa","sub"))','','','','lms_qiz.qiz_id');
        if(countArray($fetch_chk_posttest)>0){
          $numloop = 1;
          foreach ($fetch_chk_posttest as $key_posttest => $value_posttest) {

                  if($lang=="thai"){ 
                    $quiz_name = $value_posttest['quiz_name_th']!=""?$value_posttest['quiz_name_th']:$value_posttest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_jp'];
                  }else if($lang=="english"){ 
                    $quiz_name = $value_posttest['quiz_name_eng']!=""?$value_posttest['quiz_name_eng']:$value_posttest['quiz_name_th'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_jp'];
                  }else{
                    $quiz_name = $value_posttest['quiz_name_jp']!=""?$value_posttest['quiz_name_jp']:$value_posttest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_th'];
                  }
          ?>
          <h5><?php echo label('finalExam').": ".$quiz_name; ?></h5>

          <div class="table-responsive">

                <table id="myTablePosttest<?php echo $numloop;$numloop++; ?>" width="100%" style="" class="table table-bordered  table-striped tbquery">
                  <thead>
                    <tr>
                      <th width="10%"><center></center></th>
                      <th width="30%"><center><?php echo label('question'); ?></center></th>
                      <th width="30%"><center><?php echo label('answer'); ?></center></th>
                      <th width="30%"><center><?php echo label('sv_b_comment'); ?></center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $fetch_question = $this->func_query->query_result('lms_ques','lms_ques_tc','lms_ques_tc.ques_id = lms_ques.ques_id','','lms_ques.qiz_id="'.$value_posttest['qiz_id'].'" and lms_ques.ques_isDelete="0" and lms_ques_tc.cosen_id="'.$cosen_id.'" and lms_ques.ques_type in ("sa","sub")','lms_ques.ques_id ASC','','','lms_ques_tc.ques_id');
                      if(countArray($fetch_question)>0){
                        $num = 1;
                        foreach ($fetch_question as $key_question => $value_question) {
                                  if($lang=="thai"){ 
                                    $ques_name = $value_question['ques_name_th']!=""?$value_question['ques_name_th']:$value_question['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_jp'];
                                  }else if($lang=="english"){ 
                                    $ques_name = $value_question['ques_name_eng']!=""?$value_question['ques_name_eng']:$value_question['ques_name_th'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_jp'];
                                  }else{
                                    $ques_name = $value_question['ques_name_jp']!=""?$value_question['ques_name_jp']:$value_question['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_question['ques_name_th'];
                                  }
                                  if(in_array($value_question['ques_type'], array("sa","sub"))){
                                    $tc_answer = $value_question['tc_answer']!=""?$value_question['tc_answer']:"<center>-</center>";
                                  }else{
                                    $fetch_multi = $this->func_query->query_row('lms_ques_mul','','','','ques_id="'.$value_question['ques_id'].'"');
                                    if(countArray($fetch_multi)>0&&$value_question['tc_answer']!=""){
                                      if($fetch_chk_enroll['cosen_lang']=="thai"){
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_th'])&&$fetch_multi[$value_question['tc_answer'].'_th']!=""?$fetch_multi[$value_question['tc_answer'].'_th']:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_jp'];
                                      }else if($fetch_chk_enroll['cosen_lang']=="english"){
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_eng'])&&$fetch_multi[$value_question['tc_answer'].'_eng']!=""?$fetch_multi[$value_question['tc_answer'].'_eng']:$fetch_multi[$value_question['tc_answer'].'_th'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                      }else{
                                        $tc_answer = isset($fetch_multi[$value_question['tc_answer'].'_jp'])&&$fetch_multi[$value_question['tc_answer'].'_jp']!=""?$fetch_multi[$value_question['tc_answer'].'_jp']:$fetch_multi[$value_question['tc_answer'].'_eng'];
                                        $tc_answer = $tc_answer!=""?$tc_answer:$fetch_multi[$value_question['tc_answer'].'_th'];
                                      }
                                    }else{
                                        $tc_answer = "<center>-</center>";
                                    }
                                  }
                          ?>
                    <tr>
                      <th width="10%"><center><?php echo $num;$num++; ?></center></th>
                      <th width="30%"><?php echo $ques_name; ?></th>
                      <th width="30%"><?php echo isset($value_question['tc_answer'])?$tc_answer:"<center>-</center>"; ?></th>
                      <th width="30%"><center><?php echo isset($value_question['tc_note'])&&$value_question['tc_note']!=""?$value_question['tc_note']:"<center>-</center>"; ?></center></th>
                    </tr>
                          <?php
                        }
                      }
                    ?>
                  </tbody>
                </table>
          </div><hr>
          <script type="text/javascript">
            $('#myTablePosttest<?php echo $numloop;$numloop++; ?>').DataTable();
          </script>
          <?php 
          }
        }
      }
    }

    public function fetchLogImportUsersDetail(){
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->lang->load($lang,$lang);
          $this->load->model('Report_model', 'report', true);
          $this->report->loadDB();

          $lgiId = isset($_REQUEST['lgi_id']) ? $_REQUEST['lgi_id'] : '';
          $comId = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';

          $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
          $query = isset($user["emp_id"]) ? $this->report->fetchLogImportUsersDetail($user, $lgiId, $comId) : array();
          $draw = intval($this->input->get("draw"));
          $count = countArray($query);
          $result = array(
            "draw" 				      => $draw,
            "recordsTotal" 		  => $count,
            "recordsFiltered" 	=> $count,
            "data" 				      => $query,
            "error"           	=> $isError
          );
          echo json_encode($result);
          exit();
    }


}
