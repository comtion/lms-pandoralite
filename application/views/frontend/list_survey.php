<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- Page plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
    <style type="text/css">
        .clockpicker-popover {
            z-index: 999999;
        }
        #myModal_process.modal.show .modal-dialog{
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        margin: 0;
      }
      #myModal_process .circle strong{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:1.8em;
      }
      #myModal_process .circle canvas{
        visibility: hidden;
      }
      #myModal_process #circle-b{
        margin:0;
      }
      .dataTables_scrollHeadInner {
        width: 100%!important;
      }
      .dataTable {
        width: 100%!important;
      }

      #modal-detail-survey .form-control {
          background-color: #f0f0f0; /* สีพื้นหลังเป็นสีเทาอ่อน */
          color: #808080; /* สีข้อความเป็นสีเทา */
          border-color: #dcdcdc; /* สีขอบเป็นสีเทาอ่อน */
      }
      #modal-detail-survey .select2-container--default .select2-selection--single {
          background-color: #f0f0f0; /* สีพื้นหลังเป็นสีเทาอ่อน */
          color: #808080; /* สีข้อความเป็นสีเทา */
          border-color: #dcdcdc; /* สีขอบเป็นสีเทาอ่อน */
      }

      #modal-detail-survey .select2-container--default .select2-selection--multiple {
          background-color: #f0f0f0; /* สีพื้นหลังเป็นสีเทาอ่อน */
          color: #808080; /* สีข้อความเป็นสีเทา */
          border-color: #dcdcdc; /* สีขอบเป็นสีเทาอ่อน */
      }
      #modal-detail-course .mce-edit-area iframe {
          background-color: #f0f0f0 !important;
      }

      #modal-detail-course .mce-content {
          background-color: #f0f0f0 !important;
          color: #000 !important;
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
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(strtolower(label('dashboard'))); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                    </div>
                </div>  

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('sv_btn_survey_create'); ?></button>
                        <?php } ?>
                        <?php if($btn_print=="1"){ ?>
                          <button name="export_button" id="export_button" class="btn btn-outline-success export_button float-right"><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
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
                                <th width="15%" align="center"><center><?php echo label('sv_b_manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="5%" align="center"><center><?php echo label('sv_b_lang'); ?></center></th>
                                <th width="15%" align="center"><center><?php echo label('sv_b_name'); ?></center></th>
                                  <th width="10%"><center><?php echo label('da_approve_creator'); ?></center></th>
                                <th width="15%" align="center"><center><?php echo label('sv_b_period'); ?></center></th>
                                <th width="5%" align="center"><center><?php echo label('approval_status'); ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('sv_b_approvedate'); ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('sv_b_approver'); ?></center></th>
                                <th width="5%"><center><?php echo label('status'); ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('sv_b_update_date'); ?></center></th>
                                <!-- <th width="5%" align="center"><center><?php echo label('sv_b_approve'); ?></center></th> -->
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('sv_b_comment'); ?>:
                        <button type="button" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button> = <b><?php echo label('sv_b_demo'); ?></b> ,
                        <button type="button" class="btn btn-default btn-xs" style="background-color:#00d2d3;color:#ecf0f1;"><i class="mdi mdi-format-list-bulleted"></i></button> = <b><?php echo label('sv_b_list_user'); ?></b> ,
                        <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-comment-question-outline"></i></button> = <b><?php echo label('sv_b_question'); ?></b> ,
                        <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-magnify"></i></button> =
                          <b><?php echo label('sv_summary'); ?></b> ,
                        <button type="button" class="btn btn-default btn-xs" style="background-color: #34495e;color: #ecf0f1;" title="<?php echo label('sv_b_public'); ?>"><i class="mdi mdi-web"></i></button> = <b><?php echo label('sv_b_public'); ?></b> ,
                        <button type="button" class="btn btn-secondary btn-xs active"><i class="mdi mdi-alert text-warning"></i></button> = <b><?php echo label('d_waitapprove'); ?></b>
                        <!--  , <button type="button" class="btn btn-success btn-xs"><i class="mdi mdi-check"></i></button> = <b><?php echo label('d_approve'); ?></b> -->
                        <?php if($btn_update=="1"){ ?> , 
                        <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('sv_btn_edit'); ?></b>
                        <?php } ?>
                        <?php if($btn_delete=="1"){ ?> ,
                        <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('sv_btn_delete'); ?></b>
                        <?php } ?>
                      </p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_survey.php'); ?>
    <?php $this->load->view('frontend/modal/modal_survey_cannot_edit.php'); ?>

    
    <div class="modal fade bs-example-modal-lg" id="modal-surveylistuser" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-titlelistuser" id="myLargeModalLabel">Large modal</h4>
                    <button type="button" class="close import_user" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 card" id="div_listuser">
                        <div class="card-body">
                          <div class="col-md-12" align="right">
                              <button name="sv_adduser" id="sv_adduser" class="btn btn-outline-success sv_adduser"><i class="mdi mdi-account-multiple-plus"></i> <?php echo label('sv_btn_adduser'); ?></button>
                              <button name="sv_permission" id="sv_permission" class="btn btn-outline-info sv_permission"><i class="mdi mdi-account-key"></i> <?php echo label('sv_b_permission'); ?></button>

                          </div>
                          <div class="table-responsive">
                              <table id="myTable_listuser" width="100%" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_manage'); ?></center></th>
                                    <th width="5%"></th>
                                    <th width="15%" align="center"><center><?php echo label('sv_b_user_name'); ?></center></th>
                                    <th width="15%" align="center"><center><?php echo label('sv_b_company'); ?></center></th>
                                    <th width="15%" align="center"><center><?php echo label('sv_b_postname'); ?></center></th>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_start'); ?></center></th>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_done'); ?></center></th>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_status'); ?></center></th>
                                    <th width="5%" align="center">
                                        <center>
                                            <input type="checkbox" id="chkcolall_view" class="chkcolall_view checkboxheader" name="chkcolall_view" value="1">
                                            <label for="chkcolall_view"></label>
                                        </center>
                                      </th>
                                  </tr>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th colspan="2">
                                        <button type="button" class="btn btn-success btn-sm btn-block btn_sentmailmulti"><i class="mdi mdi-email-variant"></i> <?php echo label('sv_b_send_email_noti'); ?></button><br>
                                        <button type="button" class="btn btn-danger btn-sm btn-block btn_deletemulti"><i class="mdi mdi-window-close"></i> <?php echo label('sv_btn_delete'); ?></button>
                                    </th>
                                </tfoot>
                              </table>
                          </div>
                          <p><?php echo label('sv_b_comment'); ?>: <span id="btnsendmail"><button type="button" class="btn btn-success btn-xs"><i class="mdi mdi-email-variant"></i></button> = <b><?php echo label('sv_b_send_email_noti'); ?></b> , </span><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-backup-restore"></i></button> = <b><?php echo label('sv_btn_reset'); ?><?php if($btn_delete=="1"){ ?> , <?php } ?></b><?php if($btn_delete=="1"){ ?><button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('sv_btn_delete'); ?></b><?php } ?> </p>
                        </div>
                    </div>

                    <div class="col-md-12 card" id="div_permission" style="display: none;">
                        <div class="card-body">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger float-right previous_survey_listuser"><i class="mdi mdi-keyboard-return"></i> <?php echo label('sv_btn_previous'); ?></button>
                                <h3 id="permission_txt"><i class="mdi mdi-account-key"></i> <?php echo label('sv_b_permission'); ?></h3><hr>
                                <form  enctype="multipart/form-data" id="survey_permission_form" name="survey_permission_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                <input type="hidden" id="operation_pp" name="operation_pp" value="Add">
                                <input type="hidden" id="sv_id_pp" name="sv_id_pp">
                                <div id="div_permission_onsv"></div>

                                <div class="col-md-12 progress" id="progress_publicsurveypermisstion_div" style="display: none;">
                                    <div class="progress-bar-publicsurveypermisstion bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_publicsurveypermisstion"></span></div>
                                </div>
                                <div class="form-group col-md-12" align="right">
                                    <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('sv_btn_save'); ?></button>
                                    <button type="button" class="btn btn-outline-danger btn-flat previous_survey_listuser"><i class="mdi mdi-window-close"></i> <?php echo label('sv_btn_cancel'); ?></button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 card" id="div_adduser" style="display: none;">
                        <div class="card-body">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger float-right previous_survey_listuser import_user"><i class="mdi mdi-keyboard-return"></i> <?php echo label('sv_btn_previous'); ?></button>
                                <h3 id="adduser_txt"><i class="mdi mdi-account-multiple-plus"></i> <?php echo label('sv_btn_adduser'); ?></h3><hr>
                                <div class="form-group col-md-12" align="left">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group col-md-12 import_user_progress" style="display: none;" align="center">
                                                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 300px">
                                            </div>
                                            <div class="form-group col-md-12 import_user" align="left">
                                                <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('sv_b_import_data'); ?>:</label>

                                                <div class="switch">
                                                    <label><?php echo label('sv_btn_no'); ?><input type="checkbox"  id="status_add" name="status_add" checked onclick="onchange_statusadd()" value="1"><span class="lever switch-col-red"></span><?php echo label('sv_btn_yes'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12" id="div_empcode" style="display: none;" align="left">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label for="useri"><b style="color: #FF2D00">*</b><?php echo label('sv_b_user').' '; ?>: </label>
                                                    <input type="text" autocomplete="off" id="useri" name="useri" class="form-control"> 
                                                </div>
                                                <div class="col-md-5"><br>
                                                    <button type="button" onclick="add_employeetosurvey()" class="btn btn-outline-info btn-block"><i class="mdi mdi-plus"></i> <?php echo label('sv_btn_adduser'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12" id="div_upload" align="left">

                                            <form  enctype="multipart/form-data" id="upload_student_form" name="upload_student_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20 row">
                                                <input type="hidden" id="operation_student" name="operation_student" value="Add">
                                                <input type="hidden" id="sv_idimport" name="sv_idimport">
                                                <div class="col-md-6">
                                                    <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('sv_b_media_file'); ?>:</label>
                                                    <input type="file" name="importstudent" id="importstudent" class="dropify" accept=".xlsx,.xls" />
                                                    <?php echo label('sv_b_cert_example')." : "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/format_import_student.xlsx" download>format_import_student.xlsx</a>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('sv_b_result_import'); ?>:</h4>
                                                    <div id="result_import_student"></div>
                                                </div>
                                                <div class="col-md-12 progress" id="progress_publicsurveyimportuser_div" style="display: none;">
                                                    <div class="progress-bar-publicsurveyimportuser bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_publicsurveyimportuser"></span></div>
                                                </div>
                                                <div class="form-group col-md-12" align="right">
                                                    <button type="submit" class="btn btn-outline-success btn-flat pull-left import_user" name="action" id="action"><i class="mdi mdi-upload"></i> <?php echo label('upload_btn'); ?></button>
                                                    <button type="button" class="btn btn-outline-danger btn-flat previous_survey_listuser import_user"><i class="mdi mdi-window-close"></i> <?php echo label('sv_btn_cancel'); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div><hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="sv_id_listuser" name="sv_id_listuser">
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat import_user" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

      <!-- <div id="myModal_process" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body" align="center">
                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('sv_p_please_wait'); ?></h3>
              </div>
            </div>
        </div>
      </div> -->
      <div
        id="myModal_process"
        class="modal bs-example-modal-lg"
        role="dialog"
        aria-labelledby="smallModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body" align="center" style="max-height: 300px;">
              <div class="circle" id="circle-b">
                <strong></strong>
              </div>
            </div>
          </div>
        </div>
      </div>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <?php if($lang=="thai"){ ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
    <?php } ?>
    <?php $this->load->view('frontend/java/survey_java.php'); ?>
    <?php $this->load->view('frontend/java/survey_view_detail.php'); ?>
    <script type="text/javascript">
        function remove_character(str, char_pos) 
        {
            part1 = str.substring(0, char_pos);
            part2 = str.substring(char_pos + 1, str.length);
            return (part1 + part2);
        }
        $('#sv_expire_noti').keyup(function(){
            var value = $(this).val();
            var res = parseInt(value.charAt(value.length-1)); 
            var resBefore = parseInt(value.charAt(value.length-2)); 
            //Number.isInteger(123)
            if(resBefore!=""){
                if(!Number.isInteger(resBefore)){
                    if(!Number.isInteger(res)){
                        $('#sv_expire_noti').val(remove_character(value, value.length-1));
                    }
                }
            }
           // console.log(resBefore,res);
        });
        function clickCancel(modal_id){

            swal({
                title: '<?php echo label('confirm_tocancel'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('m_ok'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                    $('#'+modal_id).modal('hide');
              }
            })
        }

        function chkbox_lang(id,value){
            var remember = document.getElementById(id);
            var strArray = value.split("_");
            textarea_tinymce('sv_explanation_'+strArray[1]);
            textarea_tinymce('sv_detail_'+strArray[1]);
            if (remember.checked) {
              $('.'+value).show();
              document.getElementById('sv_title_'+strArray[1]).required = true;
            } else {
              $('.'+value).hide();
              document.getElementById('sv_title_'+strArray[1]).required = false;
            }
        }
        
        function val_lang(value_chk,value,required_val,lang){
            var strArray = value.split("_");
            if (value_chk=="1") {
              $('.'+value).show();
              /*if(required_val!=""){
                document.getElementById(required_val+lang).required = true;
              }*/
            } else {
              $('.'+value).hide();
              /*if(required_val!=""){
                document.getElementById(required_val+lang).required = false;
              }*/
            }
        }

        function add_employeetosurvey(){
            var sv_id = $('#sv_id_listuser').val();
            var useri = $('#useri').val();
            if(useri!=""){
                $("#myModal_process").modal('show');
                $( document.body ).css( 'pointer-events', 'none' );
              $.ajax({
                  url:"<?=base_url()?>index.php/insertdata/insert_emptosurvey",
                  method:'POST',
                  data:{useri:useri,sv_id:sv_id},
                  dataType:"json",
                        xhr: function() {
                          //document.getElementById("progress_cosmain_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                                console.log(xhr);
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;

                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(2),
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
                                      /*$(".progress").each(function() {

                                        //var value = $(this).attr('data-value');
                                        var left = $(this).find('.progress-left .progress-bar');
                                        var right = $(this).find('.progress-right .progress-bar');
                                        console.log(percentComplete.toFixed(2));
                                        if (percentComplete.toFixed(2) > 0) {
                                          if (percentComplete.toFixed(2) <= 50) {
                                            right.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2)) + 'deg)')
                                          } else {
                                            right.css('transform', 'rotate(180deg)')
                                            left.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2) - 50) + 'deg)')
                                          }
                                        }
                                        $('#txt_progress_cosmain').text(percentComplete.toFixed(2) + '%');
                                      })*/
                                      /*
                                      console.log(percentComplete.toFixed(2));
                                      //$('.progress-bar-cosmain').data('percentage', percentComplete);
                                       $('.progress-bar-cosmain').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });*/
                                      //Do something with upload progress here
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
                        var body = document.body;
                        body.classList.add("modal-open");
                    if(data.status=="2"){
                        $('#useri').val('');
                        swal(
                            '<?php echo label("sv_p_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            document.getElementById('div_listuser').style.display = "";
                            document.getElementById('div_permission').style.display = "none";
                            document.getElementById('div_adduser').style.display = "none";
                            fetch_data_user(0);
                        })
                    }else if(data.status=="1"){
                        swal({
                            title: '<?php echo label("sv_p_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        }).then(function () {
                            $('#useri').val('');
                            document.getElementById("useri").focus();
                        })
                    }else{
                        swal({
                            title: '<?php echo label("sv_p_add_emptocourse_error"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        }).then(function () {
                            $('#useri').val('');
                            document.getElementById("useri").focus();
                        })
                    }

                  }
                });
            }else{
                        swal({
                            title: '<?php echo label("sv_p_add_emptocourse_error"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        }).then(function () {
                            $('#useri').val('');
                            document.getElementById("useri").focus();
                        })
            }
        }

        $(document).on('submit', '#upload_student_form', function(event){
              event.preventDefault();
              //$("#myModal_process").modal({backdrop: false});
              var sv_id = $('#sv_idimport').val();
              var importstudent = $('#importstudent').val();
            if(importstudent!=""){
              $('.import_user').hide();
              $('.import_user_progress').show();
                $("#myModal_process").modal('show');
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/insertdata/upload_user_survey",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:"json",
                                                        xhr: function() {/*
                                                          document.getElementById("progress_publicsurveyimportuser_div").style.display = "";
                                                              var xhr = new window.XMLHttpRequest();
                                                              xhr.upload.addEventListener("progress", function(evt) {
                                                                  if (evt.lengthComputable) {
                                                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                                                      $('#txt_progress_publicsurveyimportuser').text(percentComplete.toFixed(2) + '%');

                                                                       $('.progress-bar-publicsurveyimportuser').animate({
                                                                        width: percentComplete + '%'
                                                                       }, {
                                                                        duration: 100
                                                                       });
                                                                      //Do something with upload progress here
                                                                  }
                                                             }, false);
                                                             return xhr;*/
                          //document.getElementById("progress_cosmain_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                                console.log(xhr);
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;

                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(2),
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
                                      /*$(".progress").each(function() {

                                        //var value = $(this).attr('data-value');
                                        var left = $(this).find('.progress-left .progress-bar');
                                        var right = $(this).find('.progress-right .progress-bar');
                                        console.log(percentComplete.toFixed(2));
                                        if (percentComplete.toFixed(2) > 0) {
                                          if (percentComplete.toFixed(2) <= 50) {
                                            right.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2)) + 'deg)')
                                          } else {
                                            right.css('transform', 'rotate(180deg)')
                                            left.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2) - 50) + 'deg)')
                                          }
                                        }
                                        $('#txt_progress_cosmain').text(percentComplete.toFixed(2) + '%');
                                      })*/
                                      /*
                                      console.log(percentComplete.toFixed(2));
                                      //$('.progress-bar-cosmain').data('percentage', percentComplete);
                                       $('.progress-bar-cosmain').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });*/
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;
                                                        },
                  success:function(data)
                  {
                    /*document.getElementById("progress_publicsurveyimportuser_div").style.display = "none";
                    $( document.body ).css( 'pointer-events', '' );*/
                    $( document.body ).css( 'pointer-events', '' );
                      $('#myModal_process').modal('hide');
                      $("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");
                        var body = document.body;
                        body.classList.add("modal-open");
                    //$( "#myModal_process" ).modal( "hide" );
                    topFunction();

                    $('.import_user').show();
                    $('.import_user_progress').hide();
                    $('#result_import_student').html('');
                    if(data.status=="2"){
                        swal(
                            '<?php echo label("after_upload_file"); ?>!',
                            ''
                        ).then(function () {
                            topFunction();
                            $('#result_import_student').html(data.result);
                            $('#upload_student_form')[0].reset();
                            $('#sv_idimport').val(sv_id);
                            clear_dropify('importstudent');
                            fetch_data_user(0);
                        })
                    }else if(data.status=="1"){
                        swal({
                            title: '<?php echo label("sv_p_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        }).then(function () {
                            $('#upload_student_form')[0].reset();
                            $('#sv_idimport').val(sv_id);
                        })
                    }else{
                        swal({
                            title: '<?php echo label("sv_p_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        })
                    }

                  }
                });
            }else{
                        swal({
                            title: '<?php echo label("sv_p_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        })
            }
        });
        function onchange_statusadd(){
            var sv_id = $('#sv_id_listuser').val();
            var status_add  = document.getElementById('status_add');
            if(!status_add.checked){
                document.getElementById('div_empcode').style.display = '';
                document.getElementById('div_upload').style.display = 'none';
            }else{
                $('#sv_idimport').val(sv_id);
                clear_dropify('importstudent');
                document.getElementById('div_empcode').style.display = 'none';
                document.getElementById('div_upload').style.display = '';
                $('#result_import_student').html('');
            }
        }
         $(document).on('click', '.sv_permission', function(){
            document.getElementById('div_listuser').style.display = "none";
            document.getElementById('div_permission').style.display = "";
            document.getElementById('div_adduser').style.display = "none";
            var sv_id = $('#sv_id_listuser').val();
            $('#sv_id_pp').val(sv_id);
            $.ajax({
                  url: '<?=base_url()?>index.php/querydata/permission_survey',
                  type: 'POST',
                  data:{sv_id:sv_id},
                  success: function(data){
                    
                    $('#div_permission_onsv').html(data);
                  }
            });
         });
         $(document).on('click', '.sv_adduser', function(){
            document.getElementById('div_listuser').style.display = "none";
            document.getElementById('div_permission').style.display = "none";
            document.getElementById('div_adduser').style.display = "";
            var sv_id = $('#sv_id_listuser').val();
            onchange_statusadd();
         });

        $(document).on('click', '.previous_survey_listuser', function(){
            document.getElementById('div_listuser').style.display = "";
            document.getElementById('div_permission').style.display = "none";
            document.getElementById('div_adduser').style.display = "none";
            fetch_data_user(0);
        });

        function onchkdep(dep_id,com_id){
          var dep_ = document.getElementById('chkdep_'+dep_id);
          if(dep_.checked == true){
            $('.chkdepall_'+dep_id).show();
            $('#div_posiofdep'+dep_id).show();
            $(".chkposiall_"+dep_id).prop('checked', true);
          }else{
            $('#div_posiofdep'+dep_id).hide();
            // $('.chkdepall_'+dep_id).hide();
            $(".chkposiall_"+dep_id).prop('checked', false);
          }
          if($('[data-com="'+com_id+'"]').is(':checked')){
            $("#chkcom_"+com_id).prop('checked', true); //true
          }else{
            $("#chkcom_"+com_id).prop('checked', false);
          }
        }

        function onchkcom(com_id){
          var com_ = document.getElementById('chkcom_'+com_id);
          if(com_.checked == true){
            $('#div_depofcompany'+com_id).show();
            $('[data-com="'+com_id+'"]').show();
            $('#divallcom_'+com_id).show();
            $('#chkallcom_'+com_id).prop('checked', true);
            $('.chkall_'+com_id).show();
            $('[data-com="'+com_id+'"]').prop('checked', true);
          }else{
            $('#div_depofcompany'+com_id).hide();
            $('[data-com="'+com_id+'"]').hide();
            $('.chkall_'+com_id).hide();
            $('[data-com="'+com_id+'"]').prop('checked', false);
            $('#chkallcom_'+com_id).prop('checked', false);
            $('#divallcom_'+com_id).hide();
          }
        }

        function onchkallcom(com_id){
          var allcom_ = document.getElementById('chkallcom_'+com_id);
          if(allcom_.checked == true){
            $('[data-com="'+com_id+'"]').prop('checked', true);
            $('#chkcom_'+com_id).prop('checked', true);
          }else{
            $('[data-com="'+com_id+'"]').prop('checked', false);
            $('#chkcom_'+com_id).prop('checked', false);
          }
        }

        function onchkposi(posi_id,com_id,dep_id){
          var posi_ = document.getElementById('chkposi_'+posi_id);
          if($('[data-com="'+com_id+'"]').is(':checked')){
            $("#chkcom_"+com_id).prop('checked', true);
          }else{
            $("#chkcom_"+com_id).prop('checked', false);
          }
          if($('[data-dep="'+dep_id+'"]').is(':checked')){
            $("#chkdep_"+dep_id).prop('checked', true);
          }else{
            $("#chkdep_"+dep_id).prop('checked', false);
          }
        }


        $(document).on('submit', '#survey_permission_form', function(event){
              event.preventDefault(); 
              const sv_id_pp = $('#sv_id_pp').val();
              const positions = $.map($('input[type=checkbox][name="posi_var[]"]:checked'), function(el) { return el.value; });

                $("#myModal_process").modal('show');
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/insertdata/insert_sv_permission",
                  method:'POST',
                  data: {
                    sv_id_pp: sv_id_pp,
                    posi_var: positions.join(",")
                  },
                  dataType:"json",
                                                        xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;

                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(2),
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
                                      /*$(".progress").each(function() {

                                        //var value = $(this).attr('data-value');
                                        var left = $(this).find('.progress-left .progress-bar');
                                        var right = $(this).find('.progress-right .progress-bar');
                                        console.log(percentComplete.toFixed(2));
                                        if (percentComplete.toFixed(2) > 0) {
                                          if (percentComplete.toFixed(2) <= 50) {
                                            right.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2)) + 'deg)')
                                          } else {
                                            right.css('transform', 'rotate(180deg)')
                                            left.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2) - 50) + 'deg)')
                                          }
                                        }
                                        $('#txt_progress_cosmain').text(percentComplete.toFixed(2) + '%');
                                      })*/
                                      /*
                                      console.log(percentComplete.toFixed(2));
                                      //$('.progress-bar-cosmain').data('percentage', percentComplete);
                                       $('.progress-bar-cosmain').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });*/
                                      //Do something with upload progress here
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
                      var body = document.body;
                      body.classList.add("modal-open");
                    /*$( document.body ).css( 'pointer-events', '' );
                    document.getElementById("progress_publicsurveypermisstion_div").style.display = "none";*/
                    if(data.status=="2"){
                        $('#survey_permission_form')[0].reset();
                        swal(
                            '<?php echo label("sv_p_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            document.getElementById('div_listuser').style.display = "";
                            document.getElementById('div_permission').style.display = "none";
                            document.getElementById('div_adduser').style.display = "none";
                            fetch_data_user(0);
                        })
                    }else{
                        swal({
                            title: '<?php echo label("sv_p_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                        })
                    }
                   
                  }
                });
            });
    </script>
</body>

</html>