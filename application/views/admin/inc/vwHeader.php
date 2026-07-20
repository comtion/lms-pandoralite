<?php
  $query = "select * from tbl_cat";
  $result = $this->db->query( $query );
  $menus = $result->result_array();
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

     <title> มูลนิธิหัวใจแห่งประเทศไทยในพระบรมราชูปถัมภ์ </title>
     <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/skin/favicon.png" type="image/x-icon"/>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>font-awesome.min.css" rel="stylesheet">
    <!-- Add custom CSS here -->
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>cp_fonts.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>arkadmin.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>admin.css" rel="stylesheet">

    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>custom.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH_ADMIN; ?>jquery.tagsinput.css" rel="stylesheet">
      <!-- JavaScript -->
    <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>jquery-1.10.2.js"></script>
    <script src="<?php echo HTTP_JS_PATH_ADMIN; ?>bootstrap.js"></script>
    <script src="//cdn.ckeditor.com/4.6.1/full/ckeditor.js"></script>
    <script src="<?php //echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>

    <link href="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/extension/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
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
<script>
  var base_url = "<?php echo base_url(); ?>";
</script>
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
            <a class="navbar-brand" href="<?php echo base_url(); ?>admin"> <img src="<?php echo base_url(); ?>assets/img/skin/logo.png" width="33" height="42"> มูลนิธิหัวใจแห่งประเทศไทยในพระบรมราชูปถัมภ์   </a>
        </div>
 <?php
// Define a default Page
  //$pg = isset($page) && $page != '' ?  $page :'home'  ;
  $pg =  $this->session->userdata('page');
?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <div class="head-center">
            <h3>
              <?php foreach( $menus as $menu ){
                if( $menu['title'] == $this->session->userdata('page') ){
                  echo $menu['name_th'];
                }
              } ?>

            </h3>
          </div>

          <ul class="nav navbar-nav navbar-right navbar-user">

            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->session->userdata('username') ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>admin/home/logout"><i class="fa fa-power-off"></i> Log Out</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav side-nav">
            <div class="head-admin">
              <p>คุณผู้ดูแลระบบ <span style="color:red;"> ( ไอพีของคุณ : <?php echo $_SERVER['REMOTE_ADDR']; ?> )</span></p>
            </div>
            <h3 class="menu-head">เมนูจัดการ</h3>
            <?php foreach( $menus as $menu ){ ?>
              <li <?php echo  $pg == $menu['title'] ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/news/lists/<?php echo $menu['id'].'/'.$menu['title']; ?>"><i class="fa fa-wpforms"></i> <?php echo $menu['name_th']; ?> </a></li>
            <?php } ?>
            <li <?php echo  $pg == 'link' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/links/lists"><i class="fa fa-wpforms"></i> จัดการ Link </a></li>
            <li <?php echo  $pg == 'banner' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>admin/banner/lists"><i class="fa fa-wpforms"></i> จัดการ Banner </a></li>
            <hr class="menuline" />
            <div class="group-add-button">
              <a href="<?php echo base_url().'admin/news/add'; ?>">เพิ่มบทความ</a>
              <a href="<?php echo base_url().'admin/news/add/video'; ?>">เพิ่มวิดีโอ</a>
              <a href="<?php echo base_url().'admin/links/add'; ?>">เพิ่ม Link</a>
              <a href="<?php echo base_url().'admin/banner/add'; ?>">เพิ่ม Banner</a>
            </div>
          </ul>

        </div><!-- /.navbar-collapse -->
      </nav>
