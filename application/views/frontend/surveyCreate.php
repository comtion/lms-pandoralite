<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo HTTP_CSS_PATH; ?>tab.css" rel="stylesheet">
<link href="<?php echo HTTP_CSS_PATH; ?>jquery-ui.css" rel="stylesheet">
<link href="<?php echo HTTP_CSS_PATH; ?>bootstrap-datetimepicker.css" rel="stylesheet">
<link href="<?php echo HTTP_CSS_PATH; ?>bootstrap-datetimepicker.min.css" rel="stylesheet">
  <script src="<?php echo HTTP_JS_PATH; ?>bootstrap.js"></script>
  <script src="<?php echo HTTP_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
  <script src="<?php echo HTTP_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/ckeditor/ckeditor.js"></script>
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

        <!-- Content Body -->
        <div class="content dashWrap">
          <div class="dashElement page">
            <div class="row crcourse">
              <div class="col-md-12">
                <!--
                <div id="langs_tab">
                <?php foreach ($langs as $each) { ?>
                  <input required class="lang_tab" value="<?php echo $each['lang']; ?>" id="tab_<?php echo $each['lang']; ?>" type="radio" name="tabs" <?php echo ($each['lang'] == $lang_tab) ? "checked": ""; ?>>
                  <label class="each_label" id="sh_<?php echo $each['lang']; ?>" for="tab_<?php echo $each['lang']; ?>"><?php echo label($each['lang']); ?></label>
                <?php } ?>
              </div>
              -->

                <?php 
              foreach ($langs as $each) {
                $lang_set = $each['lang'];
                if (isset($survey[$lang_set])){
                  $sname = $survey[$lang_set]['sname'];
                  $sdesc = $survey[$lang_set]['sdesc'];
                  $time_open = $survey[$lang_set]['time_open'];
                  $time_end = $survey[$lang_set]['time_end'];
                  $time_mod = $survey[$lang_set]['time_mod'];
                  $hidden = $survey[$lang_set]['hidden'];
                  $svsuggestion_status = $survey[$lang_set]['svsuggestion_status'];
                  $questionnaire_id = $survey[$lang_set]['questionnaire_id'];
                }else {
                  $sname = '';
                  $sdesc = '';
                  $time_open = '';
                  $time_end = '';
                  $time_mod = '';
                  $hidden = '';
                  $svsuggestion_status = '';
                  $questionnaire_id = '';
                }
              } ?>

              <?php foreach ($langs as $each) {
                $lang_set = $each['lang'];?>
              <section id="content_<?php echo $lang_set ?>" style="<?php echo $lang == $lang_set ? '' : 'display:none;' ; ?>" class="active">
                <div class="dashHeader">
                  <h2><?php echo label('svcreate');?></h2>
                </div>
                  <div class="portlet-body">
                    <div class="tabbable">

                      <div class="tab-content">
                        <div id="normal" class="tab-pane active">
                          <div class="dashContent">
                            <form id="create" method="post" novalidate></form>
                            <form id="cancel" method="post"></form>

                              <input form="create" required type="hidden" name="page" value="<?php echo $page; ?>"  />
                              <input form="create" required type="hidden" name="questionnaire_id" value="<?php echo $questionnaire_id; ?>"  />
                              <input form="create" required type="hidden" id='inp_lang' name="lang" value="<?php echo $lang_set; ?>"  />
                              <input form="create" required type="hidden" id="inp_type" name="type" value="normal" />
                              <input form="create" required type="hidden" id="inp_sDate_<?php echo $lang_set; ?>" name="time_open" />
                              <input form="create" type="hidden" id="inp_eDate_<?php echo $lang_set; ?>" name="time_close" />

                  <div class="row">
                  <div class="col-sm-3 courseCat">
                    <?php echo label('svgi');?>
                  </div>
                  <br>
                  <div class="col-sm-9">
                    <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('svtheme');?></label>
                    <div class="col-sm-9 form-group has-success has-feedback">
                        <Select name="questionnaire_select" id="questionnaire_select" class="form-control">
                          <option value=""><?php echo "เลือกเทมเพลตแบบสำรวจ";?></option>
                          <?php  foreach ($Questionnairelist as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php echo $questionnaire_id == $value['id'] ? 'selected' : '';?>><?php echo $value['title']; ?></option>
                          <?php  } ?>
                        </Select>
                    </div>
                    <label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('svname');?></label>
                    <div class="col-sm-9 form-group has-success has-feedback">
                        <input form="create" required type="text" class="form-control" name='sname' value="<?php echo $sname ?>" id="sname">
                    </div>
                        <label class="col-sm-3 control-label" for="inputSuccess">
                          <?php echo label('quessuggestion_status'); ?>
                        </label>
                        <div class="col-sm-9 form-group has-success has-feedback">
                          <div class="visible">
                            <label style="color:#000;">
                              <input form="create" type="radio" name="svsuggestion_status" id="svsuggestion_status1" value="1" <?php echo $svsuggestion_status == 1 ? 'checked' : ''; ?>> มี
                            </label>
                          </div>
                          <div class="visible">
                            <label  style="color:#000;">
                              <input form="create" type="radio" name="svsuggestion_status" id="svsuggestion_status2" value="0" <?php echo $svsuggestion_status == 0 ? 'checked' : ''; ?>> ไม่มี
                            </label>
                          </div>
                        </div>

                    <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('svhid');?></label>
                    <div class="col-sm-9 form-group has-success has-feedback">
                        <div class="visible">
                          <label style="color:#000;">
                            <input form="create" required type="radio" name="visRadio" value="1" aria-label="" <?php echo $hidden == 1 ? 'checked' : ''; ?>> <?php echo label('svhid1');?>
                          </label>
                        </div>
                        <div class="visible">
                          <label  style="color:#000;">
                            <input form="create" required type="radio" name="visRadio" value="0" aria-label="" <?php echo $hidden == 0 ? 'checked' : ''; ?>> <?php echo label('svhid2');?>
                          </label>
                        </div>
                      </div>
                      <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('svtopen');?></label>
                      <div class="col-sm-9 form-group">
                          <div class="input-group">
                            <input form="create" value="<?php echo $time_open ?>" required name="sDate" type="text" class="form-control" id="sDate_<?php echo $lang_set; ?>" placeholder="dd/mm/yy hr:min">
                            <script>
                            $( function() {
                                $( "#sDate_<?php echo $lang_set; ?>" ).datetimepicker({
                                  format: 'dd/mm/yyyy hh:ii'
                                });
                              });
                              $(document).ready(function(){
                                $("#sDate_<?php echo $lang_set; ?>").change(function() {
                                  $("#inp_sDate_<?php echo $lang_set; ?>").val($('#sDate_<?php echo $lang_set; ?>').val());
                                });
                              });
                            </script>
                            <div class="input-group-addon calendar"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                          </div>
                        </div>
                        <label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('svtclose');?></label>
                        <div class="col-sm-9 form-group">
                            <div class="input-group">
                              <input form="create" value="<?php echo $time_end; ?>" name="eDate" type="text" class="form-control" id="eDate_<?php echo $lang_set; ?>" placeholder="dd/mm/yy hr:min">
                              <script>
                              $( function() {
                                  $( "#eDate_<?php echo $lang_set; ?>" ).datetimepicker({
                                    format: 'dd/mm/yyyy hh:ii'
                                  });
                                });
                                $(document).ready(function(){
                                  $("#eDate_<?php echo $lang_set; ?>").change(function() {
                                    $("#inp_eDate_<?php echo $lang_set; ?>").val($('#eDate_<?php echo $lang_set; ?>').val());
                                  });
                                });
                              </script>
                              <div class="input-group-addon calendar"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <hr />

                      <div class="row">
                        <div class="col-sm-3 courseCat">
                          <?php echo label('svdesc');?>
                        </div>
                        <br>
                        <div class="col-sm-9">
                          <textarea form="create" name="sdesc" id="sdesc_<?php echo $lang_set ?>" rows="10" style="width:100%"><?php echo $sdesc; ?></textarea>
                        </div>
                      </div>

                      <hr>

                      <div class="row">
                        <div class="col-sm-8 col-sm-offset-4">
                          <div class="saveWrap">
                            <button form="create" name="saveRBT" value="normal" class="btn btn-default return" type="submit"><?php echo label('svsave');?></button>
                            <button form="cancel" name="cancelBT" value="cancel" class="btn btn-default cancel" type="submit"><?php echo label('m_cancel');?></button>
                          </div>
                        </div>
                      </div>
                   </div>
                  </div>
                 </div>
                </div>
               </div>
              </section>
              <?php } ?>
             </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            <br><br>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
            <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
            <script src="<?php echo HTTP_JS_PATH; ?>survey.js"></script>
            <script src="<?php echo HTTP_JS_PATH; ?>create.js"></script>
            <script src="<?php echo HTTP_JS_PATH; ?>jquery-ui.js"></script>
            <script type="text/javascript">

              $(document).ready( function(){
                  $('input[type="text"]').attr('autocomplete', 'off');
              });
                  $('select[name="questionnaire_select"]').on('change', function(){
                    var questionnaire_select = $(this).val();
                    if(questionnaire_select!=""){
                      $.ajax({
                         url: '<?=base_url()?>index.php/Survey/requestQuesData/'+questionnaire_select,
                         type: 'POST',
                         dataType: "json",
                         success: function(data) {
                          $("input[name*='sname']").val(data[0]['title']);
                          $("input[name*='questionnaire_id']").val(questionnaire_select);
                          var sdesc = document.getElementById('sdesc_<?php echo $lang_set ?>');
                          sdesc.value = data[0]['explanation'];
                          if(data[0]['suggestion_status']=="1"){
                            console.log(data[0]['suggestion_status']);
                            $("input[id*='svsuggestion_status1']").attr('checked',true);
                            $("input[id*='svsuggestion_status2']").attr('checked',false);
                          }else{
                            $("input[id*='svsuggestion_status1']").attr('checked',false);
                            $("input[id*='svsuggestion_status2']").attr('checked',true);
                          }
                          console.log(data[0]['suggestion_status']);
                          console.log(data[0]['explanation']);
                         }
                      });
                    }else{
                      $("input[name*='sname']").val('');
                      var sdesc = document.getElementById('sdesc_<?php echo $lang_set ?>');
                      sdesc.value = "";
                    }
                    
                  });
            </script>
  </div>
 </body>
</html>
