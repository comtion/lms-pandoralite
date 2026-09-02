<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>

    <link href="<?php echo HTTP_CSS_PATH; ?>home.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/home-auth-premium.css?v=20260901-2" rel="stylesheet">

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
  <body class="fix-header fix-sidebar card-no-border<?php echo empty($emp_c) ? ' premium-auth-page' : ''; ?>">
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
             <div class="row banner-text premium-auth-layout">
                <div class="col-lg-4 col-md-12 premium-auth-form-column" style="<?php if (!empty($emp_c)){ ?>display:none;<?php } ?>">
                  <div class="card premium-auth-card">
                    <div class="card-body">
                      
                          <form class="form-horizontal form-material" autocomplete="off" id="loginform" method="POST">
							  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                              <div class="premium-auth-heading">
                                <span class="premium-auth-eyebrow">ISUZU E-LEARNING</span>
                                <h3 class="box-title"><?php echo label('login'); ?></h3>
                                <p><?php echo $lang === 'thai' ? 'เข้าสู่ระบบเพื่อเริ่มต้นการเรียนรู้ของคุณ' : 'Sign in to continue your learning journey'; ?></p>
                              </div>
                              <div class="form-group ">
                                  <div class="col-md-12">
                                      <label for="inpUname"><?php echo label('username') ?></label>
                                      <div class="premium-input-wrap">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0"/></svg>
                                        <input class="form-control" onkeyup="return forceLower(this);" id="inpUname" name="inpUname" type="text" required="" autofocus autocomplete="username" placeholder="<?php echo label('username') ?>">
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <label for="inpPwd"><?php echo label('password') ?></label>
                                      <div class="premium-input-wrap premium-password-wrap">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                                        <input class="form-control" id="inpPwd" name="inpPwd" type="password" required="" autocomplete="current-password" placeholder="<?php echo label('password') ?>">
                                        <button type="button" toggle="#inpPwd" class="premium-password-toggle toggle-password" aria-label="Show or hide password">
                                          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                                        </button>
                                      </div>
                                  </div>
                              </div>
                              <input type="hidden" id="dest" name="dest" value="<?php echo $dest; ?>">
                              <div class="form-group ">
                                    <a href="javascript:void(0)" id="to-recover" class="premium-forgot-link"><?php echo label('forgot_pass'); ?></a>
                              </div>
                              <div class="form-group text-center">
                                  <div class="col-md-12  p-b-20">
                                      <button class="btn btn-block premium-login-button" id="btnlogin" type="submit">
                                        <span><?php echo label('login') ?></span>
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13M14 7l5 5-5 5"/></svg>
                                      </button>
									  <div id="login-processing" role="status" aria-live="polite" style="display:none;margin-top:12px;color:#315b86;font-weight:600;">
										<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
										<span><?php echo $lang === 'thai' ? 'กำลังตรวจสอบข้อมูล กรุณารอสักครู่...' : 'Processing, please wait...'; ?></span>
									  </div>
                                  </div>
                              </div>
                          </form>

                          <form class="form-horizontal" id="recoverform" autocomplete="off" method="POST">
							  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
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

                <div class="premium-auth-visual-column <?php if (empty($emp_c)){ ?>col-lg-8 col-md-12<?php }else{ ?>col-lg-12<?php } ?>">

                    <div class="card premium-auth-visual-card">
                      <div id="carouselExampleIndicators3" class="carousel slide carousel-fade" data-ride="carousel">
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
                                        <div class="carousel-item active premium-auth-banner-item"> <img class="img-fluid" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" onerror="this.style.display='none';this.parentNode.classList.add('premium-auth-image-missing');" alt="">
                                          <div class="premium-auth-banner-copy"><span>ISUZU E-LEARNING</span><strong><?php echo $lang === 'thai' ? 'ก้าวสู่การเรียนรู้ที่เหนือกว่า' : 'Learning that moves you forward'; ?></strong></div>
                                          <!--<div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">First title goes here</h3>
                                            <p>this is the subcontent you can use this</p>
                                          </div>-->
                                        </div>
                                        <?php }else{?>
                                        <div class="carousel-item premium-auth-banner-item"> <img class="img-fluid" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" onerror="this.style.display='none';this.parentNode.classList.add('premium-auth-image-missing');" alt="">
                                          <div class="premium-auth-banner-copy"><span>ISUZU E-LEARNING</span><strong><?php echo $lang === 'thai' ? 'ก้าวสู่การเรียนรู้ที่เหนือกว่า' : 'Learning that moves you forward'; ?></strong></div>
                                          <!--<div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">Second title goes here</h3>
                                            <p>this is the subcontent you can use this</p>
                                          </div>-->
                                        </div>
                                      <?php }$n++;}?>
                            <?php }
                                } else { ?>
                                  <div class="carousel-item active premium-auth-fallback">
                                    <div class="premium-auth-orbit" aria-hidden="true"><span></span><span></span><span></span></div>
                                    <div class="premium-auth-visual-copy">
                                      <span><?php echo $lang === 'thai' ? 'พื้นที่แห่งการเรียนรู้' : 'LEARNING WORKSPACE'; ?></span>
                                      <h2><?php echo $lang === 'thai' ? 'ขับเคลื่อนความรู้ สู่มาตรฐานมืออาชีพ' : 'Drive your knowledge forward'; ?></h2>
                                      <p><?php echo $lang === 'thai' ? 'พัฒนาทักษะ เรียนรู้ได้ทุกที่ และเติบโตไปพร้อมกัน' : 'Build skills, learn anywhere, and grow together.'; ?></p>
                                    </div>
                                  </div>
                                <?php } ?>
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
			  var $loginButton = $('#btnlogin');
			  var loginButtonHtml = $loginButton.html();
			  $loginButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo $lang === "thai" ? "กำลังเข้าสู่ระบบ..." : "Signing in..."; ?>');
			  $('#login-processing').stop(true, true).fadeIn(120);
			  $('#inpUname, #inpPwd').prop('readonly', true);
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
					$loginButton.prop('disabled', false).html(loginButtonHtml);
					$('#login-processing').hide();
					$('#inpUname, #inpPwd').prop('readonly', false);
                    if(data.status_msg=="complete"){
                        $('#loginform')[0].reset();
						window.location.href = data.redirect_val;
					}else if(data.status_msg==="first_login" || data.status_msg==="password_expired"){
						var isExpired = data.status_msg === "password_expired";
						swal({
							title: isExpired ? '<?php echo label("login_passexpire"); ?>' : '<?php echo label("login_firsttime"); ?>',
							text: isExpired ? 'กรุณาตั้งรหัสผ่านใหม่เพื่อเข้าใช้งานต่อ' : 'เพื่อความปลอดภัย กรุณาตั้งรหัสผ่านใหม่ก่อนเข้าใช้งาน',
							type: 'warning',
							allowOutsideClick: false,
							confirmButtonText: '<?php echo label("m_ok"); ?>'
						}).then(function(){ window.location.href = data.redirect_val; });
                    }else if(data.status_msg=="account_locked"){
                        swal({
                            title: '<?php echo label("account_locked"); ?>',
							text: "บัญชีถูกล็อกเพื่อความปลอดภัย กรุณาขอรีเซ็ตรหัสผ่านหรือติดต่อผู้ดูแลระบบ",
                            type: 'warning',
							showCancelButton: true,
                            confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label("forgot_pass"); ?>',
							cancelButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
							$("#loginform").hide(); $("#recoverform").fadeIn(); $("#useri").val(username);
                        })
					}else if(data.status_msg==="rate_limited"){
						swal('โปรดลองใหม่ภายหลัง', 'มีการลองเข้าสู่ระบบหลายครั้งเกินไป กรุณารอ 15 นาที หรือใช้เมนูลืมรหัสผ่าน', 'warning');
					}else if(data.status_msg==="inactive"){
						swal('ไม่สามารถเข้าใช้งานได้', 'บัญชีนี้ไม่ได้เปิดใช้งาน กรุณาติดต่อผู้ดูแลระบบขององค์กร', 'warning');
					}else if(data.status_msg==="invalid_credentials"){
						swal('เข้าสู่ระบบไม่สำเร็จ', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 'warning').then(function(){ $('#inpPwd').val('').focus(); });
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
                   
				  },
				  error:function(){
					$loginButton.prop('disabled', false).html(loginButtonHtml);
					$('#login-processing').hide();
					$('#inpUname, #inpPwd').prop('readonly', false);
					swal('ไม่สามารถเข้าสู่ระบบได้', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง', 'error');
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
