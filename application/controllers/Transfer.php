<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer extends CI_Controller {
	public function loaddata(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
			$user = $this->session->userdata('user');
			$this->load->model('User_model', 'login', TRUE);
			$this->load->model('Course_model', 'course', TRUE);
			$this->load->model('Enroll_model', 'enroll', TRUE);
			$this->load->model('Manage_model', 'manage', FALSE);
			$this->load->model('Transfer_model', 'transfer', FALSE);

			$this->manage->loadDB();
			$this->login->loadDB();
			$this->course->loadDB();
			$this->enroll->loadDB();
			$this->transfer->loadDB();

			function getContentUrl($url) {
	           // http://coursesweb.net/php-mysql/
	            // Seting options for cURL
	            $ch = curl_init($url);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
	            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
	            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	            curl_setopt($ch, CURLOPT_HEADER, 0);
	            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
	            // gets the file content, trigger error if false
	            $file = curl_exec($ch);
	            if($file === false) trigger_error(curl_error($ch));
	            curl_close ($ch);
	            return $file;
	        }

			$cos_id = isset($_REQUEST['cos_id_transfer']) ? $_REQUEST['cos_id_transfer'] : '';
			$com_id = isset($_REQUEST['com_id_transfer']) ? $_REQUEST['com_id_transfer'] : '';
			$wg_id = isset($_REQUEST['wg_id_transfer']) ? $_REQUEST['wg_id_transfer'] : '';
			$cg_id = isset($_REQUEST['cg_id_transfer']) ? $_REQUEST['cg_id_transfer'] : '';
			if($cos_id!=""&&$com_id!=""&&$cg_id!=""&&$wg_id!=""){
				$fetch_cos = $this->manage->query_data_onupdate($cos_id,'lms_cos','id');
				$data_cos = array(
					'ccode' => $fetch_cos['ccode'],
					'wg_id' => $wg_id,
					'tc_id' => $fetch_cos['tc_id'],
					'com_id' => $com_id,
					'cname_th' => $fetch_cos['cname_th'],
					'cdesc_th' => $fetch_cos['cdesc_th'],
					'cname_en' => $fetch_cos['cname_en'],
					'cdesc_en' => $fetch_cos['cdesc_en'],
					'pic' => $fetch_cos['pic'],
					'goal_score' => $fetch_cos['goal_score'],
					'seat_count' => $fetch_cos['seat_count'],
					'condition' => $fetch_cos['condition'],
					'status' => $fetch_cos['status'],
					'time_mod' => date("Y-m-d H:i"),
					'time_create' => date("Y-m-d H:i"),
					'emp_create' => $user['emp_id']
				);
				$cos_id_new = $this->transfer->create_course($data_cos,$cos_id,$cg_id);
				if($cos_id_new!="Error"){
					$query_badges = $this->manage->query_data_onupdate($cos_id,'lms_bad','courses_id');
					if(countArray($query_badges)>0){
						$data_badges = array(
							'badges_name' => $query_badges['badges_name'],
							'badges_condition' => $query_badges['badges_condition'],
							'badges_desc' => $query_badges['badges_desc'],
							'badges_img' => $query_badges['badges_img'],
							'time_create' => date('Y-m-d H:i')
						);
						$this->course->create_badges($data_badges,$cos_id_new);
					}
					$query_grade = $this->manage->query_data_onupdate($cos_id,'lms_cug','course_id');
					if(countArray($query_grade)>0){
						$data_grade = array(
							'mina' => $query_grade['mina'],
							'minb' => $query_grade['minb'],
							'minc' => $query_grade['minc'],
							'mind' => $query_grade['mind']
						);
						$this->course->create_grade($data_grade,$cos_id_new);
					}
					$query_lesson = $this->course->result_data('lms_les','cos_id',$cos_id,'','');
					if(countArray($query_lesson)>0){
						foreach ($query_lesson as $key_les => $value_les) {
								$data_les = array(
									'cos_id' => $cos_id_new,
									'les_name_th' => $value_les['les_name_th'],
									'les_name_en' => $value_les['les_name_en'],
									'les_info_th' => $value_les['les_info_th'],
									'les_info_en' => $value_les['les_info_en'],
									'status' => $value_les['status'],
									'les_type' => $value_les['les_type'],
									'scm_type' => $value_les['scm_type'],
									'time_start' => $value_les['time_start'],
									'time_end' => $value_les['time_end'],
									'time_create' => date('Y-m-d H:i'),
									'time_mod' => date('Y-m-d H:i')
								);
								$lesson_id = $this->course->create_lesson($data_les);
								if($value_les['les_type']=="1"){
									$query_media = $this->course->result_data('lms_med','lessons_id',$value_les['les_id'],'','');
									foreach ($query_media as $key_media => $value_media) {
										$each = array(
											'lessons_id' => $lesson_id,
											'med_th' => $value_media['med_th'],
											'med_en' => $value_media['med_en'],
											'thumbnail_med' => $value_media['thumbnail_med'],
											'type' => $value_media['type'],
											'video' => $value_media['video']
										);
										$this->course->insert_data_media_all($each,$lesson_id);
									}
								}else{
									$path = $this->course->check_scorm($value_les['les_id']);
									if($path!=""){
										$scmCode = $this->course->create_scorm_id($lesson_id);
										$pathnew = "scorm_".$lesson_id."_".$scmCode;

								        $newDir = ROOT_DIR."uploads/scorm/".$pathnew;
								        $oriDir = ROOT_DIR."uploads/scorm/".$path;
				        				mkdir($newDir);
				        				$targetPath = $oriDir."/".$path.".zip";

							         	$zip = new ZipArchive;
							          	$openZip = $zip->open($targetPath);
							          	$zip->extractTo($newDir);
							          	$zip->close();
				          				$this->course->update_scorm_id($scmCode,$pathnew);
									}
								}

								$query_document = $this->course->result_data('lms_fil','lessons_id',$value_les['les_id'],'','');
								if(countArray($query_document)>0){
									foreach ($query_document as $key_document => $value_document) {
									       	$each = array(
												'lessons_id' => $lesson_id,
												'path_file' => $value_document['path_file']
											);
											$this->course->insert_data_document($each);
									}
								}
						}
					}

					$query_qiz = $this->course->result_data('lms_qiz','cos_id',$cos_id,'','');
					if(countArray($query_qiz)>0){
						foreach ($query_qiz as $key_qiz => $value_qiz) {
							$data_qiz = array(
								'cos_id' => $cos_id_new,
								'quiz_name_th' => $value_qiz['quiz_name_th'],
								'quiz_info_th' => $value_qiz['quiz_info_th'],
								'quiz_name_en' => $value_qiz['quiz_name_en'],
								'quiz_info_en' => $value_qiz['quiz_info_en'],
								'time_mod' => date('Y-m-d H:i'),
								'quiz_random' => $value_qiz['quiz_random'],
								'quiz_show' => $value_qiz['quiz_show'],
								'quiz_grade' => $value_qiz['quiz_grade'],
								'quiz_type' => $value_qiz['quiz_type'],
								'quiz_answer' => $value_qiz['quiz_answer'],
								'quiz_limit' => $value_qiz['quiz_limit'],
								'quiz_limitval' => $value_qiz['quiz_limitval'],
								'quiz_maxscore' => $value_qiz['quiz_maxscore'],
								'period_open' => $value_qiz['period_open'],
								'period_end' => $value_qiz['period_end']
							);

							$data_qiz['emp_id'] = $user['emp_id'];
							$data_qiz['time_create'] = date('Y-m-d H:i');
							$qiz_id = $this->course->create_quiz($data_qiz);
							if($qiz_id!=""){
								$query_ques = $this->course->result_data('lms_ques','qiz_id',$value_qiz['qiz_id'],'','');
								foreach ($query_ques as $key_ques => $value_ques) {
									$data_ques = array(
										'qiz_id' => $qiz_id,
										'ques_type' => $value_ques['ques_type'],
										'ques_name_th' => $value_ques['ques_name_th'],
										'ques_name_en' => $value_ques['ques_name_en'],
										'ques_info_th' => $value_ques['ques_info_th'],
										'ques_info_en' => $value_ques['ques_info_en'],
										'ques_score' => $value_ques['ques_score'],
										'ques_show' => $value_ques['ques_show'],
										'time_create' => date('Y-m-d H:i'),
										'time_mod' => date('Y-m-d H:i')
									);
									$ques_id = $this->course->create_question($data_ques);
									if($value_ques['ques_type']=="multi"){
										$query_multi = $this->manage->query_data_onupdate($value_ques['ques_id'],'lms_ques_mul','ques_id');
										$data_ques_multi = array(
											'ques_id' => $ques_id,
											'mul_c1_th' => $query_multi['mul_c1_th'],
											'mul_c2_th' => $query_multi['mul_c2_th'],
											'mul_c3_th' => $query_multi['mul_c3_th'],
											'mul_c4_th' => $query_multi['mul_c4_th'],
											'mul_c5_th' => $query_multi['mul_c5_th'],
											'mul_answer' => $query_multi['mul_answer'],
											'mul_c1_en' => $query_multi['mul_c1_en'],
											'mul_c2_en' => $query_multi['mul_c2_en'],
											'mul_c3_en' => $query_multi['mul_c3_en'],
											'mul_c4_en' => $query_multi['mul_c4_en'],
											'mul_c5_en' => $query_multi['mul_c5_en']
										);
										$this->course->create_question_multi($data_ques_multi);
									}
								}
							}
						}
					}
					
					$query_survey = $this->course->result_data('lms_survey','cos_id',$cos_id,'','');
					if(countArray($query_survey)>0){
						foreach ($query_survey as $key_survey => $value_survey) {
							$data_survey = array(
								'cos_id' => $cos_id,
								'sv_title_th' => $value_survey['sv_title_th'],
								'sv_title_en' => $value_survey['sv_title_en'],
								'sv_explanation_th' => $value_survey['sv_explanation_th'],
								'sv_explanation_en' => $value_survey['sv_explanation_en'],
								'time_mod' => date('Y-m-d H:i'),
								'sv_suggestion_status' => $value_survey['sv_suggestion_status'],
								'survey_open' => $value_survey['survey_open'],
								'survey_end' => $value_survey['survey_end'],
								'time_create' => date('Y-m-d H:i'),
								'qn_id' => $value_survey['qn_id']
							);
							$sv_id = $this->course->create_survey($data_survey);
							$query_survey_detail = $this->course->result_data('lms_survey_de','sv_id',$value_survey['sv_id'],'','');
							if(countArray($query_survey_detail)>0){
								foreach ($query_survey_detail as $key_survey_detail => $value_survey_detail) {
									$data_qn = array(
										'sv_id' => $sv_id,
										'svde_heading_th' => $value_survey_detail['svde_heading_th'],
										'svde_detail_th' => $value_survey_detail['svde_detail_th'],
										'svde_heading_en' => $value_survey_detail['svde_heading_en'],
										'svde_detail_en' => $value_survey_detail['svde_detail_en']
									);
									$this->course->create_survey_detail($data_qn);
								}
							}
						}
					}
					echo "2";
				}else{
					echo "0";
				}
			}else{
				echo "0";
			}
	}
}
