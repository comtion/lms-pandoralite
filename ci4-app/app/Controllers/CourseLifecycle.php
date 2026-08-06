<?php

namespace App\Controllers;

use App\Models\CourseLifecycleModel;
use App\Models\PermissionModel;

class CourseLifecycle extends BaseController
{
    public function show(int $courseId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        $item = (new CourseLifecycleModel())->detail($courseId, $user);
        return $item ? $this->response->setJSON(['ok' => true, 'lifecycle' => $item]) : $this->response->setStatusCode(404)->setJSON(['ok' => false]);
    }

    public function transition(int $courseId)
    {
        $user = $this->session->get('user');
        if (! (new PermissionModel())->can($user, 'managecourse/courses_all', 'ru_edit')) return $this->response->setStatusCode(403)->setJSON(['ok' => false]);
        $result = (new CourseLifecycleModel())->transition($courseId, $user, strtolower(trim((string) $this->request->getPost('status'))), trim((string) $this->request->getPost('reason')));
        return $this->response->setStatusCode($result['ok'] ? 200 : 422)->setJSON($result);
    }
}
