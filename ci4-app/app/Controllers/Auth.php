<?php

namespace App\Controllers;

use App\Libraries\AuthService;

class Auth extends BaseController
{
    public function login()
    {
        if (is_array($this->session->get('user'))) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', [
            'error' => $this->session->getFlashdata('auth_error'),
        ]);
    }

    public function attempt()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('auth_error', 'Please enter username and password.');
        }

        $auth = new AuthService();

        if (! $auth->attempt((string) $this->request->getPost('username'), (string) $this->request->getPost('password'))) {
            return redirect()->back()->withInput()->with('auth_error', $this->session->getFlashdata('auth_error') ?: 'Invalid username or password.');
        }

        $redirect = $this->session->get('redirect_url') ?: site_url('dashboard');
        $this->session->remove('redirect_url');

        return redirect()->to($redirect);
    }

    public function logout()
    {
        (new AuthService())->logout();

        return redirect()->to(site_url('login'));
    }
}
