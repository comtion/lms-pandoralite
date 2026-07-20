<?php
if ($lang == "") {
	$lang = "thai";
}
$precision_swal_locale = array(
	'thai' => array('code' => 'th', 'confirm' => 'ตกลง', 'cancel' => 'ยกเลิก', 'close' => 'ปิดหน้าต่าง'),
	'english' => array('code' => 'en', 'confirm' => 'OK', 'cancel' => 'Cancel', 'close' => 'Close dialog'),
	'japan' => array('code' => 'ja', 'confirm' => '確認', 'cancel' => 'キャンセル', 'close' => '閉じる')
);
$precision_swal_text = isset($precision_swal_locale[$lang]) ? $precision_swal_locale[$lang] : $precision_swal_locale['english'];
?>
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-premium.css?v=20260720-2">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-precision.css?v=20260720-20">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-sidebar-v2.css?v=20260720-18">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/precision-global.css?v=20260720-35">
<script>
document.body.classList.add('lms-premium-dashboard','precision-app-shell');
(function (locale) {
  document.documentElement.lang = locale.code;
  window.PRECISION_UI_LOCALE = locale;

  function applySweetAlertLocale() {
    if (window.swal && typeof window.swal.setDefaults === 'function') {
      window.swal.setDefaults({
        confirmButtonText: locale.confirm,
        cancelButtonText: locale.cancel
      });
    }
  }

  function localizeOpenDialogs() {
    var closeButtons = document.querySelectorAll('.swal2-close');
    for (var i = 0; i < closeButtons.length; i++) {
      closeButtons[i].setAttribute('aria-label', locale.close);
      closeButtons[i].setAttribute('title', locale.close);
    }
  }

  applySweetAlertLocale();
  document.addEventListener('DOMContentLoaded', function () {
    applySweetAlertLocale();
    localizeOpenDialogs();
    if (window.MutationObserver) {
      new MutationObserver(localizeOpenDialogs).observe(document.body, { childList: true, subtree: true });
    }
  });
})(<?php echo json_encode($precision_swal_text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
(function () {
  var loader = document.querySelector('.preloader .loader');
  if (!loader) return;
  loader.classList.add('precision-loader');
  if (!loader.querySelector('.precision-loader-brand')) {
    var brand = document.createElement('div');
    brand.className = 'precision-loader-brand';
    brand.innerHTML = '<img src="<?php echo REAL_PATH; ?>/images/logo.png" alt="ISUZU Thailand"><span></span><img src="<?php echo REAL_PATH; ?>/images/elearning_logo.png" alt="E-Learning">';
    loader.insertBefore(brand, loader.firstChild);
  }
  if (!loader.querySelector('.precision-loader-progress')) {
    var progress = document.createElement('div');
    progress.className = 'precision-loader-progress';
    progress.setAttribute('aria-hidden', 'true');
    progress.innerHTML = '<span></span>';
    loader.appendChild(progress);
    var status = document.createElement('small');
    status.textContent = <?php echo json_encode($lang === 'thai' ? 'กำลังเตรียมพื้นที่การเรียนรู้ของคุณ' : 'Preparing your learning workspace', JSON_UNESCAPED_UNICODE); ?>;
    loader.appendChild(status);
  }
})();
</script>
<style>
.elearning-logo {
  left: 0;
  right: 0;
  max-width: 250px;
}

@media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) {
  .elearning-logo {
    max-width: 100px;
  }
}
</style>
<header class="topbar">
  <nav class="navbar top-navbar navbar-expand-md navbar-light">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo REAL_PATH; ?>/dashboard">
        <?php if (!empty($emp_c)) { ?>
        <img <?php if (isMobile()) { ?>width="100%" <?php } else { ?>height="50" <?php } ?>
          src="<?php echo $foote[0]['da_logo_top']; ?>" class="dark-logo" />
        <?php } else { ?>
        <img <?php if (isMobile()) { ?>width="100%" <?php } else { ?>height="50" <?php } ?>
          src="<?php echo REAL_PATH; ?>/images/logo.png" class="dark-logo" />
        <?php } ?>
      </a>
    </div>
    <div class="navbar-collapse">
      <?php if (is_file(ROOT_DIR . "images/elearning_logo.png")) { ?><img
        class="position-absolute m-auto hidden-md-down elearning-logo" <?php if (isMobile()) { ?>width="100%"
        <?php } else { ?>height="50" <?php } ?> src="<?php echo REAL_PATH; ?>/images/elearning_logo.png" alt="">
      <?php } ?>

      <?php if (!empty($emp_c)) { ?>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark"
            href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
        <li class="nav-item hidden-sm-down"></li>
      </ul>
      <?php } else { ?>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"> </li>
        <li class="nav-item hidden-sm-down"></li>
      </ul>
      <?php } ?>

      <?php if (!empty($emp_c)) { ?>
      <?php
        $precisionHeaderName = $lang === 'thai'
          ? ($foote[0]['fetch_usp']['fullname_th'] ?? $foote[0]['fetch_usp']['fullname_en'] ?? '')
          : ($foote[0]['fetch_usp']['fullname_en'] ?? $foote[0]['fetch_usp']['fullname_th'] ?? '');
        $precisionHeaderRole = $lang === 'thai'
          ? ($foote[0]['fetch_uspgp']['ug_name_th'] ?? $foote[0]['fetch_uspgp']['ug_name_en'] ?? '')
          : ($foote[0]['fetch_uspgp']['ug_name_en'] ?? $foote[0]['fetch_uspgp']['ug_name_th'] ?? '');
      ?>
      <div class="precision-commandbar">
        <button type="button" class="precision-search-trigger" data-toggle="modal" data-target="#modal-searchform">
          <i class="mdi mdi-magnify"></i>
          <span><?php echo $lang === 'thai' ? 'ค้นหาหลักสูตร, เนื้อหา, คู่มือ...' : 'Search courses, content, guides...'; ?></span>
          <kbd>⌘ K</kbd>
        </button>
        <span class="precision-command-divider"></span>
        <div class="precision-language">
          <i class="flag-icon <?php echo $lang === 'thai' ? 'flag-icon-th' : ($lang === 'japan' ? 'flag-icon-jp' : 'flag-icon-us'); ?>"></i>
          <strong><?php echo $lang === 'thai' ? 'ไทย' : ($lang === 'japan' ? '日本語' : 'EN'); ?></strong>
          <i class="mdi mdi-chevron-down"></i>
          <div class="precision-language-menu">
            <a class="<?php echo $lang === 'thai' ? 'is-active' : ''; ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/thai"<?php echo $lang === 'thai' ? ' aria-current="true"' : ''; ?>><i class="flag-icon flag-icon-th"></i><span>ไทย</span><?php if ($lang === 'thai') { ?><i class="mdi mdi-check precision-language-check"></i><?php } ?></a>
            <a class="<?php echo $lang === 'english' ? 'is-active' : ''; ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/english"<?php echo $lang === 'english' ? ' aria-current="true"' : ''; ?>><i class="flag-icon flag-icon-us"></i><span>English</span><?php if ($lang === 'english') { ?><i class="mdi mdi-check precision-language-check"></i><?php } ?></a>
            <a class="<?php echo $lang === 'japan' ? 'is-active' : ''; ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/japan"<?php echo $lang === 'japan' ? ' aria-current="true"' : ''; ?>><i class="flag-icon flag-icon-jp"></i><span>日本語</span><?php if ($lang === 'japan') { ?><i class="mdi mdi-check precision-language-check"></i><?php } ?></a>
          </div>
        </div>
        <span class="precision-command-divider"></span>
        <div class="precision-command-menu precision-notification-menu">
          <button type="button" class="precision-notification" aria-label="Notifications" aria-haspopup="true"><i class="mdi mdi-bell-outline"></i><span>3</span></button>
          <div class="precision-command-popover" role="menu">
            <div class="precision-popover-head"><strong><?php echo $lang === 'thai' ? 'การแจ้งเตือน' : 'Notifications'; ?></strong><small>3 <?php echo $lang === 'thai' ? 'รายการใหม่' : 'new'; ?></small></div>
            <a href="<?php echo REAL_PATH; ?>/course/ongoing"><i class="mdi mdi-play-circle-outline"></i><span><strong><?php echo $lang === 'thai' ? 'เรียนหลักสูตรต่อ' : 'Continue learning'; ?></strong><small><?php echo $lang === 'thai' ? 'หลักสูตรของคุณยังเรียนไม่จบ' : 'Your course is still in progress'; ?></small></span></a>
            <a href="<?php echo REAL_PATH; ?>/dashboard/profile/certificate"><i class="mdi mdi-certificate"></i><span><strong><?php echo $lang === 'thai' ? 'ตรวจสอบใบประกาศ' : 'Check certificates'; ?></strong><small><?php echo $lang === 'thai' ? 'ดูใบประกาศที่ได้รับล่าสุด' : 'View your latest certificates'; ?></small></span></a>
            <a href="<?php echo REAL_PATH; ?>/report/loadreport_personal"><i class="mdi mdi-chart-line"></i><span><strong><?php echo $lang === 'thai' ? 'รายงานการเรียนรู้' : 'Learning report'; ?></strong><small><?php echo $lang === 'thai' ? 'ติดตามความก้าวหน้าของคุณ' : 'Track your learning progress'; ?></small></span></a>
          </div>
        </div>
        <span class="precision-command-divider"></span>
        <div class="precision-command-menu precision-profile-menu"><button type="button" class="precision-header-profile" aria-haspopup="true">
          <?php if (!empty($foote[0]['fetch_usp']['img_profile'])) { ?>
          <img src="<?php echo media_url('uploads/profile/'.$foote[0]['fetch_usp']['img_profile'], 'uploads/profile/default_profile.jpg'); ?>" alt="">
          <?php } else { ?>
          <img src="<?php echo media_url('uploads/profile/default_profile.jpg'); ?>" alt="">
          <?php } ?>
          <span><strong><?php echo htmlspecialchars($precisionHeaderName, ENT_QUOTES, 'UTF-8'); ?></strong><small><?php echo htmlspecialchars($precisionHeaderRole, ENT_QUOTES, 'UTF-8'); ?></small></span>
          <i class="mdi mdi-chevron-down"></i>
        </button><div class="precision-command-popover precision-profile-popover" role="menu">
          <a href="<?php echo REAL_PATH; ?>/dashboard/profile"><i class="mdi mdi-account-outline"></i><span><strong><?php echo $lang === 'thai' ? 'โปรไฟล์ของฉัน' : 'My profile'; ?></strong><small><?php echo $lang === 'thai' ? 'แก้ไขข้อมูลส่วนตัว' : 'Manage personal details'; ?></small></span></a>
          <a href="<?php echo REAL_PATH; ?>/dashboard/change_pass"><i class="mdi mdi-lock-outline"></i><span><strong><?php echo $lang === 'thai' ? 'เปลี่ยนรหัสผ่าน' : 'Change password'; ?></strong></span></a>
          <a class="is-danger" href="<?php echo REAL_PATH; ?>/dashboard/logout"><i class="mdi mdi-logout"></i><span><strong><?php echo $lang === 'thai' ? 'ออกจากระบบ' : 'Sign out'; ?></strong></span></a>
        </div></div>
      </div>
      <?php } ?>
      <ul class="navbar-nav my-lg-0">
        <!-- <?php if (strpos($page, 'home') !== false) { ?>
                <li class="nav-item hidden-xs-down search-box"> <a class="nav-link hidden-sm-down waves-effect waves-dark"  data-toggle="modal" data-target="#modal-searchform" href="javascript:void(0)"><i class="ti-search"></i></a></li>
            <?php } ?> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <?php if ($lang == "thai") { ?>
            <style>
            * {
              font-family: Tahoma, sans-serif;
            }
            </style>
            <i class="flag-icon flag-icon-th"></i>
            <?php } else if ($lang == "english") { ?>
            <style>
            * {
              font-family: Roboto, sans-serif;
            }
            </style>
            <i class="flag-icon flag-icon-us"></i>
            <?php } else { ?>
            <style>
            * {
              font-family: 'Noto Sans JP', sans-serif;
            }
            </style>
            <i class="flag-icon flag-icon-jp"></i>
            <?php } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right animated bounceInDown">
            <a class="dropdown-item <?php if ($lang == "english") {
																			echo "active";
																		} ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/english"><i class="flag-icon flag-icon-us"></i>
              <?php echo label('english'); ?></a>
            <a class="dropdown-item <?php if ($lang == "thai") {
																			echo "active";
																		} ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/thai"><i class="flag-icon flag-icon-th"></i>
              <?php echo label('thailand'); ?></a>
            <a class="dropdown-item <?php if ($lang == "japan") {
																			echo "active";
																		} ?>" href="<?php echo REAL_PATH; ?>/home/change_lang/japan"><i class="flag-icon flag-icon-jp"></i>
              <?php echo label('japan'); ?></a>
          </div>
        </li>

        <li class="nav-item dropdown" id="profile_user">
          <?php if (!empty($emp_c)) { ?>
          <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user"></i>
          </a>
          <?php } ?>
          <div class="dropdown-menu dropdown-menu-right animated flipInY">
            <ul class="dropdown-user">
              <?php if (empty($emp_c)) { ?>
              <li> <a href="<?php echo REAL_PATH; ?>/dashboard/login"><i class="fas fa-key"></i>
                  <?php echo label('login'); ?></a></li>
              <?php } else { ?>
              <li>
                <div class="dw-user-box">
                  <?php
										$ar_userlist = $this->session->userdata('user');
										if ($lang == "thai") {
											$lname = $foote[0]['fetch_usp']['fullname_th'];
											$ugname = $foote[0]['fetch_uspgp']['ug_name_th'];
										} else {
											$lname = $foote[0]['fetch_usp']['fullname_en'];
											$ugname = $foote[0]['fetch_uspgp']['ug_name_en'];
										}

										if ($ugname == "Learner (Manager)") {
											$ugname = "Learner";
										}
										if (isset($foote[0]['fetch_usp']['img_profile']) && $foote[0]['fetch_usp']['img_profile'] != "") {
										?>
                  <div class="u-img"><img
                      src="<?php echo media_url('uploads/profile/'.$foote[0]['fetch_usp']['img_profile'], 'uploads/profile/default_profile.jpg'); ?>"
                      alt="user">
                  </div>
                  <?php } else { ?>
                  <div class="u-img"><img src="<?php echo media_url('uploads/profile/default_profile.jpg'); ?>" alt="user">
                  </div>
                  <?php } ?>
                  <div class="u-text">
                    <h4><?php echo $lname . "<br>" . $ugname; ?></h4>
                    <a href="<?php echo REAL_PATH; ?>/dashboard/profile"
                      class="btn btn-rounded btn-danger btn-sm"><?php echo label('view_profile'); ?></a>
                  </div>
                </div>
              </li>
              <li role="separator" class="divider"></li>

              <li><a href="<?php echo REAL_PATH; ?>/dashboard/change_pass"><i class="fas fa-key"></i>
                  <?php echo label('change_pass'); ?></a></li>

              <li><a href="<?php echo REAL_PATH; ?>/dashboard/logout"><i class="fa fa-power-off"></i>
                  <?php echo label('logout'); ?></a></li>
              <?php } ?>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!--Slider-->
</header>

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-searchform" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <form enctype="multipart/form-data" autocomplete="off" id="searchform_form" name="searchform_form" method="POST"
        class="form-horizontal p-t-20">
        <div class="modal-body">
          <input type="text" id="search_text" name="search_text" class="form-control search_text"
            onInput="edValueKeyPress()">
          <hr>
          <div id="div_search" name="div_search"></div>
        </div>
        <input type="hidden" id="search_value" name="search_value">
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger btn-flat"
            data-dismiss="modal"><?php echo label('close'); ?></button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
function edValueKeyPress() {
  var edValue = document.getElementById("search_text");
  var txtval = edValue.value;

  $.ajax({
    url: "<?= base_url() ?>index.php/course/search_course",
    method: "POST",
    data: {
      txtval_search: txtval
    },
    success: function(data) {
      if (txtval == "") {
        data = '<h4 align="center"><?php echo label('wg_datanotfound'); ?></h4>';
      }
      $('#div_search').html(data);
    }
  });
}

function onclickdetail_search(cos_id) {
  <?php if (empty($emp_c)) { ?>
  $('#modal-detailcourse').modal('show');

  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_course_data",
    method: "POST",
    data: {
      id_update: cos_id
    },
    dataType: "json",
    success: function(data) {
      <?php if ($lang == "thai") { ?>
      $('#txt_coursehead').text(data.cname_th);
      $('#description_course').html(data.cdesc_th);
      <?php } else { ?>
      $('#txt_coursehead').text(data.cname_en);
      $('#description_course').html(data.cdesc_en);
      <?php } ?>
      document.getElementById("img_coursehead").src = "<?php echo REAL_PATH; ?>/uploads/course/" + data.pic;

    }
  });
  <?php } else { ?>
  window.location.href = '<?= base_url() ?>index.php/course/detail/' + cos_id + '/1';
  <?php } ?>
}
</script>
