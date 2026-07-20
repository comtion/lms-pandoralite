<head>
  <script src="<?php echo REAL_PATH; ?>/assets/js/sco_request.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/cookies.js"></script>
  <script src="<?php echo REAL_PATH; ?>/assets/js/scorm_12.js"></script>
  <?php echo $init; ?>
<script type="text/javascript">
var myApiHandle = null;
var myFindAPITries = 0;

function myGetAPIHandle() {
  myFindAPITries = 0;
  if (myApiHandle == null) {
    myApiHandle = myGetAPI();
  }
  return myApiHandle;
}

function myFindAPI(win) {
  while ((win.API == null) && (win.parent != null) && (win.parent != win)) {
    myFindAPITries++;
    // Note: 7 is an arbitrary number, but should be more than sufficient
    if (myFindAPITries > 7) {
      return null;
    }
  }
  return win.API;
}

// hun for the API - needs to be loaded before we can launch the package
function myGetAPI() {
  var theAPI = myFindAPI(window);
  if ((theAPI == null) && (window.opener != null) && (typeof(window.opener) != "undefined")) {
    theAPI = myFindAPI(window.opener);
  }
  if (theAPI == null) {
    return null;
  }
  return theAPI;
}

function doredirect() {
  if (myGetAPIHandle() != null && document.getElementById('scorm_player').src == '') {
    document.getElementById('scorm_player').src = "<?php echo $scolaunchurl ?>";
  }
}
</script>

</head>
<body style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
  <iframe id='scorm_player' onload="doredirect();" style=" top:0px; left:0px; bottom:0px; right:0px; border:none; margin:0; padding:0; width:100%;height:100%;"></iframe>
</body>
</html>
