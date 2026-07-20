<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); 
    $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
    $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/course_detail.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border" oncontextmenu="return false">
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
        <?php if (!empty($emp_c)){ ?><?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?><?php } ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid">
              <?php if (!empty($emp_c)){ ?>
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <?php if($loadmycos!=""){ ?>
                              <?php if($user['Is_admin']=="0"){ ?>
                                <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/loadCourse"><?php echo label('mycos'); ?></a></li>
                              <?php }else{ ?>
                                <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/available"><?php echo label('course'); ?></a></li>
                              <?php } ?>
                            <?php }else{ ?>
                              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/available"><?php echo label('course'); ?></a></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php if($lang=="thai"){echo $courses['cname_th'];}else{echo $courses['cname_en'];} ?></li>
                        </ol>
                    </div>
                </div>
              <?php } ?>
                <div class="row el-element-overlay">
                    <div class="col-lg"> <img src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $courses['pic']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg'" alt="" style="width:100%;max-height: 400px;"> </div>
                    <div class="col-lg">
                        <h2><?php if($lang=="thai"){echo $courses['cname_th'];}else{echo $courses['cname_en'];} ?></h2>
                        <p><?php echo label('createBy')." : "; ?><?php if(isset($courses['emp'][0])){if($lang=="thai"){ echo $courses['emp'][0]['fullname_th']; }else{echo $courses['emp'][0]['fullname_en'];}} ?>  <?php if(isset($emp_c)){ ?><button class="btn btn-info btn-sm sentmessage" title="<?php echo label('sent'); ?>" data-toggle="modal" data-target="#modal-sentmessage"><i class="mdi mdi-message-text-outline"></i></button><?php if(countArray($courses_video)>0){ ?><button class="btn btn-sm videocos_onclick float-right" style="background-color: #ffaa00" title="<?php echo label('video_course'); ?>"><i class="mdi mdi-play-box-outline"></i> <?php echo label('video_course'); ?></button><?php } ?><?php } ?></p>
                        <div class="tabA">
                            <button class="tablinks" onclick="openCity(event, 'content_topic')" id="defaultOpen"><?php echo label('summary'); ?></button>
                            <?php if(countArray($qiz_challenge)>0){ ?>
                            <button class="tablinks" onclick="openCity(event, 'score')"><?php echo label('qiz_challenge'); ?></button>
                            <?php } ?>
                            <?php if(countArray($courses_doc)>0){ ?><button class="tablinks" onclick="openCity(event, 'paper')"><i class="mdi mdi-content-duplicate"></i><?php echo label('lesson_file'); ?></button><?php } ?>
                        </div>
                        <div id="content_topic" class="tabcontentA">
                          <p>
                            <?php  

                                if((($courses['hour'])!='0.0')&&($lang=="thai")){
                                    echo 'หลักสูตรใช้ระยะเวลา'.' '.$courses['hour'].' '.'นาที';
                                }
                                if((($courses['hour'])!='0.0')&&($lang!="thai")){
                                    echo 'Time Spent Course'.' '.$courses['hour'].' '.'minutes';
                                }

                              ?>
                          </p>
                          <div id="cdesc_cos"style="max-height: 230px;overflow-y: auto;"><?php if($lang=="thai"){echo $courses['cdesc_th'];}else{echo $courses['cdesc_en'];} ?></div>
                            <?php if(countArray($is_Enroll)>0){ ?>
                              <?php if($is_Enroll['cosen_status']=="2"){ ?>
                                          <h3 align="center" style="color: #eb4d4b;font-family: 'Prompt', sans-serif;"><i class="far fa-times-circle"></i> <?php echo label('msg_cancel_course'); ?></h3>
                              <?php } ?>
                            <?php }else{ ?>
                                  <?php if($user['Is_admin']=="0"){ ?>
                                          <button class="btn waves-effect waves-light btn-outline-secondary btn_register" id="<?php echo $cos_id; ?>" style="font-size: 12px"><i class="fas fa-user-plus"></i> <?php echo label('register'); ?></button>

                                          <input type="hidden" id="enroll_seat_hide<?php echo $cos_id; ?>" name="enroll_seat_hide<?php echo $cos_id; ?>" value="<?php echo $courses['enroll_seat']; ?>">
                                          <input type="hidden" id="seat_count_hide<?php echo $cos_id; ?>" name="seat_count_hide<?php echo $cos_id; ?>" value="<?php echo $courses['seat_count']; ?>">
                                  <?php } ?>
                            <?php } ?>
                        </div>

                        <div id="score" class="tabcontentA" style="max-height: 250px;overflow-y: auto;">
                                <table width="70%" border="1" class="table table-bordered table-hover">
                                  <thead>
                                    <th width="10%" align="center"><?php echo label('r_no'); ?></th>
                                    <th width="60%" align="center"><?php echo label('r_name'); ?></th>
                                    <th width="30%" align="center"><?php echo label('score'); ?></th>
                                  </thead>
                                  <tbody>
                                    <?php $num = 1;
                                          if(countArray($qiz_challenge)>0){ 
                                              foreach ($qiz_challenge as $key => $value) {
                                    ?>
                                              <tr>
                                                <td><?php echo $num;$num++; ?></td>
                                                <td><?php if($lang=="thai"){echo $value['fullname_th'];}else{echo $value['fullname_en'];}  ?></td>
                                                <td><?php echo $value['cosen_score']; ?></td>
                                              </tr>
                                    <?php     }
                                          }else{ ?>
                                      <tr>
                                        <td colspan="3" align="center"><?php echo label('wg_datanotfound'); ?></td>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                        </div>
                        <div id="paper" class="tabcontentA" style="max-height: 250px;overflow-y: auto;">
                            <div class="table-responsive">
                              <table width="100%" class="table">
                                <?php $num_doc = 1;
                                      foreach ($courses_doc as $key_fil_cos => $value_fil_cos) { ?>
                                      <tr>
                                        <td width="10%" style="cursor: pointer;" onclick="onpreview_document_cos_exp('<?php echo $value_fil_cos['fil_cos_id']; ?>')"><i class=" ti-search"></i></td>
                                        <td width="10%"><?php echo $num_doc;$num_doc++; ?></td>
                                        <td width="80%"><?php if($lang=="thai"){echo $value_fil_cos['name_fileth'];}else{echo $value_fil_cos['name_fileen'];} ?></td>
                                      </tr>
                                <?php } ?>
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <?php if(isset($emp_c)&&(countArray($is_Enroll)>0)){ ?>
                <div class="row">
                    <div class="col-lg-3 col-xlg-2 col-md-4">
                        <?php if(intval($menu_qiz_pre)>0){ ?>
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-default"><?php echo label('preExam'); ?></div>
                              <p class="ribbon-content">
                                  <div class="list-group">
                                    <?php foreach ($menu_qiz_pre as $keyqiz_pre => $valueqiz_pre) { 
                                                    $score = "";
                                                    if($valueqiz_pre['qiz_status']!="1"&&$valueqiz_pre['qiz_status']!="2"&&$valueqiz_pre['qiz_status']!="00"){
                                                        $score = "<br>( ".label('preYourScore3')." ".number_format(floatval($valueqiz_pre['sum_score']),2)." )";
                                                    }
                                    ?>
                                      <a href="javascript:void(0)" style="background-color: #009D79;color: #ffffff;" id="<?php echo $valueqiz_pre['qiz_id']; ?>" class="list-group-item"><?php if($lang=="thai"){echo $valueqiz_pre['quiz_name_th'];}else{echo $valueqiz_pre['quiz_name_en'];} ?> <?php echo $score; ?></a>
                                    <?php } ?>
                                  </div> 
                              </p>
                        </div>
                        <?php } ?>
                        <?php if((countArray($menu_lesson)>0||countArray($menu_scorm)>0||countArray($menu_scorm_quiz)>0)){ ?>
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-default"><?php echo label('lesson'); ?></div>
                              <p class="ribbon-content">
                                  <div class="list-group">
                                      <?php if(countArray($menu_lesson)>0){ ?>
                                      <?php foreach ($menu_lesson as $keylesson => $valuelesson) { ?>
                                              <a href="javascript:void(0)" id="<?php echo $valuelesson['les_id']; ?>" style="background-color: #009D79;color: #ffffff;" class="list-group-item les_onclick"><i class="mdi mdi-play-circle-outline"></i><br><?php if($lang=="thai"){echo $valuelesson['les_name_th'];}else{echo $valuelesson['les_name_en'];} ?>
                                              <?php 
                                              if($valuelesson['les_status']!=""){
                                                  if($valuelesson['les_status']=="1"){
                                                    echo '<span id="icon_les'.$valuelesson['les_id'].'"><i class="mdi mdi-av-timer float-right" title="'.label('inProgress').'"></i></span>';
                                                  }else if($valuelesson['les_status']=="2"){
                                                    echo '<span id="icon_les'.$valuelesson['les_id'].'"><i class="mdi mdi-check-circle-outline float-right" title="'.label('done').'"></i></span>';
                                                  }
                                              } 
                                              ?>
                                              </a>
                                              <center><h6 style="margin-top: 5px;"><time></time></h6></center>
                                              
                                      <?php } ?>
                                      <?php } ?>
                                      <?php if(countArray($menu_scorm)>0){ ?>
                                      <?php foreach ($menu_scorm as $keyscorm => $valuescorm) { ?>
                                              <a href="javascript:void(0)" id="<?php echo $valuescorm['les_id']; ?>" style="background-color: #009D79;color: #ffffff;" class="list-group-item les_onclick"><?php if($lang=="thai"){echo $valuescorm['les_name_th'];}else{echo $valuescorm['les_name_en'];} ?></a>
                                      <?php } ?>
                                      <?php } ?>
                                      <?php if(countArray($menu_scorm_quiz)>0){ ?>
                                        <?php foreach ($menu_scorm_quiz as $keyscorm_quiz => $valuescorm_quiz) { ?>
                                                <a href="javascript:void(0)" id="<?php echo $valuescorm_quiz['les_id']; ?>" style="background-color: #009D79;color: #ffffff;" class="list-group-item les_onclick"><?php if($lang=="thai"){echo $valuescorm_quiz['les_name_th'];}else{echo $valuescorm_quiz['les_name_en'];} ?></a>
                                        <?php } ?>
                                      <?php } ?>
                                  </div> 
                              </p>
                        </div>
                        <?php } ?>
                        <?php if(intval($menu_qiz_post)>0){ ?>
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-default"><?php echo label('finalExam'); ?></div>
                              <p class="ribbon-content">
                                  <div class="list-group">
                                    <?php foreach ($menu_qiz_post as $keyqiz_post => $valueqiz_post) { 
                                                    $score = "";
                                                    if($valueqiz_post['qiz_status']!="1"&&$valueqiz_post['qiz_status']!="2"&&$valueqiz_post['qiz_status']!="00"){
                                                        $score = "<br>( ".label('preYourScore3')." ".number_format(floatval($valueqiz_post['sum_score']),2)." )";
                                                    }
                                    ?>
                                      <a href="javascript:void(0)" style="background-color: #009D79;color: #ffffff;" id="<?php echo $valueqiz_post['qiz_id']; ?>" class="list-group-item qiz_onclick"><?php if($lang=="thai"){echo $valueqiz_post['quiz_name_th'];}else{echo $valueqiz_post['quiz_name_en'];} ?> <?php echo $score; ?></a>
                                    <?php } ?>
                                  </div> 
                              </p>
                        </div>
                        <?php } ?>

                        <?php if(intval($menu_survey)>0){ ?>
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-default"><?php echo label('survey'); ?></div>
                              <p class="ribbon-content">
                                  <div class="list-group">
                                    <?php foreach ($menu_survey as $key_survey => $value_survey) { ?>
                                      <a href="javascript:void(0)" style="background-color: #009D79;color: #ffffff;" id="<?php echo $value_survey['sv_id']; ?>" onclick="onchange_survey(this.id)" class="list-group-item"><?php if($lang=="thai"){echo $value_survey['sv_title_th'];}else{echo $value_survey['sv_title_en'];} ?></a>
                                    <?php } ?>
                                  </div> 
                              </p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-9 col-xlg-10 col-md-8">
                      <?php if(countArray($courses_video)>0){ ?>
                        <div class="card" id="div_videocourse">
                            <div class="card-body">
                              <h4><i class="mdi mdi-play-box-outline"></i> <?php echo label('video_course'); ?></h4><hr>
                              <div id="div_media_course"></div>
                            </div>
                        </div>
                      <?php } ?>
                        <div class="card" id="div_lesson" style="display: none;">
                            <div class="card-body">
                                  <div>
                                    <div class="row">
                                      <div class="col-md-9" id="div_txt_head_lesson">
                                        <h4><?php echo label('lesson')." : "; ?> <span id="txt_head_lesson"></span></h4>
                                      </div>
                                      <div class="form-group col-md-3" id="div_fil_id_select">
                                        <select class="form-control" onchange="onpreview_document()" id="fil_id_select" name="fil_id_select"  style="width: 100%;">
                                        </select>
                                      </div>
                                    </div>
                                        <input type="hidden" id="lesson_id" name="lesson_id">
                                        <input type="hidden" id="lesson_id_next" name="lesson_id_next">
                                        <input type="hidden" id="lesson_id_back" name="lesson_id_back">
                                      <!--<div class="row">
                                        <div class="col-md-6">
                                          <span id="date_start_lesson" class="float-left"></span><br>
                                          <span id="date_end_lesson" class="float-left"></span>
                                        </div>
                                        <div class="col-md-6 row">
                                          <div class="col-md-12">
                                            <span id="date_mod_lesson" class="float-right"></span>
                                          </div>
                                        </div>
                                      </div>--><hr>
                                      <div id="div_media_ddd"></div>
                                      <div id="div_description_leson"></div>
                                      
                                      <div id="div_document_ddd" style="display: none;"><hr>
                                        <h4 id="myLargeModalLabel"><?php echo label('lesson_file'); ?></h4>
                                        <div class="card-body table-responsive">
                                            <table id="myTable_document_ddd" width="100%" class="table table-bordered table-hover">
                                                <thead>
                                                  <tr>
                                                    <th width="10%"></th>
                                                    <th width="45%" align="center"><?php echo label('file_name'); ?></th>
                                                    <th width="15%" align="center"><?php echo label('view_file'); ?></th>
                                                    <th width="15%" align="center"><?php echo label('download_file'); ?></th>
                                                    <th width="15%" align="center"><?php echo label('download_count'); ?></th>
                                                  </tr>
                                                </thead>
                                            </table>
                                        </div>
                                      </div>
                                      <div id="div_scorm_ddd" style="height: 500px">
                                        <iframe id="scorm_play_iframe" width="100%"  height="100%" style="width:100%;" frameborder="0" disabled allowfullscreen></iframe><br/>
                                      </div>
                                      <div id="div_scorm_btn"><br>
                                              <button class="btn btn-warning col-md-12 full-Screen" onclick="openFullscreen('div_scorm_ddd')"><?php echo label('full_screen') ?></button><br>
                                      </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
              <?php } ?>
            </div>
        </div>
    </div>

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>


    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-viewquestionnaire" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><?php echo label('questionnaire')." : "; ?> <span id="txt_head_questionnaire"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="questionnaire_form" autocomplete="off" name="questionnaire_form" enctype="multipart/form-data">
                  <div class="modal-body ">
                    <div id="questionnaire_div"></div>
                  </div>
                  <div class="modal-footer">
                      <input type="submit" name="action" id="btn_question_save" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('saveR'); ?>" />
                      <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                  </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-certificate" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="mdi mdi-printer"></i> <?php echo label('mybadges'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <object id="obj_pdf_cert" type="application/pdf" width="100%" height="500">
                  </object>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-viewdocument" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="mdi mdi-printer"></i> <?php echo label('lesson_file'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body"><!-- 
                  <div id="iframe_document"></div> -->
                  <iframe id="iframe_document" style="width:100%; height:500px;" frameborder="0"></iframe>-
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="fil_id_downloadfile" id="fil_id_downloadfile">
                    <input type="hidden" id="fil_path_downloadfile" name="fil_path_downloadfile">
                    <button type="button" name="btn_downloadfile" class="btn btn-outline-info btn-flat float-left btn_downloadfile"><?php echo label('download_file'); ?></button>
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-viewvideo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-height: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="fas fa-video"></i> <?php echo label('Les_video'); ?></h4>
                    <button type="button" class="close" onclick="location.reload();" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <div id="video_file_view" class="embed-responsive embed-responsive-16by9" style="display: none;">
                  </div>
                  <div id="video_url_view" class="embed-responsive embed-responsive-16by9" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" onclick="location.reload();" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" tabindex="-1" id="modal-sentmessage" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-height: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="mdi mdi-message-text-outline"></i> <?php echo label('sent'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="sentmessage_form" autocomplete="off" name="sentmessage_form" enctype="multipart/form-data">
                <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label text-right"><b style="color: #FF2D00">*</b> <?php echo label('contamess'); ?></label>
                                            <textarea class="form-control" required name="smc_msg" id="smc_msg" rows="10"></textarea>
                                        </div>
                                        <input type="hidden" id="cos_id_msg" name="cos_id_msg">
                                        <input type="hidden" id="email_cos" name="email_cos">
                </div>
                <div class="modal-footer">
                    <input type="submit" name="action" id="btn_sentmsg" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('sent'); ?>" />
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<!--
    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-viewquiz" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><?php echo label('quiz')." : "; ?> <span id="txt_head_qiz"></span></h4>
                    <button type="button" class="close" onclick="location.reload()" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body ">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" onclick="location.reload()" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
        </div>-->
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- This is data table -->
    
    <?php //$this->load->view('frontend/inc/inc_coursedetailjs.php'); ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/pdfobject/pdfobject.js"></script>
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";

    	<?php if(countArray($courses_video)>0){ ?>
                    $.ajax({
                        url:"<?=base_url()?>index.php/course/run_media_course",
                        method:"POST",
                        data:{cos_id:'<?php echo $cos_id; ?>'},
                        success:function(data)
                        {
                          if(data!=""){
                            document.getElementById('div_videocourse').style.display = '';
                            document.getElementById('div_media_course').style.display = '';
                            $('#div_media_course').html(data);
                          }else{
                            document.getElementById('div_videocourse').style.display = 'none';
                            document.getElementById('div_media_course').style.display = 'none';
                          }
                        }
                      });
        <?php } ?>
        function onplayer_video_cos(type='',video=''){
          $('#modal-viewvideo').modal('show');
          if(type=="url"){
						
              document.getElementById('video_file_view').style.display = 'none';
              document.getElementById('video_url_view').style.display = '';

              var res = video.substring(24);
              //onYouTubeIframeAPIReady(res);
              $('#video_url_view').html('<iframe class="embed-responsive-item youtube-video" id="video_youtube" onclick="chk_youtubeonplay()" src="'+video+'" allowfullscreen></iframe>');
          }else{
              document.getElementById('video_file_view').style.display = '';
              document.getElementById('video_url_view').style.display = 'none';
              $('#video_file_view').html('<video id="video_upload" controls="controls" style="width: 100%" src="<?php echo base_url()."/uploads/cosvideo/";?>'+video+'"></video>');
          }
        }
        function onchange_survey(id){
                $('#modal-viewquestionnaire').modal('show');
                update_questionnaire(id);
        }
        function update_questionnaire(sv_id = ''){
                $('#sv_id').val(sv_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/course/rechk_survey_detail_data",
                  method:"POST",
                  data:{sv_id_rechkdata:sv_id},
                  success:function(data_rechk)
                  {
                    if(data_rechk=="1"){
                      document.getElementById('btn_question_save').style.display = 'none';
                    }else{
                      document.getElementById('btn_question_save').style.display = '';
                    }
                  }
                });
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_survey_detail_data",
                  method:"POST",
                  data:{sv_id_update:sv_id},
                  dataType:"json",
                  success:function(data)
                  {
                       
                    <?php if($lang=="thai"){ ?>               
                      $('#txt_head_questionnaire').text(data.sv_title_th);
                    <?php }else{ ?>
                      $('#txt_head_questionnaire').text(data.sv_title_en);
                    <?php } ?>
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/view_survey_detail_data",
                      method:"POST",
                      data:{sv_id_view:sv_id},
                      success:function(data_div)
                      {
                        $('#questionnaire_div').html(data_div);
                      }
                    });
                  }
                });
        }
        $(document).on('submit', '#questionnaire_form', function(event){
              event.preventDefault(); 
              var sv_id = $('#sv_id').val();
              $.ajax({
                  url:"<?=base_url()?>index.php/course/save_survey",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#questionnaire_form')[0].reset();
                        $('#modal-viewquestionnaire').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("course_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $('#questionnaire_form')[0].reset();
                            $('#sv_id').val(sv_id);
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_form_error"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                   
                  }
                });
        });
            $(document).on('click', '.btn_register', function(){
                        var id = $(this).attr("id");
                        /*var point_redeem = $('#point_redeem_hide'+id).val();
                        var point_user = '<?php echo $user['usp_point']; ?>';
                        var enroll_seat = '<?php echo $courses['enroll_seat'] ?>';
                        var seat_count = '<?php echo $courses['seat_count'] ?>';*/

                        var enroll_seat = $('#enroll_seat_hide'+id).val();
                        var seat_count = $('#seat_count_hide'+id).val();
                        if((parseInt(enroll_seat)+1)<parseInt(seat_count)){
                          status = "1";
                        }else{
                          status = "0";
                        }
                        if(parseInt(seat_count)==0){
                          status = "1";
                        }
                       /* console.log(id,parseInt(point_redeem),parseInt(point_user));
                        if(parseInt(point_redeem)>0){
                          if(parseFloat(point_user)>=parseFloat(point_redeem)){
                                $.ajax({
                                  url:"<?=base_url()?>index.php/course/register_course",
                                  method:'POST',
                                  data:{cos_id:id,status:status},
                                  success:function(data)
                                  {
                                    
                                    if(data=="2"){
                                        swal(
                                            '<?php echo label("enroll_reuse_success"); ?>!',
                                            '',
                                            'success'
                                        ).then(function () {
                                          window.location.href = '<?=base_url()?>index.php/course/detail/'+id;
                                        })
                                    }else{
                                        swal({
                                            title: '<?php echo label("enroll_reuse_error"); ?>',
                                            text: "",
                                            type: 'warning',
                                            showCancelButton: false,
                                            confirmButtonClass: 'btn btn-primary',
                                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                                        }).then(function () {
                                            location.reload();
                                        })
                                    }
                                   
                                  }
                                });
                          }else{
                            swal({
                                        title: '<?php echo label('point_dontcan'); ?>',
                                        text: "",
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label('m_ok'); ?>'
                            })
                          }
                        }else{*/
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/register_course",
                      method:'POST',
                      data:{cos_id:id,status:status},
                      success:function(data)
                      {
                        
                        if(data=="2"){
                            swal(
                                '<?php echo label("enroll_reuse_success"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                              window.location.href = '<?=base_url()?>index.php/course/detail/'+id;
                            })
                        }else{
                            swal({
                                title: '<?php echo label("enroll_reuse_error"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                location.reload();
                            })
                        }
                       
                      }
                    });
            //}
          });
         $(document).on('click', '.sentmessage', function(){
              var cos_id = '<?php echo $cos_id; ?>';
              $('#smc_msg').val('');
              $('#cos_id_msg').val(cos_id);
              $('#email_cos').val('<?php echo isset($courses['emp'][0]['email'])?$courses['emp'][0]['email']:""; ?>');
         });

        $(document).on('submit', '#sentmessage_form', function(event){
              event.preventDefault(); 
              $.ajax({
                  url:"<?=base_url()?>index.php/course/sent_message_course",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    
                    if(data=="2"){
                        swal(
                            '<?php echo label("sent_msg"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          $('#sentmessage_form')[0].reset();
                          $('#modal-sentmessage').modal('hide');
                        })
                    }else{
                        swal({
                            title: '<?php echo label("cannot_sent_msg"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                   
                  }
                });
        });

        function openCity(evt, cityName) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontentA");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
          }
          document.getElementById(cityName).style.display = "block";
          evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();

        function onpreview_document_cos_exp(fil_id){
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_fil_cos_data",
                  method:"POST",
                  data:{fil_id:fil_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                    if(data.type=="document"){
                      $('#modal-viewdocument').modal('show');
                      $('#fil_id_downloadfile').val(fil_id);
                      $('#fil_path_downloadfile').val(data.path_file);
                      //PDFObject.embed("<?php echo base_url().'/uploads/document/' ?>"+data.path_file, "#iframe_document");
                      document.getElementById("iframe_document").src = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                      //document.getElementById("iframe_document").src = "https://docs.google.com/viewer?url="+data.link+"&embedded=true";
                    }else{
                      window.location = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                    }
                  }
                });
        }

        $(document).on('click', '.btn_downloadfile', function(){
            var fil_id = $('#fil_id_downloadfile').val();
            //var path = $('#fil_path_downloadfile').val();

                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_fil_log",
                  method:"POST",
                  data:{fil_id:fil_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                  }
                });
                window.location = "<?php echo base_url().'/uploads/document/watermark.php?id=' ?>"+fil_id+'&emp_id=<?php echo $user['emp_id']; ?>&field=course';
        });

         $(document).on('click', '.qiz_onclick', function(){
            var id = $(this).attr("id");
            window.open("<?php echo REAL_PATH; ?>/pretest/detail/"+id,"_self");
            /*$( ".qiz_onclick" ).removeClass( "active" );
            $( ".les_onclick" ).removeClass( "active" );
            $( this ).addClass( "active" );

              document.getElementById('quiz_div').style.display = '';
              document.getElementById('div_lesson').style.display = 'none';
              document.getElementById('div_description_cos').style.display = 'none';
                          document.getElementById("div_description_qiz_challenge").style.display = "none";
                update_quiz(id);*/
          });

         $(document).on('click', '.videocos_onclick', function(){
              document.getElementById('div_lesson').style.display = 'none';
              document.getElementById('div_videocourse').style.display = '';
          });

         $(document).on('click', '.les_onclick', function(){
              var id = $(this).attr("id");
              $( ".les_onclick" ).removeClass( "active" );
              $( this ).addClass( "active" );
              update_lesson(id);
                $.ajax({
                  url:"<?=base_url()?>index.php/course/rechk_status_lesson",
                  method:"POST",
                  data:{les_id:id,emp_id:'<?php echo $user['emp_id']; ?>'},
                  dataType:"json",
                  success:function(data)
                  {
                      if(data.les_status == "1"){
                          $('#icon_les'+id).html('<i class="mdi mdi-av-timer float-right" title="<?php echo label('inProgress'); ?>"></i>');
                      }else if(data.les_status == "2"){
                          $('#icon_les'+id).html('<i class="mdi mdi-check-circle-outline float-right" title="<?php echo label('done'); ?>"></i>');
                      }else{
                          $('#icon_les'+id).html('');
                      }
                  }
                });
              document.getElementById('div_lesson').style.display = '';
              //document.getElementById('div_videocourse').style.display = 'none';
          });

         $(document).on('click', '.view_document', function(){
            var id = $(this).attr("id");
						
            $('#modal-viewlesson').modal('hide');
            $('#modal-viewdocument').modal('show');
            //PDFObject.embed("<?php echo base_url().'/uploads/document/' ?>"+id, "#iframe_document");
            document.getElementById("iframe_document").src = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
            //document.getElementById("iframe_document").src = "https://docs.google.com/viewer?url="+id+"&embedded=true";
          });
         $(document).on('click', '.download_doc', function(){
            var id = $(this).attr("id");
            var les_id = $('#lesson_id').val();
            fetch_data_document(les_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_fil_log",
                  method:"POST",
                  data:{fil_id:id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                  }
                });
          });

        function update_lesson(les_id=''){
          document.getElementById('scorm_play_iframe').src = "";
                $('#lesson_id').val(les_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_lesson_detail_data",
                  method:"POST",
                  data:{les_id_update:les_id},
                  dataType:"json",
                  success:function(data)
                  {
                      /*$.ajax({
                        url:"<?=base_url()?>index.php/course/update_les_log",
                        method:"POST",
                        data:{id:les_id},
                        dataType:"json",
                        success:function(dataupdate)
                        {
                        }
                      });*/
                    <?php if($lang=="thai"){ ?>
                      $('#txt_head_lesson').text(data.les_name_th);
                      $('#div_description_leson').html(data.les_info_th);
                    <?php }else{ ?>
                      $('#txt_head_lesson').text(data.les_name_en);
                      $('#div_description_leson').html(data.les_info_en);
                    <?php } ?>

                    $('#date_mod_lesson').text("<?php echo label('dateMod').' : '; ?>"+data.time_modified);
                    if(data.time_start!=""){
                      $('#date_start_lesson').text("<?php echo label('dateStart').' : '; ?>"+data.time_start_les);
                    }else{
                      $('#date_start_lesson').text("<?php echo label('dateStart').' : '.label('infinity').label('time'); ?>");
                    }
                    if(data.time_start!=""){
                      $('#date_end_lesson').text("<?php echo label('dateExpired').' : '; ?>"+data.time_end_les);
                    }else{
                      $('#date_end_lesson').text("<?php echo label('dateExpired').' : '.label('infinity').label('time'); ?>");
                    }
                    
                    if(data.les_type=="1"){
                      document.getElementById('div_scorm_ddd').style.display = 'none';
                      document.getElementById('div_scorm_btn').style.display = 'none';
                      $.ajax({
                        url:"<?=base_url()?>index.php/course/count_fetchdata",
                        method:"POST",
                        data:{tablename:'lms_fil',field_id:'lessons_id',id:les_id},
                        success:function(data)
                        {
                          if(parseInt(data)>0){
                            //fetch_data_document(les_id);
                            $.ajax({
                                  url: '<?=base_url()?>index.php/workgroup/rechecklesson',
                                  type: 'POST',
                                  data:{les_id:les_id},
                                  success: function(data_fil){
                                    $('#fil_id_select').html(data_fil);
                                  }
                            });
                            document.getElementById("div_txt_head_lesson").classList.add('col-md-9');
                            document.getElementById("div_txt_head_lesson").classList.remove('col-md-12');
                            document.getElementById('div_fil_id_select').style.display = '';
                          }else{
                            document.getElementById("div_txt_head_lesson").classList.add('col-md-12');
                            document.getElementById("div_txt_head_lesson").classList.remove('col-md-9');
                            document.getElementById('div_fil_id_select').style.display = 'none';
                          }
                        }
                      });

                      $.ajax({
                        url:"<?=base_url()?>index.php/course/run_media",
                        method:"POST",
                        data:{les_id:les_id},
                        success:function(data)
                        {
                          
                          if(data!=""){
                            document.getElementById('div_media_ddd').style.display = '';
                            $('#div_media_ddd').html(data);
                          }else{
                            document.getElementById('div_media_ddd').style.display = 'none';
                          }
                        }
                      });
                    }else{
                      document.getElementById('div_media_ddd').style.display = 'none';
                      document.getElementById('div_document_ddd').style.display = 'none';
                      document.getElementById('div_fil_id_select').style.display = 'none';
                      $.ajax({
                        url:"<?=base_url()?>index.php/course/fetchdata_scorm",
                        method:"POST",
                        data:{id:les_id},
                        success:function(data)
                        {
                          if(data!=""){
                            document.getElementById('scorm_play_iframe').src = "<?php echo base_url().'/scorm/loadScorm/' ?>"+data;
                            document.getElementById('div_scorm_ddd').style.display = '';
                            document.getElementById('div_scorm_btn').style.display = '';
                            var script = '<script>document.addEventListener("contextmenu", function (e) { e.preventDefault(); }, false);alert("1080");<\/script>';
                            $('#scorm_play_iframe').contents().find('body').append(script);
                          }else{
                            document.getElementById('div_scorm_ddd').style.display = 'none';
                            document.getElementById('div_scorm_btn').style.display = 'none';
                          }
                        }
                      });
                    }
                    
                    
                  }
                });

        }

        function onplayer_video(type='',video='',id=''){
					
          $('#modal-viewvideo').modal('show');

                $.ajax({
                  url:"<?=base_url()?>index.php/course/rechk_status_medlesson",
                  method:"POST",
                  data:{med_id:id,emp_id:'<?php echo $user['emp_id']; ?>'},
                  dataType:"json",
                  success:function(data_med)
                  {
										
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/rechk_status_lesson",
                      method:"POST",
                      data:{les_id:data_med.les_id,emp_id:'<?php echo $user['emp_id']; ?>'},
                      dataType:"json",
                      success:function(data)
                      {
                          if(data.les_status == "1"){
                              $('#icon_les'+id).html('<i class="mdi mdi-av-timer float-right" title="<?php echo label('inProgress'); ?>"></i>');
                          }else if(data.les_status == "2"){
                              $('#icon_les'+id).html('<i class="mdi mdi-check-circle-outline float-right" title="<?php echo label('done'); ?>"></i>');
                          }else{
                              $('#icon_les'+id).html('');
                          }
                      }
                    });
                  }
                });


          if(type=="url"){
              document.getElementById('video_file_view').style.display = 'none';
              document.getElementById('video_url_view').style.display = '';

              var res = video.substring(24);
              //onYouTubeIframeAPIReady(res);
              $('#video_url_view').html('<iframe class="embed-responsive-item youtube-video" id="video_youtube" onclick="chk_youtubeonplay()" src="'+video+'" allowfullscreen></iframe>');
          }else{
              document.getElementById('video_file_view').style.display = '';
              document.getElementById('video_url_view').style.display = 'none';
              $('#video_file_view').html('<video id="video_upload" controls="controls" style="width: 100%" src="<?php echo base_url()."/uploads/media/";?>'+video+'"></video>');
          }
        }
        function onpreview_document(){
          var fil_id = $('#fil_id_select').val();
          $('#fil_id_select').val("");
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_fil_data",
                  method:"POST",
                  data:{fil_id:fil_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                    if(data.type=="document"){
                      $('#modal-viewdocument').modal('show');
                      $('#fil_id_downloadfile').val(fil_id);
                      $('#fil_path_downloadfile').val(data.path_file);
                      //PDFObject.embed("<?php echo base_url().'/uploads/document/' ?>"+data.path_file, "#iframe_document");
                      document.getElementById("iframe_document").src = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                    }else{
                      window.location = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                    }
                  }
                });
        }
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

        function fetch_data_document(les_id='')
         {
            $('#myTable_document_ddd').DataTable().destroy();
            $('#myTable_document_ddd').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_document_ddd", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_lesson_document/',
                    data : {
                      les_id:       les_id,
                      status_user:  "1",
                      lang:         "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
    </script> 
</body>

</html>
