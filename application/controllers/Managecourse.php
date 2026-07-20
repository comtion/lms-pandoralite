<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Managecourse extends CI_Controller
{

	public function course_groups()
	{
		$arr['page'] = 'managecourse/course_groups';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");

		if (empty($sess)) {
			redirect(base_url() . 'dashboard/logout?redirect=' . $arr['page']);
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
		$arr['main_menu'] = $this->manage->checkmenu();
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
		$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
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
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$num_approve = 0;
		$rechk_approve = $this->func_query->query_result('lms_cog', '', '', '', 'com_id="' . $sess['com_id'] . '" and cg_isDelete="0"');
		if (countArray($rechk_approve) > 0) {
			foreach ($rechk_approve as $key_approve => $value_approve) {
				$arr_approve = explode(',', $value_approve['cg_approve_by']);
				if (countArray($arr_approve) > 0 && in_array($sess['u_id'], $arr_approve)) {
					$num_approve++;
				}
			}
		}
		$arr['is_approve'] = $num_approve > 0 ? 1 : 0;

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/course_groups', $arr);
	}

	public function courses_all($cos_id = "")
	{
		$arr['page'] = 'managecourse/courses_all';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$sess = $this->session->userdata("user");
		if (empty($sess)) {
			redirect(base_url() . 'dashboard/logout?redirect=' . $arr['page']);
		}
		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['cos_id'] = $cos_id;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$this->load->model('Setting_model', 'setting', FALSE);
		$this->setting->loadDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['main_menu'] = $this->manage->checkmenu();
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
		$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
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
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}

		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/courses_all', $arr);
	}


	public function courses_demo($cos_id, $dashboard = 0)
	{
		$arr['page'] = 'managecourse/courses_demo/' . $cos_id;
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$lang_select = isset($_REQUEST['course_lang']) ? $_REQUEST['course_lang'] : "";
		/*if($lang_select!=""){
		    if($lang_select=="thai"){
		    	$lang = 'thai';
		    }else if($lang_select=="english"){
		    	$lang = 'english';
		    }else{
		    	$lang = 'japan';
		    }
		}*/
		$arr['isDashboard'] = $dashboard;
		$sess = $this->session->userdata("user");

		if (empty($sess)) {
			redirect(base_url() . 'dashboard/logout?redirect=' . $arr['page']);
		}
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$this->lang->load($lang, $lang);
		$this->session->set_userdata('viewcourse', 'demo');
		$arr['lang_select'] = $lang_select;
		$arr['isFirsttime'] = "0";
		if ($lang_select == "") {
			$lang_select = $lang;
			$arr['isFirsttime'] = "1";
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
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();

		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_add'] = $this->manage->chk_permission($arr['page'], 'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		$arr['btn_print'] = $this->manage->chk_permission($arr['page'], 'ru_print');
		$arr['cos_id'] = $cos_id;

		date_default_timezone_set("Asia/Bangkok");
		$date_now = date('Y-m-d H:i');

		$arr['course_main'] = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
		if (countArray($arr['course_main']) == 0) {
			redirect(base_url() . 'dashboard');
		}
		$var_chk = 0;
		$arr['isApprove'] = "0";
		$fetch_chk = $this->func_query->query_result('lms_cosincg', 'lms_cog', 'lms_cog.cg_id = lms_cosincg.cg_id', '', 'lms_cosincg.course_id="' . $cos_id . '" and lms_cosincg.status_cg="1"');
		if (countArray($fetch_chk) > 0) {
			foreach ($fetch_chk as $key_chk => $value_chk) {
				$cg_approve_by = explode(',', $value_chk['cg_approve_by']);
				if (in_array($sess['u_id'], $cg_approve_by)) {
					$var_chk++;
					$arr['isApprove'] = "1";
				}
			}
		}
		if ($arr['course_main']['cos_createby'] == $sess['u_id']) {
			$var_chk++;
		}
		if ($var_chk == 0) {
			if ($sess['emp_id'] != "1" && !in_array($sess['ug_id'], array('1', '2', '6', '7', '9'))) {
				redirect(base_url() . 'dashboard');
			}
		}
		$cos_lang = explode(',', $arr['course_main']['cos_lang']);
		$arr['course_main']['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
		$arr['course_main']['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
		$arr['course_main']['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
		$cname = "";
		$cdetail = "";
		$cos_langtxt = "";

		$arr['cos_id'] = $cos_id;
		if ($lang_select == "thai") {
			$cos_langtxt = "th";
			$arr['course_main']['select_lang'] = 'th';
			$arr['course_main']['is_lang_user_th'] = 'selected';
			if ($arr['course_main']['isTH'] == "1") {
				$cname = $arr['course_main']['cname_th'];
				$cdetail = $arr['course_main']['cdesc_th'];
			} else {
				if ($arr['course_main']['cname_th'] == "") {
					$cos_langtxt = "eng";
					$arr['course_main']['select_lang'] = 'eng';
					$arr['course_main']['is_lang_user_eng'] = 'selected';
					$cname = $arr['course_main']['cname_eng'];
					$cdetail = $arr['course_main']['cdesc_eng'];
				}
				if ($cname == "") {
					$cos_langtxt = "jp";
					$arr['course_main']['select_lang'] = 'jp';
					$arr['course_main']['is_lang_user_jp'] = 'selected';
					$cname = $arr['course_main']['cname_jp'];
					$cdetail = $arr['course_main']['cdesc_jp'];
				}
			}
		} else if ($lang_select == "english") {
			$cos_langtxt = "eng";
			$arr['course_main']['select_lang'] = 'eng';
			$arr['course_main']['is_lang_user_eng'] = 'selected';
			if ($arr['course_main']['isENG'] == "1") {
				$cname = $arr['course_main']['cname_eng'];
				$cdetail = $arr['course_main']['cdesc_eng'];
			} else {
				if ($arr['course_main']['cname_eng'] == "") {
					$cos_langtxt = "th";
					$arr['course_main']['select_lang'] = 'th';
					$arr['course_main']['is_lang_user_th'] = 'selected';
					$cname = $arr['course_main']['cname_th'];
					$cdetail = $arr['course_main']['cdesc_th'];
				}
				if ($cname == "") {
					$cos_langtxt = "jp";
					$arr['course_main']['select_lang'] = 'jp';
					$arr['course_main']['is_lang_user_jp'] = 'selected';
					$cname = $arr['course_main']['cname_jp'];
					$cdetail = $arr['course_main']['cdesc_jp'];
				}
			}
		} else {
			$cos_langtxt = "jp";
			$arr['course_main']['select_lang'] = 'jp';
			$arr['course_main']['is_lang_user_jp'] = 'selected';
			if ($arr['course_main']['isJP'] == "1") {
				$cname = $arr['course_main']['cname_jp'];
				$cdetail = $arr['course_main']['cdesc_jp'];
			} else {
				if ($arr['course_main']['cname_jp'] == "") {
					$cos_langtxt = "eng";
					$arr['course_main']['select_lang'] = 'eng';
					$arr['course_main']['is_lang_user_eng'] = 'selected';
					$cname = $arr['course_main']['cname_eng'];
					$cdetail = $arr['course_main']['cdesc_eng'];
				}
				if ($cname == "") {
					$cos_langtxt = "th";
					$arr['course_main']['select_lang'] = 'th';
					$arr['course_main']['is_lang_user_th'] = 'selected';
					$cname = $arr['course_main']['cname_th'];
					$cdetail = $arr['course_main']['cdesc_th'];
				}
			}
		}

		$txt_period_course = label('UnlimitedTime');
		// and ((date_start="0000-00-00 00:00:00" and date_end="0000-00-00 00:00:00") or (date_start <= "'.$date_now.'" and "'.$date_now.'" <= date_end))
		$where = 'cos_id = "' . $cos_id . '" and cosde_isDelete="0" and cosde_status="1"';
		$result_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', $where, 'cosde_id DESC');
		if (countArray($result_detail) > 0) {
			$txt_period_course = lms_format_period_range($result_detail['date_start'], $result_detail['date_end'], $lang, ' -<br>');
		}
		$fetch_com = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $arr['course_main']['com_id'] . '"');
		$arr['course_main']['cname'] = $cname;
		$arr['course_main']['cdetail'] = $cdetail;
		$arr['course_main']['com_name'] = $lang_select == "thai" ? $fetch_com['com_name_th'] : $fetch_com['com_name_eng'];
		$arr['course_main']['txt_period_course'] = $txt_period_course;
		$arr['lesson_status'] = 0;
		// and ((time_start="0000-00-00 00:00:00" and time_end="0000-00-00 00:00:00") or (time_start <= "'.$date_now.'" and  time_end >= "'.$date_now.'"))
		$arr['lesson_arr'] = $this->func_query->query_result('lms_les', '', '', '', 'cos_id="' . $cos_id . '" and les_isDelete="0" and les_status="1"', 'les_sequences ASC');
		if (countArray($arr['lesson_arr']) > 0) {
			$arr_les_id = array();
			foreach ($arr['lesson_arr'] as $key_lesson => $value_lesson) {

				if ($lang_select == "thai") {
					$les_name = $value_lesson['les_name_th'] != "" ? $value_lesson['les_name_th'] : $value_lesson['les_name_eng'];
					$les_name = $les_name != "" ? $les_name : $value_lesson['les_name_jp'];
					$les_info = $value_lesson['les_info_th'] != "" ? $value_lesson['les_info_th'] : $value_lesson['les_info_eng'];
					$les_info = $les_info != "" ? $les_info : $value_lesson['les_info_jp'];
				} else if ($lang_select == "english") {
					$les_name = $value_lesson['les_name_eng'] != "" ? $value_lesson['les_name_eng'] : $value_lesson['les_name_th'];
					$les_name = $les_name != "" ? $les_name : $value_lesson['les_name_jp'];
					$les_info = $value_lesson['les_info_eng'] != "" ? $value_lesson['les_info_eng'] : $value_lesson['les_info_th'];
					$les_info = $les_info != "" ? $les_info : $value_lesson['les_info_jp'];
				} else {
					$les_name = $value_lesson['les_name_jp'] != "" ? $value_lesson['les_name_jp'] : $value_lesson['les_name_eng'];
					$les_name = $les_name != "" ? $les_name : $value_lesson['les_name_th'];
					$les_info = $value_lesson['les_info_jp'] != "" ? $value_lesson['les_info_jp'] : $value_lesson['les_info_eng'];
					$les_info = $les_info != "" ? $les_info : $value_lesson['les_info_th'];
				}
				$arr['lesson_arr'][$key_lesson]['les_name'] = $les_name;
				$arr['lesson_arr'][$key_lesson]['les_info'] = $les_info;
				if ($value_lesson['les_type'] == "2") {
					$fetch_scm = $this->func_query->query_row('lms_scm', '', '', '', 'lessons_id="' . $value_lesson['les_id'] . '"');
					$arr['lesson_arr'][$key_lesson]['scm_data'] = $fetch_scm;
				} else {
					$fetch_med = $this->func_query->query_result('lms_med', '', '', '', 'lessons_id="' . $value_lesson['les_id'] . '"');
					$arr['lesson_arr'][$key_lesson]['med_data'] = $fetch_med;
					$fetch_doc = $this->func_query->query_result('lms_fil', '', '', '', 'lessons_id="' . $value_lesson['les_id'] . '"');
					$arr['lesson_arr'][$key_lesson]['doc_data'] = $fetch_doc;
				}
				if (!in_array($value_lesson['les_id'], $arr_les_id)) {
					array_push($arr_les_id, $value_lesson['les_id']);
				}
			}
			$txt_les_id = implode(',', $arr_les_id);
			$value_status = 0;
			$arr['lesson_status'] = $value_status;
		}
		$arr['is_public'] = 1;
		// and ((period_open="0000-00-00 00:00:00" and period_end="0000-00-00 00:00:00") or (period_open <= "'.$date_now.'" and  period_end >= "'.$date_now.'"))
		$arr['pretest_arr'] = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_isDelete="0" and quiz_status="1" and quiz_show="1" and quiz_type="1"', 'lms_qiz.qiz_id ASC');
		$loop_run = 1;
		if (countArray($arr['pretest_arr']) > 0) {
			foreach ($arr['pretest_arr'] as $key_pretest => $value_pretest) {
				$arr['pretest_arr'][$key_pretest]['status_tc'] = "0";
				$arr['pretest_arr'][$key_pretest]['sum_score'] = "0";
				$arr['pretest_arr'][$key_pretest]['per_score'] = "0";
				$arr['pretest_arr'][$key_pretest]['endstatus'] = "0";

				$quiz_numofshown = $value_pretest['quiz_numofshown'];
				$order_question = "lms_ques.ques_id ASC";
				if ($value_pretest['quiz_random'] == "1") {
					$order_question = "RAND()";
				}
				$fetch_ques = $this->func_query->query_result('lms_ques', '', '', '', 'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" ', $order_question, '', $quiz_numofshown);
				if (countArray($fetch_ques) > 0) {
					foreach ($fetch_ques as $key_ques => $value_ques) {
						if ($value_ques['ques_type'] == "multi" || $value_ques['ques_type'] == "2choice") {
							$fetch_multi = $this->func_query->query_row('lms_ques_mul', '', '', '', 'lms_ques_mul.ques_id="' . $value_ques['ques_id'] . '"');
							$fetch_ques[$key_ques]['multi'] = $fetch_multi;
						}
					}
				} else {
					$arr['is_public'] = 0;
				}
				$arr['pretest_arr'][$key_pretest]['question'] = $fetch_ques;
			}
		}
		$arr['loop_run'] = $loop_run;
		// and ((period_open="0000-00-00 00:00:00" and period_end="0000-00-00 00:00:00") or (period_open <= "'.$date_now.'" and  period_end >= "'.$date_now.'"))
		$where = 'cos_id="' . $cos_id . '" and quiz_isDelete="0" and quiz_status="1" and quiz_show="1" and quiz_type="2"';
		$arr['posttest_arr'] = $this->func_query->query_result('lms_qiz', '', '', '', $where, 'lms_qiz.qiz_id ASC');
		if (countArray($arr['posttest_arr']) > 0) {
			foreach ($arr['posttest_arr'] as $key_posttest => $value_posttest) {
				$arr['posttest_arr'][$key_posttest]['status_tc'] = "0";
				$arr['posttest_arr'][$key_posttest]['sum_score'] = "0";
				$arr['posttest_arr'][$key_posttest]['per_score'] = "0";
				$arr['posttest_arr'][$key_posttest]['endstatus'] = "0";
				$order_question = "lms_ques.ques_id ASC";
				$quiz_numofshown = $value_posttest['quiz_numofshown'];
				if ($value_posttest['quiz_random'] == "1") {
					$order_question = "RAND()";
				}
				$fetch_ques = $this->func_query->query_result('lms_ques', '', '', '', 'qiz_id="' . $value_posttest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" ', $order_question, '', $quiz_numofshown);
				if (countArray($fetch_ques) > 0) {
					foreach ($fetch_ques as $key_ques => $value_ques) {
						if ($value_ques['ques_type'] == "multi" || $value_ques['ques_type'] == "2choice") {
							$fetch_multi = $this->func_query->query_row('lms_ques_mul', '', '', '', 'lms_ques_mul.ques_id="' . $value_ques['ques_id'] . '"');
							$fetch_ques[$key_ques]['multi'] = $fetch_multi;
						}
					}
				} else {
					$arr['is_public'] = 0;
				}
				$arr['posttest_arr'][$key_posttest]['question'] = $fetch_ques;
			}
		}
		$arr['document_cos'] = $this->func_query->query_result('lms_cos_fil', '', '', '', 'cos_id="' . $cos_id . '" and fil_status="1" and fil_isDelete="0"');
		// and ((survey_open="0000-00-00 00:00:00" and survey_end="0000-00-00 00:00:00") or (survey_open <= "'.$date_now.'" and  survey_end >= "'.$date_now.'"))
		$arr['survey_arr'] = $this->func_query->query_result('lms_survey', '', '', '', 'cos_id="' . $cos_id . '" and sv_isDelete="0" and sv_status="1"', 'sv_id ASC');
		if (countArray($arr['survey_arr']) > 0) {
			foreach ($arr['survey_arr'] as $key_sv => $value_sv) {
				$qnu_id = "";
				$arr['survey_arr'][$key_sv]['status_tc'] = '0';
				$arr['survey_arr'][$key_sv]['date_tc'] = '';
				$arr['survey_arr'][$key_sv]['suggestion_tc'] = '';
				$fetch_detail = $this->func_query->query_result('lms_survey_de', '', '', '', 'sv_id="' . $value_sv['sv_id'] . '" and svde_status="1" and svde_isDelete="0"', 'svde_id ASC');
				if (countArray($fetch_detail) > 0) {
					$arr['survey_arr'][$key_sv]['sv_detail'] = $fetch_detail;
				} else {
					unset($arr['survey_arr'][$key_sv]);
					//$arr['is_public'] = 0;
				}
			}
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
		$this->home->closeDB();
		$this->foot->closeDB();
		$this->load->view('frontend/course_detail_demo', $arr);
	}
}
