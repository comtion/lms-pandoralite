<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <style type="text/css">
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
        padding-right: 0px!important;
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
                      <div class="col-md-12" align="right">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create_managedepartment'); ?></button>
                          <button name="import_button" id="import_button" class="btn btn-outline-primary import_button" data-toggle="modal" data-target="#modal-import"><i class="mdi mdi-import"></i> <?php echo label('import_data_main'); ?></button>
                        <?php } ?>
                        <?php if($btn_print=="1"){ ?>
                          <button name="export_button" id="export_button" class="btn btn-outline-success export_button"><i class="mdi mdi-export"></i>  <?php echo label('export_data_main'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="15%"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="25%"><center><?php echo label('dep_name')." EN"; ?></center></th>
                                <th width="25%"><center><?php echo label('dep_name')." TH"; ?></center></th>
                                <th width="10%"><center><?php echo label('m_company'); ?></center></th>
                                <th width="20%"><center><?php echo label('com_createdate'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-account-key"></i></button> = <b><?php echo label('position'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    
    <div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="department_form" autocomplete="off" name="department_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dep_name_th"><b style="color: #FF2D00">*</b><?php echo label('dep_name')." TH"; ?>:</label>
                    <input type="text" id="dep_name_th" name="dep_name_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dep_name_en"><b style="color: #FF2D00">*</b><?php echo label('dep_name')." EN"; ?>:</label>
                    <input type="text" id="dep_name_en" name="dep_name_en" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                                <select class="form-control select2" required id="com_id" name="com_id"  style="width: 100%;">
                                </select>
                                <?php }else{ ?>
                                    <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly>
                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                <?php } ?>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label><?php echo label('dep_remark'); ?>:</label>
                    <textarea class="form-control" rows="3" id="dep_remark" name="dep_remark"></textarea>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="dep_id" name="dep_id">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div class="modal bs-example-modal-lg" id="modal-addposition" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 id="myLargeModalLabel"><?php echo label('position'); ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <form method="post" id="position_form" autocomplete="off" name="position_form" enctype="multipart/form-data"  class="form-horizontal row" role="form">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="posi_name_th"><b style="color: #FF2D00">*</b><?php echo label('posi_name')." TH"; ?>:</label>
                    <input type="text" id="posi_name_th" name="posi_name_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="posi_name_en"><b style="color: #FF2D00">*</b><?php echo label('posi_name')." EN"; ?>:</label>
                    <input type="text" id="posi_name_en" name="posi_name_en" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label><?php echo label('posi_remark'); ?>:</label>
                    <textarea class="form-control" rows="3" id="posi_remark" name="posi_remark"></textarea>
                  </div>
                </div>
                <input type="hidden" id="operation_position" name="operation_position" value="Add">
                <input type="hidden" id="dep_id_position" name="dep_id_position"><!-- id table lms_faq_q -->
                <input type="hidden" id="posi_id" name="posi_id">
                <div class="col-md-12" align="right">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action_position" id="action_position"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
                </div>
                </form>
                <hr>

                  <div class="row col-md-12 card">
                    <div class="card-body">
                      <div class="table-responsive">
                          <table id="tbtable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <?php if($btn_update=="1"||$btn_delete=="1"){ ?>
                                <th width="10%" align="center"><?php echo label('manage'); ?></th>
                                <?php } ?>
                                <th width="10%"></th>
                                <th width="30%" align="center"><?php echo label('posi_name')." EN"; ?></th>
                                <th width="30%" align="center"><?php echo label('posi_name')." TH"; ?></th>
                                <th width="20%" align="center"><?php echo label('posi_remark'); ?></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_update=="1"||$btn_delete=="1"){ ?> , <?php } ?><?php if($btn_delete=="1"){ ?><button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>

              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal" id="modal-import_user" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4><?php echo label('import_department'); ?></h4>
                  <button type="button" class="close btn_import" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="import_user_form" autocomplete="off" name="import_user_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                    <div class="col-md-6">
                        <label for="file_import"><b style="color: #FF2D00">*</b><?php echo 'Excel File'; ?>:</label>
                        <input type="file" name="file_import" required id="file_import" class="dropify"  accept=".xls,.xlsx" />
                        <?php echo label('certificate_example')." : "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/format_department_and_position.xlsx" download>format_department_and_position.xlsx</a>
                    </div>
                    <div class="col-md-6">
                        <h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('result_import'); ?>:</h4>
                        <div id="result_import_user" class="slimtest1" style="max-height: 300px;position: relative;"></div>
                    </div>
              </div>
              <input type="hidden" id="operation_import_user" name="operation_import_user" value="Add">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left btn_import" name="action_button" id="action_button"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat btn_import" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
    
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();
    $.fn.dataTable.ext.errMode = "none";
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
           $('#import_button').click(function(){
                $("#modal-import_user").modal({backdrop: false});
                clear_dropify('#file_import');
                $('#result_import_user').html('');
           });

           $('#export_button').click(function(){
                window.open('<?=base_url()?>exportdata/export_departmentandposition');
           });

          $(document).on('submit', '#import_user_form', function(event){
                event.preventDefault(); 
                $( ".btn_import" ).prop( "disabled", true );
                var file_import = $('#file_import').val();
                if(file_import!=""){
                  $("#myModal_process").modal('show');
                  $( document.body ).css( 'pointer-events', 'none' );
                  $.ajax({
                    url:"<?=base_url()?>index.php/setting/import_departandposi",
                    method:'POST',
                    data:new FormData(this),
                    contentType:false,
                    processData:false,
                    dataType:"json",
                        xhr: function() {
                          /*document.getElementById("progress_uploaduser_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                      $('#txt_progress_uploaduser').text(percentComplete.toFixed(2) + '%');

                                       $('.progress-bar-uploaduser').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;*/
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
                                            console.log(percentComplete.toFixed(0));
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
                      $( ".btn_import" ).prop( "disabled", false );
                      topFunction();
                      if(data.status=="2"){
                          $('#import_user_form')[0].reset();
                          swal(
                              '<?php echo label("after_upload_file"); ?>!',
                              ''
                          ).then(function () {
                              topFunction();
                              fetch_data_main(0);
                              $('#result_import_user').html(data.result);
                              clear_dropify('#file_import');
                          })
                      }else if(data.status=="1"){
                          swal({
                              title: '<?php echo label("manageimport_msgerror"); ?>',
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          }).then(function () {
                              document.getElementById("file_import").focus();
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
                              title: '<?php echo label("manageimport_msgerror"); ?>',
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          }).then(function () {
                              document.getElementById("file_import").focus();
                          })
                }
           });
        $('.select2').select2();
           $('#add_button').click(function(){
                $('#department_form')[0].reset();
                $('#operation').val("Add");
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("create_managedepartment"); ?>');
                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:''},
                        success: function(datawg){
                          $('#com_id').html(datawg);
                        }
                  });
                <?php } ?>
            });
         $(document).on('click', '.add_position', function(){
                var dep_id = $(this).attr("id");
                $("#modal-addposition").modal({backdrop: false});
                $('#position_form')[0].reset();
                $('#operation_position').val("Add");
                $('#dep_id_position').val(dep_id);
                fetch_data(dep_id,0);
            });

         function fetch_data(dep_id,page_num)
         {
            $('#tbtable').DataTable().destroy();
            var table = $('#tbtable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("tbtable", message);
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
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/manage/fetch_detail_position/'+dep_id,
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
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

      fetch_data_main(0);
      function fetch_data_main(page_num)
         {
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
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/manage/fetch_detail_department/',
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "num" },
                    { data: "dep_name_en" },
                    { data: "dep_name_th" },
                    { data: "company" },
                    { data: {
                        _:    "dep_modified.display",
                        sort: "dep_modified.timestamp"
                    } }
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
    $(document).ready(function() {

            $(document).on('submit', '#position_form', function(event){
              var dep_id_position = $('#dep_id_position').val();
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/insert_position_detail",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {

                          $('#position_form')[0].reset();
                          $('#dep_id_position').val(dep_id_position);
                          $('#operation_position').val("Add");
                          var table = $('#tbtable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data(dep_id_position,page_current);
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

            $(document).on('submit', '#department_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/insert_department",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#department_form')[0].reset();
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
                                      fetch_data_main(page_current);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("dep_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#dep_name").val("");
                            document.getElementById("dep_name").focus();
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

         $(document).on('click', '.delete', function(){
            var dep_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "<?php echo label('wg_delete'); ?>",   
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_department_data",
                    method:"POST",
                    data:{dep_id_delete:dep_id},
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
                                      fetch_data_main(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label('wg_msg_use'); ?>',
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

         $(document).on('click', '.delete_detail', function(){
            var posi_id = $(this).attr("id");
            var dep_id = $('#dep_id_position').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "<?php echo label('wg_delete'); ?>",   
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_position_data",
                    method:"POST",
                    data:{posi_id_delete:posi_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          var table = $('#tbtable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data(dep_id,page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label('wg_msg_use'); ?>',
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

          $(document).on('click', '.update', function(){
            var dep_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_department_data",
              method:"POST",
              data:{dep_id_update:dep_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("edit_managedepartment"); ?>');
                $('#department_form')[0].reset();
                $('#operation').val("Edit");
                $('#dep_remark').val(data.dep_remark);

                <?php if($com_admin!="com_associated"){ ?>
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:data.com_id},
                        success: function(data_company){
                          $('#com_id').html(data_company);
                          $('#com_id').val(data.com_id).trigger('change');
                        }
                  });
                <?php }else{ ?>
                  $('#com_id').val(data.com_id);  
                <?php } ?>
                $('#dep_name_th').val(data.dep_name_th); 
                $('#dep_name_en').val(data.dep_name_en); 
                $('#dep_id').val(data.dep_id);   
              }
            });
            
          });


          $(document).on('click', '.update_detail', function(){
            var posi_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_position_detail",
              method:"POST",
              data:{posi_id:posi_id},
              dataType:"json",
              success:function(data)
              {
                $('#position_form')[0].reset();
                $('#operation_position').val("Edit");
                $('#posi_name_th').val(data.posi_name_th);
                $('#posi_name_en').val(data.posi_name_en);
                $('#posi_remark').val(data.posi_remark);
                $('#posi_id').val(data.posi_id);
                $('#dep_id_position').val(data.dep_id);
              }
            });
            
          });
    });
    </script>
</body>

</html>