<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use App\Models\ApprovalModel;
use App\Models\PermissionModel;

class Dashboard extends BaseController
{
    public function decideCourseApproval($courseId)
    {
        return $this->approvalDecision('course', (int) $courseId);
    }

    public function decideSurveyApproval($surveyId)
    {
        return $this->approvalDecision('survey', (int) $surveyId);
    }

    public function decideCourseGroupApproval($groupId)
    {
        return $this->approvalDecision('course_group', (int) $groupId);
    }

    public function decideBulkApprovals()
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $type = strtolower(trim((string) $this->request->getPost('type')));
        $decision = strtolower(trim((string) $this->request->getPost('decision')));
        $note = trim((string) $this->request->getPost('note'));
        $ids = $this->request->getPost('ids');
        $ids = is_array($ids) ? $ids : [];
        if (! $this->canDecideApproval($user, $type)) {
            return redirect()->to(site_url('dashboard') . '#approval-queue')->with('approval_error', 'No permission for this approval action.');
        }

        $result = (new ApprovalModel())->decideMany($type, $ids, $decision, $note, $user);
        $success = $result['ok'] || ($result['partial'] ?? false);
        return redirect()->to(site_url('dashboard') . '#approval-queue')
            ->with($success ? 'approval_notice' : 'approval_error', $result['message']);
    }

    private function approvalDecision(string $type, int $id)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $decision = strtolower(trim((string) $this->request->getPost('decision')));
        $note = trim((string) $this->request->getPost('note'));
        if (! $this->canDecideApproval($user, $type)) {
            return redirect()->to(site_url('dashboard') . '#approval-queue')->with('approval_error', 'No permission for this approval action.');
        }
        if (mb_strlen($note) > 2000) {
            return redirect()->to(site_url('dashboard'))->with('approval_error', 'The approval note must not exceed 2,000 characters.');
        }

        $model = new ApprovalModel();
        $result = $model->decide($type, $id, $decision, $note, $user);

        return redirect()->to(site_url('dashboard') . '#approval-queue')
            ->with($result['ok'] ? 'approval_notice' : 'approval_error', $result['message']);
    }

    private function canDecideApproval(array $user, string $type): bool
    {
        $path = match ($type) {
            'course' => 'managecourse/courses_all',
            'survey' => 'survey/list_survey',
            'course_group' => 'managecourse/course_groups',
            default => '',
        };
        if ($path === '') {
            return false;
        }

        return (new PermissionModel())->can($user, $path, 'ru_edit');
    }

    public function index()
    {
        $user = $this->session->get('user');
        $dashboard = new DashboardModel();
        $permissions = new PermissionModel();
        $lang = $this->session->get('lang') ?? 'english';
        $summary = is_array($user) ? $dashboard->summaryForUser($user, $lang) : [];
        $approvalHistory = is_array($user) ? (new ApprovalModel())->history($user, 50) : [];
        $menus = is_array($user) ? $permissions->menuTree($user, $lang) : [];

        if ($this->hasNoSurveyItems($summary)) {
            $menus = $this->removeSurveyMenus($menus);
        }

        return view('dashboard/index', [
            'user' => $user,
            'name' => $this->session->get('name'),
            'lang' => $lang,
            'summary' => $summary,
            'approvalHistory' => $approvalHistory,
            'permissions' => is_array($user) ? $permissions->allowedPagePaths($user) : [],
            'menus' => $menus,
            'title' => $permissions->menuTitle('dashboard', $lang),
            'title_main' => $permissions->parentMenuTitle('dashboard', $lang),
        ]);
    }

    private function hasNoSurveyItems(array $summary): bool
    {
        return empty($summary['approval_surveys']) && empty($summary['public_surveys']);
    }

    private function removeSurveyMenus(array $menus): array
    {
        $surveyPaths = [
            'survey',
            'surveylink',
            'survey/list_survey',
        ];

        $filtered = [];

        foreach ($menus as $menu) {
            $path = (string) ($menu['path'] ?? '');

            if (in_array($path, $surveyPaths, true)) {
                continue;
            }

            if (! empty($menu['children'])) {
                $menu['children'] = $this->removeSurveyMenus($menu['children']);
            }

            $filtered[] = $menu;
        }

        return $filtered;
    }

    public function openNotification($notificationId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $db = db_connect();
        if (! $db->tableExists('lms_notifications')) {
            return redirect()->to(site_url('dashboard'));
        }

        $notification = $db->table('lms_notifications')
            ->where('noti_id', (int) $notificationId)
            ->where('emp_id', (int) ($user['emp_id'] ?? 0))
            ->get()
            ->getRowArray();

        if (! $notification) {
            return redirect()->to(site_url('dashboard'));
        }

        $db->table('lms_notifications')
            ->where('noti_id', (int) $notificationId)
            ->update(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);

        $url = trim((string) ($notification['url'] ?? ''));
        return redirect()->to($url !== '' ? site_url($url) : site_url('dashboard'));
    }

    public function markAllNotificationsRead()
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $db = db_connect();
        if ($db->tableExists('lms_notifications')) {
            $db->table('lms_notifications')
                ->where('emp_id', (int) ($user['emp_id'] ?? 0))
                ->where('is_read', 0)
                ->update(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
        }

        return redirect()->to(site_url('dashboard'));
    }

    public function profileSetting()
    {
        $user = $this->session->get('user');
        $employeeId = is_array($user) ? (int) ($user['emp_id'] ?? 0) : 0;

        if ($employeeId === 0) {
            return redirect()->to(site_url('dashboard'));
        }

        $profile = db_connect()->table('lms_emp')
            ->select('lms_emp.*, lms_usp.useri, lms_usp.img_profile')
            ->join('lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', 'left')
            ->where('lms_emp.emp_id', $employeeId)
            ->get()
            ->getRowArray();

        if (! $profile) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('dashboard/profile_setting', [
            'user' => $user,
            'profile' => $profile,
            'name' => $this->session->get('name'),
            'message' => session()->getFlashdata('message'),
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function updateProfile()
    {
        $user = $this->session->get('user');
        $employeeId = is_array($user) ? (int) ($user['emp_id'] ?? 0) : 0;

        if ($employeeId === 0) {
            return redirect()->to(site_url('dashboard'));
        }

        $data = [
            'prefix_th' => trim((string) $this->request->getPost('prefix_th')),
            'fname_th' => trim((string) $this->request->getPost('fname_th')),
            'lname_th' => trim((string) $this->request->getPost('lname_th')),
            'prefix_en' => trim((string) $this->request->getPost('prefix_en')),
            'fname_en' => trim((string) $this->request->getPost('fname_en')),
            'lname_en' => trim((string) $this->request->getPost('lname_en')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'work_phone' => trim((string) $this->request->getPost('work_phone')),
            'email' => trim((string) $this->request->getPost('email')),
            'emp_modifiedby' => (string) ($user['useri'] ?? $user['u_id'] ?? 'system'),
            'emp_modifieddate' => date('Y-m-d H:i:s'),
        ];
        $data['fullname_th'] = trim($data['prefix_th'] . ' ' . $data['fname_th'] . ' ' . $data['lname_th']);
        $data['fullname_en'] = trim($data['prefix_en'] . ' ' . $data['fname_en'] . ' ' . $data['lname_en']);

        if ($data['fname_th'] === '' && $data['fname_en'] === '') {
            return redirect()->back()->withInput()->with('error', 'กรุณาระบุชื่อ');
        }

        db_connect()->table('lms_emp')->where('emp_id', $employeeId)->update($data);

        $sessionUser = $user;
        if (is_array($sessionUser)) {
            $sessionUser['fullname_th'] = $data['fullname_th'];
            $sessionUser['fullname_en'] = $data['fullname_en'];
            $sessionUser['email'] = $data['email'];
            $this->session->set('user', $sessionUser);
        }
        $this->session->set('name', $data['fullname_th'] ?: $data['fullname_en']);

        return redirect()->to(site_url('dashboard/profile/setting'))->with('message', 'บันทึกสำเร็จ');
    }

    public function changePassword()
    {
        return view('dashboard/change_password', [
            'message' => session()->getFlashdata('message'),
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function updatePassword()
    {
        $user = $this->session->get('user');
        if (! is_array($user) || empty($user['u_id'])) {
            return redirect()->to(site_url('login'));
        }

        $current = (string) $this->request->getPost('current_password');
        $password = (string) $this->request->getPost('new_password');
        $confirm = (string) $this->request->getPost('confirm_password');

        if ($password === '' || strlen($password) < 4) {
            return redirect()->back()->withInput()->with('error', 'กรุณาระบุรหัสผ่านใหม่');
        }

        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'รหัสผ่านไม่ตรงกัน');
        }

        $account = db_connect()->table('lms_usp')
            ->select('u_id, userp')
            ->where('u_id', (int) $user['u_id'])
            ->where('u_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $account || (string) ($account['userp'] ?? '') !== hash('sha256', $current)) {
            return redirect()->back()->withInput()->with('error', 'รหัสผ่านเดิมไม่ถูกต้อง');
        }

        db_connect()->table('lms_usp')
            ->where('u_id', (int) $user['u_id'])
            ->update([
                'userp' => hash('sha256', $password),
                'firsttime' => 0,
                'u_modifiedby' => (string) ($user['useri'] ?? $user['u_id']),
                'u_modifieddate' => date('Y-m-d H:i:s'),
            ]);

        return redirect()->to(site_url('dashboard/change_pass'))->with('message', 'เปลี่ยนรหัสผ่านเรียบร้อย');
    }
}
