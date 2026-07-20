<?php
$this->load->view('admin/inc/vwHeader');
?>
<!--
Author : Abhishek R. Kaushik
Downloaded from http://devzone.co.in
-->

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">หัวข้อทั้งหมด </h3>
              </div>
              <div class="panel-body">
                <div class="inside-panel">
                  <label>หัวข้อที่มียอดผู้เปิดอ่านมากที่สุด 5 อันดับ</label>
                  <div class="table-responsive">
                    <table id="data-table" class="table table-hover tablesorter">
                      <thead>
                        <tr>
                          <th class="header">ยอดวิว </th>
                          <th class="header"><?php echo $page == 'cpvideo' ? 'วิดีโอ' : 'รูป' ?></th>
                          <th class="header">ชื่อหัวข้อ</th>
                          <th class="header">โพสโดย/วันที่</th>
                          <th class="header">แก้ไข/วันที่</th>
                          <th class="header">สถานะ</th>
                          <th class="header">Slider</th>
                          <th class="header">จัดการ</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach( $lists_view as $list){ ?>
                        <tr class="list-check" data-id="<?php echo $list['cpid']; ?>">
                          <td>[ <font color="#009900"><? echo $list['cp_postview']?></font> ]</td>
                          <td>
                            <img src="<?php if($list['cp_titleimg']==''){ echo HTTP_MEDIA_PATH."notimg.png";}else{echo HTTP_MEDIA_PATH.$list['cp_titleimg'];} ?>" alt="" width="150" height="114" />
                          </td>
                          <td class="title-head"><?php  echo iconv_substr($list['cp_titlehead'], 0, 120, "UTF-8")."..."; ?></td>
                          <td>
                            <?php  echo $list['cp_postby']?>
                             <br />
                            <?php  echo $list['cp_lastupdate']?>
                          </td>
                          <td>
                            <?php  echo $list['cp_editby']?>
                             <br />
                            <?php  echo $list['cp_edittime']?>
                          </td>
                          <td>
                            <?php  if ($list['cp_onoff'] == 'y'){
                                    echo"<img src='".HTTP_IMAGES_PATH_ADMIN."ok.png' width='18' height='18' /> <br />เปิดแสดง";
                                   }else {
                                     echo"<img src='".HTTP_IMAGES_PATH_ADMIN."no.png' width='18' height='18' /> <br />ปิดแสดง";
                                   }
                              ?>
                          </td>
                          <td>
                            <?php  if ($list['cp_slidetop'] == 'y'){
                                      echo"<img src='".HTTP_IMAGES_PATH_ADMIN."ok.png' width='18' height='18' /> <br />เปิดแสดง";
                                   }else {
                                      echo"<img src='".HTTP_IMAGES_PATH_ADMIN."no.png' width='18' height='18' /> <br />ปิดแสดง";
                                   }
                            ?>
                          </td>
                          <td>
                            <a href="<?php echo base_url().'admin/news/edit/'.$list['cpid'].'/'.$list['cp_code'].'/'.$page; ?>">
                              <input type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_edit.png" title="แก้ไข" />
                            </a>&nbsp;&nbsp;
                            <a href="<?php echo base_url().'news/details/'.$page.'/'.$list['cpid']; ?>" target="_blank">
                              <input type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_categories.png" title="ดูรายระเอียด" />
                            </a>&nbsp; &nbsp;
                            <a href="JavaScript:if(confirm('ยืนยันการลบหัวข้อ <?php echo $list['cp_titlehead']?>')==true){deleteNews( <?php echo $list['cpid']; ?> );}">
                              <input  type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_trash.png" title="ลบทิ้ง" />
                            </a>
                          </td>
                        </tr>
                      <?php $i++;} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="panel-body">
                <div class="inside-panel">
                  <label>หัวข้อที่อับเดทล่าสุด 5 อันดับ</label>
                  <div class="table-responsive">
                    <table id="data-table" class="table table-hover tablesorter">
                      <thead>
                        <tr>
                          <th class="header">ยอดวิว </th>
                          <th class="header"><?php echo $page == 'cpvideo' ? 'วิดีโอ' : 'รูป' ?></th>
                          <th class="header">ชื่อหัวข้อ</th>
                          <th class="header">โพสโดย/วันที่</th>
                          <th class="header">แก้ไข/วันที่</th>
                          <th class="header">สถานะ</th>
                          <th class="header">Slider</th>
                          <th class="header">จัดการ</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach( $lists_edit as $list){ ?>
                        <tr class="list-check" data-id="<?php echo $list['cpid']; ?>">
                          <td>[ <font color="#009900"><? echo $list['cp_postview']?></font> ]</td>
                          <td>
                            <img src="<?php if($list['cp_titleimg']==''){ echo HTTP_MEDIA_PATH."notimg.png";}else{echo HTTP_MEDIA_PATH.$list['cp_titleimg'];} ?>" alt="" width="150" height="114" />
                          </td>
                          <td class="title-head"><?php  echo iconv_substr($list['cp_titlehead'], 0, 120, "UTF-8")."..."; ?></td>
                          <td>
                            <?php  echo $list['cp_postby']?>
                             <br />
                            <?php  echo $list['cp_lastupdate']?>
                          </td>
                          <td>
                            <?php  echo $list['cp_editby']?>
                             <br />
                            <?php  echo $list['cp_edittime']?>
                          </td>
                          <td>
                            <?php  if ($list['cp_onoff'] == 'y'){
                                    echo"<img src='".HTTP_IMAGES_PATH_ADMIN."ok.png' width='18' height='18' /> <br />เปิดแสดง";
                                   }else {
                                     echo"<img src='".HTTP_IMAGES_PATH_ADMIN."no.png' width='18' height='18' /> <br />ปิดแสดง";
                                   }
                              ?>
                          </td>
                          <td>
                            <?php  if ($list['cp_slidetop'] == 'y'){
                                      echo"<img src='".HTTP_IMAGES_PATH_ADMIN."ok.png' width='18' height='18' /> <br />เปิดแสดง";
                                   }else {
                                      echo"<img src='".HTTP_IMAGES_PATH_ADMIN."no.png' width='18' height='18' /> <br />ปิดแสดง";
                                   }
                            ?>
                          </td>
                          <td>
                            <a href="<?php echo base_url().'admin/news/edit/'.$list['cpid'].'/'.$list['cp_code'].'/'.$page; ?>">
                              <input type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_edit.png" title="แก้ไข" />
                            </a>&nbsp;&nbsp;
                            <a href="<?php echo base_url().'news/details/'.$page.'/'.$list['cpid']; ?>" target="_blank">
                              <input type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_categories.png" title="ดูรายระเอียด" />
                            </a>&nbsp; &nbsp;
                            <a href="JavaScript:if(confirm('ยืนยันการลบหัวข้อ <?php echo $list['cp_titlehead']?>')==true){deleteNews( <?php echo $list['cpid']; ?> );}">
                              <input  type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_trash.png" title="ลบทิ้ง" />
                            </a>
                          </td>
                        </tr>
                      <?php $i++;} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>





      </div><!-- /#page-wrapper -->
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jquery.dataTables.js"></script>
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.bootstrap.js"></script>
      <script>
      </script>
<?php
$this->load->view('admin/inc/vwFooter');
?>
