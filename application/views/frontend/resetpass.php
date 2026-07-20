<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<style type="text/css">
  
      #myModal_process.modal.show .modal-dialog{
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        margin: 0;
      }
      #myModal_process .circle strong{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:1.8em;
      }
      #myModal_process .circle canvas{
        visibility: hidden;
      }
      #myModal_process #circle-b{
        margin:0;
      }
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
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
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

                        <div class="card">
                            <div class="card-body">
                                <div class="row show-grid">
                                    <div class="col-md-6 offset-md-3">

                                      <div class="card align-self-center">
                                        <form  action="<?php echo REAL_PATH.'/dashboard/resetpass'; ?>" autocomplete="off" enctype="multipart/form-data" name="resetForm" method="POST"  class="form-inline p-t-20">
                                        <div class="card-body">
                                                    <div class="form-group row">
                                                          <div class="col-sm-4" align="right">
                                                            <label for="inputCName" class="control-label"><?php echo label('username').": "; ?></label>
                                                          </div>
                                                          <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="useri" placeholder="<?php echo label('pholder_usn') ?>"><br>
                                                            <span id="emp-error"></span>
                                                          </div>
                                                          <div class="col-md-12 text-center" align="center"><br>
                                                            <b> <b style="color: #FF2D00">*</b> <?php echo label('resetpass_msg'); ?> </b><br><br>
                                                            <a href="javascript:void(0);" class="submit-reset btn btn-outline-info"> <?php echo ucwords(label('resetpass')) ?> </a>
                                                            <button  value="done" style="position:absolute;z-index:-100;left:0;top:0;" type="submit" name="submit-reset" class=" btn btn-outline-info"><i style="padding:0px;" class="fa fa-sign-in" aria-hidden="true"></i> <?php echo ucwords(label('resetpass')); ?></button>
                                                          </div>
                                                    </div>
                                        </div>
                                        </form>
                                      </div>

                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <div
        id="myModal_process"
        class="modal bs-example-modal-lg"
        role="dialog"
        aria-labelledby="smallModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body" align="center" style="max-height: 300px;">
              <div class="circle" id="circle-b">
                <strong></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <script type="text/javascript">
        $('.submit-reset').click( function(){
            //$('#emp-form').addClass('has-success');
            $('#emp-error').removeClass('text-danger');
            //$('#emp-error').addClass('text-success');
            var user = $('input[name="useri"]').val();
            if( user != "" ){
              $.ajax({
                method: 'post',
                url: base_url + '/manage/checkUser',
                data: {'user': user},
                async: false,
                dataType: 'json',
                                xhr: function() {
                                      var xhr = new window.XMLHttpRequest();
                                      xhr.upload.addEventListener("progress", function(evt) {
                                          if (evt.lengthComputable) {
                                              var percentComplete = (evt.loaded / evt.total) * 100;
                                                    var progressBarOptions = {
                                                      startAngle: -1.55,
                                                      size: 200,
                                                        value: percentComplete.toFixed(0),
                                                        fill: {
                                                        color: '#ffa500'
                                                      }
                                                    }
                                                    console.log(percentComplete.toFixed(0));
                                                    $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                                      $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                                    });

                                                    $('#circle-b').circleProgress({
                                                      value : percentComplete.toFixed(0),
                                                      fill: {
                                                        color: '#FF0000'
                                                      }
                                                    });
                                          }
                                     }, false);
                                     return xhr;
                                },
                success: function(response) {
                  if(response.text === 'TRUE') {
                    i_user = true;
                    $('#emp-error').addClass('text-danger');
                    $('#emp-error').html('<?php echo label('permisson_password'); ?>');
                  }else if(response.text =="EMPTY"){
                    i_user = true;
                    $('#emp-error').addClass('text-danger');
                    $('#emp-error').html('<?php echo label('datauser_notfound'); ?>');
                  }else {
                    $("#myModal_process").modal('show');
                    $( document.body ).css( 'pointer-events', 'none' );
                    $.ajax({
                      method: 'post',
                      url: base_url + '/dashboard/resetPassSubmit_page',
                      data: {'useri': user},
                      async: false,
                      dataType: 'json',
                                xhr: function() {
                                      var xhr = new window.XMLHttpRequest();
                                      xhr.upload.addEventListener("progress", function(evt) {
                                          if (evt.lengthComputable) {
                                              var percentComplete = (evt.loaded / evt.total) * 100;
                                                    var progressBarOptions = {
                                                      startAngle: -1.55,
                                                      size: 200,
                                                        value: percentComplete.toFixed(0),
                                                        fill: {
                                                        color: '#ffa500'
                                                      }
                                                    }
                                                    console.log(percentComplete.toFixed(0));
                                                    $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                                      $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                                    });

                                                    $('#circle-b').circleProgress({
                                                      value : percentComplete.toFixed(0),
                                                      fill: {
                                                        color: '#FF0000'
                                                      }
                                                    });
                                          }
                                     }, false);
                                     return xhr;
                                },
                      success: function(response) {
                                    $( document.body ).css( 'pointer-events', '' );
                                    $('#myModal_process').modal('hide');
                                    
                                    $("#myModal_process").removeClass("in");
                                    $("#myModal_process").css("display","none");
                                    $('input[name="useri"]').val('');
                        if(response.rs) {
                                        swal(
                                            '<?php echo label("reset_pass_success"); ?>!',
                                            '',
                                            'success'
                                        )
                          /*$('#emp-error').addClass('text-success');
                          $('#emp-error').html('<?php echo label("reset_pass_success"); ?>');*/
                        }else {
                                        swal(
                                            response.msg,
                                            '',
                                            'warning'
                                        )
                          /*$('#emp-error').addClass('text-danger');
                          $('#emp-error').html(response.msg);*/
                        }
                      },
                      error: function(){
                                    $( document.body ).css( 'pointer-events', '' );
                                    $('#myModal_process').modal('hide');
                                    
                                    $("#myModal_process").removeClass("in");
                                    $("#myModal_process").css("display","none");
                        //alert('Error: Cannot validate! 1');
                      }
                    });
                  }
                },
                error: function(){
                  alert('Error: Cannot validate! 2');
                }
              });
            }else{
              $('#emp-error').addClass('text-danger');
              $('#emp-error').html('<?php echo label("pholder_usn"); ?>');
            }
            //return false;
            //$('#emp-error').html(response.text);
          });
    </script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <!-- <script src="<?php echo REAL_PATH;?>/assets/js/login.js?v=<?php rand(1000,1000); ?>"></script> -->
</body>

</html>