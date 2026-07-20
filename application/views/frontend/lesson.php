<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/course.css" rel="stylesheet">
<script src="<?php echo REAL_PATH;?>/assets/js/detail.js"></script>
<script src="<?php echo REAL_PATH;?>/assets/js/lesson.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
                  <h2><?php echo $lesson['les_name'] ?></h2>
                  <div class="dashpageWrap course">
                    <h4 style="color:#606060;"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('dateMod').' '.$lesson['time_mod']; ?></h4>
                    <hr>
                    <?php echo $lesson['les_info'];?><br>
                    <?php if (!empty($videos)) {
                      foreach ($videos as $video) {
                        if ($video['type'] == 'upload'){?>
                          <div class="col-md-12 embed-responsive embed-responsive-16by9" style="margin:10px;">
                            <video id="upload-vid" controls controlsList="nodownload">
                              <source src="<?php echo REAL_PATH.'/uploads/'.$video['video']; ?>" type="video/mp4" frameborder="0" allowfullscreen>
                              </video>
                            </div><?php
                          } else {
                            $path = $video['video'];?>
                            <div class="col-md-12 embed-responsive embed-responsive-16by9" style="margin:10px;">
                              <iframe id="player" class="embed-responsive-item" src="<?php echo $path;?>" frameborder="0" allowfullscreen></iframe>
                            </div><?php
                          } ?><div class="tricker col-md-12 embed-responsive embed-responsive-16by9" style="opacity:0.5;background-color:#000;margin:-10px;margin-left:-25px;position:absolute;cursor:pointer;"></div>
                          <div class="tricker col-md-12 embed-responsive embed-responsive-16by9" style="margin:-10px;margin-left:-25px;position:absolute;cursor:pointer;">
                            <form method="post" enctype="multipart/form-data" action="<?php echo REAL_PATH.'/lesson/updateTrans/'.$lcode; ?>">
                              <div style="margin-left:42%;margin-top:10%;">
                                <input type="hidden" value="<?php echo $lcode ?>" name="lcode";>
                                <input type="hidden" value="<?php echo $video['type'] ?>" name="type";>
                                <input type="button" name="pass" value="<?php echo label('start').label('Les_video') ?>">
                              </div>
                            </form>
                          </div><?php
                        }
                      } 
                      if (isset($scorm) && $scorm) {?>
                        <div class="scorm-player col-md-10" style="width: 100%;height: 80%">
                          <iframe class="frame-player" src="<?php echo $callPath; ?>" width="100%" height="100%" style="overflow:hidden;height:450px;width:800px" frameborder="0" allowfullscreen></iframe><br/>
                          <button class="btn btn-warning col-md-12 full-Screen" onclick="makeFullScreen()"><?php echo label('full_screen') ?></button><br>
                          <button class="btn btn-warning col-md-12 normal-Screen" style="display:none;position:fixed;width:100%;height:4vh;left:0px; bottom:0px; right:0px; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" onclick="makeNormalScreen()"><?php echo label('normal_screen') ?></button><br>
                        </div>

                        <script type="text/javascript">
                          $(".frame-player").width($('.scorm-player').width());
                          $(".frame-player").height($('.scorm-player').height());
                        </script>
                        <?php
                      } ?>
                      <?php if ( in_array($role, array("superadmin")) ) { ?>
                        <div class='col-md-12'  style="margin:10px;">
                          <h3><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo.png">  <?php echo label('dateStart').' '.$lesson['time_start']; ?></h3>
                          <h3><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_photo.png">  <?php echo label('dateExpired').' '.$lesson['time_end'];?></h3>
                        </div>
                      <?php } ?>
                      <?php
                      if ($nextHop == 'none') {
                        $code = $ccode;
                        $path = base_url().'course/detail/';
                        $label = label('backTo').label('course');
                      } else{
                        $code = $nextHop['lcode'];
                        $path = base_url().'lesson/detail/';
                        $label = label('nextLesson');
                      }
                      if ($backHop == 'none') {
                        $backcode = $ccode;
                        $backpath = base_url().'course/detail/';
                        $backlabel = label('backTo').label('course');
                      } else{
                        $backcode = $backHop['lcode'];
                        $backpath = base_url().'lesson/detail/';
                        $backlabel = label('backLesson');
                      }
                      ?>
                      <form action="<?php echo base_url().'course/detail/'.$ccode; ?>" class="form-inline control-btn">
                        <div class="form-group">
                          <div class="col-sm-2 col-md-2">
                            <button class="btn btn-success" type="submit"><?php echo label('backTo').label('course'); ?></button>
                          </div>
                        </div>
                      </form>
                      <?php if ($backHop != 'none') { ?>
                        <form action="<?php echo $backpath.$backcode; ?>" class="form-inline control-btn">
                          <div class="form-group">
                            <div class="col-sm-2 col-md-2">
                              <button class="btn btn-info" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_jump_back.png"> <?php echo $backlabel; ?></button>
                            </div>
                          </div>
                        </form>
                      <?php } ?>
                      <?php if ($nextHop != 'none') { ?>
                        <form action="<?php echo $path.$code; ?>" class="form-inline control-btn">
                          <div class="form-group">
                            <div class="col-sm-2 col-md-2">
                              <button class="btn btn-info" type="submit"><img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_jump_next.png"> <?php echo $label; ?></button>
                            </div>
                          </div>
                        </form>
                      <?php } ?>

                      <hr>
                      <?php if (isset($files) && $files) {?>
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="tab-content faq-cat-content">
                              <div class="tab-pane active in fade" id="faq-cat-1">
                                <div class="panel-group" id="accordion-cat-1">
                                  <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                      <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#lesson_files">
                                        <h4 class="panel-title">
                                          <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_article.png">
                                          <?php echo label('lesson_file'); ?>
                                          <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                        </h4>
                                      </a>
                                    </div>
                                    <div id="lesson_files" class="panel-collapse collapse">
                                      <div class="panel-body">
                                        <div class="table-wrapper">
                                          <table style="cursor:default" class="table table-striped" id="student-table">
                                            <thead>
                                              <tr>
                                                <th align="center"><b><?php echo label('number'); ?></b></th>
                                                <th align="center"><b><?php echo label('file_name'); ?></b></th>
                                                <th align="center"><b><?php echo label('download_file'); ?></b></th>
                                                <?php if (in_array($role, array("superadmin", "admin", "manager"))) { ?><th align="center"><b><?php echo label('download_count'); ?></b></th><?php } ?>
                                              </tr>
                                            </thead>
                                            <tbody>

                                              <?php $row = 1;$count = 0; foreach ($files as $file) { ?>
                                                <tr>
                                                  <th scope="row"><?php echo $row; $row++; ?></th>
                                                  <td><a onclick="Click_log('<?php echo $file['id']; ?>','<?php echo $emp_c; ?>')" href="<?php echo REAL_PATH.'/uploads/'.$file['path_file']; ?>" target="_blank"><?php echo $file['path_file']?></a></td>
                                                  <td><a onclick="Click_log('<?php echo $file['id']; ?>','<?php echo $emp_c; ?>')" href="<?php echo REAL_PATH.'/uploads/'.$file['path_file']; ?>" target="_blank" download><?php echo label('download_this'); ?></a></td>
                                                  <?php if (in_array($role, array("superadmin", "admin", "manager"))) { ?><td align="center"><a onclick="Count_log('<?php echo $file['id']; ?>')" ><?php echo number_format($arr_count[$count]['count']); ?></a></td><?php } ?>
                                                </tr><?php
                                              } ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div><?php
                      } if (in_array($role, array("superadmin", "admin", "manager"))) { ?>
                        <div class="col-md-12">
                          <div class="row">
                            <?php if (isset($scorm) && $scorm){ ?>
                              <div class="col-sm-2 col-md-2">
                                <button onclick="window.document.location='<?php echo base_url().'scorm/edit/'.$lesson['lcode']; ?>'" style="margin-bottom:10%;" value="edit" name="edit" class="btn btn-default" type="submit">
                                  <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('lesson'); ?>
                                </button>
                              </div>
                          <?php } else {?>
                            <div class="col-sm-2 col-md-2">
                              <button onclick="window.document.location='<?php echo base_url().'lesson/edit/'.$lesson['lcode']; ?>'" style="margin-bottom:10%;" value="edit" name="edit" class="btn btn-default" type="submit">
                                <img src="<?php echo REAL_PATH; ?>/assets/images/icons/icn_edit_article.png">  <?php echo label('edit').label('lesson'); ?>
                              </button>
                            </div>
                            <?php
                          } ?>
                            <div class="col-sm-2 col-md-2">
                              <button onclick="window.document.location='<?php echo base_url().'lesson/delete/'.$lesson['lcode']; ?>'" style="margin-bottom:10%;right:0; position:relative;" name="method" value="delete" class="btn btn-danger" type="submit">
                                <img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png">  <?php echo label('delete').label('lesson'); ?>
                              </button>
                            </div>
                          </div>
                        </div><?php
                      } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade bs-example-modal-lg" id="modal-showquestion">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <div class="box-body">
                  
                                <div id="taa_table" class="table-responsive">
                                   <table id="tbtable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th><?php echo label('r_namelist'); ?></th>
                                        <th><?php echo label('m_organization'); ?></th>
                                        <th><?php echo label('m_company'); ?></th>
                                        <th><?php echo label('m_branch'); ?></th>
                                        <th><?php echo label('download_count'); ?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
            </div>
          </div>
        </div> 
      <!--footer-->
      <script>
        $.fn.dataTable.ext.errMode = "none";
        function makeFullScreen() {
          $('.full-Screen').hide();
          $('.scorm-player').addClass('fullScreen');
          $(".frame-player").width($(window).width()-90);
          $(".frame-player").height($(window).height()-90);
          $('.frame-player').addClass('fullScreen');
          $('.normal-Screen').show();
        }

        function makeNormalScreen() {
          $('.normal-Screen').hide();
          $('.fullScreen').removeClass('fullScreen');
          $('.scorm-player').removeClass('fullScreen');
          $(".frame-player").width($('.scorm-player').width());
          $(".frame-player").height($('.scorm-player').height());
          $('.full-Screen').show();
        }
        function Count_log(id=''){
          var lang = '<?php echo $lang; ?>';
          console.log(id);
          $('#modal-showquestion').modal('show');
          $('#tbtable').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("tbtable", message);
          }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/lesson/fetch_detail_status/'+id+'/'+lang,
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
                },
          });
        }
        function Click_log(id='',emp_id=''){
          console.log(id,emp_id);

            $.ajax({
              url: '<?=base_url()?>index.php/lesson/update_log/'+id+'/'+emp_id,
              type: 'POST',
              success: function(data){
                console.log(data);
                //$('#multiselect').html(data);
              }
            });
        }
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          playerVars: {autoplay: 1},
          videoId: <?php if (!empty($videos) && $video['video']){
            $path = explode('/', $video['video']);
            echo "'".$path[sizeof($path)-1]."'";
          } else {
            echo "''";
          } ?>,
          events: {
            'onReady': onPlayerReady
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady() {
        player.playVideo();
      }
      var base_url = "<?php echo REAL_PATH; ?>";


      </script>
      <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
      <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

    </div>
  </body>
  </html>
