<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {

	public function index()
	{
		$arr['page'] = 'survey';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		

		if(empty($sess)){
			redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
		}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
    		date_default_timezone_set("Asia/Bangkok");

		$this->load->model('Function_query_model', 'func_query', FALSE);
		$fetch_chkfirsttime = $this->func_query->query_row('lms_emp','','','','emp_id="'.$sess['emp_id'].'"');
		$arr['emp_firsttime'] = isset($fetch_chkfirsttime['emp_firsttime'])?$fetch_chkfirsttime['emp_firsttime']:"";
		if($arr['emp_firsttime']=="1"){
			$this->session->set_userdata('redirect_val', $arr['page']);
			redirect(base_url().'dashboard') ;
		}
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
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
		$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}
		$arr['banner'] = $this->func_query->query_result('lms_ban_cos','','','','bc_type="3" and bc_isDelete="0" and bc_status="1"');
		$lang_select = "th";
		if($lang=="english"){
			$lang_select = "eng";
		}else if($lang=="japan"){
			$lang_select = "jp";
		}
		$arr['list_survey'] = $this->func_query->query_result(
			'lms_sv','','','',
			'sv_public="1" and sv_approve="1" and sv_status="1" and sv_isDelete="0" and ((lms_sv.sv_open="0000-00-00 00:00:00" and lms_sv.sv_end="0000-00-00 00:00:00")OR
				("'.date('Y-m-d H:i').'" between lms_sv.sv_open and lms_sv.sv_end))');
		if(countArray($arr['list_survey'])>0){
			foreach ($arr['list_survey'] as $key_list => $value_list) {
				$arr['list_survey'][$key_list]['seat'] = $this->func_query->numrows('lms_sv_tc','','','','sv_id="'.$value_list['sv_id'].'" and svtc_isDelete="0"');
				$fetch_status = $this->func_query->query_row('lms_sv_tc','','','','sv_id="'.$value_list['sv_id'].'" and emp_id="'.$sess['emp_id'].'" and svtc_isDelete="0"');
				if(isset($fetch_status)){
					if($fetch_status['svtc_status']=="1"){
						$arr['list_survey'][$key_list]['status'] = label('done');
					}else{
						$arr['list_survey'][$key_list]['status'] = label('noProgress');
					}
				}else{
					$fetch_posi = $this->func_query->numrows('lms_sv_pm','','','','sv_id="'.$value_list['sv_id'].'" and posi_id="'.$sess['posi_id'].'"');
					if($fetch_posi==0){
						unset($arr['list_survey'][$key_list]);
					}else{
						$arr['list_survey'][$key_list]['status'] = label('noProgress');
					}
				}
			}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/survey_all', $arr );
	}

	public function list_survey()
	{
		$arr['page'] = 'survey/list_survey';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		

			if(empty($sess)){
				redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
			}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
		$this->load->model('Function_query_model', 'func_query', FALSE);

		$fetch_chkfirsttime = $this->func_query->query_row('lms_emp','','','','emp_id="'.$sess['emp_id'].'"');
		$arr['emp_firsttime'] = isset($fetch_chkfirsttime['emp_firsttime'])?$fetch_chkfirsttime['emp_firsttime']:"";
		if($arr['emp_firsttime']=="1"){
			redirect(base_url().'dashboard') ;
		}
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
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

		$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/list_survey', $arr );
	}

	public function surveyDetail($sv_id)
	{
		$arr['page'] = 'survey/surveydetail/'.$sv_id;
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		

			if(empty($sess)){
				redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
			}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$fetch_chkfirsttime = $this->func_query->query_row('lms_emp','','','','emp_id="'.$sess['emp_id'].'"');
		$arr['emp_firsttime'] = isset($fetch_chkfirsttime['emp_firsttime'])?$fetch_chkfirsttime['emp_firsttime']:"";
		if($arr['emp_firsttime']=="1"){
			$this->session->set_userdata('redirect_val', $arr['page']);
			redirect(base_url().'dashboard') ;
		}
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
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
		$arr['emp_id'] = $sess['emp_id'];
		$arr['sv_id'] = $sv_id;
		$arr_statuscos = "1";
		$fetch_tc = $this->func_query->query_row('lms_sv_tc','','','','sv_id="'.$sv_id.'" and emp_id="'.$sess['emp_id'].'" and svtc_isDelete="0"');
		if (!isset($fetch_tc['svtc_status'])) {
			$fetch_posi = $this->func_query->numrows('lms_sv_pm','','','','sv_id="'.$sv_id.'" and posi_id="'.$sess['posi_id'].'"');
			if ($fetch_posi==0) {
				$arr_statuscos = "2";
			}
			//redirect(base_url().'survey') ;
		}

		$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
		$arr['sv_main'] = $this->func_query->query_row('lms_sv','','','','sv_id = "'.$sv_id.'" and sv_isDelete="0" and sv_status="1"');
		if(isset($arr['sv_main']['com_id'])){
			$fetch_creator = $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id="'.$arr['sv_main']['sv_createby'].'"');
			$fetch_companycreator = $this->func_query->query_row('lms_company','','','','com_id="'.$arr['sv_main']['com_id'].'"');
			$arr['sv_main']['creator'] = $lang=="thai"?$fetch_companycreator['com_name_th']:$fetch_companycreator['com_name_eng'];
			$fetch_tc = $this->func_query->query_row('lms_sv_tc','','','','sv_id="'.$sv_id.'" and emp_id="'.$sess['emp_id'].'" and svtc_isDelete="0"');
			$tcmain_status = label('noProgress');
			if(isset($fetch_tc['svtc_status'])){
				if($fetch_tc['svtc_status']=="1"){
					$tcmain_status = label('done');
				}else{
					$tcmain_status = label('noProgress');
				}

				if($fetch_tc['svtc_firsttime']=="0000-00-00 00:00:00"){
					$arr_update = array(
						'svtc_firsttime' => date('Y-m-d H:i'),
						'svtc_modifiedby' => $sess['u_id'],
						'svtc_modifieddate' => date('Y-m-d H:i')
					);
					$this->db->where('svtc_id',$fetch_tc['svtc_id']);
					$this->db->update('lms_sv_tc',$arr_update);
				}
			}else{
				$fetch_chkpm = $this->func_query->query_row('lms_sv_pm','','','','sv_id="'.$sv_id.'" and posi_id="'.$sess['posi_id'].'"');
				if (isset($fetch_chkpm["posi_id"])) {
						$arr_datamain = array(
							'sv_id' => $sv_id,
							'emp_id' => $sess['emp_id'],
							'svtc_firsttime' => date('Y-m-d H:i:s'),
							'svtc_createby' => $sess['u_id'],
							'svtc_createdate' => date('Y-m-d H:i:s'),
							'svtc_modifiedby' => $sess['u_id'],
							'svtc_modifieddate' => date('Y-m-d H:i:s')
						);
						$this->db->insert('lms_sv_tc',$arr_datamain);
				}
			}
			$arr['sv_main']['tcmain_status'] = $tcmain_status;
			if($arr['sv_main']['sv_open']!="0000-00-00 00:00:00"&&$arr['sv_main']['sv_end']!="0000-00-00 00:00:00"){

				if(date('Y-m-d H:i')<date('Y-m-d H:i',strtotime($arr['sv_main']['sv_open']))){
					//redirect(base_url().'survey') ;
					$arr_statuscos = "3";
				}
				if(date('Y-m-d H:i')>date('Y-m-d H:i',strtotime($arr['sv_main']['sv_end']))){
					//redirect(base_url().'survey') ;
					$arr_statuscos = "3";
				}
				/*if(date('Y-m-d H:i')<date('Y-m-d H:i',strtotime($arr['sv_main']['sv_open']))||date('Y-m-d H:i')>date('Y-m-d H:i',strtotime($arr['sv_main']['sv_end']))){
					redirect(base_url().'survey') ;
				}*/
			}
		}else{
			//redirect(base_url().'survey') ;
			$arr_statuscos = "0";
		}
		$arr['arr_statuscos'] = $arr_statuscos;
		$arr_header = array();
		$order_by = "";
		$svLangArr = explode(',', $arr['sv_main']['sv_lang']);
		if($arr['sv_main']['sv_isHeader']=="1"){
			if($lang=="thai"){
				if (in_array("th", $svLangArr)) {
					$order_by = "svde_header_th ASC";
				} else {
					if (in_array("eng", $svLangArr)) {
						$order_by = "svde_header_eng ASC";
					} else {
						$order_by = "svde_header_jp ASC";
					}
				}
			}else if($lang=="english"){
				if (in_array("eng", $svLangArr)) {
					$order_by = "svde_header_eng ASC";
				} else {
					if (in_array("th", $svLangArr)) {
						$order_by = "svde_header_th ASC";
					} else {
						$order_by = "svde_header_jp ASC";
					}
				}
			}else{
				if (in_array("jp", $svLangArr)) {
					$order_by = "svde_header_jp ASC";
				} else {
					if (in_array("eng", $svLangArr)) {
						$order_by = "svde_header_eng ASC";
					} else {
						$order_by = "svde_header_th ASC";
					}
				}
			}
		}
		
		if (isset($arr['sv_main']['sv_title_th'])) {
			if($lang=="thai"){ 
				$sv_title = $arr['sv_main']['sv_title_th'] != "" ? $arr['sv_main']['sv_title_th'] : $arr['sv_main']['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $arr['sv_main']['sv_title_jp'];
			}else if($lang=="english"){ 
				$sv_title = $arr['sv_main']['sv_title_eng'] != "" ? $arr['sv_main']['sv_title_eng'] : $arr['sv_main']['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $arr['sv_main']['sv_title_jp'];
			}else{
				$sv_title = $arr['sv_main']['sv_title_jp'] != "" ? $arr['sv_main']['sv_title_jp'] : $arr['sv_main']['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $arr['sv_main']['sv_title_th'];
			}
			$arr['title'] = $sv_title;
		}
		$arr['sv_detail'] = $this->func_query->query_result('lms_svde','','','','sv_id = "'.$sv_id.'" and svde_status="1" and svde_isDelete="0"',$order_by);
		if(countArray($arr['sv_detail'])>0){
			foreach ($arr['sv_detail'] as $key_detail => $value_detail) {
				$fetch_multi = $this->func_query->query_row('lms_svde_mul','','','','svde_id="'.$value_detail['svde_id'].'" and mul_isDelete="0" and mul_status="1"');
				if(isset($fetch_multi)){
					$arr['sv_detail'][$key_detail]['multi'] = $fetch_multi;
				}
				$fetch_tc_detail = $this->func_query->query_row('lms_svde_tc','','','','sv_id="'.$sv_id.'" and svde_id="'.$value_detail['svde_id'].'" and emp_id="'.$sess['emp_id'].'" and tc_isDelete="0"');
				if(isset($fetch_tc_detail)){
					$arr['sv_detail'][$key_detail]['detail_tc'] = $fetch_tc_detail;
					if($fetch_tc_detail['tc_answer']==""){
					$arr['sv_detail'][$key_detail]['isTC'] = 0;
					}else{
					$arr['sv_detail'][$key_detail]['isTC'] = 1;
					}
				}else{
					$arr['sv_detail'][$key_detail]['isTC'] = 0;
				}
				if($arr['sv_main']['sv_isHeader']=="1"){
					if($lang=="thai"){ 
						$svde_header = $value_detail['svde_header_th']!=""?$value_detail['svde_header_th']:$value_detail['svde_header_eng'];
						$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
					}else if($lang=="english"){ 
						$svde_header = $value_detail['svde_header_eng']!=""?$value_detail['svde_header_eng']:$value_detail['svde_header_th'];
						$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
					}else{
						$svde_header = $value_detail['svde_header_jp']!=""?$value_detail['svde_header_jp']:$value_detail['svde_header_eng'];
						$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_th'];
					}
					if($svde_header!=""){
						if(!in_array($svde_header, $arr_header)){
							array_push($arr_header, $svde_header);
						}
					}
				}
			}
		}
		$arr['arr_header'] = $arr_header;
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/surveyDetail', $arr );
	}

	public function demo($sv_id,$dashboard=0)
	{
		$arr['page'] = 'survey/demo/'.$sv_id;
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		
			if(empty($sess)){
				redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
			}
		$arr['isDashboard'] = $dashboard;
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$fetch_chkfirsttime = $this->func_query->query_row('lms_emp','','','','emp_id="'.$sess['emp_id'].'"');
		$arr['emp_firsttime'] = isset($fetch_chkfirsttime['emp_firsttime'])?$fetch_chkfirsttime['emp_firsttime']:"";
		if($arr['emp_firsttime']=="1"){
			redirect(base_url().'dashboard') ;
		}
		$arr['emp_id'] = $sess['emp_id'];
		$arr['sv_id'] = $sv_id;
		$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
		$arr['sv_main'] = $this->func_query->query_row('lms_sv','','','','sv_id = "'.$sv_id.'"');
		if(countArray($arr['sv_main'])>0){
			$fetch_creator = $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id="'.$arr['sv_main']['sv_createby'].'"');
			$fetch_companycreator = $this->func_query->query_row('lms_company','','','','com_id="'.$arr['sv_main']['com_id'].'"');
			$arr['sv_main']['creator'] = $lang=="thai"?$fetch_companycreator['com_name_th']:$fetch_companycreator['com_name_eng'];
			$tcmain_status = label('noProgress');
			$arr['sv_main']['tcmain_status'] = $tcmain_status;
		}else{
			redirect(base_url().'dashboard') ;
		}

		$var_chk = 0;
		$sv_userapprove = explode(',', $arr['sv_main']['sv_userapprove']);
		if(in_array($sess['emp_id'], $sv_userapprove)){
				$var_chk++;
		}
		$arr['isCreator'] = "0";
		if($arr['sv_main']['sv_createby']==$sess['u_id']){
			$var_chk++;
			$arr['isCreator'] = "1";
		}
		/*if($var_chk==0){
			if($sess['emp_id']!="1"&&!in_array($sess['ug_id'], array('1','2','6'))){
				redirect(base_url().'dashboard') ;
			}
		}*/
		$arr['sv_main']['isApprove'] = "1";
		$sv_userapprove = explode(",",$arr['sv_main']['sv_userapprove']);
		$sv_approve = label('d_waitapprove');
		$fetch_approve = $this->func_query->query_row('lms_sv_approve','','','','sv_id ="'.$arr['sv_main']['sv_id'].'"','sva_id DESC');
		if(isset($fetch_approve)){
			if($fetch_approve['sva_approve']=="1"){
				$arr['sv_main']['isApprove'] = "1";//approve
			}else if($fetch_approve['sva_approve']=="2"){
				$arr['sv_main']['isApprove'] = "2";//waitapprove
				if(in_array($sess['emp_id'], $sv_userapprove)){
				$arr['sv_main']['isApprove'] = "22";//waitapprove
				}
			}else if($fetch_approve['sva_approve']=="3"){
				$arr['sv_main']['isApprove'] = "3";//create
			}else{
				$arr['sv_main']['isApprove'] = "0";//reject
			}
		}else{
			if(intval($arr['sv_main']['sv_public'])==0){
				$arr['sv_main']['isApprove'] = "3";//create
			}              
		}
		$num_question = $this->func_query->numrows('lms_svde','','','','sv_id="'.$arr['sv_main']['sv_id'].'" and svde_isDelete="0"');
		if($num_question==0){
			$arr['sv_main']['isApprove'] = "3";
			$arr['isCreator'] = "0";
		}
		/*$arr_user = $arr['sv_main']['sv_userapprove']!=""?explode(',', $arr['sv_main']['sv_userapprove']):array();
		if(countArray($arr_user)>0){
			if(!in_array($sess['emp_id'], $arr_user)){
				$arr['sv_main']['isApprove'] = "0";
			}
		}else{
			$arr['sv_main']['isApprove'] = "0";
		}*/
		$arr_header = array();
		$order_by = "";
		$svLangArr = explode(',', $arr['sv_main']['sv_lang']);
		if($arr['sv_main']['sv_isHeader']=="1"){
			if($lang=="thai"){
				if (in_array("th", $svLangArr)) {
					$order_by = "svde_header_th ASC";
				} else {
					if (in_array("eng", $svLangArr)) {
						$order_by = "svde_header_eng ASC";
					} else {
						$order_by = "svde_header_jp ASC";
					}
				}
			}else if($lang=="english"){
				if (in_array("eng", $svLangArr)) {
					$order_by = "svde_header_eng ASC";
				} else {
					if (in_array("th", $svLangArr)) {
						$order_by = "svde_header_th ASC";
					} else {
						$order_by = "svde_header_jp ASC";
					}
				}
			}else{
				if (in_array("jp", $svLangArr)) {
					$order_by = "svde_header_jp ASC";
				} else {
					if (in_array("eng", $svLangArr)) {
						$order_by = "svde_header_eng ASC";
					} else {
						$order_by = "svde_header_th ASC";
					}
				}
			}
		}
		$arr['sv_detail'] = $this->func_query->query_result('lms_svde','','','','sv_id = "'.$sv_id.'" and svde_status="1" and svde_isDelete="0"',$order_by);
		if(countArray($arr['sv_detail'])>0){
			foreach ($arr['sv_detail'] as $key_detail => $value_detail) {
				$fetch_multi = $this->func_query->query_row('lms_svde_mul','','','','svde_id="'.$value_detail['svde_id'].'" and mul_isDelete="0" and mul_status="1"');
				if(isset($fetch_multi)){
					$arr['sv_detail'][$key_detail]['multi'] = $fetch_multi;
				}
				$arr['sv_detail'][$key_detail]['isTC'] = 0;
				if($arr['sv_main']['sv_isHeader']=="1"){
						if($lang=="thai"){ 
							$svde_header = $value_detail['svde_header_th']!=""?$value_detail['svde_header_th']:$value_detail['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
						}else if($lang=="english"){ 
							$svde_header = $value_detail['svde_header_eng']!=""?$value_detail['svde_header_eng']:$value_detail['svde_header_th'];
							$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
						}else{
							$svde_header = $value_detail['svde_header_jp']!=""?$value_detail['svde_header_jp']:$value_detail['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_th'];
						}
						if($svde_header!=""){
							if(!in_array($svde_header, $arr_header)){
								array_push($arr_header, $svde_header);
							}
						}
				}
			}
		}
		$arr['arr_header'] = $arr_header;
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
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
		$this->load->view('frontend/surveyDemo', $arr );
	}

	public function report_survey()
	{
		$arr['page'] = 'survey/report_survey';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		
			if(empty($sess)){
				redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
			}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
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
		$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
		if($arr['btn_view']!="1"){
			redirect(base_url().'dashboard') ;
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/report_publicsurvey', $arr );
	}

}
?>
