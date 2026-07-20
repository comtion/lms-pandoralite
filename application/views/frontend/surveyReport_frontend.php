<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">

<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">

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
        <div class="row page-titles">
          <div class="col-5 align-self-center">
              <b><?php echo label('report_survey'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('report_survey'); ?></li>
            </ol>
          </div>
        </div>
  
        <div class="">
          <div class="">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-sm-12 card card-body">
                    <div class="row mt-3">
                      <div class="col-sm-12 col-lg-6"></div>
                      <div class="col-sm-12 col-lg-6">
                        <label><b style="color: #FF2D00">*</b><?php echo label('com_name').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Company</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                      <table id="report_course_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th><?php echo label('com_name'); ?></th>
                            <th><?php echo label('svname'); ?></th>
                            <th><?php echo label('total_answer'); ?></th>
                            <th><?php echo label('done'); ?></th>
                            <th><?php echo label('noProgress'); ?></th>
                            <th><?php echo label('detail'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td title="Isuzu Global CV Engineering Center Co., Ltd.">IGCE</td>
                            <td title="The Art of Communication">The Art of Communication</td>
                            <td>782</td>
                            <td>722</td>
                            <td>60</td>
                            <td class="text-center">
                              <button type="button" class="btn btn-success btn-xs view_detail" title="<?php echo label('r_viewDetail'); ?>"><i class="mdi mdi-magnify"></i></button>
                            </td>
                          </tr>
                          <tr>
                            <td title="Shonan Unitec (Thailand) Co., Ltd.">SUTT</td>
                            <td title="Conflict Resolution - Dealing with Difficult People">Conflict Resolution - Dealing with Difficult People</td>
                            <td>854</td>
                            <td>850</td>
                            <td>4</td>
                            <td class="text-center">
                              <button type="button" class="btn btn-success btn-xs view_detail" title="<?php echo label('r_viewDetail'); ?>"><i class="mdi mdi-magnify"></i></button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>

    $('#report_course_table').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'print'
      ]
    });

    $('#modal_course_detail_table').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'print'
      ]
    });
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
