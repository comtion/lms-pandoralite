<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Notification center</title>
    <style>
        body{font-family:Arial;background:#f4f6f8;color:#18202a;margin:0}main{max-width:960px;margin:32px auto;padding:0 16px}
        section{background:#fff;border:1px solid #d9e0e7;border-radius:10px;padding:20px;margin-bottom:18px}
        .item{border-bottom:1px solid #e5e7eb;padding:14px 0}.item:last-child{border:0}.unread{border-left:4px solid #2563eb;padding-left:12px}
        .notice{background:#dcfce7;color:#166534;padding:12px}label{display:block;margin:12px 0}button{padding:9px 14px}
        .skip{position:absolute;left:-9999px}.skip:focus{left:12px;top:12px;background:#fff;padding:8px}
    </style>
</head>
<body>
<a class="skip" href="#content">Skip to content</a>
<main id="content">
    <h1>Notification center <small>(<?= esc($unread) ?> unread)</small></h1>
    <?php if($notice): ?><p class="notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <section aria-labelledby="notifications-heading">
        <h2 id="notifications-heading">Latest notifications</h2>
        <form method="post" action="<?= site_url('notifications/read-all') ?>"><?= csrf_field() ?><button type="submit">Mark all as read</button></form>
        <?php if($items===[]): ?><p>No notifications.</p><?php endif ?>
        <?php foreach($items as $item): ?>
            <article class="item <?= (int)$item['is_read']===0?'unread':'' ?>">
                <h3><?= esc($item['title']) ?></h3><p><?= esc($item['message']) ?></p>
                <small><?= esc($item['created_at']) ?></small>
                <?php if((int)$item['is_read']===0): ?><form method="post" action="<?= site_url('notifications/'.$item['noti_id'].'/read') ?>"><?= csrf_field() ?><button type="submit">Mark read</button></form><?php endif ?>
            </article>
        <?php endforeach ?>
    </section>
    <section aria-labelledby="preferences-heading">
        <h2 id="preferences-heading">Preferences</h2>
        <form method="post" action="<?= site_url('notifications/preferences') ?>">
            <?= csrf_field() ?>
            <label><input type="checkbox" name="in_app_enabled" value="1" <?= $preferences['in_app_enabled']?'checked':'' ?>> In-app notifications</label>
            <label><input type="checkbox" name="email_enabled" value="1" <?= $preferences['email_enabled']?'checked':'' ?>> Email notifications</label>
            <label>Digest frequency
                <select name="digest_frequency"><?php foreach(['immediate','daily','weekly','off'] as $value): ?><option value="<?= $value ?>" <?= $preferences['digest_frequency']===$value?'selected':'' ?>><?= ucfirst($value) ?></option><?php endforeach ?></select>
            </label>
            <label>Quiet hours start <input type="time" name="quiet_hours_start" value="<?= esc(substr((string)$preferences['quiet_hours_start'],0,5)) ?>"></label>
            <label>Quiet hours end <input type="time" name="quiet_hours_end" value="<?= esc(substr((string)$preferences['quiet_hours_end'],0,5)) ?>"></label>
            <button type="submit">Save preferences</button>
        </form>
    </section>
</main>
</body>
</html>
