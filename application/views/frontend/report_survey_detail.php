<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>

<link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<!-- Date picker plugins css -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<!-- Daterange picker plugins css -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="<?php echo REAL_PATH; ?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/js/adapter.min.js"></script>
<script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/js/vue.min.js"></script>
<link href="<?php echo REAL_PATH; ?>/assets/plugins/morrisjs/morris.css" rel="stylesheet">
<!--nestable CSS -->
<link href="<?php echo REAL_PATH; ?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
<style>
	.dataTables_scrollHeadInner {
		width: 100%!important;
	}
	.dataTables_scrollHeadInner > table {
		width: 100%!important;
	}
</style>
    <link href="<?php echo REAL_PATH;?>/assets/css/report-theme.css?v=20260811-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border report-theme-page">
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	<div class="preloader">
		<div class="loader">
			<div class="loader__figure"></div>
			<p class="loader__label">
				<?php if ($lang == "thai") {
					echo $foote[0]['da_title_th'];
				} else {
					echo $foote[0]['da_title_en'];
				} ?></p>
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- Main wrapper - style you can find in pages.scss -->
	<!-- ============================================================== -->
	<div id="main-wrapper">
		<?php $this->load->view('frontend/inc/inc-header.php'); ?>
		<?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
		<!-- Page wrapper  -->
		<!-- ============================================================== -->
		<div class="page-wrapper">
			<div class="container-fluid">
				<div class="row col-12 page-titles">
					<div class="col-md-5 align-self-center">
						<b><?php echo ucwords(label('report_survey')); ?></b>
					</div>
					<div class="col-md-7 align-self-right">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo REAL_PATH; ?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
							<li class="breadcrumb-item"><?php echo ucwords(label('report')); ?></li>
							<li class="breadcrumb-item"><?php echo ucwords(label('report_general')); ?></li>
							<li class="breadcrumb-item active"><?php echo ucwords(label('report_survey')); ?></li>
						</ol>
					</div>
				</div>
				<?php $a = 0;
				$value1 = 0;
				$value2 = 0;
				$value3 = 0;
				$value4 = 0;
				$value5 = 0;
				$per1 = 0;
				$per2 = 0;
				$per3 = 0;
				$per4 = 0;
				$per5 = 0;
				$out_arr = array();
				foreach ($result_data['survey_detail'] as $key => $rowsu) {
					$total = 0;
					$ans1[$a] = 0;
					$ans2[$a] = 0;
					$ans3[$a] = 0;
					$ans4[$a] = 0;
					$ans5[$a] = 0;

					foreach ($data1  as $key1 => $rowda) {
						if (($rowsu['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '1')) : $ans1[$a]++;
						endif;
					}
					foreach ($data2  as $key2 => $rowda) {
						if (($rowsu['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '2')) : $ans2[$a]++;
						endif;
					}
					foreach ($data3  as $key3 => $rowda) {
						if (($rowsu['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '3')) : $ans3[$a]++;
						endif;
					}
					foreach ($data4  as $key4 => $rowda) {
						if (($rowsu['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '4')) : $ans4[$a]++;
						endif;
					}
					foreach ($data5  as $key5 => $rowda) {
						if (($rowsu['svde_id'] == $rowda['svde_id']) && ($rowda['qnude_var'] == '5')) : $ans5[$a]++;
						endif;
					}


					$val1 = intval($ans1[$a]) * 1;
					$val2 = intval($ans2[$a]) * 2;
					$val3 = intval($ans3[$a]) * 3;
					$val4 = intval($ans4[$a]) * 4;
					$val5 = intval($ans5[$a]) * 5;
					$total_val = $val1 + $val2 + $val3 + $val4 + $val5;
					$total = $ans1[$a] + $ans2[$a] + $ans3[$a] + $ans4[$a] + $ans5[$a];
					if ($total != 0 && $total_val != 0) {
						$output = array();
						$output['mean'] = $total_val / $total;
						$output['percent'] = (($output['mean']) * 100) / 5;
						$output['percent_1'] = intval($ans1[$a]);
						$output['percent_2'] = intval($ans2[$a]);
						$output['percent_3'] = intval($ans3[$a]);
						$output['percent_4'] = intval($ans4[$a]);
						$output['percent_5'] = intval($ans5[$a]);
						array_push($out_arr, $output);
					}
					$a++;
				}
				if ($value1 > 0 && $total > 0) {
					$per1 = ($value1 * 100) / $total;
				}
				if ($value2 > 0 && $total > 0) {
					$per2 = ($value2 * 100) / $total;
				}
				if ($value3 > 0 && $total > 0) {
					$per3 = ($value3 * 100) / $total;
				}
				if ($value4 > 0 && $total > 0) {
					$per4 = ($value4 * 100) / $total;
				}
				if ($value5 > 0 && $total > 0) {
					$per5 = ($value5 * 100) / $total;
				}

				if ($lang == "thai") {
					$sv_title = $result_data['sv_title_th'] != "" ? $result_data['sv_title_th'] : $result_data['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $result_data['sv_title_jp'];
					$sv_explanation = $result_data['sv_explanation_th'] != "" ? $result_data['sv_explanation_th'] : $result_data['sv_explanation_eng'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result_data['sv_explanation_jp'];
				} else if ($lang == "english") {
					$sv_title = $result_data['sv_title_eng'] != "" ? $result_data['sv_title_eng'] : $result_data['sv_title_th'];
					$sv_title = $sv_title != "" ? $sv_title : $result_data['sv_title_jp'];
					$sv_explanation = $result_data['sv_explanation_eng'] != "" ? $result_data['sv_explanation_eng'] : $result_data['sv_explanation_th'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result_data['sv_explanation_jp'];
				} else {
					$sv_title = $result_data['sv_title_jp'] != "" ? $result_data['sv_title_jp'] : $result_data['sv_title_eng'];
					$sv_title = $sv_title != "" ? $sv_title : $result_data['sv_title_th'];
					$sv_explanation = $result_data['sv_explanation_jp'] != "" ? $result_data['sv_explanation_jp'] : $result_data['sv_explanation_eng'];
					$sv_explanation = $sv_explanation != "" ? $sv_explanation : $result_data['sv_explanation_th'];
				}
				?>
				<div class="row col-12 page-titles">
					<div class="col-md-12 card">
						<div class="card-body">
							<!-- <button type="button" id="export_button" onclick="export_excel('<?php echo $sv_id; ?>')" class="btn btn-success export_button" ><i class="mdi mdi-file-excel"></i><span> Export Excel</span></button> -->
							<button type="button" onclick="window.location.href='<?php echo base_url() . "report/loadreport_survey"; ?>';" class="btn btn-outline-danger float-right btn_previous"><i class="mdi mdi-keyboard-return"></i>
								<?php echo label('m_previous'); ?></button>
							<h4 class="float-left">
								<?php echo $sv_title;  ?>
							</h4><br><br>
							<h5 style="float: left;">
								<?php echo $sv_explanation;  ?>
							</h5><br>
						</div>
						<div class="card-body">
							<ul class="list-inline float-right">
								<li>
									<h5><i class="fa fa-circle m-r-5" style="color: #f1c40f"></i>1</h5>
								</li>
								<li>
									<h5><i class="fa fa-circle m-r-5" style="color: #2ecc71"></i>2</h5>
								</li>
								<li>
									<h5><i class="fa fa-circle m-r-5" style="color: #3498db"></i>3</h5>
								</li>
								<li>
									<h5><i class="fa fa-circle m-r-5" style="color: #e74c3c"></i>4</h5>
								</li>
								<li>
									<h5><i class="fa fa-circle m-r-5" style="color: #9b59b6"></i>5</h5>
								</li>
							</ul>
							<p class=""><?php echo label('total_answer'); ?></p>

							<div class="chart" id="pieChart" style="height: 300px; position: relative;"></div><br>
							<hr>
							<table width="100%" class="table ">
								<tr>
									<th></th>
									<th colspan="2"><?php echo label('smax'); ?></th>
									<th></th>
									<th colspan="2"><?php echo label('smin'); ?></th>
									<th></th>
								</tr>
								<tr>
									<th width="500"></th>
									<th width="50">5</th>
									<th width="50">4</th>
									<th width="50">3</th>
									<th width="50">2</th>
									<th width="50">1</th>
									<th width="150" align="center"><?php echo label('Suggestion'); ?></th>
								</tr>
								<?php
								$title_arr = array();
								foreach ($result_data['survey_detail'] as $key => $value) {
									if ($lang == "thai") {
										$svde_heading = $value['svde_heading_th'] != "" ? $value['svde_heading_th'] : $value['svde_heading_eng'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_jp'];
										$svde_detail = $value['svde_detail_th'] != "" ? $value['svde_detail_th'] : $value['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_jp'];
									} else if ($lang == "english") {
										$svde_heading = $value['svde_heading_eng'] != "" ? $value['svde_heading_eng'] : $value['svde_heading_th'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_jp'];
										$svde_detail = $value['svde_detail_eng'] != "" ? $value['svde_detail_eng'] : $value['svde_detail_th'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_jp'];
									} else {
										$svde_heading = $value['svde_heading_jp'] != "" ? $value['svde_heading_jp'] : $value['svde_heading_eng'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_th'];
										$svde_detail = $value['svde_detail_jp'] != "" ? $value['svde_detail_jp'] : $value['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_th'];
									}
									$svde_heading = str_replace(" ", "", $svde_heading);
									if (!in_array($svde_heading, $title_arr)) {
										array_push($title_arr, $svde_heading);
									}
								}
								$count_arr = 0;
								$count_row = 1;
								$row = 0;
								$count = 1;
								foreach ($result_data['survey_detail'] as $key => $value) {
									$quest[$row] = $value['svde_id'];

									if ($lang == "thai") {
										$svde_heading = $value['svde_heading_th'] != "" ? $value['svde_heading_th'] : $value['svde_heading_eng'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_jp'];
										$svde_detail = $value['svde_detail_th'] != "" ? $value['svde_detail_th'] : $value['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_jp'];
									} else if ($lang == "english") {
										$svde_heading = $value['svde_heading_eng'] != "" ? $value['svde_heading_eng'] : $value['svde_heading_th'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_jp'];
										$svde_detail = $value['svde_detail_eng'] != "" ? $value['svde_detail_eng'] : $value['svde_detail_th'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_jp'];
									} else {
										$svde_heading = $value['svde_heading_jp'] != "" ? $value['svde_heading_jp'] : $value['svde_heading_eng'];
										$svde_heading = $svde_heading != "" ? $svde_heading : $value['svde_heading_th'];
										$svde_detail = $value['svde_detail_jp'] != "" ? $value['svde_detail_jp'] : $value['svde_detail_eng'];
										$svde_detail = $svde_detail != "" ? $svde_detail : $value['svde_detail_th'];
									}
									$svde_heading = str_replace(" ", "", $svde_heading);
									if (isset($title_arr[$count_arr]) && $title_arr[$count_arr] != $svde_heading) {
										$count_arr++;
										$count++;
										$count = 1;
									}
									if ($count == 1 && isset($title_arr[$count_arr])) { ?>
										<tr>
											<td colspan="7" align="left"><br><?php echo $title_arr[$count_arr]; ?></td>
										</tr>
									<?php
									}
									$count++;
									?>

									<tr>
										<td width="500">
											<div class="questions"><?php echo " - " . $svde_detail; ?>
											</div>
										</td>
										<td width="50"><?php echo $ans5[$row] ?></td>
										<td width="50"><?php echo $ans4[$row] ?></td>
										<td width="50"><?php echo $ans3[$row] ?></td>
										<td width="50"><?php echo $ans2[$row] ?></td>
										<td width="50"><?php echo $ans1[$row] ?></td>
										<td width="150" align="center"><button type="button" id="<?php echo $value['svde_id']; ?>" class="btn btn-success btn-sm margin view_suggestion" name="view_suggestion" data-toggle="modal" data-target="#modal-Suggestion"><i class="fa fa-search"></i><span>
													<?php echo label('slink'); ?></span></button></td>
									</tr>
									<?php $row++;
									$count_row++; ?>
								<?php } ?>
								<tr>
									<td colspan="7" align="center"><br><button type="button" id="<?php echo $sv_id; ?>" class="btn btn-info btn-sm margin view_suggestionhead" name="view_suggestionhead" data-toggle="modal" data-target="#modal-Suggestionhead"><i class="fa fa-search"></i><span>
												<?php echo label('slinkhead'); ?></span></button><br><br></td>
								</tr>
							</table><br>
							<hr>
							<div class="row">
								<div class="col-md-12" id="div_detaildata">

								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<?php $this->load->view('frontend/inc/inc-footer.php'); ?>


	<div class="modal fade  bs-example-modal-lg" id="modal-Suggestion">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="myLargeModalLabel"><i class="fa fa-search"></i><span> <?php echo label('slink'); ?></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>

				<div class="modal-body">
					<div class="box-body">
						<div id="taa_table" class="table-responsive">
							<table id="tbtable" width="100%" class="table table-bordered table-striped" style="width:100%!important">
								<thead>
									<tr>
										<th width="20%" style="width: 20%!important"></th>
										<th width="80%" class="text-center" style="width: 80%!important"><?php echo label('Suggestion'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade  bs-example-modal-lg" id="modal-Suggestionhead">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="myLargeModalLabel"><i class="fa fa-search"></i><span> <?php echo label('slinkhead'); ?></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>

				<div class="modal-body">
					<div class="box-body">
						<div id="taa_table" class="table-responsive">
							<table id="tbtable_head" width="100%" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="20%" style="width: 20%"></th>
										<th width="80%" class="text-center" style="width: 80%"><?php echo label('Suggestion'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
				</div>
			</div>
		</div>
	</div>

	<!-- wysuhtml5 Plugin JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/switchery/dist/switchery.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript">
	</script>
	<script type="text/javascript" src="<?php echo REAL_PATH; ?>/assets/plugins/multiselect/js/jquery.multi-select.js">
	</script>
	<!-- ============================================================== -->
	<!-- Style switcher -->
	<!-- ============================================================== -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
	<!-- This is data table -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
	<!-- Date Picker Plugin JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

	<script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
	<!-- Date range Plugin JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
	<!--Nestable js -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
	<!--Morris JavaScript -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/raphael/raphael-min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/morrisjs/morris.js"></script>
	<script type="text/javascript">
		$.fn.dataTable.ext.errMode = "none";
		$(document).ready(function() {
			$('.collapse').on('shown.bs.collapse', function (e) {
				$.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
			});

			$(document).on('click', '.view_suggestion', function() {
				var survey_id = $(this).attr("id");
				$('#modal-Suggestion').modal('show');

				$('#tbtable').DataTable().destroy();
				fetch_data(survey_id);
			});

			$(document).on('click', '.view_suggestionhead', function() {
				var scode = $(this).attr("id");
				$('#modal-Suggestionhead').modal('show');

				$('#tbtable_head').DataTable().destroy();
				fetch_data_head(scode);
			});

			function fetch_data(survey_id) {
				var table = $('#tbtable').DataTable({
					"language": {
						"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
						"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
						"sInfo": "<?php echo label('sInfo'); ?>",
						"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
						"decimal": "",
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
					"ajax": {
						url: '<?= base_url() ?>index.php/Report/fetch_detail/' + survey_id,
						type: 'GET'
					},
					"autoWidth":false,
					"scrollX": true,
				});
				table.columns.adjust().draw();
			}

			function fetch_data_head(scode) {
				var table = $('#tbtable_head').DataTable({
					"language": {
						"zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
						"infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
						"sInfo": "<?php echo label('sInfo'); ?>",
						"sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
						"decimal": "",
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
					"ajax": {
						url: '<?= base_url() ?>index.php/Report/fetch_detail_head/' + scode,
						type: 'GET'
					},
					"autoWidth":false,
					"scrollX": true,
				});
				table.columns.adjust().draw();
			}
		});

		$.ajax({
			url: '<?= base_url() ?>index.php/querydata/query_list_emp_reportsurveycos',
			type: 'POST',
			data: {
				sv_id: "<?php echo $sv_id; ?>"
			},
			success: function(data) {
				$('#div_detaildata').html(data);
			}
		});

		function export_excel(survey_id) {
			if (survey_id != "") {
				window.open('<?= base_url() ?>excel_export/export_report_survey.php?survey_id=' + survey_id +
					'&lang=<?php echo $lang; ?>');
			}
		}! function($) {
			"use strict";

			var MorrisCharts = function() {};
			MorrisCharts.prototype.createStackedChart = function(element, data, xkey, ykeys, labels, lineColors) {
					Morris.Bar({
						element: element,
						data: data,
						xkey: xkey,
						ykeys: ykeys,
						stacked: true,
						labels: labels,
						hideHover: 'auto',
						resize: true, //defaulted to true
						gridLineColor: '#2c3e50',
						gridTextColor: '#2c3e50',
						barColors: lineColors
					});
				},
				MorrisCharts.prototype.init = function() {
					var $stckedData = [
						<?php
						$count_arr = 0;
						$row = 0;
						$count = 1;
						if (countArray($result_data['survey_detail']) > 0) {
							foreach ($result_data['survey_detail'] as $key => $value) {
								$svde_detail_th = "";
								if ($lang == "thai") {
									$svde_detail_th = $value['svde_heading_th'] . "(" . $count . ")";
								} else if ($lang == "english") {
									$svde_detail_th = $value['svde_heading_eng'] . "(" . $count . ")";
								} else {
									$svde_detail_th = $value['svde_heading_jp'] . "(" . $count . ")";
								}
						?> {
									y: '<?php echo "(" . $svde_detail_th . " : ";
											echo isset($out_arr[$row]['percent']) ? number_format($out_arr[$row]['percent'], 2) : '0';
											echo "% : ";
											echo isset($out_arr[$row]['mean']) ? number_format($out_arr[$row]['mean'], 2) : '0';
											echo " ) "; ?>',
									a: parseInt('<?php echo isset($out_arr[$row]['percent_1']) ? $out_arr[$row]['percent_1'] : '0'; ?>'),

									b: parseInt('<?php echo isset($out_arr[$row]['percent_2']) ? $out_arr[$row]['percent_2'] : '0'; ?>'),
									c: parseInt('<?php echo isset($out_arr[$row]['percent_3']) ? $out_arr[$row]['percent_3'] : '0'; ?>'),
									d: parseInt('<?php echo isset($out_arr[$row]['percent_4']) ? $out_arr[$row]['percent_4'] : '0'; ?>'),
									e: parseInt('<?php echo isset($out_arr[$row]['percent_5']) ? $out_arr[$row]['percent_5'] : '0'; ?>')
								}
						<?php
								if ($row < (countArray($result_data['survey_detail']) + 1)) {
									echo ",";
								}
								$row++;
								$count++;
							}
						} ?>
					];
					this.createStackedChart('pieChart', $stckedData, 'y', ['a', 'b', 'c', 'd', 'e'], ['1', '2', '3', '4', '5'], [
						'#f1c40f', '#2ecc71', '#3498db', '#e74c3c', '#9b59b6'
					]);
				},
				//init
				$.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts
		}(window.jQuery),

		//initializing 
		function($) {
			"use strict";
			$.MorrisCharts.init();
		}(window.jQuery);
	</script>
</body>

</html>
