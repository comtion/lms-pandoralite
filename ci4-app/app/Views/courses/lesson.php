<?php
$course = $lesson['course'] ?? [];
$enrollment = $lesson['enrollment'] ?? null;
$tracking = $lesson['tracking'] ?? null;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($lesson['title']) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --green:#21b36b; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 44px; }
        .back { display:inline-flex; border:1px solid var(--line); border-radius:7px; padding:10px 14px; font-weight:800; background:#fff; margin-bottom:16px; }
        .layout { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:20px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; padding:20px; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:7px 0 10px; font-size:32px; line-height:1.2; }
        h2 { margin:0 0 14px; font-size:20px; }
        .muted { color:var(--muted); line-height:1.6; font-size:14px; }
        .player { min-height:360px; display:grid; place-items:center; border:1px solid var(--line); border-radius:8px; background:#111827; color:#fff; overflow:hidden; margin-top:18px; }
        video { width:100%; max-height:520px; background:#111827; }
        iframe { width:100%; height:560px; border:0; background:#fff; }
        .asset { border:1px solid var(--line); border-radius:8px; padding:13px; margin-bottom:10px; }
        .chip { display:inline-flex; border-radius:999px; background:#f3f4f6; color:#374151; padding:6px 10px; font-size:12px; font-weight:800; }
        .chip.good { background:#eaf8f1; color:#087443; }
        .btn { width:100%; border:1px solid var(--brand); background:var(--brand); color:#fff; border-radius:7px; padding:12px 15px; font-weight:900; cursor:pointer; font-size:14px; }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:980px) { .brand-row, .layout { grid-template-columns:1fr; } .brand-center, .brand-actions { text-align:left; justify-content:flex-start; } iframe { height:420px; } }
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
</header>

<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <a class="back" href="<?= site_url('coursemain/detail/' . ($lesson['cos_id'] ?? 0)) ?>">Back to course detail</a>
    <div class="layout">
        <section class="panel">
            <div class="kicker"><?= esc($course['title'] ?? 'Course') ?></div>
            <h1><?= esc($lesson['title']) ?></h1>
            <p class="muted"><?= esc($lesson['description'] ?: '-') ?></p>

            <div class="player">
                <?php if (! empty($lesson['media'][0]['url'])): ?>
                    <?php if (($lesson['media'][0]['type'] ?? '') === 'video'): ?>
                        <video controls src="<?= esc($lesson['media'][0]['url']) ?>"></video>
                    <?php else: ?>
                        <div>
                            <strong><?= esc($lesson['media'][0]['title']) ?></strong>
                            <p class="muted" style="color:#d1d5db">Media file: <?= esc($lesson['media'][0]['video'] ?? '-') ?></p>
                        </div>
                    <?php endif; ?>
                <?php elseif (! empty($lesson['scorm']['url'])): ?>
                    <iframe src="<?= esc($lesson['scorm']['url']) ?>"></iframe>
                <?php else: ?>
                    <div>
                        <strong>No playable media found</strong>
                        <p class="muted" style="color:#d1d5db">Use the assets panel for documents or SCORM references.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <aside>
            <section class="panel" style="margin-bottom:16px">
                <h2>Progress</h2>
                <p><span class="chip <?= ($tracking['learn_status'] ?? '') === '2' ? 'good' : '' ?>"><?= ($tracking['learn_status'] ?? '') === '2' ? 'Completed' : 'In progress' ?></span></p>
                <p class="muted">Enrollment ID: <?= esc($enrollment['cosen_id'] ?? '-') ?></p>
                <form method="post" action="<?= site_url('coursemain/lesson/' . $lesson['les_id'] . '/complete') ?>"><?= csrf_field() ?>
                    <button class="btn" type="submit">Mark Lesson Complete</button>
                </form>
            </section>

            <section class="panel" style="margin-bottom:16px">
                <h2>Media</h2>
                <?php foreach (($lesson['media'] ?? []) as $media): ?>
                    <div class="asset"><strong><?= esc($media['title']) ?></strong><div class="muted"><?= esc($media['type'] ?: '-') ?> · <?= esc($media['video'] ?: '-') ?></div></div>
                <?php endforeach; ?>
                <?php if (empty($lesson['media'])): ?><p class="muted">No media files.</p><?php endif; ?>
            </section>

            <section class="panel" style="margin-bottom:16px">
                <h2>Documents</h2>
                <?php foreach (($lesson['documents'] ?? []) as $document): ?>
                    <div class="asset"><strong><?= esc($document['title']) ?></strong><div class="muted"><?= esc($document['path_file'] ?: '-') ?></div></div>
                <?php endforeach; ?>
                <?php if (empty($lesson['documents'])): ?><p class="muted">No documents.</p><?php endif; ?>
            </section>

            <section class="panel">
                <h2>SCORM</h2>
                <?php if (! empty($lesson['scorm'])): ?>
                    <div class="asset"><strong>SCORM package</strong><div class="muted"><?= esc($lesson['scorm']['path'] ?: '-') ?></div></div>
                <?php else: ?>
                    <p class="muted">No SCORM package.</p>
                <?php endif; ?>
            </section>
        </aside>
    </div>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
