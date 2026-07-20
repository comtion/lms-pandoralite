<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
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
                        <b><?php echo label('ManageTestimonials'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('ManageTestimonials'); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12" align="right">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('AddTestimonials'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="10%"></th>
                                <th width="40%" align="center"><?php echo label('qr_typefile1'); ?></th>
                                <th width="20%" align="center"><?php echo label('questitle'); ?></th>
                                <th width="20%" align="center"><?php echo label('com_createdate'); ?></th>
                                <th width="10%" align="center"><?php echo label('action'); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $num = 1;
                              if(isset($data_fetch)){
                                foreach ($data_fetch as $key => $value) { ?>
                                <tr>
                                  <td><?php echo $num; ?></td>
                                  <td><img width="60%" src="../uploads/brand/<?php echo $value['tim_file']; ?>"></td>
                                  <td><?php echo $value['tim_title']; ?></td>
                                  <td><?php echo $value['tim_moddate']; ?></td>
                                  <td>
                                    <?php if($btn_update=="1"){ ?>
                                      <button type="button" name="update" id="<?php echo $value['tim_id']; ?>" title="Edit" class="btn btn-warning btn-xs update" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-lead-pencil"></i></button>
                                    <?php } ?>
                                    <?php if($btn_delete=="1"){ ?>
                                      <button type="button" name="delete" id="<?php echo $value['tim_id']; ?>" class="btn btn-danger btn-xs delete" title="Delete"><i class="mdi mdi-window-close"></i></button>
                                    <?php } ?>
                                  </td>
                                </tr>
                            <?php   $num++;
                                }
                              } 
                              ?>
                            </tbody>
                          </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <form method="post" id="testimonials_form" autocomplete="off" name="testimonials_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body">
                  <div class="form-group">
                    <label for="tim_title"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                    <input type="text" id="tim_title" name="tim_title" class="form-control"> 
                  </div>
                  <div class="form-group">
                    <label for="tim_file"><b style="color: #FF2D00">*</b><?php echo label('media_file'); ?>:</label>
                    <input type="file" name="tim_file" id="tim_file" class="dropify" accept="image/png, image/jpeg, image/gif" />
                  </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="tim_id" name="tim_id">
              <input type="hidden" id="tim_file_ori" name="tim_file_ori">
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
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("AddTestimonials"); ?>');
                $('#operation').val("Add");
                $('#testimonials_form')[0].reset();
                var nameImage = "";
                    console.log(nameImage);
                    var drEvent = $('#tim_file').dropify(
                    {
                      defaultFile: nameImage
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = nameImage;
                    drEvent.destroy();
                    drEvent.init();
                    $('.dropify').dropify({
                        defaultFile: "" ,
                    }); 
            });

    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).on('submit', '#testimonials_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_testimonials",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        $('#testimonials_form')[0].reset();
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
                            title: '<?php echo label("com_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $("#com_name").val("");
                            document.getElementById("com_name").focus();
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
            var tim_id = $(this).attr("id");
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
                console.log(tim_id);
                $.ajax({
                    url:"<?=base_url()?>index.php/setting/delete_testimonials_data",
                    method:"POST",
                    data:{tim_id_delete:tim_id,table_name:"lms_testimonials"},
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
            var tim_id = $(this).attr("id");
            console.log(tim_id);
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_testimonials_data",
              method:"POST",
              data:{tim_id_update:tim_id},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label("EditTestimonials"); ?>');
                $('#operation').val("Edit");
                $('#testimonials_form')[0].reset();
                $('#tim_title').val(data.tim_title);
                $('#tim_id').val(data.tim_id);    
                $('#tim_file_ori').val(data.tim_file);
                console.log("../uploads/brand/"+data.tim_file);
                var nameImage = "../uploads/brand/"+data.tim_file;
                    console.log(nameImage);
                    var drEvent = $('#tim_file').dropify(
                    {
                      defaultFile: nameImage
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = nameImage;
                    drEvent.destroy();
                    drEvent.init();
                    $('.dropify').dropify({
                        defaultFile: "" ,
                    });
              }
            });
            
          });

    });
    </script>
</body>

</html>