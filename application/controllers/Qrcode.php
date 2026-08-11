<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcode extends CI_Controller {


	public function index()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "qrcode/create";

		redirect(base_url().'qrcode/create', 'refresh');
	}

	public function view($qr_id){
		$arr['page'] = 'qrcode/view/'.$qr_id;
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('Home_model', 'home', FALSE);
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->home->loadDB();
		$this->setting->loadDB();
        $this->manage->loadDB();
		$this->foot->loadDB();
		$qr_id = (int) $qr_id;
		$arr['data_query'] = $this->func_query->query_row('lms_qrcode','','','','qr_id = "'.$qr_id.'" and qr_isDelete = "0"');
		if (empty($arr['data_query']) || $arr['data_query']['qr_status'] != '1') {
			show_404();
			return;
		}
		$arr['foote'] = $this->foot->getfooter();

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/viewfile', $arr );
	}

	public function create()
	{
		$arr['page'] = 'qrcode/create';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['com_name'] = $lang=="thai"?$sess['com_name_th']:$sess['com_name_eng'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$arr['data_fetch'] = $this->setting->fetch_data_ECT();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
        $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
        $arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}
		$arr['com_data'] = $this->func_query->query_row('lms_company','','','','com_id="'.$sess['com_id'].'"');
			$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
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
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/qrcode_create', $arr );
	}

}
?>
