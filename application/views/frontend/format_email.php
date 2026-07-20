<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link rel="stylesheet" type="text/css"
  href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">

<style type="text/css">
.span_hover {
  background-color: #bdc3c7;
  font-size: 14px;
}

.span_hover:hover {
  background-color: #e67e22;
  cursor: pointer;
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
      <p class="loader__label">
        <?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
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
              <li class="breadcrumb-item"><a
                  href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
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
              <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="5%"><?php echo label('manage'); ?></th>
                      <th width="1%"></th>
                      <th width="15%">
                        <center><?php echo label('sqtype'); ?></center>
                      </th>
                      <th width="20%">
                        <center><?php echo label('stitle')." TH"; ?></center>
                      </th>
                      <th width="20%">
                        <center><?php echo label('stitle')." EN"; ?></center>
                      </th>
                      <th width="10%">
                        <center><?php echo label('status'); ?></center>
                      </th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
  <div class="modal fade bs-example-modal-lg" id="modal-defaultemail" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Large modal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <form method="post" id="template_email_form" autocomplete="off" name="template_email_form"
          enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="modal-body row">
            <input type="hidden" id="smf_type" name="smf_type">
            <div class="col-md-12">
              <div class="form-group">
                <label for="smf_show"><b style="color: #FF2D00">*</b><?php echo label('status'); ?>:</label>
                <div class="switch">
                  <label><?php echo label('close'); ?><input type="checkbox" id="smf_show" name="smf_show" value="1"
                      checked><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="smf_subject_th"><b style="color: #FF2D00">*</b><?php echo label('stitle')." TH"; ?>:</label>
                <input type="text" id="smf_subject_th" name="smf_subject_th" class="form-control" required>
                <span class="badge-pill badge-warning span_hover fullnametaghead"
                  onclick="TagValue('#fullname','smf_subject_th')">#<?php echo label('m_name'); ?></span><span
                  class="badge-pill badge-warning span_hover usernametaghead"
                  onclick="TagValue('#username','smf_subject_th')">#<?php echo label('m_username'); ?></span><span
                  class="badge-pill badge-warning span_hover passwordtaghead"
                  onclick="TagValue('#password','smf_subject_th')">#<?php echo label('password'); ?></span><span
                  class="badge-pill badge-warning span_hover emailtaghead"
                  onclick="TagValue('#email','smf_subject_th')">#<?php echo label('m_mail'); ?></span><span
                  class="badge-pill badge-warning span_hover datetaghead"
                  onclick="TagValue('#date','smf_subject_th')">#<?php echo label('day'); ?></span><span
                  class="badge-pill badge-warning span_hover timetaghead"
                  onclick="TagValue('#time','smf_subject_th')">#<?php echo label('log_time'); ?></span><span
                  class="badge-pill badge-warning span_hover link_frontendtaghead"
                  onclick="TagValue('#link_frontend','smf_subject_th')">#<?php echo label('link_frontend'); ?></span><span
                  class="badge-pill badge-warning span_hover coursenametaghead"
                  onclick="TagValue('#coursename','smf_subject_th')">#<?php echo label('name_cos_survey'); ?></span><span
                  class="badge-pill badge-warning span_hover perioddatetaghead"
                  onclick="TagValue('#perioddate','smf_subject_th')">#<?php echo label('perioddate'); ?></span><span
                  class="badge-pill badge-warning span_hover messagetaghead"
                  onclick="TagValue('#message','smf_subject_th')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover expiredatetag"
                  onclick="TagValue('#expiredate','smf_subject_th')">#<?php echo label('expiredate'); ?></span><span
                  class="badge-pill badge-warning span_hover companynametag"
                  onclick="TagValue('#companyname','smf_subject_th')">#<?php echo label('p_company'); ?></span><span
                  class="badge-pill badge-warning span_hover durationofstudytag"
                  onclick="TagValue('#durationofstudy','smf_subject_th')">#<?php echo label('cos_hour'); ?></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="smf_subject_en"><b style="color: #FF2D00">*</b><?php echo label('stitle')." EN"; ?>:</label>
                <input type="text" id="smf_subject_en" name="smf_subject_en" class="form-control" required>
                <span class="badge-pill badge-warning span_hover  fullnametaghead"
                  onclick="TagValue('#fullname','smf_subject_en')">#<?php echo label('m_name'); ?></span><span
                  class="badge-pill badge-warning span_hover usernametaghead"
                  onclick="TagValue('#username','smf_subject_en')">#<?php echo label('m_username'); ?></span><span
                  class="badge-pill badge-warning span_hover passwordtaghead"
                  onclick="TagValue('#password','smf_subject_en')">#<?php echo label('password'); ?></span><span
                  class="badge-pill badge-warning span_hover emailtaghead"
                  onclick="TagValue('#email','smf_subject_en')">#<?php echo label('m_mail'); ?></span><span
                  class="badge-pill badge-warning span_hover datetaghead"
                  onclick="TagValue('#date','smf_subject_en')">#<?php echo label('day'); ?></span><span
                  class="badge-pill badge-warning span_hover timetaghead"
                  onclick="TagValue('#time','smf_subject_en')">#<?php echo label('log_time'); ?></span><span
                  class="badge-pill badge-warning span_hover link_frontendtaghead"
                  onclick="TagValue('#link_frontend','smf_subject_en')">#<?php echo label('link_frontend'); ?></span><span
                  class="badge-pill badge-warning span_hover coursenametaghead"
                  onclick="TagValue('#coursename','smf_subject_en')">#<?php echo label('name_cos_survey'); ?></span><span
                  class="badge-pill badge-warning span_hover perioddatetaghead"
                  onclick="TagValue('#perioddate','smf_subject_en')">#<?php echo label('perioddate'); ?></span><span
                  class="badge-pill badge-warning span_hover messagetaghead"
                  onclick="TagValue('#message','smf_subject_en')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover expiredatetag"
                  onclick="TagValue('#expiredate','smf_subject_en')">#<?php echo label('expiredate'); ?></span><span
                  class="badge-pill badge-warning span_hover companynametag"
                  onclick="TagValue('#companyname','smf_subject_en')">#<?php echo label('p_company'); ?></span><span
                  class="badge-pill badge-warning span_hover durationofstudytag"
                  onclick="TagValue('#durationofstudy','smf_subject_en')">#<?php echo label('cos_hour'); ?></span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="smf_message_th"><b
                    style="color: #FF2D00">*</b><?php echo label('contamess')." TH"; ?>:</label>
                <textarea id="smf_message_th" name="smf_message_th" class="form-control" style="width: 100%"
                  rows="10"></textarea>
                <span class="badge-pill badge-warning span_hover fullnametag"
                  onclick="TagValueTextarea('#fullname','smf_message_th')">#<?php echo label('m_name'); ?></span><span
                  class="badge-pill badge-warning span_hover usernametag"
                  onclick="TagValueTextarea('#username','smf_message_th')">#<?php echo label('m_username'); ?></span><span
                  class="badge-pill badge-warning span_hover passwordtag"
                  onclick="TagValueTextarea('#password','smf_message_th')">#<?php echo label('password'); ?></span><span
                  class="badge-pill badge-warning span_hover emailtag"
                  onclick="TagValueTextarea('#email','smf_message_th')">#<?php echo label('m_mail'); ?></span><span
                  class="badge-pill badge-warning span_hover datetag"
                  onclick="TagValueTextarea('#date','smf_message_th')">#<?php echo label('day'); ?></span><span
                  class="badge-pill badge-warning span_hover timetag"
                  onclick="TagValueTextarea('#time','smf_message_th')">#<?php echo label('log_time'); ?></span><span
                  class="badge-pill badge-warning span_hover link_frontendtag"
                  onclick="TagValueTextarea('#link_frontend','smf_message_th')">#<?php echo label('link_frontend'); ?></span><span
                  class="badge-pill badge-warning span_hover imagetag"
                  onclick="TagValueTextarea('#image','smf_message_th')">#<?php echo label('image'); ?></span><span
                  class="badge-pill badge-warning span_hover coursenametag"
                  onclick="TagValueTextarea('#coursename','smf_message_th')">#<?php echo label('name_cos_survey'); ?></span><span
                  class="badge-pill badge-warning span_hover perioddatetag"
                  onclick="TagValueTextarea('#perioddate','smf_message_th')">#<?php echo label('perioddate'); ?></span><span
                  class="badge-pill badge-warning span_hover messagetag"
                  onclick="TagValueTextarea('#message','smf_message_th')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover expiredatetag"
                  onclick="TagValueTextarea('#expiredate','smf_message_th')">#<?php echo label('expiredate'); ?></span><span
                  class="badge-pill badge-warning span_hover companynametag"
                  onclick="TagValueTextarea('#companyname','smf_message_th')">#<?php echo label('p_company'); ?></span><span
                  class="badge-pill badge-warning span_hover stafflisttag"
                  onclick="TagValueTextarea('#stafflist','smf_message_th')">#Staff List</span><span
                  class="badge-pill badge-warning span_hover contactnametag"
                  onclick="TagValueTextarea('#contactname','smf_message_th')">#<?php echo label('com_contact'); ?></span><span
                  class="badge-pill badge-warning span_hover contactteltag"
                  onclick="TagValueTextarea('#contacttel','smf_message_th')">#<?php echo label('com_tel'); ?></span><span
                  class="badge-pill badge-warning span_hover contactmailtag"
                  onclick="TagValueTextarea('#contactmail','smf_message_th')">#<?php echo label('com_contactmail'); ?></span><span
                  class="badge-pill badge-warning span_hover contactmessagetag"
                  onclick="TagValueTextarea('#contactmessage','smf_message_th')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover durationofstudytag"
                  onclick="TagValueTextarea('#durationofstudy','smf_message_th')">#<?php echo label('cos_hour'); ?></span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="smf_message_en"><b
                    style="color: #FF2D00">*</b><?php echo label('contamess')." EN"; ?>:</label>
                <textarea id="smf_message_en" name="smf_message_en" class="form-control" style="width: 100%"
                  rows="4"></textarea>
                <span class="badge-pill badge-warning span_hover fullnametag"
                  onclick="TagValueTextarea('#fullname','smf_message_en')">#<?php echo label('m_name'); ?></span><span
                  class="badge-pill badge-warning span_hover usernametag"
                  onclick="TagValueTextarea('#username','smf_message_en')">#<?php echo label('m_username'); ?></span><span
                  class="badge-pill badge-warning span_hover passwordtag"
                  onclick="TagValueTextarea('#password','smf_message_en')">#<?php echo label('password'); ?></span><span
                  class="badge-pill badge-warning span_hover emailtag"
                  onclick="TagValueTextarea('#email','smf_message_en')">#<?php echo label('m_mail'); ?></span><span
                  class="badge-pill badge-warning span_hover datetag"
                  onclick="TagValueTextarea('#date','smf_message_en')">#<?php echo label('day'); ?></span><span
                  class="badge-pill badge-warning span_hover timetag"
                  onclick="TagValueTextarea('#time','smf_message_en')">#<?php echo label('log_time'); ?></span><span
                  class="badge-pill badge-warning span_hover link_frontendtag"
                  onclick="TagValueTextarea('#link_frontend','smf_message_en')">#<?php echo label('link_frontend'); ?></span><span
                  class="badge-pill badge-warning span_hover imagetag"
                  onclick="TagValueTextarea('#image','smf_message_en')">#<?php echo label('image'); ?></span><span
                  class="badge-pill badge-warning span_hover coursenametag"
                  onclick="TagValueTextarea('#coursename','smf_message_en')">#<?php echo label('name_cos_survey'); ?></span><span
                  class="badge-pill badge-warning span_hover perioddatetag"
                  onclick="TagValueTextarea('#perioddate','smf_message_en')">#<?php echo label('perioddate'); ?></span><span
                  class="badge-pill badge-warning span_hover messagetag"
                  onclick="TagValueTextarea('#message','smf_message_en')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover expiredatetag"
                  onclick="TagValueTextarea('#expiredate','smf_message_en')">#<?php echo label('expiredate'); ?></span><span
                  class="badge-pill badge-warning span_hover companynametag"
                  onclick="TagValueTextarea('#companyname','smf_message_en')">#<?php echo label('p_company'); ?></span><span
                  class="badge-pill badge-warning span_hover stafflisttag"
                  onclick="TagValueTextarea('#stafflist','smf_message_en')">#Staff List</span><span
                  class="badge-pill badge-warning span_hover contactnametag"
                  onclick="TagValueTextarea('#contactname','smf_message_en')">#<?php echo label('com_contact'); ?></span><span
                  class="badge-pill badge-warning span_hover contactteltag"
                  onclick="TagValueTextarea('#contacttel','smf_message_en')">#<?php echo label('com_tel'); ?></span><span
                  class="badge-pill badge-warning span_hover contactmailtag"
                  onclick="TagValueTextarea('#contactmail','smf_message_en')">#<?php echo label('com_contactmail'); ?></span><span
                  class="badge-pill badge-warning span_hover contactmessagetag"
                  onclick="TagValueTextarea('#contactmessage','smf_message_en')">#<?php echo label('contamess'); ?></span><span
                  class="badge-pill badge-warning span_hover durationofstudytag"
                  onclick="TagValueTextarea('#durationofstudy','smf_message_en')">#<?php echo label('cos_hour'); ?></span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="smf_message_en"><?php echo label('email_import_image'); ?>:</label>
                <input type="file" name="smf_importimage" id="smf_importimage" class="dropify"
                  accept="image/png, image/jpeg, image/gif" />
              </div>
            </div>
            <script type="text/javascript">
            function TagValue(tag, id) {
              var value = $('#' + id).val();
              $('#' + id).val(value + tag);
            }

            function TagValueTextarea(tag, id) {
              var value = tinymce.get(id).getContent();
              tinymce.get(id).setContent(value + tag);
              // $('#'+id).val(value+tag);
            }
            </script>
          </div>
          <input type="hidden" id="operation" name="operation" value="Add">
          <input type="hidden" id="smf_id" name="smf_id">
          <div class="modal-footer">
            <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i
                class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <script type="text/javascript">
  var base_url = "<?php echo REAL_PATH; ?>";
  </script>
  <!-- This is data table -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
  <!-- ============================================================== -->
  <!-- Style switcher -->
  <!-- ============================================================== -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
  <!--stickey kit -->
  <script type="text/javascript">
  $('#smf_importimage').dropify();
  textarea_tinymce('smf_message_th');
  textarea_tinymce('smf_message_en');

  function textarea_tinymce(id) {
    if ($("#" + id).length > 0) {
      tinymce.init({
        selector: "textarea#" + id,
        theme: "modern",
        height: 300,
        plugins: [
          "advlist autolink link image lists charmap hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
          "save table contextmenu directionality paste textcolor"
        ],
        toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor ",
        automatic_uploads: true,

        images_upload_url: '<?=base_url()?>index.php/setting/upload_img_texteditor',
        automatic_uploads: false,

        images_upload_handler: function(blobInfo, success, failure) {
          var xhr, formData;

          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', '<?=base_url()?>index.php/setting/upload_img_texteditor');

          xhr.onload = function() {
            var json;

            if (xhr.status != 200) {
              if (xhr.status == 400) {
                failure('Please use English filename');
              } else {
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
  }

  $.fn.dataTable.ext.errMode = "none";

  function clear_dropify(id) {
    var imagenUrl = "";
    var drEvent = $('#' + id).dropify({
      defaultFile: imagenUrl
    });
    drEvent = drEvent.data('dropify');
    drEvent.resetPreview();
    drEvent.clearElement();
    drEvent.settings.defaultFile = imagenUrl;
    drEvent.destroy();
    drEvent.init();
  }
  fetch_data_main(0);

  function fetch_data_main(page_num) {
    $('#myTable').DataTable().destroy();
    var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable", message);
    }).DataTable({
      "language": {
        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
        "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
        "sInfo": "<?php echo label('sInfo'); ?>",
        "sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
        "decimal": "",
        "emptyTable": "<?php echo label('wg_datanotfound'); ?>",
        "infoPostFix": "",
        "thousands": ",",
        //"lengthMenu":     "แสดง _MENU_ รายการ",
        "lengthMenu": "<?php echo label('lengthMenu'); ?>",
        "loadingRecords": "<?php echo label('loadingRecords'); ?>",
        "processing": "<?php echo label('processing'); ?>",
        "search": "<?php echo label('filter_bar'); ?>",
        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
        "paginate": {
          "first": "<?php echo label('firstpage'); ?>",
          "last": "<?php echo label('last'); ?>",
          "next": "<?php echo label('lrn_btn_next'); ?>",
          "previous": "<?php echo label('previous'); ?>"
        },
      },
      "scrollX": true,
      "ajax": {
        url: '<?=base_url()?>index.php/setting/fetch_detail_format_email/',
        type: 'GET',
        data : {lang: "<?php echo $lang; ?>"}
      },
      "initComplete": function() {
        setTimeout(function() {
          var info = table.page.info();
          var length = info.pages;
          var page_current = info.page;
          if ((page_num + 1) > length) {
            page_num = length - 1;
          }
          table.page(page_num).draw(false);
        }, 10);
      }
    });
  }

  function onupdate_typedata(value) {
    $('.fullnametaghead').hide();
    $('.usernametaghead').hide();
    $('.passwordtaghead').hide();
    $('.emailtaghead').hide();
    $('.datetaghead').hide();
    $('.timetaghead').hide();
    $('.link_frontendtaghead').hide();
    $('.coursenametaghead').hide();
    $('.perioddatetaghead').hide();
    $('.messagetaghead').hide();
    $('.stafflisttag').hide();
    $('.contactnametag').hide();
    $('.contactteltag').hide();
    $('.contactmailtag').hide();
    $('.contactmessagetag').hide();

    $('.fullnametag').hide();
    $('.usernametag').hide();
    $('.passwordtag').hide();
    $('.emailtag').hide();
    $('.datetag').hide();
    $('.timetag').hide();
    $('.link_frontendtag').hide();
    $('.imagetag').hide();
    $('.coursenametag').hide();
    $('.perioddatetag').hide();
    $('.messagetag').hide();
    $('.expiredatetag').hide();
    $('.companynametag').show();
    $('.durationofstudytag').hide();


    if (value == "1" || value == "17") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "2") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "3") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "4") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "5") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "6") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
      $('.durationofstudytag').show();
    } else if (value == "10") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "12") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "13") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "14") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.expiredatetag').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "7") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "8") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "9") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
    } else if (value == "11") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
    } else if (value == "15" || value == "16") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.expiredatetag').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "18") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
      $('.stafflisttag').show();
      $('.durationofstudytag').show();
    } else if (value == "19") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.contactnametag').show();
      $('.contactteltag').show();
      $('.contactmailtag').show();
      $('.contactmessagetag').show();
    }
  }

  function onchk_typedata(value) {
    $('.fullnametaghead').hide();
    $('.usernametaghead').hide();
    $('.passwordtaghead').hide();
    $('.emailtaghead').hide();
    $('.datetaghead').hide();
    $('.timetaghead').hide();
    $('.link_frontendtaghead').hide();
    $('.coursenametaghead').hide();
    $('.perioddatetaghead').hide();
    $('.messagetaghead').hide();
    $('.stafflisttag').hide();
    $('.contactnametag').hide();
    $('.contactteltag').hide();
    $('.contactmailtag').hide();
    $('.contactmessagetag').hide();

    $('.fullnametag').hide();
    $('.usernametag').hide();
    $('.passwordtag').hide();
    $('.emailtag').hide();
    $('.datetag').hide();
    $('.timetag').hide();
    $('.link_frontendtag').hide();
    $('.imagetag').hide();
    $('.coursenametag').hide();
    $('.perioddatetag').hide();
    $('.messagetag').hide();
    $('.expiredatetag').hide();
    $('.companynametag').show();
    $('.durationofstudytag').hide();

    if (value == "1" || value == "17") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "2") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "3") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
    } else if (value == "4") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "5") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "6") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
      $('.durationofstudytag').show();
    } else if (value == "10") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "12") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "13") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "14") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.expiredatetag').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.durationofstudytag').show();
    } else if (value == "7") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "8") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "9") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
    } else if (value == "11") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
    } else if (value == "15" || value == "16") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.expiredatetag').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
    } else if (value == "18") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();
      $('.coursenametaghead').show();
      $('.perioddatetaghead').show();
      $('.messagetaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.coursenametag').show();
      $('.perioddatetag').show();
      $('.messagetag').show();
      $('.stafflisttag').show();
      $('.durationofstudytag').show();
    } else if (value == "19") {
      $('.fullnametaghead').show();
      $('.usernametaghead').show();
      $('.passwordtaghead').show();
      $('.emailtaghead').show();
      $('.datetaghead').show();
      $('.timetaghead').show();
      $('.link_frontendtaghead').show();

      $('.fullnametag').show();
      $('.usernametag').show();
      $('.passwordtag').show();
      $('.emailtag').show();
      $('.datetag').show();
      $('.timetag').show();
      $('.link_frontendtag').show();
      $('.imagetag').show();
      $('.contactnametag').show();
      $('.contactteltag').show();
      $('.contactmailtag').show();
      $('.contactmessagetag').show();
    }
    $.ajax({
      url: "<?=base_url()?>index.php/setting/query_rowdata",
      method: "POST",
      data: {
        fieldname: 'smf_type',
        id: value,
        dataname: 'lms_sendmail_form'
      },
      dataType: "json",
      success: function(data) {
        if (data.isData == "1") {
          $('.modal-title').text('<?php echo label("editTemplateMail"); ?>:' +
            '<?php echo label("templateMail"); ?>' + value);
          $('#template_email_form')[0].reset();
          $('#operation').val("Edit");
          $('#smf_subject_th').val(data.smf_subject_th);
          $('#smf_subject_en').val(data.smf_subject_en);
          $(tinymce.get('smf_message_th').getBody()).html(data.smf_message_th);
          $(tinymce.get('smf_message_en').getBody()).html(data.smf_message_en);
          $('#smf_type').val(data.smf_type);
          if (data.smf_show == "1") {
            document.getElementById('smf_show').checked = true;
          } else {
            document.getElementById('smf_show').checked = false;
          }

          if (data.smf_importimage != "") {
            var imagenUrl = "<?php echo base_url();?>/uploads/formatmail_img/" + data.smf_importimage;
            var drEvent = $('#smf_importimage').dropify({
              defaultFile: imagenUrl
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = imagenUrl;
            drEvent.destroy();
            drEvent.init();
          } else {
            $('#smf_importimage').dropify();
          }
        } else {
          $('.modal-title').text('<?php echo label("createTemplateMail"); ?>');
          $('#template_email_form')[0].reset();
          $('#operation').val("Add");
          $('#smf_subject_th').val('');
          $('#smf_subject_en').val('');
          $(tinymce.get('smf_message_th').getBody()).html('');
          $(tinymce.get('smf_message_en').getBody()).html('');
          //console.log(value);
          $('#smf_type').val(value);
          document.getElementById('smf_show').checked = true;
          $('#smf_importimage').dropify();
        }
      }
    });
  }
  $('#add_button').click(function() {
    $('.modal-title').text('<?php echo label("createTemplateMail"); ?>');
    $('#template_email_form')[0].reset();
    $('#operation').val("Add");
    onchk_typedata('1');

    clear_dropify('smf_importimage');
  });
  $(document).ready(function() {
    $(document).on('submit', '#template_email_form', function(event) {
      event.preventDefault();
      $.ajax({
        url: "<?=base_url()?>index.php/setting/insert_template",
        method: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(data) {
          if (data == "2") {
            $('#template_email_form')[0].reset();
            $('#modal-defaultemail').modal('hide');
            swal(
              '<?php echo label("com_msg_success"); ?>!',
              '',
              'success'
            ).then(function() {
              var table = $('#myTable').DataTable();
              var info = table.page.info();
              var length = info.pages;
              var page_current = info.page;
              fetch_data_main(page_current);
            })
          } else if (data == "1") {
            swal({
              title: '<?php echo label("ug_msg_duplicate"); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label("m_ok"); ?>'
            }).then(function() {
              $("#smf_subject_th").val("");
              $("#smf_subject_en").val("");
              document.getElementById("smf_subject_th").focus();
            })
          } else {
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

    $(document).on('click', '.delete', function() {
      var smf_id = $(this).attr("id");
      swal({
        title: '<?php echo label('wg_delete_msg'); ?>',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: '<?php echo label('wg_delete'); ?>',
        cancelButtonText: '<?php echo label('cancel'); ?>'
      }).then(function(isChk) {
        if (isChk.value) {
          $.ajax({
            url: "<?=base_url()?>index.php/setting/delete_formatmail_data",
            method: "POST",
            data: {
              smf_id_delete: smf_id
            },
            success: function(data) {
              if (data == "2") {
                swal(
                  '<?php echo label("com_msg_delete"); ?>!',
                  '',
                  'success'
                ).then(function() {
                  var table = $('#myTable').DataTable();
                  var info = table.page.info();
                  var length = info.pages;
                  var page_current = info.page;
                  fetch_data_main(page_current);
                })
              } else if (data == "1") {
                swal({
                  title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                  text: "",
                  type: 'warning',
                  showCancelButton: false,
                  confirmButtonClass: 'btn btn-primary',
                  confirmButtonText: '<?php echo label('m_ok'); ?>'
                })
              } else {
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

    $(document).on('click', '.update', function() {
      var smf_id = $(this).attr("id");
      var smf_type_name = $(this).attr("title");
      $.ajax({
        url: "<?=base_url()?>index.php/setting/query_rowdata",
        method: "POST",
        data: {
          fieldname: 'smf_id',
          id: smf_id,
          dataname: 'lms_sendmail_form'
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('#modal-defaultemail').modal('show');
          $('.modal-title').text(smf_type_name);
          $('#template_email_form')[0].reset();
          $('#operation').val("Edit");
          $('#smf_subject_th').val(data.smf_subject_th);
          $('#smf_subject_en').val(data.smf_subject_en);
          $(tinymce.get('smf_message_th').getBody()).html(data.smf_message_th);
          $(tinymce.get('smf_message_en').getBody()).html(data.smf_message_en);
          $('#smf_type').val(data.smf_type);
          $('#smf_id').val(data.smf_id);
          onupdate_typedata(data.smf_type);
          if (data.smf_show == "1") {
            document.getElementById('smf_show').checked = true;
          } else {
            document.getElementById('smf_show').checked = false;
          }

          if (data.smf_importimage != "") {
            var imagenUrl = "<?php echo base_url();?>/uploads/formatmail_img/" + data.smf_importimage;
            var drEvent = $('#smf_importimage').dropify({
              defaultFile: imagenUrl
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = imagenUrl;
            drEvent.destroy();
            drEvent.init();
          } else {
            $('#smf_importimage').dropify();
          }
        }
      });

    });
  });
  </script>
</body>

</html>