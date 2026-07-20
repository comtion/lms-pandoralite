<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <!-- Date picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
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
                                <th width="20%" align="center"><?php echo label('rw_name'); ?></th>
                                <th width="15%" align="center"><?php echo label('total_reward'); ?></th>
                                <th width="15%" align="center"><?php echo label('usage_reward'); ?></th>
                                <th width="15%" align="center"><?php echo label('pending_reward'); ?></th>
                                <th width="20%" align="center"><?php echo label('reward_period'); ?></th>
                                <th width="10%" align="center"><?php echo label('manage'); ?></th>
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
              <form method="post" id="reward_form" autocomplete="off" name="reward_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="rw_name"><b style="color: #FF2D00">*</b><?php echo label('rw_name'); ?>:</label>
                    <input type="text" id="rw_name" name="rw_name" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="trw_id"><b style="color: #FF2D00">*</b><?php echo label('reward_type'); ?>:</label>
                    <select id="trw_id" name="trw_id" required class="form-control"></select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="rw_shorttxt"><?php echo label('rw_shorttxt'); ?>:</label>
                    <input type="text" id="rw_shorttxt" name="rw_shorttxt" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="rw_point"><?php echo label('rw_point'); ?>:</label>
                    <input type="number" min="0" step="1" id="rw_point" name="rw_point" class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card form-group">
                      <label class="control-label"><?php echo label('upload_image'); ?></label>
                      <input type="file" name="rw_path" id="rw_path" class="dropify" accept="image/png, image/jpeg, image/gif" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="rw_amountreward"><?php echo label('rw_amountreward'); ?>:</label>
                    <input type="number" min="0" step="1" id="rw_amountreward" name="rw_amountreward" class="form-control"> 
                  </div>

                  <div class="form-group">
                    <div class="checkbox checkbox-success">
                      <input id="rw_unlimitamount" name="rw_unlimitamount" type="checkbox" value="1">
                      <label for="rw_unlimitamount"> <?php echo label('rw_unlimitamount'); ?> </label>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label text-right"><?php echo label('reward_period'); ?></label>
                      <div class='input-group mb-3'>
                          <input type='text' id="daterange_period" name="daterange_period" class="form-control timeseconds" />
                          <div class="input-group-append">
                              <span class="input-group-text">
                                  <span class="ti-calendar"></span>
                              </span>
                              <span class="input-group-text hover_cleartxt" onclick="clear_txt('daterange_period','rw_startdate','rw_enddate')">
                                  <span class="mdi mdi-close-box"></span>
                              </span>
                          </div>
                      </div>
                      <input type="hidden" id="rw_startdate" name="rw_startdate">
                      <input type="hidden" id="rw_enddate" name="rw_enddate">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="rw_shorttxt"><?php echo label('rw_detail'); ?>:</label>
                    <textarea name="rw_detail" id="rw_detail" rows="3"></textarea>
                  </div>
                </div>

              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="rw_id" name="rw_id">
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
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript">
        
        $.fn.dataTable.ext.errMode = "none";
        textarea_tinymce('rw_detail');
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

        function clear_txt(id,a,b){
          $('#'+id).val('');
          $('#'+a).val('0000-00-00 00:00:00');
          $('#'+b).val('0000-00-00 00:00:00');
        }
        fetch_data(0);
        function fetch_data(page_num=0)
         {
            $('#myTable').DataTable().destroy();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
                notificationForDatatableError("myTable", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/setting/fetch_reward/',
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

          document.getElementById('rw_unlimitamount').onchange = function() {
              document.getElementById('rw_amountreward').readOnly = this.checked;
              $('#rw_amountreward').val('0');
          };


           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label('add_reward'); ?>');
                $('#operation').val("Add");
                $('#reward_form')[0].reset();
                $(".dropify-clear").trigger("click");
                $('#rw_path').dropify();
                $.ajax({
                  url: '<?=base_url()?>index.php/manage/recheckrewardtype',
                  type: 'POST',
                  data:{trw_id:''},
                  success: function(data){
                    $('#trw_id').html(data);
                  }
                }); 
                document.getElementById('rw_amountreward').readOnly = false;
                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date(),
                            endDate: new Date(),
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#rw_startdate').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#rw_enddate').val(end.format('YYYY-MM-DD HH:mm:00'));
                       });
            });

          function mysqlTimeStampToDate(timestamp) {
            var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
            var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
            return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
          }
    $(document).ready(function() {
        $(document).on('submit', '#reward_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_reward",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    
                    if(data=="2"){
                        $('#reward_form')[0].reset();
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
            var rw_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/setting/delete_reward_data",
                    method:"POST",
                    data:{id_delete:rw_id,table_name:"lms_reward"},
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
            var rw_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_reward",
              method:"POST",
              data:{rw_id:rw_id},
              dataType:"json",
              success:function(data)
              {
                
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label('edit_reward'); ?>');
                $('#operation').val("Edit");
                $('#reward_form')[0].reset();
                $('#rw_name').val(data.rw_name);
                $('#rw_shorttxt').val(data.rw_shorttxt);
                $('#rw_point').val(data.rw_point);
                $('#rw_amountreward').val(data.rw_amountreward);

                if(data.rw_unlimitamount=="1"){
                  document.getElementById('rw_unlimitamount').checked = true;
                  document.getElementById('rw_amountreward').readOnly = true;
                  $('#rw_amountreward').val('0');
                }else{
                  document.getElementById('rw_unlimitamount').checked = false;
                  document.getElementById('rw_amountreward').readOnly = false;
                }
              
                        var rw_startdate = mysqlTimeStampToDate(data.rw_startdate);
                        var rw_enddate = mysqlTimeStampToDate(data.rw_enddate);
                        if(data.rw_startdate=="0000-00-00 00:00:00"&&data.rw_enddate=="0000-00-00 00:00:00"){
                          rw_startdate = new Date();
                          rw_enddate = new Date();
                        }
                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: rw_startdate,
                            endDate: rw_enddate,
                            separator: ' to ',
                            locale: {
                                format: 'DD/MMMM/YYYY HH:mm:00',
                                applyLabel: '<?php echo label("m_ok"); ?>',
                                cancelLabel: '<?php echo label("cancel"); ?>',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                <?php if($lang=="thai"){ ?>
                                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ','ส'],
                                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                                <?php }else{ ?>
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                <?php } ?>
                                firstDay: 1
                            }
                        },
                       function(start, end) {
                          $('#rw_startdate').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#rw_enddate').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });

                        if(data.rw_startdate=="0000-00-00 00:00:00"&&data.rw_enddate=="0000-00-00 00:00:00"){
                          $('#daterange_period').val('');
                        }

                $.ajax({
                  url: '<?=base_url()?>index.php/manage/recheckrewardtype',
                  type: 'POST',
                  data:{trw_id:data.trw_id},
                  success: function(data_trw){
                    $('#trw_id').html(data_trw);
                  }
                }); 
                  $(tinymce.get('rw_detail').getBody()).html(data.rw_detail);
                  if(data.rw_path!=""){
                      $(".dropify-clear").trigger("click");
                      var drEvent = $('#rw_path').dropify(
                      {
                          defaultFile: "<?php echo REAL_PATH;?>/uploads/reward/"+data.rw_path
                      });
                      drEvent = drEvent.data('dropify');
                      drEvent.resetPreview();
                      drEvent.settings.defaultFile = "<?php echo REAL_PATH;?>/uploads/reward/"+data.rw_path;
                      drEvent.destroy();
                      drEvent.init();
                  }else{
                      $(".dropify-clear").trigger("click");
                      $('#rw_path').dropify();
                  }  
                $('#rw_id').val(data.rw_id);
              }
            });
          });
    });
    </script>
</body>

</html>