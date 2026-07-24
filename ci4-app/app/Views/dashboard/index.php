<?php
$branding = $summary['branding'] ?? [];
$courseStatus = $summary['course_status'] ?? ['total' => 0, 'ongoing' => 0, 'incoming' => 0, 'closed' => 0];
$learnerCourses = $summary['learner_courses'] ?? ['all' => [], 'ongoing' => [], 'incoming' => [], 'completed' => []];
$allCourses = $learnerCourses['all'] ?? [];
$ongoingCourses = $learnerCourses['ongoing'] ?? [];
$incomingCourses = $learnerCourses['incoming'] ?? [];
$completedCourses = $learnerCourses['completed'] ?? [];
$device = $summary['device_usage'] ?? ['pc' => 0, 'mobile' => 0, 'tablet' => 0];
$companyAnalytics = $summary['company_analytics'] ?? [];
$approvalCourses = $summary['approval_courses'] ?? [];
$approvalSurveys = $summary['approval_surveys'] ?? [];
$approvalCourseGroups = $summary['approval_course_groups'] ?? [];
$approvalHistory = $approvalHistory ?? [];
$publicSurveys = $summary['public_surveys'] ?? [];
$notifications = $summary['notifications'] ?? ['unread' => 0, 'items' => []];
$notificationItems = $notifications['items'] ?? [];
$unreadNotifications = (int) ($notifications['unread'] ?? 0);
$profileImage = ! empty($user['img_profile']) ? base_url('uploads/profile/' . $user['img_profile']) : base_url('uploads/profile/default_profile.jpg');
$logoTop = base_url('images/logo.png');
$isThai = ($lang ?? 'english') === 'thai';
$htmlLang = $isThai ? 'th' : 'en';
$roleName = $isThai
    ? ($user['ug_name_th'] ?? $user['ug_name_en'] ?? 'ผู้เรียน')
    : ($user['ug_name_en'] ?? $user['ug_name_th'] ?? 'Learner');
$companyName = $isThai
    ? ($user['com_name_th'] ?? $user['com_name_eng'] ?? 'Verztec Consulting Thailand')
    : ($user['com_name_eng'] ?? $user['com_name_th'] ?? 'Verztec Consulting Thailand');
$displayName = trim((string) ($name ?? '')) ?: ($isThai ? 'ผู้เรียน' : 'Learner');
$adminRoleIds = ['1', '2', '6'];
$hasAdminAccess = in_array((string) ($user['ug_id'] ?? ''), $adminRoleIds, true);
$adminMenuDetector = static function (array $items) use (&$adminMenuDetector): bool {
    foreach ($items as $item) {
        $path = (string) ($item['path'] ?? '');
        if (str_starts_with($path, 'manage') || str_starts_with($path, 'report') || str_starts_with($path, 'dashboard/unlock')) {
            return true;
        }

        if (! empty($item['children']) && $adminMenuDetector($item['children'])) {
            return true;
        }
    }

    return false;
};
$hasAdminAccess = $hasAdminAccess || $adminMenuDetector($menus ?? []);
$translations = [
    'en' => [
        'dashboard' => 'Dashboard',
        'search' => 'Search courses, reports, or menus',
        'notifications' => 'Notifications',
        'logout' => 'Logout',
        'welcome' => 'Welcome back',
        'learning_message' => 'Continue your assigned learning, track progress, and keep your certificates moving.',
        'continue_learning' => 'Continue Learning',
        'view_courses' => 'View My Courses',
        'role' => 'Role',
        'company' => 'Company',
        'total_courses' => 'Total Courses',
        'completed_courses' => 'Completed',
        'in_progress' => 'In Progress',
        'not_started' => 'Not Started',
        'due_soon' => 'Due Soon',
        'course_progress' => 'My Course Progress',
        'assigned_courses' => 'Assigned Courses',
        'recommended_courses' => 'Recommended Courses',
        'announcements' => 'Announcements',
        'progress' => 'Progress',
        'last_accessed' => 'Last accessed',
        'remaining' => 'Estimated remaining',
        'minutes' => 'minutes',
        'duration' => 'Duration',
        'status' => 'Status',
        'category' => 'Category',
        'start' => 'Start',
        'continue' => 'Continue',
        'review' => 'Review',
        'empty_courses' => 'No courses are available in this section yet.',
        'admin_overview' => 'Admin Overview',
        'user_summary' => 'User Summary',
        'course_summary' => 'Course Summary',
        'completion_rate' => 'Completion Rate',
        'active_learners' => 'Active Learners',
        'device_usage' => 'Device Usage',
        'latest_activity' => 'Latest Activity',
        'reports_shortcut' => 'Reports Shortcut',
        'pending_courses' => 'Pending Course Approvals',
        'pending_surveys' => 'Pending Survey Approvals',
        'available_surveys' => 'Available Surveys',
        'company_analytics' => 'Company Analytics',
        'users' => 'Users',
        'courses' => 'Courses',
        'surveys' => 'Surveys',
        'learner_view' => 'Learner View',
        'admin_view' => 'Admin View',
        'all' => 'All',
        'approx' => 'Approx.',
        'unlimited' => 'Unlimited',
        'new_announcement' => 'New learning assignments and surveys will appear here when they are available.',
        'chart_summary' => 'Device usage summary',
    ],
    'th' => [
        'dashboard' => 'แดชบอร์ด',
        'search' => 'ค้นหาหลักสูตร รายงาน หรือเมนู',
        'notifications' => 'การแจ้งเตือน',
        'logout' => 'ออกจากระบบ',
        'welcome' => 'ยินดีต้อนรับกลับ',
        'learning_message' => 'เรียนต่อจากหลักสูตรที่ได้รับมอบหมาย ติดตามความคืบหน้า และจัดการใบประกาศนียบัตรได้ง่ายขึ้น',
        'continue_learning' => 'เรียนต่อ',
        'view_courses' => 'หลักสูตรของฉัน',
        'role' => 'บทบาท',
        'company' => 'บริษัท',
        'total_courses' => 'หลักสูตรทั้งหมด',
        'completed_courses' => 'เรียนสำเร็จ',
        'in_progress' => 'กำลังเรียน',
        'not_started' => 'ยังไม่เริ่ม',
        'due_soon' => 'ใกล้ครบกำหนด',
        'course_progress' => 'ความคืบหน้าการเรียน',
        'assigned_courses' => 'หลักสูตรที่ได้รับมอบหมาย',
        'recommended_courses' => 'หลักสูตรแนะนำ',
        'announcements' => 'ประกาศ',
        'progress' => 'ความคืบหน้า',
        'last_accessed' => 'เข้าเรียนล่าสุด',
        'remaining' => 'เวลาที่เหลือโดยประมาณ',
        'minutes' => 'นาที',
        'duration' => 'ระยะเวลา',
        'status' => 'สถานะ',
        'category' => 'หมวดหมู่',
        'start' => 'เริ่มเรียน',
        'continue' => 'เรียนต่อ',
        'review' => 'ทบทวน',
        'empty_courses' => 'ยังไม่มีหลักสูตรในส่วนนี้',
        'admin_overview' => 'ภาพรวมผู้ดูแลระบบ',
        'user_summary' => 'สรุปผู้ใช้งาน',
        'course_summary' => 'สรุปหลักสูตร',
        'completion_rate' => 'อัตราการเรียนสำเร็จ',
        'active_learners' => 'ผู้เรียนที่ใช้งานอยู่',
        'device_usage' => 'อุปกรณ์ที่ใช้งาน',
        'latest_activity' => 'กิจกรรมล่าสุด',
        'reports_shortcut' => 'ทางลัดรายงาน',
        'pending_courses' => 'หลักสูตรรออนุมัติ',
        'pending_surveys' => 'แบบสำรวจรออนุมัติ',
        'available_surveys' => 'แบบสำรวจที่เปิดอยู่',
        'company_analytics' => 'ข้อมูลรายบริษัท',
        'users' => 'ผู้ใช้งาน',
        'courses' => 'หลักสูตร',
        'surveys' => 'แบบสำรวจ',
        'learner_view' => 'มุมมองผู้เรียน',
        'admin_view' => 'มุมมองผู้ดูแล',
        'all' => 'ทั้งหมด',
        'approx' => 'ประมาณ',
        'unlimited' => 'ไม่จำกัด',
        'new_announcement' => 'รายการมอบหมายและแบบสำรวจใหม่จะแสดงที่นี่เมื่อพร้อมใช้งาน',
        'chart_summary' => 'สรุปการใช้งานตามอุปกรณ์',
    ],
];
$copy = $translations[$isThai ? 'th' : 'en'];
$approvalText = $isThai ? [
    'queue' => 'รายการรออนุมัติ',
    'subtitle' => 'ตรวจสอบและตัดสินใจรายการที่คุณได้รับมอบหมาย',
    'course' => 'หลักสูตร',
    'survey' => 'แบบสำรวจ',
    'course_group' => 'กลุ่มหลักสูตร',
    'owner' => 'ผู้สร้าง',
    'note' => 'หมายเหตุ / เหตุผลที่ไม่อนุมัติ',
    'approve' => 'อนุมัติ',
    'reject' => 'ไม่อนุมัติ',
    'empty' => 'ไม่มีรายการรออนุมัติ',
    'history' => 'ประวัติการอนุมัติล่าสุด',
    'bulk_note' => 'หมายเหตุสำหรับรายการที่เลือก',
    'apply_selected' => 'ดำเนินการกับรายการที่เลือก',
] : [
    'queue' => 'Approval Queue',
    'subtitle' => 'Review and decide items assigned to you',
    'course' => 'Course',
    'survey' => 'Survey',
    'course_group' => 'Course group',
    'owner' => 'Owner',
    'note' => 'Note / rejection reason',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'empty' => 'There are no items waiting for your approval.',
    'history' => 'Recent approval history',
    'bulk_note' => 'Note for selected items',
    'apply_selected' => 'Apply to selected',
];
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
$actionMenus = [
    'dashboard/unlockAcc' => [
        'title' => $isThai ? 'ปลดล็อกผู้ใช้' : 'Unlock User',
        'message' => $isThai
            ? 'เมนูนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดคำสั่งปลดล็อกในรายการผู้ใช้'
            : 'This action needs a selected user first. Go to User Information and use the unlock command on a user row.',
        'cta' => $isThai ? 'ไปหน้า User Information' : 'Go to User Information',
        'href' => site_url('manage/userdata'),
    ],
    'dashboard/resetPass' => [
        'title' => $isThai ? 'ตั้งรหัสผ่านใหม่' : 'Reset Password',
        'message' => $isThai
            ? 'เมนูนี้ต้องเลือกผู้ใช้ก่อน กรุณาไปที่หน้า User Information แล้วกดคำสั่งตั้งรหัสผ่านใหม่ในรายการผู้ใช้'
            : 'This action needs a selected user first. Go to User Information and use the reset password command on a user row.',
        'cta' => $isThai ? 'ไปหน้า User Information' : 'Go to User Information',
        'href' => site_url('manage/userdata'),
    ],
];
$isActionMenu = static fn (string $path): bool => isset($actionMenus[$path]);
$formatDate = static function (?string $value) use ($isThai): string {
    $value = trim((string) $value);
    if ($value === '' || str_starts_with($value, '0000-00-00')) {
        return '-';
    }

    $timestamp = strtotime($value);
    if (! $timestamp) {
        return '-';
    }

    return $isThai ? date('d/m/', $timestamp) . ((int) date('Y', $timestamp) + 543) : date('d M Y', $timestamp);
};
$courseProgress = static function (array $course): int {
    $progress = (int) round((float) ($course['cosen_score_per'] ?? 0));
    return max(0, min(100, $progress));
};
$courseBucket = static function (array $course): string {
    if ((string) ($course['cosen_status_sub'] ?? '') === '1') {
        return 'completed';
    }

    $first = (string) ($course['cosen_firsttime'] ?? '');
    if ($first === '' || str_starts_with($first, '0000-00-00')) {
        return 'not-started';
    }

    return 'in-progress';
};
$dueSoonCourses = array_values(array_filter($allCourses, static function (array $course): bool {
    $end = trim((string) ($course['date_end'] ?? ''));
    if ($end === '' || str_starts_with($end, '0000-00-00')) {
        return false;
    }

    $endTime = strtotime($end);
    return $endTime !== false && $endTime >= time() && $endTime <= strtotime('+14 days');
}));
$learnerStatus = [
    'all' => (int) ($summary['enroll'] ?? count($allCourses)),
    'in-progress' => (int) ($summary['in_process'] ?? count($ongoingCourses)),
    'not-started' => (int) ($summary['not_start'] ?? count($incomingCourses)),
    'completed' => (int) ($summary['success'] ?? count($completedCourses)),
    'due-soon' => count($dueSoonCourses),
];
$continueCourse = $ongoingCourses[0] ?? $incomingCourses[0] ?? $allCourses[0] ?? null;
$completionRate = $learnerStatus['all'] > 0 ? (int) round(($learnerStatus['completed'] / $learnerStatus['all']) * 100) : 0;
$totalUsers = array_sum(array_map(static fn (array $company): int => (int) ($company['usertotal'] ?? 0), $companyAnalytics));
$totalCompanyCourses = array_sum(array_map(static fn (array $company): int => (int) ($company['coursetotal'] ?? 0), $companyAnalytics));
$topCompanies = array_slice($companyAnalytics, 0, 6);
$topCompanyLabels = array_map(static fn (array $company): string => (string) ($company['com_name_eng'] ?: $company['com_name_th'] ?: '-'), $topCompanies);
$topCompanyUsers = array_map(static fn (array $company): int => (int) ($company['usertotal'] ?? 0), $topCompanies);
$chartData = [
    'courseStatus' => [
        'labels' => [$copy['total_courses'], $copy['in_progress'], $copy['not_started'], $copy['completed_courses']],
        'values' => [(int) ($courseStatus['total'] ?? 0), (int) ($courseStatus['ongoing'] ?? 0), (int) ($courseStatus['incoming'] ?? 0), (int) ($courseStatus['closed'] ?? 0)],
    ],
    'device' => [
        'labels' => ['Desktop', 'Tablet', 'Mobile'],
        'values' => [(float) ($device['pc'] ?? 0), (float) ($device['tablet'] ?? 0), (float) ($device['mobile'] ?? 0)],
    ],
    'companies' => [
        'labels' => $topCompanyLabels,
        'values' => $topCompanyUsers,
    ],
    'activity' => [
        'labels' => [$copy['pending_courses'], $copy['pending_surveys'], $copy['available_surveys']],
        'values' => [count($approvalCourses), count($approvalSurveys), count($publicSurveys)],
    ],
    'learner' => [
        'labels' => [$copy['in_progress'], $copy['not_started'], $copy['completed_courses'], $copy['due_soon']],
        'values' => [$learnerStatus['in-progress'], $learnerStatus['not-started'], $learnerStatus['completed'], $learnerStatus['due-soon']],
        'completionRate' => $completionRate,
    ],
];
$mobileItems = array_slice($menus ?? [], 0, 4);
?>
<!doctype html>
<html lang="<?= esc($htmlLang) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($branding['da_title_en'] ?? 'Verztec LMS Dashboard') ?></title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.datatables.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url('css/dashboard-enterprise.css?v=20260701-3') ?>" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="dashboardSidebar" aria-label="<?= esc($copy['dashboard']) ?>">
        <div class="brand-stack">
            <a class="brand-logo brand-wordmark" href="<?= site_url('dashboard') ?>" aria-label="Verztec LMS">
                <span class="brand-symbol">V</span>
                <span>
                    <strong>Verztec</strong>
                    <small>Learning Platform</small>
                </span>
            </a>
            <span class="tenant-badge"><i class="bi bi-buildings"></i><?= esc($companyName) ?></span>
        </div>
        <nav class="sidebar-nav" aria-label="Primary navigation">
            <?php foreach (($menus ?? []) as $menu): ?>
                <?php $hasChildren = ! empty($menu['children']); ?>
                <?php $menuPath = (string) ($menu['path'] ?? 'dashboard'); ?>
                <div class="nav-item-wrap">
                    <?php if ($hasChildren): ?>
                        <button class="side-link side-toggle" type="button" aria-expanded="false">
                            <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                            <span><?= esc($menu['name'] ?? '-') ?></span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </button>
                    <?php elseif ($isActionMenu($menuPath)): ?>
                        <button class="side-link side-action" type="button" data-action="<?= esc($menuPath) ?>">
                            <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                            <span><?= esc($menu['name'] ?? '-') ?></span>
                        </button>
                    <?php else: ?>
                        <a class="side-link <?= $menuPath === 'dashboard' ? 'active' : '' ?>"
                           href="<?= site_url($menuPath) ?>"
                           <?= $menuPath === 'dashboard' ? 'aria-current="page"' : '' ?>>
                            <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                            <span><?= esc($menu['name'] ?? '-') ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($hasChildren): ?>
                        <div class="side-submenu" hidden>
                            <?php foreach ($menu['children'] as $child): ?>
                                <?php $childPath = (string) ($child['path'] ?? 'dashboard'); ?>
                                <?php if ($isActionMenu($childPath)): ?>
                                    <button class="side-sublink side-action" type="button" data-action="<?= esc($childPath) ?>">
                                        <i class="<?= esc($menuIcon((string) ($child['icon'] ?? ''))) ?>"></i>
                                        <span><?= esc($child['name'] ?? '-') ?></span>
                                    </button>
                                <?php else: ?>
                                    <a class="side-sublink" href="<?= site_url($childPath) ?>">
                                        <i class="<?= esc($menuIcon((string) ($child['icon'] ?? ''))) ?>"></i>
                                        <span><?= esc($child['name'] ?? '-') ?></span>
                                    </a>
                                <?php endif; ?>
                                <?php if (! empty($child['children'])): ?>
                                    <div class="side-nested-submenu">
                                        <?php foreach ($child['children'] as $grandchild): ?>
                                            <?php $grandchildPath = (string) ($grandchild['path'] ?? 'dashboard'); ?>
                                            <?php if ($isActionMenu($grandchildPath)): ?>
                                                <button class="side-sublink side-grandlink side-action" type="button" data-action="<?= esc($grandchildPath) ?>">
                                                    <i class="<?= esc($menuIcon((string) ($grandchild['icon'] ?? ''))) ?>"></i>
                                                    <span><?= esc($grandchild['name'] ?? '-') ?></span>
                                                </button>
                                            <?php else: ?>
                                                <a class="side-sublink side-grandlink" href="<?= site_url($grandchildPath) ?>">
                                                    <i class="<?= esc($menuIcon((string) ($grandchild['icon'] ?? ''))) ?>"></i>
                                                    <span><?= esc($grandchild['name'] ?? '-') ?></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
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
                <input type="search" id="courseSearch" placeholder="<?= esc($copy['search']) ?>" autocomplete="off">
            </label>
            <div class="mode-switch" role="group" aria-label="Dashboard mode">
                <?php if ($hasAdminAccess): ?>
                    <button class="mode-pill active" type="button" data-mode-option="admin"><?= esc($copy['admin_view']) ?></button>
                    <button class="mode-pill" type="button" data-mode-option="learner"><?= esc($copy['learner_view']) ?></button>
                <?php endif; ?>
            </div>
            <div class="top-actions">
                <div class="language-switch" aria-label="Language">
                    <a href="<?= site_url('home/change_lang/thai') ?>" class="<?= $isThai ? 'active' : '' ?>" hreflang="th">TH</a>
                    <a href="<?= site_url('home/change_lang/english') ?>" class="<?= ! $isThai ? 'active' : '' ?>" hreflang="en">EN</a>
                </div>
                <div class="notification-menu">
                    <button class="icon-button notifications" type="button" aria-label="<?= esc($copy['notifications']) ?>">
                        <i class="bi bi-bell"></i>
                        <?php if ($unreadNotifications > 0): ?><span class="notification-badge"><?= esc((string) min($unreadNotifications, 99)) ?></span><?php endif; ?>
                    </button>
                    <div class="notification-panel" role="menu">
                        <div class="notification-panel-head">
                            <span><?= esc($copy['notifications']) ?></span>
                            <?php if ($unreadNotifications > 0): ?>
                                <form method="post" action="<?= site_url('dashboard/notifications/read-all') ?>"><?= csrf_field() ?>
                                    <button type="submit">Mark all read</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php foreach ($notificationItems as $notification): ?>
                            <a class="notification-item <?= (int) ($notification['is_read'] ?? 0) === 0 ? 'unread' : '' ?>" href="<?= site_url('dashboard/notifications/' . (int) $notification['noti_id'] . '/open') ?>" role="menuitem">
                                <strong><?= esc($notification['title'] ?? '-') ?></strong>
                                <span><?= esc($notification['message'] ?? '') ?></span>
                                <small><?= esc($formatDate((string) ($notification['created_at'] ?? ''))) ?></small>
                            </a>
                        <?php endforeach; ?>
                        <?php if (empty($notificationItems)): ?><div class="notification-empty">No notifications.</div><?php endif; ?>
                    </div>
                </div>
                <a class="profile-chip" href="<?= site_url('dashboard/profile/setting') ?>" data-profile-trigger>
                    <img class="avatar" src="<?= esc($profileImage) ?>" onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'" alt="<?= esc($displayName) ?>">
                    <span class="profile-meta">
                        <strong><?= esc($displayName) ?></strong>
                        <span><?= esc($roleName) ?></span>
                    </span>
                </a>
                <a class="icon-button" href="<?= site_url('logout') ?>" aria-label="<?= esc($copy['logout']) ?>"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </header>

        <main class="dashboard-page">
            <section class="hero-card">
                <div>
                    <p class="eyebrow">Verztec Consulting Thailand LMS</p>
                    <h1 class="hero-title"><?= esc($copy['welcome']) ?>, <?= esc($displayName) ?></h1>
                    <p class="hero-copy"><?= esc($copy['learning_message']) ?></p>
                    <div class="hero-actions">
                        <a class="btn-primary" href="<?= $continueCourse ? site_url('coursemain/detail/' . $continueCourse['cos_id']) : site_url('coursemain/my_course') ?>">
                            <i class="bi bi-play-fill"></i><?= esc($copy['continue_learning']) ?>
                        </a>
                        <a class="btn-secondary" href="<?= site_url('coursemain/my_course') ?>"><i class="bi bi-book"></i><?= esc($copy['view_courses']) ?></a>
                    </div>
                </div>
                <div class="hero-panel" aria-label="User summary">
                    <span class="role-badge"><i class="bi bi-person-badge"></i><?= esc($copy['role']) ?>: <?= esc($roleName) ?></span>
                    <span class="tenant-badge"><i class="bi bi-buildings"></i><?= esc($copy['company']) ?>: <?= esc($companyName) ?></span>
                    <div class="mini-stat">
                        <i class="bi bi-trophy"></i>
                        <div><strong><?= esc((string) $completionRate) ?>%</strong><span><?= esc($copy['completion_rate']) ?></span></div>
                    </div>
                    <div class="mini-stat">
                        <i class="bi bi-lightning-charge"></i>
                        <div><strong><?= esc((string) $learnerStatus['in-progress']) ?></strong><span><?= esc($copy['in_progress']) ?></span></div>
                    </div>
                </div>
            </section>

            <?php if ($hasAdminAccess): ?>
                <section class="section dashboard-mode" data-dashboard-mode="admin">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title"><?= esc($copy['admin_overview']) ?></h2>
                            <p class="section-subtitle"><?= esc($copy['user_summary']) ?> / <?= esc($copy['course_summary']) ?></p>
                        </div>
                        <div class="section-actions">
                            <a class="btn-secondary" href="<?= site_url('report/learnerReport') ?>"><i class="bi bi-graph-up-arrow"></i><?= esc($copy['reports_shortcut']) ?></a>
                            <a class="btn-secondary" href="<?= site_url('manage/userdata') ?>"><i class="bi bi-people"></i><?= esc($copy['users']) ?></a>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="filter-card" role="group"><span class="card-top"><span><?= esc($copy['active_learners']) ?></span><i class="bi bi-people"></i></span><span class="value"><?= number_format($totalUsers) ?></span></div>
                        <div class="filter-card" role="group"><span class="card-top"><span><?= esc($copy['course_summary']) ?></span><i class="bi bi-book"></i></span><span class="value"><?= number_format($totalCompanyCourses ?: (int) ($courseStatus['total'] ?? 0)) ?></span></div>
                        <div class="filter-card" role="group"><span class="card-top"><span><?= esc($copy['completion_rate']) ?></span><i class="bi bi-check2-circle"></i></span><span class="value"><?= esc((string) $completionRate) ?>%</span></div>
                        <div class="filter-card" role="group"><span class="card-top"><span><?= esc($copy['pending_courses']) ?></span><i class="bi bi-hourglass-split"></i></span><span class="value"><?= number_format(count($approvalCourses)) ?></span></div>
                        <div class="filter-card" role="group"><span class="card-top"><span><?= esc($copy['pending_surveys']) ?></span><i class="bi bi-clipboard-check"></i></span><span class="value"><?= number_format(count($approvalSurveys)) ?></span></div>
                    </div>

                    <div class="section chart-grid admin-chart-grid">
                        <article class="chart-card chart-card-feature">
                            <div class="chart-card-head">
                                <div>
                                    <h3 class="section-title"><?= esc($copy['course_summary']) ?></h3>
                                    <p class="section-subtitle"><?= esc($copy['total_courses']) ?> / <?= esc($copy['in_progress']) ?> / <?= esc($copy['completed_courses']) ?></p>
                                </div>
                                <span class="chart-chip"><i class="bi bi-pie-chart"></i> Live</span>
                            </div>
                            <div class="chart-canvas-wrap doughnut-wrap">
                                <canvas id="courseStatusChart" aria-label="<?= esc($copy['course_summary']) ?>"></canvas>
                                <div class="chart-center-label">
                                    <strong><?= number_format((int) ($courseStatus['total'] ?? 0)) ?></strong>
                                    <span><?= esc($copy['total_courses']) ?></span>
                                </div>
                            </div>
                        </article>
                        <article class="chart-card">
                            <div class="chart-card-head">
                                <div>
                                    <h3 class="section-title"><?= esc($copy['active_learners']) ?></h3>
                                    <p class="section-subtitle"><?= esc($copy['company_analytics']) ?></p>
                                </div>
                                <span class="chart-chip"><i class="bi bi-bar-chart"></i> Top 6</span>
                            </div>
                            <div class="chart-canvas-wrap">
                                <canvas id="companyUsersChart" aria-label="<?= esc($copy['active_learners']) ?>"></canvas>
                            </div>
                        </article>
                        <article class="chart-card">
                            <div class="chart-card-head">
                                <div>
                                    <h3 class="section-title"><?= esc($copy['latest_activity']) ?></h3>
                                    <p class="section-subtitle"><?= esc($copy['pending_courses']) ?> / <?= esc($copy['available_surveys']) ?></p>
                                </div>
                                <span class="chart-chip"><i class="bi bi-activity"></i> Queue</span>
                            </div>
                            <div class="chart-canvas-wrap">
                                <canvas id="activityChart" aria-label="<?= esc($copy['latest_activity']) ?>"></canvas>
                            </div>
                        </article>
                        <article class="chart-card">
                            <div class="chart-card-head">
                                <div>
                                    <h3 class="section-title"><?= esc($copy['device_usage']) ?></h3>
                                    <p class="section-subtitle"><?= esc($copy['chart_summary']) ?></p>
                                </div>
                                <span class="chart-chip"><i class="bi bi-display"></i> Device</span>
                            </div>
                            <div class="chart-canvas-wrap">
                                <canvas id="adminDeviceUsageChart" aria-label="<?= esc($copy['device_usage']) ?>"></canvas>
                            </div>
                        </article>
                    </div>

                    <div class="section table-card" id="approval-queue">
                        <div class="section-header">
                            <div>
                                <h3 class="section-title"><?= esc($approvalText['queue']) ?></h3>
                                <p class="section-subtitle"><?= esc($approvalText['subtitle']) ?></p>
                            </div>
                            <span class="chart-chip"><i class="bi bi-check2-square"></i> <?= number_format(count($approvalCourses) + count($approvalSurveys) + count($approvalCourseGroups)) ?></span>
                        </div>
                        <?php if (session()->getFlashdata('approval_notice')): ?>
                            <div class="approval-alert approval-alert-success"><?= esc(session()->getFlashdata('approval_notice')) ?></div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('approval_error')): ?>
                            <div class="approval-alert approval-alert-error"><?= esc(session()->getFlashdata('approval_error')) ?></div>
                        <?php endif; ?>
                        <?php if ($approvalCourses === [] && $approvalSurveys === [] && $approvalCourseGroups === []): ?>
                            <div class="approval-empty"><i class="bi bi-check-circle"></i> <?= esc($approvalText['empty']) ?></div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <?php foreach (['course' => $approvalCourses, 'survey' => $approvalSurveys, 'course_group' => $approvalCourseGroups] as $bulkType => $bulkItems): ?>
                                    <?php if ($bulkItems !== []): ?>
                                        <form id="bulk-<?= esc($bulkType) ?>" class="approval-bulk" method="post" action="<?= site_url('dashboard/approvals/bulk') ?>"><?= csrf_field() ?>
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="type" value="<?= esc($bulkType) ?>">
                                            <strong><?= esc($approvalText[$bulkType]) ?>:</strong>
                                            <input name="note" maxlength="2000" placeholder="<?= esc($approvalText['bulk_note']) ?>">
                                            <button class="approval-btn approve" name="decision" value="approve" type="submit"><?= esc($approvalText['approve']) ?></button>
                                            <button class="approval-btn reject" name="decision" value="reject" type="submit"><?= esc($approvalText['reject']) ?></button>
                                        </form>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <table class="enterprise-table approval-table">
                                    <thead><tr><th><span class="visually-hidden">Select</span></th><th>Type</th><th>Title</th><th><?= esc($approvalText['owner']) ?></th><th>Decision</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($approvalCourses as $item): ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?= (int) $item['cos_id'] ?>" form="bulk-course" aria-label="Select course"></td>
                                            <td><span class="approval-kind"><i class="bi bi-book"></i> <?= esc($approvalText['course']) ?></span></td>
                                            <td><a href="<?= site_url('coursemain/detail/' . (int) $item['cos_id']) ?>"><?= esc($item['display_name'] ?? '') ?></a></td>
                                            <td><?= esc($item['user_creator'] ?? '-') ?></td>
                                            <td>
                                                <form class="approval-form" method="post" action="<?= site_url('dashboard/approvals/courses/' . (int) $item['cos_id']) ?>"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input name="note" type="text" maxlength="2000" placeholder="<?= esc($approvalText['note']) ?>">
                                                    <button class="approval-btn approve" name="decision" value="approve" type="submit"><i class="bi bi-check-lg"></i> <?= esc($approvalText['approve']) ?></button>
                                                    <button class="approval-btn reject" name="decision" value="reject" type="submit"><i class="bi bi-x-lg"></i> <?= esc($approvalText['reject']) ?></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php foreach ($approvalSurveys as $item): ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?= (int) $item['sv_id'] ?>" form="bulk-survey" aria-label="Select survey"></td>
                                            <td><span class="approval-kind survey"><i class="bi bi-ui-checks"></i> <?= esc($approvalText['survey']) ?></span></td>
                                            <td><?= esc($item['display_title'] ?? '') ?></td>
                                            <td><?= esc($item['user_creator'] ?? '-') ?></td>
                                            <td>
                                                <form class="approval-form" method="post" action="<?= site_url('dashboard/approvals/surveys/' . (int) $item['sv_id']) ?>"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input name="note" type="text" maxlength="2000" placeholder="<?= esc($approvalText['note']) ?>">
                                                    <button class="approval-btn approve" name="decision" value="approve" type="submit"><i class="bi bi-check-lg"></i> <?= esc($approvalText['approve']) ?></button>
                                                    <button class="approval-btn reject" name="decision" value="reject" type="submit"><i class="bi bi-x-lg"></i> <?= esc($approvalText['reject']) ?></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php foreach ($approvalCourseGroups as $item): ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?= (int) $item['cg_id'] ?>" form="bulk-course_group" aria-label="Select course group"></td>
                                            <td><span class="approval-kind group"><i class="bi bi-collection"></i> <?= esc($approvalText['course_group']) ?></span></td>
                                            <td><?= esc($isThai ? ($item['cgtitle_th'] ?: $item['cgtitle_en']) : ($item['cgtitle_en'] ?: $item['cgtitle_th'])) ?></td>
                                            <td><?= esc($item['c_by'] ?? '-') ?></td>
                                            <td>
                                                <form class="approval-form" method="post" action="<?= site_url('dashboard/approvals/course-groups/' . (int) $item['cg_id']) ?>"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input name="note" type="text" maxlength="2000" placeholder="<?= esc($approvalText['note']) ?>">
                                                    <button class="approval-btn approve" name="decision" value="approve" type="submit"><i class="bi bi-check-lg"></i> <?= esc($approvalText['approve']) ?></button>
                                                    <button class="approval-btn reject" name="decision" value="reject" type="submit"><i class="bi bi-x-lg"></i> <?= esc($approvalText['reject']) ?></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($approvalHistory !== []): ?>
                        <div class="section table-card">
                            <div class="section-header"><div><h3 class="section-title"><?= esc($approvalText['history']) ?></h3></div></div>
                            <div class="table-responsive">
                                <table class="enterprise-table">
                                    <thead><tr><th>Type</th><th>Title</th><th>Decision</th><th><?= esc($approvalText['owner']) ?></th><th>Note</th><th>Date</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($approvalHistory as $history): ?>
                                        <tr>
                                            <td><?= esc($approvalText[$history['type']] ?? $history['type']) ?></td>
                                            <td><?= esc($history['title']) ?></td>
                                            <td><span class="history-state <?= (int) $history['approval_state'] === 1 ? 'approved' : 'rejected' ?>"><?= (int) $history['approval_state'] === 1 ? esc($approvalText['approve']) : esc($approvalText['reject']) ?></span></td>
                                            <td><?= esc($history['approver']) ?></td>
                                            <td><?= esc($history['approval_note'] ?: '-') ?></td>
                                            <td><?= esc($history['approved_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="section content-grid">
                        <div class="table-card">
                            <div class="section-header">
                                <div>
                                    <h3 class="section-title"><?= esc($copy['company_analytics']) ?></h3>
                                    <p class="section-subtitle"><?= esc($copy['users']) ?>, <?= esc($copy['courses']) ?>, <?= esc($copy['surveys']) ?></p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="companyAnalyticsTable" class="enterprise-table">
                                    <thead>
                                    <tr>
                                        <th><?= esc($copy['company']) ?></th>
                                        <th class="num"><?= esc($copy['users']) ?></th>
                                        <th class="num"><?= esc($copy['courses']) ?></th>
                                        <th class="num"><?= esc($copy['surveys']) ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($companyAnalytics as $company): ?>
                                        <tr>
                                            <td><?= esc($isThai ? ($company['com_name_th'] ?: $company['com_name_eng']) : ($company['com_name_eng'] ?: $company['com_name_th'])) ?></td>
                                            <td class="num"><?= number_format((int) ($company['usertotal'] ?? 0)) ?></td>
                                            <td class="num"><?= number_format((int) ($company['coursetotal'] ?? 0)) ?></td>
                                            <td class="num"><?= number_format((int) ($company['surveytotal'] ?? 0)) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="stack">
                            <div class="card card-pad">
                                <h3 class="section-title"><?= esc($copy['device_usage']) ?></h3>
                                <p class="section-subtitle"><?= esc($copy['chart_summary']) ?>: Desktop <?= esc((string) ($device['pc'] ?? 0)) ?>%, Tablet <?= esc((string) ($device['tablet'] ?? 0)) ?>%, Mobile <?= esc((string) ($device['mobile'] ?? 0)) ?>%.</p>
                                <div class="chart-summary" aria-label="<?= esc($copy['chart_summary']) ?>">
                                    <div class="chart-row"><span>Desktop</span><div class="bar"><span style="--value: <?= esc((string) ($device['pc'] ?? 0)) ?>%"></span></div><strong><?= esc((string) ($device['pc'] ?? 0)) ?>%</strong></div>
                                    <div class="chart-row"><span>Tablet</span><div class="bar"><span style="--value: <?= esc((string) ($device['tablet'] ?? 0)) ?>%"></span></div><strong><?= esc((string) ($device['tablet'] ?? 0)) ?>%</strong></div>
                                    <div class="chart-row"><span>Mobile</span><div class="bar"><span style="--value: <?= esc((string) ($device['mobile'] ?? 0)) ?>%"></span></div><strong><?= esc((string) ($device['mobile'] ?? 0)) ?>%</strong></div>
                                </div>
                            </div>
                            <div class="card card-pad">
                                <h3 class="section-title"><?= esc($copy['latest_activity']) ?></h3>
                                <ul class="activity-list">
                                    <li><i class="bi bi-clock-history"></i><span><?= esc($copy['pending_courses']) ?>: <strong><?= number_format(count($approvalCourses)) ?></strong></span></li>
                                    <li><i class="bi bi-ui-checks"></i><span><?= esc($copy['pending_surveys']) ?>: <strong><?= number_format(count($approvalSurveys)) ?></strong></span></li>
                                    <li><i class="bi bi-megaphone"></i><span><?= esc($copy['available_surveys']) ?>: <strong><?= number_format(count($publicSurveys)) ?></strong></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <section class="section dashboard-mode" data-dashboard-mode="learner" <?= $hasAdminAccess ? 'hidden' : '' ?>>
                <div class="section-header">
                    <div>
                        <h2 class="section-title"><?= esc($copy['course_progress']) ?></h2>
                        <p class="section-subtitle"><?= esc($copy['assigned_courses']) ?></p>
                    </div>
                </div>

                <div class="stats-grid" role="group" aria-label="<?= esc($copy['course_progress']) ?>">
                    <?php
                    $filters = [
                        'all' => ['label' => $copy['total_courses'], 'icon' => 'bi bi-collection', 'value' => $learnerStatus['all']],
                        'in-progress' => ['label' => $copy['in_progress'], 'icon' => 'bi bi-play-circle', 'value' => $learnerStatus['in-progress']],
                        'not-started' => ['label' => $copy['not_started'], 'icon' => 'bi bi-circle', 'value' => $learnerStatus['not-started']],
                        'completed' => ['label' => $copy['completed_courses'], 'icon' => 'bi bi-check-circle', 'value' => $learnerStatus['completed']],
                        'due-soon' => ['label' => $copy['due_soon'], 'icon' => 'bi bi-alarm', 'value' => $learnerStatus['due-soon']],
                    ];
                    ?>
                    <?php foreach ($filters as $filterKey => $filter): ?>
                        <button class="filter-card js-course-filter" type="button" data-filter="<?= esc($filterKey) ?>" aria-pressed="<?= $filterKey === 'all' ? 'true' : 'false' ?>">
                            <span class="card-top"><span><?= esc($filter['label']) ?></span><i class="<?= esc($filter['icon']) ?>"></i></span>
                            <span class="value"><?= number_format((int) $filter['value']) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="section chart-grid learner-chart-grid">
                    <article class="chart-card chart-card-feature">
                        <div class="chart-card-head">
                            <div>
                                <h3 class="section-title"><?= esc($copy['course_progress']) ?></h3>
                                <p class="section-subtitle"><?= esc($copy['in_progress']) ?> / <?= esc($copy['completed_courses']) ?></p>
                            </div>
                            <span class="chart-chip"><i class="bi bi-stars"></i> <?= esc((string) $completionRate) ?>%</span>
                        </div>
                        <div class="chart-canvas-wrap doughnut-wrap">
                            <canvas id="learnerStatusChart" aria-label="<?= esc($copy['course_progress']) ?>"></canvas>
                            <div class="chart-center-label">
                                <strong><?= esc((string) $completionRate) ?>%</strong>
                                <span><?= esc($copy['completion_rate']) ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="chart-card">
                        <div class="chart-card-head">
                            <div>
                                <h3 class="section-title"><?= esc($copy['device_usage']) ?></h3>
                                <p class="section-subtitle"><?= esc($copy['chart_summary']) ?></p>
                            </div>
                            <span class="chart-chip"><i class="bi bi-phone"></i> Device</span>
                        </div>
                        <div class="chart-canvas-wrap">
                            <canvas id="deviceUsageChart" aria-label="<?= esc($copy['device_usage']) ?>"></canvas>
                        </div>
                    </article>
                </div>

                <div class="section content-grid">
                    <div>
                        <div class="section-header">
                            <div>
                                <h3 class="section-title"><?= esc($copy['continue_learning']) ?></h3>
                                <p class="section-subtitle"><?= esc($copy['last_accessed']) ?></p>
                            </div>
                        </div>
                        <?php if ($continueCourse): ?>
                            <?php
                            $progress = $courseProgress($continueCourse);
                            $bucket = $courseBucket($continueCourse);
                            $cta = $bucket === 'completed' ? $copy['review'] : ($bucket === 'not-started' ? $copy['start'] : $copy['continue']);
                            ?>
                            <article class="continue-card">
                                <div class="course-thumb">
                                    <img loading="lazy" src="<?= esc($continueCourse['image_url'] ?? base_url('uploads/course/default_profile.jpg')) ?>" onerror="this.src='<?= base_url('uploads/course/default_profile.jpg') ?>'" alt="<?= esc($continueCourse['title'] ?? $copy['continue_learning']) ?>">
                                </div>
                                <div class="continue-body">
                                    <span class="status-badge <?= $bucket === 'completed' ? 'completed' : ($bucket === 'in-progress' ? 'progress' : 'waiting') ?>"><?= esc($continueCourse['learning_label'] ?? $filters[$bucket]['label'] ?? $copy['status']) ?></span>
                                    <h3 class="course-title"><?= esc($continueCourse['title'] ?? '-') ?></h3>
                                    <div class="course-meta">
                                        <span><i class="bi bi-tag"></i> <?= esc($continueCourse['company_name'] ?? $companyName) ?></span>
                                        <span><i class="bi bi-calendar-event"></i> <?= esc($continueCourse['period_label'] ?? $copy['unlimited']) ?></span>
                                        <span><i class="bi bi-clock"></i> <?= esc($copy['approx']) ?> <?= max(10, 100 - $progress) ?> <?= esc($copy['minutes']) ?></span>
                                    </div>
                                    <div class="progress-row">
                                        <div class="progress-label"><span><?= esc($copy['progress']) ?></span><strong><?= esc((string) $progress) ?>%</strong></div>
                                        <div class="progress-track" aria-hidden="true"><div class="progress-fill" style="--progress: <?= esc((string) $progress) ?>%"></div></div>
                                    </div>
                                    <div class="course-footer">
                                        <span class="section-subtitle"><?= esc($copy['last_accessed']) ?>: <?= esc($formatDate($continueCourse['cosen_firsttime'] ?? null)) ?></span>
                                        <a class="course-button" href="<?= site_url('coursemain/detail/' . $continueCourse['cos_id']) ?>"><i class="bi bi-arrow-right"></i><?= esc($cta) ?></a>
                                    </div>
                                </div>
                            </article>
                        <?php else: ?>
                            <div class="empty-state"><?= esc($copy['empty_courses']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="card card-pad">
                        <h3 class="section-title"><?= esc($copy['announcements']) ?></h3>
                        <ul class="announcement-list">
                            <li><i class="bi bi-megaphone"></i><span><?= esc($copy['new_announcement']) ?></span></li>
                            <li><i class="bi bi-ui-checks"></i><span><?= esc($copy['available_surveys']) ?>: <strong><?= number_format(count($publicSurveys)) ?></strong></span></li>
                        </ul>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <div>
                            <h3 class="section-title"><?= esc($copy['assigned_courses']) ?></h3>
                            <p class="section-subtitle"><?= esc($copy['recommended_courses']) ?></p>
                        </div>
                    </div>
                    <?php if ($allCourses): ?>
                        <div class="course-grid" id="courseGrid">
                            <?php foreach ($allCourses as $course): ?>
                                <?php
                                $progress = $courseProgress($course);
                                $bucket = $courseBucket($course);
                                $isDueSoon = in_array($course, $dueSoonCourses, true);
                                $cta = $bucket === 'completed' ? $copy['review'] : ($bucket === 'not-started' ? $copy['start'] : $copy['continue']);
                                ?>
                                <article class="course-card js-course-card"
                                         data-status="<?= esc($bucket) ?>"
                                         data-due-soon="<?= $isDueSoon ? '1' : '0' ?>"
                                         data-search="<?= esc(strtolower(($course['title'] ?? '') . ' ' . ($course['company_name'] ?? '') . ' ' . ($course['ccode'] ?? ''))) ?>">
                                    <div class="course-thumb">
                                        <img loading="lazy" src="<?= esc($course['image_url'] ?? base_url('uploads/course/default_profile.jpg')) ?>" onerror="this.src='<?= base_url('uploads/course/default_profile.jpg') ?>'" alt="<?= esc($course['title'] ?? $copy['assigned_courses']) ?>">
                                    </div>
                                    <div class="course-body">
                                        <div class="course-footer">
                                            <span class="status-badge <?= $bucket === 'completed' ? 'completed' : ($bucket === 'in-progress' ? 'progress' : 'waiting') ?>"><?= esc($course['learning_label'] ?? $filters[$bucket]['label'] ?? $copy['status']) ?></span>
                                        </div>
                                        <h3 class="course-title"><?= esc($course['title'] ?? '-') ?></h3>
                                        <div class="course-meta">
                                            <span><i class="bi bi-tag"></i> <?= esc($course['company_name'] ?? $companyName) ?></span>
                                            <span><i class="bi bi-calendar-event"></i> <?= esc($course['period_label'] ?? $copy['unlimited']) ?></span>
                                            <span><i class="bi bi-hash"></i> <?= esc($course['ccode'] ?? '-') ?></span>
                                        </div>
                                        <div class="progress-row">
                                            <div class="progress-label"><span><?= esc($copy['progress']) ?></span><strong><?= esc((string) $progress) ?>%</strong></div>
                                            <div class="progress-track" aria-hidden="true"><div class="progress-fill" style="--progress: <?= esc((string) $progress) ?>%"></div></div>
                                        </div>
                                        <div class="course-footer">
                                            <span class="section-subtitle"><?= esc($copy['last_accessed']) ?>: <?= esc($formatDate($course['cosen_firsttime'] ?? null)) ?></span>
                                            <a class="course-button" href="<?= site_url('coursemain/detail/' . $course['cos_id']) ?>"><?= esc($cta) ?></a>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <div class="empty-state" id="courseEmpty" hidden><?= esc($copy['empty_courses']) ?></div>
                    <?php else: ?>
                        <div class="empty-state"><?= esc($copy['empty_courses']) ?></div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</div>

<?php if ($mobileItems): ?>
    <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
        <?php foreach ($mobileItems as $menu): ?>
            <a class="bottom-link <?= ($menu['path'] ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= site_url($menu['path'] ?? 'dashboard') ?>">
                <i class="<?= esc($menuIcon((string) ($menu['icon'] ?? ''))) ?>"></i>
                <span><?= esc($menu['name'] ?? '-') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>

<div class="action-modal" id="dashboardActionModal" hidden role="dialog" aria-modal="true" aria-labelledby="actionModalTitle">
    <div class="action-modal-backdrop" data-close-action-modal></div>
    <div class="action-modal-panel">
        <h2 class="action-modal-title" id="actionModalTitle"></h2>
        <p class="action-modal-message" id="actionModalMessage"></p>
        <div class="action-modal-actions">
            <button class="btn-secondary" type="button" data-close-action-modal><?= esc($isThai ? 'ปิด' : 'Close') ?></button>
            <a class="btn-primary" id="actionModalCta" href="<?= site_url('manage/userdata') ?>"></a>
        </div>
    </div>
</div>

<div class="profile-modal" id="dashboardProfileModal" hidden>
    <div class="profile-modal-backdrop" data-close-profile-modal></div>
    <section class="profile-modal-panel profile-card-modal" role="dialog" aria-modal="true" aria-label="Profile">
        <button class="profile-modal-close" type="button" data-close-profile-modal aria-label="Close">×</button>
        <div class="profile-modal-head">
            <img class="profile-modal-avatar" src="<?= esc($profileImage) ?>" onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'" alt="<?= esc($displayName) ?>">
            <div>
                <p class="profile-modal-kicker"><?= esc($isThai ? 'บัญชีผู้ใช้งาน' : 'User Account') ?></p>
                <h2><?= esc($displayName) ?></h2>
                <span><?= esc($roleName) ?></span>
            </div>
        </div>
        <div class="profile-modal-info">
            <div><span><?= esc($copy['company']) ?></span><strong><?= esc($companyName) ?></strong></div>
            <div><span>Username</span><strong><?= esc($user['useri'] ?? '-') ?></strong></div>
            <div><span>Email</span><strong><?= esc($user['email'] ?? '-') ?></strong></div>
            <div><span>Employee ID</span><strong><?= esc($user['emp_c'] ?? $user['emp_id'] ?? '-') ?></strong></div>
        </div>
        <div class="profile-modal-actions">
            <a class="profile-modal-action primary" href="<?= site_url('dashboard/profile/setting') ?>">
                <i class="bi bi-person-lines-fill"></i><span><?= esc($isThai ? 'แก้ไขโปรไฟล์' : 'Edit Profile') ?></span>
            </a>
            <a class="profile-modal-action" href="<?= site_url('dashboard/change_pass') ?>">
                <i class="bi bi-key"></i><span><?= esc($isThai ? 'เปลี่ยนรหัสผ่าน' : 'Change Password') ?></span>
            </a>
            <a class="profile-modal-action" href="<?= site_url('dashboard/profile/certificate') ?>">
                <i class="bi bi-award"></i><span><?= esc($isThai ? 'ใบประกาศนียบัตร' : 'Certificates') ?></span>
            </a>
            <a class="profile-modal-action danger" href="<?= site_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i><span><?= esc($copy['logout']) ?></span>
            </a>
        </div>
    </section>
</div>

<style>
.approval-alert{margin:16px 20px 0;padding:12px 14px;border-radius:10px;font-weight:700}
.approval-alert-success{background:#ecfdf3;color:#166534;border:1px solid #bbf7d0}
.approval-alert-error{background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}
.approval-empty{margin:18px 20px 22px;padding:24px;border:1px dashed #d8dee8;border-radius:12px;color:#64748b;text-align:center}
.approval-empty i{margin-right:8px;color:#16a34a}
.approval-kind{display:inline-flex;align-items:center;gap:7px;padding:6px 9px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:800;white-space:nowrap}
.approval-kind.survey{background:#f5f3ff;color:#6d28d9}
.approval-kind.group{background:#fff7ed;color:#c2410c}
.approval-bulk{display:grid;grid-template-columns:auto minmax(220px,1fr) auto auto;gap:8px;align-items:center;margin:12px 20px;padding:10px 12px;border:1px solid #e5eaf1;border-radius:10px;background:#f8fafc}
.approval-bulk input{min-height:38px;border:1px solid #d8dee8;border-radius:9px;padding:8px 10px}
.history-state{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:12px;font-weight:800}
.history-state.approved{background:#dcfce7;color:#166534}.history-state.rejected{background:#ffe4e6;color:#9f1239}
.approval-table td{vertical-align:middle}
.approval-table td:nth-child(2){min-width:220px;font-weight:700}
.approval-form{display:grid;grid-template-columns:minmax(180px,1fr) auto auto;gap:8px;align-items:center;min-width:500px}
.approval-form input{width:100%;min-height:38px;border:1px solid #d8dee8;border-radius:9px;padding:8px 10px;background:#fff}
.approval-form input:focus{outline:2px solid rgba(37,99,235,.2);border-color:#2563eb}
.approval-btn{min-height:38px;border:0;border-radius:9px;padding:8px 12px;color:#fff;font-weight:800;cursor:pointer;white-space:nowrap}
.approval-btn.approve{background:#15803d}
.approval-btn.reject{background:#be123c}
.approval-btn:hover{filter:brightness(.94)}
@media(max-width:767px){
    .approval-bulk{grid-template-columns:1fr 1fr}.approval-bulk strong,.approval-bulk input{grid-column:1/-1}
    .approval-form{grid-template-columns:1fr 1fr;min-width:360px}
    .approval-form input{grid-column:1/-1}
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const actionMenus = <?= json_encode($actionMenus, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const dashboardChartData = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const modePills = document.querySelectorAll('[data-mode-option]');
const modeSwitchWrap = document.querySelector('.mode-switch');
const modeSections = document.querySelectorAll('.dashboard-mode');
const courseFilters = document.querySelectorAll('.js-course-filter');
const courseCards = document.querySelectorAll('.js-course-card');
const courseSearch = document.getElementById('courseSearch');
const courseEmpty = document.getElementById('courseEmpty');
const actionModal = document.getElementById('dashboardActionModal');
const actionModalTitle = document.getElementById('actionModalTitle');
const actionModalMessage = document.getElementById('actionModalMessage');
const actionModalCta = document.getElementById('actionModalCta');
const profileModal = document.getElementById('dashboardProfileModal');
let activeFilter = 'all';

document.querySelectorAll('.approval-form,.approval-bulk').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const submitter = event.submitter;
        if (form.classList.contains('approval-bulk') && !document.querySelector(`input[form="${form.id}"]:checked`)) {
            event.preventDefault();
            window.alert(<?= json_encode($isThai ? 'กรุณาเลือกรายการอย่างน้อยหนึ่งรายการ' : 'Please select at least one item.', JSON_UNESCAPED_UNICODE) ?>);
            return;
        }
        if (!submitter || submitter.value !== 'reject') {
            return;
        }

        const note = form.querySelector('input[name="note"]');
        if (!note || note.value.trim() !== '') {
            return;
        }

        event.preventDefault();
        note.setCustomValidity(<?= json_encode($isThai ? 'กรุณาระบุเหตุผลที่ไม่อนุมัติ' : 'Please provide a rejection reason.', JSON_UNESCAPED_UNICODE) ?>);
        note.reportValidity();
        note.addEventListener('input', () => note.setCustomValidity(''), { once: true });
    });
});

function setMode(mode) {
    modePills.forEach((button) => button.classList.toggle('active', button.dataset.modeOption === mode));
    if (modeSwitchWrap) {
        modeSwitchWrap.dataset.activeMode = mode;
    }
    modeSections.forEach((section) => {
        section.hidden = section.dataset.dashboardMode !== mode;
    });
}

function filterCourses() {
    const query = (courseSearch?.value || '').trim().toLowerCase();
    let visible = 0;

    courseCards.forEach((card) => {
        const statusMatch = activeFilter === 'all'
            || card.dataset.status === activeFilter
            || (activeFilter === 'due-soon' && card.dataset.dueSoon === '1');
        const searchMatch = !query || (card.dataset.search || '').includes(query);
        const show = statusMatch && searchMatch;
        card.hidden = !show;
        if (show) {
            visible += 1;
        }
    });

    if (courseEmpty) {
        courseEmpty.hidden = visible !== 0;
    }
}

function openActionModal(actionKey) {
    const action = actionMenus[actionKey];
    if (!action || !actionModal) {
        return;
    }

    actionModalTitle.textContent = action.title || '';
    actionModalMessage.textContent = action.message || '';
    actionModalCta.textContent = action.cta || '';
    actionModalCta.href = action.href || '#';
    actionModal.hidden = false;
    actionModalCta.focus();
}

function closeActionModal() {
    if (actionModal) {
        actionModal.hidden = true;
    }
}

function openProfileModal(url) {
    if (!profileModal) {
        window.location.href = url;
        return;
    }

    profileModal.hidden = false;
    profileModal.querySelector('.profile-modal-close')?.focus();
}

function closeProfileModal() {
    if (profileModal) {
        profileModal.hidden = true;
    }
}

document.querySelector('.menu-toggle')?.addEventListener('click', function () {
    const open = !document.body.classList.contains('nav-open');
    document.body.classList.toggle('nav-open', open);
    this.setAttribute('aria-expanded', String(open));
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        document.body.classList.remove('nav-open');
        document.querySelector('.menu-toggle')?.setAttribute('aria-expanded', 'false');
        closeActionModal();
        closeProfileModal();
    }
});

document.addEventListener('click', function (event) {
    const link = event.target.closest && event.target.closest('a[href]');
    if (!link) {
        return;
    }

    const href = link.getAttribute('href') || '';
    if (link.matches('[data-profile-trigger]') || /dashboard\/profile\/?$/.test(href)) {
        event.preventDefault();
        openProfileModal(link.href);
    }
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

document.querySelectorAll('.side-action').forEach((button) => {
    button.addEventListener('click', () => openActionModal(button.dataset.action));
});

document.querySelectorAll('[data-close-action-modal]').forEach((item) => {
    item.addEventListener('click', closeActionModal);
});

document.querySelectorAll('[data-close-profile-modal]').forEach((item) => {
    item.addEventListener('click', closeProfileModal);
});

window.addEventListener('message', function (event) {
    if (!event.data || (event.data.type !== 'profileSaved' && event.data.type !== 'profileModalClose')) {
        return;
    }

    closeProfileModal();
    if (event.data.type === 'profileSaved') {
        window.location.reload();
    }
});

modePills.forEach((button) => button.addEventListener('click', () => setMode(button.dataset.modeOption || 'admin')));
courseFilters.forEach((button) => {
    button.addEventListener('click', () => {
        activeFilter = button.dataset.filter || 'all';
        courseFilters.forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
        filterCourses();
    });
});
courseSearch?.addEventListener('input', filterCourses);

if (modePills.length === 0) {
    setMode('learner');
} else {
    setMode('admin');
}

if (window.jQuery && $.fn.DataTable && document.getElementById('companyAnalyticsTable')) {
    $('#companyAnalyticsTable').DataTable({
        pageLength: 8,
        lengthChange: false,
        language: {
            search: '<?= $isThai ? 'ค้นหา:' : 'Search:' ?>',
            info: '<?= $isThai ? 'แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ' : 'Showing _START_ to _END_ of _TOTAL_ entries' ?>',
            infoEmpty: '<?= $isThai ? 'ไม่มีข้อมูล' : 'No entries' ?>',
            zeroRecords: '<?= $isThai ? 'ไม่พบข้อมูล' : 'No matching records found' ?>',
            paginate: {
                next: '<?= $isThai ? 'ถัดไป' : 'Next' ?>',
                previous: '<?= $isThai ? 'ก่อนหน้า' : 'Previous' ?>'
            }
        }
    });
}

function makeGradient(ctx, area, from, to) {
    const gradient = ctx.createLinearGradient(0, area.bottom, 0, area.top);
    gradient.addColorStop(0, from);
    gradient.addColorStop(1, to);
    return gradient;
}

function createDashboardCharts() {
    if (!window.Chart) {
        return;
    }

    Chart.defaults.font.family = '"Inter", "Noto Sans Thai", "Prompt", system-ui, sans-serif';
    Chart.defaults.color = '#647083';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = '#172033';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 12;

    const palette = ['#f5b400', '#2563eb', '#16a34a', '#f59e0b', '#94a3b8', '#111827'];
    const doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: { position: 'bottom' },
        },
    };

    const courseStatusCanvas = document.getElementById('courseStatusChart');
    if (courseStatusCanvas) {
        new Chart(courseStatusCanvas, {
            type: 'doughnut',
            data: {
                labels: dashboardChartData.courseStatus.labels,
                datasets: [{
                    data: dashboardChartData.courseStatus.values,
                    backgroundColor: palette.slice(0, 4),
                    borderColor: '#fffdfa',
                    borderWidth: 5,
                    hoverOffset: 8,
                }],
            },
            options: doughnutOptions,
        });
    }

    const learnerStatusCanvas = document.getElementById('learnerStatusChart');
    if (learnerStatusCanvas) {
        new Chart(learnerStatusCanvas, {
            type: 'doughnut',
            data: {
                labels: dashboardChartData.learner.labels,
                datasets: [{
                    data: dashboardChartData.learner.values,
                    backgroundColor: ['#2563eb', '#f59e0b', '#16a34a', '#ef4444'],
                    borderColor: '#fffdfa',
                    borderWidth: 5,
                    hoverOffset: 8,
                }],
            },
            options: doughnutOptions,
        });
    }

    const companyUsersCanvas = document.getElementById('companyUsersChart');
    if (companyUsersCanvas) {
        const ctx = companyUsersCanvas.getContext('2d');
        new Chart(companyUsersCanvas, {
            type: 'bar',
            data: {
                labels: dashboardChartData.companies.labels,
                datasets: [{
                    label: 'Users',
                    data: dashboardChartData.companies.values,
                    borderRadius: 12,
                    borderSkipped: false,
                    backgroundColor(context) {
                        const area = context.chart.chartArea;
                        if (!area) return '#f5b400';
                        return makeGradient(ctx, area, 'rgba(245,180,0,.35)', 'rgba(245,180,0,.95)');
                    },
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true } },
                    y: { beginAtZero: true, grid: { color: '#edf0f3' }, ticks: { precision: 0 } },
                },
            },
        });
    }

    const activityCanvas = document.getElementById('activityChart');
    if (activityCanvas) {
        const ctx = activityCanvas.getContext('2d');
        new Chart(activityCanvas, {
            type: 'line',
            data: {
                labels: dashboardChartData.activity.labels,
                datasets: [{
                    label: 'Queue',
                    data: dashboardChartData.activity.values,
                    tension: 0.45,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#172033',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    borderColor: '#172033',
                    backgroundColor(context) {
                        const area = context.chart.chartArea;
                        if (!area) return 'rgba(245,180,0,.18)';
                        return makeGradient(ctx, area, 'rgba(245,180,0,.02)', 'rgba(245,180,0,.26)');
                    },
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#edf0f3' }, ticks: { precision: 0 } },
                },
            },
        });
    }

    ['adminDeviceUsageChart', 'deviceUsageChart'].forEach((canvasId) => {
        const deviceCanvas = document.getElementById(canvasId);
        if (!deviceCanvas) {
            return;
        }

        new Chart(deviceCanvas, {
            type: 'polarArea',
            data: {
                labels: dashboardChartData.device.labels,
                datasets: [{
                    data: dashboardChartData.device.values,
                    backgroundColor: ['rgba(245,180,0,.72)', 'rgba(37,99,235,.56)', 'rgba(22,163,74,.58)'],
                    borderColor: '#fffdfa',
                    borderWidth: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    r: {
                        ticks: { display: false },
                        grid: { color: '#edf0f3' },
                        angleLines: { color: '#edf0f3' },
                    },
                },
            },
        });
    });
}

createDashboardCharts();
filterCourses();
</script>
<script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
