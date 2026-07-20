<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expireuser extends CI_Controller {

  	public function index($type){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $username_firsttime = $this->session->userdata("username_firsttime");
	    $passexpire = $this->session->userdata("passexpire");
	    if(!$passexpire){
	    	redirect(base_url().'home', 'refresh');
	    }

	    $this->func_query->loadDB();
	    $u_id = isset($_REQUEST['u_id'])?$_REQUEST['u_id']:"";
		$arr['page'] = 'expireuser';
		$arr['lang'] = $lang;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();

		$arr['useron'] = $this->func_query->query_row('lms_usp','','','','useri="'.$username_firsttime.'"');


		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/expireuser', $arr );
	}
}
?>