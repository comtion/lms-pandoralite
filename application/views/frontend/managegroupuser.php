<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<?php 
            $widthdivv = 0;
            if(!isMobile()){ 
              $widthdivv = 240;
            }else{
              $widthdivv = 60;
            }
?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
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
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create_managegroupuser'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="25%"><center><?php echo label('ug_name')." TH"; ?></center></th>
                                <th width="25%"><center><?php echo label('ug_name')." EN"; ?></center></th>
                                <?php if($user['ug_for']=="com_central"){?><th width="15%"><center><?php echo label('ug_for'); ?></center></th><?php } ?>
                                <th width="15%"><center><?php echo label('m_updatedate'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-account-key"></i></button> = <b><?php echo label('m_permission_ug'); ?></b><?php if($btn_update=="1"){ ?> , <button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"&&$user['u_id']=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
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
              <form method="post" id="groupuser_form" autocomplete="off" name="groupuser_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ug_name_th"><b style="color: #FF2D00">*</b><?php echo label('ug_name')." TH"; ?>:</label>
                    <input type="text" id="ug_name_th" name="ug_name_th" class="form-control" required> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ug_name_en"><b style="color: #FF2D00">*</b><?php echo label('ug_name')." EN"; ?>:</label>
                    <input type="text" id="ug_name_en" name="ug_name_en" class="form-control" required> 
                  </div>
                </div>

                <div class="col-md-4">
                  <?php if($user['ug_for']=="com_central"){?>
                  <div class="form-group">
                    <label for="ug_for"><b style="color: #FF2D00">*</b><?php echo label('ug_for'); ?>:</label>
                    <select class="form-control" id="ug_for" name="ug_for" onchange="onchk_ugfor(this.value)"  style="width: 100%;">
                      <option selected="selected" value="com_central"><?php echo label('com_central'); ?></option>
                      <option value="com_associated"><?php echo label('com_associated'); ?></option>
                    </select>
                  </div>
                  <?php }else{ ?>
                    <input type="hidden" id="ug_for" name="ug_for" value="com_associated">
                  <?php } ?>
                </div>
                
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="ug_viewdata"><b style="color: #FF2D00">*</b><?php echo label('ug_viewdata'); ?>:</label>
                    <select class="form-control" required id="ug_viewdata" name="ug_viewdata">
                        <option value="1" selected><?php echo label('ug_viewdata_a'); ?></option>
                        <option value="2"><?php echo label('ug_viewdata_b'); ?></option>
                        <option value="3"><?php echo label('ug_viewdata_c'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="fd_id"><b style="color: #FF2D00">*</b><?php echo label('data_indashboard'); ?>:</label>
                    <select class="form-control select2" style="width: 100%" required id="fd_id" name="fd_id[]"  multiple="multiple">
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="Is_admin"><b style="color: #FF2D00">*</b><?php echo label('Is_admin'); ?>:</label>
                    <div class="switch">
                        <label><?php echo label('no'); ?><input type="checkbox"  id="Is_admin" name="Is_admin" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ug_approve"><b style="color: #FF2D00">*</b><?php echo label('ug_approve'); ?>:</label>
                    <div class="switch">
                        <label><?php echo label('no'); ?><input type="checkbox"  id="ug_approve" name="ug_approve" value="1" checked><span class="lever switch-col-indigo"></span><?php echo label('yes'); ?></label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="ug_id" name="ug_id">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade bs-example-modal-lg" id="modal-license" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title mt-0" id="myLargeModalLabel"><i class="mdi mdi-account-key"></i> <?php echo label('m_permission_ug'); ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                  <div class="table-responsive" align="center">
                    <table id="datatable" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <td align="center" width="10%"><?php echo label('m_number'); ?></td>
                          <td align="center" width="30%"><?php echo label('m_menu'); ?></td>
                          <td align="center" width="10%"><?php echo label('m_select_all'); ?></td>
                          <td align="center" width="10%">
                            <?php echo label('m_enable'); ?><br>
                            <div class="checkbox checkbox-success">
                              <input type="checkbox" id="chkcolall_view" class="chkcolall_view checkboxheader" name="chkcolall_view" onchange='chk_chkbox_allcol("rgu_view")' value="1">
                              <label for="chkcolall_view"></label>
                            </div>
                          </td>
                          <td align="center" width="10%">
                            <?php echo label('m_add'); ?><br>
                            <div class="checkbox checkbox-success">
                              <input type="checkbox" id="chkcolall_add" class="chkcolall_add checkboxheader" name="chkcolall_add" onchange='chk_chkbox_allcol("rgu_add")' value="1">
                              <label for="chkcolall_add"></label>
                            </div>
                          </td>
                          <td align="center" width="10%">
                            <?php echo label('m_edit'); ?><br>
                            <div class="checkbox checkbox-success">
                              <input type="checkbox" id="chkcolall_edit" class="chkcolall_edit checkboxheader" name="chkcolall_edit" onchange='chk_chkbox_allcol("rgu_edit")' value="1">
                              <label for="chkcolall_edit"></label>
                            </div>
                          </td>
                          <td align="center" width="10%">
                            <?php echo label('m_del'); ?><br>
                            <div class="checkbox checkbox-success">
                              <input type="checkbox" id="chkcolall_del" class="chkcolall_del checkboxheader" name="chkcolall_del" onchange='chk_chkbox_allcol("rgu_del")' value="1">
                              <label for="chkcolall_del"></label>
                            </div>
                          </td>
                          <td align="center" width="10%">
                            <?php echo label('m_export'); ?><br>
                            <div class="checkbox checkbox-success">
                              <input type="checkbox" id="chkcolall_print" class="chkcolall_print checkboxheader" name="chkcolall_print" onchange='chk_chkbox_allcol("rgu_print")' value="1">
                              <label for="chkcolall_print"></label>
                            </div>
                          </td>
                        </tr>
                      </thead>
                      <tbody id="load_detailgroup">
                        
                      </tbody>
                    </table>
                  </div>
                  <input type="hidden" id="ug_id" name="ug_id">
              </div>
              <div class="modal-footer" align="center">
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
              </div>
          </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
<!--     <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/popper.min.js"></script> -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <!--stickey kit -->
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();
    $('.select2').select2();
    $.fn.dataTable.ext.errMode = "none";
    fetch_data_usergroup(0);
      function fetch_data_usergroup(page_num)
         {
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
                    url: '<?=base_url()?>index.php/manage/fetch_detail_usergroup/',
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
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
             
                  function onchk_ugfor(value,val_active){
                      $("#ug_viewdata").html('');
                      if(value=="com_central"){
                        $('#ug_viewdata').append('<option value="1"><?php echo label('ug_viewdata_a'); ?></option>');
                        $('#ug_viewdata').append('<option value="2"><?php echo label('ug_viewdata_b'); ?></option>');
                        $('#ug_viewdata').append('<option value="3"><?php echo label('ug_viewdata_c'); ?></option>');
                      }else{
                        $('#ug_viewdata').append('<option value="2"><?php echo label('ug_viewdata_b'); ?></option>');
                        $('#ug_viewdata').append('<option value="3"><?php echo label('ug_viewdata_c'); ?></option>');
                      }
                      if(val_active==""){
                          $("#ug_viewdata").val($("#ug_viewdata option:first").val());
                      }else{
                          $("#ug_viewdata").val(val_active);
                      }
                  }             
            function chk_chkbox_allcol(field){

                var ug_id = $('#ug_id').val();
                var mode = "";
                var field_sql = "";
                if(field=="rgu_view"){
                  mode = "chkcolall_view";
                }else if(field=="rgu_add"){
                  mode = "chkcolall_add";
                }else if(field=="rgu_edit"){
                  mode = "chkcolall_edit";
                }else if(field=="rgu_del"){
                  mode = "chkcolall_del";
                }else{
                  mode = "chkcolall_print";
                }
                var value_chk = 0;
                $('.chkcol_'+field).prop('checked',false);
                var remember = document.getElementById(mode);
                if (remember.checked){
                    value_chk = 1;
                    $('.chkcol_'+field).prop('checked',true);
                }

              var $checkboxheader = $('.checkboxheader');
              var countCheckedcheckboxheader = $checkboxheader.filter(':checked').length;
              var count_menu = 5;

                  if(count_menu==countCheckedcheckboxheader){
                    $('.chkall_row').prop('checked',true);
                  }else{
                    $('.chkall_row').prop('checked',false);
                  }
                      $.ajax({
                        url:"<?=base_url()?>index.php/manage/chk_chkboxcol_groupuser",
                        method:"POST",
                        data:{field_sql_ug:field,value_chk_ug:value_chk,ug_idonrole_ug:ug_id},
                        dataType:"json",
                        success:function(data_boxcol)
                        {         
                        }
                      }); 
            }
            function chk_chkbox(name,mu_id,ug_id){
                var value_chk = 0;
                var field_sql = "";
                var remember = document.getElementById(name+'_'+mu_id);
                if (remember.checked){
                    value_chk = 1;
                }
                if(name=="chkrowall"){
                    if(value_chk==1){
                      $('.chkrow_'+mu_id).prop('checked',true);
                    }else{
                      $('.chkrow_'+mu_id).prop('checked',false);
                    }
                    field_sql = "chkrowall";
                    var arr_field = ['rgu_view','rgu_add','rgu_edit','rgu_del','rgu_print'];
                    for (i = 0; i < arr_field.length; i++) { 
                      $.ajax({
                        url:"<?=base_url()?>index.php/manage/chk_chkbox_groupuser",
                        method:"POST",
                        data:{field_sql_ug:arr_field[i],value_chk_ug:value_chk,ug_idonrole_ug:ug_id,mu_idonrole_ug:mu_id},
                        dataType:"json",
                        success:function(data)
                        {
                                         
                        }
                      });
                    }
                }else{
                    if(name=="chkenable"){
                        field_sql = "rgu_view";
                    }else if(name=="chkadd"){
                        field_sql = "rgu_add";
                    }else if(name=="chkedit"){
                        field_sql = "rgu_edit";
                    }else if(name=="chkdel"){
                        field_sql = "rgu_del";
                    }else if(name=="chkprint"){
                        field_sql = "rgu_print";
                    }
                    $.ajax({
                      url:"<?=base_url()?>index.php/manage/chk_chkbox_groupuser",
                      method:"POST",
                      data:{field_sql_ug:field_sql,value_chk_ug:value_chk,ug_idonrole_ug:ug_id,mu_idonrole_ug:mu_id},
                      dataType:"json",
                      success:function(data)
                      {
                                       
                      }
                    });
                }
								var $chkrow = $('.chkrow_'+mu_id);
								var countCheckedchkrow = $chkrow.filter(':checked').length;
								if(countCheckedchkrow==5){
									$('#chkrowall_'+mu_id).prop('checked',true);
								}else{
									$('#chkrowall_'+mu_id).prop('checked',false);
								}
								checkAllFunc();
            }
						function checkAllFunc() {


              var $chkcol_rgu_view = $('.chkcol_rgu_view');
              var countCheckedchkcol_rgu_view = $chkcol_rgu_view.filter(':checked').length;
              var $chkcol_rgu_add = $('.chkcol_rgu_add');
              var countCheckedchkcol_rgu_add = $chkcol_rgu_add.filter(':checked').length;
              var $chkcol_rgu_edit = $('.chkcol_rgu_edit');
              var countCheckedchkcol_rgu_edit = $chkcol_rgu_edit.filter(':checked').length;
              var $chkcol_rgu_del = $('.chkcol_rgu_del');
              var countCheckedchkcol_rgu_del = $chkcol_rgu_del.filter(':checked').length;
              var $chkcol_rgu_print = $('.chkcol_rgu_print');
              var countCheckedchkcol_rgu_print = $chkcol_rgu_print.filter(':checked').length;
              var count_menu = $('#count_menu').val();
                  if(count_menu==countCheckedchkcol_rgu_print){
                    $('.chkcolall_print').prop('checked',true);
                  }else{
                    $('.chkcolall_print').prop('checked',false);
                  }

                  if(count_menu==countCheckedchkcol_rgu_view){
                    $('.chkcolall_view').prop('checked',true);
                  }else{
                    $('.chkcolall_view').prop('checked',false);
                  }

                  if(count_menu==countCheckedchkcol_rgu_add){
                    $('.chkcolall_add').prop('checked',true);
                  }else{
                    $('.chkcolall_add').prop('checked',false);
                  }

                  if(count_menu==countCheckedchkcol_rgu_edit){
                    $('.chkcolall_edit').prop('checked',true);
                  }else{
                    $('.chkcolall_edit').prop('checked',false);
                  }

                  if(count_menu==countCheckedchkcol_rgu_del){
                    $('.chkcolall_del').prop('checked',true);
                  }else{
                    $('.chkcolall_del').prop('checked',false);
                  }
						}
           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("create_managegroupuser"); ?>');
                $('#groupuser_form')[0].reset();
                $('#operation').val("Add");

                $("#modal-default").modal({backdrop: false});

                $.ajax({
                  url: '<?=base_url()?>index.php/manage/rechk_funcdashboard',
                  type: 'POST',
                  data:{ug_id:''},
                  success: function(data_func){
                      $('#fd_id').html(data_func);
                  }
                });
                
                <?php if($user['ug_for']!="com_central"){?>
                  onchk_ugfor('<?php echo $user['ug_for']; ?>');
                <?php } ?>
            });
    $(document).ready(function() {
          $(document).on('submit', '#groupuser_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/insert_groupuser",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#groupuser_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var table = $('#myTable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data_usergroup(page_current);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("ug_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#ug_name_th").val("");
                            $("#ug_name_en").val("");
                            document.getElementById("ug_name_th").focus();
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
            var ug_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_groupuser_data",
                    method:"POST",
                    data:{ug_id_delete:ug_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var table = $('#myTable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data_usergroup(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
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
            var ug_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/manage/update_groupuser_data",
              method:"POST",
              data:{ug_id_update:ug_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("edit_managegroupuser"); ?>');
                $('#groupuser_form')[0].reset();
                $('#operation').val("Edit");
                $('#ug_name_th').val(data.ug_name_th);
                $('#ug_name_en').val(data.ug_name_en);
                $('#ug_for').val(data.ug_for);
                onchk_ugfor(data.ug_for,data.ug_viewdata);
                $('#ug_id').val(data.ug_id);  
                $('#ug_viewdata').val(data.ug_viewdata);     
                if(data.Is_admin=="1"){
                  document.getElementById('Is_admin').checked = true;
                }else{
                  document.getElementById('Is_admin').checked = false;
                }
                if(data.ug_approve=="1"){
                  document.getElementById('ug_approve').checked = true;
                }else{
                  document.getElementById('ug_approve').checked = false;
                }

                $.ajax({
                  url: '<?=base_url()?>index.php/manage/rechk_funcdashboard',
                  type: 'POST',
                  data:{ug_id:data.ug_id},
                  success: function(data_func){
                      $('#fd_id').html(data_func);
                  }
                });
              }
            });
            
          });
          var widthdivv = '<?php echo $widthdivv; ?>';
          $(document).on('click', '.license', function(){
            var ug_id = $(this).attr("id");
            $("#modal-license").modal({backdrop: false});
            $('#ug_id').val(ug_id);
            // $.ajax({
            //   url: '<?=base_url()?>index.php/manage/rechk_headcol',
            //   type: 'POST',
            //   data:{ug_id:ug_id},
            //   dataType:"json",
            //   success: function(data){
            //     if(data.countmenu==data.rgu_print){
            //       $('.chkcolall_print').prop('checked',true);
            //     }else{
            //       $('.chkcolall_print').prop('checked',false);
            //     }

            //     if(data.countmenu==data.rgu_view){
            //       $('.chkcolall_view').prop('checked',true);
            //     }else{
            //       $('.chkcolall_view').prop('checked',false);
            //     }

            //     if(data.countmenu==data.rgu_add){
            //       $('.chkcolall_add').prop('checked',true);
            //     }else{
            //       $('.chkcolall_add').prop('checked',false);
            //     }

            //     if(data.countmenu==data.rgu_edit){
            //       $('.chkcolall_edit').prop('checked',true);
            //     }else{
            //       $('.chkcolall_edit').prop('checked',false);
            //     }

            //     if(data.countmenu==data.rgu_del){
            //       $('.chkcolall_del').prop('checked',true);
            //     }else{
            //       $('.chkcolall_del').prop('checked',false);
            //     }
            //   }
            // });
            $.ajax({
              url: '<?=base_url()?>index.php/manage/loaddetailgroupuser',
              type: 'POST',
              data:{ug_id:ug_id},
              success: function(data){
                $('#load_detailgroup').html(data);
								checkAllFunc();
              }
            });
          });
    });
    </script>
</body>

</html>
