<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 44px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; margin-bottom:16px; }
        .head { padding:22px; display:flex; justify-content:space-between; gap:18px; align-items:flex-end; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:6px 0 0; font-size:30px; line-height:1.2; }
        .filters { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)) auto auto; gap:10px; padding:0 22px 12px; }
        .export-row { display:flex; gap:10px; flex-wrap:wrap; padding:0 22px 22px; }
        select,input { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px; background:#fff; }
        .btn { border:1px solid var(--line); border-radius:7px; padding:10px 13px; font-weight:900; background:#fff; cursor:pointer; white-space:nowrap; }
        .btn.primary { background:var(--brand); border-color:var(--brand); color:#fff; }
        .table-wrap { overflow:auto; border-top:1px solid var(--line); }
        table { width:100%; border-collapse:collapse; min-width:1100px; font-size:14px; }
        th,td { border-bottom:1px solid var(--line); padding:12px 14px; text-align:left; vertical-align:top; }
        th { color:#42526b; background:#fafafa; font-size:12px; text-transform:uppercase; }
        .muted { color:var(--muted); font-size:13px; }
        @media (max-width:980px) { .brand-row,.filters { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .head { display:block; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a>
        <div class="brand-center">E-LEARNING</div>
        <div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a href="<?= site_url('logout') ?>">Logout</a></div>
    </div>
</header>
<main class="page">
    <section class="panel">
        <div class="head">
            <div>
                <div class="kicker">Report</div>
                <h1><?= esc($title) ?></h1>
                <div class="muted"><?= count($rows ?? []) ?> records</div>
            </div>
        </div>
        <form class="filters" method="get" action="<?= site_url('report/learnerReport') ?>">
            <select name="com_id"><option value="">All companies</option><?php foreach ($companies as $company): ?><option value="<?= esc($company['com_id']) ?>" <?= (string) $filters['com_id'] === (string) $company['com_id'] ? 'selected' : '' ?>><?= esc($company['com_code'] . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?></option><?php endforeach; ?></select>
            <select name="cos_id"><option value="">All courses</option><?php foreach ($courses as $course): ?><option value="<?= esc($course['cos_id']) ?>" <?= (string) $filters['cos_id'] === (string) $course['cos_id'] ? 'selected' : '' ?>><?= esc($course['ccode'] . ' - ' . ($course['cname_eng'] ?: $course['cname_th'])) ?></option><?php endforeach; ?></select>
            <select name="status"><option value="">All status</option><option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Not started</option><option value="2" <?= $filters['status'] === '2' ? 'selected' : '' ?>>In progress</option><option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Completed</option></select>
            <input type="date" name="date_start" value="<?= esc($filters['date_start']) ?>">
            <input type="date" name="date_end" value="<?= esc($filters['date_end']) ?>">
            <button class="btn primary" type="submit">Filter</button>
            <a class="btn" href="<?= site_url('report/learnerReport/export?' . http_build_query($filters)) ?>">Export XLSX</a>
        </form>
        <div class="export-row">
            <a class="btn" href="<?= site_url('report/courseSummary/export?' . http_build_query($filters)) ?>">Course Summary</a>
            <a class="btn" href="<?= site_url('report/scormTracking/export?' . http_build_query($filters)) ?>">SCORM Tracking</a>
            <a class="btn" href="<?= site_url('report/certificateIssued/export?' . http_build_query($filters)) ?>">Certificates</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Company</th><th>Employee</th><th>Learner</th><th>Course</th><th>Status</th><th>Score</th><th>Grade</th><th>Started</th><th>Finished</th></tr></thead>
                <tbody>
                    <?php foreach (($rows ?? []) as $row): ?>
                        <tr>
                            <td><?= esc($row['company_name']) ?><div class="muted"><?= esc($row['com_code']) ?></div></td>
                            <td><?= esc($row['emp_c']) ?></td>
                            <td><?= esc($row['learner_name']) ?></td>
                            <td><strong><?= esc($row['course_title']) ?></strong><div class="muted"><?= esc($row['ccode']) ?></div></td>
                            <td><?= esc($row['status_label']) ?></td>
                            <td><?= esc($row['cosen_score_per']) ?>%</td>
                            <td><?= esc($row['cosen_grade']) ?></td>
                            <td><?= str_starts_with((string) $row['cosen_firsttime'], '0000-00-00') ? '' : esc($row['cosen_firsttime']) ?></td>
                            <td><?= str_starts_with((string) $row['cosen_finishtime'], '0000-00-00') ? '' : esc($row['cosen_finishtime']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($rows)): ?><tr><td colspan="9" class="muted">No records found.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
