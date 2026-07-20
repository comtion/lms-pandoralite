<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		error_reporting(0);
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function ManageECT()
	{
		$arr['page'] = 'setting/ManageECT';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_ECT();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/manageect', $arr);
	}

	public function usermanual()
	{
		$arr['page'] = 'setting/usermanual';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$data_fetch = $this->setting->fetch_data_ECT();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$file_name = "";
		if (in_array($sess['ug_id'], array('1'))) {
			if ($data_fetch['da_manual_sa_th'] != "" || $data_fetch['da_manual_sa_eng'] != "") {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_sa_th'] : $data_fetch['da_manual_sa_eng'];
			}
		} else if (in_array($sess['ug_id'], array('2', '6'))) {
			if ($data_fetch['da_manual_gr_th'] != "" || $data_fetch['da_manual_gr_eng'] != "") {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_gr_th'] : $data_fetch['da_manual_gr_eng'];
			}
		} else if (in_array($sess['ug_id'], array('7'))) {
			if ($data_fetch['da_manual_is_th'] != "" || $data_fetch['da_manual_is_eng'] != "" || $data_fetch['da_manual_is_center_th'] != "" || $data_fetch['da_manual_is_center_eng'] != "") {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_is_th'] : $data_fetch['da_manual_is_eng'];
				if (!in_array($sess['com_code'], array('IMAT'))) {
					$file_name = $lang == "thai" ? $data_fetch['da_manual_is_center_th'] : $data_fetch['da_manual_is_center_eng'];
				}
			}
		} else if (in_array($sess['ug_id'], array('9'))) {
			if ($data_fetch['da_manual_is_affiliate_th'] != "" || $data_fetch['da_manual_is_affiliate_eng'] != "") {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_is_affiliate_th'] : $data_fetch['da_manual_is_affiliate_eng'];
			}


			if (in_array($sess['com_admin'], array('com_central'))) {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_is_center_th'] : $data_fetch['da_manual_is_center_eng'];
			}
		} else if (in_array($sess['ug_id'], array('4', '5', '8', '14'))) {
			if ($data_fetch['da_manual_ln_th'] != "" || $data_fetch['da_manual_ln_eng'] != "") {
				$file_name = $lang == "thai" ? $data_fetch['da_manual_ln_th'] : $data_fetch['da_manual_ln_eng'];
			}
		}
		if ($file_name == "") {
			redirect(base_url() . 'dashboard');
		}
		$arr['path'] = $file_name;
		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/usermanual', $arr);
	}

	public function ManageBanner()
	{
		$arr['page'] = 'setting/ManageBanner';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_ECT();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission('setting/ManageECT', 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission('setting/ManageECT', 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission('setting/ManageECT', 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission('setting/ManageECT', 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managebanner', $arr);
	}

	public function ManageBannerCourse()
	{
		$arr['page'] = 'setting/ManageBannerCourse';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_managebannercourse();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission('setting/ManageECT', 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission('setting/ManageECT', 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission('setting/ManageECT', 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission('setting/ManageECT', 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managebannercourse', $arr);
	}

	public function ManageSSO()
	{
		$arr['page'] = 'setting/ManageECT';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Function_query_model', 'func_query', false);
		$this->func_query->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->func_query->query_row("lms_setting_sso", "", "", "", "sso_id='1'");

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managesso', $arr);
	}

	public function ManageEvent()
	{
		$arr['page'] = 'setting/ManageEvent';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Function_query_model', 'func_query', false);
		$this->func_query->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/manageevent', $arr);
	}

	public function format_email()
	{
		$arr['page'] = 'setting/format_email';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->course->query_data_onupdate_result('', 'lms_sendmail_form', '');

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission('setting/ManageECT', 'ru_del');
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/format_email', $arr);
	}

	public function fetch_detail_format_email()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$user = $this->session->userdata("user");
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_format_email() : array();
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

	public function fetch_detail_event()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$user = $this->session->userdata("user");
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_event() : array();
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

	public function query_rowdata()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$fieldname = isset($_REQUEST['fieldname']) ? $_REQUEST['fieldname'] : "";
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
		$dataname = isset($_REQUEST['dataname']) ? $_REQUEST['dataname'] : "";
		$this->db->where($fieldname, $id);
		$this->db->from($dataname);
		$query = $this->db->get();
		$fetch = $query->row_array();
		if (countArray($fetch) > 0) {
			$fetch['isData'] = "1";
		} else {
			$fetch['isData'] = "0";
		}
		echo json_encode($fetch);
	}

	public function setting_email()
	{
		$arr['page'] = 'setting/setting_email';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();

		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->course->query_data_onupdate('1', 'lms_setting_mail', 'sm_id');

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/setting_email', $arr);
	}

	public function ManageFAQ()
	{
		$arr['page'] = 'setting/ManageFAQ';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_faq();

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managefaq', $arr);
	}

	public function fetch_sort()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', true);
		$this->setting->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_sort($_REQUEST['com_id']) : array();
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
	public function check_countsortcos()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', true);
		$this->manage->loadDB();

		$arr = array();
		$arr['count_coss'] = $this->manage->countrecordcos_sort($_REQUEST['com_id']);
		echo json_encode($arr);
		exit();
	}

	public function fetch_mainmenu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', true);
		$this->setting->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_mainmenu($_REQUEST['com_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_detail_menu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', true);
		$this->setting->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_menu() : array();
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

	public function fetch_detail_faqmain()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', true);
		$this->setting->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_faq() : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function ManageMainmenu()
	{
		$arr['page'] = 'setting/ManageMainmenu';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission('setting/ManageECT', 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission('setting/ManageECT', 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission('setting/ManageECT', 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission('setting/ManageECT', 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['company_select'] = $this->manage->getCompany();

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managemainmenu', $arr);
	}

	public function ManageMenu()
	{
		$arr['page'] = 'setting/ManageMenu';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_menu();

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managemenu', $arr);
	}

	public function ManageTestimonials()
	{
		$arr['page'] = 'setting/ManageTestimonials';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');

		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_testimonials();

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if (countArray($li_arr_sub)) {
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/managetestimonials', $arr);
	}

	public function insert_testimonials()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$_REQUEST['tim_file'] = $_REQUEST['tim_file_ori'];
		if (isset($_FILES['tim_file'])) {
			$imageSourcePath = $_FILES['tim_file']['tmp_name'];
			$imageTargetPath = ROOT_DIR . "uploads/brand/" . date('YmdHis') . ".jpg";
			if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
				$_REQUEST['tim_file'] = date('YmdHis') . ".jpg";
			}
		}
		$data = array(
			'tim_file' => $_REQUEST['tim_file'],
			'tim_title' => $_REQUEST['tim_title'],
			'tim_moddate' => date('Y-m-d H:i')
		);
		if ($_REQUEST['operation'] == "Add") {
			$date['tim_createdate'] = date('Y-m-d H:i');
			$msg = $this->setting->create_testimonials($data);
		} else {
			$msg = $this->setting->update_testimonials($data, $_REQUEST['tim_id']);
		}
		echo $msg;
	}


	public function insert_bannercourse()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Fetchdata_model', 'fetch', false);
		$this->load->model('Function_query_model', 'func_query', false);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->fetch->loadDB();
		$output = array();
		if (isset($_REQUEST) && !empty($sess['emp_c'])) {
			$bc_name_th = isset($_REQUEST['bc_name_th']) ? $_REQUEST['bc_name_th'] : "";
			$bc_name_eng = isset($_REQUEST['bc_name_eng']) ? $_REQUEST['bc_name_eng'] : "";
			$bc_name_jp = isset($_REQUEST['bc_name_jp']) ? $_REQUEST['bc_name_jp'] : "";
			$bc_type = isset($_REQUEST['bc_type']) ? $_REQUEST['bc_type'] : "";
			$bc_status = isset($_REQUEST['bc_status']) ? $_REQUEST['bc_status'] : "0";

			$arr_data = array(
				'bc_name_th' => $bc_name_th,
				'bc_name_eng' => $bc_name_eng,
				'bc_name_jp' => $bc_name_jp,
				'bc_type' => $bc_type,
				'bc_status' => $bc_status,
				'bc_modifiedby' => $sess['u_id'],
				'bc_modifieddate' => date('Y-m-d H:i')
			);

			if (isset($_FILES['bc_image']) && $_FILES['bc_image'] != "") {
				if (isset($_FILES['bc_image'])) {
					$imageSourcePath = $_FILES['bc_image']['tmp_name'];
					$pathBG = $_FILES['bc_image']['name'];
					if ($pathBG != "") {
						$array_pathext = explode('.', $pathBG);
						$extension = end($array_pathext);
						$bc_image = "bannercos_" . date('YmdHis') . "." . $extension;
						$imageTargetPath = ROOT_DIR . "uploads/banner_course/" . $bc_image;
						if ($_REQUEST['operation'] == "Edit") {
							$fetch_img = $this->func_query->query_row('lms_ban_cos', '', '', '', 'bc_id="' . $_REQUEST['bc_id'] . '"');
							if (countArray($fetch_img) > 0 && $fetch_img['bc_image'] != "") {
								if (is_file(ROOT_DIR . "uploads/banner_course/" . $fetch_img['bc_image'])) {
									audit_unlink(ROOT_DIR . "uploads/banner_course/" . $fetch_img['bc_image']);
								}
							}
						}
						if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
							$arr_data['bc_image'] = $bc_image;
						}
					}
				}
			}
			if ($_REQUEST['operation'] == "Add") {

				$arr_data['bc_createdate'] = date('Y-m-d H:i');
				$arr_data['bc_createby'] = $sess['u_id'];
				$fetch_chk = $this->func_query->numrows('lms_ban_cos', '', '', '', ' bc_name_th="' . $arr_data['bc_name_th'] . '" and bc_type="' . $arr_data['bc_type'] . '" and bc_isDelete="0"');
				if ($fetch_chk == 0) {
					$this->db->insert('lms_ban_cos', $arr_data);
					$id = $this->db->insert_id();
					if ($id != "") {
						$output['status'] = "2";
					} else {
						$output['status'] = "3";
					}
				} else {
					$output['status'] = "1";
				}
			} else {
				$this->db->where('bc_id', $_REQUEST['bc_id']);
				$this->db->update('lms_ban_cos', $arr_data);
				$output['status'] = "2";
			}
		} else {
			$output['status'] = "0";
		}
		echo json_encode($output);
	}

	public function insert_template()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->setting->loadDB();
		$smf_type = isset($_REQUEST['smf_type']) ? $_REQUEST['smf_type'] : "";
		$smf_subject_th = isset($_REQUEST['smf_subject_th']) ? $_REQUEST['smf_subject_th'] : "";
		$smf_subject_en = isset($_REQUEST['smf_subject_en']) ? $_REQUEST['smf_subject_en'] : "";
		$smf_message_th = isset($_REQUEST['smf_message_th']) ? $_REQUEST['smf_message_th'] : "";
		$smf_message_en = isset($_REQUEST['smf_message_en']) ? $_REQUEST['smf_message_en'] : "";
		$smf_show = isset($_REQUEST['smf_show']) ? $_REQUEST['smf_show'] : "0";
		$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : "";
		$smf_id = isset($_REQUEST['smf_id']) ? $_REQUEST['smf_id'] : "";

		$data = array(
			'smf_type' => $smf_type,
			'smf_subject_th' => $smf_subject_th,
			'smf_subject_en' => $smf_subject_en,
			'smf_message_th' => $smf_message_th,
			'smf_message_en' => $smf_message_en,
			'smf_show' => $smf_show,
			'smf_modifiedby' => $sess['u_id'],
			'smf_modifieddate' => date('Y-m-d H:i')
		);

		if (isset($_FILES['smf_importimage']) && $_FILES['smf_importimage'] != "") {
			if (isset($_FILES['smf_importimage'])) {
				$imageSourcePath = $_FILES['smf_importimage']['tmp_name'];
				$pathBG = $_FILES['smf_importimage']['name'];
				if ($pathBG != "") {
					$array_pathext = explode('.', $pathBG);
					$extension = end($array_pathext);
					$smf_importimage = "formatmail_" . date('YmdHis') . "." . $extension;
					$imageTargetPath = ROOT_DIR . "uploads/formatmail_img/" . $smf_importimage;
					if ($operation == "Edit") {
						$fetch_img = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_id="' . $smf_id . '"');
						if (countArray($fetch_img) > 0 && $fetch_img['smf_importimage'] != "") {
							if (is_file(ROOT_DIR . "uploads/formatmail_img/" . $fetch_img['smf_importimage'])) {
								audit_unlink(ROOT_DIR . "uploads/formatmail_img/" . $fetch_img['smf_importimage']);
							}
						}
					}
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$data['smf_importimage'] = $smf_importimage;
					}
				}
			}
		}

		/*if($smf_show=="1"){
			$data_status = array(
				'smf_show' => '0',
				'smf_modifiedby'=>$sess['u_id'],
				'smf_modifieddate'=>date('Y-m-d H:i')
			);
			$this->db->where('smf_type',$smf_type);
			$this->db->update('lms_sendmail_form',$data_status);
		}*/

		if ($operation == "Add") {
			$fetch_rechk = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_type="' . $smf_type . '"');
			if (countArray($fetch_rechk) > 0) {
				$this->db->where('smf_type', $smf_type);
				$this->db->update('lms_sendmail_form', $data);
			} else {
				$data['smf_createby'] = $emp_c;
				$data['smf_createdate'] = date('Y-m-d H:i');
				$this->db->insert('lms_sendmail_form', $data);
			}
			$msg = "2";
		} else {
			$this->db->where('smf_id', $smf_id);
			$this->db->update('lms_sendmail_form', $data);
			$msg = "2";
		}
		echo $msg;
	}
	public function insert_banner()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$data = array(
			'com_id' => $_REQUEST['com_id_banner']
		);

		if (isset($_FILES['banner'])) {
			$imageSourcePath = $_FILES['banner']['tmp_name'];
			$namefile = "banner_" . date('YmdHis') . ".jpg";
			$imageTargetPath = ROOT_DIR . "uploads/banner/" . $namefile;
			if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
				$data['banner'] = $namefile;
			}
		}
		$msg = $this->setting->insert_banner($data);
		echo $msg;
	}
	public function insert_banner_about()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$data = array(
			'time_created' => date('Y-m-d H:i'),
			'emp_c' => $sess['u_id']
		);
		if (isset($_FILES['banner'])) {
			$imageSourcePath = $_FILES['banner']['tmp_name'];
			$namefile = "banner_" . date('YmdHis') . ".jpg";
			$imageTargetPath = ROOT_DIR . "uploads/banner/" . $namefile;
			if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
				$data['banner'] = $namefile;
			}
		}
		$msg = $this->setting->insert_banner_about($data);
		echo $msg;
	}

	public function insert_settingemail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Log_model', 'lg', false);
			$this->lg->loadDB();
			$this->lg->record('Setting', 'Setting Send Mail By ' . $sess['fullname_th']);
			$msg = $this->setting->insert_settingemail($_REQUEST, '1');
		}
		echo $msg;
	}

	public function insert_about()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Log_model', 'lg', false);
			$this->lg->loadDB();
			$this->lg->record('Setting', 'Setting About By ' . $sess['fullname_th']);

			if (isset($_FILES['da_logo_top']) && $_FILES['da_logo_top'] != "") {
				if (isset($_FILES['da_logo_top'])) {
					$imageSourcePath = $_FILES['da_logo_top']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "images/logo.png";
					audit_move_uploaded_file($imageSourcePath, $imageTargetPath);
					$_REQUEST['da_logo_top'] = base_url() . "images/logo.png";
				}
			}
			if (isset($_FILES['da_logo_elearning']) && $_FILES['da_logo_elearning'] != "") {
				if (isset($_FILES['da_logo_elearning'])) {
					$imageSourcePath = $_FILES['da_logo_elearning']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "images/elearning_logo.png";
					audit_move_uploaded_file($imageSourcePath, $imageTargetPath);
					$_REQUEST['da_logo_elearning'] = base_url() . "images/elearning_logo.png";
				}
			}
			if (isset($_FILES['da_logo_footer']) && $_FILES['da_logo_footer'] != "") {
				if (isset($_FILES['da_logo_footer'])) {
					$imageSourcePath = $_FILES['da_logo_footer']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "images/logo_white.png";
					audit_move_uploaded_file($imageSourcePath, $imageTargetPath);
					$_REQUEST['da_logo_footer'] = base_url() . "images/logo_white.png";
				}
			}
			if (isset($_FILES['da_footer_background']) && $_FILES['da_footer_background'] != "") {
				if (isset($_FILES['da_footer_background'])) {
					$imageSourcePath = $_FILES['da_footer_background']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "images/bg.jpg";
					audit_move_uploaded_file($imageSourcePath, $imageTargetPath);
					$_REQUEST['da_footer_background'] = base_url() . "images/bg.jpg";
				}
			}
			if (isset($_FILES['da_manual_sa_th']) && $_FILES['da_manual_sa_th'] != "") {
				if (isset($_FILES['da_manual_sa_th'])) {
					$imageSourcePath = $_FILES['da_manual_sa_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_sa_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_sa_th'] = base_url() . "uploads/user_manual/manual_sa_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_sa_eng']) && $_FILES['da_manual_sa_eng'] != "") {
				if (isset($_FILES['da_manual_sa_eng'])) {
					$imageSourcePath = $_FILES['da_manual_sa_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_sa_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_sa_eng'] = base_url() . "uploads/user_manual/manual_sa_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_gr_th']) && $_FILES['da_manual_gr_th'] != "") {
				if (isset($_FILES['da_manual_gr_th'])) {
					$imageSourcePath = $_FILES['da_manual_gr_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_gr_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_gr_th'] = base_url() . "uploads/user_manual/manual_gr_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_gr_eng']) && $_FILES['da_manual_gr_eng'] != "") {
				if (isset($_FILES['da_manual_gr_eng'])) {
					$imageSourcePath = $_FILES['da_manual_gr_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_gr_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_gr_eng'] = base_url() . "uploads/user_manual/manual_gr_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_th']) && $_FILES['da_manual_is_th'] != "") {
				if (isset($_FILES['da_manual_is_th'])) {
					$imageSourcePath = $_FILES['da_manual_is_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_th'] = base_url() . "uploads/user_manual/manual_is_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_eng']) && $_FILES['da_manual_is_eng'] != "") {
				if (isset($_FILES['da_manual_is_eng'])) {
					$imageSourcePath = $_FILES['da_manual_is_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_eng'] = base_url() . "uploads/user_manual/manual_is_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_center_th']) && $_FILES['da_manual_is_center_th'] != "") {
				if (isset($_FILES['da_manual_is_center_th'])) {
					$imageSourcePath = $_FILES['da_manual_is_center_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_center_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_center_th'] = base_url() . "uploads/user_manual/manual_is_center_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_center_eng']) && $_FILES['da_manual_is_center_eng'] != "") {
				if (isset($_FILES['da_manual_is_center_eng'])) {
					$imageSourcePath = $_FILES['da_manual_is_center_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_center_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_center_eng'] = base_url() . "uploads/user_manual/manual_is_center_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_affiliate_th']) && $_FILES['da_manual_is_affiliate_th'] != "") {
				if (isset($_FILES['da_manual_is_affiliate_th'])) {
					$imageSourcePath = $_FILES['da_manual_is_affiliate_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_affiliate_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_affiliate_th'] = base_url() . "uploads/user_manual/manual_is_affiliate_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_is_affiliate_eng']) && $_FILES['da_manual_is_affiliate_eng'] != "") {
				if (isset($_FILES['da_manual_is_affiliate_eng'])) {
					$imageSourcePath = $_FILES['da_manual_is_affiliate_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_is_affiliate_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_is_affiliate_eng'] = base_url() . "uploads/user_manual/manual_is_affiliate_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_ln_th']) && $_FILES['da_manual_ln_th'] != "") {
				if (isset($_FILES['da_manual_ln_th'])) {
					$imageSourcePath = $_FILES['da_manual_ln_th']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_ln_th" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_ln_th'] = base_url() . "uploads/user_manual/manual_ln_th" . date('ymdHis') . ".pdf";
					}
				}
			}
			if (isset($_FILES['da_manual_ln_eng']) && $_FILES['da_manual_ln_eng'] != "") {
				if (isset($_FILES['da_manual_ln_eng'])) {
					$imageSourcePath = $_FILES['da_manual_ln_eng']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/user_manual/manual_ln_eng" . date('ymdHis') . ".pdf";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$_REQUEST['da_manual_ln_eng'] = base_url() . "uploads/user_manual/manual_ln_eng" . date('ymdHis') . ".pdf";
					}
				}
			}
			unset($_REQUEST['myTable_length']);
			$msg = $this->setting->insert_about($_REQUEST, '1');
		}
		echo $msg;
	}

	public function insert_sso()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Log_model', 'lg', false);
			$this->lg->loadDB();
			$this->lg->record('Setting', 'Setting Single Sign On By ' . $sess['fullname_th']);
			$msg = $this->setting->insert_sso($_REQUEST, '1');
		}
		echo $msg;
	}

	public function li_menu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$li_arr = $this->manage->checkmenu();
		$detailpos_arr = array();
		if (countArray($li_arr) > 0) {
			foreach ($li_arr as $key_li => $value_li) {
?>
<li class="dd-item" data-id="<?php echo $value_li['mu_id']; ?>" style="width:100%">
  <div class="dd-handle" style="font-size: 18px;font-family: 'Prompt', sans-serif;"><i
      class="<?php echo $value_li['mu_icon']; ?>"></i>
    <?php if ($lang == "thai") {
							echo $value_li['mu_name_th'];
						} else if ($lang == "english") {
							echo $value_li['mu_name_en'];
						} else {
							echo $value_li['mu_name_jp'];
						} ?>
  </div>
  <?php
					$li_arr_sub = $this->manage->checkmenu_sub($value_li['mu_id']);
					if (countArray($li_arr_sub) > 0) {
					?>
  <ol class="dd-list">
    <?php foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {  ?>
    <li class="dd-item" data-id="<?php echo $value_li_sub['mu_id']; ?>">
      <div class="dd-handle" style="font-size: 18px;font-family: 'Prompt', sans-serif;"><i
          class="<?php echo $value_li_sub['mu_icon']; ?>"></i>
        <?php if ($lang == "thai") {
											echo $value_li_sub['mu_name_th'];
										} else if ($lang == "english") {
											echo $value_li_sub['mu_name_en'];
										} else {
											echo $value_li_sub['mu_name_jp'];
										} ?>
      </div>
    </li>
    <?php
								$li_arr_sub_b = $this->manage->checkmenu_sub($value_li_sub['mu_id']);
								if (countArray($li_arr_sub_b) > 0) { ?>
    <ol class="dd-list">
      <?php foreach ($li_arr_sub_b as $key_li_sub_b => $value_li_sub_b) {  ?>
      <li class="dd-item" data-id="<?php echo $value_li_sub_b['mu_id']; ?>">
        <div class="dd-handle" style="font-size: 18px;font-family: 'Prompt', sans-serif;"><i
            class="<?php echo $value_li_sub_b['mu_icon']; ?>"></i>
          <?php if ($lang == "thai") {
														echo $value_li_sub_b['mu_name_th'];
													} else if ($lang == "english") {
														echo $value_li_sub_b['mu_name_en'];
													} else {
														echo $value_li_sub_b['mu_name_jp'];
													} ?>
        </div>
      </li>
      <?php } ?>
    </ol>
    <?php 	}
							}
							?>
  </ol>
  <?php 	} ?>
</li>
<?php }
		} else { ?>
<?php echo label('datanotfound'); ?>
<?php }
	}


	public function edit_li_menu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$cos_id = "";
			$num = 1;
			$arr_out = array();
			print_r($_REQUEST);
			foreach ($_REQUEST['arr_obj'] as $key => $value) {
				if (isset($value['children']) && countArray($value['children']) > 0) {
					$this->db->from('lms_menu');
					$this->db->where('lms_menu.mu_status', '1');
					$this->db->where('lms_menu.mu_id', $value['id']);
					$query_loop = $this->db->get();
					$fetch_loop = $query_loop->result_array();
					if (countArray($fetch_loop) > 0) {
						$data = array(
							'mu_num' => $num,
							'mu_parent' => ''
						);
						$this->db->where('mu_id', $value['id']);
						$this->db->update('lms_menu', $data);
						$num++;
					}
					foreach ($value['children'] as $key_child => $value_child) {
						if (isset($value_child['children']) && countArray($value_child['children']) > 0) {
							$this->db->from('lms_menu');
							$this->db->where('lms_menu.mu_status', '1');
							$this->db->where('lms_menu.mu_id', $value_child['id']);
							$query_loop = $this->db->get();
							$fetch_loop = $query_loop->result_array();
							if (countArray($fetch_loop) > 0) {
								$data = array(
									'mu_num' => $num,
									'mu_parent' => ''
								);
								$this->db->where('mu_id', $value_child['id']);
								$this->db->update('lms_menu', $data);
								$num++;
							}
							foreach ($value_child['children'] as $key_child => $value_child_sub) {
								$data = array(
									'mu_num' => $num,
									'mu_parent' => $value_child['id']
								);
								$this->db->where('mu_id', $value_child_sub['id']);
								$this->db->update('lms_menu', $data);
								$num++;
							}
						} else {
						}
						$data = array(
							'mu_num' => $num,
							'mu_parent' => $value['id']
						);
						$this->db->where('mu_id', $value_child['id']);
						$this->db->update('lms_menu', $data);
						$num++;
					}
				} else {
					$this->db->from('lms_menu');
					$this->db->where('lms_menu.mu_status', '1');
					$this->db->where('lms_menu.mu_id', $value['id']);
					$query_loop = $this->db->get();
					$fetch_loop = $query_loop->result_array();
					if (countArray($fetch_loop) > 0) {
						$data = array(
							'mu_num' => $num,
							'mu_parent' => ''
						);
						$this->db->where('mu_id', $value['id']);
						$this->db->update('lms_menu', $data);
						$num++;
					}
				}
			}
			$li_arr = $this->manage->checkmenu();
			foreach ($_REQUEST['arr_obj'] as $key => $value) {
				$this->db->from('lms_menu');
				$this->db->where('lms_menu.mu_status', '1');
				$this->db->where('lms_menu.mu_id', $value['id']);
				$query_loop = $this->db->get();
				$fetch_loop = $query_loop->result_array();
				if (countArray($fetch_loop) > 0) {
					foreach ($li_arr as $key_li => $value_li) {
						if ($value_li['mu_id'] == $value['id']) {
							unset($li_arr[$key_li]);
						}
					}
				}
			}
			if (countArray($li_arr) > 0) {
				foreach ($li_arr as $key_li => $value_li) {
					$data = array(
						'mu_num' => $num
					);
					$this->db->where('mu_id', $value_li['mu_id']);
					$this->db->update('lms_menu', $data);
					$num++;
				}
			}
			//$msg = $this->course->delete_data($_REQUEST['id_delete'],$_REQUEST['field'],$_REQUEST['table_name']);
		}
		//echo $msg;
	}

	public function insert_menu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->loadDB();
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$mu_customer = isset($_REQUEST['mu_customer']) ? $_REQUEST['mu_customer'] : '0';
			$data = array(
				'mu_name_th' => $_REQUEST['mu_name_th'],
				'mu_name_en' => $_REQUEST['mu_name_en'],
				'mu_name_jp' => $_REQUEST['mu_name_jp'],
				'mu_icon' => $_REQUEST['mu_icon'],
				'mu_path' => $_REQUEST['mu_path'],
				'mu_customer' => $mu_customer
			);
			if ($_REQUEST['operation'] == "Add") {
				$data['mu_num'] = $this->setting->rechk_nummenu();
				$this->lg->record('Setting', 'Create Menu ' . $_REQUEST['mu_name_th'] . ' By ' . $sess['fullname_th']);
				$msg = $this->setting->create_menu($data);
			} else {
				$this->lg->record('Setting', 'Update Menu ' . $_REQUEST['mu_name_th'] . ' By ' . $sess['fullname_th']);
				$msg = $this->setting->update_menu($data, $_REQUEST['mu_id']);
			}
		}
		echo $msg;
	}


	public function insert_event()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		date_default_timezone_set("Asia/Bangkok");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'con_title_th' => $_REQUEST['con_title_th'],
				'con_title_en' => $_REQUEST['con_title_en'],
				'con_detail_th' => $_REQUEST['con_detail_th'],
				'con_detail_en' => $_REQUEST['con_detail_en'],
				'con_datestart' => $_REQUEST['con_datestart'],
				'con_dateend' => $_REQUEST['con_dateend'],
				'con_modifiedby' => $sess['u_id'],
				'con_modifieddate' => date('Y-m-d H:i')
			);
			if ($_REQUEST['operation'] == "Add") {
				$data['con_createby'] = $sess['u_id'];
				$data['con_createdate'] = date('Y-m-d H:i');
				$this->lg->record('Setting', 'Create Event ' . $_REQUEST['con_title_th'] . ' By ' . $sess['fullname_th']);
				$msg = $this->setting->create_event($data);
			} else {
				$this->lg->record('Setting', 'Update Event ' . $_REQUEST['con_title_th'] . ' By ' . $sess['fullname_th']);
				$msg = $this->setting->update_event($data, $_REQUEST['con_id']);
			}
		}
		echo $msg;
	}

	public function insert_sortcourse()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->db->from('lms_cos_sort');
			$this->db->join('lms_cos', 'lms_cos_sort.cos_id = lms_cos.id');
			$this->db->where('lms_cos.com_id', $_REQUEST['com_id_sort']);
			$this->db->order_by('lms_cos_sort.coss_num', 'DESC');
			$query = $this->db->get();
			$fetch = $query->row_array();
			if (countArray($fetch) > 0) {
				$coss_num = intval($fetch['coss_num']) + 1;
			} else {
				$coss_num = 1;
			}
			$data = array(
				'cos_id' => $_REQUEST['cos_id'],
				'coss_num' => $coss_num
			);
			if ($_REQUEST['operation_sort'] == "Add") {
				$this->db->insert('lms_cos_sort', $data);
				$msg = '2';
			}
		}
		echo $msg;
	}

	public function insert_mainmenu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$mm_status = isset($_REQUEST['mm_status']) ? $_REQUEST['mm_status'] : '0';
			$data = array(
				'mm_txt_th1' => $_REQUEST['mm_txt_th1'],
				'mm_txt_th2' => $_REQUEST['mm_txt_th2'],
				'mm_txt_en1' => $_REQUEST['mm_txt_en1'],
				'mm_txt_en2' => $_REQUEST['mm_txt_en2'],
				'com_id' => $_REQUEST['com_id'],
				'mm_icon' => $_REQUEST['mm_icon'],
				'mm_status' => $mm_status,
				'mm_modifieddate' => date('Y-m-d H:i'),
				'mm_modifiedby' => $emp_c
			);
			if ($_REQUEST['operation'] == "Add") {
				$msg = $this->setting->create_mainmenu($data);
			} else {
				$msg = $this->setting->update_mainmenu($data, $_REQUEST['mm_id']);
			}
		}
		echo $msg;
	}

	public function insert_faq()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'title' => $_REQUEST['title'],
				'lang' => $_REQUEST['lang'],
				'time_edit' => date('Y-m-d H:i'),
				'emp_c' => $emp_c
			);
			if ($_REQUEST['operation'] == "Add") {
				$date['time_created'] = date('Y-m-d H:i');
				$msg = $this->setting->create_faq_main($data);
			} else {
				$msg = $this->setting->update_faq($data, $_REQUEST['id']);
			}
		}
		echo $msg;
	}
	public function insert_faq_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_c = $sess['emp_c'];
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'question' => $_REQUEST['question'],
				'answer' => $_REQUEST['answer'],
				'tid' => $_REQUEST['tid'],
				'time_edit' => date('Y-m-d H:i'),
				'emp_c' => $emp_c
			);
			if ($_REQUEST['operation_detail'] == "Add") {
				$date['time_created'] = date('Y-m-d H:i');
				$msg = $this->setting->create_faq_detail($data);
			} else {
				$msg = $this->setting->update_faq_detail($data, $_REQUEST['faq_detail_id']);
			}
		}
		echo $msg;
	}

	public function update_testimonials_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['tim_id_update'], 'lms_testimonials', 'tim_id');
			echo json_encode($result);
		}
	}

	public function update_bannercourse_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['bc_id_update'], 'lms_ban_cos', 'bc_id');
			echo json_encode($result);
		}
	}

	public function update_faq()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['id'], 'lms_faq', 'id');
			echo json_encode($result);
		}
	}

	public function update_menu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['mu_id'], 'lms_menu', 'mu_id');
			echo json_encode($result);
		}
	}

	public function update_mainmenu()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['mm_id'], 'lms_mainmenu', 'mm_id');
			echo json_encode($result);
		}
	}

	public function update_faq_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['id'], 'lms_faq_q', 'id');
			echo json_encode($result);
		}
	}

	public function update_event()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['con_id'], 'lms_content', 'con_id');
			echo json_encode($result);
		}
	}
	public function delete_formatmail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Course_model', 'course', false);
			$fetch = $this->course->query_data_onupdate($_REQUEST['smf_id_delete'], 'lms_sendmail_form', 'smf_id');
			$this->lg->record('Setting', 'Delete Format E-Mail ' . $fetch['smf_subject_th'] . ' By ' . $sess['fullname_th']);
			$msg = $this->setting->delete_formatmail($_REQUEST['smf_id_delete']);
		}
		echo $msg;
	}

	public function upload_img_texteditor()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();

		reset($_FILES);
		$temp = current($_FILES);

		if (is_uploaded_file($temp['tmp_name'])) {
			if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
				header("HTTP/1.1 400 Invalid file name,Bad request");
				return;
			}

			$extension = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
			$allowed_image_extensions = array("gif", "jpg", "jpeg", "png");
			$allowed_video_extensions = array("mp4", "webm", "ogv", "mov");
			$allowed_extensions = array_merge($allowed_image_extensions, $allowed_video_extensions);

			if (!in_array($extension, $allowed_extensions)) {
				header("HTTP/1.1 400 Unsupported media type");
				return;
			}

			if (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime_type = finfo_file($finfo, $temp['tmp_name']);
				finfo_close($finfo);
				if (
					(in_array($extension, $allowed_image_extensions) && strpos($mime_type, 'image/') !== 0) ||
					(in_array($extension, $allowed_video_extensions) && strpos($mime_type, 'video/') !== 0 && $mime_type !== 'application/octet-stream')
				) {
					header("HTTP/1.1 400 Invalid media file");
					return;
				}
			}

			$upload_path = ROOT_DIR . "uploads/texteditor/";
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0775, true);
			}

			$file_type = in_array($extension, $allowed_video_extensions) ? "video" : "image";
			$filenamenew = htmlentities($sess['useri'] . "_" . date('YmdHis') . "_" . uniqid(), ENT_QUOTES) . "." . $extension;
			$fileName = ROOT_DIR . "uploads/texteditor/" . $filenamenew;
			audit_move_uploaded_file($temp['tmp_name'], $fileName);

			// Return JSON response with the uploaded file path.
			echo json_encode(array(
				'file_path' => base_url() . "uploads/texteditor/" . $filenamenew,
				'file_type' => $file_type
			));
		}
	}

	public function delete_mainmenu_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Course_model', 'course', false);
			$fetch = $this->course->query_data_onupdate($_REQUEST['id_delete'], 'lms_mainmenu', 'mm_id');
			$this->lg->record('Setting', 'Delete Main menu ' . $fetch['mm_txt_th1'] . ' By ' . $sess['fullname_th']);
			$msg = $this->setting->delete_mainmenu($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_menu_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', false);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$this->load->model('Course_model', 'course', false);
			$fetch = $this->course->query_data_onupdate($_REQUEST['id_delete'], 'lms_menu', 'mu_id');
			$this->lg->record('Setting', 'Delete Menu ' . $fetch['mu_name_th'] . ' By ' . $sess['fullname_th']);
			$msg = $this->setting->delete_menu($_REQUEST['id_delete']);
		}
		echo $msg;
	}

	public function delete_faq_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_faq($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_event_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_event_data($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_banner()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_banner($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_banner_about()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_banner_about($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_testimonials_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_testimonials($_REQUEST['tim_id_delete']);
		}
		echo $msg;
	}
	public function delete_faq_data_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_faq_detail($_REQUEST['id_delete']);
		}
		echo $msg;
	}
	public function delete_bannercourse_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->setting->delete_bannercourse($_REQUEST['bc_id_delete']);
		}
		echo $msg;
	}

	public function fetch_detail_faq($tid)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_faq_detail($tid) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_banner($com_id = '')
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Setting_model', 'setting', false);
		$this->setting->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->setting->fetch_data_banner($com_id) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function import_question()
	{
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Function_query_model', 'func_query', true);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		$arr_output = array();
		$result_str = "";
		if (countArray($_REQUEST) > 0) {
			$fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $_REQUEST['qiz_id_question_import'] . '"');
			$quiz_limitval = number_format($fetch_qiz['quiz_limitval']);
			if (countArray($fetch_qiz) > 0) {
				$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch_qiz['cos_id'] . '"');
				$quiz_lang = explode(',', $fetch_cos['cos_lang']);

				$file_import_question = $_FILES["file_import_question"]["name"];

				$excel_file = $_FILES['file_import_question']['tmp_name'];
				$path_parts = pathinfo($file_import_question);
				$excel_path = "importques_" . date('YmdHis') . "." . $path_parts['extension'];

				$excelTargetPath = ROOT_DIR . "uploads/excel/" . $excel_path;
				if (audit_move_uploaded_file($excel_file, $excelTargetPath)) {

					$path = './uploads/excel/' . basename($excel_path);
					$objPHPExcel = PHPExcel_IOFactory::load($path);
					$result_arr = array();
					$result_arr['success_count'] = 0;
					$result_arr['duplicate_count'] = 0;
					$result_arr['error_count'] = 0;
					$result_arr['success_data'] = array();
					$result_arr['duplicate_data'] = array();
					$result_arr['error_data'] = array();
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						//$nrColumns = ord($highestColumn) - 64;

						for ($row = 2; $row <= $highestRow; ++$row) {
							$dep_name_th = '';
							$dep_name_en = '';
							$posi_name_th = '';
							$posi_name_en = '';
							$rechk_val = 1;
							$arr_ques = array();
							$arr_multi = array();
							$arr_ques['qiz_id'] = $_REQUEST['qiz_id_question_import'];
							// if ($highestColumnIndex == 25) {
								for ($col = 0; $col < $highestColumnIndex; ++$col) {
									$cell = $worksheet->getCellByColumnAndRow($col, $row);
									$val = $cell->getValue();
									$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
									if ($col == 0) {
										$arr_ques['ques_name_th'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 1) {
										$arr_ques['ques_info_th'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 2) {
										$arr_ques['ques_name_eng'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 3) {
										$arr_ques['ques_info_eng'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 4) {
										$arr_ques['ques_name_jp'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 5) {
										$arr_ques['ques_info_jp'] = htmlspecialchars($val ?? '', ENT_QUOTES);
									}
									if ($col == 6) {
										if ($val == "Display") {
											$val = '1';
										} else {
											$val = '0';
										}
										$arr_ques['ques_status'] = $val;
									}
									if ($col == 7) {
										$arr_ques['ques_score'] = htmlentities($val ?? '');
									}
									if ($col == 8) {
										if ($val == "Short answer") {
											$val = 'sa';
										} else if ($val == "Long answer") {
											$val = 'sub';
										} else if ($val == "Multiple choice") {
											$val = 'multi';
										} else {
											$val = '2choice';
										}
										$arr_ques['ques_type'] = $val;
									}
									if ($col == 9) {
										$arr_multi['mul_c1_th'] = strval($val);
									}
									if ($col == 10) {
										$arr_multi['mul_c2_th'] = strval($val);
									}
									if ($col == 11) {
										$arr_multi['mul_c3_th'] = strval($val);
									}
									if ($col == 12) {
										$arr_multi['mul_c4_th'] = strval($val);
									}
									if ($col == 13) {
										$arr_multi['mul_c5_th'] = strval($val);
									}
									if ($col == 14) {
										$arr_multi['mul_c1_eng'] = strval($val);
									}
									if ($col == 15) {
										$arr_multi['mul_c2_eng'] = strval($val);
									}
									if ($col == 16) {
										$arr_multi['mul_c3_eng'] = strval($val);
									}
									if ($col == 17) {
										$arr_multi['mul_c4_eng'] = strval($val);
									}
									if ($col == 18) {
										$arr_multi['mul_c5_eng'] = strval($val);
									}
									if ($col == 19) {
										$arr_multi['mul_c1_jp'] = strval($val);
									}
									if ($col == 20) {
										$arr_multi['mul_c2_jp'] = strval($val);
									}
									if ($col == 21) {
										$arr_multi['mul_c3_jp'] = strval($val);
									}
									if ($col == 22) {
										$arr_multi['mul_c4_jp'] = strval($val);
									}
									if ($col == 23) {
										$arr_multi['mul_c5_jp'] = strval($val);
									}
									if ($col == 24) {
										$val = str_replace(" ", "", $val);
										$arr = explode(",", $val);
										$str = "";
										for ($i = 0; $i < countArray($arr); $i++) {
											if (intval($arr[$i]) <= 5) {
												$str .= "mul_c" . $arr[$i];
											}
											if ($i < (countArray($arr) - 1)) {
												$str .= ",";
											}
										}
										$arr_multi['mul_answer'] = $str;
									}
								}
								if (!in_array('th', $quiz_lang)) {
									$arr_ques['ques_name_th'] = "";
									$arr_ques['ques_info_th'] = "";
									$arr_multi['mul_c1_th'] = "";
									$arr_multi['mul_c2_th'] = "";
									$arr_multi['mul_c3_th'] = "";
									$arr_multi['mul_c4_th'] = "";
									$arr_multi['mul_c5_th'] = "";
								}
								if (!in_array('eng', $quiz_lang)) {
									$arr_ques['ques_name_eng'] = "";
									$arr_ques['ques_info_eng'] = "";
									$arr_multi['mul_c1_eng'] = "";
									$arr_multi['mul_c2_eng'] = "";
									$arr_multi['mul_c3_eng'] = "";
									$arr_multi['mul_c4_eng'] = "";
									$arr_multi['mul_c5_eng'] = "";
								}
								if (!in_array('jp', $quiz_lang)) {
									$arr_ques['ques_name_jp'] = "";
									$arr_ques['ques_info_jp'] = "";
									$arr_multi['mul_c1_jp'] = "";
									$arr_multi['mul_c2_jp'] = "";
									$arr_multi['mul_c3_jp'] = "";
									$arr_multi['mul_c4_jp'] = "";
									$arr_multi['mul_c5_jp'] = "";
								}
								//echo $arr_ques['ques_name_th']."2025";
								$lang_arr = array();
								$amount_lang = 0;
								if (isset($arr_ques['ques_name_th']) && $arr_ques['ques_name_th'] != "") {
									array_push($lang_arr, 'th');
									$amount_lang++;
								}
								if (isset($arr_ques['ques_name_eng']) && $arr_ques['ques_name_eng'] != "") {
									array_push($lang_arr, 'eng');
									$amount_lang++;
								}
								if (isset($arr_ques['ques_name_jp']) && $arr_ques['ques_name_jp'] != "") {
									array_push($lang_arr, 'jp');
									$amount_lang++;
								}
								$chklang = 0;
								if ($lang == "thai") {
									$ques_name = $arr_ques['ques_name_th'] != "" ? $arr_ques['ques_name_th'] : $arr_ques['ques_name_eng'];
									$ques_name = $ques_name != "" ? $ques_name : $arr_ques['ques_name_jp'];
								} else if ($lang == "english") {
									$ques_name = $arr_ques['ques_name_eng'] != "" ? $arr_ques['ques_name_eng'] : $arr_ques['ques_name_th'];
									$ques_name = $ques_name != "" ? $ques_name : $arr_ques['ques_name_jp'];
								} else {
									$ques_name = $arr_ques['ques_name_jp'] != "" ? $arr_ques['ques_name_jp'] : $arr_ques['ques_name_eng'];
									$ques_name = $ques_name != "" ? $ques_name : $arr_ques['ques_name_th'];
								}
								$valuechk = 1;
								if ($fetch_qiz['quiz_random_choice'] == "1" || $fetch_qiz['quiz_ishint'] == "1" || $fetch_qiz['quiz_model'] == "1") {
									if (!in_array($arr_ques['ques_type'], array('multi', '2choice'))) {
										$valuechk = 0;
									}
								}
								$rechk_null = 0;
								for ($i = 0; $i < countArray($quiz_lang); $i++) {
									if ($quiz_lang[$i] == "th") {
										$ques_name_th = $arr_ques['ques_name_th'];
										if ($ques_name_th == "") {
											$rechk_null++;
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											if ($arr_multi['mul_c1_th'] == "") {
												$rechk_null++;
											}
											if ($arr_multi['mul_c2_th'] == "") {
												$rechk_null++;
											}
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											$arr_mul_answer = explode(',', $arr_multi['mul_answer']);
											if (countArray($arr_mul_answer) > 0) {
												for ($mul = 0; $mul < countArray($arr_mul_answer); $mul++) {
													$var_chkchoice = isset($arr_multi[$arr_mul_answer[$mul] . '_th']) ? $arr_multi[$arr_mul_answer[$mul] . '_th'] : "";
													if ($var_chkchoice == "") {
														$rechk_null++;
													}
												}
											}
										}
									}
									if ($quiz_lang[$i] == "eng") {

										$ques_name_eng = $arr_ques['ques_name_eng'];
										if ($ques_name_eng == "") {
											$rechk_null++;
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											if ($arr_multi['mul_c1_eng'] == "") {
												$rechk_null++;
											}
											if ($arr_multi['mul_c2_eng'] == "") {
												$rechk_null++;
											}
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											$arr_mul_answer = explode(',', $arr_multi['mul_answer']);
											if (countArray($arr_mul_answer) > 0) {
												for ($mul = 0; $mul < countArray($arr_mul_answer); $mul++) {
													$var_chkchoice = isset($arr_multi[$arr_mul_answer[$mul] . '_eng']) ? $arr_multi[$arr_mul_answer[$mul] . '_eng'] : "";
													if ($var_chkchoice == "") {
														$rechk_null++;
													}
												}
											}
										}
									}
									if ($quiz_lang[$i] == "jp") {

										$ques_name_jp = $arr_ques['ques_name_jp'];
										if ($ques_name_jp == "") {
											$rechk_null++;
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											if ($arr_multi['mul_c1_jp'] == "") {
												$rechk_null++;
											}
											if ($arr_multi['mul_c2_jp'] == "") {
												$rechk_null++;
											}
										}
										if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
											$arr_mul_answer = explode(',', $arr_multi['mul_answer']);
											if (countArray($arr_mul_answer) > 0) {
												for ($mul = 0; $mul < countArray($arr_mul_answer); $mul++) {
													$var_chkchoice = isset($arr_multi[$arr_mul_answer[$mul] . '_jp']) ? $arr_multi[$arr_mul_answer[$mul] . '_jp'] : "";
													if ($var_chkchoice == "") {
														$rechk_null++;
													}
												}
											}
										}
									}
								}
								if ($arr_ques['ques_type'] == "2choice" || $arr_ques['ques_type'] == "multi") {
									if (countArray($quiz_lang) > 1) {
										for ($chkloop = 1; $chkloop <= 5; $chkloop++) {
											$langtotal = 0;
											$langtotal_null = 0;
											for ($i = 0; $i < countArray($quiz_lang); $i++) {
												if ($quiz_lang[$i] == "th") {
													$mul_th = $arr_multi['mul_c' . $chkloop . '_th'];
													if ($mul_th != "") {
														$langtotal_null++;
													}
												}
												if ($quiz_lang[$i] == "eng") {
													$langtotal++;
													$mul_eng = $arr_multi['mul_c' . $chkloop . '_eng'];
													if ($mul_eng != "") {
														$langtotal_null++;
													}
												}
												if ($quiz_lang[$i] == "jp") {
													$langtotal++;
													$mul_jp = $arr_multi['mul_c' . $chkloop . '_jp'];
													if ($mul_jp != "") {
														$langtotal_null++;
													}
												}
											}
											if ($langtotal_null > 0 && countArray($quiz_lang) != $langtotal_null) {
												$rechk_null++;
											}
										}
									}
								}
								if ($rechk_null > 0) {
									$rechk_val = 0;
								}
								if (countArray($quiz_lang) > 0 && countArray($lang_arr) > 0 && $valuechk == 1 && $rechk_val == 1) {
									$where_ques = "";
									if ($arr_ques['ques_name_th'] != "") {
										$where_ques .= "ques_name_th = '" . $arr_ques['ques_name_th'] . "'";
									}
									if ($arr_ques['ques_name_eng'] != "") {
										if ($where_ques != "") {
											$where_ques .= ' and ';
										}
										$where_ques .= "ques_name_eng = '" . $arr_ques['ques_name_eng'] . "'";
									}
									if ($arr_ques['ques_name_jp'] != "") {
										if ($where_ques != "") {
											$where_ques .= ' and ';
										}
										$where_ques .= "ques_name_jp = '" . $arr_ques['ques_name_jp'] . "'";
									}
									$where = 'qiz_id="' . $_REQUEST['qiz_id_question_import'] . '" and ques_type="' . $arr_ques['ques_type'] . '" and (' . $where_ques . ') and ques_isDelete="0"';
									$num_chk = $this->func_query->numrows('lms_ques', '', '', '', $where);


									if ($num_chk == 0) {
										$arr_ques['ques_modifiedby'] = $sess['u_id'];
										$arr_ques['ques_modifieddate'] = date('Y-m-d H:i');
										$arr_ques['ques_createby'] = $sess['u_id'];
										$arr_ques['ques_createdate'] = date('Y-m-d H:i');

										if ($quiz_limitval > 1 || $quiz_limitval == 0) {
											if ($arr_ques['ques_type'] != "sa" && $arr_ques['ques_type'] != "sub") {
												$this->db->insert('lms_ques', $arr_ques);
												$ques_id = $this->db->insert_id();
											} else {
												$ques_id = "";
											}
										} else {
											$this->db->insert('lms_ques', $arr_ques);
											$ques_id = $this->db->insert_id();
										}

										if ($ques_id == "") {
											$result_arr['error_count']++;
											array_push($result_arr['error_data'], $ques_name);
										} else {
											$result_arr['success_count']++;
											array_push($result_arr['success_data'], $ques_name);
										}
										if (($arr_ques['ques_type'] == "multi" || $arr_ques['ques_type'] == "2choice") && $ques_id != "") {
											$ques_id = $this->db->insert_id();
											$arr_multi['ques_id'] = $ques_id;
											$arr_multi['mul_createby'] = $sess['u_id'];
											$arr_multi['mul_createdate'] = date('Y-m-d H:i');
											$arr_multi['mul_modifiedby'] = $sess['u_id'];
											$arr_multi['mul_modifieddate'] = date('Y-m-d H:i');
											$this->db->insert('lms_ques_mul', $arr_multi);
										}
									} else {
										$result_arr['duplicate_count']++;
										array_push($result_arr['duplicate_data'], $ques_name);
									}
								} else {
									$result_arr['error_count']++;
									array_push($result_arr['error_data'], $ques_name);
								}
							// } else {
							// 	$result_arr['error_count']++;
							// 	array_push($result_arr['error_data'], 'Error: Incomplete column');
							// }
						}
					}


					$result_str = "";
					$result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
					if (countArray($result_arr['success_data']) > 0) {
						$quiz_numofshown = intval($fetch_qiz['quiz_numofshown']) + countArray($result_arr['success_data']);
						$arr_update = array(
							'quiz_numofshown' => $quiz_numofshown
						);
						$this->db->where('qiz_id', $_REQUEST['qiz_id_question_import']);
						$this->db->update('lms_qiz', $arr_update);
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
							if ($result_arr['success_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
					if (countArray($result_arr['duplicate_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
							if ($result_arr['duplicate_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
	
					$result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
					if (countArray($result_arr['error_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
							if ($result_arr['error_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><br>";
					}
					$arr_output['status'] = "2";
					$arr_output['result'] = $result_str;
				} else {
					$arr_output['status'] = "0";
				}
			} else {
				$arr_output['status'] = "0";
			}
		} else {
			$arr_output['status'] = "0";
		}

		$this->lg->record('Setting', 'Import Question By ' . $sess['fullname_th']);

		echo json_encode($arr_output);
	}


	public function import_question_survey()
	{
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Function_query_model', 'func_query', true);
		$this->load->model('Log_model', 'lg', false);
		$this->setting->loadDB();
		$arr_output = array();
		$result_str = "";
		if (countArray($_REQUEST) > 0) {
			$fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id_question_import'] . '"');
			if (countArray($fetch_sv) > 0) {
				$sv_lang = explode(',', $fetch_sv['sv_lang']);

				$file_import_question = $_FILES["file_import_question"]["name"];

				$excel_file = $_FILES['file_import_question']['tmp_name'];
				$path_parts = pathinfo($file_import_question);
				$excel_path = "importques_" . date('YmdHis') . "." . $path_parts['extension'];

				$excelTargetPath = ROOT_DIR . "uploads/excel/" . $excel_path;
				if (audit_move_uploaded_file($excel_file, $excelTargetPath)) {

					$path = './uploads/excel/' . basename($excel_path);
					$objPHPExcel = PHPExcel_IOFactory::load($path);
					$result_arr = array();
					$result_arr['success_count'] = 0;
					$result_arr['duplicate_count'] = 0;
					$result_arr['error_count'] = 0;
					$result_arr['success_data'] = array();
					$result_arr['duplicate_data'] = array();
					$result_arr['error_data'] = array();
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						//$nrColumns = ord($highestColumn) - 64;

						for ($row = 2; $row <= $highestRow; ++$row) {
							$dep_name_th = '';
							$dep_name_en = '';
							$posi_name_th = '';
							$posi_name_en = '';
							$rechk_val = 1;
							$arr_ques = array();
							$arr_multi = array();
							$arr_ques['sv_id'] = $_REQUEST['sv_id_question_import'];

							for ($col = 0; $col < $highestColumnIndex; ++$col) {
								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
								if ($col == 0) {
									$arr_ques['svde_header_th'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 1) {
									$arr_ques['svde_name_th'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 2) {
									$arr_ques['svde_info_th'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 3) {
									$arr_ques['svde_header_eng'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 4) {
									$arr_ques['svde_name_eng'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 5) {
									$arr_ques['svde_info_eng'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 6) {
									$arr_ques['svde_header_jp'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 7) {
									$arr_ques['svde_name_jp'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 8) {
									$arr_ques['svde_info_jp'] = htmlspecialchars($val, ENT_QUOTES);
								}
								if ($col == 9) {
									if ($val == "Display") {
										$val = '1';
									} else {
										$val = '0';
									}
									$arr_ques['svde_status'] = $val;
								}
								if ($col == 10) {
									if ($val == "Short answer") {
										$val = 'sa';
									} else if ($val == "Long answer") {
										$val = 'sub';
									} else if ($val == "Multiple choice") {
										$val = 'multi';
									} else if ($val == "Scale") {
										$val = 'scale';
									} else {
										$val = '2choice';
									}
									$arr_ques['svde_type'] = $val;
								}

								$arrSearchString = array("\n", "\r", "`");
								if ($col == 11) {
									$arr_multi['mul_c1_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 12) {
									$arr_multi['mul_c2_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 13) {
									$arr_multi['mul_c3_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 14) {
									$arr_multi['mul_c4_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 15) {
									$arr_multi['mul_c5_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 16) {
									$arr_multi['mul_c6_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 17) {
									$arr_multi['mul_c7_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 18) {
									$arr_multi['mul_c8_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 19) {
									$arr_multi['mul_c9_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 20) {
									$arr_multi['mul_c10_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 21) {
									$arr_multi['mul_c11_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 22) {
									$arr_multi['mul_c12_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 23) {
									$arr_multi['mul_c13_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 24) {
									$arr_multi['mul_c14_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 25) {
									$arr_multi['mul_c15_th'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 26) {
									$arr_multi['mul_c1_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 27) {
									$arr_multi['mul_c2_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 28) {
									$arr_multi['mul_c3_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 29) {
									$arr_multi['mul_c4_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 30) {
									$arr_multi['mul_c5_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 31) {
									$arr_multi['mul_c6_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 32) {
									$arr_multi['mul_c7_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 33) {
									$arr_multi['mul_c8_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 34) {
									$arr_multi['mul_c9_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 35) {
									$arr_multi['mul_c10_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 36) {
									$arr_multi['mul_c11_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 37) {
									$arr_multi['mul_c12_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 38) {
									$arr_multi['mul_c13_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 39) {
									$arr_multi['mul_c14_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 40) {
									$arr_multi['mul_c15_eng'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 41) {
									$arr_multi['mul_c1_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 42) {
									$arr_multi['mul_c2_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 43) {
									$arr_multi['mul_c3_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 44) {
									$arr_multi['mul_c4_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 45) {
									$arr_multi['mul_c5_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 46) {
									$arr_multi['mul_c6_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 47) {
									$arr_multi['mul_c7_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 48) {
									$arr_multi['mul_c8_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 49) {
									$arr_multi['mul_c9_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 50) {
									$arr_multi['mul_c10_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 51) {
									$arr_multi['mul_c11_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 52) {
									$arr_multi['mul_c12_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 53) {
									$arr_multi['mul_c13_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 54) {
									$arr_multi['mul_c14_jp'] = str_replace($arrSearchString, "", strval($val));
								}
								if ($col == 55) {
									$arr_multi['mul_c15_jp'] = str_replace($arrSearchString, "", strval($val));
								}
							}
							$lang_arr = array();
							if ($fetch_sv['sv_lang'] == "0") {
								$arr_ques['svde_header_th'] = "";
								$arr_ques['svde_header_eng'] = "";
								$arr_ques['svde_header_jp'] = "";
							}
							$amount_lang = 0;
							if (isset($arr_ques['svde_name_th']) && $arr_ques['svde_name_th'] != "") {
								array_push($lang_arr, 'th');
								$amount_lang++;
							}
							if (isset($arr_ques['svde_name_eng']) && $arr_ques['svde_name_eng'] != "") {
								array_push($lang_arr, 'eng');
								$amount_lang++;
							}
							if (isset($arr_ques['svde_name_jp']) && $arr_ques['svde_name_jp'] != "") {
								array_push($lang_arr, 'jp');
								$amount_lang++;
							}
							$chklang = 0;
							if ($lang == "thai") {
								$svde_name = $arr_ques['svde_name_th'] != "" ? $arr_ques['svde_name_th'] : $arr_ques['svde_name_eng'];
								$svde_name = $svde_name != "" ? $svde_name : $arr_ques['svde_name_jp'];
							} else if ($lang == "english") {
								$svde_name = $arr_ques['svde_name_eng'] != "" ? $arr_ques['svde_name_eng'] : $arr_ques['svde_name_th'];
								$svde_name = $svde_name != "" ? $svde_name : $arr_ques['svde_name_jp'];
							} else {
								$svde_name = $arr_ques['svde_name_jp'] != "" ? $arr_ques['svde_name_jp'] : $arr_ques['svde_name_eng'];
								$svde_name = $svde_name != "" ? $svde_name : $arr_ques['svde_name_th'];
							}
							$rechk_null = 0;
							for ($i = 0; $i < countArray($sv_lang); $i++) {
								if ($sv_lang[$i] == "th") {
									$svde_name_th = $arr_ques['svde_name_th'];
									if ($svde_name_th == "") {
										$rechk_null++;
									}
									if ($arr_ques['svde_type'] == "2choice" || $arr_ques['svde_type'] == "multi") {
										if ($arr_multi['mul_c1_th'] == "") {
											$rechk_null++;
										}
										if ($arr_multi['mul_c2_th'] == "") {
											$rechk_null++;
										}
									}
								}
								if ($sv_lang[$i] == "eng") {

									$svde_name_eng = $arr_ques['svde_name_eng'];
									if ($svde_name_eng == "") {
										$rechk_null++;
									}
									if ($arr_ques['svde_type'] == "2choice" || $arr_ques['svde_type'] == "multi") {
										if ($arr_multi['mul_c1_eng'] == "") {
											$rechk_null++;
										}
										if ($arr_multi['mul_c2_eng'] == "") {
											$rechk_null++;
										}
									}
								}
								if ($sv_lang[$i] == "jp") {

									$svde_name_jp = $arr_ques['svde_name_jp'];
									if ($svde_name_jp == "") {
										$rechk_null++;
									}
									if ($arr_ques['svde_type'] == "2choice" || $arr_ques['svde_type'] == "multi") {
										if ($arr_multi['mul_c1_jp'] == "") {
											$rechk_null++;
										}
										if ($arr_multi['mul_c2_jp'] == "") {
											$rechk_null++;
										}
									}
								}
							}
							if ($arr_ques['svde_type'] == "multi") {
								if (countArray($sv_lang) > 1) {
									for ($chkloop = 1; $chkloop <= 5; $chkloop++) {
										$langtotal = 0;
										$langtotal_null = 0;
										for ($i = 0; $i < countArray($sv_lang); $i++) {
											if ($sv_lang[$i] == "th") {
												$mul_th = $arr_multi['mul_c' . $chkloop . '_th'];
												if ($mul_th != "") {
													$langtotal_null++;
												}
											}
											if ($sv_lang[$i] == "eng") {
												$langtotal++;
												$mul_eng = $arr_multi['mul_c' . $chkloop . '_eng'];
												if ($mul_eng != "") {
													$langtotal_null++;
												}
											}
											if ($sv_lang[$i] == "jp") {
												$langtotal++;
												$mul_jp = $arr_multi['mul_c' . $chkloop . '_jp'];
												if ($mul_jp != "") {
													$langtotal_null++;
												}
											}
										}
										if ($langtotal_null > 0 && countArray($sv_lang) != $langtotal_null) {
											$rechk_null++;
										}
									}
								}
							}
							if ($rechk_null > 0) {
								$rechk_val = 0;
							}
							if (countArray($sv_lang) > 0 && countArray($lang_arr) > 0 && $rechk_val == 1) {
								/*for($a=0;$a<countArray($sv_lang);$a++){
										if(in_array($sv_lang[$a], $lang_arr)){
											$chklang++;
										}
									}
									if($chklang==countArray($sv_lang)){*/
								$where_ques = "";
								if ($arr_ques['svde_name_th'] != "") {
									$where_ques .= "svde_name_th = '" . $arr_ques['svde_name_th'] . "'";
								}
								if ($arr_ques['svde_name_eng'] != "") {
									if ($where_ques != "") {
										$where_ques .= ' and ';
									}
									$where_ques .= "svde_name_eng = '" . $arr_ques['svde_name_eng'] . "'";
								}
								if ($arr_ques['svde_name_jp'] != "") {
									if ($where_ques != "") {
										$where_ques .= ' and ';
									}
									$where_ques .= "svde_name_jp = '" . $arr_ques['svde_name_jp'] . "'";
								}
								$where = 'sv_id="' . $_REQUEST['sv_id_question_import'] . '" and svde_type="' . $arr_ques['svde_type'] . '" and (' . $where_ques . ') and svde_isDelete="0"';
								$num_chk = $this->func_query->query_row('lms_svde', '', '', '', $where);
								if (countArray($num_chk) == 0) {
									$arr_ques['svde_modifiedby'] = $sess['u_id'];
									$arr_ques['svde_modifieddate'] = date('Y-m-d H:i');
									$arr_ques['svde_createby'] = $sess['u_id'];
									$arr_ques['svde_createdate'] = date('Y-m-d H:i');
									$this->db->insert('lms_svde', $arr_ques);
									$svde_id = $this->db->insert_id();
									if ($svde_id == "") {
										$result_arr['error_count']++;
										array_push($result_arr['error_data'], $svde_name . "::2010");
									} else {
										$result_arr['success_count']++;
										array_push($result_arr['success_data'], $svde_name);
									}
									if (($arr_ques['svde_type'] == "multi" || $arr_ques['svde_type'] == "2choice") && $svde_id != "") {
										$svde_id = $this->db->insert_id();
										$arr_multi['svde_id'] = $svde_id;
										$arr_multi['mul_createby'] = $sess['u_id'];
										$arr_multi['mul_createdate'] = date('Y-m-d H:i');
										$arr_multi['mul_modifiedby'] = $sess['u_id'];
										$arr_multi['mul_modifieddate'] = date('Y-m-d H:i');
										$this->db->insert('lms_svde_mul', $arr_multi);
									}
								} else {
									$arr_ques['svde_modifiedby'] = $sess['u_id'];
									$arr_ques['svde_modifieddate'] = date('Y-m-d H:i');
									$this->db->where('svde_id', $num_chk['svde_id']);
									$this->db->update('lms_svde', $arr_ques);
									if (($arr_ques['svde_type'] == "multi" || $arr_ques['svde_type'] == "2choice") && $svde_id != "") {
										$arr_multi['mul_modifiedby'] = $sess['u_id'];
										$arr_multi['mul_modifieddate'] = date('Y-m-d H:i');
										$rechkchoice = $this->func_query->query_row('lms_svde_mul', '', '', '', 'svde_id="' . $num_chk['svde_id'] . '"');
										if (countArray($rechkchoice) > 0) {
											$this->db->where('svde_id', $num_chk['svde_id']);
											$this->db->update('lms_svde_mul', $arr_multi);
										} else {
											$arr_multi['mul_createby'] = $sess['u_id'];
											$arr_multi['mul_createdate'] = date('Y-m-d H:i');
											$arr_multi['svde_id'] = $num_chk['svde_id'];
											$this->db->insert('lms_svde_mul', $arr_multi);
										}
									}
									$result_arr['duplicate_count']++;
									array_push($result_arr['duplicate_data'], $svde_name);
								}
								/*}else{
										$result_arr['error_count']++;
					                	array_push($result_arr['error_data'], $svde_name);
									}*/
							} else {
								$result_arr['error_count']++;
								array_push($result_arr['error_data'], $svde_name . "::2034");
							}
						}
					}


					$result_str = "";
					$result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
					if (countArray($result_arr['success_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
							if ($result_arr['success_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
					if (countArray($result_arr['duplicate_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
							if ($result_arr['duplicate_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
					if (countArray($result_arr['error_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
							if ($result_arr['error_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><br>";
					}
					$arr_output['status'] = "2";
					$arr_output['result'] = $result_str;
				} else {
					$arr_output['status'] = "0";
				}
			} else {
				$arr_output['status'] = "0";
			}
		} else {
			$arr_output['status'] = "0";
		}

		$this->lg->record('Setting', 'Import Question Survey By ' . $sess['fullname_th']);

		echo json_encode($arr_output);
	}

	public function import_survey()
	{
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata('user');
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->setting->loadDB();

		$result_arr = array();
		$result_arr['success_count'] = 0;
		$result_arr['duplicate_count'] = 0;
		$result_arr['error_count'] = 0;
		$result_arr['success_data'] = array();
		$result_arr['duplicate_data'] = array();
		$result_arr['error_data'] = array();
		if (countArray($_REQUEST) > 0) {
			$imageSourcePath = $_FILES['file_import_survey']['tmp_name'];
			$pathBG = $_FILES['file_import_survey']['name'];
			if ($pathBG != "") {

				$array_pathext = explode('.', $pathBG);
				$extension = end($array_pathext);
				$file_import_survey = "importxlsxsurvey_" . date('YmdHis') . "." . $extension;
				$imageTargetPath = ROOT_DIR . "uploads/excel/" . $file_import_survey;
				if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
					$data_user['file_import_survey'] = $file_import_survey;

					$path = './uploads/excel/' . $file_import_survey;
					$objPHPExcel = PHPExcel_IOFactory::load($path);
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						//$nrColumns = ord($highestColumn) - 64;

						for ($row = 2; $row <= $highestRow; ++$row) {
							$dep_name_th = '';
							$dep_name_en = '';
							$posi_name_th = '';
							$posi_name_en = '';
							$arr_svde = array();
							$arr_svde['sv_id'] = $_REQUEST['sv_id_detail_import'];
							$arr_svde['svde_heading_eng'] = '';
							$arr_svde['svde_heading_th'] = '';
							$arr_svde['svde_heading_jp'] = '';
							$arr_svde['svde_detail_eng'] = '';
							$arr_svde['svde_detail_th'] = '';
							$arr_svde['svde_detail_jp'] = '';
							for ($col = 0; $col < $highestColumnIndex; ++$col) {
								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);

								if ($col == 0) {
									$arr_svde['svde_heading_eng'] = htmlentities($val);
								}
								if ($col == 1) {
									$arr_svde['svde_heading_th'] = htmlentities($val);
								}
								if ($col == 2) {
									$arr_svde['svde_heading_jp'] = htmlentities($val);
								}
								if ($col == 3) {
									$arr_svde['svde_detail_eng'] = htmlentities($val);
								}
								if ($col == 4) {
									$arr_svde['svde_detail_th'] = htmlentities($val);
								}
								if ($col == 5) {
									$arr_svde['svde_detail_jp'] = htmlentities($val);
								}
							}
							$numchk = 0;
							$fetch_sv = $this->func_query->query_row('lms_survey', '', '', '', 'sv_id="' . $_REQUEST['sv_id_detail_import'] . '"');
							if (countArray($fetch_sv) > 0) {
								if ($fetch_sv['sv_title_th'] != "" && $arr_svde['svde_detail_th'] == "") {
									$numchk++;
								}
								if ($fetch_sv['sv_title_eng'] != "" && $arr_svde['svde_detail_eng'] == "") {
									$numchk++;
								}
								if ($fetch_sv['sv_title_jp'] != "" && $arr_svde['svde_detail_jp'] == "") {
									$numchk++;
								}
							}
							if ($lang == "thai") {
								$svde_heading = $arr_svde['svde_heading_th'] != "" ? $arr_svde['svde_heading_th'] : $arr_svde['svde_heading_eng'];
								$svde_heading = $svde_heading != "" ? $svde_heading : $arr_svde['svde_heading_jp'];
								$svde_detail = $arr_svde['svde_detail_th'] != "" ? $arr_svde['svde_detail_th'] : $arr_svde['svde_detail_eng'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $arr_svde['svde_detail_jp'];
							} else if ($lang == "english") {
								$svde_heading = $arr_svde['svde_heading_eng'] != "" ? $arr_svde['svde_heading_eng'] : $arr_svde['svde_heading_th'];
								$svde_heading = $svde_heading != "" ? $svde_heading : $arr_svde['svde_heading_jp'];
								$svde_detail = $arr_svde['svde_detail_eng'] != "" ? $arr_svde['svde_detail_eng'] : $arr_svde['svde_detail_th'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $arr_svde['svde_detail_jp'];
							} else {
								$svde_heading = $arr_svde['svde_heading_jp'] != "" ? $arr_svde['svde_heading_jp'] : $arr_svde['svde_heading_eng'];
								$svde_heading = $svde_heading != "" ? $svde_heading : $arr_svde['svde_heading_th'];
								$svde_detail = $arr_svde['svde_detail_jp'] != "" ? $arr_svde['svde_detail_jp'] : $arr_svde['svde_detail_eng'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $arr_svde['svde_detail_th'];
							}
							if ((($arr_svde['svde_heading_th'] != "" && $arr_svde['svde_detail_th'] != "") || ($arr_svde['svde_heading_eng'] != "" && $arr_svde['svde_detail_eng'] != "") || ($arr_svde['svde_heading_jp'] != "" && $arr_svde['svde_detail_jp'] != "")) && $numchk == 0) {

								$this->db->from('lms_survey_de');
								$this->db->where('sv_id', $_REQUEST['sv_id_detail_import']);
								$this->db->where('svde_heading_th', htmlentities($arr_svde['svde_heading_th']));
								$this->db->where('svde_heading_eng', htmlentities($arr_svde['svde_heading_eng']));
								$this->db->where('svde_heading_jp', htmlentities($arr_svde['svde_heading_jp']));
								$this->db->where('svde_detail_th', htmlentities($arr_svde['svde_detail_th']));
								$this->db->where('svde_detail_eng', htmlentities($arr_svde['svde_detail_eng']));
								$this->db->where('svde_detail_jp', htmlentities($arr_svde['svde_detail_jp']));
								$this->db->where('svde_isDelete', '0');
								$query_chk = $this->db->get();
								$num_chk = $query_chk->num_rows();
								if ($num_chk == 0) {
									$arr_svde['svde_createby'] = $sess['u_id'];
									$arr_svde['svde_createdate'] = date('Y-m-d H:i');
									$arr_svde['svde_modifiedby'] = $sess['u_id'];
									$arr_svde['svde_modifieddate'] = date('Y-m-d H:i');
									$this->db->insert('lms_survey_de', $arr_svde);
									$svde_id = $this->db->insert_id();
									if ($svde_id != "") {
										$result_arr['success_count']++;
										array_push($result_arr['success_data'], $svde_detail);
									} else {
										$result_arr['error_count']++;
										array_push($result_arr['error_data'], $svde_detail);
									}
								} else {
									$result_arr['duplicate_count']++;
									array_push($result_arr['duplicate_data'], $svde_detail);
								}
							} else {
								if ($svde_detail != "") {
									$result_arr['error_count']++;
									array_push($result_arr['error_data'], $svde_detail);
								}
							}
						}
					}
				}

				$result_str = "";
				$result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
				if (countArray($result_arr['success_data']) > 0) {
					$result_str .= "<ol>";
					for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
						if ($result_arr['success_data'][$i] != "") {
							$result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
						}
					}
					$result_str .= "</ol><hr><br>";
				}
				$result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
				if (countArray($result_arr['duplicate_data']) > 0) {
					$result_str .= "<ol>";
					for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
						if ($result_arr['duplicate_data'][$i] != "") {
							$result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
						}
					}
					$result_str .= "</ol><hr><br>";
				}
				$result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
				if (countArray($result_arr['error_data']) > 0) {
					$result_str .= "<ol>";
					for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
						if ($result_arr['error_data'][$i] != "") {
							$result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
						}
					}
					$result_str .= "</ol><br>";
				}

				$result_arr['status'] = "2";
				$result_arr['result'] = $result_str;
			} else {
				$result_arr['status'] = "0";
			}
		} else {
			$result_arr['status'] = "0";
		}

		$this->lg->record('Setting', 'Import Survey By ' . $sess['fullname_th']);
		echo json_encode($result_arr);
	}

	public function generateRandomString($length = 8)
	{
		return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
	}

	public function import_user()
	{
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata('user');
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Manage_model', 'manage', true);
		$this->load->model('Log_model', 'lg', false);
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Function_query_model', 'func_query', true);
		$this->setting->loadDB();
		$msg = "2";

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$result_arr = array();
		$result_arr['success_count'] = 0;
		$result_arr['duplicate_count'] = 0;
		$result_arr['error_count'] = 0;
		$result_arr['line_error'] = array();
		$result_arr['success_data'] = array();
		$result_arr['duplicate_data'] = array();
		$result_arr['error_data'] = array();
		if (countArray($_REQUEST) > 0) {

			if ($_REQUEST['operation_import_user'] == "Add") {
				$excel_file = $_FILES["file_import"]["name"];
				//$data['file_import'] = $excel_file;

				$imageSourcePath = $_FILES['file_import']['tmp_name'];
				$pathBG = $_FILES['file_import']['name'];
				$array_pathext = explode('.', $pathBG);
				$extension = end($array_pathext);
				$file_import = "importxlsxuser_" . date('YmdHis') . "." . $extension;
				$imageTargetPath = ROOT_DIR . "uploads/excel/" . $file_import;
				if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
					$path = './uploads/excel/' . $file_import;
					$objPHPExcel = PHPExcel_IOFactory::load($path);
					$lgiId = $this->lg->getLogImportUserID();
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						//$nrColumns = ord($highestColumn) - 64;
						$output = array();
						$heading = "";
						$detail = "";

						$output_array = array();
						for ($row = 2; $row <= $highestRow; ++$row) {
							$dep_name_th = '';
							$dep_name_en = '';
							$posi_name_th = '';
							$posi_name_en = '';
							$arr_emp = array();
							$arr_user = array();
							$arr_user_group = array();
							$arr_company = array();
							$arr_department = array();
							$arr_position = array();
							$u_firstdate = '';
							$check_u_firstdate = '';
							$count_usr = 0;

							$data = array(
								'com_id' => $_REQUEST['com_id_import_user']
							);

							for ($col = 0; $col < $highestColumnIndex; ++$col) {

								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
								//

								if ($col == 0) {
									$arr_user['useri'] = strtolower($val);
									$arr_user['useri'] = str_replace(" ", "", $arr_user['useri']);
									$fetch_chk_user = $this->func_query->query_row('lms_usp', '', '', '', 'useri="' . $arr_user['useri'] . '"');
									$count_usr = countArray($fetch_chk_user);
									if ($count_usr == 0) {
										$check_u_firstdate = '';
									} else {
										$check_u_firstdate = $fetch_chk_user['u_firstdate'];
									}
								}
								if ($col == 1) {
									$arr_user_group['ug_name'] = $val;
								}
								if ($col == 2) {
									$arr_company['com_code'] = $val;
								}
								if ($col == 3) {
									$arr_department['dep_name'] = $val;
								}
								if ($col == 4) {
									$arr_position['posi_name'] = $val;
								}
								if ($col == 5) {
									$arr_emp['emp_manage_a'] = str_replace(" ", "", strtolower($val));
								}
								if ($col == 6) {
									$arr_emp['emp_manage_b'] = str_replace(" ", "", strtolower($val));
								}
								//if($col==7){ $arr_emp['emp_c'] = $val; }
								if ($col == 7) {
									$arr_emp['fname_th'] = str_replace(" ", "", $val);
								}
								if ($col == 8) {
									$arr_emp['lname_th'] = str_replace(" ", "", $val);
								}
								if ($col == 9) {
									$arr_emp['fname_en'] = str_replace(" ", "", $val);
								}
								if ($col == 10) {
									$arr_emp['lname_en'] = str_replace(" ", "", $val);
								}
								if ($col == 11) {
									// print_r(date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell->getValue())));
									// print_r($check_u_firstdate);
									// die;
									if ($check_u_firstdate) {
										$u_firstdate = $check_u_firstdate;
									} else {
										$u_firstdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
									}
								}

								if ($col == 12) {
									$arr_user['inactivedate'] = $val != "" ? date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cell->getValue())) : "0000-00-00";
								}
							}
							$message = "";
							if ($arr_user['useri'] == "" && $arr_user['useri'] != "Company's email*") {
								$message = label('error_comp_email');
							}
							if ($arr_company['com_code'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= label('error_notfound_comp');
							}
							$arr_emp['fullname_th'] = $arr_emp['fname_th'] != "" && $arr_emp['lname_th'] != "" ? $arr_emp['fname_th'] . " " . $arr_emp['lname_th'] : "";
							$arr_emp['fullname_en'] = $arr_emp['fname_en'] != "" && $arr_emp['lname_en'] != "" ? $arr_emp['fname_en'] . " " . $arr_emp['lname_en'] : "";
							if ($arr_emp['emp_manage_a'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= label('error_notfound_managea');
							}
							if ($arr_emp['fname_th'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= 'Name TH not found';
							}
							if ($arr_emp['lname_th'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= 'Lastname TH not found';
							}
							if ($arr_emp['fname_en'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= 'Name ENG not found';
							}
							if ($arr_emp['lname_en'] == "") {
								if ($message != "") {
									$message .= ", ";
								}
								$message .= 'Lastname ENG not found';
							}
							if ($arr_user['useri'] != "" && $arr_company['com_code'] != "" && $arr_user['useri'] != "Company's email*" && $arr_emp['emp_manage_a'] != "" && $arr_emp['fname_th'] != "" && $arr_emp['lname_th'] != "" && $arr_emp['fname_en'] != "" && $arr_emp['lname_en'] != "") {
								$arr_emp['email'] = $arr_user['useri'];
								$arr_emp['emp_c'] = $arr_user['useri'];
								$arr_emp['emp_modifiedby'] = $sess['u_id'];
								$arr_emp['emp_modifieddate'] = date('Y-m-d H:i');
								$fetch_chkcompany = $this->func_query->query_row('lms_company', '', '', '', 'com_code="' . $arr_company['com_code'] . '" and com_isDelete="0"');
								$arr_emp['com_id'] = $fetch_chkcompany['com_id'];
								$chkcom = 1;
								if ($sess['ug_id'] != "1") {
									if ($sess['com_id'] == $arr_emp['com_id']) {
										$chkcom = 0;
									}
								}
								if ($chkcom == 0) {
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label('error_comp_notmatch');
								}
								// $com_emaildomain = $fetch_chkcompany['com_emaildomain'];
								$com_emaildomain = explode(",", str_replace(" ", "", $fetch_chkcompany['com_emaildomain']));
								$email = isset($arr_emp['email']) ? explode('@', $arr_emp['email']) : "";
								$chkmatch = 1;
								if (isset($email[1])) {
									// if($email[1]!=$com_emaildomain){
									if (!in_array($email[1], $com_emaildomain)) {
										$chkmatch = 0;
									}
								} else {
									$chkmatch = 0;
								}

								if ($chkmatch == 0) {
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label("error_comp_email_notmatch");
								}

								$dep_name = str_replace(" ", "", $arr_department['dep_name']);
								$posi_name = str_replace(" ", "", $arr_position['posi_name']);
								$fetch_chkdp = $this->func_query->query_row('lms_depart', '', '', '', 'com_id="' . $fetch_chkcompany['com_id'] . '" and (REPLACE(dep_name_th, " ", "")="' . $dep_name . '" or REPLACE(dep_name_en, " ", "")="' . $dep_name . '") and dep_isDelete="0"');
								$dep_id = "";
								$posi_id = "";
								if (countArray($fetch_chkdp) > 0) {
									$dep_id = $fetch_chkdp['dep_id'];
									/*$data_dp = array(
										'dep_status' => '1',
										'dep_modifiedby' => $sess['u_id'],
										'dep_modifieddate' => date('Y-m-d H:i')
									);
									$this->db->where('dep_id',$dep_id);
									$this->db->update('lms_depart',$data_dp);*/

									$fetch_chkdp = $this->func_query->query_row('lms_position', '', '', '', 'dep_id="' . $dep_id . '" and (REPLACE(posi_name_th, " ", "")="' . $posi_name . '" or REPLACE(posi_name_en, " ", "")="' . $posi_name . '") and posi_isDelete="0"');
									if (countArray($fetch_chkdp) > 0) {
										$posi_id = $fetch_chkdp['posi_id'];
										/*$data_dp = array(
											'posi_status' => '1',
											'posi_modifiedby' => $sess['u_id'],
											'posi_modifieddate' => date('Y-m-d H:i')
										);
										$this->db->where('dep_id',$dep_id);
										$this->db->update('lms_position',$data_dp);*/
									} else {
										if ($message != "") {
											$message .= ", ";
										}
										$message .= label("error_notfound_pos");
									}
								} else {
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label("error_notfound_dept");
								}
								/*else{
									$data_dp = array(
										'dep_name_th' => $arr_department['dep_name'],
										'dep_name_en' => $arr_department['dep_name'],
										'com_id' => $fetch_chkcompany['com_id'],
										'dep_createby' => $sess['u_id'],
										'dep_createdate' => date('Y-m-d H:i'),
										'dep_modifiedby' => $sess['u_id'],
										'dep_modifieddate' => date('Y-m-d H:i')
									);
									$this->db->insert('lms_depart',$data_dp);
									$dep_id = $this->db->insert_id();
								}*/
								/*else{
									$data_dp = array(
										'posi_name_th' => $arr_position['posi_name'],
										'posi_name_en' => $arr_position['posi_name'],
										'dep_id' => $dep_id,
										'posi_createby' => $sess['u_id'],
										'posi_createdate' => date('Y-m-d H:i'),
										'posi_modifiedby' => $sess['u_id'],
										'posi_modifieddate' => date('Y-m-d H:i')
									);
									$this->db->insert('lms_position',$data_dp);
									$posi_id = $this->db->insert_id();
								}*/
								$ug_id = "";
								$ug_anme = str_replace(" ", "", $arr_user_group['ug_name']);
								$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_for="' . $fetch_chkcompany['com_admin'] . '" and (REPLACE(ug_name_th, " ", "")="' . $ug_anme . '" or REPLACE(ug_name_en, " ", "")="' . $ug_anme . '") and ug_isDelete="0"');
								if (countArray($fetch_chkug) > 0) {
									$ug_id = $fetch_chkug['ug_id'];
								} else {
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label("error_notfound_role");
								}
								$chk_inactive = 1;
								if ($arr_user['inactivedate'] != "0000-00-00" && date('Y-m-d') > date('Y-m-d', strtotime($arr_user['inactivedate']))) {
									$chk_inactive = 0;
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label("error_usage_enddate");
								}
								$isManagerABDuplicate = 0;
								$arr_emp['emp_manage_a'] = str_replace(" ", "", $arr_emp['emp_manage_a']);
								$arr_emp['emp_manage_b'] = str_replace(" ", "", $arr_emp['emp_manage_b']);
								if ($arr_emp['emp_manage_a'] != "" && $arr_emp['emp_manage_b'] != "" && $arr_emp['emp_manage_a'] == $arr_emp['emp_manage_b']) {
									$isManagerABDuplicate = 1;
									if ($message != "") {
										$message .= ", ";
									}
									$message .= label("manager1_2_duplicate");
								}
								if ($ug_id != "" && $posi_id != "" && $dep_id != "" && $chkmatch == 1 && $chkcom == 1 && $chk_inactive == 1 && $isManagerABDuplicate == 0) {
									$arr_user['dep_id'] = $dep_id;
									$arr_user['posi_id'] = $posi_id;
									$arr_user['ug_id'] = $ug_id;
									$arr_user['u_modifiedby'] = $sess['u_id'];
									$arr_user['u_modifieddate'] = date('Y-m-d H:i');
									$arr_user['u_firstdate'] = $u_firstdate;
									$fetch_chkup = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'useri="' . $arr_user['useri'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"');
									if (countArray($fetch_chkup) == 0) {
										$arr_emp['emp_createby'] = $sess['u_id'];
										$arr_emp['emp_createdate'] = date('Y-m-d H:i');
										$password = $this->generateRandomString();
										$password_enc = hash('sha256', $password);
										$arr_user['userp'] = $password_enc;
										$arr_user['u_createby'] = $sess['u_id'];
										$arr_user['u_createdate'] = date('Y-m-d H:i');
										$this->db->insert('lms_emp', $arr_emp);
										$emp_id = $this->db->insert_id();
										$arr_user['emp_id'] = $emp_id;
										$this->db->insert('lms_usp', $arr_user);
										$u_id = $this->db->insert_id();

										$data = array(
											'u_id' => $u_id,
											'logusp_status' => 'Create',
											'logusp_firstdate' => $u_firstdate,
											'logusp_inactivedate' => $arr_user['inactivedate'],
											'logusp_createby' => $sess['u_id'],
											'logusp_createdate' => date('Y-m-d H:i')
										);
										$this->db->insert('lms_log_updateusp', $data);

										$fetch_chkrole = $this->func_query->query_row('lms_role_usp', '', '', '', 'u_id="' . $u_id . '"');
										if (countArray($fetch_chkrole) == 0) {
											$result_rolegp = $this->func_query->query_result('lms_role_gp', '', '', '', 'ug_id="' . $ug_id . '"');
											$num = 1;
											foreach ($result_rolegp as $key => $value) {
												$data = array(
													'u_id' => $u_id,
													'mu_id' => $value['mu_id'],
													'ru_view' => $value['rgu_view'],
													'ru_add' => $value['rgu_add'],
													'ru_edit' => $value['rgu_edit'],
													'ru_del' => $value['rgu_del'],
													'ru_print' => $value['rgu_print']
												);
												$this->db->insert('lms_role_usp', $data);
											}
										}
										if ($u_id != "") {

											$result_arr['success_count']++;
											
											if ($arr_user['inactivedate'] != "" && $arr_user['inactivedate'] != null && $arr_user['inactivedate'] != "0000-00-00") {
												$this->lg->insertLogImportUser ($lgiId, $emp_id, 3);
											} else {
												$this->lg->insertLogImportUser ($lgiId, $emp_id, 1);
											}
											array_push($result_arr['success_data'], $arr_emp['emp_c']);

											$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
											//if($lang!="thai"){
											$date = date('d F Y');
											//}
											$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
											$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="1"');
											if (countArray($fetch_formatmail) > 0) {
												$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $arr_emp['com_id'] . '"');
												$subject_th = $fetch_formatmail['smf_subject_th'];
												$subject_en = $fetch_formatmail['smf_subject_en'];
												$message_th = $fetch_formatmail['smf_message_th'];
												$message_en = $fetch_formatmail['smf_message_en'];
												if ($subject_th != "") {
													$subject_th = str_replace("#fullname", $arr_emp['fullname_th'], $subject_th);
													$subject_th = str_replace("#username", $arr_user['useri'], $subject_th);
													$subject_th = str_replace("#email", $arr_emp['email'], $subject_th);
													$subject_th = str_replace("#coursename", "", $subject_th);
													$subject_th = str_replace("#password", $password, $subject_th);
													$subject_th = str_replace("#link_frontend", base_url(), $subject_th);
													$subject_th = str_replace("#date", $date, $subject_th);
													$subject_th = str_replace("#time", date('H:i'), $subject_th);
													$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
												}
												if ($subject_en != "") {
													$subject_en = str_replace("#fullname", $arr_emp['fullname_en'], $subject_en);
													$subject_en = str_replace("#username", $arr_user['useri'], $subject_en);
													$subject_en = str_replace("#email", $arr_emp['email'], $subject_en);
													$subject_en = str_replace("#coursename", "", $subject_en);
													$subject_en = str_replace("#password", $password, $subject_en);
													$subject_en = str_replace("#link_frontend", base_url(), $subject_en);
													$subject_en = str_replace("#date", $date, $subject_en);
													$subject_en = str_replace("#time", date('H:i'), $subject_en);
													$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
												}
												if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
													$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
												} else {
													$img_val = '';
												}
												if ($message_th != "") {
													$message_th = str_replace("#fullname", $arr_emp['fullname_th'], $message_th);
													$message_th = str_replace("#username", $arr_user['useri'], $message_th);
													$message_th = str_replace("#email", $arr_emp['email'], $message_th);
													$message_th = str_replace("#coursename", "", $message_th);
													$message_th = str_replace("#password", $password, $message_th);
													$message_th = str_replace("#link_frontend", base_url(), $message_th);
													$message_th = str_replace("#date", $date, $message_th);
													$message_th = str_replace("#time", date('H:i'), $message_th);
													$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
													$message_th = str_replace("#image", $img_val, $message_th);
												}
												if ($message_en != "") {
													$message_en = str_replace("#fullname", $arr_emp['fullname_en'], $message_en);
													$message_en = str_replace("#username", $arr_user['useri'], $message_en);
													$message_en = str_replace("#email", $arr_emp['email'], $message_en);
													$message_en = str_replace("#coursename", "", $message_en);
													$message_en = str_replace("#password", $password, $message_en);
													$message_en = str_replace("#link_frontend", base_url(), $message_en);
													$message_en = str_replace("#date", $date, $message_en);
													$message_en = str_replace("#time", date('H:i'), $message_en);
													$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
													$message_en = str_replace("#image", $img_val, $message_en);
												}
												$lang = "english";
												if ($lang == "thai") {
													$this->db->sendEmail($arr_emp['email'], $message_th, $subject_th, $fetch_setmail);
												} else {
													$this->db->sendEmail($arr_emp['email'], $message_en, $subject_en, $fetch_setmail);
												}
											}
										} else {
											$result_arr['error_count']++;
											array_push($result_arr['line_error'], '2199');
											array_push($result_arr['error_data'], $arr_emp['emp_c']);
										}
									} else {
										$this->db->where('emp_id', $fetch_chkup['emp_id']);
										$this->db->update('lms_emp', $arr_emp);
										$this->db->where('u_id', $fetch_chkup['u_id']);
										$this->db->update('lms_usp', $arr_user);


										$data = array(
											'u_id' => $fetch_chkup['u_id'],
											'logusp_status' => 'Updated',
											'logusp_firstdate' => $u_firstdate,
											'logusp_inactivedate' => $arr_user['inactivedate'],
											'logusp_createby' => $sess['u_id'],
											'logusp_createdate' => date('Y-m-d H:i')
										);
										$this->db->insert('lms_log_updateusp', $data);

										$fetch_chkrole = $this->func_query->query_row('lms_role_usp', '', '', '', 'u_id="' . $fetch_chkup['u_id'] . '"');
										if (countArray($fetch_chkrole) > 0) {
											if ($fetch_chkup['ug_id'] != $ug_id) {
												$this->db->where('u_id', $fetch_chkup['u_id']);
												$this->db->delete('lms_role_usp');
												$result_rolegp = $this->func_query->query_result('lms_role_gp', '', '', '', 'ug_id="' . $ug_id . '"');
												$num = 1;
												foreach ($result_rolegp as $key => $value) {
													$data = array(
														'u_id' => $fetch_chkup['u_id'],
														'mu_id' => $value['mu_id'],
														'ru_view' => $value['rgu_view'],
														'ru_add' => $value['rgu_add'],
														'ru_edit' => $value['rgu_edit'],
														'ru_del' => $value['rgu_del'],
														'ru_print' => $value['rgu_print']
													);
													$this->db->insert('lms_role_usp', $data);
												}
											}
										}
										$result_arr['duplicate_count']++;
										array_push($result_arr['duplicate_data'], $arr_emp['emp_c']);
										if ($arr_user['inactivedate'] != "" && $arr_user['inactivedate'] != null && $arr_user['inactivedate'] != "0000-00-00") {
											$this->lg->insertLogImportUser ($lgiId, $fetch_chkup['emp_id'], 3);
										} else {
											$this->lg->insertLogImportUser ($lgiId, $fetch_chkup['emp_id'], 2);
										}
									}
								} else {
									$result_arr['error_count']++;
									array_push($result_arr['line_error'], '2213');
									array_push($result_arr['error_data'], $arr_emp['emp_c'] . " (" . $message . ")");
								}
							} else if (isset($arr_user['useri'])) {
								$result_arr['error_count']++;
								array_push($result_arr['line_error'], '3305');
								array_push($result_arr['error_data'], " (" . $message . ")");
							}
							//end user
						}
						/*$head = "";
						foreach ($output as $key => $value) {
							if(is_array($value)){
								foreach ($value as $key => $value_b) {
									if($head!=""){
										$head_arr = explode(';', $head);
										$value_arr = explode(';', $value_b);
										$this->questionnaire->insertQuestionnaireDetail($id,$head_arr[0],$value_arr[0],$head_arr[1],$value_arr[1]);
									}
								}
							}else{
								$head = $value;
							}
						}*/
					}

					$result_str = "";
					$result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
					if (countArray($result_arr['success_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
							if ($result_arr['success_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
					if (countArray($result_arr['duplicate_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
							if ($result_arr['duplicate_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
					if (countArray($result_arr['error_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
							if ($result_arr['error_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><br>";
					}
					$fetch_user_all = $this->func_query->query_result('lms_emp', '', '', '', '(emp_manage_a!="" or emp_manage_b!="") and emp_isDelete="0"', '', 'emp_manage_a,emp_manage_b');
					if (countArray($fetch_user_all) > 0) {
						$arr_update = array(
							'is_manager' => "0"
						);
						$this->db->update('lms_emp', $arr_update);
						foreach ($fetch_user_all as $key_userall => $value_userall) {
							if ($value_userall['emp_manage_a'] != "") {
								$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $value_userall['emp_manage_a'] . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
								if (countArray($fetch_chkemp) > 0) {
									$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_chkemp['emp_id'] . '"');
									if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
										$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
										if (countArray($fetch_chkug) > 0) {
											$arr_update_ug = array(
												'ug_id' => $fetch_chkug['ug_id']
											);
											$this->db->where('emp_id', $fetch_chkemp['emp_id']);
											$this->db->update('lms_usp', $arr_update_ug);
											$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
										}
									}
									if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_associated") {
										$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_associated" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
										if (countArray($fetch_chkug) > 0) {
											$arr_update_ug = array(
												'ug_id' => $fetch_chkug['ug_id']
											);
											$this->db->where('emp_id', $fetch_chkemp['emp_id']);
											$this->db->update('lms_usp', $arr_update_ug);
											$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
										}
									}
									$arr_update = array(
										'is_manager' => "1"
									);
									$this->db->where('emp_id', $fetch_chkemp['emp_id']);
									$this->db->update('lms_emp', $arr_update);
								}
							}
							if ($value_userall['emp_manage_b'] != "") {
								$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $value_userall['emp_manage_b'] . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
								if (countArray($fetch_chkemp) > 0) {
									$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_chkemp['emp_id'] . '"');
									if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
										$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
										if (countArray($fetch_chkug) > 0) {
											$arr_update_ug = array(
												'ug_id' => $fetch_chkug['ug_id']
											);
											$this->db->where('emp_id', $fetch_chkemp['emp_id']);
											$this->db->update('lms_usp', $arr_update_ug);
											$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
										}
									}
									if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_associated") {
										$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_associated" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
										if (countArray($fetch_chkug) > 0) {
											$arr_update_ug = array(
												'ug_id' => $fetch_chkug['ug_id']
											);
											$this->db->where('emp_id', $fetch_chkemp['emp_id']);
											$this->db->update('lms_usp', $arr_update_ug);
											$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
										}
									}
									$arr_update = array(
										'is_manager' => "1"
									);
									$this->db->where('emp_id', $fetch_chkemp['emp_id']);
									$this->db->update('lms_emp', $arr_update);
								}
							}
						}
					}

					if ($lgiId != "") {
						$fetchLGImportUser = $this->func_query->query_row(
							"lms_lg_import_detail", "", "", "",
							"lgi_id = ".$lgiId, "",
							"SUM(CASE WHEN lgid_status=1 THEN 1 ELSE 0 END) as statusNew,SUM(CASE WHEN lgid_status=2 THEN 1 ELSE 0 END) as statusDuplicate,SUM(CASE WHEN lgid_status=3 THEN 1 ELSE 0 END) as statusRemove"
						);
						if (isset($fetchLGImportUser["statusNew"])) {
							$this->db->where("lgi_id", $lgiId);
							$this->db->update("lms_lg_import", array(
								"lgi_new_user" 			=> $fetchLGImportUser["statusNew"],
								"lgi_duplicate_user" 	=> $fetchLGImportUser["statusDuplicate"],
								"lgi_remove_user" 		=> $fetchLGImportUser["statusRemove"]
							));
						}
					}
					$result_arr['status'] = "2";
					$result_arr['result'] = $result_str;
				} else {
					$result_arr['status'] = "0";
				}
			}
		} else {
			$result_arr['status'] = "0";
		}
		$this->lg->record('Setting', 'Import User By ' . $sess['fullname_th']);
		echo json_encode($result_arr);
	}

	public function query_updateleanermanager()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata('user');
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Manage_model', 'manage', true);
		$this->load->model('Log_model', 'lg', false);
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Function_query_model', 'func_query', true);
		$this->setting->loadDB();

		$numrow_update = 0;
		$fetch_user_all = $this->func_query->query_result('lms_emp', '', '', '', '(emp_manage_a!="" or emp_manage_b!="") and emp_isDelete="0"', '', 'emp_manage_a,emp_manage_b');
		if (countArray($fetch_user_all) > 0) {
			$arr_update = array(
				'is_manager' => "0"
			);
			$this->db->update('lms_emp', $arr_update);
			foreach ($fetch_user_all as $key_userall => $value_userall) {
				if ($value_userall['emp_manage_a'] != "") {
					$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $value_userall['emp_manage_a'] . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
					if (countArray($fetch_chkemp) > 0) {
						$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_chkemp['emp_id'] . '"');
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_chkemp['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_associated") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_associated" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_chkemp['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						$numrow_update++;
						$arr_update = array(
							'is_manager' => "1"
						);
						$this->db->where('emp_id', $fetch_chkemp['emp_id']);
						$this->db->update('lms_emp', $arr_update);
					}
				}
				if ($value_userall['emp_manage_b'] != "") {
					$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $value_userall['emp_manage_b'] . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
					if (countArray($fetch_chkemp) > 0) {
						$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_chkemp['emp_id'] . '"');
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_chkemp['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_associated") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_associated" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_chkemp['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						$numrow_update++;
						$arr_update = array(
							'is_manager' => "1"
						);
						$this->db->where('emp_id', $fetch_chkemp['emp_id']);
						$this->db->update('lms_emp', $arr_update);
					}
				}
			}
		}
		echo $numrow_update;
	}

	public function import_departandposi()
	{
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata('user');
		$this->load->model('Setting_model', 'setting', true);
		$this->load->model('Manage_model', 'manage', true);
		$this->load->model('Log_model', 'lg', false);
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Function_query_model', 'func_query', true);
		$this->setting->loadDB();
		$msg = "2";

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$result_arr = array();
		$result_arr['success_count'] = 0;
		$result_arr['duplicate_count'] = 0;
		$result_arr['error_count'] = 0;
		$result_arr['line_error'] = array();
		$result_arr['success_data'] = array();
		$result_arr['duplicate_data'] = array();
		$result_arr['error_data'] = array();
		if (countArray($_REQUEST) > 0) {

			if ($_REQUEST['operation_import_user'] == "Add") {
				$excel_file = $_FILES["file_import"]["name"];
				//$data['file_import'] = $excel_file;

				$imageSourcePath = $_FILES['file_import']['tmp_name'];
				$pathBG = $_FILES['file_import']['name'];
				$array_pathext = explode('.', $pathBG);
				$extension = end($array_pathext);
				$file_import = "importxlsxdepartposi_" . date('YmdHis') . "." . $extension;
				$imageTargetPath = ROOT_DIR . "uploads/excel/" . $file_import;
				if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
					$path = './uploads/excel/' . $file_import;
					$objPHPExcel = PHPExcel_IOFactory::load($path);
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						//$nrColumns = ord($highestColumn) - 64;
						$output = array();
						$heading = "";
						$detail = "";

						$output_array = array();
						for ($row = 2; $row <= $highestRow; ++$row) {
							$arr_depart = array();
							$arr_posi = array();
							$company_code = "";

							for ($col = 0; $col < $highestColumnIndex; ++$col) {
								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
								if ($col == 0) {
									$company_code = $val;
								}
								if ($col == 1) {
									$arr_depart['dep_name_en'] = $val;
								}
								if ($col == 2) {
									$arr_depart['dep_name_th'] = $val;
								}
								if ($col == 3) {
									$arr_posi['posi_name_en'] = $val;
								}
								if ($col == 4) {
									$arr_posi['posi_name_th'] = $val;
								}
							}

							$message = "";
							if ($company_code == "") {
								$message .= label('error_comp_notmatch');
							}
							if ($arr_depart['dep_name_th'] == "" || $arr_depart['dep_name_en'] == "") {
								$message .= label('error_notfound_dept');
							}
							if ($arr_posi['posi_name_th'] == "" || $arr_posi['posi_name_en'] == "") {
								$message .= label('error_notfound_pos');
							}
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_code="' . $company_code . '" and com_isDelete="0"');
							if ($company_code != "" && countArray($fetch_company) > 0) {

								$fetch_chkdp = $this->func_query->query_row('lms_depart', '', '', '', 'com_id="' . $fetch_company['com_id'] . '" and (dep_name_th="' . $arr_depart['dep_name_th'] . '" and dep_name_en="' . $arr_depart['dep_name_en'] . '") and dep_isDelete="0"');
								$dep_id = "";
								$posi_id = "";
								if (countArray($fetch_chkdp) > 0) {
									$dep_id = $fetch_chkdp['dep_id'];
									$data_dp = array(
										'dep_name_th' => $arr_depart['dep_name_th'],
										'dep_name_en' => $arr_depart['dep_name_en'],
										'dep_modifiedby' => $sess['u_id'],
										'dep_modifieddate' => date('Y-m-d H:i')
									);
									$this->db->where('dep_id', $dep_id);
									$this->db->update('lms_depart', $data_dp);
								} else {
									$data_dp = array(
										'dep_name_th' => $arr_depart['dep_name_th'],
										'dep_name_en' => $arr_depart['dep_name_en'],
										'com_id' => $fetch_company['com_id'],
										'dep_createby' => $sess['u_id'],
										'dep_createdate' => date('Y-m-d H:i'),
										'dep_modifiedby' => $sess['u_id'],
										'dep_modifieddate' => date('Y-m-d H:i')
									);
									$this->db->insert('lms_depart', $data_dp);
									$dep_id = $this->db->insert_id();
								}

								if ($arr_posi['posi_name_th'] != "" && $arr_posi['posi_name_en'] != "") {
									$fetch_chkdp = $this->func_query->query_row('lms_position', '', '', '', 'dep_id="' . $dep_id . '" and (posi_name_th="' . $arr_posi['posi_name_th'] . '" and posi_name_en="' . $arr_posi['posi_name_en'] . '") and posi_isDelete="0"');
									if (countArray($fetch_chkdp) > 0) {
										$posi_id = $fetch_chkdp['posi_id'];
										$data_dp = array(
											'posi_name_th' => $arr_posi['posi_name_th'],
											'posi_name_en' => $arr_posi['posi_name_en'],
											'dep_id' => $dep_id,
											'posi_modifiedby' => $sess['u_id'],
											'posi_modifieddate' => date('Y-m-d H:i')
										);
										$this->db->where('posi_id', $posi_id);
										$this->db->update('lms_position', $data_dp);
										$result_arr['duplicate_count']++;
										if ($lang == "thai") {
											array_push($result_arr['duplicate_data'], $company_code . " " . $arr_depart['dep_name_th'] . " " . $arr_posi['posi_name_th']);
										} else {
											array_push($result_arr['duplicate_data'], $company_code . " " . $arr_depart['dep_name_en'] . " " . $arr_posi['posi_name_en']);
										}
									} else {
										$data_dp = array(
											'posi_name_th' => $arr_posi['posi_name_th'],
											'posi_name_en' => $arr_posi['posi_name_en'],
											'dep_id' => $dep_id,
											'posi_createby' => $sess['u_id'],
											'posi_createdate' => date('Y-m-d H:i'),
											'posi_modifiedby' => $sess['u_id'],
											'posi_modifieddate' => date('Y-m-d H:i')
										);
										$this->db->insert('lms_position', $data_dp);
										$id = $this->db->insert_id();
										$this->manage->insertPositionToCourse($dep_id, $id);
										$result_arr['success_count']++;
										if ($lang == "thai") {
											array_push($result_arr['success_data'], $company_code . " " . $arr_depart['dep_name_th'] . " " . $arr_posi['posi_name_th']);
										} else {
											array_push($result_arr['success_data'], $company_code . " " . $arr_depart['dep_name_en'] . " " . $arr_posi['posi_name_en']);
										}
									}
								}
							} else {
								$result_arr['error_count']++;
								array_push($result_arr['line_error'], '2909');
								if ($lang == "thai") {
									array_push($result_arr['error_data'], $company_code . " " . $arr_depart['dep_name_th'] . " " . $arr_posi['posi_name_th'] . " (" . $message . ")");
								} else {
									array_push($result_arr['error_data'], $company_code . " " . $arr_depart['dep_name_en'] . " " . $arr_posi['posi_name_en'] . " (" . $message . ")");
								}
							}
							//end user
						}
					}

					$result_str = "";
					$result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
					if (countArray($result_arr['success_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
							if ($result_arr['success_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
					if (countArray($result_arr['duplicate_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
							if ($result_arr['duplicate_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><hr><br>";
					}
					$result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
					if (countArray($result_arr['error_data']) > 0) {
						$result_str .= "<ol>";
						for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
							if ($result_arr['error_data'][$i] != "") {
								$result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
							}
						}
						$result_str .= "</ol><br>";
					}
					$result_arr['status'] = "2";
					$result_arr['result'] = $result_str;
				} else {
					$result_arr['status'] = "0";
				}
			}
		} else {
			$result_arr['status'] = "0";
		}
		$this->lg->record('Setting', 'Import Department & Position By ' . $sess['fullname_th']);
		echo json_encode($result_arr);
	}
}
