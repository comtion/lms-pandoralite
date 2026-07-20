<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
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

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create_questionnaire'); ?></button>
                        <?php } ?>
                        <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                  <label for="com_id_search"><?php echo label('com_name'); ?>:</label>
                                  <select class="form-control select2" id="com_id_search" name="com_id_search" style="width: 100%;">
                                    <?php   if(countArray($company_arr)>0){ ?>
                                                <optgroup label="<?php echo label('please_com_name'); ?>">
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
                                  <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                  <th width="5%"></th>
                                  <th width="15%"><center><?php echo label('faqlang'); ?></center></th>
                                  <th width="55%"><center><?php echo label('questionnaire_temp_name'); ?></center></th>
                                  <th width="15%"><center><?php echo label('m_updatedate'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-comment-question-outline"></i></button> = <b><?php echo label('question'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <div class="modal fade bs-example-modal-lg"  id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="questionnaire_form" autocomplete="off" name="questionnaire_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                    <select class="form-control select2" id="com_id" name="com_id"  style="width: 100%;">
                            <option value=""><?php echo label('please_com_name'); ?></option>
                          <?php foreach( $company_select as $company ){ ?>
                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?></option>
                          <?php } ?>
                    </select>
                  </div>
                </div>
                <?php }else{ ?>
                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                <?php } ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b style="color: #FF2D00">*</b><?php echo label('ceCforlang'); ?>:</label><br>
                        <input type="checkbox" id="qn_lang_eng" name="qn_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qn_lang_eng','input_ques_eng','qn_title_','1')" value="eng" <?php if($lang=="english"){ echo "checked";} ?>/>
                        <label for="qn_lang_eng"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></label><br>
                        <input type="checkbox" id="qn_lang_th" name="qn_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qn_lang_th','input_ques_th','qn_title_','1')" value="th" <?php if($lang=="thai"){ echo "checked";} ?>/>
                        <label for="qn_lang_th"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></label><br>
                        <input type="checkbox" id="qn_lang_jp" name="qn_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qn_lang_jp','input_ques_jp','qn_title_','1')" value="jp" <?php if($lang=="japan"){ echo "checked";} ?>/>
                        <label for="qn_lang_jp"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></label>
                    </div>
                </div>

                <div class="col-md-12 input_ques_eng" style="display: none;">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                        <div class="ribbon-content row">
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('questionnaire_temp_name'); ?>:</label>
                              <textarea name="qn_title_eng" id="qn_title_eng" rows="10" class="form-control"></textarea>
                          </div>
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><?php echo label('svdesc'); ?>:</label>
                              <textarea name="qn_explanation_eng" id="qn_explanation_eng" rows="10" class="form-control"></textarea>
                          </div>
                        </div>
                    </div>
                </div>
              <div class="col-md-12 input_ques_th" style="display: none;">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                        <div class="ribbon-content row">
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('questionnaire_temp_name'); ?>:</label>
                              <textarea name="qn_title_th" id="qn_title_th" rows="10" class="form-control"></textarea>
                          </div>
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><?php echo label('svdesc'); ?>:</label>
                              <textarea name="qn_explanation_th" id="qn_explanation_th" rows="10" class="form-control"></textarea>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 input_ques_jp" style="display: none;">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                        <div class="ribbon-content row">
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('questionnaire_temp_name'); ?>:</label>
                              <textarea name="qn_title_jp" id="qn_title_jp" rows="10" class="form-control"></textarea>
                          </div>
                          <div class="form-group col-md-6">
                              <label class="control-label text-right"><?php echo label('svdesc'); ?>:</label>
                              <textarea name="qn_explanation_jp" id="qn_explanation_jp" rows="10" class="form-control"></textarea>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quessuggestion_status'); ?>:</label>
                  <div class="switch">
                      <label><?php echo label('sv_btn_no'); ?><input type="checkbox"  id="qn_suggestion_status" name="qn_suggestion_status" checked value="1"><span class="lever switch-col-indigo"></span><?php echo label('sv_btn_yes'); ?></label>
                  </div>
                </div>
                <!-- <div class="col-md-3">
                  <div class="form-group">
                    <label for="qn_lang"><b style="color: #FF2D00">*</b><?php echo label('faqlang'); ?>:</label>
                    <select class="form-control" required id="qn_lang" name="qn_lang">
                      <option value="th" selected><?php echo label('thai'); ?></option>
                      <option value="eng"><?php echo label('eng'); ?></option>
                      <option value="jp"><?php echo label('jp'); ?></option>
                    </select>
                  </div>
                </div> -->

                <div class="form-group col-md-6" id="div_upload" style="margin: 0px auto 10px auto;">
                    <label for="qn_filename"><?php echo label('quesFile'); ?>:</label>
                    <input type="file" name="qn_filename" id="qn_filename" class="dropify" accept=".xlsx,.xls" />
                    <?php echo label('certificate_example').": "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/format_import_survey.xlsx" download>format_import_survey.xlsx</a>
                    <!-- <?php echo label('certificate_example').": "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/example_questionnaire.xlsx" download>example_questionnaire.xlsx</a> -->
                </div>
                <?php if($com_admin!="com_associated"){ ?>
                <div class="form-group col-md-6" style="margin: 0px auto 10px auto;">
                </div>
                <?php } ?>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="qn_id" name="qn_id">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action_main" id="action_main"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" id="modal-qn_detail" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 id="myLargeModalLabel"><?php echo label('editques'); ?>: <span id="txt_head_detail"></span></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body row">
                <div class="col-md-12">
                  <form method="post" id="qn_detail_form" autocomplete="off" name="qn_detail_form" enctype="multipart/form-data"  class="form-horizontal row" role="form">
                    <input type="hidden" id="qn_id_detail" name="qn_id_detail">
                    <div class="col-md-12 input_surveydetail_eng">
                        <div class="ribbon-wrapper card">
                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                            <div class="ribbon-content row">
                              <div class="form-group col-md-12">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                                  <input name="qnde_heading_eng" type="text" class="form-control" id="qnde_heading_eng">
                              </div>
                              <div class="form-group col-md-12">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quesdetail'); ?>:</label>
                                  <textarea name="qnde_detail_eng" id="qnde_detail_eng" class="form-control"  rows="5"></textarea>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 input_surveydetail_th">
                        <div class="ribbon-wrapper card">
                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                            <div class="ribbon-content row">
                              <div class="form-group col-md-12">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                                  <input name="qnde_heading_th" type="text" class="form-control" id="qnde_heading_th">
                              </div>
                              <div class="form-group col-md-12">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quesdetail'); ?>:</label>
                                  <textarea name="qnde_detail_th" id="qnde_detail_th" class="form-control" rows="5"></textarea>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 input_surveydetail_jp">
                        <div class="ribbon-wrapper card">
                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                            <div class="ribbon-content row">
                              <div class="form-group col-md-12 input_jp">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                                  <input name="qnde_heading_jp" type="text" class="form-control" id="qnde_heading_jp">
                              </div>
                              <div class="form-group col-md-12 input_jp">
                                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quesdetail'); ?>:</label>
                                  <textarea name="qnde_detail_jp" id="qnde_detail_jp" class="form-control"  rows="5"></textarea>
                              </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="operation_detail" name="operation_detail" value="Add">
                    <input type="hidden" id="qnde_id" name="qnde_id">
                    <div class="form-group col-md-12" align="right">
                        <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                        <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
                    </div>
                  </form>
                  <hr>
                      <div class="table-responsive">
                          <table id="myTable_detail" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                  <th width="10%"><center><?php echo label('manage'); ?></center></th>
                                  <th width="10%"></th>
                                  <th width="30%"><center><?php echo label('questitle'); ?></center></th>
                                  <th width="50%"><center><?php echo label('quesdetail'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/userCode.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript">

        $.fn.dataTable.ext.errMode = "none";

        function chkbox_lang(id,value,required_val,onclickval=0){
            var remember = document.getElementById(id);
            var strArray = value.split("_");
            document.getElementById("qn_title_th").required = false;
            document.getElementById("qn_title_eng").required = false;
            document.getElementById("qn_title_jp").required = false;
            if (remember.checked) {
              $('.'+value).show();
              document.getElementById(required_val+strArray[2]).required = true;

              $('#action_main').prop('disabled', false);
            } else {
              $('#action_main').prop('disabled', false);

              var checkedAry= [];
              $.each($("input[name='qn_lang[]']:checked"), function () {
                  checkedAry.push($(this).attr("id"));
              });
              var operation = $('#operation').val();
              if(operation=="Edit"&&onclickval==1){
                  swal({
                      title: '<?php echo label('noti_lang_uncheck'); ?> ',
                      text: "",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: "#1abc9c",   
                      cancelButtonColor: "#DD6B55",   
                      confirmButtonText: '<?php echo label('m_ok'); ?>',
                      cancelButtonText: '<?php echo label('cancel'); ?>'
                  }).then(function (isChk) {
                    if(isChk.value){
                      $('.'+value).hide();
                      document.getElementById(required_val+strArray[2]).required = false;
                      /*if(checkedAry.length>0){
                          $('#action_main').prop('disabled', false);
                      }else{
                          $('#action_main').prop('disabled', true);
                      }*/
                    }else{
                      $('.'+value).show();
                      $('#'+id).prop('checked', true);
                    }
                  })
              }else{
                  $('.'+value).hide();
                  document.getElementById(required_val+strArray[2]).required = false;
                  /*if(checkedAry.length>0){
                    $('#action_main').prop('disabled', false);
                  }else{
                    //console.log('367');
                    $('#action_main').prop('disabled', true);
                  }*/
              }
              document.getElementById(required_val+strArray[2]).required = false;
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
        function val_lang(value_chk,value,required_val,lang){
            var strArray = value.split("_");
            if (value_chk=="1") {
              $('.'+value).show();
                document.getElementById('qnde_heading_'+lang).required = true;
                document.getElementById('qnde_detail_'+lang).required = true;
            } else {
              $('.'+value).hide();
                document.getElementById('qnde_heading_'+lang).required = false;
                document.getElementById('qnde_detail_'+lang).required = false;
            }
        }
        $('.dropify').dropify();
        $('.select2').select2();
        $('#add_button').click(function(){
                document.getElementById("qn_title_th").required = false;
                document.getElementById("qn_title_eng").required = false;
                document.getElementById("qn_title_jp").required = false;
                $('.input_ques_th').hide();
                $('.input_ques_eng').hide();
                $('.input_ques_jp').hide();
                $('#questionnaire_form')[0].reset();
                $("input[name='qn_lang[]']:checked").each(function ()
                {
                  if($(this).val()=="th"){
                    chkbox_lang('qn_lang_th','input_ques_th','qn_title_');
                  }else if($(this).val()=="eng"){
                    chkbox_lang('qn_lang_eng','input_ques_eng','qn_title_');
                  }else if($(this).val()=="jp"){
                    chkbox_lang('qn_lang_jp','input_ques_jp','qn_title_');
                  }
                });
                $('.modal-title').text('<?php echo label("create_questionnaire"); ?>');
                $('#questionnaire_form')[0].reset();
                $('#operation').val("Add");

                  <?php if($com_admin=="com_associated"){ ?>
                    var com_id = '<?php echo $com_id; ?>';
                  <?php }else{ ?>
                  var com_id = $('#com_id_search').val();
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:com_id},
                        success: function(data_company){
                            $('#com_id').html(data_company);
                            //$('#com_id').val($('#com_id option:first-child').val()).trigger('change');
                        }
                  });
                  <?php } ?>
                document.getElementById('div_upload').style.display = '';
                  clear_dropify('#qn_filename');
        });

        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data(0);
        });
        fetch_data(0);
        function fetch_data(page_num=0)
         {
            var com_id = $('#com_id_search').val();
            $('#myTable').DataTable().destroy();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
                "ajax": {
                    url : '<?=base_url()?>index.php/questionnaire/fetch_questionnaire/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "num" },
                    { data: "faqlang" },
                    { data: "questionnaire_temp_name" },
                    { data: {
                        _:    "m_updatedate.display",
                        sort: "m_updatedate.timestamp"
                    } },
                ],
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
         }
            
        function fetch_data_detail(qn_id,page_num=0)
         {
            $('#myTable_detail').DataTable().destroy();
            var table = $('#myTable_detail').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_detail", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
                "ajax": {
                    url : '<?=base_url()?>index.php/questionnaire/fetch_questionnaire_detail/',
                    type : 'GET',
                    data : {
                      qn_id:qn_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
         }

        $(document).on('submit', '#questionnaire_form', function(event){
              event.preventDefault(); 

              var checkedAry= [];
              $.each($("input[name='qn_lang[]']:checked"), function () {
                  checkedAry.push($(this).attr("id"));
              });
              if(checkedAry.length>0){
                $.ajax({
                  url:"<?=base_url()?>index.php/questionnaire/insert_questionnaire",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#questionnaire_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var table = $('#myTable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data(page_current);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("questionnaire_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $('#questionnaire_form')[0].reset();
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
        });

          $(document).on('click', '.update_questionnaire', function(){
            var qn_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/questionnaire/update_questionnaire_data",
              method:"POST",
              data:{id_update:qn_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("edit_questionnaire"); ?>');
                $('#operation').val("Edit");
                document.getElementById('div_upload').style.display = 'none';
                $('#questionnaire_form')[0].reset();
                $('#qn_title_th').val(data.qn_title_th); 
                $('#qn_title_eng').val(data.qn_title_eng); 
                $('#qn_title_jp').val(data.qn_title_jp); 
                $('#qn_explanation_th').val(data.qn_explanation_th); 
                $('#qn_explanation_eng').val(data.qn_explanation_eng); 
                $('#qn_explanation_jp').val(data.qn_explanation_jp);
                $('.input_ques_th').hide();
                $('.input_ques_eng').hide();
                $('.input_ques_jp').hide();
                document.getElementById("qn_title_th").required = false;
                document.getElementById("qn_title_eng").required = false;
                document.getElementById("qn_title_jp").required = false;
                        if(data.isTH=="1"){
                            document.getElementById("qn_lang_th").checked = true;
                            chkbox_lang('qn_lang_th','input_ques_th','qn_title_');
                            $('#qn_title_th').val(data.qn_title_th);
                            $('#qn_explanation_th').val(data.qn_explanation_th);
                        }else{
                            document.getElementById("qn_lang_th").checked = false;
                            $('.input_ques_th').hide();
                        }
                        if(data.isENG=="1"){
                            document.getElementById("qn_lang_eng").checked = true;
                            chkbox_lang('qn_lang_eng','input_ques_eng','qn_title_');
                            $('#qn_title_eng').val(data.qn_title_eng);
                            $('#qn_explanation_eng').val(data.qn_explanation_eng);
                        }else{
                            document.getElementById("qn_lang_eng").checked = false;
                            $('.input_ques_eng').hide();
                        }
                        if(data.isJP=="1"){
                            document.getElementById("qn_lang_jp").checked = true;
                            chkbox_lang('qn_lang_jp','input_ques_jp','qn_title_');
                            $('#qn_title_jp').val(data.qn_title_jp);
                            $('#qn_explanation_jp').val(data.qn_explanation_jp);
                        }else{
                            document.getElementById("qn_lang_jp").checked = false;
                            $('.input_ques_jp').hide();
                        }
                  <?php if($com_admin=="com_associated"){ ?>
                    $('#com_id').val(data.com_id);  
                  <?php }else{ ?>
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:data.com_id},
                        success: function(data_company){
                            $('#com_id').html(data_company);
                            $('#com_id').val(data.com_id).trigger('change');
                        }
                  });
                  <?php } ?>
                if(data.qn_suggestion_status=="0"){
                  document.getElementById("qn_suggestion_status").checked = false;
                }else{
                  document.getElementById("qn_suggestion_status").checked = true;
                }
                $('#qn_id').val(data.qn_id);  
              }
            });
          });


          $(document).on('click', '.update_questionnaire_detail', function(){
            var qnde_id = $(this).attr("id");
            var qn_id = $('#qn_id_detail').val();
            $.ajax({
              url:"<?=base_url()?>index.php/questionnaire/update_questionnaire_detail_data",
              method:"POST",
              data:{id_update:qnde_id},
              dataType:"json",
              success:function(data)
              {
                $('#qn_detail_form')[0].reset();
                $('#operation_detail').val("Edit");
                $('#qnde_id').val(qnde_id); 
                $('#qn_id_detail').val(qn_id);
                val_lang('0','input_surveydetail_th','','th');
                val_lang('0','input_surveydetail_eng','','eng');
                val_lang('0','input_surveydetail_jp','','jp');
                document.getElementById("qnde_heading_th").required = false;
                document.getElementById("qnde_detail_th").required = false;
                document.getElementById("qnde_heading_eng").required = false;
                document.getElementById("qnde_detail_eng").required = false;
                document.getElementById("qnde_heading_jp").required = false;
                document.getElementById("qnde_detail_jp").required = false;

                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_qn',
                      type: 'POST',
                      data:{qn_id:qn_id},
                      dataType:"json",
                      success: function(datalang){
                        for (var i = datalang.arr_lang.length - 1; i >= 0; i--) {
                          if(datalang.arr_lang[i]=="th"){
                            val_lang('1','input_surveydetail_th','','th');
                            $('#qnde_heading_th').val(data.qnde_heading_th); 
                            $('#qnde_detail_th').val(data.qnde_detail_th); 
                          }
                          if(datalang.arr_lang[i]=="eng"){
                            val_lang('1','input_surveydetail_eng','','eng');
                            $('#qnde_heading_eng').val(data.qnde_heading_eng); 
                            $('#qnde_detail_eng').val(data.qnde_detail_eng); 
                          }
                          if(datalang.arr_lang[i]=="jp"){
                            val_lang('1','input_surveydetail_jp','','jp');
                            $('#qnde_heading_jp').val(data.qnde_heading_jp); 
                            $('#qnde_detail_jp').val(data.qnde_detail_jp); 
                          }
                        }
                        $('#fil_lang').val(datalang.val_lang);
                      }
                });
              }
            });
          });

          $(document).on('click', '.questionnaire_detail', function(){
            var qn_id = $(this).attr("id");
            var lang = '<?php echo $lang; ?>';
            $("#modal-qn_detail").modal({backdrop: false});
            $('#qn_detail_form')[0].reset();
            $('#qn_id_detail').val(qn_id);
            var table = $('#myTable_detail').DataTable();
            var info = table.page.info();
            var length = info.pages;
            var page_current = info.page;
            fetch_data_detail(qn_id,page_current);
            $('#operation_detail').val("Add");
            $.ajax({
              url:"<?=base_url()?>index.php/questionnaire/update_questionnaire_data",
              method:"POST",
              data:{id_update:qn_id},
              dataType:"json",
              success:function(data)
              {
                  $('#txt_head_detail').text(data.txt_head_detail); 
              }
            });
                val_lang('0','input_surveydetail_th','','th');
                val_lang('0','input_surveydetail_eng','','eng');
                val_lang('0','input_surveydetail_jp','','jp');
                document.getElementById("qnde_heading_th").required = false;
                document.getElementById("qnde_detail_th").required = false;
                document.getElementById("qnde_heading_eng").required = false;
                document.getElementById("qnde_detail_eng").required = false;
                document.getElementById("qnde_heading_jp").required = false;
                document.getElementById("qnde_detail_jp").required = false;

                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_qn',
                      type: 'POST',
                      data:{qn_id:qn_id},
                      dataType:"json",
                      success: function(datalang){
                        for (var i = datalang.arr_lang.length - 1; i >= 0; i--) {
                          if(datalang.arr_lang[i]=="th"){
                            val_lang('1','input_surveydetail_th','','th');
                          }
                          if(datalang.arr_lang[i]=="eng"){
                            val_lang('1','input_surveydetail_eng','','eng');
                          }
                          if(datalang.arr_lang[i]=="jp"){
                            val_lang('1','input_surveydetail_jp','','jp');
                          }
                        }
                        $('#fil_lang').val(datalang.val_lang);
                      }
                });
          });

        $(document).on('submit', '#qn_detail_form', function(event){
              event.preventDefault(); 
              var qn_id = $('#qn_id_detail').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/questionnaire/insert_questionnaire_detail",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#qn_detail_form')[0].reset();
                        $('#qn_id_detail').val(qn_id);
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            var table = $('#myTable_detail').DataTable();
                            var info = table.page.info();
                            var length = info.pages;
                            var page_current = info.page;
                            fetch_data_detail(qn_id,page_current);

                            $('#operation_detail').val("Add");
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


         $(document).on('click', '.delete_questionnaire', function(){
            var qn_id = $(this).attr("id");
            //var qn_id = $('#qn_id_detail').val();
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
                    data:{id_delete:qn_id,table_name:"lms_questionnaire",field:"qn_id"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          var table = $('#myTable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
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

         $(document).on('click', '.delete_questionnaire_detail', function(){
            var qnde_id = $(this).attr("id");
            var qn_id = $('#qn_id_detail').val();
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
                    data:{id_delete:qnde_id,table_name:"lms_questionnaire_de",field:"qnde_id"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                            var table = $('#myTable_detail').DataTable();
                            var info = table.page.info();
                            var length = info.pages;
                            var page_current = info.page;
                            fetch_data_detail(qn_id,page_current);
                            $('#qn_id_detail').val(qn_id);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
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
    </script>
</body>

</html>