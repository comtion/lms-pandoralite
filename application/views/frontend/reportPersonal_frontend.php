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
              <b><?php echo label('report_personal'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('report_personal'); ?></li>
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
                      <div class="col-sm-12 col-lg-6">
                        <label><b style="color: #FF2D00">*</b><?php echo label('r_cos_status').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Company</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-lg-6">
                        <label><b style="color: #FF2D00">*</b><?php echo label('r_result').' : '; ?></label>
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
                        <label class="control-label text-right"><?php echo label('period').' : '; ?></label>
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
                            <th><?php echo label('r_course_name'); ?></th>
                            <th><?php echo label('r_cos_status'); ?></th>
                            <th><?php echo label('r_result'); ?></th>
                            <th><?php echo label('score_pretest'); ?></th>
                            <th><?php echo label('score_posttest'); ?></th>
                            <th><?php echo label('maxScore'); ?></th>
                            <th><?php echo label('date_passcourse'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>The Art of Communication</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>75</td>
                            <td>93</td>
                            <td>100</td>
                            <td>3 March 2020 15:02:50</td>
                          </tr>
                          <tr>
                            <td>Performance Management System</td>
                            <td>Opening</td>
                            <td>Not Start</td>
                            <td></td>
                            <td></td>
                            <td>100</td>
                            <td>3 March 2020 09:17:50</td>
                          </tr>
                          <tr>
                            <td>Managing Budgets and Finances</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>62</td>
                            <td>73</td>
                            <td>100</td>
                            <td>3 March 2020 11:25:44</td>
                          </tr>
                          <tr>
                            <td>An Introduction to Customer Relationship Management</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>81</td>
                            <td>96</td>
                            <td>100</td>
                            <td>3 March 2020 11:12:07</td>
                          </tr>
                          <tr>
                            <td>Becoming Management Material  </td>
                            <td>Opening</td>
                            <td>In progress</td>
                            <td>46</td>
                            <td></td>
                            <td>100</td>
                            <td>3 March 2020 14:22:07</td>
                          </tr>
                          <tr>
                            <td>Business Writing that Works</td>
                            <td>Opening</td>
                            <td>Completed</td>
                            <td>79</td>
                            <td>83</td>
                            <td>100</td>
                            <td>3 March 2020 14:27:07</td>
                          </tr>
                          <tr>
                            <td>Code of Conduct</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>65</td>
                            <td>78</td>
                            <td>100</td>
                            <td>3 March 2020 14:27:07</td>
                          </tr>
                          <tr>
                            <td>Compliance</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>84</td>
                            <td>100</td>
                            <td>100</td>
                            <td>3 March 2020 13:48:53</td>
                          </tr>
                          <tr>
                            <td>Settling Issues and Dilemmas</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>96</td>
                            <td>85</td>
                            <td>100</td>
                            <td>3 March 2020 14:27:07</td>
                          </tr>
                          <tr>
                            <td>Increasing Employee Productivity</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>43</td>
                            <td>92</td>
                            <td>100</td>
                            <td>3 March 2020 13:48:53</td>
                          </tr>
                          <tr>
                            <td>Creative Thinking and Innovation</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>36</td>
                            <td>84</td>
                            <td>100</td>
                            <td>3 March 2020 11:25:44</td>
                          </tr>
                          <tr>
                            <td>Core Negotiation Skills</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td>65</td>
                            <td>82</td>
                            <td>100</td>
                            <td>3 March 2020 15:02:50</td>
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
