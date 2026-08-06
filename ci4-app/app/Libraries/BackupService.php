<?php

namespace App\Libraries;

class BackupService
{
    public function create(): array
    {
        $config = config('Database')->default;
        $dir = rtrim(config('P0')->backupDirectory, '/\\');
        if (! is_dir($dir) && ! mkdir($dir, 0750, true) && ! is_dir($dir)) {
            throw new \RuntimeException('Cannot create backup directory.');
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'database_' . date('Ymd_His') . '.sql';
        $this->nativeDump($path);
        return $this->record('database', 'success', $path, ['database' => $config['database']]);
    }

    public function createUploads(): array
    {
        $source = realpath(dirname(ROOTPATH) . DIRECTORY_SEPARATOR . 'uploads');
        if (! $source) {
            throw new \RuntimeException('Uploads directory not found.');
        }
        $dir = rtrim(config('P0')->backupDirectory, '/\\');
        if (! is_dir($dir) && ! mkdir($dir, 0750, true) && ! is_dir($dir)) {
            throw new \RuntimeException('Cannot create backup directory.');
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'uploads_' . date('Ymd_His') . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::EXCL) !== true) {
            throw new \RuntimeException('Cannot create uploads archive.');
        }
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $zip->addFile($file->getPathname(), substr($file->getPathname(), strlen($source) + 1));
            }
        }
        $zip->close();
        return $this->record('uploads', 'success', $path, ['source' => $source]);
    }

    public function verify(string $path): array
    {
        $resolved = realpath($path);
        $root = realpath(rtrim(config('P0')->backupDirectory, '/\\'));
        if (! $resolved || ! $root || ! str_starts_with($resolved, $root . DIRECTORY_SEPARATOR) || ! is_file($resolved)) {
            throw new \InvalidArgumentException('Backup path is outside the configured directory.');
        }
        $head = file_get_contents($resolved, false, null, 0, 2048);
        if (filesize($resolved) < 128 || ! str_contains((string) $head, 'MySQL')) {
            throw new \RuntimeException('Backup artifact is empty or not a MySQL dump.');
        }
        return $this->record('verify', 'success', $resolved, []);
    }

    public function prune(): int
    {
        $cutoff = time() - config('P0')->backupRetentionDays * 86400;
        $count = 0;
        foreach (glob(rtrim(config('P0')->backupDirectory, '/\\') . DIRECTORY_SEPARATOR . '*_*.*') ?: [] as $path) {
            if (is_file($path) && filemtime($path) < $cutoff) {
                unlink($path);
                $count++;
            }
        }
        return $count;
    }

    public function restoreRehearsal(string $path): array
    {
        $this->verify($path);
        $config = config('Database')->default;
        $temporary = 'p0_restore_' . bin2hex(random_bytes(6));
        if (! preg_match('/^p0_restore_[a-f0-9]{12}$/', $temporary)) {
            throw new \RuntimeException('Unsafe rehearsal database name.');
        }
        $admin = db_connect();
        foreach ($admin->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name LIKE 'p0_restore_%'")->getResultArray() as $schema) {
            if (preg_match('/^p0_restore_[a-f0-9]{12}$/', $schema['schema_name'])) {
                $admin->query("DROP DATABASE `{$schema['schema_name']}`");
            }
        }
        $admin->query("CREATE DATABASE `{$temporary}` CHARACTER SET utf8mb4");
        try {
            $mysqli = new \mysqli($config['hostname'], $config['username'], $config['password'], $temporary, (int) $config['port']);
            $mysqli->query("SET SESSION sql_mode=''");
            $stream = fopen($path, 'rb');
            if (! $stream) {
                throw new \RuntimeException('Cannot open dump for restore.');
            }
            $statement = '';
            while (($line = fgets($stream)) !== false) {
                if ($statement === '' && (trim($line) === '' || str_starts_with(ltrim($line), '--'))) {
                    continue;
                }
                $statement .= $line;
                if (str_ends_with(rtrim($line), ';')) {
                    try {
                        $ok = $mysqli->query($statement);
                    } catch (\mysqli_sql_exception $error) {
                        fclose($stream);
                        throw new \RuntimeException('Restore statement failed: ' . $error->getMessage());
                    }
                    if (! $ok) {
                        fclose($stream);
                        throw new \RuntimeException('Restore statement failed: ' . $mysqli->error);
                    }
                    $statement = '';
                }
            }
            fclose($stream);
            $result = $mysqli->query("SELECT COUNT(*) total FROM information_schema.tables WHERE table_schema='{$temporary}'")->fetch_assoc();
            $mysqli->close();
            if ((int) $result['total'] < 1) {
                throw new \RuntimeException('Restore rehearsal produced no tables.');
            }
            return $this->record('restore-rehearsal', 'success', realpath($path), ['temporary_database' => $temporary, 'tables' => (int) $result['total']]);
        } finally {
            $admin->query("DROP DATABASE IF EXISTS `{$temporary}`");
        }
    }

    private function nativeDump(string $path): void
    {
        $db = db_connect();
        $stream = fopen($path, 'wb');
        if (! $stream) {
            throw new \RuntimeException('Cannot create database dump.');
        }
        fwrite($stream, "-- MySQL dump generated by PandoraLite\nSET FOREIGN_KEY_CHECKS=0;\n");
        foreach ($db->listTables() as $table) {
            $safe = str_replace('`', '``', $table);
            $create = $db->query("SHOW CREATE TABLE `{$safe}`")->getRowArray();
            $definition = array_values($create)[1] ?? null;
            if (! $definition) {
                continue;
            }
            fwrite($stream, "\nDROP TABLE IF EXISTS `{$safe}`;\n{$definition};\n");
            $query = $db->query("SELECT * FROM `{$safe}`");
            $batch = [];
            $columns = '';
            while ($row = $query->getUnbufferedRow('array')) {
                $columns = $columns ?: implode(',', array_map(static fn($column) => '`' . str_replace('`', '``', $column) . '`', array_keys($row)));
                $values = implode(',', array_map(static fn($value) => $value === null ? 'NULL' : $db->escape($value), array_values($row)));
                $batch[] = "({$values})";
                if (count($batch) >= 250) {
                    fwrite($stream, "INSERT INTO `{$safe}` ({$columns}) VALUES " . implode(',', $batch) . ";\n");
                    $batch = [];
                }
            }
            if ($batch !== []) {
                fwrite($stream, "INSERT INTO `{$safe}` ({$columns}) VALUES " . implode(',', $batch) . ";\n");
            }
            $query->freeResult();
        }
        fwrite($stream, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($stream);
    }

    private function processToFile(array $command, string $output, array $environment): void
    {
        $pipes = [];
        $process = proc_open($command, [['pipe', 'r'], ['file', $output, 'w'], ['pipe', 'w']], $pipes, null, $environment + $_ENV);
        if (! is_resource($process)) {
            throw new \RuntimeException('Unable to start mysqldump.');
        }
        fclose($pipes[0]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            @unlink($output);
            throw new \RuntimeException('mysqldump failed: ' . trim($error));
        }
    }

    private function runProcess(array $command, ?string $inputFile = null, array $environment = []): void
    {
        $pipes = [];
        $stdin = $inputFile ? ['file', $inputFile, 'r'] : ['pipe', 'r'];
        $process = proc_open($command, [$stdin, ['pipe', 'w'], ['pipe', 'w']], $pipes, null, $environment + $_ENV);
        if (! is_resource($process)) {
            throw new \RuntimeException('Unable to start database command.');
        }
        if (! $inputFile) {
            fclose($pipes[0]);
        }
        $stdout = stream_get_contents($pipes[1]); fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]); fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new \RuntimeException('Database command failed: ' . trim($stderr ?: $stdout));
        }
    }

    private function captureProcess(array $command, array $environment): string
    {
        $pipes = [];
        $process = proc_open($command, [['pipe','r'],['pipe','w'],['pipe','w']], $pipes, null, $environment + $_ENV);
        if (! is_resource($process)) {
            throw new \RuntimeException('Unable to start database query.');
        }
        fclose($pipes[0]); $output = stream_get_contents($pipes[1]); fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]); fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new \RuntimeException('Database query failed: ' . trim($error));
        }
        return $output;
    }

    private function record(string $kind, string $status, string $path, array $details): array
    {
        $row = [
            'backup_type' => $kind, 'status' => $status === 'success' ? 'completed' : $status, 'file_path' => $path,
            'checksum_sha256' => hash_file('sha256', $path), 'file_size' => filesize($path),
            'message' => json_encode($details), 'started_at' => date('Y-m-d H:i:s'), 'finished_at' => date('Y-m-d H:i:s'),
        ];
        db_connect()->table('lms_backup_runs')->insert($row);
        return $row + [
            'artifact_path' => $row['file_path'],
            'checksum' => $row['checksum_sha256'],
            'details' => $row['message'],
        ];
    }
}
