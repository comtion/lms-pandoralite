<?php

namespace App\Libraries;

use CodeIgniter\Database\Query;
use Config\Services;
use Throwable;

class AuditLogger
{
    private static bool $recording = false;
    private static ?bool $tableReady = null;

    private const EXCLUDED_TABLES = [
        'lms_audit_logs',
        'lms_lg',
        'lms_lg_import',
        'lms_lg_import_detail',
    ];

    public static function recordDbQuery(Query $query): void
    {
        if (self::$recording) {
            return;
        }

        $sql = trim((string) $query);
        if (! self::isWriteSql($sql)) {
            return;
        }

        $table = self::tableFromSql($sql);
        if ($table === null || in_array($table, self::EXCLUDED_TABLES, true)) {
            return;
        }

        self::$recording = true;

        try {
            $db = db_connect();
            if (! self::auditTableExists($db)) {
                return;
            }

            $context = self::context();
            $db->table('lms_audit_logs')->insert([
                'audit_action'         => self::actionFromSql($sql) . '_ci4',
                'audit_table'          => $table,
                'audit_row_key'        => null,
                'audit_old_values'     => null,
                'audit_new_values'     => null,
                'audit_changed_values' => null,
                'audit_sql'            => $sql,
                'audit_controller'     => $context['controller'],
                'audit_method'         => $context['method'],
                'audit_uri'            => $context['uri'],
                'audit_http_method'    => $context['http_method'],
                'audit_ip'             => $context['ip'],
                'audit_user_agent'     => $context['user_agent'],
                'audit_user_id'        => $context['user_id'],
                'audit_emp_id'         => $context['emp_id'],
                'audit_username'       => $context['username'],
                'audit_user_display'   => $context['user_display'],
                'audit_created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Audit log failed: {message}', ['message' => $e->getMessage()]);
        } finally {
            self::$recording = false;
        }
    }

    private static function auditTableExists($db): bool
    {
        if (self::$tableReady !== null) {
            return self::$tableReady;
        }

        self::$tableReady = $db->tableExists('lms_audit_logs');

        return self::$tableReady;
    }

    private static function isWriteSql(string $sql): bool
    {
        return preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql) === 1;
    }

    private static function actionFromSql(string $sql): string
    {
        preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql, $matches);

        return isset($matches[1]) ? strtolower($matches[1]) : 'query';
    }

    private static function tableFromSql(string $sql): ?string
    {
        $patterns = [
            '/^\s*INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*REPLACE\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*UPDATE\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*DELETE\s+FROM\s+`?([a-zA-Z0-9_]+)`?/i',
            '/^\s*TRUNCATE\s+`?([a-zA-Z0-9_]+)`?/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $sql, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    private static function context(): array
    {
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
            'username'     => $user['useri'] ?? ($user['emp_c'] ?? null),
            'user_display' => $user['fullname_th'] ?? ($user['fullname_en'] ?? null),
        ];
    }
}
