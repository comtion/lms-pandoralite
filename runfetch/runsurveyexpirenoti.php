<?php
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$fetch_setmail = mysqli_fetch_array($query_obj);
$where = 'lms_sv.sv_approve="1" and lms_sv.sv_public="1" and lms_sv.sv_expire_noti!="" and lms_sv.sv_status="1" and lms_sv.sv_isDelete="0" and lms_sv.sv_end!="'.date('0000-00-00 00:00:00').'" and lms_sv.sv_end >= "'.date('Y-m-d').'"';
$db->where($where);
$fetch_svexp = $db->get('lms_sv');

$lang = "english";
		if(count($fetch_svexp)>0){
			foreach ($fetch_svexp as $key_svexp => $value_svexp) {
				if($value_svexp['sv_expire_noti']!=""){
					$sv_expire_noti = explode(",",$value_svexp['sv_expire_noti']);
					$numrechk = 0;
					$numtotal = 0;
					$num_chk = 0;
					for ($i=0; $i < count($sv_expire_noti); $i++) { 
						$numtotal++;
						if(isset($sv_expire_noti[$i])&&$sv_expire_noti[$i]!=""){
							$numrechk++;
							if(date('Y-m-d')<=date('Y-m-d',strtotime($value_svexp['sv_end']))){
								if($sv_expire_noti[$i]=="0"){
									$date_selectend = date('Y-m-d',strtotime($value_svexp['sv_end']));
								}else{
									$date_selectend = date('Y-m-d',strtotime($value_svexp['sv_end'].' -'.$sv_expire_noti[$i].'day'));
								}
								$date_now = date('Y-m-d');
								if($date_now!=$date_selectend){
									$num_chk++;
								}

							}else{
								unset($fetch_svexp[$key_svexp]);
							}
						}
					}
					if($num_chk>=count($sv_expire_noti)){
						unset($fetch_svexp[$key_svexp]);
					}
					if($numrechk==0){
						unset($fetch_svexp[$key_svexp]);
					}
				}else{
					unset($fetch_svexp[$key_svexp]);
				}
			}
		}
		// echo count($fetch_svexp);
		if(count($fetch_svexp)>0){
			foreach ($fetch_svexp as $key_svexp => $value_svexp) {

                	$arr_email = array();
	              	$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
	              	if($lang!="thai"){
	                 	$date = date('d F Y');
	              	}
	                if($lang=="thai"){ 
	                    $sv_title = $value_svexp['sv_title_th']!=""?$value_svexp['sv_title_th']:$value_svexp['sv_title_eng'];
	                    $sv_title = $sv_title!=""?$sv_title:$value_svexp['sv_title_jp'];
	                }else if($lang=="english"){ 
	                    $sv_title = $value_svexp['sv_title_eng']!=""?$value_svexp['sv_title_eng']:$value_svexp['sv_title_th'];
	                    $sv_title = $sv_title!=""?$sv_title:$value_svexp['sv_title_jp'];
	                }else{
	                    $sv_title = $value_svexp['sv_title_jp']!=""?$value_svexp['sv_title_jp']:$value_svexp['sv_title_eng'];
	                    $sv_title = $sv_title!=""?$sv_title:$value_svexp['sv_title_th'];
	                }
	                echo $sv_title;
                	$date_end = "";
                	$period = "";
                	if($value_svexp['sv_open']!="0000-00-00 00:00:00"&&$value_svexp['sv_end']!="0000-00-00 00:00:00"){
			            if($lang=="thai"){
			            $periodstart = $value_svexp['sv_open']!="0000-00-00 00:00:00"?date('d ',strtotime($value_svexp['sv_open'])).$thaimonth[intval(date('m',strtotime($value_svexp['sv_open'])))]." ".(date('Y',strtotime($value_svexp['sv_open']))+543)." ".date('H:i',strtotime($value_svexp['sv_open'])):"";
			            $periodend = $value_svexp['sv_end']!="0000-00-00 00:00:00"?date('d ',strtotime($value_svexp['sv_end'])).$thaimonth[intval(date('m',strtotime($value_svexp['sv_end'])))]." ".(date('Y',strtotime($value_svexp['sv_end']))+543)." ".date('H:i',strtotime($value_svexp['sv_end'])):"";
                		$date_end = $periodend;
			            }else{
			            $periodstart = $value_svexp['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_svexp['sv_open'])):"";
			            $periodend = $value_svexp['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_svexp['sv_end'])):"";
                		$date_end = $periodend;
			            }
			            
			            if($periodstart!=""&&$periodend!=""){
			              	$period = $periodstart." - ".$periodend;
			            }
		              	$date = date('d ',strtotime($value_svexp['sv_end'])).$thaimonth[intval(date('m',strtotime($value_svexp['sv_end'])))]." ".(date('Y',strtotime($value_svexp['sv_end']))+543);
		              	if($lang!="thai"){
		                 	$date = date('d F Y',strtotime($value_svexp['sv_end']));
		              	}
                	}
                	
				
					$where = 'sv_id="'.$value_svexp['sv_id'].'" and svtc_finishtime="0000-00-00 00:00:00" and svtc_isDelete="0"';
					$db->where($where);
					$db->orderBy('svtc_id');
					$fetch_chkuser = $db->get('lms_sv_tc');
							print_r($fetch_chkuser);
				    if(count($fetch_chkuser)>0){
						$where = 'smf_show="1" and smf_type="15"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');
				        		foreach ($fetch_chkuser as $key_chkuser => $value_chkuser) {
									$where = 'lms_emp.emp_id="'.$value_chkuser['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
									$db->where($where);
									$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
									$fetch_userposi = $db->getOne('lms_emp');
				            		if(isset($fetch_userposi['email'])){
				            			if(!in_array($fetch_userposi['email'], $arr_email)){
												$db->where('com_id="'.$fetch_userposi['com_id'].'"');
												$fetch_company = $db->getOne('lms_company');
		            						array_push($arr_email, $fetch_userposi['email']);
									              	$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];
									               // echo $message_en.":::215:".$period;
									                if($subject_th!=""){
									                  $subject_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$subject_th);
									                  $subject_th = str_replace("#username",$fetch_userposi['useri'],$subject_th);
									                  $subject_th = str_replace("#email",$fetch_userposi['email'],$subject_th);
									                  $subject_th = str_replace("#coursename",$sv_title,$subject_th);
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$subject_th);
									                  $subject_th = str_replace("#date",$date,$subject_th);
									                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
									                  $subject_th = str_replace("#perioddate",$period,$subject_th);
									                  $subject_th = str_replace("#expiredate",$date_end,$subject_th);
                									  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
									                }
									                if($subject_en!=""){
									                  $subject_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$subject_en);
									                  $subject_en = str_replace("#username",$fetch_userposi['useri'],$subject_en);
									                  $subject_en = str_replace("#email",$fetch_userposi['email'],$subject_en);
									                  $subject_en = str_replace("#coursename",$sv_title,$subject_en);
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$subject_en);
									                  $subject_en = str_replace("#date",$date,$subject_en);
									                  $subject_en = str_replace("#time",date('H:i'),$subject_en);
									                  $subject_en = str_replace("#perioddate",$period,$subject_en);
									                  $subject_en = str_replace("#expiredate",$date_end,$subject_en);
                									  $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
									                }
								                      if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
								                          $img_val = '<img src="https://elearning.isuzu.co.th/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
								                      }else{
								                          $img_val = '';
								                      }
									                if($message_th!=""){
									                  $message_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$message_th);
									                  $message_th = str_replace("#username",$fetch_userposi['useri'],$message_th);
									                  $message_th = str_replace("#email",$fetch_userposi['email'],$message_th);
									                  $message_th = str_replace("#coursename",$sv_title,$message_th);
									                  $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$message_th);
									                  $message_th = str_replace("#date",$date,$message_th);
									                  $message_th = str_replace("#time",date('H:i'),$message_th);
									                  $message_th = str_replace("#perioddate",$period,$message_th);
									                  $message_th = str_replace("#expiredate",$date_end,$message_th);
                          								$message_th = str_replace("#image",$img_val,$message_th);
                									  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  $message_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$message_en);
									                  $message_en = str_replace("#username",$fetch_userposi['useri'],$message_en);
									                  $message_en = str_replace("#email",$fetch_userposi['email'],$message_en);
									                  $message_en = str_replace("#coursename",$sv_title,$message_en);
									                  $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$message_en);
									                  $message_en = str_replace("#date",$date,$message_en);
									                  $message_en = str_replace("#time",date('H:i'),$message_en);
									                  $message_en = str_replace("#perioddate",$period,$message_en);
									                  $message_en = str_replace("#expiredate",$date_end,$message_en);
                          								$message_en = str_replace("#image",$img_val,$message_en);
                									  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
				            			//echo $fetch_userposi['email'].":::".$sv_title."  ENG<br>";
									               if($lang == "thai") {
									                sendEmail( $fetch_userposi['email'] , $message_th, $subject_th,$fetch_setmail);
				            			echo $fetch_userposi['email'].":::".$sv_title." TH<br>";
									                } else {
									                sendEmail( $fetch_userposi['email'] , $message_en, $subject_en,$fetch_setmail);
				            			echo $fetch_userposi['email']."::181::".$sv_title."  ENG<br>";
									                }
				            			}
				            		}
				        		}
				        }

					$where = 'sv_id = "'.$value_svexp['sv_id'].'"';
					$db->where($where);
					$fetch_chk_position = $db->get('lms_sv_pm');
                	if(count($fetch_chk_position)>0){

						$where = 'smf_show="1" and smf_type="15"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');
                		foreach ($fetch_chk_position as $key_chk_position => $value_chk_position) {
						    if(isset($fetch_formatmail['smf_subject_th'])){
								$where = 'lms_usp.posi_id="'.$value_chk_position['posi_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
								$db->where($where);
								$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
								$fetch_userposi = $db->get('lms_emp');
			            		if(count($fetch_userposi)>0){
			            			foreach ($fetch_userposi as $key_userposi => $value_userposi) {
			            					$varsend = 0;
											$where = 'sv_id="'.$value_svexp['sv_id'].'" and emp_id="'.$value_userposi['emp_id'].'" and svtc_isDelete="0"';
											$db->where($where);
											$db->orderBy('svtc_id');
											$fetch_chkuser = $db->getOne('lms_sv_tc');
			            					if(isset($fetch_chkuser['svtc_finishtime'])){
												$db->where('com_id="'.$value_userposi['com_id'].'"');
												$fetch_company = $db->getOne('lms_company');
				            					if($fetch_chkuser['svtc_finishtime']!="0000-00-00 00:00:00"){
				            							$varsend = 1;
				            					}
				            					if($varsend==0){
				            						if(!in_array($value_userposi['email'], $arr_email)){
		            								array_push($arr_email, $value_userposi['email']);
									              	$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];
									                if($subject_th!=""){
									                  $subject_th = str_replace("#fullname",$value_userposi['fullname_th'],$subject_th);
									                  $subject_th = str_replace("#username",$value_userposi['useri'],$subject_th);
									                  $subject_th = str_replace("#email",$value_userposi['email'],$subject_th);
									                  $subject_th = str_replace("#coursename",$sv_title,$subject_th);
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$subject_th);
									                  $subject_th = str_replace("#date",$date,$subject_th);
									                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
									                  $subject_th = str_replace("#perioddate",$period,$subject_th);
									                  $subject_th = str_replace("#expiredate",$date_end,$subject_th);
                									  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
									                }
									                if($subject_en!=""){
									                  $subject_en = str_replace("#fullname",$value_userposi['fullname_en'],$subject_en);
									                  $subject_en = str_replace("#username",$value_userposi['useri'],$subject_en);
									                  $subject_en = str_replace("#email",$value_userposi['email'],$subject_en);
									                  $subject_en = str_replace("#coursename",$sv_title,$subject_en);
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$subject_en);
									                  $subject_en = str_replace("#date",$date,$subject_en);
									                  $subject_en = str_replace("#time",date('H:i'),$subject_en);
									                  $subject_en = str_replace("#perioddate",$period,$subject_en);
									                  $subject_en = str_replace("#expiredate",$date_end,$subject_en);
                									  $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
									                }
								                      if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
								                          $img_val = '<img src="https://elearning.isuzu.co.th/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
								                      }else{
								                          $img_val = '';
								                      }
									                if($message_th!=""){
									                  $message_th = str_replace("#fullname",$value_userposi['fullname_th'],$message_th);
									                  $message_th = str_replace("#username",$value_userposi['useri'],$message_th);
									                  $message_th = str_replace("#email",$value_userposi['email'],$message_th);
									                  $message_th = str_replace("#coursename",$sv_title,$message_th);
									                  $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$message_th);
									                  $message_th = str_replace("#date",$date,$message_th);
									                  $message_th = str_replace("#time",date('H:i'),$message_th);
									                  $message_th = str_replace("#perioddate",$period,$message_th);
									                  $message_th = str_replace("#expiredate",$date_end,$message_th);
                          								$message_th = str_replace("#image",$img_val,$message_th);
                									  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  $message_en = str_replace("#fullname",$value_userposi['fullname_en'],$message_en);
									                  $message_en = str_replace("#username",$value_userposi['useri'],$message_en);
									                  $message_en = str_replace("#email",$value_userposi['email'],$message_en);
									                  $message_en = str_replace("#coursename",$sv_title,$message_en);
									                  $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/survey/surveyDetail/".$value_svexp['sv_id'],$message_en);
									                  $message_en = str_replace("#date",$date,$message_en);
									                  $message_en = str_replace("#time",date('H:i'),$message_en);
									                  $message_en = str_replace("#perioddate",$period,$message_en);
									                  $message_en = str_replace("#expiredate",$date_end,$message_en);
                          								$message_en = str_replace("#image",$img_val,$message_en);
                									  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
									                if($lang == "thai") {
									               sendEmail( $value_userposi['email'] , $message_th, $subject_th,$fetch_setmail);
									                } else {
									                sendEmail( $value_userposi['email'] , $message_en, $subject_en,$fetch_setmail);
									                }
									            	}
				            					}
			            					}
			            			}
			            		}
			            	}
		            	}
		            }
                	
			}
		}

function sendEmail($email, $message ,$subject,$object_connect){

	print_r($object_connect);

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
	     	     
	    //  echo $email_sender;
			//  echo $sender;
	    $mail->Username = $gmail_username;
	    $mail->Password = $gmail_password;
	    $mail->setFrom($email_sender,$sender);
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