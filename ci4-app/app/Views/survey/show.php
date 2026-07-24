<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($survey['title']) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --green:#087443; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1180px; margin:0 auto; min-height:72px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .page { max-width:980px; margin:0 auto; padding:28px 22px 44px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; padding:22px; margin-bottom:16px; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:7px 0 10px; font-size:32px; line-height:1.2; }
        .muted { color:var(--muted); line-height:1.6; font-size:14px; }
        .question { border:1px solid var(--line); border-radius:8px; background:#fff; padding:18px; margin-bottom:14px; }
        .question-title { font-weight:900; line-height:1.5; margin-bottom:6px; }
        .scale { display:grid; grid-template-columns:repeat(5, minmax(0, 1fr)); gap:8px; margin:14px 0; }
        .scale label { border:1px solid var(--line); border-radius:8px; padding:11px 8px; text-align:center; cursor:pointer; font-weight:900; background:#fff; }
        .scale input { display:block; margin:0 auto 7px; }
        textarea { width:100%; border:1px solid var(--line); border-radius:8px; padding:12px; min-height:92px; font-size:14px; }
        .actions { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-top:18px; }
        .btn { border:1px solid var(--line); border-radius:7px; padding:12px 16px; font-weight:900; background:#fff; cursor:pointer; font-size:14px; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:var(--green); border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:780px) { .brand-row { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .scale { grid-template-columns:1fr; } .actions { display:block; } .actions .btn { width:100%; margin-top:10px; } }
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
    <?php if (session()->getFlashdata('course_error')): ?>
        <?php $error = session()->getFlashdata('course_error'); ?>
        <div class="error"><?= esc(is_array($error) ? ($error['message'] ?? 'Survey error') : $error) ?></div>
    <?php endif; ?>
    <?php if (! empty($result)): ?>
        <div class="notice"><?= esc($result['message'] ?? 'Survey submitted.') ?></div>
    <?php endif; ?>

    <section class="panel">
        <div class="kicker">Course Survey</div>
        <h1><?= esc($survey['title']) ?></h1>
        <p class="muted"><?= esc($survey['explanation'] ?: '-') ?></p>
        <?php if (! empty($survey['last_submission'])): ?>
            <p class="muted">Last submitted: <?= esc($survey['last_submission']['qnu_datetime']) ?></p>
        <?php endif; ?>
    </section>

    <form method="post" action="<?= site_url('coursemain/survey/' . $survey['sv_id'] . '/submit') ?>"><?= csrf_field() ?>
        <?php foreach (($survey['questions'] ?? []) as $index => $question): ?>
            <?php $last = $survey['last_submission']['details'][(int) $question['svde_id']] ?? []; ?>
            <section class="question">
                <div class="question-title"><?= ($index + 1) ?>. <?= esc($question['heading']) ?></div>
                <p class="muted"><?= esc($question['detail'] ?: '-') ?></p>
                <div class="scale">
                    <?php for ($score = 5; $score >= 1; $score--): ?>
                        <label>
                            <input type="radio" name="answers[<?= esc($question['svde_id']) ?>]" value="<?= $score ?>" <?= (string) ($last['qnude_var'] ?? '') === (string) $score ? 'checked' : '' ?>>
                            <?= $score ?>
                        </label>
                    <?php endfor; ?>
                </div>
                <textarea name="suggestions[<?= esc($question['svde_id']) ?>]" placeholder="Suggestion"><?= esc($last['qnude_suggestion'] ?? '') ?></textarea>
            </section>
        <?php endforeach; ?>

        <?php if ((string) ($survey['sv_suggestion_status'] ?? '') === '1'): ?>
            <section class="panel">
                <div class="question-title">Additional Suggestion</div>
                <textarea name="qnu_suggestion"><?= esc($survey['last_submission']['qnu_suggestion'] ?? '') ?></textarea>
            </section>
        <?php endif; ?>

        <div class="actions">
            <a class="btn" href="<?= site_url('coursemain/detail/' . $survey['cos_id']) ?>">Back to Course</a>
            <?php if (! empty($survey['questions'])): ?><button class="btn primary" type="submit">Submit Survey</button><?php endif; ?>
        </div>
    </form>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
