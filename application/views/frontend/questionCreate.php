<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/tab.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/jquery-ui.css" rel="stylesheet">

<script src="<?php echo REAL_PATH; ?>/assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo REAL_PATH; ?>/assets/js/editQst.js"></script>
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
                  <?php foreach ($allLangs as $each) { ?>
                    <input required class="lang_tab" value="<?php echo $each['lang']; ?>" id="tab_<?php echo $each['lang']; ?>" type="radio" name="tabs" <?php echo ($each['lang'] == $lang_tab) ? "checked": ""; ?>>
                    <label class="each_label" id="sh_<?php echo $each['lang']; ?>" for="tab_<?php echo $each['lang']; ?>"><?php echo label($each['lang']); ?></label><?php
                  } ?>
                </div>
                <?php foreach ($allLangs as $each) {
                  $lang_set = $each['lang'];
                  if (isset($quest)){
                    $quiz_id = $quest['qst']['quiz_id'];
                    $quest_name = $quest['qst']['questions_name'];
                    $quest_info = $quest['qst']['questions_Info'];
                    $hidden = $quest['qst']['hidden'];
                    $score = $quest['qst']['score'];
                    $type = $quest['qst']['type'];
                    $t_det = $quest['type'];
                  }else {
                    $quest_name = '';
                    $quest_info = '';
                    $hidden = 1;
                    $score = 0;
                    $type = '';
                }
              ?>
              <section id="content_<?php echo $lang_set ?>" style="<?php echo $lang_tab == $lang_set ? '' : 'display:none;' ; ?>" class="active">
                  <div class="dashHeader">
                    <h2><?php echo strpos($action[$lang_set], 'create') ?  label('create') : label('edit') ; echo label('question') ?></h2>
                  </div>
                  <div class="portlet-body">
                    <div class="tabbable">

                      <div class="tab-content">
                        <div id="normal" class="tab-pane active">
                          <div class="dashContent">
                            <form method="post" enctype="multipart/form-data" action="<?php echo REAL_PATH.'/quiz/createdQuestion'; ?>">

                              <input required type="hidden" name="page" value="<?php echo $action[$lang_set]; ?>"  />
                              <?php if (strpos($page, 'edit')) {?>
                                <input required type="hidden" name="qst_id" value="<?php echo $qst_id; ?>"  /><?php
                              } ?>
                              <input required type="hidden" name="code" value="<?php echo $ccode; ?>"  />
                              <input required type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>"  />
                              <input required type="hidden" name="lang" value="<?php echo $lang_set; ?>"  />

                              <div class="row">
                                <div class="col-sm-2 courseCat">
                                  <?php echo label('ceGen') ?>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('question'); ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <p><textarea required  name="name" id="sname_txt<?php echo $lang_set ?>" rows="10" style="width: 100%"><?php echo $quest_name ?></textarea></p>
                                    <script>

                                    CKEDITOR.replace('sname_txt<?php echo $lang_set ?>' ,{
                                      customConfig: '<?=base_url('assets/ckeditor/config.js')?>?<?=time()?>',
                                      filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                    });
                                    </script>
                                  </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('quest_visible') ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <div class="visible">
                                      <label style="color:#000;">
                                        <input type="radio" name="visRadio" value="1" aria-label="" <?php echo $hidden == 1 ? 'checked' : ''; ?>> <?php echo label('show').label('question') ?>
                                      </label>
                                    </div>
                                    <div class="visible">
                                      <label  style="color:#000;">
                                        <input type="radio" name="visRadio" value="0" aria-label="" <?php echo $hidden == 0 ? 'checked' : ''; ?>> <?php echo label('hide').label('question') ?>
                                      </label>
                                    </div>
                                  </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('maxScore'); ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <input type="text" class="form-control" name="score" value="<?php echo $score ?>">
                                  </div>
                                  <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('quest_type') ?></label>
                                  <div class="col-sm-9 form-group has-success has-feedback">
                                    <select required name="type" class="form-control" id="<?php echo $lang_set; ?>_type">
                                      <option value="0"><?php echo label('choose').label('quest_type') ?></option>
                                      <option value="sa" <?php echo ($type != 'sa') ? : 'selected'; ?>><?php echo label('qt_sa'); ?></option>
                                      <option value="sub" <?php echo ($type != 'sub') ? : 'selected'; ?>><?php echo label('qt_sub'); ?></option>
                                      <!-- <option value="match" <?php //echo ($type != 'match') ? : 'selected'; ?>><?php //echo label('qt_match'); ?></option> -->
                                      <option value="multi" <?php echo ($type != 'multi') ? : 'selected'; ?>><?php echo label('qt_multi'); ?></option>
                                      <option value="twoChoice" <?php echo ($type != 'twoChoice') ? : 'selected'; ?>><?php echo label('qt_twoChoice'); ?></option>
                                      <!-- <option value="dd" <?php //echo ($type != 'dd') ? : 'selected'; ?>><?php //echo label('qt_DragNDrop'); ?></option> -->
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class='row'>
                                <div class="col-sm-2 courseCat">
                                  <?php echo label('ceDsc'); ?>
                                </div>
                                <div class="col-sm-9">
                                  <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('summary'); ?></label>
                                  <div class="col-sm-9 form-group">
                                    <p><textarea required  name="info" id="info_<?php echo $lang_set ?>" rows="10" style="width: 100%"><?php echo $quest_info ?></textarea></p>
                                    <script>
                                    CKEDITOR.replace('info_<?php echo $lang_set ?>' ,{
                                      filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                    });
                                    </script>
                                  </div>
                                </div>
                              </div>
                              <hr>

                              <div id='<?php echo $lang_set ?>_type_detail' style="display:none;">
                                <!-- Question Type Deatil Part -->
                                <div class="row">
                                  <div class="col-sm-3 courseCat">
                                    <?php echo label('quest_detail'); ?>
                                  </div>
                                  <div class="col-sm-9">
                                    <label style="display:none;" id="<?php echo $lang_set ?>_linfo" class="col-sm-3 control-label linfo" for="inputSuccess"><?php echo label('summary'); ?></label>
                                    <div id="<?php echo $lang_set ?>_ddinfo" class="col-sm-9 form-group ddinfo" style="display:none;">
                                      <p><textarea  name="q_info" id="<?php echo $lang_set ?>_qinfo" rows="10" cols="80"><?php echo (!isset($t_det['que_info'])) ? '': $t_det['que_info']; ?></textarea></p>
                                      <script>
                                      CKEDITOR.replace('<?php echo $lang_set ?>_qinfo' ,{
                                        filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                      });
                                      </script>
                                    </div>
                                    <label style="display:none;" id="<?php echo $lang_set ?>_llimit" class="col-sm-3 control-label qlimit" for="inputSuccess"><?php echo label('number_of').label('answer') ?></label>
                                    <div style="display:none;" id="<?php echo $lang_set ?>_qlimit" class="col-sm-9 form-group has-success has-feedback qlimit">
                                      <select name="limit" class="form-control" id="<?php echo $lang_set ?>_limit">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <option value="<?php echo $i ?>" <?php
                                        if (isset($t_det['limit5'])){
                                          if ($t_det['limit5'] == $i)
                                            echo 'selected';
                                        }
                                        ?>><?php echo $i; ?></option><?php
                                      } ?>
                                      </select>
                                    </div>
                                    <?php for ($i=1; $i <= 5 ; $i++) {?>
                                      <label style="display:none;" id="<?php echo $lang_set ?>_qc<?php echo $i ?>" class="col-sm-3 control-label qc<?php echo $i; ?>" for="inputSuccess"><?php echo label('choice').$i; ?></label>
                                      <div style="display:none;" id="<?php echo $lang_set ?>_c<?php echo $i ?>" class="col-sm-9 form-group has-success has-feedback c<?php echo $i; ?>">
                                        <p><textarea required  name="c[]" id="c_txt<?php echo $i; ?><?php echo $lang_set ?>" rows="10" style="width: 100%"><?php echo (!isset($t_det['c'.$i])) ? '': $t_det['c'.$i]; ?></textarea></p>
                                        <script>
                                        CKEDITOR.replace('c_txt<?php echo $i; ?><?php echo $lang_set ?>' ,{
                                          filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                        });
                                        </script>
                                      </div><?php
                                    } for($i=1; $i <= 5 ; $i++){ ?>
                                      <label style="display:none;" id="<?php echo $lang_set ?>_qa<?php echo $i; ?>" class="qa<?php echo $i; ?> col-sm-3 control-label" for="inputSuccess" style="font-size:0.9em;"><?php echo label('mat_c').label('choice').$i; ?></label>
                                      <div style="display:none;" id="<?php echo $lang_set ?>_a<?php echo $i ?>" class="col-sm-9 form-group has-success has-feedback a<?php echo $i; ?>">
                                        <p><textarea required  name="a[]" id="a_txt<?php echo $i; ?><?php echo $lang_set ?>" rows="10" style="width: 100%"><?php echo (!isset($t_det['a'.$i])) ? '': $t_det['a'.$i]; ?></textarea></p>
                                        <script>
                                        CKEDITOR.replace('a_txt<?php echo $i; ?><?php echo $lang_set ?>' ,{
                                          filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                        });
                                        </script>
                                        <!--<input type="text" class="form-control" name="a[]" value="<?php echo (!isset($t_det['a'.$i])) ? '': $t_det['a'.$i]; ?>">-->
                                      </div>
                                      <?php
                                    } for($i=1; $i <= 5 ; $i++){?>
                                      <label style="display:none;" id="<?php echo $lang_set ?>_qans<?php echo $i; ?>" class="qans<?php echo $i; ?> col-sm-3 control-label" for="inputSuccess" style="font-size:0.8em;"><?php echo label('answer').$i; ?>
                                      </label>
                                      <div style="display:none;" id="<?php echo $lang_set ?>_ans<?php echo $i; ?>" class="col-sm-9 form-group has-success has-feedback ans<?php echo $i; ?>">

                                        <p><textarea required  name="ans[]" id="ans_txt<?php echo $i; ?><?php echo $lang_set ?>" rows="10" style="width: 100%"><?php echo (!isset($t_det['ans'.$i])) ? '': $t_det['ans'.$i]; ?></textarea></p>
                                        <script>
                                        CKEDITOR.replace('ans_txt<?php echo $i; ?><?php echo $lang_set ?>' ,{
                                          filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                        });
                                        </script>
                                        <!--<input type="text" class="form-control" name="ans[]" value="<?php echo (!isset($t_det['ans'.$i])) ? '': $t_det['ans'.$i]; ?>">-->
                                      </div>
                                      <?php
                                    }?>
                                    <label style="display:none;" id="<?php echo $lang_set ?>_qans" class="qans col-sm-3 control-label" for="inputSuccess" style="font-size:0.8em;"><?php echo label('answer') ?></label>
                                    <div style="display:none;" id="<?php echo $lang_set ?>_ans" class="col-sm-9 form-group has-success has-feedback ans">
                                      <input type="text" class="form-control" name="answer" value="<?php echo (!isset($t_det['ans'])) ? '': $t_det['ans']; ?>">
                                    </div>
                                    <?php for($i=1; $i<= 5; $i++) { ?>
                                    <label id="<?php echo $lang_set ?>_qimg<?php echo $i ?>" class="qimg<?php echo $i ?> col-sm-3 control-label" for="inputSuccess1"><?php echo label('q_pic').' '.$i ?></label>
                                    <div id="<?php echo $lang_set ?>_img<?php echo $i ?>" class="img<?php echo $i ?> col-sm-9 form-group has-success has-feedback">
                                      <!-- image-preview-input -->
                                      <div class="btn btn-default image-preview-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title"><?php echo label('browse'); ?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="images[]"/> <!-- rename it -->
                                      </div>
                                    </div>
                                      <?php
                                    }?>
                                    <label id="<?php echo $lang_set ?>_qimga" class="qimga col-sm-3 control-label" for="inputSuccess1"><?php echo label('q_pic').label('answer') ?></label>
                                    <div id="<?php echo $lang_set ?>_imga" class="imga col-sm-9 form-group has-success has-feedback">
                                      <!-- image-preview-input -->
                                      <div class="btn btn-default image-preview-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title"><?php echo label('browse'); ?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="imageAnswer"/> <!-- rename it -->
                                      </div>
                                  </div>
                                  </div>
                                </div>

                                <hr>
                                <!-- Question Type Deatil Part -->
                              </div>

                              <div class="row">
                                <div class="col-sm-8 col-sm-offset-4">
                                  <div class="saveWrap">
                                    <button name="saveRBT" value="normal" class="btn btn-default return" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('saveR') ?></button>
                                    <button name="saveBT" value="normal" class="btn btn-default display" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> <?php echo label('save') ?></button>
                                    <div class="btn btn-default cancel" style="background-color:red;"><a style="color:#fff;" href="<?php echo REAL_PATH.'/quiz/detail/'.$quiz_id; ?>"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png"> <?php echo label('cancel') ?></a></div>
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
    <script src="<?php echo REAL_PATH; ?>/assets/js/question.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jquery-ui.js"></script>

  </div>
</body>
</html>
