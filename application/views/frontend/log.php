<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<?php 
          $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
          $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <link href="<?php echo REAL_PATH;?>/assets/css/report-theme.css?v=20260811-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border report-theme-page">
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
                    <div class="col-md-2 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-10 align-self-right">
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
                      <form method="post" id="search_form" autocomplete="off" name="search_form" enctype="multipart/form-data" accept-charset="utf-8"  class="form-horizontal p-t-20">
                         <div class="row">

                                <div class="col-12 col-md-8">
                                    <label class="control-label text-right"><?php echo label('log_re_period'); ?>:</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="text" id="date_start" name="date_start" onchange="caldate('date_start')" class="form-control date_start" required>
                                                        <input type="hidden" id="date_start_var" name="date_start_var">
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
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
                                                    <div class="form-group">
                                                        <input type="text" id="date_end" name="date_end" onchange="caldate('date_end')" class="form-control date_end" required>
                                                        <input type="hidden" id="date_end_var" name="date_end_var">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <input type="text" id="time_end" name="time_end" class="form-control" value="<?php echo date('H:i',strtotime('23:59')); ?>">
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 
                                    <div class='input-group mb-3'>
                                        <input type='text' id="daterange_report" name="daterange_report" class="form-control"  value="" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="ti-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="sDate" name="sDate" value="<?php echo date('Y-m-d H:i'); ?>">
                                    <input type="hidden" id="eDate" name="eDate" value="<?php echo date('Y-m-d H:i'); ?>"> -->
                                </div>
                            <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
                            <div class="col-12 col-md-4">
                              <div class="form-group">
                                
                                <div class="col-md-12 p-0">
                                    <label for="status_cr"><?php echo label('com_name'); ?>:</label>
                                    <select class="form-control select2" id="com_id" name="com_id"  style="width: 100%;">
                                        <option value="" selected><?php echo label('please_com_name'); ?></option>
                                        <?php foreach( $company_select as $company ){ ?>
                                            <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?></option>
                                        <?php  } ?>
                                    </select>
                                </div>
                              </div>
                            </div>
                                <?php }else{ ?>
                                        <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                <?php } ?>
                                <div class="offset-xl-6 col-xl-6">
                                    <div class="row m-0">
                                        <div class="col-xl-4 col-sm-12">
                                            <?php if($btn_print=="1"){ ?>
                                              <button name="export_button" id="export_button" class="btn btn-block btn-outline-success export_button float-right"><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
                                            <?php } ?>
                                        </div>
                                        <div class="col-xl-4 col-sm-12">
                                            <button name='bt' value="submit" class="btn btn-block btn-outline-info" type="submit"><i class="mdi mdi-magnify"></i> <?php echo label('search'); ?></button>
                                        </div>
                                        <div class="col-xl-4 col-sm-12">
                                            <button name='bt' value="reset" class="btn btn-block btn-outline-danger" onclick="location.reload()" type="submit"><i class="mdi mdi-autorenew"></i> <?php echo label('reset'); ?></button>
                                        </div>
                                    </div>
                                </div>
                          </div>
                      </form>
                      <div class="table-responsive">
                          <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="10%" align="center"><?php echo label('username'); ?></th>
                                <th width="20%" align="center"><?php echo label('name'); ?></th>
                                <th width="10%" align="center"><?php echo label('m_usergroup'); ?></th>
                                <th width="10%" align="center"><?php echo label('m_department'); ?></th>
                                <th width="10%" align="center"><?php echo label('ip_add'); ?></th>
                                <th width="10%" align="center"><?php echo label('device'); ?></th>
                                <th width="10%" align="center"><?php echo 'Action'; ?></th>
                                <th width="10%" align="center"><?php echo label('log_date'); ?></th>
                                <th width="10%" align="center"><?php echo label('log_time'); ?></th>
                              </tr>
                            </thead>
                            <!-- <tbody>
                              <?php $num = 1;
                              if(isset($logs)){
                                foreach ($logs as $row) { 
                                    if(isset($emps[$row['emp_id']]['emp_id'])){

                                          $string_msg = "";
                                          $pos = strpos($row['massage'], 'logged in website');
                                          if($pos === false){
                                            $pos = strpos($row['massage'], 'logged in fail');
                                            if($pos){
                                              $string_msg = "logged in fail";
                                            }
                                          }else{
                                            $string_msg = "logged in website";
                                          }
                                        ?>
                                <tr>
                                  <td><?php echo $emps[$row['emp_id']]['emp_c']; ?></td>
                                  <td><?php if($lang=="thai"){ echo $emps[$row['emp_id']]['fullname_th']; }else{ echo $emps[$row['emp_id']]['fullname_en']; } ?></td>
                                  <td><?php echo $emps[$row['emp_id']]['ug_name']; ?></td>
                                  <td><?php echo $emps[$row['emp_id']]['dep_name']; ?></td>
                                  <td style="min-width:auto;"><?php echo $row['ip']; ?></td>
                                  <td style="min-width:auto;"><?php echo $row['device']; ?></td>
                                  <td style="min-width:auto;"><?php echo $string_msg; ?></td>
                                  <td style="min-width:auto;"><?php if($lang=="thai"){ echo date('d ',strtotime($row['log_time'])).$arrMonthThaiTextFull[intval(date('m',strtotime($row['log_time'])))].date(' Y',strtotime($row['log_time']));}else{echo date('d F Y',strtotime($row['log_time']));} ?></td>
                                  <td style="min-width:auto;"><?php echo date('H:i',strtotime($row['log_time'])); ?></td>
                                </tr>
                            <?php   $num++;
                                    }
                                }
                              } 
                              ?>
                            </tbody> -->
                          </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- start - This is for export functionality only -->

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
<?php if($lang=="thai"){ ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
<?php } ?>

    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        $('.select2').select2();
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
                                orientation: 'bottom left',
                        format: 'dd/mm/yyyy',
                        autoclose: true
                }).on('changeDate', function (selected) {
                    $('#date_end').val('');
                    $('#date_start').datepicker("update", selected.date);
                         to = $('#date_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                orientation: 'bottom left',
                                format: 'dd/mm/yyyy',
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
                        format: 'dd/mm/yyyy',
                                orientation: 'bottom left',
                        autoclose: true
                }).on('changeDate', function (selected) {
                    $('#date_end').datepicker("update", selected.date);
                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                        var res_date = date_val.split("-");
                        maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                        $('#date_start').datepicker('setEndDate', maxDate);
                    });
          /*$('#daterange_report').addClass('show-calendar');
                        $('#daterange_report').daterangepicker({
                            showDropdowns: true,
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: false,
                            startDate: new Date('<?php echo date('Y-m-d'); ?> 00:01'),
                            endDate: new Date('<?php echo date('Y-m-d'); ?> 23:59'),
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
                            }
                        },
                       function(start, end) {
        let date = $(".drp-selected").text()
        console.log(date)
                          var valstart = start.format('YYYY-MM-DD').split("-");
                          var valend = end.format('YYYY-MM-DD').split("-");
                          var valtimestart = start.format('HH:mm:00');
                          var valtimeend = end.format('HH:mm:00');

                          console.log(valstart,valend);

                          <?php if($lang=="thai"){ ?>
                          var arrmonth = ['' ,'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                          var txt_datestart = (valstart[2]+" "+(arrmonth[parseInt(valstart[1])])+" "+(parseInt(valstart[0])+543)+" "+valtimestart);
                          var txt_dateend  = (valend[2]+" "+(arrmonth[parseInt(valend[1])])+" "+(parseInt(valend[0])+543)+" "+valtimeend);
                          <?php }else{ ?>
                          var arrmonth = ['' ,'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                          var txt_datestart = (valstart[2]+" "+(arrmonth[parseInt(valstart[1])])+" "+(parseInt(valstart[0]))+" "+valtimestart);
                          var txt_dateend  = (valend[2]+" "+(arrmonth[parseInt(valend[1])])+" "+(parseInt(valend[0]))+" "+valtimeend);
                          <?php } ?>
                          console.log(txt_datestart+" - "+txt_dateend);
                          $('#daterange_report span').html(txt_datestart+" - "+txt_dateend);
                          //$('.drp-selected').html(txt_datestart+" - "+txt_dateend);
                          $('#sDate').val(start.format('YYYY-MM-DD HH:mm:00'));
                          $('#eDate').val(end.format('YYYY-MM-DD HH:mm:00'));
                       });*/

        fetch_data();
        function fetch_data(date_start,time_start,date_end,time_end)
         {
            var com_id = $('#com_id').val();
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
            'processing': true,
                 //"serverSide": true,
                "searching": true,
                "ajax": {
                    url : '<?php echo base_url();?>index.php/manage/fetch_log/',
                    type : 'GET',
                    data : {
                        date_start: date_start,
                        time_start: time_start,
                        date_end:   date_end,
                        time_end:   time_end,
                        com_id:     com_id,
                        lang:       "<?php echo $lang; ?>"
                    }
                },
                "order": [[ 7, 'DESC' ], [ 8, 'DESC' ]],
              /*<?php if($btn_print=="1"){ ?>
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'print'
                ],
              <?php } ?>*/
              lengthMenu: [[10,25, 100, -1], [10,25, 100, "All"]],
              pageLength: 10,
            });
         }

          $(document).on('submit', '#search_form', function(event){
              event.preventDefault(); 
              var date_start_var = $('#date_start_var').val();
              var time_start = $('#time_start').val();
              var date_end_var = $('#date_end_var').val();
              var time_end = $('#time_end').val();
              fetch_data(date_start_var,time_start,date_end_var,time_end);
          });

           $('#export_button').click(function(){
                var com_id = $('#com_id').val();
                var date_start_var = $('#date_start_var').val();
                var time_start = $('#time_start').val();
                var date_end_var = $('#date_end_var').val();
                var time_end = $('#time_end').val();
                window.open('<?php echo base_url(); ?>exportdata/export_logview/'+com_id+'/'+date_start_var+'/'+time_start+'/'+date_end_var+'/'+time_end);
            });
    </script>
</body>

</html>
