<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$isThai = $lang === 'thai';
$isJapanese = $lang === 'japan';
$copy = array(
    'title' => $isThai ? 'หลักสูตรของฉัน' : ($isJapanese ? 'マイコース' : 'My courses'),
    'subtitle' => $isThai ? 'เรียนต่อจากจุดที่คุณหยุดไว้ และพัฒนาทักษะอย่างต่อเนื่อง' : ($isJapanese ? '学習を続け、スキルをさらに高めましょう' : 'Continue where you left off and keep building your skills'),
    'search' => $isThai ? 'ค้นหาในหลักสูตรของฉัน' : ($isJapanese ? 'マイコースを検索' : 'Search my courses'),
    'all' => $isThai ? 'ทั้งหมด' : ($isJapanese ? 'すべて' : 'All'),
    'heading' => $isThai ? 'การเรียนรู้ของฉัน' : ($isJapanese ? '私の学習' : 'My learning'),
    'continue' => $isThai ? 'เรียนต่อ' : ($isJapanese ? '続ける' : 'Continue'),
    'open' => $isThai ? 'เปิดหลักสูตร' : ($isJapanese ? 'コースを開く' : 'Open course'),
    'students' => $isThai ? 'ผู้เรียน' : ($isJapanese ? '受講者' : 'learners'),
    'empty' => $isThai ? 'ไม่พบหลักสูตรที่ตรงกับการค้นหา' : ($isJapanese ? '該当するコースがありません' : 'No matching courses found'),
    'clear' => $isThai ? 'ล้างคำค้น' : ($isJapanese ? '検索をクリア' : 'Clear search')
);

$uniqueCourses = array();
foreach ($list_course as $course) {
    if (isset($course['cos_id'])) $uniqueCourses[(string)$course['cos_id']] = $course;
}
$courses = array_values($uniqueCourses);

$courseGroupNames = array();
foreach ($list_coursegroup as $group) {
    $courseGroupNames[(string)$group['cg_id']] = $group['cgname'];
}

function my_course_image($course) {
    if (!empty($course['cos_pic']) && is_file(ROOT_DIR.'uploads/course/'.$course['cos_pic'])) {
        return REAL_PATH.'/uploads/course/'.$course['cos_pic'];
    }
    return REAL_PATH.'/images/cover_course.jpg';
}

function my_course_groups($course) {
    return isset($course['cg_arr']) && is_array($course['cg_arr']) ? implode(',', $course['cg_arr']) : '';
}

function my_course_progress($course) {
    $status = isset($course['status']) ? mb_strtolower(strip_tags($course['status']), 'UTF-8') : '';
    if ($status === mb_strtolower(label('done'), 'UTF-8') || $status === mb_strtolower(label('lrn_btn_done'), 'UTF-8')) return 100;
    if ($status === mb_strtolower(label('inProgress'), 'UTF-8') || $status === mb_strtolower(label('lrn_b_in_progress'), 'UTF-8')) return 48;
    return 8;
}

$heroImage = REAL_PATH.'/assets/images/course-catalog/hero-technician-engine.png';
?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/course-catalog-premium.css?v=20260721-15" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/my-course-premium.css?v=20260721-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border premium-course-page premium-my-course-page">
<div class="preloader"><div class="loader"><div class="loader__figure"></div><p class="loader__label"><?php echo $isThai ? $foote[0]['da_title_th'] : $foote[0]['da_title_en']; ?></p></div></div>
<div id="main-wrapper">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

    <main class="page-wrapper course-catalog-shell">
        <section class="course-hero has-image" style="--course-hero-image:url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>')">
            <div class="course-hero__content">
                <span class="course-hero__eyebrow">ISUZU E-LEARNING</span>
                <h1><?php echo $copy['title']; ?></h1>
                <p><?php echo $copy['subtitle']; ?></p>
                <label class="course-search" for="myCourseSearchInput">
                    <i class="mdi mdi-magnify" aria-hidden="true"></i>
                    <input id="myCourseSearchInput" type="search" autocomplete="off" placeholder="<?php echo $copy['search']; ?>" aria-label="<?php echo $copy['search']; ?>">
                    <button type="button" id="myCourseSearchClear" aria-label="<?php echo $copy['clear']; ?>" disabled><i class="mdi mdi-close"></i></button>
                </label>
            </div>
            <div class="course-hero__visual" aria-hidden="true"></div>

            <div class="course-categories" role="tablist" aria-label="<?php echo label('lrn_b_course_type'); ?>">
                <button class="course-category is-active" type="button" data-group="all" role="tab" aria-selected="true">
                    <i class="mdi mdi-school"></i><span><?php echo $copy['all']; ?></span>
                </button>
                <?php
                $fallbackIcons = array('mdi-account','mdi-truck','mdi-book-open-page-variant','mdi-wrench','mdi-laptop','mdi-shield','mdi-chart-line');
                $categoryIndex = 0;
                foreach ($list_coursegroup as $group) {
                    $fallbackIcon = $fallbackIcons[$categoryIndex % count($fallbackIcons)];
                    $uploadedIcon = isset($group['cg_icon']) ? basename($group['cg_icon']) : '';
                    $uploadedIconPath = $uploadedIcon !== '' ? ROOT_DIR.'uploads/course_group/icons/'.$uploadedIcon : '';
                    $categoryIndex++;
                ?>
                <button class="course-category" type="button" data-group="<?php echo $group['cg_id']; ?>" role="tab" aria-selected="false">
                    <?php if ($uploadedIcon !== '' && is_file($uploadedIconPath)) { ?>
                    <img class="course-category__icon" src="<?php echo REAL_PATH; ?>/uploads/course_group/icons/<?php echo rawurlencode($uploadedIcon); ?>" alt="">
                    <?php } else { ?>
                    <i class="mdi <?php echo $fallbackIcon; ?>"></i>
                    <?php } ?>
                    <span><?php echo htmlspecialchars($group['cgname'], ENT_QUOTES, 'UTF-8'); ?></span>
                </button>
                <?php } ?>
            </div>
        </section>

        <section class="course-discovery my-course-discovery" aria-labelledby="myLearningTitle">
            <header class="course-discovery__header">
                <div>
                    <span class="my-course-section-kicker">LEARNING LIBRARY</span>
                    <h2 id="myLearningTitle"><?php echo $copy['heading']; ?></h2>
                </div>
                <span class="my-course-count"><strong id="visibleCourseCount"><?php echo count($courses); ?></strong> <?php echo $isThai ? 'หลักสูตร' : ($isJapanese ? 'コース' : 'courses'); ?></span>
            </header>

            <?php if (count($courses) > 0) { ?>
            <div class="my-course-grid" id="myCourseResults">
                <?php foreach ($courses as $course) {
                    $groupLabel = '';
                    if (isset($course['cg_arr']) && is_array($course['cg_arr'])) {
                        foreach ($course['cg_arr'] as $groupId) {
                            if (isset($courseGroupNames[(string)$groupId])) { $groupLabel = $courseGroupNames[(string)$groupId]; break; }
                        }
                    }
                    $progress = my_course_progress($course);
                    $courseUrl = REAL_PATH.'/coursemain/detail/'.$course['cos_id'];
                ?>
                <article class="course-card course-item my-course-card<?php echo $course['isCondition'] === '1' ? ' onCondition' : ''; ?>"
                    data-name="<?php echo htmlspecialchars(mb_strtolower($course['cname'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                    data-groups="<?php echo my_course_groups($course); ?>"
                    msg="<?php echo htmlspecialchars(str_replace('_coursename_', $course['msgCondition'], label('condition_msg')), ENT_QUOTES, 'UTF-8'); ?>">
                    <a class="course-card__image" <?php if ($course['isCondition'] === '0') { ?>href="<?php echo $courseUrl; ?>"<?php } ?> style="background-image:url('<?php echo htmlspecialchars(my_course_image($course), ENT_QUOTES, 'UTF-8'); ?>')">
                        <span class="my-course-status<?php echo $progress === 100 ? ' is-complete' : ($progress > 8 ? ' is-progress' : ''); ?>"><?php echo htmlspecialchars($course['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                    <div class="course-card__body">
                        <?php if ($groupLabel !== '') { ?><span class="course-card__kicker"><?php echo htmlspecialchars($groupLabel, ENT_QUOTES, 'UTF-8'); ?></span><?php } ?>
                        <h3 title="<?php echo htmlspecialchars($course['cname'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($course['cname'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><i class="mdi mdi-calendar"></i><?php echo htmlspecialchars($course['txt_period_course'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="course-card__meta"><span><i class="mdi mdi-account-multiple-outline"></i><?php echo number_format($course['seat']); ?> <?php echo $copy['students']; ?></span><strong><?php echo $progress; ?>%</strong></div>
                        <div class="course-card__footer">
                            <div class="course-progress"><span style="width:<?php echo $progress; ?>%"></span></div>
                            <?php if ($course['isCondition'] === '0') { ?><a href="<?php echo $courseUrl; ?>"><?php echo $progress > 8 ? $copy['continue'] : $copy['open']; ?><i class="mdi mdi-chevron-right"></i></a><?php } ?>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>
            <div class="course-empty" id="myCourseEmpty" hidden><i class="mdi mdi-book-open-page-variant"></i><h3><?php echo $copy['empty']; ?></h3><button type="button"><?php echo $copy['clear']; ?></button></div>
            <?php } else { ?>
            <div class="course-empty"><i class="mdi mdi-book-open-page-variant"></i><h3><?php echo label('lrn_b_course_notfound'); ?></h3></div>
            <?php } ?>
        </section>
    </main>
</div>

<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
<script>
(function () {
    var search = document.getElementById('myCourseSearchInput');
    var clear = document.getElementById('myCourseSearchClear');
    var tabs = document.querySelectorAll('.course-category');
    var cards = document.querySelectorAll('.course-item');
    var empty = document.getElementById('myCourseEmpty');
    var count = document.getElementById('visibleCourseCount');
    var activeGroup = 'all';

    function filterCourses() {
        var query = search ? search.value.trim().toLocaleLowerCase() : '';
        var visible = 0;
        Array.prototype.forEach.call(cards, function (card) {
            var groups = (card.getAttribute('data-groups') || '').split(',');
            var show = (activeGroup === 'all' || groups.indexOf(activeGroup) !== -1)
                && (!query || (card.getAttribute('data-name') || '').indexOf(query) !== -1);
            card.hidden = !show;
            if (show) visible++;
        });
        if (empty) empty.hidden = visible > 0;
        if (count) count.textContent = visible;
        if (clear) { clear.classList.toggle('has-query', !!query); clear.disabled = !query; }
    }

    Array.prototype.forEach.call(tabs, function (tab) {
        tab.addEventListener('click', function () {
            activeGroup = tab.getAttribute('data-group');
            Array.prototype.forEach.call(tabs, function (item) {
                var active = item === tab;
                item.classList.toggle('is-active', active);
                item.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            filterCourses();
        });
    });
    if (search) search.addEventListener('input', filterCourses);
    if (clear) clear.addEventListener('click', function () { search.value = ''; search.focus(); filterCourses(); });
    var emptyButton = empty && empty.querySelector('button');
    if (emptyButton) emptyButton.addEventListener('click', function () { search.value = ''; activeGroup = 'all'; tabs[0].click(); });
})();

$(document).on('click', '.onCondition', function(event) {
    event.preventDefault();
    swal({title: $(this).attr('msg'), text: '', type: 'warning', showCancelButton: false, confirmButtonClass: 'btn btn-primary', confirmButtonText: '<?php echo label('m_ok'); ?>'});
});
</script>
</body>
</html>
