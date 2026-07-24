<?php
$isEdit = ! empty($survey);
$selectedLang = array_filter(explode(',', (string) ($survey['sv_lang'] ?? 'eng')));
$selectedCourseId = (int) ($survey['cos_id'] ?? $defaultCourseId ?? 0);
$dateValue = static function ($value): string {
    $value = (string) $value;
    return $value === '' || str_starts_with($value, '0000-00-00') ? '' : date('Y-m-d\TH:i', strtotime($value));
};
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; }
        * { box-sizing:border-box; } body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial,"Helvetica Neue",sans-serif; } a { color:inherit; text-decoration:none; }
        .topbar { background:#fff; border-bottom:1px solid var(--line); } .brand-row { max-width:1100px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; } .brand-mark { font-weight:900; color:var(--brand); font-size:20px; } .brand-center { text-align:center; font-size:28px; font-weight:900; } .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; } .logout { color:#111827; font-weight:800; }
        .page { max-width:1100px; margin:0 auto; padding:28px 22px 42px; } .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:22px; margin-bottom:18px; } .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; } h1 { margin:5px 0 18px; font-size:30px; } h2 { margin:0 0 14px; font-size:20px; }
        .grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; } .field.full { grid-column:1 / -1; } .field.two { grid-column:span 2; } label { display:block; color:var(--muted); font-size:12px; font-weight:900; margin-bottom:6px; } input,select,textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 11px; font-size:14px; background:#fff; } textarea { min-height:82px; resize:vertical; }
        .checks { display:flex; gap:12px; flex-wrap:wrap; padding:10px 0; } .checks label { color:var(--ink); font-size:14px; margin:0; display:flex; align-items:center; gap:7px; } .checks input { width:auto; }
        .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:16px; flex-wrap:wrap; } .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:10px 14px; font-weight:900; cursor:pointer; } .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; } .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        table { width:100%; border-collapse:collapse; font-size:14px; } th,td { text-align:left; padding:11px 10px; border-bottom:1px solid var(--line); vertical-align:top; } th { background:#fafafa; color:var(--muted); font-size:12px; text-transform:uppercase; } .small { font-size:12px; color:var(--muted); } .inline-form { display:grid; grid-template-columns:1fr 1fr auto auto; gap:8px; align-items:start; }
        @media (max-width:900px) { .brand-row,.grid,.inline-form { grid-template-columns:1fr; } .field.two { grid-column:auto; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar"><div class="brand-row"><a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a><div class="brand-center">E-LEARNING</div><div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div></div></header>
<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <section class="panel">
        <div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div><h1><?= esc($title) ?></h1>
        <form method="post" action="<?= $isEdit ? site_url('managecourse/surveys/' . $survey['sv_id'] . '/update') : site_url('managecourse/surveys/create') ?>"><?= csrf_field() ?>
            <div class="grid">
                <div class="field two"><label>Course</label><select name="cos_id" required><option value="">Select course</option><?php foreach (($courses ?? []) as $course): ?><option value="<?= esc($course['cos_id']) ?>" <?= $selectedCourseId === (int) $course['cos_id'] ? 'selected' : '' ?>><?= esc(($course['ccode'] ?: '-') . ' - ' . $course['title']) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label>Status</label><select name="sv_status"><option value="1" <?= (string) ($survey['sv_status'] ?? '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) ($survey['sv_status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
                <div class="field"><label>English Title</label><input name="sv_title_eng" required value="<?= esc($survey['sv_title_eng'] ?? '') ?>"></div>
                <div class="field"><label>Thai Title</label><input name="sv_title_th" value="<?= esc($survey['sv_title_th'] ?? '') ?>"></div>
                <div class="field"><label>Japanese Title</label><input name="sv_title_jp" value="<?= esc($survey['sv_title_jp'] ?? '') ?>"></div>
                <div class="field full"><label>English Explanation</label><textarea name="sv_explanation_eng"><?= esc($survey['sv_explanation_eng'] ?? '') ?></textarea></div>
                <div class="field"><label>Open Date</label><input type="datetime-local" name="survey_open" value="<?= esc($dateValue($survey['survey_open'] ?? '')) ?>"></div>
                <div class="field"><label>End Date</label><input type="datetime-local" name="survey_end" value="<?= esc($dateValue($survey['survey_end'] ?? '')) ?>"></div>
                <div class="field"><label>Suggestion</label><select name="sv_suggestion_status"><option value="0" <?= (string) ($survey['sv_suggestion_status'] ?? '0') === '0' ? 'selected' : '' ?>>Disabled</option><option value="1" <?= (string) ($survey['sv_suggestion_status'] ?? '') === '1' ? 'selected' : '' ?>>Enabled</option></select></div>
                <div class="field full"><label>Languages</label><div class="checks"><label><input type="checkbox" name="sv_lang[]" value="eng" <?= in_array('eng', $selectedLang, true) ? 'checked' : '' ?>> English</label><label><input type="checkbox" name="sv_lang[]" value="th" <?= in_array('th', $selectedLang, true) ? 'checked' : '' ?>> Thai</label><label><input type="checkbox" name="sv_lang[]" value="jp" <?= in_array('jp', $selectedLang, true) ? 'checked' : '' ?>> Japanese</label></div></div>
            </div>
            <div class="actions"><a class="btn" href="<?= site_url('managecourse/surveys') ?>">Back</a><?php if ($isEdit): ?><a class="btn" href="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/report') ?>">Report</a><?php endif; ?><button class="btn primary" type="submit">Save Survey</button></div>
        </form>
    </section>
    <?php if ($isEdit): ?>
        <section class="panel">
            <h2>Questions</h2>
            <form method="post" action="<?= site_url('managecourse/surveys/' . $survey['sv_id'] . '/questions/create') ?>" class="grid"><?= csrf_field() ?>
                <div class="field"><label>English Heading</label><input name="svde_heading_eng" required></div>
                <div class="field"><label>Thai Heading</label><input name="svde_heading_th"></div>
                <div class="field"><label>Status</label><select name="svde_status"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                <div class="field full"><label>Detail</label><textarea name="svde_detail_eng"></textarea></div>
                <div class="field full actions"><button class="btn primary" type="submit">Add Question</button></div>
            </form>
            <table>
                <thead><tr><th>Question</th><th>Status</th><th>Update</th></tr></thead>
                <tbody>
                <?php foreach (($survey['questions'] ?? []) as $question): ?>
                    <tr>
                        <td><strong><?= esc($question['heading']) ?></strong><br><span class="small">#<?= esc($question['svde_id']) ?> <?= esc($question['detail']) ?></span></td>
                        <td><?= (string) $question['svde_status'] === '1' ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <form class="inline-form" method="post" action="<?= site_url('managecourse/survey-questions/' . $question['svde_id'] . '/update') ?>"><?= csrf_field() ?>
                                <input name="svde_heading_eng" value="<?= esc($question['svde_heading_eng']) ?>" required>
                                <input name="svde_heading_th" value="<?= esc($question['svde_heading_th']) ?>">
                                <select name="svde_status"><option value="1" <?= (string) $question['svde_status'] === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) $question['svde_status'] === '0' ? 'selected' : '' ?>>Inactive</option></select>
                                <button class="btn" type="submit">Save</button>
                            </form>
                            <form method="post" action="<?= site_url('managecourse/survey-questions/' . $question['svde_id'] . '/status') ?>" style="display:inline-block;margin-top:8px"><?= csrf_field() ?>
                                <input type="hidden" name="archive" value="1"><button class="btn" type="submit">Archive</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($survey['questions'])): ?><tr><td colspan="3">No questions yet.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
