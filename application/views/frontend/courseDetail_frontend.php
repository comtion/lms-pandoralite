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
                      <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/course/available"><?php echo label('allcos'); ?></a></li>
                      <li class="breadcrumb-item active">COURSE_NAME</li>
                    </ol>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12 col-lg-4 mb-0 card card-body">
                    <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/assets/images/mockup/400x300.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
                  </div>

                  <div class="col-md-12 col-lg-8 mb-0 card card-body">
                    <h4 class="text-truncate">COURSE_NAME</h4>
                    <div class="d-block position-relative">

                        <div class="row">
                          <div class="col-lg-8 col-sm-12 mt-3">
                            <!-- FOR DESKTOP -->
                            <small class="text-muted text-truncate position-absolute col-md-12 col-lg-8 p-0 hidden-xs-down" style="bottom: 0;"><?php echo label('createBy').' : '; ?>COMPANY_OF_CREATOR</small>

                            <!-- FOR MOBILE -->
                            <small class="text-muted text-truncate hidden-sm-up"><?php echo label('createBy').' : '; ?>COMPANY_OF_CREATOR</small>
                          </div>
                          <div class="col-lg-4 col-sm-12">
                            <a type="button" title="<?php echo label('lesson_file'); ?>" href="#" class="btn btn-block waves-effect waves-light btn-secondary float-right dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-file-document"></i><?php echo ' '.label('lesson_file'); ?></a>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                            </div>
                          </div>                        
                        </div>
                        
                    </div>
                    <hr>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                  </div>
                </div>

            </div>
            
            <!-- Collapse Lesson -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_1" aria-expanded="false" aria-controls="collapseExample_1">
                <i class="fa fas fa-check mr-2"></i><?php echo 'COURSE_LESSON_NAME' ?>
                <i class="fa fa-chevron-right float-right"></i>
                <i class="fa fa-chevron-down float-right"></i>
              </a>
              <div class="collapse" id="collapseExample_1">
                <!-- MOBILE NAV -->
                <div class="hidden-sm-up">
                  <div class="list-group">
                    <a href="#lesson_1" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick active">Lesson 1 Video + Content</a>
                    <a href="#lesson_2" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">Lesson 2 Content</a>
                    <a href="#lesson_3" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">Lesson 3 Scorm</a>
                  </div>
                </div>

                <div class="card card-body">
                    <div class="vtabs customvtab row">
                      <!-- DESKTOP NAV -->
                      <ul class="nav nav-tabs tabs-vertical vtabs-lesson hidden-xs-down" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active show" data-toggle="tab" href="#lesson_1" role="tab" aria-selected="true">
                            <span>Lesson 1 Video + Content</span> 
                          </a> 
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#lesson_2" role="tab" aria-selected="false">
                            <span>Lesson 2 Content</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#lesson_3" role="tab" aria-selected="false">
                            <span>Lesson 3 Scorm</span>
                          </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content pt-0 d-block">
                        <!-- Lesson 1 Video + Content -->
                        <div class="tab-pane active show" id="lesson_1" role="tabpanel">                          
                          <div class="text-center">
                            <iframe style="width: 80%; height: auto;" src="https://www.youtube.com/embed/5RC9K6CcPGI" allowfullscreen></iframe>
                          </div>
                          <hr>
                          <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                            <br>Mauris id metus id est gravida ultrices. In et sapien ex. 
                            <br>Nunc eget erat quis lacus dignissim commodo. Cras et fermentum ligula. 
                            <br>Sed in fermentum arcu. Fusce euismod arcu eu leo semper, quis euismod leo laoreet. 
                          </p>
                            
                          <p>
                            Donec vulputate tempus egestas. Nullam auctor lacus eget nulla rhoncus auctor. 
                            <br>Donec ut ligula consectetur, euismod turpis nec, molestie augue. 
                            <br>Nunc rhoncus magna at nulla finibus, sed laoreet dui scelerisque. 
                          </p>
                          
                          <hr>
                          <h4><?php echo ' '.label('lesson_file'); ?></h4>
                          <a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>
                          <br><a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>
                          <br><a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>

                        </div>

                        <!-- Lesson 2 Content -->
                        <div class="tab-pane" id="lesson_2" role="tabpanel">
                          <h3>Coronavirus disease (COVID-19) outbreak</h3>
                          <h4>Coronavirus patients can have similar "viral load" whether or not they show symptoms</h4>
                           <p>On this website you can find information and guidance from WHO regarding the current outbreak of coronavirus disease (COVID-19) that was first reported from Wuhan, China, on 31 December 2019. Please visit this page for daily updates.

                            WHO is working closely with global experts, governments and partners to rapidly expand scientific knowledge on this new virus, to track the spread and virulence of the virus, and to provide advice to countries and individuals on measures to protect health and prevent the spread of this outbreak.

                          </p>

                            <img class="img-fluid" src="https://bangkokbiznews.sgp1.cdn.digitaloceanspaces.com/image/kt/media/image/fileupload1/source/158150274950.jpg?1581563707502" alt="">

                            <hr>
                            <h4><?php echo ' '.label('lesson_file'); ?></h4>
                            <a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>
                            <br><a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>
                            <br><a href="#"><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i><?php echo ' '.'lesson 1.pdf' ?></a>
                        </div>

                        <!-- Lesson 3 Scorm -->
                        <div class="tab-pane" id="lesson_3" role="tabpanel">
                          <h3>Lesson 3 Scorm</h3>
                          <h4>you can use it with the small code</h4>
                          <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>

            <!-- Collapse Quiz -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_2" aria-expanded="false" aria-controls="collapseExample_2">
                <i class="fa fas fa-check mr-2"></i><?php echo 'PRE_TEST_NAME / POST_TEST_NAME' ?>
                <i class="fa fa-chevron-right float-right"></i>
                <i class="fa fa-chevron-down float-right"></i>
              </a>
              <div class="collapse" id="collapseExample_2">
                <!-- MOBILE NAV -->
                <div class="hidden-sm-up">
                  <div class="list-group">
                    <a href="#quiz_detail" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick active"><?php echo label('summary'); ?></a>
                    <a href="#quiz_1" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">No. 1</a>
                    <a href="#quiz_2" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">No. 2</a>
                    <a href="#quiz_3" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick bg-checked">No. 3</a>
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
                            <span>No. 1</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_2" role="tab" aria-selected="false">
                            <span>No. 2</span>
                          </a>
                        </li>
                        <li class="nav-item bg-checked">
                          <a class="nav-link" data-toggle="tab" href="#quiz_3" role="tab" aria-selected="false">
                            <span>No. 3</span>
                          </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content pt-0 d-block">
                        <!-- Quiz Detail -->
                        <div class="tab-pane active show" id="quiz_detail" role="tabpanel">
                          <h4><?php echo label('summary'); ?></h4>
                          <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                            <br>Mauris id metus id est gravida ultrices. In et sapien ex. 
                            <br>Nunc eget erat quis lacus dignissim commodo. Cras et fermentum ligula. 
                            <br>Sed in fermentum arcu. Fusce euismod arcu eu leo semper, quis euismod leo laoreet. 
                          </p>
                            
                          <p>
                            Donec vulputate tempus egestas. Nullam auctor lacus eget nulla rhoncus auctor. 
                            <br>Donec ut ligula consectetur, euismod turpis nec, molestie augue. 
                            <br>Nunc rhoncus magna at nulla finibus, sed laoreet dui scelerisque. 
                          </p>
                          <hr>
                          <button type="button" class="btn btn-outline-secondary float-right"><?php echo label('qiz_start'); ?></button>
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

                      </div>
                    </div>
                </div>
              </div>
            </div>

            <!-- Open Survey Button -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="modal" data-target="#surveyModal" >
                <i class="fa fas fa-check mr-2"></i><?php echo 'SURVEY_NAME'; ?>
              </a>
            </div>



        </div>
    </div>

  <!-- SELECT LANGUAGE MODAL -->
  <div id="select_lang_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel"><?php echo label('Chooselang'); ?></h4>
        </div>
        <div class="modal-body row">
          <div class="col-lg-6 pt-3">
            <img class="card-img-top img-responsive" style="max-width: 300px;" src="<?php echo REAL_PATH;?>/assets/images/mockup/400x300.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
          </div>
          <div class="col-lg-6 pt-3">
            <h4 class="text-truncate">COURSE_NAME</h4>
            <p class="card-text"><?php echo label('period').' : '; ?> 
              <br>1 มกราคม 2563 - 3 มีนาคม 2563
            </p>
            <p class="mb-0"><?php echo label('Chooselang').' : '; ?></p>

            <select class="selectpicker">
             <option data-icon="flag-icon flag-icon-th"><?php echo label('thailand'); ?></option>
             <option data-icon="flag-icon flag-icon-us"><?php echo label('english'); ?></option>
             <option data-icon="flag-icon flag-icon-jp"><?php echo label('japan'); ?></option>
            </select>

          </div>

        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" title="<?php echo label('go_to_course'); ?>" class="btn waves-effect waves-light btn-outline-danger btn-danger-hover float-right"><i class="mdi mdi-file-document-box"></i><?php echo ' '.label('go_to_course'); ?></a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.SELECT LANGUAGE MODAL -->

  <!-- SURVEY MODAL -->
  <div id="surveyModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel"><?php echo label('survey').' : '.'SURVEY_NAME' ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="align-middle"><p class="text-left"><?php echo label('question'); ?></p></th>
                                <th><p class="text-center"><?php echo label('choice_5'); ?><br>5</p></th>
                                <th><p class="text-center"><?php echo label('choice_4'); ?><br>4</p></th>
                                <th><p class="text-center"><?php echo label('choice_3'); ?><br>3</p></th>
                                <th><p class="text-center"><?php echo label('choice_2'); ?><br>2</p></th>
                                <th><p class="text-center"><?php echo label('choice_1'); ?><br>1</p></th>
                                <th class="align-middle"><p class="text-left"><?php echo label('Suggestion'); ?></p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td><h6 class="m-auto">HEADER_1</h6></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_1</td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_1" class="with-gap radio-col-red"><label for="radio_1"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_2" class="with-gap radio-col-red"><label for="radio_2"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_3" class="with-gap radio-col-red"><label for="radio_3"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_4" class="with-gap radio-col-red"><label for="radio_4"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_5" class="with-gap radio-col-red"><label for="radio_5"></label></td>
                                <td><textarea class="form-control" rows="3" style="min-width: 200px;"></textarea></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_2</td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_6" class="with-gap radio-col-red"><label for="radio_6"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_7" class="with-gap radio-col-red"><label for="radio_7"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_8" class="with-gap radio-col-red"><label for="radio_8"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_9" class="with-gap radio-col-red"><label for="radio_9"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_10" class="with-gap radio-col-red"><label for="radio_10"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_3</td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_11" class="with-gap radio-col-red"><label for="radio_11"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_12" class="with-gap radio-col-red"><label for="radio_12"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_13" class="with-gap radio-col-red"><label for="radio_13"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_14" class="with-gap radio-col-red"><label for="radio_14"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_15" class="with-gap radio-col-red"><label for="radio_15"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                              <td><h6 class="m-auto">HEADER_2</h6></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_4</td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_16" class="with-gap radio-col-red"><label for="radio_16"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_17" class="with-gap radio-col-red"><label for="radio_17"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_18" class="with-gap radio-col-red"><label for="radio_18"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_19" class="with-gap radio-col-red"><label for="radio_19"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_20" class="with-gap radio-col-red"><label for="radio_20"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_5</td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_21" class="with-gap radio-col-red"><label for="radio_21"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_22" class="with-gap radio-col-red"><label for="radio_22"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_23" class="with-gap radio-col-red"><label for="radio_23"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_24" class="with-gap radio-col-red"><label for="radio_24"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_25" class="with-gap radio-col-red"><label for="radio_25"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php echo label('Suggestion_another'); ?>
                    <textarea class="form-control" rows="5"></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn waves-effect waves-light btn-outline-success" data-dismiss="modal"><?php echo label('contasend'); ?></button>
                  <button type="button" class="btn waves-effect waves-light btn-outline-danger" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.SURVEY MODAL -->

<!-- HINT MODAL -->
<div id="hint_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><?php echo label('hint'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <img class="card-img-top img-responsive mx-auto d-block" style="max-width: 300px;" src="<?php echo REAL_PATH;?>/assets/images/mockup/img4.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
        <hr>
        <h4>Overflowing text to show scroll behavior</h4>
        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn btn-outline-danger waves-effect" data-dismiss="modal"><?php echo label('m_ok'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.HINT MODAL -->

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
      $(window).on('load',function(){
          $('#select_lang_modal').modal('show');
      });

      $('#select_lang_modal').modal({backdrop: 'static', keyboard: false});
    </script>
</body>

</html>
