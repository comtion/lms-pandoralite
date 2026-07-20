<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! is_array(session('user'))) {
            session()->set('redirect_url', current_url());
            $message = 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่';
            session()->setFlashdata('auth_error', $message);

            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'session_expired' => true,
                        'message' => $message,
                        'redirect' => site_url('login'),
                    ]);
            }

            return service('response')
                ->setStatusCode(401)
                ->setBody(view('auth/session_expired_redirect', [
                    'message' => $message,
                    'loginUrl' => site_url('login'),
                ]));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
