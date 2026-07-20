<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/css/faq-page.css?v=20260720-1" rel="stylesheet">
  </head>
  <body class="fix-header fix-sidebar card-no-border">

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css  class="fix-header card-no-border fix-sidebar" -->
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
        <?php if (!empty($emp_c)){ $this->load->view('frontend/inc/inc-sidemenu.php'); } ?>
        <div class="page-wrapper faq-page">
          <div class="container-fluid faq-container">
            <header class="faq-hero">
              <div class="faq-hero-copy">
                <span class="faq-eyebrow">ISUZU E-LEARNING SUPPORT</span>
                <h1><?php echo $lang == 'thai' ? 'คำถามที่พบบ่อย' : ($lang == 'japan' ? 'よくあるご質問' : 'Frequently asked questions'); ?></h1>
                <p><?php echo $lang == 'thai' ? 'ค้นหาคำตอบเกี่ยวกับการเข้าใช้งาน การเรียน และการใช้งานระบบ' : ($lang == 'japan' ? 'ログイン、学習、システム利用に関する回答をご確認いただけます' : 'Find answers about access, learning, and using the system'); ?></p>
              </div>
              <div class="faq-search-wrap">
                <i class="mdi mdi-magnify" aria-hidden="true"></i>
                <label class="sr-only" for="faq-search"><?php echo $lang == 'thai' ? 'ค้นหาคำถาม' : 'Search questions'; ?></label>
                <input type="search" id="faq-search" class="faq-search" autocomplete="off"
                  placeholder="<?php echo $lang == 'thai' ? 'ค้นหาคำถาม เช่น รหัสผ่าน, ใบประกาศ...' : ($lang == 'japan' ? 'キーワードで質問を検索...' : 'Search questions, e.g. password, certificate...'); ?>">
                <button type="button" class="faq-search-clear" aria-label="Clear search"><i class="mdi mdi-close"></i></button>
              </div>
            </header>

            <div class="faq-groups" id="faq-groups">
              <?php foreach ($faq as $key) {
                if ($key['lang'] == $lang) {
                  $question_count = 0;
                  foreach ($faq_detail as $count_detail) {
                    if ($key['id'] == $count_detail['tid']) { $question_count++; }
                  }
              ?>
                <section class="faq-group" data-faq-group>
                  <div class="faq-group-heading">
                    <div class="faq-group-icon" aria-hidden="true"><i class="mdi mdi-help-circle-outline"></i></div>
                    <div>
                      <h2><?php echo $key['title']; ?></h2>
                      <span><?php echo $question_count; ?> <?php echo $lang == 'thai' ? 'คำถาม' : ($lang == 'japan' ? '件の質問' : ($question_count == 1 ? 'question' : 'questions')); ?></span>
                    </div>
                  </div>

                  <div class="faq-accordion" id="faq-accordion-<?php echo $key['id']; ?>">
                    <?php $num = 1;
                      foreach ($faq_detail as $key_detail) {
                        if ($key['id'] == $key_detail['tid']) {
                          $item_id = 'faq-' . $key['id'] . '-' . $num;
                    ?>
                      <article class="faq-item" data-faq-item>
                        <h3 class="faq-question" id="heading-<?php echo $item_id; ?>">
                          <button type="button" class="faq-question-button collapsed" data-toggle="collapse"
                            data-target="#<?php echo $item_id; ?>" aria-expanded="false" aria-controls="<?php echo $item_id; ?>">
                            <span class="faq-question-number">Q<?php echo $num; ?></span>
                            <span class="faq-question-text"><?php echo $key_detail['question']; ?></span>
                            <i class="mdi mdi-chevron-down faq-chevron" aria-hidden="true"></i>
                          </button>
                        </h3>
                        <div id="<?php echo $item_id; ?>" class="collapse" aria-labelledby="heading-<?php echo $item_id; ?>"
                          data-parent="#faq-accordion-<?php echo $key['id']; ?>">
                          <div class="faq-answer"><?php echo $key_detail['answer']; ?></div>
                        </div>
                      </article>
                    <?php $num++; }
                      } ?>
                  </div>
                </section>
              <?php }
                } ?>
            </div>

            <div class="faq-empty" id="faq-empty" role="status" aria-live="polite">
              <i class="mdi mdi-magnify" aria-hidden="true"></i>
              <h2><?php echo $lang == 'thai' ? 'ไม่พบคำถามที่ค้นหา' : 'No matching questions'; ?></h2>
              <p><?php echo $lang == 'thai' ? 'ลองใช้คำค้นที่สั้นลงหรือเปลี่ยนคำค้นใหม่' : 'Try a shorter or different search term'; ?></p>
            </div>
          </div>

        </div>
    </div>
    
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

    (function () {
      var $search = $('#faq-search');
      var $clear = $('.faq-search-clear');
      var $empty = $('#faq-empty');

      function filterFaq() {
        var query = $.trim($search.val()).toLocaleLowerCase();
        var visibleItems = 0;

        $('[data-faq-group]').each(function () {
          var groupVisible = 0;
          $(this).find('[data-faq-item]').each(function () {
            var isMatch = !query || $(this).text().toLocaleLowerCase().indexOf(query) !== -1;
            $(this).toggle(isMatch);
            if (isMatch) { groupVisible++; visibleItems++; }
          });
          $(this).toggle(groupVisible > 0);
        });

        $clear.toggle(query.length > 0);
        $empty.toggle(visibleItems === 0);
      }

      $search.on('input', filterFaq);
      $clear.on('click', function () {
        $search.val('').focus();
        filterFaq();
      });
      filterFaq();
    })();
    </script>
  </body>
</html>
