<?php

namespace App\Controllers;

use App\Models\CourseLifecycleModel;
use App\Models\EnrollmentWorkflowModel;
use App\Models\PermissionModel;

class WorkflowCenter extends BaseController
{
    public function index()
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all')) return redirect()->to(site_url('dashboard'));
        $courses = db_connect()->table('lms_cos c')->select('c.cos_id,c.ccode,c.cname_th,c.cname_eng,c.com_id,l.lifecycle_status,l.version_no,p.enrollment_mode,p.capacity,p.waitlist_enabled')
            ->join('lms_course_lifecycle l', 'l.cos_id=c.cos_id', 'left')->join('lms_enrollment_policies p', 'p.cos_id=c.cos_id', 'left')
            ->where(['c.cos_isDelete' => '0']);
        if ((string) ($user['ug_viewdata'] ?? '') !== '1' && (string) ($user['u_id'] ?? '') !== '1') $courses->where('c.com_id', (int) $user['com_id']);
        return view('workflows/index', [
            'courses' => $courses->orderBy('c.cos_id', 'DESC')->limit(100)->get()->getResultArray(),
            'requests' => (new EnrollmentWorkflowModel())->pending($user),
            'notice' => session('module_notice'), 'error' => session('module_error'),
        ]);
    }

    public function transition(int $courseId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all', 'ru_edit')) return redirect()->to(site_url('dashboard'));
        $result = (new CourseLifecycleModel())->transition($courseId, $user, strtolower((string) $this->request->getPost('status')), trim((string) $this->request->getPost('reason')));
        return redirect()->to(site_url('workflows'))->with($result['ok'] ? 'module_notice' : 'module_error', $result['ok'] ? 'Course lifecycle updated.' : $result['message']);
    }

    public function decide(int $requestId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'manage/userdata', 'ru_edit')) return redirect()->to(site_url('dashboard'));
        $result = (new EnrollmentWorkflowModel())->decide($requestId, $user, strtolower((string) $this->request->getPost('decision')), trim((string) $this->request->getPost('reason')));
        return redirect()->to(site_url('workflows'))->with($result['ok'] ? 'module_notice' : 'module_error', $result['ok'] ? 'Enrollment decision saved.' : $result['message']);
    }

    public function policy(int $courseId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all', 'ru_edit')) return redirect()->to(site_url('dashboard'));
        $ok = (new EnrollmentWorkflowModel())->savePolicy($courseId, $user, (array) $this->request->getPost());
        return redirect()->to(site_url('workflows'))->with($ok ? 'module_notice' : 'module_error', $ok ? 'Enrollment policy saved.' : 'Course not found.');
    }
}
