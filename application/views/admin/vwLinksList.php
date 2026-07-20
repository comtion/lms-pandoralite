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
                  <div class="table-responsive">
                    <table id="data-table" class="table table-hover tablesorter">
                      <thead>
                        <tr>
                          <th class="header">ลำดับ </th>
                          <th class="header">ชื่อ</th>
                          <th class="header">รายละเอียด</th>
                          <th class="header"> กลุ่ม </th>
                          <th class="header"> URL </th>
                          <th class="header">จัดการ</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach( $lists as $list){ ?>
                        <tr class="list-check" data-id="<?php echo $list['id']; ?>">
                          <td> <?php echo $i; ?> </td>
                          <td> <?php echo $list['name']; ?> </td>
                          <td> <?php echo $list['descirption']; ?> </td>
                          <td> <?php echo $list['group_name']; ?> </td>
                          <td> <?php echo $list['link']; ?> </td>
                          <td>
                            <a href="<?php echo base_url().'admin/links/edit/'.$list['id'].'/'.$list['code'].'/'.$page; ?>">
                              <input type="image" src="<?php echo HTTP_IMAGES_PATH_ADMIN; ?>icn_edit.png" title="แก้ไข" />
                            </a>&nbsp;&nbsp;
                            <a href="JavaScript:if(confirm('ยืนยันการลบหัวข้อ <?php echo $list['name']?>')==true){deleteNews( <?php echo $list['id']; ?> );}">
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
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>main.js"></script>
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jquery.dataTables.js"></script>
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.bootstrap.js"></script>
      <script>
      $('#data-table').DataTable( {

    	} );
      </script>
<?php
$this->load->view('admin/inc/vwFooter');
?>
