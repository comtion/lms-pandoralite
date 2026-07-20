<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); 
    $arrMonthThaiTextShort = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
    $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo REAL_PATH;?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!-- <style type="text/css">
      iframe {
          width: 100%;
          height: 100%;
      }
      iframe.fullScreen {
          width: 100%;
          height: 100%;
          position: absolute;
          top: 0;
          left: 0;
      }
      img {
            width: 100%; //Fit the large images.
            min-width: 100%; //Fit the small images.
      }
      
    </style> --> <style type="text/css">
/* Center the loader */
.cssload-thecube {
  width: 73px;
  height: 73px;
  margin: 0 auto;
  margin-top: 49px;
  position: relative;
  transform: rotateZ(45deg);
    -o-transform: rotateZ(45deg);
    -ms-transform: rotateZ(45deg);
    -webkit-transform: rotateZ(45deg);
    -moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
  position: relative;
  transform: rotateZ(45deg);
    -o-transform: rotateZ(45deg);
    -ms-transform: rotateZ(45deg);
    -webkit-transform: rotateZ(45deg);
    -moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
  float: left;
  width: 50%;
  height: 50%;
  position: relative;
  transform: scale(1.1);
    -o-transform: scale(1.1);
    -ms-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
}
.cssload-thecube .cssload-cube:before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(43,160,199);
  animation: cssload-fold-thecube 2.76s infinite linear both;
    -o-animation: cssload-fold-thecube 2.76s infinite linear both;
    -ms-animation: cssload-fold-thecube 2.76s infinite linear both;
    -webkit-animation: cssload-fold-thecube 2.76s infinite linear both;
    -moz-animation: cssload-fold-thecube 2.76s infinite linear both;
  transform-origin: 100% 100%;
    -o-transform-origin: 100% 100%;
    -ms-transform-origin: 100% 100%;
    -webkit-transform-origin: 100% 100%;
    -moz-transform-origin: 100% 100%;
}
.cssload-thecube .cssload-c2 {
  transform: scale(1.1) rotateZ(90deg);
    -o-transform: scale(1.1) rotateZ(90deg);
    -ms-transform: scale(1.1) rotateZ(90deg);
    -webkit-transform: scale(1.1) rotateZ(90deg);
    -moz-transform: scale(1.1) rotateZ(90deg);
}
.cssload-thecube .cssload-c3 {
  transform: scale(1.1) rotateZ(180deg);
    -o-transform: scale(1.1) rotateZ(180deg);
    -ms-transform: scale(1.1) rotateZ(180deg);
    -webkit-transform: scale(1.1) rotateZ(180deg);
    -moz-transform: scale(1.1) rotateZ(180deg);
}
.cssload-thecube .cssload-c4 {
  transform: scale(1.1) rotateZ(270deg);
    -o-transform: scale(1.1) rotateZ(270deg);
    -ms-transform: scale(1.1) rotateZ(270deg);
    -webkit-transform: scale(1.1) rotateZ(270deg);
    -moz-transform: scale(1.1) rotateZ(270deg);
}
.cssload-thecube .cssload-c2:before {
  animation-delay: 0.35s;
    -o-animation-delay: 0.35s;
    -ms-animation-delay: 0.35s;
    -webkit-animation-delay: 0.35s;
    -moz-animation-delay: 0.35s;
}
.cssload-thecube .cssload-c3:before {
  animation-delay: 0.69s;
    -o-animation-delay: 0.69s;
    -ms-animation-delay: 0.69s;
    -webkit-animation-delay: 0.69s;
    -moz-animation-delay: 0.69s;
}
.cssload-thecube .cssload-c4:before {
  animation-delay: 1.04s;
    -o-animation-delay: 1.04s;
    -ms-animation-delay: 1.04s;
    -webkit-animation-delay: 1.04s;
    -moz-animation-delay: 1.04s;
}



@keyframes cssload-fold-thecube {
  0%, 10% {
    transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-o-keyframes cssload-fold-thecube {
  0%, 10% {
    -o-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -o-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -o-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-ms-keyframes cssload-fold-thecube {
  0%, 10% {
    -ms-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -ms-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -ms-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-webkit-keyframes cssload-fold-thecube {
  0%, 10% {
    -webkit-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -webkit-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -webkit-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-moz-keyframes cssload-fold-thecube {
  0%, 10% {
    -moz-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -moz-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -moz-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
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
                <div class="row col-md-12 card">
                  <form  enctype="multipart/form-data" id="certificate_form" name="certificate_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                  <div class="card-body row">
                      <div class="form-group col-md-6 col-md-offset-3">
                          <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ceCertPic')." (.jpg)"; ?>:</label>
                          <input type="file" name="cert_image" id="input-file-cert_image" class="dropify" accept="image/jpeg" />
                      </div>
                      <div class="form-group col-md-6 col-md-offset-3">
                          <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ceCertFile'); ?>:</label>
                          <input type="file" name="excel" id="input-file-now-excel" class="dropify" accept=".xlsx,.xls"  required/>
                          <?php echo label('certificate_example').": "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/certificate_excel.xlsx" download>certificate_excel.xlsx</a>
                      </div>
                      <div class="form-group col-md-6" align="center">
                          <br>
                          <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('certificate_create'); ?>" />
                      </div>
                      <div class="form-group col-md-6" align="center">
                          <br>
                          <button type="reset" onclick="location.reload()" class="btn btn-outline-danger btn-block"><?php echo label('m_cancel'); ?></button>
                      </div>
                  </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>


    <div class="modal fade" tabindex="-1" id="modal-process" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="">Processing</h4>
                </div>
                <div class="modal-body">
                  <div class="card-body">

                  <div id="loader">
                    <div class="cssload-thecube">
                      <div class="cssload-cube cssload-c1"></div>
                      <div class="cssload-cube cssload-c2"></div>
                      <div class="cssload-cube cssload-c4"></div>
                      <div class="cssload-cube cssload-c3"></div>
                    </div><br><br>
                    <p align="center"><?php echo label('processing'); ?></p>
                  </div>
                  </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- This is data table -->
    
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
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>


    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/pdfmake.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script type="text/javascript">

        $(document).on('submit', '#certificate_form', function(event){
              event.preventDefault(); 
              $('#modal-process').modal('show');
              $.ajax({
                  url: '<?=base_url()?>index.php/certificate/generate',
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.responseType = 'blob';
                       return xhr;
                  },
                  success: function (data) {
                      $('#modal-process').modal('hide');
                      var a = document.createElement('a');
                      var url = window.URL.createObjectURL(data);
                      a.href = url;
                      a.download = 'certificate.pdf';
                      a.click();
                      window.URL.revokeObjectURL(url);
                      location.reload();
                  },
                                      error: function (jqXHR, exception) {
                                      $("#myModal_process").removeClass("in");
                                      $("#myModal_process").css("display","none");
                                      $('.btn_close').show();
                                          topFunction();
                                          var msg = '';
                                          if (jqXHR.status === 0) {
                                              msg = 'Not connect.\n Verify Network.';
                                          } else if (jqXHR.status == 404) {
                                              msg = 'Requested page not found. [404]';
                                          } else if (jqXHR.status == 500) {
                                              msg = '<?php echo label("ceCerterror"); ?>';
                                          } else if (exception === 'parsererror') {
                                              msg = 'Requested JSON parse failed.';
                                          } else if (exception === 'timeout') {
                                              msg = 'Time out error.';
                                          } else if (exception === 'abort') {
                                              msg = 'Ajax request aborted.';
                                          } else {
                                              msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                          }
                                          swal({
                                              title: msg,
                                              text: "",
                                              type: 'warning',
                                              showCancelButton: false,
                                              confirmButtonClass: 'btn btn-primary',
                                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                                          }).then(function () {
                                                $('#modal-process').modal('hide');
                                          })
                                      },
                });
        });

        $('.dropify').dropify();
        $('select[name="certimg"]').on('change', function(){
          var certimg = $(this).val();
          if(certimg=="upload"){
            document.getElementById('upload').style.display = "";
          }else{
            document.getElementById('upload').style.display = "none";
          }
        });
    </script>
</body>

</html>