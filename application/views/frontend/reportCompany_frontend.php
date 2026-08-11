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
        
        <!-- Course Carousel -->
        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators2" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators2" data-slide-to="2"></li>
          </ol>

          <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>

      <div class="container-fluid">
        <div class="row page-titles">
          <div class="col-5 align-self-center">
              <b><?php echo label('report_company'); ?></b>
          </div>
          <div class="col-7 align-self-right hidden-xs-down">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item"><?php echo label('report_general'); ?></li>
              <li class="breadcrumb-item active"><?php echo label('report_company'); ?></li>
            </ol>
          </div>
        </div>
  
        <div class="">
          <div class="">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-sm-12 card card-body">
                    <div class="table-responsive">
                      <table id="report_company_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th><?php echo label('com_name'); ?></th>
                                  <th><?php echo label('num_id'); ?></th>
                                  <th><?php echo label('num_admin'); ?></th>
                                  <th><?php echo label('num_instructor'); ?></th>
                                  <th><?php echo label('num_learner'); ?></th>
                                  <th><?php echo label('num_course'); ?></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td title="Isuzu Motors Asia (Thailand) Co., Ltd.">Isuzu Motors Asia (Thailand) Co., Ltd.</td>
                                  <td>1212</td>
                                  <td>2</td>
                                  <td>61</td>
                                  <td>1151</td>
                                  <td>421</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Technical Center of Asia Co., Ltd.">Isuzu Technical Center of Asia Co., Ltd.</td>
                                  <td>434</td>
                                  <td>2</td>
                                  <td>63</td>
                                  <td>371</td>
                                  <td>61</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Global CV Engineering Center Co., Ltd.">Isuzu Global CV Engineering Center Co., Ltd.</td>
                                  <td>254</td>
                                  <td>1</td>
                                  <td>66</td>
                                  <td>188</td>
                                  <td>63</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Motors Co., (Thailand) Ltd.">Isuzu Motors Co., (Thailand) Ltd.</td>
                                  <td>896</td>
                                  <td>2</td>
                                  <td>22</td>
                                  <td>874</td>
                                  <td>66</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Engine Manufacturing Co., (Thailand) Ltd.">Isuzu Engine Manufacturing Co., (Thailand) Ltd.</td>
                                  <td>236</td>
                                  <td>3</td>
                                  <td>33</td>
                                  <td>203</td>
                                  <td>22</td>
                              </tr>
                              <tr>
                                  <td title="Thai International Die Making Co., Ltd.">Thai International Die Making Co., Ltd.</td>
                                  <td>785</td>
                                  <td>4</td>
                                  <td>61</td>
                                  <td>721</td>
                                  <td>33</td>
                              </tr>
                              <tr>
                                  <td title="IT Forging (Thailand) Co., Ltd.">IT Forging (Thailand) Co., Ltd.</td>
                                  <td>421</td>
                                  <td>2</td>
                                  <td>59</td>
                                  <td>362</td>
                                  <td>61</td>
                              </tr>
                              <tr>
                                  <td title="Shonan Unitec (Thailand) Co., Ltd.">Shonan Unitec (Thailand) Co., Ltd.</td>
                                  <td>156</td>
                                  <td>1</td>
                                  <td>55</td>
                                  <td>101</td>
                                  <td>59</td>
                              </tr>
                              <tr>
                                  <td title="IJTT (Thailand) Co., Ltd.">IJTT (Thailand) Co., Ltd.</td>
                                  <td>135</td>
                                  <td>3</td>
                                  <td>39</td>
                                  <td>96</td>
                                  <td>55</td>
                              </tr>
                              <tr>
                                  <td title="Hitachi Chemical Automotive Products (Thailand) Co.,Ltd.">Hitachi Chemical Automotive Products (Thailand) Co.,Ltd.</td>
                                  <td>823</td>
                                  <td>2</td>
                                  <td>23</td>
                                  <td>800</td>
                                  <td>61</td>
                              </tr>
                              <tr>
                                  <td title="KDI Services & Technologies Co., Ltd.">KDI Services & Technologies Co., Ltd.</td>
                                  <td>621</td>
                                  <td>345</td>
                                  <td>123</td>
                                  <td>498</td>
                                  <td>63</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Logistics Asia (Thailand) Co.,Ltd.">Isuzu Logistics Asia (Thailand) Co.,Ltd.</td>
                                  <td>345</td>
                                  <td>3</td>
                                  <td>22</td>
                                  <td>323</td>
                                  <td>66</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Motors International Operations (Thailand) Co.,Ltd.">Isuzu Motors International Operations (Thailand) Co.,Ltd.</td>
                                  <td>124</td>
                                  <td>4</td>
                                  <td>36</td>
                                  <td>88</td>
                                  <td>22</td>
                              </tr>
                              <tr>
                                  <td title="ICL (Thailand) Co., Ltd.">ICL (Thailand) Co., Ltd.</td>
                                  <td>125</td>
                                  <td>6</td>
                                  <td>43</td>
                                  <td>82</td>
                                  <td>33</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Body Corporation (Thailand) Ltd.">Isuzu Body Corporation (Thailand) Ltd.</td>
                                  <td>124</td>
                                  <td>4</td>
                                  <td>19</td>
                                  <td>105</td>
                                  <td>61</td>
                              </tr>
                              <tr>
                                  <td title="Isuzu Techno (Thailand) Co., Ltd.">Isuzu Techno (Thailand) Co., Ltd.</td>
                                  <td>512</td>
                                  <td>3</td>
                                  <td>66</td>
                                  <td>446</td>
                                  <td>59</td>
                              </tr>
                              <tr>
                                  <td title="Kogei Intec (Thailand) Co., Ltd.">Kogei Intec (Thailand) Co., Ltd.</td>
                                  <td>234</td>
                                  <td>5</td>
                                  <td>64</td>
                                  <td>170</td>
                                  <td>55</td>
                              </tr>
                              <tr>
                                  <td title="Linex International (Thailand) Co., Ltd.">Linex International (Thailand) Co., Ltd.</td>
                                  <td>546</td>
                                  <td>4</td>
                                  <td>59</td>
                                  <td>487</td>
                                  <td>55</td>
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
    $('#report_company_table').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'print'
      ]
    });
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
