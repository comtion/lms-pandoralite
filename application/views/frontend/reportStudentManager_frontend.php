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
              <b><?php echo label('report_student'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('report_student'); ?></li>
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
                        <label><b style="color: #FF2D00">*</b><?php echo label('r_course_name').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Course</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-lg-6">
                        <label><b style="color: #FF2D00">*</b><?php echo label('r_cos_status').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Status</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mt-3">                      
                      <div class="col-sm-12 col-lg-6">
                        <label><b style="color: #FF2D00">*</b><?php echo label('r_result').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Status</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                      <table id="report_student_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th><?php echo label('name'); ?></th>
                            <th><?php echo label('r_course_name'); ?></th>
                            <th><?php echo label('r_cos_status'); ?></th>
                            <th><?php echo label('r_result'); ?></th>
                            <th><?php echo label('points_from_manage'); ?></th>
                            <th><?php echo label('score_pretest'); ?></th>
                            <th><?php echo label('score_posttest'); ?></th>
                            <th><?php echo label('maxScore'); ?></th>
                            <!-- <th><?php echo label('date_passcourse'); ?></th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Bonus Soythongjaroen</td>
                            <td>The Art of Communication</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i></td>
                            <td>71</td>
                            <td>84</td>
                            <td>100</td>
                            <!-- <td>2563-02-15 11:25:44</td> -->
                          </tr>
                          <tr>
                            <td>Fonthip Changwichukarn</td>
                            <td>The Art of Communication</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i></td>
                            <td>62</td>
                            <td>93</td>
                            <td>100</td>
                            <!-- <td>2563-02-19 13:48:53</td> -->
                          </tr>
                          <tr>
                            <td>Jetsada Deesiripronchai</td>
                            <td>The Art of Communication</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i></td>
                            <td>56</td>
                            <td>75</td>
                            <td>100</td>
                            <!-- <td>2563-02-18 14:27:07</td> -->
                          </tr>
                          <tr>
                            <td>Kanjana Getpan</td>
                            <td>Beyond the Best</td>
                            <td>Opening</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>85</td>
                            <td>96</td>
                            <td>100</td>
                            <!-- <td>2563-02-16 13:48:53</td> -->
                          </tr>
                          <tr>
                            <td>Karn Norant</td>
                            <td>Beyond the Best</td>
                            <td>Opening</td>
                            <td>In Progress</td>
                            <td><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-warning"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>53</td>
                            <td></td>
                            <td>100</td>
                            <!-- <td>2563-02-15 14:27:07</td> -->
                          </tr>
                          <tr>
                            <td>Nitchakamon Chamrasnet</td>
                            <td>Beyond the Best</td>
                            <td>Opening</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>43</td>
                            <td>97</td>
                            <td>100</td>
                            <!-- <td>2563-02-16 14:27:07</td> -->
                          </tr>
                          <tr>
                            <td>Preechaporn Seelayong</td>
                            <td>Conflict Resolution - Dealing with Difficult People</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>56</td>
                            <td>75</td>
                            <td>100</td>
                            <!-- <td>2563-02-15 14:22:07</td> -->
                          </tr>
                          <tr>
                            <td>Premika Phutta</td>
                            <td>Conflict Resolution - Dealing with Difficult People</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>85</td>
                            <td>96</td>
                            <td>100</td>
                            <!-- <td>2563-02-17 11:12:07</td> -->
                          </tr>
                          <tr>
                            <td>Royya Japakiya</td>
                            <td>Conflict Resolution - Dealing with Difficult People</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>74</td>
                            <td>85</td>
                            <td>100</td>
                            <!-- <td>2563-02-15 11:25:44</td> -->
                          </tr>
                          <tr>
                            <td>Tanarak Petchrak</td>
                            <td>Compliance</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>43</td>
                            <td>68</td>
                            <td>100</td>
                            <!-- <td>2563-02-17 09:17:50</td> -->
                          </tr>
                          <tr>
                            <td>Yupontee Khieokhum</td>
                            <td>Compliance</td>
                            <td>Closed</td>
                            <td>Completed</td>
                            <td><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i><i class="mdi mdi-star text-default"></i></td>
                            <td>49</td>
                            <td>53</td>
                            <td>100</td>
                            <!-- <td>2563-02-18 15:02:50</td> -->
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

    $('#report_student_table').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'print'
      ]
    });
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
