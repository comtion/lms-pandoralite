<?php
namespace App\Commands;
use App\Libraries\BackupService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
class BackupVerify extends BaseCommand
{
    protected $group='P0 Operations';
    protected $name='backup:verify';
    protected $description='Verify and record an existing backup artifact.';
    protected $usage='backup:verify <absolute-dump-path>';
    public function run(array $params)
    {
        if (empty($params[0])) {
            CLI::error('An absolute dump path is required.');
            return EXIT_ERROR;
        }
        $result=(new BackupService())->verify($params[0]);
        CLI::write('Backup verified: '.$result['checksum'], 'green');
        return EXIT_SUCCESS;
    }
}
