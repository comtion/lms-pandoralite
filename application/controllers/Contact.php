<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

			public function form_chk($emp_id="",$lang_select="english")
			{
				$lang = $this->session->userdata("lang") == null ? $lang_select : $this->session->userdata("lang") ;
				$this->lang->load($lang,$lang);
				$arr['lang'] = $lang;
				$arr['page'] = "contact";
				$arr['emp_id'] = $emp_id;
				$this->load->model('Footer_model', 'foot', FALSE);
				$this->foot->loadDB();
				$arr['foote'] = $this->foot->getfooter();
				$this->foot->closeDB();

				$this->load->view('frontend/contact', $arr );

			}

}
