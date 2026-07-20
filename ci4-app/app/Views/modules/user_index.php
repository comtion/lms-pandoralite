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
        @media (max-width:980px) { .brand-row,.head { display:block; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; margin-top:10px; } }
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
                                <form method="post" action="<?= site_url('manage/userdata/' . $record['u_id'] . '/status') ?>">
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
