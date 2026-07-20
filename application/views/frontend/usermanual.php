<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); 
  
                      $array_pathext = explode('.', $path);
                      $extension = end($array_pathext);
?>
    <style type="text/css">
      #one {
        /*margin: 50px auto;*/
        /*width: 100%;*/
        height: auto;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
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
                  <div class="col-md-12 card">
                    <div class="card-body text-right">
                      <?php 

                      if(in_array($extension, array('jpg','jpeg','png','gif'))){ ?>
                        <img src="<?php echo base_url().'/uploads/user_manual/'.$path; ?>" style="width: 100%">
                      <?php }else if(in_array($extension, array('mp4','wmv'))){ ?>
                        <video id="video_upload" controls="controls" style="width: 100%" src="<?php echo base_url().'/uploads/user_manual/'.$path; ?>"></video>
                      <?php }else{ ?>
                        <a href="<?php echo $path; ?>" class="btn waves-effect waves-light btn-warning" download><i class="mdi mdi-download"></i> <?php echo label('download_file'); ?></a>
                        <hr/>
                          <div class="loading_div" align="center" style="float: center;">
                            <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 40%">
                          </div>
                        <iframe id="iframe_document" onload="setFrameLoaded(this)" onerror="runloop();"  style="width:100%; height: 97vh;" frameborder="0">
                        </iframe>

                        <iframe id="one" src="<?php echo base_url().'/viewdoc/PDF/00/usermanual'; ?>" style="display: none; width:100%; height: 97vh;" onload="resizeIframe(this)" frameborder="0"></iframe>
                        <!-- <div id="one" class="pdf-pro-plugin" data-mode="normal" style="display: none;" data-pdf-url="<?php echo $path; ?>"></div> -->
                        <!--  src="https://docs.google.com/gview?url=<?php echo base_url().'uploads/usermanual/'.$path; ?>&a=bi&embedded=true"-->
                      <?php }  ?>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <link href="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdf-viewer.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdfjs/pdf.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdf-viewer.min.js"></script>
    <script type="text/javascript">

        function resizeIframe(obj) {
          obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
        topFunction();
        <?php 
        function user_agent(){
            $iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
            $mac = strpos($_SERVER['HTTP_USER_AGENT'],"Macintosh");
            $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
            if($iPad||$iPhone||$iPod||$mac){
                return 'ios';
            }else if($android){
                return 'android';
            }else{
                return 'pc';
            }
        }
        ?>
        var frame_loaded = 0;
        $('.loading_div').show();
        function setFrameLoaded(val)
        {
        <?php
        if(user_agent()!="ios"){
          ?>
           frame_loaded = 1;
            $('.loading_div').hide();

          <?php }else{ ?>
            var iframe_document_length = $("#iframe_document").contents().find("body").length;
            /*var iframe = document.getElementById('iframe_document');
            var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
            var test = innerDoc.getElementsByTagName('span');

            if(test != undefined) {

                alert('Exists');

            }else{

                alert('Do no Exists');
            }*/
            if(iframe_document_length==1){
              <?php if(in_array($extension, array('pdf'))){ ?>
                  $('#iframe_document').attr('src', 'https://docs.google.com/viewerng/viewer?url=<?php echo base_url().'uploads/document/'.$path; ?>&embedded=true');  
                <?php }else{ ?>
                  $('#iframe_document').attr('src', 'https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url().'uploads/document/'.$path; ?>');  
                <?php } ?>
                  /*var iframe_document_length = $("#iframe_document").contents().find("body").length;
                  if(iframe_document_length==0){
                        $('#iframe_document').attr('src', 'https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url().'uploads/document/'.$path; ?>'); 
                  }else{
                  frame_loaded = 1;
                  $('.loading_div').hide();
                  }*/
            }else{
                  frame_loaded = 1;
                  $('.loading_div').hide();
                  var iframe_document_length = $("#iframe_document").contents().find("span").length;
                  if(iframe_document_length==1){
              /*<?php if(in_array($extension, array('pdf'))){ ?>
                  $('#iframe_document').attr('src', 'https://docs.google.com/viewerng/viewer?url=<?php echo base_url().'uploads/document/'.$path; ?>&embedded=true');  
                <?php }else{ ?>*/
                  $('#iframe_document').attr('src', 'https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url().'uploads/document/'.$path; ?>');  
                /*<?php } ?>  */
                    frame_loaded = 0;
                    $('.loading_div').show();
                  }
            }
               /* if(!$("#iframe_document").contents().find("body").length) {
        $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true');   
                }else{ */
               // }
          <?php
        }
        ?>
           // $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true');   
        }
        <?php if(user_agent()=="ios"){ ?>
        (function(){
          // Only apply settimeout workaround for iOS 6 - for all others, we map to native Timers
          //if (!navigator.userAgent.match(/OS 6(_\d)+/i)) return;

          // Prevent multiple applications
          if(window.getTimeouts !== undefined) return;

          var TIMERID = 'rafTimer',
          
              touchTimeouts   = {},
              touchIntervals  = {},
              
              /* Reference to original timers */
              _st = window.setTimeout, 
              _si = window.setInterval, 
              _ct = window.clearTimeout, 
              _ci = window.clearInterval,
              
              /* Request animation timers */
              _clearTouchTimer = function(uid, isInterval){
                var interval = isInterval || false,
                    timer = interval ? touchIntervals :  touchTimeouts;
                if(timer[uid]) {
                  timer[uid].callback = undefined;
                  timer[uid].loop = false;
                  return true;
                } else {
                  return false;
                }
              },
              _touchTimer = function(callback, wait, isInterval){
                var uid,
                    name = callback.name || TIMERID + Math.floor(Math.random() * 1000),
                    delta = new Date().getTime()+ wait,
                    interval = isInterval || false,
                    timer = interval ? touchIntervals :  touchTimeouts;
            
                uid = name + "" + delta;
            
                timer[uid] = {};
                timer[uid].loop = true;
                timer[uid].callback = callback;
            
                function _loop() {
                  var now = new Date().getTime();
                  if (timer[uid].loop !== false) {
                      timer[uid].requestededFrame = webkitRequestAnimationFrame(_loop);
                      timer[uid].loop = now <= delta;
                  } else {
                    if(timer[uid].callback) timer[uid].callback();
                    if(interval){
                      delta = new Date().getTime() + wait;
                      timer[uid].loop = now <= delta;
                      timer[uid].requestedFrame = webkitRequestAnimationFrame(_loop);
                    } else {
                      delete timer[uid];
                    }
                  }
                };
                
                _loop();
                return uid;
              },
              _timer = function(callback, wait, touch, isInterval){
                if(touch){
                  return _touchTimer(callback, wait, isInterval);
                } else {
                  return isInterval ? _si(callback, wait) : _st(callback, wait);
                }
              },
              _clear = function(uid, isInterval){
                if(uid.indexOf && uid.indexOf(TIMERID) > -1){
                  return _clearTouchTimer(uid, isInterval);
                } else {
                  return isInterval ? _ci(uid) : _ct(uid);
                }
              };
          
          /* Returns raf-based timers; For debugging purposes */
          window.getTimeouts = function(){
            return { timeouts: touchTimeouts , intervals : touchIntervals }
          };

          /* Exposed globally */
          window.setTimeout = function(callback, wait, touch){
            return _timer(callback, wait, touch);
          };
          window.setInterval = function(callback, wait, touch){
            return _timer(callback, wait, touch, true);
          };
          window.clearTimeout = function(uid){
            return _clear(uid);
          };
          window.clearInterval = function(uid){
            return _clear(uid, true);
          };
        })();
      <?php } ?>
      <?php if(in_array($extension, array('pdf'))){ ?>
            $('#one').show();
            $('.pdf-pro-download').hide();
            $('#iframe_document').hide();
            $('.loading_div').hide();
       <?php 
            }
         ?>
          /*for (;;) {
            $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true');
            if(frame_loaded == 1){
              break;
            }
          }  */
        /*setInterval(ajaxCall, 3000);

        function ajaxCall() {
          alert('84');
           if(frame_loaded != 1){
            //console.log(frame_loaded);
              $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true');   
           }else{
              $('.loading_div').hide();
           }
        }*/
        /*$(function() {
            $.ajax({
                url: "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true",
                dataType: "jsonp",
                timeout: 3000,

                success: function () {
                    $('.loading_div').hide();
                    break;
                },
                error: function (parsedjson) {
                  //alert(parsedjson.status);
                    $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
                }
            });
        });
        setTimeout("alert('Hello')", 1000);*/
        /*let script = document.createElement('iframe');
          script.src = "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true"; // no such script
          document.head.append(script);

          script.onerror = function() {
            $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
          };*/
      /*$(function() {
        function manipIframe() {
            $.ajax({
                url: "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true",
                dataType: "jsonp",
                //timeout: 5000,

                success: function () {
                    $('.loading_div').hide();
                    $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
                    break;
                },
                error: function (parsedjson) {
                   //manipIframe();
                  //alert(parsedjson.status);
                   // $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
                }
            });
        }
        manipIframe();
      });*/
      //document.getElementById("iframe_document").src = "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true";
        /*function manipIframe() {
            $.ajax({
                url: "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true",
                dataType: "jsonp",
                //timeout: 5000,

                success: function () {
                    $('.loading_div').hide();
                    $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
                    break;
                },
                error: function (parsedjson) {
                    $('.loading_div').hide();
                   //manipIframe();
                  alert(parsedjson.status);
                   // $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo base_url().'uploads/document/'.$path; ?>&a=bi&embedded=true");
                }
            });
        }*/
    </script>
</body>

</html>