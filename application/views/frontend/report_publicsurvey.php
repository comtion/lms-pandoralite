<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link rel="stylesheet" type="text/css"
  href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
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

.loading-data {
  display: none;
  text-align: center;
  font-weight: bold
}
</style>
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
            <div class="card-body" id="div_main">
              <div class="col-md-12">
                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                <div class="row">
                  <div class="col-md-6">
                    <label for="com_id_search"><?php echo label('com_name'); ?>:</label>
                    <select class="form-control select2" onchange="fetch_data_main(0)" id="com_id_search"
                      name="com_id_search" style="width: 100%;">
                      <?php   if(countArray($company_arr)>0){ ?>
                      <optgroup label="<?php echo label('please_com_name'); ?>">
                        <option value=""><?php echo label('allcompany'); ?></option>
                        <?php   $numloop = 1;
                                                foreach ($company_arr as $key_com => $value_com) { ?>
                        <option value="<?php echo $value_com['com_id']; ?>"
                          <?php if($numloop==1){echo "selected";}$numloop++; ?>>
                          <?php echo $lang=="thai"?$value_com['com_name_th']:$value_com['com_name_eng']; ?></option>
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
                      <th width="5%" align="center"></th>
                      <th width="25%" align="center">
                        <center><?php echo label('com_name'); ?></center>
                      </th>
                      <th width="30%" align="center">
                        <center><?php echo label('svname'); ?></center>
                      </th>
                      <th width="10%" align="center">
                        <center><?php echo label('total_answer'); ?></center>
                      </th>
                      <th width="10%" align="center">
                        <center><?php echo label('survey_report_completed'); ?></center>
                      </th>
                      <th width="10%" align="center">
                        <center><?php echo label('noProgress'); ?></center>
                      </th>
                      <th width="10%" align="center">
                        <center><?php echo label('detail'); ?></center>
                      </th>
                    </tr>
                  </thead>
                </table>
              </div>
              <p><?php echo label('preNote'); ?>: <button type="button" title="<?php echo label('detail'); ?>"
                  class="btn btn-info btn-xs"><i class="mdi mdi-format-list-bulleted"></i></button> =
                <?php echo label('detail'); ?></p>
            </div>
            <div class="card-body" id="div_detail" style="display: none;">
              <div class="col-md-12">
                <button type="button" class="btn btn-outline-danger float-right btn_previous"><i
                    class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
              </div>
              <div class="loading-data">
                <?php echo label('loadingRecords');?></p>
              </div>
              <div id="div_detail_query">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>


  <script type="text/javascript">
  var base_url = "<?php echo REAL_PATH; ?>";
  </script>
  <!-- wysuhtml5 Plugin JavaScript -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>

  <script
    src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js">
  </script>
  <script
    src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js"
    charset="UTF-8"></script>
  <!-- start - This is for export functionality only -->
  <!-- This is data table -->
  <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

  <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
  <script type="text/javascript">
  $('.select2').select2();
  $.fn.dataTable.ext.errMode = "none";
  fetch_data_main(0);

  function fetch_data_main(page_num) {
    $('#myTable').DataTable().destroy();
    var com_id = $('#com_id_search').val();
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
        url: '<?=base_url()?>index.php/fetchdata/fetch_public_survey_report/',
        type: 'GET',
        data: {
          com_id: com_id,
          lang: "<?php echo $lang; ?>"
        }
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
      },
      <?php if($btn_print=="1"){ ?>
      dom: 'Bfrtip',
      buttons: [{
          extend: 'copyHtml5',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excelHtml5',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
      ]
      <?php } ?>
    });
  }
  $(document).on('click', '.detail', function() {
    var sv_id = $(this).attr("id");
    document.getElementById('div_detail').style.display = "";
    document.getElementById('div_main').style.display = "none";
    $(".loading-data").css("display", "block");
    $('#div_detail_query').html('');
    $.ajax({
      url: "<?=base_url()?>index.php/querydata/detail_publicsurvey_report",
      method: "POST",
      data: {
        sv_id: sv_id
      },
      success: function(data) {
        $(".loading-data").css("display", "none");
        $('#div_detail_query').html(data);
      }
    });

  });
  $(document).on('click', '.btn_previous', function() {
    document.getElementById('div_detail').style.display = "none";
    document.getElementById('div_main').style.display = "";
    fetch_data_main(0);
  });
  </script>
</body>

</html>
