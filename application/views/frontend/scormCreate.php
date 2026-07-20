<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/tab.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/jquery-ui.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap.js"></script>
<script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="<?php echo REAL_PATH; ?>/assets/ckeditor/ckeditor.js"></script>
</head>
<body>
  <div id="superwrapper">
    <!--Nav-->
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>

    <!--content-->
    <div class="container dashboard main">
      <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_categories.png"></a>
      <div class="row">
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

        <!-- Content Body -->
        <div class="content dashWrap">
          <div class="dashElement page">
            <div class="row crcourse">
              <div class="col-md-12">
                <?php if (isset($lesson)){
                  $lesson_name = $lesson['les_name'];
                  $course_id = $lesson['courses_id'];
                  $lesson_info = $lesson['les_info'];
                  $time_open = $lesson['time_start'];
                  $time_end = $lesson['time_end'];
                  $hidden = $lesson['hidden'];
                }else {
                  $lesson_name = '';
                  $lesson_info = '';
                  $time_open = '';
                  $time_end = '';
                  $hidden = 1;
                  $vid_url = '';
                  $vid_file = '';
                } ?>
                <section class="active">
                  <div class="dashHeader">
                    <h2><?php echo strpos($page, 'create') ?  label('create') : label('edit') ; echo label('scorm') ?></h2>
                  </div>
                  <div class="portlet-body">
                    <div class="tabbable">

                      <div class="tab-content">
                        <div class="tab-pane active">
                          <div class="dashContent">
                            <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">

                              <input required type="hidden" id='inp_lang' name="lang" value="<?php echo $lang ?>"  />
                              <input required type="hidden" name="code" value="<?php echo $lcode; ?>"  />
                              <input required type="hidden" name="emp_code" value="<?php echo $emp_c; ?>"  />
                              <input required type="hidden" name="course_id" value="<?php echo $ccode; ?>"  />
                              <input required type="hidden" id="inp_sDate" name="time_open" value="<?php echo $time_open ?>" />
                              <input required type="hidden" id="inp_eDate" name="time_close" value="<?php echo $time_end ?>" />

                              <div class="row">
                                <div class="col-sm-3 courseCat">
                                  <?php echo label('ceGen') ?>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('lName') ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <input required type="text" class="form-control" id="sname" name="lesson_name" value="<?php echo $lesson_name ?>">
                                  </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('less_visible') ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <div class="visible">
                                      <label style="color:#000;">
                                        <input required type="radio" name="visRadio" value="1" aria-label="" <?php echo $hidden == 1 ? 'checked' : ''; ?>> <?php echo label('show').label('lesson') ?>
                                      </label>
                                    </div>
                                    <div class="visible">
                                      <label  style="color:#000;">
                                        <input required type="radio" name="visRadio" value="0" aria-label="" <?php echo $hidden == 0 ? 'checked' : ''; ?>> <?php echo label('hide').label('lesson') ?>
                                      </label>
                                    </div>
                                  </div>

                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('less_start') ?></label>
                                  <div class="col-sm-9 form-group">
                                    <div class="input-group">
                                      <input name="sDate" value='<?php echo $time_open; ?>' type="text" class="form-control" id="sDate" placeholder="dd/mm/yy hr:min">
                                      <script>
                                      $( function() {
                                        $( "#sDate" ).datetimepicker({
                                          format: 'dd/mm/yyyy hh:ii'
                                        });
                                      });
                                      $(document).ready(function(){
                                        $("#sDate").change(function() {
                                          $("#inp_sDate").val($('#sDate').val());
                                        });
                                      });
                                      </script>
                                      <div class="input-group-addon calendar"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo_w.png"></div>
                                    </div>
                                  </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('less_end') ?></label>
                                  <div class="col-sm-9 form-group">
                                    <div class="input-group">
                                      <input name="eDate" value='<?php echo $time_end; ?>' type="text" class="form-control" id="eDate" placeholder="dd/mm/yy hr:min">
                                      <script>

                                      $( function() {
                                        $( "#eDate" ).datetimepicker({
                                          format: 'dd/mm/yyyy hh:ii'
                                        });
                                      });
                                      $(document).ready(function(){
                                        $("#eDate").change(function() {
                                          $("#inp_eDate").val($('#eDate').val());
                                        });
                                      });
                                      </script>
                                      <div class="input-group-addon calendar"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo_w.png"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <hr />

                              <div class="row">
                                <div class="col-sm-3 courseCat">
                                  <?php echo label('lesson') ?>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('less_detail') ?></label>
                                  <div class="col-sm-9 form-group">
                                    <p><textarea name="lesson_info" id="lesson_info" rows="10" cols="80"><?php echo $lesson_info ?></textarea></p>
                                    <script>
                                    CKEDITOR.replace('lesson_info' ,{
                                      filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                    });
                                    </script>
                                  </div>
                                </div>
                              </div>

                              <hr />

                              <div class="row">
                                <div class="col-sm-3 courseCat">
                                  <?php echo label('les_scorm') ?>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('status') ?></label>
                                  <div class="col-sm-8" style="<?php echo isset($scm)? 'color:#0d0': 'color:#d00';?>;"><?php echo isset($scm)?label('have'):label('none'); ?></div>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo strpos($page, 'create') ?  label('add') : label('edit') ; echo ' '.label('scorm'); ?></label>
                                  <div class="col-sm-8 form-group">
                                    <div class="input-group">
                                      <input type="file" name="scorm">
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <hr />

                              <div class="row">
                                <div class="col-sm-8 col-sm-offset-4">
                                  <div class="saveWrap">
                                    <button name="saveRBT" value="normal" class="btn btn-default return" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('saveR') ?></button>
                                    <button name="saveBT" value="normal" class="btn btn-default display" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('save') ?></button>
                                    <div class="btn btn-default cancel" style="background-color:red;"><a style="color:#fff;" href="<?php echo REAL_PATH.'/course/detail/'.$ccode; ?>"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png"> <?php echo label('cancel') ?></a></div>
                                  </div>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/video.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jquery-ui.js"></script>

  </body>
  </html>
