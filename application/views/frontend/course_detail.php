<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$raw_course_main = isset($course_main) && is_array($course_main) ? $course_main : array();
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
?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">

<link href="<?php echo REAL_PATH; ?>/assets/video/video-js.css" rel="stylesheet" type="text/css">
<script src="<?php echo REAL_PATH; ?>/assets/video/video.js"></script>
<!-- Import Library Overlay -->
<link href="<?php echo REAL_PATH; ?>/assets/video/videojs-overlay.css" rel="stylesheet" type="text/css">
<script src="<?php echo REAL_PATH; ?>/assets/video/videojs-overlay.min.js"></script>
<!-- Import Library Fullscreen Lanscape -->
<script src="<?php echo REAL_PATH; ?>/assets/video/videojs-mobile-ui.min.js"></script>
<style type="text/css">
.disable {
  pointer-events: none;
  cursor: default;
}

/*.customvtab .tabs-vertical li .nav-link.active, .customvtab .tabs-vertical li .nav-link:hover, .customvtab .tabs-vertical li .nav-link:focus {
          border-right: 2px solid #000000;
          color: #000000;
      }*/
.les_info img {
  width: 100%;
  height: auto;
}

.tab-body {
  width: 100%;
  height: calc(100% - 41px);
}

.tab-pane {
  width: 100% !important;
}

.break-word {
  word-wrap: break-word;
}

.btnalert {
  font-size: 13px;
  margin: 3px;
}

.swal2-popup {
  display: flex;
  min-width: 600px;
}

a,
h3,
h4,
p {
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;
}

.btn {
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;
}

.btn {
  white-space: normal !important;
  word-wrap: break-word;
  word-break: normal;
}

/*.swal-size{
display: flex;
width: fit-content;
padding: 50px;
min-height: 300px;
}

.swal-btn-group{
position: absolute;
bottom: 30px;
left: 0;
right: 0;
}

.swal-btn-group button{
margin : 3px;
}*/

  #video-loading {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    color: white;
    font-size: 1.5rem;
  }

  #video-loading.hidden {
    display: none;
  }
</style>
<script type="text/javascript">
</script>
</head>

<body class="fix-header fix-sidebar card-no-border">
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="loader">
      <div class="loader__figure"></div>
      <p class="loader__label"><?php if ($lang == "thai") {
                                  echo $foote[0]['da_title_th'];
                                } else {
                                  echo $foote[0]['da_title_en'];
                                } ?></p>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
      <div class="container-fluid">
        <div class="row col-12 page-titles">
          <div class="col-md-1 align-self-center">
            <button class="btn btn-outline-info btn-sm"
              onclick="window.location.href='<?php echo REAL_PATH . '/coursemain/my_course'; ?>'"><i
                class="mdi mdi-keyboard-return"></i> <?php echo ucwords(label('m_previous')); ?></button>
          </div>
          <div class="col-md-11 align-self-right" style="word-break: break-all;">
            <ol class="breadcrumb d-inline">
              <li class="breadcrumb-item d-inline"><a
                  href="<?php echo REAL_PATH; ?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
              <li class="breadcrumb-item d-inline"><a class="text-danger"
                  href="<?php echo REAL_PATH; ?>/coursemain/my_course"><?php echo ucwords(label('my_course')); ?></a>
              </li>
              <li class="breadcrumb-item active d-inline">
                <?php echo isset($course_main['cname']) ? ucwords($course_main['cname']) : ""; ?></li>
            </ol>
          </div>
        </div>
        <?php
        if ($lang_select == "") {
          $lang_select = $lang;
        }
        if ($lang_select == "thai") {
          $createBy = "สร้างโดย";
          $lesson_file = "เอกสารประกอบการเรียน";
          $survey_txt = "แบบสำรวจ";
          $pointtxt = "คะแนน";
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
          $wg_datanotfound = "ไม่พบหลักสูตรหรือแบบสอบถามหรือลิงก์ไม่สามารถเข้าถึงได้";
          $cos_expired = "หลักสูตรหรือแบบสอบถามยังไม่เปิดใช้งานหรือปิดไปแล้ว";
          $qiz_not_complete = "กรุณากดบันทึกคำตอบก่อนทำการยืนยันแบบทดสอบ";
          $save_complete = "ส่งคำตอบสำเร็จ";
          $confirm_submit_quiz = "คุณแน่ใจที่จะส่งคำตอบหรือไม่ ?";
          $noti_clicksave = "กรุณาตอบคำถาม";
          $answer_wrong = "คุณตอบผิด กรุณาตอบอีกครั้ง";
          $chk_answer_label = 'ตรวจคำตอบ';
          $preExam_label = 'แบบทดสอบก่อนเรียน';
          $finalExam_label = 'แบบทดสอบหลังเรียน';
          $cannotaccess = 'กรุณาทำส่วนก่อนหน้าให้เสร็จสิ้นจึงจะสามารถเข้าสู่ส่วนถัดไปได้';
          $condition_msg = 'ท่านจะสามารถเรียนหลักสูตรนี้ได้ เมื่อท่านผ่านหลักสูตรตามที่กำหนด (_coursename_)';
          
          $video_loading = 'กำลังโหลดวิดีโอ...';
          $video_cannot_loading = 'ไม่สามารถโหลดวิดีโอได้';
        } else if ($lang_select == "english") {
          $createBy = "Created by";
          $lesson_file = "Course Material";
          $survey_txt = "Survey";
          $pointtxt = "Score";
          $lessontxt = "Lesson";
          $preNo = "No.";
          $yes_btn = "Yes";
          $no_btn = "No";
          $summarytxt = "Description";
          $close = "Close";
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
          $condition_msg = 'You will be eligible for this course after you have completed a prerequisite (_coursename_)';
          
          $video_loading = 'Loading video…';
          $video_cannot_loading = 'Unable to load video';
        } else {
          $createBy = "作成者：";
          $lesson_file = "学習資料";
          $survey_txt = "アンケート";
          $pointtxt = "点数";
          $lessontxt = "レッスン";
          $preNo = "番号";
          $summarytxt = "説明";
          $close = "閉";
          $yes_btn = "はい";
          $no_btn = "いいえ";
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
          $sent_survey = "ｱﾝｹｰﾄを提出する";
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
          $save_complete = "提出完成";
          $confirm_submit_quiz = "本当に提出しますか？";
          $noti_clicksave = "質問に回答してください。";
          $answer_wrong = "回答が間違っています。再度回答して下さい。";
          $chk_answer_label = '回答ﾚﾋﾞｭｰ';
          $preExam_label = '事前テスト';
          $finalExam_label = '事後テスト';
          $cannotaccess = '次のセクションを続けるため、前のセクションを完了して下さい。';
          $condition_msg = '(_coursename_)の前提条件をｸﾘｱしたら、このｺｰｽを勉強することになります';
          
          $video_loading = '動画を読み込み中…';
          $video_cannot_loading = '	動画を読み込めませんでした。';
        }
        ?>
        <div class="row">
          <div class="col-auto col-md-12 col-lg-4 mb-0 card card-body">
            <img class="card-img-top img-responsive"
              src="<?php echo REAL_PATH; ?>/uploads/course/<?php echo $course_main['cos_pic']; ?>"
              onerror="this.src='<?php echo REAL_PATH; ?>/images/cover_course.jpg';" alt="Card image cap">
          </div>

          <div class="col-md-12 col-lg-8 mb-0 card card-body">
            <h4 class="text-truncate"><?php echo isset($course_main['cname']) ? $course_main['cname'] : ""; ?></h4>
            <div class="d-block position-relative">

              <div class="row">
                <div class="col-lg-8 col-sm-12 mt-3">
                  <!-- FOR DESKTOP -->
                  <small class="text-muted text-truncate position-absolute col-md-12 col-lg-8 p-0 hidden-xs-down"
                    style="bottom: 0;"><?php echo isset($course_main['com_name']) ? $createBy . ': ' . $course_main['com_name'] : ''; ?></small>

                  <!-- FOR MOBILE -->
                  <small
                    class="text-muted text-truncate hidden-sm-up"><?php echo isset($course_main['com_name']) ? $createBy . ': ' . $course_main['com_name'] : ''; ?></small>
                </div>
                <div class="col-lg-4 col-sm-12">
                  <?php if (isset($document_cos) && countArray($document_cos) > 0) { ?>

                  <a type="button" title="<?php echo $lesson_file; ?>" href="#"
                    class="btn btn-block waves-effect waves-light btn-secondary float-right dropdown-toggle float-right"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i
                      class="mdi mdi-file-document"></i><?php echo ' ' . $lesson_file; ?></a>
                  <div class="dropdown-menu" x-placement="bottom-start"
                    style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <?php foreach ($document_cos as $key_doccos => $value_doccos) {

                        if ($lang_select == "thai") {
                          $name_file = $value_doccos['name_file_th'] != "" ? $value_doccos['name_file_th'] : $value_doccos['name_file_eng'];
                          $name_file = $name_file != "" ? $name_file : $value_doccos['name_file_jp'];
                        } else if ($lang_select == "english") {
                          $name_file = $value_doccos['name_file_eng'] != "" ? $value_doccos['name_file_eng'] : $value_doccos['name_file_th'];
                          $name_file = $name_file != "" ? $name_file : $value_doccos['name_file_jp'];
                        } else {
                          $name_file = $value_doccos['name_file_jp'] != "" ? $value_doccos['name_file_jp'] : $value_doccos['name_file_eng'];
                          $name_file = $name_file != "" ? $name_file : $value_doccos['name_file_th'];
                        }
                      ?>
                    <button class="dropdown-item view_doccos" typevalue="course_file"
                      id="<?php echo $value_doccos['fil_cos_id']; ?>"
                      path="<?php echo $value_doccos['path_file']; ?>"><?php echo $name_file; ?></button>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
              </div>

            </div>
            <hr>
            <?php echo  isset($course_main['cdetail']) ? str_replace('../uploads/texteditor/', base_url() . '/uploads/texteditor/', $course_main['cdetail']) : ""; ?>
          </div>
        </div>

      </div>

      <?php $this->load->view('frontend/tab/course_option.php'); ?>


    </div>
  </div>

  <!-- SELECT LANGUAGE MODAL -->
  <div id="select_lang_modal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close"
            onclick="window.location.replace('<?php echo REAL_PATH; ?>/coursemain/all_courses');">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="lang_form" name="lang_form" autocomplete="off" method="POST"
          accept-charset="utf-8" class="form-horizontal p-t-20">
          <div class="col-lg-12">
            <h4 class="text-truncate mb-0" data-toggle="tooltip"
              title="<?php echo isset($course_main['cname']) ? $course_main['cname'] : ""; ?>">
              <?php echo isset($course_main['cname']) ? $course_main['cname'] : ""; ?></h4>
          </div>
          <div class="modal-body row pt-0">
            <div class="col-lg-6 pt-3">
              <img class="card-img-top img-responsive" style="max-width: 300px;"
                src="<?php echo REAL_PATH; ?>/uploads/course/<?php echo $course_main['cos_pic']; ?>"
                onerror="this.src='<?php echo REAL_PATH; ?>/images/cover_course.jpg';" alt="Card image cap">
            </div>
            <div class="col-lg-6 pt-3">
              <p class="card-text"><?php echo $periodtxt . ': '; ?>
                <br><?php echo isset($course_main['txt_period_course']) ? $course_main['txt_period_course'] : ''; ?>
              </p>
              <p class="mb-0"><?php echo $Chooselangtxt . ': '; ?></p>

              <select id="course_lang" name="course_lang" class="selectpicker">
                <?php if (isset($course_main['isENG']) && $course_main['isENG'] == "1") { ?><option value="english" <?php if ($course_main['select_lang'] == "english") {
                                                                                      echo "selected";
                                                                                    } ?>
                  data-icon="flag-icon flag-icon-us"><?php echo $englishtxt; ?></option><?php } ?>
                <?php if (isset($course_main['isTH']) && $course_main['isTH'] == "1") { ?><option value="thai" <?php if ($course_main['select_lang'] == "thai") {
                                                                                  echo "selected";
                                                                                } ?>
                  data-icon="flag-icon flag-icon-th">
                  <?php echo $thailandtxt; ?></option><?php } ?>
                <?php if (isset($course_main['isJP']) && $course_main['isJP'] == "1") { ?><option value="japan" <?php if ($course_main['select_lang'] == "japan") {
                                                                                    echo "selected";
                                                                                  } ?>
                  data-icon="flag-icon flag-icon-jp">
                  <?php echo $japantxt; ?></option><?php } ?>
              </select>

            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" title="<?php echo $go_to_course; ?>"
              class="btn waves-effect waves-light btn-outline-danger btn-danger-hover float-right" name="action"
              id="action"><i class="mdi mdi-file-document-box"></i> <?php echo $go_to_course; ?></button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.SELECT LANGUAGE MODAL -->

  <div id="hint_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel"><?php echo $hinttxt; ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <img id="imgques_hintimg" class="card-img-top img-responsive mx-auto d-block" style="max-width: 300px;"
            src="<?php echo REAL_PATH; ?>/assets/images/mockup/img4.jpg"
            onerror="this.src='<?php echo REAL_PATH; ?>/images/logo.png';" alt="Card image cap">
          <hr>
          <h4 id="txtques_hintname"></h4>
          <p id="txtques_hintdetail"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn btn-outline-danger waves-effect"
            data-dismiss="modal"><?php echo $m_ok; ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.HINT MODAL -->

  <div class="modal fade bs-example-modal-lg" id="modal-viewdocument" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="myLargeModalLabel"><i class="mdi mdi-printer"></i> <?php echo $lesson_file; ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <!--
                  <div id="iframe_document"></div> -->
          <iframe id="iframe_document" style="width:100%; height:500px;" frameborder="0"></iframe>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="fil_id_downloadfile" id="fil_id_downloadfile">
          <input type="hidden" id="fil_path_downloadfile" name="fil_path_downloadfile">
          <button type="button" name="btn_downloadfile"
            class="btn btn-outline-info btn-flat float-left btn_downloadfile"><i class="mdi mdi-download"></i>
            <?php echo $download_file; ?></button>
          <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i
              class="mdi mdi-window-close"></i> <?php echo $close; ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
   <!-- VIDEO MODAL (scoped) -->
  <div class="modal fade" id="modal-viewvideo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-imat-video" role="document">
      <div class="modal-content">
        <button type="button" class="imat-close" data-dismiss="modal" aria-label="Close">
          <!-- ไอคอนปิด (SVG) -->
          <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
            <path d="M18.3 5.71L12 12.01 5.7 5.7 4.29 7.11 10.59 13.41 4.3 19.7 5.71 21.11 12 14.82 18.29 21.11 19.7 19.7 13.41 13.41 19.7 7.12z"/>
          </svg>
        </button>

        <div class="modal-body imat-body">
          <div id="video-loading"><?php echo $video_loading; ?></div>

          <!-- วิดีโอไฟล์ -->
          <div id="video_file_view">
            <!-- จะถูกเติม <video> ด้วยสคริปต์ -->
          </div>

          <!-- วิดีโอจาก URL (YouTube/Vimeo) -->
          <div id="video_url_view" class="imat-embed">
            <!-- จะถูกเติม <iframe> ด้วยสคริปต์ -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="surveyModal" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="max-width: 100% ">
          <h4 class="modal-title" id="myLargeModalLabel"><?php echo $survey_txt . ': '; ?><span
              id="txt_headsurvey"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <form enctype="multipart/form-data" id="survey_form" name="survey_form" autocomplete="off" method="POST"
          accept-charset="utf-8" class="form-horizontal p-t-20">
          <div class="modal-body" style="overflow-x: auto;">
            <h5 id="txt_infosurvey"></h5>
            <div id="survey_data">

            </div>
          </div>
          <input type="hidden" name="sv_id" id="sv_id">
          <input type="hidden" name="cosen_id" id="cosen_id" value="<?php echo $cosen_id; ?>">
          <div class="modal-footer" align="right">
            <button type="submit" class="btn waves-effect waves-light btn-outline-success btn_survey" name="action"
              id="action"><i class="mdi mdi-send"></i> <?php echo $sent_survey; ?></button>
            <button type="button" class="btn waves-effect waves-light btn-outline-danger" data-dismiss="modal"><i
                class="mdi mdi-window-close"></i> <?php echo $close; ?></button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.SURVEY MODAL -->

  <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-certificate" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="myLargeModalLabel"><i class="mdi mdi-certificate"></i> <?php echo label('dash_btn_certificate'); ?>
          </h4>
          <button type="button" class="close"
            onclick='window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";'
            data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body divEmbed">

          <iframe id="obj_pdf_cert" style="width:100%; height: 97vh;" frameborder="0">
          </iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger btn-flat"
            onclick='window.location.href = "<?php echo base_url() . 'coursemain/detail/' . $cos_id . '/' . $lang_select; ?>";'
            data-dismiss="modal"><?php echo label('close'); ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

  <script type="text/javascript">
  var isstatus = 1;
  <?php if ($arr_statuscos == "2") { ?>
  isstatus = 0;
  swal({
    title: '<?php echo $wg_datanotfound; ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  }).then(function() {
    window.location = "<?php echo REAL_PATH . "/coursemain/all_courses/" ?>";
  })
  <?php } else if ($arr_statuscos == "3") { ?>
  isstatus = 0;
  swal({
    title: '<?php echo $cos_expired; ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  }).then(function() {
    window.location = "<?php echo REAL_PATH . "/coursemain/all_courses/" ?>";
  })
  <?php } else if ($arr_statuscos == "4") { ?>
  isstatus = 0;
  swal({
    title: '<?php echo label('lrn_p_regis_sub'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  }).then(function() {
    window.location = "<?php echo REAL_PATH . "/coursemain/all_courses/" ?>";
  })
  <?php } else { ?>
  <?php if (isset($enroll) && countArray($enroll) > 0) { ?>
  <?php if ($enroll['cosen_status'] == "0") { ?> //Wait approve
  isstatus = 0;
  swal({
    title: '<?php echo $d_waitapprove; ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  })
  <?php } else if ($enroll['cosen_status'] == "3") { ?> //ผู้เรียนสำรอง
  isstatus = 0;
  swal({
    title: '<?php echo $regis_sub; ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  })
  <?php } ?>
  <?php } else { ?>
  isstatus = 0;
  swal({
    title: '<?php echo $r_notregister; ?>',
    text: "",
    type: 'warning',
    showCancelButton: false,
    confirmButtonClass: 'btn btn-primary',
    confirmButtonText: '<?php echo $m_ok; ?>'
  }).then(function() {
    window.location = "<?php echo REAL_PATH . "/coursemain/all_courses/" ?>";
  })
  <?php }
    } ?>
  </script>
  <script type="text/javascript">
  var base_url = "<?php echo REAL_PATH; ?>";
  </script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>
  <?php $this->load->view('frontend/java/course_detail_java.php'); ?>
  <script type="text/javascript">
  function onclickfirstquestion(tab) {

    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
  }
  </script>
  <script type="text/javascript">
  function play_scm(numb) {
    var link_scm = $('#link_scm' + numb).val();
    $('#scorm_play_iframe' + numb).attr('src', link_scm);
  }

  <?php if (isset($lesson_arr)) {
      if (countArray($lesson_arr) > 1) { ?>
  updateStatusLesson();
  <?php     } else if (countArray($lesson_arr) == 1) { ?>
  updateStatusLesson();
  <?php if ($lesson_arr[0]['les_type'] == "2") { ?>
  play_scm('1');
  <?php } ?>
  <?php     }
      if ($isScrom > 0) { ?>
  setInterval(function() {
    if (!document.getElementById("lessonheader").classList.contains("btn-danger")) {
      updateStatusLesson();
    }
  }, 10000)
  <?php     }
    } ?>
  document.addEventListener('contextmenu', event => event.preventDefault());
  $(document).keydown(function(event) {
    if (event.keyCode == 123) {
      return false;
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
      return false;
    }
  });
  $("footer").addClass("mt-5");

  function ongotab(les_id, lesson) {
    $('a[href="#' + les_id + '"]').tab('show');
    $('html,body').animate({
      scrollTop: $('#less_div').offset().top - 150
    }, 'fast');
    $('.tab_lesson').animate({
      scrollTop: 0
    }, 'fast');

    $.ajax({
      url: "<?= base_url() ?>index.php/updatedatacourse/update_lesson/" + lesson,
      method: 'POST',
      dataType: "json",
      data: {
        cosen_id: '<?php echo $cosen_id; ?>'
      },
      success: function(data) {}
    });

    updateStatusLesson();
  }

  function isset(ref) {
    return typeof ref !== 'undefined'
  }
  
  // ====== SELECTOR หลัก ======
  const $modal = $('#modal-viewvideo');
  const loadingPopup = document.getElementById('video-loading');

  // ====== หยุดสื่อทั้งหมดในโมดอล ======
  function stopAllMedia() {
    // หยุด HTML5 <video>
    $modal.find('video').each(function () {
      try { this.pause(); this.currentTime = 0; } catch (e) {}
      // เคลียร์ src เพื่อให้หยุดเด็ดขาดบนบางเบราว์เซอร์ (iOS)
      if (this.src) {
        this.removeAttribute('src');
        this.load();
      }
    });

    // หยุด YouTube iframe (ต้องมี enablejsapi=1 ใน src)
    $modal.find('iframe#video_youtube').each(function () {
      const win = this.contentWindow;
      if (win) {
        win.postMessage(JSON.stringify({
          event: 'command', func: 'stopVideo', args: []
        }), '*');
      }
      // กันพลาด: รีเซ็ต src สักชั่วคราว
      const old = this.getAttribute('src') || '';
      this.setAttribute('src', 'about:blank');
      // ถ้าจะกลับมาเล่นใหม่ onplayer_video_cos จะเติม iframe ใหม่ให้อยู่แล้ว
    });

    // ล้าง DOM แสดงผล (คุณสร้างใหม่ทุกครั้งตอนเปิดอยู่แล้ว)
    $('#video_file_view').empty().hide();
    $('#video_url_view').empty().hide();
    if (loadingPopup) loadingPopup.classList.add('hidden');
  }

  // ====== ปุ่มปิดของคุณ (.imat-close) ======
  $(document).on('click', '.imat-close', function () {
    stopAllMedia();
    $modal.modal('hide');
  });

  // ====== ถ้าปิดด้วยวิธีอื่น (ESC/Backdrop/ปุ่ม X ของ Bootstrap) ======
  $modal.on('hide.bs.modal hidden.bs.modal', stopAllMedia);

  // ====== ฟังก์ชันเดิมที่คุณมี: ปรับให้เรียก stopAllMedia ด้วย ======
  function onResetVideo() {
    stopAllMedia();
  }


  
  $(document).click(function(event) {
    //if you click on anything except the modal itself or the "open modal" link, close the modal
    if ($(event.target).closest(".show").length) {
      var video_upload = document.getElementById("video_upload_html5_api");
      if ($("#video_upload_html5_api").length > 0) {
        /*$('#video_file_view').html('');document.getElementById("video_upload_html5_api").pause();*/
      }
      var iframe = document.getElementById('video_youtube');
      if ($("#video_youtube").length > 0) {
        iframe.src = iframe.src;
      }
    }
  });

  function onResetVideo() {
    $('#video_file_view').html('');
    $('#video_url_view').html('');
    // location.reload();
  }

  function onplayer_video_cos(type, video, id) {
    // Show loading by default
    loadingPopup.classList.remove('hidden');
    $('#modal-viewvideo').modal({backdrop: false});
    if (type == "url") {
      document.getElementById('video_file_view').style.display = 'none';
      document.getElementById('video_url_view').style.display = '';

      $.ajax({
        url: "<?= base_url() ?>index.php/updatedatacourse/update_med",
        method: 'POST',
        data: {
          med_id: id,
          cosen_id: '<?php echo $cosen_id; ?>'
        },
        dataType: "json",
        success: function(data) {}
      });
      var res = video.substring(24);
      //onYouTubeIframeAPIReady(res);
      $('#video_url_view').html(
        '<iframe class="embed-responsive-item youtube-video" id="video_youtube" onclick="chk_youtubeonplay()" src="' +
        video + '" allowfullscreen></iframe>');
      updateStatusLesson();
      $('#status_medvideo' + id).val('1');
    } else {
      /*var token ;
      $.post( "<?php echo REAL_PATH ?>/setsession.php?filename="+video, function( data ) {
        $('#modal-viewvideo').on('hidden.bs.modal', function () {
            $('#video_file_view').html('<video id="video_upload" controls preload="none" controls controlsList="nodownload" data-setup="{}" style="width: 100%"><source src=""></video>');

          })*/
      var status_medvideo = $('#status_medvideo' + id).val();
      // console.log(token);
      document.getElementById('video_file_view').style.display = '';
      document.getElementById('video_url_view').style.display = 'none';
      $('#video_file_view').html(
        '<video id="video_upload" autoplay controls preload="metadata" controls controlsList="nodownload" onvolumechange="changeVolumeVideo(' +
        id + ')" data-setup="{}" style="width: 100%"><source src="<?php echo REAL_PATH . '/uploads/media/'; ?>' +
        video + '" type="video/mp4"></video>');
      var supposedCurrentTime = 0;
      var videoplay = document.getElementById("video_upload");
      document.getElementById('video_upload').play();
      

      // When video is ready to play
      videoplay.addEventListener('canplaythrough', function () {
        loadingPopup.classList.add('hidden');
      });

      // In case video fails to load
      videoplay.addEventListener('error', function () {
        loadingPopup.textContent = '<?php echo $video_cannot_loading; ?>';
      });

      $.ajax({
        url: "<?= base_url() ?>index.php/video/queryMedTc",
        method: "GET",
        data: {
          med_id: id,
          cosen_id: '<?php echo $cosen_id; ?>'
        },
        dataType: "json",
        success: function(dataVolume) {
          videoplay.volume = dataVolume.volume;
        }
      });

      $("#video_upload").on(
        "timeupdate",
        function(event) {
          let statusMedvideoNow = parseInt($('#status_medvideo' + id).val());
          const currentTime = parseFloat(this.currentTime);
          const duration = parseFloat(this.duration);
          const durationTenPercent = parseFloat(this.duration) - (parseFloat(duration) * 0.01);
          if (currentTime >= durationTenPercent && statusMedvideoNow != 1) {
            rechk_onclick(id);
            $('#status_medvideo' + id).val('1');
            supposedCurrentTime = 0;
            updateStatusLesson();
          }
        });

      videoplay.onended = function() {
        rechk_onclick(id);
        $('#status_medvideo' + id).val('1');
        supposedCurrentTime = 0;
        updateStatusLesson();
      };
      // });
      // });
      /*$('#video_upload_html5_api').html('');
      $.ajax({
            url:"<?= base_url() ?>index.php/querydata/runvideo",
            method:'POST',
            data:{video:video,id:id},
            success:function(data)
            {
              $('#video_file_view').html(data);
            }
      });*/
    }
  }

  function changeVolumeVideo(id) {
    const videoplay = document.getElementById("video_upload");
    $.ajax({
      url: "<?= base_url() ?>index.php/video/updateVolume",
      method: "POST",
      data: {
        medtc_volume: videoplay.volume,
        med_id: id,
        cosen_id: '<?php echo $cosen_id; ?>'
      },
      dataType: "json",
      success: function(dataVolume) {
        if (dataVolume.status == "0") {
          swal({
            title: '<?php echo label("com_msg_error_save"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          })
        }
      }
    });
  }
  $(document).on('click', '.survey_main', function(event) {
    event.preventDefault();
    var sv_id = $(this).attr("id");
    var status_tc = $(this).attr("status_tc");
    if (status_tc == "1") {
      $('.btn_survey').hide();
    } else {
      $('.btn_survey').show();
    }
    $("#surveyModal").modal({
      backdrop: false
    });
    $('#sv_id').val(sv_id);
    $.ajax({
      url: "<?= base_url() ?>index.php/querydata/update_survey_detail_data",
      method: "POST",
      data: {
        sv_id_update: sv_id,
        type: "real",
        lang_select: "<?php echo $lang_select; ?>",
        cosen_id: '<?php echo $cosen_id; ?>'
      },
      dataType: "json",
      success: function(data) {
        $('#txt_headsurvey').text(data.sv_title);
        $('#txt_infosurvey').text(data.sv_explanation);

      }
    });
    $.ajax({
      url: '<?= base_url() ?>index.php/querydata/survey_data',
      type: 'POST',
      data: {
        sv_id: sv_id,
        type: "real",
        lang_select: "<?php echo $lang_select; ?>",
        cosen_id: '<?php echo $cosen_id; ?>'
      },
      success: function(data_cg) {
        $('#survey_data').html(data_cg);
      }
    });
  });

  function rechk_onclick(id) {
    $.ajax({
      url: "<?= base_url() ?>index.php/updatedatacourse/update_med",
      method: "POST",
      data: {
        med_id: id,
        cosen_id: '<?php echo $cosen_id; ?>'
      },
      dataType: "json",
      success: function(data) {
        updateStatusLesson();
      }
    });
  }

  $(document).on('submit', '#survey_form', function(event) {
    event.preventDefault();
    var form = $('#survey_form')[0];
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
          url: "<?= base_url() ?>index.php/updatedatacourse/insert_survey_tc",
          method: 'POST',
          data: new FormData(form),
          contentType: false,
          processData: false,
          dataType: "json",
          success: function(data) {
            if (data.status == "1") {
              $('#survey_form')[0].reset();
              swal(
                '<?php echo $save_complete; ?>!',
                '',
                'success'
              ).then(function() {
                $('#surveyModal').modal('hide');
                location.reload();
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
                location.reload();
              })
            }

          }
        });
      }
    });
  });
  </script>

</body>

</html>
