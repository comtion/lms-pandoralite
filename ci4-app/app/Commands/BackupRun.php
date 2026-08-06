<?php

namespace App\Commands;

use App\Libraries\BackupService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BackupRun extends BaseCommand
{
    protected $group = 'P0 Operations';
    protected $name = 'backup:run';
    protected $description = 'Create, verify, checksum and prune database backups.';

    public function run(array $params)
    {
        $service = new BackupService();
        $backup = $service->create();
        $service->verify($backup['artifact_path']);
        $uploads = $service->createUploads();
        $pruned = $service->prune();
        CLI::write("Verified {$backup['artifact_path']} ({$backup['checksum']}) and {$uploads['artifact_path']}; pruned {$pruned}.", 'green');
    }
}
