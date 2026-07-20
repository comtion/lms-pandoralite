<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
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
                        <b><?php echo label('allworkgroup'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('allworkgroup'); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                    <div class="col-md-12 card">
                        <div class="card-body">
                            <div class="col-md-12" align="right">
                                <input type="text" class="form-control col-md-4" id="txt_search" name="txt_search" placeholder="<?php echo label('search'); ?>">
                                <?php if($btn_add=="1"){ ?>
                                    <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('allworkgroup'); ?></button><br>
                                <?php } ?>
                                <hr>
                            </div>

                            <div class="col-md-12" align="center" id="workgroup_div">
                            </div>
                            <form method="post" action="<?php echo REAL_PATH;?>/enroll">
                            
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form  enctype="multipart/form-data" autocomplete="off" id="workgroup_form" name="workgroup_form" method="POST"  class="form-horizontal p-t-20">
                    <div class="modal-body card">
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><?php echo label('wcode'); ?></label>
                                <input name="wcode" id="wcode" class="form-control" type="text">
                            </div>
                            <div class="form-group col-md-6">
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
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('wtitle')." TH"; ?></label>
                                <input required name="wtitle_th" type="text" class="form-control" id="wtitle_th">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('wtitle')." EN"; ?></label>
                                <input required name="wtitle_en" type="text" class="form-control" id="wtitle_en">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><?php echo label('wthumb'); ?></label>
                                <input type="file" name="image" id="input-file-now-custom-1" class="dropify" accept="image/png, image/jpeg, image/gif" />
                                <input type="hidden" id="image_ori" name="image_ori">
                            </div>
                            <div class="form-group col-md-6">
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <label class="control-label text-right"><?php echo label('wdesc')." TH"; ?></label>
                                <textarea name="wdesc_th" id="wdesc_th" rows="10" cols="80"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label text-right"><?php echo label('wdesc')." EN"; ?></label>
                                <textarea name="wdesc_en" id="wdesc_en" rows="10" cols="80"></textarea>
                            </div>

                            <div class="col-md-12 progress" id="progress_div">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="txt_progress"></span></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="operation" name="operation" value="Add">
                    <input type="hidden" id="id" name="id">
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

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        load_dataondiv("");
        $(document).ready(function(){
          $("#txt_search").keyup(function(){
            var txt_search = $('#txt_search').val();
            load_dataondiv(txt_search);
          });
        });
        function load_dataondiv(value=""){

            $.ajax({
                url:"<?=base_url()?>index.php/workgroup/load_workgroup_data",
                method:"POST",
                data:{value:value},
                success:function(data)
                {
                    
                    $('#workgroup_div').html(data);
                }
            });
        }
        $('.slimtest1').perfectScrollbar();

        if ($("#wdesc_th").length > 0) {
            tinymce.init({
                selector: "textarea#wdesc_th",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

            });
        }
        if ($("#wdesc_en").length > 0) {
            tinymce.init({
                selector: "textarea#wdesc_en",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

            });
        }
        $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("create").label("workgroup"); ?>');
                $('#operation').val("Add");
                $('#workgroup_form')[0].reset();
                var nameImage = "";
                    var drEvent = $('#input-file-now-custom-1').dropify(
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

        $(document).on('submit', '#workgroup_form', function(event){
              event.preventDefault(); 

                $.ajax({
                  url:"<?=base_url()?>index.php/workgroup/insert_workgroup",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                console.log(percentComplete);
                                $('#txt_progress').text(percentComplete + '%');
                                //Do something with upload progress here
                            }
                       }, false);
                       return xhr;
                  },
                  success:function(data)
                  {
                    document.getElementById("progress_div").style.display = "none";
                    
                    if(data=="2"){
                        $('#workgroup_form')[0].reset();
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
                            title: '<?php echo label("wg_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            $('#workgroup_form')[0].reset();
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
            var id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/workgroup/delete_workgroup_data",
                    method:"POST",
                    data:{id_delete:id},
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

          $(document).on('click', '.update', function(){
            var id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/workgroup/update_workgroup_data",
              method:"POST",
              data:{id_update:id},
              dataType:"json",
              success:function(data)
              {
                
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label("edit").label("workgroup"); ?>');
                $('#operation').val("Edit");
                $('#workgroup_form')[0].reset();
                $('#wcode').val(data.wcode); 
                $('#wtitle_th').val(data.wtitle_th); 
                $('#wtitle_en').val(data.wtitle_en); 
                $(tinymce.get('wdesc_th').getBody()).html(data.wdesc_th);
                $(tinymce.get('wdesc_en').getBody()).html(data.wdesc_en);
                $('#com_id').val(data.com_id);  
                $('#image_ori').val(data.wthumb);
                $('#id').val(data.id);  
                if(data.wthumb!=""){
                    var nameImage = "<?php echo REAL_PATH;?>/uploads/work_group/"+data.wthumb;
                    var drEvent = $('#input-file-now-custom-1').dropify(
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
                        defaultFile: "<?php echo REAL_PATH;?>/uploads/work_group/"+data.wthumb ,
                    }); 
                }else{
                    $('.dropify').dropify();
                }
              }
            });
            
          });
    </script>
</body>

</html>