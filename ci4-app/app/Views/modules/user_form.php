<?php
$isEdit = $mode === 'edit';
$value = static fn (string $key, string $default = '') => old($key, (string) ($record[$key] ?? $default));
?>
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
        .brand-row { max-width:1120px; margin:0 auto; min-height:72px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; color:var(--brand); font-size:20px; }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .page { max-width:1120px; margin:0 auto; padding:28px 22px 44px; }
        .panel { background:#fff; border:1px solid var(--line); border-radius:8px; padding:22px; margin-bottom:16px; }
        .kicker { color:var(--brand); font-size:12px; font-weight:900; text-transform:uppercase; }
        h1 { margin:6px 0 0; font-size:30px; }
        h2 { font-size:18px; margin:0 0 16px; }
        .grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
        .field label { display:block; font-size:12px; font-weight:900; color:var(--muted); margin-bottom:6px; }
        .field input,.field select { width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; background:#fff; }
        .actions { display:flex; justify-content:space-between; gap:12px; align-items:center; }
        .btn { border:1px solid var(--line); border-radius:7px; padding:11px 15px; font-weight:900; background:#fff; cursor:pointer; font-size:14px; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        @media (max-width:900px) { .brand-row,.grid { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } .actions { display:block; } .actions .btn { width:100%; margin-top:10px; } }
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
    <section class="panel">
        <div class="kicker"><?= $isEdit ? 'Edit Account' : 'Create Account' ?></div>
        <h1><?= esc($title) ?></h1>
    </section>

    <form method="post" action="<?= $isEdit ? site_url('manage/userdata/' . $record['u_id'] . '/update') : site_url('manage/userdata/create') ?>">
        <section class="panel">
            <h2>Account</h2>
            <div class="grid">
                <div class="field"><label>Username</label><input name="useri" value="<?= esc($value('useri')) ?>" required></div>
                <div class="field"><label>Password <?= $isEdit ? '(leave blank to keep)' : '' ?></label><input name="password" type="password" <?= $isEdit ? '' : 'required' ?>></div>
                <div class="field">
                    <label>User Group</label>
                    <select name="ug_id" required>
                        <option value="">Select group</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= esc($group['ug_id']) ?>" <?= $value('ug_id') === (string) $group['ug_id'] ? 'selected' : '' ?>><?= esc($group['ug_code'] . ' - ' . ($group['ug_name_en'] ?: $group['ug_name_th'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status"><option value="1" <?= $value('status', '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= $value('status') === '0' ? 'selected' : '' ?>>Inactive</option></select>
                </div>
                <div class="field">
                    <label>Language</label>
                    <select name="lang"><option value="english" <?= $value('lang', 'english') === 'english' ? 'selected' : '' ?>>English</option><option value="thai" <?= $value('lang') === 'thai' ? 'selected' : '' ?>>Thai</option><option value="japan" <?= $value('lang') === 'japan' ? 'selected' : '' ?>>Japan</option></select>
                </div>
            </div>
        </section>

        <section class="panel">
            <h2>Employee</h2>
            <div class="grid">
                <div class="field"><label>Employee Code</label><input name="emp_c" value="<?= esc($value('emp_c')) ?>" required></div>
                <div class="field"><label>Email</label><input name="email" type="email" value="<?= esc($value('email')) ?>"></div>
                <div class="field"><label>Phone</label><input name="phone" value="<?= esc($value('phone')) ?>"></div>
                <div class="field"><label>Prefix EN</label><input name="prefix_en" value="<?= esc($value('prefix_en')) ?>"></div>
                <div class="field"><label>First Name EN</label><input name="fname_en" value="<?= esc($value('fname_en')) ?>" required></div>
                <div class="field"><label>Last Name EN</label><input name="lname_en" value="<?= esc($value('lname_en')) ?>" required></div>
                <div class="field"><label>Prefix TH</label><input name="prefix_th" value="<?= esc($value('prefix_th')) ?>"></div>
                <div class="field"><label>First Name TH</label><input name="fname_th" value="<?= esc($value('fname_th')) ?>"></div>
                <div class="field"><label>Last Name TH</label><input name="lname_th" value="<?= esc($value('lname_th')) ?>"></div>
                <div class="field">
                    <label>Company</label>
                    <select name="com_id" required>
                        <option value="">Select company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= esc($company['com_id']) ?>" <?= $value('com_id') === (string) $company['com_id'] ? 'selected' : '' ?>><?= esc($company['com_code'] . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Department</label>
                    <select name="dep_id">
                        <option value="0">No department</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= esc($department['dep_id']) ?>" <?= $value('dep_id') === (string) $department['dep_id'] ? 'selected' : '' ?>><?= esc($department['dep_name_en'] ?: $department['dep_name_th']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>Gender</label><input name="gender" value="<?= esc($value('gender')) ?>"></div>
                <div class="field"><label>Nationality</label><input name="emp_nationality" value="<?= esc($value('emp_nationality')) ?>"></div>
                <div class="field"><label>Employ Date</label><input name="employ_date" type="date" value="<?= str_starts_with($value('employ_date'), '0000-00-00') ? '' : esc($value('employ_date')) ?>"></div>
                <div class="field"><label>Depart Date</label><input name="depart_date" type="date" value="<?= str_starts_with($value('depart_date'), '0000-00-00') ? '' : esc($value('depart_date')) ?>"></div>
            </div>
        </section>

        <section class="panel actions">
            <a class="btn" href="<?= site_url('manage/userdata') ?>">Back</a>
            <button class="btn primary" type="submit"><?= $isEdit ? 'Update User' : 'Create User' ?></button>
        </section>
    </form>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
