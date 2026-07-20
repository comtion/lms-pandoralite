<?php 
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
/*set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/IOFactory.php';
require_once 'PHPExcel.php';*/
require_once "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$object_connect = mysqli_fetch_array($query_obj);

$bold = [
    'font' => [
        'bold' => true,
    ],
];
$border = [
    'borders' => [ // กำหนดเส้นขอบ
        'allBorders' => [ // กำหนดเส้นขอบทั้งหม
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$where = 'cos_approve="1" and cos_public="1" and cos_expire_noti!="" and cos_status="1" and cos_isDelete="0" and lms_cos_detail.date_end!="0000-00-00 00:00:00" and lms_cos_detail.date_end >= "'.date('Y-m-d').'"';// and lms_cos.cname_eng = "How you like that_EN"
$db->where($where);
$db->join('lms_cos_detail','lms_cos_detail.cos_id = lms_cos.cos_id');
$fetch_courseexp = $db->get('lms_cos');
$lang = "english";
if(count($fetch_courseexp)>0){
			foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
				if($value_courseexp['cos_expire_noti']!=""){
					$cos_expire_noti = explode(",",$value_courseexp['cos_expire_noti']);
					$numrechk = 0;
					$numtotal = 0;
					$num_chk = 0;
					for ($i=0; $i < count($cos_expire_noti); $i++) { 
						$numtotal++;
						if(isset($cos_expire_noti[$i])&&$cos_expire_noti[$i]!=""){
							$numrechk++;
							if(date('Y-m-d')<=date('Y-m-d',strtotime($value_courseexp['date_end']))){
								if($cos_expire_noti[$i]=="0"){
									$date_selectend = date('Y-m-d',strtotime($value_courseexp['date_end']));
								}else{
									$date_selectend = date('Y-m-d',strtotime($value_courseexp['date_end'].' -'.$cos_expire_noti[$i].'day'));
								}
								$date_now = date('Y-m-d');
								//echo $date_now.":::".$date_selectend."<br>";
								if($date_now!=$date_selectend){
									$num_chk++;
									//unset($fetch_courseexp[$key_courseexp]);
								}
							}else{
								unset($fetch_courseexp[$key_courseexp]);
							}
						}
					}
					if($num_chk>=count($cos_expire_noti)){
						unset($fetch_courseexp[$key_courseexp]);
					}
					if($numrechk==0){
						unset($fetch_courseexp[$key_courseexp]);
					}
				}else{
					unset($fetch_courseexp[$key_courseexp]);
				}
			}
		}
		// print_r($fetch_courseexp);
			if(count($fetch_courseexp)>0){
            $arr_emailmanager = array();
			foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
					$where = 'cos_id="'.$value_courseexp['cos_id'].'" and cosen_status="1" and cosen_status_sub!="1" and cosen_isDelete="0"';
					$db->where($where);
					$db->orderBy('cosen_id');
					$fetch_chkuser = $db->get('lms_cos_enroll');
					//print_r($fetch_chkuser);
				        if(count($fetch_chkuser)>0){
				        		foreach ($fetch_chkuser as $key_chkuser => $value_chkuser) {
				        			//echo $value_chkuser['emp_id']."::";
									$where_if = 'lms_emp.emp_id="'.$value_chkuser['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
									$db->where($where_if);
									$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
									$fetch_userposi = $db->getOne('lms_emp');
				            		if(isset($fetch_userposi['emp_manage_a'])){

									/*$db->where('com_id="'.$fetch_userposi['com_id'].'"');
									$fetch_company = $db->getOne('lms_company');*/
									//print_r($fetch_userposi['email']);
				            			//echo $fetch_userposi['email']."::233:::<br>";
		            						//array_push($arr_email, $fetch_userposi['email']);
		            						if($fetch_userposi['emp_manage_a']!=""){
		            							$arr_emailmanager[$fetch_userposi['emp_manage_a']] = array();
		            						}
		            						if($fetch_userposi['emp_manage_b']!=""){
		            							$arr_emailmanager[$fetch_userposi['emp_manage_a']] = array();
		            						}
		            						//echo $value_userposi['email']."::";
				            		}
				        		}
				        	}
			}
			$arr_cosid = array();
				foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
				
                	$arr_email = array();

					$cos_lang = explode(',', $value_courseexp['cos_lang']);
					$value_courseexp['isTH'] = in_array('th',$cos_lang)?"1":"0";
					$value_courseexp['isENG'] = in_array('eng',$cos_lang)?"1":"0";
					$value_courseexp['isJP'] = in_array('jp',$cos_lang)?"1":"0";
					$cname = "";
		            if($lang=="thai"){
						$cos_langtxt = "th";
		                if($value_courseexp['isTH']=="1"){
		                  $cname = $value_courseexp['cname_th'];
		                }else{
		                  if($cname==""&&$value_courseexp['isENG']=="1"){
		                    $cname = $value_courseexp['cname_eng'];
		                  }
		                  if($cname==""&&$value_courseexp['isJP']=="1"){
		                    $cname = $value_courseexp['cname_jp'];
		                  }
		                }
		            }else if($lang=="english"){
						$cos_langtxt = "eng";
		                if($value_courseexp['isENG']=="1"){
		                  $cname = $value_courseexp['cname_eng'];
		                }else{
		                  if($cname==""&&$value_courseexp['isTH']=="1"){
		                    $cname = $value_courseexp['cname_th'];
		                  }
		                  if($cname==""&&$value_courseexp['isJP']=="1"){
		                    $cname = $value_courseexp['cname_jp'];
		                  }
		                }
		            }else{
						$cos_langtxt = "jp";
		                if($value_courseexp['isJP']=="1"){
		                  $cname = $value_courseexp['cname_jp'];
		                }else{
		                  if($cname==""&&$value_courseexp['isENG']=="1"){
		                    $cname = $value_courseexp['cname_eng'];
		                  }
		                  if($cname==""&&$value_courseexp['isTH']=="1"){
		                    $cname = $value_courseexp['cname_th'];
		                  }
		                }
		            }
					
					$where = 'cos_id="'.$value_courseexp['cos_id'].'" and cosen_status="1" and cosen_status_sub!="1" and cosen_isDelete="0"';
					$db->where($where);
					$db->orderBy('cosen_id');
					$fetch_chkuser = $db->get('lms_cos_enroll');
					//print_r($fetch_chkuser);
					if(count($fetch_chkuser)>0){

						$where = 'smf_show="1" and smf_type="14"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');
						foreach ($fetch_chkuser as $key_chkuser => $value_chkuser) {
							//echo $value_chkuser['emp_id']."::";
							$where_if = 'lms_emp.emp_id="'.$value_chkuser['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'"'.
										' or lms_usp.inactivedate = "0000-00-00 00:00:00")';
							$db->where($where_if);
							$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
							$fetch_userposi = $db->getOne('lms_emp');
							if(isset($fetch_userposi['emp_c']) && !in_array($fetch_userposi['emp_c'], $arr_email)){
									array_push($arr_email, $fetch_userposi['emp_c']);
									if($fetch_userposi['emp_manage_a']!=""&&$fetch_userposi['emp_manage_a']!=$fetch_userposi['emp_c']){
										$output = array(
											'mail_emp' => $fetch_userposi['email'],
											'emp_id' => $fetch_userposi['emp_id'],
											'cos' => $value_courseexp['cos_id']
										);
										if(!isset($arr_emailmanager[$fetch_userposi['emp_manage_a']])){
											$arr_emailmanager[$fetch_userposi['emp_manage_a']] = array();
										}
										if(!in_array($value_courseexp['cos_id'], $arr_cosid)){
											array_push($arr_cosid, $value_courseexp['cos_id']);
										}
										array_push($arr_emailmanager[$fetch_userposi['emp_manage_a']], $output);

									}
									if($fetch_userposi['emp_manage_b']!=""&&$fetch_userposi['emp_manage_b']!=$fetch_userposi['emp_c']){
										$output = array(
											'mail_emp' => $fetch_userposi['email'],
											'emp_id' => $fetch_userposi['emp_id'],
											'cos' => $value_courseexp['cos_id']
										);
										if(!isset($arr_emailmanager[$fetch_userposi['emp_manage_b']])){
											$arr_emailmanager[$fetch_userposi['emp_manage_b']] = array();
										}
										if ($fetch_userposi['emp_manage_a'] != $fetch_userposi['emp_manage_b']) {
											if(!in_array($value_courseexp['cos_id'], $arr_cosid)){
												array_push($arr_cosid, $value_courseexp['cos_id']);
											}
											array_push($arr_emailmanager[$fetch_userposi['emp_manage_b']], $output);
										}
									}
							}
						}
					}

				}
				echo "----------------<br>";
				if(count($arr_emailmanager)>0){
					// print_r($arr_emailmanager);
					foreach ($arr_emailmanager as $key_manager => $value_manager) {

						$where_if = 'lms_usp.useri="'.$key_manager.'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'"'.
									' or lms_usp.inactivedate = "0000-00-00 00:00:00")';
						$db->where($where_if);
						$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
						$fetch_manager = $db->getOne('lms_emp');

						if(count($value_manager)>0){
							for ($cosid_val=0; $cosid_val < count($arr_cosid); $cosid_val++) { 

								$where_if = 'lms_cos.cos_id="'.$arr_cosid[$cosid_val].'"';
								$db->where($where_if);
								$db->join('lms_cos_detail','lms_cos_detail.cos_id = lms_cos.cos_id');
								$fetch_cos = $db->getOne('lms_cos');
								if(isset($fetch_cos['cos_lang'])){
									$cos_lang = explode(',', $fetch_cos['cos_lang']);
									$fetch_cos['isTH'] = in_array('th',$cos_lang)?"1":"0";
									$fetch_cos['isENG'] = in_array('eng',$cos_lang)?"1":"0";
									$fetch_cos['isJP'] = in_array('jp',$cos_lang)?"1":"0";
									$cname = "";
									if($lang=="thai"){
										if($fetch_cos['isTH']=="1"){
											$cname = $fetch_cos['cname_th'];
										}else{
											if($cname==""&&$fetch_cos['isENG']=="1"){
											$cname = $fetch_cos['cname_eng'];
											}
											if($cname==""&&$fetch_cos['isJP']=="1"){
											$cname = $fetch_cos['cname_jp'];
											}
										}
									}else if($lang=="english"){
										if($fetch_cos['isENG']=="1"){
											$cname = $fetch_cos['cname_eng'];
										}else{
											if($cname==""&&$fetch_cos['isTH']=="1"){
											$cname = $fetch_cos['cname_th'];
											}
											if($cname==""&&$fetch_cos['isJP']=="1"){
											$cname = $fetch_cos['cname_jp'];
											}
										}
									}else{
										if($fetch_cos['isJP']=="1"){
											$cname = $fetch_cos['cname_jp'];
										}else{
											if($cname==""&&$fetch_cos['isENG']=="1"){
											$cname = $fetch_cos['cname_eng'];
											}
											if($cname==""&&$fetch_cos['isTH']=="1"){
											$cname = $fetch_cos['cname_th'];
											}
										}
									}

									$date_end = "";
									if($fetch_cos['date_start']!="0000-00-00 00:00:00" && $fetch_cos['date_end']!="0000-00-00 00:00:00"){
										if($lang=="thai"){
											$periodstart = $fetch_cos['date_start']!="0000-00-00 00:00:00" ? 
															date('d ',strtotime($fetch_cos['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_cos['date_start'])))]." ".
															(date('Y',strtotime($fetch_cos['date_start']))+543)." ".
															date('H:i',strtotime($fetch_cos['date_start'])):"";
											$periodend = $fetch_cos['date_end']!="0000-00-00 00:00:00" ? 
															date('d ',strtotime($fetch_cos['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos['date_end'])))]." ".
															(date('Y',strtotime($fetch_cos['date_end']))+543)." ".
															date('H:i',strtotime($fetch_cos['date_end'])):"";
											$date_end = $periodend;
										}else{
											$periodstart = $fetch_cos['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos['date_start'])):"";
											$periodend = $fetch_cos['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos['date_end'])):"";
											$date_end = $periodend;
										}
										
										if($periodstart!=""&&$periodend!=""){
												$period = $periodstart." - ".$periodend;
										}

										$date = date('d ',strtotime($fetch_cos['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos['date_end'])))]." ".
												(date('Y',strtotime($fetch_cos['date_end']))+543);
										if($lang!="thai"){
											$date = date('d F Y',strtotime($fetch_cos['date_end']));
										}
									}

									$chkisMail = 0;
									for ($i=0; $i < count($value_manager); $i++) { 
										if($arr_cosid[$cosid_val]==$value_manager[$i]['cos']){
											$chkisMail++;
										}
									}

									if($chkisMail>0){
										$objPHPExcel = new Spreadsheet();

										$objPHPExcel->setActiveSheetIndex(0);

										$activeSheet = $objPHPExcel->getActiveSheet();
										$activeSheet->getColumnDimension('A')->setAutoSize(true);
										$current_date = date('d-m-Y' . '@' . 'h-i-a');
										$filedate = explode('@', $current_date, -1);
										$explode_filedate = explode('-', $filedate[0]);
										$reformatted_filedate = $explode_filedate[0] . $explode_filedate[1] . $explode_filedate[2];
										$filename = $fetch_manager['emp_c'] . "-Course-incomplete-report".$arr_cosid[$cosid_val];
										$extension = '.xls';
										$filename = $reformatted_filedate.$fetch_manager['emp_c']."-Course-incomplete-report".$arr_cosid[$cosid_val]. $extension;

										$activeSheet->setCellValue('A1', 'Employees name');
										$activeSheet->setCellValue('B1', 'Company Code');
										$activeSheet->setCellValue('C1', 'Department');
										$icolumn = 2;
										$crow = 2;

										for ($i=0; $i < count($value_manager); $i++) { 
											if($arr_cosid[$cosid_val]==$value_manager[$i]['cos']){
												$where_if = 'lms_emp.emp_id="'.$value_manager[$i]['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and '.
															'(lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
												//echo $where_if;
												$db->where($where_if);
												$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
												$db->join('lms_company','lms_company.com_id = lms_emp.com_id');
												$db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
												$fetch_emp = $db->getOne('lms_emp');
												if(isset($fetch_emp['fname_en'])){

													$staff_name = $fetch_emp['fname_en']." ".$fetch_emp['lname_en'];
													$company_code = $fetch_emp["com_code"];
													$dep_name = $fetch_emp["dep_name_en"];
													//echo $staff_name."::".$cname;
													$activeSheet->setCellValue('A' . $icolumn, $staff_name);
													$activeSheet->setCellValue('B' . $icolumn, $company_code);
													$activeSheet->setCellValue('C' . $icolumn, $dep_name);
													$icolumn++;
													//print_r($value_manager[$i]);
												}
											}
										}
										$objPHPExcel->getActiveSheet()->getStyle('A1:C'.($icolumn-1))->applyFromArray($border);
										$Excel_writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
										$Excel_writer->save('file_excel/' . $filename);
										$attachment = 'file_excel/' . $filename;

										$where = 'smf_show="1" and smf_type="18"';
										$db->where($where);
										$fetch_formatmail = $db->getOne('lms_sendmail_form');
										if(isset($fetch_formatmail['smf_subject_th'])){
											//echo $key_manager.":::355<br>";
											$where_if = 'lms_usp.useri="'.$key_manager.'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'"'.
														' or lms_usp.inactivedate = "0000-00-00 00:00:00")';
											$db->where($where_if);
											$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
											$fetch_manager = $db->getOne('lms_emp');
											
											$db->where('com_id="'.$fetch_manager['com_id'].'"');
											$fetch_company = $db->getOne('lms_company');
											$subject_th = $fetch_formatmail['smf_subject_th'];
											$subject_en = $fetch_formatmail['smf_subject_en'];
											$message_th = $fetch_formatmail['smf_message_th'];
											$message_en = $fetch_formatmail['smf_message_en'];
											if($subject_th!=""){
												$subject_th = str_replace("#fullname",$fetch_manager['fullname_th'],$subject_th);
												$subject_th = str_replace("#username",$fetch_manager['useri'],$subject_th);
												$subject_th = str_replace("#email",$fetch_manager['email'],$subject_th);
												$subject_th = str_replace("#coursename",$cname,$subject_th);
												$subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$arr_cosid[$cosid_val],$subject_th);
												$subject_th = str_replace("#date",$date,$subject_th);
												$subject_th = str_replace("#time",date('H:i'),$subject_th);
												$subject_th = str_replace("#perioddate",$period,$subject_th);
												$subject_th = str_replace("#expiredate",$date_end,$subject_th);
												$subject_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$subject_th);
												$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
											}
											if($subject_en!=""){
												$subject_en = str_replace("#fullname",$fetch_manager['fullname_en'],$subject_en);
												$subject_en = str_replace("#username",$fetch_manager['useri'],$subject_en);
												$subject_en = str_replace("#email",$fetch_manager['email'],$subject_en);
												$subject_en = str_replace("#coursename",$cname,$subject_en);
												$subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$arr_cosid[$cosid_val],$subject_en);
												$subject_en = str_replace("#date",$date,$subject_en);
												$subject_en = str_replace("#time",date('H:i'),$subject_en);
												$subject_en = str_replace("#perioddate",$period,$subject_en);
												$subject_en = str_replace("#expiredate",$date_end,$subject_en);
												$subject_en = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$subject_en);
												$subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
											}
											if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
												$img_val = '<img src="https://elearning.isuzu.co.th/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
											}else{
												$img_val = '';
											}
											if($message_th!=""){
												$message_th = str_replace("#fullname",$fetch_manager['fullname_th'],$message_th);
												$message_th = str_replace("#username",$fetch_manager['useri'],$message_th);
												$message_th = str_replace("#email",$fetch_manager['email'],$message_th);
												$message_th = str_replace("#coursename",$cname,$message_th);
												$message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$arr_cosid[$cosid_val],$message_th);
												$message_th = str_replace("#date",$date,$message_th);
												$message_th = str_replace("#time",date('H:i'),$message_th);
												$message_th = str_replace("#perioddate",$period,$message_th);
												$message_th = str_replace("#expiredate",$date_end,$message_th);
												$message_th = str_replace("#image",$img_val,$message_th);
												$message_th = str_replace("#stafflist","",$message_th);
												$message_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_th);
												$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
											}
											if($message_en!=""){
												$message_en = str_replace("#fullname",$fetch_manager['fullname_en'],$message_en);
												$message_en = str_replace("#username",$fetch_manager['useri'],$message_en);
												$message_en = str_replace("#email",$fetch_manager['email'],$message_en);
												$message_en = str_replace("#coursename",$cname,$message_en);
												$message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$arr_cosid[$cosid_val],$message_en);
												$message_en = str_replace("#date",$date,$message_en);
												$message_en = str_replace("#time",date('H:i'),$message_en);
												$message_en = str_replace("#perioddate",$period,$message_en);
												$message_en = str_replace("#expiredate",$date_end,$message_en);
												$message_en = str_replace("#image",$img_val,$message_en);
												$message_en = str_replace("#stafflist","",$message_en);
												$message_en = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_en);
												$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
											}
											if($lang == "thai") {
												echo $fetch_manager['email'].":".$cname."-th<br>";
												sendEmail( $fetch_manager['email'] , $message_th, $subject_th, $object_connect, $attachment);
											} else {
												echo $fetch_manager['email'].":".$cname."-en<br>";
												sendEmail($fetch_manager['email'], $message_en, $subject_en, $object_connect, $attachment);
											}
										}
									}
								}
												

							}
						}
					}
				}
			}

function sendEmail($email, $message ,$subject,$object_connect,$attachment){
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
	    $mail->addAttachment($attachment);
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