<?php
$pageLabel = $title_main ? $title_main . ' / ' . $title : $title;
$records = $module['records'] ?? [];
$columns = $module['columns'] ?? [];
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageLabel) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#697386; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --soft:#fafafa; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); letter-spacing:.4px; }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; align-items:center; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .main-nav { max-width:1250px; margin:0 auto; display:flex; gap:2px; padding:0 22px; overflow:visible; }
        .nav-item { position:relative; }
        .nav-link { display:flex; align-items:center; gap:9px; min-height:56px; padding:0 13px; color:#5b6573; font-weight:700; font-size:14px; border-bottom:3px solid transparent; white-space:nowrap; }
        .nav-item.active > .nav-link, .nav-link:hover { color:var(--brand); border-color:var(--brand); }
        .nav-icon { width:10px; height:10px; border-radius:2px; background:currentColor; display:inline-block; }
        .dropdown { display:none; position:absolute; top:56px; left:0; min-width:250px; background:#fff; border:1px solid var(--line); box-shadow:0 18px 40px rgba(15,23,42,.12); padding:10px 0; }
        .nav-item:hover > .dropdown { display:block; }
        .dropdown a { display:flex; gap:10px; align-items:center; padding:12px 16px; color:#4b5563; font-size:14px; }
        .dropdown a:hover { color:var(--brand); background:#fafafa; }
        .page { max-width:1250px; margin:0 auto; padding:28px 22px 42px; }
        .page-head { display:flex; justify-content:space-between; align-items:flex-end; gap:20px; margin-bottom:18px; }
        .kicker { color:var(--brand); font-weight:900; font-size:12px; text-transform:uppercase; }
        h1 { margin:5px 0 0; font-size:30px; letter-spacing:0; }
        .sub { margin:7px 0 0; color:var(--muted); font-size:14px; line-height:1.55; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end; }
        .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:10px 14px; font-weight:800; font-size:13px; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .metrics { display:grid; grid-template-columns:repeat(4, 1fr); gap:14px; margin-bottom:18px; }
        .metric, .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; }
        .metric { padding:17px 18px; }
        .metric span { display:block; color:var(--muted); font-size:12px; margin-bottom:7px; }
        .metric strong { font-size:26px; }
        .panel { padding:18px; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid var(--line); padding-bottom:14px; margin-bottom:16px; }
        .toolbar-title { display:flex; gap:10px; align-items:center; font-weight:900; }
        .pill { display:inline-flex; border-radius:999px; padding:5px 9px; background:#eef2f7; color:#475569; font-size:12px; font-weight:800; }
        .search { max-width:360px; width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; }
        .table-wrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        th, td { text-align:left; padding:13px 12px; border-bottom:1px solid var(--line); vertical-align:top; max-width:340px; }
        th { color:#566176; font-size:12px; text-transform:uppercase; background:var(--soft); white-space:nowrap; }
        td { color:#374151; line-height:1.45; }
        .empty { min-height:250px; display:grid; place-items:center; text-align:center; color:var(--muted); }
        .empty strong { display:block; color:var(--ink); font-size:20px; margin-bottom:6px; }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .inline-form { display:inline; }
        .small-action { border:1px solid var(--brand); background:var(--brand); color:#fff; border-radius:6px; padding:8px 10px; font-size:12px; font-weight:900; cursor:pointer; white-space:nowrap; }
        .small-action.secondary { background:#fff; color:#374151; border-color:var(--line); }
        .form-grid { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:12px; align-items:end; }
        .field label { display:block; color:var(--muted); font-size:12px; font-weight:800; margin-bottom:6px; }
        .field input, .field select { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 11px; font-size:14px; }
        .form-panel { margin-bottom:18px; }
        @media (max-width: 980px) {
            .brand-row { grid-template-columns:1fr; }
            .brand-center, .brand-actions { text-align:left; justify-content:flex-start; }
            .main-nav { overflow:auto; }
            .page-head { display:block; }
            .actions { justify-content:flex-start; margin-top:14px; }
            .metrics { grid-template-columns:repeat(2, 1fr); }
        }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a>
        <div class="brand-center">E-LEARNING</div>
        <div class="brand-actions">
            <span><?= esc($name ?? '-') ?></span>
            <a class="logout" href="<?= site_url('logout') ?>">Logout</a>
        </div>
    </div>
    <nav class="main-nav">
        <?php foreach (($menus ?? []) as $menu): ?>
            <div class="nav-item <?= ($menu['path'] ?? '') === $path || ($menu['name'] ?? '') === $title_main ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url($menu['path']) ?>"><span class="nav-icon"></span><?= esc($menu['name']) ?></a>
                <?php if (! empty($menu['children'])): ?>
                    <div class="dropdown">
                        <?php foreach ($menu['children'] as $child): ?>
                            <a href="<?= site_url($child['path']) ?>"><span class="nav-icon"></span><?= esc($child['name']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</header>

<main class="page">
    <?php if (session()->getFlashdata('module_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('module_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('module_error')): ?><div class="error"><?= esc(session()->getFlashdata('module_error')) ?></div><?php endif; ?>
    <div class="page-head">
        <div>
            <div class="kicker"><?= esc($title_main ?: 'LMS Module') ?></div>
            <h1><?= esc($title) ?></h1>
            <p class="sub">Route <strong><?= esc($path) ?></strong> is now served by CI4 with live data from <strong><?= esc($module['source'] ?? '-') ?></strong>.</p>
        </div>
        <div class="actions">
            <a class="btn" href="<?= site_url('dashboard') ?>">Dashboard</a>
            <?php if (($module['mode'] ?? '') === 'user'): ?><a class="btn primary" href="<?= site_url('manage/userdata/create') ?>">Create User</a><?php endif; ?>
            <span class="btn primary"><?= in_array(($module['mode'] ?? ''), ['company', 'department', 'unlock', 'reset_password'], true) ? 'Migrated actions' : 'Migrated read-only' ?></span>
        </div>
    </div>

    <section class="metrics">
        <?php foreach (($module['summary'] ?? []) as $metric): ?>
            <div class="metric"><span><?= esc($metric['label']) ?></span><strong><?= esc($metric['value']) ?></strong></div>
        <?php endforeach; ?>
    </section>

    <?php if (($module['mode'] ?? '') === 'company'): ?>
        <section class="panel form-panel">
            <div class="toolbar-title" style="margin-bottom:14px">Create Company</div>
            <form method="post" action="<?= site_url('manage/companydata/create') ?>" class="form-grid"><?= csrf_field() ?>
                <div class="field"><label>Code</label><input name="com_code" maxlength="5" required></div>
                <div class="field"><label>English Name</label><input name="com_name_eng" required></div>
                <div class="field"><label>Thai Name</label><input name="com_name_th"></div>
                <div class="field"><label>Email Domain</label><input name="com_emaildomain"></div>
                <div class="field"><label>Email</label><input name="com_mail" type="email"></div>
                <div class="field"><label>Telephone</label><input name="com_tel"></div>
                <div class="field"><label>Fax</label><input name="com_fax"></div>
                <div class="field"><button class="small-action" type="submit" style="width:100%;padding:11px">Create Company</button></div>
            </form>
        </section>
    <?php elseif (($module['mode'] ?? '') === 'department'): ?>
        <section class="panel form-panel">
            <div class="toolbar-title" style="margin-bottom:14px">Create Department</div>
            <form method="post" action="<?= site_url('manage/departmentdata/create') ?>" class="form-grid"><?= csrf_field() ?>
                <div class="field">
                    <label>Company</label>
                    <select name="com_id" required>
                        <option value="">Select company</option>
                        <?php foreach (($module['companies'] ?? []) as $company): ?>
                            <option value="<?= esc($company['com_id']) ?>"><?= esc($company['com_code'] . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>English Name</label><input name="dep_name_en" required></div>
                <div class="field"><label>Thai Name</label><input name="dep_name_th"></div>
                <div class="field"><label>Remark</label><input name="dep_remark"></div>
                <div class="field"><button class="small-action" type="submit" style="width:100%;padding:11px">Create Department</button></div>
            </form>
        </section>
    <?php endif; ?>

    <section class="panel">
        <div class="toolbar">
            <div class="toolbar-title">
                <span><?= esc($title) ?></span>
                <span class="pill"><?= esc($module['mode'] ?? 'overview') ?></span>
                <span class="pill"><?= count($records) ?> rows</span>
            </div>
            <input class="search" type="search" placeholder="Search displayed records" oninput="filterRows(this.value)">
        </div>

        <?php if (empty($records)): ?>
            <div class="empty"><div><strong>No records found</strong><span>This module is routed in CI4, but no data matched the current source query.</span></div></div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th><?= esc($column) ?></th>
                        <?php endforeach; ?>
                        <?php if (in_array(($module['mode'] ?? ''), ['unlock', 'reset_password', 'company', 'department', 'user'], true)): ?><th>Action</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr data-filter="<?= esc(strtolower(implode(' ', array_map('strval', $record)))) ?>">
                            <?php foreach ($columns as $column): ?>
                                <td><?= esc((string) ($record[$column] ?? '-')) ?></td>
                            <?php endforeach; ?>
                            <?php if (($module['mode'] ?? '') === 'unlock'): ?>
                                <td>
                                    <form class="inline-form" method="post" action="<?= site_url('dashboard/unlockAcc/' . $record['u_id']) ?>"><?= csrf_field() ?>
                                        <button class="small-action" type="submit">Unlock</button>
                                    </form>
                                </td>
                            <?php elseif (($module['mode'] ?? '') === 'reset_password'): ?>
                                <td>
                                    <form class="inline-form" method="post" action="<?= site_url('dashboard/resetPass/' . $record['u_id']) ?>" onsubmit="return confirm('Reset password for <?= esc($record['title']) ?>?')"><?= csrf_field() ?>
                                        <button class="small-action" type="submit">Reset</button>
                                    </form>
                                </td>
                            <?php elseif (($module['mode'] ?? '') === 'company'): ?>
                                <td>
                                    <a class="small-action secondary" href="<?= site_url('manage/companydata/' . $record['com_id'] . '/edit') ?>">Edit</a>
                                    <form class="inline-form" method="post" action="<?= site_url('manage/companydata/' . $record['com_id'] . '/status') ?>"><?= csrf_field() ?>
                                        <input type="hidden" name="status" value="<?= (string) ($record['com_status'] ?? '') === '1' ? '0' : '1' ?>">
                                        <button class="small-action <?= (string) ($record['com_status'] ?? '') === '1' ? 'secondary' : '' ?>" type="submit"><?= (string) ($record['com_status'] ?? '') === '1' ? 'Deactivate' : 'Activate' ?></button>
                                    </form>
                                </td>
                            <?php elseif (($module['mode'] ?? '') === 'department'): ?>
                                <td>
                                    <a class="small-action secondary" href="<?= site_url('manage/departmentdata/' . $record['dep_id'] . '/edit') ?>">Edit</a>
                                    <form class="inline-form" method="post" action="<?= site_url('manage/departmentdata/' . $record['dep_id'] . '/status') ?>"><?= csrf_field() ?>
                                        <input type="hidden" name="status" value="<?= (string) ($record['dep_status'] ?? '') === '1' ? '0' : '1' ?>">
                                        <button class="small-action <?= (string) ($record['dep_status'] ?? '') === '1' ? 'secondary' : '' ?>" type="submit"><?= (string) ($record['dep_status'] ?? '') === '1' ? 'Deactivate' : 'Activate' ?></button>
                                    </form>
                                </td>
                            <?php elseif (($module['mode'] ?? '') === 'user'): ?>
                                <td>
                                    <a class="small-action secondary" href="<?= site_url('manage/userdata/' . $record['emp_id'] . '/edit') ?>">Edit</a>
                                    <form class="inline-form" method="post" action="<?= site_url('manage/userdata/' . $record['emp_id'] . '/status') ?>"><?= csrf_field() ?>
                                        <input type="hidden" name="status" value="<?= (string) ($record['status'] ?? '') === '1' ? '0' : '1' ?>">
                                        <button class="small-action <?= (string) ($record['status'] ?? '') === '1' ? 'secondary' : '' ?>" type="submit"><?= (string) ($record['status'] ?? '') === '1' ? 'Deactivate' : 'Activate' ?></button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
function filterRows(value) {
    const keyword = String(value || '').trim().toLowerCase();
    document.querySelectorAll('[data-filter]').forEach((node) => {
        node.style.display = node.dataset.filter.includes(keyword) ? '' : 'none';
    });
}
</script>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
