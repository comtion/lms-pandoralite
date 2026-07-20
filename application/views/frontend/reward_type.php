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
            <p class="loader__label"><?php echo isset($title) ? $title : 'Learning Management System'?></p>
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
                        <b><?php echo label('reward_type'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('reward_type'); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">

                      <div class="row">
                        <div class="col-md-12" align="right">
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
                                <th width="40%" align="center"><?php echo label('name_typereward'); ?></th>
                                <th width="45%" align="center"><?php echo label('detail_typereward'); ?></th>
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
    
    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="rewardtype_form" autocomplete="off" name="rewardtype_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="trw_name"><b style="color: #FF2D00">*</b><?php echo label('name_typereward'); ?>:</label>
                    <input type="text" id="trw_name" name="trw_name" required class="form-control"> 
                  </div>
                  <div class="card form-group">
                      <label class="control-label"><?php echo label('upload_image'); ?></label>
                      <input type="file" name="trw_path" id="trw_path" class="dropify" accept="image/png, image/jpeg, image/gif" />
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label text-right"><?php echo label('detail_typereward'); ?></label>
                    <textarea name="trw_detail" id="trw_detail" rows="3" cols="80"></textarea>
                  </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="trw_id" name="trw_id">
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
        textarea_tinymce('trw_detail');
        function textarea_tinymce(id=''){
            if ($("#"+id).length > 0) {
                tinymce.init({
                    selector: "textarea#"+id,
                    theme: "modern",
                    height: 150,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

                });
            }
        }
        fetch_data(0);
        function fetch_data(page_num=0)
         {
            $('#myTable').DataTable().destroy();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/setting/fetch_rewardtype/',
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

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label('add_typereward'); ?>');
                $('#operation').val("Add");
                $('#rewardtype_form')[0].reset();
                $('#trw_path').dropify();
            });

    $(document).ready(function() {
        $(document).on('submit', '#rewardtype_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_rewardtype",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    
                    if(data=="2"){
                        $('#rewardtype_form')[0].reset();
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
                          fetch_data(page_current);
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


         $(document).on('click', '.delete', function(){
            var trw_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/setting/delete_rewardtype_data",
                    method:"POST",
                    data:{id_delete:trw_id,table_name:"lms_typereward"},
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
                          fetch_data(page_current);
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
            var trw_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_rewardtype",
              method:"POST",
              data:{trw_id:trw_id},
              dataType:"json",
              success:function(data)
              {
                
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label('edit_typereward'); ?>');
                $('#operation').val("Edit");
                $('#rewardtype_form')[0].reset();
                $('#trw_name').val(data.trw_name);
                $(tinymce.get('trw_detail').getBody()).html(data.trw_detail);
                  if(data.trw_path!=""){
                      $(".dropify-clear").trigger("click");
                      var drEvent = $('#trw_path').dropify(
                      {
                          defaultFile: "<?php echo REAL_PATH;?>/uploads/reward/"+data.trw_path
                      });
                      drEvent = drEvent.data('dropify');
                      drEvent.resetPreview();
                      drEvent.settings.defaultFile = "<?php echo REAL_PATH;?>/uploads/reward/"+data.trw_path;
                      drEvent.destroy();
                      drEvent.init();
                  }else{
                      $(".dropify-clear").trigger("click");
                      $('#trw_path').dropify();
                  }  
                $('#trw_id').val(data.trw_id);
              }
            });
          });
    });
    </script>
</body>

</html>