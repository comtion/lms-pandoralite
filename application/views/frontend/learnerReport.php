<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    $this->load->view('frontend/inc/inc-meta-dashboard.php'); 
?>

<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css"
    href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<!-- Date picker plugins css -->
<link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
    type="text/css" />
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
            <p class="loader__label">
                <?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
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
                            <li class="breadcrumb-item"><a
                                    href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a>
                            </li>
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
                            <form enctype="multipart/form-data" id="search_form" name="search_form" autocomplete="off"
                                method="POST" accept-charset="utf-8" class="form-horizontal p-t-20">
                                <div class="row">
                                    <div class="col-6 col-md-4">
                                        <div class="form-group">
                                            <label for="com_id"><?php echo label('com_name'); ?>:</label>
                                            <select class="form-control select2" id="com_id" name="com_id"
                                                style="width: 100%;">
												<?php if (countArray($company_select) > 0) { ?>
													<optgroup label="<?php echo label('please_com_name'); ?>">
														<?php if ($user['ug_id'] == "1") { ?>
															<option value="" selected><?php echo label('allcompany'); ?></option>
														<?php }
														foreach ($company_select as $keyCompany => $valueCompany) { ?>
															<option value="<?php echo $valueCompany['com_id']; ?>">
																<?php echo $lang == "thai" ? $valueCompany['com_name_th'] : $valueCompany['com_name_eng']; ?>
															</option>
														<?php } ?>
													</optgroup>
												<?php   } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-12">
                                        <div class="form-group">
                                            <label for="cos_id"><?php echo label('ceCname'); ?>:</label>
                                            <select class="form-control select2" id="cos_id" name="cos_id"
                                                style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="cosen_status_sub"><?php echo label('learning_status'); ?>:</label>
                                            <select class="form-control" id="cosen_status_sub" name="cosen_status_sub"
                                                style="width: 100%;">
                                                <option value=""><?php echo label('r_company'); ?></option>
                                                <option value="0"><?php echo label('not_start'); ?></option>
                                                <option value="2"><?php echo label('inProgress'); ?></option>
                                                <option value="1"><?php echo label('r_pass'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <label class="control-label text-right"><?php echo label('date_passcourse'); ?>:</label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" id="date_start" name="date_start"
                                                                onchange="caldate('date_start')"
                                                                class="form-control date_start">
                                                            <input type="hidden" id="date_start_var"
                                                                name="date_start_var">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group clockpicker "
                                                                data-placement="bottom" data-align="top"
                                                                data-autoclose="true">
                                                                <input type="text" id="time_start" name="time_start"
                                                                    class="form-control"
                                                                    value="<?php echo date('H:i',strtotime('00:00')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" id="date_end" name="date_end"
                                                                onchange="caldate('date_end')"
                                                                class="form-control date_end">
                                                            <input type="hidden" id="date_end_var" name="date_end_var">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group clockpicker "
                                                                data-placement="bottom" data-align="top"
                                                                data-autoclose="true">
                                                                <input type="text" id="time_end" name="time_end"
                                                                    class="form-control"
                                                                    value="<?php echo date('H:i',strtotime('23:59')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-xl-4 col-xl-8 col-md-12">
                                        <div class="row">
                                            <?php if($btn_print=="1"){ ?>
                                            <div class="col-xl-4 col-md-12 d-flex flex-column">
                                                <button type="button"
                                                        name="export_button"
                                                        id="export_button"
                                                        class="btn btn-block btn-outline-success export_button"
                                                ><i class="mdi mdi-file-excel"></i> <?php echo label('export_data_main'); ?></button>
                                            </div>
                                            <?php } ?>
                                            <div class="col-xl-4 col-md-12 d-flex flex-column">
                                                <input  type="submit" name="action" id="action"
                                                        class="btn btn-block btn-outline-info btn-block"
                                                        value="<?php echo label('search'); ?>" />
                                            </div>
                                            <div class="col-xl-4 col-md-12 d-flex flex-column">
                                                <button type="reset" class="btn btn-block btn-outline-danger btn-block"
                                                        onclick="onclear()"><?php echo label('m_cancel'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="table-responsive">

                                <table id="myTable" width="2150" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="200"><?php echo textCenter(label('ceCname')." TH"); ?></th>
                                            <th width="200"><?php echo textCenter(label('ceCname')." ENG"); ?></th>
                                            <th width="200"><?php echo textCenter(label('ceCname')." JP"); ?></th>
                                            <th width="150"><?php echo textCenter(label('cos_hour')); ?></th>
                                            <th width="150"><?php echo textCenter(label('perioddate')); ?></th>
                                            <th width="150"><?php echo textCenter(label('da_approve_creator')); ?></th>
                                            <th width="100"><?php echo textCenter(label('create_date')); ?></th>
                                            <th width="150"><?php echo textCenter(label('com_name')); ?></th>
                                            <th width="200"><?php echo textCenter(label('m_username')); ?></th>
                                            <th width="250"><?php echo textCenter(label('m_name')." TH"); ?></th>
                                            <th width="250"><?php echo textCenter(label('m_name')." ENG"); ?></th>
                                            <th width="150"><?php echo textCenter(label('r_finish_emp')); ?></th>
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
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript">
    </script>
    <script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js">
    </script>
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
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <?php if($lang=="thai"){ ?>
    <script
        src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js">
    </script>
    <script
        src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js"
        charset="UTF-8"></script>
    <?php } ?>
    <script type="text/javascript">
    $.fn.dataTable.ext.errMode = "none";

    $('.select2').select2();
    function dropdownCourses() {
        $.ajax({
            url: '<?=base_url("workgroup/selectCourseAll")?>',
            type: 'POST',
            success: function(data) {
                $('#cos_id').html(data);
            }
        });
    }

    function onclear() {
        $('#cos_id').empty();
        dropdownCourses();
        $('#myTable').DataTable().destroy();
    }
    onclear();

    $('.clockpicker').clockpicker({
        placement:  'bottom',
        align:      'left',
        autoclose:  true,
        donetext:   'Done',
    });

    function changedate(value) {
        const dateArr = value != "" ? value.split("/") : [];
        const lang = "<?php echo $lang == 'thai' ? 'th' : 'eng'; ?>";
        if (dateArr.length > 0) {
            if (lang == "th") {
                return (parseInt(dateArr[2]) - 543) + "-" + dateArr[1] + "-" + dateArr[0];
            } else {
                return (parseInt(dateArr[2])) + "-" + dateArr[1] + "-" + dateArr[0];
            }
        } else {
            return "";
        }
    }

    function date_picker(id) {
        jQuery('#' + id).datepicker({
            format: 'dd/mm/yyyy',
            language: 'th',
            thaiyear: true
        }).datepicker("setDate", "1");
    }

    function caldate(id) {
        var val_change = changedate($('#' + id).val());
        $('#' + id + '_var').val(val_change);
    }
    const lang = "<?php echo $lang == 'thai' ? 'th' : 'eng'; ?>";
    const isThaiYear = <?php echo $lang == 'thai' ? 'true' : 'false'; ?>;
    from = $('#date_start').datepicker({
        language: lang,
        thaiyear: isThaiYear,
        format: 'dd/mm/yyyy',
        orientation: 'bottom left',
        autoclose: true
    }).on('changeDate', function(selected) {
        $('#date_end').val('');
        $('#date_start').datepicker("update", selected.date);
        to = $('#date_end').datepicker({
            language: lang,
            thaiyear: isThaiYear,
            format: 'dd/mm/yyyy',
            orientation: 'bottom left',
            autoclose: true
        }).datepicker('setStartDate', selected.date).focus().on('changeDate', function(selected) {
            var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {
                timeZone: "Asia/Bangkok"
            });
            var date_val = moment(maxDate).format('YYYY-MM-DD');
            var res_date = date_val.split("-");
            maxDate = res_date[2] + "/" + res_date[1] + "/" + (parseInt(res_date[0]));
            console.log(maxDate, selected.date.valueOf());
            $('#date_start').datepicker('setEndDate', maxDate);
        });
    });
    to = $('#date_end').datepicker({
        language: lang,
        thaiyear: isThaiYear,
        orientation: 'bottom left',
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(selected) {
        $('#date_end').datepicker("update", selected.date);
        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {
            timeZone: "Asia/Bangkok"
        });
        var date_val = moment(maxDate).format('YYYY-MM-DD');
        var res_date = date_val.split("-");
        maxDate = res_date[2] + "/" + res_date[1] + "/" + (parseInt(res_date[0]));
        $('#date_start').datepicker('setEndDate', maxDate);
    });
    // fetchDataLearnerReport();

    function fetchDataLearnerReport() {
        const com_id = $('#com_id').val();
        const cos_id = $('#cos_id').val();
        const cosen_status_sub = $('#cosen_status_sub').val();
        const date_start = $('#date_start_var').val();
        const time_start = $('#time_start').val();
        const date_end = $('#date_end_var').val();
        const time_end = $('#time_end').val();

        $('#myTable').DataTable().destroy();
        $('#myTable').on('error.dt', function(e, settings, techNote, message) {
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
            "scrollX": true,
            "ajax": {
                url: '<?=base_url("report/fetchLearnerReport/")?>',
                data: {
                    com_id: com_id,
                    cos_id: cos_id,
                    cosen_status_sub: cosen_status_sub,
                    date_start: date_start,
                    time_start: time_start,
                    date_end: date_end,
                    time_end: time_end
                },
                type: 'GET'
            },
            "pageLength": 50,
        });
    }

    $('#export_button').click(function(){
        const comId = $('#com_id').val();
        const cosId = $('#cos_id').val();
        const cosenStatusSub = $('#cosen_status_sub').val();
        const dateStart = $('#date_start_var').val();
        const timeStart = $('#time_start').val();
        const dateEnd = $('#date_end_var').val();
        const timeEnd = $('#time_end').val();
        const newForm = jQuery('<form>', {
            'action': '<?=base_url("exportdata/exportLearnerReport/")?>',
            'target': '_blank'
        }).append(jQuery('<input>', {
            'name': 'comId',
            'value': comId,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'cosId',
            'value': cosId,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'cosenStatusSub',
            'value': cosenStatusSub,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'dateStart',
            'value': dateStart,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'timeStart',
            'value': timeStart,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'dateEnd',
            'value': dateEnd,
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': 'timeEnd',
            'value': timeEnd,
            'type': 'hidden'
        }));
        $(document.body).append(newForm);
        newForm.submit();
    });

    $(document).on('submit', '#search_form', function(event) {
        event.preventDefault();
        var com_id = $('#com_id').val();
        var cos_id = $('#cos_id').val();
        var cosen_status_sub = $('#cosen_status_sub').val();

        var date_start_var = $('#date_start_var').val();
        var time_start = $('#time_start').val();
        if (time_start == "") {
            time_start = "00:00";
        }
        var date_end_var = $('#date_end_var').val();
        var time_end = $('#time_end').val();
        if (time_end == "") {
            time_end = "00:00";
        }
        var val_chk = 1;
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if (date_start != "" || date_end != "") {
            if (date_start != "" && date_end != "") {
                date_start_var = date_start_var + " " + time_start + ":00";
                date_end_var = date_end_var + " " + time_end + ":00";
                start_date = date_start_var.replace(/-/g, "/");
                end_date = date_end_var.replace(/-/g, "/");
                var d_start = new Date(start_date);
                var d_end = new Date(end_date);
                if (date_start_var == date_end_var) {
                    $('#time_end').focus();
                    val_chk = 0;
                } else if (d_start > d_end) {
                    $('#date_end').val("");
                    $('#date_end_var').val("");
                    $('#date_end').focus();
                    val_chk = 0;
                }
            } else if (date_start == "" && date_end != "") {
                $('#date_start').focus();
                val_chk = 0;
            } else if (date_start != "" && date_end == "") {
                $('#date_end').focus();
                val_chk = 0;
            }
        }
        if (val_chk == 1) {
            fetchDataLearnerReport();
        }
    });
    </script>
</body>

</html>