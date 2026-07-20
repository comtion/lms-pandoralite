<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); 

    $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
    $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>

    <!-- chartist CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/css-chart/css-chart.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/easy-pie-chart.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css">
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/dashboard.css">


    <link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/cdn/roundslider.min.css">

    <style type="text/css">
      .dt-head-center {text-align: center;}
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">

                    <div class="col-lg-4 ">
                      <div class="card">
                        <div class="card-body little-profile text-center pb-0">
                          <div class="pro-img m-t-20">
                                  <?php if(isset($profile['img_profile'])&&$profile['img_profile']!=""){ ?>
                                    <img src="<?php echo media_url('uploads/profile/'.$profile['img_profile'], 'uploads/profile/default_profile.jpg'); ?>"/>
                                  <?php }else{ ?>
                                    <img src="<?php echo media_url('uploads/profile/default_profile.jpg'); ?>"/>
                                  <?php } ?></div>
                          <h3 class="m-b-0" style="font-family: 'Prompt', sans-serif;">
                            <?php 
                                        if($lang=="thai"){ echo $profile['fullname_th']; }
                                        else{ echo $profile['fullname_en']; }
                            ?>
                          </h3>
                          <h6 class="text-muted" style="font-family: 'Prompt', sans-serif;">
                            <!-- <?php 
                                  if($lang=="thai"){ $ugname = $profile['ug_name_th']; }
                                  else{ $ugname = $profile['ug_name_en']; } 
                                  echo $ugname; 
                            ?> --> Instructor
                          </h6>
                        </div>

                        <?php if($user['Is_admin']=="0"){ ?>
                        <!-- USER BUTTON -->
                        <div class="card-body text-center button-group">
                          <a href="<?php echo REAL_PATH;?>/dashboard/profile/setting" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-account-edit"></i> <?php echo label('edit_profile'); ?></a>
                          <a href="<?php echo REAL_PATH;?>/course/available" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-magnify"></i> <?php echo label('view_allcourse'); ?></a>
                          <a href="<?php echo REAL_PATH;?>/dashboard/profile/certificate" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-certificate"></i> <?php echo label('view_cert'); ?></a>
                          <a href="<?php echo REAL_PATH;?>/course/nonenroll" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-close-circle"></i> <?php echo label('course_not_register'); ?></a>
                          <a href="<?php echo REAL_PATH;?>/report/loadreport_personal" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-format-list-bulleted"></i> <?php echo label('show_myscore'); ?></a>
                        </div>
                        <?php }else{ ?>

                        <div class="card-body text-center button-group" id="show_admin_dashboard">
                          <div class="row">
                            <div class="col-6" style="padding-right: 2.5px;">
                              <!-- edit profile Button -->
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/setting" class="btn btn-block waves-effect waves-light btn-secondary" title="<?php echo label('edit_profile'); ?>"><i class="mdi mdi-account-edit"></i> <?php echo label('edit_profile'); ?>
                              </a>
                            </div>
                          
                            <div class="col-6" style="padding-left: 2.5px;">   

                              <!-- view cert Button -->
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/certificate" class="btn btn-block waves-effect waves-light btn-secondary"><i class="mdi mdi-certificate"></i> <?php echo label('view_cert'); ?></a>

                              <!-- view allcourse Button -->
                            </div>  
                            <div class="col-12">
                              <a href="<?php echo REAL_PATH;?>/course/available" class="btn btn-block waves-effect waves-light btn-secondary"><i class="mdi mdi-magnify"></i> <?php echo label('view_allcourse'); ?></a>  
                            </div>  
                                                    
                          </div>

                          <!-- not register course button -->
                          <!-- <a href="<?php echo REAL_PATH;?>/course/nonenroll" class="btn btn-block waves-effect waves-light btn-secondary mt-0"><i class="mdi mdi-close-circle"></i> <?php echo label('course_not_register'); ?></a> -->

                          </div>

                        <?php } ?>
                      </div>
                      <?php if(in_array('4', $arr_role_fd)){ ?>

                        <?php } ?>
                    </div>

                    <div class="col-lg-8">
                      <?php if(in_array('1', $arr_role_fd)){ ?>
                        <div class="card">
                            <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                              <ol class="carousel-indicators">
                                <?php if(isset($pic)&&countArray($pic)>0){
                                        if($pic != null&&$page=='dashboard'){?>
                                            <?php $count_num = 0;$n=1;foreach ($pic as $row) {
                                              if($n==1){ ?>
                                                <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>" class="active"></li>
                                              <?php }else{?>
                                                <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>"></li>
                                            <?php }$n++;$count_num++;}?>
                                  <?php }
                                      } ?>
                              </ol>
                              <div class="carousel-inner" role="listbox">

                                <?php if(isset($pic)&&countArray($pic)>0){
                                  if($pic != null&&$page=='dashboard'){?>
                                    <?php $n=1;foreach ($pic as $row) {
                                      if($n==1){ ?>
                                      <div class="carousel-item active"> <img class="img-responsive" width="100%" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
                                        <!--<div class="carousel-caption d-none d-md-block">
                                          <h3 class="text-white">First title goes here</h3>
                                          <p>this is the subcontent you can use this</p>
                                        </div>-->
                                      </div>
                                    <?php }else{?>
                                      <div class="carousel-item"> <img class="img-responsive" width="100%" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
                                        <!--<div class="carousel-caption d-none d-md-block">
                                          <h3 class="text-white">Second title goes here</h3>
                                          <p>this is the subcontent you can use this</p>
                                        </div>-->
                                      </div>
                                    <?php }$n++;}?>
                                  <?php }
                                      } ?>
                              </div>
                              <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> 
                            </div>
                          </div>
                        <?php } ?>

                        <?php if(in_array('5', $arr_role_fd)){ ?>

                        <?php } ?>
                    </div>


                    <?php if(in_array('4', $arr_role_fd)){ ?>
                    <!-- approve admin learner -->
                    <div class="col-md-12" id="survey_admin_learner">
                      <div class="card card-body">
                        <h4 class="card-title"><span class="lstick"></span><?php echo label('questionnaire'); ?></h4>
                        <table id="survey_table" class="display table table-hover" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th><b><?php echo label('com_name'); ?></b></th>
                              <th style="width: 250px;"><b><?php echo label('survey_close_date'); ?></b></th>
                              <th><b></b></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td title="Donec sit amet magna sit amet mauris convallis tempor eu lobortis lacus.">Donec sit amet magna sit amet mauris convallis tempor eu lobortis lacus.</td>
                              <td>3 March 2563</td>
                              <td>
                                  <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_survey'); ?>">
                                    <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                  </a>
                                </td>  
                            </tr>
                            <tr>
                              <td title="Mauris in massa tempor, blandit velit ut, luctus lorem.">Mauris in massa tempor, blandit velit ut, luctus lorem.</td>
                              <td>3 March 2563</td>
                              <td>
                                  <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_survey'); ?>">
                                    <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                  </a>
                                </td>  
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="col-md-12" id="course_admin_learner">
                       <div class="card card-body">
                        <h4 class="card-title"><span class="lstick"></span><?php echo label('coursedata'); ?></h4>
                        <div class="row">
                          <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <input data-plugin="knob" data-width="150" data-height="150" data-fgColor="#ec2029" value="25" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".2" /><small class="knob-text"><?php echo label('courses_total'); ?></small>
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-6">
                             <div class="text-center">
                                 <input data-plugin="knob" data-width="150" data-height="150" data-fgColor="#81f880" value="8" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".2" /><small class="knob-text"><?php echo label('courses_ongoing'); ?></small>
                             </div>
                          </div>
                          <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <input data-plugin="knob" data-width="150" data-height="150" data-fgColor="#f9ef01" value="12" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".2" /><small class="knob-text"><?php echo label('courses_incoming'); ?></small>
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <input data-plugin="knob" data-width="150" data-height="150" data-fgColor="#9c9fa4" value="5" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".2" /><small class="knob-text"><?php echo label('courses_completed'); ?></small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6" id="on_going_course_admin_learner">
                      <div class="card card-body" style="min-height: 550px;">
                        <h4 class="card-title"><span class="lstick"></span><?php echo label('ongoning_course'); ?></h4>
                        <table id="ongoing_course_table_admin_learner" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th><b><?php echo label('r_course_name'); ?></b></th>
                                    <th><b><?php echo label('close_date'); ?></b></th>
                                    <th><b></b></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td title="Lorem ipsum dolor sit amet.">Lorem ipsum dolor sit amet.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>                                    
                                  </tr>
                                  <tr>
                                    <td title="Pellentesque facilisis elit eget elit blandit venenatis.">Pellentesque facilisis elit eget elit blandit venenatis.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Etiam sit amet odio at ex consectetur aliquet eu sit amet nisi.">Etiam sit amet odio at ex consectetur aliquet eu sit amet nisi.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Ut eget ipsum vitae urna imperdiet bibendum.">Ut eget ipsum vitae urna imperdiet bibendum.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Etiam venenatis quam efficitur, vehicula nulla sed, ornare lectus.">Etiam venenatis quam efficitur, vehicula nulla sed, ornare lectus.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Donec semper libero vulputate venenatis feugiat.">Donec semper libero vulputate venenatis feugiat.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Sed lacinia ex sit amet sem egestas, at pulvinar velit molestie.">Sed lacinia ex sit amet sem egestas, at pulvinar velit molestie.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                  <tr>
                                    <td title="Mauris in massa tempor, blandit velit ut, luctus lorem.">Mauris in massa tempor, blandit velit ut, luctus lorem.</td>
                                    <td>3 March 2563</td>
                                    <td>
                                      <a type="button" href="#" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('go_to_course'); ?>">
                                        <span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
                                      </a>
                                    </td>  
                                  </tr>
                                </tbody>
                              </table>
                      </div>
                    </div>

                    <div class="col-md-6" id="in_coming_course_admin_learner">
                      <div class="card card-body" style="min-height: 550px;">
                        <h4 class="card-title"><span class="lstick"></span><?php echo label('incoming_course'); ?></h4>
                        <table id="incoming_course_table_admin_learner" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th><b><?php echo label('r_course_name'); ?></b></th>
                                    <th><b><?php echo label('close_date'); ?></b></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td title="Pellentesque facilisis elit eget elit blandit venenatis.">Pellentesque facilisis elit eget elit blandit venenatis.</td>
                                    <td>3 March 2563</td>                               
                                  </tr>
                                  <tr>
                                    <td title="Fusce cursus massa et maximus pulvinar.">Fusce cursus massa et maximus pulvinar.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Ut eget ipsum vitae urna imperdiet bibendum.">Ut eget ipsum vitae urna imperdiet bibendum.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Etiam venenatis quam efficitur, vehicula nulla sed, ornare lectus.">Etiam venenatis quam efficitur, vehicula nulla sed, ornare lectus.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Donec semper libero vulputate venenatis feugiat.">Donec semper libero vulputate venenatis feugiat.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Etiam sit amet odio at ex consectetur aliquet eu sit amet nisi.">Etiam sit amet odio at ex consectetur aliquet eu sit amet nisi.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Donec semper libero vulputate venenatis feugiat.">Donec semper libero vulputate venenatis feugiat.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                  <tr>
                                    <td title="Nullam ut augue in diam aliquam dignissim.">Nullam ut augue in diam aliquam dignissim.</td>
                                    <td>3 March 2563</td>
                                  </tr>
                                </tbody>
                              </table>
                      </div>
                    </div>
                    <?php } ?>
                </div>             
            </div>

        </div>
    </div>

  <!-- DASHBOARD MODAL -->
  <div id="dashboard_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel"><?php echo label('conditions_of_use'); ?></h4>
          <button type="button" class="close" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <!-- Content -->
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam et tincidunt lacus. Vivamus rutrum eu libero id pharetra. Curabitur dignissim laoreet venenatis. Integer malesuada enim nec arcu ultrices hendrerit. Donec ut vulputate erat, at congue quam. Proin aliquam ante vel lacus gravida fermentum. Aliquam porta felis ut maximus aliquet. Etiam quis vestibulum turpis. Integer non gravida nisi. Nullam viverra arcu at finibus tincidunt. Vivamus elementum sem quis tincidunt posuere. Morbi interdum, diam tristique auctor tristique, ex tortor consequat nunc, non porttitor leo urna at ex. Aliquam blandit metus libero, tempor luctus ex finibus ut. Aliquam diam libero, varius at felis molestie, bibendum eleifend lorem.</p>

          <p>Nullam euismod libero ut purus laoreet euismod. Integer in pharetra magna. Nulla dui urna, rhoncus et ex sit amet, sodales viverra magna. Nulla molestie et nunc non fermentum. Maecenas non ultricies felis. Aenean venenatis molestie nisl, at aliquam ante pellentesque et. Quisque bibendum faucibus sodales. Fusce non ex sed neque faucibus varius. Vivamus ac ipsum odio. Integer finibus ipsum eget odio lacinia, nec tristique ipsum cursus. Maecenas quis odio nisl. Donec imperdiet tellus enim, eu rutrum eros dignissim et. Aenean nisi lorem, interdum sagittis aliquet et, finibus in velit. Nam mollis ligula vel enim porttitor vulputate. Ut ullamcorper venenatis velit.</p>

          <p>Proin in dignissim ante, bibendum gravida nibh. Sed eget tortor malesuada erat tincidunt sagittis quis vel felis. Sed a nisl urna. Curabitur in imperdiet sem, ut molestie augue. Ut non erat et elit accumsan accumsan. Suspendisse sed nisi eget diam tristique facilisis sed ac metus. Ut vulputate nec metus sed feugiat. In posuere odio in congue scelerisque. Curabitur facilisis purus purus, ac vestibulum enim porta vitae. Morbi vel elit ligula. Nunc accumsan, nunc ac dictum accumsan, sem sem mollis tortor, vel pretium nisl est sed est. Suspendisse lectus risus, ornare id orci ut, suscipit aliquam leo. Vivamus viverra vulputate leo, ut eleifend arcu euismod et. Morbi elit nisi, porta rhoncus arcu nec, ornare porttitor elit.</p>

          <p>Nam non ligula iaculis, auctor odio ut, consequat lectus. Mauris ac urna ex. Cras fermentum ultrices sem non cursus. Praesent consequat metus nec turpis elementum tristique. Suspendisse ornare auctor erat, sit amet malesuada augue. Etiam elementum ac erat quis imperdiet. Suspendisse potenti. Integer tellus orci, ultrices in ipsum in, auctor luctus ipsum. Vestibulum pretium felis ut aliquet varius. Integer id viverra lectus.</p>

          <p>Fusce fermentum auctor diam, ut aliquam risus convallis egestas. Morbi ullamcorper suscipit semper. Duis ex tortor, luctus et viverra non, semper nec nisi. Vestibulum facilisis tincidunt ultrices. Vestibulum laoreet est eget elit tristique tristique. Donec hendrerit efficitur lacinia. Curabitur bibendum nisl at dui ullamcorper ultrices. Nam auctor risus non dui blandit, a varius odio bibendum. Proin eget commodo quam. Praesent lacinia metus at placerat fringilla.</p>

          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam et tincidunt lacus. Vivamus rutrum eu libero id pharetra. Curabitur dignissim laoreet venenatis. Integer malesuada enim nec arcu ultrices hendrerit. Donec ut vulputate erat, at congue quam. Proin aliquam ante vel lacus gravida fermentum. Aliquam porta felis ut maximus aliquet. Etiam quis vestibulum turpis. Integer non gravida nisi. Nullam viverra arcu at finibus tincidunt. Vivamus elementum sem quis tincidunt posuere. Morbi interdum, diam tristique auctor tristique, ex tortor consequat nunc, non porttitor leo urna at ex. Aliquam blandit metus libero, tempor luctus ex finibus ut. Aliquam diam libero, varius at felis molestie, bibendum eleifend lorem.</p>

          <p>Nullam euismod libero ut purus laoreet euismod. Integer in pharetra magna. Nulla dui urna, rhoncus et ex sit amet, sodales viverra magna. Nulla molestie et nunc non fermentum. Maecenas non ultricies felis. Aenean venenatis molestie nisl, at aliquam ante pellentesque et. Quisque bibendum faucibus sodales. Fusce non ex sed neque faucibus varius. Vivamus ac ipsum odio. Integer finibus ipsum eget odio lacinia, nec tristique ipsum cursus. Maecenas quis odio nisl. Donec imperdiet tellus enim, eu rutrum eros dignissim et. Aenean nisi lorem, interdum sagittis aliquet et, finibus in velit. Nam mollis ligula vel enim porttitor vulputate. Ut ullamcorper venenatis velit.</p>

          <p>Proin in dignissim ante, bibendum gravida nibh. Sed eget tortor malesuada erat tincidunt sagittis quis vel felis. Sed a nisl urna. Curabitur in imperdiet sem, ut molestie augue. Ut non erat et elit accumsan accumsan. Suspendisse sed nisi eget diam tristique facilisis sed ac metus. Ut vulputate nec metus sed feugiat. In posuere odio in congue scelerisque. Curabitur facilisis purus purus, ac vestibulum enim porta vitae. Morbi vel elit ligula. Nunc accumsan, nunc ac dictum accumsan, sem sem mollis tortor, vel pretium nisl est sed est. Suspendisse lectus risus, ornare id orci ut, suscipit aliquam leo. Vivamus viverra vulputate leo, ut eleifend arcu euismod et. Morbi elit nisi, porta rhoncus arcu nec, ornare porttitor elit.</p>

          <p>Nam non ligula iaculis, auctor odio ut, consequat lectus. Mauris ac urna ex. Cras fermentum ultrices sem non cursus. Praesent consequat metus nec turpis elementum tristique. Suspendisse ornare auctor erat, sit amet malesuada augue. Etiam elementum ac erat quis imperdiet. Suspendisse potenti. Integer tellus orci, ultrices in ipsum in, auctor luctus ipsum. Vestibulum pretium felis ut aliquet varius. Integer id viverra lectus.</p>

          <p>Fusce fermentum auctor diam, ut aliquam risus convallis egestas. Morbi ullamcorper suscipit semper. Duis ex tortor, luctus et viverra non, semper nec nisi. Vestibulum facilisis tincidunt ultrices. Vestibulum laoreet est eget elit tristique tristique. Donec hendrerit efficitur lacinia. Curabitur bibendum nisl at dui ullamcorper ultrices. Nam auctor risus non dui blandit, a varius odio bibendum. Proin eget commodo quam. Praesent lacinia metus at placerat fringilla.</p>

            <input type="checkbox" id="check-1" class="filled-in chk-col-red">
            <label for="check-1">Fusce volutpat enim sed leo tincidunt, a molestie ligula lobortis.</label>
            <br>
            <input type="checkbox" id="check-2" class="filled-in chk-col-red">
            <label for="check-2">Etiam convallis erat ac sem egestas sagittis.</label>
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" title="<?php echo label('go_to_course'); ?>" class="btn waves-effect waves-light btn-outline-danger btn-danger-hover float-right disabled" id="confirm_button" role="button" aria-disabled="true"><i class="mdi mdi-file-document-box"></i><?php echo ' '.label('m_ok'); ?></a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.DASHBOARD MODAL -->

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/Chart.js/Chart.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/d3/d3.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/c3-master/c3.min.js"></script>
    <!--morris JavaScript --> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.js"></script> 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script> 
    <!-- Chart JS --> 
    <script src="<?php echo REAL_PATH; ?>/assets/js/dashboard1.js"></script> 
    <!-- EASY PIE CHART JS -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/Chart.js/Chart.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/knob/jquery.knob.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/cdn/roundslider.min.js"></script>

    <script type="text/javascript">
      
        $(function() {
            $('[data-plugin="knob"]').knob();
        });
        <?php if(in_array('4', $arr_role_fd)){ ?>
        $(document).ready(
          function() {

            var chart = c3.generate({
                bindto: '#visitor',
                data: {
                    columns: [
                        ['Desktop', parseInt('<?php echo $PC_log; ?>')],
                        ['Tablet', parseInt('<?php echo $Tablet_log; ?>')],
                        ['Mobile', parseInt('<?php echo $Mobile_log; ?>')],
                    ],
                    
                    type : 'donut',
                    onclick: function (d, i) { console.log("onclick", d, i); },
                    onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                    onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                },
                donut: {
                    label: {
                        show: false
                      },
                    title:"",
                    width:20,
                    
                },
                
                legend: {
                  hide: true
                  //or hide: 'data1'
                  //or hide: ['data1', 'data2']
                },
                color: {
                      pattern: ['#e67e22','#eceff1',  '#26c6da', '#1e88e5']
                }
            });

          }
        );
        <?php } ?>
    </script>

    <script>
      $('#company_active_user_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#ongoing_course_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#incoming_course_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#ongoing_course_table_admin_learner').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#incoming_course_table_admin_learner').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });


    </script>

    <script type="text/javascript">
      $(window).on('load',function(){
          $('#dashboard_modal').modal('show');
      });

      $('#dashboard_modal').modal({backdrop: 'static', keyboard: false});

      $("input[type=checkbox]").change(function(){

        if($('#check-1').is(':checked') && $('#check-2').is(':checked')) {
          $('#confirm_button').removeClass("disabled");
        }else{
          $('#confirm_button').addClass("disabled");
        }
      });
    </script>
</body>

</html>
