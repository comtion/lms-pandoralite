<?php
$isEdit = ! empty($lesson);
$selectedLang = array_filter(explode(',', (string) ($lesson['les_lang'] ?? 'eng')));
$dateValue = static function ($value): string {
    $raw = (string) ($value ?? '');
    if ($raw === '' || str_starts_with($raw, '0000-00-00')) {
        return '';
    }
    $time = strtotime($raw);
    return $time ? date('Y-m-d\TH:i', $time) : '';
};
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { --brand:#e71921; --ink:#1f2937; --muted:#697386; --line:#e5e7eb; --bg:#f4f6f9; --panel:#fff; --soft:#f9fafb; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial, "Helvetica Neue", sans-serif; }
        a { color:inherit; text-decoration:none; }
        .topbar { position:sticky; top:0; z-index:10; background:#fff; border-bottom:1px solid var(--line); }
        .brand-row { max-width:1250px; margin:0 auto; min-height:74px; display:grid; grid-template-columns:220px 1fr 220px; align-items:center; gap:18px; padding:10px 22px; }
        .brand-mark { font-weight:900; font-size:20px; color:var(--brand); }
        .brand-center { text-align:center; font-size:28px; font-weight:900; }
        .brand-actions { display:flex; justify-content:flex-end; gap:14px; color:var(--muted); font-size:13px; }
        .logout { color:#111827; font-weight:800; }
        .page { max-width:1080px; margin:0 auto; padding:28px 22px 42px; }
        .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:22px; margin-bottom:18px; }
        .kicker { color:var(--brand); font-weight:900; font-size:12px; text-transform:uppercase; }
        h1 { margin:5px 0 18px; font-size:30px; }
        h2 { margin:0; font-size:20px; }
        .section-head { display:flex; justify-content:space-between; align-items:flex-end; gap:14px; margin-bottom:16px; }
        .section-note { color:var(--muted); font-size:13px; margin-top:4px; }
        .form-grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:14px; }
        .field.full { grid-column:1 / -1; }
        label { display:block; color:var(--muted); font-size:12px; font-weight:800; margin-bottom:6px; }
        input, select, textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:11px 12px; font-size:14px; background:#fff; }
        textarea { min-height:92px; resize:vertical; }
        .checks { display:flex; gap:12px; flex-wrap:wrap; padding:10px 0; }
        .checks label { display:flex; align-items:center; gap:7px; margin:0; color:var(--ink); font-size:14px; }
        .checks input { width:auto; }
        .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:18px; }
        .btn { border:1px solid var(--line); background:#fff; color:#374151; border-radius:7px; padding:11px 15px; font-weight:900; cursor:pointer; }
        .btn.primary { background:var(--brand); color:#fff; border-color:var(--brand); }
        .error { border:1px solid #ffc7c7; background:#fff1f1; color:#b42318; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .notice { border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-weight:800; }
        .asset-list { display:grid; gap:12px; margin-bottom:18px; }
        .asset-item { border:1px solid var(--line); border-radius:8px; padding:14px; background:var(--soft); }
        .asset-title { display:flex; justify-content:space-between; gap:12px; margin-bottom:12px; font-weight:900; }
        .asset-title a { color:var(--brand); font-size:13px; }
        .empty { color:var(--muted); border:1px dashed var(--line); border-radius:8px; padding:14px; margin-bottom:18px; background:#fff; }
        @media (max-width:780px) {
            .brand-row,.form-grid { grid-template-columns:1fr; }
            .brand-center,.brand-actions { text-align:left; justify-content:flex-start; }
            .section-head { display:block; }
        }
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
    <?php if (session()->getFlashdata('course_error')): ?><div class="error"><?= esc(session()->getFlashdata('course_error')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('course_notice')): ?><div class="notice"><?= esc(session()->getFlashdata('course_notice')) ?></div><?php endif; ?>

    <section class="panel">
        <div class="kicker"><?= esc($course['ccode'] ?? 'Course') ?></div>
        <h1><?= esc($title) ?></h1>
        <form method="post" action="<?= $isEdit ? site_url('managecourse/lessons/' . $lesson['les_id'] . '/update') : site_url('managecourse/courses_all/' . $course['cos_id'] . '/lessons/create') ?>"><?= csrf_field() ?>
            <div class="form-grid">
                <div class="field"><label>Sequence</label><input name="les_sequences" type="number" min="0" value="<?= esc($lesson['les_sequences'] ?? '0') ?>"></div>
                <div class="field"><label>Type</label><select name="les_type"><option value="1" <?= (string) ($lesson['les_type'] ?? '1') === '1' ? 'selected' : '' ?>>Media</option><option value="2" <?= (string) ($lesson['les_type'] ?? '') === '2' ? 'selected' : '' ?>>SCORM</option></select></div>
                <div class="field"><label>Status</label><select name="les_status"><option value="1" <?= (string) ($lesson['les_status'] ?? '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= (string) ($lesson['les_status'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
                <div class="field"><label>English Name</label><input name="les_name_eng" required value="<?= esc($lesson['les_name_eng'] ?? '') ?>"></div>
                <div class="field"><label>Thai Name</label><input name="les_name_th" value="<?= esc($lesson['les_name_th'] ?? '') ?>"></div>
                <div class="field"><label>Japanese Name</label><input name="les_name_jp" value="<?= esc($lesson['les_name_jp'] ?? '') ?>"></div>
                <div class="field full"><label>English Description</label><textarea name="les_info_eng"><?= esc($lesson['les_info_eng'] ?? '') ?></textarea></div>
                <div class="field full"><label>Thai Description</label><textarea name="les_info_th"><?= esc($lesson['les_info_th'] ?? '') ?></textarea></div>
                <div class="field"><label>Start Time</label><input name="time_start" type="datetime-local" value="<?= esc($dateValue($lesson['time_start'] ?? '')) ?>"></div>
                <div class="field"><label>End Time</label><input name="time_end" type="datetime-local" value="<?= esc($dateValue($lesson['time_end'] ?? '')) ?>"></div>
                <div class="field"><label>SCORM Type</label><select name="scm_type"><option value="0" <?= (string) ($lesson['scm_type'] ?? '0') === '0' ? 'selected' : '' ?>>Default</option><option value="2" <?= (string) ($lesson['scm_type'] ?? '') === '2' ? 'selected' : '' ?>>External</option></select></div>
                <div class="field full"><label>Languages</label><div class="checks">
                    <label><input type="checkbox" name="les_lang[]" value="eng" <?= in_array('eng', $selectedLang, true) ? 'checked' : '' ?>> English</label>
                    <label><input type="checkbox" name="les_lang[]" value="th" <?= in_array('th', $selectedLang, true) ? 'checked' : '' ?>> Thai</label>
                    <label><input type="checkbox" name="les_lang[]" value="jp" <?= in_array('jp', $selectedLang, true) ? 'checked' : '' ?>> Japanese</label>
                </div></div>
            </div>
            <div class="actions"><a class="btn" href="<?= site_url('managecourse/courses_all/' . $course['cos_id'] . '/edit') ?>">Cancel</a><button class="btn primary" type="submit">Save Lesson</button></div>
        </form>
    </section>

    <?php if ($isEdit): ?>
        <section class="panel">
            <div class="section-head">
                <div><h2>Media</h2><div class="section-note">Use a file name under uploads/media or an external URL.</div></div>
            </div>
            <?php if (empty($mediaItems)): ?><div class="empty">No media in this lesson.</div><?php endif; ?>
            <div class="asset-list">
                <?php foreach ($mediaItems as $item): ?>
                    <form class="asset-item" method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/media/' . $item['id'] . '/update') ?>"><?= csrf_field() ?>
                        <div class="asset-title"><span><?= esc($item['title'] ?: 'Media #' . $item['id']) ?></span><?php if (! empty($item['url'])): ?><a href="<?= esc($item['url']) ?>" target="_blank" rel="noopener">Open</a><?php endif; ?></div>
                        <div class="form-grid">
                            <div class="field"><label>English Name</label><input name="med_name_eng" required value="<?= esc($item['med_name_eng'] ?? '') ?>"></div>
                            <div class="field"><label>Thai Name</label><input name="med_name_th" value="<?= esc($item['med_name_th'] ?? '') ?>"></div>
                            <div class="field"><label>Japanese Name</label><input name="med_name_jp" value="<?= esc($item['med_name_jp'] ?? '') ?>"></div>
                            <div class="field"><label>Source Type</label><select name="type"><option value="upload" <?= (string) ($item['type'] ?? 'upload') === 'upload' ? 'selected' : '' ?>>Upload Path</option><option value="url" <?= (string) ($item['type'] ?? '') === 'url' ? 'selected' : '' ?>>External URL</option></select></div>
                            <div class="field"><label>Thumbnail Path</label><input name="thumbnail_med" value="<?= esc($item['thumbnail_med'] ?? '') ?>"></div>
                            <div class="field"><label>Video / URL</label><input name="video" value="<?= esc($item['video'] ?? '') ?>"></div>
                            <div class="field"><label>Replace Video File</label><input name="media_file" type="file" accept=".mp4,.mov,.webm,.avi,.m4v,video/*"></div>
                            <div class="field"><label>Replace Thumbnail File</label><input name="thumbnail_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/*"></div>
                        </div>
                        <div class="actions"><button class="btn primary" type="submit">Update Media</button></div>
                    </form>
                <?php endforeach; ?>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/lessons/' . $lesson['les_id'] . '/media/create') ?>"><?= csrf_field() ?>
                <div class="form-grid">
                    <div class="field"><label>English Name</label><input name="med_name_eng" required></div>
                    <div class="field"><label>Thai Name</label><input name="med_name_th"></div>
                    <div class="field"><label>Japanese Name</label><input name="med_name_jp"></div>
                    <div class="field"><label>Source Type</label><select name="type"><option value="upload">Upload Path</option><option value="url">External URL</option></select></div>
                    <div class="field"><label>Thumbnail Path</label><input name="thumbnail_med"></div>
                    <div class="field"><label>Video / URL</label><input name="video"></div>
                    <div class="field"><label>Video File</label><input name="media_file" type="file" accept=".mp4,.mov,.webm,.avi,.m4v,video/*"></div>
                    <div class="field"><label>Thumbnail File</label><input name="thumbnail_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/*"></div>
                </div>
                <div class="actions"><button class="btn primary" type="submit">Add Media</button></div>
            </form>
        </section>

        <section class="panel">
            <div class="section-head">
                <div><h2>Documents</h2><div class="section-note">Use a file name under uploads/document.</div></div>
            </div>
            <?php if (empty($documentItems)): ?><div class="empty">No documents in this lesson.</div><?php endif; ?>
            <div class="asset-list">
                <?php foreach ($documentItems as $item): ?>
                    <form class="asset-item" method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/documents/' . $item['id'] . '/update') ?>"><?= csrf_field() ?>
                        <div class="asset-title"><span><?= esc($item['title'] ?: 'Document #' . $item['id']) ?></span><?php if (! empty($item['url'])): ?><a href="<?= esc($item['url']) ?>" target="_blank" rel="noopener">Open</a><?php endif; ?></div>
                        <div class="form-grid">
                            <div class="field"><label>English Name</label><input name="name_file_eng" required value="<?= esc($item['name_file_eng'] ?? '') ?>"></div>
                            <div class="field"><label>Thai Name</label><input name="name_file_th" value="<?= esc($item['name_file_th'] ?? '') ?>"></div>
                            <div class="field"><label>Japanese Name</label><input name="name_file_jp" value="<?= esc($item['name_file_jp'] ?? '') ?>"></div>
                            <div class="field two"><label>File Path</label><input name="path_file" value="<?= esc($item['path_file'] ?? '') ?>"></div>
                            <div class="field"><label>Replace Document File</label><input name="document_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"></div>
                        </div>
                        <div class="actions"><button class="btn primary" type="submit">Update Document</button></div>
                    </form>
                <?php endforeach; ?>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/lessons/' . $lesson['les_id'] . '/documents/create') ?>"><?= csrf_field() ?>
                <div class="form-grid">
                    <div class="field"><label>English Name</label><input name="name_file_eng" required></div>
                    <div class="field"><label>Thai Name</label><input name="name_file_th"></div>
                    <div class="field"><label>Japanese Name</label><input name="name_file_jp"></div>
                    <div class="field two"><label>File Path</label><input name="path_file"></div>
                    <div class="field"><label>Document File</label><input name="document_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"></div>
                </div>
                <div class="actions"><button class="btn primary" type="submit">Add Document</button></div>
            </form>
        </section>

        <section class="panel">
            <div class="section-head">
                <div><h2>SCORM</h2><div class="section-note">Use the package folder/path under uploads/scorm.</div></div>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?= site_url('managecourse/lessons/' . $lesson['les_id'] . '/scorm/save') ?>"><?= csrf_field() ?>
                <div class="form-grid">
                    <div class="field two"><label>SCORM Path</label><input name="path" value="<?= esc($scormItem['path'] ?? '') ?>"></div>
                    <div class="field"><label>SCORM ZIP File</label><input name="scorm_file" type="file" accept=".zip"></div>
                </div>
                <?php if (! empty($scormItem['url'])): ?><div class="section-note"><a href="<?= esc($scormItem['url']) ?>" target="_blank" rel="noopener">Open current SCORM path</a></div><?php endif; ?>
                <div class="actions"><button class="btn primary" type="submit">Save SCORM</button></div>
            </form>
        </section>
    <?php endif; ?>
</main>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
