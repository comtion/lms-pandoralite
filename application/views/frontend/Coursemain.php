<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coursemain extends CI_Controller {

	public function all_courses()
	{
		$arr['page'] = 'coursemain/all_courses';
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
    		$date_now = date('Y-m-d H:i');
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
			$arr['banner'] = $this->func_query->query_result('lms_ban_cos','','','','bc_type="1" and bc_isDelete="0" and bc_status="1"');
			$lang_select = "th";
			if($lang=="english"){
				$lang_select = "eng";
			}else if($lang=="japan"){
				$lang_select = "jp";
			}
			// and lms_cos.cos_id  in (select distinct cos_id from lms_cos_detail Left join lms_cos_detail_ug on lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id where lms_cos_detail_ug.posi_id = "'.$sess['posi_id'].'")lms_cos.com_id="'.$sess['com_id'].'" and com_id="'.$sess['com_id'].'" and 
			$arr['list_coursegroup'] = $this->func_query->query_result('lms_cog','','','','cg_approve="1" and cg_isDelete="0" and cg_status="1"');
			$arr['list_course'] = $this->func_query->query_result('lms_cos','','','',' lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_status="1" and lms_cos.cos_isDelete="0"','cos_id DESC','lms_cos.cos_id,lms_cos.ccode,lms_cos.cos_lang,lms_cos.cname_th,lms_cos.cdesc_th,lms_cos.cname_eng,lms_cos.cdesc_eng,lms_cos.cname_jp,lms_cos.cdesc_jp,lms_cos.sub_description_th,lms_cos.sub_description_eng,lms_cos.sub_description_jp,lms_cos.cos_pic,lms_cos.seat_count');
			if(countArray($arr['list_course'])>0){
				foreach ($arr['list_course'] as $key_list => $value_list) {

	                $value_chk = 1;
	                $arr['list_course'][$key_list]['date_start'] = "0000-00-00 00:00:00";
	                $arr['list_course'][$key_list]['date_end'] = "0000-00-00 00:00:00";
	                $arr['list_course'][$key_list]['txt_period_course'] = label('UnlimitedTime');
	                $fetch_detail = $this->func_query->query_row('lms_cos_detail','','','','cos_id="'.$value_list['cos_id'].'" and lms_cos_detail.cosde_isDelete="0"');
	                if(countArray($fetch_detail)>0){
	                  if((lms_has_period_date($fetch_detail['date_start'])&&date('Y-m-d H:i',strtotime($fetch_detail['date_start']))>date('Y-m-d H:i'))||(lms_has_period_date($fetch_detail['date_end'])&&date('Y-m-d H:i',strtotime($fetch_detail['date_end']))<date('Y-m-d H:i'))){
	                    $value_chk = 0;
	                  }else{
	                    $arr['list_course'][$key_list]['date_start'] = $fetch_detail['date_start'];
	                    $arr['list_course'][$key_list]['date_end'] = $fetch_detail['date_end'];
	                    $arr['list_course'][$key_list]['txt_period_course'] = lms_format_period_range($fetch_detail['date_start'], $fetch_detail['date_end'], $lang);
	                  }
	                }
	                if($value_chk==1){
						$fetch_status = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
						// Public courses are governed by approval, publish status, and period dates.
						if(isset($arr['list_course'][$key_list])){
                  			$result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value_list['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
	                  		if($result_chkcg==0){
	                  			unset($arr['list_course'][$key_list]);
	                  		}
						}
	                }else{
	                  unset($arr['list_course'][$key_list]);
	                }
				}
			}
			if(countArray($arr['list_course'])>0){
				$arr_cg = array();
				foreach ($arr['list_course'] as $key_list => $value_list) {
					$fetch_seat = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and cosen_isDelete="0"');
					$arr['list_course'][$key_list]['isseatFull'] = "0";
					if(intval($value_list['seat_count'])>0&&$fetch_seat>=intval($value_list['seat_count'])){
						$arr['list_course'][$key_list]['isseatFull'] = "1";
					}
					$cos_lang = explode(',', $value_list['cos_lang']);
					$value_list['isTH'] = in_array('th',$cos_lang)?"1":"0";
					$value_list['isENG'] = in_array('eng',$cos_lang)?"1":"0";
					$value_list['isJP'] = in_array('jp',$cos_lang)?"1":"0";

					$cname = "";
					$cos_langtxt = "";
					if($lang=="thai"){
							$cos_langtxt = "th";
							if($value_list['isTH']=="1"){
								$cname = $value_list['cname_th'];
							}else{
								if($value_list['cname_th']==""){
									$cname = $value_list['cname_eng'];
								}
								if($cname==""){
									$cname = $value_list['cname_jp'];
								}
							}
					}else if($lang=="english"){
							$cos_langtxt = "eng";
							if($value_list['isENG']=="1"){
								$cname = $value_list['cname_eng'];
							}else{
								if($value_list['cname_eng']==""){
									$cname = $value_list['cname_th'];
								}
								if($cname==""){
									$cname = $value_list['cname_jp'];
								}
							}
					}else{
							$cos_langtxt = "jp";
							if($value_list['isJP']=="1"){
								$cname = $value_list['cname_jp'];
							}else{
								if($value_list['cname_jp']==""){
									$cname = $value_list['cname_eng'];
								}
								if($cname==""){
									$cname = $value_list['cname_th'];
								}
							}
					}
					//if(in_array($cos_langtxt,$cos_lang)){
						$fetch_cg = $this->func_query->query_result('lms_cosincg','','','','course_id="'.$value_list['cos_id'].'" and status_cg="1"');
						$arr['list_course'][$key_list]['cg_arr'] = array();
						if(countArray($fetch_cg)>0){
							foreach ($fetch_cg as $key_cg => $value_cg) {
								if(!in_array($value_cg['cg_id'], $arr_cg)){
									array_push($arr_cg,$value_cg['cg_id']);
								}
								if(!in_array($value_cg['cg_id'], $arr['list_course'][$key_list]['cg_arr'])){
									array_push($arr['list_course'][$key_list]['cg_arr'],$value_cg['cg_id']);
								}
							}
						}
						$arr['list_course'][$key_list]['cname'] = $cname;
						$arr['list_course'][$key_list]['seat'] = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and cosen_isDelete="0"');
						$fetch_status = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
						$arr['list_course'][$key_list]['status'] = label('inProgress');
						if(countArray($fetch_status)>0){
							$arr['list_course'][$key_list]['isRegister'] = "1";
							if($fetch_status['cosen_status_sub']=="1"){
								$arr['list_course'][$key_list]['status'] = label('done');
							}else if($fetch_status['cosen_status_sub']=="0"&&$fetch_status['cosen_firsttime']!="0000-00-00 00:00:00"){
								$arr['list_course'][$key_list]['status'] = label('inProgress');
							}else{
								if($fetch_status['cosen_firsttime']=="0000-00-00 00:00:00"){
									$arr['list_course'][$key_list]['status'] = label('not_start');
								}else{
									$arr['list_course'][$key_list]['status'] = label('inProgress');
								}
							}
						}else{
							$arr['list_course'][$key_list]['status'] = label('r_notregister');
							$arr['list_course'][$key_list]['isRegister'] = "0";
						}
					/*}else{
						unset($arr['list_course'][$key_list]);
					}*/
				}
				if(countArray($arr['list_coursegroup'])>0){
					foreach ($arr['list_coursegroup'] as $key_cog => $value_cog) {
						$cg_name = "";
						if($lang=="thai"){
							$cg_name = $value_cog['cgtitle_th'];
						}else if($lang=="english"){
							$cg_name = $value_cog['cgtitle_en'];
						}else{
							$cg_name = $value_cog['cgtitle_jp'];
						}
						if(!in_array($value_cog['cg_id'], $arr_cg)){
							unset($arr['list_coursegroup'][$key_cog]);
						}else{
							$arr['list_coursegroup'][$key_cog]['cgname'] = $cg_name;
						}
					}
				}
			}else{
				if(countArray($arr['list_coursegroup'])>0){
					foreach ($arr['list_coursegroup'] as $key_cog => $value_cog) {
							unset($arr['list_coursegroup'][$key_cog]);
					}
				}
			}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/courseall_frontend', $arr );
	}

	public function my_course()
	{
		$arr['page'] = 'coursemain/my_course';
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
    		$date_now = date('Y-m-d H:i');
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
			$arr['banner'] = $this->func_query->query_result('lms_ban_cos','','','','bc_type="2" and bc_isDelete="0" and bc_status="1"');
			$lang_select = "th";
			if($lang=="english"){
				$lang_select = "eng";
			}else if($lang=="japan"){
				$lang_select = "jp";
			}
			$arr['list_coursegroup'] = $this->func_query->query_result('lms_cog','','','','cg_approve="1" and cg_isDelete="0" and cg_status="1"');
			//lms_cos.com_id="'.$sess['com_id'].'" and com_id="'.$sess['com_id'].'" and 
			$arr['list_course'] = $this->func_query->query_result('lms_cos','','','','lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_status="1" and lms_cos.cos_isDelete="0"','cos_id DESC','lms_cos.cos_id,lms_cos.ccode,lms_cos.cos_lang,lms_cos.cname_th,lms_cos.cdesc_th,lms_cos.cname_eng,lms_cos.cdesc_eng,lms_cos.cname_jp,lms_cos.cdesc_jp,lms_cos.sub_description_th,lms_cos.sub_description_eng,lms_cos.sub_description_jp,lms_cos.cos_pic');
			if(countArray($arr['list_course'])>0){
				foreach ($arr['list_course'] as $key_list => $value_list) {
	                $value_chk = 1;
	                $arr['list_course'][$key_list]['date_start'] = "0000-00-00 00:00:00";
	                $arr['list_course'][$key_list]['date_end'] = "0000-00-00 00:00:00";
	                $arr['list_course'][$key_list]['txt_period_course'] = label('UnlimitedTime');
	                $fetch_detail = $this->func_query->query_row('lms_cos_detail','','','','cos_id="'.$value_list['cos_id'].'" and lms_cos_detail.cosde_isDelete="0"');
	                if(countArray($fetch_detail)>0){
	                  if((lms_has_period_date($fetch_detail['date_start'])&&date('Y-m-d H:i',strtotime($fetch_detail['date_start']))>date('Y-m-d H:i'))||(lms_has_period_date($fetch_detail['date_end'])&&date('Y-m-d H:i',strtotime($fetch_detail['date_end']))<date('Y-m-d H:i'))){
	                    $value_chk = 0;
	                  }else{
	                    $arr['list_course'][$key_list]['date_start'] = $fetch_detail['date_start'];
	                    $arr['list_course'][$key_list]['date_end'] = $fetch_detail['date_end'];
	                    $arr['list_course'][$key_list]['txt_period_course'] = lms_format_period_range($fetch_detail['date_start'], $fetch_detail['date_end'], $lang);
	                  }
	                }
	                if($value_chk==1){
						$fetch_status = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
						// Public courses are governed by approval, publish status, and period dates.
						if(isset($arr['list_course'][$key_list])){
                  			$result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value_list['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
	                  		if($result_chkcg==0){
	                  			unset($arr['list_course'][$key_list]);
	                  		}
						}
	                }else{
	                  unset($arr['list_course'][$key_list]);
	                }
				}
			}
			
			if(countArray($arr['list_course'])>0){
				$arr_cg = array();
				foreach ($arr['list_course'] as $key_list => $value_list) {
					$fetch_enroll = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'"');
					if($fetch_enroll>0){
						$cos_lang = explode(',', $value_list['cos_lang']);
						$value_list['isTH'] = in_array('th',$cos_lang)?"1":"0";
						$value_list['isENG'] = in_array('eng',$cos_lang)?"1":"0";
						$value_list['isJP'] = in_array('jp',$cos_lang)?"1":"0";

						$cname = "";
						$cos_langtxt = "";
						if($lang=="thai"){
								$cos_langtxt = "th";
								if($value_list['isTH']=="1"){
									$cname = $value_list['cname_th'];
								}else{
									if($value_list['cname_th']==""){
										$cname = $value_list['cname_eng'];
									}
									if($cname==""){
										$cname = $value_list['cname_jp'];
									}
								}
						}else if($lang=="english"){
								$cos_langtxt = "eng";
								if($value_list['isENG']=="1"){
									$cname = $value_list['cname_eng'];
								}else{
									if($value_list['cname_eng']==""){
										$cname = $value_list['cname_th'];
									}
									if($cname==""){
										$cname = $value_list['cname_jp'];
									}
								}
						}else{
								$cos_langtxt = "jp";
								if($value_list['isJP']=="1"){
									$cname = $value_list['cname_jp'];
								}else{
									if($value_list['cname_jp']==""){
										$cname = $value_list['cname_eng'];
									}
									if($cname==""){
										$cname = $value_list['cname_th'];
									}
								}
						}
						//if(in_array($cos_langtxt,$cos_lang)){
							$fetch_cg = $this->func_query->query_result('lms_cosincg','','','','course_id="'.$value_list['cos_id'].'" and status_cg="1"');
							$arr['list_course'][$key_list]['cg_arr'] = array();
							if(countArray($fetch_cg)>0){
								foreach ($fetch_cg as $key_cg => $value_cg) {
									if(!in_array($value_cg['cg_id'], $arr_cg)){
										array_push($arr_cg,$value_cg['cg_id']);
									}
									if(!in_array($value_cg['cg_id'], $arr['list_course'][$key_list]['cg_arr'])){
										array_push($arr['list_course'][$key_list]['cg_arr'],$value_cg['cg_id']);
									}
								}
							}
							$arr['list_course'][$key_list]['cname'] = $cname;
							$arr['list_course'][$key_list]['seat'] = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and cosen_isDelete="0"');
							$fetch_status = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
							$arr['list_course'][$key_list]['status'] = label('inProgress');
							if(countArray($fetch_status)>0){
								$arr['list_course'][$key_list]['isRegister'] = "1";
								if($fetch_status['cosen_status_sub']=="1"){
									$arr['list_course'][$key_list]['status'] = label('done');
								}else if($fetch_status['cosen_status_sub']=="0"&&$fetch_status['cosen_firsttime']!="0000-00-00 00:00:00"){
									$arr['list_course'][$key_list]['status'] = label('inProgress');
								}else{
									if($fetch_status['cosen_firsttime']=="0000-00-00 00:00:00"){
										$arr['list_course'][$key_list]['status'] = label('not_start');
									}else{
										$arr['list_course'][$key_list]['status'] = label('inProgress');
									}
								}
							}else{
								$arr['list_course'][$key_list]['status'] = label('r_notregister');
								$arr['list_course'][$key_list]['isRegister'] = "0";
							}
						/*}else{
							unset($arr['list_course'][$key_list]);
						}*/
					}else{
						unset($arr['list_course'][$key_list]);
					}
				}
				if(countArray($arr['list_coursegroup'])>0){
					foreach ($arr['list_coursegroup'] as $key_cog => $value_cog) {
						$cg_name = "";
						if($lang=="thai"){
							$cg_name = $value_cog['cgtitle_th'];
						}else if($lang=="english"){
							$cg_name = $value_cog['cgtitle_en'];
						}else{
							$cg_name = $value_cog['cgtitle_jp'];
						}
						if(!in_array($value_cog['cg_id'], $arr_cg)){
							unset($arr['list_coursegroup'][$key_cog]);
						}else{
							$arr['list_coursegroup'][$key_cog]['cgname'] = $cg_name;
						}
					}
				}
			}else{
				if(countArray($arr['list_coursegroup'])>0){
					foreach ($arr['list_coursegroup'] as $key_cog => $value_cog) {
							unset($arr['list_coursegroup'][$key_cog]);
					}
				}
			}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/my_course', $arr );
	}

	public function endcos($cos_id){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    $this->load->model('Course_model', 'course', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $fetch_chkcos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
	    if(countArray($fetch_chkcos)>0){

            $fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_status="1" and cosen_finishtime="0000-00-00 00:00:00" and cosen_isDelete="0"');

            if(countArray($fetch_enroll)>0){
            	$cosen_id = $fetch_enroll['cosen_id'];
		    	$status_cos = 0;
		    	$amount_les = 0;
		    	$amount_qiz = 0;
	            $score = 0;
	            $total = 0;
		    	$fetch_qiz = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_type="2" and quiz_show="1" and quiz_status="1" and quiz_isDelete="0"');
		    	$num_chk_qiz = 0;
		    	$numloopqiz = 0; 
		    	$numloopqizpass = 0; 
		    	if(countArray($fetch_qiz)>0){
	              	foreach ($fetch_qiz as $key_qiz => $value_qiz) {
	                    $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
	                    if(countArray($fetch_chk)>0){
		              		if($value_qiz['quiz_limit']=="1"){
		              			if($fetch_chk['limit_val']<=intval($value_qiz['quiz_limitval'])){
		              				if(floatval($fetch_chk['per_score'])>=floatval($value_qiz['quiz_maxscore'])){
		              					$numloopqizpass++;
		              				}else{
		              					if($fetch_chk['limit_val']==intval($value_qiz['quiz_limitval'])){
		              						$numloopqizpass++;
		              					}
		              				}
		              			}
		              		}else{
		              				if(floatval($fetch_chk['per_score'])>=floatval($value_qiz['quiz_maxscore'])){
		              					$numloopqizpass++;
		              				}
		              		}
		              	}
		              	$numloopqiz++;
	                    $score_total = 0;
	                    $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
	                    if(countArray($fetch_chk)>0){
	                    	$amount_qiz++;
		                    $fetch_questc = $this->func_query->query_result('lms_ques_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiztc_id="'.$fetch_chk['qiztc_id'].'"');
		                    if(countArray($fetch_questc)==intval($value_qiz['quiz_numofshown'])){
		                      $num_chk_qiz++;
		                    }
		                    if(countArray($fetch_questc)>0){
			                    foreach ($fetch_questc as $key => $value) {
			                    	$fetch_sum = $this->func_query->query_row('lms_ques','','','','ques_id="'.$value['ques_id'].'" and qiz_id="'.$value_qiz['qiz_id'].'"');
			                      	$score_total += countArray($fetch_sum)>0?floatval($fetch_sum['ques_score']):0;
			                    }
		                    }
		                    
		                    $score += countArray($fetch_chk)>0?floatval($fetch_chk['sum_score']):0;
		                    $total += $score_total;
	                    }
	                }
		    	}
		    	$fetch_lesson = $this->func_query->query_result('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1"');
		    	if(countArray($fetch_lesson)>0){
		    		foreach ($fetch_lesson as $key_lesson => $value_lesson) {
		    			$fetch_lestc = $this->func_query->query_row('lms_les_tc','','','','les_id="'.$value_lesson['les_id'].'" and cosen_id="'.$cosen_id.'" and emp_id="'.$sess['emp_id'].'"');
		    			if(countArray($fetch_lestc)>0){
		    				if($fetch_lestc['learn_status']=="2"){
		    					$amount_les++;
		    				}
		    			}
		    			if($value_lesson['les_type']=="2"){
		    				$fetch_scm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$sess['emp_id'].'" and lms_scm_val.var_name="cmi_core_lesson_status" and (lms_scm_val.var_value="completed" or lms_scm_val.var_value="passed")');
		    				if(countArray($fetch_scm)>0){
		    					$fetch_rawscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$sess['emp_id'].'" and lms_scm_val.var_name="cmi_core_score_raw" and lms_scm_val.var_value!=""');
		    					if(countArray($fetch_rawscm)>0){
		    						$fetch_maxscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$sess['emp_id'].'" and lms_scm_val.var_name="cmi_core_score_max" and lms_scm_val.var_value!=""');
		    						if(countArray($fetch_maxscm)>0){
					                    $total += floatval($fetch_maxscm['var_value']);
					                    $score += floatval($fetch_rawscm['var_value']);
		    						}
		    					}
		    				}
		    			}
		    		}
		    	}
		    	$cosen_grade = "";
	            $cosen_score = 0;
	            $cosen_score_per = 0;

	            $cosen_status_sub = '2';
	            $cosen_finishtime = '0000-00-00 00:00:00';
			                	//echo "518:".$total."::".$score;
	            if($total>0){
	            	if($score>=0&&$total>0){
	            		$cosen_score = $score;
			            $cosen_score_per = ($score/$total)*100;
			            $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
			            if(countArray($fetch_cug)>0){
			            	if($fetch_chkcos['cos_typegrading']=="1"){
				                if($cosen_score_per>=floatval($fetch_cug['mina'])){
				                  	$cosen_grade = "A";
				                }else if($cosen_score_per>=floatval($fetch_cug['minb'])){
				                  	$cosen_grade = "B";
				                }else if($cosen_score_per>=floatval($fetch_cug['minc'])){
				                  	$cosen_grade = "C";
				                }else if($cosen_score_per>=floatval($fetch_cug['mind'])){
				                  	$cosen_grade = "D";
				                }else{
				                  	$cosen_grade = "F";
				                }
			            	}else{
			            		if($cosen_score_per>=floatval($fetch_cug['mina'])){
				                  	$cosen_grade = "P";
				                }else{
				                  	$cosen_grade = "F";
				                }
			            	}
			            }
			           	if(floatval($cosen_score_per)>=floatval($fetch_chkcos['goal_score'])){
			                $cosen_status_sub = 1;
			                $cosen_finishtime = date('Y-m-d H:i');
			            }else{
			            	if($numloopqizpass==$numloopqiz){
			            		$cosen_status_sub = 1;
			                	$cosen_finishtime = date('Y-m-d H:i');
			            	}else{
			                	$cosen_status_sub = 2;
			            	}
			            }
		            }
	            }else{
	            	$cosen_score = 100;
	            	$cosen_score_per = 100;

			        $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
			        if(countArray($fetch_cug)>0){
			            	if($fetch_chkcos['cos_typegrading']=="1"){
				                if($cosen_score_per>=floatval($fetch_cug['mina'])){
				                  	$cosen_grade = "A";
				                }else if($cosen_score_per>=floatval($fetch_cug['minb'])){
				                  	$cosen_grade = "B";
				                }else if($cosen_score_per>=floatval($fetch_cug['minc'])){
				                  	$cosen_grade = "C";
				                }else if($cosen_score_per>=floatval($fetch_cug['mind'])){
				                  	$cosen_grade = "D";
				                }else{
				                  	$cosen_grade = "F";
				                }
			            	}else{
			            		if($cosen_score_per>=floatval($fetch_cug['mina'])){
				                  	$cosen_grade = "P";
				                }else{
				                  	$cosen_grade = "F";
				                }
			            	}
			        }
			        
					if($amount_les==0&&$amount_qiz==0){
					        $status_rechk = 0;
					    	$fetch_qiz = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_type="1" and quiz_show="1" and quiz_status="1" and quiz_isDelete="0"');
					    	if(countArray($fetch_qiz)>0){
					    		foreach ($fetch_qiz as $key_qiz => $value_qiz) {
				                    $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_status="3"','qiztc_id DESC');
				                    if(countArray($fetch_chk)>0){
				                    	$status_rechk++;
				                    }
					    		}
					    	}
					    	$fetch_survey = $this->func_query->query_result('lms_survey','','','','cos_id="'.$cos_id.'" and sv_status="1" and sv_isDelete="0"');
					    	if(countArray($fetch_survey)>0){
					    		foreach ($fetch_survey as $key_sv => $value_sv) {
				                    $fetch_chk = $this->func_query->query_row('lms_qn_user','','','','sv_id="'.$value_sv['sv_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qnu_status="1"','qnu_id DESC');
				                    if(countArray($fetch_chk)>0){
				                    	$status_rechk++;
				                    }
					    		}
					    	}
					    	$caltotal = countArray($fetch_qiz)+countArray($fetch_survey);
					    	if($status_rechk==$caltotal){
					                $cosen_status_sub = 1;
					                $cosen_finishtime = date('Y-m-d H:i');
					    	}
					}
	            }
	            $val_cosen = 0;
	            $total_couse = 0;
	            $fetch_les = $this->func_query->numrows('lms_les','','','','les_isDelete="0" and cos_id="'.$cos_id.'"');
	            $fetch_lestc = $this->func_query->numrows('lms_les_tc','','','','learn_status="2" and cosen_id="'.$cosen_id.'"');
	            $fetch_qiz = $this->func_query->numrows('lms_qiz','','','','quiz_isDelete="0" and cos_id="'.$cos_id.'"');
	            $fetch_qiztc = $this->func_query->numrows('lms_qiz_tc','','','','qiz_status="3" and cosen_id="'.$cosen_id.'"');
	            $fetch_sv = $this->func_query->numrows('lms_survey','','','','sv_isDelete="0" and cos_id="'.$cos_id.'"');
	            $fetch_svtc = $this->func_query->numrows('lms_qn_user','','','','qnu_status="1" and cosen_id="'.$cosen_id.'"');
	            if($fetch_les>0){
	            	$total_couse++;
		            if($fetch_les==$fetch_lestc){
		            	$val_cosen++;
		            }
	            }
	            if($fetch_qiz>0){
	            	$total_couse++;
		            if($fetch_qiz==$fetch_qiztc){
		            	$val_cosen++;
		            }
	            }
	            /*if($fetch_sv>0){
	            	$total_couse++;
		            if($fetch_sv==$fetch_svtc){
		            	$val_cosen++;
		            }
	            }*/
	            if($total_couse==$val_cosen){
		            if($cosen_finishtime!="0000-00-00 00:00:00"&&$cosen_finishtime!=""){
		            	$fetch_bad = $this->func_query->query_row('lms_bad','','','','courses_id="'.$cos_id.'"');
		                if(countArray($fetch_bad)>0){
		                	$score_pass = 0;
		                	if($fetch_bad['badges_condition']=="P"||$fetch_bad['badges_condition']=="F"){
		                		$score_pass = floatval($fetch_cug['mina']);
		                	}else{
		                		if($fetch_bad['badges_condition']=="A"){
		                			$score_pass = floatval($fetch_cug['mina']);
		                		}else if($fetch_bad['badges_condition']=="B"){
		                			$score_pass = floatval($fetch_cug['minb']);
		                		}else if($fetch_bad['badges_condition']=="C"){
		                			$score_pass = floatval($fetch_cug['minc']);
		                		}else if($fetch_bad['badges_condition']=="D"){
		                			$score_pass = floatval($fetch_cug['mind']);
		                		}
		                	}
		                	if($cosen_score_per>=$score_pass){
		                   		$this->course->update_cert($cos_id,$sess);	
		                	}
		                }
		            }
			        $cosen_status_sub = 1;
			       	$cosen_finishtime = date('Y-m-d H:i');
	            }else{
			        $cosen_grade = '';
			        $cosen_score = 0;
			        $cosen_score_per = 0;
			        $cosen_status_sub = 2;
			       	$cosen_finishtime = '0000-00-00 00:00:00';
	            }

            	$arr_update = array(
	            	'cosen_grade' => $cosen_grade,
	            	'cosen_score' => $cosen_score,
	            	'cosen_score_per' => $cosen_score_per,
	            	'cosen_status_sub' => $cosen_status_sub,
	            	'cosen_finishtime' => $cosen_finishtime,
	            	'cosen_modifiedby' => $sess['u_id'],
	            	'cosen_modifieddate' => date('Y-m-d H:i')
	            );
	            $this->db->where('cosen_id',$fetch_enroll['cosen_id']);
	            $this->db->update('lms_cos_enroll',$arr_update);
            }
            
	    }
	}

	public function detail($cos_id)
	{
		$arr['page'] = 'coursemain/detail/'.$cos_id;
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$lang_select = isset($_REQUEST['course_lang'])?$_REQUEST['course_lang']:"";
		/*if($lang_select!=""){
		    if($lang_select=="th"){
		    	$lang = 'thai';
		    }else if($lang_select=="eng"){
		    	$lang = 'english';
		    }else{
		    	$lang = 'japan';
		    }
		}*/
		$sess = $this->session->userdata("user");
			if(empty($sess)){
				redirect(base_url().'dashboard/logout?redirect='.$arr['page']) ;
			}
		$this->session->set_userdata('viewcourse', 'real' );
   		$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$arr['lang_select'] = $lang_select;
		$arr['isFirsttime'] = "0";
		if($lang_select==""){
			$lang_select = $lang;
			$arr['isFirsttime'] = "1";
		}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$this->manage->loadDB();
		$this->home->loadDB();
		$this->setting->loadDB();

		$arr['foote'] = $this->foot->getfooter();

		date_default_timezone_set("Asia/Bangkok");
		$date_now = date('Y-m-d H:i');
		$arr['arr_permission'] = $this->manage->chk_permission_page();
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
		$arr['course_main'] = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
		$arr['enroll'] = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
		/*if(countArray($arr['enroll'])==0){

		}*/
        $result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$cos_id.'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
	    if($result_chkcg==0){
	        redirect(base_url().'coursemain/my_course') ;
	    }
		$cos_lang = explode(',', $arr['course_main']['cos_lang']);
		$arr['course_main']['isTH'] = in_array('th',$cos_lang)?"1":"0";
		$arr['course_main']['isENG'] = in_array('eng',$cos_lang)?"1":"0";
		$arr['course_main']['isJP'] = in_array('jp',$cos_lang)?"1":"0";
		$cname = "";
		$cdetail = "";
		$cos_langtxt = "";

		$arr['cos_id'] = $cos_id;
		if($lang_select=="thai"){
			$cos_langtxt = "th";
		    $arr['course_main']['select_lang'] = 'th';
		    $arr['course_main']['is_lang_user_th'] = 'selected';
				if($arr['course_main']['isTH']=="1"){
					$cname = $arr['course_main']['cname_th'];
					$cdetail = $arr['course_main']['cdesc_th'];
				}else{
					if($arr['course_main']['cname_th']==""){
						$cos_langtxt = "eng";
					    $arr['course_main']['select_lang'] = 'eng';
					    $arr['course_main']['is_lang_user_eng'] = 'selected';
						$cname = $arr['course_main']['cname_eng'];
						$cdetail = $arr['course_main']['cdesc_eng'];
					}
					if($cname==""){
						$cos_langtxt = "jp";
					    $arr['course_main']['select_lang'] = 'jp';
					    $arr['course_main']['is_lang_user_jp'] = 'selected';
						$cname = $arr['course_main']['cname_jp'];
						$cdetail = $arr['course_main']['cdesc_jp'];
					}
				}
		}else if($lang_select=="english"){
			$cos_langtxt = "eng";
		    $arr['course_main']['select_lang'] = 'eng';
		    $arr['course_main']['is_lang_user_eng'] = 'selected';
				if($arr['course_main']['isENG']=="1"){
					$cname = $arr['course_main']['cname_eng'];
					$cdetail = $arr['course_main']['cdesc_eng'];
				}else{
					if($arr['course_main']['cname_eng']==""){
						$cos_langtxt = "th";
					    $arr['course_main']['select_lang'] = 'th';
					    $arr['course_main']['is_lang_user_th'] = 'selected';
						$cname = $arr['course_main']['cname_th'];
						$cdetail = $arr['course_main']['cdesc_th'];
					}
					if($cname==""){
						$cos_langtxt = "jp";
					    $arr['course_main']['select_lang'] = 'jp';
					    $arr['course_main']['is_lang_user_jp'] = 'selected';
						$cname = $arr['course_main']['cname_jp'];
						$cdetail = $arr['course_main']['cdesc_jp'];
					}
				}
		}else{
			$cos_langtxt = "jp";
		    $arr['course_main']['select_lang'] = 'jp';
		    $arr['course_main']['is_lang_user_jp'] = 'selected';
				if($arr['course_main']['isJP']=="1"){
					$cname = $arr['course_main']['cname_jp'];
					$cdetail = $arr['course_main']['cdesc_jp'];
				}else{
					if($arr['course_main']['cname_jp']==""){
						$cos_langtxt = "eng";
					    $arr['course_main']['select_lang'] = 'eng';
					    $arr['course_main']['is_lang_user_eng'] = 'selected';
						$cname = $arr['course_main']['cname_eng'];
						$cdetail = $arr['course_main']['cdesc_eng'];
					}
					if($cname==""){
						$cos_langtxt = "th";
					    $arr['course_main']['select_lang'] = 'th';
					    $arr['course_main']['is_lang_user_th'] = 'selected';
						$cname = $arr['course_main']['cname_th'];
						$cdetail = $arr['course_main']['cdesc_th'];
					}
				}
		}
		/*if(!in_array($cos_langtxt,$cos_lang)){
			redirect(base_url().'dashboard') ;
		}*/
		$cosen_id = "";
		$fetch_chkenroll = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0" and cosen_status="1"');
		$cosen_id = $fetch_chkenroll['cosen_id'];
		if(countArray($fetch_chkenroll)>0&&$lang_select!=""){
			if($fetch_chkenroll['cosen_firsttime']=="0000-00-00 00:00:00"){
				$arr_updateenroll = array(
					'cosen_firsttime' => date('Y-m-d H:i'),
					'cosen_status' => '1',
					'cosen_status_sub' => '2',
					'cosen_modifiedby' => $sess['u_id'],
					'cosen_modifieddate' => date('Y-m-d H:i'),
				);
				$this->db->where('cosen_id',$fetch_chkenroll['cosen_id']);
				$this->db->update('lms_cos_enroll',$arr_updateenroll);
			}
			$this->endcos($cos_id);

				if($fetch_chkenroll['cosen_lang']==""||$fetch_chkenroll['cosen_lang']!=$lang_select){
					$arr_updateenroll = array(
						'cosen_lang' => $lang_select,
						'cosen_modifiedby' => $sess['u_id'],
						'cosen_modifieddate' => date('Y-m-d H:i'),
					);
					$this->db->where('cosen_id',$fetch_chkenroll['cosen_id']);
					$this->db->update('lms_cos_enroll',$arr_updateenroll);
				}
		}
		$arr['cosen_id'] = $cosen_id;
		$txt_period_course = label('UnlimitedTime');
		$arr['canstudy'] = "1";
		$where = 'cos_id = "'.$cos_id.'" and ((date_start IS NULL or CAST(date_start AS CHAR)="" or date_start="0000-00-00 00:00:00" or date_start <= "'.$date_now.'") and (date_end IS NULL or CAST(date_end AS CHAR)="" or date_end="0000-00-00 00:00:00" or "'.$date_now.'" <= date_end)) and cosde_isDelete="0" and cosde_status="1"';
		$result_detail = $this->func_query->query_row('lms_cos_detail','','','', $where,'cosde_id DESC');
		if(countArray($result_detail)>0){
			if((!empty($result_detail['date_start'])&&$result_detail['date_start']!="0000-00-00 00:00:00")||(!empty($result_detail['date_end'])&&$result_detail['date_end']!="0000-00-00 00:00:00")){
				if(!empty($result_detail['date_start'])&&$result_detail['date_start']!="0000-00-00 00:00:00"&&date('Y-m-d H:i')<date('Y-m-d H:i',strtotime($result_detail['date_start']))){
					redirect(base_url().'coursemain/my_course') ;
				}
				if(!empty($result_detail['date_end'])&&$result_detail['date_end']!="0000-00-00 00:00:00"&&date('Y-m-d H:i')>date('Y-m-d H:i',strtotime($result_detail['date_end']))){
					redirect(base_url().'coursemain/my_course') ;
				}
				$txt_period_course = lms_format_period_range($result_detail['date_start'], $result_detail['date_end'], $lang);
			}
		}else{
			$period_detail = $this->func_query->query_row('lms_cos_detail','','','','cos_id = "'.$cos_id.'" and cosde_isDelete="0" and cosde_status="1"','cosde_id DESC');
			if(countArray($period_detail)>0){
				redirect(base_url().'coursemain/my_course') ;
			}
		}
		$fetch_com = $this->func_query->query_row('lms_company','','','','com_id="'.$arr['course_main']['com_id'].'"');
		$arr['course_main']['cname'] = $cname;
		$arr['course_main']['cdetail'] = $cdetail;
		$arr['course_main']['com_name'] = $lang_select=="thai"?$fetch_com['com_name_th']:$fetch_com['com_name_eng'];
		$arr['course_main']['txt_period_course'] = $txt_period_course;
		$arr['lesson_status'] = 0;
		$arr['lesson_arr'] = $this->func_query->query_result('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1" and ((time_start="0000-00-00 00:00:00" and time_end="0000-00-00 00:00:00") or (time_start <= "'.$date_now.'" and  time_end >= "'.$date_now.'"))','les_sequences ASC');
		if(countArray($arr['lesson_arr'])>0){
			$arr_les_id = array();
			foreach ($arr['lesson_arr'] as $key_lesson => $value_lesson) {

                if($lang_select=="thai"){ 
                    $les_name = $value_lesson['les_name_th']!=""?$value_lesson['les_name_th']:$value_lesson['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value_lesson['les_name_jp'];
                    $les_info = $value_lesson['les_info_th']!=""?$value_lesson['les_info_th']:$value_lesson['les_info_eng'];
                    $les_info = $les_info!=""?$les_info:$value_lesson['les_info_jp'];
                }else if($lang_select=="english"){ 
                    $les_name = $value_lesson['les_name_eng']!=""?$value_lesson['les_name_eng']:$value_lesson['les_name_th'];
                    $les_name = $les_name!=""?$les_name:$value_lesson['les_name_jp'];
                    $les_info = $value_lesson['les_info_eng']!=""?$value_lesson['les_info_eng']:$value_lesson['les_info_th'];
                    $les_info = $les_info!=""?$les_info:$value_lesson['les_info_jp'];
                }else{
                    $les_name = $value_lesson['les_name_jp']!=""?$value_lesson['les_name_jp']:$value_lesson['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value_lesson['les_name_th'];
                    $les_info = $value_lesson['les_info_jp']!=""?$value_lesson['les_info_jp']:$value_lesson['les_info_eng'];
                    $les_info = $les_info!=""?$les_info:$value_lesson['les_info_th'];
                }
                $arr['lesson_arr'][$key_lesson]['les_name'] = $les_name;
                $arr['lesson_arr'][$key_lesson]['les_info'] = $les_info;
		    	if($value_lesson['les_type']=="2"){
		    		$fetch_scm = $this->func_query->query_row('lms_scm','','','','lessons_id="'.$value_lesson['les_id'].'"');
		    		$arr['lesson_arr'][$key_lesson]['scm_data'] = $fetch_scm;
		    	}else{
		    		$fetch_med = $this->func_query->query_result('lms_med','','','','lessons_id="'.$value_lesson['les_id'].'"');
		    		foreach ($fetch_med as $key_med => $value_med) {
		    			$fetch_medtc = $this->func_query->query_row('lms_med_tc','','','','emp_id="'.$sess['emp_id'].'" and med_id="'.$value_med['id'].'" and cosen_id="'.$cosen_id.'"');
		    			$fetch_med[$key_med]['arr_status'] = countArray($fetch_medtc)>0?1:0;
		    		}
		    		$arr['lesson_arr'][$key_lesson]['med_data'] = $fetch_med;
		    		$fetch_doc = $this->func_query->query_result('lms_fil','','','','lessons_id="'.$value_lesson['les_id'].'"');
		    		foreach ($fetch_doc as $key_doc => $value_doc) {
		    			$fetch_doctc = $this->func_query->query_row('lms_fil_log','','','','emp_id="'.$sess['emp_id'].'" and fil_id="'.$value_doc['id'].'" and cosen_id="'.$cosen_id.'"');
		    			$fetch_doc[$key_doc]['arr_status'] = countArray($fetch_doctc)>0?1:0;
		    		}
		    		$arr['lesson_arr'][$key_lesson]['doc_data'] = $fetch_doc;
		    	}
				if(!in_array($value_lesson['les_id'], $arr_les_id)){
					array_push($arr_les_id, $value_lesson['les_id']);
				}
			}
			$txt_les_id = implode(',', $arr_les_id);
			$value_status = 0;
			$fetch_chktc = $this->func_query->query_result('lms_les_tc','','','','les_id in ('.$txt_les_id.') and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'"');
			if(countArray($fetch_chktc)){
				foreach ($fetch_chktc as $key_chktc => $value_chktc) {
					if($value_chktc['learn_status']=="2"){
						$value_status++;
					}
				}
			}
			$arr['lesson_status'] = $value_status;
		}
		$arr['pretest_arr'] = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_isDelete="0" and quiz_status="1" and quiz_show="1" and quiz_type="1" and ((period_open="0000-00-00 00:00:00" and period_end="0000-00-00 00:00:00") or (period_open <= "'.$date_now.'" and  period_end >= "'.$date_now.'"))','lms_qiz.qiz_id ASC');
		$loop_run = 1;
		if(countArray($arr['pretest_arr'])>0){
				foreach ($arr['pretest_arr'] as $key_pretest => $value_pretest) {
	                $fetch_chkques = $this->func_query->numrows('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
	                if($fetch_chkques==0){
	                 	unset($arr['pretest_arr'][$key_pretest]);
	                }
				}
				if(countArray($arr['pretest_arr'])>0){
					foreach ($arr['pretest_arr'] as $key_pretest => $value_pretest) {
							$fetch_chktc = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_pretest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
							$per_score = 0;
							$qiztc_id = "";
							$ques_id_arr = array();
							if(countArray($fetch_chktc)>0){
								$arr['pretest_arr'][$key_pretest]['status_tc'] = $fetch_chktc['qiz_status'];
								$arr['pretest_arr'][$key_pretest]['sum_score'] = $fetch_chktc['sum_score'];
								$arr['pretest_arr'][$key_pretest]['per_score'] = $fetch_chktc['per_score'];
								$arr['pretest_arr'][$key_pretest]['endstatus'] = "0";
								$num_loop = $this->func_query->numrows('lms_qiz_tc','','','','qiz_id="'.$value_pretest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and qiz_status="3" and cosen_id="'.$cosen_id.'"');
								$quiz_limitval = 1;
								if($value_pretest['quiz_limit']=="1"){
									$quiz_limitval = intval($value_pretest['quiz_limitval']);
									if($num_loop>=$quiz_limitval){
										if($fetch_chktc['qiz_status']=="3"){
											$arr['pretest_arr'][$key_pretest]['endstatus'] = "1";
										}
									}
								}
								/*if($arr['pretest_arr'][$key_pretest]['endstatus']=="0"){
									if($fetch_chktc['qiz_status']=="3"&&floatval($fetch_chktc['per_score'])>=floatval($value_pretest['quiz_maxscore'])){
										$arr['pretest_arr'][$key_pretest]['endstatus'] = "1";
									}
								}*/
								if($fetch_chktc['qiz_status']=="3"){
								    if(floatval($fetch_chktc['per_score'])<floatval($value_pretest['quiz_maxscore'])){
										if($arr['pretest_arr'][$key_pretest]['endstatus']!="1"){
										    $fetch_loop = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_pretest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and cosen_id="'.$cosen_id.'"','limit_val DESC');
										    $limit_val = countArray($fetch_loop)>0?intval($fetch_loop['limit_val'])+1:1;
										    $arr_main = array(
										        'qiz_id' => $value_pretest['qiz_id'],
										        'emp_id' => $sess['emp_id'],
										        'time_start' => date('Y-m-d H:i'),
										        'time_mod' => date('Y-m-d H:i'),
										        'qiz_status' => '1',
										        'limit_val' => $limit_val,
										        'cosen_id' => $cosen_id
										    );
										    $this->db->insert('lms_qiz_tc',$arr_main);
			      							$qiztc_id = $this->db->insert_id();
									    	$loop_run=0;
									    }else{
									    	$qiztc_id = $fetch_chktc['qiztc_id'];
									    }
								    }else{
									    $qiztc_id = $fetch_chktc['qiztc_id'];
									}
								}else{
									$loop_run=0;
									$qiztc_id = $fetch_chktc['qiztc_id'];
								}
								$fetch_chk_ques = $this->func_query->query_result('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and cosen_id="'.$cosen_id.'"');

								$quiz_numofshown = $value_pretest['quiz_numofshown'];
								if(countArray($fetch_chk_ques)>0){
									if(intval($quiz_numofshown)==countArray($fetch_chk_ques)){
										foreach ($fetch_chk_ques as $key_chkques => $value_chkques) {
											if(!in_array($value_chkques['ques_id'], $ques_id_arr)){
											    array_push($ques_id_arr, $value_chkques['ques_id']);
											}
										}
									}else if(intval($quiz_numofshown)>countArray($fetch_chk_ques)){
										$amount = intval($quiz_numofshown)-countArray($fetch_chk_ques);
										$arr_ques_ori = array();
										foreach ($fetch_chk_ques as $key_chkques => $value_chkques) {
											if(!in_array($value_chkques['ques_id'], $arr_ques_ori)){
											    array_push($arr_ques_ori, $value_chkques['ques_id']);
											    array_push($ques_id_arr, $value_chkques['ques_id']);
											}
										}

										$order_question = "lms_ques.ques_id ASC";
										if($value_pretest['quiz_random']=="1"){
											$order_question = "RAND()";
										}
										$where_arr = ' and ques_id not in ('.implode(',', $arr_ques_ori).')';
										$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"'.$where_arr,$order_question,'',$amount);

										if(countArray($fetch_ques)>0){
											foreach ($fetch_ques as $key_ques => $value_ques) {
											      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and ques_id="'.$value_ques['ques_id'].'" and cosen_id="'.$cosen_id.'"');

											      $arr_main = array(
											          'qiztc_id' => $qiztc_id,
											          'qiz_id' => $value_pretest['qiz_id'],
											          'ques_id' => $value_ques['ques_id'],
											          'emp_id' => $sess['emp_id'],
										        	  'cosen_id' => $cosen_id
											      );
											      if($fetch_chk_ques==0){
											      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
											      		array_push($ques_id_arr, $value_ques['ques_id']);
											      	}
											        $this->db->insert('lms_ques_tc',$arr_main);
											      }
											}
										}
									}else{
										$amount = countArray($fetch_chk_ques)-intval($quiz_numofshown);
										echo "974::".$amount;
										$fetch_chk_ques = $this->func_query->query_result('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and cosen_id="'.$cosen_id.'"','lms_ques_tc.ques_id DESC',$amount);
										foreach ($fetch_chk_ques as $key_questc => $value_questc) {
											$this->db->where('tc_id',$value_questc['tc_id']);
											$this->db->delete('lms_ques_tc');
										}

										$order_question = "lms_ques.ques_id ASC";
										if($value_pretest['quiz_random']=="1"){
											$order_question = "RAND()";
										}
										$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$amount);

										if(countArray($fetch_ques)>0){
											foreach ($fetch_ques as $key_ques => $value_ques) {
											      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and cosen_id="'.$cosen_id.'" and ques_id="'.$value_ques['ques_id'].'"');

											      $arr_main = array(
											          'qiztc_id' => $qiztc_id,
											          'qiz_id' => $value_pretest['qiz_id'],
											          'ques_id' => $value_ques['ques_id'],
											          'emp_id' => $sess['emp_id'],
										        	  'cosen_id' => $cosen_id
											      );
											      if($fetch_chk_ques==0){
											      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
											      		array_push($ques_id_arr, $value_ques['ques_id']);
											      	}
											        $this->db->insert('lms_ques_tc',$arr_main);
											      }
											}
										}
									}
								}else{
									$order_question = "lms_ques.ques_id ASC";
									$quiz_numofshown = $value_pretest['quiz_numofshown'];
									if($value_pretest['quiz_random']=="1"){
										$order_question = "RAND()";
									}
									
									$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$quiz_numofshown);
									if(countArray($fetch_ques)>0){
										foreach ($fetch_ques as $key_ques => $value_ques) {
										      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and cosen_id="'.$cosen_id.'" and ques_id="'.$value_ques['ques_id'].'"');

										      $arr_main = array(
										          'qiztc_id' => $qiztc_id,
										          'qiz_id' => $value_pretest['qiz_id'],
										          'ques_id' => $value_ques['ques_id'],
										          'emp_id' => $sess['emp_id'],
										          'cosen_id' => $cosen_id
										      );
										      if($fetch_chk_ques==0){
										      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
										      		array_push($ques_id_arr, $value_ques['ques_id']);
										      	}
										        $this->db->insert('lms_ques_tc',$arr_main);
										      }
										}
									}
								}
								$arr['pretest_arr'][$key_pretest]['qiztc_id'] = $qiztc_id;
							}else{
								$arr['pretest_arr'][$key_pretest]['status_tc'] = "0";
								$arr['pretest_arr'][$key_pretest]['sum_score'] = "0";
								$arr['pretest_arr'][$key_pretest]['per_score'] = "0";
								$arr['pretest_arr'][$key_pretest]['endstatus'] = "0";

								    $fetch_loop = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_pretest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and cosen_id="'.$cosen_id.'"','limit_val DESC');
								    $limit_val = countArray($fetch_loop)>0?intval($fetch_loop['limit_val'])+1:1;
								    $arr_main = array(
								        'qiz_id' => $value_pretest['qiz_id'],
								        'emp_id' => $sess['emp_id'],
								        'time_start' => date('Y-m-d H:i'),
								        'time_mod' => date('Y-m-d H:i'),
								        'qiz_status' => '1',
								        'limit_val' => $limit_val,
										'cosen_id' => $cosen_id
								    );
								    $this->db->insert('lms_qiz_tc',$arr_main);
	      							$qiztc_id = $this->db->insert_id();
								$arr['pretest_arr'][$key_pretest]['qiztc_id'] = $qiztc_id;

								$order_question = "lms_ques.ques_id ASC";
								$quiz_numofshown = $value_pretest['quiz_numofshown'];
								if($value_pretest['quiz_random']=="1"){
									$order_question = "RAND()";
								}
								
								$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$quiz_numofshown);
								if(countArray($fetch_ques)>0){
									foreach ($fetch_ques as $key_ques => $value_ques) {
									      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$value_pretest['qiz_id'].'" and cosen_id="'.$cosen_id.'" and ques_id="'.$value_ques['ques_id'].'"');

									      $arr_main = array(
									          'qiztc_id' => $qiztc_id,
									          'qiz_id' => $value_pretest['qiz_id'],
									          'ques_id' => $value_ques['ques_id'],
									          'emp_id' => $sess['emp_id'],
										      'cosen_id' => $cosen_id
									      );
									      if($fetch_chk_ques==0){
									      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
									      		array_push($ques_id_arr, $value_ques['ques_id']);
									      	}
									        $this->db->insert('lms_ques_tc',$arr_main);
									      }
									}
								}

							}
							if(countArray($ques_id_arr)>0){
								$order_question = "lms_ques.ques_id ASC";
								if($value_pretest['quiz_random']=="1"){
									$order_question = "RAND()";
								}
								$where_arr = 'and ques_id in ('.implode(',', $ques_id_arr).')';
								$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0" '.$where_arr,$order_question);
							}else{
								$fetch_ques = array();
							}
							if(countArray($fetch_ques)>0){
								foreach ($fetch_ques as $key_ques => $value_ques) {
									if($value_ques['ques_type']=="multi"||$value_ques['ques_type']=="2choice"){
										$fetch_multi = $this->func_query->query_row('lms_ques_mul','','','','lms_ques_mul.ques_id="'.$value_ques['ques_id'].'"');
										$fetch_ques[$key_ques]['multi'] = $fetch_multi;
									}
									$fetch_chktc_ques = $this->func_query->query_row('lms_ques_tc','','','','qiz_id="'.$value_pretest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and ques_id="'.$value_ques['ques_id'].'" and qiztc_id="'.$qiztc_id.'"');
									if(countArray($fetch_chktc_ques)>0){
										$fetch_ques[$key_ques]['tc'] = $fetch_chktc_ques;
									}
								}
							}
							$arr['pretest_arr'][$key_pretest]['question'] = $fetch_ques;
					}
				}
		}
		$arr['loop_run'] = $loop_run;
		$where = 'cos_id="'.$cos_id.'" and quiz_isDelete="0" and quiz_status="1" and quiz_show="1" and quiz_type="2" and ((period_open="0000-00-00 00:00:00" and period_end="0000-00-00 00:00:00") or (period_open <= "'.$date_now.'" and  period_end >= "'.$date_now.'"))';
		$arr['posttest_arr'] = $this->func_query->query_result('lms_qiz','','','',$where,'lms_qiz.qiz_id ASC');
		if(countArray($arr['posttest_arr'])>0){

				foreach ($arr['posttest_arr'] as $key_posttest => $value_posttest) {
	                $fetch_chkques = $this->func_query->numrows('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
	                if($fetch_chkques==0){
	                 	unset($arr['posttest_arr'][$key_posttest]);
	                }
				}
				if(countArray($arr['posttest_arr'])>0){
					foreach ($arr['posttest_arr'] as $key_posttest => $value_posttest) {
							$fetch_chktc = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_posttest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
							$per_score = 0;
							$qiztc_id = "";
							$ques_id_arr = array();
							if(countArray($fetch_chktc)>0){
								$arr['posttest_arr'][$key_posttest]['status_tc'] = $fetch_chktc['qiz_status'];
								$arr['posttest_arr'][$key_posttest]['sum_score'] = $fetch_chktc['sum_score'];
								$arr['posttest_arr'][$key_posttest]['per_score'] = $fetch_chktc['per_score'];
								$arr['posttest_arr'][$key_posttest]['endstatus'] = "0";
								$num_loop = $this->func_query->numrows('lms_qiz_tc','','','','qiz_id="'.$value_posttest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and cosen_id="'.$cosen_id.'" and qiz_status="3"');
								$quiz_limitval = 1;
								if($value_posttest['quiz_limit']=="1"){
									$quiz_limitval = intval($value_posttest['quiz_limitval']);
									if($num_loop>=$quiz_limitval){
										if($fetch_chktc['qiz_status']=="3"){
											$arr['posttest_arr'][$key_posttest]['endstatus'] = "1";
										}
									}
								}
								if($fetch_chktc['qiz_status']=="3"&&$arr['posttest_arr'][$key_posttest]['endstatus']=="0"){
								    if(floatval($fetch_chktc['per_score'])<floatval($value_posttest['quiz_maxscore'])){
										if($arr['posttest_arr'][$key_posttest]['endstatus']!="1"){
										    $fetch_loop = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_posttest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiztc_isDelete="0"','limit_val DESC');
										    $limit_val = countArray($fetch_loop)>0?intval($fetch_loop['limit_val'])+1:1;
										    $arr_main = array(
										        'qiz_id' => $value_posttest['qiz_id'],
										        'emp_id' => $sess['emp_id'],
										        'time_start' => date('Y-m-d H:i'),
										        'time_mod' => date('Y-m-d H:i'),
										        'qiz_status' => '1',
										        'limit_val' => $limit_val,
										        'cosen_id' => $cosen_id
										    );
										    $this->db->insert('lms_qiz_tc',$arr_main);
			      							$qiztc_id = $this->db->insert_id();
									    }else{
									    	$qiztc_id = $fetch_chktc['qiztc_id'];
									    }
								    }else{
									    $qiztc_id = $fetch_chktc['qiztc_id'];
									}
								}else{
									$qiztc_id = $fetch_chktc['qiztc_id'];
								}
								$fetch_chk_ques = $this->func_query->query_result('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'"');

								$quiz_numofshown = $value_posttest['quiz_numofshown'];
								if(countArray($fetch_chk_ques)>0){
									if(intval($quiz_numofshown)==countArray($fetch_chk_ques)){
										foreach ($fetch_chk_ques as $key_chkques => $value_chkques) {
											if(!in_array($value_chkques['ques_id'], $ques_id_arr)){
											    array_push($ques_id_arr, $value_chkques['ques_id']);
											}
										}
									}else if(intval($quiz_numofshown)>countArray($fetch_chk_ques)){
										$amount = intval($quiz_numofshown)-countArray($fetch_chk_ques);
										$arr_ques_ori = array();
										foreach ($fetch_chk_ques as $key_chkques => $value_chkques) {
											if(!in_array($value_chkques['ques_id'], $arr_ques_ori)){
											    array_push($arr_ques_ori, $value_chkques['ques_id']);
											    array_push($ques_id_arr, $value_chkques['ques_id']);
											}
										}

										$order_question = "lms_ques.ques_id ASC";
										if($value_posttest['quiz_random']=="1"){
											$order_question = "RAND()";
										}
										$where_arr = ' and ques_id not in ('.implode(',', $arr_ques_ori).')';
										$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"'.$where_arr,$order_question,'',$amount);

										if(countArray($fetch_ques)>0){
											foreach ($fetch_ques as $key_ques => $value_ques) {
											      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'" and ques_id="'.$value_ques['ques_id'].'"');

											      $arr_main = array(
											          'qiztc_id' => $qiztc_id,
											          'qiz_id' => $value_posttest['qiz_id'],
											          'ques_id' => $value_ques['ques_id'],
											          'emp_id' => $sess['emp_id'],
										        	  'cosen_id' => $cosen_id
											      );
											      if($fetch_chk_ques==0){
											      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
											      		array_push($ques_id_arr, $value_ques['ques_id']);
											      	}
											        $this->db->insert('lms_ques_tc',$arr_main);
											      }
											}
										}
									}else{
										$amount = countArray($fetch_chk_ques)-intval($quiz_numofshown);
										if($amount>0){
										$fetch_chk_ques = $this->func_query->query_result('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'"','','',$amount);
										if(countArray($fetch_chk_ques)>0){
											foreach ($fetch_chk_ques as $key_questc => $value_questc) {
												$this->db->where('tc_id',$value_questc['tc_id']);
												$this->db->delete('lms_ques_tc');
											}
										}
										}

										
										$order_question = "lms_ques.ques_id ASC";
										if($value_posttest['quiz_random']=="1"){
											$order_question = "RAND()";
										}
										$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$amount);

										if(countArray($fetch_ques)>0){
											foreach ($fetch_ques as $key_ques => $value_ques) {
											      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'" and ques_id="'.$value_ques['ques_id'].'"');

											      $arr_main = array(
											          'qiztc_id' => $qiztc_id,
											          'qiz_id' => $value_posttest['qiz_id'],
											          'ques_id' => $value_ques['ques_id'],
											          'emp_id' => $sess['emp_id'],
										        	  'cosen_id' => $cosen_id
											      );
											      if($fetch_chk_ques==0){
											      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
											      		array_push($ques_id_arr, $value_ques['ques_id']);
											      	}
											        $this->db->insert('lms_ques_tc',$arr_main);
											      }
											}
										}
									}
								}else{
									$order_question = "lms_ques.ques_id ASC";
									$quiz_numofshown = $value_posttest['quiz_numofshown'];
									if($value_posttest['quiz_random']=="1"){
										$order_question = "RAND()";
									}
									
									$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$quiz_numofshown);
									if(countArray($fetch_ques)>0){
										foreach ($fetch_ques as $key_ques => $value_ques) {
										      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'" and ques_id="'.$value_ques['ques_id'].'"');

										      $arr_main = array(
										          'qiztc_id' => $qiztc_id,
										          'qiz_id' => $value_posttest['qiz_id'],
										          'ques_id' => $value_ques['ques_id'],
										          'emp_id' => $sess['emp_id'],
										          'cosen_id' => $cosen_id
										      );
										      if($fetch_chk_ques==0){
										      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
										      		array_push($ques_id_arr, $value_ques['ques_id']);
										      	}
										        $this->db->insert('lms_ques_tc',$arr_main);
										      }else{
										      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
										      		array_push($ques_id_arr, $value_ques['ques_id']);
										      	}
										      }
										}
									}
								}
								$arr['posttest_arr'][$key_posttest]['qiztc_id'] = $qiztc_id;
							}else{
								$arr['posttest_arr'][$key_posttest]['status_tc'] = "0";
								$arr['posttest_arr'][$key_posttest]['sum_score'] = "0";
								$arr['posttest_arr'][$key_posttest]['per_score'] = "0";
								$arr['posttest_arr'][$key_posttest]['endstatus'] = "0";

								    $fetch_loop = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_posttest['qiz_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiztc_isDelete="0"','limit_val DESC');
								    $limit_val = countArray($fetch_loop)>0?intval($fetch_loop['limit_val'])+1:1;
								    $arr_main = array(
								        'qiz_id' => $value_posttest['qiz_id'],
								        'emp_id' => $sess['emp_id'],
								        'time_start' => date('Y-m-d H:i'),
								        'time_mod' => date('Y-m-d H:i'),
								        'qiz_status' => '1',
								        'limit_val' => $limit_val,
										'cosen_id' => $cosen_id
								    );
								    $this->db->insert('lms_qiz_tc',$arr_main);
	      							$qiztc_id = $this->db->insert_id();
								$arr['posttest_arr'][$key_posttest]['qiztc_id'] = $qiztc_id;

								$order_question = "lms_ques.ques_id ASC";
								$quiz_numofshown = $value_posttest['quiz_numofshown'];
								if($value_posttest['quiz_random']=="1"){
									$order_question = "RAND()";
								}
								
								$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"',$order_question,'',$quiz_numofshown);
								if(countArray($fetch_ques)>0){
									foreach ($fetch_ques as $key_ques => $value_ques) {
									      $fetch_chk_ques = $this->func_query->numrows('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'" and qiz_id="'.$value_posttest['qiz_id'].'" and ques_id="'.$value_ques['ques_id'].'"');

									      $arr_main = array(
									          'qiztc_id' => $qiztc_id,
									          'qiz_id' => $value_posttest['qiz_id'],
									          'ques_id' => $value_ques['ques_id'],
									          'emp_id' => $sess['emp_id'],
										      'cosen_id' => $cosen_id
									      );
									      if($fetch_chk_ques==0){
									      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
									      		array_push($ques_id_arr, $value_ques['ques_id']);
									      	}
									        $this->db->insert('lms_ques_tc',$arr_main);
									      }else{
									      	if(!in_array($value_ques['ques_id'], $ques_id_arr)){
									      		array_push($ques_id_arr, $value_ques['ques_id']);
									      	}
									      }
									}
								}

							}
							if(countArray($ques_id_arr)>0){
								$order_question = "lms_ques.ques_id ASC";
								if($value_posttest['quiz_random']=="1"){
									$order_question = "RAND()";
								}
								$where_arr = 'and ques_id in ('.implode(',', $ques_id_arr).')';
								$fetch_ques = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0" '.$where_arr,$order_question);
							}else{
								$fetch_ques = array();
							}
							if(countArray($fetch_ques)>0){
								foreach ($fetch_ques as $key_ques => $value_ques) {
									if($value_ques['ques_type']=="multi"||$value_ques['ques_type']=="2choice"){
										$fetch_multi = $this->func_query->query_row('lms_ques_mul','','','','lms_ques_mul.ques_id="'.$value_ques['ques_id'].'"');
										$fetch_ques[$key_ques]['multi'] = $fetch_multi;
									}
									$fetch_chktc_ques = $this->func_query->query_row('lms_ques_tc','','','','qiz_id="'.$value_posttest['qiz_id'].'" and cosen_id="'.$cosen_id.'" and emp_id="'.$sess['emp_id'].'" and ques_id="'.$value_ques['ques_id'].'" and qiztc_id="'.$qiztc_id.'"');
									if(countArray($fetch_chktc_ques)>0){
										$fetch_ques[$key_ques]['tc'] = $fetch_chktc_ques;
									}
								}
							}
							$arr['posttest_arr'][$key_posttest]['question'] = $fetch_ques;
					}
				}
		}
		$arr['document_cos'] = $this->func_query->query_result('lms_cos_fil','','','','cos_id="'.$cos_id.'" and fil_status="1" and fil_isDelete="0"');

		$arr['survey_arr'] = $this->func_query->query_result('lms_survey','','','','cos_id="'.$cos_id.'" and sv_isDelete="0" and sv_status="1" and ((survey_open="0000-00-00 00:00:00" and survey_end="0000-00-00 00:00:00") or (survey_open <= "'.$date_now.'" and  survey_end >= "'.$date_now.'"))','sv_id ASC');
		if(countArray($arr['survey_arr'])>0){

				foreach ($arr['survey_arr'] as $key_survey => $value_survey) {
	                $fetch_chkques = $this->func_query->numrows('lms_survey_de','','','','sv_id="'.$value_survey['sv_id'].'" and svde_status="1" and svde_isDelete="0"');
	                if($fetch_chkques==0){
	                 	unset($arr['survey_arr'][$key_survey]);
	                }
				}
				if(countArray($arr['survey_arr'])>0){
					foreach ($arr['survey_arr'] as $key_sv => $value_sv) {
						$qnu_id = "";
						$fetch_status = $this->func_query->query_row('lms_qn_user','','','','emp_id="'.$sess['emp_id'].'" and sv_id="'.$value_sv['sv_id'].'" and cosen_id="'.$cosen_id.'"');
						if(countArray($fetch_status)>0){
							$arr['survey_arr'][$key_sv]['status_tc'] = $fetch_status['qnu_status'];
							$arr['survey_arr'][$key_sv]['date_tc'] = $fetch_status['qnu_datetime'];
							$arr['survey_arr'][$key_sv]['suggestion_tc'] = $fetch_status['qnu_suggestion'];
							$qnu_id = $fetch_status['qnu_id'];
						}else{
							$arr['survey_arr'][$key_sv]['status_tc'] = '0';
							$arr['survey_arr'][$key_sv]['date_tc'] = '';
							$arr['survey_arr'][$key_sv]['suggestion_tc'] = '';
						}
						$fetch_detail = $this->func_query->query_result('lms_survey_de','','','','sv_id="'.$value_sv['sv_id'].'" and svde_status="1" and svde_isDelete="0"','svde_id ASC');
						if(countArray($fetch_detail)>0){
							foreach ($fetch_detail as $key_detail => $value_detail) {
								if($qnu_id!=""){
									$fetch_detailtc = $this->func_query->query_row('lms_qn_user_de','','','','svde_id="'.$value_detail['svde_id'].'" and qnu_id="'.$qnu_id.'"');
									if(countArray($fetch_detailtc)>0){
										$fetch_detail[$key_detail]['detail_tc'] = $fetch_detailtc;
									}
								}
							}
						}
						$arr['survey_arr'][$key_sv]['sv_detail'] = $fetch_detail;
					}
				}
		}
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/course_detail', $arr );
	}

}
?>
