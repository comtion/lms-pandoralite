<?php

namespace App\Controllers;

use App\Libraries\ParityService;
use App\Models\PermissionModel;

class Health extends BaseController
{
    public function ready()
    {
        $ok = false;
        try {
            $db = db_connect();
            $ok = $db->query('SELECT 1')->getRow() !== null
                && $db->tableExists('lms_jobs')
                && $db->tableExists('lms_user_mfa');
        } catch (\Throwable) {
            $ok = false;
        }
        return $this->response->setStatusCode($ok ? 200 : 503)->setJSON([
            'status' => $ok ? 'ready' : 'unavailable',
            'timestamp' => gmdate('c'),
        ]);
    }

    public function details()
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'manage/userdata')) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'forbidden']);
        }
        $db = db_connect();
        $started = microtime(true);
        $db->query('SELECT 1');
        $parity = (new ParityService())->report();
        return $this->response->setJSON([
            'status' => 'ready',
            'environment' => ENVIRONMENT,
            'php' => PHP_VERSION,
            'database_ms' => round((microtime(true) - $started) * 1000, 2),
            'route_parity' => [$parity['explicit'], $parity['total']],
            'queue' => [
                'pending' => $db->table('lms_jobs')->where('status', 'pending')->countAllResults(),
                'failed' => $db->table('lms_jobs')->where('status', 'failed')->countAllResults(),
            ],
            'latest_backup' => $db->table('lms_backup_runs')->orderBy('backup_id', 'DESC')->get(1)->getRowArray(),
        ]);
    }
}
