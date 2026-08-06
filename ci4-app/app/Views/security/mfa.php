<!doctype html><html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>MFA Security</title>
<style>body{font-family:Tahoma,Arial;background:#f4f6f8}main{max-width:650px;margin:40px auto;background:white;padding:28px}code{display:block;overflow-wrap:anywhere;background:#eee;padding:12px}input,button{padding:10px;margin-top:12px}.ok{color:green}.error{color:#b00020}</style></head><body><main>
<p><a href="<?= site_url('dashboard') ?>">กลับ Dashboard</a></p><h1>การยืนยันตัวตนสองขั้นตอน</h1>
<?php if ($message): ?><p class="ok"><?= esc($message) ?></p><?php endif ?><?php if ($error): ?><p class="error"><?= esc($error) ?></p><?php endif ?>
<?php if ($enabled): ?><p>MFA เปิดใช้งานอยู่</p><form method="post" action="<?= site_url('security/mfa/disable') ?>"><?= csrf_field() ?><input name="code" pattern="[0-9]{6}" required placeholder="รหัส 6 หลัก"><button>ปิด MFA</button></form>
<?php else: ?><p>เพิ่มบัญชีด้วย URI ด้านล่างในแอป Authenticator แล้วกรอกรหัสเพื่อยืนยัน</p><code><?= esc($uri) ?></code><p>Secret: <strong><?= esc($secret) ?></strong></p>
<form method="post" action="<?= site_url('security/mfa/enable') ?>"><?= csrf_field() ?><input name="code" pattern="[0-9]{6}" required placeholder="รหัส 6 หลัก"><button>เปิด MFA</button></form><?php endif ?>
</main></body></html>
