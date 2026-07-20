<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<script src="<?php echo REAL_PATH;?>/assets/js/quiz.js?v=<?php echo rand(1001,1001); ?>"></script>
</head>
<body>
  <div id="superwrapper">
    <!--Nav-->
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <!--content-->
    <div class="container dashboard main">
      <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-custom-arrow" aria-hidden="true"></i></a>
      <div class="row">
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <div class="content dashWrap">
          <h1 style="color:#000;"><?php echo ($title_q['type']==0)?label('preExam'):label('finalExam'); echo ": ".$title_q['name']; ?></h1>
          <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
            <div class="col-md-2">
              <form action='<?php echo REAL_PATH.'/quiz/createQuestion/'.$qcode; ?>'>
                <button type="submit" class="btn btn-defualt" style="margin-bottom:5px;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_new_article.png"> <?php echo label('create').label('question'); ?></button>
              </form>
            </div>
            <div class="col-md-3">
              <form action='<?php echo REAL_PATH.'/quiz/edit/'.$qcode; ?>'>
                <button type="submit" class="btn btn-defualt" style="margin-bottom:5px;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png"> <?php echo label('edit').label('quiz'); ?></button>
              </form>
            </div>
            <div>
              <form action='<?php echo REAL_PATH.'/quiz/delete/'.$qcode; ?>'>
                <button type="submit" class="btn btn-danger" style="margin-bottom:5px;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_trash_w.png"> <?php echo label('delete').label('quiz'); ?></button>
              </form>
            </div>
            <?php
          }

          $qNo = 1;
          if ($questions != FALSE) {
            foreach ($questions as $each) {
              $id = $each['qst']['id'];
              $qHeader = $each['qst']['questions_name'];
              $qInfo = isset($each['type']['que_info']) ? $each['type']['que_info'] : $each['qst']['questions_Info'];
              $qScore = $each['qst']['score'];
              $type = $each['qst']['type'];

              $answer = $answers[$each['qst']['id']];

              ?>
              <div class="dashElement page quiz">
                <div class="row">
                  <div class="col-md-12 col-sm-height">
                    <div class="col-sm-4 quizQ inside inside-full-height">
                      <h3><?php echo label('topic').' '.$qNo; ?></h3>
                      <p><?php echo !empty($answer) ? label('answered') : label('n_answered'); ?></p>
                      <p><?php echo label('mark_out_of').' '.$qScore; ?></p>
                      <!-- <p class="flagQ">Flag question</p> -->
                      <?php if (in_array($role, array("superadmin", "admin", "manager"))) {?>
                        <div class="col-md-6 editQ">
                          <form action='<?php echo REAL_PATH.'/quiz/editQuestion/'.$id.'/'.$qcode; ?>'>
                            <button type="submit" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png"> <?php echo label('edit').label('question'); ?></button>
                          </form>
                        </div>
                        <div class='col-md-6 editQ'>
                          <form action='<?php echo REAL_PATH.'/quiz/deleteQuestion/'.$id.'/'.$qcode; ?>'>
                            <button type="submit" class="btn btn-danger" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_trash_w.png"> <?php echo label('delete').label('question'); ?></button>
                          </form>
                        </div>
                        <?php if (in_array($type, array('sa', 'sub'))) {?>
                          <div class='col-md-6' style="margin-top:10px;">
                            <form action='<?php echo REAL_PATH.'/quiz/checkAnswer/'.$course[$lang]['ccode'].'/'.$qcode.'/'.$id; ?>'>
                              <button type="submit" class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_security.png"> <?php echo label('check').label('answer'); ?></button>
                            </form>
                          </div>
                          <?php
                        }
                      }?>
                    </div>
                    <div class="col-sm-8 quizA">
                      <p><?php echo $qHeader ?></p>
                      <p><?php echo $qInfo ?> : </p>
                      <form method="post" enctype="multipart/form-data" action="<?php echo REAL_PATH.'/quiz/saveQuestion/'.$id; ?>">
                        <input type="hidden" name="type" value="<?php echo $type; ?>" class="<?php echo $qNo; ?>">
                        <input type="hidden" name="code" value="<?php echo $qcode; ?>" class="<?php echo $qNo; ?>">
                        <input type="hidden" name="quest_id" value="<?php echo $id; ?>" class="<?php echo $qNo; ?>">
                        <input type="hidden" name="enStatus" value="<?php echo $enStatus; ?>">
                        <?php $typeData = $each['type']; $choice = array(); $cAnswer = array(); $preC = array('a','b','c','d','e');
                        if ( in_array($type, array('twoChoice', 'match', 'multi') ) ) {
                          $c1 = $typeData['c1'];
                          $c2 = $typeData['c2'];
                          array_push($choice, $c1);
                          array_push($choice, $c2);
                        }
                        if (in_array($type, array('match', 'multi') ) ){
                          $c3 = $typeData['c3'];
                          $c4 = $typeData['c4'];
                          $c5 = $typeData['c5'];
                          array_push($choice, $c3);
                          array_push($choice, $c4);
                          array_push($choice, $c5);
                        }
                        if(in_array($type, array('multi') )){

                        }
                        if (in_array($type, array('twoChoice', 'dd') ) ){
                          $ans = $typeData['ans'];
                          array_push($cAnswer, $ans);
                        }
                        if ($type == 'match') {
                          $a1 = $typeData['a1'];
                          $a2 = $typeData['a2'];
                          $a3 = $typeData['a3'];
                          $a4 = $typeData['a4'];
                          $a5 = $typeData['a5'];
                          array_push($cAnswer, $a1);
                          array_push($cAnswer, $a2);
                          array_push($cAnswer, $a3);
                          array_push($cAnswer, $a4);
                          array_push($cAnswer, $a5);
                          $index = 1;
                          $outputAns = array();
                          foreach ($cAnswer as $each) {
                            $outputAns[$each] = $preC[$index-1];
                            $index++;
                          }?>

                          <div class="row">
                            <div class="col-md-12 matchingWrap">
                              <div class="col-md-6">
                                <?php $index=1; foreach ($choice as $each): if (empty($each)) break;?>
                                  <div class="form-group">
                                    <div class="col-xs-3 frontMatch">
                                      <input type="hidden" value="<?php echo $answer['ans'.$index]; ?>" class="<?php echo $qNo; ?>" name="ans[]" id="idans<?php echo $qNo.'cut'.$index?>">
                                      <input type="text" style="width:30px;" class="form-control pre_ans" value="<?php echo (empty($answer)) ? '':$outputAns[$answer['ans'.$index]]; ?>" id="ans<?php echo $qNo.'cut'.$index?>">
                                    </div>
                                    <div class="col-xs-9 backMatch">
                                      <?php echo $each ?>
                                    </div>
                                  </div><br><?php $index++; endforeach; ?>
                                </div>
                                <div class="col-md-6">
                                  <?php $index=1; foreach ($cAnswer as $each): if (empty($each)) break;?>
                                  <div class="form-group">
                                    <div class="col-xs-3 frontMatch">
                                      <?php echo $preC[$index-1].'.' ?>
                                    </div>
                                    <div class="col-xs-9">
                                      <p id="<?php echo $preC[$index-1].'ans'.$qNo ?>"><?php echo $each ?></p>
                                    </div>
                                  </div><?php $index++; endforeach; ?>
                                </div>
                              </div>
                            </div><?php
                          } else if ($type == 'multi') {
                            $limit = $typeData['limit5'];?>
                            <input type="hidden" name="limit" value="<?php echo $limit; ?>" class="<?php echo $qNo; ?>">
                            <?php $style_color = "";
                            if($limit == 1){$index=1; foreach ($choice as $each): if (empty($each)) break;

                              if($qizStatus[$qcode]['label']== 'pass'){
                                if($typeData['ans1']==$each){
                                    $style_color = "color:#27ae60";
                                }else{
                                    $style_color = "color:#c0392b";
                                }

                                if(!empty($answer) && in_array($each, $answer)){
                                  $style_color = $style_color;
                                }else if($typeData['ans1']==$each){
                                  $style_color = "color:#f1c40f";
                                }else{
                                  $style_color = "";
                                }
                              }
                              ?>
                              <div class="radio">
                                <label style="<?php echo $style_color; ?>">
                                  <input type="radio" name="answer" class="<?php echo $qNo; ?>" value="<?php echo $each ?>" <?php echo (!empty($answer) && in_array($each, $answer)) ? 'checked':''; ?>>
                                  <?php echo $preC[$index-1].'. '.$each; ?>
                                </label>
                              </div><?php $index++; endforeach;
                            } else {$index=1; foreach ($choice as $each): if (empty($each)) break;?>
                              <div class="radio">
                                <label>
                                  <input type="checkbox" name="ans[]" class="<?php echo $qNo; ?>" value="<?php echo $each ?>" <?php echo (!empty($answer) && in_array($each, $answer)) ? 'checked':''; ?>>
                                  <?php echo $preC[$index-1].'. '.$each; ?>
                                </label>
                              </div>
                              <?php $index++; endforeach;
                            }
                          } else if ($type == 'dd') {
                            $imgs = $typeData['imgs'];
                            $limit = $typeData['limit5'];
                            $info = $typeData['que_info'];?>

                            <div class="row">
                              <input type="hidden" name='dropAnswer' value="<?php echo $answer['ans1'] ?>" class="<?php echo $qNo; ?>" >
                              <div class="col-md-12 matchingWrap">
                                <div class="col-md-6 dragQ">
                                  <?php $count = 1; foreach ($imgs as $img) { ?>
                                    <div id="divDrag<?php echo $count.$qNo; ?>" ondrop="drop(event)" ondragover="allowDrop(event)" class="dragWrap">
                                      <img id="drag<?php echo $count.$qNo; ?>" src="<?php echo REAL_PATH;?>/uploads/<?php echo $img['image']; ?>" draggable="true" ondragstart="drag(event)" width="50" height="50">
                                    </div><?php $count++;
                                  } ?>
                                </div>
                                <div class="col-md-6">
                                  <div class="dragAns">
                                    <div id="divDrop<?php echo $qNo; ?>" ondrop="drop(event)" ondragover="dragover_handler(event);" class="dragWrap"></div>
                                    <div class="dragAns"></div>
                                  </div>
                                </div>
                              </div>
                            </div><?php
                          } else if ($type == 'twoChoice'){?>
                            <div class="radio">
                              <label>
                                <input type="radio" name="answer" class="<?php echo $qNo; ?>"  value="<?php echo $c1 ?>" <?php echo $c1 == $answer['ans1'] ? 'checked':''; ?>>
                                a. <?php echo $c1 ?>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="answer"  class="<?php echo $qNo; ?>" value="<?php echo $c2 ?>" <?php echo $c2 == $answer['ans1'] ? 'checked':''; ?>>
                                b. <?php echo $c2 ?>
                              </label>
                            </div><?php
                          } else if ($type == 'sa'){?>
                            <input type="text" class="form-control <?php echo $qNo; ?>" name='answer' value="<?php echo $answer['ans1']; ?>"><?php
                          } else if ($type == 'sub') { ?>
                            <textarea class="form-control <?php echo $qNo; ?>" rows="3" name='answer'><?php echo $answer['ans1']; ?></textarea><?php
                          }?>
                          <div style="margin-top:10px;">
                            <!--<button type="submit" type="button" name="save"><?//php echo label('saveR').label('question') ?></button>-->
                            <input type="button" value="<?php echo label('saveR').label('question') ?>" style="display:none;" name="save" class="btn-danger" id="<?php echo $qNo; ?>">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div><?php $qNo++;
              }
            }
            if($qizStatus[$qcode]['label']!= 'pass'){
            ?>

              <form action="<?php echo REAL_PATH.'/quiz/submitQuiz/'.$qcode; ?>">
                <div class="col-md-12">
                  <input type="hidden" name="url_submit" value="<?php echo REAL_PATH.'/quiz/submitQuiz/'.$qcode; ?>" />
                  <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2"><button type="button" class="form-control btn btn-success bt-allsave " data-qcode="<?php echo $qcode; ?>" name="bt-save"> <?php echo label('saveR').label('answer'); ?></button></div>
                    <div class="col-md-2"><button type="button" class="form-control btn btn-success bt-sendAnswer" data-qcode="<?php echo $qcode; ?>" name="bt"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_tags.png"> <?php echo label('sendAnswer'); ?></button></div>
                  </div>
                </div>
              </form>
            <?php } ?>
          </div>
        </div>
      </div>
      <script>
      var base_url = "<?php echo REAL_PATH; ?>";
      var lang = "<?php echo $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ; ?>"
      </script>
      <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
      <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    </div>
  </body>
  </html>
