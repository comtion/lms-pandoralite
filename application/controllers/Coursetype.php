<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class coursetype extends CI_Controller {

  public function index(){}

  public function loadCourseType(){
    //Check user session : Redirect to login page with destination url when it false;
    $arr = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
    	$arr['page'] = "coursetype/loadCourseType";

		$this->load->model('User_model', 'login', FALSE);
		if($this->login->checkSession($arr['page'])){
			$sess = $this->session->userdata("user");
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $sess['com_name_th'];
			}else{
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$arr['user'] = $sess;
			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->model('Coursetype_model', 'coursetype', FALSE);
			$this->load->model('Manage_model', 'manage', FALSE);
			$this->manage->loadDB();
			$this->coursetype->loadDB();

        	$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
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
			$arr['data_fetch'] = $this->coursetype->fetch_data_coursetype();
			$arr['company_select'] = $this->manage->getCompany();
			$this->manage->closeDB();
			$this->coursetype->closeDB();
			$this->load->view('frontend/coursetype', $arr );
		}else{
			redirect(base_url().'dashboard/login');
		}
  }


	public function insert_coursetype(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Coursetype_model', 'coursetype', FALSE);
		$this->coursetype->loadDB();
		if(countArray($_REQUEST)>0){
        	$tc_courselearner = isset($_REQUEST['tc_courselearner'])?$_REQUEST['tc_courselearner']:"0";
			$data = array(
				'com_id' => $_REQUEST['com_id'],
				'tc_name_th' => $_REQUEST['tc_name_th'],
				'tc_name_en' => $_REQUEST['tc_name_en'],
				'tc_color' => $_REQUEST['tc_color'],
            	'tc_courselearner' => $tc_courselearner,
				'tc_modifeddate' => date('Y-m-d H:i')
			);
			if($_REQUEST['operation']=="Add"){
				$msg = $this->coursetype->create_coursetype($data);
			}else{
				$msg = $this->coursetype->update_coursetype($data,$_REQUEST['tc_id']);
			}
		}
		echo $msg;
		$this->coursetype->closeDB();
	}


	public function delete_coursetype_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Coursetype_model', 'coursetype', FALSE);
		$this->coursetype->loadDB();
		if(countArray($_REQUEST)>0){
			$data = array(
				'tc_status' => '0'
			);
			$msg = $this->coursetype->update_coursetype($data,$_REQUEST['tc_id_delete']);
		}
		echo $msg;
		$this->coursetype->closeDB();
	}


	public function update_coursetype_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['tc_id_update'],'lms_typecos','tc_id');
			echo json_encode($result);
		}
	}

	public function chk_chkbox(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Coursetype_model', 'coursetype', FALSE);
		$this->coursetype->loadDB();
		if(countArray($_REQUEST)>0){
			$msg = $this->coursetype->chk_chkbox($_REQUEST);
		}
		echo $msg;
	}
	
}
