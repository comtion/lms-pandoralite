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
      .qr-toolbar { display:flex; gap:12px; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; margin:18px 0; }
      .qr-filters { display:flex; gap:10px; flex-wrap:wrap; }
      .qr-filters .form-group { margin:0; min-width:180px; }
      .qr-summary { display:grid; grid-template-columns:repeat(3,minmax(125px,1fr)); gap:12px; margin:18px 0; }
      .qr-summary-card { border:1px solid #e5eaf0; border-radius:12px; padding:14px 16px; background:#fff; }
      .qr-summary-card strong { display:block; font-size:24px; line-height:1.1; color:#24344d; }
      .qr-summary-card span { color:#718096; font-size:13px; }
      .qr-actions { display:flex; justify-content:center; gap:5px; min-width:150px; }
      .qr-action { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; }
      .qr-link { display:flex; align-items:center; gap:4px; max-width:360px; }
      .qr-link a { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
      .qr-name { font-weight:600; color:#334155; }
      .qr-detail { display:block; max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
      .qr-status { padding:7px 10px; border-radius:20px; font-weight:500; }
      .qr-preview-image { width:260px; max-width:100%; border:1px solid #e5eaf0; border-radius:14px; padding:12px; background:#fff; }
      @media (max-width:767px) { .qr-summary { grid-template-columns:1fr; } .qr-toolbar,.qr-filters { display:block; } .qr-filters .form-group { margin-bottom:10px; } }
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
                        <b>QR Code</b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active">QR Code</li>
                        </ol>
                    </div>
                </div>  

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('qr_create'); ?></button>
                        <?php } ?>
                        <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                  <label for="com_id"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                  <select class="form-control select2" id="com_id_search" name="com_id_search"  style="width: 100%;">
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
                      <div class="qr-summary" aria-label="QR Code summary">
                        <div class="qr-summary-card"><strong id="qr_total">0</strong><span><?php echo $lang=='thai'?'QR Code ทั้งหมด':'Total QR Codes'; ?></span></div>
                        <div class="qr-summary-card"><strong id="qr_active">0</strong><span><?php echo $lang=='thai'?'กำลังใช้งาน':'Active'; ?></span></div>
                        <div class="qr-summary-card"><strong id="qr_inactive">0</strong><span><?php echo $lang=='thai'?'ปิดใช้งาน':'Inactive'; ?></span></div>
                      </div>
                      <div class="qr-toolbar">
                        <div class="qr-filters">
                          <div class="form-group"><label for="filter_type"><?php echo $lang=='thai'?'ประเภทไฟล์':'File type'; ?></label><select id="filter_type" class="form-control"><option value=""><?php echo $lang=='thai'?'ทุกประเภท':'All types'; ?></option><option value="1"><?php echo label('qr_typefile_a'); ?></option><option value="2"><?php echo label('qr_typefile_b'); ?></option><option value="3"><?php echo label('qr_typefile_c'); ?></option><option value="4"><?php echo label('qr_typefile_d'); ?></option></select></div>
                          <div class="form-group"><label for="filter_status"><?php echo label('status'); ?></label><select id="filter_status" class="form-control"><option value=""><?php echo $lang=='thai'?'ทุกสถานะ':'All statuses'; ?></option><option value="1"><?php echo label('open'); ?></option><option value="0"><?php echo label('close'); ?></option></select></div>
                        </div>
                        <button type="button" id="reset_filters" class="btn btn-outline-secondary"><i class="mdi mdi-filter-remove"></i> <?php echo $lang=='thai'?'ล้างตัวกรอง':'Clear filters'; ?></button>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="20%"><center><?php echo label('m_company'); ?></center></th>
                                <th width="15%"><center><?php echo label('qr_typefile'); ?></center></th>
                                <th width="20%"><center><?php echo label('qr_name'); ?></center></th>
                                <th width="20%"><center><?php echo label('qr_path'); ?></center></th>
                                <th width="10%"><center><?php echo label('status'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-download"></i></button> = <b><?php echo label('qr_download'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
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
                  <button type="button" class="close btn_close"  data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="qrcode_form" autocomplete="off" name="qrcode_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="com_id"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                      <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
                      <select class="form-control select2" required id="com_id" name="com_id"  style="width: 100%;">
                      </select>
                      <?php }else{ ?>
                          <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $lang=="thai"?$com_data['com_name_th']:$com_data['com_name_eng']; ?>" readonly>
                          <input type="hidden" id="com_id" name="com_id" value="<?php echo $user['com_id']; ?>">
                      <?php } ?>
                    </div>
                </div>
                <!-- <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>4<?php }else{?>6<?php } ?> -->
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="qr_name"><b style="color: #FF2D00">*</b><?php echo label('qr_name'); ?>:</label>
                    <input type="text" id="qr_name" name="qr_name" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="qr_type"><b style="color: #FF2D00">*</b><?php echo label('qr_typefile'); ?>:</label>
                    <select id="qr_type" required name="qr_type" onchange="onchk_typeupload(this.value)" class="form-control">
                      <option value="1" selected><?php echo label('qr_typefile_a'); ?></option>
                      <option value="2"><?php echo label('qr_typefile_b'); ?></option>
                      <option value="3"><?php echo label('qr_typefile_c'); ?></option>
                      <option value="4"><?php echo label('qr_typefile_d'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="qr_detail"><?php echo label('qr_detail'); ?>:</label>
                    <textarea id="qr_detail" name="qr_detail" rows="8" class="form-control"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="qr_path"><b style="color: #FF2D00">*</b><?php echo label('qr_upload_file'); ?>:</label>
                    <input type="file" name="qr_path" id="qr_path" required class="dropify"   />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="qr_status"><b style="color: #FF2D00">*</b><?php echo label('status'); ?>:</label>
                    <div class="switch">
                        <label><?php echo label('close'); ?><input type="checkbox"  id="qr_status" name="qr_status" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                    </div>
                  </div>
                </div>
                                <div class="col-md-12 progress" id="progress_qrcode_div" style="display: none;">
                                    <div class="progress-bar-qrcode bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_qrcode"></span></div>
                                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="qr_id" name="qr_id">
              <div class="modal-footer">
                  <button type="submit" class="col-md-6 col-lg-1 btn btn-outline-success btn-flat pull-left btn_close" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="col-md-6 col-lg-1 btn btn-outline-danger btn-flat btn_close" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="qr-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="qr-preview-title">QR Code</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body text-center"><img id="qr-preview-image" class="qr-preview-image" alt="QR Code preview"><p id="qr-preview-url" class="small text-muted mt-3 text-break"></p></div>
        <div class="modal-footer"><button type="button" id="qr-preview-copy" class="btn btn-outline-primary"><i class="mdi mdi-content-copy"></i> <?php echo $lang=='thai'?'คัดลอกลิงก์':'Copy link'; ?></button><a id="qr-preview-open" class="btn btn-primary" target="_blank" rel="noopener"><i class="mdi mdi-open-in-new"></i> <?php echo $lang=='thai'?'เปิดเนื้อหา':'Open'; ?></a></div>
      </div></div>
    </div>

      
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
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <!--stickey kit -->
    <script type="text/javascript">
      $.fn.dataTable.ext.errMode = "none";
      $('.select2').select2();
      $('.slimtest1').perfectScrollbar();
      fetch_data_qrcode(0);

        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data_qrcode(0);
        });

      function fetch_data_qrcode(page_num)
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
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/manage/fetch_detail_qrcode/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
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
                      updateQrSummary(table);
                    }, 10 );
                  },
                  "drawCallback": function(){ updateQrSummary(this.api()); }
            });
         }

        function updateQrSummary(table){
          var total=0, active=0, inactive=0;
          table.rows({search:'none'}).every(function(){ var node=this.node(); if(!node){return;} total++; var status=$(node).find('.qr-status').data('status'); String(status)==='1'?active++:inactive++; });
          $('#qr_total').text(total); $('#qr_active').text(active); $('#qr_inactive').text(inactive);
        }
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
          if(settings.nTable.id!=='myTable'){return true;}
          var row=settings.aoData[dataIndex].nTr, type=String($(row).find('.qr-type').data('type')||''), status=String($(row).find('.qr-status').data('status'));
          return (!$('#filter_type').val()||$('#filter_type').val()===type) && ($('#filter_status').val()===''||$('#filter_status').val()===status);
        });
        $('#filter_type,#filter_status').on('change',function(){ $('#myTable').DataTable().draw(); });
        $('#reset_filters').on('click',function(){ $('#filter_type,#filter_status').val(''); $('#myTable').DataTable().search('').draw(); });
        function copyQrUrl(url){
          if(navigator.clipboard && window.isSecureContext){ return navigator.clipboard.writeText(url); }
          var input=$('<textarea>').val(url).appendTo('body').select(); document.execCommand('copy'); input.remove(); return Promise.resolve();
        }
        $(document).on('click','.copy-qr-link',function(){ var button=$(this),url=button.data('url'); copyQrUrl(url).then(function(){ button.addClass('text-success'); setTimeout(function(){button.removeClass('text-success');},1200); }); });
        $(document).on('click','.preview-qr',function(){ var item=$(this); $('#qr-preview-title').text(item.data('name')); $('#qr-preview-image').attr('src',item.data('image')); $('#qr-preview-url').text(item.data('url')); $('#qr-preview-open').attr('href',item.data('url')); $('#qr-preview-copy').data('url',item.data('url')); $('#qr-preview-modal').modal('show'); });
        $('#qr-preview-copy').on('click',function(){ copyQrUrl($(this).data('url')); });

        function onchk_typeupload(value){
                if(value=="1"){
                    $('#qr_path').attr("accept", "image/png, image/jpeg, image/gif");
                }else if(value=="2"){
                    $('#qr_path').attr("accept", "video/mp4");
                }else if(value=="3"){
                    $('#qr_path').attr("accept", ".pdf,application/pdf");
                }else{
                    $('#qr_path').attr("accept", ".doc,.docx,.xls,.xlsx,.ppt,.pptx");
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
                          
           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("qr_create"); ?>');
                $('#qrcode_form')[0].reset();
                $('#operation').val("Add");
                clear_dropify('#qr_path');
                $('#qr_path').attr("accept", "image/png, image/jpeg, image/gif");

                $("#modal-default").modal({backdrop: false});

                  <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:''},
                        success: function(data_company){
                            $('#com_id').html(data_company);
                            $('#com_id').val($('#com_id option:first-child').val()).trigger('change');
                        }
                  });
                  <?php } ?>
            });

    $("input[type='file']").on("change", function () {
      console.log(this.files[0].size);
     if(this.files[0].size > 5000000) {
       $(this).val('');
                          swal({
                              title: '<?php echo label("upload_max"); ?>',
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          })
     }
    });
    $(document).ready(function() {
          $(document).on('submit', '#qrcode_form', function(event){
              event.preventDefault(); 
              var fileExtension = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'mp4', 'pdf', 'png', 'jpg', 'jpeg', 'gif'];
              var operation = $('#operation').val();
              
              if (operation=="Add"&&$.inArray($('#qr_path').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                          swal({
                              title: '<?php echo label("media_type_dontmatch"); ?>',
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          })
              }else{
                  $("#myModal_process").modal('show');
                  $( document.body ).css( 'pointer-events', 'none' );
                $('.btn_close').hide();
                /*$("#myModal_process").addClass("in");
                $("#myModal_process").css("display","block");
                $("#myModal_process").modal({backdrop: false});*/
                  $.ajax({
                    url:"<?=base_url()?>index.php/manage/insert_qrcode",
                    method:'POST',
                    data:new FormData(this),
                    contentType:false,
                    processData:false,
                        xhr: function() {
                          /*document.getElementById("progress_qrcode_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                      $('#txt_progress_qrcode').text(percentComplete.toFixed(2) + '%');

                                       $('.progress-bar-qrcode').animate({
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
                      /*$( document.body ).css( 'pointer-events', '' );
                          document.getElementById("progress_qrcode_div").style.display = "none";*/
                          $( document.body ).css( 'pointer-events', '' );
                        $('#myModal_process').modal('hide');
                        
                        $("#myModal_process").removeClass("in");
                        $("#myModal_process").css("display","none");
                      /*$("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");*/
                      $('.btn_close').show();
                      topFunction();
                      if(data=="2"){
                          swal(
                              '<?php echo label("com_msg_success"); ?>!',
                              '',
                              'success'
                          ).then(function () {
                            $('#qrcode_form')[0].reset();
                            $('#modal-default').modal('hide');
                            var table = $('#myTable').DataTable();
                            var info = table.page.info();
                            var length = info.pages;
                            var page_current = info.page;
                            fetch_data_qrcode(page_current);
                          })
                      }else if(data==="4"){
                          swal({title:'<?php echo $lang=="thai"?"ไฟล์ไม่ถูกต้อง หรือมีขนาดเกิน 5 MB":"Invalid file or file exceeds 5 MB"; ?>',text:"",type:'warning',confirmButtonText:'<?php echo label("m_ok"); ?>'});
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
                     
                    },
                      error: function (jqXHR, exception) {
                      
                          $( document.body ).css( 'pointer-events', '' );
                        $('#myModal_process').modal('hide');
                        
                        $("#myModal_process").removeClass("in");
                        $("#myModal_process").css("display","none");
                      $('.btn_close').show();
                          topFunction();
                          var msg = '';
                          if (jqXHR.status === 0) {
                              msg = 'Not connect.\n Verify Network.';
                          } else if (jqXHR.status == 404) {
                              msg = 'Requested page not found. [404]';
                          } else if (jqXHR.status == 500) {
                              msg = 'Internal Server Error [500].';
                          } else if (exception === 'parsererror') {
                              msg = 'Requested JSON parse failed.';
                          } else if (exception === 'timeout') {
                              msg = 'Time out error.';
                          } else if (exception === 'abort') {
                              msg = 'Ajax request aborted.';
                          } else {
                              msg = 'Uncaught Error.\n' + jqXHR.responseText;
                          }
                          swal({
                              title: msg,
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          })
                      },
                  });
              }
            });

         $(document).on('click', '.delete', function(){
            var qr_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/manage/delete_qrcode_data",
                    method:"POST",
                    data:{qr_id_delete:qr_id},
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
                          fetch_data_qrcode(page_current);
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

          $(document).on('click', '.update', function(){
            var qr_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_qrcode_data",
              method:"POST",
              data:{qr_id_update:qr_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("qr_edit"); ?>');
                $('#qrcode_form')[0].reset();
                $('#operation').val("Edit");
                $('#qr_name').val(data.qr_name);
                $('#qr_type').val(data.qr_type);
                $('#qr_detail').val(data.qr_detail);
                $('#qr_id').val(data.qr_id);    


                  <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
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
                    onchk_typeupload(data.qr_type);

                      if(data.qr_path!=""){
                        document.getElementById("qr_path").required = false;
                        var imagenUrl = "<?php echo REAL_PATH;?>/uploads/file_forqrcode/"+data.qr_path;
                        var drEvent = $('#qr_path').dropify(
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
                        document.getElementById("qr_path").required = false;
                        $('.dropify').dropify(); 
                      }  
                if(data.qr_status=="1"){
                  document.getElementById('qr_status').checked = true;
                }else{
                  document.getElementById('qr_status').checked = false;
                }
              }
            });
            
          });
    });
    </script>
</body>

</html>
