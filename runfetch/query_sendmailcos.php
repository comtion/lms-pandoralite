<?php 
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

mysqli_query($conndb,"SET NAMES 'utf8'");
$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$object_connect = mysqli_fetch_array($query_obj);
$lang = "english";
$cos_sql = "select * from lms_cos inner join lms_cos_detail on lms_cos.cos_id = lms_cos_detail.cos_id where lms_cos.cos_id='32'";
$query_cos = mysqli_query($conndb,$cos_sql);
$fetch_maincos = mysqli_fetch_array($query_cos);
									$cos_lang = explode(',', $fetch_maincos['cos_lang']);
                                  $fetch_maincos['isTH'] = in_array('th',$cos_lang)?"1":"0";
                                  $fetch_maincos['isENG'] = in_array('eng',$cos_lang)?"1":"0";
                                  $fetch_maincos['isJP'] = in_array('jp',$cos_lang)?"1":"0";
                                  $cname = "";
                                  if($lang=="thai"){
                                      if($fetch_maincos['isTH']=="1"){
                                        $cname = $fetch_maincos['cname_th'];
                                      }else{
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_eng'];
                                        }
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_jp'];
                                        }
                                      }
                                  }else if($lang=="english"){
                                      if($fetch_maincos['isENG']=="1"){
                                        $cname = $fetch_maincos['cname_eng'];
                                      }else{
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_th'];
                                        }
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_jp'];
                                        }
                                      }
                                  }else{
                                      if($fetch_maincos['isJP']=="1"){
                                        $cname = $fetch_maincos['cname_jp'];
                                      }else{
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_eng'];
                                        }
                                        if($cname==""){
                                          $cname = $fetch_maincos['cname_th'];
                                        }
                                      }
                                  }

				                echo $cname."<br>";
				                $date_end = "";
				                $period = "Unlimited time";
				                	if($fetch_maincos['date_start']!="0000-00-00 00:00:00"&&$fetch_maincos['date_end']!="0000-00-00 00:00:00"){
							            if($lang=="thai"){
							            $periodstart = $fetch_maincos['date_start']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_maincos['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_maincos['date_start'])))]." ".(date('Y',strtotime($fetch_maincos['date_start']))+543)." ".date('H:i',strtotime($fetch_maincos['date_start'])):"";
							            $periodend = $fetch_maincos['date_end']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_maincos['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_maincos['date_end'])))]." ".(date('Y',strtotime($fetch_maincos['date_end']))+543)." ".date('H:i',strtotime($fetch_maincos['date_end'])):"";
				                		$date_end = $periodend;
							            }else{
							            $periodstart = $fetch_maincos['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_maincos['date_start'])):"";
							            $periodend = $fetch_maincos['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_maincos['date_end'])):"";
				                		$date_end = $periodend;
							            }
							            $periodstart = $fetch_maincos['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_maincos['date_start'])):"";
							            $periodend = $fetch_maincos['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_maincos['date_end'])):"";
				                		$date_end = $periodend;
							            
							            if($periodstart!=""&&$periodend!=""){
							              	$period = $periodstart." - ".$periodend;
							            }
						              	$date = date('d ',strtotime($fetch_maincos['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_maincos['date_end'])))]." ".(date('Y',strtotime($fetch_maincos['date_end']))+543);
						              	//if($lang!="thai"){
						                 	$date = date('d F Y',strtotime($fetch_maincos['date_end']));
						              	//}
				                	}
$arr_emailsentdone = array('varanyu-nhurod@ilat.isuzu.co.th','p_kunlapapruk@imit.co.th','p_chotika@imit.co.th','k_kantapat@imit.co.th','teerapong_injan@ita.isuzu.co.jp','nattapong_adiraketanasup@ita.isuzu.co.jp','pakorn_boonpapeanlert@ita.isuzu.co.jp','muthita@itf.co.th','pissanu@itf.co.th','supaporn-kittitormmo@kdi.co.th','adisa-chindakhan@ilat.isuzu.co.th','setthawut_d@icl-t.co.th','sureeporn.p@ibct.co.th','songklod-pongsakonpruttikul@iemt.isuzu.co.th','witaya-choo-im@iemt.isuzu.co.th','suthisak_tantisrisuk@igce.isuzu.co.th','wajakorn_chutichaiwatana@igce.isuzu.co.th','paradon_vongvanitkangwan@igce.isuzu.co.th','pherawat_aekbuncha@igce.isuzu.co.th','apisara_wisutthaporn@igce.isuzu.co.th','kaizen_ry@ijtt-th.com','suchanuch_s@ijtt-th.com','thidarat_t@ijtt-th.com','p_noppadon@imit.co.th','m_juthaporn@imit.co.th','j_chayanee@imit.co.th','suchart_prypiroonrojn@ita.isuzu.co.jp','worapan_pongnapang@ita.isuzu.co.jp','yutthapong_singkaew@ita.isuzu.co.jp','wissawa_veing-in@ita.isuzu.co.jp','duangsuda@itf.co.th','uranchai@itf.co.th','kannika_r@icl-t.co.th','pornlert-amornwithayawet@iemt.isuzu.co.th','panida-junlavate@iemt.isuzu.co.th','phongsathon_intarit@igce.isuzu.co.th','nattachai_silanothai@igce.isuzu.co.th','boossarakham_fongsawat@igce.isuzu.co.th','adichai_vijan@igce.isuzu.co.th','suphinya_s@ijtt-th.com','pansa_p@ijtt-th.com','siranee_h@ijtt-th.com','komgritch-rangpetch@ilat.isuzu.co.th','p_orravee@imit.co.th','s_palika@imit.co.th','k_pacharida@imit.co.th','pongsak_meepin@ita.isuzu.co.jp','seksan_wannakul@ita.isuzu.co.jp','natchanaiyawat_nasri@ita.isuzu.co.jp','chavalit_chaokasemsoonthon@ita.isuzu.co.jp','kongeak@itf.co.th','anuwat-kaewkaisorn@kdi.co.th','panu_c@icl-t.co.th','kittichai.t@ibct.co.th','nirund_t@icl-t.co.th','sangduen-asiravat@iemt.isuzu.co.th','decha-sutthiprapa@iemt.isuzu.co.th','surat_issarapirak@igce.isuzu.co.th','jiratchaya_duangsawang@igce.isuzu.co.th','amornvit_thaiyingsombat@igce.isuzu.co.th','thanyaphon_nantawan@igce.isuzu.co.th','jitlada_a@ijtt-th.com','chaiyapoom_p@ijtt-th.com','patchaploy_m@ijtt-th.com','sittisak-watee@ilat.isuzu.co.th','c_nuttakarn@imit.co.th','s_nakarin@imit.co.th','k_jitphumisak@imit.co.th','aod_siri@ita.isuzu.co.jp','nitithep_nimsawad@ita.isuzu.co.jp','piyawat_poolkao@ita.isuzu.co.jp','netipong@itf.co.th','pitiporn@itf.co.th','pornchai-tantiapirom@kdi.co.th','rattana_p@icl-t.co.th','natcha-koma@ibct.co.th','songsak-pinitapakorn@iemt.isuzu.co.th','weerawat-suphaluklawan@iemt.isuzu.co.th','suwaree_hiranpongsatorn@igce.isuzu.co.th','wutiporn_kammalakul@igce.isuzu.co.th','kannika_boonthamdee@igce.isuzu.co.th','phuwanat_naksiri@igce.isuzu.co.th','kannika_jaiseti@igce.isuzu.co.th','natkanet_k@ijtt-th.com','prachai_k@ijtt-th.com','supansa_c@ijtt-th.com','n_buppachat@imit.co.th','v_dhanawoot@imit.co.th','i_kantida@imit.co.th','tarawit_pongsiripreeda@ita.isuzu.co.jp','nuttawat_suwanrusk@ita.isuzu.co.jp','withaya_marom@ita.isuzu.co.jp','puttipong_wongpetchai@ita.isuzu.co.jp','dutsadee@itf.co.th','usa.l@itf.co.th','chanwit- sanping@kdi.co.th','suthat_d@icl-t.co.th','niphon_n@ibct.co.th','piphitchai-suphithakwong@iemt.isuzu.co.th','patchara-tongsalee@iemt.isuzu.co.th','banlang_kosoom@igce.isuzu.co.th','atchima_kanchanapan@igce.isuzu.co.th','kiatisak_charoenpongsaphan@igce.isuzu.co.th','kritchwit_bunnag@igce.isuzu.co.th','supaporn_c@ijtt-th.com','sommai_b@ijtt-th.com','sirilak_s@ijtt-th.com','thirananchai-tepin@ilat.isuzu.co.th','t_nareerat@imit.co.th','s_peeraya@imit.co.th','b_saithan@imit.co.th','tossaporn_tassanabanchong@ita.isuzu.co.jp','thanakorn_sabaitae@ita.isuzu.co.jp','chalacom_wisatsing@ita.isuzu.co.jp','krittaya_sinsomboonchai@ita.isuzu.co.jp','nipat@itf.co.th','nakorn-punyasujjathum@kdi.co.th','jeerawat_k@icl-t.co.th','kritchanut_k@icl-t.co.th','jintana-sirilertsombat@iemt.isuzu.co.th','teeturch-jitrisarn@iemt.isuzu.co.th','pachara_pinyowattayakorn@igce.isuzu.co.th','janyaporn_kulprakan@igce.isuzu.co.th','praphat_tewutthatanont@igce.isuzu.co.th','chutipong_saenkaruna@igce.isuzu.co.th','chatpong_l@ijtt-th.com','dechatron_c@ijtt-th.com','juthamas_r@ijtt-th.com','shaichon-samuthlom@ilat.isuzu.co.th','j_prueksa@imit.co.th','n_suttapa@imit.co.th','s_apichaya@imit.co.th','kittipong_likhitmaskul@ita.isuzu.co.jp','nuttapol_yiemkhantithavon@ita.isuzu.co.jp','aekkaluck_yodying@ita.isuzu.co.jp','paitool.r@itf.co.th','porntip@itf.co.th','arpakorn-heeminkul@kdi.co.th','wichian_a@icl-t.co.th','gateway-plant@ibct.co.th','suwannee-yindeechan@iemt.isuzu.co.th','somkhane-wanyuan@iemt.isuzu.co.th','kirawat_wongchampa@igce.isuzu.co.th','arpa_palanuchsuk@igce.isuzu.co.th','napatsawan_khumjan@igce.isuzu.co.th','sasikarn_jiviriyawat@igce.isuzu.co.th','sirinit_thangthumachit@igce.isuzu.co.th','nirut_j@ijtt-th.com','teangsak_r@ijtt-th.com','anaknong_p@ijtt-th.com','m_torranon@imit.co.th','s_pitikorn@imit.co.th','p_natcharee@imit.co.th','chairote_boonfahprathan@ita.isuzu.co.jp','sivanun_oangarj@ita.isuzu.co.jp','titiphun_pasom@ita.isuzu.co.jp','chawalit_vithayasintana@ita.isuzu.co.jp','ekaphol@itf.co.th','wantanee@itf.co.th','vekeephat_m@icl-t.co.th','sakon-ouangsue@ibct.co.th','yongyuth-porjaroen@iemt.isuzu.co.th','pattarika-ratanasonti@iemt.isuzu.co.th','cheewan_vijidwong@igce.isuzu.co.th','natthanun_songthai@igce.isuzu.co.th','samareeya_meelapudomchai@igce.isuzu.co.th','napasihn_vongpiyasatit@igce.isuzu.co.th','chanakarn_s@ijtt-th.com','phatthanasan_r@ijtt-th.com','praisri_a@ijtt-th.com','ukkarachai-kansane@ilat.isuzu.co.th','w_sasikarn@imit.co.th','r_angkoon@imit.co.th','r_visith@imit.co.th','piyanuch_rongtong@ita.isuzu.co.jp','verachard_khurchotikul@ita.isuzu.co.jp','tanate_treeratsakulchai@ita.isuzu.co.jp','budsarakum_inchand@ita.isuzu.co.jp','nirun@itf.co.th','somnuek-suwanlert@kdi.co.th','preeyanut_p@icl-t.co.th','natcharee_h@icl-t.co.th','sudtee-promwong@iemt.isuzu.co.th','teerayut-sutthisuwan@iemt.isuzu.co.th','mananya_ritmun@igce.isuzu.co.th','kullada_inchum@igce.isuzu.co.th','setthawut_osothsinlp@igce.isuzu.co.th','thanissawan_himma@igce.isuzu.co.th','pitchayapa_i@ijtt-th.com','chatchawin_k@ijtt-th.com','prinphan_m@ijtt-th.com','s_snehnart@imit.co.th','p_sriraovapak@imit.co.th','p_supathat@imit.co.th','c_chuttimun@imit.co.th','thapakorn_soomkuan@ita.isuzu.co.jp','paripong_taychupakorn@ita.isuzu.co.jp','siwaporn_waiyawong@ita.isuzu.co.jp','ponlawat@itf.co.th','prangtip.w@itf.co.th','kittikon-kongjanda@kdi.co.th','worathat_t@icl-t.co.th','worayut.a@ibct.co.th','rathachad-jintanunkul@iemt.isuzu.co.th','kankanid-chinnasongkram@iemt.isuzu.co.th','supaporn_prasitthitham@igce.isuzu.co.th','phanphon_kulchan@igce.isuzu.co.th','sorn_tayakkanonta@igce.isuzu.co.th','jiranuwat_suphantamat@igce.isuzu.co.th','punsaa_lamubol@igce.isuzu.co.th','sontaya_c@ijtt-th.com','anchalee_t@ijtt-th.com','chunkamol_t@ijtt-th.com','p_petcharat@imit.co.th','p_kritika@imit.co.th','s_jutarat@imit.co.th','suebsin_wangnoi@ita.isuzu.co.jp','apiwat_nasaarn@ita.isuzu.co.jp','pasu_taveeboon@ita.isuzu.co.jp','jiranupong_sivaraks@ita.isuzu.co.jp','harnnarong@itf.co.th','watin@itf.co.th','sawitree_k@icl-t.co.th','natee_s@ibct.co.th','yuthana-teskratuk@iemt.isuzu.co.th','wanida-upamai@iemt.isuzu.co.th','kankanit_tuwicharanon@igce.isuzu.co.th','peeradej_rojanapasakorn@igce.isuzu.co.th','tanat_tunanunkul@igce.isuzu.co.th','nudee_charoonpasurakul@igce.isuzu.co.th','yuphawan_c@ijtt-th.com','anongnath_n@ijtt-th.com','patcharadit_nuwongsri@ijtt-th.com','julawan-chanphitak@ilat.isuzu.co.th','s_surasit@imit.co.th','s_hathaisiri@imit.co.th','m_patcharida@imit.co.th','somchai_boonma@ita.isuzu.co.jp','yuttachai_kittiwara@ita.isuzu.co.jp','arwut_choewchan@ita.isuzu.co.jp','saringkan_borisutprasit@ita.isuzu.co.jp','niyom@itf.co.th','teerasak-lamkaothong@kdi.co.th','santi_t@icl-t.co.th','aornuma_r@icl-t.co.th','kingbua-chuenwut@iemt.isuzu.co.th','pana-pombut@iemt.isuzu.co.th','monpriya_suebsaeng@igce.isuzu.co.th','nitchakan_sason@igce.isuzu.co.th','somrutai_hannaruchai@igce.isuzu.co.th','sirisin_tansakul@igce.isuzu.co.th','worawut_r@ijtt-th.com','sanya_s@ijtt-th.com','noppol_s@ijtt-th.com','p_pantip@imit.co.th','s_krongkan@imit.co.th','t_yanwarute@imit.co.th','s_nuttawat@imit.co.th','korrarit_ngamarom@ita.isuzu.co.jp','peerawat_sumrejphol@ita.isuzu.co.jp','sarin_nitiraksa@ita.isuzu.co.jp','prakit@itf.co.th','prasit@itf.co.th','arunee-meewandee@kdi.co.th','waraporn_s@icl-t.co.th','siwa-jampatasi@ibct.co.th','ausanee-plaibangmod@iemt.isuzu.co.th','chompu-untasri@iemt.isuzu.co.th','warut_nasong@igce.isuzu.co.th','natthawut_vimorkcharoensuk@igce.isuzu.co.th','phanpika_kawsiri@igce.isuzu.co.th','chaiwat_seubniem@igce.isuzu.co.th','janwimon_phanphai@igce.isuzu.co.th','phatsakorn_g@ijtt-th.com','linda_y@ijtt-th.com','korrapop-wasunit@ilat.isuzu.co.th','p_danupol@imit.co.th','n_panalee@imit.co.th','k_pornpailin@imit.co.th','aphirut_chalermchai@ita.isuzu.co.jp','arttasit_theplerdboon@ita.isuzu.co.jp','puwanat_wiboonnatakul@ita.isuzu.co.jp','weeranuch_kanjanapinyoying@ita.isuzu.co.jp','jaidow@itf.co.th','supatta@itf.co.th','kanapatt_l@icl-t.co.th');
$lang = "english";
$sql_rechk = "SELECT DISTINCT lms_emp.emp_id,lms_emp.emp_c,lms_emp.email,lms_emp.fullname_th,lms_emp.fullname_en,lms_usp.useri FROM `lms_cos_enroll` INNER JOIN lms_emp on lms_cos_enroll.emp_id = lms_emp.emp_id INNER JOIN lms_usp on lms_usp.emp_id = lms_cos_enroll.emp_id where cos_id = '32' and cosen_isDelete = 0 and cosen_status = 1 and cosen_status_sub != 1 and cosen_firsttime = '0000-00-00 00:00:00' and lms_emp.emp_isDelete = 0 ORDER BY `lms_cos_enroll`.`cosen_status_sub` ASC";
$query_rechk = mysqli_query($conndb,$sql_rechk);
$num_rechk = mysqli_num_rows($query_rechk);
$numtotal = 0;
$numunarray = 0;
if($num_rechk>0){
	$where = 'smf_show="1" and smf_type="10"';
	$db->where($where);
	$fetch_formatmail = $db->getOne('lms_sendmail_form');
	while($fetch_rechk = mysqli_fetch_array($query_rechk)){
		if(in_array($fetch_rechk['email'], $arr_emailsentdone)){
			$numtotal++;
				        			//echo $numtotal."::";
									$where_if = 'lms_emp.emp_id="'.$fetch_rechk['emp_id'].'"';
									$db->where($where_if);
									$db->join('lms_usp','lms_usp.emp_id = lms_emp.emp_id');
									$fetch_userposi = $db->getOne('lms_emp');
				            		if(count($fetch_userposi)>0&&!in_array($fetch_userposi['email'], $arr_email)){

									$db->where('com_id="'.$fetch_userposi['com_id'].'"');
									$fetch_company = $db->getOne('lms_company');
									//print_r($fetch_userposi['email']);
				            			//echo $fetch_userposi['email']."::233:::<br>";
		            						array_push($arr_email, $fetch_userposi['email']);
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
									                  $subject_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$fetch_maincos['cos_id'],$subject_th);
									                  $subject_th = str_replace("#date",$date,$subject_th);
									                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
									                  $subject_th = str_replace("#perioddate",$period,$subject_th);
									                  $subject_th = str_replace("#expiredate",$date_end,$subject_th);
                          								$subject_th = str_replace("#durationofstudy",$fetch_maincos['cos_hour'],$subject_th);
                          							  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
									                }
									                if($subject_en!=""){
									                  $subject_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$subject_en);
									                  $subject_en = str_replace("#username",$fetch_userposi['useri'],$subject_en);
									                  $subject_en = str_replace("#email",$fetch_userposi['email'],$subject_en);
									                  $subject_en = str_replace("#coursename",$cname,$subject_en);
									                  $subject_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$fetch_maincos['cos_id'],$subject_en);
									                  $subject_en = str_replace("#date",$date,$subject_en);
									                  $subject_en = str_replace("#time",date('H:i'),$subject_en);
									                  $subject_en = str_replace("#perioddate",$period,$subject_en);
									                  $subject_en = str_replace("#expiredate",$date_end,$subject_en);
                          								$subject_en = str_replace("#durationofstudy",$fetch_maincos['cos_hour'],$subject_en);
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
									                  $message_th = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$fetch_maincos['cos_id'],$message_th);
									                  $message_th = str_replace("#date",$date,$message_th);
									                  $message_th = str_replace("#time",date('H:i'),$message_th);
									                  $message_th = str_replace("#perioddate",$period,$message_th);
									                  $message_th = str_replace("#expiredate",$date_end,$message_th);
                          								$message_th = str_replace("#image",$img_val,$message_th);
                          								$message_th = str_replace("#durationofstudy",$fetch_maincos['cos_hour'],$message_th);
                          							  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
									                }
									                if($message_en!=""){
									                  $message_en = str_replace("#fullname",$fetch_userposi['fullname_en'],$message_en);
									                  $message_en = str_replace("#username",$fetch_userposi['useri'],$message_en);
									                  $message_en = str_replace("#email",$fetch_userposi['email'],$message_en);
									                  $message_en = str_replace("#coursename",$cname,$message_en);
									                  $message_en = str_replace("#link_frontend","https://elearning.isuzu.co.th/coursemain/detail/".$fetch_maincos['cos_id'],$message_en);
									                  $message_en = str_replace("#date",$date,$message_en);
									                  $message_en = str_replace("#time",date('H:i'),$message_en);
									                  $message_en = str_replace("#perioddate",$period,$message_en);
									                  $message_en = str_replace("#expiredate",$date_end,$message_en);
                          								$message_en = str_replace("#image",$img_val,$message_en);
                          								$message_en = str_replace("#durationofstudy",$fetch_maincos['cos_hour'],$message_en);
                          							  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
									                }
									                if($lang == "thai") {
									                //sendEmail( $fetch_userposi['email'] , $message_th, $subject_th,$object_connect);
									                } else {
									                	echo $fetch_userposi['email']."<br>";
									               	sendEmail( $fetch_userposi['email'] , $message_en, $subject_en,$object_connect);
									                }
				            		}
				        		
		}else{
			$numunarray++;
		}
	}
	echo "จำนวนคน:".$numtotal."<br>จำนวนคนที่ไม่อยู่ในที่ส่งไปแล้ว".$numunarray;
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
      //$mail->addBcc("yupontee.k@verztec.com");
      //$mail->addBcc("jetsada.d@verztec.com");
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


