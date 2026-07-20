<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- Date picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/adapter.min.js"></script>
    <script type="text/javascript" src="<?php echo REAL_PATH;?>/assets/js/vue.min.js"></script>
    <link href="<?php echo REAL_PATH;?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/css/pages/stylish-tooltip.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
          float: right;
          color: #ffffff;
          margin-right: 0px;
          margin-left: 4px; }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
          background: #398bf7;
          color: #ffffff;
          border-color: #398bf7; }

    </style>
    <style>
    /* Hide the browser's default checkbox */
    html body .bg-inverse1 {
      background-color: #474644; }
    .btn-thai_h,
    .btn-thai_h.disabled {
      background: #009D79;
      color: #ffffff;
      -webkit-box-shadow: 0 2px 2px 0 rgba(0, 157, 121, 0.14), 0 3px 1px -2px rgba(0, 157, 121, 0.2), 0 1px 5px 0 rgba(0, 157, 121, 0.12);
      box-shadow: 0 2px 2px 0 rgba(0, 157, 121, 0.14), 0 3px 1px -2px rgba(0, 157, 121, 0.2), 0 1px 5px 0 rgba(0, 157, 121, 0.12);
      border: 1px solid #009D79;
      -webkit-transition: 0.2s ease-in;
      -o-transition: 0.2s ease-in;
      transition: 0.2s ease-in; }
      .btn-thai_h:hover,
      .btn-thai_h.disabled:hover {
        background: #009D79;
        color: #ffffff;
        -webkit-box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 157, 121, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
        box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
        border: 1px solid #009D79; }
      .btn-thai_h.active, .btn-thai_h:active, .btn-thai_h:focus,
      .btn-thai_h.disabled.active,
      .btn-thai_h.disabled:active,
      .btn-thai_h.disabled:focus {
        background: #009D79;
        color: #ffffff;
        -webkit-box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
        box-shadow: 0 14px 26px -12px rgba(0, 157, 121, 0.42), 0 4px 23px 0 rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 157, 121, 0.2);
        border-color: transparent; }
    </style>  
    <style type="text/css">
      .top-right {
          position: absolute;
          top: 6px;
          right: 25px;
      }

      <?php if(isMobile()){ ?>
        #div_menu {
            width:100%;
            z-index:2;
            position:fixed;
            left:100%;
            top: 100px;
            background-color: #fff;
        }
        #x {
            position: absolute;
            top: -20px;
            right: 5px;
        }
        #onclose_divmenu_btn{
            position: absolute;
            top: 0px;
            left: -50px;
        }
      <?php } ?>
        .hover_cleartxt:hover {
          background-color: #c0392b;
          color:#ffffff;
        }
     </style>
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

        <?php if($user['Is_admin']=="0"){ ?>

<!--         <div style="background-image:url(<?php echo REAL_PATH; ?>/assets/images/bg.jpg); width:100%;  padding:17px 15px;">
          <div class="container-fluid r-aside">


                  <div class="col-lg-6">
                    <div style="vertical-align:middle;">

                        <h1 style="color:#FFF; font-weight:800;font-family: Roboto, Arial, 'trebuchet MS', Helvetica, sans-serif;">Learning Subject Overview</h1>
                        <h5 style="color:#FFF;font-family: Roboto, Arial, 'trebuchet MS', Helvetica, sans-serif;">1 Empire Tower, 45th Floor, Unit 4505, River Wing West South Sathorn Road, Yannawa, Sathorn, Bangkok 10120</h5>

                    </div>
                  </div>


          </div>
        </div> -->

        <div style="background-image:url(<?php echo REAL_PATH; ?>/images/bg.jpg); width:100%; padding:100px">
          <div class="container-fluid r-aside">
            <div class="col-lg-6">
              <div style="vertical-align:middle">
                <h1 style="color:#FFF; font-weight:600">การเรียนรู้ยอดนิยม</h1>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo label('course'); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo label('course'); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                    <div class="col-md-12 card">
                        <div class="card-body row">
                            <div class="col-md-12">

                                <div class="row">
                                  <div class="col-12">
                                    <input type="text" class="form-control col-md-4 float-right" id="txt_search" name="txt_search" placeholder="<?php echo label('search'); ?>"><br><br><hr>
                                  </div>
                                  <div id="course_load_div" class="col-12 row">
                                    
                                  </div>
                                </div>
                                <script type="text/javascript">
                                    load_dataondiv("");
                                    $(document).ready(function(){
                                      $("#txt_search").keyup(function(){
                                        var txt_search = $('#txt_search').val();
                                        console.log(txt_search);
                                        load_dataondiv(txt_search);
                                      });
                                    });
                                    function load_dataondiv(value=""){
                                        var cgcode = '<?php echo $cgcode; ?>';
                                        var wcode = '<?php echo $wcode; ?>';
                                        var isNonEnroll = '<?php echo $isNonEnroll; ?>';
                                        $.ajax({
                                            url:"<?=base_url()?>index.php/course/load_course_data",
                                            method:"POST",
                                            data:{value:value,cgcode:cgcode,wcode:wcode,isNonEnroll:isNonEnroll},
                                            success:function(data)
                                            {
                                                console.log(data);
                                                $('#course_load_div').html(data);
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </div><br>
                    </div>
                </div>

            </div>
        </div>
    </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>



    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-transfercourse" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="">Transfer Course</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form  enctype="multipart/form-data" id="transfer_form" name="transfer_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                <div class="modal-body">
                  <div class="card-body row">
                                        <div class="form-group col-md-6">
                                            <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
                                            <select class="form-control" required id="com_id_transfer" name="com_id_transfer"  style="width: 100%;">
                                                    <option value=""><?php echo label('please_com_name'); ?></option>
                                                  <?php foreach( $company_select as $company ){ ?>
                                                    <option value="<?php echo $company['com_id']; ?>"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></option>
                                                  <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('wtitle'); ?>:</label>
                                            <select class="form-control" required id="wg_id_transfer" name="wg_id_transfer"  style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('cgtitle'); ?>:</label>
                                            <select class="form-control select2" required id="cg_id_transfer" name="cg_id_transfer[]" multiple  style="width: 100%;">
                                            </select>
                                        </div>
                  </div>
                </div>
                <input type="hidden" id="cos_id_transfer" name="cos_id_transfer">
                <div class="modal-footer">
                    <input type="submit" name="action" id="action" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('m_ok'); ?>" />
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
                </div>
              </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <!-- Bootstrap tether Core JavaScript --><!-- 
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/popper.min.js"></script> -->
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

       /* $('.timeseconds').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: false,
            locale: {
                format: 'DD/MMMM/YYYY HH:mm:00',
                applyLabel: '<?php echo label("m_ok"); ?>',
                cancelLabel: '<?php echo label("cancel"); ?>',
                fromLabel: 'From',
                separator: ' to ',
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

    </script>
    <?php $this->load->view('frontend/inc/inc_coursejs.php'); ?>
</body>

</html>
