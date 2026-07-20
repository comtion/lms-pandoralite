<div class="dashSidemenu">
  <div class="dashNavigation">
    <div class="row">
      <div class="col-sm-12">
        <ul>
          <li class="<?php echo $page == 'home' ? 'active' : '' ?>"><i class="fa fa-home" aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/home"><?php echo label('home'); ?></a></span></li>
          <li class="<?php echo $page == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt" aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></span></li>
          <?php if($role != "superadmin"): ?>
          <li class="<?php echo $page == 'profile' ? 'active' : '' ?>"><i class="fa fa-user " aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/profile"><?php echo label('profile'); ?></a></span></li>
          <?php endif ?>
          <!-- <li class="<?php //echo $page == 'calendar' ? 'active' : '' ?>"><i class="fa fa-calendar" aria-hidden="true"></i><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/calendar"><?php //echo label('calendar'); ?></a></span></li>
          <li class="<?php //echo (in_array($page, array('blog/all', 'blog/my', 'blog/detail', 'blog/create', 'blog/edit'))) ? 'subactive' : '' ?>"><i class="fa fa-comment-o" aria-hidden="true"></i><span class="dashMenu"><?php //echo label('blog'); ?></span>
          <ul>
            <div>
              <li class="<?php //echo $page == 'blog/all'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/blog/all"><?php //echo label('allblog'); ?></a></span></li>
              <li class="<?php //echo $page == 'blog/my'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/blog/my"><?php //echo label('myblog'); ?></a></span></li>
            </div>
          </ul>
        </li> -->
          <li class="<?php echo (in_array($page, array('workgroup/loadWorkGroup'))) ? 'subactive' : '' ?>"><i class="fas fa-book-open" aria-hidden="true"></i><span class="dashMenu"><?php echo label('learningsystem'); ?></span>
          <ul>
            <div>
              <li class="<?php echo $page == 'workgroup/loadWorkGroup'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/workgroup/loadWorkGroup"><?php echo label('allworkgroup'); ?></a></span></li>
              <li class="<?php echo $page == 'coursegroup/loadCourseGroup'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/coursegroup/loadCourseGroup"><?php echo label('allcoursegroup'); ?></a></span></li>
              <li class="<?php echo $page == 'course/available'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/course/available"><?php echo label('allcos'); ?></a></span></li>
              <?php if(in_array($role, array("user"))):?>
                <li class="<?php echo $page == 'course/loadCourse'  ? 'active' : '' ?>"><i aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/course/loadCourse"><?php echo label('mycos'); ?></a></span></li>
              <?php endif ?>
              <li>---------------<li>
            <?php if(in_array($role, array("superadmin"))):?>
              <!-- <li class="<?php //echo $page == 'workgroup/create'  ? 'active' : '' ?>">
                <i aria-hidden="true"></i>
                <span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/workgroup/create">+ <?php //echo label('create');?><?php //echo label('workgroup');?></a></span>
              </li> -->
              <li class="<?php echo $page == 'coursegroup/create'  ? 'active' : '' ?>">
                <i aria-hidden="true"></i>
                <span class="dashMenu"><a href="<?php echo REAL_PATH;?>/coursegroup/create">+ <?php echo label('create');?><?php echo label('coursegroup');?></a></span>
              </li>
              <li class="<?php echo $page == 'course/create'  ? 'active' : '' ?>">
                <i aria-hidden="true"></i>
                <span class="dashMenu"><a href="<?php echo REAL_PATH;?>/course/create">+ <?php echo label('create');?><?php echo label('course');?></a></span>
              </li>
            <?php endif ?>
            </div>
          </ul>
          </li>
          <?php if(in_array($role, array("superadmin", "admin"))): ?>
            <li class="<?php echo (in_array($page, array('report/loadTranscript', 'report/loadSubordinate', 'report/loadCourse', 'report/enrolledReport'))) ? 'subactive' : '' ?>"><i class="fas fa-newspaper" aria-hidden="true"></i><span class="dashMenu"><?php echo label('report'); ?></span>
            <ul>
              <div>
                <?php if(!in_array($role, array("superadmin", "admin"))): ?>
                <!-- <li class="<?php //echo $page == 'report/loadTranscript'  ? 'active' : '' ?>"><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/report/loadTranscript"><?php //echo label('transcript'); ?></a></span></li> -->
                <?php endif ?>
                <!-- <li class="<?php //echo $page == 'report/loadSubordinate'  ? 'active' : '' ?>"><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/report/loadSubordinate"><?php //echo label('subordinate'); ?></a></span></li> -->
                <?php if(in_array($role, array("superadmin", "admin", "manager"))): ?>
                <li class="<?php echo $page == 'report/loadCourse'  ? 'active' : '' ?>"><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/report/loadAllCourse"><?php echo label('course') ?></a></span></li>
                <?php endif ?>
                <!-- <li class="<?php //echo $page == 'report/enrolledReport'  ? 'active' : '' ?>"><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/report/enrolledReport"><?php //echo label('enrolledreport') ?></a></span></li> -->
              </div>
            </ul>
            </li>
          <?php endif ?>


          <?php if(in_array($role, array("superadmin"))): ?>
            <!-- Course & Create Cert -->
            <li class="<?php echo (in_array($page, array('certificate/create'))) ? 'subactive' : '' ?>">
              <i class="fas fa-award" aria-hidden="true"></i>
              <span class="dashMenu">
                <?php echo label('certificate'); ?>
              </span>
              <ul>
                <div>
                  <li class="<?php echo $page == 'certificate/create'  ? 'active' : '' ?>">
                    <i aria-hidden="true"></i>
                    <span class="dashMenu">
                      <a href="<?php echo REAL_PATH;?>/certificate/create">
                        <?php echo '+ '.label('create');?>
                      </a>
                    </span>
                  </li>
                </div>
              </ul>
            </li>

            <!-- Pretest -->
            <li class="<?php echo (in_array($page, array('pretest/view', 'pretest/create', 'pretest/start', 'pretest/report', 'pretest/edit'))) ? 'subactive' : '' ?>">
              <i class="fas fa-book-reader" aria-hidden="true"></i>
              <span class="dashMenu">
                <?php echo label('preTest'); ?>
              </span>
              <ul>
                <div>
                  <li class="<?php echo $page == 'pretest/start'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php echo REAL_PATH;?>/pretest/view">
                        <?php echo label('preStart'); ?>
                      </a>
                    </span>
                  </li>
                  <?php if(in_array($role, array("superadmin", "admin"))):?>
                  <li class="<?php echo $page == 'pretest/create'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php echo REAL_PATH;?>/pretest/create">
                        <?php echo label('preCreate'); ?>
                      </a>
                    </span>
                  </li>
                  <li class="<?php echo $page == 'pretest/edit'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php echo REAL_PATH;?>/pretest/select">
                        <?php echo label('preEdit'); ?>
                      </a>
                    </span>
                  </li>
                  <?php endif ?>
                </div>
              </ul>
            </li>

            <!-- Specific Test -->
          <!--  <li class="<?php //echo (in_array($page, array('SpecificTest/view', 'SpecificTest/create', 'SpecificTest/start', 'SpecificTest/report', 'SpecificTest/edit'))) ? 'subactive' : '' ?>">
              <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>
              <span class="dashMenu">
                <?php //echo label('specificTest'); ?>
              </span>
              <ul>
                <div>
                  <li class="<?php //echo $page == 'SpecificTest/start'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php //echo REAL_PATH;?>/SpecificTest/view">
                        <?php //echo label('specificStart'); ?>
                      </a>
                    </span>
                  </li>
                  <?php //if(in_array($role, array("superadmin", "admin"))):?>
                  <li class="<?php //echo $page == 'SpecificTest/create'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php //echo REAL_PATH;?>/SpecificTest/create">
                        <?php //echo label('specificCreate'); ?>
                      </a>
                    </span>
                  </li> -->
                  <!-- <li class="<?php //echo $page == 'SpecificTest/edit'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php //echo REAL_PATH;?>/SpecificTest/edit">
                        <?php //echo label('specificEdit'); ?>
                      </a>
                    </span>
                  </li> -->
                  <?php //endif ?>
                  <?php //if(in_array($role, array("superadmin", "admin"))):?>
                  <!-- <li class="<?php //echo $page == 'SpecificTest/report'  ? 'active' : '' ?>">
                    <span class="dashMenu">
                      <a href="<?php //echo REAL_PATH;?>/SpecificTest/report">
                        <?php //echo label('specificReport') ?>
                      </a>
                    </span>
                  </li>
                  <?php// endif ?>
                </div>
              </ul>
            </li> -->
            <!-- <li class="<?php //echo (in_array($page, array('manage/manageUser', 'manage/manageLevel'))) ? 'subactive' : '' ?>">
              <i class="fa fa-id-card" aria-hidden="true"></i><span class="dashMenu"><?php //echo label('manage'); ?></span>
            <ul>
              <div>
                <li class="<?php //echo $page == 'manage/manageUser'  ? 'active' : '' ?>">
                  <span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/manage/manageUser"><?php //echo label('manage').label('user'); ?></a></span></li>
              </div>
            </ul>
          </li> -->
            <li class="<?php echo $page == 'log/view' ? 'active' : '' ?>"><i class="fa fa-history" aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/log/view"><?php echo label('log_record'); ?></a></span></li>
            <li class="<?php echo $page == 'dashboard/unlockAcc' ? 'active' : '' ?>">
              <i class="fa fa-unlock" aria-hidden="true"></i>
              <span class="dashMenu">
                <a href="<?php echo REAL_PATH;?>/dashboard/unlockAcc">
                  <?php echo label('unlock').label('account'); ?>
                </a>
              </span>
            </li>
            <li class="<?php echo $page == 'ManageECT' ? 'active' : '' ?>"><i class="fa fa-edit" aria-hidden="true"></i><span class="dashMenu"><a href="<?php echo REAL_PATH;?>/ManageECT"><?php echo label('ManageECT'); ?></a></span></li>
          <?php endif ?>
          <!-- <li class="<?php //echo $page == 'learningMap' ? 'active' : '' ?>"><i class="fa fa-map" aria-hidden="true"></i><span class="dashMenu"><a href="<?php //echo REAL_PATH;?>/learningMap"><?php //echo 'Learning Map'; ?></a></span></li> -->
        </ul>
      </div>
    </div>
  </div>
</div>
<?php if( null == $this->session->userdata('acceptCondition') ){ ?>
  <script>
  $(document).ready( function(){
    dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 250,
        width: 350,
        modal: true,
        buttons: {
          "ตกลง": addUser,
        },
        close: function() {
          form[ 0 ].reset();
          allFields.removeClass( "ui-state-error" );
        }
      });

      form = dialog.find( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        addUser();
      });

      $( "#create-user" ).button().on( "click", function() {
        dialog.dialog( "open" );
      });

    dialog.dialog( "open" );
  });
  function addUser(){
    $checkCon = $('input[name="accept-condition"]');
    if( $checkCon.is(":checked") ){
      $.ajax( base_url+'dashboard/updateCondition', {
        type: 'POST',
        dataType: 'json',
        data:  {  } ,
        success: function(result){
          if(result.rs){
            dialog.dialog( "close" );
          }else{
          }
        }
      }); /// end ajax add customer
      console.log('check');
    }else{
      console.log('uncheck');
    }
  }
  </script>
<?php } ?>
