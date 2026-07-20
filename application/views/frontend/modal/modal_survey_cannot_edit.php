<div    class="modal fade bs-example-modal-lg"
        id="modal-detail-survey"
        aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title-detail-survey">Large modal</h4>
                    <button type="button" class="close btn_close"
                            data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-tabs-detail-survey" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#main_survey_tab" role="tab">
                                <?php echo label('ceGen'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#question_survey_tab" role="tab">
                                <?php echo label('sv_b_question'); ?>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="main_survey_tab" role="tabpanel">
                            <div class="p-20 card" style="pointer-events: none;">
                                <div class="card-body row">
                                    <div class="form-group col-md-12 col-lg-4">
                                        <label><b style="color: #FF2D00">*</b><?php echo label('sv_b_lang'); ?>:</label>
                                        <div class="col-12 row">
                                            <div class="col-12">
                                                <input type="checkbox" id="sv_lang_eng_view" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_eng_view','input_eng_view')" value="eng" <?php if($lang=="english"){ echo "checked";} ?>/>
                                                <label for="sv_lang_eng_view"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></label>
                                            </div>
                                            
                                        <div class="col-12">
                                            <input type="checkbox" id="sv_lang_th_view" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_th_view','input_th_view')" value="th" <?php if($lang=="thai"){ echo "checked";} ?>/>
                                            <label for="sv_lang_th_view"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></label>
                                            </div>
                                            
                                            <div class="col-12">
                                                <input type="checkbox" id="sv_lang_jp_view" class="filled-in chk-col-red" onclick="chkbox_lang('sv_lang_jp_view','input_jp_view')" value="jp" <?php if($lang=="japan"){ echo "checked";} ?>/>
                                                <label for="sv_lang_jp_view"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></label>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="form-group col-md-12 col-lg-4">
                                        <label for="sv_userapprove_view"><b style="color: #FF2D00">*</b><?php echo label('sv_b_approver'); ?>:</label><br>
                                        <select class="form-control select2" id="sv_userapprove_view" multiple required style="width: 100%;"></select>
                                    </div>
                
                                    <div class="form-group col-md-12 col-lg-4 d-block">
                                        <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_type'); ?>:</label>
                                        <div class="row">
                                        <div class="col-md-4 col-lg-4 text-right">
                                            <small><?php echo label('sv_b_type_b'); ?></small>
                                        </div>
                                        <div class="col-md-4 col-lg-2 text-center">
                                            <div class="switch">
                                            <label>
                                                <input type="checkbox"  id="sv_type_view" value="1">
                                                <span class="lever switch-col-indigo"></span>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <small><?php echo label('sv_b_type_a'); ?></small>
                                        </div>                                                                  
                                        </div>
                                    </div>

                                    <div class="col-md-12 input_eng_view" style="display:none;">
                                    <hr>
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                            <div class="ribbon-content row">
                                                <div class="form-group col-md-12 input_eng">
                                                    <label class="control-label" for="sv_title_eng_view"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                                    <input type="text" class="form-control" id="sv_title_eng_view">
                                                </div>
                                                <div class="form-group col-md-12 col-lg-6 input_eng">
                                                    <label class="control-label" for="sv_explanation_eng_view"><?php echo label('svdesc'); ?>:</label>
                                                    <textarea class="form-control" id="sv_explanation_eng_view" rows="5"></textarea>
                                                </div>
                                                <div class="form-group col-md-12 col-lg-6 input_eng">
                                                    <label class="control-label" for="sv_detail_eng_view"><?php echo label('survey_summary'); ?>:</label>
                                                    <textarea class="form-control" id="sv_detail_eng_view" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 input_th_view" style="display:none;">
                                    <hr>
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                            <div class="ribbon-content row">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="sv_title_th_view"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                                    <input type="text" class="form-control" id="sv_title_th_view">
                                                </div>
                                                <div class="form-group col-md-12 col-lg-6">
                                                    <label class="control-label" for="sv_explanation_th_view"><?php echo label('svdesc'); ?>:</label>
                                                    <textarea class="form-control" id="sv_explanation_th_view" rows="5"></textarea>
                                                </div>
                                                <div class="form-group col-md-12 col-lg-6">
                                                    <label class="control-label" for="sv_detail_th_view"><?php echo label('survey_summary'); ?>:</label>
                                                    <textarea class="form-control" id="sv_detail_th_view" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 input_jp_view" style="display:none;">
                                        <hr>
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                            <div class="ribbon-content row">
                                                <div class="form-group col-md-12 input_jp">
                                                    <label class="control-label" for="sv_title_jp_view"><b style="color: #FF2D00">*</b><?php echo label('sv_b_name'); ?>:</label>
                                                    <input type="text" class="form-control" id="sv_title_jp_view">
                                                </div>
                                                <div class="form-group col-md-6 input_jp">
                                                    <label class="control-label" for="sv_explanation_jp_view"><?php echo label('svdesc'); ?>:</label>
                                                    <textarea class="form-control" id="sv_explanation_jp_view" rows="5"></textarea>
                                                </div>
                                                <div class="form-group col-md-6 input_jp">
                                                    <label class="control-label" for="sv_detail_jp_view"><?php echo label('survey_summary'); ?>:</label>
                                                    <textarea class="form-control" id="sv_detail_jp_view" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <hr>
                                        <label class="control-label"><?php echo label('sv_b_period'); ?>: </label>

                                        <div class="row">
                                            <div class="col-md-12 col-lg-6">
                                                <label class="control-label" for="survey_open_view"><?php echo label('sv_b_start_on'); ?></label>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" id="survey_open_view" class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <input type="text" id="time_start_survey_view" class="form-control" value="<?php echo date('H:i',strtotime('00:00')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-6">
                                                <label class="control-label" for="survey_end_view"><?php echo label('sv_b_finish_on'); ?></label>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" id="survey_end_view" class="form-control">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <input type="text" id="time_end_survey_view" class="form-control" value="<?php echo date('H:i',strtotime('23:59')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-md-12 col-lg-6">
                                        <label class="control-label" for="sv_cover_view"><?php echo label('sv_b_pic'); ?>:</label><br>
                                        <center><img src="" id="view_img_survey_view" style="width: 60%" alt=""></center>
                                    </div>
                                    <div class="form-group col-md-12 col-lg-6 d-block">
                                        <div class="block">
                                            <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_b_display'); ?>:</label>
                                            <div class="row text-center">
                                                <div class="col-md-4 col-lg-2 text-right">
                                                    <small><?php echo label('sv_b_hide'); ?></small>
                                                </div>
                                                <div class="col-md-4 col-lg-2 text-center">
                                                    <div class="switch">
                                                    <label>
                                                        <input type="checkbox"  id="sv_status_view" checked value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                    </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-2">
                                                    <small><?php echo label('sv_b_show'); ?></small>
                                                </div>                                                                  
                                            </div>
                                        </div><br>
                                        <div class="block">
                                            <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sv_isSection'); ?>:</label>
                                            <div class="row text-center">
                                                <div class="col-md-4 col-lg-2 text-right">
                                                    <small><?php echo label('sv_btn_no'); ?></small>
                                                </div>
                                                <div class="col-md-4 col-lg-2 text-center">
                                                    <div class="switch">
                                                    <label>
                                                        <input type="checkbox"  id="sv_isHeader_view" value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                    </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-2">
                                                    <small><?php echo label('sv_btn_yes'); ?></small>
                                                </div>                                                                  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12"><hr></div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="sv_expire_noti_view"><?php echo label('noti_expire_sv'); ?>:</label>
                                        <input type="text" class="form-control" id="sv_expire_noti_view">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="question_survey_tab" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable_question_survey" width="100%" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th width="5%"></th>
                                                <th width="10%"><?php echo textCenter(label('questitle')); ?></th>
                                                <th width="10%"><?php echo textCenter(label('sv_b_question_type')); ?></th>
                                                <th width="35%"><?php echo textCenter(label('sv_b_question')); ?></th>
                                                <th width="25%"><?php echo textCenter(label('sv_b_choice')); ?></th>
                                                <th width="15%"><?php echo textCenter(label('sv_b_update_date')); ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-danger btn-flat btn_close"
                            data-dismiss="modal"
                    ><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
                </div>
            </div>
        </div>
</div>
