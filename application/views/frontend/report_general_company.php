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
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                                <?php if($com_admin!="com_associated"&&($user['ug_id']=="1")){ ?>
                                                <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                                <select class="form-control select2" required id="com_id" name="com_id"  style="width: 100%;">
                                                        <option value="" selected><?php echo label('allcompany'); ?></option>
                                                      <?php foreach( $company_select as $company ){ ?>
                                                        <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_eng']; } ?></option>
                                                      <?php } ?>
                                                </select>
                                                <?php }else{ ?>
                                                    <input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
                                                <?php } ?>
                                  </div>
                                </div>
                            </div>
                            <?php if($com_admin!="com_associated"){ ?>
                            <hr>
                            <?php } ?>
                            <div class="table-responsive">
                                  <table id="myTable" width="100%" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                        <?php if($user['ug_id']=="1"){ ?>
                                        <th width="30%"><center><?php echo label('com_name'); ?></center></th>
                                        <?php } ?>
                                        <th width="10%"><center><?php echo label('num_id'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_activeacc'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_admin'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_instructor'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_learner'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_course'); ?></center></th>
                                        <th width="10%"><center><?php echo label('num_survey'); ?></center></th>
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
    <!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
    <script type="text/javascript">
         $('.select2').select2();

        $.fn.dataTable.ext.errMode = "none";
        <?php if($com_admin!="com_associated"){ ?>
            fetch_data_company('');
        <?php }else{ ?>
            fetch_data_company('<?php echo $com_id; ?>');
        <?php } ?>

        $('select[name="com_id"]').on('change', function(){
          var com_id = $(this).val();
          if(com_id!=""){
            fetch_data_company(com_id);
          }else{
            <?php if($com_admin!="com_associated"){ ?>
                fetch_data_company('');
            <?php }else{ ?>
                fetch_data_company('<?php echo $com_id; ?>');
            <?php } ?>
          }
        });
        function fetch_data_company(com_id)
         {
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
                    url : '<?=base_url()?>index.php/report/fetch_course_company/',
                    data : {
                        com_id:com_id,
                        lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
                <?php if($btn_print=="1"){?>
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'print'
                ]
                <?php } ?>
            });
         }
    </script>
</body>

</html>