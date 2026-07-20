<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
  public function view()
  {
    $arr['page'] = 'log/view';
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
      $arr['emp_c'] = $user['emp_c'];
      $arr['com_admin'] = $user['com_admin'];
      $arr['com_id'] = $user['com_id'];
      $arr['user_data'] = $user;
      $arr['user'] = $user;
		//in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;

    $this->load->model('Date_model', 'date', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->load->model('Manage_model', 'manage', FALSE);
    $this->manage->loadDB();
    date_default_timezone_set("Asia/Bangkok");
      $arr['arr_permission'] = $this->manage->chk_permission_page();
      $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
      $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
      if($arr['btn_view']!="1"){
        redirect(base_url().'dashboard') ;
      }
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
    // $sqlDate = array();
    // $inputBt = $this->input->post('bt');
    // if (!empty($inputBt)){
    //   if ($inputBt == 'reset'){
    //     redirect(base_url().$arr['page']);
    //   }
    //   $sday = $this->input->post('sDate');
    //   $eday = $this->input->post('eDate');
    //   $com_id = $this->input->post('com_id');
    //   $sqlDate['s'] = $sday;
    //   $sqlDate['e'] = $eday;
    //   $sqlDate['com_id'] = $com_id;
    // }else{
    //   $sqlDate['pos_for'] = "";
    //   $sday = date('Y-m-d H:i');
    //   $eday = date('Y-m-d H:i');
    // }
    // $arr['sday'] = $sday;
    // $arr['eday'] = $eday;
    //Record Log activity
    $this->lg->loadDB();
    // $arr['logs'] = $this->lg->getRecords($sqlDate);
    // $arr['emps'] = $this->lg->getAllEmp($sqlDate);
    $arr['company_select'] = $this->manage->getCompany();
    $this->lg->closeDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $this->foot->closeDB();

    $this->load->view('frontend/log', $arr);
  }

  public function logEmail()
  {
    $arr['page'] = 'log/logEmail';
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
      $arr['emp_c'] = $user['emp_c'];
      $arr['com_admin'] = $user['com_admin'];
      $arr['com_id'] = $user['com_id'];
      $arr['user_data'] = $user;
      $arr['user'] = $user;
		//in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;

    $this->load->model('Date_model', 'date', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->load->model('Manage_model', 'manage', FALSE);
    $this->manage->loadDB();
    date_default_timezone_set("Asia/Bangkok");
    $arr['arr_permission'] = $this->manage->chk_permission_page();
    $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
    $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
    if($arr['btn_view']!="1"){
      redirect(base_url().'dashboard') ;
    }
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
    $this->lg->closeDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $this->foot->closeDB();

    $this->load->view('frontend/logEmail', $arr);
  }

  public function logImportUsers()
  {
    $arr['page'] = 'log/logImportUsers';
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
      $arr['emp_c'] = $user['emp_c'];
      $arr['com_admin'] = $user['com_admin'];
      $arr['com_id'] = $user['com_id'];
      $arr['user_data'] = $user;
      $arr['user'] = $user;
		//in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;

    $this->load->model('Date_model', 'date', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->load->model('Manage_model', 'manage', FALSE);
    $this->manage->loadDB();
    date_default_timezone_set("Asia/Bangkok");
    $arr['arr_permission'] = $this->manage->chk_permission_page();
    $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
    $arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
    if($arr['btn_view']!="1"){
      redirect(base_url().'dashboard') ;
    }
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
    $arr['company_select'] = $this->manage->getCompanyAll();
    $this->lg->closeDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $this->foot->closeDB();

    $this->load->view('frontend/logImportUsers', $arr);
  }

}
