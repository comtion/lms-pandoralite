<?php

namespace App\Controllers;

use App\Models\ModuleModel;
use App\Models\OrganizationModel;
use App\Models\PermissionModel;
use App\Models\UserAdminModel;

class ModulePortal extends BaseController
{
    public function show(...$segments)
    {
        $path = implode('/', $segments);
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        $module = new ModuleModel();
        if (! in_array($path, $permissions->allowedPagePaths($user), true) && ! $module->hasConfiguredModule($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($path);
        }

        $data = $module->dataFor($path, $lang);

        return view('modules/index', [
            'path' => $path,
            'title' => $permissions->menuTitle($path, $lang) ?: $path,
            'title_main' => $permissions->parentMenuTitle($path, $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'module' => $data,
        ]);
    }

    public function unlockUser($userId)
    {
        return $this->userAction('dashboard/unlockAcc', (int) $userId, 'unlock');
    }

    public function resetPassword($userId)
    {
        return $this->userAction('dashboard/resetPass', (int) $userId, 'reset');
    }

    public function createCompany()
    {
        return $this->organizationAction('manage/companydata', 'create_company');
    }

    public function createUser()
    {
        return $this->userForm('create');
    }

    public function userIndex()
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'manage/userdata')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('modules/user_index', [
            'path' => 'manage/userdata',
            'title' => $permissions->menuTitle('manage/userdata', $lang) ?: 'User Data',
            'title_main' => $permissions->parentMenuTitle('manage/userdata', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
            'records' => (new UserAdminModel())->users(),
        ]);
    }

    public function storeUser()
    {
        return $this->userMutation('create');
    }

    public function editUser($userId)
    {
        return $this->userForm('edit', (int) $userId);
    }

    public function updateUser($userId)
    {
        return $this->userMutation('update', (int) $userId);
    }

    public function userStatus($userId)
    {
        return $this->userMutation('status', (int) $userId);
    }

    public function companyStatus($companyId)
    {
        return $this->organizationAction('manage/companydata', 'company_status', (int) $companyId);
    }

    public function editCompany($companyId)
    {
        return $this->organizationEdit('manage/companydata', 'company', (int) $companyId);
    }

    public function updateCompany($companyId)
    {
        return $this->organizationAction('manage/companydata', 'update_company', (int) $companyId);
    }

    public function createDepartment()
    {
        return $this->organizationAction('manage/departmentdata', 'create_department');
    }

    public function departmentStatus($departmentId)
    {
        return $this->organizationAction('manage/departmentdata', 'department_status', (int) $departmentId);
    }

    public function editDepartment($departmentId)
    {
        return $this->organizationEdit('manage/departmentdata', 'department', (int) $departmentId);
    }

    public function updateDepartment($departmentId)
    {
        return $this->organizationAction('manage/departmentdata', 'update_department', (int) $departmentId);
    }

    private function userAction(string $permissionPath, int $userId, string $action)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, $permissionPath, 'ru_edit')) {
            return redirect()
                ->to(site_url($permissionPath))
                ->with('module_error', 'No edit permission for this action.');
        }

        $admin = new UserAdminModel();
        $result = $action === 'reset'
            ? $admin->resetPasswordByUserId($userId, $user)
            : $admin->unlockByUserId($userId, $user);

        $message = $result['message'];
        if (! empty($result['temporary_password'])) {
            $message .= ' | Temporary password: ' . $result['temporary_password'];
        }
        if (array_key_exists('email_sent', $result)) {
            $message .= ! empty($result['email_sent']) ? ' | Reset email sent.' : ' | Reset email not sent: ' . ($result['email_error'] ?? 'unknown error');
        }

        return redirect()
            ->to(site_url($permissionPath))
            ->with($result['ok'] ? 'module_notice' : 'module_error', $message);
    }

    private function userForm(string $mode, ?int $userId = null)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $field = $mode === 'create' ? 'ru_add' : 'ru_edit';
        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'manage/userdata', $field)) {
            return redirect()->to(site_url('manage/userdata'))->with('module_error', 'No permission for this action.');
        }

        $admin = new UserAdminModel();
        $record = $mode === 'edit' ? $admin->userForEdit((int) $userId) : [];
        if ($mode === 'edit' && ! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User ' . $userId);
        }

        return view('modules/user_form', [
            'mode' => $mode,
            'record' => $record,
            'companies' => $admin->companies(),
            'departments' => $admin->departments(),
            'groups' => $admin->groups(),
            'path' => 'manage/userdata',
            'title' => $mode === 'create' ? 'Create User' : 'Edit User',
            'title_main' => $permissions->parentMenuTitle('manage/userdata', $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }

    private function userMutation(string $mode, ?int $userId = null)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $field = $mode === 'create' ? 'ru_add' : 'ru_edit';
        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'manage/userdata', $field)) {
            return redirect()->to(site_url('manage/userdata'))->with('module_error', 'No permission for this action.');
        }

        $admin = new UserAdminModel();
        $result = match ($mode) {
            'create' => $admin->createUser($this->request->getPost(), $user),
            'update' => $admin->updateUser((int) $userId, $this->request->getPost(), $user),
            'status' => $admin->setUserStatus((int) $userId, (int) $this->request->getPost('status'), $user),
            default => ['ok' => false, 'message' => 'Unknown user action.'],
        };

        $message = $result['message'];
        if (! empty($result['temporary_password'])) {
            $message .= ' | Temporary password: ' . $result['temporary_password'];
        }

        $target = $mode === 'create' && ! empty($result['u_id'])
            ? 'manage/userdata/' . $result['u_id'] . '/edit'
            : 'manage/userdata';

        return redirect()->to(site_url($target))->with($result['ok'] ? 'module_notice' : 'module_error', $message);
    }

    private function organizationAction(string $permissionPath, string $action, ?int $id = null)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $field = str_starts_with($action, 'create_') ? 'ru_add' : 'ru_edit';
        $permissions = new PermissionModel();
        if (! $permissions->can($user, $permissionPath, $field)) {
            return redirect()
                ->to(site_url($permissionPath))
                ->with('module_error', 'No permission for this action.');
        }

        $organization = new OrganizationModel();
        $result = match ($action) {
            'create_company' => $organization->createCompany($this->request->getPost(), $user),
            'create_department' => $organization->createDepartment($this->request->getPost(), $user),
            'update_company' => $organization->updateCompany((int) $id, $this->request->getPost(), $user),
            'update_department' => $organization->updateDepartment((int) $id, $this->request->getPost(), $user),
            'company_status' => $organization->setCompanyStatus((int) $id, (int) $this->request->getPost('status'), $user),
            'department_status' => $organization->setDepartmentStatus((int) $id, (int) $this->request->getPost('status'), $user),
            default => ['ok' => false, 'message' => 'Unknown action.'],
        };

        return redirect()
            ->to(site_url($permissionPath))
            ->with($result['ok'] ? 'module_notice' : 'module_error', $result['message']);
    }

    private function organizationEdit(string $permissionPath, string $type, int $id)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, $permissionPath, 'ru_edit')) {
            return redirect()
                ->to(site_url($permissionPath))
                ->with('module_error', 'No edit permission for this action.');
        }

        $organization = new OrganizationModel();
        $record = $type === 'company' ? $organization->company($id) : $organization->department($id);
        if (! $record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($type . ' ' . $id);
        }

        return view('modules/organization_form', [
            'type' => $type,
            'record' => $record,
            'companies' => $organization->activeCompanies(),
            'path' => $permissionPath,
            'title' => $type === 'company' ? 'Edit Company' : 'Edit Department',
            'title_main' => $permissions->parentMenuTitle($permissionPath, $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }
}
