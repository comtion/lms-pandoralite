<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --green:#087443; --red:#b42318; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .main-nav { max-width:1250px; margin:0 auto; display:flex; gap:2px; padding:0 22px; overflow:visible; }
        .nav-item { position:relative; }
        .nav-link { display:flex; gap:9px; align-items:center; min-height:56px; padding:0 13px; color:#5b6573; font-weight:700; font-size:14px; border-bottom:3px solid transparent; white-space:nowrap; }
        .nav-link:hover, .nav-item.active > .nav-link { color:var(--brand); border-color:var(--brand); }
        .nav-icon { width:10px; height:10px; border-radius:2px; background:currentColor; display:inline-block; }
        .dropdown { display:none; position:absolute; top:56px; left:0; min-width:250px; background:#fff; border:1px solid var(--line); box-shadow:0 18px 40px rgba(15,23,42,.12); padding:10px 0; }
        .nav-item:hover > .dropdown { display:block; }
        .dropdown a { display:flex; gap:10px; align-items:center; padding:12px 16px; color:#4b5563; font-size:14px; }
        .dropdown a:hover { color:var(--brand); background:#fafafa; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 44px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; }
        .head { padding:22px; display:flex; justify-content:space-between; gap:20px; align-items:flex-end; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:6px 0 0; font-size:30px; line-height:1.2; }
        .muted { color:var(--muted); font-size:13px; line-height:1.5; }
        .table-wrap { overflow:auto; border-top:1px solid var(--line); }
        table { width:100%; border-collapse:collapse; min-width:880px; font-size:14px; }
        th, td { padding:13px 16px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
        th { color:#42526b; background:#fafafa; font-size:12px; text-transform:uppercase; }
        .btn { display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); border-radius:7px; padding:9px 12px; font-weight:900; background:#fff; font-size:13px; }
        .btn.primary { background:var(--brand); border-color:var(--brand); color:#fff; }
        .badge { display:inline-flex; border-radius:999px; padding:5px 9px; font-size:12px; font-weight:900; background:#edf7f1; color:var(--green); }
        .badge.missing { background:#fff1f1; color:var(--red); }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:var(--green); border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:var(--red); border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:980px) { .brand-row { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .main-nav { overflow:auto; } .head { display:block; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a>
        <div class="brand-center">E-LEARNING</div>
        <div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div>
    </div>
    <nav class="main-nav">
        <?php foreach (($menus ?? []) as $menu): ?>
            <div class="nav-item <?= str_starts_with($path ?? '', $menu['path'] ?? '') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url($menu['path']) ?>"><span class="nav-icon"></span><?= esc($menu['name']) ?></a>
                <?php if (! empty($menu['children'])): ?>
                    <div class="dropdown">
                        <?php foreach ($menu['children'] as $child): ?>
                            <a href="<?= site_url($child['path']) ?>"><span class="nav-icon"></span><?= esc($child['name']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</header>

<main class="page">
    <?php if (session()->getFlashdata('module_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('module_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('module_error')): ?><div class="error"><?= esc(session()->getFlashdata('module_error')) ?></div><?php endif; ?>
    <section class="panel">
        <div class="head">
            <div>
                <div class="kicker"><?= $canSeeAll ? 'Certificate Management' : 'My Certificates' ?></div>
                <h1><?= esc($title) ?></h1>
                <div class="muted"><?= count($certificates ?? []) ?> certificate records</div>
            </div>
        </div>
        <?php if($canSeeAll): ?><form method="post" action="<?= site_url('certificate/bulk-regenerate') ?>"><?= csrf_field() ?><div style="padding:0 22px 16px"><button class="btn" type="submit">Regenerate selected</button></div><?php endif ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <?php if($canSeeAll): ?><th><input type="checkbox" onclick="document.querySelectorAll('[name=&quot;certificate_ids[]&quot;]').forEach(x=>x.checked=this.checked)"></th><?php endif ?><th>Issued</th>
                        <th>Course</th>
                        <th>Learner</th>
                        <th>Company</th>
                        <th>File</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($certificates ?? []) as $certificate): ?>
                        <tr>
                            <?php if($canSeeAll): ?><td><input type="checkbox" name="certificate_ids[]" value="<?= esc($certificate['cert_id']) ?>"></td><?php endif ?>
                            <td><?= esc($certificate['cert_date']) ?><div class="muted"><?= esc($certificate['cert_createtime']) ?></div></td>
                            <td><strong><?= esc($certificate['course_title']) ?></strong><div class="muted"><?= esc($certificate['ccode'] ?? '-') ?></div></td>
                            <td><?= esc($certificate['learner_name']) ?><div class="muted"><?= esc($certificate['emp_c'] ?? '-') ?></div></td>
                            <td><?= esc($certificate['company_name']) ?></td>
                            <td><span class="badge <?= empty($certificate['file_exists']) ? 'missing' : '' ?>"><?= empty($certificate['file_exists']) ? 'Missing file' : 'Ready' ?></span><div class="muted"><?= esc($certificate['cert_file']) ?></div></td>
                            <td>
                                <?php if (! empty($certificate['file_exists'])): ?><a class="btn primary" href="<?= site_url('certificate/download/' . $certificate['cert_id']) ?>">Download</a><?php endif; ?>
                                <?php if (! empty($canSeeAll)): ?>
                                    <button class="btn" type="submit" formaction="<?= site_url('certificate/regenerate/' . $certificate['cert_id']) ?>">Regenerate</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($certificates)): ?>
                        <tr><td colspan="<?= $canSeeAll ? 7 : 6 ?>" class="muted">No certificates found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($canSeeAll): ?></form><?php endif ?>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
