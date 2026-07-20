<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/css/privacy-policy.css?v=20260720-1" rel="stylesheet">
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
        <?php if (!empty($user)) { $this->load->view('frontend/inc/inc-sidemenu.php'); } ?>
        <div class="page-wrapper privacy-page">
          <div class="container-fluid privacy-container">
            <div class="privacy-breadcrumb-row">
              <ol class="breadcrumb" aria-label="Breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/home"><?php echo label('home'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo label('privacy_policy'); ?></li>
              </ol>
            </div>

            <header class="privacy-hero">
              <div class="privacy-icon" aria-hidden="true"><i class="mdi mdi-shield-outline"></i></div>
              <div>
                <span class="privacy-eyebrow">ISUZU E-LEARNING</span>
                <h1><?php echo label('privacy_policy'); ?></h1>
                <p><?php
                  if ($lang == "thai") {
                    echo "รายละเอียดการเก็บรวบรวม ใช้ และคุ้มครองข้อมูลส่วนบุคคลของผู้ใช้งาน";
                  } else if ($lang == "english") {
                    echo "How we collect, use, and protect your personal information";
                  } else {
                    echo "個人情報の収集、利用および保護について";
                  }
                ?></p>
              </div>
            </header>

            <main class="privacy-document" id="privacy-document">
              <div class="privacy-document-accent" aria-hidden="true"></div>
              <article class="privacy-content">
                <?php if($lang=="thai"){echo $foote[0]['da_privacy_policy_th'];}else if($lang=="english"){echo $foote[0]['da_privacy_policy_en'];}else{echo $foote[0]['da_privacy_policy_jp'];} ?>
              </article>
            </main>
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
