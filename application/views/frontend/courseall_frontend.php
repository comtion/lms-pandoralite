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
                    <span class="sr-only"><?php echo label('lrn_btn_previous'); ?></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?php echo label('lrn_btn_next'); ?></span>
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
              <div class="card card-body row">
                <div class="col-12 text-right mb-3">
                    <form class="form-search justify-content-end">
                      <button>
                          <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                              <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                      </button>
                      <input  class="input-search" placeholder="<?php echo label('search'); ?>..." required="" type="text"
                              id="courseSearchInput"
                              onkeyup="filterCoursesByName()">
                      <button class="reset-search" type="reset">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                      </button>
                  </form>
                </div>
                <div class="col-md-12">
                  <?php if(countArray($list_course)>0){ ?>
                  <div class="row">
                  <?php if(countArray($list_coursegroup)>0){ ?>
                    <div class="col-auto col-md-12 col-lg-3 card card-body">

                      <div class="stickyside">

                        <label align="left"><?php echo label('lrn_b_course_type'); ?></label>
                        <?php foreach ($list_coursegroup as $key_cg => $value_cg) { ?>
                        <div class="checkbox checkbox-success">
                          <input type="checkbox" id="chk_cg_<?php echo $value_cg['cg_id'] ?>" onclick="clickdiv_cg('<?php echo $value_cg['cg_id']; ?>')" name="chk_cg_<?php echo $value_cg['cg_id'] ?>" checked>
                          <label for="chk_cg_<?php echo $value_cg['cg_id'] ?>"><?php echo $value_cg['cgname']; ?></label>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-9 card card-body">
                      <?php   $numgroup = 1;
                              foreach ($list_coursegroup as $key_cg => $value_cg) { ?>
                          <div class="card-header mb-4 div_cgheader<?php echo $value_cg['cg_id']; ?>" align="left"><?php echo $value_cg['cgname']; ?></div>
                          <div class="card-group div_cgheader<?php echo $value_cg['cg_id']; ?>">
                            <div class="col-md-12 row">
                          <?php   if(countArray($list_course)>0){
                                      $numloop = 1;
                                      foreach ($list_course as $key_list => $value_list) {
                                          if(in_array($value_cg['cg_id'], $value_list['cg_arr'])){
                          ?>
                            <div class="col-md-4 course-item" data-name="<?php echo strtolower($value_list['cname']); ?>" data-group="<?php echo $value_cg['cg_id']; ?>">
                              <div class="card">
                              <div class="card-img-top img-responsive <?php if($value_list['isCondition']=="1"){ ?>onCondition<?php } ?> <?php if($value_list['isRegister']=="1"){ ?>pointer<?php } ?>" msg="<?php echo str_replace('_coursename_', $value_list['msgCondition'], label('condition_msg')); ?>" alt="" <?php if($value_list['isRegister']=="1"&&$value_list['isCondition']=="0"){ ?>onclick="location.href='<?php echo REAL_PATH;?>/coursemain/detail/<?php echo $value_list['cos_id']; ?>';"<?php } ?> style="background-image:<?php if($value_list['cos_pic']!=""&&is_file(ROOT_DIR."uploads/course/".$value_list['cos_pic'])){ ?>url(<?php echo REAL_PATH;?>/uploads/course/<?php echo $value_list['cos_pic']; ?>) <?php }else{ ?>url(<?php echo REAL_PATH;?>/images/cover_course.jpg)<?php } ?>; overflow: hidden; background-size: contain; background-position: center; background-repeat: no-repeat; width: 100%; height: 215px;"></div>
                              <div class="card-body <?php if($value_list['isCondition']=="1"){ ?>onCondition<?php } ?>" msg="<?php echo str_replace('_coursename_', $value_list['msgCondition'], label('condition_msg')); ?>">
                                <h4 class="card-title h4-two-line-ellipsis <?php if($value_list['isRegister']=="1"){ ?>pointer<?php } ?>" <?php if($value_list['isRegister']=="1"&&$value_list['isCondition']=="0"){ ?>onclick="location.href='<?php echo REAL_PATH;?>/coursemain/detail/<?php echo $value_list['cos_id']; ?>';"<?php } ?> title="<?php echo $value_list['cname']; ?>"><?php echo $value_list['cname']; ?></h4>
                                <p class="card-text mt-3"><?php echo label('lrn_b_course_period').': '; ?>
                                  <br>
                            <?php echo isset($value_list['txt_period_course']) ? $value_list['txt_period_course'] : lms_format_period_range($value_list['date_start'], $value_list['date_end'], $lang); ?>
                                </p>
                                <?php if($value_list['status']==label('lrn_btn_done')){ ?>
                              
                                <p class="card-text imat-completed-text"><?php echo label('lrn_btn_status').': '.$value_list['status']; ?></p>
                                <?php }else if($value_list['status']==label('lrn_b_in_progress')){ ?>
                                <p class="card-text imat-incoming-text"><?php echo label('lrn_btn_status').': '.$value_list['status']; ?></p>
                                <?php }else if($value_list['status']==label('lrn_b_no_progress')){ ?>
                                <p class="card-text imat-ongoing-text"><?php echo label('lrn_btn_status').': '.$value_list['status']; ?></p>
                                <?php }else{ ?>
                                <p class="card-text imat-incompleted-text"><?php echo label('lrn_btn_status').': '.$value_list['status']; ?></p>
                                <?php } ?>
                                <div class="row" title="<?php echo label('lrn_b_total_student'); ?>">
                                  <div class="col-4 align-self-center">
                                  <i class="mdi mdi-account-multiple-outline"></i> <?php echo number_format($value_list['seat']); ?>
                                  </div>
                                  <?php if($value_list['isRegister']=="0"){ ?>
                                  <div class="col-8">
                                    <?php if($value_list['isseatFull']=="0"){ ?>
                                    <?php     if($value_list['isCondition']=="0"){ ?>

                                    <button id="<?php echo $value_list['cos_id']; ?>" title="<?php echo label('lrn_btn_register'); ?>" class="btn btn-block btn_register waves-effect waves-light btn-outline-danger btn-danger-hover float-right"><i class="mdi mdi-file-document-box"></i><?php echo ' '.label('lrn_btn_register'); ?></button>
                                    <?php     }
                                          }else{ ?>
                                      <span><?php echo label('full'); ?></span>
                                    <?php } ?>
                                  </div>
                                  <?php } ?>
                                </div>
                              </div>
                              </div>
                            </div>
                          <?php
                                      }
                                  }
                              }
                          ?>
                          </div>
                          <?php if($numgroup<countArray($list_coursegroup)){ ?>
                          <hr class="mb-4">
                          <?php } ?>
                        </div>
                      <?php   $numgroup++;
                              } ?>
                    </div>
                <?php } ?>
                  </div>
                    <?php }else{ ?>
                      <h4 align="center"><i class="mdi mdi-alert-box"></i> <?php echo label('lrn_b_course_notfound'); ?></h4>
                    <?php } ?>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

      <div id="myModal_process" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body" align="center">
                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('please_wait'); ?></h3>
              </div>
            </div>
        </div>
      </div>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>

    <script type="text/javascript">
      
      
      
      function filterCoursesByName() {
        const input = document.getElementById("courseSearchInput").value.toLowerCase();
        const courseItems = document.querySelectorAll(".course-item");
        const groupVisibleMap = {};

        // ซ่อนหรือแสดงแต่ละหลักสูตร
        courseItems.forEach(item => {
          const name = item.getAttribute("data-name");
          const groupId = item.getAttribute("data-group");

          const isVisible = name.includes(input);
          item.style.display = isVisible ? "block" : "none";

          if (!groupVisibleMap[groupId]) groupVisibleMap[groupId] = 0;
          if (isVisible) groupVisibleMap[groupId]++;
        });

        // แสดงหรือซ่อนทั้งกลุ่มตามจำนวนที่เหลือ
        Object.keys(groupVisibleMap).forEach(groupId => {
          const visibleCount = groupVisibleMap[groupId];

          const headers = document.querySelectorAll(`.div_cgheader${groupId}`);
          headers.forEach(el => {
            el.style.display = visibleCount > 0 ? "block" : "none";
          });
        });

        // กลุ่มที่ไม่มี course-item แสดงเลย (เผื่อ fallback)
        const allGroupHeaders = document.querySelectorAll("[class^='div_cgheader']");

        allGroupHeaders.forEach(el => {
          const match = el.className.match(/div_cgheader(\d+)/);
          if (match) {
            const groupId = match[1];
            if (!groupVisibleMap[groupId] || groupVisibleMap[groupId] === 0) {
              el.style.display = "none";
            }
          }
        });
      }

      $('.onCondition').click(function(){
            var msg = $(this).attr("msg");
                        swal({
                            title: msg,
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
      });
      function clickdiv_cg(cg_id){
        var chkbox = document.getElementById('chk_cg_'+cg_id);
        if(chkbox.checked){
          $('.div_cgheader'+cg_id).show();
        }else{
          $('.div_cgheader'+cg_id).hide();
        }
      }

      $(document).on('click', '.btn_register', function(){
            var cos_id = $(this).attr("id");
            swal({
                title: '<?php echo label('enroll_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#FE0000",   
                confirmButtonText: '<?php echo label('lrn_btn_register'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $("#myModal_process").modal({backdrop: false});
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                      url:"<?=base_url()?>index.php/querydata/enroll_course_byuser",
                      method:"POST",
                      data:{cos_id:cos_id},
                      dataType:"json",
                      success:function(data)
                      {
                          $( document.body ).css( 'pointer-events', '' );
                          if(data.status=="2"){
                              swal(
                                  '<?php echo label("enroll_reuse_success"); ?>',
                                  '',
                                  'success'
                              ).then(function () {
                                location.reload();
                              })
                          }else if(data.status=="3"){//Wait approve
                              swal({
                                title: '<?php echo label('lrn_b_approver_student'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                              }).then(function () {
                                location.reload();
                              })
                          }else if(data.status=="1"){//Duplicate
                              swal({
                                title: '<?php echo label('lrn_btn_re_enroll'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                              }).then(function () {
                                location.reload();
                              })
                          }else if(data.status=="5"){//Seat Full
                              swal({
                                title: '<?php echo label('lrn_p_regis_sub'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                              }).then(function () {
                                location.reload();
                              })
                          }else if(data.status=="11"){//condition
                              swal({
                                title: '<?php echo label('register_condition'); ?>'+data.msg,
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                              }).then(function () {
                                location.reload();
                              })
                          }else{
                              swal({
                                title: '<?php echo label('lrn_p_data_not_found'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                              }).then(function () {
                                location.reload();
                              })
                          }
                      }
                });
              }
            });
      });
    </script>
</body>

</html>
