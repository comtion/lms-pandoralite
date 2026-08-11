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
    <style>
      :root{--report-red:#ed1c24;--report-ink:#172033;--report-muted:#7b8498;--report-line:#e2e7ef;--report-soft:#f7f9fc;--report-green:#18875e}
      .personal-report .container-fluid{padding:32px 32px 48px}
      .personal-report__head{display:flex;justify-content:space-between;align-items:flex-start;margin:0 0 28px;padding:0}
      .personal-report__head h1{font-size:28px;line-height:1.25;font-weight:700;color:var(--report-ink);margin:0 0 8px}
      .personal-report__head p{font-size:14px;color:var(--report-muted);margin:0}
      .personal-report__head .breadcrumb{justify-content:flex-end;margin:2px 0 16px;background:transparent;padding:0}
      .personal-report__head .breadcrumb a{color:#7c8597}
      .personal-report__head .breadcrumb-item.active{color:var(--report-red)}
      .report-export{height:44px;padding:0 18px;border:1px solid rgba(237,28,36,.55);border-radius:8px;background:#fff;color:var(--report-red);font-weight:600;cursor:pointer}
      .report-export:hover{background:#fff5f5;border-color:var(--report-red)}
      .report-overview{display:grid;grid-template-columns:repeat(4,1fr);margin:0 0 20px;padding:12px 0}
      .report-stat{text-align:center;padding:6px 20px;border-right:1px solid var(--report-line)}
      .report-stat:last-child{border-right:0}.report-stat__label{display:block;color:#596277;font-size:14px;font-weight:600;margin-bottom:8px}
      .report-stat strong{display:block;color:var(--report-red);font-size:31px;line-height:1;font-weight:700;margin-bottom:6px}
      .report-stat small{font-size:13px;color:#8a93a5}
      .report-panel{background:#fff;border:1px solid var(--report-line);border-radius:12px;box-shadow:0 5px 20px rgba(31,42,68,.04);overflow:hidden}
      .report-filters{display:grid;grid-template-columns:1fr 1fr 1.65fr auto auto;gap:14px;align-items:end;padding:22px 24px;background:#fff;border-bottom:1px solid var(--report-line)}
      .report-field label{display:block;font-size:12px;font-weight:600;color:#657086;margin:0 0 7px}
      .report-field .form-control{height:44px;border:1px solid #dce2eb;border-radius:8px;color:#30394c;background:#fff;padding:9px 12px;box-shadow:none}
      .report-date{display:grid;grid-template-columns:minmax(105px,1fr) 82px 20px minmax(105px,1fr) 82px;gap:7px;align-items:center}.report-date__dash{text-align:center;color:#9ba4b3}
      .report-action{height:44px;padding:0 22px;border-radius:8px;border:1px solid transparent;font-weight:600;cursor:pointer;white-space:nowrap}
      .report-action--primary{background:var(--report-red);color:#fff}.report-action--primary:hover{background:#d9151d}
      .report-action--quiet{background:#fff;border-color:#dce2eb;color:#566075}.report-action--quiet:hover{background:var(--report-soft)}
      .report-table-wrap{padding:20px 24px 14px}.report-table-wrap .dataTables_wrapper{font-size:13px;color:#4e586d}
      .report-table-wrap .dataTables_length{float:left}.report-table-wrap .dataTables_filter{float:right;margin:0 0 14px}
      .report-table-wrap .dataTables_filter label{font-size:12px;font-weight:600;color:#657086}
      .report-table-wrap .dataTables_filter input{height:40px;width:230px!important;margin-left:10px;border:1px solid #dce2eb;border-radius:8px;padding:8px 12px;background:#fff}
      .report-table-wrap .dataTables_length select{height:40px;border:1px solid #dce2eb;border-radius:8px;padding:4px 28px 4px 10px}
      #myTable{width:100%!important;border-collapse:separate!important;border-spacing:0;border:1px solid var(--report-line)!important;border-radius:10px;overflow:hidden;margin-top:8px!important}
      #myTable thead th{background:#f8fafc!important;color:#5b6579!important;font-size:12px;font-weight:700;line-height:1.35;border:0!important;border-bottom:1px solid var(--report-line)!important;padding:16px 12px!important;vertical-align:middle}
      #myTable tbody td{background:#fff!important;border:0!important;border-bottom:1px solid var(--report-line)!important;padding:17px 12px!important;vertical-align:middle;color:#3f495d;line-height:1.45}
      #myTable tbody tr:last-child td{border-bottom:0!important}#myTable tbody tr:hover td{background:#fcfdff!important}
      #myTable tbody td:nth-child(2){font-weight:600;color:#273145;min-width:220px}
      #myTable .btn-info{background:#f0faf6;border-color:#b9e6d0;color:var(--report-green);border-radius:6px}
      .report-table-wrap .dataTables_info{padding-top:18px;color:#8a93a5}.report-table-wrap .dataTables_paginate{padding-top:12px}
      .report-table-wrap .page-item .page-link{border:1px solid #dfe4ec;color:#5f687a;border-radius:7px!important;margin:0 3px;min-width:38px;text-align:center}
      .report-table-wrap .page-item.active .page-link{background:var(--report-red);border-color:var(--report-red);color:#fff}
      .report-note{margin:14px 2px 0;color:#8a93a5;font-size:12px}.report-note .btn{margin:0 5px}
      @media(max-width:1400px){.report-filters{grid-template-columns:1fr 1fr;}.report-field--date{grid-column:1/-1}.report-action{width:100%}}
      @media(max-width:991px){.personal-report .container-fluid{padding:24px 18px 40px}.personal-report__head{display:block}.personal-report__head-right{margin-top:16px}.personal-report__head .breadcrumb{justify-content:flex-start}.report-overview{grid-template-columns:1fr 1fr}.report-stat:nth-child(2){border-right:0}.report-stat:nth-child(-n+2){border-bottom:1px solid var(--report-line);padding-bottom:18px;margin-bottom:12px}.report-filters{grid-template-columns:1fr}.report-field--date{grid-column:auto}.report-date{grid-template-columns:1fr 76px}.report-date__dash{display:none}.report-table-wrap{padding:16px 14px}}
      @media(max-width:575px){.report-overview{grid-template-columns:1fr}.report-stat{border-right:0!important;border-bottom:1px solid var(--report-line);padding:14px}.report-stat:last-child{border-bottom:0}.report-date{grid-template-columns:1fr 72px}.report-table-wrap .dataTables_filter,.report-table-wrap .dataTables_length{float:none;text-align:left}.report-table-wrap .dataTables_filter input{width:100%!important;margin:8px 0 0}.report-table-wrap .dataTables_filter label{width:100%}}
    </style>
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
        <div class="page-wrapper personal-report">
            <div class="container-fluid">
                <header class="personal-report__head">
                    <div>
                        <h1><?php echo $lang === 'thai' ? 'รายงานการเรียนรู้ของฉัน' : 'My learning report'; ?></h1>
                        <p><?php echo $lang === 'thai' ? 'ภาพรวมผลการเรียนรู้ ความก้าวหน้า และสถานะการผ่านหลักสูตร' : 'Review your learning progress, scores, and course results.'; ?></p>
                    </div>
                    <div class="personal-report__head-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?><li class="breadcrumb-item"><?php echo ucwords(strtolower($title_main)); ?></li><?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                        <?php if($btn_print=="1"){ ?><button type="button" name="export_button" id="export_button" class="report-export"><i class="mdi mdi-download"></i> <?php echo label('export_data_main'); ?></button><?php } ?>
                    </div>
                </header>

                <section class="report-overview" aria-label="<?php echo $lang === 'thai' ? 'สรุปผลการเรียน' : 'Learning summary'; ?>">
                    <div class="report-stat"><span class="report-stat__label"><?php echo $lang === 'thai' ? 'หลักสูตรทั้งหมด' : 'Total courses'; ?></span><strong id="report-total">0</strong><small><?php echo $lang === 'thai' ? 'หลักสูตร' : 'courses'; ?></small></div>
                    <div class="report-stat"><span class="report-stat__label"><?php echo $lang === 'thai' ? 'เรียนจบแล้ว' : 'Completed'; ?></span><strong id="report-completed">0</strong><small><?php echo $lang === 'thai' ? 'หลักสูตร' : 'courses'; ?></small></div>
                    <div class="report-stat"><span class="report-stat__label"><?php echo $lang === 'thai' ? 'อัตราผ่าน' : 'Pass rate'; ?></span><strong id="report-pass-rate">0%</strong><small><?php echo $lang === 'thai' ? 'ผ่านเกณฑ์' : 'passed'; ?></small></div>
                    <div class="report-stat"><span class="report-stat__label"><?php echo $lang === 'thai' ? 'คะแนนเฉลี่ย' : 'Average score'; ?></span><strong id="report-average">0</strong><small><?php echo $lang === 'thai' ? 'คะแนน' : 'points'; ?></small></div>
                </section>

                <section class="report-panel">
                    <form enctype="multipart/form-data" id="search_form" name="search_form" autocomplete="off" method="POST" accept-charset="utf-8" class="report-filters">
                        <div class="report-field"><label for="course_status"><?php echo label('r_result'); ?></label><select class="form-control" id="course_status" name="course_status"><option value=""><?php echo label('r_company'); ?></option><option value="1"><?php echo label('open'); ?></option><option value="0"><?php echo label('close'); ?></option></select></div>
                        <div class="report-field"><label for="cosen_status_sub"><?php echo label('learning_status'); ?></label><select class="form-control" id="cosen_status_sub" name="cosen_status_sub"><option value=""><?php echo label('r_company'); ?></option><option value="0"><?php echo label('not_start'); ?></option><option value="2"><?php echo label('inProgress'); ?></option><option value="1"><?php echo label('r_pass'); ?></option></select></div>
                        <div class="report-field report-field--date"><label for="date_start"><?php echo label('date_passcourse'); ?></label><div class="report-date"><input type="text" id="date_start" name="date_start" onchange="caldate('date_start')" class="form-control date_start" placeholder="<?php echo $lang === 'thai' ? 'วันเริ่มต้น' : 'Start date'; ?>"><input type="hidden" id="date_start_var" name="date_start_var"><div class="clockpicker" data-placement="bottom" data-align="top" data-autoclose="true"><input type="text" id="time_start" name="time_start" class="form-control" value="<?php echo date('H:i',strtotime('00:00')); ?>"></div><span class="report-date__dash">–</span><input type="text" id="date_end" name="date_end" onchange="caldate('date_end')" class="form-control date_end" placeholder="<?php echo $lang === 'thai' ? 'วันสิ้นสุด' : 'End date'; ?>"><input type="hidden" id="date_end_var" name="date_end_var"><div class="clockpicker" data-placement="bottom" data-align="top" data-autoclose="true"><input type="text" id="time_end" name="time_end" class="form-control" value="<?php echo date('H:i',strtotime('23:59')); ?>"></div></div></div>
                        <button type="submit" name="action" id="action" class="report-action report-action--primary"><i class="mdi mdi-magnify"></i> <?php echo label('search'); ?></button>
                        <button type="reset" class="report-action report-action--quiet" onclick="onclear()"><i class="mdi mdi-refresh"></i> <?php echo $lang === 'thai' ? 'รีเซ็ต' : 'Reset'; ?></button>
                    </form>
                    <div class="report-table-wrap"><div class="table-responsive"><table id="myTable" class="table">
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
                                  </table></div><p class="report-note"><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-comment-text-outline"></i></button> = <b><?php echo label('answer'); ?></b></p></div>
                </section>
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
                $('#date_start_var, #date_end_var').val('');
                $('#course_status, #cosen_status_sub').val('');
                $('#time_start').val('00:00');
                $('#time_end').val('23:59');
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

        function reportPlainText(value){
          return $('<div>').html(value == null ? '' : String(value)).text().replace(/\s+/g, ' ').trim();
        }

        function updateReportOverview(rows){
          rows = Array.isArray(rows) ? rows : [];
          var total = rows.length;
          var completed = 0;
          var passed = 0;
          var scoreTotal = 0;
          var scoreCount = 0;
          rows.forEach(function(row){
            var learning = reportPlainText(row.status_learner).toLowerCase();
            var result = reportPlainText(row.preReport).toLowerCase();
            if(/complete|completed|pass|สำเร็จ|ผ่าน/.test(learning)){ completed++; }
            if(result && !/fail|ไม่ผ่าน/.test(result) && /pass|ผ่าน/.test(result)){ passed++; }
            var score = parseFloat(reportPlainText(row.score_posttest).replace(/,/g, ''));
            if(!isNaN(score)){ scoreTotal += score; scoreCount++; }
          });
          $('#report-total').text(total);
          $('#report-completed').text(completed);
          $('#report-pass-rate').text(total ? Math.round((passed / total) * 100) + '%' : '0%');
          $('#report-average').text(scoreCount ? Math.round(scoreTotal / scoreCount) : '0');
        }
        
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
                    type : 'GET',
                    dataSrc : function(json){
                      var rows = json && Array.isArray(json.data) ? json.data : [];
                      updateReportOverview(rows);
                      return rows;
                    }
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
