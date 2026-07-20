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

<div id="page-wrapper" class="link-form">

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
                        $id = $news[$lang['text']]['id'];
                        $link_group = $news[$lang['text']]['link_group'];
                        $name = $news[$lang['text']]['name'];
                        $descirption = $news[$lang['text']]['descirption'];
                        $link = $news[$lang['text']]['link'];


                      }else{

                        $id = '';
                        $link_group = '';
                        $name = '';
                        $descirption = '';
                        $link = '';
                      }

                    ?>

                      <section id="content<?php echo $lang['id']; ?>">
                        <form role="form" class="form-edit" action="<?php echo base_url().'admin/links/saveForm'; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"  />
                        <input type="hidden" name="page" value="<?php echo $page; ?>"  />
                        <input type="hidden" name="lang" value="<?php echo $lang_set; ?>"  />
                        <input type="hidden" name="code" value="<?php echo $code; ?>"  />
                        <input type="hidden" name="page" value="<?php echo $page; ?>"  />
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title"></i> เพิ่มหรือแก้ไขข้อมูลลิ้งค์ </h3>
                              </div>
                              <div class="panel-body">
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 1. ชื่อลิ้ง</label>
                                  <input class="form-control" type="text" name="name_th" value="<?php echo $name; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 2. รายละเอียดลิ้ง</label>
                                  <input class="form-control" type="text" name="descirption" value="<?php echo $descirption; ?>" />
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 3. ลิ้งค์</label>
                                  <select class="form-control" name="link_group">
                                    <?php foreach( $cats as $cat ){ ?>
                                      <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $link_group ? 'selected' : '' ?>><?php echo $cat['name']; ?></option>
                                    <?php } ?>
                                  </select>
                                </div> <!-- End inside panel -->
                                <div class="inside-panel">
                                  <label >ขั้นตอนที่ 4. ลิ้งค์</label>
                                  <input class="form-control" type="text" name="link" value="<?php echo $link; ?>" />
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
