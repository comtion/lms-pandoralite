<?php

namespace App\Commands;

use App\Models\DashboardModel;
use App\Models\PermissionModel;
use App\Models\UserModel;
use App\Models\PublicSurveyModel;
use App\Models\ReportModel;
use App\Libraries\ParityService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrationSmoke extends BaseCommand
{
    protected $group = 'Migration';
    protected $name = 'migration:smoke';
    protected $description = 'Checks the CI4 migration data layer against the existing LMS database.';

    public function run(array $params)
    {
        $username = $params[0] ?? 'admin_verztec';

        $userModel = new UserModel();
        $row = $userModel->builder()
            ->select('lms_usp.*, lms_emp.*, lms_company.*, lms_usp_gp.*')
            ->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id')
            ->join('lms_company', 'lms_emp.com_id = lms_company.com_id')
            ->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id')
            ->where('lms_usp.useri', $username)
            ->where('lms_emp.status', '1')
            ->where('lms_emp.emp_isDelete', '0')
            ->where('lms_usp.u_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $row) {
            CLI::error('No active LMS user found for ' . $username);
            return EXIT_ERROR;
        }

        $dashboard = (new DashboardModel())->summaryForUser($row, $row['lang_last'] ?: 'english');
        $permissions = new PermissionModel();
        $allowed = $permissions->allowedPagePaths($row);
        $menus = $permissions->menuTree($row, $row['lang_last'] ?: 'english');

        CLI::write('User: ' . $row['useri'], 'green');
        CLI::write('Dashboard course_total: ' . $dashboard['course_total']);
        CLI::write('Dashboard enroll: ' . $dashboard['enroll']);
        CLI::write('Dashboard course_select: ' . count($dashboard['course_select']));
        CLI::write('Approval courses: ' . count($dashboard['approval_courses']));
        CLI::write('Approval surveys: ' . count($dashboard['approval_surveys']));
        CLI::write('Approval course groups: ' . count($dashboard['approval_course_groups']));
        CLI::write('Public surveys: ' . count($dashboard['public_surveys']));
        CLI::write('Allowed pages: ' . count($allowed));
        if (CLI::getOption('list')) {
            foreach ($allowed as $path) {
                CLI::write(' - ' . $path);
            }
        }
        CLI::write('Top-level menus: ' . count($menus));
        $parity = (new ParityService())->report();
        $publicSurveys = (new PublicSurveyModel())->available($row, $row['lang_last'] ?: 'english');
        $scormSummary = (new ReportModel())->scormSummaryRows($row, $row['lang_last'] ?: 'english', [], 10);
        CLI::write('Explicit menu parity: ' . $parity['explicit'] . '/' . $parity['total']);
        CLI::write('Available public surveys: ' . count($publicSurveys));
        CLI::write('SCORM summary sample: ' . count($scormSummary));
        if ($parity['missing'] !== []) {
            CLI::error('CI4 menu parity is incomplete.');
            return EXIT_ERROR;
        }

        return EXIT_SUCCESS;
    }
}
