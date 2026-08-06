<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;
class MigrationInventory extends BaseConfig
{
    public array $modules = [
        'authentication' => ['status'=>'native','owner'=>'Auth','evidence'=>'Login, logout, profile, password, MFA and OIDC'],
        'dashboard' => ['status'=>'native','owner'=>'Dashboard','evidence'=>'Summary, approvals, notifications and profile'],
        'users-organizations' => ['status'=>'native','owner'=>'ModulePortal','evidence'=>'User/company/department CRUD and bulk import'],
        'course-authoring' => ['status'=>'native','owner'=>'CoursePortal','evidence'=>'Courses, groups, lessons, media, documents and SCORM'],
        'learner-runtime' => ['status'=>'native','owner'=>'CoursePortal','evidence'=>'Catalog, enrollment, progress and completion'],
        'quiz' => ['status'=>'native','owner'=>'QuizPortal','evidence'=>'Authoring, import, attempt and grading'],
        'survey' => ['status'=>'native','owner'=>'SurveyPortal','evidence'=>'Authoring, response, report and export'],
        'certificates' => ['status'=>'native','owner'=>'CertificatePortal','evidence'=>'Listing, download, single and bulk regeneration'],
        'reports' => ['status'=>'native','owner'=>'ReportPortal','evidence'=>'Learner, course, SCORM, certificate, survey and log exports'],
        'advanced-scorm-reporting' => ['status'=>'native','owner'=>'ReportPortal','evidence'=>'Aggregated status, score, location, time, raw values and export'],
        'legacy-menu-modules' => ['status'=>'native','owner'=>'AdminModule','evidence'=>'Explicit routes, CRUD, uploads, question builders, logs and exports'],
        'operations' => ['status'=>'native','owner'=>'Operations','evidence'=>'Health, backup history and persistent queue'],
    ];
}
