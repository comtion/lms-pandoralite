<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
    <link href="<?php echo REAL_PATH; ?>/assets/css/pages/tab-page.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH; ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border">
		<div class="container-fluid">

                <div class="row">
                  <div class="col-md-12 col-lg-4 mb-0 card card-body">
                    <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/assets/images/mockup/400x300.jpg" onerror="this.src='<?php echo REAL_PATH;?>/images/logo.png';" alt="Card image cap">
                  </div>

                  <div class="col-md-12 col-lg-8 mb-0 card card-body">
                    <h4 class="text-truncate">COURSE_NAME</h4>
                    <div class="d-block position-relative">

                        <!-- FOR DESKTOP -->
                        <small class="text-muted text-truncate position-absolute col-md-12 col-lg-8 p-0" style="bottom: 0;"><?php echo label('createBy').' : '; ?>NAME_OF_CREATOR</small>

                        <!-- FOR MOBILE -->
                        <small class="text-muted text-truncate hidden-sm-up"><?php echo label('createBy').' : '; ?>NAME_OF_CREATOR</small>

                        <a type="button" href="#" class="col-md-12 col-lg-4 btn waves-effect waves-light btn-secondary float-right" title="<?php echo label('lesson_file'); ?>"><i class="mdi mdi-file-document"></i><?php echo ' '.label('lesson_file'); ?></a>
                    </div>
                    <hr>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                  </div>
                </div>

            
            <!-- Collapse Lesson -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_1" aria-expanded="false" aria-controls="collapseExample_1">
                <i class="fa fas fa-check mr-2"></i><?php echo 'COURSE_LESSON_NAME' ?>
                <i class="fa fa-chevron-right float-right"></i>
                <i class="fa fa-chevron-down float-right"></i>
              </a>
              <div class="collapse" id="collapseExample_1">
                <!-- MOBILE NAV -->
                <div class="hidden-sm-up">
                  <div class="list-group">
                    <a href="#lesson_1" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick active">Lesson 1 Video + Content</a>
                    <a href="#lesson_2" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">Lesson 2 Content</a>
                    <a href="#lesson_3" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">Lesson 3 Scorm</a>
                  </div>
                </div>

                <div class="card card-body">
                    <div class="vtabs customvtab row">
                      <!-- DESKTOP NAV -->
                      <ul class="nav nav-tabs tabs-vertical vtabs-lesson hidden-xs-down" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active show" data-toggle="tab" href="#lesson_1" role="tab" aria-selected="true">
                            <span>Lesson 1 Video + Content</span> 
                          </a> 
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#lesson_2" role="tab" aria-selected="false">
                            <span>Lesson 2 Content</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#lesson_3" role="tab" aria-selected="false">
                            <span>Lesson 3 Scorm</span>
                          </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content pt-0 d-block">
                        <!-- Lesson 1 Video + Content -->
                        <div class="tab-pane active show" id="lesson_1" role="tabpanel">                          
                          <div class="text-center">
                            <iframe style="width: 80%; height: auto;" src="https://www.youtube.com/embed/5RC9K6CcPGI" allowfullscreen></iframe>
                          </div>
                          <hr>
                          <p>
                            แค่ได้เห็นเธอ ตอนอยู่ด้วยกันกับเขา 
                            <br>ฉันก็รู้ดีว่าเรื่องเรา มันคงไม่มีหวัง 
                            <br>แต่เพราะรักเธอ ก็เลยยังไม่ไปไหน 
                            <br>ทำได้แค่มองเรื่อยไป และบอกตัวเองไว้ 
                          </p>
                            
                          <p>
                            *ถ้าเธอมีความสุขกับเขาคนนั้น ฉันก็ยินดีแม้ความสุขนั้นไม่มีฉัน 
                            <br>จะรักเธอแค่ไหน ก็ต้องยอมเข้าใจ และยอมรับความจริงให้ไหว *
                          </p>
                            
                          <p>
                            *เธอกับเขาเหมาะสมกันดีแล้ว 
                            <br>ถูกแล้วที่เธอเลือกคนที่ดีกว่าฉัน ฉันควรจำเอาไว้ 
                            <br>ว่าคนอย่างฉันเหมาะสมยืนตรงนี้ ได้เพียงแค่มองและคอยหวังดีเรื่อยไป 
                            <br>ฉันดีใจที่เธอเจอคนที่เหมาะสม 
                          </p>

                          <p>
                            อย่างเธอกับเขาก็คงต้องเรียกว่าเหมาะสม 
                            <br>ส่วนคนระดับฉันคงทำได้แค่มองและชื่นชม 
                            <br>ขืนอยู่กับฉันชีวิตของเธอคงล่มจม 
                            <br>ฉันไม่มีอย่างเขาทั้งเงินทอง หน้าตา และสถานะทางสังคม 
                            <br>พ่อแม่ของเธอคงบอกคู่เธอเหมือนกิ่งทองใบหยก 
                            <br>แต่ถ้าเป็นฉันคงเหมือนหนูตกถังข้าวสารดูสกปรก 
                            <br>ไปกินข้าวกับญาติเธอ ฉันคงเป็นได้แค่ตัวตลก 
                            <br>และเพื่อนของเธอคงชอบเขา แต่บอกว่าฉันนั้นสอบตก
                          </p>
                        </div>

                        <!-- Lesson 2 Content -->
                        <div class="tab-pane" id="lesson_2" role="tabpanel">
                          <h3>สถานการณ์ไวรัสโคโรน่าสายพันธุ์ใหม่ 2019</h3>
                          <h4>แพร่ระบาดหนักในประเทศจีน และอีกกว่า 26 ประเทศทั่วโลก รวมถึงประเทศไทยด้วย</h4>
                           <p>เฉิ่มแฟ้บเอาท์ บัตเตอร์มาร์เก็ตติ้งวอลนัทกรอบรูปภูมิทัศน์ โอเปร่าเลสเบี้ยนบราโซนี่ คอนเซปต์ตรวจสอบมอลล์พาวเวอร์ เคลียร์ เทวาธิราชวีไอพี ยาวี บริกรวิภัชภาคคาร์หลินจือเพรส ก๋ากั่นสุริยยาตร์นพมาศ โอยัวะแตงกวาฮันนีมูนคอรัปชั่น ราชานุญาต เมจิคยูวีรามาธิบดี เจไดเวอร์สแตนดาร์ดพุดดิ้งเบนโตะ ฟินิกซ์ ล็อตแฟลช แชมเปญตอกย้ำซูมดั๊มพ์ผลักดัน

                            ลอจิสติกส์โซนี่ห่วย เจได ซูม รุสโซ วอล์กบู๊ สหัชญาณ ซิมโฟนี่แพตเทิร์นโพลล์ คลับคาวบอยสะกอมแฟกซ์เทอร์โบ แรงผลักดีพาร์ตเมนท์ คำสาป เต๊ะบราฮิตป๊อก สี่แยกสโตร์ดีกรี พุทธศตวรรษอาข่าต่อยอดกระดี๊กระด๊าจุ๊ย วโรกาสเก๊ะแจ๊กพอตคณาญาติอัลตรา อุตสาหการ
                            
                            เคอร์ฟิวโบว์ลิ่ง รามเทพ วอลนัทแต๋วพาวเวอร์ซิ่งบัลลาสต์ ธัมโม คาวบอยเป่ายิงฉุบอิกัวนาบิล แคมปัสเกมส์อิสรชน เพลซ ท็อปบู๊ทหลวงปู่ไลฟ์เลิฟอุปัทวเหตุ ซีนปัจฉิมนิเทศสมาพันธ์ เซ็นทรัล วาฟเฟิลไคลแม็กซ์ ริกเตอร์สปอร์ตแชมเปญแรงผลักมหาอุปราชา ตาปรือ ชินบัญชรกระดี๊กระด๊าเพลซปัจฉิมนิเทศเดชานุภาพ ฮิตสต๊อค
                            
                            ครัวซองต์ทริป ไทเฮาบ๊วย แซ็กนพมาศบลอนด์ สตูดิโอฟินิกซ์โรแมนติคก๋ากั่น ซาร์เด้อฟรังก์พุทธศตวรรษดีเจ อุตสาหการเคลียร์ซูมแฟร์โอยัวะ คอมเมนต์มอนสเตอร์ ตาปรือนินจามอลล์ไรเฟิล โดมิโนกษัตริยาธิราช มิลค์คอร์รัปชั่นเจ๊าะแจ๊ะเพรียวบางหล่อฮังก้วยชีส โปสเตอร์ตัวเองภควัทคีตา แกรนด์อิกัวนาสจ๊วตออทิสติก ยูวีดิสเครดิตเฮีย แฟกซ์ ลีกหลวงปู่
                            
                            เวิร์กแอปเปิ้ลฟลุทป๊อก ป๊อกโพลล์ อิออนบัตเตอร์ เวิร์กมาร์เก็ต โกเต็กซ์บาร์บีคิวโปรโมทพาสตาแก๊สโซฮอล์ ซีรีส์โกะเธคซีอีโอรีวิว คูลเลอร์ ช็อคเกสต์เฮาส์แต๋วนู้ดสัมนา ฮัมไวอากร้าสตรอเบอรี อีแต๋นสถาปัตย์ ครัวซองสี่แยกดราม่า ปักขคณนาโฮลวีตล้มเหลวโดนัท คอมเมนต์โมจิเอาท์ดอร์ซานตาคลอสพรีเมียร์ ซีอีโอปัจเจกชนวิดีโอใช้งาน รีเสิร์ชเฟรชชี่ เป่ายิ้งฉุบพาสเจอร์ไรส์ซาร์คอร์สฮิ</p>

                            <img class="img-fluid" src="https://bangkokbiznews.sgp1.cdn.digitaloceanspaces.com/image/kt/media/image/fileupload1/source/158150274950.jpg?1581563707502" alt="">

                        </div>

                        <!-- Lesson 3 Scorm -->
                        <div class="tab-pane" id="lesson_3" role="tabpanel">
                          <h3>Lesson 3 Scorm</h3>
                          <h4>you can use it with the small code</h4>
                          <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>


            <!-- Collapse Quiz -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="collapse" data-target="#collapseExample_2" aria-expanded="false" aria-controls="collapseExample_2">
                <i class="fa fas fa-check mr-2"></i><?php echo 'PRE_TEST_NAME / POST_TEST_NAME' ?>
                <i class="fa fa-chevron-right float-right"></i>
                <i class="fa fa-chevron-down float-right"></i>
              </a>
              <div class="collapse" id="collapseExample_2">
                <!-- MOBILE NAV -->
                <div class="hidden-sm-up">
                  <div class="list-group">
                    <a href="#quiz_detail" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick active"><?php echo label('summary'); ?></a>
                    <a href="#quiz_1" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">ข้อที่ 1</a>
                    <a href="#quiz_2" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick">ข้อที่ 2</a>
                    <a href="#quiz_3" id="" data-toggle="tab" role="tab" aria-selected="true" class="rounded-0 list-group-item les_onclick bg-checked">ข้อที่ 3</a>
                  </div>
                </div>

                <div class="card card-body">
                    <div class="vtabs customvtab row">
                      <!-- DESKTOP NAV -->
                      <ul class="nav nav-tabs vtabs-quiz tabs-vertical hidden-xs-down" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active show" data-toggle="tab" href="#quiz_detail" role="tab" aria-selected="true">
                            <span><?php echo label('summary'); ?></span> 
                          </a> 
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_1" role="tab" aria-selected="false">
                            <span>ข้อที่ 1</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#quiz_2" role="tab" aria-selected="false">
                            <span>ข้อที่ 2</span>
                          </a>
                        </li>
                        <li class="nav-item bg-checked">
                          <a class="nav-link" data-toggle="tab" href="#quiz_3" role="tab" aria-selected="false">
                            <span>ข้อที่ 3</span>
                          </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content pt-0 d-block">
                        <!-- Quiz Detail -->
                        <div class="tab-pane active show" id="quiz_detail" role="tabpanel">
                          <h4><?php echo label('summary'); ?></h4>
                          <p>
                            แค่ได้เห็นเธอ ตอนอยู่ด้วยกันกับเขา 
                            <br>ฉันก็รู้ดีว่าเรื่องเรา มันคงไม่มีหวัง 
                            <br>แต่เพราะรักเธอ ก็เลยยังไม่ไปไหน 
                            <br>ทำได้แค่มองเรื่อยไป และบอกตัวเองไว้ 
                          </p>
                            
                          <p>
                            *ถ้าเธอมีความสุขกับเขาคนนั้น ฉันก็ยินดีแม้ความสุขนั้นไม่มีฉัน 
                            <br>จะรักเธอแค่ไหน ก็ต้องยอมเข้าใจ และยอมรับความจริงให้ไหว *
                          </p>
                          <hr>
                          <button type="button" class="btn btn-outline-secondary float-right"><?php echo label('qiz_start'); ?></button>
                        </div>

                        <!-- Textarea -->
                        <div class="tab-pane" id="quiz_1" role="tabpanel">
                          <h3>1. textarea</h3>
                          <textarea class="form-control" rows="5"></textarea>
                          <hr>
                          <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10 text-right">
                              <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                              <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                          </div>
                        </div>

                        <!-- Two Choice -->
                        <div class="tab-pane" id="quiz_2" role="tabpanel">
                          <h3>2. two choice</h3>
                            <input name="tow_choice_group" type="radio" id="tow_choice_1" class="with-gap radio-col-red">
                            <label for="tow_choice_1">Red</label>
                            <br>
                            <input name="tow_choice_group" type="radio" id="tow_choice_2" class="with-gap radio-col-red">
                            <label for="tow_choice_2">Pink</label>
                            <hr>
                            <div class="row">
                              <div class="col-2">                                
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                              </div>
                              <div class="col-10 text-right">
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                              </div>
                            </div>

                        </div>

                        <!-- Multi Choice -->
                        <div class="tab-pane" id="quiz_3" role="tabpanel">
                          <h3>3. multi choice (limit 5)</h3>
                            <input name="multi_choice_group" type="radio" id="multi_choice_1" class="with-gap radio-col-red">
                            <label for="multi_choice_1">Choice 1</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_2" class="with-gap radio-col-red">
                            <label for="multi_choice_2">Choice 2</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_3" class="with-gap radio-col-red">
                            <label for="multi_choice_3">Choice 3</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_4" class="with-gap radio-col-red">
                            <label for="multi_choice_4">Choice 4</label>
                            <br>
                            <input name="multi_choice_group" type="radio" id="multi_choice_5" class="with-gap radio-col-red">
                            <label for="multi_choice_5">Choice 5</label>
                            <hr>
                            <div class="row">
                              <div class="col-2">                                
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-chevron-left"></i><?php echo ' '.label('m_previous'); ?></button>
                              </div>
                              <div class="col-10 text-right">
                                <button type="button" class="btn btn-outline-secondary"><i class="mdi mdi-content-save"></i><?php echo ' '.label('saveR'); ?></button>
                                <button type="button" class="btn btn-outline-secondary"><?php echo ' '.label('m_next'); ?> <i class="mdi mdi-chevron-right"></i></button>
                              </div>
                            </div>

                        </div>

                      </div>
                    </div>
                </div>
              </div>
            </div>

            <!-- Open Survey Button -->
            <div class="container-fluid p-0 mb-3">
              <a href="#" class="btn btn-block imat-red-bg waves-effect waves-light btn-danger rounded-0 text-left" type="button" data-toggle="modal" data-target="#surveyModal" >
                <i class="fa fas fa-check mr-2"></i><?php echo 'SURVEY_NAME'; ?>
              </a>
            </div>


        </div>

  <!-- SURVEY MODAL -->
  <div id="surveyModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="myLargeModalLabel"><?php echo label('survey').' : '.'SURVEY_NAME' ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="align-middle"><p class="text-left"><?php echo label('question'); ?></p></th>
                                <th><p class="text-center"><?php echo label('choice_5'); ?><br>5</p></th>
                                <th><p class="text-center"><?php echo label('choice_4'); ?><br>4</p></th>
                                <th><p class="text-center"><?php echo label('choice_3'); ?><br>3</p></th>
                                <th><p class="text-center"><?php echo label('choice_2'); ?><br>2</p></th>
                                <th><p class="text-center"><?php echo label('choice_1'); ?><br>1</p></th>
                                <th class="align-middle"><p class="text-left"><?php echo label('Suggestion'); ?></p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td><h6 class="m-auto">HEADER_1</h6></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_1</td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_1" class="with-gap radio-col-red"><label for="radio_1"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_2" class="with-gap radio-col-red"><label for="radio_2"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_3" class="with-gap radio-col-red"><label for="radio_3"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_4" class="with-gap radio-col-red"><label for="radio_4"></label></td>
                                <td class="text-center"><input name="group_1" type="radio" id="radio_5" class="with-gap radio-col-red"><label for="radio_5"></label></td>
                                <td><textarea class="form-control" rows="3" style="min-width: 200px;"></textarea></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_2</td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_6" class="with-gap radio-col-red"><label for="radio_6"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_7" class="with-gap radio-col-red"><label for="radio_7"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_8" class="with-gap radio-col-red"><label for="radio_8"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_9" class="with-gap radio-col-red"><label for="radio_9"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_2" type="radio" id="radio_10" class="with-gap radio-col-red"><label for="radio_10"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_3</td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_11" class="with-gap radio-col-red"><label for="radio_11"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_12" class="with-gap radio-col-red"><label for="radio_12"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_13" class="with-gap radio-col-red"><label for="radio_13"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_14" class="with-gap radio-col-red"><label for="radio_14"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_3" type="radio" id="radio_15" class="with-gap radio-col-red"><label for="radio_15"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                              <td><h6 class="m-auto">HEADER_2</h6></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_4</td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_16" class="with-gap radio-col-red"><label for="radio_16"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_17" class="with-gap radio-col-red"><label for="radio_17"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_18" class="with-gap radio-col-red"><label for="radio_18"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_19" class="with-gap radio-col-red"><label for="radio_19"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_4" type="radio" id="radio_20" class="with-gap radio-col-red"><label for="radio_20"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>questionnnnnnnnnnnnnnnnnnnnnnnnnn_5</td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_21" class="with-gap radio-col-red"><label for="radio_21"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_22" class="with-gap radio-col-red"><label for="radio_22"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_23" class="with-gap radio-col-red"><label for="radio_23"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_24" class="with-gap radio-col-red"><label for="radio_24"></label>
                                </td>
                                <td class="text-center">
                                  <input name="group_5" type="radio" id="radio_25" class="with-gap radio-col-red"><label for="radio_25"></label>
                                </td>
                                <td>
                                  <textarea class="form-control" rows="3" style="min-width: 200px;"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php echo label('Suggestion_another'); ?>
                    <textarea class="form-control" rows="5"></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn waves-effect waves-light btn-outline-success" data-dismiss="modal"><?php echo label('contasend'); ?></button>
                  <button type="button" class="btn waves-effect waves-light btn-outline-danger" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.SURVEY MODAL -->
</body>    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">

      <?php if(countArray($cos_data)==0){ ?>
        swal({
            title: '<?php echo label("wg_datanotfound"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function () {
          window.open("<?php echo REAL_PATH; ?>/dashboard", "_self");
        });
      <?php }else{ ?>
      $(window).on('load',function(){
          $('#select_lang_modal').modal('show');
      });

      $('#select_lang_modal').modal({backdrop: 'static', keyboard: false});
      <?php } ?>
    </script>
</body>

</html>