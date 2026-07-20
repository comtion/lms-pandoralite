<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
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
		redirect(base_url().'dashboard', 'refresh');
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "profile";

		$this->load->model('User_model', 'login', false);
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
			$arr['user'] = $user;

			if($arr['role'] == "superadmin") {
					redirect(base_url().'dashboard', 'refresh');
			}

			$this->load->model('Profile_model', 'profile', false);
			$this->profile->loadDB();
			$arr['emp'] = $this->profile->getDetail($arr['emp_c'], $arr['lang']);
			//$lead = $this->profile->getDetail($arr['emp']['lead'], $arr['lang']);
			//$arr['emp']['lead_name'] = $lead['prefix'].$lead['fname'].' '.$lead['lname'];
			$this->profile->closeDB();

			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->view('frontend/profile', $arr );
		}
	}

	public function fetch_course_cert(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang, $lang);

		$this->load->model('Profile_model', 'profile', true);
		$this->profile->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->profile->fetch_course_cert($user) : array();
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
}
