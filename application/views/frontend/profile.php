<?php defined('BASEPATH') OR exit('No direct script access allowed');
$title = (isset($lang) && $lang === 'thai')
    ? 'โปรไฟล์ของฉัน | ISUZU E-Learning'
    : 'My Profile | ISUZU E-Learning';
?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<!-- Page CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/contact-app-page.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/profile-luxury.css?v=20260806-5" rel="stylesheet">
    <style type="text/css">
        .transparent {
          color: rgb(0, 0, 0);
    background-color: rgba(255, 255, 255, 0.2);
        }
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
      
    </style> 
    <style type="text/css">
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
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border profile-luxury-page">
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
            <div class="container-fluid profile-luxury-shell">

                <div class="row profile-luxury-layout">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-4 col-md-4 profile-identity-column">
                        <div class="card profile-identity-card">
                        <!-- <?php if($profile['bgpic_user']!=""){ ?>
                            <div class="card-inverse" <?php if($profile['bgpic_user']!=""){ ?>style="
                              width: auto;
                              height: 100% !important;
                              overflow: hidden;
                              background-image: url(<?php echo base_url()."uploads/bg_user/".$profile['bgpic_user']; ?>);
                              background-size: cover;
                              background-position: center;"<?php } ?>>

                              <?php if($profile['bgpic_user']!=""){ ?>
                                <div style="padding:40px; background: rgba(0,0,0,0.5);">
                              <?php } ?>
                        <?php }else{ ?> -->
                        <!-- <?php } ?> -->
                            <div class="card-body">
                                <center>
                                  <div class="profile-portrait-wrap">
                                    <?php if(isset($profile['img_profile'])&&$profile['img_profile']!=""){ ?>
                                      <img id="profilePortraitPreview" src="<?php echo media_url('uploads/profile/'.$profile['img_profile']); ?>" onerror="this.onerror=null;this.src='<?php echo REAL_PATH; ?>/assets/images/profile-fallback-premium.webp'" class="profile-portrait" alt="Profile"/>
                                    <?php }else{ ?>
                                      <img id="profilePortraitPreview" src="<?php echo REAL_PATH; ?>/assets/images/profile-fallback-premium.webp" class="profile-portrait" alt="Profile"/>
                                    <?php } ?>
                                    <label class="profile-camera-button" for="img_profile" title="<?php echo label('m_profile'); ?>">
                                      <i class="mdi mdi-camera"></i><span class="sr-only"><?php echo label('m_profile'); ?></span>
                                    </label>
                                  </div>
                                    <h4 class="card-title m-t-10">
                                      <?php 
                                        if($lang=="thai"){
                                          echo $profile['fullname_th'];
                                        }else{
                                          echo $profile['fullname_en'];
                                        }
                                      ?>
                                    </h4>
                                    <h6 class="card-title profile-role"><?php
                                                  if($lang=="thai"){
                                                    $ugname = $profile['ug_name_th'];
                                                  }else{
                                                    $ugname = $profile['ug_name_en'];
                                                  } 
                                                  echo $ugname; ?>
                                    </h6>
                                </center>
                                <div class="profile-signature"></div>
                                <?php if($profile['email']!=""){ ?>
                                    <h6 class="card-title profile-contact"><i class="mdi mdi-email-outline"></i><span><?php echo $profile['email']; ?></span></h6>
                                <?php } ?>
                                <?php if($profile['work_phone']!=""){ ?>
                                    <h6 class="card-title profile-contact"><i class="mdi mdi-phone"></i><span><?php echo $profile['work_phone']; ?></span></h6>
                                <?php } ?>
                                <div class="profile-account-meta">
                                  <h5><?php echo $lang == 'thai' ? 'เกี่ยวกับบัญชี' : 'Account details'; ?></h5>
                                  <p><i class="mdi mdi-account-outline"></i><span><?php echo $lang == 'thai' ? 'บทบาท' : 'Role'; ?></span><strong><?php echo $ugname; ?></strong></p>
                                  <p><i class="mdi mdi-shield"></i><span><?php echo $lang == 'thai' ? 'สถานะ' : 'Status'; ?></span><strong><?php echo $lang == 'thai' ? 'เปิดใช้งาน' : 'Active'; ?></strong></p>
                                </div>
                                <!-- <?php if($profile['bgpic_user']!=""){ ?>
                                  </div>
                                <?php } ?> -->
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-8 col-md-8 profile-form-column">
                        <div class="card profile-workspace-card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist"><!-- 
                              <?php if($user['Is_admin']=="0"){ ?>
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li>
                              <?php } ?> -->
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#setting" role="tab"><?php echo label('ManageSetting'); ?></a> </li>
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#certificate" role="tab"><?php echo label('certificate'); ?></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <div class="tab-pane active" id="certificate" role="tabpanel">
                                    <div class="card-body certificate-gallery-panel">
                                        <?php if($user['Is_admin']!="0"&&$user['ug_for']=="OWNER"){ ?>
                                        <form  enctype="multipart/form-data" id="certificate_form" name="certificate_form" autocomplete="off" method="POST" accept-charset="utf-8"  class="form-horizontal p-t-20">
                                              <div class="card-body row">
                                                  <div class="form-group col-md-6 col-md-offset-3">
                                                      <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ceCertPic'); ?>:</label>
                                                      <input type="file" name="cert_image" id="input-file-cert_image" class="dropify_cert" accept="image/x-png,image/jpeg" />
                                                  </div>
                                                  <div class="form-group col-md-6 col-md-offset-3">
                                                      <label for="status_cr"><b style="color: #FF2D00">*</b><?php echo label('ceCertFile'); ?>:</label>
                                                      <input type="file" name="excel" id="input-file-now-excel" class="dropify_cert" accept=".xlsx,.xls" />
                                                      <?php echo label('certificate_example').": "; ?><a href="<?php echo REAL_PATH;?>/uploads/format/certificate_excel.xlsx" download>certificate_excel.xlsx</a>
                                                  </div>
                                                  <div class="form-group col-md-6" align="center">
                                                      <br>
                                                      <input type="submit" name="action" id="action" class="btn btn-outline-success btn-block pull-left" value="<?php echo label('create').label('certificate'); ?>" />
                                                  </div>
                                                  <div class="form-group col-md-6" align="center">
                                                      <br>
                                                      <button type="reset" onclick="location.reload()" class="btn btn-outline-danger btn-block"><?php echo label('m_cancel'); ?></button>
                                                  </div>
                                              </div>
                                        </form>
                                        <?php }else{ ?>
                                            <div class="certificate-gallery-head">
                                              <div>
                                                <span class="certificate-gallery-kicker"><?php echo $lang == 'thai' ? 'ความสำเร็จของคุณ' : 'Your achievements'; ?></span>
                                                <h3><?php echo $lang == 'thai' ? 'ใบประกาศนียบัตร' : 'Certificates'; ?></h3>
                                                <p><?php echo $lang == 'thai' ? 'รวบรวมหลักสูตรที่คุณผ่านและใบประกาศที่ได้รับทั้งหมด' : 'A collection of your completed courses and earned certificates.'; ?></p>
                                              </div>
                                              <div class="certificate-count" aria-label="<?php echo countArray($certshow); ?> certificates">
                                                <strong><?php echo countArray($certshow); ?></strong>
                                                <span><?php echo $lang == 'thai' ? 'ใบประกาศ' : 'earned'; ?></span>
                                              </div>
                                            </div>
                                            <?php if(countArray($certshow)>0){ ?>
                                            <div class="certificate-gallery-grid">
                                                <?php foreach ($certshow as $key_cert => $value_cert) { 

                                                        if($lang=="thai"){ 
                                                          $cname = $value_cert['cname_th']!=""?$value_cert['cname_th']:$value_cert['cname_eng'];
                                                          $cname = $cname!=""?$cname:$value_cert['cname_jp'];
                                                        }else if($lang=="english"){ 
                                                          $cname = $value_cert['cname_eng']!=""?$value_cert['cname_eng']:$value_cert['cname_th'];
                                                          $cname = $cname!=""?$cname:$value_cert['cname_jp'];
                                                        }else{
                                                          $cname = $value_cert['cname_jp']!=""?$value_cert['cname_jp']:$value_cert['cname_eng'];
                                                          $cname = $cname!=""?$cname:$value_cert['cname_th'];
                                                        }
                                                  ?>
                                                        <article class="certificate-card">
                                                          <div class="certificate-card-media">
                                                            <img class="certificate-badge-image" src="<?php echo REAL_PATH; ?>/assets/images/certificate-fallback.jpg" data-certificate-src="<?php echo base_url()."uploads/badges/".$value_cert['badges_img']; ?>" onerror="this.onerror=null;this.src='<?php echo REAL_PATH; ?>/assets/images/certificate-fallback.jpg';" alt="<?php echo htmlspecialchars($cname, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <span class="certificate-earned-badge"><i class="mdi mdi-certificate"></i><?php echo $lang == 'thai' ? 'ได้รับแล้ว' : 'Earned'; ?></span>
                                                          </div>
                                                          <div class="certificate-card-body">
                                                            <div class="certificate-card-meta">
                                                              <span><i class="mdi mdi-check-circle-outline"></i><?php echo $lang == 'thai' ? 'ผ่านหลักสูตร' : 'Course completed'; ?></span>
                                                            </div>
                                                            <h4><?php echo $cname; ?></h4>
                                                            <button type="button" name="cert_view" id="<?php echo $value_cert['cos_id']; ?>" class="certificate-view-button cert_view">
                                                              <span><?php echo $lang == 'thai' ? 'ดูใบประกาศ' : 'View certificate'; ?></span>
                                                              <i class="mdi mdi-arrow-right"></i>
                                                            </button>
                                                          </div>
                                                        </article>
                                                <?php } ?>
                                            </div>
                                            <?php }else{ ?>
                                                <div class="certificate-empty-state"><i class="mdi mdi-certificate"></i><h4><?php echo label('wg_datanotfound'); ?></h4><p><?php echo $lang == 'thai' ? 'เมื่อเรียนผ่านหลักสูตร ใบประกาศจะปรากฏที่นี่' : 'Your certificates will appear here after you complete a course.'; ?></p></div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="tab-pane" id="setting" role="tabpanel">
                                    <div class="card card-body profile-settings-panel">
                                        <form method="post" id="userprofile_form" autocomplete="off" name="userprofile_form" enctype="multipart/form-data"  class="form-horizontal form-material" role="form">
                                          <div class="profile-upload-field" aria-hidden="true">
                                            <input type="file" name="img_profile" id="img_profile" accept="image/png, image/jpeg, image/gif" tabindex="-1" />
                                            <input type="hidden" id="img_profile_ori" name="img_profile_ori" value="<?php echo $profile['img_profile']; ?>">
                                          </div>
                                          <div class="profile-form-heading">
                                            <h3><?php echo $lang == 'thai' ? 'ข้อมูลส่วนตัว' : 'Personal information'; ?></h3>
                                            <p><?php echo $lang == 'thai' ? 'ตรวจสอบข้อมูลชื่อและช่องทางการติดต่อของคุณ' : 'Review your identity and contact information'; ?></p>
                                          </div>
                                                      <input type="hidden" name="prefix_th" id="prefix_th">
                                                      <input type="hidden" name="prefix_en" id="prefix_en">
                                                      <div class="row"><!-- 
                                                          <div class="col-md-4">
                                                              <div class="form-group">
                                                                  <label for="prefix_th"> <?php echo label('m_prefix')." TH"; ?>: </label>
                                                                  <input type="text" class="form-control required" id="prefix_th" name="prefix_th" value="<?php echo $user['prefix_th']; ?>"> </div>
                                                          </div> -->
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="fname_th"><b style="color: #FF2D00">*</b><?php echo label('m_fname')." TH"; ?>: </label>
                                                                  <input type="text" class="form-control" required id="fname_th" name="fname_th" readonly value="<?php echo $user['fname_th']; ?>"> </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="lname_th"><b style="color: #FF2D00">*</b><?php echo label('m_lname')." TH"; ?>: </label>
                                                                  <input type="text" class="form-control" required id="lname_th" name="lname_th" readonly value="<?php echo $user['lname_th']; ?>"> </div>
                                                          </div>
                                                      </div>
                                                      <div class="row"><!-- 
                                                          <div class="col-md-4">
                                                              <div class="form-group">
                                                                  <label for="prefix_en"> <?php echo label('m_prefix')." EN"; ?>: </label>
                                                                  <input type="text" class="form-control" required id="prefix_en" name="prefix_en" value="<?php echo $user['prefix_en']; ?>"> </div>
                                                          </div> -->
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="fname_en"><b style="color: #FF2D00">*</b><?php echo label('m_fname')." EN"; ?>: </label>
                                                                  <input type="text" class="form-control" required id="fname_en" name="fname_en" readonly value="<?php echo $user['fname_en']; ?>"> </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="lname_en"><b style="color: #FF2D00">*</b><?php echo label('m_lname')." EN"; ?>: </label>
                                                                  <input type="text" class="form-control" required id="lname_en" name="lname_en" readonly value="<?php echo $user['lname_en']; ?>"> </div>
                                                          </div>
                                                      </div>

                                                      <!-- <div class="row">
                                                          <div class="col-md-12">
                                                              <div class="form-group">
                                                                  <label for="address_th"><?php echo label('m_address')." TH";?> :</label>
                                                                  <textarea name="address_th" id="address_th" rows="3" class="form-control"><?php echo $user['address_th']; ?></textarea>
                                                              </div>
                                                          </div>
                                                          <div class="col-md-12">
                                                              <div class="form-address_en">
                                                                  <label for="webUrl3"><?php echo label('m_address')." EN";?> :</label>
                                                                  <textarea name="address_en" id="address_en" rows="3" class="form-control"><?php echo $user['address_en']; ?></textarea>
                                                              </div>
                                                          </div>
                                                      </div>

                                                      <div class="row">
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="work_phone"> <?php echo label('m_workphone'); ?>: </label>
                                                                  <input type="text" class="form-control" id="work_phone" name="work_phone" value="<?php echo $user['work_phone']; ?>"> </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="phone"> <?php echo label('m_phone'); ?>: </label>
                                                                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>"> </div>
                                                          </div>
                                                      </div> -->
                                                      <div class="row">
                                                          <div class="col-md-12">
                                                              <div class="form-group">
                                                                  <label for="email"><b style="color: #FF2D00">*</b><?php echo label('m_mail'); ?>: </label>
                                                                  <input type="email" class="form-control" <?php if($user['email']==""){?>required <?php }else{?> readonly <?php } ?> id="email" name="email" value="<?php echo $user['email']; ?>"> </div>
                                                          </div>
                                                      </div>
                                                      <div class="row profile-detail-row">
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="work_phone"><b style="color: #FF2D00">*</b><?php echo $lang == 'thai' ? 'เบอร์โทรศัพท์' : 'Phone number'; ?>: </label>
                                                                  <input type="text" class="form-control" id="work_phone" name="work_phone" value="<?php echo $user['work_phone']; ?>">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                                  <label for="profile_role"><?php echo $lang == 'thai' ? 'ตำแหน่ง' : 'Role'; ?>: </label>
                                                                  <input type="text" class="form-control" id="profile_role" readonly value="<?php echo $ugname; ?>">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="row profile-detail-row">
                                                          <div class="col-md-12">
                                                              <div class="form-group">
                                                                  <label for="profile_language"><?php echo $lang == 'thai' ? 'ภาษาเริ่มต้นของระบบ' : 'Default system language'; ?>: </label>
                                                                  <div class="profile-language-select">
                                                                    <select class="form-control" id="profile_language" name="profile_language">
                                                                      <option value="thai" <?php echo $lang == 'thai' ? 'selected' : ''; ?>>ไทย</option>
                                                                      <option value="english" <?php echo $lang == 'english' ? 'selected' : ''; ?>>English</option>
                                                                      <option value="japan" <?php echo $lang == 'japan' ? 'selected' : ''; ?>>日本語</option>
                                                                    </select>
                                                                    <i class="mdi mdi-chevron-down" aria-hidden="true"></i>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <input type="hidden" id="com_id" name="com_id" value="<?php echo $user['com_id']; ?>">
                                                      <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $user['emp_id']; ?>">
                                                      <input type="hidden" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
                                                      <input type="hidden" id="address_th" name="address_th" value="<?php echo $user['address_th']; ?>">
                                                      <input type="hidden" id="address_en" name="address_en" value="<?php echo $user['address_en']; ?>">
                                                      <input type="hidden" id="u_id" name="u_id" value="<?php echo $user['u_id']; ?>">
                                                      <div class="row">
                                                        <div class="col-md-12 profile-save-row"><button type="submit" class="btn profile-save-button"><i class="mdi mdi-content-save"></i> <?php echo label('btn_saveprofile'); ?></button></div>
                                                      </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            </div>
        </div>
    </div>


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

    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-certificate" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel"><i class="mdi mdi-certificate"></i> <?php echo label('dash_btn_certificate'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body divEmbed">

                        <iframe id="obj_pdf_cert"  style="width:100%; height: 97vh;" frameborder="0">
                        </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <!--stickey kit -->
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        var tab = '<?php echo $tabshow; ?>';
        if(tab!=""){
          console.log(tab);
          $('.nav-tabs a[href="#'+tab+'"]').tab('show')
        }else{
          $('.nav-tabs a[href="#setting"]').tab('show');
        }

        $(document).on('submit', '#certificate_form', function(event){
              event.preventDefault(); 
              $('#modal-process').modal('show');
              $.ajax({
                  url: '<?=base_url()?>index.php/certificate/generate',
                  method: 'GET',
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
                  }
                });
        });

        $('.dropify_cert').dropify();
        $('select[name="certimg"]').on('change', function(){
          var certimg = $(this).val();
          if(certimg=="upload"){
            document.getElementById('upload').style.display = "";
          }else{
            document.getElementById('upload').style.display = "none";
          }
        });
          $(document).on('submit', '#userprofile_form', function(event){
              event.preventDefault(); 
                var $profileButton = $(this).find('.profile-save-button');
                var profileButtonHtml = $profileButton.html();
                $.ajax({
                  url:"<?=base_url()?>index.php/manage/update_profile",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  beforeSend:function()
                  {
                    $profileButton.prop('disabled', true).html('<i class="mdi mdi-content-save"></i> <?php echo $lang == "thai" ? "กำลังบันทึก..." : "Saving..."; ?>');
                  },
                  success:function(data)
                  {
                    if(data=="2"){
                        //$('#userprofile_form')[0].reset();
                        $('#modal-default').modal('hide');
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          var selectedLanguage = $('#profile_language').val();
                          var currentLanguage = <?php echo json_encode($lang); ?>;
                          window.location.href = selectedLanguage !== currentLanguage
                            ? "<?php echo base_url(); ?>home/change_lang/" + selectedLanguage
                            : "<?php echo base_url(); ?>dashboard/profile";
                           // $('.nav-tabs a[href="#setting"]').tab('show');
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                      })
                    }
                  },
                  error:function(xhr)
                  {
                    var uploadMessage = xhr.responseText === 'upload_error'
                      ? '<?php echo $lang == "thai" ? "กรุณาเลือกไฟล์รูปภาพ JPG, PNG หรือ GIF ที่ถูกต้อง" : "Please choose a valid JPG, PNG, or GIF image."; ?>'
                      : '<?php echo $lang == "thai" ? "ไม่สามารถบันทึกรูปโปรไฟล์ได้ กรุณาลองใหม่อีกครั้ง" : "The profile image could not be saved. Please try again."; ?>';
                    swal({
                      title: '<?php echo label('com_msg_error_save'); ?>',
                      text: uploadMessage,
                      type: 'warning',
                      showCancelButton: false,
                      confirmButtonClass: 'btn btn-primary',
                      confirmButtonText: '<?php echo label("m_ok"); ?>'
                    });
                  },
                  complete:function()
                  {
                    $profileButton.prop('disabled', false).html(profileButtonHtml);
                  }
                });
            });
        var certificateImagesLoaded = false;
        function loadCertificateImages()
        {
          if (certificateImagesLoaded) return;
          certificateImagesLoaded = true;
          $('.certificate-badge-image').each(function () {
            var source = $(this).attr('data-certificate-src');
            if (source) $(this).attr('src', source);
          });
        }

        $('a[data-toggle="tab"][href="#certificate"]').on('shown.bs.tab', loadCertificateImages);
        if (tab === 'certificate') loadCertificateImages();

        function clear_dropify(id){
            var drEvent = $(id).dropify(
                    {
                      defaultFile: ''
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = '';
                    drEvent.destroy();
                    drEvent.init();
        }

                  var img_profile = $('.dropify').dropify({
                       defaultFile: "<?php echo media_url('uploads/profile/'.$profile['img_profile'], 'uploads/profile/default_profile.jpg'); ?>" ,
                  });
                  
                  img_profile.on('dropify.beforeClear', function(event, element){

                      swal({
                          title: '<?php echo label('wg_delete_msg'); ?> ',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: '<?php echo label('wg_delete'); ?>',
                          cancelButtonText: '<?php echo label("m_cancel"); ?>'
                      }).then(function (isChk) {
                        if(isChk.value){
                            $.ajax({
                                  url:"<?=base_url()?>index.php/querydata/delete_img_profile",
                                  method:"POST",
                                  data:{u_id:'<?php echo $user["u_id"]; ?>',type:'1'},
                                  dataType:"json",
                                  success:function(data)
                                  {
                                      if(data.status=="2"){
                                          swal(
                                              '<?php echo label("com_msg_delete"); ?>!',
                                              '',
                                              'success'
                                          ).then(function () {
                                            location.reload();
                                          })
                                      }else{
                                          swal({
                                              title: '<?php echo label('com_msg_error_save'); ?>',
                                              text: "",
                                              type: 'warning',
                                              showCancelButton: false,
                                              confirmButtonClass: 'btn btn-primary',
                                              confirmButtonText: '<?php echo label('m_ok'); ?>'
                                          })
                                      }
                                  }
                            });
                        }else{
                          var img_profile = $('.dropify').dropify({
                               defaultFile: "<?php echo media_url('uploads/profile/'.$profile['img_profile'], 'uploads/profile/default_profile.jpg'); ?>" ,
                          });
                        }
                      });
                  });

                  var bgpic_user = $('.dropify_bg').dropify({
                       defaultFile: "<?php echo REAL_PATH;?>/uploads/bg_user/<?php echo $profile['bgpic_user']; ?>" ,
                  });
                  bgpic_user.on('dropify.beforeClear', function(event, element){

                      swal({
                          title: '<?php echo label('wg_delete_msg'); ?> ',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: '<?php echo label('wg_delete'); ?>',
                          cancelButtonText: '<?php echo label("m_cancel"); ?>'
                      }).then(function (isChk) {
                        if(isChk.value){
                            $.ajax({
                                  url:"<?=base_url()?>index.php/querydata/delete_img_profile",
                                  method:"POST",
                                  data:{u_id:'<?php echo $user["u_id"]; ?>',type:'2'},
                                  dataType:"json",
                                  success:function(data)
                                  {
                                      if(data.status=="2"){
                                          swal(
                                              '<?php echo label("com_msg_delete"); ?>!',
                                              '',
                                              'success'
                                          ).then(function () {
                                            location.reload();
                                          })
                                      }else{
                                          swal({
                                              title: '<?php echo label('com_msg_error_save'); ?>',
                                              text: "",
                                              type: 'warning',
                                              showCancelButton: false,
                                              confirmButtonClass: 'btn btn-primary',
                                              confirmButtonText: '<?php echo label('m_ok'); ?>'
                                          })
                                      }
                                  }
                            });
                        }else{
                          var bgpic_user = $('.dropify_bg').dropify({
                               defaultFile: "<?php echo REAL_PATH;?>/uploads/bg_user/<?php echo $profile['bgpic_user']; ?>" ,
                          });
                        }
                      });
                  });
        function fetch_data_cert()
         {
            $('#myTable').DataTable().destroy();
            $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
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
                "ajax": {
                    url : '<?=base_url()?>index.php/profile/fetch_course_cert/',
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
                },
            });
         }

         $(document).on('click', '.cert_view', function(){
            var cos_id = $(this).attr("id");
            $.ajax({
                  url:"<?=base_url()?>index.php/certificate/createfile",
                  method:"POST",
                  data:{cos_id:cos_id},
                  dataType:"json",
                  success:function(data)
                  {
                    $('#modal-certificate').modal('show');
                    $('#obj_pdf_cert').attr('src', "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data);
                    //$( "object").attr('data', "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data);
                    //$('#obj_pdf_cert').html( "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data);
                    //$('#obj_pdf_cert').attr('data', "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data);
                    //document.getElementById('obj_pdf_cert').data = "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data;
                  }
            });
          });

          $('#img_profile').on('change', function () {
            var input = this;
            if (!input.files || !input.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (event) {
              $('#profilePortraitPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(input.files[0]);
          });
    </script>
</body>

</html>
