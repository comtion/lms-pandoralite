<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<link href="<?php echo HTTP_CSS_PATH; ?>request.css" rel="stylesheet" type="text/css" />

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
              <div class="col-md-12">
                <div class="dashHeader">
                  <div class="col-md-12">
                    <div class="tableNav"></div>
                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#self">ลงทะเบียนด้วยตนเอง</a></li>
                      <li><a data-toggle="tab" href="#other">ลงทะเบียนให้ผู้อื่น</a></li>
                    </ul>
                    <div class="tableNav"></div>
                    <!--form method="post" id="request-form"-->
                      <div class="tab-content">
                        <div id="self" class="tab-pane fade in active">
                          <div id="request-content">
                            <table class="info-header">
                              <tr>
                                <td>
                                  <h3>EXTERNAL TRAINING REQUISITION</h3>
                                  <span>Applicable for courses not available in ASP</span>
                                </td>
                                <td>
                                  Please Complete and submit this form to 
                                  learning &#38; Development and OD prior to course 
                                  commencement
                                </td>
                              </tr>
                            </table>
                            <br><b class="info-topic">PARTICIPANT INFORMATION</b>
                            <table class="info-content" id="self-staff">
                              <tr>
                                <td><b>Company</b></td>
                                <td colspan="4">
                                  <select name="scompany">
                                    <option value=""></option>
                                    <?php foreach($comp as $each) { ?>
                                    <option value="<?php echo $each['company']; ?>" <?php echo $emp['company'] == $each['company']? 'selected' : '';?>><?php echo $each['company']; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              </tr>
                              <tr>
                                <td><b>Division</b></td>
                                <td colspan="4">
                                  <select name="sdivision">
                                    <option value=""></option>
                                    <?php foreach($org as $each) { ?>
                                    <option value="<?php echo $each['org_desc']; ?>" <?php echo $emp['org_desc'] == $each['org_desc']? 'selected' : '';?>><?php echo $each['org_desc']; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              </tr>
                              <tr>
                                <td align="right" class="mini-col"><b>No.</b></td>
                                <td class="mini-col"><b>Staff ID No.</b></td>
                                <td><b>Name &#8211; Last Name</b></td>
                                <td><b>Position</b></td>
                                <td><b>Department</b></td>
                              </tr>
                              <tr class="self-staff">
                                <td align="right"><i class="fa fa-plus-circle" aria-hidden="true"></i><i class="fa fa-minus-circle" aria-hidden="true"></i>1.</td>
                                <td><input type="text" value="<?php echo $emp['emp_c']; ?>"></td>
                                <td><input type="text" value="<?php echo $emp['prefix'].$emp['fname'].' '.$emp['lname']; ?>"></td>
                                <td><input type="text" value="<?php echo $emp['postname']; ?>"></td>
                                <td><input type="text" value="<?php echo $emp['org_desc']; ?>"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><b>Current Responsibilities</b></td>
                                <td colspan="3"><textarea rows="4" maxlength="300" name="scurrentR"></textarea></td>
                              </tr>
                            </table>
                            <br><b class="info-topic">TRAING PROGRAMME INFORMATION</b>
                            <table class="info-content">
                              <tr>
                                <td><b>Title of Course &#47; Workshop</b></td>
                                <td><b>Location of Training</b></td>
                                <td><b>Name of Organizer &#47; Vendor</b></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="stitleOCW" value=""></td>
                                <td><input type="text" name="slocationOT" value=""></td>
                                <td><input type="text" name="snameOOV" value=""></td>
                              </tr>
                              <tr>
                                <td><b>Course Objective</b></td>
                                <td colspan="4"><textarea rows="4" maxlength="300" name="scourseO"></textarea></td>
                              </tr>
                              <tr>
                                <td><b>Expected Result</b></td>
                                <td colspan="4"><textarea rows="4" maxlength="300" name="sexpectedR"></textarea></td>
                              </tr>
                              <tr>
                                <td><b>Dates of Training</b></td>
                                <td>Begin Date : <input type="text" class="mini-input" name="sbeginD" value=""></td>
                                <td>End Date : <input type="text" class="mini-input" name="sendD" value=""></td>
                              </tr>
                            </table>
                            <br><b class="info-topic">EXPENSE</b>
                            <table class="info-content">
                              <tr>
                                <td><b>Course Fees &#40;Baht&#41;</b></td>
                                <td><b>Pay to</b></td>
                                <td><b>Charge to &#40;Department &#47; Division&#41;</b></td>
                                <td><b>RC Code</b></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="scourseF" value=""></td>
                                <td><input type="text" name="spayT" value=""></td>
                                <td><input type="text" name="schargeT" value=""></td>
                                <td>
                                  <select name="srcC">
                                    <option value=""></option>
                                    <?php foreach($org as $each) { ?>
                                      <?php if(!empty($each['org_abbr_code'])) { ?>
                                        <option value="<?php echo $each['org_abbr_code']; ?>" <?php echo $emp['org_abbr_code'] == $each['org_abbr_code']? 'selected' : '';?>><?php echo $each['org_abbr_code']; ?></option>
                                      <?php } ?>
                                    <?php } ?>
                                  </select>
                                </td>
                              </tr>
                            </table>
                            <br>
                            <table class="info-sign">
                              <tr>
                                <td align="right"><b>Request By</b></td><td class="info-sign-field"><input type="text" name="ssrequestB" value=""></td>
                                <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" name="ssapprovedB1" value=""></td>
                              </tr>
                              <tr>
                                <td align="right"><b>Department &#47; Ext. &#35;</b></td><td class="info-sign-field"><input type="text" name="ssdepartment" value=""></td>
                                <td align="right"><b>Division</b></td><td class="info-sign-field"><input type="text" name="ssdivision" value=""></td>
                              </tr>
                              <tr>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="ssdate1" value=""></td>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="ssdate2" value=""></td>
                              </tr>
                              <tr><td colspan="4" class="info-sign-empty"></td></tr>
                              <tr>
                                <td align="right"><b>Confirmed By</b></td><td class="info-sign-field"><input type="text" name="ssconfirmedB" value=""></td>
                                <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" name="ssapprovedB2" value=""></td>
                              </tr>
                              <tr>
                                <td></td><td align="center">&#40;<input class="info-sign-name" type="text" value="">&#41;</td>
                                <td></td><td align="center">&#40;<input class="info-sign-name" type="text" value="">&#41;</td>
                              </tr>
                              <tr>
                                <td></td><td align="center">Learning &#38; Dev. and OD</td>
                                <td></td><td align="center">HR Division Head</td>
                              </tr>
                              <tr>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="ssdate3" value=""></td>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="ssdate4" value=""></td>
                              </tr>
                            </table>
                            <div class="info-footer">
                              <br><br><br>
                              HRD-18<br><?php echo date('F Y'); ?>
                            </div>
                          </div>
                          <div align="center">
                            <button type="button" class="btn btn-default" id="self-print">Print</button>
                            <!--button type="submit" class="btn btn-default" name="self-pdf">PDF</button-->
                          </div>
                        </div>
                        <div id="other" class="tab-pane fade">
                          <div id="request-content">
                            <table class="info-header">
                              <tr>
                                <td>
                                  <h3>EXTERNAL TRAINING REQUISITION</h3>
                                  <span>Applicable for courses not available in ASP</span>
                                </td>
                                <td>
                                  Please Complete and submit this form to 
                                  learning &#38; Development and OD prior to course 
                                  commencement
                                </td>
                              </tr>
                            </table>
                            <br><b class="info-topic">PARTICIPANT INFORMATION</b>
                            <table class="info-content" id="other-staff">
                              <tr>
                                <td align="right"><b>Company</b></td>
                                <td colspan="4"><input type="text" name="ocompany" value=""></td>
                              </tr>
                              <tr>
                                <td align="right"><b>Division</b></td>
                                <td colspan="4"><input type="text" name="odivision" value=""></td>
                              </tr>
                              <tr>
                                <td align="right" class="mini-col"><b>No.</b></td>
                                <td class="mini-col"><b>Staff ID No.</b></td>
                                <td><b>Name &#8211; Last Name</b></td>
                                <td><b>Position</b></td>
                                <td><b>Department</b></td>
                              </tr>
                              <tr class="other-staff">
                                <td align="right"><i class="fa fa-plus-circle" aria-hidden="true"></i><i class="fa fa-minus-circle" aria-hidden="true"></i>1.</td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><b>Current Responsibilities</b></td>
                                <td colspan="3"><textarea rows="4" maxlength="300" name="ocurrentR"></textarea></td>
                              </tr>
                            </table>
                            <br><b class="info-topic">TRAING PROGRAMME INFORMATION</b>
                            <table class="info-content">
                              <tr>
                                <td><b>Title of Course &#47; Workshop</b></td>
                                <td><b>Location of Training</b></td>
                                <td><b>Name of Organizer &#47; Vendor</b></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="otitleOCW" value=""></td>
                                <td><input type="text" name="olocationOT" value=""></td>
                                <td><input type="text" name="onameOOV" value=""></td>
                              </tr>
                              <tr>
                                <td><b>Course Objective</b></td>
                                <td colspan="4"><textarea rows="4" maxlength="300" name="ocourseO"></textarea></td>
                              </tr>
                              <tr>
                                <td><b>Expected Result</b></td>
                                <td colspan="4"><textarea rows="4" maxlength="300" name="oexpectedR"></textarea></td>
                              </tr>
                              <tr>
                                <td><b>Dates of Training</b></td>
                                <td>Begin Date : <input type="text" class="mini-input" name="obeginD" value=""></td>
                                <td>End Date : <input type="text" class="mini-input" name="oendD" value=""></td>
                              </tr>
                            </table>
                            <br><b class="info-topic">EXPENSE</b>
                            <table class="info-content">
                              <tr>
                                <td><b>Course Fees &#40;Baht&#41;</b></td>
                                <td><b>Pay to</b></td>
                                <td><b>Charge to &#40;Department &#47; Division&#41;</b></td>
                                <td><b>RC Code</b></td>
                              </tr>
                              <tr>
                                <td><input type="text" name="ocourseF" value=""></td>
                                <td><input type="text" name="opayT" value=""></td>
                                <td><input type="text" name="ochargeT" value=""></td>
                                <td><input type="text" name="orcC" value=""></td>
                              </tr>
                            </table>
                            <br>
                            <table class="info-sign">
                              <tr>
                                <td align="right"><b>Request By</b></td><td class="info-sign-field"><input type="text" name="osrequestB" value=""></td>
                                <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" name="osapprovedB1" value=""></td>
                              </tr>
                              <tr>
                                <td align="right"><b>Department &#47; Ext. &#35;</b></td><td class="info-sign-field"><input type="text" name="osdepartment" value=""></td>
                                <td align="right"><b>Division</b></td><td class="info-sign-field"><input type="text" name="osdivision" value=""></td>
                              </tr>
                              <tr>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="osdate1" value=""></td>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="osdate2" value=""></td>
                              </tr>
                              <tr><td colspan="4" class="info-sign-empty"></td></tr>
                              <tr>
                                <td align="right"><b>Confirmed By</b></td><td class="info-sign-field"><input type="text" name="osconfirmedB" value=""></td>
                                <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" name="osapprovedB2" value=""></td>
                              </tr>
                              <tr>
                                <td></td><td align="center">&#40;<input class="info-sign-name" type="text" value="">&#41;</td>
                                <td></td><td align="center">&#40;<input class="info-sign-name" type="text" value="">&#41;</td>
                              </tr>
                              <tr>
                                <td></td><td align="center">Learning &#38; Dev. and OD</td>
                                <td></td><td align="center">HR Division Head</td>
                              </tr>
                              <tr>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="osdate3" value=""></td>
                                <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" name="osdate4" value=""></td>
                              </tr>
                            </table>
                            <div class="info-footer">
                              <br><br><br>
                              HRD-18<br><?php echo date('F Y'); ?>
                            </div>
                          </div>
                          <div align="center">
                            <button type="button" class="btn btn-default" id="other-print">Print</button>
                            <!--button type="submit" class="btn btn-default" name="other-pdf">PDF</button-->
                          </div>
                        </div>
                      </div>
                    <!--/form-->
                    <div class="tableNav"></div>
                  </div>
                </div>
  						</div>
            </div>
          </div>
				</div>
			</div>
		</div>
		</div>


		<!--footer-->
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    
    <script src="<?php echo HTTP_JS_PATH; ?>request.js"></script>
      
    </div>
  </body>
</html>
