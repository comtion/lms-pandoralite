<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enroll extends CI_Controller {

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
		$this->load->model('User_model', 'login', TRUE);
		$this->load->model('Enroll_model', 'enroll', TRUE);

		$arr['lang'] = $lang;
		$arr['page'] = "enroll";
		$arr['ccode'] = $this->session->userdata('ccode');

		if($this->login->checkSession('enroll')){
			$sess = $this->session->userdata("user");
			$arr['emp_c'] = $sess['emp_c'];
			$arr['role'] = $sess['role'];

			$condition = null;
			if ($arr['role'] != 'admin'){
			$condition = array(
				'comp' => $this->input->get('comp'),
				'org' => $this->input->get('org'),
				'position' => $this->input->get('pls'),
				'orggroup' => $this->input->get('orggroup'),
				'empgroup' => $this->input->get('empgrp')
			);}

			$this->enroll->loadDB();
			$emp = $this->enroll->getEmpNow($sess['emp_c'], 'thailand');
			$arr['emps'] = $this->enroll->getEmp($emp, array() , $condition );
			$arr['comp'] = $this->enroll->getComp();
			$arr['orgName'] = $this->enroll->getOrgName();
			$arr['orgGup'] = $this->enroll->getOrgGup();
			$arr['plDesc'] = $this->enroll->getPlDesc();
			$arr['empgrpdesc'] = $this->enroll->getEmpGupDes();

			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->enroll->closeDB();
			$this->load->view('frontend/enroll', $arr );
		}
	}

	public function login()
	{
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "login";

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();

		$username = $this->input->post('inpUname');
		$password = $this->input->post('inpPwd');

		$this->load->model('User_model', 'login', TRUE);
		$this->login->loadDB();
		if($this->login->checkLogin( $username, $password )){
			$this->login->closeDB();
			$this->login->sendRedirect('enroll');
		}
		else{
			$this->login->closeDB();
			$this->login->sendLogin('enroll');
		}
	}

	public function enrolled()
	{
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "login";

		$users = $this->input->post('codes');
		$ccode = $this->input->post('ccode');

		$this->load->model('Enroll_model', 'enroll', False);
		$this->enroll->loadDB();
		$this->enroll->enrollUser($ccode, $users);
		$this->enroll->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();

		redirect(base_url().'course/detail/'.$ccode.'/'.$lang);
	}

	public function change( $type ){
		$this->session->set_userdata('lang',$type);
		redirect("","refresh");
	}
}
