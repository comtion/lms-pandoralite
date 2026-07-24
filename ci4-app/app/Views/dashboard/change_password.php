<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เปลี่ยนรหัสผ่าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f3f5f8; font-family:Tahoma, Arial, sans-serif; color:#18202a; }
        .page { max-width:620px; margin:0 auto; padding:34px 18px; }
        .panel { background:#fff; border:1px solid #dfe5ec; padding:24px; }
        h1 { font-size:24px; font-weight:700; margin:0 0 22px; border-left:2px solid #ec2029; padding-left:12px; }
        .btn-primary { background:#ec2029; border-color:#ec2029; }
        .btn-primary:hover { background:#c91821; border-color:#c91821; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<main class="page">
    <div class="panel">
        <h1>เปลี่ยนรหัสผ่าน</h1>
        <?php if (! empty($message)): ?><div class="alert alert-success"><?= esc($message) ?></div><?php endif; ?>
        <?php if (! empty($error)): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
        <form method="post" action="<?= site_url('dashboard/change_pass') ?>"><?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">รหัสผ่านเดิม</label>
                <input class="form-control" type="password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input class="form-control" type="password" name="new_password" required>
            </div>
            <div class="mb-4">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input class="form-control" type="password" name="confirm_password" required>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a class="btn btn-outline-secondary" href="<?= site_url('dashboard') ?>">กลับ</a>
                <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>บันทึก</button>
            </div>
        </form>
    </div>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
