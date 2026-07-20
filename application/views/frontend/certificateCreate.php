<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
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
									<h2 class="dark"><?php echo label('create').label('certificate') ?></h2>
								</div>
								<br>
								<div class="dashContent">
									<div class="row">
										<div class="col-sm-3 courseCat">
											
										</div>
										<div class="col-sm-9">
											<?php echo form_open_multipart('Certificate/generate'); ?>
											<!-- Certificate Pic -->
											<label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('ceCertPic'); ?></label>
											<div class="col-sm-9 form-group has-success has-feedback">

												<Select id="certimg" id="type" class="form-control">
													<option value="default"><?php echo label('ceCertDefault'); ?></option>
													<option value="upload"><?php echo label('ceCertUpload');?></option>
												</Select>
												<div id="default" class="type" style="display:none">  </div>

												<div id="upload" class="type" style="display:none">
													<div class="btn btn-default image-preview-input" >
														<span class="glyphicon glyphicon-folder-open"></span>
														<span class="image-preview-input-title"><?php echo label('ceCertBrowsePic'); ?></span>
														<input type="file" accept="image/jpeg" name="cert_image" onchange="ValidateSingleInput(this);"/>
													</div>
													<button type="button" class="btn btn-danger image-preview-clear" style="display:none;">
														<span class="glyphicon glyphicon-remove" height='auto'></span> <?php echo label('clear'); ?>
													</button>
												</div>

											</div>

											<!-- Certificate File -->
											<label class="col-sm-3 control-label" for="inputSuccess1"><?php echo label('ceCertFile'); ?></label>
											<div class="col-sm-9 form-group has-success has-feedback">			
												<input type="file" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
											</div>

										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-12 text-center">
											<div class="saveWrap">
												<button name="fileSubmit" value="normal" class="btn btn-default return" type="submit">
													<img src="<?php echo REAL_PATH; ?>/assets/images/icons/ok_w.png"> 
													<?php echo label('create').label('certificate'); ?>
												</button>
												<div class="btn btn-default cancel" style="background-color:red;">
													<a style="color:#fff;" href="<?php echo REAL_PATH.'/dashboard/'?>">
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
		</div>
	</div>
	<!--footer-->
	<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
	<?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

	<script src="<?php echo REAL_PATH;?>/assets/js/certificate.js"></script>

</body>
</html>
