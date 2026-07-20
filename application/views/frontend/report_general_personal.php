<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>

    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- Date picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/adapter.min.js"></script>
    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/vue.min.js"></script>
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
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
                            <form  enctype="multipart/form-data" id="search_form" name="search_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                  <div class="form-group mb-1">
                                    <label for="course_status"><?php echo label('r_result'); ?>:</label>
                                    <select class="form-control" id="course_status" name="course_status"  style="width: 100%;">
                                        <option value=""><?php echo label('r_company'); ?></option>
                                        <option value="1"><?php echo label('open'); ?></option>
                                        <option value="0"><?php echo label('close'); ?></option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                  <div class="form-group mb-1">
                                    <label for="cosen_status_sub"><?php echo label('learning_status'); ?>:</label>
                                    <select class="form-control" id="cosen_status_sub" name="cosen_status_sub"  style="width: 100%;">
                                        <option value=""><?php echo label('r_company'); ?></option>
                                        <option value="0"><?php echo label('not_start'); ?></option>
                                        <option value="2"><?php echo label('inProgress'); ?></option>
                                        <option value="1"><?php echo label('r_pass'); ?></option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <label class="control-label text-right"><?php echo label('date_passcourse'); ?>:</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-1">
                                                        <input type="text" id="date_start" name="date_start" onchange="caldate('date_start')" class="form-control date_start">
                                                        <input type="hidden" id="date_start_var" name="date_start_var">
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-1">
                                                        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <input type="text" id="time_start" name="time_start" class="form-control" value="<?php echo date('H:i',strtotime('00:00')); ?>">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-1">
                                                        <input type="text" id="date_end" name="date_end" onchange="caldate('date_end')" class="form-control date_end">
                                                        <input type="hidden" id="date_end_var" name="date_end_var">
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-1">
                                                        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <input type="text" id="time_end" name="time_end" class="form-control" value="<?php echo date('H:i',strtotime('23:59')); ?>">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                                   <!--  <div class='input-group mb-3'>
                                                        <input type='text' id="daterange_report" name="daterange_report" class="form-control timeseconds" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <span class="ti-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="date_start_var" name="date_start_var">
                                                    <input type="hidden" id="date_end_var" name="date_end_var"> -->
                                    <!-- <div class="input-daterange input-group" id="date-range">
                                        <input type="text" class="form-control" name="date_start" id="date_start" />
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-info b-0 text-white"><?php echo label('to'); ?></span>
                                        </div>
                                        <input type="text" class="form-control" name="date_end" id="date_end" />
                                        <input type="hidden" id="date_start_var" name="date_start_var">
                                        <input type="hidden" id="date_end_var" name="date_end_var">
                                    </div> -->
                                </div>
                                <div class="offset-xl-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-xl-4 col-sm-12">
                                            <?php if($btn_print=="1"){ ?>
                                              <button name="export_button" id="export_button" class="btn btn-block btn-outline-success export_button float-right"><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
                                            <?php } ?>
                                        </div>
                                        <div class="col-xl-4 col-sm-12" align="center">
                                            <input type="submit" name="action" id="action" class="btn btn-block btn-outline-info btn-block" value="<?php echo label('search'); ?>" />
                                        </div>
                                        <div class="col-xl-4 col-sm-12" align="center">
                                            <button type="reset" class="btn btn-block btn-outline-danger btn-block" onclick="onclear()"><?php echo label('m_cancel'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <hr>
                            <div class="table-responsive">
                                  <table id="myTable" width="1200" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                        <th width="50" align="center"></th>
                                        <th width="250" align="center"><?php echo label('ceCname'); ?></th>
                                        <th width="150" align="center"><?php echo label('r_result'); ?></th>
                                        <th width="200" align="center"><?php echo label('learning_status'); ?></th>
                                        <th width="150" align="center"><?php echo label('score_pretest'); ?></th>
                                        <th width="150" align="center"><?php echo label('maxScore')."<br>(".label('score_pretest').")"; ?></th>
                                        <th width="150" align="center"><?php echo label('score_posttest'); ?></th>
                                        <th width="150" align="center"><?php echo label('maxScore')."<br>(".label('score_posttest').")"; ?></th>
                                        <th width="150" ><center><?php echo label('preReport'); ?></center></th>
                                        <th width="150" align="center"><?php echo label('date_passcourse'); ?></th>
                                      </tr>
                                    </thead>
                                  </table>
                            </div>
                            <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-comment-text-outline"></i></button> = <b><?php echo label('answer'); ?></b></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

      <div class="modal fade  bs-example-modal-lg" id="modal-view_answer">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 id="myLargeModalLabel"><i class="mdi mdi-comment-text-outline"></i><span> <?php echo label('answer'); ?></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>

              <div class="modal-body">
                <div class="card-body" id="div_allquestion">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
            </div>
          </div>
        </div> 
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
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

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
<?php if($lang=="thai"){ ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
<?php } ?>
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        function onclear(){
                $("#date_start").datepicker("update", '');
                $("#date_end").datepicker("update", '');
                $('#cos_id').empty();
                fetch_data_personal('','','','');
                $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/select_course',
                      type: 'POST',
                      data:{com_id:'<?php echo $com_id; ?>'},
                      success: function(data){
                        $('#cos_id').html(data);
                      }
                });
        }
       /* jQuery('#date-range').datepicker({
            toggleActive: true,
            format: 'dd/MM/yyyy',
            orientation: "bottom left"
        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

                        $('#daterange_report').daterangepicker({
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
                          $('#date_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#date_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
                        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                          console.log(start.format('YYYY-MM-DD HH:mm:00'),end.format('YYYY-MM-DD HH:mm:00'));
                       });*/

        $('.clockpicker').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            donetext: 'Done',
        }).find('input').change(function() {
            console.log(this.value);
        });
        function changedate(value){
            var res_date = value.split("/");
            <?php if($lang=="thai"){ ?>
            return (parseInt(res_date[2])-543)+"-"+res_date[1]+"-"+res_date[0];
            <?php }else{ ?>
            return (parseInt(res_date[2]))+"-"+res_date[1]+"-"+res_date[0];
            <?php } ?>
        }
        
        function date_picker(id){
          jQuery('#'+id).datepicker({
                          format: 'dd/mm/yyyy',
                          language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                          thaiyear: true    
          }).datepicker("setDate", "1");
        }

        function caldate(id){
            var val_change = changedate($('#'+id).val());  
            $('#'+id+'_var').val(val_change);
        }
                from = $('#date_start').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?> 
                        format: 'dd/mm/yyyy',
                                orientation: 'bottom left',
                        autoclose: true
                }).on('changeDate', function (selected) {
                    $('#date_end').val('');
                    $('#date_start').datepicker("update", selected.date);
                         to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                format: 'dd/mm/yyyy',
                                orientation: 'bottom left',
                                autoclose: true
                        }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                var date_val = moment(maxDate).format('YYYY-MM-DD');
                                var res_date = date_val.split("-");
                                maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                console.log(maxDate,selected.date.valueOf());
                                $('#date_start').datepicker('setEndDate', maxDate);
                            });
                });
                 to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                orientation: 'bottom left',
                        format: 'dd/mm/yyyy',
                        autoclose: true
                }).on('changeDate', function (selected) {
                    $('#date_end').datepicker("update", selected.date);
                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                        var res_date = date_val.split("-");
                        maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                        $('#date_start').datepicker('setEndDate', maxDate);
                    });
                $.ajax({
                      url: '<?=base_url()?>index.php/workgroup/select_course',
                      type: 'POST',
                      data:{com_id:'<?php echo $com_id; ?>'},
                      success: function(data){
                        $('#cos_id').html(data);
                      }
                });

        fetch_data_personal();
        
        function fetch_data_personal(date_start,time_start,date_end,time_end)
         {
            var course_status = $('#course_status').val();
            var cosen_status_sub = $('#cosen_status_sub').val();
            var date_start_var = $('#date_start_var').val();
            var date_end_var = $('#date_end_var').val();
            $('#myTable').DataTable().destroy();
            $('#myTable').on('error.dt', function(e, settings, techNote, message) {
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
                    url : '<?=base_url()?>index.php/report/fetch_course_personal/',
                    data : {
                      course_status:course_status,
                      cosen_status_sub:cosen_status_sub,
                      date_start:date_start,
                      time_start:time_start,
                      date_end:date_end,
                      time_end:time_end,
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
                "columns": [
                    { data: "button_all" },
                    { data: "cname" },
                    { data: "cos_status" },
                    { data: "status_learner" },
                    { data: "score_pretest" },
                    { data: "score_pretest_full" },
                    { data: "score_posttest" },
                    { data: "score_posttest_full" },
                    { data: "preReport" },
                    { data: {
                        _:    "cosen_finishtime.display",
                        sort: "cosen_finishtime.timestamp"
                    } }
                ],
                /*<?php if($btn_print=="1"){?>
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'print'
                ]
                <?php } ?>*/
            });
         }

        $(document).on('submit', '#search_form', function(event){
              event.preventDefault(); 
              var date_start_var = $('#date_start_var').val();
              var time_start = $('#time_start').val();
              var date_end_var = $('#date_end_var').val();
              var time_end = $('#time_end').val();
                var date_start_var = $('#date_start_var').val();
                var time_start = $('#time_start').val();
                if(time_start==""){
                  time_start = "00:00";
                }
                var date_end_var = $('#date_end_var').val();
                var time_end = $('#time_end').val();
                if(time_end==""){
                  time_end = "00:00";
                }
                var val_chk = 1;
                var date_start = $('#date_start').val();
                var date_end = $('#date_end').val();
                if(date_start!=""||date_end!=""){
                  if(date_start!=""&&date_end!=""){
                    date_start_var = date_start_var+" "+time_start+":00";
                    date_end_var = date_end_var+" "+time_end+":00";
                    start_date = date_start_var.replace(/-/g, "/");
                    end_date = date_end_var.replace(/-/g, "/");
                    var d_start = new Date(start_date);
                    var d_end = new Date(end_date);
                    if(date_start_var==date_end_var){
                      $('#time_end').focus();
                      val_chk = 0;
                    }else if(d_start>d_end){
                      $('#date_end').val("");
                      $('#date_end_var').val("");
                      $('#date_end').focus();
                      val_chk = 0;
                    }
                  }else if(date_start==""&&date_end!=""){
                    $('#date_start').focus();
                    val_chk = 0;
                  }else if(date_start!=""&&date_end==""){
                    $('#date_end').focus();
                    val_chk = 0;
                  }
                }
                if(val_chk==1){
              fetch_data_personal(date_start_var,time_start,date_end_var,time_end);
                }
        });
          $(document).on('click', '.view_answer', function(){
            var cosen_id = $(this).attr("id");
            $('#modal-view_answer').modal('show');

                $.ajax({
                      url: '<?=base_url()?>index.php/report/fetch_detail_answer',
                      type: 'POST',
                      data:{cosen_id:cosen_id},
                      success: function(data){
                        $('#div_allquestion').html(data);
                      }
                });
          });
           $('#export_button').click(function(){
            var course_status = $('#course_status').val();
            var cosen_status_sub = $('#cosen_status_sub').val();
                var date_start_var = $('#date_start_var').val();
              var time_start = $('#time_start').val();
              var date_end_var = $('#date_end_var').val();
              var time_end = $('#time_end').val();
                var date_start_var = $('#date_start_var').val();
                var time_start = $('#time_start').val();
                if(time_start==""){
                  time_start = "00:00";
                }
                var date_end_var = $('#date_end_var').val();
                var time_end = $('#time_end').val();
                if(time_end==""){
                  time_end = "00:00";
                }
                var val_chk = 1;
                var date_start = $('#date_start').val();
                var date_end = $('#date_end').val();
                if(date_start!=""||date_end!=""){
                  if(date_start!=""&&date_end!=""){
                    date_start_var = date_start_var+" "+time_start+":00";
                    date_end_var = date_end_var+" "+time_end+":00";
                    start_date = date_start_var.replace(/-/g, "/");
                    end_date = date_end_var.replace(/-/g, "/");
                    var d_start = new Date(start_date);
                    var d_end = new Date(end_date);
                    if(date_start_var==date_end_var){
                      $('#time_end').focus();
                      val_chk = 0;
                    }else if(d_start>d_end){
                      $('#date_end').val("");
                      $('#date_end_var').val("");
                      $('#date_end').focus();
                      val_chk = 0;
                    }
                  }else if(date_start==""&&date_end!=""){
                    $('#date_start').focus();
                    val_chk = 0;
                  }else if(date_start!=""&&date_end==""){
                    $('#date_end').focus();
                    val_chk = 0;
                  }
                }
                if(course_status==""){
                    course_status = 4;
                }
                if(cosen_status_sub==""){
                    cosen_status_sub = 4;
                }
                if(date_start_var==""){
                    date_start_var = '0000-00-00';
                }else{
                    
                        var maxDate = new Date(start_date).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_start_var = moment(maxDate).format('YYYY-MM-DD');
                }
                if(date_end_var==""){
                    date_end_var = '0000-00-00';
                }else{
                    
                        var maxDate = new Date(end_date).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_end_var = moment(maxDate).format('YYYY-MM-DD');
                }
                if(val_chk==1){
                    window.open('<?php echo base_url(); ?>exportdata/export_reportpersonal/'+course_status+'/'+cosen_status_sub+'/'+date_start_var+'/'+date_end_var+'/'+time_start+'/'+time_end);
                }
            });
    </script>
</body>

</html>