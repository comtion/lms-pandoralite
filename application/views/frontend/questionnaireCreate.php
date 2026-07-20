<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
	$lang_set = $lang;
?>
<script src="<?php echo REAL_PATH; ?>/assets/ckeditor/ckeditor.js"></script>
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
									<h2 class="dark"><?php echo label('questionnaireCreate') ?></h2>
								</div>
								<br>

								<?php echo form_open_multipart('questionnaire/createQuestionnaire'); ?>
									<div class="dashContent" id="addmorequestion">
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3 control-label" for="inputSuccess">
													<?php echo label('quesName'); ?>
												</label>
												<div class="col-sm-9 form-group has-success has-feedback">
													<input name="questionnaireName" id="questionnaireName"  class="form-control" type="text" required>
												</div>

												<label class="col-sm-3 control-label" for="inputSuccess">
													<?php echo label('quesExplanation'); ?>
												</label>
												<div class="col-sm-9 form-group has-success has-feedback">
													<input name="questionnaireExplanation" id="questionnaireExplanation"  class="form-control" type="text" required>
												</div>

												<label class="col-sm-3 control-label" for="inputSuccess">
													<?php echo label('quessuggestion_status'); ?>
												</label>
												<div class="col-sm-9 form-group has-success has-feedback">
													<div class="form-check form-check-inline">
													  <input class="form-check-input" type="radio" name="suggestion_status" id="suggestion_status1" value="1" checked>
													  <label class="form-check-label" for="suggestion_status1">มี</label>
													</div>
													<div class="form-check form-check-inline">
													  <input class="form-check-input" type="radio" name="suggestion_status" id="suggestion_status2" value="0">
													  <label class="form-check-label" for="suggestion_status2">ไม่มี</label>
													</div>
												</div>

												<label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('quesFile'); ?></label>
												<div class="col-sm-9 form-group has-success has-feedback">			
													<input type="file" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
												</div>

											</div>
										</div>
										<hr/>
										<div class="row">
											<div class="col-sm-12 text-center">
												<div class="saveWrap">
													<button class="btn btn-default return <?php echo $lang_set; ?>" type="submit">
														<img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png">
														<?php echo label('saveR') ?>
													</button>
													<a style="color:#fff;" class="btn btn-danger"  href="<?php echo REAL_PATH.'/dashboard/'?>">
														<img src="<?php echo REAL_PATH; ?>/assets/images/icons/no_w.png"> <?php echo label('cancel') ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		<!--footer-->
		<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
		<?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
		<script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
		<script>
		  	$(document).ready( function(){
		    	$('input[type="text"]').attr('autocomplete', 'off');
			});
		</script>
	</body>
</html>
