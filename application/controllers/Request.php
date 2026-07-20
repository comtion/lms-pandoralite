<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {
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
	public function index()
	{
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "request";

		$this->load->model('User_model', 'login', FALSE);
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['role'] = $user['role'];

			$this->load->model('Report_model', 'report', FALSE);
			$this->load->model('Enroll_model', 'enroll', FALSE);
			$this->enroll->loadDB();
			$arr['emp'] = $this->report->getDetail($arr['emp_c'], $lang);
			$arr['comp'] = $this->enroll->getComp($lang);
			$arr['org'] = $this->enroll->getOrg($lang);
			$this->enroll->closeDB();

			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();
			$this->load->view('frontend/request', $arr);
		}
	}
}
