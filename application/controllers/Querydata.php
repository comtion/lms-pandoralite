<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Querydata extends CI_Controller
{

	public function query_course()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id = "' . $cos_id . '"');
		$cos_lang = explode(',', $result['cos_lang']);
		$result['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
		$result['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
		$result['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
		$fetch_cg = $this->func_query->query_result('lms_cosincg', '', '', '', 'course_id="' . $cos_id . '" and status_cg="1"', '', 'cg_id');
		$arr_cg = array();
		if (countArray($fetch_cg) > 0) {
			foreach ($fetch_cg as $key => $value) {
				array_push($arr_cg, $value['cg_id']);
			}
			$result['cg_id'] = implode(',', $arr_cg);
		} else {
			$result['cg_id'] = "";
		}

		$cname = "";
		if ($lang=="thai") { 
			$cname = $result['cname_th']!=""?$result['cname_th']:$result['cname_eng'];
			$cname = $cname!=""?$cname:$result['cname_jp'];
		} elseif ($lang=="english"){ 
			$cname = $result['cname_eng']!=""?$result['cname_eng']:$result['cname_th'];
			$cname = $cname!=""?$cname:$result['cname_jp'];
		} else {
			$cname = $result['cname_jp']!=""?$result['cname_jp']:$result['cname_eng'];
			$cname = $cname!=""?$cname:$result['cname_th'];
		}
		$result['courseName'] = $cname;
		echo json_encode($result);
		exit();
	}

	public function public_course()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$sess = $this->session->userdata("user");
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		if (isset($sess['u_id'])) {
			$arr_update = array(
				'cos_public' 		=> '1',
				'cos_modifiedby' 	=> $sess['u_id'],
				'cos_modifieddate' 	=> date('Y-m-d H:i')
			);
			$this->db->where('cos_id', $cos_id);
			if ($this->db->update('lms_cos', $arr_update)) {
				$lang = "english";
				$arr_email = array();
				$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
				$cos_lang = explode(',', $fetch_cos['cos_lang']);
				$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
				$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
				$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
				$cname = "";
				if ($lang == "thai") {
					if ($fetch_cos['isTH'] == "1") {
						$cname = $fetch_cos['cname_th'];
					} else {
						if ($cname == "") {
							$cname = $fetch_cos['cname_eng'];
						}
						if ($cname == "") {
							$cname = $fetch_cos['cname_jp'];
						}
					}
				} else if ($lang == "english") {
					if ($fetch_cos['isENG'] == "1") {
						$cname = $fetch_cos['cname_eng'];
					} else {
						if ($cname == "") {
							$cname = $fetch_cos['cname_th'];
						}
						if ($cname == "") {
							$cname = $fetch_cos['cname_jp'];
						}
					}
				} else {
					if ($fetch_cos['isJP'] == "1") {
						$cname = $fetch_cos['cname_jp'];
					} else {
						if ($cname == "") {
							$cname = $fetch_cos['cname_eng'];
						}
						if ($cname == "") {
							$cname = $fetch_cos['cname_th'];
						}
					}
				}
				$fetch_cosincg = $this->func_query->query_result('lms_cosincg', '', '', '', 'course_id="' . $cos_id . '" and status_cg="1"');
				if (countArray($fetch_cosincg) > 0) {
					foreach ($fetch_cosincg as $key_cosincg => $value_cosincg) {
						$fetch_cg = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $value_cosincg['cg_id'] . '"');
						if (countArray($fetch_cg) > 0) {
							$cg_approve_by = explode(',', $fetch_cg['cg_approve_by']);
							if (countArray($cg_approve_by) > 0) {
								for ($i = 0; $i < countArray($cg_approve_by); $i++) {
									if (isset($cg_approve_by[$i])) {
										$fetch = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_usp.u_id = "' . $cg_approve_by[$i] . '"');
										if (countArray($fetch) > 0) {
											if (!in_array($fetch['emp_id'], $arr_email)) {
												array_push($arr_email, $fetch['emp_id']);
											}
										}
									}
								}
							}
						}
					}
					if (countArray($arr_email) > 0) {
						for ($loopmail = 0; $loopmail < countArray($arr_email); $loopmail++) {
							$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
							//if($lang!="thai"){
							$date = date('d F Y');
							//}
							$fetch = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_emp.emp_id = "' . $arr_email[$loopmail] . '" and emp_isDelete="0"');
							$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
							$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="4"');
							$fetch_about = $this->func_query->query_row('lms_about', '', '', '', 'da_id="1"');
							if (countArray($fetch_formatmail) > 0) {
								$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch['com_id'] . '"');
								$subject_th = $fetch_formatmail['smf_subject_th'];
								$subject_en = $fetch_formatmail['smf_subject_en'];
								$message_th = $fetch_formatmail['smf_message_th'];
								$message_en = $fetch_formatmail['smf_message_en'];
								$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
								if ($subject_th != "") {
									$subject_th = str_replace("#fullname", $fetch['fullname_th'], $subject_th);
									$subject_th = str_replace("#username", $fetch['useri'], $subject_th);
									$subject_th = str_replace("#email", $fetch['email'], $subject_th);
									$subject_th = str_replace("#coursename", $cname, $subject_th);
									$subject_th = str_replace("#link_frontend", base_url() . "managecourse/courses_demo/" . $cos_id, $subject_th);
									$subject_th = str_replace("#date", $date, $subject_th);
									$subject_th = str_replace("#time", date('H:i'), $subject_th);
									$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
									$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
								}
								if ($subject_en != "") {
									$subject_en = str_replace("#fullname", $fetch['fullname_en'], $subject_en);
									$subject_en = str_replace("#username", $fetch['useri'], $subject_en);
									$subject_en = str_replace("#email", $fetch['email'], $subject_en);
									$subject_en = str_replace("#coursename", $cname, $subject_en);
									$subject_en = str_replace("#link_frontend", base_url() . "managecourse/courses_demo/" . $cos_id, $subject_en);
									$subject_en = str_replace("#date", $date, $subject_en);
									$subject_en = str_replace("#time", date('H:i'), $subject_en);
									$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
									$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
								}
								if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
									$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
								} else {
									$img_val = '';
								}
								if ($message_th != "") {
									$message_th = str_replace("#fullname", $fetch['fullname_th'], $message_th);
									$message_th = str_replace("#username", $fetch['useri'], $message_th);
									$message_th = str_replace("#password", '', $message_th);
									$message_th = str_replace("#email", $fetch['email'], $message_th);
									$message_th = str_replace("#coursename", $cname, $message_th);
									$message_th = str_replace("#link_frontend", base_url() . "managecourse/courses_demo/" . $cos_id, $message_th);
									$message_th = str_replace("#date", $date, $message_th);
									$message_th = str_replace("#time", date('H:i'), $message_th);
									$message_th = str_replace("#image", $img_val, $message_th);
									$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
									$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
								}
								if ($message_en != "") {
									$message_en = str_replace("#fullname", $fetch['fullname_en'], $message_en);
									$message_en = str_replace("#username", $fetch['useri'], $message_en);
									$message_en = str_replace("#password", '', $message_en);
									$message_en = str_replace("#email", $fetch['email'], $message_en);
									$message_en = str_replace("#coursename", $cname, $message_en);
									$message_en = str_replace("#link_frontend", base_url() . "managecourse/courses_demo/" . $cos_id, $message_en);
									$message_en = str_replace("#date", $date, $message_en);
									$message_en = str_replace("#time", date('H:i'), $message_en);
									$message_en = str_replace("#image", $img_val, $message_en);
									$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
									$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
								}
								$lang = "english";
								if ($lang == "thai") {
									$this->db->sendEmail($fetch['email'], $message_th, $subject_th, $fetch_setmail);
								} else {
									$this->db->sendEmail($fetch['email'], $message_en, $subject_en, $fetch_setmail);
								}
							}
						}
					}
				}
			}
		}
	}

	public function delete_img_profile()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
		$fetch_userp = $this->func_query->query_row('lms_usp', '', '', '', 'lms_usp.u_id="' . $sess['u_id'] . '"');
		$output = array();
		if (countArray($fetch_userp) > 0) {
			if ($type == "1") {
				if (is_file(ROOT_DIR . "uploads/profile/" . $fetch_userp['img_profile'])) {
					audit_unlink(ROOT_DIR . "uploads/profile/" . $fetch_userp['img_profile']);
					$arr_update = array(
						'img_profile' => ''
					);
					$this->db->where('u_id', $fetch_userp['u_id']);
					$this->db->update('lms_usp', $arr_update);
				}
			} else {
				$fetch_userp = $this->func_query->query_row('lms_usp', '', '', '', 'lms_usp.u_id="' . $sess['u_id'] . '"');
				if (is_file(ROOT_DIR . "uploads/bg_user/" . $fetch_userp['bgpic_user'])) {
					audit_unlink(ROOT_DIR . "uploads/bg_user/" . $fetch_userp['bgpic_user']);
					$arr_update = array(
						'bgpic_user' => ''
					);
					$this->db->where('u_id', $fetch_userp['u_id']);
					$this->db->update('lms_usp', $arr_update);
				}
			}
			$output['status'] = "2";
		} else {
			$output['status'] = "1";
		}
		echo json_encode($output);
	}

	public function delete_img_com_logo()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
		$fetch_userp = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $_REQUEST['com_id'] . '"');
		$output = array();
		if (countArray($fetch_userp) > 0) {
			if ($type == "com_logo_footer") {
				if (is_file(ROOT_DIR . "uploads/logo/" . $fetch_userp['com_logo_footer'])) {
					audit_unlink(ROOT_DIR . "uploads/logo/" . $fetch_userp['com_logo_footer']);
					$arr_update = array(
						'com_logo_footer' => ''
					);
					$this->db->where('com_id', $fetch_userp['com_id']);
					$this->db->update('lms_company', $arr_update);
				}
			} else {
				$fetch_userp = $this->func_query->query_row('lms_company', '', '', '', 'lms_company.com_id="' . $_REQUEST['com_id'] . '"');
				if (is_file(ROOT_DIR . "uploads/logo/" . $fetch_userp['com_logo_top'])) {
					audit_unlink(ROOT_DIR . "uploads/logo/" . $fetch_userp['com_logo_top']);
					$arr_update = array(
						'com_logo_top' => ''
					);
					$this->db->where('com_id', $fetch_userp['com_id']);
					$this->db->update('lms_company', $arr_update);
				}
			}
			$output['status'] = "2";
		} else {
			$output['status'] = "1";
		}
		echo json_encode($output);
	}

	public function delete_logo()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
		$fetch_userp = $this->func_query->query_row('lms_usp', '', '', '', 'lms_usp.u_id="' . $sess['u_id'] . '"');
		$output = array();
		if (countArray($fetch_userp) > 0) {
			$fetchAbout = $this->func_query->query_row('lms_about', '', '', '', 'da_id = 1');
			if ($type == "da_logo_elearning") {
				$arrLogoElearning = explode("/", $fetchAbout["da_logo_elearning"]);
				$valLogoElearning = end($arrLogoElearning);
				if (is_file(ROOT_DIR . "images/".$valLogoElearning)) {
					audit_unlink(ROOT_DIR . "images/".$valLogoElearning);
				}
			} else if ($type == "da_logo_top") {
				$arrLogoTop = explode("/", $fetchAbout["da_logo_top"]);
				$valLogoTop = end($arrLogoTop);
				if (is_file(ROOT_DIR . "images/".$valLogoTop)) {
					audit_unlink(ROOT_DIR . "images/".$valLogoTop);
				}
			} else if ($type == "da_logo_footer") {
				$arrLogoFooter = explode("/", $fetchAbout["da_logo_footer"]);
				$valLogoFooter = end($arrLogoFooter);
				if (is_file(ROOT_DIR . "images/".$valLogoFooter)) {
					audit_unlink(ROOT_DIR . "images/".$valLogoFooter);
				}
			} else if ($type == "da_footer_background") {
				$arrFooterBackground = explode("/", $fetchAbout["da_footer_background"]);
				$valFooterBackground = end($arrFooterBackground);
				if (is_file(ROOT_DIR . "images/".$valFooterBackground)) {
					audit_unlink(ROOT_DIR . "images/".$valFooterBackground);
				}
			} else if (in_array($type, array(
				'da_manual_sa_th',
				'da_manual_sa_eng',
				'da_manual_gr_th',
				'da_manual_gr_eng',
				'da_manual_is_th',
				'da_manual_is_eng',
				'da_manual_is_center_th',
				'da_manual_is_center_eng',
				'da_manual_is_affiliate_th',
				'da_manual_is_affiliate_eng',
				'da_manual_ln_th',
				'da_manual_ln_eng'
			))) {
				$filePath = trim((string) $fetchAbout[$type]);
				if ($filePath != '') {
					$arrManualPath = explode("/", $filePath);
					$valManualPath = end($arrManualPath);
					if ($valManualPath != '' && is_file(ROOT_DIR . "uploads/user_manual/" . $valManualPath)) {
						audit_unlink(ROOT_DIR . "uploads/user_manual/" . $valManualPath);
					}
				}
				$arr_update = array(
					$type => ''
				);
				$this->db->where('da_id', '1');
				$this->db->update('lms_about', $arr_update);
			}
			$output['status'] = "2";
		} else {
			$output['status'] = "1";
		}
		echo json_encode($output);
	}

	public function runvideo()
	{
		$video = isset($_REQUEST['video']) ? $_REQUEST['video'] : "";
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
?>

		<video id="video_upload" controls preload="none" controls controlsList="nodownload" data-setup="{}" style="width: 100%">
			<source src="<?php echo REAL_PATH . '/uploads/media/' . $video; ?>" type="video/mp4">
			<script type="text/javascript">
				var myPlayer = videojs('video_upload');
				var firsttime = false; // false does't has bookmark, true = has bookmark
				var previousTime = 0;
				var currentTime = 0;
				var seekStart = null;
				myPlayer.mobileUi({
					fullscreen: {
						enterOnRotate: true,
						lockOnRotate: false,
						//iOS: true // ใช้ได้ตั้งแต่ iOS 10.3.3 ไม่เกิน 12.xx
					}
				});

				if (myPlayer) {
					myPlayer.ready(function() {
						setInterval(function() {
							currentTime = myPlayer.currentTime();
						}, 1000);

						myPlayer.on("ended", function(event) {
							console.log("End");
							rechk_onclick('<?php echo $id; ?>');
						})
					})
				}
			</script>
		</video>
	<?php
	}

	public function add_exp()
	{
		$datenow = date("Y/m/d H:i:s", strtotime("+30 minutes"));
		$date = date("Y/m/d H:i:s");
		$this->session->set_userdata("exp_date", $datenow);
		$return['datenow'] = $datenow;
		$return['date'] = $date;
		echo json_encode($return);
	}

	public function chk_logout()
	{
		$output = array();
		$sess = $this->session->userdata("user");
		$exp_date = $this->session->userdata("exp_date");
		// print_r($exp_date);
		$datenow = date("Y/m/d H:i:s");
		if ($exp_date <= $datenow) {
			$output['status'] = "0";
		} else {
			$output['status'] = "1";
		}
		echo json_encode($output);
	}

	public function enroll_course_byuser()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$fetch_chk = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
		$output = array();
		$lang = "english";
		if (countArray($fetch_chk) > 0) {
			$condition = $fetch_chk['condition'] != "" ? explode(",", $fetch_chk['condition']) : "";
			$pass = 1;
			$arr_txt = "";
			if ($condition != "" && countArray($condition) > 0) {
				$numloop_chk = 1;
				for ($i = 0; $i < countArray($condition); $i++) {
					$fetch_chkenroll = $this->func_query->query_row('lms_cos_enroll', '', '', '', 'cos_id="' . $condition[$i] . '" and emp_id = "' . $sess['emp_id'] . '" and cosen_isDelete="0" and cosen_status="1" and cosen_finishtime!="0000-00-00 00:00:00"');
					if (countArray($fetch_chkenroll) == 0) {

						$fetch_chkcos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $condition[$i] . '"');
						if (countArray($fetch_chkcos) > 0) {
							$pass = 0;
							$cos_lang = explode(',', $fetch_chkcos['cos_lang']);
							$fetch_chkcos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
							$fetch_chkcos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
							$fetch_chkcos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
							$cname = "";
							if ($lang == "thai") {
								if ($fetch_chkcos['isTH'] == "1") {
									$cname = $fetch_chkcos['cname_th'];
								} else {
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_eng'];
									}
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_jp'];
									}
								}
							} else if ($lang == "english") {
								if ($fetch_chkcos['isENG'] == "1") {
									$cname = $fetch_chkcos['cname_eng'];
								} else {
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_th'];
									}
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_jp'];
									}
								}
							} else {
								if ($fetch_chkcos['isJP'] == "1") {
									$cname = $fetch_chkcos['cname_jp'];
								} else {
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_eng'];
									}
									if ($cname == "") {
										$cname = $fetch_chkcos['cname_th'];
									}
								}
							}
							$arr_txt .= $cname;
							if ($numloop_chk < countArray($condition)) {
								$arr_txt .= ",";
							}
						}
					}
					$numloop_chk++;
				}
			}
			if ($pass == 1) {
				$fetch_chkseat = $this->func_query->numrows('lms_cos_enroll', '', '', '', 'cos_id="' . $cos_id . '" and cosen_isDelete="0" and cosen_status="1"');
				if ($fetch_chkseat <= intval($fetch_chk['seat_count'])) {
					$fetch_chkenroll = $this->func_query->query_row('lms_cos_enroll', '', '', '', 'cos_id="' . $cos_id . '" and emp_id = "' . $sess['emp_id'] . '" and cosen_isDelete="0" and cosen_status!="2"');
					if (countArray($fetch_chkenroll) == 0) {
						$arr_insert = array(
							'cos_id' => $cos_id,
							'emp_id' => $sess['emp_id'],
							'cosen_status' => '1', //ผู้เรียนปัจจุบัน
							'cosen_createby' => $sess['u_id'],
							'cosen_createdate' => date('Y-m-d H:i'),
							'cosen_modifiedby' => $sess['u_id'],
							'cosen_modifieddate' => date('Y-m-d H:i'),
							'cosen_timerequest' => date('Y-m-d H:i')
						);
						$this->db->insert('lms_cos_enroll', $arr_insert);
						$cosen_id = $this->db->insert_id();

						$this->db->insert('lms_log_enroll', array('cosen_id' => $cosen_id));

						$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
						$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
						$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
						if ($lang != "thai") {
							$date = date('d F Y');
						}
						$cos_lang = explode(',', $fetch_cos['cos_lang']);
						$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
						$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
						$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
						$cname = "";
						if ($lang == "thai") {
							if ($fetch_cos['isTH'] == "1") {
								$cname = $fetch_cos['cname_th'];
							} else {
								if ($cname == "") {
									$cname = $fetch_cos['cname_eng'];
								}
								if ($cname == "") {
									$cname = $fetch_cos['cname_jp'];
								}
							}
						} else if ($lang == "english") {
							if ($fetch_cos['isENG'] == "1") {
								$cname = $fetch_cos['cname_eng'];
							} else {
								if ($cname == "") {
									$cname = $fetch_cos['cname_th'];
								}
								if ($cname == "") {
									$cname = $fetch_cos['cname_jp'];
								}
							}
						} else {
							if ($fetch_cos['isJP'] == "1") {
								$cname = $fetch_cos['cname_jp'];
							} else {
								if ($cname == "") {
									$cname = $fetch_cos['cname_eng'];
								}
								if ($cname == "") {
									$cname = $fetch_cos['cname_th'];
								}
							}
						}
						$period = "Unlimited time"; //label('UnlimitedTime');
						$fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_status="1" and cosde_isDelete="0"');
						if (countArray($fetch_cos_detail) > 0) {
							if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
								if ($lang == "thai") {
									$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_start'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_start'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_start'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_start'])) : "";
									$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_end'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_end'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_end'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_end'])) : "";
								} else {
									$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
									$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
								}

								$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
								$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
								if ($periodstart != "" && $periodend != "") {
									$period = $periodstart . " - " . $periodend;
								}
							}
						}
						$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
						//if($lang!="thai"){
						$date = date('d F Y');
						//}

						$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $sess['u_id'] . '"');
						$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="13"');
						if (countArray($fetch_formatmail) > 0) {
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
							$subject_th = $fetch_formatmail['smf_subject_th'];
							$subject_en = $fetch_formatmail['smf_subject_en'];
							$message_th = $fetch_formatmail['smf_message_th'];
							$message_en = $fetch_formatmail['smf_message_en'];
							$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
							if ($subject_th != "") {
								$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
								$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
								$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
								$subject_th = str_replace("#coursename", $cname, $subject_th);
								$subject_th = str_replace("#link_frontend", base_url() . "coursemain/my_course", $subject_th);
								$subject_th = str_replace("#date", $date, $subject_th);
								$subject_th = str_replace("#time", date('H:i'), $subject_th);
								$subject_th = str_replace("#perioddate", $period, $subject_th);
								$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
								$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
							}
							if ($subject_en != "") {
								$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
								$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
								$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
								$subject_en = str_replace("#coursename", $cname, $subject_en);
								$subject_en = str_replace("#link_frontend", base_url() . "coursemain/my_course", $subject_en);
								$subject_en = str_replace("#date", $date, $subject_en);
								$subject_en = str_replace("#time", date('H:i'), $subject_en);
								$subject_en = str_replace("#perioddate", $period, $subject_en);
								$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
								$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
							}
							if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
								$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
							} else {
								$img_val = '';
							}
							if ($message_th != "") {
								$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
								$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
								$message_th = str_replace("#email", $fetch_user['email'], $message_th);
								$message_th = str_replace("#coursename", $cname, $message_th);
								$message_th = str_replace("#link_frontend", base_url() . "coursemain/my_course", $message_th);
								$message_th = str_replace("#date", $date, $message_th);
								$message_th = str_replace("#time", date('H:i'), $message_th);
								$message_th = str_replace("#perioddate", $period, $message_th);
								$message_th = str_replace("#image", $img_val, $message_th);
								$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
								$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
							}
							if ($message_en != "") {
								$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
								$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
								$message_en = str_replace("#email", $fetch_user['email'], $message_en);
								$message_en = str_replace("#coursename", $cname, $message_en);
								$message_en = str_replace("#link_frontend", base_url() . "coursemain/my_course", $message_en);
								$message_en = str_replace("#date", $date, $message_en);
								$message_en = str_replace("#time", date('H:i'), $message_en);
								$message_en = str_replace("#perioddate", $period, $message_en);
								$message_en = str_replace("#image", $img_val, $message_en);
								$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
								$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
							}
							if ($lang == "thai") {
								$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
							} else {
								$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
							}
						}
						$output['status'] = "2";
					} else {
						if ($fetch_chkenroll['cosen_status'] == "0") {
							$output['status'] = "3"; //Wait approve
						} else {
							$output['status'] = "1"; //Duplicate
						}
					}
				} else {
					if (intval($fetch_chk['seat_count']) == 0) {
						$arr_insert = array(
							'cos_id' => $cos_id,
							'emp_id' => $sess['emp_id'],
							'cosen_status' => '1', //ผู้เรียนปัจจุบัน
							'cosen_createby' => $sess['u_id'],
							'cosen_createdate' => date('Y-m-d H:i'),
							'cosen_modifiedby' => $sess['u_id'],
							'cosen_modifieddate' => date('Y-m-d H:i'),
							'cosen_timerequest' => date('Y-m-d H:i')
						);
						$this->db->insert('lms_cos_enroll', $arr_insert);
						$cosen_id = $this->db->insert_id();

						$this->db->insert('lms_log_enroll', array('cosen_id' => $cosen_id));

						$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
						$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
						$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
						if ($lang != "thai") {
							$date = date('d F Y');
						}
						if ($lang == "thai") {
							$cname = $fetch_cos['cname_th'] != "" ? $fetch_cos['cname_th'] : $fetch_cos['cname_eng'];
							$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
						} else if ($lang == "english") {
							$cname = $fetch_cos['cname_eng'] != "" ? $fetch_cos['cname_eng'] : $fetch_cos['cname_th'];
							$cname = $cname != "" ? $cname : $fetch_cos['cname_jp'];
						} else {
							$cname = $fetch_cos['cname_jp'] != "" ? $fetch_cos['cname_jp'] : $fetch_cos['cname_eng'];
							$cname = $cname != "" ? $cname : $fetch_cos['cname_th'];
						}
						$period = "Unlimited time"; //label('UnlimitedTime');
						$fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_status="1" and cosde_isDelete="0"');
						if (countArray($fetch_cos_detail) > 0) {
							if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
								if ($lang == "thai") {
									$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_start'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_start'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_start'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_start'])) : "";
									$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_end'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_end'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_end'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_end'])) : "";
								} else {
									$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
									$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
								}
								$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
								$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

								if ($periodstart != "" && $periodend != "") {
									$period = $periodstart . " - " . $periodend;
								}
							}
						}
						$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
						// if($lang!="thai"){
						$date = date('d F Y');
						//}

						$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $sess['u_id'] . '"');
						$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="13"');
						if (countArray($fetch_formatmail) > 0) {
							$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
							$subject_th = $fetch_formatmail['smf_subject_th'];
							$subject_en = $fetch_formatmail['smf_subject_en'];
							$message_th = $fetch_formatmail['smf_message_th'];
							$message_en = $fetch_formatmail['smf_message_en'];
							$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
							if ($subject_th != "") {
								$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
								$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
								$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
								$subject_th = str_replace("#coursename", $cname, $subject_th);
								$subject_th = str_replace("#link_frontend", base_url() . "coursemain/my_course", $subject_th);
								$subject_th = str_replace("#date", $date, $subject_th);
								$subject_th = str_replace("#time", date('H:i'), $subject_th);
								$subject_th = str_replace("#perioddate", $period, $subject_th);
								$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
								$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
							}
							if ($subject_en != "") {
								$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
								$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
								$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
								$subject_en = str_replace("#coursename", $cname, $subject_en);
								$subject_en = str_replace("#link_frontend", base_url() . "coursemain/my_course", $subject_en);
								$subject_en = str_replace("#date", $date, $subject_en);
								$subject_en = str_replace("#time", date('H:i'), $subject_en);
								$subject_en = str_replace("#perioddate", $period, $subject_en);
								$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
								$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
							}
							if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
								$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
							} else {
								$img_val = '';
							}
							if ($message_th != "") {
								$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
								$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
								$message_th = str_replace("#email", $fetch_user['email'], $message_th);
								$message_th = str_replace("#coursename", $cname, $message_th);
								$message_th = str_replace("#link_frontend", base_url() . "coursemain/my_course", $message_th);
								$message_th = str_replace("#date", $date, $message_th);
								$message_th = str_replace("#time", date('H:i'), $message_th);
								$message_th = str_replace("#perioddate", $period, $message_th);
								$message_th = str_replace("#image", $img_val, $message_th);
								$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
								$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
							}
							if ($message_en != "") {
								$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
								$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
								$message_en = str_replace("#email", $fetch_user['email'], $message_en);
								$message_en = str_replace("#coursename", $cname, $message_en);
								$message_en = str_replace("#link_frontend", base_url() . "coursemain/my_course", $message_en);
								$message_en = str_replace("#date", $date, $message_en);
								$message_en = str_replace("#time", date('H:i'), $message_en);
								$message_en = str_replace("#perioddate", $period, $message_en);
								$message_en = str_replace("#image", $img_val, $message_en);
								$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
								$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
							}
							if ($lang == "thai") {
								$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
							} else {
								$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
							}
						}
						$output['status'] = "2";
					} else {
						$output['status'] = "5"; //Seat Full
					}
				}
			} else {
				$output['status'] = "11"; //condition
				$output['msg'] = $arr_txt;
			}
		} else {
			$output['status'] = "0";
		}
		echo json_encode($output);
	}

	public function update_firsttime()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : "";
		$fetch_chk = $this->func_query->query_row('lms_emp', '', '', '', 'emp_id="' . $emp_id . '"');
		$output = array();
		if (countArray($fetch_chk) > 0) {
			$arr = array(
				'emp_firsttime' => '0',
				'emp_modifiedby' => $sess['u_id'],
				'emp_modifieddate' => date('Y-m-d H:i')
			);
			$this->db->where('emp_id', $emp_id);
			$this->db->update('lms_emp', $arr);
			$this->session->set_userdata('redirect_val', '');
			$output['status'] = "2";
		} else {
			$output['status'] = "2";
		}
		echo json_encode($output);
	}

	public function replaceAnswerForCasePicture($arrTc, $arrMultiChoice)
	{
		if (countArray($arrTc) > 0) {
			foreach ($arrTc as $tc => $valueTc) {
				if (strpos($valueTc, '<img') !== false) {
					$answerString = $valueTc;
					$answerString = str_replace("<p>&nbsp;</p>", "", $answerString);
					$answerString = str_replace(array("\n", "\r"), "", $answerString);
					$answerString = strip_tags($answerString);
					$answerString = str_replace(" ", "", $answerString);
					$answerString = str_replace("&nbsp;", "", $answerString);
					$answerString = str_replace("\xc2\xa0", "", $answerString);
					if ($answerString != "") {
						$arrTc[$tc] = $answerString;
					} else {
						$key = array_search($valueTc, $arrMultiChoice);
						if ($key) {
							$arrTc[$tc] = str_replace('mul_c', '', $key);
						}
					}
				} else {
					$arrTc[$tc] = $valueTc;
				}
			}
		}
		return implode(',', $arrTc);
	}

	public function detail_publicsurvey_report()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		$fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
		$fetch_svde = $this->func_query->query_result('lms_svde', '', '', '', 'sv_id="' . $sv_id . '" and svde_isDelete="0"', '', 'lms_svde.svde_id,svde_name_th,svde_name_eng,svde_name_jp');
		$numsvde = ((countArray($fetch_svde) * 2) * 200) + 800;
		if ($lang == "thai") {
			$sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			$sv_explanation = $fetch_sv['sv_explanation_th'] != "" ? $fetch_sv['sv_explanation_th'] : $fetch_sv['sv_explanation_eng'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_jp'];
		} else if ($lang == "english") {
			$sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			$sv_explanation = $fetch_sv['sv_explanation_eng'] != "" ? $fetch_sv['sv_explanation_eng'] : $fetch_sv['sv_explanation_th'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_jp'];
		} else {
			$sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
			$sv_explanation = $fetch_sv['sv_explanation_jp'] != "" ? $fetch_sv['sv_explanation_jp'] : $fetch_sv['sv_explanation_eng'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_th'];
		}
	?>
		<h4><?php echo $sv_title; ?></h4><br>
		<hr>
		<?php echo $sv_explanation; ?>
		<div class="table-responsive">
			<table id="myTable_detail" width="" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th width="50" align="center"></th>
						<th width="250" align="center">
							<center><?php echo label('m_name'); ?></center>
						</th>
						<th width="300" align="center">
							<center><?php echo label('com_name'); ?></center>
						</th>
						<th width="100" align="center">
							<center><?php echo label('m_department'); ?></center>
						</th>
						<th width="100" align="center">
							<center><?php echo label('p_postname'); ?></center>
						</th>
						<?php if (countArray($fetch_svde) > 0) {
							$numloop = 1;
							foreach ($fetch_svde as $key_svde => $value_svde) {
								$fetchMultipleChoice = $this->func_query->query_row('lms_svde_mul', '', '', '', 'svde_id="' . $value_svde['svde_id'] . '"');
								$fetch_svde[$key_svde]['multipleChoice'] = $fetchMultipleChoice;
						?>
								<th width="200" align="center">
									<center><?php echo label('question') . "<br>" . $numloop; ?></center>
								</th>
								<th width="200" align="center">
									<center><?php echo label('answer') . "<br>" . $numloop;
													$numloop++; ?></center>
								</th>
						<?php     }
						} ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$fetch_detail = $this->func_query->query_result('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and svtc_isDelete="0" and svtc_status="1"', 'svtc_id DESC', 'emp_id');
					if (countArray($fetch_detail) > 0) {
						$numrows = 1;
						$arrEmpID = array();
						$arrEmpData = array();
						$arrCompany = array();
						foreach ($fetch_detail as $key_detail => $value_detail) {
							if (!in_array($value_detail['emp_id'], $arrEmpID)) {
								array_push($arrEmpID, $value_detail['emp_id']);
							}
						}
						$fetch_com = $this->func_query->query_result('lms_company', '', '', '', '', '', 'com_id,com_name_th,com_name_eng');
						foreach ($fetch_com as $key_com => $value_com) {
							$arrCompany[$value_com['com_id']]['com_name'] = $lang == "thai" ? $value_com['com_name_th'] : $value_com['com_name_eng'];
						}
						$fetch_emp = $this->func_query->query_result('lms_emp', 'lms_usp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_emp.emp_id in (' . implode(",", $arrEmpID) . ')', '', 'lms_emp.emp_id,lms_emp.com_id,lms_usp.posi_id,lms_emp.fullname_th,lms_emp.fullname_en');
						foreach ($fetch_emp as $key_emp => $value_emp) {
							$fetch_dep = $this->func_query->query_row('lms_depart', 'lms_position', 'lms_depart.dep_id = lms_position.dep_id', '', 'lms_position.posi_id="' . $value_emp['posi_id'] . '"', '', 'dep_name_th,dep_name_en,posi_name_th,posi_name_en');
							$arrEmpData[$value_emp['emp_id']]['fullname'] = $lang == "thai" ? $value_emp['fullname_th'] : $value_emp['fullname_en'];
							$arrEmpData[$value_emp['emp_id']]['com_name'] = $arrCompany[$value_emp['com_id']]['com_name'];
							$arrEmpData[$value_emp['emp_id']]['dep_name'] = $lang == "thai" ? $fetch_dep['dep_name_th'] : $fetch_dep['dep_name_en'];
							$arrEmpData[$value_emp['emp_id']]['posi_name'] = $lang == "thai" ? $fetch_dep['posi_name_th'] : $fetch_dep['posi_name_en'];
						}
						foreach ($fetch_detail as $key_detail => $value_detail) {
					?>
							<tr>
								<td align="right"><?php echo $numrows; ?></td>
								<td><?php if ($fetch_sv['sv_type'] == "1") {
											echo $arrEmpData[$value_detail['emp_id']]['fullname'];
										} else {
											echo "User " . $numrows;
										} ?></td>
								<td><?php echo $arrEmpData[$value_detail['emp_id']]['com_name']; ?></td>
								<td><?php echo $arrEmpData[$value_detail['emp_id']]['dep_name']; ?></td>
								<td><?php echo $arrEmpData[$value_detail['emp_id']]['posi_name']; ?></td>
								<?php if (countArray($fetch_svde) > 0) {
									$numloop = 1;
									foreach ($fetch_svde as $key_svde => $value_svde) {
										$tc_answer = "-";
										$fetch_svdetc = $this->func_query->query_row('lms_svde_tc', '', '', '', 'svde_id="' . $value_svde['svde_id'] . '" and emp_id="' . $value_detail['emp_id'] . '"', 'tc_id DESC', 'tc_answer,tc_note');
										if (countArray($fetch_svdetc) > 0) {
											$tc_answer = $fetch_svdetc['tc_answer'];
											$tc_answer = str_replace("svde_specify", $fetch_svdetc['tc_note'], $tc_answer);
											$tc_answer = str_replace("||", ",", $tc_answer);
										}

										if ($lang == "thai") {
											$svde_name = $value_svde['svde_name_th'] != "" ? $value_svde['svde_name_th'] : $value_svde['svde_name_eng'];
											$svde_name = $svde_name != "" ? $svde_name : $value_svde['svde_name_jp'];
										} else if ($lang == "english") {
											$svde_name = $value_svde['svde_name_eng'] != "" ? $value_svde['svde_name_eng'] : $value_svde['svde_name_th'];
											$svde_name = $svde_name != "" ? $svde_name : $value_svde['svde_name_jp'];
										} else {
											$svde_name = $value_svde['svde_name_jp'] != "" ? $value_svde['svde_name_jp'] : $value_svde['svde_name_eng'];
											$svde_name = $svde_name != "" ? $svde_name : $value_svde['svde_name_th'];
										}

										if (isset($fetch_svdetc['tc_answer']) && strpos($fetch_svdetc['tc_answer'], '<img') !== false) {
											$maintc_answer = $fetch_svdetc['tc_answer'];
											$maintc_answer = str_replace("svde_specify", $fetch_svdetc['tc_note'], $maintc_answer);
											$arrTcAnswer = explode("||", $maintc_answer);
											$quesSurvey = array();
											$multipleChoice = $value_svde['multipleChoice'];
											if ($lang == "thai") {
												$quesSurvey['mul_c1'] = $multipleChoice['mul_c1_th'] != "" ? $multipleChoice['mul_c1_th'] : $multipleChoice['mul_c1_eng'];
												$quesSurvey['mul_c1'] = $quesSurvey['mul_c1'] != "" ? $quesSurvey['mul_c1'] : $multipleChoice['mul_c1_jp'];
												$quesSurvey['mul_c2'] = $multipleChoice['mul_c2_th'] != "" ? $multipleChoice['mul_c2_th'] : $multipleChoice['mul_c2_eng'];
												$quesSurvey['mul_c2'] = $quesSurvey['mul_c2'] != "" ? $quesSurvey['mul_c2'] : $multipleChoice['mul_c2_jp'];
												$quesSurvey['mul_c3'] = $multipleChoice['mul_c3_th'] != "" ? $multipleChoice['mul_c3_th'] : $multipleChoice['mul_c3_eng'];
												$quesSurvey['mul_c3'] = $quesSurvey['mul_c3'] != "" ? $quesSurvey['mul_c3'] : $multipleChoice['mul_c3_jp'];
												$quesSurvey['mul_c4'] = $multipleChoice['mul_c4_th'] != "" ? $multipleChoice['mul_c4_th'] : $multipleChoice['mul_c4_eng'];
												$quesSurvey['mul_c4'] = $quesSurvey['mul_c4'] != "" ? $quesSurvey['mul_c4'] : $multipleChoice['mul_c4_jp'];
												$quesSurvey['mul_c5'] = $multipleChoice['mul_c5_th'] != "" ? $multipleChoice['mul_c5_th'] : $multipleChoice['mul_c5_eng'];
												$quesSurvey['mul_c5'] = $quesSurvey['mul_c5'] != "" ? $quesSurvey['mul_c5'] : $multipleChoice['mul_c5_jp'];
												$quesSurvey['mul_c6'] = $multipleChoice['mul_c6_th'] != "" ? $multipleChoice['mul_c6_th'] : $multipleChoice['mul_c6_eng'];
												$quesSurvey['mul_c6'] = $quesSurvey['mul_c6'] != "" ? $quesSurvey['mul_c6'] : $multipleChoice['mul_c6_jp'];
												$quesSurvey['mul_c7'] = $multipleChoice['mul_c7_th'] != "" ? $multipleChoice['mul_c7_th'] : $multipleChoice['mul_c7_eng'];
												$quesSurvey['mul_c7'] = $quesSurvey['mul_c7'] != "" ? $quesSurvey['mul_c7'] : $multipleChoice['mul_c7_jp'];
												$quesSurvey['mul_c8'] = $multipleChoice['mul_c8_th'] != "" ? $multipleChoice['mul_c8_th'] : $multipleChoice['mul_c8_eng'];
												$quesSurvey['mul_c8'] = $quesSurvey['mul_c8'] != "" ? $quesSurvey['mul_c8'] : $multipleChoice['mul_c8_jp'];
												$quesSurvey['mul_c9'] = $multipleChoice['mul_c9_th'] != "" ? $multipleChoice['mul_c9_th'] : $multipleChoice['mul_c9_eng'];
												$quesSurvey['mul_c9'] = $quesSurvey['mul_c9'] != "" ? $quesSurvey['mul_c9'] : $multipleChoice['mul_c9_jp'];
												$quesSurvey['mul_c10'] = $multipleChoice['mul_c10_th'] != "" ? $multipleChoice['mul_c10_th'] : $multipleChoice['mul_c10_eng'];
												$quesSurvey['mul_c10'] = $quesSurvey['mul_c10'] != "" ? $quesSurvey['mul_c10'] : $multipleChoice['mul_c10_jp'];
												$quesSurvey['mul_c11'] = $multipleChoice['mul_c11_th'] != "" ? $multipleChoice['mul_c11_th'] : $multipleChoice['mul_c11_eng'];
												$quesSurvey['mul_c11'] = $quesSurvey['mul_c11'] != "" ? $quesSurvey['mul_c11'] : $multipleChoice['mul_c11_jp'];
												$quesSurvey['mul_c12'] = $multipleChoice['mul_c12_th'] != "" ? $multipleChoice['mul_c12_th'] : $multipleChoice['mul_c12_eng'];
												$quesSurvey['mul_c12'] = $quesSurvey['mul_c12'] != "" ? $quesSurvey['mul_c12'] : $multipleChoice['mul_c12_jp'];
												$quesSurvey['mul_c13'] = $multipleChoice['mul_c13_th'] != "" ? $multipleChoice['mul_c13_th'] : $multipleChoice['mul_c13_eng'];
												$quesSurvey['mul_c13'] = $quesSurvey['mul_c13'] != "" ? $quesSurvey['mul_c13'] : $multipleChoice['mul_c13_jp'];
												$quesSurvey['mul_c14'] = $multipleChoice['mul_c14_th'] != "" ? $multipleChoice['mul_c14_th'] : $multipleChoice['mul_c14_eng'];
												$quesSurvey['mul_c14'] = $quesSurvey['mul_c14'] != "" ? $quesSurvey['mul_c14'] : $multipleChoice['mul_c14_jp'];
												$quesSurvey['mul_c15'] = $multipleChoice['mul_c15_th'] != "" ? $multipleChoice['mul_c15_th'] : $multipleChoice['mul_c15_eng'];
												$quesSurvey['mul_c15'] = $quesSurvey['mul_c15'] != "" ? $quesSurvey['mul_c15'] : $multipleChoice['mul_c15_jp'];
											} else if ($lang == "english") {
												$quesSurvey['mul_c1'] = $multipleChoice['mul_c1_eng'] != "" ? $multipleChoice['mul_c1_eng'] : $multipleChoice['mul_c1_th'];
												$quesSurvey['mul_c1'] = $quesSurvey['mul_c1'] != "" ? $quesSurvey['mul_c1'] : $multipleChoice['mul_c1_jp'];
												$quesSurvey['mul_c2'] = $multipleChoice['mul_c2_eng'] != "" ? $multipleChoice['mul_c2_eng'] : $multipleChoice['mul_c2_th'];
												$quesSurvey['mul_c2'] = $quesSurvey['mul_c2'] != "" ? $quesSurvey['mul_c2'] : $multipleChoice['mul_c2_jp'];
												$quesSurvey['mul_c3'] = $multipleChoice['mul_c3_eng'] != "" ? $multipleChoice['mul_c3_eng'] : $multipleChoice['mul_c3_th'];
												$quesSurvey['mul_c3'] = $quesSurvey['mul_c3'] != "" ? $quesSurvey['mul_c3'] : $multipleChoice['mul_c3_jp'];
												$quesSurvey['mul_c4'] = $multipleChoice['mul_c4_eng'] != "" ? $multipleChoice['mul_c4_eng'] : $multipleChoice['mul_c4_th'];
												$quesSurvey['mul_c4'] = $quesSurvey['mul_c4'] != "" ? $quesSurvey['mul_c4'] : $multipleChoice['mul_c4_jp'];
												$quesSurvey['mul_c5'] = $multipleChoice['mul_c5_eng'] != "" ? $multipleChoice['mul_c5_eng'] : $multipleChoice['mul_c5_th'];
												$quesSurvey['mul_c5'] = $quesSurvey['mul_c5'] != "" ? $quesSurvey['mul_c5'] : $multipleChoice['mul_c5_jp'];
												$quesSurvey['mul_c6'] = $multipleChoice['mul_c6_eng'] != "" ? $multipleChoice['mul_c6_eng'] : $multipleChoice['mul_c6_th'];
												$quesSurvey['mul_c6'] = $quesSurvey['mul_c6'] != "" ? $quesSurvey['mul_c6'] : $multipleChoice['mul_c6_jp'];
												$quesSurvey['mul_c7'] = $multipleChoice['mul_c7_eng'] != "" ? $multipleChoice['mul_c7_eng'] : $multipleChoice['mul_c7_th'];
												$quesSurvey['mul_c7'] = $quesSurvey['mul_c7'] != "" ? $quesSurvey['mul_c7'] : $multipleChoice['mul_c7_jp'];
												$quesSurvey['mul_c8'] = $multipleChoice['mul_c8_eng'] != "" ? $multipleChoice['mul_c8_eng'] : $multipleChoice['mul_c8_th'];
												$quesSurvey['mul_c8'] = $quesSurvey['mul_c8'] != "" ? $quesSurvey['mul_c8'] : $multipleChoice['mul_c8_jp'];
												$quesSurvey['mul_c9'] = $multipleChoice['mul_c9_eng'] != "" ? $multipleChoice['mul_c9_eng'] : $multipleChoice['mul_c9_th'];
												$quesSurvey['mul_c9'] = $quesSurvey['mul_c9'] != "" ? $quesSurvey['mul_c9'] : $multipleChoice['mul_c9_jp'];
												$quesSurvey['mul_c10'] = $multipleChoice['mul_c10_eng'] != "" ? $multipleChoice['mul_c10_eng'] : $multipleChoice['mul_c10_th'];
												$quesSurvey['mul_c10'] = $quesSurvey['mul_c10'] != "" ? $quesSurvey['mul_c10'] : $multipleChoice['mul_c10_jp'];
												$quesSurvey['mul_c11'] = $multipleChoice['mul_c11_eng'] != "" ? $multipleChoice['mul_c11_eng'] : $multipleChoice['mul_c11_th'];
												$quesSurvey['mul_c11'] = $quesSurvey['mul_c11'] != "" ? $quesSurvey['mul_c11'] : $multipleChoice['mul_c11_jp'];
												$quesSurvey['mul_c12'] = $multipleChoice['mul_c12_eng'] != "" ? $multipleChoice['mul_c12_eng'] : $multipleChoice['mul_c12_th'];
												$quesSurvey['mul_c12'] = $quesSurvey['mul_c12'] != "" ? $quesSurvey['mul_c12'] : $multipleChoice['mul_c12_jp'];
												$quesSurvey['mul_c13'] = $multipleChoice['mul_c13_eng'] != "" ? $multipleChoice['mul_c13_eng'] : $multipleChoice['mul_c13_th'];
												$quesSurvey['mul_c13'] = $quesSurvey['mul_c13'] != "" ? $quesSurvey['mul_c13'] : $multipleChoice['mul_c13_jp'];
												$quesSurvey['mul_c14'] = $multipleChoice['mul_c14_eng'] != "" ? $multipleChoice['mul_c14_eng'] : $multipleChoice['mul_c14_th'];
												$quesSurvey['mul_c14'] = $quesSurvey['mul_c14'] != "" ? $quesSurvey['mul_c14'] : $multipleChoice['mul_c14_jp'];
												$quesSurvey['mul_c15'] = $multipleChoice['mul_c15_eng'] != "" ? $multipleChoice['mul_c15_eng'] : $multipleChoice['mul_c15_th'];
												$quesSurvey['mul_c15'] = $quesSurvey['mul_c15'] != "" ? $quesSurvey['mul_c15'] : $multipleChoice['mul_c15_jp'];
											} else {
												$quesSurvey['mul_c1'] = $multipleChoice['mul_c1_jp'] != "" ? $multipleChoice['mul_c1_jp'] : $multipleChoice['mul_c1_eng'];
												$quesSurvey['mul_c1'] = $quesSurvey['mul_c1'] != "" ? $quesSurvey['mul_c1'] : $multipleChoice['mul_c1_th'];
												$quesSurvey['mul_c2'] = $multipleChoice['mul_c2_jp'] != "" ? $multipleChoice['mul_c2_jp'] : $multipleChoice['mul_c2_eng'];
												$quesSurvey['mul_c2'] = $quesSurvey['mul_c2'] != "" ? $quesSurvey['mul_c2'] : $multipleChoice['mul_c2_th'];
												$quesSurvey['mul_c3'] = $multipleChoice['mul_c3_jp'] != "" ? $multipleChoice['mul_c3_jp'] : $multipleChoice['mul_c3_eng'];
												$quesSurvey['mul_c3'] = $quesSurvey['mul_c3'] != "" ? $quesSurvey['mul_c3'] : $multipleChoice['mul_c3_th'];
												$quesSurvey['mul_c4'] = $multipleChoice['mul_c4_jp'] != "" ? $multipleChoice['mul_c4_jp'] : $multipleChoice['mul_c4_eng'];
												$quesSurvey['mul_c4'] = $quesSurvey['mul_c4'] != "" ? $quesSurvey['mul_c4'] : $multipleChoice['mul_c4_th'];
												$quesSurvey['mul_c5'] = $multipleChoice['mul_c5_jp'] != "" ? $multipleChoice['mul_c5_jp'] : $multipleChoice['mul_c5_eng'];
												$quesSurvey['mul_c5'] = $quesSurvey['mul_c5'] != "" ? $quesSurvey['mul_c5'] : $multipleChoice['mul_c5_th'];
												$quesSurvey['mul_c6'] = $multipleChoice['mul_c6_jp'] != "" ? $multipleChoice['mul_c6_jp'] : $multipleChoice['mul_c6_eng'];
												$quesSurvey['mul_c6'] = $quesSurvey['mul_c6'] != "" ? $quesSurvey['mul_c6'] : $multipleChoice['mul_c6_th'];
												$quesSurvey['mul_c7'] = $multipleChoice['mul_c7_jp'] != "" ? $multipleChoice['mul_c7_jp'] : $multipleChoice['mul_c7_eng'];
												$quesSurvey['mul_c7'] = $quesSurvey['mul_c7'] != "" ? $quesSurvey['mul_c7'] : $multipleChoice['mul_c7_th'];
												$quesSurvey['mul_c8'] = $multipleChoice['mul_c8_jp'] != "" ? $multipleChoice['mul_c8_jp'] : $multipleChoice['mul_c8_eng'];
												$quesSurvey['mul_c8'] = $quesSurvey['mul_c8'] != "" ? $quesSurvey['mul_c8'] : $multipleChoice['mul_c8_th'];
												$quesSurvey['mul_c9'] = $multipleChoice['mul_c9_jp'] != "" ? $multipleChoice['mul_c9_jp'] : $multipleChoice['mul_c9_eng'];
												$quesSurvey['mul_c9'] = $quesSurvey['mul_c9'] != "" ? $quesSurvey['mul_c9'] : $multipleChoice['mul_c9_th'];
												$quesSurvey['mul_c10'] = $multipleChoice['mul_c10_jp'] != "" ? $multipleChoice['mul_c10_jp'] : $multipleChoice['mul_c10_eng'];
												$quesSurvey['mul_c10'] = $quesSurvey['mul_c10'] != "" ? $quesSurvey['mul_c10'] : $multipleChoice['mul_c10_th'];
												$quesSurvey['mul_c11'] = $multipleChoice['mul_c11_jp'] != "" ? $multipleChoice['mul_c11_jp'] : $multipleChoice['mul_c11_eng'];
												$quesSurvey['mul_c11'] = $quesSurvey['mul_c11'] != "" ? $quesSurvey['mul_c11'] : $multipleChoice['mul_c11_th'];
												$quesSurvey['mul_c12'] = $multipleChoice['mul_c12_jp'] != "" ? $multipleChoice['mul_c12_jp'] : $multipleChoice['mul_c12_eng'];
												$quesSurvey['mul_c12'] = $quesSurvey['mul_c12'] != "" ? $quesSurvey['mul_c12'] : $multipleChoice['mul_c12_th'];
												$quesSurvey['mul_c13'] = $multipleChoice['mul_c13_jp'] != "" ? $multipleChoice['mul_c13_jp'] : $multipleChoice['mul_c13_eng'];
												$quesSurvey['mul_c13'] = $quesSurvey['mul_c13'] != "" ? $quesSurvey['mul_c13'] : $multipleChoice['mul_c13_th'];
												$quesSurvey['mul_c14'] = $multipleChoice['mul_c14_jp'] != "" ? $multipleChoice['mul_c14_jp'] : $multipleChoice['mul_c14_eng'];
												$quesSurvey['mul_c14'] = $quesSurvey['mul_c14'] != "" ? $quesSurvey['mul_c14'] : $multipleChoice['mul_c14_th'];
												$quesSurvey['mul_c15'] = $multipleChoice['mul_c15_jp'] != "" ? $multipleChoice['mul_c15_jp'] : $multipleChoice['mul_c15_eng'];
												$quesSurvey['mul_c15'] = $quesSurvey['mul_c15'] != "" ? $quesSurvey['mul_c15'] : $multipleChoice['mul_c15_th'];
											}
											$tc_answer = $this->replaceAnswerForCasePicture($arrTcAnswer, $quesSurvey);
										}
										$tc_answer = strip_tags($tc_answer);
								?>
										<td><?php echo strip_tags($svde_name); ?></td>
										<td><?php echo $tc_answer; ?></td>
								<?php     }
								} ?>
							</tr>
					<?php $numrows++;
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<script type="text/javascript">
			$('#myTable_detail').DataTable({
				"language": {
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
					"sInfo": "<?php echo label('sInfo'); ?>",
					"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
					"decimal": "",
					"emptyTable": "<?php echo label('wg_datanotfound'); ?>",
					"infoPostFix": "",
					"thousands": ",",
					//"lengthMenu":     "แสดง _MENU_ รายการ",
					"lengthMenu": "<?php echo label('lengthMenu'); ?>",
					"loadingRecords": "<?php echo label('loadingRecords'); ?>",
					"processing": "<?php echo label('processing'); ?>",
					"search": "<?php echo label('filter_bar'); ?>",
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"paginate": {
						"first": "<?php echo label('firstpage'); ?>",
						"last": "<?php echo label('last'); ?>",
						"next": "<?php echo label('lrn_btn_next'); ?>",
						"previous": "<?php echo label('previous'); ?>"
					},
				},
				"scrollX": true,
				fixedColumns: true,
				dom: 'Bfrtip',
				buttons: [
					'copy', 'excel', 'print'
				],
				columnDefs: [{
					targets: [
						<?php if (countArray($fetch_svde) > 0) {
							$numloop = 5;
							$countsvde = countArray($fetch_svde) * 2;
							for ($i = 1; $i <= $countsvde; $i++) { ?>
								parseInt('<?php echo $numloop;
													$numloop++; ?>'),
						<?php }
						} ?>
					],
					createdCell: function(cell, td, cellData, rowData, row, col) {
						var $cell = $(cell);
						if (td.length > 20) {
							$(cell).contents().wrapAll("<div class='content'></div>");
							var $content = $cell.find(".content");
							$(cell).append($("<button class='btn btn-default btn-sm'>...</button>"));
							$btn = $(cell).find("button");

							$content.css({
								"height": "50px",
								"overflow": "hidden"
							})
							$cell.data("isLess", true);

							$btn.click(function() {
								var isLess = $cell.data("isLess");
								$content.css("height", isLess ? "auto" : "50px")
								$(this).html(isLess ? "<i class='mdi mdi-arrow-up-bold-circle-outline'></i>" : "...")
								$cell.data("isLess", !isLess)
							})
						}
					}
				}]
			});
		</script>
	<?php

	}

	public function query_list_emp_reportsurveycos()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		$fetch_sv = $this->func_query->query_row('lms_survey', 'lms_cos', 'lms_survey.cos_id = lms_cos.cos_id', '', 'sv_id="' . $sv_id . '"');
		$fetch_svde = $this->func_query->query_result('lms_survey_de', '', '', '', 'sv_id="' . $sv_id . '" and svde_isDelete="0"');
		$numsvde = ((countArray($fetch_svde) * 2) * 200) + 800;
		if ($lang == "thai") {
			$sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			$sv_explanation = $fetch_sv['sv_explanation_th'] != "" ? $fetch_sv['sv_explanation_th'] : $fetch_sv['sv_explanation_eng'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_jp'];
			$cname = $fetch_sv['cname_th'] != "" ? $fetch_sv['cname_th'] : $fetch_sv['cname_eng'];
			$cname = $cname != "" ? $cname : $fetch_sv['cname_jp'];
		} else if ($lang == "english") {
			$sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
			$sv_explanation = $fetch_sv['sv_explanation_eng'] != "" ? $fetch_sv['sv_explanation_eng'] : $fetch_sv['sv_explanation_th'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_jp'];
			$cname = $fetch_sv['cname_eng'] != "" ? $fetch_sv['cname_eng'] : $fetch_sv['cname_th'];
			$cname = $cname != "" ? $cname : $fetch_sv['cname_jp'];
		} else {
			$sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
			$sv_explanation = $fetch_sv['sv_explanation_jp'] != "" ? $fetch_sv['sv_explanation_jp'] : $fetch_sv['sv_explanation_eng'];
			$sv_explanation = $sv_explanation != "" ? $sv_explanation : $fetch_sv['sv_explanation_th'];
			$cname = $fetch_sv['cname_jp'] != "" ? $fetch_sv['cname_jp'] : $fetch_sv['cname_eng'];
			$cname = $cname != "" ? $cname : $fetch_sv['cname_th'];
		}
	?>
		<div class="table-responsive">
			<table id="myTable_detail" width="100%" cellspacing="0" class="table table-bordered table-striped">
				<thead>
					<!-- <tr>
                <th colspan="<?php echo (countArray($fetch_svde)) + 2; ?>"><center><?php echo $lang == "thai" ? "แบบสำรวจสำหรับรายวิชา " : "Survey for courses "; ?><?php echo $cname; ?></center></th>
          </tr>
          <tr>
                <th colspan="<?php echo (countArray($fetch_svde)) + 2; ?>"><center><?php echo $sv_title; ?></center></th>
          </tr> -->
					<tr>
						<th>No.</th>
						<?php
						$num = 0;
						foreach ($fetch_svde as $key_chk => $fetch_chk) {
							$num++;

							if ($lang == "thai") {
								$svde_detail = $fetch_chk['svde_detail_th'] != "" ? $fetch_chk['svde_detail_th'] : $fetch_chk['svde_detail_eng'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $fetch_chk['svde_detail_jp'];
							} else if ($lang == "english") {
								$svde_detail = $fetch_chk['svde_detail_eng'] != "" ? $fetch_chk['svde_detail_eng'] : $fetch_chk['svde_detail_th'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $fetch_chk['svde_detail_jp'];
							} else {
								$svde_detail = $fetch_chk['svde_detail_jp'] != "" ? $fetch_chk['svde_detail_jp'] : $fetch_chk['svde_detail_eng'];
								$svde_detail = $svde_detail != "" ? $svde_detail : $fetch_chk['svde_detail_th'];
							}
						?>
							<th>
								<center><?php echo $svde_detail; ?></center>
							</th>
						<?php } ?>
						<th>
							<?php
							if ($lang == "thai") {
								echo "ข้อเสนอแนะท้ายบท";
							} else if ($lang == "english") {
								echo "Suggestion at the end of survey";
							} else {
								echo "アンケート後のコメント";
							}
							?>
						</th>
					</tr>
					<!-- <tr>
                <th></th>
                <th></th>
            <?php for ($a = 0; $a < $num; $a++) { ?>
              <th><?php echo label('score'); ?></th>
            <?php } ?>
          </tr> -->
				</thead>
				<tbody>
					<?php
					$query_head = $this->func_query->query_result('lms_qn_user', '', '', '', 'sv_id="' . $sv_id . '"');
					if (countArray($query_head) > 0) {
						$num = 1;
						foreach ($query_head as $key_head => $fetch_head) {
							$query_detail = $this->func_query->query_result('lms_qn_user_de', '', '', '', 'lms_qn_user_de.qnu_id="' . $fetch_head['qnu_id'] . '"');
							if (countArray($query_detail) > 0) {
					?>
								<tr>
									<td><span style="float: right;"><?php echo $num;
																									$num++; ?></span></td>
									<?php
									foreach ($query_detail as $key_detail => $fetch_detail) {
										$qnude_suggestion = $fetch_detail['qnude_suggestion'] != "" ? " (" . $fetch_detail['qnude_suggestion'] . ")" : "";
									?>
										<td align="center"><?php echo $fetch_detail['qnude_var'] . $qnude_suggestion; ?></td>
										<!-- <td><?php echo $fetch_detail['qnude_suggestion']; ?></td> -->
									<?php   } ?>
									<td><?php echo $fetch_head['qnu_suggestion']; ?></td>
								</tr>
					<?php
							}
						}
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th>_</th>
						<?php
						$a = 0;
						$out_arr = array();
						$data1 = $this->func_query->query_result('lms_survey_de', 'lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id', '', 'lms_survey_de.sv_id="' . $sv_id . '" and lms_qn_user_de.qnude_var="1"', '', 'lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
						$data2 = $this->func_query->query_result('lms_survey_de', 'lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id', '', 'lms_survey_de.sv_id="' . $sv_id . '" and lms_qn_user_de.qnude_var="2"', '', 'lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
						$data3 = $this->func_query->query_result('lms_survey_de', 'lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id', '', 'lms_survey_de.sv_id="' . $sv_id . '" and lms_qn_user_de.qnude_var="3"', '', 'lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
						$data4 = $this->func_query->query_result('lms_survey_de', 'lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id', '', 'lms_survey_de.sv_id="' . $sv_id . '" and lms_qn_user_de.qnude_var="4"', '', 'lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
						$data5 = $this->func_query->query_result('lms_survey_de', 'lms_qn_user_de', 'lms_survey_de.svde_id = lms_qn_user_de.svde_id', '', 'lms_survey_de.sv_id="' . $sv_id . '" and lms_qn_user_de.qnude_var="5"', '', 'lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var');
						$objQuery = $this->func_query->query_result('lms_survey_de', '', '', '', 'sv_id="' . $sv_id . '" and svde_isDelete="0"');
						foreach ($objQuery as $key_chkobj => $fetch_chk) {
							$total = 0;
							$ans1[$a] = 0;
							$ans2[$a] = 0;
							$ans3[$a] = 0;
							$ans4[$a] = 0;
							$ans5[$a] = 0;
							foreach ($data1  as $key1 => $rowda) {
								if (($fetch_chk['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '1')) : $ans1[$a]++;
								endif;
							}
							foreach ($data2  as $key2 => $rowda) {
								if (($fetch_chk['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '2')) : $ans2[$a]++;
								endif;
							}
							foreach ($data3  as $key3 => $rowda) {
								if (($fetch_chk['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '3')) : $ans3[$a]++;
								endif;
							}
							foreach ($data4  as $key4 => $rowda) {
								if (($fetch_chk['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '4')) : $ans4[$a]++;
								endif;
							}
							foreach ($data5  as $key5 => $rowda) {
								if (($fetch_chk['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '5')) : $ans5[$a]++;
								endif;
							}

							$val1 = intval($ans1[$a]) * 1;
							$val2 = intval($ans2[$a]) * 2;
							$val3 = intval($ans3[$a]) * 3;
							$val4 = intval($ans4[$a]) * 4;
							$val5 = intval($ans5[$a]) * 5;
							$total_val = $val1 + $val2 + $val3 + $val4 + $val5;
							$total = $ans1[$a] + $ans2[$a] + $ans3[$a] + $ans4[$a] + $ans5[$a];
							$output = array();
							if ($total_val > 0 && $total > 0) {
								$calval = $total_val / $total;
							} else {
								$calval = 0;
							}
							$output['mean'] = $calval;
							$output['percent'] = (($calval) * 100) / 5;
							$output['total_val'] = $total;
							//print_r($data1);
							array_push($out_arr, $output);
							$a++;
						}
						$num = 0;
						foreach ($objQuery as $key_chkobj => $fetch_chk) { ?>
							<th><span style='float:right;'><?php echo number_format($out_arr[$num]['mean'], 2) . " : " . number_format($out_arr[$num]['percent'], 2) . " %" ?></span><?php $num++;
																																																																																			} ?>
							<th>_</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<script type="text/javascript">
			$('#myTable_detail').DataTable({
				"language": {
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
					"sInfo": "<?php echo label('sInfo'); ?>",
					"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
					"decimal": "",
					"emptyTable": "<?php echo label('wg_datanotfound'); ?>",
					"infoPostFix": "",
					"thousands": ",",
					//"lengthMenu":     "แสดง _MENU_ รายการ",
					"lengthMenu": "<?php echo label('lengthMenu'); ?>",
					"loadingRecords": "<?php echo label('loadingRecords'); ?>",
					"processing": "<?php echo label('processing'); ?>",
					"search": "<?php echo label('filter_bar'); ?>",
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"paginate": {
						"first": "<?php echo label('firstpage'); ?>",
						"last": "<?php echo label('last'); ?>",
						"next": "<?php echo label('lrn_btn_next'); ?>",
						"previous": "<?php echo label('previous'); ?>"
					},
				},
				"scrollX": true,
				dom: 'Bfrtip',
				buttons: [

					{
						extend: 'copyHtml5',
						header: true,
						footer: true,
						title: '<?php echo $lang == "thai" ? "แบบสำรวจสำหรับรายวิชา " : "Survey for courses "; ?><?php echo $cname; ?>',
						message: '<?php echo $sv_title; ?>',
					},
					{
						extend: 'excelHtml5',
						header: true,
						footer: true,
						title: '<?php echo $lang == "thai" ? "แบบสำรวจสำหรับรายวิชา " : "Survey for courses "; ?><?php echo $cname; ?>',
						message: '<?php echo $sv_title; ?>',
					},
					{
						extend: 'print',
						header: true,
						footer: true,
						title: '<?php echo $lang == "thai" ? "แบบสำรวจสำหรับรายวิชา " : "Survey for courses "; ?><?php echo $cname; ?>',
						message: '<?php echo $sv_title; ?>',
					}
				],
			});
		</script>
		<?php
	}

	public function updateSaveSVTC()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$output = array();
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		if (isset($_REQUEST['svde_id']) && countArray($_REQUEST['svde_id']) > 0) {
			for ($i = 0; $i < countArray($_REQUEST['svde_id']); $i++) {
				$svde_id = isset($_REQUEST['svde_id'][$i]) ? $_REQUEST['svde_id'][$i] : "";
				$tc_answer = isset($_REQUEST['tc_answer'][$i]) ? $_REQUEST['tc_answer'][$i] : "";
				$tc_note = isset($_REQUEST['tc_note'][$i]) ? $_REQUEST['tc_note'][$i] : "";
				$arr_data = array(
					'sv_id' => $sv_id,
					'svde_id' => $svde_id,
					'emp_id' => $sess['emp_id'],
					'tc_answer' => trim($tc_answer),
					'tc_note' => $tc_note,
					'tc_finish' => date('Y-m-d H:i'),
					'tc_flag' => 'true',
					'tc_save' => 'true'
				);
				$fetch_svdetc = $this->func_query->query_row('lms_svde_tc', '', '', '', 'svde_id="' . $svde_id . '" and emp_id="' . $sess['emp_id'] . '" and tc_isDelete="0"');
				$fetch_chk = $this->func_query->query_row('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and emp_id = "' . $sess['emp_id'] . '" and svtc_isDelete="0"');
				if (countArray($fetch_chk) == 0) {
					$arr_datamain = array(
						'sv_id' => $sv_id,
						'emp_id' => $sess['emp_id'],
						'svtc_firsttime' => date('Y-m-d H:i:s'),
						'svtc_createby' => $sess['u_id'],
						'svtc_createdate' => date('Y-m-d H:i:s'),
						'svtc_modifiedby' => $sess['u_id'],
						'svtc_modifieddate' => date('Y-m-d H:i:s')
					);
					$this->db->insert('lms_sv_tc', $arr_datamain);
				} else {
					if ($fetch_chk['svtc_firsttime'] == "0000-00-00 00:00:00") {
						$arr_update = array(
							'svtc_firsttime' => date('Y-m-d H:i:s'),
							'svtc_modifiedby' => $sess['u_id'],
							'svtc_modifieddate' => date('Y-m-d H:i:s')
						);
						$this->db->where('svtc_id', $fetch_chk['svtc_id']);
						$this->db->update('lms_sv_tc', $arr_update);
					}
				}

				if (countArray($fetch_svdetc) > 0) {
					$this->db->where('tc_id', $fetch_svdetc['tc_id']);
					$this->db->update('lms_svde_tc', $arr_data);
				} else {
					$this->db->insert('lms_svde_tc', $arr_data);
				}
			}
			$output['msg'] = "2";
		} else {
			$output['msg'] = "0";
		}

		echo json_encode($output);
	}

	public function updateSaveSVMainTC()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		if (isset($_REQUEST['svde_id']) && countArray($_REQUEST['svde_id']) > 0) {
			for ($i = 0; $i < countArray($_REQUEST['svde_id']); $i++) {
				$svde_id = isset($_REQUEST['svde_id'][$i]) ? $_REQUEST['svde_id'][$i] : "";
				$tc_answer = isset($_REQUEST['tc_answer'][$i]) ? $_REQUEST['tc_answer'][$i] : "";
				$tc_note = isset($_REQUEST['tc_note'][$i]) ? $_REQUEST['tc_note'][$i] : "";
				$arr_data = array(
					'sv_id' => $sv_id,
					'svde_id' => $svde_id,
					'emp_id' => $sess['emp_id'],
					'tc_answer' => $tc_answer,
					'tc_note' => $tc_note,
					'tc_finish' => date('Y-m-d H:i'),
					'tc_flag' => 'true',
					'tc_save' => 'true'
				);
				$fetch_svdetc = $this->func_query->query_row('lms_svde_tc', '', '', '', 'svde_id="' . $svde_id . '" and emp_id="' . $sess['emp_id'] . '" and tc_isDelete="0"');
				$fetch_chk = $this->func_query->query_row('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and emp_id = "' . $sess['emp_id'] . '" and svtc_isDelete="0"');
				if (countArray($fetch_chk) == 0) {
					$arr_datamain = array(
						'sv_id' => $sv_id,
						'emp_id' => $sess['emp_id'],
						'svtc_firsttime' => date('Y-m-d H:i'),
						'svtc_createby' => $sess['u_id'],
						'svtc_createdate' => date('Y-m-d H:i'),
						'svtc_modifiedby' => $sess['u_id'],
						'svtc_modifieddate' => date('Y-m-d H:i')
					);
					$this->db->insert('lms_sv_tc', $arr_datamain);
				} else {
					if ($fetch_chk['svtc_firsttime'] == "0000-00-00 00:00:00") {
						$arr_update = array(
							'svtc_firsttime' => date('Y-m-d H:i'),
							'svtc_modifiedby' => $sess['u_id'],
							'svtc_modifieddate' => date('Y-m-d H:i')
						);
						$this->db->where('svtc_id', $fetch_chk['svtc_id']);
						$this->db->update('lms_sv_tc', $arr_update);
					}
				}

				if (countArray($fetch_svdetc) > 0) {
					$this->db->where('tc_id', $fetch_svdetc['tc_id']);
					$this->db->update('lms_svde_tc', $arr_data);
				} else {
					$this->db->insert('lms_svde_tc', $arr_data);
				}
			}
			$fetch_chk = $this->func_query->query_row('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and emp_id = "' . $sess['emp_id'] . '" and svtc_isDelete="0"');
			if (countArray($fetch_chk) > 0) {
				$arr_datamain = array(
					'svtc_status' => '1',
					'svtc_finishtime' => date('Y-m-d H:i'),
					'svtc_modifiedby' => $sess['u_id'],
					'svtc_modifieddate' => date('Y-m-d H:i')
				);
				$this->db->where('svtc_id', $fetch_chk['svtc_id']);
				$this->db->update('lms_sv_tc', $arr_datamain);
				$output['msg'] = "2";
			}
			$output['msg'] = "2";
		} else {
			$output['msg'] = "0";
		}
		echo json_encode($output);
	}

	public function query_coursemain()
	{
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id = "' . $cos_id . '"');
		$result_tc = $this->func_query->query_row('lms_typecos', '', '', '', 'tc_id = "' . $result['tc_id'] . '"');
		$cos_lang = explode(',', $result['cos_lang']);
		$result['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
		$result['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
		$result['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
		$result['is_lang_user_th'] = '';
		$result['is_lang_user_eng'] = '';
		$result['is_lang_user_jp'] = '';
		$result['select_lang'] = '';
		$result['tc_courselearner'] = $result_tc['tc_courselearner'];
		$cname = "";
		if ($lang == "thai") {
			$result['select_lang'] = 'th';
			$result['is_lang_user_th'] = 'selected';
			if ($result['isTH'] == "1") {
				$cname = $result['cname_th'];
			} else {
				if ($result['cname_th'] == "") {
					$cname = $result['cname_eng'];
				}
				if ($cname == "") {
					$cname = $result['cname_jp'];
				}
			}
		} else if ($lang == "english") {
			$result['select_lang'] = 'eng';
			$result['is_lang_user_eng'] = 'selected';
			if ($result['isENG'] == "1") {
				$cname = $result['cname_eng'];
			} else {
				if ($result['cname_eng'] == "") {
					$cname = $result['cname_th'];
				}
				if ($cname == "") {
					$cname = $result['cname_jp'];
				}
			}
		} else {
			$result['select_lang'] = 'jp';
			$result['is_lang_user_jp'] = 'selected';
			if ($result['isJP'] == "1") {
				$cname = $result['cname_jp'];
			} else {
				if ($result['cname_jp'] == "") {
					$cname = $result['cname_eng'];
				}
				if ($cname == "") {
					$cname = $result['cname_th'];
				}
			}
		}
		$fetch_seat = $this->func_query->numrows('lms_cos_enroll', '', '', '', 'cos_id="' . $cos_id . '" and cosen_isDelete="0"');
		$result['isseatFull'] = "0";
		if (intval($result['seat_count']) > 0 && $fetch_seat >= intval($result['seat_count'])) {
			$result['isseatFull'] = "1";
		}
		$txt_period_course = label('UnlimitedTime');
		$datetime_now = date('Y-m-d H:i');
		$where = 'cos_id = "' . $cos_id . '" and cosde_isDelete="0" and cosde_status="1"';
		$result_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', $where, 'cosde_id DESC');
		if (countArray($result_detail) > 0) {
			$txt_period_course = lms_format_period_range($result_detail['date_start'], $result_detail['date_end'], $lang);
		}
		$numqiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_isDelete="0"');
		$result['isQiz'] = $numqiz > 0 ? 1 : 0;
		$fetch_chk = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id = "' . $cos_id . '" and cosde_isDelete="0" and cosde_status="1"', 'cosde_id DESC');
		if (countArray($fetch_chk) > 0) {
			$result['isData_period'] = "1";
			$result['cosde_id'] = $fetch_chk['cosde_id'];
		} else {
			$result['isData_period'] = "0";
		}
		$result['cname_main'] = $cname;
		$result['txt_period_course'] = $txt_period_course;
		
		$isCanEdit = false;
		if ($result['cos_approve']=="1" || $result['cos_public']=="1") {
			// $fetchCheckCGINCOS = $this->func_query->query_result(
			//   "lms_cosincg",
			//   "lms_cog",
			//   "lms_cosincg.cg_id = lms_cog.cg_id", "",
			//   "lms_cosincg.course_id = ".$cos_id
			// );
			// if (!empty($fetchCheckCGINCOS) && $result['cos_approve']!="1") {
			//   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
			// 	  $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
			// 	  if (!empty($cgApproveBy)) {
			// 		for ($i = 0; $i < countArray($cgApproveBy); $i++) {
			// 		  if ($user['u_id'] == $cgApproveBy[$i]) {
			// 			$isCanEdit = true;
			// 		  }
			// 		}
			// 	  }
			//   }
			// }
			if ($user['u_id'] == "1") {
			  $isCanEdit = true;
			}
		} else {
		  $isCanEdit = true;
		}
		$result['isCanEdit'] = $isCanEdit;

		echo json_encode($result);
		exit();
	}

	public function select_lang_lesson()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$les_lang = isset($_REQUEST['les_lang']) ? $_REQUEST['les_lang'] : "";
		$result = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

		$output = array();
		$cos_lang = explode(',', $result['cos_lang']);
		$output['arr_lang'] = $cos_lang;
		$output['val_lang'] = $result['cos_lang'];
		echo json_encode($output);
	}

	public function select_lang_qn()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$qn_id = isset($_REQUEST['qn_id']) ? $_REQUEST['qn_id'] : "";
		$result = $this->func_query->query_row('lms_questionnaire', '', '', '', 'qn_id="' . $qn_id . '"');

		$output = array();
		$qn_lang = explode(',', $result['qn_lang']);
		$output['arr_lang'] = $qn_lang;
		$output['val_lang'] = $result['qn_lang'];
		echo json_encode($output);
	}

	public function select_lang_qizex()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$qize_id = isset($_REQUEST['qize_id']) ? $_REQUEST['qize_id'] : "";
		$result = $this->func_query->query_row('lms_qiz_exp', '', '', '', 'qize_id="' . $qize_id . '"');

		$output = array();
		$qize_lang = explode(',', $result['qize_lang']);
		$output['arr_lang'] = $qize_lang;
		$output['val_lang'] = $result['qize_lang'];
		echo json_encode($output);
	}

	public function select_lang_survey()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		$result = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');

		$output = array();
		$sv_lang = explode(',', $result['sv_lang']);
		$output['arr_lang'] = $sv_lang;
		$output['sv_isHeader'] = $result['sv_isHeader'];
		echo json_encode($output);
	}

	public function select_lang_cosvideo()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$cosv_lang = isset($_REQUEST['cosv_lang']) ? $_REQUEST['cosv_lang'] : "";
		$result = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

		$cos_lang = explode(',', $result['cos_lang']);
		print_r($_REQUEST);
		if (countArray($cos_lang) > 0) {

			echo "<optgroup label='" . label('Chooselang') . "'>";
			foreach ($cos_lang as $key) {
				$select_val = "";
				if ($key == $cosv_lang) {
					$select_val = "selected";
				}
				if ($key == "th") {
					echo "<option value='" . $key . "' " . $select_val . ">" . label('thailand') . "</option>";
				} else if ($key == "eng") {
					echo "<option value='" . $key . "' " . $select_val . ">" . label('english') . "</option>";
				} else {
					echo "<option value='" . $key . "' " . $select_val . ">" . label('japan') . "</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}

	public function query_status_cos()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		date_default_timezone_set("Asia/Bangkok");
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
		$sess = $this->session->userdata("user");
		$date_now = date('Y-m-d H:i');
		if ($type == "1") {
			$where = '';
			if ($com_id != "") {
				$where = ' and lms_cos.com_id = "' . $com_id . '"';
			}
			$courses_total = $this->func_query->query_result('lms_cos', '', '', '', 'cos_approve="1" and cos_public="1" and cos_isDelete="0"' . $where);

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
					if (countArray($fetch_chk_ug) > 0) {
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
		} else {
			$courses_total = $this->func_query->query_result('lms_cos', '', '', '', 'cos_approve="1" and cos_public="1" and cos_status="1" and cos_isDelete="0"');
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
				foreach ($courses_total as $key_list => $value_list) {
					$fetch_status = $this->func_query->query_row('lms_cos_enroll', '', '', '', 'cos_id="' . $value_list['cos_id'] . '" and emp_id="' . $sess['emp_id'] . '" and cosen_isDelete="0"');
					if (countArray($fetch_status) == 0) {
						$fetch_chk_ug = $this->func_query->query_row('lms_cos_detail', 'lms_cos_detail_ug', 'lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id', '', 'lms_cos_detail_ug.posi_id = "' . $sess['posi_id'] . '" and lms_cos_detail.cos_id = "' . $value_list['cos_id'] . '"');
						if (countArray($fetch_chk_ug) == 0) {
							unset($courses_total[$key_list]);
						} else {
							if ($fetch_chk_ug['date_start'] != "0000-00-00 00:00:00" && $fetch_chk_ug['date_end'] != "0000-00-00 00:00:00") {
								if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) > date('Y-m-d H:i')) {
									$courses_incoming++;
								}
								if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) <= date('Y-m-d H:i') && date('Y-m-d H:i', strtotime($fetch_chk_ug['date_end'])) >= date('Y-m-d H:i')) {
									$courses_ongoing++;
								}
							} else {
								$courses_ongoing++;
							}
						}
					} else {
						// $fetch_chk_ug = $this->func_query->query_row('lms_cos_detail','lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id','','lms_cos_detail_ug.posi_id = "'.$sess['posi_id'].'" and lms_cos_detail.cos_id = "'.$value_list['cos_id'].'"');
						$fetch_chk_ug = $this->func_query->query_row('lms_cos_detail', '', '', '', 'lms_cos_detail.cos_id = "' . $value_list['cos_id'] . '"');
						if ($fetch_status['cosen_status'] == "1" && $fetch_status['cosen_status_sub'] == "1") {
							/*if(($fetch_chk_ug['date_start']=="0000-00-00 00:00:00"&&$fetch_chk_ug['date_end']=="0000-00-00 00:00:00")||(date('Y-m-d H:i',strtotime($fetch_chk_ug['date_start']))<=date('Y-m-d H:i')&&date('Y-m-d H:i',strtotime($fetch_chk_ug['date_end']))>=date('Y-m-d H:i'))){
              }*/
							if (countArray($fetch_chk_ug) > 0) {
								if (($fetch_chk_ug['date_start'] == "0000-00-00 00:00:00" && $fetch_chk_ug['date_end'] == "0000-00-00 00:00:00") || (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_end'])) >= date('Y-m-d H:i') && date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) <= date('Y-m-d H:i'))) {

									$courses_completed++;
								}
							} else {
								$courses_completed++;
							}
						} else {
							if (countArray($fetch_chk_ug) > 0) {
								if ($fetch_chk_ug['date_start'] != "0000-00-00 00:00:00" && $fetch_chk_ug['date_end'] != "0000-00-00 00:00:00") {
									if (date('Y-m-d H:i', strtotime($fetch_chk_ug['date_start'])) > date('Y-m-d H:i')) {
										$courses_incoming++;
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
				}
			}
			$courses_total = $courses_ongoing + $courses_incoming + $courses_completed;
			//$courses_total = $courses_ongoing+$courses_incoming;
		}
		$output = array();
		$output['courses_total'] = $courses_total;
		$output['courses_ongoing'] = $courses_ongoing;
		$output['courses_incoming'] = $courses_incoming;
		$output['courses_completed'] = $courses_completed;
		$output['courses_close'] = $courses_close;
		echo json_encode($output);
	}

	public function rechk_lang_lesson()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$les_id = isset($_REQUEST['les_id']) ? $_REQUEST['les_id'] : "";
		$result = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

		$cos_lang = explode(',', $result['cos_lang']);
		$result['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
		$result['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
		$result['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";

		echo json_encode($result);
		exit();
	}

	public function rechk_course_period()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_cos', 'lms_cos_detail', 'lms_cos.cos_id = lms_cos_detail.cos_id', '', 'lms_cos.cos_id="' . $cos_id . '"');

		if (countArray($result) > 0 && $result['date_start'] != "0000-00-00 00:00:00" && date('Y-m-d', strtotime($result['date_start'])) < date('Y-m-d')) {
			$result['isApprove'] = "0";
		} else {
			$result['isApprove'] = "1";
		}

		echo json_encode($result);
		exit();
	}

	public function rechk_qiz()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";

		$result = $this->func_query->query_row('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_isDelete = 0');
		echo json_encode($result);
		exit();
	}

	public function rechk_survey_period()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		$result = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
		$result['isApprove'] = "1";
		if (countArray($result) > 0 && $result['sv_open'] != "0000-00-00 00:00:00" && date('Y-m-d', strtotime($result['sv_open'])) < date('Y-m-d')) {
			$result['isApprove'] = "0";
		}

		echo json_encode($result);
		exit();
	}

	public function update_cert_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id = "' . $cos_id . '"');

		echo json_encode($result);
		exit();
	}

	public function update_course_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$cosde_id = isset($_REQUEST['cosde_id']) ? $_REQUEST['cosde_id'] : "";
		$result = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cosde_id = "' . $cosde_id . '"');
		if (countArray($result) > 0) {
			if (!empty($result['date_start']) && $result['date_start'] != "0000-00-00 00:00:00") {
				$result['date_start_var'] = $result['date_start'];
				$result['time_start'] = date('H:i', strtotime($result['date_start']));
				$result['date_start'] = date('d/m/Y', strtotime($result['date_start']));
			} else {
				$result['date_start'] = "";
				$result['time_start'] = "00:00";
				$result['date_start_var'] = "";
			}

			if (!empty($result['date_end']) && $result['date_end'] != "0000-00-00 00:00:00") {
				$result['date_end_var'] = $result['date_end'];
				$result['time_end'] = date('H:i', strtotime($result['date_end']));
				$result['date_end'] = date('d/m/Y', strtotime($result['date_end']));
			} else {
				$result['date_end'] = "";
				$result['time_end'] = "23:59";
				$result['date_end_var'] = "";
			}
		} else {
			$result['date_start'] = "";
			$result['time_start'] = "00:00";
			$result['date_start_var'] = "";

			$result['date_end'] = "";
			$result['time_end'] = "23:59";
			$result['date_end_var'] = "";
		}

		$result_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id = "' . $result['cos_id'] . '"');
		$result['date_start_condition'] = "";
		if ($result_cos['condition'] != "") {
			$var_cos = "";
			$condition = explode(',', $result_cos['condition']);
			if (countArray($condition) > 0) {
				$fetch_chk_con = $this->func_query->query_row('lms_cos', 'lms_cos_detail', 'lms_cos_detail.cos_id = lms_cos.cos_id', '', 'lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_status="1" and lms_cos.cos_isDelete="0" and lms_cos.cos_id in (' . $result_cos['condition'] . ') and lms_cos_detail.date_end!="0000-00-00 00:00:00"', 'lms_cos_detail.date_end DESC');
				if (countArray($fetch_chk_con) > 0) {
					$result['date_start_condition'] = date('d/m/Y', strtotime($fetch_chk_con['date_end'] . " +1day"));
				}
			}
		}
		echo json_encode($result);
		exit();
	}

	public function query_course_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id = "' . $cos_id . '" and cosde_isDelete="0"');
		if (countArray($result) > 0) {
			if (!empty($result['date_start']) && $result['date_start'] != "0000-00-00 00:00:00") {
				$result['date_start_var'] = date('Y-m-d', strtotime($result['date_start']));
				$result['time_start'] = date('H:i', strtotime($result['date_start']));
				$result['date_start'] = date('d/m/Y', strtotime($result['date_start']));
				$result['isData'] = "1";
			} else {
				$result['date_start'] = "";
				$result['time_start'] = "00:00";
				$result['date_start_var'] = "";
				$result['isData'] = "0";
			}

			if (!empty($result['date_end']) && $result['date_end'] != "0000-00-00 00:00:00") {
				$result['date_end_var'] = date('Y-m-d', strtotime($result['date_end']));
				$result['time_end'] = date('H:i', strtotime($result['date_end']));
				$result['date_end'] = date('d/m/Y', strtotime($result['date_end']));
				$result['isData'] = "1";
			} else {
				$result['isData'] = "0";
				$result['date_end'] = "";
				$result['time_end'] = "23:59";
				$result['date_end_var'] = "";
			}
		} else {
			$result['isData'] = "0";
			$result['date_start'] = "";
			$result['time_start'] = "00:00";
			$result['date_start_var'] = "";

			$result['date_end'] = "";
			$result['time_end'] = "23:59";
			$result['date_end_var'] = "";
		}
		echo json_encode($result);
		exit();
	}


	public function update_quiz_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		$this->func_query->loadDB();
		$qiz_id = isset($_REQUEST['qiz_id']) ? $_REQUEST['qiz_id'] : "";
		$result = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id = "' . $qiz_id . '"');
		$fetch_ques = $this->func_query->numrows('lms_ques', '', '', '', 'qiz_id="' . $qiz_id . '" and ques_isDelete="0"');
		//$result_ques = $this->course->recheck_total('lms_ques','qiz_id',$_REQUEST['qiz_id'],'');
		$result['result_ques'] = $fetch_ques;
		if ($result['period_open'] != "0000-00-00 00:00:00") {
			$result['time_start'] = date('H:i', strtotime($result['period_open']));
			$result['period_open_var'] = $result['period_open'];
			$result['period_open'] = date('d/m/Y', strtotime($result['period_open']));
		} else {
			$result['period_open'] = "";
			$result['time_start'] = "00:00";
			$result['period_open_var'] = "";
		}

		if ($result['period_end'] != "0000-00-00 00:00:00") {
			$result['time_end'] = date('H:i', strtotime($result['period_end']));
			$result['period_end_var'] = $result['period_end'];
			$result['period_end'] = date('d/m/Y', strtotime($result['period_end']));
		} else {
			$result['period_end'] = "";
			$result['time_end'] = "23:59";
			$result['period_end_var'] = "";
		}
		echo json_encode($result);
		exit();
	}


	public function update_survey_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['sv_id_update'], 'lms_survey', 'sv_id');
			if ($result['survey_open'] != "0000-00-00 00:00:00") {
				$result['time_start'] = date('H:i', strtotime($result['survey_open']));
				$result['survey_open_var'] = $result['survey_open'];
				$result['survey_open'] = date('d/m/Y', strtotime($result['survey_open']));
			} else {
				$result['survey_open_var'] = "";
				$result['survey_open'] = "";
				$result['time_start'] = "00:00";
			}
			if ($result['survey_end'] != "0000-00-00 00:00:00") {
				$result['time_end'] = date('H:i', strtotime($result['survey_end']));
				$result['survey_end_var'] = $result['survey_end'];
				$result['survey_end'] = date('d/m/Y', strtotime($result['survey_end']));
			} else {
				$result['survey_end_var'] = "";
				$result['survey_end'] = "";
				$result['time_end'] = "23:59";
			}
			if (isset($_REQUEST['type'])) {
				if ($_REQUEST['lang_select'] == "thai") {
					$sv_title = $result['sv_title_th'] != "" ? $result['sv_title_th'] : $result['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_jp'];
					$sv_explanation = $result['sv_explanation_th'] != "" ? $result['sv_explanation_th'] : $result['sv_explanation_eng'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result['sv_explanation_jp'];
				} else if ($_REQUEST['lang_select'] == "english") {
					$sv_title = $result['sv_title_eng'] != "" ? $result['sv_title_eng'] : $result['sv_title_th'];
					$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_jp'];
					$sv_explanation = $result['sv_explanation_eng'] != "" ? $result['sv_explanation_eng'] : $result['sv_explanation_th'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result['sv_explanation_jp'];
				} else {
					$sv_title = $result['sv_title_jp'] != "" ? $result['sv_title_jp'] : $result['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_th'];
					$sv_explanation = $result['sv_explanation_jp'] != "" ? $result['sv_explanation_jp'] : $result['sv_explanation_eng'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result['sv_explanation_th'];
				}
				$result['sv_title'] = $sv_title;
				$result['sv_explanation'] = $sv_explanation;
			}
		}
		echo json_encode($result);
		exit();
	}

	public function update_survey_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->manage->query_data_onupdate($_REQUEST['sv_id'], 'lms_sv', 'sv_id');
			if ($result['sv_open'] != "0000-00-00 00:00:00") {
				$result['time_start'] = date('H:i', strtotime($result['sv_open']));
				$result['sv_open_var'] = $result['sv_open'];
				$result['sv_open'] = date('d/m/Y', strtotime($result['sv_open']));
			} else {
				$result['sv_open_var'] = "";
				$result['sv_open'] = "";
				$result['time_start'] = "00:00";
			}
			if ($result['sv_end'] != "0000-00-00 00:00:00") {
				$result['time_end'] = date('H:i', strtotime($result['sv_end']));
				$result['sv_end_var'] = $result['sv_end'];
				$result['sv_end'] = date('d/m/Y', strtotime($result['sv_end']));
			} else {
				$result['sv_end_var'] = "";
				$result['sv_end'] = "";
				$result['time_end'] = "23:59";
			}
			if ($lang == "thai") {
				$sv_title = $result['sv_title_th'] != "" ? $result['sv_title_th'] : $result['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_jp'];
			} else if ($lang == "english") {
				$sv_title = $result['sv_title_eng'] != "" ? $result['sv_title_eng'] : $result['sv_title_th'];
				$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_jp'];
			} else {
				$sv_title = $result['sv_title_jp'] != "" ? $result['sv_title_jp'] : $result['sv_title_eng'];
				$sv_title = $sv_title != "" ? $sv_title : $result['sv_title_th'];
			}
			$result['sv_titlename'] = $sv_title;

			$sv_lang = explode(',', $result['sv_lang']);
			$result['isTH'] = in_array('th', $sv_lang) ? "1" : "0";
			$result['isENG'] = in_array('eng', $sv_lang) ? "1" : "0";
			$result['isJP'] = in_array('jp', $sv_lang) ? "1" : "0";
		}
		echo json_encode($result);
		exit();
	}

	public function update_score_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_row('lms_cug', '', '', '', 'course_id = "' . $cos_id . '"');

		echo json_encode($result);
		exit();
	}

	public function update_cosvideo_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cosv_id = isset($_REQUEST['cosv_id']) ? $_REQUEST['cosv_id'] : "";
		$result = $this->func_query->query_row('lms_cos_video', '', '', '', 'cosv_id = "' . $cosv_id . '"');

		echo json_encode($result);
		exit();
	}

	public function update_cosdoc_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$fil_cos_id = isset($_REQUEST['fil_cos_id']) ? $_REQUEST['fil_cos_id'] : "";
		$result = $this->func_query->query_row('lms_cos_fil', '', '', '', 'fil_cos_id = "' . $fil_cos_id . '"');

		echo json_encode($result);
		exit();
	}

	public function query_lesson()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('Lesson_model', 'lesson', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$les_id = isset($_REQUEST['les_id']) ? $_REQUEST['les_id'] : "";
		$status_study = isset($_REQUEST['status_study']) ? $_REQUEST['status_study'] : "";
		$result = $this->func_query->query_row('lms_les', '', '', '', 'les_id = "' . $les_id . '"');
		if ($status_study == "1" && isset($sess['emp_id'])) {
			$num_tc = $this->func_query->numrows('lms_les_tc', '', '', '', 'emp_id = "' . $sess['emp_id'] . '" and les_id = "' . $les_id . '"');
			if ($num_tc == 0) {
				$arr_lestc = array(
					'emp_id' => $sess['emp_id'],
					'les_id' => $_REQUEST['les_id_update'],
					'learn_status' => '1'
				);
				$this->lesson->createTC($arr_lestc);
			}
			$this->course->firsttime_les($result['cos_id']);
		}
		$num_fil = $this->func_query->numrows('lms_fil', '', '', '', 'lessons_id = "' . $les_id . '"');

		$result['num_fil'] = $num_fil;
		if ($result['time_start'] != "0000-00-00 00:00:00") {
			$result['time_start_les'] = date('H:i', strtotime($result['time_start']));
			$result['date_start_les_var'] = $result['time_start'];
			$result['time_start'] = date('d/m/Y', strtotime($result['time_start']));
		} else {
			$result['time_start'] = "";
			$result['time_start_les'] = "00:00";
			$result['date_start_les_var'] = "";
		}

		if ($result['time_end'] != "0000-00-00 00:00:00") {
			$result['time_end_les'] = date('H:i', strtotime($result['time_end']));
			$result['date_end_les_var'] = $result['time_end'];
			$result['time_end'] = date('d/m/Y', strtotime($result['time_end']));
		} else {
			$result['time_end'] = "";
			$result['time_end_les'] = "23:59";
			$result['date_end_les_var'] = "";
		}
		if ($result['les_type'] == "1") {
			$url = $this->query_data_arr($les_id, 'lms_med', 'type', 'url');
			$result['upload'] = $this->query_data_arr($les_id, 'lms_med', 'type', 'upload');
			$result['document'] = $this->query_data_arr($les_id, 'lms_fil', '', '');
			$result['url'] = "";
			if (countArray($url) > 0) {
				$num_url = 0;
				foreach ($url as $key => $value) {
					$result['url'] .= $value['video'];
					$num_url++;
					if ($num_url < countArray($url)) {
						$result['url'] .= ",";
					}
				}
			}
		} else {
			$result['scorm'] = $this->func_query->query_row('lms_scm', '', '', '', 'lessons_id="' . $les_id . '"');
		}
		echo json_encode($result);
		exit();
	}


	public function query_data_arr($id, $datatable, $fieldname, $type)
	{
		$this->db->from($datatable);
		if ($type != "") {
			$this->db->where($fieldname, $type);
		}
		$this->db->where('lessons_id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function recheckcompany()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$result = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_name_th!="Verztec"');
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('please_com_name') . "'>";
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if ($key['com_id'] == $com_id) {
					$select_val = "selected";
				}
				$com_code = "";
				if ($key['com_code'] != "") {
					$com_code = " (" . $key['com_code'] . ")";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['com_id'] . "' " . $select_val . ">" . $key['com_name_th'] . $com_code . "</option>";
				} else {
					echo "<option value='" . $key['com_id'] . "' " . $select_val . ">" . $key['com_name_eng'] . $com_code . "</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckcompanyForDuplicateCourse()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		
		$user = $this->session->userdata('user');
		if (isset($user["com_id"])) {
			$where = "com_isDelete = 0 and com_name_th != 'Verztec'";
			$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : $user["com_id"];
			if (in_array($user["ug_name_th"], array("Instructor", "Gr.Com Admin"))) {
				$where .= " and com_id = ".$user["com_id"];
			}
			$result = $this->func_query->query_result(
				"lms_company", "", "", "",
				$where
			);
			if (countArray($result) > 0) {
				echo "<optgroup label='" . label('please_com_name') . "'>";
				$numloop = 1;
				foreach ($result as $key) {
					$select_val = "";
					if ($key['com_id'] == $com_id) {
						$select_val = "selected";
					}
					$com_code = "";
					if ($key['com_code'] != "") {
						$com_code = " (" . $key['com_code'] . ")";
					}
					if ($lang == "thai") {
						echo "<option value='" . $key['com_id'] . "' " . $select_val . ">" . $key['com_name_th'] . $com_code . "</option>";
					} else {
						echo "<option value='" . $key['com_id'] . "' " . $select_val . ">" . $key['com_name_eng'] . $com_code . "</option>";
					}
				}
				$this->func_query->closeDB();
				echo "</optgroup>";
			} else {
				echo '<option value="">' . label('wg_datanotfound') . '</option>';
			}
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckInstructor() {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$user = $this->session->userdata('user');
		$uId = isset($_GET['uId']) ? $_GET['uId'] : "";
		$comId = isset($_GET['comId']) && !checkValueIsNullTypeNumber($_GET['comId']) ? $_GET['comId'] : $user["com_id"];

		$arrCompanys = array();
		$resultCompanys = $this->func_query->query_result(
			"lms_company", "", "", "", "", "",
			"com_id, com_code"
		);
		if (!empty($resultCompanys)) {
			foreach ($resultCompanys as $keyCompany) {
				$arrCompanys[$keyCompany["com_id"]] = $keyCompany["com_code"];
			}
		}


		$where = 'emp_isDelete="0" and ug_id in (select lms_usp_gp.ug_id from lms_usp_gp where ug_name_th in ("IMAT Super Admin", "Gr.Com Admin", "Instructor")
					 and ug_isDelete = 0) and lms_emp.emp_id not in (1, 1805) and lms_usp.u_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
		if (!checkValueIsNullTypeNumber($comId)) {
			$where .= ' and lms_emp.com_id = '.$comId;
		}
		$result = $this->func_query->query_result(
			'lms_emp',
			'lms_usp',
			'lms_emp.emp_id = lms_usp.emp_id', '',
			$where, '',
			'lms_usp.u_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en'
		);
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			foreach ($result as $key) {
				if (isset($arrCompanys[$key["com_id"]])) {
					$selectVal = "";
					if ($key['u_id'] == $uId) {
						$selectVal = "selected";
					}
					echo "<option value='" . $key['u_id'] . "' " . $selectVal . ">" . 
							($lang == "thai" ? $key['fullname_th'] : $key['fullname_en'])." [".$arrCompanys[$key["com_id"]] . "]</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckcompanyforcourse()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$result = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_name_th!="Verztec" and com_id in (select lms_emp.com_id FROM lms_emp where emp_id in (select lms_cos_enroll.emp_id from lms_cos_enroll where lms_cos_enroll.cos_id = "' . $cos_id . '"))');
		if (countArray($result) > 0) {
			echo '<option value="" selected>' . label('allcompany') . '</option>';
			$numloop = 1;
			foreach ($result as $key) {
				$com_code = "";
				if ($key['com_code'] != "") {
					$com_code = " (" . $key['com_code'] . ")";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['com_id'] . "'>" . $key['com_name_th'] . $com_code . "</option>";
				} else {
					echo "<option value='" . $key['com_id'] . "'>" . $key['com_name_eng'] . $com_code . "</option>";
				}
			}
			$this->func_query->closeDB();
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckCompanyForLogImportUsers()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$lgi_id = isset($_REQUEST['lgi_id']) ? $_REQUEST['lgi_id'] : "";
		$result = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_id in (select lms_emp.com_id FROM lms_emp where emp_id in (select lms_lg_import_detail.emp_id from lms_lg_import_detail where lms_lg_import_detail.lgi_id = "' . $lgi_id . '"))');
		if (countArray($result) > 0) {
			echo '<option value="" selected>' . label('allcompany') . '</option>';
			$numloop = 1;
			foreach ($result as $key) {
				$com_code = "";
				if ($key['com_code'] != "") {
					$com_code = " (" . $key['com_code'] . ")";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['com_id'] . "'>" . $key['com_name_th'] . $com_code . "</option>";
				} else {
					echo "<option value='" . $key['com_id'] . "'>" . $key['com_name_eng'] . $com_code . "</option>";
				}
			}
			$this->func_query->closeDB();
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckcompany_optionreport()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$com_admin = isset($_REQUEST['com_admin']) ? $_REQUEST['com_admin'] : "";
		$result = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_name_th!="Verztec" and com_admin="' . $com_admin . '"');
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('please_com_name') . "'>";
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if ($key['com_id'] == $com_id) {
					$select_val = "selected";
				}
				$com_code = "";
				if ($key['com_code'] != "") {
					$com_code = " (" . $key['com_code'] . ")";
				}
				$com_name = $lang == "thai" ? $key['com_name_th'] . $com_code : $key['com_name_eng'] . $com_code;
				echo "<option value='" . $key['com_id'] . "' " . $select_val . ">" . $com_name . "</option>";
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function recheckdepart_optionreport()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$dep_id = isset($_REQUEST['dep_id']) ? $_REQUEST['dep_id'] : "";
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$result = $this->func_query->query_result('lms_depart', '', '', '', 'dep_isDelete="0" and dep_status="1" and com_id="' . $com_id . '"');
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('svplease') . "'>";
			echo '<option value="" selected>' . label('r_company') . '</option>';
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if ($key['dep_id'] == $dep_id) {
					$select_val = "selected";
				}
				$dep_name = $lang == "thai" ? $key['dep_name_th'] . $com_code : $key['dep_name_en'];
				echo "<option value='" . $key['dep_id'] . "' " . $select_val . ">" . $dep_name . "</option>";
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function option_coursegroups()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$result = $this->func_query->query_result('lms_cog', '', '', '', 'com_id="' . $com_id . '" and cg_approve="1" and cg_isDelete="0" and cg_status="1"');
		if (countArray($result) > 0) {
			echo '<option value="" selected>' . label('allcoursegroup') . '</option>';
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if ($lang == "thai") {
					$cgtitle = $key['cgtitle_th'] != "" ? $key['cgtitle_th'] : $key['cgtitle_en'];
					$cgtitle = $cgtitle != "" ? $cgtitle : $key['cgtitle_jp'];
				} else if ($lang == "english") {
					$cgtitle = $key['cgtitle_en'] != "" ? $key['cgtitle_en'] : $key['cgtitle_th'];
					$cgtitle = $cgtitle != "" ? $cgtitle : $key['cgtitle_jp'];
				} else {
					$cgtitle = $key['cgtitle_jp'] != "" ? $key['cgtitle_jp'] : $key['cgtitle_en'];
					$cgtitle = $cgtitle != "" ? $cgtitle : $key['cgtitle_th'];
				}
				echo "<option value='" . $key['cg_id'] . "'>" . $cgtitle . "</option>";
			}
			$this->func_query->closeDB();
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	private function validate_quiz_answer_upload($question, $file)
	{
		if (!isset($file) || !isset($file['tmp_name']) || $file['tmp_name'] == "" || !is_uploaded_file($file['tmp_name'])) {
			return array('status' => true, 'data' => array());
		}

		if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $file['name'])) {
			return array('status' => false, 'message' => 'invalid_filename');
		}

		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$image_extensions = array('gif', 'jpg', 'jpeg', 'png');
		$video_extensions = array('mp4', 'webm', 'ogv', 'mov');
		$upload_type = isset($question['ques_upload_type']) ? $question['ques_upload_type'] : 'both';
		$allowed_extensions = $upload_type == 'image' ? $image_extensions : ($upload_type == 'video' ? $video_extensions : array_merge($image_extensions, $video_extensions));

		if (!in_array($extension, $allowed_extensions)) {
			return array('status' => false, 'message' => 'invalid_extension');
		}

		$max_mb = isset($question['ques_upload_max_mb']) ? intval($question['ques_upload_max_mb']) : 10;
		$max_mb = $max_mb > 0 ? $max_mb : 10;
		if (intval($file['size']) > ($max_mb * 1024 * 1024)) {
			return array('status' => false, 'message' => 'file_too_large');
		}

		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $file['tmp_name']);
			finfo_close($finfo);
			if (
				(in_array($extension, $image_extensions) && strpos($mime_type, 'image/') !== 0) ||
				(in_array($extension, $video_extensions) && strpos($mime_type, 'video/') !== 0 && $mime_type !== 'application/octet-stream')
			) {
				return array('status' => false, 'message' => 'invalid_mime');
			}
		}

		$upload_path = ROOT_DIR . "uploads/quiz_answer/";
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0775, true);
		}

		$file_type = in_array($extension, $video_extensions) ? 'video' : 'image';
		$file_name = "quiz_answer_" . date('YmdHis') . "_" . uniqid() . "." . $extension;
		if (!audit_move_uploaded_file($file['tmp_name'], $upload_path . $file_name)) {
			return array('status' => false, 'message' => 'upload_failed');
		}

		return array(
			'status' => true,
			'data' => array(
				'tc_upload_file' => $file_name,
				'tc_upload_original' => $file['name'],
				'tc_upload_type' => $file_type
			)
		);
	}

	private function build_quiz_answer_upload_data($question, $existing_answer, $file_key)
	{
		$existing_file = isset($existing_answer['tc_upload_file']) ? $existing_answer['tc_upload_file'] : "";
		$has_new_file = isset($_FILES[$file_key]) && isset($_FILES[$file_key]['tmp_name']) && $_FILES[$file_key]['tmp_name'] != "";

		if (isset($question['ques_upload_required']) && $question['ques_upload_required'] == "1" && !$has_new_file && $existing_file == "") {
			return array('status' => false, 'message' => 'required_upload');
		}

		if ($has_new_file) {
			return $this->validate_quiz_answer_upload($question, $_FILES[$file_key]);
		}

		return array('status' => true, 'data' => array());
	}

	public function save_question($qiz_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$output = array();
		/*$ques_id = isset($_REQUEST['ques_id'])?$_REQUEST['ques_id']:"";
    $qiz_id = isset($_REQUEST['qiz_id'])?$_REQUEST['qiz_id']:"";
    $answer = isset($_REQUEST['answer'])?$_REQUEST['answer']:"";
    $cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
    $score = isset($_REQUEST['score'])?$_REQUEST['score']:"0";*/
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$type_qiz = isset($_REQUEST['type_qiz' . $qiz_id]) ? $_REQUEST['type_qiz' . $qiz_id] : "";
		if ($type_qiz != "" && isset($sess['emp_id'])) {
			$cosen_id = isset($_REQUEST['cosen_id' . $qiz_id]) ? $_REQUEST['cosen_id' . $qiz_id] : "";
			$tc_answer = isset($_REQUEST['tc_answer_' . $type_qiz . $qiz_id]) ? $_REQUEST['tc_answer_' . $type_qiz . $qiz_id] : "";
			$ques_id = isset($_REQUEST['ques_id_' . $type_qiz . $qiz_id]) ? $_REQUEST['ques_id_' . $type_qiz . $qiz_id] : "";
			if (countArray($ques_id) > 0 && $cosen_id != "") {
				$qiztc_id = "";
				$fetch_chk = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $qiz_id . '" and cosen_id="' . $cosen_id . '" and qiztc_isDelete="0" and time_finish="0000-00-00 00:00:00"', 'qiztc_id DESC');
				if (countArray($fetch_chk) > 0) {
					$qiztc_id = $fetch_chk['qiztc_id'];
				} else {
					$fetch_loop = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $qiz_id . '" and cosen_id="' . $cosen_id . '" and qiztc_isDelete="0"', 'limit_val DESC');
					$limit_val = countArray($fetch_loop) > 0 ? intval($fetch_loop['limit_val']) + 1 : 1;
					$arr_main = array(
						'qiz_id' => $qiz_id,
						'emp_id' => $sess['emp_id'],
						'time_start' => date('Y-m-d H:i'),
						'time_mod' => date('Y-m-d H:i'),
						'qiz_status' => '1',
						'limit_val' => $limit_val,
						'cosen_id' => $cosen_id
					);
					$this->db->insert('lms_qiz_tc', $arr_main);
					$qiztc_id = $this->db->insert_id();
				}
				if ($qiztc_id != "") {
					for ($ques = 0; $ques < countArray($ques_id); $ques++) {
						$tc_score = 0;
						$fetch_ques_upload = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $ques_id[$ques] . '"');
						$fetch_ques = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $ques_id[$ques] . '" and ques_type in ("multi","2choice")');
						if (countArray($fetch_ques) > 0) {
							$fetch_quesmulti = $this->func_query->query_row('lms_ques_mul', '', '', '', 'ques_id="' . $ques_id[$ques] . '"');
							if (countArray($fetch_quesmulti) > 0) {
								$mul_answer = explode(',', $fetch_quesmulti['mul_answer']);
								if (in_array($tc_answer[$ques], $mul_answer)) {
									$tc_score = floatval($fetch_ques['ques_score']);
								}
							}
						}
						$fetch_chk_ques = $this->func_query->query_row('lms_ques_tc', '', '', '', 'qiztc_id="' . $qiztc_id . '" and cosen_id="' . $cosen_id . '" and qiz_id="' . $qiz_id . '" and ques_id="' . $ques_id[$ques] . '"');
						$file_key = 'tc_upload_' . $type_qiz . $qiz_id . '_' . $ques_id[$ques];
						$upload_result = $this->build_quiz_answer_upload_data($fetch_ques_upload, $fetch_chk_ques, $file_key);
						if (!$upload_result['status']) {
							$output['status'] = "4";
							$output['message'] = $upload_result['message'];
							echo json_encode($output);
							return;
						}
						$valtc_answer = isset($tc_answer[$ques]) ? $tc_answer[$ques] : "";
						$has_upload_answer = (isset($fetch_chk_ques['tc_upload_file']) && $fetch_chk_ques['tc_upload_file'] != "") || countArray($upload_result['data']) > 0;
						$has_answer = $valtc_answer != "" || $has_upload_answer;
						$arr_main = array(
							'qiztc_id' => $qiztc_id,
							'qiz_id' => $qiz_id,
							'ques_id' => $ques_id[$ques],
							'emp_id' => $sess['emp_id'],
							'tc_answer' => $valtc_answer,
							'tc_finish' => $has_answer ? date('Y-m-d H:i') : "0000-00-00 00:00:00",
							'tc_flag' => $has_answer ? 'true' : 'false',
							'tc_save' => $has_answer ? 'true' : 'false',
							'tc_score' => $tc_score,
							'cosen_id' => $cosen_id,
						);
						if (countArray($upload_result['data']) > 0) {
							$arr_main = array_merge($arr_main, $upload_result['data']);
						}
						if (countArray($fetch_chk_ques) > 0) {
							$this->db->where('tc_id', $fetch_chk_ques['tc_id']);
							$this->db->update('lms_ques_tc', $arr_main);
						} else {
							$this->db->insert('lms_ques_tc', $arr_main);
						}
					}
					$output['status'] = "2";
				} else {
					$output['status'] = "0";
				}
			} else {
				$output['status'] = "0";
			}
		} else {
			$output['status'] = "0";
		}
		/*$fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$qiz_id.'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0" and time_finish="0000-00-00 00:00:00"','qiztc_id DESC');
    $qiztc_id = "";
    if(countArray($fetch_chk)>0){
      $qiztc_id = $fetch_chk['qiztc_id'];
    }else{
      $fetch_loop = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$qiz_id.'" and emp_id="'.$sess['emp_id'].'" and qiztc_isDelete="0"','limit_val DESC');
      $limit_val = countArray($fetch_loop)>0?intval($fetch_loop['limit_val'])+1:1;
      $arr_main = array(
        'qiz_id' => $qiz_id,
        'emp_id' => $sess['emp_id'],
        'time_start' => date('Y-m-d H:i'),
        'time_mod' => date('Y-m-d H:i'),
        'qiz_status' => '1',
        'limit_val' => $limit_val,
        'cosen_id' => $cosen_id
      );
      $this->db->insert('lms_qiz_tc',$arr_main);
      $qiztc_id = $this->db->insert_id();
    }
    if($qiztc_id!=""){
      $fetch_chk_ques = $this->func_query->query_row('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and emp_id="'.$sess['emp_id'].'" and qiz_id="'.$qiz_id.'" and ques_id="'.$ques_id.'"');

      $arr_main = array(
          'qiztc_id' => $qiztc_id,
          'qiz_id' => $qiz_id,
          'ques_id' => $ques_id,
          'emp_id' => $sess['emp_id'],
          'tc_answer' => $answer,
          'tc_finish' => date('Y-m-d H:i'),
          'tc_flag' => 'true',
          'tc_save' => 'true',
          'tc_score' => $score,
          'cosen_id' => $cosen_id,
      );
      if(countArray($fetch_chk_ques)>0){
        $this->db->where('tc_id',$fetch_chk_ques['tc_id']);
        $this->db->update('lms_ques_tc',$arr_main);
      }else{
        $this->db->insert('lms_ques_tc',$arr_main);
      }
      $output['status'] = "2";
    }else{
      $output['status'] = "0";
    }*/
		echo json_encode($output);
	}

	public function send_question($qiz_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$output = array();
		$score = 0;

		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$type_qiz = isset($_REQUEST['type_qiz' . $qiz_id]) ? $_REQUEST['type_qiz' . $qiz_id] : "";
		if ($type_qiz != "" && isset($sess['emp_id'])) {
			$cosen_id = isset($_REQUEST['cosen_id' . $qiz_id]) ? $_REQUEST['cosen_id' . $qiz_id] : "";
			$tc_answer = isset($_REQUEST['tc_answer_' . $type_qiz . $qiz_id]) ? $_REQUEST['tc_answer_' . $type_qiz . $qiz_id] : "";
			$ques_id = isset($_REQUEST['ques_id_' . $type_qiz . $qiz_id]) ? $_REQUEST['ques_id_' . $type_qiz . $qiz_id] : "";
			if (countArray($ques_id) > 0 && $cosen_id != "") {
				$qiztc_id = "";
				$fetch_chk = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $qiz_id . '" and cosen_id="' . $cosen_id . '" and qiztc_isDelete="0" and time_finish="0000-00-00 00:00:00"', 'qiztc_id DESC');
				if (countArray($fetch_chk) > 0) {
					$qiztc_id = $fetch_chk['qiztc_id'];
				} else {
					$fetch_loop = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $qiz_id . '" and cosen_id="' . $cosen_id . '" and qiztc_isDelete="0"', 'limit_val DESC');
					$limit_val = countArray($fetch_loop) > 0 ? intval($fetch_loop['limit_val']) + 1 : 1;
					$arr_main = array(
						'qiz_id' => $qiz_id,
						'emp_id' => $sess['emp_id'],
						'time_start' => date('Y-m-d H:i'),
						'time_mod' => date('Y-m-d H:i'),
						'qiz_status' => '1',
						'limit_val' => $limit_val,
						'cosen_id' => $cosen_id
					);
					$this->db->insert('lms_qiz_tc', $arr_main);
					$qiztc_id = $this->db->insert_id();
				}
				if ($qiztc_id != "") {
					$score_sum = 0;
					$score_per = 0;
					$score = 0;
					for ($ques = 0; $ques < countArray($ques_id); $ques++) {
						$tc_score = 0;
						$fetch_ques_upload = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $ques_id[$ques] . '"');
						$fetch_ques = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $ques_id[$ques] . '" and ques_type in ("multi","2choice")');
						if (countArray($fetch_ques) > 0) {
							$score += floatval($fetch_ques['ques_score']);
							$fetch_quesmulti = $this->func_query->query_row('lms_ques_mul', '', '', '', 'ques_id="' . $ques_id[$ques] . '"');
							if (countArray($fetch_quesmulti) > 0) {
								$mul_answer = explode(',', $fetch_quesmulti['mul_answer']);
								if (in_array($tc_answer[$ques], $mul_answer)) {
									$tc_score = floatval($fetch_ques['ques_score']);
									$score_sum += floatval($tc_score);
								}
							}
						}
						$fetch_chk_ques = $this->func_query->query_row('lms_ques_tc', '', '', '', 'qiztc_id="' . $qiztc_id . '" and cosen_id="' . $cosen_id . '" and qiz_id="' . $qiz_id . '" and ques_id="' . $ques_id[$ques] . '"');
						$file_key = 'tc_upload_' . $type_qiz . $qiz_id . '_' . $ques_id[$ques];
						$upload_result = $this->build_quiz_answer_upload_data($fetch_ques_upload, $fetch_chk_ques, $file_key);
						if (!$upload_result['status']) {
							$output['status'] = "4";
							$output['message'] = $upload_result['message'];
							echo json_encode($output);
							return;
						}
						$valtc_answer = isset($tc_answer[$ques]) ? $tc_answer[$ques] : "";
						$has_upload_answer = (isset($fetch_chk_ques['tc_upload_file']) && $fetch_chk_ques['tc_upload_file'] != "") || countArray($upload_result['data']) > 0;
						$has_answer = $valtc_answer != "" || $has_upload_answer;
						$arr_main = array(
							'qiztc_id' => $qiztc_id,
							'qiz_id' => $qiz_id,
							'ques_id' => $ques_id[$ques],
							'emp_id' => $sess['emp_id'],
							'tc_answer' => $valtc_answer,
							'tc_finish' => $has_answer ? date('Y-m-d H:i') : "0000-00-00 00:00:00",
							'tc_flag' => $has_answer ? 'true' : 'false',
							'tc_save' => $has_answer ? 'true' : 'false',
							'tc_score' => $tc_score,
							'cosen_id' => $cosen_id,
						);
						if (countArray($upload_result['data']) > 0) {
							$arr_main = array_merge($arr_main, $upload_result['data']);
						}
						if (countArray($fetch_chk_ques) > 0) {
							$this->db->where('tc_id', $fetch_chk_ques['tc_id']);
							$this->db->update('lms_ques_tc', $arr_main);
						} else {
							$this->db->insert('lms_ques_tc', $arr_main);
						}
					}

					if ($score_sum > 0) {
						$score_per = ($score_sum / $score) * 100;
					} else {
						if ($score == 0) {
							$score_per = 100;
						}
					}

					$arr_update = array(
						'time_mod' => date('Y-m-d H:i'),
						'time_finish' => date('Y-m-d H:i'),
						'sum_score' => $score_sum,
						'per_score' => $score_per,
						'qiz_status' => '3',
					);
					$this->db->where('qiztc_id', $qiztc_id);
					$this->db->where('cosen_id', $cosen_id);
					$this->db->update('lms_qiz_tc', $arr_update);

					$output['isCert'] = "0";
					$fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $qiz_id . '"');
					if (countArray($fetch_qiz) > 0) {
						$fetch_chkbad = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $fetch_qiz['cos_id'] . '"');
						$fetch_chkcos = $this->func_query->query_row('lms_cos', 'lms_cug', 'lms_cug.course_id = lms_cos.cos_id', '', 'lms_cos.cos_id="' . $fetch_qiz['cos_id'] . '"');
						$fetch_qizall = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $fetch_qiz['cos_id'] . '" and quiz_type="2" and quiz_isDelete="0"');
						$num_qiztc = 0;
						$isSaSub = 0;
						$scoreall = 0;
						if (countArray($fetch_qizall) > 0) {
							$score_real = 0;
							$score_cos = 0;
							foreach ($fetch_qizall as $key_qizall => $value_qizall) {
								$fetch_isSa = $this->func_query->numrows('lms_ques', '', '', '', 'qiz_id="' . $value_qizall['qiz_id'] . '" and ques_isDelete="0" and ques_type in ("sa","sub")', '');
								if ($fetch_isSa > 0) {
									$isSaSub++;
								}
								$fetch_chk_tc = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $value_qizall['qiz_id'] . '" and cosen_id="' . $cosen_id . '" and qiztc_isDelete="0"', 'qiztc_id DESC');

								$fetch_chk_ques = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_qizall['qiz_id'] . '" and ques_isDelete="0" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where cosen_id="' . $cosen_id . '" and qiztc_id = "' . $fetch_chk_tc['qiztc_id'] . '")', '', 'sum(lms_ques.ques_score) as total_score');
								$scoreall += floatval($fetch_chk_ques['total_score']);

								$num_loop = $this->func_query->numrows('lms_qiz_tc', '', '', '', 'qiz_id="' . $value_qizall['qiz_id'] . '" and emp_id="' . $sess['emp_id'] . '" and qiztc_isDelete="0" and qiz_status="3" and cosen_id="' . $cosen_id . '"');
								if (countArray($fetch_chk_tc) > 0) {
									$score_percos = floatval($fetch_chk_ques['total_score']) > 0 ? floatval($fetch_chk_tc['sum_score']) / floatval($fetch_chk_ques['total_score']) * 100 : 100;
									if ($fetch_chk_tc['qiz_status'] == "3") {
										// &&floatval($score_percos)>=floatval($value_qizall['quiz_maxscore'])
										$score_real += floatval($fetch_chk_tc['sum_score']);
									}
									if ($value_qizall['quiz_limit'] == "1") {
										$quiz_limitval = intval($value_qizall['quiz_limitval']);
										if ($num_loop >= $quiz_limitval) {
											if ($fetch_chk_tc['qiz_status'] == "3") {
												$num_qiztc++;
											}
										} else {
											if (floatval($score_percos) >= floatval($value_qizall['quiz_maxscore'])) {
												if ($fetch_chk_tc['qiz_status'] == "3") {
													$num_qiztc++;
												}
											}
										}
									} else {
										if (floatval($score_percos) >= floatval($value_qizall['quiz_maxscore'])) {
											if ($fetch_chk_tc['qiz_status'] == "3") {
												$num_qiztc++;
											}
										}
									}
								}
							}
							if ($isSaSub == 0) {
								$score_cos = floatval($scoreall) > 0 ? floatval($score_real) / floatval($scoreall) * 100 : 100;
								if (countArray($fetch_chkcos) > 0) {
									if (countArray($fetch_chkbad) > 0) {
										if ($fetch_chkbad['badges_condition'] == "P") {
											$score_pass = floatval($fetch_chkcos['mina']);
										} else {
											if ($fetch_chkbad['badges_condition'] == "A") {
												$score_pass = floatval($fetch_chkcos['mina']);
											} else if ($fetch_chkbad['badges_condition'] == "B") {
												$score_pass = floatval($fetch_chkcos['minb']);
											} else if ($fetch_chkbad['badges_condition'] == "C") {
												$score_pass = floatval($fetch_chkcos['minc']);
											} else if ($fetch_chkbad['badges_condition'] == "D") {
												$score_pass = floatval($fetch_chkcos['mind']);
											} else {
												$score_pass = 0;
											}
										}
										if ($score_cos >= $score_pass && countArray($fetch_chkbad) > 0) {
											$output['isCert'] = "1";
										}
									}
									$output['result_cos'] = 'fail';
									if ($score_cos >= floatval($fetch_chkcos['goal_score'])) {
										$output['result_cos'] = 'pass';
									}
									$output['resultscorecos'] = number_format($score_cos) . "%";
								} else {
									$output['resultscorecos'] = number_format($score_cos) . "%";
								}
							}
						}

						$fetch_isSa = $this->func_query->numrows('lms_ques', '', '', '', 'qiz_id="' . $qiz_id . '" and ques_isDelete="0" and ques_type in ("sa","sub")', '');
						if ($fetch_isSa > 0) {
							$isSaSub++;
						}
						$output['result'] = 'fail';
						if ($score_per >= floatval($fetch_qiz['quiz_maxscore'])) {
							$output['result'] = 'pass';
						}
						if ($scoreall == 0) {
							$output['resultscorecos'] = "";
							$output['result_cos'] = 'fail';
							if ($score_per >= floatval($fetch_qiz['quiz_maxscore'])) {
								$output['result_cos'] = 'pass';
							}
						}

						$output['quiz_grade'] = $fetch_qiz['quiz_grade'];
						$output['score'] = number_format($score_sum) . "/" . $score;
						$output['isSaSub'] = $isSaSub;
						if ($num_qiztc >= countArray($fetch_qizall)) {

							$isLast = "1";
							$fetchLesson = $this->func_query->numrows(
								'lms_les',
								'',
								'',
								'',
								'les_isDelete="0" and les_status="1" and cos_id="' . $fetch_qiz['cos_id'] . '"'
							);

							if ($fetchLesson > 0) {
								$fetchLessonTC = $this->func_query->numrows(
									'lms_les_tc',
									'',
									'',
									'',
									'learn_status="2" and cosen_id="' . $cosen_id . '"'
								);
								if ($fetchLesson > $fetchLessonTC) {
									$isLast = "0";
								}
							}
							$output['is_last'] = $isLast;
							if ($isSaSub == 0 && $isLast == "1") {
								// ตรวจสอบว่าหลักสูตรบังคับทำ survey หรือไม่
								$require_survey = 0;
								$has_survey = 1; // เริ่มต้น assume ว่าทำครบ
								$survey_id = null;

								if (!empty($fetch_qiz['cos_id'])) {
									if (isset($fetch_chkcos['is_survey_required']) && intval($fetch_chkcos['is_survey_required']) === 1) {
										$require_survey = 1;
										
										// ดึงแบบสำรวจทั้งหมดที่ผูกกับคอร์ส
										$fetch_survey_arr = $this->func_query->query_result(
											'lms_survey',
											'',
											'',
											'',
											'cos_id="' . $fetch_qiz['cos_id'] . '" and sv_isDelete="0" and sv_status="1"',
											'sv_id ASC'
										);

										if (countArray($fetch_survey_arr) > 0) {
											foreach ($fetch_survey_arr as $survey) {
												$chk_survey_done = $this->func_query->query_row(
													'lms_qn_user',
													'',
													'',
													'',
													'sv_id="'.$survey['sv_id'].'" and cosen_id="'.$cosen_id.'" and qnu_status="1"'
												);

												if (empty($chk_survey_done)) {
													// เจอแบบสำรวจที่ยังไม่ได้ทำ
													$has_survey = 0;
													$survey_id = $survey['sv_id'];
													break;
												}
											}
										}
									}
								}

								$output['require_survey'] = $require_survey;
								$output['has_survey'] = $has_survey;
								$output['survey_id'] = $survey_id;
								$this->endcos($fetch_qiz['cos_id']);

								
            					$fetch_enroll = $this->func_query->query_row(
									'lms_cos_enroll','','','',
									'cosen_id="'.$cosen_id.'"'
								);
								if (isset($fetch_enroll["cosen_lang"])) {
									$lang_select = !checkValueIsNullTypeString($fetch_enroll["cosen_lang"]) ? $fetch_enroll["cosen_lang"] : $lang;
									if ($lang_select=="thai") { 
										$quiz_name = $fetch_qiz['quiz_name_th']!=""?$fetch_qiz['quiz_name_th']:$fetch_qiz['quiz_name_eng'];
										$quiz_name = $quiz_name!=""?$quiz_name:$fetch_qiz['quiz_name_jp'];
										$quiz_info = $fetch_qiz['quiz_info_th']!=""?$fetch_qiz['quiz_info_th']:$fetch_qiz['quiz_info_eng'];
										$quiz_info = $quiz_info!=""?$quiz_info:$fetch_qiz['quiz_info_jp'];
									} else if ($lang_select=="english") { 
										$quiz_name = $fetch_qiz['quiz_name_eng']!=""?$fetch_qiz['quiz_name_eng']:$fetch_qiz['quiz_name_th'];
										$quiz_name = $quiz_name!=""?$quiz_name:$fetch_qiz['quiz_name_jp'];
										$quiz_info = $fetch_qiz['quiz_info_eng']!=""?$fetch_qiz['quiz_info_eng']:$fetch_qiz['quiz_info_th'];
										$quiz_info = $quiz_info!=""?$quiz_info:$fetch_qiz['quiz_info_jp'];
									} else {
										$quiz_name = $fetch_qiz['quiz_name_jp']!=""?$fetch_qiz['quiz_name_jp']:$fetch_qiz['quiz_name_eng'];
										$quiz_name = $quiz_name!=""?$quiz_name:$fetch_qiz['quiz_name_th'];
										$quiz_info = $fetch_qiz['quiz_info_jp']!=""?$fetch_qiz['quiz_info_jp']:$fetch_qiz['quiz_info_eng'];
										$quiz_info = $quiz_info!=""?$quiz_info:$fetch_qiz['quiz_info_th'];
									}
									$output['quiz_name'] = $quiz_name;
								}
							}
						} else {
							$output['is_last'] = "0";
							$output['isCert'] = "0";
						}
					}
					$output['status'] = "2";
				} else {
					$output['status'] = "0:1871";
				}
			} else {
				$output['status'] = "0:1874";
			}
		} else {
			$output['status'] = "0:1877";
		}

		echo json_encode($output);
	}

	public function get_latest_status_after_survey($cos_id)
	{
		$sess = $this->session->userdata("user");
		
		// โหลด model ที่ใช้
		$this->load->model('Course_model', 'course', true);

		// 2. ตรวจสอบว่าได้ทำแบบสำรวจหลักของ course แล้วหรือยัง
		$hasSurvey = $this->course->has_completed_survey($cos_id, $sess['emp_id']);

		// 3. ตรวจสอบว่ามีแบบสำรวจถัดไปหรือไม่
		$nextSurvey = $this->course->get_next_required_survey($cos_id, $sess['emp_id']);

		// 4. ตรวจสอบสิทธิ์ใบประกาศ
		$isCert = $this->course->can_show_certificate($cos_id, $sess['emp_id']);

		// ส่ง response
		echo json_encode([
			'status' 				=> '1',
			'has_survey' 			=> $hasSurvey ? '1' : '0',
			'isCert' 				=> $isCert ? '1' : '0',
			'next_survey_id' 		=> isset($nextSurvey['sv_id']) ? $nextSurvey['sv_id'] : '',
			'require_next_survey' 	=> isset($nextSurvey['sv_id']) ? '1' : '0'
		]);
	}

	public function endcos($cos_id)
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$this->func_query->loadDB();
		$fetch_chkcos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
		if (countArray($fetch_chkcos) > 0 && isset($sess['emp_id'])) {
			$isFailed = 0;
			$fetch_enroll = $this->func_query->query_row('lms_cos_enroll', '', '', '', 'cos_id="' . $cos_id . '" and emp_id="' . $sess['emp_id'] . '" and cosen_status="1" and cosen_status_sub!="1" and cosen_lang!="" and cosen_isDelete="0"', 'cosen_id DESC');
			if (countArray($fetch_enroll) > 0) {
				$cosen_id = $fetch_enroll['cosen_id'];
				$status_cos = 0;
				$amount_les = 0;
				$amount_qiz = 0;
				$score = 0;
				$total = 0;
				$fetch_qiz = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_type="2" and quiz_show="1" and quiz_status="1" and quiz_isDelete="0"');
				$num_chk_qiz = 0;
				$numloopqiz = 0;
				$numloopqizpass = 0;
				if (countArray($fetch_qiz) > 0) {
					// ตรวจสอบคะแนน และผลการผ่านในแบบทดสอบ
					foreach ($fetch_qiz as $key_qiz => $value_qiz) {
						$fetch_chk = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $value_qiz['qiz_id'] . '" and emp_id="' . $sess['emp_id'] . '" and qiz_status="3" and cosen_id="' . $cosen_id . '"', 'qiztc_id DESC');
						if (countArray($fetch_chk) > 0) {
							if ($value_qiz['quiz_limit'] == "1") {
								if ($fetch_chk['limit_val'] <= intval($value_qiz['quiz_limitval'])) {
									if (floatval($fetch_chk['per_score']) >= floatval($value_qiz['quiz_maxscore'])) {
										$numloopqizpass++;
									} else {
										if ($fetch_chk['limit_val'] == intval($value_qiz['quiz_limitval'])) {
											$numloopqizpass++;
										} else {
											$isFailed++;
										}
									}
								}
							} else {
								if (floatval($fetch_chk['per_score']) >= floatval($value_qiz['quiz_maxscore'])) {
									$numloopqizpass++;
								} else {
									$isFailed++;
								}
							}
						}
						$numloopqiz++;
						$score_total = 0;
						$fetch_chk = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiz_id="' . $value_qiz['qiz_id'] . '" and qiz_status="3" and cosen_id="' . $cosen_id . '"', 'qiztc_id DESC');
						if (countArray($fetch_chk) > 0) {
							$amount_qiz++;
							$fetch_questc = $this->func_query->query_result('lms_ques_tc', '', '', '', 'qiz_id="' . $value_qiz['qiz_id'] . '" and cosen_id="' . $cosen_id . '" and qiztc_id="' . $fetch_chk['qiztc_id'] . '"');
							if (countArray($fetch_questc) == intval($value_qiz['quiz_numofshown'])) {
								$num_chk_qiz++;
							}

							// คะแนนที่ผู้เรียนทำได้ทั้งหมด
							$score += countArray($fetch_chk) > 0 ? floatval($fetch_chk['sum_score']) : 0;
						}
						$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_qiz['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_qiz['qiz_id'] . '" and cosen_id="' . $cosen_id . '" and qiztc_id="' . $fetch_chk['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
						// คะแนนในแต่ละคำถามทั้งหมด
						$total += countArray($fetch_sum) > 0 ? floatval($fetch_sum['total_score']) : 0;
					}
				}
				// ตรวจสอบในกรณีที่วิชานั้นมีบทเรียน จะต้องเรียนเสร็จสิ้นทุกบทเรียน
				$fetch_lesson = $this->func_query->query_result('lms_les', '', '', '', 'cos_id="' . $cos_id . '" and les_isDelete="0" and les_status="1"');
				if (countArray($fetch_lesson) > 0) {
					foreach ($fetch_lesson as $key_lesson => $value_lesson) {
						$fetch_lestc = $this->func_query->query_row('lms_les_tc', '', '', '', 'les_id="' . $value_lesson['les_id'] . '" and cosen_id="' . $cosen_id . '"');
						if (countArray($fetch_lestc) > 0) {
							if ($fetch_lestc['learn_status'] == "2") {
								$amount_les++;
							}
						}
					}
				}
				$cosen_grade = "";
				$cosen_score = 0;
				$cosen_score_per = 0;

				$cosen_status_sub = '2';
				$cosen_finishtime = '0000-00-00 00:00:00';

				// กรณีที่มีคะแนนรวม มากกว่า 0 แสดงว่ามีแบบทดสอบ
				if ($total > 0) {
					if ($score >= 0 && $total > 0) {
						$cosen_score = $score;
						$cosen_score_per = ($score / $total) * 100;
						$fetch_cug = $this->func_query->query_row('lms_cug', '', '', '', 'course_id="' . $cos_id . '"');
						if (countArray($fetch_cug) > 0) {
							if ($fetch_chkcos['cos_typegrading'] == "1") {
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
							} else {
								if ($cosen_score_per >= floatval($fetch_cug['mina'])) {
									$cosen_grade = "P";
								} else {
									$cosen_grade = "F";
								}
							}
						}
						if (floatval($cosen_score_per) >= floatval($fetch_chkcos['goal_score'])) {
							$cosen_status_sub = 1; // debug 100
							$cosen_finishtime = date('Y-m-d H:i');
						} else {
							if ($numloopqizpass == $numloopqiz) {
								$cosen_status_sub = 1; // debug 200
								$cosen_finishtime = date('Y-m-d H:i');
							} else {
								$cosen_status_sub = 2;
							}
						}
					}
				} else {
					// กรณีที่วิชานั้นไม่มีแบบทดสอบ
					$cosen_score = 100;
					$cosen_score_per = 100;
					$cosen_status_sub = 1; // debug 300
					$cosen_finishtime = date('Y-m-d H:i');

					$fetch_cug = $this->func_query->query_row('lms_cug', '', '', '', 'course_id="' . $cos_id . '"');
					if (countArray($fetch_cug) > 0) {
						if ($fetch_chkcos['cos_typegrading'] == "1") {
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
						} else {
							if ($cosen_score_per >= floatval($fetch_cug['mina'])) {
								$cosen_grade = "P";
							} else {
								$cosen_grade = "F";
							}
						}
					}
				}
				$val_cosen = 0;
				$total_couse = 0;


				$fetch_les = $this->func_query->numrows('lms_les', '', '', '', 'les_isDelete="0" and les_status="1" and cos_id="' . $cos_id . '"');
				$fetch_lestc = $this->func_query->numrows('lms_les_tc', '', '', '', 'learn_status="2" and cosen_id="' . $cosen_id . '"');
				$fetch_qiz = $this->func_query->numrows('lms_qiz', '', '', '', 'quiz_isDelete="0" and quiz_show="1" and cos_id="' . $cos_id . '"');
				// $fetch_qiztc = $this->func_query->numrows('lms_qiz_tc','','','','qiz_status="3" and cosen_id="'.$cosen_id.'"');
				$fetch_sv = $this->func_query->numrows('lms_survey','','','','sv_isDelete="0" and sv_status="1" and cos_id="'.$cos_id.'"');
				$fetch_svtc = $this->func_query->numrows('lms_qn_user','','','','qnu_status="1" and cosen_id="'.$cosen_id.'"');
				// ตรวจสอบจำนวนของการทำบทเรียนว่าเสร็จสิ้นทั้งหมด 
				if ($fetch_les > 0) {
					$total_couse++;
					if ($fetch_les <= $fetch_lestc) {
						$val_cosen++;
					}
				}
				// ตรวจสอบจำนวนของการทำแบบทดสอบว่าเสร็จสิ้นทั้งหมด 
				if ($fetch_qiz > 0) {
					$arrQizId = array();
					$fetch_qiz_query = $this->func_query->query_result('lms_qiz', '', '', '', 'quiz_isDelete="0" and quiz_show="1" and cos_id="' . $cos_id . '"');
					if (countArray($fetch_qiz_query) > 0) {
						foreach ($fetch_qiz_query as $key_qiz_query => $value_qiz_query) {
							$total_couse++;
							$numcheck_qiz = $this->func_query->numrows('lms_qiz_tc', '', '', '', 'cosen_id="' . $cosen_id . '"', 'qiztc_id DESC');
							$numcheck_qizpass = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'cosen_id="' . $cosen_id . '" and qiz_id = "' . $value_qiz_query['qiz_id'] . '"', 'qiztc_id DESC');
							$fetch_chksh_lg = $this->func_query->numrows('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_qiz_query['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" and ques_type in ("sub","sa") and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where cosen_id="' . $cosen_id . '" and qiztc_id = "' . $numcheck_qizpass['qiztc_id'] . '")');
							if ($fetch_chksh_lg > 0) {
								$isEndTest = 1;
								if ($value_qiz_query['quiz_limit'] == "1") {
									if ($numcheck_qiz < intval($value_qiz_query['quiz_limitval'])) {
										if (floatval($numcheck_qizpass['per_score']) < floatval($value_qiz_query['quiz_maxscore'])) {
											$isEndTest = 0;
										}
									}
								} else {
									if (floatval($numcheck_qizpass['per_score']) < floatval($value_qiz_query['quiz_maxscore'])) {
										$isEndTest = 0;
									}
								}
								if (countArray($numcheck_qizpass) > 0 && $numcheck_qizpass['qiz_status'] == "3" && $isEndTest == 1) {
									$fetch_chktc_sa = $this->func_query->numrows('lms_ques_tc', '', '', '', 'cosen_id="' . $cosen_id . '" and qiztc_id = "' . $numcheck_qizpass['qiztc_id'] . '" and lms_ques_tc.ques_id in (select lms_ques.ques_id from lms_ques where lms_ques.qiz_id="' . $value_qiz_query['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" and ques_type in ("sub","sa"))');
									if ($fetch_chktc_sa >= $fetch_chksh_lg) {
										if (!in_array($value_qiz_query['qiz_id'], $arrQizId)) {
											array_push($arrQizId, $value_qiz_query['qiz_id']);
											$val_cosen++;
										}
									}
								}
							}
							$fetch_chksh_lg_notsub = $this->func_query->numrows('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_qiz_query['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" and ques_type not in ("sub","sa") and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where cosen_id="' . $cosen_id . '" and qiztc_id = "' . $numcheck_qizpass['qiztc_id'] . '")');
							if ($fetch_chksh_lg_notsub > 0) {
								$isEndTest = 1;
								if ($value_qiz_query['quiz_limit'] == "1") {
									if ($numcheck_qiz < intval($value_qiz_query['quiz_limitval'])) {
										if (floatval($numcheck_qizpass['per_score']) < floatval($value_qiz_query['quiz_maxscore'])) {
											$isEndTest = 0;
										}
									}
								} else {
									if (floatval($numcheck_qizpass['per_score']) < floatval($value_qiz_query['quiz_maxscore'])) {
										$isEndTest = 0;
									}
								}
								if (countArray($numcheck_qizpass) > 0 && $numcheck_qizpass['qiz_status'] == "3" && $isEndTest == 1) {
									$fetch_chktc_sa = $this->func_query->numrows('lms_ques_tc', '', '', '', 'cosen_id="' . $cosen_id . '" and qiztc_id = "' . $numcheck_qizpass['qiztc_id'] . '" and lms_ques_tc.ques_id in (select lms_ques.ques_id from lms_ques where lms_ques.qiz_id="' . $value_qiz_query['qiz_id'] . '" and ques_status="1" and ques_isDelete="0" and ques_type not in ("sub","sa"))');
									if ($fetch_chktc_sa >= $fetch_chksh_lg_notsub) {
										if (!in_array($value_qiz_query['qiz_id'], $arrQizId)) {
											array_push($arrQizId, $value_qiz_query['qiz_id']);
											$val_cosen++;
										}
									}
								}
							}
						}
					}
				}
				if ($total_couse == $val_cosen) {
					if ($cosen_finishtime != "0000-00-00 00:00:00" && $cosen_finishtime != "") {
						$fetch_bad = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $cos_id . '"');
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
							$cosen_score_per = round($cosen_score_per);
							// ออกใบประกาศนีย์บัตร
							if ($cosen_score_per >= $score_pass) {

								$this->course->update_cert($cos_id, $sess);
							}
						}
					}

					if ($isFailed == 0) {
						$cosen_status_sub = 1; // debug 400
						$cosen_finishtime = date('Y-m-d H:i');

						// ตรวจสอบว่าหลักสูตรนี้บังคับทำแบบสำรวจหรือไม่
						if(intval($fetch_chkcos['is_survey_required']) === 1){
							// ตรวจสอบว่าผู้เรียนทำแบบสำรวจหรือยัง

							if ($fetch_sv != $fetch_svtc) {
								// ยังไม่ทำแบบสำรวจ → ห้ามจบ
								$cosen_status_sub = 2; // ยังไม่จบ
								$cosen_finishtime = '0000-00-00 00:00:00';
								$cosen_score = 0;
								$cosen_score_per = 0;
								$cosen_grade = '';
							}
						}
					} else {
						$cosen_status_sub = 2;
					}
				} else {
					// เรียนไม่จบ
					$cosen_grade = '';
					$cosen_score = 0;
					$cosen_score_per = 0;
					$cosen_status_sub = 2;
					$cosen_finishtime = '0000-00-00 00:00:00';
				}
				$cosen_round = intval($fetch_enroll['cosen_round']);

				$arr_update = array(
					'cosen_grade' => $cosen_grade,
					'cosen_score' => $cosen_score,
					'cosen_score_per' => $cosen_score_per,
					'cosen_status_sub' => $cosen_status_sub,
					'cosen_finishtime' => $cosen_finishtime,
					'cosen_modifiedby' => $sess['u_id'],
					'cosen_modifieddate' => date('Y-m-d H:i')
				);
				$this->db->where('cosen_id', $fetch_enroll['cosen_id']);
				$this->db->update('lms_cos_enroll', $arr_update);
			}
		}
	}

	public function recheckcondition()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$condition = isset($_REQUEST['condition']) ? explode(',', $_REQUEST['condition']) : "";
		$where = 'cos_public="1" and cos_approve="1" and cos_isDelete="0" and com_id="' . $com_id . '"';
		if ($cos_id != "") {
			$where .= ' and cos_id != "' . $cos_id . '"';
		}
		$result = $this->func_query->query_result('lms_cos', '', '', '', $where);
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('none') . "'>";
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if (in_array($key['cos_id'], $condition)) {
					$select_val = "selected";
				}
				$ccode = "";
				if ($key['ccode'] != "") {
					$ccode = " (" . $key['ccode'] . ")";
				}
				if ($lang == "thai") {
					$cname = $key['cname_th'];
					if ($cname == "") {
						$cname = $key['cname_eng'];
					}
					if ($cname == "") {
						$cname = $key['cname_jp'];
					}
					echo "<option value='" . $key['cos_id'] . "' " . $select_val . ">" . $cname . $ccode . "</option>";
				} else {
					$cname = $key['cname_eng'];
					if ($cname == "") {
						$cname = $key['cname_th'];
					}
					if ($cname == "") {
						$cname = $key['cname_jp'];
					}
					echo "<option value='" . $key['cos_id'] . "' " . $select_val . ">" . $cname . $ccode . "</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('none') . '</option>';
		}
	}

	public function rechecktypecos()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$tc_id = isset($_REQUEST['tc_id']) ? $_REQUEST['tc_id'] : "";
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$result = $this->func_query->query_result('lms_typecos', '', '', '', 'tc_status="1" and com_id ="' . $com_id . '"');
		if (countArray($result) > 0) {
			echo "<optgroup label='" . label('Choosecostype') . "'>";
			$numloop = 1;
			foreach ($result as $key) {
				$select_val = "";
				if ($key['tc_id'] == $tc_id) {
					$select_val = "selected";
				}
				if ($lang == "thai") {
					echo "<option value='" . $key['tc_id'] . "' " . $select_val . ">" . $key['tc_name_th'] . "</option>";
				} else {
					echo "<option value='" . $key['tc_id'] . "' " . $select_val . ">" . $key['tc_name_en'] . "</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}


	public function recheckgroupcosmulti()
	{
		$cg_id = isset($_REQUEST['cg_id']) ? $_REQUEST['cg_id'] : "";
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$cg_id_arr = explode(",", $cg_id);
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$fetch_query = $this->func_query->query_result('lms_cog', '', '', '', 'cg_isDelete="0" and cg_approve="1" and cg_status="1" and com_id ="' . $com_id . '"', 'cg_id ASC');
		if (countArray($fetch_query) > 0) {
			echo "<optgroup label='" . label('Choosecoursegroup') . "'>";
			foreach ($fetch_query as $key) {
				$select_val = "";
				if (isset($_REQUEST['cos_id'])) {
					$numchk = $this->func_query->numrows('lms_cosincg', '', '', '', 'course_id="' . $_REQUEST['cos_id'] . '" and cg_id="' . $key['cg_id'] . '"');
					if ($numchk > 0) {
						$select_val = "selected";
					}
				}/*
        if(countArray($cg_id_arr)>0&&in_array($key['cg_id'], $cg_id_arr)){
          $select_val = "selected";
        }*/
				if ($lang == "thai") {
					echo "<option value='" . $key['cg_id'] . "' " . $select_val . ">" . $key['cgtitle_th'] . "</option>";
				} else {
					echo "<option value='" . $key['cg_id'] . "' " . $select_val . ">" . $key['cgtitle_en'] . "</option>";
				}
			}
			$this->func_query->closeDB();
			echo "</optgroup>";
		} else {
			echo "<optgroup label='" . label('wg_datanotfound') . "'></optgroup>";
		}
	}

	public function reject_cos()
	{
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$cosa_note = isset($_REQUEST['cosa_note']) ? $_REQUEST['cosa_note'] : "";

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$arr_update = array(
			'cos_id' => $cos_id,
			'cosa_approve' => '0',
			'cosa_note' => $cosa_note,
			'cosa_createby' => $sess['u_id'],
			'cosa_createdate' => date('Y-m-d H:i'),
		);
		$this->db->insert('lms_cos_approve', $arr_update);
		$arr_updatecos = array(
			'cos_public' => '0',
			'cos_approve' => '0',
		);
		$this->db->where('cos_id', $cos_id);
		$this->db->update('lms_cos', $arr_updatecos);
		$lang = "english";
		$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
		$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
		$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
		// if($lang!="thai"){
		$date = date('d F Y');
		//}
		$cos_lang = explode(',', $fetch_cos['cos_lang']);
		$fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
		$fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
		$fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
		$cname = "";
		if ($lang == "thai") {
			if ($fetch_cos['isTH'] == "1") {
				$cname = $fetch_cos['cname_th'];
			} else {
				if ($cname == "") {
					$cname = $fetch_cos['cname_eng'];
				}
				if ($cname == "") {
					$cname = $fetch_cos['cname_jp'];
				}
			}
		} else if ($lang == "english") {
			if ($fetch_cos['isENG'] == "1") {
				$cname = $fetch_cos['cname_eng'];
			} else {
				if ($cname == "") {
					$cname = $fetch_cos['cname_th'];
				}
				if ($cname == "") {
					$cname = $fetch_cos['cname_jp'];
				}
			}
		} else {
			if ($fetch_cos['isJP'] == "1") {
				$cname = $fetch_cos['cname_jp'];
			} else {
				if ($cname == "") {
					$cname = $fetch_cos['cname_eng'];
				}
				if ($cname == "") {
					$cname = $fetch_cos['cname_th'];
				}
			}
		}
		$period = "Unlimited time"; //label('UnlimitedTime');
		$fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_status="1" and cosde_isDelete="0"');
		if (countArray($fetch_cos_detail) > 0) {
			if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
				if ($lang == "thai") {
					$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_start'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_start'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_start'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_start'])) : "";
					$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_end'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_end'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_end'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_end'])) : "";
				} else {
					$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
					$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
				}
				$periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
				$periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

				if ($periodstart != "" && $periodend != "") {
					$period = $periodstart . " - " . $periodend;
				}
			}
		}
		$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
		//if($lang!="thai"){
		$date = date('d F Y');
		//}
		if ($fetch_cos['cos_createby'] != "") {
			$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_cos['cos_createby'] . '"');
			$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="6"');
			if (countArray($fetch_formatmail) > 0) {
				$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
				$subject_th = $fetch_formatmail['smf_subject_th'];
				$subject_en = $fetch_formatmail['smf_subject_en'];
				$message_th = $fetch_formatmail['smf_message_th'];
				$message_en = $fetch_formatmail['smf_message_en'];
				$cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
				if ($subject_th != "") {
					$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
					$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
					$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
					$subject_th = str_replace("#coursename", $cname, $subject_th);
					$subject_th = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $subject_th);
					$subject_th = str_replace("#date", $date, $subject_th);
					$subject_th = str_replace("#time", date('H:i'), $subject_th);
					$subject_th = str_replace("#perioddate", $period, $subject_th);
					$subject_th = str_replace("#message", $cosa_note, $subject_th);
					$subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
					$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
				}
				if ($subject_en != "") {
					$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
					$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
					$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
					$subject_en = str_replace("#coursename", $cname, $subject_en);
					$subject_en = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $subject_en);
					$subject_en = str_replace("#date", $date, $subject_en);
					$subject_en = str_replace("#time", date('H:i'), $subject_en);
					$subject_en = str_replace("#perioddate", $period, $subject_en);
					$subject_en = str_replace("#message", $cosa_note, $subject_en);
					$subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
					$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
				}
				if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
					$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
				} else {
					$img_val = '';
				}
				if ($message_th != "") {
					$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
					$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
					$message_th = str_replace("#email", $fetch_user['email'], $message_th);
					$message_th = str_replace("#coursename", $cname, $message_th);
					$message_th = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $message_th);
					$message_th = str_replace("#date", $date, $message_th);
					$message_th = str_replace("#time", date('H:i'), $message_th);
					$message_th = str_replace("#perioddate", $period, $message_th);
					$message_th = str_replace("#message", $cosa_note, $message_th);
					$message_th = str_replace("#image", $img_val, $message_th);
					$message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
					$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
				}
				if ($message_en != "") {
					$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
					$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
					$message_en = str_replace("#email", $fetch_user['email'], $message_en);
					$message_en = str_replace("#coursename", $cname, $message_en);
					$message_en = str_replace("#link_frontend", base_url() . "managecourse/courses_all/", $message_en);
					$message_en = str_replace("#date", $date, $message_en);
					$message_en = str_replace("#time", date('H:i'), $message_en);
					$message_en = str_replace("#perioddate", $period, $message_en);
					$message_en = str_replace("#message", $cosa_note, $message_en);
					$message_en = str_replace("#image", $img_val, $message_en);
					$message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
					$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
				}
				$lang = "english";
				if ($lang == "thai") {
					$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
				} else {
					$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
				}
			}
		}
		$output['status'] = "2";
		echo json_encode($output);
	}


	public function reject_cog()
	{
		$cg_id = isset($_REQUEST['cg_id']) ? $_REQUEST['cg_id'] : "";
		$coga_note = isset($_REQUEST['coga_note']) ? $_REQUEST['coga_note'] : "";

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

		$arr_update = array(
			'cg_id' => $_REQUEST['cg_id'],
			'coga_note' => $coga_note,
			'coga_approve' => '0',
			'coga_createby' => $sess['u_id'],
			'coga_createdate' => date('Y-m-d H:i'),
		);
		$this->db->insert('lms_cog_approve', $arr_update);
		$arr_update = array(
			'cg_id' => $cg_id,
			'cg_approve' => '0'
		);
		$this->db->where('cg_id', $cg_id);
		$this->db->update('lms_cog', $arr_update);

		$lang = "english";
		$fetch_cg = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $cg_id . '"');
		$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
		$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
		//if($lang!="thai"){
		$date = date('d F Y');
		// }
		if ($lang == "thai") {
			$cgtitle = $fetch_cg['cgtitle_th'] != "" ? $fetch_cg['cgtitle_th'] : $fetch_cg['cgtitle_en'];
			$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_jp'];
		} else if ($lang == "english") {
			$cgtitle = $fetch_cg['cgtitle_en'] != "" ? $fetch_cg['cgtitle_en'] : $fetch_cg['cgtitle_th'];
			$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_jp'];
		} else {
			$cgtitle = $fetch_cg['cgtitle_jp'] != "" ? $fetch_cg['cgtitle_jp'] : $fetch_cg['cgtitle_en'];
			$cgtitle = $cgtitle != "" ? $cgtitle : $fetch_cg['cgtitle_th'];
		}

		if ($fetch_cg['c_by'] != "") {
			$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_cg['c_by'] . '"');
			$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="6"');
			if (countArray($fetch_formatmail) > 0) {
				$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
				$subject_th = $fetch_formatmail['smf_subject_th'];
				$subject_en = $fetch_formatmail['smf_subject_en'];
				$message_th = $fetch_formatmail['smf_message_th'];
				$message_en = $fetch_formatmail['smf_message_en'];
				if ($subject_th != "") {
					$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
					$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
					$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
					$subject_th = str_replace("#coursename", $cgtitle, $subject_th);
					$subject_th = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $subject_th);
					$subject_th = str_replace("#date", $date, $subject_th);
					$subject_th = str_replace("#time", date('H:i'), $subject_th);
					$subject_th = str_replace("#perioddate", '', $subject_th);
					$subject_th = str_replace("#message", $coga_note, $subject_th);
					$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
				}
				if ($subject_en != "") {
					$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
					$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
					$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
					$subject_en = str_replace("#coursename", $cgtitle, $subject_en);
					$subject_en = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $subject_en);
					$subject_en = str_replace("#date", $date, $subject_en);
					$subject_en = str_replace("#time", date('H:i'), $subject_en);
					$subject_en = str_replace("#perioddate", '', $subject_en);
					$subject_en = str_replace("#message", $coga_note, $subject_en);
					$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
				}
				if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
					$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
				} else {
					$img_val = '';
				}
				if ($message_th != "") {
					$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
					$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
					$message_th = str_replace("#email", $fetch_user['email'], $message_th);
					$message_th = str_replace("#coursename", $cgtitle, $message_th);
					$message_th = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $message_th);
					$message_th = str_replace("#date", $date, $message_th);
					$message_th = str_replace("#time", date('H:i'), $message_th);
					$message_th = str_replace("#perioddate", '', $message_th);
					$message_th = str_replace("#message", $coga_note, $message_th);
					$message_th = str_replace("#image", $img_val, $message_th);
					$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
				}
				if ($message_en != "") {
					$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
					$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
					$message_en = str_replace("#email", $fetch_user['email'], $message_en);
					$message_en = str_replace("#coursename", $cgtitle, $message_en);
					$message_en = str_replace("#link_frontend", base_url() . "managecourse/course_groups/", $message_en);
					$message_en = str_replace("#date", $date, $message_en);
					$message_en = str_replace("#time", date('H:i'), $message_en);
					$message_en = str_replace("#perioddate", '', $message_en);
					$message_en = str_replace("#message", $coga_note, $message_en);
					$message_en = str_replace("#image", $img_val, $message_en);
					$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
				}
				$lang = "english";
				if ($lang == "thai") {
					$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
				} else {
					$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
				}
			}
		}
		$output['status'] = "2";
		echo json_encode($output);
	}

	public function reject_publicsurvey()
	{
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";
		$sva_note = isset($_REQUEST['sva_note']) ? $_REQUEST['sva_note'] : "";

		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata("user");
		$arr_update = array(
			'sv_id' => $sv_id,
			'sva_approve' => '0',
			'sva_note' => $sva_note,
			'sva_createby' => $sess['u_id'],
			'sva_createdate' => date('Y-m-d H:i'),
		);
		$this->db->insert('lms_sv_approve', $arr_update);
		$arr_updatecos = array(
			'sv_public' => '0',
			'sv_approve' => '0',
		);
		$this->db->where('sv_id', $sv_id);
		$this->db->update('lms_sv', $arr_updatecos);
		$date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
		// if($lang!="thai"){
		$date = date('d F Y');
		// }
		$lang = "english";
		$fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
		$fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id="' . $fetch_sv['sv_createby'] . '"');
		if ($lang == "thai") {
			$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_sv['sv_open'])) . $thaimonth[intval(date('m', strtotime($fetch_sv['sv_open'])))] . " " . (date('Y', strtotime($fetch_sv['sv_open'])) + 543) . " " . date('H:i', strtotime($fetch_sv['sv_open'])) : "";
			$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_sv['sv_end'])) . $thaimonth[intval(date('m', strtotime($fetch_sv['sv_end'])))] . " " . (date('Y', strtotime($fetch_sv['sv_end'])) + 543) . " " . date('H:i', strtotime($fetch_sv['sv_end'])) : "";
		} else {
			$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
			$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
		}
		$periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
		$periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
		$period = "Unlimited time"; //label('UnlimitedTime');
		if ($periodstart != "" && $periodend != "") {
			$period = $periodstart . " - " . $periodend;
		}

		if ($lang == "thai") {
			$sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
		} else if ($lang == "english") {
			$sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
		} else {
			$sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
			$sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
		}
		$fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
		$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="9"');
		if (countArray($fetch_formatmail) > 0) {
			$subject_th = $fetch_formatmail['smf_subject_th'];
			$subject_en = $fetch_formatmail['smf_subject_en'];
			$message_th = $fetch_formatmail['smf_message_th'];
			$message_en = $fetch_formatmail['smf_message_en'];
			$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
			if ($subject_th != "") {
				$subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
				$subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
				$subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
				$subject_th = str_replace("#coursename", $sv_title, $subject_th);
				$subject_th = str_replace("#link_frontend", base_url() . "survey/list_survey/", $subject_th);
				$subject_th = str_replace("#date", $date, $subject_th);
				$subject_th = str_replace("#time", date('H:i'), $subject_th);
				$subject_th = str_replace("#perioddate", $period, $subject_th);
				$subject_th = str_replace("#message", $sva_note, $subject_th);
				$subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
			}
			if ($subject_en != "") {
				$subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
				$subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
				$subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
				$subject_en = str_replace("#coursename", $sv_title, $subject_en);
				$subject_en = str_replace("#link_frontend", base_url() . "survey/list_survey/", $subject_en);
				$subject_en = str_replace("#date", $date, $subject_en);
				$subject_en = str_replace("#time", date('H:i'), $subject_en);
				$subject_en = str_replace("#perioddate", $period, $subject_en);
				$subject_en = str_replace("#message", $sva_note, $subject_en);
				$subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
			}
			if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
				$img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
			} else {
				$img_val = '';
			}
			if ($message_th != "") {
				$message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
				$message_th = str_replace("#username", $fetch_user['useri'], $message_th);
				$message_th = str_replace("#email", $fetch_user['email'], $message_th);
				$message_th = str_replace("#coursename", $sv_title, $message_th);
				$message_th = str_replace("#link_frontend", base_url() . "survey/list_survey/", $message_th);
				$message_th = str_replace("#date", $date, $message_th);
				$message_th = str_replace("#time", date('H:i'), $message_th);
				$message_th = str_replace("#perioddate", $period, $message_th);
				$message_th = str_replace("#image", $img_val, $message_th);
				$message_th = str_replace("#message", $sva_note, $message_th);
				$message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
			}
			if ($message_en != "") {
				$message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
				$message_en = str_replace("#username", $fetch_user['useri'], $message_en);
				$message_en = str_replace("#email", $fetch_user['email'], $message_en);
				$message_en = str_replace("#coursename", $sv_title, $message_en);
				$message_en = str_replace("#link_frontend", base_url() . "survey/list_survey/", $message_en);
				$message_en = str_replace("#date", $date, $message_en);
				$message_en = str_replace("#time", date('H:i'), $message_en);
				$message_en = str_replace("#perioddate", $period, $message_en);
				$message_en = str_replace("#image", $img_val, $message_en);
				$message_en = str_replace("#message", $sva_note, $message_en);
				$message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
			}
			$lang = "english";
			if ($lang == "thai") {
				$this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
			} else {
				$this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
			}
		}
		$output['status'] = "2";
		echo json_encode($output);
	}

	public function recheckapprovemulti()
	{
		$cg_approve_by = isset($_REQUEST['cg_approve_by']) ? $_REQUEST['cg_approve_by'] : "";
		$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
		$cg_approve_by_arr = explode(",", $cg_approve_by);
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();

		$sess = $this->session->userdata("user");
		$arr_user = array();
		if (isset($_REQUEST['cg_id']) && $_REQUEST['cg_id'] != "") {
			$fetch_chk = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $_REQUEST['cg_id'] . '"');
			if (countArray($fetch_chk) > 0 && $fetch_chk['cg_approve_by'] != "") {
				$arr_user = explode(',', $fetch_chk['cg_approve_by']);
			}
		}
		$result = $this->func_query->query_result('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_emp.com_id="' . $_REQUEST['com_id'] . '" and (lms_usp.inactivedate = "0000-00-00" or lms_usp.inactivedate < "' . date("Y-m-d") . '") and lms_usp.u_isDelete = 0 and lms_emp.emp_id!="' . $sess['emp_id'] . '"');
		if (countArray($result) > 0) {
			foreach ($result as $key => $value) {
				$select_val = "";
				$fullname = $lang == "thai" ? $value['fullname_th'] : $value['fullname_en'];
				if (countArray($arr_user) > 0 && in_array($value['u_id'], $arr_user)) {
					$select_val = "selected";
				}
				echo '<option value="' . $value['u_id'] . '" ' . $select_val . '>' . $fullname . '</option>';
			}
		} else {
			echo '<option value="">' . label('wg_datanotfound') . '</option>';
		}
	}

	public function permission_course()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$cos_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : "";
		$cosde_id = isset($_REQUEST['cosde_id']) ? $_REQUEST['cosde_id'] : "";
		$isView = isset($_POST['isView']) && !checkValueIsNullTypeNumber($_POST['isView']) ? intval($_POST['isView']) : 0;

		$sess = $this->session->userdata("user");
		if (isset($sess['com_id'])) {
			$fetch_comuser = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $sess['com_id'] . '"');
			$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
			$where = "";
			if ($fetch_comuser['com_admin'] == "com_associated") {
				$where = " and com_id = '" . $sess['com_id'] . "'";
			}
			$result_com = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_name_th!="Verztec"' . $where);
			$arr_company = array();
			$arr_department = array();
			$arr_position = array();
			if ($cosde_id != "") {
				$fetch_ug = $this->func_query->query_result('lms_cos_detail_ug', '', '', '', 'cosde_id="' . $cosde_id . '"');
				foreach ($fetch_ug as $key_ug => $value_ug) {
					array_push($arr_position, $value_ug['posi_id']);
				}
			}
			$txt_position = "";
			if (countArray($arr_position) > 0) {
				$txt_position = implode(',', $arr_position);
				$txt_position = ' and lms_position.posi_id in (' . $txt_position . ')';
			}
			$result_depcom = $this->func_query->query_result('lms_depart', 'lms_position', 'lms_depart.dep_id = lms_position.dep_id', '', 'lms_position.posi_isDelete="0" and lms_position.posi_status = "1" and lms_depart.dep_isDelete = "0" and lms_depart.dep_status = "1"' . $where, '', 'lms_depart.com_id,lms_depart.dep_id,lms_position.posi_id');
			$arr_com = array();
			foreach ($result_depcom as $key_depcom => $value_depcom) {
				array_push($arr_com, $value_depcom['com_id']);
				if (countArray($arr_position) > 0 && in_array($value_depcom['posi_id'], $arr_position) && !in_array($value_depcom['dep_id'], $arr_department)) {
					array_push($arr_department, $value_depcom['dep_id']);
				}
				if (countArray($arr_position) > 0 && in_array($value_depcom['posi_id'], $arr_position) && !in_array($value_depcom['com_id'], $arr_company)) {
					array_push($arr_company, $value_depcom['com_id']);
				}
			}
			if (countArray($result_com) > 0) {
				$numcom = 1;
				foreach ($result_com as $key_com => $value_com) {
					if (!in_array($value_com['com_id'], $arr_com)) {
						unset($result_com[$key_com]);
					}
				}

				if ($fetch_cos['cos_approve'] == "1" && $sess['u_id'] != "1") {
					$isView = 1;
				}

				foreach ($result_com as $key_com => $value_com) {
					$num_chk = 0;
					$order_by_dep = $lang == 'thai' ? 'dep_name_th ASC' : 'dep_name_en ASC';
					$order_by_posi = $lang == 'thai' ? 'posi_name_th ASC' : 'posi_name_en ASC';
	
					$result_dep = $this->func_query->query_result('lms_depart', '', '', '', 'dep_isDelete="0" and dep_status="1" and com_id = "' . $value_com['com_id'] . '"', $order_by_dep);
					$result_posi = $this->func_query->query_result('lms_position', 'lms_depart', 'lms_depart.dep_id = lms_position.dep_id', '', 'posi_isDelete="0" and posi_status="1" and dep_isDelete="0" and dep_status="1" and lms_depart.com_id = "' . $value_com['com_id'] . '"', $order_by_posi, 'lms_position.dep_id,lms_position.posi_id,lms_position.posi_name_th,lms_position.posi_name_en');
					$arr_dep = array();
					foreach ($result_posi as $key_posi => $value_posi) {
						array_push($arr_dep, $value_posi['dep_id']);
					}
					foreach ($result_dep as $key_dep => $value_dep) {
						if (!in_array($value_dep['dep_id'], $arr_dep)) {
							unset($result_dep[$key_dep]);
						}
	
						if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
							$num_chk++;
						}
					}
	
					?>
					<div class="card m-b-0">
						<div class="row">
							<div class="col-auto" <?php if ($isView == 1) { ?>style="pointer-events:none;" <?php } ?>>
								
								<input 	type="checkbox" id="chkcom_<?php echo $value_com['com_id'] ?>"
											onclick="onchkcom('<?php echo $value_com['com_id']; ?>')"
											value="<?php echo $value_com['com_id']; ?>"
											name="company_var[]"
											class="filled-in chk-col-red" <?php if ($num_chk > 0) { echo "checked"; } ?> />
								<label for="chkcom_<?php echo $value_com['com_id']; ?>"
								><?php echo ($lang == "thai" ? $value_com['com_name_th'] : $value_com['com_name_eng'])." [" . $value_com['com_code'] . "]"; ?></label>
							</div>
							<div class="col-auto" id="divallcom_<?php echo $value_com['com_id']; ?>" <?php if ($isView == 1) { ?>style="pointer-events:none;" <?php } ?>>
								<input 	type="checkbox"
										class="filled-in chk-col-red"
										id="chkallcom_<?php echo $value_com['com_id']; ?>"
										onclick="onchkallcom('<?php echo $value_com['com_id']; ?>')"
								><label for="chkallcom_<?php echo $value_com['com_id']; ?>"><?php echo label('r_company'); ?></label>
								<script>
									$(document).ready(function() {
										<?php if ($num_chk == 0) { ?>
											$('#divallcom_<?php echo $value_com['com_id']; ?>').hide();
											// $('#collapseButtonOfCompany<?php echo $value_com['com_id']; ?>').html('<i class="mdi mdi-plus-box"></i>');
										<?php } else { ?>
											$('#divallcom_<?php echo $value_com['com_id']; ?>').show();
											// $('#collapseButtonOfCompany<?php echo $value_com['com_id']; ?>').html('<i class="mdi mdi-minus-box"></i>');
										<?php } ?>
									});
								</script>
							</div>
							<div class="col-12">
								<button type="button" class="btn btn-danger btn-sm btn-collapse-company"
										style="position: absolute; left: 2.7rem;"
										id="collapseButtonOfCompany<?php echo $value_com['com_id']; ?>"
										onclick="toggleButtonText('<?php echo $value_com['com_id']; ?>')"><i class="mdi mdi-plus-box"></i></button>
							</div>
						</div>
	
						<?php $allnum_chkdep = 0;
						foreach ($result_dep as $key_dep => $value_dep) {
	
							if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
								$allnum_chkdep++;
							}
						}
						?>
						<hr>
						<div class="col-lg-12" <?php if ($isView == 1) { ?>style="pointer-events:none;" <?php } ?>>
							<div 	class="row"
									id="div_depofcompany<?php echo $value_com['com_id']; ?>" style="margin-bottom:2px;display: none;">
	
								<?php foreach ($result_dep as $key_dep => $value_dep) {
									$num_chkdep = 0;
									if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
										$num_chkdep++;
									}
								?>
									<div class="col-lg-3 col-md-12 col-sm-12 chkall_<?php echo $value_com['com_id'] ?>">
	
										<input type="checkbox" onclick="onchkdep('<?php echo $value_dep['dep_id'] ?>','<?php echo $value_com['com_id']; ?>')" id="chkdep_<?php echo $value_dep['dep_id'] ?>" name="dep_var[]" value="<?php echo $value_posi['dep_id'] ?>" data-com="<?php echo $value_com['com_id']; ?>" class="filled-in chk-col-red chkall_<?php echo $value_com['com_id'] ?>" <?php if ($num_chkdep > 0) {
																																																																																																																																																																																								echo "checked";
																																																																																																																																																																																							} ?> />
										<label for="chkdep_<?php echo $value_dep['dep_id'] ?>"><?php if ($lang == "thai") {
																																							echo $value_dep['dep_name_th'];
																																						} else {
																																							echo $value_dep['dep_name_en'];
																																						} ?></label>
										<div>
											<!-- <hr> -->
											<div style="top: -10px;" class="card-body row chkall_<?php echo $value_com['com_id'] ?>" <?php if ($allnum_chkdep == 0) { ?>style="margin-bottom:2px;display: none;" <?php } ?>>
												<?php foreach ($result_posi as $key_posi => $value_posi) {
	
													$num_chkposi = 0;
													if (countArray($arr_department) > 0 && in_array($value_posi['dep_id'], $arr_department)) {
														$num_chkposi++;
													}
													if ($value_posi['dep_id'] == $value_dep['dep_id']) {
												?>
														<div class="col-12 chkall_<?php echo $value_com['com_id'] ?> chkdepall_<?php echo $value_posi['dep_id'] ?>" <?php if ($num_chkposi > 0) { ?> style="margin-top:0px;" <?php } else { ?>style="margin-bottom:2px;display: none;" <?php } ?>>
															<input type="checkbox" id="chkposi_<?php echo $value_posi['posi_id'] ?>" name="posi_var[]" class="filled-in chk-col-red chkcompany<?php echo $value_com['com_id']; ?> chkposiall_<?php echo $value_posi['dep_id'] ?>" onclick="onchkposi('<?php echo $value_posi['posi_id'] ?>','<?php echo $value_com['com_id']; ?>','<?php echo $value_posi['dep_id'] ?>')" data-com="<?php echo $value_com['com_id']; ?>" <?php if (in_array($value_posi['posi_id'], $arr_position)) {
																																																																																																																																																																																																																							echo "checked";
																																																																																																																																																																																																																						} ?> value="<?php echo $value_posi['posi_id'] ?>" data-dep="<?php echo $value_posi['dep_id']; ?>" />
															<label for="chkposi_<?php echo $value_posi['posi_id'] ?>"><?php if ($lang == "thai") {
																																													echo $value_posi['posi_name_th'];
																																												} else {
																																													echo $value_posi['posi_name_en'];
																																												} ?></label>
														</div>
	
												<?php }
												} ?>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						var checkedAry = [];
						$.each($(".chkcompany<?php echo $value_com['com_id']; ?>:checked"), function() {
							checkedAry.push($(this).attr("id"));
						});
						var checkedAryAll = [];
						$.each($(".chkcompany<?php echo $value_com['com_id']; ?>"), function() {
							checkedAryAll.push($(this).attr("id"));
						});
						if (checkedAry.length == checkedAryAll.length) {
							$("#chkallcom_<?php echo $value_com['com_id']; ?>").prop('checked', true);
						} else {
							$("#chkallcom_<?php echo $value_com['com_id']; ?>").prop('checked', false);
						}
					</script>
				<?php
					/*if($numcom<countArray($result_com)){
			  echo '<hr>';
			}*/ $numcom++;
				}
				$this->func_query->closeDB();
			} else {
				echo '<h3 align="center">' . label('wg_datanotfound') . '</h3>';
			}
		} else {
			echo '<h3 align="center">' . label('wg_datanotfound') . '</h3>';
		}
	}

	public function permission_survey()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->func_query->loadDB();
		$sv_id = isset($_REQUEST['sv_id']) ? $_REQUEST['sv_id'] : "";

		$sess = $this->session->userdata("user");
		if (isset($sess['com_id'])) {
			$fetch_comuser = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $sess['com_id'] . '"');
			$where = "";
			if ($fetch_comuser['com_admin'] == "com_associated") {
				$where = " and com_id = '" . $sess['com_id'] . "'";
			}
			$result_com = $this->func_query->query_result('lms_company', '', '', '', 'com_isDelete="0" and com_status="1" and com_name_th!="Verztec"' . $where);
			$arr_company = array();
			$arr_department = array();
			$arr_position = array();
			if ($sv_id != "") {
				$fetch_ug = $this->func_query->query_result('lms_sv_pm', '', '', '', 'sv_id="' . $sv_id . '"');
				foreach ($fetch_ug as $key_ug => $value_ug) {
					array_push($arr_position, $value_ug['posi_id']);
				}
			}
			$txt_position = "";
			if (countArray($arr_position) > 0) {
				$txt_position = implode(',', $arr_position);
				$txt_position = ' and lms_position.posi_id in (' . $txt_position . ')';
			}
			$result_depcom = $this->func_query->query_result('lms_depart', 'lms_position', 'lms_depart.dep_id = lms_position.dep_id', '', 'lms_position.posi_isDelete="0" and lms_position.posi_status = "1" and lms_depart.dep_isDelete = "0" and lms_depart.dep_status = "1"' . $where, '', 'lms_depart.com_id,lms_depart.dep_id,lms_position.posi_id');
			$arr_com = array();
			foreach ($result_depcom as $key_depcom => $value_depcom) {
				array_push($arr_com, $value_depcom['com_id']);
				if (countArray($arr_position) > 0 && in_array($value_depcom['posi_id'], $arr_position) && !in_array($value_depcom['dep_id'], $arr_department)) {
					array_push($arr_department, $value_depcom['dep_id']);
				}
				if (countArray($arr_position) > 0 && in_array($value_depcom['posi_id'], $arr_position) && !in_array($value_depcom['com_id'], $arr_company)) {
					array_push($arr_company, $value_depcom['com_id']);
				}
			}
			if (countArray($result_com) > 0) {
				$numcom = 1;
				foreach ($result_com as $key_com => $value_com) {
					if (!in_array($value_com['com_id'], $arr_com)) {
						unset($result_com[$key_com]);
					}
				}
				foreach ($result_com as $key_com => $value_com) {
					$num_chk = 0;
					$order_by_dep = $lang == 'thai' ? 'dep_name_th ASC' : 'dep_name_en ASC';
					$order_by_posi = $lang == 'thai' ? 'posi_name_th ASC' : 'posi_name_en ASC';
					$result_dep = $this->func_query->query_result('lms_depart', '', '', '', 'dep_isDelete="0" and dep_status="1" and com_id = "' . $value_com['com_id'] . '"', $order_by_dep);
					$result_posi = $this->func_query->query_result('lms_position', 'lms_depart', 'lms_depart.dep_id = lms_position.dep_id', '', 'posi_isDelete="0" and posi_status="1" and dep_isDelete="0" and dep_status="1" and lms_depart.com_id = "' . $value_com['com_id'] . '"', $order_by_posi, 'lms_position.dep_id,lms_position.posi_id,lms_position.posi_name_th,lms_position.posi_name_en');
					$arr_dep = array();
					foreach ($result_posi as $key_posi => $value_posi) {
						array_push($arr_dep, $value_posi['dep_id']);
					}
					foreach ($result_dep as $key_dep => $value_dep) {
						if (!in_array($value_dep['dep_id'], $arr_dep)) {
							unset($result_dep[$key_dep]);
						}
	
						if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
							$num_chk++;
						}
					}
				?>
					<div class="card m-b-0">
						<div class="row">
							<div class="col-auto">
								<input type="checkbox" id="chkcom_<?php echo $value_com['com_id'] ?>" onclick="onchkcom('<?php echo $value_com['com_id']; ?>')" value="<?php echo $value_com['com_id']; ?>" name="company_var[]" class="filled-in chk-col-red" <?php if ($num_chk > 0) {
																																																																																																																									echo "checked";
																																																																																																																								} ?> />
								<label for="chkcom_<?php echo $value_com['com_id']; ?>"><?php if ($lang == "thai") {
																																					echo $value_com['com_name_th'];
																																				} else {
																																					echo $value_com['com_name_eng'];
																																				}
																																				echo " [" . $value_com['com_code'] . "]"; ?></label>
							</div>
							<div class="col-auto" id="divallcom_<?php echo $value_com['com_id']; ?>">
								<input type="checkbox" class="filled-in chk-col-red" id="chkallcom_<?php echo $value_com['com_id']; ?>" onclick="onchkallcom('<?php echo $value_com['com_id']; ?>')"><label for="chkallcom_<?php echo $value_com['com_id']; ?>"><?php echo label('r_company'); ?></label>
								<script>
									$(document).ready(function() {
										<?php if ($num_chk == 0) { ?>
											$('#divallcom_<?php echo $value_com['com_id']; ?>').hide();
										<?php } else { ?>
											$('#divallcom_<?php echo $value_com['com_id']; ?>').show();
										<?php } ?>
									});
								</script>
							</div>
						</div>
	
						<?php $allnum_chkdep = 0;
						foreach ($result_dep as $key_dep => $value_dep) {
	
							if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
								$allnum_chkdep++;
							}
						}
						?>
						<hr>
						<div class="col-lg-12">
							<div class="row" id="div_depofcompany<?php echo $value_com['com_id']; ?>" style="margin-bottom:2px;display: none;">
	
								<?php foreach ($result_dep as $key_dep => $value_dep) {
									$num_chkdep = 0;
									if (countArray($arr_department) > 0 && in_array($value_dep['dep_id'], $arr_department)) {
										$num_chkdep++;
									}
								?>
									<div class="col-lg-3 col-md-12 col-sm-12 chkall_<?php echo $value_com['com_id'] ?>">
	
										<input type="checkbox" onclick="onchkdep('<?php echo $value_dep['dep_id'] ?>','<?php echo $value_com['com_id']; ?>')" id="chkdep_<?php echo $value_dep['dep_id'] ?>" name="dep_var[]" value="<?php echo $value_posi['dep_id'] ?>" data-com="<?php echo $value_com['com_id']; ?>" class="filled-in chk-col-red chkall_<?php echo $value_com['com_id'] ?>" <?php if ($num_chkdep > 0) {
																																																																																																																																																																																								echo "checked";
																																																																																																																																																																																							} ?> />
										<label for="chkdep_<?php echo $value_dep['dep_id'] ?>"><?php if ($lang == "thai") {
																																							echo $value_dep['dep_name_th'];
																																						} else {
																																							echo $value_dep['dep_name_en'];
																																						} ?></label>
										<div>
											<!-- <hr> -->
											<div style="top: -10px;" class="card-body row chkall_<?php echo $value_com['com_id'] ?>" <?php if ($allnum_chkdep == 0) { ?>style="margin-bottom:2px;display: none;" <?php } ?>>
												<?php foreach ($result_posi as $key_posi => $value_posi) {
	
													$num_chkposi = 0;
													if (countArray($arr_department) > 0 && in_array($value_posi['dep_id'], $arr_department)) {
														$num_chkposi++;
													}
													if ($value_posi['dep_id'] == $value_dep['dep_id']) {
												?>
														<div class="col-12 chkall_<?php echo $value_com['com_id'] ?> chkdepall_<?php echo $value_posi['dep_id'] ?>" <?php if ($num_chkposi > 0) { ?> style="margin-top:0px;" <?php } else { ?>style="margin-bottom:2px;display: none;" <?php } ?>>
															<input type="checkbox" id="chkposi_<?php echo $value_posi['posi_id'] ?>" name="posi_var[]" class="filled-in chk-col-red chkcompany<?php echo $value_com['com_id']; ?> chkposiall_<?php echo $value_posi['dep_id'] ?>" onclick="onchkposi('<?php echo $value_posi['posi_id'] ?>','<?php echo $value_com['com_id']; ?>','<?php echo $value_posi['dep_id'] ?>')" data-com="<?php echo $value_com['com_id']; ?>" <?php if (in_array($value_posi['posi_id'], $arr_position)) {
																																																																																																																																																																																																echo "checked";
																																																																																																																																																																																															} ?> value="<?php echo $value_posi['posi_id'] ?>" data-dep="<?php echo $value_posi['dep_id']; ?>" />
															<label for="chkposi_<?php echo $value_posi['posi_id'] ?>"><?php if ($lang == "thai") {
																																													echo $value_posi['posi_name_th'];
																																												} else {
																																													echo $value_posi['posi_name_en'];
																																												} ?></label>
														</div>
	
												<?php }
												} ?>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<script>
						var checkedAry = [];
						$.each($(".chkcompany<?php echo $value_com['com_id']; ?>:checked"), function() {
							checkedAry.push($(this).attr("id"));
						});
						var checkedAryAll = [];
						$.each($(".chkcompany<?php echo $value_com['com_id']; ?>"), function() {
							checkedAryAll.push($(this).attr("id"));
						});
						if (checkedAry.length == checkedAryAll.length) {
							$("#chkallcom_<?php echo $value_com['com_id']; ?>").prop('checked', true);
						} else {
							$("#chkallcom_<?php echo $value_com['com_id']; ?>").prop('checked', false);
						}
					</script>
				<?php
					/*if($numcom<countArray($result_com)){
			  echo '<hr>';
			}*/ $numcom++;
				}
				$this->func_query->closeDB();
			} else {
				echo '<h3 align="center">' . label('wg_datanotfound') . '</h3>';
			}
		}
	}

	public function update_question_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '"');
			if ($result['ques_type'] == "multi" || $result['ques_type'] == "2choice") {
				$result_multi = $this->func_query->query_row('lms_ques_mul', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '"');
				$result['multi'] = $result_multi;
			}

			$result['counttc'] = $this->func_query->numrows('lms_ques_tc', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '" and tc_flag="true" and tc_save="true"');
			if ($lang == "thai") {
				$ques_name = $result['ques_name_th'] != "" ? $result['ques_name_th'] : $result['ques_name_eng'];
				$ques_name = $ques_name != "" ? $ques_name : $result['ques_name_jp'];
			} else if ($lang == "english") {
				$ques_name = $result['ques_name_eng'] != "" ? $result['ques_name_eng'] : $result['ques_name_th'];
				$ques_name = $ques_name != "" ? $ques_name : $result['ques_name_jp'];
			} else {
				$ques_name = $result['ques_name_jp'] != "" ? $result['ques_name_jp'] : $result['ques_name_eng'];
				$ques_name = $ques_name != "" ? $ques_name : $result['ques_name_th'];
			}
			$result['ques_score'] = intval($result['ques_score']);
			$result['ques_name'] = $ques_name;
			echo json_encode($result);
		}
	}

	public function rechk_course_incg()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			date_default_timezone_set("Asia/Bangkok");
			$cg_id = isset($_REQUEST['cg_id']) ? $_REQUEST['cg_id'] : "";
			$result_cg = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $cg_id . '"');
			$where = 'lms_cos.cos_id in (select lms_cosincg.course_id from lms_cosincg where lms_cosincg.cg_id = "' . $cg_id . '") and lms_cos.cos_isDelete="0"';
			$result = $this->func_query->query_result('lms_cos', '', '', '', $where);
			$output = array();
			$output['status'] = "0";
			$output['cg_status'] = $result_cg['cg_status'];
			$total_cos = 0;
			if (countArray($result) > 0) {
				foreach ($result as $key => $value) {
					$fetch_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $value['cos_id'] . '"');
					if (countArray($fetch_detail) > 0) {
						if (($fetch_detail['date_start'] == "0000-00-00 00:00:00" && $fetch_detail['date_end'] == "0000-00-00 00:00:00") || (date('Y-m-d H:i', strtotime($fetch_detail['date_end'])) >= date('Y-m-d H:i'))) {
							$total_cos++;
						}
					} else {
						$total_cos++;
					}
				}
			}
			if ($total_cos > 0) {
				$output['status'] = "1";
			}
			echo json_encode($output);
		}
	}

	public function update_sdve_question_detail_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->func_query->query_row('lms_svde', 'lms_sv', 'lms_sv.sv_id = lms_svde.sv_id', '', 'svde_id="' . $_REQUEST['svde_id'] . '"');
			if ($result['svde_type'] == "multi" || $result['svde_type'] == "2choice") {
				$result_multi = $this->func_query->query_row('lms_svde_mul', '', '', '', 'svde_id="' . $_REQUEST['svde_id'] . '" and mul_isDelete="0"');
				$result['multi'] = $result_multi;
			}
			echo json_encode($result);
		}
	}

	public function user_approve()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$arr_user = array();
			if (isset($_REQUEST['sv_id']) && $_REQUEST['sv_id'] != "") {
				$fetch_chk = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
				if (countArray($fetch_chk) > 0 && $fetch_chk['sv_userapprove'] != "") {
					$arr_user = explode(',', $fetch_chk['sv_userapprove']);
				}
			}
			$where = 'lms_emp.com_id="' . $_REQUEST['com_id'] . '" and (lms_usp.inactivedate = "0000-00-00" or lms_usp.inactivedate < "' . date("Y-m-d") . '") and lms_usp.u_isDelete = 0';
			if (!isset($_REQUEST['viewDetail']) || (isset($_REQUEST['viewDetail']) && $_REQUEST['viewDetail'] != 1)) {
				$where .= ' and lms_emp.emp_id!="' . $sess['emp_id'] . '"';
			}
			$result = $this->func_query->query_result(
				'lms_usp',
				'lms_emp',
				'lms_usp.emp_id = lms_emp.emp_id', '', 
				$where);
			if (countArray($result) > 0) {
				foreach ($result as $key => $value) {
					$select_val = "";
					$fullname = $lang == "thai" ? $value['fullname_th'] : $value['fullname_en'];
					if (countArray($arr_user) > 0 && in_array($value['emp_id'], $arr_user)) {
						$select_val = "selected";
					}
					echo '<option value="' . $value['emp_id'] . '" ' . $select_val . '>' . $fullname . '</option>';
				}
			} else {
				echo '<option value="">' . label('wg_datanotfound') . '</option>';
			}
		}
	}

	public function survey_data()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$sess = $this->session->userdata("user");
		$this->manage->loadDB();
		if (countArray($_REQUEST) > 0) {
			$result = $this->func_query->query_row('lms_survey', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
			$varselect = "";
			if ($_REQUEST['lang_select'] == "thai") {
				$varselect = "svde_heading_th";
			} else if ($_REQUEST['lang_select'] == "english") {
				$varselect = "svde_heading_eng";
			} else {
				$varselect = "svde_heading_jp";
			}
			$result_head = $this->func_query->query_result('lms_survey_de', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '" and svde_status="1" and svde_isDelete="0"', 'svde_id ASC', $varselect);
			$qnu_id = "";
			$qnu_status = "";
			$fetch_status = $this->func_query->query_row('lms_qn_user', '', '', '', 'emp_id="' . $sess['emp_id'] . '" and sv_id="' . $_REQUEST['sv_id'] . '"');
			if (countArray($fetch_status) > 0 && $_REQUEST['type'] == "real") {
				$qnu_id = $fetch_status['qnu_id'];
				$qnu_status = "1";
			}
			if ($_REQUEST['lang_select'] == "thai") {
				$questiontxt = "คำถาม";
				$Suggestiontxt = "ความคิดเห็น (ถ้ามี)";
				$choice_5txt = "ดีมาก";
				$choice_4txt = "ดี";
				$choice_3txt = "ปานกลาง";
				$choice_2txt = "พอใช้";
				$choice_1txt = "ควรปรับปรุง";
				$Suggestion_anothertxt = "ข้อเสนอแนะ (ถ้ามี)";
				$Suggestion_helptxt = "ความคิดเห็นของคุณช่วยให้เราพัฒนาหลักสูตรให้ดียิ่งขึ้น";
				$Suggestion_placeholder = "บอกเราได้เลยว่าอะไรดีอยู่แล้ว หรือส่วนไหนที่ควรปรับปรุง...";
			} else if ($_REQUEST['lang_select'] == "english") {
				$questiontxt = "Question";
				$Suggestiontxt = "Suggestion";
				$choice_5txt = "Very good";
				$choice_4txt = "Good";
				$choice_3txt = "Moderate";
				$choice_2txt = "Fair";
				$choice_1txt = "Need improvement";
				$Suggestion_anothertxt = "Suggestion";
				$Suggestion_helptxt = "Your feedback helps us improve this course.";
				$Suggestion_placeholder = "Tell us what worked well or what we could improve...";
			} else {
				$questiontxt = "質問";
				$Suggestiontxt = "コメント";
				$choice_5txt = "とても良い";
				$choice_4txt = "良い";
				$choice_3txt = "ふつう";
				$choice_2txt = "まあまあ";
				$choice_1txt = "改善必要";
				$Suggestion_anothertxt = "コメント";
				$Suggestion_helptxt = "ご意見はコースの改善に役立ちます。";
				$Suggestion_placeholder = "良かった点や改善してほしい点をご記入ください...";
			}
			?>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th style="min-width: 250px;" class="align-middle">
								<p class="text-left"><?php echo $questiontxt; ?></p>
							</th>
							<th style="min-width: 100px;">
								<p class="text-center"><?php echo $choice_5txt; ?><br>5</p>
							</th>
							<th style="min-width: 100px;">
								<p class="text-center"><?php echo $choice_4txt; ?><br>4</p>
							</th>
							<th style="min-width: 100px;">
								<p class="text-center"><?php echo $choice_3txt; ?><br>3</p>
							</th>
							<th style="min-width: 100px;">
								<p class="text-center"><?php echo $choice_2txt; ?><br>2</p>
							</th>
							<th style="min-width: 150px;">
								<p class="text-center"><?php echo $choice_1txt; ?><br>1</p>
							</th>
							<th style="max-width: 200px;" class="align-middle">
								<p class="text-left"><?php echo $Suggestiontxt; ?></p>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (countArray($result_head) > 0) {
							$num = 0;
							$num_head = 1;
							$txt_head = "";
							foreach ($result_head as $key_head => $value_head) {
								$svde_heading_th = isset($value_head['svde_heading_th']) ? $value_head['svde_heading_th'] : "";
								$svde_heading_eng = isset($value_head['svde_heading_eng']) ? $value_head['svde_heading_eng'] : "";
								$svde_heading_jp = isset($value_head['svde_heading_jp']) ? $value_head['svde_heading_jp'] : "";
								if ($_REQUEST['lang_select'] == "thai") {
									$svde_heading = $svde_heading_th != "" ? $svde_heading_th : $svde_heading_eng;
									$svde_heading = $svde_heading != "" ? $svde_heading : $svde_heading_jp;
								} else if ($_REQUEST['lang_select'] == "english") {
									$svde_heading = $svde_heading_eng != "" ? $svde_heading_eng : $svde_heading_th;
									$svde_heading = $svde_heading != "" ? $svde_heading : $svde_heading_jp;
								} else {
									$svde_heading = $svde_heading_jp != "" ? $svde_heading_jp : $svde_heading_eng;
									$svde_heading = $svde_heading != "" ? $svde_heading : $svde_heading_th;
								}
						?>

								<tr>
									<td colspan="7">
										<h4 class="m-auto break-word"><b><?php echo $svde_heading; ?></b></h4>
									</td>
								</tr>
								<?php

								$varselect = "";
								if ($_REQUEST['lang_select'] == "thai") {
									$varselect = 'and svde_heading_th="' . $svde_heading . '"';
								} else if ($_REQUEST['lang_select'] == "english") {
									$varselect = 'and svde_heading_eng="' . $svde_heading . '"';
								} else {
									$varselect = 'and svde_heading_jp="' . $svde_heading . '"';
								}
								$where_detail = 'sv_id="' . $_REQUEST['sv_id'] . '" and svde_status="1" and svde_isDelete="0" ' . $varselect;
								$result_detail = $this->func_query->query_result('lms_survey_de', '', '', '', $where_detail, 'svde_id ASC', '');
								foreach ($result_detail as $key_detail => $value_detail) {

									if ($qnu_id != "") {
										$fetch_detailtc = $this->func_query->query_row('lms_qn_user_de', '', '', '', 'svde_id="' . $value_detail['svde_id'] . '" and qnu_id="' . $qnu_id . '"');
									}

									if ($_REQUEST['lang_select'] == "thai") {
										$svde_detail = $value_detail['svde_detail_th'] != "" ? $value_detail['svde_detail_th'] : $value_detail['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value_detail['svde_detail_jp'];
									} else if ($_REQUEST['lang_select'] == "english") {
										$svde_detail = $value_detail['svde_detail_eng'] != "" ? $value_detail['svde_detail_eng'] : $value_detail['svde_detail_th'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value_detail['svde_detail_jp'];
									} else {
										$svde_detail = $value_detail['svde_detail_jp'] != "" ? $value_detail['svde_detail_jp'] : $value_detail['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value_detail['svde_detail_th'];
									}

								?>

									<input type="hidden" name="svde_id[]" value="<?php echo $value_detail['svde_id']; ?>">
									<tr>
										<td width="100%" class="break-word"><?php echo $svde_detail; ?></td>
										<td class="text-center">
											<input <?php if ($qnu_status == '1') {
																echo "disabled";
															} ?> name="qnude_var[<?php echo $num; ?>]" type="radio" value="5" id="radio_<?php echo $num; ?>1" required class="with-gap radio-col-red" <?php if (isset($fetch_detailtc['qnude_var']) && $fetch_detailtc['qnude_var'] == "5") {
																																																																																					echo "checked";
																																																																																				} ?>><label for="radio_<?php echo $num; ?>1"></label>
										</td>
										<td class="text-center">
											<input <?php if ($qnu_status == '1') {
																echo "disabled";
															} ?> name="qnude_var[<?php echo $num; ?>]" type="radio" value="4" id="radio_<?php echo $num; ?>2" required class="with-gap radio-col-red" <?php if (isset($fetch_detailtc['qnude_var']) && $fetch_detailtc['qnude_var'] == "4") {
																																																																																					echo "checked";
																																																																																				} ?>><label for="radio_<?php echo $num; ?>2"></label>
										</td>
										<td class="text-center">
											<input <?php if ($qnu_status == '1') {
																echo "disabled";
															} ?> name="qnude_var[<?php echo $num; ?>]" type="radio" value="3" id="radio_<?php echo $num; ?>3" required class="with-gap radio-col-red" <?php if (isset($fetch_detailtc['qnude_var']) && $fetch_detailtc['qnude_var'] == "3") {
																																																																																					echo "checked";
																																																																																				} ?>><label for="radio_<?php echo $num; ?>3"></label>
										</td>
										<td class="text-center">
											<input <?php if ($qnu_status == '1') {
																echo "disabled";
															} ?> name="qnude_var[<?php echo $num; ?>]" type="radio" value="2" id="radio_<?php echo $num; ?>4" required class="with-gap radio-col-red" <?php if (isset($fetch_detailtc['qnude_var']) && $fetch_detailtc['qnude_var'] == "2") {
																																																																																					echo "checked";
																																																																																				} ?>><label for="radio_<?php echo $num; ?>4"></label>
										</td>
										<td class="text-center">
											<input <?php if ($qnu_status == '1') {
																echo "disabled";
															} ?> name="qnude_var[<?php echo $num; ?>]" type="radio" value="1" id="radio_<?php echo $num; ?>5" required class="with-gap radio-col-red" <?php if (isset($fetch_detailtc['qnude_var']) && $fetch_detailtc['qnude_var'] == "1") {
																																																																																					echo "checked";
																																																																																				} ?>><label for="radio_<?php echo $num; ?>5"></label>
										</td>
										<td>
											<textarea class="form-control" <?php if ($qnu_status == '1') {
																												echo "disabled";
																											} ?> name="qnude_suggestion[<?php echo $num; ?>]" id="qnude_suggestion" rows="3" style="min-width: 200px;"><?php if (isset($fetch_detailtc['qnude_suggestion']) && $fetch_detailtc['qnude_suggestion'] != "") {
																																																																																		echo $fetch_detailtc['qnude_suggestion'];
																																																																																	} ?></textarea>
										</td>
									</tr>
						<?php $num++;
								}
							}
						}
						?>
					</tbody>
				</table>
				<?php if ($result['sv_suggestion_status'] == "1") { ?>
					<section class="survey-suggestion-card">
						<div class="survey-suggestion-heading">
							<span class="survey-suggestion-icon"><i class="mdi mdi-lightbulb-on-outline"></i></span>
							<div>
								<h4><?php echo $Suggestion_anothertxt; ?></h4>
								<p><?php echo $Suggestion_helptxt; ?></p>
							</div>
							<span class="survey-suggestion-optional"><?php echo $_REQUEST['lang_select'] == 'thai' ? 'ไม่บังคับ' : ($_REQUEST['lang_select'] == 'japan' ? '任意' : 'Optional'); ?></span>
						</div>
						<div class="survey-suggestion-field">
					<textarea id="qnu_suggestion" name="qnu_suggestion" maxlength="500"
						placeholder="<?php echo htmlspecialchars($Suggestion_placeholder, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($qnu_status == '1') {
																																echo "disabled";
																															} ?> class="form-control" rows="5"><?php if (isset($fetch_status['qnu_suggestion']) && $fetch_status['qnu_suggestion'] != "" && $_REQUEST['type'] == "real") {
																																																		echo $fetch_status['qnu_suggestion'];
																																																	} ?></textarea>
							<span class="survey-suggestion-count"><strong id="surveySuggestionCount">0</strong>/500</span>
						</div>
					</section>
				<?php } ?>
			</div>
<?php
		}
	}
}
