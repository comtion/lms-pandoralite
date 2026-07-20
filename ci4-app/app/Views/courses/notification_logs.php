<?php
$displayName = trim((string) ($name ?? '')) ?: 'Learner';
$profileImage = ! empty($user['img_profile']) ? base_url('uploads/profile/' . $user['img_profile']) : base_url('uploads/profile/default_profile.jpg');
$courseTitle = static function (array $schedule): string {
    foreach (['cname_eng', 'cname_th', 'cname_jp'] as $field) {
        $value = trim((string) ($schedule[$field] ?? ''));
        if ($value !== '') {
            return strip_tags($value);
        }
    }
    return 'Course #' . (int) ($schedule['cos_id'] ?? 0);
};
$filters = $filters ?? [];
$page = max(1, (int) ($page ?? 1));
$perPage = max(1, (int) ($perPage ?? 25));
$totalSchedules = (int) ($totalSchedules ?? 0);
$schedulePages = max(1, (int) ceil($totalSchedules / $perPage));
$logPage = max(1, (int) ($logPage ?? 1));
$logPerPage = max(1, (int) ($logPerPage ?? 100));
$totalLogs = (int) ($totalLogs ?? 0);
$logPages = max(1, (int) ceil($totalLogs / $logPerPage));
$queryUrl = static function (array $params): string {
    $merged = array_filter(array_merge($_GET, $params), static fn ($value): bool => $value !== '' && $value !== null);
    return site_url('managecourse/course_notifications') . ($merged ? '?' . http_build_query($merged) : '');
};
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/dashboard-enterprise.css?v=20260701-3') ?>" rel="stylesheet">
    <style>
        body { margin:0; background:#f4f6f9; color:#172033; font-family:Arial, "Helvetica Neue", sans-serif; }
        .topbar { position:sticky; top:0; z-index:10; min-height:72px; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:12px 24px; border-bottom:1px solid #e5e7eb; background:#fff; }
        .brand { color:#e71921; font-weight:900; font-size:20px; }
        .profile { display:flex; align-items:center; gap:10px; color:#4b5563; font-size:13px; }
        .profile img { width:34px; height:34px; border-radius:50%; object-fit:cover; }
        .page { max-width:1300px; margin:0 auto; padding:28px 22px 44px; }
        .head { display:flex; align-items:flex-end; justify-content:space-between; gap:18px; margin-bottom:18px; }
        .kicker { color:#e71921; font-weight:900; font-size:12px; text-transform:uppercase; }
        h1 { margin:4px 0 0; font-size:30px; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end; }
        .btn { display:inline-flex; align-items:center; gap:7px; border:1px solid #e5e7eb; border-radius:7px; background:#fff; color:#374151; padding:10px 13px; font-weight:800; text-decoration:none; cursor:pointer; }
        .btn.primary { background:#172033; border-color:#172033; color:#fff; }
        .notice,.error { border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; }
        .panel { background:#fff; border:1px solid #eee3ce; border-radius:18px; box-shadow:0 12px 32px rgba(31,41,55,.055); padding:20px; margin-bottom:18px; }
        .filters { display:grid; grid-template-columns:repeat(6, minmax(0, 1fr)); gap:10px; align-items:end; }
        .filters label { display:grid; gap:5px; color:#6b7280; font-size:12px; font-weight:800; }
        .filters input,.filters select { width:100%; border:1px solid #e5e7eb; border-radius:7px; padding:9px 10px; font-size:14px; }
        .pager { display:flex; align-items:center; justify-content:flex-end; gap:8px; margin-top:14px; color:#6b7280; font-size:13px; }
        .pager a { border:1px solid #e5e7eb; border-radius:7px; padding:8px 10px; color:#374151; text-decoration:none; background:#fff; font-weight:800; }
        .pager a.disabled { opacity:.45; pointer-events:none; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        th,td { text-align:left; padding:12px 10px; border-bottom:1px solid #e5e7eb; vertical-align:top; }
        th { color:#6b7280; font-size:12px; text-transform:uppercase; background:#fafafa; }
        .badge { display:inline-flex; border-radius:999px; padding:5px 9px; font-size:12px; font-weight:900; background:#f3f4f6; color:#374151; }
        .badge.sent { background:#eaf8f1; color:#087443; }
        .badge.failed { background:#fff1f1; color:#b42318; }
        .badge.pending,.badge.waiting_course { background:#fff7dc; color:#8a6300; }
        .muted { color:#6b7280; font-size:12px; }
        @media (max-width:1000px) { .filters { grid-template-columns:repeat(2, minmax(0, 1fr)); } }
        @media (max-width:800px) { .head,.topbar { display:block; } .actions { justify-content:flex-start; margin-top:12px; } table { min-width:900px; } .panel { overflow:auto; } }
    </style>
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= site_url('dashboard') ?>">LMS CI4</a>
    <div class="profile"><img src="<?= esc($profileImage) ?>" onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'" alt="<?= esc($displayName) ?>"><span><?= esc($displayName) ?></span></div>
</header>
<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <div class="head">
        <div>
            <div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div>
            <h1><?= esc($title) ?></h1>
        </div>
        <div class="actions">
            <a class="btn" href="<?= site_url('managecourse/courses_all') ?>"><i class="bi bi-book"></i> Courses</a>
            <a class="btn" href="<?= site_url('dashboard') ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        </div>
    </div>

    <section class="panel">
        <form class="filters" method="get" action="<?= site_url('managecourse/course_notifications') ?>">
            <?php if ((int) ($selectedScheduleId ?? 0) > 0): ?><input type="hidden" name="schedule_id" value="<?= esc((string) (int) $selectedScheduleId) ?>"><?php endif; ?>
            <label>Course ID<input name="course_id" type="number" min="1" value="<?= esc((string) ($filters['course_id'] ?: '')) ?>"></label>
            <label>Schedule Status<select name="status">
                <option value="">All</option>
                <?php foreach (['pending', 'waiting_course', 'processing', 'sent', 'failed', 'canceled'] as $status): ?>
                    <option value="<?= esc($status) ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= esc($status) ?></option>
                <?php endforeach; ?>
            </select></label>
            <label>Channel<select name="channel">
                <option value="">All</option>
                <option value="system" <?= ($filters['channel'] ?? '') === 'system' ? 'selected' : '' ?>>system</option>
                <option value="email" <?= ($filters['channel'] ?? '') === 'email' ? 'selected' : '' ?>>email</option>
            </select></label>
            <label>Log Status<select name="log_status">
                <option value="">All</option>
                <option value="sent" <?= ($filters['log_status'] ?? '') === 'sent' ? 'selected' : '' ?>>sent</option>
                <option value="failed" <?= ($filters['log_status'] ?? '') === 'failed' ? 'selected' : '' ?>>failed</option>
            </select></label>
            <label>From<input name="date_from" type="date" value="<?= esc($filters['date_from'] ?? '') ?>"></label>
            <label>To<input name="date_to" type="date" value="<?= esc($filters['date_to'] ?? '') ?>"></label>
            <div class="actions">
                <button class="btn primary" type="submit">Apply</button>
                <a class="btn" href="<?= site_url('managecourse/course_notifications') ?>">Reset</a>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2 style="margin:0 0 14px;font-size:20px">Schedules <span class="muted">(<?= esc((string) $totalSchedules) ?>)</span></h2>
        <table>
            <thead><tr><th>Course</th><th>Channels</th><th>Audience</th><th>Send At</th><th>Status</th><th>Sent</th><th>Failed</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach (($schedules ?? []) as $schedule): ?>
                <tr>
                    <td><strong><?= esc($courseTitle($schedule)) ?></strong><br><span class="muted"><?= esc($schedule['ccode'] ?? '-') ?> #<?= esc($schedule['cos_id']) ?></span></td>
                    <td><?= esc($schedule['channels']) ?></td>
                    <td><?= esc($schedule['audience_type']) ?></td>
                    <td><?= esc($schedule['send_at']) ?></td>
                    <td><span class="badge <?= esc($schedule['status']) ?>"><?= esc($schedule['status']) ?></span></td>
                    <td><span class="badge sent"><?= esc((string) (int) ($schedule['sent_count'] ?? 0)) ?></span></td>
                    <td><span class="badge failed"><?= esc((string) (int) ($schedule['failed_count'] ?? 0)) ?></span></td>
                    <td>
                        <a class="btn" href="<?= site_url('managecourse/course_notifications?schedule_id=' . (int) $schedule['cn_id']) ?>">View</a>
                        <?php if ((int) ($schedule['failed_count'] ?? 0) > 0): ?>
                            <form method="post" action="<?= site_url('managecourse/course_notifications/' . (int) $schedule['cn_id'] . '/retry') ?>" style="display:inline">
                                <button class="btn primary" type="submit">Retry Failed</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($schedules)): ?><tr><td colspan="8">No notification schedules yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
        <div class="pager">
            <span>Page <?= esc((string) $page) ?> / <?= esc((string) $schedulePages) ?></span>
            <a class="<?= $page <= 1 ? 'disabled' : '' ?>" href="<?= esc($queryUrl(['page' => max(1, $page - 1)])) ?>">Previous</a>
            <a class="<?= $page >= $schedulePages ? 'disabled' : '' ?>" href="<?= esc($queryUrl(['page' => min($schedulePages, $page + 1)])) ?>">Next</a>
        </div>
    </section>

    <?php if ((int) ($selectedScheduleId ?? 0) > 0): ?>
        <section class="panel">
            <h2 style="margin:0 0 14px;font-size:20px">Recipient Logs <span class="muted">(<?= esc((string) $totalLogs) ?>)</span></h2>
            <table>
                <thead><tr><th>Time</th><th>Recipient</th><th>Email</th><th>Channel</th><th>Status</th><th>Message</th></tr></thead>
                <tbody>
                <?php foreach (($logs ?? []) as $log): ?>
                    <tr>
                        <td><?= esc($log['created_at']) ?></td>
                        <td><?= esc($log['fullname_en'] ?: $log['fullname_th'] ?: ('Employee #' . $log['emp_id'])) ?></td>
                        <td><?= esc($log['recipient_email'] ?: '-') ?></td>
                        <td><?= esc($log['channel']) ?></td>
                        <td><span class="badge <?= esc($log['status']) ?>"><?= esc($log['status']) ?></span></td>
                        <td><span class="muted"><?= esc($log['message'] ?: '-') ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?><tr><td colspan="6">No logs for this schedule.</td></tr><?php endif; ?>
                </tbody>
            </table>
            <div class="pager">
                <span>Page <?= esc((string) $logPage) ?> / <?= esc((string) $logPages) ?></span>
                <a class="<?= $logPage <= 1 ? 'disabled' : '' ?>" href="<?= esc($queryUrl(['log_page' => max(1, $logPage - 1)])) ?>">Previous</a>
                <a class="<?= $logPage >= $logPages ? 'disabled' : '' ?>" href="<?= esc($queryUrl(['log_page' => min($logPages, $logPage + 1)])) ?>">Next</a>
            </div>
        </section>
    <?php endif; ?>
</main>
<script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
