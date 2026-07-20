<?php
date_default_timezone_set('Asia/Bangkok');

$host = getenv('RUNFETCH_DB_HOST') ?: 'db';
$user = getenv('RUNFETCH_DB_USER') ?: 'root';
$pass = getenv('RUNFETCH_DB_PASSWORD');
if ($pass === false) {
    $pass = 'rootpass123!';
}
$name = getenv('RUNFETCH_DB_NAME') ?: 'lms_pandoralite_db';
$port = getenv('RUNFETCH_DB_PORT') ?: 3306;

$mysqli = new mysqli($host, $user, $pass, $name, (int) $port);
if ($mysqli->connect_errno) {
    echo "DB connection failed: {$mysqli->connect_error}\n";
    exit(1);
}
$mysqli->set_charset('utf8');

$result = $mysqli->query('SELECT * FROM lms_audit_logs ORDER BY audit_id ASC');
if (!$result) {
    echo "Query failed: {$mysqli->error}\n";
    exit(1);
}

$prevHash = null;
$checked = 0;
$failed = 0;
while ($row = $result->fetch_assoc()) {
    $auditId = $row['audit_id'];
    $hash = $row['audit_hash'];
    $storedPrev = $row['audit_prev_hash'];
    unset($row['audit_id'], $row['audit_prev_hash'], $row['audit_hash']);
    ksort($row);
    $expected = hash('sha256', (string) $prevHash . '|' . json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    if ($storedPrev !== $prevHash || $hash !== $expected) {
        echo "Hash mismatch at audit_id {$auditId}: stored hash chain is invalid.\n";
        echo "Stored prev: {$storedPrev}\nExpected prev: {$prevHash}\n";
        echo "Stored hash: {$hash}\nExpected hash: {$expected}\n";
        $failed++;
        break;
    }

    $prevHash = $hash;
    $checked++;
}

if ($failed === 0) {
    echo "Audit hash chain OK. Checked rows: {$checked}\n";
}

$mysqli->close();
exit($failed === 0 ? 0 : 1);
