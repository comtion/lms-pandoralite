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

$stmt = $mysqli->prepare('UPDATE lms_audit_logs SET audit_prev_hash = ?, audit_hash = ? WHERE audit_id = ?');
if (!$stmt) {
    echo "Prepare failed: {$mysqli->error}\n";
    exit(1);
}

$prevHash = null;
$updated = 0;
while ($row = $result->fetch_assoc()) {
    $auditId = (int) $row['audit_id'];
    unset($row['audit_id'], $row['audit_prev_hash'], $row['audit_hash']);
    ksort($row);

    $hash = hash('sha256', (string) $prevHash . '|' . json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    $stmt->bind_param('ssi', $prevHash, $hash, $auditId);
    if (!$stmt->execute()) {
        echo "Update failed at audit_id {$auditId}: {$stmt->error}\n";
        exit(1);
    }

    $prevHash = $hash;
    $updated++;
}

$stmt->close();
$mysqli->close();

echo "Audit hash chain backfilled. Updated rows: {$updated}\n";
