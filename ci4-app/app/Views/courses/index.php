<?php
$isGroup = $mode === 'groups';
$pageLabel = $title_main ? $title_main . ' / ' . $title : $title;
$isThai = ($lang ?? 'english') === 'thai';
$displayName = trim((string) ($name ?? '')) ?: ($isThai ? 'ผู้เรียน' : 'Learner');
$roleName = $isThai
    ? ($user['ug_name_th'] ?? $user['ug_name_en'] ?? '-')
    : ($user['ug_name_en'] ?? $user['ug_name_th'] ?? '-');
$companyName = $isThai
    ? ($user['com_name_th'] ?? $user['com_name_eng'] ?? '-')
    : ($user['com_name_eng'] ?? $user['com_name_th'] ?? '-');
$profileImage = ! empty($user['img_profile']) ? base_url('uploads/profile/' . $user['img_profile']) : base_url('uploads/profile/default_profile.jpg');
$menuIcon = static function (string $icon): string {
    $key = trim(str_replace(['mdi mdi-', 'mdi-', 'mdi '], '', $icon));
    $map = [
        '' => 'bi bi-circle-fill',
        'view-dashboard' => 'bi bi-grid-1x2-fill',
        'settings' => 'bi bi-gear-fill',
        'book-open-page-variant' => 'bi bi-book-half',
        'book-open' => 'bi bi-book',
        'file-document' => 'bi bi-file-earmark-text-fill',
        'file-document-box' => 'bi bi-file-earmark-richtext',
        'chart-areaspline' => 'bi bi-graph-up-arrow',
        'chart-bar' => 'bi bi-bar-chart-line',
        'chart-gantt' => 'bi bi-kanban',
        'qrcode' => 'bi bi-qr-code',
        'account-settings-variant' => 'bi bi-person-gear',
        'account-settings' => 'bi bi-person-gear',
        'account' => 'bi bi-person',
        'account-key' => 'bi bi-person-lock',
        'account-multiple' => 'bi bi-people',
        'group' => 'bi bi-collection',
        'certificate' => 'bi bi-award',
        'lead-pencil' => 'bi bi-pencil-square',
        'store' => 'bi bi-building',
        'history' => 'bi bi-clock-history',
        'email' => 'bi bi-envelope-at',
        'email-variant' => 'bi bi-envelope-paper',
        'email-secure' => 'bi bi-shield-lock',
        'folder-account' => 'bi bi-folder2-open',
        'folder-image' => 'bi bi-images',
        'comment-question-outline' => 'bi bi-question-circle',
        'menu' => 'bi bi-list',
        'format-list-bulleted-type' => 'bi bi-list-check',
    ];

    return $map[$key] ?? 'bi bi-circle-fill';
};
$normalizePath = static function (string $value): string {
    $pathOnly = parse_url($value, PHP_URL_PATH) ?: $value;
    $pathOnly = preg_replace('#^/?index\.php/?#', '', $pathOnly);

    return trim(strtolower((string) $pathOnly), '/');
};
$currentPath = $normalizePath((string) ($path ?? service('uri')->getPath()));
$isActivePath = static function (string $candidate) use ($currentPath, $normalizePath): bool {
    $candidatePath = $normalizePath($candidate);

    return $candidatePath !== '' && $candidatePath === $currentPath;
};
$isActiveMenuNode = static function (array $node) use (&$isActiveMenuNode, $isActivePath): bool {
    if ($isActivePath((string) ($node['path'] ?? ''))) {
        return true;
    }

    foreach (($node['children'] ?? []) as $childNode) {
        if (is_array($childNode) && $isActiveMenuNode($childNode)) {
            return true;
        }
    }

    return false;
};
$metricMap = [
    ['label' => 'Course Groups', 'value' => $counts['groups'] ?? 0],
    ['label' => 'All Courses', 'value' => $counts['courses'] ?? 0],
    ['label' => 'Published', 'value' => $counts['public'] ?? 0],
    ['label' => 'My Courses', 'value' => $counts['my'] ?? 0],
];
$approvalText = static function ($value) use ($isThai): string {
    $state = (string) $value;
    if ($state === '1') {
        return $isThai ? 'อนุมัติ' : 'Approved';
    }
    if ($state === '0') {
        return $isThai ? 'ไม่อนุมัติ' : 'Rejected';
    }

    return $isThai ? 'อยู่ระหว่างรอการอนุมัติ' : 'Pending approval';
};
$statusText = static function ($value) use ($isThai): string {
    return (string) $value === '1' ? ($isThai ? 'เปิด' : 'Open') : ($isThai ? 'ปิด' : 'Closed');
};
$approvedByText = static function (array $item): string {
    if ((string) ($item['cg_approve'] ?? '') !== '1') {
        return '-';
    }

    return trim((string) ($item['u_by'] ?? $item['cg_approve_by'] ?? '')) ?: '-';
};
$approvedDateText = static function (array $item) use ($isThai): string {
    if ((string) ($item['cg_approve'] ?? '') !== '1' || empty($item['u_date'])) {
        return '-';
    }

    $time = strtotime((string) $item['u_date']);
    if (! $time) {
        return '-';
    }

    $year = (int) date('Y', $time) + ($isThai ? 543 : 0);
    return date('d/m/', $time) . $year . date(' H:i', $time);
};
$isSuperAdmin = (int) ($user['ug_id'] ?? 0) === 1
    || str_contains(strtolower((string) (($user['ug_name_en'] ?? '') . ' ' . ($user['ug_name_th'] ?? ''))), 'super admin')
    || str_contains(strtolower((string) (($user['ug_name_en'] ?? '') . ' ' . ($user['ug_name_th'] ?? ''))), 'superadmin');
$isApprovedLocked = static function (array $item) use ($isSuperAdmin): bool {
    return (string) ($item['cg_approve'] ?? '') === '1' && ! $isSuperAdmin;
};
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageLabel) ?></title>
    <?php if ($isGroup): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php endif; ?>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --green:#21b36b; --yellow:#d8a300; --gray:#8b929d; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:76px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:800; font-size:20px; color:var(--brand); letter-spacing:.5px; }
        .brand-center { text-align:center; font-size:30px; font-weight:900; }
        .brand-actions { display:flex; align-items:center; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:700; }
        .main-nav { max-width:1250px; margin:0 auto; display:flex; gap:2px; padding:0 22px; overflow:visible; }
        .nav-item { position:relative; }
        .nav-link { display:flex; align-items:center; gap:9px; min-height:58px; padding:0 13px; color:#5b6573; font-weight:700; font-size:14px; border-bottom:3px solid transparent; white-space:nowrap; }
        .nav-item.active > .nav-link, .nav-link:hover { color:var(--brand); border-color:var(--brand); }
        .nav-icon { width:10px; height:10px; border-radius:2px; background:currentColor; display:inline-block; }
        .dropdown { display:none; position:absolute; top:58px; left:0; min-width:238px; background:#fff; border:1px solid var(--line); box-shadow:0 18px 40px rgba(15,23,42,.12); padding:10px 0; }
        .nav-item:hover > .dropdown { display:block; }
        .dropdown a { display:flex; gap:10px; align-items:center; padding:12px 16px; color:#4b5563; font-size:14px; }
        .dropdown a:hover { color:var(--brand); background:#fafafa; }
        .page { max-width:1500px; margin:0 auto; padding:28px; }
        .page-head { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:20px; }
        .kicker { color:var(--brand); font-weight:800; font-size:12px; text-transform:uppercase; }
        h1 { margin:4px 0 0; font-size:30px; letter-spacing:0; }
        .sub { margin:7px 0 0; color:var(--muted); font-size:14px; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end; }
        .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:10px 14px; font-weight:700; font-size:13px; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .metrics { display:grid; grid-template-columns:repeat(4, 1fr); gap:14px; margin-bottom:18px; }
        .metric, .panel { background:var(--panel); border:1px solid #eee3ce; border-radius:18px; }
        .metric { padding:17px 18px; box-shadow:0 10px 28px rgba(31,41,55,.05); }
        .metric span { display:block; color:var(--muted); font-size:12px; margin-bottom:7px; }
        .metric strong { font-size:26px; }
        .panel { padding:20px; box-shadow:0 12px 32px rgba(31,41,55,.055); }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid var(--line); padding-bottom:14px; margin-bottom:16px; }
        .search { max-width:360px; width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; }
        .grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:16px; }
        .course-card { border:1px solid var(--line); border-radius:8px; overflow:hidden; background:#fff; min-width:0; }
        .cover { aspect-ratio:16/9; background:#f3f4f6 center/contain no-repeat; border-bottom:1px solid var(--line); }
        .course-body { padding:15px; }
        .course-title { font-size:16px; font-weight:800; line-height:1.35; min-height:43px; margin:0 0 8px; }
        .course-desc { color:var(--muted); font-size:13px; line-height:1.5; min-height:40px; margin:0 0 13px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
        .meta { display:grid; gap:8px; font-size:13px; color:#4b5563; }
        .meta-row { display:flex; justify-content:space-between; gap:12px; border-top:1px solid #f1f2f4; padding-top:8px; }
        .badge { display:inline-flex; align-items:center; border-radius:999px; padding:5px 9px; font-size:12px; font-weight:800; background:#f3f4f6; color:#374151; }
        .badge.good { background:#eaf8f1; color:#087443; }
        .badge.warn { background:#fff7dc; color:#8a6300; }
        .row-actions { display:flex; gap:7px; flex-wrap:wrap; align-items:center; }
        .icon-action { border:1px solid var(--line); border-radius:6px; background:#fff; color:#374151; padding:7px 9px; line-height:1; cursor:pointer; font-weight:800; }
        .icon-action:hover { color:var(--brand); border-color:var(--brand); }
        .icon-action.danger:hover { color:#b42318; border-color:#ffc7c7; background:#fff1f1; }
        .modal label { color:#566176; font-size:12px; font-weight:800; margin-bottom:6px; }
        .modal input, .modal select, .modal textarea { border-radius:7px; }
        .modal textarea { min-height:84px; resize:vertical; }
        .empty { min-height:250px; display:grid; place-items:center; text-align:center; color:var(--muted); }
        .empty strong { display:block; color:var(--ink); font-size:20px; margin-bottom:6px; }
        .table-wrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        th, td { text-align:left; padding:13px 12px; border-bottom:1px solid var(--line); vertical-align:top; }
        th { color:#566176; font-size:12px; text-transform:uppercase; background:#fafafa; }
        @media (max-width: 980px) {
            .brand-row { grid-template-columns:1fr; text-align:left; }
            .brand-center, .brand-actions { text-align:left; justify-content:flex-start; }
            .main-nav { overflow:auto; }
            .page-head { display:block; }
            .actions { justify-content:flex-start; margin-top:14px; }
            .metrics { grid-template-columns:repeat(2, 1fr); }
            .grid { grid-template-columns:1fr; }
        }
    </style>
    <link href="<?= base_url('css/dashboard-enterprise.css?v=20260701-3') ?>" rel="stylesheet">
    <style>
        .course-shell .sidebar { height: 100vh; }
        .course-shell .main-area { min-width: 0; }
        .course-shell .topbar { grid-template-columns:auto minmax(180px, 480px) 1fr auto; }
        .course-shell .search-box { grid-column:2; }
        .course-shell .top-actions { grid-column:4; justify-self:end; margin-left:auto; }
        .course-shell .side-sublink.active,
        .course-shell .side-grandlink.active {
            background:#fff3bd;
            color:#172033;
            box-shadow:inset 0 0 0 1px rgba(245,180,0,.2), 0 8px 18px rgba(245,180,0,.12);
            transform:none;
        }
        .course-shell .side-sublink.active i,
        .course-shell .side-grandlink.active i {
            color:#a67600;
        }
        .course-shell .page-head { margin-top: 0; }
        .course-shell .metric { min-height: 108px; }
        .course-shell .toolbar { border-bottom-color: #eee3ce; }
        .course-shell table th { background: #fbfaf6; color: #6a7484; }
        .course-shell table td { color: #263247; }
        .course-shell .badge.good { background:#eaf8ef; color:#166534; }
        .course-shell .badge.warn { background:#fff3bd; color:#765400; }
        .course-shell .icon-action { border-radius:10px; min-width:42px; min-height:42px; color:#172033; }
        .course-shell .icon-action:hover { background:#fff7d6; border-color:#f1d888; color:#172033; }
        .course-shell .actions .btn.primary, .course-shell .btn.primary { background:#172033; border-color:#172033; color:#fff; }
        .course-shell .course-hero-title { margin:0; color:#111827; font-size:clamp(30px,3vw,44px); line-height:1.08; font-weight:950; }
        .course-shell .course-hero-copy { max-width:780px; margin:12px 0 20px; color:#4b5563; font-size:16px; line-height:1.7; }
        .course-shell .course-hero-panel { display:grid; gap:12px; padding:22px; border-radius:22px; background:rgba(255,255,255,.82); border:1px solid rgba(240,216,137,.65); box-shadow:0 12px 30px rgba(92,70,0,.08); }
        .course-shell .course-hero-stat { display:grid; grid-template-columns:auto 1fr; gap:12px; align-items:center; }
        .course-shell .course-hero-stat i { width:44px; height:44px; display:inline-flex; align-items:center; justify-content:center; border-radius:14px; background:#ffeaa0; color:#715000; font-size:20px; }
        .course-shell .course-hero-stat strong { display:block; color:#111827; font-size:26px; line-height:1; }
        .course-shell .course-hero-stat span { display:block; margin-top:4px; color:#6b7280; font-size:13px; }
        .course-shell .content-panel { background:#fff; border:1px solid #eee3ce; border-radius:22px; box-shadow:0 12px 32px rgba(31,41,55,.055); padding:22px; }
        .course-shell .legacy-page-title { display:flex; justify-content:space-between; gap:20px; align-items:flex-end; margin:22px 0 20px; }
        .course-shell .legacy-page-title h1 { margin:0; color:#111827; font-size:20px; font-weight:700; }
        .course-shell .legacy-breadcrumb { display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:flex-end; color:#5f6b7a; font-size:14px; }
        .course-shell .legacy-breadcrumb span:last-child { color:#e71921; }
        .course-shell .legacy-course-panel { width:100%; max-width:none; margin:0; border:0; border-radius:28px; box-shadow:0 18px 52px rgba(22,32,51,.08); padding:24px 24px 30px; background:rgba(255,255,255,.92); }
        .course-shell .legacy-filter-row { display:grid; grid-template-columns:minmax(280px, 464px) 1fr auto; gap:18px; align-items:end; margin-bottom:24px; }
        .course-shell .legacy-label { display:block; margin-bottom:8px; color:#435064; font-size:14px; }
        .course-shell .legacy-select, .course-shell .legacy-search, .course-shell .legacy-length select { width:100%; min-height:42px; border:1px solid rgba(191,199,210,.7); border-radius:14px; background:#fff; color:#455468; padding:8px 14px; font-size:14px; box-shadow:0 8px 20px rgba(22,32,51,.04); }
        .course-shell .searchable-select { position:relative; width:100%; }
        .course-shell .searchable-select .searchable-native { position:absolute; inset:auto auto 0 0; width:1px; height:1px; opacity:0; pointer-events:none; }
        .course-shell .searchable-toggle { width:100%; min-height:42px; display:flex; align-items:center; justify-content:space-between; gap:12px; border:1px solid rgba(191,199,210,.7); border-radius:14px; background:#fff; color:#455468; padding:8px 14px; font-size:14px; text-align:left; box-shadow:0 8px 20px rgba(22,32,51,.04); }
        .course-shell .searchable-toggle:focus { outline:0; border-color:#f4c542; box-shadow:0 0 0 4px rgba(245,180,0,.16); }
        .course-shell .searchable-toggle i { color:#64748b; }
        .course-shell .searchable-menu { position:absolute; z-index:30; top:calc(100% + 8px); left:0; right:0; overflow:hidden; border:1px solid rgba(220,226,235,.9); border-radius:18px; background:#fff; box-shadow:0 18px 42px rgba(22,32,51,.16); }
        .course-shell .searchable-menu[hidden] { display:none; }
        .course-shell .searchable-search { width:calc(100% - 20px); margin:10px; min-height:38px; border:1px solid rgba(191,199,210,.75); border-radius:12px; padding:7px 11px; outline:0; }
        .course-shell .searchable-search:focus { border-color:#f4c542; box-shadow:0 0 0 3px rgba(245,180,0,.12); }
        .course-shell .searchable-options { max-height:230px; overflow:auto; padding:0 8px 8px; }
        .course-shell .searchable-option { width:100%; min-height:38px; border:0; border-radius:12px; background:transparent; color:#263247; padding:8px 10px; text-align:left; }
        .course-shell .searchable-option:hover,
        .course-shell .searchable-option.active { background:#fff3bd; color:#172033; }
        .course-shell .searchable-empty { padding:10px 12px; color:#7b8494; font-size:13px; }
        .course-shell .modal .searchable-menu { z-index:1065; }
        .course-shell .legacy-create-btn { min-height:42px; display:inline-flex; align-items:center; gap:7px; border:1px solid rgba(13,110,253,.22); background:#fff; color:#0d6efd; border-radius:14px; padding:9px 16px; font-weight:700; box-shadow:0 10px 24px rgba(22,32,51,.06); }
        .course-shell .legacy-create-btn:hover { background:#f6faff; border-color:#0d6efd; }
        .course-shell .legacy-table-controls { display:flex; justify-content:space-between; gap:20px; align-items:center; margin-bottom:16px; color:#455468; font-size:14px; }
        .course-shell .legacy-length { display:inline-flex; align-items:center; gap:8px; }
        .course-shell .legacy-length select { width:72px; min-height:38px; }
        .course-shell .legacy-search-wrap { display:inline-flex; align-items:center; gap:8px; }
        .course-shell .legacy-search { width:230px; min-height:38px; border-radius:14px; padding:7px 12px; }
        .course-shell .legacy-table-wrap { overflow:auto; border:0; border-radius:22px; background:linear-gradient(180deg, rgba(255,255,255,.92), rgba(251,253,255,.92)); padding:4px; }
        .course-shell .legacy-table { min-width:1120px; border-collapse:separate; border-spacing:0 10px; font-size:14px; }
        .course-shell .legacy-table th, .course-shell .legacy-table td { border:0; text-align:center; vertical-align:middle; padding:15px 12px; color:#516173; }
        .course-shell .legacy-table th { background:transparent; font-weight:700; color:#38475a; text-transform:none; }
        .course-shell .legacy-table tbody td { background:#fff; box-shadow:0 10px 24px rgba(22,32,51,.04); }
        .course-shell .legacy-table tbody tr:nth-child(odd) td { background:#eef3f9; }
        .course-shell .legacy-table tbody td:first-child { border-radius:18px 0 0 18px; }
        .course-shell .legacy-table tbody td:last-child { border-radius:0 18px 18px 0; }
        .course-shell .legacy-table td:nth-child(2), .course-shell .legacy-table td:nth-child(3), .course-shell .legacy-table td:nth-child(4) { text-align:left; }
        .course-shell .legacy-actions { display:flex; gap:7px; justify-content:center; align-items:center; flex-wrap:wrap; }
        .course-shell .legacy-action { width:34px; height:34px; border:0; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; color:#fff; cursor:pointer; font-size:15px; box-shadow:0 8px 18px rgba(22,32,51,.1); }
        .course-shell .legacy-action.edit { background:#ffb21a; }
        .course-shell .legacy-action.delete { background:#ff0000; }
        .course-shell .legacy-action.approve { background:#1aa665; }
        .course-shell .legacy-action.state { background:#64748b; }
        .course-shell .legacy-action.locked { background:#d1d5db; color:#6b7280; cursor:not-allowed; box-shadow:none; opacity:.75; }
        .course-shell .legacy-inline-form { margin:0; display:inline-flex; }
        .course-shell .legacy-empty { min-height:160px; display:grid; place-items:center; color:#6b7280; }
        @media (max-width: 900px) {
            .course-shell .legacy-page-title, .course-shell .legacy-table-controls { display:block; }
            .course-shell .legacy-filter-row { grid-template-columns:1fr; }
            .course-shell .legacy-create-btn { width:100%; justify-content:center; }
            .course-shell .legacy-search-wrap { width:100%; margin-top:12px; justify-content:space-between; }
            .course-shell .legacy-search { width:100%; }
        }
    </style>
</head>
<body class="course-shell">
<div class="app-shell">
    <aside class="sidebar" id="dashboardSidebar">
        <div class="brand-stack">
            <a class="brand-lockup" href="<?= site_url('dashboard') ?>">
                <span class="brand-symbol">V</span>
                <span class="brand-wordmark"><strong>Verztec</strong><small>Learning Platform</small></span>
            </a>
            <span class="tenant-badge"><i class="bi bi-buildings"></i><?= esc($companyName) ?></span>
        </div>
        <nav class="sidebar-nav" aria-label="Primary navigation">
            <?php foreach (($menus ?? []) as $menu): ?>
                <?php $hasChildren = ! empty($menu['children']); ?>
                <?php $menuPath = (string) ($menu['path'] ?? 'dashboard'); ?>
                <?php $menuActive = $isActiveMenuNode($menu); ?>
                <div class="nav-item-wrap">
                    <?php if ($hasChildren): ?>
                        <button class="side-link side-toggle <?= $menuActive ? 'active' : '' ?>" type="button" aria-expanded="<?= $menuActive ? 'true' : 'false' ?>">
                            <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                            <span><?= esc($menu['name'] ?? '-') ?></span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </button>
                        <div class="side-submenu" <?= $menuActive ? '' : 'hidden' ?>>
                            <?php foreach ($menu['children'] as $child): ?>
                                <?php $childPath = (string) ($child['path'] ?? 'dashboard'); ?>
                                <?php $childActive = $isActiveMenuNode($child); ?>
                                <a class="side-sublink <?= $childActive ? 'active' : '' ?>" href="<?= site_url($childPath) ?>">
                                    <i class="<?= esc($menuIcon((string) ($child['icon'] ?? ''))) ?>"></i>
                                    <span><?= esc($child['name'] ?? '-') ?></span>
                                </a>
                                <?php if (! empty($child['children'])): ?>
                                    <div class="side-nested-submenu">
                                        <?php foreach ($child['children'] as $grandchild): ?>
                                            <?php $grandchildPath = (string) ($grandchild['path'] ?? 'dashboard'); ?>
                                            <?php $grandchildActive = $isActiveMenuNode($grandchild); ?>
                                            <a class="side-sublink side-grandlink <?= $grandchildActive ? 'active' : '' ?>" href="<?= site_url($grandchildPath) ?>">
                                                <i class="<?= esc($menuIcon((string) ($grandchild['icon'] ?? ''))) ?>"></i>
                                                <span><?= esc($grandchild['name'] ?? '-') ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <a class="side-link <?= $menuActive ? 'active' : '' ?>" href="<?= site_url($menuPath) ?>">
                            <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                            <span><?= esc($menu['name'] ?? '-') ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>
    </aside>

    <div class="main-area">
        <header class="topbar">
            <button class="menu-toggle" type="button" aria-controls="dashboardSidebar" aria-expanded="false" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
            <label class="search-box">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="<?= esc($isThai ? 'ค้นหาหลักสูตร รายงาน หรือเมนู' : 'Search courses, reports, or menus') ?>" autocomplete="off">
            </label>
            <div class="top-actions">
                <div class="language-switch" aria-label="Language">
                    <a href="<?= site_url('home/change_lang/thai') ?>" class="<?= $isThai ? 'active' : '' ?>" hreflang="th">TH</a>
                    <a href="<?= site_url('home/change_lang/english') ?>" class="<?= ! $isThai ? 'active' : '' ?>" hreflang="en">EN</a>
                </div>
                <button class="icon-button notifications" type="button" aria-label="Notifications"><i class="bi bi-bell"></i></button>
                <a class="profile-chip" href="<?= site_url('dashboard/profile/setting') ?>">
                    <img class="avatar" src="<?= esc($profileImage) ?>" onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'" alt="<?= esc($displayName) ?>">
                    <span class="profile-meta"><strong><?= esc($displayName) ?></strong><span><?= esc($roleName) ?></span></span>
                </a>
                <a class="icon-button" href="<?= site_url('logout') ?>" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </header>

<main class="dashboard-page course-page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <?php if ($isGroup): ?>
        <div class="legacy-page-title">
            <h1><?= esc($title) ?></h1>
            <div class="legacy-breadcrumb" aria-label="Breadcrumb">
                <span><?= esc($isThai ? 'หน้าหลักผู้ใช้งาน' : 'Dashboard') ?></span>
                <i class="bi bi-chevron-right"></i>
                <span><?= esc($title_main ?: ($isThai ? 'จัดการหลักสูตร' : 'Manage Courses')) ?></span>
                <i class="bi bi-chevron-right"></i>
                <span><?= esc($title) ?></span>
            </div>
        </div>

        <section class="content-panel legacy-course-panel">
            <div class="legacy-filter-row">
                <label>
                    <span class="legacy-label"><?= esc($isThai ? 'ชื่อบริษัท:' : 'Company:') ?></span>
                    <select id="companyFilter" class="legacy-select" onchange="filterCards(document.getElementById('recordSearch')?.value || '')">
                        <option value=""><?= esc($isThai ? 'บริษัททั้งหมด' : 'All companies') ?></option>
                        <?php foreach (($companies ?? []) as $company): ?>
                            <?php $companyLabel = $isThai ? ($company['com_name_th'] ?? $company['com_name_eng'] ?? '-') : ($company['com_name_eng'] ?? $company['com_name_th'] ?? '-'); ?>
                            <option value="<?= (int) ($company['com_id'] ?? 0) ?>"><?= esc($companyLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <span></span>
                <button class="legacy-create-btn" type="button" data-bs-toggle="modal" data-bs-target="#courseGroupCreateModal">
                    <i class="bi bi-plus-square"></i><?= esc($isThai ? 'สร้างกลุ่มหลักสูตร' : 'Create course group') ?>
                </button>
            </div>

            <div class="legacy-table-controls">
                <label class="legacy-length">
                    <span><?= esc($isThai ? 'แสดง' : 'Show') ?></span>
                    <select aria-label="Rows per page" data-no-search>
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span><?= esc($isThai ? 'รายการ' : 'entries') ?></span>
                </label>
                <label class="legacy-search-wrap">
                    <span><?= esc($isThai ? 'ค้นหา:' : 'Search:') ?></span>
                    <input id="recordSearch" class="legacy-search" type="search" oninput="filterCards(this.value)">
                </label>
            </div>

            <?php if (empty($items)): ?>
                <div class="legacy-empty"><?= esc($isThai ? 'ไม่พบข้อมูล' : 'No records found') ?></div>
            <?php else: ?>
                <div class="legacy-table-wrap">
                    <table id="recordTable" class="legacy-table">
                        <thead>
                        <tr>
                            <th><?= esc($isThai ? 'จัดการ' : 'Manage') ?></th>
                            <th><?= esc($isThai ? 'ชื่อกลุ่มหลักสูตร (อังกฤษ)' : 'Course group name (English)') ?></th>
                            <th><?= esc($isThai ? 'ชื่อกลุ่มหลักสูตร (ไทย)' : 'Course group name (Thai)') ?></th>
                            <th><?= esc($isThai ? 'ชื่อกลุ่มหลักสูตร (ญี่ปุ่น)' : 'Course group name (Japanese)') ?></th>
                            <th><?= esc($isThai ? 'สถานะ' : 'Status') ?></th>
                            <th><?= esc($isThai ? 'สถานะการอนุมัติ' : 'Approval status') ?></th>
                            <th><?= esc($isThai ? 'อนุมัติโดย' : 'Approved by') ?></th>
                            <th><?= esc($isThai ? 'อนุมัติเมื่อวันที่' : 'Approved date') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $companyId = (int) ($item['com_id'] ?? 0);
                            $filterText = strtolower(($item['cgcode'] ?? '') . ' ' . ($item['cgtitle_en'] ?? '') . ' ' . ($item['cgtitle_th'] ?? '') . ' ' . ($item['cgtitle_jp'] ?? '') . ' ' . ($item['company_name'] ?? ''));
                            $lockedByApproval = $isApprovedLocked($item);
                            $lockedTitle = $isThai ? 'อนุมัติแล้ว แก้ไขหรือลบได้เฉพาะ Super Admin' : 'Approved records can only be edited or deleted by Super Admin';
                            ?>
                            <tr data-filter="<?= esc($filterText) ?>" data-company="<?= $companyId ?>">
                                <td>
                                    <div class="legacy-actions">
                                        <?php if ($lockedByApproval): ?>
                                            <button class="legacy-action locked" type="button" title="<?= esc($lockedTitle) ?>" disabled><i class="bi bi-pencil-fill"></i></button>
                                            <button class="legacy-action locked" type="button" title="<?= esc($lockedTitle) ?>" disabled><i class="bi bi-x-lg"></i></button>
                                        <?php else: ?>
                                            <button class="legacy-action edit" type="button" title="<?= esc($isThai ? 'แก้ไข' : 'Edit') ?>" data-bs-toggle="modal" data-bs-target="#courseGroupEditModal<?= (int) $item['cg_id'] ?>"><i class="bi bi-pencil-fill"></i></button>
                                            <form class="legacy-inline-form" method="post" action="<?= site_url('managecourse/course_groups/' . $item['cg_id'] . '/archive') ?>" onsubmit="return confirm('<?= esc($isThai ? 'ต้องการลบกลุ่มหลักสูตรนี้?' : 'Delete this course group?') ?>');"><?= csrf_field() ?>
                                                <button class="legacy-action delete" type="submit" title="<?= esc($isThai ? 'ลบ' : 'Delete') ?>"><i class="bi bi-x-lg"></i></button>
                                            </form>
                                        <?php endif; ?>
                                        <form class="legacy-inline-form" method="post" action="<?= site_url('managecourse/course_groups/' . $item['cg_id'] . '/approval') ?>"><?= csrf_field() ?>
                                            <input type="hidden" name="approval" value="1">
                                            <button class="legacy-action approve" type="submit" title="<?= esc($isThai ? 'อนุมัติ' : 'Approve') ?>"><i class="bi bi-check2"></i></button>
                                        </form>
                                        <button class="legacy-action state" type="button" title="<?= esc($isThai ? 'ไม่อนุมัติ' : 'Reject') ?>" data-bs-toggle="modal" data-bs-target="#courseGroupRejectModal<?= (int) $item['cg_id'] ?>"><i class="bi bi-slash-circle"></i></button>
                                    </div>
                                </td>
                                <td><?= esc($item['cgtitle_en'] ?: '-') ?><br><span class="sub"><?= esc($item['cgcode'] ?: '-') ?></span></td>
                                <td><?= esc($item['cgtitle_th'] ?: '-') ?></td>
                                <td><?= esc($item['cgtitle_jp'] ?: '-') ?></td>
                                <td><?= esc($statusText($item['cg_status'] ?? null)) ?></td>
                                <td><?= esc($approvalText($item['cg_approve'] ?? null)) ?></td>
                                <td><?= esc($approvedByText($item)) ?></td>
                                <td><?= esc($approvedDateText($item)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?= view('courses/partials/course_group_modals', ['items' => $items, 'companies' => $companies ?? []]) ?>
        </section>
    <?php else: ?>
        <section class="hero-card">
            <div>
                <p class="eyebrow"><?= esc($title_main ?: 'LMS') ?></p>
                <h1 class="course-hero-title"><?= esc($title) ?></h1>
                <p class="course-hero-copy"><?= esc($isThai ? 'จัดการกลุ่มหลักสูตร รายชื่อหลักสูตร และสถานะการอนุมัติจากข้อมูล LMS เดิม' : 'Manage course groups, course lists, and approval status from the existing LMS data.') ?></p>
                <div class="hero-actions">
                    <?php if ($mode === 'admin'): ?><a class="btn-secondary" href="<?= site_url('managecourse/quizzes') ?>">Manage Quizzes</a><a class="btn-secondary" href="<?= site_url('managecourse/surveys') ?>">Manage Surveys</a><a class="btn-secondary" href="<?= site_url('managecourse/course_notifications') ?>">Notification Logs</a><a class="btn-primary" href="<?= site_url('managecourse/courses_all/create') ?>">Create Course</a><?php endif; ?>
                </div>
            </div>
            <div class="course-hero-panel">
                <span class="role-badge"><i class="bi bi-person-badge"></i><?= esc($roleName) ?></span>
                <span class="tenant-badge"><i class="bi bi-buildings"></i><?= esc($companyName) ?></span>
                <div class="course-hero-stat"><i class="bi bi-collection"></i><div><strong><?= esc($counts['groups'] ?? 0) ?></strong><span>Course Groups</span></div></div>
                <div class="course-hero-stat"><i class="bi bi-book"></i><div><strong><?= esc($counts['courses'] ?? 0) ?></strong><span>All Courses</span></div></div>
            </div>
        </section>

        <section class="metrics">
            <?php foreach ($metricMap as $metric): ?>
                <div class="metric"><span><?= esc($metric['label']) ?></span><strong><?= esc($metric['value']) ?></strong></div>
            <?php endforeach; ?>
        </section>

        <section class="content-panel">
            <div class="toolbar">
                <strong><?= esc('Courses') ?></strong>
                <input class="search" type="search" placeholder="Search displayed records" oninput="filterCards(this.value)">
            </div>
            <div class="grid" id="recordGrid">
                <?php foreach ($items as $item): ?>
                    <article class="course-card" data-filter="<?= esc(strtolower(($item['ccode'] ?? '') . ' ' . ($item['title'] ?? '') . ' ' . ($item['company_name'] ?? ''))) ?>">
                        <div class="cover" style="background-image:url('<?= esc($item['image_url']) ?>')" role="img" aria-label="<?= esc($item['title']) ?>"></div>
                        <div class="course-body">
                            <h2 class="course-title"><a href="<?= site_url('coursemain/detail/' . $item['cos_id']) ?>"><?= esc($item['title']) ?></a></h2>
                            <p class="course-desc"><?= esc($item['description'] ?: '-') ?></p>
                            <div class="meta">
                                <div class="meta-row"><span>Code</span><strong><?= esc($item['ccode'] ?: '-') ?></strong></div>
                                <div class="meta-row"><span>Company</span><strong><?= esc($item['company_name'] ?: '-') ?></strong></div>
                                <div class="meta-row"><span>Period</span><strong><?= esc($item['period_label']) ?></strong></div>
                                <div class="meta-row"><span>Seats</span><strong><?= esc($item['seat_label']) ?></strong></div>
                                <div class="meta-row"><span>Status</span><span class="badge <?= ($item['status_label'] ?? '') === 'Published' ? 'good' : 'warn' ?>"><?= esc($item['learning_label'] ?? $item['status_label']) ?></span></div>
                                <div class="meta-row"><span>Action</span><a class="badge good" href="<?= site_url('coursemain/detail/' . $item['cos_id']) ?>">View detail</a></div>
                                <?php if ($mode === 'admin'): ?>
                                    <div class="meta-row"><span>Manage</span><a class="badge" href="<?= site_url('managecourse/courses_all/' . $item['cos_id'] . '/edit') ?>">Edit</a></div>
                                    <div class="meta-row"><span>Quiz</span><a class="badge" href="<?= site_url('managecourse/quizzes?cos_id=' . $item['cos_id']) ?>">Manage</a></div>
                                    <div class="meta-row"><span>Survey</span><a class="badge" href="<?= site_url('managecourse/surveys?cos_id=' . $item['cos_id']) ?>">Manage</a></div>
                                    <div class="meta-row">
                                        <span>Status</span>
                                        <form method="post" action="<?= site_url('managecourse/courses_all/' . $item['cos_id'] . '/status') ?>"><?= csrf_field() ?>
                                            <input type="hidden" name="status" value="<?= (string) ($item['cos_status'] ?? '') === '1' ? '0' : '1' ?>">
                                            <button class="badge" type="submit" style="border:0;cursor:pointer"><?= (string) ($item['cos_status'] ?? '') === '1' ? 'Deactivate' : 'Activate' ?></button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
</div>
</div>

<?php if ($isGroup): ?>
    <div class="modal fade" id="courseGroupResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= session()->getFlashdata('course_error') ? 'Action failed' : 'Saved successfully' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= esc(session()->getFlashdata('course_notice') ?: session()->getFlashdata('course_error')) ?>
                </div>
                <div class="modal-footer"><button type="button" class="btn primary" data-bs-dismiss="modal">OK</button></div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function filterCards(value) {
    const keyword = String(value || '').trim().toLowerCase();
    const company = document.getElementById('companyFilter')?.value || '';
    document.querySelectorAll('[data-filter]').forEach((node) => {
        const matchesText = node.dataset.filter.includes(keyword);
        const matchesCompany = !company || node.dataset.company === company;
        node.style.display = matchesText && matchesCompany ? '' : 'none';
    });
}
function initSearchableSelects(scope = document) {
    scope.querySelectorAll('select:not([data-no-search]):not([data-searchable-ready])').forEach((select) => {
        select.dataset.searchableReady = 'true';
        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-select';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.classList.add('searchable-native');

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'searchable-toggle';
        toggle.innerHTML = '<span></span><i class="bi bi-chevron-down"></i>';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';
        menu.hidden = true;

        const search = document.createElement('input');
        search.type = 'search';
        search.className = 'searchable-search';
        search.placeholder = 'ค้นหา...';
        search.autocomplete = 'off';

        const options = document.createElement('div');
        options.className = 'searchable-options';

        menu.appendChild(search);
        menu.appendChild(options);
        wrapper.appendChild(toggle);
        wrapper.appendChild(menu);

        const label = () => select.options[select.selectedIndex]?.text || select.options[0]?.text || '-';
        const updateToggle = () => {
            toggle.querySelector('span').textContent = label();
        };
        const renderOptions = (query = '') => {
            const normalized = query.trim().toLowerCase();
            options.innerHTML = '';
            let visible = 0;
            Array.from(select.options).forEach((option) => {
                const text = option.text.trim();
                if (normalized && !text.toLowerCase().includes(normalized)) {
                    return;
                }
                visible += 1;
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'searchable-option' + (option.selected ? ' active' : '');
                item.textContent = text;
                item.dataset.value = option.value;
                item.addEventListener('click', () => {
                    select.value = option.value;
                    updateToggle();
                    renderOptions(search.value);
                    menu.hidden = true;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    toggle.focus();
                });
                options.appendChild(item);
            });
            if (visible === 0) {
                const empty = document.createElement('div');
                empty.className = 'searchable-empty';
                empty.textContent = 'ไม่พบข้อมูล';
                options.appendChild(empty);
            }
        };
        const openMenu = () => {
            document.querySelectorAll('.searchable-menu:not([hidden])').forEach((open) => {
                if (open !== menu) {
                    open.hidden = true;
                }
            });
            renderOptions('');
            search.value = '';
            menu.hidden = false;
            search.focus();
        };

        updateToggle();
        renderOptions();
        toggle.addEventListener('click', () => {
            if (menu.hidden) {
                openMenu();
            } else {
                menu.hidden = true;
            }
        });
        search.addEventListener('input', () => renderOptions(search.value));
        select.addEventListener('change', () => {
            updateToggle();
            renderOptions(search.value);
        });
    });
}
document.addEventListener('click', (event) => {
    if (!event.target.closest('.searchable-select')) {
        document.querySelectorAll('.searchable-menu:not([hidden])').forEach((menu) => {
            menu.hidden = true;
        });
    }
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('.searchable-menu:not([hidden])').forEach((menu) => {
            menu.hidden = true;
        });
    }
});
document.addEventListener('DOMContentLoaded', () => initSearchableSelects());
document.querySelector('.menu-toggle')?.addEventListener('click', function () {
    const open = !document.body.classList.contains('nav-open');
    document.body.classList.toggle('nav-open', open);
    this.setAttribute('aria-expanded', String(open));
});
document.querySelectorAll('.side-toggle').forEach((button) => {
    button.addEventListener('click', () => {
        const submenu = button.closest('.nav-item-wrap')?.querySelector('.side-submenu');
        const expanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', String(!expanded));
        if (submenu) {
            submenu.hidden = expanded;
        }
    });
});
</script>
<?php if ($isGroup): ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (session()->getFlashdata('course_notice') || session()->getFlashdata('course_error')): ?>
        <script>
        window.addEventListener('DOMContentLoaded', () => {
            new bootstrap.Modal(document.getElementById('courseGroupResultModal')).show();
        });
        </script>
    <?php endif; ?>
<?php endif; ?>
<script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
