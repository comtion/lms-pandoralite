<?php
class Report_model extends CI_Model
{
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function loadDB()
	{
		$this->load->database();
	}

	public function closeDB()
	{
		$this->db->close();
	}

	public function fetch_course_company($user, $com_id = "")
	{

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$this->db->from('lms_company');
		if ($user['ug_id'] != "1") {
			$this->db->where('lms_company.com_id', $user['com_id']);
		}/*else{
            if($com_id!=""){
            $this->db->where('lms_company.com_id',$com_id);
            }
          }*/
		if ($com_id != "") {
			$this->db->where('lms_company.com_id', $com_id);
		}
		//$this->db->where('lms_company.com_admin','com_associated');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->where('lms_company.com_status', '1');
		$this->db->where('lms_company.com_id!=', '2');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$output = array();
			$numloop = 1;
			if ($user['ug_id'] == "1") {
				$output['0'] = $lang == "thai" ? $value['com_name_th'] : $value['com_name_eng'];
			} else {
				$numloop = 0;
			}
			$numaccount = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0"');
			$numaccountactive = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
			$numaccount_admin = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where ug_id in (select ug_id from lms_usp_gp where Is_admin="1")  and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
			if ($value['com_admin'] == "com_central") {
				$numaccount_instructor = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where ug_id in (select ug_id from lms_usp_gp where ug_name_en="Instructor") and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
				$numaccount_learner = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where ug_id in (5,8) and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
			} else {
				$numaccount_instructor = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where ug_id in (select ug_id from lms_usp_gp where ug_name_en="Instructor") and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
				$numaccount_learner = $this->func_query->numrows('lms_emp', '', '', '', 'com_id="' . $value['com_id'] . '" and emp_isDelete="0" and emp_id in (select emp_id from lms_usp where ug_id in (4,14) and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00"))');
			}
			//$numaccount = $numaccount_admin+$numaccount_instructor+$numaccount_learner;
			$output[$numloop] = "<span style='float:right'>" . number_format($numaccount) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($numaccountactive) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($numaccount_admin) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($numaccount_instructor) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($numaccount_learner) . "</span>";
			$numloop++;

			$numcourse = $this->func_query->numrows('lms_cos', '', '', '', 'com_id="' . $value['com_id'] . '" and cos_isDelete="0" and cos_approve="1"');
			$numsurvey = $this->func_query->numrows('lms_sv', '', '', '', 'com_id="' . $value['com_id'] . '" and sv_isDelete="0" and sv_approve="1"');
			$output[$numloop] = "<span style='float:right'>" . number_format($numcourse) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($numsurvey) . "</span>";

			/*$this->db->from('lms_usp');
              $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
              $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
              $this->db->where('lms_emp.com_id',$value['com_id']);
              $this->db->where('lms_usp.dummy_status','0');
              $this->db->where('lms_usp_gp.Is_admin','0');
              $query_user = $this->db->get();
              $num_user = $query_user->num_rows();
              $output['3'] = "<span style='float:right'>".number_format($num_user)."</span>";

              $this->db->from('lms_cos');
              $this->db->where('lms_cos.com_id',$value['com_id']);
              $this->db->where('lms_cos.cos_status','1');
              $query_cos = $this->db->get();
              $num_cos = $query_cos->num_rows();
              $output['4'] = "<span style='float:right'>".number_format($num_cos)."</span>";
              if($lang=="thai"){
                $date_create = date('d',strtotime($value['com_createdate']))." ".$thaimonth[intval(date('m',strtotime($value['com_createdate'])))]." ".(date('Y',strtotime($value['com_createdate'])));
              }else{
                $date_create = date('d F Y',strtotime($value['com_createdate']));
              }
              $output['5'] = $date_create;*/
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_course_survey($user, $com_id = "")
	{

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$this->db->from('lms_cos');
		if ($user['com_admin'] == "com_associated" || $com_id != "") {
			$this->db->where('lms_cos.com_id', $com_id);
		}
		if ($user['ug_id'] == "7" || $user['ug_id'] == "9") {
			$this->db->where('lms_cos.cos_createby', $user['u_id']);
		}
		$this->db->join('lms_company', 'lms_cos.com_id = lms_company.com_id');
		$this->db->join('lms_survey', 'lms_cos.cos_id = lms_survey.cos_id');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->where('lms_cos.cos_isDelete', '0');
		$this->db->where('lms_cos.cos_approve', '1');
		$this->db->where('lms_survey.sv_status', '1');
		$this->db->where('lms_survey.sv_isDelete', '0');
		$this->db->order_by('lms_survey.sv_id DESC');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$output = array();

			if ($lang == "thai") {
				$cname = $value['cname_th'] != "" ? $value['cname_th'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
				$sv_title = $value['sv_title_th'] != "" ? $value['sv_title_th'] : $value['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $value['sv_title_jp'];
			} else if ($lang == "english") {
				$cname = $value['cname_eng'] != "" ? $value['cname_eng'] : $value['cname_th'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
				$sv_title = $value['sv_title_eng'] != "" ? $value['sv_title_eng'] : $value['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $value['sv_title_jp'];
			} else {
				$cname = $value['cname_jp'] != "" ? $value['cname_jp'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_th'];
				$sv_title = $value['sv_title_jp'] != "" ? $value['sv_title_jp'] : $value['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $value['sv_title_th'];
			}
			$numloop = 1;
			if ($user['ug_id'] == "1") {
				$output['0'] = '<center>' . $value['com_code'] . '</center>';
			} else {
				$numloop = 0;
			}
			$numCompleteSV = 0;
			$query_head = $this->func_query->query_result('lms_qn_user', '', '', '', 'sv_id="' . $value['sv_id'] . '" and qnu_status="1"');

			foreach ($query_head as $key => $value_head) {
				$query_head2 = $this->func_query->query_result('lms_qn_user_de', '', '', '', 'qnu_id="' . $value_head['qnu_id'] . '"');
				if (countArray($query_head2) > 0) {
					$numCompleteSV++;
				}
			}
			$output[$numloop] = $cname;
			$numloop++;
			$output[$numloop] = $sv_title;
			$numloop++;
			$num_total = $this->func_query->numrows('lms_qn_user', '', '', '', 'sv_id="' . $value['sv_id'] . '"');
			$num_complete = $numCompleteSV;
			$num_incomplete = $num_total - $num_complete;
			$output[$numloop] = "<span style='float:right'>" . number_format($num_total) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($num_complete) . "</span>";
			$numloop++;
			$output[$numloop] = "<span style='float:right'>" . number_format($num_incomplete) . "</span>";
			$numloop++;
			$output[$numloop] = '<center><button type="button" name="view_survey" id="' . $value['sv_id'] . '" data-toggle="modal" data-target="#modal-survey" class="btn btn-info btn-xs view_survey" title="' . label('r_viewDetail') . '"><i class="mdi mdi-format-list-bulleted"></i></button></center>';
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_coursename_company($user, $com_id, $cg_id)
	{

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$this->db->from('lms_cos');
		if ($com_id != "") {
			$this->db->where('lms_cos.com_id', $com_id);
		} else {
			if ($user['com_admin'] == "com_associated") {
				$this->db->where('lms_cos.com_id', $user['com_id']);
			} else {
				if ($user['ug_viewdata'] != "1") {
					$this->db->where('lms_cos.com_id', $user['com_id']);
				}
			}
		}
		if ($user['ug_viewdata'] == "3") {
			$this->db->where('lms_cos.cos_createby', $user['u_id']);
		}
		if ($cg_id != "") {
			$this->db->where('(lms_cos.cos_id in (select course_id from lms_cosincg where cg_id="' . $cg_id . '"))');
		}
		$this->db->join('lms_company', 'lms_cos.com_id = lms_company.com_id');
		$this->db->where('lms_cos.cos_isDelete', '0');
		$this->db->where('lms_cos.cos_approve', '1');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->order_by('cos_id', 'DESC');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$output = array();
			$output['0'] = '<center><button type="button" name="view_detail" id="' . $value['cos_id'] . '" class="btn btn-info btn-xs view_detail" title="' . label('r_viewDetail') . '"><i class="mdi mdi-format-list-bulleted"></i></button></center>';
			$output['1'] = $num;
			$num++;
			if ($lang == "thai") {
				$cname = $value['cname_th'] != "" ? $value['cname_th'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
			} else if ($lang == "english") {
				$cname = $value['cname_eng'] != "" ? $value['cname_eng'] : $value['cname_th'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
			} else {
				$cname = $value['cname_jp'] != "" ? $value['cname_jp'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_th'];
			}
			$output['2'] = $cname;
			$average_score = 0;
			$this->db->where('cos_id', $value['cos_id']);
			$this->db->where('cosen_status_sub', '1');
			$this->db->where('lms_cos_enroll.cosen_isDelete', '0');
			$this->db->from('lms_cos_enroll');
			$query_enroll = $this->db->get();
			$num_enroll = $query_enroll->num_rows();
			if ($num_enroll > 0) {
				$fetch_enroll = $query_enroll->result_array();
				foreach ($fetch_enroll as $key_score => $value_score) {
					$average_score += floatval($value_score['cosen_score']);
				}
				if ($average_score > 0) {
					$average_score = $average_score / $num_enroll;
				}
			}
			/* $this->db->from('lms_cos_enroll');
              $this->db->where('lms_cos_enroll.cosen_isDelete','0');
              $this->db->where('cos_id',$value['cos_id']);
              $query_enr = $this->db->get();
              $fetch_enr = $query_enr->result_array();*/

			$fetch_enr = $this->func_query->query_result('lms_cos_enroll', 'lms_emp', 'lms_cos_enroll.emp_id = lms_emp.emp_id', '', 'cos_id="' . $value['cos_id'] . '" and cosen_isDelete="0" and lms_emp.emp_isDelete="0"');
			$complete = 0;
			$inProgress = 0;
			$notStart = 0;
			foreach ($fetch_enr as $key_enr => $value_enr) {
				if ($value_enr['cosen_status_sub'] == "1") {
					$complete++;
				} else if ($value_enr['cosen_status_sub'] == "2") {
					if(checkDatetimeIsNull($value_enr['cosen_firsttime'])){
						$notStart++;
					} else {
						$inProgress++;
					}
				} else {
					$notStart++;
				}
			}
			$status_course = label('open');
			$fetch_chk_status = $this->func_query->query_row('lms_cos_detail', '', '', '', 'lms_cos_detail.cos_id = "' . $value['cos_id'] . '"');
			if (countArray($fetch_chk_status) > 0) {
				if ($fetch_chk_status['date_end'] != "0000-00-00 00:00:00" && date('Y-m-d H:i', strtotime($fetch_chk_status['date_end'])) < date('Y-m-d H:i')) {
					$status_course = label('sv_b_close');
				}
			}
			if ($value['cos_status'] == "0") {
				$status_course = label('sv_b_close');
			}
			$result_chkcg = $this->func_query->numrows('lms_cosincg', 'lms_cog', 'lms_cosincg.cg_id = lms_cog.cg_id', '', 'lms_cosincg.course_id="' . $value['cos_id'] . '" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
			if ($result_chkcg == 0) {
				$status_course = label('close');
			}
			$fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_type="2" and quiz_isDelete="0"', '', 'count(qiz_id) as total_qiz');
			if (countArray($fetch_qiz) > 0) {
				if (intval($fetch_qiz['total_qiz']) == 0) {
					$average_score = 0;
				} else {
					$fetch_sumscore = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id in (select lms_qiz.qiz_id from lms_qiz where cos_id="' . $value['cos_id'] . '" and quiz_type="2" and quiz_isDelete="0") and ques_isDelete="0"', '', 'sum(ques_score) as totalScore');
					if (countArray($fetch_sumscore) > 0) {
						if (floatval($fetch_sumscore['totalScore']) == 0) {
							$average_score = 0;
						}
					} else {
						$average_score = 0;
					}
				}
			} else {
				$average_score = 0;
			}
			if ($user['ug_id'] == "1") {
				$output['3'] = '<center>' . $value['com_code'] . '</center>';
				$output['4'] = '<center>' . $status_course . '</center>';
				$output['5'] = $average_score > 0 ? "<span style='float:right'>" . number_format($average_score) . "</span>" : "<center>-</center>";
				$output['6'] = "<span style='float:right'>" . number_format(countArray($fetch_enr)) . "</span>";
				$output['7'] = "<span style='float:right'>" . number_format($complete) . "</span>";
				$output['8'] = "<span style='float:right'>" . number_format($inProgress) . "</span>";
				$output['9'] = "<span style='float:right'>" . number_format($notStart) . "</span>";
			} else {
				$output['3'] = '<center>' . $status_course . '</center>';
				$output['4'] = $average_score > 0 ? "<span style='float:right'>" . number_format($average_score) . "</span>" : "<center>-</center>";
				$output['5'] = "<span style='float:right'>" . number_format(countArray($fetch_enr)) . "</span>";
				$output['6'] = "<span style='float:right'>" . number_format($complete) . "</span>";
				$output['7'] = "<span style='float:right'>" . number_format($inProgress) . "</span>";
				$output['8'] = "<span style='float:right'>" . number_format($notStart) . "</span>";
			}
			//$output['2'] = $lang=="thai"?$value['com_name_th']:$value['com_name_eng'];


			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	function fetch_Suggestion($svde_id)
	{
		$this->db->select("qnude_suggestion");
		$this->db->from('lms_qn_user_de');
		$this->db->where('svde_id', $svde_id);
		$this->db->where_not_in('qnude_suggestion', "");
		$query = $this->db->get();
		return $query->result();
	}


	function fetch_Suggestion_head($sv_id)
	{
		$this->db->select("qnu_suggestion");
		$this->db->from('lms_qn_user');
		$this->db->where('sv_id', $sv_id);
		$this->db->where_not_in('qnu_suggestion', "");
		$query = $this->db->get();
		return $query->result();
	}

	public function fetchLearnerReport($user, $com_id = "", $cos_id = "", $cosen_status_sub = "", $date_start = "", $date_end = "") {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'funcQuery', false);
		$this->manage->loadDB();

		$arrCompany = array();
		$arrEmp = array();
		$arrEmpUID = array();
		$arrCourses = array();
		$whereCompany = "com_isDelete = 0";
		$whereCosen = "cosen_isDelete = 0";

		if (!checkValueIsNullTypeNumber($com_id)) {
			$whereCompany .= " and lms_company.com_id = ".$com_id;
			$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$com_id.")";
		} else {
			if ($user['com_admin'] == "com_associated") {
				$whereCompany .= " and lms_company.com_id = ".$user['com_id'];
				$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$user['com_id'].")";
			} else {
				if ($user['ug_viewdata'] != "1") {
					$whereCompany .= " and lms_company.com_id = ".$user['com_id'];
					$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$user['com_id'].")";
				}
			}
		}
		if (!checkValueIsNullTypeString($cos_id)) {
			$whereCosen .= " and lms_cos_enroll.cos_id = ".$cos_id;
		}
		if (!checkValueIsNullTypeString($cosen_status_sub)) {
			if ($cosen_status_sub == "0") {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = 0";
			} elseif ($cosen_status_sub == "2") {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = 2";
			} else {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = ".$cosen_status_sub;
			}
		}
		if (!checkValueIsNullTypeString($date_start) && !checkValueIsNullTypeString($date_end)) {
			$whereCosen .= " and (lms_cos_enroll.cosen_finishtime BETWEEN '" . $date_start . "' AND '" . $date_end . "')";
		}
		
		$fetchCosens = $this->funcQuery->query_result(
			"lms_cos_enroll", "", "", "", $whereCosen,
			"cosen_id ASC", "cos_id, emp_id, cosen_finishtime"
		);
		$fetchArray = array();
		if (!empty($fetchCosens)) {
			$fetchCompanys = $this->funcQuery->query_result(
				"lms_company", "", "", "",
				$whereCompany, "",
				"com_id, com_code"
			);
			if (!empty($fetchCompanys)) {
				foreach ($fetchCompanys as $keyCompany) {
					$arrCompany[$keyCompany["com_id"]] = $keyCompany["com_code"];
				}
			}
			$fetchEmps = $this->funcQuery->query_result(
				"lms_emp", "lms_usp", "lms_usp.emp_id = lms_emp.emp_id", "", "", "",
				"lms_usp.u_id, lms_emp.emp_id, com_id, emp_c, fullname_th, fullname_en"
			);
			if (!empty($fetchEmps)) {
				foreach ($fetchEmps as $keyEmp) {
					if (isset($arrCompany[$keyEmp["com_id"]])) {
						$arrEmp[$keyEmp["emp_id"]] = array(
							"company" 		=> $arrCompany[$keyEmp["com_id"]],
							"username" 		=> $keyEmp["emp_c"],
							"fullname_th" 	=> $keyEmp["fullname_th"],
							"fullname_en" 	=> $keyEmp["fullname_en"],
						);
						$arrEmpUID[$keyEmp["u_id"]] = array(
							"company" 		=> $arrCompany[$keyEmp["com_id"]],
							"username" 		=> $keyEmp["emp_c"],
							"fullname" 		=> $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"],
						);
					}
				}
			}
			$whereCos = "cos_isDelete = 0";
			if (!checkValueIsNullTypeNumber($cos_id)) {
				$whereCos .= " and cos_id = ".$cos_id;
			}
			if ($user['ug_viewdata'] == "3") {
				$whereCos .= " and cos_createby = ".$user['u_id'];
			}
			$arrCosDetail = array();
			$fetchCourseDetails = $this->funcQuery->query_result(
				"lms_cos_detail", "", "", "", "cos_id in (select lms_cos.cos_id from lms_cos where ".$whereCos.")", "",
				"cos_id, date_start, date_end"
			);
			if (!empty($fetchCourseDetails)) {
				foreach ($fetchCourseDetails as $keyCosDetail) {
					$arrCosDetail[$keyCosDetail["cos_id"]] = array(
						"date_start" => $keyCosDetail["date_start"],
						"date_end" => $keyCosDetail["date_end"]
					);
				}
			}
			$fetchCourses = $this->funcQuery->query_result(
				"lms_cos", "", "", "", $whereCos, "",
				"cos_id, cname_th, cname_eng, cname_jp, cos_hour, cos_createby, cos_createdate"
			);
			if (!empty($fetchCourses)) {
				foreach ($fetchCourses as $keyCourse) {
					$period = '-';
					if(isset($arrCosDetail[$keyCourse["cos_id"]])){
						$fetch_detail = $arrCosDetail[$keyCourse["cos_id"]];
	  
						if ($fetch_detail['date_start']!="0000-00-00 00:00:00"&&$fetch_detail['date_end']!="0000-00-00 00:00:00"){
						  if ($lang=="thai") {
						  $periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/',strtotime($fetch_detail['date_start'])).(date('Y',strtotime($fetch_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_detail['date_start'])):"";
						  $periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/',strtotime($fetch_detail['date_end'])).(date('Y',strtotime($fetch_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_detail['date_end'])):"";
							$date_end = $periodend;
						  } else {
						  $periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_start'])):"";
						  $periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_end'])):"";
							$date_end = $periodend;
						  }
						 // $periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_start'])):"";
						 // $periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_end'])):"";
						  $date_end = $periodend;
						
						  if($periodstart!=""&&$periodend!=""){
							  $period = $periodstart." - ".$periodend;
						  }
						}
					}
					$arrCourses[$keyCourse["cos_id"]] = array(
						"cname_th" 		=> $keyCourse["cname_th"],
						"cname_eng" 	=> $keyCourse["cname_eng"],
						"cname_jp" 		=> $keyCourse["cname_jp"],
						"cos_hour" 		=> $keyCourse["cos_hour"],
						"period" 		=> $period,
						"createdate" 	=> !checkDatetimeIsNull($keyCourse["cos_createdate"]) ? ($lang=="thai" ? date('d/m/',strtotime($keyCourse['cos_createdate'])).(date('Y',strtotime($keyCourse['cos_createdate']))+543)." ".date('H:i',strtotime($keyCourse['cos_createdate'])) : date("d/m/Y H:i", strtotime($keyCourse["cos_createdate"]))) : "",
						"createby" 		=> isset($arrEmpUID[$keyCourse["cos_createby"]]) ? $arrEmpUID[$keyCourse["cos_createby"]]["fullname"] : "",
					);
				}
			}
			foreach ($fetchCosens as $keyCosen) {
				if (isset($arrCourses[$keyCosen["cos_id"]]) && isset($arrEmp[$keyCosen["emp_id"]])) {
					$dataEmp = $arrEmp[$keyCosen["emp_id"]];
					$dataCourse = $arrCourses[$keyCosen["cos_id"]];
					$output = array();
					$column = 0;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["cname_th"]) ? $dataCourse["cname_th"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["cname_eng"]) ? $dataCourse["cname_eng"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["cname_jp"]) ? $dataCourse["cname_jp"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["cos_hour"]) ? $dataCourse["cos_hour"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["period"]) ? $dataCourse["period"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["createby"]) ? $dataCourse["createby"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataCourse["createdate"]) ? $dataCourse["createdate"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataEmp["company"]) ? $dataEmp["company"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataEmp["username"]) ? $dataEmp["username"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataEmp["fullname_th"]) ? $dataEmp["fullname_th"] : ""; $column++;
					$output[$column] = !checkValueIsNullTypeString($dataEmp["fullname_en"]) ? $dataEmp["fullname_en"] : ""; $column++;
					$output[$column] = $keyCosen["cosen_finishtime"] != "0000-00-00 00:00:00" && $keyCosen["cosen_finishtime"] != "" ? textCenter(date("Y-m-d", strtotime($keyCosen["cosen_finishtime"]))) : "";

					array_push($fetchArray, $output);
				}
			}
		}
		$this->funcQuery->closeDB();
		return $fetchArray;
	}


	public function fetch_course_student($user, $com_id = "", $dep_id = "", $cos_id = "", $course_status = "", $cosen_status_sub = "", $date_start = "", $date_end = "")
	{

		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata('user');
		$date_now = date('Y-m-d H:i');
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();

		
		$arrEmployees = array();
		$where_emp = " and (lms_emp.emp_manage_a ='".$sess['emp_c']."' or lms_emp.emp_manage_b ='".$sess['emp_c']."')";
		$fetchEmployees = $this->func_query->query_result(
			"lms_emp",
			"lms_company",
			"lms_emp.com_id = lms_company.com_id", "",
			"lms_company.com_isDelete = 0 and lms_emp.emp_isDelete = 0".$where_emp, "",
			"emp_id, emp_c, fullname_th, fullname_en"
		);
		if (!empty($fetchEmployees)) {
			foreach ($fetchEmployees as $keyEmp) {
				$arrEmployees[$keyEmp["emp_id"]] = array(
					"empCode" 	=> $keyEmp['emp_c'],
					"fullname" 	=> $lang == "thai" ? $keyEmp['fullname_th'] : $keyEmp['fullname_en']
				);
			}
		}

		$whereCourse = "";
		$arrCourses = array();
		if ($course_status != "") {
			if ($course_status == "1") {
				$whereCourse .= " and lms_cos.cos_id in (
					select lms_cos_detail.cos_id from lms_cos_detail where (
						(lms_cos_detail.date_end='0000-00-00 00:00:00') or (lms_cos_detail.date_end >= '" . $date_now . "')
					) and cos_status = 1 and cosde_isDelete = 0)";
				$whereCourse .= " and lms_cos.cos_id in (select lms_cosincg.course_id from lms_cosincg inner join lms_cog on lms_cosincg.cg_id = lms_cog.cg_id where lms_cog.cg_status = 1 and lms_cog.cg_approve = 1 and lms_cog.cg_isDelete = 0)";
			} else {
				$whereCourse .= " and lms_cos.cos_id in (
					select lms_cos_detail.cos_id from lms_cos_detail where 
					lms_cos_detail.date_end != '0000-00-00 00:00:00' and lms_cos_detail.date_end < '" . $date_now . "' and cosde_status = 1 and cosde_isDelete = 0)";
			}
		}
		if ($cos_id != "") {
			$whereCourse .= " and cos_id = ".$cos_id;
		}
		$fetchCourses = $this->func_query->query_result(
			"lms_cos", "", "", "",
			"lms_cos.cos_isDelete = 0 and cos_public = 1 and cos_approve = 1".$whereCourse, "",
			"cos_id, cname_th, cname_eng, cname_jp, cos_status, cos_typegrading, goal_score, max_score"
		);
		if (!empty($fetchCourses)) {
			foreach ($fetchCourses as $keyCos) {
				
				if ($lang == "thai") {
					$cname = $keyCos['cname_th'] != "" ? $keyCos['cname_th'] : $keyCos['cname_eng'];
					$cname = $cname != "" ? $cname : $keyCos['cname_jp'];
				} else if ($lang == "english") {
					$cname = $keyCos['cname_eng'] != "" ? $keyCos['cname_eng'] : $keyCos['cname_th'];
					$cname = $cname != "" ? $cname : $keyCos['cname_jp'];
				} else {
					$cname = $keyCos['cname_jp'] != "" ? $keyCos['cname_jp'] : $keyCos['cname_eng'];
					$cname = $cname != "" ? $cname : $keyCos['cname_th'];
				}
				$fetch_detail = $this->func_query->query_row(
					"lms_cos_detail", "", "", "",
					"cos_id = ". $keyCos["cos_id"] ." and cosde_isDelete = 0", "", "date_end"
				);
				$cosStatus = label("open");
				if (isset($fetch_detail["date_end"])) {
					if ($fetch_detail["date_end"] != "0000-00-00 00:00:00" && date("Y-m-d H:i") > date("Y-m-d H:i", strtotime($fetch_detail["date_end"]))) {
						$cosStatus = label("sv_b_close");
					}
				}
				$result_chkcg = $this->func_query->numrows(
					"lms_cosincg",
					"lms_cog",
					"lms_cosincg.cg_id = lms_cog.cg_id", "",
					"lms_cosincg.course_id = " . $keyCos["cos_id"] . " and lms_cog.cg_status = 1 and lms_cog.cg_approve = 1 and lms_cog.cg_isDelete = 0"
				);
				if ($result_chkcg == 0) {
					$cosStatus = label("sv_b_close");
				}
				if ($keyCos["cos_status"] == "0") {
					$cosStatus = label("sv_b_close");
				}
				$where_shlg = 'cos_id = "' . $keyCos['cos_id'] . '" and qiz_id in (select lms_ques.qiz_id from lms_ques where ques_type in ("sa","sub") and ques_isDelete="0")';
				$fetchChkShlg = $this->func_query->numrows('lms_qiz', '', '', '', $where_shlg, "", "qiz_id");

				$fetchQiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $keyCos['cos_id'] . '" and quiz_status="1" and quiz_isDelete="0"');

				$arrCos[$keyCos["cos_id"]] = array(
					"cosName" 				=> $cname,
					"cosStatus" 			=> $cosStatus,
					"checkAmountQuestionSA" => $fetchChkShlg,
					"cosTypeGrading"		=> $keyCos["cos_typegrading"],
					"goalScore"				=> $keyCos["goal_score"],
					"maxScore"				=> $keyCos["max_score"],
					"haveQuiz"				=> $fetchQiz > 0 ? true : false
				);
			}
		}
		
		$arrPretests = array();
		$arrPostests = array();
		$fetchPretests = $this->func_query->query_result(
			"lms_qiz", "", "", "",
			"quiz_type = 1 and quiz_status = 1 and quiz_isDelete = 0", "",
			"cos_id, qiz_id, quiz_numofshown"
		);
		if (!empty($fetchPretests)) {
			foreach ($fetchPretests as $keyPretest) {
				if (!isset($arrPretests[$keyPretest["cos_id"]])) {
					$arrPretests[$keyPretest["cos_id"]] = array();
				}
				array_push($arrPretests[$keyPretest["cos_id"]], $keyPretest);
			}
		}
		$fetchPosttests = $this->func_query->query_result(
			"lms_qiz", "", "", "",
			"quiz_type = 2 and quiz_status = 1 and quiz_isDelete = 0", "",
			"cos_id, qiz_id"
		);
		if (!empty($fetchPosttests)) {
			foreach ($fetchPosttests as $keyPosttest) {
				if (!isset($arrPostests[$keyPosttest["cos_id"]])) {
					$arrPostests[$keyPosttest["cos_id"]] = array();
				}
				array_push($arrPostests[$keyPosttest["cos_id"]], $keyPosttest);
			}
		}

		$arrQuizHaveQuestionTypeSa = array();
		
		$fetchChkquesShlo = $this->func_query->query_result(
			'lms_ques', 'lms_qiz', 'lms_ques.qiz_id = lms_qiz.qiz_id', '',
			'ques_type in ("sub","sa") and ques_isDelete="0"', '',
			'lms_ques.ques_id, lms_qiz.cos_id'
		);
		if (!empty($fetchChkquesShlo)) {
			foreach ($fetchChkquesShlo as $keyQues) {
				if (!isset($arrQuizHaveQuestionTypeSa[$keyQues["cos_id"]])) {
					$arrQuizHaveQuestionTypeSa[$keyQues["cos_id"]] = array();
				}
				if (!in_array($keyQues["ques_id"], $arrQuizHaveQuestionTypeSa[$keyQues["cos_id"]])) {
					array_push($arrQuizHaveQuestionTypeSa[$keyQues["cos_id"]], $keyQues["ques_id"]);
				}
			}
		}

		
		$this->db->from('lms_cos_enroll');
		//$this->db->where('lms_company.com_admin','com_associated');
		$user = $this->session->userdata('user');
		$this->db->where('lms_cos_enroll.cosen_isDelete', '0');
		/*if(intval($user['ug_id'])>3){
              $com_id = $user['com_id'];
          }
          if($com_id!=""){
            $this->db->where('lms_cos.com_id',$com_id);
          }
          if($dep_id!=""){
            $where_dep = '(lms_emp.emp_id in (select lms_usp.emp_id from lms_usp where lms_usp.dep_id="'.$dep_id.'"))';
            $this->db->where($where_dep);
          }*/
		if ($cos_id != "") {
			$this->db->where('lms_cos_enroll.cos_id', $cos_id);
		}
		if ($cosen_status_sub != "") {
			if ($cosen_status_sub == "0") {
				$this->db->where('lms_cos_enroll.cosen_status_sub', '0');
				//$this->db->where('lms_cos_enroll.cosen_firsttime','0000-00-00 00:00:00');
			} else if ($cosen_status_sub == "2") {
				//$this->db->where('lms_cos_enroll.cosen_firsttime!=','0000-00-00 00:00:00');
				$this->db->where('lms_cos_enroll.cosen_status_sub', '2');
			} else {
				$this->db->where('lms_cos_enroll.cosen_status_sub', $cosen_status_sub);
			}
		}
		if ($date_start != "" && $date_end != "") {
			$where = "(lms_cos_enroll.cosen_finishtime BETWEEN '" . $date_start . "' AND '" . $date_end . "')";
			$this->db->where($where);
		}
		$this->db->order_by('lms_cos_enroll.cosen_id DESC');
		$this->db->select('cos_id, emp_id, cosen_id, cosen_status_sub, cosen_score, cosen_score_per, cosen_grade, cosen_firsttime, cosen_finishtime');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		if (!empty($fetch)) {
			foreach ($fetch as $key => $value) {
				if (isset($arrEmployees[$value["emp_id"]]) && isset($arrCos[$value["cos_id"]])) {
					$dataEmployee = $arrEmployees[$value["emp_id"]];
					$dataCourse = $arrCos[$value["cos_id"]];
	
					$output = array();
					$output['0'] = $dataCourse["checkAmountQuestionSA"] > 0 ? '<center><button type="button" name="view_answer" id="' . $value['cosen_id'] . '" data-toggle="modal" data-target="#modal-view_answer" class="btn btn-info btn-xs view_answer" title="' . label('answer') . '"><i class="mdi mdi-comment-text-outline"></i></button></center>' : '<center>-</center>';
					$output['1'] = $num;
					$num++;
					$output['2'] = $dataEmployee['empCode'];
					$output['3'] = $dataEmployee['fullname'];
	
	
					$output['4'] = $dataCourse["cosName"];
					$output['5'] = $dataCourse["cosStatus"];
					if ($value['cosen_status_sub'] == "0") {
						$output['6'] = label('not_start');
					} else if ($value['cosen_status_sub'] == "1") {
						$output['6'] = label('r_pass');
					} else if ($value['cosen_status_sub'] == "2") {
						if(checkDatetimeIsNull($value['cosen_firsttime'])){
						$output['6'] = label('not_start');
						}else{
						$output['6'] = label('inProgress');
						}
					} else {
						$output['6'] = label('not_start');
					}
					$score_pretest = 0;
					$score_posttest = 0;
					$score_pretest_full = 0;
					$score_posttest_full = 0;
					
					if (isset($arrPretests[$value["cos_id"]]) && !empty($arrPretests[$value["cos_id"]])) {
						foreach ($arrPretests[$value["cos_id"]] as $key_pretest => $value_pretest) {
							$sum_score_all = 0;
							$sum_score_quesall = 0;
							$fetch_chkpretest = $this->func_query->query_row(
								'lms_qiz_tc', '', '', '',
								'lms_qiz_tc.cosen_id="'.$value['cosen_id'].'" and lms_qiz_tc.qiz_id="'.$value_pretest['qiz_id'].'"
								 and qiztc_isDelete="0" and qiz_status="3"',
								'qiztc_id DESC', 'qiztc_id, sum_score'
							);

							$fetch_chkques = $this->func_query->query_row(
								'lms_ques', '', '', '',
								'lms_ques.qiz_id="' . $value_pretest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0"', '',
								'SUM(lms_ques.ques_score) as ques_score'
							);
							if (isset($fetch_chkques['ques_score'])) {
								$sum_score_quesall += floatval($fetch_chkques['ques_score']);
							}

							if (isset($fetch_chkpretest["qiztc_id"])) {
	
								// $fetch_chkques = $this->func_query->query_result('lms_ques','','','','lms_ques.qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
								// if(countArray($fetch_chkques)>0){
								//     foreach ($fetch_chkques as $key_chkques => $value_chkques) {
								//         $fetch_tc = $this->func_query->query_row('lms_ques_tc','','','','lms_ques_tc.ques_id="'.$value_chkques['ques_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkpretest['qiztc_id'].'"');
								//         if(countArray($fetch_tc)>0){
								//         $score_pretest+=floatval($fetch_tc['tc_score']);
								//         }else{
								//         $score_pretest+=0;
								//         }
								//         $sum_score_all+=floatval($value_chkques['ques_score']);
								//     }
								// }
								$fetch_tc = $this->func_query->query_row(
									'lms_ques_tc', '', '', '',
									'lms_ques_tc.qiz_id="'.$value_pretest['qiz_id'].'" and lms_ques_tc.cosen_id="'.$value['cosen_id'].'"
									 and lms_ques_tc.qiztc_id="'.$fetch_chkpretest['qiztc_id'].'"', '',
									 'SUM(lms_ques_tc.tc_score) as tc_score'
								);
								if (isset($fetch_tc['tc_score']) && floatval($fetch_tc['tc_score']) > 0) {
									$score_pretest += floatval($fetch_tc['tc_score']);
								} else {
									$score_pretest += floatval($fetch_chkpretest['sum_score']);
								}
	
								$fetch_sum = $this->func_query->query_row(
									'lms_ques', '', '', '',
									'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (
										select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="'.$value_pretest['qiz_id'].'"
										 and cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkpretest['qiztc_id'].'"
									) and ques_status="1" and ques_isDelete="0"', '',
									'SUM(ques_score) as total_score'
								);
		
								if (isset($fetch_sum['total_score'])) {
									$score_pretest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
								} else {
									$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
								}
							} else {
								$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
							}
	
						}
					}
					if (isset($arrPostests[$value["cos_id"]]) && !empty($arrPostests[$value["cos_id"]])) {
						foreach ($arrPostests[$value["cos_id"]] as $key_posttest => $value_posttest) {
							$sum_score_all = 0;
							$sum_score_quesall = 0;
							$fetch_chkposttest = $this->func_query->query_row(
								'lms_qiz_tc', '', '', '',
								'lms_qiz_tc.cosen_id="'.$value['cosen_id'].'" and lms_qiz_tc.qiz_id="'.$value_posttest['qiz_id'].'" and qiztc_isDelete = 0 and qiz_status = 3',
								'qiztc_id DESC', 'qiztc_id, sum_score'
							);
							$fetch_chkques = $this->func_query->query_row(
								'lms_ques', '', '', '',
								'lms_ques.qiz_id="' . $value_posttest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0"', '',
								'SUM(lms_ques.ques_score) as ques_score'
							);
							if (isset($fetch_chkques['ques_score'])) {
								$sum_score_quesall += floatval($fetch_chkques['ques_score']);
							}
							if (isset($fetch_chkposttest['qiztc_id'])) {
	
								// $fetch_chkques = $this->func_query->query_result('lms_ques','','','','lms_ques.qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
								// if(countArray($fetch_chkques)>0){
								//     foreach ($fetch_chkques as $key_chkques => $value_chkques) {
								//         $fetch_tc = $this->func_query->query_row('lms_ques_tc','','','','lms_ques_tc.ques_id="'.$value_chkques['ques_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkposttest['qiztc_id'].'"');
								//         if(countArray($fetch_tc)>0){
								//         $score_posttest+=floatval($fetch_tc['tc_score']);
								//         }else{
								//         $score_posttest+=0;
								//         }
								//         $sum_score_all+=floatval($value_chkques['ques_score']);
								//     }
								// }
								$fetch_tc = $this->func_query->query_row(
									'lms_ques_tc',
									'lms_ques',
									'lms_ques.ques_id = lms_ques_tc.ques_id', '',
									'lms_ques_tc.qiz_id="'.$value_posttest['qiz_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'"
									 and lms_ques_tc.qiztc_id="'.$fetch_chkposttest['qiztc_id'].'"', '',
									'SUM(lms_ques_tc.tc_score) as tc_score'
								);
								if (floatval($fetch_tc['tc_score']) > 0) {
									$score_posttest += floatval($fetch_tc['tc_score']);
								} else {
									$score_posttest += floatval($fetch_chkposttest['sum_score']);
								}
								$fetch_sum = $this->func_query->query_row(
									'lms_ques', '', '', '',
									'qiz_id="'.$value_posttest['qiz_id'].'" and ques_id in (
										select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="'.$value_posttest['qiz_id'].'"
										 and cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '"
									) and ques_status="1" and ques_isDelete="0"', '',
									'SUM(ques_score) as total_score'
								);
		
								if (isset($fetch_sum['total_score'])) {
									$score_posttest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
								} else {
									$score_posttest_full += $sum_score_quesall;
								}
							} else {
								$score_posttest_full += $sum_score_quesall;
							}
						}
					}
					/* $output['6'] = "<span style='float:right'>".number_format($score_pretest)."</span>";
					$output['7'] = "<span style='float:right'>".number_format($score_posttest)."</span>";  
					$output['8'] = "<span style='float:right'>".number_format($score_pretest)."</span>";
					$output['9'] = "<span style='float:right'>".number_format($score_posttest_full)."</span>"; */
	
					$output['7'] = "<span style='float:right'>" . number_format($score_pretest) . "</span>";
					$output['8'] = "<span style='float:right'>" . number_format($score_pretest_full) . "</span>";
					if ($dataCourse["haveQuiz"]) {
						$output['9'] = "<span style='float:right'>" . number_format($score_posttest) . "</span>";
						$output['10'] = "<span style='float:right'>" . number_format($score_posttest_full) . "</span>";
					} else {
						if ($value['cosen_status_sub'] != "1") {
							$output['9'] = "<span style='float:right'>0</span>";
							$output['10'] = "<span style='float:right'>0</span>";
						} else {
							$output['9'] = "<span style='float:right'>" . number_format($value['cosen_score']) . "</span>";
							$max_score = intval($dataCourse['maxScore']) == 0 ? number_format('100') : number_format($dataCourse['maxScore']);
							$output['10'] = "<span style='float:right'>" . $max_score . "</span>";
						}
					}
					$preReport = '-';
					$var_rechk = 1;
					if (isset($arrQuizHaveQuestionTypeSa[$value["cos_id"]])) {
						foreach ($arrQuizHaveQuestionTypeSa[$value["cos_id"]] as $value_chkques_shlo) {
							$fetch_chktc = $this->func_query->query_row(
								'lms_ques_tc', '', '', '',
								'lms_ques_tc.ques_id="'.$value_chkques_shlo.'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'"',
								'lms_ques_tc.tc_id DESC', 'lms_ques_tc.tc_isSavescore'
							);
							if (isset($fetch_chktc['tc_isSavescore']) && $fetch_chktc['tc_isSavescore'] == 0) {
								$var_rechk = 0;
							}
						}
					}
					if ($value['cosen_status_sub'] == "1" && $var_rechk == 1) {
						if ($dataCourse['cosTypeGrading'] == "1") {
							$preReport = $value['cosen_grade'] != "" ? $value['cosen_grade'] : '-';
						} else {
							// echo 
							if (intval($value['cosen_score_per']) >= intval($dataCourse['goalScore'])) {
								$preReport = label('pass');
							} else {
								$preReport = label('fail');
							}
						}
					}
					$output['11'] = "<center>" . $preReport . "</center>";
					/*
					$output['9'] = "<span style='float:right'>".number_format($value['cosen_score'])."</span>";  */
					// if($sess['is_manager']!="1"){
					/* if($lang=="thai"){
					$output['11'] = $value['cosen_finishtime']!="0000-00-00 00:00:00"?date('d',strtotime($value['cosen_finishtime']))." ".$thaimonth[intval(date('m',strtotime($value['cosen_finishtime'])))]." ".(date('d',strtotime($value['cosen_finishtime']))+543)." ".date('H:i',strtotime($value['cosen_finishtime'])):"<center>-</center>";
					}else{
					$output['11'] = $value['cosen_finishtime']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value['cosen_finishtime'])):"<center>-</center>";
					}*/
					$output['12'] = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['cosen_finishtime'])) : "<center>-</center>";
					// }
					/*$this->db->from('lms_usp');
					$this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
					$this->db->where('lms_usp.emp_id',$value['emp_id']);
					$query_usp = $this->db->get();
					$fetch_usp = $query_usp->row_array();
					$cosen_reward = intval($value['cosen_reward']) == 0 ? "-" : number_format($value['cosen_reward']);
					if($btn_status==""){
						$cosen_pfm = "";
						if(intval($value['cosen_pfm']) == 0){
							for ($i=0; $i < 5; $i++) { 
							$cosen_pfm .= '<i class="mdi mdi-star text-default"></i>';
							}
						}else{
						for ($i=0; $i < intval($value['cosen_pfm']); $i++) { 
							$cosen_pfm .= '<i class="mdi mdi-star text-warning"></i>';
						}
						$num_point = 5-intval($value['cosen_pfm']);
						if($num_point>0){
							for ($i=0; $i < $num_point; $i++) { 
							$cosen_pfm .= '<i class="mdi mdi-star text-default"></i>';
							}
						}
						}
						//$cosen_pfm = intval($value['cosen_pfm']) == 0 ? "-" : number_format($value['cosen_pfm']);
					}else{
						$cosen_pfm = "";
						if(intval($value['cosen_pfm']) == 0){
						$cosen_pfm ='<input type="hidden" id="php1_hidden'.$value['cosen_id'].'" value="1"><i class="mdi mdi-star php1'.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php1" id="'.$value['cosen_id'].'"></i><input type="hidden" id="php2_hidden'.$value['cosen_id'].'" value="2"><i class="mdi mdi-star php2'.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php2" id="'.$value['cosen_id'].'"></i><input type="hidden" id="php3_hidden'.$value['cosen_id'].'" value="3"><i class="mdi mdi-star php3'.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php3" id="'.$value['cosen_id'].'"></i><input type="hidden" id="php4_hidden'.$value['cosen_id'].'" value="4"><i class="mdi mdi-star php4'.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php4" id="'.$value['cosen_id'].'"></i><input type="hidden" id="php5_hidden'.$value['cosen_id'].'" value="5"><i class="mdi mdi-star php5'.$value['cosen_id'].' p-r-10" onclick="change(this.title,this.id)" title="php5" id="'.$value['cosen_id'].'"></i><input type="hidden" name="phprating[]" id="phprating'.$value['cosen_id'].'" value="0"><input type="hidden" name="emp_id[]" id="emp_id" value="'.$value['emp_id'].'"><input type="hidden" name="cos_id[]" id="cos_id" value="'.$value['cos_id'].'">';  
						}else{
						for ($i=1; $i <= intval($value['cosen_pfm']); $i++) { 
							$cosen_pfm .= '<input type="hidden" id="php'.$i.'_hidden'.$value['cosen_id'].'" value="'.$i.'"><i class="mdi mdi-star text-warning php'.$i.''.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php'.$i.'" id="'.$value['cosen_id'].'"></i>';
						}
						$num_point = 5-intval($value['cosen_pfm']);
						if($num_point>0){
							for ($i=intval($value['cosen_pfm'])+1; $i <= 5; $i++) { 
							$cosen_pfm .= '<input type="hidden" id="php'.$i.'_hidden'.$value['cosen_id'].'" value="'.$i.'"><i class="mdi mdi-star text-default php'.$i.''.$value['cosen_id'].'" onclick="change(this.title,this.id)" title="php'.$i.'" id="'.$value['cosen_id'].'"></i>';
							}
						}
						$cosen_pfm .= '<input type="hidden" name="phprating[]" id="phprating'.$value['cosen_id'].'" value="'.(intval($value['cosen_pfm'])).'"><input type="hidden" name="emp_id[]" id="emp_id" value="'.$value['emp_id'].'"><input type="hidden" name="cos_id[]" id="cos_id" value="'.$value['cos_id'].'">';
						}
					}
					*/
	
	
	
					$count++;
					array_push($fetch_arr, $output);
				}
			}
		}
		return $fetch_arr;
	}

	public function fetch_coursename_detail($user, $cos_id = "", $com_id = "")
	{

		date_default_timezone_set("Asia/Bangkok");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$where = 'lms_cos_enroll.cos_id="' . $cos_id . '" and lms_cos_enroll.cosen_isDelete="0" and lms_emp.emp_isDelete="0"';
		$select_column = 'lms_emp.fullname_th as fullname,';
		if ($lang != "thai") {
			$select_column = 'lms_emp.fullname_en as fullname,';
		}
		if ($com_id != '') {
			$where .= ' and lms_emp.com_id = "' . $com_id . '"';
		}
		$select_column .= 'lms_emp.emp_id,lms_emp.emp_c,lms_emp.com_id,lms_cos_enroll.cosen_status_sub,lms_cos_enroll.cosen_firsttime,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_id,lms_cos_enroll.cos_id,lms_cos_enroll.cosen_score';
		$fetch = $this->func_query->query_result('lms_cos_enroll', 'lms_emp', 'lms_cos_enroll.emp_id = lms_emp.emp_id', '', $where, '', $select_column);
		//$this->db->where('lms_company.com_admin','com_associated');
		$user = $this->session->userdata('user');
		if (intval($user['ug_id']) > 3) {
			$com_id = $user['com_id'];
		}
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		$fetch_cug = $this->func_query->query_row('lms_cug', '', '', '', 'course_id="' . $cos_id . '"');
		$fetch_qiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_isDelete="0"', '', 'qiz_id');
		$fetch_pretest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_type="1" and quiz_status="1" and quiz_isDelete="0"', '', 'qiz_id, quiz_numofshown');
		$fetch_posttest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_type="2" and quiz_status="1" and quiz_isDelete="0"', '', 'qiz_id, quiz_numofshown');
		$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"', '', 'cos_typegrading, goal_score');
		foreach ($fetch as $key => $value) {
			$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id = "' . $value['com_id'] . '"', '', 'com_name_th, com_name_eng');
			$fetch_user = $this->func_query->query_row('lms_usp', 'lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', '', 'lms_usp.emp_id = "' . $value['emp_id'] . '"', '', 'lms_depart.dep_name_th, lms_depart.dep_name_en, lms_usp.inactivedate');
			$output = array();
			$output['username'] = $value['emp_c'];
			$output['m_name'] = $value['fullname'];
			$output['m_company'] = $lang == "thai" ? $fetch_company['com_name_th'] : $fetch_company['com_name_eng'];
			$output['r_organization'] = $lang == "thai" ? $fetch_user['dep_name_th'] : $fetch_user['dep_name_en'];

			$inactive_check = $fetch_user['inactivedate'] != "0000-00-00" && date("Y-m-d") >= date("Y-m-d", strtotime($fetch_user['inactivedate'])) ? "<center>" . label('cos_report_inactive') . "</center>" : "";

			$output['inactive_check'] = "<center>" . $inactive_check . "</center>";

			if ($value['cosen_status_sub'] == "0") {
				$output['learning_status'] = label('not_start');
			} else if ($value['cosen_status_sub'] == "1") {
				$output['learning_status'] = label('r_pass');
			} else if ($value['cosen_status_sub'] == "2") {
				if(checkDatetimeIsNull($value['cosen_firsttime'])){
                  	$output['learning_status'] = label('not_start');
                }else{
					$output['learning_status'] = label('inProgress');
				}
			} else {
				$output['learning_status'] = label('not_start');
			}
			$score_pretest = 0;
			$score_posttest = 0;
			$score_pretest_full = 0;
			$score_posttest_full = 0;
			if (countArray($fetch_pretest) > 0) {
				foreach ($fetch_pretest as $key_pretest => $value_pretest) {
					$sum_score_all = 0;
					$sum_score_quesall = 0;
					$fetch_chkpretest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_pretest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
					if (countArray($fetch_chkpretest) > 0) {

						$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_pretest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
						if (floatval($fetch_tc['tc_score']) > 0) {
							$score_pretest += floatval($fetch_tc['tc_score']);
						} else {
							$score_pretest += floatval($fetch_chkpretest['sum_score']);
						}
						$sum_score_all += floatval($fetch_tc['ques_score']);
					} else {
						$fetch_chkques = $this->func_query->query_row('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(lms_ques.ques_score) as ques_score');
						if (countArray($fetch_chkques) > 0) {
							$sum_score_quesall += floatval($fetch_chkques['ques_score']);
						}
					}

					@$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
					if (countArray($fetch_sum) > 0) {
						$score_pretest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
					} else {
						$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
					}
				}
			}
			if (countArray($fetch_posttest) > 0) {
				foreach ($fetch_posttest as $key_posttest => $value_posttest) {
					$sum_score_all = 0;
					$sum_score_quesall = 0;

					$fetch_chkposttest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_posttest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
					if (countArray($fetch_chkposttest) > 0) {

						$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_posttest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
						if (floatval($fetch_tc['tc_score']) > 0) {
							$score_posttest += floatval($fetch_tc['tc_score']);
						} else {
							$score_posttest += floatval($fetch_chkposttest['sum_score']);
						}
						$sum_score_all += floatval($fetch_tc['ques_score']);
					} else {
						$fetch_chkques = $this->func_query->query_row('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(lms_ques.ques_score) as ques_score');
						if (countArray($fetch_chkques) > 0) {
							$sum_score_quesall += floatval($fetch_chkques['ques_score']);
						}
					}

					@$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
					if (countArray($fetch_sum) > 0) {
						$score_posttest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
					} else {
						$score_posttest_full += $sum_score_quesall;
					}
				}
			}

			// if (in_array($output['learning_status'], array(label('inProgress'),label('not_start')))) {
			//   $score_pretest_full = 0;
			//   $score_posttest_full = 0;
			// }
			if (countArray($fetch_pretest) > 0) {
				$output['score_pretest'] = "<span style='float:right'>" . number_format($score_pretest) . "</span>";
				$output['maxScore_pretest'] = "<span style='float:right'>" . number_format($score_pretest_full) . "</span>";
			} else {
				$output['score_pretest'] = "<span style='float:right'>0</span>";
				$output['maxScore_pretest'] = "<span style='float:right'>0</span>";
			}
			if ($fetch_qiz > 0) {
				$output['score_posttest'] = "<span style='float:right'>" . number_format($score_posttest) . "</span>";
				$output['maxScore_posttest'] = "<span style='float:right'>" . number_format($score_posttest_full) . "</span>";
			} else {
				$output['score_posttest'] = "<span style='float:right'>0</span>";
				$output['maxScore_posttest'] = "<span style='float:right'>0</span>";
			}
			$output['learning_status'] = "<center>" . $output['learning_status'] . "</center>";
			$preReport = '-';
			$var_rechk = 1;
			$fetch_chkques_shlo = $this->func_query->query_result('lms_ques', '', '', '', 'ques_type in ("sub","sa") and qiz_id in (select lms_qiz.qiz_id from lms_qiz where lms_qiz.cos_id = "' . $value['cos_id'] . '") and ques_isDelete="0"', '', 'lms_ques.ques_id');
			if (countArray($fetch_chkques_shlo) > 0) {
				foreach ($fetch_chkques_shlo as $key_chkques_shlo => $value_chkques_shlo) {
					$fetch_chktc = $this->func_query->query_row('lms_ques_tc', '', '', '', 'lms_ques_tc.ques_id="' . $value_chkques_shlo['ques_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '"', 'lms_ques_tc.tc_id DESC', 'tc_isSavescore');
					if (countArray($fetch_chktc) > 0) {
						if ($fetch_chktc['tc_isSavescore'] == "0") {
							$var_rechk = 0;
						}
					}
				}
			}
			if ($value['cosen_status_sub'] == "1") {

				if ($fetch_cos['cos_typegrading'] == "1") {
					$cosen_score_per = (floatval($value['cosen_score']) / $score_posttest_full * 100);
					if ($cosen_score_per >= floatval($fetch_cug['mina'])) {
						$cosen_grade = "A";
					} else if ($cosen_score_per >= floatval($fetch_cug['minb'])) {
						$cosen_grade = "B";
					} else if ($cosen_score_per >= floatval($fetch_cug['minc'])) {
						$cosen_grade = "C";
					} else if ($cosen_score_per >= floatval($fetch_cug['mind'])) {
						$cosen_grade = "D";
					} else {
						$cosen_grade = "F";
					}
					$preReport = @$value['cosen_grade'] != "" ? @$value['cosen_grade'] : @$cosen_grade;
				} else {
					if ($score_posttest_full == 0 || (floatval($value['cosen_score']) / $score_posttest_full * 100) >= intval($fetch_cos['goal_score'])) {
						$preReport = label('pass');
					} else {
						$preReport = label('fail');
					}
				}
			}
			$output['preReport'] = "<center>" . $preReport . "</center>";

			$r_finish_emp = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['cosen_finishtime'])) : "<center>-</center>";
			$r_finish_empori = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? $value['cosen_finishtime'] : "";
			$arrr_finish_emp = array(
				'display' => $r_finish_emp,
				'timestamp' => strtotime($r_finish_empori),
			);
			$output['r_finish_emp'] = $arrr_finish_emp;

			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_course_personal($user, $course_status = "", $cosen_status_sub = "", $date_start = "", $date_end = "")
	{

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$date_end = $date_end != "" && $date_end != "0000-00-00 00:00:00" ? $date_end : date('Y-m-d H:i');
		$this->db->from('lms_cos_enroll');
		$this->db->join('lms_cos', 'lms_cos_enroll.cos_id = lms_cos.cos_id');
		$this->db->join('lms_company', 'lms_cos.com_id = lms_company.com_id');
		$this->db->join('lms_emp', 'lms_cos_enroll.emp_id = lms_emp.emp_id');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		$this->db->where('lms_cos.cos_isDelete', '0');
		$this->db->where('lms_emp.emp_isDelete', '0');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->where('lms_cos_enroll.cosen_isDelete', '0');
		$this->db->where('lms_cos.cos_public', '1');
		$this->db->where('lms_cos.cos_approve', '1');
		if ($course_status != "") {
			if ($course_status == "1") {
				//$this->db->where('lms_cos.cos_status','1');
				$where = 'lms_cos.cos_id in (select lms_cos_detail.cos_id from lms_cos_detail where ((lms_cos_detail.date_end="0000-00-00 00:00:00") or (lms_cos_detail.date_end >= "' . $date_end . '")) and cos_status="1" and cosde_isDelete="0")';
				$this->db->where($where);
			} else {
				//$this->db->where('lms_cos.cos_status','0');
				$where = 'lms_cos.cos_id in (select lms_cos_detail.cos_id from lms_cos_detail where lms_cos_detail.date_end!="0000-00-00 00:00:00" and lms_cos_detail.date_end < "' . $date_end . '" and cosde_status="1" and cosde_isDelete="0")';
				$this->db->where($where);
			}
		}
		if ($cosen_status_sub != "") {
			if ($cosen_status_sub == "0") {
				$this->db->where('lms_cos_enroll.cosen_status_sub', '0');
				//$this->db->where('lms_cos_enroll.cosen_firsttime','0000-00-00 00:00:00');
			} else if ($cosen_status_sub == "2") {
				// $this->db->where('lms_cos_enroll.cosen_firsttime!=','0000-00-00 00:00:00');
				$this->db->where('lms_cos_enroll.cosen_status_sub', '2');
			} else {
				$this->db->where('lms_cos_enroll.cosen_status_sub', $cosen_status_sub);
			}
		}
		if ($date_start != "" && $date_end != "") {
			$where = "(lms_cos_enroll.cosen_finishtime BETWEEN '" . $date_start . "' AND '" . $date_end . "')";
			$this->db->where($where);
		}
		$this->db->order_by('lms_cos_enroll.cosen_id DESC');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();

		if ($course_status != "") {
			if ($course_status == "1") {
				foreach ($fetch as $key => $value) {
					$result_chkcg = $this->func_query->numrows('lms_cosincg', 'lms_cog', 'lms_cosincg.cg_id = lms_cog.cg_id', '', 'lms_cosincg.course_id="' . $value['cos_id'] . '" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
					if ($result_chkcg == 0) {
						unset($fetch[$key]);
					}
				}
			}
		}
		foreach ($fetch as $key => $value) {
			$fetch_qiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_isDelete="0"');
			$average_score = 0;
			if ($lang == "thai") {
				$cname = $value['cname_th'] != "" ? $value['cname_th'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
			} else if ($lang == "english") {
				$cname = $value['cname_eng'] != "" ? $value['cname_eng'] : $value['cname_th'];
				$cname = $cname != "" ? $cname : $value['cname_jp'];
			} else {
				$cname = $value['cname_jp'] != "" ? $value['cname_jp'] : $value['cname_eng'];
				$cname = $cname != "" ? $cname : $value['cname_th'];
			}

			$where_shlg = 'cos_id = "' . $value['cos_id'] . '" and qiz_id in (select lms_ques.qiz_id from lms_ques where ques_type in ("sa","sub") and ques_isDelete="0")';
			$fetch_chk_shlg = $this->func_query->numrows('lms_qiz', '', '', '', $where_shlg);
			$output = array();
			$output['button_all'] = $fetch_chk_shlg > 0 ? '<center><button type="button" name="view_answer" id="' . $value['cosen_id'] . '" data-toggle="modal" data-target="#modal-view_answer" class="btn btn-info btn-xs view_answer" title="' . label('answer') . '"><i class="mdi mdi-comment-text-outline"></i></button></center>' : '<center>-</center>';

			$output['cname'] = $cname;
			$fetch_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $value['cos_id'] . '" and cosde_isDelete="0"');
			$cos_status = label('open');
			if (countArray($fetch_detail) > 0) {
				if ($fetch_detail['date_end'] != "0000-00-00 00:00:00" && date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($fetch_detail['date_end']))) {
					$cos_status = label('sv_b_close');
				}
			}
			if ($value['cos_status'] == "0") {
				$cos_status = label('sv_b_close');
			}
			$output['cos_status'] = $cos_status;
			if ($value['cosen_status_sub'] == "0") {
				$output['status_learner'] = label('not_start');
			} else if ($value['cosen_status_sub'] == "1") {
				$output['status_learner'] = label('r_pass');
			} else if ($value['cosen_status_sub'] == "2") {
				if(checkDatetimeIsNull($value['cosen_firsttime'])){
                  	$output['status_learner'] = label('not_start');
                }else{
					$output['status_learner'] = label('inProgress');
				}
			} else {
				$output['status_learner'] = label('not_start');
			}
			$score_pretest = 0;
			$score_posttest = 0;
			$score_pretest_full = 0;
			$score_posttest_full = 0;
			$fetch_pretest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_type="1" and quiz_status="1" and quiz_isDelete="0"');
			$fetch_posttest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_type="2" and quiz_status="1" and quiz_isDelete="0"');

			if (countArray($fetch_pretest) > 0) {
				foreach ($fetch_pretest as $key_pretest => $value_pretest) {
					$sum_score_all = 0;
					$sum_score_quesall = 0;
					$fetch_chkpretest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_pretest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
					if (countArray($fetch_chkpretest) > 0) {

						$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_pretest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
						if (floatval($fetch_tc['tc_score']) > 0) {
							$score_pretest += floatval($fetch_tc['tc_score']);
						} else {
							$score_pretest += floatval($fetch_chkpretest['sum_score']);
						}
						$sum_score_all += floatval($fetch_tc['ques_score']);
					} else {
						$fetch_chkques = $this->func_query->query_row('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(lms_ques.ques_score) as ques_score');
						if (countArray($fetch_chkques) > 0) {
							$sum_score_quesall += floatval($fetch_chkques['ques_score']);
						}
					}

					$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
					if (countArray($fetch_sum) > 0) {
						$score_pretest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
					} else {
						$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
					}
				}
			}
			if (countArray($fetch_posttest) > 0) {
				foreach ($fetch_posttest as $key_posttest => $value_posttest) {
					$sum_score_all = 0;
					$sum_score_quesall = 0;
					$fetch_chkposttest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_posttest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
					if (countArray($fetch_chkposttest) > 0) {
						// $fetch_chkques = $this->func_query->query_result('lms_ques','','','','lms_ques.qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
						// if(countArray($fetch_chkques)>0){
						//     foreach ($fetch_chkques as $key_chkques => $value_chkques) {
						//         $fetch_tc = $this->func_query->query_row('lms_ques_tc','','','','lms_ques_tc.ques_id="'.$value_chkques['ques_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkposttest['qiztc_id'].'"');
						//         if(countArray($fetch_tc)>0){
						//           if ($fetch_tc['tc_save'] == "true") {
						//               $score_posttest+=floatval($fetch_tc['tc_score']);
						//           } else {
						//               $score_posttest+=floatval($fetch_chkposttest['sum_score']);
						//           }
						//         }else{
						//         $score_posttest+=0;
						//         }
						//         $sum_score_all+=floatval($value_chkques['ques_score']);
						//     }
						// }

						$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_posttest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
						if (floatval($fetch_tc['tc_score']) > 0) {
							$score_posttest += floatval($fetch_tc['tc_score']);
						} else {
							$score_posttest += floatval($fetch_chkposttest['sum_score']);
						}

						$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
						if (countArray($fetch_sum) > 0) {

							$score_posttest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
						}
					} else {
						$fetch_chkques = $this->func_query->query_result('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"');
						if (countArray($fetch_chkques) > 0) {
							foreach ($fetch_chkques as $key_chkques => $value_chkques) {
								$sum_score_quesall += floatval($value_chkques['ques_score']);
							}
						}
						$score_posttest_full += $sum_score_quesall;
					}
				}
			}
			// if (in_array($output['status_learner'], array(label('inProgress'),label('not_start')))) {
			//   $score_pretest_full = 0;
			//   $score_posttest_full = 0;
			// }
			$output['score_pretest'] = "<span style='float:right'>" . number_format($score_pretest) . "</span>";
			$output['score_pretest_full'] = "<span style='float:right'>" . number_format($score_pretest_full) . "</span>";

			if ($fetch_qiz > 0) {
				$output['score_posttest'] = "<span style='float:right'>" . number_format($score_posttest) . "</span>";
				$output['score_posttest_full'] = "<span style='float:right'>" . number_format($score_posttest_full) . "</span>";
			} else {
				/*if($value['cosen_status_sub']!="1"){*/
				$output['score_posttest'] = "<span style='float:right'>0</span>";
				$output['score_posttest_full'] = "<span style='float:right'>0</span>";
				/*}else{
                $output['score_posttest'] = "<span style='float:right'>".number_format($value['cosen_score'])."</span>";
                $max_score = number_format($value['max_score'])==0?number_format('100'):number_format($value['max_score']);
                $output['score_posttest_full'] = "<span style='float:right'>".$max_score."</span>";  
                } */
			}

			$preReport = '-';
			$var_rechk = 1;
			$fetch_chkques_shlo = $this->func_query->query_result('lms_ques', '', '', '', 'ques_type in ("sub","sa") and qiz_id in (select lms_qiz.qiz_id from lms_qiz where lms_qiz.cos_id = "' . $value['cos_id'] . '") and ques_isDelete="0"');
			if (countArray($fetch_chkques_shlo) > 0) {
				foreach ($fetch_chkques_shlo as $key_chkques_shlo => $value_chkques_shlo) {
					$fetch_chktc = $this->func_query->query_row('lms_ques_tc', '', '', '', 'lms_ques_tc.ques_id="' . $value_chkques_shlo['ques_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '"', 'lms_ques_tc.tc_id DESC');
					if (countArray($fetch_chktc) > 0) {
						if ($fetch_chktc['tc_isSavescore'] == "0") {
							$var_rechk = 0;
						}
					}
				}
			}
			if ($value['cosen_status_sub'] == "1" && $var_rechk == 1) {
				if ($value['cos_typegrading'] == "1") {
					$preReport = $value['cosen_grade'] != "" ? $value['cosen_grade'] : '-';
				} else {
					if ($score_posttest_full == 0 || (floatval($value['cosen_score']) / $score_posttest_full * 100) >= intval($value['goal_score'])) {
						$preReport = label('pass');
					} else {
						$preReport = label('fail');
					}
				}
			}
			$output['preReport'] = "<center>" . $preReport . "</center>";
			/* if($lang=="thai"){
              $output['8'] = $value['cosen_finishtime']!="0000-00-00 00:00:00"?date('d',strtotime($value['cosen_finishtime']))." ".$thaimonth[intval(date('m',strtotime($value['cosen_finishtime'])))]." ".(date('d',strtotime($value['cosen_finishtime']))+543)." ".date('H:i',strtotime($value['cosen_finishtime'])):"<center>-</center>";
              }else{
              $output['8'] = $value['cosen_finishtime']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value['cosen_finishtime'])):"<center>-</center>";
              }*/
			$cosen_finishtime = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['cosen_finishtime'])) : "<center>-</center>";
			$cosen_finishtimeori = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? $value['cosen_finishtime'] : "";
			$arrcosen_finishtimeori = array(
				'display' => $cosen_finishtime,
				'timestamp' => strtotime($cosen_finishtimeori),
			);

			$output['cosen_finishtime'] = $arrcosen_finishtimeori;
			//$output['cosen_finishtime'] = $value['cosen_finishtime']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['cosen_finishtime'])):"<center>-</center>";
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetchLogImportUsersDetail($user, $lgiId, $comId) {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'funcQuery', FALSE);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");

		$fetchArr = array();
		$arrEmployees = array();
		$arrCompanys = array();
		if (isset($user["emp_id"])) {
			$fetchCompanys = $this->funcQuery->query_result("lms_company");
			if (!empty($fetchCompanys)) {
				foreach ($fetchCompanys as $keyCompany) {
					$isPassCompany = true;
					if ($comId != "" && $comId != $keyCompany["com_id"]) {
						$isPassCompany = false;
					}
					if ($isPassCompany) {
						$arrCompanys[$keyCompany["com_id"]] = $lang == "thai" ? $keyCompany["com_name_th"] : $keyCompany["com_name_eng"];
					}
				}
			}
			$fetchEmps = $this->funcQuery->query_result(
				"lms_emp",
				"lms_usp",
				"lms_emp.emp_id = lms_usp.emp_id", "", "", "",
				"lms_emp.emp_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri"
			);
			if (!empty($fetchEmps)) {
				foreach ($fetchEmps as $keyEmp) {
					if (isset($arrCompanys[$keyEmp["com_id"]])) {
						$arrEmployees[$keyEmp["emp_id"]] = array(
							"company"	=> $arrCompanys[$keyEmp["com_id"]],
							"username"  => $keyEmp["useri"],
							"fullname"  => $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"]
						);
					}
				}
			}
			$fetchLogDetail = $this->funcQuery->query_result("lms_lg_import_detail", "", "", "", "lgi_id = ".$lgiId);
			if (!empty($fetchLogDetail)) {
				foreach ($fetchLogDetail as $keyLogDetail) {
					if (isset($arrEmployees[$keyLogDetail["emp_id"]])) {
						$dataEmp = $arrEmployees[$keyLogDetail["emp_id"]];
						$statusImport = label("newUser");
						if ($keyLogDetail["lgid_status"] == 2) {
							$statusImport = label("duplicateUser");
						} else if ($keyLogDetail["lgid_status"] == 3) {
							$statusImport = label("removeUser");
						}
						$dateDisplay = date("d/m/Y H:i:s", strtotime($keyLogDetail["lgid_datetime"]));
						if ($lang == "thai") {
						  $dateDisplay = date('d/m',strtotime($keyLogDetail['lgid_datetime']))."/".(date('Y',strtotime($keyLogDetail['lgid_datetime']))+543)." ".date('H:i:s',strtotime($keyLogDetail['lgid_datetime']));
						}

						$arrLogDate = array(
						  "display"   => $dateDisplay,
						  "timestamp" => strtotime($keyLogDetail["lgid_datetime"])
						);
						
						array_push($fetchArr, array(
							"username" 		=> $dataEmp["username"],
							"fullname" 		=> $dataEmp["fullname"],
							"company" 		=> textCenter($dataEmp["company"]),
							"statusImport" 	=> textCenter($statusImport),
							"logdate" 		=> $arrLogDate
						));
					}
				}
			}
		}

		return $fetchArr;
	}
}
