<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; color:var(--brand); font-size:20px; }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 44px; }
        .head { display:flex; justify-content:space-between; align-items:flex-end; gap:18px; margin-bottom:18px; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:6px 0 0; font-size:30px; }
        .btn { display:inline-flex; border:1px solid var(--line); border-radius:7px; padding:10px 13px; font-weight:900; background:#fff; font-size:13px; cursor:pointer; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; overflow:hidden; }
        .toolbar { padding:16px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; gap:12px; align-items:center; }
        .search { width:320px; max-width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 12px; }
        .table-wrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; min-width:980px; font-size:14px; }
        th,td { padding:12px 14px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
        th { background:#fafafa; color:#42526b; font-size:12px; text-transform:uppercase; }
        .muted { color:var(--muted); font-size:13px; }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        form { display:inline; }
        .bulk-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px; }
        .bulk-card { background:#fff; border:1px solid var(--line); border-radius:8px; padding:18px; }
        .bulk-card h2 { margin:0 0 6px; font-size:18px; }
        .bulk-card p { color:var(--muted); margin:0 0 14px; font-size:13px; line-height:1.5; }
        .bulk-form { display:grid; gap:10px; }
        .bulk-form input,.bulk-form select,.bulk-form textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 12px; font:inherit; }
        .bulk-form textarea { min-height:105px; resize:vertical; }
        .bulk-actions { display:flex; flex-wrap:wrap; gap:8px; }
        @media (max-width:980px) { .brand-row,.head { display:block; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; margin-top:10px; } }
        @media (max-width:760px) { .bulk-grid { grid-template-columns:1fr; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a>
        <div class="brand-center">E-LEARNING</div>
        <div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a href="<?= site_url('logout') ?>">Logout</a></div>
    </div>
</header>
<main class="page">
    <?php if (session()->getFlashdata('module_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('module_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('module_error')): ?><div class="error"><?= esc(session()->getFlashdata('module_error')) ?></div><?php endif; ?>
    <div class="head">
        <div><div class="kicker"><?= esc($title_main ?: 'Users') ?></div><h1><?= esc($title) ?></h1><div class="muted"><?= count($records ?? []) ?> users</div></div>
        <a class="btn primary" href="<?= site_url('manage/userdata/create') ?>">Create User</a>
    </div>
    <div class="bulk-grid">
        <section class="bulk-card">
            <h2>Bulk User Import</h2>
            <p>Validate up to 1,000 new users before committing. Imports are all-or-nothing and restricted to your company scope.</p>
            <form class="bulk-form" method="post" enctype="multipart/form-data" action="<?= site_url('manage/userdata/bulk/import') ?>"><?= csrf_field() ?>
                <input type="file" name="user_file" accept=".xlsx,.xls,.csv" required>
                <div class="bulk-actions">
                    <button class="btn" type="submit" name="commit" value="0">Validate / Dry-run</button>
                    <button class="btn primary" type="submit" name="commit" value="1" onclick="return confirm('Import all validated users?')">Import Users</button>
                    <a class="btn" href="<?= site_url('manage/userdata/bulk/template') ?>">Download Template</a>
                </div>
            </form>
        </section>
        <section class="bulk-card">
            <h2>Bulk Enrollment</h2>
            <p>Enroll active employees or safely remove learners who have not started. Employee codes may be separated by a new line, comma, or semicolon.</p>
            <form class="bulk-form" method="post" action="<?= site_url('manage/userdata/bulk/enrollment') ?>"><?= csrf_field() ?>
                <select name="course_id" required>
                    <option value="">Select course</option>
                    <?php foreach (($courses ?? []) as $course): ?>
                        <option value="<?= (int) $course['cos_id'] ?>"><?= esc(($course['ccode'] ?: '#'.$course['cos_id']) . ' — ' . ($course['cname_eng'] ?: $course['cname_th'])) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="employee_codes" required placeholder="EMP001&#10;EMP002&#10;EMP003"></textarea>
                <input name="cancel_note" maxlength="1000" placeholder="Reason (used when unenrolling)">
                <div class="bulk-actions">
                    <button class="btn primary" type="submit" name="enrollment_action" value="enroll">Enroll Selected Users</button>
                    <button class="btn" type="submit" name="enrollment_action" value="unenroll" onclick="return confirm('Unenroll users who have not started this course?')">Safe Unenroll</button>
                </div>
            </form>
        </section>
    </div>
    <section class="panel">
        <div class="toolbar"><strong>User Accounts</strong><input class="search" type="search" placeholder="Search users" oninput="filterRows(this.value)"></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>User</th><th>Employee</th><th>Company</th><th>Group</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach (($records ?? []) as $record): ?>
                        <tr data-filter="<?= esc(strtolower(implode(' ', array_map('strval', $record)))) ?>">
                            <td><strong><?= esc($record['useri']) ?></strong><div class="muted">UID <?= esc($record['u_id']) ?></div></td>
                            <td><?= esc($record['fullname_en'] ?: $record['fullname_th']) ?><div class="muted"><?= esc($record['emp_c']) ?> | <?= esc($record['email']) ?></div></td>
                            <td><?= esc($record['com_code']) ?><div class="muted"><?= esc($record['com_name_eng'] ?: $record['com_name_th']) ?></div></td>
                            <td><?= esc($record['ug_name_en'] ?: $record['ug_name_th']) ?></td>
                            <td><?= (string) $record['u_status'] === '1' ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a class="btn" href="<?= site_url('manage/userdata/' . $record['u_id'] . '/edit') ?>">Edit</a>
                                <form method="post" action="<?= site_url('manage/userdata/' . $record['u_id'] . '/status') ?>"><?= csrf_field() ?>
                                    <input type="hidden" name="status" value="<?= (string) $record['u_status'] === '1' ? '0' : '1' ?>">
                                    <button class="btn" type="submit"><?= (string) $record['u_status'] === '1' ? 'Deactivate' : 'Activate' ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($records)): ?><tr><td colspan="6" class="muted">No users found.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<script>
function filterRows(value) {
    const keyword = String(value || '').trim().toLowerCase();
    document.querySelectorAll('[data-filter]').forEach((row) => {
        row.style.display = row.dataset.filter.includes(keyword) ? '' : 'none';
    });
}
</script>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
