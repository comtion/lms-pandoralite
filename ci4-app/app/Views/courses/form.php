<?php
$isEdit = ! empty($course);
$selectedLang = array_filter(explode(',', (string) ($course['cos_lang'] ?? 'eng')));
$notification = $courseNotification ?? null;
$notifyChannels = array_filter(explode(',', (string) ($notification['channels'] ?? 'system,email')));
$notifyDepartments = array_filter(array_map('intval', explode(',', (string) ($notification['target_departments'] ?? ''))));
$notifyUsers = array_filter(array_map('intval', explode(',', (string) ($notification['target_users'] ?? ''))));
$notifySendAt = '';
if (! empty($notification['send_at']) && ! str_starts_with((string) $notification['send_at'], '0000-00-00')) {
    $notifySendAt = date('Y-m-d\TH:i', strtotime((string) $notification['send_at']));
}
?>
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
        .page { max-width:1100px; margin:0 auto; padding:28px 22px 42px; }
        .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:22px; }
        .kicker { color:var(--brand); font-weight:900; font-size:12px; text-transform:uppercase; }
        h1 { margin:5px 0 18px; font-size:30px; }
        .form-grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:14px; }
        .field.full { grid-column:1 / -1; }
        .field.two { grid-column:span 2; }
        label { display:block; color:var(--muted); font-size:12px; font-weight:800; margin-bottom:6px; }
        input, select, textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; background:#fff; }
        textarea { min-height:92px; resize:vertical; }
        .checks { display:flex; gap:12px; flex-wrap:wrap; padding:10px 0; }
        .checks label { display:flex; align-items:center; gap:7px; margin:0; color:var(--ink); font-size:14px; }
        .checks input { width:auto; }
        .group-picker { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px; }
        .group-option { display:flex; align-items:flex-start; gap:10px; border:1px solid var(--line); border-radius:8px; padding:11px; background:#fafafa; }
        .group-option input { width:auto; margin-top:2px; }
        .group-option strong { display:block; font-size:14px; }
        .group-option span { color:var(--muted); font-size:12px; }
        .sub-panel { grid-column:1 / -1; border:1px solid var(--line); border-radius:8px; padding:16px; background:#fafafa; }
        .sub-panel h2 { margin:0 0 12px; font-size:18px; }
        .multi-select { min-height:118px; }
        .hint { margin-top:6px; color:var(--muted); font-size:12px; }
        .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:18px; }
        .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:11px 15px; font-weight:900; cursor:pointer; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .notice { border:1px solid #bee7cf; background:#ecfdf3; color:#087443; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .lesson-list { margin-top:22px; border-top:1px solid var(--line); padding-top:18px; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        th, td { text-align:left; padding:11px 10px; border-bottom:1px solid var(--line); vertical-align:top; }
        th { color:var(--muted); font-size:12px; text-transform:uppercase; background:#fafafa; }
        .small-action { display:inline-flex; border:1px solid var(--line); border-radius:6px; padding:7px 9px; font-size:12px; font-weight:900; background:#fff; cursor:pointer; }
        @media (max-width:880px) { .brand-row,.form-grid,.group-picker { grid-template-columns:1fr; } .field.two { grid-column:auto; } .brand-center,.brand-actions { text-align:left; justify-content:flex-start; } }
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
</header>

<main class="page">
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <section class="panel">
        <div class="kicker"><?= esc($title_main ?: 'Manage Course') ?></div>
        <h1><?= esc($title) ?></h1>
        <form method="post" action="<?= $isEdit ? site_url('managecourse/courses_all/' . $course['cos_id'] . '/update') : site_url('managecourse/courses_all/create') ?>">
            <div class="form-grid">
                <div class="field"><label>Course Code</label><input name="ccode" maxlength="15" required value="<?= esc($course['ccode'] ?? '') ?>"></div>
                <div class="field"><label>Company</label><select id="courseCompanySelect" name="com_id" required>
                    <option value="">Select company</option>
                    <?php foreach (($companies ?? []) as $company): ?>
                        <option value="<?= esc($company['com_id']) ?>" <?= (string) ($course['com_id'] ?? '') === (string) $company['com_id'] ? 'selected' : '' ?>><?= esc($company['com_code'] . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?></option>
                    <?php endforeach; ?>
                </select></div>
                <div class="field"><label>Course Type</label><select name="tc_id" required>
                    <option value="">Select type</option>
                    <?php foreach (($types ?? []) as $type): ?>
                        <option value="<?= esc($type['tc_id']) ?>" <?= (string) ($course['tc_id'] ?? '') === (string) $type['tc_id'] ? 'selected' : '' ?>><?= esc(($type['tc_name_en'] ?: $type['tc_name_th']) . ' #' . $type['tc_id']) ?></option>
                    <?php endforeach; ?>
                </select></div>
                <div class="field"><label>English Name</label><input name="cname_eng" required value="<?= esc($course['cname_eng'] ?? '') ?>"></div>
                <div class="field"><label>Thai Name</label><input name="cname_th" value="<?= esc($course['cname_th'] ?? '') ?>"></div>
                <div class="field"><label>Japanese Name</label><input name="cname_jp" value="<?= esc($course['cname_jp'] ?? '') ?>"></div>
                <div class="field full"><label>English Short Description</label><textarea name="sub_description_eng"><?= esc($course['sub_description_eng'] ?? '') ?></textarea></div>
                <div class="field full"><label>English Description</label><textarea name="cdesc_eng"><?= esc($course['cdesc_eng'] ?? '') ?></textarea></div>
                <div class="field"><label>Seat Count</label><input name="seat_count" type="number" min="0" value="<?= esc($course['seat_count'] ?? '0') ?>"></div>
                <div class="field"><label>Hours</label><input name="cos_hour" type="number" min="0" value="<?= esc($course['cos_hour'] ?? '0') ?>"></div>
                <div class="field"><label>Goal Score</label><input name="goal_score" type="number" min="0" max="100" value="<?= esc($course['goal_score'] ?? '80') ?>"></div>
                <div class="field"><label>Max Score</label><input name="max_score" type="number" min="0" value="<?= esc($course['max_score'] ?? '100') ?>"></div>
                <div class="field"><label>Grading</label><select name="cos_typegrading"><option value="2" <?= (string) ($course['cos_typegrading'] ?? '2') === '2' ? 'selected' : '' ?>>Pass/Fail</option><option value="1" <?= (string) ($course['cos_typegrading'] ?? '') === '1' ? 'selected' : '' ?>>Grade</option></select></div>
                <div class="field"><label>Status</label><select name="cos_status"><option value="1" <?= (string) ($course['cos_status'] ?? '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) ($course['cos_status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
                <div class="field"><label>Visibility</label><select name="cos_public"><option value="0" <?= (string) ($course['cos_public'] ?? '0') === '0' ? 'selected' : '' ?>>Private</option><option value="1" <?= (string) ($course['cos_public'] ?? '') === '1' ? 'selected' : '' ?>>Public</option></select></div>
                <div class="field"><label>Approval</label><select name="cos_approve"><option value="0" <?= (string) ($course['cos_approve'] ?? '0') === '0' ? 'selected' : '' ?>>Waiting</option><option value="1" <?= (string) ($course['cos_approve'] ?? '') === '1' ? 'selected' : '' ?>>Approved</option></select></div>
                <div class="field two"><label>Languages</label><div class="checks">
                    <label><input type="checkbox" name="cos_lang[]" value="eng" <?= in_array('eng', $selectedLang, true) ? 'checked' : '' ?>> English</label>
                    <label><input type="checkbox" name="cos_lang[]" value="th" <?= in_array('th', $selectedLang, true) ? 'checked' : '' ?>> Thai</label>
                    <label><input type="checkbox" name="cos_lang[]" value="jp" <?= in_array('jp', $selectedLang, true) ? 'checked' : '' ?>> Japanese</label>
                </div></div>
                <div class="field"><label>Survey Required</label><select name="is_survey_required"><option value="0" <?= (string) ($course['is_survey_required'] ?? '0') === '0' ? 'selected' : '' ?>>No</option><option value="1" <?= (string) ($course['is_survey_required'] ?? '') === '1' ? 'selected' : '' ?>>Yes</option></select></div>
                <section class="sub-panel">
                    <h2>New Course Notification</h2>
                    <div class="form-grid">
                        <div class="field"><label>Enable Notification</label><select name="notify_enabled">
                            <option value="0" <?= (string) ($notification['enabled'] ?? '0') === '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= (string) ($notification['enabled'] ?? '') === '1' ? 'selected' : '' ?>>Yes</option>
                        </select></div>
                        <div class="field"><label>Start Sending</label><input name="notify_send_at" type="datetime-local" value="<?= esc($notifySendAt) ?>"><div class="hint">Blank means send as soon as the dispatch command runs.</div></div>
                        <div class="field"><label>Audience</label><select name="notify_audience_type">
                            <option value="all" <?= (string) ($notification['audience_type'] ?? 'all') === 'all' ? 'selected' : '' ?>>All active learners in company</option>
                            <option value="departments" <?= (string) ($notification['audience_type'] ?? '') === 'departments' ? 'selected' : '' ?>>Selected departments</option>
                            <option value="users" <?= (string) ($notification['audience_type'] ?? '') === 'users' ? 'selected' : '' ?>>Selected learners</option>
                        </select></div>
                        <div class="field full"><label>Channels</label><div class="checks">
                            <label><input type="checkbox" name="notify_channels[]" value="system" <?= in_array('system', $notifyChannels, true) ? 'checked' : '' ?>> In-system notification</label>
                            <label><input type="checkbox" name="notify_channels[]" value="email" <?= in_array('email', $notifyChannels, true) ? 'checked' : '' ?>> Email</label>
                        </div></div>
                        <div class="field two"><label>Departments</label><select id="notifyDepartmentSelect" class="multi-select" name="notify_department_ids[]" multiple>
                            <?php foreach (($departments ?? []) as $department): ?>
                                <?php $departmentName = $department['dep_name_en'] ?: $department['dep_name_th'] ?: ('Department #' . $department['dep_id']); ?>
                                <option value="<?= esc($department['dep_id']) ?>" <?= in_array((int) $department['dep_id'], $notifyDepartments, true) ? 'selected' : '' ?>><?= esc($departmentName) ?></option>
                            <?php endforeach; ?>
                        </select><div class="hint">Used when Audience is Selected departments.</div></div>
                        <div class="field"><label>Learners</label><select id="notifyUserSelect" class="multi-select" name="notify_user_ids[]" multiple>
                            <?php foreach (($learners ?? []) as $learner): ?>
                                <?php $learnerName = $learner['fullname_en'] ?: $learner['fullname_th'] ?: $learner['useri'] ?: ('Learner #' . $learner['emp_id']); ?>
                                <option value="<?= esc($learner['emp_id']) ?>" <?= in_array((int) $learner['emp_id'], $notifyUsers, true) ? 'selected' : '' ?>><?= esc($learnerName . ($learner['email'] ? ' - ' . $learner['email'] : '')) ?></option>
                            <?php endforeach; ?>
                        </select><div class="hint">Used when Audience is Selected learners.</div></div>
                    </div>
                    <?php if ($notification): ?><div class="hint">Current status: <?= esc($notification['status']) ?><?= ! empty($notification['dispatched_at']) ? ' at ' . esc($notification['dispatched_at']) : '' ?></div><?php endif; ?>
                </section>
            </div>
                <div class="actions">
                    <?php if ($isEdit): ?><a class="btn" href="<?= site_url('managecourse/quizzes?cos_id=' . $course['cos_id']) ?>">Manage Quizzes</a><?php endif; ?>
                    <?php if ($isEdit): ?><a class="btn" href="<?= site_url('managecourse/surveys?cos_id=' . $course['cos_id']) ?>">Manage Surveys</a><?php endif; ?>
                    <a class="btn" href="<?= site_url('managecourse/course_notifications') ?>">Notification Logs</a>
                    <a class="btn" href="<?= site_url('managecourse/courses_all') ?>">Cancel</a>
                    <button class="btn primary" type="submit">Save Course</button>
                </div>
        </form>

        <?php if ($isEdit): ?>
            <section class="lesson-list">
                <div class="actions" style="justify-content:space-between;margin-top:0">
                    <div>
                        <h2 style="margin:0;font-size:20px">Course Groups</h2>
                        <p style="margin:5px 0 0;color:var(--muted);font-size:13px">Assign this course to active groups in the selected company.</p>
                    </div>
                    <button class="btn primary" form="course-groups-form" type="submit">Save Groups</button>
                </div>
                <form id="course-groups-form" method="post" action="<?= site_url('managecourse/courses_all/' . $course['cos_id'] . '/groups/update') ?>">
                    <div class="group-picker">
                        <?php foreach (($courseGroups ?? []) as $group): ?>
                            <?php $checked = in_array((int) $group['cg_id'], array_map('intval', $selectedCourseGroups ?? []), true); ?>
                            <label class="group-option">
                                <input type="checkbox" name="cg_id[]" value="<?= esc($group['cg_id']) ?>" <?= $checked ? 'checked' : '' ?>>
                                <span><strong><?= esc($group['title'] ?: $group['cgtitle_en']) ?></strong><span><?= esc(($group['cgcode'] ?? '-') . ' #' . $group['cg_id']) ?></span></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if (empty($courseGroups)): ?><div style="color:var(--muted)">No active course groups for this company.</div><?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="lesson-list">
                <div class="actions" style="justify-content:space-between;margin-top:0">
                    <h2 style="margin:0;font-size:20px">Lessons</h2>
                    <a class="btn primary" href="<?= site_url('managecourse/courses_all/' . $course['cos_id'] . '/lessons/create') ?>">Create Lesson</a>
                </div>
                <table>
                    <thead><tr><th>Seq</th><th>Lesson</th><th>Type</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach (($lessons ?? []) as $lesson): ?>
                        <tr>
                            <td><?= esc($lesson['les_sequences']) ?></td>
                            <td><strong><?= esc($lesson['title']) ?></strong><br><span style="color:var(--muted)"><?= esc($lesson['description']) ?></span></td>
                            <td><?= (string) $lesson['les_type'] === '2' ? 'SCORM' : 'Media' ?></td>
                            <td><?= (string) $lesson['les_status'] === '1' ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a class="small-action" href="<?= site_url('managecourse/lessons/' . $lesson['les_id'] . '/edit') ?>">Edit</a>
                                <form method="post" action="<?= site_url('managecourse/lessons/' . $lesson['les_id'] . '/status') ?>" style="display:inline">
                                    <input type="hidden" name="status" value="<?= (string) $lesson['les_status'] === '1' ? '0' : '1' ?>">
                                    <button class="small-action" type="submit"><?= (string) $lesson['les_status'] === '1' ? 'Deactivate' : 'Activate' ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lessons)): ?><tr><td colspan="5">No lessons yet.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
    </section>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
    <script>
    (function () {
        const company = document.getElementById('courseCompanySelect');
        const departments = document.getElementById('notifyDepartmentSelect');
        const learners = document.getElementById('notifyUserSelect');
        if (!company || !departments || !learners) {
            return;
        }

        function option(value, text) {
            const node = document.createElement('option');
            node.value = value;
            node.textContent = text;
            return node;
        }

        company.addEventListener('change', function () {
            const companyId = company.value;
            departments.innerHTML = '';
            learners.innerHTML = '';
            if (!companyId) {
                return;
            }

            fetch('<?= site_url('managecourse/course_notification_audience') ?>?com_id=' + encodeURIComponent(companyId), {
                headers: { 'Accept': 'application/json' },
            })
                .then((response) => response.ok ? response.json() : Promise.reject(response))
                .then((payload) => {
                    if (!payload.ok) {
                        return;
                    }
                    payload.departments.forEach((department) => {
                        departments.appendChild(option(department.dep_id, department.dep_name_en || department.dep_name_th || ('Department #' + department.dep_id)));
                    });
                    payload.learners.forEach((learner) => {
                        const name = learner.fullname_en || learner.fullname_th || learner.useri || ('Learner #' + learner.emp_id);
                        learners.appendChild(option(learner.emp_id, name + (learner.email ? ' - ' + learner.email : '')));
                    });
                })
                .catch(() => {
                    departments.innerHTML = '';
                    learners.innerHTML = '';
                });
        });
    })();
    </script>
</body>
</html>
