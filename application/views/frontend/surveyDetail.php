<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
    <style type="text/css">
        .break-word {
            word-wrap: break-word;
        }
        .vtabs {
            display: table-row;
        }
         /*Hidden class for adding and removing*/
    .lds-dual-ring.hidden {
        display: none;
    }

    /*Add an overlay to the entire page blocking any further presses to buttons or other elements.*/
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0,0,0,.8);
        z-index: 999;
        opacity: 1;
        transition: all 0.5s;
    }

    /*Spinner Styles*/
    .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 5% auto;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>
    <script type="text/javascript">
        function oncheckboxVal(name="", id="", number_id=""){
            var elements = document.querySelectorAll('input[name="'+name+'"]:checked');
            var checkedElements = Array.prototype.map.call(elements, function (el, i) {
                return el.value;
            });
            if(id!=""&&jQuery.inArray( "svde_specify", checkedElements )){
                $('#'+id).focus();
                var tc_note = $('#tc_note'+number_id).val();
                if(tc_note!=""){
                    $('#tc_note'+number_id).val('');
                }
                var varlsvde_specify = $('#'+id).val();
                if(varlsvde_specify==""){
                    $('#tc_require'+number_id).val('0');
                    if ($("#svde_specify_txt" + number_id).length > 0){
                        $("#svde_specify_txt" + number_id).css('border', '');
                    }
                }else{
                    $('#tc_require'+number_id).val('1');
                }
            }else{
                $('#tc_require'+number_id).val('1');
                if ($('#multi_choice_'+number_id+'6').is(':checked')) {
                    console.log('28');
                }else{
                    $('#tc_require'+number_id).val('1');
                }
                if ($("#svde_specify_txt" + number_id).length > 0){
                    $("#svde_specify_txt" + number_id).css('border', '');
                }
            }
            $('#tc_answer'+number_id).val(checkedElements.join('||'));
            //$('#tc_note').val(value2);
        }
    </script>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                      <button class="btn btn-outline-danger btn-sm" onclick="window.location.href='<?php echo REAL_PATH.'/survey'; ?>'">
					  	<i class="mdi mdi-keyboard-return"></i> <?php echo ucwords(label('m_previous')); ?>
					</button>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <!-- <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol> -->
                    </div>
                </div>
                <?php 
                if($lang=="thai"){ 
                    $sv_title = $sv_main['sv_title_th'] != "" ? $sv_main['sv_title_th'] : $sv_main['sv_title_eng'];
                    $sv_title = $sv_title != "" ? $sv_title : $sv_main['sv_title_jp'];
                    $sv_explanation = $sv_main['sv_explanation_th'] != "" ? $sv_main['sv_explanation_th'] : $sv_main['sv_explanation_eng'];
                    $sv_explanation = $sv_explanation != "" ? $sv_explanation : $sv_main['sv_explanation_jp'];
                    $sv_detailtxt = $sv_main['sv_detail_th'] != "" ? $sv_main['sv_detail_th'] : $sv_main['sv_detail_eng'];
                    $sv_detailtxt = $sv_detailtxt != "" ? $sv_detailtxt : $sv_main['sv_detail_jp'];
                }else if($lang=="english"){ 
                    $sv_title = $sv_main['sv_title_eng'] != "" ? $sv_main['sv_title_eng'] : $sv_main['sv_title_th'];
                    $sv_title = $sv_title != "" ? $sv_title : $sv_main['sv_title_jp'];
                    $sv_explanation = $sv_main['sv_explanation_eng'] != "" ? $sv_main['sv_explanation_eng'] : $sv_main['sv_explanation_th'];
                    $sv_explanation = $sv_explanation != "" ? $sv_explanation : $sv_main['sv_explanation_jp'];
                    $sv_detailtxt = $sv_main['sv_detail_eng'] != "" ? $sv_main['sv_detail_eng'] : $sv_main['sv_detail_th'];
                    $sv_detailtxt = $sv_detailtxt != "" ? $sv_detailtxt : $sv_main['sv_detail_jp'];
                }else{
                    $sv_title = $sv_main['sv_title_jp'] != "" ? $sv_main['sv_title_jp'] : $sv_main['sv_title_eng'];
                    $sv_title = $sv_title != "" ? $sv_title : $sv_main['sv_title_th'];
                    $sv_explanation = $sv_main['sv_explanation_jp'] != "" ? $sv_main['sv_explanation_jp'] : $sv_main['sv_explanation_eng'];
                    $sv_explanation = $sv_explanation != "" ? $sv_explanation : $sv_main['sv_explanation_th'];
                    $sv_detailtxt = $sv_main['sv_detail_jp'] != "" ? $sv_main['sv_detail_jp'] : $sv_main['sv_detail_eng'];
                    $sv_detailtxt = $sv_detailtxt != "" ? $sv_detailtxt : $sv_main['sv_detail_th'];
                }

                  $sv_lang = explode(',', $sv_main['sv_lang']);
                  $sv_main['isTH'] = in_array('th',$sv_lang) ? "1" : "0";
                  $sv_main['isENG'] = in_array('eng',$sv_lang) ? "1" : "0";
                  $sv_main['isJP'] = in_array('jp',$sv_lang) ? "1" : "0";
                ?>
                <div class="row">
                    <div class="col-md-12 mb-0 pb-0 card card-body">
                        <div class="card-title">
                            <div class="row">
                              <div class="col-md-12">
                                <h4 class="card-title"><span class="lstick"></span><?php echo $sv_title; ?></h4>
                              </div>
                            </div>
                            <div class="d-block position-relative">
                                <small class="text-muted text-truncate" style="bottom: 0;"><?php echo label('createBy').' : '.$sv_main['creator']; ?></small>
                            </div>
                            <hr class="mt-0">
                        </div>
                    </div>
                </div>


                <div class="row">
                <?php if(isset($sv_main['sv_cover'])){ ?>
                  <div class="col-md-4 col-lg-4 mb-0 card card-body">
                    <img 
						class="card-img-top img-responsive" 
						<?php if(isset($sv_main['sv_cover'])){ ?>src="<?php echo REAL_PATH;?>/uploads/publicsv/<?php echo $sv_main['sv_cover']; ?>"<?php } ?> 
						onerror="this.src='<?php echo REAL_PATH;?>/images/cover_survey.jpg';" alt="">
                  </div>
                <?php } ?>
                  <div class="col-md-8 col-lg-8 mb-0 card card-body">
                    <?php echo str_replace('../', base_url(),$sv_explanation); ?>
                  </div>
                </div>

            </div>
            <?php
            $check_loop = 0;
            if(countArray($sv_detail) > 0){
                if($sv_detailtxt!=""){
                    $check_loop = 0;
                }else{
                    $check_loop = 1;
                }
                foreach ($sv_detail as $key_detail => $value_detail) {
                    if($value_detail['isTC']=="1"){
                        $check_loop++;
                    }
                    if($check_loop > countArray($sv_detail)){
                        $check_loop = countArray($sv_detail);
                    }
                    if($check_loop>=1){
                        if($value_detail['isTC']=="0"){
                            break;
                        }
                    }
                }
            }
            function removePath2($str) {
              return str_replace('../../', '../', $str);
            }
            ?>
            <!-- Collapse Survey -->
            <div class="container-fluid p-0 mb-3">
                <a 	href="#" class="btn btn-block <?php if($sv_main['tcmain_status']==label('done')){ ?>imat-red-bg btn-danger<?php } ?> waves-effect waves-light rounded-0 text-left" 
					type="button" data-toggle="collapse" data-target="#collapseExample_2" 
					<?php if($sv_main['tcmain_status']!=label('done')){ ?>style="background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;"<?php } ?> 
					aria-expanded="true" aria-controls="collapseExample_2">
                    <?php if($sv_main['tcmain_status']==label('done')){ ?><i class="fa fas fa-check mr-2"></i><?php } ?><?php echo $sv_title; ?>
                    <i class="fa fa-chevron-right float-right"></i>
                    <i class="fa fa-chevron-down float-right"></i>
                </a>
                <div class="collapse show" id="collapseExample_2">

                    <!-- MOBILE NAV -->
                    <div class="hidden-sm-up">
                      <div class="list-group">
                        <?php if($sv_detailtxt!=""){ ?>
                        <a 	href="#quiz_detail" id="" data-toggle="tab" role="tab" aria-selected="true" 
							class="rounded-0 list-group-item les_onclick <?php if($check_loop==0){ ?>active<?php } ?>">
							<?php echo label('summary'); ?>
						</a>
                        <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                    foreach ($sv_detail as $key_detail => $value_detail) {
                        ?>
                        				<a 	href="#quiz_<?php echo $numloop; ?>" 
											id="" 
											data-toggle="tab" role="tab" aria-selected="true" 
											class="rounded-0 list-group-item les_onclick <?php if($check_loop==$numloop){ ?>active<?php } ?>">
											<?php echo label('preNo'); ?> <?php echo $numloop; ?>
										</a>
                        <?php           $numloop++;
                                    }
                                } ?>
                        <?php }else{ ?>
                        <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                    foreach ($sv_detail as $key_detail => $value_detail) {
                        ?>
                        				<a 	href="#quiz_<?php echo $numloop; ?>" 
											id="" 
											data-toggle="tab" role="tab" aria-selected="true" 
											class="rounded-0 list-group-item les_onclick <?php if($check_loop==$numloop){ ?>active<?php } ?>">
											<?php echo label('preNo'); ?> <?php echo $numloop; ?>
										</a>
                        <?php           $numloop++;
                                    }
                                } ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="card card-body" style="height: 550px;position: relative;">
                        <div class="vtabs customvtab">
                            <!-- DESKTOP NAV -->
                            <div class="row">
                            <div class="col-2 hidden-sm-down" style="height: 500px; overflow-y: auto; padding-right: 0;">
								<ul class="nav nav-tabs tabs-vertical hidden-xs-down" role="tablist">
								<?php if($sv_detailtxt!=""){ ?>
									<li class="nav-item">
									<a class="nav-link <?php if($check_loop==0){ ?>active show<?php } ?>" data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
										<span><?php echo label('summary'); ?></span>
									</a>
									</li>
								<?php   if(countArray($sv_detail)>0){ $numloop = 1;
											$txt_header = '';
											foreach ($sv_detail as $key_detail => $value_detail) {

												if($lang=="thai"){ 
													$svde_header = $value_detail['svde_header_th'] != "" ? $value_detail['svde_header_th'] : $value_detail['svde_header_eng'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_jp'];
												}else if($lang=="english"){ 
													$svde_header = $value_detail['svde_header_eng'] != "" ? $value_detail['svde_header_eng'] : $value_detail['svde_header_th'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_jp'];
												}else{
													$svde_header = $value_detail['svde_header_jp'] != "" ? $value_detail['svde_header_jp'] : $value_detail['svde_header_eng'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_th'];
												}
												if($sv_main['sv_isHeader']=="1"&&$txt_header!=$svde_header){
													$txt_header = $svde_header;
													?>
													<li class="nav-item">
														<span class="header-tab"><?php echo $svde_header; ?></span>
													</li>
													<?php
												}
								?>
									<li class="nav-item">
										<a 	class="nav-link <?php if($check_loop==$numloop){ ?>active show<?php } ?>" 
											data-toggle="tab" href="#quiz_<?php echo $numloop; ?>" role="tab" aria-selected="false">
											<span><?php echo label('preNo'); ?> <?php echo $numloop; ?></span>
										</a>
									</li>
								<?php         $numloop++;
											}
										} ?>
								<?php }else{ ?>
								<?php   if(countArray($sv_detail)>0){ $numloop = 1;
											$txt_header = '';
											foreach ($sv_detail as $key_detail => $value_detail) {

												if($lang=="thai"){ 
													$svde_header = $value_detail['svde_header_th'] != "" ? $value_detail['svde_header_th'] : $value_detail['svde_header_eng'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_jp'];
												}else if($lang=="english"){ 
													$svde_header = $value_detail['svde_header_eng'] != "" ? $value_detail['svde_header_eng'] : $value_detail['svde_header_th'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_jp'];
												}else{
													$svde_header = $value_detail['svde_header_jp'] != "" ? $value_detail['svde_header_jp'] : $value_detail['svde_header_eng'];
													$svde_header = $svde_header != "" ? $svde_header : $value_detail['svde_header_th'];
												}
												if($sv_main['sv_isHeader']=="1"&&$txt_header!=$svde_header){
													$txt_header = $svde_header;
													?>
													<li class="nav-item">
														<span class="header-tab"><?php echo $svde_header; ?></span>
													</li>
													<?php
												}
								?>
									<li class="nav-item">
										<a 	class="nav-link  <?php if($check_loop==$numloop){ ?>active show<?php } ?>" 
											data-toggle="tab" href="#quiz_<?php echo $numloop; ?>" role="tab" aria-selected="false">
											<span><?php echo label('preNo'); ?> <?php echo $numloop; ?></span>
										</a>
									</li>
								<?php         $numloop++;
											}
										}
									} ?>
									<!--  style="pointer-events: none; " -->
								</ul>
                            </div>
							<style type="text/css">
								p img{
									max-width: 100%;
									height: auto;
								}
							</style>
                            <!-- Tab panes -->
                            <div class="col-lg-10 col-sm-12" style="overflow: hidden;">
              <form method="post" id="survey_form" autocomplete="off" name="survey_form" enctype="multipart/form-data" role="form">
                    <input type="hidden" id="sv_id" name="sv_id" value="<?php echo $sv_id; ?>">
                            <!-- Tab panes -->
                            <div class="tab-content pt-0 d-block" style="word-wrap: break-word;height: 500px; overflow-y: auto;">
                            <?php if($sv_detailtxt!=""){ ?>
                                <div class="tab-pane <?php if($check_loop==0){ ?>active show<?php } ?>" id="quiz_detail" role="tabpanel">
                                  <h4><?php echo label('summary'); ?></h4>
                                  <?php echo str_replace('../', base_url(), removePath2($sv_detailtxt)); ?>
                                  <hr>
                                  <button type="button" onclick="activaTab('quiz_1')" class="btn btn-outline-secondary float-right"><?php echo label('questionnaireStart'); ?></button>
                                </div>
                            <?php } ?>
                            <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                        foreach ($sv_detail as $key_detail => $value_detail) {
                                            $value_check = "";
                                            $value_note = "";
                                            if($value_detail['svde_type']=="sa" || $value_detail['svde_type']=="sub"){
                                                if(isset($value_detail['detail_tc']) && countArray($value_detail['detail_tc'])>0){
                                                    $value_check = html_entity_decode(htmlspecialchars_decode($value_detail['detail_tc']['tc_answer']));
                                                }
                                            }else{
                                                if(isset($value_detail['detail_tc']) && countArray($value_detail['detail_tc'])>0){
                                                    $value_check = $value_detail['detail_tc']['tc_answer'] != "" ? explode('||', html_entity_decode(htmlspecialchars_decode($value_detail['detail_tc']['tc_answer']))) : array();
                                                    $value_note = $value_detail['detail_tc']['tc_note'];
                                                }
                                            }

											if($lang=="thai"){ 
												$svde_name = $value_detail['svde_name_th'] != "" ? $value_detail['svde_name_th'] : $value_detail['svde_name_eng'];
												$svde_name = $svde_name != "" ? $svde_name : $value_detail['svde_name_jp'];
												$svde_info = $value_detail['svde_info_th'] != "" ? $value_detail['svde_info_th'] : $value_detail['svde_info_eng'];
												$svde_info = $svde_info != "" ? $svde_info : $value_detail['svde_info_jp'];
											}else if($lang=="english"){ 
												$svde_name = $value_detail['svde_name_eng'] != "" ? $value_detail['svde_name_eng'] : $value_detail['svde_name_th'];
												$svde_name = $svde_name != "" ? $svde_name : $value_detail['svde_name_jp'];
												$svde_info = $value_detail['svde_info_eng'] != "" ? $value_detail['svde_info_eng'] : $value_detail['svde_info_th'];
												$svde_info = $svde_info != "" ? $svde_info : $value_detail['svde_info_jp'];
											}else{
												$svde_name = $value_detail['svde_name_jp'] != "" ? $value_detail['svde_name_jp'] : $value_detail['svde_name_eng'];
												$svde_name = $svde_name != "" ? $svde_name : $value_detail['svde_name_th'];
												$svde_info = $value_detail['svde_info_jp'] != "" ? $value_detail['svde_info_jp'] : $value_detail['svde_info_eng'];
												$svde_info = $svde_info != "" ? $svde_info : $value_detail['svde_info_th'];
											}
                            ?>
							<input type="hidden" id="svde_id<?php echo $numloop; ?>" name="svde_id[]" value="<?php echo $value_detail['svde_id']; ?>">
							<input type="hidden" id="svde_type<?php echo $numloop; ?>" name="svde_type[]" value="<?php echo $value_detail['svde_type']; ?>">
							<input type="hidden" id="tc_answer<?php echo $numloop; ?>" name="tc_answer[]" value="<?php echo isset($value_detail['detail_tc']['tc_answer'])? htmlentities($value_detail['detail_tc']['tc_answer'], ENT_QUOTES, "UTF-8", false):""; ?>">
							<input type="hidden" id="tc_note<?php echo $numloop; ?>" name="tc_note[]" value="<?php echo isset($value_detail['detail_tc']['tc_note'])?$value_detail['detail_tc']['tc_note']:""; ?>">
							<input type="hidden" id="tc_require<?php echo $numloop; ?>" name="tc_require[]" value="1">
                            <div class="tab-pane <?php if($check_loop==$numloop){ ?>active show<?php } ?>" id="quiz_<?php echo $numloop; ?>" role="tabpanel">
                                <div class="row">
                                    <div class="col" style="flex-grow: 0.3;"><h3><?php echo $numloop; ?>.</h3></div>
                                    <div class="col-11">
                                        <div class="break-word" style="max-width:100%;">
                                        <h3><?php echo str_replace('../', base_url(),removePath2($svde_name)); ?></h3>
                                        <?php if($svde_info!=""){ ?><p><?php echo str_replace('../', base_url(),removePath2($svde_info)); ?></p> <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    
                                    var elmnt = document.getElementById("quiz_<?php echo $numloop; ?>");
                                   // $(".break-word").width($(".tab-pane").width());
                                </script>
                                        <?php if($value_detail['svde_type']=="sa" || $value_detail['svde_type']=="sub"){
                            ?>
                                            <?php if($value_detail['svde_type']=="sub"){ ?>
                                          		<textarea 	class="form-control text-input-val-<?php echo $numloop; ?>" 
															id="textarea<?php echo $numloop; ?>" 
															name="textarea<?php echo $numloop; ?>" 
															<?php if($sv_main['tcmain_status']==label('done')){ echo "readonly"; }?> 
															onkeyup="onselectVal('<?php echo $numloop; ?>',this.value,'')" 
															maxlength="60000" 
															rows="5"><?php echo $value_check; ?></textarea>
                                            <?php }else{ ?>
                                              	<input 	type="text" 
														class="form-control text-input-val-<?php echo $numloop; ?>" 
														id="textarea<?php echo $numloop; ?>" 
														onkeyup="onselectVal('<?php echo $numloop; ?>',this.value,'')" 
														<?php if($sv_main['tcmain_status']==label('done')){ echo "readonly"; }?> 
														maxlength="255" 
														name="textarea<?php echo $numloop; ?>" 
														value="<?php echo $value_check; ?>">
                                            <?php } ?>
                                    <?php  }else if($value_detail['svde_type']=="scale"){ ?>
                                <div class="text-center">
                                    <?php echo label('smax'); ?>

                                    <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','5','')" <?php } ?> 
											name="scale_choice_group<?php echo $numloop; ?>" 
											type="radio" 
											<?php if($value_check!=""&&in_array('5', $value_check)){echo "checked";} ?> 
											id="scale_choice_<?php echo $numloop; ?>5" 
											class="with-gap radio-col-red">
                                    <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','5','')"<?php } ?> 
											for="scale_choice_<?php echo $numloop; ?>5">5</label>
                                    <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','4','')"<?php } ?> 
											name="scale_choice_group<?php echo $numloop; ?>" 
											type="radio" 
											<?php if($value_check!=""&&in_array('4', $value_check)){echo "checked";} ?> 
											id="scale_choice_<?php echo $numloop; ?>4" 
											class="with-gap radio-col-red">
                                    <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','4','')"<?php } ?> 
											for="scale_choice_<?php echo $numloop; ?>4">4</label>
                                    <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','3','')"<?php } ?> 
											name="scale_choice_group<?php echo $numloop; ?>" 
											type="radio" 
											<?php if($value_check!=""&&in_array('3', $value_check)){echo "checked";} ?> 
											id="scale_choice_<?php echo $numloop; ?>3" 
											class="with-gap radio-col-red">
                                    <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','3','')"<?php } ?> 
											for="scale_choice_<?php echo $numloop; ?>3">3</label>
                                    <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','2','')"<?php } ?> 
											name="scale_choice_group<?php echo $numloop; ?>" 
											type="radio" 
											<?php if($value_check!=""&&in_array('2', $value_check)){echo "checked";} ?> 
											id="scale_choice_<?php echo $numloop; ?>2" 
											class="with-gap radio-col-red">
                                    <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','2','')"<?php } ?> 
											for="scale_choice_<?php echo $numloop; ?>2">2</label>
                                    <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','1','')"<?php } ?> 
											name="scale_choice_group<?php echo $numloop; ?>" 
											type="radio" 
											<?php if($value_check!=""&&in_array('1', $value_check)){echo "checked";} ?> 
											id="scale_choice_<?php echo $numloop; ?>1" 
											class="with-gap radio-col-red">
                                    <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
											<?php if($sv_main['tcmain_status']!=label('done')){ ?> onclick="onselectVal('<?php echo $numloop; ?>','1','')"<?php } ?> 
											for="scale_choice_<?php echo $numloop; ?>1">1</label>
                                    <?php echo label('smin'); ?>



                                </div>
                                    <?php  }else if($value_detail['svde_type']=="2choice"){
                                            if(isset($value_detail['multi']) && countArray($value_detail['multi'])>0)
                                            {

                                                if($lang=="thai"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_th'] != "" ? $value_detail['multi']['mul_c1_th'] : $value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_th'] != "" ? $value_detail['multi']['mul_c2_th'] : $value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_jp'];
                                                }else if($lang=="english"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_eng'] != "" ? $value_detail['multi']['mul_c1_eng'] : $value_detail['multi']['mul_c1_th'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_eng'] != "" ? $value_detail['multi']['mul_c2_eng'] : $value_detail['multi']['mul_c2_th'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_jp'];
                                                }else{
                                                    $mul_c1 = $value_detail['multi']['mul_c1_jp'] != "" ? $value_detail['multi']['mul_c1_jp'] : $value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_th'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_jp'] != "" ? $value_detail['multi']['mul_c2_jp'] : $value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_th'];
                                                }

                                                if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}
                                                ?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')"<?php } ?> 
												name="two_choicegroup<?php echo $numloop; ?>" 
												type="radio" 
												id="two_choice<?php echo $numloop; ?>1" 
												<?php 	if($value_check!="" && 
															(in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_th'])), $value_check)||
															in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_eng'])), $value_check)||
															in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_jp'])), $value_check)))
														{echo "checked";} ?> 
												class="with-gap radio-col-red">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')"<?php } ?> for="two_choice<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(),removePath2($mul_c1)); ?></label><br>
                                         <?php      if($rechkimgtext){ echo "</p>";}
                                                }
                                                if($mul_c2!=""){ 
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')"<?php } ?> 
												name="two_choicegroup<?php echo $numloop; ?>" 
												<?php if($value_check!="" && 
															(in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_th'])), $value_check)||
															in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_eng'])), $value_check)||
															in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_jp'])), $value_check)))
															{echo "checked";} ?> 
												type="radio" 
												id="two_choice<?php echo $numloop; ?>2" 
												class="with-gap radio-col-red">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')"<?php } ?> for="two_choice<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(),removePath2($mul_c2)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                }
                                                if($lang=="thai"){ 
                                                    $svde_specify_name = $value_detail['svde_specify_name_th'] != "" ? $value_detail['svde_specify_name_th'] : $value_detail['svde_specify_name_eng'];
                                                    $svde_specify_name = $svde_specify_name != "" ? $svde_specify_name : $value_detail['svde_specify_name_jp'];
                                                }else if($lang=="english"){ 
                                                    $svde_specify_name = $value_detail['svde_specify_name_eng'] != "" ? $value_detail['svde_specify_name_eng'] : $value_detail['svde_specify_name_th'];
                                                    $svde_specify_name = $svde_specify_name != "" ? $svde_specify_name : $value_detail['svde_specify_name_jp'];
                                                }else{
                                                    $svde_specify_name = $value_detail['svde_specify_name_jp'] != "" ? $value_detail['svde_specify_name_jp'] : $value_detail['svde_specify_name_eng'];
                                                    $svde_specify_name = $svde_specify_name != "" ? $svde_specify_name : $value_detail['svde_specify_name_th'];
                                                }
                                                /*if($value_detail['svde_isSpecify']=="1"&&$svde_specify_name!=""){ 

                                                ?>
                                                <input onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" <?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> name="two_choicegroup<?php echo $numloop; ?>" type="radio" id="two_choice<?php echo $numloop; ?>3" value="svde_specify" class="with-gap radio-col-red">
                                                <label onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" for="two_choice<?php echo $numloop; ?>3"><?php echo $svde_specify_name; ?></label><br>
                                                <input onkeyup="chkbox_onkey('two_choice<?php echo $numloop; ?>3',this.value,'1','','<?php echo $numloop; ?>');" type="text" value="<?php echo $value_note; ?>" class="form-control" name="svde_specify_txt" id="svde_specify_txt<?php echo $numloop; ?>">
                                         <?php  }*/
                                            }
                                           }else if($value_detail['svde_type']=="multi"){
                                            
                                                if($lang=="thai"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_th'] != "" ? $value_detail['multi']['mul_c1_th'] : $value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_th'] != "" ? $value_detail['multi']['mul_c2_th'] : $value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_jp'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_th'] != "" ? $value_detail['multi']['mul_c3_th'] : $value_detail['multi']['mul_c3_eng'];
                                                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_detail['multi']['mul_c3_jp'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_th'] != "" ? $value_detail['multi']['mul_c4_th'] : $value_detail['multi']['mul_c4_eng'];
                                                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_detail['multi']['mul_c4_jp'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_th'] != "" ? $value_detail['multi']['mul_c5_th'] : $value_detail['multi']['mul_c5_eng'];
                                                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_detail['multi']['mul_c5_jp'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_th'] != "" ? $value_detail['multi']['mul_c6_th'] : $value_detail['multi']['mul_c6_eng'];
                                                    $mul_c6 = $mul_c6 != "" ? $mul_c6 : $value_detail['multi']['mul_c6_jp'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_th'] != "" ? $value_detail['multi']['mul_c7_th'] : $value_detail['multi']['mul_c7_eng'];
                                                    $mul_c7 = $mul_c7 != "" ? $mul_c7 : $value_detail['multi']['mul_c7_jp'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_th'] != "" ? $value_detail['multi']['mul_c8_th'] : $value_detail['multi']['mul_c8_eng'];
                                                    $mul_c8 = $mul_c8 != "" ? $mul_c8 : $value_detail['multi']['mul_c8_jp'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_th'] != "" ? $value_detail['multi']['mul_c9_th'] : $value_detail['multi']['mul_c9_eng'];
                                                    $mul_c9 = $mul_c9 != "" ? $mul_c9 : $value_detail['multi']['mul_c9_jp'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_th'] != "" ? $value_detail['multi']['mul_c10_th'] : $value_detail['multi']['mul_c10_eng'];
                                                    $mul_c10 = $mul_c10 != "" ? $mul_c10 : $value_detail['multi']['mul_c10_jp'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_th'] != "" ? $value_detail['multi']['mul_c11_th'] : $value_detail['multi']['mul_c11_eng'];
                                                    $mul_c11 = $mul_c11 != "" ? $mul_c11 : $value_detail['multi']['mul_c11_jp'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_th'] != "" ? $value_detail['multi']['mul_c12_th'] : $value_detail['multi']['mul_c12_eng'];
                                                    $mul_c12 = $mul_c12 != "" ? $mul_c12 : $value_detail['multi']['mul_c12_jp'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_th'] != "" ? $value_detail['multi']['mul_c13_th'] : $value_detail['multi']['mul_c13_eng'];
                                                    $mul_c13 = $mul_c13 != "" ? $mul_c13 : $value_detail['multi']['mul_c13_jp'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_th'] != "" ? $value_detail['multi']['mul_c14_th'] : $value_detail['multi']['mul_c14_eng'];
                                                    $mul_c14 = $mul_c14 != "" ? $mul_c14 : $value_detail['multi']['mul_c14_jp'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_th'] != "" ? $value_detail['multi']['mul_c15_th'] : $value_detail['multi']['mul_c15_eng'];
                                                    $mul_c15 = $mul_c15 != "" ? $mul_c15 : $value_detail['multi']['mul_c15_jp'];
                                                }else if($lang=="english"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_eng'] != "" ? $value_detail['multi']['mul_c1_eng'] : $value_detail['multi']['mul_c1_th'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_eng'] != "" ? $value_detail['multi']['mul_c2_eng'] : $value_detail['multi']['mul_c2_th'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_jp'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_eng'] != "" ? $value_detail['multi']['mul_c3_eng'] : $value_detail['multi']['mul_c3_th'];
                                                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_detail['multi']['mul_c3_jp'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_eng'] != "" ? $value_detail['multi']['mul_c4_eng'] : $value_detail['multi']['mul_c4_th'];
                                                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_detail['multi']['mul_c4_jp'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_eng'] != "" ? $value_detail['multi']['mul_c5_eng'] : $value_detail['multi']['mul_c5_th'];
                                                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_detail['multi']['mul_c5_jp'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_eng'] != "" ? $value_detail['multi']['mul_c6_eng'] : $value_detail['multi']['mul_c6_th'];
                                                    $mul_c6 = $mul_c6 != "" ? $mul_c6 : $value_detail['multi']['mul_c6_jp'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_eng'] != "" ? $value_detail['multi']['mul_c7_eng'] : $value_detail['multi']['mul_c7_th'];
                                                    $mul_c7 = $mul_c7 != "" ? $mul_c7 : $value_detail['multi']['mul_c7_jp'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_eng'] != "" ? $value_detail['multi']['mul_c8_eng'] : $value_detail['multi']['mul_c8_th'];
                                                    $mul_c8 = $mul_c8 != "" ? $mul_c8 : $value_detail['multi']['mul_c8_jp'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_eng'] != "" ? $value_detail['multi']['mul_c9_eng'] : $value_detail['multi']['mul_c9_th'];
                                                    $mul_c9 = $mul_c9 != "" ? $mul_c9 : $value_detail['multi']['mul_c9_jp'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_eng'] != "" ? $value_detail['multi']['mul_c10_eng'] : $value_detail['multi']['mul_c10_th'];
                                                    $mul_c10 = $mul_c10 != "" ? $mul_c10 : $value_detail['multi']['mul_c10_jp'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_eng'] != "" ? $value_detail['multi']['mul_c11_eng'] : $value_detail['multi']['mul_c11_th'];
                                                    $mul_c11 = $mul_c11 != "" ? $mul_c11 : $value_detail['multi']['mul_c11_jp'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_eng'] != "" ? $value_detail['multi']['mul_c12_eng'] : $value_detail['multi']['mul_c12_th'];
                                                    $mul_c12 = $mul_c12 != "" ? $mul_c12 : $value_detail['multi']['mul_c12_jp'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_eng'] != "" ? $value_detail['multi']['mul_c13_eng'] : $value_detail['multi']['mul_c13_th'];
                                                    $mul_c13 = $mul_c13 != "" ? $mul_c13 : $value_detail['multi']['mul_c13_jp'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_eng'] != "" ? $value_detail['multi']['mul_c14_eng'] : $value_detail['multi']['mul_c14_th'];
                                                    $mul_c14 = $mul_c14 != "" ? $mul_c14 : $value_detail['multi']['mul_c14_jp'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_eng'] != "" ? $value_detail['multi']['mul_c15_eng'] : $value_detail['multi']['mul_c15_th'];
                                                    $mul_c15 = $mul_c15 != "" ? $mul_c15 : $value_detail['multi']['mul_c15_jp'];
                                                }else{
                                                    $mul_c1 = $value_detail['multi']['mul_c1_jp'] != "" ? $value_detail['multi']['mul_c1_jp'] : $value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_detail['multi']['mul_c1_th'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_jp'] != "" ? $value_detail['multi']['mul_c2_jp'] : $value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_detail['multi']['mul_c2_th'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_jp'] != "" ? $value_detail['multi']['mul_c3_jp'] : $value_detail['multi']['mul_c3_eng'];
                                                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_detail['multi']['mul_c3_th'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_jp'] != "" ? $value_detail['multi']['mul_c4_jp'] : $value_detail['multi']['mul_c4_eng'];
                                                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_detail['multi']['mul_c4_th'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_jp'] != "" ? $value_detail['multi']['mul_c5_jp'] : $value_detail['multi']['mul_c5_eng'];
                                                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_detail['multi']['mul_c5_th'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_jp'] != "" ? $value_detail['multi']['mul_c6_jp'] : $value_detail['multi']['mul_c6_eng'];
                                                    $mul_c6 = $mul_c6 != "" ? $mul_c6 : $value_detail['multi']['mul_c6_th'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_jp'] != "" ? $value_detail['multi']['mul_c7_jp'] : $value_detail['multi']['mul_c7_eng'];
                                                    $mul_c7 = $mul_c7 != "" ? $mul_c7 : $value_detail['multi']['mul_c7_th'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_jp'] != "" ? $value_detail['multi']['mul_c8_jp'] : $value_detail['multi']['mul_c8_eng'];
                                                    $mul_c8 = $mul_c8 != "" ? $mul_c8 : $value_detail['multi']['mul_c8_th'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_jp'] != "" ? $value_detail['multi']['mul_c9_jp'] : $value_detail['multi']['mul_c9_eng'];
                                                    $mul_c9 = $mul_c9 != "" ? $mul_c9 : $value_detail['multi']['mul_c9_th'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_jp'] != "" ? $value_detail['multi']['mul_c10_jp'] : $value_detail['multi']['mul_c10_eng'];
                                                    $mul_c10 = $mul_c10 != "" ? $mul_c10 : $value_detail['multi']['mul_c10_th'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_jp'] != "" ? $value_detail['multi']['mul_c11_jp'] : $value_detail['multi']['mul_c11_eng'];
                                                    $mul_c11 = $mul_c11 != "" ? $mul_c11 : $value_detail['multi']['mul_c11_th'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_jp'] != "" ? $value_detail['multi']['mul_c12_jp'] : $value_detail['multi']['mul_c12_eng'];
                                                    $mul_c12 = $mul_c12 != "" ? $mul_c12 : $value_detail['multi']['mul_c12_th'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_jp'] != "" ? $value_detail['multi']['mul_c13_jp'] : $value_detail['multi']['mul_c13_eng'];
                                                    $mul_c13 = $mul_c13 != "" ? $mul_c13 : $value_detail['multi']['mul_c13_th'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_jp'] != "" ? $value_detail['multi']['mul_c14_jp'] : $value_detail['multi']['mul_c14_eng'];
                                                    $mul_c14 = $mul_c14 != "" ? $mul_c14 : $value_detail['multi']['mul_c14_th'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_jp'] != "" ? $value_detail['multi']['mul_c15_jp'] : $value_detail['multi']['mul_c15_eng'];
                                                    $mul_c15 = $mul_c15 != "" ? $mul_c15 : $value_detail['multi']['mul_c15_th'];
                                                }
                                                $isMulC1 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c1_jp'])), $value_check)) : false;
                                                $isMulC2 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c2_jp'])), $value_check)) : false;
                                                $isMulC3 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c3_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c3_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c3_jp'])), $value_check)) : false;
                                                $isMulC4 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c4_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c4_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c4_jp'])), $value_check)) : false;
                                                $isMulC5 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c5_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c5_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c5_jp'])), $value_check)) : false;
                                                $isMulC6 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c6_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c6_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c6_jp'])), $value_check)) : false;
                                                $isMulC7 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c7_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c7_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c7_jp'])), $value_check)) : false;
                                                $isMulC8 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c8_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c8_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c8_jp'])), $value_check)) : false;
                                                $isMulC9 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c9_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c9_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c9_jp'])), $value_check)) : false;
                                                $isMulC10 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c10_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c10_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c10_jp'])), $value_check)) : false;
                                                $isMulC11 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c11_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c11_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c11_jp'])), $value_check)) : false;
                                                $isMulC12 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c12_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c12_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c12_jp'])), $value_check)) : false;
                                                $isMulC13 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c13_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c13_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c13_jp'])), $value_check)) : false;
                                                $isMulC14 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c14_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c14_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c14_jp'])), $value_check)) : false;
                                                $isMulC15 = is_array($value_check) && countArray($value_check) > 0 ? (in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c15_th'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c15_eng'])), $value_check)||in_array(html_entity_decode(htmlspecialchars_decode($value_detail['multi']['mul_c15_jp'])), $value_check)) : false;
                                            if($value_detail['svde_isMultichoice']!="1"){
                                            if(isset($value_detail['multi']) && countArray($value_detail['multi'])>0){
                                                    if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												<?php if($value_check!=""&&$isMulC1){echo "checked";} ?> 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>1" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c1); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(),removePath2($mul_c1)); ?></label><br>
                                             <?php      if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c2!=""){
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";} ?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')"<?php } ?> 
												<?php if($value_check!=""&&$isMulC2){echo "checked";} ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>2" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c2); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(),removePath2($mul_c2)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c3!=""){ 
                                                    $rechkimgtext = strpos($mul_c3, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  
												<?php if($value_check!=""&&$isMulC3){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c3); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>3" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c3); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c3); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>3"><?php echo str_replace('../', base_url(),removePath2($mul_c3)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c4!=""){ 
                                                    $rechkimgtext = strpos($mul_c4, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  
												<?php if($value_check!=""&&$isMulC4){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c4); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>4" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c4); ?>">
                                        <label  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c4); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>4"><?php echo str_replace('../', base_url(),removePath2($mul_c4)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c5!=""){ 
                                                    $rechkimgtext = strpos($mul_c5, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC5){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c5); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>5" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c5); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c5); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>5"><?php echo str_replace('../', base_url(),removePath2($mul_c5)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c6!=""){ 
                                                    $rechkimgtext = strpos($mul_c6, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC6){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c6); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>6" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c6); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c6); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>6"><?php echo str_replace('../', base_url(),removePath2($mul_c6)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c7!=""){ 
                                                    $rechkimgtext = strpos($mul_c7, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC7){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c7); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>7" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c7); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c7); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>7"><?php echo str_replace('../', base_url(),removePath2($mul_c7)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c8!=""){ 
                                                    $rechkimgtext = strpos($mul_c8, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC8){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c8); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>8" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c8); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c8); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>8"><?php echo str_replace('../', base_url(),removePath2($mul_c8)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c9!=""){ 
                                                    $rechkimgtext = strpos($mul_c9, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC9){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c9); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>9" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c9); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c9); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>9"><?php echo str_replace('../', base_url(),removePath2($mul_c9)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c10!=""){ 
                                                    $rechkimgtext = strpos($mul_c10, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC10){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c10); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>10" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c10); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c10); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>10"><?php echo str_replace('../', base_url(),removePath2($mul_c10)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c11!=""){ 
                                                    $rechkimgtext = strpos($mul_c11, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC11){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c11); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>11" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c11); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c11); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>11"><?php echo str_replace('../', base_url(),removePath2($mul_c11)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c12!=""){ 
                                                    $rechkimgtext = strpos($mul_c12, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC12){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c12); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>12" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c12); ?>">
                                        <label <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c12); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>12"><?php echo str_replace('../', base_url(),removePath2($mul_c12)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c13!=""){ 
                                                    $rechkimgtext = strpos($mul_c13, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC13){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c13); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>13" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c13); ?>">
                                        <label <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c13); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>13"><?php echo str_replace('../', base_url(),removePath2($mul_c13)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c14!=""){ 
                                                    $rechkimgtext = strpos($mul_c14, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC14){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c14); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>14" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c14); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c14); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>14"><?php echo str_replace('../', base_url(),removePath2($mul_c14)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c15!=""){ 
                                                    $rechkimgtext = strpos($mul_c15, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>   
												<?php if($value_check!=""&&$isMulC15){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c15); ?>','')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>" 
												type="radio" 
												id="multi_choice_<?php echo $numloop; ?>15" 
												class="with-gap radio-col-red" 
												value="<?php echo htmlentities($mul_c15); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?>  <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c15); ?>','')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>15"><?php echo str_replace('../', base_url(),removePath2($mul_c15)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                }

                                                $svde_specify_name = "";
                                                
                                                if($lang=="thai"){ 

                                                      if($sv_main['isTH']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isENG']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isJP']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                        }
                                                      }
                                                }else if($lang=="english"){ 

                                                      if($sv_main['isENG']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isTH']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isJP']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                        }
                                                      }
                                                }else{
                                                      if($sv_main['isJP']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isENG']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isTH']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                        }
                                                      }
                                                }
                                                if($value_detail['svde_isSpecify']=="1"&&$svde_specify_name!=""){ 

                                                ?>
                                                <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														name="multi_choice_group<?php echo $numloop; ?>" 
														<?php if($sv_main['tcmain_status']!=label('done')){ ?>
															onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')"
														<?php } ?> 
														type="radio" 
														id="multi_choice_<?php echo $numloop; ?>16" 
														<?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> 
														value="svde_specify" class="with-gap radio-col-red">
                                                <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														<?php if($sv_main['tcmain_status']!=label('done')){ ?>
															onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')"
														<?php } ?> 
														for="multi_choice_<?php echo $numloop; ?>16"><?php echo $svde_specify_name; ?></label><br>
                                                <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														type="text" onkeyup="" 
														class="form-control text-input-val-<?php echo $numloop; ?>" 
														name="svde_specify_txt" 
														id="svde_specify_txt<?php echo $numloop; ?>" 
														value="<?php echo $value_note; ?>">
                                                <script type="text/javascript">
                                                    var svde_specify = document.getElementById('svde_specify_txt<?php echo $numloop; ?>');
                                                    var arr = [19,44,45, 46,33,34, 35, 36, 91 ,145,144,112,113,114,115,116,117,118,119,120,121,122,123,27,20,16,17,18,93,13];
                                                    svde_specify.addEventListener("keyup", event => {
                                                      if (jQuery.inArray( event.keyCode, arr ) < 0) {
                                                        var svde_specifyval = $('#svde_specify_txt<?php echo $numloop; ?>').val();
                                                        chkbox_onkey('multi_choice_<?php echo $numloop; ?>16',svde_specifyval,'1','','<?php echo $numloop; ?>');
                                                      }
                                                      // do something
                                                    });
                                                </script>
                                         <?php  }
                                                }else{
                                                if(isset($value_detail['multi']) && countArray($value_detail['multi'])>0){
                                                    if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>1" 
												<?php if($value_check!=""&&$isMulC1){echo "checked";} ?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> 
												value="<?php echo htmlentities($mul_c1); ?>" 
												class="with-gap filled-in chk-col-red">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(),removePath2($mul_c1)); ?></label><br>
                                         <?php      if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c2!=""){ 
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												<?php if($value_check!=""&&$isMulC2){echo "checked";} ?> 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>2" 
												value="<?php echo htmlentities($mul_c2); ?>" 
												class="with-gap filled-in chk-col-red">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(),removePath2($mul_c2)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c3!=""){ 
                                                    $rechkimgtext = strpos($mul_c3, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												<?php if($value_check!=""&&$isMulC3){echo "checked";} ?> 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>3" 
												value="<?php echo htmlentities($mul_c3); ?>" 
												class="with-gap filled-in chk-col-red">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>3"><?php echo str_replace('../', base_url(),removePath2($mul_c3)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c4!=""){ 
                                                    $rechkimgtext = strpos($mul_c4, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> 
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>4" 
												<?php if($value_check!=""&&$isMulC4){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c4); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?> for="multi_choice_<?php echo $numloop; ?>4"><?php echo str_replace('../', base_url(),removePath2($mul_c4)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c5!=""){ 
                                                    $rechkimgtext = strpos($mul_c5, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>5" 
												<?php if($value_check!=""&&$isMulC5){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red"
												value="<?php echo htmlentities($mul_c5); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c5); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>5"><?php echo str_replace('../', base_url(),removePath2($mul_c5)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c6!=""){ 
                                                    $rechkimgtext = strpos($mul_c6, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>6" 
												<?php if($value_check!=""&&$isMulC6){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c6); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c6); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>6"><?php echo str_replace('../', base_url(),removePath2($mul_c6)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c7!=""){ 
                                                    $rechkimgtext = strpos($mul_c7, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>7" 
												<?php if($value_check!=""&&$isMulC7){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c7); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c7); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>7"><?php echo str_replace('../', base_url(),removePath2($mul_c7)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c8!=""){ 
                                                    $rechkimgtext = strpos($mul_c8, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>8" 
												<?php if($value_check!=""&&$isMulC8){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c8); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c8); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>8"><?php echo str_replace('../', base_url(),removePath2($mul_c8)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c9!=""){ 
                                                    $rechkimgtext = strpos($mul_c9, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>9" 
												<?php if($value_check!=""&&$isMulC9){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c9); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c9); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>9"><?php echo str_replace('../', base_url(),removePath2($mul_c9)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c10!=""){ 
                                                    $rechkimgtext = strpos($mul_c10, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>10" 
												<?php if($value_check!=""&&$isMulC10){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c10); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c10); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>10"><?php echo str_replace('../', base_url(),removePath2($mul_c10)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c11!=""){ 
                                                    $rechkimgtext = strpos($mul_c11, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>11" 
												<?php if($value_check!=""&&$isMulC11){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c11); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c11); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>11"><?php echo str_replace('../', base_url(),removePath2($mul_c11)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c12!=""){ 
                                                    $rechkimgtext = strpos($mul_c12, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>12" 
												<?php if($value_check!=""&&$isMulC12){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c12); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c12); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>12"><?php echo str_replace('../', base_url(),removePath2($mul_c12)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c13!=""){ 
                                                    $rechkimgtext = strpos($mul_c13, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>13" 
												<?php if($value_check!=""&&$isMulC13){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c13); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c13); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>13"><?php echo str_replace('../', base_url(),removePath2($mul_c13)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c14!=""){ 
                                                    $rechkimgtext = strpos($mul_c14, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>14" 
												<?php if($value_check!=""&&$isMulC14){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c14); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c14); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>14"><?php echo str_replace('../', base_url(),removePath2($mul_c14)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c15!=""){
                                                    $rechkimgtext = strpos($mul_c15, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";} ?>
                                        <input  <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
												<?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"<?php } ?>  
												name="multi_choice_group<?php echo $numloop; ?>[]" 
												type="checkbox" 
												id="multi_choice_<?php echo $numloop; ?>15" 
												<?php if($value_check!=""&&$isMulC15){echo "checked";} ?> 
												class="with-gap filled-in chk-col-red" 
												value="<?php echo htmlentities($mul_c15); ?>">
                                        <label <?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> <?php if($sv_main['tcmain_status']!=label('done')){ ?>onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c15); ?>');$('#tc_note').val('');"<?php } ?> for="multi_choice_<?php echo $numloop; ?>15"><?php echo str_replace('../', base_url(),removePath2($mul_c15)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                }
                                                $svde_specify_name = "";
                                                if($lang=="thai"){ 

                                                      if($sv_main['isTH']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isENG']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isJP']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                        }
                                                      }
                                                }else if($lang=="english"){ 

                                                      if($sv_main['isENG']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isTH']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isJP']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                        }
                                                      }
                                                }else{
                                                      if($sv_main['isJP']=="1"){
                                                        $svde_specify_name = $value_detail['svde_specify_name_jp'];
                                                      }else{
                                                        if($svde_specify_name==""&&$sv_main['isENG']=="1"){
                                                            $svde_specify_name = $value_detail['svde_specify_name_eng'];
                                                        }
                                                        if($svde_specify_name==""&&$sv_main['isTH']=="1"){
                                                          $svde_specify_name = $value_detail['svde_specify_name_th'];
                                                        }
                                                      }
                                                }
                                                if($value_detail['svde_isSpecify']=="1"&&$svde_specify_name!=""){ 
                                                    

                                            ?>
                                                <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														name="multi_choice_group<?php echo $numloop; ?>[]" 
														type="checkbox" 
														value="svde_specify" 
														id="multi_choice_<?php echo $numloop; ?>16" 
														<?php if($sv_main['tcmain_status']!=label('done')){ ?>
															onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','svde_specify_txt<?php echo $numloop; ?>','<?php echo $numloop; ?>')"
														<?php } ?> 
														<?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> 
														value="svde_specify" 
														class="with-gap filled-in chk-col-red">
                                                <label 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														<?php if($sv_main['tcmain_status']!=label('done')){ ?>
															onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','svde_specify_txt<?php echo $numloop; ?>','<?php echo $numloop; ?>')"
														<?php } ?> 
														for="multi_choice_<?php echo $numloop; ?>16"><?php echo $svde_specify_name; ?></label><br>
                                                <input 	<?php if($sv_main['tcmain_status']==label('done')){ echo "disabled"; }?> 
														type="text" 
														maxlength="255" 
														onkeyup="" 
														class="form-control  text-input-val-<?php echo $numloop; ?>" name="svde_specify_txt" id="svde_specify_txt<?php echo $numloop; ?>" value="<?php echo $value_note; ?>">
                                                <script type="text/javascript">
                                                    $('#multi_choice_<?php echo $numloop; ?>16').on('change', function() {
                                                        var rechk_checkbox = this.checked ? this.value : '';
                                                        if(rechk_checkbox==""){
                                                            $('#tc_require<?php echo $numloop; ?>').val('1');
                                                        }else{
                                                            if($('#svde_specify_txt<?php echo $numloop; ?>').val()==""){
                                                                $('#tc_require<?php echo $numloop; ?>').val('0');
                                                            }else{
                                                                $('#tc_require<?php echo $numloop; ?>').val('1');
                                                            }
                                                        }
                                                    });
                                                    var svde_specify = document.getElementById('svde_specify_txt<?php echo $numloop; ?>');
                                                    var arr = [19,44,45, 46,33,34, 35, 36, 91 ,145,144,112,113,114,115,116,117,118,119,120,121,122,123,27,20,16,17,18,93,13];
                                                    svde_specify.addEventListener("keyup", event => {
                                                      if (jQuery.inArray( event.keyCode, arr ) < 0) {
                                                        var svde_specifyval = $('#svde_specify_txt<?php echo $numloop; ?>').val();
                                                        chkbox_onkey('multi_choice_<?php echo $numloop; ?>16',svde_specifyval,'2','multi_choice_group<?php echo $numloop; ?>[]','<?php echo $numloop; ?>','chkbox');
                                                      }
                                                      // do something
                                                    });
                                                </script>
                                         <?php  }
                                                }
                                            }
                                            ?>
                                            <script type="text/javascript">
                                                function chkbox_onkey(id,value,type,name="", numval="", typechk=""){
                                                    if(value==""){
                                                        $('#'+id).prop('checked', false);
                                                        if(type=="1"){
                                                            $('#tc_answer'+numval).val('');
                                                            $('#tc_note'+numval).val('');
                                                        }
                                                        if(type=="2"){
                                                            $('#tc_note'+numval).val('');
                                                        }
                                                        $('#tc_require'+numval).val('1');
                                                        //$('.btnsend').prop('disabled', false);
                                                    }else{
                                                        $('#'+id).prop('checked', true);
                                                        //$('.btnsend').prop('disabled', false);
                                                        if(type=="1"){
                                                            $('#tc_answer'+numval).val('svde_specify');
                                                            $('#tc_note'+numval).val(value);
                                                        }
                                                        if(type=="2"){
                                                            $('#tc_note'+numval).val(value);
                                                        }
                                                        $('#tc_require'+numval).val('1');
                                                    }
                                                    if(typechk=="chkbox"){
                                                        var elements = document.querySelectorAll('input[name="'+name+'"]:checked');
                                                        var checkedElements = Array.prototype.map.call(elements, function (el, i) {
                                                            return el.value;
                                                        });
                                                        if(id!=""&&jQuery.inArray( "svde_specify", checkedElements )){
                                                            var varlsvde_specify = $('#svde_specify_txt'+numval).val();
                                                            if(varlsvde_specify==""){
                                                                $('#tc_require'+numval).val('0');
                                                            }else{
                                                                $('#tc_require'+numval).val('1');
                                                            }
                                                        }else{
                                                            $('#tc_require'+numval).val('1');
                                                        }
                                                    }
                                                    if(type=="2"){
                                                        var elements = document.querySelectorAll('input[name="'+name+'"]:checked');
                                                        var checkedElements = Array.prototype.map.call(elements, function (el, i) {
                                                            return el.value;
                                                        });
                                                        $('#tc_answer'+numval).val(checkedElements.join('||'));
                                                    }
                                                    onchangetxtspecify(id,numval);
                                                }
                                            </script>
                                <hr>
                                <div class="row">
                                    <div class="col-2">
                                        <?php if($sv_detailtxt!=""){ ?>
                                        <?php if($numloop>=1){
                                                $numpre = 0;
                                                if($numloop==1){
                                                    $pretopage = "quiz_detail";
                                                }else{
                                                    $numpre = $numloop-1;
                                                    $pretopage = "quiz_".$numpre;
                                                }
                                        ?>
                                        <button type="button" onclick="activaTab('<?php echo $pretopage; ?>')" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <?php if($numloop>1){
                                                $numpre = 0;
                                                if($numloop==1){
                                                    $pretopage = "quiz_detail";
                                                }else{
                                                    $numpre = $numloop-1;
                                                    $pretopage = "quiz_".$numpre;
                                                }
                                        ?>
                                        <button type="button" onclick="activaTab('<?php echo $pretopage; ?>')" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                                        <?php } ?>
                                    <?php } ?>
                                    </div>
                                    <div class="col-10 text-right">
                                        <?php if($sv_main['tcmain_status']!=label('done')){ ?>
                                        <button type="button" onclick="onsave('<?php echo $numloop; ?>','<?php echo $value_detail['svde_id'];?>')" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                        <?php } ?>
                                        <?php if($numloop<countArray($sv_detail)){
                                                $numnext = 0;
                                                    $numnext = $numloop+1;
                                                    $nexttopage = "quiz_".$numnext;
                                        ?>
                                        <button type="button" class="btn btn-outline-secondary" onclick="activaTab('<?php echo $nexttopage; ?>','<?php echo $numloop; ?>')"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                                        <?php } ?>
                                        <?php if(countArray($sv_detail)==$numloop){ ?>
                                            <?php if($sv_main['tcmain_status']!=label('done')){ ?>
                                        <button type="button" onclick="onsend()" class="btn btn-outline-secondary btnsend"><?php echo label('preSend'); ?></button>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                                  <?php
                                            $numloop++;
                                        }
                                    } ?>
                            </div>
                        </form>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
    <script type="text/javascript">

      <?php if($arr_statuscos=="0"){ ?>
                        swal({
                            title: '<?php echo label('lrn_p_data_not_found'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                        }).then(function () {
                            window.location = "<?php echo REAL_PATH."/survey" ?>";
                        })
      <?php }else if($arr_statuscos=="2"){ ?>
                        swal({
                            title: '<?php echo label('lrn_p_data_not_found'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                        }).then(function () {
                            window.location = "<?php echo REAL_PATH."/survey" ?>";
                        })
      <?php }else if($arr_statuscos=="3"){ ?>
                        swal({
                            title: '<?php echo label('cos_expired'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('lrn_btn_ok'); ?>'
                        }).then(function () {
                            window.location = "<?php echo REAL_PATH."/survey" ?>";
                        })
      <?php } ?>
      $( "footer" ).addClass( "mt-5" );
        /*$("html, body").animate({ 
            scrollTop: 0
        }, "slow");*/
        //$('.collapse').collapse('show');
        function activaTab(tab,numloop){
          $('.nav-tabs a[href="#' + tab + '"]').tab('show');
            var tc_note = $('#tc_note'+parseInt(numloop)).val();
            var tc_answer = $("#tc_answer"+numloop).val();
            var rechk_speci = tc_answer.indexOf("svde_specify");
            var tc_require = "1";
            if(rechk_speci<0){
                tc_require="1";
            }else{
                if(tc_note.length==0){
                    tc_require="0";
                }
            }
            $('#tc_require'+parseInt(numloop)).val(tc_require);
/*tc_require
multi_choice_group
svde_specify_txt*/
        }

        function createButton(text,classs,style,id,column, cb) {
            return $('<div class="'+column+'"> <button class="'+classs+'" style="'+style+'" id="'+id+'">' + text + '</button></div>').on('click', cb);
        }
        function onselectVal(numVal="", value1="", value2="", type="", id=""){
            if(type=="svde_specify"){
                $('#'+id).focus();
                $('#svde_specify_txt'+numVal).attr('readonly', false);
                $('#tc_require'+numVal).val('0');
                //$('.btnsend').prop('disabled', true);
                //console.log(type);
            }else{
                $('#svde_specify_txt'+numVal).val('');
                $('#svde_specify_txt'+numVal).attr('readonly', true);
               // $('.btnsend').prop('disabled', false);
                $('#tc_require'+numVal).val('1');

                if ($("#svde_specify_txt" + numVal).length > 0){
                    $("#svde_specify_txt" + numVal).css('border', '');
                }
            }

            $('#tc_answer'+numVal).val(value1);
            $('#tc_note'+numVal).val(value2);
        }
        function onsave(numloop,svde_id){
            var tc_answer = $('#tc_answer'+parseInt(numloop)).val();
            var tc_note = $('#tc_note'+parseInt(numloop)).val();
            var tc_require = $('#tc_require'+parseInt(numloop)).val();
            var sv_detail_amount = parseInt('<?php echo countArray($sv_detail) ?>');
            var rechk_speci = tc_answer.search("svde_specify");
            if(rechk_speci<0){
                tc_require="1";
            }else{
                if(tc_note.length==0){
                    tc_require="0";
                }
            }
            $('#tc_require'+parseInt(numloop)).val(tc_require);
            var values = $("input[name='tc_require[]']")
              .map(function(){return $(this).val();}).get();
            var total = 0;
            $( "input[name='tc_require[]']" ).each( function(){
              total += parseFloat( $( this ).val() ) || 0;
            });
			
            if(tc_require=="1"){

                $.ajax({
                    url:"<?=base_url()?>index.php/querydata/updateSaveSVTC",
                    method:'POST',
                    data: $("#survey_form").serialize(),
                    //data:{sv_id:'<?php echo $sv_id; ?>',svde_id:svde_id,tc_answer:tc_answer,tc_note:tc_note,emp_id:'<?php echo $emp_id; ?>'},
                    dataType:'json',
                    success:function(data)
                    {
                        if(data.msg=="2"){
                            swal(
                                '<?php echo label("com_msg_success"); ?>!',
                                '',
                                'success'
                            )
                        }else{
                                    swal({
                                        title: '<?php echo label("com_msg_error_save"); ?>',
                                        text: "",
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label("ok"); ?>'
                                    })
                        }
                    }
                });
            }else{
                $('#svde_specify_txt'+numloop).focus();
                        if ($("#svde_specify_txt" + numloop).length > 0){
                            var str1 = $('#tc_answer'+numloop).val();
                            var str2 = "svde_specify";
                            if(str1.indexOf(str2) != -1){
                                document.getElementById("svde_specify_txt" + numloop).focus();
                                $("#svde_specify_txt" + numloop).css('border', '3px solid #e74c3c');
                            }
                        }
            }
            /*if(numloop<sv_detail_amount){
                $('.btnsend').prop('disabled', true);
            }else{
                $('.btnsend').prop('disabled', false);
            }*/
            if(tc_require=="0"){
                swal({
                                        title: '<?php echo label("com_msg_form_error"); ?>',
                                        text: "",
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label("ok"); ?>'
                                    })
                //$('.btnsend').prop('disabled', true);
            }else{
                //$('.btnsend').prop('disabled', false);
            }
        }
        function onchangetxtspecify(id,numloop){
            var valuechk = $('#'+id).val();
            if(valuechk==""){
                var str1 = $('#tc_answer'+numloop).val();
                var str2 = "svde_specify";
                if(str1.indexOf(str2) != -1){
                    document.getElementById("svde_specify_txt" + numloop).focus();
                    $("#svde_specify_txt" + numloop).css('border', '3px solid #e74c3c');
                }
            }else{
                //$("#svde_specify_txt" + numloop).css('border', '');
            }
        }

		
        function onsend(){
            var sv_detail_amount = parseInt('<?php echo countArray($sv_detail) ?>');
            var tc_answer = $('#tc_answer'+parseInt(sv_detail_amount)).val();
            var tc_note = $('#tc_note'+parseInt(sv_detail_amount)).val();
            var tc_require = $('#tc_require'+parseInt(sv_detail_amount)).val();
            var rechk_speci = tc_answer.search("svde_specify");
            if(rechk_speci<0){
                tc_require="1";
            }else{
                if(tc_note.length==0){
                    tc_require="0";
                }
            }

            var total = 0;
            $( "input[name='tc_require[]']" ).each( function(){
              total += parseFloat( $( this ).val() ) || 0;
            });

                var numloopchk = 1;
                var numchk = 1;
                var pagenumber = 0;
                var map = {};
				const svdeTypeInput = ["sa", "sub"];
                $("input[name='tc_answer[]']").each(function() {
					let answerOfUser = $(this).val();
                    var str1 = $(this).val();
                    var str2 = "svde_specify";
                    if(str1.indexOf(str2) != -1){
                    //if($(this).val()=="svde_specify"){
                        var tc_note = $('#tc_note'+numchk).val();

                        answerOfUser = tc_note;
                    }
                    map[numchk] = answerOfUser.trim();
					if ($('.text-input-val-' + numchk).length > 0) {
						var textInput = $('.text-input-val-' + numchk).val();
						$('.text-input-val-' + numchk).val(textInput.trim());
						if (svdeTypeInput.includes($('#svde_type'+numchk).val())) {
							$(this).val($(this).val().trim());
						}
						
					}
                    numchk++;
                });
                numloopchk = 0;
                numchk = 1;
                $.each(map, function(key, value) {
                      if(value==""&&numloopchk==0){
                        pagenumber = key;
                        numloopchk++;
                      }
                });
                var numchk = 1;
                /*var arr = $('input[name="tc_answer[]"]').each(function () {
                    var tc_answer = $( this ).val();
                  console.log(numchk+":::"+tc_answer);
                  if(tc_answer!=""){
                    if(tc_answer=="svde_specify"){
                        var tc_note = $('#tc_note'+numloopchk).val();
                        if(tc_note!=""){
                            return tc_answer; // $(this).val()
                        }else{
                            if(pagenumber==0){
                                console.log("1005"+numloopchk);
                            pagenumber = numloopchk;
                            }
                        }
                    }else{
                        return tc_answer; // $(this).val()
                    }
                  }else{
                  console.log(numchk+":::"+tc_answer);
                    if(numchk==1){
                        pagenumber = numloopchk;
                    }
                  }
                  console.log(numloopchk+"<br>");
                  numloopchk+=1;
                  numchk++;
                }).get();*/
                if(pagenumber!=0){
                    swal({
                        title: '<?php echo label("qiz-not-complete"); ?>',
                        text: "",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: '<?php echo label("m_ok"); ?>'
                    }).then(function () {
                        $('.nav-tabs a[href="#quiz_'+pagenumber+'"]').tab('show');
                        if ($("#svde_specify_txt" + pagenumber).length > 0){
                            var str1 = $('#tc_answer'+pagenumber).val();
                            var str2 = "svde_specify";
                            if(str1.indexOf(str2) != -1){
                                document.getElementById("svde_specify_txt" + pagenumber).focus();
                                $("#svde_specify_txt" + pagenumber).css('border', '3px solid #e74c3c');
                            }
                        }
                    })
                }else{
                      swal({
                          title: '<?php echo label("confirm_submit_quiz"); ?> ',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: "#1abc9c",   
                          cancelButtonColor: "#DD6B55",
                          confirmButtonText: '<?php echo label("yes"); ?>',
                          cancelButtonText: '<?php echo label("no"); ?>'
                      }).then(function (isChk) {
                    
                        if(isChk.value){
                        $.ajax({
                            url:"<?=base_url()?>index.php/querydata/updateSaveSVMainTC",
                            method:'POST',
                            data: $("#survey_form").serialize(),
                            //data:{sv_id:'<?php echo $sv_id; ?>',svde_id:svde_id,tc_answer:tc_answer,tc_note:tc_note,emp_id:'<?php echo $emp_id; ?>'},
                            dataType:'json',
                            success:function(data)
                            {
                            //  $(".lds-dual-ring.hidden").show();
                               setTimeout(function(){ 
                                if(data.msg=="2"){
                                    var isCert = 'col-lg-6';
                                    $('.swal2-popup').addClass('swal-size');
                                    var buttons = $('<br><br><br><div class="row button-group">')
                                    .append(createButton('<i class="mdi mdi-backburger"></i> <?php echo label("godashboardtxt"); ?>','btn btn-flat btn-block btn-primary btn_dashboard btnalert','','',isCert, function() {
                                        swal.closeModal();
                                    })).append(createButton('<i class="mdi mdi-magnify"></i> <?php echo label("review_answertxt"); ?>','btn btn-flat btn-block btn-info view_answer btnalert','','',isCert, function() {
                                        swal.closeModal();
                                    }))
                                    var typetxt = "success";
                                    var text = '<?php echo label("save-complete"); ?>';
                                    buttons.append('</div>');
                                    swal({
                                        title: text,
                                        text: text,
                                        html: buttons,
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        allowEscapeKey : false,
                                        allowOutsideClick: false
                                    });
                            /*swal(
                                '<?php echo label("save-complete"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                                location.reload();
                            })*/
                                }else{
                                swal({
                                    title: '<?php echo label("com_msg_error_save"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("ok"); ?>'
                                })
                                }
                                //  $(".lds-dual-ring.hidden").hide(); 

                                }, 3000);
                                
                            },
                            complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
                                
                            },
                        });
                        }
                      });
                }

            /*$('#tc_require'+parseInt(sv_detail_amount)).val(tc_require);
            var values = $("input[name='tc_require[]']")
              .map(function(){return $(this).val();}).get();
            var total = 0;
            $( "input[name='tc_require[]']" ).each( function(){
              total += parseFloat( $( this ).val() ) || 0;
            });
            if(total==sv_detail_amount){
            }else{
                swal({
                                        title: '<?php echo label("com_msg_form_error"); ?>',
                                        text: "",
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: '<?php echo label("ok"); ?>'
                                    })
            }*/
           /* $.ajax({
                url:"<?=base_url()?>index.php/querydata/updateSaveSVMainTC",
                method:'POST',
                data:{sv_id:'<?php echo $sv_id; ?>',emp_id:'<?php echo $emp_id; ?>'},
                dataType:'json',
                success:function(data)
                {
                    if(data.msg=="2"){
                        swal(
                            '<?php echo label("save-complete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                    }else{
                                swal({
                                    title: '<?php echo label("com_msg_error_save"); ?>',
                                    text: "",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: '<?php echo label("ok"); ?>'
                                })
                    }
                }
            });*/
        }
            $(document).on('click', '.btn_dashboard', function(event){
                event.preventDefault();
                $('.swal2-popup').removeClass('swal-size');
                window.location.href = "<?php echo base_url().'dashboard';?>";
            });
            $(document).on('click', '.view_answer', function(event){
                event.preventDefault();
                $('.swal2-popup').removeClass('swal-size');
                location.reload();
            });
    </script>
</body>

</html>
