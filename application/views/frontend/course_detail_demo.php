<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');
  $video_loading = isset($video_loading) && $video_loading !== '' ? $video_loading : 'Loading video...';

?>
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/course-detail-premium.css?v=20260731-28" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/course-demo-premium.css?v=20260731-04" rel="stylesheet">
    <style type="text/css">
      .disable {
         pointer-events: none;
         cursor: default;
      }
      /*.customvtab .tabs-vertical li .nav-link.active, .customvtab .tabs-vertical li .nav-link:hover, .customvtab .tabs-vertical li .nav-link:focus {
          border-right: 2px solid #000000;
          color: #000000;
      }*/
      .les_info img{
          width: 100%;
          height: auto;
      }
        .break-word {
          display: inline-block;
          word-break: break-word;
        }
              a,h3,h4,p{overflow-wrap: break-word;
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
              .btn{overflow-wrap: break-word;
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
              }.btn {
    white-space:normal !important; 
    word-wrap: break-word; 
    word-break: normal;
}

    </style>
</head>

<body class="fix-header fix-sidebar card-no-border premium-course-detail demo-course-detail">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper demo-course-page">

            <div class="container-fluid course-detail-container demo-course-shell">
                <nav class="course-detail-toolbar" aria-label="Course navigation">
                  <a class="course-detail-back" href="<?php echo $isDashboard == "1" ? REAL_PATH.'/dashboard' : REAL_PATH.'/managecourse/courses_all'; ?>">
                    <i class="mdi mdi-arrow-left"></i><span><?php echo ucwords(label('m_previous')); ?></span>
                  </a>
                  <span class="course-toolbar-divider" aria-hidden="true"></span>
                  <ol class="course-toolbar-trail">
                    <li><a href="<?php echo REAL_PATH; ?>/dashboard"><i class="mdi mdi-home-outline"></i><span><?php echo ucwords(label('dashboard')); ?></span></a></li>
                    <li><a href="<?php echo REAL_PATH; ?>/managecourse/courses_all"><?php echo $title_main != "" ? ucwords(strtolower($title_main)) : ucwords(label('my_course')); ?></a></li>
                    <li aria-current="page"><?php echo $course_main['cname']; ?></li>
                  </ol>
                </nav>
              <div class="row demo-admin-actions">
                <div class="card card-body">
                <div class="row">
                    <div class="col-md-4" align="left">
                      <?php 
                        $pageback = REAL_PATH.'/managecourse/courses_all';

                        if($isDashboard=="1"){
                            $pageback = REAL_PATH.'/dashboard';
                        }
                      ?>
                      <button class="btn btn-outline-info " onclick="window.location.href='<?php echo $pageback; ?>'"><i class="mdi mdi-keyboard-return"></i> <?php echo ucwords(label('m_previous')); ?></button>
                    </div>
                    <div class="col-md-8" align="right">

                <?php if($course_main['cos_approve']=="0"){ ?>
                    <?php if($course_main['cos_public']=="0"){ 
                            if($is_public=="1"&&$course_main['cos_status']=="1"){
                    ?>
                      <button type="button" class="btn waves-effect waves-light btn-outline-success float-right enable_course"
                              data-require-survey="<?php echo $course_main['is_survey_required']; ?>"
                              data-has-survey="<?php echo count($survey_arr); ?>"
                              name="enable_course" id="<?php echo $course_main['cos_id']; ?>"><i class="mdi mdi-check"></i> <?php echo label('enable_course'); ?></button>
                    <?php   }else{ ?>
                      <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i> <?php echo label('d_waitcreate'); ?></span></h4>
                    <?php   }
                          }else{ 
                            if($isApprove=="1"&&$course_main['cos_status']=="1"){
                    ?>
                      <button type="button" id="<?php echo $cos_id; ?>" class="btn mdi-btn waves-effect waves-light btn-secondary active approve_cos" title="<?php echo label('d_approve'); ?>" style="-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;">
                            <span class="icon is-medium"><i class="mdi mdi-24px mdi-alert text-warning mdi-light"></i> <?php echo label('d_waitapprove'); ?></span>
                      </button>
                      <?php }else{ ?>
                      <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i> <?php echo label('wait_approve'); ?></span></h4>
                      <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                      <h4><span id="txt_approve"><i class="mdi mdi-check-circle-outline"></i> <?php echo label('d_approved'); ?></span></h4>
                <?php } ?>
                    </div>
                </div>
                </div>
              </div>
                <?php 
                if($lang_select==""){
                  $lang_select = $lang;
                }
                if($lang_select=="thai"){
                  $createBy = "สร้างโดย";
                  $lesson_file = "เอกสารประกอบการเรียน";
                  $survey_txt = "แบบสำรวจ";
                  $pointtxt = "คะแนน";
                  $lessontxt = "บทเรียน";
                  $preNo = "ข้อที่";
                  $summarytxt = "คำอธิบาย";
                  $close = "ปิด";
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
                  $wg_datanotfound = "ไม่พบข้อมูล";
                  $cos_expired = "หลักสูตรนี้หมดอายุแล้ว";
                  $qiz_not_complete = "กรุณากดบันทึกคำตอบก่อนทำการยืนยันแบบทดสอบ";
                  $save_complete = "ส่งคำตอบสำเร็จ";
                  $confirm_submit_quiz = "คุณแน่ใจที่จะส่งคำตอบหรือไม่ ?";
                  $noti_clicksave = "กรุณาตอบคำถาม";
                  $answer_wrong = "คุณตอบผิด กรุณาตอบอีกครั้ง";
                  $chk_answer_label = 'ตรวจคำตอบ';
                  $preExam_label = 'แบบทดสอบก่อนเรียน';
                  $finalExam_label = 'แบบทดสอบหลังเรียน';
                  $condition_msg = 'ท่านจะสามารถเรียนหลักสูตรนี้ได้ เมื่อท่านผ่านหลักสูตรตามที่กำหนด <br>&#34;_coursename_&#34;';
                }else if($lang_select=="english"){
                  $createBy = "Created by";
                  $lesson_file = "Course Material";
                  $survey_txt = "Survey";
                  $pointtxt = "Score";
                  $lessontxt = "Lesson";
                  $preNo = "No.";
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
                  $wg_datanotfound = "Information not found";
                  $cos_expired = "This course has expired";
                  $qiz_not_complete = "Please save the answer before confirming test";
                  $save_complete = "Submitted successful";
                  $confirm_submit_quiz = "Are you sure you want to submit ?";
                  $noti_clicksave = "Please answer the questions.";
                  $answer_wrong = "Your answer is wrong, Please try again.";
                  $chk_answer_label = 'Review answer';
                  $preExam_label = 'Pre-test';
                  $finalExam_label = 'Post-test';
                  $condition_msg = 'You will be eligible for this course after you have completed a prerequisite<br>&#34;_coursename_&#34;';
                }else{
                  $createBy = "作成者：";
                  $lesson_file = "学習資料";
                  $survey_txt = "アンケート";
                  $pointtxt = "点数";
                  $lessontxt = "レッスン";
                  $preNo = "番号";
                  $summarytxt = "説明";
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
                  $wg_datanotfound = "情報がありません";
                  $cos_expired = "このｺｰｽは終了しました";
                  $qiz_not_complete = "ﾃｽﾄをｺﾝﾌｧｰﾑする前に回答を保存して下さい。";
                  $save_complete = "提出完了";
                  $confirm_submit_quiz = "本当に提出しますか？";
                  $noti_clicksave = "質問に回答してください。";
                  $answer_wrong = "回答が間違っています。再度回答して下さい。";
                  $chk_answer_label = '回答ﾚﾋﾞｭｰ';
                  $preExam_label = '事前テスト';
                  $finalExam_label = '事後テスト';
                  $condition_msg = 'このコースを受けることにあたり、以下のコースを完了しなければなりません<br>&#34;_coursename_&#34;';
                }
                ?>
                <section class="demo-course-hero">
                <div class="row course-detail-overview demo-course-overview">
                  <div class="col-auto col-md-12 col-lg-4 mb-0 card card-body course-detail-cover demo-course-cover">
                    <div class="course-cover-card">
                      <div class="course-cover-card__glow" aria-hidden="true"></div>
                      <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $course_main['cos_pic']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/images/cover_course.jpg';" alt="<?php echo htmlspecialchars($course_main['cname'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                  </div>

                  <div class="col-md-12 col-lg-8 mb-0 card card-body course-detail-info demo-course-info">
                    <div class="course-hero-labels">
                      <span class="course-detail-eyebrow"><i class="mdi mdi-book-open-page-variant"></i> ISUZU E-LEARNING</span>
                      <span class="course-hero-format"><i class="mdi mdi-eye-outline"></i><?php echo $lang_select == 'thai' ? 'ตัวอย่างหลักสูตร' : ($lang_select == 'japan' ? 'コースプレビュー' : 'Course preview'); ?></span>
                    </div>
                    <h1><?php echo $course_main['cname']; ?></h1>
                    <p class="course-hero-summary"><?php echo $lang_select == 'thai' ? 'ดูโครงสร้าง เนื้อหา และลำดับการเรียนรู้ก่อนเปิดใช้งานหลักสูตร' : ($lang_select == 'japan' ? '公開前にコースの内容と学習順序を確認できます。' : 'Preview the course content and learning sequence before publishing.'); ?></p>
                    <div class="course-detail-meta-bar">
                      <span><i class="mdi mdi-calendar-clock"></i><small><?php echo $periodtxt; ?></small><strong><?php echo $course_main['txt_period_course']; ?></strong></span>
                      <span><i class="mdi mdi-domain"></i><small><?php echo $createBy; ?></small><strong><?php echo $course_main['com_name']; ?></strong></span>
                      <span><i class="mdi mdi-format-list-numbers"></i><small><?php echo $lang_select == 'thai' ? 'เนื้อหาทั้งหมด' : 'Learning items'; ?></small><strong id="courseHeroItemCount">—</strong></span>
                      <span><i class="mdi mdi-eye-outline"></i><small><?php echo $lang_select == 'thai' ? 'สถานะ' : 'Mode'; ?></small><strong><?php echo $lang_select == 'thai' ? 'ตัวอย่าง' : ($lang_select == 'japan' ? 'プレビュー' : 'Preview'); ?></strong></span>
                    </div>
                    <div class="d-block position-relative">

                        <div class="row">
                          <div class="col-lg-8 col-sm-12 mt-3">
                            <!-- FOR DESKTOP -->
                            <small class="text-muted text-truncate position-absolute col-md-12 col-lg-8 p-0 hidden-xs-down" style="bottom: 0;"><?php echo $createBy.': '.$course_main['com_name']; ?></small>

                            <!-- FOR MOBILE -->
                            <small class="text-muted text-truncate hidden-sm-up"><?php echo $createBy.': '.$course_main['com_name']; ?></small>
                          </div>
                          <div class="col-lg-4 col-sm-12">
                            <?php if(countArray($document_cos)>0){ ?>

                              <a type="button" title="<?php echo $lesson_file; ?>" href="#" class="btn btn-block waves-effect waves-light btn-secondary float-right dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-file-document"></i><?php echo ' '.$lesson_file; ?></a>
                              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <?php foreach ($document_cos as $key_doccos => $value_doccos) { 
                                  
                                      if($lang_select=="thai"){ 
                                        $name_file = $value_doccos['name_file_th']!=""?$value_doccos['name_file_th']:$value_doccos['name_file_eng'];
                                        $name_file = $name_file!=""?$name_file:$value_doccos['name_file_jp'];
                                      }else if($lang_select=="english"){ 
                                        $name_file = $value_doccos['name_file_eng']!=""?$value_doccos['name_file_eng']:$value_doccos['name_file_th'];
                                        $name_file = $name_file!=""?$name_file:$value_doccos['name_file_jp'];
                                      }else{
                                        $name_file = $value_doccos['name_file_jp']!=""?$value_doccos['name_file_jp']:$value_doccos['name_file_eng'];
                                        $name_file = $name_file!=""?$name_file:$value_doccos['name_file_th'];
                                      }
                                ?>
                                  <button class="dropdown-item view_doccos" typevalue="course_filedemo" id="<?php echo $value_doccos['fil_cos_id']; ?>"  path="<?php echo $value_doccos['path_file']; ?>"><?php echo $name_file; ?></button>
                                <?php } ?>
                              </div>
                            <?php } ?>
                          </div>
                        </div>

                    </div>
                  </div>
                </div>
                </section>

            </div>
            <div class="course-workspace-grid">
            <section class="course-detail-description-panel">
              <header>
                <i class="mdi mdi-clipboard-text course-section-icon" aria-hidden="true"></i>
                <span>ABOUT THIS COURSE</span>
                <h2><?php echo $lang_select == 'thai' ? 'รายละเอียดหลักสูตร' : ($lang_select == 'japan' ? 'コース概要' : 'Course overview'); ?></h2>
              </header>
              <div class="course-detail-description">
                <?php echo str_replace('../uploads/texteditor/', base_url().'/uploads/texteditor/', $course_main['cdetail']); ?>
              </div>
            </section>

            <section class="course-learning-path demo-learning-workspace">
              <header class="course-learning-path__header">
                <div class="course-path-heading">
                  <span class="course-path-heading__icon"><i class="mdi mdi-format-list-numbers" aria-hidden="true"></i></span>
                  <div>
                    <span>COURSE JOURNEY</span>
                    <h2><?php echo $lang_select == 'thai' ? 'เส้นทางการเรียนรู้' : ($lang_select == 'japan' ? '学習パス' : 'Learning path'); ?></h2>
                    <small><?php echo $lang_select == 'thai' ? 'ดูตัวอย่างเนื้อหาและลำดับการเรียนรู้' : ($lang_select == 'japan' ? 'コース内容と学習順序をプレビューします' : 'Preview the course content and learning sequence'); ?></small>
                  </div>
                </div>
                <div class="course-progress-ring" id="courseProgressRing" style="--course-progress:0">
                  <div><strong id="courseProgressText">0%</strong><span><?php echo $lang_select == 'thai' ? 'ความคืบหน้า' : ($lang_select == 'japan' ? '進捗' : 'Progress'); ?></span></div>
                </div>
              </header>
              <div class="course-path-summary" aria-live="polite">
                <div><i class="mdi mdi-format-list-numbers"></i><span><?php echo $lang_select == 'thai' ? 'ทั้งหมด' : ($lang_select == 'japan' ? '合計' : 'Total'); ?><strong id="coursePathTotal">0</strong></span></div>
                <div><i class="mdi mdi-checkbox-marked-circle-outline"></i><span><?php echo $lang_select == 'thai' ? 'สำเร็จแล้ว' : ($lang_select == 'japan' ? '完了' : 'Completed'); ?><strong>0</strong></span></div>
                <div><i class="mdi mdi-eye-outline"></i><span><?php echo $lang_select == 'thai' ? 'โหมด' : ($lang_select == 'japan' ? 'モード' : 'Mode'); ?><strong><?php echo $lang_select == 'thai' ? 'ตัวอย่าง' : ($lang_select == 'japan' ? 'プレビュー' : 'Preview'); ?></strong></span></div>
              </div>
              <div class="course-learning-progress" aria-hidden="true"><span id="courseProgressBar"></span></div>
              <div class="course-learning-steps demo-learning-list">
                <?php $this->load->view('frontend/tab/course_demo.php'); ?>

            <?php if(countArray($survey_arr)>0){
                      foreach ($survey_arr as $key_survey => $value_survey) {

                  if($lang_select=="thai"){ 
                    $sv_title = $value_survey['sv_title_th']!=""?$value_survey['sv_title_th']:$value_survey['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value_survey['sv_title_jp'];
                  }else if($lang_select=="english"){ 
                    $sv_title = $value_survey['sv_title_eng']!=""?$value_survey['sv_title_eng']:$value_survey['sv_title_th'];
                    $sv_title = $sv_title!=""?$sv_title:$value_survey['sv_title_jp'];
                  }else{
                    $sv_title = $value_survey['sv_title_jp']!=""?$value_survey['sv_title_jp']:$value_survey['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value_survey['sv_title_th'];
                  }
            ?>
            <div class="container-fluid p-0 mb-3">
              <a href="" id="<?php echo $value_survey['sv_id']; ?>" status_tc="<?php echo $value_survey['status_tc']; ?>" <?php if($value_survey['status_tc']!="1"){ ?>style="background-color:#95a5a6;border-color:#95a5a6;color: #ecf0f1;"<?php } ?> class="btn btn-block <?php if($value_survey['status_tc']=="1"){ ?>imat-red-bg btn-danger<?php } ?> text-left <?php if($loop_run==1){echo "survey_main";}else{echo "disable";} ?>  break-word" type="button" >
                <?php if($value_survey['status_tc']=="1"){ ?><i class="fa fas fa-check mr-2"></i><?php } ?> <?php echo $survey_txt.': '.$sv_title; ?>
              </a>
            </div>
          <?php         
                      }
                  } ?> 
              </div>
            </section>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
      var path = document.querySelector('.course-learning-path');
      if (!path) return;

      var steps = path.querySelectorAll(
        '.demo-learning-list > .container-fluid > a.btn-block, ' +
        '.demo-learning-list > form > .container-fluid > a.btn-block'
      );
      var total = steps.length;
      var totalText = document.getElementById('coursePathTotal');
      var heroCount = document.getElementById('courseHeroItemCount');
      if (totalText) totalText.textContent = total;
      if (heroCount) heroCount.textContent = total + ' <?php echo $lang_select == 'thai' ? 'รายการ' : ($lang_select == 'japan' ? '項目' : 'items'); ?>';

      for (var i = 0; i < total; i += 1) {
        var step = steps[i];
        var shell = step.parentElement;
        var label = (step.textContent || '').replace(/\s+/g, ' ').trim();
        var kind = 'lesson';
        var kindLabel = '<?php echo $lang_select == 'thai' ? 'บทเรียน' : ($lang_select == 'japan' ? 'レッスン' : 'Lesson'); ?>';
        var kindIcon = 'mdi-play-circle-outline';

        if (/แบบทดสอบ|test|quiz|exam|テスト/i.test(label)) {
          kind = 'quiz';
          kindLabel = '<?php echo $lang_select == 'thai' ? 'แบบทดสอบ' : ($lang_select == 'japan' ? 'テスト' : 'Assessment'); ?>';
          kindIcon = 'mdi-clipboard-text';
        } else if (/แบบสำรวจ|survey|アンケート/i.test(label)) {
          kind = 'survey';
          kindLabel = '<?php echo $lang_select == 'thai' ? 'แบบสำรวจ' : ($lang_select == 'japan' ? 'アンケート' : 'Survey'); ?>';
          kindIcon = 'mdi-file-document';
        }

        step.classList.add('onclickrechk', 'step-kind-' + kind);
        step.classList.remove('rounded-0', 'disable');
        step.style.backgroundColor = '';
        step.style.borderColor = '';
        step.style.color = '';
        step.style.pointerEvents = '';
        step.setAttribute('data-statustc', '0');
        if (shell) shell.classList.add('learning-step-shell');
        if (i === 0) step.classList.add('timeline-first', 'is-current');
        if (i === total - 1) step.classList.add('timeline-last');

        var index = document.createElement('span');
        index.className = 'learning-step-index';
        index.textContent = i + 1;
        step.insertBefore(index, step.firstChild);

        var typeBadge = document.createElement('span');
        typeBadge.className = 'learning-step-kind';
        typeBadge.innerHTML = '<i class="mdi ' + kindIcon + '"></i><em>' + kindLabel + '</em>';
        step.appendChild(typeBadge);

        var status = document.createElement('span');
        status.className = 'learning-step-status';
        status.innerHTML = i === 0
          ? '<i class="mdi mdi-play"></i><em><?php echo $lang_select == 'thai' ? 'เริ่มดู' : ($lang_select == 'japan' ? '表示' : 'Preview'); ?></em>'
          : '<i class="mdi mdi-eye-outline"></i><em><?php echo $lang_select == 'thai' ? 'ดูตัวอย่าง' : ($lang_select == 'japan' ? 'プレビュー' : 'Preview'); ?></em>';
        step.appendChild(status);
      }
    });
    </script>

    <!-- SELECT LANGUAGE MODAL -->
    <div id="select_lang_modal" class="modal course-entry-modal-wrap" role="dialog" aria-labelledby="courseEntryTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered course-entry-dialog">
        <div class="modal-content course-entry-modal">
          <div class="modal-header">
            <div class="course-entry-brand"><span>ISUZU</span><small>E-LEARNING</small></div>
            <button type="button" class="close" onclick="window.location.replace('<?php echo REAL_PATH;?>/managecourse/courses_all');">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form  enctype="multipart/form-data" id="lang_form" name="lang_form" autocomplete="off" method="POST" accept-charset="utf-8" class="form-horizontal p-t-20">
            <input type="hidden"
              name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="course-entry-heading">
              <span class="course-entry-kicker"><?php echo $lang_select == 'thai' ? 'พร้อมเริ่มการเรียนรู้' : ($lang_select == 'japan' ? '学習を始める準備ができました' : 'READY TO LEARN'); ?></span>
              <h2 id="courseEntryTitle" data-toggle="tooltip" title="<?php echo $course_main['cname']; ?>"><?php echo $course_main['cname']; ?></h2>
            </div>
          <div class="modal-body course-entry-body">
            <div class="course-entry-cover">
              <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $course_main['cos_pic']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/images/cover_course.jpg';" alt="Card image cap">
            </div>
            <div class="course-entry-options">
              <div class="course-entry-meta">
                <i class="mdi mdi-calendar-clock"></i>
                <span><small><?php echo $periodtxt; ?></small><strong><?php echo $course_main['txt_period_course']; ?></strong></span>
              </div>
              <label for="course_lang"><?php echo $Chooselangtxt; ?></label>

              <select id="course_lang" name="course_lang" class="selectpicker">
               <?php if($course_main['isTH']=="1"){ ?><option value="thai" <?php if($course_main['select_lang']=="thai"){ echo "selected"; } ?> data-icon="flag-icon flag-icon-th"><?php echo $thailandtxt; ?></option><?php } ?>
               <?php if($course_main['isENG']=="1"){ ?><option value="english" <?php if($course_main['select_lang']=="english"){ echo "selected"; } ?> data-icon="flag-icon flag-icon-us"><?php echo $englishtxt; ?></option><?php } ?>
               <?php if($course_main['isJP']=="1"){ ?><option value="japan" <?php if($course_main['select_lang']=="japan"){ echo "selected"; } ?> data-icon="flag-icon flag-icon-jp"><?php echo $japantxt; ?></option><?php } ?>
              </select>

            </div>

          </div>
          <div class="modal-footer course-entry-footer">
            <button type="submit" title="<?php echo $go_to_course; ?>" class="btn waves-effect waves-light course-entry-submit" name="action" id="action"><span><?php echo $go_to_course; ?></span><i class="mdi mdi-arrow-right"></i></button>
          </div>
        </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.SELECT LANGUAGE MODAL -->

    <div id="hint_modal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel"><?php echo $hinttxt; ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            <img id="imgques_hintimg" class="card-img-top img-responsive mx-auto d-block" style="max-width: 300px;" src="<?php echo REAL_PATH;?>/assets/images/mockup/img4.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
            <hr>
            <h4 id="txtques_hintname"></h4>
            <p id="txtques_hintdetail"></p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn btn-outline-danger waves-effect" data-dismiss="modal"><?php echo $m_ok; ?></button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.HINT MODAL -->

    <div class="modal bs-example-modal-lg" id="modal-viewdocument" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="mdi mdi-printer"></i> <?php echo $lesson_file; ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body"><!--
                  <div id="iframe_document"></div> -->
                  <iframe id="iframe_document" style="width:100%; height:500px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="fil_id_downloadfile" id="fil_id_downloadfile">
                    <input type="hidden" id="fil_path_downloadfile" name="fil_path_downloadfile">
                    <button type="button" name="btn_downloadfile" class="btn btn-outline-info btn-flat float-left btn_downloadfile"><i class="mdi mdi-download"></i> <?php echo $download_file; ?></button>
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo $close; ?></button>
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
    <!-- /.modal -->
    <div id="surveyModal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header break-word" style="max-width: 100% ">
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo $survey_txt.': '; ?><span id="txt_headsurvey" class="break-word"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form  enctype="multipart/form-data" id="survey_form" name="survey_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                <div class="modal-body" style="">
                    <h5 id="txt_infosurvey"></h5>
                    <div id="survey_data">
                      
                    </div>
                </div>
                <input type="hidden" name="sv_id" id="sv_id">
                <div class="modal-footer" align="right">
                    <button type="submit" class="btn waves-effect waves-light btn-outline-success btn_survey" name="action" id="action"><i class="mdi mdi-send"></i> <?php echo $sent_survey; ?></button>
                    <button type="button" class="btn waves-effect waves-light btn-outline-danger" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo $close; ?></button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.SURVEY MODAL -->


    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

      <div id="myModal_process" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body" align="center">
                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('please_wait'); ?></h3>
              </div>
            </div>
        </div>
      </div>
                            <script type="text/javascript">
                              function onclickfirstquestion(tab){

                                $('.nav-tabs a[href="#'+tab+'"]').tab('show');
                              }
                            </script>
    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">

      document.addEventListener('contextmenu', event => event.preventDefault());
      $(document).keydown(function(event){
          if(event.keyCode==123){
              return false;
          }
          else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
                   return false;
          }
      });

      $( "footer" ).addClass( "mt-5" );
        /*window.onclick = function(event) {
           if (event.target.id != "modal-viewvideo") {
              $("#video_url_view").html('');
              $("#video_file_view").html('');

           }
        }*/
            function showVideoLoading() {
              $('#video-loading').show();
            }
            function hideVideoLoading() {
              $('#video-loading').hide();
            }
            function bindVideoReadyEvents() {
              $('#video_upload').on('loadeddata canplay playing', function() {
                hideVideoLoading();
              });
              $('#video_youtube').on('load', function() {
                hideVideoLoading();
              });
            }
            function onResetVideo(){
              $('#video_file_view').html('');
              $('#video_url_view').html('');
              showVideoLoading();
            }
            function inArray(needle, haystack) {
                var length = haystack.length;
                for(var i = 0; i < length; i++) {
                    if(haystack[i] == needle) return true;
                }
                return false;
            }
          function createButton(text,classs,style,id, cb) {
            return $(' <button class="'+classs+'" style="'+style+'" id="'+id+'">' + text + '</button>').on('click', cb);
          }
          
                function ongotab(les_id){
                  $('a[href="#' + les_id + '"]').tab('show');
                  $('html,body').animate({scrollTop: $('#less_div').offset().top  - 150},'fast');
                  $('.tab_lesson').animate({scrollTop: 0},'fast');
                }
        function play_scm(numb){
            var link_scm = $('#link_scm'+numb).val();
            $('#scorm_play_iframe' + numb).attr('src',link_scm);
        }
        $(document).click(function(event) {
          //if you click on anything except the modal itself or the "open modal" link, close the modal
          if ($(event.target).closest(".modal,.js-open-modal").length) {
              var video_upload = document.getElementById("video_upload_html5_api");
              if($("#video_upload_html5_api").length > 0){document.getElementById("video_upload_html5_api").pause();}
              var iframe = document.getElementById('video_youtube');
              if($("#video_youtube").length > 0){ iframe.src = iframe.src; }
          }
        });
        function click_next(id,ques_id,qiz_id,type){
              var ques_type = $('#ques_type_'+type+'_'+ques_id).val();
              var ques_score = $('#ques_score_'+type+'_'+ques_id).val();
              var quiz_model = $('#quiz_model_'+type+'_'+qiz_id).val();
              var quiz_ishint = $('#quiz_ishint_'+type+'_'+qiz_id).val();
              var tc_save = $('#tc_save_'+type+'_'+ques_id).val();
              var mul_answer = $('#mul_answer_'+type+'_'+ques_id).val();
							
              var answer = "";
              var chk_answer = 1;
              if(ques_type=="multi"||ques_type=="2choice"){
                var radioValue = $('input[name=multi_choice_group_'+type+'_'+ques_id+']:checked').val(); 
                if(radioValue&&quiz_model=="1"){
                  answer = radioValue;
                  var res = mul_answer.split(",");
                  if (!inArray(answer, res)) {
                      chk_answer = 0;
                      if(quiz_ishint=="1"){
                        var ques_hintname = $('#ques_hintname_'+type+'_'+ques_id).val();
                        var ques_hintdetail = $('#ques_hintdetail_'+type+'_'+ques_id).val();
                        var ques_hintimg = $('#ques_hintimg_'+type+'_'+ques_id).val();
                        $('#hint_modal').modal('show');
                        $('#txtques_hintname').text(ques_hintname);
                        $('#txtques_hintdetail').text(ques_hintdetail);
                        $("#imgques_hintimg").attr("src","<?php echo REAL_PATH.'/uploads/hint/' ?>"+ques_hintimg);
                      }else{
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
              }else{
                var txtValue = $('#txt_answer_'+type+'_'+ques_id).val();
                if(txtValue!=""){
                  answer = txtValue;
                }else{
                  answer = "";
                }
              }
              var answer_ques = $('#tc_answer_'+type+ques_id).val();
              if(quiz_model=="1"){
                if(answer_ques!=""){
                  if(chk_answer==1){
                      $('a[href="#' + id + '"]').tab('show');
                  }
                }else{
                    swal({
                        title: '<?php echo $noti_clicksave; ?>',
                        text: "",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: '<?php echo $m_ok; ?>'
                    })
                }
              }else{
                if(chk_answer==1){
                    $('a[href="#' + id + '"]').tab('show');
                }
              }
              
            }
             $(document).on('click', '.btn_send', function(event){
                event.preventDefault();
                var qiz_id = $(this).attr("id");
                var ques_id = $(this).attr("ques_id");
                var type = $(this).attr("typeval");
                
                var amount_ques = parseInt($('#amount_ques_'+type+'_'+qiz_id).val());
                var qiztc_id = $('#qiztc_id_'+type+'_'+qiz_id).val();
                var numloopchk = 1;
                var numchk = 1;
                var pagenumber = 0;
                var formdata = $('#'+type+'_form'+qiz_id).serialize();
                var arr = $('input[name="tc_answer_'+type+qiz_id+'[]"]').map(function () {
                  if(this.value!=""){
                    numloopchk++;
                    return this.value; // $(this).val()
                  }else{
                    if(numchk==1){
                    pagenumber = numloopchk;
                    numchk++;
                    }
                  }
                }).get();
                if(pagenumber!=0){
                    swal({
                        title: '<?php echo $noti_clicksave; ?>',
                        text: "",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: '<?php echo $m_ok; ?>'
                    }).then(function () {
                        $('.nav-tabs a[href="#quiz_'+qiz_id+'_'+pagenumber+'"]').tab('show');
                    })
                }else{
                    var ques_type = $('#ques_type_'+type+'_'+ques_id).val();
                    var ques_score = $('#ques_score_'+type+'_'+ques_id).val();
                    var quiz_model = $('#quiz_model_'+type+'_'+qiz_id).val();
                    var quiz_ishint = $('#quiz_ishint_'+type+'_'+qiz_id).val();
                    var tc_save = $('#tc_save_'+type+'_'+ques_id).val();
                    var mul_answer = $('#mul_answer_'+type+'_'+ques_id).val();

                    var answer = "";
                    var chk_answer = 1;
                    if(ques_type=="multi"||ques_type=="2choice"){
                      var radioValue = $('input[name=multi_choice_group_'+type+'_'+ques_id+']:checked').val(); 
                      if(radioValue&&quiz_model=="1"){
                        answer = radioValue;
                        var res = mul_answer.split(",");
                        if (!inArray(answer, res)) {
                            chk_answer = 0;
                            if(quiz_ishint=="1"){
                              var ques_hintname = $('#ques_hintname_'+type+'_'+ques_id).val();
                              var ques_hintdetail = $('#ques_hintdetail_'+type+'_'+ques_id).val();
                              var ques_hintimg = $('#ques_hintimg_'+type+'_'+ques_id).val();
                              $('#hint_modal').modal('show');
                              $('#txtques_hintname').text(ques_hintname);
                              $('#txtques_hintdetail').text(ques_hintdetail);
                              $("#imgques_hintimg").attr("src","<?php echo REAL_PATH.'/uploads/hint/' ?>"+ques_hintimg);
                            }else{
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
                    }else{
                      var txtValue = $('#txt_answer_'+type+'_'+ques_id).val();
                      if(txtValue!=""){
                        answer = txtValue;
                      }else{
                        answer = "";
                      }
                    }
                    if(chk_answer==1){
                                    swal({
                                        title: '<?php echo $save_complete; ?>!',
                                        text: "",
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo $m_ok; ?>'
                                    }).then(function () {
                                      $(".text_answer").css({"background-color": "#e8fdeb", "color": "#06d79c"});

                                        //location.reload();
                                    })
                    }
                }
            });
            function onselectVal(type,ques_id,value){
              $('#tc_answer_'+type+ques_id).val(value);
            }
        function click_previous(id){
            $('a[href="#' + id + '"]').tab('show');
        }
        function click_save(ques_id,qiz_id,type){
              var ques_type = $('#ques_type_'+type+'_'+ques_id).val();
              var ques_score = $('#ques_score_'+type+'_'+ques_id).val();
              var quiz_model = $('#quiz_model_'+type+'_'+qiz_id).val();
              var mul_answer = $('#mul_answer_'+type+'_'+ques_id).val();
              var answer = "";
              var tc_score = 0;

              var formdata = $('#'+type+'_form'+qiz_id).serialize();

              
              $('#tc_save_'+type+'_'+ques_id).val('1');
              if(ques_type=="multi"||ques_type=="2choice"){
                var radioValue = $('input[name=multi_choice_group_'+type+'_'+ques_id+']:checked').val(); 
                if(radioValue){
                  answer = radioValue;
                  var res = mul_answer.split(",");
                  if (inArray(answer, res)) {
                    tc_score = ques_score;
                  }
                }else{
                  answer = "";
                }
              }else{
                var txtValue = $('#txt_answer_'+type+'_'+ques_id).val();
                if(txtValue!=""){
                  answer = txtValue;
                  tc_score = ques_score;
                }else{
                  answer = "";
                }
              }
                            $('#tc_save'+ques_id).val(1);
                            swal({
                                title: '<?php echo $com_msg_success; ?>!',
                                text: "",
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo $m_ok; ?>'
                            })
            }

        $(document).on('click', '.enable_course', function(event){
            event.preventDefault();
            var cos_id = $(this).attr("id");
            var is_survey_required = $(this).data("require-survey");
            var has_survey = $(this).data("has-survey");
            
            // ✳️ ถ้าบังคับทำ survey แต่ยังไม่มี survey → ห้ามส่งอนุมัติ
            if(is_survey_required == 1 && has_survey == 0){
              swal({
                title: "<?php echo label('require_survey_warning_title'); ?>",
                text: "<?php echo label('require_survey_warning_msg'); ?>",
                type: "error",
                confirmButtonText: "<?php echo label('ok'); ?>"
              });
              return; // หยุดไม่ให้ดำเนินการต่อ
            }
            
            swal({
                title: '<?php echo label('enablecourse_is'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",  
                confirmButtonText: '<?php echo label('yes'); ?>',
                cancelButtonText: '<?php echo label('no'); ?>'
            }).then(function (isChk) {
                  if(isChk.value){
                    $(document.body).css('pointer-events', 'none');
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/public_course",
                      method:"POST",
                      data:{cos_id:cos_id},
                      success:function(data)
                      {
                          $(document.body).css('pointer-events', '');
                          location.reload();
                      }
                    });
                  }
                });
          });
        $(document).on('click', '.survey_main', function(event){
            event.preventDefault();
            var sv_id = $(this).attr("id");
            var status_tc = $(this).attr("status_tc");
            if(status_tc=="1"){
              $('.btn_survey').hide();
            }else{
              $('.btn_survey').show();
            }
            $("#surveyModal").modal({backdrop: false});
            $('#sv_id').val(sv_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/querydata/update_survey_detail_data",
                  method:"POST",
                  data:{sv_id_update:sv_id,type:"demo",lang_select:"<?php echo $lang_select; ?>"},
                  dataType:"json",
                  success:function(data)
                  {
                      $('#txt_headsurvey').text(data.sv_title);
                      $('#txt_infosurvey').text(data.sv_explanation);
                  }
                });
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/survey_data',
                      type: 'POST',
                      data:{sv_id:sv_id,type:"demo",lang_select:"<?php echo $lang_select; ?>"},
                      success: function(data_cg){
                        $('#survey_data').html(data_cg);
                      }
                });
          });
        $(document).on('submit', '#survey_form', function(event){
              event.preventDefault(); 
              swal(
                            '<?php echo $com_msg_success; ?>!',
                            '',
                            'success'
                        )
                
            });

        $(document).on('click', '.btn_downloadfile', function(event){
            event.preventDefault();
            var id = $('#fil_id_downloadfile').val();
            var path = $('#fil_path_downloadfile').val();
            window.location.href = "<?php echo base_url().'/uploads/document/' ?>"+path;
          });

        $(document).on('click', '.view_doccos', function(event){
            event.preventDefault();
            var id = $(this).attr("id");
            var typevalue = $(this).attr("typevalue");
            var path = $(this).attr("path");
            var res = path.split(".");
            /*$('#modal-viewlesson').modal('hide');
            $('#modal-viewdocument').modal('show');*/
            $('#fil_id_downloadfile').val(id);
            $('#fil_path_downloadfile').val(path);
            
                  window.open('<?php echo base_url().'viewdoc/fileview/';?>'+id+'/'+typevalue+'/<?php echo $cos_id; ?>', '_blank');
                /*if(res[1]=="pdf"){
                  document.getElementById("iframe_document").src = "<?php echo base_url().'/uploads/document/' ?>"+path;
                }else{*//*
                  document.getElementById("iframe_document").src = "https://docs.google.com/gview?url=<?php echo base_url().'/uploads/document/'; ?>"+path+"&embedded=true";*/
                //}
          });
        $('#modal-viewvideo').on('hidden.bs.modal', function () {
          onResetVideo();
        });

        function onplayer_video_cos(type='',video=''){
          onResetVideo();
          $('#modal-viewvideo').modal({backdrop: false});
          if(type=="url"){
              document.getElementById('video_file_view').style.display = 'none';
              document.getElementById('video_url_view').style.display = '';

              var res = video.substring(24);
              //onYouTubeIframeAPIReady(res);
              $('#video_url_view').html('<iframe class="embed-responsive-item youtube-video" id="video_youtube" onclick="chk_youtubeonplay()" src="'+video+'" allowfullscreen></iframe>');
              bindVideoReadyEvents();
              setTimeout(hideVideoLoading, 1500);
          }else{
                  /*var token ;
                  $.post( "<?php echo REAL_PATH ?>/setsession.php?filename="+video, function( data ) {
                    $('#modal-viewvideo').on('hidden.bs.modal', function () {
                        $('#video_file_view').html('<video id="video_upload" controls preload="none" controls controlsList="nodownload" data-setup="{}" style="width: 100%"><source src=""></video>');

                      })
                      document.getElementById('video_file_view').style.display = '';
                      document.getElementById('video_url_view').style.display = 'none';
                      token = data
                      // console.log(token);
                      $('#video_file_view').html('<video id="video_upload" autoplay="autoplay" controls preload="none" controls controlsList="nodownload" data-setup="{}" style="width: 100%"><source src="<?php echo REAL_PATH; ?>/video.php'+token+'" type="video/mp4"></video>');
                      var videoplay = document.getElementById("video_upload");
                      videoplay.onended = function() {
                        rechk_onclick(id);
                      };
                  });*/
              document.getElementById('video_file_view').style.display = '';
              document.getElementById('video_url_view').style.display = 'none';
              $('#video_file_view').html('<video id="video_upload" autoplay controls="controls" preload="metadata" controlsList="nodownload" style="width: 100%" src="<?php echo base_url()."/uploads/media/";?>'+video+'"></video>');
              bindVideoReadyEvents();
              document.getElementById('video_upload').play();
                  /*var token ;
                  $.post( "<?php echo base_url(); ?>setsession.php", function( data ) {
                      $('#modal-viewvideo').on('hidden.bs.modal', function () { $('#video_upload')[0].pause(); })
                      document.getElementById('video_file_view').style.display = '';
                      document.getElementById('video_url_view').style.display = 'none';
                      token = data
                      $('#video_file_view').html('<video id="video_upload" controls preload="none" controls controlsList="nodownload" data-setup="{}" style="width: 100%"><source src="<?php echo base_url(); ?>video.php?filename='+video+'&show_the_video='+token+'" type="video/mp4"></video>');
                      var videoplay = document.getElementById("video_upload");
                      document.getElementById('video_upload').play();
                  });*/
              /*document.getElementById('video_file_view').style.display = '';
              document.getElementById('video_url_view').style.display = 'none';
              $('#video_file_view').html('<video id="video_upload" controls="controls" controlsList="nodownload" style="width: 100%" src="<?php echo base_url()."/uploads/media/";?>'+video+'"></video>');
              document.getElementById('video_upload').play();*/
          }
        }
      <?php if(countArray($course_main)==0){ ?>
        swal({
            title: '<?php echo $wg_datanotfound; ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo $m_ok; ?>'
        }).then(function () {
          window.open("<?php echo REAL_PATH; ?>/dashboard", "_self");
        });
      <?php }else{ 
              if($isFirsttime=="1"){
      ?>
      $(window).on('load',function(){
          $('#select_lang_modal').modal('show');
      });

      $('#select_lang_modal').modal({backdrop: 'static', keyboard: false});
      <?php   }
            } ?>

      $(document).on("keydown",function(ev){
        if(ev.keyCode==27||ev.keyCode==122){
            document.getElementById('scorm_play_iframe').style.width = "100%";
            document.getElementById('scorm_play_iframe').style.height = "100%";
            document.getElementById("div_scorm_ddd").style.height = "500px";
        }
      })

        function openFullscreen(id) {
          var elem = document.getElementById(id);

          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
            elem.webkitRequestFullscreen();
          } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
          }
          if(id=="div_course_detail"){
            var heightdiv_description = $('#div_description_cos');
            if(parseInt(heightdiv_description.outerHeight())>400){
              document.getElementById("contents_main").style.height = "500px";
              document.getElementById("div_description_cos").style.height = "400px";
              document.getElementById("div_description_cos").style.overflow = "hidden";
              document.getElementById("div_description_cos").style.overflowY = "scroll";
            }
          }

          if( isFullScreen()) {
              closeFullscreen(id);
              if(id=="div_course_detail"){
                document.getElementById("contents_main").style.height = "";
                document.getElementById("div_description_cos").style.height = "";
                document.getElementById("div_description_cos").style.overflow = "hidden";
                document.getElementById("div_description_cos").style.overflowY = "hidden";
              }
          }
        }
        function isFullScreen(){
            return window.screenTop == 0 ? true : false;
        }
        function closeFullscreen(id="") {
          var elem = document.getElementById(id);
          if (elem.exitFullscreen) {
            elem.exitFullscreen();
          } else if (elem.mozCancelFullScreen) { /* Firefox */
            elem.mozCancelFullScreen();
          } else if (elem.webkitExitFullscreen) { /* Chrome, Safari and Opera */
            elem.webkitExitFullscreen();
          } else if (elem.msExitFullscreen) { /* IE/Edge */
            elem.msExitFullscreen();
          }
        }

          $(document).on('click', '.btnrefresh', function(e) {
              e.preventDefault();
              location.reload();
          });
         $(document).on('click', '.approve_cos', function(e){
            var cos_id = $(this).attr("id");

            $.ajax({
                  url:"<?=base_url()?>index.php/querydata/rechk_course_period",
                  method:"POST",
                  data:{cos_id:cos_id},
                  dataType:"json",
                  success:function(data)
                  {
                    var title_val = '';
                    if(data.isApprove=="1"){
                        title_val = '<?php echo label('approve_is_course'); ?>';
                        var buttons = $('<div>')
                        .append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>','btn btn-flat btnapprove_cos','background-color:#1abc9c;',cos_id, function() {
                        })).append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject_cos','background-color:#DD6B55;',cos_id, function() {
                           swal.close();
                        })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                           swal.close();
                        }));
                    }else{
                        title_val = '<?php echo label('cantapprove_is_course'); ?>';
                        var buttons = $('<div>')
                        .append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject_cos','background-color:#DD6B55;',cos_id, function() {
                           swal.close();
                        })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                           swal.close();
                        }));
                    }
                    e.preventDefault();
                    swal({
                      title: title_val,
                      html: buttons,
                      type: "warning",
                      showConfirmButton: false,
                      showCancelButton: false
                    });
                  }
            });
          });

          $(document).on('click', '.btnapprove_cos', function(e) {
              e.preventDefault();
              var cos_id = $(this).attr("id");
              $("#myModal_process").modal('show');
              $( document.body ).css( 'pointer-events', 'none' );
              $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_cos_data",
                    method:"POST",
                    data:{cos_id:cos_id},
                    xhr: function() {
                      //document.getElementById("progress_div").style.display = "";
                          var xhr = new window.XMLHttpRequest();
                          xhr.upload.addEventListener("progress", function(evt) {
                              if (evt.lengthComputable) {
                                  var percentComplete = (evt.loaded / evt.total) * 100;
                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(0),
                                            fill: {
                                            color: '#ffa500'
                                          }
                                        }
																				
                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                              }
                         }, false);
                         return xhr;
                    },
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      $('#myModal_process').modal('hide');
                        
                      $("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        }).then(function () {
                          location.reload();
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        }).then(function () {
                          location.reload();
                        })
                      }
                    }
              });
          });

            $(document).on('click', '.les_onclick', function(event){
                event.preventDefault();
                var les_id = $(this).attr("id");
                  $('html,body').animate({scrollTop: $('#less_div').offset().top  - 150},'fast');

            });

          $(document).on('click', '.btnreject_cos', function(e) {
              e.preventDefault();
              var cos_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",    
                confirmButtonText: '<?php echo label('m_ok'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preDetail'); ?>: ",
                inputValidator: (value) => {
                  if (!value) {
                    // หากไม่กรอกข้อมูล
                    return '<?php echo label("pls_enter_reason"); ?>';
                  }
                }
              }).then(function (isChk) {
                  if(isChk.value){
                    $("#myModal_process").modal({backdrop: false});
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/reject_cos",
                      method:"POST",
                      data:{cos_id:cos_id,cosa_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {
                    location.reload();
                      }
                    });
                  }
              });
          });
    </script>
</body>

</html>
