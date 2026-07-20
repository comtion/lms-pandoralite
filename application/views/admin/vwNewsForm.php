<?php
$this->load->view('admin/inc/vwHeader');
?>
<!--
Load Page Specific CSS and JS here
Author : Abhishek R. Kaushik
Downloaded from http://devzone.co.in
-->
<!--  PAge Code Starts here -->

    <!-- Page Specific Plugins -->
    <!--<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>-->
    <!-- Page Specific CSS
    <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
    <script src="<?php //echo HTTP_JS_PATH_ADMIN; ?>morris/chart-data-morris.js"></script>-->

<div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <!-- <h1>Home <small>Page editor</small></h1> -->
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard </li>
            </ol>
          </div>
        </div><!-- /.row -->



        <div class="row">
          <div class="col-lg-12">
            <div class="panel">
              <!-- <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Home page details</h3>
              </div> -->
              <div class="panel-body" id="container">
                <!--Pestaña 1 activa por defecto-->
                <?php foreach( $langs as $lang ){  ?>
                  <input id="tab<?php echo $lang['id']; ?>" type="radio" name="tabs" <?php echo ($lang['text'] == $lang_tab ) ? 'checked' : ''; ?> >
                  <label class="lang-tab" for="tab<?php echo $lang['id']; ?>"><?php echo getTextLang( $lang['text'] ); ?></label>
                <?php }  ?>

                    <?php foreach( $langs as $lang ){
                      $lang_set = $lang['text'];
                      if( isset($news[$lang['text']]) ){
                        $id = $news[$lang['text']]['cpid'];
                        $cp_categorys = explode( ',' , $news[$lang['text']]['cp_category'] );
                        $cp_by = $news[$lang['text']]['cp_by'];
                        $cp_titlehead = $news[$lang['text']]['cp_titlehead'];
                        $cp_titletext = $news[$lang['text']]['cp_titletext'];
                        $cp_titleimg = $news[$lang['text']]['cp_titleimg'];
                        $cp_talkby = $news[$lang['text']]['cp_talkby'];
                        $cp_content = $news[$lang['text']]['cp_content'];
                        $cp_postby = $news[$lang['text']]['cp_postby'];
                        $cp_postview = $news[$lang['text']]['cp_postview'];

                        $cp_tag = $news[$lang['text']]['cp_tag'];
                        $cp_video = $news[$lang['text']]['cp_video'];
                        $cp_slidetop = $news[$lang['text']]['cp_slidetop'];
                        $cp_onoff = $news[$lang['text']]['cp_onoff'];

                        $cp_sticky = $news[$lang['text']]['cp_sticky'];
                        $cp_editby = $news[$lang['text']]['cp_editby'];
                        $cp_edittime = $news[$lang['text']]['cp_edittime']; // edit date
                        $cp_lastupdate = $news[$lang['text']]['cp_lastupdate']; // post date

                      }else{

                        $id = '';
                        $cp_categorys = array();
                        $cp_by = '';
                        $cp_titlehead = '';
                        $cp_titletext = '';
                        $cp_titleimg = '';
                        $cp_talkby = '';
                        $cp_content = '';
                        $cp_postby = '';
                        $cp_postview = '';

                        $cp_tag = '';
                        $cp_video = '';
                        $cp_slidetop = '';
                        $cp_onoff = '';

                        $cp_sticky = '';
                        $cp_editby = '';
                        $cp_edittime = '';
                        $cp_lastupdate = '';
                      }

                    ?>

                      <section id="content<?php echo $lang['id']; ?>">
                        <form role="form" class="form-edit" action="<?php echo base_url().'admin/news/saveForm'; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"  />
                        <input type="hidden" name="page" value="<?php echo $page; ?>"  />
                        <input type="hidden" name="lang" value="<?php echo $lang_set; ?>"  />
                        <input type="hidden" name="code" value="<?php echo $code; ?>"  />
                        <input type="hidden" name="page" value="<?php echo $page; ?>"  />
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title"></i> เพิ่มข้อมูลใหม่ สำหรับ<?php echo getTextLang( $lang['text'] ); ?> </h3>
                              </div>
                              <div class="panel-body">
                                <?php if( $page == "video"){ ?>
                                  <div class="inside-panel">
                                    <label >ขั้นตอนที่ 0. URL VIDEO YOUTUBE</label>
                                    <img src="<?php echo base_url().'assets/admin/images/youtubelink.png'; ?>" />
                                    <input class="form-control" type="text" name="cp_video" value="<?php echo $cp_video; ?>" />

                                  </div> <!-- End inside panel -->
                                <?php } ?>
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 1. อับโหลดรูปภาพไตเติล  ( รูปภาพเหมาะสมขนาด 640 x 550 )</label>
                                  <p>ขนาดรูปไม่เกิน 2 mb นะครับ (.gif / .jpg / .jpeg / .png)</p>
                                  <input type="file" name="cp_titleimg" />

                                  <br />
                                  <p>รูปภาพเก่า</p>
                                  <input type="hidden" name="fileselect" value="<?php echo $page == 'video' ? 'video' : $cp_titleimg; ?>"  />
                                  <img src="<?php echo ( $cp_titleimg == '' ) ? base_url().'uploads/thumb-size-demo.png' : base_url().'uploads/'.$cp_titleimg; ?>" class="img-preview" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 2. โพสโดย</label>
                                  <input class="form-control" type="text" name="cp_by" value="<?php echo $cp_by; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 3. ชื่อหัวข้อ</label>
                                  <input class="form-control" type="text" name="cp_titlehead" value="<?php echo $cp_titlehead; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 4. พูดโดย</label>
                                  <input class="form-control" type="text" name="cp_talkby" value="<?php echo $cp_talkby; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 5. ข้อความไตเติล โชว์หน้าแรก</label>
                                  <textarea name="cp_titletext" rows="10"> <?php echo $cp_titletext; ?></textarea>
                                </div> <!-- End inside panel -->
                                <div class="inside-panel news-group">
                                  <label >ขั้นตอนที่ 6. เลือกกลุ่มข่าว (ข่าวหนึ่งข่าวสามารถแสดงผลได้ในหลายกลุ่มข่าว)</label>
                                  <?php foreach( $cats as $cat ){ ?>
                                    <?php if( in_array($cat['id'], $cp_categorys ) ){ ?>
                                      <input type="checkbox" name="cp_category[]" value="<?php echo $cat['id']; ?>" checked="checked"> <?php echo $cat['name_th']; ?> <br />
                                    <?php }else{ ?>
                                      <input type="checkbox" name="cp_category[]" value="<?php echo $cat['id']; ?>"> <?php echo $cat['name_th']; ?> <br />
                                    <?php } ?>
                                  <?php } ?>
                                  <?php if( $page == "video"){ ?>
                                    <input type="checkbox" name="cp_category[]" value="6" checked="checked"> วิดีโอ
                                  <?php } ?>
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 7. เนื้อหาทั้งหมด</label>
                                  <p><textarea name="cp_content" id="editor<?php echo $lang['id']; ?>" rows="10" cols="80"><?php echo $cp_content; ?></textarea></p>
                                  <script>
                                    CKEDITOR.replace('editor<?php echo $lang['id']; ?>' ,{
                                  		filebrowserImageBrowseUrl : '<?php echo base_url('assets/filemanager/index.html');?>'
                                  	});
                                  </script>
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 8. แท็ก,คำค้นหา / Tag,Keyword</label>
                                  <input id="tags_1" name="cp_tag" type="text" class="tags" value="<?php echo $cp_tag; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 9. การตั้งค่า-การเรียงลำดับของหัวข้อ</label>
                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" align="left">
                                        <input type="radio" name="cp_sticky" id="radio" value="3" <?php echo ($cp_sticky == 3) ? 'checked="checked"' : '' ?> />
                                      </td>
                                      <td width="1%" height="25" align="left"><img src="<?php echo base_url(); ?>assets/admin/images/1.png" width="25" height="25" /></td>
                                      <td width="97%" align="left" class="left">สำคัญมากพิเศษ (จะถูกจัดเรียงในกลุ่มความสำคัญอันดับ 1)</td>
                                    </tr>
                                    </table>
                                  </div>

                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" align="left">
                                        <input type="radio" name="cp_sticky" id="radio" value="2" <?php echo ($cp_sticky == 2) ? 'checked="checked"' : '' ?> />
                                      </td>
                                      <td width="1%" height="25" align="left"><img src="<?php echo base_url(); ?>assets/admin/images/2.png" width="25" height="25" /></td>
                                      <td width="97%" align="left" class="left">สำคัญมาก (จะถูกจัดเรียงในกลุ่มความสำคัญอันดับ 2)</td>
                                    </tr>
                                    </table>
                                  </div>

                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" align="left">
                                        <input type="radio" name="cp_sticky" id="radio" value="1" <?php echo ($cp_sticky == 1) ? 'checked="checked"' : '' ?> />
                                      </td>
                                      <td width="1%" height="25" align="left"><img src="<?php echo base_url(); ?>assets/admin/images/3.png" width="25" height="25" /></td>
                                      <td width="97%" align="left" class="left">สำคัญ (จะถูกจัดเรียงในกลุ่มความสำคัญอันดับ 3)</td>
                                    </tr>
                                    </table>
                                  </div>

                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" align="left">
                                        <input name="cp_sticky" type="radio" id="radio" value="" <?php echo ($cp_sticky == "") ? 'checked="checked"' : '' ?> />
                                      </td>
                                      <td width="1%" height="25" align="left"><img src="<?php echo base_url(); ?>assets/admin/images/4.png" width="25" height="25" /></td>
                                      <td width="97%" align="left" class="left">ทั่วไป ( เรียงตามวันโพส)</td>
                                    </tr>
                                    </table>
                                  </div>
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 10. การตั้งค่า-แสดงข่าวที่หน้าแรกเป็น สไลด์</label>
                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" height="25" align="left">
                                        <input type="radio" name="cp_slidetop" id="radio" value="y" <?php echo ($cp_slidetop == "y") ? 'checked="checked"' : '' ?>/>
                                      </td>
                                      <td width="97%" align="left" class="left">แสดงในข่าว Slider</td>
                                    </tr>
                                    </table>
                                  </div>

                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" height="25" align="left">
                                        <input name="cp_slidetop" type="radio" id="radio" value="n" <?php echo ($cp_slidetop == "n") ? 'checked="checked"' : '' ?>/>
                                      </td>
                                      <td width="97%" align="left" class="left">ไม่แสดงในข่าว Slider</td>
                                    </tr>
                                    </table>
                                  </div>
                                </div> <!-- End inside panel -->

                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 11. การตั้งค่า- เปิด / ปิด สถานะการแสดงหัวข้อ</label>
                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" height="25" align="left">
                                        <input type="radio" name="cp_onoff" id="radio" value="y" <?php echo ($cp_onoff == "y") ? 'checked="checked"' : '' ?>/>
                                      </td>
                                      <td width="97%" align="left" class="left">เปิด การแสดงหัวข้อ</td>
                                    </tr>
                                    </table>
                                  </div>

                                  <div class="radio_button_addcon">
                                    <table width="90%" border="0" cellspacing="3" cellpadding="0">
                                    <tr>
                                      <td width="2%" height="25" align="left">
                                        <input name="cp_onoff" type="radio" id="radio" value="n" <?php echo ($cp_onoff == "n") ? 'checked="checked"' : '' ?> />
                                      </td>
                                      <td width="97%" align="left" class="left">ปิด การแสดงหัวข้อ</td>
                                    </tr>
                                    </table>
                                  </div>
                                </div> <!-- End inside panel -->

                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-2"></div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-2">
                            <input type="submit" value="Submit" class="form-control">
                          </div>
                        </div>

                        </form>
            	        </section>

                    <?php }  ?>


              </div><!-- panel body -->
            </div>
          </div>
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->


<!--  PAge Code Ends here -->
<?php $this->load->view('admin/inc/vwFooter'); ?>


<script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>main.js"></script>
