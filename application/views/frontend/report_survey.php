<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>

<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
  type="text/css" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css"
  href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<!-- Date picker plugins css -->
<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
  type="text/css" />
<!-- Daterange picker plugins css -->
<link href="<?php echo REAL_PATH;?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/adapter.min.js"></script>
<script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/vue.min.js"></script>
<link href="<?php echo REAL_PATH;?>/assets/plugins/morrisjs/morris.css" rel="stylesheet">
<!--nestable CSS -->
<link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/css/report-theme.css?v=20260811-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border report-theme-page">
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
            <b><?php echo ucwords(label('report_survey')); ?></b>
          </div>
          <div class="col-md-7 align-self-right">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a
                  href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
              <li class="breadcrumb-item"><?php echo ucwords(label('report')); ?></li>
              <li class="breadcrumb-item"><?php echo ucwords(label('report_general')); ?></li>
              <li class="breadcrumb-item active"><?php echo ucwords(label('report_survey')); ?></li>
            </ol>
          </div>
        </div>

        <div class="row col-12 page-titles">
          <div class="col-md-12 card">
            <div class="card-body">
              <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
              <div class="row">
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <label for="com_id"><?php echo label('com_name'); ?>:</label>
                    <select class="form-control select2" id="com_id" name="com_id" style="width: 100%;">
                      <option value=""><?php echo label('allcompany'); ?></option>
                      <?php foreach( $company_select as $company ){ ?>
                      <option value="<?php echo $company['com_id']; ?>">
                        <?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <hr>
              <?php }else{ ?>
              <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
              <?php } ?>
              <div class="table-responsive">
                <table id="myTable" width="100%" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <?php if($user['ug_id']=="1"){ ?>
                      <th width="15%" align="center"><?php echo label('m_company'); ?></th>
                      <?php  } ?>
                      <th width="20%" align="center"><?php echo label('ceCname'); ?></th>
                      <th width="35%" align="center"><?php echo label('course_survey_name'); ?></th>
                      <th width="10%" align="center"><?php echo label('total_answer'); ?></th>
                      <th width="10%" align="center"><?php echo label('survey_report_completed'); ?></th>
                      <th width="10%" align="center"><?php echo label('noProgress'); ?></th>
                      <th width="10%" align="center"><?php echo label('detail'); ?></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i
                    class="mdi mdi-format-list-bulleted"></i></button> = <b><?php echo label('r_viewDetail'); ?></b></p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

  <!-- wysuhtml5 Plugin JavaScript -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript">
  </script>
  <script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js">
  </script>
  <!-- ============================================================== -->
  <!-- Style switcher -->
  <!-- ============================================================== -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
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
  <!--Morris JavaScript -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/raphael/raphael-min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/morrisjs/morris.js"></script>
  <script type="text/javascript">
  $('.select2').select2();
  fetch_data_student();
  $.fn.dataTable.ext.errMode = "none";
  function fetch_data_student() {
    var com_id = $('#com_id').val();
    $('#myTable').DataTable().destroy();
    $('#myTable').on('error.dt', function(e, settings, techNote, message) {
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
        url: '<?=base_url()?>index.php/report/fetch_course_survey/',
        data: {
          com_id: com_id,
          lang: "<?php echo $lang; ?>"
        },
        type: 'GET'
      },
      <?php if($btn_print=="1"){?>
      dom: 'Bfrtip',
      buttons: [{
          extend: 'copy',
          exportOptions: {
            <?php if($user['ug_id']=="1"){ ?>
            columns: [0, 1, 2, 3, 4, 5]
            <?php }else{ ?>
            columns: [0, 1, 2, 3, 4]
            <?php } ?>
          },
        },
        {
          extend: 'excel',
          exportOptions: {
            <?php if($user['ug_id']=="1"){ ?>
            columns: [0, 1, 2, 3, 4, 5]
            <?php }else{ ?>
            columns: [0, 1, 2, 3, 4]
            <?php } ?>
          },
        },
        {
          extend: 'print',
          exportOptions: {
            <?php if($user['ug_id']=="1"){ ?>
            columns: [0, 1, 2, 3, 4, 5]
            <?php }else{ ?>
            columns: [0, 1, 2, 3, 4]
            <?php } ?>
          },
        },
      ]
      <?php } ?>
    });
  }

  $(document).on('click', '.view_survey', function() {
    var sv_id = $(this).attr("id");

    window.location.href = "<?php echo base_url().'report/loadreport_survey_detail/'; ?>" + sv_id;
  });
  $('select[name="com_id"]').on('change', function() {
    var com_id = $(this).val();
    fetch_data_student();

  });
  </script>
</body>

</html>
