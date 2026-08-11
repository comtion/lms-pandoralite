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
                    <div class="col-md-3 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-9 align-self-right">
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
                                        </div>
                                    <?php if($com_admin!="com_associated"&&$user['ug_id']=="1"){ ?>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12 p-0">
                                                <label for="com_id"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                                <select class="form-control select2" id="com_id" name="com_id" required style="width: 100%;">
                                                    <option value="All" selected><?php echo label('r_company'); ?></option>
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
                                            <div class="col-xl-6 col-sm-12">
                                                <button name='bt' value="submit" class="btn btn-block btn-outline-info" type="submit"><i class="mdi mdi-magnify"></i> <?php echo label('search'); ?></button>
                                            </div>
                                            <div class="col-xl-6 col-sm-12">
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
                                        <th width="10%"><?php echo textCenter(label('detail')); ?></th>
                                        <th width="15%"><?php echo textCenter(label('username')); ?></th>
                                        <th width="20%"><?php echo textCenter(label('name')); ?></th>
                                        <th width="15%"><?php echo textCenter(label('com_createdate')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('newUser')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('duplicateUser')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('removeUser')); ?></th>
                                    </tr>
                                    </thead>
                                </table>
                                <p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-format-list-bulleted"></i></button> = <b><?php echo label('r_viewDetail'); ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal bs-example-modal-lg" tabindex="-1" id="modal-detail-import" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-height: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><?php echo label('cos_report_details'); ?>: <span id="txtshow_cosname"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" id="lgi_id_search" name="lgi_id_search">
                        <div class="row">
                          <div class="col-md-6">
                                <label for="status_cr"><?php echo label('com_name'); ?>:</label>
                                <select class="form-control select2"
                                        id="com_id_report"
                                        name="com_id_report"
                                        onchange="queryEnrollByCompany()"
                                        style="width: 100%;">
                                </select>
                          </div>
                          <div class="col-md-6">
                          <?php if($btn_print=="1"){ ?><br>
                            <button name="export_button" id="export_button" class="btn btn-outline-success export_button float-right"><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
                          <?php } ?>
                          </div>
                        </div>
                        <div class="table-responsive">
                            <table id="myTable_detail" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="20%" ><center><?php echo label('username'); ?></center></th>
                                        <th width="30%" ><center><?php echo label('m_name'); ?></center></th>
                                        <th width="20%" ><center><?php echo label('m_company'); ?></center></th>
                                        <th width="15%" ><center><?php echo label('m_status'); ?></center></th>
                                        <th width="15%" ><center><?php echo label('com_createdate'); ?></center></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
        let lang = "<?php echo $lang == 'thai' ? 'th' : 'eng'; ?>";
        let isThaiYear = <?php echo $lang == 'thai' ? 'true' : 'false'; ?>;
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
            language: lang,
            thaiyear: isThaiYear 
          }).datepicker("setDate", "1");
        }

        function caldate(id){
            var val_change = changedate($('#'+id).val());  
            $('#'+id+'_var').val(val_change);
        }

        from = $('#date_start').datepicker({
            language: lang,
            thaiyear: isThaiYear,  
            orientation: 'bottom left',
            format: 'dd/mm/yyyy',
            autoclose: true
        }).on('changeDate', function (selected) {
            $('#date_end').val('');
            $('#date_start').datepicker("update", selected.date);

            to = $('#date_end').datepicker({
                language: lang,
                thaiyear: isThaiYear,  
                orientation: 'bottom left',
                format: 'dd/mm/yyyy',
                autoclose: true
            }).datepicker('setStartDate', selected.date).focus();
        });

        to = $('#date_end').datepicker({
            language: lang,
            thaiyear: isThaiYear,  
            format: 'dd/mm/yyyy',
            orientation: 'bottom left',
            autoclose: true
        });

        function fetch_data(date_start = "", time_start = "", date_end = "", time_end = "")
        {
                $('#myTable').DataTable().destroy();
                const comId = $('#com_id').val();
                const table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
                    notificationForDatatableError("myTable", message);
                }).DataTable({
                    "language": {
                        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
                        "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
                        "sInfo": "<?php echo label('sInfo'); ?>",
                        "sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
                        "decimal": "",
                        "emptyTable": "<?php echo label('wg_datanotfound'); ?>",
                        "infoPostFix": "",
                        "thousands": ",",
                        //"lengthMenu":     "แสดง _MENU_ รายการ",
                        "lengthMenu": "<?php echo label('lengthMenu'); ?>",
                        "loadingRecords": "<?php echo label('loadingRecords'); ?>",
                        "processing": "<?php echo label('processing'); ?>",
                        "search": "<?php echo label('filter_bar'); ?>",
                        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
                        "paginate": {
                            "first": "<?php echo label('firstpage'); ?>",
                            "last": "<?php echo label('last'); ?>",
                            "next": "<?php echo label('lrn_btn_next'); ?>",
                            "previous": "<?php echo label('previous'); ?>"
                        },
                    },
                    "ajax": {
                        url: '<?= base_url() ?>fetchdata/fetchLogImportUsers/',
                        type: 'GET',
                        data: {
                            comId:          comId,
                            dateStart:      date_start,
                            timeStart:      time_start,
                            dateEnd:        date_end,
                            timeEnd:        time_end,
                            lang:           "<?php echo $lang; ?>"
                        },
                    },
                    "columns": [
                        {
                            data: "detail"
                        },
                        {
                            data: "user"
                        },
                        {
                            data: "fullname"
                        },
                        {
                            data: {
                                _: "logdate.display",
                                sort: "logdate.timestamp"
                            }
                        },
                        {
                            data: "newUser"
                        },
                        {
                            data: "duplicateUser"
                        },
                        {
                            data: "removeUser"
                        }
                    ],
                    "order": [[3, "desc"]],
                    "initComplete": function() {
                        setTimeout(function() {
                            const page_num = 0;
                            var info = table.page.info();
                            var length = info.pages;
                            var page_current = info.page;
                            if ((page_num + 1) > length) {
                                page_num = length - 1;
                            }
                            table.page(page_num).draw(false);
                        }, 10);
                    }
                });
         }

        $(document).on('submit', '#search_form', function(event){
            event.preventDefault(); 
            const dateStart = $('#date_start_var').val();
            const timeStart = $('#time_start').val();
            const dateEnd = $('#date_end_var').val();
            const timeEnd = $('#time_end').val();
            fetch_data(dateStart, timeStart, dateEnd, timeEnd);
        });

        $('#export_button').click(function(){
            const lgiId = $('#lgi_id_search').val();
            const comId = $('#com_id_report').val();
            let linkExport = "<?php echo base_url(); ?>exportdata/exportLogImportUser/" + lgiId;
            if (comId != "") {
                linkExport += "/" + comId;
            }
            window.open(linkExport);
        });

        
        function fetchDetailOfLogImportUsers(lgiId)
        {
            var com_id_report = $('#com_id_report').val();
            $('#myTable_detail').DataTable().destroy();
            $('#myTable_detail').on('error.dt', function(e, settings, techNote, message) {
                notificationForDatatableError("myTable_detail", message);
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
                    url: '<?=base_url()?>index.php/report/fetchLogImportUsersDetail/',
                    data: {
                        lgi_id: lgiId,
                        com_id: com_id_report,
                        lang:   "<?php echo $lang; ?>"
                    },
                    type: 'GET'
                },
                "columns": [
                    { data: "username" },
                    { data: "fullname" },
                    { data: "company" },
                    { data: "statusImport" },
                    { data: {
                        _:    "logdate.display",
                        sort: "logdate.timestamp"
                    } }
                ]
            });
        }

        function queryEnrollByCompany(){
            var lgiId = $('#lgi_id_search').val();
            fetchDetailOfLogImportUsers(lgiId);
        }
        
        $(document).on('click', '.view_detail', function(){
            var lgiId = $(this).attr("id");
            $("#modal-detail-import").modal({backdrop: false});
            $('#lgi_id_search').val(lgiId);

            $.ajax({
                  url: '<?=base_url()?>index.php/querydata/recheckCompanyForLogImportUsers',
                  type: 'POST',
                  data:{com_id:'',lgi_id:lgiId},
                  success: function(data_company){
                    $('#com_id_report').html(data_company);
                  }
            });

            fetchDetailOfLogImportUsers(lgiId);
        });
    </script>
</body>

</html>
