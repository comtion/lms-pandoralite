<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
    <link href="<?= base_url('css/report-suite.css?v=20260811-1') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar"><div class="brand-row"><a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a><div class="brand-center">E-LEARNING</div><div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></div></div></header>
<main class="page report-page">
    <div class="head report-head">
        <div class="report-title-wrap"><div class="report-title-icon"><i class="bi bi-activity"></i></div><div><div class="kicker"><?= esc($title_main ?: 'Reports') ?></div><h1><?= esc($title) ?></h1><p class="sub report-description">Inspect detailed SCORM progress, scores, locations, session time, and tracking values.</p></div></div>
        <div class="report-head-actions"><a class="btn" href="<?= site_url('report/learnerReport') ?>"><i class="bi bi-arrow-left"></i> Learning report</a><a class="btn primary" href="<?= site_url('report/scormTracking/export').'?'.http_build_query($filters) ?>"><i class="bi bi-file-earmark-excel"></i> Export XLSX</a></div>
    </div>
    <section class="metrics report-metrics">
        <div class="metric report-metric"><div class="report-metric__top"><span>Tracking records</span><i class="bi bi-database report-metric__icon"></i></div><strong><?= count($rows ?? []) ?></strong></div>
        <div class="metric report-metric"><div class="report-metric__top"><span>Companies</span><i class="bi bi-buildings report-metric__icon"></i></div><strong><?= count($companies ?? []) ?></strong></div>
        <div class="metric report-metric"><div class="report-metric__top"><span>Courses available</span><i class="bi bi-journal-bookmark report-metric__icon"></i></div><strong><?= count($courses ?? []) ?></strong></div>
        <div class="metric report-metric"><div class="report-metric__top"><span>Active filters</span><i class="bi bi-funnel report-metric__icon"></i></div><strong><?= count(array_filter($filters ?? [], static fn($v) => $v !== '')) ?></strong></div>
    </section>
    <section class="panel report-panel">
        <div class="toolbar report-toolbar"><div><p class="report-toolbar__title">Report filters</p><p class="report-toolbar__hint">Narrow results by company or course.</p></div></div>
        <form class="report-filters" method="get">
            <div class="report-filter"><label for="com_id">Company</label><select id="com_id" name="com_id"><option value="">All companies</option><?php foreach($companies as $c): ?><option value="<?= esc($c['com_id']) ?>" <?= (string)$filters['com_id']===(string)$c['com_id']?'selected':'' ?>><?= esc($c['com_code'].' - '.($c['com_name_eng'] ?: $c['com_name_th'] ?? '')) ?></option><?php endforeach ?></select></div>
            <div class="report-filter"><label for="cos_id">Course</label><select id="cos_id" name="cos_id"><option value="">All courses</option><?php foreach($courses as $c): ?><option value="<?= esc($c['cos_id']) ?>" <?= (string)$filters['cos_id']===(string)$c['cos_id']?'selected':'' ?>><?= esc($c['ccode'].' - '.($c['cname_eng'] ?: $c['cname_th'] ?? '')) ?></option><?php endforeach ?></select></div>
            <div class="report-filter-actions"><button class="btn primary" type="submit"><i class="bi bi-search"></i> Apply filters</button><a class="btn" href="<?= site_url('report/scormTracking') ?>"><i class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
        </form>
        <div class="table-wrap report-table-wrap"><table class="report-table"><thead><tr><th>Learner</th><th>Course</th><th>Lesson</th><th>SCORM</th><th>Status</th><th>Score</th><th>Location</th><th>Session time</th><th>Values</th></tr></thead><tbody>
        <?php foreach(($rows ?? []) as $r): ?><?php $status = strtolower((string)($r['status'] ?? '')); $badge = str_contains($status,'complete') || str_contains($status,'pass') ? 'complete' : (str_contains($status,'progress') ? 'progress' : 'pending'); ?><tr><td><span class="report-primary"><?= esc($r['learner_name']) ?></span><span class="report-secondary"><?= esc($r['emp_c']) ?></span></td><td><span class="report-primary"><?= esc($r['course_title']) ?></span><span class="report-secondary"><?= esc($r['ccode']) ?></span></td><td><?= esc($r['lesson_title']) ?></td><td><?= esc($r['scm_id']) ?></td><td><span class="badge report-badge--<?= $badge ?>"><?= esc($r['status'] ?: 'Unknown') ?></span></td><td class="report-score"><?= esc($r['score']) ?></td><td><?= esc($r['location']) ?></td><td><?= esc($r['session_time']) ?></td><td><span class="report-json" title="<?= esc($r['updated_values']) ?>"><?= esc($r['updated_values']) ?></span></td></tr><?php endforeach ?>
        <?php if(empty($rows)): ?><tr><td colspan="9" class="report-empty"><div class="report-empty__icon"><i class="bi bi-inbox"></i></div><strong>No tracking records found</strong><span class="muted">Try changing the selected filters.</span></td></tr><?php endif ?>
        </tbody></table></div>
    </section>
</main>
<script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body></html>
