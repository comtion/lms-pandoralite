<div    class="modal fade bs-example-modal-lg"
        id="modal-detail-course"
        aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title-detail-course">Large modal</h4>
                    <button type="button" class="close btn_close"
                            data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-tabs-detail-course" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#main_course_tab" role="tab">
                                <?php echo label('ceGen'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#certificate_course_tab" role="tab">
                                <?php echo label('certificate'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#period_and_permission_course_tab" role="tab">
                                <?php echo label('period_and_permission'); ?>
                            </a>
                        </li>
                        <li class="nav-item" id="li_lesson_view">
                            <a class="nav-link" data-toggle="tab" href="#lesson_course_tab" role="tab">
                                <?php echo label('lesson'); ?>
                            </a>
                        </li>
                        <li class="nav-item" id="li_quiz_view">
                            <a class="nav-link" data-toggle="tab" href="#quiz_course_tab" role="tab">
                                <?php echo label('quiz'); ?>
                            </a>
                        </li>
                        <li class="nav-item" id="li_survey_view">
                            <a class="nav-link" data-toggle="tab" href="#survey_course_tab" role="tab">
                                <?php echo label('survey'); ?>
                            </a>
                        </li>
                        <li class="nav-item" id="li_enroll_view">
                            <a class="nav-link" data-toggle="tab" href="#student_enroll_course_tab" role="tab">
                                <?php echo label('student_enroll'); ?>
                            </a>
                        </li>
                        <li class="nav-item" id="li_document_view">
                            <a class="nav-link" data-toggle="tab" href="#document_file_course_tab" role="tab">
                                <?php echo label('lesson_file'); ?>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="main_course_tab" role="tabpanel" style="pointer-events: none;">
                            <div class="p-20 card">
                                <div class="card-body row">
                                    <div class="form-group col-md-6">
                                        <label for="cg_id_view"><b style="color: #FF2D00">*</b><?php echo label('cgtitle'); ?>:</label>
                                        <select class="form-control select2" id="cg_id_view" multiple  style="width: 100%;">
                                        </select><br><br>
                                        <label for="tc_id_view"><b style="color: #FF2D00">*</b><?php echo label('ceCtype'); ?>:</label>
                                        <select class="form-control" id="tc_id_view"  style="width: 100%;">
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6"><br>
                                        <label><b style="color: #FF2D00">*</b><?php echo label('ceCforlang'); ?>:</label><br>
                                        <input type="checkbox" id="cos_lang_eng_view" class="filled-in chk-col-red" value="eng"/>
                                        <label for="cos_lang_eng_view"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></label><br>
                                        <input type="checkbox" id="cos_lang_th_view" class="filled-in chk-col-red" value="th"/>
                                        <label for="cos_lang_th_view"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></label><br>
                                        <input type="checkbox" id="cos_lang_jp_view" class="filled-in chk-col-red" value="jp"/>
                                        <label for="cos_lang_jp_view"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></label>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>

                                    <div class="col-md-12 input_eng_view" style="display:none;">
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                            <div class="ribbon-content row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="cname_eng_view"><b style="color: #FF2D00">*</b><?php echo label('ceCname'); ?>:</label>
                                                    <input type="text" class="form-control" id="cname_eng_view">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="cdesc_eng_view"><?php echo label('ceDsc'); ?>:</label>
                                                    <textarea class="form-control" id="cdesc_eng_view" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 input_th_view" style="display:none;">
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                            <div class="ribbon-content row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="cname_th_view"><b style="color: #FF2D00">*</b><?php echo label('ceCname'); ?>:</label>
                                                <input type="text" class="form-control" id="cname_th_view">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="control-label" for="cdesc_th_view"><?php echo label('ceDsc'); ?>:</label>
                                                <textarea class="form-control" id="cdesc_th_view" rows="5"></textarea>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 input_jp_view" style="display:none;">
                                        <div class="ribbon-wrapper card">
                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                            <div class="ribbon-content row">
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="cname_jp_view"><b style="color: #FF2D00">*</b><?php echo label('ceCname'); ?>:</label>
                                                <input type="text" class="form-control" id="cname_jp_view">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="control-label" for="cdesc_jp_view"><?php echo label('ceDsc'); ?>:</label>
                                                <textarea class="form-control" id="cdesc_jp_view" rows="5"></textarea>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="condition_view"><?php echo label('ceCbef'); ?>:</label>
                                        <select class="form-control select2" multiple id="condition_view" name="condition_view[]"  style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="goal_score_view"><b style="color: #FF2D00">*</b><?php echo label('ccond'); ?>:</label>
                                        <input type="text" min="0" step="1" class="form-control" id="goal_score_view">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cos_status_view"><b style="color: #FF2D00">*</b><?php echo label('ceCvis'); ?>:</label>
                                            <div class="switch">
                                                <label><?php echo label('close'); ?><input type="checkbox" id="cos_status_view" checked value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="cos_typegrading_view"><b style="color: #FF2D00">*</b><?php echo label('typegrading'); ?>:</label>
                                        <select id="cos_typegrading_view" class="form-control">
                                            <option value="2" selected><?php echo label('typegrading_b'); ?></option>
                                            <option value="1" ><?php echo label('typegrading_a'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="seat_count_view"><?php echo label('numSeat'); ?>:</label>
                                        <input type="text" min="0"  step="1" class="form-control" id="seat_count_view">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="cos_hour_view"><?php echo label('cos_hour'); ?>:</label>
                                        <input type="text" min="0" step="1" class="form-control" id="cos_hour_view">
                                    </div>
                                    <div class="form-group col-md-6" id="div_cospicviewDemo" style="display: none;">
                                        <label class="control-label" for="view_img_cos_view"><?php echo label('ceCpic'); ?></label><br>
                                        <center><img src="" id="view_img_cos_view" style="width: 60%" alt=""></center>
                                    </div>
                                    <div class="form-group col-md-12"><hr></div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label" for="cos_expire_noti_view"><?php echo label('noti_expire_cos'); ?>:</label>
                                        <input type="text" class="form-control" id="cos_expire_noti_view">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_survey_required_view"><b style="color: #FF2D00">*</b><?php echo label('require_survey'); ?>:</label>
                                            <div class="switch">
                                                <label><?php echo label('not_required'); ?>
                                                    <input type="checkbox" id="is_survey_required_view" name="is_survey_required_view" value="1">
                                                    <span class="lever switch-col-indigo"></span>
                                                    <?php echo label('required'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="certificate_course_tab" role="tabpanel" style="pointer-events: none;">
                            
                            <div class="card-body row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="badges_name_view"><?php echo label('certName'); ?>:</label>
                                    <input type="text" class="form-control" id="badges_name_view">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="badges_condition_view"><?php echo label('condCert'); ?>:</label>
                                    <select class="form-control" id="badges_condition_view"  style="width: 100%;">
                                        <option value="A" selected>A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="badges_desc_view"><?php echo label('customCert'); ?>:</label>
                                    <textarea class="form-control" id="badges_desc_view" rows="8" style="width: 100%"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="badges_img_view"><?php echo label('certpic'); ?>:</label>
                                    <img src="" id="badges_img_view" style="width: 60%" alt="">
                                </div>
                                <div class="form-group col-md-12">
                                    <hr>
                                    <h5 align="left"><?php echo label('grading'); ?></h5>
                                    <hr>
                                </div>
                                <div class="form-group col-md-6 typegrading_a_view">
                                    <label class="control-label" for="mina_view"><?php echo label('cusGrade')."A (%)"; ?>:</label>
                                    <input type="text" class="form-control" min="0" step="1" max="100" id="mina_view" value="80">
                                </div>
                                <div class="form-group col-md-6 typegrading_a_view">
                                    <label class="control-label" for="minb_view"><?php echo label('cusGrade')."B (%)"; ?>:</label>
                                    <input type="text" class="form-control" min="0" step="1" max="100" id="minb_view" value="70">
                                </div>
                                <div class="form-group col-md-6 typegrading_a_view">
                                    <label class="control-label" for="minc_view"><?php echo label('cusGrade')."C (%)"; ?>:</label>
                                    <input type="text" class="form-control" min="0" step="1" max="100" id="minc_view" value="60">
                                </div>
                                <div class="form-group col-md-6 typegrading_a_view">
                                    <label class="control-label" for="mind_view"><?php echo label('cusGrade')."D (%)"; ?>:</label>
                                    <input type="text" class="form-control" min="0" step="1" max="100" id="mind_view" value="50">
                                </div>

                                <div class="form-group col-md-6 typegrading_b_view" style="display: none;">
                                    <label class="control-label" for="mina_b_view"><?php echo label('ccond'); ?>:</label>
                                    <input type="text" class="form-control" min="0" step="1" max="100" id="mina_b_view" value="50">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="period_and_permission_course_tab" role="tabpanel">
                            <div class="card-body row">
                                <div class="form-group col-md-12" style="pointer-events: none;">
                                    <label class="control-label text-right"><?php echo label('period'); ?>: </label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label text-right" for="date_start_view"><?php echo label('r_start_on'); ?></label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input  type="text" id="date_start_view"
                                                            class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                    data-autoclose="true">
                                                    <input type="text" id="time_start_view" class="form-control"
                                                        value="<?php echo date('H:i', strtotime('00:00')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label text-right" for="date_end_view"><?php echo label('r_finish_on'); ?></label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" id="date_end_view" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                    data-autoclose="true">
                                                    <input type="text" id="time_end_view" class="form-control"
                                                        value="<?php echo date('H:i', strtotime('23:59')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <hr>
                                    <label class="control-label text-right"><?php echo label('permission'); ?>:</label>
                                    <hr>
                                    <div id="view_permission_div">
                                    </div><br>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="lesson_course_tab" role="tabpanel">
                            <div class="card-body row">
                                <div id="div_lesson_view" class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="myTable_lesson_view" class="table table-bordered table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%" id="col_lessson">
                                                        <?php echo textCenter(label('manage')); ?>
                                                    </th>
                                                    <th width="10%"></th>
                                                    <th width="10%">
                                                        <?php echo textCenter(label('faqlang')); ?>
                                                    </th>
                                                    <th width="45%">
                                                        <?php echo textCenter(label('lName')); ?>
                                                    </th>
                                                    <th width="15%">
                                                        <?php echo textCenter(label('dateStart')); ?>
                                                    </th>
                                                    <th width="15%">
                                                        <?php echo textCenter(label('dateExpired')); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <p><?php echo label('preNote'); ?>:
                                        <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-note-multiple"></i></button> =
                                            <b><?php echo label('detail'); ?></b>
                                    </p>
                                </div>
                                

                                <div id="div_create_lesson_view" class="col-md-12 row" style="display: none;">
                                    <div class="col-md-12">
                                        <button type="button"
                                                class="btn btn-outline-danger float-right"
                                                onclick="display_style('div_create_lesson_view','div_lesson_view')"><i class="mdi mdi-keyboard-return"></i>
                                                <?php echo label('m_previous'); ?>
                                        </button>
                                        <h3 id="txthead_lesson_view"></h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-12 row" style="pointer-events: none;">

                                        <div class="col-md-12 input_les_th_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger">
                                                    <i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?>
                                                </div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="les_name_th_view"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                                                        <input type="text" class="form-control" id="les_name_th_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="les_info_th_view"><?php echo label('lesson_summary'); ?>:</label>
                                                        <textarea id="les_info_th_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 input_les_eng_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger">
                                                    <i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                                                </div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="les_name_eng_view"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                                                        <input type="text" class="form-control" id="les_name_eng_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="les_info_eng_view"><?php echo label('lesson_summary'); ?>:</label>
                                                        <textarea id="les_info_eng_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 input_les_jp_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger">
                                                    <i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                                                </div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="les_name_jp_view"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                                                        <input name="les_name_jp" type="text" class="form-control" id="les_name_jp_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="les_info_jp_view"><?php echo label('lesson_summary'); ?>:</label>
                                                        <textarea name="les_info_jp" id="les_info_jp_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="control-label text-right"><?php echo label('period_les'); ?>: </label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label text-right" for="date_start_les_view"><?php echo label('r_start_on'); ?></label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" id="date_start_les_view" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                            data-autoclose="true">
                                                            <input type="text" id="time_start_les_view" class="form-control"
                                                                value="<?php echo date('H:i', strtotime('00:00')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label text-right" for="date_end_les_view"><?php echo label('r_finish_on'); ?></label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" id="date_end_les_view" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                            data-autoclose="true">
                                                            <input type="text" id="time_end_les_view" class="form-control"
                                                                value="<?php echo date('H:i', strtotime('23:59')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status_les_view"><b style="color: #FF2D00">*</b><?php echo label('less_visible'); ?>:</label>
                                                <div class="switch">
                                                    <label><?php echo label('svhid2'); ?><input type="checkbox" id="status_les_view" checked
                                                        value="1"><span class="lever switch-col-indigo"></span><?php echo label('svhid1'); ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="les_type_view"><b style="color: #FF2D00">*</b><?php echo label('qr_typefile'); ?>:</label>
                                                <div class="switch">
                                                    <label><?php echo "Media"; ?><input type="checkbox" id="les_type_view" value="2"><span
                                                        class="lever switch-col-indigo"></span><?php echo "Scorm"; ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 row" id="div_media_view">
                                            <div class="form-group col-md-12" style="margin: 0px auto 10px auto;">
                                                <label for="status_cr"><?php echo label('media_type'); ?>:</label>
                                                <select class="form-control" id="type_media_view" style="width: 100%;" required>
                                                    <option value="0" selected><?php echo label('none'); ?></option>
                                                    <option value="1"><?php echo "URL"; ?></option>
                                                    <option value="2"><?php echo "Upload File"; ?></option>
                                                </select>
                                                <div class="" id="div_multifile_url_view" style="display: none;">
                                                    <textarea class="form-control" id="url_media_view" rows="5" style="width: 100%"></textarea>
                                                    <label class="control-label text-right" for="url_media_view"><?php echo label('les_url_msg'); ?></label>
                                                </div>
                                                <div class="" id="div_multifile_upload_file_view" style="display: none;"><br>
                                                    <div class="table-responsive" id="tb_media_view" style="display: none;">
                                                        <table id="myTable_media_view" width="100%" class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th width="10%"></th>
                                                                <th width="50%">
                                                                <center><?php echo label('file_name'); ?></center>
                                                                </th>
                                                                <th width="40%">
                                                                <center><?php echo label('menu_path'); ?></center>
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6" style="margin: 0px auto 10px auto;"></div>
                                            <div class="form-group col-md-12" style="margin: 0px auto 10px auto;">
                                            <hr>
                                            <h5 align="left"><?php echo label('file_document'); ?></h5>
                                            <br>
                                            <div class="table-responsive" id="tb_document_view" style="display: none;">
                                                <table id="myTable_document_view" width="100%" border="1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                    <th width="10%"></th>
                                                    <th width="20%" class="input_les_th_view">
                                                        <?php echo textCenter(label('file_name') . " " . label('acro_th')); ?>
                                                    </th>
                                                    <th width="20%" class="input_les_eng_view">
                                                        <?php echo textCenter(label('file_name') . " " . label('acro_en')); ?>
                                                    </th>
                                                    <th width="20%" class="input_les_jp_view">
                                                        <?php echo textCenter(label('file_name') . " " . label('acro_jp')); ?>
                                                    </th>
                                                    <th width="30%">
                                                        <?php echo textCenter(label('menu_path')); ?>
                                                    </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tb_document-tbody" id="tb_document_body_view"></tbody>
                                                </table>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 row" id="div_scorm_view">
                                            <div class="form-group col-md-6">
                                                <h5 align="left"><?php echo label('les_scorm'); ?>:</h5>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="control-label text-right"><b
                                                    style="color: #FF2D00">*</b><?php echo label('les_type_scorm'); ?>:</label>
                                                <div class="m-b-10">
                                                    <label class="custom-control custom-radio" for="radio_scm_type1_view">
                                                        <input type="radio" id="radio_scm_type1_view" checked value="0"
                                                            class="custom-control-input">
                                                        <span class="custom-control-label"><?php echo label('lesson'); ?></span>
                                                    </label>
                                                    <label class="custom-control custom-radio" for="radio_scm_type2_view">
                                                        <input type="radio" id="radio_scm_type2_view" value="1" class="custom-control-input">
                                                        <span class="custom-control-label"><?php echo label('quiz'); ?></span>
                                                    </label>
                                                    <label class="custom-control custom-radio" for="radio_scm_type3_view">
                                                        <input type="radio" id="radio_scm_type3_view" value="2" class="custom-control-input">
                                                        <span class="custom-control-label"><?php echo label('lesson') . " + " . label('quiz'); ?></span>
                                                    </label>
                                                </div>
                                                <hr>
                                                <span id="txt_scormoriginal_view"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="quiz_course_tab" role="tabpanel">
                            <div class="card-body row">
                                <div id="div_quiz_view" class="col-md-12">
                                    <div class="table-responsive">
                                    <table id="myTable_quiz_view" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th style="min-width: 80px;">
                                                <?php echo textCenter(label('manage')); ?>
                                            </th>
                                            <th width="10%"></th>
                                            <th width="10%">
                                                <?php echo textCenter(label('faqlang')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('qName')); ?>
                                            </th>
                                            <th width="10%">
                                                <?php echo textCenter(label('qiz_type')); ?>
                                            </th>
                                            <th width="10%">
                                                <?php echo textCenter(label('maxScore')); ?>
                                            </th>
                                            <th width="10%">
                                                <?php echo textCenter(label('ccond')); ?>
                                            </th>
                                            <th width="10%">
                                                <?php echo textCenter(label('dateStart')); ?>
                                            </th>
                                            <th width="10%">
                                                <?php echo textCenter(label('dateExpired')); ?>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    </div>
                                    <p><?php echo label('preNote'); ?>:
                                        <button type="button"
                                                class="btn btn-info btn-xs"><i class="mdi mdi-comment-question-outline"></i>
                                        </button> = <b><?php echo label('question'); ?></b> ,
                                        <button type="button"
                                                class="btn btn-info btn-xs"><i class="mdi mdi-note-multiple"></i>
                                        </button> = <b><?php echo label('detail'); ?></b>
                                    </p>
                                </div>
                                
                                <div id="div_create_quiz_view" class="col-md-12 row" style="display: none;">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-danger float-right"
                                        onclick="display_style('div_create_quiz_view','div_quiz_view')"><i class="mdi mdi-keyboard-return"></i>
                                        <?php echo label('m_previous'); ?></button>
                                        <h3 id="txthead_quiz_view"></h3>
                                        <hr>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="alert alert-warning" role="alert">
                                        <p> <?php echo label('quiz_limit_note'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 row" style="pointer-events: none;">
                                        <input type="hidden" id="quiz_lang_view">
                                        <div class="col-md-12 input_quiz_th_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="quiz_name_th_view"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                                                        <input type="text" class="form-control" id="quiz_name_th_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="quiz_info_th_view"><?php echo label('qiz_detail'); ?>:</label>
                                                        <textarea id="quiz_info_th_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 input_quiz_eng_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger">
                                                    <i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                                                </div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="quiz_name_eng_view"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                                                        <input type="text" class="form-control" id="quiz_name_eng_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="quiz_info_eng_view"><?php echo label('qiz_detail'); ?>:</label>
                                                        <textarea id="quiz_info_eng_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 input_quiz_jp_view" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger">
                                                    <i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                                                </div>
                                                <div class="ribbon-content row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="quiz_name_jp_view"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                                                        <input type="text" class="form-control" id="quiz_name_jp_view">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label class="control-label" for="quiz_info_jp_view"><?php echo label('qiz_detail'); ?>:</label>
                                                        <textarea id="quiz_info_jp_view" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label"><?php echo label('periodlog'); ?>: </label>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label"><?php echo label('r_start_on'); ?></label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" id="period_open_view" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                                data-autoclose="true">
                                                                <input  type="text" id="time_start_quiz_view" class="form-control"
                                                                        value="<?php echo date('H:i', strtotime('00:00')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label" for="period_end_view"><?php echo label('r_finish_on'); ?></label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" id="period_end_view" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                                                data-autoclose="true">
                                                                <input type="text" id="time_end_quiz_view" class="form-control"
                                                                        value="<?php echo date('H:i', strtotime('23:59')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_random_view"><b style="color: #FF2D00">*</b><?php echo label('random'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('disable'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_random_view">
                                                            <input type="checkbox" id="quiz_random_view" value="1">
                                                            <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('enable'); ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_random_choice_view"><b style="color: #FF2D00">*</b><?php echo label('random_choice'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('disable'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                <div class="switch">
                                                    <label for="quiz_random_choice_view">
                                                        <input type="checkbox" id="quiz_random_choice_view" value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                    </label>
                                                </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('enable'); ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_ishint_view"><b style="color: #FF2D00">*</b><?php echo label('is_quiz_hint'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('sv_b_hide'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_ishint_view">
                                                            <input type="checkbox" id="quiz_ishint_view" value="1">
                                                            <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('sv_b_show'); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_model_view"><b style="color: #FF2D00">*</b><?php echo label('model'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('can_skip_question'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_model_view">
                                                        <input type="checkbox" id="quiz_model_view" class="radio_chklimit" value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('answer_until_correct'); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_grade_view"><b style="color: #FF2D00">*</b><?php echo label('show_myscore'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('hide'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_grade_view">
                                                        <input type="checkbox" id="quiz_grade_view" value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('show'); ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_type_view"><b style="color: #FF2D00">*</b><?php echo label('qiz_type'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('preExam'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_type_view">
                                                        <input type="checkbox" id="quiz_type_view" checked value="2">
                                                        <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('finalExam'); ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="quiz_show_view"><b style="color: #FF2D00">*</b><?php echo label('qiz_visible'); ?>:</label>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    <small><?php echo label('hide'); ?></small>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <div class="switch">
                                                        <label for="quiz_show_view">
                                                        <input type="checkbox" id="quiz_show_view" checked value="1">
                                                        <span class="lever switch-col-indigo"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <small><?php echo label('show'); ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4" id="div_answer_view" style="display: none;">
                                            <div>
                                                <label class="control-label" for="quiz_answer_view"><b style="color: #FF2D00">*</b><?php echo label('preAns'); ?>:</label>
                                                <div class="row">
                                                    <div class="col-4 text-right">
                                                        <small><?php echo label('sv_b_hide'); ?></small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="switch">
                                                            <label for="quiz_answer_view">
                                                                <input type="checkbox" id="quiz_answer_view" value="1">
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

                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                <label class="control-label" for="quiz_limit_view"><b style="color: #FF2D00">*</b><?php echo label('qiz_limit'); ?>:</label>
                                                <div class="row">
                                                    <div class="col-4 text-right">
                                                        <small><?php echo label('no'); ?></small>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="switch">
                                                            <label for="quiz_limit_view">
                                                            <input type="checkbox" id="quiz_limit_view">
                                                            <span class="lever switch-col-indigo"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small><?php echo label('yes'); ?></small>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="quiz_limitval_view"><b class="text-danger">*</b><?php echo label('number_of'); ?>:</label>
                                                    <input type="number" class="form-control" id="quiz_limitval_view">
                                                </div>

                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                        <div class="row">

                                            <div class="form-group col-md-4 div_lastquiz_view">
                                            <label class="control-label" for="quiz_numofshown_view"><b
                                                class="text-danger">*</b><?php echo label('quiz_numofshown'); ?>:</label>

                                            <div class="input-group">
                                                <input type="number" class="form-control" id="quiz_numofshown_view">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="txt_totalquiz_view"></span>
                                                </div>
                                            </div>
                                            <input type="hidden" id="totalquiz_view"><br>
                                            <?php echo label('quiz_numofshown_note'); ?>
                                            </div>
                                            <div class="form-group col-md-4 div_lastquiz_view">
                                                <label class="control-label" for="quiz_maxscore_view"><b style="color: #FF2D00">*</b><?php echo label('ccond'); ?>:</label>
                                                <input type="text" value="0" class="form-control" id="quiz_maxscore_view">
                                            </div>
                                            <div class="form-group col-md-4" id="div_template_qize_view">
                                                <label class="control-label" for="qize_id_view"><?php echo label('quiz_ex'); ?>:</label>
                                                <select class="form-control" id="qize_id_view" style="width: 100%;">
                                                </select>
                                            </div>

                                        </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="div_quiz_question_view" class="col-md-12" style="display: none;">
                                    <div class="col-md-12" align="right">
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="display_style('div_quiz_question_view','div_quiz_view')"><i class="mdi mdi-keyboard-return"></i>
                                            <?php echo label('m_previous'); ?></button>
                                        <h3 class="float-left"><?php echo label('question'); ?></h3>
                                        <hr>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="myTable_quiz_question_view" width="100%" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th width="5%"></th>
                                                <th width="15%">
                                                    <?php echo textCenter(label('quest_type')); ?>
                                                </th>
                                                <th width="45%">
                                                    <?php echo textCenter(label('squestion')); ?>
                                                </th>
                                                <th width="25%">
                                                    <?php echo textCenter(label('choice')); ?>
                                                </th>
                                                <th width="10%" align="center">
                                                    <?php echo textCenter(label('status')); ?>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="survey_course_tab" role="tabpanel">
                            <div class="card-body row">
                                <div id="div_survey_view" class="col-md-12">
                                    <div class="table-responsive">
                                    <table id="myTable_cos_id_survey_view" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th width="10%">
                                                <?php echo textCenter(label('manage')); ?>
                                            </th>
                                            <th width="10%"></th>
                                            <th width="15%">
                                                <?php echo textCenter(label('faqlang')); ?>
                                            </th>
                                            <th width="25%">
                                                <?php echo textCenter(label('sName')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('r_start_on')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('r_finish_on')); ?>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    </div>
                                    <p><?php echo label('preNote'); ?>:
                                        <button type="button"
                                                class="btn btn-info btn-xs"><i class="mdi mdi-comment-question-outline"></i>
                                        </button> = <b><?php echo label('question'); ?></b> ,
                                        <button type="button"
                                                class="btn btn-info btn-xs"><i class="mdi mdi-note-multiple"></i>
                                        </button> = <b><?php echo label('detail'); ?></b>
                                    </p>
                                </div>
                                <div id="div_create_survey_view" style="display: none;">
                                    <div class="col-md-12 row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-outline-danger float-right" type="button"
                                                onclick="display_style('div_create_survey_view','div_survey_view')"><i class="mdi mdi-keyboard-return"></i>
                                                <?php echo label('m_previous'); ?></button>
                                            <h3 id="txthead_survey_view"></h3>
                                            <hr>
                                        </div>
                                        <div class="col-md-12 row" style="pointer-events: none;">
                                            <input type="hidden" id="sv_lang_view">

                                            <div class="col-md-12 input_survey_eng_view" style="display:none;">
                                                <div class="ribbon-wrapper card">
                                                    <div class="ribbon ribbon-danger">
                                                        <i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                                                    </div>
                                                    <div class="ribbon-content row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label" for="sv_title_eng_view"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                                                            <input type="text" class="form-control" id="sv_title_eng_view">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label class="control-label" for="sv_explanation_eng_view"><?php echo label('svdesc'); ?>:</label>
                                                            <textarea id="sv_explanation_eng_view" class="form-control" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 input_survey_th_view" style="display:none;">
                                                <div class="ribbon-wrapper card">
                                                    <div class="ribbon ribbon-danger">
                                                        <i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?>
                                                    </div>
                                                    <div class="ribbon-content row">
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label" for="sv_title_th_view"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                                                            <input type="text" class="form-control" id="sv_title_th_view">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label class="control-label" for="sv_explanation_th_view"><?php echo label('svdesc'); ?>:</label>
                                                            <textarea id="sv_explanation_th_view" class="form-control" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 input_survey_jp_view" style="display:none;">
                                                <div class="ribbon-wrapper card">
                                                    <div class="ribbon ribbon-danger">
                                                        <i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                                                    </div>
                                                    <div class="ribbon-content row">
                                                        <div class="form-group col-md-6 input_jp">
                                                            <label class="control-label" for="sv_title_jp_view"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                                                            <input type="text" class="form-control" id="sv_title_jp_view">
                                                        </div>
                                                        <div class="form-group col-md-12 input_jp">
                                                            <label class="control-label" for="sv_explanation_jp_view"><?php echo label('svdesc'); ?>:</label>
                                                            <textarea id="sv_explanation_jp_view" class="form-control" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group col-md-12">
                                                <label class="control-label"><?php echo label('sv_specific'); ?>: </label>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="survey_open_view"><?php echo label('r_start_on'); ?></label>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <input type="text" id="survey_open_view" class="form-control">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-group clockpicker" data-placement="bottom" data-align="top"
                                                                    data-autoclose="true">
                                                                    <input type="text" id="time_start_survey_view" class="form-control"
                                                                    value="<?php echo date('H:i', strtotime('00:00')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="survey_end_view"><?php echo label('r_finish_on'); ?></label>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <input type="text" id="survey_end_view" class="form-control">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-group clockpicker" data-placement="bottom" data-align="top"
                                                                    data-autoclose="true">
                                                                    <input type="text" id="time_end_survey_view" class="form-control"
                                                                    value="<?php echo date('H:i', strtotime('23:59')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="sv_suggestion_status_view"><b
                                                    style="color: #FF2D00">*</b><?php echo label('quessuggestion_status'); ?>:</label>
                                                <div class="switch">
                                                    <label><?php echo label('no'); ?><input type="checkbox" id="sv_suggestion_status_view" checked
                                                        value="1"><span
                                                        class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label" for="sv_status_view"><b style="color: #FF2D00">*</b><?php echo label('quesStatus'); ?>:</label>
                                                <div class="switch">
                                                    <label><?php echo label('close'); ?><input type="checkbox" id="sv_status_view" checked
                                                        value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="qn_id_view"><?php echo label('svtheme'); ?>:</label>
                                                <select class="form-control select2" id="qn_id_view" style="width: 100%;">
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                                <div id="div_sv_survey_detail_view" style="display: none;" class="col-md-12 row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-danger float-right"
                                                onclick="display_style('div_sv_survey_detail_view','div_survey_view')"
                                        ><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                                    </div>
                                    <div class="col-md-12">
                                        <h4 id="sv_name_txt_view"></h4>
                                        <hr>
                                        <div class="table-responsive">
                                            <table id="myTable_survey_detail_view" width="100%" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="10%"></th>
                                                    <th width="50%">
                                                        <?php echo textCenter(label('questitle')); ?>
                                                    </th>
                                                    <th width="40%">
                                                        <?php echo textCenter(label('squestion')); ?>
                                                    </th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="student_enroll_course_tab" role="tabpanel">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="myTable_enroll_view" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th width="5%"></th>
                                            <th width="35%">
                                                <?php echo textCenter(label('m_name')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('m_company')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('learning_status')); ?>
                                            </th>
                                            <th width="20%">
                                                <?php echo textCenter(label('score')); ?>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="document_file_course_tab" role="tabpanel">
                            <div class="card-body row">
                                <div class="col-md-12 table-responsive" id="tb_cos_document_view">
                                    <table  id="myTable_cos_document_view" width="100%"
                                            class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    <?php echo textCenter(label('faqlang')); ?>
                                                </th>
                                                <th width="55%">
                                                    <?php echo textCenter(label('file_name')); ?>
                                                </th>
                                                <th width="15%">
                                                    <?php echo textCenter(label('dateMod')); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
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