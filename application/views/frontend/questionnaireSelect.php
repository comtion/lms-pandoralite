<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
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
									<h2 class="dark"><?php echo label('questionnaireEdit')?></h2>
								</div>
								<br>
								<div class="dashContent">									
									<div class="row">
										<div class="col-md-12">
											<?php $row=0;
												foreach($datas as $data): 
													if($data->hidden=="1"): $row++;?>
												<div class="col-md-12 courseList">
													<div class="courseListWrap" style="box-shadow:0 2px 0 rgba(0, 0, 0, .1);">
														<h3><?php echo "[ ".$data->title." ] : คำชี้แจ้ง ".$data->explanation;?></h3>
														<br>
														<div class="text-right">
															<a class="btn btn-default" href="<?php echo base_url().'questionnaire/detail/'.$data->id;?>">
																<i class="fa fa-plus fa-lg" style="padding: 2px;"></i>
																<?php echo label('prePlusNo') ?>
															</a>
															<a class="btn btn-default" href="<?php echo base_url().'questionnaire/edit/'.$data->id;?>" style="margin-right: 3px;">
																<i class="fa fa-pencil fa-lg" style="padding: 2px; color: black;"></i>
																<?php echo label('preEditNo') ?>
															<a class="btn btn-default remove-<?php echo $data->id; ?>">
																<i class="fa fa-trash-o fa-lg" style="padding: 2px;"></i>
																<?php echo label('quesDelete') ?>
															</a>
															<br>
															<i> <?php echo label('quesEditDate').' '.substr($data->time_mod,8,2).' '.$thaimonth[substr($data->time_mod,5,2)-1].' '.(substr($data->time_mod,0,4)+543).' '.label('quesEditTime').' '.substr($data->time_mod,-8); ?></i>
														</div>
														<br>
													</div>
												</div>
												<script>
													$(document).on("click", "a.remove-<?php echo $data->id; ?>", function() {
													    if (confirm('<?php echo label('quesRemoveNo').' ['.$data->id.'] : '.$data->title.' '; ?>?'))
													    {
													    	window.location = "<?php echo base_url().'questionnaire/delete/'.$data->id;?>";	
													    }
													});
												</script>
											<?php 	endif;
												endforeach; ?>
												<?php if($row==0):?>
													<h3 style="text-align:center;color:#5e5e5e;margin-bottom:20px;">ไม่พบ<?php echo label('questionnaire'); ?></h3>
												<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

	<!--footer-->
	<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
	<?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

</body>
</html>
