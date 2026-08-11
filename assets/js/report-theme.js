(function () {
  'use strict';

  function createIcon(icon) {
    var span = document.createElement('span');
    span.className = 'report-hero-icon';
    span.innerHTML = '<i class="mdi ' + icon + '" aria-hidden="true"></i>';
    return span;
  }

  function iconForPath(path) {
    if (path.indexOf('logImportUsers') !== -1) return 'mdi-import';
    if (path.indexOf('log') !== -1) return 'mdi-history';
    if (path.indexOf('audit') !== -1) return 'mdi-shield';
    if (path.indexOf('survey') !== -1) return 'mdi-clipboard-text';
    if (path.indexOf('student') !== -1 || path.indexOf('learner') !== -1) return 'mdi-account-multiple';
    if (path.indexOf('course') !== -1) return 'mdi-book-open';
    if (path.indexOf('company') !== -1) return 'mdi-domain';
    return 'mdi-chart-bar';
  }

  function sectionHead(title, description) {
    var head = document.createElement('div');
    head.className = 'report-section-head report-section-head--generated';
    head.innerHTML = '<div><span class="report-section-icon"><i class="mdi mdi-filter-variant"></i></span>' +
      '<div><h2>' + title + '</h2><p>' + description + '</p></div></div>' +
      '<span class="report-data-badge"><i class="mdi mdi-circle"></i> ข้อมูลระบบ</span>';
    return head;
  }

  function resultHead() {
    var head = document.createElement('div');
    head.className = 'report-table-title report-table-title--generated';
    head.innerHTML = '<div><h2>รายการข้อมูลรายงาน</h2><p>ผลลัพธ์จะแสดงตามตัวกรองที่เลือก</p></div>';
    return head;
  }

  function enhance() {
    var body = document.body;
    if (!body || !body.classList.contains('report-theme-page') || body.classList.contains('email-log-report-page')) return;

    var themeLink = document.querySelector('link[href*="report-theme.css"]');
    if (themeLink && themeLink.href.indexOf('v=20260811-4') === -1) {
      themeLink.href = themeLink.href.replace(/([?&])v=[^&]*/, '$1v=20260811-4');
    }

    var container = document.querySelector('.page-wrapper .container-fluid');
    if (!container) return;

    var titleRows = container.querySelectorAll(':scope > .page-titles');
    var hero = titleRows.length ? titleRows[0] : null;
    if (hero && !hero.classList.contains('report-hero')) {
      hero.classList.add('report-hero');
      var titleColumn = hero.querySelector(':scope > [class*="col-"]:first-child');
      var breadcrumbColumn = hero.querySelector(':scope > [class*="col-"]:last-child');
      if (titleColumn) titleColumn.classList.add('report-hero-copy');
      if (breadcrumbColumn && breadcrumbColumn !== titleColumn) breadcrumbColumn.classList.add('report-hero-crumbs');
      var originalTitle = titleColumn ? titleColumn.querySelector('b, h1') : null;
      if (titleColumn && originalTitle) {
        var titleText = originalTitle.textContent.trim();
        var wrap = document.createElement('div');
        wrap.className = 'report-hero-title';
        var copy = document.createElement('div');
        copy.innerHTML = '<span class="report-kicker">รายงาน</span><h1></h1><p>ตรวจสอบและวิเคราะห์ข้อมูลจากระบบ</p>';
        copy.querySelector('h1').textContent = titleText;
        wrap.appendChild(createIcon(iconForPath(window.location.pathname)));
        wrap.appendChild(copy);
        originalTitle.replaceWith(wrap);
      }
    }

    var contentRow = titleRows.length > 1 ? titleRows[1] : null;
    if (!contentRow) return;
    contentRow.classList.add('report-content-row');
    var card = contentRow.querySelector('.card');
    if (!card) return;
    card.classList.add('report-workspace');
    var cardBody = card.querySelector(':scope > .card-body') || card.querySelector('.card-body');
    if (!cardBody) return;

    if (!cardBody.querySelector('.report-section-head')) {
      var filter = cardBody.querySelector('form.form-horizontal, form[id*="search"], :scope > .row');
      if (filter) {
        filter.classList.add('report-generated-filter');
        cardBody.insertBefore(sectionHead('ตัวกรองรายงาน', 'เลือกเงื่อนไขที่ต้องการเพื่อค้นหาข้อมูล'), filter);
      }
    }

    var tableWrap = cardBody.querySelector('.table-responsive');
    if (tableWrap && !tableWrap.classList.contains('report-data-table')) {
      tableWrap.classList.add('report-data-table');
      if (!tableWrap.previousElementSibling || !tableWrap.previousElementSibling.classList.contains('report-table-title')) {
        tableWrap.parentNode.insertBefore(resultHead(), tableWrap);
      }
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', enhance);
  else enhance();
})();
