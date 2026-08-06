<?php

namespace App\Commands;

use App\Models\DashboardModel;
use App\Models\ReportModel;
use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PerformanceSmoke extends BaseCommand
{
    protected $group = 'Performance';
    protected $name = 'performance:smoke';
    protected $description = 'Measures critical read paths and fails when the local P1 latency budget is exceeded.';

    public function run(array $params)
    {
        $username = $params[0] ?? 'admin_verztec';
        $budget = max(100, (int) (CLI::getOption('budget-ms') ?: 1500));
        $user = (new UserModel())->builder()->select('lms_usp.*,lms_emp.*,lms_company.*,lms_usp_gp.*')
            ->join('lms_emp', 'lms_emp.emp_id=lms_usp.emp_id')
            ->join('lms_company', 'lms_company.com_id=lms_emp.com_id')
            ->join('lms_usp_gp', 'lms_usp_gp.ug_id=lms_usp.ug_id')
            ->where(['lms_usp.useri' => $username, 'lms_usp.u_isDelete' => '0'])->get()->getRowArray();
        if (! $user) {
            CLI::error('Performance user not found.');
            return EXIT_ERROR;
        }
        $checks = [
            'dashboard' => fn () => (new DashboardModel())->summaryForUser($user, $user['lang_last'] ?: 'english'),
            'learner-report-100' => fn () => (new ReportModel())->learnerRows($user, $user['lang_last'] ?: 'english', [], 100),
            'scorm-report-100' => fn () => (new ReportModel())->scormSummaryRows($user, $user['lang_last'] ?: 'english', [], 100),
        ];
        $failed = false;
        foreach ($checks as $label => $check) {
            $samples = [];
            for ($run = 0; $run < 3; $run++) {
                $started = microtime(true); $check(); $samples[] = (microtime(true) - $started) * 1000;
            }
            sort($samples); $ms = $samples[1];
            CLI::write(sprintf('%s median: %.1f ms (samples %.1f/%.1f/%.1f, budget %d ms)', $label, $ms, $samples[0], $samples[1], $samples[2], $budget), $ms <= $budget ? 'green' : 'red');
            $failed = $failed || $ms > $budget;
        }
        if (CLI::getOption('profile') !== null) {
            $dashboard = new DashboardModel();
            foreach (['companyAnalytics' => [], 'courseTotal' => [$user], 'courseStatusOverview' => [$user], 'publicSurveys' => [$user, $user['lang_last'] ?: 'english'], 'learnerCourses' => [$user, $user['lang_last'] ?: 'english']] as $method => $arguments) {
                $started = microtime(true);
                $dashboard->{$method}(...$arguments);
                CLI::write(sprintf('  %s: %.1f ms', $method, (microtime(true) - $started) * 1000));
            }
        }
        return $failed ? EXIT_ERROR : EXIT_SUCCESS;
    }
}
