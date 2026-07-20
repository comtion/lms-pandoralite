<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
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
                        <b><?php echo label('menu_setting'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('menu_setting'); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12" align="right">
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('event_add'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="5%"></th>
                                <th width="30%" align="center"><?php echo label('questitle'); ?></th>
                                <th width="40%" align="center"><?php echo label('quesdetail'); ?></th>
                                <th width="15%" align="center"><?php echo label('periodlog'); ?></th>
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
              <form method="post" id="event_form" autocomplete="off" name="event_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="con_title_th"><b style="color: #FF2D00">*</b><?php echo label('questitle')." TH"; ?>:</label>
                    <input type="text" id="con_title_th" name="con_title_th" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="con_title_en"><b style="color: #FF2D00">*</b><?php echo label('questitle')." EN"; ?>:</label>
                    <input type="text" id="con_title_en" name="con_title_en" required class="form-control"> 
                  </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label text-right"><?php echo label('quesdetail')." TH"; ?></label>
                    <textarea name="con_detail_th" id="con_detail_th" rows="5" class="form-control"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label text-right"><?php echo label('quesdetail')." EN"; ?></label>
                    <textarea name="con_detail_en" id="con_detail_en" rows="5" class="form-control"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label text-right"><?php echo label('periodlog'); ?></label>
                    <div class='input-group mb-3'>
                        <input type='text' id="daterange_period" required name="daterange_period" class="form-control" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <span class="ti-calendar"></span>
                            </span>
                            <span class="input-group-text hover_cleartxt" onclick="clear_txt('daterange_period','con_datestart','con_dateend')">
                                <span class="mdi mdi-close-box"></span>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" id="con_datestart" name="con_datestart">
                    <input type="hidden" id="con_dateend" name="con_dateend">
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="con_id" name="con_id">
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

    <!-- Bootstrap tether Core JavaScript -->
<!--     <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/popper.min.js"></script> -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
        <script src="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <script type="text/javascript">
          $.fn.dataTable.ext.errMode = "none";
          function textarea_tinymce(id=''){
              if ($("#"+id).length > 0) {
                  tinymce.init({
                      selector: "textarea#"+id,
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
          }
          function mysqlTimeStampToDate(timestamp) {
            //function parses mysql datetime string and returns javascript Date object
            //input has to be in this format: 2007-06-05 15:26:02
            var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
            var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
            return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
          }
          function clear_txt(id,a,b){
            $('#'+id).val('');
            $('#'+a).val('0000-00-00 00:00:00');
            $('#'+b).val('0000-00-00 00:00:00');
          }

      fetch_data_menu(0);
      function fetch_data_menu(page_num=0)
         {
            $('#myTable').DataTable().destroy();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/setting/fetch_detail_event/',
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
                        textarea_tinymce('con_detail_th');
                        textarea_tinymce('con_detail_en');

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("event_add"); ?>');
                $('#operation').val("Add");
                $('#event_form')[0].reset();

                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date(),
                            endDate: new Date(),
                            separator: ' to ',
                            drops: 'up',
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
                          $('#con_datestart').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#con_dateend').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
            });

    $(document).ready(function() {
        $(document).on('submit', '#event_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_event",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        $('#event_form')[0].reset();
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
                                      fetch_data_menu(page_current);
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
            var con_id = $(this).attr("id");
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
                    url:"<?=base_url()?>index.php/setting/delete_event_data",
                    method:"POST",
                    data:{id_delete:con_id,table_name:"lms_content"},
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
                                      fetch_data_menu(page_current);
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
            var con_id = $(this).attr("id");
            console.log(con_id);
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_event",
              method:"POST",
              data:{con_id:con_id},
              dataType:"json",
              success:function(data)
              {
                console.log(data);
                $('#modal-default').modal('show');
                $('.modal-title').text('<?php echo label("event_edit"); ?>');
                $('#operation').val("Edit");
                $('#event_form')[0].reset();

                        $('#con_datestart').val(data.con_datestart);
                        $('#con_dateend').val(data.con_dateend);
                        var ddate_start = mysqlTimeStampToDate(data.con_datestart);
                        var date_end = mysqlTimeStampToDate(data.con_dateend);
                        if(data.con_datestart==""&&data.con_dateend==""){
                          ddate_start = new Date();
                          date_end = new Date();
                        }
                        $('#daterange_period').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: ddate_start,
                            endDate: date_end,
                            separator: ' to ',
                            drops: 'up',
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
                          $('#con_datestart').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#con_dateend').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                       });
                $('#con_title_th').val(data.con_title_th);
                $('#con_title_en').val(data.con_title_en);
                $(tinymce.get('con_detail_th').getBody()).html(data.con_detail_th);
                $(tinymce.get('con_detail_en').getBody()).html(data.con_detail_en);
                $('#con_id').val(data.con_id);
              }
            });
          });
    });
    </script>
</body>

</html>