<?php
$enrollment = $course['enrollment'] ?? null;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($course['title']) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --green:#21b36b; --yellow:#d8a300; }
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
        .hero { display:grid; grid-template-columns:minmax(0,1fr) 360px; gap:22px; align-items:stretch; margin-bottom:20px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; }
        .intro { padding:26px; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:8px 0 10px; font-size:34px; line-height:1.15; letter-spacing:0; }
        .desc { color:var(--muted); line-height:1.65; font-size:15px; }
        .cover { min-height:300px; background:#f3f4f6 center/contain no-repeat; border-radius:8px; border:1px solid var(--line); }
        .stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin:18px 0; }
        .stat { background:#fff; border:1px solid var(--line); border-radius:8px; padding:16px; }
        .stat span { display:block; color:var(--muted); font-size:12px; margin-bottom:7px; }
        .stat strong { font-size:20px; }
        .content-grid { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:20px; }
        .section { padding:20px; margin-bottom:16px; }
        .section h2 { margin:0 0 14px; font-size:20px; }
        .lesson { border:1px solid var(--line); border-radius:8px; padding:15px; margin-bottom:12px; background:#fff; }
        .lesson-head { display:flex; justify-content:space-between; gap:12px; margin-bottom:8px; }
        .lesson-title { font-weight:900; }
        .muted { color:var(--muted); font-size:13px; line-height:1.55; }
        .chips { display:flex; flex-wrap:wrap; gap:8px; margin-top:12px; }
        .chip { border-radius:999px; background:#f3f4f6; color:#374151; padding:6px 10px; font-size:12px; font-weight:800; }
        .chip.good { background:#eaf8f1; color:#087443; }
        .chip.warn { background:#fff7dc; color:#8a6300; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        td { border-bottom:1px solid var(--line); padding:11px 0; vertical-align:top; }
        td:first-child { color:var(--muted); width:42%; }
        .back { display:inline-flex; border:1px solid var(--line); border-radius:7px; padding:10px 14px; font-weight:800; background:#fff; margin-bottom:16px; }
        .action-row { display:flex; flex-wrap:wrap; gap:10px; margin-top:18px; }
        .btn { border:1px solid var(--line); border-radius:7px; padding:11px 15px; font-weight:900; background:#fff; cursor:pointer; font-size:14px; }
        .btn.primary { background:var(--brand); border-color:var(--brand); color:#fff; }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:980px) {
            .brand-row, .hero, .content-grid { grid-template-columns:1fr; }
            .brand-center, .brand-actions { text-align:left; justify-content:flex-start; }
            .main-nav { overflow:auto; }
            .stats { grid-template-columns:repeat(2,1fr); }
        }
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
            <div class="nav-item <?= ($menu['path'] ?? '') === 'course' ? 'active' : '' ?>">
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
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <a class="back" href="<?= site_url('coursemain/all_courses') ?>">Back to courses</a>
    <section class="hero">
        <div class="panel intro">
            <div class="kicker"><?= esc($course['ccode'] ?: 'Course') ?></div>
            <h1><?= esc($course['title']) ?></h1>
            <p class="desc"><?= esc($course['full_description'] ?: $course['description'] ?: '-') ?></p>
            <div class="chips">
                <span class="chip good"><?= esc($course['status_label']) ?></span>
                <span class="chip"><?= esc($course['period_label']) ?></span>
                <span class="chip"><?= esc($course['company_name'] ?: '-') ?></span>
            </div>
            <div class="action-row">
                <?php if (empty($enrollment)): ?>
                    <form method="post" action="<?= site_url('coursemain/enroll/' . $course['cos_id']) ?>"><button class="btn primary" type="submit">Enroll Course</button></form>
                <?php else: ?>
                    <form method="post" action="<?= site_url('coursemain/start/' . $course['cos_id']) ?>"><button class="btn primary" type="submit">Start Learning</button></form>
                    <a class="btn" href="<?= site_url('coursemain/my_course') ?>">My Course</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="cover" style="background-image:url('<?= esc($course['image_url']) ?>')"></div>
    </section>

    <section class="stats">
        <div class="stat"><span>Lessons</span><strong><?= count($course['lessons'] ?? []) ?></strong></div>
        <div class="stat"><span>Tests</span><strong><?= count($course['quizzes'] ?? []) ?></strong></div>
        <div class="stat"><span>Surveys</span><strong><?= count($course['surveys'] ?? []) ?></strong></div>
        <div class="stat"><span>Enrollment</span><strong><?= esc($enrollment['learning_label'] ?? 'Not enrolled') ?></strong></div>
    </section>

    <div class="content-grid">
        <section class="panel section">
            <h2>Learning Structure</h2>
            <?php if (empty($course['lessons'])): ?>
                <p class="muted">No lessons found for this course.</p>
            <?php endif; ?>
            <?php foreach (($course['lessons'] ?? []) as $lesson): ?>
                <article class="lesson">
                    <div class="lesson-head">
                        <div>
                            <div class="lesson-title"><?= esc($lesson['les_sequences']) ?>. <?= esc($lesson['title']) ?></div>
                            <div class="muted"><?= esc($lesson['description'] ?: '-') ?></div>
                        </div>
                        <span class="chip <?= ($lesson['status_label'] ?? '') === 'Completed' ? 'good' : '' ?>"><?= esc($lesson['status_label']) ?></span>
                    </div>
                    <div class="chips">
                        <span class="chip">Media <?= count($lesson['media'] ?? []) ?></span>
                        <span class="chip">Documents <?= count($lesson['documents'] ?? []) ?></span>
                        <?php if (! empty($lesson['scorm'])): ?><span class="chip warn">SCORM</span><?php endif; ?>
                        <?php if (! empty($enrollment)): ?><a class="chip good" href="<?= site_url('coursemain/lesson/' . $lesson['les_id']) ?>">Open lesson</a><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <aside>
            <section class="panel section">
                <h2>Course Facts</h2>
                <table>
                    <tr><td>Company</td><td><?= esc($course['company_name'] ?: '-') ?></td></tr>
                    <tr><td>Seats</td><td><?= esc($course['seat_label']) ?></td></tr>
                    <tr><td>Enrolled</td><td><?= esc($course['enrolled_count'] ?? 0) ?></td></tr>
                    <tr><td>Goal Score</td><td><?= esc($course['goal_score'] ?? '-') ?></td></tr>
                    <tr><td>Hours</td><td><?= esc($course['cos_hour'] ?? '-') ?></td></tr>
                </table>
            </section>

            <section class="panel section">
                <h2>Tests & Surveys</h2>
                <?php foreach (($course['quizzes'] ?? []) as $quiz): ?>
                    <div class="lesson">
                        <strong><?= esc($quiz['title']) ?></strong>
                        <div class="muted"><?= esc($quiz['type_label']) ?> · Pass <?= esc($quiz['quiz_maxscore']) ?>%</div>
                        <?php if (! empty($enrollment)): ?>
                            <div class="chips"><a class="chip good" href="<?= site_url('coursemain/quiz/' . $quiz['qiz_id']) ?>">Open quiz</a></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php foreach (($course['surveys'] ?? []) as $survey): ?>
                    <div class="lesson">
                        <strong><?= esc($survey['title']) ?></strong>
                        <div class="muted">Survey</div>
                        <?php if (! empty($enrollment)): ?>
                            <div class="chips"><a class="chip good" href="<?= site_url('coursemain/survey/' . $survey['sv_id']) ?>">Open survey</a></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($course['quizzes']) && empty($course['surveys'])): ?>
                    <p class="muted">No tests or surveys found.</p>
                <?php endif; ?>
            </section>

            <section class="panel section">
                <h2>Documents</h2>
                <?php foreach (($course['documents'] ?? []) as $document): ?>
                    <div class="lesson"><strong><?= esc($document['title']) ?></strong><div class="muted"><?= esc($document['path_file'] ?? '-') ?></div></div>
                <?php endforeach; ?>
                <?php if (empty($course['documents'])): ?><p class="muted">No course documents found.</p><?php endif; ?>
            </section>
        </aside>
    </div>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
