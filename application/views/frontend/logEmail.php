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
                    <div class="col-md-4 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-8 align-self-right">
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
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12 p-0">
                                                <label for="status_event"><?php echo label('statusLogEmail'); ?>:</label>
                                                <select class="form-control select2" id="status_event" name="status_event"  style="width: 100%;">
                                                    <option value="All" selected><?php echo label('m_select_all'); ?></option>
                                                    <option value="bounces"><?php echo label('email_bounces'); ?></option>
                                                    <option value="hardBounces"><?php echo label('email_hardBounces'); ?></option>
                                                    <option value="softBounces"><?php echo label('email_softBounces'); ?></option>
                                                    <option value="delivered"><?php echo label('email_delivered'); ?></option>
                                                    <option value="spam"><?php echo label('email_spam'); ?></option>
                                                    <option value="requests"><?php echo label('email_requests'); ?></option>
                                                    <option value="opened"><?php echo label('email_opened'); ?></option>
                                                    <option value="clicks"><?php echo label('email_clicks'); ?></option>
                                                    <option value="invalid"><?php echo label('email_invalid'); ?></option>
                                                    <option value="deferred"><?php echo label('email_deferred'); ?></option>
                                                    <option value="blocked"><?php echo label('email_blocked'); ?></option>
                                                    <option value="unsubscribed"><?php echo label('email_unsubscribed'); ?></option>
                                                    <option value="error"><?php echo label('email_error'); ?></option>
                                                    <option value="loadedByProxy"><?php echo label('email_loadedByProxy'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="offset-xl-12 col-xl-12">
                                        <div class="row m-0">
                                            <?php if ($user["ug_id"] == "1") { ?>
                                            <div class="col-xl-3 col-sm-12">
                                                <button type="button" name="update-log" id="update-log" class="btn btn-block btn-outline-primary update-log float-right"><i class="mdi mdi-email-variant"></i> <?php echo label('update_log_email'); ?></button>
                                            </div>
                                            <?php } ?>
                                            <div class="col-xl-3 col-sm-12">
                                                <?php if($btn_print=="1"){ ?>
                                                    <button type="button" name="export_button" id="export_button" class="btn btn-block btn-outline-success export_button float-right"><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
                                                <?php } ?>
                                            </div>
                                            <div class="col-xl-3 col-sm-12">
                                                <button name='bt' value="submit" class="btn btn-block btn-outline-info" type="submit"><i class="mdi mdi-magnify"></i> <?php echo label('search'); ?></button>
                                            </div>
                                            <div class="col-xl-3 col-sm-12">
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
                                        <th width="10%"><?php echo textCenter(label('m_company')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('username')); ?></th>
                                        <th width="15%"><?php echo textCenter(label('name')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('m_usergroup')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('m_department')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('ip_add')); ?></th>
                                        <th width="25%"><?php echo textCenter(label('email_subject')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('com_createdate')); ?></th>
                                        <th width="10%"><?php echo textCenter(label('statusLogEmail')); ?></th>
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
                autoclose: true,
            });
                    

            function fetch_data(date_start = "", time_start = "", date_end = "", time_end = "")
            {
                $('#myTable').DataTable().destroy();
                const comId = $('#com_id').val();
                const statusEvent = $('#status_event').val();
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
                        url: '<?= base_url() ?>fetchdata/fetchLogEmail/',
                        type: 'GET',
                        data: {
                            comId:          comId,
                            statusEvent:    statusEvent,
                            dateStart:      date_start,
                            timeStart:      time_start,
                            dateEnd:        date_end,
                            timeEnd:        time_end,
                            lang:           "<?php echo $lang; ?>"
                        },
                    },
                    "columns": [
                        {
                            data: "company"
                        },
                        {
                            data: "user"
                        },
                        {
                            data: "fullname"
                        },
                        {
                            data: "usergroup"
                        },
                        {
                            data: "department"
                        },
                        {
                            data: "ipaddress"
                        },
                        {
                            data: "subject"
                        },
                        {
                            data: {
                            _: "logdate.display",
                            sort: "logdate.timestamp"
                            }
                        },
                        {
                            data: "status"
                        }
                    ],
                    "order": [[6, "desc"]]
                });
            }

            $(document).on('click', '.update-log', function(){
                $.ajax({
                    url:"<?=base_url()?>index.php/saveEmailLog/updateLog",
                    method:"POST",
                    dataType:"json",
                    success:function(data)
                    {
                        if(data.status == 2){
                            swal(
                                '<?php echo label("msg_update_success"); ?>',
                                '',
                                'success'
                            ).then(function () {
                                const dateStart = $('#date_start_var').val();
                                const timeStart = $('#time_start').val();
                                const dateEnd = $('#date_end_var').val();
                                const timeEnd = $('#time_end').val();
                                fetch_data(dateStart, timeStart, dateEnd, timeEnd);
                            })
                        } else {
                            swal({
                                title: '<?php echo label('msg_update_fail'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('m_ok'); ?>'
                            })
                        }
                    }
                });
            });

            $(document).on('submit', '#search_form', function(event){
                event.preventDefault(); 
                const dateStart = $('#date_start_var').val();
                const timeStart = $('#time_start').val();
                const dateEnd = $('#date_end_var').val();
                const timeEnd = $('#time_end').val();
                fetch_data(dateStart, timeStart, dateEnd, timeEnd);
            });

            $('#export_button').click(function(){
                const comId = $('#com_id').val();
                const statusEvent = $('#status_event').val();
                const dateStart = $('#date_start_var').val();
                const timeStart = $('#time_start').val();
                const dateEnd = $('#date_end_var').val();
                const timeEnd = $('#time_end').val();
                if (dateStart != "" && dateEnd != "") {
                    window.open('<?php echo base_url(); ?>exportdata/exportLogEmail/'+comId+'/'+statusEvent+'/'+dateStart+'/'+timeStart+'/'+dateEnd+'/'+timeEnd);
                }
            });
    </script>
</body>

</html>