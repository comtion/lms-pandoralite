<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
							<div class="tableNav">
								<!--button class="btn btn-default left" type="submit"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
								<button class="btn btn-default right" type="submit"><i class="fa fa-caret-right" aria-hidden="true"></i></button-->
							</div>
							<div class="col-md-12">
								<div class="table-wrapper">
									<table class="table table-striped" id="allcourse-table">
										<thead>
											<tr>
												<th><?php echo label('r_no'); ?></th>
                        <th><?php echo label('cosCode'); ?></th>
                        <th><?php echo label('r_course_name'); ?></th>
                        <th><?php echo label('r_start_on'); ?></th>
                        <th><?php echo label('r_finish_on'); ?></th>
                        <th><?php echo label('r_cos_status'); ?></th>
                        <th><?php echo label('r_number_of_students'); ?></th>
                        <!-- <th><?php //echo label('r_numb_pending'); ?></th> -->
                        <th><?php echo label('r_second_pending'); ?></th>
                        <th><?php echo label('r_ccompleted'); ?></th>
                        <th><?php echo label('r_cuncompleted'); ?></th>
                        <th><?php echo label('r_noshow'); ?></th>
                        <th><?php echo label('r_sick'); ?></th>
                        <th><?php echo label('r_bi'); ?></th>
                        <th><?php echo label('r_b7'); ?></th>
                        <th><?php echo label('r_a7'); ?></th>
											</tr>
										</thead>
										<tbody>
                      <?php foreach($courses as $no=>$course) { ?>
											<tr>
												<th scope="row"><?php echo $no+1; ?></th>
                        <td><?php echo $course['ucode']; ?></td>
                        <td><a href="<?php echo base_url().'report/loadCourseGraph/'.$course['ccode']; ?>"><?php echo $course['cname']; ?></a></td>
                        <td><?php echo $course['time_open']; ?></td>
                        <td><?php echo $course['time_end']; ?></td>
                        <td><?php if($course['status'] == 0) {
                          echo label('r_canceled');
                        } else {
                          echo label('r_normal');
                        } ?></td>
                        <td><?php echo $course['number']; ?></td>
                        <!-- <td><?php //echo $course['pending']; ?></td> -->
                        <td><?php echo $course['secpending']; ?></td>
                        <td><?php echo $course['ppassC']; ?></td>
                        <td><?php echo $course['pfailC']; ?></td>
                        <td><?php echo $course['pnoshow']; ?></td>
                        <td><?php echo $course['psick']; ?></td>
                        <td><?php echo $course['pbi']; ?></td>
                        <td><?php echo $course['pccbef']; ?></td>
                        <td><?php echo $course['pccaf']; ?></td>
											</tr>
                      <?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
      <br><br><br><br><br><br><br><br><br><br><br><br>
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
