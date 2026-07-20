<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
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
                      <div class="row">
                        <div class="col-md-6">
                            <?php if($com_admin!="CUSTOMER"){ ?>
                                  <select class="form-control" id="com_id_select" name="com_id_select"  style="width: 100%;">
                                          <option value=""><?php echo label('please_com_name'); ?></option>
                                        <?php foreach( $company_select as $company ){ ?>
                                          <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                        <?php } ?>
                                  </select>
                            <?php } ?>
                          </div>
                        <div class="col-md-6" align="right">
                          <?php if($btn_add=="1"){ ?>
                            <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('add').label('ManageMainmenu'); ?></button>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                              <tr>
                                <th width="5%"></th>
                                <th width="15%" align="center"><?php echo 'Icon'; ?></th>
                                <th width="30%" align="center"><?php echo label('txt_a'); ?></th>
                                <th width="30%" align="center"><?php echo label('txt_b'); ?></th>
                                <th width="10%" align="center"><?php echo label('status'); ?></th>
                                <th width="10%" align="center"><?php echo label('action'); ?></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                            <?php if($com_admin!="CUSTOMER"){ ?>
                                  <select class="form-control" id="com_id_select_sort" name="com_id_select_sort"  style="width: 100%;">
                                          <option value=""><?php echo label('please_com_name'); ?></option>
                                        <?php foreach( $company_select as $company ){ ?>
                                          <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                        <?php } ?>
                                  </select>
                            <?php } ?>
                          </div>
                        <div class="col-md-6" align="right">
                          <?php if($btn_add=="1"){ ?>
                            <button name="add_button_sort" id="add_button_sort" class="btn btn-outline-info add_button_sort" data-toggle="modal" data-target="#modal-sortcourse"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('add').label('sort_course'); ?></button>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable_sort" width="100%" class="table table-bordered">
                            <thead>
                              <tr>
                                <th width="15%"></th>
                                <th width="75%" align="center"><?php echo label('ceCname'); ?></th>
                                <th width="10%" align="center"><?php echo label('action'); ?></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
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
              <form method="post" id="mainmenu_form" autocomplete="off" name="mainmenu_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <?php if($com_admin!="CUSTOMER"){ ?>
                                <select class="form-control" required id="com_id" name="com_id"  style="width: 100%;">
                                        <option value=""><?php echo label('please_com_name'); ?></option>
                                      <?php foreach( $company_select as $company ){ ?>
                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                      <?php } ?>
                                </select>
                                <?php }else{ ?>
                                    <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly>
                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                <?php } ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mm_txt_th1"><b style="color: #FF2D00">*</b><?php echo label('txt_a')." TH"; ?>:</label>
                    <input type="text" id="mm_txt_th1" name="mm_txt_th1" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mm_txt_en1"><b style="color: #FF2D00">*</b><?php echo label('txt_a')." EN"; ?>:</label>
                    <input type="text" id="mm_txt_en1" name="mm_txt_en1" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mm_txt_th2"><?php echo label('txt_b')." TH"; ?>:</label>
                    <input type="text" id="mm_txt_th2" name="mm_txt_th2" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mm_txt_en2"><?php echo label('txt_b')." EN"; ?>:</label>
                    <input type="text" id="mm_txt_en2" name="mm_txt_en2" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mm_icon"><b style="color: #FF2D00">*</b><?php echo 'Icon'; ?>:</label>
                    <input type="text" id="mm_icon" name="mm_icon" required="" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="checkbox checkbox-success">
                      <input type="checkbox" id="mm_status" name="mm_status" value="1">
                      <label for="mm_status"><?php echo label('status'); ?></label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="mm_id" name="mm_id">
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
    <div class="modal fade bs-example-modal-lg" id="modal-sortcourse" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabelcc">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="sortcourse_form" autocomplete="off" name="sortcourse_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <?php if($com_admin!="CUSTOMER"){ ?>
                                <select class="form-control" required id="com_id_sort" name="com_id_sort"  style="width: 100%;">
                                        <option value=""><?php echo label('please_com_name'); ?></option>
                                      <?php foreach( $company_select as $company ){ ?>
                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                      <?php } ?>
                                </select>
                                <?php }else{ ?>
                                    <input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly>
                                    <input type="hidden" id="com_id_sort" name="com_id_sort" value="<?php echo $com_id; ?>">
                                <?php } ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ceCname'); ?>:</label>
                    <select class="form-control" required id="cos_id" name="cos_id"  style="width: 100%;">
                    </select>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation_sort" name="operation_sort" value="Add">
              <input type="hidden" id="coss_id" name="coss_id">
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
    <script src="<?php echo REAL_PATH; ?>/assets/js/userCode.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/course.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        function fetch_data(com_id='',page_num=0)
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
                    url : '<?=base_url()?>index.php/setting/fetch_mainmenu/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
                      lang: "<?php echo $lang; ?>"
                    }
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
                          
        function fetch_data_sort(com_id='',page_num=0)
         {
            $('#myTable_sort').DataTable().destroy();
            var table = $('#myTable_sort').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_sort", message);
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
                    url : '<?=base_url()?>index.php/setting/fetch_sort/',
                    type : 'GET',
                    data : {com_id:com_id}
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

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label('add').label('ManageMainmenu'); ?>');
                $('#operation').val("Add");
                $('#mainmenu_form')[0].reset();
            });
           $('#add_button_sort').click(function(){
                $('#myLargeModalLabelcc').text('<?php echo label('add').label('sort_course'); ?>');
                $('#operation_sort').val("Add");
                $('#sortcourse_form')[0].reset();
            });

    $('#com_id_select').on("change", function() {
       var com_id = $('#com_id_select').val();
       if(com_id!=""){
                          var table = $('#myTable').DataTable();
                          var info = table.page.info();
                          var length = info.pages;
                          var page_current = info.page;
                          fetch_data(com_id,page_current);
       }else{
        $('#myTable').DataTable().destroy();
       }
    });

    $('#com_id_sort').on("change", function() {
      var com_id = $('#com_id_sort').val();

            $.ajax({
              url:"<?=base_url()?>index.php/workgroup/recheckcosid",
              method:"POST",
              data:{com_id:com_id},
              success:function(data)
              {
                console.log(data);
                $('#cos_id').html(data);
              }
            });
    });

    $('#com_id_select_sort').on("change", function() {
       var com_id = $('#com_id_select_sort').val();
       if(com_id!=""){
                          var table_sort = $('#myTable_sort').DataTable();
                          var info_sort = table_sort.page.info();
                          var length_sort = info_sort.pages;
                          var page_current_sort = info_sort.page;
                          fetch_data_sort(com_id,page_current_sort);

            $.ajax({
              url:"<?=base_url()?>index.php/setting/check_countsortcos",
              method:"POST",
              data:{com_id:com_id},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                if(parseInt(data.count_coss) >= 4){
                  document.getElementById("add_button_sort").disabled = true;
                }else{
                  document.getElementById("add_button_sort").disabled = false;
                }
              }
            });
       }else{
        $('#myTable_sort').DataTable().destroy();
       }
    });

    $(document).ready(function() {
        $(document).on('submit', '#mainmenu_form', function(event){
              var com_id = $('#com_id_select').val();
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_mainmenu",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        $('#mainmenu_form')[0].reset();
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
                          fetch_data(com_id,page_current);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("com_msg_duplicate"); ?>',
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

        $(document).on('submit', '#sortcourse_form', function(event){
              var com_id = $('#com_id_select_sort').val();
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_sortcourse",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        $('#sortcourse_form')[0].reset();
                        $('#modal-sortcourse').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var table_sort = $('#myTable_sort').DataTable();
                          var info_sort = table_sort.page.info();
                          var length_sort = info_sort.pages;
                          var page_current_sort = info_sort.page;
                          fetch_data_sort(com_id,page_current_sort);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("com_msg_duplicate"); ?>',
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

         $(document).on('click', '.delete_sort', function(){
            var coss_id = $(this).attr("id");
            var com_id = $('#com_id_select_sort').val();
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
                    url:"<?=base_url()?>index.php/course/delete_data",
                    method:"POST",
                    data:{id_delete:coss_id,table_name:"lms_cos_sort",field:"coss_id"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var table_sort = $('#myTable_sort').DataTable();
                          var info_sort = table_sort.page.info();
                          var length_sort = info_sort.pages;
                          var page_current_sort = info_sort.page;
                          fetch_data_sort(com_id,page_current_sort);
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
         $(document).on('click', '.delete', function(){
            var mm_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/setting/delete_menu_data",
                    method:"POST",
                    data:{id_delete:mm_id,table_name:"lms_mainmenu"},
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
                          fetch_data(com_id,page_current);
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
            var mm_id = $(this).attr("id");
            console.log(mm_id);
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_mainmenu",
              method:"POST",
              data:{mm_id:mm_id},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label('edit').label('ManageMainmenu'); ?>');
                $('#operation').val("Edit");
                $('#mainmenu_form')[0].reset();
                $('#mm_txt_th1').val(data.mm_txt_th1);
                $('#mm_txt_th2').val(data.mm_txt_th2);
                $('#mm_txt_en1').val(data.mm_txt_en1);
                $('#mm_txt_en2').val(data.mm_txt_en2);
                $('#mm_icon').val(data.mm_icon);
                $('#com_id').val(data.com_id);
                if(data.mm_status=="1"){
                  document.getElementById("mm_status").checked = true;
                }
                $('#mm_id').val(data.mm_id);
              }
            });
          });
    });
    </script>
</body>

</html>