<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $returnType = 'array';

    private array $mobileBlockedPaths = [
        'quiz/create_template',
        'certificate/certificateall',
        'questionnaire/create',
        'learning_system',
        'survey/list_survey',
        'manage_courses',
    ];

    public function allowedPagePaths(array $user, bool $mobile = false): array
    {
        $menus = $this->menus($mobile);
        $allowed = [];

        foreach ($menus as $menu) {
            if ($this->can($user, $menu['mu_path'], 'ru_view')) {
                $allowed[] = $menu['mu_path'];
            }
        }

        return $allowed;
    }

    public function can(array $user, string $path, string $field = 'ru_view'): bool
    {
        if (empty($user['u_id']) || ! in_array($field, ['ru_view', 'ru_add', 'ru_edit', 'ru_del', 'ru_print'], true)) {
            return false;
        }

        return $this->db->table('lms_role_usp')
            ->join('lms_menu', 'lms_role_usp.mu_id = lms_menu.mu_id')
            ->where('lms_role_usp.' . $field, '1')
            ->where('lms_menu.mu_path', $path)
            ->where('lms_role_usp.u_id', $user['u_id'])
            ->countAllResults() > 0;
    }

    public function menuTree(array $user, string $lang, bool $mobile = false): array
    {
        $allowed = array_flip($this->allowedPagePaths($user, $mobile));
        $parents = $this->topMenus($mobile);
        $tree = [];

        foreach ($parents as $parent) {
            $children = [];

            foreach ($this->subMenus((int) $parent['mu_id'], $mobile) as $child) {
                $grandchildren = [];

                foreach ($this->subMenus((int) $child['mu_id'], $mobile) as $grandchild) {
                    if (isset($allowed[$grandchild['mu_path']])) {
                        $grandchildren[] = $this->decorateMenu($grandchild, $lang, []);
                    }
                }

                if (isset($allowed[$child['mu_path']]) || $grandchildren !== []) {
                    $children[] = $this->decorateMenu($child, $lang, $grandchildren);
                }
            }

            if (isset($allowed[$parent['mu_path']]) || $children !== []) {
                $tree[] = $this->decorateMenu($parent, $lang, $children);
            }
        }

        return $tree;
    }

    public function menuTitle(string $path, string $lang): string
    {
        $menu = $this->db->table('lms_menu')
            ->where('mu_path', $path)
            ->where('mu_status', '1')
            ->get()
            ->getRowArray();

        return $menu ? $this->localizedMenuName($menu, $lang) : '';
    }

    public function parentMenuTitle(string $path, string $lang): string
    {
        $menu = $this->db->table('lms_menu')
            ->where('mu_path', $path)
            ->where('mu_status', '1')
            ->get()
            ->getRowArray();

        if (! $menu || (string) $menu['mu_parent'] === '0') {
            return '';
        }

        $parent = $this->db->table('lms_menu')
            ->where('mu_id', $menu['mu_parent'])
            ->get()
            ->getRowArray();

        return $parent ? $this->localizedMenuName($parent, $lang) : '';
    }

    private function topMenus(bool $mobile): array
    {
        return $this->menus($mobile, 0);
    }

    private function subMenus(int $parentId, bool $mobile): array
    {
        return $this->menus($mobile, $parentId);
    }

    private function menus(bool $mobile, ?int $parentId = null): array
    {
        $builder = $this->db->table('lms_menu')
            ->where('mu_status', '1')
            ->orderBy('mu_num', 'ASC');

        if ($parentId !== null) {
            $builder->where('mu_parent', $parentId);
        }

        if ($mobile) {
            $builder->notLike('mu_path', 'managecourse')
                ->whereNotIn('mu_path', $this->mobileBlockedPaths);
        }

        return $builder->get()->getResultArray();
    }

    private function decorateMenu(array $menu, string $lang, array $children): array
    {
        return [
            'id' => (int) $menu['mu_id'],
            'name' => $this->localizedMenuName($menu, $lang),
            'path' => $menu['mu_path'],
            'icon' => $menu['mu_icon'] ?? '',
            'children' => $children,
        ];
    }

    private function localizedMenuName(array $menu, string $lang): string
    {
        return match ($lang) {
            'thai' => $menu['mu_name_th'] ?: ($menu['mu_name_en'] ?? ''),
            'japan' => $menu['mu_name_jp'] ?: ($menu['mu_name_en'] ?? ''),
            default => $menu['mu_name_en'] ?: ($menu['mu_name_th'] ?? ''),
        };
    }
}
