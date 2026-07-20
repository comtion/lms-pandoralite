<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/course.css" rel="stylesheet">
</head>
<body>
  <div id="superwrapper">
    <!--Nav-->
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <!--content-->
    <div class="container dashboard main">
      <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_categories.png"></a>
      <div class="row">
<?php $this->load->view('frontend/inc/inc-sidemenu.php');
        $name = $lang == 'english' ? $emp['prefix'].' ' : $emp['prefix'];
        $name = $name.$emp['fname'].' '.$emp['lname'];
        ?>

        <div class="content dashWrap">
          <div class="dashElement page">
            <div class="row">
              <div class="col-md-12">
                <div class="dashHeader">
                  <div class='row' style="height:300px; overflow:hidden;">
                    <div class="col-md-12" ><img class='responsive coverCourse' src="<?php echo REAL_PATH.'/uploads/'.$course['pic']; ?>" ></div>
                  </div>
                  <?php if(in_array($role, array("superadmin"))) { ?>
                    <h2><?php echo "[ ".$course['ucode']." ] : ".$course['cname'];?></h2>
                  <?php }else{ ?>
                    <h2><?php echo $course['cname'];?></h2>
                  <?php } ?>

                  <div class="dashpageWrap course">
                    <?php if ( in_array($role, array("superadmin")) ) { ?>
                      <h3><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_profile.png"> <?php echo label('createBy').' '.$name; ?></h3>
                      <h4 style="color:#606060;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png"> <?php echo label('dateMod').' '.$course['time_mod']; ?></h4>
                    <?php } ?>
                    <?php if( $course['seat'] != NULL ){ ?>
                      <h4 style="color:#606060;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_view_users.png"> <?php echo label('numSeat').' '.(($course['seat'] == NULL) ? label('infinity'): $seat);?></h4>
                    <?php } ?>
                    <?php if( !empty( $course['condition'] ) ){ ?>
                      <h4><?php echo label('prerequisite'); ?> <?php echo $course['conditionName']." ".label('prerequisite_end'); ?></h4>
                    <?php } ?>
                    <hr><div class="course-detail"><h4><?php echo $course['cdesc']; ?></h4></div><hr>
                    <?php 
                    if ( $course['time_open'] != "30 พฤศจิกายน 542" && $course['time_open'] != "30 Nov -0001 00:00" && $course['time_open'] != "30 พฤศจิกายน 542 00:00" ) { ?>
                      <h3><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo.png"> <?php echo label('dateStart').' '.$course['time_open']; ?></h3>
                    <?php } ?>
                    <?php if ( $course['time_end'] != "Infinity Time" && $course['time_end'] != "ไม่จำกัดเวลา" ) { ?>
                      <h3><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo.png"> <?php echo label('dateExpired').' '.$course['time_end'];?></h3>
                    <?php } ?>

                    <?php if ($emp_c == $course['emp_c']) { ?>
                      <?php echo form_open('course/getPara', 'class="form-inline"');
                      echo form_input(array('type'=>'hidden','name'=>'ccode','value'=>$course['ccode']));
                      echo form_input(array('type'=>'hidden','name'=>'wcode','value'=>$course['wcode']));
                      echo form_input(array('type'=>'hidden','name'=>'cgcode','value'=>$course['cgcode']));
                      ?>
                      <div class="form-group" style="margin-bottom:10px;">
                        <div class="col-sm-2 col-md-2">
                          <button value="edit" name="method" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png"> <?php echo label('edit').label('course'); ?></button>
                        </div>
                        <div class="col-sm-3 col-md-3">
                          <button value="custom" name="method" class="btn btn-default" type="submit"><img style="width:10%;" src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_bdg.png"> <?php echo label('edit').label('certificate'); ?></button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                          <button name="method" value="delete" class="btn btn-danger" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_trash_w.png"> <?php echo label('delete').label('course'); ?></button>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-2 col-md-2">
                          <button value="enroll" name="method" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_add_user.png"> <?php echo label('add').label('student'); ?></button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                          <button name="method" value="lesson" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_new_article.png"> <?php echo label('add').label('lesson'); ?></button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                          <button name="method" value="scorm" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_new_article.png"> <?php echo label('add').label('scorm'); ?></button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                          <button name="method" value="quiz" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_new_article.png"> <?php echo label('add').label('quiz'); ?></button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                          <button name="method" value="survey" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_new_article.png"> <?php echo label('create').label('survey'); ?></button>
                        </div>
                      </div>
                    </form><br><br><hr><?php
                  } else {if ( !( $enrollStatus || $isRegistered || in_array($role, array("superadmin", "admin", "manager") ) ) ) {?>
                    <?php if( !empty( $course['condition'] ) ){ ?>
                      <?php if( $course['conditionStatus'] == "pass" ){ ?>
                        <div class="form-group">
                          <div class="col-sm-2 col-md-3">
                            <a href="<?php echo base_url();?>course/register/<?php echo $course['ccode'];?>" class="btn-enroll"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_add_user_w.png"> <?php echo label('register') ?></a>
                          </div>
                        </div>
                        <br><br><hr>
                      <?php } ?>
                    <?php }else{
                      ?>
                      <div class="form-group" id="register_btn" align="center">
                        <?php if($txt_showerror!=''){ ?>
                          <span style="font-size: 14px" id="txt_showerror"><?php echo $txt_showerror; ?></span>
                        <?php }else{ ?>
                          <a  href="<?php echo base_url();?>course/register/<?php echo $course['ccode'];?>" class="btn-enroll"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_add_user_w.png"> <?php echo label('register') ?></a>
                        <?php }?>
                          <!---->
                      </div>
                      <hr>
                    <?php } ?>
                  <?php }
                } 
                
                ?>
                <?php if(intval($coursecount)>0){ ?>
                <!-- Overview Part -->
                <?php if (sizeof($lessons) > 0 || sizeof($quizes) > 0 || sizeof($surveys) > 0 ||in_array($role, array("superadmin", "admin", "manager"))) {?>
                  <div class="tab-content faq-cat-content">
                    <div class="tab-pane active in fade" id="faq-cat-1">
                      <div class="panel-group" id="accordion-cat-1">

                        <?php if (sizeof($lessons) > 0): ?>
                          <div class="panel panel-default panel-faq">
                            <div class="panel-heading">
                              <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#over-lesson">
                                <h4 class="panel-title">
                                  <img style="width:15px;height:15px;" src="<?php echo REAL_PATH."/assets/images/icons/".$lesCom; ?>">
                                  <?php echo label('lesson'); ?>
                                  <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                </h4>
                              </a>
                            </div>
                            <div id="over-lesson" class="panel-collapse collapse">
                              <div class="panel-body">
                                <div class="table-wrapper">
                                  <table style="cursor:default" class="table table-striped" id="student-table">
                                    <thead>
                                      <tr>
                                        <th><?php echo label('number'); ?></th>
                                        <th><?php echo label('lName'); ?></th>
                                        <th><?php echo label('status'); ?></th>
                                        <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                          <th><?php echo label('manage').label('lesson'); ?></th><?php
                                        } ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php $row = 1; foreach ($lessons as $lesson) {?>
                                        <tr>
                                          <?php if ($enrollStatus || in_array($role, array("superadmin", "admin", "manager"))): ?>
                                            <?php /* <th style="cursor:pointer" onclick="window.document.location='<?php echo ($prePath && !$isCreater && !$cos_hidden) ? $prePath: base_url().'lesson/detail/'.$lesson['lcode'] ?>';" scope="row"><?php echo $row; $row++; ?></th>
                                            <td style="cursor:pointer" onclick="window.document.location='<?php echo ($prePath && !$isCreater && !$cos_hidden) ? $prePath: base_url().'lesson/detail/'.$lesson['lcode'] ?>';"><?php echo $lesson['les_name'] ?></td>
                                            <td onclick="window.document.location='<?php echo ($prePath && !$isCreater && !$cos_hidden) ? $prePath: base_url().'lesson/detail/'.$lesson['lcode'] ?>';" style="cursor:pointer;font-weight:bold;color:<?php echo $lesStatus[$lesson['lcode']] == 'done' ? '#0F0': '#F00' ; ?>;"><?php echo label($lesStatus[$lesson['lcode']]); ?></td>
                                            --*/ ?>
                                            <th style="cursor:pointer" onclick="window.document.location='<?php echo base_url().'lesson/detail/'.$lesson['lcode'] ?>';" scope="row"><?php echo $row; $row++; ?></th>
                                            <td style="cursor:pointer" onclick="window.document.location='<?php echo base_url().'lesson/detail/'.$lesson['lcode'] ?>';"><?php echo $lesson['les_name'] ?></td>
                                            <td onclick="window.document.location='<?php echo base_url().'lesson/detail/'.$lesson['lcode'] ?>';" style="cursor:pointer;font-weight:bold;color:<?php echo $lesStatus[$lesson['lcode']] == 'done' ? '#0F0': '#F00' ; ?>;"><?php echo label($lesStatus[$lesson['lcode']]); ?></td>
                                          <?php else: ?>
                                            <th scope="row"><?php echo $row; $row++; ?></th>
                                            <td ><?php echo $lesson['les_name'] ?></td>
                                            <td style="font-weight:bold;color:#F00;"><?php echo label($lesStatus[$lesson['lcode']]); ?></td>
                                          <?php endif; ?>
                                          <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                            <td>
                                              <?php if (isset($lesson['isScorm']) && $lesson['isScorm']) {?>
                                                <button onclick="window.document.location='<?php echo base_url().'scorm/edit/'.$lesson['lcode']; ?>'" value="edit" name="edit" class="btn btn-default" type="submit">
                                                <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('lesson'); ?>
                                                </button><?php
                                              } else {?>
                                                <button onclick="window.document.location='<?php echo base_url().'lesson/edit/'.$lesson['lcode']; ?>'" value="edit" name="edit" class="btn btn-default" type="submit">
                                                <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('lesson'); ?>
                                                </button><?php
                                              } ?>
                                            </td><?php
                                          }?></tr><?php
                                        } ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endif; ?>

                          <?php if (sizeof($quizes) > 0): ?>
                            <div class="panel panel-default panel-faq">
                              <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#over-quiz">
                                  <h4 class="panel-title">
                                    <img style="width:15px;height:15px;" src="<?php echo REAL_PATH."/assets/images/icons/".$qizCom; ?>">
                                    <?php echo label('quiz'); ?>
                                    <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                  </h4>
                                </a>
                              </div>
                              <div id="over-quiz" class="panel-collapse collapse">
                                <div class="panel-body">
                                  <div class="table-wrapper">
                                    <table style="cursor:default" class="table table-striped" id="student-table">
                                      <thead>
                                        <tr>
                                          <th><?php echo label('number'); ?></th>
                                          <th><?php echo label('qName'); ?></th>
                                          <th><?php echo label('maxScore'); ?></th>
                                          <th><?php echo label('status'); ?></th>
                                          <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                            <th><?php echo label('manage').label('quiz'); ?></th><?php
                                          } ?></tr>.
                                        </thead>
                                        <tbody>
                                          <?php $row = 1; foreach ($quizes as $quiz) { ?>
                                            <?php if (!$enrollStatus && !in_array($role, array("superadmin", "admin", "manager"))): ?>
                                              <tr>
                                                <th scope="row"><?php echo $row; $row++; ?></th>
                                                <td><?php echo $quiz['quiz_name']; ?></td>
                                                <td><?php echo empty($qizStatus[$quiz['qcode']]['sum_score']) ? 0 : $qizStatus[$quiz['qcode']]['sum_score'] ;echo ' / '.$quiz['max_score']; ?></td>
                                                <td style="font-weight:bold;color:#F00;">
                                            <?php else: ?>
                                              <tr <?php echo (!$cos_hidden && (empty($qizStatus)||$isCreater || !isset($qizStatus[$quiz['qcode']]) || ($qizStatus[$quiz['qcode']]['ent'])||($qizStatus[$quiz['qcode']]['submit']=='noProgress')))? "  style=\"cursor:pointer\" onclick=\"window.document.location='".REAL_PATH.'/quiz/detail/'.$quiz['qcode']."'\"": ""; ?>>
                                                <th scope="row"><?php echo $row; $row++; ?></th>
                                                <td><?php echo $quiz['quiz_name']; ?></td>
                                                <td><?php echo empty($qizStatus[$quiz['qcode']]['sum_score']) ? 0 : $qizStatus[$quiz['qcode']]['sum_score'] ;echo ' / '.$quiz['max_score']; ?></td>
                                                <td style="font-weight:bold;color:<?php echo ($qizStatus==NULL || !isset($qizStatus[$quiz['qcode']]) || ($qizStatus[$quiz['qcode']]['label'] != 'done' && $qizStatus[$quiz['qcode']]['label'] != 'pass'))?'#F00':'#0F0' ; ?>">
                                            <?php endif; ?>
                                              <?php echo ($qizStatus!=NULL && isset($qizStatus[$quiz['qcode']]))? label($qizStatus[$quiz['qcode']]['label']):label('noProgress'); ?></td>
                                              <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                                <td>
                                                  <button value="edit" name="edit" class="btn btn-default" type="submit">
                                                    <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('quiz'); ?>
                                                  </button>
                                                </td><?php
                                              }?></tr><?php
                                            } ?>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php endif; ?>

                              <?php if (sizeof($surveys) > 0 && !$cos_hidden ) : ?>
                                <div class="panel panel-default panel-faq">
                                  <div class="panel-heading">
                                    <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#over-survey">
                                      <h4 class="panel-title">
                                        <img style="width:15px;height:15px;" src="<?php echo REAL_PATH."/assets/images/icons/".$svCom; ?>">
                                        <?php echo label('survey'); ?>
                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                      </h4>
                                    </a>
                                  </div>
                                  <div id="over-survey" class="panel-collapse collapse">
                                    <div class="panel-body">
                                      <div class="table-wrapper">
                                        <table style="cursor:default" class="table table-striped" id="student-table">
                                          <thead>
                                            <tr>
                                              <th><?php echo label('number'); ?></th>
                                              <th><?php echo label('sName'); ?></th>
                                              <th><?php echo label('status'); ?></th>
                                              <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                                <th><?php echo label('manage').label('survey'); ?></th><?php
                                              } ?></tr>
                                            </thead>
                                            <tbody>
                                              <?php $row = 1; foreach ($surveys as $survey) { ?>
                                                <tr style="cursor:pointer" onclick="window.document.location='<?php echo REAL_PATH.'/survey/detail/'.$survey['scode'] ?>';">
                                                  <th scope="row"><?php echo $row; $row++; ?></th>
                                                  <td><?php echo $survey['sname'] ?></td>
                                                  <td style="font-weight:bold;color:<?php echo $svStatus[$survey['scode']] == 'done' ? '#0F0': '#F00' ; ?>;"><?php echo label($svStatus[$survey['scode']]); ?></td>
                                                  <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                                                    <td>
                                                      <button value="edit" name="edit" class="btn btn-default" type="submit">
                                                        <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('survey'); ?>
                                                      </button>
                                                    </td><?php
                                                  } ?></tr><?php
                                                } ?>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  <?php endif; ?>

                                  <?php if (in_array($role, array("superadmin", "admin", "manager"))) : ?>
                                    <div class="panel panel-default panel-faq">
                                      <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#over-enroll">
                                          <h4 class="panel-title">
                                            <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_settings.png">
                                            <?php echo label('student').label('enroll'); ?>
                                            <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                          </h4>
                                        </a>
                                      </div>
                                      <div id="over-enroll" class="panel-collapse collapse">
                                        <div class="panel-body">
                                          <div class="row">
                                            <div class="col-sm-2 col-md-2">
                                              <form action="<?php echo REAL_PATH."/course/manage/".$course['ccode']; ?>" method="post">
                                                <button class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png"> <?php echo label('manage').label('learner'); ?></button>
                                              </form>
                                            </div>
                                            <div class="col-md-8"></div>
                                            <div class="col-md-2">
                                              <a href="<?php echo REAL_PATH."/course/empEnrollToExcel/".$course['ccode']; ?>" target="_blank" class="btn btn-default" ><?php echo label('export'); ?></a>
                                            </div>
                                          </div>
                                          <div class="table-wrapper ">
                                            <table style="cursor:default" class="table table-striped" id="student-table">
                                              <thead>
                                                <tr>
                                                  <th><?php echo label('number'); ?></th>
                                                  <th><?php echo label('name'); ?></th>
                                                  <!-- <th><?php //echo label('last_act'); ?></th> -->
                                                  <th><?php echo label('status'); ?></th>
                                                  <th><?php echo label('manage').label('student'); ?></th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <form method="post" enctype="multipart/form-data">
                                                  <input type="hidden" name="ccode" value="<?php echo $course['ccode'] ?>">
                                                  <?php $row = 1; foreach ($real_l as $emp) { ?>
                                                    <tr>
                                                      <th scope="row"><?php echo $row; $row++; ?></th>
                                                      <td><?php echo $emp['prefix'].$emp['fname'].' '.$emp['lname']; ?></td>
                                                      <!-- <td><?php //echo $emp_time[$emp['emp_c']] ?></td> -->
                                                      <td><?php echo label('real_student'); ?></td>
                                                      <td><input type="button" class="del-st btn btn-danger" value="<?php echo label('delete').label('student'); ?>" id="<?php echo $emp['emp_c'] ?>"></td>
                                                    </tr>
                                                  <?php } ?>
                                                  <?php foreach ($sub_l as $emp) { ?>
                                                    <tr>
                                                      <th scope="row"><?php echo $row; $row++; ?></th>
                                                      <td><?php echo $emp['prefix'].$emp['fname'].' '.$emp['lname']; ?></td>
                                                      <!-- <td><?php //echo $emp_time[$emp['emp_c']] ?></td> -->
                                                      <td><?php echo label('sub_student'); ?></td>
                                                      <td><input type="button" class="add-st btn btn-success" value="<?php echo label('add').label('student'); ?>" id="<?php echo $emp['emp_c'] ?>"></td>
                                                    </tr>
                                                  <?php } ?>
                                                  <?php foreach ($cancel_l as $emp) { ?>
                                                    <tr>
                                                      <th scope="row"><?php echo $row; $row++; ?></th>
                                                      <td><?php echo $emp['prefix'].$emp['fname'].' '.$emp['lname']; ?></td>
                                                      <!-- <td><?php //echo $emp_time[$emp['emp_c']] ?></td> -->
                                                      <td><?php echo label('cancel_student'); ?></td>
                                                      <td><input type="button" class="add-st btn btn-success" value="<?php echo label('add').label('student'); ?>" id="<?php echo $emp['emp_c'] ?>"></td>
                                                    </tr>
                                                  <?php } ?>
                                                </form>
                                              </tbody>
                                            </table>
                                            <div id="del-ext" style="display:none;position:fixed;z-index:1;left:0;top:0;width:100%;height:100%;overflow:auto; background-color: rgb(0,0,0);background-color: rgba(0,0,0,0.4);padding-top: 60px;">
                                              <form method="post" style="margin: 5px auto;border: 1px solid #888;width: 80%;">
                                                <div class="row" style="background-color:#fff;margin: 5px auto;border: 1px solid #888;width: 80%;padding:0% 3% 3% 3%;">
                                                  <h2><?php echo label('delete').label('student'); ?></h2>
                                                  <div class="row">
                                                    <div class="col-md-3"><?php echo label('choose').label('type') ?> : </div>
                                                    <select name="type" class="form-control col-md-9" style="height:2em;">
                                                      <option value="noshow"><?php echo label('noshow'); ?></option>
                                                      <option value="sick"><?php echo label('sick'); ?></option>
                                                      <option value="business"><?php echo label('business'); ?></option>
                                                      <option value="cancelbef"><?php echo label('cancelbef'); ?></option>
                                                      <option value="cancelaf"><?php echo label('cancelaf'); ?></option>
                                                    </select>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-3"><?php echo label('qiz_etc') ?></div><input class="form-control col-md-9" type="text" name="note">
                                                  </div><br>
                                                  <input class="sub-ext col-md-12 btn btn-danger" type="button" value="<?php echo label('delete').label('student'); ?>">
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  <?php endif; ?>

                                </div>
                              </div>
                            </div>

                            <!-- Overview Part -->
                          <?php }
                          } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        
      </div>
    </div>

          <!--footer-->
          <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
          <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
          <script src="<?php echo REAL_PATH;?>/assets/js/detail.js"></script>
          <script type="text/javascript">var base_url = "<?php echo REAL_PATH ?>";</script>
    </div>
  </body>
</html>
