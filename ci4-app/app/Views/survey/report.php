<?php $survey = $report['survey']; ?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; }
        * { box-sizing:border-box; } body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial,"Helvetica Neue",sans-serif; } a { color:inherit; text-decoration:none; }
        .topbar { background:#fff; border-bottom:1px solid var(--line); } .brand-row { max-width:1100px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; } .brand-mark { font-weight:900; color:var(--brand); font-size:20px; } .brand-center { text-align:center; font-size:28px; font-weight:900; } .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; } .logout { color:#111827; font-weight:800; }
        .page { max-width:1100px; margin:0 auto; padding:28px 22px 42px; } .head { display:flex; justify-content:space-between; gap:16px; align-items:flex-end; margin-bottom:18px; } .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; } h1 { margin:5px 0 0; font-size:30px; } .sub { color:var(--muted); font-size:13px; margin:7px 0 0; }
        .btn { display:inline-flex; border:1px solid var(--line); background:#fff; border-radius:7px; padding:10px 14px; font-weight:900; } .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:18px; margin-bottom:18px; } .summary { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:18px; } .metric { background:#fff; border:1px solid var(--line); border-radius:8px; padding:16px; } .metric span { color:var(--muted); font-size:12px; display:block; } .metric strong { font-size:26px; }
        table { width:100%; border-collapse:collapse; font-size:14px; } th,td { text-align:left; padding:12px 10px; border-bottom:1px solid var(--line); vertical-align:top; } th { background:#fafafa; color:var(--muted); font-size:12px; text-transform:uppercase; } .bar { height:8px; background:#f1f2f4; border-radius:999px; overflow:hidden; min-width:110px; } .fill { height:100%; background:var(--brand); }
        @media (max-width:850px) { .brand-row,.summary { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .head { display:block; } .head .btn { margin-top:12px; } }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
    <link href="<?= base_url('css/report-suite.css?v=20260811-1') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar"><div class="brand-row"><a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a><div class="brand-center">E-LEARNING</div><div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div></div></header>
<main class="page report-page">
    <div class="head">
        <div><div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div><h1><?= esc($survey['title']) ?></h1><p class="sub"><?= esc(($survey['ccode'] ?: '-') . ' - ' . ($survey['course_title'] ?? '-')) ?></p></div>
        <div><a class="btn" href="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/edit') ?>">Edit</a> <a class="btn primary" href="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/report/export') ?>">Export XLSX</a></div>
    </div>
    <section class="summary report-metrics">
        <div class="metric"><span>Questions</span><strong><?= esc(count($report['summary'])) ?></strong></div>
        <div class="metric"><span>Submissions</span><strong><?= esc(count($report['submissions'])) ?></strong></div>
        <div class="metric"><span>Status</span><strong><?= (string) $survey['sv_status'] === '1' ? 'Active' : 'Inactive' ?></strong></div>
    </section>
    <section class="panel report-panel">
        <table>
            <thead><tr><th>Question</th><th>Average</th><th>Rating Distribution</th></tr></thead>
            <tbody>
            <?php foreach (($report['summary'] ?? []) as $item): ?>
                <tr>
                    <td><strong><?= esc($item['question']['heading']) ?></strong><br><span class="sub"><?= esc($item['responses']) ?> responses</span></td>
                    <td><?= esc(number_format((float) $item['average'], 2)) ?></td>
                    <td><?php foreach ($item['ratings'] as $rate => $count): ?><?php $pct = $item['responses'] > 0 ? (int) round($count * 100 / $item['responses']) : 0; ?><div style="display:grid;grid-template-columns:42px 1fr 38px;gap:8px;align-items:center;margin:5px 0"><span><?= esc($rate) ?></span><div class="bar"><div class="fill" style="width:<?= esc($pct) ?>%"></div></div><span><?= esc($count) ?></span></div><?php endforeach; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($report['summary'])): ?><tr><td colspan="3">No survey questions found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
