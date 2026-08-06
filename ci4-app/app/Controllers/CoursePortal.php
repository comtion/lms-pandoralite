<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CourseNotificationModel;
use App\Models\PermissionModel;

class CoursePortal extends BaseController
{
    public function manageHome()
    {
        return $this->renderCoursePage('manage_courses', 'admin', 'managecourse/courses_all');
    }

    public function learningHome()
    {
        return $this->renderCoursePage('course', 'all', 'coursemain/all_courses');
    }

    public function courseGroups()
    {
        return $this->renderCoursePage('managecourse/course_groups', 'groups');
    }

    public function storeCourseGroup()
    {
        return $this->courseGroupWrite(null);
    }

    public function updateCourseGroup($groupId)
    {
        return $this->courseGroupWrite((int) $groupId);
    }

    public function courseGroupStatus($groupId)
    {
        $context = $this->courseGroupContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new CourseModel())->setCourseGroupStatus((int) $groupId, (int) $this->request->getPost('status'), $context['user']);

        return redirect()->to(site_url('managecourse/course_groups'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function courseGroupApproval($groupId)
    {
        $context = $this->courseGroupContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new CourseModel())->setCourseGroupApproval(
            (int) $groupId,
            (int) $this->request->getPost('approval'),
            (string) $this->request->getPost('cg_reject'),
            $context['user']
        );

        return redirect()->to(site_url('managecourse/course_groups'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function archiveCourseGroup($groupId)
    {
        $context = $this->courseGroupContext('ru_del');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new CourseModel())->archiveCourseGroup((int) $groupId, $context['user']);

        return redirect()->to(site_url('managecourse/course_groups'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function manageCourses()
    {
        return $this->renderCoursePage('managecourse/courses_all', 'admin');
    }

    public function createCourse()
    {
        return $this->courseForm(null);
    }

    public function storeCourse()
    {
        return $this->courseWrite(null);
    }

    public function editCourse($courseId)
    {
        return $this->courseForm((int) $courseId);
    }

    public function updateCourse($courseId)
    {
        return $this->courseWrite((int) $courseId);
    }

    public function courseStatus($courseId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_edit')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No edit permission.');
        }

        $result = (new CourseModel())->setCourseStatus((int) $courseId, (int) $this->request->getPost('status'), $user);

        return redirect()
            ->to(site_url('managecourse/courses_all'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function updateCourseGroups($courseId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_edit')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No edit permission.');
        }

        $groups = $this->request->getPost('cg_id') ?? [];
        if (! is_array($groups)) {
            $groups = [$groups];
        }

        $result = (new CourseModel())->syncCourseGroups((int) $courseId, $groups, $user);

        return redirect()
            ->to(site_url('managecourse/courses_all/' . (int) $courseId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function audienceOptions()
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Login required.']);
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_view')) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'No permission.']);
        }

        $companyId = (int) $this->request->getGet('com_id');
        $courses = new CourseModel();

        return $this->response->setJSON([
            'ok' => true,
            'departments' => $courses->departments($companyId),
            'learners' => $courses->activeLearners($companyId),
        ]);
    }

    public function notificationLogs()
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_view')) {
            return redirect()->to(site_url('dashboard'));
        }

        $model = new CourseNotificationModel();
        $scheduleId = (int) $this->request->getGet('schedule_id');
        $filters = [
            'course_id' => (int) $this->request->getGet('course_id'),
            'status' => trim((string) $this->request->getGet('status')),
            'channel' => trim((string) $this->request->getGet('channel')),
            'log_status' => trim((string) $this->request->getGet('log_status')),
            'date_from' => trim((string) $this->request->getGet('date_from')),
            'date_to' => trim((string) $this->request->getGet('date_to')),
        ];
        $page = max(1, (int) $this->request->getGet('page'));
        $perPage = 25;
        $logPage = max(1, (int) $this->request->getGet('log_page'));
        $logPerPage = 100;

        return view('courses/notification_logs', [
            'path' => 'managecourse/course_notifications',
            'title' => 'Course Notification Logs',
            'title_main' => $permissions->parentMenuTitle('managecourse/courses_all', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'filters' => $filters,
            'page' => $page,
            'perPage' => $perPage,
            'totalSchedules' => $model->scheduleCount($filters),
            'schedules' => $model->schedulesWithLogSummary($filters, $perPage, ($page - 1) * $perPage),
            'selectedScheduleId' => $scheduleId,
            'logPage' => $logPage,
            'logPerPage' => $logPerPage,
            'totalLogs' => $scheduleId > 0 ? $model->logCount($scheduleId, $filters) : 0,
            'logs' => $scheduleId > 0 ? $model->logsForSchedule($scheduleId, $filters, $logPerPage, ($logPage - 1) * $logPerPage) : [],
        ]);
    }

    public function retryNotificationSchedule($scheduleId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_edit')) {
            return redirect()->to(site_url('managecourse/course_notifications'))->with('course_error', 'No edit permission.');
        }

        $result = (new CourseNotificationModel())->retrySchedule((int) $scheduleId);

        return redirect()
            ->to(site_url('managecourse/course_notifications?schedule_id=' . (int) $scheduleId))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function createLesson($courseId)
    {
        return $this->lessonForm((int) $courseId, null);
    }

    public function storeLesson($courseId)
    {
        return $this->lessonWrite((int) $courseId, null);
    }

    public function editLesson($lessonId)
    {
        $courses = new CourseModel();
        $lesson = $courses->lessonForEdit((int) $lessonId);
        if (! $lesson) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lesson ' . $lessonId);
        }

        return $this->lessonForm((int) $lesson['cos_id'], (int) $lessonId);
    }

    public function updateLesson($lessonId)
    {
        return $this->lessonWrite(null, (int) $lessonId);
    }

    public function lessonStatus($lessonId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_edit')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No edit permission.');
        }

        $result = (new CourseModel())->setLessonStatus((int) $lessonId, (int) $this->request->getPost('status'), $user);
        $courseId = $result['course_id'] ?? 0;

        return redirect()
            ->to(site_url('managecourse/courses_all/' . $courseId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function saveLessonMedia($lessonId)
    {
        return $this->lessonAssetWrite((int) $lessonId, null, 'media');
    }

    public function updateLessonMedia($mediaId)
    {
        return $this->lessonAssetWrite(null, (int) $mediaId, 'media');
    }

    public function saveLessonDocument($lessonId)
    {
        return $this->lessonAssetWrite((int) $lessonId, null, 'document');
    }

    public function updateLessonDocument($documentId)
    {
        return $this->lessonAssetWrite(null, (int) $documentId, 'document');
    }

    public function saveLessonScorm($lessonId)
    {
        return $this->lessonAssetWrite((int) $lessonId, null, 'scorm');
    }

    public function allCourses()
    {
        return $this->renderCoursePage('coursemain/all_courses', 'all');
    }

    public function myCourse()
    {
        return $this->renderCoursePage('coursemain/my_course', 'my');
    }

    public function detail($courseId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'coursemain/all_courses') && ! $permissions->can($user, 'coursemain/my_course')) {
            return redirect()->to(site_url('dashboard'));
        }

        $courses = new CourseModel();
        $course = $courses->courseDetail((int) $courseId, $user, $lang);

        if (! $course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Course ' . $courseId);
        }

        return view('courses/detail', [
            'path' => 'coursemain/detail/' . (int) $courseId,
            'title' => $course['title'],
            'title_main' => $permissions->menuTitle('course', $lang) ?: 'Learning',
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'course' => $course,
        ]);
    }

    public function enroll($courseId)
    {
        return $this->courseAction((int) $courseId, 'enroll');
    }

    public function start($courseId)
    {
        return $this->courseAction((int) $courseId, 'start');
    }

    public function lesson($lessonId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'coursemain/my_course') && ! $permissions->can($user, 'coursemain/all_courses')) {
            return redirect()->to(site_url('dashboard'));
        }

        $courses = new CourseModel();
        $lesson = $courses->lessonDetail((int) $lessonId, $user, $lang);

        if (! $lesson) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lesson ' . $lessonId);
        }

        return view('courses/lesson', [
            'path' => 'coursemain/lesson/' . (int) $lessonId,
            'title' => $lesson['title'],
            'title_main' => $lesson['course']['title'] ?? 'Course',
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'lesson' => $lesson,
        ]);
    }

    public function completeLesson($lessonId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $result = (new CourseModel())->markLessonComplete((int) $lessonId, $user, $lang);

        return redirect()
            ->to(site_url('coursemain/lesson/' . (int) $lessonId))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function courseAction(int $courseId, string $action)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'coursemain/all_courses') && ! $permissions->can($user, 'coursemain/my_course')) {
            return redirect()->to(site_url('dashboard'));
        }

        $courses = new CourseModel();
        $hasPolicy = db_connect()->table('lms_enrollment_policies')->where('cos_id', $courseId)->countAllResults() > 0;
        $hasEnrollment = db_connect()->table('lms_cos_enroll')->where(['cos_id' => $courseId, 'emp_id' => (int) $user['emp_id'], 'cosen_isDelete' => '0'])->countAllResults() > 0;
        if ($action === 'start' && $hasPolicy && ! $hasEnrollment) {
            $workflow = (new \App\Models\EnrollmentWorkflowModel())->request($courseId, $user, (int) $user['emp_id']);
            if (! $workflow['ok'] || $workflow['status'] !== 'approved') {
                $result = ['ok' => $workflow['ok'], 'message' => $workflow['ok'] ? ($workflow['status'] === 'waitlisted' ? 'Course is full. You have joined the waitlist.' : 'Enrollment request submitted for approval.') : $workflow['message']];
            } else {
                $result = $courses->startCourse($courseId, $user, $lang);
            }
        } elseif ($action === 'start') {
            $result = $courses->startCourse($courseId, $user, $lang);
        } elseif ($hasPolicy) {
            $workflow = (new \App\Models\EnrollmentWorkflowModel())->request($courseId, $user, (int) $user['emp_id']);
            $result = [
                'ok' => $workflow['ok'],
                'message' => $workflow['ok'] ? match ($workflow['status']) {
                    'approved' => 'Enrolled',
                    'waitlisted' => 'Course is full. You have joined the waitlist.',
                    default => 'Enrollment request submitted for approval.',
                } : $workflow['message'],
            ];
        } else {
            $result = $courses->enrollCourse($courseId, $user, $lang);
        }

        return redirect()
            ->to(site_url('coursemain/detail/' . $courseId))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function courseForm(?int $courseId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', $courseId ? 'ru_edit' : 'ru_add')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No permission for this action.');
        }

        $courses = new CourseModel();
        $course = $courseId ? $courses->courseForEdit($courseId) : null;
        if ($courseId && ! $course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Course ' . $courseId);
        }
        $companyId = $course ? (int) $course['com_id'] : null;
        $notificationModel = new CourseNotificationModel();

        return view('courses/form', [
            'course' => $course,
            'companies' => $courses->companies(),
            'types' => $courses->courseTypes($companyId),
            'departments' => $courses->departments($companyId),
            'learners' => $courses->activeLearners($companyId),
            'courseNotification' => $course ? $notificationModel->scheduleForCourse((int) $course['cos_id']) : null,
            'courseGroups' => $course ? $courses->assignableCourseGroups((int) $course['com_id'], $lang) : [],
            'selectedCourseGroups' => $course ? $courses->courseGroupIds((int) $course['cos_id']) : [],
            'lessons' => $course ? $courses->lessonsForCourse((int) $course['cos_id'], $lang) : [],
            'path' => 'managecourse/courses_all',
            'title' => $courseId ? 'Edit Course' : 'Create Course',
            'title_main' => $permissions->parentMenuTitle('managecourse/courses_all', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }

    private function courseWrite(?int $courseId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', $courseId ? 'ru_edit' : 'ru_add')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No permission for this action.');
        }

        $courses = new CourseModel();
        $result = $courseId
            ? $courses->updateCourse($courseId, $this->request->getPost(), $user)
            : $courses->createCourse($this->request->getPost(), $user);

        $target = $result['ok'] && ! empty($result['course_id'])
            ? site_url('managecourse/courses_all/' . $result['course_id'] . '/edit')
            : ($courseId ? site_url('managecourse/courses_all/' . $courseId . '/edit') : site_url('managecourse/courses_all/create'));

        return redirect()
            ->to($result['ok'] ? site_url('managecourse/courses_all') : $target)
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function courseGroupWrite(?int $groupId)
    {
        $context = $this->courseGroupContext($groupId ? 'ru_edit' : 'ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $model = new CourseModel();
        $result = $groupId
            ? $model->updateCourseGroup($groupId, $this->request->getPost(), $context['user'])
            : $model->createCourseGroup($this->request->getPost(), $context['user']);

        return redirect()->to(site_url('managecourse/course_groups'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function courseGroupContext(string $field = 'ru_view')
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/course_groups', $field)) {
            return redirect()->to(site_url('managecourse/course_groups'))->with('course_error', 'No permission for this action.');
        }

        return ['user' => $user, 'permissions' => $permissions];
    }

    private function lessonForm(int $courseId, ?int $lessonId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', $lessonId ? 'ru_edit' : 'ru_add')) {
            return redirect()->to(site_url('managecourse/courses_all/' . $courseId . '/edit'))->with('course_error', 'No permission for this action.');
        }

        $courses = new CourseModel();
        $course = $courses->courseForEdit($courseId);
        $lesson = $lessonId ? $courses->lessonForEdit($lessonId) : null;
        if (! $course || ($lessonId && ! $lesson)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lesson form');
        }

        return view('courses/lesson_form', [
            'course' => $course,
            'lesson' => $lesson,
            'mediaItems' => $lessonId ? $courses->mediaForLesson($lessonId, $lang) : [],
            'documentItems' => $lessonId ? $courses->documentsForLesson($lessonId, $lang) : [],
            'scormItem' => $lessonId ? $courses->scormForLesson($lessonId) : null,
            'path' => 'managecourse/courses_all',
            'title' => $lessonId ? 'Edit Lesson' : 'Create Lesson',
            'title_main' => $permissions->parentMenuTitle('managecourse/courses_all', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }

    private function lessonWrite(?int $courseId, ?int $lessonId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', $lessonId ? 'ru_edit' : 'ru_add')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No permission for this action.');
        }

        $courses = new CourseModel();
        $result = $lessonId
            ? $courses->updateLesson($lessonId, $this->request->getPost(), $user)
            : $courses->createLesson((int) $courseId, $this->request->getPost(), $user);

        $targetCourseId = $courseId ?: ($result['course_id'] ?? 0);

        return redirect()
            ->to($result['ok'] ? site_url('managecourse/courses_all/' . $targetCourseId . '/edit') : current_url())
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function lessonAssetWrite(?int $lessonId, ?int $assetId, string $type)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', 'ru_edit')) {
            return redirect()->to(site_url('managecourse/courses_all'))->with('course_error', 'No edit permission.');
        }

        $courses = new CourseModel();
        $post = $this->assetPost($type);
        $result = match ($type) {
            'media' => $assetId
                ? $courses->updateLessonMedia($assetId, $post, $user)
                : $courses->createLessonMedia((int) $lessonId, $post, $user),
            'document' => $assetId
                ? $courses->updateLessonDocument($assetId, $post, $user)
                : $courses->createLessonDocument((int) $lessonId, $post, $user),
            default => $courses->saveLessonScorm((int) $lessonId, $post, $user),
        };

        $targetLessonId = $lessonId ?: ($result['lesson_id'] ?? 0);
        $target = $targetLessonId
            ? site_url('managecourse/lessons/' . $targetLessonId . '/edit')
            : site_url('managecourse/courses_all/' . (int) ($result['course_id'] ?? 0) . '/edit');

        return redirect()
            ->to($target)
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function assetPost(string $type): array
    {
        $post = $this->request->getPost();

        if ($type === 'media') {
            $media = $this->moveUploadedFile('media_file', 'uploads/media', ['mp4', 'mov', 'webm', 'avi', 'm4v']);
            if ($media['ok'] && $media['filename'] !== '') {
                $post['video'] = $media['filename'];
                $post['type'] = 'upload';
            } elseif (! $media['ok']) {
                $post['upload_error'] = $media['message'];
            }

            $thumbnail = $this->moveUploadedFile('thumbnail_file', 'uploads/media', ['jpg', 'jpeg', 'png', 'webp']);
            if ($thumbnail['ok'] && $thumbnail['filename'] !== '') {
                $post['thumbnail_med'] = $thumbnail['filename'];
            } elseif (! $thumbnail['ok']) {
                $post['upload_error'] = $thumbnail['message'];
            }
        }

        if ($type === 'document') {
            $document = $this->moveUploadedFile('document_file', 'uploads/document', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
            if ($document['ok'] && $document['filename'] !== '') {
                $post['path_file'] = $document['filename'];
            } elseif (! $document['ok']) {
                $post['upload_error'] = $document['message'];
            }
        }

        if ($type === 'scorm') {
            $package = $this->moveUploadedFile('scorm_file', 'uploads/scorm', ['zip']);
            if ($package['ok'] && $package['filename'] !== '') {
                $post['path'] = $package['filename'];
            } elseif (! $package['ok']) {
                $post['upload_error'] = $package['message'];
            }
        }

        return $post;
    }

    private function moveUploadedFile(string $field, string $target, array $extensions): array
    {
        $file = $this->request->getFile($field);
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'filename' => ''];
        }

        if (! $file->isValid()) {
            return ['ok' => false, 'message' => $file->getErrorString(), 'filename' => ''];
        }

        $extension = strtolower($file->getClientExtension() ?: $file->guessExtension() ?: '');
        if (! in_array($extension, $extensions, true)) {
            return ['ok' => false, 'message' => 'Invalid file type: .' . $extension, 'filename' => ''];
        }

        $directory = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, trim($target, '/'));
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            return ['ok' => false, 'message' => 'Upload directory is not writable.', 'filename' => ''];
        }

        $filename = date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
        $file->move($directory, $filename);

        return ['ok' => true, 'filename' => $filename];
    }

    private function renderCoursePage(string $path, string $mode, ?string $permissionPath = null)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, $permissionPath ?? $path)) {
            return redirect()->to(site_url('dashboard'));
        }

        $courses = new CourseModel();
        $items = match ($mode) {
            'groups' => $courses->courseGroups($user, $lang),
            'my' => $courses->myCourses($user, $lang),
            'all' => $courses->publicCourses($user, $lang),
            default => $courses->adminCourses($user, $lang),
        };

        return view('courses/index', [
            'mode' => $mode,
            'path' => $path,
            'title' => $permissions->menuTitle($path, $lang) ?: $path,
            'title_main' => $permissions->parentMenuTitle($path, $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'lang' => $lang,
            'items' => $items,
            'counts' => $courses->dashboardCounts($user),
            'companies' => $courses->companies(),
        ]);
    }
}
