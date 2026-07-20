<?php
/*
header('Content-type: application/vnd.ms-excel');

//// It will be called file.xls

header('Content-Disposition: attachment; filename="User Information.xls"');*/

$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';
// Create new PHPExcel object

require_once "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$bold = [
    'font' => [
        'bold' => true,
    ],
];
$border = [
    'borders' => [ // กำหนดเส้นขอบ
        'allBorders' => [ // กำหนดเส้นขอบทั้งหม
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

include("conn.php");
mysqli_query($conndb,"SET NAMES 'utf8'");

		$arr_ug = array();
		$arr_depart = array();
		$arr_posi = array();

		$sql_ug = "select * from lms_usp_gp";
		$query_ug = mysqli_query($conndb,$sql_ug);
		$num_ug = mysqli_num_rows($query_ug);
		if($num_ug>0){
				while ($fetch_ug = mysqli_fetch_array($query_ug)) {
						$arr_ug[$fetch_ug['ug_id']]['name_th'] = $fetch_ug['ug_name_th'];
						$arr_ug[$fetch_ug['ug_id']]['name_en'] = $fetch_ug['ug_name_en'];
				}
		}
		$sql_dep = "select * from lms_depart";
		$query_dep = mysqli_query($conndb,$sql_dep);
		$num_dep = mysqli_num_rows($query_dep);
		if($num_dep>0){
				while ($fetch_dep = mysqli_fetch_array($query_dep)) {
						$arr_dep[$fetch_dep['dep_id']]['name_th'] = $fetch_dep['dep_name_th'];
						$arr_dep[$fetch_dep['dep_id']]['name_en'] = $fetch_dep['dep_name_en'];
				}
		}
		$sql_posi = "select * from lms_position";
		$query_posi = mysqli_query($conndb,$sql_posi);
		$num_posi = mysqli_num_rows($query_posi);
		if($num_posi>0){
				while ($fetch_posi = mysqli_fetch_array($query_posi)) {
						$arr_posi[$fetch_posi['posi_id']]['name_th'] = $fetch_posi['posi_name_th'];
						$arr_posi[$fetch_posi['posi_id']]['name_en'] = $fetch_posi['posi_name_en'];
				}
		}

		$where = '';
		if($com_id!=""){
				$where = " and lms_emp.com_id='".$com_id."'";
		}
		$sql_emp = "select * from lms_emp inner join lms_usp on lms_emp.emp_id = lms_usp.emp_id where lms_emp.emp_isDelete='0' and lms_usp.u_isDelete='0'
								 and lms_usp.useri!='admin_verztec'".$where." order by lms_emp.com_id ASC";
		$query_emp = mysqli_query($conndb,$sql_emp);
?>

<?php 

		function data_rechk($conndb,$sv_id='',$num=''){
			$sql_detail = "select lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var from lms_survey_de inner join lms_qn_user_de on lms_survey_de.svde_id = lms_qn_user_de.svde_id
										 where lms_survey_de.sv_id = '".$sv_id."' and lms_qn_user_de.qnude_var='".$num."'";
			$query_detail = mysqli_query($conndb,$sql_detail);
			// $num_detail = mysqli_num_rows($query_detail);
			return $query_detail;
		}



		$objPHPExcel = new Spreadsheet();
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->getColumnDimension('A')->setAutoSize(true);
		$activeSheet->getColumnDimension('B')->setAutoSize(true);
		$activeSheet->getColumnDimension('C')->setAutoSize(true);
		$activeSheet->getColumnDimension('D')->setAutoSize(true);
		$activeSheet->getColumnDimension('E')->setAutoSize(true);
		$activeSheet->getColumnDimension('F')->setAutoSize(true);
		$activeSheet->getColumnDimension('G')->setAutoSize(true);
		$activeSheet->getColumnDimension('H')->setAutoSize(true);
		$activeSheet->getColumnDimension('I')->setAutoSize(true);
		$activeSheet->getColumnDimension('J')->setAutoSize(true);
		$activeSheet->getColumnDimension('K')->setAutoSize(true);
		$activeSheet->getColumnDimension('L')->setAutoSize(true);
		$activeSheet->getColumnDimension('M')->setAutoSize(true);
		$activeSheet->setCellValue('A1', "Company' email*");
		$activeSheet->setCellValue('B1', 'User Group*');
		$activeSheet->setCellValue('C1', 'Company Code (Nick Name)*');
		$activeSheet->setCellValue('D1', 'Department*');
		$activeSheet->setCellValue('E1', 'Position*');
		$activeSheet->setCellValue('F1', "Manager 1 company' Email*");
		$activeSheet->setCellValue('G1', "Manager 2 company' Email");
		$activeSheet->setCellValue('H1', 'Name TH*');
		$activeSheet->setCellValue('I1', 'Lastname TH*');
		$activeSheet->setCellValue('J1', 'Name ENG*');
		$activeSheet->setCellValue('K1', 'Lastname ENG*');
		$activeSheet->setCellValue('L1', 'System start date*');
		$activeSheet->setCellValue('M1', 'System usage end date');

		$num_emp = mysqli_num_rows($query_emp); 
		if($num_emp>0){
				$numrow = 2;
				while($fetch_emp = mysqli_fetch_array($query_emp)){
						if(isset($arr_ug[$fetch_emp['ug_id']]) && isset($arr_dep[$fetch_emp['dep_id']]) && isset($arr_posi[$fetch_emp['posi_id']])){
								$sql_com = "select * from lms_company where com_id='".$fetch_emp['com_id']."'";
								$query_com = mysqli_query($conndb,$sql_com);
								$fetch_com = mysqli_fetch_array($query_com);
								$u_firstdate = $fetch_emp['u_firstdate']!="0000-00-00 00:00:00"?date('Y-m-d',strtotime($fetch_emp['u_firstdate'])):"";
								$inactivedate = $fetch_emp['inactivedate']!="0000-00-00"?date('Y-m-d',strtotime($fetch_emp['inactivedate'])):"";
								$activeSheet->setCellValue('A'.$numrow, $fetch_emp['useri']);
								$activeSheet->setCellValue('B'.$numrow, $arr_ug[$fetch_emp['ug_id']]['name_en']);
								$activeSheet->setCellValue('C'.$numrow, $fetch_com['com_code']);
								$activeSheet->setCellValue('D'.$numrow, $arr_dep[$fetch_emp['dep_id']]['name_en']);
								$activeSheet->setCellValue('E'.$numrow, $arr_posi[$fetch_emp['posi_id']]['name_en']);
								$activeSheet->setCellValue('F'.$numrow, $fetch_emp['emp_manage_a']);
								$activeSheet->setCellValue('G'.$numrow, $fetch_emp['emp_manage_b']);
								$activeSheet->setCellValue('H'.$numrow, $fetch_emp['fname_th']);
								$activeSheet->setCellValue('I'.$numrow, $fetch_emp['lname_th']);
								$activeSheet->setCellValue('J'.$numrow, $fetch_emp['fname_en']);
								$activeSheet->setCellValue('K'.$numrow, $fetch_emp['lname_en']);
								if($u_firstdate!=""){
									$activeSheet->setCellValue('L'.$numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($u_firstdate));
									$activeSheet->getStyle('L'.$numrow)
											->getNumberFormat()
											->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
								}else{
									$activeSheet->setCellValue('L'.$numrow, '');
								}
								if($inactivedate!=""){
									$activeSheet->setCellValue('M'.$numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($inactivedate));
									$activeSheet->getStyle('M'.$numrow)
											->getNumberFormat()
											->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
								}else{
									$activeSheet->setCellValue('M'.$numrow, '');
								}
								$numrow++;
						}
				}
		}

    /*$writer = new Xlsx($objPHPExcel);
    $writer->save('hello world.xlsx');*/
/*    $writer = new Xlsx($objPHPExcel);
    $writer->save('User_Information.xlsx');

    file_put_contents("User_Information.xlsx", fopen("https://127.0.0.1/isuzu_motor/excel_export/User_Information.xlsx", 'r'));*/

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
    $namefile = 'fileexcel/User_Information'.date('YmdHi').'.xlsx';
            $writer->save($namefile);
            //$writer->save("php://output");

		$url = 'http://127.0.0.1:90/imat/excel_export/'.$namefile; 
		header( "location: ".$url );

        /*    set_time_limit(0); 
$file = file_get_contents('path of your file');
file_put_contents('file.ext', $file);*/
?>
<!--
  echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">';
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<body>
 
<table width="100%"  id="tbl1" class="table2excel" border="1" style="border-collapse: collapse;border: 1px solid black;">
  <thead>
    <tr>
      <th>Company's email*</th>
      <th>User Group*</th>
      <th>Company Code (Nick Name)*</th>
      <th>Department*</th>
      <th>Position*</th>
      <th>Manager 1 company's Email*</th>
      <th>Manager 2 company's Email</th>
      <th>Name TH*</th>
      <th>Lastname TH*</th>
      <th>Name ENG*</th>
      <th>Lastname ENG*</th>
      <th>System start date*</th>
      <th>System usage end date</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $num_emp = mysqli_num_rows($query_emp); 
      if($num_emp>0){
        while($fetch_emp = mysqli_fetch_array($query_emp)){

          $sql_com = "select * from lms_company where com_id='".$fetch_emp['com_id']."'";
          $query_com = mysqli_query($conndb,$sql_com);
          $fetch_com = mysqli_fetch_array($query_com);
    ?>
    <tr>
      <td><?php echo $fetch_emp['useri']; ?></td>
      <td><?php echo $arr_ug[$fetch_emp['ug_id']]['name_en']; ?></td>
      <td><?php echo $fetch_com['com_code']; ?></td>
      <td><?php echo $arr_dep[$fetch_emp['dep_id']]['name_en']; ?></td>
      <td><?php echo $arr_posi[$fetch_emp['posi_id']]['name_en']; ?></td>
      <td><?php echo $fetch_emp['emp_manage_a']; ?></td>
      <td><?php echo $fetch_emp['emp_manage_b']; ?></td>
      <td><?php echo $fetch_emp['fname_th']; ?></td>
      <td><?php echo $fetch_emp['lname_th']; ?></td>
      <td><?php echo $fetch_emp['fname_en']; ?></td>
      <td><?php echo $fetch_emp['lname_en']; ?></td>
      <td><?php echo $fetch_emp['u_firstdate']!="0000-00-00 00:00:00"?date('d/m/Y',strtotime($fetch_emp['u_firstdate'])):""; ?></td>
      <td><?php echo $fetch_emp['inactivedate']!="0000-00-00"?date('d/m/Y',strtotime($fetch_emp['inactivedate'])):""; ?></td>
    </tr>
  <?php } 
      }
  ?>
  </tbody>
</table>
 
</body>
</html>-->
