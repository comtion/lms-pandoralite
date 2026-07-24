<?php
$isEdit = ! empty($quiz);
$selectedLang = array_filter(explode(',', (string) ($quiz['quiz_lang'] ?? 'eng')));
$selectedCourseId = (int) ($quiz['cos_id'] ?? $defaultCourseId ?? 0);
$dateValue = static fn ($value): string => ((string) $value === '' || str_starts_with((string) $value, '0000-00-00')) ? '' : date('Y-m-d\TH:i', strtotime((string) $value));
$typeLabel = static function (string $type): string {
    return match ($type) {
        'text' => 'Text answer',
        'fill_blank' => 'Fill in the blank',
        'sort_order' => 'Sort order',
        default => 'Multiple choice',
    };
};
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root{--brand:#e71921;--ink:#1f2937;--muted:#6b7280;--line:#e5e7eb;--bg:#f4f6f9;--panel:#fff}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font-family:Arial,"Helvetica Neue",sans-serif}a{color:inherit;text-decoration:none}
        .topbar{background:#fff;border-bottom:1px solid var(--line)}.brand-row{max-width:1120px;margin:0 auto;min-height:74px;display:grid;grid-template-columns:220px 1fr 220px;align-items:center;gap:18px;padding:10px 22px}.brand-mark{font-weight:900;color:var(--brand);font-size:20px}.brand-center{text-align:center;font-size:28px;font-weight:900}.brand-actions{display:flex;justify-content:flex-end;gap:14px;color:var(--muted);font-size:13px}.logout{font-weight:800;color:#111827}
        .page{max-width:1120px;margin:0 auto;padding:28px 22px 42px}.panel{background:var(--panel);border:1px solid var(--line);border-radius:8px;padding:22px;margin-bottom:18px}.kicker{color:var(--brand);font-size:12px;font-weight:900;text-transform:uppercase}h1{margin:5px 0 18px;font-size:30px}h2{margin:0 0 14px;font-size:20px}
        .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.field.full{grid-column:1/-1}.field.two{grid-column:span 2}label{display:block;color:var(--muted);font-size:12px;font-weight:900;margin-bottom:6px}input,select,textarea{width:100%;border:1px solid var(--line);border-radius:7px;padding:10px 11px;font-size:14px;background:#fff}textarea{min-height:80px;resize:vertical}.checks{display:flex;gap:12px;flex-wrap:wrap;padding:10px 0}.checks label{color:var(--ink);font-size:14px;margin:0;display:flex;align-items:center;gap:7px}.checks input{width:auto}
        .actions{display:flex;gap:10px;justify-content:flex-end;margin-top:16px;flex-wrap:wrap}.btn{border:1px solid var(--line);background:#fff;border-radius:7px;padding:10px 14px;font-weight:900;cursor:pointer}.btn.primary{background:var(--brand);color:#fff;border-color:var(--brand)}.notice{border:1px solid #bee7cf;background:#ecfdf3;color:#087443;border-radius:8px;padding:12px 14px;margin-bottom:16px;font-weight:800}.error{border:1px solid #ffc7c7;background:#fff1f1;color:#b42318;border-radius:8px;padding:12px 14px;margin-bottom:16px;font-weight:800}
        table{width:100%;border-collapse:collapse;font-size:14px;min-width:720px}th,td{text-align:left;padding:11px 10px;border-bottom:1px solid var(--line);vertical-align:top}th{background:#fafafa;color:var(--muted);font-size:12px;text-transform:uppercase}.table-scroll{overflow-x:auto}.small{font-size:12px;color:var(--muted)}.choice-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px}.question-help{color:var(--muted);font-size:12px;line-height:1.5;margin-top:6px}.edit-question{padding:14px;background:#fafafa;border:1px solid var(--line);border-radius:8px}details summary{cursor:pointer;font-weight:900;color:var(--brand)}
        @media(max-width:900px){.brand-row,.grid{grid-template-columns:1fr}.field.two{grid-column:auto}.brand-center,.brand-actions{text-align:left;justify-content:flex-start}.page{padding:20px 14px 34px}.panel{padding:16px}.edit-question{padding:12px}}
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar"><div class="brand-row"><a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a><div class="brand-center">E-LEARNING</div><div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div></div></header>
<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>

    <section class="panel">
        <div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div>
        <h1><?= esc($title) ?></h1>
        <form method="post" enctype="multipart/form-data" action="<?= $isEdit ? site_url('managecourse/quizzes/' . $quiz['qiz_id'] . '/update') : site_url('managecourse/quizzes/create') ?>"><?= csrf_field() ?>
            <div class="grid">
                <div class="field two"><label>Course</label><select name="cos_id" required><option value="">Select course</option><?php foreach (($courses ?? []) as $course): ?><option value="<?= esc($course['cos_id']) ?>" <?= $selectedCourseId === (int) $course['cos_id'] ? 'selected' : '' ?>><?= esc(($course['ccode'] ?: '-') . ' - ' . $course['title']) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label>Status</label><select name="quiz_status"><option value="1" <?= (string)($quiz['quiz_status'] ?? '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string)($quiz['quiz_status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
                <div class="field"><label>English Name</label><input name="quiz_name_eng" required value="<?= esc($quiz['quiz_name_eng'] ?? '') ?>"></div>
                <div class="field"><label>Thai Name</label><input name="quiz_name_th" value="<?= esc($quiz['quiz_name_th'] ?? '') ?>"></div>
                <div class="field"><label>Japanese Name</label><input name="quiz_name_jp" value="<?= esc($quiz['quiz_name_jp'] ?? '') ?>"></div>
                <div class="field full"><label>Description</label><textarea name="quiz_info_eng"><?= esc($quiz['quiz_info_eng'] ?? '') ?></textarea></div>
                <div class="field"><label>Type</label><select name="quiz_type"><option value="1" <?= (string)($quiz['quiz_type'] ?? '1') === '1' ? 'selected' : '' ?>>Pre-test</option><option value="2" <?= (string)($quiz['quiz_type'] ?? '') === '2' ? 'selected' : '' ?>>Post-test</option></select></div>
                <div class="field"><label>Pass Score %</label><input type="number" min="0" max="100" name="quiz_maxscore" value="<?= esc($quiz['quiz_maxscore'] ?? '80') ?>"></div>
                <div class="field"><label>Attempt Limit</label><input type="number" min="0" name="quiz_limitval" value="<?= esc($quiz['quiz_limitval'] ?? '0') ?>"></div>
                <div class="field"><label>Open Date</label><input type="datetime-local" name="period_open" value="<?= esc($dateValue($quiz['period_open'] ?? '')) ?>"></div>
                <div class="field"><label>End Date</label><input type="datetime-local" name="period_end" value="<?= esc($dateValue($quiz['period_end'] ?? '')) ?>"></div>
                <div class="field"><label>Show to Learner</label><select name="quiz_show"><option value="1" <?= (string)($quiz['quiz_show'] ?? '1') === '1' ? 'selected' : '' ?>>Yes</option><option value="0" <?= (string)($quiz['quiz_show'] ?? '') === '0' ? 'selected' : '' ?>>No</option></select></div>
                <div class="field full"><label>Languages</label><div class="checks"><label><input type="checkbox" name="quiz_lang[]" value="eng" <?= in_array('eng', $selectedLang, true) ? 'checked' : '' ?>> English</label><label><input type="checkbox" name="quiz_lang[]" value="th" <?= in_array('th', $selectedLang, true) ? 'checked' : '' ?>> Thai</label><label><input type="checkbox" name="quiz_lang[]" value="jp" <?= in_array('jp', $selectedLang, true) ? 'checked' : '' ?>> Japanese</label></div></div>
            </div>
            <input type="hidden" name="quiz_limit" value="0"><input type="hidden" name="quiz_random" value="0"><input type="hidden" name="quiz_random_choice" value="0"><input type="hidden" name="quiz_grade" value="1"><input type="hidden" name="quiz_answer" value="0"><input type="hidden" name="quiz_ishint" value="0"><input type="hidden" name="quiz_model" value="1"><input type="hidden" name="quiz_numofshown" value="0">
            <div class="actions"><a class="btn" href="<?= site_url('managecourse/quizzes') ?>">Back</a><?php if ($isEdit): ?><a class="btn" href="<?= site_url('managecourse/quizzes/' . $quiz['qiz_id'] . '/grading') ?>">Grade Text Answers</a><?php endif; ?><button class="btn primary" type="submit">Save Quiz</button></div>
        </form>
    </section>

    <?php if ($isEdit): ?>
        <section class="panel">
            <h2>Questions</h2>
            <form method="post" action="<?= site_url('managecourse/quizzes/' . $quiz['qiz_id'] . '/questions/create') ?>" class="grid"><?= csrf_field() ?>
                <div class="field"><label>Question Type</label><select name="ques_type" id="questionType" class="question-type"><option value="multi">Multiple choice</option><option value="text">Text answer</option><option value="fill_blank">Fill in the blank</option><option value="sort_order">Sort order</option></select></div>
                <div class="field"><label>Score</label><input type="number" step="0.01" min="0" name="ques_score" value="1"></div>
                <div class="field"><label>Status</label><select name="ques_status"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                <div class="field full"><label>English Question</label><input name="ques_name_eng" required><div class="question-help">For fill in the blank, use ____ in the sentence for each answer field below.</div></div>
                <div class="field full choice-settings"><label id="choiceLabel" class="choice-label">Choices / Answers / Sort Items</label><div class="choice-grid"><?php for ($i = 1; $i <= 10; $i++): ?><input name="mul_c<?= $i ?>_eng" placeholder="Choice <?= $i ?>"><?php endfor; ?></div></div>
                <div class="field correct-answer"><label>Correct Answer</label><select name="mul_answer"><?php for ($i = 1; $i <= 10; $i++): ?><option value="mul_c<?= $i ?>">Choice <?= $i ?></option><?php endfor; ?></select><div class="question-help">For sort order imports use 1,2,3. In this form, the displayed item order is the correct order.</div></div>
                <div class="field blank-mode"><label>Fill Blank Scoring</label><select name="ques_blank_score_mode"><option value="all_or_nothing">All blanks must be correct</option><option value="partial">Partial score per correct blank</option></select></div>
                <div class="field full actions"><button class="btn primary" type="submit">Add Question</button></div>
            </form>
            <form method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/quizzes/' . $quiz['qiz_id'] . '/questions/import') ?>" class="grid" style="border-top:1px solid var(--line);margin-top:18px;padding-top:18px"><?= csrf_field() ?>
                <div class="field two"><label>Import Questions XLSX/CSV</label><input type="file" name="question_file" accept=".xlsx,.xls,.csv" required></div>
                <div class="field"><label>Template</label><a class="btn" href="<?= site_url('managecourse/quizzes/import-template') ?>">Download Template</a></div>
                <div class="field full actions"><button class="btn primary" type="submit">Import Questions</button></div>
            </form>
            <div class="table-scroll"><table><thead><tr><th>Question</th><th>Type</th><th>Score</th><th>Status</th><th>Action</th></tr></thead><tbody>
                <?php foreach (($quiz['questions'] ?? []) as $question): ?>
                    <?php
                    $choiceRow = $question['choices'] ?? [];
                    $correctAnswer = (string) ($choiceRow['mul_answer'] ?? 'mul_c1');
                    ?>
                    <tr><td><strong><?= esc($question['title']) ?></strong><br><span class="small">#<?= esc($question['ques_id']) ?></span></td><td><?= esc($typeLabel((string) $question['ques_type'])) ?></td><td><?= esc($question['ques_score']) ?></td><td><?= (string) $question['ques_status'] === '1' ? 'Active' : 'Inactive' ?></td><td><form method="post" action="<?= site_url('managecourse/quiz-questions/' . $question['ques_id'] . '/status') ?>"><?= csrf_field() ?><input type="hidden" name="archive" value="1"><button class="btn" type="submit">Archive</button></form></td></tr>
                    <tr>
                        <td colspan="5">
                            <details>
                                <summary>Edit question</summary>
                                <form method="post" action="<?= site_url('managecourse/quiz-questions/' . $question['ques_id'] . '/update') ?>" class="grid edit-question"><?= csrf_field() ?>
                                    <div class="field"><label>Question Type</label><select name="ques_type" class="question-type"><option value="multi" <?= (string) $question['ques_type'] === 'multi' ? 'selected' : '' ?>>Multiple choice</option><option value="text" <?= (string) $question['ques_type'] === 'text' ? 'selected' : '' ?>>Text answer</option><option value="fill_blank" <?= (string) $question['ques_type'] === 'fill_blank' ? 'selected' : '' ?>>Fill in the blank</option><option value="sort_order" <?= (string) $question['ques_type'] === 'sort_order' ? 'selected' : '' ?>>Sort order</option></select></div>
                                    <div class="field"><label>Score</label><input type="number" step="0.01" min="0" name="ques_score" value="<?= esc($question['ques_score']) ?>"></div>
                                    <div class="field"><label>Status</label><select name="ques_status"><option value="1" <?= (string) $question['ques_status'] === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) $question['ques_status'] === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
                                    <div class="field full"><label>English Question</label><input name="ques_name_eng" required value="<?= esc($question['ques_name_eng'] ?? '') ?>"><div class="question-help">For fill in the blank, use ____ in the sentence for each answer field below.</div></div>
                                    <div class="field full choice-settings"><label class="choice-label">Choices / Answers / Sort Items</label><div class="choice-grid"><?php for ($i = 1; $i <= 10; $i++): ?><input name="mul_c<?= $i ?>_eng" value="<?= esc($choiceRow['mul_c' . $i . '_eng'] ?? '') ?>" placeholder="Choice <?= $i ?>"><?php endfor; ?></div></div>
                                    <div class="field correct-answer"><label>Correct Answer</label><select name="mul_answer"><?php for ($i = 1; $i <= 10; $i++): ?><option value="mul_c<?= $i ?>" <?= $correctAnswer === 'mul_c' . $i ? 'selected' : '' ?>>Choice <?= $i ?></option><?php endfor; ?></select><div class="question-help">For sort order, the item order above is the correct order.</div></div>
                                    <div class="field blank-mode"><label>Fill Blank Scoring</label><select name="ques_blank_score_mode"><option value="all_or_nothing" <?= (string) ($question['ques_blank_score_mode'] ?? 'all_or_nothing') === 'all_or_nothing' ? 'selected' : '' ?>>All blanks must be correct</option><option value="partial" <?= (string) ($question['ques_blank_score_mode'] ?? '') === 'partial' ? 'selected' : '' ?>>Partial score per correct blank</option></select></div>
                                    <div class="field full actions"><button class="btn primary" type="submit">Save Question</button></div>
                                </form>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($quiz['questions'])): ?><tr><td colspan="5">No questions yet.</td></tr><?php endif; ?>
            </tbody></table></div>
        </section>
    <?php endif; ?>
</main>
<script>
function syncQuestionFields(select) {
    const form = select.closest('form');
    const type = select.value;
    const choiceSettings = form ? form.querySelector('.choice-settings') : null;
    const correctAnswer = form ? form.querySelector('.correct-answer') : null;
    const blankMode = form ? form.querySelector('.blank-mode') : null;
    const choiceLabel = form ? form.querySelector('.choice-label') : null;
    if (choiceSettings) choiceSettings.style.display = type === 'text' ? 'none' : '';
    if (correctAnswer) correctAnswer.style.display = type === 'multi' ? '' : 'none';
    if (blankMode) blankMode.style.display = type === 'fill_blank' ? '' : 'none';
    if (choiceLabel) choiceLabel.textContent = type === 'fill_blank' ? 'Blank Answers' : (type === 'sort_order' ? 'Items in Correct Order' : 'Choices');
}
document.querySelectorAll('.question-type').forEach((select) => {
    select.addEventListener('change', () => syncQuestionFields(select));
    syncQuestionFields(select);
});
</script>
<script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
