<?php

namespace App\Controllers;

use App\Models\CertificateModel;
use App\Models\PermissionModel;

class CertificatePortal extends BaseController
{
    public function index()
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'certificate/certificateall')) {
            return redirect()->to(site_url('dashboard'));
        }

        $model = new CertificateModel();
        $canSeeAll = $permissions->can($user, 'certificate/certificateall', 'ru_print') || (string) ($user['u_id'] ?? '') === '1';

        return view('certificate/index', [
            'path' => 'certificate/certificateall',
            'title' => $permissions->menuTitle('certificate/certificateall', $lang) ?: 'Certificates',
            'title_main' => $permissions->parentMenuTitle('certificate/certificateall', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'certificates' => $model->certificates($user, $lang, $canSeeAll),
            'canSeeAll' => $canSeeAll,
        ]);
    }

    public function download($certificateId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        $model = new CertificateModel();
        $certificate = $model->certificate((int) $certificateId);
        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate ' . $certificateId);
        }

        $isOwner = (int) ($certificate['emp_id'] ?? 0) === (int) ($user['emp_id'] ?? 0);
        $canManage = $permissions->can($user, 'certificate/certificateall', 'ru_print') || (string) ($user['u_id'] ?? '') === '1';
        if (! $isOwner && ! $canManage) {
            return redirect()->to(site_url('dashboard'));
        }

        $path = $model->certificatePath((string) $certificate['cert_file']);
        if (! is_file($path)) {
            return redirect()
                ->to(site_url('certificate/certificateall'))
                ->with('module_error', 'Certificate file not found.');
        }

        return $this->response->download($path, null)->setFileName((string) $certificate['cert_file']);
    }

    public function regenerate($certificateId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        $canManage = $permissions->can($user, 'certificate/certificateall', 'ru_print') || (string) ($user['u_id'] ?? '') === '1';
        if (! $canManage) {
            return redirect()->to(site_url('certificate/certificateall'))->with('module_error', 'No permission to regenerate certificates.');
        }

        $model = new CertificateModel();
        $certificate = $model->certificate((int) $certificateId);
        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate ' . $certificateId);
        }

        $result = $model->ensureCertificate((int) $certificate['cos_id'], (int) $certificate['emp_id'], $lang, true);

        return redirect()
            ->to(site_url('certificate/certificateall'))
            ->with($result ? 'module_notice' : 'module_error', $result ? 'Certificate regenerated.' : 'Certificate regeneration failed.');
    }

    public function bulkRegenerate()
    {
        $user = $this->session->get('user');
        $permissions = new PermissionModel();
        if (! is_array($user) || (! $permissions->can($user, 'certificate/certificateall', 'ru_print') && (string)($user['u_id'] ?? '') !== '1')) {
            return redirect()->to(site_url('dashboard'));
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', (array)$this->request->getPost('certificate_ids')))));
        if ($ids === [] || count($ids) > 200) {
            return redirect()->back()->with('module_error', 'Select 1-200 certificates.');
        }
        $model = new CertificateModel();
        $ok = 0;
        foreach ($ids as $id) {
            $certificate = $model->certificate($id);
            if ($certificate && $model->ensureCertificate((int)$certificate['cos_id'], (int)$certificate['emp_id'], $this->session->get('lang') ?? 'english', true)) {
                $ok++;
            }
        }
        return redirect()->to(site_url('certificate/certificateall'))->with('module_notice', "Regenerated {$ok} of ".count($ids).' certificates.');
    }
}
