<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
    <!-- page css -->
    <link href="<?php echo REAL_PATH;?>/assets/css/pages/login-register-lock.css" rel="stylesheet">
  </head>
  <body class="card-no-border">

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
    <section id="wrapper">
        <div class="login-register" style="background-image:url(<?php echo REAL_PATH;?>/assets/images/background/login-register.jpg);height: 100%; background-position: center;background-repeat: no-repeat;background-size: cover;">
            <div class="login-box card">
                <div class="card-body">
                      <?php if( $this->session->userdata("passexpire") ){ ?>
                        <h2 style="color:#555;padding: 10px 0;font-size: 16px; width:100%;text-align:center;"><?php echo label('login_passexpire'); ?></h2>
                      <?php }else{ ?>
                        <h2 style="color:#555;padding: 10px 0;font-size: 16px; width:100%;text-align:center;"><?php echo label('login_firsttime'); ?></h2>
                      <?php } ?>
                    <form class="form-horizontal form-material" id="form_updatepass" autocomplete="off" method="POST">
                        <div class="form-group ">
                            <div class="col-sm-12" id="newpass_div">
                                <label for="newpass"><b style="color: #FF2D00">*</b><?php echo label('newpass'); ?>:</label>
                                <input class="form-control" id="newpass" name="newpass" onkeyup="validPassRechk()" type="text" required> 
                                <span toggle="#newpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <span id="txt_newpass" style="color: #c0392b;"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" id="confirm_div">
                                <label for="confirmpass"><b style="color: #FF2D00">*</b><?php echo label('confirmpass'); ?>:</label>
                                <input class="form-control" id="confirmpass" name="confirmpass" onkeyup="validPassRechk()" type="text" required> 
                                <span toggle="#confirmpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <span id="txt_confirmpass" style="color: #c0392b;"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <p style="color:red;"><?php echo label('passnote') ?></p>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-sm-12 p-b-20">
                                <button class="btn btn-block btn-outline-success  pass-confirm" type="submit"><i class="icon-login"></i> <?php echo label('confirm') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
      var base_url = '<?php echo base_url(); ?>';
    </script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
<!--     <script src="<?php echo REAL_PATH;?>/assets/plugins/bootstrap/js/popper.min.js"></script> -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">


      $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
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
        $(document).on('submit', '#form_updatepass', function(event){
            event.preventDefault(); 
            var newpass = $('#newpass').val();
            var confirmpass = $('#confirmpass').val();
            if(newpass==confirmpass){
                $.ajax( '<?php echo base_url();?>'+'dashboard/updatePass', {
                    type: 'POST',
                    dataType: 'json',
                    data:  { newpass : $('input[name="newpass"]').val() } ,
                    success: function(result){
                      if(result.rs){
                        swal(
                                        '<?php echo label("change_pass_success"); ?>',
                                        '',
                                        'success'
                        ).then(function () {
                                        window.location.href = '<?php echo base_url()."index.php/dashboard/login"; ?>';
                        });
                      }else{
                        var msg_noti = "<?php echo label("duplicate_pass_b"); ?>";
                        swal({
                                    title: msg_noti,
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                                    //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                        })
                      }
                    }
                  });
            }else{
                swal({
                            title: '<?php echo label("msg_noti_updatepass"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                }).then(function () {
                            //window.location.href = '<?php echo base_url()."dashboard/login"; ?>';
                })
            }
        });
        function validPass(){
          var check = true;
          $newpass = $('input[name="newpass"]');
          $confirmpass = $('input[name="confirmpass"]');

          if( $newpass.val() == "" ){
            $newpass.css({border:'1px solid red'});
            check = false;
          }else if( $newpass.val().length < 8 ){
            $newpass.css({border:'1px solid red'});
            check = false;
          }else if( !checkCase( $newpass.val() ) ){
            $newpass.css({border:'1px solid red'});
            check = false;
          }

          if( $confirmpass.val() == "" ){
            $confirmpass.css({border:'1px solid red'});
            check = false;
          }else if( $confirmpass.val() !== $newpass.val() ){
            $confirmpass.css({border:'1px solid red'});
            check = false;
          }
          return check;
        }
        function validPassRechk(){
          var check = true;
          var checkconfirm = true;
          $newpass = $('input[name="newpass"]');
          $confirmpass = $('input[name="confirmpass"]');
          //$newpass.css({border:'2px solid #ccc'});
          //$confirmpass.css({border:'2px solid #ccc'});
          var txt_newpass = "";
          var txt_confirmpass = "";
          $('#txt_newpass').text('');
          $('#txt_confirmpass').text('');  
          if( $newpass.val() == "" ){
            //$newpass.css({border:'1px solid red'});
            txt_newpass = "<?php echo label("pholder_newpassword"); ?>";
            check = false;
          }else if( $newpass.val().length < 8 ){
            //$newpass.css({border:'1px solid red'});
            txt_newpass = "<?php echo label("password_if1"); ?>";
            check = false;
          }else{
            if( $confirmpass.val() == "" ){
              //$confirmpass.css({border:'1px solid red'});
              txt_confirmpass = "<?php echo label("pholder_confirmpassword"); ?>";
              checkconfirm = false;
            }else if( $confirmpass.val() !== $newpass.val() ){
              //$confirmpass.css({border:'1px solid red'});
              txt_confirmpass = "<?php echo label("dontmatch"); ?>";
              checkconfirm = false;
            }
          }
          if(checkCase( $newpass.val() )){
            check = true;

            if(txt_newpass!=""){
              check = false;
            }
          }else{
            if(txt_newpass!=""){
              <?php if($lang!="japan"){ ?>
              txt_newpass = txt_newpass + " <?php echo label("and"); ?> ";
              <?php } ?>
            }
            txt_newpass = txt_newpass + "<?php echo label("charlargeandnumber"); ?>";
            check = false;
          }
          if(checkCase( $confirmpass.val() )){
            checkconfirm = true;

            if(txt_confirmpass!=""){
              checkconfirm = false;
            }
          }else{
            if(txt_confirmpass!=""){
              <?php if($lang!="japan"){ ?>
              txt_confirmpass = txt_confirmpass + " <?php echo label("and"); ?> ";
              <?php } ?>
            }
            txt_confirmpass = txt_confirmpass + "<?php echo label("charlargeandnumber"); ?>";
            checkconfirm = false;
          }
          $('#txt_newpass').text(txt_newpass);  
          $('#txt_confirmpass').text(txt_confirmpass);  
          if(check&&checkconfirm){
            $('.pass-confirm').show();
            check = true;
          }else{
            $('.pass-confirm').hide();
            check = false;
          }
          return check;
        }
        function checkCase( str ){
          var upperCase= new RegExp('[A-Z]');
          //var lowerCase= new RegExp('[a-z]');
          var numbers = new RegExp('[0-9]');

          if( str.match(upperCase) &&  str.match(numbers) )
          {
            return true;
              //$("#passwordErrorMsg").html("OK")
          }
          else
          {
              return false;
              //$("#passwordErrorMsg").html("Your password must be between 6 and 20 characters.     It must contain a mixture of upper and lower case letters, and at least one number or symbol.");
          }
        }

    </script>
  </body>
</html>
