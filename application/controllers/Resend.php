<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resend extends CI_Controller {

	public function mailfirsttime($u_id){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->manage->loadDB();
	    date_default_timezone_set("Asia/Bangkok");
   		$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$password = $this->generateRandomString();
		$password_enc = hash('sha256', $password);

		$date = date('Y-m-d H:i') ;
		$date = new DateTime($date);
		$date->modify('+90 day');
		$expiredate = date_format($date, 'Y-m-d H:i');


        $fetch_user = $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id="'.$u_id.'" and lms_usp.u_isDelete="0" and lms_usp.firsttime = 1 and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
        if(countArray($fetch_user)>0){
			$fetch_setmail = $this->func_query->query_row('lms_setting_mail','','','','sm_id="1"');
			$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form','','','','smf_show="1" and smf_type="1"');
			$fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_user['com_id'].'"');

						            $date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
						           	$date = date('d F Y');
									if(countArray($fetch_formatmail)>0){
										$arr_update = array(
											'userp' => $password,
											'expiredate' => $expiredate,
										);
										$this->db->where('u_id',$fetch_user['u_id']);
										$this->db->update('lms_usp',$arr_update);
				                        $subject_th = $fetch_formatmail['smf_subject_th'];
				                        $subject_en = $fetch_formatmail['smf_subject_en'];
				                        $message_th = $fetch_formatmail['smf_message_th'];
				                        $message_en = $fetch_formatmail['smf_message_en'];
				                        if($subject_th!=""){
				                            $subject_th = str_replace("#fullname",$fetch_user['fullname_th'],$subject_th);
				                            $subject_th = str_replace("#username",$fetch_user['useri'],$subject_th);
				                            $subject_th = str_replace("#email",$fetch_user['email'],$subject_th);
				                            $subject_th = str_replace("#coursename","",$subject_th);
				                            $subject_th = str_replace("#password",$password,$subject_th);
				                            $subject_th = str_replace("#link_frontend",base_url(),$subject_th);
				                            $subject_th = str_replace("#date",$date,$subject_th);
				                            $subject_th = str_replace("#time",date('H:i'),$subject_th);
				                            $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
				                        }
				                        if($subject_en!=""){
				                            $subject_en = str_replace("#fullname",$fetch_user['fullname_en'],$subject_en);
				                            $subject_en = str_replace("#username",$fetch_user['useri'],$subject_en);
				                            $subject_en = str_replace("#email",$fetch_user['email'],$subject_en);
				                            $subject_en = str_replace("#coursename","",$subject_en);
				                            $subject_en = str_replace("#password",$password,$subject_en);
				                            $subject_en = str_replace("#link_frontend",base_url(),$subject_en);
				                            $subject_en = str_replace("#date",$date,$subject_en);
				                            $subject_en = str_replace("#time",date('H:i'),$subject_en);
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
				                            $message_th = str_replace("#coursename","",$message_th);
				                            $message_th = str_replace("#password",$password,$message_th);
				                            $message_th = str_replace("#link_frontend",base_url(),$message_th);
				                            $message_th = str_replace("#date",$date,$message_th);
				                            $message_th = str_replace("#time",date('H:i'),$message_th);
				                            $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
                          					$message_th = str_replace("#image",$img_val,$message_th);
				                        }
				                        if($message_en!=""){
				                            $message_en = str_replace("#fullname",$fetch_user['fullname_en'],$message_en);
				                            $message_en = str_replace("#username",$fetch_user['useri'],$message_en);
				                            $message_en = str_replace("#email",$fetch_user['email'],$message_en);
				                            $message_en = str_replace("#coursename","",$message_en);
				                            $message_en = str_replace("#password",$password,$message_en);
				                            $message_en = str_replace("#link_frontend",base_url(),$message_en);
				                            $message_en = str_replace("#date",$date,$message_en);
				                            $message_en = str_replace("#time",date('H:i'),$message_en);
				                            $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
				                            $message_en = str_replace("#image",$img_val,$message_en);
				                        }

		                				$lang = "english";
				                        if($lang == "thai") {
				                          $this->db->sendEmail( $fetch_user['email'] , $message_th, $subject_th,$fetch_setmail);
				                        } else {
				                          $this->db->sendEmail( $fetch_user['email'] , $message_en, $subject_en,$fetch_setmail);
				                        }
									}
			echo "Send mail to ".$fetch_user['email']." Done !!!";
        }else{
        	echo "Data not found !!";
        }
	}

	public function generateRandomString($length = 8) {
	    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}
?>