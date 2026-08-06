<?php

namespace App\Controllers;

use App\Models\EnrollmentWorkflowModel;
use App\Models\PermissionModel;

class EnrollmentWorkflow extends BaseController
{
    public function request(int $courseId)
    {
        $user = $this->session->get('user');
        $result = (new EnrollmentWorkflowModel())->request($courseId, $user, (int) $user['emp_id']);
        return $this->response->setStatusCode($result['ok'] ? 200 : 422)->setJSON($result);
    }

    public function pending()
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'manage/userdata')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        return $this->response->setJSON(['ok' => true, 'items' => (new EnrollmentWorkflowModel())->pending($user, (int) ($this->request->getGet('limit') ?: 100))]);
    }

    public function decide(int $requestId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'manage/userdata', 'ru_edit')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        $result = (new EnrollmentWorkflowModel())->decide($requestId, $user, strtolower((string) $this->request->getPost('decision')), trim((string) $this->request->getPost('reason')));
        return $this->response->setStatusCode($result['ok'] ? 200 : 422)->setJSON($result);
    }

    public function policy(int $courseId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        $model = new EnrollmentWorkflowModel();
        if ($this->request->is('get')) return $this->response->setJSON(['ok' => true, 'policy' => $model->policy($courseId, $user)]);
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all', 'ru_edit')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        $ok = $model->savePolicy($courseId, $user, (array) $this->request->getPost());
        return $this->response->setStatusCode($ok ? 200 : 404)->setJSON(['ok' => $ok]);
    }
}
