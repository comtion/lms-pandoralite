<?php
namespace App\Filters;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
class MfaEnforcementFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! config('P0')->mfaRequiredForAdmins) {
            return null;
        }
        $user = service('session')->get('user');
        $path = trim($request->getUri()->getPath(), '/');
        if (! is_array($user) || (string)($user['Is_admin'] ?? '0') !== '1'
            || str_ends_with($path, 'security/mfa') || str_ends_with($path, 'security/mfa/enable')
            || str_ends_with($path, 'logout')) {
            return null;
        }
        if (! (new UserModel())->mfaEnabled((int)$user['u_id'])) {
            return redirect()->to(site_url('security/mfa'));
        }
        return null;
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
