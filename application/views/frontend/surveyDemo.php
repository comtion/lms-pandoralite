<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
    <!--Wave Effects -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/sidebarmenu.js"></script>
    <style type="text/css">
        .break-word {
            word-wrap: break-word;
        }
        .vtabs {
            display: table-row;
        }
    </style>
    <script type="text/javascript">
        function oncheckboxVal(name="",id="",number_id=""){
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
                }else{
                    $('#tc_require'+number_id).val('1');
                }
            }else{
                $('#tc_require'+number_id).val('1');
                if ($('#multi_choice_'+number_id+'6').is(':checked')) {
                }else{
                    $('#tc_require'+number_id).val('1');
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
            <div class="row">
                <div class="card card-body">
                <div class="row">
                    <div class="col-md-4" align="left">
                      <?php 
                        $pageback = REAL_PATH.'/survey/list_survey';

                        if($isDashboard=="1"){
                            $pageback = REAL_PATH.'/dashboard';
                        }
                      ?>
                      <button class="btn btn-outline-info " onclick="window.location.href='<?php echo $pageback; ?>'"><i class="mdi mdi-keyboard-return"></i> <?php echo ucwords(label('m_previous')); ?></button>
                    </div>
                    <div class="col-md-8" align="right">

                <?php if($sv_main['isApprove']=="0"){ ?>
                    <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i><?php echo label('d_reject'); ?></span></h4>
                <?php }else if($sv_main['isApprove']=="1"){ ?>
                    <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i><?php echo label('d_approved'); ?></span></h4>
                <?php }else if($sv_main['isApprove']=="2"){ ?>
                    <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i><?php echo label('wait_approve'); ?></span></h4>
                <?php }else if($sv_main['isApprove']=="22"&&countArray($sv_detail)>0){ 
                            if($sv_main['sv_status']=="1"){
                ?>
                      <button type="button" id="<?php echo $sv_main['sv_id']; ?>" class="btn mdi-btn waves-effect waves-light btn-secondary active approve" title="<?php echo label('d_approve'); ?>" style="-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;">
                            <span class="icon is-medium"><i class="mdi mdi-24px mdi-alert text-warning mdi-light"></i> <?php echo label('d_waitapprove'); ?></span>
                      </button>
                    <!-- <button type="button" class="btn waves-effect waves-light btn-secondary float-right approve" name="approve" id="<?php echo $sv_main['sv_id']; ?>"><i class="mdi mdi-alert text-warning"></i> <?php echo label('sv_b_approve'); ?></button> -->
                <?php       }
                      }else if($sv_main['isApprove']=="3"){ 
                        if($isCreator=="1"){
                            if($sv_main['sv_status']=="1"){
                ?>
                      <button type="button" id="<?php echo $sv_main['sv_id']; ?>" class="btn mdi-btn waves-effect waves-light btn-secondary active publicsv" style="background-color: #34495e;color: #ecf0f1;" title="<?php echo label('d_approve'); ?>" style="-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;">
                            <span class="icon is-medium"><i class="mdi mdi-24px mdi-web text-warning mdi-light"></i> <?php echo label('public'); ?></span>
                      </button>
                    <?php   }
                        }else{ ?>
                    <h4><span id="txt_approve"><i class="mdi mdi-timer-sand"></i><?php echo label('d_waitcreate'); ?></span></h4>
                    <?php } ?>
                <?php } ?>
               
                    </div>
                </div>
                </div>
            </div>
            <style type="text/css">
                  p img{
                    max-width: 100%;
                    height: auto;
                  }
            </style>
                <?php 

                  function removePath2($str) {
                    return str_replace('../../', '../', $str);
                  }
                  $sv_lang = explode(',', $sv_main['sv_lang']);
                  $sv_main['isTH'] = in_array('th',$sv_lang)?"1":"0";
                  $sv_main['isENG'] = in_array('eng',$sv_lang)?"1":"0";
                  $sv_main['isJP'] = in_array('jp',$sv_lang)?"1":"0";
                if($lang=="thai"){ 
                    $sv_title = $sv_main['sv_title_th']!=""?$sv_main['sv_title_th']:$sv_main['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$sv_main['sv_title_jp'];
                    $sv_explanation = $sv_main['sv_explanation_th']!=""?$sv_main['sv_explanation_th']:$sv_main['sv_explanation_eng'];
                    $sv_explanation = $sv_explanation!=""?$sv_explanation:$sv_main['sv_explanation_jp'];
                    $sv_detailtxt = $sv_main['sv_detail_th']!=""?$sv_main['sv_detail_th']:$sv_main['sv_detail_eng'];
                    $sv_detailtxt = $sv_detailtxt!=""?$sv_detailtxt:$sv_main['sv_detail_jp'];
                }else if($lang=="english"){ 
                    $sv_title = $sv_main['sv_title_eng']!=""?$sv_main['sv_title_eng']:$sv_main['sv_title_th'];
                    $sv_title = $sv_title!=""?$sv_title:$sv_main['sv_title_jp'];
                    $sv_explanation = $sv_main['sv_explanation_eng']!=""?$sv_main['sv_explanation_eng']:$sv_main['sv_explanation_th'];
                    $sv_explanation = $sv_explanation!=""?$sv_explanation:$sv_main['sv_explanation_jp'];
                    $sv_detailtxt = $sv_main['sv_detail_eng']!=""?$sv_main['sv_detail_eng']:$sv_main['sv_detail_th'];
                    $sv_detailtxt = $sv_detailtxt!=""?$sv_detailtxt:$sv_main['sv_detail_jp'];
                }else{
                    $sv_title = $sv_main['sv_title_jp']!=""?$sv_main['sv_title_jp']:$sv_main['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$sv_main['sv_title_th'];
                    $sv_explanation = $sv_main['sv_explanation_jp']!=""?$sv_main['sv_explanation_jp']:$sv_main['sv_explanation_eng'];
                    $sv_explanation = $sv_explanation!=""?$sv_explanation:$sv_main['sv_explanation_th'];
                    $sv_detailtxt = $sv_main['sv_detail_jp']!=""?$sv_main['sv_detail_jp']:$sv_main['sv_detail_eng'];
                    $sv_detailtxt = $sv_detailtxt!=""?$sv_detailtxt:$sv_main['sv_detail_th'];
                }
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
                    <img class="card-img-top img-responsive" <?php if(isset($sv_main['sv_cover'])){ ?>src="<?php echo REAL_PATH;?>/uploads/publicsv/<?php echo $sv_main['sv_cover']; ?>"<?php } ?> onerror="this.src='<?php echo REAL_PATH;?>/images/cover_survey.jpg';" alt="">
                  </div>
                <?php } ?>
                  <div class="col-md-8 col-lg-8 mb-0 card card-body">
                    <?php echo str_replace('../', base_url(), removePath2($sv_explanation)); ?>
                  </div>
                </div>

            </div>
            <?php
            $check_loop = 0;
            if($sv_detailtxt==""){
                $check_loop = 1;
            }

            ?>
            <!-- Collapse Survey -->
            <div class="container-fluid p-0 mb-3">
                <a href="#" class="btn btn-block <?php if($sv_main['tcmain_status']==label('done')){ ?>imat-red-bg btn-danger<?php } ?> waves-effect waves-light rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_2" <?php if($sv_main['tcmain_status']!=label('done')){ ?>style="background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;"<?php } ?> aria-expanded="true" aria-controls="collapseExample_2">
                    <?php if($sv_main['tcmain_status']==label('done')){ ?><i class="fa fas fa-check mr-2"></i><?php } ?><?php echo $sv_title; ?>
                    <i class="fa fa-chevron-right float-right"></i>
                    <i class="fa fa-chevron-down float-right"></i>
                </a>
                <div class="collapse show" id="collapseExample_2">
                    <!-- MOBILE NAV -->
                    <div class="hidden-sm-up">
                      <div class="list-group">
                        <?php if($sv_detailtxt!=""){ ?>
                        <a href="#quiz_detail" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick <?php if($check_loop==0){ ?>active<?php } ?>"><?php echo label('summary'); ?></a>
                        <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                    foreach ($sv_detail as $key_detail => $value_detail) {
                        ?>
                        <a href="#quiz_<?php echo $numloop; ?>" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick <?php if($check_loop==$numloop){ ?>active<?php } ?>"><?php echo label('preNo'); ?> <?php echo $numloop; ?></a>
                        <?php           $numloop++;
                                    }
                                } ?>
                        <?php }else{ ?>
                        <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                    foreach ($sv_detail as $key_detail => $value_detail) {
                        ?>
                        <a href="#quiz_<?php echo $numloop; ?>" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick <?php if($check_loop==$numloop){ ?>active<?php } ?>"><?php echo label('preNo'); ?> <?php echo $numloop; ?></a>
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
                            <div class="col-2 hidden-sm-down" style="word-wrap: break-word;height: 500px; overflow-y: auto; padding-right: 0;">
                            <ul class="nav nav-tabs tabs-vertical hidden-xs-down" role="tablist">
                            <?php if($sv_detailtxt!=""){ ?>
                                <li class="nav-item">
                                  <a class="nav-link <?php if($check_loop==0){ ?>active show<?php } ?>" data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
                                    <span><?php echo label('summary'); ?></span>
                                  </a>
                                </li>
                            <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                        $numheader = 1;
                                        $txt_header = '';
                                        foreach ($sv_detail as $key_detail => $value_detail) {

                                                if($lang=="thai"){ 
                                                    $svde_header = $value_detail['svde_header_th']!=""?$value_detail['svde_header_th']:$value_detail['svde_header_eng'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
                                                }else if($lang=="english"){ 
                                                    $svde_header = $value_detail['svde_header_eng']!=""?$value_detail['svde_header_eng']:$value_detail['svde_header_th'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
                                                }else{
                                                    $svde_header = $value_detail['svde_header_jp']!=""?$value_detail['svde_header_jp']:$value_detail['svde_header_eng'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_th'];
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
                              <a class="nav-link <?php if($check_loop==$numloop){ ?>active show<?php } ?>" data-toggle="tab" href="#quiz_<?php echo $numloop; ?>" role="tab" aria-selected="false">
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
                                                    $svde_header = $value_detail['svde_header_th']!=""?$value_detail['svde_header_th']:$value_detail['svde_header_eng'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
                                                }else if($lang=="english"){ 
                                                    $svde_header = $value_detail['svde_header_eng']!=""?$value_detail['svde_header_eng']:$value_detail['svde_header_th'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_jp'];
                                                }else{
                                                    $svde_header = $value_detail['svde_header_jp']!=""?$value_detail['svde_header_jp']:$value_detail['svde_header_eng'];
                                                    $svde_header = $svde_header!=""?$svde_header:$value_detail['svde_header_th'];
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
                              <a class="nav-link  <?php if($check_loop==$numloop){ ?>active show<?php } ?>" data-toggle="tab" href="#quiz_<?php echo $numloop; ?>" role="tab" aria-selected="false">
                                <span><?php echo label('preNo'); ?> <?php echo $numloop; ?></span>
                              </a>
                            </li>
                            <?php         $numloop++;
                                        }
                                    }
                                  } ?>
                            </ul>
                            </div>
                            <!-- Tab panes -->
                            <div class="col-lg-10 col-sm-12" style="overflow: hidden;">
                            <div class="tab-content pt-0 d-block" style="height: 500px; overflow-y: auto;">
                            <?php if($sv_detailtxt!=""){ ?>
                                <div class="tab-pane <?php if($check_loop==0){ ?>active show<?php } ?>" id="quiz_detail" role="tabpanel">
                                  <h4><?php echo label('summary'); ?></h4>
                                  <?php echo str_replace('../', base_url(),$sv_detailtxt); ?>
                                  <hr>
                                  <button type="button" onclick="activaTab('quiz_1')" class="btn btn-outline-secondary float-right"><?php echo label('questionnaireStart'); ?></button>
                                </div>
                            <?php } ?>
                            <?php   if(countArray($sv_detail)>0){ $numloop = 1;
                                        foreach ($sv_detail as $key_detail => $value_detail) {
                                            $value_check = "";
                                            $value_note = "";
                                            if($value_detail['svde_type']=="sa"||$value_detail['svde_type']=="sub"){
                                                if(isset($value_detail['detail_tc'])&&countArray($value_detail['detail_tc'])>0){
                                                    $value_check = $value_detail['detail_tc']['tc_answer'];
                                                }
                                            }else{
                                                if(isset($value_detail['detail_tc'])&&countArray($value_detail['detail_tc'])>0){
                                                    $value_check = explode('||', $value_detail['detail_tc']['tc_answer']);
                                                    $value_note = $value_detail['detail_tc']['tc_note'];
                                                }
                                            }

                                        if($lang=="thai"){ 
                                            $svde_name = $value_detail['svde_name_th']!=""?$value_detail['svde_name_th']:$value_detail['svde_name_eng'];
                                            $svde_name = $svde_name!=""?$svde_name:$value_detail['svde_name_jp'];
                                            $svde_info = $value_detail['svde_info_th']!=""?$value_detail['svde_info_th']:$value_detail['svde_info_eng'];
                                            $svde_info = $svde_info!=""?$svde_info:$value_detail['svde_info_jp'];
                                        }else if($lang=="english"){ 
                                            $svde_name = $value_detail['svde_name_eng']!=""?$value_detail['svde_name_eng']:$value_detail['svde_name_th'];
                                            $svde_name = $svde_name!=""?$svde_name:$value_detail['svde_name_jp'];
                                            $svde_info = $value_detail['svde_info_eng']!=""?$value_detail['svde_info_eng']:$value_detail['svde_info_th'];
                                            $svde_info = $svde_info!=""?$svde_info:$value_detail['svde_info_jp'];
                                        }else{
                                            $svde_name = $value_detail['svde_name_jp']!=""?$value_detail['svde_name_jp']:$value_detail['svde_name_eng'];
                                            $svde_name = $svde_name!=""?$svde_name:$value_detail['svde_name_th'];
                                            $svde_info = $value_detail['svde_info_jp']!=""?$value_detail['svde_info_jp']:$value_detail['svde_info_eng'];
                                            $svde_info = $svde_info!=""?$svde_info:$value_detail['svde_info_th'];
                                        }
                            ?>
                        <input type="hidden" id="tc_answer<?php echo $numloop; ?>" name="tc_answer<?php echo $numloop; ?>">
                        <input type="hidden" id="tc_note<?php echo $numloop; ?>" name="tc_note<?php echo $numloop; ?>">
                        <input type="hidden" id="tc_require<?php echo $numloop; ?>" name="tc_require<?php echo $numloop; ?>" value="1">
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
                                <!--         <div class="break-word" style="max-width:100%;">
                                        <h3><?php echo $numloop; ?>. <?php echo $svde_name; ?></h3>
                                        <?php if($svde_info!=""){ ?><p><?php echo $svde_info; ?></p> <?php } ?>
                                        </div> -->
                                <script type="text/javascript">
                                    
                                    var elmnt = document.getElementById("quiz_<?php echo $numloop; ?>");
                                    //$(".break-word").width($(".tab-pane").width());
                                </script>
                                        <?php if($value_detail['svde_type']=="sa"||$value_detail['svde_type']=="sub"){
                            ?>
                                            <?php if($value_detail['svde_type']=="sub"){ ?>
                                          <textarea class="form-control" id="textarea<?php echo $numloop; ?>" name="textarea<?php echo $numloop; ?>" onkeyup="onselectVal('<?php echo $numloop; ?>',this.value,'')" maxlength="10000" rows="5"><?php echo $value_check; ?></textarea>
                                            <?php }else{ ?>
                                              <input type="text" class="form-control" id="textarea<?php echo $numloop; ?>" onkeyup="onselectVal('<?php echo $numloop; ?>',this.value,'')"  maxlength="255" name="textarea<?php echo $numloop; ?>" value="<?php echo $value_check; ?>">
                                            <?php } ?>
                                    <?php  }else if($value_detail['svde_type']=="scale"){ ?>
                                <div class="text-center">
                                    <?php echo label('smax'); ?>
                                    <input onclick="onselectVal('<?php echo $numloop; ?>','5','')" name="scale_choice_group<?php echo $numloop; ?>" type="radio" <?php if($value_check!=""&&in_array('5', $value_check)){echo "checked";} ?> id="scale_choice_<?php echo $numloop; ?>5" class="with-gap radio-col-red">
                                    <label onclick="onselectVal('<?php echo $numloop; ?>','5','')" for="scale_choice_<?php echo $numloop; ?>5">5</label>
                                    <input onclick="onselectVal('<?php echo $numloop; ?>','4','')" name="scale_choice_group<?php echo $numloop; ?>" type="radio" <?php if($value_check!=""&&in_array('4', $value_check)){echo "checked";} ?> id="scale_choice_<?php echo $numloop; ?>4" class="with-gap radio-col-red">
                                    <label onclick="onselectVal('<?php echo $numloop; ?>','4','')" for="scale_choice_<?php echo $numloop; ?>4">4</label>
                                    <input onclick="onselectVal('<?php echo $numloop; ?>','3','')" name="scale_choice_group<?php echo $numloop; ?>" type="radio" <?php if($value_check!=""&&in_array('3', $value_check)){echo "checked";} ?> id="scale_choice_<?php echo $numloop; ?>3" class="with-gap radio-col-red">
                                    <label onclick="onselectVal('<?php echo $numloop; ?>','3','')" for="scale_choice_<?php echo $numloop; ?>3">3</label>
                                    <input onclick="onselectVal('<?php echo $numloop; ?>','2','')" name="scale_choice_group<?php echo $numloop; ?>" type="radio" <?php if($value_check!=""&&in_array('2', $value_check)){echo "checked";} ?> id="scale_choice_<?php echo $numloop; ?>2" class="with-gap radio-col-red">
                                    <label onclick="onselectVal('<?php echo $numloop; ?>','2','')" for="scale_choice_<?php echo $numloop; ?>2">2</label>
                                    <input onclick="onselectVal('<?php echo $numloop; ?>','1','')" name="scale_choice_group<?php echo $numloop; ?>" type="radio" <?php if($value_check!=""&&in_array('1', $value_check)){echo "checked";} ?> id="scale_choice_<?php echo $numloop; ?>1" class="with-gap radio-col-red">
                                    <label onclick="onselectVal('<?php echo $numloop; ?>','1','')" for="scale_choice_<?php echo $numloop; ?>1">1</label>
                                    <?php echo label('smin'); ?>




                                </div>
                                    <?php  }else if($value_detail['svde_type']=="2choice"){
                                            if(isset($value_detail['multi'])&&countArray($value_detail['multi'])>0)
                                            {

                                                if($lang=="thai"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_th']!=""?$value_detail['multi']['mul_c1_th']:$value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_th']!=""?$value_detail['multi']['mul_c2_th']:$value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_jp'];
                                                }else if($lang=="english"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_eng']!=""?$value_detail['multi']['mul_c1_eng']:$value_detail['multi']['mul_c1_th'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_eng']!=""?$value_detail['multi']['mul_c2_eng']:$value_detail['multi']['mul_c2_th'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_jp'];
                                                }else{
                                                    $mul_c1 = $value_detail['multi']['mul_c1_jp']!=""?$value_detail['multi']['mul_c1_jp']:$value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_th'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_jp']!=""?$value_detail['multi']['mul_c2_jp']:$value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_th'];
                                                }

                                                if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')" name="two_choicegroup<?php echo $numloop; ?>" type="radio" id="two_choice<?php echo $numloop; ?>1" <?php if($value_check!=""&&in_array($mul_c1, $value_check)){echo "checked";} ?> class="with-gap radio-col-red">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')" for="two_choice<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(), removePath2($mul_c1)); ?></label><br>
                                         <?php  if($rechkimgtext){ echo "</p>";}
                                                    }
                                                if($mul_c2!=""){ 
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')" name="two_choicegroup<?php echo $numloop; ?>" <?php if($value_check!=""&&in_array($mul_c2, $value_check)){echo "checked";} ?> type="radio" id="two_choice<?php echo $numloop; ?>2" class="with-gap radio-col-red">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')" for="two_choice<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(), removePath2($mul_c2)); ?></label><br>
                                          <?php if($rechkimgtext){ echo "</p>";}
                                                }
                                                if($lang=="thai"){ 
                                                    $svde_specify_name = $value_detail['svde_specify_name_th']!=""?$value_detail['svde_specify_name_th']:$value_detail['svde_specify_name_eng'];
                                                    $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value_detail['svde_specify_name_jp'];
                                                }else if($lang=="english"){ 
                                                    $svde_specify_name = $value_detail['svde_specify_name_eng']!=""?$value_detail['svde_specify_name_eng']:$value_detail['svde_specify_name_th'];
                                                    $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value_detail['svde_specify_name_jp'];
                                                }else{
                                                    $svde_specify_name = $value_detail['svde_specify_name_jp']!=""?$value_detail['svde_specify_name_jp']:$value_detail['svde_specify_name_eng'];
                                                    $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value_detail['svde_specify_name_th'];
                                                }
                                                /*if($value_detail['svde_isSpecify']=="1"&&$svde_specify_name!=""){ 

                                                ?>
                                                <input onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" <?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> name="two_choicegroup<?php echo $numloop; ?>" type="radio" id="two_choice<?php echo $numloop; ?>3" class="with-gap radio-col-red">
                                                <label onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" for="two_choice<?php echo $numloop; ?>3"><?php echo $svde_specify_name; ?></label><br>
                                                <input onkeyup="chkbox_onkey('two_choice<?php echo $numloop; ?>3',this.value,'1','','<?php echo $numloop; ?>');" type="text" value="<?php echo $value_note; ?>" class="form-control" name="svde_specify_txt" id="svde_specify_txt<?php echo $numloop; ?>">
                                         <?php  }*/
                                            }
                                           }else if($value_detail['svde_type']=="multi"){
                                            
                                                if($lang=="thai"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_th']!=""?$value_detail['multi']['mul_c1_th']:$value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_th']!=""?$value_detail['multi']['mul_c2_th']:$value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_jp'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_th']!=""?$value_detail['multi']['mul_c3_th']:$value_detail['multi']['mul_c3_eng'];
                                                    $mul_c3 = $mul_c3!=""?$mul_c3:$value_detail['multi']['mul_c3_jp'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_th']!=""?$value_detail['multi']['mul_c4_th']:$value_detail['multi']['mul_c4_eng'];
                                                    $mul_c4 = $mul_c4!=""?$mul_c4:$value_detail['multi']['mul_c4_jp'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_th']!=""?$value_detail['multi']['mul_c5_th']:$value_detail['multi']['mul_c5_eng'];
                                                    $mul_c5 = $mul_c5!=""?$mul_c5:$value_detail['multi']['mul_c5_jp'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_th']!=""?$value_detail['multi']['mul_c6_th']:$value_detail['multi']['mul_c6_eng'];
                                                    $mul_c6 = $mul_c6!=""?$mul_c6:$value_detail['multi']['mul_c6_jp'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_th']!=""?$value_detail['multi']['mul_c7_th']:$value_detail['multi']['mul_c7_eng'];
                                                    $mul_c7 = $mul_c7!=""?$mul_c7:$value_detail['multi']['mul_c7_jp'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_th']!=""?$value_detail['multi']['mul_c8_th']:$value_detail['multi']['mul_c8_eng'];
                                                    $mul_c8 = $mul_c8!=""?$mul_c8:$value_detail['multi']['mul_c8_jp'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_th']!=""?$value_detail['multi']['mul_c9_th']:$value_detail['multi']['mul_c9_eng'];
                                                    $mul_c9 = $mul_c9!=""?$mul_c9:$value_detail['multi']['mul_c9_jp'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_th']!=""?$value_detail['multi']['mul_c10_th']:$value_detail['multi']['mul_c10_eng'];
                                                    $mul_c10 = $mul_c10!=""?$mul_c10:$value_detail['multi']['mul_c10_jp'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_th']!=""?$value_detail['multi']['mul_c11_th']:$value_detail['multi']['mul_c11_eng'];
                                                    $mul_c11 = $mul_c11!=""?$mul_c11:$value_detail['multi']['mul_c11_jp'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_th']!=""?$value_detail['multi']['mul_c12_th']:$value_detail['multi']['mul_c12_eng'];
                                                    $mul_c12 = $mul_c12!=""?$mul_c12:$value_detail['multi']['mul_c12_jp'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_th']!=""?$value_detail['multi']['mul_c13_th']:$value_detail['multi']['mul_c13_eng'];
                                                    $mul_c13 = $mul_c13!=""?$mul_c13:$value_detail['multi']['mul_c13_jp'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_th']!=""?$value_detail['multi']['mul_c14_th']:$value_detail['multi']['mul_c14_eng'];
                                                    $mul_c14 = $mul_c14!=""?$mul_c14:$value_detail['multi']['mul_c14_jp'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_th']!=""?$value_detail['multi']['mul_c15_th']:$value_detail['multi']['mul_c15_eng'];
                                                    $mul_c15 = $mul_c15!=""?$mul_c15:$value_detail['multi']['mul_c15_jp'];
                                                }else if($lang=="english"){ 
                                                    $mul_c1 = $value_detail['multi']['mul_c1_eng']!=""?$value_detail['multi']['mul_c1_eng']:$value_detail['multi']['mul_c1_th'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_jp'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_eng']!=""?$value_detail['multi']['mul_c2_eng']:$value_detail['multi']['mul_c2_th'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_jp'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_eng']!=""?$value_detail['multi']['mul_c3_eng']:$value_detail['multi']['mul_c3_th'];
                                                    $mul_c3 = $mul_c3!=""?$mul_c3:$value_detail['multi']['mul_c3_jp'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_eng']!=""?$value_detail['multi']['mul_c4_eng']:$value_detail['multi']['mul_c4_th'];
                                                    $mul_c4 = $mul_c4!=""?$mul_c4:$value_detail['multi']['mul_c4_jp'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_eng']!=""?$value_detail['multi']['mul_c5_eng']:$value_detail['multi']['mul_c5_th'];
                                                    $mul_c5 = $mul_c5!=""?$mul_c5:$value_detail['multi']['mul_c5_jp'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_eng']!=""?$value_detail['multi']['mul_c6_eng']:$value_detail['multi']['mul_c6_th'];
                                                    $mul_c6 = $mul_c6!=""?$mul_c6:$value_detail['multi']['mul_c6_jp'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_eng']!=""?$value_detail['multi']['mul_c7_eng']:$value_detail['multi']['mul_c7_th'];
                                                    $mul_c7 = $mul_c7!=""?$mul_c7:$value_detail['multi']['mul_c7_jp'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_eng']!=""?$value_detail['multi']['mul_c8_eng']:$value_detail['multi']['mul_c8_th'];
                                                    $mul_c8 = $mul_c8!=""?$mul_c8:$value_detail['multi']['mul_c8_jp'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_eng']!=""?$value_detail['multi']['mul_c9_eng']:$value_detail['multi']['mul_c9_th'];
                                                    $mul_c9 = $mul_c9!=""?$mul_c9:$value_detail['multi']['mul_c9_jp'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_eng']!=""?$value_detail['multi']['mul_c10_eng']:$value_detail['multi']['mul_c10_th'];
                                                    $mul_c10 = $mul_c10!=""?$mul_c10:$value_detail['multi']['mul_c10_jp'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_eng']!=""?$value_detail['multi']['mul_c11_eng']:$value_detail['multi']['mul_c11_th'];
                                                    $mul_c11 = $mul_c11!=""?$mul_c11:$value_detail['multi']['mul_c11_jp'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_eng']!=""?$value_detail['multi']['mul_c12_eng']:$value_detail['multi']['mul_c12_th'];
                                                    $mul_c12 = $mul_c12!=""?$mul_c12:$value_detail['multi']['mul_c12_jp'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_eng']!=""?$value_detail['multi']['mul_c13_eng']:$value_detail['multi']['mul_c13_th'];
                                                    $mul_c13 = $mul_c13!=""?$mul_c13:$value_detail['multi']['mul_c13_jp'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_eng']!=""?$value_detail['multi']['mul_c14_eng']:$value_detail['multi']['mul_c14_th'];
                                                    $mul_c14 = $mul_c14!=""?$mul_c14:$value_detail['multi']['mul_c14_jp'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_eng']!=""?$value_detail['multi']['mul_c15_eng']:$value_detail['multi']['mul_c15_th'];
                                                    $mul_c15 = $mul_c15!=""?$mul_c15:$value_detail['multi']['mul_c15_jp'];
                                                }else{
                                                    $mul_c1 = $value_detail['multi']['mul_c1_jp']!=""?$value_detail['multi']['mul_c1_jp']:$value_detail['multi']['mul_c1_eng'];
                                                    $mul_c1 = $mul_c1!=""?$mul_c1:$value_detail['multi']['mul_c1_th'];
                                                    $mul_c2 = $value_detail['multi']['mul_c2_jp']!=""?$value_detail['multi']['mul_c2_jp']:$value_detail['multi']['mul_c2_eng'];
                                                    $mul_c2 = $mul_c2!=""?$mul_c2:$value_detail['multi']['mul_c2_th'];
                                                    $mul_c3 = $value_detail['multi']['mul_c3_jp']!=""?$value_detail['multi']['mul_c3_jp']:$value_detail['multi']['mul_c3_eng'];
                                                    $mul_c3 = $mul_c3!=""?$mul_c3:$value_detail['multi']['mul_c3_th'];
                                                    $mul_c4 = $value_detail['multi']['mul_c4_jp']!=""?$value_detail['multi']['mul_c4_jp']:$value_detail['multi']['mul_c4_eng'];
                                                    $mul_c4 = $mul_c4!=""?$mul_c4:$value_detail['multi']['mul_c4_th'];
                                                    $mul_c5 = $value_detail['multi']['mul_c5_jp']!=""?$value_detail['multi']['mul_c5_jp']:$value_detail['multi']['mul_c5_eng'];
                                                    $mul_c5 = $mul_c5!=""?$mul_c5:$value_detail['multi']['mul_c5_th'];
                                                    $mul_c6 = $value_detail['multi']['mul_c6_jp']!=""?$value_detail['multi']['mul_c6_jp']:$value_detail['multi']['mul_c6_eng'];
                                                    $mul_c6 = $mul_c6!=""?$mul_c6:$value_detail['multi']['mul_c6_th'];
                                                    $mul_c7 = $value_detail['multi']['mul_c7_jp']!=""?$value_detail['multi']['mul_c7_jp']:$value_detail['multi']['mul_c7_eng'];
                                                    $mul_c7 = $mul_c7!=""?$mul_c7:$value_detail['multi']['mul_c7_th'];
                                                    $mul_c8 = $value_detail['multi']['mul_c8_jp']!=""?$value_detail['multi']['mul_c8_jp']:$value_detail['multi']['mul_c8_eng'];
                                                    $mul_c8 = $mul_c8!=""?$mul_c8:$value_detail['multi']['mul_c8_th'];
                                                    $mul_c9 = $value_detail['multi']['mul_c9_jp']!=""?$value_detail['multi']['mul_c9_jp']:$value_detail['multi']['mul_c9_eng'];
                                                    $mul_c9 = $mul_c9!=""?$mul_c9:$value_detail['multi']['mul_c9_th'];
                                                    $mul_c10 = $value_detail['multi']['mul_c10_jp']!=""?$value_detail['multi']['mul_c10_jp']:$value_detail['multi']['mul_c10_eng'];
                                                    $mul_c10 = $mul_c10!=""?$mul_c10:$value_detail['multi']['mul_c10_th'];
                                                    $mul_c11 = $value_detail['multi']['mul_c11_jp']!=""?$value_detail['multi']['mul_c11_jp']:$value_detail['multi']['mul_c11_eng'];
                                                    $mul_c11 = $mul_c11!=""?$mul_c11:$value_detail['multi']['mul_c11_th'];
                                                    $mul_c12 = $value_detail['multi']['mul_c12_jp']!=""?$value_detail['multi']['mul_c12_jp']:$value_detail['multi']['mul_c12_eng'];
                                                    $mul_c12 = $mul_c12!=""?$mul_c12:$value_detail['multi']['mul_c12_th'];
                                                    $mul_c13 = $value_detail['multi']['mul_c13_jp']!=""?$value_detail['multi']['mul_c13_jp']:$value_detail['multi']['mul_c13_eng'];
                                                    $mul_c13 = $mul_c13!=""?$mul_c13:$value_detail['multi']['mul_c13_th'];
                                                    $mul_c14 = $value_detail['multi']['mul_c14_jp']!=""?$value_detail['multi']['mul_c14_jp']:$value_detail['multi']['mul_c14_eng'];
                                                    $mul_c14 = $mul_c14!=""?$mul_c14:$value_detail['multi']['mul_c14_th'];
                                                    $mul_c15 = $value_detail['multi']['mul_c15_jp']!=""?$value_detail['multi']['mul_c15_jp']:$value_detail['multi']['mul_c15_eng'];
                                                    $mul_c15 = $mul_c15!=""?$mul_c15:$value_detail['multi']['mul_c15_th'];
                                                }

                                                
                                            if($value_detail['svde_isMultichoice']!="1"){
                                            if(isset($value_detail['multi'])&&countArray($value_detail['multi'])>0){

                                                    if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" <?php if($value_check!=""&&in_array($mul_c1, $value_check)){echo "checked";} ?> type="radio" id="multi_choice_<?php echo $numloop; ?>1" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c1); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c1); ?>','')" for="multi_choice_<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(), removePath2($mul_c1)); ?></label><br>
                                         <?php      if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c2!=""){ 
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')" <?php if($value_check!=""&&in_array($mul_c2, $value_check)){echo "checked";} ?> name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>2" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c2); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c2); ?>','')" for="multi_choice_<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(), removePath2($mul_c2)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c3!=""){ 
                                                    $rechkimgtext = strpos($mul_c3, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input <?php if($value_check!=""&&in_array($mul_c3, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c3); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>3" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c3); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c3); ?>','')" for="multi_choice_<?php echo $numloop; ?>3"><?php echo str_replace('../', base_url(), removePath2($mul_c3)); ?></label><br>
                                          <?php    if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c4!=""){ 
                                                    $rechkimgtext = strpos($mul_c4, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input <?php if($value_check!=""&&in_array($mul_c4, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c4); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>4" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c4); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c4); ?>','')" for="multi_choice_<?php echo $numloop; ?>4"><?php echo str_replace('../', base_url(), removePath2($mul_c4)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c5!=""){ 
                                                    $rechkimgtext = strpos($mul_c5, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c5, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c5); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>5" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c5); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c5); ?>','')" for="multi_choice_<?php echo $numloop; ?>5"><?php echo str_replace('../', base_url(), removePath2($mul_c5)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c6!=""){ 
                                                    $rechkimgtext = strpos($mul_c6, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c6, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c6); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>6" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c6); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c6); ?>','')" for="multi_choice_<?php echo $numloop; ?>6"><?php echo str_replace('../', base_url(), removePath2($mul_c6)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c7!=""){ 
                                                    $rechkimgtext = strpos($mul_c7, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c7, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c7); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>7" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c7); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c7); ?>','')" for="multi_choice_<?php echo $numloop; ?>7"><?php echo str_replace('../', base_url(), removePath2($mul_c7)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c8!=""){ 
                                                    $rechkimgtext = strpos($mul_c8, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c8, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c8); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>8" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c8); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c8); ?>','')" for="multi_choice_<?php echo $numloop; ?>8"><?php echo str_replace('../', base_url(), removePath2($mul_c8)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c9!=""){ 
                                                    $rechkimgtext = strpos($mul_c9, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c9, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c9); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>9" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c9); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c9); ?>','')" for="multi_choice_<?php echo $numloop; ?>9"><?php echo str_replace('../', base_url(), removePath2($mul_c9)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c10!=""){ 
                                                    $rechkimgtext = strpos($mul_c10, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c10, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c10); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>10" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c10); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c10); ?>','')" for="multi_choice_<?php echo $numloop; ?>10"><?php echo str_replace('../', base_url(), removePath2($mul_c10)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c11!=""){ 
                                                    $rechkimgtext = strpos($mul_c11, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c11, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c11); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>11" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c11); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c11); ?>','')" for="multi_choice_<?php echo $numloop; ?>11"><?php echo str_replace('../', base_url(), removePath2($mul_c11)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c12!=""){ 
                                                    $rechkimgtext = strpos($mul_c12, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c12, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c12); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>12" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c12); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c12); ?>','')" for="multi_choice_<?php echo $numloop; ?>12"><?php echo str_replace('../', base_url(), removePath2($mul_c12)); ?></label><br>
                                          <?php     }
                                                    if($mul_c13!=""){ 
                                                    $rechkimgtext = strpos($mul_c13, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c13, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c13); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>13" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c13); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c13); ?>','')" for="multi_choice_<?php echo $numloop; ?>13"><?php echo str_replace('../', base_url(), removePath2($mul_c13)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c14!=""){ 
                                                    $rechkimgtext = strpos($mul_c14, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c14, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c14); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>14" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c14); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c14); ?>','')" for="multi_choice_<?php echo $numloop; ?>14"><?php echo str_replace('../', base_url(), removePath2($mul_c14)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c15!=""){ 
                                                    $rechkimgtext = strpos($mul_c15, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input  <?php if($value_check!=""&&in_array($mul_c15, $value_check)){echo "checked";} ?> onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c15); ?>','')" name="multi_choice_group<?php echo $numloop; ?>" type="radio" id="multi_choice_<?php echo $numloop; ?>15" class="with-gap radio-col-red" value="<?php echo htmlentities($mul_c15); ?>">
                                        <label onclick="onselectVal('<?php echo $numloop; ?>','<?php echo htmlentities($mul_c15); ?>','')" for="multi_choice_<?php echo $numloop; ?>15"><?php echo str_replace('../', base_url(), removePath2($mul_c15)); ?></label><br>
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
                                                <input name="multi_choice_group<?php echo $numloop; ?>" onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" type="radio" id="multi_choice_<?php echo $numloop; ?>16" <?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> class="with-gap radio-col-red">
                                                <label onclick="onselectVal('<?php echo $numloop; ?>','svde_specify','','svde_specify','svde_specify_txt<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>16"><?php echo $svde_specify_name; ?></label><br>
                                                <input type="text" onkeyup="chkbox_onkey('multi_choice_<?php echo $numloop; ?>16',this.value,'1','','<?php echo $numloop; ?>');" class="form-control" name="svde_specify_txt" id="svde_specify_txt<?php echo $numloop; ?>" value="<?php echo $value_note; ?>">
                                         <?php  }
                                                }else{
                                                if(isset($value_detail['multi'])&&countArray($value_detail['multi'])>0){
                                                    if($mul_c1!=""){ 
                                                    $rechkimgtext = strpos($mul_c1, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>1" <?php if($value_check!=""&&in_array($mul_c1, $value_check)){echo "checked";} ?> onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" value="<?php echo htmlentities($mul_c1); ?>" class="with-gap filled-in chk-col-red">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>1"><?php echo str_replace('../', base_url(), removePath2($mul_c1)); ?></label><br>
                                         <?php      if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c2!=""){ 
                                                    $rechkimgtext = strpos($mul_c2, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" name="multi_choice_group<?php echo $numloop; ?>[]" <?php if($value_check!=""&&in_array($mul_c2, $value_check)){echo "checked";} ?> type="checkbox" id="multi_choice_<?php echo $numloop; ?>2" value="<?php echo htmlentities($mul_c2); ?>" class="with-gap filled-in chk-col-red">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>2"><?php echo str_replace('../', base_url(), removePath2($mul_c2)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c3!=""){ 
                                                    $rechkimgtext = strpos($mul_c3, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" name="multi_choice_group<?php echo $numloop; ?>[]" <?php if($value_check!=""&&in_array($mul_c3, $value_check)){echo "checked";} ?> type="checkbox" id="multi_choice_<?php echo $numloop; ?>3" value="<?php echo htmlentities($mul_c3); ?>" class="with-gap filled-in chk-col-red">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>3"><?php echo str_replace('../', base_url(), removePath2($mul_c3)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c4!=""){ 
                                                    $rechkimgtext = strpos($mul_c4, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>4" <?php if($value_check!=""&&in_array($mul_c4, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c4); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>4"><?php echo str_replace('../', base_url(), removePath2($mul_c4)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c5!=""){ 
                                                    $rechkimgtext = strpos($mul_c5, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>5" <?php if($value_check!=""&&in_array($mul_c5, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c5); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c5); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>5"><?php echo str_replace('../', base_url(), removePath2($mul_c5)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c6!=""){ 
                                                    $rechkimgtext = strpos($mul_c6, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>6" <?php if($value_check!=""&&in_array($mul_c6, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c6); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c6); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>6"><?php echo str_replace('../', base_url(), removePath2($mul_c6)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c7!=""){ 
                                                    $rechkimgtext = strpos($mul_c7, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>7" <?php if($value_check!=""&&in_array($mul_c7, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c7); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c7); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>7"><?php echo str_replace('../', base_url(), removePath2($mul_c7)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c8!=""){ 
                                                    $rechkimgtext = strpos($mul_c8, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>8" <?php if($value_check!=""&&in_array($mul_c8, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c8); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c8); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>8"><?php echo str_replace('../', base_url(), removePath2($mul_c8)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c9!=""){ 
                                                    $rechkimgtext = strpos($mul_c9, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>9" <?php if($value_check!=""&&in_array($mul_c9, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c9); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c9); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>9"><?php echo str_replace('../', base_url(), removePath2($mul_c9)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c10!=""){ 
                                                    $rechkimgtext = strpos($mul_c10, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>10" <?php if($value_check!=""&&in_array($mul_c10, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c10); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c10); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>10"><?php echo str_replace('../', base_url(), removePath2($mul_c10)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c11!=""){ 
                                                    $rechkimgtext = strpos($mul_c11, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>11" <?php if($value_check!=""&&in_array($mul_c11, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c11); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c11); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>11"><?php echo str_replace('../', base_url(), removePath2($mul_c11)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c12!=""){ 
                                                    $rechkimgtext = strpos($mul_c12, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>12" <?php if($value_check!=""&&in_array($mul_c12, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c12); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c12); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>12"><?php echo str_replace('../', base_url(), removePath2($mul_c12)); ?></label><br>
                                          <?php    if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c13!=""){ 
                                                    $rechkimgtext = strpos($mul_c13, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>13" <?php if($value_check!=""&&in_array($mul_c13, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c13); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c13); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>13"><?php echo str_replace('../', base_url(), removePath2($mul_c13)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c14!=""){ 
                                                    $rechkimgtext = strpos($mul_c14, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>14" <?php if($value_check!=""&&in_array($mul_c14, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c14); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c14); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>14"><?php echo str_replace('../', base_url(), removePath2($mul_c14)); ?></label><br>
                                          <?php     if($rechkimgtext){ echo "</p>";}
                                                    }
                                                    if($mul_c15!=""){ 
                                                    $rechkimgtext = strpos($mul_c15, 'uploads/texteditor');
                                                    if($rechkimgtext){ echo "<p>";}?>
                                        <input onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>15" <?php if($value_check!=""&&in_array($mul_c15, $value_check)){echo "checked";} ?> class="with-gap filled-in chk-col-red" value="<?php echo htmlentities($mul_c15); ?>">
                                        <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','','<?php echo $numloop; ?>')"  onclick="$('#tc_answer').val('<?php echo htmlentities($mul_c15); ?>');$('#tc_note').val('');" for="multi_choice_<?php echo $numloop; ?>15"><?php echo str_replace('../', base_url(), removePath2($mul_c15)); ?></label><br>
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
                                                if($value_detail['svde_isSpecify']=="1"&&$svde_specify_name!=""){ ?>
                                                <input name="multi_choice_group<?php echo $numloop; ?>[]" type="checkbox" id="multi_choice_<?php echo $numloop; ?>16" onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','svde_specify_txt<?php echo $numloop; ?>','<?php echo $numloop; ?>')" <?php if($value_check!=""&&in_array('svde_specify', $value_check)){echo "checked";} ?> value="svde_specify" class="with-gap filled-in chk-col-red">
                                                <label onclick="oncheckboxVal('multi_choice_group<?php echo $numloop; ?>[]','svde_specify_txt<?php echo $numloop; ?>','<?php echo $numloop; ?>')" for="multi_choice_<?php echo $numloop; ?>16"><?php echo $svde_specify_name; ?></label><br>
                                                <input type="text" maxlength="255" onkeyup="chkbox_onkey('multi_choice_<?php echo $numloop; ?>16',this.value,'2','multi_choice_group<?php echo $numloop; ?>[]','<?php echo $numloop; ?>','chkbox');" class="form-control" name="svde_specify_txt" id="svde_specify_txt<?php echo $numloop; ?>" value="<?php echo $value_note; ?>">
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
                                                </script>
                                         <?php  }
                                                }
                                            }
                                            ?>
                                            <script type="text/javascript">
                                                function chkbox_onkey(id,value,type,name="",numval="",typechk=""){
                                                    if(value==""){
                                                        $('#'+id).prop('checked', false);
                                                        if(type=="1"){
                                                            $('#tc_answer'+numval).val('');
                                                            $('#tc_note'+numval).val('');
                                                        }
                                                        if(type=="2"){
                                                            $('#tc_note'+numval).val('');
                                                        }
                                                        $('#tc_require'+numval).val('0');
                                                    }else{
                                                        $('#'+id).prop('checked', true);
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
                                        <!-- <?php if($sv_main['tcmain_status']!=label('done')){ ?>
                                        <button type="button" onclick="onsave('<?php echo $numloop; ?>','<?php echo $value_detail['svde_id'];?>')" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                        <?php } ?> -->
                                        <?php if($numloop<countArray($sv_detail)){
                                                $numnext = 0;
                                                    $numnext = $numloop+1;
                                                    $nexttopage = "quiz_".$numnext;
                                        ?>
                                        <button type="button" class="btn btn-outline-secondary" onclick="activaTab('<?php echo $nexttopage; ?>')"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                                        <?php } ?>
                                       <!--  <?php if(countArray($sv_detail)==$numloop){ ?>
                                            <?php if($sv_main['tcmain_status']!=label('done')){ ?>
                                        <button type="button" onclick="onsend()" class="btn btn-outline-secondary"><?php echo label('preSend'); ?></button>
                                            <?php } ?>
                                        <?php } ?> -->
                                    </div>
                                </div>
                            </div>
                                  <?php
                                            $numloop++;
                                        }
                                    } ?>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

      <div id="myModal_process" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body" align="center">
                <img src="<?php echo REAL_PATH; ?>/assets/images/01-progress.gif" style="width: 50%">
                <br>
                <h3 style="color: black;"><?php echo label('please_wait'); ?></h3>
              </div>
            </div>
        </div>
      </div>

    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>

    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/js/bootstrap-datepicker-custom.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/bootstrap-datepicker/bootstrap-datepicker-custom/dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
    <script type="text/javascript">

      $( "footer" ).addClass( "mt-5" );
        $(document).on('click', '.publicsv', function(){
            var sv_id = $(this).attr("id");

            swal({
                title: '<?php echo label('isPublic_sv'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#16a085",   
                confirmButtonText: '<i class="mdi mdi-check"></i> <?php echo label('m_ok'); ?>',
                cancelButtonText: '<i class="mdi mdi-window-close"></i> <?php echo label('cancel'); ?>',
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/public_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                            location.reload();
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
        });
          function createButton(text,classs,style,id, cb) {
            return $(' <button class="'+classs+'" style="'+style+'" id="'+id+'">' + text + '</button>').on('click', cb);
          }

          $(document).on('click', '.btnrefresh', function(e) {
              e.preventDefault();
              location.reload();
          });
          $(document).on('click', '.approve', function(e){
            var sv_id = $(this).attr("id");

            $.ajax({
                  url:"<?=base_url()?>index.php/querydata/rechk_survey_period",
                  method:"POST",
                  data:{sv_id:sv_id},
                  dataType:"json",
                  success:function(data)
                  {
                    var title_val = '';
                    if(data.isApprove=="1"){
                        title_val = '<?php echo label('approve_is'); ?>';
                        var buttons = $('<div>')
                        .append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>','btn btn-flat btnapprove_psv','background-color:#1abc9c;',sv_id, function() {
                        })).append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject_psv','background-color:#DD6B55;',sv_id, function() {
                           swal.close();
                        })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                           swal.close();
                        }));
                    }else{
                        title_val = '<?php echo label('cantapprove_is'); ?>';
                        var buttons = $('<div>')
                        .append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject_psv','background-color:#DD6B55;',sv_id, function() {
                           swal.close();
                        })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                           swal.close();
                        }));
                    }
                    e.preventDefault();
                    swal({
                      title: title_val,
                      html: buttons,
                      type: "warning",
                      showConfirmButton: false,
                      showCancelButton: false
                    });
                  }
            });
          });

          $(document).on('click', '.btnapprove_psv', function(e) {
              e.preventDefault();
              var sv_id = $(this).attr("id");
              $("#myModal_process").modal('show');
              $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    xhr: function() {
                      //document.getElementById("progress_div").style.display = "";
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
                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                              }
                         }, false);
                         return xhr;
                    },
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      $('#myModal_process').modal('hide');
                        
                      $("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        }).then(function () {
                          location.reload();
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        }).then(function () {
                          location.reload();
                        })
                      }
                    }
                });
          });


          $(document).on('click', '.btnreject_psv', function(e) {
              e.preventDefault();
              var sv_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",     
                confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preNote'); ?>: ",
                inputValidator: (value) => {
                  if (!value) {
                    // หากไม่กรอกข้อมูล
                    return '<?php echo label("pls_enter_reason"); ?>';
                  }
                }
              }).then(function (isChk) {
                  if(isChk.value){
                    $("#myModal_process").modal({backdrop: false});
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/reject_publicsurvey",
                      method:"POST",
                      data:{sv_id:sv_id,sva_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {
                      }
                    });
                    location.reload();
                  }
               /* if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                  return false
                }else{
                swal("Nice!", "You wrote: " + inputValue, "success");
                }*/
              });
          });

         /*$(document).on('click', '.approve', function(){
            var sv_id = $(this).attr("id");
            swal({
                title: '<?php echo label('approve_is'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#16a085",   
                confirmButtonText: '<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
                cancelButtonText: '<i class="mdi mdi-window-close"></i> <?php echo label('cancel'); ?>',
                footer: '<button type="button" class="btn btn-info btn-block btnreject" style="background-color:#DD6B55;" id="'+sv_id+'"><i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?></button>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

          $(document).on('click', '.btnreject', function(e) {
              e.preventDefault();
              var sv_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preNote'); ?> :"
              }).then(function (isChk) {
                  if(isChk.value){
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/reject_publicsurvey",
                      method:"POST",
                      data:{sv_id:sv_id,sva_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {
                          
                      }
                    });
                  }
               /* if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                  return false
                }else{
                swal("Nice!", "You wrote: " + inputValue, "success");
                }*
              });
          })*/
        /*$("html, body").animate({ 
            scrollTop: 0
        }, "slow");*/
        //$('.collapse').collapse('show');
        function activaTab(tab){
          $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        }
        function onselectVal(numVal="",value1="",value2="",type="",id=""){
            if(type=="svde_specify"){
                $('#'+id).focus();
                $('#svde_specify_txt'+numVal).attr('readonly', false);
                $('#tc_require'+numVal).val('0');
                //console.log(type);
            }else{
                $('#svde_specify_txt'+numVal).val('');
                $('#svde_specify_txt'+numVal).attr('readonly', true);
                $('#tc_require'+numVal).val('1');
            }
            $('#tc_answer'+numVal).val(value1);
            $('#tc_note'+numVal).val(value2);
        }
        function onsave(numloop,svde_id){
            var tc_answer = $('#tc_answer'+parseInt(numloop)).val();
            var tc_note = $('#tc_note'+parseInt(numloop)).val();
            var tc_require = $('#tc_require'+parseInt(numloop)).val();
            if(tc_require=="1"){
                            swal(
                                '<?php echo label("com_msg_success"); ?>!',
                                '',
                                'success'
                            )
            }else{
                $('#svde_specify_txt'+numloop).focus();
            }
        }
        function onsend(){
                        swal(
                            '<?php echo label("save-complete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                        })
        }
    </script>
</body>

</html>
