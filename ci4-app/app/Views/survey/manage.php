<?php $filters = $filters ?? []; ?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --good:#087443; --warn:#8a6300; }
        * { box-sizing:border-box; } body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial,"Helvetica Neue",sans-serif; } a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); } .brand-center { text-align:center; font-size:28px; font-weight:900; } .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; } .logout { color:#111827; font-weight:800; }
        .main-nav { max-width:1250px; margin:0 auto; display:flex; gap:2px; padding:0 22px; overflow:auto; } .nav-link { display:flex; align-items:center; gap:9px; min-height:54px; padding:0 13px; color:#5b6573; font-weight:800; font-size:14px; border-bottom:3px solid transparent; white-space:nowrap; } .nav-link.active,.nav-link:hover { color:var(--brand); border-color:var(--brand); } .nav-icon { width:10px; height:10px; border-radius:2px; background:currentColor; display:inline-block; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 42px; } .page-head { display:flex; justify-content:space-between; gap:18px; align-items:flex-end; margin-bottom:18px; } .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; } h1 { margin:5px 0 0; font-size:30px; } .sub { color:var(--muted); font-size:13px; margin:7px 0 0; }
        .btn { display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:10px 14px; font-weight:900; font-size:13px; cursor:pointer; } .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:var(--good); border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; } .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:18px; } .filters { display:grid; grid-template-columns:2fr 1fr 1.2fr auto; gap:10px; margin-bottom:16px; } input,select { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 11px; font-size:14px; background:#fff; }
        table { width:100%; border-collapse:collapse; font-size:14px; } th,td { text-align:left; padding:13px 12px; border-bottom:1px solid var(--line); vertical-align:top; } th { background:#fafafa; color:var(--muted); font-size:12px; text-transform:uppercase; } .table-wrap { overflow:auto; }
        .badge { display:inline-flex; border-radius:999px; padding:5px 9px; font-size:12px; font-weight:900; background:#f3f4f6; color:#374151; } .badge.good { background:#eaf8f1; color:var(--good); } .badge.warn { background:#fff7dc; color:var(--warn); } .row-actions { display:flex; gap:7px; flex-wrap:wrap; }
        @media (max-width:900px) { .brand-row,.filters { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .page-head { display:block; } .page-head .btn { margin-top:12px; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row"><a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a><div class="brand-center">E-LEARNING</div><div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div></div>
    <nav class="main-nav">
        <?php foreach (($menus ?? []) as $menu): ?><a class="nav-link <?= ($menu['path'] ?? '') === $path ? 'active' : '' ?>" href="<?= site_url($menu['path']) ?>"><span class="nav-icon"></span><?= esc($menu['name']) ?></a><?php endforeach; ?>
    </nav>
</header>
<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <div class="page-head">
        <div><div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div><h1><?= esc($title) ?></h1><p class="sub">Create and monitor course satisfaction surveys migrated to CI4.</p></div>
        <a class="btn primary" href="<?= site_url('managecourse/surveys/create' . (! empty($filters['cos_id']) ? '?cos_id=' . (int) $filters['cos_id'] : '')) ?>">Create Survey</a>
    </div>
    <section class="panel">
        <form class="filters" method="get" action="<?= site_url('managecourse/surveys') ?>">
            <select name="cos_id"><option value="">All courses</option><?php foreach (($courses ?? []) as $course): ?><option value="<?= esc($course['cos_id']) ?>" <?= (string) ($filters['cos_id'] ?? '') === (string) $course['cos_id'] ? 'selected' : '' ?>><?= esc(($course['ccode'] ?: '-') . ' - ' . $course['title']) ?></option><?php endforeach; ?></select>
            <select name="status"><option value="">All status</option><option value="1" <?= (string) ($filters['status'] ?? '') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) ($filters['status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select>
            <input name="keyword" value="<?= esc($filters['keyword'] ?? '') ?>" placeholder="Search survey or course">
            <button class="btn" type="submit">Filter</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Survey</th><th>Course</th><th>Period</th><th>Questions</th><th>Responses</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach (($surveys ?? []) as $survey): ?>
                    <tr>
                        <td><strong><?= esc($survey['title']) ?></strong><br><span class="sub">#<?= esc($survey['sv_id']) ?></span></td>
                        <td><?= esc(($survey['ccode'] ?: '-') . ' - ' . $survey['course_title']) ?></td>
                        <td><?= esc($survey['period_label']) ?></td>
                        <td><?= esc($survey['question_count']) ?></td>
                        <td><?= esc($survey['submission_count']) ?></td>
                        <td><span class="badge <?= (string) $survey['sv_status'] === '1' ? 'good' : 'warn' ?>"><?= (string) $survey['sv_status'] === '1' ? 'Active' : 'Inactive' ?></span></td>
                        <td><div class="row-actions"><a class="badge" href="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/edit') ?>">Edit</a><a class="badge good" href="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/report') ?>">Report</a></div></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($surveys)): ?><tr><td colspan="7">No surveys found.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
