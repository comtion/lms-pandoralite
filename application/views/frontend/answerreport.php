<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/extension/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
                  <h2><?php echo $question['questions_name']; ?></h2>
                  <!--?php if($course['status'] == 0) { ?>
                    <div class="dashpageWrap">
                      <label>?php echo label('r_note'); ?></label>
                    </div>
                  ?php } ?-->
                </div>
              </div>
              <div class="col-md-12">
                <div id="canvas-holder" style="width: 100%;">
              		<canvas id="canvas"></canvas>
              	</div>
                <!-- <div id="canvas-holder" style="width:100%">
              		<canvas id="chart-area"></canvas>
              	</div> -->
              </div>
							<div class="tableNav">
								<!--button class="btn btn-default left" type="submit"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
								<button class="btn btn-default right" type="submit"><i class="fa fa-caret-right" aria-hidden="true"></i></button-->
							</div>
							<div class="col-md-12">
								<div class="table-wrapper">
									<table class="table table-striped" id="course-table">
										<thead>
											<tr>
                        <th><?php echo label('correct_answer'); ?></th>
												<th><?php echo $answers['c1']; ?></th>
                        <th><?php echo $answers['c2']; ?></th>
                        <?php if( isset( $answers['c3'] ) ){ ?>
                          <th><?php echo $answers['c3']; ?></th>
                        <?php } ?>
                        <?php if( isset( $answers['c4'] ) ){ ?>
                          <th><?php echo $answers['c4']; ?></th>
                        <?php } ?>
											</tr>
										</thead>
										<tbody>
                      <td><?php echo $answers['correctAns']; ?></td>
                      <td><?php echo $answers['c1count'] == "" ? 0 : $answers['c1count']; ?></td>
                      <td><?php echo $answers['c2count'] == "" ? 0 : $answers['c2count']; ?></td>
                      <?php if( isset( $answers['c3'] ) ){ ?>
                        <td><?php echo $answers['c3count'] == "" ? 0 : $answers['c3count']; ?></td>
                      <?php } ?>
                      <?php if( isset( $answers['c4'] ) ){ ?>
                        <td><?php echo $answers['c4count'] == "" ? 0 : $answers['c4count']; ?></td>
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
    <!--script src="<?php //echo HTTP_JS_PATH; ?>tableCarousel.js"></script-->
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH; ?>Chart.bundle.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH; ?>utils.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>report.js"></script>
    <script>
    <?php if( $question['type'] == "multi"){ ?>
  		var color = Chart.helpers.color;
  		var horizontalBarChartData = {
  			labels: ['<?php echo $answers['c1']; ?>', '<?php echo $answers['c2']; ?>', '<?php echo $answers['c3']; ?>', '<?php echo $answers['c4']; ?>'],
  			datasets: [{
  				label: 'Answer Count',
  				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
  				borderColor: window.chartColors.red,
  				borderWidth: 1,
  				data: [
  						<?php echo $answers['c1count'] == "" ? 0 : $answers['c1count']; ?>,
              <?php echo $answers['c2count'] == "" ? 0 : $answers['c2count']; ?>,
              <?php echo $answers['c3count'] == "" ? 0 : $answers['c3count']; ?>,
              <?php echo $answers['c4count'] == "" ? 0 : $answers['c4count']; ?>
  				]
  			}]
  		};
    <?php }else if( $question['type'] == "twoChoice" ){ ?>
      var color = Chart.helpers.color;
  		var horizontalBarChartData = {
  			labels: ['<?php echo $answers['c1']; ?>', '<?php echo $answers['c2']; ?>'],
  			datasets: [{
  				label: 'Answer Count',
  				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
  				borderColor: window.chartColors.red,
  				borderWidth: 1,
  				data: [
  						<?php echo $answers['c1count'] == "" ? 0 : $answers['c1count']; ?>,
              <?php echo $answers['c2count'] == "" ? 0 : $answers['c2count']; ?>
  				]
  			}]
  		};
    <?php } ?>
      window.onload = function() {
  			var ctx = document.getElementById('canvas').getContext('2d');
  			window.myHorizontalBar = new Chart(ctx, {
  				type: 'horizontalBar',
  				data: horizontalBarChartData,
  				options: {
  					// Elements options apply to all of the options unless overridden in a dataset
  					// In this case, we are setting the border of each horizontal bar to be 2px wide
  					elements: {
  						rectangle: {
  							borderWidth: 2,
  						}
  					},
  					responsive: true,
  					legend: {
  						position: 'right',
  					}/*,
  					title: {
  						display: true,
  						text: 'Chart.js Horizontal Bar Chart'
  					}*/
  				}
  			});

  		};


  	</script>
    </div>
  </body>
</html>
