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
    <!--c3 CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/c3-master/c3.min.css" rel="stylesheet">

    <!-- chartist CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- Date picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/adapter.min.js"></script>
    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/vue.min.js"></script>
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .element_onsave  { position:fixed; bottom:70px; right:2%; }
        .save_btn {
          background-color: #1abc9c;
          color: #ffffff;
        }
        .save_btn:hover {
          background-color: #d35400;
        }
        .sent_btn {
          background-color: #3498db;
          color: #ffffff;
        }
        .sent_btn:hover {
          background-color: #2980b9;
        }
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
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid"> 
                <div class="row col-22 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php if($qiz['quiz_type']=="1"){echo label('preExam')." : ";}else{echo label('finalExam')." : ";} if($lang=="thai"){echo $qiz['quiz_name_th'];}else{echo $qiz['quiz_name_en'];} ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/loadCourse"><?php echo label('mycos'); ?></a></li>
                            <?php if($qiz['quiz_type']=="2"){ ?>
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/detail/<?php echo $qiz['cos_id']; ?>"><?php if($lang=="thai"){echo $course['cname_th'];}else{echo $course['cname_en'];} ?></a></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php if($qiz['quiz_type']=="1"){echo label('preExam');}else{echo label('finalExam');} ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                    <form class="row">
                        <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $user['emp_id']; ?>">
                        <input type="hidden" id="qiz_id" name="qiz_id" value="<?php echo $qcode; ?>">
                    <?php $num_row = 1; 
                        foreach ($question as $key_ques => $value_ques) { 
                            $arr_answer = array($value_ques['mul_answer']);
                    ?>

                        <input type="hidden" id="value_ques<?php echo $num_row; ?>" name="value_ques_[]" value="<?php echo $value_ques['tc_answer']; ?>">
                        <input type="hidden" id="ques_id_<?php echo $num_row; ?>" name="ques_id_<?php echo $num_row; ?>" value="<?php echo $value_ques['ques_id']; ?>">
                        <div class="col-md-12 card card-body"  id="ques_div<?php echo $num_row; ?>">
                            <div class="row">
                                <div class="col-md-4" style="padding-left: 50px">
                                    <h3 align="left"><?php echo label('sqi')." ".$num_row;  ?></h3>
                                    <b><?php if($value_ques['tc_answer']!=""){echo label('answered');}else{echo label('n_answered');}  ?></b><br>
                                    <b><?php echo label('mark_out_of')." ".number_format($value_ques['ques_score']); ?></b>
                                </div>
                                <div class="col-md-8" style="padding-right: 50px">
                                    <?php if($lang=="thai"){echo $value_ques['ques_name_th'];}else{echo $value_ques['ques_name_en'];} ?><br>

                                    <?php if($value_ques['ques_type']=="multi"){ ?>
                                        <?php if($value_ques['mul_c1_th']!=""||$value_ques['mul_c1_en']!=""){ ?>
                                        <input name="tc_answer_input_<?php echo $num_row; ?>" onclick="updateinput('<?php echo $num_row; ?>',this.value)" type="radio" id="mul_c1_<?php echo $num_row; ?>" class="with-gap radio-col-teal radio_answer <?php echo $num_row; ?>" value="mul_c1" <?php if($value_ques['tc_answer']=="mul_c1"){echo "checked";} ?>/>
                                        <label <?php if($qiz['quiz_type']=="2"&&$value_ques['tc_answer']!=""&&$qiz['quiz_answer']=="1"&&$value_ques['qiz_status']!="1"&&$value_ques['qiz_status']!="2"&&in_array('mul_c1', $arr_answer)&&$value_ques['tc_answer']!='mul_c1'){ ?>style="color:#c0392b;"<?php } ?> for="mul_c1_<?php echo $num_row; ?>"><b><?php if($lang=="thai"){echo $value_ques['mul_c1_th'];}else{echo $value_ques['mul_c1_en'];} ?></b></label><br>
                                        <?php } ?>
                                        <?php if($value_ques['mul_c2_th']!=""||$value_ques['mul_c2_en']!=""){ ?>
                                        <input name="tc_answer_input_<?php echo $num_row; ?>" onclick="updateinput('<?php echo $num_row; ?>',this.value)" type="radio" id="mul_c2_<?php echo $num_row; ?>" class="with-gap radio-col-teal radio_answer <?php echo $num_row; ?>" value="mul_c2" <?php if($value_ques['tc_answer']=="mul_c2"){echo "checked";} ?>/>
                                        <label <?php if($qiz['quiz_type']=="2"&&$value_ques['tc_answer']!=""&&$qiz['quiz_answer']=="1"&&$value_ques['qiz_status']!="1"&&$value_ques['qiz_status']!="2"&&in_array('mul_c2', $arr_answer)&&$value_ques['tc_answer']!='mul_c2'){ ?>style="color:#c0392b;"<?php } ?> for="mul_c2_<?php echo $num_row; ?>"><b><?php if($lang=="thai"){echo $value_ques['mul_c2_th'];}else{echo $value_ques['mul_c2_en'];} ?></b></label><br>
                                        <?php } ?>
                                        <?php if($value_ques['mul_c3_th']!=""||$value_ques['mul_c3_en']!=""){ ?>
                                        <input name="tc_answer_input_<?php echo $num_row; ?>" onclick="updateinput('<?php echo $num_row; ?>',this.value)" type="radio" id="mul_c3_<?php echo $num_row; ?>" class="with-gap radio-col-teal radio_answer <?php echo $num_row; ?>" value="mul_c3" <?php if($value_ques['tc_answer']=="mul_c3"){echo "checked";} ?>/>
                                        <label <?php if($qiz['quiz_type']=="2"&&$value_ques['tc_answer']!=""&&$qiz['quiz_answer']=="1"&&$value_ques['qiz_status']!="1"&&$value_ques['qiz_status']!="2"&&in_array('mul_c3', $arr_answer)&&$value_ques['tc_answer']!='mul_c3'){ ?>style="color:#c0392b;"<?php } ?> for="mul_c3_<?php echo $num_row; ?>"><b><?php if($lang=="thai"){echo $value_ques['mul_c3_th'];}else{echo $value_ques['mul_c3_en'];} ?></b></label><br>
                                        <?php } ?>
                                        <?php if($value_ques['mul_c4_th']!=""||$value_ques['mul_c4_en']!=""){ ?>
                                        <input name="tc_answer_input_<?php echo $num_row; ?>" onclick="updateinput('<?php echo $num_row; ?>',this.value)" type="radio" id="mul_c4_<?php echo $num_row; ?>" class="with-gap radio-col-teal radio_answer <?php echo $num_row; ?>" value="mul_c4" <?php if($value_ques['tc_answer']=="mul_c4"){echo "checked";} ?>/>
                                        <label <?php if($qiz['quiz_type']=="2"&&$value_ques['tc_answer']!=""&&$qiz['quiz_answer']=="1"&&$value_ques['qiz_status']!="1"&&$value_ques['qiz_status']!="2"&&in_array('mul_c4', $arr_answer)&&$value_ques['tc_answer']!='mul_c4'){ ?>style="color:#c0392b;"<?php } ?> for="mul_c4_<?php echo $num_row; ?>"><b><?php if($lang=="thai"){echo $value_ques['mul_c4_th'];}else{echo $value_ques['mul_c4_en'];} ?></b></label><br>
                                        <?php } ?>
                                        <?php if($value_ques['mul_c5_th']!=""||$value_ques['mul_c5_en']!=""){ ?>
                                        <input name="tc_answer_input_<?php echo $num_row; ?>" onclick="updateinput('<?php echo $num_row; ?>',this.value)" type="radio" id="mul_c5_<?php echo $num_row; ?>" class="with-gap radio-col-teal radio_answer <?php echo $num_row; ?>" value="mul_c5" <?php if($value_ques['tc_answer']=="mul_c5"){echo "checked";} ?>/>
                                        <label <?php if($qiz['quiz_type']=="2"&&$value_ques['tc_answer']!=""&&$qiz['quiz_answer']=="1"&&$value_ques['qiz_status']!="1"&&$value_ques['qiz_status']!="2"&&in_array('mul_c5', $arr_answer)&&$value_ques['tc_answer']!='mul_c5'){ ?>style="color:#c0392b;"<?php } ?> for="mul_c5_<?php echo $num_row; ?>"><b><?php if($lang=="thai"){echo $value_ques['mul_c5_th'];}else{echo $value_ques['mul_c5_en'];} ?></b></label><br>
                                        <?php } ?>
                                        <input type="hidden" id="tc_answer_<?php echo $num_row; ?>" name="tc_answer_<?php echo $num_row; ?>" value="<?php echo $value_ques['tc_answer']; ?>">
                                    <?php }else{ ?>
                                        <textarea class="form-control radio_answer" name="tc_answer_<?php echo $num_row; ?>" id="tc_answer_<?php echo $num_row; ?>" rows="4"><?php echo $value_ques['tc_answer']; ?></textarea>
                                    <?php } $num_row++;?>
                                </div>
                            </div>
                        </div>
                    <?php }  ?>
                    <?php if(countArray($question)>0){ ?>
                        <div class="col-md-12 card card-body" id="div_btn" align="right" style="color:#ffffff;">
                            <div class="row">
                                <?php if($qiz['quiz_type']!="1"){ ?>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-block btn-success" onclick="onBackCourse()" id="btn_backtocourse" name="btn_backtocourse"><i class="mdi mdi-keyboard-return"></i> <?php echo label('backToCourse'); ?></button>
                                </div>
                                <div class="col-md-3">
                                    
                                </div>
                                <?php }else{ ?>
                                <div class="col-md-6">
                                    
                                </div>
                                <?php } ?>
                                <div class="col-md-3 div_button">
                                    <button type="button" class="btn btn-block save_btn" onclick="onclick_saveall()" id="bt_saveAnswer" name="bt_save"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                                </div>
                                <div class="col-md-3 div_button">
                                    <button type="button" class="btn btn-block sent_btn" onclick="sentans()" id="bt_sendAnswer" name="bt_sent"><i class="mdi mdi-checkbox-marked-circle-outline"></i> <?php echo label('sendAnswer'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php   }else{ 
                                if($qiz['quiz_type']=="2"){
                    ?>
                        <div class="col-md-12 card card-body">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-block btn-success" onclick="onBackCourse()" id="btn_backtocourse" name="btn_backtocourse"><i class="mdi mdi-keyboard-return"></i> <?php echo label('backToCourse'); ?></button>
                                </div>
                        </div>
                    <?php       }
                            } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="element_onsave" id="div_button_save">
          <button type="button" class="btn save_btn" id="btn_savemobile" onclick="onclick_saveall()"><i class="mdi mdi-content-save" style="font-size: 18px;"></i> <?php echo label('saveR'); ?></button>
        </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <!--morris JavaScript --> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.js"></script> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script> 
    <!--c3 JavaScript --> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/d3/d3.min.js"></script> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/c3-master/c3.min.js"></script> 
    
    <?php $this->load->view('frontend/inc/inc_pretestjs.php'); ?>
    
</body>

</html>