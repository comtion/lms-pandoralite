(function (window, document) {
  'use strict';

  var selector = [
    'select:not([multiple])',
    ':not([size])',
    ':not(.select2)',
    ':not(.select2-hidden-accessible)',
    ':not(.selectpicker)',
    ':not(.multiselect)',
    ':not(.multi-select)',
    ':not([data-native-select])'
  ].join('');
  var observerTimer = null;

  function enhanceSelects(root) {
    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) return;

    var $ = window.jQuery;
    var $selects = $(root || document).find(selector);

    if (root && root.matches && root.matches(selector)) {
      $selects = $selects.add(root);
    }

    $selects.each(function () {
      var $select = $(this);
      if ($select.data('precisionSelectReady')) return;

      var $modal = $select.closest('.modal');
      var $modalDialog = $select.closest('.modal-dialog');
      var optionCount = $select.children('option').length;
      var settings = {
        width: '100%',
        minimumResultsForSearch: optionCount > 8 ? 0 : Infinity
      };

      if ($modalDialog.length) {
        settings.dropdownParent = $modalDialog;
      } else if ($modal.length) {
        settings.dropdownParent = $modal;
      }

      $select
        .data('precisionSelectReady', true)
        .addClass('precision-native-select')
        .select2(settings);
    });
  }

  function scheduleEnhancement(root) {
    window.clearTimeout(observerTimer);
    observerTimer = window.setTimeout(function () {
      enhanceSelects(root || document);
    }, 30);
  }

  function start() {
    enhanceSelects(document);

    if (window.jQuery) {
      window.jQuery(document).on('shown.bs.modal shown.bs.collapse', function (event) {
        enhanceSelects(event.target);
      });
    }

    if (window.MutationObserver) {
      new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
          if (mutations[i].addedNodes.length) {
            scheduleEnhancement(document);
            break;
          }
        }
      }).observe(document.body, { childList: true, subtree: true });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})(window, document);
