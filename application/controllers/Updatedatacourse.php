<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updatedatacourse extends CI_Controller {

	public function updateStatusLessonAll () {
	    date_default_timezone_set("Asia/Bangkok");
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'funcQuery', FALSE);
	    $this->funcQuery->loadDB();
	    $sess = $this->session->userdata("user");
	    $cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
	    $cosen_id = isset($_REQUEST['cosen_id']) ? $_REQUEST['cosen_id'] : "";

		$arrTC = array();
		$fetchLes = $this->funcQuery->query_result('lms_les', '', '', '', 'cos_id = "'.$cos_id.'" and les_isDelete = 0');
		if (sizeof($fetchLes) > 0 && isset($sess['emp_id'])) {
			foreach ($fetchLes as $keyLes => $valueLes) {
				$statusTc = 0;
				$fetchTc = $this->funcQuery->query_row('lms_les_tc', '', '', '', 'cosen_id = "'.$cosen_id.'" and les_id = "'.$valueLes['les_id'].'"');
				if (isset($fetchTc['learn_status'])) {
					$statusTc = intval($fetchTc['learn_status']);
				}
				$output = array(
					'lesId' => $valueLes['les_id'],
					'statusTc' => $statusTc
				);
				array_push($arrTC, $output);
			}
		}
		echo json_encode($arrTC);
	}

  	public function update_fil(){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $fil_id = isset($_REQUEST['fil_id'])?$_REQUEST['fil_id']:"";
	    $cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
	    $fetch_chkles = $this->func_query->query_row('lms_fil','','','','id="'.$fil_id.'"');
	    $numchk = $this->func_query->numrows('lms_fil_log','','','','fil_id="'.$fil_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_id="'.$cosen_id.'"');
	    if($numchk==0){
	    	$arr_insert = array(
	    		'emp_id'=>$sess['emp_id'],
	    		'fil_id'=>$fil_id,
				'cosen_id' => $cosen_id
	    	);
	    	$this->db->insert('lms_fil_log',$arr_insert);
	    	$this->update_lesson($fetch_chkles['lessons_id']);
	    }
	}

  	public function update_med(){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $med_id = isset($_REQUEST['med_id'])?$_REQUEST['med_id']:"";
	    $cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
	    $fetch_chkles = $this->func_query->query_row('lms_med','','','','id="'.$med_id.'"');
	    $numchk = $this->func_query->numrows('lms_med_tc','','','','med_id="'.$med_id.'" and cosen_id="'.$cosen_id.'"');
	    if($numchk==0){
	    	$arr_insert = array(
	    		'emp_id'		=> $sess['emp_id'],
	    		'med_id'		=> $med_id,
	    		'medtc_datetime'=> date('Y-m-d H:i'),
				'cosen_id' 		=> $cosen_id,
				'medtc_status' 	=> '2'
	    	);
	    	$this->db->insert('lms_med_tc',$arr_insert);
	    } else {
            $arrayUpdate = array(
	    		'medtc_datetime'=> date('Y-m-d H:i'),
				'medtc_status' 	=> '2'
            );
            $this->db->where('med_id = "'.$med_id.'" and cosen_id = "'.$cosen_id.'"');
            $this->db->update('lms_med_tc', $arrayUpdate);
		}
		$this->update_lesson($fetch_chkles['lessons_id']);
	}

	public function update_lesson($les_id){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
	    $fetch_chkles = $this->func_query->query_row('lms_les','','','','les_id="'.$les_id.'"');
		$value_total = 0;
		$total = 0;
		$output = array();
	    if(isset($sess['emp_id']) && isset($fetch_chkles['les_type']) && $fetch_chkles['les_type']=="1"){
		    $fetch_chkmed = $this->func_query->query_result('lms_med','','','','lessons_id="'.$les_id.'"');
		    $fetch_chkfil = $this->func_query->query_result('lms_fil','','','','lessons_id="'.$les_id.'"');
		    $total = countArray($fetch_chkmed);//+countArray($fetch_chkfil)
		    if(countArray($fetch_chkmed)>0){
			    foreach ($fetch_chkmed as $key_chkmed => $value_chkmed) {
			    	$fetch_chkmedtc = $this->func_query->numrows('lms_med_tc','','','',
					'med_id="'.$value_chkmed['id'].'" and cosen_id="'.$cosen_id.'" and medtc_status = 2');
			    	if($fetch_chkmedtc>0){
			    		$value_total++;
			    	}
			    }
		    }
		    /*if(countArray($fetch_chkfil)>0){
			    foreach ($fetch_chkfil as $key_chkfil => $value_chkfil) {
			    	$fetch_chkfiltc = $this->func_query->numrows('lms_fil_log','','','','fil_id="'.$value_chkfil['id'].'" and emp_id="'.$sess['emp_id'].'"');
			    	if($fetch_chkfiltc>0){
			    		$value_total++;
			    	}
			    }
		    }*/
		    $learn_status = "0";
		    if($value_total>0){
		    	if($value_total==$total){
		    		$learn_status = "2";
		    	}else{
		    		$learn_status = "1";
		    	}
		    }else{
		    	if(countArray($fetch_chkmed)==0){
		    		$learn_status = "2";
		    	}else{
		    		$learn_status = "1";
		    	}
		    }
		    if(isset($sess['emp_id']) && $sess['emp_id'] != "" && $les_id != ""){
			    $fetch_chkstatus = $this->func_query->query_row('lms_les_tc','','','','les_id="'.$les_id.'" and cosen_id="'.$cosen_id.'"');
			    if(isset($fetch_chkstatus)){
			    	if($fetch_chkstatus['learn_status']!="2"){
				    	$arr_insert = array(
				    		'emp_id'  		=> $sess['emp_id'],
				    		'les_id'  		=> $les_id,
				    		'learn_status'	=> $learn_status
				    	);
				    	$this->db->where('lestc_id',$fetch_chkstatus['lestc_id']);
				    	$this->db->update('lms_les_tc',$arr_insert);
			    	}
			    }else{
			    	$arr_insert = array(
			    		'emp_id'=>$sess['emp_id'],
			    		'les_id'=>$les_id,
			    		'learn_status'=>$learn_status,
						'cosen_id' => $cosen_id
			    	);
			    	$this->db->insert('lms_les_tc',$arr_insert);
			    }
			    /*if($learn_status=="2"){
			    	$this->endcos($fetch_chkles['cos_id']);
			    }*/
		    }
		    $output['status'] = $learn_status;
	    }else{
	    	$fetch_chkstatus = $this->func_query->query_row('lms_les_tc','','','','les_id="'.$les_id.'" and cosen_id="'.$cosen_id.'"');
	    	$output['status'] = isset($fetch_chkstatus['learn_status']) ? $fetch_chkstatus['learn_status'] : 0;
	    }
	    echo json_encode($output);
	}

	public function rechk_status_lesson(){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
    	date_default_timezone_set("Asia/Bangkok");
		$date_now = date('Y-m-d H:i');
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
		$output = array();
		$cos_id = isset($_REQUEST['cos_id'])?$_REQUEST['cos_id']:"";
		$cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
		if($cos_id!="" && isset($sess['emp_id'])){

			$value_status = 0;
			$numles = $this->func_query->numrows('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1" and ((time_start="0000-00-00 00:00:00" and time_end="0000-00-00 00:00:00") or (time_start <= "'.$date_now.'" and  time_end >= "'.$date_now.'"))');
			$fetch_chktc = $this->func_query->query_result('lms_les_tc','','','','les_id in (select les_id from lms_les where cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1" and ((time_start="0000-00-00 00:00:00" and time_end="0000-00-00 00:00:00") or (time_start <= "'.$date_now.'" and  time_end >= "'.$date_now.'"))) and cosen_id="'.$cosen_id.'"');
			if(countArray($fetch_chktc)){
				foreach ($fetch_chktc as $key_chktc => $value_chktc) {
					if($value_chktc['learn_status']=="2"){
						$value_status++;
					} else if ($value_chktc['learn_status']=="1") {
						$value_status += 0.5;
					}
				}
			}
		    $output['status'] = $value_status;
		    $output['numles'] = $numles;
		    if($value_status==$numles){
		    	$this->endcos($cos_id);
		    }
		}else{
			$output['status'] = 'error';
		}
	    echo json_encode($output);
	}

	public function insert_survey_tc(){
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $sv_id = isset($_REQUEST['sv_id'])?$_REQUEST['sv_id']:"";
	    $cosen_id = isset($_REQUEST['cosen_id'])?$_REQUEST['cosen_id']:"";
	    $qnu_suggestion = isset($_REQUEST['qnu_suggestion'])?$_REQUEST['qnu_suggestion']:"";

	    $svde_id = isset($_REQUEST['svde_id'])?$_REQUEST['svde_id']:"";
	    $qnude_var = isset($_REQUEST['qnude_var'])?$_REQUEST['qnude_var']:"";
	    $qnude_suggestion = isset($_REQUEST['qnude_suggestion'])?$_REQUEST['qnude_suggestion']:"";
	    $fetch_main = $this->func_query->numrows('lms_qn_user','','','','emp_id="'.$sess['emp_id'].'" and sv_id="'.$sv_id.'" and cosen_id="'.$cosen_id.'"');
	    $output = array();
	    if($fetch_main==0 && isset($sess['emp_id'])){
			$fetch_enroll = $this->func_query->query_row(
				'lms_cos_enroll','','','',
				'cosen_id="'.$cosen_id.'"'
			);
			if (isset($fetch_enroll["cos_id"])) {
				$arr_main = array(
					'sv_id' => $sv_id,
					'emp_id' => $sess['emp_id'],
					'qnu_suggestion' => $qnu_suggestion,
					'qnu_datetime' => date('Y-m-d H:i'),
					'qnu_status' => '1',
					'cosen_id' => $cosen_id
				);
				$this->db->insert('lms_qn_user',$arr_main);
				$qnu_id = $this->db->insert_id();
				if($qnu_id!=""){
					if(countArray($svde_id)>0){
						for ($i=0; $i < countArray($svde_id); $i++) { 
							if(isset($svde_id[$i])){
								$arr_detail = array(
									'qnu_id' => $qnu_id,
									'svde_id' => $svde_id[$i],
									'qnude_var' => isset($qnude_var[$i])?$qnude_var[$i]:"",
									'qnude_suggestion' => isset($qnude_suggestion[$i])?$qnude_suggestion[$i]:""
								);
								$fetch_chkdetail = $this->func_query->numrows('lms_qn_user_de','','','','qnu_id="'.$qnu_id.'" and svde_id="'.$svde_id[$i].'"');
								if($fetch_chkdetail==0){
									$this->db->insert('lms_qn_user_de',$arr_detail);
								}
							}
						}
					}
					$this->endcos($fetch_enroll["cos_id"]);
					$output['status'] = "1";
				}else{
					$output['status'] = "0";
				}
			}else{
				$output['status'] = "0";
			}
	    }else{
	    	$output['status'] = "0";
	    }
	    echo json_encode($output);
	}

	public function endcos($cos_id) {
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
	    $this->lang->load($lang,$lang);
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    $this->load->model('Course_model', 'course', FALSE);
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
	    $this->func_query->loadDB();
	    $fetch_chkcos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
	    if(countArray($fetch_chkcos)>0 && isset($sess['emp_id'])){

            $fetch_enroll = $this->func_query->query_row(
				'lms_cos_enroll','','','',
				'cos_id="'.$cos_id.'" and emp_id="'.$sess['emp_id'].'" and cosen_status="1" and cosen_status_sub!="1" and cosen_lang!="" and cosen_isDelete="0"',
				'cosen_id DESC');
            if(isset($fetch_enroll)){
            	$cosen_id = $fetch_enroll['cosen_id'];
		    	$status_cos = 0;
		    	$amount_les = 0;
		    	$amount_qiz = 0;
	            $score = 0;
	            $total = 0;
		    	$fetch_qiz = $this->func_query->query_result(
					'lms_qiz','','','',
					'cos_id="'.$cos_id.'" and quiz_type="2" and quiz_show="1" and quiz_status="1" and quiz_isDelete="0"');
		    	$num_chk_qiz = 0;
		    	$numloopqiz = 0; 
		    	$numloopqizpass = 0; 
		    	if(countArray($fetch_qiz)>0){
					// ตรวจสอบคะแนน และผลการผ่านในแบบทดสอบ
	              	foreach ($fetch_qiz as $key_qiz => $value_qiz) {
	                    $fetch_chk = $this->func_query->query_row(
							'lms_qiz_tc','','','',
							'qiz_id="'.$value_qiz['qiz_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
	                    if(isset($fetch_chk)){
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
	                    $fetch_chk = $this->func_query->query_row(
							'lms_qiz_tc','','','',
							'qiz_id="'.$value_qiz['qiz_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
	                    if(isset($fetch_chk)){
	                    	$amount_qiz++;
		                    $fetch_questc = $this->func_query->query_result(
								'lms_ques_tc','','','',
								'qiz_id="'.$value_qiz['qiz_id'].'" and cosen_id="'.$cosen_id.'" and qiztc_id="'.$fetch_chk['qiztc_id'].'"');
		                    if(countArray($fetch_questc)==intval($value_qiz['quiz_numofshown'])){
		                      $num_chk_qiz++;
		                    }
			                
		                    // คะแนนที่ผู้เรียนทำได้ทั้งหมด
		                    $score += countArray($fetch_chk)>0?floatval($fetch_chk['sum_score']):0;
	                    }
	                    $fetch_sum = $this->func_query->query_row(
							'lms_ques','','','',
							'qiz_id="'.$value_qiz['qiz_id'].'" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="'.$value_qiz['qiz_id'].'" and cosen_id="'.$cosen_id.'") 
							and ques_status="1" and ques_isDelete="0"','',
							'SUM(ques_score) as total_score');
		                // คะแนนในแต่ละคำถามทั้งหมด
						$total += isset($fetch_sum)?floatval($fetch_sum['total_score']):0;
	                }
		    	}
				// ตรวจสอบในกรณีที่วิชานั้นมีบทเรียน จะต้องเรียนเสร็จสิ้นทุกบทเรียน
		    	$fetch_lesson = $this->func_query->query_result(
					'lms_les','','','',
					'cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1"');
		    	if(countArray($fetch_lesson)>0){
		    		foreach ($fetch_lesson as $key_lesson => $value_lesson) {
		    			$fetch_lestc = $this->func_query->query_row(
							'lms_les_tc','','','',
							'les_id="'.$value_lesson['les_id'].'" and cosen_id="'.$cosen_id.'"');
		    			if(isset($fetch_lestc)){
		    				if($fetch_lestc['learn_status']=="2"){
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
	            if($total>0){
	            	if($score>=0&&$total>0){
	            		$cosen_score = $score;
			            $cosen_score_per = ($score/$total)*100;
			            $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
			            if(isset($fetch_cug)){
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
			                $cosen_status_sub = 1; // debug 100
			                $cosen_finishtime = date('Y-m-d H:i');
			            }else{
			            	if($numloopqizpass==$numloopqiz){
			            		$cosen_status_sub = 1; // debug 200
			                	$cosen_finishtime = date('Y-m-d H:i');
			            	}else{
			                	$cosen_status_sub = 2;
			            	}
			            }
		            }
	            }else{
					// กรณีที่วิชานั้นไม่มีแบบทดสอบ
	            	$cosen_score = 100;
	            	$cosen_score_per = 100;
								$cosen_status_sub = 1; // debug 300
								$cosen_finishtime = date('Y-m-d H:i');

			        $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
			        if(isset($fetch_cug)){
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
	            }
	            $val_cosen = 0;
	            $total_couse = 0;
	            

	            $fetch_les = $this->func_query->numrows('lms_les','','','','les_isDelete="0" and les_status="1" and cos_id="'.$cos_id.'"');
	            $fetch_lestc = $this->func_query->numrows('lms_les_tc','','','','learn_status="2" and cosen_id="'.$cosen_id.'"');
	            $fetch_qiz = $this->func_query->numrows('lms_qiz','','','','quiz_isDelete="0" and quiz_show="1" and cos_id="'.$cos_id.'"');
	            $fetch_qiztc = $this->func_query->numrows('lms_qiz_tc','','','','qiz_status="3" and cosen_id="'.$cosen_id.'"');
	            $fetch_sv = $this->func_query->numrows('lms_survey','','','','sv_isDelete="0" and sv_status="1" and cos_id="'.$cos_id.'"');
	            $fetch_svtc = $this->func_query->numrows('lms_qn_user','','','','qnu_status="1" and cosen_id="'.$cosen_id.'"');
				// ตรวจสอบจำนวนของการทำบทเรียนว่าเสร็จสิ้นทั้งหมด 
	            if($fetch_les>0){
	            	$total_couse++;
		            if($fetch_les<=$fetch_lestc){
		            	$val_cosen++;
		            }
	            }
				// ตรวจสอบจำนวนของการทำแบบทดสอบว่าเสร็จสิ้นทั้งหมด 
	            if($fetch_qiz>0){

	            	$fetch_qiz_query = $this->func_query->query_result('lms_qiz','','','','quiz_isDelete="0" and quiz_show="1" and cos_id="'.$cos_id.'"');
	            	if(countArray($fetch_qiz_query)>0){
	            		foreach ($fetch_qiz_query as $key_qiz_query => $value_qiz_query) {
							$numcheck_qiz = $this->func_query->numrows(
								'lms_qiz_tc','','','',
								'cosen_id="'.$cosen_id.'"',
								'qiztc_id DESC');
							$numcheck_qizpass = $this->func_query->query_row(
								'lms_qiz_tc','','','',
								'cosen_id="'.$cosen_id.'" and qiz_id = "'.$value_qiz_query['qiz_id'].'"',
								'qiztc_id DESC');
							$fetch_chksh_lg = isset($numcheck_qizpass['qiztc_id']) ? $this->func_query->numrows(
								'lms_ques','','','',
								'lms_ques.qiz_id="'.$value_qiz_query['qiz_id'].'" and ques_status="1" and ques_isDelete="0" and ques_type in ("sub","sa")
								 and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where cosen_id="'.$cosen_id.'" and qiztc_id = "'.$numcheck_qizpass['qiztc_id'].'")') : 0;
							if($fetch_chksh_lg>0 && isset($numcheck_qizpass['qiztc_id'])){
								$total_couse++;
								$isEndTest = 1;
								if($value_qiz_query['quiz_limit']=="1"){
									if ($numcheck_qiz<intval($value_qiz_query['quiz_limitval'])) {
										if(floatval($numcheck_qizpass['per_score'])<floatval($value_qiz_query['quiz_maxscore'])){
											$isEndTest = 0;
										}
									}
								}else{
									if(floatval($numcheck_qizpass['per_score'])<floatval($value_qiz_query['quiz_maxscore'])){
										$isEndTest = 0;
									}
								}
								if(countArray($numcheck_qizpass)>0&&$numcheck_qizpass['qiz_status']=="3"&&$isEndTest==1){
									$fetch_chktc_sa = $this->func_query->numrows(
										'lms_ques_tc','','','',
										'cosen_id="'.$cosen_id.'" and qiztc_id = "'.$numcheck_qizpass['qiztc_id'].'" and 
										lms_ques_tc.ques_id in (select lms_ques.ques_id from lms_ques where lms_ques.qiz_id="'.$value_qiz_query['qiz_id'].'" and ques_status="1" 
										and ques_isDelete="0" and ques_type in ("sub","sa"))');
									if($fetch_chktc_sa>=$fetch_chksh_lg){
										$val_cosen++;
									}
								}
							}
							$fetch_chksh_lg_notsub = isset($numcheck_qizpass['qiztc_id']) ? $this->func_query->numrows(
								'lms_ques','','','',
								'lms_ques.qiz_id="'.$value_qiz_query['qiz_id'].'" and ques_status="1" and ques_isDelete="0" and ques_type not in ("sub","sa") and 
								ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where cosen_id="'.$cosen_id.'" and qiztc_id = "'.$numcheck_qizpass['qiztc_id'].'")') : 0;
							if($fetch_chksh_lg_notsub>0 && isset($numcheck_qizpass['qiztc_id'])){
								$total_couse++;
								$isEndTest = 1;
								if($value_qiz_query['quiz_limit']=="1"){
									if ($numcheck_qiz<intval($value_qiz_query['quiz_limitval'])) {
										if(floatval($numcheck_qizpass['per_score'])<floatval($value_qiz_query['quiz_maxscore'])){
											$isEndTest = 0;
										}
									}
								}else{
									if(floatval($numcheck_qizpass['per_score'])<floatval($value_qiz_query['quiz_maxscore'])){
										$isEndTest = 0;
									}
								}
								if(countArray($numcheck_qizpass)>0&&$numcheck_qizpass['qiz_status']=="3"&&$isEndTest==1){
									$fetch_chktc_sa = $this->func_query->numrows(
										'lms_ques_tc','','','',
										'cosen_id="'.$cosen_id.'" and qiztc_id = "'.$numcheck_qizpass['qiztc_id'].'" and lms_ques_tc.ques_id in 
										(select lms_ques.ques_id from lms_ques where lms_ques.qiz_id="'.$value_qiz_query['qiz_id'].'" and ques_status="1" and 
										ques_isDelete="0" and ques_type not in ("sub","sa"))');
									if($fetch_chktc_sa>=$fetch_chksh_lg_notsub){
										$val_cosen++;
									}
								}
							}

	            		}
	            	}
	            }
	            if($total_couse==$val_cosen){
		            if($cosen_finishtime!="0000-00-00 00:00:00" && $cosen_finishtime !=""){
		            	$fetch_bad = $this->func_query->query_row('lms_bad','','','','courses_id="'.$cos_id.'"');
		                if(isset($fetch_bad)){
		                	$score_pass = 0;
		                	if($fetch_bad['badges_condition']=="P"){
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
		                		}else{
		                			$score_pass = 0;
		                		}
		                	}
							$cosen_score_per = round($cosen_score_per);
							// ออกใบประกาศนีย์บัตร
		                	if($cosen_score_per>=$score_pass){
	            
		                   		$this->course->update_cert($cos_id,$sess);	
		                	}
		                }
		            }
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
	            }else{
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
	            $this->db->where('cosen_id',$fetch_enroll['cosen_id']);
	            $this->db->update('lms_cos_enroll',$arr_update);
            }
            
	    }
	}
}
