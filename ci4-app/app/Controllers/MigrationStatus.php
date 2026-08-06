<?php

namespace App\Controllers;

class MigrationStatus extends BaseController
{
    public function index()
    {
        $statusPath = dirname(ROOTPATH) . DIRECTORY_SEPARATOR . 'CI4_MIGRATION_STATUS.md';
        $content = is_file($statusPath) ? file_get_contents($statusPath) : 'Migration status file not found.';

        return view('migration/status', [
            'content' => $content,
            'inventory' => (new \App\Libraries\MigrationInventoryService())->report(),
        ]);
    }

    public function inventory()
    {
        return $this->response->setJSON((new \App\Libraries\MigrationInventoryService())->report());
    }
}
