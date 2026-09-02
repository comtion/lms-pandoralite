(function () {
  "use strict";

  function formatBytes(bytes) {
    if (!Number.isFinite(bytes) || bytes <= 0) return "";
    var units = ["B", "KB", "MB", "GB"];
    var index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
    return (bytes / Math.pow(1024, index)).toFixed(index ? 1 : 0) + " " + units[index];
  }

  function addFileSummary(input) {
    if (!input || input.dataset.lessonSummaryReady === "true") return;
    input.dataset.lessonSummaryReady = "true";

    var summary = document.createElement("div");
    summary.className = "lesson-file-summary";
    summary.setAttribute("aria-live", "polite");
    summary.innerHTML = '<i class="mdi mdi-file-check-outline" aria-hidden="true"></i><div><strong></strong><small></small></div>';
    input.insertAdjacentElement("afterend", summary);

    input.addEventListener("change", function () {
      var file = input.files && input.files[0];
      var oldPreview = summary.querySelector("img");
      if (oldPreview) oldPreview.remove();

      if (!file) {
        summary.classList.remove("is-visible");
        return;
      }

      summary.querySelector("strong").textContent = file.name;
      summary.querySelector("small").textContent = [formatBytes(file.size), file.type || "File"].filter(Boolean).join(" • ");
      summary.classList.add("is-visible");

      if (file.type.indexOf("image/") === 0) {
        var preview = document.createElement("img");
        preview.alt = "";
        var previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.addEventListener("load", function () { URL.revokeObjectURL(previewUrl); }, { once: true });
        summary.appendChild(preview);
      }
    });
  }

  function formatDuration(seconds) {
    if (!Number.isFinite(seconds)) return "—";
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var remainingSeconds = Math.floor(seconds % 60);
    return (hours ? String(hours).padStart(2, "0") + ":" : "") + String(minutes).padStart(2, "0") + ":" + String(remainingSeconds).padStart(2, "0");
  }

  function createVideoDialog() {
    var dialog = document.getElementById("lessonVideoDialog");
    if (dialog) return dialog;

    dialog = document.createElement("dialog");
    dialog.id = "lessonVideoDialog";
    dialog.className = "lesson-video-dialog";
    dialog.setAttribute("closedby", "any");
    dialog.setAttribute("aria-labelledby", "lessonVideoDialogTitle");
    dialog.innerHTML = '<header class="lesson-video-dialog-head"><span class="lesson-video-dialog-icon" aria-hidden="true"><i class="mdi mdi-youtube-play"></i></span><div class="lesson-video-dialog-copy"><span>VIDEO PREVIEW</span><strong id="lessonVideoDialogTitle">ดูตัวอย่างวิดีโอ</strong></div><button type="button" class="lesson-video-dialog-close" aria-label="ปิดหน้าต่างตัวอย่างวิดีโอ"><i class="mdi mdi-window-close" aria-hidden="true"></i></button></header><div class="lesson-video-dialog-stage"><video controls playsinline preload="metadata"></video><p class="lesson-video-dialog-status" aria-live="polite">กำลังโหลดวิดีโอ…</p></div>';
    document.body.appendChild(dialog);

    var video = dialog.querySelector("video");
    var status = dialog.querySelector(".lesson-video-dialog-status");
    function closeDialog() {
      video.pause();
      video.removeAttribute("src");
      video.load();
      dialog.close();
    }
    dialog.querySelector(".lesson-video-dialog-close").addEventListener("click", closeDialog);
    dialog.addEventListener("cancel", function (event) {
      event.preventDefault();
      closeDialog();
    });
    dialog.addEventListener("click", function (event) {
      if (event.target === dialog) closeDialog();
    });
    video.addEventListener("loadedmetadata", function () {
      status.hidden = true;
    });
    video.addEventListener("error", function () {
      status.hidden = false;
      status.textContent = "ไม่สามารถโหลดวิดีโอนี้ได้ กรุณาตรวจสอบว่าไฟล์ยังอยู่บนระบบ";
      status.classList.add("is-error");
    });
    return dialog;
  }

  function initMediaSourcePicker() {
    var select = document.getElementById("type_media");
    var picker = document.getElementById("lessonMediaSourcePicker");
    if (!select || !picker) return function () {};

    var radios = Array.prototype.slice.call(picker.querySelectorAll('input[type="radio"]'));
    function hideDuplicateSelectUi() {
      select.hidden = true;
      select.style.setProperty("display", "none", "important");
      var generatedSelectUi = select.parentElement && select.parentElement.querySelectorAll(
        ".select2-container, .bootstrap-select, .selectize-control, .chosen-container"
      );
      Array.prototype.forEach.call(generatedSelectUi || [], function (element) {
        element.classList.add("lesson-media-source-native-ui");
        element.setAttribute("aria-hidden", "true");
      });
    }

    function syncPickerFromSelect() {
      hideDuplicateSelectUi();
      var selectedValue = String(select.value);
      radios.forEach(function (radio) {
        radio.checked = radio.value === selectedValue;
      });
      picker.dataset.selectedValue = selectedValue;
    }

    radios.forEach(function (radio) {
      radio.addEventListener("change", function () {
        if (!radio.checked || select.value === radio.value) return;
        select.value = radio.value;
        select.dispatchEvent(new Event("change", { bubbles: true }));
      });
    });
    select.addEventListener("change", syncPickerFromSelect);
    select.classList.add("is-enhanced");
    select.setAttribute("aria-hidden", "true");
    select.tabIndex = -1;
    picker.classList.add("is-enhanced");
    if (select.parentElement) {
      new MutationObserver(hideDuplicateSelectUi).observe(select.parentElement, { childList: true });
    }
    syncPickerFromSelect();
    window.syncLessonMediaPicker = syncPickerFromSelect;
    return syncPickerFromSelect;
  }

  function initAvailabilityMode() {
    var toggle = document.getElementById("lessonAlwaysOpen");
    var fields = document.getElementById("lessonAvailabilityFields");
    if (!toggle || !fields) return function () {};

    var controls = Array.prototype.slice.call(fields.querySelectorAll("input"));
    var startDate = document.getElementById("date_start_les");
    var endDate = document.getElementById("date_end_les");
    toggle.checked = Boolean(startDate && endDate && !startDate.value && !endDate.value);
    function syncAvailabilityMode() {
      fields.classList.toggle("is-disabled", toggle.checked);
      controls.forEach(function (control) {
        control.disabled = toggle.checked;
      });
      if (toggle.checked) {
        controls.forEach(function (control) { control.value = ""; });
      }
    }

    toggle.addEventListener("change", function () {
      syncAvailabilityMode();
      if (window.updateLessonReadiness) window.updateLessonReadiness();
    });
    syncAvailabilityMode();
    return syncAvailabilityMode;
  }

  function initVideoManager(form) {
    var manager = document.getElementById("div_multifile_upload_file");
    var videoInput = document.getElementById("media_file");
    var thumbnailInput = document.getElementById("thumbnail_med");
    var previewPanel = document.getElementById("lessonVideoPreviewPanel");
    var previewVideo = document.getElementById("lessonVideoPreview");
    var captureButton = document.getElementById("lessonCaptureThumbnail");
    var feedback = document.getElementById("lessonVideoFeedback");
    if (!manager || !videoInput || !previewPanel || !previewVideo) return;

    function createMediaEditor() {
      var dialog = document.getElementById("lessonMediaEditor");
      if (dialog) return dialog;
      dialog = document.createElement("dialog");
      dialog.id = "lessonMediaEditor";
      dialog.className = "lesson-media-editor";
      dialog.setAttribute("closedby", "any");
      dialog.setAttribute("aria-labelledby", "lessonMediaEditorTitle");
      dialog.innerHTML = '<form method="dialog" class="lesson-media-editor-form"><header><div><span>EDIT VIDEO</span><h5 id="lessonMediaEditorTitle">แก้ไขวิดีโอ</h5><p>แสดงช่องชื่อตามภาษาที่หลักสูตรรองรับ ไฟล์เดิมจะยังคงอยู่หากไม่ได้เลือกไฟล์ใหม่</p></div><button type="button" class="lesson-media-editor-close" aria-label="ปิด"><i class="mdi mdi-window-close" aria-hidden="true"></i></button></header><div class="lesson-media-editor-body"><input type="hidden" name="id"><div class="lesson-media-editor-names"><label data-language="th">ชื่อภาษาไทย<input class="form-control" name="med_name_th" type="text"></label><label data-language="eng">English name<input class="form-control" name="med_name_eng" type="text"></label><label data-language="jp">日本語名<input class="form-control" name="med_name_jp" type="text"></label></div><section class="lesson-media-current" aria-label="ไฟล์ที่ใช้อยู่"><div class="lesson-media-current-cover"><div class="lesson-media-cover-preview"><img data-current-cover alt="ภาพปกปัจจุบัน" hidden><i class="mdi mdi-image-area" data-cover-placeholder aria-hidden="true"></i></div><div><span>ภาพปกปัจจุบัน</span><strong data-current-cover-name>ยังไม่มีภาพปก</strong></div></div><div class="lesson-media-current-video"><i class="mdi mdi-video" aria-hidden="true"></i><div><span>ไฟล์วิดีโอปัจจุบัน</span><strong data-current-video>—</strong></div></div></section><div class="lesson-media-replacements"><div class="lesson-media-replacement"><label for="lessonEditorThumbnail"><i class="mdi mdi-image-area" aria-hidden="true"></i><span><strong>เปลี่ยนภาพปก</strong><small>JPG สูงสุด 10 MB · ไม่บังคับ</small></span></label><input id="lessonEditorThumbnail" class="dropify" name="thumbnail" type="file" accept="image/jpeg,.jpg" data-height="120"></div><div class="lesson-media-replacement"><label for="lessonEditorVideo"><i class="mdi mdi-video-switch" aria-hidden="true"></i><span><strong>เปลี่ยนไฟล์วิดีโอ</strong><small>MP4 สูงสุด 512 MB · ไม่บังคับ</small></span></label><input id="lessonEditorVideo" class="dropify" name="video" type="file" accept="video/mp4,.mp4" data-height="120"></div></div><p class="lesson-media-editor-feedback" aria-live="polite"></p></div><footer><button type="button" class="btn lesson-media-editor-cancel">ยกเลิก</button><button type="submit" class="btn lesson-media-editor-save"><i class="mdi mdi-content-save" aria-hidden="true"></i>บันทึกการแก้ไข</button></footer></form>';
      document.body.appendChild(dialog);

      if (window.jQuery && window.jQuery.fn.dropify) {
        window.jQuery(dialog).find(".dropify").dropify({
          messages: {
            default: "ลากไฟล์มาวาง หรือคลิกเพื่อเลือกไฟล์",
            replace: "ลากไฟล์ใหม่มาวาง หรือคลิกเพื่อเปลี่ยน",
            remove: "ลบ",
            error: "ไม่สามารถเลือกไฟล์นี้ได้"
          },
          error: { fileSize: "ไฟล์มีขนาดใหญ่เกินกำหนด", fileExtension: "ชนิดไฟล์ไม่ถูกต้อง" }
        });
      }

      var editorCoverUrl = "";
      function closeEditor() {
        if (editorCoverUrl) URL.revokeObjectURL(editorCoverUrl);
        editorCoverUrl = "";
        dialog.close();
      }
      dialog.querySelector('input[name="thumbnail"]').addEventListener("change", function (event) {
        var file = event.currentTarget.files && event.currentTarget.files[0];
        if (!file) return;
        if (editorCoverUrl) URL.revokeObjectURL(editorCoverUrl);
        editorCoverUrl = URL.createObjectURL(file);
        var cover = dialog.querySelector("[data-current-cover]");
        cover.src = editorCoverUrl;
        cover.hidden = false;
        dialog.querySelector("[data-cover-placeholder]").hidden = true;
        dialog.querySelector("[data-current-cover-name]").textContent = "ไฟล์ใหม่: " + file.name;
      });
      dialog.querySelector('input[name="video"]').addEventListener("change", function (event) {
        var file = event.currentTarget.files && event.currentTarget.files[0];
        if (file) dialog.querySelector("[data-current-video]").textContent = "ไฟล์ใหม่: " + file.name;
      });
      dialog.querySelector(".lesson-media-editor-close").addEventListener("click", closeEditor);
      dialog.querySelector(".lesson-media-editor-cancel").addEventListener("click", closeEditor);
      dialog.addEventListener("cancel", function (event) { event.preventDefault(); closeEditor(); });
      dialog.addEventListener("click", function (event) { if (event.target === dialog) closeEditor(); });
      dialog.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault();
        var editorForm = event.currentTarget;
        var status = dialog.querySelector(".lesson-media-editor-feedback");
        var saveButton = dialog.querySelector(".lesson-media-editor-save");
        status.textContent = "กำลังบันทึก…";
        status.classList.remove("is-error", "is-success");
        saveButton.disabled = true;
        fetch(manager.dataset.mediaUpdateUrl, { method: "POST", body: new FormData(editorForm), credentials: "same-origin" })
          .then(function (response) { return response.json().then(function (data) { if (!response.ok) throw new Error(data.message || "Update failed"); return data; }); })
          .then(function () {
            status.textContent = "บันทึกการแก้ไขแล้ว";
            status.classList.add("is-success");
            if (window.jQuery && window.jQuery.fn.DataTable && window.jQuery.fn.DataTable.isDataTable("#myTable_media")) {
              window.jQuery("#myTable_media").DataTable().ajax.reload(null, false);
            }
            window.setTimeout(closeEditor, 450);
          })
          .catch(function (error) { status.textContent = error.message; status.classList.add("is-error"); })
          .then(function () { saveButton.disabled = false; });
      });
      return dialog;
    }

    function openMediaEditor(id) {
      var dialog = createMediaEditor();
      var editorForm = dialog.querySelector("form");
      var status = dialog.querySelector(".lesson-media-editor-feedback");
      editorForm.reset();
      if (window.jQuery && window.jQuery.fn.dropify) {
        window.jQuery(dialog).find(".dropify").each(function () {
          var instance = window.jQuery(this).data("dropify");
          if (instance) {
            instance.resetPreview();
            instance.clearElement();
          }
        });
      }
      var languageField = document.getElementById("les_lang");
      var supportedLanguages = languageField && languageField.value
        ? languageField.value.split(",").map(function (language) { return language.trim().toLowerCase(); })
        : ["th", "eng", "jp"];
      supportedLanguages = supportedLanguages.map(function (language) {
        if (language === "thai") return "th";
        if (language === "en" || language === "english") return "eng";
        if (language === "japan" || language === "japanese") return "jp";
        return language;
      });
      var visibleLanguageCount = 0;
      Array.prototype.forEach.call(dialog.querySelectorAll("[data-language]"), function (label) {
        var isSupported = supportedLanguages.indexOf(label.dataset.language) !== -1;
        var input = label.querySelector("input");
        label.hidden = !isSupported;
        input.disabled = !isSupported;
        input.required = isSupported;
        if (isSupported) visibleLanguageCount += 1;
      });
      if (!visibleLanguageCount) {
        Array.prototype.forEach.call(dialog.querySelectorAll("[data-language]"), function (label) {
          label.hidden = false;
          label.querySelector("input").disabled = false;
          label.querySelector("input").required = true;
        });
        visibleLanguageCount = 3;
      }
      dialog.querySelector(".lesson-media-editor-names").dataset.languageCount = String(visibleLanguageCount);
      status.textContent = "กำลังโหลดข้อมูล…";
      status.classList.remove("is-error", "is-success");
      dialog.showModal();
      fetch(manager.dataset.mediaDetailUrl + "?id=" + encodeURIComponent(id), { credentials: "same-origin" })
        .then(function (response) { return response.json().then(function (data) { if (!response.ok) throw new Error(data.message || "Load failed"); return data; }); })
        .then(function (result) {
          var data = result.data;
          editorForm.elements.id.value = data.id;
          editorForm.elements.med_name_th.value = data.med_name_th || "";
          editorForm.elements.med_name_eng.value = data.med_name_eng || "";
          editorForm.elements.med_name_jp.value = data.med_name_jp || "";
          dialog.querySelector("[data-current-video]").textContent = data.video || "—";
          var cover = dialog.querySelector("[data-current-cover]");
          var coverPlaceholder = dialog.querySelector("[data-cover-placeholder]");
          cover.hidden = !data.thumbnail;
          coverPlaceholder.hidden = Boolean(data.thumbnail);
          dialog.querySelector("[data-current-cover-name]").textContent = data.thumbnail || "ยังไม่มีภาพปก";
          if (data.thumbnail) cover.src = manager.dataset.thumbnailBase + encodeURIComponent(data.thumbnail);
          status.textContent = "";
          var firstNameField = dialog.querySelector("[data-language]:not([hidden]) input");
          if (firstNameField) firstNameField.focus();
        })
        .catch(function (error) { status.textContent = error.message; status.classList.add("is-error"); });
    }

    var currentPreviewUrl = "";
    var maxVideoBytes = 512 * 1024 * 1024;

    function setFeedback(message, isError) {
      feedback.textContent = message || "";
      feedback.classList.toggle("is-error", Boolean(isError));
    }

    function resetPreview() {
      if (currentPreviewUrl) URL.revokeObjectURL(currentPreviewUrl);
      currentPreviewUrl = "";
      previewVideo.pause();
      previewVideo.removeAttribute("src");
      previewVideo.load();
      previewPanel.hidden = true;
      setFeedback("");
    }

    videoInput.addEventListener("change", function () {
      var file = videoInput.files && videoInput.files[0];
      resetPreview();
      videoInput.setCustomValidity("");
      if (!file) return;

      var isMp4 = file.type === "video/mp4" || /\.mp4$/i.test(file.name);
      if (!isMp4) {
        videoInput.setCustomValidity("Only MP4 video files are supported.");
        setFeedback("รองรับเฉพาะไฟล์ MP4 เท่านั้น", true);
        videoInput.value = "";
        return;
      }
      if (file.size > maxVideoBytes) {
        videoInput.setCustomValidity("Video size must not exceed 512 MB.");
        setFeedback("ไฟล์มีขนาดเกิน 512 MB กรุณาลดขนาดก่อนอัปโหลด", true);
        videoInput.value = "";
        return;
      }

      currentPreviewUrl = URL.createObjectURL(file);
      previewPanel.hidden = false;
      previewVideo.src = currentPreviewUrl;
      document.getElementById("lessonVideoPreviewName").textContent = file.name;
      document.getElementById("lessonVideoSize").textContent = formatBytes(file.size);
      document.getElementById("lessonVideoFormat").textContent = "MP4";
      document.getElementById("lessonVideoDuration").textContent = "…";
      document.getElementById("lessonVideoResolution").textContent = "…";
      setFeedback("ไฟล์ผ่านการตรวจสอบเบื้องต้น พร้อมดูตัวอย่างและบันทึก");
    });

    previewVideo.addEventListener("loadedmetadata", function () {
      document.getElementById("lessonVideoDuration").textContent = formatDuration(previewVideo.duration);
      document.getElementById("lessonVideoResolution").textContent = previewVideo.videoWidth + " × " + previewVideo.videoHeight;
      var ratio = previewVideo.videoHeight ? previewVideo.videoWidth / previewVideo.videoHeight : 0;
      if (ratio && Math.abs(ratio - (16 / 9)) > .08) {
        setFeedback("วิดีโอใช้งานได้ แต่แนะนำอัตราส่วน 16:9 เพื่อให้แสดงผลเต็มพื้นที่", false);
      }
    });

    previewVideo.addEventListener("error", function () {
      setFeedback("ไม่สามารถอ่านตัวอย่างวิดีโอนี้ได้ กรุณาตรวจสอบ codec ของไฟล์ MP4", true);
    });

    if (captureButton && thumbnailInput) {
      captureButton.addEventListener("click", function () {
        if (!previewVideo.videoWidth || !previewVideo.videoHeight) {
          setFeedback("กรุณารอให้วิดีโอพร้อมเล่นก่อนเลือกภาพปก", true);
          return;
        }
        var canvas = document.createElement("canvas");
        canvas.width = previewVideo.videoWidth;
        canvas.height = previewVideo.videoHeight;
        canvas.getContext("2d").drawImage(previewVideo, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(function (blob) {
          if (!blob || typeof DataTransfer === "undefined") {
            setFeedback("เบราว์เซอร์นี้ไม่รองรับการสร้างภาพปกอัตโนมัติ", true);
            return;
          }
          var transfer = new DataTransfer();
          transfer.items.add(new File([blob], "video-cover.jpg", { type: "image/jpeg" }));
          thumbnailInput.files = transfer.files;
          thumbnailInput.dispatchEvent(new Event("change", { bubbles: true }));
          setFeedback("สร้างภาพปกจากเฟรมปัจจุบันแล้ว");
        }, "image/jpeg", .9);
      });
    }

    document.addEventListener("click", function (event) {
      var editButton = event.target.closest && event.target.closest(".edit_media");
      if (editButton && manager.contains(editButton)) {
        event.preventDefault();
        openMediaEditor(editButton.dataset.mediaId);
        return;
      }
      var link = event.target.closest && event.target.closest(".view_video");
      if (!link || !manager.contains(link)) return;
      event.preventDefault();
      event.stopImmediatePropagation();
      var path = link.getAttribute("id") || "";
      var dialog = createVideoDialog();
      var video = dialog.querySelector("video");
      var status = dialog.querySelector(".lesson-video-dialog-status");
      var filename = link.textContent.trim() || path.split(/[\\/]/).pop() || "Video preview";
      dialog.querySelector("strong").textContent = filename;
      dialog.querySelector("strong").title = filename;
      status.hidden = false;
      status.textContent = "กำลังโหลดวิดีโอ…";
      status.classList.remove("is-error");
      var base = manager.dataset.videoBase || window.location.origin + "/";
      try {
        video.src = new URL(path.replace(/^\/+/, ""), /\/$/.test(base) ? base : base + "/").href;
      } catch (error) {
        video.src = base.replace(/\/+$/, "") + "/" + path.replace(/^\/+/, "");
      }
      dialog.showModal();
    }, true);

    form.addEventListener("reset", function () {
      window.setTimeout(resetPreview, 0);
    });
  }

  function initLessonAuthoring() {
    var form = document.getElementById("lesson_form");
    var readiness = form && form.querySelector(".lesson-readiness");
    if (!form || !readiness) return;

    var syncMediaPicker = initMediaSourcePicker();
    var syncAvailabilityMode = initAvailabilityMode();
    var checks = {
      title: readiness.querySelector('[data-lesson-check="title"]'),
      schedule: readiness.querySelector('[data-lesson-check="schedule"]'),
      type: readiness.querySelector('[data-lesson-check="type"]'),
      content: readiness.querySelector('[data-lesson-check="content"]')
    };
    var readinessText = document.getElementById("lessonReadinessText");
    var readinessBar = document.getElementById("lessonReadinessBar");
    var saveStatus = document.getElementById("lessonSaveStatus");

    function hasLessonTitle() {
      var languageField = document.getElementById("les_lang");
      var selectedLanguages = languageField && languageField.value
        ? languageField.value.split(",").map(function (language) { return language.trim().toLowerCase(); })
        : [];
      var titleFields = {
        th: document.getElementById("les_name_th"),
        eng: document.getElementById("les_name_eng"),
        en: document.getElementById("les_name_eng"),
        jp: document.getElementById("les_name_jp"),
        japan: document.getElementById("les_name_jp")
      };

      if (selectedLanguages.length) {
        return selectedLanguages.some(function (language) {
          var input = titleFields[language];
          return Boolean(input && input.value.trim());
        });
      }

      return [titleFields.th, titleFields.eng, titleFields.jp].some(function (input) {
        return Boolean(input && input.value.trim());
      });
    }

    function hasContent() {
      var lessonType = document.getElementById("les_type");
      if (lessonType && lessonType.checked) {
        var scorm = document.getElementById("scorm_file");
        var existingScorm = document.getElementById("txt_scormoriginal");
        return Boolean((scorm && scorm.files && scorm.files.length) || (existingScorm && existingScorm.textContent.trim()));
      }

      var mediaType = document.getElementById("type_media");
      if (!mediaType || mediaType.value === "0") return false;
      if (mediaType.value === "1") {
        var url = document.getElementById("url_media");
        return Boolean(url && url.value.trim());
      }
      var media = document.getElementById("media_file");
      var existingMediaRows = Array.prototype.slice.call(document.querySelectorAll("#myTable_media tbody tr"));
      var hasExistingMedia = existingMediaRows.some(function (row) {
        return !row.querySelector("td.dataTables_empty") && row.textContent.trim() !== "";
      });
      return Boolean((media && media.files && media.files.length) || hasExistingMedia);
    }

    function updateReadiness() {
      var start = document.getElementById("date_start_les");
      var end = document.getElementById("date_end_les");
      var mediaType = document.getElementById("type_media");
      var lessonType = document.getElementById("les_type");
      var states = {
        title: hasLessonTitle(),
        schedule: Boolean(document.getElementById("lessonAlwaysOpen") && document.getElementById("lessonAlwaysOpen").checked) || Boolean(start && start.value && end && end.value),
        type: Boolean((lessonType && lessonType.checked) || (mediaType && mediaType.value !== "0")),
        content: hasContent()
      };
      var completed = 0;

      Object.keys(states).forEach(function (key) {
        var item = checks[key];
        if (!item) return;
        item.classList.toggle("is-complete", states[key]);
        var icon = item.querySelector("i");
        if (icon) icon.className = states[key] ? "mdi mdi-check-circle" : "mdi mdi-circle-outline";
        if (states[key]) completed += 1;
      });

      var ready = completed === 4;
      readiness.classList.toggle("is-ready", ready);
      readinessText.textContent = completed + " / 4";
      readinessBar.style.inlineSize = (completed * 25) + "%";
      saveStatus.classList.toggle("is-ready", ready);
      saveStatus.querySelector("span").textContent = ready
        ? readiness.dataset.readyLabel
        : completed + " / 4";
    }

    form.querySelectorAll('input[type="file"]').forEach(addFileSummary);
    initVideoManager(form);
    form.addEventListener("input", updateReadiness);
    form.addEventListener("change", updateReadiness);
    form.addEventListener("reset", function () {
      window.setTimeout(function () {
        syncMediaPicker();
        updateReadiness();
      }, 0);
    });

    window.updateLessonReadiness = updateReadiness;

    var observer = new MutationObserver(function () {
      window.requestAnimationFrame(updateReadiness);
    });
    ["div_media", "div_scorm", "div_multifile_url", "div_multifile_upload_file"].forEach(function (id) {
      var target = document.getElementById(id);
      if (target) observer.observe(target, { attributes: true, attributeFilter: ["style", "class"] });
    });

    var lessonEditor = document.getElementById("div_create_lesson");
    if (lessonEditor) observer.observe(lessonEditor, { attributes: true, attributeFilter: ["style", "class"] });

    var mediaTableBody = document.querySelector("#myTable_media tbody");
    if (mediaTableBody) observer.observe(mediaTableBody, { childList: true });

    var existingScorm = document.getElementById("txt_scormoriginal");
    if (existingScorm) observer.observe(existingScorm, { childList: true, characterData: true, subtree: true });

    if (window.jQuery) {
      window.jQuery(document).on("ajaxComplete.lessonReadiness", function () {
        [0, 80, 250].forEach(function (delay) {
          window.setTimeout(function () {
            syncMediaPicker();
            var alwaysOpen = document.getElementById("lessonAlwaysOpen");
            if (alwaysOpen && start && end) alwaysOpen.checked = !start.value && !end.value;
            syncAvailabilityMode();
            updateReadiness();
          }, delay);
        });
      });
    }

    updateReadiness();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initLessonAuthoring);
  } else {
    initLessonAuthoring();
  }
}());
