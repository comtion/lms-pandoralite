<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Pandora Learning Management System</p>
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
                <br><br>
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                      <?php foreach ($langs as $each) { ?>
                        <input required class="lang_tab" value="<?php echo $each['lang']; ?>" id="tab_<?php echo $each['lang']; ?>" type="radio" name="tabs" <?php echo ($each['lang'] == $lang_tab) ? "checked": ""; ?>>
                        <label class="each_label" id="sh_<?php echo $each['lang']; ?>" for="tab_<?php echo $each['lang']; ?>"><?php echo label($each['lang']); ?></label><?php
                      } ?>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo strpos($page, 'create') ?  label('create') : label('edit') ; echo label('workgroup') ?></li>
                        </ol>
                    </div>
                </div>
                    <div class="tab-content">
                      <div class="tab-pane normal active">
                        <div class="card">
                            <div class="card-header bg-default">
                                <h4 class="m-b-0"><?php echo strpos($page, 'create') ?  label('create') : label('edit') ; echo label('workgroup') ?></h4>
                            </div>
                            <form enctype="multipart/form-data" method="POST" id="about_form" name="about_form" class="form-horizontal p-t-20">
                            <input type="hidden" id="da_id" name="da_id" value="1">
                            <div class="card-body row">
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Title name TH</label>
                                              <input required name="da_title_th" id="da_title_th" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_title_th; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Title name EN</label>
                                              <input required name="da_title_en" id="da_title_en" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_title_en; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Company name TH</label>
                                              <input required name="da_company_th" id="da_company_th" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_company_th; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Company name EN</label>
                                              <input required name="da_company_en" id="da_company_en" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_company_en; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label text-right">Address TH</label>
                                            <textarea  value="<?php echo $da_address_th ?>" name="da_address_th" id="da_address_th" rows="10" cols="80"><?php echo $wdesc; ?></textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label text-right">Address EN</label>
                                            <textarea  value="<?php echo $da_address_en ?>" name="da_address_en" id="da_address_en" rows="10" cols="80"><?php echo $wdesc; ?></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Phone number</label>
                                              <input required name="da_contact_main" id="da_contact_main" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_contact_main; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Fax</label>
                                              <input required name="da_contact_fax" id="da_contact_fax" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_contact_fax; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">E-Mail A</label>
                                              <input required name="da_email_a" id="da_email_a" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_email_a; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">E-Mail B</label>
                                              <input required name="da_email_b" id="da_email_b" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_email_b; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Website</label>
                                              <input required name="da_website" id="da_website" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_website; ?>" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                              <label class="control-label text-right">Copyright</label>
                                              <input required name="da_copyright" id="da_copyright" class="<?php echo $lang_set; ?> form-control" value="<?php echo $da_copyright; ?>" type="text">
                                        </div>
                                        <hr />
                                          <div class="form-group  col-md-12">
                                              <div align="center">
                                                <div>
                                                  <button name="saveRBT" value="normal" class="btn btn-outline-success return <?php echo $lang_set; ?>" type="submit"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                                                  <a href="<?php echo REAL_PATH.'/setting/ManageECT/'; ?>" class="btn btn-outline-danger cancel <?php echo $lang_set; ?>"><i class="mdi mdi-close-box-outline"></i> <?php echo label('cancel') ?></a>
                                                </div>
                                              </div>
                                          </div>
                              </form>
                            </div>
                        </div>
                      </div>
                    </div>

            </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
        </div>
    </div>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/userCode.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

    $(document).ready(function() {
        $('.dropify').dropify();
        if ($("#wdesc_<?php echo $lang_set ?>").length > 0) {
            tinymce.init({
                selector: "textarea#wdesc_<?php echo $lang_set ?>",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

            });
        }
    });
    </script>
</body>

</html>