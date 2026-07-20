
            <?php 

                                        function twodshuffle($array)
                                        {
                                            // Get array length
                                            $count = countArray($array);
                                            // Create a range of indicies
                                            $indi = range(0,$count-1);
                                            // Randomize indicies array
                                            shuffle($indi);
                                            // Initialize new array
                                            $newarray = array($count);
                                            // Holds current index
                                            $i = 0;
                                            // Shuffle multidimensional array
                                            foreach ($indi as $index)
                                            {
                                                $newarray[$i] = $array[$index];
                                                $i++;
                                            }
                                            return $newarray;
                                        }

                if($lang_select==""){
                  $lang_select = $lang;
                }
                if($lang_select=="thai"){
                  $createBy = "สร้างโดย";
                  $lesson_file = "เอกสารประกอบการเรียน";
                  $survey_txt = "แบบสำรวจ";
                  $pointtxt = "คะแนน";
                  $lessontxt = "บทเรียน";
                  $preNo = "ข้อที่";
                  $summarytxt = "คำอธิบาย";
                  $close = "ปิด";
                  $m_previous = "กลับ";
                  $saveR = "บันทึก";
                  $m_next = "ถัดไป";
                  $preSend = "ส่งคำตอบ";
                  $full_screentxt = "ขยายเต็มจอ";
                  $qiz_starttxt = "เริ่มทำแบบทดสอบ";
                  $download_file = "ดาวน์โหลดไฟล์";
                  $m_ok = "ตกลง";
                  $go_to_course = "เข้าสู่หลักสูตร";
                  $Les_video = "วิดีโอประกอบการสอน";
                  $hinttxt = "คำใบ้";
                  $sent_survey = "ส่งแบบสำรวจ";
                  $periodtxt = "ระยะเวลาที่เปิดหลักสูตร";
                  $Chooselangtxt = "กรุณาเลือกภาษา";
                  $thailandtxt = "ภาษาไทย";
                  $englishtxt = "ภาษาอังกฤษ";
                  $japantxt = "ภาษาญี่ปุ่น";
                  $com_msg_success = "บันทึกข้อมูลสำเร็จ";
                  $com_msg_error_save = "ไม่สามารถบันทึกข้อมูลได้";
                  $d_waitapprove = "อยู่ระหว่างรอการอนุมัติ";
                  $regis_sub = "หลักสูตรนี้เต็มแล้ว ชื่อของคุณจะถูกบันทึกในรายชื่อสำรอง";
                  $r_notregister = "ยังไม่ลงทะเบียน";
                  $wg_datanotfound = "ไม่พบข้อมูล";
                  $qiz_not_complete = "กรุณากดบันทึกคำตอบก่อนทำการยืนยันแบบทดสอบ";
                  $save_complete = "ส่งคำตอบสำเร็จ";
                  $noti_clicksave = "กรุณาตอบคำถาม";
                  $answer_wrong = "คุณตอบผิด กรุณาตอบอีกครั้ง";
                  $chk_answer_label = 'ตรวจคำตอบ';
                  $preExam_label = 'แบบทดสอบก่อนเรียน';
                  $finalExam_label = 'แบบทดสอบหลังเรียน';
                }else if($lang_select=="english"){
                  $createBy = "Created by";
                  $lesson_file = "Course Material";
                  $survey_txt = "Survey";
                  $pointtxt = "Score";
                  $lessontxt = "Lesson";
                  $preNo = "No.";
                  $summarytxt = "Description";
                  $close = "Close";
                  $m_previous = "Back";
                  $saveR = "Save";
                  $m_next = "Next";
                  $preSend = "Submit answer";
                  $full_screentxt = "Enter full screen";
                  $qiz_starttxt = "Start test";
                  $download_file = "Download file";
                  $m_ok = "OK";
                  $go_to_course = "Go to Course";
                  $Les_video = "Video";
                  $hinttxt = "Hint";
                  $sent_survey = "Submit Survey";
                  $periodtxt = "Course period";
                  $Chooselangtxt = "Please select language";
                  $thailandtxt = "Thai";
                  $englishtxt = "English";
                  $japantxt = "Japanese";
                  $com_msg_success = "Saved successfully";
                  $com_msg_error_save = "Cannot save information";
                  $d_waitapprove = "Pending approval";
                  $regis_sub = "This course is fully booked. Your name will be in the waiting list.";
                  $r_notregister = "Not registered yet";
                  $wg_datanotfound = "Information not found";
                  $qiz_not_complete = "Please save the answer before confirming test";
                  $save_complete = "Submitted successfully";
                  $noti_clicksave = "Please answer the questions.";
                  $answer_wrong = "Your answer is wrong, Please try again.";
                  $chk_answer_label = 'Review answer';
                  $preExam_label = 'Pre-test';
                  $finalExam_label = 'Post-test';
                }else{
                  $createBy = "作成者";
                  $lesson_file = "学習の資料";
                  $survey_txt = "査定";
                  $pointtxt = "ｽｺｱ";
                  $lessontxt = "ﾚｯｽﾝ";
                  $preNo = "番号";
                  $summarytxt = "説明";
                  $close = "閉";
                  $m_previous = "戻る";
                  $saveR = "保存";
                  $m_next = "次";
                  $preSend = "回答を提出";
                  $full_screentxt = "ﾌﾙｽｸﾘｰﾝ";
                  $qiz_starttxt = "ﾃｽﾄ開始";
                  $download_file = "ﾀﾞｳﾝﾛｰﾄﾞﾌｧｲﾙ";
                  $m_ok = "OK";
                  $go_to_course = "ｺｰｽへ";
                  $Les_video = "動画";
                  $hinttxt = "ﾋﾝﾄ";
                  $sent_survey = "査定を提出する";
                  $periodtxt = "ｺｰｽ期間";
                  $Chooselangtxt = "言語を選択して下さい";
                  $thailandtxt = "ﾀｲ語";
                  $englishtxt = "英語";
                  $japantxt = "日本語";
                  $com_msg_success = "保存完了";
                  $com_msg_error_save = "情報を保存できません。";
                  $d_waitapprove = "承認待ち中";
                  $regis_sub = "このｺｰｽは定員に達しています。待機ﾘｽﾄに入りました。";
                  $r_notregister = "未登録";
                  $wg_datanotfound = "情報がありません";
                  $qiz_not_complete = "ﾃｽﾄをｺﾝﾌｧｰﾑする前に回答を保存して下さい。";
                  $save_complete = "提出完成";
                  $noti_clicksave = "質問に回答してください。";
                  $answer_wrong = "回答が間違っています。再度回答して下さい。";
                  $chk_answer_label = '回答をﾚﾋﾞｭｰ';
                  $preExam_label = '事前テスト';
                  $finalExam_label = '事後テスト';
                }
                if(countArray($pretest_arr)>0){
                      foreach ($pretest_arr as $key_pretest => $value_pretest) {
                        if(countArray($value_pretest['question'])>0){

                  if($lang_select=="thai"){ 
                    $quiz_name = $value_pretest['quiz_name_th']!=""?$value_pretest['quiz_name_th']:$value_pretest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_jp'];
                    $quiz_info = $value_pretest['quiz_info_th']!=""?$value_pretest['quiz_info_th']:$value_pretest['quiz_info_eng'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_pretest['quiz_info_jp'];
                  }else if($lang_select=="english"){ 
                    $quiz_name = $value_pretest['quiz_name_eng']!=""?$value_pretest['quiz_name_eng']:$value_pretest['quiz_name_th'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_jp'];
                    $quiz_info = $value_pretest['quiz_info_eng']!=""?$value_pretest['quiz_info_eng']:$value_pretest['quiz_info_th'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_pretest['quiz_info_jp'];
                  }else{
                    $quiz_name = $value_pretest['quiz_name_jp']!=""?$value_pretest['quiz_name_jp']:$value_pretest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_pretest['quiz_name_th'];
                    $quiz_info = $value_pretest['quiz_info_jp']!=""?$value_pretest['quiz_info_jp']:$value_pretest['quiz_info_eng'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_pretest['quiz_info_th'];
                  }
            ?>
              <form method="post" id="pretest_form<?php echo $value_pretest['qiz_id']; ?>" autocomplete="off" name="pretest_form<?php echo $value_pretest['qiz_id']; ?>" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="container-fluid p-0 mb-3">
                <a href="#" class="btn btn-block <?php if($value_pretest['status_tc']=="3"){ ?>imat-red-bg btn-danger<?php } ?> waves-effect waves-light rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_<?php echo $value_pretest['qiz_id']; ?>" aria-expanded="false" <?php if($value_pretest['status_tc']!="3"){ ?>style="background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;"<?php } ?> aria-controls="collapseExample_<?php echo $value_pretest['qiz_id']; ?>">
                  <?php if($value_pretest['status_tc']=="3"){ ?><i class="fa fas fa-check mr-2"></i><?php } ?> <?php echo label('preExam').": ".$quiz_name; ?>
                  <?php if($value_pretest['status_tc']=="3"&&$value_pretest['quiz_grade']=="1"){ echo " (".intval($value_pretest['sum_score'])." ".$pointtxt.")";} ?>
                  <i class="fa fa-chevron-right float-right"></i>
                  <i class="fa fa-chevron-down float-right"></i>
                </a>
                <div class="collapse" id="collapseExample_<?php echo $value_pretest['qiz_id']; ?>">
                  <div class="hidden-sm-up">
                    <div class="list-group">
                      <?php if($quiz_info!=""){ ?>
                      <a href="#quiz_detail" id="" data-toggle="tab" role="tab" <?php if($value_pretest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> aria-selected="true" class="rounded-0 list-group-item active"><?php echo $summarytxt; ?></a>
                    <?php } ?>
                      <?php if(countArray($value_pretest['question'])>0){
                            $numloop = 1;
                                foreach ($value_pretest['question'] as $key_ques => $value_ques) {
                                  if($lang_select=="thai"){ 
                                    $ques_name = $value_ques['ques_name_th']!=""?$value_ques['ques_name_th']:$value_ques['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                  }else if($lang_select=="english"){ 
                                    $ques_name = $value_ques['ques_name_eng']!=""?$value_ques['ques_name_eng']:$value_ques['ques_name_th'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                  }else{
                                    $ques_name = $value_ques['ques_name_jp']!=""?$value_ques['ques_name_jp']:$value_ques['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_th'];
                                  }
                      ?>
                      <a href="#quiz_<?php echo $numloop;$numloop++; ?>" id="" <?php if($value_pretest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item"><?php echo strip_tags($ques_name); ?></a>
                      <?php
                                }
                            } ?>
                    </div>
                  </div>
                  <div class="card card-body">
                      <div class="vtabs customvtab row">
                          <!-- DESKTOP NAV -->
                          <ul class="nav nav-tabs vtabs-quiz tabs-vertical hidden-xs-down" role="tablist">
                          <?php $numpretest = 0;
                                $div_first = "";
                          
                                if($quiz_info!=""){ $numpretest++;?>
                            <li class="nav-item">
                              <a class="nav-link <?php if($numpretest==1){ ?>active show<?php } ?>" <?php if($value_pretest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
                                <span><?php echo $summarytxt; ?></span>
                              </a>
                            </li>
                          <?php }
                                  $numpretest++;
                          ?>
                          <?php if(countArray($value_pretest['question'])>0){
                                    $numloop = 1;
                                    foreach ($value_pretest['question'] as $key_ques => $value_ques) {
                                      if($numloop==1){
                                        $div_first = "quiz_".$value_pretest['qiz_id']."_".$numloop;
                                      }
                          ?>
                            <li class="nav-item">
                              <a class="nav-link <?php if($numpretest==1){ ?>active show<?php } ?>" <?php if($value_pretest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" href="#quiz_<?php echo $value_pretest['qiz_id']."_".$numloop; ?>" role="tab" aria-selected="false">
                                <span><?php echo $preNo." ".$numloop;$numloop++; ?></span>
                              </a>
                            </li>
                          <?php       $numpretest++;
                                    }
                                } ?>
                          </ul>
                          <input type="hidden" id="quiz_model_pretest_<?php echo $value_pretest['qiz_id']; ?>" name="quiz_model<?php echo $value_pretest['qiz_id']; ?>" value="<?php echo $value_pretest['quiz_model']; ?>">
                          <input type="hidden" id="quiz_ishint_pretest_<?php echo $value_pretest['qiz_id']; ?>" name="quiz_ishint<?php echo $value_pretest['qiz_id']; ?>" value="<?php echo $value_pretest['quiz_ishint']; ?>">
                          <input type="hidden" id="cosen_id_pretest_<?php echo $value_pretest['qiz_id']; ?>" name="cosen_id<?php echo $value_pretest['qiz_id']; ?>" value="<?php echo $cosen_id; ?>">
                          <input type="hidden" id="type_qiz<?php echo $value_ques['ques_id']; ?>" name="type_qiz<?php echo $value_pretest['qiz_id']; ?>" value="pretest">
                          <!-- Tab panes -->
                          <div class="tab-content pt-0 d-block">
                          <?php $numpretest = 0;
                                if($quiz_info!=""){ $numpretest++; ?>
                            <div class="tab-pane <?php if($numpretest==1){ ?>active show<?php } ?>" id="quiz_detail" role="tabpanel">
                              <h4><?php echo $summarytxt; ?></h4>
                              <p>
                                <?php echo $quiz_info; ?>
                              </p>
                              <hr>
                              <button type="button" class="btn btn-outline-secondary float-right" onclick="onclickfirstquestion('<?php echo $div_first; ?>')"><?php if($value_pretest['status_tc']!="3"){ echo $qiz_starttxt; }else{ echo $chk_answer_label; } ?></button>
                            </div>
                          <?php }
                                $numpretest++;  ?>

                          <?php if(countArray($value_pretest['question'])>0){
                                    $numloop = 1;$numpre = 0;$numnext = 2;
                                    foreach ($value_pretest['question'] as $key_ques => $value_ques) {

                                      if($lang_select=="thai"){ 
                                        $ques_name = $value_ques['ques_name_th']!=""?$value_ques['ques_name_th']:$value_ques['ques_name_eng'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                        $ques_info = $value_ques['ques_info_th']!=""?$value_ques['ques_info_th']:$value_ques['ques_info_eng'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_jp'];
                                        $ques_hintname = $value_ques['ques_hintname_th']!=""?$value_ques['ques_hintname_th']:$value_ques['ques_hintname_eng'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_jp'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_th']!=""?$value_ques['ques_hintdetail_th']:$value_ques['ques_hintdetail_eng'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_jp'];
                                      }else if($lang_select=="english"){ 
                                        $ques_name = $value_ques['ques_name_eng']!=""?$value_ques['ques_name_eng']:$value_ques['ques_name_th'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                        $ques_info = $value_ques['ques_info_eng']!=""?$value_ques['ques_info_eng']:$value_ques['ques_info_th'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_jp'];
                                        $ques_hintname = $value_ques['ques_hintname_eng']!=""?$value_ques['ques_hintname_eng']:$value_ques['ques_hintname_th'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_jp'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_eng']!=""?$value_ques['ques_hintdetail_eng']:$value_ques['ques_hintdetail_th'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_jp'];
                                      }else{
                                        $ques_name = $value_ques['ques_name_jp']!=""?$value_ques['ques_name_jp']:$value_ques['ques_name_eng'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_th'];
                                        $ques_info = $value_ques['ques_info_jp']!=""?$value_ques['ques_info_jp']:$value_ques['ques_info_eng'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_th'];
                                        $ques_hintname = $value_ques['ques_hintname_jp']!=""?$value_ques['ques_hintname_jp']:$value_ques['ques_hintname_eng'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_th'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_jp']!=""?$value_ques['ques_hintdetail_jp']:$value_ques['ques_hintdetail_eng'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_th'];
                                      }
                          ?>
                            <input type="hidden" id="tc_answer_pretest<?php echo $value_ques['ques_id']; ?>" name="tc_answer_pretest<?php echo $value_pretest['qiz_id']; ?>[]" value="<?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?>">
                            <input type="hidden" id="ques_id_pretest<?php echo $value_ques['ques_id']; ?>" name="ques_id_pretest<?php echo $value_pretest['qiz_id']; ?>[]" value="<?php echo $value_ques['ques_id']; ?>">

                            <input type="hidden" id="ques_hintname_pretest_<?php echo $value_ques['ques_id'];?>" name="ques_hintname<?php echo $value_ques['ques_id'];?>" value="<?php echo $ques_hintname; ?>">
                            <input type="hidden" id="ques_hintdetail_pretest_<?php echo $value_ques['ques_id'];?>" name="ques_hintdetail<?php echo $value_ques['ques_id'];?>" value="<?php echo $ques_hintdetail; ?>">
                            <input type="hidden" id="ques_hintimg_pretest_<?php echo $value_ques['ques_id'];?>" name="ques_hintimg<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_hintimg']; ?>">
                            <input type="hidden" id="ques_type_pretest_<?php echo $value_ques['ques_id'];?>" name="ques_type<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_type']; ?>">
                            <input type="hidden" id="ques_score_pretest_<?php echo $value_ques['ques_id'];?>" name="ques_score<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_score']; ?>">
                            <input type="hidden" id="mul_answer_pretest_<?php echo $value_ques['ques_id'];?>" name="mul_answer<?php echo $value_ques['ques_id'];?>" value="<?php echo isset($value_ques['multi']['mul_answer'])?$value_ques['multi']['mul_answer']:""; ?>">
                            <input type="hidden" id="tc_save_pretest_<?php echo $value_ques['ques_id']; ?>" name="tc_save_pretest_<?php echo $value_pretest['qiz_id']; ?>[]" value="<?php echo isset($value_ques['tc'])&&countArray($value_ques['tc'])>0&&$value_pretest['status_tc']=="3"?1:0; ?>">
                            <input type="hidden" id="amount_ques_pretest_<?php echo $value_pretest['qiz_id']; ?>" name="amount_ques<?php echo $value_pretest['qiz_id']; ?>" value="<?php echo countArray($value_pretest['question']); ?>">
                            <input type="hidden" id="qiztc_id_pretest_<?php echo $value_pretest['qiz_id']; ?>" name="qiztc_id<?php echo $value_pretest['qiz_id']; ?>" value="<?php echo $value_pretest['qiztc_id']; ?>">

                            <div class="tab-pane <?php if($numpretest==1){ ?>active show<?php } ?>" id="quiz_<?php echo $value_pretest['qiz_id']."_".$numloop; ?>" role="tabpanel">
                              <h3><?php echo strip_tags($ques_name); ?></h3>
                              <?php if($ques_info!=""){ ?><p><?php echo strip_tags($ques_info); ?></p> <?php } ?>
                              <?php if($value_ques['ques_type']=="sub"||$value_ques['ques_type']=="sa"){ ?>
                                <?php if($value_ques['ques_type']=="sub"){ ?>
                              <textarea class="form-control" id="txt_answer_pretest_<?php echo $value_ques['ques_id']; ?>" name="txt_answer_<?php echo $value_ques['ques_id']; ?>" onkeyup="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>',this.value)" maxlength="10000" rows="5"><?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?></textarea>
                                <?php }else{ ?>
                                  <input type="text" class="form-control" id="txt_answer_pretest_<?php echo $value_ques['ques_id']; ?>" onkeyup="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>',this.value)"  maxlength="255" name="txt_answer_<?php echo $value_ques['ques_id']; ?>" value="<?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?>">
                                <?php } ?>
                              <?php }else{
                                       $mul_answer = isset($value_ques['multi']['mul_answer'])?explode(',', $value_ques['multi']['mul_answer']):"";
                                      if(isset($value_ques['multi'])){

                                        if($lang_select=="thai"){ 
                                          $mul_c1 = $value_ques['multi']['mul_c1_th']!=""?$value_ques['multi']['mul_c1_th']:$value_ques['multi']['mul_c1_eng'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_jp'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_th']!=""?$value_ques['multi']['mul_c2_th']:$value_ques['multi']['mul_c2_eng'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_jp'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_th']!=""?$value_ques['multi']['mul_c3_th']:$value_ques['multi']['mul_c3_eng'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_jp'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_th']!=""?$value_ques['multi']['mul_c4_th']:$value_ques['multi']['mul_c4_eng'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_jp'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_th']!=""?$value_ques['multi']['mul_c5_th']:$value_ques['multi']['mul_c5_eng'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_jp'];
                                        }else if($lang_select=="english"){ 
                                          $mul_c1 = $value_ques['multi']['mul_c1_eng']!=""?$value_ques['multi']['mul_c1_eng']:$value_ques['multi']['mul_c1_th'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_jp'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_eng']!=""?$value_ques['multi']['mul_c2_eng']:$value_ques['multi']['mul_c2_th'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_jp'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_eng']!=""?$value_ques['multi']['mul_c3_eng']:$value_ques['multi']['mul_c3_th'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_jp'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_eng']!=""?$value_ques['multi']['mul_c4_eng']:$value_ques['multi']['mul_c4_th'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_jp'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_eng']!=""?$value_ques['multi']['mul_c5_eng']:$value_ques['multi']['mul_c5_th'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_jp'];
                                        }else{
                                          $mul_c1 = $value_ques['multi']['mul_c1_jp']!=""?$value_ques['multi']['mul_c1_jp']:$value_ques['multi']['mul_c1_eng'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_th'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_jp']!=""?$value_ques['multi']['mul_c2_jp']:$value_ques['multi']['mul_c2_eng'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_th'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_jp']!=""?$value_ques['multi']['mul_c3_jp']:$value_ques['multi']['mul_c3_eng'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_th'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_jp']!=""?$value_ques['multi']['mul_c4_jp']:$value_ques['multi']['mul_c4_eng'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_th'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_jp']!=""?$value_ques['multi']['mul_c5_jp']:$value_ques['multi']['mul_c5_eng'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_th'];
                                        }

                                        $arr_choice = array();
                                        if($mul_c1!=""){
                                          $arr_detail = array('num_choice'=>'1','name_choice'=>$mul_c1,'value_choice'=>'mul_c1');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c2!=""){
                                          $arr_detail = array('num_choice'=>'2','name_choice'=>$mul_c2,'value_choice'=>'mul_c2');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c3!=""){
                                          $arr_detail = array('num_choice'=>'3','name_choice'=>$mul_c3,'value_choice'=>'mul_c3');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c4!=""){
                                          $arr_detail = array('num_choice'=>'4','name_choice'=>$mul_c4,'value_choice'=>'mul_c4');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c5!=""){
                                          $arr_detail = array('num_choice'=>'5','name_choice'=>$mul_c5,'value_choice'=>'mul_c5');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($value_pretest['quiz_random_choice']=="1"){
                                            $arr_choice = twodshuffle($arr_choice);
                                        }
                                        $tc_answer = isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:"";
                                        for ($choicenum=0; $choicenum < countArray($arr_choice); $choicenum++) { 
                                          ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','<?php echo $arr_choice[$choicenum]['value_choice']; ?>')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?><?php echo  $arr_choice[$choicenum]['num_choice']; ?>" class="with-gap radio-col-red" value="<?php echo $arr_choice[$choicenum]['value_choice']; ?>" <?php if($tc_answer==$arr_choice[$choicenum]['value_choice']){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array($arr_choice[$choicenum]['value_choice'], $mul_answer)){ if($tc_answer==$arr_choice[$choicenum]['value_choice']){?>class="label label-light-success"<?php }}else{if($tc_answer==$arr_choice[$choicenum]['value_choice']){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','<?php echo $arr_choice[$choicenum]['value_choice']; ?>')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?><?php echo  $arr_choice[$choicenum]['num_choice']; ?>"><?php echo strip_tags($arr_choice[$choicenum]['name_choice'],"<label>"); ?></label>
                                <br>
                                          <?php
                                        }
/*
                                        $tc_answer = isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:"";
                                        if($mul_c1!=""){
                              ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c1')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>1" class="with-gap radio-col-red" value="mul_c1" <?php if($tc_answer=="mul_c1"){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array('mul_c1', $mul_answer)){ if($tc_answer=="mul_c1"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c1"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c1')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>1"><?php echo strip_tags($mul_c1,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c2!=""){
                              ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c2')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>2" class="with-gap radio-col-red" value="mul_c2" <?php if($tc_answer=="mul_c2"){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array('mul_c2', $mul_answer)){ if($tc_answer=="mul_c2"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c2"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c2')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>2"><?php echo strip_tags($mul_c2,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c3!=""){
                              ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c3')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>3" class="with-gap radio-col-red" value="mul_c3" <?php if($tc_answer=="mul_c3"){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array('mul_c3', $mul_answer)){ if($tc_answer=="mul_c3"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c3"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c3')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>3"><?php echo strip_tags($mul_c3,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c4!=""){
                              ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c4')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>4" class="with-gap radio-col-red" value="mul_c4" <?php if($tc_answer=="mul_c4"){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array('mul_c4', $mul_answer)){ if($tc_answer=="mul_c4"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c4"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c4')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>4"><?php echo strip_tags($mul_c4,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c5!=""){
                              ?>
                                <input name="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c5')" type="radio" id="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>5" class="with-gap radio-col-red" value="mul_c5" <?php if($tc_answer=="mul_c5"){echo "checked";} ?>>
                                <label <?php if($value_pretest['status_tc']=="3"){if($value_pretest['quiz_grade']=="1"&&in_array('mul_c5', $mul_answer)){ if($tc_answer=="mul_c5"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c5"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('pretest','<?php echo $value_ques['ques_id']; ?>','mul_c5')" for="multi_choice_group_pretest_<?php echo $value_ques['ques_id']; ?>5"><?php echo strip_tags($mul_c5,"<label>"); ?></label>
                              <?php     }*/
                                      }
                                    } ?>
                                <hr>
                                <div class="row">
                                  <div class="col-2">
                                  <?php if($numloop>1){ ?>
                                    <button type="button" onclick="click_previous('quiz_<?php echo $value_pretest['qiz_id']."_".$numpre; ?>','<?php echo $value_ques['ques_id'];?>','<?php echo $value_pretest['qiz_id']; ?>')" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i> <?php echo $m_previous; ?></button>
                                  <?php } ?>
                                  </div>
                                  <div class="col-10 text-right">
                                    <?php if($value_pretest['endstatus']=="0"){ ?>
                                    <button type="button" class="btn btn-outline-secondary" onclick="click_save('<?php echo $value_ques['ques_id'];?>','<?php echo $value_pretest['qiz_id']; ?>','pretest')"><i class="mdi mdi-content-save"></i> <?php echo $saveR; ?></button>
                                    <?php } ?>
                                    <?php if($numloop<countArray($value_pretest['question'])){ ?>
                                    <button type="button" onclick="click_next('quiz_<?php echo $value_pretest['qiz_id']."_".$numnext; ?>','<?php echo $value_ques['ques_id'];?>','<?php echo $value_pretest['qiz_id']; ?>','pretest')"  class="btn btn-outline-secondary"><?php echo $m_next; ?> <i class="mdi mdi-chevron-right"></i></button>
                                  <?php } ?>
                                    <?php if($numloop==countArray($value_pretest['question'])&&$value_pretest['endstatus']=="0"){ ?>
                                    <button type="button" id="<?php echo $value_pretest['qiz_id']; ?>" ques_id="<?php echo $value_ques['ques_id']; ?>" typeval="pretest" class="btn btn-outline-success btn_send"><i class="mdi mdi-send"></i> <?php echo $preSend; ?></button>
                                  <?php } ?>
                                  </div>
                                </div>
                            </div>
                          <?php       $numpretest++;$numloop++;$numpre++;$numnext++;
                                    }
                                } ?>
                          </div>
                      </div>
                  </div>

                </div>
              </div>
            </form>
            <?php       }
                      }
                  } ?>


            <?php if(countArray($lesson_arr)>0){ ?>
              <div class="container-fluid p-0 mb-3">
                <a href="#" id="lessonheader" class="btn btn-block <?php if($lesson_status==countArray($lesson_arr)){ ?>imat-red-bg btn-danger<?php } ?> waves-effect waves-light rounded-0 text-left  <?php if($loop_run==0){echo "disable";} ?>" type="button" data-toggle="collapse" data-target="#collapseExample_lesson" aria-expanded="false" <?php if($lesson_status!=countArray($lesson_arr)){ ?>style="background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;"<?php } ?> aria-controls="collapseExample_lesson">
                  <span id="txtstatus_lesson"><?php if($lesson_status==countArray($lesson_arr)){ ?><i class="fa fas fa-check mr-2"></i><?php }else if($lesson_status>0){ ?><i class="fa fas fa-hourglass-half mr-2"></i><?php } ?></span> <?php echo $lessontxt.":"; ?>
                  <i class="fa fa-chevron-right float-right"></i>
                  <i class="fa fa-chevron-down float-right"></i>
                </a>
                <div class="collapse" id="collapseExample_lesson">
                    <div class="hidden-sm-up">
                      <div class="list-group">
                        <?php $numlesson = 0;
                              foreach ($lesson_arr as $key_lesson => $value_lesson) { $numlesson++;?>
                        <a href="#lesson_<?php echo $value_lesson['les_id']; ?>" id="<?php echo $value_lesson['les_id']; ?>" <?php if($value_lesson['les_type']=="2"){  ?>onclick="play_scm('<?php echo $numlesson; ?>')"<?php } ?> data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick "><?php echo $value_lesson['les_name']; ?></a>
                        <?php } ?>
                      </div>
                    </div><!-- <?php if($numlesson==1){ ?>active<?php } ?> -->

                    <div class="card card-body">
                        <div class="vtabs customvtab row">
                          <!-- DESKTOP NAV -->
                          <ul class="nav nav-tabs tabs-vertical vtabs-lesson hidden-xs-down" role="tablist">
                          <?php $numlesson = 0;
                                foreach ($lesson_arr as $key_lesson => $value_lesson) { $numlesson++;?>
                            <li class="nav-item">
                               <!-- <?php if($numlesson==1){ ?>active show<?php } ?> -->
                              <a class="nav-link les_onclick" <?php if($value_lesson['les_type']=="2"){  ?>onclick="play_scm('<?php echo $numlesson; ?>')"<?php } ?> id="<?php echo $value_lesson['les_id']; ?>" data-toggle="tab" href="#lesson_<?php echo $value_lesson['les_id']; ?>" role="tab" aria-selected="true">
                                <span><?php echo $value_lesson['les_name']; ?></span> 
                              </a> 
                            </li>
                          <?php } ?>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content pt-0 d-block">
                          <?php $numlesson = 0;
                                foreach ($lesson_arr as $key_lesson => $value_lesson) { $numlesson++;?>
                                  <?php if($numlesson==1){ ?>
                              <script type="text/javascript">
                                
                                var les_id = $(this).attr("id");
                                 /* $.ajax({
                                    url:"<?=base_url()?>index.php/updatedatacourse/update_lesson/<?php echo $value_lesson['les_id']; ?>",
                                    method:'POST',
                                    dataType:"json",
                                    data:{cosen_id:'<?php echo $cosen_id; ?>'},
                                    success:function(data)
                                    {
                                    }
                                  });
*/
                                  
                                  $.ajax({
                                    url:"<?=base_url()?>index.php/updatedatacourse/rechk_status_lesson/",
                                    method:'POST',
                                    dataType:"json",
                                    data:{cos_id:'<?php echo $cos_id; ?>',cosen_id:'<?php echo $cosen_id; ?>'},
                                    success:function(data)
                                    {
                                      $("#lessonheader").css("background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;");
                                      $("#lessonheader").removeClass("imat-red-bg btn-danger");
                                      if(parseInt(data.status)==parseInt(data.numles)){
                                        $('#txtstatus_lesson').html('<i class="fa fas fa-check mr-2"></i>');
                                        $("#lessonheader").addClass("imat-red-bg btn-danger");
                                        $("#lessonheader").css("background: #fe0000;color: #ecf0f1;border-color: #fe0000;");
                                      }else if(parseInt(data.status)>0){
                                        $('#txtstatus_lesson').html('<i class="fa fas fa-hourglass-half mr-2"></i>');
                                      }else{
                                        $('#txtstatus_lesson').html('');
                                      }
                                    }
                                  });
                              </script>
                            <?php } ?> <!-- <?php if($numlesson==1){ ?>active show<?php } ?> -->
                            <div class="tab-pane" id="lesson_<?php echo $value_lesson['les_id']; ?>" role="tabpanel">
                          <?php 
                            if(isset($value_lesson['med_data'])&&countArray($value_lesson['med_data'])>0){ 
                          ?>
                              <div class="row">
                              <?php foreach ($value_lesson['med_data'] as $key_med => $value_med) { 
                                      if($value_med['thumbnail_med']!=""){
                                        $thumbnail_med = base_url().'/uploads/thumbnail/'.$value_med['thumbnail_med'];
                                      }else{
                                        $thumbnail_med = base_url().'/assets/images/background/user-info.jpg';
                                      }

                                      if($lang_select=="thai"){ 
                                        $med_name = $value_med['med_name_th']!=""?$value_med['med_name_th']:$value_med['med_name_eng'];
                                        $med_name = $med_name!=""?$med_name:$value_med['med_name_jp'];
                                      }else if($lang_select=="english"){ 
                                        $med_name = $value_med['med_name_eng']!=""?$value_med['med_name_eng']:$value_med['med_name_th'];
                                        $med_name = $med_name!=""?$med_name:$value_med['med_name_jp'];
                                      }else{
                                        $med_name = $value_med['med_name_jp']!=""?$value_med['med_name_jp']:$value_med['med_name_eng'];
                                        $med_name = $med_name!=""?$med_name:$value_med['med_name_th'];
                                      }
                              ?>
                                <div class="col-md-4">
                                    <br>
                                    <div onclick="onplayer_video_cos('<?php echo $value_med['type']; ?>','<?php echo $value_med['video']; ?>','<?php echo $value_med['id']; ?>')" class="onplayer_video" style="width: 100%;height: 150px;background-image: url('<?php echo $thumbnail_med;?>');background-position: center;background-size:cover;display: flex;justify-content:center;align-items: center;cursor: pointer;">
                                        <i style="font-size: 60px;" class="fas fa-play-circle playbutton" title="<?php echo $med_name; ?>"></i>
                                    </div><br>
                                </div>
                              <?php } ?>
                              </div><hr>
                      <?php }
                      ?>
                      <div class="row">
                        <div class="col-md-12"><div class="card card-body" style="word-break: break-all "><?php echo $value_lesson['les_info']!=""?$value_lesson['les_info']."<hr>":"";  ?></div></div>
                      </div>
                      <?php 
                            
                            if($value_lesson['les_type']=="2"){ 
                      ?>
                                      <div id="div_scorm_ddd" style="height: 700px">
                                        <iframe id="scorm_play_iframe<?php echo $numlesson; ?>" src="<?php if($numlesson==1){ echo base_url().'/scorm/loadScorm/'.$value_lesson['scm_data']['id'];} ?>" width="100%"  height="100%" style="width:100%;" frameborder="0" disabled allowfullscreen></iframe><br/>
                                        <input type="hidden" id="link_scm<?php echo $numlesson; ?>" name="link_scm" value="<?php echo base_url().'/scorm/loadScorm/'.$value_lesson['scm_data']['id']; ?>">
                                      </div>
                                      <div id="div_scorm_btn"><br>
                                              <button class="btn btn-warning col-md-12 full-Screen" onclick="openFullscreen('div_scorm_ddd')"><?php echo $full_screentxt ?></button><br>
                                      </div>
                                      <script type="text/javascript">
                                        window.frames[0].stop();
                                      </script>
                      <?php }else{ 
                              if(isset($value_lesson['doc_data'])&&countArray($value_lesson['doc_data'])>0){
                      ?>
                          <h4><?php echo ' '.$lesson_file; ?></h4>
                          <?php foreach ($value_lesson['doc_data'] as $key_doc => $value_doc) { 
                                      if($lang_select=="thai"){ 
                                        $name_file = $value_doc['name_file_th']!=""?$value_doc['name_file_th']:$value_doc['name_file_eng'];
                                        $name_file = $name_file!=""?$name_file:$value_doc['name_file_jp'];
                                      }else if($lang_select=="english"){ 
                                        $name_file = $value_doc['name_file_eng']!=""?$value_doc['name_file_eng']:$value_doc['name_file_th'];
                                        $name_file = $name_file!=""?$name_file:$value_doc['name_file_jp'];
                                      }else{
                                        $name_file = $value_doc['name_file_jp']!=""?$value_doc['name_file_jp']:$value_doc['name_file_eng'];
                                        $name_file = $name_file!=""?$name_file:$value_doc['name_file_th'];
                                      }
                          ?>
                          <a type="button" class="view_doccos" id="<?php echo $value_doc['id']; ?>" typevalue="lesson_file" path="<?php echo $value_doc['path_file']; ?>" href=""><i class="mdi mdi-download label-warning" style="color: #ffffff;"></i> <?php echo $name_file; ?></a><br>
                          <?php } ?>

                      <?php   }
                            } ?>
                            </div>
                          <?php } ?>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            <?php } ?>

            <?php if(countArray($posttest_arr)>0){
                      foreach ($posttest_arr as $key_posttest => $value_posttest) {
                        if(countArray($value_posttest['question'])>0){

                  if($lang_select=="thai"){ 
                    $quiz_name = $value_posttest['quiz_name_th']!=""?$value_posttest['quiz_name_th']:$value_posttest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_jp'];
                    $quiz_info = $value_posttest['quiz_info_th']!=""?$value_posttest['quiz_info_th']:$value_posttest['quiz_info_eng'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_posttest['quiz_info_jp'];
                  }else if($lang_select=="english"){ 
                    $quiz_name = $value_posttest['quiz_name_eng']!=""?$value_posttest['quiz_name_eng']:$value_posttest['quiz_name_th'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_jp'];
                    $quiz_info = $value_posttest['quiz_info_eng']!=""?$value_posttest['quiz_info_eng']:$value_posttest['quiz_info_th'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_posttest['quiz_info_jp'];
                  }else{
                    $quiz_name = $value_posttest['quiz_name_jp']!=""?$value_posttest['quiz_name_jp']:$value_posttest['quiz_name_eng'];
                    $quiz_name = $quiz_name!=""?$quiz_name:$value_posttest['quiz_name_th'];
                    $quiz_info = $value_posttest['quiz_info_jp']!=""?$value_posttest['quiz_info_jp']:$value_posttest['quiz_info_eng'];
                    $quiz_info = $quiz_info!=""?$quiz_info:$value_posttest['quiz_info_th'];
                  }
            ?>
              <form method="post" id="posttest_form<?php echo $value_posttest['qiz_id']; ?>" autocomplete="off" name="posttest_form<?php echo $value_posttest['qiz_id']; ?>" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="container-fluid p-0 mb-3">
                <a href="#" id="quizheader<?php echo $value_posttest['qiz_id']; ?>" class="btn btn-block <?php if($value_posttest['status_tc']=="3"){ ?>imat-red-bg btn-danger<?php } ?> waves-effect waves-light rounded-0 text-left  <?php if($loop_run==0){echo "disable";} ?>" type="button" data-toggle="collapse" data-target="#collapseExample_<?php echo $value_posttest['qiz_id']; ?>" aria-expanded="false" <?php if($value_posttest['status_tc']!="3"){ ?>style="background-color: #95a5a6;color: #ecf0f1;border-color: #95a5a6;"<?php } ?> aria-controls="collapseExample_<?php echo $value_posttest['qiz_id']; ?>" >
                  <span id="txtstatus_quiz<?php echo $value_posttest['qiz_id']; ?>"><?php if($value_posttest['status_tc']=="3"){ ?><i class="fa fas fa-check mr-2"></i><?php }else if($value_posttest['status_tc']=="2"){ ?><i class="fa fas fa-hourglass-half mr-2"></i><?php } ?></span>  <?php echo label('finalExam').": ".$quiz_name; ?>
                  <?php if($value_posttest['status_tc']=="3"&&$value_posttest['quiz_grade']=="1"){ echo " (".intval($value_posttest['sum_score'])." ".$pointtxt.")";} ?>
                  <i class="fa fa-chevron-right float-right"></i>
                  <i class="fa fa-chevron-down float-right"></i>
                </a>
                <div class="collapse" id="collapseExample_<?php echo $value_posttest['qiz_id']; ?>">
                  <div class="hidden-sm-up">
                    <div class="list-group">
                      <?php if($quiz_info!=""){ ?>
                      <a href="#quiz_detail" id="" data-toggle="tab" role="tab" <?php if($value_posttest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> aria-selected="true" class="rounded-0 list-group-item active"><?php echo $summarytxt; ?></a>
                    <?php } ?>
                      <?php if(countArray($value_posttest['question'])>0){
                            $numloop = 1;
                                foreach ($value_posttest['question'] as $key_ques => $value_ques) {
                                  if($lang_select=="thai"){ 
                                    $ques_name = $value_ques['ques_name_th']!=""?$value_ques['ques_name_th']:$value_ques['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                  }else if($lang_select=="english"){ 
                                    $ques_name = $value_ques['ques_name_eng']!=""?$value_ques['ques_name_eng']:$value_ques['ques_name_th'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                  }else{
                                    $ques_name = $value_ques['ques_name_jp']!=""?$value_ques['ques_name_jp']:$value_ques['ques_name_eng'];
                                    $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_th'];
                                  }
                      ?>
                      <a href="#quiz_<?php echo $numloop;$numloop++; ?>" id="" <?php if($value_posttest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item"><?php echo strip_tags($ques_name); ?></a>
                      <?php
                                }
                            } ?>
                    </div>
                  </div>
                  <div class="card card-body">
                      <div class="vtabs customvtab row">
                          <!-- DESKTOP NAV -->
                          <ul class="nav nav-tabs vtabs-quiz tabs-vertical hidden-xs-down" role="tablist">
                          <?php $numposttest = 0;
                                $div_first = "";
                                if($quiz_info!=""){ $numposttest++;?>
                            <li class="nav-item">
                              <a class="nav-link <?php if($numposttest==1){ ?>active show<?php } ?>" <?php if($value_posttest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
                                <span><?php echo $summarytxt; ?></span>
                              </a>
                            </li>
                          <?php }
                                  $numposttest++;
                          ?>
                          <?php if(countArray($value_posttest['question'])>0){
                                    $numloop = 1;
                                    foreach ($value_posttest['question'] as $key_ques => $value_ques) {
                                      if($numloop==1){
                                        $div_first = "quiz_".$value_posttest['qiz_id']."_".$numloop;
                                      }
                          ?>
                            <li class="nav-item">
                              <a class="nav-link <?php if($numposttest==1){ ?>active show<?php } ?>" <?php if($value_posttest['quiz_model']=="1"){ ?>style="pointer-events: none; "<?php } ?> data-toggle="tab" href="#quiz_<?php echo $value_posttest['qiz_id']."_".$numloop; ?>" role="tab" aria-selected="false">
                                <span><?php echo $preNo." ".$numloop;$numloop++; ?></span>
                              </a>
                            </li>
                          <?php       $numposttest++;
                                    }
                                } ?>
                          </ul>
                          <input type="hidden" id="quiz_model_posttest_<?php echo $value_posttest['qiz_id']; ?>" name="quiz_model<?php echo $value_posttest['qiz_id']; ?>" value="<?php echo $value_posttest['quiz_model']; ?>">
                          <input type="hidden" id="quiz_ishint_posttest_<?php echo $value_posttest['qiz_id']; ?>" name="quiz_ishint<?php echo $value_posttest['qiz_id']; ?>" value="<?php echo $value_posttest['quiz_ishint']; ?>">
                          <input type="hidden" id="cosen_id_posttest_<?php echo $value_posttest['qiz_id']; ?>" name="cosen_id<?php echo $value_posttest['qiz_id']; ?>" value="<?php echo $cosen_id; ?>">
                          <input type="hidden" id="type_qiz<?php echo $value_ques['ques_id']; ?>" name="type_qiz<?php echo $value_posttest['qiz_id']; ?>" value="posttest">

                          <!-- Tab panes -->
                          <div class="tab-content pt-0 d-block">
                          <?php $numposttest = 0;
                                if($quiz_info!=""){ $numposttest++; ?>
                            <div class="tab-pane <?php if($numposttest==1){ ?>active show<?php } ?>" id="quiz_detail" role="tabpanel">
                              <h4><?php echo $summarytxt; ?></h4>
                              <p>
                                <?php echo $quiz_info; ?>
                              </p>
                              <hr>
                              <button type="button" class="btn btn-outline-secondary float-right" onclick="onclickfirstquestion('<?php echo $div_first; ?>')"><?php if($value_posttest['status_tc']!="3"){ echo $qiz_starttxt; }else{ echo $chk_answer_label; } ?></button>
                            </div>
                          <?php }
                                $numposttest++;  ?>

                          <?php if(countArray($value_posttest['question'])>0){
                                    $numloop = 1;$numpre = 0;$numnext = 2;
                                    foreach ($value_posttest['question'] as $key_ques => $value_ques) {

                                      if($lang_select=="thai"){ 
                                        $ques_name = $value_ques['ques_name_th']!=""?$value_ques['ques_name_th']:$value_ques['ques_name_eng'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                        $ques_info = $value_ques['ques_info_th']!=""?$value_ques['ques_info_th']:$value_ques['ques_info_eng'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_jp'];
                                        $ques_hintname = $value_ques['ques_hintname_th']!=""?$value_ques['ques_hintname_th']:$value_ques['ques_hintname_eng'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_jp'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_th']!=""?$value_ques['ques_hintdetail_th']:$value_ques['ques_hintdetail_eng'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_jp'];
                                      }else if($lang_select=="english"){ 
                                        $ques_name = $value_ques['ques_name_eng']!=""?$value_ques['ques_name_eng']:$value_ques['ques_name_th'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_jp'];
                                        $ques_info = $value_ques['ques_info_eng']!=""?$value_ques['ques_info_eng']:$value_ques['ques_info_th'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_jp'];
                                        $ques_hintname = $value_ques['ques_hintname_eng']!=""?$value_ques['ques_hintname_eng']:$value_ques['ques_hintname_th'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_jp'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_eng']!=""?$value_ques['ques_hintdetail_eng']:$value_ques['ques_hintdetail_th'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_jp'];
                                      }else{
                                        $ques_name = $value_ques['ques_name_jp']!=""?$value_ques['ques_name_jp']:$value_ques['ques_name_eng'];
                                        $ques_name = $ques_name!=""?$ques_name:$value_ques['ques_name_th'];
                                        $ques_info = $value_ques['ques_info_jp']!=""?$value_ques['ques_info_jp']:$value_ques['ques_info_eng'];
                                        $ques_info = $ques_info!=""?$ques_info:$value_ques['ques_info_th'];
                                        $ques_hintname = $value_ques['ques_hintname_jp']!=""?$value_ques['ques_hintname_jp']:$value_ques['ques_hintname_eng'];
                                        $ques_hintname = $ques_hintname!=""?$ques_hintname:$value_ques['ques_hintname_th'];
                                        $ques_hintdetail = $value_ques['ques_hintdetail_jp']!=""?$value_ques['ques_hintdetail_jp']:$value_ques['ques_hintdetail_eng'];
                                        $ques_hintdetail = $ques_hintdetail!=""?$ques_hintdetail:$value_ques['ques_hintdetail_th'];
                                      }
                          ?>

                            <input type="hidden" id="tc_answer_posttest<?php echo $value_ques['ques_id']; ?>" name="tc_answer_posttest<?php echo $value_posttest['qiz_id']; ?>[]" value="<?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?>">
                            <input type="hidden" id="ques_id_posttest<?php echo $value_ques['ques_id']; ?>" name="ques_id_posttest<?php echo $value_posttest['qiz_id']; ?>[]" value="<?php echo $value_ques['ques_id']; ?>">

                            <input type="hidden" id="ques_hintname_posttest_<?php echo $value_ques['ques_id'];?>" name="ques_hintname<?php echo $value_ques['ques_id'];?>" value="<?php echo $ques_hintname; ?>">
                            <input type="hidden" id="ques_hintdetail_posttest_<?php echo $value_ques['ques_id'];?>" name="ques_hintdetail<?php echo $value_ques['ques_id'];?>" value="<?php echo $ques_hintdetail; ?>">
                            <input type="hidden" id="ques_hintimg_posttest_<?php echo $value_ques['ques_id'];?>" name="ques_hintimg<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_hintimg']; ?>">
                            <input type="hidden" id="ques_type_posttest_<?php echo $value_ques['ques_id'];?>" name="ques_type<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_type']; ?>">
                            <input type="hidden" id="ques_score_posttest_<?php echo $value_ques['ques_id'];?>" name="ques_score<?php echo $value_ques['ques_id'];?>" value="<?php echo $value_ques['ques_score']; ?>">
                            <input type="hidden" id="mul_answer_posttest_<?php echo $value_ques['ques_id'];?>" name="mul_answer<?php echo $value_ques['ques_id'];?>" value="<?php echo isset($value_ques['multi']['mul_answer'])?$value_ques['multi']['mul_answer']:""; ?>">
                            <input type="hidden" id="tc_save_posttest_<?php echo $value_ques['ques_id']; ?>" name="tc_save_posttest_<?php echo $value_posttest['qiz_id']; ?>[]" value="<?php echo isset($value_ques['tc'])&&countArray($value_ques['tc'])>0&&$value_posttest['status_tc']=="3"?1:0; ?>">
                            <input type="hidden" id="amount_ques_posttest_<?php echo $value_posttest['qiz_id']; ?>" name="amount_ques<?php echo $value_posttest['qiz_id']; ?>" value="<?php echo countArray($value_posttest['question']); ?>">
                            <input type="hidden" id="qiztc_id_posttest_<?php echo $value_posttest['qiz_id']; ?>" name="qiztc_id<?php echo $value_posttest['qiz_id']; ?>" value="<?php echo $value_posttest['qiztc_id']; ?>">

                            <div class="tab-pane <?php if($numposttest==1){ ?>active show<?php } ?>" id="quiz_<?php echo $value_posttest['qiz_id']."_".$numloop; ?>" role="tabpanel">
                              <h3><?php echo strip_tags($ques_name); ?></h3>
                              <?php if($ques_info!=""){ ?><p><?php echo strip_tags($ques_info); ?></p> <?php } ?>
                              <?php if($value_ques['ques_type']=="sub"||$value_ques['ques_type']=="sa"){ ?>
                                <?php if($value_ques['ques_type']=="sub"){ ?>
                              <textarea class="form-control" id="txt_answer_posttest_<?php echo $value_ques['ques_id']; ?>" name="txt_answer_<?php echo $value_ques['ques_id']; ?>" onkeyup="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>',this.value)" maxlength="10000" rows="5"><?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?></textarea>
                                <?php }else{ ?>
                                  <input type="text" class="form-control" id="txt_answer_posttest_<?php echo $value_ques['ques_id']; ?>" onkeyup="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>',this.value)"  maxlength="255" name="txt_answer_<?php echo $value_ques['ques_id']; ?>" value="<?php echo isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:""; ?>">
                                <?php } ?>
                              <?php }else{
                                      $mul_answer = isset($value_ques['multi']['mul_answer'])?explode(',', $value_ques['multi']['mul_answer']):"";
                                      if(isset($value_ques['multi'])){

                                        if($lang_select=="thai"){ 
                                          $mul_c1 = $value_ques['multi']['mul_c1_th']!=""?$value_ques['multi']['mul_c1_th']:$value_ques['multi']['mul_c1_eng'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_jp'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_th']!=""?$value_ques['multi']['mul_c2_th']:$value_ques['multi']['mul_c2_eng'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_jp'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_th']!=""?$value_ques['multi']['mul_c3_th']:$value_ques['multi']['mul_c3_eng'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_jp'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_th']!=""?$value_ques['multi']['mul_c4_th']:$value_ques['multi']['mul_c4_eng'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_jp'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_th']!=""?$value_ques['multi']['mul_c5_th']:$value_ques['multi']['mul_c5_eng'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_jp'];
                                        }else if($lang_select=="english"){ 
                                          $mul_c1 = $value_ques['multi']['mul_c1_eng']!=""?$value_ques['multi']['mul_c1_eng']:$value_ques['multi']['mul_c1_th'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_jp'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_eng']!=""?$value_ques['multi']['mul_c2_eng']:$value_ques['multi']['mul_c2_th'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_jp'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_eng']!=""?$value_ques['multi']['mul_c3_eng']:$value_ques['multi']['mul_c3_th'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_jp'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_eng']!=""?$value_ques['multi']['mul_c4_eng']:$value_ques['multi']['mul_c4_th'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_jp'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_eng']!=""?$value_ques['multi']['mul_c5_eng']:$value_ques['multi']['mul_c5_th'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_jp'];
                                        }else{
                                          $mul_c1 = $value_ques['multi']['mul_c1_jp']!=""?$value_ques['multi']['mul_c1_jp']:$value_ques['multi']['mul_c1_eng'];
                                          $mul_c1 = $mul_c1!=""?$mul_c1:$value_ques['multi']['mul_c1_th'];
                                          $mul_c2 = $value_ques['multi']['mul_c2_jp']!=""?$value_ques['multi']['mul_c2_jp']:$value_ques['multi']['mul_c2_eng'];
                                          $mul_c2 = $mul_c2!=""?$mul_c2:$value_ques['multi']['mul_c2_th'];
                                          $mul_c3 = $value_ques['multi']['mul_c3_jp']!=""?$value_ques['multi']['mul_c3_jp']:$value_ques['multi']['mul_c3_eng'];
                                          $mul_c3 = $mul_c3!=""?$mul_c3:$value_ques['multi']['mul_c3_th'];
                                          $mul_c4 = $value_ques['multi']['mul_c4_jp']!=""?$value_ques['multi']['mul_c4_jp']:$value_ques['multi']['mul_c4_eng'];
                                          $mul_c4 = $mul_c4!=""?$mul_c4:$value_ques['multi']['mul_c4_th'];
                                          $mul_c5 = $value_ques['multi']['mul_c5_jp']!=""?$value_ques['multi']['mul_c5_jp']:$value_ques['multi']['mul_c5_eng'];
                                          $mul_c5 = $mul_c5!=""?$mul_c5:$value_ques['multi']['mul_c5_th'];
                                        }

                                        $arr_choice = array();
                                        if($mul_c1!=""){
                                          $arr_detail = array('num_choice'=>'1','name_choice'=>$mul_c1,'value_choice'=>'mul_c1');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c2!=""){
                                          $arr_detail = array('num_choice'=>'2','name_choice'=>$mul_c2,'value_choice'=>'mul_c2');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c3!=""){
                                          $arr_detail = array('num_choice'=>'3','name_choice'=>$mul_c3,'value_choice'=>'mul_c3');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c4!=""){
                                          $arr_detail = array('num_choice'=>'4','name_choice'=>$mul_c4,'value_choice'=>'mul_c4');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($mul_c5!=""){
                                          $arr_detail = array('num_choice'=>'5','name_choice'=>$mul_c5,'value_choice'=>'mul_c5');
                                          array_push($arr_choice, $arr_detail);
                                        }
                                        if($value_posttest['quiz_random_choice']=="1"){
                                            $arr_choice = twodshuffle($arr_choice);
                                        }
                                        $tc_answer = isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:"";
                                        for ($choicenum=0; $choicenum < countArray($arr_choice); $choicenum++) { 
                                          ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','<?php echo $arr_choice[$choicenum]['value_choice']; ?>')" type="radio" id="multi_posttest_<?php echo $value_ques['ques_id']; ?><?php echo  $arr_choice[$choicenum]['num_choice']; ?>" class="with-gap radio-col-red" value="<?php echo $arr_choice[$choicenum]['value_choice']; ?>" <?php if($tc_answer==$arr_choice[$choicenum]['value_choice']){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array($arr_choice[$choicenum]['value_choice'], $mul_answer)){ if($tc_answer==$arr_choice[$choicenum]['value_choice']){?>class="label label-light-success"<?php }}else{if($tc_answer==$arr_choice[$choicenum]['value_choice']){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','<?php echo $arr_choice[$choicenum]['value_choice']; ?>')" for="multi_posttest_<?php echo $value_ques['ques_id']; ?><?php echo  $arr_choice[$choicenum]['num_choice']; ?>"><?php echo strip_tags($arr_choice[$choicenum]['name_choice'],"<label>"); ?></label>
                                <br>
                                          <?php
                                        }
                                       /* $tc_answer = isset($value_ques['tc']['tc_answer'])?$value_ques['tc']['tc_answer']:"";
                                        if($mul_c1!=""){
                              ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c1')" type="radio" id="multi_posttest_<?php echo $value_ques['ques_id']; ?>1" class="with-gap radio-col-red" value="mul_c1" <?php if($tc_answer=="mul_c1"){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array('mul_c1', $mul_answer)){ if($tc_answer=="mul_c1"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c1"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c1')" for="multi_posttest_<?php echo $value_ques['ques_id']; ?>1"><?php echo $mul_c1; ?></label>
                                <br>
                              <?php     }
                                        if($mul_c2!=""){
                              ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c2')" type="radio" id="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>2" class="with-gap radio-col-red" value="mul_c2" <?php if($tc_answer=="mul_c2"){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array('mul_c2', $mul_answer)){ if($tc_answer=="mul_c2"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c2"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c2')" for="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>2"><?php echo $mul_c2; ?></label>
                                <br>
                              <?php     }
                                        if($mul_c3!=""){
                              ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c3')" type="radio" id="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>3" class="with-gap radio-col-red" value="mul_c3" <?php if($tc_answer=="mul_c3"){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array('mul_c3', $mul_answer)){ if($tc_answer=="mul_c3"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c3"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c3')" for="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>3"><?php echo strip_tags($mul_c3,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c4!=""){
                              ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c4')" type="radio" id="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>4" class="with-gap radio-col-red" value="mul_c4" <?php if($tc_answer=="mul_c4"){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array('mul_c4', $mul_answer)){ if($tc_answer=="mul_c4"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c4"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c4')" for="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>4"><?php echo strip_tags($mul_c4,"<label>"); ?></label>
                                <br>
                              <?php     }
                                        if($mul_c5!=""){
                              ?>
                                <input name="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>" onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c5')" type="radio" id="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>5" class="with-gap radio-col-red" value="mul_c5" <?php if($tc_answer=="mul_c5"){echo "checked";} ?>>
                                <label <?php if($value_posttest['status_tc']=="3"){if($value_posttest['quiz_grade']=="1"&&in_array('mul_c5', $mul_answer)){ if($tc_answer=="mul_c5"){?>class="label label-light-success"<?php }}else{if($tc_answer=="mul_c5"){?>class="label label-light-danger"<?php }}} ?> onclick="onselectVal('posttest','<?php echo $value_ques['ques_id']; ?>','mul_c5')" for="multi_choice_group_posttest_<?php echo $value_ques['ques_id']; ?>5"><?php echo strip_tags($mul_c5,"<label>"); ?></label>
                              <?php     }*/
                                      }
                                    } ?>
                                <hr>
                                <div class="row">
                                  <div class="col-2">
                                  <?php if($numloop>1){ ?>
                                    <button type="button" onclick="click_previous('quiz_<?php echo $value_posttest['qiz_id']."_".$numpre; ?>','<?php echo $value_ques['ques_id'];?>','<?php echo $value_posttest['qiz_id']; ?>')" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i> <?php echo $m_previous; ?></button>
                                  <?php } ?>
                                  </div>
                                  <div class="col-10 text-right">
                                    <?php if($value_posttest['endstatus']=="0"){ ?>
                                    <button type="button" class="btn btn-outline-secondary" onclick="click_save('<?php echo $value_ques['ques_id'];?>','<?php echo $value_posttest['qiz_id']; ?>','posttest')"><i class="mdi mdi-content-save"></i> <?php echo $saveR; ?></button>
                                    <?php } ?>
                                    <?php if($numloop<countArray($value_posttest['question'])){ ?>
                                    <button type="button" onclick="click_next('quiz_<?php echo $value_posttest['qiz_id']."_".$numnext; ?>','<?php echo $value_ques['ques_id'];?>','<?php echo $value_posttest['qiz_id']; ?>','posttest')"  class="btn btn-outline-secondary"><?php echo $m_next; ?> <i class="mdi mdi-chevron-right"></i></button>
                                  <?php } ?>
                                    <?php if($numloop==countArray($value_posttest['question'])&&$value_posttest['endstatus']=="0"){ ?>
                                    <button type="button" id="<?php echo $value_posttest['qiz_id']; ?>" ques_id="<?php echo $value_ques['ques_id']; ?>" typeval="posttest" class="btn btn-outline-success btn_send"><i class="mdi mdi-send"></i> <?php echo $preSend; ?></button>
                                  <?php } ?>
                                  </div>
                                </div>
                            </div>
                          <?php       $numposttest++;$numloop++;$numpre++;$numnext++;
                                    }
                                } ?>
                          </div>
                      </div>
                  </div>

                </div>
              </div>
            </form>
            <?php       }
                      }
                  } ?>