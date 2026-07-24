<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <style type="text/css">
      
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
      .course-group-icon-dropify .dropify-wrapper {
        height: 142px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
      }
      .course-group-icon-dropify .dropify-wrapper:hover {
        border-color: #ef1b23;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
      }
      .course-group-icon-dropify .dropify-wrapper .dropify-message p {
        color: #64748b;
        font-size: 13px;
      }
      .course-group-icon-dropify .dropify-wrapper .dropify-preview .dropify-render img {
        max-width: 64px;
        max-height: 64px;
        object-fit: contain;
      }
      .course-group-icon-help { display: block; margin-top: 6px; color: #718096; font-size: 11px; }
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border precision-data-page">
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
                <div class="row col-12 page-titles precision-page-heading">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                        <small><?php echo $lang=="thai" ? "จัดการกลุ่มหลักสูตรทั้งหมดในระบบ" : "Manage all course groups in the system"; ?></small>
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

                <div class="row col-12 page-titles precision-workspace-row">
                  <div class="col-md-12 card precision-data-workspace">
                    <div class="card-body">
                      <div class="col-md-12 precision-workspace-toolbar">
                        <div class="precision-workspace-title">
                          <span><?php echo ucwords(strtolower($title)); ?></span>
                          <small><?php echo $lang=="thai" ? "ค้นหา ตรวจสอบ และจัดการข้อมูลได้จากพื้นที่เดียว" : "Search, review and manage records in one workspace"; ?></small>
                        </div>
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo ucwords(label('createcoursegroup')); ?></button>
                        <?php } ?>
                        <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                  <label for="com_id_search"><?php echo label('com_name'); ?>: </label>
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
                                <?php //if($btn_update=="1"||$btn_delete=="1"){ ?>
                                <th width="10%" align="center"><center><?php echo label('manage'); ?></center></th>
                              <?php //} ?>
                                <!-- <th width="10%" align="center"><center><?php echo label('cgcode'); ?></center></th> -->
                                <th width="10%" align="center"><center><?php echo label('cgtitle')." (".label('EN').")"; ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('cgtitle')." (".label('TH').")"; ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('cgtitle')." (".label('JP').")"; ?></center></th>
                                <th width="5%" align="center"><center><?php echo label('course_status'); ?></center></th>
                                <?php //if($is_approve=="1"){ ?>
                                <th width="5%" align="center"><center><?php echo label('sv_b_approve'); ?></center></th>
                                  <th width="10%"><center><?php echo label('d_approver'); ?></center></th>
                                  <th width="10%"><center><?php echo label('d_approvedate'); ?></center></th>
                                <?php //} ?>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p class="precision-table-legend"><?php echo label('preNote'); ?>: <button type="button" class="btn btn-secondary btn-xs active"><i class="mdi mdi-alert text-warning"></i></button> = <b><?php echo label('d_waitapprove'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
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
              <form method="post" id="course_group_form" autocomplete="off" name="course_group_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cgtitle_en"><b style="color: #FF2D00">*</b><?php echo label('cgtitle')." (".label('EN').")"; ?>:</label>
                    <input type="text" id="cgtitle_en" name="cgtitle_en" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cgtitle_th"><b style="color: #FF2D00">*</b><?php echo label('cgtitle')." (".label('TH').")"; ?>:</label>
                    <input type="text" id="cgtitle_th" name="cgtitle_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('cgdesc')." (".label('EN').")"; ?>:</label>
                    <textarea class="form-control" rows="4" id="cgdesc_en" name="cgdesc_en"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('cgdesc')." (".label('TH').")"; ?>:</label>
                    <textarea class="form-control" rows="4" id="cgdesc_th" name="cgdesc_th"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cgtitle_jp"><b style="color: #FF2D00">*</b><?php echo label('cgtitle')." (".label('JP').")"; ?>:</label>
                    <input type="text" id="cgtitle_jp" name="cgtitle_jp" class="form-control" required> 
                  </div>
                  <div class="form-group">
                    <label><?php echo label('cgdesc')." (".label('JP').")"; ?>:</label>
                    <textarea class="form-control" rows="4" id="cgdesc_jp" name="cgdesc_jp"></textarea>
                  </div>
                </div>
                <!-- <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('cgthumb'); ?>:</label>
                    <input type="file" name="cgthumb" id="cgthumb" class="dropify"  accept="image/png, image/jpeg" />
                  </div>
                </div> -->

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="cg_icon"><?php echo $lang == 'thai' ? 'ไอคอนสำหรับแท็บ (PNG)' : ($lang == 'japan' ? 'タブアイコン (PNG)' : 'Tab icon (PNG)'); ?>:</label>
                    <div class="course-group-icon-dropify">
                      <input type="file" name="cg_icon" id="cg_icon" class="dropify" accept="image/png,.png" data-allowed-file-extensions="png" data-max-file-size="2M" data-height="140" data-show-remove="true">
                    </div>
                    <small class="course-group-icon-help"><?php echo $lang == 'thai' ? 'รองรับเฉพาะ PNG ขนาดไม่เกิน 2 MB หากไม่อัปโหลด ระบบจะแสดงไอคอนมาตรฐาน' : ($lang == 'japan' ? 'PNGのみ、最大2 MB。未設定の場合は標準アイコンを表示します。' : 'PNG only, maximum 2 MB. If omitted, the default icon will be used.'); ?></small>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                      <label for="cg_approve_by"><b style="color: #FF2D00">*</b><?php echo label('cg_approve_by'); ?>:</label>
                      <select class="form-control select2" required id="cg_approve_by" name="cg_approve_by[]" multiple  style="width: 100%;">
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="cg_status"><?php echo label('status'); ?>:</label>
                    <div class="switch">
                        <label><?php echo label('close'); ?><input type="checkbox" checked  id="cg_status" name="cg_status" value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="cg_id" name="cg_id">
              <input type="hidden" id="com_id" name="com_id">
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
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <script type="text/javascript">
        $('.select2').select2();
        $("#cg_approve_by").select2({
            dropdownParent: $("#modal-default"),
            maximumSelectionLength: 5,
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                maximumSelected: function (e) {
                    var t = "<?php echo label('select_approver'); ?>";
                    return t.replace("_", e.maximum);
                }
            }
        });

        $.fn.dataTable.ext.errMode = "none";
      fetch_data_main(0);
      function fetch_data_main(page_num)
         {
            $('#myTable').DataTable().destroy();
            var com_id = $('#com_id_search').val();
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
                    url : '<?=base_url()?>index.php/fetchdata/fetch_detail_coursegroup/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "cgtitle_en" },
                    { data: "cgtitle_th" },
                    { data: "cgtitle_jp" },
                    { data: "cg_status" },
                    { data: "approve_status" },
                    { data: "cos_approveby" },
                    { data: {
                        _:    "cos_approvedate.display",
                        sort: "cos_approvedate.timestamp"
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
                  },
                  "drawCallback": function () {
                    var $actionButtons = $('#myTable tbody .btn[title]');
                    $actionButtons.attr({
                      'data-toggle': 'tooltip',
                      'data-placement': 'top',
                      'data-container': 'body'
                    });
                    if ($.fn.tooltip) {
                      $actionButtons.tooltip({trigger: 'hover focus'});
                    }
                  }
            });
         }

        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data_main(0);
          $('#com_id').val(com_id);
        });
        function clear_dropify(id){
                    var imagenUrl = "";
                    var drEvent = $('#'+id).dropify(
                    {
                      defaultFile: imagenUrl
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = imagenUrl;
                    drEvent.destroy();
                    drEvent.init();
        }
        function resetCourseGroupIcon(imageUrl) {
          var $input = $('#cg_icon');
          var dropify = $input.data('dropify');
          $input.val('');
          if (!dropify) {
            $input.attr('data-default-file', imageUrl || '');
            return;
          }
          dropify.resetPreview();
          dropify.clearElement();
          dropify.settings.defaultFile = imageUrl || '';
          dropify.destroy();
          dropify.init();
        }
           $('#add_button').click(function(){
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label('createcoursegroup'); ?>');
                $('#course_group_form')[0].reset();
                $('#operation').val("Add");
                resetCourseGroupIcon();
                // clear_dropify('cgthumb');
                var com_id = $('#com_id_search').val();
                $('#com_id').val(com_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/querydata/recheckapprovemulti",
                  method:"POST",
                  data:{cg_id:'',com_id:com_id},
                  success:function(datacg_approve_by)
                  { 
                      $('#cg_approve_by').html(datacg_approve_by); 

                  }
                });
                //clear_dropify('com_logo');
            });

    $(document).ready(function() {
        $('.dropify').dropify();
        //$('#myTable').DataTable();
        $(document).on('submit', '#course_group_form', function(event){
              event.preventDefault(); 
              $('#action').prop('disabled', true);
                              $("#myModal_process").modal('show');
                              $( document.body ).css( 'pointer-events', 'none' );
              var operation = $('#operation').val();
              var rechk_val = 1;
              var cg_statusval = document.getElementById('cg_status');
              if(cg_statusval.checked){
              var cg_status = 1;
              }else{
              var cg_status = 0;
              }
              var form_input = new FormData(this);
              if(operation=="Edit"){
                    var cg_id = $('#cg_id').val();
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/rechk_course_incg",
                      method:"POST",
                      data:{cg_id:cg_id},
                      dataType:"json",
                      success:function(data)
                      { 
                          if(data.status=="1"&&cg_status==0&&cg_status!=data.cg_status){
                              swal({
                                  title: '<?php echo label('thiscg_isclose'); ?>',
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: true,
                                  confirmButtonColor: "#1abc9c",  
                                  cancelButtonColor: "#DD6B55",   
                                  confirmButtonText: '<?php echo label('ok'); ?>',
                                  cancelButtonText: '<?php echo label('cancel'); ?>'
                              }).then(function (isChk) {
                                if(isChk.value){
                                    $("#myModal_process").modal('show');
                                    $( document.body ).css( 'pointer-events', 'none' );
                                    $.ajax({
                                      url:"<?=base_url()?>index.php/insertdata/insert_coursegroup",
                                      method:'POST',
                                      data:form_input,
                                      contentType:false,
                                      processData:false,
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

                                        $('#action').prop('disabled', false);
                                        if(data.status=="2"){
                                            $('#course_group_form')[0].reset();
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
                                        }else if(data.status=="1"){
                                            swal({
                                                title: '<?php echo label("cg_msg_duplicate"); ?>',
                                                text: "",
                                                type: 'warning',
                                                showCancelButton: false,
                                                confirmButtonClass: 'btn btn-primary',
                                                confirmButtonText: '<?php echo label("m_ok"); ?>'
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
                                }
                              });
                          }else{
                              $("#myModal_process").modal('show');
                              $( document.body ).css( 'pointer-events', 'none' );
                              $.ajax({
                                url:"<?=base_url()?>index.php/insertdata/insert_coursegroup",
                                method:'POST',
                                data:form_input,
                                contentType:false,
                                processData:false,
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
                                        $('#action').prop('disabled', false);
                                  if(data.status=="2"){
                                      $('#course_group_form')[0].reset();
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
                                  }else if(data.status=="1"){
                                      swal({
                                          title: '<?php echo label("cg_msg_duplicate"); ?>',
                                          text: "",
                                          type: 'warning',
                                          showCancelButton: false,
                                          confirmButtonClass: 'btn btn-primary',
                                          confirmButtonText: '<?php echo label("m_ok"); ?>'
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
                          }
                      }
                    });
              }else{
                  $("#myModal_process").modal('show');
                  $( document.body ).css( 'pointer-events', 'none' );
                  $.ajax({
                    url:"<?=base_url()?>index.php/insertdata/insert_coursegroup",
                    method:'POST',
                    data:form_input,
                    contentType:false,
                    processData:false,
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
                                        $('#action').prop('disabled', false);
                      if(data.status=="2"){
                          $('#course_group_form')[0].reset();
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
                      }else if(data.status=="1"){
                          swal({
                              title: '<?php echo label("cg_msg_duplicate"); ?>',
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
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
              }

            });

         $(document).on('click', '.delete', function(){
            var cg_id = $(this).attr("id");

            $.ajax({
              url:"<?=base_url()?>index.php/querydata/rechk_course_incg",
              method:"POST",
              data:{cg_id:cg_id},
              dataType:"json",
              success:function(data)
              { 
                  if(data.status=="1"){
                      swal({
                                  title: '<?php echo label('thiscg_isdelete'); ?>',
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: true,
                                  confirmButtonColor: "#1abc9c",  
                                  cancelButtonColor: "#DD6B55",   
                                  confirmButtonText: '<?php echo label('ok'); ?>',
                                  cancelButtonText: '<?php echo label('cancel'); ?>'
                      }).then(function (isChk) {
                          if(isChk.value){
                                swal({
                                    title: '<?php echo label('wg_delete_msg'); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",   
                                    confirmButtonText: '<?php echo label('wg_delete'); ?>',
                                    cancelButtonText: '<?php echo label('cancel'); ?>'
                                }).then(function (isChk) {
                                  if(isChk.value){
                                    $.ajax({
                                        url:"<?=base_url()?>index.php/manage/delete_cosgroup_data",
                                        method:"POST",
                                        data:{id_delete:cg_id},
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
                          }
                      });
                  }else{
                      swal({
                          title: '<?php echo label('wg_delete_msg'); ?>',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",   
                          confirmButtonText: '<?php echo label('wg_delete'); ?>',
                          cancelButtonText: '<?php echo label('cancel'); ?>'
                      }).then(function (isChk) {
                        if(isChk.value){
                          $.ajax({
                              url:"<?=base_url()?>index.php/manage/delete_cosgroup_data",
                              method:"POST",
                              data:{id_delete:cg_id},
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
                  }
              }
            });
          });
          function createButton(text,classs,style,id, cb) {
            return $(' <button class="'+classs+'" style="'+style+'" id="'+id+'">' + text + '</button>').on('click', cb);
          }
          
          $(document).on('click', '.btnrefresh', function(e) {
              e.preventDefault();
              $('.swal2-container').hide();
          });

         $(document).on('click', '.approve', function(e){
            var cg_id = $(this).attr("id");

            var buttons = $('<div>')
            .append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>','btn btn-flat btnapprove_cog','background-color:#1abc9c;',cg_id, function() {
            })).append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject_cog','background-color:#DD6B55;',cg_id, function() {
               swal.close();
            })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
               swal.close();
            }));
            e.preventDefault();
            swal({
              title: "<?php echo label('approve_is'); ?>",
              html: buttons,
              type: "warning",
              showConfirmButton: false,
              showCancelButton: false
            });
          });

          $(document).on('click', '.btnapprove_cog', function(e) {
              e.preventDefault();
              var cg_id = $(this).attr("id");
                $("#myModal_process").modal({backdrop: false});
                $(document.body).css('pointer-events', 'none');
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_cosgroup_data",
                    method:"POST",
                    data:{cg_id:cg_id},
                    success:function(data)
                    {
                      $(document.body).css('pointer-events', '');
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                            location.reload();
                             /*         var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
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
                             /*         var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
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
                             /*         var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
                        })
                      }
                    }
                });
          });

          $(document).on('click', '.btnreject_cog', function(e) {
              e.preventDefault();
              var cg_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preNote'); ?>: ",
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
                      url:"<?=base_url()?>index.php/querydata/reject_cog",
                      method:"POST",
                      data:{cg_id:cg_id,coga_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {

                        if(data.status == "2"){
                          location.reload();
                        }
                      }
                    });
                  }
               /* if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                  return false
                }else{
                swal("Nice!", "You wrote: " + inputValue, "success");
                }*/
              });
          });

        /* $(document).on('click', '.approve', function(){
            var cg_id = $(this).attr("id");
            swal({
                title: '<?php echo label('approve_is'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#16a085",   
                confirmButtonText: '<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
                cancelButtonText: '<i class="mdi mdi-window-close"></i> <?php echo label('cancel'); ?>',
                footer: '<button type="button" class="btn btn-info btn-block btnreject" style="background-color:#DD6B55;" id="'+cg_id+'"><i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?></button>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_cosgroup_data",
                    method:"POST",
                    data:{cg_id:cg_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
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

          $(document).on('click', '.btnreject', function(e) {
              e.preventDefault();
              var cg_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preNote'); ?> :"
              }).then(function (isChk) {
                  if(isChk.value){
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/reject_cog",
                      method:"POST",
                      data:{cg_id:cg_id,coga_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {
                          
                      }
                    });
                  }
               /* if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                  return false
                }else{
                swal("Nice!", "You wrote: " + inputValue, "success");
                }
              });
          });*/


          $(document).on('click', '.update', function(){
            var cg_id = $(this).attr("id");

            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_coursegroup_data",
              method:"POST",
              data:{cg_id_update:cg_id},
              dataType:"json",
              success:function(data)
              {                
                  $("#modal-default").modal({backdrop: false});
                  $('.modal-title').text('<?php echo ucwords(label("editcoursegroup")); ?>');
                  $('#course_group_form')[0].reset();
                  $('#operation').val("Edit");
                  resetCourseGroupIcon(data.cg_icon ? "<?php echo REAL_PATH;?>/uploads/course_group/icons/" + data.cg_icon : '');

                  /*if(data.cgthumb!=""){
                    var imagenUrl = "<?php echo REAL_PATH;?>/uploads/course_group/"+data.cgthumb;
                    var drEvent = $('#cgthumb').dropify(
                    {
                      defaultFile: imagenUrl
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = imagenUrl;
                    drEvent.destroy();
                    drEvent.init();
                  }else{
                    $('#cgthumb').dropify(); 
                  }  */

                if(data.cg_status=="1"){
                  document.getElementById('cg_status').checked = true;
                }else{
                  document.getElementById('cg_status').checked = false;
                }
                $('#com_id').val(data.com_id);
                $('#cgtitle_th').val(data.cgtitle_th);
                $('#cgtitle_en').val(data.cgtitle_en);  
                $('#cgtitle_jp').val(data.cgtitle_jp);  
                $('#cgdesc_th').val(data.cgdesc_th);
                $('#cgdesc_en').val(data.cgdesc_en); 
                $('#cgdesc_jp').val(data.cgdesc_jp); 
                $('#cg_id').val(data.cg_id);     
                $.ajax({
                  url:"<?=base_url()?>index.php/querydata/recheckapprovemulti",
                  method:"POST",
                  data:{cg_id:data.cg_id,com_id:data.com_id},
                  success:function(datacg_approve_by)
                  { 
                      $('#cg_approve_by').html(datacg_approve_by);
                  }
                });
                

              }
            });
            
          });

    });
    </script>
</body>

</html>
