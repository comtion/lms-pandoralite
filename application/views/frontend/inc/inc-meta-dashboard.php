<?php 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<?php
  $dropifyLanguage = isset($lang) ? strtolower((string) $lang) : strtolower((string) $this->session->userdata('lang'));
  if (in_array($dropifyLanguage, array('thai', 'th', 'thailand'), true)) {
      $dropifyLocale = 'th';
  } elseif (in_array($dropifyLanguage, array('japanese', 'japan', 'jp', 'ja'), true)) {
      $dropifyLocale = 'ja';
  } else {
      $dropifyLocale = 'en';
  }
?>
<html lang="<?php echo $dropifyLocale; ?>">

<?php 
  function isMobile() {
      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }
  header ("Last-Modified: " . gmdate ("D, d M Y H:i") . " GMT");
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-Frame-Options" content="deny">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo REAL_PATH; ?>/assets/images/favicon.png">
    <title><?php if(!isset($title)){if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];}}else{ echo $title; } ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/icheck/skins/all.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/style.css?modified=<?php echo date("YmdHis"); ?>" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css?modified=20210827" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/dashboard1.css" rel="stylesheet">
    <!-- page css -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/form-icheck.css" rel="stylesheet">
    <!--alerts CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- You can change the theme colors from here -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/colors/default-dark.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/css/verztec-enterprise.css?modified=<?php echo date("YmdHis"); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/plugins/dropify/dist/css/dropify.min.css">
    <link href="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style type="text/css">
        body{
            font-family: Roboto, Arial, "trebuchet MS", Helvetica, sans-serif;
        }
    </style>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/waves.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/dashboard1.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script>
    /* Apply the active system language to every Dropify instance, including
       instances created later inside AJAX content and modals. */
    (function ($) {
      if (!$ || !$.fn || !$.fn.dropify) return;

      var locale = <?php echo json_encode($dropifyLocale); ?>;
      var translations = {
        th: {
          messages: {
            'default': 'ลากไฟล์มาวางที่นี่ หรือคลิกเพื่อเลือกไฟล์',
            'replace': 'ลากไฟล์มาวาง หรือคลิกเพื่อเปลี่ยนไฟล์',
            'remove': 'ลบไฟล์',
            'error': 'ไม่สามารถใช้ไฟล์นี้ได้ กรุณาตรวจสอบอีกครั้ง'
          },
          error: {
            'fileSize': 'ไฟล์มีขนาดใหญ่เกินกำหนด (สูงสุด {{ value }})',
            'minWidth': 'ความกว้างของรูปภาพต้องไม่น้อยกว่า {{ value }}px',
            'maxWidth': 'ความกว้างของรูปภาพต้องไม่เกิน {{ value }}px',
            'minHeight': 'ความสูงของรูปภาพต้องไม่น้อยกว่า {{ value }}px',
            'maxHeight': 'ความสูงของรูปภาพต้องไม่เกิน {{ value }}px',
            'imageFormat': 'รองรับเฉพาะไฟล์รูปแบบ {{ value }} เท่านั้น'
          }
        },
        ja: {
          messages: {
            'default': 'ファイルをここにドロップするか、クリックして選択',
            'replace': 'ファイルをドロップするか、クリックして変更',
            'remove': 'ファイルを削除',
            'error': 'このファイルは使用できません。もう一度確認してください'
          },
          error: {
            'fileSize': 'ファイルサイズが上限を超えています（最大 {{ value }}）',
            'minWidth': '画像の幅は {{ value }}px 以上にしてください',
            'maxWidth': '画像の幅は {{ value }}px 以下にしてください',
            'minHeight': '画像の高さは {{ value }}px 以上にしてください',
            'maxHeight': '画像の高さは {{ value }}px 以下にしてください',
            'imageFormat': '使用できる画像形式は {{ value }} のみです'
          }
        },
        en: {
          messages: {
            'default': 'Drag and drop a file here, or click to browse',
            'replace': 'Drag and drop or click to replace the file',
            'remove': 'Remove file',
            'error': 'This file cannot be used. Please check it and try again.'
          },
          error: {
            'fileSize': 'The file is too large ({{ value }} maximum).',
            'minWidth': 'The image must be at least {{ value }}px wide.',
            'maxWidth': 'The image must not exceed {{ value }}px in width.',
            'minHeight': 'The image must be at least {{ value }}px high.',
            'maxHeight': 'The image must not exceed {{ value }}px in height.',
            'imageFormat': 'Only {{ value }} image files are allowed.'
          }
        }
      };

      var originalDropify = $.fn.dropify;
      $.fn.dropify = function (options) {
        var localizedOptions = $.extend(true, {}, translations[locale] || translations.en, options || {});
        return originalDropify.call(this, localizedOptions);
      };
      $.fn.dropify.Constructor = originalDropify.Constructor;
      window.PRECISION_DROPIFY_LOCALE = locale;
    })(window.jQuery);
    </script>
    <!-- Sweet-Alert  -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.min.js"></script>
    <script>
      window.REAL_PATH = "<?php echo REAL_PATH; ?>";
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/verztec-enterprise.js?modified=<?php echo date("YmdHis"); ?>"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style type="text/css">
        body{
            font-family: Roboto, Arial, "trebuchet MS", Helvetica, sans-serif;
        }
        i{
          font-size: 14px;
          font-weight: bold;
        }
        .flag-icon-jp{
          border-width: 0.5px;
          border-style: solid;
        }
        strong { font-weight: bold; }
    </style>
    <script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
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
        #myBtn {
          background-color: rgb(156,159,164);
          width: 40px;
          height: 40px;
          display: none;
          position: fixed;
          bottom: 20px;
          right: 30px;
          z-index: 99;
          border: none;
          outline: none;
          color: white;
          cursor: pointer;
          border-radius: 4px;
        }

        #myBtn:hover {
          background-color: rgb(236,32,41);
        }
        .page-titles .breadcrumb {
            padding: 0px;
            margin-bottom: 0px;
            background: transparent;
            font-size: 16px;
        }
        .dropify-wrapper.touch-fallback {
            max-height: 200px!important;
        }
        .select2-results__group {
            color: inherit;
            font-size: inherit;
            font-weight:bold;
            padding: 6px 4px;
        }
        strong{
          font-weight: bold !important;
        }
    </style>
    <link href="<?php echo REAL_PATH; ?>/css/verztec-enterprise.css?modified=<?php echo date("YmdHis"); ?>" rel="stylesheet">
