<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <!-- Dropzone css -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
</head>

    <!-- Dropzone css -->
    <link href="../assets/plugins/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        margin-right: 20px;
        position: relative;
        z-index: 2;
      }
    </style>
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
                    <div class="tab-content">
                      <div class="tab-pane normal active">
                        <div class="card">
                            <form enctype="multipart/form-data" method="POST" id="setting_email_form" name="setting_email_form" class="form-horizontal p-t-20">
                            <div class="card-body row">
                                        <div class="form-group col-md-4">
                                              <label class="control-label text-right">HOST:</label>
                                              <input required name="sm_host" id="sm_host" class="form-control" value="<?php echo $data_fetch["sm_host"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-4">
                                              <label class="control-label text-right">PORT:</label>
                                              <input required name="sm_port" id="sm_port" class="form-control" value="<?php echo $data_fetch["sm_port"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-4">
                                              <label class="control-label text-right">SMTP Auth:</label>
                                              <select required name="sm_smtpauth" id="sm_smtpauth" class="form-control">
                                                <option value="true" <?php if($data_fetch['sm_smtpauth']=="true"){ echo "selected";} ?>>TRUE</option>
                                                <option value="false" <?php if($data_fetch['sm_smtpauth']=="false"){ echo "selected";} ?>>FALSE</option>
                                              </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Username:</label>
                                              <input required name="sm_username" id="sm_username" class="form-control" value="<?php echo $data_fetch["sm_username"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label text-right">Password:</label>
                                            <input required name="sm_password" id="sm_password" class="form-control" value="<?php echo $data_fetch["sm_password"]; ?>" type="password">
                                            <span toggle="#sm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label text-right">Sender name:</label>
                                            <input required name="sm_sender" id="sm_sender" class="form-control" value="<?php echo $data_fetch["sm_sender"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Sender e-mail:</label>
                                              <input required name="sm_emailsender" id="sm_emailsender" class="form-control" value="<?php echo $data_fetch["sm_emailsender"]; ?>" type="text">
                                        </div>
                                        <hr>
                                        <input type="hidden" id="sm_id" name="sm_id" value="1">
                                          <div class="form-group  col-md-12">
                                              <div align="center">
                                                <div>
                                                  <button name="saveRBT" value="normal" class="btn btn-outline-success return"  type="submit"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                                                  <a href="<?php echo REAL_PATH.'/setting/setting_email/'; ?>" class="btn btn-outline-danger cancel "><i class="mdi mdi-close-box-outline"></i> <?php echo label('cancel') ?></a>
                                                </div>
                                              </div>
                                          </div>
                              </form>
                              <hr>

                            </div>
                        </div>
                      </div>
                    </div>

            </div>
        </div>
    </div>

            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- Dropzone Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/dropzone-master/dist/dropzone.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
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
    $(document).ready(function() {

        $(document).on('submit', '#setting_email_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_settingemail",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("com_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#com_name").val("");
                            document.getElementById("com_name").focus();
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
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

    </script>
</body>

</html>