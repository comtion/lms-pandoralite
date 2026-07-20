<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        body { margin: 0; font-family: Tahoma, Arial, sans-serif; background: #f3f5f8; color: #18202a; }
        a { color: inherit; text-decoration: none; }
        .topbar { background: #fff; border-bottom: 1px solid #dfe5ec; position: sticky; top: 0; z-index: 20; }
        .brand-row { max-width: 1250px; margin: 0 auto; height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 18px; }
        .brand-row img { max-height: 46px; }
        .main-nav { max-width: 1250px; margin: 0 auto; display: flex; padding: 0 18px; overflow-x: auto; }
        .nav-item { position: relative; flex: 0 0 auto; }
        .nav-link { height: 54px; display: flex; align-items: center; padding: 0 14px; color: #596676; font-weight: 700; border-bottom: 3px solid transparent; }
        .nav-link:hover { color: #ec2029; border-bottom-color: #ec2029; background: #f7f8fa; }
        .page { max-width: 1250px; margin: 0 auto; padding: 28px 18px; }
        .panel { background: #fff; border: 1px solid #e1e7ee; padding: 28px; }
        .eyebrow { color: #ec2029; font-weight: 700; margin: 0 0 8px; }
        h1 { margin: 0 0 10px; font-size: 28px; }
        p { color: #607080; line-height: 1.7; }
        code { background: #f1f3f6; border: 1px solid #dfe5ec; border-radius: 4px; padding: 2px 6px; }
        .actions { margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { border: 1px solid #d5dbe3; border-radius: 4px; padding: 10px 14px; background: #fff; font-weight: 700; }
        .btn.primary { background: #ec2029; border-color: #ec2029; color: #fff; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <img src="<?= site_url('images/logo.png') ?>" alt="Logo">
        <strong>LMS CI4 Migration</strong>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </div>
    <nav class="main-nav">
        <?php foreach (($menus ?? []) as $menu): ?>
            <div class="nav-item"><a class="nav-link" href="<?= site_url($menu['path']) ?>"><?= esc($menu['name']) ?></a></div>
        <?php endforeach; ?>
    </nav>
</header>
<main class="page">
    <section class="panel">
        <p class="eyebrow"><?= esc($title_main ?: 'Feature') ?></p>
        <h1><?= esc($title) ?></h1>
        <p>หน้านี้มีอยู่ในสิทธิ์และเมนูเดิมแล้ว แต่ยังไม่ได้ migrate logic จาก CodeIgniter 3 มาเป็น CodeIgniter 4 แบบสมบูรณ์</p>
        <p>Route: <code><?= esc($path) ?></code></p>
        <div class="actions">
            <a class="btn primary" href="<?= site_url('dashboard') ?>">กลับ Dashboard</a>
            <a class="btn" href="<?= site_url('migration/status') ?>">ดูสถานะ Migration</a>
        </div>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
