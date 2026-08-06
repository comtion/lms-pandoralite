<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');
$arrMonthThaiTextShort = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย", "ธ.ค.");
$arrMonthThaiTextFull = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
?>


<!-- chartist CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
<!-- page css -->
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/ribbon-page.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/plugins/css-chart/css-chart.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/easy-pie-chart.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/dashboard.css">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-premium.css?v=20260720-1">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-precision.css?v=20260806-4">
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/css/dashboard-sidebar-v2.css?v=20260720-15">
<!-- Timeline CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/horizontal-timeline/css/horizontal-timeline.css" rel="stylesheet">

<!-- page css -->
<link href="<?php echo REAL_PATH; ?>/assets/css/pages/timeline-vertical-horizontal.css" rel="stylesheet">


<link rel="stylesheet" href="<?php echo REAL_PATH; ?>/assets/cdn/roundslider.min.css">

<style type="text/css">
	.dt-head-center {
		text-align: center;
	}

	.dataTables_scrollBody {
		overflow-x: hidden !important;
	}

	.modal-body {
		max-height: max-content !important;
		overflow-y: hidden !important;
	}
</style>
</head>

<body class="fix-header fix-sidebar card-no-border lms-premium-dashboard">
	<div class="preloader">
		<div class="loader precision-loader">
			<div class="precision-loader-brand">
				<img src="<?php echo REAL_PATH; ?>/images/logo.png" alt="ISUZU Thailand">
				<span></span>
				<img src="<?php echo REAL_PATH; ?>/images/elearning_logo.png" alt="E-Learning">
			</div>
			<div class="loader__figure"></div>
			<p class="loader__label">
				<?php if ($lang == "thai") {
					echo $foote[0]['da_title_th'];
				} else {
					echo $foote[0]['da_title_en'];
				} ?></p>
			<div class="precision-loader-progress" aria-hidden="true"><span></span></div>
			<small><?php echo $lang === 'thai' ? 'กำลังเตรียมพื้นที่การเรียนรู้ของคุณ' : 'Preparing your learning workspace'; ?></small>
		</div>
	</div>
	<div id="main-wrapper">

		<?php $this->load->view('frontend/inc/inc-header.php'); ?>

		<?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>

		<div class="page-wrapper">
			<div class="container-fluid" id="dashboard_content">
				<?php
				$precisionName = $lang === 'thai'
					? ($profile['fullname_th'] ?? $profile['fullname_en'] ?? '')
					: ($profile['fullname_en'] ?? $profile['fullname_th'] ?? '');
				$precisionDate = $lang === 'thai'
					? date('d/m/') . (date('Y') + 543)
					: date('d M Y');
				?>
				<section class="precision-dashboard" aria-label="<?php echo $lang === 'thai' ? 'ภาพรวมการเรียนรู้' : 'Learning overview'; ?>">
					<div class="precision-welcome">
						<div class="precision-welcome-copy">
							<span class="precision-accent" aria-hidden="true"></span>
							<div>
								<p class="precision-kicker"><?php echo $lang === 'thai' ? 'LEARNING WORKSPACE' : 'LEARNING WORKSPACE'; ?></p>
								<h1><?php echo $lang === 'thai' ? 'ยินดีต้อนรับกลับ, ' : 'Welcome back, '; ?><?php echo htmlspecialchars($precisionName, ENT_QUOTES, 'UTF-8'); ?></h1>
								<p><?php echo $lang === 'thai' ? 'พร้อมขับเคลื่อนการเรียนรู้ สู่มาตรฐานบริการระดับมืออาชีพ' : 'Keep building capability toward a professional service standard.'; ?></p>
								<div class="precision-date-row">
									<span><i class="mdi mdi-calendar-blank"></i><?php echo $precisionDate; ?></span>
									<span><i class="mdi mdi-clock" aria-hidden="true"></i><strong id="precisionClock"><?php echo date('H:i'); ?></strong></span>
								</div>
							</div>
						</div>
					</div>

					<section class="precision-current" aria-labelledby="precisionCurrentTitle">
						<header><span class="precision-section-index">01</span><h2 id="precisionCurrentTitle"><?php echo $lang === 'thai' ? 'หลักสูตรที่กำลังเรียน' : 'Continue learning'; ?></h2></header>
						<div class="precision-course-layout">
							<img class="precision-course-image" src="<?php echo REAL_PATH; ?>/images/dashboard-dmax-course.png" alt="ISUZU D-MAX">
							<div class="precision-course-copy">
								<span class="precision-status"><i class="mdi mdi-radiobox-marked"></i><?php echo $lang === 'thai' ? 'กำลังเรียน' : 'In progress'; ?></span>
								<h3><?php echo $lang === 'thai' ? 'ความรู้พื้นฐานผลิตภัณฑ์ ISUZU D-MAX' : 'ISUZU D-MAX Product Fundamentals'; ?></h3>
								<p><?php echo $lang === 'thai' ? 'หมวดหมู่: ผลิตภัณฑ์และเทคโนโลยี · เรียนรู้เพื่อยกระดับความเชี่ยวชาญ' : 'Product & Technology · Build practical product expertise'; ?></p>
								<div class="precision-progress-head"><span><?php echo $lang === 'thai' ? 'ความก้าวหน้าในการเรียน' : 'Course progress'; ?></span><strong>68%</strong></div>
								<div class="precision-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="68"><span></span></div>
								<div class="precision-progress-meta"><span><?php echo $lang === 'thai' ? 'เรียนไปแล้ว 6 จาก 9 บทเรียน' : '6 of 9 lessons completed'; ?></span><span><?php echo $lang === 'thai' ? 'ใช้เวลาเรียน 2 ชม. 45 นาที' : '2h 45m learning time'; ?></span></div>
							</div>
							<div class="precision-course-actions">
								<a class="precision-primary" href="<?php echo REAL_PATH; ?>/coursemain/my_course"><?php echo $lang === 'thai' ? 'เรียนต่อ' : 'Continue'; ?><i class="mdi mdi-arrow-right"></i></a>
								<a class="precision-secondary" href="<?php echo REAL_PATH; ?>/coursemain/my_course"><i class="mdi mdi-bookmark-outline"></i><?php echo $lang === 'thai' ? 'ดูหลักสูตรของฉัน' : 'My courses'; ?></a>
							</div>
						</div>
					</section>

					<div class="precision-overview-grid">
						<section class="precision-metrics" aria-labelledby="precisionMetricsTitle">
							<header><span class="precision-section-index">02</span><h2 id="precisionMetricsTitle"><?php echo $lang === 'thai' ? 'ภาพรวมการเรียนรู้' : 'Learning overview'; ?></h2></header>
							<div class="precision-metric-grid">
								<div class="precision-metric is-red"><span><?php echo $lang === 'thai' ? 'หลักสูตรทั้งหมด' : 'All courses'; ?></span><strong data-precision-value="courses_total">0</strong><i class="mdi mdi-book-open-page-variant"></i></div>
								<div class="precision-metric is-green"><span><?php echo $lang === 'thai' ? 'หลักสูตรที่เปิดอยู่' : 'Open courses'; ?></span><strong data-precision-value="courses_ongoing">0</strong><i class="mdi mdi-play-circle-outline"></i></div>
								<div class="precision-metric is-gold"><span><?php echo $lang === 'thai' ? 'หลักสูตรที่กำลังจะเปิด' : 'Upcoming'; ?></span><strong data-precision-value="courses_incoming">0</strong><i class="mdi mdi-calendar-blank"></i></div>
								<div class="precision-metric is-gray"><span><?php echo $lang === 'thai' ? 'หลักสูตรที่ปิดแล้ว' : 'Closed'; ?></span><strong data-precision-value="courses_close">0</strong><i class="mdi mdi-lock-outline"></i></div>
							</div>
							<div class="precision-achievement"><i class="mdi mdi-trophy-award"></i><div><strong><?php echo $lang === 'thai' ? 'รักษามาตรฐานการเรียนรู้อย่างต่อเนื่อง' : 'Keep your learning momentum'; ?></strong><span><?php echo $lang === 'thai' ? 'คุณอยู่ในกลุ่มผู้เรียนที่มีความสม่ำเสมอ' : 'You are among the most consistent learners'; ?></span></div><a href="<?php echo REAL_PATH; ?>/report/loadreport_personal"><?php echo $lang === 'thai' ? 'ดูรายงาน' : 'View report'; ?><i class="mdi mdi-arrow-right"></i></a></div>
						</section>
						<section class="precision-device" aria-labelledby="precisionDeviceTitle">
							<header><span class="precision-section-index">03</span><h2 id="precisionDeviceTitle"><?php echo $lang === 'thai' ? 'การใช้งานระบบ' : 'Device usage'; ?></h2><span><?php echo $lang === 'thai' ? '7 วันที่ผ่านมา' : 'Last 7 days'; ?></span></header>
							<div class="precision-device-body">
								<div class="precision-donut" style="--desktop: <?php echo max(1, (int) $PC_log); ?>; --tablet: <?php echo max(0, (int) $Tablet_log); ?>; --mobile: <?php echo max(0, (int) $Mobile_log); ?>;"><div><strong><?php echo number_format((int) $PC_log + (int) $Tablet_log + (int) $Mobile_log); ?></strong><span><?php echo $lang === 'thai' ? 'เซสชันรวม' : 'sessions'; ?></span></div></div>
								<ul><li><i class="mdi mdi-laptop"></i><span><?php echo $lang === 'thai' ? 'เดสก์ท็อป' : 'Desktop'; ?></span><strong><?php echo number_format((int) $PC_log); ?></strong></li><li><i class="mdi mdi-cellphone-android"></i><span><?php echo $lang === 'thai' ? 'มือถือ' : 'Mobile'; ?></span><strong><?php echo number_format((int) $Mobile_log); ?></strong></li><li><i class="mdi mdi-tablet-ipad"></i><span><?php echo $lang === 'thai' ? 'แท็บเล็ต' : 'Tablet'; ?></span><strong><?php echo number_format((int) $Tablet_log); ?></strong></li></ul>
							</div>
						</section>
					</div>
					<nav class="precision-shortcuts" aria-label="<?php echo $lang === 'thai' ? 'ทางลัด' : 'Shortcuts'; ?>">
						<strong><?php echo $lang === 'thai' ? 'ทางลัด' : 'Shortcuts'; ?></strong>
						<div><a href="<?php echo REAL_PATH; ?>/course/available"><i class="mdi mdi-magnify"></i><span><?php echo $lang === 'thai' ? 'ค้นหาหลักสูตร' : 'Find courses'; ?></span><i class="mdi mdi-chevron-right"></i></a><a href="<?php echo REAL_PATH; ?>/coursemain/my_course"><i class="mdi mdi-book-open-outline"></i><span><?php echo $lang === 'thai' ? 'แผนการเรียนของฉัน' : 'My learning plan'; ?></span><i class="mdi mdi-chevron-right"></i></a><a href="<?php echo REAL_PATH; ?>/dashboard/profile/certificate"><i class="mdi mdi-certificate"></i><span><?php echo $lang === 'thai' ? 'ดาวน์โหลดใบประกาศ' : 'Certificates'; ?></span><i class="mdi mdi-chevron-right"></i></a><a href="<?php echo REAL_PATH; ?>/faq"><i class="mdi mdi-headset"></i><span><?php echo $lang === 'thai' ? 'ช่วยเหลือ' : 'Help'; ?></span><i class="mdi mdi-chevron-right"></i></a></div>
					</nav>
				</section>
				<div class="row page-titles">
					<div class="legacy-dashboard-intro"><?php $this->load->view('frontend/detail/dashboard_detail.php'); ?></div>
					<?php if (in_array('6', $arr_role_fd) && (count($fetch_course_approve) > 0 || count($fetch_survey_public_approve) > 0)) { ?>
						<div class="col-lg-12">
							<!-- Admin Approve -->
							<div class="card card-body" id="admin_approve">
								<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_b_da_approve'); ?></h4>
								<?php if (count($fetch_course_approve) > 0) { ?>
									<div class="table-responsive">
										<table id="survey_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th style="width: 800px !important;"><b><?php echo label('dash_b_course_name'); ?></b></th>
													<th><b><?php echo label('dash_b_da_approve_creator'); ?></b></th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($fetch_course_approve as $key_cos => $value_cos) {
													$cname = "";
													if ($lang == "thai") {
														if ($value_cos['isTH'] == "1") {
															$cname = $value_cos['cname_th'];
														} else {
															if ($value_cos['cname_th'] == "") {
																$cname = $value_cos['cname_eng'];
															}
															if ($cname == "") {
																$cname = $value_cos['cname_jp'];
															}
														}
													} else if ($lang == "english") {
														if ($value_cos['isENG'] == "1") {
															$cname = $value_cos['cname_eng'];
														} else {
															if ($value_cos['cname_eng'] == "") {
																$cname = $value_cos['cname_jp'];
															}
															if ($cname == "") {
																$cname = $value_cos['cname_th'];
															}
														}
													} else {
														if ($value_cos['isJP'] == "1") {
															$cname = $value_cos['cname_jp'];
														} else {
															if ($value_cos['cname_jp'] == "") {
																$cname = $value_cos['cname_eng'];
															}
															if ($cname == "") {
																$cname = $value_cos['cname_th'];
															}
														}
													}
												?>
													<tr>
														<td title="<?php echo $cname; ?>"><?php echo $cname; ?></td>
														<td><?php echo $value_cos['user_creator']; ?></td>
														<td class="text-right">
															<button type="button" id="<?php echo $value_cos['cos_id']; ?>" class="btn btn-xs btn-primary viewcos" style="background-color: #745af2;" title="<?php echo label('dashboard_preview'); ?>">
																<i class="mdi mdi-eye"></i>
															</button>
															<button type="button" id="<?php echo $value_cos['cos_id']; ?>" class="btn btn-xs btn-secondary active approve_cos" title="<?php echo label('d_approve'); ?>">
																<i class="mdi mdi-alert text-warning"></i>
															</button>
															<!-- <a type="button" href="<?php echo REAL_PATH . '/managecourse/courses_demo/' . $value_cos['cos_id']; ?>" class="btn mdi-btn btn-warning" title="<?php echo label('dash_b_go_to_course'); ?>">
                                         <i class="mdi mdi-share mdi-light"></i></span>
                                        </a> -->
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<hr>
								<?php } ?>
								<?php if (count($fetch_survey_public_approve) > 0) { ?>
									<div class="table-responsive">
										<table id="survey_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th style="width: 800px !important;"><b><?php echo label('dash_b_survey_name'); ?></b></th>
													<th><b><?php echo label('dash_b_da_approve_creator'); ?></b></th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($fetch_survey_public_approve as $key_survey_public => $value_survey_public) {

													if ($lang == "thai") {
														$sv_title = $value_survey_public['sv_title_th'] != "" ? $value_survey_public['sv_title_th'] : $value_survey_public['sv_title_eng'];
														$sv_title = $sv_title != "" ? $sv_title : $value_survey_public['sv_title_jp'];
													} else if ($lang == "english") {
														$sv_title = $value_survey_public['sv_title_eng'] != "" ? $value_survey_public['sv_title_eng'] : $value_survey_public['sv_title_th'];
														$sv_title = $sv_title != "" ? $sv_title : $value_survey_public['sv_title_jp'];
													} else {
														$sv_title = $value_survey_public['sv_title_jp'] != "" ? $value_survey_public['sv_title_jp'] : $value_survey_public['sv_title_eng'];
														$sv_title = $sv_title != "" ? $sv_title : $value_survey_public['sv_title_th'];
													}
												?>
													<tr>
														<td title="<?php echo $sv_title; ?>"><?php echo $sv_title; ?></td>
														<td><?php echo $value_survey_public['user_creator']; ?></td>
														<td class="text-right">
															<button type="button" id="<?php echo $value_survey_public['sv_id']; ?>" class="btn btn-xs btn-primary viewpublicsv" style="background-color: #745af2;" title="<?php echo label('dashboard_preview'); ?>">
																<i class="mdi mdi-eye"></i>
															</button>
															<button type="button" id="<?php echo $value_survey_public['sv_id']; ?>" class="btn btn-xs btn-secondary active approve_psv" title="<?php echo label('d_waitapprove'); ?>">
																<i class="mdi mdi-alert text-warning"></i>
															</button>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								<?php } ?>
								<p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button> = <b><?php echo label('dashboard_preview'); ?></b> , <button type="button" class="btn btn-secondary btn-xs active"><i class="mdi mdi-alert text-warning"></i></button> = <b><?php echo label('d_waitapprove'); ?></b>
								</p>
							</div>
						</div>
					<?php } ?>
					<div class="col-lg-12">
						<div class="row">
							<?php if (count($rechk_approve_cog) > 0) { ?>
								<div class="col-md-12" id="cog_admin">
									<div class="card card-body">
										<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_b_da_approve'); ?></h4>
										<div class="table-responsive">
											<table id="survey_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="width: 400px !important;"><b><?php echo label('cgtitle'); ?></b></th>
														<th style="width: 400px !important;"><b><?php echo label('cgdesc'); ?></b></th>
														<th style="width: 250px !important;"><b><?php echo label('create_date'); ?></b></th>
														<th><b></b></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($rechk_approve_cog as $key_approve_cog => $value_approve_cog) {

														$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
														if ($lang == "thai") {
															$cgtitle = $value_approve_cog['cgtitle_th'] != "" ? $value_approve_cog['cgtitle_th'] : $value_approve_cog['cgtitle_en'];
															$cgtitle = $cgtitle != "" ? $cgtitle : $value_approve_cog['cgtitle_jp'];
															$cgdesc = $value_approve_cog['cgdesc_th'] != "" ? $value_approve_cog['cgdesc_th'] : $value_approve_cog['cgdesc_en'];
															$cgdesc = $cgdesc != "" ? $cgdesc : $value_approve_cog['cgdesc_jp'];
														} else if ($lang == "english") {
															$cgtitle = $value_approve_cog['cgtitle_en'] != "" ? $value_approve_cog['cgtitle_en'] : $value_approve_cog['cgtitle_th'];
															$cgtitle = $cgtitle != "" ? $cgtitle : $value_approve_cog['cgtitle_jp'];
															$cgdesc = $value_approve_cog['cgdesc_en'] != "" ? $value_approve_cog['cgdesc_en'] : $value_approve_cog['cgdesc_th'];
															$cgdesc = $cgdesc != "" ? $cgdesc : $value_approve_cog['cgdesc_jp'];
														} else {
															$cgtitle = $value_approve_cog['cgtitle_jp'] != "" ? $value_approve_cog['cgtitle_jp'] : $value_approve_cog['cgtitle_en'];
															$cgtitle = $cgtitle != "" ? $cgtitle : $value_approve_cog['cgtitle_th'];
															$cgdesc = $value_approve_cog['cgdesc_jp'] != "" ? $value_approve_cog['cgdesc_jp'] : $value_approve_cog['cgdesc_en'];
															$cgdesc = $cgdesc != "" ? $cgdesc : $value_approve_cog['cgdesc_th'];
														}
														if ($lang == "thai") {
															$c_date = $value_approve_cog['c_date'] != "0000-00-00 00:00:00" ? date('d', strtotime($value_approve_cog['c_date'])) . " " . $thaimonth[intval(date('m', strtotime($value_approve_cog['c_date'])))] . " " . (date('Y', strtotime($value_approve_cog['c_date'])) + 543) . " " . date('H:i', strtotime($value_approve_cog['c_date'])) : '<center>-</center>';
															//$c_date = $value_approve_cog['c_date']!="0000-00-00 00:00:00"?date('d/m',strtotime($value_approve_cog['c_date']))."/".(date('Y',strtotime($value_approve_cog['c_date']))+543)." ".date('H:i',strtotime($value_approve_cog['c_date'])):'<center>-</center>';
														} else {
															$c_date = $value_approve_cog['c_date'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($value_approve_cog['c_date'])) : '<center>-</center>';
															//$c_date = $value_approve_cog['c_date']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value_approve_cog['c_date'])):'<center>-</center>';
														}
													?>
														<tr>
															<td title="<?php echo $cgtitle; ?>"><?php echo $cgtitle; ?></td>
															<td title="<?php echo $cgdesc; ?>"><?php echo $cgdesc; ?></td>
															<td><?php echo $c_date; ?></td>
															<td class="text-right">
																<button type="button" id="<?php echo $value_approve_cog['cg_id']; ?>" class="btn btn-xs btn-primary viewcog" style="background-color: #745af2;" title="<?php echo label('dashboard_preview'); ?>">
																	<i class="mdi mdi-eye"></i>
																</button>
																<button type="button" id="<?php echo $value_approve_cog['cg_id']; ?>" class="btn btn-xs btn-secondary active approve_cog" title="<?php echo label('d_approve'); ?>">
																	<i class="mdi mdi-alert text-warning"></i>
																</button>

																<!-- <button type="button" id="<?php echo $value_approve_cog['cg_id']; ?>" class="btn mdi-btn btn-success approve_cog" title="<?php echo label('d_approve'); ?>">
                                            <span class="icon is-medium"><i class="mdi mdi-24px mdi-check mdi-light"></i> <?php echo label('d_approve'); ?></span>
                                          </button> -->
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<p><?php echo label('preNote'); ?>: <button type="button" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button> = <b><?php echo label('dashboard_preview'); ?></b> , <button type="button" class="btn btn-secondary btn-xs active"><i class="mdi mdi-alert text-warning"></i></button> =
											<b><?php echo label('d_waitapprove'); ?></b>
										</p>
									</div>
								</div>
							<?php } ?>

							<?php if (count($arr_surveypublic) > 0) { ?>
								<div class="col-md-12" id="survey_admin_learner">
									<div class="card card-body">
										<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_questionnaire'); ?></h4>
										<div class="table-responsive">
											<table id="survey_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th><b><?php echo label('dash_b_survey_name'); ?></b></th>
														<th style="width: 250px;"><b><?php echo label('dash_b_close_date'); ?></b></th>
														<th><b></b></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($arr_surveypublic as $key_surveypublic => $value_surveypublic) {

														if ($lang == "thai") {
															$sv_title = $value_surveypublic['sv_title_th'] != "" ? $value_surveypublic['sv_title_th'] : $value_surveypublic['sv_title_eng'];
															$sv_title = $sv_title != "" ? $sv_title : $value_surveypublic['sv_title_jp'];
														} else if ($lang == "english") {
															$sv_title = $value_surveypublic['sv_title_eng'] != "" ? $value_surveypublic['sv_title_eng'] : $value_surveypublic['sv_title_th'];
															$sv_title = $sv_title != "" ? $sv_title : $value_surveypublic['sv_title_jp'];
														} else {
															$sv_title = $value_surveypublic['sv_title_jp'] != "" ? $value_surveypublic['sv_title_jp'] : $value_surveypublic['sv_title_eng'];
															$sv_title = $sv_title != "" ? $sv_title : $value_surveypublic['sv_title_th'];
														} ?>
														<tr>
															<td title="<?php echo $sv_title; ?>"><?php echo $sv_title; ?></td>
															<td><?php echo $value_surveypublic['sv_end']; ?></td>
															<td>
																<a type="button" href="<?php echo REAL_PATH . '/survey/surveyDetail/' . $value_surveypublic['sv_id']; ?>" class="btn mdi-btn waves-effect waves-light btn-warning" title="<?php echo label('dash_b_go_to_survey'); ?>">
																	<span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span>
																</a>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if (in_array('4', $arr_role_fd)) { ?>
								<div class="col-xl-4 col-md-12 precision-data-source" aria-hidden="true">
									<div class="card" id="log_visit">
										<div class="card-body">
											<div class="d-flex no-block">
												<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_access_system'); ?>
												</h4>
											</div>
											<div class="p-20">
												<canvas id="chart3" height="210"></canvas>
											</div>
										</div>
									</div>

								</div>
							<?php } ?>

							<?php if (in_array('5', $arr_role_fd)) { ?>
								<div class="precision-data-source <?php if (in_array('4', $arr_role_fd)) { ?>col-md-12 col-xl-8<?php } else { ?>col-md-12 col-xl-12<?php } ?>" id="div_status_cos" aria-hidden="true">
									<div class="card card-body">
										<div class="card-title">
											<div class="row">
												<div class="col-xl-6 col-md-12">
													<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_course_information'); ?></h4>
												</div>
												<div class="col-md-12 col-xl-6" id="div_company">
													<?php if ($com_admin != "com_associated" && $user['ug_id'] == "1") { ?>
														<div class="form-group mb-0">
															<select class="custom-select select2 b-0 col-12" id="com_id_search" onchange="onchange_company(this.value)" name="com_id_search" style="width: 100%;">
																<?php if (count($company_arr) > 0) { ?>
																	<option value="" selected><?php echo label('allcompany'); ?></option>
																	<?php $numloop = 1;
																	foreach ($company_arr as $key_com => $value_com) { ?>
																		<option value="<?php echo $value_com['com_id']; ?>">
																			<?php echo $lang == "thai" ? $value_com['com_name_th'] : $value_com['com_name_eng']; ?>
																		</option>
																	<?php   } ?>
																<?php   } ?>
															</select>
														</div>
													<?php } else { ?>
														<input type="hidden" id="com_id_search" name="com_id_search" value="<?php echo $com_id; ?>">
													<?php } ?>
												</div>
											</div>
											<hr>
										</div>

										<div class="card-body">
											<div class="row" style="margin:auto;">
												<div class="col-lg-auto col-md-6 m-auto">
													<div class="text-center">
														<input data-plugin="knob" id="courses_total" data-width="140" data-height="140" data-linecap="round" data-fgColor="#ec2029" value="0" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".125" /><small class="knob-text"><?php echo label('dash_b_total'); ?></small>
													</div>
												</div>
												<div class="col-lg-auto col-md-6 m-auto">
													<div class="text-center">
														<input data-plugin="knob" id="courses_ongoing" data-width="140" data-height="140" data-linecap="round" data-fgColor="#81f880" value="0" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".125" /><small class="knob-text"><?php echo label('dash_b_on_going'); ?></small>
													</div>
												</div>
												<div class="col-lg-auto col-md-6 m-auto">
													<div class="text-center">
														<input data-plugin="knob" id="courses_incoming" data-width="140" data-height="140" data-linecap="round" data-fgColor="#f9ef01" value="0" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".125" /><small class="knob-text"><?php echo label('dash_b_incoming'); ?></small>
													</div>
												</div>
												<div class="col-lg-auto col-md-6 m-auto div_cos_completed" style="display: none;">
													<div class="text-center">
														<input data-plugin="knob" id="courses_completed" data-width="140" data-height="140" data-linecap="round" data-fgColor="#9c9fa4" value="0" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".125" /><small class="knob-text"><?php echo label('dash_b_completed'); ?></small>
													</div>
												</div>
												<div class="col-lg-auto col-md-6 m-auto div_cos_close" style="display: none;">
													<div class="text-center">
														<input data-plugin="knob" id="courses_close" data-width="140" data-height="140" data-linecap="round" data-fgColor="#9c9fa4" value="0" data-skin="tron" data-angleOffset="0" data-readOnly=true data-thickness=".125" /><small class="knob-text"><?php echo label('dash_b_closed'); ?></small>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>
							<?php } ?>
						</div>

						<?php if ($user['ug_id'] == "1") { ?>
							<!-- Comany Active User -->
							<div class="card card-body" id="active_user_admin">
								<?php
								$usage_company_total = count($company_arr);
								$usage_user_total = array_sum(array_column($company_arr, 'usertotal'));
								$usage_course_total = array_sum(array_column($company_arr, 'coursetotal'));
								$usage_survey_total = array_sum(array_column($company_arr, 'surveytotal'));
								$usage_company_divisor = max(1, $usage_company_total);
								?>
								<div class="usage-section-heading">
									<div>
										<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_usage_information'); ?></h4>
										<p><?php echo $lang == 'thai' ? 'ภาพรวมจำนวนผู้ใช้งาน หลักสูตร และแบบสำรวจ แยกตามบริษัท' : 'An overview of users, courses and surveys by company'; ?></p>
									</div>
									<span class="usage-company-count"><i class="mdi mdi-domain"></i><?php echo number_format($usage_company_total); ?> <?php echo $lang == 'thai' ? 'บริษัท' : 'companies'; ?></span>
								</div>
								<div class="usage-summary-grid" aria-label="<?php echo label('dash_h_usage_information'); ?>">
									<div class="usage-summary-item is-company"><span><i class="mdi mdi-domain"></i></span><div><small><?php echo $lang == 'thai' ? 'บริษัททั้งหมด' : 'Total companies'; ?></small><strong><?php echo number_format($usage_company_total); ?></strong><em><?php echo $lang == 'thai' ? 'องค์กรในระบบ' : 'organizations in the system'; ?></em></div></div>
									<div class="usage-summary-item is-user"><span><i class="mdi mdi-account-multiple-outline"></i></span><div><small><?php echo label('dash_b_user_total'); ?></small><strong><?php echo number_format($usage_user_total); ?></strong><em><?php echo $lang == 'thai' ? 'เฉลี่ย' : 'Avg.'; ?> <?php echo number_format($usage_user_total / $usage_company_divisor, 0); ?> / <?php echo $lang == 'thai' ? 'บริษัท' : 'company'; ?></em></div></div>
									<div class="usage-summary-item is-course"><span><i class="mdi mdi-book-open-page-variant"></i></span><div><small><?php echo label('dash_b_total_course'); ?></small><strong><?php echo number_format($usage_course_total); ?></strong><em><?php echo $lang == 'thai' ? 'เฉลี่ย' : 'Avg.'; ?> <?php echo number_format($usage_course_total / $usage_company_divisor, 0); ?> / <?php echo $lang == 'thai' ? 'บริษัท' : 'company'; ?></em></div></div>
									<div class="usage-summary-item is-survey"><span><i class="mdi mdi-clipboard-text-outline"></i></span><div><small><?php echo label('dash_b_total_survey'); ?></small><strong><?php echo number_format($usage_survey_total); ?></strong><em><?php echo $lang == 'thai' ? 'เฉลี่ย' : 'Avg.'; ?> <?php echo number_format($usage_survey_total / $usage_company_divisor, 0); ?> / <?php echo $lang == 'thai' ? 'บริษัท' : 'company'; ?></em></div></div>
								</div>
								<div class="usage-table-shell">
									<div class="usage-table-heading">
										<div><i class="mdi mdi-format-list-bulleted"></i><span><?php echo $lang == 'thai' ? 'รายละเอียดตามบริษัท' : 'Company breakdown'; ?></span></div>
										<small><?php echo $lang == 'thai' ? 'แสดง 10 รายการต่อหน้า' : '10 items per page'; ?></small>
									</div>
								<table id="company_active_user_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="55%"><b><i class="mdi mdi-domain"></i><?php echo label('dash_b_company_name'); ?></b></th>
											<th width="15%"><b><i class="mdi mdi-account-multiple-outline"></i><?php echo label('dash_b_user_total'); ?></b></th>
											<th width="15%"><b><i class="mdi mdi-book-open-page-variant"></i><?php echo label('dash_b_total_course'); ?></b></th>
											<th width="15%"><b><i class="mdi mdi-clipboard-text-outline"></i><?php echo label('dash_b_total_survey'); ?></b></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($company_arr as $key_com => $value_com) {
											$com_name = $lang == "thai" ? $value_com['com_name_th'] : $value_com['com_name_eng'];
											$company_user_total = (int) $value_com['usertotal'];
											$company_course_total = (int) $value_com['coursetotal'];
											$company_survey_total = (int) $value_com['surveytotal'];
										?>
											<tr>
												<td title="<?php echo $com_name; ?>"><span class="usage-company-name"><i class="mdi mdi-domain"></i><span><?php echo $com_name; ?></span></span></td>
												<td align="right"><span class="usage-value is-user"><strong><?php echo number_format($company_user_total); ?></strong><small><?php echo number_format(($company_user_total / max(1, $usage_user_total)) * 100, 1); ?>%</small></span></td>
												<td align="right"><span class="usage-value is-course"><strong><?php echo number_format($company_course_total); ?></strong><small><?php echo number_format(($company_course_total / max(1, $usage_course_total)) * 100, 1); ?>%</small></span></td>
												<td align="right"><span class="usage-value is-survey"><strong><?php echo number_format($company_survey_total); ?></strong><small><?php echo number_format(($company_survey_total / max(1, $usage_survey_total)) * 100, 1); ?>%</small></span></td>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td><span class="usage-total-label"><i class="mdi mdi-sigma"></i><?php echo $lang == 'thai' ? 'รวมทั้งหมด' : 'Grand total'; ?></span></td>
											<td align="right"><?php echo number_format($usage_user_total); ?></td>
											<td align="right"><?php echo number_format($usage_course_total); ?></td>
											<td align="right"><?php echo number_format($usage_survey_total); ?></td>
										</tr>
									</tfoot>
								</table>
								</div>
							</div>
						<?php } ?>
						<div class="row">
							<!-- On-Going Course Table -->
							<div class="col-md-12" id="ongoing_div">
								<div class=" card card-body" style="min-height: 550px !important; max-height: 550px !important;">
									<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_ongoing_course'); ?>
									</h4>
									<table id="ongoing_course_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><b><?php echo label('dash_b_course_name'); ?></b></th>
												<th><b><?php echo label('dash_b_close_date'); ?></b></th>
												<th><b></b></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>

							<!-- Incoming Course Table -->
							<div class="col-md-12" id="incoming_div">
								<div class="card card-body" style="min-height: 550px !important; max-height: 550px !important;">
									<h4 class="card-title"><span class="lstick"></span><?php echo label('dash_h_incoming_courses'); ?>
									</h4>
									<table id="incoming_course_table" class="display table table-hover table-ellipsis-350px" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><b><?php echo label('dash_b_course_name'); ?></b></th>
												<th><b><?php echo label('sv_b_start_on'); ?></b></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid" id="pdpa_content" style="display:none">
				<?php include_once("pdpa_content.php"); ?>
			</div>
		</div>
	</div>
	<?php $this->load->view('frontend/modal/modal_dashboard.php'); ?>

	<div id="myModal_process" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-body" align="center">
					<img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
					<br>
					<h3 style="color: black;"><?php echo label('please_wait'); ?></h3>
				</div>
			</div>
		</div>
	</div>

	<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>

	<div class="modal fade bs-example-modal-lg" id="modal-previewcog" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4><?php echo label('coursegroup'); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form method="post" id="course_group_form" autocomplete="off" name="course_group_form" enctype="multipart/form-data" class="form-horizontal" role="form">
					<div class="modal-body row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cgtitle_th"><b style="color: #FF2D00">*</b><?php echo label('cgtitle') . " (" . label('TH') . ")"; ?>:</label>
								<input type="text" id="cgtitle_th" name="cgtitle_th" class="form-control" readonly>
							</div>
							<div class="form-group">
								<label><?php echo label('cgdesc') . " (" . label('TH') . ")"; ?>:</label>
								<textarea class="form-control" rows="4" id="cgdesc_th" name="cgdesc_th" readonly></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cgtitle_en"><b style="color: #FF2D00">*</b><?php echo label('cgtitle') . " (" . label('EN') . ")"; ?>:</label>
								<input type="text" id="cgtitle_en" name="cgtitle_en" class="form-control" readonly>
							</div>
							<div class="form-group">
								<label><?php echo label('cgdesc') . " (" . label('EN') . ")"; ?>:</label>
								<textarea class="form-control" rows="4" id="cgdesc_en" name="cgdesc_en" readonly></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cgtitle_jp"><b style="color: #FF2D00">*</b><?php echo label('cgtitle') . " (" . label('JP') . ")"; ?>:</label>
								<input type="text" id="cgtitle_jp" name="cgtitle_jp" class="form-control" readonly>
							</div>
							<div class="form-group">
								<label><?php echo label('cgdesc') . " (" . label('JP') . ")"; ?>:</label>
								<textarea class="form-control" rows="4" id="cgdesc_jp" name="cgdesc_jp" readonly></textarea>
							</div>
						</div>
						<!-- <div class="col-md-6">
                  <div class="form-group" id="div_cog">
                    <label><?php echo label('cgthumb'); ?>:</label>
                    <img src="" id="cgthumb" style="width: 100%">
                  </div>
                </div> -->

					</div>
					<input type="hidden" id="cg_id" name="cg_id">
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>

	<script src="<?php echo REAL_PATH; ?>/assets/plugins/Chart.js/Chart.min.js"></script>

	<script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js">
	</script>
	<!--c3 JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/d3/d3.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/c3-master/c3.min.js"></script>
	<!--morris JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-js/dist/chartist.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js">
	</script>
	<!-- Chart JS -->
	<script src="<?php echo REAL_PATH; ?>/assets/js/dashboard1.js"></script>
	<!-- EASY PIE CHART JS -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js">
	</script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/Chart.js/Chart.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/knob/jquery.knob.js"></script>

	<!-- Horizontal-timeline JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/horizontal-timeline/js/horizontal-timeline.js"></script>

	<?php //$this->load->view('frontend/java/dashboard_java.php'); 
	?>
	<script type="text/javascript">
		$(document).on('click', '.viewcog', function() {
			var cg_id = $(this).attr("id");

			$.ajax({
				url: "<?= base_url() ?>index.php/manage/update_coursegroup_data",
				method: "POST",
				data: {
					cg_id_update: cg_id
				},
				dataType: "json",
				success: function(data) {
					$("#modal-previewcog").modal({
						backdrop: false
					});

					/*if(data.cgthumb!=""){
					  $('#div_cog').show();
					  $('#cgthumb').attr('src',"<?php echo REAL_PATH; ?>/uploads/course_group/"+data.cgthumb);
					}else{
					  $('#div_cog').hide();
					}  */

					$('#cgtitle_th').val(data.cgtitle_th);
					$('#cgtitle_en').val(data.cgtitle_en);
					$('#cgtitle_jp').val(data.cgtitle_jp);
					$('#cgdesc_th').val(data.cgdesc_th);
					$('#cgdesc_en').val(data.cgdesc_en);
					$('#cgdesc_jp').val(data.cgdesc_jp);
					$('#cg_id').val(data.cg_id);
				}
			});

		});

		$(document).on('click', '.viewpublicsv', function() {
			var sv_id = $(this).attr("id");
			window.open('<?php echo base_url() . "survey/demo/"; ?>' + sv_id + '/1', '_blank');
		});

		$(document).on('click', '.viewcos', function() {
			var cos_id = $(this).attr("id");
			window.open('<?php echo base_url() . "managecourse/courses_demo/"; ?>' + cos_id + '/1', '_blank');
		});

		function ondisplay_chk(div_main, div_sub) {
			document.getElementById(div_main).style.display = "";
			document.getElementById(div_sub).style.display = "none";
		}
		/*$(document).on('click', '.approve_cog', function(){
		   var cg_id = $(this).attr("id");
		   swal({
		       title: '<?php echo label('approve_is_coursegroup'); ?>',
		       text: "",
		       type: 'warning',
		       showCancelButton: true,
		       confirmButtonColor: "#1abc9c",   
		       cancelButtonColor: "#DD6B55",   
		       confirmButtonText: '<?php echo label('d_approve'); ?>',
		       cancelButtonText: '<?php echo label('cancel'); ?>'
		   }).then(function (isChk) {
		     if(isChk.value){
		       $.ajax({
		           url:"<?= base_url() ?>index.php/manage/approve_cosgroup_data",
		           method:"POST",
		           data:{id_delete:cg_id},
		           success:function(data)
		           {
		             if(data == "2"){
		               swal(
		                   '<?php echo label("approve_msg_success"); ?>',
		                   '',
		                   'success'
		               ).then(function () {
		                     location.reload();
		               })
		             }else if(data == "1"){
		                swal({
		                   title: '<?php echo label("wg_msg_use"); ?>',
		                   text: "",
		                   type: 'warning',
		                   showCancelButton: false,
		                   confirmButtonClass: 'btn btn-primary',
		                   confirmButtonText: '<?php echo label('m_ok'); ?>'
		               })
		             }else{
		                swal({
		                   title: '<?php echo label('com_msg_error_save'); ?>',
		                   text: "",
		                   type: 'warning',
		                   showCancelButton: false,
		                   confirmButtonClass: 'btn btn-primary',
		                   confirmButtonText: '<?php echo label('m_ok'); ?>'
		               })
		             }
		           }
		       });
		     }
		   })
		 });*/
		function createButton(text, classs, style, id, cb) {
			return $(' <button class="' + classs + '" style="' + style + '" id="' + id + '">' + text + '</button>').on(
				'click',
				cb);
		}
		$(document).on('click', '.approve_psv', function(e) {
			var sv_id = $(this).attr("id");
			$.ajax({
				url: "<?= base_url() ?>index.php/querydata/rechk_survey_period",
				method: "POST",
				data: {
					sv_id: sv_id
				},
				dataType: "json",
				success: function(data) {

					var title_val = '';
					if (data.isApprove == "1") {
						title_val = '<?php echo label('approve_is'); ?>';
						var buttons = $('<div>')
							.append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
								'btn btn-flat btnapprove_psv', 'background-color:#1abc9c;', sv_id,
								function() {})).append(createButton(
								'<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
								'btn btn-flat btnreject_psv', 'background-color:#DD6B55;', sv_id,
								function() {
									swal.close();
								})).append(createButton('<?php echo label('cancel'); ?>', 'btn btn-flat btnrefresh', '', '',
								function() {
									swal.close();
								}));
					} else {
						title_val = '<?php echo label('cantapprove_is'); ?>';
						var buttons = $('<div>')
							.append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
								'btn btn-flat btnreject_psv', 'background-color:#DD6B55;', sv_id,
								function() {
									swal.close();
								})).append(createButton('<?php echo label('cancel'); ?>', 'btn btn-flat btnrefresh', '', '',
								function() {
									swal.close();
								}));
					}
					e.preventDefault();
					swal({
						title: title_val,
						html: buttons,
						type: "warning",
						showConfirmButton: false,
						showCancelButton: false
					});
				}
			});
		});

		$(document).on('click', '.btnapprove_psv', function(e) {
			e.preventDefault();
			var sv_id = $(this).attr("id");
			$("#myModal_process").modal('show');
			$(document.body).css('pointer-events', 'none');
			$.ajax({
				url: "<?= base_url() ?>index.php/manage/approve_survey_data",
				method: "POST",
				data: {
					sv_id: sv_id
				},
				xhr: function() {
					//document.getElementById("progress_div").style.display = "";
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							var progressBarOptions = {
								startAngle: -1.55,
								size: 200,
								value: percentComplete.toFixed(0),
								fill: {
									color: '#ffa500'
								}
							}
							console.log(percentComplete.toFixed(0));
							$('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(
								event, progress, stepValue) {
								$(this).find('strong').html("LOADING...<br/>" + percentComplete.toFixed(0) + "%");
							});

							$('#circle-b').circleProgress({
								value: percentComplete.toFixed(0),
								fill: {
									color: '#FF0000'
								}
							});
						}
					}, false);
					return xhr;
				},
				success: function(data) {
					$(document.body).css('pointer-events', '');
					$('#myModal_process').modal('hide');

					$("#myModal_process").removeClass("in");
					$("#myModal_process").css("display", "none");
					if (data == "2") {
						swal(
							'<?php echo label("approve_msg_success"); ?>',
							'',
							'success'
						).then(function() {
							location.reload();
						})
					} else if (data == "1") {
						swal({
							title: '<?php echo label("wg_msg_use"); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
						}).then(function() {
							location.reload();
						})
					} else {
						swal({
							title: '<?php echo label('com_msg_error_save'); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
						}).then(function() {
							location.reload();
						})
					}
				}
			});
		});


		$(document).on('click', '.btnreject_psv', function(e) {
			e.preventDefault();
			var sv_id = $(this).attr("id");
			swal({
				title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
				text: "",
				input: 'text',
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonColor: "#1abc9c",
				cancelButtonColor: "#DD6B55",
				confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>',
				inputPlaceholder: "<?php echo label('preDetail'); ?>: ",
				inputValidator: (value) => {
					if (!value) {
						// หากไม่กรอกข้อมูล
						return '<?php echo label("pls_enter_reason"); ?>';
					}
				}
			}).then(function(isChk) {
				if (isChk.value) {
					$("#myModal_process").modal({
						backdrop: false
					});
					$.ajax({
						url: "<?= base_url() ?>index.php/querydata/reject_publicsurvey",
						method: "POST",
						data: {
							sv_id: sv_id,
							sva_note: isChk.value
						},
						dataType: "json",
						success: function(data) {}
					});
					location.reload();
				}
				/* if (inputValue === "") {
				   swal.showInputError("You need to write something!");
				   return false
				 }else{
				 swal("Nice!", "You wrote: " + inputValue, "success");
				 }*/
			});
		});
		$(document).on('click', '.approve_cos', function(e) {
			var cos_id = $(this).attr("id");

			$.ajax({
				url: "<?= base_url() ?>index.php/querydata/rechk_course_period",
				method: "POST",
				data: {
					cos_id: cos_id
				},
				dataType: "json",
				success: function(data) {
					var title_val = '';
					if (data.isApprove == "1") {
						title_val = '<?php echo label('approve_is_course'); ?>';
						var buttons = $('<div>')
							.append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
								'btn btn-flat btnapprove_cos', 'background-color:#1abc9c;', cos_id,
								function() {})).append(createButton(
								'<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
								'btn btn-flat btnreject_cos', 'background-color:#DD6B55;', cos_id,
								function() {
									swal.close();
								})).append(createButton('<?php echo label('cancel'); ?>', 'btn btn-flat btnrefresh', '', '',
								function() {
									swal.close();
								}));
					} else {
						title_val = '<?php echo label('cantapprove_is_course'); ?>';
						var buttons = $('<div>')
							.append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
								'btn btn-flat btnreject_cos', 'background-color:#DD6B55;', cos_id,
								function() {
									swal.close();
								})).append(createButton('<?php echo label('cancel'); ?>', 'btn btn-flat btnrefresh', '', '',
								function() {
									swal.close();
								}));
					}
					e.preventDefault();
					swal({
						title: title_val,
						html: buttons,
						type: "warning",
						showConfirmButton: false,
						showCancelButton: false
					});
				}
			});
		});

		$(document).on('click', '.btnapprove_cos', function(e) {
			e.preventDefault();
			var cos_id = $(this).attr("id");
			$("#myModal_process").modal('show');
			$(document.body).css('pointer-events', 'none');
			$.ajax({
				url: "<?= base_url() ?>index.php/manage/approve_cos_data",
				method: "POST",
				data: {
					cos_id: cos_id
				},
				xhr: function() {
					//document.getElementById("progress_div").style.display = "";
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							var progressBarOptions = {
								startAngle: -1.55,
								size: 200,
								value: percentComplete.toFixed(0),
								fill: {
									color: '#ffa500'
								}
							}
							console.log(percentComplete.toFixed(0));
							$('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(
								event, progress, stepValue) {
								$(this).find('strong').html("LOADING...<br/>" + percentComplete.toFixed(0) + "%");
							});

							$('#circle-b').circleProgress({
								value: percentComplete.toFixed(0),
								fill: {
									color: '#FF0000'
								}
							});
						}
					}, false);
					return xhr;
				},
				success: function(data) {
					$(document.body).css('pointer-events', '');
					$('#myModal_process').modal('hide');

					$("#myModal_process").removeClass("in");
					$("#myModal_process").css("display", "none");
					if (data == "2") {
						swal(
							'<?php echo label("approve_msg_success"); ?>',
							'',
							'success'
						).then(function() {
							location.reload();
						})
					} else if (data == "1") {
						swal({
							title: '<?php echo label("wg_msg_use"); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('m_ok'); ?>'
						}).then(function() {
							location.reload();
						})
					} else {
						swal({
							title: '<?php echo label('com_msg_error_save'); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('m_ok'); ?>'
						}).then(function() {
							location.reload();
						})
					}
				}
			});
		});

		$(document).on('click', '.btnrefresh', function(e) {
			e.preventDefault();
			location.reload();
		});


		$(document).on('click', '.btnreject_cos', function(e) {
			e.preventDefault();
			var cos_id = $(this).attr("id");
			swal({
				title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
				text: "",
				input: 'text',
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonColor: "#1abc9c",
				cancelButtonColor: "#DD6B55",
				confirmButtonText: '<?php echo label('m_ok'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>',
				inputPlaceholder: "<?php echo label('preDetail'); ?>: ",
				inputValidator: (value) => {
					if (!value) {
						// หากไม่กรอกข้อมูล
						return '<?php echo label("pls_enter_reason"); ?>';
					}
				}
			}).then(function(isChk) {
				if (isChk.value) {
					$("#myModal_process").modal({
						backdrop: false
					});
					$.ajax({
						url: "<?= base_url() ?>index.php/querydata/reject_cos",
						method: "POST",
						data: {
							cos_id: cos_id,
							cosa_note: isChk.value
						},
						dataType: "json",
						success: function(data) {}
					});
					location.reload();
				}
			});
		});
		$(document).on('click', '.approve_cog', function(e) {
			var cg_id = $(this).attr("id");

			var buttons = $('<div>')
				.append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
					'btn btn-flat btnapprove_cog', 'background-color:#1abc9c;', cg_id,
					function() {})).append(createButton(
					'<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>', 'btn btn-flat btnreject_cog',
					'background-color:#DD6B55;', cg_id,
					function() {
						swal.close();
					})).append(createButton('<?php echo label('cancel'); ?>', 'btn btn-flat btnrefresh', '', '', function() {
					swal.close();
				}));
			e.preventDefault();
			swal({
				title: "<?php echo label('approve_is'); ?>",
				html: buttons,
				type: "warning",
				showConfirmButton: false,
				showCancelButton: false
			});
		});

		$(document).on('click', '.btnapprove_cog', function(e) {
			e.preventDefault();
			var cg_id = $(this).attr("id");
			$("#myModal_process").modal({
				backdrop: false
			});
			$(document.body).css('pointer-events', 'none');
			$.ajax({
				url: "<?= base_url() ?>index.php/manage/approve_cosgroup_data",
				method: "POST",
				data: {
					cg_id: cg_id
				},
				success: function(data) {
					$(document.body).css('pointer-events', '');
					if (data == "2") {
						swal(
							'<?php echo label("approve_msg_success"); ?>',
							'',
							'success'
						).then(function() {
							location.reload();
						})
					} else if (data == "1") {
						swal({
							title: '<?php echo label("wg_msg_use"); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('m_ok'); ?>'
						}).then(function() {
							location.reload();
						})
					} else {
						swal({
							title: '<?php echo label('com_msg_error_save'); ?>',
							text: "",
							type: 'warning',
							showCancelButton: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: '<?php echo label('m_ok'); ?>'
						}).then(function() {
							location.reload();
						})
					}
				}
			});
		});

		$(document).on('click', '.btnreject_cog', function(e) {
			e.preventDefault();
			var cg_id = $(this).attr("id");
			swal({
				title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
				text: "",
				input: 'text',
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonColor: "#1abc9c",
				cancelButtonColor: "#DD6B55",
				confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>',
				inputPlaceholder: "<?php echo label('preDetail'); ?>: ",
				inputValidator: (value) => {
					if (!value) {
						// หากไม่กรอกข้อมูล
						return '<?php echo label("pls_enter_reason"); ?>';
					}
				}
			}).then(function(isChk) {
				if (isChk.value) {
					$("#myModal_process").modal({
						backdrop: false
					});
					$.ajax({
						url: "<?= base_url() ?>index.php/querydata/reject_cog",
						method: "POST",
						data: {
							cg_id: cg_id,
							coga_note: isChk.value
						},
						dataType: "json",
						success: function(data) {}
					});
					location.reload();
				}
				/* if (inputValue === "") {
				   swal.showInputError("You need to write something!");
				   return false
				 }else{
				 swal("Nice!", "You wrote: " + inputValue, "success");
				 }*/
			});
		});
		$('#company_active_user_table').DataTable({
			"ordering": false,
			"searching": false,
			"lengthChange": false,
			"bInfo": false,
			"oLanguage": {
				"oPaginate": {
					"sPrevious": "<", // This is the link to the previous page
					"sNext": ">", // This is the link to the next page
				}
			},
			"dom": "<'usage-table-scroll't><'usage-table-footer'p>",
			"scrollX": true,
			"pageLength": 10
		});


		$(document).on('click', '.detail_cos', function() {
			var cos_id = $(this).attr("id");
			window.location.href = "<?php echo REAL_PATH . '/coursemain/detail/' ?>" + cos_id;
		});

		function fetch_data_ongoing(page_num, type_val) {
			if (page_num == "") {
				page_num = 0;
			}
			if (type_val == "") {
				type_val = 1;
			}
			$('#ongoing_course_table').DataTable().destroy();
			var table = $('#ongoing_course_table').on('error.dt', function(e, settings, techNote, message) {
				notificationForDatatableError("ongoing_course_table", message);
			}).DataTable({
				"language": {
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
					"decimal": "",
					"sInfo": "<?php echo label('sInfo'); ?>",
					"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
					"emptyTable": "<?php echo label('wg_datanotfound'); ?>",
					"infoPostFix": "",
					"thousands": ",",
					//"lengthMenu":     "แสดง _MENU_ รายการ",
					"lengthMenu": "<?php echo label('lengthMenu'); ?>",
					"loadingRecords": "<?php echo label('loadingRecords'); ?>",
					"processing": "<?php echo label('processing'); ?>",
					"search": "<?php echo label('filter_bar'); ?>",
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"paginate": {
						"first": "<?php echo label('firstpage'); ?>",
						"last": "<?php echo label('last'); ?>",
						"next": "<?php echo label('lrn_btn_next'); ?>",
						"previous": "<?php echo label('previous'); ?>"
					},
				},
				"scrollX": true,
				"ajax": {
					url: '<?= base_url() ?>index.php/fetchdata/fetch_detail_ongoing/',
					type: 'GET',
					data: {
						com_id: '<?php echo $user['com_id']; ?>',
						type: type_val
					},
				},
				"initComplete": function() {
					setTimeout(function() {
						var info = table.page.info();
						var length = info.pages;
						var page_current = info.page;
						if ((page_num + 1) > length) {
							page_num = length - 1;
						}
						table.page(page_num).draw(false);
					}, 10);
				},
				"bInfo": false,
				"oLanguage": {
					"sZeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"oPaginate": {
						"sPrevious": "<", // This is the link to the previous page
						"sNext": ">", // This is the link to the next page
					}
				},
				"dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
					"<'row'<'col-sm-12'tr>>" +
					'<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
				"scrollX": true,
				"ordering": false,
				"searching": false,
				"lengthChange": false,
				"pageLength": 5
			});
		}

		function fetch_data_incoming(page_num, type_val) {
			if (page_num == "") {
				page_num = 0;
			}
			if (type_val == "") {
				type_val = 1;
			}
			$('#incoming_course_table').DataTable().destroy();
			var table = $('#incoming_course_table').on('error.dt', function(e, settings, techNote, message) {
				notificationForDatatableError("incoming_course_table", message);
			}).DataTable({
				"language": {
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
					"decimal": "",
					"sInfo": "<?php echo label('sInfo'); ?>",
					"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
					"emptyTable": "<?php echo label('wg_datanotfound'); ?>",
					"infoPostFix": "",
					"thousands": ",",
					//"lengthMenu":     "แสดง _MENU_ รายการ",
					"lengthMenu": "<?php echo label('lengthMenu'); ?>",
					"loadingRecords": "<?php echo label('loadingRecords'); ?>",
					"processing": "<?php echo label('processing'); ?>",
					"search": "<?php echo label('filter_bar'); ?>",
					"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"paginate": {
						"first": "<?php echo label('firstpage'); ?>",
						"last": "<?php echo label('last'); ?>",
						"next": "<?php echo label('lrn_btn_next'); ?>",
						"previous": "<?php echo label('previous'); ?>"
					},
				},
				"scrollX": true,
				"ajax": {
					url: '<?= base_url() ?>index.php/fetchdata/fetch_detail_incoming/',
					type: 'GET',
					data: {
						com_id: '<?php echo $user['com_id']; ?>',
						type: type_val
					},
				},
				"initComplete": function() {
					setTimeout(function() {
						var info = table.page.info();
						var length = info.pages;
						var page_current = info.page;
						if ((page_num + 1) > length) {
							page_num = length - 1;
						}
						table.page(page_num).draw(false);
					}, 10);
				},
				"bInfo": false,
				"oLanguage": {
					"sZeroRecords": "<?php echo label('wg_datanotfound'); ?>",
					"oPaginate": {
						"sPrevious": "<", // This is the link to the previous page
						"sNext": ">", // This is the link to the next page
					}
				},
				"dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
					"<'row'<'col-sm-12'tr>>" +
					'<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
				"scrollX": true,
				"ordering": false,
				"searching": false,
				"lengthChange": false,
				"pageLength": 5
			});
		}
		$('.select2').select2();
		$(function() {
			$('[data-plugin="knob"]').knob();
		});
		onchange_company('');

		function onchange_company(value) {
			if (value != "") {
				var com_id = value;
			} else {
				var com_id = $('#com_id_search').val();
			}
			<?php if ($user['Is_admin'] == "1") { ?>
				$.ajax({
					url: '<?= base_url() ?>index.php/querydata/query_status_cos',
					type: 'POST',
					data: {
						com_id: com_id,
						type: "1"
					},
					dataType: "json",
					success: function(data) {
						$('.div_cos_completed').hide();
						$('.div_cos_close').show();
						$('[data-plugin="knob"]').knob();
						$('[data-plugin="knob"]').trigger('configure', {
							max: parseInt(data.courses_total)
						});
						$('#courses_total').val(parseInt(data.courses_total)).trigger('change').trigger('draw');
						$('#courses_ongoing').val(parseInt(data.courses_ongoing)).trigger('change').trigger('draw');
						$('#courses_incoming').val(parseInt(data.courses_incoming)).trigger('change').trigger('draw');
						$('#courses_close').val(parseInt(data.courses_close)).trigger('change').trigger('draw');
					}
				});
			<?php } else { ?>
				$.ajax({
					url: '<?= base_url() ?>index.php/querydata/query_status_cos',
					type: 'POST',
					data: {
						com_id: com_id,
						type: "2"
					},
					dataType: "json",
					success: function(data) {
						$('.div_cos_close').hide();
						$('.div_cos_completed').show();
						$('[data-plugin="knob"]').knob();
						$('[data-plugin="knob"]').trigger('configure', {
							max: parseInt(data.courses_total)
						});
						$('#courses_total').val(parseInt(data.courses_total)).trigger('change').trigger('draw');
						$('#courses_ongoing').val(parseInt(data.courses_ongoing)).trigger('change').trigger('draw');
						$('#courses_incoming').val(parseInt(data.courses_incoming)).trigger('change').trigger('draw');
						$('#courses_completed').val(parseInt(data.courses_completed)).trigger('change').trigger('draw');
					}
				});
			<?php } ?>
		}
		<?php if ($user['Is_admin'] == "1") { ?>
			fetch_data_ongoing(0, 1);
			fetch_data_incoming(0, 1);
		<?php } else { ?>
			fetch_data_ongoing(0, 2);
			fetch_data_incoming(0, 2);
		<?php } ?>
		<?php if ($emp_firsttime == "1" && $arr_welcome['wctitle_a'] != "") { ?>
			$(document).ready(function() {
				$('#pdpa_content').show();
				$("#dashboard_content").hide();
				$("#sidebarnav").hide();
				$("#profile_user").hide();
			});

			<?php if (isset($arr_msg_confirm) && count($arr_msg_confirm) > 0) { ?>
				document.getElementById("confirm_button").disabled = true;
				$("input[name='check_confirm[]']").change(function() {
					total_confirm = 0;
					$.each($("input[name='check_confirm[]']:checked"), function() {
						total_confirm++;
					});
					var confirm = parseInt('<?php echo count($arr_msg_confirm); ?>');
					console.log(total_confirm, confirm);
					if (total_confirm == confirm) {
						document.getElementById("confirm_button").disabled = false;
					} else {
						document.getElementById("confirm_button").disabled = true;
					}
				});
			<?php } else { ?>
				document.getElementById("confirm_button").disabled = false;
			<?php } ?>
		<?php } ?>

		function confirmfirsttime() {
			var emp_id = '<?php echo $user['emp_id']; ?>';
			var redirect_val = '<?php echo isset($redirect_val) && $redirect_val != "" ? $redirect_val : "dashboard"; ?>';
			$.ajax({
				url: "<?= base_url() ?>index.php/querydata/update_firsttime",
				method: "POST",
				data: {
					emp_id: emp_id
				},
				dataType: "json",
				success: function(data) {
					$("#dashboard_content").show()
					$("#sidebarnav").show()
					$("#profile_user").show();
					location.href = '<?php echo base_url(); ?>' + redirect_val;
				}
			});
		}

		$(document).ready(function() {
			$('#show_admin_dashboard').hide();
			$('#survey_admin_learner').hide();
			$('#admin_learner_approve').hide();

			$('#course_admin_learner').hide();
			$('#on_going_course_admin_learner').hide();
			$('#in_coming_course_admin_learner').hide();
			<?php if ($user['Is_admin'] == "1") { ?>
				$('#ongoing_div').hide();
				$('#incoming_div').hide();
				$("#div_status_cos").removeClass("col-md-12");
				$("#div_status_cos").addClass("col-md-12 col-xl-8");
			<?php } else { ?>
				$("#div_status_cos").removeClass("col-md-12 col-xl-8");
				$("#div_status_cos").addClass("col-md-12");
				$('#active_user_admin').hide();

				$('#survey_admin_learner').show();
				<?php if (count($rechk_approve_cog) > 0) { ?>
					$('#cog_admin').show();
				<?php } else { ?>
					$('#cog_admin').hide();
				<?php } ?>
				$("#ongoing_div").removeClass("col-md-12");
				$("#ongoing_div").addClass("col-md-6");

				$("#incoming_div").removeClass("col-md-12");
				$("#incoming_div").addClass("col-md-6");
			<?php } ?>
		});

		$('#dashboard').click(function() {
			if ($(this).prop("checked") == false) {
				$("#div_status_cos").removeClass("col-md-12");
				$("#div_status_cos").addClass("col-md-12 col-xl-8");
				<?php if (in_array('5', $arr_role_fd)) { ?>
					document.getElementById('div_company').style.display = "";
				<?php } ?>
				$('#show_admin_dashboard').hide();
				$('#show_learner_dashboard').show();
				$('#log_visit').fadeIn("slow");
				$('#admin_approve').fadeIn("slow");
				$('#cog_admin').fadeIn("slow");
				$('#active_user_admin').fadeIn("slow");
				$('#survey_admin_learner').fadeOut("slow");
				$('#admin_learner_approve').fadeOut("slow");
				$('#course_admin_learner').fadeOut("slow");
				onchange_company('');

				$("#ongoing_div").removeClass("col-md-6");
				$("#ongoing_div").addClass("col-md-12");
				$("#incoming_div").removeClass("col-md-6");
				$("#incoming_div").addClass("col-md-12");
				$('#ongoing_div').hide();
				$('#incoming_div').hide();
				fetch_data_ongoing(0, 1);
				fetch_data_incoming(0, 1);
			} else if ($(this).prop("checked") == true) {
				<?php if (in_array('5', $arr_role_fd)) { ?>
					document.getElementById('div_company').style.display = "none";
				<?php } ?>
				$("#div_status_cos").removeClass("col-md-12 col-xl-8");
				$("#div_status_cos").addClass("col-md-12");
				$.ajax({
					url: '<?= base_url() ?>index.php/querydata/query_status_cos',
					type: 'POST',
					data: {
						com_id: '<?php echo $user['com_id']; ?>',
						type: "2"
					},
					dataType: "json",
					success: function(data) {
						$('[data-plugin="knob"]').knob();
						$('[data-plugin="knob"]').trigger('configure', {
							max: parseInt(data.courses_total)
						});
						$('#courses_total').val(parseInt(data.courses_total)).trigger('change').trigger('draw');
						$('#courses_ongoing').val(parseInt(data.courses_ongoing)).trigger('change').trigger('draw');
						$('#courses_incoming').val(parseInt(data.courses_incoming)).trigger('change').trigger('draw');
						$('#courses_completed').val(parseInt(data.courses_completed)).trigger('change').trigger('draw');
						$('.div_cos_close').hide();
						$('.div_cos_completed').show();
					}
				});
				$('#show_learner_dashboard').hide();
				$('#show_admin_dashboard').show();
				$('#log_visit').fadeOut("slow");
				$('#admin_approve').fadeOut("slow");
				$('#active_user_admin').fadeOut("slow");
				$('#cog_admin').fadeOut("slow");
				$('#survey_admin_learner').fadeIn("slow");
				$('#admin_learner_approve').fadeIn("slow");
				$('#course_admin_learner').fadeIn("slow");

				$("#ongoing_div").removeClass("col-md-12");
				$("#ongoing_div").addClass("col-md-6");

				$("#incoming_div").removeClass("col-md-12");
				$("#incoming_div").addClass("col-md-6");
				$('#ongoing_div').show();
				$('#incoming_div').show();
				fetch_data_ongoing(0, 2);
				fetch_data_incoming(0, 2);
			}
		});
		<?php if (in_array('4', $arr_role_fd)) { ?>
			new Chart(document.getElementById("chart3"), {
				"type": "doughnut",
				options: {
					aspectRatio: 1,
					layout: {
						padding: {
							left: 0,
							right: 0,
							top: 0,
							bottom: 0,
						}
					},
					responsive: true,
					legend: {
						position: 'bottom',
						labels: {
							boxWidth: 10
						}
					},
					cutoutPercentage: 80,
				},
				"data": {
					"labels": ["Desktop", "Tablet", "Mobile"],
					"datasets": [{
						"label": "My First Dataset",
						"data": [parseInt('<?php echo $PC_log; ?>'), parseInt('<?php echo $Tablet_log; ?>'), parseInt(
							'<?php echo $Mobile_log; ?>')],
						"backgroundColor": ["#ed1c24", "#667085", "#dce2e9"],
						borderWidth: 1
					}]
				}
			});
		<?php } ?>

		$(document).on('click', '.btn_register', function() {
			var cos_id = $(this).attr("id");
			swal({
				title: '<?php echo label('enroll_msg'); ?>',
				text: "",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: "#FE0000",
				confirmButtonText: '<?php echo label('lrn_btn_register'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>'
			}).then(function(isChk) {
				if (isChk.value) {
					$("#myModal_process").modal({
						backdrop: false
					});
					$.ajax({
						url: "<?= base_url() ?>index.php/querydata/enroll_course_byuser",
						method: "POST",
						data: {
							cos_id: cos_id
						},
						dataType: "json",
						success: function(data) {
							if (data.status == "2") {
								swal(
									'<?php echo label("enroll_reuse_success"); ?>',
									'',
									'success'
								).then(function() {
									location.reload();
									fetch_data_ongoing(0, 2);
								})
							} else if (data.status == "3") { //Wait approve
								swal({
									title: '<?php echo label('lrn_b_approver_student'); ?>',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
								}).then(function() {
									fetch_data_ongoing(0, 2);
								})
							} else if (data.status == "1") { //Duplicate
								swal({
									title: '<?php echo label('lrn_btn_re_enroll'); ?>',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
								}).then(function() {
									fetch_data_ongoing(0, 2);
								})
							} else if (data.status == "5") { //Seat Full
								swal({
									title: '<?php echo label('lrn_p_regis_sub'); ?>',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
								}).then(function() {
									fetch_data_ongoing(0, 2);
								})
							} else if (data.status == "11") { //condition
								swal({
									title: '<?php echo label('register_condition'); ?> &#34;' + data.msg + '&#34;',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
								}).then(function() {
									fetch_data_ongoing(0, 2);
								})
							} else {
								swal({
									title: '<?php echo label('lrn_p_data_not_found'); ?>',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
								}).then(function() {
									fetch_data_ongoing(0, 2);
								})
							}
							$("#myModal_process").modal('hide');
						}
					});
				}
			});
		});

		// Keep the precision overview in sync with the existing dashboard data source.
		function syncPrecisionMetric(sourceId) {
			var source = document.getElementById(sourceId);
			var target = document.querySelector('[data-precision-value="' + sourceId + '"]');
			if (source && target) {
				target.textContent = Number(source.value || 0).toLocaleString();
			}
		}
		$(document).on('change', '#courses_total,#courses_ongoing,#courses_incoming,#courses_close,#courses_completed', function() {
			var targetId = this.id === 'courses_completed' ? 'courses_close' : this.id;
			var target = document.querySelector('[data-precision-value="' + targetId + '"]');
			if (target) target.textContent = Number(this.value || 0).toLocaleString();
		});
		$(document).ready(function() {
			['courses_total','courses_ongoing','courses_incoming','courses_close'].forEach(syncPrecisionMetric);
			var clock = document.getElementById('precisionClock');
			if (clock) {
				function updatePrecisionClock() {
					var now = new Date();
					clock.textContent = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0') + ':' + String(now.getSeconds()).padStart(2,'0');
				}
				updatePrecisionClock();
				setInterval(updatePrecisionClock, 1000);
			}
		});
	</script>
</body>

</html>
