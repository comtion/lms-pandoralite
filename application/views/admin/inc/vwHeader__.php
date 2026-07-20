<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

     <title>Truemoney Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
    <!-- Add custom CSS here -->
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>arkadmin.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>admin.css" rel="stylesheet">

    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>custom.css" rel="stylesheet">
      <!-- JavaScript -->
    <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>jquery-1.10.2.js"></script>
    <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>bootstrap.js"></script>
    <!-- <script src="<?php //echo HTTP_JS_PATH_ADMIN; ?>das.js"></script> -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>html5shiv.js"></script>
      <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>respond.min.js"></script>
    <![endif]-->
    <?php if( $page == "media"){ ?>
      <!-- Generic page styles -->
      <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH_ADMIN; ?>upload/style.css">
      <!-- blueimp Gallery styles -->
      <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
      <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
      <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH_ADMIN; ?>upload/jquery.fileupload.css">
      <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH_ADMIN; ?>upload/jquery.fileupload-ui.css">
      <!-- CSS adjustments for browsers with JavaScript disabled -->
      <noscript><link rel="stylesheet" href="<?php echo HTTP_CSS_PATH_ADMIN; ?>upload/jquery.fileupload-noscript.css"></noscript>
      <noscript><link rel="stylesheet" href="<?php echo HTTP_CSS_PATH_ADMIN; ?>upload/jquery.fileupload-ui-noscript.css"></noscript>
    <?php } ?>

  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>admin">Admin ascend money</a>
        </div>
 <?php
// Define a default Page
  $pg = isset($page) && $page != '' ?  $page :'home'  ;
?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li <?php echo  $pg =='home' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-wpforms"></i> Home page</a></li>
            <li class="dropdown <?php if( isset( $menu_open )) { echo $menu_open == "product" ? "open" : ""; } ?>"  >
  	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wpforms"></i> Product <b class="caret"></b></a>
  	          <ul class="dropdown-menu">
  	            <li>
                  <a <?php echo  $pg =='product' ? 'class="active"' : '' ?> href="<?php echo base_url(); ?>admin/product/"><i class="fa fa-wpforms"></i> Product Banner</a>
                  <a <?php echo  $pg =='ewallet' ? 'class="active"' : '' ?> href="<?php echo base_url(); ?>admin/product/edit/ewallet"><i class="fa fa-wpforms"></i> e-wallet</a>
                  <a <?php echo  $pg =='billpayment' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/billpayment"><i class="fa fa-wpforms"></i> Bill payment</a>
                  <a <?php echo  $pg =='mobiletopup' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/mobiletopup"><i class="fa fa-wpforms"></i> Mobiel Topup</a>
                  <a <?php echo  $pg =='gametopup' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/gametopup"><i class="fa fa-wpforms"></i> Game Topup</a>
                  <a <?php echo  $pg =='moneytransfer' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/moneytransfer"><i class="fa fa-wpforms"></i> Money Transfer</a>
                  <a <?php echo  $pg =='prepaiddebit' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/prepaiddebit"><i class="fa fa-wpforms"></i> Prepaid Debit Card</a>
                  <a <?php echo  $pg =='payroll' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/payroll"><i class="fa fa-wpforms"></i> Pay roll</a>
                  <a <?php echo  $pg =='gateway' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/gateway"><i class="fa fa-wpforms"></i> Payment Gateway</a>
                  <a <?php echo  $pg =='channels' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/product/edit/channels"><i class="fa fa-wpforms"></i> Paypoint Channels</a>
  	            </li>
  	          </ul>
  	        </li>
            <li <?php echo  $pg =='factoring' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/factoring"><i class="fa fa-wpforms"></i> Factoring </a></li>
            <li <?php echo  $pg =='about' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/about"><i class="fa fa-wpforms"></i> About us</a></li>
            <li <?php echo  $pg =='culture' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/culture"><i class="fa fa-wpforms"></i> Culture</a></li>
            <li <?php echo  $pg =='news' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/news"><i class="fa fa-wpforms"></i> News</a></li>
            <li class="dropdown <?php if( isset( $menu_open )) { echo $menu_open == "contact" ? "open" : ""; } ?>">
  	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wpforms"></i> Contact <b class="caret"></b></a>
  	          <ul class="dropdown-menu">
  	            <li>
                  <a <?php echo  $pg =='contact' ? 'class="active"' : '' ?> href="<?php echo base_url(); ?>admin/contact/"><i class="fa fa-wpforms"></i> Contact</a>
                  <a <?php echo  $pg =='contactlist' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/contactlist/"><i class="fa fa-wpforms"></i> Contact List</a>
                </li>
  	          </ul>
  	        </li>
            <li class="dropdown <?php if( isset( $menu_open )) { echo $menu_open == "careers" ? "open" : ""; } ?>">
  	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wpforms"></i> Careers <b class="caret"></b></a>
  	          <ul class="dropdown-menu">
  	            <li>
                  <a <?php echo  $pg =='careers' ? 'class="active"' : '' ?> href="<?php echo base_url(); ?>admin/careers/banner"><i class="fa fa-wpforms"></i> Careers Banner</a>
                  <a <?php echo  $pg =='careerslist' ? 'class="active"' : '' ?> href="<?php echo base_url(); ?>admin/careers/"><i class="fa fa-wpforms"></i> Careers List</a>
                  <a <?php echo  $pg =='location' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/location/"><i class="fa fa-wpforms"></i> Location</a>
                  <a <?php echo  $pg =='catagory' ? 'class="active"' : '' ?>href="<?php echo base_url(); ?>admin/catagory/"><i class="fa fa-wpforms"></i> Catagory</a>
                </li>
  	          </ul>
  	        </li>
            <li <?php echo  $pg =='media' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/media"><i class="fa fa-wpforms"></i> Media</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">

            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->session->userdata('username') ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>admin/home/logout"><i class="fa fa-power-off"></i> Log Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
