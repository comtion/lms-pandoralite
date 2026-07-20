<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LMS Login</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Arial, sans-serif; background: #f4f6f8; color: #18202a; }
        main { width: min(420px, calc(100vw - 32px)); background: #fff; border: 1px solid #d9e0e7; border-radius: 8px; padding: 28px; box-shadow: 0 18px 45px rgba(24, 32, 42, .08); }
        h1 { margin: 0 0 6px; font-size: 24px; }
        p { margin: 0 0 22px; color: #607080; }
        label { display: block; margin: 16px 0 6px; font-size: 14px; font-weight: 700; }
        input { width: 100%; box-sizing: border-box; border: 1px solid #c9d2dc; border-radius: 6px; padding: 12px; font-size: 15px; }
        button { width: 100%; margin-top: 22px; border: 0; border-radius: 6px; padding: 12px 16px; background: #111827; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; }
        .error { margin-bottom: 16px; padding: 10px 12px; border-radius: 6px; background: #fff1f2; color: #b42318; font-size: 14px; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<main>
    <h1>LMS Login</h1>
    <p>Sign in with your existing LMS account.</p>

    <?php if (! empty($error)): ?>
        <div class="error"><?= esc($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input id="username" name="username" value="<?= old('username') ?>" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>

        <button type="submit">Sign In</button>
    </form>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
