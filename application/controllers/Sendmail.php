<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sendmail extends CI_Controller {



	public function sentmail_svuser_single(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
	    date_default_timezone_set("Asia/Bangkok");
	    $sess = $this->session->userdata("user");
   		$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			//print_r($_REQUEST);
   		$status_save = "";
   		$output = array();
		if(countArray($_REQUEST)>0){
			$fetch_svtc = $this->func_query->query_row('lms_sv_tc','lms_sv','lms_sv_tc.sv_id = lms_sv.sv_id','','svtc_id="'.$_REQUEST['svtc_id'].'"');
			if(countArray($fetch_svtc)>0){
				$fetch_user = $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_emp.emp_id="'.$fetch_svtc['emp_id'].'"');
				if($lang=="thai"){
				$periodstart = $fetch_svtc['sv_open']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_svtc['sv_open'])).$thaimonth[intval(date('m',strtotime($fetch_svtc['sv_open'])))].(date('Y',strtotime($fetch_svtc['sv_open']))+543)." ".date('H:i',strtotime($fetch_svtc['sv_open'])):"";
				$periodend = $fetch_svtc['sv_end']!="0000-00-00 00:00:00"?date('d',strtotime($fetch_svtc['sv_end'])).$thaimonth[intval(date('m',strtotime($fetch_svtc['sv_end'])))].(date('Y',strtotime($fetch_svtc['sv_end']))+543)." ".date('H:i',strtotime($fetch_svtc['sv_end'])):"";
				}else{
			            $periodstart = $fetch_svtc['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_svtc['sv_open'])):"";
			            $periodend = $fetch_svtc['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_svtc['sv_end'])):"";
				}
				$period = label('UnlimitedTime');
				if($periodstart!=""&&$periodend!=""){
					$period = $periodstart." - ".$periodend;
				}


                  if($lang=="thai"){ 
                    $sv_title = $fetch_svtc['sv_title_th']!=""?$fetch_svtc['sv_title_th']:$fetch_svtc['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$fetch_svtc['sv_title_jp'];
                  }else if($lang=="english"){ 
                    $sv_title = $fetch_svtc['sv_title_eng']!=""?$fetch_svtc['sv_title_eng']:$fetch_svtc['sv_title_th'];
                    $sv_title = $sv_title!=""?$sv_title:$fetch_svtc['sv_title_jp'];
                  }else{
                    $sv_title = $fetch_svtc['sv_title_jp']!=""?$fetch_svtc['sv_title_jp']:$fetch_svtc['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$fetch_svtc['sv_title_th'];
                  }
				$fetch_setmail = $this->func_query->query_row('lms_setting_mail','','','','sm_id="1"');
				$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form','','','','smf_show="1" and smf_type="11"');
				if(countArray($fetch_formatmail)>0){
              		$fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_user['com_id'].'"');
		            $subject_th = $fetch_formatmail['smf_subject_th'];
		            $subject_en = $fetch_formatmail['smf_subject_en'];
		            $message_th = $fetch_formatmail['smf_message_th'];
		            $message_en = $fetch_formatmail['smf_message_en'];
		            if($subject_th!=""){
		                $subject_th = str_replace("#fullname",$fetch_user['fullname_th'],$subject_th);
		                $subject_th = str_replace("#username",$fetch_user['useri'],$subject_th);
		                $subject_th = str_replace("#email",$fetch_user['email'],$subject_th);
		                $subject_th = str_replace("#coursename",$sv_title,$subject_th);
		                $subject_th = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$fetch_svtc['sv_id'],$subject_th);
		                $subject_th = str_replace("#date",date('d').$thaimonth[intval(date('m'))].(date('Y')+543),$subject_th);
		                $subject_th = str_replace("#time",date('H:i'),$subject_th);
		                $subject_th = str_replace("#perioddate",$period,$subject_th);
                        $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
					}
		            if($subject_en!=""){
		                $subject_en = str_replace("#fullname",$fetch_user['fullname_en'],$subject_en);
		                $subject_en = str_replace("#username",$fetch_user['useri'],$subject_en);
		                $subject_en = str_replace("#email",$fetch_user['email'],$subject_en);
		                $subject_en = str_replace("#coursename",$sv_title,$subject_en);
		                $subject_en = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$fetch_svtc['sv_id'],$subject_en);
		                $subject_en = str_replace("#date",date('d').$thaimonth[intval(date('m'))].(date('Y')+543),$subject_en);
		                $subject_en = str_replace("#time",date('H:i'),$subject_en);
		                $subject_en = str_replace("#perioddate",$period,$subject_en);
                        $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
		            }
                      if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
                          $img_val = '<img src="'.base_url().'/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
                      }else{
                          $img_val = '';
                      }
		            if($message_th!=""){
		                $message_th = str_replace("#fullname",$fetch_user['fullname_th'],$message_th);
		                $message_th = str_replace("#username",$fetch_user['useri'],$message_th);
		                $message_th = str_replace("#email",$fetch_user['email'],$message_th);
		                $message_th = str_replace("#coursename",$sv_title,$message_th);
		                $message_th = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$fetch_svtc['sv_id'],$message_th);
		                $message_th = str_replace("#date",date('d').$thaimonth[intval(date('m'))].(date('Y')+543),$message_th);
		                $message_th = str_replace("#time",date('H:i'),$message_th);
		                $message_th = str_replace("#perioddate",$period,$message_th);
                        $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
                          $message_th = str_replace("#image",$img_val,$message_th);
		            }
		            if($message_en!=""){
		                $message_en = str_replace("#fullname",$fetch_user['fullname_en'],$message_en);
		                $message_en = str_replace("#username",$fetch_user['useri'],$message_en);
		                $message_en = str_replace("#email",$fetch_user['email'],$message_en);
		                $message_en = str_replace("#coursename",$sv_title,$message_en);
		                $message_en = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$fetch_svtc['sv_id'],$message_en);
		                $message_en = str_replace("#date",date('d').$thaimonth[intval(date('m'))].(date('Y')+543),$message_en);
		                $message_en = str_replace("#time",date('H:i'),$message_en);
		                $message_en = str_replace("#perioddate",$period,$message_en);
                        $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
                          $message_en = str_replace("#image",$img_val,$message_en);
		            }
		            if($lang == "thai") {
		              $this->db->sendEmail( $fetch_user['email'] , $message_th, $subject_th,$fetch_setmail);
		            } else {
		              $this->db->sendEmail( $fetch_user['email'] , $message_en, $subject_en,$fetch_setmail);
		            }
					$output['status'] = "2";
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


}
?>