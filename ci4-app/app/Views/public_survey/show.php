<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title><?= esc($survey['title']) ?></title>
    <style>
        body{font-family:Arial;background:#f4f6f8}
        main{max-width:850px;margin:30px auto;background:#fff;padding:25px}
        .q{padding:15px 0;border-bottom:1px solid #ddd}
        .error{padding:12px;background:#fee2e2;color:#991b1b}
        textarea{width:100%;padding:8px}
    </style>
</head>
<body>
<main>
    <h1><?= esc($survey['title']) ?></h1>
    <p><?= esc($survey['explanation']) ?></p>
    <?php if(session('module_error')): ?><p class="error"><?= esc(session('module_error')) ?></p><?php endif ?>
    <form method="post" action="<?= site_url('survey/'.$survey['sv_id'].'/submit') ?>">
        <?= csrf_field() ?>
        <?php foreach($questions as $q): ?>
            <div class="q">
                <strong><?= esc($q['question']) ?></strong>
                <?php if($q['choices']): ?>
                    <?php foreach($q['choices'] as $choice): ?>
                        <label><input type="radio" name="answers[<?= esc($q['svde_id']) ?>]" value="<?= esc($choice) ?>" required> <?= esc($choice) ?></label><br>
                    <?php endforeach ?>
                <?php else: ?>
                    <textarea name="answers[<?= esc($q['svde_id']) ?>]" required><?= esc(old('answers.'.$q['svde_id'])) ?></textarea>
                <?php endif ?>
            </div>
        <?php endforeach ?>
        <p><button>Submit survey</button></p>
    </form>
</main>
</body>
</html>
