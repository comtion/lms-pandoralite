<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
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
            <?php   if(countArray($banner)>0){ ?>
            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <?php $numban = 0;
                        foreach ($banner as $key_ban => $value_ban) { ?>
                    <li data-target="#carouselExampleIndicators2" data-slide-to="<?php echo $numban; ?>" <?php if($numban==0){ ?>class="active"<?php }$numban++; ?>></li>
                  <?php } ?>
                </ol>
                <div class="carousel-inner" role="listbox" style="height: 200px;">
                  <?php $numban = 0;
                        foreach ($banner as $key_ban => $value_ban) { ?>
                    <div class="carousel-item <?php if($numban==0){ ?>active<?php }$numban++; ?>">
                      <img class="carousel-course" style="background-image:url(<?php echo REAL_PATH; ?>/uploads/banner_course/<?php echo $value_ban['bc_image']; ?>); overflow: hidden; background-size: cover; background-position: center; width: 100%; height: 200px;" alt="">
                        <div class="carousel-caption d-md-block">
                        <h1 class="container-fluid text-white"><?php if($lang=="thai"){echo $value_ban['bc_name_th'];}else if($lang=="english"){echo $value_ban['bc_name_eng'];}else{echo $value_ban['bc_name_jp'];} ?></h1>
                      </div>
                    </div>
                  <?php } ?>
                </div>


                <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?php echo label('m_previous'); ?></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?php echo label('m_next'); ?></span>
                </a>
            </div>
            <?php   } ?>
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                    </div>
                </div>  

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body row">
                        <div class="col-12 text-right mb-3">
                            <form class="form-search justify-content-end">
                              <button>
                                  <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                                      <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                                  </svg>
                              </button>
                              <input  class="input-search" placeholder="<?php echo label('search'); ?>..." required="" type="text"
                                      id="surveySearchInput"
                                      onkeyup="filterSurveysByName()">
                              <button class="reset-search" type="reset">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                  </svg>
                              </button>
                          </form>
                        </div>
                        <?php   if(countArray($list_survey)>0){ 
                                    $numloop = 1;
                                    foreach ($list_survey as $key_list => $value_list) {
                                    
                                      if($lang=="thai"){ 
                                        $sv_title = $value_list['sv_title_th']!=""?$value_list['sv_title_th']:$value_list['sv_title_eng'];
                                        $sv_title = $sv_title!=""?$sv_title:$value_list['sv_title_jp'];
                                      }else if($lang=="english"){ 
                                        $sv_title = $value_list['sv_title_eng']!=""?$value_list['sv_title_eng']:$value_list['sv_title_th'];
                                        $sv_title = $sv_title!=""?$sv_title:$value_list['sv_title_jp'];
                                      }else{
                                        $sv_title = $value_list['sv_title_jp']!=""?$value_list['sv_title_jp']:$value_list['sv_title_eng'];
                                        $sv_title = $sv_title!=""?$sv_title:$value_list['sv_title_th'];
                                      }
                        ?>
                        <div class="card-group pt-3 col-md-3">
                          <!-- CARD 1 -->
                          <div class="card">
                            <div class="card-img-top img-responsive pointer" alt="" onclick="location.href='<?php echo REAL_PATH;?>/survey/surveyDetail/<?php echo $value_list['sv_id']; ?>';" <?php if(isset($value_list['sv_cover'])){ ?>style="background-image:<?php if($value_list['sv_cover']!=""&&is_file(ROOT_DIR."uploads/publicsv/".$value_list['sv_cover'])){ ?>url(<?php echo REAL_PATH;?>/uploads/publicsv/<?php echo $value_list['sv_cover']; ?>)<?php }else{ ?>url(<?php echo REAL_PATH;?>/images/cover_survey.jpg)<?php } ?>; overflow: hidden; background-size: cover; background-position: center; width: 100%; height: 215px;"<?php } ?>></div>

                            <div class="card-body">
                              <h4 class="card-title h4-two-line-ellipsis pointer" onclick="location.href='<?php echo REAL_PATH;?>/survey/surveyDetail/<?php echo $value_list['sv_id']; ?>';" title="<?php echo $sv_title; ?>"><?php echo $sv_title; ?></h4>
                              <p class="card-text mt-3"><?php echo label('sv_specific').': '; ?> 
                                <br>
                          <?php if($value_list['sv_open']!="0000-00-00 00:00:00"&&$value_list['sv_end']!="0000-00-00 00:00:00"){ 
                                $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
                                    if($lang=="thai"){
                                        echo date('d ',strtotime($value_list['sv_open'])).$arrMonthThaiTextFull[intval(date('m',strtotime($value_list['sv_open'])))]." ".(date('Y',strtotime($value_list['sv_open']))+543)." ".date('H:i',strtotime($value_list['sv_open']))." ".date('d ',strtotime($value_list['sv_end'])).$arrMonthThaiTextFull[intval(date('m',strtotime($value_list['sv_end'])))]." ".(date('Y',strtotime($value_list['sv_end']))+543)." ".date('H:i',strtotime($value_list['sv_end']));
                                    }else{
                                        echo date('d F Y H:i',strtotime($value_list['sv_open']))." ".date('d F Y H:i',strtotime($value_list['sv_end']));
                                    }
                                }else{ echo label('UnlimitedTime'); } ?>
                              </p>
                              <p class="card-text imat-completed-text"><?php echo label('status').': '.$value_list['status']; ?></p>
                              <!-- <p class="card-text imat-incompleted-text"><?php echo label('status').': '.label('noProgress'); ?></p> -->
                              <div class="row" title="<?php echo label('speopled'); ?>">
                                <div class="col-12 align-self-center text-right">
                                 <i class="mdi mdi-account-multiple-outline"></i> <?php echo $value_list['seat']; ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                       <!--  <?php if($numloop==4){ $numloop=0;?>
                        <hr class="p-3">
                        <?php } ?> -->
                        <?php       
                                    $numloop++;
                                    }
                                }else{ ?>
                        <h4 align="center"><i class="mdi mdi-alert-circle-outline"></i> <?php echo label('wg_datanotfound'); ?></h4>
                        <?php } ?>

                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>

    <script type="text/javascript">

        function filterSurveysByName() {
          const input = document.getElementById("surveySearchInput");
          const filter = input.value.toLowerCase();
          const cards = document.querySelectorAll(".card-group");

          cards.forEach(function(cardGroup) {
            const titleElem = cardGroup.querySelector(".card-title");
            const title = titleElem ? titleElem.textContent.toLowerCase() : "";

            if (title.includes(filter)) {
              cardGroup.style.display = "";
            } else {
              cardGroup.style.display = "none";
            }
          });
        }
    </script>
</body>

</html>