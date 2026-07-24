<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$isThai = $lang === 'thai';
$isJapanese = $lang === 'japan';
$copy = array(
    'title' => $isThai ? 'หลักสูตรทั้งหมด' : ($isJapanese ? 'すべてのコース' : 'All courses'),
    'subtitle' => $isThai ? 'ยกระดับทักษะ ความรู้ เพื่อก้าวสู่มาตรฐานอีซูซุ' : ($isJapanese ? '知識とスキルを磨き、次のステップへ' : 'Build the knowledge and skills to move forward'),
    'search' => $isThai ? 'ค้นหาหลักสูตร' : ($isJapanese ? 'コースを検索' : 'Search courses'),
    'all' => $isThai ? 'ทั้งหมด' : ($isJapanese ? 'すべて' : 'All'),
    'recommended' => $isThai ? 'แนะนำสำหรับคุณ' : ($isJapanese ? 'あなたへのおすすめ' : 'Recommended for you'),
    'view_all' => $isThai ? 'ดูทั้งหมด' : ($isJapanese ? 'すべて見る' : 'View all'),
    'learning_path' => $isThai ? 'เส้นทางการเรียนรู้' : ($isJapanese ? 'ラーニングパス' : 'Learning path'),
    'continue' => $isThai ? 'เรียนต่อ' : ($isJapanese ? '続ける' : 'Continue'),
    'start' => $isThai ? 'เริ่มเรียน' : ($isJapanese ? '開始' : 'Start course'),
    'register' => $isThai ? 'ลงทะเบียน' : ($isJapanese ? '登録' : 'Enroll'),
    'students' => $isThai ? 'ผู้เรียน' : ($isJapanese ? '受講者' : 'learners'),
    'period' => $isThai ? 'ระยะเวลาเปิดเรียน' : ($isJapanese ? '受講期間' : 'Course period'),
    'empty' => $isThai ? 'ไม่พบหลักสูตรที่ตรงกับการค้นหา' : ($isJapanese ? '該当するコースがありません' : 'No matching courses found'),
    'clear' => $isThai ? 'ล้างตัวกรอง' : ($isJapanese ? 'フィルターを解除' : 'Clear filters')
);

$uniqueCourses = array();
foreach ($list_course as $course) {
    $uniqueCourses[(string)$course['cos_id']] = $course;
}
$courses = array_values($uniqueCourses);
$featured = count($courses) ? $courses[0] : null;
$courseGroupNames = array();
foreach ($list_coursegroup as $group) {
    $courseGroupNames[(string)$group['cg_id']] = $group['cgname'];
}
$featuredDescription = '';
if ($featured) {
    $descriptionKeys = $isThai
        ? array('sub_description_th', 'cdesc_th', 'sub_description_eng', 'cdesc_eng')
        : ($isJapanese
            ? array('sub_description_jp', 'cdesc_jp', 'sub_description_eng', 'cdesc_eng')
            : array('sub_description_eng', 'cdesc_eng', 'sub_description_th', 'cdesc_th'));
    foreach ($descriptionKeys as $descriptionKey) {
        if (!empty($featured[$descriptionKey])) {
            $featuredDescription = trim(preg_replace('/\s+/u', ' ', strip_tags($featured[$descriptionKey])));
            break;
        }
    }
    if ($featuredDescription !== '') $featuredDescription = mb_substr($featuredDescription, 0, 170, 'UTF-8');
}

$heroImage = REAL_PATH.'/assets/images/course-catalog/hero-technician-engine.png';

function premium_course_image($course) {
    if (!empty($course['cos_pic']) && is_file(ROOT_DIR.'uploads/course/'.$course['cos_pic'])) {
        return REAL_PATH.'/uploads/course/'.$course['cos_pic'];
    }
    return REAL_PATH.'/images/cover_course.jpg';
}

function premium_course_groups($course) {
    return isset($course['cg_arr']) && is_array($course['cg_arr']) ? implode(',', $course['cg_arr']) : '';
}
?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/course-catalog-premium.css?v=20260721-14" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border premium-course-page">
<div class="preloader"><div class="loader"><div class="loader__figure"></div><p class="loader__label"><?php echo $isThai ? $foote[0]['da_title_th'] : $foote[0]['da_title_en']; ?></p></div></div>
<div id="main-wrapper">
    <?php $this->load->view('frontend/inc/inc-header.php'); ?>
    <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

    <main class="page-wrapper course-catalog-shell">
        <section class="course-hero<?php echo $heroImage ? ' has-image' : ''; ?>"<?php if ($heroImage) { ?> style="--course-hero-image:url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>')"<?php } ?>>
            <div class="course-hero__content">
                <h1><?php echo $copy['title']; ?></h1>
                <p><?php echo $copy['subtitle']; ?></p>
                <label class="course-search" for="courseSearchInput">
                    <i class="mdi mdi-magnify" aria-hidden="true"></i>
                    <input id="courseSearchInput" type="search" autocomplete="off" placeholder="<?php echo $copy['search']; ?>" aria-label="<?php echo $copy['search']; ?>">
                    <button type="button" id="courseSearchClear" aria-label="<?php echo $copy['clear']; ?>" disabled><i class="mdi mdi-close"></i></button>
                </label>
            </div>
            <div class="course-hero__visual" aria-hidden="true"></div>

            <div class="course-categories" role="tablist" aria-label="<?php echo label('lrn_b_course_type'); ?>">
                <button class="course-category is-active" type="button" data-group="all" role="tab" aria-selected="true">
                    <i class="mdi mdi-school"></i><span><?php echo $copy['all']; ?></span>
                </button>
                <?php
                $categoryIcons = array('mdi-account','mdi-truck','mdi-book-open-page-variant','mdi-wrench','mdi-laptop','mdi-shield','mdi-chart-line');
                $categoryIndex = 0;
                foreach ($list_coursegroup as $group) {
                    $icon = $categoryIcons[$categoryIndex % count($categoryIcons)];
                    $uploadedIcon = isset($group['cg_icon']) ? basename($group['cg_icon']) : '';
                    $uploadedIconPath = $uploadedIcon !== '' ? ROOT_DIR . 'uploads/course_group/icons/' . $uploadedIcon : '';
                    $categoryIndex++;
                ?>
                <button class="course-category" type="button" data-group="<?php echo $group['cg_id']; ?>" role="tab" aria-selected="false">
                    <?php if ($uploadedIcon !== '' && is_file($uploadedIconPath)) { ?>
                    <img class="course-category__icon" src="<?php echo REAL_PATH; ?>/uploads/course_group/icons/<?php echo rawurlencode($uploadedIcon); ?>" alt="">
                    <?php } else { ?>
                    <i class="mdi <?php echo $icon; ?>"></i>
                    <?php } ?>
                    <span><?php echo htmlspecialchars($group['cgname'], ENT_QUOTES, 'UTF-8'); ?></span>
                </button>
                <?php } ?>
            </div>
        </section>

        <section class="course-discovery" aria-labelledby="recommendedTitle">
            <header class="course-discovery__header">
                <h2 id="recommendedTitle"><?php echo $copy['recommended']; ?></h2>
            </header>

            <?php if ($featured) { ?>
            <div class="course-layout<?php echo count($courses) <= 1 ? ' is-feature-only' : ''; ?>" id="courseResults">
                <article class="course-feature course-item" data-feature-mode="hero" data-name="<?php echo htmlspecialchars(mb_strtolower($featured['cname'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" data-groups="<?php echo premium_course_groups($featured); ?>">
                    <div class="course-feature__image" style="background-image:url('<?php echo REAL_PATH; ?>/assets/images/course-catalog/featured-technician.png')"></div>
                    <div class="course-feature__shade"></div>
                    <div class="course-feature__content">
                        <div class="course-feature__topline"><span class="course-feature__badge"><?php echo $copy['learning_path']; ?></span><?php if (!empty($featured['ccode'])) { ?><span class="course-feature__code"><?php echo htmlspecialchars($featured['ccode'], ENT_QUOTES, 'UTF-8'); ?></span><?php } ?></div>
                        <h3><?php echo htmlspecialchars($featured['cname'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <?php if ($featuredDescription !== '') { ?><p class="course-feature__description"><?php echo htmlspecialchars($featuredDescription, ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
                        <p class="course-feature__period"><i class="mdi mdi-calendar-clock"></i><span><?php echo $copy['period']; ?><strong><?php echo htmlspecialchars($featured['txt_period_course'], ENT_QUOTES, 'UTF-8'); ?></strong></span></p>
                        <div class="course-feature__meta"><span><i class="mdi mdi-account-multiple-outline"></i><?php echo number_format($featured['seat']); ?> <?php echo $copy['students']; ?></span><span><?php echo htmlspecialchars($featured['status'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                        <div class="course-progress" aria-hidden="true"><span style="width:<?php echo $featured['isRegister'] === '1' ? '60' : '8'; ?>%"></span></div>
                        <?php if ($featured['isRegister'] === '1' && $featured['isCondition'] === '0') { ?>
                        <a class="course-primary" href="<?php echo REAL_PATH; ?>/coursemain/detail/<?php echo $featured['cos_id']; ?>"><?php echo $copy['continue']; ?><i class="mdi mdi-arrow-right"></i></a>
                        <?php } elseif ($featured['isRegister'] === '0' && $featured['isseatFull'] === '0' && $featured['isCondition'] === '0') { ?>
                        <button class="course-primary catalog-enroll-action" type="button" data-course-id="<?php echo $featured['cos_id']; ?>"><?php echo $copy['register']; ?><i class="mdi mdi-arrow-right"></i></button>
                        <?php } ?>
                    </div>
                </article>

                <div class="course-grid">
                    <?php
                    $featuredGroupLabel = '';
                    foreach ($featured['cg_arr'] as $featuredGroupId) {
                        if (isset($courseGroupNames[(string)$featuredGroupId])) { $featuredGroupLabel = $courseGroupNames[(string)$featuredGroupId]; break; }
                    }
                    ?>
                    <article class="course-card course-item" data-feature-mode="card" data-name="<?php echo htmlspecialchars(mb_strtolower($featured['cname'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" data-groups="<?php echo premium_course_groups($featured); ?>" hidden>
                        <button class="course-bookmark" type="button" aria-label="Bookmark" aria-pressed="false"><i class="mdi mdi-bookmark-outline"></i></button>
                        <a class="course-card__image<?php echo $featured['isCondition'] === '1' ? ' onCondition' : ''; ?>" <?php if ($featured['isRegister'] === '1' && $featured['isCondition'] === '0') { ?>href="<?php echo REAL_PATH; ?>/coursemain/detail/<?php echo $featured['cos_id']; ?>"<?php } ?> msg="<?php echo htmlspecialchars(str_replace('_coursename_', $featured['msgCondition'], label('condition_msg')), ENT_QUOTES, 'UTF-8'); ?>" style="background-image:url('<?php echo htmlspecialchars(premium_course_image($featured), ENT_QUOTES, 'UTF-8'); ?>')"></a>
                        <div class="course-card__body">
                            <?php if ($featuredGroupLabel !== '') { ?><span class="course-card__kicker"><?php echo htmlspecialchars($featuredGroupLabel, ENT_QUOTES, 'UTF-8'); ?></span><?php } ?>
                            <h3 title="<?php echo htmlspecialchars($featured['cname'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($featured['cname'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><i class="mdi mdi-calendar"></i><?php echo htmlspecialchars($featured['txt_period_course'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="course-card__meta"><span><i class="mdi mdi-account-multiple-outline"></i><?php echo number_format($featured['seat']); ?> <?php echo $copy['students']; ?></span><span><?php echo htmlspecialchars($featured['status'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                            <div class="course-card__footer">
                                <div class="course-progress"><span style="width:<?php echo $featured['isRegister'] === '1' ? '60' : '8'; ?>%"></span></div>
                                <?php if ($featured['isRegister'] === '1' && $featured['isCondition'] === '0') { ?>
                                <a href="<?php echo REAL_PATH; ?>/coursemain/detail/<?php echo $featured['cos_id']; ?>"><?php echo $copy['continue']; ?><i class="mdi mdi-chevron-right"></i></a>
                                <?php } elseif ($featured['isRegister'] === '0' && $featured['isseatFull'] === '0' && $featured['isCondition'] === '0') { ?>
                                <button class="catalog-enroll-action" type="button" data-course-id="<?php echo $featured['cos_id']; ?>"><?php echo $copy['register']; ?><i class="mdi mdi-chevron-right"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                    <?php foreach (array_slice($courses, 1) as $course) { ?>
                    <?php
                    $cardGroupLabel = '';
                    foreach ($course['cg_arr'] as $cardGroupId) {
                        if (isset($courseGroupNames[(string)$cardGroupId])) { $cardGroupLabel = $courseGroupNames[(string)$cardGroupId]; break; }
                    }
                    ?>
                    <article class="course-card course-item" data-name="<?php echo htmlspecialchars(mb_strtolower($course['cname'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" data-groups="<?php echo premium_course_groups($course); ?>">
                        <button class="course-bookmark" type="button" aria-label="Bookmark" aria-pressed="false"><i class="mdi mdi-bookmark-outline"></i></button>
                        <a class="course-card__image<?php echo $course['isCondition'] === '1' ? ' onCondition' : ''; ?>" <?php if ($course['isRegister'] === '1' && $course['isCondition'] === '0') { ?>href="<?php echo REAL_PATH; ?>/coursemain/detail/<?php echo $course['cos_id']; ?>"<?php } ?> msg="<?php echo htmlspecialchars(str_replace('_coursename_', $course['msgCondition'], label('condition_msg')), ENT_QUOTES, 'UTF-8'); ?>" style="background-image:url('<?php echo htmlspecialchars(premium_course_image($course), ENT_QUOTES, 'UTF-8'); ?>')"></a>
                        <div class="course-card__body">
                            <?php if ($cardGroupLabel !== '') { ?><span class="course-card__kicker"><?php echo htmlspecialchars($cardGroupLabel, ENT_QUOTES, 'UTF-8'); ?></span><?php } ?>
                            <h3 title="<?php echo htmlspecialchars($course['cname'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($course['cname'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><i class="mdi mdi-calendar"></i><?php echo htmlspecialchars($course['txt_period_course'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="course-card__meta"><span><i class="mdi mdi-account-multiple-outline"></i><?php echo number_format($course['seat']); ?> <?php echo $copy['students']; ?></span><span><?php echo htmlspecialchars($course['status'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                            <div class="course-card__footer">
                                <div class="course-progress"><span style="width:<?php echo $course['isRegister'] === '1' ? '45' : '8'; ?>%"></span></div>
                                <?php if ($course['isRegister'] === '1' && $course['isCondition'] === '0') { ?>
                                <a href="<?php echo REAL_PATH; ?>/coursemain/detail/<?php echo $course['cos_id']; ?>"><?php echo $copy['continue']; ?><i class="mdi mdi-chevron-right"></i></a>
                                <?php } elseif ($course['isRegister'] === '0' && $course['isseatFull'] === '0' && $course['isCondition'] === '0') { ?>
                                <button class="catalog-enroll-action" type="button" data-course-id="<?php echo $course['cos_id']; ?>"><?php echo $copy['register']; ?><i class="mdi mdi-chevron-right"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                    <?php } ?>
                </div>
            </div>
            <div class="course-empty" id="courseEmpty" hidden><i class="mdi mdi-book-open-page-variant"></i><h3><?php echo $copy['empty']; ?></h3><button type="button"><?php echo $copy['clear']; ?></button></div>
            <?php } else { ?>
            <div class="course-empty"><i class="mdi mdi-book-open-page-variant"></i><h3><?php echo label('lrn_b_course_notfound'); ?></h3></div>
            <?php } ?>
        </section>
    </main>
</div>

<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
<div id="myModal_process" class="modal fade bs-example-modal-lg" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body text-center"><img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" alt="" style="width:50%"><h3><?php echo label('please_wait'); ?></h3></div></div></div></div>

<script>
(function () {
    var search = document.getElementById('courseSearchInput');
    var clear = document.getElementById('courseSearchClear');
    var categoryButtons = document.querySelectorAll('.course-category');
    var items = document.querySelectorAll('.course-item');
    var empty = document.getElementById('courseEmpty');
    var activeGroup = 'all';

    function applyFilters() {
        var query = search ? search.value.trim().toLocaleLowerCase() : '';
        var visible = 0;
        Array.prototype.forEach.call(items, function (item) {
            var groups = (item.getAttribute('data-groups') || '').split(',');
            var matchesGroup = activeGroup === 'all' || groups.indexOf(activeGroup) !== -1;
            var matchesText = !query || (item.getAttribute('data-name') || '').indexOf(query) !== -1;
            var show = matchesGroup && matchesText;
            var featureMode = item.getAttribute('data-feature-mode');
            if (featureMode === 'hero') show = show && activeGroup === 'all';
            if (featureMode === 'card') show = show && activeGroup !== 'all';
            item.hidden = !show;
            if (show) visible++;
        });
        var results = document.getElementById('courseResults');
        var feature = results && results.querySelector('.course-feature');
        if (results && feature) results.classList.toggle('is-feature-filtered', feature.hidden);
        if (empty) empty.hidden = visible > 0;
        if (clear) {
            clear.classList.toggle('has-query', !!query);
            clear.disabled = !query;
        }
    }

    Array.prototype.forEach.call(categoryButtons, function (button) {
        button.addEventListener('click', function () {
            activeGroup = button.getAttribute('data-group');
            Array.prototype.forEach.call(categoryButtons, function (item) {
                var selected = item === button;
                item.classList.toggle('is-active', selected);
                item.setAttribute('aria-selected', selected ? 'true' : 'false');
            });
            var heading = document.getElementById('recommendedTitle');
            var categoryLabel = button.querySelector('span');
            if (heading) heading.textContent = activeGroup === 'all'
                ? <?php echo json_encode($copy['recommended'], JSON_UNESCAPED_UNICODE); ?>
                : (categoryLabel ? categoryLabel.textContent : '');
            applyFilters();
        });
    });
    if (search) search.addEventListener('input', applyFilters);
    if (clear) clear.addEventListener('click', function () {
        if (search.value) search.value = '';
        search.focus();
        applyFilters();
    });
    var emptyButton = empty && empty.querySelector('button');
    if (emptyButton) emptyButton.addEventListener('click', function () { search.value = ''; activeGroup = 'all'; categoryButtons[0].click(); });
    document.addEventListener('click', function (event) {
        var bookmark = event.target.closest('.course-bookmark');
        if (!bookmark) return;
        var active = bookmark.getAttribute('aria-pressed') === 'true';
        bookmark.setAttribute('aria-pressed', active ? 'false' : 'true');
        bookmark.classList.toggle('is-active', !active);
        bookmark.querySelector('i').className = active ? 'mdi mdi-bookmark-outline' : 'mdi mdi-bookmark';
    });
})();

$('.onCondition').click(function(event){
    event.preventDefault();
    swal({title: $(this).attr('msg'), text: '', type: 'warning', showCancelButton: false, confirmButtonClass: 'btn btn-primary', confirmButtonText: '<?php echo label('m_ok'); ?>'});
});

$(document).on('click', '.catalog-enroll-action', function(){
    var cos_id = $(this).data('course-id');
    swal({title: '<?php echo label('enroll_msg'); ?>', text: '', type: 'warning', showCancelButton: true, confirmButtonColor: '#E31B23', confirmButtonText: '<?php echo label('lrn_btn_register'); ?>', cancelButtonText: '<?php echo label('cancel'); ?>'}).then(function(result) {
        if (!result.value) return;
        $('#myModal_process').modal({backdrop: false});
        $.ajax({url:'<?php echo base_url(); ?>index.php/querydata/enroll_course_byuser', method:'POST', data:{cos_id:cos_id}, dataType:'json', success:function(data){
            if (data.status === '2') swal('<?php echo label('enroll_reuse_success'); ?>', '', 'success').then(function(){ location.reload(); });
            else if (data.status === '3') swal({title:'<?php echo label('lrn_b_approver_student'); ?>', type:'warning', confirmButtonText:'<?php echo label('lrn_btn_ok'); ?>'}).then(function(){ location.reload(); });
            else if (data.status === '1') swal({title:'<?php echo label('lrn_btn_re_enroll'); ?>', type:'warning', confirmButtonText:'<?php echo label('lrn_btn_ok'); ?>'}).then(function(){ location.reload(); });
            else swal({title:'<?php echo label('enroll_success'); ?>', type:'success', confirmButtonText:'<?php echo label('lrn_btn_ok'); ?>'}).then(function(){ location.reload(); });
        }});
    });
});
</script>
</body>
</html>
