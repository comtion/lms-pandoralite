<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                    </div>
                </div> 

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create_quiz_ex'); ?></button>
                        <?php } ?>
                        <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                        <div class="row">
                            <div class="col-md-6">
                                  <label for="com_id_search"><?php echo label('com_name'); ?>:</label>
                                  <select class="form-control select2" id="com_id_search" name="com_id_search" style="width: 100%;">
                                    <?php   if(countArray($company_arr)>0){ ?>
                                                <optgroup label="<?php echo label('please_com_name'); ?>">
                                        <?php   $numloop = 1;
                                                foreach ($company_arr as $key_com => $value_com) { ?>
                                                    <option value="<?php echo $value_com['com_id']; ?>" <?php if($numloop==1){echo "selected";}$numloop++; ?>><?php echo $lang=="thai"?$value_com['com_name_th']:$value_com['com_name_eng']; ?></option>
                                        <?php   } ?>
                                                </optgroup>
                                    <?php   } ?>
                                  </select>
                            </div>
                        </div>
                        <?php }else{ ?>
                            <input type="hidden" id="com_id_search" name="com_id_search" value="<?php echo $com_id; ?>">
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="10%"><center><?php echo label('faqlang'); ?></center></th>
                                <th width="30%"><center><?php echo label('quiz_ex'); ?></center></th>
                                <th width="25%"><center><?php echo label('m_company'); ?></center></th>
                                <th width="15%"><center><?php echo label('com_createdate'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-comment-question-outline"></i></button> = <b><?php echo label('create_question'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>

            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="quiz_ex_form" autocomplete="off" name="quiz_ex_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <select class="form-control select2" required id="com_id" name="com_id"  style="width: 100%;">
                                        <option value=""><?php echo label('please_com_name'); ?></option>
                                      <?php foreach( $company_select as $company ){ ?>
                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?></option>
                                      <?php   
                                            } ?>
                                </select>
                  </div>
                </div>
                                <?php }else{ ?>
                                    <!-- <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly> -->
                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                <?php } ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b style="color: #FF2D00">*</b><?php echo label('ceCforlang'); ?>:</label><br>
                        <input type="checkbox" id="qize_lang_eng" name="qize_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qize_lang_eng','input_eng','qize_name_')" value="eng" <?php if($lang=="english"){ echo "checked";} ?>/>
                        <label for="qize_lang_eng"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></label><br>
                        <input type="checkbox" id="qize_lang_th" name="qize_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qize_lang_th','input_th','qize_name_')" value="th" <?php if($lang=="thai"){ echo "checked";} ?>/>
                        <label for="qize_lang_th"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></label><br>
                        <input type="checkbox" id="qize_lang_jp" name="qize_lang[]" class="filled-in chk-col-red" onclick="chkbox_lang('qize_lang_jp','input_jp','qize_name_')" value="jp" <?php if($lang=="japan"){ echo "checked";} ?>/>
                        <label for="qize_lang_jp"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></label>
                    </div>
                </div>
                                        <div class="col-md-12 input_eng" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                                <div class="ribbon-content row">
                                                  <div class="form-group col-md-6">
                                                      <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quiz_ex'); ?>:</label>
                                                      <input name="qize_name_eng" type="text" class="form-control" id="qize_name_eng">
                                                  </div>
                                                  <div class="form-group col-md-12">
                                                      <label class="control-label"><?php echo label('detail'); ?>:</label>
                                                      <textarea name="qize_info_eng" id="qize_info_eng" class="form-control" rows="5"></textarea>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 input_th" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                                <div class="ribbon-content row">
                                                  <div class="form-group col-md-6">
                                                      <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quiz_ex'); ?>:</label>
                                                      <input name="qize_name_th" type="text" class="form-control" id="qize_name_th">
                                                  </div>
                                                  <div class="form-group col-md-12">
                                                      <label class="control-label"><?php echo label('detail'); ?>:</label>
                                                      <textarea name="qize_info_th" id="qize_info_th" class="form-control" rows="5"></textarea>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 input_jp" style="display:none;">
                                            <div class="ribbon-wrapper card">
                                                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                                <div class="ribbon-content row">
                                                  <div class="form-group col-md-6 input_jp">
                                                      <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quiz_ex'); ?>:</label>
                                                      <input name="qize_name_jp" type="text" class="form-control" id="qize_name_jp">
                                                  </div>
                                                  <div class="form-group col-md-12 input_jp">
                                                      <label class="control-label"><?php echo label('detail'); ?>:</label>
                                                      <textarea name="qize_info_jp" id="qize_info_jp" class="form-control" rows="5"></textarea>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="qize_id" name="qize_id">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action_main" id="action_main"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->



    <div class="modal fade bs-example-modal-lg" id="modal-question" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 id="myLargeModalLabel"><?php echo label('create_question'); ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body row">
                <input type="hidden" id="qize_id_detail" name="qize_id_detail">

                <div id="div_quiz_question" class="col-md-12">
                  <button name="add_question" id="add_question" class="btn btn-outline-info add_question float-right"><i class="mdi mdi-plus-box-outline"></i> <?php echo ucwords(label('sqaddsquestion')); ?></button>
                  <div class="table-responsive">
                        <table id="myTable_quiz_question" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="10%"><center><?php echo label('manage'); ?></center></th>
                                <th width="20%"><center><?php echo label('quest_type'); ?></center></th>
                                <th width="40%"><center><?php echo label('squestion'); ?></center></th>
                                <th width="30%"><center><?php echo label('choice'); ?></center></th>
                              </tr>
                            </thead>
                        </table>
                  </div>
                      <p><?php echo label('preNote'); ?>: <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                </div>


                                        <div id="div_create_question" style="display: none;" class="col-md-12">

                                            <button name="back_quiz" id="back_quiz" class="btn btn-danger back_quiz float-right" onclick="display_style('div_create_question','div_quiz_question')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                                            <form  enctype="multipart/form-data" id="question_form" name="question_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                                <input type="hidden" id="qize_id_question" name="qize_id_question">
                                                <input type="hidden" id="quese_id" name="quese_id">
                                                <input type="hidden" id="operation_question" name="operation_question" value="Add">
                                                <div class="col-md-12 row" style="">
                                                    <div class="col-md-12 input_ques_eng">
                                                        <div class="ribbon-wrapper card">
                                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                                            <div class="ribbon-content row">
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                                                                  <textarea name="quese_name_eng" id="quese_name_eng" rows="10" class="form-control"></textarea>
                                                              </div>
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><?php echo label('quesExplanation'); ?>:</label>
                                                                  <textarea name="quese_info_eng" id="quese_info_eng" rows="10" class="form-control"></textarea>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                  <div class="col-md-12 input_ques_th">
                                                        <div class="ribbon-wrapper card">
                                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                                            <div class="ribbon-content row">
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                                                                  <textarea name="quese_name_th" id="quese_name_th" rows="10" class="form-control"></textarea>
                                                              </div>
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><?php echo label('quesExplanation'); ?>:</label>
                                                                  <textarea name="quese_info_th" id="quese_info_th" rows="10" class="form-control"></textarea>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 input_ques_jp">
                                                        <div class="ribbon-wrapper card">
                                                            <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                                            <div class="ribbon-content row">
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                                                                  <textarea name="quese_name_jp" id="quese_name_jp" rows="10" class="form-control"></textarea>
                                                              </div>
                                                              <div class="form-group col-md-6">
                                                                  <label class="control-label text-right"><?php echo label('quesExplanation'); ?>:</label>
                                                                  <textarea name="quese_info_jp" id="quese_info_jp" rows="10" class="form-control"></textarea>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('quest_visible'); ?>:</label>
                                                        <div class="switch">
                                                            <label><?php echo label('hide'); ?><input type="checkbox"  id="quese_show" name="quese_show" checked value="1"><span class="lever switch-col-indigo"></span><?php echo label('show'); ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="control-label text-right"><?php echo label('maxScore'); ?>:</label>
                                                        <input name="quese_score"  type="number" min="0"   step="1" pattern="[0123456789]" class="form-control" id="quese_score">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label><b style="color: #FF2D00">*</b><?php echo label('quest_type'); ?>:</label>
                                                        <select class="form-control" required id="quese_type" name="quese_type"  style="width: 100%;">
                                                            <option value=""><?php echo label('sv_b_choose_question_type'); ?></option>
                                                            <option value="sa"><?php echo label('qt_sa'); ?></option>
                                                            <option value="sub"><?php echo label('qt_sub'); ?></option>
                                                            <option value="2choice"><?php echo label('qt_twoChoice'); ?></option>
                                                            <option value="multi"><?php echo label('qt_multi'); ?></option>
                                                            <!-- <option value="scale"><?php echo label('qt_scale'); ?></option> -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="col-md-12 row" id="div_question_mul" style="display: none;">
                                                        <div class="form-group col-md-6 courseCat">
                                                            <h4><?php echo label('quest_detail'); ?></h4>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                        </div>

                                                        <!-- <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 1"; ?>:</label>
                                                            <textarea name="mule_c1" id="mule_c1" rows="10"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 2"; ?>:</label>
                                                            <textarea name="mule_c2" id="mule_c2" rows="10"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 3"; ?>:</label>
                                                            <textarea name="mule_c3" id="mule_c3" rows="10"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 4"; ?>:</label>
                                                            <textarea name="mule_c4" id="mule_c4" rows="10"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="control-label text-right"><?php echo label('choice')." 5"; ?>:</label>
                                                            <textarea name="mule_c5" id="mule_c5" rows="10"></textarea>
                                                        </div> -->

                                                            <div class="col-md-12 input_quesdetail_eng">
                                                                <div class="ribbon-wrapper card">
                                                                    <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
                                                                    <div class="ribbon-content row">
                                                                      <div class="form-group col-md-6 mule_c1">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 1"; ?>:</label>
                                                                          <textarea name="mule_c1_eng" id="mule_c1_eng" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c2">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 2"; ?>:</label>
                                                                          <textarea name="mule_c2_eng" id="mule_c2_eng" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c3">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 3"; ?>:</label>
                                                                          <textarea name="mule_c3_eng" id="mule_c3_eng" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c4">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 4"; ?>:</label>
                                                                          <textarea name="mule_c4_eng" id="mule_c4_eng" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c5">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 5"; ?>:</label>
                                                                          <textarea name="mule_c5_eng" id="mule_c5_eng" rows="5"></textarea>
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <div class="col-md-12 input_quesdetail_th">
                                                                <div class="ribbon-wrapper card">
                                                                    <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?></div>
                                                                    <div class="ribbon-content row">
                                                                      <div class="form-group col-md-6 mule_c1">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 1"; ?>:</label>
                                                                          <textarea name="mule_c1_th" id="mule_c1_th" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c2">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 2"; ?>:</label>
                                                                          <textarea name="mule_c2_th" id="mule_c2_th" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c3">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 3"; ?>:</label>
                                                                          <textarea name="mule_c3_th" id="mule_c3_th" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c4">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 4"; ?>:</label>
                                                                          <textarea name="mule_c4_th" id="mule_c4_th" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c5">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 5"; ?>:</label>
                                                                          <textarea name="mule_c5_th" id="mule_c5_th" rows="5"></textarea>
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 input_quesdetail_jp">
                                                                <div class="ribbon-wrapper card">
                                                                    <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
                                                                    <div class="ribbon-content row">
                                                                      <div class="form-group col-md-6 mule_c1">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 1"; ?>:</label>
                                                                          <textarea name="mule_c1_jp" id="mule_c1_jp" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c2">
                                                                          <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('choice')." 2"; ?>:</label>
                                                                          <textarea name="mule_c2_jp" id="mule_c2_jp" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c3">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 3"; ?>:</label>
                                                                          <textarea name="mule_c3_jp" id="mule_c3_jp" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c4">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 4"; ?>:</label>
                                                                          <textarea name="mule_c4_jp" id="mule_c4_jp" rows="5"></textarea>
                                                                      </div>
                                                                      <div class="form-group col-md-6 mule_c5">
                                                                          <label class="control-label text-right"><?php echo label('choice')." 5"; ?>:</label>
                                                                          <textarea name="mule_c5_jp" id="mule_c5_jp" rows="5"></textarea>
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <div class="form-group col-md-6">
                                                                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('answer'); ?>:</label>
                                                                <select class="form-control select2" id="mule_answer" name="mule_answer[]" multiple  style="width: 100%;">
                                                                    <option value="mul_c1"><?php echo label('choice')." 1"; ?></option>
                                                                    <option value="mul_c2"><?php echo label('choice')." 2"; ?></option>
                                                                    <option value="mul_c3"><?php echo label('choice')." 3"; ?></option>
                                                                    <option value="mul_c4"><?php echo label('choice')." 4"; ?></option>
                                                                    <option value="mul_c5"><?php echo label('choice')." 5"; ?></option>
                                                                </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                        </div>
                                                    </div>


                                                    <div class="form-group col-md-12" align="right">
                                                      <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                                                      <button type="button" class="btn btn-outline-danger btn-flat" onclick="display_style('div_create_question','div_quiz_question')"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
              </div>
          </div>
              </form>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script><!-- 
    <script src="<?php echo REAL_PATH; ?>/assets/js/userCode.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script> -->
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript">
        $('.slimtest1').perfectScrollbar();
        fetch_data();
        $('.select2').select2();

        $.fn.dataTable.ext.errMode = "none";

        function val_lang(value_chk,value,required_val,lang){
            var strArray = value.split("_");
            if (value_chk=="1") {
              $('.'+value).show();
              if(required_val!=""){
                document.getElementById(required_val+lang).required = true;
              }
            } else {
              $('.'+value).hide();
              if(required_val!=""){
                document.getElementById(required_val+lang).required = false;
              }
            }
        }
        function chkbox_lang(id,value,required_val){
            var remember = document.getElementById(id);
            var strArray = value.split("_");
            if (remember.checked) {
              $('.'+value).show();
              document.getElementById(required_val+strArray[1]).required = true;
              $('#action_main').prop('disabled', false);
            } else {
              $('#action_main').prop('disabled', false);

              var checkedAry= [];
              $.each($("input[name='qize_lang[]']:checked"), function () {
                  checkedAry.push($(this).attr("id"));
              });
              var operation = $('#operation').val();
              if(operation=="Edit"){
                  swal({
                      title: '<?php echo label('noti_lang_uncheck'); ?> ',
                      text: "",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: "#1abc9c",   
                      cancelButtonColor: "#DD6B55",   
                      confirmButtonText: '<?php echo label('m_ok'); ?>',
                      cancelButtonText: '<?php echo label('cancel'); ?>'
                  }).then(function (isChk) {
                    if(isChk.value){
                      $('.'+value).hide();
                      document.getElementById(required_val+strArray[1]).required = false;
                      if(checkedAry.length>0){
                          $('#action_main').prop('disabled', false);
                      }else{
                          $('#action_main').prop('disabled', true);
                      }
                    }else{
                      $('#'+id).prop('checked', true);
                    }
                  })
              }else{
                  $('.'+value).hide();
                  document.getElementById(required_val+strArray[1]).required = false;
                  if(checkedAry.length>0){
                    $('#action_main').prop('disabled', false);
                  }else{
                    $('#action_main').prop('disabled', true);
                  }
              }
              $('.'+value).hide();
              document.getElementById(required_val+strArray[1]).required = false;
            }
        }
        function fetch_data_question(qize_id='',page_num=0)
         {
            $('#myTable_quiz_question').DataTable().destroy();
            var table = $('#myTable_quiz_question').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_quiz_question", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/quiz/fetch_course_question/',
                    data : {
                      qize_id:qize_id,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
         }                
        function display_style(div_name='',div_main=''){
            var x = document.getElementById(div_name);
            var y = document.getElementById(div_main);
            if (x.style.display === 'none') {
                x.style.display = '';
                y.style.display = 'none';
            } else {
                x.style.display = 'none';
                y.style.display = '';

                if(div_name=='div_create_pp'){
                    var id = $('#course_id_pp').val();
                    fetch_data_detail(id);
                }

                if(div_name=='div_create_question'){
                    document.getElementById('div_question_mul').style.display = 'none';
                }

                if(div_name=='div_quiz_detail'){
                    document.getElementById('div_question_check').style.display = 'none';
                }
            }

        }
        $('select[name="quese_type"]').on('change', function(){
          var quese_type = $(this).val();
          $('.mule_c1').hide();
          $('.mule_c2').hide();
          $('.mule_c3').hide();
          $('.mule_c4').hide();
          $('.mule_c5').hide();
          val_lang('0','input_quesdetail_th','','th');
          val_lang('0','input_quesdetail_eng','','eng');
          val_lang('0','input_quesdetail_jp','','jp');
          var qize_id =  $('#qize_id_detail').val();
          document.getElementById("mule_answer").required = false;
          if(quese_type=='multi'){
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                      type: 'POST',
                      data:{qize_id:qize_id},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_quesdetail_th','','th');
                            textarea_tinymce('mule_c1_th');
                            textarea_tinymce('mule_c2_th');
                            textarea_tinymce('mule_c3_th');
                            textarea_tinymce('mule_c4_th');
                            textarea_tinymce('mule_c5_th');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_quesdetail_eng','','eng');
                            textarea_tinymce('mule_c1_eng');
                            textarea_tinymce('mule_c2_eng');
                            textarea_tinymce('mule_c3_eng');
                            textarea_tinymce('mule_c4_eng');
                            textarea_tinymce('mule_c5_eng');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_quesdetail_jp','','jp');
                            textarea_tinymce('mule_c1_jp');
                            textarea_tinymce('mule_c2_jp');
                            textarea_tinymce('mule_c3_jp');
                            textarea_tinymce('mule_c4_jp');
                            textarea_tinymce('mule_c5_jp');
                          }
                        }
                      }
                });
            $('.mule_c1').show();
            $('.mule_c2').show();
            $('.mule_c3').show();
            $('.mule_c4').show();
            $('.mule_c5').show();
            $("#mule_answer").html('');
            $("#mule_answer").append('<option value="mul_c1"><?php echo label('choice')." 1"; ?></option>');
            $("#mule_answer").append('<option value="mul_c2"><?php echo label('choice')." 2"; ?></option>');
            $("#mule_answer").append('<option value="mul_c3"><?php echo label('choice')." 3"; ?></option>');
            $("#mule_answer").append('<option value="mul_c4"><?php echo label('choice')." 4"; ?></option>');
            $("#mule_answer").append('<option value="mul_c5"><?php echo label('choice')." 5"; ?></option>');
                $("#mule_answer").select2({
                    maximumSelectionLength: 5,
                });
            document.getElementById("mule_answer").required = true;
            document.getElementById('div_question_mul').style.display = '';
          }else if(quese_type=='2choice'){
                $.ajax({
                      url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                      type: 'POST',
                      data:{qize_id:qize_id},
                      dataType:"json",
                      success: function(data){
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                          if(data.arr_lang[i]=="th"){
                            val_lang('1','input_quesdetail_th','','th');
                            textarea_tinymce('mule_c1_th');
                            textarea_tinymce('mule_c2_th');
                          }
                          if(data.arr_lang[i]=="eng"){
                            val_lang('1','input_quesdetail_eng','','eng');
                            textarea_tinymce('mule_c1_eng');
                            textarea_tinymce('mule_c2_eng');
                          }
                          if(data.arr_lang[i]=="jp"){
                            val_lang('1','input_quesdetail_jp','','jp');
                            textarea_tinymce('mule_c1_jp');
                            textarea_tinymce('mule_c2_jp');
                          }
                        }
                      }
                });
            $('.mule_c1').show();
            $('.mule_c2').show();
            $("#mule_answer").html('');
            $("#mule_answer").append('<option value="mul_c1"><?php echo label('choice')." 1"; ?></option>');
            $("#mule_answer").append('<option value="mul_c2"><?php echo label('choice')." 2"; ?></option>');
                $("#mule_answer").select2({
                    maximumSelectionLength: 1,
                });
            document.getElementById("mule_answer").required = true;
            document.getElementById('div_question_mul').style.display = '';
          }else{
            document.getElementById('div_question_mul').style.display = 'none';
          }
        });

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("create_quiz_ex"); ?>');
                $('#quiz_ex_form')[0].reset();
                $('#operation').val("Add");
                $('.input_th').hide();
                $('.input_eng').hide();
                $('.input_jp').hide();
                document.getElementById("qize_name_th").required = false;
                document.getElementById("qize_name_eng").required = false;
                document.getElementById("qize_name_jp").required = false;
                $("input[name='qize_lang[]']:checked").each(function ()
                {
                  if($(this).val()=="th"){
                    chkbox_lang('qize_lang_th','input_th','qize_name_');
                  }else if($(this).val()=="eng"){
                    chkbox_lang('qize_lang_eng','input_eng','qize_name_');
                  }else if($(this).val()=="jp"){
                    chkbox_lang('qize_lang_jp','input_jp','qize_name_');
                  }
                });
                  <?php if($com_admin=="com_associated"){ ?>
                    var com_id = '<?php echo $com_id; ?>';
                  <?php }else{ ?>

                  var com_id = $('#com_id_search').val();
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:com_id},
                        success: function(data_company){
                            $('#com_id').html(data_company);
                            //$('#com_id').val($('#com_id option:first-child').val()).trigger('change');
                        }
                  });
                  <?php } ?>
            });

           $('#add_question').click(function(){
                $('#question_form')[0].reset();
                $('#operation_question').val("Add");
                $("#modal-question").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("create_quiz_ex"); ?>');
                document.getElementById('div_quiz_question').style.display = 'none';
                document.getElementById('div_create_question').style.display = '';

                var qize_id = $('#qize_id_question').val();
                document.getElementById("quese_name_th").required = false;
                document.getElementById("quese_name_eng").required = false;
                document.getElementById("quese_name_jp").required = false;
                document.getElementById("mule_answer").required = false;
                val_lang('0','input_ques_th','','th');
                val_lang('0','input_ques_eng','','eng');
                val_lang('0','input_ques_jp','','jp');

                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                              type: 'POST',
                              data:{qize_id:qize_id},
                              dataType:"json",
                              success: function(data_lang){
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    val_lang('1','input_ques_th','','th');
                                    textarea_tinymce('quese_name_th');
                                    textarea_tinymce('quese_info_th');
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    val_lang('1','input_ques_eng','','eng');
                                    textarea_tinymce('quese_name_eng');
                                    textarea_tinymce('quese_info_eng');
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    val_lang('1','input_ques_jp','','jp');
                                    textarea_tinymce('quese_name_jp');
                                    textarea_tinymce('quese_info_jp');
                                  }
                                }
                              }
                        });
            });

        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data(0);
        });
            
         function fetch_data(page_num=0)
         {
            var com_id = $('#com_id_search').val();
            $('#myTable').DataTable().destroy();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/quiz/fetch_detail_quizex/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "num" },
                    { data: "qize_lang" },
                    { data: "qize_name" },
                    { data: "com_name" },
                    { data: {
                        _:    "qize_modifieddate.display",
                        sort: "qize_modifieddate.timestamp"
                    } },
                ],
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
         }

            $(document).on('submit', '#quiz_ex_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/quiz/insert_quiz_ex",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#quiz_ex_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          fetch_data(0);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("data_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                   
                  }
                });
            });

         $(document).on('click', '.delete_quiz', function(){
            var qize_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_data_qiz_exp",
                    method:"POST",
                    data:{qize_id:qize_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label('wg_msg_use'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

        $(document).on('click', '.update_quiz', function(){
            var qize_id = $(this).attr("id");
            $('.modal-title').text('<?php echo label("edit_quiz_ex"); ?>');
            $('#operation').val("Edit");
            $('#qize_id').val(qize_id);
                $('.input_th').hide();
                $('.input_eng').hide();
                $('.input_jp').hide();
                document.getElementById("qize_name_th").required = false;
                document.getElementById("qize_name_eng").required = false;
                document.getElementById("qize_name_jp").required = false;
            $.ajax({
                  url:"<?=base_url()?>index.php/quiz/update_quizex_detail_data",
                  method:"POST",
                  data:{qize_id_update:qize_id},
                  dataType:"json",
                  success:function(data)
                  {

                        $("#modal-default").modal({backdrop: false});
                        <?php if($com_admin!="com_associated"){ ?>
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/recheckcompany',
                              type: 'POST',
                              data:{com_id:data.com_id},
                              success: function(data_company){
                                  $('#com_id').html(data_company);
                                  $('#com_id').val(data.com_id).trigger('change');
                              }
                        });
                        <?php }else{ ?>
                        $('#com_id').val(data.com_id);   
                        <?php } ?>

                        if(data.isTH=="1"){
                            document.getElementById("qize_lang_th").checked = true;
                            chkbox_lang('qize_lang_th','input_th','qize_name_');
                            $('#qize_name_th').val(data.qize_name_th);
                            $('#qize_info_th').val(data.qize_info_th);
                        }else{
                            document.getElementById("qize_lang_th").checked = false;
                            $('.input_th').hide();
                        }
                        if(data.isENG=="1"){
                            document.getElementById("qize_lang_eng").checked = true;
                            chkbox_lang('qize_lang_eng','input_eng','qize_name_');
                            $('#qize_name_eng').val(data.qize_name_eng);
                            $('#qize_info_eng').val(data.qize_info_eng);
                        }else{
                            document.getElementById("qize_lang_eng").checked = false;
                            $('.input_eng').hide();
                        }
                        if(data.isJP=="1"){
                            document.getElementById("qize_lang_jp").checked = true;
                            chkbox_lang('qize_lang_jp','input_jp','qize_name_');
                            $('#qize_name_jp').val(data.qize_name_jp);
                            $('#qize_info_jp').val(data.qize_info_jp);
                        }else{
                            document.getElementById("qize_lang_jp").checked = false;
                            $('.input_jp').hide();
                        }
                  }
            });
        });


        function textarea_tinymce(id=''){
            if ($("#"+id).length > 0) {
                if (tinymce.get(id)) {
                    tinymce.get(id).remove();
                }
                tinymce.init({
                    selector: "textarea#"+id,
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                        "save table contextmenu directionality paste textcolor"
                    ],
                    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor ",
                    media_live_embeds: true,
                    extended_valid_elements: "video[controls|width|height|src|poster|preload|class|style],source[src|type]",
                    file_picker_types: "image media",
                    file_picker_callback: function(callback, value, meta) {
                      var input = document.createElement('input');
                      input.setAttribute('type', 'file');
                      input.setAttribute('accept', meta.filetype === 'image' ? 'image/*' : 'video/*');

                      input.onchange = function() {
                        var file = this.files[0];
                        var xhr = new XMLHttpRequest();
                        var formData = new FormData();

                        xhr.withCredentials = false;
                        xhr.open('POST', '<?=base_url()?>index.php/setting/upload_img_texteditor');
                        xhr.onload = function() {
                          var json;

                          if (xhr.status != 200) {
                            alert(xhr.status == 400 ? 'Please select a valid image or video file with an English filename' : 'HTTP Error: ' + xhr.status);
                            return;
                          }

                          json = JSON.parse(xhr.responseText);
                          if (!json || typeof json.file_path != 'string') {
                            alert('Invalid JSON: ' + xhr.responseText);
                            return;
                          }

                          callback(json.file_path, { title: file.name, alt: file.name });
                        };

                        formData.append('file', file, file.name);
                        xhr.send(formData);
                      };

                      input.click();
                    },

                    images_upload_url : '<?=base_url()?>index.php/setting/upload_img_texteditor',
                    automatic_uploads : false,

                    images_upload_handler : function(blobInfo, success, failure) {
                      var xhr, formData;

                      xhr = new XMLHttpRequest();
                      xhr.withCredentials = false;
                      xhr.open('POST', '<?=base_url()?>index.php/setting/upload_img_texteditor');

                      xhr.onload = function() {
                        var json;

                        if (xhr.status != 200) {
                          if(xhr.status==400){
                            failure('Please use English filename');
                          }else{
                            failure('HTTP Error: ' + xhr.status);
                          }
                          return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.file_path != 'string') {
                          failure('Invalid JSON: ' + xhr.responseText);
                          return;
                        }

                        success(json.file_path);
                      };

                      formData = new FormData();
                      formData.append('file', blobInfo.blob(), blobInfo.filename());

                      xhr.send(formData);
                    },
                });
            }
        }
        $(document).on('click', '.quiz_detail', function(){
            textarea_tinymce('quese_name_th');
            textarea_tinymce('quese_name_eng');
            textarea_tinymce('quese_name_jp');
            textarea_tinymce('quese_info_th');
            textarea_tinymce('quese_info_eng');
            textarea_tinymce('quese_info_jp');
            var qize_id = $(this).attr("id");
            $("#modal-question").modal({backdrop: false});
            $('#qize_id_detail').val(qize_id);
            $('#qize_id_question').val(qize_id);
            $('#div_create_question').hide();
            $('#div_quiz_question').show();

            var table = $('#myTable_quiz_question').DataTable();
            var info = table.page.info();
            var length = info.pages;
            var page_current = info.page;
            fetch_data_question(qize_id,page_current);
        });
        $(document).ready(function() {
            $('.select2').select2();
        });

        $(document).on('submit', '#question_form', function(event){
              event.preventDefault(); 
              tinymce.triggerSave();
              var qize_id = $('#qize_id_question').val();
              var rechk_val = 1;
              var form = $('#question_form')[0];
              var quese_type = $('#quese_type').val();
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                              type: 'POST',
                              data:{qize_id:qize_id},
                              dataType:"json",
                              success: function(data_lang){
                                var rechk_null = 0;
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    var quese_name_th = $('#quese_name_th').val();
                                    tinymce.get('quese_name_th').focus();
                                    if(quese_name_th==""){
                                        rechk_null++;
                                    }
                                    if(quese_type=="2choice"||quese_type=="multi"){
                                      var mule_c1_th = $('#mule_c1_th').val();
                                      var mule_c2_th = $('#mule_c2_th').val();
                                      if(mule_c1_th==""){
                                          rechk_null++;
                                      }
                                      if(mule_c2_th==""){
                                          rechk_null++;
                                      }
                                    }
                                    if(quese_type=="multi"){
                                      $("#mule_answer option:selected").each(function () {
                                         var $this = $(this);
                                         if ($this.length) {
                                          var selText = $this.val();
                                          var mul_val = $('#'+selText.replace("_", "e_")+'_th').val();
                                          if(mul_val==""){
                                            rechk_null++;
                                          }
                                         }
                                      });
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    var quese_name_eng = $('#quese_name_eng').val();
                                    tinymce.get('quese_name_eng').focus();
                                    if(quese_name_eng==""){
                                        rechk_null++;
                                    }
                                    if(quese_type=="2choice"||quese_type=="multi"){
                                      var mule_c1_eng = $('#mule_c1_eng').val();
                                      var mule_c2_eng = $('#mule_c2_eng').val();
                                      if(mule_c1_eng==""){
                                          rechk_null++;
                                      }
                                      if(mule_c2_eng==""){
                                          rechk_null++;
                                      }
                                    }
                                    if(quese_type=="multi"){
                                      $("#mule_answer option:selected").each(function () {
                                         var $this = $(this);
                                         if ($this.length) {
                                          var selText = $this.val();
                                          var mul_val = $('#'+selText.replace("_", "e_")+'_eng').val();
                                          if(mul_val==""){
                                            rechk_null++;
                                          }
                                         }
                                      });
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    var quese_name_jp = $('#quese_name_jp').val();
                                    tinymce.get('quese_name_jp').focus();
                                    if(quese_name_jp==""){
                                        rechk_null++;
                                    }
                                    if(quese_type=="2choice"||quese_type=="multi"){
                                      var mule_c1_jp = $('#mule_c1_jp').val();
                                      var mule_c2_jp = $('#mule_c2_jp').val();
                                      if(mule_c1_jp==""){
                                          rechk_null++;
                                      }
                                      if(mule_c2_jp==""){
                                          rechk_null++;
                                      }
                                    }
                                    if(quese_type=="multi"){
                                      $("#mule_answer option:selected").each(function () {
                                         var $this = $(this);
                                         if ($this.length) {
                                          var selText = $this.val();
                                          var mul_val = $('#'+selText.replace("_", "e_")+'_jp').val();
                                          if(mul_val==""){
                                            rechk_null++;
                                          }
                                         }
                                      });
                                    }
                                  }
                                }
                                if(rechk_null>0){
                                    rechk_val = 0;
                                }
                                if(rechk_val==1){
                                      $.ajax({
                                        url:"<?=base_url()?>index.php/quiz/insert_question",
                                        method:'POST',
                                        data:new FormData(form),
                                        contentType:false,
                                        processData:false,
                                        success:function(data)
                                        {

                                          if(data=="2"){
                                              swal(
                                                  '<?php echo label("com_msg_success"); ?>!',
                                                  '',
                                                  'success'
                                              ).then(function () {
                                                  $('#question_form')[0].reset();
                                                  display_style('div_create_question','div_quiz_question');
                                                  $('#qize_id_question').val(qize_id);
                                                  fetch_data_question(qize_id,0);
                                              })
                                          }else if(data=="1"){
                                              swal({
                                                  title: '<?php echo label("course_msg_duplicate"); ?>',
                                                  text: "",
                                                  type: 'warning',
                                                  showCancelButton: false,
                                                  confirmButtonClass: 'btn btn-primary',
                                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                                              }).then(function () {
                                                  $('#question_form')[0].reset();
                                              })
                                          }else{
                                              swal({
                                                  title: '<?php echo label("com_msg_error_save"); ?>',
                                                  text: "",
                                                  type: 'warning',
                                                  showCancelButton: false,
                                                  confirmButtonClass: 'btn btn-primary',
                                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                                              })
                                          }
                                         
                                        }
                                      });
                                }else{
                                            swal({
                                                title: '<?php echo label("com_msg_form_error"); ?>',
                                                text: "",
                                                type: 'warning',
                                                showCancelButton: false,
                                                confirmButtonClass: 'btn btn-primary',
                                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                                            }).then(function () {
                                                //topFunction();
                                            })
                                }
                              }
                        });
        });

        $(document).on('click', '.update_ques', function(){
            var quese_id = $(this).attr("id");

            $('#operation_question').val("Edit");
            $('#quese_id').val(quese_id);

                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckmule_answer',
                                      type: 'POST',
                                      data:{quese_id:''},
                                      success: function(answer){
                                        console.log(answer);
                                        $('#mul_answer').html(answer);
                                      }
                                });
            var qize_id = $('#qize_id_question').val();

            document.getElementById("quese_name_th").required = false;
            document.getElementById("quese_name_eng").required = false;
            document.getElementById("quese_name_jp").required = false;
            document.getElementById("mule_answer").required = false;
            val_lang('0','input_ques_th','','th');
            val_lang('0','input_ques_eng','','eng');
            val_lang('0','input_ques_jp','','jp');
            val_lang('0','input_quesdetail_th','','th');
            val_lang('0','input_quesdetail_eng','','eng');
            val_lang('0','input_quesdetail_jp','','jp');
            $.ajax({
                  url:"<?=base_url()?>index.php/quiz/update_question_detail_data",
                  method:"POST",
                  data:{quese_id_update:quese_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('#quese_type').val(data.quese_type);
                        $('#quese_score').val(parseInt(data.quese_score));

                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                              type: 'POST',
                              data:{qize_id:data.qize_id},
                              dataType:"json",
                              success: function(data_lang){
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    val_lang('1','input_ques_th','','th');
                                    $('#quese_name_th').val(data.quese_name_th);
                                    $('#quese_info_th').val(data.quese_info_th);
                                    textarea_tinymce('quese_name_th');
                                    textarea_tinymce('quese_info_th');
                                    $(tinymce.get('quese_name_th').getBody()).html(data.quese_name_th);
                                    $(tinymce.get('quese_info_th').getBody()).html(data.quese_info_th);
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    val_lang('1','input_ques_eng','','eng');
                                    $('#quese_name_eng').val(data.quese_name_eng);
                                    $('#quese_info_eng').val(data.quese_info_eng);
                                    textarea_tinymce('quese_name_eng');
                                    textarea_tinymce('quese_info_eng');
                                    $(tinymce.get('quese_name_eng').getBody()).html(data.quese_name_eng);
                                    $(tinymce.get('quese_info_eng').getBody()).html(data.quese_info_eng);
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    val_lang('1','input_ques_jp','','jp');
                                    $('#quese_name_jp').val(data.quese_name_jp);
                                    $('#quese_info_jp').val(data.quese_info_jp);
                                    textarea_tinymce('quese_name_jp');
                                    textarea_tinymce('quese_info_jp');
                                    $(tinymce.get('quese_name_jp').getBody()).html(data.quese_name_jp);
                                    $(tinymce.get('quese_info_jp').getBody()).html(data.quese_info_jp);
                                  }
                                }
                              }
                        });

                        if(data.quese_show=="0"){
                            document.getElementById("quese_show").checked = false;
                        }else{
                            document.getElementById("quese_show").checked = true;
                        }
                        $('.mule_c1').hide();
                        $('.mule_c2').hide();
                        $('.mule_c3').hide();
                        $('.mule_c4').hide();
                        $('.mule_c5').hide();
                        if(data.quese_type=='multi'){

                            document.getElementById("mule_answer").required = true;
                            $.ajax({
                                  url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                                  type: 'POST',
                                  data:{qize_id:data.qize_id},
                                  dataType:"json",
                                  success: function(data_lang){
                                    for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                      if(data_lang.arr_lang[i]=="th"){
                                        val_lang('1','input_quesdetail_th','','th');
                                        textarea_tinymce('mule_c1_th');
                                        textarea_tinymce('mule_c2_th');
                                        textarea_tinymce('mule_c3_th');
                                        textarea_tinymce('mule_c4_th');
                                        textarea_tinymce('mule_c5_th');
                                        $(tinymce.get('mule_c1_th').getBody()).html(data.multi['mule_c1_th']);
                                        $(tinymce.get('mule_c2_th').getBody()).html(data.multi['mule_c2_th']);
                                        $(tinymce.get('mule_c3_th').getBody()).html(data.multi['mule_c3_th']);
                                        $(tinymce.get('mule_c4_th').getBody()).html(data.multi['mule_c4_th']);
                                        $(tinymce.get('mule_c5_th').getBody()).html(data.multi['mule_c5_th']);
                                      }
                                      if(data_lang.arr_lang[i]=="eng"){
                                        val_lang('1','input_quesdetail_eng','','eng');
                                        textarea_tinymce('mule_c1_eng');
                                        textarea_tinymce('mule_c2_eng');
                                        textarea_tinymce('mule_c3_eng');
                                        textarea_tinymce('mule_c4_eng');
                                        textarea_tinymce('mule_c5_eng');
                                        $(tinymce.get('mule_c1_eng').getBody()).html(data.multi['mule_c1_eng']);
                                        $(tinymce.get('mule_c2_eng').getBody()).html(data.multi['mule_c2_eng']);
                                        $(tinymce.get('mule_c3_eng').getBody()).html(data.multi['mule_c3_eng']);
                                        $(tinymce.get('mule_c4_eng').getBody()).html(data.multi['mule_c4_eng']);
                                        $(tinymce.get('mule_c5_eng').getBody()).html(data.multi['mule_c5_eng']);
                                      }
                                      if(data_lang.arr_lang[i]=="jp"){
                                        val_lang('1','input_quesdetail_jp','','jp');
                                        textarea_tinymce('mule_c1_jp');
                                        textarea_tinymce('mule_c2_jp');
                                        textarea_tinymce('mule_c3_jp');
                                        textarea_tinymce('mule_c4_jp');
                                        textarea_tinymce('mule_c5_jp');
                                        $(tinymce.get('mule_c1_jp').getBody()).html(data.multi['mule_c1_jp']);
                                        $(tinymce.get('mule_c2_jp').getBody()).html(data.multi['mule_c2_jp']);
                                        $(tinymce.get('mule_c3_jp').getBody()).html(data.multi['mule_c3_jp']);
                                        $(tinymce.get('mule_c4_jp').getBody()).html(data.multi['mule_c4_jp']);
                                        $(tinymce.get('mule_c5_jp').getBody()).html(data.multi['mule_c5_jp']);
                                      }
                                    }
                                  }
                            });
                            $('.mule_c1').show();
                            $('.mule_c2').show();
                            $('.mule_c3').show();
                            $('.mule_c4').show();
                            $('.mule_c5').show();
                            document.getElementById('div_question_mul').style.display = '';
                            var myarr = data.multi['mule_answer'];
                            if(myarr!=""){
                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckmule_answer',
                                      type: 'POST',
                                      data:{quese_id:quese_id,type:'multi'},
                                      success: function(answer){
                                        $('#mule_answer').html(answer);
                                      }
                                });
                            }
                            $("#mule_answer").select2({
                                maximumSelectionLength: 5,
                            });
                        }else if(data.quese_type=='2choice'){
                            document.getElementById("mule_answer").required = true;
                            $.ajax({
                                  url: '<?=base_url()?>index.php/querydata/select_lang_qizex',
                                  type: 'POST',
                                  data:{qize_id:data.qize_id},
                                  dataType:"json",
                                  success: function(data_lang){
                                    for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                      if(data_lang.arr_lang[i]=="th"){
                                        val_lang('1','input_quesdetail_th','','th');
                                        textarea_tinymce('mule_c1_th');
                                        textarea_tinymce('mule_c2_th');
                                        $(tinymce.get('mule_c1_th').getBody()).html(data.multi['mule_c1_th']);
                                        $(tinymce.get('mule_c2_th').getBody()).html(data.multi['mule_c2_th']);
                                      }
                                      if(data_lang.arr_lang[i]=="eng"){
                                        val_lang('1','input_quesdetail_eng','','eng');
                                        textarea_tinymce('mule_c1_eng');
                                        textarea_tinymce('mule_c2_eng');
                                        $(tinymce.get('mule_c1_eng').getBody()).html(data.multi['mule_c1_eng']);
                                        $(tinymce.get('mule_c2_eng').getBody()).html(data.multi['mule_c2_eng']);
                                      }
                                      if(data_lang.arr_lang[i]=="jp"){
                                        val_lang('1','input_quesdetail_jp','','jp');
                                        textarea_tinymce('mule_c1_jp');
                                        textarea_tinymce('mule_c2_jp');
                                        $(tinymce.get('mule_c1_jp').getBody()).html(data.multi['mule_c1_jp']);
                                        $(tinymce.get('mule_c2_jp').getBody()).html(data.multi['mule_c2_jp']);
                                      }
                                    }
                                  }
                            });
                            $('.mule_c1').show();
                            $('.mule_c2').show();
                            document.getElementById('div_question_mul').style.display = '';
                            var myarr = data.multi['mule_answer'];
                            if(myarr!=""){
                                $.ajax({
                                      url: '<?=base_url()?>index.php/workgroup/recheckmule_answer',
                                      type: 'POST',
                                      data:{quese_id:quese_id,type:'2choice'},
                                      success: function(answer){
                                        $('#mule_answer').html(answer);
                                      }
                                });
                            }
                            $("#mule_answer").select2({
                                maximumSelectionLength: 1,
                            });
                        }else{
                            document.getElementById('div_question_mul').style.display = 'none';
                        }
                  }
            });
            display_style('div_create_question','div_quiz_question');
        });

         $(document).on('click', '.delete_ques', function(){
            var quese_id = $(this).attr("id");
            var qize_id = $('#qize_id_question').val();
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label("m_cancel"); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_data_qiz_exp_question",
                    method:"POST",
                    data:{quese_id:quese_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                            var table = $('#myTable_quiz_question').DataTable();
                            var info = table.page.info();
                            var length = info.pages;
                            var page_current = info.page;
                            fetch_data_question(qize_id,page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });
    </script>
</body>

</html>
