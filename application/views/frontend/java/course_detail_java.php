<script type="text/javascript">
	$('.slimtest1').perfectScrollbar();
	<?php
$raw_course_main = isset($course_main) && is_array($course_main) ? $course_main : array();
$has_course_main = countArray($raw_course_main) > 0;
$course_main = array_merge(
	array(
		'cos_id' => '',
		'cos_pic' => '',
		'cname' => '',
		'com_name' => '',
		'cdetail' => '',
		'txt_period_course' => '',
		'isENG' => '0',
		'isTH' => '0',
		'isJP' => '0',
		'select_lang' => '',
		'isCondition' => '0',
		'msgCondition' => '',
	),
	$raw_course_main
);
$cos_id = isset($cos_id) ? $cos_id : $course_main['cos_id'];
$lang_select = isset($lang_select) ? $lang_select : '';

	if ($lang_select == "") {
		$lang_select = $lang;
	}
	if ($lang_select == "thai") {
		$createBy = "สร้างโดย";
		$lesson_file = "เอกสารประกอบการเรียน";
		$survey_txt = "แบบสำรวจ";
		$pointtxt = "คะแนน";
		$finalpointtxt = "คะแนน";
		$resultcos = "ผล";
		$lessontxt = "บทเรียน";
		$preNo = "ข้อที่";
		$summarytxt = "คำอธิบาย";
		$close = "ปิด";
		$yes_btn = "ใช่";
		$no_btn = "ไม่";
		$m_previous = "กลับ";
		$saveR = "บันทึก";
		$m_next = "ถัดไป";
		$preSend = "ส่งคำตอบ";
		$full_screentxt = "ขยายเต็มจอ";
		$qiz_starttxt = "เริ่มทำแบบทดสอบ";
		$download_file = "ดาวน์โหลดไฟล์";
		$m_ok = "ตกลง";
		$go_to_course = "เข้าสู่หลักสูตร";
		$Les_video = "วิดีโอประกอบการสอน";
		$hinttxt = "คำใบ้";
		$statustxt = "สถานะ";
		$sent_survey = "ส่งแบบสำรวจ";
		$periodtxt = "ระยะเวลาที่เปิดหลักสูตร";
		$Chooselangtxt = "กรุณาเลือกภาษา";
		$thailandtxt = "ภาษาไทย";
		$englishtxt = "ภาษาอังกฤษ";
		$japantxt = "ภาษาญี่ปุ่น";
		$com_msg_success = "บันทึกข้อมูลสำเร็จ";
		$com_msg_error_save = "ไม่สามารถบันทึกข้อมูลได้";
		$d_waitapprove = "อยู่ระหว่างรอการอนุมัติ";
		$regis_sub = "หลักสูตรนี้เต็มแล้ว ชื่อของคุณจะถูกบันทึกในรายชื่อสำรอง";
		$r_notregister = "ยังไม่ลงทะเบียน";
		$wg_datanotfound = "ไม่พบหลักสูตรหรือแบบสำรวจหรือลิงก์ไม่สามารถเข้าถึงได้";
		$cos_expired = "หลักสูตรหรือแบบสำรวจยังไม่เปิดใช้งานหรือปิดไปแล้ว";
		$qiz_not_complete = "กรุณากดบันทึกคำตอบก่อนทำการยืนยันแบบทดสอบ";
		$save_complete = "ส่งคำตอบสำเร็จ";
		$confirm_submit_quiz = "คุณแน่ใจที่จะส่งคำตอบหรือไม่ ?";
		$noti_clicksave = "กรุณาตอบคำถาม";
		$answer_wrong = "คุณตอบผิด กรุณาตอบอีกครั้ง";
		$chk_answer_label = 'ตรวจคำตอบ';
		$preExam_label = 'แบบทดสอบก่อนเรียน';
		$finalExam_label = 'แบบทดสอบหลังเรียน';
		$cannotaccess = 'กรุณาทำส่วนก่อนหน้าให้เสร็จสิ้นจึงจะสามารถเข้าสู่ส่วนถัดไปได้';
		$condition_msg = 'ท่านจะสามารถเรียนหลักสูตรนี้ได้ เมื่อท่านผ่านหลักสูตรตามที่กำหนด <br>"_coursename_"';
		$passtxt = 'ผ่าน';
		$failtxt = 'ไม่ผ่าน';
		$msg_cert = 'คุณได้รับประกาศนียบัตร กรุณาตรวจสอบที่เมนูประกาศนียบัตร';
		$godashboardtxt = 'ไปหน้าหลักผู้ใช้งาน';
		$review_answertxt = 'ทบทวนคำตอบอีกครั้ง';
		$view_certtxt = 'ดูประกาศนียบัตร';
		
		$msg_requiresurvey = "กรุณาทำแบบสำรวจหลังเรียนก่อน เพื่อรับใบประกาศนียบัตร";
		$msg_requiresurveynoncert = "กรุณาทำแบบสำรวจก่อน เพื่อจบหลักสูตร";
		$goto_surveytxt = "ไปทำแบบสำรวจ";
	} else if ($lang_select == "english") {
		$createBy = "Created by";
		$lesson_file = "Course Material";
		$survey_txt = "Survey";
		$pointtxt = "Score";
		$finalpointtxt = "Final score";
		$resultcos = "Result";
		$lessontxt = "Lesson";
		$preNo = "No.";
		$summarytxt = "Description";
		$close = "Close";
		$yes_btn = "Yes";
		$no_btn = "No";
		$m_previous = "Back";
		$saveR = "Save";
		$m_next = "Next";
		$preSend = "Submit answer";
		$full_screentxt = "Enter full screen";
		$qiz_starttxt = "Start test";
		$download_file = "Download file";
		$m_ok = "OK";
		$go_to_course = "Go to Course";
		$Les_video = "Video";
		$hinttxt = "Hint";
		$statustxt = "Status";
		$sent_survey = "Submit Survey";
		$periodtxt = "Course period";
		$Chooselangtxt = "Please select language";
		$thailandtxt = "Thai";
		$englishtxt = "English";
		$japantxt = "Japanese";
		$com_msg_success = "Saved successful";
		$com_msg_error_save = "Cannot save information";
		$d_waitapprove = "Pending approval";
		$regis_sub = "This course is fully booked. Your name will be in the waiting list.";
		$r_notregister = "Not registered yet";
		$wg_datanotfound = "This course/survey was not found or the link is unable to access.";
		$cos_expired = "This course/survey has not been opened/already expired.";
		$qiz_not_complete = "Please save the answer before confirming test";
		$save_complete = "Submitted successful";
		$confirm_submit_quiz = "Are you sure you want to submit ?";
		$noti_clicksave = "Please answer the questions.";
		$answer_wrong = "Your answer is wrong, Please try again.";
		$chk_answer_label = 'Review answer';
		$preExam_label = 'Pre-test';
		$finalExam_label = 'Post-test';
		$cannotaccess = 'Please complete previous section before accessing to next section';
		$condition_msg = 'You will be eligible for this course after you have completed a prerequisite <br>"_coursename_';
		$passtxt = 'Passed';
		$failtxt = 'Failed';
		$msg_cert = 'You got certificate. Please check in the certificate menu.';
		$godashboardtxt = 'Go to Dashboard';
		$review_answertxt = 'Review answer again';
		$view_certtxt = 'View Certificate';
		
		$msg_requiresurvey = "Please complete the course survey to receive certificate.";
		$msg_requiresurveynoncert = "Please complete the survey to complete course.";
		$goto_surveytxt = "Go to Survey";
	} else {
		$createBy = "作成者：";
		$lesson_file = "学習資料";
		$survey_txt = "アンケート";
		$pointtxt = "点数";
		$finalpointtxt = "Final score";
		$resultcos = "結果";
		$lessontxt = "レッスン";
		$preNo = "番号";
		$summarytxt = "説明";
		$close = "閉";
		$yes_btn = "はい";
		$no_btn = "いいえ";
		$close = "閉";
		$m_previous = "戻る";
		$saveR = "保存";
		$m_next = "次";
		$preSend = "回答を提出";
		$full_screentxt = "ﾌﾙｽｸﾘｰﾝ";
		$qiz_starttxt = "ﾃｽﾄ開始";
		$download_file = "ﾀﾞｳﾝﾛｰﾄﾞﾌｧｲﾙ";
		$m_ok = "OK";
		$go_to_course = "ｺｰｽへ";
		$Les_video = "動画";
		$hinttxt = "ﾋﾝﾄ";
		$statustxt = "状況";
		$sent_survey = "アンケートを提出する";
		$periodtxt = "ｺｰｽ期間";
		$Chooselangtxt = "言語を選択して下さい";
		$thailandtxt = "ﾀｲ語";
		$englishtxt = "英語";
		$japantxt = "日本語";
		$com_msg_success = "保存完了";
		$com_msg_error_save = "情報を保存できません。";
		$d_waitapprove = "承認待ち";
		$regis_sub = "このｺｰｽは定員に達しています。待機ﾘｽﾄに入りました。";
		$r_notregister = "未登録";
		$wg_datanotfound = "このコース/アンケートは見つかりません又はリンクにアクセスできません。";
		$cos_expired = "このコース/アンケートはまだ開始していません又は期限が切れました。";
		$qiz_not_complete = "ﾃｽﾄをｺﾝﾌｧｰﾑする前に回答を保存して下さい。";
		$save_complete = "提出完了";
		$confirm_submit_quiz = "本当に提出しますか？";
		$noti_clicksave = "質問に回答してください。";
		$answer_wrong = "回答が間違っています。再度回答して下さい。";
		$chk_answer_label = '回答ﾚﾋﾞｭｰ';
		$preExam_label = '事前テスト';
		$finalExam_label = '事後テスト';
		$cannotaccess = '次のセクションを続けるため、前のセクションを完了して下さい。';
		$condition_msg = 'このコースを受けることにあたり、以下のコースを完了しなければなりません<br>_coursename_';
		$passtxt = '合格';
		$failtxt = '不合格';
		$msg_cert = '証明書を取得しました。証明書メニューで確認してください。';
		$godashboardtxt = 'ﾀﾞｯｼｭﾎﾞｰﾄﾞへ';
		$review_answertxt = '回答を再確認';
		$view_certtxt = '証明書を確認';

		$msg_requiresurvey = "証明書を受け取るために、ｺｰｽのｱﾝｹｰﾄにご回答ください。";
		$msg_requiresurveynoncert = "ｺｰｽ完成ために、ｺｰｽのｱﾝｹｰﾄにご回答ください。";
		$goto_surveytxt = "ｱﾝｹｰﾄへ";
	}
	?>
	<?php if ($course_main['isCondition'] == "1") { ?>
		swal({
			title: '<?php echo str_replace('_coursename_', $course_main['msgCondition'], $condition_msg); ?>',
			text: "",
			type: 'warning',
			showCancelButton: false,
			confirmButtonClass: 'btn btn-primary',
			confirmButtonText: '<?php echo $m_ok; ?>'
		}).then(function() {
			window.open("<?php echo REAL_PATH; ?>/coursemain/all_courses", "_self");
		})
	<?php } ?>
	<?php if (!$has_course_main) { ?>
		swal({
			title: '<?php echo $wg_datanotfound; ?>',
			text: "",
			type: 'warning',
			showCancelButton: false,
			confirmButtonClass: 'btn btn-primary',
			confirmButtonText: '<?php echo $m_ok; ?>'
		}).then(function() {
			window.open("<?php echo REAL_PATH; ?>/coursemain/all_courses", "_self");
		});
		<?php } else {
		if ($isFirsttime == "1") { ?>
			if (isstatus == 1) {
				$(window).on('load', function() {
					$('#select_lang_modal').modal('show');
				});
			}
			//$('#select_lang_modal').modal({backdrop: 'static', keyboard: false});
	<?php     }
	} ?>

	$(document).on("keydown", function(ev) {
		if (ev.keyCode == 27 || ev.keyCode == 122) {
			document.getElementById('scorm_play_iframe').style.width = "100%";
			document.getElementById('scorm_play_iframe').style.height = "100%";
			document.getElementById("div_scorm_ddd").style.height = "500px";
		}
	})

	function onselectVal(type, ques_id, value) {
		$('#tc_answer_' + type + ques_id).val(value);
	}

	function validateQuizAnswerUpload(type, qiz_id, ques_id) {
		var required = $('#ques_upload_required_' + type + '_' + ques_id).val();
		if (required != "1") {
			return true;
		}

		var input = $('#tc_upload_' + type + '_' + qiz_id + '_' + ques_id)[0];
		if (!input) {
			return true;
		}

		var hasExistingFile = $(input).attr('data-existing') != "";
		if (!input.files.length) {
			return hasExistingFile;
		}

		var file = input.files[0];
		var uploadType = $('#ques_upload_type_' + type + '_' + ques_id).val() || 'both';
		var maxMb = parseInt($('#ques_upload_max_mb_' + type + '_' + ques_id).val() || '10');
		var ext = file.name.split('.').pop().toLowerCase();
		var imageExts = ['gif', 'jpg', 'jpeg', 'png'];
		var videoExts = ['mp4', 'webm', 'ogv', 'mov'];
		var allowedExts = uploadType == 'image' ? imageExts : (uploadType == 'video' ? videoExts : imageExts.concat(videoExts));

		if (allowedExts.indexOf(ext) === -1) {
			return false;
		}

		return file.size <= (maxMb * 1024 * 1024);
	}

	function validateQuizUploads(type, qiz_id) {
		var firstInvalid = "";
		$('input[name="ques_id_' + type + qiz_id + '[]"]').each(function() {
			var quesId = $(this).val();
			if (!validateQuizAnswerUpload(type, qiz_id, quesId) && firstInvalid == "") {
				firstInvalid = quesId;
			}
		});

		if (firstInvalid != "") {
			swal({
				title: 'Please upload a valid required file before continuing.',
				text: "",
				type: 'warning',
				showCancelButton: false,
				confirmButtonClass: 'btn btn-primary',
				confirmButtonText: '<?php echo $m_ok; ?>'
			});
			return false;
		}

		return true;
	}

	function rechk_onclick(lcode, ltype) {
		///console.log(lcode,ltype);
		$.ajax(base_url + '/lesson/updateTrans/' + lcode, {
			type: 'POST',
			dataType: 'json',
			success: function(arresult) {
				/*if(arresult.rs){
				  console.log('Update complete.');
				}else{
				  console.log('Can not update.');
				}*/
			}
		});
	}

	function openFullscreen(id) {
		var elem = document.getElementById(id);

		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.mozRequestFullScreen) {
			/* Firefox */
			elem.mozRequestFullScreen();
		} else if (elem.webkitRequestFullscreen) {
			/* Chrome, Safari & Opera */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) {
			/* IE/Edge */
			elem.msRequestFullscreen();
		}
		if (id == "div_course_detail") {
			var heightdiv_description = $('#div_description_cos');
			if (parseInt(heightdiv_description.outerHeight()) > 400) {
				document.getElementById("contents_main").style.height = "500px";
				document.getElementById("div_description_cos").style.height = "400px";
				document.getElementById("div_description_cos").style.overflow = "hidden";
				document.getElementById("div_description_cos").style.overflowY = "scroll";
			}
		}

		if (isFullScreen()) {
			closeFullscreen(id);
			if (id == "div_course_detail") {
				document.getElementById("contents_main").style.height = "";
				document.getElementById("div_description_cos").style.height = "";
				document.getElementById("div_description_cos").style.overflow = "hidden";
				document.getElementById("div_description_cos").style.overflowY = "hidden";
			}
		}

		$.ajax({
			url: "<?= base_url() ?>index.php/updatedatacourse/rechk_status_lesson/",
			method: 'POST',
			dataType: "json",
			data: {
				cos_id: '<?php echo $cos_id; ?>',
				cosen_id: '<?php echo $cosen_id; ?>'
			},
			success: function(data) {
				$("#lessonheader").css("background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;");
				$("#lessonheader").removeClass("imat-red-bg btn-danger");
				if (parseInt(data.status) == parseInt(data.numles)) {
					$('#txtstatus_lesson').html('<i class="fa fas fa-check mr-2"></i>');
					$("#lessonheader").css("background-color: #fe0000;color: #ecf0f1;border-color: #fe0000;");
					$("#lessonheader").addClass("imat-red-bg btn-danger");
					$("#lessonheader").attr("data-statustc", "1");
					runlooprechk();
				} else if (parseFloat(data.status) > 0) {
					$('#txtstatus_lesson').html('<i class="fa fas fa-hourglass-half mr-2"></i>');
				} else {
					$('#txtstatus_lesson').html('');
				}
			}
		});
	}

	function updateStatusLesson() {
		$.ajax({
			url: "<?= base_url() ?>index.php/updatedatacourse/rechk_status_lesson/",
			method: 'POST',
			dataType: "json",
			data: {
				cos_id: '<?php echo $cos_id; ?>',
				cosen_id: '<?php echo $cosen_id; ?>'
			},
			success: function(data) {
				$("#lessonheader").css("background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;");
				$("#lessonheader").removeClass("imat-red-bg btn-danger");
				if (data.status != "error") {
					if (parseInt(data.status) == parseInt(data.numles)) {
						$('#txtstatus_lesson').html('<i class="fa fas fa-check mr-2"></i>');
						$("#lessonheader").css("background-color: #fe0000;color: #ecf0f1;border-color: #fe0000;");
						$("#lessonheader").addClass("imat-red-bg btn-danger");
						$("#lessonheader").attr("data-statustc", "1");
						runlooprechk();
					} else if (parseFloat(data.status) > 0) {
						$('#txtstatus_lesson').html('<i class="fa fas fa-hourglass-half mr-2"></i>');
					} else {
						$('#txtstatus_lesson').html('');
					}
				} else {
					window.location.href =
						"<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
				}
			}
		});
		$.ajax({
			url: "<?= base_url() ?>index.php/updatedatacourse/updateStatusLessonAll/",
			method: 'POST',
			dataType: "json",
			data: {
				cos_id: '<?php echo $cos_id; ?>',
				cosen_id: '<?php echo $cosen_id; ?>'
			},
			success: function(data) {
				if (data.length > 0) {
					for (let a = 0; a < data.length; a++) {
						if (data[a].statusTc != 0 && data[a].statusTc != 1) {
							let backgroundColor = "#fe0000";
							// if (data[a].statusTc == 1) {
							//     backgroundColor = "#F0C008";
							// }
							$(".tab-menu-lesson-" + data[a].lesId).css({
								"background-color": backgroundColor,
								"color": "#ffffff"
							});
						}
					}

					if (data.length == 1) {
						$("#lesson_" + data[0].lesId).addClass("active show");
					}
				}
			}
		});
	}

	function isFullScreen() {
		return window.screenTop == 0 ? true : false;
	}

	function closeFullscreen(id) {
		var elem = document.getElementById(id);
		if (elem.exitFullscreen) {
			elem.exitFullscreen();
		} else if (elem.mozCancelFullScreen) {
			/* Firefox */
			elem.mozCancelFullScreen();
		} else if (elem.webkitExitFullscreen) {
			/* Chrome, Safari and Opera */
			elem.webkitExitFullscreen();
		} else if (elem.msExitFullscreen) {
			/* IE/Edge */
			elem.msExitFullscreen();
		}
	}

	function inArray(needle, haystack) {
		var length = haystack.length;
		for (var i = 0; i < length; i++) {
			if (haystack[i] == needle) return true;
		}
		return false;
	}

	function click_previous(id) {
		$('a[href="#' + id + '"]').tab('show');
	}

	function click_next(id, ques_id, qiz_id, type) {
		if (!validateQuizAnswerUpload(type, qiz_id, ques_id)) {
			swal({
				title: 'Please upload a valid required file before continuing.',
				text: "",
				type: 'warning',
				showCancelButton: false,
				confirmButtonClass: 'btn btn-primary',
				confirmButtonText: '<?php echo $m_ok; ?>'
			});
			return;
		}
		var ques_type = $('#ques_type_' + type + '_' + ques_id).val();
		var ques_score = $('#ques_score_' + type + '_' + ques_id).val();
		var quiz_model = $('#quiz_model_' + type + '_' + qiz_id).val();
		var quiz_ishint = $('#quiz_ishint_' + type + '_' + qiz_id).val();
		var tc_save = $('#tc_save_' + type + '_' + ques_id).val();
		var mul_answer = $('#mul_answer_' + type + '_' + ques_id).val();

		var answer = "";
		var chk_answer = 1;
		if (ques_type == "multi" || ques_type == "2choice") {
			var radioValue = $('input[name=multi_choice_group_' + type + '_' + ques_id + ']:checked').val();
			if (radioValue && quiz_model == "1") {
				answer = radioValue;
				var res = mul_answer.split(",");
				if (!inArray(answer, res)) {
					chk_answer = 0;
					if (quiz_ishint == "1") {
						var ques_hintname = $('#ques_hintname_' + type + '_' + ques_id).val();
						var ques_hintdetail = $('#ques_hintdetail_' + type + '_' + ques_id).val();
						var ques_hintimg = $('#ques_hintimg_' + type + '_' + ques_id).val();
						$('#hint_modal').modal('show');
						$('#txtques_hintname').text(ques_hintname);
						$('#txtques_hintdetail').text(ques_hintdetail);
						$("#imgques_hintimg").attr("src", "<?php echo REAL_PATH . '/uploads/hint/' ?>" + ques_hintimg);
					} else {
						swal({
							title: '<?php echo $answer_wrong; ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo $m_ok; ?>'
						})
					}
				}
			}
		} else {
			var txtValue = $('#txt_answer_' + type + '_' + ques_id).val();
			if (txtValue != "") {
				answer = txtValue;
			} else {
				answer = "";
			}
		}
		var answer_ques = $('#tc_answer_' + type + ques_id).val();
		var upload_ques = $('#ques_upload_required_' + type + '_' + ques_id).val() == "1" && validateQuizAnswerUpload(type, qiz_id, ques_id);
		if (quiz_model == "1") {
			if (answer_ques != "" || upload_ques) {
				if (chk_answer == 1) {
					$('a[href="#' + id + '"]').tab('show');
				}
			} else {
				swal({
					title: '<?php echo $noti_clicksave; ?>',
					text: "",
					type: 'warning',
					showCancelButton: false,
					confirmButtonClass: 'btn btn-primary',
					confirmButtonText: '<?php echo $m_ok; ?>'
				})
			}
		} else {
			if (chk_answer == 1) {
				$('a[href="#' + id + '"]').tab('show');
			}
		}

	}

	function click_save(ques_id, qiz_id, type) {
		if (!validateQuizAnswerUpload(type, qiz_id, ques_id)) {
			swal({
				title: 'Please upload a valid required file before saving.',
				text: "",
				type: 'warning',
				showCancelButton: false,
				confirmButtonClass: 'btn btn-primary',
				confirmButtonText: '<?php echo $m_ok; ?>'
			});
			return;
		}
		var ques_type = $('#ques_type_' + type + '_' + ques_id).val();
		var ques_score = $('#ques_score_' + type + '_' + ques_id).val();
		var quiz_model = $('#quiz_model_' + type + '_' + qiz_id).val();
		var mul_answer = $('#mul_answer_' + type + '_' + ques_id).val();
		var answer = "";
		var tc_score = 0;

		var formdata = new FormData($('#' + type + '_form' + qiz_id)[0]);


		$('#tc_save_' + type + '_' + ques_id).val('1');
		if (ques_type == "multi" || ques_type == "2choice") {
			var radioValue = $('input[name=multi_choice_group_' + type + '_' + ques_id + ']:checked').val();
			if (radioValue) {
				answer = radioValue;
				var res = mul_answer.split(",");
				if (inArray(answer, res)) {
					tc_score = ques_score;
				}
			} else {
				answer = "";
			}
		} else {
			var txtValue = $('#txt_answer_' + type + '_' + ques_id).val();
			if (txtValue != "") {
				answer = txtValue;
				tc_score = ques_score;
			} else {
				answer = "";
			}
		}

		$.ajax({
			url: "<?= base_url() ?>index.php/querydata/save_question/" + qiz_id,
			method: 'POST',
			data: formdata,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(data) {
				if (data.status == "2") {
					$('#tc_save' + ques_id).val(1);
					swal({
						title: '<?php echo $com_msg_success; ?>!',
						text: "",
						type: 'success',
						showCancelButton: false,
						confirmButtonClass: 'btn btn-primary',
						confirmButtonText: '<?php echo $m_ok; ?>'
					})
				} else if (data.status == "4") {
					swal({
						title: 'Uploaded file does not match this question requirement.',
						text: "",
						type: 'warning',
						showCancelButton: false,
						confirmButtonClass: 'btn btn-primary',
						confirmButtonText: '<?php echo $m_ok; ?>'
					})
				} else {
					swal({
						title: '<?php echo $com_msg_error_save; ?>',
						text: "",
						type: 'warning',
						showCancelButton: false,
						confirmButtonClass: 'btn btn-primary',
						confirmButtonText: '<?php echo $m_ok; ?>'
					}).then(function() {
						window.location.href =
							"<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
					});
				}
			}
		});
		/*if(quiz_model=="1"){
		  if(answer!=""){
		    $.ajax({
		      url:"<?= base_url() ?>index.php/querydata/save_question",
		      method:'POST',
		      data:{qiz_id:qiz_id,ques_id:ques_id,answer:answer,score:tc_score,cosen_id:'<?php echo $cosen_id; ?>'},
		      dataType:"json",
		      success:function(data)
		      {
		          if(data.status=="2"){
		              $('#tc_save'+ques_id).val(1);
		              swal({
		                  title: '<?php echo $com_msg_success; ?>',
		                  text: "",
		                  type: 'success',
		                  showCancelButton: false,
		                  confirmButtonClass: 'btn btn-primary',
		                  confirmButtonText: '<?php echo $m_ok; ?>'
		              })
		          }else{
		              swal({
		                  title: '<?php echo $com_msg_error_save; ?>',
		                  text: "",
		                  type: 'warning',
		                  showCancelButton: false,
		                  confirmButtonClass: 'btn btn-primary',
		                  confirmButtonText: '<?php echo $m_ok; ?>'
		              })
		          }
		      }
		    });
		  }else{
		    swal({
		        title: '<?php echo $com_msg_error_save; ?>',
		        text: "",
		        type: 'warning',
		        showCancelButton: false,
		        confirmButtonClass: 'btn btn-primary',
		        confirmButtonText: '<?php echo $m_ok; ?>'
		    })
		  }
		}else{
		    $.ajax({
		      url:"<?= base_url() ?>index.php/querydata/save_question",
		      method:'POST',
		      data:{qiz_id:qiz_id,ques_id:ques_id,answer:answer,score:tc_score,cosen_id:'<?php echo $cosen_id; ?>'},
		      dataType:"json",
		      success:function(data)
		      {
		          if(data.status=="2"){
		              $('#tc_save_'+type+'_'+ques_id).val(1);
		              swal({
		                  title: '<?php echo $com_msg_success; ?>',
		                  text: "",
		                  type: 'success',
		                  showCancelButton: false,
		                  confirmButtonClass: 'btn btn-primary',
		                  confirmButtonText: '<?php echo $m_ok; ?>'
		              })
		          }else{
		              swal({
		                  title: '<?php echo $com_msg_error_save; ?>',
		                  text: "",
		                  type: 'warning',
		                  showCancelButton: false,
		                  confirmButtonClass: 'btn btn-primary',
		                  confirmButtonText: '<?php echo $m_ok; ?>'
		              })
		          }
		      }
		    });
		}*/
	}

	function showAlert(id) {
		var IDs = [];
		$("#" + id).find("a").each(function() {
			IDs.push(this.id);
		});
		if (IDs.length > 0) {
			for (var i = IDs.length - 1; i >= 0; i--) {
				if (IDs[i] != "") {
					if ($('#' + IDs[i]).prop("disabled")) {
						swal({
							title: '<?php echo $cannotaccess; ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo $m_ok; ?>'
						})
					}
				}
			}
		}
	}
	$(document).on('click', '.btn_downloadfile', function(event) {
		event.preventDefault();
		var id = $('#fil_id_downloadfile').val();
		var path = $('#fil_path_downloadfile').val();
		window.location.href = "<?php echo base_url() . '/uploads/document/' ?>" + path;
	});
	$(document).on('click', '.les_onclick', function(event) {
		event.preventDefault();
		var les_id = $(this).attr("id");
		$('html,body').animate({
			scrollTop: $('#less_div').offset().top - 150
		}, 'fast');
		$.ajax({
			url: "<?= base_url() ?>index.php/updatedatacourse/update_lesson/" + les_id,
			method: 'POST',
			dataType: "json",
			data: {
				cosen_id: '<?php echo $cosen_id; ?>'
			},
			success: function(data) {}
		});

		updateStatusLesson();
	});
	$(document).on('click', '.update1Lesson', function(event) {
		event.preventDefault();
		const les_id =
			"<?php echo isset($lesson_arr[0]['les_id']) && $lesson_arr[0]['les_id'] != '' ? $lesson_arr[0]['les_id'] : ''; ?>";
		if (les_id != "") {
			$.ajax({
				url: "<?= base_url() ?>index.php/updatedatacourse/update_lesson/" + les_id,
				method: 'POST',
				dataType: "json",
				data: {
					cosen_id: '<?php echo $cosen_id; ?>'
				},
				success: function(data) {
					updateStatusLesson();
				}
			});
		}
	});


	function runlooprechk() {
		var numrechk = 0;
		for (iloop = 0; iloop < loop_number; iloop++) {
			var status_tc = $('.div_header_tab' + numrechk).attr('data-statustc');
			if (iloop != 0) {
				if (status_tc == 0) {
					$('.div_header_tab' + iloop).prop("disabled", true);
				} else {
					$('.div_header_tab' + iloop).prop("disabled", false);
				}
			}
			if (iloop > 0) {
				numrechk++;
			}
		}
	}

	function createButton(text, classs, style, id, column, cb) {
		return $('<div class="' + column + '"> <button class="' + classs + '" style="' + style + '" id="' + id + '">' + text +
			'</button></div>').on('click', cb);
	}

	$(document).on('click', '.btn_send', function(event) {
		event.preventDefault();
		var qiz_id = $(this).attr("id");
		var ques_id = $(this).attr("ques_id");
		var type = $(this).attr("typeval");

		var amount_ques = parseInt($('#amount_ques_' + type + '_' + qiz_id).val());
		var qiztc_id = $('#qiztc_id_' + type + '_' + qiz_id).val();
		var numloopchk = 1;
		var numchk = 1;
		var pagenumber = 0;
		var formdata = new FormData($('#' + type + '_form' + qiz_id)[0]);
		var arr = $('input[name="tc_answer_' + type + qiz_id + '[]"]').map(function() {
			var answerQuesId = this.id.replace('tc_answer_' + type, '');
			var uploadAnswered = $('#ques_upload_required_' + type + '_' + answerQuesId).val() == "1" && validateQuizAnswerUpload(type, qiz_id, answerQuesId);
			if (this.value != "" || uploadAnswered) {
				numloopchk++;
				return this.value; // $(this).val()
			} else {
				if (numchk == 1) {
					pagenumber = numloopchk;
					numchk++;
				}
			}
		}).get();
		if (pagenumber != 0) {
			swal({
				title: '<?php echo $noti_clicksave; ?>',
				text: "",
				type: 'warning',
				showCancelButton: false,
				confirmButtonClass: 'btn btn-primary',
				confirmButtonText: '<?php echo $m_ok; ?>'
			}).then(function() {
				$('.nav-tabs a[href="#quiz_' + qiz_id + '_' + pagenumber + '"]').tab('show');
			})
		} else {
			if (!validateQuizUploads(type, qiz_id)) {
				return;
			}
			var ques_type = $('#ques_type_' + type + '_' + ques_id).val();
			var ques_score = $('#ques_score_' + type + '_' + ques_id).val();
			var quiz_model = $('#quiz_model_' + type + '_' + qiz_id).val();
			var quiz_ishint = $('#quiz_ishint_' + type + '_' + qiz_id).val();
			var tc_save = $('#tc_save_' + type + '_' + ques_id).val();
			var mul_answer = $('#mul_answer_' + type + '_' + ques_id).val();

			var answer = "";
			var chk_answer = 1;
			if (ques_type == "multi" || ques_type == "2choice") {
				var radioValue = $('input[name=multi_choice_group_' + type + '_' + ques_id + ']:checked').val();
				if (radioValue && quiz_model == "1") {
					answer = radioValue;
					var res = mul_answer.split(",");
					if (!inArray(answer, res)) {
						chk_answer = 0;
						if (quiz_ishint == "1") {
							var ques_hintname = $('#ques_hintname_' + type + '_' + ques_id).val();
							var ques_hintdetail = $('#ques_hintdetail_' + type + '_' + ques_id).val();
							var ques_hintimg = $('#ques_hintimg_' + type + '_' + ques_id).val();
							$('#hint_modal').modal('show');
							$('#txtques_hintname').text(ques_hintname);
							$('#txtques_hintdetail').text(ques_hintdetail);
							$("#imgques_hintimg").attr("src", "<?php echo REAL_PATH . '/uploads/hint/' ?>" + ques_hintimg);
						} else {
							swal({
								title: '<?php echo $answer_wrong; ?>',
								text: "",
								type: 'warning',
								showCancelButton: false,
								confirmButtonClass: 'btn btn-primary',
								confirmButtonText: '<?php echo $m_ok; ?>'
							})
						}
					}
				}
			} else {
				var txtValue = $('#txt_answer_' + type + '_' + ques_id).val();
				if (txtValue != "") {
					answer = txtValue;
				} else {
					answer = "";
				}
			}
			if (chk_answer == 1) {

				swal({
					title: '<?php echo $confirm_submit_quiz; ?> ',
					text: "",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: "#1abc9c",
					cancelButtonColor: "#DD6B55",
					confirmButtonText: '<?php echo $yes_btn; ?>',
					cancelButtonText: '<?php echo $no_btn; ?>'
				}).then(function(isChk) {
					if (isChk.value) {
						$.ajax({
							url: "<?= base_url() ?>index.php/querydata/send_question/" + qiz_id,
							method: 'POST',
							data: formdata,
							contentType: false,
							processData: false,
							dataType: "json",
							success: function (data) {
								if (data.status == "2") {
									if (parseInt(data.isSaSub) === 0) {
										if (data.is_last == "1") {
											var isCert = 'col-lg-6';
											if (data.isCert == "1") {
												isCert = 'col-lg-4';
											}
											if (data.require_survey == "1" && data.has_survey == "0" && data.survey_id && data.survey_id !== "0") {
												isCert = 'col-lg-4';
											}

											$('.swal2-popup').addClass('swal-size');
											var buttons = $('<br><br><br><div class="row button-group">')
												.append(createButton(
													'<i class="mdi mdi-backburger"></i> <?php echo $godashboardtxt; ?>',
													'btn btn-flat btn-block btn-primary btn_dashboard btnalert', '', '', isCert,
													function () {
														swal.closeModal();
													}
												))
												.append(createButton(
													'<i class="mdi mdi-magnify"></i> <?php echo $review_answertxt; ?>',
													'btn btn-flat btn-block btn-info view_answer btnalert', '', '', isCert,
													function () {
														swal.closeModal();
													}
												));

											var typetxt = "success";
											var text = '';
											if (data.quiz_grade == "1") {
												text = '<?php echo $pointtxt; ?>: ' + data.score;
												if (data.resultscorecos != "") {
													text += '<br><?php echo $resultcos; ?>: ' + data.resultscorecos;
													text += data.result_cos == "fail" ? " (<?php echo $failtxt; ?>)<br>" : " (<?php echo $passtxt; ?>)<br>";
												} else {
													text += '<br><?php echo $resultcos; ?>: ';
													text += data.result_cos == "fail" ? "<?php echo $failtxt; ?><br>" : "<?php echo $passtxt; ?><br>";
												}
											} else {
												if (data.result_cos == "fail") {
													text = "<?php echo $resultcos; ?>: <?php echo $failtxt; ?><br>";
												} else {
													text = "<?php echo $resultcos; ?>: <?php echo $passtxt; ?><br>";
												}
											}

											// ✅ ถ้ายังไม่ทำแบบสำรวจ แต่ระบบบังคับ → แสดงปุ่ม "ไปทำแบบสำรวจ"
											if (data.require_survey == "1" && data.has_survey == "0" && data.survey_id && data.survey_id !== "0") {
												buttons.append(createButton(
													'<i class="mdi mdi-clipboard-text"></i> <?php echo $goto_surveytxt; ?>',
													'btn btn-flat btn-block btn-warning btn_gotosurvey btnalert', '', data.survey_id, 'col-lg-4',
													function () {
														swal.closeModal();
													}
												));

												// ----------- 1. อัปเดตปุ่ม Quiz Header -----------
												let $quizHeader = $('#quizheader' + qiz_id);
												let $statusSpan = $('#txtstatus_quiz' + qiz_id);

												// ปรับ class และ style
												$quizHeader
													.removeClass('disable')
													.addClass('btn-danger imat-red-bg') // หรือ class ตามต้องการ
													.css({ color: '#fff' });

												let statusText = '';
												if (data.quiz_grade == "1") {
													statusText = ` (${data.score}: ${data.result == "pass" ? '<?php echo $passtxt; ?>' : '<?php echo $failtxt; ?>'})`;
												} else {
													statusText = ` (<?php echo $statustxt; ?>: ${data.result == "pass" ? '<?php echo $passtxt; ?>' : '<?php echo $failtxt; ?>'})`;
												}
												let quizLabel = data.quiz_type == 1 ? "<?php echo $preExam_label; ?>" : "<?php echo $finalExam_label; ?>";
												let quizName = data.quiz_name;

												$quizHeader.html(`
													<span id="txtstatus_quiz${qiz_id}"><i class="fa fas fa-check mr-2"></i></span>
													${quizLabel}: ${quizName} ${statusText}
													<i class="fa fa-chevron-right float-right"></i>
													<i class="fa fa-chevron-down float-right"></i>
												`);
												$('.btn-save-answer-quiz-' + qiz_id).hide();
												$('.btn-send-answer-quiz-' + qiz_id).hide();
												if (data.isCert == "1") {
													text += '<br><?php echo $msg_requiresurvey; ?>';
												} else {
													text += '<br><?php echo $msg_requiresurveynoncert; ?>';
												}
											}

											// ✅ เงื่อนไขแสดงปุ่มใบประกาศ (ถ้าทำแบบสำรวจแล้ว หรือไม่ require)
											if (data.isCert == "1" && !(data.require_survey == "1" && data.has_survey == "0")) {
												buttons.append(createButton(
													'<i class="mdi mdi-certificate"></i> <?php echo $view_certtxt; ?>',
													'btn btn-flat btn-block btn-success btn_certificate btnalert', '', '',
													'col-lg-4',
													function () {
														swal.closeModal();
													}
												));
												text += '<?php echo $msg_cert; ?>';
											}

											buttons.append('</div>');

											swal({
												title: text,
												text: text,
												html: buttons,
												showConfirmButton: false,
												showCancelButton: false,
												allowEscapeKey: false,
												allowOutsideClick: false
											});
										} else {
											// ✅ ไม่ใช่ข้อสุดท้าย
											var text = '';
											var typetxt = "success";
											if (data.quiz_grade == "1") {
												text = '<?php echo $pointtxt; ?>: ' + data.score;
												if (data.result == "fail") {
													text += " (<?php echo $failtxt; ?>)<br>";
													typetxt = "warning";
												} else {
													text += " (<?php echo $passtxt; ?>)<br>";
												}
											} else {
												if (data.result == "fail") {
													text = "<?php echo $statustxt . ': ' . $failtxt; ?><br>";
													typetxt = "warning";
												} else {
													text = "<?php echo $statustxt . ': ' . $passtxt; ?><br>";
												}
											}

											swal({
												title: text,
												text: '',
												type: typetxt,
												showCancelButton: false,
												confirmButtonClass: 'btn btn-primary',
												confirmButtonText: '<?php echo $m_ok; ?>'
											}).then(function () {
												window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
											});
										}
									} else {
										swal({
											title: '<?php echo $save_complete; ?>!',
											text: "",
											type: 'success',
											showCancelButton: false,
											confirmButtonClass: 'btn btn-primary',
											confirmButtonText: '<?php echo $m_ok; ?>'
										}).then(function () {
											window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
										});
									}
								} else if (data.status == "4") {
									swal({
										title: 'Uploaded file does not match this question requirement.',
										text: "",
										type: 'warning',
										showCancelButton: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: '<?php echo $m_ok; ?>'
									})
								} else {
									swal({
										title: '<?php echo $com_msg_error_save; ?>',
										text: "",
										type: 'warning',
										showCancelButton: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: '<?php echo $m_ok; ?>'
									}).then(function () {
										window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
									});
								}
							}
						});

					}
				});
			}
		}
		/*var sumloop = 0;
		for (var i = arr.length - 1; i >= 0; i--) {
		  sumloop += parseInt(arr[i]);
		}
		if(sumloop==amount_ques){
		  $.ajax({
		    url:"<?= base_url() ?>index.php/querydata/send_question",
		    method:'POST',
		    data:{qiz_id:qiz_id,qiztc_id:qiztc_id,cosen_id:'<?php echo $cosen_id; ?>'},
		    dataType:"json",
		    success:function(data)
		    {
		        if(data.status=="2"){
		            swal({
		                title: '<?php echo $save_complete; ?>',
		                text: "",
		                type: 'success',
		                showCancelButton: false,
		                confirmButtonClass: 'btn btn-primary',
		                confirmButtonText: '<?php echo $m_ok; ?>'
		            }).then(function () {
		                location.reload();
		            })
		        }else{
		            swal({
		                title: '<?php echo $com_msg_error_save; ?>',
		                text: "",
		                type: 'warning',
		                showCancelButton: false,
		                confirmButtonClass: 'btn btn-primary',
		                confirmButtonText: '<?php echo $m_ok; ?>'
		            })
		        }
		    }
		  });
		}else{
		    swal({
		        title: '<?php echo $qiz_not_complete; ?>',
		        text: "",
		        type: 'warning',
		        showCancelButton: false,
		        confirmButtonClass: 'btn btn-primary',
		        confirmButtonText: '<?php echo $m_ok; ?>'
		    })
		}*/
	});
	$(document).on('click', '.btn_dashboard', function(event) {
		event.preventDefault();
		$('.swal2-popup').removeClass('swal-size');
		window.location.href = "<?php echo base_url() . 'dashboard'; ?>";
	});
	$(document).on('click', '.view_answer', function(event) {
		event.preventDefault();
		$('.swal2-popup').removeClass('swal-size');
		window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";
	});
	$(document).on('click', '.btn_gotosurvey', function (event) {
		event.preventDefault();
		swal.closeModal();

		// ----------- 2. เปิดปุ่ม Survey -----------
		let sid = $(this).attr("id");
		let $surveyBtn = $('#' + sid);

		// เปิด class ที่ถูก disable
		$surveyBtn.removeClass('disable').addClass('survey_main');
		$surveyBtn.prop("disabled", false);


		// ----------- 3. Scroll ไปยังแบบสำรวจ -----------
		let surveyDiv = $('#div_survey' + sid);
		if (surveyDiv.length > 0) {
			$('html, body').animate({
				scrollTop: surveyDiv.offset().top - 120
			}, 600);
			surveyDiv.addClass("highlight-survey");
			setTimeout(() => {
				surveyDiv.removeClass("highlight-survey");
			}, 2000);
		}
	});
	$(document).on('click', '.btn_certificate', function(event) {
		event.preventDefault();
		swal.close();
		$('.swal2-popup').removeClass('swal-size');
		$.ajax({
			url: "<?= base_url() ?>index.php/certificate/createfile",
			method: "POST",
			data: {
				cos_id: '<?php echo $cos_id; ?>'
			},
			dataType: "json",
			success: function(data) {
				$("#modal-certificate").modal({
					backdrop: false
				});
				$('#obj_pdf_cert').attr('src', "<?php echo REAL_PATH . "/uploads/certificate/"; ?>" + data);
				//$( "object").attr('data', "<?php echo REAL_PATH . "/uploads/certificate/"; ?>"+data);
				//$('#obj_pdf_cert').html( "<?php echo REAL_PATH . "/uploads/certificate/"; ?>"+data);
				//$('#obj_pdf_cert').attr('data', "<?php echo REAL_PATH . "/uploads/certificate/"; ?>"+data);
				//document.getElementById('obj_pdf_cert').data = "<?php echo REAL_PATH . "/uploads/certificate/"; ?>"+data;
			}
		});
	});
	$(document).on('click', '.view_doccos', function(event) {
		event.preventDefault();
		var id = $(this).attr("id");
		var path = $(this).attr("path");
		var lesid = $(this).attr("lesid");
		var typevalue = $(this).attr("typevalue");
		if (typevalue == "lesson_file") {
			$.ajax({
				url: "<?= base_url() ?>index.php/updatedatacourse/update_fil",
				method: 'POST',
				data: {
					fil_id: id,
					cosen_id: '<?php echo $cosen_id; ?>'
				},
				dataType: "json",
				success: function(data) {}
			});
		}
		var res = path.split(".");
		/*$('#modal-viewdocument').modal('show');*/
		$('#fil_id_downloadfile').val(id);
		$('#fil_path_downloadfile').val(path);
		window.open('<?php echo base_url() . 'viewdoc/fileview/'; ?>' + id + '/' + typevalue + '/<?php echo $cos_id; ?>',
			'_blank');
		/*if(res[1]=="pdf"){
    document.getElementById("iframe_document").src = "<?php echo base_url() . '/uploads/document/' ?>"+path;
  }else{*
/
  /*document.getElementById("iframe_document").src = "https://docs.google.com/gview?url=<?php echo base_url() . '/uploads/document/'; ?>"+path+"&embedded=true";*/
		//}

		$.ajax({
			url: "<?= base_url() ?>index.php/updatedatacourse/update_lesson/" + lesid,
			method: 'POST',
			dataType: "json",
			data: {
				cosen_id: '<?php echo $cosen_id; ?>'
			},
			success: function(data) {}
		});
		updateStatusLesson();
	});
</script>
