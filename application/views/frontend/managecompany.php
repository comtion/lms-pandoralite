<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
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
                        <?php if($btn_add=="1"&&$user['ug_viewdata']=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('addcompany'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px !important;" align="center"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="20%" align="center"><center><?php echo label('acronym_nickname'); ?></center></th>
                                <th width="40%" align="center"><center><?php echo label('com_name'); ?></center></th>
                                <th width="10%" align="center"><center><?php echo label('com_admin'); ?></center></th>
                                <th width="15%" align="center"><center><?php echo label('m_updatedate'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <!-- <button type="button" class="btn btn-success btn-xs"><i class="mdi mdi-file-import"></i></button> = <b><?php echo label('import_user'); ?></b> ,  --><button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-image-area"></i></button> = <b><?php echo label('banner'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"&&!in_array($user['ug_id'], array('2','6'))){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <div class="modal fade" id="modal-import_user" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4>Import User</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="import_user_form" autocomplete="off" name="import_user_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
              		<div class="col-md-12">
	                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ug_name'); ?>:</label>
	                    <select class="form-control" required id="ug_id" name="ug_id"  style="width: 100%;">
	                    </select>
              		</div>
              		<div class="col-md-12">
	                    <label for="file_import"><b style="color: #FF2D00">*</b><?php echo 'Excel File'; ?>:</label>
                      	<input type="file" name="file_import" required id="file_import" class="dropify"  accept=".xls,.xlsx" />
                    	<?php echo label('certificate_example')." : "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/format_import_user.xlsx" download>format_import_user.xlsx</a>
              		</div>
              </div>
              <input type="hidden" id="operation_import_user" name="operation_import_user" value="Add">
              <input type="hidden" id="com_id_import_user" name="com_id_import_user">
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

    <div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="company_form" autocomplete="off" name="company_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="com_code"><b style="color: #FF2D00">*</b><?php echo label('acronym_nickname'); ?>:</label>
                    <input type="text" id="com_code" name="com_code" maxlength="6" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="com_name_eng"><b style="color: #FF2D00">*</b><?php echo label('com_name')." EN"; ?>:</label>
                    <input type="text" id="com_name_eng" name="com_name_eng" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="com_name_th"><b style="color: #FF2D00">*</b><?php echo label('com_name')." TH"; ?>:</label>
                    <input type="text" id="com_name_th" name="com_name_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_admin'); ?>:</label>
                    <select class="form-control" id="com_admin" name="com_admin"  style="width: 100%;">
                      <option selected value="com_central"><?php echo label('com_central'); ?></option>
                      <option value="com_associated"><?php echo label('com_associated'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="com_emaildomain"><b style="color: #FF2D00">*</b><?php echo label('com_emaildomain'); ?>: </label>
                    <input type="text" id="com_emaildomain" name="com_emaildomain" onkeyup="return forceLower(this);" onchange="checkDomain(this.value)" placeholder="(sample: imat.isuzu.co.th)" class="form-control" required> 
                    <span class="text-danger"><?php echo label("com_emaildomain_noti")." (sample: imat.isuzu.co.th,isuzu.com)"; ?></span>
                    <!-- pattern="[a-z0-9.-]+\.[a-z]{2,4}$" -->
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="com_mail"><?php echo label('com_mail'); ?>:</label>
                    <input type="text" id="com_mail" name="com_mail" class="form-control" > 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="com_tel"><?php echo label('com_tel'); ?>:</label>
                    <input type="text" id="com_tel" name="com_tel" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="com_fax"><?php echo label('com_fax'); ?>:</label>
                    <input type="text" id="com_fax" name="com_fax" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('com_add')." EN"; ?>:</label>
                    <textarea class="form-control" rows="8" id="com_add_eng" name="com_add_eng"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('com_add')." TH"; ?>:</label>
                    <textarea class="form-control" rows="8" id="com_add_th" name="com_add_th"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                    <hr>
                    <h5>PDPA Content</h5>
                </div>
               <div class="form-group col-md-6">
                     <label class="control-label text-right"><?php echo label('sv_b_title'); ?> <?php echo label('acro_en'); ?>:</label>
                     <input name="com_wctitle_eng" id="com_wctitle_eng" class="form-control" type="text">

                     <label class="control-label text-right"><?php echo label('message'); ?> <?php echo label('acro_en'); ?>:</label>
                     <textarea name="com_wcmessage_eng" id="com_wcmessage_eng" class="form-control texteditor" style="width: 100%" rows="4"></textarea>
               </div>
                <div class="form-group col-md-6">
                     <label class="control-label text-right"><?php echo label('sv_b_title'); ?> <?php echo label('acro_th'); ?>:</label>
                     <input name="com_wctitle_th" id="com_wctitle_th" class="form-control" type="text">

                     <label class="control-label text-right"><?php echo label('message'); ?> <?php echo label('acro_th'); ?>:</label>
                     <textarea name="com_wcmessage_th" id="com_wcmessage_th" class="form-control texteditor" style="width: 100%" rows="4"></textarea>
               </div>
               <div class="form-group col-md-6">
                     <label class="control-label text-right"><?php echo label('sv_b_title'); ?> <?php echo label('acro_jp'); ?>:</label>
                     <input name="com_wctitle_jp" id="com_wctitle_jp" class="form-control" type="text">

                     <label class="control-label text-right"><?php echo label('message'); ?> <?php echo label('acro_jp'); ?>:</label>
                     <textarea name="com_wcmessage_jp" id="com_wcmessage_jp" class="form-control texteditor" style="width: 100%" rows="4"></textarea>
               </div>
               <div class="form-group col-md-6">
               </div>
               <div class="col-md-12">
                     <hr>
               </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('com_logo_top'); ?>:</label>
                    <input type="file" name="com_logo_top" id="com_logo_top" class="dropify_top"  accept="image/png, image/jpeg" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo label('com_logo_footer'); ?>:</label>
                    <input type="file" name="com_logo_footer" id="com_logo_footer" class="dropify_footer"  accept="image/png, image/jpeg" />
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="com_id" name="com_id">
              <input type="hidden" id="com_logo_top_ori" name="com_logo_top_ori">
              <input type="hidden" id="com_logo_footer_ori" name="com_logo_footer_ori">
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

    <div class="modal fade bs-example-modal-lg" id="modal-banner" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 id="myLargeModalLabel"><?php echo label('banner'); ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body row">
                <div class="form-group col-md-12 row">
                  <div class="form-group col-md-6">
                    <form method="post" id="banner_form" autocomplete="off" name="banner_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
                      <label class="control-label text-right"><?php echo label('banner_file'); ?></label>
                      <input type="file" name="banner" id="banner" class="dropifymain" accept="image/png, image/jpeg, image/gif" />
                      <br><label class="control-label pull-right"><button type="submit" name="add_banner" id="add_banner" class="btn btn-info btn-sm add_banner" title="upload"><i class="mdi mdi-upload"></i>  <?php echo label('import_btn'); ?></button></label>
                      <input type="hidden" id="com_id_banner" name="com_id_banner">
                    </form>
                  </div>
                  <div class="form-group col-md-6">
                      <div class="table-responsive">
                        <table id="myTable_banner" width="100%" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <?php if($btn_delete=="1"){ ?>
                              <th width="5%" align="center"><?php echo label('sv_b_manage'); ?></th>
                              <?php } ?>
                              <th width="10%"></th>
                              <th width="45%" align="center"><?php echo label('image_banner'); ?></th>
                              <th width="20%" align="center"><?php echo label('file_name'); ?></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                  </div>
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
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript">
      $.fn.dataTable.ext.errMode = "none";
      $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
      });
        if ($(".texteditor").length > 0) {
            tinymce.init({
                selector: "textarea.texteditor",
                theme: "modern",
                height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                        "save table contextmenu directionality paste textcolor"
                    ],
                    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor ",

                    images_upload_url : '<?=base_url()?>index.php/setting/upload_img_texteditor',
                    automatic_uploads : false,

                    images_upload_handler : function(blobInfo, success, failure) {
                      var xhr, formData;

                      xhr = new XMLHttpRequest();
                      xhr.withCredentials = false;
                      xhr.open('POST', '<?=base_url()?>index.php/setting/upload_img_texteditor');

                      xhr.onload = function() {
                        var json;

                        if (xhr.status != 200) {
                          if(xhr.status==400){
                            failure('Please use English filename');
                          }else{
                            failure('HTTP Error: ' + xhr.status);
                          }
                          return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.file_path != 'string') {
                          failure('Invalid JSON: ' + xhr.responseText);
                          return;
                        }

                        success(json.file_path);
                      };

                      formData = new FormData();
                      formData.append('file', blobInfo.blob(), blobInfo.filename());

                      xhr.send(formData);
                    },

            });
        }
      function forceLower(strInput) 
      {
        strInput.value=strInput.value.toLowerCase();
      }

      function recheckErrorOfValue(value) {
          const arrFormatError = ["https", "http", "www"];
          let numError = 0;

          for (var iLoop = 0; iLoop < arrFormatError.length; iLoop++) {
            if (value.search(arrFormatError[iLoop]) >= 0) {
              numError++;
            }
          }
          return numError;
      }

      function checkDomain(emailDomainValue) {
          let arrForEmailDomain = [];
          emailDomainValue.replace(" ", "");
          let emailDomainArr = emailDomainValue.split(",");
          if (emailDomainArr.length > 0) {
            for (var iLoop = 0; iLoop < emailDomainArr.length; iLoop++) {
                if (/^[a-z0-9.-]+\.[a-z]{2,4}$/.test(emailDomainArr[iLoop]) && 
                    recheckErrorOfValue(emailDomainArr[iLoop]) == 0) {
                  arrForEmailDomain.push(emailDomainArr[iLoop]);
                }
            }
          }
          
          $("#com_emaildomain").val(arrForEmailDomain.join());
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
                    url : '<?=base_url()?>index.php/manage/fetch_detail_company/',
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "num" },
                    { data: "nickname" },
                    { data: "com_name" },
                    { data: "com_admin" },
                    { data: {
                        _:    "m_updatedate.display",
                        sort: "m_updatedate.timestamp"
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
        function clear_dropify(id){
                    $('.'+id).dropify(); 
        }
           /*$('#add_button').click(function(){
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("addcompany"); ?>');
                $('#operation').val("Add");
                $('#company_form')[0].reset();

                clear_dropify('com_logo_top');
                clear_dropify('com_logo_footer');
                //clear_dropify('com_logo');
            });*/
         function fetch_data(com_id)
         {
            $('#myTable_banner').DataTable().destroy();
            $('#myTable_banner').on('error.dt', function(e, settings, techNote, message) {
                notificationForDatatableError("myTable_banner", message);
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
                    url : '<?=base_url()?>index.php/setting/fetch_banner/'+com_id,
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
                },
            });
         }
         $(document).on('click', '.import_user', function(){
            var com_id = $(this).attr("id");
            $('#com_id_import_user').val(com_id);

            clear_dropify('file_import');
            $("#modal-import_user").modal({backdrop: false});

            $.ajax({
              url: '<?=base_url()?>index.php/manage/recheckusergroup',
              type: 'POST',
              data:{com_id:com_id},
              success: function(data){
                $('#ug_id').html(data);
              }
            }); 
          });

        $(document).on('submit', '#import_user_form', function(event){
              event.preventDefault(); 
              var com_id = $('#com_id_import_user').val();
              var file_import = $('#file_import').val();
              if(file_import!=""){
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/import_user",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#import_user_form')[0].reset();
                        swal(
                            '<?php echo label("import_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
        				            $.ajax({
        				              url: '<?=base_url()?>index.php/manage/recheckusergroup',
        				              type: 'POST',
        				              data:{com_id:com_id},
        				              success: function(data){
        				                $('#ug_id').html(data);
        				              }
        				            }); 
                    				$('#com_id_import_user').val(com_id);

                            var imagenUrl = "";
                            var drEvent = $('#file_import').dropify(
                            {
                              defaultFile: imagenUrl
                            });
                            drEvent = drEvent.data('dropify');
                            drEvent.resetPreview();
                            drEvent.clearElement();
                            drEvent.settings.defaultFile = imagenUrl;
                            drEvent.destroy();
                            drEvent.init();
                        })
                    }else if(data=="1"){
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

         $(document).on('click', '.bannerbtn', function(){
            var com_id = $(this).attr("id");
            $('#com_id_banner').val(com_id);
            fetch_data(com_id);
            $('.dropifymain').dropify();
            $("#modal-banner").modal({backdrop: false});
            clear_dropify('banner');
          });
         $(document).on('click', '.delete_banner', function(){
            var id = $(this).attr("id");
            var com_id = $('#com_id_banner').val();
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
                    url:"<?=base_url()?>index.php/setting/delete_banner",
                    method:"POST",
                    data:{id_delete:id,table_name:"lms_ban"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          fetch_data(com_id);
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
         

        $(document).on('submit', '#banner_form', function(event){
              event.preventDefault(); 
              var com_id = $('#com_id_banner').val();
              var banner = $('#banner').val();
              if(banner!=""){
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_banner",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#banner_form')[0].reset();
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data(com_id);
                            var imagenUrl = "";
                            var drEvent = $('#banner').dropify(
                            {
                              defaultFile: imagenUrl
                            });
                            drEvent = drEvent.data('dropify');
                            drEvent.resetPreview();
                            drEvent.clearElement();
                            drEvent.settings.defaultFile = imagenUrl;
                            drEvent.destroy();
                            drEvent.init();
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("managebanner_msgerror"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            document.getElementById("banner").focus();
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
                            title: '<?php echo label("managebanner_msgerror"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            document.getElementById("banner").focus();
                        })
              }
                
            });

    $(document).ready(function() {
        $('.dropify').dropify();
        //$('#myTable').DataTable();
        $(document).on('submit', '#company_form', function(event){
              event.preventDefault(); 
              $('#com_admin').prop('disabled', false);
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/insert_company",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        $('#company_form')[0].reset();
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
                                      /*location.reload();*/
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("com_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#com_name").val("");
                            document.getElementById("com_name").focus();
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
            var com_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/manage/delete_company_data",
                    method:"POST",
                    data:{com_id_delete:com_id},
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
                            title: '<?php echo label("cannot_delcom_ifuseractive"); ?>',
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

        function clear_dropify(id){
                    var imagenUrl = "";
                    var drEvent = $('.'+id).dropify(
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
           $('#add_button').click(function(){
                  $("#modal-default").modal({backdrop: false});
                  $('.modal-title').text('<?php echo label("addcompany"); ?>');
                  $('#company_form')[0].reset();
                  $('#operation').val("Add");
                  clear_dropify('dropify_top');
                  clear_dropify('dropify_footer');
                    $('#com_code').attr('readonly', false);
                    $('#com_name_th').attr('readonly', false);
                    $('#com_name_eng').attr('readonly', false);
                    $('#com_admin').attr('readonly', false);
                    $('#com_mail').attr('readonly', false);
                    $('#com_emaildomain').attr('readonly', false);
            });

          $(document).on('click', '.update', function(){
            var com_id = $(this).attr("id");
            
            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_company_data",
              method:"POST",
              data:{com_id_update:com_id},
              dataType:"json",
              success:function(data)
              {                


                  $("#modal-default").modal({backdrop: false});
                  $('.modal-title').text('<?php echo label("editcompany"); ?>');
                  $('#company_form')[0].reset();
                  $('#operation').val("Edit");
                  $('.dropify_top').dropify();
                  $('.dropify_footer').dropify();

                    if(data.com_logo_top!=""){
                        var nameImage = "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_top
                        var drEvent_top = $(".dropify_top").dropify(
                        {
                          defaultFile: nameImage
                        });
                        drEvent_top = drEvent_top.data('dropify');
                        drEvent_top.resetPreview();
                        drEvent_top.clearElement();
                        drEvent_top.settings.defaultFile = nameImage;
                        drEvent_top.destroy();
                        drEvent_top.init();

                        var drEvent_top = $('.dropify_top').dropify({
                            defaultFile: "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_top ,
                        });

                        var dropifyElementtop = {};
                        $('.dropify_top').each(function() {
                            dropifyElementtop[this.id] = true;
                        });

                        drEvent_top.on('dropify.beforeClear', function(event, element){
                            $('#com_logo_top_ori').val("");
                            /*id = event.target.id;
                            if(dropifyElementtop[id]) {
                                  swal({
                                      title: '<?php echo label('wg_delete_msg'); ?> ',
                                      text: "",
                                      type: 'warning',
                                      showCancelButton: true,
                                      confirmButtonColor: "#DD6B55",
                                      confirmButtonText: '<?php echo label('wg_delete'); ?>',
                                      cancelButtonText: '<?php echo label("m_cancel"); ?>'
                                  }).then(function (isChk) {
                                    if(isChk.value){
                                        $.ajax({
                                              url:"<?=base_url()?>index.php/querydata/delete_img_com_logo",
                                              method:"POST",
                                              data:{com_id:com_id,type:'com_logo_top'},
                                              dataType:"json",
                                              success:function(data)
                                              {
                                                  if(data.status=="2"){
                                                      swal(
                                                          '<?php echo label("com_msg_delete"); ?>!',
                                                          '',
                                                          'success'
                                                      ).then(function () {
                                                        location.reload();
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
                                    }else{
                                      var bgpic_user = $('.dropify_top').dropify({
                                           defaultFile: "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_top ,
                                      });
                                    }
                                  });
                                return false;

                            }
                            element.value = "";*/
                        });
                    }else{
                        var nameImage = "";
                        var drEvent_top = $(".dropify_top").dropify(
                        {
                          defaultFile: nameImage
                        });
                        drEvent_top = drEvent_top.data('dropify');
                        drEvent_top.resetPreview();
                        drEvent_top.clearElement();
                        drEvent_top.settings.defaultFile = nameImage;
                        drEvent_top.destroy();
                        drEvent_top.init();
                    }

                    if(data.com_logo_footer!=""){

                        var nameImage = "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_footer
                        var drEvent = $('.dropify_footer').dropify(
                        {
                          defaultFile: nameImage
                        });
                        drEvent = drEvent.data('dropify');
                        drEvent.resetPreview();
                        drEvent.clearElement();
                        drEvent.settings.defaultFile = nameImage;
                        drEvent.destroy();
                        drEvent.init();

                        var drEvent = $('.dropify_footer').dropify({
                            defaultFile: "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_footer ,
                        });
                        var dropifyElementfooter = {};
                        $('.com_logo_footer').each(function() {
                            dropifyElementfooter[this.id] = true;
                        });

                        drEvent.on('dropify.beforeClear', function(event, element){

                            $('#com_logo_footer_ori').val("");
                            /*id = event.target.id;
                            console.log(id);
                            if(dropifyElementfooter[id]) {
                                  swal({
                                      title: '<?php echo label('wg_delete_msg'); ?> ',
                                      text: "",
                                      type: 'warning',
                                      showCancelButton: true,
                                      confirmButtonColor: "#DD6B55",
                                      confirmButtonText: '<?php echo label('wg_delete'); ?>',
                                      cancelButtonText: '<?php echo label("m_cancel"); ?>'
                                  }).then(function (isChk) {
                                    if(isChk.value){
                                        $.ajax({
                                              url:"<?=base_url()?>index.php/querydata/delete_img_com_logo",
                                              method:"POST",
                                              data:{com_id:com_id,type:'com_logo_footer'},
                                              dataType:"json",
                                              success:function(data)
                                              {
                                                  if(data.status=="2"){
                                                      swal(
                                                          '<?php echo label("com_msg_delete"); ?>!',
                                                          '',
                                                          'success'
                                                      ).then(function () {
                                                        location.reload();
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
                                    }else{
                                      var bgpic_user = $('.com_logo_footer').dropify({
                                           defaultFile: "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_footer ,
                                      });
                                    }
                                  });
                                return false;

                            }

                            element.value = "";*/
                        });
                    }else{
                        var nameImage = "";
                        var drEvent = $('.dropify_footer').dropify(
                        {
                          defaultFile: nameImage
                        });
                        drEvent = drEvent.data('dropify');
                        drEvent.resetPreview();
                        drEvent.clearElement();
                        drEvent.settings.defaultFile = nameImage;
                        drEvent.destroy();
                        drEvent.init();
                    }
                  /*if(data.com_logo_top!=""){
                    var imagenUrl = "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_top;
                    var drEvent = $('#com_logo_top').dropify(
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
                    $('#com_logo_top').dropify(); 
                  }  */
                    $('#com_logo_top_ori').val(data.com_logo_top);
                    $('#com_logo_footer_ori').val(data.com_logo_footer);
                  <?php if($user['ug_id']!="1"){ ?>
                    $('#com_code').attr('readonly', true);
                    $('#com_name_th').attr('readonly', true);
                    $('#com_name_eng').attr('readonly', true);
                    $('#com_mail').attr('readonly', true);
                    $('#com_admin').prop('disabled', true);
                    $('#com_emaildomain').attr('readonly', true);
                    <?php if(in_array($user['ug_id'], array('2','6'))){ ?>
                    $('#com_mail').attr('readonly', false);
                    <?php } ?>
                  <?php }else{ ?>
                    $('#com_code').attr('readonly', false);
                    $('#com_name_th').attr('readonly', false);
                    $('#com_name_eng').attr('readonly', false);
                    $('#com_admin').attr('readonly', false);
                    $('#com_mail').attr('readonly', false);
                    $('#com_admin').prop('disabled', false);
                    $('#com_emaildomain').attr('readonly', false);
                  <?php } ?>





                 /* if(data.com_logo_footer!=""){
                    var imagenUrl = "<?php echo REAL_PATH;?>/uploads/logo/"+data.com_logo_footer;
                    var drEvent = $('#com_logo_footer').dropify(
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
                    $('#com_logo_footer').dropify(); 
                  }  */
                $('#com_code').val(data.com_code);
                $('#com_name_th').val(data.com_name_th);
                $('#com_name_eng').val(data.com_name_eng);
                $('#com_admin').val(data.com_admin);
                $('#com_mail').val(data.com_mail);
                $('#com_emaildomain').val(data.com_emaildomain);
                $('#com_tel').val(data.com_tel);
                $('#com_fax').val(data.com_fax);  
                $('#com_add_th').val(data.com_add_th);
                $('#com_add_eng').val(data.com_add_eng); 
                $('#com_id').val(data.com_id); 
                $('#com_wctitle_th').val(data.com_wctitle_th); 
                $('#com_wctitle_eng').val(data.com_wctitle_eng); 
                $('#com_wctitle_jp').val(data.com_wctitle_jp); 
                $(tinymce.get('com_wcmessage_th').getBody()).html(data.com_wcmessage_th);
                $(tinymce.get('com_wcmessage_eng').getBody()).html(data.com_wcmessage_eng);
                $(tinymce.get('com_wcmessage_jp').getBody()).html(data.com_wcmessage_jp);
              }
            });
            
          });
    });
    </script>
</body>

</html>