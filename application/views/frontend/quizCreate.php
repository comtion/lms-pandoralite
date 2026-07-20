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
                <div id="langs_tab">
                  <?php foreach ($langs as $each) { ?>
                    <input required class="lang_tab" value="<?php echo $each['lang']; ?>" id="tab_<?php echo $each['lang']; ?>" type="radio" name="tabs" <?php echo ($each['lang'] == $lang_tab) ? "checked": ""; ?>>
                    <label class="each_label" id="sh_<?php echo $each['lang']; ?>" for="tab_<?php echo $each['lang']; ?>"><?php echo label($each['lang']); ?></label><?php
                  } ?>
                </div><?php foreach ($langs as $each) {
                  $lang_set = $each['lang'];
                  if (isset($quizes[$lang_set])){
                    $quiz = $quizes[$lang_set];
                    $quiz_name = $quiz['quiz_name'];
                    $course_id = $quiz['courses_id'];
                    $quiz_info = $quiz['quiz_info'];
                    $time_open = $quiz['time_open'];
                    $time_end = ($quiz['time_end'] == '0000-00-00 00:00:00')?'':$quiz['time_end'];
                    $time_mod = $quiz['time_mod'];
                    $hidden = $quiz['hidden'];
                    $random = $quiz['random'];
                    $showgrades = $quiz['showgrades'];
                    $max_score = $quiz['max_score'];
                    $attempts = $quiz['attempts'];
                    $n_attempts = $quiz['n_attempts'];
                    $quiz_type = $quiz['quiz_type'];
                    $condition = $quiz['condition'];
                  }else {
                    $quiz_name = '';
                    $quiz_info = '';
                    $time_open = '';
                    $time_end = '';
                    $time_mod = '';
                    $hidden = 1;
                    $random = 1;
                    $showgrades = 1;
                    $max_score = 0;
                    $attempts = 0;
                    $n_attempts = 1;
                    $quiz_type = 0;
                    $condition = '';
                  }?>
                  <section id="content_<?php echo $lang_set ?>" style="<?php echo $lang_tab == $lang_set ? '' : 'display:none;' ; ?>" class="active">
                    <div class="dashHeader">
                      <h2><?php echo (isset($editIntro) && (in_array($lang_set, $editIntro))) ? label('edit'): label('create'); echo label('quiz') ?></h2>
                    </div>
                    <div class="portlet-body">
                      <div class="tabbable">

                        <div class="tab-content">
                          <div id="normal" class="tab-pane active">
                            <div class="dashContent">
                              <form method="post" action="<?php echo REAL_PATH.'/quiz/create/'.$course[$clang]['ccode'] ?>">

                                <input required type="hidden" name="page" value="<?php echo isset($actions)?$actions[$lang_set]:'create'; ?>"  />
                                <input required type="hidden" id='inp_lang' name="lang" value="<?php echo $clang; ?>"  />
                                <input required type="hidden" id='inp_code' name="code" value="<?php echo $qcode; ?>"  />
                                <input required type="hidden" name="emp_code" value="<?php echo $emp_c; ?>"  />
                                <input required type="hidden" name="course_id" value="<?php echo $course_id; ?>"  />
                                <input required type="hidden" id="inp_type" name="type" value="normal" />
                                <input required type="hidden" class="<?php echo $lang_set; ?>" id="time_open" name="time_open_<?php echo $lang_set ?>" value="<?php echo $time_open ?>" />
                                <input required type="hidden" class="<?php echo $lang_set; ?>" id="time_close" name="time_close_<?php echo $lang_set ?>" value="<?php echo $time_end ?>" />

                                <div class="row">
                                  <div class="col-sm-3 courseCat">
                                    <?php echo label('ceGen'); ?>
                                  </div>
                                  <div class="col-sm-9">
                                    <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('qName') ?></label>
                                    <div class="col-sm-9 form-group has-success has-feedback">
                                      <input required type="text" class="form-control" id="sname" name="quiz_name" value="<?php echo $quiz_name ?>">
                                    </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('random') ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <div class="visible">
                                      <label style="color:#000;">
                                        <input type="radio" name="randRadio" value="1" aria-label="" <?php echo $random == 1 ? 'checked' : ''; ?>> <?php echo label('enable').label('random') ?>
                                      </label>
                                    </div>
                                    <div class="visible">
                                      <label  style="color:#000;">
                                        <input type="radio" name="randRadio" value="0" aria-label="" <?php echo $random == 0 ? 'checked' : ''; ?>> <?php echo label('disable').label('random') ?>
                                      </label>
                                    </div>
                                  </div>
                                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('qiz_visible') ?></label>
                                    <div class="col-sm-9 form-group has-success has-feedback">
                                      <div class="visible">
                                        <label style="color:#000;">
                                          <input required type="radio" name="visRadio" value="1" aria-label="" <?php echo $hidden == 1 ? 'checked' : ''; ?>><?php echo label('show').label('quiz') ?>
                                        </label>
                                      </div>
                                      <div class="visible">
                                        <label  style="color:#000;">
                                          <input required type="radio" name="visRadio" value="0" aria-label="" <?php echo $hidden == 0 ? 'checked' : ''; ?>><?php echo label('hide').label('quiz') ?>
                                        </label>
                                      </div>
                                    </div>
                                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('show').label('grade') ?></label>
                                    <div class="col-sm-9 form-group has-success has-feedback">
                                      <div class="visible">
                                        <label style="color:#000;">
                                          <input required type="radio" name="showgrades" value="1" aria-label="" <?php echo $showgrades == 1 ? 'checked' : ''; ?> ><?php echo label('show') ?>
                                        </label>
                                      </div>
                                      <div class="visible">
                                        <label  style="color:#000;">
                                          <input required type="radio" name="showgrades" value="0" aria-label="" <?php echo $showgrades == 0 ? 'checked' : ''; ?>><?php echo label('hide') ?>
                                        </label>
                                      </div>
                                    </div>
                                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('qiz_type') ?></label>
                                    <div class="col-sm-9 form-group has-success has-feedback">
                                      <div class="visible">
                                        <label style="color:#000;">
                                          <input required class='qtype' type="radio" name="quiz_type" value="0" aria-label="" <?php echo $quiz_type == 0 ? 'checked' : ''; ?>> <?php echo label('preExam'); ?>
                                        </label>
                                      </div>
                                      <div class="visible">
                                        <label  style="color:#000;">
                                          <input required class='qtype'  type="radio" name="quiz_type" value="1" aria-label="" <?php echo $quiz_type == 1 ? 'checked' : ''; ?>> <?php echo label('finalExam'); ?>
                                        </label>
                                      </div>
                                    </div>
                                    <div id="mng_score" style="display:none;">
                                      <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('maxScore') ?></label>
                                      <div class="col-sm-9 form-group has-success has-feedback">
                                        <input type="text" value="<?php echo $max_score; ?>" name="max_score" class="form-control" id="max_score">
                                      </div>
                                    </div>

                                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('qiz_start'); ?></label>
                                    <div class="col-sm-9 form-group">
                                      <div class="input-group">
                                        <input name="sDate" value='<?php echo $time_open; ?>' type="text" class="form-control" id="sDate_<?php echo $lang_set; ?>" placeholder="dd/mm/yy hr:min">
                                        <div class="input-group-addon calendar"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo_w.png"></div>
                                        <script>
                                        $( function() {
                                          $( "#sDate_<?php echo $lang_set; ?>" ).datetimepicker({
                                            format: 'dd/mm/yyyy hh:ii'
                                          });
                                        });
                                        $(document).ready(function(){
                                          $("#sDate_<?php echo $lang_set; ?>").change(function() {
                                            $("[name='time_open_<?php echo $lang_set ?>']").val($('#sDate_<?php echo $lang_set; ?>').val());
                                          });
                                        });
                                        </script>
                                      </div>
                                    </div>
                                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('qiz_end') ?></label>
                                    <div class="col-sm-9 form-group">
                                      <div class="input-group">
                                        <input name="eDate" value='<?php echo $time_end; ?>' type="text" class="form-control" id="eDate_<?php echo $lang_set; ?>" placeholder="dd/mm/yy hr:min">
                                        <div class="input-group-addon calendar"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo_w.png"></div>
                                        <script>
                                        $( function() {
                                          $( "#eDate_<?php echo $lang_set; ?>" ).datetimepicker({
                                            format: 'dd/mm/yyyy hh:ii'
                                          });
                                        });
                                        $(document).ready(function(){
                                          $("#eDate_<?php echo $lang_set; ?>").change(function() {
                                            $("[name='time_close_<?php echo $lang_set ?>']").val($('#eDate_<?php echo $lang_set; ?>').val());
                                          });
                                        });
                                        </script>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <hr />

                                <div class="row">
                                  <div class="col-sm-3 courseCat">
                                    <?php echo label('qiz_desc') ?>
                                  </div>
                                  <div class="col-sm-9">
                                    <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('qiz_desc') ?></label>
                                    <div class="col-sm-9 form-group">
                                      <p><textarea name="quiz_info" id="quiz_info_<?php echo $lang_set; ?>" rows="10" cols="80"><?php echo $quiz_info; ?></textarea></p>
                                      <script>
                                      CKEDITOR.replace('quiz_info_<?php echo $lang_set; ?>' ,{
                                        filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                      });
                                      </script>
                                    </div>
                                  </div>
                                </div>

                                <hr />

                                <div class="row">
                                  <div class="col-sm-3 courseCat">
                                    <?php echo label('qiz_etc') ?>
                                  </div>
                                  <div class="col-sm-9">
                                    <label class="col-sm-4 control-label" for="inputSuccess"><?php echo label('qiz_num') ?></label>
                                    <div class="col-sm-10 form-group">
                                      <div class="radio">
                                        <label>
                                          <input required type="radio" name="attempts_<?php echo $lang_set; ?>" id="yes_attempts" class="number <?php echo $lang_set; ?>" value="1" aria-label="..." <?php echo $attempts == 1 ? 'checked' : ''; ?>><?php echo label('yes') ?>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input required type="radio" name="attempts_<?php echo $lang_set; ?>" id="no_attempts" class="number <?php echo $lang_set; ?>" value="0" aria-label="..." <?php echo $attempts == 0 ? 'checked' : ''; ?>><?php echo label('no') ?>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="attempted <?php echo $lang_set; ?>" style="display:none;">
                                      <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('number_of') ?></label>
                                      <div class="col-sm-9 form-group has-success has-feedback">
                                        <input required type='text' name="n_attempts" value="<?php echo $n_attempts; ?>" class="form-control" id="n_attempts">
                                      </div>
                                    </div>
                                    <div class="col-sm-9 form-group">
                                      <label class="col-sm-5 control-label" for="inputSuccess1"><?php echo label('ccond'); ?></label>
                                      <input value="<?php echo $condition ?>" required class="col-sm-2 form-group condition" type="text" name="condition">
                                      <div class="col-md-2"><?php echo '%'; ?></div>
                                    </div>
                                  </div>
                                </div>

                                <hr />

                                <div class="row">
                                  <div class="col-sm-8 col-sm-offset-4">
                                    <div class="saveWrap">
                                      <button name="saveRBT" value="normal" class="btn btn-default return" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('saveR') ?></button>
                                      <button name="saveBT" value="normal" class="btn btn-default display" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('save') ?></button>
                                      <div class="btn btn-default cancel" style="background-color:red;"><a style="color:#fff;" href="<?php echo REAL_PATH.'/course/detail/'.$course_id; ?>"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png"> <?php echo label('cancel') ?></a></div>
                                    </div>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section><?php
                } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    <script type="text/javascript">var lang = "<?php echo $lang_set ?>";</script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/quiz.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jquery-ui.js"></script>

  </div>
</body>
</html>
