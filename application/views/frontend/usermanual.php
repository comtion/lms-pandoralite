<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$this->load->view('frontend/inc/inc-meta-dashboard.php');

$manual_url = $path;
$manual_path = parse_url($manual_url, PHP_URL_PATH);
$extension = strtolower(pathinfo($manual_path, PATHINFO_EXTENSION));
$manual_name = basename($manual_path);
$is_image = in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'webp'));
$is_video = in_array($extension, array('mp4', 'wmv', 'webm'));
$is_pdf = $extension === 'pdf';
?>
<link href="<?php echo REAL_PATH; ?>/assets/css/user-manual.css?v=20260720-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border">
  <div class="preloader">
    <div class="loader">
      <div class="loader__figure"></div>
      <p class="loader__label"><?php if ($lang == "thai") { echo $foote[0]['da_title_th']; } else { echo $foote[0]['da_title_en']; } ?></p>
    </div>
  </div>

  <div id="main-wrapper">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

    <div class="page-wrapper manual-page">
      <div class="container-fluid manual-container">
        <header class="manual-hero">
          <div class="manual-heading">
            <div class="manual-heading-icon" aria-hidden="true"><i class="mdi mdi-book-open-page-variant"></i></div>
            <div>
              <span class="manual-eyebrow">ISUZU E-LEARNING SUPPORT</span>
              <h1><?php echo label('user_manual'); ?></h1>
              <p><?php
                echo $lang == 'thai'
                  ? 'เปิดอ่านหรือดาวน์โหลดคู่มือการใช้งานระบบสำหรับบัญชีของคุณ'
                  : ($lang == 'japan'
                    ? 'アカウント用のシステム利用マニュアルを表示またはダウンロードできます'
                    : 'View or download the system guide prepared for your account');
              ?></p>
            </div>
          </div>

          <div class="manual-actions">
            <a class="manual-button manual-button-secondary" href="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
              <i class="mdi mdi-open-in-new" aria-hidden="true"></i>
              <span><?php echo $lang == 'thai' ? 'เปิดในแท็บใหม่' : ($lang == 'japan' ? '新しいタブで開く' : 'Open in new tab'); ?></span>
            </a>
            <a class="manual-button manual-button-primary" href="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>" download>
              <i class="mdi mdi-download" aria-hidden="true"></i>
              <span><?php echo label('download_file'); ?></span>
            </a>
          </div>
        </header>

        <main class="manual-card">
          <div class="manual-card-toolbar">
            <div class="manual-file-info">
              <span class="manual-file-icon" aria-hidden="true"><i class="mdi mdi-file-document"></i></span>
              <div>
                <strong><?php echo htmlspecialchars($manual_name, ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo strtoupper($extension ?: 'FILE'); ?> · <?php echo $lang == 'thai' ? 'คู่มือการใช้งานระบบ' : 'System user guide'; ?></span>
              </div>
            </div>
            <span class="manual-status" id="manual-status">
              <i class="mdi mdi-circle" aria-hidden="true"></i>
              <span><?php echo $lang == 'thai' ? 'กำลังตรวจสอบไฟล์' : 'Checking file'; ?></span>
            </span>
          </div>

          <div class="manual-viewer" id="manual-viewer">
            <div class="manual-loading" id="manual-loading" role="status" aria-live="polite">
              <span class="manual-spinner" aria-hidden="true"></span>
              <strong><?php echo $lang == 'thai' ? 'กำลังเตรียมคู่มือ' : 'Preparing your guide'; ?></strong>
              <p><?php echo $lang == 'thai' ? 'กรุณารอสักครู่ ระบบกำลังตรวจสอบเอกสาร' : 'Please wait while the document is checked'; ?></p>
            </div>

            <div class="manual-error" id="manual-error" role="alert">
              <span class="manual-error-icon" aria-hidden="true"><i class="mdi mdi-alert-circle-outline"></i></span>
              <h2><?php echo $lang == 'thai' ? 'ไม่สามารถแสดงตัวอย่างเอกสารได้' : 'The document preview is unavailable'; ?></h2>
              <p><?php echo $lang == 'thai' ? 'ไฟล์อาจถูกย้ายหรือยังไม่พร้อมใช้งาน คุณยังสามารถลองเปิดหรือดาวน์โหลดไฟล์โดยตรงได้' : 'The file may have moved or is not ready. You can still try opening or downloading it directly.'; ?></p>
              <div class="manual-error-actions">
                <button type="button" class="manual-button manual-button-secondary" id="manual-retry"><i class="mdi mdi-refresh"></i> <?php echo $lang == 'thai' ? 'ลองใหม่' : 'Try again'; ?></button>
                <a class="manual-button manual-button-primary" href="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>" download><i class="mdi mdi-download"></i> <?php echo label('download_file'); ?></a>
              </div>
            </div>

            <?php if ($is_image) { ?>
              <img class="manual-media manual-image" id="manual-content" data-src="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars(label('user_manual'), ENT_QUOTES, 'UTF-8'); ?>">
            <?php } else if ($is_video) { ?>
              <video class="manual-media manual-video" id="manual-content" data-src="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>" controls preload="metadata"></video>
            <?php } else if ($is_pdf) { ?>
              <iframe class="manual-media manual-pdf" id="manual-content" data-src="<?php echo htmlspecialchars($manual_url, ENT_QUOTES, 'UTF-8'); ?>#toolbar=1&navpanes=0&view=FitH" title="<?php echo htmlspecialchars(label('user_manual'), ENT_QUOTES, 'UTF-8'); ?>"></iframe>
            <?php } else { ?>
              <div class="manual-unsupported" id="manual-content">
                <i class="mdi mdi-file-document-box" aria-hidden="true"></i>
                <h2><?php echo $lang == 'thai' ? 'ไฟล์นี้ไม่รองรับการแสดงตัวอย่าง' : 'Preview is not supported for this file'; ?></h2>
                <p><?php echo $lang == 'thai' ? 'ดาวน์โหลดไฟล์เพื่อเปิดด้วยโปรแกรมบนอุปกรณ์ของคุณ' : 'Download the file to open it with an application on your device'; ?></p>
              </div>
            <?php } ?>
          </div>
        </main>
      </div>
    </div>
  </div>

  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

  <script>
  (function () {
    var fileUrl = <?php echo json_encode($manual_url, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
    var previewSupported = <?php echo ($is_image || $is_video || $is_pdf) ? 'true' : 'false'; ?>;
    var content = document.getElementById('manual-content');
    var loading = document.getElementById('manual-loading');
    var error = document.getElementById('manual-error');
    var status = document.getElementById('manual-status');
    var retry = document.getElementById('manual-retry');
    var loadTimer;

    function setStatus(type, text) {
      status.className = 'manual-status manual-status-' + type;
      status.querySelector('span').textContent = text;
    }

    function showError() {
      clearTimeout(loadTimer);
      loading.style.display = 'none';
      if (content) { content.style.display = 'none'; }
      error.style.display = 'flex';
      setStatus('error', <?php echo json_encode($lang == 'thai' ? 'ไม่พร้อมใช้งาน' : 'Unavailable', JSON_UNESCAPED_UNICODE); ?>);
    }

    function showContent() {
      clearTimeout(loadTimer);
      loading.style.display = 'none';
      error.style.display = 'none';
      if (content) { content.style.display = ''; }
      setStatus('ready', <?php echo json_encode($lang == 'thai' ? 'พร้อมอ่าน' : 'Ready to read', JSON_UNESCAPED_UNICODE); ?>);
    }

    function loadPreview() {
      error.style.display = 'none';
      loading.style.display = 'flex';
      setStatus('loading', <?php echo json_encode($lang == 'thai' ? 'กำลังตรวจสอบไฟล์' : 'Checking file', JSON_UNESCAPED_UNICODE); ?>);

      if (!previewSupported || !content || !content.getAttribute('data-src')) {
        loading.style.display = 'none';
        if (content) { content.style.display = 'flex'; }
        setStatus('ready', <?php echo json_encode($lang == 'thai' ? 'ดาวน์โหลดเพื่อเปิดไฟล์' : 'Download to open', JSON_UNESCAPED_UNICODE); ?>);
        return;
      }

      fetch(fileUrl, { method: 'HEAD', credentials: 'same-origin', cache: 'no-store' })
        .then(function (response) {
          if (!response.ok) { throw new Error('File unavailable'); }
          content.onload = showContent;
          content.onerror = showError;
          content.setAttribute('src', content.getAttribute('data-src'));
          loadTimer = setTimeout(function () {
            if (loading.style.display !== 'none') { showError(); }
          }, 12000);
        })
        .catch(showError);
    }

    retry.addEventListener('click', loadPreview);
    loadPreview();
  })();
  </script>
</body>
</html>
