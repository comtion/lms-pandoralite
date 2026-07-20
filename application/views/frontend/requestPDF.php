<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<!--link href="<?php echo HTTP_CSS_PATH; ?>request.css" rel="stylesheet" type="text/css" /-->

  </head>
  <body>
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
      <table class="info-content">
        <tr>
          <td align="right"><b>Company</b></td>
          <td colspan="4"><input type="text" value="<?php echo $pval['company']; ?>"></td>
        </tr>
        <tr>
          <td align="right"><b>Division</b></td>
          <td colspan="4"><input type="text" value="<?php echo $pval['division']; ?>"></td>
        </tr>
        <tr>
          <td align="right" class="mini-col"><b>No.</b></td>
          <td class="mini-col"><b>Staff ID No.</b></td>
          <td><b>Name &#8211; Last Name</b></td>
          <td><b>Position</b></td>
          <td><b>Department</b></td>
        </tr>
        <tr>
          <td align="right">1.</td>
          <td><input type="text"></td>
          <td><input type="text"></td>
          <td><input type="text"></td>
          <td><input type="text"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Current Responsibilities</b></td>
          <td colspan="3"><textarea rows="4" maxlength="300"><?php echo $pval['currentR']; ?></textarea></td>
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
          <td><input type="text" value="<?php echo $pval['titleOCW']; ?>"></td>
          <td><input type="text" value="<?php echo $pval['locationOT']; ?>"></td>
          <td><input type="text" value="<?php echo $pval['nameOOV']; ?>"></td>
        </tr>
        <tr>
          <td><b>Course Objective</b></td>
          <td colspan="4"><textarea rows="4" maxlength="300"><?php echo $pval['courseO']; ?></textarea></td>
        </tr>
        <tr>
          <td><b>Expected Result</b></td>
          <td colspan="4"><textarea rows="4" maxlength="300"><?php echo $pval['expectedR']; ?></textarea></td>
        </tr>
        <tr>
          <td><b>Dates of Training</b></td>
          <td>Begin Date : <input type="text" class="mini-input" value="<?php echo $pval['beginD']; ?>"></td>
          <td>End Date : <input type="text" class="mini-input" value="<?php echo $pval['endD']; ?>"></td>
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
          <td><input type="text" value="<?php echo $pval['courseF']; ?>"></td>
          <td><input type="text" value="<?php echo $pval['payT']; ?>"></td>
          <td><input type="text" value="<?php echo $pval['chargeT']; ?>"></td>
          <td><input type="text" value="<?php echo $pval['rcC']; ?>"></td>
        </tr>
      </table>
      <br>
      <table class="info-sign">
        <tr>
          <td align="right"><b>Request By</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['srequestB']; ?>"></td>
          <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sapprovedB1']; ?>"></td>
        </tr>
        <tr>
          <td align="right"><b>Department &#47; Ext. &#35;</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdepartment']; ?>"></td>
          <td align="right"><b>Division</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdivision']; ?>"></td>
        </tr>
        <tr>
          <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdate1']; ?>"></td>
          <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdate2']; ?>"></td>
        </tr>
        <tr><td colspan="4" class="info-sign-empty"></td></tr>
        <tr>
          <td align="right"><b>Confirmed By</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sconfirmedB']; ?>"></td>
          <td align="right"><b>Approved By</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sapprovedB2']; ?>"></td>
        </tr>
        <tr>
          <td></td><td align="center">&#40;Pe Suwansakornkul&#41;</td>
          <td></td><td align="center">&#40;Vimolpan Suwantewatoop&#41;</td>
        </tr>
        <tr>
          <td></td><td align="center">Learning &#38; Dev. and OD</td>
          <td></td><td align="center">HR Division Head</td>
        </tr>
        <tr>
          <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdate3']; ?>"></td>
          <td align="right"><b>Date</b></td><td class="info-sign-field"><input type="text" value="<?php echo $pval['sdate4']; ?>"></td>
        </tr>
      </table>
      <div class="info-footer">
        HRD-18<br><?php echo date('F Y'); ?>
      </div>
    </div>



    <style>
      @media print {
        body * {
          font-size: 14px;
          visibility: hidden;
        }
        h3 {
          font-size: 18px;
          font-weight: bold;
          display: inline-block;
        }
        span {
          display: inline-block;
        }
        #request-content * {
          visibility: visible;
        }
        #request-content {
          position: fixed;
          left: 0;
          top: 0;
        }
      }
      select {
        width: 100%;
        border: none;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
        text-align-last: center;
        -moz-text-align-last: center;
      }
      select::-ms-expand {
        display: none;
      }
      input {
        width: 100%;
        border: none;
        outline: none;
        text-align: center;
      }
      input.mini-input {
        width: auto;
      }
      textarea {
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        resize: none;
      }
      table {
        width: 100%;
      }
      td {
        padding: 5px;
      }
      td.mini-col {
        width: 10%;
      }
      td.info-sign-field {
        border-bottom: 1px solid black;
      }
      td.info-sign-empty {
        padding-top: 30px;
      }
      .info-header td {
        padding: 10px;
        width: 50%;
        border: 2px solid black;
      }
      .info-topic {
        padding-left: 1em;
      }
      .info-content td {
        border: 1px solid black;
      }
      .info-sign-field input {
        margin-top: 3px;
        margin-bottom: 5px;
      }
      .info-footer {
        text-align: right;
      }
    </style>
  </body>
</html>
