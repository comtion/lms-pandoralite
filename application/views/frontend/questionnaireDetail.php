<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
	$lang_set = $lang;
?>
<script src="<?php echo REAL_PATH; ?>/assets/ckeditor/ckeditor.js"></script>
<head>
	<style type="text/css">
		
	.removeno-btn:hover{
		text-decoration: none;
	}
	.fa-minus-circle{
		color: red;
	}
	.fa-minus-circle:hover{
		color: gray;
	}
	</style>
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
									<h2 class="dark" align="center"><?php echo label('questionnaire').$questionnaireName ?></h2>
								</div>
								<br>

								<?php echo form_open_multipart('questionnaire/updateDetail'); ?>
									<div class="dashContent" id="addmorequestion">
										<div class="row">
											<div class="col-sm-12">
												<p style="font-size: 16px"><?php echo label('quesExplanation')." ".$questionnaireExplanation ?></p><hr>
												<input type="hidden" name="questionnaire_id" id="questionnaire_id" value="<?php echo $questionnaire_id;?>">

												<hr>
				                                <div class="table-wrapper">
				                                    		 <?php $row = 1;  foreach ($detail_que as $detail_val) {?>
				                                    		 	<input name="detailid[]" type="hidden" value="<?php echo $detail_val['id']; ?>">
																<!-- Start Collapse -->
																<div data-toggle="collapse" data-target="#addE-<?php echo $detail_val['id']; ?>" style="cursor: pointer;">
																	<div class="row" style="border-bottom: 1px solid #ccc; width: 93.5%; margin:0 auto; padding: 5px 0 5px 15px;">
																		<div class="col-sm-10">
																			<a class="removeno-btn removeno-func-<?php echo $detail_val['id'].'-'.$detail_val['id']; ?>">
																				<i class="fa fa-minus-circle fa-lg"></i>
																			</a>
																			<?php echo $detail_val['heading']; ?> <?php echo $detail_val['detail']; ?>
																		</div>
																		<div class="col-sm-2 text-right">
																			<i class="fa fa-angle-down " style="margin-top: 2px;"></i>
																		</div>
																	</div>
																</div>
																<!-- End Collapse -->
																<div class="col-sm-12 collapse" id="addE-<?php echo $detail_val['id']; ?>">
																	<br>
																	<label class="col-sm-3 control-label" for="inputSuccess">
																		<?php echo label('questitle'); ?>
																	</label>
																	<div class="col-sm-9 form-group">
																		<p>
																			<input name="queheadingName[]" id="queheadingName[]"  class="form-control" type="text" value="<?php echo $detail_val['heading']; ?>">
																		</p>
																	</div>

																	<label class="col-sm-3 control-label" for="inputSuccess">
																		<?php echo label('quesdetail'); ?>
																	</label>
																	<div class="col-sm-9 form-group">
																		<p>
																			<input name="queDetailName[]" id="queDetailName[]"  class="form-control" type="text" value="<?php echo $detail_val['detail']; ?>">
																		</p>
																	</div>
																</div>
																<!-- end no.2 -->

																<script>

																	$(document).on("click", "a.removeno-func-<?php echo $detail_val['id'].'-'.$detail_val['id']; ?>", function() {
																	    if (confirm('<?php echo label('quesRemoveNo').' '.$detail_val['heading'].' : '.$detail_val['detail']; ?> ?'))
																	    {
																	    	window.location = "<?php echo base_url().'questionnaire/remove/'.$detail_val['id'].'/'.$questionnaire_id; ?>";
																	    }
																	});
																</script>
				                                    		 <?php } ?>
				                                    		 <div class="row text-right"  id="addmoreno-1" style="margin-right: 30px; margin-top: 5px;">
																<br>
																<a class="btn btn-default" id="add-1" onclick="onClick_1(); add_no_1();">
																	<i class="fa fa-plus fa-lg" style="padding: 2px;"></i>
																	<?php echo label('prePlusNo') ?>
																</a>
																<a class="btn btn-default" id="removeno1" onclick="remove_no_1();" >
																	<i class="fa fa-Minus fa-lg" style="padding: 2px;"></i>
																	<?php echo label('preMinusNo') ?>
																</a>
															</div><br>
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
		</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

		<!--footer-->
		<?php $this->load->view('frontend/inc/inc-footer.php'); ?>
		<?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
		<script src="<?php echo REAL_PATH; ?>/assets/js/create.js"></script>
		<script>
		  	$(document).ready( function(){
		    	$('input[type="text"]').attr('autocomplete', 'off');
			});
		    $("#removeno1").hide();

		    $("#add-1").click(function(){
		        $("#removeno1").fadeIn("slow");
		    });
			var count_no_1 = <?php echo countArray($detail_que); ?>;
			function add_no_1() {			
				$("#addmoreno-1").before('<div data-toggle="collapse" data-target="#addE-'+count_no_1+'" id="A'+count_no_1+'" style="cursor: pointer;"><input name="detailid[]" type="hidden" value="0"><div class="row" style="border-bottom: 1px solid #ccc; width: 93.5%; margin:0 auto; padding: 5px 0 5px 15px;"><div class="col-sm-10">(<?php echo label('preNew'); ?>'+count_no_1+')</div><div class="col-sm-2 text-right"><i class="fa fa-angle-down " style="margin-top: 2px;"></i></div><div class="col-sm-2 text-right"></div></div></div><div class="col-sm-12 collapse" id="addE-'+count_no_1+'"><br><label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('questitle'); ?></label><div class="col-sm-9 form-group"><p><input name="queheadingName[]" id="queheadingName-'+count_no_1+'"  class="form-control" type="text"></p></div><label class="col-sm-3 control-label" for="inputSuccess"><?php echo label('quesdetail'); ?></label><div class="col-sm-9 form-group"><p><input name="queDetailName[]" id="queDetailName-'+count_no_1+'"  class="form-control" type="text"></p></div></div>');
			}
			function onClick_1() { count_no_1++; }
			function remove_no_1(){
				value_head_1 = 'A'+count_no_1;
				value_content_1 = 'addE-'+count_no_1;			

				if(count_no_1==<?php echo countArray($detail_que)+1; ?>){
					$("#removeno1").hide("slow");
					count_no_1--;
				}
				if(count_no_1<=<?php echo countArray($detail_que); ?>){
			    	count_no_1=<?php echo countArray($detail_que); ?>;
			    }else{
					count_no_1--;
			    }

				var head_1 = document.getElementById(value_head_1);
				var content_1 = document.getElementById(value_content_1);
			    head_1.remove();
			    content_1.remove();
			}
		</script>
	</body>
</html>
