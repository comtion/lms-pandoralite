<?php
namespace App\Commands;
use App\Libraries\ParityService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
class MigrationParity extends BaseCommand
{
    protected $group='Migration';protected $name='migration:parity';protected $description='Fail unless every production menu path has an explicit CI4 route.';
    public function run(array $params){$r=(new ParityService())->report();CLI::write("Explicit routes: {$r['explicit']}/{$r['total']} ({$r['coverage_percent']}%)",$r['missing']?'yellow':'green');foreach($r['missing'] as $p)CLI::error("Missing: {$p}");return $r['missing']?EXIT_ERROR:EXIT_SUCCESS;}
}
