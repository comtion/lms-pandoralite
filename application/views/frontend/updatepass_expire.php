<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); 
    $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
    $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <style type="text/css">
      iframe {
          width: 100%;
          height: 100%;
      }

      .myclass {
        color: red;
        font-size: 12px;
      }

      iframe.fullScreen {
          width: 100%;
          height: 100%;
          position: absolute;
          top: 0;
          left: 0;
      }
      table#myTable_lesson.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_lesson.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_quiz.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_quiz.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_cos_id_survey.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_cos_id_survey.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      table#myTable_document_ddd.dataTable tbody tr:hover {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
       
      table#myTable_document_ddd.dataTable tbody tr:hover > .sorting_1 {
        color: #fff;
        background-color: #f0932b;
        cursor: pointer;
      }
      .playbutton:hover {
        color: #f0932b;
      }
      <?php if(isMobile()){ ?>
        #div_menu {
            width:100%;
            z-index:2;
            position:fixed;
            left:100%;
            top: 100px;
            background-color: #fff;
        }
        #x {
            position: absolute;
            top: -20px;
            right: 5px;
        }
        #onclose_divmenu_btn{
            position: absolute;
            top: 0px;
            left: -50px;
        }
      <?php } ?>

      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        margin-right: 20px;
        position: relative;
        z-index: 2;
      }
    </style>
    <style>
    .pdfobject-container { height: 33rem; border: 1rem solid rgba(0,0,0,.1); }
    </style>
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
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid">

                <div class="row col-12 page-titles">
                    <div class="col-md-6 offset-md-3 card">
                        <div class="card-body">
                            <form method="post" id="changepass_form" autocomplete="off" name="changepass_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="oldpass"><b style="color: #FF2D00">*</b> <?php echo label('oldpass'); ?>:</label>
                                        <input type="text" id="oldpass" required onkeyup="validPassOld()" name="oldpass" class="form-control"> 
                                        <span toggle="#oldpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span id="txt_oldpass" style="color: #c0392b;"></span>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="newpass"><b style="color: #FF2D00">*</b> <?php echo label('newpass'); ?>:</label>
                                        <input type="text" id="newpass" required onkeyup="validPassRechk()" name="newpass" class="form-control"> 
                                        <span toggle="#newpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span id="txt_newpass" style="color: #c0392b;"></span>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="confirmpass"><b style="color: #FF2D00">*</b> <?php echo label('confirmpass'); ?>:</label>
                                        <input type="text" id="confirmpass" required onkeyup="validPassRechk()" name="confirmpass" class="form-control"> 
                                        <span toggle="#confirmpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span id="txt_confirmpass" style="color: #c0392b;"></span>
                                    </div>
                                  </div>
                                </div>
                                <hr>
                                <input type="hidden" id="chkold" name="chkold">
                                <input type="hidden" id="password_original" name="password_original" value="<?php echo $userdata['userp']; ?>">
                                <input type="hidden" id="u_id" name="u_id" value="<?php echo $userdata['u_id']; ?>">
                                <input type="hidden" id="useri" name="useri" value="<?php echo $userdata['useri']; ?>">
                                <div class="col-md-12">
                                    <center><button type="submit" id="action" name="action" class="btn btn-success btn-flat"><i class="mdi mdi-check"></i> <?php echo label('confirm'); ?></button></center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>
        </div>
    </div>

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>



    <script type="text/javascript">
      var base_url = '<?php echo base_url(); ?>';
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/sha256.min.js"></script>

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
        <?php if(date('Y-m-d H:i',strtotime($userdata['expiredate']))>date('Y-m-d H:i')){ ?>
                        swal({
                                    title: '<?php echo label("lrn_p_data_not_found"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("ok"); ?>'
                        }).then(function () {
                                  window.location.href = '<?php echo base_url(); ?>';
                        })
        <?php } ?>
        $(document).on('submit', '#changepass_form', function(event){
                var password_original = $('#password_original').val();
                var oldpass = sha256($('#oldpass').val());
                var newpass = sha256($('#newpass').val());
                var confirmpass = sha256($('#confirmpass').val());
                event.preventDefault(); 
                if(password_original!=oldpass){
                        swal({
                                    title: '<?php echo label("oldpass_change"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("ok"); ?>'
                        }).then(function () {
                                  $('#oldpass').val('');
                        })
                }else{
                    if(newpass==confirmpass){

                        $.ajax( '<?php echo base_url();?>'+'dashboard/update_password', {
                            type: 'POST',
                            dataType: 'json',
                            data:new FormData(this),
                            contentType:false,
                            processData:false,
                            success: function(result){
                              if(result.rs){
                                swal(
                                                '<?php echo label("change_pass_success"); ?>',
                                                '',
                                                'success'
                                ).then(function () {
                                      window.location.href = '<?=base_url()?>index.php/dashboard/logout';
                                });
                              }else{
                                /*var msg_noti = "<?php echo label("al_log_not_found"); ?>";
                                if(result.msg=="055"){*/
                                var msg_noti = "<?php echo label("duplicate_pass_b"); ?>";
                                //}
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
                                    title: '<?php echo label("confirmpass_change"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("ok"); ?>'
                        }).then(function () {
                                  $('#confirmpass').val('');
                        })
                    }
                }
            });
        $('#action').hide();
        function validPassOld(){
            var password_original = $('#password_original').val();
            var oldpass = sha256($('#oldpass').val());
            $('#txt_oldpass').text('');  
            if(password_original!=oldpass){
              $('#txt_oldpass').text('<?php echo label("oldpass_change"); ?>');  
            }
        }
        function validPassRechk(){
          var check = true;
          var checkconfirm = true;
          $oldpass = $('input[name="oldpass"]');
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
          }else if($newpass.val()==$oldpass.val()){
            //$newpass.css({border:'1px solid red'});
            txt_newpass = "<?php echo label("duplicate_pass_b"); ?>";
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
              txt_newpass = txt_newpass + " <?php echo label("and"); ?> ";
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
              txt_confirmpass = txt_confirmpass + " <?php echo label("and"); ?> ";
            }
            txt_confirmpass = txt_confirmpass + "<?php echo label("charlargeandnumber"); ?>";
            checkconfirm = false;
          }
          $('#txt_newpass').html(txt_newpass);  
          $('#txt_confirmpass').html(txt_confirmpass);  
          if(check&&checkconfirm){
            $('#action').show();
            check = true;
          }else{
            $('#action').hide();
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
              }else
              {
                  return false;
              }
            }
    </script>
</body>

</html>
