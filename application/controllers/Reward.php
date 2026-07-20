<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward extends CI_Controller {

	public function reward_type() {
		$arr['page'] = "reward/reward_type";
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', TRUE);
		$this->load->model('Course_model', 'course', TRUE);
		$this->load->model('Enroll_model', 'enroll', TRUE);
		$this->load->model('Log_model', 'lg', FALSE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
    	$this->load->model('Coursegroup_model', 'coursegroup', TRUE);

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
    	$this->coursegroup->loadDB();

		$arr['isNonEnroll'] = "";
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
        $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['cgcode'] = '';
			$arr['wcode'] = '';
			$arr['user'] = $user;
			$this->load->helper(array('form', 'url'));


			//Record Log activity
			$this->lg->record('reward', 'enter Reward Type');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/reward_type', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function manage_reward() {
		$arr['page'] = "reward/manage_reward";
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', TRUE);
		$this->load->model('Course_model', 'course', TRUE);
		$this->load->model('Enroll_model', 'enroll', TRUE);
		$this->load->model('Log_model', 'lg', FALSE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
    	$this->load->model('Coursegroup_model', 'coursegroup', TRUE);

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
    	$this->coursegroup->loadDB();

		$arr['isNonEnroll'] = "";
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
        $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['cgcode'] = '';
			$arr['wcode'] = '';
			$arr['user'] = $user;
			$this->load->helper(array('form', 'url'));


			//Record Log activity
			$this->lg->record('reward', 'enter Manage Reward');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/manage_reward', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

}