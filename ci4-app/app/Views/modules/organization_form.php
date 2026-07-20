<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#697386; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .main-nav { max-width:1250px; margin:0 auto; display:flex; gap:2px; padding:0 22px; overflow:auto; }
        .nav-link { display:flex; align-items:center; min-height:56px; padding:0 13px; color:#5b6573; font-weight:700; font-size:14px; border-bottom:3px solid transparent; white-space:nowrap; }
        .nav-link:hover { color:var(--brand); border-color:var(--brand); }
        .page { max-width:980px; margin:0 auto; padding:28px 22px 42px; }
        .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:22px; }
        .kicker { color:var(--brand); font-weight:900; font-size:12px; text-transform:uppercase; }
        h1 { margin:5px 0 18px; font-size:30px; }
        .form-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:14px; }
        .field.full { grid-column:1 / -1; }
        label { display:block; color:var(--muted); font-size:12px; font-weight:800; margin-bottom:6px; }
        input, select, textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; background:#fff; }
        textarea { min-height:92px; resize:vertical; }
        .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:18px; }
        .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:11px 15px; font-weight:900; cursor:pointer; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        @media (max-width:760px) { .brand-row, .form-grid { grid-template-columns:1fr; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand-row">
        <a class="brand-mark" href="<?= site_url('dashboard') ?>">LMS CI4</a>
        <div class="brand-center">E-LEARNING</div>
        <div class="brand-actions"><span><?= esc($name ?? '-') ?></span><a class="logout" href="<?= site_url('logout') ?>">Logout</a></div>
    </div>
    <nav class="main-nav">
        <?php foreach (($menus ?? []) as $menu): ?>
            <a class="nav-link" href="<?= site_url($menu['path']) ?>"><?= esc($menu['name']) ?></a>
        <?php endforeach; ?>
    </nav>
</header>

<main class="page">
    <section class="panel">
        <div class="kicker"><?= esc($title_main ?: 'Organization') ?></div>
        <h1><?= esc($title) ?></h1>

        <?php if ($type === 'company'): ?>
            <form method="post" action="<?= site_url('manage/companydata/' . $record['com_id'] . '/update') ?>">
                <div class="form-grid">
                    <div class="field"><label>Code</label><input name="com_code" maxlength="5" required value="<?= esc($record['com_code']) ?>"></div>
                    <div class="field"><label>English Name</label><input name="com_name_eng" required value="<?= esc($record['com_name_eng']) ?>"></div>
                    <div class="field"><label>Thai Name</label><input name="com_name_th" value="<?= esc($record['com_name_th']) ?>"></div>
                    <div class="field"><label>Email Domain</label><input name="com_emaildomain" value="<?= esc($record['com_emaildomain']) ?>"></div>
                    <div class="field"><label>Email</label><input name="com_mail" type="email" value="<?= esc($record['com_mail']) ?>"></div>
                    <div class="field"><label>Telephone</label><input name="com_tel" value="<?= esc($record['com_tel']) ?>"></div>
                    <div class="field"><label>Fax</label><input name="com_fax" value="<?= esc($record['com_fax']) ?>"></div>
                    <div class="field full"><label>Thai Address</label><textarea name="com_add_th"><?= esc($record['com_add_th']) ?></textarea></div>
                    <div class="field full"><label>English Address</label><textarea name="com_add_eng"><?= esc($record['com_add_eng']) ?></textarea></div>
                </div>
                <div class="actions"><a class="btn" href="<?= site_url('manage/companydata') ?>">Cancel</a><button class="btn primary" type="submit">Save Company</button></div>
            </form>
        <?php else: ?>
            <form method="post" action="<?= site_url('manage/departmentdata/' . $record['dep_id'] . '/update') ?>">
                <div class="form-grid">
                    <div class="field full">
                        <label>Company</label>
                        <select name="com_id" required>
                            <?php foreach (($companies ?? []) as $company): ?>
                                <option value="<?= esc($company['com_id']) ?>" <?= (string) $company['com_id'] === (string) $record['com_id'] ? 'selected' : '' ?>>
                                    <?= esc($company['com_code'] . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field"><label>English Name</label><input name="dep_name_en" required value="<?= esc($record['dep_name_en']) ?>"></div>
                    <div class="field"><label>Thai Name</label><input name="dep_name_th" value="<?= esc($record['dep_name_th']) ?>"></div>
                    <div class="field full"><label>Remark</label><textarea name="dep_remark"><?= esc($record['dep_remark']) ?></textarea></div>
                </div>
                <div class="actions"><a class="btn" href="<?= site_url('manage/departmentdata') ?>">Cancel</a><button class="btn primary" type="submit">Save Department</button></div>
            </form>
        <?php endif; ?>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
