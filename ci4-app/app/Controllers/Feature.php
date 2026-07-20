<?php

namespace App\Controllers;

use App\Models\PermissionModel;

class Feature extends BaseController
{
    public function show(...$segments)
    {
        $path = implode('/', $segments);
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        $permissions = new PermissionModel();

        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        if (! in_array($path, $permissions->allowedPagePaths($user), true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($path);
        }

        return view('feature/pending', [
            'path' => $path,
            'title' => $permissions->menuTitle($path, $lang) ?: $path,
            'title_main' => $permissions->parentMenuTitle($path, $lang),
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }
}
