<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
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
          <?php if(in_array($role, array("superadmin", "admin"))): ?>
            <div class="content dashWrap">
                <div class="row">
                <div class="col-sm-8">
                  <form method="post">
                    <table class="display" align="left">
                        <td>
                            <div class="saveWrap">
                              <button name="sresults" value="normal" class="btn btn-default display" type="submit"><?php echo label('sresults'); ?></button>
                            </div>
                        </td>
                        <td>
                            <div class="saveWrap"><button name="editSV" value="normal" class="btn btn-default display" type="submit"><?php echo label('sedit'); ?></button>
                            </div>
                        </td>
                        <td>
                            <div class="saveWrap">
                              <button name="deleteSV" value="normal" class="btn btn-default display" type="submit"><?php echo label('sdelete'); ?></button>
                            </div>
                        </td>
                    </table>
                    </form>
                </div>
                </div>
            </div>
            <br>
          <?php endif; ?>
            <?php if(in_array($role, array("superadmin", "admin"))): ?>
              <div class="content dashWrap">
               <div class="dashElement page">
                <div class="row">
                  <br>
                 <div class="col-sm-3 courseCat"><?php echo label('sqaddsquestion'); ?></div>
                 <div class="col-sm-9">
                  <form method="post">
                   <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('stitle'); ?></label>
                   <div class="col-sm-9 form-group has-success has-feedback">
                       <input required type="text" class="form-control" name="title_svq">
                   </div>
                   <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('squestion'); ?></label>
                   <div class="col-sm-9 form-group has-success has-feedback">
                       <input required type="text" class="form-control" name="question">
                   </div>
                   <input type="hidden" id="type" name="type" value="1">

                     <div class="col-sm-2 col-sm-offset-3">
                       <div class="saveWrap">
                          <button name="addSQ" value="normal" class="btn btn-default display" type="submit" href="<?php echo $page;?>"><?php echo label('sqaddsquestion'); ?></button>
                       </div>
                     </div>
                 </div>
                </form>
                 </div>
                  <br>
                </div>
               </div>
            <?php endif; ?>

				<div class="content dashWrap">
					<div class="dashElement page">
						<div class="row">
							<div class="col-sm-12">
								<div class="dashHeader">
                  <h2><?php echo $survey['sname'];$svsuggestion_status = $survey['svsuggestion_status'];  ?></h2>
									<h2><?php echo label('sforcou'); ?><?php echo $course['cname']; ?></h2>
                  <br>
									<div class="dashpageWrap">
										<p class="textDescription">
											<?php echo $survey['sdesc'] ?>
										</p>
                    <br>
										<form id="SForm" method="post"></form>
											<div class="row survey">
                        <table class="display" align="right" >
                          <tr>
                            <th></th>
                            <th colspan="2"><?php echo label('smax'); ?></th>
                            <th></th>
                            <th colspan="2"><?php echo label('smin'); ?></th>
                            <th></th>
                          </tr>
                          <tr>
                            <th width="500"></th>
                            <th width="50">5</th>
                            <th width="50">4</th>
                            <th width="50">3</th>
                            <th width="50">2</th>
                            <th width="50">1</th>
                            <th width="250" align="center"><?php echo label('Suggestion'); ?></th>
                          </tr>
                        <?php   
                        $title_arr = array();
                        foreach ($surveys_title as $key => $value) {
                          array_push($title_arr, $value['title_svq']);
                        }
                        //print_r($title_arr);
                        $count_arr = 0;
                        $row = 1;$count = 1; foreach($surveys as $survey) { ?>
                          <?php $quest[$row] = $survey['id'];

                          if($title_arr[$count_arr]!=$survey['title_svq']){
                             $count_arr++;$count++;
                             $count=1;
                          }
                          if($count==1){ ?>
                            <tr>
                              <td colspan="7" align="left"><br><?php echo $title_arr[$count_arr]; ?></td>
                            </tr>
                          <?php $count=0;
                          } 
                          ?>

                          <tr>
                            <td width="500">
                              <?php if(in_array($role, array("superadmin", "admin"))): ?>
                              <form id="DForm" method="post">
                                <button name="deleteSQ" value="<?php echo $survey['id']?>" type="submit" form="DForm">
                                  <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                              </form>
                              <?php endif; ?>
                              <input form="SForm" required type="hidden" name='quest[<?php echo $row?>]' value="<?php echo $survey['id']?>"/>
                              <div class="questions"><?php echo " - " .$survey['question']; ?>
                              </div>
                            </td>
                            <td width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="5"></td>
                            <td width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="4"></td>
                            <td width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="3"></td>
                            <td width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="2"></td>
                            <td width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="1"></td>
                            <td width="250" align="center"><textarea form="SForm" class="form" name="Suggestion[<?php echo $row?>]" style="width: 100%"></textarea></td>
                          </tr>
                          <?php $row++;?>
                        <?php } ?>
                        </table>
                        <br>
                        <br>
                        <hr>
                        <?php 
                          if($svsuggestion_status=="1"){ ?>
                        <div class="row">
                          <div class="col-sm-3 text-center"><br><?php echo label('Suggestion'); ?></div>
                          <div class="col-sm-9">
                            <textarea form="SForm" class="form" name="Suggestion_head" style="width: 100%"></textarea>
                          </div>
                        </div>
                        <?php  }
                        ?>
                      <!--  <?php $row = 1; foreach($surveys as $survey) { ?>
                          <?php $quest[$row] = $survey['id'];?>
                          <div class="col-sm-6">
                            <?php if(in_array($role, array("superadmin", "admin"))): ?>
                            <form id="DForm" method="post">
                              <button name="deleteSQ" value="<?php echo $survey['id']?>" type="submit" form="DForm">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                              </button>
                            </form>
                            <?php endif; ?>
                            <input form="SForm" required type="hidden" name='quest[<?php echo $row?>]' value="<?php echo $survey['id']?>"/>
  													<div class="questions"><?php echo $row. ". " .$survey['question']; ?>
                            </div>
                          </div>
                            <?php if( 1 == $survey['type']): ?>
                                <table class="display" align="right" >
                                  <th width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="5"></th>
                                  <th width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="4"></th>
                                  <th width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="3"></th>
                                  <th width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="2"></th>
                                  <th width="50"><input form="SForm" required type="radio" style="cursor:pointer;" name='ans[<?php echo $row?>]' value="1"></th>
                              </table>
                            <?php endif; ?>
                              <?php if( 0 == $survey['type']): ?>
                              <div class="col-sm-12 col-sm-offset-5">
                                <table class="display" align="left">
                                  <th><textarea form="SForm" required class="form" name="ans[<?php echo $row?>]" cols="68" rows="3"></textarea></th>
                                </table>
      												</div>
                            <?php endif; ?>
                            <?php $row++;?>
                        <?php } ?> -->
											</div>
                      <br>
                      <?php if(!in_array($role, array("superadmin", "admin"))): ?>
                      <?php if(isset($survey['id'])): ?>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6 col-sm-offset-5">
                          <div class="saveWrap">
                            <button form="SForm" name="submitANS" value="normal" class="btn btn-default return" type="submit"><?php echo label('sqsave'); ?></button>
                          </div>
                        </div>
                      </div>
                      <br>
                      <?php endif; ?>
                      <?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		  </div>
      </div>
      <br>
      <br>
      <br>
      <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
      <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    </div>
  </body>
</html>
