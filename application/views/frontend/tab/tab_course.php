<style>
.autocomplete-wrap { position: relative; }
.autocomplete-list {
  position: absolute; left:0; right:0; top:100%;
  z-index: 1000; background:#fff; border:1px solid #ddd; border-radius: .25rem;
  max-height: 240px; overflow:auto; margin-top:2px; display:none;
}
.autocomplete-item { padding:.5rem .75rem; cursor:pointer; }
.autocomplete-item.is-active, .autocomplete-item:hover { background:#f5f5f5; }
</style>
<div class="tab-pane p-20 card active" id="period_and_permission" role="tabpanel">
  <div class="card-body row">
    <div id="div_create_pp">
      <form enctype="multipart/form-data" id="period_and_permission_form" name="period_and_permission_form"
        autocomplete="off" method="POST" accept-charset="utf-8" class="form-horizontal p-t-20" novalidate>
        <input type="hidden" id="cosde_id" name="cosde_id">
        <input type="hidden" id="operation_pp" name="operation_pp" value="Add">
        <input type="hidden" id="course_id_pp" name="course_id_pp">
        <div class="col-md-12 row">
          <!-- <div class="col-md-12">
                        <button type="button" class="btn btn-outline-danger float-right" onclick="display_style('div_create_pp','div_pp')"><i class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
                        <h3 id="txthead_period"></h3><hr>
                      </div> -->
          <div class="form-group col-md-12">
            <label class="control-label text-right"><?php echo label('period'); ?>: </label>
            <div class="row">
              <div class="col-md-6">
                <label class="control-label text-right"><?php echo label('course_start_date'); ?></label>
                <div class="row">
                  <div class="col-md-8">
                    <input type="text" id="date_start" name="date_start" onchange="caldate('date_start')"
                      class="form-control date_start">
                    <input type="hidden" id="date_start_var" name="date_start_var">
                  </div>
                  <div class="col-md-4">
                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                      data-autoclose="true">
                      <input type="text" id="time_start" name="time_start" class="form-control"
                        value="<?php echo date('H:i', strtotime('00:00')); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label class="control-label text-right"><?php echo label('course_end_date_optional'); ?></label>
                <div class="row">
                  <div class="col-md-8">
                    <input type="text" id="date_end" name="date_end" onchange="caldate('date_end')"
                      class="form-control date_end">
                    <input type="hidden" id="date_end_var" name="date_end_var">
                  </div>
                  <div class="col-md-4">
                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                      data-autoclose="true">
                      <input type="text" id="time_end" name="time_end" class="form-control"
                        value="<?php echo date('H:i', strtotime('23:59')); ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--<div class="input-group">
                              <input type="text" class="form-control" required name="date_start" id="date_start" />
                              <div class="input-group-append">
                                  <span class="input-group-text bg-info b-0 text-white"><?php echo label('to'); ?></span>
                              </div>
                              <input type="text" class="form-control" required name="date_end" id="date_end" />
                          </div>-->
          </div>
          <!-- <div class="col-md-12">
                        <div class="form-group">
                          <label for="cosde_status"><b style="color: #FF2D00">*</b><?php echo label('course_status'); ?>:</label>
                          <div class="switch">
                              <label><?php echo label('close'); ?><input type="checkbox"  id="cosde_status" checked name="cosde_status" value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                          </div>
                        </div>
                      </div> -->
          <input type="hidden" id="cosde_status" name="cosde_status" value="1">
          <div class="form-group col-md-12 row" style="display: none;">
            <div class="form-group col-md-6">
              <label class="control-label text-right"><?php echo label('point_redeem'); ?></label>
              <input name="point_redeem" type="number" min="0" max="100" step="0.01" pattern="[0123456789.]"
                class="form-control" id="point_redeem">
            </div>
            <div class="form-group col-md-6">
              <label class="control-label text-right"><?php echo label('get_point'); ?></label>
              <input name="get_point" type="number" min="0" max="100" step="0.01" pattern="[0123456789.]"
                class="form-control" id="get_point">
            </div>
          </div>
          <div class="form-group col-md-12">
            <hr>
            <label class="control-label text-right"><?php echo label('permission'); ?>:</label>
            <hr>
            <div id="permission_div">
            </div><br>
          </div>

          <!-- <div class="col-md-12 progress" id="progress_div" style="display: none;">
                          <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress"></span></div>
                      </div> -->
          <div class="form-group col-md-12" align="right">
            <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat"
              onclick="window.location.href='<?php echo base_url() . "managecourse/courses_all"; ?>'"><i
                class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="tab-pane p-20 card" id="document" role="tabpanel">
  <div class="card-body row">
    <div class="col-md-12 row" id="cos_document">
      <input type="hidden" id="fil_lang" name="fil_lang">
      <div class="col-md-6 input_doccos_th" style="display:none;">
        <div class="ribbon-wrapper card">
          <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?>
          </div>
          <div class="ribbon-content row">
            <div class="form-group col-md-12">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('file_name'); ?>:</label>
              <input name="name_file_th" type="text" class="form-control" id="name_file_th" autocomplete="off">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 input_doccos_eng" style="display:none;">
        <div class="ribbon-wrapper card">
          <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?></div>
          <div class="ribbon-content row">
            <div class="form-group col-md-12">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('file_name'); ?>:</label>
              <input name="name_file_eng" type="text" class="form-control" id="name_file_eng" autocomplete="off">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 input_doccos_jp" style="display:none;">
        <div class="ribbon-wrapper card">
          <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?></div>
          <div class="ribbon-content row">
            <div class="form-group col-md-12">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('file_name'); ?>:</label>
              <input name="name_file_jp" type="text" class="form-control" id="name_file_jp" autocomplete="off">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group col-md-12" id="cos_filedocument">
        <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('file_document_cos'); ?>:</label>
        <input type="file" name="path_file" required id="path_file" class="dropify"
          accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.jpg,.jpeg,.png,.pdf" />
        <input type="hidden" id="fil_cos_id" name="fil_cos_id">
      </div>
      <div class="col-md-12 progress" id="progress_filedocument_div" style="display: none;">
        <div class="progress-bar-filedocument bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
          aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_filedocument"></span>
        </div>
      </div>
      <div class="col-md-12" align="right">
        <button type="button" onclick="onAddFileDocCos()" class="btn btn-outline-success btn-flat pull-right"
          name="add_file" id="add_file"><i class="mdi mdi-note-plus"></i>
          <?php echo ucwords(label('ceCertBrowse')); ?></button>
        <button type="button" onclick="clearDocumentCOS()" class="btn btn-outline-danger btn-flat pull-right"><i
            class="mdi mdi-window-close"></i> <?php echo label('clear_data'); ?></button>
        <hr>
      </div>
      <input type="hidden" id="operation_doccos" name="operation_doccos" value="Add">
    </div>
    <div class="col-md-12 row" align="">
      <div class="col-md-12 table-responsive" id="tb_cos_document">
        <table id="myTable_cos_document" width="100%" class="table table-bordered table-striped">
          <thead>
            <tr>
              <?php if ($btn_update == "1" || $btn_delete == "1") { ?>
              <th width="20%">
                <center><?php echo label('manage'); ?></center>
              </th>
              <?php } ?>
              <th width="10%"></th>
              <th width="35%">
                <center><?php echo label('file_name'); ?></center>
              </th>
              <th width="20%">
                <center><?php echo label('faqlang'); ?></center>
              </th>
              <th width="15%">
                <center><?php echo label('dateMod'); ?></center>
              </th>
            </tr>
          </thead>
        </table>
      </div>
      <p><?php echo label('preNote'); ?>: <?php if ($btn_update == "1") { ?><button type="button"
          class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
        <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_delete == "1") { ?> , <button type="button"
          class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
        <b><?php echo label('delete'); ?></b><?php } ?>
      </p>
    </div>
  </div>
</div>

<div class="tab-pane p-20 card" id="lesson" role="tabpanel">
  <div class="card-body row">

    <div id="div_order_lesson" class="col-md-12" style="display: none;">

      <div class="col-md-12">
        <button type="button" class="btn btn-outline-danger float-right"
          onclick="display_style('div_order_lesson','div_lesson')"><i class="mdi mdi-keyboard-return"></i>
          <?php echo label('m_previous'); ?></button>
        <h3 id="txthead_sortlesson"></h3>
        <hr>
      </div>
      <form enctype="multipart/form-data" id="lesson_order_form" name="lesson_order_form" autocomplete="off"
        method="POST" accept-charset="utf-8" class="form-horizontal p-t-20">
        <input type="hidden" id="les_id_order" name="les_id_order">
        <input type="hidden" id="operation_lesson_order" name="operation_lesson_order" value="Add">
      </form>
      <div class="card">
        <div class="card-body">
          <!-- <div class="form-group col-md-6">
                        <label class="control-label text-right"><?php echo label('faqlang'); ?></label>
                        <select id="les_lang_sort" name="les_lang_sort" onchange="reload_sortlesson($('#course_id_lesson').val(),this.value)" class="form-control"></select>
                    </div> -->
          <div class="myadmin-dd dd" id="nestable">
            <ol class="dd-list" id="load_li_lesson" style="width: 100%;font-size: 20px">
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div id="div_create_lesson" class="col-md-12" style="display: none;">
      <form enctype="multipart/form-data" id="lesson_form" name="lesson_form" autocomplete="off" method="POST"
        accept-charset="utf-8" class="form-horizontal p-t-20">
        <input type="hidden" id="les_id" name="les_id">
        <input type="hidden" id="operation_lesson" name="operation_lesson" value="Add">
        <input type="hidden" id="course_id_lesson" name="course_id_lesson">
        <div class="col-md-12 row">
          <div class="col-md-12">
            <button type="button" class="btn btn-outline-danger float-right"
              onclick="display_style('div_create_lesson','div_lesson')"><i class="mdi mdi-keyboard-return"></i>
              <?php echo label('m_previous'); ?></button>
            <h3 id="txthead_lesson"></h3>
            <hr>
          </div>
          <input type="hidden" id="les_lang" name="les_lang">

          <div class="col-md-12 input_les_th" style="display:none;">
            <div class="ribbon-wrapper card">
              <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i> <?php echo label('thailand'); ?>
              </div>
              <div class="ribbon-content row">
                <div class="form-group col-md-6">
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                  <input name="les_name_th" type="text" class="form-control" id="les_name_th">
                </div>
                <div class="form-group col-md-12">
                  <label class="control-label"><?php echo label('lesson_summary'); ?>:</label>
                  <textarea name="les_info_th" id="les_info_th" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 input_les_eng" style="display:none;">
            <div class="ribbon-wrapper card">
              <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
              </div>
              <div class="ribbon-content row">
                <div class="form-group col-md-6">
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                  <input name="les_name_eng" type="text" class="form-control" id="les_name_eng">
                </div>
                <div class="form-group col-md-12">
                  <label class="control-label"><?php echo label('lesson_summary'); ?>:</label>
                  <textarea name="les_info_eng" id="les_info_eng" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 input_les_jp" style="display:none;">
            <div class="ribbon-wrapper card">
              <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
              </div>
              <div class="ribbon-content row">
                <div class="form-group col-md-6 input_jp">
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('lName'); ?>:</label>
                  <input name="les_name_jp" type="text" class="form-control" id="les_name_jp">
                </div>
                <div class="form-group col-md-12 input_jp">
                  <label class="control-label"><?php echo label('lesson_summary'); ?>:</label>
                  <textarea name="les_info_jp" id="les_info_jp" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group col-md-12">
            <label class="control-label text-right"><?php echo label('period_les'); ?>: </label>
            <div class="row">
              <div class="col-md-6">
                <label class="control-label text-right"><?php echo label('r_start_on'); ?></label>
                <div class="row">
                  <div class="col-md-8">
                    <input type="text" id="date_start_les" name="date_start_les" onchange="caldate('date_start_les')"
                      class="form-control date_start_les">
                    <input type="hidden" id="date_start_les_var" name="date_start_les_var">
                  </div>
                  <div class="col-md-4">
                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                      data-autoclose="true">
                      <input type="text" id="time_start_les" name="time_start_les" class="form-control"
                        value="<?php echo date('H:i', strtotime('00:00')); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label class="control-label text-right"><?php echo label('r_finish_on'); ?></label>
                <div class="row">
                  <div class="col-md-8">
                    <input type="text" id="date_end_les" name="date_end_les" onchange="caldate('date_end_les')"
                      class="form-control date_end_les">
                    <input type="hidden" id="date_end_les_var" name="date_end_les_var">
                  </div>
                  <div class="col-md-4">
                    <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                      data-autoclose="true">
                      <input type="text" id="time_end_les" name="time_end_les" class="form-control"
                        value="<?php echo date('H:i', strtotime('23:59')); ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="status_les"><b style="color: #FF2D00">*</b><?php echo label('less_visible'); ?>:</label>
              <div class="switch">
                <label><?php echo label('svhid2'); ?><input type="checkbox" id="status_les" name="status_les" checked
                    value="1"><span class="lever switch-col-indigo"></span><?php echo label('svhid1'); ?></label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="les_type"><b style="color: #FF2D00">*</b><?php echo label('qr_typefile'); ?>:</label>
              <div class="switch">
                <label><?php echo "Media"; ?><input type="checkbox" onclick="changeValEnableDivMedia()" id="les_type"
                    name="les_type" value="2"><span
                    class="lever switch-col-indigo"></span><?php echo "Scorm"; ?></label>
              </div>
            </div>
          </div>

          <div class="col-md-12 row" id="div_media">
            <div class="form-group col-md-12" style="margin: 0px auto 10px auto;">
              <!-- <h5 align="left"><?php echo label('Les_video'); ?></h5> -->
              <label for="status_cr"><?php echo label('media_type'); ?>:</label>
              <select class="form-control" id="type_media" name="type_media" style="width: 100%;" required>
                <option value="0" selected><?php echo label('none'); ?></option>
                <option value="1"><?php echo "URL"; ?></option>
                <option value="2"><?php echo "Upload File"; ?></option>
              </select>
              <div class="" id="div_multifile_url" style="display: none;">
                <textarea class="form-control" name="url_media" id="url_media" rows="5" style="width: 100%"></textarea>
                <label class="control-label text-right"><?php echo label('les_url_msg'); ?></label>
              </div>
              <div class="" id="div_multifile_upload_file" style="display: none;"><br>
                <div class="row">
                  <div class="form-group col-md-6 input_les_th">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('file_name') . " " . label('thailand'); ?>:</label>
                    <input name="med_name_th" type="text" class="form-control" id="med_name_th">
                  </div>
                  <div class="form-group col-md-6 input_les_eng">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('file_name') . " " . label('english'); ?>:</label>
                    <input name="med_name_eng" type="text" class="form-control" id="med_name_eng">
                  </div>
                  <div class="form-group col-md-6 input_les_jp">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('file_name') . " " . label('japan'); ?>:</label>
                    <input name="med_name_jp" type="text" class="form-control" id="med_name_jp">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label class="control-label text-right"><?php echo label('thumbnail_med'); ?></label><input
                      type="file" name="thumbnail_med" id="thumbnail_med" class="dropify" accept="image/jpeg" />
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('media_file') . " (" . label('max_file') . ")"; ?>:</label>
                    <input type="file" name="media_file" id="media_file" class="dropify" accept="video/mp4"
                      onchange="ValidateSingleInputfile(this);onchangefilemedia();" />
                  </div>
                </div>
                <script type="text/javascript">
                function ValidateSingleInputfile(oInput) {
                  var fileUpload = document.getElementById("media_file");
                  if (typeof(fileUpload.files) != "undefined") {
                    var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2);
                    // alert(size + " KB.");
                    if (size > 1048576) {
                      swal({
                        title: '<?php echo label("file_warning"); ?>',
                        text: "",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: '<?php echo label("m_ok"); ?>'
                      })
                      oInput.value = "";
                      return false;
                    }
                  } else {
                    alert("This browser does not support HTML5.");
                  }
                  var validFileExtensionsss = ["mp4"];
                  if (oInput.type == "file") {
                    var sFileName = oInput.value;
                    var res = sFileName.split(".");
                    // console.log(res);
                    if (res.length > 0) {
                      var blnValid = false;
                      var rechkindex = validFileExtensionsss.lastIndexOf(res);
                      // console.log("recheck", rechkindex);
                      if (rechkindex == -1) {
                        blnValid = true;
                        // break;
                      }
                      if (!blnValid) {

                        swal({
                          title: '<?php echo label("media_type_dontmatch"); ?>',
                          text: "",
                          type: 'warning',
                          showCancelButton: false,
                          confirmButtonClass: 'btn btn-primary',
                          confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                        oInput.value = "";
                        return false;
                      }
                    }
                  }
                  return true;
                }
                </script>
                <br>
                <div class="table-responsive" id="tb_media" style="display: none;">
                  <table id="myTable_media" width="100%" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th width="20%">
                          <center><?php echo label('manage'); ?></center>
                        </th>
                        <th width="10%"></th>
                        <th width="30%">
                          <center><?php echo label('file_name'); ?></center>
                        </th>
                        <th width="40%">
                          <center><?php echo label('menu_path'); ?></center>
                        </th>
                      </tr>
                    </thead>
                  </table>
                  <p><?php echo label('preNote'); ?>: <?php if ($btn_delete == "1") { ?><button type="button"
                      class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
                    <b><?php echo label('delete'); ?></b><?php } ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="form-group col-md-6" style="margin: 0px auto 10px auto;"></div>
            <div class="form-group col-md-12" style="margin: 0px auto 10px auto;">
              <hr>
              <h5 align="left"><?php echo label('file_document'); ?> <button name="add_file_lesson" id="add_file_lesson"
                  onclick="return false;" class="btn btn-twitter waves-effect waves-light add_file_lesson"><i
                    class="mdi mdi-plus-box-outline"></i></button></h5>

              <!--<input type="file" name="document_file[]" id="input-file-now-custom-document" multiple class="dropify" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.pdf" />-->
              <input type="hidden" id="count_file" name="count_file" value="0">
              <br>
              <div class="table-responsive" id="tb_document" style="display: none;">
                <table id="myTable_document" width="100%" border="1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="10%"></th>
                      <th width="20%" class="input_les_th">
                        <center><?php echo label('file_name') . " " . label('acro_th'); ?></center>
                      </th>
                      <th width="20%" class="input_les_eng">
                        <center><?php echo label('file_name') . " " . label('acro_en'); ?></center>
                      </th>
                      <th width="20%" class="input_les_jp">
                        <center><?php echo label('file_name') . " " . label('acro_jp'); ?></center>
                      </th>
                      <th width="30%">
                        <center><?php echo label('menu_path'); ?></center>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="tb_document-tbody" id="tb_document_body"></tbody>
                </table>
                <p><?php echo label('preNote'); ?>: <?php if ($btn_delete == "1") { ?><button type="button"
                    class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
                  <b><?php echo label('delete'); ?></b><?php } ?>
                </p>
              </div>
            </div>
          </div>

          <div class="col-md-12 row" id="div_scorm">
            <div class="form-group col-md-6">
              <h5 align="left"><?php echo label('les_scorm'); ?>:</h5>
              <input type="file" name="scorm_file" id="scorm_file" class="dropify"
                accept="application/zip,application/x-zip,application/x-zip-compressed" />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label text-right"><b
                  style="color: #FF2D00">*</b><?php echo label('les_type_scorm'); ?>:</label>
              <div class="m-b-10">
                <label class="custom-control custom-radio">
                  <input type="radio" id="radio_scm_type1" name="scm_type" checked value="0"
                    class="custom-control-input">
                  <span class="custom-control-label"><?php echo label('lesson'); ?></span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" id="radio_scm_type2" name="scm_type" value="1" class="custom-control-input">
                  <span class="custom-control-label"><?php echo label('quiz'); ?></span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" id="radio_scm_type3" name="scm_type" value="2" class="custom-control-input">
                  <span class="custom-control-label"><?php echo label('lesson') . " + " . label('quiz'); ?></span>
                </label>
              </div>
              <hr>
              <span id="txt_scormoriginal"></span>
            </div>
          </div>

          <div class="col-md-12 progress" id="progress_lesson_div" style="display: none;">
            <div class="progress-bar-lesson bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
              aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_lesson"></span>
            </div>
          </div>
          <div class="form-group col-md-12" align="right">
            <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat"
              onclick="display_stylecancel('div_create_lesson','div_lesson')"><i class="mdi mdi-window-close"></i>
              <?php echo label('m_cancel'); ?></button>
          </div>

        </div>
      </form>
    </div>
    <div id="div_lesson" class="col-md-12">
      <div class="col-md-12" align="right">
        <?php if ($btn_update == "1") { ?>
        <button name="edit_order_lesson" id="edit_order_lesson" class="btn btn-outline-primary edit_order_lesson"
          onclick="create_div('div_order_lesson','div_lesson','lesson_order_form')"><i class="mdi mdi-lead-pencil"></i>
          <?php echo label('edit_lesson_sequences'); ?></button>
        <?php } ?>
        <?php if ($btn_add == "1") { ?>
        <button name="add_lesson" id="add_lesson" class="btn btn-outline-info add_lesson"
          onclick="create_div('div_create_lesson','div_lesson','lesson_form')"><i class="mdi mdi-plus-box-outline"></i>
          <?php echo label('create_lesson'); ?></button>
        <?php } ?>
      </div>
      <div class="table-responsive">
        <table id="myTable_lesson" width="100%" class="table table-bordered table-striped">
          <thead>
            <tr>
              <?php if ($btn_update == "1" || $btn_delete == "1") { ?>
              <th width="10%" id="col_lessson">
                <center><?php echo label('manage'); ?></center>
              </th>
              <?php } ?>
              <th width="10%"></th>
              <th width="10%">
                <center><?php echo label('faqlang'); ?></center>
              </th>
              <th width="40%">
                <center><?php echo label('lName'); ?></center>
              </th>
              <th width="15%">
                <center><?php echo label('dateStart'); ?></center>
              </th>
              <th width="15%">
                <center><?php echo label('dateExpired'); ?></center>
              </th>
            </tr>
          </thead>
        </table>
      </div>
      <p><?php echo label('preNote'); ?>: <?php if ($btn_update == "1") { ?><button type="button"
          class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
        <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_update == "1" && $btn_delete == "1") { ?> ,
        <?php } ?><?php if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i
            class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?>
      </p>
    </div>

  </div>
</div>

<div class="tab-pane p-20 card" id="quiz" role="tabpanel">
  <div class="card-body">

    <div id="div_quiz_main" class="row">
      <div id="div_create_quiz" style="display: none;">
        <form enctype="multipart/form-data" id="quiz_form" name="quiz_form" autocomplete="off" method="POST"
          accept-charset="utf-8" class="form-horizontal p-t-20">
          <input type="hidden" id="qiz_id" name="qiz_id">
          <input type="hidden" id="operation_quiz" name="operation_quiz" value="Add">
          <input type="hidden" id="course_id_quiz" name="course_id_quiz">

          <div class="col-md-12">
            <button type="button" class="btn btn-outline-danger float-right"
              onclick="display_style('div_create_quiz','div_quiz')"><i class="mdi mdi-keyboard-return"></i>
              <?php echo label('m_previous'); ?></button>
            <h3 id="txthead_quiz"></h3>
            <hr>
          </div>

          <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
              <p> <?php echo label('quiz_limit_note'); ?></p>
            </div>
          </div>
          <div class="col-md-12 row">
            <input type="hidden" id="quiz_lang" name="quiz_lang">
            <div class="col-md-12 input_quiz_th" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                  <?php echo label('thailand'); ?></div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                    <input name="quiz_name_th" type="text" class="form-control" id="quiz_name_th">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><?php echo label('qiz_detail'); ?>:</label>
                    <textarea name="quiz_info_th" id="quiz_info_th" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_quiz_eng" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                    <input name="quiz_name_eng" type="text" class="form-control" id="quiz_name_eng">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><?php echo label('qiz_detail'); ?>:</label>
                    <textarea name="quiz_info_eng" id="quiz_info_eng" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_quiz_jp" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6 input_jp">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('preName'); ?>:</label>
                    <input name="quiz_name_jp" type="text" class="form-control" id="quiz_name_jp">
                  </div>
                  <div class="form-group col-md-12 input_jp">
                    <label class="control-label"><?php echo label('qiz_detail'); ?>:</label>
                    <textarea name="quiz_info_jp" id="quiz_info_jp" rows="5"></textarea>
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
                      <input type="text" id="period_open" name="period_open" onchange="caldate('period_open')"
                        class="form-control period_open">
                      <input type="hidden" id="period_open_var" name="period_open_var">
                    </div>
                    <div class="col-md-4">
                      <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                        data-autoclose="true">
                        <input type="text" id="time_start_quiz" name="time_start_quiz" class="form-control"
                          value="<?php echo date('H:i', strtotime('00:00')); ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="control-label"><?php echo label('r_finish_on'); ?></label>
                  <div class="row">
                    <div class="col-md-8">
                      <input type="text" id="period_end" name="period_end" onchange="caldate('period_end')"
                        class="form-control period_end">
                      <input type="hidden" id="period_end_var" name="period_end_var">
                    </div>
                    <div class="col-md-4">
                      <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                        data-autoclose="true">
                        <input type="text" id="time_end_quiz" name="time_end_quiz" class="form-control"
                          value="<?php echo date('H:i', strtotime('23:59')); ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="form-group col-md-4">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('random'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('disable'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_random" name="quiz_random" value="1">
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
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('random_choice'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('disable'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_random_choice" name="quiz_random_choice" value="1">
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
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('is_quiz_hint'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('sv_b_hide'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_ishint" name="quiz_ishint" onclick="clickhint_quiz()" value="1">
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
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('model'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('can_skip_question'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_model" name="quiz_model" onclick="readonly_quiz('quiz_limitval')"
                        class="radio_chklimit" value="1">
                      <span class="lever switch-col-indigo"></span>
                    </label>
                  </div>
                </div>
                <div class="col-4">
                  <small><?php echo label('answer_until_correct'); ?></small>
                </div>
              </div>
            </div>

            <script type="text/javascript">
            function onchk_numofshow(value) {
              var totalquiz = parseInt($('#totalquiz').val());
              if (parseInt(value) > totalquiz) {
                $('#quiz_numofshown').val(totalquiz);
              }
            }
            </script>
            <div class="form-group col-md-4">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('show_myscore'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('hide'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_grade" name="quiz_grade" value="1">
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
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('qiz_type'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('preExam'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_type" name="quiz_type" checked value="2"
                        onclick="display_quiz('div_answer')">
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
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('qiz_visible'); ?>:</label>
              <div class="row">
                <div class="col-4 text-right">
                  <small><?php echo label('hide'); ?></small>
                </div>
                <div class="col-4 text-center">
                  <div class="switch">
                    <label>
                      <input type="checkbox" id="quiz_show" name="quiz_show" checked value="1">
                      <span class="lever switch-col-indigo"></span>
                    </label>
                  </div>
                </div>
                <div class="col-4">
                  <small><?php echo label('show'); ?></small>
                </div>
              </div>
            </div>

            <div class="form-group col-md-4" id="div_answer" style="display: none;">
              <div>
                <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('preAns'); ?>:</label>
                <div class="row">
                  <div class="col-4 text-right">
                    <small><?php echo label('sv_b_hide'); ?></small>
                  </div>
                  <div class="col-4 text-center">
                    <div class="switch">
                      <label>
                        <input type="checkbox" id="quiz_answer" name="quiz_answer" value="1">
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
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('qiz_limit'); ?>:</label>
                  <div class="row">
                    <div class="col-4 text-right">
                      <small><?php echo label('no'); ?></small>
                    </div>
                    <div class="col-4 text-center">
                      <div class="switch">
                        <label>
                          <input type="checkbox" id="quiz_limit" name="quiz_limit" class="radio_chklimit"
                            onclick="readonly_quiz('quiz_limitval')">
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
                  <label class="control-label"><b class="text-danger">*</b><?php echo label('number_of'); ?>:</label>
                  <input name="quiz_limitval" type="number" class="form-control" id="quiz_limitval">
                </div>

              </div>
            </div>


            <div class="col-md-12">
              <div class="row">

                <div class="form-group col-md-4 div_lastquiz">
                  <label class="control-label"><b
                      class="text-danger">*</b><?php echo label('quiz_numofshown'); ?>:</label>

                  <div class="input-group">
                    <input type="number" onchange="onchk_numofshow(this.value)" class="form-control"
                      name="quiz_numofshown" id="quiz_numofshown">
                    <div class="input-group-append">
                      <span class="input-group-text" id="txt_totalquiz"></span>
                    </div>
                  </div>
                  <input type="hidden" id="totalquiz" name="totalquiz"><br>
                  <?php echo label('quiz_numofshown_note'); ?>
                </div>
                <div class="form-group col-md-4 div_lastquiz">
                  <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('ccond'); ?>:</label>
                  <input name="quiz_maxscore" required type="text"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="0"
                    class="form-control" id="quiz_maxscore">
                </div>
                <div class="form-group col-md-4" id="div_template_qize">
                  <label class="control-label"><?php echo label('quiz_ex'); ?>:</label>
                  <select class="form-control" id="qize_id" name="qize_id" style="width: 100%;">
                  </select>
                </div>

              </div>
            </div>


            <div class="col-md-12 progress" id="progress_qiz_div" style="display: none;">
              <div class="progress-bar-qiz bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_qiz"></span>
              </div>
            </div>
            <div class="form-group col-md-12" align="right">
              <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                  class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
              <button type="button" class="btn btn-outline-danger btn-flat"
                onclick="display_stylecancel('div_create_quiz','div_quiz')"><i class="mdi mdi-window-close"></i>
                <?php echo label('m_cancel'); ?></button>
            </div>

          </div>
        </form>
        <script>
        (function () {
          var form = document.getElementById('quiz_form');
          if (!form || form.querySelector('.quiz-settings-panel')) return;

          var definitions = [
            {title:'ช่วงเวลาที่เปิดให้ทำแบบทดสอบ',desc:'กำหนดวันและเวลาที่ผู้เรียนสามารถเข้าทำแบบทดสอบได้',icon:'mdi-calendar-clock',items:[
              {id:'period_open',name:'วันและเวลาเปิด–ปิด',help:'นอกช่วงเวลานี้ผู้เรียนจะไม่สามารถเริ่มทำแบบทดสอบได้',wide:true}
            ]},
            {title:'รูปแบบการทำข้อสอบ',desc:'ตั้งค่าลำดับคำถาม วิธีตอบ และตัวช่วยระหว่างทำข้อสอบ',icon:'mdi-tune',items:[
              {id:'quiz_random',name:'สุ่มลำดับคำถาม',help:'ผู้เรียนแต่ละคนจะเห็นคำถามเรียงลำดับต่างกัน'},
              {id:'quiz_random_choice',name:'สุ่มลำดับตัวเลือก',help:'สลับตำแหน่งตัวเลือกคำตอบโดยอัตโนมัติ'},
              {id:'quiz_ishint',name:'แสดงคำใบ้',help:'ผู้เรียนสามารถเปิดดูคำใบ้ที่กำหนดไว้ในแต่ละข้อ'},
              {id:'quiz_model',name:'บังคับตอบให้ถูกก่อนผ่านไปข้อถัดไป',help:'เหมาะกับแบบฝึกหัด หากปิด ผู้เรียนสามารถข้ามและย้อนกลับมาทำได้',wide:true}
            ]},
            {title:'การแสดงผลแก่ผู้เรียน',desc:'ควบคุมสิ่งที่ผู้เรียนมองเห็นก่อนและหลังส่งคำตอบ',icon:'mdi-eye-outline',items:[
              {id:'quiz_grade',name:'แสดงคะแนนหลังส่ง',help:'แจ้งคะแนนรวมให้ผู้เรียนทราบทันทีหลังส่งข้อสอบ'},
              {id:'quiz_type',name:'ประเภทแบบทดสอบ',help:'สวิตช์ซ้ายคือก่อนเรียน และขวาคือหลังเรียน'},
              {id:'quiz_show',name:'เผยแพร่ให้ผู้เรียนเห็น',help:'หากปิด แบบทดสอบยังอยู่ในระบบแต่ผู้เรียนจะไม่เห็น'},
              {id:'quiz_answer',name:'แสดงเฉลย',help:'แสดงคำตอบที่ถูกต้องหลังผู้เรียนส่งข้อสอบ'}
            ]},
            {title:'เกณฑ์และจำนวนครั้ง',desc:'กำหนดจำนวนข้อที่แสดง คะแนนผ่าน และสิทธิ์ในการทำซ้ำ',icon:'mdi-shield-check-outline',items:[
              {id:'quiz_limit',name:'จำกัดจำนวนครั้งในการทำ',help:'เปิดเมื่อต้องการกำหนดจำนวนครั้งสูงสุด'},
              {id:'quiz_limitval',name:'จำนวนครั้งสูงสุด',help:'กรอกจำนวนเต็ม เช่น 1 หรือ 3'},
              {id:'quiz_numofshown',name:'จำนวนข้อที่แสดง',help:'จำนวนคำถามที่จะเลือกมาแสดงต่อผู้เรียน'},
              {id:'quiz_maxscore',name:'คะแนนผ่าน (%)',help:'กำหนดตั้งแต่ 0–100 เปอร์เซ็นต์'},
              {id:'qize_id',name:'เทมเพลตแบบทดสอบ',help:'เลือกต้นแบบที่มีอยู่ หรือเว้นว่างเพื่อสร้างใหม่'}
            ]}
          ];

          var first = document.getElementById('period_open');
          if (!first) return;
          var panel = document.createElement('div');
          panel.className = 'quiz-settings-panel col-md-12';
          var firstGroup = first.closest('.form-group');
          firstGroup.parentNode.insertBefore(panel, firstGroup);

          definitions.forEach(function (definition) {
            var section = document.createElement('section');
            section.className = 'quiz-settings-section';
            section.innerHTML = '<div class="quiz-settings-head"><span class="quiz-settings-icon"><i class="mdi '+definition.icon+'"></i></span><div><h4 class="quiz-settings-title">'+definition.title+'</h4><p class="quiz-settings-desc">'+definition.desc+'</p></div></div><div class="quiz-setting-grid"></div>';
            var grid = section.querySelector('.quiz-setting-grid');
            definition.items.forEach(function (item) {
              var control = document.getElementById(item.id);
              if (!control) return;
              var group = control.closest('.form-group');
              if (!group || group.classList.contains('quiz-setting-card')) return;
              group.classList.add('quiz-setting-card');
              if (item.wide) group.classList.add('quiz-setting-wide');
              var intro = document.createElement('div');
              intro.innerHTML = '<p class="quiz-setting-name">'+item.name+'</p><p class="quiz-setting-help">'+item.help+'</p>';
              group.insertBefore(intro, group.firstChild);
              if (item.id === 'quiz_limitval') { control.min='1'; control.step='1'; control.placeholder='เช่น 3'; }
              if (item.id === 'quiz_numofshown') { control.min='1'; control.step='1'; }
              if (item.id === 'quiz_maxscore') { control.type='number'; control.min='0'; control.max='100'; control.step='0.01'; }
              grid.appendChild(group);
            });
            panel.appendChild(section);
          });

          var actions = form.querySelector('#action');
          if (actions) {
            var actionGroup = actions.closest('.form-group');
            actionGroup.classList.add('quiz-settings-actions');
            panel.appendChild(actionGroup);
          }

          function syncLimitField() {
            var toggle = document.getElementById('quiz_limit');
            var value = document.getElementById('quiz_limitval');
            if (!toggle || !value) return;
            value.readOnly = !toggle.checked;
            if (!toggle.checked) value.value = '';
          }
          var limitToggle = document.getElementById('quiz_limit');
          if (limitToggle) limitToggle.addEventListener('change', syncLimitField);
          syncLimitField();
        })();
        </script>
      </div>

      <div id="div_quiz" class="col-md-12">
        <div class="col-md-12" align="right">
          <?php if ($btn_add == "1") { ?>
          <button name="add_quiz" id="add_quiz" class="btn btn-outline-info add_quiz"
            onclick="create_div('div_create_quiz','div_quiz','quiz_form')"><i class="mdi mdi-plus-box-outline"></i>
            <?php echo label('create_quiz'); ?></button>
          <?php } ?>
        </div>
        <div class="table-responsive">
          <table id="myTable_quiz" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <?php if ($btn_update == "1" || $btn_delete == "1") { ?>
                <th style="min-width: 80px;">
                  <center><?php echo label('manage'); ?></center>
                </th>
                <?php } ?>
                <th width="10%"></th>
                <th width="10%">
                  <center><?php echo label('faqlang'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('qName'); ?></center>
                </th>
                <th width="10%">
                  <center><?php echo label('qiz_type'); ?></center>
                </th>
                <th width="10%">
                  <center><?php echo label('maxScore'); ?></center>
                </th>
                <th width="10%">
                  <center><?php echo label('ccond'); ?></center>
                </th>
                <th width="10%">
                  <center><?php echo label('dateStart'); ?></center>
                </th>
                <th width="10%">
                  <center><?php echo label('dateExpired'); ?></center>
                </th>
              </tr>
            </thead>
          </table>
        </div>
        <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i
              class="mdi mdi-comment-question-outline"></i></button> =
          <b><?php echo label('question'); ?></b><?php if ($btn_update == "1") { ?> , <button type="button"
            class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
          <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_update == "1" && $btn_delete == "1") { ?> ,
          <?php } ?><?php if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i
              class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?>
        </p>
      </div>
    </div>

    <div id="div_question_check" style="display: none;">
      <div class="col-md-12" align="right">
        <button type="button" name="back_quiz_check" id="back_quiz_check" class="btn btn-outline-danger back_quiz_check"
          onclick="display_style('div_question_check','div_quiz_detail')"><i class="mdi mdi-keyboard-return"></i>
          <?php echo label('m_previous'); ?></button>
      </div>
      <h4 id="quiz_name_txt_question"></h4>
      <hr>
      <form enctype="multipart/form-data" id="checkquestion_form" name="checkquestion_form" autocomplete="off"
        method="POST" accept-charset="utf-8" class="form-horizontal p-t-20">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="myTable_quiz_question_check" width="100%" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="20%">
                    <center><?php echo label('username'); ?></center>
                  </th>
                  <th width="10%">
                    <center><?php echo label('m_name'); ?></center>
                  </th>
                  <th width="15%">
                    <center><?php echo label('answer'); ?></center>
                  </th>
                  <th width="25%">
                    <center><?php echo label('msg_fromadmin'); ?></center>
                  </th>
                  <th width="10%">
                    <center><?php echo label('maxScore'); ?></center>
                  </th>
                  <th width="10%">
                    <center><?php echo label('score'); ?></center>
                  </th>
                  <th width="10%" class="tc_set">
                    <center><?php echo label('saveR'); ?></center>
                  </th>
                </tr>
              </thead>
            </table>
          </div>
          <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-success btn-xs"><i
                class="mdi mdi-content-save"></i></button> = <b><?php echo label('saveR'); ?></b></p>
        </div>
        <input type="hidden" id="ques_idcheck" name="ques_idcheck">
      </form>
    </div>
    <div id="div_quiz_detail" style="display: none;">
      <div id="div_quiz_question" class="col-md-12">
        <div class="col-md-12" align="right">
          <button type="button" class="btn btn-outline-danger"
            onclick="display_style('div_quiz_detail','div_quiz_main')"><i class="mdi mdi-keyboard-return"></i>
            <?php echo label('m_previous'); ?></button>
          <?php if ($btn_add == "1") { ?>
          <button name="import_question" id="import_question" class="btn btn-outline-info import_question"
            onclick="create_div('div_import_question','div_quiz_question','question_import_form')"><i
              class="mdi mdi-upload"></i> <?php echo ucwords(label('import_data_question')); ?></button>
          <button name="add_question" id="add_question" class="btn btn-outline-info add_question"
            onclick="create_div('div_create_question','div_quiz_question','question_form')"><i
              class="mdi mdi-plus-box-outline"></i> <?php echo label('create_question'); ?></button>
          <?php } ?>
          <h3 class="float-left"><?php echo label('question'); ?></h3>
          <hr>
        </div>
        <div class="table-responsive">
          <table id="myTable_quiz_question" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="15%">
                  <center><?php echo label('manage'); ?></center>
                </th>
                <th width="5%"></th>
                <th width="15%">
                  <center><?php echo label('quest_type'); ?></center>
                </th>
                <th width="35%">
                  <center><?php echo label('squestion'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('choice'); ?></center>
                </th>
                <th width="10%" align="center">
                  <center><?php echo label('status'); ?></center>
                </th>
              </tr>
            </thead>
          </table>
        </div>
        <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i
              class="mdi mdi-check-circle"></i></button> =
          <b><?php echo label('chk_answer'); ?></b><?php if ($btn_update == "1") { ?> , <button type="button"
            class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
          <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_update == "1" && $btn_delete == "1") { ?> ,
          <?php } ?><?php if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i
              class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?>
        </p>
      </div>
      <div id="div_import_question" class="col-md-12" style="display: none;">
        <div class="col-md-12">
          <button type="button" class="btn btn-outline-danger float-right"
            onclick="display_style('div_import_question','div_quiz_question')"><i class="mdi mdi-keyboard-return"></i>
            <?php echo label('m_previous'); ?></button>
          <h3><i class="mdi mdi-upload"></i> <?php echo ucwords(label('import_data_question')); ?></h3>
          <hr>
        </div>
        <form enctype="multipart/form-data" id="question_import_form" name="question_import_form" autocomplete="off"
          method="POST" accept-charset="utf-8" class="form-horizontal p-t-20 row">
          <div class="col-md-6">
            <label for="file_import_question"><b style="color: #FF2D00">*</b><?php echo 'Excel File'; ?>:</label>
            <input type="file" name="file_import_question" required id="file_import_question" class="dropify"
              accept=".xls,.xlsx" />
            <?php echo label('certificate_example') . ": "; ?><a
              href="<?php echo REAL_PATH; ?>/uploads/format/format_import_qiz.xlsx"
              download>format_import_question.xlsx</a><br>
            <b style="color:red;"><?php echo label('remark_upload_question'); ?></b>
            <input type="hidden" id="operation_import_question" name="operation_import_question" value="Add">
            <input type="hidden" id="qiz_id_question_import" name="qiz_id_question_import">
          </div>

          <div class="col-md-6">
            <h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('result_import'); ?>:</h4>
            <div id="result_import_question" class="slimtest1" style="max-height: 300px;position: relative;"></div>
          </div>
          <div class="col-md-12 progress" id="progress_importqiz_div" style="display: none;">
            <div class="progress-bar-importqiz bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
              aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only"
                id="txt_progress_importqiz"></span></div>
          </div>
          <div class="form-group col-md-12" align="right">
            <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                class="mdi mdi-upload"></i> <?php echo ucwords(label('upload_btn')); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat"
              onclick="display_stylecancel('div_import_question','div_quiz_question')"><i
                class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
          </div>
        </form>
      </div>
      <div id="div_create_question" style="display: none;" class="col-md-12">
        <div class="col-md-12">
          <button type="button" class="btn btn-outline-danger float-right"
            onclick="display_style('div_create_question','div_quiz_question')"><i class="mdi mdi-keyboard-return"></i>
            <?php echo label('m_previous'); ?></button>
          <h3 id="quiz_name_txt"></h3>
          <hr>
        </div>
        <form enctype="multipart/form-data" id="question_form" name="question_form" autocomplete="off" method="POST"
          accept-charset="utf-8" class="form-horizontal p-t-20">
          <input type="hidden" id="qiz_id_question" name="qiz_id_question">
          <input type="hidden" id="cos_id_question" name="cos_id_question">
          <input type="hidden" id="ques_id" name="ques_id">
          <input type="hidden" id="operation_question" name="operation_question" value="Add">
          <div class="col-md-12 row">
            <div class="col-md-12 input_ques_th" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                  <?php echo label('thailand'); ?></div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="ques_name_th" id="ques_name_th" rows="10" class="form-control"></textarea>
                  </div>
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><?php echo label('question_summary'); ?>:</label>
                    <textarea name="ques_info_th" id="ques_info_th" rows="10" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_ques_eng" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="ques_name_eng" id="ques_name_eng" rows="10" class="form-control"></textarea>
                  </div>
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><?php echo label('question_summary'); ?>:</label>
                    <textarea name="ques_info_eng" id="ques_info_eng" rows="10" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_ques_jp" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="ques_name_jp" id="ques_name_jp" rows="10" class="form-control"></textarea>
                  </div>
                  <div class="form-group col-md-6">
                    <label class="control-label text-right"><?php echo label('question_summary'); ?>:</label>
                    <textarea name="ques_info_jp" id="ques_info_jp" rows="10" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group col-md-4">
              <label><b style="color: #FF2D00">*</b><?php echo label('quest_type'); ?>:</label>
              <select class="form-control" required id="ques_type" name="ques_type" style="width: 100%;">

              </select>
            </div>
            <div class="form-group col-md-4">
              <label class="control-label text-right"><b
                  style="color: #FF2D00">*</b><?php echo label('maxScore'); ?>:</label>
              <input name="ques_score" required type="text" class="form-control" value="0" id="ques_score"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
            </div>

            <div class="form-group col-md-4">
              <label class="control-label text-right"><b
                  style="color: #FF2D00">*</b><?php echo label('quest_visible'); ?>:</label>
              <div class="switch">
                <label><?php echo label('hide'); ?><input type="checkbox" id="ques_show" name="ques_show" checked
                    value="1"><span class="lever switch-col-indigo"></span><?php echo label('show'); ?></label>
              </div>
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Require learner upload:</label>
              <div class="switch">
                <label>No<input type="checkbox" id="ques_upload_required" name="ques_upload_required" value="1"><span class="lever switch-col-indigo"></span>Yes</label>
              </div>
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Allowed upload type:</label>
              <select class="form-control" id="ques_upload_type" name="ques_upload_type">
                <option value="both">Image or Video</option>
                <option value="image">Image only</option>
                <option value="video">Video only</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Max upload size (MB):</label>
              <input name="ques_upload_max_mb" type="number" min="1" max="200" step="1" class="form-control" id="ques_upload_max_mb" value="10">
            </div>
            <style>
              .quiz-settings-panel{width:100%;margin-top:10px}.quiz-settings-section{border:1px solid #e3e8f1;border-radius:14px;background:#fff;margin-bottom:18px;padding:20px;box-shadow:0 3px 14px rgba(38,51,77,.05)}
              .quiz-settings-head{display:flex;gap:12px;align-items:flex-start;margin-bottom:16px}.quiz-settings-icon{width:40px;height:40px;flex:0 0 40px;border-radius:11px;background:#fff0f1;color:#e71921;display:flex;align-items:center;justify-content:center;font-size:21px}.quiz-settings-title{font-size:17px;font-weight:800;color:#25324b;margin:0}.quiz-settings-desc{font-size:13px;line-height:1.5;color:#77839a;margin:3px 0 0}
              .quiz-setting-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.quiz-setting-card{width:auto!important;max-width:none!important;margin:0!important;padding:15px!important;border:1px solid #e3e8f1;border-radius:12px;background:#fafbfd;min-height:120px;display:flex;flex-direction:column;justify-content:space-between}.quiz-setting-card.quiz-setting-wide{grid-column:span 2}.quiz-setting-card>.control-label:first-child{display:none}.quiz-setting-name{font-size:14px;font-weight:800;color:#25324b;margin:0}.quiz-setting-help{font-size:12px;line-height:1.45;color:#77839a;margin:5px 0 12px}.quiz-setting-card .row{margin-left:0;margin-right:0}.quiz-setting-card .switch{margin:0}.quiz-setting-card .form-control{min-height:42px;border-radius:9px}.quiz-setting-card .input-group-text{border-radius:0 9px 9px 0}.quiz-field-help{display:block;color:#77839a;font-size:12px;line-height:1.45;margin-top:7px}.quiz-settings-panel .text-danger{color:#e71921!important}.quiz-settings-actions{display:flex!important;justify-content:flex-end!important;gap:10px;padding:5px 0 0!important}.quiz-settings-actions .btn{min-width:112px;border-radius:9px;padding:10px 17px;font-weight:700}
              @media(max-width:991px){.quiz-setting-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.quiz-setting-card.quiz-setting-wide{grid-column:span 2}}
              @media(max-width:767px){.quiz-settings-section{padding:15px}.quiz-setting-grid{grid-template-columns:1fr}.quiz-setting-card.quiz-setting-wide{grid-column:auto}.quiz-settings-actions{position:sticky;bottom:0;background:#fff;padding:12px!important;z-index:5;box-shadow:0 -5px 18px rgba(38,51,77,.1)}.quiz-settings-actions .btn{flex:1;min-width:0}}
            </style>
            <div class="form-group col-md-12">
              <label class="control-label">Upload note for learner:</label>
              <textarea name="ques_upload_note" id="ques_upload_note" rows="3" class="form-control" placeholder="Example: Please upload a clear image or MP4 video. Maximum 10 MB."></textarea>
            </div>

            <div class="col-md-12 row" id="div_question_mul" style="display: none;">
              <div class="form-group col-md-6 courseCat">
                <h4><?php echo label('quest_detail'); ?></h4>
              </div>
              <div class="form-group col-md-6">
                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('answer'); ?>:</label>
                <select class="form-control select2 mul_answerclass" id="mul_answer" name="mul_answer[]" multiple
                  style="width: 100%;">
                  <option value="mul_c1"><?php echo label('choice') . " 1"; ?></option>
                  <option value="mul_c2"><?php echo label('choice') . " 2"; ?></option>
                  <option value="mul_c3"><?php echo label('choice') . " 3"; ?></option>
                  <option value="mul_c4"><?php echo label('choice') . " 4"; ?></option>
                  <option value="mul_c5"><?php echo label('choice') . " 5"; ?></option>
                </select>
              </div>

              <div class="col-md-12 input_quesdetail_th" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                    <?php echo label('thailand'); ?></div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-6 mul_c1">
                      <label class="control-label text-right"><?php echo label('choice') . " 1"; ?>:</label>
                      <textarea name="mul_c1_th" id="mul_c1_th" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c2">
                      <label class="control-label text-right"><?php echo label('choice') . " 2"; ?>:</label>
                      <textarea name="mul_c2_th" id="mul_c2_th" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c3">
                      <label class="control-label text-right"><?php echo label('choice') . " 3"; ?>:</label>
                      <textarea name="mul_c3_th" id="mul_c3_th" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c4">
                      <label class="control-label text-right"><?php echo label('choice') . " 4"; ?>:</label>
                      <textarea name="mul_c4_th" id="mul_c4_th" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c5">
                      <label class="control-label text-right"><?php echo label('choice') . " 5"; ?>:</label>
                      <textarea name="mul_c5_th" id="mul_c5_th" rows="5" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 input_quesdetail_eng" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i>
                    <?php echo label('english'); ?></div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-6 mul_c1">
                      <label class="control-label text-right"><?php echo label('choice') . " 1"; ?>:</label>
                      <textarea name="mul_c1_eng" id="mul_c1_eng" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c2">
                      <label class="control-label text-right"><?php echo label('choice') . " 2"; ?>:</label>
                      <textarea name="mul_c2_eng" id="mul_c2_eng" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c3">
                      <label class="control-label text-right"><?php echo label('choice') . " 3"; ?>:</label>
                      <textarea name="mul_c3_eng" id="mul_c3_eng" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c4">
                      <label class="control-label text-right"><?php echo label('choice') . " 4"; ?>:</label>
                      <textarea name="mul_c4_eng" id="mul_c4_eng" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c5">
                      <label class="control-label text-right"><?php echo label('choice') . " 5"; ?>:</label>
                      <textarea name="mul_c5_eng" id="mul_c5_eng" rows="5" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 input_quesdetail_jp" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                  </div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-6 mul_c1">
                      <label class="control-label text-right"><?php echo label('choice') . " 1"; ?>:</label>
                      <textarea name="mul_c1_jp" id="mul_c1_jp" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c2">
                      <label class="control-label text-right"><?php echo label('choice') . " 2"; ?>:</label>
                      <textarea name="mul_c2_jp" id="mul_c2_jp" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c3">
                      <label class="control-label text-right"><?php echo label('choice') . " 3"; ?>:</label>
                      <textarea name="mul_c3_jp" id="mul_c3_jp" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c4">
                      <label class="control-label text-right"><?php echo label('choice') . " 4"; ?>:</label>
                      <textarea name="mul_c4_jp" id="mul_c4_jp" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6 mul_c5">
                      <label class="control-label text-right"><?php echo label('choice') . " 5"; ?>:</label>
                      <textarea name="mul_c5_jp" id="mul_c5_jp" rows="5" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-md-12 row" id="div_question_hint" style="display: none;">
              <div class="form-group col-md-12">
                <h4><?php echo label('txt_hinthead'); ?></h4>
              </div>

              <div class="col-md-6 input_quesdetail_th" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                    <?php echo label('thailand'); ?></div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-12">
                      <label class="control-label"><?php echo label('msg_hinthead'); ?>:</label>
                      <input name="ques_hintname_th" type="text" class="form-control" id="ques_hintname_th">
                    </div>
                    <div class="form-group col-md-12">
                      <label class="control-label text-right"><?php echo label('detail'); ?>:</label>
                      <textarea name="ques_hintdetail_th" id="ques_hintdetail_th" rows="8"
                        class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 input_quesdetail_eng" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i>
                    <?php echo label('english'); ?></div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-12">
                      <label class="control-label"><?php echo label('msg_hinthead'); ?>:</label>
                      <input name="ques_hintname_eng" type="text" class="form-control" id="ques_hintname_eng">
                    </div>
                    <div class="form-group col-md-12">
                      <label class="control-label text-right"><?php echo label('detail'); ?>:</label>
                      <textarea name="ques_hintdetail_eng" id="ques_hintdetail_eng" rows="8"
                        class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 input_quesdetail_jp" style="display:none;">
                <div class="ribbon-wrapper card">
                  <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                  </div>
                  <div class="ribbon-content row">
                    <div class="form-group col-md-12">
                      <label class="control-label"><?php echo label('msg_hinthead'); ?>:</label>
                      <input name="ques_hintname_jp" type="text" class="form-control" id="ques_hintname_jp">
                    </div>
                    <div class="form-group col-md-12">
                      <label class="control-label text-right"><?php echo label('detail'); ?>:</label>
                      <textarea name="ques_hintdetail_jp" id="ques_hintdetail_jp" rows="8"
                        class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="control-label"><?php echo label('img_hinthead'); ?>:</label>
                <input type="file" name="ques_hintimg" id="ques_hintimg" class="ques_hintimg"
                  accept="image/png, image/jpeg, image/gif" />
              </div>
            </div>

            <div class="col-md-12 progress" id="progress_question_div" style="display: none;">
              <div class="progress-bar-question bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only"
                  id="txt_progress_question"></span></div>
            </div>
            <div class="form-group col-md-12" align="right">
              <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                  class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
              <button type="button" class="btn btn-outline-danger btn-flat"
                onclick="display_stylecancel('div_create_question','div_quiz_question')"><i
                  class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="tab-pane p-20 card" id="videocourse" role="tabpanel">

  <div class="card-body row">
    <form enctype="multipart/form-data" id="videocourse_form" name="videocourse_form" autocomplete="off" method="POST"
      accept-charset="utf-8" class="form-horizontal p-t-20">
      <div class="col-md-12 row">
        <input type="hidden" id="operation_cosv" name="operation_cosv" value="Add">
        <input type="hidden" id="course_id_cosv" name="course_id_cosv">
        <input type="hidden" id="cosv_id" name="cosv_id">
        <div class="form-group col-md-6">
          <label class="control-label"><?php echo label('faqlang'); ?>:</label>
          <select class="form-control select2" style="width: 100%" multiple id="cosv_lang" name="cosv_lang[]">
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="type_media_cosv"><?php echo label('sqtype') . " Media"; ?>:</label>
          <select class="form-control" id="type_media_cosv" name="type_media_cosv" style="width: 100%;">
            <option value="1" selected><?php echo "URL"; ?></option>
            <option value="2"><?php echo "Upload File"; ?></option>
          </select>
        </div>
        <div class="col-md-12">
          <div class="" id="div_multifile_url_videocourse">
            <textarea class="form-control" name="url_media_cosv" id="url_media_cosv" rows="5"
              style="width: 100%"></textarea>
            <label class="control-label text-right"><?php echo label('les_url_msg'); ?></label>
          </div>
          <div class="" id="div_multifile_upload_file_videocourse" style="display: none;">
            <div class="row">
              <div class="form-group col-md-6 input_cosv_eng" style="display: none;">
                <label
                  class="control-label text-right"><?php echo label('file_name') . " (" . label('eng') . ")"; ?>:</label>
                <input name="cosv_eng" type="text" class="form-control" id="cosv_eng">
              </div>
              <div class="form-group col-md-6 input_cosv_th" style="display: none;">
                <label
                  class="control-label text-right"><?php echo label('file_name') . " (" . label('thai') . ")"; ?>:</label>
                <input name="cosv_th" type="text" class="form-control" id="cosv_th">
              </div>
              <div class="form-group col-md-6 input_cosv_jp" style="display: none;">
                <label
                  class="control-label text-right"><?php echo label('file_name') . " (" . label('jp') . ")"; ?>:</label>
                <input name="cosv_jp" type="text" class="form-control" id="cosv_jp">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label class="control-label text-right"><b
                    style="color: #FF2D00">*</b><?php echo label('thumbnail_med'); ?>:</label>
                <input type="file" name="cosv_thumbnail" id="cosv_thumbnail" class="dropify" accept="image/jpeg" />
              </div>
              <div class="form-group col-md-12">
                <label class="control-label text-right"><b
                    style="color: #FF2D00">*</b><?php echo label('media_file') . " (" . label('max_file') . ")"; ?>:</label>
                <input type="file" name="cosv_video" id="cosv_video" class="dropify" accept="video/mp4" />
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 progress" id="progress_cosvideo_div" style="display: none;">
          <div class="progress-bar-cosvideo bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
            aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_cosvideo"></span>
          </div>
        </div>
        <div class="col-md-12" align="right">
          <button type="submit" class="btn btn-outline-success btn-flat pull-right" name="add_file" id="add_file"><i
              class="mdi mdi-note-plus"></i> <?php echo label('saveR'); ?></button>
          <button type="button" class="btn btn-outline-danger btn-flat pull-right"><i class="mdi mdi-window-close"></i>
            <?php echo label('m_cancel'); ?></button>
          <hr>
        </div>
      </div>
    </form>
    <div class="col-md-12 row" align="">
      <div class="col-md-12 table-responsive" id="tb_cos_document">
        <table id="myTable_videocourse" width="100%" class="table table-bordered table-striped">
          <thead>
            <tr>
              <?php if ($btn_update == "1" || $btn_delete == "1") { ?>
              <th width="20%">
                <center><?php echo label('manage'); ?></center>
              </th>
              <?php } ?>
              <th width="10%"></th>
              <th width="35%">
                <center><?php echo label('file_name'); ?></center>
              </th>
              <th width="20%">
                <center><?php echo label('menu_path'); ?></center>
              </th>
              <th width="15%">
                <center><?php echo label('faqlang'); ?></center>
              </th>
            </tr>
          </thead>
        </table>
      </div>
      <p><?php echo label('preNote'); ?>: <?php if ($btn_update == "1") { ?><button type="button"
          class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
        <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_delete == "1") { ?> , <button type="button"
          class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
        <b><?php echo label('delete'); ?></b><?php } ?>
      </p>
    </div>
  </div>
</div>

<div class="tab-pane p-20 card" id="enroll" role="tabpanel">
  <div class="card-body">
    <div id="div_enroll_main" class="row">
      <?php if(isset($user['u_id']) && $user['u_id'] == "1"){ ?>
      <div class="col-md-12" align="right">
        <button name="regencert_course" id="regencert_course" class="btn btn-outline-success regencert_course"><i
            class="mdi mdi-refresh"></i> Regenerate Certificate</button>
        <hr>
      </div>
      <?php } ?>
      <div class="col-md-12" id="manage_quiz_div" style="display: none;" align="right">
        <button name="manage_quiz" id="manage_quiz" class="btn btn-outline-info manage_quiz"><i
            class="mdi mdi-table-edit"></i> <?php echo label('managequiz'); ?></button>
        <hr>
      </div>
      <div class="form-group col-md-12" id="check_importdata" align="left">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group col-md-12" align="left">
              <label class="control-label text-right"><b
                  style="color: #FF2D00">*</b><?php echo label('import_data'); ?>:</label>

              <div class="switch">
                <label><?php echo label('no'); ?><input type="checkbox" id="status_add" name="status_add"
                    onclick="onchange_statusadd()" value="1"><span
                    class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
              </div>
            </div>
          </div>
          <div class="form-group col-md-12" id="div_empcode" align="left">
            <div class="row">
              
              <div class="col-md-7">
                <label for="useri">
                  <b style="color:#FF2D00">*</b><?php echo label('input_learner_email'); ?>:
                </label>

                <div class="autocomplete-wrap">
                  <input
                    type="text"
                    id="useri"
                    name="useri"
                    autocomplete="off"
                    class="form-control"
                    placeholder="<?= label('ph_learner_email'); ?>"
                    data-limit="10"
                  >
                  <div id="useri-list" class="autocomplete-list" role="listbox" aria-label="Suggestions"></div>
                </div>
              </div>
              <div class="col-md-5">
                <button type="button" onclick="add_employeetocourse()"
                  class="position-absolute col-6 btn btn-outline-info btn_add_lerner" style="bottom: 0;"><i
                    class="mdi mdi-plus"></i> <?php echo ucwords(label('e_add')); ?></button>
              </div>
            </div>
          </div>
          <div class="form-group col-md-12" id="div_upload" style="display: none;" align="left">

            <form enctype="multipart/form-data" id="upload_student_form" name="upload_student_form" autocomplete="off"
              method="POST" accept-charset="utf-8" class="form-horizontal p-t-20 row">
              <input type="hidden" id="operation_student" name="operation_student" value="Add">
              <input type="hidden" id="course_id_student" name="course_id_student">
              <div class="col-md-6">
                <label class="control-label text-right"><b
                    style="color: #FF2D00">*</b><?php echo label('media_excel_file'); ?>:</label>
                <input type="file" name="importstudent" id="importstudent" class="dropify" accept=".xlsx,.xls" />
                <?php echo label('certificate_example') . ": "; ?><a
                  href="<?php echo REAL_PATH; ?>/uploads/format/format_import_student.xlsx"
                  download>format_import_student.xlsx</a>
              </div>
              <div class="col-md-6">
                <h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('result_import'); ?>:</h4>
                <div id="result_import_student" class="slimtest1" style="max-height: 300px;position: relative;"></div>
              </div>
              <div class="col-md-12 progress" id="progress_uploadstudent_div" style="display: none;">
                <div class="progress-bar-uploadstudent bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                  aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only"
                    id="txt_progress_uploadstudent"></span></div>
              </div>
              <div class="form-group col-md-12" align="right">
                <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                    class="mdi mdi-upload"></i> <?php echo ucwords(label('upload_btn')); ?></button>
                <button type="button" class="btn btn-outline-danger btn-flat"><i class="mdi mdi-window-close"></i>
                  <?php echo label('m_cancel'); ?></button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="form-group col-md-7 all_score" align="left">
        <label for="cosen_score_all"><?php echo label('record_all_score'); ?>: </label>
        <input type="text" id="cosen_score_all" name="cosen_score_all" class="form-control"> <br>
      </div>
      <div class="form-group col-md-5 all_score" align="left"><br>
        <button type="button" class="btn btn-outline-success btn-block" onclick="update_score_all()"><i
            class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
      </div>
      <div id="div_enroll" class="col-md-12">
        <div class="table-responsive">
          <table id="myTable_enroll" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <?php if ($btn_delete == "1") { ?>
                <th width="10%">
                  <center><?php echo label('manage'); ?></center>
                </th>
                <?php } ?>
                <th width="5%">
                  <center></center>
                </th>
                <th width="25%">
                  <center><?php echo label('m_name'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('m_company'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('learning_status'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('score'); ?></center>
                </th>
              </tr>
            </thead>
          </table>
        </div>
        <p><?php echo label('preNote'); ?>: <?php if ($btn_delete == "1") { ?><button type="button"
            class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
          <b><?php echo label('delete'); ?></b><?php } ?>
        </p>
      </div>
    </div>
    <div id="div_enroll_cancel" class="row">
      <div class="col-md-12">
        <form enctype="multipart/form-data" id="enroll_cancel_form" name="enroll_cancel_form" autocomplete="off"
          method="POST" accept-charset="utf-8" class="form-horizontal p-t-20 row">
          <input type="hidden" id="cos_id_enroll" name="cos_id_enroll">
          <input type="hidden" id="cosen_id_enroll" name="cosen_id_enroll">
          <div class="form-group col-md-12">
            <label for="cosen_cancelnote"><b
                style="color: #FF2D00">*</b><?php echo label('preNote') . label('e_remove'); ?>:</label>
            <textarea name="cosen_cancelnote" class="form-control" id="cosen_cancelnote" required
              style="width: 100%"></textarea>
          </div>
          <div class="form-group col-md-12" align="right">
            <button type="submit" class="btn btn-outline-warning btn-flat pull-left" name="reset" id="action"><i
                class="mdi mdi-account-remove"></i> <?php echo label('e_remove'); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat"
              onclick="display_stylecancel('div_enroll_cancel','div_enroll_main')"><i class="mdi mdi-window-close"></i>
              <?php echo label('m_cancel'); ?></button>
          </div>
        </form>
      </div>
    </div>
    <div id="div_enroll_qiz" class="row">
      <div class="col-md-12" align="right">
        <button type="button" name="back_enroll" id="back_enroll" class="btn btn-outline-danger back_enroll"
          onclick="display_style('div_enroll_qiz','div_enroll_main')"><i class="mdi mdi-keyboard-return"></i>
          <?php echo label('m_previous'); ?></button>
      </div>
      <div class="col-md-12 row">
        <div class="form-group col-md-4" align="left">
          <label for="quiz_lang_test"><b style="color: #FF2D00">*</b><?php echo label('faqlang'); ?>: </label>
          <select class="form-control" onchange="onchange_quizlang(this.value);" id="quiz_lang_test"
            name="quiz_lang_test" style="width: 100%;">
          </select>
        </div>
        <div class="form-group col-md-4" align="left">
          <label for="qiz_id_enroll"><b style="color: #FF2D00">*</b><?php echo label('quiz'); ?>: </label>
          <select class="form-control" required id="qiz_id_enroll" name="qiz_id_enroll" style="width: 100%;">
          </select>
        </div>
        <div class="form-group col-md-4" align="left">
          <label for="qiz_score_all"><b
              style="color: #FF2D00">*</b><?php echo label('saveR') . label('point') . label('r_company'); ?>: </label>
          <input type="text" id="qiz_score_all" name="qiz_score_all" class="form-control"> <br>
          <button type="button" class="btn btn-outline-success btn-block" onclick="qiz_update_score_all()"><i
              class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
        </div>
        <div id="div_enroll" class="col-md-12">
          <hr>
          <div class="table-responsive">
            <table id="myTable_enroll_qiz" width="100%" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="10%"></th>
                  <th width="40%">
                    <center><?php echo label('r_name'); ?></center>
                  </th>
                  <th width="20%">
                    <center><?php echo label('m_company'); ?></center>
                  </th>
                  <th width="10%">
                    <center><?php echo label('status'); ?></center>
                  </th>
                  <th width="20%">
                    <center><?php echo label('score'); ?></center>
                  </th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="tab-pane p-20 card" id="survey" role="tabpanel">
  <div class="card-body">
    <div id="div_survey_main" class="row">
      <div id="div_survey" class="col-md-12">
        <?php if ($btn_add == "1") { ?>
        <button name="add_survey" id="add_survey" class="btn btn-outline-info add_survey float-right"
          onclick="create_div('div_create_survey','div_survey','survey_form')"><i class="mdi mdi-plus-box-outline"></i>
          <?php echo label('create_survey'); ?></button>
        <?php } ?>
        <div class="table-responsive">
          <table id="myTable_cos_id_survey" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="10%">
                  <center><?php echo label('manage'); ?></center>
                </th>
                <th width="10%"></th>
                <th width="15%">
                  <center><?php echo label('faqlang'); ?></center>
                </th>
                <th width="25%">
                  <center><?php echo label('sName'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('r_start_on'); ?></center>
                </th>
                <th width="20%">
                  <center><?php echo label('r_finish_on'); ?></center>
                </th>
              </tr>
            </thead>
          </table>
        </div>
        <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i
              class="mdi mdi-comment-question-outline"></i></button> =
          <b><?php echo label('question'); ?></b><?php if ($btn_update == "1") { ?> , <button type="button"
            class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
          <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_update == "1" && $btn_delete == "1") { ?> ,
          <?php } ?><?php if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i
              class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?>
        </p>
      </div>

      <div id="div_create_survey" style="display: none;">
        <form enctype="multipart/form-data" id="survey_form" name="survey_form" autocomplete="off" method="POST"
          accept-charset="utf-8" class="form-horizontal p-t-20">
          <input type="hidden" id="sv_id" name="sv_id">
          <input type="hidden" id="operation_survey" name="operation_survey" value="Add">
          <input type="hidden" id="course_id_survey" name="course_id_survey">
          <div class="col-md-12 row" style="">
            <div class="col-md-12">
              <button type="button" class="btn btn-outline-danger float-right" type="button"
                onclick="display_style('div_create_survey','div_survey')"><i class="mdi mdi-keyboard-return"></i>
                <?php echo label('m_previous'); ?></button>
              <h3 id="txthead_survey"></h3>
              <hr>
            </div>

            <input type="hidden" id="sv_lang" name="sv_lang">

            <div class="col-md-12 input_survey_eng" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                    <input name="sv_title_eng" type="text" class="form-control" id="sv_title_eng">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><?php echo label('svdesc'); ?>:</label>
                    <textarea name="sv_explanation_eng" id="sv_explanation_eng" class="form-control"
                      rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 input_survey_th" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                  <?php echo label('thailand'); ?></div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                    <input name="sv_title_th" type="text" class="form-control" id="sv_title_th">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><?php echo label('svdesc'); ?>:</label>
                    <textarea name="sv_explanation_th" id="sv_explanation_th" class="form-control" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_survey_jp" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-6 input_jp">
                    <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('sName'); ?>:</label>
                    <input name="sv_title_jp" type="text" class="form-control" id="sv_title_jp">
                  </div>
                  <div class="form-group col-md-12 input_jp">
                    <label class="control-label"><?php echo label('svdesc'); ?>:</label>
                    <textarea name="sv_explanation_jp" id="sv_explanation_jp" class="form-control" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>


            <div class="form-group col-md-12">
              <label class="control-label"><?php echo label('sv_specific'); ?>: </label>

              <div class="row">
                <div class="col-md-6">
                  <label class="control-label"><?php echo label('r_start_on'); ?></label>
                  <div class="row">
                    <div class="col-md-8">
                      <input type="text" id="survey_open" name="survey_open" onchange="caldate('survey_open')"
                        class="form-control survey_open">
                      <input type="hidden" id="survey_open_var" name="survey_open_var">
                    </div>
                    <div class="col-md-4">
                      <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                        data-autoclose="true">
                        <input type="text" id="time_start_survey" name="time_start_survey" class="form-control"
                          value="<?php echo date('H:i', strtotime('00:00')); ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="control-label"><?php echo label('r_finish_on'); ?></label>
                  <div class="row">
                    <div class="col-md-8">
                      <input type="text" id="survey_end" name="survey_end" onchange="caldate('survey_end')"
                        class="form-control survey_end">
                      <input type="hidden" id="survey_end_var" name="survey_end_var">
                    </div>
                    <div class="col-md-4">
                      <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                        data-autoclose="true">
                        <input type="text" id="time_end_survey" name="time_end_survey" class="form-control"
                          value="<?php echo date('H:i', strtotime('23:59')); ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>


            <div class="form-group col-md-4">
              <label class="control-label"><b
                  style="color: #FF2D00">*</b><?php echo label('quessuggestion_status'); ?>:</label>
              <div class="switch">
                <label><?php echo label('no'); ?><input type="checkbox" id="sv_suggestion_status" checked
                    name="sv_suggestion_status" value="1"><span
                    class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
              </div>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label"><b style="color: #FF2D00">*</b><?php echo label('quesStatus'); ?>:</label>
              <div class="switch">
                <label><?php echo label('close'); ?><input type="checkbox" id="sv_status" checked name="sv_status"
                    value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="status_cr"><?php echo label('svtheme'); ?>:</label>
              <select class="form-control select2" id="qn_id" name="qn_id" style="width: 100%;">
              </select>
            </div>

            <div class="col-md-12 progress" id="progress_survey_div" style="display: none;">
              <div class="progress-bar-survey bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_survey"></span>
              </div>
            </div>
            <div class="form-group col-md-12" align="right">
              <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action_survey"
                id="action_survey"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
              <button type="button" class="btn btn-outline-danger btn-flat"
                onclick="display_stylecancel('div_create_survey','div_survey')"><i class="mdi mdi-window-close"></i>
                <?php echo label('m_cancel'); ?></button>
            </div>

          </div>
        </form>
      </div>
    </div>
    <div id="div_survey_detail" style="display: none;">
      <div id="div_sv_survey_detail" class="col-md-12">
        <div class="col-md-12" align="right">
          <button type="button" name="back_survey_detail" id="back_survey_detail"
            class="btn btn-outline-danger back_survey_detail" onclick="btnprevious_survey()"><i
              class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
          <?php if ($btn_add == "1") { ?>
          <button name="import_question" id="import_survey_detail" class="btn btn-outline-info import_survey_detail"
            onclick="create_div('div_import_survey_detail','div_sv_survey_detail','survey_detail_import_form')"><i
              class="mdi mdi-upload"></i> <?php echo ucwords(label('import_data_question')); ?></button>
          <button name="add_survey_detail" id="add_survey_detail" class="btn btn-outline-info add_survey_detail"
            onclick="create_div('div_create_survey_detail','div_sv_survey_detail','survey_detail_form')"><i
              class="mdi mdi-plus-box-outline"></i> <?php echo label('create_question'); ?></button>
          <?php } ?>
        </div>
        <h4 id="sv_name_txt"></h4>
        <hr>
        <div class="table-responsive">
          <table id="myTable_survey_detail" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <?php if ($btn_update == "1" || $btn_delete == "1") { ?>
                <th width="10%">
                  <center><?php echo label('manage'); ?></center>
                </th>
                <?php } ?>
                <th width="10%"></th>
                <th width="40%">
                  <center><?php echo label('questitle'); ?></center>
                </th>
                <th width="40%">
                  <center><?php echo label('squestion'); ?></center>
                </th>
              </tr>
            </thead>
          </table>
        </div>
        <p><?php echo label('preNote'); ?>: <?php if ($btn_update == "1") { ?><button type="button"
            class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
          <b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_update == "1" && $btn_delete == "1") { ?> ,
          <?php } ?><?php if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i
              class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?>
        </p>
      </div>
      <div id="div_import_survey_detail" style="display: none;">

        <div class="col-md-12">
          <button type="button" class="btn btn-outline-danger float-right"
            onclick="display_style('div_import_survey_detail','div_sv_survey_detail')"><i
              class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
          <h3 id="txthead_import_survey_detail"></h3>
          <hr>
        </div>
        <form enctype="multipart/form-data" id="survey_detail_import_form" name="survey_detail_import_form"
          autocomplete="off" method="POST" accept-charset="utf-8" class="form-horizontal p-t-20 row">
          <div class="col-md-6">
            <label for="file_import_survey"><b style="color: #FF2D00">*</b><?php echo 'Excel File'; ?>:</label>
            <input type="file" name="file_import_survey" required id="file_import_survey" class="dropify"
              accept=".xls,.xlsx" />
            <?php echo label('certificate_example') . ": "; ?><a
              href="<?php echo REAL_PATH; ?>/uploads/format/format_import_survey.xlsx"
              download>format_import_survey.xlsx</a>
            <input type="hidden" id="operation_import_survey" name="operation_import_survey" value="Add">
            <input type="hidden" id="sv_id_detail_import" name="sv_id_detail_import">
          </div>

          <div class="col-md-6">
            <h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('result_import'); ?>:</h4>
            <div id="result_import_survey" class="slimtest1" style="max-height: 300px;position: relative;"></div>
          </div>
          <div class="col-md-12 progress" id="progress_importsurvey_div" style="display: none;">
            <div class="progress-bar-importsurvey bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
              aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only"
                id="txt_progress_importsurvey"></span></div>
          </div>
          <div class="form-group col-md-12" align="right">
            <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                class="mdi mdi-upload"></i> <?php echo ucwords(label('upload_btn')); ?></button>
            <button type="button" class="btn btn-outline-danger btn-flat"
              onclick="display_stylecancel('div_import_survey_detail','div_sv_survey_detail')"><i
                class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
          </div>
        </form>
      </div>
      <div id="div_create_survey_detail" style="display: none;" class="col-md-12">

        <div class="col-md-12">
          <button type="button" class="btn btn-outline-danger float-right"
            onclick="display_style('div_create_survey_detail','div_sv_survey_detail')"><i
              class="mdi mdi-keyboard-return"></i> <?php echo label('m_previous'); ?></button>
          <h3 id="txthead_survey_detail"></h3>
          <hr>
        </div>
        <form enctype="multipart/form-data" id="survey_detail_form" name="survey_detail_form" autocomplete="off"
          method="POST" accept-charset="utf-8" class="form-horizontal p-t-20">
          <input type="hidden" id="sv_id_detail" name="sv_id_detail">
          <input type="hidden" id="cos_id_detail" name="cos_id_detail">
          <input type="hidden" id="svde_id" name="svde_id">
          <input type="hidden" id="operation_survey_detail" name="operation_survey_detail" value="Add">
          <div class="col-md-12 row" style="">

            <div class="col-md-12 input_surveydetail_eng" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-us"></i> <?php echo label('english'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-12">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                    <input name="svde_heading_eng" type="text" class="form-control" id="svde_heading_eng">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="svde_detail_eng" id="svde_detail_eng" class="form-control" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_surveydetail_th" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-th"></i>
                  <?php echo label('thailand'); ?></div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-12">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                    <input name="svde_heading_th" type="text" class="form-control" id="svde_heading_th">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="svde_detail_th" id="svde_detail_th" class="form-control" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 input_surveydetail_jp" style="display:none;">
              <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-danger"><i class="flag-icon flag-icon-jp"></i> <?php echo label('japan'); ?>
                </div>
                <div class="ribbon-content row">
                  <div class="form-group col-md-12 input_jp">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('questitle'); ?>:</label>
                    <input name="svde_heading_jp" type="text" class="form-control" id="svde_heading_jp">
                  </div>
                  <div class="form-group col-md-12 input_jp">
                    <label class="control-label"><b
                        style="color: #FF2D00">*</b><?php echo label('squestion'); ?>:</label>
                    <textarea name="svde_detail_jp" id="svde_detail_jp" class="form-control" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-md-12 progress" id="progress_surveydetail_div" style="display: none;">
              <div class="progress-bar-surveydetail bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only"
                  id="txt_progress_surveydetail"></span></div>
            </div>
            <div class="form-group col-md-12" align="right">
              <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i
                  class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
              <button type="button" class="btn btn-outline-danger btn-flat"
                onclick="display_stylecancel('div_create_survey_detail','div_sv_survey_detail')"><i
                  class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
