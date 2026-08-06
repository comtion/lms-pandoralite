<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ตั้งรหัสผ่านใหม่</title>
  <link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f4f7fa; min-height:100vh; display:flex; align-items:center; }
    .reset-card { max-width:520px; margin:auto; background:#fff; padding:32px; border-radius:12px; box-shadow:0 10px 35px rgba(0,0,0,.08); }
    .requirements { color:#667; font-size:.9rem; }
  </style>
</head>
<body>
  <main class="container">
    <section class="reset-card">
      <h2>ตั้งรหัสผ่านใหม่</h2>
      <?php if (!$valid_token): ?>
        <div class="alert alert-warning">ลิงก์นี้ไม่ถูกต้อง หมดอายุ หรือถูกใช้แล้ว</div>
        <a class="btn btn-primary" href="<?php echo base_url('home'); ?>">กลับหน้าเข้าสู่ระบบ</a>
      <?php else: ?>
        <p class="requirements">อย่างน้อย 10 ตัวอักษร และต้องมีตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก ตัวเลข และอักขระพิเศษ</p>
        <form id="reset-form">
          <input type="hidden" name="token" value="<?php echo html_escape($token); ?>">
          <?php if ($this->config->item('csrf_protection')): ?>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <?php endif; ?>
          <div class="form-group">
            <label for="password">รหัสผ่านใหม่</label>
            <input class="form-control" id="password" name="password" type="password" minlength="10" autocomplete="new-password" required>
          </div>
          <div class="form-group">
            <label for="password-confirm">ยืนยันรหัสผ่านใหม่</label>
            <input class="form-control" id="password-confirm" name="password_confirm" type="password" minlength="10" autocomplete="new-password" required>
          </div>
          <div id="result" role="alert"></div>
          <button class="btn btn-primary btn-block" type="submit">บันทึกรหัสผ่านใหม่</button>
        </form>
      <?php endif; ?>
    </section>
  </main>
<?php if ($valid_token): ?>
<script>
document.getElementById('reset-form').addEventListener('submit', async function (event) {
  event.preventDefault();
  const result = document.getElementById('result');
  const button = this.querySelector('button[type="submit"]');
  button.disabled = true;
  try {
    const response = await fetch('<?php echo base_url('dashboard/complete_password_reset'); ?>', {
      method: 'POST', body: new FormData(this), credentials: 'same-origin'
    });
    const data = await response.json();
    if (!data.rs) throw new Error(data.msg || 'ไม่สามารถตั้งรหัสผ่านได้');
    result.className = 'alert alert-success';
    result.textContent = 'ตั้งรหัสผ่านใหม่เรียบร้อยแล้ว กำลังกลับไปหน้าเข้าสู่ระบบ';
    window.setTimeout(function () { window.location.href = data.redirect_val; }, 1200);
  } catch (error) {
    result.className = 'alert alert-danger';
    result.textContent = error.message;
    button.disabled = false;
  }
});
</script>
<?php endif; ?>
</body>
</html>
