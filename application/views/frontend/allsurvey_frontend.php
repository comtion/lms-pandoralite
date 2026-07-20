<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
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
        
        <!-- Course Carousel -->
        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators2" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators2" data-slide-to="2"></li>
          </ol>
          
          <div class="carousel-inner" role="listbox" style="height: 200px;">
            <div class="carousel-item active">
              <img class="carousel-course" src="<?php echo REAL_PATH; ?>/assets/images/bg.jpg" style="width: auto; height: 200px;" alt="First slide">
                <div class="carousel-caption d-md-block">
                <h1 class="container-fluid text-white"><?php echo label('survey'); ?></h1>
              </div>
            </div>
            <div class="carousel-item">
              <img class="carousel-course" src="<?php echo REAL_PATH; ?>/assets/images/bg.jpg" style="width: auto; height: 200px;" alt="Second slide">
              <div class="carousel-caption d-md-block">
                <h1 class="container-fluid text-white"><?php echo label('survey'); ?></h1>
              </div>
            </div>
            <div class="carousel-item">
              <img class="carousel-course" src="<?php echo REAL_PATH; ?>/assets/images/bg.jpg" style="width: auto; height: 200px;" alt="Third slide">
              <div class="carousel-caption d-md-block">
                <h1 class="container-fluid text-white"><?php echo label('survey'); ?></h1>
              </div>
            </div>
          </div>

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
              <b><?php echo label('survey'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('survey'); ?></li>
            </ol>
          </div>
        </div>
  
        <div class="">
          <div class="">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-sm-12 card card-body">
                    <div class="card-group pt-3">
                      <!-- CARD 1 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img8.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Quisque ut enim egestas, sodales massa sit amet, pulvinar sem.">Quisque ut enim egestas, sodales massa sit amet, pulvinar sem.</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p>
                          <!-- <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p> -->
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 2 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img1.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <!-- <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p> -->
                          <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p>
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 3 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img2.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p>
                          <!-- <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p> -->
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 4 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img3.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <!-- <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p> -->
                          <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p>
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                    
                    <hr class="p-3">

                    <div class="card-group">
                      <!-- CARD 5 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img4.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p>
                          <!-- <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p> -->
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 6 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img5.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <!-- <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p> -->
                          <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p>
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 7 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img6.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p>
                          <!-- <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p> -->
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- CARD 8 -->
                      <div class="card">
                        <img class="card-img-top img-responsive pointer" src="<?php echo REAL_PATH;?>/assets/images/mockup/img7.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" >
                        <div class="card-body">
                          <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/course/surveyDetail';" title="Card title">Card title</h4>
                          <p class="card-text mt-3"><?php echo label('sv_specific').' : '; ?> 
                            <br>1 January 2563 - 3 March 2563
                          </p>
                          <!-- <p class="card-text imat-completed-text"><?php echo label('status').' : '.label('done'); ?></p> -->
                          <p class="card-text imat-incompleted-text"><?php echo label('status').' : '.label('noProgress'); ?></p>
                          <div class="row" title="<?php echo label('speopled'); ?>">
                            <div class="col-12 align-self-center text-right">
                             <i class="mdi mdi-account-multiple-outline"></i> 36
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
        </div>
      </div>
    </div>
    </div>

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>
</body>

</html>
