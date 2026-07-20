<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/extension/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo REAL_PATH; ?>/assets/admin/js/dataTables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP_CSS_PATH; ?>manage.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
/* Center the loader */
.cssload-thecube {
  width: 73px;
  height: 73px;
  margin: 0 auto;
  margin-top: 49px;
  position: relative;
  transform: rotateZ(45deg);
    -o-transform: rotateZ(45deg);
    -ms-transform: rotateZ(45deg);
    -webkit-transform: rotateZ(45deg);
    -moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
  position: relative;
  transform: rotateZ(45deg);
    -o-transform: rotateZ(45deg);
    -ms-transform: rotateZ(45deg);
    -webkit-transform: rotateZ(45deg);
    -moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
  float: left;
  width: 50%;
  height: 50%;
  position: relative;
  transform: scale(1.1);
    -o-transform: scale(1.1);
    -ms-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
}
.cssload-thecube .cssload-cube:before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(43,160,199);
  animation: cssload-fold-thecube 2.76s infinite linear both;
    -o-animation: cssload-fold-thecube 2.76s infinite linear both;
    -ms-animation: cssload-fold-thecube 2.76s infinite linear both;
    -webkit-animation: cssload-fold-thecube 2.76s infinite linear both;
    -moz-animation: cssload-fold-thecube 2.76s infinite linear both;
  transform-origin: 100% 100%;
    -o-transform-origin: 100% 100%;
    -ms-transform-origin: 100% 100%;
    -webkit-transform-origin: 100% 100%;
    -moz-transform-origin: 100% 100%;
}
.cssload-thecube .cssload-c2 {
  transform: scale(1.1) rotateZ(90deg);
    -o-transform: scale(1.1) rotateZ(90deg);
    -ms-transform: scale(1.1) rotateZ(90deg);
    -webkit-transform: scale(1.1) rotateZ(90deg);
    -moz-transform: scale(1.1) rotateZ(90deg);
}
.cssload-thecube .cssload-c3 {
  transform: scale(1.1) rotateZ(180deg);
    -o-transform: scale(1.1) rotateZ(180deg);
    -ms-transform: scale(1.1) rotateZ(180deg);
    -webkit-transform: scale(1.1) rotateZ(180deg);
    -moz-transform: scale(1.1) rotateZ(180deg);
}
.cssload-thecube .cssload-c4 {
  transform: scale(1.1) rotateZ(270deg);
    -o-transform: scale(1.1) rotateZ(270deg);
    -ms-transform: scale(1.1) rotateZ(270deg);
    -webkit-transform: scale(1.1) rotateZ(270deg);
    -moz-transform: scale(1.1) rotateZ(270deg);
}
.cssload-thecube .cssload-c2:before {
  animation-delay: 0.35s;
    -o-animation-delay: 0.35s;
    -ms-animation-delay: 0.35s;
    -webkit-animation-delay: 0.35s;
    -moz-animation-delay: 0.35s;
}
.cssload-thecube .cssload-c3:before {
  animation-delay: 0.69s;
    -o-animation-delay: 0.69s;
    -ms-animation-delay: 0.69s;
    -webkit-animation-delay: 0.69s;
    -moz-animation-delay: 0.69s;
}
.cssload-thecube .cssload-c4:before {
  animation-delay: 1.04s;
    -o-animation-delay: 1.04s;
    -ms-animation-delay: 1.04s;
    -webkit-animation-delay: 1.04s;
    -moz-animation-delay: 1.04s;
}



@keyframes cssload-fold-thecube {
  0%, 10% {
    transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-o-keyframes cssload-fold-thecube {
  0%, 10% {
    -o-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -o-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -o-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-ms-keyframes cssload-fold-thecube {
  0%, 10% {
    -ms-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -ms-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -ms-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-webkit-keyframes cssload-fold-thecube {
  0%, 10% {
    -webkit-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -webkit-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -webkit-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
}

@-moz-keyframes cssload-fold-thecube {
  0%, 10% {
    -moz-transform: perspective(136px) rotateX(-180deg);
    opacity: 0;
  }
  25%,
        75% {
    -moz-transform: perspective(136px) rotateX(0deg);
    opacity: 1;
  }
  90%,
        100% {
    -moz-transform: perspective(136px) rotateY(180deg);
    opacity: 0;
  }
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
							<div class="tableNav">
								<button class="btn btn-default left" type="submit"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
								<button class="btn btn-default right" type="submit"><i class="fa fa-caret-right" aria-hidden="true"></i></button>
							</div>
              <form action="removeUser" method="post" id="remove-form"></form>
              <form action="<?php $fpage = explode('/',$page); echo end($fpage);?>" method="post">
  							<div class="col-md-12">
                  <div class="dashpageWrap">
                    <?php if($page == 'manage/manageUser') { ?>
                    <button type="submit" name="add" class="manage-btn"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php echo label('m_add'); ?></button>
                    <button type="button" id="add_button" class="manage-btn" data-toggle="modal" data-target="#modal-default"><i class="fa fa-clock-o"></i><span> อัพข้อมูล (Excel File)</span></button>
                    <?php } ?>
    							</div>
  								<div class="table-wrapper">
  									<table class="table table-striped" id="manage-table">
  										<thead>
  											<tr>
  												<th><?php echo label('m_no'); ?></th>
                          <th><?php echo label('m_username'); ?></th>
                          <th><?php echo label('m_name'); ?></th>
                          <th><?php echo label('m_organization'); ?></th>
                          <th><?php echo label('m_company'); ?></th>
                          <th><?php echo label('r_position'); ?></th>
                          <th><?php echo label('m_role'); ?></th>
                          <th></th>
  											</tr>
  										</thead>
  										<tbody>
                        <?php foreach($users as $no=>$user) { ?>
  											<tr>
  												<th scope="row"><?php echo $no+1; ?></th>
                          <td><?php echo $user['useri']; ?></td>
                          <td><?php echo $user['prefix'].$user['fname'].' '.$user['lname']; ?></td>
                          <td><?php echo getOrgName( $user['org1'] , '1' , $lang ); ?></td>
                          <td><?php echo getOrgName( $user['org2'] , '2' , $lang); ?></td>
                          <td><?php echo getPosname( $user['main_pos'] , $lang ); ?></td>
                          <td><?php echo $user['role']; ?></td>
                          <td>
                            <button type="submit" name="edit" value="<?php echo $user['useri']; ?>" class="manage-btn"><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo label('m_edit'); ?></button>
                            <?php if($page == 'manage/manageUser') { ?>
                              <?php if($user['emp_c'] != $emp_c) { ?>
                              <button type="submit" name="remove" value="<?php echo $user['useri']; ?>" form="remove-form" class="manage-btn"><i class="fa fa-trash" aria-hidden="true"></i> <?php echo label('m_remove'); ?></button>
                              <?php } ?>
                            <?php } ?>
                          </td>
  											</tr>
                        <?php } ?>
  										</tbody>
  									</table>
  								</div>
                  <div class="tableNav"></div>
  							</div>
              </form>
						</div>
					</div>
				</div>
        <br><br><br><br><br>
			</div>
		</div>

      <div class="modal modal-default fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-clock-o"></i><span> อัพข้อมูล (Excel File)</span></h4>
              </div>

              <form method="post" id="import_form" name="import_form" enctype="multipart/form-data" class="form-horizontal" role="form">
                <div class="modal-body">

                  <div class="row">
                    <div class="col-md-12">
                      <div id="mySend">
                        <div class="box-body">
                          <div class="form-group"> 
                                      <label for="excel_file">ไฟล์ Excel (.xls):</label>
                                      <input type="file" name="excel_file" id="excel_file" accept="application/vnd.ms-excel"><br>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="loader" style="display:none;">
                    <div class="cssload-thecube">
                      <div class="cssload-cube cssload-c1"></div>
                      <div class="cssload-cube cssload-c2"></div>
                      <div class="cssload-cube cssload-c4"></div>
                      <div class="cssload-cube cssload-c3"></div>
                    </div><br><br>
                    <p align="center">รอสักครู่ ระบบกำลังประมวลผล ...</p>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="operation" id="operation" value="Add" >
                  <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">ยกเลิก</button>
                  <input type="submit" name="action" id="action" class="btn btn-primary btn-flat pull-left" value="ตกลง" />
                </div>

              </form>
            </div>
          </div>
        </div>

		<!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>

    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.tableTools.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/jszip.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo HTTP_JS_PATH_ADMIN; ?>dataTables/buttons.print.min.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>tableCarousel.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>manage.js"></script>
    <script type="text/javascript">
      
            $('#add_button').click(function(){
              document.getElementById("loader").style.display = "none";
              document.getElementById("mySend").style.display = "";
            });
            function showPage() {
              document.getElementById("loader").style.display = "";
              document.getElementById("mySend").style.display = "none";
            }
            $(document).on('submit', '#import_form', function(event){
              event.preventDefault();
              var excel_file = $('#excel_file').val();  

              if(excel_file!='') 
              {

                showPage();
                console.log(excel_file);
                $.ajax({
                  url:"<?=base_url()?>import/import_admindealer.php",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                      console.log(data);
                    if(data=="1"){
                      alert('อัพโหลดข้อมูลเรียบร้อย');
                      location.reload();
                    }else{
                      console.log(data);
                    }
                    //
                  }
                });
              }
              else
              {    
                alert("กรุณาเลือกไฟล์ Excel ก่อนนะครับ");
              }
            });
    </script>
    </div>
  </body>
</html>
