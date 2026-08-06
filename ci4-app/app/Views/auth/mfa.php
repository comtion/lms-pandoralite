<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ยืนยันตัวตนสองขั้นตอน</title>
    <style>body{font-family:Tahoma,Arial;background:#f3f5f8;display:grid;place-items:center;min-height:100vh}.card{background:#fff;padding:28px;border-radius:12px;width:min(390px,85vw);box-shadow:0 8px 30px #0002}input,button{box-sizing:border-box;width:100%;padding:12px;margin-top:12px}button{background:#b5121b;color:#fff;border:0;border-radius:6px}.error{color:#b00020}</style>
</head>
<body><main class="card">
    <h1>ยืนยันตัวตน</h1>
    <p>กรอกรหัส 6 หลักจากแอป Authenticator</p>
    <?php if ($error): ?><p class="error"><?= esc($error) ?></p><?php endif ?>
    <form method="post" action="<?= site_url('login/mfa') ?>">
        <?= csrf_field() ?><input name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus>
        <button type="submit">ยืนยัน</button>
    </form>
</main></body></html>
