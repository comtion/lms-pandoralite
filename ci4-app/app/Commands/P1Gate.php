<?php

namespace App\Commands;

use App\Models\DashboardModel;
use App\Models\ReportModel;
use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class P1Gate extends BaseCommand
{
    protected $group = 'Operations';
    protected $name = 'p1:gate';
    protected $description = 'Validates P1 workflows, health, audit integrity and local performance budgets.';

    public function run(array $params)
    {
        $failures = [];
        $db = db_connect();
        foreach (['lms_course_lifecycle', 'lms_course_lifecycle_history', 'lms_enrollment_policies', 'lms_enrollment_requests', 'lms_enrollment_waitlist', 'lms_notifications', 'lms_notification_preferences', 'lms_audit_logs'] as $table) {
            $this->check($db->tableExists($table), "Table {$table}", $failures);
        }
        $routes = (string) file_get_contents(APPPATH . 'Config/Routes.php');
        foreach (['health/ready', 'notifications/preferences', '/lifecycle', 'enrollment/requests', "get('workflows'"] as $route) {
            $this->check(str_contains($routes, $route), "Route {$route}", $failures);
        }
        $this->check($this->auditValid($db), 'Audit hash chain integrity', $failures);

        $username = $params[0] ?? 'admin_verztec';
        $user = (new UserModel())->builder()->select('lms_usp.*,lms_emp.*,lms_company.*,lms_usp_gp.*')
            ->join('lms_emp', 'lms_emp.emp_id=lms_usp.emp_id')->join('lms_company', 'lms_company.com_id=lms_emp.com_id')
            ->join('lms_usp_gp', 'lms_usp_gp.ug_id=lms_usp.ug_id')->where(['lms_usp.useri' => $username, 'lms_usp.u_isDelete' => '0'])->get()->getRowArray();
        if (! $user) {
            $failures[] = 'Performance user';
        } else {
            foreach ([
                'Dashboard' => fn () => (new DashboardModel())->summaryForUser($user, $user['lang_last'] ?: 'english'),
                'Learner report' => fn () => (new ReportModel())->learnerRows($user, $user['lang_last'] ?: 'english', [], 100),
                'SCORM report' => fn () => (new ReportModel())->scormSummaryRows($user, $user['lang_last'] ?: 'english', [], 100),
            ] as $label => $work) {
                $samples = [];
                for ($run = 0; $run < 3; $run++) {
                    $started = microtime(true); $work(); $samples[] = (microtime(true) - $started) * 1000;
                }
                sort($samples); $ms = $samples[1];
                $this->check($ms <= 1500, sprintf('%s median %.1f ms', $label, $ms), $failures);
            }
        }
        if ($failures !== []) {
            CLI::error(count($failures) . ' P1 gate check(s) failed.');
            return EXIT_ERROR;
        }
        CLI::write('P1 gate passed.', 'green');
        return EXIT_SUCCESS;
    }

    private function auditValid($db): bool
    {
        $previous = null;
        foreach ($db->table('lms_audit_logs')->orderBy('audit_id', 'ASC')->get()->getResultArray() as $row) {
            $storedPrevious = $row['audit_prev_hash']; $storedHash = $row['audit_hash'];
            unset($row['audit_id'], $row['audit_prev_hash'], $row['audit_hash']); ksort($row);
            $expected = hash('sha256', (string) $previous . '|' . json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            if ((string) $storedPrevious !== (string) $previous || ! hash_equals((string) $storedHash, $expected)) return false;
            $previous = $storedHash;
        }
        return true;
    }

    private function check(bool $passed, string $label, array &$failures): void
    {
        $passed ? CLI::write('PASS ' . $label, 'green') : CLI::error('FAIL ' . $label);
        if (! $passed) $failures[] = $label;
    }
}
