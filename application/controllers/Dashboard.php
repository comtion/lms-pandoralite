<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
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
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "dashboard";

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->load->model('Dashboard_model', 'dashboard', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->dashboard->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$this->load->model('User_model', 'login', false);
		$this->load->model('Home_model', 'home', false);
		$this->home->loadDB();
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");
			date_default_timezone_set("Asia/Bangkok");

			$arr['redirect_val'] = $this->session->userdata("redirect_val");
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			$arr_role_fd = array();

			$fetch_chkfirsttime = $this->func_query->query_row('lms_emp', '', '', '', 'emp_id="' . $sess['emp_id'] . '"');
			$arr['emp_firsttime'] = isset($fetch_chkfirsttime['emp_firsttime']) ? $fetch_chkfirsttime['emp_firsttime'] : "";
			if ($arr['emp_firsttime'] == "1") {
				$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $sess['com_id'] . '"');
				$fetch_detail = $this->func_query->query_row('lms_about', '', '', '', 'da_id="1"');
				$arr_welcome = array();
				$arr_welcome['wctitle_a'] = $fetch_detail['da_wctitle_th'];
				$arr_welcome['wcmessage_a'] = $fetch_detail['da_wcmessage_th'];
				$arr_welcome['wctitle_b'] = $fetch_company['com_wctitle_th'];
				$arr_welcome['wcmessage_b'] = $fetch_company['com_wcmessage_th'];

				if ($lang == "english") {
					$arr_welcome['wctitle_a'] = $fetch_detail['da_wctitle_en'];
					$arr_welcome['wcmessage_a'] = $fetch_detail['da_wcmessage_en'];
					$arr_welcome['wctitle_b'] = $fetch_company['com_wctitle_eng'];
					$arr_welcome['wcmessage_b'] = $fetch_company['com_wcmessage_eng'];
				} else if ($lang == "japan") {
					$arr_welcome['wctitle_a'] = $fetch_detail['da_wctitle_jp'];
					$arr_welcome['wcmessage_a'] = $fetch_detail['da_wcmessage_jp'];
					$arr_welcome['wctitle_b'] = $fetch_company['com_wctitle_jp'];
					$arr_welcome['wcmessage_b'] = $fetch_company['com_wcmessage_jp'];
				}
				$arr['arr_welcome'] = $arr_welcome;
				$arr['arr_msg_confirm'] = $this->func_query->query_result('lms_confirmmsg', '', '', '', 'conmsg_isDelete="0" and conmsg_status="1"');
			}

			$arr['arr_permission'] = $this->manage->chk_permission_page();
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

			$fetch_loop = $this->func_query->query_result('lms_func_dashboard', '', '', '', '');
			if (countArray($fetch_loop) > 0) {
				foreach ($fetch_loop as $key) {
					$fetch_chk = $this->func_query->query_row('lms_role_fd', '', '', '', 'ug_id="' . $arr['user']['ug_id'] . '" and fd_id="' . $key['fd_id'] . '"');
					if (isset($fetch_chk)) {
						array_push($arr_role_fd, $key['fd_id']);
					}
				}
			}
			$arr['arr_role_fd'] = $arr_role_fd;
			$arr['profile'] = $this->manage->query_data_onupdate($arr['user']['u_id'], 'lms_usp', 'u_id');
			$arr['course_total'] = $this->manage->course_total();

			$PC_log = $this->dashboard->log_usersys('PC');
			$Mobile_log = $this->dashboard->log_usersys('Mobile');
			$Tablet_log = $this->dashboard->log_usersys('Tablet');
			$arr['course_select'] = $this->dashboard->course_select();

			$arr['coursetotal'] = $this->manage->countamount_emp('coursetotal');
			$arr['enroll'] = $this->manage->countamount_emp('enroll');
			$arr['success'] = $this->manage->countamount_emp('success');
			$arr['inProcess'] = $this->manage->countamount_emp('inProcess');
			$arr['not_start'] = $this->manage->countamount_emp('not_start');

			$approver_cog = array();
			$fetch_cog = $this->func_query->query_result('lms_cog', '', '', '', 'com_id="' . $sess['com_id'] . '" and cg_isDelete="0" and cg_approve="1" and cg_status="1"');
			if (countArray($fetch_cog) > 0) {
				foreach ($fetch_cog as $key_cog => $value_cog) {

					$cg_approve_by = explode(',', $value_cog['cg_approve_by']);
					if (countArray($cg_approve_by) > 0) {
						for ($i = 0; $i < countArray($cg_approve_by); $i++) {
							if ($sess['u_id'] == $cg_approve_by[$i]) {
								if (!in_array($value_cog['cg_id'], $approver_cog)) {
									array_push($approver_cog, $value_cog['cg_id']);
								}
							}
						}
					}
				}
			}
			if (countArray($approver_cog) > 0) {
				$approver_cog = implode(',', $approver_cog);
				$fetch_course_approve = $this->func_query->query_result('lms_cos', '', '', '', 'com_id="' . $sess['com_id'] . '" and cos_id in (select course_id from lms_cosincg where cg_id in (' . $approver_cog . ')) and cos_approve="0" and cos_public="1" and cos_isDelete="0"');
				if (countArray($fetch_course_approve) > 0) {
					foreach ($fetch_course_approve as $key_cos_approve => $value_cos_approve) {
						$cos_lang = explode(',', $value_cos_approve['cos_lang']);
						$fetch_course_approve[$key_cos_approve]['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
						$fetch_course_approve[$key_cos_approve]['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
						$fetch_course_approve[$key_cos_approve]['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";

						$fetch_user = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_emp.emp_id=lms_usp.emp_id', '', 'lms_usp.u_id="' . $value_cos_approve['cos_createby'] . '"');
						$fetch_course_approve[$key_cos_approve]['user_creator'] = $lang == "thai" ? $fetch_user['fullname_th'] : $fetch_user['fullname_en'];
					}
				}
			} else {
				$fetch_course_approve = array();
			}
			$arr['fetch_course_approve'] = $fetch_course_approve;
			//if(in_array($sess['ug_id'], array('2','6'))){
			$fetch_survey_public_approve = $this->func_query->query_result('lms_sv', '', '', '', 'com_id="' . $sess['com_id'] . '" and sv_public="1" and sv_approve="0" and sv_isDelete="0" and sv_status = 1');
			if (countArray($fetch_survey_public_approve) > 0) {
				foreach ($fetch_survey_public_approve as $key_survey_public => $value_survey_public) {
					$fetch_user = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_emp.emp_id=lms_usp.emp_id', '', 'lms_usp.u_id="' . $value_survey_public['sv_createby'] . '"');
					$fetch_survey_public_approve[$key_survey_public]['user_creator'] = $lang == "thai" ? $fetch_user['fullname_th'] : $fetch_user['fullname_en'];

					$num_question = $this->func_query->numrows('lms_svde', '', '', '', 'sv_id="' . $value_survey_public['sv_id'] . '" and svde_isDelete="0"');

					$arr_user = $value_survey_public['sv_userapprove'] != "" ? explode(',', $value_survey_public['sv_userapprove']) : array();
					if (isset($arr_user)) {
						if (!in_array($sess['emp_id'], $arr_user)) {
							unset($fetch_survey_public_approve[$key_survey_public]);
						} else {
							if ($num_question == 0) {
								unset($fetch_survey_public_approve[$key_survey_public]);
							}
						}
					} else {
						unset($fetch_survey_public_approve[$key_survey_public]);
					}
				}
			}
			/*}else{
					$fetch_survey_public_approve = array();
				}*/
			$arr['fetch_survey_public_approve'] = $fetch_survey_public_approve;

			$arr['company_arr'] = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_id != "2"');
			foreach ($arr['company_arr'] as $key_com => $value_com) {
				$arr['company_arr'][$key_com]['usertotal'] = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value_com['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
				/*$coursetotal = 0; and (lms_usp.expiredate > "'.date('Y-m-d H:i').'" or lms_usp.expiredate = "0000-00-00 00:00:00")
				$fetch_cos = $this->func_query->query_result('lms_cos','','','','com_id="'.$value_com['com_id'].'" and cos_approve="1" and cos_public="1" and cos_isDelete="0"');
				if(countArray($fetch_cos)>0){
					foreach ($fetch_cos as $key_cos => $value_cos) {
                  			$result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value_cos['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
	                  		if($result_chkcg>0){
	                  			$coursetotal++;
	                  		}

					}
				}*/

				$where = '';
				$where = ' and lms_cos.com_id = "' . $value_com['com_id'] . '"';
				$courses_total = $this->func_query->query_result('lms_cos', '', '', '', 'cos_approve="1" and cos_public="1" and cos_isDelete="0"' . $where);
				//  and cos_status="1"
				$courses_ongoing = 0;
				$courses_completed = 0;
				$courses_incoming = 0;
				$courses_close = 0;
				if (countArray($courses_total) > 0) {
					foreach ($courses_total as $key_list => $value_list) {
						if (isset($courses_total[$key_list])) {
							$result_chkcg = $this->func_query->numrows('lms_cosincg', 'lms_cog', 'lms_cosincg.cg_id = lms_cog.cg_id', '', 'lms_cosincg.course_id="' . $value_list['cos_id'] . '" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
							if ($result_chkcg == 0) {
								unset($courses_total[$key_list]);
							}
						}
					}
				}
				if (countArray($courses_total) > 0) {
					foreach ($courses_total as $key_list => $value_list) {
						$completed = $this->func_query->numrows('lms_cos_enroll', '', '', '', 'cos_id = "' . $value_list['cos_id'] . '" and cosen_status="1" and cosen_status_sub="1"');
						$courses_completed += $completed;
						$fetch_chk_ug = $this->func_query->query_row('lms_cos_detail', '', '', '', 'lms_cos_detail.cos_id = "' . $value_list['cos_id'] . '"');
						if (isset($fetch_chk_ug)) {
							if ($fetch_chk_ug['date_start'] != "0000-00-00 00:00:00" && $fetch_chk_ug['date_end'] != "0000-00-00 00:00:00") {
								if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) > date('Y-m-d H:i')) {
									$courses_incoming++;
								}
								if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_end'])) < date('Y-m-d H:i')) {
									$courses_close++;
								}
								if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) <= date('Y-m-d H:i') && date('Y-m-d H:i', strtotime($fetch_chk_ug['date_end'])) >= date('Y-m-d H:i')) {
									$courses_ongoing++;
								}
							} else {
								$courses_ongoing++;
							}
						} else {
							$courses_ongoing++;
						}
					}
				}
				$courses_total = $courses_ongoing + $courses_incoming + $courses_close;

				$where = 'sv_isDelete="0" and lms_sv.com_id="' . $value_com['com_id'] . '" and sv_public="1" and sv_approve="1"';
				$survey_total = $this->func_query->numrows('lms_sv', '', '', '', $where, '');

				$arr['company_arr'][$key_com]['coursetotal'] = $courses_total;
				$arr['company_arr'][$key_com]['surveytotal'] = $survey_total;
			}

			if (!isset($PC_log)) {
				$PC_log = 0;
			}
			if (!isset($Mobile_log)) {
				$Mobile_log = 0;
			}
			if (!isset($Tablet_log)) {
				$Tablet_log = 0;
			}
			$total_log = $PC_log + $Mobile_log + $Tablet_log;
			$arr['PC_log'] = $total_log > 0 ? number_format(($PC_log / $total_log) * 100, 2) : 0;
			$arr['Mobile_log'] = $total_log > 0 ? number_format(($Mobile_log / $total_log) * 100, 2) : 0;
			$arr['Tablet_log'] = $total_log > 0 ? number_format(($Tablet_log / $total_log) * 100, 2) : 0;
			$lang_select = "";
			if ($lang == "thai") {
				$lang_select = "th";
			} else if ($lang == "english") {
				$lang_select = "eng";
			} else {
				$lang_select = "jp";
			}
			$arr['arr_surveypublic'] = $this->func_query->query_result('lms_sv', '', '', '', 'sv_public="1" and sv_approve="1" and sv_isDelete="0" and ((sv_open="0000-00-00 00:00:00" and sv_end="0000-00-00 00:00:00")or("' . date('Y-m-d H:i') . '" between sv_open and sv_end))');
			if (countArray($arr['arr_surveypublic']) > 0) {
				foreach ($arr['arr_surveypublic'] as $key_publicsv => $value_publicsv) {
					if ($lang == "thai") {
						$arr['arr_surveypublic'][$key_publicsv]['sv_end'] = $value_publicsv['sv_end'] != "0000-00-00 00:00:00" ? date('d', strtotime($value_publicsv['sv_end'])) . " " . $thaimonth[intval(date('m', strtotime($value_publicsv['sv_end'])))] . " " . (date('Y', strtotime($value_publicsv['sv_end'])) + 543) . " " . date('H:i', strtotime($value_publicsv['sv_end'])) : label('UnlimitedTime');
					} else {
						$arr['arr_surveypublic'][$key_publicsv]['sv_end'] = $value_publicsv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($value_publicsv['sv_end'])) : label('UnlimitedTime');
					}
					$fetch_tc = $this->func_query->numrows('lms_sv_tc', '', '', '', 'sv_id="' . $value_publicsv['sv_id'] . '" and emp_id="' . $sess['emp_id'] . '" and svtc_isDelete="0"');
					if (!isset($fetch_tc['svtc_finishtime'])) {
						$fetch_posi = $this->func_query->numrows('lms_sv_pm', '', '', '', 'sv_id="' . $value_publicsv['sv_id'] . '" and posi_id="' . $sess['posi_id'] . '"');
						if ($fetch_posi == 0) {
							unset($arr['arr_surveypublic'][$key_publicsv]);
						}
					} else {
						if ($fetch_tc['svtc_finishtime'] != "0000-00-00 00:00:00") {
							unset($arr['arr_surveypublic'][$key_publicsv]);
						}
					}
				}
			}
			$num_approve = 0;
			$rechk_approve_cog = $this->func_query->query_result('lms_cog', '', '', '', 'com_id="' . $sess['com_id'] . '" and cg_isDelete="0"');
			if (countArray($rechk_approve_cog) > 0) {
				foreach ($rechk_approve_cog as $key_approve => $value_approve) {
					$arr_approve = explode(',', $value_approve['cg_approve_by']);
					if (countArray($arr_approve) > 0 && in_array($sess['u_id'], $arr_approve) && $value_approve['cg_approve'] == "2") {
						$num_approve++;
					} else {
						unset($rechk_approve_cog[$key_approve]);
					}
				}
			}
			$arr['rechk_approve_cog'] = $rechk_approve_cog;
			/*$arr['scoreAvg'] = $this->manage->chk_scoretotal();
			$arr['can_registered'] = $this->manage->chk_course_registered();
			$arr['query_registered'] = $this->manage->query_course_registered();
			$arr['course_not_register'] = $this->manage->chk_course_not_register();
			$arr['course_pass'] = $this->manage->chk_course_status('1');
			$arr['course_wait'] = $this->manage->chk_course_status('2');
			$arr['total_student'] = $this->manage->chk_total_status('2');
			$arr['total_course'] = $this->manage->chk_total_status('1');
			$arr['course_not_study'] = $this->manage->chk_course_status('0');*/
			$arr['pic'] = $this->home->getpic_all();
			//Record Log activity
			$this->load->model('Log_model', 'lg', false);
			$this->lg->loadDB();
			$this->lg->record('dashboard', 'enter dashboard.');
			$this->lg->closeDB();

			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->view('frontend/dashboard', $arr);
		}
	}

	public function sortArrayByKey(&$array, $key, $string = false, $asc = true)
	{
		if ($string) {
			usort($array, function ($a, $b) use (&$key, &$asc) {
				if ($asc)    return strcmp(strtolower($a[$key]), strtolower($b[$key]));
				else        return strcmp(strtolower($b[$key]), strtolower($a[$key]));
			});
		} else {
			usort($array, function ($a, $b) use (&$key, &$asc) {
				if ($a[$key] == $b[$key]) {
					return 0;
				}
				if ($asc) return ($a[$key] < $b[$key]) ? -1 : 1;
				else     return ($a[$key] > $b[$key]) ? -1 : 1;
			});
		}
	}

	public function profile($tab = 'setting')
	{

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "profile";

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->load->model('Profile_model', 'profile', false);
		$this->profile->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			$arr['tabshow'] = $tab;
			if ($sess['Is_admin'] == "0") {
				$yourArray = $this->profile->query_timeline($sess);
				$this->sortArrayByKey($yourArray, "datetime_run", true, false);
				$arr['timeline'] = $yourArray;
			} else {
				$arr['timeline'] = "";
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
			$this->load->model('Course_model', 'course', true);
			$this->course->loadDB();
			$arr['company_detail'] = $this->course->query_data_onupdate($arr['com_id'], 'lms_company', 'com_id');
			$arr['certshow'] = $this->profile->loaddata_cert($sess);
			$arr['profile'] = $this->manage->query_data_onupdate($arr['user']['u_id'], 'lms_usp', 'u_id');
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();
			$this->load->view('frontend/profile', $arr);
		}
	}

	public function change_pass()
	{

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "dashboard/change_pass";

		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->load->model('Profile_model', 'profile', false);
		$this->profile->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;

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
			$this->load->model('Course_model', 'course', true);
			$this->course->loadDB();
			$arr['profile'] = $this->manage->query_data_onupdate($arr['user']['u_id'], 'lms_usp', 'u_id');
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();
			$this->load->view('frontend/change_pass', $arr);
		}
	}

	public function loopchk_expireuser()
	{
		$lang = "english";
		$this->load->model('Function_query_model', 'func_query', true);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$dateexpire = date('Y-m-d H:i');
		$msg = "";
		$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
		$fetch_chk_exp = $this->func_query->query_result('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.expiredate<"' . $dateexpire . '" and lms_emp.emp_isDelete="0" and lms_usp.u_isDelete="0"');
		if (countArray($fetch_chk_exp) > 0) {
			$num_exp = 0;
			$list_usp = "";
			foreach ($fetch_chk_exp as $key_exp => $value_exp) {

				$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
				if ($lang != "thai") {
					$date = date('d F Y');
				}
				$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_exp['com_id'] . '"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="2"');
				if (countArray($fetch_formatmail) > 0) {
					$list_usp .= " - " . $value_exp['fullname_en'] . "<br>";
					$num_exp++;
					$subject_th = $fetch_formatmail['smf_subject_th'];
					$subject_en = $fetch_formatmail['smf_subject_en'];
					$message_th = $fetch_formatmail['smf_message_th'];
					$message_en = $fetch_formatmail['smf_message_en'];
					if ($subject_th != "") {
						$subject_th = str_replace("#fullname", $value_exp['fullname_th'], $subject_th);
						$subject_th = str_replace("#username", $value_exp['useri'], $subject_th);
						$subject_th = str_replace("#email", $value_exp['email'], $subject_th);
						$subject_th = str_replace("#coursename", "", $subject_th);
						$subject_th = str_replace("#password", "", $subject_th);
						$subject_th = str_replace("#link_frontend", base_url() . 'dashboard/passexpireonmail/' . $value_exp['u_id'], $subject_th);
						$subject_th = str_replace("#date", $date, $subject_th);
						$subject_th = str_replace("#time", date('H:i'), $subject_th);
						$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
					}
					if ($subject_en != "") {
						$subject_en = str_replace("#fullname", $value_exp['fullname_en'], $subject_en);
						$subject_en = str_replace("#username", $value_exp['useri'], $subject_en);
						$subject_en = str_replace("#email", $value_exp['email'], $subject_en);
						$subject_en = str_replace("#coursename", "", $subject_en);
						$subject_en = str_replace("#password", "", $subject_en);
						$subject_en = str_replace("#link_frontend", base_url() . 'dashboard/passexpireonmail/' . $value_exp['u_id'], $subject_en);
						$subject_en = str_replace("#date", $date, $subject_en);
						$subject_en = str_replace("#time", date('H:i'), $subject_en);
						$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
					}
					if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
						$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '"style="max-width:800px">';
					} else {
						$img_val = '';
					}
					if ($message_th != "") {
						$message_th = str_replace("#fullname", $value_exp['fullname_th'], $message_th);
						$message_th = str_replace("#username", $value_exp['useri'], $message_th);
						$message_th = str_replace("#email", $value_exp['email'], $message_th);
						$message_th = str_replace("#coursename", "", $message_th);
						$message_th = str_replace("#password", "", $message_th);
						$message_th = str_replace("#link_frontend", base_url() . 'dashboard/passexpireonmail/' . $value_exp['u_id'], $message_th);
						$message_th = str_replace("#date", $date, $message_th);
						$message_th = str_replace("#time", date('H:i'), $message_th);
						$message_th = str_replace("#image", $img_val, $message_th);
						$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
					}
					if ($message_en != "") {
						$message_en = str_replace("#fullname", $value_exp['fullname_en'], $message_en);
						$message_en = str_replace("#username", $value_exp['useri'], $message_en);
						$message_en = str_replace("#email", $value_exp['email'], $message_en);
						$message_en = str_replace("#coursename", "", $message_en);
						$message_en = str_replace("#password", "", $message_en);
						$message_en = str_replace("#link_frontend", base_url() . 'dashboard/passexpireonmail/' . $value_exp['u_id'], $message_en);
						$message_en = str_replace("#date", $date, $message_en);
						$message_en = str_replace("#time", date('H:i'), $message_en);
						$message_en = str_replace("#image", $img_val, $message_en);
						$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
					}
					$lang = "english";
					if ($lang == "thai") {
						$this->db->sendEmail($value_exp['email'], $message_th, $subject_th, $fetch_setmail);
					} else {
						$this->db->sendEmail($value_exp['email'], $message_en, $subject_en, $fetch_setmail);
					}
				}
			}

			$msg = "List User Expire (" . $num_exp . "): <br>" . $list_usp;
		} else {
			$msg = "USER EXPIRE : Not Found";
		}
		$this->db->sendEmail('it.bangkok@verztec.com', $msg, 'Notification : USER Expire for LMS IMAT', $fetch_setmail);
		echo $msg;
	}

	public function update_password()
	{
		$this->output->set_content_type('application/json');
		$sess = $this->session->userdata('user');
		if (!is_array($sess) || empty($sess['u_id'])) {
			$this->output->set_status_header(401);
			echo json_encode(array('rs' => false, 'msg' => 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่'));
			return;
		}
		$this->load->database();
		$oldPassword = (string) $this->input->post('oldpass', false);
		$newPassword = (string) $this->input->post('newpass', false);
		$confirmPassword = (string) $this->input->post('confirmpass', false);
		if ($newPassword !== $confirmPassword) {
			echo json_encode(array('rs' => false, 'msg' => 'รหัสผ่านใหม่และการยืนยันไม่ตรงกัน'));
			return;
		}
		if (!$this->isStrongPassword($newPassword)) {
			echo json_encode(array('rs' => false, 'msg' => 'รหัสผ่านต้องมีอย่างน้อย 10 ตัว และมีตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก ตัวเลข และอักขระพิเศษ'));
			return;
		}
		$current = $this->db->select('userp')->get_where('lms_usp', array('u_id' => $sess['u_id'], 'u_isDelete' => 0))->row_array();
		$storedHash = isset($current['userp']) ? (string) $current['userp'] : '';
		$isModern = preg_match('/^\$(2[ayb]|argon2i|argon2id)\$/', $storedHash) === 1;
		$oldMatches = $isModern ? password_verify($oldPassword, $storedHash) : hash_equals($storedHash, hash('sha256', $oldPassword));
		if (!$oldMatches) {
			echo json_encode(array('rs' => false, 'msg' => 'รหัสผ่านเดิมไม่ถูกต้อง'));
			return;
		}

		$history = $this->db->order_by('lp_id', 'DESC')->limit(3)
			->get_where('lms_log_password', array('u_id' => $sess['u_id']))->result_array();
		foreach ($history as $item) {
			$historyHash = (string) $item['lp_password'];
			if (password_verify($newPassword, $historyHash) || hash_equals($historyHash, hash('sha256', $newPassword))) {
				echo json_encode(array('rs' => false, 'msg' => 'ไม่สามารถใช้รหัสผ่านซ้ำกับ 3 ครั้งล่าสุดได้'));
				return;
			}
		}

		$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
		$now = date('Y-m-d H:i:s');
		$this->db->trans_start();
		$this->db->where('u_id', $sess['u_id'])->update('lms_usp', array(
			'userp' => $newHash,
			'expiredate' => date('Y-m-d H:i:s', strtotime('+90 days'))
		));
		$this->db->insert('lms_log_password', array(
			'u_id' => $sess['u_id'], 'lp_datetime' => $now, 'lp_password' => $newHash
		));
		$this->db->trans_complete();
		if (!$this->db->trans_status()) {
			$this->output->set_status_header(500);
			echo json_encode(array('rs' => false, 'msg' => 'ไม่สามารถเปลี่ยนรหัสผ่านได้ กรุณาลองใหม่'));
			return;
		}
		$this->session->sess_regenerate(true);
		log_message('info', 'User changed password: ' . $sess['useri']);
		echo json_encode(array('rs' => true, 'msg' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'));
		return;

		$sess = $this->session->userdata("user");
		$useri = $sess['useri'];

		$confirm_pass = isset($_REQUEST['confirmpass']) ? $_REQUEST['confirmpass'] : "";
		$u_id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : $sess['u_id'];
		$useri = isset($_REQUEST['useri']) ? $_REQUEST['useri'] : $sess['useri'];
		$this->load->model('Function_query_model', 'func_query', true);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->loadDB();
		if ($confirm_pass != "") {
			if (!empty($sess) || isset($_REQUEST['u_id'])) {
				$password_enc = hash('sha256', $confirm_pass);
				$status_duplicate = 0;
				$fetch_logpass = $this->func_query->query_result('lms_log_password', 'lms_usp', 'lms_usp.u_id = lms_log_password.u_id', '', 'lms_usp.useri="' . $useri . '" and lms_usp.u_isDelete="0"', 'lms_log_password.lp_id DESC', '', 3);
				if (countArray($fetch_logpass) > 0) {
					$chkpass = 0;
					foreach ($fetch_logpass as $key_logpass => $value_logpass) {
						if ($password_enc == $value_logpass['lp_password']) {
							$chkpass++;
						}
					}
					if ($chkpass > 0) {
						$status_duplicate = 1;
					}
				}
				if ($status_duplicate == 0) {
					$this->lg->record('dashboard', 'user name ' . $useri . ' Change Password.');
					$date = date('Y-m-d H:i');
					$date = new DateTime($date);
					$date->modify('+90 day');
					$dateexpire = date_format($date, 'Y-m-d H:i');
					$data = array(
						'userp' => $password_enc,
						'expiredate' => $dateexpire
					);
					$this->db->where('u_id', $u_id);
					$this->db->update('lms_usp', $data);

					$arr_logpass = array(
						'u_id' =>  $u_id,
						'lp_datetime' => date('Y-m-d H:i'),
						'lp_password' => $password_enc
					);
					$this->db->insert('lms_log_password', $arr_logpass);
					$arr_result['rs'] = true;
					$arr_result['msg'] = "050"; //เปลี่ยนรหัสผ่านเรียบร้อย
					echo json_encode($arr_result);
				} else {
					$arr_result['rs'] = false;
					$arr_result['msg'] = "055"; //รหัสผ่านซ้ำกับของเก่า 
					echo json_encode($arr_result);
				}
				$this->lg->closeDB();
			} else {
				$arr_result['rs'] = false;
				$arr_result['msg'] = "056"; //ไม่พบข้อมูล 
				echo json_encode($arr_result);
			}
		} else {
			$arr_result['rs'] = false;
			$arr_result['msg'] = "056"; //ไม่พบรหัสผ่าน 
			echo json_encode($arr_result);
		}
	}


	public function fetch_grade()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang, $lang);
		$this->load->model('Dashboard_model', 'dashboard', true);
		$this->dashboard->loadDB();
		$grade = isset($_REQUEST['grade']) ? $_REQUEST['grade'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->dashboard->fetch_grade($user, $grade) : array();
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

	public function unlockAcc()
	{
		$arr['page'] = 'dashboard/unlockAcc';
		$this->load->model('User_model', 'login', true);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
		}
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		!$this->login->checkSession($arr['page']) ?: $arr['page'];
		$user = $this->session->userdata("user");
		$arr['user'] = $user;
		$arr['emp_c'] = $user['emp_c'];
		$arr['com_admin'] = $user['com_admin'];
		$arr['com_id'] = $user['com_id'];

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
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
		$arr['accounts'] = $this->login->getLockedAcc();
		$arr['companyCode'] = $this->manage->getArrCompany();
		$arr['company_select'] = $this->manage->getCompany();
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->manage->closeDB();

		$this->load->view('frontend/unlockAcc', $arr);
	}

	public function fetch_detail_unlockacc()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('User_model', 'user', false);
		$this->user->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->user->fetch_data_unlockacc() : array();
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

	public function unlockUser()
	{
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$user = $this->session->userdata('user');
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : "";
		$useri = isset($_REQUEST['useri']) ? $_REQUEST['useri'] : "";
		/*$emp_id = $this->input->post('emp_id');
		$useri = $this->input->post('useri');*/
		$this->load->model('User_model', 'user', false);
		$this->user->loadDB();
		$this->user->unlock($emp_id);
		$this->unlockpass($useri);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->loadDB();
		$this->lg->record('user', 'Unlock user : ' . $useri . ' By ' . $user['fullname_th']);
		$this->user->closeDB();
		echo "1";
		//redirect(base_url().'dashboard/unlockAcc');
	}


	public function confim_account($emp_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('User_model', 'login', true);
		$this->login->loadDB();
		$data = array('confirm_status' => '1');
		$this->db->where('emp_id', $emp_id);
		$this->db->update('lms_usp', $data);

		$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard';
		$this->login->sendLogin($redirect);
	}

	public function chk_firsttime_user()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('User_model', 'login', true);
		$this->load->model('Function_query_model', 'func_query', true);
		$this->login->loadDB();
		$password = hash('sha256', $_REQUEST['password']);
		$arr_output = array();
		$dest = isset($_REQUEST['dest']) && $_REQUEST['dest'] != "" ? $_REQUEST['dest'] : "";
		if ($this->login->checkfirsttime($_REQUEST['username'], $_REQUEST['password'])) {
			setcookie("emp_id", "", time() - 3600);
			$this->session->set_userdata('lang', $lang);
			if ($this->login->checkconfirm_status($_REQUEST['username'], $_REQUEST['password'])) {
				$this->session->set_userdata('username_firsttime', $_REQUEST['username']);
				$this->session->set_userdata('firsttime', true);
				$arr_output['status'] = "1";
				$arr_output['redirect_val'] = base_url() . "dashboard/firsttime";
			} else {
				$this->session->set_userdata('firsttime', true);
				$this->session->set_userdata('username_firsttime', $_REQUEST['username']);
				$arr_output['status'] = "3";
				$arr_output['redirect_val'] = base_url() . "dashboard/firsttime";
			}
		} else {
			$username = $_REQUEST['username'];
			$fetch_chkuser = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.useri="' . $username . '" and lms_usp.u_isDelete="0"');

			date_default_timezone_set("Asia/Bangkok");
			$date_now = date('Y-m-d H:i');
			$this->session->set_userdata('passexpire', false);
			$this->login->checkLogin($_REQUEST['username'], $password);
			$arr_output['status'] = "0";
			$arr_output['redirect_val'] = $dest != "" ? base_url() . $dest : base_url() . "dashboard";
			if ($fetch_chkuser['emp_firsttime'] == "1") {
				$this->session->set_userdata('redirect_val', $dest);
				$arr_output['redirect_val'] = base_url() . "dashboard";
			}
			/*$dateExpire    = date('Y-m-d H:i',strtotime($fetch_chkuser['expiredate']));
	        if ($date_now > $dateExpire) {
	          	$this->session->set_userdata('username_firsttime', $username );
	          	$this->session->set_userdata('passexpire', true );
	          	//redirect(base_url().'dashboard/passexpire');
	          	$arr_output['status']="4";
				$arr_output['redirect_val']=base_url()."dashboard/passexpire";
	        }else{*/
			//}

		}
		echo json_encode($arr_output);
	}

	public function unlockpass($useri)
	{
		$arr_result = array();
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;

		$emp_c = $useri;
		if ($emp_c == "") {
			redirect(base_url() . 'home');
		} else {
			$this->load->model('User_model', 'login', true);
			$this->load->model('Function_query_model', 'func_query', true);
			$this->login->loadDB();
			$emp = $this->login->getUser($emp_c);
			if ($emp != null) {
				if ($emp['email'] != "") {
					$password = $this->generateRandomString();
					$password_enc = hash('sha256', $password);
					if ($this->login->updatePass($emp_c, $password_enc, $password)) {
						$this->login->setFirstTime($emp_c);
						$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');

						$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="3"');
						if (countArray($fetch_formatmail) > 0) {
							$subject_th = $fetch_formatmail['smf_subject_th'];
							$subject_en = $fetch_formatmail['smf_subject_en'];
							$message_th = $fetch_formatmail['smf_message_th'];
							$message_en = $fetch_formatmail['smf_message_en'];
							$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
							if ($lang != "thai") {
								$date = date('d F Y');
							}
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $emp['com_id'] . '"');
							if ($subject_th != "") {
								$subject_th = str_replace("#fullname", $emp['fullname_th'], $subject_th);
								$subject_th = str_replace("#username", $emp['useri'], $subject_th);
								$subject_th = str_replace("#email", $emp['email'], $subject_th);
								$subject_th = str_replace("#coursename", "", $subject_th);
								$subject_th = str_replace("#link_frontend", base_url(), $subject_th);
								$subject_th = str_replace("#password", $password, $subject_th);
								$subject_th = str_replace("#date", $date, $subject_th);
								$subject_th = str_replace("#time", date('H:i'), $subject_th);
								$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
							}
							if ($subject_en != "") {
								$subject_en = str_replace("#fullname", $emp['fullname_en'], $subject_en);
								$subject_en = str_replace("#username", $emp['useri'], $subject_en);
								$subject_en = str_replace("#email", $emp['email'], $subject_en);
								$subject_en = str_replace("#coursename", "", $subject_en);
								$subject_en = str_replace("#link_frontend", base_url(), $subject_en);
								$subject_en = str_replace("#password", $password, $subject_en);
								$subject_en = str_replace("#date", $date, $subject_en);
								$subject_en = str_replace("#time", date('H:i'), $subject_en);
								$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
							}
							if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
								$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '"style="max-width:800px">';
							} else {
								$img_val = '';
							}
							if ($message_th != "") {
								$message_th = str_replace("#fullname", $emp['fullname_th'], $message_th);
								$message_th = str_replace("#username", $emp['useri'], $message_th);
								$message_th = str_replace("#email", $emp['email'], $message_th);
								$message_th = str_replace("#coursename", "", $message_th);
								$message_th = str_replace("#link_frontend", base_url(), $message_th);
								$message_th = str_replace("#password", $password, $message_th);
								$message_th = str_replace("#date", $date, $message_th);
								$message_th = str_replace("#time", date('H:i'), $message_th);
								$message_th = str_replace("#image", $img_val, $message_th);
								$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
							}
							if ($message_en != "") {
								$message_en = str_replace("#fullname", $emp['fullname_en'], $message_en);
								$message_en = str_replace("#username", $emp['useri'], $message_en);
								$message_en = str_replace("#email", $emp['email'], $message_en);
								$message_en = str_replace("#coursename", "", $message_en);
								$message_en = str_replace("#link_frontend", base_url(), $message_en);
								$message_en = str_replace("#password", $password, $message_en);
								$message_en = str_replace("#date", $date, $message_en);
								$message_en = str_replace("#time", date('H:i'), $message_en);
								$message_en = str_replace("#image", $img_val, $message_en);
								$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
							}
							$lang = "english";
							if ($lang == "thai") {
								$this->db->sendEmail($emp['email'], $message_th, $subject_th, $fetch_setmail);
							} else {
								$this->db->sendEmail($emp['email'], $message_en, $subject_en, $fetch_setmail);
							}
						}
						$arr_result['rs'] = true;
						echo json_encode($arr_result);
					} else {
						$arr_result['rs'] = false;
						$arr_result['msg'] = label('cannot_sent_password');
						echo json_encode($arr_result);
					}
				} else {
					$arr_result['rs'] = false;
					$arr_result['msg'] = label('notfound_email');
					echo json_encode($arr_result);
				}
			} else {
				$arr_result['rs'] = false;
				$arr_result['msg'] = label('notfound_user');
				echo json_encode($arr_result);
			}
		}
	}

	public function login()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$arr['lang'] = $lang;
		$arr['page'] = "login";
		$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home';
		$arr['dest'] = $redirect;
		$this->load->model('User_model', 'login', true);
		//$this->login->sendLogin($redirect);

		redirect(base_url() . 'home', 'location', 302);
	}
	public function firsttime()
	{
		$arr = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "firsttime";
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();

		if ($this->session->userdata("firsttime")) {
			$this->load->view('frontend/updatepass', $arr);
		} else {
			redirect(base_url() . 'home');
		}
	}
	public function passexpire()
	{
		$arr = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "passexpire";
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		if ($this->session->userdata("passexpire")) {
			$this->load->view('frontend/updatepass', $arr);
		} else {
			redirect(base_url() . 'home');
		}
	}
	public function passexpireonmail($u_id)
	{
		$arr = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = "dashboard/passexpireonmail";
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->foot->loadDB();
		$fetch_chk_user = $this->func_query->query_row('lms_usp', '', '', '', 'u_id="' . $u_id . '"');
		$arr['userdata'] = $fetch_chk_user;
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		if (countArray($arr['userdata']) > 0) {
			$this->load->view('frontend/updatepass_expire', $arr);
		} else {
			redirect(base_url() . 'home');
		}
	}
	public function testDate()
	{
		date_default_timezone_set("Asia/Bangkok");
		$date = date('Y-m-d H:i');
		$date = new DateTime($date);
		$date->modify('+60 day');
		echo date_format($date, 'Y-m-d H:i');
	}
	public function updatePass($resetPass = 0)
	{
		$this->output->set_content_type('application/json');
		$issuedAt = (int) $this->session->userdata('password_change_issued_at');
		if ($issuedAt <= 0 || (time() - $issuedAt) > 900) {
			echo json_encode(array('rs' => false, 'msg' => 'คำขอหมดอายุ กรุณาเข้าสู่ระบบใหม่'));
			return;
		}
		$this->load->model('Function_query_model', 'func_query', true);
		$this->func_query->loadDB();
		$arr_result = array();
		if (null !== $this->session->userdata('username_firsttime')) {
			if ($this->session->userdata('username_firsttime') != '') {
				// code here
				$password = isset($_POST['newpass']) ? $_POST['newpass'] : '';
				if ($password != '') {
					$user = $this->session->userdata('username_firsttime');
					if (!$this->isStrongPassword($password)) {
						echo json_encode(array('rs' => false, 'msg' => 'รหัสผ่านต้องมีอย่างน้อย 10 ตัว และมีตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก ตัวเลข และอักขระพิเศษ'));
						return;
					}
					$password_enc = password_hash($password, PASSWORD_DEFAULT);
					$status_duplicate = 0;
					$fetch_logpass = $this->func_query->query_result('lms_log_password', 'lms_usp', 'lms_usp.u_id = lms_log_password.u_id', '', 'lms_usp.useri="' . $user . '" and lms_usp.u_isDelete="0"', 'lms_log_password.lp_id DESC', '', 3);
					if (countArray($fetch_logpass) > 0) {
						$chkpass = 0;
						foreach ($fetch_logpass as $key_logpass => $value_logpass) {
							if (password_verify($password, $value_logpass['lp_password']) || hash_equals((string) $value_logpass['lp_password'], hash('sha256', $password))) {
								$chkpass++;
							}
						}
						if ($chkpass > 0) {
							$status_duplicate = 1;
						}
					}
					if ($status_duplicate == 0) {
						$this->load->model('User_model', 'login', true);
						$this->login->loadDB();
						if ($this->login->updatePass($this->session->userdata('username_firsttime'), $password_enc, $password, $resetPass)) {
							$this->session->set_userdata('login', array($user => 0));
							$arr_result['rs'] = true;
							$arr_result['msg'] = "050"; //เปลี่ยนรหัสผ่านเรียบร้อย
							$this->load->helper('cookie');
							setcookie("emp_id", "", time() - 3600, '/');
							$this->session->sess_destroy();
							echo json_encode($arr_result);
						} else {
							$arr_result['rs'] = false;
							$arr_result['msg'] = "054"; //รหัสผ่านซ้ำกับของก่อนหน้านี้
							echo json_encode($arr_result);
						}
					} else {
						$arr_result['rs'] = false;
						$arr_result['msg'] = "055"; //รหัสผ่านซ้ำกับของเก่า 
						echo json_encode($arr_result);
					}
				} else {
					$arr_result['rs'] = false;
					$arr_result['msg'] = "กรุณากรอกรหัสผ่านใหม่ของคุณ";
					echo json_encode($arr_result);
				}
			} else {
				redirect(base_url() . 'home');
			}
		} else {
			redirect(base_url() . 'home');
		}
	}

	private function isStrongPassword($password)
	{
		$password = (string) $password;
		return strlen($password) >= 10
			&& preg_match('/[A-Z]/', $password)
			&& preg_match('/[a-z]/', $password)
			&& preg_match('/[0-9]/', $password)
			&& preg_match('/[^A-Za-z0-9]/', $password);
	}
	public function forgotpass()
	{
		$arr_result = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;

		$emp_c = isset($_POST['emp_c']) ? $_POST['emp_c'] : '';
		if ($emp_c == "") {
			redirect(base_url() . 'home');
		} else {
			$this->load->model('User_model', 'login', true);
			$this->login->loadDB();
			$password = $this->generateRandomString();
			$password_enc = hash('sha256', $password);
			$emp = $this->login->getEmp($emp_c);
			if (sizeof($emp) > 0) {
				$password_enc = hash('sha256', $password_enc);
				if ($this->login->updatePass($emp_c, $password_enc, $password, '1')) {
					$this->login->setFirstTime($emp_c);
					$arr_result['rs'] = true;
					echo json_encode($arr_result);
				} else {
					$arr_result['rs'] = false;
					$arr_result['msg'] = "ไม่สามารถส่งรหัสผ่านใหม่ได้";
					echo json_encode($arr_result);
				}
			} else {
				$arr_result['rs'] = false;
				$arr_result['msg'] = "ไม่พบรหัสผู้ใช้งานของคุณ";
				echo json_encode($arr_result);
			}
		}
	}
	public function resetPass()
	{

		$arr_result = array();
		$arr = array();
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = 'dashboard/resetPass';
		$this->load->model('User_model', 'login', false);
		if ($this->login->checkSession($arr['page'])) {
			$sess = $this->session->userdata("user");
			$arr['user'] = $sess;
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
		}
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['btn_view'] = $this->manage->chk_permission($arr['page'], 'ru_view');
		if ($arr['btn_view'] != "1") {
			redirect(base_url() . 'dashboard');
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
		$this->load->model('Footer_model', 'foot', false);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/resetpass', $arr);
	}

	public function resetPassSubmit()
	{
		$this->output->set_content_type('application/json');
		$genericMessage = 'หากข้อมูลตรงกับบัญชีในระบบ เราได้ส่งลิงก์ตั้งรหัสผ่านใหม่ไปยังอีเมลแล้ว ลิงก์มีอายุ 30 นาที';
		$username = strtolower(trim((string) $this->input->post('useri', true)));
		$this->load->model('User_model', 'login', false);
		$this->login->loadDB();
		$reset = $username !== '' && $this->login->passwordResetRequestAllowed()
			? $this->login->createPasswordResetToken($username)
			: null;
		if ($reset) {
			$this->load->model('Function_query_model', 'func_query', true);
			$settings = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
			$link = base_url() . 'dashboard/reset_password/' . rawurlencode($reset['token']);
			$name = !empty($reset['user']['fullname_th']) ? $reset['user']['fullname_th'] : $reset['user']['fullname_en'];
			$subject = 'ตั้งรหัสผ่านใหม่สำหรับ LMS';
			$message = '<p>เรียน ' . html_escape($name) . '</p>'
				. '<p>มีคำขอตั้งรหัสผ่านใหม่สำหรับบัญชี LMS ของคุณ</p>'
				. '<p><a href="' . html_escape($link) . '">ตั้งรหัสผ่านใหม่</a></p>'
				. '<p>ลิงก์นี้ใช้ได้ครั้งเดียวและหมดอายุภายใน 30 นาที หากคุณไม่ได้เป็นผู้ขอ สามารถละเว้นอีเมลฉบับนี้ได้</p>';
			try {
				$this->db->sendEmail($reset['user']['email'], $message, $subject, $settings);
			} catch (Throwable $e) {
				log_message('error', 'Password reset email failed: ' . $e->getMessage());
			}
		}
		echo json_encode(array('rs' => true, 'msg' => $genericMessage));
		return;

		$arr_result = array();
		$arr = array();
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = 'resetpass';
		$this->load->model('User_model', 'login', false);
		$this->login->loadDB();
		$user = $this->session->userdata('user');
		$useri = isset($_POST['useri']) ? $_POST['useri']  : '';

		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', true);
		$this->lg->loadDB();
		$this->lg->record('user', 'Reset user : ' . $useri . (isset($user['fullname_th']) ? ' By '.$user['fullname_th'] : ""));
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$this->session->set_userdata('login', null);
		if ($useri == "") {
			$arr_result['rs'] = false;
			$arr_result['msg'] = label('com_msg_form_error');
			echo json_encode($arr_result);
		} else {
			$empdata = $this->login->getUser($useri);
			$chkuser = 1;
			/*if($user['ug_id']!="1"){
				if($empdata['ug_id']=="1"){
					$chkuser = 0;
				}
			}*/
			if (isset($empdata) && $chkuser == 1) {
				// if($empdata['login']=="0"){
				// 	$arr_result['rs'] = false;
				// 	$arr_result['msg'] = label('userlock');
				// }else{
				$password = $this->generateRandomString();
				$password_enc = hash('sha256', $password);
				$this->login->updatePass($useri, $password_enc, '', 1);
				$this->login->setFirstTime($useri);

				$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
				if ($lang != "thai") {
					$date = date('d F Y');
				}
				$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="2"');
				if (isset($fetch_formatmail)) {
					$subject_th = $fetch_formatmail['smf_subject_th'];
					$subject_en = $fetch_formatmail['smf_subject_en'];
					$message_th = $fetch_formatmail['smf_message_th'];
					$message_en = $fetch_formatmail['smf_message_en'];
					$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $empdata['com_id'] . '"');
					if ($subject_th != "") {
						$subject_th = str_replace("#fullname", $empdata['fullname_th'], $subject_th);
						$subject_th = str_replace("#username", $empdata['useri'], $subject_th);
						$subject_th = str_replace("#email", $empdata['email'], $subject_th);
						$subject_th = str_replace("#password", $password, $subject_th);
						$subject_th = str_replace("#coursename", "", $subject_th);
						$subject_th = str_replace("#date", $date, $subject_th);
						$subject_th = str_replace("#time", date('H:i'), $subject_th);
						$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
					}
					if ($subject_en != "") {
						$subject_en = str_replace("#fullname", $empdata['fullname_en'], $subject_en);
						$subject_en = str_replace("#username", $empdata['useri'], $subject_en);
						$subject_en = str_replace("#email", $empdata['email'], $subject_en);
						$subject_en = str_replace("#password", $password, $subject_en);
						$subject_en = str_replace("#coursename", "", $subject_en);
						$subject_en = str_replace("#date", $date, $subject_en);
						$subject_en = str_replace("#time", date('H:i'), $subject_en);
						$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
					}
					if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
						$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
					} else {
						$img_val = '';
					}
					if ($message_th != "") {
						$message_th = str_replace("#fullname", $empdata['fullname_th'], $message_th);
						$message_th = str_replace("#username", $empdata['useri'], $message_th);
						$message_th = str_replace("#email", $empdata['email'], $message_th);
						$message_th = str_replace("#password", $password, $message_th);
						$message_th = str_replace("#coursename", "", $message_th);
						$message_th = str_replace("#date", $date, $message_th);
						$message_th = str_replace("#time", date('H:i'), $message_th);
						$message_th = str_replace("#image", $img_val, $message_th);
						$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
					}
					if ($message_en != "") {
						$message_en = str_replace("#fullname", $empdata['fullname_en'], $message_en);
						$message_en = str_replace("#username", $empdata['useri'], $message_en);
						$message_en = str_replace("#email", $empdata['email'], $message_en);
						$message_en = str_replace("#password", $password, $message_en);
						$message_en = str_replace("#coursename", "", $message_en);
						$message_en = str_replace("#date", $date, $message_en);
						$message_en = str_replace("#time", date('H:i'), $message_en);
						$message_en = str_replace("#image", $img_val, $message_en);
						$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
					}
					$lang = "english";
					if ($lang == "thai") {
						$this->db->sendEmail($empdata['email'], $message_th, $subject_th, $fetch_setmail);
					} else {
						$this->db->sendEmail($empdata['email'], $message_en, $subject_en, $fetch_setmail);
					}
				}
				$arr_result['msg'] = label('sentmail_success');
				$arr_result['rs'] = true;
				$arr_result['emp_id'] = $empdata['emp_id'];
				// }

			} else {
				$arr_result['rs'] = false;
				$arr_result['msg'] = label('datauser_notfound');
			}
			echo json_encode($arr_result);
			$this->login->closeDB();
		}
	}

	public function reset_password($token = '')
	{
		$this->load->model('User_model', 'login', false);
		$this->login->loadDB();
		$record = $this->login->findPasswordResetToken($token);
		$data = array('token' => $token, 'valid_token' => !empty($record));
		$this->load->view('frontend/reset_password_token', $data);
	}

	public function complete_password_reset()
	{
		$this->output->set_content_type('application/json');
		$token = (string) $this->input->post('token', true);
		$password = (string) $this->input->post('password', false);
		$confirm = (string) $this->input->post('password_confirm', false);
		if ($password !== $confirm || !$this->isStrongPassword($password)) {
			echo json_encode(array('rs' => false, 'msg' => 'รหัสผ่านไม่ตรงกัน หรือไม่ผ่านเงื่อนไขความปลอดภัย'));
			return;
		}
		$this->load->model('User_model', 'login', false);
		$this->login->loadDB();
		if (!$this->login->consumePasswordResetToken($token, $password)) {
			echo json_encode(array('rs' => false, 'msg' => 'ลิงก์ไม่ถูกต้อง หมดอายุ หรือถูกใช้แล้ว'));
			return;
		}
		$this->session->sess_destroy();
		echo json_encode(array('rs' => true, 'redirect_val' => base_url() . 'home'));
	}

	public function resetPassSubmit_page()
	{
		$arr_result = array();
		$arr = array();
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$arr['lang'] = $lang;
		$arr['page'] = 'resetpass';
		$this->load->model('User_model', 'login', false);
		$this->login->loadDB();
		$user = $this->session->userdata('user');
		$useri = isset($_POST['useri']) ? $_POST['useri']  : '';

		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Function_query_model', 'func_query', true);
		$this->lg->loadDB();
		$this->lg->record('user', 'Reset user : ' . $useri . ' By ' . $user['fullname_th']);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if ($useri == "") {
			$arr_result['rs'] = false;
			$arr_result['msg'] = label('com_msg_form_error');
			echo json_encode($arr_result);
		} else {
			$empdata = $this->login->getUser($useri);
			$chkuser = 1;
			if ($user['ug_id'] != "1") {
				if ($empdata['ug_id'] == "1") {
					$chkuser = 0;
				}
			}
			if ($chkuser == 1) {
				if (sizeof($empdata) > 0) {
					$password = $this->generateRandomString();
					$password_enc = hash('sha256', $password);
					$this->login->updatePass($useri, $password_enc, '', 1);
					$this->login->setFirstTime($useri);

					$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
					if ($lang != "thai") {
						$date = date('d F Y');
					}
					$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
					$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="2"');
					if (isset($fetch_formatmail)) {
						$subject_th = $fetch_formatmail['smf_subject_th'];
						$subject_en = $fetch_formatmail['smf_subject_en'];
						$message_th = $fetch_formatmail['smf_message_th'];
						$message_en = $fetch_formatmail['smf_message_en'];
						$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $empdata['com_id'] . '"');
						if ($subject_th != "") {
							$subject_th = str_replace("#fullname", $empdata['fullname_th'], $subject_th);
							$subject_th = str_replace("#username", $empdata['useri'], $subject_th);
							$subject_th = str_replace("#email", $empdata['email'], $subject_th);
							$subject_th = str_replace("#password", $password, $subject_th);
							$subject_th = str_replace("#coursename", "", $subject_th);
							$subject_th = str_replace("#date", $date, $subject_th);
							$subject_th = str_replace("#time", date('H:i'), $subject_th);
							$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
						}
						if ($subject_en != "") {
							$subject_en = str_replace("#fullname", $empdata['fullname_en'], $subject_en);
							$subject_en = str_replace("#username", $empdata['useri'], $subject_en);
							$subject_en = str_replace("#email", $empdata['email'], $subject_en);
							$subject_en = str_replace("#password", $password, $subject_en);
							$subject_en = str_replace("#coursename", "", $subject_en);
							$subject_en = str_replace("#date", $date, $subject_en);
							$subject_en = str_replace("#time", date('H:i'), $subject_en);
							$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
						}
						if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
							$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '"  style="max-width:800px">';
						} else {
							$img_val = '';
						}
						if ($message_th != "") {
							$message_th = str_replace("#fullname", $empdata['fullname_th'], $message_th);
							$message_th = str_replace("#username", $empdata['useri'], $message_th);
							$message_th = str_replace("#email", $empdata['email'], $message_th);
							$message_th = str_replace("#password", $password, $message_th);
							$message_th = str_replace("#coursename", "", $message_th);
							$message_th = str_replace("#date", $date, $message_th);
							$message_th = str_replace("#time", date('H:i'), $message_th);
							$message_th = str_replace("#image", $img_val, $message_th);
							$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
						}
						if ($message_en != "") {
							$message_en = str_replace("#fullname", $empdata['fullname_en'], $message_en);
							$message_en = str_replace("#username", $empdata['useri'], $message_en);
							$message_en = str_replace("#email", $empdata['email'], $message_en);
							$message_en = str_replace("#password", $password, $message_en);
							$message_en = str_replace("#coursename", "", $message_en);
							$message_en = str_replace("#date", $date, $message_en);
							$message_en = str_replace("#time", date('H:i'), $message_en);
							$message_en = str_replace("#image", $img_val, $message_en);
							$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
						}
						$lang = "english";
						if ($lang == "thai") {
							$this->db->sendEmail($empdata['email'], $message_th, $subject_th, $fetch_setmail);
						} else {
							$this->db->sendEmail($empdata['email'], $message_en, $subject_en, $fetch_setmail);
						}
					}
					$arr_result['msg'] = label('sentmail_success');
					$arr_result['rs'] = true;
					$arr_result['emp_id'] = $empdata['emp_id'];
				} else {
					$arr_result['rs'] = false;
					$arr_result['msg'] = label('datauser_notfound');
				}
			} else {
				$arr_result['rs'] = false;
				$arr_result['msg'] = label('permisson_password');
			}
			echo json_encode($arr_result);
			$this->login->closeDB();
		}
	}

	private function sendEmail_main($email, $message, $subject)
	{
		require_once 'class/class.simple_mail.php';
		/*$mail_to = $aemail;*/

		$mail = new SimpleMail();
		//$mail->SMTPAuth = false;
		// SMTP server
		//$mail->Host = "172.20.102.105";

		// set the SMTP port for the outMail server
		// use either 25, 587, 2525 or 8025
		//$mail->Port = 25;

		$mail->setTo($email, '')
			->setFrom(SERVER_EMAIL, 'no-reply@verztec.com')
			->setSubject($subject)
			->addGenericHeader('MIME-Version', '1.0')
			->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
			->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
			->setMessage($message);
		$mail->send();
	}

	public function generateRandomString($length = 8)
	{
		return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
	}
	public function updateCondition()
	{
		$arr_result = array();
		$this->session->set_userdata('acceptCondition', 1);
		$arr_result['rs'] = true;
		echo json_encode($arr_result);
	}
	public function chk_login()
	{
		$this->output->set_content_type('application/json');
		$username = strtolower(trim((string) $this->input->post('inpUname', true)));
		$password = (string) $this->input->post('inpPwd', false);
		$dest = trim((string) $this->input->post('dest', true), '/');
		if ($dest === '' || preg_match('#^(?:https?:)?//#i', $dest) || strpos($dest, '..') !== false) {
			$dest = 'dashboard';
		}

		$this->load->model('User_model', 'login', true);
		$this->login->loadDB();
		$result = $this->login->authenticate($username, $password);
		$status = $result['status'];
		$response = array('status_msg' => $status);
		if ($status === 'complete') {
			$response['redirect_val'] = base_url() . $dest;
		} elseif ($status === 'first_login') {
			$response['redirect_val'] = base_url() . 'dashboard/firsttime';
		} elseif ($status === 'password_expired') {
			$response['redirect_val'] = base_url() . 'dashboard/passexpire';
		} elseif ($status === 'rate_limited') {
			$response['retry_after'] = isset($result['retry_after']) ? $result['retry_after'] : 900;
		}
		log_message($status === 'complete' ? 'info' : 'error', 'Authentication result: ' . $status . ' for ' . $username);
		echo json_encode($response);
		return;

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$username = $_REQUEST['inpUname'];
		$password = $_REQUEST['inpPwd'];
		$password_enc = hash('sha256', $password);
		$count = 1;
		$this->load->model('User_model', 'login', true);
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Function_query_model', 'func_query', true);
		$this->login->loadDB();
		$arr_output = array();
		$fetch_chk = $this->func_query->query_row('lms_usp', '', '', '', 'useri="' . $username . '" and u_isDelete="0"');
		$chk_date = 1;
		if (isset($fetch_chk)) {
			if (isset($fetch_chk['inactivedate']) && $fetch_chk['inactivedate'] != "0000-00-00" && $fetch_chk['inactivedate'] != "") {
				if (date('Y-m-d') > date('Y-m-d', strtotime($fetch_chk['inactivedate']))) {
					$chk_date = 0;
				}
			}
			if ($password_enc != $fetch_chk['userp']) {
				$chk_date = 4;
			}
		} else {
			$chk_date = 3;
		}
		if ($chk_date == 1) {
			if ($this->login->rechk_login($username, $password_enc)) {
				$arr_output['status_msg'] = "complete";

				$this->load->model('Log_model', 'lg', false);
				$this->lg->loadDB();
				$this->lg->record('login', 'Username: ' . $username . ' log in');
				//$this->lg->closeDB();
			} else {
				$count_error = 0;
				if ($this->session->userdata("login") == null) {
					$this->session->set_userdata('login', array($username => 1));
				} else {
					$counter = $this->session->userdata("login");
					if (isset($counter[$username]))
						$counter[$username] = intval($counter[$username]) + 1;
					else {
						$counter[$username] = 1;
					}
					$this->session->set_userdata('login', $counter);
					if ($counter[$username] > 4) {
						$this->login->lockUser($username);
						$this->session->sess_destroy();
						$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
						$this->output->set_header("Pragma: no-cache");
					} else {
						if (intval($counter[$username]) > 3) {
							$count_error = 3;
						}
					}
				}
				if ($this->login->isLocked($username)) {
					$this->login->closeDB();
					$arr_output['status_msg'] = "account_locked";
					$fetch_usp = $this->func_query->query_row('lms_usp', '', '', '', 'useri="' . $username . '" and lms_usp.u_isDelete="0"');
					$arr_output['emp_id'] = $fetch_usp['emp_id'];
					//Record Log activity
					$this->load->model('Log_model', 'lg', false);
					$this->lg->loadDB();
					$this->lg->record('login', 'Username: ' . $username . ' is locked.');
					$this->lg->closeDB();
				} else {
					if ($count_error == 3) {
						$arr_output['status_msg'] = "login_failed_4_time";
					} else {
						$arr_output['status_msg'] = "login_failed";
					}
					$this->login->closeDB();
					//Record Log activity
					$this->load->model('Log_model', 'lg', false);
					$this->lg->loadDB();
					$this->lg->record('login', 'Username: ' . $username . ' logged in fail.');
					$this->lg->closeDB();
				}
			}
		} else {
			if ($chk_date == 3) {
				$arr_output['status_msg'] = "notfound";
			} else if ($chk_date == 4) {
				$arr_output['status_msg'] = "passnotfound";
			} else if ($chk_date == 0) {
				$arr_output['status_msg'] = "inactive";
			} else {
				$arr_output['status_msg'] = "login_failed";
			}
			if ($chk_date != 3 && $chk_date != 0) {
				$count_error = 0;
				if ($this->session->userdata("login") == null) {
					$this->session->set_userdata('login', array($username => 1));
				} else {
					$counter = $this->session->userdata("login");
					if (isset($counter[$username]))
						$counter[$username] = intval($counter[$username]) + 1;
					else {
						$counter[$username] = 1;
					}
					$this->session->set_userdata('login', $counter);
					if ($counter[$username] > 4) {
						$this->login->lockUser($username);
						$this->session->sess_destroy();
						$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
						$this->output->set_header("Pragma: no-cache");
					} else {
						if (intval($counter[$username]) > 3) {
							$count_error = 3;
						}
					}
				}
				if ($this->login->isLocked($username)) {
					$this->login->closeDB();
					$arr_output['status_msg'] = "account_locked";
					$fetch_usp = $this->func_query->query_row('lms_usp', '', '', '', 'useri="' . $username . '"');
					$arr_output['emp_id'] = $fetch_usp['emp_id'];
					$this->session->set_userdata('login', array($username => 0));
					//Record Log activity
					$this->load->model('Log_model', 'lg', false);
					$this->lg->loadDB();
					$this->lg->record('login', 'Username: ' . $username . ' is locked.');
					$this->lg->closeDB();
				} else {
					if ($count_error == 3) {
						$arr_output['status_msg'] = "login_failed_4_time";
					}/*else{
							$arr_output['status_msg'] = "login_failed";
						}*/
					$this->login->closeDB();
					//Record Log activity
					$this->load->model('Log_model', 'lg', false);
					$this->lg->loadDB();
					$this->lg->record('login', 'Username: ' . $username . ' logged in fail.');
					$this->lg->closeDB();
				}
			}
		}
		echo json_encode($arr_output);
	}
	public function loggedIn()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : "";
		$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
		$redirect = 'dashboard';

		//$this->load->model('AES_model', 'aes', true);
		//$password_enc = $this->aes->encrypt($password);
		$password_enc = hash('sha256', $password);
		$count = 1;
		$arr_output = array();
		$this->load->model('User_model', 'login', true);
		$this->login->loadDB();
		if ($this->login->checkLogin($username, $password_enc)) {
			//Record Log activity
			$sess = $this->session->userdata("user");
			$emp_c = $sess['emp_c'];
			$this->load->model('Log_model', 'lg', false);
			$this->lg->loadDB();
			$this->lg->record('home', 'user id ' . $emp_c . ' logged in website.');
			$this->lg->closeDB();
			$this->login->closeDB();
			$arr_output['status'] = "1";
			$arr_output['text_msg'] = "";
			$arr_output['redirect'] = base_url() . $redirect;
			//redirect(base_url().$redirect);
		} else {
			$count_error = 0;
			if ($this->session->userdata("login") == null) {
				$this->session->set_userdata('login', array($username => 1));
			} else {
				$counter = $this->session->userdata("login");
				if (isset($counter[$username]))
					$counter[$username] = intval($counter[$username]) + 1;
				else {
					$counter[$username] = 1;
				}
				$this->session->set_userdata('login', $counter);
				if ($counter[$username] > 4) {
					$this->login->lockUser($username);
					$this->session->sess_destroy();
					$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
					$this->output->set_header("Pragma: no-cache");
				} else {
					if (intval($counter[$username]) > 3) {
						$count_error = 3;
						//$path = base_url()."home";
						//$text = label('login_failed_4_time');
					}
				}
			}
			if ($this->login->isLocked($username)) {
				$this->login->closeDB();
				$path = base_url() . "contact";
				$text = label('account_locked');
				//Record Log activity
				$this->load->model('Log_model', 'lg', false);
				$this->lg->loadDB();
				$this->lg->record('login', 'Username: ' . $username . ' is locked.');
				$this->lg->closeDB();
			} else {
				if ($count_error == 3) {
					$text = label('login_failed_4_time');
				} else {
					$text = label('login_failed');
				}
				$this->login->closeDB();
				$path = base_url() . "home";
				//Record Log activity
				$this->load->model('Log_model', 'lg', false);
				$this->lg->loadDB();
				$this->lg->record('login', 'Username: ' . $username . ' logged in fail.');
				$this->lg->closeDB();
			}
			$arr_output['status'] = "0";
			$arr_output['text_msg'] = $text;
			$arr_output['redirect'] = $path;
			/*echo"<script language='JavaScript'>";
			echo"alert('".$text."');";
			echo"window.location='".$path."';";
			echo"</script>";*/
		}
		echo json_encode($arr_output);
	}

	public function logout()
	{
		$sess = $this->session->userdata("user");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$emp_c = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$this->load->model('User_model', 'login', true);
		$this->login->loadDB();
		if (isset($sess['u_id'])) {
			$arr_update = array(
				'lang_last' => $lang
			);
			$this->db->where('u_id', $sess['u_id']);
			$this->db->update('lms_usp', $arr_update);
		}

		$redirect = isset($_GET['redirect']) ? trim((string) $_GET['redirect'], '/') : 'dashboard';
		if ($redirect === '' || preg_match('#^(?:https?:)?//#i', $redirect) || strpos($redirect, '..') !== false) {
			$redirect = 'dashboard';
		}
		$this->login->logout($emp_c);
		$this->load->helper('cookie');

		$this->load->model('Log_model', 'lg', false);
		$this->lg->loadDB();
		$this->lg->record('home', 'user id ' . $emp_c . 'logged out.');
		$this->lg->closeDB();

		// CI must destroy its own session file and cookie. Native
		// session_destroy() alone can leave the CI cookie reusable.
		$this->session->unset_userdata(array(
			'user', 'name', 'login', 'firsttime', 'passexpire',
			'username_firsttime', 'password_change_reason',
			'password_change_issued_at', 'p0_last_activity', 'p0_session_started'
		));
		$this->session->sess_destroy();

		$cookieSecure = filter_var(getenv('LMS_COOKIE_SECURE') ?: false, FILTER_VALIDATE_BOOLEAN);
		setcookie('emp_id', '', array(
			'expires' => time() - 3600,
			'path' => '/',
			'secure' => $cookieSecure,
			'httponly' => true,
			'samesite' => 'Lax'
		));
		$sessionCookie = (string) $this->config->item('sess_cookie_name');
		if ($sessionCookie !== '') {
			setcookie($sessionCookie, '', array(
				'expires' => time() - 3600,
				'path' => (string) ($this->config->item('cookie_path') ?: '/'),
				'domain' => (string) $this->config->item('cookie_domain'),
				'secure' => $cookieSecure,
				'httponly' => true,
				'samesite' => 'Lax'
			));
		}

		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, private, max-age=0");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
		redirect(base_url() . 'home?redirect=' . rawurlencode($redirect), 'location', 303);
	}
}
