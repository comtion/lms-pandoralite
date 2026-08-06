<?php
namespace App\Commands;
use App\Libraries\BackupService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
class BackupRestoreRehearsal extends BaseCommand
{
    protected $group='P0 Operations';
    protected $name='backup:restore-rehearsal';
    protected $description='Restore a dump into an isolated random database, validate it, then remove it.';
    protected $usage='backup:restore-rehearsal <absolute-dump-path> --confirm';
    public function run(array $params)
    {
        if (! CLI::getOption('confirm') || empty($params[0])) {
            CLI::error('An absolute dump path and --confirm are required.');
            return EXIT_ERROR;
        }
        $result=(new BackupService())->restoreRehearsal($params[0]);
        CLI::write('Restore rehearsal passed with '.$result['details'], 'green');
        return EXIT_SUCCESS;
    }
}
