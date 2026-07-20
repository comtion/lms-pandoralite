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

        <?php $this->load->view('frontend/inc/inc-sidemenu.php');?>

        <div class="content dashWrap">
          <div class="dashElement page">
            <div class="row">
              <div class="col-md-12">
                <div class="dashHeader">
                  <h2><?php echo $quest['qst']['questions_name']; ?></h2><hr>
                  <div class="dashpageWrap course">
                    <p><?php echo $quest['qst']['questions_Info']; ?></p>
                      <div class="panel-body">
                        <?php if ($answers) { ?>
                        <div class="table-wrapper">
                          <table style="cursor:default" class="table table-striped" id="student-table">
                            <thead>
                              <tr>
                                <th><?php echo label('number'); ?></th>
                                <th><?php echo label('p_emp_c'); ?></th>
                                <th><?php echo label('name'); ?></th>
                                <th><?php echo label('answer'); ?></th>
                                <th><?php echo label('check').label('answer'); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $row = 1; foreach ($answers as $answer) {?>
                                <tr>
                                  <th scope="row"><?php echo $row; $row++; ?></th>
                                  <td><?php echo $answer['emp_c'] ?></td>
                                  <td><?php echo $answer['emp_name'] ?></td>
                                  <td><?php echo $answer['ans']; ?></td>
                                  <td><form action="<?php echo REAL_PATH.'/quiz/saveScore'; ?>" class="form-inline" method="post">
                                    <input type="hidden" name="quest_id" value="<?php echo $quest['qst']['id']; ?>">
                                    <input type="hidden" name="emp_c" value="<?php echo $answer['emp_c']; ?>">
                                    <input type="hidden" name="max" value="<?php echo $quest['qst']['score']; ?>">
                                    <input type="score" name="score">
                                    <button name="saveBT" value="normal" class="btn btn-default display" type="submit" ><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit.png"> <?php echo label('save') ?></button>
                                  </form></td>
                                </tr><?php
                              } ?>
                            </tbody>
                          </table>
                        </div><?php
                      } ?>
                        <form action="<?php echo base_url().'quiz/detail/'.$quiz_id; ?>" class="form-inline">
                          <div style="margin:10px;" class="form-group">
                            <div class="col-sm-2 col-md-2">
                              <button class="btn btn-default" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_jump_back.png"> <?php echo label('backTo').label('quiz'); ?></button>
                            </div>
                            <div class="col-md-8"></div>
                            <?php if( sizeof( $answers ) > 0 ){ ?>
                            <div class="col-md-2">
                              <a href="<?php echo REAL_PATH."/quiz/exportAnswer/".$ccode."/".$qcode."/".$qstcode; ?>" target="_blank" class="btn btn-default"><?php echo label('export'); ?></a>
                            </div>
                          <?php } ?>
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
    </div>

    <!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    <script src="<?php echo REAL_PATH;?>/assets/js/detail.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/js/checkAnswer.js"></script>

  </div>
</body>
</html>
