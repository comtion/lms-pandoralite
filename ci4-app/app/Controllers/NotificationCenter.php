<?php

namespace App\Controllers;

use App\Models\NotificationCenterModel;

class NotificationCenter extends BaseController
{
    public function index()
    {
        $user = $this->session->get('user');
        $model = new NotificationCenterModel();
        return view('notifications/index', [
            'items' => $model->latest($user, (int) ($this->request->getGet('limit') ?: 20)),
            'unread' => $model->unread($user),
            'preferences' => $model->preferences($user),
            'notice' => session('module_notice'),
        ]);
    }

    public function read(int $id)
    {
        (new NotificationCenterModel())->markRead($id, $this->session->get('user'));
        return redirect()->to(site_url('notifications'));
    }

    public function readAll()
    {
        (new NotificationCenterModel())->markAllRead($this->session->get('user'));
        return redirect()->to(site_url('notifications'))->with('module_notice', 'All notifications marked as read.');
    }

    public function preferences()
    {
        (new NotificationCenterModel())->savePreferences($this->session->get('user'), (array) $this->request->getPost());
        return redirect()->to(site_url('notifications'))->with('module_notice', 'Notification preferences saved.');
    }
}
