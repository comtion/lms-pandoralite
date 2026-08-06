<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>

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
      <div class="page-wrapper"> 
        <!-- ============================================================== --> 
        <!-- Container fluid  --> 
        <!-- ============================================================== -->
        <div class="container-fluid"> 
          <div class="row">
              <div class="col-md-6 offset-md-3" style="<?php if (!empty($emp_c)){ ?>display:none;<?php } ?>">
                <div class="card">
                  <div class="card-body">
                    
						<form class="form-horizontal form-material" autocomplete="off" id="loginform" method="POST">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <h3 class="box-title m-b-20" style="font-family: 'Prompt', sans-serif;">
                                <?php echo label('login'); ?>
                            </h3>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" onkeyup="return forceLower(this);" id="inpUname" name="inpUname" type="text" required="" autofocus placeholder="<?php echo label('username') ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" id="inpPwd" name="inpPwd" type="password" required="" placeholder="<?php echo label('password') ?>"> 
                                    <span toggle="#inpPwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    <input type="hidden" id="grant_type" name="grant_type" value="client_credentials">
                                    <input type="hidden" id="scope" name="scope" value="view_profile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                </div>
                                <div class="col-md-7">
                                    <div class="checkbox checkbox-info float-left">
                                    </div> <a id="to-recover" class="text-muted float-right"><i class="fa fa-lock"></i> <?php echo label('forgot_pass'); ?></a> 
                                </div>
                            </div>
                            <div class="form-group text-center">
									<button class="btn btn-thai_h" id="btnlogin" type="submit"><i class="icon-login"></i> <?php echo label('login') ?></button>
									<div id="login-processing" role="status" aria-live="polite" style="display:none;margin-top:12px;color:#315b86;font-weight:600;">
										<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing, please wait...
									</div>
                            </div>
                        </form>
                        <?php 
              /*function getContentUrl($url) {
                $ch = curl_init($url);
                $fields = array(
                    'Username' => 'yupontee.k@verztec.com', // array key corresponds to the name of a field on your form
                    'Password' => 'Verztec123TH',
                    'grant_type' => 'client_credentials',
                    'scope' => 'token,user',
                );

                $data = http_build_query($fields);
                $decdata = "THELearning:VhacfxOoU1sfL668";
                $decdata = utf8_encode($decdata);
                //$sha1 = sha1($decdata, TRUE);
                //$hash = hash_hmac( "sha256", $decdata, true );
                $raw = base64_encode($decdata);
                echo $raw;
                $headers = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Basic '.$raw,
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
              }
              $arr = getContentUrl('https://sso-api-uat.thaihealth.or.th:9100/oauth2/token');
              $output = json_decode($arr,true);
              print_r($output);
              echo "<br>";
              function getContentUrl_userdata($url,$output) {
                $ch = curl_init($url);
                $fields = array(
                    'code' => 'IxwqjvibNUq4j5xRrPrgRf6U3X3UNquR-7XzOvGV6oc=', //IxwqjvibNUq4j5xRrPrgRf6U3X3UNquR-7XzOvGV6oc=
                    'redirect_uri' => 'https://thaihealth.pandoralms.com/home',
                    'grant_type' => 'authorization_code',
                    'client_id' => 'THELearning',
                );

                $data = http_build_query($fields);
                $decdata = "THELearning:VhacfxOoU1sfL668";
                $decdata = utf8_encode($decdata);
                //$sha1 = sha1($decdata, TRUE);
                //$hash = hash_hmac( "sha256", $decdata, true );
                $raw = base64_encode($decdata);
                //echo $raw;
                $headers = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer '.$output['access_token']
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
              }
              $arr_user = getContentUrl_userdata('https://sso-uat.thaihealth.or.th/api/token/validate',$output);
              $output_user = json_decode($arr_user,true);
              print_r($output_user);
              echo "<br>";
              function getContentUrl_sessionthree($url,$output) {
                $ch = curl_init($url);
                $decdata = "THELearning:VhacfxOoU1sfL668";
                $decdata = utf8_encode($decdata);
                //$sha1 = sha1($decdata, TRUE);
                //$hash = hash_hmac( "sha256", $decdata, true );
                $raw = base64_encode($decdata);
                //echo $raw;
                $headers = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer '.$output['access_token'],
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
              }
              echo date('Y-m-d',strtotime($output_user['created_at']));
              $arr_user_detail = getContentUrl_sessionthree('https://sso-uat.thaihealth.or.th/api/userrole/'.$output_user['uid'].'/client/THELearning',$output);
              $output_user_detail = json_decode($arr_user_detail,true);
              print_r($output_user_detail);*/
                //https://sso-api-uat.thaihealth.or.th:9100/oauth2/token
              //,'"Authorization": {THELearning}:{VhacfxOoU1sfL668}'
              /*function getContentUrl($url) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
              }
              $arr = getContentUrl('https://sso-uat.thaihealth.or.th/api/user/jetsada.d@verztec.com');
              $output = json_decode($arr,true);
              print_r($output);*/
                        ?>
						<form class="form-horizontal" id="recoverform" style="display: none;" autocomplete="off" method="POST">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="form-group ">
                                <div class="col-sm-12">
                                    <h3 style="font-family: 'Prompt', sans-serif;"><?php echo label('forgot_pass'); ?></h3>
                                    <p class="text-muted"><?php echo label('forgot_pass_noti'); ?></p>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="useri" placeholder="<?php echo label('pholder_usn') ?>"> </div>
                            </div>
                            <div class="form-group text-center m-t-20">
                                <div class="col-sm-12">
                                    <button type="reset" value="Reset" class="btn btn-outline-info pull-right return_login"><i class=" fas fa-chevron-circle-left"></i><span> <?php echo label('m_previous'); ?></span></button>
                                    <button class="btn btn-outline-danger text-uppercase waves-effect waves-light" type="submit"><?php echo label('m_ok'); ?></button>
                                </div>
                            </div>
                        </form>
                  </div>
                </div>
              </div>

          </div>
      </div>
    </div>
    
    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-detailcourse" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-height: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                      <div class="card-body d-flex flex-column">
                        <div class="row">
                          <div class="col-md-5">
                              <img class="img-responsive" id="img_coursehead" width="100%" alt="">
                              <div id="rating_course" class="float-right"></div>
                          </div>
                          <div class="col-md-7">
                              <label id="txt_coursehead" style="font-size: 23px"></label><hr>
                              <div id="description_course" class="example-1 square scrollbar-cyan bordered-cyan" style="height: 250px;"></div>
                          </div>
                        </div>
                      </div>
                      <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-register" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id=""><i class="mdi mdi-account-plus"></i> <?php echo label('register'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form  enctype="multipart/form-data" id="register_form" name="register_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                <div class="modal-body">

                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><?php echo label('m_user_information'); ?></span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down"><?php echo label('m_general_information'); ?></span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-20" id="home2" role="tabpanel">
                                	<div class="row">
			                          <div class="col-md-6">
			                            <div class="form-group">
			                              <label for="useri"> <?php echo label('m_username'); ?> : <span class="danger">*</span> </label>
			                              <input type="text" class="form-control" required maxlength="15" onkeyup="return forceLower(this);" id="useri" name="useri"> 
			                            </div>
			                          </div>
			                          <div class="col-md-6">
			                            <div class="form-group">
			                              <label for="email"> <?php echo label('m_mail'); ?> : <span class="danger">*</span> </label>
			                              <input type="text" class="form-control" required id="email" name="email"> 
			                            </div>
			                          </div>
                                	</div>
                                </div>
                                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                                	<div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prefix_th"> <?php echo label('m_prefix')." [TH]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="prefix_th" name="prefix_th"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fname_th"> <?php echo label('m_fname')." [TH]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="fname_th" name="fname_th"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lname_th"> <?php echo label('m_lname')." [TH]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="lname_th" name="lname_th"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prefix_en"> <?php echo label('m_prefix')." [EN]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="prefix_en" name="prefix_en"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fname_en"> <?php echo label('m_fname')." [EN]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="fname_en" name="fname_en"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lname_en"> <?php echo label('m_lname')." [EN]"; ?> : <span class="danger">*</span> </label>
                                                    <input type="text" class="form-control " required id="lname_en" name="lname_en"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="work_phone"> <?php echo label('m_workphone'); ?> : </label>
                                                    <input type="text" class="form-control" id="work_phone" name="work_phone"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phone"> <?php echo label('m_phone'); ?> : </label>
                                                    <input type="text" class="form-control" id="phone" name="phone"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="gender"> <?php echo label('m_gender'); ?> : </label>
                                                    <select class="custom-select form-control" id="gender" name="gender">
                                                        <option value="Male" selected><?php echo label('m_male'); ?></option>
                                                        <option value="Female"><?php echo label('m_female'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                	</div>
                                </div>
                            </div>

                </div>
                <input type="hidden" id="operation" name="operation" value="Add">
                <div class="modal-footer">
                    <input type="submit" name="action" id="action" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('save_data'); ?>" />
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
                </div>
              </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

      <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo REAL_PATH;?>/assets/js/perfect-scrollbar.jquery.min.js"></script>
    <script type="text/javascript">

                /*$.ajax({
                  url:"https://sso-uat.thaihealth.or.th/api/token/validate",
                  method:'POST',
                  dataType: 'json',
                  data: {code:"s8oczKbXXvx2jcHqGKIQdgL-CMMa4A2HMpHSDRyN7NI=",grant_type:"authorization_code",client_id:"THELearning"},
                  success:function(data)
                  {
                    console.log("Line 797: "+data);
                  }
                });*/
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
    document.getElementById('recoverform').style.display = "none";
      $(document).ready(function() {
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
                    console.log(data);
                    if(data=="2"){
                        $('#register_form')[0].reset();
                        $('#modal-register').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
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
                console.log(data);
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
            console.log("Line:568||"+rating_course);
            var str = 'Rating : ';
            for (var i = 1; i <=parseInt(rating_course); i++) {
              str += '<i class="fa fa-star text-warning"></i>';
            }
            for (var i = 1; i <=(5-parseInt(rating_course)); i++) {
              str += '<i class="fa fa-star text-default"></i>';
            }
            $('#rating_course').html(str);
         }
         function run_analytic(cos_id=''){
            console.log(cos_id);
            window.location.href = '<?=base_url()?>course/analytic_course/'+cos_id;
         }

         $(document).on('click', '.btn_detail', function(){
            var id = $(this).attr("id");
            console.log('<?=base_url()?>index.php/course/detail/'+id);
            window.location.href = '<?=base_url()?>index.php/course/detail/'+id+'/<?php echo "1"; ?>';
          });
          $(document).on('submit', '#recoverform', function(event){
              event.preventDefault(); 
              var username = $('#useri').val();
              console.log('line : 525');
                $.ajax({
                  url:"<?=base_url()?>index.php/dashboard/resetPassSubmit",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:'json',
                  success:function(data)
                  {
                    console.log(data);
                    if(data.rs==true){
                        $('#recoverform')[0].reset();
                                    swal(
                                        data.msg,
                                        '',
                                        'success'
                                    ).then(function () {
                                        window.location.href = '<?php echo base_url()."contact/form_chk/"; ?>'+username+'/';
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
          $(document).on('submit', '#loginform', function(event){
              event.preventDefault(); 
              var username = $('#inpUname').val();
              var password = $('#inpPwd').val();
			  var $loginButton = $('#btnlogin');
			  var loginButtonHtml = $loginButton.html();
			  $loginButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			  $('#login-processing').stop(true, true).fadeIn(120);
			  $('#inpUname, #inpPwd').prop('readonly', true);
                $.ajax({
                  url:"<?=base_url()?>index.php/dashboard/chk_login",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:"json",
                  success:function(data)
                  {
					$loginButton.prop('disabled', false).html(loginButtonHtml);
					$('#login-processing').hide();
					$('#inpUname, #inpPwd').prop('readonly', false);
                    if(data.status_msg=="complete"){
                        $('#loginform')[0].reset();
						window.location.href = data.redirect_val;
					}else if(data.status_msg==="first_login" || data.status_msg==="password_expired"){
						swal({
							title: data.status_msg === "password_expired" ? '<?php echo label("login_passexpire"); ?>' : '<?php echo label("login_firsttime"); ?>',
							text: 'กรุณาตั้งรหัสผ่านใหม่เพื่อเข้าใช้งานต่อ', type: 'warning', confirmButtonText: '<?php echo label("m_ok"); ?>'
						}).then(function(){ window.location.href = data.redirect_val; });
                    }else if(data.status_msg=="account_locked"){
                        swal({
                            title: '<?php echo label("account_locked"); ?>',
							text: "บัญชีถูกล็อก กรุณาใช้เมนูลืมรหัสผ่านหรือติดต่อผู้ดูแลระบบ",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
							$("#loginform").hide(); $("#recoverform").fadeIn(); $("#useri").val(username);
                        })
					}else if(data.status_msg==="rate_limited"){
						swal('โปรดลองใหม่ภายหลัง', 'มีการลองเข้าสู่ระบบหลายครั้งเกินไป กรุณารอ 15 นาที', 'warning');
					}else if(data.status_msg==="invalid_credentials"){
						swal('เข้าสู่ระบบไม่สำเร็จ', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 'warning');
					}else if(data.status_msg==="inactive"){
						swal('ไม่สามารถเข้าใช้งานได้', 'กรุณาติดต่อผู้ดูแลระบบ', 'warning');
                    }else if(data.status_msg=="login_failed_4_time"){
                        swal({
                            title: '<?php echo label("login_failed_4_time"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            window.location.href = '<?php echo REAL_PATH; ?>/home/backoffice';
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
                            window.location.href = '<?php echo REAL_PATH; ?>/home/backoffice';
                        })
                    }
                   
				  },
				  error:function(){
					$loginButton.prop('disabled', false).html(loginButtonHtml);
					$('#login-processing').hide();
					$('#inpUname, #inpPwd').prop('readonly', false);
					swal('Login failed', 'Unable to connect to the server. Please try again.', 'error');
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
                        console.log(data);
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
         /*$(document).on('click', '.btn_register', function(){
            var id = $(this).attr("id");
            var enroll_seat = '<?php echo $courses['enroll_seat'] ?>';
            var seat_count = '<?php echo $courses['seat_count'] ?>';
            
          });*/
    </script>
  </body>
</html>
