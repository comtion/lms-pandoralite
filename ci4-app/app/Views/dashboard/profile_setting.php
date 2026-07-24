<?php
$profileImage = ! empty($profile['img_profile']) ? base_url('uploads/profile/' . $profile['img_profile']) : base_url('uploads/profile/default_profile.jpg');
$isModal = (string) service('request')->getGet('modal') === '1';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขโปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f3f5f8; font-family: Tahoma, Arial, sans-serif; color: #18202a; }
        .page { max-width: 980px; margin: 0 auto; padding: 28px 18px; }
        .panel { background: #fff; border: 1px solid #dfe5ec; padding: 24px; }
        .head { display: flex; align-items: center; gap: 18px; margin-bottom: 24px; }
        .avatar { width: 86px; height: 86px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 10px 24px rgba(0,0,0,.14); }
        .title { margin: 0; font-size: 24px; font-weight: 700; }
        .muted { color: #667386; margin: 4px 0 0; }
        .section-title { border-left: 2px solid #ec2029; padding-left: 12px; margin: 20px 0 16px; font-size: 18px; font-weight: 700; }
        .btn-primary { background: #ec2029; border-color: #ec2029; }
        .btn-primary:hover { background: #c91821; border-color: #c91821; }
        body.profile-modal-mode { background: transparent; }
        body.profile-modal-mode .page { max-width: none; padding: 0; }
        body.profile-modal-mode .panel { border: 0; box-shadow: none !important; border-radius: 0 !important; }
        body.profile-modal-mode .head { margin-bottom: 18px; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body class="<?= $isModal ? 'profile-modal-mode' : '' ?>">
<main class="page">
    <div class="panel">
        <div class="head">
            <img class="avatar" src="<?= esc($profileImage) ?>" onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'" alt="Profile">
            <div>
                <h1 class="title">แก้ไขโปรไฟล์</h1>
                <p class="muted"><?= esc($profile['useri'] ?? '') ?></p>
            </div>
        </div>

        <?php if (! empty($message)): ?>
            <div class="alert alert-success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if (! empty($error)): ?>
            <div class="alert alert-danger"><?= esc($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('dashboard/profile/setting') ?><?= $isModal ? '?modal=1' : '' ?>"><?= csrf_field() ?>
            <h2 class="section-title">ข้อมูลภาษาไทย</h2>
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">คำนำหน้า</label>
                    <input class="form-control" name="prefix_th" value="<?= esc(old('prefix_th', $profile['prefix_th'] ?? '')) ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label">ชื่อ</label>
                    <input class="form-control" name="fname_th" value="<?= esc(old('fname_th', $profile['fname_th'] ?? '')) ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label">นามสกุล</label>
                    <input class="form-control" name="lname_th" value="<?= esc(old('lname_th', $profile['lname_th'] ?? '')) ?>">
                </div>
            </div>

            <h2 class="section-title">English</h2>
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Prefix</label>
                    <input class="form-control" name="prefix_en" value="<?= esc(old('prefix_en', $profile['prefix_en'] ?? '')) ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label">First name</label>
                    <input class="form-control" name="fname_en" value="<?= esc(old('fname_en', $profile['fname_en'] ?? '')) ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Last name</label>
                    <input class="form-control" name="lname_en" value="<?= esc(old('lname_en', $profile['lname_en'] ?? '')) ?>">
                </div>
            </div>

            <h2 class="section-title">ข้อมูลติดต่อ</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">โทรศัพท์</label>
                    <input class="form-control" name="phone" value="<?= esc(old('phone', $profile['phone'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เบอร์ที่ทำงาน</label>
                    <input class="form-control" name="work_phone" value="<?= esc(old('work_phone', $profile['work_phone'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="<?= esc(old('email', $profile['email'] ?? '')) ?>">
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <?php if ($isModal): ?>
                    <button class="btn btn-outline-secondary" type="button" onclick="window.parent.postMessage({type: 'profileModalClose'}, '*')">กลับ</button>
                <?php else: ?>
                    <a class="btn btn-outline-secondary" href="<?= site_url('dashboard') ?>">กลับ</a>
                <?php endif; ?>
                <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>บันทึก</button>
            </div>
        </form>
    </div>
</main>
<?php if ($isModal && ! empty($message)): ?>
    <script>
    window.parent.postMessage({type: 'profileSaved'}, '*');
    </script>
<?php endif; ?>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
