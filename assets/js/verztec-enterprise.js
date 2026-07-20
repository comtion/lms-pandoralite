(function () {
  function showActionDialog(type) {
    var isThai = document.documentElement.lang === "th" || /thai/i.test(document.documentElement.lang || "");
    var isUnlock = type.indexOf("unlockAcc") !== -1;
    var title = isUnlock ? (isThai ? "ปลดล็อกผู้ใช้" : "Unlock User") : (isThai ? "ตั้งรหัสผ่านใหม่" : "Reset Password");
    var message = isUnlock
      ? (isThai ? "คำสั่งนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดปลดล็อกในรายการผู้ใช้" : "This action needs a selected user first. Go to User Information and use the unlock command on a user row.")
      : (isThai ? "คำสั่งนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดตั้งรหัสผ่านใหม่ในรายการผู้ใช้" : "This action needs a selected user first. Go to User Information and use the reset password command on a user row.");

    if (window.swal) {
      swal({
        title: title,
        text: message,
        type: "info",
        showCancelButton: true,
        confirmButtonText: isThai ? "ไปหน้า User Information" : "Go to User Information",
        cancelButtonText: isThai ? "ปิด" : "Close",
      }, function (ok) {
        if (ok) {
          window.location.href = (window.REAL_PATH || "") + "/manage/userdata";
        }
      });
      return;
    }

    if (window.confirm(title + "\n\n" + message)) {
      window.location.href = (window.REAL_PATH || "") + "/manage/userdata";
    }
  }

  document.addEventListener("click", function (event) {
    var link = event.target.closest && event.target.closest("a[href]");
    if (!link) {
      return;
    }

    var href = link.getAttribute("href") || "";
    if (href.indexOf("dashboard/unlockAcc") === -1 && href.indexOf("dashboard/resetPass") === -1) {
      return;
    }

    event.preventDefault();
    showActionDialog(href);
  });
})();
