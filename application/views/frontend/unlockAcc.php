<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<?php 
          $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
          $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <style type="text/css">
        .clockpicker-popover {
            z-index: 999999;
        }
        #myModal_process.modal.show .modal-dialog{
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        margin: 0;
      }
      #myModal_process .circle strong{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:1.8em;
      }
      #myModal_process .circle canvas{
        visibility: hidden;
      }
      #myModal_process #circle-b{
        margin:0;
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
                      <div class="table-responsive">
                          <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th width="10%"><center><!-- <?php echo label('number'); ?> --></center></th>
                                <!-- <th width="15%"><center><?php echo label('emp_id'); ?></center></th> -->
                                <th width="15%"><center><?php echo label('username'); ?></center></th>
                                <th width="20%"><center><?php echo label('name'); ?></center></th>
                                <th width="15%" align="center">
                                  <center><?php echo label('m_company'); ?></center>
                                </th>
                                <th width="15%"><center><?php echo label('block_datetime'); ?></center></th>
                                <th width="10%"><center><?php echo label('unlock_account'); ?></center></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $number = 1;
                                    foreach ($accounts as $row) {
                                      if (isset($companyCode[$row["com_id"]])) {
                              ?>
                                <tr>
                                  <td><?php echo $number; ?></td>
                                  <!-- <td style="min-width:auto;"><?php echo $row['emp_c'] ?></td> -->
                                  <td style="min-width:auto;"><?php echo $row['useri']; ?></td>
                                  <td style="min-width:auto;"><?php if($lang=="thai"){ echo $row['fullname_th']; }else{ echo $row['fullname_en']; } ?></td>
                                  <td style="min-width:auto;" align="center"><?php echo $companyCode[$row["com_id"]]; ?></td>

                                  <td style="min-width:auto;">
                                    <?php
                                      if($row['u_lockdate']!="0000-00-00 00:00:00"){
                                          echo $lang=="thai"?date('d/m/',strtotime($row['u_lockdate'])).(date('Y',strtotime($row['u_lockdate']))+543)." ".date('H:i',strtotime($row['u_lockdate'])):date('d/m/Y H:i',strtotime($row['u_lockdate']));
                                      } ?>
                                  </td>
                                  <td style="min-width:auto;" align="center">
                                    <center>
                                      <!-- <form action="<?php echo REAL_PATH.'/dashboard/unlockUser'; ?>" class="form-inline" method="post">
                                        <input type="hidden" name="emp_id" value="<?php echo $row['emp_id']; ?>">
                                        <input type="hidden" name="useri" value="<?php echo $row['useri']; ?>"> -->
                                        <button class="btn btn-default btnunlock display" data-emp_id="<?php echo $row['emp_id']; ?>" data-useri="<?php echo $row['useri']; ?>" type="button" ><i class="mdi mdi-lock-open"></i> <?php echo label('unlock') ?></button>
                                      <!-- </form> -->
                                    </center>
                                  </td>
                                </tr>
                              <?php $number++;
                                      }
                                    }
                              ?>
                            </tbody>
                          </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
      <div
        id="myModal_process"
        class="modal bs-example-modal-lg"
        role="dialog"
        aria-labelledby="smallModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body" align="center" style="max-height: 300px;">
              <div class="circle" id="circle-b">
                <strong></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

    <script type="text/javascript">

           $('.btnunlock').click(function(){
                var emp_id = $(this).data("emp_id");
                var useri = $(this).data("useri");
                $("#myModal_process").modal('show');
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/dashboard/unlockUser",
                  method:"POST",
                  data:{emp_id:emp_id,useri:useri},
                        xhr: function() {
                          /*document.getElementById("progress_publicsurvey_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                      $('#txt_progress_publicsurvey').text(percentComplete.toFixed(2) + '%');

                                       $('.progress-bar-publicsurvey').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;*/
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                  var percentComplete = (evt.loaded / evt.total) * 100;
                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(0),
                                            fill: {
                                            color: '#ffa500'
                                          }
                                        }

                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                                  }
                             }, false);
                             return xhr;
                        },
                  success:function(data)
                  {
                      location.reload();
                  }
                });
                //window.location.href = '<?php echo base_url(); ?>dashboard/unlockUser/'+emp_id+'/'+useri;
                //window.open('<?php echo base_url(); ?>dashboard/unlockUser/'+emp_id+'/'+useri);
            });
    $('.slimtest1').perfectScrollbar();
    $('#myTable').DataTable({
      
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
    });
    </script>
</body>

</html>