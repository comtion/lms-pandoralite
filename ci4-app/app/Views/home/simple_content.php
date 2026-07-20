<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f3f5f8; font-family:Tahoma, Arial, sans-serif; color:#18202a; }
        .page { max-width:960px; margin:0 auto; padding:32px 18px; }
        .panel { background:#fff; border:1px solid #dfe5ec; padding:24px; }
        h1 { font-size:26px; font-weight:700; margin:0 0 22px; border-left:2px solid #ec2029; padding-left:12px; }
        .item { border-top:1px solid #e6ebf1; padding:16px 0; }
        .item:first-of-type { border-top:0; }
        .item h2 { font-size:18px; font-weight:700; margin:0 0 8px; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<main class="page">
    <div class="panel">
        <h1><?= esc($title) ?></h1>
        <?php if (empty($rows)): ?>
            <p class="text-muted mb-0">No records found.</p>
        <?php endif; ?>
        <?php foreach (($rows ?? []) as $row): ?>
            <div class="item">
                <h2><?= esc($row['title'] ?? '-') ?></h2>
                <div><?= trim((string) ($row['body'] ?? '')) !== '' ? $row['body'] : '' ?></div>
            </div>
        <?php endforeach; ?>
        <div class="mt-3"><a class="btn btn-outline-secondary" href="<?= site_url('dashboard') ?>">กลับ</a></div>
    </div>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
