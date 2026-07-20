<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
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
                <div class="row col-12 page-titles">
                  <div class="col-md-5 align-self-center"></div>
                  <div class="col-md-7 align-self-right">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/available"><?php echo label('dashboard'); ?></a></li>
                      <li class="breadcrumb-item active">SURVEY_NAME</li>
                    </ol>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12 mb-0 pb-0 card card-body">
                    
                  <div class="card-title">
                    <div class="row">                                
                      <div class="col-md-12">
                        <h4 class="card-title"><span class="lstick"></span>SURVEY_NAME</h4>
                      </div>

                    </div>
                    <div class="d-block position-relative">
                        <small class="text-muted text-truncate" style="bottom: 0;"><?php echo label('createBy').' : '; ?>NAME_OF_CREATOR</small>
                    </div>
                    <hr class="mt-0">
                  </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12 col-lg-4 mb-0 card card-body">
                    <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/assets/images/mockup/img6.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
                  </div>

                  <div class="col-md-12 col-lg-8 mb-0 card card-body">
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                  </div>
                </div>

            </div>

            <!-- Collapse Survey -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_2" aria-expanded="true" aria-controls="collapseExample_2">
                <i class="fa fas fa-check mr-2"></i><?php echo 'SURVEY_NAME' ?>
                <i class="fa fa-chevron-right float-right"></i>
                <i class="fa fa-chevron-down float-right"></i>
              </a>
              <div class="collapse show" id="collapseExample_2">
                <!-- MOBILE NAV -->
                <div class="hidden-sm-up">
                  <div class="list-group">
                    <a href="#quiz_detail" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick active"><?php echo label('summary'); ?></a>
                    <a href="#quiz_1" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">ข้อที่ 1</a>
                    <a href="#quiz_2" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">ข้อที่ 2</a>
                    <a href="#quiz_3" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">ข้อที่ 3</a>
                    <a href="#quiz_4" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick bg-checked">ข้อที่ 4</a>
                  </div>
                </div>

                <div class="card card-body">
                    <div class="vtabs customvtab row">
                      <!-- DESKTOP NAV -->
                      <ul class="nav nav-tabs vtabs-quiz tabs-vertical hidden-xs-down" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active show" data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
                            <span><?php echo label('summary'); ?></span> 
                          </a> 
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_1" role="tab" aria-selected="false">
                            <span>ข้อที่ 1</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_2" role="tab" aria-selected="false">
                            <span>ข้อที่ 2</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_3" role="tab" aria-selected="false">
                            <span>ข้อที่ 3</span>
                          </a>
                        </li>
                        <li class="nav-item bg-checked">
                          <a class="nav-link" data-toggle="tab" href="#quiz_4" role="tab" aria-selected="false">
                            <span>ข้อที่ 4</span>
                          </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content pt-0 d-block">
                        <!-- Quiz Detail -->
                        <div class="tab-pane active show" id="quiz_detail" role="tabpanel">
                          <h4><?php echo label('summary'); ?></h4>
                          <p>
                            แค่ได้เห็นเธอ ตอนอยู่ด้วยกันกับเขา 
                            <br>ฉันก็รู้ดีว่าเรื่องเรา มันคงไม่มีหวัง 
                            <br>แต่เพราะรักเธอ ก็เลยยังไม่ไปไหน 
                            <br>ทำได้แค่มองเรื่อยไป และบอกตัวเองไว้ 
                          </p>
                            
                          <p>
                            *ถ้าเธอมีความสุขกับเขาคนนั้น ฉันก็ยินดีแม้ความสุขนั้นไม่มีฉัน 
                            <br>จะรักเธอแค่ไหน ก็ต้องยอมเข้าใจ และยอมรับความจริงให้ไหว *
                          </p>
                          <hr>
                          <button type="button" class="btn btn-outline-secondary float-right"><?php echo label('questionnaireStart'); ?></button>
                        </div>

                        <!-- Textarea -->
                        <div class="tab-pane" id="quiz_1" role="tabpanel">
                          <h3>1. textarea</h3>
                          <textarea class="form-control" rows="5"></textarea>
                          <hr>
                          <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10 text-right">
                              <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                              <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                          </div>
                        </div>

                        <!-- Two Choice -->
                        <div class="tab-pane" id="quiz_2" role="tabpanel">
                          <h3>2. two choice</h3>
                            <input name="tow_choice_group" type="radio" id="tow_choice_1" class="with-gap radio-col-red">
                            <label for="tow_choice_1">Red</label>
                            <br>
                            <input name="tow_choice_group" type="radio" id="tow_choice_2" class="with-gap radio-col-red">
                            <label for="tow_choice_2">Pink</label>
                            <hr>
                            <div class="row">
                              <div class="col-2">                                
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                              </div>
                              <div class="col-10 text-right">
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                              </div>
                            </div>

                        </div>

                        <!-- Multi Choice -->
                        <div class="tab-pane" id="quiz_3" role="tabpanel">
                          <h3>3. multi choice (limit 5)</h3>
                            <input name="multi_choice_group" type="radio" id="multi_choice_1" class="with-gap radio-col-red">
                            <label for="multi_choice_1">Choice 1</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_2" class="with-gap radio-col-red">
                            <label for="multi_choice_2">Choice 2</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_3" class="with-gap radio-col-red">
                            <label for="multi_choice_3">Choice 3</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_4" class="with-gap radio-col-red">
                            <label for="multi_choice_4">Choice 4</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_5" class="with-gap radio-col-red">
                            <label for="multi_choice_5">Choice 5</label>
                            <hr>
                            <div class="row">
                              <div class="col-2">                                
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                              </div>
                              <div class="col-10 text-right">
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                              </div>
                            </div>

                        </div>
                        
                        <!-- Scale Choice -->
                        <div class="tab-pane" id="quiz_4" role="tabpanel">
                          <h3>4. scale</h3>
                          <div class="text-center">
                            <?php echo label('smin'); ?>
                            <input name="scale_choice_group" type="radio" id="scale_choice_1" class="with-gap radio-col-red">
                            <label for="scale_choice_1">1</label>

                            <input name="scale_choice_group" type="radio" id="scale_choice_2" class="with-gap radio-col-red">
                            <label for="scale_choice_2">2</label>

                            <input name="scale_choice_group" type="radio" id="scale_choice_3" class="with-gap radio-col-red">
                            <label for="scale_choice_3">3</label>

                            <input name="scale_choice_group" type="radio" id="scale_choice_4" class="with-gap radio-col-red">
                            <label for="scale_choice_4">4</label>

                            <input name="scale_choice_group" type="radio" id="scale_choice_5" class="with-gap radio-col-red">
                            <label for="scale_choice_5">5</label>
                            <?php echo label('smax'); ?>                           
                          </div>
                          <hr>
                          <div class="row">
                              <div class="col-2">                                
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                              </div>
                              <div class="col-10 text-right">
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                <button type="button" class="btn btn-outline-secondary"><?php echo label('preSend'); ?></button>
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

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>
</body>

</html>
