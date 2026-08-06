<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage extends CI_Controller
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
	public function __construct(){
		parent::__construct();
		
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function index()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage";

		redirect(base_url() . 'manage/manageUser', 'refresh');
	}

	public function companydata()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage/companydata";

		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");

			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			if ($lang == "thai") {
				$arr['com_name'] = $sess['com_name_th'];
			} else {
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();


			$this->load->model('Manage_model', 'manage', false);
			$this->manage->loadDB();
			$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
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
			//$arr['data_fetch'] = $this->manage->fetch_data_company();
			$this->manage->closeDB();
			$this->load->view('frontend/managecompany', $arr);
		} else {
			redirect(base_url() . 'dashboard/login');
		}
	}

	public function departmentdata()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage/departmentdata";

		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");

			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			if ($lang == "thai") {
				$arr['com_name'] = $sess['com_name_th'];
			} else {
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->model('Manage_model', 'manage', false);
			$this->manage->loadDB();
			$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
			$arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');
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
			$arr['data_fetch'] = $this->manage->fetch_data_department();
			$arr['company_select'] = $this->manage->getCompany();
			$this->manage->closeDB();
			$this->load->view('frontend/managedepartment', $arr);
		} else {
			redirect(base_url() . 'dashboard/login');
		}
	}


	public function groupuserdata()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage/groupuserdata";

		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");

			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			if ($lang == "thai") {
				$arr['com_name'] = $sess['com_name_th'];
			} else {
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->model('Manage_model', 'manage', false);
			$this->manage->loadDB();
			$arr['total_menu'] = $this->manage->arr_menu_query();
			$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
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
			$arr['data_fetch'] = $this->manage->fetch_data_groupuser();
			$this->manage->closeDB();
			$this->load->view('frontend/managegroupuser', $arr);
		} else {
			redirect(base_url() . 'dashboard/login');
		}
	}


	public function userdata()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage/userdata";

		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");

			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			if ($lang == "thai") {
				$arr['com_name'] = $sess['com_name_th'];
			} else {
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->model('Manage_model', 'manage', false);
			$this->load->model('Function_query_model', 'func_query', false);
			$this->manage->loadDB();
			$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
			$arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');
			if ($arr['btn_view'] != "1") {
				redirect(base_url() . 'dashboard');
			}
			$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
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
			$arr['company_select'] = $this->manage->getCompany();
			$this->manage->closeDB();
			$this->load->view('frontend/manageusers', $arr);
		} else {
			redirect(base_url() . 'dashboard/login');
		}
	}

	public function fetch_detail_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_user($_REQUEST['com_id']) : array();
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

	public function fetch_detail_userenroll()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_userenroll($_REQUEST['emp_id']) : array();
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

	public function fetch_detail_learnerincomplete()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_learnerincomplete() : array();
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

	public function fetch_detail_qrcode()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_qrcode($_REQUEST['com_id']) : array();
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

	public function fetch_detail_usergroup()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_groupuser() : array();
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

	public function fetch_detail_company()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_company() : array();
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

	public function fetch_detail_conmsg()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_conmsg() : array();
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

	public function fetch_detail_department()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_department() : array();
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

	public function fetch_detail_position($dep_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_position_detail($dep_id) : array();
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

	public function fetch_log()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : "";
		$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : "";
		$length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : "";
		$order = $this->input->post("order");
		$search = $this->input->post("search");
		$search = isset($search['value']);
		$time_start = isset($_REQUEST['time_start']) ? $_REQUEST['time_start'] : '';
		$time_end = isset($_REQUEST['time_end']) ? $_REQUEST['time_end'] : '';
		$date_start = isset($_REQUEST['date_start']) && $_REQUEST['date_start'] != "" ? $_REQUEST['date_start'] . " " . $time_start : "0000-00-00 00:00:00";
		$date_end = isset($_REQUEST['date_end']) && $_REQUEST['date_end'] != "" ? $_REQUEST['date_end'] . " " . $time_end : "0000-00-00 00:00:00";
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->manage->fetch_data_log($date_start, $date_end, $com_id, $length, $start, $order, $search) : array();
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

	public function loaddetailgroupuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$ug_id = $_REQUEST['ug_id'];
			$data_resend = $this->manage->chkdataRoleUsergroup($ug_id);
			echo $data_resend;
		}
	}

	public function loaddetailuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$u_id = $_REQUEST['u_id'];
			$data_resend = $this->manage->chkdataRoleUser($u_id);
			echo $data_resend;
		}
	}
	public function chk_chkbox_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->manage->chkbox_user($_REQUEST);
		}
		echo $msg;
	}
	public function chk_chkbox_groupuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->manage->chkbox_groupuser($_REQUEST);
		}
		echo $msg;
	}

	public function chk_chkboxcol_groupuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->manage->chkbox_col_groupuser($_REQUEST);
		}
		echo $msg;
	}
	public function chk_chkboxcol_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$msg = $this->manage->chkbox_col_user($_REQUEST);
		}
		echo $msg;
	}

	public function insert_company()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'com_code' => $_REQUEST['com_code'],
				'com_name_th' => $_REQUEST['com_name_th'],
				'com_name_eng' => $_REQUEST['com_name_eng'],
				'com_add_th' => $_REQUEST['com_add_th'],
				'com_add_eng' => $_REQUEST['com_add_eng'],
				'com_emaildomain' => $_REQUEST['com_emaildomain'],
				'com_tel' => $_REQUEST['com_tel'],
				'com_fax' => $_REQUEST['com_fax'],
				'com_mail' => $_REQUEST['com_mail'],
				'com_admin' => $_REQUEST['com_admin'],
				'com_wctitle_th' => $_REQUEST['com_wctitle_th'],
				'com_wcmessage_th' => $_REQUEST['com_wcmessage_th'],
				'com_wctitle_eng' => $_REQUEST['com_wctitle_eng'],
				'com_wcmessage_eng' => $_REQUEST['com_wcmessage_eng'],
				'com_wctitle_jp' => $_REQUEST['com_wctitle_jp'],
				'com_wcmessage_jp' => $_REQUEST['com_wcmessage_jp'],
				'com_modifiedby' => $sess['u_id'],
				'com_modifieddate' => date('Y-m-d H:i')
			);
			if (isset($_FILES['com_logo_top']) && $_FILES['com_logo_top'] != "") {
				if (isset($_FILES['com_logo_top'])) {
					$imageSourcePath = $_FILES['com_logo_top']['tmp_name'];
					$pathBG = $_FILES['com_logo_top']['name'];
					if ($pathBG != "") {
						$array_pathext = explode('.', $pathBG);
						$extension = end($array_pathext);
						$com_logo_top = "logoTop_" . date('YmdHis') . "." . $extension;
						$imageTargetPath = ROOT_DIR . "uploads/logo/" . $com_logo_top;
						if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
							if ($_REQUEST['operation'] == "Edit") {
								$fetch_m = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $_REQUEST['com_id'] . '"');
								if (countArray($fetch_m) > 0 && $fetch_m['com_logo_top'] != "") {
									if (is_file(ROOT_DIR . "uploads/logo/" . $fetch_m['com_logo_top'])) {
										audit_unlink(ROOT_DIR . "uploads/logo/" . $fetch_m['com_logo_top']);
									}
								}
							}
							$data['com_logo_top'] = $com_logo_top;
						}
					}
				}
			}
			if (isset($_FILES['com_logo_footer']) && $_FILES['com_logo_footer'] != "") {
				if (isset($_FILES['com_logo_footer'])) {
					$imageSourcePath = $_FILES['com_logo_footer']['tmp_name'];
					$pathBG = $_FILES['com_logo_footer']['name'];
					if ($pathBG != "") {
						$array_pathext = explode('.', $pathBG);
						$extension = end($array_pathext);
						$com_logo_footer = "logoFooter_" . date('YmdHis') . "." . $extension;
						$imageTargetPath = ROOT_DIR . "uploads/logo/" . $com_logo_footer;
						if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
							if ($_REQUEST['operation'] == "Edit") {
								$fetch_m = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $_REQUEST['com_id'] . '"');
								if (countArray($fetch_m) > 0 && $fetch_m['com_logo_footer'] != "") {
									if (is_file(ROOT_DIR . "uploads/logo/" . $fetch_m['com_logo_footer'])) {
										audit_unlink(ROOT_DIR . "uploads/logo/" . $fetch_m['com_logo_footer']);
									}
								}
							}
							$data['com_logo_footer'] = $com_logo_footer;
						}
					}
				}
			}
			if ($_REQUEST['operation'] == "Add") {
				$data['com_createby'] = $sess['u_id'];
				$data['com_createdate'] = date('Y-m-d H:i');
				$msg = $this->manage->create_company($data);
			} else {
				$data['com_logo_top'] = isset($data['com_logo_top']) && $data['com_logo_top'] != "" ? $data['com_logo_top'] : $_REQUEST['com_logo_top_ori'];
				$data['com_logo_footer'] = isset($data['com_logo_footer']) && $data['com_logo_footer'] != "" ? $data['com_logo_footer'] : $_REQUEST['com_logo_footer_ori'];
				$msg = $this->manage->update_company($data, $_REQUEST['com_id']);
			}
		}
		echo $msg;
	}

	public function insert_position_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		date_default_timezone_set("Asia/Bangkok");
		$emp_c = $sess['emp_c'];
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'dep_id' => $_REQUEST['dep_id_position'],
				'posi_name_th' => $_REQUEST['posi_name_th'],
				'posi_name_en' => $_REQUEST['posi_name_en'],
				'posi_remark' => $_REQUEST['posi_remark'],
				'posi_modifiedby' => $sess['u_id'],
				'posi_modifieddate' => date('Y-m-d H:i')
			);
			if ($_REQUEST['operation_position'] == "Add") {
				$data['posi_createby'] = $sess['u_id'];
				$data['posi_createdate'] = date('Y-m-d H:i');
				$msg = $this->manage->create_position_detail($data);
			} else {
				$msg = $this->manage->update_position_detail($data, $_REQUEST['posi_id']);
			}
		}
		echo $msg;
	}

	public function insert_conmsg()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		date_default_timezone_set("Asia/Bangkok");
		$emp_c = $sess['emp_c'];
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$conmsg_status = isset($_REQUEST['conmsg_status']) ? $_REQUEST['conmsg_status'] : "0";
			$data = array(
				'conmsg_title_th' => $_REQUEST['conmsg_title_th'],
				'conmsg_title_eng' => $_REQUEST['conmsg_title_eng'],
				'conmsg_title_jp' => $_REQUEST['conmsg_title_jp'],
				'conmsg_modifiedby' => $sess['u_id'],
				'conmsg_status' => $conmsg_status,
				'conmsg_modifieddate' => date('Y-m-d H:i')
			);
			if ($_REQUEST['operation'] == "Add") {
				$data['conmsg_createby'] = $sess['u_id'];
				$data['conmsg_createdate'] = date('Y-m-d H:i');
				$msg = $this->manage->create_conmsg($data);
			} else {
				$msg = $this->manage->update_conmsg($data, $_REQUEST['conmsg_id']);
			}
		}
		echo $msg;
	}


	public function insert_qrcode()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		date_default_timezone_set("Asia/Bangkok");
		$emp_c = $sess['emp_c'];
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$qr_id = isset($_REQUEST['qr_id']) ? $_REQUEST['qr_id'] : "";
			$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : "";
			$qr_status = isset($_REQUEST['qr_status']) ? $_REQUEST['qr_status'] : "0";

			$data = array(
				'qr_name' => $_REQUEST['qr_name'],
				'qr_type' => $_REQUEST['qr_type'],
				'com_id' => $_REQUEST['com_id'],
				'qr_status' => $qr_status,
				'qr_detail' => $_REQUEST['qr_detail'],
				'qr_modifiedby' => $sess['u_id'],
				'qr_modifieddate' => date('Y-m-d H:i')
			);

			if (isset($_FILES['qr_path']) && $_FILES['qr_path'] != "") {
				if (isset($_FILES['qr_path'])) {
					$imageSourcePath = $_FILES['qr_path']['tmp_name'];
					$path_parts = pathinfo($_FILES['qr_path']['name']);
					if (isset($path_parts['extension'])) {
						$qr_path = "qr_path_" . date('YmdHis') . "." . $path_parts['extension'];

						$imageTargetPath = ROOT_DIR . "uploads/file_forqrcode/" . $qr_path;
						if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
							$data['qr_path'] = $qr_path;
							if ($operation == "Edit") {
								$fetch_chk = $this->func_query->query_row('lms_qrcode', '', '', '', 'qr_id="' . $qr_id . '"');
								if ($fetch_chk['qr_path'] != "") {
									if (is_file(ROOT_DIR . "uploads/file_forqrcode/" . $fetch_chk['qr_path'])) {
										audit_unlink(ROOT_DIR . "uploads/file_forqrcode/" . $fetch_chk['qr_path']);
									}
								}
							}
						}
					}
				}
			}
			if ($_REQUEST['operation'] == "Add") {
				$data['qr_createby'] = $sess['u_id'];
				$data['qr_createdate'] = date('Y-m-d H:i');
				$msg = $this->manage->create_qrcode_detail($data);
			} else {
				$msg = $this->manage->update_qrcode_detail($data, $qr_id);
			}
		}
		echo $msg;
	}

	public function delete_qrcode_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		date_default_timezone_set("Asia/Bangkok");
		$emp_c = $sess['emp_c'];
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$fetch_chk = $this->func_query->query_row('lms_qrcode', '', '', '', 'qr_id="' . $_REQUEST['qr_id_delete'] . '"');
			if ($fetch_chk['qr_path'] != "") {
				if (is_file(ROOT_DIR . "uploads/file_forqrcode/" . $fetch_chk['qr_path'])) {
					audit_unlink(ROOT_DIR . "uploads/file_forqrcode/" . $fetch_chk['qr_path']);
				}
			}
			$data = array(
				'qr_isDelete' => '1',
				'qr_modifiedby' => $sess['u_id'],
				'qr_modifieddate' => date('Y-m-d H:i')
			);
			$msg = $this->manage->update_qrcode_detail($data, $_REQUEST['qr_id_delete']);
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

	public function insert_department()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'dep_name_th' => $_REQUEST['dep_name_th'],
				'dep_name_en' => $_REQUEST['dep_name_en'],
				'com_id' => $_REQUEST['com_id'],
				'dep_remark' => $_REQUEST['dep_remark'],
				'dep_modifiedby' => $sess['u_id'],
				'dep_modifieddate' => date('Y-m-d H:i')
			);
			if ($_REQUEST['operation'] == "Add") {
				$data['dep_createby'] = $sess['u_id'];
				$data['dep_createdate'] = date('Y-m-d H:i');
				$msg = $this->manage->create_department($data);
			} else {
				$msg = $this->manage->update_department($data, $_REQUEST['dep_id']);
			}
		}
		echo $msg;
	}


	public function insert_groupuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$Is_admin = isset($_REQUEST['Is_admin']) ? $_REQUEST['Is_admin'] : '0';
			$ug_approve = isset($_REQUEST['ug_approve']) ? $_REQUEST['ug_approve'] : '0';
			$fd_id = isset($_REQUEST['fd_id']) ? $_REQUEST['fd_id'] : '';
			$data = array(
				'ug_name_th' => $_REQUEST['ug_name_th'],
				'ug_name_en' => $_REQUEST['ug_name_en'],
				'ug_viewdata' => $_REQUEST['ug_viewdata'],
				'ug_for' => $_REQUEST['ug_for'],
				'Is_admin' => $Is_admin,
				'ug_approve' => $ug_approve
			);
			if ($_REQUEST['operation'] == "Add") {
				$msg = $this->manage->create_groupuser($data, $fd_id);
			} else {

				if (countArray($fd_id) > 0) {
					$this->db->where('ug_id', $_REQUEST['ug_id']);
					$this->db->delete('lms_role_fd');
					for ($i = 0; $i < countArray($fd_id); $i++) {
						$arr_insert = array(
							'ug_id' => $_REQUEST['ug_id'],
							'fd_id' => $fd_id[$i],
						);
						$this->db->insert('lms_role_fd', $arr_insert);
					}
				}
				$msg = $this->manage->update_groupuser($data, $_REQUEST['ug_id']);
			}
		}
		echo $msg;
	}

	public function update_profile()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$datas = $_REQUEST;
			$data_user = array();
			if (isset($_FILES['img_profile']) && (int) $_FILES['img_profile']['error'] !== UPLOAD_ERR_NO_FILE) {
				$profileUpload = $_FILES['img_profile'];
				$profileImageInfo = $profileUpload['error'] === UPLOAD_ERR_OK
					? @getimagesize($profileUpload['tmp_name'])
					: false;
				$allowedProfileTypes = array(
					'image/jpeg' => 'jpg',
					'image/png' => 'png',
					'image/gif' => 'gif'
				);

				if (!$profileImageInfo || !isset($allowedProfileTypes[$profileImageInfo['mime']])) {
					http_response_code(422);
					echo 'upload_error';
					return;
				}

				$profileDirectory = ROOT_DIR . 'uploads/profile/';
				if (!is_dir($profileDirectory) && !mkdir($profileDirectory, DIR_WRITE_MODE, true)) {
					http_response_code(500);
					echo 'upload_directory_error';
					return;
				}

				$profileFilename = $_REQUEST['u_id'] . '_' . date('YmdHis') . '.' . $allowedProfileTypes[$profileImageInfo['mime']];
				$imageTargetPath = $profileDirectory . $profileFilename;
				if (!audit_move_uploaded_file($profileUpload['tmp_name'], $imageTargetPath)) {
					http_response_code(500);
					echo 'upload_save_error';
					return;
				}

				$data_user['img_profile'] = $profileFilename;
			}
			if (isset($_FILES['bgpic_user']) && $_FILES['bgpic_user'] != "") {
				if (isset($_FILES['bgpic_user'])) {
					$imageSourcePath = $_FILES['bgpic_user']['tmp_name'];
					$imageTargetPath = ROOT_DIR . "uploads/bg_user/" . $_REQUEST['u_id'] . "_" . date('YmdHis') . ".jpg";
					if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
						$data_user['bgpic_user'] = $_REQUEST['u_id'] . "_" . date('YmdHis') . ".jpg";
					}
				}
			}
			$data = array(
				'prefix_th' => $datas['prefix_th'],
				'fname_th' => $datas['fname_th'],
				'lname_th' => $datas['lname_th'],
				'fullname_th' => $datas['prefix_th'] . $datas['fname_th'] . " " . $datas['lname_th'],
				'prefix_en' => $datas['prefix_en'],
				'fname_en' => $datas['fname_en'],
				'lname_en' => $datas['lname_en'],
				'fullname_en' => $datas['prefix_en'] . $datas['fname_en'] . " " . $datas['lname_en'],
				'address_th' => $datas['address_th'],
				'address_en' => $datas['address_en'],
				'work_phone' => $datas['work_phone'],
				'phone' => $datas['phone'],
				'email' => $datas['email'],
				'emp_modifiedby' => $sess['u_id'],
				'emp_modifieddate' => date('Y-m-d H:i')
			);

			$msg = $this->manage->update_emp($data, $_REQUEST['emp_id']);
			if ($msg == "2") {
				date_default_timezone_set("Asia/Bangkok");
				if (countArray($data_user) > 0) {
					$this->db->where('u_id', $_REQUEST['u_id']);
					$this->db->update('lms_usp', $data_user);
				}

				$this->db->from('lms_usp');
				$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
				$this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id');
				$this->db->join('lms_company', 'lms_depart.com_id = lms_company.com_id');
				$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
				$this->db->join('lms_position', 'lms_usp.posi_id = lms_position.posi_id');
				$this->db->where('lms_usp.u_id', $_REQUEST['u_id']);
				$query = $this->db->get();
				$result = $query->row_array();
				$session_data = $result;
				$this->session->set_userdata('user', $session_data);

				/*if($sess['Is_admin']!="0"){
				        $this->db->where('com_id', $_REQUEST['com_id']);
				        $this->db->update('lms_company', $data_com);
				    }*/
			}
			echo "2";
		}
	}


	private function sendEmail($email, $message)
	{
		require_once 'class/class.simple_mail.php';
		/*$mail_to = $aemail;*/

		$mail = new SimpleMail();
		//$mail->SMTPAuth = false;
		// SMTP server
		//$mail->Host = "172.20.102.105";

		// set the SMTP port for the outMail server
		// use either 25, 587, 2525 or 8025
		//$mail->Port = 25;

		$mail->setTo($email, '')
			->setFrom(SERVER_EMAIL, 'no-reply@verztec.com')
			->setSubject('Verztec E-Learning Auto E-mail')
			->addGenericHeader('MIME-Version', '1.0')
			->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
			->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
			->setMessage($message);
		$mail->send();
	}

	public function generateRandomString($length = 8)
	{
		return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
	}

	public function insert_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		//print_r($_REQUEST);
		$status_save = "";
		if (countArray($_REQUEST) > 0) {
			$datas = $_REQUEST;
			$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $_REQUEST['com_id'] . '"');
			$com_emaildomain = explode(",", str_replace(" ", "", $fetch_company['com_emaildomain']));
			$email = isset($_REQUEST['email']) ? explode('@', $_REQUEST['email']) : "";
			$chkmatch = 1;
			if (isset($email[1])) {
				// $email[1]!=$com_emaildomain
				if (!in_array($email[1], $com_emaildomain)) {
					$chkmatch = 0;
				}
			} else {
				$chkmatch = 0;
			}
			// $com_emaildomain==""
			if (countArray($com_emaildomain) == 0 && $_REQUEST['email'] == "") {
				$chkmatch = 0;
			}
			if ($chkmatch == 1) {
				include ROOT_DIR . "assets/plugins/phpqrcode/qrlib.php";
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 6;
				$filename = ROOT_DIR . "uploads/qrcode/" . $_REQUEST['emp_c'] . ".png";
				QRcode::png($_REQUEST['emp_c'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);

				$dep_id = isset($_REQUEST['dep_id']) ? $_REQUEST['dep_id'] : '';
				$posi_id = isset($_REQUEST['posi_id']) ? $_REQUEST['posi_id'] : '';
				$emp_manage_a = isset($_REQUEST['emp_manage_a']) ? str_replace(" ", "", $_REQUEST['emp_manage_a']) : '';
				$emp_manage_b = isset($_REQUEST['emp_manage_b']) ? str_replace(" ", "", $_REQUEST['emp_manage_b']) : '';
				$data = array(
					'emp_c' => strtolower($_REQUEST['email']),
					'prefix_th' => '',
					'fname_th' => str_replace(" ", "", $_REQUEST['fname_th']),
					'lname_th' => str_replace(" ", "", $_REQUEST['lname_th']),
					'fullname_th' => str_replace(" ", "", $_REQUEST['fname_th']) . " " . str_replace(" ", "", $_REQUEST['lname_th']),
					'prefix_en' => '',
					'fname_en' => str_replace(" ", "", $_REQUEST['fname_en']),
					'lname_en' => str_replace(" ", "", $_REQUEST['lname_en']),
					'fullname_en' => str_replace(" ", "", $_REQUEST['fname_en']) . " " . str_replace(" ", "", $_REQUEST['lname_en']),
					'gender' => isset($_REQUEST['gender']) ? $_REQUEST['gender'] : "",
					/*'address_th' => $_REQUEST['address_th'],
						'address_en' => $_REQUEST['address_en'],*/
					'work_phone' => $_REQUEST['work_phone'],
					'phone' => $_REQUEST['phone'],
					'email' => $_REQUEST['email'],
					'emp_manage_a' => $emp_manage_a,
					'emp_manage_b' => $emp_manage_b,
					'lang' => isset($_REQUEST['lang']) ? $_REQUEST['lang'] : "english",
					'com_id' => $_REQUEST['com_id'],
					'employ_date' => $_REQUEST['employ_date_var']
				);
				$data_user = array(
					'useri' => strtolower($datas['useri']),
					'dep_id' => $dep_id,
					'posi_id' => $posi_id,
					'ug_id' => $datas['ug_id'],
					'inactivedate' => isset($_REQUEST['inactivedate_var']) && $_REQUEST['inactivedate_var'] != "" ? $_REQUEST['inactivedate_var'] : "0000-00-00",
					'u_firstdate' => isset($_REQUEST['u_firstdate_var']) ? $_REQUEST['u_firstdate_var'] : date('Y-m-d H:i', strtotime('+90 day')),
					'u_modifiedby' => $sess['u_id'],
					'u_modifieddate' => date('Y-m-d H:i'),
				);
				if ($emp_manage_a != "") {
					$fetch_manage = $this->func_query->query_row('lms_emp', '', '', '', 'email="' . $data['emp_manage_a'] . '"');
					if (countArray($fetch_manage) > 0 && $fetch_manage['is_manager'] == "0") {

						$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_manage['emp_id'] . '"');
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_manage['emp_id']);
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
								$this->db->where('emp_id', $fetch_manage['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						$arr_update = array(
							'is_manager' => '1'
						);
						$arr_update['emp_modifiedby'] = $sess['u_id'];
						$arr_update['emp_modifieddate'] = date('Y-m-d H:i');
						$this->db->where('emp_id', $fetch_manage['emp_id']);
						$this->db->update('lms_emp', $arr_update);
					}
				}
				if ($emp_manage_b) {
					$fetch_manage = $this->func_query->query_row('lms_emp', '', '', '', 'email="' . $data['emp_manage_b'] . '"');
					if (countArray($fetch_manage) > 0 && $fetch_manage['is_manager'] == "0") {


						$fetch_usp = $this->func_query->query_row('lms_usp', 'lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id', '', 'lms_usp.emp_id = "' . $fetch_manage['emp_id'] . '"');
						if (countArray($fetch_usp) > 0 && $fetch_usp['ug_name_en'] == "Learner" && $fetch_usp['ug_for'] == "com_central") {
							$fetch_chkug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_name_en="Learner (Manager)" and ug_for="com_central" and ug_isDelete="0" and ug_status="1"', 'ug_id DESC');
							if (countArray($fetch_chkug) > 0) {
								$arr_update_ug = array(
									'ug_id' => $fetch_chkug['ug_id']
								);
								$this->db->where('emp_id', $fetch_manage['emp_id']);
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
								$this->db->where('emp_id', $fetch_manage['emp_id']);
								$this->db->update('lms_usp', $arr_update_ug);
								$this->manage->rechk_role($fetch_usp['u_id'], $fetch_chkug['ug_id']);
							}
						}
						$arr_update = array(
							'is_manager' => '1'
						);
						$arr_update['emp_modifiedby'] = $sess['u_id'];
						$arr_update['emp_modifieddate'] = date('Y-m-d H:i');
						$this->db->where('emp_id', $fetch_manage['emp_id']);
						$this->db->update('lms_emp', $arr_update);
					}
				}
				if (isset($_FILES['img_profile']) && $_FILES['img_profile'] != "") {
					if (isset($_FILES['img_profile'])) {
						$imageSourcePath = $_FILES['img_profile']['tmp_name'];
						$pathBG = $_FILES['img_profile']['name'];
						if ($pathBG != "") {
							$array_pathext = explode('.', $pathBG);
							$extension = end($array_pathext);
							$img_profile = $_REQUEST['emp_c'] . "_" . date('YmdHis') . "." . $extension;
							$imageTargetPath = ROOT_DIR . "uploads/profile/" . $img_profile;
							if ($_REQUEST['operation'] == "Edit") {
								$fetch_img = $this->func_query->query_row('lms_usp', '', '', '', 'u_id="' . $_REQUEST['u_id'] . '"');
								if (countArray($fetch_img) > 0 && $fetch_img['img_profile'] != "") {
									if (is_file(ROOT_DIR . "uploads/profile/" . $fetch_img['img_profile'])) {
										audit_unlink(ROOT_DIR . "uploads/profile/" . $fetch_img['img_profile']);
									}
								}
							}
							if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
								$data_user['img_profile'] = $img_profile;
							}
						}
					}
				}

				if (isset($_FILES['bgpic_user']) && $_FILES['bgpic_user'] != "") {
					if (isset($_FILES['bgpic_user'])) {
						$imageSourcePath = $_FILES['bgpic_user']['tmp_name'];
						$pathBG = $_FILES['bgpic_user']['name'];
						if ($pathBG != "") {
							$array_pathext = explode('.', $pathBG);
							$extension = end($array_pathext);
							$bgpic_user = $_REQUEST['emp_c'] . "_" . date('YmdHis') . "." . $extension;
							$imageTargetPath = ROOT_DIR . "uploads/bg_user/" . $bgpic_user;
							if ($_REQUEST['operation'] == "Edit") {
								$fetch_img = $this->func_query->query_row('lms_usp', '', '', '', 'u_id="' . $_REQUEST['u_id'] . '"');
								if (countArray($fetch_img) > 0 && $fetch_img['bgpic_user'] != "") {
									if (is_file(ROOT_DIR . "uploads/bg_user/" . $fetch_img['bgpic_user'])) {
										audit_unlink(ROOT_DIR . "uploads/bg_user/" . $fetch_img['bgpic_user']);
									}
								}
							}
							if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
								$data_user['bgpic_user'] = $bgpic_user;
							}
						}
					}
				}

				if ($_REQUEST['operation'] == "Add") {
					$data['emp_createby'] = $sess['u_id'];
					$data['emp_createdate'] = date('Y-m-d H:i');
					$data['emp_modifiedby'] = $sess['u_id'];
					$data['emp_modifieddate'] = date('Y-m-d H:i');
					$password = $this->generateRandomString();
					$password_enc = hash('sha256', $password);
					$msg = $this->manage->create_emp($data);
					if ($msg != "0") {
						$date = date('Y-m-d H:i');
						$date = new DateTime($date);
						$date->modify('+90 day');
						$data_user['expiredate'] = date_format($date, 'Y-m-d H:i');
						$data_user['u_createdate'] = date('Y-m-d H:i');
						$data_user['u_createby'] = $sess['u_id'];
						$data_user['userp'] = $password_enc;
						$data_user['emp_id'] = $msg;
						$status_save = $this->manage->create_user($data_user);
						$this->update_manager($data['emp_manage_a'], $data['emp_manage_b']);
						$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $_REQUEST['com_id'] . '"');
						if ($status_save == "2") {
							$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
							$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="1"');

							$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
							//if($lang!="thai"){
							$date = date('d F Y');
							//}
							if (countArray($fetch_formatmail) > 0) {
								$subject_th = $fetch_formatmail['smf_subject_th'];
								$subject_en = $fetch_formatmail['smf_subject_en'];
								$message_th = $fetch_formatmail['smf_message_th'];
								$message_en = $fetch_formatmail['smf_message_en'];
								if ($subject_th != "") {
									$subject_th = str_replace("#fullname", $data['fullname_th'], $subject_th);
									$subject_th = str_replace("#username", $data_user['useri'], $subject_th);
									$subject_th = str_replace("#email", $data['email'], $subject_th);
									$subject_th = str_replace("#coursename", "", $subject_th);
									$subject_th = str_replace("#password", $password, $subject_th);
									$subject_th = str_replace("#link_frontend", base_url(), $subject_th);
									$subject_th = str_replace("#date", $date, $subject_th);
									$subject_th = str_replace("#time", date('H:i'), $subject_th);
									$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
								}
								if ($subject_en != "") {
									$subject_en = str_replace("#fullname", $data['fullname_en'], $subject_en);
									$subject_en = str_replace("#username", $data_user['useri'], $subject_en);
									$subject_en = str_replace("#email", $data['email'], $subject_en);
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
									$message_th = str_replace("#fullname", $data['fullname_th'], $message_th);
									$message_th = str_replace("#username", $data_user['useri'], $message_th);
									$message_th = str_replace("#email", $data['email'], $message_th);
									$message_th = str_replace("#coursename", "", $message_th);
									$message_th = str_replace("#password", $password, $message_th);
									$message_th = str_replace("#link_frontend", base_url(), $message_th);
									$message_th = str_replace("#date", $date, $message_th);
									$message_th = str_replace("#time", date('H:i'), $message_th);
									$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
									$message_th = str_replace("#image", $img_val, $message_th);
								}
								if ($message_en != "") {
									$message_en = str_replace("#fullname", $data['fullname_en'], $message_en);
									$message_en = str_replace("#username", $data_user['useri'], $message_en);
									$message_en = str_replace("#email", $data['email'], $message_en);
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
									$this->db->sendEmail($data['email'], $message_th, $subject_th, $fetch_setmail);
								} else {
									$this->db->sendEmail($data['email'], $message_en, $subject_en, $fetch_setmail);
								}
							}
						} else {
							if ($status_save != "") {
								$status_save = $status_save;
							} else {
								$status_save = "0";
							}
						}
					} else {
						$status_save = "1"; // Duplicate
					}
				} else {

					$data['emp_modifiedby'] = $sess['u_id'];
					$data['emp_modifieddate'] = date('Y-m-d H:i');
					$msg = $this->manage->update_emp($data, $_REQUEST['emp_id']);
					if ($msg == "2") {
						$data_user['emp_id'] = $_REQUEST['emp_id'];
						$status_save = $this->manage->update_user($data_user, $_REQUEST['u_id']);
						$this->update_manager($data['emp_manage_a'], $data['emp_manage_b']);
					}
				}
			} else {
				$status_save = "9";
			}
		}
		echo $status_save;
	}

	public function resendmail_firsttime()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : "";
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$fetch_chkemp = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_emp.emp_id = "' . $emp_id . '" and lms_emp.emp_firsttime = 1');
		$output = array();
		if (countArray($fetch_chkemp) > 0) {

			$password = $this->generateRandomString();
			$password_enc = hash('sha256', $password);

			$date = date('Y-m-d H:i');
			$date = new DateTime($date);
			$date->modify('+90 day');
			$data_user = array();
			$data_user['expiredate'] = date_format($date, 'Y-m-d H:i');
			$data_user['u_modifieddate'] = date('Y-m-d H:i');
			$data_user['u_modifiedby'] = $sess['u_id'];
			$data_user['userp'] = $password_enc;
			$this->db->where('u_id', $fetch_chkemp['u_id']);
			$this->db->update('lms_usp', $data_user);
			$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_chkemp['com_id'] . '"');

			$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
			$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="1"');

			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			if (countArray($fetch_formatmail) > 0) {
				$subject_th = $fetch_formatmail['smf_subject_th'];
				$subject_en = $fetch_formatmail['smf_subject_en'];
				$message_th = $fetch_formatmail['smf_message_th'];
				$message_en = $fetch_formatmail['smf_message_en'];
				if ($subject_th != "") {
					$subject_th = str_replace("#fullname", $fetch_chkemp['fullname_th'], $subject_th);
					$subject_th = str_replace("#username", $fetch_chkemp['useri'], $subject_th);
					$subject_th = str_replace("#email", $fetch_chkemp['email'], $subject_th);
					$subject_th = str_replace("#coursename", "", $subject_th);
					$subject_th = str_replace("#password", $password, $subject_th);
					$subject_th = str_replace("#link_frontend", base_url(), $subject_th);
					$subject_th = str_replace("#date", $date, $subject_th);
					$subject_th = str_replace("#time", date('H:i'), $subject_th);
					$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
				}
				if ($subject_en != "") {
					$subject_en = str_replace("#fullname", $fetch_chkemp['fullname_en'], $subject_en);
					$subject_en = str_replace("#username", $fetch_chkemp['useri'], $subject_en);
					$subject_en = str_replace("#email", $fetch_chkemp['email'], $subject_en);
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
					$message_th = str_replace("#fullname", $fetch_chkemp['fullname_th'], $message_th);
					$message_th = str_replace("#username", $fetch_chkemp['useri'], $message_th);
					$message_th = str_replace("#email", $fetch_chkemp['email'], $message_th);
					$message_th = str_replace("#coursename", "", $message_th);
					$message_th = str_replace("#password", $password, $message_th);
					$message_th = str_replace("#link_frontend", base_url(), $message_th);
					$message_th = str_replace("#date", $date, $message_th);
					$message_th = str_replace("#time", date('H:i'), $message_th);
					$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
					$message_th = str_replace("#image", $img_val, $message_th);
				}
				if ($message_en != "") {
					$message_en = str_replace("#fullname", $fetch_chkemp['fullname_en'], $message_en);
					$message_en = str_replace("#username", $fetch_chkemp['useri'], $message_en);
					$message_en = str_replace("#email", $fetch_chkemp['email'], $message_en);
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
					$this->db->sendEmail($fetch_chkemp['email'], $message_th, $subject_th, $fetch_setmail);
				} else {
					$this->db->sendEmail($fetch_chkemp['email'], $message_en, $subject_en, $fetch_setmail);
				}
			}
			$output['status'] = "2";
		} else {
			$output['status'] = "0";
		}
		echo json_encode($output);
	}

	public function update_manager($emp_c_a, $emp_c_b)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		//echo $emp_c_a."::".$emp_c_b;
		if ($emp_c_a != "") {
			$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $emp_c_a . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
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
		if ($emp_c_b != "") {
			$fetch_chkemp = $this->func_query->query_row('lms_emp', '', '', '', 'emp_c="' . $emp_c_b . '" and emp_isDelete="0" and (depart_date="0000-00-00" or depart_date IS NULL)');
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

	public function delete_company_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$fetch_chkuser = $this->func_query->numrows('lms_emp', '', '', '', 'com_id ="' . $_REQUEST['com_id_delete'] . '" and emp_isDelete="0"');
		if (countArray($_REQUEST) > 0) {
			if ($fetch_chkuser == 0) {
				$data = array(
					'com_isDelete' => '1',
					'com_modifiedby' => $sess['u_id'],
					'com_modifieddate' => date('Y-m-d H:i')
				);
				$msg = $this->manage->update_company($data, $_REQUEST['com_id_delete']);
			} else {
				$msg = "1";
			}
		}
		echo $msg;
	}

	public function delete_conmsg_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'conmsg_isDelete' => '1',
				'conmsg_modifiedby' => $sess['u_id'],
				'conmsg_modifieddate' => date('Y-m-d H:i')
			);
			$msg = $this->manage->update_conmsg($data, $_REQUEST['conmsg_id']);
		}
		echo $msg;
	}

	public function delete_cosgroup_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'cg_isDelete' => '1',
				'u_by' => $sess['u_id'],
				'u_date' => date('Y-m-d H:i')
			);

			$this->db->where('cg_id', $_REQUEST['id_delete']);
			$this->db->update('lms_cog', $data);

			$fetch = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $_REQUEST['id_delete'] . '"');
			$cgtitle = "";
			if ($lang == "thai") {
				$cgtitle = $fetch['cgtitle_th'] != "" ? $fetch['cgtitle_th'] : $fetch['cgtitle_en'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch['cgtitle_jp'];
			} else if ($lang == "english") {
				$cgtitle = $fetch['cgtitle_en'] != "" ? $fetch['cgtitle_en'] : $fetch['cgtitle_th'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch['cgtitle_jp'];
			} else {
				$cgtitle = $fetch['cgtitle_jp'] != "" ? $fetch['cgtitle_jp'] : $fetch['cgtitle_en'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch['cgtitle_th'];
			}
			$this->lg->record('courseGroup', 'Delete course group : ' . $cgtitle . ' (' . $_REQUEST['id_delete'] . ')');
			$msg = "2";
		}
		echo $msg;
	}

	public function approve_cosgroupall()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$fetch = $this->func_query->query_result('lms_cog', '', '', '', 'cg_approve="1" and cg_isDelete="0" and cg_id not in (select cg_id from lms_cog_approve)');
		foreach ($fetch as $key => $value) {
			$arr_update = array(
				'cg_id' => $value['cg_id'],
				'coga_approve' => '1',
				'coga_createby' => $value['u_by'],
				'coga_createdate' => $value['u_date'],
			);
			$this->db->insert('lms_cog_approve', $arr_update);
		}
	}

	public function approve_cosgroup_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$arr_update = array(
				'cg_id' => $_REQUEST['cg_id'],
				'coga_approve' => '1',
				'coga_createby' => $sess['u_id'],
				'coga_createdate' => date('Y-m-d H:i'),
			);
			$this->db->insert('lms_cog_approve', $arr_update);
			$data = array(
				'cg_approve' => '1',
				'u_by' => $sess['u_id'],
				'u_date' => date('Y-m-d H:i')
			);

			$this->db->where('cg_id', $_REQUEST['cg_id']);
			$this->db->update('lms_cog', $data);
			$fetch_cg = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $_REQUEST['cg_id'] . '"');
			$cgtitle = "";
			if ($lang == "thai") {
				$cgtitle = $fetch_cg['cgtitle_th'] != "" ? $fetch_cg['cgtitle_th'] : $fetch_cg['cgtitle_en'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_jp'];
			} else if ($lang == "english") {
				$cgtitle = $fetch_cg['cgtitle_en'] != "" ? $fetch_cg['cgtitle_en'] : $fetch_cg['cgtitle_th'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_jp'];
			} else {
				$cgtitle = $fetch_cg['cgtitle_jp'] != "" ? $fetch_cg['cgtitle_jp'] : $fetch_cg['cgtitle_en'];
				$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_th'];
			}
			$this->lg->record('courseGroup', 'Approve course group : ' . $cgtitle . ' (' . $_REQUEST['cg_id'] . ')');

			$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');

			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			if ($fetch_cg['c_by'] != "") {
				$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_cg['c_by'] . '"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="5"');
				if (countArray($fetch_formatmail) > 0) {
					$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
					$subject_th = $fetch_formatmail['smf_subject_th'];
					$subject_en = $fetch_formatmail['smf_subject_en'];
					$message_th = $fetch_formatmail['smf_message_th'];
					$message_en = $fetch_formatmail['smf_message_en'];
					if ($subject_th != "") {
						$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
						$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
						$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
						$subject_th = str_replace("#coursename", $cgtitle, $subject_th);
						$subject_th = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $subject_th);
						$subject_th = str_replace("#date", $date, $subject_th);
						$subject_th = str_replace("#time", date('H:i'), $subject_th);
						$subject_th = str_replace("#perioddate", '', $subject_th);
						$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
						$subject_th = str_replace("#durationofstudy", '-', $subject_th);
					}
					if ($subject_en != "") {
						$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
						$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
						$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
						$subject_en = str_replace("#coursename", $cgtitle, $subject_en);
						$subject_en = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $subject_en);
						$subject_en = str_replace("#date", $date, $subject_en);
						$subject_en = str_replace("#time", date('H:i'), $subject_en);
						$subject_en = str_replace("#perioddate", '', $subject_en);
						$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
						$subject_en = str_replace("#durationofstudy", '-', $subject_en);
					}
					if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
						$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
					} else {
						$img_val = '';
					}
					if ($message_th != "") {
						$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
						$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
						$message_th = str_replace("#email", $fetch_user['email'], $message_th);
						$message_th = str_replace("#coursename", $cgtitle, $message_th);
						$message_th = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $message_th);
						$message_th = str_replace("#date", $date, $message_th);
						$message_th = str_replace("#time", date('H:i'), $message_th);
						$message_th = str_replace("#perioddate", '', $message_th);
						$message_th = str_replace("#image", $img_val, $message_th);
						$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
						$message_th = str_replace("#durationofstudy", '-', $message_th);
					}
					if ($message_en != "") {
						$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
						$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
						$message_en = str_replace("#email", $fetch_user['email'], $message_en);
						$message_en = str_replace("#coursename", $cgtitle, $message_en);
						$message_en = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $message_en);
						$message_en = str_replace("#date", $date, $message_en);
						$message_en = str_replace("#time", date('H:i'), $message_en);
						$message_en = str_replace("#perioddate", '', $message_en);
						$message_en = str_replace("#image", $img_val, $message_en);
						$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
						$message_en = str_replace("#durationofstudy", '-', $message_en);
					}
					$lang = "english";
					if ($lang == "thai") {
						$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
					} else {
						$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
					}
				}
			}
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_cosdoc_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'fil_isDelete' => '1',
				'fil_modifiedby' => $sess['u_id'],
				'fil_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('fil_cos_id', $_REQUEST['id_delete']);
			$this->db->update('lms_cos_fil', $data);

			$fetch = $this->func_query->query_row('lms_cos_fil', '', '', '', 'fil_cos_id="' . $_REQUEST['id_delete'] . '"');
			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'lms_cos.cos_id="' . $fetch['cos_id'] . '"');

			$cname = "";
			if ($lang == "thai") {
				$cname = $fetch_cos['cname_th'] != "" ? $fetch_cos['cname_th'] : $fetch_cos['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
			} else if ($lang == "english") {
				$cname = $fetch_cos['cname_eng'] != "" ? $fetch_cos['cname_eng'] : $fetch_cos['cname_th'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
			} else {
				$cname = $fetch_cos['cname_jp'] != "" ? $fetch_cos['cname_jp'] : $fetch_cos['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_th'];
			}
			$this->lg->record('Course', 'Delete document of course : ' . $fetch['path_file'] . ' of Course: ' . $cname . '(' . $fetch['cos_id'] . ')');
			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_cosdetail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'cosde_isDelete' => '1',
				'cosde_modifiedby' => $sess['u_id'],
				'cosde_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('cosde_id', $_REQUEST['cosde_id']);
			$this->db->update('lms_cos_detail', $data);

			$fetch = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cosde_id="' . $_REQUEST['cosde_id'] . '"');
			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'lms_cos.cos_id="' . $fetch['cos_id'] . '"');

			$cname = "";
			if ($lang == "thai") {
				$cname = $fetch_cos['cname_th'] != "" ? $fetch_cos['cname_th'] : $fetch_cos['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
			} else if ($lang == "english") {
				$cname = $fetch_cos['cname_eng'] != "" ? $fetch_cos['cname_eng'] : $fetch_cos['cname_th'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
			} else {
				$cname = $fetch_cos['cname_jp'] != "" ? $fetch_cos['cname_jp'] : $fetch_cos['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch_cos['cname_th'];
			}
			$this->lg->record('Course', 'Delete period: ' . $fetch['date_start'] . ' - ' . $fetch['date_end'] . ' of Course: ' . $cname . '(' . $fetch['cos_id'] . ')');
			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_cos_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'cos_isDelete' => '1',
				'cos_modifiedby' => $sess['u_id'],
				'cos_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('cos_id', $_REQUEST['id_delete']);
			$this->db->update('lms_cos', $data);
			$fetch = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $_REQUEST['id_delete'] . '"');
			$cname = "";
			if ($lang == "thai") {
				$cname = $fetch['cname_th'] != "" ? $fetch['cname_th'] : $fetch['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch['cname_jp'];
			} else if ($lang == "english") {
				$cname = $fetch['cname_eng'] != "" ? $fetch['cname_eng'] : $fetch['cname_th'];
				$cname = $cname != "" ? $cname : $fetch['cname_jp'];
			} else {
				$cname = $fetch['cname_jp'] != "" ? $fetch['cname_jp'] : $fetch['cname_eng'];
				$cname = $cname != "" ? $cname : $fetch['cname_th'];
			}
			$this->lg->record('Course', 'Delete course: ' . $cname . ' (' . $fetch['cos_id'] . ')');
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_sv_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'sv_isDelete' => '1',
				'sv_modifiedby' => $sess['u_id'],
				'sv_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('sv_id', $_REQUEST['id_delete']);
			$this->db->update('lms_sv', $data);
			$fetch = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['id_delete'] . '"');

			if ($lang == "thai") {
				$sv_title = $fetch['sv_title_th'] != "" ? $fetch['sv_title_th'] : $fetch['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch['sv_title_jp'];
			} else if ($lang == "english") {
				$sv_title = $fetch['sv_title_eng'] != "" ? $fetch['sv_title_eng'] : $fetch['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch['sv_title_jp'];
			} else {
				$sv_title = $fetch['sv_title_jp'] != "" ? $fetch['sv_title_jp'] : $fetch['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch['sv_title_th'];
			}
			$this->lg->record('publicSurvey', 'Delete public survey: ' . $sv_title . ' (' . $fetch['sv_id'] . ')');
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_sv_question_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'svde_isDelete' => '1',
				'svde_modifiedby' => $sess['u_id'],
				'svde_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('svde_id', $_REQUEST['id_delete']);
			$this->db->update('lms_svde', $data);
			$fetch = $this->func_query->query_row('lms_svde', '', '', '', 'svde_id="' . $_REQUEST['id_delete'] . '"');
			$fetchMain = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $fetch['sv_id'] . '"');

			if ($lang == "thai") {
				$sv_title = $fetchMain['sv_title_th'] != "" ? $fetchMain['sv_title_th'] : $fetchMain['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetchMain['sv_title_jp'];
				$svde_name = $fetch['svde_name_th'] != "" ? $fetch['svde_name_th'] : $fetch['svde_name_eng'];
				$svde_name = $svde_name != "" ? $svde_name : $fetch['svde_name_jp'];
			} else if ($lang == "english") {
				$sv_title = $fetchMain['sv_title_eng'] != "" ? $fetchMain['sv_title_eng'] : $fetchMain['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $fetchMain['sv_title_jp'];
				$svde_name = $fetch['svde_name_eng'] != "" ? $fetch['svde_name_eng'] : $fetch['svde_name_th'];
				$svde_name = $svde_name != "" ? $svde_name : $fetch['svde_name_jp'];
			} else {
				$sv_title = $fetchMain['sv_title_jp'] != "" ? $fetchMain['sv_title_jp'] : $fetchMain['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetchMain['sv_title_th'];
				$svde_name = $fetch['svde_name_jp'] != "" ? $fetch['svde_name_jp'] : $fetch['svde_name_eng'];
				$svde_name = $svde_name != "" ? $svde_name : $fetch['svde_name_th'];
			}
			$this->lg->record('publicSurvey', 'Delete question: ' . $svde_name . '(' . $fetch['svde_id'] . ') of public survey: ' . $sv_title . '(' . $fetch['sv_id'] . ')');
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_data_qiz_exp()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'qize_isDelete' => '1',
				'qize_modifiedby' => $sess['u_id'],
				'qize_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('qize_id', $_REQUEST['qize_id']);
			$this->db->update('lms_qiz_exp', $data);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_data_qiz_exp_question()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'quese_isDelete' => '1',
				'quese_modifiedby' => $sess['u_id'],
				'quese_modifieddate' => date('Y-m-d H:i')
			);

			$this->db->where('quese_id', $_REQUEST['quese_id']);
			$this->db->update('lms_quese', $data);
			$msg = "2";
		}
		echo $msg;
	}

	public function recheckmanage_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$emp_manage = $this->input->post('emp_manage');
		$email = $this->input->post('email');
		$emp_manage_type = $this->input->post('emp_manage_type');
		$com_id = $this->input->post('com_id');
		$q = $this->input->post('q');
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->load->model('Function_query_model', 'func_query', false);
		$this->func_query->loadDB();
		$fetch_query = $this->func_query->query_result('lms_emp', '', '', '', 'emp_isDelete="0" and com_id="' . $com_id . '" and email!="" and email!="' . $email . '" and email like "%' . $q . '%"', '', 'email', 100);
		$array = array();
		if (countArray($fetch_query) > 0) {
			//echo "<optgroup label='".label('sv_b_none')."'>";
			$array[] =  array("id" => "", "value" => label('sv_b_none'), "selected" => false);
			$numloop = 1;
			foreach ($fetch_query as $key) {
				$select_val = false;
				if ($key['email'] == $emp_manage) {
					$select_val = true;
				}
				$numloop++;
				//echo "<option value='".$key['cus_id']."' ".$select_val.">".$key['cus_fullname']."</option>"; 
				$array[] =  array("id" => $key["email"], "value" => $key["email"], "selected" => $select_val);
			}
			$this->manage->closeDB();
			//echo "</optgroup>";
		} else {
			//echo "<option value=''>".label('datanotfound')."</option>";
			$array[] =  array("id" => '', "value" => label('wg_datanotfound'));
		}
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public function recheckmanage_data_normal()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$emp_manage = $this->input->post('emp_manage');
		$email = $this->input->post('email');
		$emp_manage_type = $this->input->post('emp_manage_type');
		$com_id = $this->input->post('com_id');
		$q = $this->input->post('q');
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->load->model('Function_query_model', 'func_query', false);
		$this->func_query->loadDB();
		$fetch_query = $this->func_query->query_result('lms_emp', '', '', '', 'emp_isDelete="0" and com_id="' . $com_id . '" and email!="" and email!="' . $email . '"', '', 'email', 100);
		$array = array();
		if (countArray($fetch_query) > 0) {
			echo "<optgroup label='" . label('sv_b_none') . "'>";
			//$array[] =  array("id"=> "", "value" => label('sv_b_none') , "selected"=> false);
			$numloop = 1;
			foreach ($fetch_query as $key) {
				$select_val = false;
				if ($key['email'] == $emp_manage) {
					$select_val = true;
				}
				$numloop++;
				echo "<option value='" . $key['email'] . "' " . $select_val . ">" . $key['email'] . "</option>";
				//$array[] =  array("id"=> $key["email"], "value" => $key["email"] , "selected"=> $select_val);

			}
			$this->manage->closeDB();
			echo "</optgroup>";
		} else {
			echo "<option value=''>" . label('wg_datanotfound') . "</option>";
			//$array[] =  array("id"=> '', "value" => label('datanotfound'));
		}
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public function approve_cos_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$msg = "";
		$lang = "english";
		if (countArray($_REQUEST) > 0) {
			$chkis_sendmail = 0;
			$arr_update = array(
				'cos_id' => $_REQUEST['cos_id'],
				'cosa_approve' => '1',
				'cosa_createby' => $sess['u_id'],
				'cosa_createdate' => date('Y-m-d H:i'),
			);
			$this->db->insert('lms_cos_approve', $arr_update);
			$data = array(
				'cos_public' => '1',
				'cos_approve' => '1',
				'cos_approveby' => $sess['u_id'],
				'cos_approvedate' => date('Y-m-d H:i')
			);

			$this->db->where('cos_id', $_REQUEST['cos_id']);
			$this->db->update('lms_cos', $data);

			$result = $this->func_query->query_row('lms_cos', 'lms_cos_detail', 'lms_cos.cos_id = lms_cos_detail.cos_id', '', 'lms_cos.cos_id="' . $_REQUEST['cos_id'] . '"');
			if (countArray($result) > 0) {
				if ($result['date_start'] != "0000-00-00 00:00:00") {
					if (date('Y-m-d') < date('Y-m-d', strtotime($result['date_start']))) {
						$arr_update = array(
							'cos_id' => $_REQUEST['cos_id'],
							'jcosnoti_datejob' => date('Y-m-d', strtotime($result['date_start'])),
						);
						$this->db->insert('lms_job_cosnoti', $arr_update);
					} else {
						$chkis_sendmail = 1;
					}
				} else {
					$chkis_sendmail = 1;
				}
			} else {
				$chkis_sendmail = 1;
			}


			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '"');
			$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$cname = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$cname = $fetch_cos['cname_th'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$cname = $fetch_cos['cname_eng'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$cname = $fetch_cos['cname_jp'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
				}
			}
			$this->lg->record('course', 'Approve course ' . $cname . '(' . $_REQUEST['cos_id'] . ')');
			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			$period = "Unlimited time"; //label('UnlimitedTime');
			$fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '" and cosde_status="1" and cosde_isDelete="0"');
			if (countArray($fetch_cos_detail) > 0) {
				if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
					// if($lang=="thai"){
					// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_start'])))].(date('Y',strtotime($fetch_cos_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_start'])):"";
					// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_end'])))].(date('Y',strtotime($fetch_cos_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_end'])):"";
					// }else{
					// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_start'])):"";
					// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_end'])):"";
					// }
					$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
					$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

					if ($periodstart != "" && $periodend != "") {
						$period = $periodstart . " - " . $periodend;
					}
				}
			}
			if ($fetch_cos['cos_createby'] != "") {
				$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_cos['cos_createby'] . '"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="5"');
				if (countArray($fetch_formatmail) > 0) {
					$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
					$subject_th = $fetch_formatmail['smf_subject_th'];
					$subject_en = $fetch_formatmail['smf_subject_en'];
					$message_th = $fetch_formatmail['smf_message_th'];
					$message_en = $fetch_formatmail['smf_message_en'];
					$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
					if ($subject_th != "") {
						$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
						$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
						$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
						$subject_th = str_replace("#coursename", $cname, $subject_th);
						$subject_th = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $subject_th);
						$subject_th = str_replace("#date", $date, $subject_th);
						$subject_th = str_replace("#time", date('H:i'), $subject_th);
						$subject_th = str_replace("#perioddate", $period, $subject_th);
						$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
						$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
					}
					if ($subject_en != "") {
						$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
						$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
						$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
						$subject_en = str_replace("#coursename", $cname, $subject_en);
						$subject_en = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $subject_en);
						$subject_en = str_replace("#date", $date, $subject_en);
						$subject_en = str_replace("#time", date('H:i'), $subject_en);
						$subject_en = str_replace("#perioddate", $period, $subject_en);
						$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
						$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
					}
					if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
						$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
					} else {
						$img_val = '';
					}
					if ($message_th != "") {
						$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
						$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
						$message_th = str_replace("#email", $fetch_user['email'], $message_th);
						$message_th = str_replace("#coursename", $cname, $message_th);
						$message_th = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $message_th);
						$message_th = str_replace("#date", $date, $message_th);
						$message_th = str_replace("#time", date('H:i'), $message_th);
						$message_th = str_replace("#perioddate", $period, $message_th);
						$message_th = str_replace("#image", $img_val, $message_th);
						$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
						$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
					}
					if ($message_en != "") {
						$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
						$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
						$message_en = str_replace("#email", $fetch_user['email'], $message_en);
						$message_en = str_replace("#coursename", $cname, $message_en);
						$message_en = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $message_en);
						$message_en = str_replace("#date", $date, $message_en);
						$message_en = str_replace("#time", date('H:i'), $message_en);
						$message_en = str_replace("#perioddate", $period, $message_en);
						$message_en = str_replace("#image", $img_val, $message_en);
						$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
						$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
					}
					//echo $message_en;
					//$lang = "english";
					if ($lang == "thai") {
						$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
					} else {
						$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
					}
				}
			}
			if ($chkis_sendmail == 1) {
				$period = "Unlimited time"; //label('UnlimitedTime');
				$fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '" and cosde_status="1" and cosde_isDelete="0"');
				if (countArray($fetch_cos_detail) > 0) {
					if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
						// if($lang=="thai"){
						// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_start'])))].(date('Y',strtotime($fetch_cos_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_start'])):"";
						// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_end'])))].(date('Y',strtotime($fetch_cos_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_end'])):"";
						// }else{
						// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_start'])):"";
						// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_end'])):"";
						// }
						$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
						$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

						if ($periodstart != "" && $periodend != "") {
							$period = $periodstart . " - " . $periodend;
						}
					}
				}
				$arr_email = array();

				$fetch_tc = $this->func_query->query_result('lms_cos_enroll', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '" and  cosen_isDelete="0"');
				if (countArray($fetch_tc) > 0) {
					foreach ($fetch_tc as $key_tc => $value_tc) {
						$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_emp.emp_id="' . $value_tc['emp_id'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
						$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="10"');
						if (countArray($fetch_formatmail) > 0 && !in_array($fetch_user['email'], $arr_email) && countArray($fetch_user) > 0) {
							array_push($arr_email, $fetch_user['email']);

							///	echo $fetch_user['email']."::2010:";
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
							$subject_th = $fetch_formatmail['smf_subject_th'];
							$subject_en = $fetch_formatmail['smf_subject_en'];
							$message_th = $fetch_formatmail['smf_message_th'];
							$message_en = $fetch_formatmail['smf_message_en'];
							$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
							if ($subject_th != "") {
								$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
								$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
								$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
								$subject_th = str_replace("#coursename", $cname, $subject_th);
								$subject_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $_REQUEST['cos_id'], $subject_th);
								$subject_th = str_replace("#date", $date, $subject_th);
								$subject_th = str_replace("#time", date('H:i'), $subject_th);
								$subject_th = str_replace("#perioddate", $period, $subject_th);
								$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
								$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
							}
							if ($subject_en != "") {
								$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
								$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
								$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
								$subject_en = str_replace("#coursename", $cname, $subject_en);
								$subject_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $_REQUEST['cos_id'], $subject_en);
								$subject_en = str_replace("#date", $date, $subject_en);
								$subject_en = str_replace("#time", date('H:i'), $subject_en);
								$subject_en = str_replace("#perioddate", $period, $subject_en);
								$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
								$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
							}
							if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
								$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
							} else {
								$img_val = '';
							}
							if ($message_th != "") {
								$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
								$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
								$message_th = str_replace("#email", $fetch_user['email'], $message_th);
								$message_th = str_replace("#coursename", $cname, $message_th);
								$message_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $_REQUEST['cos_id'], $message_th);
								$message_th = str_replace("#date", $date, $message_th);
								$message_th = str_replace("#time", date('H:i'), $message_th);
								$message_th = str_replace("#perioddate", $period, $message_th);
								$message_th = str_replace("#image", $img_val, $message_th);
								$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
								$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
							}
							if ($message_en != "") {
								$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
								$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
								$message_en = str_replace("#email", $fetch_user['email'], $message_en);
								$message_en = str_replace("#coursename", $cname, $message_en);
								$message_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $_REQUEST['cos_id'], $message_en);
								$message_en = str_replace("#date", $date, $message_en);
								$message_en = str_replace("#time", date('H:i'), $message_en);
								$message_en = str_replace("#perioddate", $period, $message_en);
								$message_en = str_replace("#image", $img_val, $message_en);
								$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
								$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
							}
							//echo $message_en;
							//$lang = "english";
							if ($lang == "thai") {
								$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
							} else {
								$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
							}
						}
					}
				}

				if (countArray($fetch_cos_detail) > 0) {
					if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
						// if($lang=="thai"){
						// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_start'])))].(date('Y',strtotime($fetch_cos_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_start'])):"";
						// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_cos_detail['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_end'])))].(date('Y',strtotime($fetch_cos_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_end'])):"";
						// }else{
						// $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_start'])):"";
						// $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_end'])):"";
						// }
						$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
						$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

						if ($periodstart != "" && $periodend != "") {
							$period = $periodstart . " - " . $periodend;
						}
					}
					$fetch_chk_position = $this->func_query->query_result('lms_cos_detail_ug', '', '', '', 'cosde_id="' . $fetch_cos_detail['cosde_id'] . '"');
					if (countArray($fetch_chk_position) > 0) {
						$fetch_formatmail_b = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="12"');
						foreach ($fetch_chk_position as $key_chk_position => $value_chk_position) {
							if (countArray($fetch_formatmail_b) > 0) {
								$fetch_userposi = $this->func_query->query_result('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.posi_id="' . $value_chk_position['posi_id'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
								if (countArray($fetch_userposi)) {
									foreach ($fetch_userposi as $key_userposi => $value_userposi) {
										if (!in_array($value_userposi['email'], $arr_email)) {
											//echo $value_userposi['email']."::2106:";
											array_push($arr_email, $value_userposi['email']);
											$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_userposi['com_id'] . '"');
											$subject_th = $fetch_formatmail_b['smf_subject_th'];
											$subject_en = $fetch_formatmail_b['smf_subject_en'];
											$message_b_th = $fetch_formatmail_b['smf_message_th'];
											$message_b_en = $fetch_formatmail_b['smf_message_en'];

											$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
											if ($subject_th != "") {
												$subject_th = str_replace("#fullname", $value_userposi['fullname_th'], $subject_th);
												$subject_th = str_replace("#username", $value_userposi['useri'], $subject_th);
												$subject_th = str_replace("#email", $value_userposi['email'], $subject_th);
												$subject_th = str_replace("#coursename", $cname, $subject_th);
												$subject_th = str_replace("#link_frontend", base_url() . "coursemain/all_courses", $subject_th);
												$subject_th = str_replace("#date", $date, $subject_th);
												$subject_th = str_replace("#time", date('H:i'), $subject_th);
												$subject_th = str_replace("#perioddate", $period, $subject_th);
												$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
												$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
											}
											if ($subject_en != "") {
												$subject_en = str_replace("#fullname", $value_userposi['fullname_en'], $subject_en);
												$subject_en = str_replace("#username", $value_userposi['useri'], $subject_en);
												$subject_en = str_replace("#email", $value_userposi['email'], $subject_en);
												$subject_en = str_replace("#coursename", $cname, $subject_en);
												$subject_en = str_replace("#link_frontend", base_url() . "coursemain/all_courses", $subject_en);
												$subject_en = str_replace("#date", $date, $subject_en);
												$subject_en = str_replace("#time", date('H:i'), $subject_en);
												$subject_en = str_replace("#perioddate", $period, $subject_en);
												$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
												$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
											}
											if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
												$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
											} else {
												$img_val = '';
											}
											if ($message_b_th != "") {
												$message_b_th = str_replace("#fullname", $value_userposi['fullname_th'], $message_b_th);
												$message_b_th = str_replace("#username", $value_userposi['useri'], $message_b_th);
												$message_b_th = str_replace("#email", $value_userposi['email'], $message_b_th);
												$message_b_th = str_replace("#coursename", $cname, $message_b_th);
												$message_b_th = str_replace("#link_frontend", base_url() . "coursemain/all_courses", $message_b_th);
												$message_b_th = str_replace("#date", $date, $message_b_th);
												$message_b_th = str_replace("#time", date('H:i'), $message_b_th);
												$message_b_th = str_replace("#perioddate", $period, $message_b_th);
												$message_b_th = str_replace("#image", $img_val, $message_b_th);
												$message_b_th = str_replace("#durationofstudy", $cos_hour, $message_b_th);
												$message_b_th = str_replace("#companyname", $fetch_company['com_code'], $message_b_th);
											}
											if ($message_b_en != "") {
												$message_b_en = str_replace("#fullname", $value_userposi['fullname_en'], $message_b_en);
												$message_b_en = str_replace("#username", $value_userposi['useri'], $message_b_en);
												$message_b_en = str_replace("#email", $value_userposi['email'], $message_b_en);
												$message_b_en = str_replace("#coursename", $cname, $message_b_en);
												$message_b_en = str_replace("#link_frontend", base_url() . "coursemain/all_courses", $message_b_en);
												$message_b_en = str_replace("#date", $date, $message_b_en);
												$message_b_en = str_replace("#time", date('H:i'), $message_b_en);
												$message_b_en = str_replace("#perioddate", $period, $message_b_en);
												$message_b_en = str_replace("#image", $img_val, $message_b_en);
												$message_b_en = str_replace("#durationofstudy", $cos_hour, $message_b_en);
												$message_b_en = str_replace("#companyname", $fetch_company['com_code'], $message_b_en);
											}
											// $lang = "english";
											//echo $message_b_en;
											if ($lang == "thai") {
												$this->db->sendEmail($value_userposi['email'], $message_b_th, $subject_th, $fetch_setmail);
											} else {
												$this->db->sendEmail($value_userposi['email'], $message_b_en, $subject_en, $fetch_setmail);
											}
										}
									}
								}
							}
							# code...
						}
					}
				}
			}


			$msg = "2";
		}
		echo $msg;
	}

	public function approve_survey_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$msg = "";
		if (countArray($_REQUEST) > 0) {

			$chkis_sendmail = 0;
			$arr_update = array(
				'sv_id' => $_REQUEST['sv_id'],
				'sva_approve' => '1',
				'sva_createby' => $sess['u_id'],
				'sva_createdate' => date('Y-m-d H:i'),
			);
			$this->db->insert('lms_sv_approve', $arr_update);
			$data = array(
				'sv_public' => '1',
				'sv_approve' => '1',
				'sv_approveby' => $sess['u_id'],
				'sv_approvedate' => date('Y-m-d H:i')
			);
			$this->db->where('sv_id', $_REQUEST['sv_id']);
			$this->db->update('lms_sv', $data);
			$result = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
			if (countArray($result) > 0) {
				if ($result['sv_open'] != "0000-00-00 00:00:00") {
					if (date('Y-m-d') < date('Y-m-d', strtotime($result['sv_open']))) {
						$arr_update = array(
							'sv_id' => $_REQUEST['sv_id'],
							'jsvnoti_datejob' => date('Y-m-d', strtotime($result['sv_open'])),
						);
						$this->db->insert('lms_job_svnoti', $arr_update);
					} else {
						$chkis_sendmail = 1;
					}
				} else {
					$chkis_sendmail = 1;
				}
			}

			$fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
			$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			$lang = "english";
			if ($lang == "thai") {
				$sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			} else if ($lang == "english") {
				$sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			} else {
				$sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
			}
			$this->lg->record('publicSurvey', 'Approve public survey: ' . $sv_title . '(' . $_REQUEST['sv_id'] . ')');
			if ($lang == "thai") {
				$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d', strtotime($fetch_sv['sv_open'])) . $thaimonth[intval(date('m', strtotime($fetch_sv['sv_open'])))] . (date('Y', strtotime($fetch_sv['sv_open'])) + 543) . " " . date('H:i', strtotime($fetch_sv['sv_open'])) : "";
				$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d', strtotime($fetch_sv['sv_end'])) . $thaimonth[intval(date('m', strtotime($fetch_sv['sv_end'])))] . (date('Y', strtotime($fetch_sv['sv_end'])) + 543) . " " . date('H:i', strtotime($fetch_sv['sv_end'])) : "";
			} else {
				$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
				$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
			}
			$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
			$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
			$period = "Unlimited time"; //label('UnlimitedTime');
			if ($periodstart != "" && $periodend != "") {
				$period = $periodstart . " - " . $periodend;
			}
			$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
			//if($lang!="thai"){
			$date = date('d F Y');
			//}
			if ($fetch_sv['sv_createby'] != "") {
				$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_sv['sv_createby'] . '"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="8"');
				if (countArray($fetch_formatmail) > 0) {
					$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
					$subject_th = $fetch_formatmail['smf_subject_th'];
					$subject_en = $fetch_formatmail['smf_subject_en'];
					$message_th = $fetch_formatmail['smf_message_th'];
					$message_en = $fetch_formatmail['smf_message_en'];
					if ($subject_th != "") {
						$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
						$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
						$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
						$subject_th = str_replace("#coursename", $sv_title, $subject_th);
						$subject_th = str_replace("#link_frontend", base_url() . "survey/list_survey/", $subject_th);
						$subject_th = str_replace("#date", $date, $subject_th);
						$subject_th = str_replace("#time", date('H:i'), $subject_th);
						$subject_th = str_replace("#perioddate", $period, $subject_th);
						$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
					}
					if ($subject_en != "") {
						$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
						$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
						$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
						$subject_en = str_replace("#coursename", $sv_title, $subject_en);
						$subject_en = str_replace("#link_frontend", base_url() . "survey/list_survey/", $subject_en);
						$subject_en = str_replace("#date", $date, $subject_en);
						$subject_en = str_replace("#time", date('H:i'), $subject_en);
						$subject_en = str_replace("#perioddate", $period, $subject_en);
						$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
					}
					if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
						$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
					} else {
						$img_val = '';
					}
					if ($message_th != "") {
						$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
						$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
						$message_th = str_replace("#email", $fetch_user['email'], $message_th);
						$message_th = str_replace("#coursename", $sv_title, $message_th);
						$message_th = str_replace("#link_frontend", base_url() . "survey/list_survey/", $message_th);
						$message_th = str_replace("#date", $date, $message_th);
						$message_th = str_replace("#time", date('H:i'), $message_th);
						$message_th = str_replace("#perioddate", $period, $message_th);
						$message_th = str_replace("#image", $img_val, $message_th);
						$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
					}
					if ($message_en != "") {
						$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
						$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
						$message_en = str_replace("#email", $fetch_user['email'], $message_en);
						$message_en = str_replace("#coursename", $sv_title, $message_en);
						$message_en = str_replace("#link_frontend", base_url() . "survey/list_survey/", $message_en);
						$message_en = str_replace("#date", $date, $message_en);
						$message_en = str_replace("#time", date('H:i'), $message_en);
						$message_en = str_replace("#perioddate", $period, $message_en);
						$message_en = str_replace("#image", $img_val, $message_en);
						$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
					}
					$lang = "english";
					if ($lang == "thai") {
						$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
					} else {
						$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
					}
				}
			}
			if ($chkis_sendmail == 1) {
				$arr_email = array();

				$fetch_tc = $this->func_query->query_result('lms_sv_tc', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '" and svtc_isMail="0" and svtc_isDelete="0"');
				if (countArray($fetch_tc) > 0) {
					foreach ($fetch_tc as $key_tc => $value_tc) {
						$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_emp.emp_id="' . $value_tc['emp_id'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
						$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="11"');
						if (countArray($fetch_formatmail) > 0 && !in_array($fetch_user['email'], $arr_email)) {
							array_push($arr_email, $fetch_user['email']);
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
							$subject_th = $fetch_formatmail['smf_subject_th'];
							$subject_en = $fetch_formatmail['smf_subject_en'];
							$message_th = $fetch_formatmail['smf_message_th'];
							$message_en = $fetch_formatmail['smf_message_en'];
							if ($subject_th != "") {
								$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
								$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
								$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
								$subject_th = str_replace("#coursename", $sv_title, $subject_th);
								$subject_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $subject_th);
								$subject_th = str_replace("#date", $date, $subject_th);
								$subject_th = str_replace("#time", date('H:i'), $subject_th);
								$subject_th = str_replace("#perioddate", $period, $subject_th);
								$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
							}
							if ($subject_en != "") {
								$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
								$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
								$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
								$subject_en = str_replace("#coursename", $sv_title, $subject_en);
								$subject_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $subject_en);
								$subject_en = str_replace("#date", $date, $subject_en);
								$subject_en = str_replace("#time", date('H:i'), $subject_en);
								$subject_en = str_replace("#perioddate", $period, $subject_en);
								$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
							}
							if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
								$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
							} else {
								$img_val = '';
							}
							if ($message_th != "") {
								$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
								$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
								$message_th = str_replace("#email", $fetch_user['email'], $message_th);
								$message_th = str_replace("#coursename", $sv_title, $message_th);
								$message_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $message_th);
								$message_th = str_replace("#date", $date, $message_th);
								$message_th = str_replace("#time", date('H:i'), $message_th);
								$message_th = str_replace("#perioddate", $period, $message_th);
								$message_th = str_replace("#image", $img_val, $message_th);
								$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
							}
							if ($message_en != "") {
								$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
								$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
								$message_en = str_replace("#email", $fetch_user['email'], $message_en);
								$message_en = str_replace("#coursename", $sv_title, $message_en);
								$message_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $message_en);
								$message_en = str_replace("#date", $date, $message_en);
								$message_en = str_replace("#time", date('H:i'), $message_en);
								$message_en = str_replace("#perioddate", $period, $message_en);
								$message_en = str_replace("#image", $img_val, $message_en);
								$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
							}
							$lang = "english";
							if ($lang == "thai") {
								$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
							} else {
								$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
							}
						}
					}
				}

				$fetch_chk_position = $this->func_query->query_result('lms_sv_pm', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
				if (countArray($fetch_chk_position) > 0) {
					$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="16"');
					foreach ($fetch_chk_position as $key_chk_position => $value_chk_position) {
						if (countArray($fetch_formatmail) > 0) {
							$fetch_userposi = $this->func_query->query_result('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.posi_id="' . $value_chk_position['posi_id'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
							if (countArray($fetch_userposi) > 0) {
								foreach ($fetch_userposi as $key_userposi => $value_userposi) {
									if (!in_array($value_userposi['email'], $arr_email)) {
										array_push($arr_email, $value_userposi['email']);
										$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_userposi['com_id'] . '"');
										$subject_th = $fetch_formatmail['smf_subject_th'];
										$subject_en = $fetch_formatmail['smf_subject_en'];
										$message_th = $fetch_formatmail['smf_message_th'];
										$message_en = $fetch_formatmail['smf_message_en'];
										if ($subject_th != "") {
											$subject_th = str_replace("#fullname", $value_userposi['fullname_th'], $subject_th);
											$subject_th = str_replace("#username", $value_userposi['useri'], $subject_th);
											$subject_th = str_replace("#email", $value_userposi['email'], $subject_th);
											$subject_th = str_replace("#coursename", $sv_title, $subject_th);
											$subject_th = str_replace("#link_frontend", base_url() . "survey", $subject_th);
											$subject_th = str_replace("#date", $date, $subject_th);
											$subject_th = str_replace("#time", date('H:i'), $subject_th);
											$subject_th = str_replace("#perioddate", $period, $subject_th);
											$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
										}
										if ($subject_en != "") {
											$subject_en = str_replace("#fullname", $value_userposi['fullname_en'], $subject_en);
											$subject_en = str_replace("#username", $value_userposi['useri'], $subject_en);
											$subject_en = str_replace("#email", $value_userposi['email'], $subject_en);
											$subject_en = str_replace("#coursename", $sv_title, $subject_en);
											$subject_en = str_replace("#link_frontend", base_url() . "survey", $subject_en);
											$subject_en = str_replace("#date", $date, $subject_en);
											$subject_en = str_replace("#time", date('H:i'), $subject_en);
											$subject_en = str_replace("#perioddate", $period, $subject_en);
											$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
										}
										if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
											$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
										} else {
											$img_val = '';
										}
										if ($message_th != "") {
											$message_th = str_replace("#fullname", $value_userposi['fullname_th'], $message_th);
											$message_th = str_replace("#username", $value_userposi['useri'], $message_th);
											$message_th = str_replace("#email", $value_userposi['email'], $message_th);
											$message_th = str_replace("#coursename", $sv_title, $message_th);
											$message_th = str_replace("#link_frontend", base_url() . "survey", $message_th);
											$message_th = str_replace("#date", $date, $message_th);
											$message_th = str_replace("#time", date('H:i'), $message_th);
											$message_th = str_replace("#perioddate", $period, $message_th);
											$message_th = str_replace("#image", $img_val, $message_th);
											$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
										}
										if ($message_en != "") {
											$message_en = str_replace("#fullname", $value_userposi['fullname_en'], $message_en);
											$message_en = str_replace("#username", $value_userposi['useri'], $message_en);
											$message_en = str_replace("#email", $value_userposi['email'], $message_en);
											$message_en = str_replace("#coursename", $sv_title, $message_en);
											$message_en = str_replace("#link_frontend", base_url() . "survey", $message_en);
											$message_en = str_replace("#date", $date, $message_en);
											$message_en = str_replace("#time", date('H:i'), $message_en);
											$message_en = str_replace("#perioddate", $period, $message_en);
											$message_en = str_replace("#image", $img_val, $message_en);
											$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
										}
										$lang = "english";
										if ($lang == "thai") {
											$this->db->sendEmail($value_userposi['email'], $message_th, $subject_th, $fetch_setmail);
										} else {
											$this->db->sendEmail($value_userposi['email'], $message_en, $subject_en, $fetch_setmail);
										}
									}
								}
							}
						}
						# code...
					}
				}
			}

			$msg = "2";
		}
		echo $msg;
	}

	public function public_survey_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$msg = "";
		if (countArray($_REQUEST) > 0) {
			$arr_update = array(
				'sv_id' => $_REQUEST['sv_id'],
				'sva_approve' => '2',
				'sva_createby' => $sess['u_id'],
				'sva_createdate' => date('Y-m-d H:i'),
			);
			$this->db->insert('lms_sv_approve', $arr_update);
			$arr_update = array(
				'sv_id' => $_REQUEST['sv_id'],
				'sv_public' => '1',
				'sv_modifiedby' => $sess['u_id'],
				'sv_modifieddate' => date('Y-m-d H:i'),
			);
			$this->db->where('sv_id', $_REQUEST['sv_id']);
			$this->db->update('lms_sv', $arr_update);

			$fetch_com = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $sess['com_id'] . '"');
			if ($fetch_com['com_admin'] == "com_central") {
				$fetch_approver = $this->func_query->query_result('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.ug_id="6" and u_isDelete="0" and lms_emp.com_id="' . $sess['com_id'] . '" and lms_emp.emp_isDelete="0"');
			} else {
			}
			$lang = "english";
			$arr_user = array();
			$fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
			$fetch_approver = array();
			if (countArray($fetch_sv) > 0 && $fetch_sv['sv_userapprove'] != "") {
				$arr_user = explode(',', $fetch_sv['sv_userapprove']);
				if (countArray($arr_user) > 0) {
					for ($i = 0; $i < countArray($arr_user); $i++) {
						$fetch_data = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'u_isDelete="0" and lms_emp.emp_id="' . $arr_user[$i] . '" and lms_emp.emp_isDelete="0"');
						if (countArray($fetch_data) > 0) {
							array_push($fetch_approver, $fetch_data);
						}
					}
				}
			}
			if (isset($fetch_approver) && countArray($fetch_approver) > 0) {
				if ($lang == "thai") {
					$sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
				} else if ($lang == "english") {
					$sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
					$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
				} else {
					$sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
				}
				$this->lg->record('publicSurvey', 'Request approve public survey: ' . $sv_title . '(' . $_REQUEST['sv_id'] . ')');
				// if($lang=="thai"){
				// $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_sv['sv_open'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_open'])))].(date('Y',strtotime($fetch_sv['sv_open']))+543)." ".date('H:i',strtotime($fetch_sv['sv_open'])):"";
				// $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_sv['sv_end'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_end'])))].(date('Y',strtotime($fetch_sv['sv_end']))+543)." ".date('H:i',strtotime($fetch_sv['sv_end'])):"";
				// }else{
				// $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_open'])):"";
				// $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_end'])):"";
				// }
				$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
				$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
				$period = "Unlimited time"; //label('UnlimitedTime');
				if ($periodstart != "" && $periodend != "") {
					$period = $periodstart . " - " . $periodend;
				}
				$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
				//if($lang!="thai"){
				$date = date('d F Y');
				//}
				foreach ($fetch_approver as $key_approve => $value_approve) {
					$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
					$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="7"');
					if (countArray($fetch_formatmail) > 0) {
						$subject_th = $fetch_formatmail['smf_subject_th'];
						$subject_en = $fetch_formatmail['smf_subject_en'];
						$message_th = $fetch_formatmail['smf_message_th'];
						$message_en = $fetch_formatmail['smf_message_en'];
						$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_approve['com_id'] . '"');
						if ($subject_th != "") {
							$subject_th = str_replace("#fullname", $value_approve['fullname_th'], $subject_th);
							$subject_th = str_replace("#username", $value_approve['useri'], $subject_th);
							$subject_th = str_replace("#email", $value_approve['email'], $subject_th);
							$subject_th = str_replace("#coursename", $sv_title, $subject_th);
							$subject_th = str_replace("#link_frontend", base_url() . "survey/demo/" . $_REQUEST['sv_id'], $subject_th);
							$subject_th = str_replace("#date", $date, $subject_th);
							$subject_th = str_replace("#time", date('H:i'), $subject_th);
							$subject_th = str_replace("#perioddate", $period, $subject_th);
							$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
						}
						if ($subject_en != "") {
							$subject_en = str_replace("#fullname", $value_approve['fullname_en'], $subject_en);
							$subject_en = str_replace("#username", $value_approve['useri'], $subject_en);
							$subject_en = str_replace("#email", $value_approve['email'], $subject_en);
							$subject_en = str_replace("#coursename", $sv_title, $subject_en);
							$subject_en = str_replace("#link_frontend", base_url() . "survey/demo/" . $_REQUEST['sv_id'], $subject_en);
							$subject_en = str_replace("#date", $date, $subject_en);
							$subject_en = str_replace("#time", date('H:i'), $subject_en);
							$subject_en = str_replace("#perioddate", $period, $subject_en);
							$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
						}
						if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
							$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
						} else {
							$img_val = '';
						}
						if ($message_th != "") {
							$message_th = str_replace("#fullname", $value_approve['fullname_th'], $message_th);
							$message_th = str_replace("#username", $value_approve['useri'], $message_th);
							$message_th = str_replace("#email", $value_approve['email'], $message_th);
							$message_th = str_replace("#coursename", $sv_title, $message_th);
							$message_th = str_replace("#link_frontend", base_url() . "survey/demo/" . $_REQUEST['sv_id'], $message_th);
							$message_th = str_replace("#date", $date, $message_th);
							$message_th = str_replace("#time", date('H:i'), $message_th);
							$message_th = str_replace("#perioddate", $period, $message_th);
							$message_th = str_replace("#image", $img_val, $message_th);
							$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
						}
						if ($message_en != "") {
							$message_en = str_replace("#fullname", $value_approve['fullname_en'], $message_en);
							$message_en = str_replace("#username", $value_approve['useri'], $message_en);
							$message_en = str_replace("#email", $value_approve['email'], $message_en);
							$message_en = str_replace("#coursename", $sv_title, $message_en);
							$message_en = str_replace("#link_frontend", base_url() . "survey/demo/" . $_REQUEST['sv_id'], $message_en);
							$message_en = str_replace("#date", $date, $message_en);
							$message_en = str_replace("#time", date('H:i'), $message_en);
							$message_en = str_replace("#perioddate", $period, $message_en);
							$message_en = str_replace("#image", $img_val, $message_en);
							$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
						}
						$lang = "english";
						if ($lang == "thai") {
							$this->db->sendEmail($value_approve['email'], $message_th, $subject_th, $fetch_setmail);
						} else {
							$this->db->sendEmail($value_approve['email'], $message_en, $subject_en, $fetch_setmail);
						}
					}
				}
			}


			$msg = "2";
		}
		echo $msg;
	}

	public function delete_department_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'dep_isDelete' => '1',
				'dep_modifiedby' => $sess['u_id'],
				'dep_modifieddate' => date('Y-m-d H:i')
			);
			$msg = $this->manage->update_department($data, $_REQUEST['dep_id_delete']);
		}
		echo $msg;
	}

	public function delete_svtc_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$output = array();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'svtc_isDelete' => '1',
				'svtc_modifiedby' => $sess['u_id'],
				'svtc_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('svtc_id', $_REQUEST['svtc_id']);
			$this->db->update('lms_sv_tc', $data);
			$fetchchk = $this->func_query->query_row('lms_sv_tc', '', '', '', 'svtc_id="' . $_REQUEST['svtc_id'] . '"');
			$data = array(
				'tc_isDelete' => '1',
				'tc_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('sv_id', $fetchchk['sv_id']);
			$this->db->where('emp_id', $fetchchk['emp_id']);
			$this->db->update('lms_svde_tc', $data);
			$output['status'] = "2";
		} else {
			$output['status'] = "0";
		}
		echo json_encode($output);
	}

	public function reset_svtc_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$output = array();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'svtc_isDelete' => '1',
				'svtc_modifiedby' => $sess['u_id'],
				'svtc_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('svtc_id', $_REQUEST['svtc_id']);
			$this->db->update('lms_sv_tc', $data);
			$fetchchk = $this->func_query->query_row('lms_sv_tc', '', '', '', 'svtc_id="' . $_REQUEST['svtc_id'] . '"');
			$data = array(
				'tc_isDelete' => '1',
				'tc_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('sv_id', $fetchchk['sv_id']);
			$this->db->where('emp_id', $fetchchk['emp_id']);
			$this->db->update('lms_svde_tc', $data);
			$data_insert = array(
				'sv_id' => $fetchchk['sv_id'],
				'emp_id' => $fetchchk['emp_id'],
				'svtc_createby' => $sess['u_id'],
				'svtc_createdate' => date('Y-m-d H:i'),
				'svtc_modifiedby' => $sess['u_id'],
				'svtc_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->insert('lms_sv_tc', $data_insert);
			$output['status'] = "2";
		} else {
			$output['status'] = "0";
		}
		echo json_encode($output);
	}

	public function delete_position_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'posi_isDelete' => '1',
				'posi_modifiedby' => $sess['u_id'],
				'posi_modifieddate' => date('Y-m-d H:i')
			);
			$msg = $this->manage->update_position_detail($data, $_REQUEST['posi_id_delete']);
		}
		echo $msg;
	}

	public function delete_groupuser_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'ug_isDelete' => '1',
				'ug_modifiedby' => $sess['u_id'],
				'ug_modifieddate' => date('Y-m-d H:i')
			);
			$msg = $this->manage->update_groupuser($data, $_REQUEST['ug_id_delete']);
		}
		echo $msg;
	}

	public function delete_quiz()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0 && isset($sess['u_id'])) {
			$data = array(
				'quiz_isDelete' => '1',
				'quiz_modifiedby' => $sess['u_id'],
				'quiz_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('qiz_id', $_REQUEST['qiz_id']);
			$this->db->update('lms_qiz', $data);

			$fetch = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $_REQUEST['qiz_id'] . '"');
			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch['cos_id'] . '"');

			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$cname = "";
			$qizname = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$cname = $fetch_cos['cname_th'];
					$qizname = $fetch['quiz_name_th'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_eng'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$cname = $fetch_cos['cname_eng'];
					$qizname = $fetch['quiz_name_eng'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_th'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$cname = $fetch_cos['cname_jp'];
					$qizname = $fetch['quiz_name_jp'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_eng'];
					}
					if ($qizname == "") {
						$qizname = $fetch['quiz_name_th'];
					}
				}
			}
			$this->lg->record('quiz', 'Delete quiz ' . $qizname . '(' . $_REQUEST['qiz_id'] . ') of Course: ' . $cname . '(' . $fetch['cos_id'] . ')');
			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_ques()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$fetch_ques = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '"');
			$fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $fetch_ques['qiz_id'] . '"');
			if (countArray($fetch_qiz) > 0) {
				$quiz_numofshown = intval($fetch_qiz['quiz_numofshown']) - 1;
				if ($quiz_numofshown < 0) {
					$quiz_numofshown = 0;
				}
				$dataupdate = array(
					'quiz_numofshown' => $quiz_numofshown,
					'quiz_modifiedby' => $sess['u_id'],
					'quiz_modifieddate' => date('Y-m-d H:i')
				);
				$this->db->where('qiz_id', $fetch_ques['qiz_id']);
				$this->db->update('lms_qiz', $dataupdate);
			}

			$data = array(
				'ques_isDelete' => '1',
				'ques_modifiedby' => $sess['u_id'],
				'ques_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('ques_id', $_REQUEST['ques_id']);
			$this->db->update('lms_ques', $data);

			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch_qiz['cos_id'] . '"');
			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$qizname = "";
			$quesname = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$qizname = $fetch_qiz['quiz_name_th'];
					$quesname = $fetch_ques['ques_name_th'];
				} else {
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_eng'];
					}
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_jp'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_eng'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$qizname = $fetch_qiz['quiz_name_eng'];
					$quesname = $fetch_ques['ques_name_eng'];
				} else {
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_th'];
					}
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_jp'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_th'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$qizname = $fetch_qiz['quiz_name_jp'];
					$quesname = $fetch_ques['ques_name_jp'];
				} else {
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_eng'];
					}
					if ($qizname == "") {
						$qizname = $fetch_qiz['quiz_name_th'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_eng'];
					}
					if ($quesname == "") {
						$quesname = $fetch_ques['ques_name_th'];
					}
				}
			}
			$this->lg->record('Quiz', 'Delete question: ' . htmlentities($quesname) . '(' . $_REQUEST['ques_id'] . ') of Quiz : ' . $qizname . '(' . $fetch_ques['qiz_id'] . ')');

			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch_qiz['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_survey()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'sv_isDelete' => '1',
				'sv_modifiedby' => $sess['u_id'],
				'sv_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('sv_id', $_REQUEST['sv_id']);
			$this->db->update('lms_survey', $data);

			$fetch = $this->func_query->query_row('lms_survey', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');

			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch['cos_id'] . '"');
			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$cname = "";
			$sv_title = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$cname = $fetch_cos['cname_th'];
					$sv_title = $fetch['sv_title_th'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_eng'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$cname = $fetch_cos['cname_eng'];
					$sv_title = $fetch['sv_title_eng'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_th'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$cname = $fetch_cos['cname_jp'];
					$sv_title = $fetch['sv_title_jp'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_eng'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_th'];
					}
				}
			}
			$this->lg->record('survey', 'Delete survey: ' . $sv_title . '(' . $fetch['sv_id'] . ') in course ' . $cname . '(' . $fetch['cos_id'] . ')');
			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function update_status_learner()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");

		$fetch_enroll = $this->func_query->query_result(
			'lms_cos_enroll',
			'lms_cos',
			'lms_cos.cos_id = lms_cos_enroll.cos_id',
			'',
			'lms_cos.cos_id in (SELECT lms_les.cos_id from lms_les left join lms_med on lms_les.les_id = lms_med.lessons_id) and lms_cos_enroll.cos_id NOT IN (select lms_qiz.cos_id from lms_qiz) and lms_cos_enroll.cosen_status_sub != 1 and lms_cos_enroll.cosen_firsttime != "0000-00-00 00:00:00" and lms_cos.cos_isDelete = 0'
		);
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		if (countArray($fetch_enroll) > 0) {
			foreach ($fetch_enroll as $key => $value) {
				$chkles = $this->func_query->numrows('lms_les', '', '', '', 'lms_les.cos_id = "' . $value['cos_id'] . '" and les_isDelete=0');

				$chklestc = $this->func_query->numrows('lms_les_tc', '', '', '', 'lms_les_tc.les_id in (SELECT lms_les.les_id from lms_les left join lms_med on lms_les.les_id = lms_med.lessons_id where lms_les.cos_id = "' . $value['cos_id'] . '") and lms_les_tc.cosen_id = "' . $value['cosen_id'] . '" and lms_les_tc.learn_status = "2"');
				if ($chkles != $chklestc) {
					unset($fetch_enroll[$key]);
				}
			}
		}

		if (countArray($fetch_enroll) > 0) {
			foreach ($fetch_enroll as $key => $value) {
				$arr_update = array(
					'cosen_finishtime' => date('Y-m-d H:i:s'),
					'cosen_status_sub' => 1
				);
				$this->db->where('cosen_id = "' . $value['cosen_id'] . '"');
				$this->db->update('lms_cos_enroll', $arr_update);
			}
		}
		$msg = "2";
		echo $msg;
	}

	public function delete_survey_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'svde_isDelete' => '1',
				'svde_modifiedby' => $sess['u_id'],
				'svde_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('svde_id', $_REQUEST['svde_id']);
			$this->db->update('lms_survey_de', $data);
			$fetch = $this->func_query->query_row('lms_survey', 'lms_survey_de', 'lms_survey_de.sv_id = lms_survey.sv_id', '', 'lms_survey_de.svde_id="' . $_REQUEST['svde_id'] . '"');

			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch['cos_id'] . '"');

			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$cname = "";
			$sv_title = "";
			$question = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$cname = $fetch_cos['cname_th'];
					$sv_title = $fetch['sv_title_th'];
					$svde_heading_th = $fetch['svde_heading_th'] != "" ? "[" . $fetch['svde_heading_th'] . "] " : "";
					$question = $svde_heading_th . $fetch['svde_detail_th'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_eng'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_jp'];
					}
					if ($question == "") {
						$svde_heading_eng = $fetch['svde_heading_eng'] != "" ? "[" . $fetch['svde_heading_eng'] . "] " : "";
						$question = $svde_heading_eng . $fetch['svde_detail_eng'];
					}
					if ($question == "") {
						$svde_heading_jp = $fetch['svde_heading_jp'] != "" ? "[" . $fetch['svde_heading_jp'] . "] " : "";
						$question = $svde_heading_jp . $fetch['svde_detail_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$cname = $fetch_cos['cname_eng'];
					$sv_title = $fetch['sv_title_eng'];
					$svde_heading_eng = $fetch['svde_heading_eng'] != "" ? "[" . $fetch['svde_heading_eng'] . "] " : "";
					$question = $svde_heading_eng . $fetch['svde_detail_eng'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_th'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_jp'];
					}
					if ($question == "") {
						$svde_heading_th = $fetch['svde_heading_th'] != "" ? "[" . $fetch['svde_heading_th'] . "] " : "";
						$question = $svde_heading_th . $fetch['svde_detail_th'];
					}
					if ($question == "") {
						$svde_heading_jp = $fetch['svde_heading_jp'] != "" ? "[" . $fetch['svde_heading_jp'] . "] " : "";
						$question = $svde_heading_jp . $fetch['svde_detail_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$cname = $fetch_cos['cname_jp'];
					$sv_title = $fetch['sv_title_jp'];
					$svde_heading_jp = $fetch['svde_heading_jp'] != "" ? "[" . $fetch['svde_heading_jp'] . "] " : "";
					$question = $svde_heading_jp . $fetch['svde_detail_jp'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_eng'];
					}
					if ($sv_title == "") {
						$sv_title = $fetch['sv_title_th'];
					}
					if ($question == "") {
						$svde_heading_eng = $fetch['svde_heading_eng'] != "" ? "[" . $fetch['svde_heading_eng'] . "] " : "";
						$question = $svde_heading_eng . $fetch['svde_detail_eng'];
					}
					if ($question == "") {
						$svde_heading_th = $fetch['svde_heading_th'] != "" ? "[" . $fetch['svde_heading_th'] . "] " : "";
						$question = $svde_heading_th . $fetch['svde_detail_th'];
					}
				}
			}
			$this->lg->record('survey', 'Delete question: ' . $question . '(' . $_REQUEST['svde_id'] . ') of Survey : ' . $sv_title . '(' . $fetch['sv_id'] . ')');
			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_lesson()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		if (countArray($_REQUEST) > 0) {

			$fetch_les = $this->func_query->query_row("lms_les", '', '', '', 'les_id = "' . $_REQUEST['les_id'] . '"');
			if ($fetch_les['les_type'] == "2") {
				$path = $this->course->check_scorm($_REQUEST['les_id']);
				$newDir = ROOT_DIR . "uploads/scorm/" . $path;
				function emptyDir($dir)
				{
					if (is_dir($dir)) {
						$scn = scandir($dir);
						foreach ($scn as $files) {
							if ($files !== '.') {
								if ($files !== '..') {
									if (!is_dir($dir . '/' . $files)) {
										audit_unlink($dir . '/' . $files);
									} else {
										emptyDir($dir . '/' . $files);
										rmdir($dir . '/' . $files);
									}
								}
							}
						}
					}
				}
				emptyDir($newDir);
				rmdir($newDir);
				$this->course->delete_data($_REQUEST['les_id'], 'lessons_id', 'lms_scm');
			}
			$data = array(
				'les_isDelete' => '1',
				'les_modifiedby' => $sess['u_id'],
				'les_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('les_id', $_REQUEST['les_id']);
			$this->db->update('lms_les', $data);

			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch_les['cos_id'] . '"');
			$cos_lang = explode(',', $fetch_cos['cos_lang']);
			$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
			$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
			$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
			$cname = "";
			$lesname = "";
			if ($lang == "thai") {
				if ($fetch_cos['isTH'] == "1") {
					$cname = $fetch_cos['cname_th'];
					$lesname = $fetch_les['les_name_th'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_eng'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_jp'];
					}
				}
			} else if ($lang == "english") {
				if ($fetch_cos['isENG'] == "1") {
					$cname = $fetch_cos['cname_eng'];
					$lesname = $fetch_les['les_name_eng'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_jp'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_th'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_jp'];
					}
				}
			} else {
				if ($fetch_cos['isJP'] == "1") {
					$cname = $fetch_cos['cname_jp'];
					$lesname = $fetch_les['les_name_jp'];
				} else {
					if ($cname == "") {
						$cname = $fetch_cos['cname_eng'];
					}
					if ($cname == "") {
						$cname = $fetch_cos['cname_th'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_eng'];
					}
					if ($lesname == "") {
						$lesname = $fetch_les['les_name_th'];
					}
				}
			}

			$this->lg->record('lesson', 'Delete lesson: ' . $lesname . '(' . $_REQUEST['les_id'] . ') of course: ' . $cname . '(' . $fetch_les['cos_id'] . ')' . '');

			$arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
			$this->db->where('cos_id', $fetch_les['cos_id']);
			$this->db->update('lms_cos', $arr_update);
			$msg = "2";
		}
		echo $msg;
	}

	public function delete_user_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$data = array(
				'emp_isDelete' => '1',
			);
			$this->db->where('emp_id', $_REQUEST['emp_id_delete']);
			$this->db->update('lms_emp', $data);
			$datausp = array(
				'u_isDelete' => '1',
			);
			$this->db->where('emp_id', $_REQUEST['emp_id_delete']);
			$this->db->update('lms_usp', $datausp);
			$msg = "2";
		}
		echo $msg;
	}

	public function update_company_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['com_id_update'], 'lms_company', 'com_id');
			echo json_encode($result);
		}
	}

	public function update_conmsg_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['conmsg_id'], 'lms_confirmmsg', 'conmsg_id');
			echo json_encode($result);
		}
	}

	public function update_coursegroup_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['cg_id_update'], 'lms_cog', 'cg_id');
			echo json_encode($result);
		}
	}

	public function update_department_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['dep_id_update'], 'lms_depart', 'dep_id');
			echo json_encode($result);
		}
	}

	public function rechk_headcol()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_chkheadcol($_REQUEST['ug_id']);
			echo json_encode($result);
		}
	}

	public function rechk_headcol_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_chkheadcol_user($_REQUEST['u_id']);
			echo json_encode($result);
		}
	}

	public function update_groupuser_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['ug_id_update'], 'lms_usp_gp', 'ug_id');
			echo json_encode($result);
		}
	}

	public function update_user_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['u_id_update'], 'lms_usp', 'u_id');
			$result['expiredate'] = date('Y-m-d', strtotime($result['expiredate']));



			if (!checkDateIsNull($result['employ_date'])) {
				$result['employ_date_var'] = $result['employ_date'];
				$result['employ_date'] = date('d/m/Y', strtotime($result['employ_date']));
			} else {
				$result['employ_date'] = "";
				$result['employ_date_var'] = "";
			}

			if (!checkDateIsNull($result['inactivedate'])) {
				$result['inactivedate_var'] = date('Y-m-d', strtotime($result['inactivedate']));
				$result['inactivedate'] = date('d/m/Y', strtotime($result['inactivedate']));
			} else {
				$result['inactivedate'] = "";
				$result['inactivedate_var'] = "";
			}




			if (!checkDatetimeIsNull($result['u_firstdate'])) {
				$result['u_firstdate_var'] = date('Y-m-d', strtotime($result['u_firstdate']));
				$result['u_firstdate'] = date('d/m/Y', strtotime($result['u_firstdate']));
			} else {
				$result['u_firstdate'] = "";
				$result['u_firstdate_var'] = "";
			}
			echo json_encode($result);
		}
	}

	public function update_position_detail()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['posi_id'], 'lms_position', 'posi_id');
			echo json_encode($result);
		}
	}

	public function update_qrcode_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['qr_id_update'], 'lms_qrcode', 'qr_id');
			echo json_encode($result);
		}
	}

	public function recheckcompany()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$com_id = $this->input->post('com_id');
		$dep_id = $this->input->post('dep_id');
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$org_select = $this->manage->checkdepartment($com_id);
		if (countArray($org_select) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			foreach ($org_select as $key) {
				$select_val = "";
				if ($key['dep_id'] == $dep_id) {
					$select_val = "selected";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['dep_id'] . "' " . $select_val . ">" . $key['dep_name_th'] . "</option>";
				} else {
					echo "<option value='" . $key['dep_id'] . "' " . $select_val . ">" . $key['dep_name_en'] . "</option>";
				}
			}
			$this->manage->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}

	public function rechk_funcdashboard()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$ug_id = isset($_REQUEST['ug_id']) ? $_REQUEST['ug_id'] : "";
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		$fetch_loop = $this->func_query->query_result('lms_func_dashboard', '', '', '', '');
		if (countArray($fetch_loop) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			$numloop = 1;
			foreach ($fetch_loop as $key) {
				$fetch_chk = $this->func_query->query_row('lms_role_fd', '', '', '', 'ug_id="' . $ug_id . '" and fd_id="' . $key['fd_id'] . '"');
				$select_val = "";
				if (countArray($fetch_chk) > 0) {
					$select_val = "selected";
				}
				$numloop++;
				$fd_name = $lang == "thai" ? $key['fd_name_th'] : $key['fd_name_eng'];
				echo "<option value='" . $key['fd_id'] . "' " . $select_val . ">" . $fd_name . "</option>";
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}

	public function recheckusergroup()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$com_id = $this->input->post('com_id');
		$ug_id = $this->input->post('ug_id');
		$user = $this->session->userdata('user');
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();

		$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id = "' . $com_id . '"');
		$org_select = $this->manage->checkusergroup($com_id);
		if (countArray($org_select) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			$num_loop = 1;
			foreach ($org_select as $key) {
				if ($fetch_company['com_admin'] == "com_central") {
					if ($user['ug_id'] > 1) {
						if ($key['ug_id'] != "1") {
							$select_val = "";
							if ($key['ug_id'] == $ug_id) {
								$select_val = "selected";
							} else {
								if ($num_loop == 1) {
									$select_val = "selected";
								}
							}

							if ($lang == "thai") {
								echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_th'] . "</option>";
							} else {
								echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_en'] . "</option>";
							}
						}
					} else {
						$select_val = "";
						if ($key['ug_id'] == $ug_id) {
							$select_val = "selected";
						} else {
							if ($num_loop == 1) {
								$select_val = "selected";
							}
						}

						if ($lang == "thai") {
							echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_th'] . "</option>";
						} else {
							echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_en'] . "</option>";
						}
					}
				} else {
					$select_val = "";
					if ($key['ug_id'] == $ug_id) {
						$select_val = "selected";
					} else {
						if ($num_loop == 1) {
							$select_val = "selected";
						}
					}

					if ($lang == "thai") {
						echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_th'] . "</option>";
					} else {
						echo "<option value='" . $key['ug_id'] . "' " . $select_val . ">" . $key['ug_name_en'] . "</option>";
					}
				}
				$num_loop++;
			}
			$this->manage->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}

	public function recheckdepartment()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$dep_id = $this->input->post('dep_id');
		$posi_id = $this->input->post('posi_id');
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$posi_select = $this->manage->checkposition($dep_id);
		if (countArray($posi_select) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			foreach ($posi_select as $key) {
				$select_val = "";
				if ($key['posi_id'] == $posi_id) {
					$select_val = "selected";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['posi_id'] . "' " . $select_val . ">" . $key['posi_name_th'] . "</option>";
				} else {
					echo "<option value='" . $key['posi_id'] . "' " . $select_val . ">" . $key['posi_name_en'] . "</option>";
				}
			}
			$this->manage->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}


	public function manageLevel()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "manage/manageLevel";

		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['role'] = $user['role'];

			if (!in_array($arr['role'], array("superadmin", "admintis", "admin"))) {
				redirect(base_url() . 'dashboard', 'refresh');
			}

			if (isset($_POST['edit'])) {
				$this->load->model('Manage_model', 'manage', false);
				$this->manage->loadDB();
				$arr['emp'] = $this->manage->getEmp($_POST['edit']);
				$arr['emp']['emp_name'] = $arr['emp']['prefix'] . $arr['emp']['fname'] . ' ' . $arr['emp']['lname'];
				$lead_name = $this->manage->getEmp($arr['emp']['lead'], $lang);
				$arr['emp']['lead_name'] = $lead_name['prefix'] . $lead_name['fname'] . ' ' . $lead_name['lname'];
				$this->manage->closeDB();

				$this->load->model('Footer_model', 'foot', false);
				$this->foot->loadDB();
				$arr['foote'] = $this->foot->getfooter();
				$this->foot->closeDB();

				$this->load->view('frontend/managelevel', $arr);
			} else {
				$this->load->model('Manage_model', 'manage', false);
				$this->manage->loadDB();
				$arr['users'] = $this->manage->getEmps($lang);
				$this->manage->closeDB();

				$this->load->model('Footer_model', 'foot', false);
				$this->foot->loadDB();
				$arr['foote'] = $this->foot->getfooter();
				$this->foot->closeDB();

				$this->load->view('frontend/manage', $arr);
			}
		}
	}


	public function editPass()
	{
		if (isset($_POST['cancel'])) {
			redirect(base_url() . 'manage', 'refresh');
		}

		$user = $this->security->xss_clean($_POST['done']);
		$pass = $this->security->xss_clean($_POST['pass']);

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$pass = hash('sha256', $pass);
		$this->manage->editPass($user, $pass);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->record('manage', 'change ' . $user . '\'s password.');
		$this->manage->closeDB();

		redirect(base_url() . 'manage', 'refresh');
	}

	public function editLevel()
	{
		if (isset($_POST['cancel'])) {
			redirect(base_url() . 'manage/manageLevel', 'refresh');
		}

		$emp_c = $this->security->xss_clean($_POST['emp']);
		$lead = $this->security->xss_clean($_POST['lead']);
		$level = $this->security->xss_clean($_POST['level']);

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->manage->editLevel($emp_c, $lead, $level);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->record('manage', 'edit ' . $emp_c . '\'s leader to ' . $lead . ' and level to ' . $level . '.');
		$this->manage->closeDB();

		redirect(base_url() . 'manage', 'refresh');
	}

	public function checkLead()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$emp_c = $_POST['emp'];
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['text'] = $this->manage->checkLead($emp_c, $lang);
		$this->manage->closeDB();
		echo json_encode($arr);
	}

	public function checkEmpC()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$emp_c = $_POST['emp'];
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['text'] = $this->manage->checkEmpC($emp_c, $lang);
		$this->manage->closeDB();
		echo json_encode($arr);
	}

	public function checkUser()
	{
		$useri = $_POST['user'];
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['text'] = $this->manage->checkUser($useri);
		$this->manage->closeDB();
		echo json_encode($arr);
	}
}
