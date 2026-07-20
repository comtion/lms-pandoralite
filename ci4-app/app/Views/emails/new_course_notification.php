<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New course available</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#1f2937;line-height:1.5">
    <h2 style="margin:0 0 12px">New course available</h2>
    <p>Hello <?= esc($recipient['fullname_en'] ?: $recipient['fullname_th'] ?: $recipient['useri'] ?: 'Learner') ?>,</p>
    <p>A new course is available: <strong><?= esc($courseTitle) ?></strong></p>
    <p><a href="<?= esc($courseUrl) ?>" style="display:inline-block;background:#e71921;color:#fff;padding:10px 14px;text-decoration:none;border-radius:6px">View course</a></p>
    <p style="color:#697386;font-size:13px">This message was sent by the LMS notification system.</p>
</body>
</html>
