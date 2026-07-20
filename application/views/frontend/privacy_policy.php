<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
  </head>
  <body class="fix-header fix-sidebar">

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css  class="fix-header card-no-border fix-sidebar" -->
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
        <div class="page-wrapper">
          <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo label('privacy_policy'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/home"><?php echo label('home'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('privacy_policy'); ?></li>
                        </ol>
                    </div>
                </div>
                 <div class="row">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <?php if($lang=="thai"){echo $foote[0]['da_privacy_policy_th'];}else if($lang=="english"){echo $foote[0]['da_privacy_policy_en'];}else{echo $foote[0]['da_privacy_policy_jp'];} ?>
                    </div>
                  </div>
                </div>
          </div>

        </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    </div>
    
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();
    </script>
  </body>
</html>
