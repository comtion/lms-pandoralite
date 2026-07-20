<?php 
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$object_connect = mysqli_fetch_array($query_obj);

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
			foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
				
                	$arr_email = array();
                	$arr_emailmanager = array();
              	/*$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
              	if($lang!="thai"){
                 	$date = date('d F Y');
              	}*/
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

                echo $cname."<br>";
                $date_end = "";
                	if($value_courseexp['date_start']!="0000-00-00 00:00:00"&&$value_courseexp['date_end']!="0000-00-00 00:00:00"){
			            if($lang=="thai"){
			            $periodstart = $value_courseexp['date_start']!="0000-00-00 00:00:00"?date('d ',strtotime($value_courseexp['date_start'])).$thaimonth[intval(date('m',strtotime($value_courseexp['date_start'])))]." ".(date('Y',strtotime($value_courseexp['date_start']))+543)." ".date('H:i',strtotime($value_courseexp['date_start'])):"";
			            $periodend = $value_courseexp['date_end']!="0000-00-00 00:00:00"?date('d ',strtotime($value_courseexp['date_end'])).$thaimonth[intval(date('m',strtotime($value_courseexp['date_end'])))]." ".(date('Y',strtotime($value_courseexp['date_end']))+543)." ".date('H:i',strtotime($value_courseexp['date_end'])):"";
                		$date_end = $periodend;
			            }else{
			            $periodstart = $value_courseexp['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_courseexp['date_start'])):"";
			            $periodend = $value_courseexp['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_courseexp['date_end'])):"";
                		$date_end = $periodend;
			            }
			            $periodstart = $value_courseexp['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_courseexp['date_start'])):"";
			            $periodend = $value_courseexp['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value_courseexp['date_end'])):"";
                		$date_end = $periodend;
			            
			            if($periodstart!=""&&$periodend!=""){
			              	$period = $periodstart." - ".$periodend;
			            }
		              	$date = date('d ',strtotime($value_courseexp['date_end'])).$thaimonth[intval(date('m',strtotime($value_courseexp['date_end'])))]." ".(date('Y',strtotime($value_courseexp['date_end']))+543);
		              	//if($lang!="thai"){
		                 	$date = date('d F Y',strtotime($value_courseexp['date_end']));
		              	//}
                	}
                	

					$where = 'cos_id="'.$value_courseexp['cos_id'].'" and cosen_status="1" and cosen_status_sub!="1" and cosen_isDelete="0"';
					$db->where($where);
					$db->orderBy('cosen_id');
					$fetch_chkuser = $db->get('lms_cos_enroll');
					// print_r($fetch_chkuser);
				        if(count($fetch_chkuser)>0){

						$where = 'smf_show="1" and smf_type="14"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');
				        		foreach ($fetch_chkuser as $key_chkuser => $value_chkuser) {
				        			// $arrCosNotSendEmail = array(
				        			// 	337 => array("worapat_pakpoomkamon@isuzu.com","nattawut_sathira@isuzu.com","sirasith_nawamongkolwatana@isuzu.com","thananya_ketkaew@isuzu.com","vitoon_kongcharoensukying@isuzu.com","panya_chinveroj@isuzu.com","patchara_hanvanichakul@isuzu.com","kanchana_ucharoen@isuzu.com","thipsukon_sumpakitpol@isuzu.com","chalongchai_boonyiam@isuzu.com","jiradat_prasertsri@isuzu.com","sureewan_saykleang@isuzu.com","juthatip_kaewkluean@isuzu.com","jirapa_surawit@isuzu.com","amata_chamunghatthapong@isuzu.com","bvornkrit_yokvijit@isuzu.com","chirapha_kittisuphalak@isuzu.com","boonkanit_chongcharoen@isuzu.com","chawalit_vithayasintana@isuzu.com","sittichai_kulpradit@isuzu.com","anirut_patthanatheera@isuzu.com","anupong_khumnun@isuzu.com","anuwat_kukamjad@isuzu.com","phapatsarrin_theerawichetkun@isuzu.com","satsaya_treenuchakorn@isuzu.com","sopchok_bunyapraet@isuzu.com","rianpanya_faamnuypol@isuzu.com","tossaporn_tassanabanchong@isuzu.com","piyachat_meungjuntra@isuzu.com","chalitta-bunruang@linex.isuzu.co.th","chamaiporn-chokyosworawit@linex.isuzu.co.th","supawat-thongsing@linex.isuzu.co.th","banchob-thanadetcharit@linex.isuzu.co.th","watchara-phiewkhiaw@linex.isuzu.co.th","khamron-dehakun@linex.isuzu.co.th","duangkamon-wuthi@linex.isuzu.co.th","thanawan.methapruet.xibst@resonac.com","wattana-sittisijan@linex.isuzu.co.th","rungsee-phothong@tid.isuzu.co.th","nuttawut-wilaiphat@ilt.isuzu.co.th")
				        			// );
				        			//echo $value_chkuser['emp_id']."::";
									$where_if = 'lms_emp.emp_id="'.$value_chkuser['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
									$db->where($where_if);
									$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
									$fetch_userposi = $db->getOne('lms_emp');
									$canSend = 1;
									// if (isset($arrCosNotSendEmail[$value_courseexp['cos_id']])) {
									// 	if (in_array($fetch_userposi['email'], $arrCosNotSendEmail[$value_courseexp['cos_id']])) {
									// 		$canSend = 0;
									// 	}
									// }
				            		if(isset($fetch_userposi['email']) && !in_array($fetch_userposi['email'], $arr_email) && $canSend == 1) {

									$db->where('com_id="'.$fetch_userposi['com_id'].'"');
									$fetch_company = $db->getOne('lms_company');
									//print_r($fetch_userposi['email']);
				            			echo $fetch_userposi['email']."::233:::<br>";
		            						array_push($arr_email, $fetch_userposi['email']);
		            						if($fetch_userposi['emp_manage_a']!=""&&$fetch_userposi['emp_manage_a']!=$fetch_userposi['email']){
		            							if(isset($arr_emailmanager[$fetch_userposi['emp_manage_a']])){
		            								array_push($arr_emailmanager[$fetch_userposi['emp_manage_a']], $fetch_userposi['email']);
		            							}else{
		            								$arr_emailmanager[$fetch_userposi['emp_manage_a']] = array();
		            								array_push($arr_emailmanager[$fetch_userposi['emp_manage_a']], $fetch_userposi['email']);
		            							}
		            						}
		            						if($fetch_userposi['emp_manage_b']!=""&&$fetch_userposi['emp_manage_b']!=$fetch_userposi['email']){
		            							if(isset($arr_emailmanager[$fetch_userposi['emp_manage_b']])){
		            								array_push($arr_emailmanager[$fetch_userposi['emp_manage_b']], $fetch_userposi['email']);
		            							}else{
		            								$arr_emailmanager[$fetch_userposi['emp_manage_b']] = array();
		            								array_push($arr_emailmanager[$fetch_userposi['emp_manage_b']], $fetch_userposi['email']);
		            							}
		            						}
		            						//echo $value_userposi['email']."::";
									              	$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];
									              	//print_r($fetch_formatmail);
									                if($subject_th!=""){
									                  $subject_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$subject_th);
									                  $subject_th = str_replace("#username",$fetch_userposi['useri'],$subject_th);
									                  $subject_th = str_replace("#email",$fetch_userposi['email'],$subject_th);
									                  $subject_th = str_replace("#coursename",$cname,$subject_th);
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_th);
									                  $subject_th = str_replace("#date",$date,$subject_th);
									                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
									                  $subject_th = str_replace("#perioddate",$period,$subject_th);
									                  $subject_th = str_replace("#expiredate",$date_end,$subject_th);
                          								$subject_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$subject_th);
                          							  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
									                }
									                if($subject_en!=""){
									                  $subject_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$subject_en);
									                  $subject_en = str_replace("#username",$fetch_userposi['useri'],$subject_en);
									                  $subject_en = str_replace("#email",$fetch_userposi['email'],$subject_en);
									                  $subject_en = str_replace("#coursename",$cname,$subject_en);
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_en);
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
									                  $message_th = str_replace("#fullname",$fetch_userposi['fullname_th'],$message_th);
									                  $message_th = str_replace("#username",$fetch_userposi['useri'],$message_th);
									                  $message_th = str_replace("#email",$fetch_userposi['email'],$message_th);
									                  $message_th = str_replace("#coursename",$cname,$message_th);
									                  $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_th);
									                  $message_th = str_replace("#date",$date,$message_th);
									                  $message_th = str_replace("#time",date('H:i'),$message_th);
									                  $message_th = str_replace("#perioddate",$period,$message_th);
									                  $message_th = str_replace("#expiredate",$date_end,$message_th);
                          								$message_th = str_replace("#image",$img_val,$message_th);
                          								$message_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_th);
                          							  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  $message_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$message_en);
									                  $message_en = str_replace("#username",$fetch_userposi['useri'],$message_en);
									                  $message_en = str_replace("#email",$fetch_userposi['email'],$message_en);
									                  $message_en = str_replace("#coursename",$cname,$message_en);
									                  $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_en);
									                  $message_en = str_replace("#date",$date,$message_en);
									                  $message_en = str_replace("#time",date('H:i'),$message_en);
									                  $message_en = str_replace("#perioddate",$period,$message_en);
									                  $message_en = str_replace("#expiredate",$date_end,$message_en);
                          								$message_en = str_replace("#image",$img_val,$message_en);
                          								$message_en = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_en);
                          							  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
									                if($lang == "thai") {
									                sendEmail( $fetch_userposi['email'] , $message_th, $subject_th,$object_connect);
									                } else {
									               	sendEmail( $fetch_userposi['email'] , $message_en, $subject_en,$object_connect);
									                }
				            		}
				        		}
				        }

                	$where = 'cosde_id in (select lms_cos_detail.cosde_id from lms_cos_detail where cos_id = "'.$value_courseexp['cos_id'].'" and cosde_isDelete="0")';
					$db->where($where);
					$fetch_chk_position = $db->get('lms_cos_detail_ug');
					if(!empty($fetch_chk_position)){

						$where = 'smf_show="1" and smf_type="14"';
						$db->where($where);
						$fetch_formatmail = $db->getOne('lms_sendmail_form');
                		foreach ($fetch_chk_position as $key_chk_position => $value_chk_position) {
							if(isset($fetch_formatmail['smf_subject_th'])){
								$where = 'lms_usp.posi_id="'.$value_chk_position['posi_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "'.date('Y-m-d H:i').'" or lms_usp.inactivedate = "0000-00-00 00:00:00")';
								$db->where($where);
								$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
								$fetch_userposi = $db->get('lms_emp');
								if(!empty($fetch_userposi)){
			            			foreach ($fetch_userposi as $key_userposi => $value_userposi) {
			            					$varsend = 0;
											$where = 'cos_id="'.$value_courseexp['cos_id'].'" and emp_id="'.$value_userposi['emp_id'].'" and cosen_status="1" and cosen_isDelete="0"';
											$db->where($where);
											$db->orderBy('cosen_id');
											$fetch_chkuser = $db->getOne('lms_cos_enroll');
											if(isset($fetch_chkuser['cosen_status_sub'])){
												$db->where('com_id="'.$value_userposi['com_id'].'"');
												$fetch_company = $db->getOne('lms_company');
			            						if($fetch_chkuser['cosen_status_sub']!="1"){
			            							$varsend = 1;
			            						}
				            					if($varsend==1&&!in_array($value_userposi['email'], $arr_email)){
				            						echo $value_userposi['email']."::126::<br>";
				            						array_push($arr_email, $value_userposi['email']);
				            						if($value_userposi['emp_manage_a']!=""&&$value_userposi['emp_manage_a']!=$value_userposi['email']){
				            							if(isset($arr_emailmanager[$value_userposi['emp_manage_a']])){
				            								array_push($arr_emailmanager[$value_userposi['emp_manage_a']], $value_userposi['email']);
				            							}else{
				            								$arr_emailmanager[$value_userposi['emp_manage_a']] = array();
				            								array_push($arr_emailmanager[$value_userposi['emp_manage_a']], $value_userposi['email']);
				            							}
				            						}
				            						if($value_userposi['emp_manage_b']!=""&&$value_userposi['emp_manage_b']!=$value_userposi['email']){
				            							if(isset($arr_emailmanager[$value_userposi['emp_manage_b']])){
				            								array_push($arr_emailmanager[$value_userposi['emp_manage_b']], $value_userposi['email']);
				            							}else{
				            								$arr_emailmanager[$value_userposi['emp_manage_b']] = array();
				            								array_push($arr_emailmanager[$value_userposi['emp_manage_b']], $value_userposi['email']);
				            							}
				            						}
									              	$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];
									                if($subject_th!=""){
									                  $subject_th = str_replace("#fullname",$value_userposi['fullname_th'],$subject_th);
									                  $subject_th = str_replace("#username",$value_userposi['useri'],$subject_th);
									                  $subject_th = str_replace("#email",$value_userposi['email'],$subject_th);
									                  $subject_th = str_replace("#coursename",$cname,$subject_th);
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_th);
									                  $subject_th = str_replace("#date",$date,$subject_th);
									                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
									                  $subject_th = str_replace("#perioddate",$period,$subject_th);
									                  $subject_th = str_replace("#expiredate",$date_end,$subject_th);
									                  $subject_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$subject_th);
                          							  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
									                }
									                if($subject_en!=""){
									                  $subject_en = str_replace("#fullname",$value_userposi['fullname_en'],$subject_en);
									                  $subject_en = str_replace("#username",$value_userposi['useri'],$subject_en);
									                  $subject_en = str_replace("#email",$value_userposi['email'],$subject_en);
									                  $subject_en = str_replace("#coursename",$cname,$subject_en);
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_en);
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
									                  	$message_th = str_replace("#fullname",$value_userposi['fullname_th'],$message_th);
									                  	$message_th = str_replace("#username",$value_userposi['useri'],$message_th);
									                  	$message_th = str_replace("#email",$value_userposi['email'],$message_th);
									                  	$message_th = str_replace("#coursename",$cname,$message_th);
									                  	$message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_th);
									                  	$message_th = str_replace("#date",$date,$message_th);
									                  	$message_th = str_replace("#time",date('H:i'),$message_th);
									                  	$message_th = str_replace("#perioddate",$period,$message_th);
									                  	$message_th = str_replace("#expiredate",$date_end,$message_th);
                          								$message_th = str_replace("#image",$img_val,$message_th);
                          								$message_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_th);
                          							  	$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  	$message_en = str_replace("#fullname",$value_userposi['fullname_en'],$message_en);
									                  	$message_en = str_replace("#username",$value_userposi['useri'],$message_en);
									                  	$message_en = str_replace("#email",$value_userposi['email'],$message_en);
									                  	$message_en = str_replace("#coursename",$cname,$message_en);
									                  	$message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_en);
									                  	$message_en = str_replace("#date",$date,$message_en);
									                  	$message_en = str_replace("#time",date('H:i'),$message_en);
									                  	$message_en = str_replace("#perioddate",$period,$message_en);
									                  	$message_en = str_replace("#expiredate",$date_end,$message_en);
                          								$message_en = str_replace("#image",$img_val,$message_en);
                          								$message_en = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_en);
                          							  	$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
									                if($lang == "thai") {
									                sendEmail( $value_userposi['email'] , $message_th, $subject_th,$object_connect);
									                } else {
									                sendEmail( $value_userposi['email'] , $message_en, $subject_en,$object_connect);
									                }
				            					}
			            					}
			            			}
			            		}
			            	}
		            	}
		            }

				       /* if(count($arr_emailmanager)>0){

							$where = 'smf_show="1" and smf_type="18"';
							$db->where($where);
							$fetch_formatmail = $db->getOne('lms_sendmail_form');
							if(count($fetch_formatmail)>0){
								$arr_emplist = array();
		                		foreach ($arr_emailmanager as $key_manager => $value_manager) {
					        		$list_emp = "";
		                			if(count($value_manager)>0){
					        		$list_emp = "<ol>";
		                				for ($i=0; $i < count($value_manager); $i++) { 
											$where_if = 'lms_emp.emp_c="'.$value_manager[$i].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"';
											$db->where($where_if);
											$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
											$fetch_emp = $db->getOne('lms_emp');
											if(count($fetch_emp)>0){
												$db->where('com_id="'.$fetch_emp['com_id'].'"');
												$fetch_company = $db->getOne('lms_company');
												if(!in_array($value_manager[$i], $arr_emplist)){
													if($lang=="thai"){
														$list_emp .= "<li>คุณ ".$fetch_emp['fname_th']." ".$fetch_emp['lname_th']." /".$fetch_company['com_code']."</li>";
													}else{
														$list_emp .= "<li>Mr./Ms. ".$fetch_emp['fname_en']." ".$fetch_emp['lname_en']." /".$fetch_company['com_code']."</li>";
													}
												}
												array_push($arr_emplist, $value_manager[$i]);
											}
		                				}
			                		$list_emp .= "</ol>";
		                			}
									$where_if = 'lms_emp.emp_c="'.$key_manager.'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"';
									$db->where($where_if);
									$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
									$fetch_manager = $db->getOne('lms_emp');
									$db->where('com_id="'.$fetch_manager['com_id'].'"');
									$fetch_company = $db->getOne('lms_company');
									if(count($fetch_manager)>0&&$list_emp!=""){
										$value_message = "";
										$subject_th = $fetch_formatmail['smf_subject_th'];
									              	$subject_en = $fetch_formatmail['smf_subject_en'];
									              	$message_th = $fetch_formatmail['smf_message_th'];
									              	$message_en = $fetch_formatmail['smf_message_en'];
									                if($subject_th!=""){
									                  $subject_th = str_replace("#fullname",$fetch_manager['fullname_th'],$subject_th);
									                  $subject_th = str_replace("#username",$fetch_manager['useri'],$subject_th);
									                  $subject_th = str_replace("#email",$fetch_manager['email'],$subject_th);
									                  $subject_th = str_replace("#coursename",$cname,$subject_th);
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_th);
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
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$subject_en);
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
									                  $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_th);
									                  $message_th = str_replace("#date",$date,$message_th);
									                  $message_th = str_replace("#time",date('H:i'),$message_th);
									                  $message_th = str_replace("#perioddate",$period,$message_th);
									                  $message_th = str_replace("#expiredate",$date_end,$message_th);
                          							  $message_th = str_replace("#image",$img_val,$message_th);
                          							  $message_th = str_replace("#stafflist",$list_emp,$message_th);
                          								$message_th = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_th);
                          							  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  $message_en = str_replace("#fullname",$fetch_manager['fullname_en'],$message_en);
									                  $message_en = str_replace("#username",$fetch_manager['useri'],$message_en);
									                  $message_en = str_replace("#email",$fetch_manager['email'],$message_en);
									                  $message_en = str_replace("#coursename",$cname,$message_en);
									                  $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id'],$message_en);
									                  $message_en = str_replace("#date",$date,$message_en);
									                  $message_en = str_replace("#time",date('H:i'),$message_en);
									                  $message_en = str_replace("#perioddate",$period,$message_en);
									                  $message_en = str_replace("#expiredate",$date_end,$message_en);
                          							  $message_en = str_replace("#image",$img_val,$message_en);
                          							  $message_en = str_replace("#stafflist",$list_emp,$message_en);
                          								$message_en = str_replace("#durationofstudy",$value_courseexp['cos_hour'],$message_en);
                          							  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
									                if($lang == "thai") {
									                sendEmail( $fetch_manager['email'] , $message_th, $subject_th,$object_connect);
									                } else {
									                sendEmail( $fetch_manager['email'] , $message_en, $subject_en,$object_connect);
									                }

									}
		                		}
		                	}
				        }*/
			}
		}
				                		/*if($lang=="thai"){
				                			$value_message .= "เรียน คุณ ".$fetch_manager['fname_th']." ".$fetch_manager['lname_th']." /".$fetch_company['com_code']." <br><br>";
				                			$value_message .= "หลักสูตร ".$cname." กำลังจะหมดอายุ<br>";
				                			$value_message .= "ระยะเวลาของหลักสูตร: ".$period."<br>";
				                			$value_message .= "<a href='https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id']."'>https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id']."</a><br><br>";
				                			$value_message .= "รายชื่อพนักงานภายใต้การดูแลของท่านที่ยังเรียนไม่จบ มีดังนี้<br><br>";
				                			$value_message .= $list_emp;

										    sendEmail( $fetch_manager['email'] , $value_message, 'Sample E-Mail Subject',$object_connect);
				                		}else{
				                			$value_message .= "Dear Mr./Ms. ".$fetch_manager['fname_en']." ".$fetch_manager['lname_en']." /".$fetch_company['com_code']." <br><br>";
				                			$value_message .= "Course Name : ".$cname."is about to close.<br>";
				                			$value_message .= "Course Period: ".$period."<br>";
				                			$value_message .= "<a href='https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id']."'>https://elearning.isuzu.co.th/coursemain/detail/".$value_courseexp['cos_id']."</a><br><br>";
				                			$value_message .= "Followings are the list of employees under your supervision who haven’t completed the course:<br><br>";
				                			$value_message .= $list_emp;
										    sendEmail( $fetch_manager['email'] , $value_message, 'Sample E-Mail Subject',$object_connect);
				                		}*/
/*$sql_courseexp = 'select * from lms_cos inner join lms_cos_detail on lms_cos_detail.cos_id = lms_cos.cos_id where cos_approve="1" and cos_public="1" and cos_expire_noti!="" and cos_status="1" and cos_isDelete="0" and lms_cos_detail.date_end!="0000-00-00 00:00:00"';
$query_courseexp = mysqli_query($conndb,$sql_courseexp);

while ($value_courseexp = mysqli_fetch_array($query_courseexp)) {
	# code...
}
		$fetch_courseexp = $this->func_query->query_result('lms_cos','lms_cos_detail','lms_cos_detail.cos_id = lms_cos.cos_id','','cos_approve="1" and cos_public="1" and cos_expire_noti!="" and cos_status="1" and cos_isDelete="0" and lms_cos_detail.date_end!="0000-00-00 00:00:00"');

		if(count($fetch_courseexp)>0){
			foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
				if($value_courseexp['cos_expire_noti']!=""){
					$cos_expire_noti = explode(",",$value_courseexp['cos_expire_noti']);
					$numrechk = 0;
					$numtotal = 0;
					for ($i=0; $i < count($cos_expire_noti); $i++) { 
						$numtotal++;
						if(isset($cos_expire_noti[$i])&&$cos_expire_noti[$i]!=""&&$cos_expire_noti[$i]!="0"){
							$numrechk++;
							if(date('Y-m-d')<=date('Y-m-d',strtotime($value_courseexp['date_end']))){
								if($cos_expire_noti[$i]=="0"){
									$date_selectend = date('Y-m-d');
								}else{
									$date_selectend = date('Y-m-d',strtotime($value_courseexp['date_end'].' -'.$cos_expire_noti[$i].'day'));
								}
								$date_now = date('Y-m-d');
								if($date_now!=$date_selectend){
									unset($fetch_courseexp[$key_courseexp]);
								}
							}else{
								unset($fetch_courseexp[$key_courseexp]);
							}
						}
					}
					if($numrechk==0){
						unset($fetch_courseexp[$key_courseexp]);
					}
				}else{
					unset($fetch_courseexp[$key_courseexp]);
				}
			}
		}*/

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
	    //$mail->addBcc("yupontee.k@verztec.com");
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
/*function sendEmail($email, $message, $subject, $object_connect) {
		$sub = "ข้อความจากเว็บไซต์";

		$mail = new SimpleMail();

	    $sender = $object_connect['sm_sender'];//"THAIHEALTH LMS"; // ชื่อผู้ส่ง
	    $email_sender = $object_connect['sm_emailsender'];//"pandora@verztec.com"; // เมล์ผู้ส่ง 
	    $email_receiver = $email; // เมล์ผู้รับ ***
    if(!empty($email)) {
	    $mail->setFrom($email_sender, $sender);
  		$mail->setTo($email,'')
  			->setSubject($subject)
  			->addGenericHeader('MIME-Version', '1.0')
  			->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
  			->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
  			->setMessage($message);
  		$mail->send();
      $GLOBALS['z'][] = $email;
    }
}*/

?>