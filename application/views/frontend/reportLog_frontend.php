<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">

<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">

    <link href="<?php echo REAL_PATH;?>/assets/css/report-theme.css?v=20260811-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border report-theme-page">
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
              <b><?php echo label('log_record'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('log_record'); ?></li>
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
                    <div class="row mt-3">
                      <div class="form-group col-md-12">
                        <label class="control-label text-right"><?php echo label('periodlog').' : '; ?></label>
                        <div class="row">
                          <div class="col-md-6">
                            <label class="control-label text-right">Start date:</label>
                            <div class="row">
                              <div class="col-md-8">
                                <input type="text" id="date_start" name="date_start" class="form-control date_start" value="01/01/2563">
                                <input type="hidden" id="date_start_var" name="date_start_var" value="2563-01-01">
                              </div>
                              <div class="col-md-4">
                                <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                  <input type="text" id="time_start" name="time_start" class="form-control" value="00:00">
                                </div>
                              </div>
                            </div>
                          </div>
                            <div class="col-md-6">
                              <label class="control-label text-right">End date:</label>
                              <div class="row">
                                <div class="col-md-8">
                                  <input type="text" id="date_end" name="date_end" class="form-control date_end" value="31/05/2563">
                                  <input type="hidden" id="date_end_var" name="date_end_var" value="NaN-undefined-">
                                </div>
                                <div class="col-md-4">
                                  <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" id="time_end" name="time_end" class="form-control" value="23:59">
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                      <table id="report_course_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th><?php echo label('p_email'); ?></th>
                            <th><?php echo label('name'); ?></th>
                            <th><?php echo label('m_usergroup'); ?></th>
                            <th><?php echo label('ip_add'); ?></th>
                            <th><?php echo label('device'); ?></th>
                            <th><?php echo label('log_re_action'); ?></th>
                            <th><?php echo label('log_date'); ?></th>
                            <th><?php echo label('log_time'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>bonus.s@sutt.co.th</td>
                            <td>Bonus Soythongjaroen</td>
                            <td>User</td>
                            <td>1.47.5.186,<br> 127.0.0.1</td>
                            <td>PC : windows</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td>                            
                            <td>15:02:50</td>
                          </tr>
                          <tr>
                            <td>fonthip.c@sutt.co.th</td>
                            <td>Fonthip Changwichukarn</td>
                            <td>User</td>
                            <td>182.232.135.33,<br> 127.0.0.1</td>
                            <td>PC : windows</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>09:17:50</td>
                          </tr>
                          <tr>
                            <td>jetsada.d@sutt.co.th</td>
                            <td>Jetsada Deesiripronchai</td>
                            <td>Admin</td>
                            <td>58.181.128.219,<br> 127.0.0.1</td>
                            <td>PC : windows</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>11:25:44</td>
                          </tr>
                          <tr>
                            <td>kanjana.g@sutt.co.th</td>
                            <td>Kanjana Getpan</td>
                            <td>User</td>
                            <td>182.232.137.56,<br> 127.0.0.1</td>
                            <td>PC : windows</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>11:12:07</td>
                          </tr>
                          <tr>
                            <td>karn.n@sutt.co.th</td>
                            <td>Karn Norant</td>
                            <td>User</td>
                            <td>49.229.64.143,<br> 127.0.0.1</td>
                            <td>Mobile : Android</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>14:22:07</td>
                          </tr>
                          <tr>
                            <td>nitchakamon.c@sutt.co.th</td>
                            <td>Nitchakamon Chamrasnet</td>
                            <td>User</td>
                            <td>58.181.128.219,<br> 127.0.0.1</td>
                            <td>Mobile : Android</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>14:27:07</td>
                          </tr>
                          <tr>
                            <td>preechaporn.s@sutt.co.th</td>
                            <td>Preechaporn Seelayong</td>
                            <td>User</td>
                            <td>182.232.168.144,<br> 127.0.0.1</td>
                            <td>PC : mac</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>14:27:07</td>
                          </tr>
                          <tr>
                            <td>premika.p@sutt.co.th</td>
                            <td>Premika Phutta</td>
                            <td>Admin</td>
                            <td>184.22.100.30,<br> 127.0.0.1</td>
                            <td>PC : mac</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>13:48:53</td>
                          </tr>
                          <tr>
                            <td>royya.j@sutt.co.th</td>
                            <td>Royya Japakiya</td>
                            <td>User</td>
                            <td>110.170.63.254,<br> 127.0.0.1</td>
                            <td>Mobile : Apple</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>14:27:07</td>
                          </tr>
                          <tr>
                            <td>tanarak.p@sutt.co.th</td>
                            <td>Tanarak Petchrak</td>
                            <td>User</td>
                            <td>49.230.99.219,<br> 127.0.0.1</td>
                            <td>Mobile : Apple</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>13:48:53</td>
                          </tr>
                          <tr>
                            <td>yupontee.k@sutt.co.th </td>
                            <td>Yupontee Khieokhum</td>
                            <td>Admin</td>
                            <td>182.232.187.19,<br> 127.0.0.1</td>
                            <td>Mobile : Apple</td>
                            <td>logged in website</td>
                            <td>3 March 2020</td> 
                            <td>11:25:44</td>
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
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
</body>

</html>
