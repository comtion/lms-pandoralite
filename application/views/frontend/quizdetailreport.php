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
              <div class="col-md-12">
                <div class="labelHeadLine">
                  <h2 class=""><?php echo label('report').label('quiz').' : '.label('course').' - '.$course['cname']; ?></h2>
                </div>
              </div>
            </div>
						<div class="row">
							<div class="tableNav">
								<!--button class="btn btn-default left" type="submit"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
								<button class="btn btn-default right" type="submit"><i class="fa fa-caret-right" aria-hidden="true"></i></button-->
							</div>
							<div class="col-md-12">
								<div class="table-wrapper">
									<table class="table table-striped" id="allquiz-table">
										<thead>
											<tr>
												<th style="text-align: center;"><?php echo label('r_no'); ?></th>
                        <th style="text-align: center;"><?php echo label('qName'); ?></th>
                        <th style="text-align: center;"><?php echo label('maxScore'); ?></th>
                        <th style="text-align: center;"><?php echo label('r_viewDetail'); ?></th>
											</tr>
										</thead>
										<tbody>
                      <?php $i = 1; ?>
                      <?php foreach($quizs as $no=>$quiz) { ?>
											<tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td style="text-align: center;"><a href="<?php echo base_url().'report/loadQuestionReport/'.$quiz['qcode']; ?>"><?php echo $quiz['quiz_name']; ?></a></td>
                        <td style="text-align: center;"><?php echo $quiz['max_score']; ?></td>
                        <td style="text-align: center;"><a href="<?php echo base_url().'report/loadQuizReportDetail/'.$quiz['qcode']; ?>"><i class="fas fa-table" aria-hidden="true"></i></a></td>
											</tr>
                      <?php $i++;} ?>
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
