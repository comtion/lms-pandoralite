    
                  <div class="col-lg-4 ">
                      <div class="card" style="max-height:350px;">
                        <div class="card-body little-profile text-center h-100">
                          <div class="pro-img <?php if($user['Is_admin']=="0"){ ?>m-t-30<?php }else{ ?>m-t-5<?php } ?>">
                                  <?php if(isset($profile['img_profile'])&&$profile['img_profile']!=""){ ?>
                                    <img src="<?php echo media_url('uploads/profile/'.$profile['img_profile'], 'uploads/profile/default_profile.jpg'); ?>"/>
                                  <?php }else{ ?>
                                    <img src="<?php echo media_url('uploads/profile/default_profile.jpg'); ?>"/>
                                  <?php } ?></div>
                          <h3 class="m-b-0" style="font-family: 'Prompt', sans-serif;">
                            
                                      <?php 
                                        if($lang=="thai"){
                                          echo $profile['fullname_th'];
                                        }else{
                                          echo $profile['fullname_en'];
                                        }
                                      ?>
                          </h3>
                          <h6 class="text-muted" style="font-family: 'Prompt', sans-serif;">
                            <?php 
                                                  if($lang=="thai"){
                                                    $ugname = $profile['ug_name_th'];
                                                  }else{
                                                    $ugname = $profile['ug_name_en'];
                                                  } 
                                                  if($ugname=="Learner (Manager)"){
                                                    $ugname="Learner";
                                                  }
                                                  echo $ugname; ?>
                          </h6>
                        </div>

                        <?php if($user['Is_admin']=="0"){ ?>
                        <!-- USER BUTTON -->
                        <div class="card-body text-center button-group">
                          <div class="row">
                            <div class="col-6" style="padding-left: 2.5px;">
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/setting" class="btn btn-block waves-effect waves-light btn-secondary">
                                <i class="mdi mdi-account-edit"></i> <?php echo label('dash_btn_edit_profile'); ?>
                              </a>
                            </div>
                            <div class="col-6" style="padding-right: 2.5px;">
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/certificate" class="btn btn-block waves-effect waves-light btn-secondary">
                                <i class="mdi mdi-certificate"></i> <?php echo label('dash_btn_certificate'); ?>
                              </a>
                            </div><!-- 
                            <div class="col-6" style="padding-left: 2.5px;">
                              <a href="<?php echo REAL_PATH;?>/course/available" class="btn btn-block waves-effect waves-light btn-secondary">
                                <i class="mdi mdi-magnify"></i> <?php echo label('dash_btn_view_allcourse'); ?>
                              </a>
                            </div> -->
                          </div>
                          <!-- <div class="row">
                            <div class="col-6" style="padding-left: 2.5px;">
                              <a href="<?php echo REAL_PATH;?>/report/loadreport_personal" class="btn btn-block waves-effect waves-light btn-secondary">
                                <i class="mdi mdi-format-list-bulleted"></i> <?php echo label('dash_btn_show_myscore'); ?>
                              </a>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <a href="<?php echo REAL_PATH;?>/course/nonenroll" class="btn btn-block waves-effect waves-light btn-secondary">
                                <i class="mdi mdi-close-circle"></i> <?php echo label('dash_btn_not_register'); ?>
                              </a>
                            </div>                          
                          </div> -->
                        </div>
                        <?php }else{ ?>
                        <!-- ADMIN BUTTON -->
                        <div class="switch text-center">
                            <label>
                              <?php echo label('dash_show_admin_dashboard'); ?>
                              <input type="checkbox" id="dashboard" ><span class="lever switch-col-grey"></span>
                              <?php echo label('dash_show_learner_dashboard'); ?>
                            </label>
                        </div>
                        <div class="card-body text-center button-group" id="show_learner_dashboard">
                          <div class="row">
                            <div class="col-6" style="padding-right: 2.5px;">
                              <!-- profile setting Button -->
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/setting" class="btn btn-block waves-effect waves-light btn-secondary" title="<?php echo label('dash_btn_edit_profile'); ?>">
                                <i class="mdi mdi-account-edit"></i> <?php echo label('dash_btn_edit_profile'); ?>
                              </a>
                            </div>
                            <div class="col-6" style="padding-left: 2.5px;">                            
                              <!-- manage user Button -->
                              <?php if(in_array('manage/userdata', $arr_permission)){ ?>
                              <a href="<?php echo REAL_PATH;?>/manage/userdata" class="btn btn-block waves-effect waves-light btn-secondary" title="<?php echo label('dash_btn_manage_user'); ?>">
                                <i class="mdi mdi-lead-pencil"></i> <?php echo label('dash_btn_manage_user'); ?>
                              </a>
                            <?php } ?>
                            </div>                            
                          </div>
                        </div>

                        <div class="card-body text-center button-group" id="show_admin_dashboard">
                          <div class="row">
                            <div class="col-6" style="padding-right: 2.5px;">
                              <!-- edit profile Button -->
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/setting" class="btn btn-block waves-effect waves-light btn-secondary" title="<?php echo label('dash_btn_edit_profile'); ?>"><i class="mdi mdi-account-edit"></i> <?php echo label('dash_btn_edit_profile'); ?>
                              </a>
                            </div>
                          
                            <div class="col-6" style="padding-left: 2.5px;">   
                              <a href="<?php echo REAL_PATH;?>/dashboard/profile/certificate" class="btn btn-block waves-effect waves-light btn-secondary" title="<?php echo label('dash_btn_certificate'); ?>"><i class="mdi mdi-certificate"></i> <?php echo label('dash_btn_certificate'); ?></a>
                            </div>                            
                          </div>
                        </div>

                        <?php } ?>
                      </div>
                    </div>

                    <?php if(in_array('1', $arr_role_fd)){ ?>
                    <div class="col-lg-8">
                        <div class="card">
                            <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                              <ol class="carousel-indicators">
                                <?php if(isset($pic)&&countArray($pic)>0){
                                        if($pic != null&&$page=='dashboard'){?>
                                            <?php $count_num = 0;$n=1;foreach ($pic as $row) {
                                              if($n==1){ ?>
                                                <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>" class="active"></li>
                                              <?php }else{?>
                                                <li data-target="#carouselExampleIndicators3" data-slide-to="<?php echo $count_num; ?>"></li>
                                            <?php }$n++;$count_num++;}?>
                                  <?php }
                                      } ?>
                              </ol>
                              <div class="carousel-inner" role="listbox">

                                <?php if(isset($pic)&&countArray($pic)>0){
                                  if($pic != null&&$page=='dashboard'){?>
                                    <?php $n=1;foreach ($pic as $row) {
                                      $file = ROOT_DIR.'uploads/banner/'.$row['banner'];
                                      if(is_file($file)) {
                                      if($n==1){ ?>
                                      <div class="carousel-item active" style="width: 100%; text-align: center; max-height:350px;"> <img class="img-responsive"  style="width: 100%; text-align: center; max-height:350px;" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
                                      </div>
                                    <?php }else{?>
                                      <div class="carousel-item" style="width: 100%; text-align: center; max-height:350px;"> <img class="img-responsive"  style="width: 100%; text-align: center; max-height:350px;" src="<?php echo media_url('uploads/banner/'.$row['banner'], 'uploads/banner/banner_default.png'); ?>" alt="">
                                      </div>
                                    <?php }$n++;
                                      }
                                  }?>
                                  <?php }
                                      } ?>
                              </div>
                              <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> 
                            </div>
                          </div>
                        </div>
                    <?php } ?>
