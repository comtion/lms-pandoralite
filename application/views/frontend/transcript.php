<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/extension/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />

  </head>
  <body>
    <div id="superwrapper">
  	<?php $this->load->view('frontend/inc/inc-header.php'); ?>

		<!--content-->
		<div class="container dashboard main">
			<a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-custom-arrow" aria-hidden="true"></i></a>
			<div class="row">
				<?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
				<div class="content dashWrap">
					<div class="dashElement page">
						<div class="row">
              <div class="col-md-12">
                <div class="dashHeader">
                  <h2><?php echo $employee['prefix'].$employee['fname'].' '.$employee['lname']; ?></h2>
                  <div class="dashpageWrap">
                    <label><?php echo label('r_position'); ?>:</label> <?php echo $employee['postname']; ?>
                    <label><?php echo label('r_organization'); ?>:</label> <?php echo $employee['org_desc']; ?>
                    <br>
                    <label><?php echo label('r_courses'); ?>:</label> <?php echo $employee['courses']; ?>
                    <br>
                    <label><?php echo label('r_passed'); ?>:</label> <?php echo $employee['passed']; ?>
                  </div>
                </div>
              </div>
							<div class="tableNav">
								<!--button class="btn btn-default left" type="submit"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
								<button class="btn btn-default right" type="submit"><i class="fa fa-caret-right" aria-hidden="true"></i></button-->
							</div>
							<div class="col-md-12">
								<div class="table-wrapper">
									<table class="table table-striped" id="transcript-table">
                    <thead>
											<tr>
												<th><?php echo label('r_no'); ?></th>
                        <th><?php echo label('r_course_name'); ?></th>
                        <th><?php echo label('r_course_type'); ?></th>
                        <th><?php echo label('r_result'); ?></th>
                        <th><?php echo label('r_max_score'); ?></th>
                        <th><?php echo label('r_passing_score'); ?></th>
                        <th><?php echo label('r_socre'); ?></th>
                        <th><?php echo label('r_grade'); ?></th>
                        <th><?php echo label('r_enroll_on'); ?></th>
                        <th><?php echo label('r_last_accessed_on'); ?></th>
                        <th><?php echo label('r_course_finish_on'); ?></th>
                        <th><?php echo label('r_passed_year'); ?></th>
											</tr>
										</thead>
										<tbody>
                      <?php foreach ($courses as $no=>$course) {
    ?>
											<tr>
												<th scope="row"><?php echo $no+1; ?></th>
                        <td><?php echo $course['cname']; ?></td>
                        <td><?php echo $course['coursetype']; ?></td>
                        <td><?php echo $course['result']; ?></td>
                        <td><?php echo $course['max_score']; ?></td>
                        <td><?php echo sprintf('%d%%', $course['goal_score']); ?></td>
                        <td><?php echo $course['sum_score']; ?></td>
                        <td><?php echo $course['grade']; ?></td>
                        <td><?php
                          if ($course['time_request'] == '0000-00-00 00:00:00' || empty($course['time_request']) || $course['time_request'] == null) {
                              echo '';
                          } else {
                              echo changeDate($course['time_request'], $lang);
                          } ?></td>
                        <td><?php
                          if ($course['time'] == '0000-00-00 00:00:00' || empty($course['time']) || $course['time'] == null) {
                              echo '';
                          } else {
                              echo changeDate($course['time'], $lang);
                          } ?></td>
                        <td><?php
                          if ($course['time_end'] == '0000-00-00 00:00:00' || empty($course['time_end']) || $course['time_end'] == null) {
                              echo '';
                          } else {
                              echo changeDate($course['time_end'], $lang);
                          } ?></td>
                        <td><?php echo $course['year']; ?></td>
											</tr>
                      <?php
} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
        <br><br><br><br><br><br><br><br>
			</div>
		</div>


		<!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.tableTools.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jszip.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.print.min.js"></script>
    <!--script src="<?php echo HTTP_JS_PATH; ?>tableCarousel.js"></script-->
    <script src="<?php echo HTTP_JS_PATH; ?>report.js"></script>

    </div>
  </body>
</html>
