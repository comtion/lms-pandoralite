# CodeIgniter 4 Migration Status

## Current Status

- P1 localhost acceptance is implemented: lifecycle/enrollment workflows, notification preferences, readiness endpoints, audit-chain verification, accessibility baseline, and automated 1.5-second critical-query budgets. See `docs/CI4_P1_STATUS.md`.
- Production menu parity is now 47/47 explicit CI4 routes (100%) for `admin_verztec`, enforced by `php spark migration:parity`.
- Former read-only gaps now have native CI4 workflows for public surveys, questionnaire/public-survey question builders, settings CRUD, protected asset uploads, log/audit drill-down and exports, advanced SCORM reporting, and bulk certificate regeneration.

- A fresh CodeIgniter 4 application has been created at `ci4-app`.
- The installed framework version is CodeIgniter `4.7.3`.
- The CI4 app has been configured with the same database connection used by the existing CI3 app.
- Core runtime packages used by the LMS have been added to the CI4 app:
  - `phpoffice/phpspreadsheet`
  - `mpdf/mpdf`
  - `phpmailer/phpmailer`
- Authentication/session foundation has been started in CI4:
  - `ci4-app/app/Models/UserModel.php`
  - `ci4-app/app/Libraries/AuthService.php`
  - `ci4-app/app/Filters/AuthFilter.php`
  - `ci4-app/app/Controllers/Auth.php`
  - `ci4-app/app/Controllers/Dashboard.php`
  - `ci4-app/app/Views/auth/login.php`
  - `ci4-app/app/Views/dashboard/index.php`
- Dashboard summary data migration has been started in CI4:
  - `ci4-app/app/Models/DashboardModel.php`
  - Course totals, enroll counts, in-process counts, success counts, not-started counts, device usage, company course list, course status rings, company analytics, branding, and banner data now use CI4 Query Builder.
  - The admin/learner dashboard switch now stays on `/dashboard` and toggles the visible dashboard sections in-page, matching the CI3 dashboard flow instead of redirecting users to the course page.
  - Learner dashboard data now reuses the migrated course enrollment data for ongoing, not-started, and completed course summaries inside the dashboard.
  - `dashboard/profile/setting` now renders a CI4 profile edit form for the current user and saves basic `lms_emp` profile fields.
  - `dashboard/change_pass` now renders a CI4 change-password form and updates `lms_usp.userp` using the existing SHA-256 password format after checking the current password.
  - CI3 dashboard support paths now resolve in CI4: `dashboard/profile`, `dashboard/profile/certificate`, `dashboard/logout`, `home/change_lang/{thai|english|japan}`, `home/faq`, and `home/privacy_policy`.
  - Known migrated module paths configured in `ModuleModel` can now render through CI4 even when the legacy menu permission table does not expose the path directly.
  - Legacy dashboard links from the CI3 reference dashboard were rechecked against CI4; all 49 internal paths now return HTTP 200/redirect-success instead of 404/500 for `admin_verztec`.
- Dashboard permission/menu migration has been started in CI4:
  - `ci4-app/app/Models/PermissionModel.php`
  - User-level page permissions now read from `lms_role_usp` and `lms_menu`.
  - Main menu and nested submenu data now come from `lms_menu`.
- Dashboard approval/survey widget data has been started in CI4:
  - Pending course approvals now read from `lms_cog`, `lms_cosincg`, and `lms_cos`.
  - Pending survey approvals now read from `lms_sv` and `lms_svde`.
  - Pending course group approvals now read from `lms_cog`.
  - Public surveys now read from `lms_sv`, `lms_sv_tc`, and `lms_sv_pm`.
  - Dashboard approvers can now approve or reject pending courses, public surveys, and course groups individually or in bulk.
  - Rejection reasons are mandatory; decisions use row locks and transactions, write legacy-compatible approval history, enforce edit permission plus assigned-approver/company scope, and notify owners in-app and by configured email template.
  - Approval history is visible on the dashboard, future course/survey notification jobs are scheduled without duplicates, and course notification schedules resume after approval.
  - Global CSRF, invalid-character filtering, and secure response headers are enabled; all CI4 POST forms now emit CSRF tokens (the SCORM data-model endpoint remains explicitly exempt for package compatibility).
- The former pending fallback has been replaced for all 47 permitted production-menu paths:
  - `ci4-app/app/Controllers/ModulePortal.php`
  - `ci4-app/app/Models/ModuleModel.php`
  - `ci4-app/app/Views/modules/index.php`
  - Every inventoried menu path has an explicit CI4 route and native workflow; parity is enforced by an automated release gate.
- User administration actions have been started in CI4:
  - `ci4-app/app/Models/UserAdminModel.php`
  - `manage/userdata` now has a dedicated CI4 user list using joined employee, login, company, and group data.
  - `manage/userdata/create` can create both `lms_emp` and `lms_usp` records with duplicate checks and a temporary password.
  - `manage/userdata/{userId}/edit` and `manage/userdata/{userId}/update` can edit employee profile and login account data.
  - `manage/userdata/{userId}/status` can activate/deactivate employee/login status without hard deletion.
  - `dashboard/unlockAcc/{userId}` can unlock a locked account by setting `login=1` and clearing `u_lockdate`.
  - `dashboard/resetPass/{userId}` can reset a password, set `firsttime=1`, extend `expiredate`, and show a temporary password to the admin.
  - `dashboard/resetPass/{userId}` now attempts reset-password email delivery through `lms_setting_mail` and the active `lms_sendmail_form` template type `2`, and records delivery results in `lms_lg_email`.
  - `manage/userdata` now provides a downloadable XLSX bulk-user template, dry-run validation, and all-or-nothing import for up to 1,000 new users with duplicate, company, department, group, and password checks.
  - Admins can bulk enroll up to 1,000 employee codes into a company-scoped course. Safe unenrollment is limited to learners who have not started and uses soft deletion with a cancellation reason.
- Organization master data actions have been started in CI4:
  - `ci4-app/app/Models/OrganizationModel.php`
  - `manage/companydata/create` can create a company and seed default course types.
  - `manage/companydata/{companyId}/edit` and `manage/companydata/{companyId}/update` can edit company master data.
  - `manage/companydata/{companyId}/status` can activate/deactivate a company.
  - `manage/departmentdata/create` can create a department under an active company.
  - `manage/departmentdata/{departmentId}/edit` and `manage/departmentdata/{departmentId}/update` can edit department master data.
  - `manage/departmentdata/{departmentId}/status` can activate/deactivate a department.
  - Destructive hard delete is intentionally excluded from the product behavior; archival/status changes preserve auditability and organization history.
- Course feature migration has been started in CI4:
  - `ci4-app/app/Controllers/CoursePortal.php`
  - `ci4-app/app/Models/CourseModel.php`
  - `ci4-app/app/Views/courses/index.php`
  - The following paths now render real CI4 pages instead of the pending fallback:
    - `manage_courses`
    - `managecourse/course_groups`
  - `managecourse/courses_all`
  - `course`
  - `coursemain/all_courses`
  - `coursemain/my_course`
  - `coursemain/detail/{courseId}`
  - `coursemain/enroll/{courseId}`
  - `coursemain/start/{courseId}`
  - `coursemain/lesson/{lessonId}`
  - `coursemain/lesson/{lessonId}/complete`
  - `coursemain/quiz/{quizId}`
  - `coursemain/quiz/{quizId}/submit`
  - `scorm/load/{scormId}`
  - `scorm/datamodel/{scormId}`
  - `managecourse/courses_all/create`
  - `managecourse/courses_all/{courseId}/edit`
  - `managecourse/courses_all/{courseId}/update`
  - `managecourse/courses_all/{courseId}/status`
  - `managecourse/courses_all/{courseId}/groups/update`
  - `managecourse/courses_all/{courseId}/lessons/create`
  - `managecourse/lessons/{lessonId}/edit`
  - `managecourse/lessons/{lessonId}/update`
  - `managecourse/lessons/{lessonId}/status`
  - `managecourse/lessons/{lessonId}/media/create`
  - `managecourse/media/{mediaId}/update`
  - `managecourse/lessons/{lessonId}/documents/create`
  - `managecourse/documents/{documentId}/update`
  - `managecourse/lessons/{lessonId}/scorm/save`
  - Course detail now shows course metadata, enrollment status, lessons, media/document counts, SCORM availability, tests, surveys, and course documents.
  - Learner flow has started: users can enroll/start a course, open a lesson player page, and mark a lesson complete. This writes to the existing `lms_cos_enroll`, `lms_log_enroll`, `lms_les_tc`, and `lms_med_tc` tables.
  - Course master data can now be created, edited, activated, and deactivated in CI4.
  - Course group management now supports the legacy one-page workflow in CI4: admins can create, edit in a Bootstrap modal, activate/deactivate, approve/reject, and archive course groups from `managecourse/course_groups`, with popup feedback after actions.
  - Course group assignment can now be updated from the CI4 course edit screen using active groups from the course company.
  - Lesson master data can now be created, edited, activated, and deactivated in CI4.
  - Lesson media, lesson document, and lesson SCORM path records can now be created or updated from the CI4 lesson edit screen.
  - Physical upload for lesson media files, media thumbnails, lesson documents, and SCORM ZIP package paths is now available from the CI4 lesson edit screen.
  - SCORM ZIP upload now extracts packages into the existing `uploads/scorm/scorm_{lessonId}_{scormId}` folder pattern, detects launch files from `imsmanifest.xml` or common index files, and serves packages through a CI4 SCORM player.
  - The CI4 SCORM player exposes basic SCORM 1.2 and 2004 JavaScript APIs and writes key/value tracking data to `lms_scm_val` using the legacy-compatible variable names.
  - Quiz runtime has started in CI4: learners can open a quiz from course detail, answer multiple-choice/text questions, submit an attempt, and write results to `lms_qiz_tc` and `lms_ques_tc`.
  - Quiz authoring, import, and subjective grading have started in CI4: authorized admins can open `managecourse/quizzes`, create/edit course quizzes, add multiple-choice or text questions, import questions from XLSX/XLS/CSV using a downloadable template, archive questions, open grading for text answers, update manual scores, and recalculate quiz attempt totals.
  - Course survey runtime has started in CI4: learners can open a course survey from course detail, answer 1-5 rating questions, add suggestions, and write results to `lms_qn_user` and `lms_qn_user_de`.
  - Course survey authoring/reporting has started in CI4: authorized admins can open `managecourse/surveys`, create/edit course surveys, add/update/archive survey questions, activate/deactivate surveys, view rating summaries, and export survey reports to XLSX.
  - Certificate listing has started in CI4: authorized users can open `certificate/certificateall`, review certificate records from `lms_certificate`, download certificate PDFs, and regenerate missing certificate files.
  - Course completion recalculation has started in CI4: lesson completion, quiz submission, and survey submission now recalculate `lms_cos_enroll` score/status and can create certificates through CI4.
  - Learner report export has started in CI4: authorized users can open `report/learnerReport`, filter core learning history, and export XLSX through PhpSpreadsheet.
  - Specialized report exports have started in CI4: `report/courseSummary/export`, `report/scormTracking/export`, and `report/certificateIssued/export` generate XLSX files using the same company/course/date filters from the learner report screen.
  - Advanced SCORM reporting, bulk certificate regeneration, and inventoried niche reports now have dedicated CI4 routes. Destructive hard delete remains an intentional non-feature.
- A migration status page has been added:
  - `ci4-app/app/Controllers/MigrationStatus.php`
  - `ci4-app/app/Views/migration/status.php`
- Migration smoke testing has been added:
  - `ci4-app/app/Commands/MigrationSmoke.php`

## Important Constraint

CodeIgniter 3 cannot be upgraded in place by only changing Composer packages. CodeIgniter 4 is a framework rewrite and is not backward compatible with CI3. The existing controllers, models, views, routes, libraries, sessions, and database calls must be converted into the CI4 structure.

## Existing CI3 Surface Area

- Controllers: 134 files in `application/controllers`
- Models: 47 files in `application/models`
- Views: 219 files in `application/views`

## Migration Rules

- Controllers move from `application/controllers` to `ci4-app/app/Controllers`.
- Models move from `application/models` to `ci4-app/app/Models`.
- Views move from `application/views` to `ci4-app/app/Views`.
- CI3 controller classes using `extends CI_Controller` must become namespaced CI4 controllers using `extends BaseController`.
- CI3 model classes using `extends CI_Model` must become namespaced CI4 models using `extends CodeIgniter\Model`.
- `$this->load->view(...)` must become `return view(...)` or `echo view(...)`.
- `$this->load->model(...)`, `$this->load->library(...)`, and `$this->load->helper(...)` must be replaced with CI4 services, factories, namespaces, helpers, or explicit object construction.
- `$this->db` usage must be converted to CI4 database services or CI4 models.
- Routes should be explicitly defined in `ci4-app/app/Config/Routes.php`.
- Public assets should be served from `ci4-app/public` or the web server should be configured to use `ci4-app/public` as the document root.

## Recommended Migration Order

1. Authentication and session flow. Started.
2. Dashboard and shared layout. Started with real summary data, branding/banner assets, permission/menu data, company analytics, course status rings, and read-only approval/survey widgets.
3. Course catalog, course detail, enrollment, and learning player. Started with course group/course list/my course pages, CI4 course detail, enroll/start, lesson player, and lesson complete tracking.
4. Quiz, survey, SCORM, certificates, and reports. Started for quiz runtime, course survey runtime, SCORM playback/tracking, certificate listing/download/regeneration, completion recalculation, and learner report export.
5. Admin management screens and settings.
6. UI modernization after each migrated module is stable.

## Completed Verification

- `composer validate --strict` passes in `ci4-app`.
- `php spark namespaces` passes in `ci4-app`.
- `php spark routes` exposes `/`, `/login`, `/logout`, protected `/dashboard`, `/migration/status`, and 47/47 explicit production-menu routes.
- `php spark db:table lms_usp` can connect to the existing LMS database.
- `php spark db:table lms_emp` can connect to the existing LMS database.
- `spark serve` returns HTTP 200 for `/login`.
- `spark serve` returns HTTP 302 from `/dashboard` to `/login` when no user session exists.
- Dashboard summary source tables exist and contain data:
  - `lms_cos_detail`
  - `lms_cos_detail_ug`
  - `lms_cos_enroll`
  - `lms_lg`
- `php spark migration:smoke admin_verztec` passes:
  - Dashboard summary query runs against the existing database.
  - Permission query returns allowed pages.
  - Menu tree query returns top-level menus.
  - Approval and survey widget queries run against the existing database.
  - Allowed menu coverage returns 47 feature paths for `admin_verztec`.
  - Explicit route parity returns 47/47.
  - Public-survey and SCORM summary read paths execute successfully.
- `php spark release:gate` passes all deterministic local checks: route parity, P0 tables, database latency, and upload execution protection.
- Browser E2E confirms the login form, CSRF field, unauthenticated dashboard redirect, and zero console errors.

## Current Local URLs

- CI4 LMS: `http://localhost:8184/lms-pandoralite/ci4-app/public/login`
- Existing project on port 8081 remains untouched: `http://localhost:8081/`

## Next Migration Step

CI4 production-menu functional parity is complete. Production cutover is gated only by environment-specific controls: HTTPS/base URL, secure cookies, real OIDC credentials, administrator MFA enrollment/enforcement, production acceptance, and the scheduled cutover/rollback decision.

## Verification Commands

Run these commands from the Docker host:

```powershell
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark list
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark routes
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark migration:parity
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark migration:smoke admin_verztec
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark release:gate
docker exec -w /var/www/html/lms-pandoralite/ci4-app php84-apache php spark release:gate --production
```
