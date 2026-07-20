<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- Color picker plugins css -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/jquery-asColorPicker-master/dist/css/asColorPicker.css" rel="stylesheet">
    <style type="text/css">
      
        .asColorPicker-gradient-preview {
          position: static;
          width: 200px;
          border: 1px solid rgba(0, 0, 0, .05);
        }
    </style>
    <script type="text/javascript">
      
           function chk_chkbox(name,tc_id){
                var value_chk = 0;
                var field_sql = "";
                var remember = document.getElementById(name+'_'+tc_id);
                if (remember.checked){
                    value_chk = 1;
                }
                $.ajax({
                  url:"<?=base_url()?>index.php/coursetype/chk_chkbox",
                  method:"POST",
                  data:{field_sql:name,value_chk:value_chk,tc_id_chk:tc_id},
                  dataType:"json",
                  success:function(data)
                  {            
                  }
                });
           }
    </script>
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
                      <div class="col-md-12" align="right">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button mb-4" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('createcoursetype'); ?></button>
                        <?php } ?>
                      </div>
                      <?php if($com_admin!="CUSTOMER"){ 
                                foreach($company_select as $company ) { 
                        ?>
                                <div class="card">
                                    <div class="card-header"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?></div>
                                    <div class="card-body">
                                          <div class="table-responsive">
                                              <table width="100%" class="myTable table table-bordered table-striped">
                                                <thead>
                                                  <tr>
                                                    <th width="10%" align="center"><?php echo label('manage'); ?></th>
                                                    <th width="5%"></th>
                                                    <th width="15%" align="center"><?php echo label('ceCtype')." EN"; ?></th>
                                                    <th width="15%" align="center"><?php echo label('ceCtype')." TH"; ?></th>
                                                    <th width="10%" align="center"><?php echo label('lesson'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('quiz'); ?></th>
                                                    <th width="15%" align="center"><?php echo label('survey'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('student_enroll'); ?></th>
                                                    <th width="20%" align="center"><?php echo label('lesson_file'); ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $num = 1;
                                                  if(isset($data_fetch)){
                                                    foreach ($data_fetch as $key => $value) { 
                                                        if($value['com_id']==$company['com_id']){?>
                                                    <tr>
                                                      <td>
                                                        <?php if($btn_update=="1"){ ?>
                                                          <button type="button" name="update" id="<?php echo $value['tc_id']; ?>" title="Edit" class="btn btn-warning btn-xs update" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-lead-pencil"></i></button>
                                                        <?php } ?>
                                                        <?php if($btn_delete=="1"){ ?>
                                                          <button type="button" name="delete" id="<?php echo $value['tc_id']; ?>" class="btn btn-danger btn-xs delete" title="Delete"><i class="mdi mdi-window-close"></i></button>
                                                        <?php } ?>
                                                      </td>
                                                      <td><?php echo $num; ?></td>
                                                      <td><?php echo $value['tc_name_en']; ?></td>
                                                      <td><?php echo $value['tc_name_th']; ?></td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_lesson_<?php echo $value['tc_id'];?>" name="tc_lesson_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_lesson","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_lesson']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_lesson_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_pretest_<?php echo $value['tc_id'];?>" name="tc_pretest_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_pretest","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_pretest']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_pretest_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_questionnaire_<?php echo $value['tc_id'];?>" name="tc_questionnaire_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_questionnaire","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_questionnaire']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_questionnaire_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_student_enroll_<?php echo $value['tc_id'];?>" name="tc_student_enroll_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_student_enroll","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_student_enroll']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_student_enroll_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_doccos_<?php echo $value['tc_id'];?>" name="tc_doccos_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_doccos","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_doccos']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_doccos_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                    </tr>
                                                <?php   $num++;
                                                        }
                                                    }
                                                  } 
                                                  ?>
                                                </tbody>
                                              </table>
                                          </div>
                                    </div>
                                </div>
                      <?php     }
                            }else{ ?>
                                <div class="table-responsive">
                                              <table width="100%" class="myTable table table-bordered table-striped">
                                                <thead>
                                                  <tr>
                                                    <th width="10%"></th>
                                                    <th width="20%" align="center"><?php echo label('ceCtype')." EN"; ?></th>
                                                    <th width="20%" align="center"><?php echo label('ceCtype')." TH"; ?></th>
                                                    <th width="10%" align="center"><?php echo label('lesson'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('quiz'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('survey'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('student_enroll'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('lesson_file'); ?></th>
                                                    <th width="10%" align="center"><?php echo label('manage'); ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $num = 1;
                                                  if(isset($data_fetch)){
                                                    foreach ($data_fetch as $key => $value) { 
                                                        if($value['com_id']==$com_id){?>
                                                    <tr>
                                                      <td><?php echo $num; ?></td>
                                                      <td><?php echo $value['tc_name_en']; ?></td>
                                                      <td><?php echo $value['tc_name_th']; ?></td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_lesson_<?php echo $value['tc_id'];?>" name="tc_lesson_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_lesson","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_lesson']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_lesson_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_pretest_<?php echo $value['tc_id'];?>" name="tc_pretest_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_pretest","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_pretest']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_pretest_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_questionnaire_<?php echo $value['tc_id'];?>" name="tc_questionnaire_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_questionnaire","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_questionnaire']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_questionnaire_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_student_enroll_<?php echo $value['tc_id'];?>" name="tc_student_enroll_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_student_enroll","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_student_enroll']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_student_enroll_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <div class="checkbox checkbox-success">
                                                            <input type="checkbox" id="tc_doccos_<?php echo $value['tc_id'];?>" name="tc_doccos_<?php echo$value['tc_id'];?>" onchange='chk_chkbox("tc_doccos","<?php echo$value['tc_id'];?>")' value="1" <?php if($value['tc_doccos']=="1"){ echo "checked";} ?>>
                                                            <label for="tc_doccos_<?php echo $value['tc_id'];?>"></label>
                                                        </div> 
                                                      </td>
                                                      <td>
                                                        <button type="button" name="update" id="<?php echo $value['tc_id']; ?>" title="Edit" class="btn btn-warning btn-xs update" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-lead-pencil"></i></button>
                                                        <button type="button" name="delete" id="<?php echo $value['tc_id']; ?>" class="btn btn-danger btn-xs delete" title="Delete"><i class="mdi mdi-window-close"></i></button>
                                                      </td>
                                                    </tr>
                                                <?php   $num++;
                                                        }
                                                    }
                                                  } 
                                                  ?>
                                                </tbody>
                                              </table>
                                          </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
            </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="coursetype_form" autocomplete="off" name="coursetype_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <?php if($com_admin!="CUSTOMER"){ ?>
                                <select class="form-control select2" required id="com_id" name="com_id"  style="width: 100%;">
                                </select>
                                <?php }else{ ?>
                                    <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly>
                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                <?php } ?>
                  </div>
                </div>
                <div class="col-md-6  m-b-30"><!-- 
                    <div class="example">
                      <h5 class="box-title"><?php echo label('ctype_color'); ?>:</h5>
                      <input type="text" name="tc_color" id="tc_color" required class="complex-colorpicker form-control" value="#398bf7" />
                    </div> -->
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tc_name_en"><b style="color: #FF2D00">*</b><?php echo label('ceCtype')." EN"; ?>:</label>
                    <input type="text" id="tc_name_en" name="tc_name_en" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tc_name_th"><b style="color: #FF2D00">*</b><?php echo label('ceCtype')." TH"; ?>:</label>
                    <input type="text" id="tc_name_th" name="tc_name_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tc_courselearner"><b style="color: #FF2D00">*</b><?php echo label('course_type_details'); ?>:</label>
                    <div class="switch">
                        <label><?php echo label('no'); ?><input type="checkbox"  id="tc_courselearner" name="tc_courselearner" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="tc_id" name="tc_id">
              <input type="hidden" id="tc_color" name="tc_color">
              <div class="modal-footer">
                  <input type="submit" name="action" id="action" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('saveR'); ?>" />
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
        <!-- Color Picker Plugin JavaScript -->
        <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-asColor/dist/jquery-asColor.js"></script>
        <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-asGradient/dist/jquery-asGradient.js"></script>
        <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();
    $('.select2').select2();

        $(".complex-colorpicker").asColorPicker({
            mode: 'complex'
        });
           $('#add_button').click(function(){
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("createcoursetype"); ?>');
                $('#coursetype_form')[0].reset();
                $('#operation').val("Add");
                <?php if($com_admin=="com_central"){ ?>
                  $.ajax({
                        url: '<?=base_url()?>index.php/querydata/recheckcompany',
                        type: 'POST',
                        data:{com_id:''},
                        success: function(data_company){
                          $('#com_id').html(data_company);
                          $("#com_id").val($("#com_id option:first").val());
                        }
                  });
                <?php }?>
            });
    $(document).ready(function() {
        $('.myTable').DataTable({
          
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
        });

            $(document).on('submit', '#coursetype_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/coursetype/insert_coursetype",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#coursetype_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("dep_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#dep_name").val("");
                            document.getElementById("dep_name").focus();
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

         $(document).on('click', '.delete', function(){
            var tc_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "<?php echo label('wg_delete'); ?>",   
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/coursetype/delete_coursetype_data",
                    method:"POST",
                    data:{tc_id_delete:tc_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
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

          $(document).on('click', '.update', function(){
            var tc_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/coursetype/update_coursetype_data",
              method:"POST",
              data:{tc_id_update:tc_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("editcoursetype"); ?>');
                $('#coursetype_form')[0].reset();
                $('#operation').val("Edit");
                <?php if($com_admin=="com_central"){ ?>
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
                $('#tc_name_th').val(data.tc_name_th); 
                $('#tc_name_en').val(data.tc_name_en); 
                $('#tc_id').val(data.tc_id);   
                $('#tc_color').val(data.tc_color);
                if(data.tc_courselearner=="1"){
                  document.getElementById('tc_courselearner').checked = true;
                }else{
                  document.getElementById('tc_courselearner').checked = false;
                }
              }
            });
            
          });


    });
    </script>
</body>

</html>