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
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
          float: right;
          color: #ffffff;
          margin-right: 0px;
          margin-left: 4px; }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
          background: #398bf7;
          color: #ffffff;
          border-color: #398bf7; }

    </style>
    <style type="text/css">
    .scrollbar-deep-purple::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-deep-purple::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-deep-purple::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #512da8; }

      .scrollbar-cyan::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-cyan::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-cyan::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #00bcd4; }

      .scrollbar-dusty-grass::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-dusty-grass::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-dusty-grass::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-image: -webkit-linear-gradient(330deg, #d4fc79 0%, #96e6a1 100%);
      background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%); }

      .scrollbar-ripe-malinka::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-ripe-malinka::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-ripe-malinka::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-image: -webkit-linear-gradient(330deg, #f093fb 0%, #f5576c 100%);
      background-image: linear-gradient(120deg, #f093fb 0%, #f5576c 100%); }

      .bordered-deep-purple::-webkit-scrollbar-track {
      -webkit-box-shadow: none;
      border: 1px solid #512da8; }

      .bordered-deep-purple::-webkit-scrollbar-thumb {
      -webkit-box-shadow: none; }

      .bordered-cyan::-webkit-scrollbar-track {
      -webkit-box-shadow: none;
      border: 1px solid #00bcd4; }

      .bordered-cyan::-webkit-scrollbar-thumb {
      -webkit-box-shadow: none; }

      .square::-webkit-scrollbar-track {
      border-radius: 0 !important; }

      .square::-webkit-scrollbar-thumb {
      border-radius: 0 !important; }

      .thin::-webkit-scrollbar {
      width: 6px; }

      .example-1 {
      position: relative;
      overflow-y: scroll;
      height: 90px; }
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
                <div class="row col-12 page-titles">

                    <div class="row page-titles">
                      <div class="col-lg-6 col-md-12">
                        <div class="card"> <img class="card-img img-responsive" src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $courses['pic']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg'" alt="">
                        </div>
                      </div>
                      <!-- ============================================================== --> 
                      <!-- Activity widget find scss into widget folder--> 
                      <!-- ============================================================== -->
                      <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                      <input type="hidden" id="cos_id" name="cos_id" value="<?php echo $cos_id; ?>">

                      <div class="col-lg-6 col-md-12">
                          <div class="card card-body">
                            <div class="d-flex">
                              <h4 class="card-title"><span class="lstick"></span>Rating :</h4>
                              <div class="p-l-20">
                                <?php for ($i=1; $i <= intval($courses['cos_rating']); $i++) { ?>
                                  <i class="fa fa-star text-warning"></i>
                                <?php } ?>
                                <?php for ($i=1; $i <= (5-intval($courses['cos_rating'])); $i++) { ?>
                                  <i class="fa fa-star text-default"></i>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex">
                              <h4 class="card-title"><span class="lstick"></span><?php if($lang=="thai"){echo $courses['cname_th'];}else{echo $courses['cname_en'];} ?></h4>
                              <!-- <span class="badge badge-success">9</span> -->
                              <!--<div class="btn-group ml-auto m-t-10"> <a href="JavaScript:void(0)" class="icon-options-vertical link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void(0)">Export PDF</a> <a class="dropdown-item" href="javascript:void(0)">Export Excel</a> <a class="dropdown-item" href="javascript:void(0)">Send Report to Email</a> </div>
                              </div>-->
                            </div>
                            <div>
                              <?php foreach ($coursescg as $key_cg => $value_cg) { ?>
                              <button type="button" class="btn waves-effect waves-light btn-rounded btn-sm btn-info"><?php if($lang=="thai"){echo $value_cg['cgtitle_th'];}else{echo $value_cg['cgtitle_en'];} ?></button>
                              <?php } ?>
                              
                              <?php if($courses['cdesc_th']!=""||$courses['cdesc_en']!=""){ ?>
                              <hr>
                              <p onclick="onclickdetail()" style="cursor: pointer;"><i id="icondetail" class="fas fa-search"></i> <span id='txt_detail'><?php echo label('r_viewDetail'); ?></span></p>
                              <div id="detail_div" style="display: none;"><?php if($lang=="thai"){echo $courses['cdesc_th'];}else{echo $courses['cdesc_en'];} ?></div><hr>
                              <?php }else{ ?>
                                <br><br>
                              <?php } ?>
                              <a href="#" class="btn btn-success btn_permisssion"  data-toggle="modal" data-target="#modal-permisssion"> <?php echo label('setpermission'); ?></a> <a href="#" class="btn btn-success btn_quiz" data-toggle="modal" data-target="#modal-quiz"> <?php echo label('edit').label('quiz'); ?></a> <a href="#" class="btn btn-success btn_survey" data-toggle="modal" data-target="#modal-survey"> <?php echo label('edit').label('survey'); ?></a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <script type="text/javascript">
                        function onclickdetail(){
                          var chk = document.getElementById('detail_div');
                          if($('#detail_div').css('display') == 'none'){
                            chk.style.display = '';
                            $("#icondetail").removeClass("fa-search"); 
                            $("#icondetail").addClass("fa-window-close");
                            $('#txt_detail').html('<?php echo label('close_detail'); ?>');
                          }else{
                            console.log('212');
                            $("#icondetail").addClass("fa-search"); 
                            $("#icondetail").removeClass("fa-window-close");
                            $('#txt_detail').html('<?php echo label('r_viewDetail'); ?>');
                            document.getElementById('detail_div').style.display = 'none';
                          }
                        }
                      </script>
                      <div class="col-lg-6 col-md-12">
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex no-block">
                                  <div>
                                    <h3 class="card-title m-b-5"><span class="lstick"></span>Learning Subject Overview</h3>
                                  </div>
                                  <div class="ml-auto">
                                    <select class="custom-select b-0" onchange="onchange_month()" id="month_select" name="month_select">
                                      <?php 
                                            $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
                                            for ($i=0; $i < countArray($month_select); $i++) { 
                                              $month = $i;
                                              if($i<10){
                                                $month = "0".$i;
                                              }
                                      ?>
                                        <option value="<?php echo date('Y-m',strtotime($month_select[$i])); ?>"><?php if($lang=="thai"){echo $arrMonthThaiTextFull[intval(date('m',strtotime($month_select[$i])))]." ".(date('Y',strtotime($month_select[$i]))+543);}else{echo date('F Y',strtotime($month_select[$i]));} ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-theme stats-bar">
                                <div class="row">
                                  <div class="col-lg-4 col-md-4">
                                    <div class="p-20 active">
                                      <h6 class="text-white"><?php echo label('total_seat'); ?></h6>
                                      <h3 class="text-white m-b-0"><?php if(intval($courses['seat_count'])>0){echo number_format($courses['seat_count']);}else{ ?><i class="mdi mdi-infinity"></i><?php } ?></h3>
                                    </div>
                                  </div>
                                  <div class="col-lg-4 col-md-4">
                                    <div class="p-20">
                                      <h6 class="text-white"><?php echo label('registered_seats'); ?></h6>
                                      <h3 class="text-white m-b-0"><?php echo number_format($registered_seats); ?></h3>
                                    </div>
                                  </div>
                                  <div class="col-lg-4 col-md-4">
                                    <div class="p-20">
                                      <h6 class="text-white"><?php echo label('this_month'); ?></h6>
                                      <h3 class="text-white m-b-0" id="this_month"><?php echo number_format($this_month); ?></h3>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div id="sales-overview2" class="p-relative" style="height:330px;"></div>
                              </div>
                            </div>
                      </div>

                      <div class="col-lg-6 col-md-12">
                            <div class="card card-body">
                              <div class="d-flex no-block">
                                <div>
                                  <h4 class="card-title"><span class="lstick"></span>10 Best Score</h4>
                                </div>
                              </div>
                              <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr>
                                      <th align="center"><?php echo label('name'); ?></th>
                                      <th align="center"><?php echo label('r_position'); ?></th>
                                      <th align="center"><?php echo label('time_analytic'); ?></th>
                                      <th align="center"><?php echo label('score'); ?></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if(countArray($course_regis)>0){
                                            foreach ($course_regis as $keycos_reg => $valuecos_reg) { ?>
                                              <tr>
                                                <td><?php if($lang=="thai"){echo $valuecos_reg['fullname_th'];}else{echo $valuecos_reg['fullname_en'];} ?></td>
                                                <td><?php if($lang=="thai"){echo $valuecos_reg['ug_name_th'];}else{echo $valuecos_reg['ug_name_en'];} ?></td>
                                                <td><?php echo $valuecos_reg['time']; ?></td>
                                                <td><?php echo $valuecos_reg['cosen_score']; ?></td>
                                              </tr>
                                      <?php }
                                          }else{ ?>
                                            <tr>
                                              <td colspan="4" align="center"><?php echo label('wg_datanotfound'); ?></td>
                                            </tr>
                                    <?php } 
                                    ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                      </div>

                    </div>
                </div>

                <br><br>
            </div>
        </div>
    </div>


    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-permisssion" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><?php echo label('period_and_permission'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body ">
                                <div class="card-body row">
                                    <div class="col-md-12" align="right">
                                            <button name="add_period_and_permission" onclick="create_div('div_create_pp','div_pp','period_and_permission_form')" id="add_period_and_permission" class="btn btn-outline-info add_period_and_permission"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('period_and_permission'); ?></button>
                                    </div>
                                    <div id="div_create_pp" style="display: none;">
                                        <form  enctype="multipart/form-data" id="period_and_permission_form" name="period_and_permission_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                        <input type="hidden" id="cosde_id" name="cosde_id">
                                        <input type="hidden" id="operation_pp" name="operation_pp" value="Add">
                                        <input type="hidden" id="course_id_pp" name="course_id_pp">
                                        <div class="col-md-12 row" style="">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label text-right"><?php echo label('period'); ?></label>
                                                    <div class='input-group mb-3'>
                                                        <input type='text' id="daterange_period" name="daterange_period" class="form-control timeseconds" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <span class="ti-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="date_start_var" name="date_start_var">
                                                    <input type="hidden" id="date_end_var" name="date_end_var">
                                                    <!--<div class="input-group">
                                                        <input type="text" class="form-control" required name="date_start" id="date_start" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-info b-0 text-white"><?php echo label('to'); ?></span>
                                                        </div>
                                                        <input type="text" class="form-control" required name="date_end" id="date_end" />
                                                    </div>-->
                                                </div>
                                                <div class="form-group col-md-12 row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('point_redeem'); ?></label>
                                                        <input name="point_redeem" type="number" min="0" max="100"   step="0.01" pattern="[0123456789.]" class="form-control" id="point_redeem">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('get_point'); ?></label>
                                                        <input name="get_point" type="number" min="0" max="100"   step="0.01" pattern="[0123456789.]" class="form-control" id="get_point">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <hr>
                                                    <label class="control-label text-right"><?php echo label('permission'); ?></label>
                                                    <div id="permission_div">
                                                    </div>
                                                    <hr>
                                                </div>

                                                <div class="form-group col-md-6" align="center">
                                                    <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('saveR'); ?>" />
                                                </div>
                                                <div class="form-group col-md-6" align="center">
                                                    <button type="reset" class="btn btn-outline-danger btn-block" onclick="display_style('div_create_pp','div_pp')"><?php echo label('m_cancel'); ?></button>
                                                </div>
                                        </div>
                                        </form>
                                    </div>
                                    <div id="div_pp" class="col-md-12">
                                        <div class="table-responsive">
                                          <table id="myTable_pp" width="100%" class="table table-bordered">
                                            <thead>
                                              <tr>
                                                <th width="10%"></th>
                                                <th width="40%" align="center"><?php echo label('r_start_on'); ?></th>
                                                <th width="40%" align="center"><?php echo label('r_finish_on'); ?></th>
                                                <th width="10%" align="center"><?php echo label('action'); ?></th>
                                              </tr>
                                            </thead>
                                          </table>
                                      </div>
                                    </div>

                                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-survey" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><?php echo label('survey'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body ">
                                <div class="card-body">
                                    <div id="div_survey_main" class="row">
                                        <div class="col-md-12" align="right">
                                                <button name="add_survey" id="add_survey" class="btn btn-outline-info add_survey" onclick="create_div('div_create_survey','div_survey','survey_form')"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('survey'); ?></button>
                                        </div>

                                        <div id="div_survey" class="col-md-12">
                                            <div class="table-responsive">
                                              <table id="myTable_cos_id_survey" width="100%" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="10%"></th>
                                                    <th width="40%" align="center"><?php echo label('sName'); ?></th>
                                                    <th width="20%" align="center"><?php echo label('r_start_on'); ?></th>
                                                    <th width="20%" align="center"><?php echo label('r_finish_on'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('action'); ?></th>
                                                  </tr>
                                                </thead>
                                              </table>
                                            </div>
                                        </div>

                                        <div id="div_create_survey" style="display: none;">
                                            <form  enctype="multipart/form-data" id="survey_form" name="survey_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                                <input type="hidden" id="sv_id" name="sv_id">
                                                <input type="hidden" id="operation_survey" name="operation_survey" value="Add">
                                                <input type="hidden" id="course_id_survey" name="course_id_survey">
                                                <input type="hidden" id="com_id_survey" name="com_id_survey">
                                                <div class="col-md-12 row" style="">

                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label for="sv_title_th"><b style="color: #FF2D00">*</b><?php echo label('sName')." TH"; ?>:</label>
                                                        <input type="text" id="sv_title_th" name="sv_title_th" class="form-control" required> 
                                                      </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label for="sv_title_en"><b style="color: #FF2D00">*</b><?php echo label('sName')." EN"; ?>:</label>
                                                        <input type="text" id="sv_title_en" name="sv_title_en" class="form-control" required> 
                                                      </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label for="sv_explanation_th"><b style="color: #FF2D00">*</b><?php echo label('svdesc')." TH"; ?>:</label>
                                                        <input type="text" id="sv_explanation_th" name="sv_explanation_th" class="form-control" required> 
                                                      </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label for="sv_explanation_en"><b style="color: #FF2D00">*</b><?php echo label('svdesc')." EN"; ?>:</label>
                                                        <input type="text" id="sv_explanation_en" name="sv_explanation_en" class="form-control" required> 
                                                      </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label class="control-label text-right"><?php echo label('period_specific'); ?></label>
                                                        <div class='input-group mb-3'>
                                                            <input type='text' id="daterange_survey" name="daterange_survey" class="form-control timeseconds" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <span class="ti-calendar"></span>
                                                                </span>
                                                            </div>
                                                            <input type="hidden" id="survey_open_var" name="survey_open_var">
                                                            <input type="hidden" id="survey_end_var" name="survey_end_var">
                                                        </div>

                                                        <!-- <div class="input-daterange input-group" id="date-range_survey">
                                                            <input type="text" class="form-control" name="survey_open" id="survey_open" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text bg-info b-0 text-white"><?php echo label('to'); ?></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="survey_end" id="survey_end" />
                                                            <input type="hidden" id="survey_open_var" name="survey_open_var">
                                                            <input type="hidden" id="survey_end_var" name="survey_end_var">
                                                        </div> -->
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                      <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quessuggestion_status'); ?></label>
                                                      <div class="m-b-10">
                                                          <label class="custom-control custom-radio">
                                                              <input type="radio" id="radio_sv_suggestion_status1" name="sv_suggestion_status" checked value="1" class="custom-control-input">
                                                              <span class="custom-control-label"><?php echo  label('have'); ?></span>
                                                          </label>
                                                          <label class="custom-control custom-radio">
                                                              <input type="radio" id="radio_sv_suggestion_status2" name="sv_suggestion_status" value="0" class="custom-control-input">
                                                              <span class="custom-control-label"><?php echo label('none'); ?></span>
                                                          </label>
                                                      </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="status_cr"><?php echo label('svtheme'); ?>:</label>
                                                        <select class="form-control select2" id="qn_id" name="qn_id"  style="width: 100%;">
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 progress" id="progress_survey_div">
                                                        <div class="progress-barsurvey bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="txt_progresssurvey"></span></div>
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('saveR'); ?>" />
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <button type="reset" class="btn btn-outline-danger btn-block" onclick="display_style('div_create_survey','div_survey')"><?php echo label('m_cancel'); ?></button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="div_survey_detail" style="display: none;">

                                        <div class="col-md-12" align="right">
                                            <button name="back_survey_detail" id="back_survey_detail" class="btn btn-outline-success back_survey_detail" onclick="display_style('div_survey_detail','div_survey_main')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                                            <button name="add_survey_detail" id="add_survey_detail" class="btn btn-outline-info add_survey_detail" onclick="create_div('div_create_survey_detail','div_sv_survey_detail','survey_detail_form')"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('squestion'); ?></button>
                                        </div>
                                        <h4 id="sv_name_txt"></h4>
                                        <hr>
                                        <div id="div_sv_survey_detail" class="col-md-12">
                                            <div class="table-responsive">
                                              <table id="myTable_survey_detail" width="100%" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="10%" align="center"></th>
                                                    <th width="40%" align="center"><?php echo label('stitle'); ?></th>
                                                    <th width="40%" align="center"><?php echo label('squestion'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('manage'); ?></th>
                                                  </tr>
                                                </thead>
                                              </table>
                                            </div>
                                        </div>
                                        <div id="div_create_survey_detail" style="display: none;" class="col-md-12">

                                            <form  enctype="multipart/form-data" id="survey_detail_form" name="survey_detail_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                                <input type="hidden" id="sv_id_detail" name="sv_id_detail">
                                                <input type="hidden" id="cos_id_detail" name="cos_id_detail">
                                                <input type="hidden" id="svde_id" name="svde_id">
                                                <input type="hidden" id="operation_survey_detail" name="operation_survey_detail" value="Add">
                                                <div class="col-md-12 row" style="">
                                                    <div class="form-group col-md-6">
                                                        <label for="svde_heading_th"><b style="color: #FF2D00">*</b><?php echo label('stitle')." TH"; ?>:</label>
                                                        <input type="text" id="svde_heading_th" name="svde_heading_th" class="form-control" required> 
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="svde_heading_en"><b style="color: #FF2D00">*</b><?php echo label('stitle')." EN"; ?>:</label>
                                                        <input type="text" id="svde_heading_en" name="svde_heading_en" class="form-control" required> 
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="svde_detail_th"><b style="color: #FF2D00">*</b><?php echo label('squestion')." TH"; ?>:</label>
                                                        <input type="text" id="svde_detail_th" name="svde_detail_th" class="form-control" required> 
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="svde_detail_en"><b style="color: #FF2D00">*</b><?php echo label('squestion')." EN"; ?>:</label>
                                                        <input type="text" id="svde_detail_en" name="svde_detail_en" class="form-control" required> 
                                                    </div>
                                                    <div class="col-md-12 progress" id="progress_survey_detail_div">
                                                        <div class="progress-barsurvey_detail bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="txt_progresssurvey_detail"></span></div>
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('saveR'); ?>" />
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <button type="reset" class="btn btn-outline-danger btn-block" onclick="display_style('div_create_survey_detail','div_sv_survey_detail')"><?php echo label('m_cancel'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-quiz" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><?php echo label('quiz'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body ">
                   <div class="card-body">
                                    <div id="div_quiz_main" class="row">
                                        <div class="col-md-12" align="right">
                                                <button name="add_quiz" id="add_quiz" class="btn btn-outline-info add_quiz" onclick="create_div('div_create_quiz','div_quiz','quiz_form')"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('quiz'); ?></button>
                                        </div>
                                        <div id="div_create_quiz" style="display: none;">
                                            <form  enctype="multipart/form-data" id="quiz_form" name="quiz_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                                <input type="hidden" id="qiz_id" name="qiz_id">
                                                <input type="hidden" id="operation_quiz" name="operation_quiz" value="Add">
                                                <input type="hidden" id="course_id_quiz" name="course_id_quiz">
                                                <div class="col-md-12 row" style="">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('specificName')." TH"; ?></label>
                                                        <input required name="quiz_name_th" type="text" class="form-control" id="quiz_name_th">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('specificName')." EN"; ?></label>
                                                        <input required name="quiz_name_en" type="text" class="form-control" id="quiz_name_en">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('summary')." TH"; ?></label>
                                                        <textarea name="quiz_info_th" id="quiz_info_th" rows="10" cols="80"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('summary')." EN"; ?></label>
                                                        <textarea name="quiz_info_en" id="quiz_info_en" rows="10" cols="80"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label text-right"><?php echo label('period_specific'); ?></label>
                                                        <div class='input-group mb-3'>
                                                            <input type='text' id="daterange_quiz" name="daterange_quiz" class="form-control timeseconds" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <span class="ti-calendar"></span>
                                                                </span>
                                                            </div>
                                                            <input type="hidden" id="period_open_var" name="period_open_var">
                                                            <input type="hidden" id="period_end_var" name="period_end_var">
                                                        </div>

                                                        <!-- <div class="input-daterange input-group" id="date-range_quiz">
                                                            <input type="text" class="form-control" name="period_open" id="period_open" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text bg-info b-0 text-white"><?php echo label('to'); ?></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="period_end" id="period_end" />
                                                            <input type="hidden" id="period_open_var" name="period_open_var">
                                                            <input type="hidden" id="period_end_var" name="period_end_var">
                                                        </div> -->
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('random'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_random1" name="quiz_random" checked value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('enable').label('random'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_random2" name="quiz_random" value="0" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('disable').label('random'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('qiz_visible'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_show1" name="quiz_show" checked value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('show').label('quiz'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_show2" name="quiz_show" value="0" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('hide').label('quiz'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('show').label('grade'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_grade1" name="quiz_grade" checked value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('show').label('quiz'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_grade2" name="quiz_grade" value="0" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('hide').label('quiz'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('qiz_type'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_type1" name="quiz_type" onclick="display('1','div_answer')" checked value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('preExam'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_type2" name="quiz_type" onclick="display('2','div_answer')" value="2" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('finalExam'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <div id="div_answer" style="display: none;">
                                                            <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('preAns'); ?></label>
                                                            <div class="m-b-10">
                                                                <label class="custom-control custom-radio">
                                                                    <input type="radio" id="radio_answer1" name="quiz_answer" value="1" class="custom-control-input">
                                                                    <span class="custom-control-label"><?php echo label('enable').label('preAns'); ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input type="radio" id="radio_answer2" name="quiz_answer" checked value="0" class="custom-control-input">
                                                                    <span class="custom-control-label"><?php echo label('disable').label('preAns'); ?></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('qiz_num'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_limit1" onclick="readonly('1','quiz_limitval')" name="quiz_limit" value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('yes'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_limit2" onclick="readonly('0','quiz_limitval')" name="quiz_limit" checked value="0" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('no'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('number_of'); ?></label>
                                                        <input name="quiz_limitval"  type="number" min="0"   step="0.01" pattern="[0123456789.]" class="form-control" id="quiz_limitval" readonly>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('ccond')." (%)"; ?></label>
                                                        <input name="quiz_maxscore" required  type="number" min="0" max="100"   step="0.01" pattern="[0123456789.]" class="form-control" id="quiz_maxscore">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div id="div_template_qize">
                                                            <label><?php echo label('quiz_ex'); ?>:</label>
                                                            <select class="form-control" id="qize_id" name="qize_id"  style="width: 100%;">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 progress" id="progress_quiz_div">
                                                        <div class="progress-barquiz bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="txt_progressquiz"></span></div>
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('saveR'); ?>" />
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <button type="reset" class="btn btn-outline-danger btn-block" onclick="display_style('div_create_quiz','div_quiz')"><?php echo label('m_cancel'); ?></button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div id="div_quiz" class="col-md-12">
                                            <div class="table-responsive">
                                              <table id="myTable_quiz" width="100%" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="10%"></th>
                                                    <th width="50%" align="center"><?php echo label('specificName'); ?></th>
                                                    <th width="30%" align="center"><?php echo label('maxScore'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('action'); ?></th>
                                                  </tr>
                                                </thead>
                                              </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_question_check" style="display: none;">
                                        <div class="col-md-12" align="right">
                                            <button name="back_quiz_check" id="back_quiz_check" class="btn btn-outline-success back_quiz_check" onclick="display_style('div_question_check','div_quiz_detail')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                                        </div>
                                        <h4 id="quiz_name_txt_question"></h4>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                              <table id="myTable_quiz_question_check" width="100%" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="20%" align="center"><?php echo label('emp_id'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('r_name'); ?></th>
                                                    <th width="15%" align="center"><?php echo label('answer'); ?></th>
                                                    <th width="25%" align="center"><?php echo label('msg_fromadmin'); ?></th>
                                                    <th width="30%" align="center"><?php echo label('score'); ?></th>
                                                  </tr>
                                                </thead>
                                              </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_quiz_detail" style="display: none;">
                                        <div class="col-md-12" align="right">
                                            <button name="back_quiz" id="back_quiz" class="btn btn-outline-success back_quiz" onclick="display_style('div_quiz_detail','div_quiz_main')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                                                <button name="add_question" id="add_question" class="btn btn-outline-info add_question" onclick="create_div('div_create_question','div_quiz_question','question_form')"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('squestion'); ?></button>
                                        </div>
                                        <h4 id="quiz_name_txt"></h4>
                                        <hr>
                                        <div id="div_quiz_question" class="col-md-12">
                                            <div class="table-responsive">
                                              <table id="myTable_quiz_question" width="100%" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="20%" align="center"><?php echo label('quest_type'); ?></th>
                                                    <th width="40%" align="center"><?php echo label('squestion'); ?></th>
                                                    <th width="30%" align="center"><?php echo label('choice'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('manage'); ?></th>
                                                  </tr>
                                                </thead>
                                              </table>
                                            </div>
                                        </div>
                                        <div id="div_create_question" style="display: none;" class="col-md-12">
                                            <form  enctype="multipart/form-data" id="question_form" name="question_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                                <input type="hidden" id="qiz_id_question" name="qiz_id_question">
                                                <input type="hidden" id="cos_id_question" name="cos_id_question">
                                                <input type="hidden" id="ques_id" name="ques_id">
                                                <input type="hidden" id="operation_question" name="operation_question" value="Add">
                                                <div class="col-md-12 row" style="">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('squestion')." TH"; ?></label>
                                                        <textarea name="ques_name_th" id="ques_name_th" rows="10" cols="80"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('squestion')." EN"; ?></label>
                                                        <textarea name="ques_name_en" id="ques_name_en" rows="10" cols="80"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('summary')." TH"; ?></label>
                                                        <textarea name="ques_info_th" id="ques_info_th" rows="10" cols="80"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('summary')." EN"; ?></label>
                                                        <textarea name="ques_info_en" id="ques_info_en" rows="10" cols="80"></textarea>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('quest_visible'); ?></label>
                                                        <div class="m-b-10">
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_ques_show1" name="ques_show" checked value="1" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('show').label('squestion'); ?></span>
                                                            </label>
                                                            <label class="custom-control custom-radio">
                                                                <input type="radio" id="radio_ques_show2" name="ques_show" value="0" class="custom-control-input">
                                                                <span class="custom-control-label"><?php echo label('hide').label('squestion'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label text-right"><?php echo label('maxScore'); ?></label>
                                                        <input name="ques_score"  type="number" min="0"   step="0.01" pattern="[0123456789.]" class="form-control" id="ques_score">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label><?php echo label('quest_type'); ?>:</label>
                                                        <select class="form-control" required id="ques_type" name="ques_type"  style="width: 100%;">
                                                            <option value="0" selected><?php echo label('choose').label('quest_type') ?></option>
                                                            <option value="sa"><?php echo label('qt_sa'); ?></option>
                                                            <option value="sub"><?php echo label('qt_sub'); ?></option>
                                                            <option value="multi"><?php echo label('qt_multi'); ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="col-md-12 row" id="div_question_mul" style="display: none;">
                                                        <div class="form-group col-md-6 courseCat">
                                                            <h4><?php echo label('quest_detail'); ?></h4>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 1 TH"; ?></label>
                                                            <textarea name="mul_c1_th" id="mul_c1_th" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 1 EN"; ?></label>
                                                            <textarea name="mul_c1_en" id="mul_c1_en" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 2 TH"; ?></label>
                                                            <textarea name="mul_c2_th" id="mul_c2_th" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 2 EN"; ?></label>
                                                            <textarea name="mul_c2_en" id="mul_c2_en" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 3 TH"; ?></label>
                                                            <textarea name="mul_c3_th" id="mul_c3_th" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 3 EN"; ?></label>
                                                            <textarea name="mul_c3_en" id="mul_c3_en" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 4 TH"; ?></label>
                                                            <textarea name="mul_c4_th" id="mul_c4_th" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 4 EN"; ?></label>
                                                            <textarea name="mul_c4_en" id="mul_c4_en" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 5 TH"; ?></label>
                                                            <textarea name="mul_c5_th" id="mul_c5_th" rows="10" cols="80"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 5 EN"; ?></label>
                                                            <textarea name="mul_c5_en" id="mul_c5_en" rows="10" cols="80"></textarea>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('answer'); ?>:</label>
                                                                <select class="form-control select2" id="mul_answer" name="mul_answer[]" multiple  style="width: 100%;">
                                                                    <option value="mul_c1"><?php echo label('choice')." 1"; ?></option>
                                                                    <option value="mul_c2"><?php echo label('choice')." 2"; ?></option>
                                                                    <option value="mul_c3"><?php echo label('choice')." 3"; ?></option>
                                                                    <option value="mul_c4"><?php echo label('choice')." 4"; ?></option>
                                                                    <option value="mul_c5"><?php echo label('choice')." 5"; ?></option>
                                                                </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 progress" id="progress_question_div">
                                                        <div class="progress-barquestion bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="txt_progressquestion"></span></div>
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('saveR'); ?>" />
                                                    </div>
                                                    <div class="form-group col-md-6" align="center">
                                                        <br>
                                                        <button type="reset" class="btn btn-outline-danger btn-block" onclick="display_style('div_create_question','div_quiz_question')"><?php echo label('m_cancel'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        function fetch_data_enroll_qiz(qiz_id)
         {
            $('#myTable_enroll_qiz').DataTable().destroy();
            if(qiz_id!=''){
                $('#myTable_enroll_qiz').on('error.dt', function(e, settings, techNote, message) {
                  notificationForDatatableError("myTable_enroll_qiz", message);
                }).DataTable({
                    "ajax": {
                        url : '<?=base_url()?>index.php/course/fetch_course_enroll_qiz/',
                        data : {
                          qiz_id:qiz_id,
                          lang: "<?php echo $lang; ?>"
                        },
                        type : 'GET'
                    }
                });
            }else{
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
                    }
                });
            }
         }
        function fetch_data_quiz(cos_id)
         {
            $('#myTable_quiz').DataTable().destroy();
            $('#myTable_quiz').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_quiz", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_quiz/',
                    data : {
                      cos_id:cos_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
        function fetch_data_survey(cos_id)
         {
            $('#myTable_cos_id_survey').DataTable().destroy();
            $('#myTable_cos_id_survey').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_cos_id_survey", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_survey/',
                    data : {
                      cos_id:cos_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
        function fetch_data_survey_detail(sv_id)
         {
            $('#myTable_survey_detail').DataTable().destroy();
            $('#myTable_survey_detail').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_survey_detail", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_survey_detail/',
                    data : {
                      sv_id:sv_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
        function fetch_data_question(quiz)
         {
            $('#myTable_quiz_question').DataTable().destroy();
            $('#myTable_quiz_question').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_quiz_question", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_question/',
                    data : {
                      quiz:quiz,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
        function fetch_data_quiz_detail(qiz_id)
         {
            $('#myTable_document').DataTable().destroy();
            $('#myTable_document').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_document", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_quiz_detail/',
                    data : {
                      qiz_id:qiz_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }

        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }
        $(document).on('submit', '#survey_detail_form', function(event){
              event.preventDefault(); 
              var course_id = $('#course_id_survey').val();
              var com_id = $('#com_id_survey').val();
              var sv_id = $('#sv_id_detail').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_survey_detail",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                    document.getElementById("progress_survey_detail_div").style.display = "";
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                console.log(percentComplete);
                                $('#txt_progresssurvey_detail').text(percentComplete.toFixed(2) + '%');

                                 $('.progress-barsurvey_detail').animate({
                                  width: percentComplete + '%'
                                 }, {
                                  duration: 100
                                 });
                                //Do something with upload progress here
                            }
                       }, false);
                       return xhr;
                  },
                  success:function(data)
                  {
                    document.getElementById("progress_survey_detail_div").style.display = "none";
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            $('#survey_detail_form')[0].reset();
                            display_style('div_create_survey_detail','div_sv_survey_detail');
                            $('#course_id_survey').val(course_id);
                            $('#com_id_survey').val(com_id);
                            $('#sv_id_detail').val(sv_id);
                            fetch_data_survey_detail(sv_id);
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
                            $('#survey_detail_form')[0].reset();
                            $('#course_id_survey').val(course_id);
                            $('#com_id_survey').val(com_id);
                        })
                    }else{
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
        });
        $(document).on('submit', '#survey_form', function(event){
              event.preventDefault(); 
              var course_id = $('#course_id_survey').val();
              var com_id = $('#com_id_survey').val();
              /*var survey_open = new Date($('#survey_open').val());
              var survey_end = new Date($('#survey_end').val());
              $('#survey_open_var').val(formatDate(survey_open));
              $('#survey_end_var').val(formatDate(survey_end));*/
              console.log(formatDate(survey_open),formatDate(survey_end));
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_survey",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                    document.getElementById("progress_survey_div").style.display = "";
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                console.log(percentComplete);
                                $('#txt_progresssurvey').text(percentComplete.toFixed(2) + '%');

                                 $('.progress-barsurvey').animate({
                                  width: percentComplete + '%'
                                 }, {
                                  duration: 100
                                 });
                                //Do something with upload progress here
                            }
                       }, false);
                       return xhr;
                  },
                  success:function(data)
                  {
                    document.getElementById("progress_survey_div").style.display = "none";
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            $('#survey_form')[0].reset();
                            display_style('div_create_survey','div_survey');
                            $('#course_id_survey').val(course_id);
                            $('#com_id_survey').val(com_id);
                            fetch_data_survey(course_id);
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
                            $('#survey_form')[0].reset();
                            $('#course_id_survey').val(course_id);
                            $('#com_id_survey').val(com_id);
                        })
                    }else{
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
        });

        $(document).on('submit', '#question_form', function(event){
              event.preventDefault(); 
              var course_id = $('#cos_id_question').val();
              var qiz_id = $('#qiz_id_question').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_question",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                    document.getElementById("progress_question_div").style.display = "";
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                console.log(percentComplete);
                                $('#txt_progressquestion').text(percentComplete.toFixed(2) + '%');

                                 $('.progress-barquestion').animate({
                                  width: percentComplete + '%'
                                 }, {
                                  duration: 100
                                 });
                                //Do something with upload progress here
                            }
                       }, false);
                       return xhr;
                  },
                  success:function(data)
                  {
                    document.getElementById("progress_question_div").style.display = "none";
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            $('#question_form')[0].reset();
                            display_style('div_create_question','div_quiz_question');
                            $('#cos_id_question').val(course_id);
                            $('#qiz_id_question').val(qiz_id);
                            
                            fetch_data_question(qiz_id);
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
                            $('#question_form')[0].reset();
                        })
                    }else{
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
        });

        $(document).on('submit', '#quiz_form', function(event){
              event.preventDefault(); 
              var period_open = new Date($('#period_open').val());
              var period_end = new Date($('#period_end').val());
              var course_id = $('#cos_id').val();
              /*$('#period_open_var').val(formatDate(period_open));
              $('#period_end_var').val(formatDate(period_end));
              console.log(formatDate(period_open),formatDate(period_end));*/
              document.getElementById('div_quiz_detail').style.display = 'none';
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_quiz",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                    document.getElementById("progress_quiz_div").style.display = "";
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                console.log(percentComplete);
                                $('#txt_progressquiz').text(percentComplete.toFixed(2) + '%');

                                 $('.progress-barquiz').animate({
                                  width: percentComplete + '%'
                                 }, {
                                  duration: 100
                                 });
                                //Do something with upload progress here
                            }
                       }, false);
                       return xhr;
                  },
                  success:function(data)
                  {
                    document.getElementById("progress_quiz_div").style.display = "none";
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            $('#quiz_form')[0].reset();
                            display_style('div_create_quiz','div_quiz');
                            $('#cos_id').val(course_id);
                            fetch_data_quiz(course_id);
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
                            $('#quiz_form')[0].reset();
                        })
                    }else{
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
        });
        $('select[name="qiz_id_enroll"]').on('change', function(){
          var qiz_id = $(this).val();
          if(qiz_id!="000"){
            fetch_data_enroll_qiz(qiz_id);
          }else{
            $('#myTable_enroll_qiz').DataTable().destroy();
            fetch_data_enroll_qiz('');
          }
        });

        $('select[name="qn_id"]').on('change', function(){
          var qn_id = $(this).val();
          if(qn_id!="000"){
            $.ajax({
              url:"<?=base_url()?>index.php/questionnaire/update_questionnaire_data",
              method:"POST",
              data:{id_update:qn_id},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                $('#sv_title_th').val(data.qn_title_th); 
                $('#sv_title_en').val(data.qn_title_en); 
                $('#sv_explanation_th').val(data.qn_explanation_th); 
                $('#sv_explanation_en').val(data.qn_explanation_en);  
                if(data.qn_suggestion_status=="1"){
                  document.getElementById("radio_sv_suggestion_status1").checked = true;
                }else{
                  document.getElementById("radio_sv_suggestion_status2").checked = true;
                }
              }
            });
          }
        });
        $('select[name="ques_type"]').on('change', function(){
          var ques_type = $(this).val();
          if(ques_type=='multi'){
            document.getElementById('div_question_mul').style.display = '';
          }else{
            document.getElementById('div_question_mul').style.display = 'none';
          }
        });
        function chk_posi(dep_id=""){
            if($('input[id="chkdep_'+dep_id+'"]').is(':checked')){
              $('input[class="chkall_'+dep_id+'"]').prop('checked', true);
            } else {
              $('input[class="chkall_'+dep_id+'"]').prop('checked', false);
            }
        }
        function clear_dropify(id){
            var drEvent = $(id).dropify(
                    {
                      defaultFile: ''
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = '';
                    drEvent.destroy();
                    drEvent.init();
        }
        function changeNote_tc(tc_id){
          var tc_note = $('#tc_note_'+tc_id).val();
                                $.ajax({
                                      url: '<?=base_url()?>index.php/course/update_note_tc',
                                      type: 'POST',
                                      data:{tc_id:tc_id,tc_note:tc_note},
                                      success: function(note){
                                        console.log(note);
                                      }
                                });
        }
        $(document).on('click', '.check_ques', function(){
            var ques_id = $(this).attr("id");
            console.log(ques_id);
            document.getElementById('div_question_check').style.display = '';
            document.getElementById('div_quiz_detail').style.display = 'none';
            
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_question_detail_data",
                  method:"POST",
                  data:{ques_id_update:ques_id},
                  dataType:"json",
                  success:function(data)
                  {
                    <?php if($lang=="thai"){ ?>
                      $('#quiz_name_txt_question').html('<?php echo label("chk_answer_txt"); ?>'+data.ques_name_th);
                    <?php }else{ ?>
                      $('#quiz_name_txt_question').html('<?php echo label("chk_answer_txt"); ?>'+data.ques_name_en);
                    <?php } ?>
                  }
            });
            fetch_data_quiz_question_check(ques_id);
        });
        $(document).on('click', '.back_quiz', function(){
            var cos_id = $('#course_id_quiz').val();
            document.getElementById('div_quiz').style.display = '';
            document.getElementById('div_create_quiz').style.display = 'none';
            document.getElementById('div_quiz_main').style.display = '';
            document.getElementById('div_quiz_detail').style.display = 'none';
            fetch_data_quiz(cos_id);

        });

        $(document).on('click', '.back_survey_detail', function(){
            var cos_id = $('#course_id_survey').val();
            document.getElementById('div_survey').style.display = '';
            document.getElementById('div_create_survey').style.display = 'none';
            document.getElementById('div_survey_main').style.display = '';
            document.getElementById('div_survey_detail').style.display = 'none';
            fetch_data_survey(cos_id);
            display_disable('div_create_survey','div_survey');

        });

        $(document).on('click', '.quiz_detail', function(){
            var qiz_id = $(this).attr("id");
            var cos_id = $('#course_id_quiz').val();
            var lang = '<?php echo $lang; ?>';
            console.log(qiz_id,lang);
            document.getElementById('div_quiz').style.display = 'none';
            document.getElementById('div_create_quiz').style.display = 'none';
            document.getElementById('div_quiz_main').style.display = 'none';
            document.getElementById('div_quiz_detail').style.display = '';
            display_disable('div_create_question','div_quiz_question');

            $('#question_form')[0].reset();   
            $('#qiz_id_question').val(qiz_id);
            $('#cos_id_question').val(cos_id);
            fetch_data_question(qiz_id);
            $.ajax({
                url:"<?=base_url()?>index.php/course/update_quiz_detail_data",
                method:"POST",
                data:{qiz_id_update:qiz_id},
                dataType:"json",
                success:function(data)
                {
                    if(lang=="thai"){
                        $('#quiz_name_txt').text(data.quiz_name_th);
                    }else{
                        $('#quiz_name_txt').text(data.quiz_name_en);
                    }
                }
            });
        });


        $(document).on('click', '.survey_detail', function(){
            var sv_id = $(this).attr("id");
            var cos_id = $('#course_id_survey').val();
            var lang = '<?php echo $lang; ?>';
            console.log(sv_id,lang);
            document.getElementById('div_survey').style.display = 'none';
            document.getElementById('div_create_survey').style.display = 'none';
            document.getElementById('div_survey_main').style.display = 'none';
            document.getElementById('div_survey_detail').style.display = '';
            display_disable('div_create_survey_detail','div_sv_survey_detail');

            $('#survey_detail_form')[0].reset();   
            $('#sv_id_detail').val(sv_id);
            $('#cos_id_detail').val(cos_id);
            fetch_data_survey_detail(sv_id);
            $.ajax({
                url:"<?=base_url()?>index.php/course/update_survey_detail_data",
                method:"POST",
                data:{sv_id_update:sv_id},
                dataType:"json",
                success:function(data)
                {
                    if(lang=="thai"){
                        $('#sv_name_txt').text(data.sv_title_th);
                    }else{
                        $('#sv_name_txt').text(data.sv_title_en);
                    }
                }
            });
        });
        $(document).on('click', '.update_survey_detail', function(){
            var svde_id = $(this).attr("id");
            console.log(svde_id);
            document.getElementById('div_survey_main').style.display = 'none';
            //var com_id = $('com_id_survey').val();
            var cos_id = $('course_id_survey').val();
            var sv_id = $('sv_id_detail').val();
            var com_id = $('#com_id'+cos_id).val();
            $('#survey_detail_form')[0].reset();  
            $('#operation_survey_detail').val("Edit");
            $('#svde_id').val(svde_id);
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_survey_sv_detail_data",
                  method:"POST",
                  data:{svde_id_update:svde_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('#svde_heading_th').val(data.svde_heading_th);
                        $('#svde_heading_en').val(data.svde_heading_en);
                        $('#svde_detail_th').val(data.svde_detail_th);
                        $('#svde_detail_en').val(data.svde_detail_en);
                  }
            });
            display_style('div_create_survey_detail','div_sv_survey_detail');
        });
        $(document).on('click', '.update_survey', function(){
            var sv_id = $(this).attr("id");
            console.log(sv_id);
            document.getElementById('div_survey_detail').style.display = 'none';
            //var com_id = $('com_id_survey').val();
            var cos_id = $('course_id_survey').val();
            var com_id = $('#com_id'+cos_id).val();
            //$('#survey_form')[0].reset();  
            $('#operation_survey').val("Edit");
            $('#sv_id').val(sv_id);
            console.log($('com_id_survey').val());
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_survey_detail_data",
                  method:"POST",
                  data:{sv_id_update:sv_id},
                  dataType:"json",
                  success:function(data)
                  {

                        $('#sv_title_th').val(data.sv_title_th);
                        $('#sv_title_en').val(data.sv_title_en);
                        $('#sv_explanation_th').val(data.sv_explanation_th);
                        $('#sv_explanation_en').val(data.sv_explanation_en);
                        $('#quiz_name_en').val(data.quiz_name_en);
                        $('#quiz_name_en').val(data.quiz_name_en);
                        $('#course_id_survey').val(data.cos_id);
                        var com_id = $('#com_id'+data.cos_id).val();
                        $('com_id_survey').val(com_id);
                        console.log(data.cos_id,com_id);
                        $.ajax({
                              url: '<?=base_url()?>index.php/workgroup/recheckquestionnaire',
                              type: 'POST',
                              data:{com_id:com_id,qn_id:data.qn_id},
                              success: function(data){
                                console.log(data);
                                $('#qn_id').html(data);
                              }
                        });
                        if(data.sv_suggestion_status=="1"){
                            document.getElementById("radio_sv_suggestion_status1").checked = true;
                        }else{
                            document.getElementById("radio_sv_suggestion_status2").checked = true;
                        }


                        var date_start = data.survey_open_var.split(/[- :]/);
                        var date_end = data.survey_end_var.split(/[- :]/);

                        // Apply each element to the Date function

                        var ddate_start = mysqlTimeStampToDate(data.survey_open_var);
                        var date_end = mysqlTimeStampToDate(data.survey_end_var);
                        $('#daterange_survey').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: ddate_start,
                            endDate: date_end,
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#survey_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#survey_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
                        /*$('#survey_open_var').val(data.survey_open_var);
                        $('#survey_end_var').val(data.survey_end_var);
                        $("#survey_open").datepicker("update", data.survey_open);
                        $("#survey_end").datepicker("update", data.survey_end);*/
                  }
            });
            display_style('div_create_survey','div_survey');
        });
        $(document).on('click', '.update_quiz', function(){
            var qiz_id = $(this).attr("id");
            console.log(qiz_id);
            document.getElementById('div_quiz_detail').style.display = 'none';
            document.getElementById('div_template_qize').style.display = 'none';

            $('#operation_quiz').val("Edit");
            $('#qiz_id').val(qiz_id);
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_quiz_detail_data",
                  method:"POST",
                  data:{qiz_id_update:qiz_id},
                  dataType:"json",
                  success:function(data)
                  {

                        $('#quiz_name_th').val(data.quiz_name_th);
                        $('#quiz_name_en').val(data.quiz_name_en);
                        $(tinymce.get('quiz_info_th').getBody()).html(data.quiz_info_th);
                        $(tinymce.get('quiz_info_en').getBody()).html(data.quiz_info_en);

                        if(data.quiz_random=="1"){
                            document.getElementById("radio_random1").checked = true;
                        }else{
                            document.getElementById("radio_random2").checked = true;
                        }
                        if(data.quiz_show=="1"){
                            document.getElementById("radio_show1").checked = true;
                        }else{
                            document.getElementById("radio_show2").checked = true;
                        }
                        if(data.quiz_grade=="1"){
                            document.getElementById("radio_grade1").checked = true;
                        }else{
                            document.getElementById("radio_grade2").checked = true;
                        }
                        if(data.quiz_type=="1"){
                            display('1','div_answer');
                            document.getElementById("radio_type1").checked = true;
                        }else{
                            display('2','div_answer');
                            document.getElementById("radio_type2").checked = true;
                            if(data.quiz_answer=="1"){
                                document.getElementById("radio_answer1").checked = true;
                            }else{
                                document.getElementById("radio_answer2").checked = true;
                            }
                        }
                        if(data.quiz_limit=="1"){
                            $('#quiz_limitval').val(data.quiz_limitval);
                            readonly('1','quiz_limitval');
                            document.getElementById("radio_limit1").checked = true;
                        }else{
                            $('#quiz_limitval').val('');
                            readonly('0','quiz_limitval');
                            document.getElementById("radio_limit2").checked = true;
                        }

                        $('#quiz_maxscore').val(data.quiz_maxscore);
                        $('#period_open_var').val(data.period_open_var);
                        $('#period_end_var').val(data.period_end_var);
                        
                        var ddate_start = mysqlTimeStampToDate(data.period_open_var);
                        var date_end = mysqlTimeStampToDate(data.period_end_var);
                        $('#daterange_quiz').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: ddate_start,
                            endDate: date_end,
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#period_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#period_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
                        /*$("#period_open").datepicker("update", data.period_open);
                        $("#period_end").datepicker("update", data.period_end);*/
                  }
            });
            display_style('div_create_quiz','div_quiz');
        });
        function fetch_data_quiz_question_check(ques_id)
         {
            $('#myTable_quiz_question_check').DataTable().destroy();
            $('#myTable_quiz_question_check').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_quiz_question_check", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_quiz_question_check/',
                    data : {
                      ques_id:ques_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
                "columnDefs": [
                  { width: "20%", targets: 0 },
                  { width: "10%", targets: 1 },
                  { width: "15%", targets: 2 },
                  { width: "25%", targets: 3 },
                  { width: "30%", targets: 4 }
                ],
            });
         }
        $(document).on('click', '.update_ques', function(){
            var ques_id = $(this).attr("id");
            console.log(ques_id);

            $('#operation_question').val("Edit");
            $('#ques_id').val(ques_id);

                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckmul_answer',
                                      type: 'POST',
                                      data:{ques_id:''},
                                      success: function(answer){
                                        console.log(answer);
                                        $('#mul_answer').html(answer);
                                      }
                                });
            var qiz_id = $('#qiz_id_question').val();
            var cos_id = $('#course_id_quiz').val();
            $('#cos_id_question').val(cos_id);
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_question_detail_data",
                  method:"POST",
                  data:{ques_id_update:ques_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('#ques_type').val(data.ques_type);
                        $('#ques_score').val(data.ques_score);
                        $(tinymce.get('ques_name_th').getBody()).html(data.ques_name_th);
                        $(tinymce.get('ques_name_en').getBody()).html(data.ques_name_en);
                        $(tinymce.get('ques_info_th').getBody()).html(data.ques_info_th);
                        $(tinymce.get('ques_info_en').getBody()).html(data.ques_info_en);
                        if(data.ques_show=="1"){
                            document.getElementById("radio_ques_show1").checked = true;
                        }else{
                            document.getElementById("radio_ques_show2").checked = true;
                        }
                        if(data.ques_type=='multi'){
                            document.getElementById('div_question_mul').style.display = '';
                            $(tinymce.get('mul_c1_th').getBody()).html(data.multi['mul_c1_th']);
                            $(tinymce.get('mul_c2_th').getBody()).html(data.multi['mul_c2_th']);
                            $(tinymce.get('mul_c3_th').getBody()).html(data.multi['mul_c3_th']);
                            $(tinymce.get('mul_c4_th').getBody()).html(data.multi['mul_c4_th']);
                            $(tinymce.get('mul_c5_th').getBody()).html(data.multi['mul_c5_th']);
                            $(tinymce.get('mul_c1_en').getBody()).html(data.multi['mul_c1_en']);
                            $(tinymce.get('mul_c2_en').getBody()).html(data.multi['mul_c2_en']);
                            $(tinymce.get('mul_c3_en').getBody()).html(data.multi['mul_c3_en']);
                            $(tinymce.get('mul_c4_en').getBody()).html(data.multi['mul_c4_en']);
                            $(tinymce.get('mul_c5_en').getBody()).html(data.multi['mul_c5_en']);
                            var myarr = data.multi['mul_answer'];
                            if(myarr!=""){
                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckmul_answer',
                                      type: 'POST',
                                      data:{ques_id:ques_id},
                                      success: function(answer){
                                        console.log(answer);
                                        $('#mul_answer').html(answer);
                                      }
                                });
                            }
                        }else{
                            document.getElementById('div_question_mul').style.display = 'none';
                        }
                        console.log(data);
                  }
            });
            display_style('div_create_question','div_quiz_question');
        });
        textarea_tinymce('quiz_info_th');
        textarea_tinymce('quiz_info_en');
        textarea_tinymce('ques_name_th');
        textarea_tinymce('ques_name_en');
        textarea_tinymce('ques_info_th');
        textarea_tinymce('ques_info_en');

        textarea_tinymce('mul_c1_th');
        textarea_tinymce('mul_c1_en');
        textarea_tinymce('mul_c2_th');
        textarea_tinymce('mul_c2_en');
        textarea_tinymce('mul_c3_th');
        textarea_tinymce('mul_c3_en');
        textarea_tinymce('mul_c4_th');
        textarea_tinymce('mul_c4_en');
        textarea_tinymce('mul_c5_th');
        textarea_tinymce('mul_c5_en');

        $(document).on('click', '.btn_survey', function(){
            var com_id = $('#com_id').val();
            var id = $('#cos_id').val();
            console.log(id);
            $('#operation_survey').val("Add");
            $('#course_id_survey').val(id);
            $('#com_id_survey').val(com_id);
            document.getElementById('div_create_survey').style.display = 'none';
            document.getElementById('div_survey').style.display = '';
            document.getElementById('div_survey_main').style.display = '';
            document.getElementById('div_survey_detail').style.display = 'none';
            fetch_data_survey(id);
        });
        $(document).on('click', '.btn_quiz', function(){
            var com_id = $('#com_id').val();
            var id = $('#cos_id').val();
            console.log(id);
            $('#operation_quiz').val("Add");
            $('#course_id_quiz').val(id);
            document.getElementById('div_create_quiz').style.display = 'none';
            document.getElementById('div_quiz_detail').style.display = 'none';

            document.getElementById('div_quiz').style.display = '';
            fetch_data_quiz(id);
        });
        $(document).on('click', '.btn_permisssion', function(){
            var com_id = $('#com_id').val();
            var id = $('#cos_id').val();
            console.log(id);
            $('#com_id_detail').val(com_id);
            $('#operation_pp').val("Add");
            $('#course_id_pp').val(id);
            document.getElementById('div_create_pp').style.display = 'none';
            document.getElementById('div_pp').style.display = '';
            $.ajax({
                  url: '<?=base_url()?>index.php/course/permission_course',
                  type: 'POST',
                  data:{com_id:com_id,course_id:id,cosde_id:''},
                  success: function(data_permiss){
                    console.log(data_permiss);
                    $('#permission_div').html(data_permiss);
                  }
            });
            fetch_data_detail(id);
        });
          $(".checkall").each(function () {
              document.getElementById("chkcg_"+this.value).checked = true;
          });
        function fetch_data_detail(cos_id)
         {
            $('#myTable_pp').DataTable().destroy();
            $('#myTable_pp').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_pp", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_detail/',
                    data : {
                      cos_id:cos_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
         }
      function oncheckboxall(){
        var checkBoxall = document.getElementById("chkcg_all");
        if(checkBoxall.checked == true){
          $(".checkall").attr("checked", true);
          var sList = "";
          $(".checkall").each(function () {
              document.getElementById("chkcg_"+this.value).checked = true;
            document.getElementById("div_cg_main"+this.value).style.display = "";
          });
        }else{
          $(".checkall").attr("checked", false);

          var sList = "";
          $(".checkall").each(function () {
              document.getElementById("chkcg_"+this.value).checked = false;
              document.getElementById("div_cg_main"+this.value).style.display = "none";
          });
        }
      }
      function oncheckbox(id){
        console.log(id);
        var count_cg = parseInt('<?php echo countArray($courses_cg); ?>');
        var checkBox = document.getElementById("chkcg_"+id);
        if(checkBox.checked == true){
          document.getElementById("div_cg_main"+id).style.display = "";
        }else{
          document.getElementById("div_cg_main"+id).style.display = "none";
        }
        var sList = 0;
        $(".checkall").each(function () {
          if(this.checked){
            sList++;
            document.getElementById("div_cg_main"+this.value).style.display = "";
          }else{
            document.getElementById("div_cg_main"+this.value).style.display = "none";
          }
        });
        if(sList==count_cg){
          document.getElementById("chkcg_all").checked = true;
        }else{
          document.getElementById("chkcg_all").checked = false;
        }
      }
        function reset_model(name_model,sup_model){
            $('#'+sup_model).modal('hide');
            $('#'+name_model).modal('show');
        }
        function textarea_tinymce(id){
            if ($("#"+id).length > 0) {
                tinymce.init({
                    selector: "textarea#"+id,
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

                });
            }
        }
        function readonly(val_chk,field_name){
            if(val_chk=="1"){
                document.getElementById(field_name).readOnly = false;
            }else{
                document.getElementById(field_name).readOnly = true;
            }
        }
        function changeScore(cosen_id){
            var cosen_score = $('#cosen_score'+cosen_id).val();
              $.ajax({
                  url:"<?=base_url()?>index.php/course/update_score",
                  method:'POST',
                  data:{cosen_id:cosen_id,cosen_score:cosen_score},
                  success:function(data)
                  {
                    console.log(data);
                  }
              });
        }
        function changeScore_qiz(id){
            var sum_score = $('#sum_score'+id).val();
              $.ajax({
                  url:"<?=base_url()?>index.php/course/update_score_qiz",
                  method:'POST',
                  data:{id:id,sum_score:sum_score},
                  success:function(data)
                  {
                    console.log(data);
                  }
              });
        }
        function display(val_chk,field_name){
            if(val_chk=="1"){
                document.getElementById(field_name).style.display = 'none';
            }else{
                document.getElementById(field_name).style.display = '';
            }
        }

        function display_disable(div_name,div_main){
            var x = document.getElementById(div_name);
            var y = document.getElementById(div_main);
                x.style.display = 'none';
                y.style.display = '';
            if(div_name=='div_create_lesson'){
                document.getElementById('div_lesson').style.display = '';
                document.getElementById('div_create_lesson').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = 'none';
            }
            if(div_name=='div_create_quiz'){
                document.getElementById('div_quiz_main').style.display = '';
                document.getElementById('div_quiz_detail').style.display = 'none';
                document.getElementById('div_question_check').style.display = 'none';
            }
            if(div_name=='div_create_survey'){
                document.getElementById('div_survey_main').style.display = '';
                document.getElementById('div_survey_detail').style.display = 'none';
            }
            if(div_name=='div_create_videocourse'){
                document.getElementById('div_videocourse').style.display = '';
                document.getElementById('div_create_videocourse').style.display = 'none';
            }
            if(div_main=='div_enroll_main'){
                document.getElementById('div_enroll_qiz').style.display = 'none';
                var cos_id = $('#course_id_pp').val();
                
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
                    }
                });
                $.ajax({
                      url: '<?=base_url()?>index.php/course/rechkquizandstudent',
                      type: 'POST',
                      data:{cos_id:cos_id},
                      success: function(data){
                        console.log(data);
                        if(data == "1"){
                            document.getElementById('manage_quiz').style.display = '';
                        }else{
                            document.getElementById('manage_quiz').style.display = 'none';
                        }
                      }
                });
            }
              var com_id = $('#com_id_survey').val();
              console.log("960"+com_id);
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

                if(div_name=='div_create_pp'){
                    var id = $('#course_id_pp').val();
                    fetch_data_detail(id);
                }

                if(div_name=='div_create_question'){
                    document.getElementById('div_question_mul').style.display = 'none';
                }

                if(div_name=='div_quiz_detail'){
                    document.getElementById('div_question_check').style.display = 'none';
                }
            }

        }
        function create_div(div_name,div_main,form_name){
            document.getElementById(div_name).style.display = '';
            document.getElementById(div_main).style.display = 'none';
            var com_id = $('#com_id').val();
            var cos_id = $('#cos_id').val();
            $('#'+form_name)[0].reset();
            $('#com_id_detail').val(com_id);
            $('#course_id_pp').val(cos_id);
            $('#course_id_lesson').val(cos_id);
            $('#course_id_quiz').val(cos_id);
            $('#course_id_survey').val(cos_id);
            $('#course_id_cosv').val(cos_id);
            $('#com_id_survey').val(com_id);
            if(form_name=="period_and_permission_form"){
                $.ajax({
                      url: '<?=base_url()?>index.php/course/permission_course',
                      type: 'POST',
                      data:{com_id:com_id,course_id:cos_id,cosde_id:''},
                      success: function(data){
                        console.log(data);
                        $('#permission_div').html(data);
                      }
                });
                $('#operation_pp').val("Add");
                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date(),
                            endDate: new Date(),
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#date_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#date_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
                //$("#date_start").datepicker("update", '');
                //$("#date_end").datepicker("update", '');
            }else if(form_name=="lesson_form"){
                $('#operation_lesson').val("Add");
                $("#time_start").datepicker("update", '');
                $("#time_end").datepicker("update", '');
                document.getElementById('div_media').style.display = '';
                document.getElementById('div_scorm').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = 'none';
                document.getElementById('div_multifile_url').style.display = 'none';
                document.getElementById('div_multifile_upload_file').style.display = 'none';
            }else if(form_name=="videocourse_form"){
                $('#operation_cosv').val("Add");
                document.getElementById('div_multifile_url_videocourse').style.display = '';
                document.getElementById('div_multifile_upload_file_videocourse').style.display = 'none';
            }else if(form_name=="lesson_order_form"){
                $('#operation_lesson_order').val("Add");
                document.getElementById('div_create_lesson').style.display = 'none';
                document.getElementById('div_order_lesson').style.display = '';
                $.ajax({
                      url: '<?=base_url()?>index.php/course/li_lesson_course',
                      type: 'POST',
                      data:{cos_id:cos_id},
                      success: function(data){
                        console.log(data);
                        $('#load_li_lesson').html(data);
                      }
                });
            }else if(form_name=="quiz_form"){
                $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/select_qize',
                      type: 'POST',
                      data:{com_id:com_id},
                      success: function(data){
                        console.log(data);
                        $('#qize_id').html(data);
                      }
                });
                document.getElementById('div_template_qize').style.display = '';
                $('#operation_quiz').val("Add");/*
                $("#period_open").datepicker("update", '');
                $("#period_end").datepicker("update", '');*/

                        $('#daterange_quiz').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date(),
                            endDate: new Date(),
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#period_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#period_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
            }else if(form_name=="survey_form"){
                $('#operation_survey').val("Add");
                /*$("#survey_open").datepicker("update", '');
                $("#survey_end").datepicker("update", '');*/
                
                        $('#daterange_survey').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date(),
                            endDate: new Date(),
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#survey_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#survey_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
            }else if(form_name="question_form"){
                $('#operation_question').val("Add");
            }
        }
         $(document).on('click', '.delete_period', function(){
            var id = $(this).attr("id");
            var cos_id = $('#cos_id').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/course/delete_data",
                    method:"POST",
                    data:{id_delete:id,table_name:"lms_cos_detail",field:"cosde_id"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_detail(cos_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.delete_survey', function(){
            var sv_id = $(this).attr("id");
            var course_id = $('#course_id_survey').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/course/delete_data_update",
                    method:"POST",
                    data:{id_delete:sv_id,table_name:"lms_survey",field:"sv_id",field_status:"sv_status"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_survey(course_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.delete_survey_detail', function(){
            var svde_id = $(this).attr("id");
            var sv_id = $('#sv_id_detail').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/course/delete_data",
                    method:"POST",
                    data:{id_delete:svde_id,table_name:"lms_survey_de",field:"svde_id"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_survey_detail(sv_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.delete_ques', function(){
            var ques_id = $(this).attr("id");
            var course_id = $('#cos_id_question').val();
            var qiz_id = $('#qiz_id_question').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/course/delete_data_update",
                    method:"POST",
                    data:{id_delete:ques_id,table_name:"lms_ques",field:"ques_id",field_status:"ques_status"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_question(qiz_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.delete_quiz', function(){
            var qiz_id = $(this).attr("id");
            var cos_id = $('#course_id_quiz').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/course/delete_data_update",
                    method:"POST",
                    data:{id_delete:qiz_id,table_name:"lms_qiz",field:"qiz_id",field_status:"quiz_status"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_quiz(cos_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

        $(document).on('click', '.update_period', function(){
            var cosde_id = $(this).attr("id");
            console.log(cosde_id);

            $('#operation_pp').val("Edit");
            $('#cosde_id').val(cosde_id);
            var cos_id = $('#cos_id').val();
            var com_id = $('#com_id').val();
            $.ajax({
                  url: '<?=base_url()?>index.php/course/permission_course',
                  type: 'POST',
                  data:{com_id:com_id,course_id:cos_id,cosde_id:cosde_id},
                  success: function(data){
                    console.log(data);
                    $('#permission_div').html(data);
                  }
            });
            $.ajax({
                  url:"<?=base_url()?>index.php/course/update_course_detail_data",
                  method:"POST",
                  data:{cosde_id_update:cosde_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('#date_start_var').val(data.date_start_var);
                        $('#date_end_var').val(data.date_end_var);
                        $('#get_point').val(data.get_point);
                        $('#point_redeem').val(data.point_redeem);
                        var date_start = data.date_start_var.split(/[- :]/);
                        var date_end = data.date_end_var.split(/[- :]/);

                        // Apply each element to the Date function

                        var ddate_start = mysqlTimeStampToDate(data.date_start_var);
                        var date_end = mysqlTimeStampToDate(data.date_end_var);
                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: ddate_start,
                            endDate: date_end,
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#date_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#date_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
                  }
            });
            display_style('div_create_pp','div_pp');
        });
          function mysqlTimeStampToDate(timestamp) {
            //function parses mysql datetime string and returns javascript Date object
            //input has to be in this format: 2007-06-05 15:26:02
            var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
            var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
            return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
          }
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }
        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: 'dd/MM/yyyy'
        });
        jQuery('#date-range_les').datepicker({
            toggleActive: true,
            format: 'dd/MM/yyyy'
        });
        jQuery('#date-range_quiz').datepicker({
            toggleActive: true,
            format: 'dd/MM/yyyy'
        });
        jQuery('#date-range_survey').datepicker({
            toggleActive: true,
            format: 'dd/MM/yyyy'
        });

        $(document).on('submit', '#period_and_permission_form', function(event){
              event.preventDefault(); 
              var date_start = new Date($('#date_start').val());
              var date_end = new Date($('#date_end').val());
              var course_id = $('#course_id_pp').val();
              /*$('#date_start_var').val(formatDate(date_start));
              $('#date_end_var').val(formatDate(date_end));
              console.log(formatDate(date_start),formatDate(date_end));*/
                $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_period_and_permission",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            $('#period_and_permission_form')[0].reset();
                            display_style('div_create_pp','div_pp');
                            $('#course_id_pp').val(course_id);
                            fetch_data_detail(course_id);
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
                            $('#period_and_permission_form')[0].reset();
                        })
                    }else{
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
        });
      function onchange_month(){
        var month_select = $('#month_select').val();
        var cos_id = '<?php echo $cos_id; ?>';

            $.ajax({
              url:"<?=base_url()?>index.php/course/course_select_month",
              method:"POST",
              data:{cos_id:cos_id,month_select:month_select},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                $('#this_month').text(data.this_month);
              }
            });
      }
        $(function () {
            "use strict";
            // ============================================================== 
            // Sales overview
            // ============================================================== 
             new Chartist.Line('#sales-overview2', {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul' , 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                , series: [
                  {meta:"Earning ($)", data: [parseInt('<?php echo $chart_total[0]; ?>'),parseInt('<?php echo $chart_total[1]; ?>'),parseInt('<?php echo $chart_total[2]; ?>'),parseInt('<?php echo $chart_total[3]; ?>'),parseInt('<?php echo $chart_total[4]; ?>'),parseInt('<?php echo $chart_total[5]; ?>'),parseInt('<?php echo $chart_total[6]; ?>'),parseInt('<?php echo $chart_total[7]; ?>'),parseInt('<?php echo $chart_total[8]; ?>'),parseInt('<?php echo $chart_total[9]; ?>'),parseInt('<?php echo $chart_total[10]; ?>'),parseInt('<?php echo $chart_total[11]; ?>')]}
              ]
            }, {
                low: 0
                , showArea: true
                , divisor: 10
                , lineSmooth:false
                , fullWidth: true
                , showLine: true
                , chartPadding: 30
                , axisX: {
                    showLabel: true
                    , showGrid: false
                    , offset: 50
                }
                , plugins: [
                  Chartist.plugins.tooltip()
                ], 
                // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
                axisY: {
                  onlyInteger: true
                    , showLabel: true
                    , scaleMinSpace: 50 
                    , showGrid: true
                    , offset: 10,
                    labelInterpolationFnc: function(value) {
                  return value
                },

                }
                
            });
        });

    </script>
</body>

</html>