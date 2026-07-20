<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
  </head>
  <body>
  	<div id="superwrapper">
	    <!--Nav-->
	    <?php $this->load->view('frontend/inc/inc-header.php'); ?>


      <!--content-->
      <div class="container dashboard main">
        <div class="row">
          <div class="content dashWrap noSideBar">
            <div class="dashElement page courseCreate">
              <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                  <h2 style="color:#555;padding: 10px 0;font-size: 17px;">กรุณาเปลี่ยนรหัสผ่านเนื่องจากเข้าใช้งานครั้งแรก</h2>
                  <form method="POST" action="<?php echo REAL_PATH.'/dashboard/loggedIn'; ?>" class="form-horizontal">
                    <div class="form-group">
                      <label for="inputCName" class="col-sm-4 control-label"><?php echo label('newpass') ?></label>
                      <div class="col-sm-8">
                          <input type="password" class="form-control" name="newpass">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputCName" class="col-sm-4 control-label"><?php echo label('confirmpass') ?></label>
                      <div class="col-sm-8">
                          <input type="password" class="form-control" name="confirmpass">
                      </div>
                    </div>
                    <div class="form-group courseCreateButton">
                      <button type="submit" class="btn btn-primary passexpire-confirm"><i style="padding:0px;" class="fa fa-sign-in" aria-hidden="true"></i> <?php echo label('confirm') ?></button>
                    </div>
                  </form>
                </div>
                <div class="col-md-3">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

		<!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

    </div>
  </body>
</html>
