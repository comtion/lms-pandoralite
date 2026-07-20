<?php
date_default_timezone_set('Asia/Bangkok');

$days = isset($argv[1]) ? (int) $argv[1] : 365;
$dryRun = in_array('--dry-run', $argv, true);
if ($days < 30) {
    echo "Retention must be at least 30 days.\n";
    exit(1);
}

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

$cutoff = date('Y-m-d H:i:s', strtotime('-'.$days.' days'));
$archiveDir = __DIR__ . DIRECTORY_SEPARATOR . 'audit_archive';
if (!is_dir($archiveDir)) {
    mkdir($archiveDir, 0775, true);
}

$countResult = $mysqli->query("SELECT COUNT(*) AS total FROM lms_audit_logs WHERE audit_created_at < '".$mysqli->real_escape_string($cutoff)."'");
$countRow = $countResult ? $countResult->fetch_assoc() : array('total' => 0);
$total = (int) $countRow['total'];
echo "Rows older than {$cutoff}: {$total}\n";

if ($total === 0 || $dryRun) {
    $mysqli->close();
    exit(0);
}

$file = $archiveDir . DIRECTORY_SEPARATOR . 'audit_logs_before_' . date('Ymd_His') . '.csv';
$fp = fopen($file, 'w');
fwrite($fp, "\xEF\xBB\xBF");

$query = $mysqli->query("SELECT * FROM lms_audit_logs WHERE audit_created_at < '".$mysqli->real_escape_string($cutoff)."' ORDER BY audit_id ASC");
$headersWritten = false;
while ($row = $query->fetch_assoc()) {
    if (!$headersWritten) {
        fputcsv($fp, array_keys($row));
        $headersWritten = true;
    }
    fputcsv($fp, $row);
}
fclose($fp);

$mysqli->begin_transaction();
try {
    $delete = $mysqli->query("DELETE FROM lms_audit_logs WHERE audit_created_at < '".$mysqli->real_escape_string($cutoff)."'");
    if (!$delete) {
        throw new RuntimeException($mysqli->error);
    }
    $mysqli->commit();
    echo "Archived to {$file}\n";
    echo "Deleted rows: {$mysqli->affected_rows}\n";
} catch (Throwable $e) {
    $mysqli->rollback();
    echo "Archive delete failed: {$e->getMessage()}\n";
    exit(1);
} finally {
    $mysqli->close();
}
