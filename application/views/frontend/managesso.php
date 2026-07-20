<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <!-- Dropzone css -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
</head>

    <!-- Dropzone css -->
    <link href="../assets/plugins/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
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
                                <h4 class="m-b-0"><?php echo label('ManageSSO') ?></h4>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('ManageSSO') ?></li>
                        </ol>
                    </div>
                </div>
                    <div class="tab-content">
                      <div class="tab-pane normal active">
                        <div class="card">
                            <form enctype="multipart/form-data" method="POST" id="sso_form" name="sso_form" class="form-horizontal p-t-20">
                            <input type="hidden" id="sso_id" name="sso_id" value="1">
                            <div class="card-body row">
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Client ID</label>
                                              <input required name="sso_client_id" id="sso_client_id" class="form-control" value="<?php echo $data_fetch["sso_client_id"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Password</label>
                                              <input required name="sso_password" id="sso_password" class="form-control" value="<?php echo $data_fetch["sso_password"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Access token</label>
                                              <input required name="sso_access_token" id="sso_access_token" class="form-control" value="<?php echo $data_fetch["sso_access_token"]; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Redirect URL</label>
                                              <input required name="sso_redirect_url" id="sso_redirect_url" class="form-control" value="<?php echo $data_fetch["sso_redirect_url"]; ?>" type="text">
                                        </div>
                                        
                                        <div class="col-md-12">
                                              <hr>
                                        </div>
                                          <div class="form-group  col-md-12">
                                              <div align="center">
                                                <div>
                                                  <button name="saveRBT" value="normal" class="btn btn-outline-success "  type="submit"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                                                  <a href="<?php echo REAL_PATH.'/setting/ManageSSO/'; ?>" class="btn btn-outline-danger cancel" ><i class="mdi mdi-close-box-outline"></i> <?php echo label('cancel') ?></a>
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
    <script src="<?php echo REAL_PATH; ?>/assets/js/userCode.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

    $(document).ready(function() {
        $('.dropify').dropify();
        
        if ($(".texteditor").length > 0) {
            tinymce.init({
                selector: "textarea.texteditor",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

            });
        }

        $(document).on('submit', '#sso_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_sso",
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