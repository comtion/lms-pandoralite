(function () {
  var loaderTimer = null;

  function ensurePageLoader() {
    var existing = document.getElementById("lmsPageLoader");
    if (existing) {
      return existing;
    }

    var loader = document.createElement("div");
    loader.id = "lmsPageLoader";
    loader.className = "lms-page-loader";
    loader.hidden = true;
    loader.setAttribute("role", "status");
    loader.setAttribute("aria-live", "polite");
    loader.innerHTML = [
      '<div class="lms-page-loader__panel">',
      '<div class="lms-page-loader__mark" aria-hidden="true">',
      '<span class="lms-page-loader__ring"></span>',
      '<span class="lms-page-loader__plus"></span>',
      "</div>",
      '<span class="lms-page-loader__pulse" aria-hidden="true"></span>',
      '<strong class="lms-page-loader__brand">Vajira LMS</strong>',
      '<span class="lms-page-loader__label">กำลังประมวลผลข้อมูล...</span>',
      "</div>",
    ].join("");
    document.body.appendChild(loader);
    return loader;
  }

  function showPageLoader(message) {
    window.clearTimeout(loaderTimer);
    loaderTimer = window.setTimeout(function () {
      var loader = ensurePageLoader();
      loader.querySelector(".lms-page-loader__label").textContent = message || "กำลังประมวลผลข้อมูล...";
      loader.hidden = false;
      document.documentElement.classList.add("lms-loading");
    }, 80);
  }

  function hidePageLoader() {
    window.clearTimeout(loaderTimer);
    var loader = document.getElementById("lmsPageLoader");
    if (loader) {
      loader.hidden = true;
    }
    document.documentElement.classList.remove("lms-loading");
  }

  function shouldSkipLinkLoading(link, event) {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
      return true;
    }

    if (
      link.hasAttribute("download") ||
      link.matches("[data-no-loading], [data-bs-toggle], [data-enterprise-close], [data-profile-close], [data-close-modal], [data-close-profile-modal]")
    ) {
      return true;
    }

    var target = (link.getAttribute("target") || "").toLowerCase();
    if (target && target !== "_self") {
      return true;
    }

    var href = link.getAttribute("href") || "";
    if (!href || href === "#" || href.indexOf("javascript:") === 0 || href.indexOf("mailto:") === 0 || href.indexOf("tel:") === 0) {
      return true;
    }

    var url;
    try {
      url = new URL(link.href, window.location.href);
    } catch (error) {
      return true;
    }

    if (url.origin !== window.location.origin) {
      return true;
    }

    return url.pathname === window.location.pathname && url.search === window.location.search && Boolean(url.hash);
  }

  window.LmsLoading = {
    show: showPageLoader,
    hide: hidePageLoader,
  };

  function actionMessage(path) {
    var isThai = (document.documentElement.lang || "").toLowerCase().startsWith("th");
    if (path.indexOf("dashboard/unlockAcc") !== -1) {
      return {
        title: isThai ? "ปลดล็อกผู้ใช้" : "Unlock User",
        message: isThai
          ? "คำสั่งนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดปลดล็อกในรายการผู้ใช้"
          : "This action needs a selected user first. Go to User Information and use the unlock command on a user row.",
      };
    }

    if (path.indexOf("dashboard/resetPass") !== -1) {
      return {
        title: isThai ? "ตั้งรหัสผ่านใหม่" : "Reset Password",
        message: isThai
          ? "คำสั่งนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดตั้งรหัสผ่านใหม่ในรายการผู้ใช้"
          : "This action needs a selected user first. Go to User Information and use the reset password command on a user row.",
      };
    }

    return null;
  }

  function ensureModal() {
    var existing = document.getElementById("enterpriseActionModal");
    if (existing) {
      return existing;
    }

    var modal = document.createElement("div");
    modal.id = "enterpriseActionModal";
    modal.className = "enterprise-action-modal";
    modal.hidden = true;
    modal.innerHTML = [
      '<div class="enterprise-action-backdrop" data-enterprise-close></div>',
      '<div class="enterprise-action-panel" role="dialog" aria-modal="true" aria-labelledby="enterpriseActionTitle">',
      '<h2 id="enterpriseActionTitle"></h2>',
      '<p id="enterpriseActionMessage"></p>',
      '<div class="enterprise-action-buttons">',
      '<button type="button" class="btn" data-enterprise-close>Close</button>',
      '<a class="btn primary" id="enterpriseActionCta" href="#">User Information</a>',
      "</div>",
      "</div>",
    ].join("");
    document.body.appendChild(modal);

    modal.querySelectorAll("[data-enterprise-close]").forEach(function (node) {
      node.addEventListener("click", function () {
        modal.hidden = true;
      });
    });

    return modal;
  }

  function ensureProfileModal() {
    var existing = document.getElementById("enterpriseProfileModal");
    if (existing) {
      return existing;
    }

    var modal = document.createElement("div");
    modal.id = "enterpriseProfileModal";
    modal.className = "enterprise-profile-modal";
    modal.hidden = true;
    modal.innerHTML = [
      '<div class="enterprise-profile-backdrop" data-profile-close></div>',
      '<section class="enterprise-profile-panel" role="dialog" aria-modal="true" aria-label="Edit profile">',
      '<button class="enterprise-profile-close" type="button" data-profile-close aria-label="Close">×</button>',
      '<iframe title="Edit profile" id="enterpriseProfileFrame"></iframe>',
      "</section>",
    ].join("");
    document.body.appendChild(modal);

    modal.querySelectorAll("[data-profile-close]").forEach(function (node) {
      node.addEventListener("click", function () {
        modal.hidden = true;
        modal.querySelector("iframe").src = "about:blank";
      });
    });

    return modal;
  }

  function openProfileModal(url) {
    var modal = ensureProfileModal();
    var iframe = modal.querySelector("iframe");
    var separator = url.indexOf("?") === -1 ? "?" : "&";
    iframe.src = url + separator + "modal=1";
    modal.hidden = false;
    modal.querySelector(".enterprise-profile-close").focus();
  }

  document.addEventListener("click", function (event) {
    var link = event.target.closest && event.target.closest("a[href]");
    if (!link || event.defaultPrevented) {
      return;
    }

    var href = link.getAttribute("href") || "";
    if ((href.indexOf("dashboard/profile/setting") !== -1 || href.match(/dashboard\/profile\/?$/)) && !link.closest("#dashboardProfileModal")) {
      event.preventDefault();
      openProfileModal(link.href);
      return;
    }

    var info = actionMessage(href);
    if (!info) {
      return;
    }

    event.preventDefault();
    var modal = ensureModal();
    modal.querySelector("#enterpriseActionTitle").textContent = info.title;
    modal.querySelector("#enterpriseActionMessage").textContent = info.message;
    modal.querySelector("#enterpriseActionCta").href = (window.location.origin || "") + "/manage/userdata";
    modal.hidden = false;
    modal.querySelector("#enterpriseActionCta").focus();
  });

  document.addEventListener("click", function (event) {
    var link = event.target.closest && event.target.closest("a[href]");
    if (!link || shouldSkipLinkLoading(link, event)) {
      return;
    }

    window.setTimeout(function () {
      if (!event.defaultPrevented) {
        showPageLoader(link.dataset.loadingText || "กำลังโหลดข้อมูล...");
      }
    }, 0);
  });

  document.addEventListener("submit", function (event) {
    var form = event.target;
    if (
      event.defaultPrevented ||
      !form ||
      form.matches("[data-no-loading]") ||
      ((form.getAttribute("target") || "").toLowerCase() && (form.getAttribute("target") || "").toLowerCase() !== "_self")
    ) {
      return;
    }

    showPageLoader(form.dataset.loadingText || "กำลังประมวลผลข้อมูล...");
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      var modal = document.getElementById("enterpriseActionModal");
      if (modal) {
        modal.hidden = true;
      }
      var profileModal = document.getElementById("enterpriseProfileModal");
      if (profileModal) {
        profileModal.hidden = true;
      }
    }
  });

  window.addEventListener("message", function (event) {
    if (!event.data || (event.data.type !== "profileSaved" && event.data.type !== "profileModalClose")) {
      return;
    }

    var modal = document.getElementById("enterpriseProfileModal");
    if (modal) {
      modal.hidden = true;
      modal.querySelector("iframe").src = "about:blank";
    }

    if (event.data.type === "profileSaved") {
      window.location.reload();
    }
  });

  window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
      hidePageLoader();
    }
  });
})();
