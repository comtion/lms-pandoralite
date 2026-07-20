<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Runscript extends CI_Controller {

	public function runEmailNewUser(){
  		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Function_query_model', 'funcQuery', FALSE);
		$this->funcQuery->loadDB();

        $fetchNewEmp = $this->funcQuery->query_result("lms_usp", "lms_emp", "lms_usp.emp_id = lms_emp.emp_id", "", "lms_emp.emp_isDelete = 0 and lms_emp.emp_createdate like '%2023-07-06%'");
        if (!empty($fetchNewEmp)) {
            $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
            foreach ($fetchNewEmp as $keyEmp) {
                $password = $this->generateRandomString();
                $password_enc = hash('sha256', $password);
                $arr_user = array(
                    'userp' => $password_enc
                );
                
                $this->db->where('u_id',$keyEmp['u_id']);
                $this->db->update('lms_usp',$arr_user);

                $date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
                //if($lang!="thai"){
                    $date = date('d F Y');
                //}
                $fetch_setmail = $this->funcQuery->query_row('lms_setting_mail','','','','sm_id="1"');
                $fetch_formatmail = $this->funcQuery->query_row('lms_sendmail_form','','','','smf_show="1" and smf_type="1"');
                if(count($fetch_formatmail)>0){
                      $fetch_company = $this->funcQuery->query_row('lms_company','','','','com_id="'.$keyEmp['com_id'].'"');
                    $subject_th = $fetch_formatmail['smf_subject_th'];
                    $subject_en = $fetch_formatmail['smf_subject_en'];
                    $message_th = $fetch_formatmail['smf_message_th'];
                    $message_en = $fetch_formatmail['smf_message_en'];
                    if($subject_th!=""){
                        $subject_th = str_replace("#fullname",$keyEmp['fullname_th'],$subject_th);
                        $subject_th = str_replace("#username",$arr_user['useri'],$subject_th);
                        $subject_th = str_replace("#email",$keyEmp['email'],$subject_th);
                        $subject_th = str_replace("#coursename","",$subject_th);
                        $subject_th = str_replace("#password",$password,$subject_th);
                        $subject_th = str_replace("#link_frontend",base_url(),$subject_th);
                        $subject_th = str_replace("#date",$date,$subject_th);
                        $subject_th = str_replace("#time",date('H:i'),$subject_th);
                        $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
                    }
                    if($subject_en!=""){
                        $subject_en = str_replace("#fullname",$keyEmp['fullname_en'],$subject_en);
                        $subject_en = str_replace("#username",$arr_user['useri'],$subject_en);
                        $subject_en = str_replace("#email",$keyEmp['email'],$subject_en);
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
                        $message_th = str_replace("#fullname",$keyEmp['fullname_th'],$message_th);
                        $message_th = str_replace("#username",$arr_user['useri'],$message_th);
                        $message_th = str_replace("#email",$keyEmp['email'],$message_th);
                        $message_th = str_replace("#coursename","",$message_th);
                        $message_th = str_replace("#password",$password,$message_th);
                        $message_th = str_replace("#link_frontend",base_url(),$message_th);
                        $message_th = str_replace("#date",$date,$message_th);
                        $message_th = str_replace("#time",date('H:i'),$message_th);
                        $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
                          $message_th = str_replace("#image",$img_val,$message_th);
                    }
                    if($message_en!=""){
                        $message_en = str_replace("#fullname",$keyEmp['fullname_en'],$message_en);
                        $message_en = str_replace("#username",$arr_user['useri'],$message_en);
                        $message_en = str_replace("#email",$keyEmp['email'],$message_en);
                        $message_en = str_replace("#coursename","",$message_en);
                        $message_en = str_replace("#password",$password,$message_en);
                        $message_en = str_replace("#link_frontend",base_url(),$message_en);
                        $message_en = str_replace("#date",$date,$message_en);
                        $message_en = str_replace("#time",date('H:i'),$message_en);
                        $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
                        $message_en = str_replace("#image",$img_val,$message_en);
                    }
                    
                    $this->db->sendEmail( $keyEmp['email'] , $message_en, $subject_en,$fetch_setmail);
                }
            }
        }

    }

	public function generateRandomString($length = 8) {
	    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}