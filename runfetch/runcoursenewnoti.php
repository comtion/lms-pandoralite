<?php

date_default_timezone_set('Asia/Bangkok');

$spark = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ci4-app' . DIRECTORY_SEPARATOR . 'spark';
$php = PHP_BINARY ?: 'php';
$limit = isset($argv[1]) ? max(1, (int) $argv[1]) : 25;
$recipientLimit = isset($argv[2]) ? max(1, (int) $argv[2]) : 250;

if (! is_file($spark)) {
    fwrite(STDERR, "CI4 spark file not found: {$spark}\n");
    exit(1);
}

$command = escapeshellarg($php) . ' ' . escapeshellarg($spark) . ' course-notifications:dispatch ' . $limit . ' ' . $recipientLimit;
passthru($command, $exitCode);
exit((int) $exitCode);
