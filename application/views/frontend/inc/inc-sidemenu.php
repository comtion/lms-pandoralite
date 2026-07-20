<?php
defined('BASEPATH') or exit('No direct script access allowed');

$precisionMenuName = static function (array $item) use ($lang): string {
    if ($lang === 'thai') {
        return (string) ($item['mu_name_th'] ?? $item['mu_name_en'] ?? '');
    }
    if ($lang === 'japan') {
        return (string) ($item['mu_name_jp'] ?? $item['mu_name_en'] ?? '');
    }
    return (string) ($item['mu_name_en'] ?? $item['mu_name_th'] ?? '');
};

$precisionChildren = static function (array $item) use ($submenu, $submenu_b): array {
    $id = $item['mu_id'] ?? null;
    if ($id === null) {
        return [];
    }
    return $submenu[$id] ?? $submenu_b[$id] ?? [];
};

$precisionCanShow = null;
$precisionCanShow = static function (array $item) use (&$precisionCanShow, $precisionChildren, $arr_permission): bool {
    $path = (string) ($item['mu_path'] ?? '');
    if ($path !== '' && in_array($path, $arr_permission, true)) {
        return true;
    }
    foreach ($precisionChildren($item) as $child) {
        if ($precisionCanShow($child)) {
            return true;
        }
    }
    return false;
};

$precisionIsActive = null;
$precisionIsActive = static function (array $item) use (&$precisionIsActive, $precisionChildren, $page): bool {
    if ((string) ($item['mu_path'] ?? '') === (string) $page) {
        return true;
    }
    foreach ($precisionChildren($item) as $child) {
        if ($precisionIsActive($child)) {
            return true;
        }
    }
    return false;
};

$precisionRenderBranch = null;
$precisionRenderBranch = static function (array $items, int $level = 0) use (&$precisionRenderBranch, $precisionChildren, $precisionCanShow, $precisionIsActive, $precisionMenuName, $arr_permission): void {
    foreach ($items as $item) {
        if (! $precisionCanShow($item)) {
            continue;
        }

        $children = array_values(array_filter($precisionChildren($item), $precisionCanShow));
        $hasChildren = count($children) > 0;
        $active = $precisionIsActive($item);
        $path = (string) ($item['mu_path'] ?? '');
        $icon = trim((string) ($item['mu_icon'] ?? 'mdi mdi-circle-outline')) ?: 'mdi mdi-circle-outline';
        $name = $precisionMenuName($item);
        if ($level === 0 && (stripos($name, 'report') !== false || strpos($name, 'รายงาน') !== false)) {
            $icon = 'mdi mdi-chart-line';
        }
        ?>
        <li class="precision-nav-item level-<?php echo $level; ?><?php echo $active ? ' active' : ''; ?><?php echo $hasChildren ? ' has-children' : ''; ?>">
            <?php if ($hasChildren) { ?>
                <button class="precision-nav-trigger" type="button" aria-expanded="false">
                    <i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <span class="hide-menu"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
                    <i class="mdi mdi-chevron-right precision-nav-chevron"></i>
                </button>
                <div class="precision-submenu" hidden>
                    <div class="precision-submenu-head">
                        <span><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
                        <button type="button" class="precision-submenu-close" aria-label="Close"><i class="mdi mdi-close"></i></button>
                    </div>
                    <ul><?php $precisionRenderBranch($children, $level + 1); ?></ul>
                </div>
            <?php } elseif ($path !== '' && in_array($path, $arr_permission, true)) { ?>
                <a class="precision-nav-link" href="<?php echo REAL_PATH . '/' . ltrim($path, '/'); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>>
                    <i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <span class="hide-menu"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php } ?>
        </li>
        <?php
    }
};
?>

<aside class="precision-sidebar-v2 precision-sidebar" id="navbar" aria-label="Main navigation">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="precision-nav-root">
                <?php $precisionRenderBranch($main_menu ?? []); ?>
                <?php
                $currentUser = $this->session->userdata('user');
                if (isset($currentUser['ug_id']) && (string) $currentUser['ug_id'] === '1' && !in_array('auditlog/view', $arr_permission, true)) {
                ?>
                <li class="precision-nav-item level-0<?php echo $page === 'auditlog/view' ? ' active' : ''; ?>">
                    <a class="precision-nav-link" href="<?php echo base_url(); ?>index.php/auditlog/view">
                        <i class="mdi mdi-history"></i><span class="hide-menu">Audit Log</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </nav>
        <button class="precision-sidebar-collapse" type="button" aria-expanded="true" aria-label="<?php echo $lang === 'thai' ? 'ซ่อนเมนู' : 'Collapse menu'; ?>">
            <i class="mdi mdi-chevron-double-left"></i>
            <span><?php echo $lang === 'thai' ? 'ซ่อนเมนู' : 'Collapse'; ?></span>
        </button>
    </div>
</aside>

<div class="precision-sidebar-backdrop" hidden></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.querySelector('.precision-sidebar');
    var backdrop = document.querySelector('.precision-sidebar-backdrop');
    var collapseButton = document.querySelector('.precision-sidebar-collapse');
    if (!sidebar) return;

    function closeMenus(exceptItem) {
        sidebar.querySelectorAll('.precision-nav-item.open').forEach(function (item) {
            if (item === exceptItem || item.contains(exceptItem)) return;
            item.classList.remove('open');
            var button = item.querySelector(':scope > .precision-nav-trigger');
            var menu = item.querySelector(':scope > .precision-submenu');
            if (button) button.setAttribute('aria-expanded', 'false');
            if (menu) menu.hidden = true;
        });
        if (!sidebar.querySelector('.precision-nav-item.open') && backdrop) backdrop.hidden = true;
    }

    sidebar.addEventListener('click', function (event) {
        var closeButton = event.target.closest('.precision-submenu-close');
        if (closeButton) {
            closeMenus(null);
            return;
        }
        var trigger = event.target.closest('.precision-nav-trigger');
        if (!trigger) return;
        var item = trigger.closest('.precision-nav-item');
        var menu = item.querySelector(':scope > .precision-submenu');
        var willOpen = !item.classList.contains('open');
        closeMenus(item.parentElement.closest('.precision-nav-item'));
        item.classList.toggle('open', willOpen);
        trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        if (menu) {
            menu.hidden = !willOpen;
            if (willOpen && item.classList.contains('level-0')) {
                var rect = item.getBoundingClientRect();
                menu.style.top = Math.max(96, Math.min(rect.top, window.innerHeight - menu.offsetHeight - 18)) + 'px';
            }
        }
        if (backdrop) backdrop.hidden = !willOpen;
    });

    if (backdrop) backdrop.addEventListener('click', function () { closeMenus(null); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape') closeMenus(null); });

    if (collapseButton) {
        collapseButton.addEventListener('click', function () {
            closeMenus(null);
            hideRailTooltip();
            document.body.classList.toggle('precision-sidebar-collapsed');
            collapseButton.setAttribute('aria-expanded', document.body.classList.contains('precision-sidebar-collapsed') ? 'false' : 'true');
        });
    }

    var railTooltip = document.createElement('div');
    railTooltip.className = 'precision-rail-tooltip';
    railTooltip.hidden = true;
    document.body.appendChild(railTooltip);

    function showRailTooltip(control) {
        if (!document.body.classList.contains('precision-sidebar-collapsed')) return;
        var label = control.querySelector(':scope > .hide-menu');
        if (!label) return;
        var rect = control.getBoundingClientRect();
        railTooltip.textContent = label.textContent.trim();
        railTooltip.style.left = (rect.right + 12) + 'px';
        railTooltip.style.top = (rect.top + rect.height / 2) + 'px';
        railTooltip.hidden = false;
    }

    function hideRailTooltip() { railTooltip.hidden = true; }

    sidebar.querySelectorAll('.precision-nav-root > .level-0 > .precision-nav-link, .precision-nav-root > .level-0 > .precision-nav-trigger').forEach(function (control) {
        control.addEventListener('mouseenter', function () { showRailTooltip(control); });
        control.addEventListener('mouseleave', hideRailTooltip);
        control.addEventListener('focus', function () { showRailTooltip(control); });
        control.addEventListener('blur', hideRailTooltip);
    });
    sidebar.querySelector('.sidebar-nav').addEventListener('scroll', hideRailTooltip, { passive: true });
});
</script>
