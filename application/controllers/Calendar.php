<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {

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
		$arr['page'] = "calendar";

		$this->load->model('User_model', 'login', FALSE);
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$emp_c = $user['emp_c'];
			$arr['emp_c'] = $user['emp_c'];
			$arr['role'] = $user['role'];

			$date = isset( $_GET['date'] ) ? $_GET['date'] : date('M Y');
			$btn = isset( $_GET['btn'] ) ? $_GET['btn'] : '';
			$arr['filter'] = isset( $_GET['opt-filter'] ) ? $_GET['opt-filter'] : '';

			$this->load->model('Calendar_model', 'calendar', FALSE);
			$arr['date'] = $this->calendar->getAdjacentM($date, $btn);
			$arr['fdom'] = date('N', strtotime('first day of this month', strtotime($arr['date'])));
			$arr['dom'] = date('t', strtotime($arr['date']));
			$this->calendar->loadDB();
			if(in_array($arr['role'], array("superadmin","admintis", "admin"))) {
				$arr['courses'] = $this->calendar->getAllCos($arr['filter']);
			} else {
				$arr['courses'] = $this->calendar->getMyCos($emp_c, $arr['filter']);
			}
			// $arr['cc'] = $this->session->userdata("cc") == null ? $this->calendar->cc($arr['courses']) : $this->session->userdata("cc");
			// 
			// foreach($arr['courses'] as $course) {
			// 	if(!array_key_exists($course['ccode'], $arr['cc'])) {
			// 		$arr['cc'][$course['ccode']] = $this->calendar->getColor($course['coursetype']);
			// 	}
			// }
			// $this->session->set_userdata('cc', $arr['cc']);
			$this->calendar->closeDB();

			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->view('frontend/calendar', $arr );
		}
	}
}
