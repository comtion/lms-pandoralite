<?php

namespace App\Commands;

use App\Libraries\ParityService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\P0;

class ReleaseGate extends BaseCommand
{
    protected $group = 'Operations';
    protected $name = 'release:gate';
    protected $description = 'Runs deterministic CI4 release-readiness checks; add --production for production-only controls.';

    public function run(array $params)
    {
        $production = CLI::getOption('production') !== null;
        $failures = [];
        $warnings = [];
        $started = microtime(true);

        $parity = (new ParityService())->report();
        $this->check($parity['missing'] === [], "Explicit route parity {$parity['explicit']}/{$parity['total']}", $failures);

        $db = db_connect();
        foreach (['lms_user_mfa', 'lms_oidc_identity', 'lms_jobs', 'lms_backup_runs'] as $table) {
            $this->check($db->tableExists($table), "Database table {$table}", $failures);
        }
        try {
            $before = microtime(true);
            $db->query('SELECT 1')->getRow();
            $elapsed = (microtime(true) - $before) * 1000;
            $this->check($elapsed < 1000, sprintf('Database round trip %.1f ms (<1000 ms)', $elapsed), $failures);
        } catch (\Throwable $e) {
            $failures[] = 'Database connectivity: ' . $e->getMessage();
            CLI::error('FAIL Database connectivity');
        }

        $uploadGuard = ROOTPATH . '../uploads/admin/.htaccess';
        $this->check(is_file($uploadGuard) && str_contains((string) file_get_contents($uploadGuard), 'RemoveHandler'), 'Upload execution guard', $failures);

        $p0 = config(P0::class);
        if ($production) {
            $this->check(ENVIRONMENT === 'production', 'CI_ENVIRONMENT is production', $failures);
            $baseUrl = (string) config('App')->baseURL;
            $this->check(str_starts_with(strtolower($baseUrl), 'https://'), 'HTTPS base URL', $failures);
            $this->check((bool) config('Cookie')->secure, 'Secure cookies', $failures);
            $this->check($p0->mfaRequiredForAdmins, 'Admin MFA enforcement', $failures);
            $oidcConfigured = $p0->oidcDiscoveryUrl !== '' && $p0->oidcClientId !== '' && $p0->oidcClientSecret !== '';
            $this->check($oidcConfigured, 'OIDC provider credentials', $failures);
        } else {
            if (! $p0->mfaRequiredForAdmins) {
                $warnings[] = 'Admin MFA is not enforced in this environment.';
            }
            if ($p0->oidcDiscoveryUrl === '' || $p0->oidcClientId === '' || $p0->oidcClientSecret === '') {
                $warnings[] = 'OIDC provider credentials are not configured in this environment.';
            }
        }

        foreach ($warnings as $warning) {
            CLI::write('WARN ' . $warning, 'yellow');
        }
        CLI::write(sprintf('Release gate completed in %.2f s', microtime(true) - $started));
        if ($failures !== []) {
            CLI::error(count($failures) . ' release gate check(s) failed.');
            return EXIT_ERROR;
        }
        CLI::write('Release gate passed.', 'green');
        return EXIT_SUCCESS;
    }

    private function check(bool $passed, string $label, array &$failures): void
    {
        if ($passed) {
            CLI::write('PASS ' . $label, 'green');
            return;
        }
        $failures[] = $label;
        CLI::error('FAIL ' . $label);
    }
}
