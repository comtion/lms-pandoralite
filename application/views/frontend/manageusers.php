<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<?php
$widthdivv = 0;
if (!isMobile()) {
	$widthdivv = 240;
} else {
	$widthdivv = 60;
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
<link href="<?php echo REAL_PATH; ?>/assets/plugins/wizard/steps.css" rel="stylesheet">
<style type="text/css">
	#myModal_process.modal.show .modal-dialog {
		position: fixed;
		top: 50%;
		left: 50%;
		/* bring your own prefixes */
		transform: translate(-50%, -50%);
		margin: 0;
	}

	#myModal_process .circle strong {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 1.8em;
	}

	#myModal_process .circle canvas {
		visibility: hidden;
	}

	#myModal_process #circle-b {
		margin: 0;
	}
</style>
</head>

<body class="fix-header fix-sidebar card-no-border">
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	<div class="preloader">
		<div class="loader">
			<div class="loader__figure"></div>
			<p class="loader__label"><?php if ($lang == "thai") {
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
						<b><?php echo ucwords(strtolower($title)); ?></b>
					</div>
					<div class="col-md-7 align-self-right">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo REAL_PATH; ?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
							<?php if ($title_main != "") { ?>
								<li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
							<?php } ?>
							<li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
						</ol>
					</div>
				</div>

				<div class="row col-12 page-titles">
					<div class="col-md-12 card">
						<div class="card-body">
							<div class="col-md-12" align="right">
								<?php if ($btn_print == "1") { ?>
									<button name="export_button" id="export_button" class="btn btn-outline-success export_button float-right"><i class="mdi mdi-export"></i>
										<?php echo label('export_user'); ?></button>
								<?php } ?>
								<?php if ($btn_add == "1") { ?>
									<button name="import_button" id="import_button" class="btn btn-outline-primary import_button float-right" data-toggle="modal" data-target="#modal-import"><i class="mdi mdi-import"></i>
										<?php echo label('import_user'); ?></button>
									<button name="add_button" id="add_button" class="btn btn-outline-info add_button float-right" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i>
										<?php echo label('create_user'); ?></button>
								<?php }
								if (in_array($user['useri'], array('support_verztec'))) {
								?>
									<button name="Listlearnerincomplete" id="Listlearnerincomplete" class="btn btn-outline-warning Listlearnerincomplete float-right" data-toggle="modal" data-target="#modal-listlearnerincomplete"><i class="mdi mdi-format-list-bulleted"></i> List Learner
										incomplete</button>
								<?php
								}
								if ($com_admin != "com_associated" && ($user['ug_id'] == "1")) { ?>
									<div class="row">
										<div class="col-md-6" align="left">
											<label for="com_id"><?php echo label('com_name'); ?>:</label>
											<select class="form-control select2" id="com_id_search" name="com_id_search" style="width: 100%;">
												<?php if (countArray($company_arr) > 0) { ?>
													<optgroup label="<?php echo label('please_com_name'); ?>">
														<?php
														if ($user['ug_id'] == "1") {
														?>
															<option value="" selected><?php echo label('allcompany'); ?></option>
														<?php
														}
														//$numloop = 1; <?php if($numloop==1){echo "selected";}$numloop++;
														foreach ($company_arr as $key_com => $value_com) { ?>
															<option value="<?php echo $value_com['com_id']; ?>">
																<?php echo $lang == "thai" ? $value_com['com_name_th'] : $value_com['com_name_eng']; ?>
															</option>
														<?php   } ?>
													</optgroup>
												<?php   } ?>
											</select>
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" id="com_id_search" name="com_id_search" value="<?php echo $com_id; ?>">
								<?php } ?>
							</div>
							<div class="table-responsive">
								<table id="myTable" width="100%" class="table table-bordered">
									<thead>
										<tr>
											<th style="min-width: 80px !important;" align="center">
												<center><?php echo label('manage'); ?></center>
											</th>
											<th width="5%"><?php echo label('number'); ?></th>
											<th width="15%" align="center">
												<center><?php echo label('m_username'); ?></center>
											</th>
											<th width="25%" align="center">
												<center><?php echo label('m_name'); ?></center>
											</th>
											<th width="25%" align="center">
												<center><?php echo label('m_usergroup'); ?></center>
											</th>
											<!--<th width="10%" align="center"><center><?php echo label('m_department'); ?></center></th>-->
											<th width="20%" align="center">
												<center><?php echo label('m_company'); ?></center>
											</th>
											<!-- 
                                <th width="10%" align="center"><?php echo label('m_status'); ?></th> -->
										</tr>
									</thead>
								</table>
							</div>
							<p><?php echo label('preNote'); ?>: <?php if ($user['ug_id'] == "1") { ?><button type="button" class="btn btn-info btn-xs"><i class="mdi mdi-account-key"></i></button> =
									<b><?php echo label('m_permission'); ?></b><?php }
																														if ($user['ug_id'] == "1" && $btn_update == "1") {
																															echo " , ";
																														}
																														if ($btn_update == "1") { ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> =
									<b><?php echo label('m_edit'); ?></b><?php } ?><?php if ($btn_delete == "1" && $btn_update == "1") {
																																		echo " , ";
																																	}
																																	if ($btn_delete == "1") { ?><button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> =
									<b><?php echo label('delete'); ?></b><?php } ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('frontend/inc/inc-footer.php'); ?>

	<div class="modal fade bs-example-modal-lg" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<div class="card wizard-content">
						<div class="card-body">
							<form method="post" id="user_form" autocomplete="off" name="user_form" enctype="multipart/form-data" class="validation-wizard wizard-circle">
								<input type="hidden" id="operation" name="operation" value="Add">
								<input type="hidden" id="u_id" name="u_id">
								<input type="hidden" id="emp_id" name="emp_id">
								<input type="hidden" id="img_profile_ori" name="img_profile_ori">
								<input type="hidden" id="employ_date" name="employ_date">
								<input type="hidden" id="employ_date_var" name="employ_date_var">
								<input type="hidden" id="work_phone" name="work_phone">
								<input type="hidden" id="phone" name="phone">
								<!-- Step 1 -->
								<h6><?php echo label('m_general_information'); ?></h6>
								<section>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="com_id"><b style="color: #FF2D00">*</b><?php echo label('com_name'); ?>:</label>
												<?php if ($com_admin != "com_associated" && ($user['ug_id'] == "1")) { ?>
													<select class="form-control select2" required id="com_id" name="com_id" style="width: 100%;">
													</select>
												<?php } else { ?>
													<input type="text" id="com_name" class="form-control" name="com_name" value="<?php echo $com_name; ?>" readonly>
													<input type="hidden" id="com_id" name="com_id" value="<?php echo $com_id; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="wphoneNumber2"><b style="color: #FF2D00">*</b><?php echo label('dep_name'); ?>:</label>
												<select class="form-control select2" id="dep_id" name="dep_id" style="width: 100%;">
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="wphoneNumber2"><b style="color: #FF2D00">*</b><?php echo label('posi_name'); ?>:</label>
												<select class="form-control select2" id="posi_id" name="posi_id" style="width: 100%;">
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prefix_th"> <?php echo label('m_prefix') . " TH"; ?>: </label>
                                                    <input type="text" class="form-control required" id="prefix_th" name="prefix_th"> </div>
                                            </div> -->
										<div class="col-md-6">
											<div class="form-group">
												<label for="fname_th"> <b style="color: #FF2D00">*</b><?php echo label('m_fname') . " TH"; ?>:
												</label>
												<input type="text" class="form-control required" id="fname_th" name="fname_th">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="lname_th"> <b style="color: #FF2D00">*</b><?php echo label('m_lname') . " TH"; ?>:
												</label>
												<input type="text" class="form-control required" id="lname_th" name="lname_th">
											</div>
										</div>
									</div>
									<div class="row">
										<input type="hidden" class="form-control" id="prefix_th" name="prefix_th">
										<input type="hidden" class="form-control" id="prefix_en" name="prefix_en">
										<input type="hidden" class="form-control" id="gender" name="gender" value="Male">
										<!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prefix_en"> <?php echo label('m_prefix') . " EN"; ?>: </label>
                                                    <input type="text" class="form-control required" id="prefix_en" name="prefix_en"> </div>
                                            </div> -->
										<div class="col-md-6">
											<div class="form-group">
												<label for="fname_en"> <b style="color: #FF2D00">*</b><?php echo label('m_fname') . " EN"; ?>:
												</label>
												<input type="text" class="form-control required" id="fname_en" name="fname_en">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="lname_en"> <b style="color: #FF2D00">*</b><?php echo label('m_lname') . " EN"; ?>:
												</label>
												<input type="text" class="form-control required" id="lname_en" name="lname_en">
											</div>
										</div>
									</div>
									<!--  <div class="row"> -->
									<!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="emp_c"> <?php echo label('m_emp_c'); ?>: </label>
                                                    <input type="text" class="form-control required" id="emp_c" name="emp_c"> 
                                                </div>
                                            </div> -->
									<input type="hidden" id="emp_c" name="emp_c">
									<!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="gender"> <?php echo label('m_gender'); ?>: </label>
                                                    <select class="custom-select form-control" id="gender" name="gender">
                                                        <option value="Male" selected><?php echo label('m_male'); ?></option>
                                                        <option value="Female"><?php echo label('m_female'); ?></option>
                                                    </select>
                                                </div>
                                            </div> -->
									<!-- <div class="col-md-6">
                                            </div>
                                        </div> -->
								</section>
								<!-- Step 3 -->
								<h6><?php echo label('m_user_information'); ?></h6>
								<section>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="useri"><b style="color: #FF2D00">*</b><?php echo label('m_username'); ?>: </label>
												<input type="email" class="form-control required" id="useri" name="useri" onkeyup="return forceLower(this);">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="ug_id"><b style="color: #FF2D00">*</b><?php echo label('ug_name'); ?>:</label>
												<select class="form-control select2 required" id="ug_id" name="ug_id" style="width: 100%;">
													<option value=""><?php echo label('please_com_name'); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<!-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="employ_date"><?php echo label('m_employ_date'); ?>:</label>
                                                    <input type="text" class="form-control" id="employ_date" name="employ_date" onchange="caldate('employ_date')">
                                                    <input type="hidden" id="employ_date_var" name="employ_date_var">
                                                </div>
                                            </div> -->
										<div class="col-md-4">
											<div class="form-group">
												<label for="u_firstdate"><b style="color: #FF2D00">*</b><?php echo label('m_firstdate'); ?>:</label>
												<input type="text" class="form-control" id="u_firstdate" name="u_firstdate" required onchange="caldate('u_firstdate')">
												<input type="hidden" id="u_firstdate_var" name="u_firstdate_var">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="inactivedate"><?php echo label('m_usage_enddate'); ?>:</label>
												<input type="text" class="form-control" id="inactivedate" name="inactivedate" onchange="caldate('inactivedate')">
												<input type="hidden" id="inactivedate_var" name="inactivedate_var">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="lang"> <?php echo label('faqlang'); ?>: </label>
												<select class="custom-select form-control" id="lang" name="lang">
													<option selected value="thai"><?php echo label('thai'); ?></option>
													<option value="english"><?php echo label('eng'); ?></option>
													<option value="japan"><?php echo label('jp'); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<!--  <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="work_phone"> <?php echo label('m_workphone'); ?>: </label>
                                                    <input type="text" class="form-control" id="work_phone" name="work_phone"> </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phone"> <?php echo label('m_phone'); ?>: </label>
                                                    <input type="text" class="form-control" id="phone" name="phone"> </div>
                                            </div> -->
										<div class="col-md-4">
											<div class="form-group">
												<label for="email"> <?php echo label('m_mail'); ?>: </label>
												<input type="text" class="form-control" id="email" name="email" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="emp_manage_a"><b style="color: #FF2D00">*</b><?php echo label('m_manager1'); ?>:
												</label>
												<input type="text" class="form-control" id="emp_manage_a" name="emp_manage_a" required>
												<!-- <select id="emp_manage_a" name="emp_manage_a" style="width: 100%" class="form-control select2"></select> -->
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="emp_manage_b"> <?php echo label('m_manager2'); ?>: </label>
												<input type="text" class="form-control" id="emp_manage_b" name="emp_manage_b">
												<!-- <select id="emp_manage_b" name="emp_manage_b" style="width: 100%" class="form-control select2"></select> -->
											</div>
										</div>
									</div>
									<div class="row">
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label text-right"><?php echo label('m_profile'); ?></label>

												<input type="file" name="img_profile" id="img_profile" class="dropify" accept="image/png, image/jpeg, image/gif" />
											</div>
										</div>
										<!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                          <label class="control-label text-right"><?php echo label('m_profilebg'); ?></label>
                                                          <input type="file" name="bgpic_user" id="bgpic_user" class="dropify_bg" accept="image/png, image/jpeg, image/gif" />
                                                          <input type="hidden" id="bgpic_user_ori" name="bgpic_user_ori">
                                                    </div>
                                            </div> -->
									</div>
								</section>
							</form>
						</div>
					</div>
				</div>
				<!--<div class="modal-footer">
                  <input type="submit" name="action" id="action" class="btn btn-outline-success btn-flat pull-left" value="<?php echo label('saveR'); ?>" />
                  <button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><?php echo label('m_cancel'); ?></button>
              </div>-->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->


	<div class="modal bs-example-modal-lg" id="modal-license" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="mt-0" id="myLargeModalLabel"><i class="mdi mdi-account-key"></i>
						<?php echo label('m_permission'); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form method="post" id="checkpermission_form" autocomplete="off" name="checkpermission_form" enctype="multipart/form-data" class="form-horizontal" role="form">
						<div class="table-responsive" align="center">
							<table id="datatable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<td align="center" width="10%"><?php echo label('m_number'); ?></td>
										<td align="center" width="30%"><?php echo label('m_menu'); ?></td>
										<td align="center" width="10%"><?php echo label('m_select_all'); ?></td>
										<td align="center" width="10%">
											<?php echo label('m_enable'); ?>
											<div class="mt-auto" style="bottom:0px">
												<div class="checkbox checkbox-success">
													<input type="checkbox" id="chkcolall_view" class="chkcolall_view checkboxheader" name="chkcolall_view" onchange='chk_chkbox_allcol("ru_view")' value="1">
													<label for="chkcolall_view"></label>
												</div>
											</div>
										</td>
										<td align="center" width="10%">
											<?php echo label('m_add'); ?>
											<div class="mt-auto" style="bottom:0px">
												<div class="checkbox checkbox-success">
													<input type="checkbox" id="chkcolall_add" class="chkcolall_add checkboxheader" name="chkcolall_add" onchange='chk_chkbox_allcol("ru_add")' value="1">
													<label for="chkcolall_add"></label>
												</div>
											</div>
										</td>
										<td align="center" width="10%">
											<?php echo label('m_edit'); ?>
											<div class="mt-auto" style="bottom:0px">
												<div class="checkbox checkbox-success">
													<input type="checkbox" id="chkcolall_edit" class="chkcolall_edit checkboxheader" name="chkcolall_edit" onchange='chk_chkbox_allcol("ru_edit")' value="1">
													<label for="chkcolall_edit"></label>
												</div>
											</div>
										</td>
										<td align="center" width="10%">
											<?php echo label('m_del'); ?>
											<div class="mt-auto" style="bottom:0px">
												<div class="checkbox checkbox-success">
													<input type="checkbox" id="chkcolall_del" class="chkcolall_del checkboxheader" name="chkcolall_del" onchange='chk_chkbox_allcol("ru_del")' value="1">
													<label for="chkcolall_del"></label>
												</div>
											</div>
										</td>
										<td align="center" width="10%">
											<?php echo label('m_export'); ?>
											<div class="mt-auto" style="bottom:0px">
												<div class="checkbox checkbox-success">
													<input type="checkbox" id="chkcolall_print" class="chkcolall_print checkboxheader" name="chkcolall_print" onchange='chk_chkbox_allcol("ru_print")' value="1">
													<label for="chkcolall_print"></label>
												</div>
											</div>
										</td>
									</tr>
								</thead>
								<tbody id="load_detail">

								</tbody>
							</table>

							<input type="hidden" id="u_id_role" name="u_id_role">
						</div>
					</form>
				</div>
				<div class="modal-footer" align="center">
					<button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('close'); ?></button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal" id="modal-import_user" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4><?php echo label('import_user'); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form method="post" id="import_user_form" autocomplete="off" name="import_user_form" enctype="multipart/form-data" class="form-horizontal" role="form">
					<div class="modal-body row">
						<div class="col-md-6">
							<label for="file_import"><b style="color: #FF2D00">*</b><?php echo 'Excel File'; ?>:</label>
							<input type="file" name="file_import" required id="file_import" class="dropify" accept=".xls,.xlsx" />
							<?php echo label('certificate_example') . ": "; ?><a href="<?php echo REAL_PATH; ?>/uploads/format/format_import_user.xlsx" download>format_import_user.xlsx</a>
						</div>
						<div class="col-md-6">
							<h4><i class="mdi mdi-format-list-numbers"></i> <?php echo label('result_import'); ?>:</h4>
							<div id="result_import_user" class="slimtest1" style="max-height: 290px;position: relative;overflow-y: auto;"></div>
						</div>
						<div class="col-md-12 progress" id="progress_uploaduser_div" style="display: none;">
							<div class="progress-bar-uploaduser bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%; height:6px;"><span class="sr-only" id="txt_progress_uploaduser"></span></div>
						</div>
					</div>
					<input type="hidden" id="operation_import_user" name="operation_import_user" value="Add">
					<input type="hidden" id="com_id_import_user" name="com_id_import_user">
					<div class="modal-footer">
						<button type="submit" class="btn btn-outline-success btn-flat pull-left" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
						<button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<div class="modal" id="modal-listenroll" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4><?php echo label('d_coc_total'); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="myTable_enroll" width="100%" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="10%" align="center"></th>
										<th width="30%" align="center"><?php echo label('ceCname'); ?></th>
										<th width="15%" align="center"><?php echo label('r_result'); ?></th>
										<th width="15%" align="center"><?php echo label('learning_status'); ?></th>
										<th width="15%" align="center">First time</th>
										<th width="15%" align="center">Finish time</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<div class="modal" id="modal-listlearnerincomplete" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4><i class="mdi mdi-format-list-bulleted"></i> List Learner incomplete</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="myTable_learnerincomplete" width="100%" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="10%" align="center"></th>
										<th width="30%" align="center"><?php echo label('name'); ?></th>
										<th width="15%" align="center"><?php echo label('m_company'); ?></th>
										<th width="30%" align="center"><?php echo label('ceCname'); ?></th>
										<th width="15%" align="center">First time</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-info btn-flat update_status_learner" data-dismiss="modal"><i class="mdi mdi-update"></i> Update Status</button>
					<button type="button" class="btn btn-outline-danger btn-flat" data-dismiss="modal"><i class="mdi mdi-window-close"></i> <?php echo label('m_cancel'); ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<div id="myModal_process" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-body" align="center" style="max-height: 300px;">
					<div class="circle" id="circle-b">
						<strong></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($lang == "thai") { ?>
		<script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js">
		</script>
		<script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
	<?php } ?>
	<!--  <div id="myModal_process" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body" align="center">
                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('please_wait'); ?></h3>
              </div>
            </div>
        </div>
      </div> -->

	<script type="text/javascript">
		var base_url = "<?php echo REAL_PATH; ?>";
	</script>
	<!-- This is data table -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
	<!-- ============================================================== -->
	<!-- Style switcher -->
	<!-- ============================================================== -->
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/moment/moment.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/wizard/jquery.steps.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/wizard/jquery.validate.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>

	<script src="<?php echo REAL_PATH; ?>/assets/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.flash.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/jszip.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/pdfmake.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/vfs_fonts.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.html5.min.js"></script>
	<script src="<?php echo REAL_PATH; ?>/assets/js/buttons.print.min.js"></script>
	<!--stickey kit -->
	<script type="text/javascript">
		$('.slimtest1').perfectScrollbar();
		$.fn.dataTable.ext.errMode = "none";
		$.fn.steps.setStep = function(step) {
			var currentIndex = $(this).steps('getCurrentIndex');
			for (var i = 0; i < Math.abs(step - currentIndex); i++) {
				if (step > currentIndex) {
					$(this).steps('next');
				} else {
					$(this).steps('previous');
				}
			}
		};

		function changedate(value) {
			var res_date = value.split("/");
			<?php if ($lang == "thai") { ?>
				return (parseInt(res_date[2]) - 543) + "-" + res_date[1] + "-" + res_date[0];
			<?php } else { ?>
				return (parseInt(res_date[2])) + "-" + res_date[1] + "-" + res_date[0];
			<?php } ?>
		}


		function caldate(id) {
			var val_change = changedate($('#' + id).val());
			$('#' + id + '_var').val(val_change);
		}
		$(document).on('submit', '#import_user_form', function(event) {
			event.preventDefault();
			$("#myModal_process").modal('show');
			$(document.body).css('pointer-events', 'none');
			/*$("#myModal_process").modal({backdrop: false});
			$( "body" ).addClass( "modal-open" );*/
			var com_id = $('#com_id_import_user').val();
			var file_import = $('#file_import').val();
			if (file_import != "") {
				$.ajax({
					url: "<?= base_url() ?>index.php/setting/import_user",
					method: 'POST',
					data: new FormData(this),
					contentType: false,
					processData: false,
					dataType: "json",
					xhr: function() {
						/*document.getElementById("progress_uploaduser_div").style.display = "";
						    var xhr = new window.XMLHttpRequest();
						    xhr.upload.addEventListener("progress", function(evt) {
						        if (evt.lengthComputable) {
						            var percentComplete = (evt.loaded / evt.total) * 100;
						            $('#txt_progress_uploaduser').text(percentComplete.toFixed(2) + '%');

						             $('.progress-bar-uploaduser').animate({
						              width: percentComplete + '%'
						             }, {
						              duration: 100
						             });
						            //Do something with upload progress here
						        }
						   }, false);
						   return xhr;*/
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
						/*     document.getElementById("progress_uploaduser_div").style.display = "none";
						     $( document.body ).css( 'pointer-events', '' );*/
						/*
						                    $( "#myModal_process" ).modal( "hide" );
						                    $('.modal-backdrop').remove();
						                    document.getElementById('myModal_process').style.display = 'none';
						                    $( "body" ).removeClass( "modal-open" );
						                    $('body').css('padding-right','0');*/
						topFunction();
						if (data.status == "2") {
							$('#import_user_form')[0].reset();
							swal(
								'<?php echo label("after_upload_file"); ?>!',
								''
							).then(function() {
								topFunction();
								fetch_data(0);
								$('#com_id_search').val(com_id);
								$('#result_import_user').html(data.result);
								clear_dropify('#file_import');
							})
						} else if (data.status == "1") {
							swal({
								title: '<?php echo label("manageimport_msgerror"); ?>',
								text: "",
								type: 'warning',
								showCancelButton: false,
								confirmButtonClass: 'btn btn-primary',
								confirmButtonText: '<?php echo label("m_ok"); ?>'
							}).then(function() {
								document.getElementById("file_import").focus();
							})
						} else {
							swal({
								title: '<?php echo label("com_msg_error_save"); ?>',
								text: "",
								type: 'warning',
								showCancelButton: false,
								confirmButtonClass: 'btn btn-primary',
								confirmButtonText: '<?php echo label("m_ok"); ?>'
							})
						}

					}
				});
			} else {

				swal({
					title: '<?php echo label("manageimport_msgerror"); ?>',
					text: "",
					type: 'warning',
					showCancelButton: false,
					confirmButtonClass: 'btn btn-primary',
					confirmButtonText: '<?php echo label("m_ok"); ?>'
				}).then(function() {
					document.getElementById("file_import").focus();
				})
			}
		});
		fetch_data(0);

		function fetch_data(page_num) {
			var com_id = $('#com_id_search').val();
			$('#myTable').DataTable().destroy();
			var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({
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
				"scrollX": true,
				"ajax": {
					url: '<?= base_url() ?>index.php/manage/fetch_detail_user/',
					type: 'GET',
					data: {
						com_id: com_id,
						lang: "<?php echo $lang; ?>"
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
				}
			});
		}

		function forceLower(strInput) {
			strInput.value = strInput.value.toLowerCase();
			$('#email').val(strInput.value);
		}

		$('#myTable').DataTable();
		$('.slimtest1').perfectScrollbar();

		function chk_chkbox_allcol(field) {

			var u_id = $('#u_id_role').val();
			var mode = "";
			var field_sql = "";
			if (field == "ru_view") {
				mode = "chkcolall_view";
			} else if (field == "ru_add") {
				mode = "chkcolall_add";
			} else if (field == "ru_edit") {
				mode = "chkcolall_edit";
			} else if (field == "ru_del") {
				mode = "chkcolall_del";
			} else {
				mode = "chkcolall_print";
			}
			var value_chk = 0;
			$('.chkcol_' + field).prop('checked', false);
			var remember = document.getElementById(mode);
			if (remember.checked) {
				value_chk = 1;
				$('.chkcol_' + field).prop('checked', true);
			}

			var $checkboxheader = $('.checkboxheader');
			var countCheckedcheckboxheader = $checkboxheader.filter(':checked').length;
			var count_menu = 5;

			if (count_menu == countCheckedcheckboxheader) {
				$('.chkall_row').prop('checked', true);
			} else {
				$('.chkall_row').prop('checked', false);
			}

			<?php if ($user['Is_admin'] != "0") { ?>
				$.ajax({
					url: "<?= base_url() ?>index.php/manage/chk_chkboxcol_user",
					method: "POST",
					data: {
						field_sql_ug: field,
						value_chk_ug: value_chk,
						u_idonrole_ug: u_id
					},
					dataType: "json",
					success: function(data) {

					}
				});
			<?php } ?>
		}

		runwizard();

		function runwizard() {
			var form = $(".validation-wizard").show();
			$(".validation-wizard").steps({
				startIndex: 0,
				headerTag: "h6",
				bodyTag: "section",
				transitionEffect: "fade",
				titleTemplate: '<span class="step">#index#</span> #title#',
				labels: {
					finish: "<?php echo label('saveR'); ?>",
					previous: "<?php echo label('m_previous'); ?>",
					next: "<?php echo label('m_next'); ?>"
				},
				onStepChanging: function(event, currentIndex, newIndex) {
					return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex <
						newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" +
							newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden",
						form.valid())
				},
				onFinishing: function(event, currentIndex) {
					return form.validate().settings.ignore = ":disabled", form.valid()
				},
				onFinished: function(event, currentIndex) {
					// var formdata = $('#user_form').serializeArray();
					var formData = new FormData(document.querySelector('#user_form'));
					var emp_manage_a = $('#emp_manage_a').val();
					var emp_manage_b = $('#emp_manage_b').val();
					emp_manage_a.replace(" ", "");
					emp_manage_b.replace(" ", "");
					var isPass = 1;
					if (emp_manage_a != "" && emp_manage_b != "" && emp_manage_a == emp_manage_b) {
						isPass = 3; // Manager A & B Duplicate
					}
					if (isPass === 1) {
						$("#myModal_process").modal('show');
						$(document.body).css('pointer-events', 'none');
						$.ajax({
							url: "<?= base_url() ?>index.php/manage/insert_user",
							method: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							xhr: function() {
								/*document.getElementById("progress_qrcode_div").style.display = "";
								    var xhr = new window.XMLHttpRequest();
								    xhr.upload.addEventListener("progress", function(evt) {
								        if (evt.lengthComputable) {
								            var percentComplete = (evt.loaded / evt.total) * 100;
								            $('#txt_progress_qrcode').text(percentComplete.toFixed(2) + '%');

								            $('.progress-bar-qrcode').animate({
								              width: percentComplete + '%'
								            }, {
								              duration: 100
								            });
								            //Do something with upload progress here
								        }
								  }, false);
								  return xhr;*/
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
										$('.circle').circleProgress(progressBarOptions).on('circle-animation-progress',
											function(event, progress, stepValue) {
												$(this).find('strong').html("LOADING...<br/>" + percentComplete.toFixed(0) +
													"%");
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
									$('#user_form')[0].reset();
									$('#modal-default').modal('hide');
									swal(
										'<?php echo label("com_msg_success"); ?>!',
										'',
										'success'
									).then(function() {
										var table = $('#myTable').DataTable();
										var info = table.page.info();
										var length = info.pages;
										var page_current = info.page;
										fetch_data(page_current);
										$("#user_form").steps("setStep", 0);
									})
								} else if (data == "1") {
									swal({
										title: '<?php echo label("user_duplicate"); ?>',
										text: "",
										type: 'warning',
										showCancelButton: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: '<?php echo label("m_ok"); ?>'
									})
								} else if (data == "9") {
									swal({
										title: '<?php echo label("email_domain_not_match"); ?>',
										text: "",
										type: 'warning',
										showCancelButton: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: '<?php echo label("m_ok"); ?>'
									})
								} else {
									swal({
										title: '<?php echo label("sv_p_error_save"); ?>',
										text: "",
										type: 'warning',
										showCancelButton: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: '<?php echo label("m_ok"); ?>'
									})
								}

							}
						});
					} else if (isPass === 3) {
						swal(
							'<?php echo label("manager1_2_duplicate"); ?>',
							'',
							'warning'
						).then(function() {
							$('#emp_manage_b').val('');
							$('#emp_manage_b').focus();
						})
					}
				}
			}), $(".validation-wizard").validate({
				ignore: "input[type=hidden]",
				errorClass: "text-danger",
				successClass: "text-success",
				highlight: function(element, errorClass) {
					$(element).removeClass(errorClass)
				},
				unhighlight: function(element, errorClass) {
					$(element).removeClass(errorClass)
				},
				errorPlacement: function(error, element) {
					error.insertAfter(element)
				},
				rules: {
					email: {
						email: !0
					}
				}
			});
			$('.select2').select2();
		}
		$('select[name="com_id"]').on('change', function() {
			var com_id = $(this).val();
			$.ajax({
				url: '<?= base_url() ?>index.php/manage/recheckcompany',
				type: 'POST',
				data: {
					com_id: com_id
				},
				success: function(data) {

					$('#dep_id').html(data);
					$('#dep_id').val($('#dep_id option:first-child').val()).trigger('change');
				}
			});
			var operation = $('#operation').val();
			$.ajax({
				url: '<?= base_url() ?>index.php/manage/recheckusergroup',
				type: 'POST',
				data: {
					com_id: com_id,
					operation: operation
				},
				success: function(data) {

					$('#ug_id').html(data);
					$('#ug_id').val($('#ug_id option:first-child').val()).trigger('change');
				}
			});


			/*$('#emp_manage_a').select2({
			    ajax: {
			        url: "<?php echo base_url(); ?>index.php/manage/recheckmanage_data",
			        dataType: 'json',
			        type: 'POST',
			        delay: 250,
			        data: function (params) {
			            return {
			                emp_manage:'',
			                email:$('#email').val(),
			                emp_manage_type:'A',
			                com_id:com_id,
			                q: params.term, // search term
			                page: params.page
			            };
			        },
			        processResults: function (data, page) {
			            return {
			                results: $.map(data, function(obj) {
			                    return { id: obj.id, text: obj.value };
			                })
			            };
			        }
			    }
			});

			$('#emp_manage_b').select2({
			    ajax: {
			        url: "<?php echo base_url(); ?>index.php/manage/recheckmanage_data",
			        dataType: 'json',
			        type: 'POST',
			        delay: 250,
			        data: function (params) {
			            return {
			                emp_manage:'',
			                email:$('#email').val(),
			                emp_manage_type:'B',
			                com_id:com_id,
			                q: params.term, // search term
			                page: params.page
			            };
			        },
			        processResults: function (data, page) {
			            return {
			                results: $.map(data, function(obj) {
			                    return { id: obj.id, text: obj.value };
			                })
			            };
			        }
			    }
			});*/
		});

		$('select[name="com_id_search"]').on('change', function() {
			var com_id = $(this).val();
			fetch_data(0);
		});

		$('select[name="dep_id"]').on('change', function() {
			var dep_id = $(this).val();
			$.ajax({
				url: '<?= base_url() ?>index.php/manage/recheckdepartment',
				type: 'POST',
				data: {
					dep_id: dep_id,
					posi_id: ''
				},
				success: function(data) {

					$('#posi_id').html(data);
					$('#posi_id').val($('#posi_id option:first-child').val()).trigger('change');
				}
			});
		});

		$(document).ready(function() {
			var widthdivv = '<?php echo $widthdivv; ?>';
			$(document).on('click', '.license', function() {
				var u_id = $(this).attr("id");
				$("#modal-license").modal({
					backdrop: false
				});
				$('#u_id_role').val(u_id);

				$.ajax({
					url: '<?= base_url() ?>index.php/manage/rechk_headcol_user',
					type: 'POST',
					data: {
						u_id: u_id
					},
					dataType: "json",
					success: function(data) {

						if (data.countmenu == data.ru_print) {
							$('.chkcolall_print').prop('checked', true);
						} else {
							$('.chkcolall_print').prop('checked', false);
						}

						if (data.countmenu == data.ru_view) {
							$('.chkcolall_view').prop('checked', true);
						} else {
							$('.chkcolall_view').prop('checked', false);
						}

						if (data.countmenu == data.ru_add) {
							$('.chkcolall_add').prop('checked', true);
						} else {
							$('.chkcolall_add').prop('checked', false);
						}

						if (data.countmenu == data.ru_edit) {
							$('.chkcolall_edit').prop('checked', true);
						} else {
							$('.chkcolall_edit').prop('checked', false);
						}

						if (data.countmenu == data.ru_del) {
							$('.chkcolall_del').prop('checked', true);
						} else {
							$('.chkcolall_del').prop('checked', false);
						}
					}
				});
				$.ajax({
					url: '<?= base_url() ?>index.php/manage/loaddetailuser',
					type: 'POST',
					data: {
						u_id: u_id
					},
					success: function(data) {

						$('#load_detail').html(data);
					}
				});
			});

			$(document).on('click', '.update', function() {
				var u_id = $(this).attr("id");

				clear_dropify('.dropify');
				/*clear_dropify('.dropify_bg');*/

				to = $('#employ_date').datepicker({
					<?php if ($lang == "thai") { ?>
						language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
						thaiyear: true,
					<?php } ?>
					format: 'dd/mm/yyyy',
					autoclose: true
				})

				to = $('#inactivedate').datepicker({
					<?php if ($lang == "thai") { ?>
						language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
						thaiyear: true,
					<?php } else { ?>
						language: 'en',
						thaiyear: false,
					<?php } ?>
					format: 'dd/mm/yyyy',
					autoclose: true
				})
				to = $('#u_firstdate').datepicker({
					<?php if ($lang == "thai") { ?>
						language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
						thaiyear: true,
					<?php } else { ?>
						language: 'en',
						thaiyear: false,
					<?php } ?>
					format: 'dd/mm/yyyy',
					autoclose: true
				})
				$.ajax({
					url: "<?= base_url() ?>index.php/manage/update_user_data",
					method: "POST",
					data: {
						u_id_update: u_id
					},
					dataType: "json",
					success: function(data) {
						$('#user_form')[0].reset();
						$("#user_form").steps("setStep", 0);
						$("#modal-default").modal({
							backdrop: false
						});
						$('.modal-title').text('<?php echo label("edit_user"); ?>');
						$('#operation').val("Edit");
						<?php if ($com_admin != "com_associated") { ?>
							$.ajax({
								url: '<?= base_url() ?>index.php/querydata/recheckcompany',
								type: 'POST',
								data: {
									com_id: data.com_id
								},
								success: function(data_company) {
									$('#com_id').html(data_company);
								}
							});
						<?php } else { ?>
							$('#com_id').val(data.com_id);
						<?php } ?>
						$.ajax({
							url: '<?= base_url() ?>index.php/manage/recheckcompany',
							type: 'POST',
							data: {
								com_id: data.com_id,
								dep_id: data.dep_id
							},
							success: function(datadep) {

								$('#dep_id').html(datadep);

								$.ajax({
									url: '<?= base_url() ?>index.php/manage/recheckdepartment',
									type: 'POST',
									data: {
										dep_id: data.dep_id,
										posi_id: data.posi_id
									},
									success: function(dataposi) {

										$('#posi_id').html(dataposi);
									}
								});
							}
						});
						$.ajax({
							url: '<?= base_url() ?>index.php/manage/recheckusergroup',
							type: 'POST',
							data: {
								com_id: data.com_id,
								ug_id: data.ug_id
							},
							success: function(dataug) {

								$('#ug_id').html(dataug);
								$('#ug_id').val(data.ug_id).trigger('change');
							}
						});

						$('#emp_c').val(data.emp_c);
						$('#lang').val(data.lang);
						$('#prefix_th').val(data.prefix_th);
						$('#fname_th').val(data.fname_th);
						$('#lname_th').val(data.lname_th);
						$('#prefix_en').val(data.prefix_en);
						$('#fname_en').val(data.fname_en);
						$('#lname_en').val(data.lname_en);
						$('#gender').val(data.gender);
						/*
						             $('#address_th').val(data.address_th);     
						             $('#address_en').val(data.address_en);     */
						$('#work_phone').val(data.work_phone);
						$('#phone').val(data.phone);
						$('#email').val(data.email);
						document.getElementById("useri").readOnly = true;
						$('#useri').val(data.useri);
						$('#img_profile_ori').val(data.img_profile);

						$('#employ_date_var').val(data.employ_date_var);
						$('#inactivedate_var').val(data.inactivedate_var);
						$('#u_firstdate_var').val(data.u_firstdate_var);

						if (data.employ_date != "") {
							$("#employ_date").datepicker("setDate", data.employ_date);
						} else {
							$('#employ_date').val('');
						}
						if (data.inactivedate != "") {
							$("#inactivedate").datepicker("setDate", data.inactivedate);
						} else {
							$('#inactivedate').val('');
						}
						if (data.u_firstdate != "") {
							$('#u_firstdate').datepicker('setStartDate', data.u_firstdate);
							$("#u_firstdate").datepicker("setDate", data.u_firstdate);
						} else {
							$('#u_firstdate').val('');
						}
						$('#u_id').val(data.u_id);
						$('#emp_id').val(data.emp_id);

						$.ajax({
							url: '<?= base_url() ?>index.php/manage/recheckmanage_data_normal',
							type: 'POST',
							data: {
								emp_manage: data.emp_manage_a,
								email: data.email,
								emp_manage_type: 'B',
								com_id: data.com_id
							},
							success: function(dataug) {

								$('#emp_manage_a').html(dataug);
								$('#emp_manage_a').val(data.emp_manage_a).trigger('change');
							}
						});

						$.ajax({
							url: '<?= base_url() ?>index.php/manage/recheckmanage_data_normal',
							type: 'POST',
							data: {
								emp_manage: data.emp_manage_b,
								email: data.email,
								emp_manage_type: 'B',
								com_id: data.com_id
							},
							success: function(dataug) {

								$('#emp_manage_b').html(dataug);
								$('#emp_manage_b').val(data.emp_manage_b).trigger('change');
							}
						});

						if (data.img_profile != "") {
							var nameImage = "<?php echo REAL_PATH; ?>/uploads/profile/" + data.img_profile
							var drEvent = $('#img_profile').dropify({
								defaultFile: nameImage
							});
							drEvent = drEvent.data('dropify');
							drEvent.resetPreview();
							drEvent.clearElement();
							drEvent.settings.defaultFile = nameImage;
							drEvent.destroy();
							drEvent.init();

							var drEvent = $('.dropify').dropify({
								defaultFile: "<?php echo REAL_PATH; ?>/uploads/profile/" + data.img_profile,
							});

							drEvent.on('dropify.beforeClear', function(event, element) {
								$('#img_profile_ori').val("");
								return true;
							});
						} else {
							$('.dropify').dropify();
						}


						/*if(data.bgpic_user!=""){
						    var nameImage = "<?php echo REAL_PATH; ?>/uploads/bg_user/"+data.bgpic_user
						    var drEvent = $('#bgpic_user').dropify(
						    {
						      defaultFile: nameImage
						    });
						    drEvent = drEvent.data('dropify');
						    drEvent.resetPreview();
						    drEvent.clearElement();
						    drEvent.settings.defaultFile = nameImage;
						    drEvent.destroy();
						    drEvent.init();

						    var drEvent = $('.dropify').dropify({
						        defaultFile: "<?php echo REAL_PATH; ?>/uploads/bg_user/"+data.bgpic_user ,
						    });

						    drEvent.on('dropify.beforeClear', function(event, element){
						            $('#bgpic_user_ori').val("");
						            return true; 
						    });
						}else{
						    $('.dropify').dropify();
						}*/
					}
				});
			});
		});

		function clear_dropify(id) {
			var drEvent = $(id).dropify({
				defaultFile: ''
			});
			drEvent = drEvent.data('dropify');
			drEvent.resetPreview();
			drEvent.clearElement();
			drEvent.settings.defaultFile = '';
			drEvent.destroy();
			drEvent.init();
		}

		function chk_chkbox(name, mu_id, u_id) {
			var value_chk = 0;
			var field_sql = "";
			var remember = document.getElementById(name + '_' + mu_id);
			if (remember.checked) {
				value_chk = 1;
			}
			if (name == "chkrowall") {
				if (value_chk == 1) {
					$('.chkrow_' + mu_id).prop('checked', true);
				} else {
					$('.chkrow_' + mu_id).prop('checked', false);
				}
				field_sql = "chkrowall";
				var arr_field = ['ru_view', 'ru_add', 'ru_edit', 'ru_del', 'ru_print'];
				for (i = 0; i < arr_field.length; i++) {
					$.ajax({
						url: "<?= base_url() ?>index.php/manage/chk_chkbox_user",
						method: "POST",
						data: {
							field_sql_ug: arr_field[i],
							value_chk_ug: value_chk,
							u_idonrole_ug: u_id,
							mu_idonrole_ug: mu_id
						},
						dataType: "json",
						success: function(data) {

						}
					});
				}
			} else {
				if (name == "chkenable") {
					field_sql = "ru_view";
				} else if (name == "chkadd") {
					field_sql = "ru_add";
				} else if (name == "chkedit") {
					field_sql = "ru_edit";
				} else if (name == "chkdel") {
					field_sql = "ru_del";
				} else if (name == "chkprint") {
					field_sql = "ru_print";
				}
				<?php if ($user['Is_admin'] != "0") { ?>
					$.ajax({
						url: "<?= base_url() ?>index.php/manage/chk_chkbox_user",
						method: "POST",
						data: {
							field_sql_ug: field_sql,
							value_chk_ug: value_chk,
							u_idonrole_ug: u_id,
							mu_idonrole_ug: mu_id
						},
						dataType: "json",
						success: function(data) {

						}
					});
				<?php } ?>
			}

			var $chkrow = $('.chkrow_' + mu_id);
			var countCheckedchkrow = $chkrow.filter(':checked').length;
			if (countCheckedchkrow == 5) {
				$('#chkrowall_' + mu_id).prop('checked', true);
			} else {
				$('#chkrowall_' + mu_id).prop('checked', false);
			}

			var $chkcol_ru_view = $('.chkcol_ru_view');
			var countCheckedchkcol_ru_view = $chkcol_ru_view.filter(':checked').length;
			var $chkcol_ru_add = $('.chkcol_ru_add');
			var countCheckedchkcol_ru_add = $chkcol_ru_add.filter(':checked').length;
			var $chkcol_ru_edit = $('.chkcol_ru_edit');
			var countCheckedchkcol_ru_edit = $chkcol_ru_edit.filter(':checked').length;
			var $chkcol_ru_del = $('.chkcol_ru_del');
			var countCheckedchkcol_ru_del = $chkcol_ru_del.filter(':checked').length;
			var $chkcol_ru_print = $('.chkcol_ru_print');
			var countCheckedchkcol_ru_print = $chkcol_ru_print.filter(':checked').length;
			var count_menu = $('#count_menu').val();

			if (count_menu == countCheckedchkcol_ru_print) {
				$('.chkcolall_print').prop('checked', true);
			} else {
				$('.chkcolall_print').prop('checked', false);
			}

			if (count_menu == countCheckedchkcol_ru_view) {
				$('.chkcolall_view').prop('checked', true);
			} else {
				$('.chkcolall_view').prop('checked', false);
			}

			if (count_menu == countCheckedchkcol_ru_add) {
				$('.chkcolall_add').prop('checked', true);
			} else {
				$('.chkcolall_add').prop('checked', false);
			}

			if (count_menu == countCheckedchkcol_ru_edit) {
				$('.chkcolall_edit').prop('checked', true);
			} else {
				$('.chkcolall_edit').prop('checked', false);
			}

			if (count_menu == countCheckedchkcol_ru_del) {
				$('.chkcolall_del').prop('checked', true);
			} else {
				$('.chkcolall_del').prop('checked', false);
			}
		}
		$('#import_button').click(function() {
			$("#modal-import_user").modal({
				backdrop: false
			});
			clear_dropify('#file_import');
			var com_id = $('#com_id_search').val();
			$('#com_id_import_user').val(com_id);
			$('#result_import_user').html('');
		});
		$('#export_button').click(function() {
			var com_id = $('#com_id_search').val();
			window.open('<?= base_url() ?>exportdata/export_user/' + com_id);
		});
		$('#Listlearnerincomplete').click(function() {
			$('#myTable_learnerincomplete').DataTable().destroy();
			var table = $('#myTable_learnerincomplete').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_learnerincomplete", message);
            }).DataTable({
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
				"scrollX": true,
				"ajax": {
					url: '<?= base_url() ?>index.php/manage/fetch_detail_learnerincomplete/',
					type: 'GET',
					data : {lang: "<?php echo $lang; ?>"}
				},
				<?php if ($btn_print == "1") { ?>
					dom: 'Bfrtip',
					buttons: [
						'copy', 'excel', 'print'
					]
				<?php } ?>
			});
		});

		$('#add_button').click(function() {
			$('.modal-title').text('<?php echo label("create_user"); ?>');
			$("#modal-default").modal({
				backdrop: false
			});
			$('#user_form')[0].reset();
			$('#operation').val("Add");
			$('.dropify').dropify();
			$('#dep_id').empty();
			$('#ug_id').empty();
			$('#posi_id').empty();
			$('.dropify').dropify({
				defaultFile: "",
			});

			$("#user_form").steps("setStep", 0);
			clear_dropify('#img_profile');
			//clear_dropify('#bgpic_user');
			document.getElementById("useri").readOnly = false;
			<?php if ($com_admin == "com_associated") { ?>
				var com_id = '<?php echo $com_id; ?>';

				$.ajax({
					url: '<?= base_url() ?>index.php/manage/recheckcompany',
					type: 'POST',
					data: {
						com_id: com_id
					},
					success: function(data) {
						$('#dep_id').html(data);
						$('#dep_id').val($('#dep_id option:first-child').val()).trigger('change');
					}
				});

				$.ajax({
					url: '<?= base_url() ?>index.php/manage/recheckusergroup',
					type: 'POST',
					data: {
						com_id: com_id
					},
					success: function(data) {
						$('#ug_id').html(data);
						$('#ug_id').val($('#ug_id option:first-child').val()).trigger('change');
					}
				});
			<?php } else { ?>
				$.ajax({
					url: '<?= base_url() ?>index.php/querydata/recheckcompany',
					type: 'POST',
					data: {
						com_id: ''
					},
					success: function(data_company) {
						$('#com_id').html(data_company);
						$('#com_id').val($('#com_id_search').val()).trigger('change');
					}
				});
			<?php } ?>

			/*  to = $('#employ_date').datepicker({
			            <?php if ($lang == "thai") { ?>
			                  language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
			                  thaiyear: true,  
			            <?php } ?>
			      format: 'dd/mm/yyyy',
			      autoclose: true
			  })*/

			to = $('#inactivedate').datepicker({
				<?php if ($lang == "thai") { ?>
					language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
					thaiyear: true,
				<?php } else { ?>
					language: 'en',
					thaiyear: false,
				<?php } ?>
				format: 'dd/mm/yyyy',
				autoclose: true
			})
			to = $('#u_firstdate').datepicker({
				<?php if ($lang == "thai") { ?>
					language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
					thaiyear: true,
				<?php } else { ?>
					language: 'en',
					thaiyear: false,
				<?php } ?>
				format: 'dd/mm/yyyy',
				autoclose: true
			})
			var startDate = new Date();
			$('#u_firstdate').datepicker('setStartDate', startDate);
			$('#inactivedate').datepicker('setStartDate', startDate);
		});

		$(document).on('click', '.update_status_learner', function() {
			swal({
				title: 'คุณแน่ใจหรือไม่ อัพเดทสถานะผู้เรียนที่ยังเรียนไม่เสร็จทั้งหมด',
				text: "",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: '<?php echo label('m_ok'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>'
			}).then(function(isChk) {
				if (isChk.value) {
					$.ajax({
						url: "<?= base_url() ?>index.php/manage/update_status_learner",
						method: "POST",
						success: function(data) {
							if (data == "2") {
								swal(
									'Success !',
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
								})
							} else {
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
		});
		$(document).on('click', '.delete', function() {
			var emp_id = $(this).attr("id");
			swal({
				title: '<?php echo label('wg_delete_msg'); ?>',
				text: "",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: '<?php echo label('wg_delete'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>'
			}).then(function(isChk) {
				if (isChk.value) {
					$.ajax({
						url: "<?= base_url() ?>index.php/manage/delete_user_data",
						method: "POST",
						data: {
							emp_id_delete: emp_id
						},
						success: function(data) {
							if (data == "2") {
								swal(
									'<?php echo label("com_msg_delete"); ?>!',
									'',
									'success'
								).then(function() {
									var table = $('#myTable').DataTable();
									var info = table.page.info();
									var length = info.pages;
									var page_current = info.page;
									fetch_data(page_current);
								})
							} else if (data == "1") {
								swal({
									title: '<?php echo label("wg_msg_use"); ?>',
									text: "",
									type: 'warning',
									showCancelButton: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: '<?php echo label('m_ok'); ?>'
								})
							} else {
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
		});



		function fetch_data_enroll(page_num, emp_id) {
			$('#myTable_enroll').DataTable().destroy();
			var table = $('#myTable_enroll').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_enroll", message);
            }).DataTable({
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
				"scrollX": true,
				"ajax": {
					url: '<?= base_url() ?>index.php/manage/fetch_detail_userenroll/',
					type: 'GET',
					data: {
						emp_id: emp_id,
						lang: "<?php echo $lang; ?>"
					},
				},
				"columns": [{
						data: "button"
					},
					{
						data: "cname"
					},
					{
						data: "cos_status"
					},
					{
						data: "status_learner"
					},
					{
						data: {
							_: "cosen_firsttime.display",
							sort: "cosen_firsttime.timestamp"
						}
					},
					{
						data: {
							_: "cosen_finishtime.display",
							sort: "cosen_finishtime.timestamp"
						}
					}
				],
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
				}
			});
		}

		$(document).on('click', '.dataenroll', function() {
			var emp_id = $(this).attr("id");
			$("#modal-listenroll").modal({
				backdrop: false
			});
			fetch_data_enroll(0, emp_id);
		});


		$(document).on('click', '.regencert', function() {
			var cos_id = $(this).attr("id");
			var emp_id = $(this).data("empid");

			$.ajax({
				url: "<?= base_url() ?>index.php/certificate/createfilebyuseronly",
				method: "POST",
				data: {
					cos_id: cos_id,
					emp_id: emp_id
				},
				dataType: "json",
				success: function(data) {
					fetch_data_enroll(0, emp_id);
				}
			});
		});


		$(document).on('click', '.resendfirsttime', function() {
			var emp_id = $(this).attr("id");
			swal({
				title: '<?php echo label('sv_b_send_email_noti'); ?>',
				text: "",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: "#3498db",
				confirmButtonText: '<?php echo label('m_ok'); ?>',
				cancelButtonText: '<?php echo label('cancel'); ?>'
			}).then(function(isChk) {
				if (isChk.value) {
					$.ajax({
						url: "<?= base_url() ?>index.php/manage/resendmail_firsttime",
						method: "POST",
						data: {
							emp_id: emp_id
						},
						dataType: "json",
						success: function(data) {
							if (data.status == "2") {
								swal(
									'<?php echo label("sentmail_success"); ?>',
									'',
									'success'
								).then(function() {
									var table = $('#myTable').DataTable();
									var info = table.page.info();
									var length = info.pages;
									var page_current = info.page;
									fetch_data(page_current);
								})
							} else {
								swal({
									title: '<?php echo label('add_emptocourse_error'); ?>',
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
		});
	</script>
</body>


</html>