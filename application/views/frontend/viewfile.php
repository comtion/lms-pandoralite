<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <style type="text/css">
      div[aria-label="Pop-out"] {
      display: none;
       }
      div[aria-label="toolbar"] {
      width: 52px;
      }    
    </style>
    <style type="text/css">
      #one {
       /* margin: 50px auto;*/
        /*width: 100%;*/
        height: auto;
      }
    </style>
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
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
                <?php 
                  if($data_query['qr_status']=="1"){
                    if($data_query['qr_type']=="1"){ ?>
                      <img src="<?php echo REAL_PATH.'/uploads/file_forqrcode/'.$data_query['qr_path']; ?>" style="width: 100%;height: auto;pointer-events:none;">
              <?php }else if($data_query['qr_type']=="2"){ ?>
                        <video style="width: 100%;height: 97vh;" controls controlsList="nodownload" id="video_file_view">
                          <source src="<?php echo REAL_PATH.'/uploads/file_forqrcode/'.$data_query['qr_path']; ?>" type="video/mp4">
                        </video>

              <?php }else{
                      $file = base_url().'uploads/file_forqrcode/'.$data_query['qr_path'];

                      $array_pathext = explode('.', $data_query['qr_path']);
                      $extension = end($array_pathext);
              ?>
                      <iframe id="one" src="<?php echo base_url().'/viewdoc/PDF/'.$data_query['qr_id'].'/qrcode'; ?>" style="display: none; width:100%; height: 97vh;" onload="resizeIframe(this)" frameborder="0"></iframe>
                          <div class="loading_div" align="center" style="float: center;">
                            <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 40%">
                          </div>
                      <iframe src="https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true" onload="setFrameLoaded()" id="iframe_document" style="width: 100%;height: 97vh;" frameborder="0">
                      </iframe>
                        
                        <!-- <div id="one" class="pdf-pro-plugin" data-mode="normal" style="display: none;" data-pdf-url="<?php echo $file; ?>"></div> -->
                      <!-- <iframe style="width: 100%;height: 97vh;"  src="<?php echo $file; ?>"></iframe> --><!-- #toolbar=0&navpanes=0&scrollbar=0-->

              
              <?php }
                  }else{
                    echo '<h3 align="center">ไม่พบไฟล์ข้อมูล กรุณาติดต่อผู้ดูแลระบบ<br>The data not found, please contact your administrator.</h3>';
                  }
                ?>
    <!--stickey kit -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery/jquery.min.js"></script>
    <link href="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdf-viewer.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdfjs/pdf.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/pdfviewer/pdf-viewer.min.js"></script>
    <script type="text/javascript">
              <?php if($data_query['qr_type']=="2"){ ?>
                  var token ;
                  var video = '<?php echo $data_query['qr_path']; ?>';
                  $.post( "<?php echo REAL_PATH ?>/setsession.php?filename="+video+"&type=qrcode", function( data ) {
                      token = data
                      $("#video_file_view").html('<source src="<?php echo REAL_PATH; ?>/video.php'+token+'" type="video/mp4"></source>' );
                  });
                <?php } ?>
      document.addEventListener('contextmenu', event => event.preventDefault());
      $(document).keydown(function(event){
          if(event.keyCode==123){
              return false;
          }
          else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
                   return false;
          }
      });
        function resizeIframe(obj) {
          obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
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
      document.addEventListener('contextmenu', event => event.preventDefault()); 
        function setFrameLoaded()
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
              <?php if (isset($file)) { ?>
                  $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo $file; ?>&a=bi&embedded=true');   
              <?php } ?>
            }else{
                  frame_loaded = 1;
                  $('.loading_div').hide();
                  var iframe_document_length = $("#iframe_document").contents().find("span").length;
                  if(iframe_document_length==1){
                  <?php if (isset($file)) { ?>
                    $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo $file; ?>&a=bi&embedded=true');   
                  <?php } ?>
                    frame_loaded = 0;
                    $('.loading_div').show();
                  }
            }
          <?php
        }
        ?>
        }

      <?php if(isset($extension)&&in_array($extension, array('pdf'))){ ?>
            $('#one').show();
            $('.pdf-pro-download').hide();
            $('#iframe_document').hide();
            $('.loading_div').hide();
       <?php 
            }else{ 
                if(isset($extension)&&!in_array($extension, array('jpg','jpeg','png','gif','mp4','wmv'))){
              ?>
                $('#one').hide();
                $('#iframe_document').show();

              <?php
              if(user_agent()!="ios"){
                ?>
      <?php  if($data_query['qr_type']!="1"&&$data_query['qr_type']!="2"){ 
                $file = base_url().'uploads/file_forqrcode/'.$data_query['qr_path'];
      ?>
        var frame_loaded = 0;
          $('.loading_div').show();
        setInterval(function(){ 
         if(frame_loaded != 1){
          $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true');   
         }else{
          $('.loading_div').hide();
         // $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true');
         }
        }, 3000);
      <?php } ?>
        $(function() {
            $.ajax({
                url: "https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true",
                dataType: "jsonp",
                timeout: 5000,

                success: function () {
                    $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true");
                },
                error: function (parsedjson) {
                  //alert(parsedjson.status);
                    $("#iframe_document").attr("src", "https://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true");
                }
            });
        });
              $(function() {
                function manipIframe() {
                  el = $('body', $('#iframe_document').contents());
                  if (el.length != 1) {
                    setTimeout(manipIframe, 100);
                    return;
                  }
                }
                manipIframe();
              });
              $('#iframe_document').attr('src', 'https://docs.google.com/gview?url=<?php echo $file; ?>&a=bi&embedded=true');   
            <?php } else{ ?>/*
              <?php if(in_array($extension, array('pdf'))){ ?>
                        $('#iframe_document').attr('src', 'https://docs.google.com/viewerng/viewer?url=<?php echo $file; ?>&embedded=true');  
                      <?php }else{ ?>*/
                        $('#iframe_document').attr('src', 'https://view.officeapps.live.com/op/embed.aspx?src=<?php echo $file; ?>');  /*
                      <?php } ?>*/
            <?php } ?>
          <?php }
            } ?>
    </script>
</body>

</html>