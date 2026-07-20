
    <div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="survey_form" autocomplete="off" name="survey_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="form-group col-md-12 col-lg-4">
                    <label><b style="color: #FF2D00">*</b><?php echo label('sv_b_lang'); ?>:</label>
                    <div class="col-12 row">
                        <div class="col-12">
                            <input type="checkbox" id="sv_lang_eng" name="sv_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_eng','input_eng')" value="eng" <?php if($lang=="english"){ echo "checked";} ?>/>
                            <label for="sv_lang_eng"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></label>
                        </div>
                        
                       <div class="col-12">
                        <input type="checkbox" id="sv_lang_th" name="sv_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_th','input_th')" value="th" <?php if($lang=="thai"){ echo "checked";} ?>/>
                        <label for="sv_lang_th"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></label>
                        </div>
                        
                        <div class="col-12">
                            <input type="checkbox" id="sv_lang_jp" name="sv_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_jp','input_jp')" value="jp" <?php if($lang=="japan"){ echo "checked";} ?>/>
                            <label for="sv_lang_jp"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></label>
                        </div>
                    </div>
                    
                </div>

                <div class="form-group col-md-12 col-lg-4">
                    <label><b style="color: #FF2D00">*</b><?php echo label('sv_b_approver'); ?>:</label><br>
                    <select class="form-control select2" id="sv_userapprove" name="sv_userapprove[]" multiple required style="width: 100%;"></select>
                </div>
                
                <div class="form-group col-md-12 col-lg-4 d-block">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_type'); ?></label>
                    <div class="row">
                      <div class="col-md-4 col-lg-4 text-right">
                        <small><?php echo label('sv_b_type_b'); ?></small>
                      </div>
                      <div class="col-md-4 col-lg-2 text-center">
                        <div class="switch">
                          <label>
                            <input type="checkbox"  id="sv_type" name="sv_type" value="1">
                            <span class="lever switch-col-indigo"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4">
                        <small><?php echo label('sv_b_type_a'); ?></small>
                      </div>                                                                  
                    </div>
                </div>

                <div class="col-md-12 input_eng" style="display:none;">
                  <hr>
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                        <div class="ribbon-content row">
                            <div class="form-group col-md-12 input_eng">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                <input name="sv_title_eng" type="text" class="form-control" id="sv_title_eng">
                            </div>
                            <div class="form-group col-md-12 col-lg-6 input_eng">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_remark'); ?>:</label>
                                <textarea class="form-control" name="sv_explanation_eng" id="sv_explanation_eng" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12 col-lg-6 input_eng">
                                <label class="control-label"><?php echo label('preExplanation'); ?>:</label>
                                <textarea class="form-control" name="sv_detail_eng" id="sv_detail_eng" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 input_th" style="display:none;">
                  <hr>
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                        <div class="ribbon-content row">
                            <div class="form-group col-md-12">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                <input name="sv_title_th" type="text" class="form-control" id="sv_title_th">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_remark'); ?>:</label>
                                <textarea class="form-control" name="sv_explanation_th" id="sv_explanation_th" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label class="control-label"><?php echo label('preExplanation'); ?>:</label>
                                <textarea class="form-control" name="sv_detail_th" id="sv_detail_th" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 input_jp" style="display:none;">
                  <hr>
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                        <div class="ribbon-content row">
                            <div class="form-group col-md-12 input_jp">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                <input name="sv_title_jp" type="text" class="form-control" id="sv_title_jp">
                            </div>
                            <div class="form-group col-md-6 input_jp">
                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_remark'); ?>:</label>
                                <textarea class="form-control" name="sv_explanation_jp" id="sv_explanation_jp" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-6 input_jp">
                                <label class="control-label"><?php echo label('preExplanation'); ?>:</label>
                                <textarea class="form-control" name="sv_detail_jp" id="sv_detail_jp" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                  <hr>
                    <label class="control-label"><?php echo label('sv_b_period'); ?></label>

                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <label class="control-label"><?php echo label('sv_b_start_on'); ?>:</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" id="survey_open" name="survey_open" onchange="caldate('survey_open')" class="form-control survey_open">
                                    <input type="hidden" id="survey_open_var" name="survey_open_var">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                        <input type="text" id="time_start_survey" name="time_start_survey" class="form-control" value="<?php echo date('H:i',strtotime('00:00')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <label class="control-label"><?php echo label('sv_b_finish_on'); ?>:</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" id="survey_end" name="survey_end" onchange="caldate('survey_end')" class="form-control survey_end">
                                    <input type="hidden" id="survey_end_var" name="survey_end_var">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                        <input type="text" id="time_end_survey" name="time_end_survey" class="form-control" value="<?php echo date('H:i',strtotime('23:59')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group col-md-12 col-lg-6">
                    <label class="control-label"><?php echo label('sv_b_pic'); ?></label>
                    <input type="file" name="sv_cover" id="sv_cover" class="dropify_main" accept="image/png, image/jpeg, image/gif" />
                    <input type="hidden" id="sv_cover_ori" name="sv_cover_ori">
                </div>

                <div class="form-group col-md-12 col-lg-6 d-block">
                    <div class="block mb-5">
                        <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_more_info'); ?></label>
                        <div class="row text-center">
                          <div class="col-md-4 col-lg-2 text-right">
                            <small><?php echo label('sv_b_none'); ?></small>
                          </div>
                          <div class="col-md-4 col-lg-2 text-center">
                            <div class="switch">
                              <label>
                                <input type="checkbox"  id="sv_suggestion_status" name="sv_suggestion_status" value="1">
                                <span class="lever switch-col-indigo"></span>
                              </label>
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-2">
                            <small><?php echo label('sv_b_have'); ?></small>
                          </div>                                                                  
                        </div>
                    </div>
                    <div class="block">
                        <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_display'); ?></label>
                        <div class="row text-center">
                          <div class="col-md-4 col-lg-2 text-right">
                            <small><?php echo label('sv_b_hide'); ?></small>
                          </div>
                          <div class="col-md-4 col-lg-2 text-center">
                            <div class="switch">
                              <label>
                                <input type="checkbox"  id="sv_status" name="sv_status" checked value="1">
                                <span class="lever switch-col-indigo"></span>
                              </label>
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-2">
                            <small><?php echo label('sv_b_show'); ?></small>
                          </div>                                                                  
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12"><hr></div>
                <div class="form-group col-md-4">
                    <label class="control-label"><?php echo label('noti_expire_sv'); ?>:</label>
                    <input name="sv_expire_noti"  type="text" min="0"  step="1" class="form-control" id="sv_expire_noti" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="sv_id" name="sv_id">
              <input type="hidden" id="com_id" name="com_id">
              <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('sv_btn_save'); ?></button>
                        <button type="button" class="btn btn-outline-danger btn-flat" onclick="clickCancel('modal-default')"><i class="mdi mdi-window-close"></i> <?php echo label('sv_btn_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade bs-example-modal-lg" id="modal-question" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-titleques" id="myLargeModalLabel">Large modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 card" id="div_question">
                        <div class="card-body">
                          <div class="col-md-12">
                            <?php if($btn_add=="1"){ ?>
                              <button name="add_button_question" id="add_button_question" class="btn btn-outline-info add_button_question float-right"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('sv_b_add_question'); ?></button>
                            <?php } ?>
                          </div>
                          <div class="table-responsive">
                              <table id="myTable_question" width="100%" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <?php if($btn_update=="1"||$btn_delete=="1"){ ?>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_manage'); ?></center></th>
                                    <?php } ?>
                                    <th width="5%"></th>
                                    <th width="10%" align="center"><center><?php echo label('sv_b_question_type'); ?></center></th>
                                    <th width="30%" align="center"><center><?php echo label('sv_b_question'); ?></center></th>
                                    <th width="30%" align="center"><center><?php echo label('sv_b_choice'); ?></center></th>
                                    <th width="15%" align="center"><center><?php echo label('sv_b_update_date'); ?></center></th>
                                  </tr>
                                </thead>
                              </table>
                          </div>
                          <p><?php echo label('sv_b_comment'); ?> : <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('sv_btn_edit'); ?></b><?php } ?><?php if($btn_update=="1"&&$btn_delete=="1"){ ?> , <?php } ?><?php if($btn_delete=="1"){ ?><button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('sv_btn_delete'); ?></b><?php } ?></p>
                        </div>
                    </div>
                    <div class="col-md-12" id="div_add_question" style="display: none;">
                        
                      <div class="col-md-12">
                          <button type="button" class="btn btn-outline-danger float-right previous_survey"><i class="mdi mdi-keyboard-return"></i> <?php echo label('sv_btn_previous'); ?></button>
                          <h3 id="quiz_name_txt"></h3><hr>
                      </div>
                        <form  enctype="multipart/form-data" id="question_form" name="question_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                            <input type="hidden" id="sv_id_question_create" name="sv_id_question_create">
                            <input type="hidden" id="svde_id" name="svde_id">
                            <input type="hidden" id="operation_question" name="operation_question" value="Add">
                            <div class="col-md-12 row" style="">


                                <div class="col-md-12 input_svde_eng" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-12 col-lg-6">
                                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_question'); ?>:</label>
                                                <textarea class="form-control" name="svde_name_eng" id="svde_name_eng" rows="5"></textarea>
                                            </div>
                                            <div class="form-group col-md-12 col-lg-6">
                                                <label class="control-label"><?php echo label('sv_b_ex'); ?>:</label>
                                                <textarea class="form-control" name="svde_info_eng" id="svde_info_eng" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 input_svde_th" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-12 col-lg-6">
                                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_question'); ?>:</label>
                                                <textarea class="form-control" name="svde_name_th" id="svde_name_th" rows="5"></textarea>
                                            </div>
                                            <div class="form-group col-md-12 col-lg-6">
                                                <label class="control-label"><?php echo label('sv_b_ex'); ?>:</label>
                                                <textarea class="form-control" name="svde_info_th" id="svde_info_th" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 input_svde_jp" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-12 col-lg-6 input_jp">
                                                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_question'); ?>:</label>
                                                <textarea class="form-control" name="svde_name_jp" id="svde_name_jp" rows="5"></textarea>
                                            </div>
                                            <div class="form-group col-md-12 col-lg-6 input_jp">
                                                <label class="control-label"><?php echo label('sv_b_ex'); ?>:</label>
                                                <textarea class="form-control" name="svde_info_jp" id="svde_info_jp" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="form-group col-md-6 col-lg-6">
                                        <label><b style="color: #FF2D00">*</b><?php echo label('sv_b_question_type'); ?>:</label>
                                        <select class="form-control" required id="svde_type" name="svde_type"  style="width: 100%;">
                                            <optgroup label="<?php echo label('sv_b_choose_question_type') ?>">
                                                <option value="sa"><?php echo label('sv_q_short_ans'); ?></option>
                                                <option value="sub"><?php echo label('sv_q_long_ans'); ?></option>
                                                <option value="scale"><?php echo label('sv_q_scale_ans'); ?></option>
                                                <option value="2choice"><?php echo label('sv_q_two_choice'); ?></option>
                                                <option value="multi"><?php echo label('sv_q_multi_choice'); ?></option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 col-lg-3">
                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('sv_q_visible'); ?></label>
                                        <div class="row">
                                          <div class="col-4 text-right">
                                            <small><?php echo label('sv_b_hide'); ?></small>
                                          </div>
                                          <div class="col-4 text-center">
                                            <div class="switch">
                                              <label>
                                                <input type="checkbox"  id="svde_status" name="svde_status" checked value="1">
                                                <span class="lever switch-col-indigo"></span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="col-4">
                                            <small><?php echo label('sv_b_show'); ?></small>
                                          </div>                                                                  
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="col-md-12 row" id="div_question_mul" style="display: none;">
                                    <div class="form-group col-md-6 courseCat">
                                        <h4><?php echo label('sv_q_detail'); ?></h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                            <!-- <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('answer'); ?>:</label>
                                            <select class="form-control select2" id="mul_answer" name="mul_answer[]" multiple  style="width: 100%;">
                                                <option value="mul_c1"><?php echo label('choice')." 1"; ?></option>
                                                <option value="mul_c2"><?php echo label('choice')." 2"; ?></option>
                                                <option value="mul_c3"><?php echo label('choice')." 3"; ?></option>
                                                <option value="mul_c4"><?php echo label('choice')." 4"; ?></option>
                                                <option value="mul_c5"><?php echo label('choice')." 5"; ?></option>
                                            </select>   --> 
                                    </div>

                                <div class="col-md-12 input_svdedetail_eng" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-6 mul_c1">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 1"; ?></label>
                                                <textarea name="mul_c1_eng" id="mul_c1_eng" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c2">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 2"; ?></label>
                                                <textarea name="mul_c2_eng" id="mul_c2_eng" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c3">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 3"; ?></label>
                                                <textarea name="mul_c3_eng" id="mul_c3_eng" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c4">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 4"; ?></label>
                                                <textarea name="mul_c4_eng" id="mul_c4_eng" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c5">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 5"; ?></label>
                                                <textarea name="mul_c5_eng" id="mul_c5_eng" rows="5" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 input_svdedetail_th" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-6 mul_c1">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 1"; ?></label>
                                                <textarea name="mul_c1_th" id="mul_c1_th" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c2">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 2"; ?></label>
                                                <textarea name="mul_c2_th" id="mul_c2_th" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c3">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 3"; ?></label>
                                                <textarea name="mul_c3_th" id="mul_c3_th" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c4">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 4"; ?></label>
                                                <textarea name="mul_c4_th" id="mul_c4_th" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c5">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 5"; ?></label>
                                                <textarea name="mul_c5_th" id="mul_c5_th" rows="5" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 input_svdedetail_jp" style="display:none;">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                        <div class="ribbon-content row">
                                            <div class="form-group col-md-6 mul_c1">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 1"; ?></label>
                                                <textarea name="mul_c1_jp" id="mul_c1_jp" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c2">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 2"; ?></label>
                                                <textarea name="mul_c2_jp" id="mul_c2_jp" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c3">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 3"; ?></label>
                                                <textarea name="mul_c3_jp" id="mul_c3_jp" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c4">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 4"; ?></label>
                                                <textarea name="mul_c4_jp" id="mul_c4_jp" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mul_c5">
                                                <label class="control-label text-right"><?php echo label('sv_b_choice')." 5"; ?></label>
                                                <textarea name="mul_c5_jp" id="mul_c5_jp" rows="5" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="form-group col-md-12 multiple_choice">
                                        <label class="control-label text-right isMultichoice"><b style="color: #FF2D00">*</b><?php echo label('sv_b_is_multi_choice'); ?></label>
                                        <div class="switch isMultichoice">
                                            <label><?php echo label('sv_btn_no'); ?><input type="checkbox" id="svde_isMultichoice" name="svde_isMultichoice" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('sv_btn_yes'); ?></label>
                                        </div><br><br>
                                        <label class="control-label text-right"><?php echo label('sv_b_is_etc'); ?></label>
                                        <div class="switch">
                                            <label><?php echo label('sv_b_none'); ?><input type="checkbox" onclick="onSpecify()" id="svde_isSpecify" name="svde_isSpecify" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('sv_b_have'); ?></label>
                                        </div><br>
                                        <div id="div_svde_isSpecify" style="display: none;">
                                          <div class="row">
                                            <div class="col-md-6 input_svdedetail_eng" style="display: none;">
                                              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_is_etc_name'); ?> EN</label>
                                              <input type="text" class="form-control" id="svde_specify_name_eng" name="svde_specify_name_eng" value="others">
                                            </div>
                                            <div class="col-md-6 input_svdedetail_th" style="display: none;">
                                              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_is_etc_name'); ?> TH</label>
                                              <input type="text" class="form-control" id="svde_specify_name_th" name="svde_specify_name_th" value="อื่นๆ">
                                            </div>
                                            <div class="col-md-6 input_svdedetail_jp" style="display: none;">
                                              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_is_etc_name'); ?> JP</label>
                                              <input type="text" class="form-control" id="svde_specify_name_jp" name="svde_specify_name_jp" value="その他">
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-12" align="right">
                                    <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('sv_btn_save'); ?></button>
                                    <button type="button" class="btn btn-outline-danger btn-flat previous_survey"><i class="mdi mdi-window-close"></i> <?php echo label('sv_btn_cancel'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <input type="hidden" id="sv_id_question" name="sv_id_question">
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('sv_b_close'); ?></button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->