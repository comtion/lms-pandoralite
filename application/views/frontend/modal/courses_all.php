<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      .test[style] {
          padding-right:0 !important;
      }
      .test.modal-open {
          overflow: auto;
      }    
      .modal {
          padding-right: 0px !important;
      }
      .text-wrap{
        white-space:normal;overflow-wrap: anywhere;
      }
      .circle {
        width: 200px;
          margin: 6px 20px 20px;
          display: inline-block;
          position: relative;
          text-align: center;
        vertical-align: top;
        strong {
          position: absolute;
          top: 70px;
          left: 0;
          width: 100%;
          text-align: center;
          line-height: 45px;
          font-size: 43px;
        }
      }
    </style>

    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- <link href="<?php echo REAL_PATH; ?>/assets/css/progressbar_circular.css" rel="stylesheet"> -->
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
                
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                    </div>
                </div>  
                

                <div class="row col-12 page-titles" id="div_maincourse">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('createcourse'); ?></button>
                        <?php } ?>
                        <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                  <label for="com_id_search"><?php echo label('sv_b_com_name'); ?>: </label>
                                  <select class="form-control select2" id="com_id_search" name="com_id_search" style="width: 100%;">
                                    <?php   if(countArray($company_arr)>0){ ?>
                                                <optgroup label="<?php echo label('sv_b_select_com_name'); ?>">
                                        <?php   $numloop = 1;
                                                foreach ($company_arr as $key_com => $value_com) { ?>
                                                    <option value="<?php echo $value_com['com_id']; ?>" <?php if($numloop==1){echo "selected";}$numloop++; ?>><?php echo $lang=="thai"?$value_com['com_name_th']:$value_com['com_name_eng']; ?></option>
                                        <?php   } ?>
                                                </optgroup>
                                    <?php   } ?>
                                  </select>
                            </div>
                        </div>
                        <?php }else{ ?>
                            <input type="hidden" id="com_id_search" name="com_id_search" value="<?php echo $com_id; ?>">
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                  <th style="min-width: 55px !important;"><center><?php echo label('manage'); ?></center></th>
                                  <th width="5%"></th>
                                  <!-- <th width="10%"><center><?php echo label('cosCode'); ?></center></th> -->
                                  <th width="15%"><center><?php echo label('ceCname'); ?></center></th>
                                  <th width="10%"><center><?php echo label('ceCforlang'); ?></center></th>
                                  <th width="10%"><center><?php echo label('da_approve_creator'); ?></center></th>
                            <?php if(in_array($user['ug_id'], array('1','2','6'))){ ?>
                                  <th width="15%"><center><?php echo label('com_name'); ?></center></th>
                            <?php } ?>
                                  <th width="10%"><center><?php echo label('approval_status'); ?></center></th>
                                  <th width="10%"><center><?php echo label('d_approvedate'); ?></center></th>
                                  <th width="10%"><center><?php echo label('d_approver'); ?></center></th>
                                  <th width="5%"><center><?php echo label('status'); ?></center></th>
                                  <th width="10%"><center><?php echo label('dateMod'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button> = <b><?php echo label('sample_course'); ?></b> , <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-note-multiple"></i></button> = <b><?php echo label('ceDetailCourse'); ?></b><?php if($user['ug_approve']=="1"){ ?> , <button type="button" class="btn btn-secondary btn-xs active"><i class="mdi mdi-alert text-warning"></i></button> = <b><?php echo label('d_waitapprove'); ?></b><?php } ?><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>

                <div class="row col-12 page-titles" id="div_detailofcourse" style="display: none;">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="row">
                          <div class="col-md-4" align="left">
                              <button class="btn btn-outline-info float-left" onclick="display_style('div_detailofcourse','div_maincourse')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous_courses'); ?></button>
                          </div>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="ribbon-wrapper card">
                              <div class="ribbon ribbon-danger"><i class="mdi mdi-note-multiple"></i> <?php echo label('ceDetailCourse'); ?> : <span id="txtname_cosdetail"></span></div>
                              <div class="ribbon-content">
                                  <ul class="nav nav-tabs" role="tablist">
                                      <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#period_and_permission" role="tab" title="<?php echo label('period_and_permission'); ?>" onclick="run_create_pp()"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('period_and_permission'); ?></span></a> </li>
                                      <li class="nav-item" id="li_lesson"> <a class="nav-link" data-toggle="tab" onclick="display_disable('div_create_lesson','div_lesson')" href="#lesson" role="tab" title="<?php echo label('lesson'); ?>"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('lesson'); ?></span></a> </li>
                                      <li class="nav-item" id="li_quiz"> <a class="nav-link" data-toggle="tab" onclick="display_disable('div_create_quiz','div_quiz')" href="#quiz" role="tab" title="<?php echo label('quiz'); ?>"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('quiz'); ?></span></a> </li>
                                      <li class="nav-item" id="li_survey"> <a class="nav-link" data-toggle="tab" onclick="display_disable('div_create_survey','div_survey')" href="#survey" role="tab" title="<?php echo label('survey'); ?>"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('survey'); ?></span></a> </li>
                                      <li class="nav-item" id="li_enroll"> <a class="nav-link" data-toggle="tab" onclick="display_disable('div_enroll_cancel','div_enroll_main')" href="#enroll" role="tab" title="<?php echo label('student_enroll'); ?>"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('student_enroll'); ?></span></a> </li>
                                      <!-- <li class="nav-item" id="li_videocourse"> <a class="nav-link" data-toggle="tab" href="#videocourse" onclick="page_videocourse()" role="tab" title="<?php echo label('video_course'); ?>"><span class="hidden-sm-up"><i class="fas fa-file-video"></i></span> <span class="hidden-xs-down"><?php echo label('video_course'); ?></span></a> </li> -->
                                      <li class="nav-item" id="nav-item_document"> <a class="nav-link" data-toggle="tab" href="#document" onclick="clicktabdoccos()" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-file-document"></i></span> <span class="hidden-xs-down"><?php echo label('lesson_file'); ?></span></a> </li>
                                  </ul>
                                  <div class="tab-content tabcontent-border">
                                    <?php $this->load->view('frontend/tab/tab_course.php'); ?>
                                    
                                  </div>
                              </div>
                        </div>

                    </div>
                  </div>
                </div>

                <div class="row col-12 page-titles" id="div_demoofcourse" style="display: none;">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="ribbon-wrapper card">
                          <div class="ribbon ribbon-danger"><i class="mdi mdi-view-dashboard"></i> <?php echo label('sample_course'); ?> : <span id="txtname_cosdemo"></span></div>
                          <div class="ribbon-content">
                              <div class="jumbotron">

                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>

            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

      <div id="myModal_process" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body" align="center" style="max-height:300px;">

                      <div class="circle" id="circle-b">
                        <strong></strong>
                      </div>
                                          
                <!-- <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('please_wait'); ?></h3> -->
              </div>
            </div>
        </div>
      </div>
      
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>

    <?php if($lang=="thai"){ ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
    <?php } ?>
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <script type="text/javascript">
    </script>
    <?php $this->load->view('frontend/java/course_java.php'); ?>
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data_main(0);
          $('#com_id').val(com_id);
        });
        function run_create_pp(){
            var cos_id = $('#course_id_pp').val();
              $.ajax({
                  url:"<?=base_url()?>index.php/querydata/query_coursemain",
                  method:"POST",
                  data:{cos_id:cos_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $("#period_and_permission_form").find("input,select,textarea,button").prop("disabled",false);
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                        $("#period_and_permission_form").find("input,select,textarea,button").prop("disabled",true);
                        console.log('224');
                        }
                        <?php } ?>
                        $('#div_create_pp').show();
                         $.ajax({
                              url: '<?=base_url()?>index.php/querydata/permission_course',
                              type: 'POST',
                              data:{course_id:cos_id,cosde_id:data.cosde_id},
                              success: function(data_permission){
                                
                                $('#permission_div').html(data_permission);
                              }
                        });
                        from = $('#date_start').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                format: 'dd/mm/yyyy',
                                autoclose: true
                        })
                        /*.on('changeDate', function (selected) {
                            $('#date_end').val('');
                            $('#date_start').datepicker("update", selected.date);
                                 to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                        format: 'dd/mm/yyyy',
                                        autoclose: true
                                }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                                        var res_date = date_val.split("-");
                                        maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                        $('#date_start').datepicker('setEndDate', maxDate);
                                    });
                        });*/
                        to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                            format: 'dd/mm/yyyy',
                            autoclose: true
                        })
                        /*.on('changeDate', function (selected) {
                            $('#date_end').datepicker("update", selected.date);
                            var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                            var date_val = moment(maxDate).format('YYYY-MM-DD');
                            var res_date = date_val.split("-");
                            maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                            $('#date_start').datepicker('setEndDate', maxDate);
                        });*/
                        if(data.isData_period=="1"){
                          $('#cosde_id').val(data.cosde_id);
                          $('#operation_pp').val("Edit");
                          $.ajax({
                                url:"<?=base_url()?>index.php/querydata/update_course_detail_data",
                                method:"POST",
                                data:{cosde_id:data.cosde_id},
                                dataType:"json",
                                success:function(data_pp)
                                {
                                      if(data_pp.cosde_status=="1"){
                                        $("#cosde_status").attr("checked", true);
                                      }else{
                                        $("#cosde_status").attr("checked", false);
                                      }
                                      setCoursePeriodInputValues(data_pp);
                                      $('#time_start').val(data_pp.time_start);
                                      $('#time_end').val(data_pp.time_end);
                                      $('#get_point').val(data_pp.get_point);
                                      $('#point_redeem').val(data_pp.point_redeem);
                                      if(data_pp.date_start!=""){
                                        $('#date_start').datepicker('setStartDate', data_pp.date_start_condition || data_pp.date_start);
                                        $('#date_end').datepicker('setStartDate', data_pp.date_start_condition || data_pp.date_start);
                                      }
                                      if(data_pp.date_end!=""){
                                        $("#date_end").datepicker("update", data_pp.date_end);
                                      }
                                      setCoursePeriodInputValues(data_pp);
                                      setTimeout(function() {
                                        setCoursePeriodInputValues(data_pp);
                                      }, 100);
                                }
                          });
                        }else{
                          $('#operation_pp').val("Add");
                        }
                      }
                    });
        }

        function display_style(div_name,div_main){
            var x = document.getElementById(div_name);
            var y = document.getElementById(div_main);
            if (x.style.display === 'none') {
                x.style.display = '';
                y.style.display = 'none';
            } else {
                x.style.display = 'none';
                y.style.display = '';
                if(div_main == 'div_sv_survey_detail'){
                    document.getElementById('div_import_survey_detail').style.display = 'none';
                }
                if(div_name=='div_create_pp'){
                    var id = $('#course_id_pp').val();
                    var table = $('#myTable_pp').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data_detail(id,page_current);
                }

                if(div_name=='div_create_question'){
                    document.getElementById('div_question_mul').style.display = 'none';
                    document.getElementById('div_import_question').style.display = 'none';
                }

                if(div_name=='div_import_question'){
                    document.getElementById('div_create_question').style.display = 'none';
                }

                if(div_name=='div_quiz_detail'){
                    document.getElementById('div_question_check').style.display = 'none';
                    if(div_main=='div_quiz_main'){
                      document.getElementById('div_quiz').style.display = '';
                    }
                }
                if(div_name=="div_question_check"){
                  var cos_id = $('#course_id_pp').val();
                          fetch_data_quiz(cos_id,page_current);
                }
                if(div_name=="div_detailofcourse"){
                  fetch_data_main(0);
                }
            }
            topFunction();
        }

        function display_stylecancel(div_name,div_main){
            var x = document.getElementById(div_name);
            var y = document.getElementById(div_main);

            swal({
                title: '<?php echo label("confirm_tocancel"); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('m_ok'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
                if(isChk.value){
                    if (x.style.display === 'none') {
                        x.style.display = '';
                        y.style.display = 'none';
                    } else {
                        x.style.display = 'none';
                        y.style.display = '';
                        if(div_main == 'div_sv_survey_detail'){
                            document.getElementById('div_import_survey_detail').style.display = 'none';
                        }
                        if(div_name=='div_create_pp'){
                            var id = $('#course_id_pp').val();
                            var table = $('#myTable_pp').DataTable();
                                  var info = table.page.info();
                                  var length = info.pages;
                                  var page_current = info.page;
                                  fetch_data_detail(id,page_current);
                        }

                        if(div_name=='div_create_question'){
                            document.getElementById('div_question_mul').style.display = 'none';
                            document.getElementById('div_import_question').style.display = 'none';
                        }

                        if(div_name=='div_import_question'){
                            document.getElementById('div_create_question').style.display = 'none';
                        }

                        if(div_name=='div_quiz_detail'){
                            document.getElementById('div_question_check').style.display = 'none';
                            if(div_main=='div_quiz_main'){
                              document.getElementById('div_quiz').style.display = '';
                            }
                        }
                    }
                    topFunction();
                }
            })
        }
        $(document).on('click', '.demo_course', function(){
            var cos_id = $(this).attr("id");
            window.open("<?php echo REAL_PATH; ?>/managecourse/courses_demo/"+cos_id, "_self");
            /*document.getElementById('div_detailofcourse').style.display = "none";
            document.getElementById('div_demoofcourse').style.display = "";
            document.getElementById('div_maincourse').style.display = "none";

            $(window).on('load',function(){
                $('#select_lang_modal').modal('show');
            });

            $('#select_lang_modal').modal({backdrop: 'static', keyboard: false});
            $.ajax({
              url:"<?=base_url()?>index.php/querydata/query_coursemain",
              method:"POST",
              data:{cos_id:cos_id},
              dataType:"json",
              success:function(data)
              {
                  if(data.cos_pic!=""){
                    $("#course_cover").attr("src","<?php echo REAL_PATH;?>/uploads/course/"+data.cos_pic);
                  }else{
                    $("#course_cover").attr("src","<?php echo REAL_PATH;?>/images/logo.png");
                  }
                  if(data.isTH=="1"){
                      $('#course_lang').append('<option value="th" data-icon="flag-icon flag-icon-th" '+data.is_lang_user_th+'><?php echo label('thai'); ?></option>');
                  }
                  if(data.isENG=="1"){
                      $('#course_lang').append('<option value="eng" data-icon="flag-icon flag-icon-us" '+data.is_lang_user_eng+'><?php echo label('english'); ?></option>');
                  }
                  if(data.isJP=="1"){
                      $('#course_lang').append('<option value="jp" data-icon="flag-icon flag-icon-jp" '+data.is_lang_user_jp+'><?php echo label('japan'); ?></option>');
                  }
                  $('#course_lang').val(data.select_lang).trigger('change');
                  $("#txt_period_course").text(data.txt_period_course);
                  $('#txtname_cosdemo').text(data.cname_main);
                  $('#course_name').text(data.cname_main);
              }
            });*/
        });

        $(document).on('click', '.detail_course', function(){
            var cos_id = $(this).attr("id");
            document.getElementById('div_detailofcourse').style.display = "";
            document.getElementById('div_demoofcourse').style.display = "none";
            document.getElementById('div_maincourse').style.display = "none";
            $('.nav-tabs a[href="#period_and_permission"]').tab('show');
            $('#course_id_pp').val(cos_id);
            $('#course_id_student').val(cos_id);
            fetch_data_detail(cos_id);
            fetch_data_document_cos(cos_id,0);
            $.ajax({
              url:"<?=base_url()?>index.php/querydata/query_coursemain",
              method:"POST",
              data:{cos_id:cos_id},
              dataType:"json",
              success:function(data)
              {

                        $("#period_and_permission").find("input,select,textarea,button").prop("disabled",false);
                        $("#permission_div").find("input,select,textarea,button").prop("disabled",false);
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                        $("#period_and_permission").find("input,select,textarea,button").prop("disabled",true);
                        $("#permission_div").find("input,select,textarea,button").prop("disabled",true);
                        }
                        <?php } ?>
                     $.ajax({
                          url: '<?=base_url()?>index.php/querydata/permission_course',
                          type: 'POST',
                          data:{course_id:cos_id,cosde_id:data.cosde_id},
                          success: function(data_permission){
                            
                            $('#permission_div').html(data_permission);
                          }
                    });
                    from = $('#date_start').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                            format: 'dd/mm/yyyy',
                            autoclose: true
                    }).on('changeDate', function (selected) {
                        $('#date_end').val('');
                        $('#date_start').datepicker("update", selected.date);
                             to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                    format: 'dd/mm/yyyy',
                                    autoclose: true
                            }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                    var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                    var date_val = moment(maxDate).format('YYYY-MM-DD');
                                    var res_date = date_val.split("-");
                                    maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                    $('#date_start').datepicker('setEndDate', maxDate);
                                });
                    });
                    var startDate = new Date();
                    $('#date_start').datepicker('setStartDate', startDate);
                    to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                        format: 'dd/mm/yyyy',
                        autoclose: true
                    }).on('changeDate', function (selected) {
                        $('#date_end').datepicker("update", selected.date);
                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                        var res_date = date_val.split("-");
                        maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                        $('#date_start').datepicker('setEndDate', maxDate);
                    });
                    $('#date_end').datepicker('setStartDate', startDate);
                    if(data.isData_period=="1"){
                      $('#cosde_id').val(data.cosde_id);
                      $('#operation_pp').val("Edit");
                      $.ajax({
                            url:"<?=base_url()?>index.php/querydata/update_course_detail_data",
                            method:"POST",
                            data:{cosde_id:data.cosde_id},
                            dataType:"json",
                            success:function(data_pp)
                            {
                                  if(data_pp.cosde_status=="1"){
                                    $("#cosde_status").attr("checked", true);
                                  }else{
                                    $("#cosde_status").attr("checked", false);
                                  }
                                  setCoursePeriodInputValues(data_pp);
                                  $('#time_start').val(data_pp.time_start);
                                  $('#time_end').val(data_pp.time_end);
                                  $('#get_point').val(data_pp.get_point);
                                  $('#point_redeem').val(data_pp.point_redeem);
                                  if(data_pp.date_start!=""){
                                    if(data_pp.date_start_condition!=""){
                                    $('#date_start').datepicker('setStartDate', data_pp.date_start_condition);
                                    $('#date_end').datepicker('setStartDate', data_pp.date_start_condition);
                                    }else{
                                    $('#date_start').datepicker('setStartDate', data_pp.date_start);
                                    $('#date_end').datepicker('setStartDate', data_pp.date_start);
                                    }
                                  }
                                  if(data_pp.date_end!=""){
                                    $("#date_end").datepicker("update", data_pp.date_end);
                                  }
                                  setCoursePeriodInputValues(data_pp);
                                  setTimeout(function() {
                                    setCoursePeriodInputValues(data_pp);
                                  }, 100);
                            }
                      });
                    }else{
                      $('#operation_pp').val("Add");
                    }
                    $.ajax({
                      url:"<?=base_url()?>index.php/coursetype/update_coursetype_data",
                      method:"POST",
                      data:{tc_id_update:data.tc_id},
                      dataType:"json",
                      success:function(datatype)
                      {
                        if(datatype.tc_lesson=="0"){
                            document.getElementById('li_lesson').style.display = 'none';
                        }else{
                            document.getElementById('li_lesson').style.display = '';
                        }
                        if(datatype.tc_pretest=="0"){
                            document.getElementById('li_quiz').style.display = 'none';
                        }else{
                            document.getElementById('li_quiz').style.display = '';
                        }
                        if(datatype.tc_questionnaire=="0"){
                            document.getElementById('li_survey').style.display = 'none';
                        }else{
                            document.getElementById('li_survey').style.display = '';
                        }
                        if(datatype.tc_student_enroll=="0"){
                            document.getElementById('li_enroll').style.display = 'none';
                        }else{
                            document.getElementById('li_enroll').style.display = '';
                        }
                        if(datatype.tc_doccos=="0"){
                            document.getElementById('nav-item_document').style.display = 'none';
                        }else{
                            document.getElementById('nav-item_document').style.display = '';
                        }
                      }
                    });
                  $('#txtname_cosdetail').text(data.cname_main);
                  if(data.isTH=="1"){
                      $('.input_cosv_th').show();
                      $('#fil_lang').append('<option value="th"><?php echo label('thai'); ?></option>');
                      $('#cosv_lang').append('<option value="th"><?php echo label('thai'); ?></option>');
                  }
                  if(data.isENG=="1"){
                      $('.input_cosv_eng').show();
                      $('#fil_lang').append('<option value="eng"><?php echo label('english'); ?></option>');
                      $('#cosv_lang').append('<option value="eng"><?php echo label('english'); ?></option>');
                  }
                  if(data.isJP=="1"){
                      $('.input_cosv_jp').show();
                      $('#fil_lang').append('<option value="jp"><?php echo label('japan'); ?></option>');
                      $('#cosv_lang').append('<option value="jp"><?php echo label('japan'); ?></option>');
                  }
                  $("#fil_lang").val($("#fil_lang option:first").val());
                  $("#cosv_lang").val($("#cosv_lang option:first").val());
              }
            });
        });


        function create_div(div_name,div_main,form_name){
            document.getElementById(div_name).style.display = '';
            document.getElementById(div_main).style.display = 'none';
            var cos_id = $('#course_id_pp').val();
            var qiz_id = $('#qiz_id_question_import').val();
            $('#'+form_name)[0].reset();
            $('#course_id_pp').val(cos_id);
            $('#course_id_lesson').val(cos_id);
            $('#course_id_quiz').val(cos_id);
            $('#course_id_survey').val(cos_id);
            $('#course_id_cosv').val(cos_id);
            if(form_name=="period_and_permission_form"){
                var cos_id = $('#course_id_pp').val();
                $('#txthead_period').text('<?php echo label("create_period_and_permission"); ?>');
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/permission_course',
                      type: 'POST',
                      data:{cos_id:cos_id,cosde_id:''},
                      success: function(data){
                        
                        $('#permission_div').html(data);
                      }
                });
                $('#operation_pp').val("Add");

                $(function () {
                  from = $('#date_start').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#date_end').val('');
                      $('#date_start').datepicker("update", selected.date);
                           to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#date_start').datepicker('setEndDate', maxDate);
                              });
                  });
                   to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#date_end').datepicker("update", selected.date);
                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                          var res_date = date_val.split("-");
                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                          console.log(maxDate,selected.date.valueOf());
                          $('#date_start').datepicker('setEndDate', maxDate);
                      });
                })

                //$("#date_start").datepicker("update", '');
                //$("#date_end").datepicker("update", '');
            }else if(form_name=="lesson_form"){
                $('#tb_document').hide();
                $('#tb_document_body').html('');
                $('#operation_lesson').val("Add");
                document.getElementById('div_media').style.display = '';
                document.getElementById('div_scorm').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = 'none';
                document.getElementById('div_multifile_url').style.display = 'none';
                document.getElementById('div_multifile_upload_file').style.display = 'none';
                val_lang('0','input_les_th','les_name_','th');
                val_lang('0','input_les_eng','les_name_','eng');
                val_lang('0','input_les_jp','les_name_','jp');
                document.getElementById("les_name_th").required = false;
                document.getElementById("les_name_eng").required = false;
                document.getElementById("les_name_jp").required = false;
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_les_th','les_name_','th');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_les_eng','les_name_','eng');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_les_jp','les_name_','jp');
                          }
                        }
                        $('#les_lang').val(data.val_lang);
                          //$('#les_lang').html(data);
                          //$('#les_lang').val($('#les_lang option:first-child').val()).trigger('change');
                      }
                });
                textarea_tinymce('les_info_th','1');
                textarea_tinymce('les_info_eng','1');
                textarea_tinymce('les_info_jp','1');
                $('#txthead_lesson').text('<?php echo label("create_lesson"); ?>');
                $('#txt_scormoriginal').text('');

                $(function () {
                  from = $('#date_start_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#date_end_les').datepicker('setStartDate', selected.date);
                      $("#date_end_les").datepicker( "setDate", selected.date);
                      /*$('#date_end_les').val('');
                      $('#date_start_les').datepicker("update", selected.date);
                           to = $('#date_end_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#date_start_les').datepicker('setEndDate', maxDate);
                              });*/
                  });
                   to = $('#date_end_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                          //$('#date_start_les').datepicker('setEndDate', selected.date);
                  });
                })
                $.ajax({
                    url: '<?=base_url()?>index.php/querydata/query_course_detail_data',
                    type: 'POST',
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success: function(data){
                      if(data.isData=="1"){
                          var start_date = data.date_start_var.split("-");
                          var StartDate = start_date[2]+"/"+start_date[1]+"/"+(parseInt(start_date[0]));
                          var end_date = data.date_end_var.split("-");
                          var EndDate = end_date[2]+"/"+end_date[1]+"/"+(parseInt(end_date[0]));
                          $('#date_start_les').datepicker('setStartDate', StartDate);
                          $('#date_end_les').datepicker('setStartDate', StartDate);
                          $('#date_end_les').datepicker('setEndDate', EndDate);
                          $('#date_start_les').datepicker('setEndDate', EndDate);
                          $( "#date_start_les" ).datepicker( "setDate", StartDate);
                          $( "#date_end_les" ).datepicker( "setDate", EndDate);
                      }else{                              
                              var startDate = new Date();
                              $('#date_start_les').datepicker('setStartDate', startDate);
                              $('#date_end_les').datepicker('setStartDate', startDate);
                      }
                    }
                  });


                clear_dropify('media_file');
                clear_dropify('thumbnail_med');
                //clear_dropify('input-file-now-custom-document');
                clear_dropify('scorm_file');
                $('table#myTable_document tr.row_document').remove();
                //fetch_data_media('',0);
            }else if(form_name=="videocourse_form"){
                $('#operation_cosv').val("Add");
                document.getElementById('div_multifile_url_videocourse').style.display = '';
                document.getElementById('div_multifile_upload_file_videocourse').style.display = 'none';
            }else if(form_name=="lesson_order_form"){
                $('#txthead_sortlesson'). text('<?php echo label("edit_lesson_sequences"); ?>');
                $('#operation_lesson_order').val("Add");
                document.getElementById('div_create_lesson').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = '';

                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      success: function(data){
                          $('#les_lang_sort').html(data);
                          $('#les_lang_sort').val($('#les_lang_sort option:first-child').val()).trigger('change');
                          var les_lang = $('#les_lang_sort option:first-child').val();
                          reload_sortlesson(cos_id,les_lang);
                      }
                });
            }else if(form_name=="quiz_form"){
                $('#txt_totalquiz').text('');

                $(".div_lastquiz").removeClass("col-md-6");
                $(".div_lastquiz").addClass("col-md-4");
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,quiz_lang:''},
                      success: function(data_lang){
                          $('#quiz_lang').html(data_lang);
                          $('#quiz_lang').val($('#quiz_lang option:first-child').val()).trigger('change');
                          var quiz_lang = $('#quiz_lang').val();

                          $.ajax({
                            url:"<?=base_url()?>index.php/querydata/query_course",
                            method:"POST",
                            data:{cos_id:cos_id},
                            dataType:"json",
                            success:function(data_cos)
                            {
                              $.ajax({
                                url: '<?=base_url()?>index.php/workgroup/select_qize',
                                type: 'POST',
                                data:{com_id:data_cos.com_id,quiz_lang:data_cos.cos_lang},
                                success: function(data){
                                  
                                  $('#qize_id').html(data);
                                }
                              });
                            }
                          });
                      }
                });
                val_lang('0','input_quiz_th','quiz_name_','th');
                val_lang('0','input_quiz_eng','quiz_name_','eng');
                val_lang('0','input_quiz_jp','quiz_name_','jp');
                document.getElementById("quiz_name_th").required = false;
                document.getElementById("quiz_name_eng").required = false;
                document.getElementById("quiz_name_jp").required = false;
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_quiz_th','quiz_name_','th');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_quiz_eng','quiz_name_','eng');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_quiz_jp','quiz_name_','jp');
                          }
                        }
                        $('#quiz_lang').val(data.val_lang);
                      }
                });
                textarea_tinymce('quiz_info_th','1');
                textarea_tinymce('quiz_info_eng','1');
                textarea_tinymce('quiz_info_jp','1');
                /**/
                document.getElementById("quiz_numofshown").readOnly = true;
                document.getElementById("quiz_numofshown").max = "";
                document.getElementById('div_template_qize').style.display = '';
                $('#operation_quiz').val("Add");/*
                $("#period_open").datepicker("update", '');
                $("#period_end").datepicker("update", '');*/

                $('#txthead_quiz').text('<?php echo label("create_quiz"); ?>');

                $(function () {
                  from = $('#period_open').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#period_end').datepicker('setStartDate', selected.date);
                      $("#period_end").datepicker( "setDate", selected.date);
                      /*$('#date_end_les').val('');
                      $('#date_start_les').datepicker("update", selected.date);
                           to = $('#date_end_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#date_start_les').datepicker('setEndDate', maxDate);
                              });*/
                  });
                  /*.on('changeDate', function (selected) {
                      $('#period_end').val('');
                      $('#period_open').datepicker("update", selected.date);
                           to = $('#period_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#period_open').datepicker('setEndDate', maxDate);
                              });
                  });*/
                   to = $('#period_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  })
                   /*.on('changeDate', function (selected) {
                      $('#period_end').datepicker("update", selected.date);
                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                          var res_date = date_val.split("-");
                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                          console.log(maxDate,selected.date.valueOf());
                          $('#period_open').datepicker('setEndDate', maxDate);
                      });*/
                })

                $.ajax({
                    url: '<?=base_url()?>index.php/querydata/query_course_detail_data',
                    type: 'POST',
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success: function(data){
                      if(data.isData=="1"){
                          var start_date = data.date_start_var.split("-");
                          var StartDate = start_date[2]+"/"+start_date[1]+"/"+(parseInt(start_date[0]));
                          var end_date = data.date_end_var.split("-");
                          var EndDate = end_date[2]+"/"+end_date[1]+"/"+(parseInt(end_date[0]));
                          $('#period_open').datepicker('setStartDate', StartDate);
                          $('#period_end').datepicker('setStartDate', StartDate);
                          $('#period_end').datepicker('setEndDate', EndDate);
                          $('#period_open').datepicker('setEndDate', EndDate);
                          $( "#period_open" ).datepicker( "setDate", StartDate);
                          $( "#period_end" ).datepicker( "setDate", EndDate);
                      }else{                              
                              var startDate = new Date();
                              $('#period_open').datepicker('setStartDate', startDate);
                              $('#period_end').datepicker('setStartDate', startDate);
                      }
                    }
                  });
                display_quiz('div_answer');
                readonly_quiz('quiz_limitval');
            }else if(form_name=="survey_form"){
                $('#operation_survey').val("Add");
                $('#txthead_survey').text('<?php echo label("create_survey"); ?>');
                /*$.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      success: function(data){
                          $('#sv_lang').html(data);
                          $('#sv_lang').val($('#sv_lang option:first-child').val()).trigger('change');
                      }
                });*/

               /* $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/recheckquestionnaire',
                      type: 'POST',
                      data:{qn_id:''},
                      success: function(data){
                          $('#qn_id').html(data);
                      }
                });*/
                $(function () {
                  from = $('#survey_open').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#survey_end').datepicker('setStartDate', selected.date);
                      $("#survey_end").datepicker( "setDate", selected.date);
                      /*$('#date_end_les').val('');
                      $('#date_start_les').datepicker("update", selected.date);
                           to = $('#date_end_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#date_start_les').datepicker('setEndDate', maxDate);
                              });*/
                  });
                  /*.on('changeDate', function (selected) {
                      $('#survey_end').val('');
                      $('#survey_open').datepicker("update", selected.date);
                           to = $('#survey_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#survey_open').datepicker('setEndDate', maxDate);
                              });
                  });*/
                   to = $('#survey_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  })
                   /*.on('changeDate', function (selected) {
                      $('#survey_end').datepicker("update", selected.date);
                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                          var res_date = date_val.split("-");
                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                          $('#survey_open').datepicker('setEndDate', maxDate);
                      });*/
                })


                $.ajax({
                    url: '<?=base_url()?>index.php/querydata/query_course_detail_data',
                    type: 'POST',
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success: function(data){
                      if(data.isData=="1"){
                          var start_date = data.date_start_var.split("-");
                          var StartDate = start_date[2]+"/"+start_date[1]+"/"+(parseInt(start_date[0]));
                          var end_date = data.date_end_var.split("-");
                          var EndDate = end_date[2]+"/"+end_date[1]+"/"+(parseInt(end_date[0]));
                          $('#survey_open').datepicker('setStartDate', StartDate);
                          $('#survey_end').datepicker('setStartDate', StartDate);
                          $('#survey_end').datepicker('setEndDate', EndDate);
                          $('#survey_open').datepicker('setEndDate', EndDate);
                          $( "#survey_open" ).datepicker( "setDate", StartDate);
                          $( "#survey_end" ).datepicker( "setDate", EndDate);
                      }else{                              
                              var startDate = new Date();
                              $('#survey_open').datepicker('setStartDate', startDate);
                              $('#survey_end').datepicker('setStartDate', startDate);
                      }
                    }
                  });

                val_lang('0','input_survey_th','sv_title_','th');
                val_lang('0','input_survey_eng','sv_title_','eng');
                val_lang('0','input_survey_jp','sv_title_','jp');
                document.getElementById("sv_title_th").required = false;
                document.getElementById("sv_title_eng").required = false;
                document.getElementById("sv_title_jp").required = false;
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_survey_th','sv_title_','th');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_survey_eng','sv_title_','eng');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_survey_jp','sv_title_','jp');
                          }
                        }

                        $('#sv_lang').val(data.val_lang);

                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckquestionnaire',
                                      type: 'POST',
                                      data:{qn_id:'',cos_lang:data.val_lang,cos_id:cos_id},
                                      success: function(dataqn){
                                        
                                        $('#qn_id').html(dataqn);
                                      }
                                });
                      }
                });
            }else if(form_name=="survey_detail_form"){
                $('#operation_survey_detail').val("Add");
                $('#txthead_survey_detail').text('<?php echo label("create_question"); ?>');
                document.getElementById('div_import_survey_detail').style.display = 'none';
                val_lang('0','input_surveydetail_th','svde_heading_','th');
                val_lang('0','input_surveydetail_eng','svde_heading_','eng');
                val_lang('0','input_surveydetail_jp','svde_heading_','jp');
                document.getElementById('svde_heading_th').required = false;
                document.getElementById('svde_heading_eng').required = false;
                document.getElementById('svde_heading_jp').required = false;
                document.getElementById('svde_detail_th').required = false;
                document.getElementById('svde_detail_eng').required = false;
                document.getElementById('svde_detail_jp').required = false;
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_surveydetail_th','svde_heading_','th');
                            document.getElementById('svde_heading_th').required = true;
                            document.getElementById('svde_detail_th').required = true;
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_surveydetail_eng','svde_heading_','eng');
                            document.getElementById('svde_heading_eng').required = true;
                            document.getElementById('svde_detail_eng').required = true;
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_surveydetail_jp','svde_heading_','jp');
                            document.getElementById('svde_heading_jp').required = true;
                            document.getElementById('svde_detail_jp').required = true;
                          }
                        }
                      }
                });
            }else if(form_name=="survey_detail_import_form"){
                $('#operation_import_survey').val("Add");
                $('#result_import_survey').html('');
                document.getElementById('div_create_survey_detail').style.display = 'none';
                clear_dropify('file_import_survey');
                $('#txthead_import_survey_detail').text('<?php echo label("import_data_question"); ?>');
            }else if(form_name=="question_form"){
                var qiz_id = $('#qiz_id_question').val();

                $("#ques_type").val($("#ques_type option:first").val());
                $('#quiz_name_txt').text('<?php echo label("create_question"); ?>');
                $('#operation_question').val("Add");
                $("#mul_answer").val("");
                $("#mul_answer").trigger("change");
                //$('#ques_type').val('0');
                $('#cos_id_question').val(cos_id);
                clear_dropify('ques_hintimg');
                $('#ques_upload_required').prop('checked', false);
                $('#ques_upload_type').val('both');
                $('#ques_upload_max_mb').val('10');
                $('#ques_upload_note').val('');
                val_lang('0','input_ques_th','ques_name_','th');
                val_lang('0','input_ques_eng','ques_name_','eng');
                val_lang('0','input_ques_jp','ques_name_','jp');
                document.getElementById("ques_name_th").required = false;
                document.getElementById("ques_name_eng").required = false;
                document.getElementById("ques_name_jp").required = false;
                document.getElementById("mul_answer").required = false;
                $('#ques_name_th').val('');
                $('#ques_name_eng').val('');
                $('#ques_name_jp').val('');
                         
                $("#ques_type").html('');
                $("#ques_type").append('<option value="sa"><?php echo label('qt_sa'); ?></option>');
                $("#ques_type").append('<option value="sub"><?php echo label('qt_sub'); ?></option>');
                $("#ques_type").append('<option value="2choice"><?php echo label('qt_twoChoice'); ?></option>');
                $("#ques_type").append('<option value="multi"><?php echo label('qt_multi'); ?></option>');
                $.ajax({
                      url:"<?=base_url()?>index.php/querydata/update_quiz_detail_data",
                      method:"POST",
                      data:{qiz_id:qiz_id},
                      dataType:"json",
                      success:function(data)
                      {
                        if(data.quiz_random_choice=="1"||data.quiz_model=="1"||data.quiz_ishint=="1"){
                          $("#ques_type").val('2choice');
                          $("#ques_type option[value='sa']").remove();
                          $("#ques_type option[value='sub']").remove();
                          select_questype('2choice');
                        }
                      }
                });   
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_lesson',
                      type: 'POST',
                      data:{cos_id:cos_id,les_lang:''},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_ques_th','','th');
                            $('#ques_name_th').val('');
                            $('#ques_info_th').val('');
                            textarea_tinymce('ques_name_th','1');
                            textarea_tinymce('ques_info_th','1');
                            $(tinymce.get('ques_name_th').getBody()).html('');
                            $(tinymce.get('ques_info_th').getBody()).html('');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_ques_eng','','eng');
                            $('#ques_name_eng').val('');
                            $('#ques_info_eng').val('');
                            textarea_tinymce('ques_name_eng','1');
                            textarea_tinymce('ques_info_eng','1');
                            $(tinymce.get('ques_name_eng').getBody()).html('');
                            $(tinymce.get('ques_info_eng').getBody()).html('');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_ques_jp','','jp');
                            $('#ques_name_jp').val('');
                            $('#ques_info_jp').val('');
                            textarea_tinymce('ques_name_jp','1');
                            textarea_tinymce('ques_info_jp','1');
                            $(tinymce.get('ques_name_jp').getBody()).html('');
                            $(tinymce.get('ques_info_jp').getBody()).html('');
                          }
                        }
                      }
                });
                document.getElementById("div_question_hint").style.display = 'none';
                document.getElementById('div_question_mul').style.display = 'none';
                document.getElementById('div_import_question').style.display = 'none';
            }else if(form_name=="question_import_form"){
                $('#qiz_id_question_import').val(qiz_id);
                $('#operation_import_question').val("Add");
                $('#result_import_question').html('');
                document.getElementById('div_question_mul').style.display = 'none';
                document.getElementById('div_create_question').style.display = 'none';
            }
        }
        function reload_sortlesson(cos_id,les_lang){
                          $.ajax({
                                url: '<?=base_url()?>index.php/course/li_lesson_course',
                                type: 'POST',
                                data:{cos_id:cos_id,les_lang:les_lang},
                                success: function(data_load){
                                  
                                  $('#load_li_lesson').html(data_load);
                                }
                          });
        }
        function page_videocourse(){
            var cos_id = $('#course_id_pp').val();
            $('#course_id_cosv').val(cos_id);
            fetch_data_coursevideo(cos_id,0);
        }
        function display_disable(div_name,div_main){
            var cos_id = $('#course_id_pp').val();
            var x = document.getElementById(div_name);
            var y = document.getElementById(div_main);
                x.style.display = 'none';
                y.style.display = '';
            if(div_name=='div_create_lesson'){
                document.getElementById('div_lesson').style.display = '';
                document.getElementById('div_create_lesson').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = 'none';
                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/query_coursemain",
                    method:"POST",
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success:function(data)
                    {

                        <?php if($btn_update=="1"){ ?>
                          $('#edit_order_lesson').show();
                        <?php } ?>
                        <?php if($btn_add=="1"){ ?>
                          $('#add_lesson').show();
                        <?php } ?>
                        /*<?php if($btn_update=="1"||$btn_delete=="1"){ ?>
                          $('#col_lessson').show();
                        <?php } ?>*/
                        //$("#lesson").find("input,select,textarea,button").prop("disabled",false);
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                          //$('#col_lessson').hide();
                          $('#add_lesson').hide();
                          $('#edit_order_lesson').hide();
                        //$("#lesson").find("input,select,textarea,button").prop("disabled",true);
                        }
                        <?php } ?>
                        fetch_data_lesson(cos_id,0);
                    }
                });
            }
            if(div_name=='div_create_pp'){
              $.ajax({
              url:"<?=base_url()?>index.php/querydata/query_coursemain",
              method:"POST",
              data:{cos_id:cos_id},
              dataType:"json",
              success:function(data)
              {
                    $('#div_create_pp').show();
                     $.ajax({
                          url: '<?=base_url()?>index.php/querydata/permission_course',
                          type: 'POST',
                          data:{course_id:cos_id,cosde_id:data.cosde_id},
                          success: function(data_permission){
                            
                            $('#permission_div').html(data_permission);
                          }
                    });

                    from = $('#date_start').datepicker({
                            language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                            thaiyear: true,  
                            format: 'dd/mm/yyyy',
                            autoclose: true
                    }).on('changeDate', function (selected) {
                        $('#date_end').val('');
                        $('#date_start').datepicker("update", selected.date);
                             to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                    format: 'dd/mm/yyyy',
                                    autoclose: true
                            }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                    var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                    var date_val = moment(maxDate).format('YYYY-MM-DD');
                                    var res_date = date_val.split("-");
                                    maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                    console.log(maxDate,selected.date.valueOf());
                                    $('#date_start').datepicker('setEndDate', maxDate);
                                });
                    });
                    to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                        format: 'dd/mm/yyyy',
                        autoclose: true
                    }).on('changeDate', function (selected) {
                        $('#date_end').datepicker("update", selected.date);
                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                        var res_date = date_val.split("-");
                        maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                        $('#date_start').datepicker('setEndDate', maxDate);
                    });
                    if(data.isData_period=="1"){
                      $('#cosde_id').val(data.cosde_id);
                      $('#operation_pp').val("Edit");
                      $.ajax({
                            url:"<?=base_url()?>index.php/querydata/update_course_detail_data",
                            method:"POST",
                            data:{cosde_id:data.cosde_id},
                            dataType:"json",
                            success:function(data_pp)
                            {
                                  if(data_pp.cosde_status=="1"){
                                    $("#cosde_status").attr("checked", true);
                                  }else{
                                    $("#cosde_status").attr("checked", false);
                                  }
                                  $('#date_start_var').val(data_pp.date_start_var);
                                  $('#date_end_var').val(data_pp.date_end_var);
                                  $('#time_start').val(data_pp.time_start);
                                  $('#time_end').val(data_pp.time_end);
                                  $('#get_point').val(data_pp.get_point);
                                  $('#point_redeem').val(data_pp.point_redeem);
                                  if(data_pp.date_start!=""&&data_pp.date_end!=""){
                                    $("#date_start").datepicker("update", data_pp.date_start); 
                                    $("#date_end").datepicker("update", data_pp.date_end); 
                                  }else{
                                    $('#date_start').val('');
                                    $('#date_end').val('');
                                  }
                            }
                      });
                    }else{
                      $('#operation_pp').val("Add");
                    }
                  }
                });
            }
            if(div_name=='div_create_quiz'){
                fetch_data_quiz(cos_id,0);
                document.getElementById('div_quiz_main').style.display = '';
                document.getElementById('div_quiz_detail').style.display = 'none';
                document.getElementById('div_question_check').style.display = 'none';
                document.getElementById('div_import_question').style.display = 'none';
                
                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/query_coursemain",
                    method:"POST",
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success:function(data)
                    {

                    <?php if($btn_add=="1"){ ?>
                        $('#add_quiz').show();
                    <?php } ?>
                        //$("#quiz").find("input,select,textarea,button").prop("disabled",false);
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                        //$("#quiz").find("input,select,textarea,button").prop("disabled",true);
                        $('#add_quiz').hide();
                        }
                        <?php } ?>
                    }
                });
            }
            if(div_name=='div_create_survey'){
              fetch_data_survey(cos_id,0);
                document.getElementById('div_survey_main').style.display = '';
                document.getElementById('div_survey_detail').style.display = 'none';
                
                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/query_coursemain",
                    method:"POST",
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success:function(data)
                    {

                    <?php if($btn_add=="1"){ ?>
                        $('#add_survey').show();
                    <?php } ?>
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                        $('#add_survey').hide();
                        }
                        <?php } ?>
                    }
                });
            }
            if(div_main=='div_enroll_main'){
                document.getElementById('div_enroll_qiz').style.display = 'none';
                var cos_id = $('#course_id_pp').val();
                fetch_data_enroll(cos_id,0);
                $('.btn_add_lerner').prop('disabled', false);
                $('#result_import_student').html('');
                $('#myTable_enroll_qiz').DataTable().destroy();
                $('#myTable_enroll_qiz').on('error.dt', function(e, settings, techNote, message) {
                  notificationForDatatableError("myTable_enroll_qiz", message);
                }).DataTable({
                    "ajax": {
                        url : '<?=base_url()?>index.php/course/fetch_course_enroll_qiz/',
                        data : {
                          qiz_id:'',
                          lang: "<?php echo $lang; ?>"
                        },
                        type : 'GET'
                    },
                    <?php if($btn_print=="1"){?>
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'print'
                    ]
                    <?php } ?>
                });
                /*
                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/query_coursemain",
                    method:"POST",
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success:function(data)
                    {

                        $("#enroll").find("input,select,textarea,button").prop("disabled",false);
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                        $("#enroll").find("input,select,textarea,button").prop("disabled",true);
                        }
                        <?php } ?>
                    }
                });*/

                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/query_coursemain",
                    method:"POST",
                    data:{cos_id:cos_id},
                    dataType:"json",
                    success:function(data)
                    {
                        if(data.cos_approve=="1"){
                          if(data.isQiz==1){
                            $('.all_score').hide();
                          }else{
                            $('.all_score').show();
                          }
                        }else{
                            $('.all_score').hide();
                        }
                    <?php if($btn_add=="1"){ ?>
                        $('#check_importdata').show();
                    <?php } ?>
                        <?php if($user['u_id']!="1"){ ?>
                        if(data.cos_approve=="1"){
                          if(data.tc_courselearner=="1"){
                            if(data.isseatFull=="0"){
                            $('#check_importdata').show();
                            }else{
                            $('#check_importdata').hide();
                            }
                          }else{
                            $('#check_importdata').hide();
                          }
                        }
                        <?php } ?>
                    }
                });
                /*$.ajax({
                      url: '<?=base_url()?>index.php/course/rechkquizandstudent',
                      type: 'POST',
                      data:{cos_id:cos_id},
                      success: function(data){
                        
                        if(data == "1"){
                            document.getElementById('manage_quiz_div').style.display = '';
                        }else{
                            document.getElementById('manage_quiz_div').style.display = 'none';
                        }
                      }
                });*/
            }
              var com_id = $('#com_id_survey').val();
        }

    </script>
</body>

</html>
