<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
  </head>
  <body class="fix-header fix-sidebar card-no-border">

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
        <?php if (!empty($emp_c)){ $this->load->view('frontend/inc/inc-sidemenu.php'); } ?>
        <div class="page-wrapper">
          <div class="container-fluid">
                <!-- <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo $title; ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo $title_main; ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo $title; ?></li>
                        </ol>
                    </div>
                </div>   -->
                
                 <div class="row">
                  <div class="col-md-12 card">
                    <div class="card-body">
                <?php 
                      foreach ($faq as $key) {
                        if($key['lang']==$lang){ ?>
                          <div class="card-body">
                                <h4 class="card-title"><?php echo $key['title']; ?></h4>
                                <!-- Accordian-part -->
                                <div id="accordian-part">
                                    <div id="accordian-3">

                                      <?php  $num = 1;
                                            foreach ($faq_detail as $key_detail) { 
                                                if($key['id']==$key_detail['tid']){ ?>

                                        <div class="card m-b-0 border-0">
                                            <div class="card-header p-l-0">
                                                <h5 class="mb-0" style="padding-left: 10px">
                                                    <a href="#" class="link p-10" id="headingOne" data-toggle="collapse" data-target="#collapse<?php echo $key['id'].$num; ?>" aria-expanded="true" aria-controls="collapse<?php echo $key['id']; ?>">
                                                        Q<?php echo $num; ?>. <?php echo $key_detail['question']; ?>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapse<?php echo$key['id'].$num; ?>" class="collapse show" aria-labelledby="headingOne" data-parent="#accordian-3">
                                                <div class="card-body p-l-0" style="padding: 20px">
                                                    <?php echo $key_detail['answer']; ?>
                                                </div>
                                            </div>
                                        </div>
                                      <?php $num++;     }
                                              } ?>

                                    </div>
                                </div>
                                <!-- End accordian-part -->
                            </div>
                 <?php  }
                      } ?>
                        </div>
                    </div>
                  </div>
                </div>
          </div>

        </div>
    </div>
    
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();
    <?php if(countArray($faq_detail)>0){ ?>
            $('.collapse').collapse('hide');
            $('#collapse1').collapse('show');
    <?php } ?>
    </script>
  </body>
</html>
