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
              <b><?php echo label('report_course'); ?></b>
          </div>
          <div class="col-7 align-self-right hidden-xs-down">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('report_course'); ?></li>
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
                        <label><b style="color: #FF2D00">*</b><?php echo label('coursegroup').' : '; ?></label>
                        <select class="custom-select">
                          <option selected="">All Course Group</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
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
                                  <th><?php echo label('ceCname'); ?></th>
                                  <th><?php echo label('com_name'); ?></th>
                                  <th><?php echo label('r_average_score'); ?></th>
                                  <th><?php echo label('numSeat'); ?></th>
                                  <th><?php echo label('d_c'); ?></th>
                                  <th><?php echo label('inProgress'); ?></th>
                                  <th><?php echo label('not_start'); ?></th>
                                  <th><?php echo label('detail'); ?></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td title="The Art of Communication">The Art of Communication</td>
                                  <td>IGCE</td>
                                  <td>87</td>
                                  <td>1000</td>
                                  <td>731</td>
                                  <td>61</td>
                                  <td>68</td>
                                  <td class="text-center">
                                    <button type="button" data-toggle="modal" data-target="#" class="btn btn-success btn-xs view_detail" title="<?php echo label('r_viewDetail'); ?>"><i class="mdi mdi-magnify"></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td title="Conflict Resolution - Dealing with Difficult People">Conflict Resolution - Dealing with Difficult People</td>
                                  <td>SUTT</td>
                                  <td>92</td>
                                  <td>500</td>
                                  <td>371</td>
                                  <td>23</td>
                                  <td>106</td>
                                  <td class="text-center">
                                    <button type="button" data-toggle="modal" data-target="#detail_modal" class="btn btn-success btn-xs view_detail" title="<?php echo label('r_viewDetail'); ?>"><i class="mdi mdi-magnify"></i></button>
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

 <!-- sample modal content -->
 <div id="detail_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="myLargeModalLabel"><?php echo label('report_course'); ?></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <div class="modal-body">
                 <div class="table-responsive">
                      <table id="modal_course_detail_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th><?php echo label('p_email'); ?></th>
                                  <th><?php echo label('name'); ?></th>
                                  <th><?php echo label('com_name'); ?></th>
                                  <th><?php echo label('m_department'); ?></th>
                                  <th><?php echo label('r_result'); ?></th>
                                  <th><?php echo label('score_pretest'); ?></th>
                                  <th><?php echo label('score_posttest'); ?></th>
                                  <th><?php echo label('date_passcourse'); ?></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>jetsada.d@sutt.co.th</td>
                                  <td>Jetsada Deesiripronchai</td>
                                  <td>SUTT</td>
                                  <td>Information Technology</td>
                                  <td>Completed</td>
                                  <td>56</td>
                                  <td>84</td>
                                  <td>3 March 2020 14:27:07</td>
                              </tr>
                              <tr>
                                  <td>yupontee.k@sutt.co.th</td>
                                  <td>Yupontee Khieokhum</td>
                                  <td>SUTT</td>
                                  <td>Information Technology</td>
                                  <td>Completed</td>
                                  <td>58</td>
                                  <td>86</td>
                                  <td>3 March 2020 15:02:50</td>
                              </tr>
                              <tr>
                                  <td>premika.p@sutt.co.th</td>
                                  <td>Premika Phutta</td>
                                  <td>SUTT</td>
                                  <td>Information Technology</td>
                                  <td>Completed</td>
                                  <td>73</td>
                                  <td>93</td>
                                  <td>3 March 2020 11:12:07</td>
                              </tr>
                              <tr>
                                  <td>tanarak.p@sutt.co.th</td>
                                  <td>Tanarak Petchrak</td>
                                  <td>SUTT</td>
                                  <td>Senior Business Development Executive</td>
                                  <td>Completed</td>
                                  <td>82</td>
                                  <td>88</td>
                                  <td>3 March 2020 09:17:50</td>
                              </tr>
                              <tr>
                                  <td>fonthip.c@sutt.co.th</td>
                                  <td>Fonthip Changwichukarn</td>
                                  <td>SUTT</td>
                                  <td>Senior Business Development Executive</td>
                                  <td>Completed</td>
                                  <td>42</td>
                                  <td>76</td>
                                  <td>3 March 2020 13:48:53</td>
                              </tr>
                              <tr>
                                  <td>nitchakamon.c@sutt.co.th</td>
                                  <td>Nitchakamon Chamrasnet</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>Completed</td>
                                  <td>57</td>
                                  <td>78</td>
                                  <td>3 March 2020 14:27:07</td>
                              </tr>
                              <tr>
                                  <td>kanjana.g@sutt.co.th</td>
                                  <td>Kanjana Getpan</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>In progress</td>
                                  <td>46</td>
                                  <td>79</td>
                                  <td>3 March 2020 13:48:53</td>
                              </tr>
                              <tr>
                                  <td>Royya.j@sutt.co.th</td>
                                  <td>Royya Japakiya</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>In progress</td>
                                  <td>63</td>
                                  <td>95</td>
                                  <td>3 March 2020 11:25:44</td>
                              </tr>
                              <tr>
                                  <td>karn.n@sutt.co.th</td>
                                  <td>Karn Norant</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>Not Start</td>
                                  <td>68</td>
                                  <td>100</td>
                                  <td>3 March 2020 14:27:07</td>
                              </tr>
                              <tr>
                                  <td>bonus.s@sutt.co.th</td>
                                  <td>Bonus Soythongjaroen</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>Not Start</td>
                                  <td>64</td>
                                  <td>88</td>
                                  <td>3 March 2020 11:25:44</td>
                              </tr>
                              <tr>
                                  <td>preechaporn.s@sutt.co.th</td>
                                  <td>Preechaporn Seelayong</td>
                                  <td>SUTT</td>
                                  <td>Graphic Design</td>
                                  <td>Not Start</td>
                                  <td>59</td>
                                  <td>79</td>
                                  <td>3 March 2020 14:22:07</td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
             </div>
         </div>
         <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
 </div>
 <!-- /.modal -->
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
