<?php

namespace App\Database\MySQLi;

use Config\Services;
use Throwable;

class Connection extends \CodeIgniter\Database\MySQLi\Connection
{
    public $DBDriver = 'MySQLi';

    private bool $auditEnabled = true;
    private ?bool $auditTableReady = null;

    private array $excludedTables = [
        'lms_audit_logs',
        'lms_lg',
        'lms_lg_import',
        'lms_lg_import_detail',
    ];

    public function query(string $sql, $binds = null, bool $setEscapeFlags = true, string $queryClass = '')
    {
        $auditSql = $this->interpolateSql($sql, $binds);
        $shouldAudit = $this->auditEnabled && $this->isWriteSql($auditSql);
        $table = $shouldAudit ? $this->tableFromSql($auditSql) : null;
        $oldValues = null;
        $newValues = null;

        if ($shouldAudit && ! $this->shouldSkipTable($table)) {
            $oldValues = $this->rawOldValues($auditSql);
            $newValues = $this->rawNewValues($auditSql);
        }

        $result = parent::query($sql, $binds, $setEscapeFlags, $queryClass);

        if ($shouldAudit && $result !== false && ! $this->shouldSkipTable($table)) {
            $action = $this->actionFromSql($auditSql);
            if (($action === 'insert' || $action === 'replace') && is_array($newValues)) {
                $newValues = $this->withGeneratedInsertId($newValues);
            }

            $this->writeAudit([
                'audit_action'         => $action . '_ci4',
                'audit_table'          => $table,
                'audit_row_key'        => $this->rowsKey($oldValues ?? $newValues),
                'audit_old_values'     => $this->json($oldValues),
                'audit_new_values'     => $this->json($newValues),
                'audit_changed_values' => $this->json($this->changes($oldValues, $newValues)),
                'audit_sql'            => $auditSql,
            ]);
        }

        return $result;
    }

    private function writeAudit(array $payload): void
    {
        if (! $this->auditTableExists()) {
            return;
        }

        $context = $this->context();
        $data = array_merge($payload, [
            'audit_controller'   => $context['controller'],
            'audit_method'       => $context['method'],
            'audit_uri'          => $context['uri'],
            'audit_http_method'  => $context['http_method'],
            'audit_ip'           => $context['ip'],
            'audit_user_agent'   => $context['user_agent'],
            'audit_user_id'      => $context['user_id'],
            'audit_emp_id'       => $context['emp_id'],
            'audit_com_id'       => $context['com_id'],
            'audit_username'     => $context['username'],
            'audit_user_display' => $context['user_display'],
            'audit_created_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->insertAuditWithSeparateConnection($data);
    }

    private function insertAuditWithSeparateConnection(array $data): void
    {
        $mysqli = $this->newAuditMysqli();
        if ($mysqli === null) {
            return;
        }
        $chain = $this->auditHashChain($mysqli, $data);
        $data['audit_prev_hash'] = $chain['prev_hash'];
        $data['audit_hash'] = $chain['hash'];

        $fields = [];
        $values = [];
        foreach ($data as $field => $value) {
            $fields[] = '`' . str_replace('`', '``', $field) . '`';
            $values[] = $value === null ? 'NULL' : "'" . $mysqli->real_escape_string((string) $value) . "'";
        }

        $sql = 'INSERT INTO `lms_audit_logs` (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        if (! $mysqli->query($sql)) {
            log_message('error', 'Audit log insert failed: {message}', ['message' => $mysqli->error]);
        }
        $mysqli->close();
    }

    private function auditHashChain(\mysqli $mysqli, array $data): array
    {
        $prevHash = null;
        $result = $mysqli->query('SELECT audit_hash FROM `lms_audit_logs` ORDER BY audit_id DESC LIMIT 1');
        if ($result !== false && ($row = $result->fetch_assoc())) {
            $prevHash = $row['audit_hash'];
        }

        ksort($data);
        $hash = hash('sha256', (string) $prevHash . '|' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return ['prev_hash' => $prevHash, 'hash' => $hash];
    }

    private function newAuditMysqli(): ?\mysqli
    {
        $hostname = $this->hostname;
        $port = $this->port ?: ini_get('mysqli.default_port');
        if (str_contains($hostname, ':')) {
            [$hostname, $port] = explode(':', $hostname, 2);
        }

        $mysqli = @new \mysqli($hostname, $this->username, $this->password, $this->database, (int) $port);
        if ($mysqli->connect_errno) {
            log_message('error', 'Audit log connection failed: {message}', ['message' => $mysqli->connect_error]);
            return null;
        }

        $mysqli->set_charset($this->charset);
        return $mysqli;
    }

    private function auditTableExists(): bool
    {
        if ($this->auditTableReady !== null) {
            return $this->auditTableReady;
        }

        $mysqli = $this->newAuditMysqli();
        if ($mysqli === null) {
            $this->auditTableReady = false;
            return false;
        }
        $result = $mysqli->query("SHOW TABLES LIKE 'lms_audit_logs'");
        $this->auditTableReady = $result !== false && $result->num_rows > 0;
        $mysqli->close();

        return $this->auditTableReady;
    }

    private function rawOldValues(string $sql): ?array
    {
        $table = $this->tableFromSql($sql);
        if ($this->shouldSkipTable($table)) {
            return null;
        }

        $where = null;
        if (preg_match('/^\s*UPDATE\s+`?[a-zA-Z0-9_]+`?\s+SET\s+.+?\s+WHERE\s+(.+)$/is', $sql, $matches) === 1) {
            $where = $this->stripOrderLimit($matches[1]);
        } elseif (preg_match('/^\s*DELETE\s+FROM\s+`?[a-zA-Z0-9_]+`?\s+WHERE\s+(.+)$/is', $sql, $matches) === 1) {
            $where = $this->stripOrderLimit($matches[1]);
        } elseif (preg_match('/^\s*TRUNCATE\s+/i', $sql) === 1) {
            return $this->snapshotAllRows($table);
        }

        if ($where === null || trim($where) === '') {
            return null;
        }

        $query = $this->withoutAudit(
            fn () => parent::query('SELECT * FROM ' . $this->escapeIdentifiers($table) . ' WHERE ' . $where)
        );

        return $query !== false ? $query->getResultArray() : null;
    }

    private function rawNewValues(string $sql): ?array
    {
        if (preg_match('/^\s*UPDATE\s+`?[a-zA-Z0-9_]+`?\s+SET\s+(.+?)\s+WHERE\s+/is', $sql, $matches) === 1) {
            return $this->parseAssignments($matches[1]);
        }

        if (preg_match('/^\s*INSERT\s+INTO\s+`?[a-zA-Z0-9_]+`?\s*\((.+?)\)\s*VALUES\s*\((.+?)\)/is', $sql, $matches) === 1) {
            $fields = $this->splitSqlList($matches[1]);
            $values = $this->splitSqlList($matches[2]);
            $row = [];
            foreach ($fields as $index => $field) {
                $field = trim(str_replace('`', '', $field));
                $row[$field] = isset($values[$index]) ? $this->sqlLiteralToValue(trim($values[$index])) : null;
            }

            return $row;
        }

        return null;
    }

    private function snapshotAllRows(string $table): array
    {
        $query = $this->withoutAudit(fn () => parent::query('SELECT * FROM ' . $this->escapeIdentifiers($table)));

        return $query !== false ? $query->getResultArray() : [];
    }

    private function parseAssignments(string $setSql): array
    {
        $data = [];
        foreach ($this->splitSqlList($setSql) as $part) {
            if (! str_contains($part, '=')) {
                continue;
            }
            [$field, $value] = explode('=', $part, 2);
            $field = trim(str_replace('`', '', $field));
            $data[$field] = $this->sqlLiteralToValue(trim($value));
        }

        return $data;
    }

    private function changes($old, $new): array
    {
        if ($old === null) {
            return ['before' => null, 'after' => $new];
        }
        if ($new === null) {
            return ['before' => $old, 'after' => null];
        }

        $rows = array_is_list($old) ? $old : [$old];
        $changes = [];
        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                continue;
            }
            foreach ($new as $field => $value) {
                if (! array_key_exists($field, $row) || (string) $row[$field] !== (string) $value) {
                    $changes[$index][$field] = [
                        'from' => $row[$field] ?? null,
                        'to'   => $value,
                    ];
                }
            }
        }

        return $changes;
    }

    private function withoutAudit(callable $callback)
    {
        $enabled = $this->auditEnabled;
        $this->auditEnabled = false;

        try {
            return $callback();
        } finally {
            $this->auditEnabled = $enabled;
        }
    }

    private function isWriteSql(string $sql): bool
    {
        return preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql) === 1;
    }

    private function actionFromSql(string $sql): string
    {
        preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql, $matches);

        return isset($matches[1]) ? strtolower($matches[1]) : 'query';
    }

    private function tableFromSql(string $sql): ?string
    {
        foreach ([
            '/^\s*INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*REPLACE\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*UPDATE\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*DELETE\s+FROM\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*TRUNCATE\s+`?([a-zA-Z0-9_]+)`?/i',
        ] as $pattern) {
            if (preg_match($pattern, $sql, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    private function shouldSkipTable(?string $table): bool
    {
        return $table === null || $table === '' || in_array($table, $this->excludedTables, true);
    }

    private function splitSqlList(string $sql): array
    {
        $items = [];
        $current = '';
        $quote = null;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            if ($quote !== null) {
                $current .= $char;
                if ($char === $quote && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $quote = null;
                }
                continue;
            }
            if ($char === "'" || $char === '"') {
                $quote = $char;
                $current .= $char;
                continue;
            }
            if ($char === ',') {
                $items[] = trim($current);
                $current = '';
                continue;
            }
            $current .= $char;
        }

        if (trim($current) !== '') {
            $items[] = trim($current);
        }

        return $items;
    }

    private function sqlLiteralToValue(string $value)
    {
        if (strtoupper($value) === 'NULL') {
            return null;
        }
        if (strlen($value) >= 2 && $value[0] === "'" && substr($value, -1) === "'") {
            return str_replace("\\'", "'", substr($value, 1, -1));
        }

        return $value;
    }

    private function withGeneratedInsertId(array $row): array
    {
        if (isset($row['id'])) {
            return $row;
        }

        $insertId = $this->insertID();
        if ($insertId === 0 || $insertId === '0' || $insertId === null) {
            return $row;
        }

        return array_merge(['id' => $insertId], $row);
    }

    private function stripOrderLimit(string $where): string
    {
        $where = preg_replace('/\s+ORDER\s+BY\s+.+$/is', '', $where);
        $where = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?\s*$/is', '', $where);

        return trim($where);
    }

    private function interpolateSql(string $sql, $binds): string
    {
        if (empty($binds)) {
            return $sql;
        }
        if (! is_array($binds)) {
            $binds = [$binds];
        }
        foreach ($binds as $key => $bind) {
            if (is_array($bind)) {
                $bind = $bind[0] ?? null;
            }
            $escaped = $this->escape($bind);
            if (is_string($key)) {
                $sql = str_replace(':' . $key . ':', $escaped, $sql);
            } else {
                $sql = preg_replace('/\?/', $escaped, $sql, 1);
            }
        }

        return $sql;
    }

    private function json($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($this->maskSensitive($value), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function maskSensitive($value, string $keyName = '')
    {
        if (is_array($value)) {
            $masked = [];
            foreach ($value as $key => $item) {
                $masked[$key] = $this->maskSensitive($item, (string) $key);
            }

            return $masked;
        }
        if (is_object($value)) {
            return $this->maskSensitive(get_object_vars($value), $keyName);
        }
        if ($keyName !== '' && preg_match('/(pass|password|userp|token|secret|csrf|session)/i', $keyName) === 1) {
            return '[masked]';
        }

        return $value;
    }

    private function rowsKey($rows): ?string
    {
        if (! is_array($rows) || $rows === []) {
            return null;
        }

        if (! array_is_list($rows)) {
            $rows = [$rows];
        }

        $keys = ['id', 'u_id', 'emp_id', 'cos_id', 'qcode', 'scode'];
        $output = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            foreach ($keys as $key) {
                if (isset($row[$key])) {
                    $output[] = $key . '=' . $row[$key];
                    break;
                }
            }
        }

        return $output === [] ? null : implode(',', $output);
    }

    private function context(): array
    {
        try {
            $request = Services::request(null, false);
            $router = Services::router(null, false);
            $session = session();
            $user = $session->get('user');
            $user = is_array($user) ? $user : [];

            return [
                'controller'   => $router ? $router->controllerName() : null,
                'method'       => $router ? $router->methodName() : null,
                'uri'          => $request ? (string) $request->getUri() : null,
                'http_method'  => $request ? $request->getMethod() : null,
                'ip'           => $request ? $request->getIPAddress() : null,
                'user_agent'   => $request ? (string) $request->getUserAgent() : null,
                'user_id'      => $user['u_id'] ?? null,
                'emp_id'       => $user['emp_id'] ?? null,
                'com_id'       => $user['com_id'] ?? null,
                'username'     => $user['useri'] ?? ($user['emp_c'] ?? null),
                'user_display' => $user['fullname_th'] ?? ($user['fullname_en'] ?? null),
            ];
        } catch (Throwable $e) {
            return [
                'controller' => null,
                'method' => null,
                'uri' => null,
                'http_method' => null,
                'ip' => null,
                'user_agent' => null,
                'user_id' => null,
                'emp_id' => null,
                'com_id' => null,
                'username' => null,
                'user_display' => null,
            ];
        }
    }
}
