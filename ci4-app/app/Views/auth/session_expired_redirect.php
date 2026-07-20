<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background: #f7f9fc;
            color: #172033;
            font-family: Arial, sans-serif;
        }
        main {
            width: min(420px, calc(100vw - 32px));
            padding: 28px;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 18px 48px rgba(22, 32, 51, .12);
            text-align: center;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }
        p {
            margin: 0;
            color: #607080;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<main>
    <h1><?= esc($message ?? 'Session expired') ?></h1>
    <p>กำลังพาไปหน้า Login...</p>
</main>
<script>
    const sessionExpiredMessage = <?= json_encode($message ?? 'Session expired', JSON_UNESCAPED_UNICODE) ?>;
    const loginUrl = <?= json_encode($loginUrl ?? site_url('login'), JSON_UNESCAPED_SLASHES) ?>;
    alert(sessionExpiredMessage);
    window.location.replace(loginUrl);
</script>
</body>
</html>
