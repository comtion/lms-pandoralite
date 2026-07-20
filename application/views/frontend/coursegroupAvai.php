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
                        <b><?php echo ucwords(label('coursegroup')); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo ucwords(label('coursegroup')); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                    <div class="col-md-12 card">
                        <div class="card-body">
                            <div class="col-md-12" align="right">
                                <input type="text" class="form-control col-md-4" id="txt_search" name="txt_search" placeholder="<?php echo label('search'); ?>">
                                <?php if($btn_add=="1"){ ?>
                                    <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('create').label('coursegroup'); ?></button><br>
                                <?php } ?>
                            </div>

                            <div class="col-md-12" id="coursegroup_div">
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
                <form  enctype="multipart/form-data" id="coursegroup_form" autocomplete="off" name="coursegroup_form" method="POST"  class="form-horizontal p-t-20">
                    <div class="modal-body card">
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><?php echo label('cgcode'); ?></label>
                                <input name="cgcode" id="cgcode" class="form-control" type="text">
                            </div>
                            <div class="form-group col-md-6">
                                <?php if($com_admin!="CUSTOMER"){ ?>
                                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                <select class="form-control" required id="com_id" name="com_id"  style="width: 100%;">
                                        <option value=""><?php echo label('please_com_name'); ?></option>
                                      <?php foreach( $company_select as $company ){ ?>
                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                      <?php } ?>
                                </select>
                                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('wtitle'); ?>:</label>
                                <select class="form-control" required id="wg_id" name="wg_id"  style="width: 100%;">
                                </select>
                                <?php }else{ ?>
                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                    <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('wtitle'); ?>:</label>
                                    <select class="form-control" required id="wg_id" name="wg_id"  style="width: 100%;">
                                    </select>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('cgtitle')." TH"; ?></label>
                                <input required name="cgtitle_th" type="text" class="form-control" id="cgtitle_th">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><b style="color: #FF2D00">*</b><?php echo label('cgtitle')." EN"; ?></label>
                                <input required name="cgtitle_en" type="text" class="form-control" id="cgtitle_en">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label text-right"><?php echo label('cgthumb'); ?></label>
                                <input type="file" name="image" id="input-file-now-custom-1" class="dropify" accept="image/png, image/jpeg, image/gif" />
                                <input type="hidden" id="image_ori" name="image_ori">
                            </div>
                            <div class="form-group col-md-6">
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <label class="control-label text-right"><?php echo label('cgdesc')." TH"; ?></label>
                                <textarea name="cgdesc_th" id="cgdesc_th" rows="10" cols="80"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label text-right"><?php echo label('cgdesc')." EN"; ?></label>
                                <textarea name="cgdesc_en" id="cgdesc_en" rows="10" cols="80"></textarea>
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
            var wcode = '<?php echo $wcode; ?>';
            $.ajax({
                url:"<?=base_url()?>index.php/coursegroup/load_coursegroup_data",
                method:"POST",
                data:{value:value,wcode:wcode},
                success:function(data)
                {
                    $('#coursegroup_div').html(data);
                }
            });
        }
        $('.slimtest1').perfectScrollbar();
        $('select[name="com_id"]').on('change', function(){
          var com_id = $(this).val();
            $.ajax({
                  url: '<?=base_url()?>index.php/workgroup/recheckworkgroup',
                  type: 'POST',
                  data:{com_id:com_id,wg_id:''},
                  success: function(data){
                    $('#wg_id').html(data);
                  }
            });
        });
        <?php if($com_admin=="CUSTOMER"){ ?>
            var com_id = '<?php echo $com_id; ?>';
            $.ajax({
                  url: '<?=base_url()?>index.php/workgroup/recheckworkgroup',
                  type: 'POST',
                  data:{com_id:com_id,wg_id:''},
                  success: function(data){
                    $('#wg_id').html(data);
                  }
            });
        <?php } ?>
        if ($("#cgdesc_th").length > 0) {
            tinymce.init({
                selector: "textarea#cgdesc_th",
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
        if ($("#cgdesc_en").length > 0) {
            tinymce.init({
                selector: "textarea#cgdesc_en",
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
                $('.modal-title').text('<?php echo label("create").label("coursegroup"); ?>');
                $('#operation').val("Add");
                $('#coursegroup_form')[0].reset();
                $('.dropify').dropify();
                clear_dropify('#input-file-now-custom-1');
        });

        function clear_dropify(id){
            var drEvent = $(id).dropify(
                    {
                      defaultFile: ''
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = '';
                    drEvent.destroy();
                    drEvent.init();
        }

        $(document).on('submit', '#coursegroup_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/coursegroup/insert_coursegroup",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
								
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
                        $('#coursegroup_form')[0].reset();
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
                            $('#coursegroup_form')[0].reset();
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
                    url:"<?=base_url()?>index.php/coursegroup/delete_coursegroup_data",
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
              url:"<?=base_url()?>index.php/coursegroup/update_coursegroup_data",
              method:"POST",
              data:{id_update:id},
              dataType:"json",
              success:function(data)
              {
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label("edit").label("coursegroup"); ?>');
                $('#operation').val("Edit");
                $('#coursegroup_form')[0].reset();
                $('#cgcode').val(data.cgcode); 
                $('#cgtitle_th').val(data.cgtitle_th); 
                $('#cgtitle_en').val(data.cgtitle_en); 
                $(tinymce.get('cgdesc_th').getBody()).html(data.cgdesc_th);
                $(tinymce.get('cgdesc_en').getBody()).html(data.cgdesc_en);
               
                $('#image_ori').val(data.cgthumb);
                $('#id').val(data.id);  
                $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/getCompanyForWG',
                      type: 'POST',
                      data:{wg_id:data.wg_id},
                      success: function(data){
                         $('#com_id').val(data);  
                      }
                });
                $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/recheckworkgroup',
                      type: 'POST',
                      data:{com_id:'',wg_id:data.wg_id},
                      success: function(data){
                        $('#wg_id').html(data);
                      }
                });
                if(data.cgthumb!=""){
                    var nameImage = "<?php echo REAL_PATH;?>/uploads/course_group/"+data.cgthumb;
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
                }else{
                    $('.dropify').dropify();
                }
              }
            });
            
          });
    </script>
</body>

</html>
