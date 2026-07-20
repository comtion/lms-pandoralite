<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
  $lang_set = $lang;
?>
<script src="<?php echo REAL_PATH; ?>/assets/ckeditor/ckeditor.js"></script>
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-datetimepicker.min.js"></script>
</head>
<body>
  <div id="superwrapper">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>

    <!--content-->
    <div class="container dashboard main">
      <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-custom-arrow" aria-hidden="true"></i></a>
      <div class="row">
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <div class="content dashWrap">
          <div class="dashElement page">
            <div class="row">
              <div class="col-md-12">
                <div class="dashHeader">
                  <h2 class="dark"><?php echo "Finish Message"; ?></h2>
                </div>
                <br>

                  <?php 
                    $id = "";
                    $title_th = "";
                    $title_en = "";
                    $message_th = "";
                    $message_en = "";
                    foreach ($finish_data as $key => $value) {
                      $id = $value['id'];
                      $title_th = $value['title_th'];
                      $title_en = $value['title_en'];
                      $message_th = $value['message_th'];
                      $message_en = $value['message_en'];
                    }
                    echo form_open_multipart('compliance/editfinish'); 
                  ?>
                  <div class="dashContent" id="addmorequestion">
                    <div class="row">
                      <div class="col-sm-12">
                        <input type="hidden" id="finish_id" name="finish_id" value="<?php echo $id; ?>">

                        <label class="col-sm-2 control-label" for="inputSuccess">
                          <?php echo "*Thai Title"; ?>
                        </label>
                        <div class="col-sm-4 form-group has-success has-feedback">
                          <input name="title_th" id="title_th" value="<?php echo $title_th; ?>"  class="form-control" type="text" required>
                        </div>

                        <label class="col-sm-2 control-label" for="inputSuccess">
                          <?php echo "*English Title"; ?>
                        </label>
                        <div class="col-sm-4 form-group has-success has-feedback">
                          <input name="title_en" id="title_en" value="<?php echo $title_en; ?>"  class="form-control" type="text" required>
                        </div>


                        <label class="col-sm-3 control-label" for="inputSuccess">
                          <?php echo "Thai Message"; ?>
                        </label>
                        <div class="col-sm-9 form-group has-success has-feedback">
                          <textarea class="form-control" name="message_th" id="message_th" rows="5" style="width:100%" ><?php echo $message_th; ?></textarea>
                        </div>

                        <label class="col-sm-3 control-label" for="inputSuccess">
                          <?php echo "English Message"; ?>
                        </label>
                        <div class="col-sm-9 form-group has-success has-feedback">
                          <textarea class="form-control" name="message_en" id="message_en" rows="5" style="width:100%" ><?php echo $message_en; ?></textarea>
                        </div>


                      </div>
                    </div>
                    <hr/>
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <div class="saveWrap">
                          <button class="btn btn-default return <?php echo $lang_set; ?>" type="submit">
                            <img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png">
                            <?php echo label('saveR') ?>
                          </button>
                          <a style="color:#fff;" class="btn btn-danger"  href="<?php echo REAL_PATH.'/dashboard/'?>">
                            <img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png"> <?php echo label('cancel') ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php echo form_close(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <script>
        $(document).ready( function(){
            $('input[type="text"]').attr('autocomplete', 'off');
        });

        CKEDITOR.replace('message_th' ,{
          customConfig: '<?=base_url('assets/ckeditor/config.js')?>?<?=time()?>',
          filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
        });
        CKEDITOR.replace('message_en' ,{
          customConfig: '<?=base_url('assets/ckeditor/config.js')?>?<?=time()?>',
          filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
        });
    </script>
  </body>
</html>
