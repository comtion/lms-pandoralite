<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCORM Player</title>
    <style>
        html, body { width:100%; height:100%; margin:0; overflow:hidden; background:#111827; font-family:Arial, sans-serif; }
        iframe { width:100%; height:100%; border:0; background:#fff; }
        .empty { height:100%; display:grid; place-items:center; color:#fff; }
    </style>
    <link href="<?= base_url('css/enterprise-pages.css?v=20260701-2') ?>" rel="stylesheet">
</head>
<body>
<?php if (! empty($package['launch_url'])): ?>
    <iframe id="scorm-frame" src="<?= esc($package['launch_url']) ?>" allowfullscreen></iframe>
<?php else: ?>
    <div class="empty">SCORM launch file not found.</div>
<?php endif; ?>

<script>
const scormEndpoint = <?= json_encode(site_url('scorm/datamodel/' . $scormId)) ?>;
const cache = <?= json_encode($values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?> || {};
let lastError = '0';
let initialized = false;

function normalizeName(name) {
    return String(name || '')
        .replace(/^cmi_core_/, 'cmi.core.')
        .replace(/^cmi_core_score_/, 'cmi.core.score.');
}

function setValue(name, value) {
    cache[normalizeName(name)] = String(value ?? '');
    lastError = '0';
    return 'true';
}

function getValue(name) {
    const key = normalizeName(name);
    lastError = '0';
    return cache[key] ?? '';
}

function commit() {
    const body = JSON.stringify({values: cache});
    if (navigator.sendBeacon) {
        const blob = new Blob([body], {type: 'application/json'});
        navigator.sendBeacon(scormEndpoint, blob);
        lastError = '0';
        return 'true';
    }

    fetch(scormEndpoint, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body,
        keepalive: true
    }).catch(() => {});
    lastError = '0';
    return 'true';
}

window.API = {
    LMSInitialize: function () { initialized = true; lastError = '0'; return 'true'; },
    LMSFinish: function () { initialized = false; return commit(); },
    LMSGetValue: getValue,
    LMSSetValue: setValue,
    LMSCommit: commit,
    LMSGetLastError: function () { return lastError; },
    LMSGetErrorString: function (code) { return code === '0' || code === 0 ? 'No error' : 'SCORM error'; },
    LMSGetDiagnostic: function () { return ''; }
};

window.API_1484_11 = {
    Initialize: function () { initialized = true; lastError = '0'; return 'true'; },
    Terminate: function () { initialized = false; return commit(); },
    GetValue: getValue,
    SetValue: setValue,
    Commit: commit,
    GetLastError: function () { return lastError; },
    GetErrorString: function (code) { return code === '0' || code === 0 ? 'No error' : 'SCORM error'; },
    GetDiagnostic: function () { return ''; }
};

window.addEventListener('beforeunload', commit);
</script>
    <script src="<?= base_url('js/enterprise-pages.js?v=20260701-3') ?>"></script>
</body>
</html>
