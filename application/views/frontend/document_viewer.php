<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$this->load->view('frontend/inc/inc-meta-dashboard.php');

$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$documentUrl = base_url().'uploads/document/'.str_replace('%2F', '/', rawurlencode($path));
$pdfViewerUrl = base_url().'viewdoc/PDF/'.rawurlencode($id).'/'.rawurlencode($type);
$safeTitle = $filname !== '' ? $filname : basename($path);
$isImage = in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'), true);
$isVideo = in_array($extension, array('mp4', 'webm', 'ogg', 'wmv'), true);
$isPdf = $extension === 'pdf';

$viewerText = array(
  'thai' => array(
    'eyebrow' => 'ศูนย์เอกสารการเรียนรู้',
    'back' => 'กลับไปหน้าหลักสูตร',
    'preview' => 'ตัวอย่างเอกสาร',
    'secure' => 'โหมดแสดงผลแบบปลอดภัย',
    'download' => 'ดาวน์โหลดไฟล์',
    'open' => 'เปิดไฟล์ต้นฉบับ',
    'loading' => 'กำลังเตรียมเอกสาร',
    'loading_detail' => 'ระบบกำลังตรวจสอบและจัดเตรียมไฟล์สำหรับการแสดงผล',
    'failed' => 'ไม่สามารถแสดงตัวอย่างไฟล์ได้',
    'failed_detail' => 'ไฟล์อาจถูกย้าย สูญหาย หรือเซิร์ฟเวอร์ส่งข้อมูลที่ไม่ใช่เอกสารกลับมา',
    'retry' => 'ลองใหม่',
    'hint' => 'หากยังเปิดไม่ได้ กรุณาดาวน์โหลดไฟล์เพื่อตรวจสอบจากอุปกรณ์ของคุณ',
    'type' => 'ประเภทไฟล์'
  ),
  'english' => array(
    'eyebrow' => 'Learning document center',
    'back' => 'Back to course',
    'preview' => 'Document preview',
    'secure' => 'Secure viewing mode',
    'download' => 'Download file',
    'open' => 'Open original file',
    'loading' => 'Preparing your document',
    'loading_detail' => 'The file is being verified and prepared for viewing.',
    'failed' => 'The document preview is unavailable',
    'failed_detail' => 'The file may have moved, be missing, or the server returned an invalid document response.',
    'retry' => 'Try again',
    'hint' => 'If the preview still fails, download the file and open it on your device.',
    'type' => 'File type'
  ),
  'japan' => array(
    'eyebrow' => '学習ドキュメントセンター',
    'back' => 'コースに戻る',
    'preview' => 'ドキュメントプレビュー',
    'secure' => 'セキュア表示モード',
    'download' => 'ファイルをダウンロード',
    'open' => '元のファイルを開く',
    'loading' => 'ドキュメントを準備しています',
    'loading_detail' => '表示用ファイルを確認して準備しています。',
    'failed' => 'プレビューを表示できません',
    'failed_detail' => 'ファイルが移動・削除されたか、無効な応答が返されました。',
    'retry' => '再試行',
    'hint' => '表示できない場合は、ファイルをダウンロードしてご確認ください。',
    'type' => 'ファイル形式'
  )
);
$copy = isset($viewerText[$lang]) ? $viewerText[$lang] : $viewerText['english'];
?>
<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/css/document-viewer-premium.css?v=20260721-2">
</head>

<body class="fix-header fix-sidebar card-no-border precision-document-page">
  <div class="preloader">
    <div class="loader">
      <div class="loader__figure"></div>
      <p class="loader__label"><?php echo $lang === 'thai' ? $foote[0]['da_title_th'] : $foote[0]['da_title_en']; ?></p>
    </div>
  </div>

  <div id="main-wrapper">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

    <div class="page-wrapper document-page-wrapper">
      <main class="document-workspace">
        <section class="document-hero" aria-labelledby="document-title">
          <div class="document-hero__identity">
            <a class="document-back" href="<?php echo base_url().htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">
              <i class="mdi mdi-arrow-left"></i>
              <span><?php echo $copy['back']; ?></span>
            </a>
            <div class="document-heading">
              <span class="document-type-icon"><i class="mdi <?php echo $isPdf ? 'mdi-file-pdf-box' : ($isImage ? 'mdi-image-outline' : ($isVideo ? 'mdi-play-circle-outline' : 'mdi-file-document-outline')); ?>"></i></span>
              <div>
                <span class="document-eyebrow"><?php echo $copy['eyebrow']; ?></span>
                <h1 id="document-title"><?php echo htmlspecialchars($safeTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="document-meta">
                  <span><i class="mdi mdi-file-outline"></i><?php echo $copy['type']; ?>: <?php echo strtoupper(htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')); ?></span>
                  <span><i class="mdi mdi-shield-check-outline"></i><?php echo $copy['secure']; ?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="document-hero__actions">
            <a class="document-action document-action--ghost" href="<?php echo htmlspecialchars($documentUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
              <i class="mdi mdi-open-in-new"></i><span><?php echo $copy['open']; ?></span>
            </a>
            <a class="document-action document-action--primary" href="<?php echo htmlspecialchars($documentUrl, ENT_QUOTES, 'UTF-8'); ?>" download="<?php echo htmlspecialchars($safeTitle, ENT_QUOTES, 'UTF-8'); ?>">
              <i class="mdi mdi-download-outline"></i><span><?php echo $copy['download']; ?></span>
            </a>
          </div>
        </section>

        <section class="document-viewer-card">
          <header class="document-viewer-toolbar">
            <div>
              <span class="document-live-dot"></span>
              <strong><?php echo $copy['preview']; ?></strong>
            </div>
            <span class="document-format-pill"><?php echo strtoupper(htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')); ?></span>
          </header>

          <div class="document-stage <?php echo $isImage ? 'is-image' : ($isVideo ? 'is-video' : 'is-document'); ?>" id="documentStage">
            <div class="document-loader" id="documentLoader">
              <span class="document-loader__mark"><i class="mdi mdi-file-eye-outline"></i></span>
              <span class="document-loader__ring"></span>
              <strong><?php echo $copy['loading']; ?></strong>
              <p><?php echo $copy['loading_detail']; ?></p>
            </div>

            <div class="document-error" id="documentError" hidden>
              <div class="document-error__panel">
                <span class="document-error__icon"><i class="mdi mdi-file-alert-outline"></i></span>
                <span class="document-error__eyebrow">ISUZU E-LEARNING</span>
                <h2><?php echo $copy['failed']; ?></h2>
                <p><?php echo $copy['failed_detail']; ?></p>
                <div class="document-error__actions">
                  <button type="button" class="document-action document-action--primary" id="retryViewer"><i class="mdi mdi-refresh"></i><?php echo $copy['retry']; ?></button>
                  <a class="document-action document-action--ghost" href="<?php echo htmlspecialchars($documentUrl, ENT_QUOTES, 'UTF-8'); ?>" download><i class="mdi mdi-download-outline"></i><?php echo $copy['download']; ?></a>
                </div>
                <small><i class="mdi mdi-information-outline"></i><?php echo $copy['hint']; ?></small>
              </div>
            </div>

            <?php if ($isImage) { ?>
              <img class="document-media" id="documentMedia" src="<?php echo htmlspecialchars($documentUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($safeTitle, ENT_QUOTES, 'UTF-8'); ?>">
            <?php } elseif ($isVideo) { ?>
              <video class="document-media document-video" id="documentMedia" controls preload="metadata" src="<?php echo htmlspecialchars($documentUrl, ENT_QUOTES, 'UTF-8'); ?>"></video>
            <?php } elseif ($isPdf) { ?>
              <iframe class="document-frame" id="pdfDocumentFrame" title="<?php echo htmlspecialchars($safeTitle, ENT_QUOTES, 'UTF-8'); ?>" src="<?php echo htmlspecialchars($pdfViewerUrl, ENT_QUOTES, 'UTF-8'); ?>" allowfullscreen></iframe>
            <?php } else { ?>
              <iframe class="document-frame" id="officeDocumentFrame" title="<?php echo htmlspecialchars($safeTitle, ENT_QUOTES, 'UTF-8'); ?>"></iframe>
            <?php } ?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

  <script>
  (function () {
    'use strict';
    var loader = document.getElementById('documentLoader');
    var error = document.getElementById('documentError');
    var stage = document.getElementById('documentStage');
    var retry = document.getElementById('retryViewer');
    var pdfFrame = document.getElementById('pdfDocumentFrame');
    var officeFrame = document.getElementById('officeDocumentFrame');
    var media = document.getElementById('documentMedia');
    var documentUrl = <?php echo json_encode($documentUrl); ?>;
    var extension = <?php echo json_encode($extension); ?>;

    function ready() {
      if (loader) loader.classList.add('is-hidden');
      if (stage) stage.classList.add('is-ready');
    }

    function failed() {
      if (loader) loader.classList.add('is-hidden');
      if (error) error.hidden = false;
      if (stage) stage.classList.add('has-error');
      if (pdfFrame) pdfFrame.classList.add('is-hidden');
      if (officeFrame) officeFrame.classList.add('is-hidden');
    }

    function inspectPdfFrame() {
      try {
        var child = pdfFrame.contentDocument || pdfFrame.contentWindow.document;
        var bodyText = child && child.body ? child.body.innerText : '';
        var errorWrapper = child && child.querySelector ? child.querySelector('#errorWrapper:not(.hidden)') : null;
        if (errorWrapper || /Unexpected server response|An error occurred while loading the PDF/i.test(bodyText)) {
          failed();
          return;
        }
      } catch (e) {
        // The viewer can still be used when its document is isolated by the browser.
      }
      ready();
    }

    if (media) {
      media.addEventListener(extension === 'mp4' || extension === 'webm' || extension === 'ogg' || extension === 'wmv' ? 'loadedmetadata' : 'load', ready);
      media.addEventListener('error', failed);
      if (media.complete) ready();
    }

    if (pdfFrame) {
      pdfFrame.addEventListener('load', function () {
        window.setTimeout(inspectPdfFrame, 900);
      });
      pdfFrame.addEventListener('error', failed);
      window.setTimeout(inspectPdfFrame, 5000);
    }

    if (officeFrame) {
      var encoded = encodeURIComponent(documentUrl);
      var viewerUrl = /iPad|iPhone|iPod/.test(navigator.userAgent)
        ? 'https://docs.google.com/gview?embedded=true&url=' + encoded
        : 'https://view.officeapps.live.com/op/embed.aspx?src=' + encoded;
      officeFrame.src = viewerUrl;
      officeFrame.addEventListener('load', ready);
      officeFrame.addEventListener('error', failed);
      window.setTimeout(ready, 3500);
    }

    if (retry) {
      retry.addEventListener('click', function () {
        error.hidden = true;
        stage.classList.remove('has-error');
        loader.classList.remove('is-hidden');
        if (pdfFrame) {
          pdfFrame.classList.remove('is-hidden');
          pdfFrame.src = pdfFrame.src.split('?')[0] + '?retry=' + Date.now();
        } else if (officeFrame) {
          officeFrame.classList.remove('is-hidden');
          officeFrame.src = officeFrame.src;
        } else if (media) {
          media.src = documentUrl + '?retry=' + Date.now();
        }
      });
    }

    document.addEventListener('contextmenu', function (event) {
      if (stage && stage.contains(event.target)) event.preventDefault();
    });
  }());
  </script>
</body>
</html>
