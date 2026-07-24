<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($quiz['title']) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --green:#087443; }
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
        .question-head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
        .question-title { font-weight:900; line-height:1.5; }
        .score { color:var(--muted); font-size:12px; font-weight:800; white-space:nowrap; }
        .choice { display:flex; align-items:flex-start; gap:10px; border:1px solid var(--line); border-radius:8px; padding:12px; margin:9px 0; cursor:pointer; }
        .choice input { margin-top:2px; }
        textarea, .blank-input { width:100%; border:1px solid var(--line); border-radius:8px; padding:12px; font-size:14px; background:#fff; }
        textarea { min-height:100px; }
        .blank-sentence { line-height:2.8; }
        .blank-inline { display:inline-block; width:180px; max-width:100%; margin:0 5px; vertical-align:middle; }
        .blank-extra { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; margin-top:12px; }
        .blank-field label { color:var(--muted); display:block; font-size:12px; font-weight:900; margin-bottom:5px; }
        .sort-list { display:grid; gap:9px; }
        .sort-row { display:grid; grid-template-columns:42px minmax(0,1fr) 92px; gap:10px; align-items:center; border:1px solid var(--line); border-radius:8px; padding:10px; background:#fff; }
        .sort-row.dragging { opacity:.55; }
        .sort-handle { border:1px solid var(--line); border-radius:7px; background:#f9fafb; min-height:36px; cursor:grab; font-weight:900; color:var(--muted); }
        .sort-item { font-weight:800; line-height:1.4; }
        .sort-actions { display:flex; gap:6px; }
        .sort-actions button { width:40px; height:36px; border:1px solid var(--line); border-radius:7px; background:#fff; cursor:pointer; font-weight:900; }
        .actions { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-top:18px; }
        .btn { border:1px solid var(--line); border-radius:7px; padding:12px 16px; font-weight:900; background:#fff; cursor:pointer; font-size:14px; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:var(--green); border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:780px) { .brand-row,.blank-extra { grid-template-columns:1fr; } .page { padding:20px 14px 34px; } .panel,.question { padding:16px; } .blank-inline { display:block; width:100%; margin:8px 0; } .sort-row { grid-template-columns:38px minmax(0,1fr); } .sort-actions { grid-column:1/-1; justify-content:flex-end; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .actions { display:block; } .actions .btn { width:100%; margin-top:10px; } }
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
        <div class="error"><?= esc(is_array($error) ? ($error['message'] ?? 'Quiz error') : $error) ?></div>
    <?php endif; ?>
    <?php if (! empty($result)): ?>
        <div class="notice">
            Score <?= esc($result['score']) ?> / <?= esc($result['total']) ?> (<?= esc($result['percent']) ?>%)
            <?= ! empty($result['passed']) ? 'Passed' : 'Submitted' ?>
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="kicker"><?= esc($quiz['type_label']) ?></div>
        <h1><?= esc($quiz['title']) ?></h1>
        <p class="muted"><?= esc($quiz['description'] ?: '-') ?></p>
        <?php if (! empty($quiz['last_attempt'])): ?>
            <p class="muted">Last score: <?= esc($quiz['last_attempt']['sum_score']) ?> (<?= esc($quiz['last_attempt']['per_score']) ?>%)</p>
        <?php endif; ?>
    </section>

    <form method="post" action="<?= site_url('coursemain/quiz/' . $quiz['qiz_id'] . '/submit') ?>"><?= csrf_field() ?>
        <?php foreach (($quiz['questions'] ?? []) as $index => $question): ?>
            <section class="question">
                <div class="question-head">
                    <div class="question-title"><?= ($index + 1) ?>. <?= esc($question['title']) ?></div>
                    <div class="score"><?= esc($question['ques_score']) ?> pts</div>
                </div>
                <?php if ($question['ques_type'] === 'multi'): ?>
                    <?php foreach (($question['choices'] ?? []) as $choice): ?>
                        <label class="choice">
                            <input type="radio" name="answers[<?= esc($question['ques_id']) ?>]" value="<?= esc($choice['value']) ?>">
                            <span><?= esc($choice['text']) ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php elseif ($question['ques_type'] === 'fill_blank'): ?>
                    <?php
                    $blankAnswers = array_values($question['blank_answers'] ?? []);
                    $segments = preg_split('/_{2,}/', (string) $question['title']);
                    $inlineCount = max(0, count($segments) - 1);
                    ?>
                    <?php if ($inlineCount > 0): ?>
                        <div class="blank-sentence">
                            <?php foreach ($segments as $segmentIndex => $segment): ?>
                                <?= esc($segment) ?>
                                <?php if ($segmentIndex < $inlineCount): ?>
                                    <input class="blank-input blank-inline" name="answers[<?= esc($question['ques_id']) ?>][<?= esc($segmentIndex) ?>]" autocomplete="off" aria-label="Blank <?= esc($segmentIndex + 1) ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (count($blankAnswers) > $inlineCount): ?>
                        <div class="blank-extra">
                        <?php for ($blankIndex = $inlineCount; $blankIndex < count($blankAnswers); $blankIndex++): ?>
                            <div class="blank-field">
                                <label>Blank <?= esc($blankIndex + 1) ?></label>
                                <input class="blank-input" name="answers[<?= esc($question['ques_id']) ?>][<?= esc($blankIndex) ?>]" autocomplete="off">
                            </div>
                        <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif ($question['ques_type'] === 'sort_order'): ?>
                    <div class="sort-list" data-sort-list>
                    <?php foreach (($question['choices'] ?? []) as $positionIndex => $choice): ?>
                        <div class="sort-row" draggable="true" data-sort-row>
                            <button class="sort-handle" type="button" aria-label="Drag item">::</button>
                            <div class="sort-item"><?= esc($choice['text']) ?></div>
                            <div class="sort-actions">
                                <button type="button" data-sort-up aria-label="Move up">↑</button>
                                <button type="button" data-sort-down aria-label="Move down">↓</button>
                            </div>
                            <input type="hidden" data-sort-position name="answers[<?= esc($question['ques_id']) ?>][<?= esc($choice['value']) ?>]" value="<?= esc($positionIndex + 1) ?>">
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <textarea name="answers[<?= esc($question['ques_id']) ?>]" placeholder="Type your answer"></textarea>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>

        <div class="actions">
            <a class="btn" href="<?= site_url('coursemain/detail/' . $quiz['cos_id']) ?>">Back to Course</a>
            <?php if (! empty($quiz['questions'])): ?><button class="btn primary" type="submit">Submit Quiz</button><?php endif; ?>
        </div>
    </form>
</main>
    <script>
    document.querySelectorAll('[data-sort-list]').forEach((list) => {
        let dragged = null;
        const rows = () => Array.from(list.querySelectorAll('[data-sort-row]'));
        const updatePositions = () => {
            rows().forEach((row, index) => {
                const input = row.querySelector('[data-sort-position]');
                if (input) input.value = String(index + 1);
            });
        };
        const moveRow = (row, direction) => {
            if (!row) return;
            if (direction < 0 && row.previousElementSibling) {
                list.insertBefore(row, row.previousElementSibling);
            } else if (direction > 0 && row.nextElementSibling) {
                list.insertBefore(row.nextElementSibling, row);
            }
            updatePositions();
        };
        list.addEventListener('dragstart', (event) => {
            const row = event.target.closest('[data-sort-row]');
            if (!row) return;
            dragged = row;
            row.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
        });
        list.addEventListener('dragend', () => {
            if (dragged) dragged.classList.remove('dragging');
            dragged = null;
            updatePositions();
        });
        list.addEventListener('dragover', (event) => {
            event.preventDefault();
            const target = event.target.closest('[data-sort-row]');
            if (!dragged || !target || target === dragged) return;
            const rect = target.getBoundingClientRect();
            const after = event.clientY > rect.top + rect.height / 2;
            list.insertBefore(dragged, after ? target.nextSibling : target);
        });
        list.addEventListener('click', (event) => {
            const row = event.target.closest('[data-sort-row]');
            if (event.target.closest('[data-sort-up]')) moveRow(row, -1);
            if (event.target.closest('[data-sort-down]')) moveRow(row, 1);
        });
        updatePositions();
    });
    </script>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
