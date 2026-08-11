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
$precision_csrf_name = (string) ($this->security->get_csrf_token_name() ?? '');
$precision_csrf_token = (string) ($this->security->get_csrf_hash() ?? '');
$precision_login_success = $this->session->flashdata('login_success');
if ($precision_login_success !== null) {
	// Consume immediately so refresh/back navigation never repeats the toast.
	$this->session->unset_userdata('login_success');
}
?>
<?php if (is_array($precision_login_success)): ?>
<div id="precision-login-toast" class="precision-login-toast" role="status" aria-live="polite">
  <span class="precision-login-toast-icon"><i class="mdi mdi-check"></i></span>
  <span><strong><?php echo $lang === 'thai' ? 'เข้าสู่ระบบสำเร็จ' : 'Signed in successfully'; ?></strong>
  <small><?php echo $lang === 'thai' ? 'ยินดีต้อนรับ ' : 'Welcome, '; ?><?php echo htmlspecialchars((string) ($precision_login_success['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></small></span>
  <button type="button" aria-label="<?php echo $lang === 'thai' ? 'ปิด' : 'Close'; ?>">&times;</button>
</div>
<style>
.precision-login-toast{position:fixed;top:82px;right:24px;z-index:10050;display:flex;align-items:center;gap:12px;min-width:300px;max-width:420px;padding:14px 16px;background:#fff;border:1px solid #d8eee3;border-left:4px solid #22a06b;border-radius:10px;box-shadow:0 14px 38px rgba(20,45,35,.18);color:#173b2c;opacity:0;transform:translateY(-12px);transition:opacity .25s ease,transform .25s ease}
.precision-login-toast.is-visible{opacity:1;transform:translateY(0)}.precision-login-toast-icon{display:grid;place-items:center;width:34px;height:34px;border-radius:50%;background:#e5f7ed;color:#168653;font-size:20px}.precision-login-toast span:nth-child(2){display:flex;flex:1;flex-direction:column}.precision-login-toast small{margin-top:2px;color:#607269}.precision-login-toast button{border:0;background:transparent;color:#738078;font-size:22px;cursor:pointer}@media(max-width:600px){.precision-login-toast{top:68px;left:12px;right:12px;min-width:0}}
</style>
<script>
(function(){
  function showLoginToast(){
    var toast=document.getElementById('precision-login-toast'); if(!toast)return;
    var timer; function close(){toast.classList.remove('is-visible');window.setTimeout(function(){toast.remove();},260);}
    toast.querySelector('button').addEventListener('click',close);
    window.requestAnimationFrame(function(){toast.classList.add('is-visible');});
    timer=window.setTimeout(close,4000);
    toast.addEventListener('mouseenter',function(){window.clearTimeout(timer);});
    toast.addEventListener('mouseleave',function(){timer=window.setTimeout(close,1500);});
  }
  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',showLoginToast);}else{showLoginToast();}
})();
</script>
<?php endif; ?>
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-premium.css?v=20260720-2">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-precision.css?v=20260806-4">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-sidebar-v2.css?v=20260720-18">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/precision-global.css?v=20260724-38">
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/menu-modal-focus.css?v=20260806-3">
<script src="<?php echo REAL_PATH; ?>/assets/js/precision-selects.js?v=20260731-1" defer></script>
<meta name="lms-base-url" content="<?php echo htmlspecialchars(rtrim(REAL_PATH, '/'), ENT_QUOTES, 'UTF-8'); ?>">
<meta name="lms-csrf-name" content="<?php echo htmlspecialchars($precision_csrf_name, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="lms-csrf-token" content="<?php echo htmlspecialchars($precision_csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="lms-language" content="<?php echo htmlspecialchars($lang, ENT_QUOTES, 'UTF-8'); ?>">
<!-- Load synchronously so the CSRF ajaxSend hook is installed before page-level
     scripts issue their initial POST requests. -->
<script src="<?php echo REAL_PATH; ?>/assets/js/p0-notifications.js?v=20260806-3"></script>
<script>
document.body.classList.add('lms-premium-dashboard','precision-app-shell');
(function () {
  function applySharedModalStyle(root) {
    if (!root || root.nodeType !== 1) return;
    var modals = [];
    if (root.matches && root.matches('.modal')) modals.push(root);
    if (root.querySelectorAll) modals = modals.concat(Array.prototype.slice.call(root.querySelectorAll('.modal')));

    modals.forEach(function (modal) {
      modal.classList.add('app-focus-modal');

      var dialog = modal.querySelector('.modal-dialog');
      if (dialog) dialog.classList.add('modal-dialog-centered');

      var header = modal.querySelector('.modal-header');
      var title = header && header.querySelector('.modal-title, h1, h2, h3, h4, h5, h6');
      if (title) title.classList.add('modal-title');

      Array.prototype.forEach.call(modal.querySelectorAll('button[type="submit"], input[type="submit"]'), function (button) {
        button.classList.add('btn-modal-primary');
      });
      Array.prototype.forEach.call(modal.querySelectorAll('[data-dismiss="modal"]'), function (button) {
        if (!button.classList.contains('close')) button.classList.add('btn-modal-secondary');
      });
    });
  }

  applySharedModalStyle(document.body);
  if (window.MutationObserver) {
    new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        Array.prototype.forEach.call(mutation.addedNodes, applySharedModalStyle);
      });
    }).observe(document.body, {childList: true, subtree: true});
  }
}());
(function (locale) {
  document.documentElement.lang = locale.code;
  window.PRECISION_UI_LOCALE = locale;

  function applySweetAlertLocale() {
    if (window.swal && typeof window.swal.setDefaults === 'function') {
      window.swal.setDefaults({
        confirmButtonText: locale.confirm
      });
    }
  }

  function localizeOpenDialogs() {
    var closeButtons = document.querySelectorAll('.swal2-close');
    for (var i = 0; i < closeButtons.length; i++) {
      closeButtons[i].setAttribute('aria-label', locale.close);
      closeButtons[i].setAttribute('title', locale.close);
    }
    var cancelButtons = document.querySelectorAll('.swal2-cancel');
    for (var j = 0; j < cancelButtons.length; j++) {
      if (cancelButtons[j].textContent !== locale.cancel) {
        cancelButtons[j].textContent = locale.cancel;
      }
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
          <button type="button" class="precision-notification" id="lms-notification-button" aria-label="Notifications" aria-haspopup="true"><i class="mdi mdi-bell-outline"></i><span id="lms-notification-count" hidden>0</span></button>
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
		  <a href="#" data-toggle="modal" data-target="#change-password-modal"><i class="mdi mdi-lock-outline"></i><span><strong><?php echo $lang === 'thai' ? 'เปลี่ยนรหัสผ่าน' : 'Change password'; ?></strong></span></a>
          <a class="is-danger" href="<?php echo REAL_PATH; ?>/dashboard/logout"><i class="mdi mdi-logout"></i><span><strong><?php echo $lang === 'thai' ? 'ออกจากระบบ' : 'Sign out'; ?></strong></span></a>
        </div></div>
      </div>
      <?php } ?>
      <ul class="navbar-nav my-lg-0<?php echo empty($emp_c) ? ' premium-guest-language-nav' : ''; ?>">
        <!-- <?php if (strpos($page, 'home') !== false) { ?>
                <li class="nav-item hidden-xs-down search-box"> <a class="nav-link hidden-sm-down waves-effect waves-dark"  data-toggle="modal" data-target="#modal-searchform" href="javascript:void(0)"><i class="ti-search"></i></a></li>
            <?php } ?> -->
        <li class="nav-item dropdown<?php echo empty($emp_c) ? ' premium-guest-language' : ''; ?>">
          <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <?php if (empty($emp_c)) { ?>
            <i class="mdi mdi-web premium-guest-language-globe" aria-hidden="true"></i>
            <?php } ?>
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
            <?php if (empty($emp_c)) { ?>
            <span class="premium-guest-language-label"><?php echo $lang === 'thai' ? 'ไทย' : ($lang === 'japan' ? '日本語' : 'English'); ?></span>
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

			  <li><a href="#" data-toggle="modal" data-target="#change-password-modal"><i class="fas fa-key"></i>
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
<?php if (!empty($emp_c)): ?>
<div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="change-password-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content precision-password-modal">
      <div class="modal-header">
        <div><small><?php echo $lang === 'thai' ? 'ความปลอดภัยบัญชี' : 'Account security'; ?></small>
          <h4 class="modal-title" id="change-password-title"><?php echo $lang === 'thai' ? 'เปลี่ยนรหัสผ่าน' : 'Change password'; ?></h4></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form id="change-password-modal-form" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="form-group"><label for="modal-oldpass"><?php echo $lang === 'thai' ? 'รหัสผ่านเดิม' : 'Current password'; ?> <span class="text-danger">*</span></label>
            <div class="precision-password-field"><input class="form-control" id="modal-oldpass" name="oldpass" type="password" autocomplete="current-password" required><button type="button" class="precision-password-eye" data-password-target="#modal-oldpass" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div></div>
          <div class="form-group"><label for="modal-newpass"><?php echo $lang === 'thai' ? 'รหัสผ่านใหม่' : 'New password'; ?> <span class="text-danger">*</span></label>
            <div class="precision-password-field"><input class="form-control" id="modal-newpass" name="newpass" type="password" minlength="10" autocomplete="new-password" required><button type="button" class="precision-password-eye" data-password-target="#modal-newpass" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div></div>
          <div class="form-group"><label for="modal-confirmpass"><?php echo $lang === 'thai' ? 'ยืนยันรหัสผ่านใหม่' : 'Confirm new password'; ?> <span class="text-danger">*</span></label>
            <div class="precision-password-field"><input class="form-control" id="modal-confirmpass" name="confirmpass" type="password" minlength="10" autocomplete="new-password" required><button type="button" class="precision-password-eye" data-password-target="#modal-confirmpass" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div></div>
          <p class="precision-password-help"><i class="mdi mdi-information-outline"></i> <?php echo $lang === 'thai' ? 'อย่างน้อย 10 ตัว มีพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ และห้ามซ้ำ 3 ครั้งล่าสุด' : 'Use 10+ characters with upper/lowercase, a number and a symbol. Do not reuse the last 3 passwords.'; ?></p>
          <div id="change-password-modal-result" class="alert" role="alert" style="display:none"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $lang === 'thai' ? 'ยกเลิก' : 'Cancel'; ?></button><button type="submit" class="btn btn-danger" id="change-password-submit"><span><?php echo $lang === 'thai' ? 'บันทึกรหัสผ่านใหม่' : 'Save new password'; ?></span></button></div>
      </form>
    </div>
  </div>
</div>
<style>
.precision-password-modal{border:0;border-radius:16px;box-shadow:0 24px 70px rgba(25,36,52,.24);overflow:hidden}.precision-password-modal .modal-header{align-items:flex-start;padding:22px 24px 16px;border-bottom:1px solid #edf0f4}.precision-password-modal .modal-header small{color:#e63737;font-weight:700;letter-spacing:.04em}.precision-password-modal .modal-title{margin-top:3px;font-weight:700;color:#202a36}.precision-password-modal .modal-body{padding:22px 24px 8px}.precision-password-modal .modal-footer{padding:16px 24px 22px;border-top:0}.precision-password-modal label{font-weight:600;color:#435064}.precision-password-field{position:relative}.precision-password-field .form-control{height:44px;padding-right:46px;border-radius:8px}.precision-password-eye{position:absolute;right:4px;top:50%;transform:translateY(-50%);width:38px;height:36px;display:flex;align-items:center;justify-content:center;padding:0;line-height:1;border:0;border-radius:6px;outline:0;background:transparent;color:#718096;font-size:19px;cursor:pointer}.precision-password-eye i{display:block;line-height:1}.precision-password-eye:focus-visible{box-shadow:0 0 0 2px rgba(230,55,55,.22)}.precision-password-help{display:flex;gap:7px;padding:10px 12px;border-radius:8px;background:#f5f7fa;color:#667386;font-size:12px;line-height:1.45}.precision-password-modal .btn{min-width:110px;border-radius:8px;font-weight:600}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('[data-password-target]').forEach(function(button){button.addEventListener('click',function(){var input=document.querySelector(button.getAttribute('data-password-target'));if(!input)return;var show=input.type==='password';input.type=show?'text':'password';button.querySelector('i').className=show?'mdi mdi-eye-off-outline':'mdi mdi-eye-outline';});});
  var form=document.getElementById('change-password-modal-form');if(!form)return;
  var result=document.getElementById('change-password-modal-result');var submit=document.getElementById('change-password-submit');var original=submit.innerHTML;
  form.addEventListener('submit',function(event){event.preventDefault();result.style.display='none';submit.disabled=true;submit.innerHTML='<i class="fa fa-spinner fa-spin"></i> <?php echo $lang === 'thai' ? 'กำลังบันทึก...' : 'Saving...'; ?>';
    fetch('<?php echo base_url('dashboard/update_password'); ?>',{method:'POST',body:new FormData(form),credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(response){return response.json();}).then(function(data){if(!data.rs)throw new Error(data.msg||'Unable to change password');result.className='alert alert-success';result.textContent=data.msg;result.style.display='block';form.reset();window.setTimeout(function(){if(window.jQuery)window.jQuery('#change-password-modal').modal('hide');result.style.display='none';},1200);}).catch(function(error){result.className='alert alert-danger';result.textContent=error.message;result.style.display='block';}).finally(function(){submit.disabled=false;submit.innerHTML=original;});
  });
  if(window.jQuery){window.jQuery('#change-password-modal').on('hidden.bs.modal',function(){form.reset();result.style.display='none';});}
});
</script>
<?php endif; ?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="modal-searchform" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg precision-search-dialog">
    <div class="modal-content precision-search-modal">
      <div class="modal-header precision-search-head">
        <div><span class="precision-search-eyebrow">QUICK FIND</span><h2><?php echo $lang === 'thai' ? 'ค้นหาหลักสูตร' : 'Find your course'; ?></h2></div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <form enctype="multipart/form-data" autocomplete="off" id="searchform_form" name="searchform_form" method="POST"
        class="form-horizontal p-t-20">
        <div class="modal-body precision-search-body">
          <label class="precision-search-field" for="search_text"><i class="mdi mdi-magnify"></i>
            <input type="search" id="search_text" name="search_text" class="search_text" autocomplete="off"
              placeholder="<?php echo $lang === 'thai' ? 'พิมพ์ชื่อหลักสูตรอย่างน้อย 2 ตัวอักษร' : 'Type at least 2 characters'; ?>" onInput="edValueKeyPress()">
            <span class="precision-search-spinner" aria-hidden="true"></span><kbd>ESC</kbd>
          </label>
          <div class="precision-search-hint"><i class="mdi mdi-flash"></i><?php echo $lang === 'thai' ? 'ค้นหาแบบเรียลไทม์จากหลักสูตรที่คุณมีสิทธิ์เข้าถึง' : 'Live results from courses available to you'; ?></div>
          <div id="div_search" name="div_search" class="precision-search-results" aria-live="polite"></div>
        </div>
        <input type="hidden" id="search_value" name="search_value">
        <div class="modal-footer precision-search-footer">
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
var precisionSearchTimer = null;
var precisionSearchRequest = null;
function edValueKeyPress() {
  var edValue = document.getElementById("search_text");
  var txtval = edValue.value.trim();
  var searchField = document.querySelector('.precision-search-field');
  window.clearTimeout(precisionSearchTimer);
  if (precisionSearchRequest) precisionSearchRequest.abort();
  if (txtval.length < 2) {
    searchField.classList.remove('is-loading');
    $('#div_search').html('<div class="precision-search-state"><i class="mdi mdi-magnify"></i><strong><?php echo $lang === 'thai' ? 'เริ่มค้นหาได้เลย' : 'Start searching'; ?></strong><span><?php echo $lang === 'thai' ? 'พิมพ์อย่างน้อย 2 ตัวอักษรเพื่อค้นหาหลักสูตร' : 'Enter at least 2 characters to find a course'; ?></span></div>');
    return;
  }
  searchField.classList.add('is-loading');
  precisionSearchTimer = window.setTimeout(function () {
  precisionSearchRequest = $.ajax({
    url: "<?= base_url() ?>index.php/course/search_course",
    method: "POST",
    data: {
      txtval_search: txtval
    },
    success: function(data) {
      $('#div_search').html(data || '<div class="precision-search-state"><i class="mdi mdi-magnify-minus"></i><strong><?php echo $lang === 'thai' ? 'ไม่พบหลักสูตร' : 'No courses found'; ?></strong><span><?php echo $lang === 'thai' ? 'ลองใช้คำค้นอื่นหรือชื่อหลักสูตรที่สั้นลง' : 'Try another or shorter search term'; ?></span></div>');
    },
    error: function(_, status) {
      if (status !== 'abort') $('#div_search').html('<div class="precision-search-state"><i class="mdi mdi-alert-circle-outline"></i><strong><?php echo $lang === 'thai' ? 'ค้นหาไม่สำเร็จ' : 'Search unavailable'; ?></strong><span><?php echo $lang === 'thai' ? 'กรุณาลองใหม่อีกครั้ง' : 'Please try again'; ?></span></div>');
    },
    complete: function() {
      searchField.classList.remove('is-loading');
    }
  });
  }, 280);
}

$('#modal-searchform').on('shown.bs.modal', function () {
  var input = document.getElementById('search_text');
  input.focus();
  if (!input.value) edValueKeyPress();
});
document.addEventListener('keydown', function(event) {
  if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
    event.preventDefault();
    $('#modal-searchform').modal('show');
  }
});

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
