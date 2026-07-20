<?php 
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$fetch_setmail = mysqli_fetch_array($query_obj);
$lang = "english";
$sql_rechk = 'select * from lms_emp inner join lms_usp on lms_emp.emp_id = lms_usp.emp_id where lms_emp.emp_createdate like "%2020-08-11%" and lms_usp.firsttime="1"';
$query_rechk = mysqli_query($conndb,$sql_rechk);
$num_rechk = mysqli_num_rows($query_rechk);
if($num_rechk>0){
	$date = date('d F Y');
	while ($fetch_userposi = mysqli_fetch_array($query_rechk)) {
		
						$where = 'smf_show="1" and smf_type="1"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');

										if($fetch_userposi['email']!=""){

													$password = generateRandomString();
													$password_enc = hash('sha256', $password);
													$sql_update = 'update lms_usp set userp = "'.$password_enc.'" where emp_id = "'.$fetch_userposi['emp_id'].'"';
													echo $sql_update."<br>";
													//mysqli_query($conndb,$sql_update);

													$db->where('com_id="'.$fetch_userposi['com_id'].'"');
													$fetch_company = $db->getOne('lms_company');

		            								//array_push($arr_email, $fetch_userposi['email']);
									              	$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];

									              	if($subject_th!=""){
							                            $subject_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$subject_th);
							                            $subject_th = str_replace("#username",$fetch_userposi['useri'],$subject_th);
							                            $subject_th = str_replace("#email",$fetch_userposi['email'],$subject_th);
							                            $subject_th = str_replace("#coursename","",$subject_th);
							                            $subject_th = str_replace("#password",$password,$subject_th);
							                            $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/",$subject_th);
							                            $subject_th = str_replace("#date",$date,$subject_th);
							                            $subject_th = str_replace("#time",date('H:i'),$subject_th);
	                        							$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
							                        }
							                        if($subject_en!=""){
							                            $subject_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$subject_en);
							                            $subject_en = str_replace("#username",$fetch_userposi['useri'],$subject_en);
							                            $subject_en = str_replace("#email",$fetch_userposi['email'],$subject_en);
							                            $subject_en = str_replace("#coursename","",$subject_en);
							                            $subject_en = str_replace("#password",$password,$subject_en);
							                            $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/",$subject_en);
							                            $subject_en = str_replace("#date",$date,$subject_en);
							                            $subject_en = str_replace("#time",date('H:i'),$subject_en);
							                            $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
							                        }
							                        if($message_th!=""){
							                            $message_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$message_th);
							                            $message_th = str_replace("#username",$fetch_userposi['useri'],$message_th);
							                            $message_th = str_replace("#email",$fetch_userposi['email'],$message_th);
							                            $message_th = str_replace("#coursename","",$message_th);
							                            $message_th = str_replace("#password",$password,$message_th);
							                            $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/",$message_th);
							                            $message_th = str_replace("#date",$date,$message_th);
							                            $message_th = str_replace("#time",date('H:i'),$message_th);
							                            $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
							                        }
							                        if($message_en!=""){
							                            $message_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$message_en);
							                            $message_en = str_replace("#username",$fetch_userposi['useri'],$message_en);
							                            $message_en = str_replace("#email",$fetch_userposi['email'],$message_en);
							                            $message_en = str_replace("#coursename","",$message_en);
							                            $message_en = str_replace("#password",$password,$message_en);
							                            $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/",$message_en);
							                            $message_en = str_replace("#date",$date,$message_en);
							                            $message_en = str_replace("#time",date('H:i'),$message_en);
							                            $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
							                        }
									               
									               if($lang == "thai") {
									                //sendEmail( $fetch_userposi['email'] , $message_th, $subject_th,$fetch_setmail);
				            			echo $fetch_userposi['email']."::84:<br>";
									                } else {
									                //sendEmail( $fetch_userposi['email'] , $message_en, $subject_en,$fetch_setmail);
				            			echo $fetch_userposi['email']."::181::<br>";
									                }
				            			}
	}
}
	function generateRandomString($length = 8) {
	    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
	function sendEmail($email, $message ,$subject,$object_connect){
		//require_once 'class/phpmailer/PHPMailerAutoload.php';
		header('Content-Type: text/html; charset=utf-8');
		$sub = "ข้อความจากเว็บไซต์";
		$mail = new PHPMailer;
	    $mail->CharSet = "utf-8";
	     
	    $mail->isSMTP();
	    $mail->Host = $object_connect['sm_host'];//'mail.verztec.com';
	    $mail->Port = $object_connect['sm_port'];//587;
	    //$mail->SMTPSecure = 'tls';
	    if($object_connect['sm_smtpauth']=="true"){
	    	$mail->SMTPAuth = true;
	    }else{
	    	$mail->SMTPAuth = false;
	    }
	    //true;
	     
	    $gmail_username = $object_connect['sm_username'];//"pandora@verztec.com"; // gmail ที่ใช้ส่ง
	    $gmail_password = $object_connect['sm_password'];//"pppp99999"; // รหัสผ่าน gmail
	    // ตั้งค่าอนุญาตการใช้งานได้ที่นี่ https://myaccount.google.com/lesssecureapps?pli=1
	    $mail->SMTPOptions = array(
	            'ssl' => array(
	                'verify_peer' => false,
	                'verify_peer_name' => false,
	                'allow_self_signed' => true
	            )
	        );
	     
	    $sender = $object_connect['sm_sender'];//"THAIHEALTH LMS"; // ชื่อผู้ส่ง
	    $email_sender = $object_connect['sm_emailsender'];//"pandora@verztec.com"; // เมล์ผู้ส่ง 
	    $email_receiver = $email; // เมล์ผู้รับ ***
	     	     
	     
	    $mail->Username = $gmail_username;
	    $mail->Password = $gmail_password;
	    $mail->setFrom($email_sender, $sender);
	    $mail->addAddress($email_receiver);
	    $mail->Subject = $subject;
	    if($email_receiver){
	        $mail->msgHTML($message);
	        if (!$mail->send()) {  // สั่งให้ส่ง email
	            // กรณีส่ง email ไม่สำเร็จ
	            //echo "Error_Sentmail";
	            //echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
	        }else{
	            // กรณีส่ง email สำเร็จ
	            //echo "Send Success";
	        }   
	    }

	}
?>