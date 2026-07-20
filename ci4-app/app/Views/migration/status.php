<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Migration Status</title>
    <style>
        body { margin: 0; font-family: Tahoma, Arial, sans-serif; background: #f3f5f8; color: #18202a; }
        main { max-width: 980px; margin: 0 auto; padding: 28px 18px; }
        pre { white-space: pre-wrap; background: #fff; border: 1px solid #dfe5ec; padding: 22px; line-height: 1.6; }
        a { color: #ec2029; font-weight: 700; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<main>
    <p><a href="<?= site_url('dashboard') ?>">กลับ Dashboard</a></p>
    <pre><?= esc($content) ?></pre>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
