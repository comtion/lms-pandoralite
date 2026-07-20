<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>

    <link href="<?php echo HTTP_CSS_PATH; ?>home.css" rel="stylesheet">

    <style>
      @media screen and (max-height: 600px) {
          .footer{
            display: none !important;
          }
      }

      @media (min-width:1025px) {
        .footer-con {
              padding: 0;
        }
      }
      @media (min-width:1400px) {
        .footer-con {
              padding: 25px 15px;
        }
      }

      @supports (-ms-ime-align: auto) {
        .footer-con {
              padding: 0 !important;
        }
      }
      .align-left {
        text-align: left;
      }
    </style>
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
        

        <?php if (empty($emp_c)){?> 
        <style>
          .fix-header.fix-sidebar .page-wrapper{
            padding-top: 70px !important;
          }

        </style>
        <?php } ?>
        <?php if (!empty($emp_c)){?> 
        <style>

        </style>
        <?php } ?>
        
      <div class="page-wrapper"> 
          <div class="container-fluid"> 
             <div class="row banner-text">
                <div class="col-lg-4 col-md-12" style="<?php if (!empty($emp_c)){ ?>display:none;<?php } ?>">
                  <div class="card">
                    <div class="card-body">
                      
                          <form class="form-horizontal form-material" autocomplete="off" id="loginform" method="POST">
                              <h3 class="box-title m-b-20">
                                  <?php echo label('login'); ?>
                              </h3>
                              <div class="form-group ">
                                  <div class="col-md-12">
                                      <input class="form-control" onkeyup="return forceLower(this);" id="inpUname" name="inpUname" type="text" required="" autofocus placeholder="<?php echo label('username') ?>"> </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <input class="form-control" id="inpPwd" name="inpPwd" type="text" required="" placeholder="<?php echo label('password') ?>"> 
                                      <span toggle="#inpPwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                  </div>
                              </div>
                              <input type="hidden" id="dest" name="dest" value="<?php echo $dest; ?>">
                              <div class="form-group ">
                                    <a href="javascript:void(0)" id="to-recover" class="text-muted float-right"><i class="fa fa-lock"></i> <?php echo label('forgot_pass'); ?></a> 
                              </div>
                              <div class="form-group text-center">
                                  <div class="col-md-12  p-b-20">
                                      <button class="btn btn-block btn-outline-success" id="btnlogin" type="submit"><i class="icon-login"></i> <?php echo label('login') ?></button>
                                  </div>
                              </div>
                          </form>

                          <form class="form-horizontal" id="recoverform" autocomplete="off" method="POST">
                              <div class="form-group ">
                                  <div class="col-md-12">
                                      <h3><?php echo label('forgot_pass'); ?></h3>
                                      <p class="text-muted"><?php echo label('forgot_pass_noti'); ?></p>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <input class="form-control" type="text" id="useri" name="useri" placeholder="<?php echo label('pholder_usn') ?>"> </div>
                              </div>
                              <div class="form-group text-center m-t-20">
                                  <div class="col-md-12">
                                      <button type="reset" value="Reset" class="btn btn-outline-info pull-right return_login"><i class=" fas fa-chevron-circle-left"></i><span> <?php echo label('m_previous'); ?></span></button>
                                      <button class="btn btn-outline-success text-uppercase waves-effect waves-light" id="recover_btn" type="submit"><?php echo label('m_ok'); ?></button>
                                  </div>
                              </div>
                          </form>
                    </div>
                  </div>
                </div>

                <div class="<?php if (empty($emp_c)){ ?>col-lg-8 col-md-12<?php }else{ ?>col-lg-12<?php } ?>">

                    <div class="card">
                      <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                          <?php if(isset($pic)&&countArray($pic)>0){
                                  if($pic != null&&$page=='home'){?>
                                      <?php $count_num = 0;$n=1;foreach ($pic as $row) {
                                        if($n==1){ ?>
                                          <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>" class="active" ></li>
                                        <?php }else{?>
                                          <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>"></li>
                                      <?php }$n++;$count_num++;}?>
                            <?php }
                                } ?>
                        </ol>
                        <div class="carousel-inner" role="listbox">

                          <?php if(isset($pic)&&countArray($pic)>0){
                                  if($pic != null&&$page=='home'){?>
                                      <?php $n=1;foreach ($pic as $row) {
                                        if($n==1){ ?>
                                        <div class="carousel-item active" style="width: 100%; text-align: center; max-height:350px;"> <img class="img-fluid" width="100%" style="max-height:350px;" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
                                          <!--<div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">First title goes here</h3>
                                            <p>this is the subcontent you can use this</p>
                                          </div>-->
                                        </div>
                                        <?php }else{?>
                                        <div class="carousel-item" style="width: 100%; text-align: center; max-height:350px;"> <img class="img-fluid" width="100%" style="max-height:350px;" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
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

                </div>
            </div>
          </div>
      </div>
    </div>
        <div class="footer hidden-md-down" style="padding: 0 !important;"><?php $this->load->view('frontend/inc/inc-footer.php'); ?></div>

<div class="hidden-lg-up"><?php $this->load->view('frontend/inc/inc-footer.php'); ?></div>

    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo REAL_PATH;?>/assets/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/sidebarmenu.js"></script>
    <script type="text/javascript">
      function removeSpaceFromText (id) {
          var txt = $("#" + id);
          var func = function() {
            txt.val(txt.val().replace(/\s/g, ''));
          }
          txt.keyup(func).blur(func);
      }
      removeSpaceFromText ('inpUname');
      removeSpaceFromText ('inpPwd');
      document.getElementById('inpPwd').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            document.getElementById('btnlogin').click();
        }
      });
      $(function(){
          $(".chkinputENOnly").keypress(function(event){
              var ew = event.which;
              if(ew == 32)
                  return true;
              if(48 <= ew && ew <= 57)
                  return true;
              if(65 <= ew && ew <= 90)
                  return true;
              if(97 <= ew && ew <= 122)
                  return true;
              return false;
          });
      });
      $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
      function forceLower(strInput) 
      {
        strInput.value=strInput.value.toLowerCase();
      }
    $("#recoverform").slideUp();
    //document.getElementById('recoverform').style.display = "none";
      /*$(document).ready(function() {
        $(document).on('submit', '#register_form', function(event){
              event.preventDefault(); 
              document.getElementById("action").disabled = true;
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/register_user",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {

                    document.getElementById("action").disabled = false;
                    if(data=="2"){
                        $('#register_form')[0].reset();
                        $('#modal-register').modal('hide');
                        swal({
                            title: "<?php echo label("com_msg_success"); ?>",
                            text: "",
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                          location.reload();
                        })
                    }else if(data=="1"){
                        swal({
                            title: "<?php echo label("com_msg_duplicate"); ?>",
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                        })
                    }else{
                        swal({
                            title: "<?php echo label("com_msg_error_save"); ?>",
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                   
                  }
                });
            });
      });*/
         $(document).on('click', '.readmore', function(){
            var id = $(this).attr("id");
            var title = $(this).attr("title");
            var detail = $(this).attr("detail");
            $('#modal-readmore').modal('show');
            $('#title_readmore').text(title);
            $('#detail_readmore').html(detail);
          });
         $(document).on('click', '.btn_example', function(){
            var id = $(this).attr("id");
            console.log(id);
            $.ajax({
              url:"<?=base_url()?>index.php/course/update_course_data",
              method:"POST",
              data:{id_update:id},
              dataType:"json",
              success:function(data)
              {
                <?php if($lang=="thai"){ ?>
                  $('#txt_coursehead').text(data.cname_th);
                  $('#description_course').html(data.cdesc_th);
                <?php }else{ ?>
                  $('#txt_coursehead').text(data.cname_en);
                  $('#description_course').html(data.cdesc_en);
                <?php } ?>
                rating_course(data.cos_rating);
                document.getElementById("img_coursehead").src = "<?php echo REAL_PATH;?>/uploads/course/"+data.pic;
                $("#img_coursehead").on("error", function(){
                    $(this).attr('src', '<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg');
                });
              }
            });
          });
         function rating_course(rating_course){
            var str = 'Rating : ';
            for (var i = 1; i <=parseInt(rating_course); i++) {
              str += '<i class="fa fa-star text-warning"></i>';
            }
            for (var i = 1; i <=(5-parseInt(rating_course)); i++) {
              str += '<i class="fa fa-star text-default"></i>';
            }
            $('#rating_course').html(str);
         }
         function run_analytic(cos_id){
            window.location.href = '<?=base_url()?>course/analytic_course/'+cos_id;
         }

         $(document).on('click', '.btn_detail', function(){
            var id = $(this).attr("id");
            window.location.href = '<?=base_url()?>index.php/course/detail/'+id+'/<?php echo "1"; ?>';
          });
          $(document).on('submit', '#recoverform', function(event){
              event.preventDefault(); 
              var username = $('#useri').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/dashboard/resetPassSubmit",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:'json',
                  xhr: function() {
                          //document.getElementById("progress_cosmain_div").style.display = "";
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {
                          $("#recover_btn").attr("disabled", true);
                      }
                    }, false);
                    return xhr;
                  },
                  success:function(data)
                  {
                    $("#recover_btn").attr("disabled", false);
                    if(data.rs==true){
                        $('#recoverform')[0].reset();

                        swal({
                            title: data.msg,
                            text: "",
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                          window.location.href = '<?php echo base_url()."index.php/home"; ?>';
                          //window.location.href = '<?php echo base_url()."contact/form_chk/"; ?>'+data.emp_id+'/';
                        })
                    }else{
                        swal({
                            title: data.msg,
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            window.location.href = '<?php echo base_url()."index.php/home"; ?>';
                        })
                    }                   
                  }
                });
            });
          $('#loginform').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
              e.preventDefault();
              return false;
            }
          });
          $(document).on('submit', '#loginform', function(event){
              event.preventDefault(); 
              var username = $('#inpUname').val();
              var password = $('#inpPwd').val();
              var dest = $('#dest').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/dashboard/chk_login",
                  method:'POST',
                  data:new FormData(this),
                  dataType: 'json',
                  cache: false,
                  processData: false,
                  contentType: false,
                  timeout: 15000,
                  async: true,
                  headers: {
                    "cache-control": "no-cache"
                  },
                  xhr: function() {
                          //document.getElementById("progress_cosmain_div").style.display = "";
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {
                          $("#btnlogin").attr("disabled", true);
                      }
                    }, false);
                    return xhr;
                  },
                  success:function(data)
                  {
                    $("#btnlogin").attr("disabled", false);
                    if(data.status_msg=="complete"){
                        $('#loginform')[0].reset();
                        $.ajax({
                          url:"<?=base_url()?>index.php/dashboard/chk_firsttime_user",
                          method:'POST',
                          data:{username:username,password:password,dest:dest},
                          dataType: 'json',
                          success:function(data_chk)
                          {
                            if(data_chk.status=="0"){
                                swal({
                                    title: '<?php echo label("login_msg"); ?>',
                                    text: "",
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("m_ok"); ?>'
                                }).then(function () {
                                  window.location.href = data_chk.redirect_val;
                                })
                            }else if(data_chk.status=="4"){
                                swal({
                                    title: '<?php echo label("login_passexpire"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("m_ok"); ?>'
                                }).then(function () {
                                  window.location.href = data_chk.redirect_val;
                                })
                            }else{
                                swal({
                                    title: '<?php echo label("login_firsttime"); ?>',
                                    text: "",
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("m_ok"); ?>'
                                }).then(function () {
                                  window.location.href = data_chk.redirect_val;
                                })
                            }
                          }
                        });
                        
                    }else if(data.status_msg=="account_locked"){
                        swal({
                            title: '<?php echo label("account_locked"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            window.location.href = '<?php echo base_url()."contact/form_chk/"; ?>'+data.emp_id+'/<?php echo $lang; ?>/';
                        })
                    }else if(data.status_msg=="login_failed_4_time"){
                        swal({
                            html: '<div class="align-left"><?php echo label("login_failed_4_time"); ?></div>',
                            // title: '<?php echo label("login_failed_4_time"); ?>',
                            // text: "",
                            type: 'warning',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-default',
                            confirmButtonText: '<?php echo label("forgot_pass"); ?>',
                        }).then(function () {
                          
                            $("#loginform").slideUp();
                            $("#recoverform").fadeIn();
                            document.getElementById('loginform').style.display = "none";
                            document.getElementById('recoverform').style.display = "";
                            $("#useri").val(username);
 
                            /*$.ajax({
                              url:"<?=base_url()?>index.php/dashboard/resetPassSubmit",
                              method:'POST',
                              data: {
                                "useri": username
                              },
                              dataType:'json',
                              success:function(data)
                              {
                                if (data.rs==true) {
                                    swal({
                                        title: data.msg,
                                        text: "",
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label("m_ok"); ?>'
                                    }).then(function () {
                                        window.location.href = '<?php echo base_url()."index.php/home"; ?>';
                                    })
                                } else {
                                    swal({
                                        title: data.msg,
                                        text: "",
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label("m_ok"); ?>'
                                    }).then(function () {
                                        window.location.href = '<?php echo base_url()."index.php/home"; ?>';
                                    })
                                }
                              }
                            });*/
                        })
                    }else if(data.status_msg=="login_failed"){
                        swal({
                            title: '<?php echo label("login_failed"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                            $('#inpPwd').val('');
                            $('#inpPwd').focus();
                        })
                    }else if(data.status_msg=="notfound"){
                        swal({
                            title: '<?php echo label("datauser_notfound"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                            $('#inpPwd').val('');
                            $('#inpPwd').focus();
                        })
                    }else if(data.status_msg=="passnotfound"){
                        swal({
                            title: '<?php echo label("password_failed"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                            $('#inpPwd').val('');
                            $('#inpPwd').focus();
                        })
                    }else if(data.status_msg=="inactive"){
                        swal({
                            title: '<?php echo label("account_locked"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                            $('#inpUname').val('');
                            $('#inpPwd').val('');
                            $('#inpUname').focus();
                        })
                    }
                   
                  }
                });
            });
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
            document.getElementById('loginform').style.display = "none";
            document.getElementById('recoverform').style.display = "";
        });
        $('.btn_register').on("click",function(){
            $('#modal-register').modal('show');

              document.getElementById("action").disabled = false;
        });
        $('.return_login').on("click", function(){
            document.getElementById("inpUname").focus();
            $("#loginform").fadeIn();
            $("#recoverform").slideUp();
            document.getElementById('loginform').style.display = "";
            document.getElementById('recoverform').style.display = "none";
        });
        function register_course(id,enroll_seat,seat_count){
            if((parseInt(enroll_seat)+1)<parseInt(seat_count)){
              status = "1";
            }else{
              status = "0";
            }
            if(parseInt(seat_count)==0){
              status = "1";
            }
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/register_course",
                      method:'POST',
                      data:{cos_id:id,status:status},
                      success:function(data)
                      {
                        if(data=="2"){
                            swal(
                                '<?php echo label("enroll_reuse_success"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                              window.location.href = '<?=base_url()?>index.php/course/detail/'+id;
                            })
                        }else{
                            swal({
                                title: '<?php echo label("enroll_reuse_error"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                location.reload();
                            })
                        }
                       
                      }
                    });
        }
				
    </script>
  </body>
</html>
