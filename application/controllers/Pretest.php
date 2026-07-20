<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pretest extends CI_Controller {

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

	public function detail($qcode)
	{
		$arr['page'] = 'course/loadCourse';
		$this->load->model('User_model', 'login', TRUE);
		$this->load->model('Quiz_model', 'quiz', FALSE);
		$this->load->model('Question_model', 'question', FALSE);
		$this->load->model('Lang_model', 'langM', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		$this->load->model('Enroll_model', 'enroll', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->enroll->loadDB();
		$this->manage->loadDB();
		$this->quiz->loadDB();
		$this->langM->loadDB();
		$this->course->loadDB();
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$arr['lang'] = $lang;
		$arr['qcode'] = $qcode;
		$user = $this->session->userdata('user');
		$arr['emp_c'] = $user['emp_c'];
		$arr['com_admin'] = $user['com_admin'];
		$arr['com_id'] = $user['com_id'];
		if($lang=="thai"){
			$arr['com_name'] = $user['com_name_th'];
		}else{
			$arr['com_name'] = $user['com_name_en'];
		}
		$arr['user'] = $user;
		if($this->login->checkSession($arr['page'])){
			$arr['query_qiz'] = $this->func_query->query_row('lms_qiz','','','','qiz_id = "'.$qcode.'"');
			$arr['question'] = $this->course->query_data_onupdate_result($qcode, 'lms_ques','qiz_id');
			
			foreach ($arr['question'] as $key_ques => $value_ques) {
				$arr['question'][$key_ques]['tc_answer'] = "";
				$arr['question'][$key_ques]['mul_c1_th'] = "";
				$arr['question'][$key_ques]['mul_c1_en'] = "";
				$arr['question'][$key_ques]['mul_c2_th'] = "";
				$arr['question'][$key_ques]['mul_c2_en'] = "";
				$arr['question'][$key_ques]['mul_c3_th'] = "";
				$arr['question'][$key_ques]['mul_c3_en'] = "";
				$arr['question'][$key_ques]['mul_c4_th'] = "";
				$arr['question'][$key_ques]['mul_c4_en'] = "";
				$arr['question'][$key_ques]['mul_c5_th'] = "";
				$arr['question'][$key_ques]['mul_c5_en'] = "";
				$arr['question'][$key_ques]['qiz_status'] = "";
				$arr['question'][$key_ques]['mul_answer'] = "";

					$this->db->where('qiz_id',$value_ques['qiz_id']);
					$this->db->where('ques_id',$value_ques['ques_id']);
					$this->db->where('emp_id',$user['emp_id']);
					$this->db->from('lms_ques_tc');
					$query_tc = $this->db->get();
					$num_tc = $query_tc->num_rows();
					if($num_tc>0){
						$fetch_tc = $query_tc->row_array();
						$arr['question'][$key_ques]['tc_answer'] = $fetch_tc['tc_answer'];
					}
					$this->db->where('qiz_id',$value_ques['qiz_id']);
					$this->db->where('emp_id',$user['emp_id']);
					$this->db->from('lms_qiz_tc');
					$query_tc = $this->db->get();
					$num_tc = $query_tc->num_rows();
					if($num_tc>0){
						$fetch_tc = $query_tc->row_array();
						$arr['question'][$key_ques]['qiz_status'] = $fetch_tc['qiz_status'];
					}

				if($value_ques['ques_type']=="multi"){
					$this->db->where('ques_id',$value_ques['ques_id']);
					$this->db->from('lms_ques_mul');
					$query_multi = $this->db->get();
					$num_multi = $query_multi->num_rows();
					if($num_multi>0){
						$fetch_muli = $query_multi->row_array();
						$arr['question'][$key_ques]['mul_c1_th'] = $fetch_muli['mul_c1_th'];
						$arr['question'][$key_ques]['mul_c1_en'] = $fetch_muli['mul_c1_en'];
						$arr['question'][$key_ques]['mul_c2_th'] = $fetch_muli['mul_c2_th'];
						$arr['question'][$key_ques]['mul_c2_en'] = $fetch_muli['mul_c2_en'];
						$arr['question'][$key_ques]['mul_c3_th'] = $fetch_muli['mul_c3_th'];
						$arr['question'][$key_ques]['mul_c3_en'] = $fetch_muli['mul_c3_en'];
						$arr['question'][$key_ques]['mul_c4_th'] = $fetch_muli['mul_c4_th'];
						$arr['question'][$key_ques]['mul_c4_en'] = $fetch_muli['mul_c4_en'];
						$arr['question'][$key_ques]['mul_c5_th'] = $fetch_muli['mul_c5_th'];
						$arr['question'][$key_ques]['mul_c5_en'] = $fetch_muli['mul_c5_en'];
						$arr['question'][$key_ques]['mul_answer'] = $fetch_muli['mul_answer'];
					}
				}
			}
			$arr['qiz'] = $this->course->query_data_onupdate($qcode, 'lms_qiz','qiz_id');
			$this->course->firsttime_les($arr['qiz']['cos_id']);
			$arr['course'] = $this->course->query_data_onupdate($arr['qiz']['cos_id'], 'lms_cos','id');
			!$this->login->checkSession($arr['page']) ? redirect( base_url()."dashboard") : $arr['page'];
			//$quizs = $this->quiz->getAllData($qcode);

			$arr['is_Enroll'] = $this->course->rechk_enroll($arr['qiz']['cos_id'],$user['emp_id']);
			if(countArray($arr['is_Enroll'])==0){
				redirect( base_url()."course/loadCourse");
			}
			$fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_status="3" and qiz_id="'.$qcode.'" and emp_id="'.$user['emp_id'].'"');

	        if($arr['qiz']['quiz_type']=="1"){
				//$arr['is_QuizBeforeClass'] = $this->course->IsQuizBeforeClass($arr['qiz']['cos_id'],$user['emp_id']);
				if(countArray($fetch_chk)>0){
					redirect( base_url()."course/detail/".$arr['qiz']['cos_id']);
				}
	        }
	        	$arr['arr_permission'] = $this->manage->chk_permission_page();
				$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
				$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
				if($arr['btn_view']!="1"){
					redirect(base_url().'dashboard') ;
				}
			$this->course->closeDB();
			$this->langM->closeDB();
			$this->question->closeDB();
			$this->quiz->closeDB();
			//Record Log activity
			$this->load->model('Log_model', 'lg', FALSE);
			$this->lg->loadDB();
			$this->lg->record('quiz', 'enter Pretest id '.$qcode.'.');
			$this->lg->closeDB();

			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->view('frontend/pretest_detail', $arr);
		}
	}

	public function saveQuestion($qiz_id)
	{
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', FALSE);
		$this->course->loadDB();
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';
		$ques_id = isset($_REQUEST['ques_id']) ? $_REQUEST['ques_id'] : '';
		$tc_answer = isset($_REQUEST['tc_answer']) ? $_REQUEST['tc_answer'] : '';

		for ($i=0; $i < countArray($ques_id); $i++) { 
			//echo $ques_id[$i].":::".$tc_answer[$i];
			$result = $this->course->answer_data_onques_last($qiz_id,$ques_id[$i],$tc_answer[$i]);
		}
		$result = $this->course->update_time_qiz($qiz_id,'time_save','lms_qiz_tc');

        $this->db->from('lms_ques_tc');
        $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
        $this->db->where('lms_ques_tc.emp_id',$emp_id);
        $query_row = $this->db->get();
        $num_row = $query_row->num_rows();
        $fetch_row = $query_row->result_array();
        $tc_save = 0;
        foreach ($fetch_row as $key => $value) {
        	if($value['tc_save']=="1"){
        		$tc_save++;
        	}
        }
        if($tc_save!=$num_row){
        	$arr['warning_msg'] = "disable";
        }else{
        	$arr['warning_msg'] = "enable";
        }
		$arr['msg'] = "2";
		echo json_encode($arr);
	}

	public function reTCQuestion($qiz_id)
	{
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', FALSE);
		$this->course->loadDB();
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';

        $this->db->from('lms_ques_tc');
        $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
        $this->db->where('lms_ques_tc.emp_id',$emp_id);
        $query_row = $this->db->get();
        $num_row = $query_row->num_rows();
        $tc_save = 0;
        if($num_row>0){
       		$fetch_row = $query_row->result_array();
	        foreach ($fetch_row as $key => $value) {
	        	if($value['tc_save']=="1"){
	        		$tc_save++;
	        	}
	        }
        }else{
	        $this->db->from('lms_ques');
	        $this->db->where('lms_ques.qiz_id',$qiz_id);
	        $query_row = $this->db->get();
	        $num_row = $query_row->num_rows();
        }
        if($tc_save!=$num_row){
        	$arr['warning_msg'] = "disable";
        }else{
        	$arr['warning_msg'] = "enable";
        }
		$arr['msg'] = "2";
		echo json_encode($arr);
	}

	public function reTCQiz($qiz_id)
	{
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->course->loadDB();
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';

              $fetch_rechk_tc = $this->func_query->query_row('lms_qiz_tc','lms_qiz','lms_qiz.qiz_id = lms_qiz_tc.qiz_id','','lms_qiz_tc.emp_id="'.$emp_id.'" and lms_qiz_tc.qiz_id="'.$qiz_id.'"');

        	  $tc_limit = 1;
              if(countArray($fetch_rechk_tc)>0){
                  $fetch_ques = $this->func_query->query_result('lms_ques','lms_ques_mul','lms_ques.ques_id = lms_ques_mul.ques_id','','lms_ques.qiz_id="'.$qiz_id.'" and lms_ques.ques_show="1" and lms_ques.ques_status="1"');
                  $fetch_ques_tc = $this->func_query->query_result('lms_ques_tc','','','','lms_ques_tc.qiz_id="'.$qiz_id.'" and lms_ques_tc.emp_id="'.$emp_id.'"');
                  $score_ori = 0;
                  if(countArray($fetch_ques)==countArray($fetch_ques_tc)){
                      foreach ($fetch_ques as $key_ori => $value_ori) {
                        $score_ori+=floatval($value_ori['ques_score']);
                      }
                      if($score_ori>0&&floatval($fetch_rechk_tc['sum_score'])>0){
                          $score_student = (floatval($fetch_rechk_tc['sum_score'])/$score_ori)*100;
                          if($score_student>=floatval($fetch_rechk_tc['quiz_maxscore'])){
                            if($fetch_rechk_tc['quiz_limit']=="1"&&floatval($fetch_rechk_tc['quiz_limitval'])<(floatval($fetch_rechk_tc['limit_val'])+1)){
                                $tc_limit = 0;
                            }
                            if($fetch_rechk_tc['quiz_limit']=="0"){
                                $tc_limit = 0;
                            }
                          }
                      }
                  }
              }

       /* $this->db->from('lms_qiz');
        $this->db->where('lms_qiz.qiz_id',$qiz_id);
        $query_qiz = $this->db->get();
        $num_qiz = $query_qiz->num_rows();
        $fetch_qiz = $query_qiz->row_array();

        $this->db->from('lms_qiz_tc');
        $this->db->where('lms_qiz_tc.qiz_id',$qiz_id);
        $this->db->where('lms_qiz_tc.emp_id',$emp_id);
        $query_row = $this->db->get();
        $num_row = $query_row->num_rows();
        $fetch_row = $query_row->row_array();

        $tc_limit = 1;
        if($fetch_qiz['quiz_limit']=="1"){
        	if(intval($fetch_qiz['quiz_limitval'])>0){
        		if((intval($fetch_row['limit_val'])+1)>intval($fetch_qiz['quiz_limitval'])){
        			$tc_limit = 0;
        		}
        	}
        }
        
        if($fetch_row['qiz_status']=="3"){
                            $this->db->from('lms_ques_tc');
                            $this->db->where('lms_ques_tc.qiz_id', $qiz_id);
                            $this->db->where('lms_ques_tc.emp_id', $emp_id);
                            $query_questc = $this->db->get();
                            $fetch_questc = $query_questc->result_array();
                            $score_total = 0;
                            foreach ($fetch_questc as $key => $value) {
                                $this->db->from('lms_ques');
                                $this->db->where('lms_ques.qiz_id', $qiz_id);
                                $this->db->where('lms_ques.ques_id', $value['ques_id']);
                                $query_score_ques = $this->db->get();
                                $fetch_score_ques = $query_score_ques->row_array();
                                $score_total += floatval($fetch_score_ques['ques_score']);
                            }
                            $sum_score = (floatval($fetch_row['sum_score'])/$score_total)*100;
                            if($sum_score>=floatval($fetch_qiz['quiz_maxscore'])){
                              		$tc_limit = 0;
                            }
        }*/

        if($tc_limit!=1){
        	$arr['warning_msg'] = "disable";
        }else{
        	$arr['warning_msg'] = "enable";
        }
		$arr['msg'] = "2";
		echo json_encode($arr);
	}

}
