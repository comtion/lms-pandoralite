<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Product">
<?php 
  function isMobile() {
      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }
  header ("Last-Modified: " . gmdate ("D, d M Y H:i") . " GMT");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
  <?php if(!isset($title)){if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];}}else{ echo $title; } ?>
</title>

<link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/css/style.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css?modified=20210827" rel="stylesheet">
<!-- You can change the theme colors from here -->
<link href="<?php echo REAL_PATH; ?>/assets/css/colors/default-dark.css" id="theme" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/dashboard1.css" rel="stylesheet">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo REAL_PATH; ?>/assets/images/favicon.png">
<!-- page css -->
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/card-page.css" rel="stylesheet">
<!--alerts CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">


<script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<!-- <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/popper.min.js"></script> -->
<script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo REAL_PATH; ?>/assets/js/perfect-scrollbar.jquery.min.js"></script>
<!--Wave Effects -->
<script src="<?php echo REAL_PATH; ?>/assets/js/waves.js"></script>
<!--stickey kit -->
<script src="<?php echo REAL_PATH; ?>/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="<?php echo REAL_PATH; ?>/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!--Custom JavaScript -->
<script src="<?php echo REAL_PATH; ?>/assets/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/dashboard1.js"></script>
    <!-- Sweet-Alert  -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/promise.min.js"></script>
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
          background-color: #1abc9c;
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
          background-color: #d35400;
        }
    </style>  
    <style type="text/css">
      .slider2 {
          .bs-slider, .carousel-item{
              max-height: 1000px;
          }
          .bs-slider-overlay {
              background:rgba(32, 35, 43, 0.8);
          }
          .slider-control span {
              width:53px;
              height:53px;
              line-height:53px;
              text-align:center;
              border-radius:50%;
              border:1px solid #fff;
          }
          label {
              line-height:27px;
              border-radius:0;
              color:#1a1a1a;
              -webkit-animation-delay: 0.5s;
              animation-delay: 0.5s;
          }
          h2 {
              line-height:42px;
              font-size:36px;

          }
          p {
              line-height:24px;
              font-weight:400;
          }
          i.icon-Play-Music {
              font-size:32px;
          }
      }

      @media (max-width: 1280px) {
          .slider2, .slider3 {
              overflow-y:hidden;

              .slide-image {
                  height:550px;
                  width:auto !important;
              }

              .slider-control span {
                  width:44px;
                  height:44px;
                  line-height:44px;
              }
          }
      }
      @media (max-width: 1100px) {
          .slider2, .slider3 {
              h2 {
                  font-size:34px;
              }
              .slide-image {
                  left:-20%;
                  position:relative;
              }
              .slide-text {
                  width:80% !important;
              }
          }
      }
      @media (max-width:992px) {
          .slider2 {
              video {
                  width:120%;
              }
          }
      }
      @media (max-width:767px) {
          .slider2, .slider3 {
              .slider-control span {
                  width:34px;
                  height:34px;
                  line-height:34px;
              }
              .slide-text {
                  padding:0 !important;
                  width:80% !important;
              }
              h2 {
                  font-size:28px;
                  line-height:36px;
                  margin-bottom:30px;
              }
              .slide-image {
                  left:0;
                  height:300px;
              }
              .btn {
                  padding-left:15px;
                  padding-right:15px;

                  &.btn-md {
                      padding-left:25px;
                      padding-right:25px;
                  }
              }
              .btn-pad {
                  padding-right:0;
              }
          }
      }
      @media (max-width: 428px) {
          .slider2, .slider3 {
              .slider-control span {
                  width:26px;
                  height:26px;
                  line-height:26px;
                  font-size:12px !important;
              }
              h2 {
                  font-size:22px;
                  line-height:30px;
                  margin-bottom:20px;
              }
              i.icon-Play-Music {
                  font-size:26px;
              }
              a.btn-md {
                  text-align:center;
                  margin-bottom:10px;
              }
              .slide-image {
                  left:-75%;
                  height:380px;
              }
              .btn {
                  padding-left:0;
              }
              video {
                  width:160%;
              }
          }
      }
      /*.scrollbar-deep-purple::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-deep-purple::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-deep-purple::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #512da8; }

      .scrollbar-cyan::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-cyan::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-cyan::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #00bcd4; }

      .scrollbar-dusty-grass::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-dusty-grass::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-dusty-grass::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-image: -webkit-linear-gradient(330deg, #d4fc79 0%, #96e6a1 100%);
      background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%); }

      .scrollbar-ripe-malinka::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-color: #F5F5F5;
      border-radius: 10px; }

      .scrollbar-ripe-malinka::-webkit-scrollbar {
      width: 12px;
      background-color: #F5F5F5; }

      .scrollbar-ripe-malinka::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
      background-image: -webkit-linear-gradient(330deg, #f093fb 0%, #f5576c 100%);
      background-image: linear-gradient(120deg, #f093fb 0%, #f5576c 100%); }
*/
      .bordered-deep-purple::-webkit-scrollbar-track {
      -webkit-box-shadow: none;
      border: 1px solid #512da8; }

      .bordered-deep-purple::-webkit-scrollbar-thumb {
      -webkit-box-shadow: none; }

      .bordered-cyan::-webkit-scrollbar-track {
      -webkit-box-shadow: none;
      border: 1px solid #00bcd4; }

      .bordered-cyan::-webkit-scrollbar-thumb {
      -webkit-box-shadow: none; }

      .square::-webkit-scrollbar-track {
      border-radius: 0 !important; }

      .square::-webkit-scrollbar-thumb {
      border-radius: 0 !important; }

      .thin::-webkit-scrollbar {
      width: 6px; }

      .example-1 {
      position: relative;
      overflow-y: scroll;
      height: 200px; }
      
      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        margin-right: 20px;
        position: relative;
        z-index: 2;
      }
    </style>
    <style>
    /* Hide the browser's default checkbox */
    .containerA {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }
    .containerA img {
        vertical-align: middle;
    }
    .containerA .contentA {
        position: absolute;
        top: 0;
        background: rgb(0, 0, 0); /* Fallback color */
        background: rgba(0, 0, 0, 0.5); /* Black background with 0.5 opacity */
        color: #f1f1f1;
        height: 100%;
        width: 50%;
        padding: 20px;}
        .flag-icon-jp{
          border-width: 0.5px;
          border-style: solid;
        }
        .page-titles .breadcrumb {
            padding: 0px;
            margin-bottom: 0px;
            background: transparent;
            font-size: 16px;
        }
    </style>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
