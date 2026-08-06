(function () {
  'use strict';

  var meta = function (name) {
    var node = document.querySelector('meta[name="' + name + '"]');
    return node ? node.content : '';
  };
  var base = meta('lms-base-url').replace(/\/$/, '');
  var csrfName = meta('lms-csrf-name');
  var csrfToken = meta('lms-csrf-token');
  var isThai = meta('lms-language') === 'thai';

  function updateCsrf(response) {
    var name = response.headers.get('X-CSRF-Name');
    var token = response.headers.get('X-CSRF-Token');
    if (name && token) {
      csrfName = name;
      csrfToken = token;
      var tokenMeta = document.querySelector('meta[name="lms-csrf-token"]');
      if (tokenMeta) tokenMeta.content = token;
    }
  }

  window.lmsSecurePost = function (url, data) {
    var body = new URLSearchParams(data || {});
    if (csrfName && csrfToken) body.set(csrfName, csrfToken);
    return fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      body: body
    }).then(function (response) {
      updateCsrf(response);
      return response.json().then(function (json) {
        if (!response.ok) throw json;
        return json;
      });
    });
  };

  function attachJqueryCsrf() {
    if (!window.jQuery) return;
    window.jQuery(document).ajaxSend(function (_event, xhr, settings) {
      if ((settings.type || 'GET').toUpperCase() !== 'GET' && csrfName && csrfToken) {
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        if (window.FormData && settings.data instanceof window.FormData) {
          if (!settings.data.has(csrfName)) settings.data.append(csrfName, csrfToken);
        } else if (typeof settings.data === 'string' &&
            settings.data.indexOf(encodeURIComponent(csrfName) + '=') < 0) {
          settings.data += (settings.data ? '&' : '') +
            encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfToken);
        }
      }
    }).ajaxComplete(function (_event, xhr) {
      var name = xhr.getResponseHeader('X-CSRF-Name');
      var token = xhr.getResponseHeader('X-CSRF-Token');
      if (name && token) {
        csrfName = name;
        csrfToken = token;
      }
    });
  }

  function escapeHtml(value) {
    var node = document.createElement('span');
    node.textContent = value == null ? '' : String(value);
    return node.innerHTML;
  }

  function render(data) {
    var menu = document.querySelector('.precision-notification-menu .precision-command-popover');
    var badge = document.getElementById('lms-notification-count');
    if (!menu || !badge) return;
    badge.textContent = data.unread;
    badge.hidden = !Number(data.unread);
    var items = data.items.map(function (item) {
      var url = item.url || '#';
      if (url !== '#' && !/^(?:https?:)?\/\//i.test(url) && url.charAt(0) !== '/') url = base + '/' + url;
      return '<a href="' + escapeHtml(url) + '" data-notification-id="' +
        Number(item.noti_id) + '" class="' + (Number(item.is_read) ? '' : 'is-unread') +
        '"><i class="mdi mdi-bell-outline"></i><span><strong>' +
        escapeHtml(item.title) + '</strong><small>' +
        escapeHtml(item.message || item.created_at) + '</small></span></a>';
    }).join('');
    menu.innerHTML =
      '<div class="precision-popover-head"><strong>' +
      (isThai ? 'การแจ้งเตือน' : 'Notifications') + '</strong><small>' +
      Number(data.unread) + ' ' + (isThai ? 'รายการใหม่' : 'new') + '</small></div>' +
      '<div class="lms-notification-items">' +
      (items || '<span class="precision-notification-empty">' +
        (isThai ? 'ไม่มีการแจ้งเตือนใหม่' : 'No new notifications') + '</span>') +
      '</div><button type="button" class="precision-notification-read-all" id="lms-notification-read-all">' +
      (isThai ? 'อ่านทั้งหมดแล้ว' : 'Mark all as read') + '</button>';
  }

  function load() {
    if (!document.getElementById('lms-notification-button')) return;
    fetch(base + '/notifications?limit=8', {
      credentials: 'same-origin',
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    }).then(function (response) {
      updateCsrf(response);
      if (!response.ok) throw new Error('notification request failed');
      return response.json();
    }).then(render).catch(function () {});
  }

  document.addEventListener('click', function (event) {
    var item = event.target.closest('[data-notification-id]');
    if (item) window.lmsSecurePost(base + '/notifications/read/' + item.dataset.notificationId);
    if (event.target.closest('#lms-notification-read-all')) {
      window.lmsSecurePost(base + '/notifications/read_all').then(load);
    }
  });

  attachJqueryCsrf();
  document.addEventListener('DOMContentLoaded', load);
}());
