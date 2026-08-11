<?php
class Manage_model extends CI_Model
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

	private function course_availability_where($alias = 'lms_cos_detail')
	{
		$now = date('Y-m-d H:i');
		return "((" . $alias . ".date_start IS NULL OR CAST(" . $alias . ".date_start AS CHAR) = '' OR " . $alias . ".date_start = '0000-00-00 00:00:00' OR " . $alias . ".date_start <= '" . $now . "')"
			. " AND (" . $alias . ".date_end IS NULL OR CAST(" . $alias . ".date_end AS CHAR) = '' OR " . $alias . ".date_end = '0000-00-00 00:00:00' OR " . $alias . ".date_end >= '" . $now . "'))";
	}
	public function course_total()
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->db->select('lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cos_pic,lms_cos_enroll.cosen_status,lms_cos_enroll.cosen_status_sub,lms_cos_enroll.cosen_grade,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_firsttime,lms_cos_detail.date_start,lms_cos_detail.date_end');
		$this->db->from('lms_cos');
		$this->db->join('lms_cos_enroll', 'lms_cos.cos_id = lms_cos_enroll.cos_id');
		$this->db->join('lms_cos_detail', 'lms_cos.cos_id = lms_cos_detail.cos_id', 'LEFT');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		$this->db->where($this->course_availability_where('lms_cos_detail'));
		$this->db->where('lms_cos.cos_status', '1');
		$this->db->where('lms_cos_detail.cosde_status', '1');
		$this->db->where('lms_cos_enroll.cosen_status!=', '2');
		$this->db->order_by('lms_cos_detail.date_end', 'ASC');
		$this->db->limit(4);
		$query = $this->db->get();
		$fetch = $query->result_array();
		return $fetch;
	}
	public function countamount_emp($type = "")
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->load->model('Course_model', 'course', TRUE);
		if ($type == "coursetotal") {
			$where = "lms_cos.cos_id in (select lms_cos_detail.cos_id from lms_cos_detail
											 where lms_cos_detail.cosde_id in (select lms_cos_detail_ug.cosde_id from lms_cos_detail_ug where lms_cos_detail_ug.posi_id = '" . $user['posi_id'] . "')
											 and lms_cos_detail.cosde_status = '1' and " . $this->course_availability_where('lms_cos_detail') . ")";
			$this->db->where($where);
			$this->db->from('lms_cos');
			$this->db->select('lms_cos.cos_id');
			$query = $this->db->get();
			return $query->num_rows();
		} else if ($type == "enroll") {
			$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
			$this->db->where('lms_cos_enroll.cosen_status', '1');
			$this->db->from('lms_cos_enroll');
			$this->db->select('lms_cos_enroll.cosen_id');
			$query = $this->db->get();
			return $query->num_rows();
		} else if ($type == "inProcess") {
			$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
			$this->db->where('lms_cos_enroll.cosen_status', '1');
			$this->db->where('lms_cos_enroll.cosen_status_sub', '2');
			$this->db->where('lms_cos_enroll.cosen_firsttime!=', '0000-00-00 00:00:00');
			$this->db->from('lms_cos_enroll');
			$this->db->select('lms_cos_enroll.cosen_id');
			$query = $this->db->get();
			return $query->num_rows();
		} else if ($type == "success") {
			$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
			$this->db->where('lms_cos_enroll.cosen_status', '1');
			$this->db->where('lms_cos_enroll.cosen_status_sub', '1');
			$this->db->where('lms_cos_enroll.cosen_firsttime!=', '0000-00-00 00:00:00');
			$this->db->where('lms_cos_enroll.cosen_finishtime!=', '0000-00-00 00:00:00');
			$this->db->from('lms_cos_enroll');
			$this->db->select('lms_cos_enroll.cosen_id');
			$query = $this->db->get();
			return $query->num_rows();
		} else {
			$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
			$this->db->where('lms_cos_enroll.cosen_status', '1');
			$this->db->where('lms_cos_enroll.cosen_firsttime', '0000-00-00 00:00:00');
			$this->db->from('lms_cos_enroll');
			$this->db->select('lms_cos_enroll.cosen_id');
			$query = $this->db->get();
			return $query->num_rows();
		}
	}
	public function create_company($data)
	{
		date_default_timezone_set("Asia/Bangkok");
		$data_typecos = array(
			'0' => array('tc_name_th' => 'E-learning', 'tc_name_en' => 'E-learning', 'tc_lesson' => '1', 'tc_pretest' => '1', 'tc_questionnaire' => '1', 'tc_qrcode' => '0', 'tc_student_enroll' => '1', 'tc_courselearner' => '1'),
			'1' => array('tc_name_th' => 'ห้องเรียน', 'tc_name_en' => 'Classroom', 'tc_lesson' => '0', 'tc_pretest' => '0', 'tc_questionnaire' => '0', 'tc_qrcode' => '1', 'tc_student_enroll' => '0', 'tc_courselearner' => '1'),
		);
		$this->db->from('lms_company');
		$this->db->where('com_code', $data['com_code']);
		$this->db->where('com_name_th', $data['com_name_th']);
		$this->db->where('com_name_eng', $data['com_name_eng']);
		$this->db->where('com_status', '1');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_company', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				foreach ($data_typecos as $key => $value) {
					$value['com_id'] = $id;
					$value['tc_createdate'] = date('Y-m-d H:i');
					$value['tc_modifeddate'] = date('Y-m-d H:i');
					$this->db->insert('lms_typecos', $value);
				}
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}

	public function chk_permission($mu_path, $field)
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");

		$this->db->from('lms_role_usp');
		$this->db->join('lms_menu', 'lms_role_usp.mu_id = lms_menu.mu_id');
		$this->db->where($field, '1');
		$this->db->where('lms_menu.mu_path', $mu_path);
		$this->db->where('u_id', @$user['u_id']);
		$query = $this->db->get();
		$fetch = $query->row_array();
		if (countArray($fetch) > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	public function chk_permission_page()
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");

		$this->db->from('lms_menu');
		/*if($user['com_admin']=="com_associated"){
            $this->db->where('mu_customer', '1');
          }*/
		if ($this->isMobile()) {
			$where = "mu_id in (SELECT mu_id FROM lms_menu where mu_path NOT LIKE '%managecourse%' and mu_path NOT IN ('quiz/create_template','certificate/certificateall','quiz/create_template','questionnaire/create','learning_system','survey/list_survey','manage_courses'))";
			$this->db->where($where);
		}
		$query = $this->db->get();
		$fetch = $query->result_array();
		$arr = array();
		foreach ($fetch as $key => $value) {
			$val_chk = $this->chk_permission($value['mu_path'], 'ru_view');
			if ($val_chk == "0") {
				unset($fetch[$key]);
			} else {
				array_push($arr, $value['mu_path']);
			}
		}

		return $arr;
	}

	public function update_company($data, $com_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('com_id', $com_id);
		$this->db->update('lms_company', $data);
		return "2";
	}
	public function delete_company($com_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('com_id', $com_id);
		$this->db->delete('lms_company');
		return "2";
	}

	public function create_department($data)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_depart');
		$this->db->where('dep_name_th', $data['dep_name_th']);
		$this->db->where('dep_name_en', $data['dep_name_en']);
		$this->db->where('com_id', $data['com_id']);
		$this->db->where('dep_status', '1');
		$this->db->where('dep_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$data['dep_createdate'] = date("Y-m-d H:i");
			$this->db->insert('lms_depart', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}

	public function update_department($data, $dep_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('dep_id', $dep_id);
		$this->db->update('lms_depart', $data);
		return "2";
	}
	public function delete_department($dep_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('dep_id', $dep_id);
		$this->db->delete('lms_depart');
		return "2";
	}

	public function create_groupuser($data, $fd_id)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_usp_gp');
		$this->db->where('ug_name_th', $data['ug_name_th']);
		$this->db->where('ug_name_en', $data['ug_name_en']);
		$this->db->where('ug_for', $data['ug_for']);
		$this->db->where('ug_status', '1');
		$this->db->where('ug_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$data['ug_createdate'] = date("Y-m-d H:i");
			$this->db->insert('lms_usp_gp', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				if (countArray($fd_id) > 0) {
					for ($i = 0; $i < countArray($fd_id); $i++) {
						$arr_insert = array(
							'ug_id' => $id,
							'fd_id' => $fd_id[$i],
						);
						$this->db->insert('lms_role_fd', $arr_insert);
					}
				}
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}



	public function fetch_data_log($sday, $eday, $com_id, $length, $start, $order, $search)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

		$arrMonthThaiTextShort = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย", "ธ.ค.");
		$arrMonthThaiTextFull = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$this->load->model('Log_model', 'lg', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->lg->loadDB();
		$sqlDate = array();
		//date_default_timezone_set("Asia/Bangkok");

		$var_and = "lms_emp.emp_isDelete='0'";
		$var_and .= " and lms_emp.com_id='" . $com_id . "'";
		if (($sday != "" && $eday != "")) {
			$var_and .= " and (lms_lg.log_time between '" . $sday . "' and '" . $eday . "')";
		}
		$col = 0;
		$dir = "";
		if (!empty($order)) {
			foreach ($order as $o) {
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}

		if ($dir != "asc" && $dir != "desc") {
			$dir = "desc";
		}
		$valid_columns = array(
			0 => 'lms_lg.emp_id',
			1 => 'lms_lg.emp_id',
			2 => 'lms_lg.emp_id',
			3 => 'lms_lg.ip',
			4 => 'lms_lg.device',
			5 => 'lms_lg.massage',
			6 => 'lms_lg.log_time',
			7 => 'lms_lg.log_time',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		if ($order != null) {
			$this->db->order_by($order, $dir);
		}
		if ($search != "") {
			$var_and .= "(lms_emp.emp_c like '%" . $search . "%' or lms_emp.fullname_th like '%" . $search . "%' or lms_emp.fullname_en like '%" . $search . "%' or lms_usp_gp.ug_name_th like '%" . $search . "%' or lms_usp_gp.ug_name_en like '%" . $search . "%' or lms_depart.dep_name_th like '%" . $search . "%' or lms_depart.dep_name_en like '%" . $search . "%' or lms_lg.massage like '%" . $search . "%')";
		}
		$this->db->where($var_and);
		$this->db->join('lms_emp', 'lms_lg.emp_id = lms_emp.emp_id');
		$this->db->join('lms_usp', 'lms_usp.emp_id = lms_emp.emp_id');
		$this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', 'LEFT');
		$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
		$this->db->from("lms_lg");
		/* if($length>=0){
            $this->db->limit($length,$start);
          }*/
		$query = $this->db->get();
		$fetch = $query->result_array();

		//$fetch = $this->func_query->query_result("tbl_customer","tbl_branch","tbl_branch.b_id = tbl_customer.b_id","",$var_and,"cus_id DESC");
		// $num = $start + 1;
		$count = 0;
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$output = array();
			$string_msg = "";
			$fetch_usp =
				/*$pos = strpos($value['massage'], 'logged in website');
              if($pos === false){
                $pos = strpos($value['massage'], 'logged in fail');
                if($pos){
                  $string_msg = "logged in fail";
                }
              }else{
                $string_msg = "logged in website";
              }*/
				$string_msg = $value['massage'];
			$output['0'] = $value['emp_c'];
			$fullname = "";
			$fullname = $lang == "thai" ? $value['fullname_th'] : $value['fullname_en'];
			$ug_name = $lang == "thai" ? $value['ug_name_th'] : $value['ug_name_en'];
			$dep_name = $lang == "thai" ? $value['dep_name_th'] : $value['dep_name_en'];
			$output['1'] = $fullname;
			$output['2'] = $ug_name;
			$output['3'] = $dep_name;
			$output['4'] = $value['ip'];
			$output['5'] = $value['device'];
			$output['6'] = $string_msg;
			if ($lang == "thai") {
				$output['7'] = date('d/m/', strtotime($value['log_time'])) . (date('Y', strtotime($value['log_time'])) + 543);
			} else {
				$output['7'] = date('d/m/Y', strtotime($value['log_time']));
			}
			$output['8'] = date('H:i', strtotime($value['log_time']));
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function create_emp($data)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_emp');
		$this->db->where('emp_c', $data['emp_c']);
		// $this->db->where('com_id',$data['com_id']);
		$this->db->where('emp_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_emp', $data);
			$id = $this->db->insert_id();
			return $id;
		} else {
			return "0";
		}
	}
	public function update_emp($data, $emp_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('emp_id', $emp_id);
		$this->db->update('lms_emp', $data);
		return "2";
	}
	public function rechk_role($u_id, $ug_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->from('lms_role_usp');
		$this->db->where('u_id', $u_id);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->from('lms_role_gp');
			$this->db->where('ug_id', $ug_id);
			$query = $this->db->get();
			$result_ques = $query->result_array();
			$num = 1;
			foreach ($result_ques as $key => $value) {
				$data = array(
					'u_id' => $u_id,
					'mu_id' => $value['mu_id'],
					'ru_view' => $value['rgu_view'],
					'ru_add' => $value['rgu_add'],
					'ru_edit' => $value['rgu_edit'],
					'ru_del' => $value['rgu_del'],
					'ru_print' => $value['rgu_print']
				);
				$this->db->insert('lms_role_usp', $data);
			}
		} else {
			$this->db->from('lms_usp');
			$this->db->where('u_id', $u_id);
			$this->db->where('ug_id', $ug_id);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$this->db->where('u_id', $u_id);
				$this->db->delete('lms_role_usp');

				$this->db->from('lms_role_gp');
				$this->db->where('ug_id', $ug_id);
				$query = $this->db->get();
				$result_ques = $query->result_array();
				$num = 1;
				foreach ($result_ques as $key => $value) {
					$data = array(
						'u_id' => $u_id,
						'mu_id' => $value['mu_id'],
						'ru_view' => $value['rgu_view'],
						'ru_add' => $value['rgu_add'],
						'ru_edit' => $value['rgu_edit'],
						'ru_del' => $value['rgu_del'],
						'ru_print' => $value['rgu_print']
					);
					$this->db->insert('lms_role_usp', $data);
				}
			}
		}
	}
	public function chk_company($dep_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->from('lms_depart');
		$this->db->where('dep_id', $dep_id);
		$query = $this->db->get();
		$fetch = $query->row_array();
		return $fetch['com_id'];
	}
	public function create_user($data)
	{
		date_default_timezone_set("Asia/Bangkok");
		$com_id = $this->chk_company($data['dep_id']);
		$this->db->from('lms_usp');
		//$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
		//$this->db->where('lms_depart.com_id', $com_id);
		$this->db->where('lms_usp.useri', $data['useri']);
		$this->db->where('u_isDelete', '0');
		// $this->db->where('lms_usp.emp_id', $data['emp_id']);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_usp', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				$this->rechk_role($id, $data['ug_id']);
				return "2";
			} else {
				return "3";
			}
		} else {
			$this->db->where('emp_id', $data['emp_id']);
			$this->db->delete('lms_emp');
			return "1";
		}
	}
	public function update_user($data, $u_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('u_id', $u_id);
		$this->db->update('lms_usp', $data);
		$this->rechk_role($u_id, $data['ug_id']);
		return "2";
	}

	public function chkbox_user($data_chk)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_role_usp');
		$this->db->where('u_id', $data_chk['u_idonrole_ug']);
		$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$data = array(
				'u_id' => $data_chk['u_idonrole_ug'],
				'mu_id' => $data_chk['mu_idonrole_ug'],
				$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
			);
			$this->db->insert('lms_role_usp', $data);
		} else {
			$data = array(
				$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
			);
			$this->db->where('u_id', $data_chk['u_idonrole_ug']);
			$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
			$this->db->update('lms_role_usp', $data);
		}
	}
	public function insert_arr($name_table, $arr)
	{
		$this->db->insert($name_table, $arr);
	}
	public function arr_menu_query()
	{
		$this->db->from('lms_menu');
		$this->db->order_by('mu_num', 'ASC');
		$query = $this->db->get();
		$row = $query->result();
		$result_ques = $query->result_array();
		return $result_ques;
	}
	public function chkbox_col_user($data_chk)
	{
		$this->db->from('lms_menu');
		$this->db->order_by('mu_num', 'ASC');
		$query = $this->db->get();
		$row = $query->result();
		$result_ques = $query->result_array();
		$num = 1;
		foreach ($result_ques as $key => $value) {
			$this->db->from('lms_role_usp');
			$this->db->where('u_id', $data_chk['u_idonrole_ug']);
			$this->db->where('mu_id', $value['mu_id']);
			$query = $this->db->get();
			if ($query->num_rows() == 0) {
				$data = array(
					'u_id' => $data_chk['u_idonrole_ug'],
					'mu_id' => $value['mu_id'],
					$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
				);
				$this->db->insert('lms_role_usp', $data);
			} else {
				$data = array(
					$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
				);
				$this->db->where('u_id', $data_chk['u_idonrole_ug']);
				$this->db->where('mu_id', $value['mu_id']);
				$this->db->update('lms_role_usp', $data);
			}
		}
	}
	public function chkbox_col_groupuser($data_chk)
	{
		$allowed_fields = array('rgu_view', 'rgu_add', 'rgu_edit', 'rgu_del', 'rgu_print');
		$field = isset($data_chk['field_sql_ug']) ? $data_chk['field_sql_ug'] : '';
		$ug_id = isset($data_chk['ug_idonrole_ug']) ? intval($data_chk['ug_idonrole_ug']) : 0;
		$permission_value = isset($data_chk['value_chk_ug']) && intval($data_chk['value_chk_ug']) === 1 ? 1 : 0;
		$menu_ids = isset($data_chk['mu_ids_ug']) ? array_values(array_unique(array_filter(array_map('intval', (array) $data_chk['mu_ids_ug'])))) : array();

		if (!in_array($field, $allowed_fields, true) || $ug_id <= 0 || count($menu_ids) === 0) {
			return json_encode(array('success' => false));
		}

		$this->db->from('lms_menu');
		$this->db->where_in('mu_id', $menu_ids);
		$this->db->order_by('mu_num', 'ASC');
		$query = $this->db->get();
		$row = $query->result();
		$result_ques = $query->result_array();
		$num = 1;
		foreach ($result_ques as $key => $value) {
			$this->db->from('lms_role_gp');
			$this->db->where('ug_id', $ug_id);
			$this->db->where('mu_id', $value['mu_id']);
			$query = $this->db->get();
			if ($query->num_rows() == 0) {
				$data = array(
					'ug_id' => $ug_id,
					'mu_id' => $value['mu_id'],
					$field => $permission_value
				);
				$this->db->insert('lms_role_gp', $data);

				$this->db->where('ug_id', $ug_id);
				$this->db->from('lms_usp');
				$query_usp = $this->db->get();
				$num_usp = $query_usp->num_rows();
				if ($num_usp > 0) {
					$fetch_usp = $query_usp->result_array();
					foreach ($fetch_usp as $key_usp => $value_usp) {
						$field_usp = str_replace("g", "", $field);
						$this->db->where('u_id', $value_usp['u_id']);
						$this->db->where('mu_id', $value['mu_id']);
						$this->db->from('lms_role_usp');
						$query = $this->db->get();
						$num_chk = $query->num_rows();
						if ($num_chk > 0) {
							$data_usp = array(
								$field_usp => $permission_value
							);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $value['mu_id']);
							$this->db->update('lms_role_usp', $data_usp);
						} else {
							$data_usp = array(
								'u_id' => $value_usp['u_id'],
								'mu_id' => $value['mu_id'],
								$field_usp => $permission_value
							);
							$this->db->insert('lms_role_usp', $data_usp);
						}
					}
				}
			} else {
				$data = array(
					$field => $permission_value
				);
				$this->db->where('ug_id', $ug_id);
				$this->db->where('mu_id', $value['mu_id']);
				$this->db->update('lms_role_gp', $data);

				$this->db->where('ug_id', $ug_id);
				$this->db->from('lms_usp');
				$query_usp = $this->db->get();
				$num_usp = $query_usp->num_rows();
				if ($num_usp > 0) {
					$fetch_usp = $query_usp->result_array();
					foreach ($fetch_usp as $key_usp => $value_usp) {
						$field_usp = str_replace("g", "", $field);
						$this->db->where('u_id', $value_usp['u_id']);
						$this->db->where('mu_id', $value['mu_id']);
						$this->db->from('lms_role_usp');
						$query = $this->db->get();
						$num_chk = $query->num_rows();
						if ($num_chk > 0) {
							$data_usp = array(
								$field_usp => $permission_value
							);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $value['mu_id']);
							$this->db->update('lms_role_usp', $data_usp);
						} else {
							$data_usp = array(
								'u_id' => $value_usp['u_id'],
								'mu_id' => $value['mu_id'],
								$field_usp => $permission_value
							);
							$this->db->insert('lms_role_usp', $data_usp);
						}
					}
				}
			}
		}
		return json_encode(array('success' => true));
	}
	public function chkbox_groupuser($data_chk)
	{
		date_default_timezone_set("Asia/Bangkok");
		$arr_field = array('rgu_view', 'rgu_add', 'rgu_edit', 'rgu_del', 'rgu_print');
		$this->db->from('lms_role_gp');
		$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
		$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			if ($data_chk['field_sql_ug'] == "chkrowall") {
				for ($i = 0; $i < 5; $i++) {
					$data = array();
					$data['ug_id'] = $data_chk['ug_idonrole_ug'];
					$data['mu_id'] = $data_chk['mu_idonrole_ug'];;
					$data[$arr_field[$i]] = $data_chk['value_chk_ug'];
					$this->insert_arr('lms_role_gp', $data);

					$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
					$this->db->from('lms_usp');
					$query_usp = $this->db->get();
					$num_usp = $query_usp->num_rows();
					if ($num_usp > 0) {
						$fetch_usp = $query_usp->result_array();
						foreach ($fetch_usp as $key_usp => $value_usp) {
							$field_usp = str_replace("g", "", $arr_field[$i]);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
							$this->db->from('lms_role_usp');
							$query = $this->db->get();
							$num_chk = $query->num_rows();
							if ($num_chk > 0) {
								$data_usp = array(
									$field_usp => $data_chk['value_chk_ug']
								);
								$this->db->where('u_id', $value_usp['u_id']);
								$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
								$this->db->update('lms_role_usp', $data_usp);
							} else {
								$data_usp = array(
									'u_id' => $value_usp['u_id'],
									'mu_id' => $data_chk['mu_idonrole_ug'],
									$field_usp => $data_chk['value_chk_ug']
								);
								$this->db->insert('lms_role_usp', $data_usp);
							}
						}
					}
				}
			} else {
				$data = array(
					'ug_id' => $data_chk['ug_idonrole_ug'],
					'mu_id' => $data_chk['mu_idonrole_ug'],
					$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
				);
				$this->db->insert('lms_role_gp', $data);

				$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
				$this->db->from('lms_usp');
				$query_usp = $this->db->get();
				$num_usp = $query_usp->num_rows();
				if ($num_usp > 0) {
					$fetch_usp = $query_usp->result_array();
					foreach ($fetch_usp as $key_usp => $value_usp) {
						$field_usp = str_replace("g", "", $data_chk['field_sql_ug']);
						$this->db->where('u_id', $value_usp['u_id']);
						$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
						$this->db->from('lms_role_usp');
						$query = $this->db->get();
						$num_chk = $query->num_rows();
						if ($num_chk > 0) {
							$data_usp = array(
								$field_usp => $data_chk['value_chk_ug']
							);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
							$this->db->update('lms_role_usp', $data_usp);
						} else {
							$data_usp = array(
								'u_id' => $value_usp['u_id'],
								'mu_id' => $data_chk['mu_idonrole_ug'],
								$field_usp => $data_chk['value_chk_ug']
							);
							$this->db->insert('lms_role_usp', $data_usp);
						}
					}
				}
			}
		} else {
			if ($data_chk['field_sql_ug'] == "chkrowall") {
				for ($i = 0; $i < 5; $i++) {
					$data = array(
						$arr_field[$i] => $data_chk['value_chk_ug']
					);
					$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
					$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
					$this->db->update('lms_role_gp', $data);



					$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
					$this->db->from('lms_usp');
					$query_usp = $this->db->get();
					$num_usp = $query_usp->num_rows();
					if ($num_usp > 0) {
						$fetch_usp = $query_usp->result_array();
						foreach ($fetch_usp as $key_usp => $value_usp) {
							$field_usp = str_replace("g", "", $arr_field[$i]);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
							$this->db->from('lms_role_usp');
							$query = $this->db->get();
							$num_chk = $query->num_rows();
							if ($num_chk > 0) {
								$data_usp = array(
									$field_usp => $data_chk['value_chk_ug']
								);
								$this->db->where('u_id', $value_usp['u_id']);
								$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
								$this->db->update('lms_role_usp', $data_usp);
							} else {
								$data_usp = array(
									'u_id' => $value_usp['u_id'],
									'mu_id' => $data_chk['mu_idonrole_ug'],
									$field_usp => $data_chk['value_chk_ug']
								);
								$this->db->insert('lms_role_usp', $data_usp);
							}
						}
					}
				}
			} else {
				$data = array(
					$data_chk['field_sql_ug'] => $data_chk['value_chk_ug']
				);
				$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
				$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
				$this->db->update('lms_role_gp', $data);


				$this->db->where('ug_id', $data_chk['ug_idonrole_ug']);
				$this->db->from('lms_usp');
				$query_usp = $this->db->get();
				$num_usp = $query_usp->num_rows();
				if ($num_usp > 0) {
					$fetch_usp = $query_usp->result_array();
					foreach ($fetch_usp as $key_usp => $value_usp) {
						$field_usp = str_replace("g", "", $data_chk['field_sql_ug']);
						$this->db->where('u_id', $value_usp['u_id']);
						$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
						$this->db->from('lms_role_usp');
						$query = $this->db->get();
						$num_chk = $query->num_rows();
						if ($num_chk > 0) {
							$data_usp = array(
								$field_usp => $data_chk['value_chk_ug']
							);
							$this->db->where('u_id', $value_usp['u_id']);
							$this->db->where('mu_id', $data_chk['mu_idonrole_ug']);
							$this->db->update('lms_role_usp', $data_usp);
						} else {
							$data_usp = array(
								'u_id' => $value_usp['u_id'],
								'mu_id' => $data_chk['mu_idonrole_ug'],
								$field_usp => $data_chk['value_chk_ug']
							);
							$this->db->insert('lms_role_usp', $data_usp);
						}
					}
				}
			}
		}
	}

	public function chkdataRoleUsergroup($ug_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata("user");
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$num = 1;
		$where = 'lms_menu.mu_parent="0" and lms_menu.mu_status="1" and lms_menu.mu_path !="dashboard"';
		if ($user['com_admin'] == "com_associated") {
			$where = 'mu_customer="1"';
		}
		$fetch_user_gp = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_id="' . $ug_id . '"');
		$result_menu = $this->func_query->query_result('lms_menu', '', '', '', $where, 'mu_num ASC');
		$num_ques = 0;
		foreach ($result_menu as $key => $value) {
			$li_arr_sub = $this->checkmenu_sub($value['mu_id']);

			if (countArray($li_arr_sub) > 0) {
				$num_secord = 1;
				foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {
					if ($value_li_sub['mu_id'] == $value['mu_id']) {
						unset($result_menu[$key]);
					}
				}
			}
		}
		foreach ($result_menu as $key => $value) {
			$chkenable = 0;
			$chkadd = 0;
			$chkedit = 0;
			$chkdel = 0;
			$chkprint = 0;
			$this->db->from('lms_role_gp');
			$this->db->where('ug_id', $ug_id);
			$this->db->where('mu_id', $value['mu_id']);
			$query_chk = $this->db->get();
			if ($query_chk->num_rows() > 0) {

				$fetch_chk = $query_chk->row_array();
				$chkenable = intval($fetch_chk['rgu_view']);
				$chkadd = intval($fetch_chk['rgu_add']);
				$chkedit = intval($fetch_chk['rgu_edit']);
				$chkdel = intval($fetch_chk['rgu_del']);
				$chkprint = intval($fetch_chk['rgu_print']);
			}
			echo '<tr>';
			echo '<td align="left" width="10%">' . $num . '</td>';
			if ($lang == "thai") {
				echo '<td align="left" width="30%">' . $value["mu_name_th"] . '</td>';
			} else if ($lang == "english") {
				echo '<td align="left" width="30%">' . $value["mu_name_en"] . '</td>';
			} else {
				echo '<td align="left" width="30%">' . $value["mu_name_jp"] . '</td>';
			}
			$li_arr_sub = $this->checkmenu_sub($value['mu_id']);
			if (countArray($li_arr_sub) == 0) {
				if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value['mu_id']; ?>" name="chkrowall_<?php echo $value['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value['mu_id']; ?>" name="chkrowall_<?php echo $value['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkrowall_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkenable == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value['mu_id']; ?>" name="chkenable_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value['mu_id']; ?>" name="chkenable_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkenable_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  }
				if ($chkadd == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value['mu_id']; ?>" name="chkadd_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value['mu_id']; ?>" name="chkadd_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkadd_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkedit == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value['mu_id']; ?>" name="chkedit_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value['mu_id']; ?>" name="chkedit_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkedit_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  }
				if ($chkdel == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value['mu_id']; ?>" name="chkdel_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value['mu_id']; ?>" name="chkdel_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkdel_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkprint == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value['mu_id']; ?>" name="chkprint_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value['mu_id']; ?>" name="chkprint_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkprint_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
					<?php  }
			} else {
				echo '<td colspan="6"></td>';
			}
			echo '</tr>';
			if (countArray($li_arr_sub) > 0) {
				$num_secord = 1;

				foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {
					$chkenable = 0;
					$chkadd = 0;
					$chkedit = 0;
					$chkdel = 0;
					$chkprint = 0;
					$this->db->from('lms_role_gp');
					$this->db->where('ug_id', $ug_id);
					$this->db->where('mu_id', $value_li_sub['mu_id']);
					$query_chk = $this->db->get();
					if ($query_chk->num_rows() > 0) {
						$fetch_chk = $query_chk->row_array();
						$chkenable = intval($fetch_chk['rgu_view']);
						$chkadd = intval($fetch_chk['rgu_add']);
						$chkedit = intval($fetch_chk['rgu_edit']);
						$chkdel = intval($fetch_chk['rgu_del']);
						$chkprint = intval($fetch_chk['rgu_print']);
					}
					echo '<tr>';
					echo '<td align="center" width="10%">' . $num . '.' . $num_secord . '</td>';
					if ($lang == "thai") {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_th"] . '</td>';
					} else if ($lang == "english") {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_en"] . '</td>';
					} else {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_jp"] . '</td>';
					}
					$li_arr_sub_b = $this->checkmenu_sub($value_li_sub['mu_id']);
					if (countArray($li_arr_sub_b) == 0) {
						if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkrowall_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkenable == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkenable_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  }
						if ($chkadd == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkadd_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkedit == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkedit_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  }
						if ($chkdel == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkdel_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkprint == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkprint_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
							<?php  }
					} else {
						echo '<td colspan="6"></td>';
					}
					echo '</tr>';
					if (countArray($li_arr_sub_b) > 0) {
						$num_three = 1;

						foreach ($li_arr_sub_b as $key_li_sub_b => $value_li_sub_b) {
							$chkenable = 0;
							$chkadd = 0;
							$chkedit = 0;
							$chkdel = 0;
							$chkprint = 0;
							$this->db->from('lms_role_gp');
							$this->db->where('ug_id', $ug_id);
							$this->db->where('mu_id', $value_li_sub_b['mu_id']);
							$query_chk = $this->db->get();
							if ($query_chk->num_rows() > 0) {

								$fetch_chk = $query_chk->row_array();
								$chkenable = intval($fetch_chk['rgu_view']);
								$chkadd = intval($fetch_chk['rgu_add']);
								$chkedit = intval($fetch_chk['rgu_edit']);
								$chkdel = intval($fetch_chk['rgu_del']);
								$chkprint = intval($fetch_chk['rgu_print']);
							}
							echo '<tr>';
							echo '<td align="right" width="10%">' . $num . '.' . $num_secord . '.' . $num_three . '</td>';
							if ($lang == "thai") {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_th"] . '</td>';
							} else if ($lang == "english") {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_en"] . '</td>';
							} else {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_jp"] . '</td>';
							}
							if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkenable == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  }
							if ($chkadd == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?>  chkcol_rgu_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkedit == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  }
							if ($chkdel == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkprint == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_rgu_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $ug_id; ?>")' value="1"><label for="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
					<?php  }
							$num_three++;
							$num_ques++;
						}
					}
					$num_secord++;
					$num_ques++;
				}
			}
			$num++;
			$num_ques++;
		}
		echo '<input type="hidden" id="count_menu" name="count_menu" value="' . $num_ques . '">';
	}

	public function chkdataRoleUser($u_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata("user");
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$where = 'lms_menu.mu_parent="0" and lms_menu.mu_status="1" and lms_menu.mu_path !="dashboard"';
		if ($user['com_admin'] == "com_associated") {
			$where = 'mu_customer="1"';
		}
		$fetch_user = $this->func_query->query_row('lms_usp', '', '', '', 'u_id="' . $u_id . '"');
		$result_menu = $this->func_query->query_result('lms_menu', '', '', '', $where, 'mu_num ASC');
		$arr_sub = array();
		foreach ($result_menu as $key => $value) {
			$num_chk = 0;
			$li_arr_sub = $this->checkmenu_sub($value['mu_id']);
			if (countArray($li_arr_sub) > 0) {
				foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {
					$li_arr_sub_b = $this->checkmenu_sub($value_li_sub['mu_id']);
					array_push($arr_sub, $value_li_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						foreach ($li_arr_sub_b as $key_sub_b => $value_sub_b) {
							array_push($arr_sub, $value_sub_b['mu_id']);
							$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value_sub_b['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
							if ($fetch_chk > 0) {
								array_push($arr_sub, $value_sub_b['mu_id']);
								$num_chk++;
							}
						}
					} else {
						$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value_li_sub['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
						if ($fetch_chk > 0) {
							$num_chk++;
						}
					}
				}
			} else {
				$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
				if ($fetch_chk > 0) {
					$num_chk++;
				}
			}
			if ($num_chk == 0) {
				unset($result_menu[$key]);
			}
		}
		foreach ($result_menu as $key => $value) {
			if (in_array($value['mu_id'], $arr_sub)) {
				unset($result_menu[$key]);
			}
		}
		$num_ques = 0;
		$num = 1;
		foreach ($result_menu as $key => $value) {
			$chkenable = 0;
			$chkadd = 0;
			$chkedit = 0;
			$chkdel = 0;
			$chkprint = 0;
			$this->db->from('lms_role_usp');
			$this->db->where('u_id', $u_id);
			$this->db->where('mu_id', $value['mu_id']);
			$query_chk = $this->db->get();
			if ($query_chk->num_rows() > 0) {

				$fetch_chk = $query_chk->row_array();
				$chkenable = intval($fetch_chk['ru_view']);
				$chkadd = intval($fetch_chk['ru_add']);
				$chkedit = intval($fetch_chk['ru_edit']);
				$chkdel = intval($fetch_chk['ru_del']);
				$chkprint = intval($fetch_chk['ru_print']);
			}
			echo '<tr>';
			echo '<td align="left" width="10%">' . $num . '</td>';
			if ($lang == "thai") {
				echo '<td align="left" width="30%">' . $value["mu_name_th"] . '</td>';
			} else if ($lang == "english") {
				echo '<td align="left" width="30%">' . $value["mu_name_en"] . '</td>';
			} else {
				echo '<td align="left" width="30%">' . $value["mu_name_jp"] . '</td>';
			}
			$li_arr_sub = $this->checkmenu_sub($value['mu_id']);
			if (countArray($li_arr_sub) == 0) {
				if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value['mu_id']; ?>" name="chkrowall_<?php echo $value['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value['mu_id']; ?>" name="chkrowall_<?php echo $value['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkrowall_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkenable == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value['mu_id']; ?>" name="chkenable_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value['mu_id']; ?>" name="chkenable_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkenable_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  }
				if ($chkadd == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value['mu_id']; ?>" name="chkadd_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value['mu_id']; ?>" name="chkadd_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkadd_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkedit == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value['mu_id']; ?>" name="chkedit_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value['mu_id']; ?>" name="chkedit_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkedit_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  }
				if ($chkdel == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value['mu_id']; ?>" name="chkdel_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value['mu_id']; ?>" name="chkdel_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkdel_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php }
				if ($chkprint == 1) { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value['mu_id']; ?>" name="chkprint_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
				<?php  } else { ?>
					<td align="center" width="10%">
						<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value['mu_id']; ?>" name="chkprint_<?php echo $value['mu_id']; ?>" class="chkrow_<?php echo $value['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkprint_<?php echo $value['mu_id']; ?>"></label></div>
					</td>
					<?php  }
			} else {
				echo '<td colspan="6"></td>';
			}
			echo '</tr>';
			if (countArray($li_arr_sub) > 0) {
				$num_secord = 1;

				foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {
					$num_chk = 0;
					$li_arr_sub_b = $this->checkmenu_sub($value_li_sub['mu_id']);
					if (countArray($li_arr_sub_b) > 0) {
						foreach ($li_arr_sub_b as $key_sub_b => $value_sub_b) {
							$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value_sub_b['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
							if ($fetch_chk > 0) {
								$num_chk++;
							}
						}
					} else {
						$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value_li_sub['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
						if ($fetch_chk > 0) {
							$num_chk++;
						}
					}
					if ($num_chk == 0) {
						unset($li_arr_sub[$key_li_sub]);
					}
				}
				foreach ($li_arr_sub as $key_li_sub => $value_li_sub) {
					$chkenable = 0;
					$chkadd = 0;
					$chkedit = 0;
					$chkdel = 0;
					$chkprint = 0;
					$this->db->from('lms_role_usp');
					$this->db->where('u_id', $u_id);
					$this->db->where('mu_id', $value_li_sub['mu_id']);
					$query_chk = $this->db->get();
					if ($query_chk->num_rows() > 0) {

						$fetch_chk = $query_chk->row_array();
						$chkenable = intval($fetch_chk['ru_view']);
						$chkadd = intval($fetch_chk['ru_add']);
						$chkedit = intval($fetch_chk['ru_edit']);
						$chkdel = intval($fetch_chk['ru_del']);
						$chkprint = intval($fetch_chk['ru_print']);
					}
					echo '<tr>';
					echo '<td align="center" width="10%">' . $num . '.' . $num_secord . '</td>';
					if ($lang == "thai") {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_th"] . '</td>';
					} else if ($lang == "english") {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_en"] . '</td>';
					} else {
						echo '<td align="left" width="30%">' . $value_li_sub["mu_name_jp"] . '</td>';
					}
					$li_arr_sub_b = $this->checkmenu_sub($value_li_sub['mu_id']);
					if (countArray($li_arr_sub_b) == 0) {
						if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkrowall_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkenable == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkenable_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  }
						if ($chkadd == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkadd_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkedit == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkedit_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  }
						if ($chkdel == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkdel_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php }
						if ($chkprint == 1) { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
						<?php  } else { ?>
							<td align="center" width="10%">
								<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkprint_<?php echo $value_li_sub['mu_id']; ?>"></label></div>
							</td>
							<?php  }
					} else {
						echo '<td colspan="6"></td>';
					}
					echo '</tr>';

					if (countArray($li_arr_sub_b) > 0) {
						$num_three = 1;


						foreach ($li_arr_sub_b as $key_li_sub_b => $value_li_sub_b) {
							$fetch_chk = $this->func_query->numrows('lms_role_gp', '', '', '', 'mu_id="' . $value_li_sub_b['mu_id'] . '" and ug_id="' . $fetch_user['ug_id'] . '" and rgu_view="1"');
							if ($fetch_chk == 0) {
								unset($li_arr_sub_b[$key_li_sub_b]);
							}
						}
						foreach ($li_arr_sub_b as $key_li_sub_b => $value_li_sub_b) {
							$chkenable = 0;
							$chkadd = 0;
							$chkedit = 0;
							$chkdel = 0;
							$chkprint = 0;
							$this->db->from('lms_role_usp');
							$this->db->where('u_id', $u_id);
							$this->db->where('mu_id', $value_li_sub_b['mu_id']);
							$query_chk = $this->db->get();
							if ($query_chk->num_rows() > 0) {

								$fetch_chk = $query_chk->row_array();
								$chkenable = intval($fetch_chk['ru_view']);
								$chkadd = intval($fetch_chk['ru_add']);
								$chkedit = intval($fetch_chk['ru_edit']);
								$chkdel = intval($fetch_chk['ru_del']);
								$chkprint = intval($fetch_chk['ru_print']);
							}
							echo '<tr>';
							echo '<td align="right" width="10%">' . $num . '.' . $num_secord . '.' . $num_three . '</td>';
							if ($lang == "thai") {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_th"] . '</td>';
							} else if ($lang == "english") {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_en"] . '</td>';
							} else {
								echo '<td align="left" width="30%">' . $value_li_sub_b["mu_name_jp"] . '</td>';
							}
							if ($chkenable == 1 && $chkadd == 1 && $chkedit == 1 && $chkdel == 1 && $chkprint == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>" onchange='chk_chkbox("chkrowall","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkrowall_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkenable == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_view" onchange='chk_chkbox("chkenable","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkenable_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  }
							if ($chkadd == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?>  chkcol_ru_add" onchange='chk_chkbox("chkadd","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkadd_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkedit == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_edit" onchange='chk_chkbox("chkedit","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkedit_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  }
							if ($chkdel == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_del" onchange='chk_chkbox("chkdel","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkdel_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php }
							if ($chkprint == 1) { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1" checked><label for="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
							<?php  } else { ?>
								<td align="center" width="10%">
									<div class="checkbox checkbox-success"><input type="checkbox" id="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" name="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>" class="chkrow_<?php echo $value_li_sub_b['mu_id']; ?> chkcol_ru_print" onchange='chk_chkbox("chkprint","<?php echo $value_li_sub_b['mu_id']; ?>","<?php echo $u_id; ?>")' value="1"><label for="chkprint_<?php echo $value_li_sub_b['mu_id']; ?>"></label></div>
								</td>
<?php  }
							echo '</tr>';
							$num_three++;
							$num_ques++;
						}
					}
					$num_secord++;
					$num_ques++;
				}
			}
			$num++;
			$num_ques++;
		}
		echo '<input type="hidden" id="count_menu" name="count_menu" value="' . $num_ques . '">';
	}
	public function update_groupuser($data, $ug_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('ug_id', $ug_id);
		$this->db->update('lms_usp_gp', $data);
		return "2";
	}
	public function delete_groupuser($ug_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('ug_id', $ug_id);
		$this->db->delete('lms_usp_gp');
		return "2";
	}
	public function query_data_onupdate($id, $datatable, $fieldname)
	{
		$user = $this->session->userdata('user');
		$this->db->from($datatable);
		if ($datatable == "lms_usp") {
			//$this->db->select('lms_usp.u_id, lms_emp.lang,lms_emp.emp_c,lms_emp.emp_id, lms_emp.prefix_th, lms_emp.fname_th, lms_emp.lname_th,lms_emp.fullname_th,lms_emp.fullname_en, lms_emp.prefix_en, lms_emp.fname_en, lms_emp.lname_en,lms_emp.gender,lms_emp.address_th,lms_emp.address_en,lms_emp.work_phone,lms_emp.phone,lms_emp.email,lms_emp.employ_date,lms_usp.useri, lms_usp_gp.ug_id, lms_usp_gp.ug_name_en,lms_usp_gp.ug_for,lms_usp.dep_id, lms_depart.dep_name_th,lms_depart.dep_name_en,lms_company.com_id, lms_company.com_name_th,lms_company.com_name_eng,lms_company.com_bgpic_user,lms_emp.status, lms_emp.lang,lms_emp.is_manager,lms_usp.login ,lms_usp.last_act,lms_usp.firsttime,lms_usp.expiredate,lms_usp.img_profile,lms_position.posi_id,lms_position.posi_name_th,lms_position.posi_name_en');
			$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
			$this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', 'left');
			$this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
			$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
			$this->db->join('lms_position', 'lms_usp.posi_id = lms_position.posi_id', 'left');
		} else if ($datatable == "lms_les") {
			$this->db->join('lms_cos', 'lms_les.cos_id = lms_cos.cos_id');
			if ($user['ug_name_en'] == "User") {
				$this->db->where('lms_cos.com_id', $user['com_id']);
			}
		}
		$this->db->where($fieldname, $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function query_multi_data_onupdate($id, $datatable, $fieldname)
	{
		$user = $this->session->userdata('user');
		$this->db->from($datatable);
		if ($datatable == "lms_usp") {
			//$this->db->select('lms_usp.u_id, lms_emp.lang,lms_emp.emp_c,lms_emp.emp_id, lms_emp.prefix_th, lms_emp.fname_th, lms_emp.lname_th,lms_emp.fullname_th,lms_emp.fullname_en, lms_emp.prefix_en, lms_emp.fname_en, lms_emp.lname_en,lms_emp.gender,lms_emp.address_th,lms_emp.address_en,lms_emp.work_phone,lms_emp.phone,lms_emp.email,lms_emp.employ_date,lms_usp.useri, lms_usp_gp.ug_id, lms_usp_gp.ug_name_en,lms_usp_gp.ug_for,lms_usp.dep_id, lms_depart.dep_name_th,lms_depart.dep_name_en,lms_company.com_id, lms_company.com_name_th,lms_company.com_name_eng,lms_company.com_bgpic_user,lms_emp.status, lms_emp.lang,lms_emp.is_manager,lms_usp.login ,lms_usp.last_act,lms_usp.firsttime,lms_usp.expiredate,lms_usp.img_profile,lms_position.posi_id,lms_position.posi_name_th,lms_position.posi_name_en');
			$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
			//$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
			$this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
			$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
			//$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
		} else if ($datatable == "lms_les") {
			$this->db->join('lms_cos', 'lms_les.cos_id = lms_cos.cos_id');
			if ($user['ug_name_en'] == "User") {
				$this->db->where('lms_cos.com_id', $user['com_id']);
			}
		}
		$this->db->where($fieldname, $id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function query_data_chkheadcol($ug_id)
	{
		$arr_field = array('rgu_view', 'rgu_add', 'rgu_edit', 'rgu_del', 'rgu_print');
		$this->db->from('lms_menu');
		$this->db->order_by('mu_num', 'ASC');
		$query = $this->db->get();
		$row = $query->result();
		$result_ques = $query->num_rows();

		$rgu_view = 0;
		$rgu_add = 0;
		$rgu_edit = 0;
		$rgu_del = 0;
		$rgu_print = 0;

		$this->db->from('lms_role_gp');
		$this->db->where('ug_id', $ug_id);
		$query = $this->db->get();
		$result_ques = $query->num_rows();
		$this->db->select('sum(rgu_view)as rgu_view,sum(rgu_add)as rgu_add,sum(rgu_edit)as rgu_edit,sum(rgu_del)as rgu_del,sum(rgu_print)as rgu_print');
		$this->db->from('lms_role_gp');
		$this->db->where('ug_id', $ug_id);
		$query = $this->db->get();
		$fetch = $query->row_array();
		$rgu_view = $fetch['rgu_view'];
		$rgu_add = $fetch['rgu_add'];
		$rgu_edit = $fetch['rgu_edit'];
		$rgu_del = $fetch['rgu_del'];
		$rgu_print = $fetch['rgu_print'];
		/*$rgu_view = $this->countrecordheadcol("rgu_view",$ug_id);
          $rgu_add = $this->countrecordheadcol("rgu_add",$ug_id);
          $rgu_edit = $this->countrecordheadcol("rgu_edit",$ug_id);
          $rgu_del = $this->countrecordheadcol("rgu_del",$ug_id);
          $rgu_print = $this->countrecordheadcol("rgu_print",$ug_id);*/
		$arr = array(
			'countmenu' => $result_ques,
			'rgu_view' => $rgu_view,
			'rgu_add' => $rgu_add,
			'rgu_edit' => $rgu_edit,
			'rgu_del' => $rgu_del,
			'rgu_print' => $rgu_print
		);
		return $arr;
	}

	public function getSVQ($sv_id, $val)
	{
		$this->db->select('lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
		$this->db->from('lms_survey_de');
		$this->db->join('lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id');
		$this->db->where('lms_survey_de.sv_id', $sv_id);
		$this->db->where('lms_qn_user_de.qnude_var', $val);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function query_data_chkheadcol_user($u_id)
	{
		$arr_field = array('ru_view', 'ru_add', 'ru_edit', 'ru_del', 'ru_print');
		$this->db->from('lms_menu');
		$this->db->order_by('mu_num', 'ASC');
		$query = $this->db->get();
		$row = $query->result();
		$result_ques = $query->num_rows();

		$ru_view = 0;
		$ru_add = 0;
		$ru_edit = 0;
		$ru_del = 0;
		$ru_print = 0;

		$ru_view = $this->countrecordheadcoluser("ru_view", $u_id);
		$ru_add = $this->countrecordheadcoluser("ru_add", $u_id);
		$ru_edit = $this->countrecordheadcoluser("ru_edit", $u_id);
		$ru_del = $this->countrecordheadcoluser("ru_del", $u_id);
		$ru_print = $this->countrecordheadcoluser("ru_print", $u_id);
		$arr = array(
			'countmenu' => $result_ques,
			'ru_view' => $ru_view,
			'ru_add' => $ru_add,
			'ru_edit' => $ru_edit,
			'ru_del' => $ru_del,
			'ru_print' => $ru_print
		);
		return $arr;
	}

	public function countrecordheadcol($field = "", $ug_id = "")
	{
		$this->db->from('lms_role_gp');
		$this->db->where($field, '1');
		$this->db->where('ug_id', $ug_id);
		$query = $this->db->get();
		$row = $query->num_rows();
		return $row;
	}
	public function countrecordheadcoluser($field = "", $ug_id = "")
	{
		$this->db->from('lms_role_usp');
		$this->db->where($field, '1');
		$this->db->where('u_id', $ug_id);
		$query = $this->db->get();
		$row = $query->num_rows();
		return $row;
	}

	public function countrecordcos_sort($com_id = "")
	{
		$this->db->from('lms_cos_sort');
		$this->db->join('lms_cos', 'lms_cos_sort.cos_id = lms_cos.cos_id');
		$this->db->where('lms_cos.com_id', $com_id);
		$query = $this->db->get();
		$row = $query->num_rows();
		return $row;
	}
	public function getCompany($wg_code = "")
	{
		$user = $this->session->userdata('user');
		$ar_return = array();
		$this->db->select('com_id,com_name_th,com_name_eng,com_admin');
		$this->db->distinct();
		if ($user['com_admin'] == "com_associated") {
			$this->db->where('com_id', $user['com_id']);
		} else {
			if ($user['ug_viewdata'] != "1") {
				$this->db->where('com_id', $user['com_id']);
			}
		}
		$this->db->where('com_status', '1');
		$this->db->where('com_isDelete', '0');
		$this->db->where('com_id!=', '2');
		$query = $this->db->get('lms_company');
		$ar_return = $query->result_array();
		return $ar_return;
	}

	public function getArrCompany() {
		$arrCompany = array();
		$this->db->where('com_isDelete', '0');
		$query = $this->db->get('lms_company');
		$fetchCom = $query->result_array();
		if (!empty($fetchCom)) {
			foreach ($fetchCom as $keyCom) {
				$arrCompany[$keyCom["com_id"]] = $keyCom["com_code"];
			}
		}
		return $arrCompany;
	}

	public function getCompanyAll()
	{
		$user = $this->session->userdata('user');
		$ar_return = array();
		$this->db->select('com_id,com_name_th,com_name_eng,com_admin');
		$this->db->distinct();
		if ($user['com_admin'] == "com_associated") {
			$this->db->where('com_id', $user['com_id']);
		} else {
			if ($user['ug_viewdata'] != "1") {
				$this->db->where('com_id', $user['com_id']);
			}
		}
		$this->db->where('com_status', '1');
		$this->db->where('com_isDelete', '0');
		$query = $this->db->get('lms_company');
		$ar_return = $query->result_array();
		return $ar_return;
	}

	public function getUser($useri, $lang)
	{
		$this->db->select('lms_usp.u_id,lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en,lms_usp.useri, lms_usp_gp.ug_id, lms_usp_gp.ug_name_th,lms_usp_gp.ug_name_en,lms_usp_gp.Is_admin,lms_usp_gp.ug_for,lms_depart.dep_id, lms_depart.dep_name_th,lms_depart.dep_name_en,lms_company.com_id, lms_company.com_name_th,lms_company.com_name_eng,lms_emp.status, lms_emp.lang,lms_emp.is_manager,lms_usp.login ,lms_usp.last_act,lms_usp.firsttime,lms_usp.expiredate,lms_usp.img_profile');
		$this->db->from('lms_usp');
		$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
		$this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id');
		$this->db->join('lms_company', 'lms_depart.com_id = lms_company.com_id');
		$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
		$this->db->where('lms_usp.useri', $useri);
		//$this->db->where('lang', $lang);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function checkdepartment($com_id)
	{
		$this->db->from('lms_depart');
		$this->db->where('lms_depart.dep_status', '1');
		$this->db->where('lms_depart.dep_isDelete', '0');
		$this->db->where('lms_depart.com_id', $com_id);
		//$this->db->where('lang', $lang);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function isMobile()
	{
		return isset($_SERVER["HTTP_USER_AGENT"]) && $_SERVER["HTTP_USER_AGENT"] != null ? preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]) : false;
	}

	public function checklesson($cos_id, $les_lang)
	{
		$this->db->from('lms_les');
		$this->db->where('lms_les.les_status', '1');
		$this->db->where('lms_les.les_isDelete', '0');
		$this->db->where('lms_les.cos_id', $cos_id);
		//$this->db->where('lms_les.les_lang', $les_lang);
		$this->db->order_by('les_sequences', 'ASC');
		//$this->db->where('lang', $lang);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function checkmenu()
	{
		$this->db->from('lms_menu');
		$this->db->where('lms_menu.mu_status', '1');
		$this->db->where('lms_menu.mu_parent', '0');
		$this->db->order_by('mu_num', 'ASC');
		//$this->db->where('lang', $lang);
		if ($this->isMobile()) {
			$where = "mu_id in (SELECT mu_id FROM lms_menu where mu_path NOT LIKE '%managecourse%' and mu_path NOT IN ('quiz/create_template','certificate/certificateall','quiz/create_template','questionnaire/create','learning_system','survey/list_survey','manage_courses'))";
			$this->db->where($where);
		}
		$query = $this->db->get();
		$fetch = $query->result_array();

		return $query->result_array();
	}
	public function get_namemenu($mu_path)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

		$this->load->model('Function_query_model', 'fn_query', FALSE);
		$this->fn_query->loadDB();
		$where_com = "mu_path = '" . $mu_path . "' and mu_status = '1'";
		$fetch = $this->fn_query->query_row("lms_menu", "", "", "", $where_com);
		if (isset($fetch['mu_name_th'])) {
			if ($lang == "thai") {
				return $fetch['mu_name_th'];
			} else if ($lang == "english") {
				return $fetch['mu_name_en'];
			} else {
				return $fetch['mu_name_jp'];
			}
		} else {
			return "";
		}
	}

	public function get_namemenu_sub($mu_path)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

		$this->load->model('Function_query_model', 'fn_query', FALSE);
		$this->fn_query->loadDB();
		$where_com = "mu_path = '" . $mu_path . "' and mu_status = '1'";
		$fetch = $this->fn_query->query_row("lms_menu", "", "", "", $where_com);
		if (isset($fetch['mu_parent']) && $fetch['mu_parent'] != "0") {
			$fetchParent = $this->fn_query->query_row("lms_menu", "", "", "", "mu_id=" . $fetch['mu_parent']);
			if (isset($fetchParent["mu_name_th"])) {
				if ($lang == "thai") {
					return $fetchParent['mu_name_th'];
				} else if ($lang == "english") {
					return $fetchParent['mu_name_en'];
				} else {
					return $fetchParent['mu_name_jp'];
				}
			} else {
				return "";
			}
		} else {
			return "";
		}
	}

	public function checkmenu_sub($mu_id)
	{
		$this->db->from('lms_menu');
		$this->db->where('lms_menu.mu_status', '1');
		$this->db->where('lms_menu.mu_parent', $mu_id);
		$this->db->order_by('mu_num', 'ASC');
		//$this->db->where('lang', $lang);
		if ($this->isMobile()) {
			$where = "mu_id in (SELECT mu_id FROM lms_menu where mu_path NOT LIKE '%managecourse%' and mu_path NOT IN ('quiz/create_template','certificate/certificateall','quiz/create_template','questionnaire/create','learning_system','survey/list_survey','manage_courses'))";
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function checkposition($dep_id)
	{
		$this->db->from('lms_position');
		$this->db->where('lms_position.posi_status', '1');
		$this->db->where('lms_position.posi_isDelete', '0');
		$this->db->where('lms_position.dep_id', $dep_id);
		//$this->db->where('lang', $lang);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function checkusergroup($com_id)
	{
		$this->db->from('lms_company');
		$this->db->where('lms_company.com_id', $com_id);
		$query = $this->db->get();
		$fetch = $query->row_array();

		if (isset($fetch['com_admin'])) {
			$this->db->from('lms_usp_gp');
			$this->db->where('lms_usp_gp.ug_status', '1');
			$this->db->where('lms_usp_gp.ug_isDelete', '0');
			$this->db->where('lms_usp_gp.ug_for', $fetch['com_admin']);
			//$this->db->where('lang', $lang);
			$query = $this->db->get();
			return $query->result_array();
		} else {
			return array();
		}
	}
	public function fetch_data_company()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$page = "manage/companydata";
		$arr_permission = $this->manage->chk_permission_page();
		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');
		$sess = $this->session->userdata("user");
		$where = '';
		if ($sess['ug_viewdata'] != "1") {
			$where = ' and com_id = "' . $sess['com_id'] . '"';
		}
		$fetch = $this->func_query->query_result('lms_company', '', '', '', 'lms_company.com_isDelete="0" and lms_company.com_id!="2"' . $where, 'com_id DESC');
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$import = '<button type="button" name="import_user" id="' . $value['com_id'] . '" title="' . label('import_user') . '" class="btn btn-success btn-xs import_user"><i class="mdi mdi-file-import"></i></button>';
			$banner = '<button type="button" name="bannerbtn" id="' . $value['com_id'] . '" title="' . label('banner') . '" class="btn btn-info btn-xs bannerbtn"><i class="mdi mdi-image-area"></i></button>';
			$update = '<button type="button" name="update" id="' . $value['com_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['com_id'] . '" class="btn btn-danger btn-xs delete" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$output = array();
			$com_name = $value['com_name_th'];
			if ($lang != "thai") {
				$com_name = $value['com_name_eng'];
			}
			$output['num'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['nickname'] = "<center>" . $value['com_code'] . "</center>";
			$output['com_name'] = $com_name;
			$output['com_admin'] = "<center>" . label($value['com_admin']) . "</center>";
			if ($lang == "thai") {
				$com_modifieddate = $value['com_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['com_modifieddate'])) . (date('Y', strtotime($value['com_modifieddate'])) + 543) . " " . date('H:i', strtotime($value['com_modifieddate'])) : "<center>-</center>";
			} else {
				$com_modifieddate = $value['com_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['com_modifieddate'])) : "<center>-</center>";
			}

			//$com_modifieddate = "";
			$com_modifieddateori = "";

			$com_modifieddateori = $value['com_modifieddate'] != "0000-00-00 00:00:00" ? $value['com_modifieddate'] : "";
			$arr_modified = array(
				'display' => $com_modifieddate,
				'timestamp' => strtotime($com_modifieddateori),
			);
			$output['m_updatedate'] = $arr_modified;

			if ($btn_update != "1") {
				$update = "";
			}
			// if ($btn_delete != "1") {
			// 	$delete = "";
			// }
			if ($sess['ug_id'] != "1") {
				$delete = "";
			}
			//$import.
			$output['buttonall'] = "<center>" . $banner ." ". $update ." ". $delete . "</center>";
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}
	public function fetch_data_conmsg()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$page = "setting/ManageECT";
		$arr_permission = $this->manage->chk_permission_page();
		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');

		$fetch = $this->func_query->query_result('lms_confirmmsg', '', '', '', 'lms_confirmmsg.conmsg_isDelete="0"', 'conmsg_id DESC');
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$update = '<button type="button" name="update" id="' . $value['conmsg_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['conmsg_id'] . '" class="btn btn-danger btn-xs delete" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$output = array();
			$output['1'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['2'] = $value['conmsg_title_eng'];
			$output['3'] = $value['conmsg_title_th'];
			$output['4'] = $value['conmsg_title_jp'];

			if ($value['conmsg_status'] == "1") {
				$output['5'] = "<center>" . label('open') . "</center>";
			} else {
				$output['5'] = "<center>" . label('close') . "</center>";
			}
			$output['6'] = date('d/m/Y H:i', strtotime($value['conmsg_modifieddate']));

			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			$output['0'] = "<center>" . $update ." ". $delete . "</center>";
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}
	public function fetch_data_department()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$page = "manage/departmentdata";
		$arr_permission = $this->manage->chk_permission_page();
		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');

		$sess = $this->session->userdata("user");
		$this->db->from('lms_depart');
		$this->db->join('lms_company', 'lms_depart.com_id = lms_company.com_id');
		$this->db->where('lms_depart.dep_status', '1');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->where('lms_depart.dep_isDelete', '0');
		$this->db->where('lms_company.com_status', '1');
		$this->db->where('lms_company.com_id!=', '2');
		if ($sess['ug_id'] != "1") {
			$this->db->where('lms_depart.com_id', $sess['com_id']);
		}
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$position = '<button type="button" name="add_position" id="' . $value['dep_id'] . '" title="' . label('position') . '" class="btn btn-info btn-xs add_position"><i class="mdi mdi-account-plus"></i></button>';
			$update = '<button type="button" name="update" id="' . $value['dep_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['dep_id'] . '" class="btn btn-danger btn-xs delete" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$output = array();
			$output['num'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['dep_name_en'] = $value['dep_name_en'];
			$output['dep_name_th'] = $value['dep_name_th'];
			$output['company'] = "<center>" . $value['com_code'] . "</center>";
			//if($lang=="thai"){ $output['4'] = $value['com_name_th']; }else{ $output['4'] = $value['com_name_eng']; }

			if ($lang == "thai") {
				$dep_modifieddate = $value['dep_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['dep_modifieddate'])) . (date('Y', strtotime($value['dep_modifieddate'])) + 543) . " " . date('H:i', strtotime($value['dep_modifieddate'])) : "<center>-</center>";
			} else {
				$dep_modifieddate = $value['dep_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['dep_modifieddate'])) : "<center>-</center>";
			}

			//$dep_modifieddate = "";
			$dep_modifieddateori = "";

			$dep_modifieddateori = $value['dep_modifieddate'] != "0000-00-00 00:00:00" ? $value['dep_modifieddate'] : "";
			$arr_modified = array(
				'display' => $dep_modifieddate,
				'timestamp' => strtotime($dep_modifieddateori),
			);
			$output['dep_modified'] = $arr_modified;

			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			$output['buttonall'] = "<center>" . $position ." ". $update ." ". $delete . "</center>";
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}
	public function fetch_data_groupuser()
	{
		$sess = $this->session->userdata("user");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$page = "manage/groupuserdata";
		$arr_permission = $this->manage->chk_permission_page();
		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');
		$this->db->from('lms_usp_gp');
		$this->db->where('lms_usp_gp.ug_isDelete', '0');
		if ($sess['com_admin'] == "com_associated") {
			$this->db->where('lms_usp_gp.ug_for', 'com_associated');
		}
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$license = '<button type="button" name="license" id="' . $value['ug_id'] . '" title="' . label('m_permission_ug') . '" class="btn btn-info btn-xs license"><i class="mdi mdi-account-key"></i></button>';
			$update = '<button type="button" name="update" id="' . $value['ug_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['ug_id'] . '" class="btn btn-danger btn-xs delete" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$output = array();
			$output['1'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['2'] = $value['ug_name_th'];
			$output['3'] = $value['ug_name_en'];
			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			if ($sess['u_id'] != "1") {
				$delete = "";
			}
			if ($sess['ug_for'] == "com_central") {
				$output['4'] = "<center>" . label($value['ug_for']) . "</center>";

				if ($lang == "thai") {
					$output['5'] = $value['ug_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['ug_modifieddate'])) . (date('Y', strtotime($value['ug_modifieddate'])) + 543) . " " . date('H:i', strtotime($value['ug_modifieddate'])) : "<center>-</center>";
				} else {
					$output['5'] = $value['ug_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['ug_modifieddate'])) : "<center>-</center>";
				}
				$output['0'] = "<center>" . $license ." ". $update ." ". $delete . "</center>";
			} else {
				if ($lang == "thai") {
					$output['4'] = $value['ug_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['ug_modifieddate'])) . (date('Y', strtotime($value['ug_modifieddate'])) + 543) . " " . date('H:i', strtotime($value['ug_modifieddate'])) : "<center>-</center>";
				} else {
					$output['4'] = $value['ug_modifieddate'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['ug_modifieddate'])) : "<center>-</center>";
				}
				$output['0'] = "<center>" . $license ." ". $update ." ". $delete . "</center>";
			}
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_data_userenroll($emp_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();

		$fetch_enroll = $this->func_query->query_result('lms_cos_enroll', 'lms_cos', 'lms_cos.cos_id = lms_cos_enroll.cos_id', '', 'lms_cos_enroll.emp_id = "' . $emp_id . '"');
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch_enroll as $key => $value) {
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
			$regencert = '<button type="button" name="regencert" id="' . $value['cos_id'] . '" data-empid="' . $emp_id . '" class="btn btn-success btn-xs regencert" title="Regenarate certificate"><i class="mdi mdi-refresh"></i></button>';

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
			$downloadcert = "";
			if ($value['cosen_status_sub'] == "0") {
				$status_learner = label('not_start');
			} else if ($value['cosen_status_sub'] == "1") {
				$status_learner = label('r_pass');
			} else if ($value['cosen_status_sub'] == "2") {
				/*if($value['cosen_firsttime']=="0000-00-00 00:00:00"){
                  $output['2'] = label('not_start');
                }else{*/
				$status_learner = label('inProgress');
				//}
			} else {
				$status_learner = label('not_start');
			}
			if ($status_learner != label('r_pass')) {
				$regencert = "-";
			} else {
				$fetch_bad = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $value['cos_id'] . '"');
				$fetch_cug = $this->func_query->query_row('lms_cug', '', '', '', 'course_id="' . $value['cos_id'] . '"');
				if (countArray($fetch_bad) > 0) {
					$score_pass = 0;
					if ($fetch_bad['badges_condition'] == "P") {
						$score_pass = floatval($fetch_cug['mina']);
					} else {
						if ($fetch_bad['badges_condition'] == "A") {
							$score_pass = floatval($fetch_cug['mina']);
						} else if ($fetch_bad['badges_condition'] == "B") {
							$score_pass = floatval($fetch_cug['minb']);
						} else if ($fetch_bad['badges_condition'] == "C") {
							$score_pass = floatval($fetch_cug['minc']);
						} else if ($fetch_bad['badges_condition'] == "D") {
							$score_pass = floatval($fetch_cug['mind']);
						} else {
							$score_pass = 0;
						}
					}
					$cosen_score_per = round($value['cosen_score_per']);
					if ($cosen_score_per < $score_pass) {
						$regencert = "-";
					} else {
						$fetch_cert = $this->func_query->query_row('lms_certificate', '', '', '', 'cos_id = "' . $value['cos_id'] . '" and emp_id = "' . $emp_id . '"');
						if (countArray($fetch_cert) > 0 && is_file(ROOT_DIR . "uploads/certificate/" . $fetch_cert['cert_file'])) {
							$downloadcert = '<a class="btn btn-warning btn-xs" href="' . base_url() . 'uploads/certificate/' . $fetch_cert['cert_file'] . '" download><i class="mdi mdi-download"></i></a>';
						}
					}
				} else {
					$regencert = "-";
				}
			}
			$output = array();
			$output['button'] = "<center>" . $regencert ." ". $downloadcert . "</center>";
			$output['cname'] = $cname;
			$output['cos_status'] = $cos_status;
			$output['status_learner'] = $status_learner;
			$cosen_firsttime = $value['cosen_firsttime'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['cosen_firsttime'])) : "<center>-</center>";
			$cosen_firsttimeori = $value['cosen_firsttime'] != "0000-00-00 00:00:00" ? $value['cosen_firsttime'] : "";
			$arrcosen_firsttimeori = array(
				'display' => $cosen_firsttime != "<center>-</center>" ? $cosen_firsttime : "<center>-</center>",
				'timestamp' => $cosen_firsttimeori != "" ? strtotime($cosen_firsttimeori) : 0,
			);

			$output['cosen_firsttime'] = $arrcosen_firsttimeori;
			$cosen_finishtime = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['cosen_finishtime'])) : "<center>-</center>";
			$cosen_finishtimeori = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? $value['cosen_finishtime'] : "";
			$arrcosen_finishtimeori = array(
				'display' => $cosen_finishtime != "<center>-</center>" ? $cosen_finishtime : "<center>-</center>",
				'timestamp' => $cosen_finishtimeori != "" ? strtotime($cosen_finishtimeori) : 0,
			);

			$output['cosen_finishtime'] = $arrcosen_finishtimeori;


			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_data_learnerincomplete()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();
		$fetch_enroll = $this->func_query->query_result('lms_cos_enroll', 'lms_cos', 'lms_cos.cos_id = lms_cos_enroll.cos_id', '', 'lms_cos.cos_id in (SELECT lms_les.cos_id from lms_les left join lms_med on lms_les.les_id = lms_med.lessons_id) and lms_cos_enroll.cos_id NOT IN (select lms_qiz.cos_id from lms_qiz) and lms_cos_enroll.cosen_status_sub != 1 and lms_cos_enroll.cosen_firsttime != "0000-00-00 00:00:00" and lms_cos.cos_isDelete = 0');
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		if (countArray($fetch_enroll) > 0) {
			foreach ($fetch_enroll as $key => $value) {
				$chkles = $this->func_query->numrows('lms_les', '', '', '', 'lms_les.cos_id = "' . $value['cos_id'] . '" and les_isDelete=0');

				$chklestc = $this->func_query->numrows('lms_les_tc', '', '', '', 'lms_les_tc.les_id in (SELECT lms_les.les_id from lms_les left join lms_med on lms_les.les_id = lms_med.lessons_id where lms_les.cos_id = "' . $value['cos_id'] . '") and lms_les_tc.cosen_id = "' . $value['cosen_id'] . '" and lms_les_tc.learn_status = "2"');
				if ($chkles != $chklestc) {
					unset($fetch_enroll[$key]);
				}
			}
		}
		if (countArray($fetch_enroll) > 0) {
			foreach ($fetch_enroll as $key => $value) {
				$fetch_emp = $this->func_query->query_row('lms_emp', 'lms_company', 'lms_emp.com_id = lms_company.com_id', '', 'lms_emp.emp_id = "' . $value['emp_id'] . '"');
				$cos_lang = explode(',', $value['cos_lang']);
				$value['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
				$value['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
				$value['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
				$cname = "";
				if ($lang == "thai") {
					if ($value['isTH'] == "1") {
						$cname = $value['cname_th'];
					} else {
						if ($cname == "") {
							$cname = $value['cname_eng'];
						}
						if ($cname == "") {
							$cname = $value['cname_jp'];
						}
					}
				} else if ($lang == "english") {
					if ($value['isENG'] == "1") {
						$cname = $value['cname_eng'];
					} else {
						if ($cname == "") {
							$cname = $value['cname_th'];
						}
						if ($cname == "") {
							$cname = $value['cname_jp'];
						}
					}
				} else {
					if ($value['isJP'] == "1") {
						$cname = $value['cname_jp'];
					} else {
						if ($cname == "") {
							$cname = $value['cname_eng'];
						}
						if ($cname == "") {
							$cname = $value['cname_th'];
						}
					}
				}
				$output = array();
				$output[0] = $num;
				$num++;
				$output[1] = $lang == "thai" ? $fetch_emp['fullname_th'] : $fetch_emp['fullname_en'];
				$output[2] = $lang == "thai" ? $fetch_emp['com_name_th'] : $fetch_emp['com_name_eng'];
				$output[3] = $cname;
				$output[4] = $value['cosen_firsttime'];
				array_push($fetch_arr, $output);
			}
		}
		return $fetch_arr;
	}

	public function fetch_data_user($com_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();
		$page = "manage/userdata";
		$arr_permission = $this->manage->chk_permission_page();
		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');
		$this->db->from('lms_usp');
		$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
		//$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id','RIGHT');
		$this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
		$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
		//$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id','RIGHT');
		$this->db->where('lms_emp.emp_isDelete', '0');

		$user = $this->session->userdata('user');
		//if(!in_array($user['useri'], array('admin_verztec','support_verztec'))){
		$this->db->where('lms_usp.useri not in ("admin_verztec","support_verztec")');
		//}
		if ($com_id != "") {
			$this->db->where('lms_company.com_id', $com_id);
		}
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$license = '<button type="button" name="license" id="' . $value['u_id'] . '" title="' . label('m_permission') . '" class="btn btn-info btn-xs license"><i class="mdi mdi-account-key"></i></button>';
			$update = '<button type="button" name="update" id="' . $value['u_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['emp_id'] . '" class="btn btn-danger btn-xs delete" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$resendfirsttime = '<button type="button" name="resendfirsttime" id="' . $value['emp_id'] . '" class="btn btn-primary btn-xs resendfirsttime" title="Resend E-Mail Firsttime"><i class="mdi mdi-clock-alert"></i></button>';
			$dataenroll = '<button type="button" name="dataenroll" id="' . $value['emp_id'] . '" class="btn btn-success btn-xs dataenroll" title="' . label('d_coc_total') . '"><i class="mdi mdi-format-list-numbers"></i></button>';
			$output = array();
			$output['1'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['2'] = $value['useri'];
			if ($lang == "thai") {
				$output['3'] = $value['fullname_th'];
				$output['4'] = "<center>" . $value['ug_name_th'] . "</center>";
				$output['5'] = "<center>" . $value['com_code'] . "</center>";
			} else {
				$output['3'] = $value['fullname_en'];
				$output['4'] = "<center>" . $value['ug_name_en'] . "</center>";
				$output['5'] = "<center>" . $value['com_code'] . "</center>";
			}
			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			if ($user['ug_id'] != "1") {
				$license = "";
			}
			if ($sess['ug_id'] > 1) {
				if ($value['ug_id'] == 1) {
					$update = "";
					$delete = "";
					$license = "";
				}
			}
			if (!in_array($user['useri'], array('support_verztec'))) {
				$resendfirsttime = "";
				$dataenroll = "";
			} else {
				if ($value['emp_firsttime'] != "1") {
					$resendfirsttime = "";
				} else {
					$dataenroll = "";
				}
				$fetch_rechkenroll = $this->func_query->query_row('lms_cos_enroll', '', '', '', 'emp_id = "' . $value['emp_id'] . '"', '', 'count(cos_id) as total_course');
				if (countArray($fetch_rechkenroll) > 0 && intval($fetch_rechkenroll['total_course']) == 0) {
					$dataenroll = "";
				}
			}
			$output['0'] = "<center>" . $license ." ". $update ." ". $delete ." ". $resendfirsttime ." ". $dataenroll . "</center>";
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_data_qrcode($com_id = "")
	{
		$sess = $this->session->userdata("user");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		$page = "qrcode/create";

		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');
		$where = "";
		$where = ' and lms_qrcode.com_id ="' . $com_id . '"';
		$fetch = $this->func_query->query_result('lms_qrcode', 'lms_company', 'lms_qrcode.com_id = lms_company.com_id', 'left', 'qr_isDelete="0"' . $where, 'qr_id  DESC');
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$publicUrl = base_url() . 'qrcode/view/' . $value['qr_id'];
			$safeName = htmlspecialchars($value['qr_name'], ENT_QUOTES, 'UTF-8');
			$safeDetail = htmlspecialchars((string) $value['qr_detail'], ENT_QUOTES, 'UTF-8');
			$update = '<button type="button" name="update" id="' . $value['qr_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update qr-action"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete" id="' . $value['qr_id'] . '" class="btn btn-danger btn-xs delete qr-action" title="' . label('delete') . '"><i class="mdi mdi-delete"></i></button>';
			$downloadqr = '<a title="' . label('qr_download') . '" class="btn btn-info btn-xs qr-action" href="' . REAL_PATH . '/uploads/qrcode_file/' . $value['qr_id'] . '.png" download="QR-' . $safeName . '.png"><i class="mdi mdi-download"></i></a>';
			$preview = '<button type="button" class="btn btn-primary btn-xs qr-action preview-qr" title="Preview QR Code" data-name="' . $safeName . '" data-url="' . htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8') . '" data-image="' . REAL_PATH . '/uploads/qrcode_file/' . $value['qr_id'] . '.png"><i class="mdi mdi-qrcode-scan"></i></button>';
			$output = array();
			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			$qr_type = "";
			if ($value['qr_type'] == "1") {
				$qr_type = label('qr_typefile_a');
			} else if ($value['qr_type'] == "2") {
				$qr_type = label('qr_typefile_b');
			} else if ($value['qr_type'] == "3") {
				$qr_type = label('qr_typefile_c');
			} else {
				$qr_type = label('qr_typefile_d');
			}
			/*if($btn_delete=="1"||$btn_update=="1"){*/
			$output['1'] = "<span style='float:right;'>" . $num . "</span>";
			$num++;
			$output['2'] = $value['com_code'];
			$output['3'] = '<span class="qr-type" data-type="' . $value['qr_type'] . '">' . $qr_type . '</span>';
			$output['4'] = '<div class="qr-name">' . $safeName . '</div>' . ($safeDetail !== '' ? '<small class="text-muted qr-detail" title="' . $safeDetail . '">' . $safeDetail . '</small>' : '');
			$output['5'] = '<div class="qr-link"><a target="_blank" rel="noopener" href="' . $publicUrl . '">' . $publicUrl . '</a><button type="button" class="btn btn-link btn-sm copy-qr-link" data-url="' . htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8') . '" title="Copy link"><i class="mdi mdi-content-copy"></i></button></div>';
			$output['0'] = '<div class="qr-actions">' . $preview . $downloadqr . $update . $delete . '</div>';

			if ($value['qr_status'] == "1") {
				$output['6'] = '<span class="badge badge-success qr-status" data-status="1"><i class="mdi mdi-check-circle"></i> ' . label('open') . '</span>';
			} else {
				$output['6'] = '<span class="badge badge-secondary qr-status" data-status="0"><i class="mdi mdi-cancel"></i> ' . label('close') . '</span>';
			}
			/*}else{
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              $output['1'] = $qr_type;
              $output['2'] = $value['qr_name'];
              $output['3'] = '<a target="_blank" href="'.REAL_PATH.'/qrcode/'.$value['qr_id'].'">'.REAL_PATH.'/qrcode/'.$value['qr_id'].'</a>';

              if($value['qr_status']=="1"){
                $output['4'] = "<center>".label('open')."</center>";
              }else{
                $output['4'] = "<center>".label('close')."</center>";
              }
            }*/
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}

	public function fetch_data_position_detail($dep_id)
	{
		$sess = $this->session->userdata("user");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$page = "manage/departmentdata";

		$btn_add = $this->manage->chk_permission($page, 'ru_add');
		$btn_update = $this->manage->chk_permission($page, 'ru_edit');
		$btn_delete = $this->manage->chk_permission($page, 'ru_del');
		$btn_view = $this->manage->chk_permission($page, 'ru_view');

		$this->db->from('lms_position');
		$this->db->where('lms_position.dep_id', $dep_id);
		$this->db->where('lms_position.posi_isDelete', '0');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		foreach ($fetch as $key => $value) {
			$update = '<button type="button" name="update_detail" id="' . $value['posi_id'] . '" title="' . label('m_edit') . '" class="btn btn-warning btn-xs update_detail"><i class="mdi mdi-lead-pencil"></i></button>';
			$delete = '<button type="button" name="delete_detail" id="' . $value['posi_id'] . '" class="btn btn-danger btn-xs delete_detail" title="' . label('delete') . '"><i class="mdi mdi-window-close"></i></button>';
			$output = array();
			if ($btn_update != "1") {
				$update = "";
			}
			if ($btn_delete != "1") {
				$delete = "";
			}
			if ($btn_delete == "1" || $btn_update == "1") {
				$output['1'] = "<span style='float:right;'>" . $num . "</span>";
				$num++;
				$output['2'] = $value['posi_name_en'];
				$output['3'] = $value['posi_name_th'];
				$output['4'] = $value['posi_remark'];
				$output['0'] = "<center>" . $update ." ". $delete . "</center>";
			} else {
				$output['0'] = "<span style='float:right;'>" . $num . "</span>";
				$num++;
				$output['1'] = $value['posi_name_en'];
				$output['2'] = $value['posi_name_th'];
				$output['3'] = $value['posi_remark'];
			}
			$count++;
			array_push($fetch_arr, $output);
		}
		return $fetch_arr;
	}


	public function create_position_detail($data)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_position');
		$this->db->where('dep_id', $data['dep_id']);
		$this->db->where('posi_name_th', $data['posi_name_th']);
		$this->db->where('posi_name_en', $data['posi_name_en']);
		$this->db->where('posi_status', '1');
		$this->db->where('posi_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_position', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				$this->insertPositionToCourse($data['dep_id'], $id);
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}


	public function insertPositionToCourse($dep_id, $posi_id)
	{
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$fetch_dep = $this->func_query->query_row('lms_depart', '', '', '', 'dep_id = ' . $dep_id);
		$fetch_count = $this->func_query->query_row(
			'lms_position',
			'',
			'',
			'',
			'lms_position.dep_id in (select lms_depart.dep_id from lms_depart where lms_depart.com_id = ' . $fetch_dep['com_id'] . ' and lms_depart.dep_isDelete = 0 and dep_status = 1) and 
            lms_position.posi_isDelete = 0 and lms_position.posi_status = 1 and lms_position.posi_id != ' . $posi_id,
			'',
			'count(lms_position.posi_id) as amountPosition'
		);

		$this->db->from('lms_cos');
		$this->db->join('lms_cos_detail', 'lms_cos.cos_id = lms_cos_detail.cos_id');
		$this->db->join('lms_cos_detail_ug', 'lms_cos_detail_ug.cosde_id = lms_cos_detail.cosde_id');
		$this->db->where('lms_cos_detail_ug.posi_id in (SELECT lms_position.posi_id FROM lms_position WHERE lms_position.dep_id in (select lms_depart.dep_id from lms_depart where lms_depart.com_id = ' . $fetch_dep['com_id'] . ' and lms_depart.dep_isDelete = 0 and dep_status = 1) and lms_position.posi_isDelete = 0 and lms_position.posi_status = 1)');
		$this->db->select('lms_cos.ccode, lms_cos_detail.cosde_id, count(lms_cos_detail_ug.posi_id) as amountPosition');
		$this->db->group_by('lms_cos.cos_id');

		$queryChkCourseInPosition = $this->db->get();
		$fetchChkCourseInPosition =  $queryChkCourseInPosition->result_array();
		if (countArray($fetchChkCourseInPosition) > 0) {
			foreach ($fetchChkCourseInPosition as $keyCourse => $valueCourse) {
				if (intval($valueCourse['amountPosition']) == intval($fetch_count['amountPosition'])) {
					$arrInsert = array(
						'cosde_id' => $valueCourse['cosde_id'],
						'posi_id' => $posi_id,
						'cosdepos_date' => date('Y-m-d H:i')
					);
					$this->db->insert('lms_cos_detail_ug', $arrInsert);
				}
			}
		}
	}

	public function create_conmsg($data)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_confirmmsg');
		$this->db->where('conmsg_title_th', $data['conmsg_title_th']);
		$this->db->where('conmsg_title_eng', $data['conmsg_title_eng']);
		$this->db->where('conmsg_title_jp', $data['conmsg_title_jp']);
		$this->db->where('conmsg_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_confirmmsg', $data);
			$id = $this->db->insert_id();
			if ($id != "") {
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}

	public function create_qrcode_detail($data)
	{
		date_default_timezone_set("Asia/Bangkok");

		$this->db->from('lms_qrcode');
		$this->db->where('qr_name', $data['qr_name']);
		$this->db->where('com_id', $data['com_id']);
		$this->db->where('qr_isDelete', '0');
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('lms_qrcode', $data);
			$id = $this->db->insert_id();
			if ($id != "") {

				include ROOT_DIR . "assets/plugins/phpqrcode/qrlib.php";
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 6;
				$filename = ROOT_DIR . "uploads/qrcode_file/" . $id . ".png";
				QRcode::png(base_url() . 'qrcode/view/' . $id, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				return "2";
			} else {
				return "3";
			}
		} else {
			return "1";
		}
	}

	public function update_position_detail($data, $id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('posi_id', $id);
		$this->db->update('lms_position', $data);
		return "2";
	}

	public function update_conmsg($data, $conmsg_id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('conmsg_id', $conmsg_id);
		$this->db->update('lms_confirmmsg', $data);
		return "2";
	}

	public function update_qrcode_detail($data, $id)
	{
		date_default_timezone_set("Asia/Bangkok");
		$this->db->where('qr_id', $id);
		$this->db->update('lms_qrcode', $data);
		include ROOT_DIR . "assets/plugins/phpqrcode/qrlib.php";
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 6;
		$filename = ROOT_DIR . "uploads/qrcode_file/" . $id . ".png";
		QRcode::png(base_url() . 'qrcode/view/' . $id, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		return "2";
	}


	public function checkUser($useri)
	{
		$this->db->where('lms_usp.useri', $useri);
		$this->db->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id');
		$query = $this->db->get('lms_usp');
		$row = $query->row_array();

		$user = $this->session->userdata("user");
		if (empty($row)) {
			return 'EMPTY';
		} else {
			if (in_array($user['ug_id'], array('2', '6'))) {
				if ($user['com_id'] == $row['com_id']) {
					return 'FALSE';
				} else {
					return 'TRUE';
				}
			} else if ($user['ug_id'] == "1") {
				return 'FALSE';
			} else {
				return 'TRUE';
			}
		}
	}

	public function chk_grade($grade)
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->db->from('lms_cos_enroll');
		$this->db->where('emp_id', $user['emp_id']);
		$this->db->where('cosen_grade', $grade);
		$query = $this->db->get();
		$num = $query->num_rows();
		return $num;
	}

	public function chk_scoretotal()
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->db->from('lms_cos_enroll');
		$this->db->where('emp_id', $user['emp_id']);
		$this->db->where_not_in('cosen_score', '');
		$query = $this->db->get();
		$fetch = $query->result_array();
		$score = 0;
		foreach ($fetch as $key => $value) {
			$score += floatval($value['cosen_score']);
		}
		$scoretotal = 0;
		if ($score > 0) {
			$scoretotal = ($score * 100) / (countArray($fetch) * 100);
		}
		return $scoretotal;
	}

	public function query_course_registered()
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->db->from('lms_cos_enroll');
		$this->db->join('lms_cos', 'lms_cos_enroll.cos_id=lms_cos.cos_id');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		//$this->db->where('cosen_status','2');
		$query_ens = $this->db->get();
		$num_ens = $query_ens->num_rows();
		$fetch = $query_ens->result_array();
		return $fetch;
	}

	public function chk_course_registered()
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");

		$this->db->from('lms_cos_detail');
		$this->db->join('lms_cos_detail_ug', 'lms_cos_detail.cosde_id=lms_cos_detail_ug.cosde_id');
		$this->db->where('lms_cos_detail_ug.posi_id', $user['posi_id']);
		$query = $this->db->get();
		$num = $query->num_rows();


		$this->db->from('lms_cos_enroll');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		$this->db->where('cosen_status', '2');
		$query_ens = $this->db->get();
		$num_ens = $query_ens->num_rows();
		return $num - $num_ens;
	}

	public function chk_course_not_register()
	{

		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");

		$this->db->from('lms_cos_detail');
		$this->db->join('lms_cos_detail_ug', 'lms_cos_detail.cosde_id=lms_cos_detail_ug.cosde_id');
		$this->db->where('lms_cos_detail_ug.posi_id', $user['posi_id']);
		$query_registered = $this->db->get();
		$num_registered = $query_registered->num_rows();

		$this->db->from('lms_cos_enroll');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		$query = $this->db->get();
		$num = $query->num_rows();
		return intval($num_registered) - intval($num);
	}

	public function chk_course_status($status)
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		$this->db->from('lms_cos_enroll');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		if ($status == "2") {
			$this->db->where('lms_cos_enroll.cosen_status!=', '2');
			$this->db->where('lms_cos_enroll.cosen_status_sub!=', '1');
		} else {
			$this->db->where('lms_cos_enroll.cosen_firsttime!=', '0000-00-00 00:00:00');
			$this->db->where('lms_cos_enroll.cosen_finishtime!=', '0000-00-00 00:00:00');
			$this->db->where('lms_cos_enroll.cosen_status', $status);
		}
		$query = $this->db->get();
		$num = $query->num_rows();
		return $num;
	}

	public function chk_total_status($status)
	{
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata("user");
		if ($status == "1") {
			$this->db->from('lms_cos');
			$this->db->where('lms_cos.cos_status', '1');
			if ($user['com_admin'] == "com_associated") {
				$this->db->where('lms_cos.com_id', $user['com_id']);
			}
		} else {
			$this->db->from('lms_usp');
			$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
			$this->db->where('lms_usp.login', '1');
			$this->db->where('lms_usp_gp.Is_admin', '0');
			$this->db->where('lms_usp.dummy_status', '0');
		}
		$query = $this->db->get();
		$num = $query->num_rows();
		return $num;
	}
}
