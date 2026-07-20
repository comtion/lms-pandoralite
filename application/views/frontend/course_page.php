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
    <style type="text/css">
      iframe {
          width: 100%;
          height: 100%;
      }
      image {
          width: 100%;
      }

      .myclass {
        color: red;
        font-size: 12px;
      }

      iframe.fullScreen {
          width: 100%;
          height: 100%;
          position: absolute;
          top: 0;
          left: 0;
      }
      table#myTable_lesson.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_lesson.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_quiz.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_quiz.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_cos_id_survey.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_cos_id_survey.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_document_ddd.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_document_ddd.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      .playbutton:hover {
        color: #f0932b;
      }
      <?php if(isMobile()){ ?>
        #div_menu {
            width:100%;
            z-index:2;
            position:fixed;
            left:100%;
            top: 100px;
            background-color: #fff;
        }
        #x {
            position: absolute;
            top: -20px;
            right: 5px;
        }
        #onclose_divmenu_btn{
            position: absolute;
            top: 0px;
            left: -50px;
        }
      <?php } ?>
        html body .bg-inverse1 {
            background-color: #474644;
        }
        .btn-thai_h, .btn-thai_h.disabled {
            background: #009D79;
            color: #ffffff;
            -webkit-box-shadow: 0 2px 2px 0 rgba(0, 157, 121, 0.14), 0 3px 1px -2px rgba(0, 157, 121, 0.2), 0 1px 5px 0 rgba(0, 157, 121, 0.12);
            box-shadow: 0 2px 2px 0 rgba(0, 157, 121, 0.14), 0 3px 1px -2px rgba(0, 157, 121, 0.2), 0 1px 5px 0 rgba(0, 157, 121, 0.12);
            border: 1px solid #009D79;
            -webkit-transition: 0.2s ease-in;
            -o-transition: 0.2s ease-in;
            transition: 0.2s ease-in;
        }
        .btn-thai_h:hover, .btn-thai_h.disabled:hover {
            background: #009D79;
            color: #ffffff;
            -webkit-box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 157, 121, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
            box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
            border: 1px solid #009D79;
        }
        .btn-thai_h.active, .btn-thai_h:active, .btn-thai_h:focus, .btn-thai_h.disabled.active, .btn-thai_h.disabled:active, .btn-thai_h.disabled:focus {
            background: #009D79;
            color: #ffffff;
            -webkit-box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
            box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
            border-color: transparent;
        }
        .tabA {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }
        /* Style the buttons inside the tab */
        .tabA button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }
        /* Change background color of buttons on hover */
        .tabA button:hover {
            background-color: #ddd;
        }
        /* Create an active/current tablink class */
        .tabA button.active {
            background-color: #ccc;
        }
        /* Style the tab content */
        .tabcontentA {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
        .list-group-hover .list-group-item:hover {
            background-color: #f5f5f5;
        }
    </style>
    <style>
    .pdfobject-container { height: 30rem; border: 1rem solid rgba(0,0,0,.1); }
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border">
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
              <?php }else{ ?>
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/home"><?php echo label('home'); ?></a></li>
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
                            <button class="tablinks" onclick="openCity(event, 'score')"><?php echo label('qiz_challenge'); ?></button>
                            <?php if(countArray($courses_doc)>0){ ?><button class="tablinks" onclick="openCity(event, 'paper')"><i class="mdi mdi-content-duplicate"></i><?php echo label('lesson_file'); ?></button><?php } ?>
                        </div>
                        <div id="content_topic" class="tabcontentA">
                          <div id="cdesc_cos"style="max-height: 230px;overflow-y: auto;"><?php if($lang=="thai"){echo $courses['cdesc_th'];}else{echo $courses['cdesc_en'];} ?></div>
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
                <br><br><!-- 
                <?php if(isset($emp_c)){ ?>
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
                        <?php if(countArray($menu_lesson)>0||countArray($menu_scorm)>0||countArray($menu_scorm_quiz)>0){ ?>
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-default"><?php echo label('lesson'); ?></div>
                              <p class="ribbon-content">
                                  <div class="list-group">
                                      <?php if(countArray($menu_lesson)>0){ ?>
                                      <?php foreach ($menu_lesson as $keylesson => $valuelesson) { ?>
                                              <a href="javascript:void(0)" id="<?php echo $valuelesson['les_id']; ?>" style="background-color: #009D79;color: #ffffff;" class="list-group-item les_onclick"><?php if($lang=="thai"){echo $valuelesson['les_name_th'];}else{echo $valuelesson['les_name_en'];} ?></a>
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
                                      <a href="javascript:void(0)" style="background-color: #009D79;color: #ffffff;" id="<?php echo $value_survey['sv_id']; ?>" onclick="onchange_survey(this.id)" class="list-group-item"><?php if($lang=="thai"){echo $value_survey['sv_title_th'];}else{echo $value_survey['sv_title_en'];} ?> <?php echo $score; ?></a>
                                    <?php } ?>
                                  </div> 
                              </p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-9 col-xlg-10 col-md-8">
                        <div class="card" id="div_videocourse">
                            <div class="card-body">
                              <h4><i class="mdi mdi-play-box-outline"></i> <?php echo label('video_course'); ?></h4><hr>
                              <div id="div_media_course"></div>
                            </div>
                        </div>
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
                                     <div class="row">
                                        <div class="col-md-6">
                                          <span id="date_start_lesson" class="float-left"></span><br>
                                          <span id="date_end_lesson" class="float-left"></span>
                                        </div>
                                        <div class="col-md-6 row">
                                          <div class="col-md-12">
                                            <span id="date_mod_lesson" class="float-right"></span>
                                          </div>
                                        </div>
                                      </div><hr>
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
                                        <iframe id="scorm_play_iframe" width="100%" height="100%" style="width:100%;" frameborder="0" allowfullscreen></iframe><br/>
                                      </div>
                                      <div id="div_scorm_btn"><br>
                                              <button class="btn btn-warning col-md-12 full-Screen" onclick="openFullscreen('div_scorm_ddd')"><?php echo label('full_screen') ?></button><br>
                                      </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
              <?php } ?> -->
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
                  <iframe id="iframe_document" style="width:100%; height:500px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="fil_id_downloadfile" id="fil_id_downloadfile">
                    <input type="hidden" id="fil_path_downloadfile" name="fil_path_downloadfile">
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <div id="video_file_view" class="embed-responsive embed-responsive-16by9" style="display: none;">
                  </div>
                  <div id="video_url_view" class="embed-responsive embed-responsive-16by9" style="display: none;"></div>
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



         $(document).on('click', '.videocos_onclick', function(){
              document.getElementById('div_lesson').style.display = 'none';
              document.getElementById('div_videocourse').style.display = '';
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

        function fetch_data_document(les_id='')
         {
            $('#myTable_document_ddd').DataTable().destroy();
            $('#myTable_document_ddd').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_document_ddd", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_lesson_document/',
                    data : {
                      les_id:     les_id,
                      status_user:"1",
                      lang:       "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
    </script> 
</body>

</html>
