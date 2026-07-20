<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <style type="text/css">
        #myModal_process.modal.show .modal-dialog{
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        margin: 0;
      }
      #myModal_process .circle strong{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:1.8em;
      }
      #myModal_process .circle canvas{
        visibility: hidden;
      }
      #myModal_process #circle-b{
        margin:0;
      }
    </style>
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
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('AddBannerCourse'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                <th width="10%"></th>
                                <th width="30%"><center><?php echo label('banner_course_file'); ?></center></th>
                                <th width="20%"><center><?php echo label('banner_name'); ?></center></th>
                                <th width="20%"><center><?php echo label('banner_type'); ?></center></th>
                                <th width="10%"><center><?php echo label('status'); ?></center></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $num = 1;
                              if(isset($data_fetch)){
                                foreach ($data_fetch as $key => $value) { ?>
                                <tr>
                                  <td>
                                    <center>
                                    <?php if($btn_update=="1"){ ?>
                                      <button type="button" name="update" id="<?php echo $value['bc_id']; ?>" title="Edit" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>
                                    <?php } ?>
                                    <?php if($btn_delete=="1"){ ?>
                                      <button type="button" name="delete" id="<?php echo $value['bc_id']; ?>" class="btn btn-danger btn-xs delete" title="Delete"><i class="mdi mdi-window-close"></i></button>
                                    <?php } ?>
                                    </center>
                                  </td>
                                  <td align="right"><span style="float: right;"><?php echo $num; ?></span></td>
                                  <td><img width="60%" src="../uploads/banner_course/<?php echo $value['bc_image']; ?>"></td>
                                  <td align="center"><?php if($lang=="thai"){echo $value['bc_name_th'];}else if($lang=="english"){echo $value['bc_name_eng'];}else{echo $value['bc_name_jp'];} ?></td>
                                  <td align="center"><?php if($value['bc_type']!="3"){echo $value['bc_type']=="1"?label('total_course'):label('my_course');}else{ echo label('survey'); } ?></td>
                                  <td align="center"><?php echo $value['bc_status']=="1"?label('open'):label('close'); ?></td>
                                </tr>
                            <?php   $num++;
                                }
                              } 
                              ?>
                            </tbody>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
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
              <form method="post" id="bannercourse_form" autocomplete="off" name="bannercourse_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                  <div class="form-group col-md-4">
                    <label for="bc_name_th"><?php echo label('banner_name')." (".label('TH').")"; ?>:</label>
                    <input type="text" id="bc_name_th" name="bc_name_th" class="form-control"> 
                  </div>
                  <div class="form-group col-md-4">
                    <label for="bc_name_eng"><?php echo label('banner_name')." (".label('EN').")"; ?>:</label>
                    <input type="text" id="bc_name_eng" name="bc_name_eng" class="form-control"> 
                  </div>
                  <div class="form-group col-md-4">
                    <label for="bc_name_jp"><?php echo label('banner_name')." (".label('JP').")"; ?>:</label>
                    <input type="text" id="bc_name_jp" name="bc_name_jp" class="form-control"> 
                  </div>
                  <div class="form-group col-md-4">
                    <label for="bc_type"><b style="color: #FF2D00">*</b><?php echo label('banner_type'); ?>:</label>
                    <select id="bc_type" name="bc_type" class="form-control">
                      <option value="1" selected><?php echo label('total_course'); ?></option>
                      <option value="2"><?php echo label('my_course'); ?></option>
                      <option value="3"><?php echo label('survey'); ?></option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="bc_status"><b style="color: #FF2D00">*</b><?php echo label('status'); ?>:</label>
                      <div class="switch">
                          <label><?php echo label('close'); ?><input type="checkbox"  id="bc_status" name="bc_status" value="1"><span class="lever switch-col-indigo"></span><?php echo label('open'); ?></label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="bc_image"><b style="color: #FF2D00">*</b><?php echo label('banner_course_file'); ?>:</label>
                    <input type="file" name="bc_image" id="bc_image" required class="dropify" accept="image/png, image/jpeg, image/gif" />
                  </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="bc_id" name="bc_id">
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
      
      <div
        id="myModal_process"
        class="modal bs-example-modal-lg"
        role="dialog"
        aria-labelledby="smallModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body" align="center" style="max-height: 300px;">
              <div class="circle" id="circle-b">
                <strong></strong>
              </div>
            </div>
          </div>
        </div>
      </div>


    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

           $('#add_button').click(function(){
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("AddBannerCourse"); ?>');
                $('#operation').val("Add");
                $('#bannercourse_form')[0].reset();

                document.getElementById("bc_image").required = true;
                var nameImage = "";
                    var drEvent = $('#bc_image').dropify(
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
        $('#myTable').DataTable({
          
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
        $(document).on('submit', '#bannercourse_form', function(event){
              event.preventDefault(); 
                  $("#myModal_process").modal('show');
                  $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_bannercourse",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:"json",
                        xhr: function() {
                             var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                            var progressBarOptions = {
                                              startAngle: -1.55,
                                              size: 200,
                                                value: percentComplete.toFixed(0),
                                                fill: {
                                                color: '#ffa500'
                                              }
                                            }
                                            console.log(percentComplete.toFixed(0));
                                            $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                              $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                            });

                                            $('#circle-b').circleProgress({
                                              value : percentComplete.toFixed(0),
                                              fill: {
                                                color: '#FF0000'
                                              }
                                            });
                                  }
                             }, false);
                             return xhr;
                        },
                  success:function(data)
                  {
                          $( document.body ).css( 'pointer-events', '' );
                        $('#myModal_process').modal('hide');
                        
                        $("#myModal_process").removeClass("in");
                        $("#myModal_process").css("display","none");
                    if(data.status=="2"){
                        $('#bannercourse_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                    }else if(data.status=="1"){
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
                            title: '<?php echo label("com_msg_form_error"); ?>',
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
            var bc_id = $(this).attr("id");
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
                console.log(bc_id);
                $.ajax({
                    url:"<?=base_url()?>index.php/setting/delete_bannercourse_data",
                    method:"POST",
                    data:{bc_id_delete:bc_id,table_name:"lms_ban_cos"},
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
            var bc_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_bannercourse_data",
              method:"POST",
              data:{bc_id_update:bc_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("EditBannerCourse"); ?>');
                $('#operation').val("Edit");
                $('#bannercourse_form')[0].reset();
                $('#bc_name_th').val(data.bc_name_th);
                $('#bc_name_eng').val(data.bc_name_eng);
                $('#bc_name_jp').val(data.bc_name_jp);
                $('#bc_id').val(data.bc_id);    
                $('#bc_type').val(data.bc_type);    
                document.getElementById("bc_image").required = false;
                $('#bc_image_ori').val(data.bc_image);
                if(data.bc_status=="1"){
                  document.getElementById('bc_status').checked = true;
                }else{
                  document.getElementById('bc_status').checked = false;
                }
                var nameImage = "../uploads/banner_course/"+data.bc_image;
                    console.log(nameImage);
                    var drEvent = $('#bc_image').dropify(
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