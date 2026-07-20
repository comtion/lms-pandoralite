<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exportdata extends CI_Controller
{

	public function export_departmentandposition()
	{
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->func_query->loadDB();
		$sess = $this->session->userdata("user");

		$arr['emp_c'] = $sess['emp_c'];
		$arr['com_admin'] = $sess['com_admin'];
		$arr['com_id'] = $sess['com_id'];
		$arr['user'] = $sess;
		$whereDepartment = '';
		if ($sess['ug_id'] != "1" && isset($sess['com_id'])) {
			$whereDepartment = ' and lms_depart.com_id = '.$sess['com_id'];
		}
		$fetch_postion = $this->func_query->query_result(
			'lms_position',
			'lms_depart',
			'lms_position.dep_id = lms_depart.dep_id', '',
			'lms_depart.dep_isDelete="0" and lms_position.posi_isDelete = "0" and lms_depart.com_id in (select lms_company.com_id from lms_company where lms_company.com_id != 2 and lms_company.com_status = 1 and lms_company.com_isDelete = 0)'.$whereDepartment, '',
			'(SELECT lms_company.com_code from lms_company where lms_company.com_id = lms_depart.com_id) as com_code,lms_depart.dep_name_th,lms_depart.dep_name_en,lms_position.posi_name_en,lms_position.posi_name_th,lms_depart.dep_modifieddate');
		if (countArray($fetch_postion) > 0) {

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
			$activeSheet->setCellValue('A1', 'Company Code');
			$activeSheet->setCellValue('B1', 'Department ENG');
			$activeSheet->setCellValue('C1', 'Department TH');
			$activeSheet->setCellValue('D1', 'POSITION ENG');
			$activeSheet->setCellValue('E1', 'POSITION TH');
			$activeSheet->setCellValue('F1', 'Last modified date');
			$activeSheet->mergeCells("F1:G1");
			$numrow = 2;
			foreach ($fetch_postion as $key => $value) {
				$activeSheet->setCellValue('A' . $numrow, $value['com_code']);
				$activeSheet->setCellValue('B' . $numrow, $value['dep_name_en']);
				$activeSheet->setCellValue('C' . $numrow, $value['dep_name_th']);
				$activeSheet->setCellValue('D' . $numrow, $value['posi_name_en']);
				$activeSheet->setCellValue('E' . $numrow, $value['posi_name_th']);
				$dep_modifieddate = $value['dep_modifieddate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['dep_modifieddate'])) : "";
				$dep_modifieddateori = $value['dep_modifieddate'] != "0000-00-00 00:00:00" ? $value['dep_modifieddate'] : "";

				if ($dep_modifieddate != "") {
					$activeSheet->setCellValue('F' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($dep_modifieddate));
					$activeSheet->getStyle('F' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('G' . $numrow, date('H:i', strtotime($dep_modifieddate)));
				} else {
					$activeSheet->setCellValue('F' . $numrow, '');
					$activeSheet->setCellValue('G' . $numrow, '');
				}
				$numrow++;
			}
    
			$styleArray = array(
				"borders" => array(
					"allBorders" => array(
						"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				),
			);
	
			$activeSheet->getStyle('A1:G'.($numrow-1))->applyFromArray($styleArray);
			foreach (range("A", "G") as $columnID) {
				$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			$activeSheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center');
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="department_and_position_' . date('Ymd') . '.xlsx"');
			header('Cache-Control: max-age=0');
			$writer->save("php://output");
		}
	}

	public function export_managecourse($com_id)
	{
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

		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');
		//$com_id = isset($_REQUEST['com_id'])?$_REQUEST['com_id']:"";
		$where = 'cos_isDelete="0" and lms_cos.com_id="' . $com_id . '" and lms_company.com_isDelete="0" and lms_company.com_status="1"';
		if ($user['ug_approve'] != "1") {
			$where .= ' and cos_approve="1"';
		}
		$fetch_ug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_id="' . $user['ug_id'] . '"');
		/*if($fetch_ug['ug_viewdata']=="2"){
            $where .= ' and lms_cos.com_id="'.$user['com_id'].'"';
          }else*/
		if ($fetch_ug['ug_viewdata'] == "3") {
			$where .= ' and cos_createby="' . $user['u_id'] . '"';
		}

		$fetch = $this->func_query->query_result('lms_cos', 'lms_company', 'lms_cos.com_id = lms_company.com_id', '', $where, 'cos_approve ASC,cos_id DESC');


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
		$activeSheet->setCellValue('A1', '');
		$activeSheet->setCellValue('B1', 'Course name');
		$activeSheet->setCellValue('C1', 'Support language');
		$activeSheet->setCellValue('D1', 'Course period');
		$activeSheet->setCellValue('E1', 'Number of learners');
		$activeSheet->setCellValue('F1', 'Instructor');
		$activeSheet->setCellValue('G1', 'Company name');
		$activeSheet->setCellValue('H1', 'Approval status');
		$activeSheet->setCellValue('I1', 'Approved date');
		$activeSheet->mergeCells("I1:J1");
		$activeSheet->setCellValue('K1', 'Approver');
		$activeSheet->setCellValue('L1', 'Status');
		$activeSheet->setCellValue('M1', 'Last modified date');
		$activeSheet->mergeCells("M1:N1");
		$activeSheet->setCellValue('O1', 'create date');
		$activeSheet->mergeCells("O1:P1");

		if (countArray($fetch) > 0) {
			foreach ($fetch as $key_list => $value_list) {
				if (isset($fetch[$key_list])) {
					$result_chkcg = $this->func_query->numrows('lms_cosincg', 'lms_cog', 'lms_cosincg.cg_id = lms_cog.cg_id', '', 'lms_cosincg.course_id="' . $value_list['cos_id'] . '" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
					if ($result_chkcg == 0) {
						unset($fetch[$key_list]);
					}
				}
			}
		}
		if (countArray($fetch) > 0) {
			$numrow = 2;
			$num = 1;
			foreach ($fetch as $key => $value) {

				$fetch_cg = $this->func_query->query_result('lms_cog', 'lms_cosincg', 'lms_cosincg.cg_id = lms_cog.cg_id', '', 'course_id = "' . $value['cos_id'] . '" and status_cg="1"');
				$cg_approve_by = array();
				if (countArray($fetch_cg) > 0) {
					foreach ($fetch_cg as $key_cg => $value_cg) {
						if ($value_cg['cg_approve_by'] != "") {
							$arr_approver = explode(',', $value_cg['cg_approve_by']);
							if (countArray($arr_approver) > 0) {
								for ($i = 0; $i < countArray($arr_approver); $i++) {
									$fetch_by = $this->func_query->query_row('lms_usp', '', '', '', 'u_id = "' . $arr_approver[$i] . '"');
									if (countArray($fetch_by) > 0) {
										array_push($cg_approve_by, $fetch_by['emp_id']);
									}
								}
							}
						}
					}
				}
				$fetch_approveby = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id = "' . $value['cos_approveby'] . '"');

				$cname = "";
				$cos_lang = explode(',', $value['cos_lang']);
				$value['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
				$value['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
				$value['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
				if ($lang == "thai") {
					$value['select_lang'] = 'th';
					$value['is_lang_user_th'] = 'selected';
					if ($value['isTH'] == "1") {
						$cname = $value['cname_th'];
					} else {
						if ($cname == "" && $value['isENG'] == "1") {
							$cname = $value['cname_eng'];
						}
						if ($cname == "" && $value['isJP'] == "1") {
							$cname = $value['cname_jp'];
						}
					}
				} else if ($lang == "english") {
					$value['select_lang'] = 'eng';
					$value['is_lang_user_eng'] = 'selected';
					if ($value['isENG'] == "1") {
						$cname = $value['cname_eng'];
					} else {
						if ($cname == "" && $value['isTH'] == "1") {
							$cname = $value['cname_th'];
						}
						if ($cname == "" && $value['isJP'] == "1") {
							$cname = $value['cname_jp'];
						}
					}
				} else {
					$value['select_lang'] = 'jp';
					$value['is_lang_user_jp'] = 'selected';
					if ($value['isJP'] == "1") {
						$cname = $value['cname_jp'];
					} else {
						if ($cname == "" && $value['isENG'] == "1") {
							$cname = $value['cname_eng'];
						}
						if ($cname == "" && $value['isTH'] == "1") {
							$cname = $value['cname_th'];
						}
					}
				}
				$cos_lang = explode(',', $value['cos_lang']);
				$cos_lang_txt = "";
				$cos_lang_arr = explode(',', $value['cos_lang']);
				if (in_array('eng', $cos_lang_arr)) {
					$cos_lang_txt .= "EN";
				}
				if (in_array('th', $cos_lang_arr)) {
					$cos_lang_txt = $cos_lang_txt != "" ? $cos_lang_txt . "," : "";
					$cos_lang_txt .= "TH";
				}
				if (in_array('jp', $cos_lang_arr)) {
					$cos_lang_txt = $cos_lang_txt != "" ? $cos_lang_txt . "," : "";
					$cos_lang_txt .= "JP";
				}
				$cos_approvedate = "";
				$cos_approvedateori = "";

				if (intval($value['cos_public']) == 0) {
					$cos_approve = 'In progress';
					$cos_approvedate = "";
					$cos_approveby = "";
				} else {
					$cos_approve = 'Pending approval';
					if ($value['cos_approve'] == "1") {
						$cos_approve = 'Approved';
						if ($value['cos_approveby'] != "") {
							$fetch_cosapprover = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id = "' . $value['cos_approveby'] . '"');
							if (countArray($fetch_cosapprover) > 0) {
								$cos_approveby = $lang == "thai" ? $fetch_cosapprover['fullname_th'] : $fetch_cosapprover['fullname_en'];
							}
						}
					} else if ($value['cos_approve'] == "2") {
						$cos_approve = 'Rejected';
					} else {
						$cos_approvedate = "";
						$cos_approveby = "";
					}
				}

				$cos_approvedate = $value['cos_approvedate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['cos_approvedate'])) : "";
				$fetch_chkperiod = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $value['cos_id'] . '"');
				$status_cos = 'Open';
				if (countArray($fetch_chkperiod) > 0 && $fetch_chkperiod['date_end'] != "0000-00-00 00:00:00" && date('Y-m-d H:i', strtotime($fetch_chkperiod['date_end'])) < date('Y-m-d H:i')) {
					$status_cos = 'Close';
				} else {
					$status_cos = $value['cos_status'] == "1" ? 'Open' : 'Close';
				}
				if ($cos_approve == 'In progress' || $cos_approve == 'Pending approval') {
					$status_cos = "-";
				}
				$fetch_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id = "' . $value['cos_id'] . '"');
				$period = '-';
				if (countArray($fetch_detail) > 0) {

					if ($fetch_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_detail['date_end'] != "0000-00-00 00:00:00") {
						if ($lang == "thai") {
							$periodstart = $fetch_detail['date_start'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($fetch_detail['date_start'])) . (date('Y', strtotime($fetch_detail['date_start'])) + 543) . " " . date('H:i', strtotime($fetch_detail['date_start'])) : "";
							$periodend = $fetch_detail['date_end'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($fetch_detail['date_end'])) . (date('Y', strtotime($fetch_detail['date_end'])) + 543) . " " . date('H:i', strtotime($fetch_detail['date_end'])) : "";
							$date_end = $periodend;
						} else {
							$periodstart = $fetch_detail['date_start'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($fetch_detail['date_start'])) : "";
							$periodend = $fetch_detail['date_end'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($fetch_detail['date_end'])) : "";
							$date_end = $periodend;
						}
						// $periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_start'])):"";
						// $periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_end'])):"";
						$date_end = $periodend;

						if ($periodstart != "" && $periodend != "") {
							$period = $periodstart . " - " . $periodend;
						}
					}
				}
				$fetch_enroll = $this->func_query->numrows('lms_cos_enroll', '', '', '', 'cos_id="' . $value['cos_id'] . '" and cosen_isDelete="0"');
				/* if($lang=="thai"){
                  $cos_modifieddate = $value['cos_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['cos_modifieddate'])).(date('Y',strtotime($value['cos_modifieddate']))+543)." ".date('H:i',strtotime($value['cos_modifieddate'])):"-";$numloop++;
                  }else{
                  $cos_modifieddate = $value['cos_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['cos_modifieddate'])):"-";$numloop++;
                  }*/

				$cos_modifieddate = $value['cos_modifieddate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['cos_modifieddate'])) : "";
				$cos_createdate = $value['cos_createdate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['cos_createdate'])) : "";
				//$cos_modifieddate = "";
				$cos_modifieddateori = "";

				$fetch_createby = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id = "' . $value['cos_createby'] . '"');
				$cos_modifieddateori = $value['cos_modifieddate'] != "0000-00-00 00:00:00" ? $value['cos_modifieddate'] : "";
				$activeSheet->setCellValue('A' . $numrow, $num);
				$num++;
				$activeSheet->setCellValue('B' . $numrow, $cname);
				$activeSheet->setCellValue('C' . $numrow, $cos_lang_txt);
				$activeSheet->setCellValue('D' . $numrow, $period);
				$activeSheet->setCellValue('E' . $numrow, $fetch_enroll);
				$activeSheet->setCellValue('F' . $numrow, $lang == "thai" ? $fetch_createby['fullname_th'] : $fetch_createby['fullname_en']);
				$activeSheet->setCellValue('G' . $numrow, $value['com_code']);
				$activeSheet->setCellValue('H' . $numrow, $cos_approve);

				if ($cos_approvedate != "") {
					$activeSheet->setCellValue('I' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cos_approvedate));
					$activeSheet->getStyle('I' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('J' . $numrow, date('H:i', strtotime($cos_approvedate)));
				} else {
					$activeSheet->setCellValue('I' . $numrow, '');
					$activeSheet->setCellValue('J' . $numrow, '');
				}
				$activeSheet->setCellValue('K' . $numrow, $cos_approveby);
				$activeSheet->setCellValue('L' . $numrow, $status_cos);

				if ($cos_modifieddate != "") {
					$activeSheet->setCellValue('M' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cos_modifieddate));
					$activeSheet->getStyle('M' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('N' . $numrow, date('H:i', strtotime($cos_modifieddate)));
				} else {
					$activeSheet->setCellValue('M' . $numrow, '');
					$activeSheet->setCellValue('N' . $numrow, '');
				}

				if ($cos_createdate != "") {
					$activeSheet->setCellValue('O' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cos_createdate));
					$activeSheet->getStyle('O' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('P' . $numrow, date('H:i', strtotime($cos_createdate)));
				} else {
					$activeSheet->setCellValue('O' . $numrow, '');
					$activeSheet->setCellValue('P' . $numrow, '');
				}
				$numrow++;
			}
		}

    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:P'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "P") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:P1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Data_allcourse_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function export_managesurvey($com_id)
	{
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

		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');
		//$com_id = isset($_REQUEST['com_id'])?$_REQUEST['com_id']:"";

		$where = 'sv_isDelete="0" and lms_sv.com_id="' . $com_id . '" and lms_company.com_isDelete="0" and lms_company.com_status="1"';
		$fetch_ug = $this->func_query->query_row('lms_usp_gp', '', '', '', 'ug_id="' . $user['ug_id'] . '"');
		if ($fetch_ug['ug_viewdata'] == "3") {
			$where .= ' and sv_createby="' . $user['u_id'] . '"';
		}
		$fetch = $this->func_query->query_result('lms_sv', 'lms_company', 'lms_company.com_id = lms_sv.com_id', '', $where, 'sv_approve ASC,sv_approvedate DESC,sv_id DESC');

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
		$activeSheet->setCellValue('A1', '');
		$activeSheet->setCellValue('B1', 'Support language');
		$activeSheet->setCellValue('C1', 'Survey name');
		$activeSheet->setCellValue('D1', 'Instructor');
		$activeSheet->setCellValue('E1', 'Survey period');
		$activeSheet->setCellValue('F1', 'Approval status');
		$activeSheet->setCellValue('G1', 'Approved date');
		$activeSheet->mergeCells("G1:H1");
		$activeSheet->setCellValue('I1', 'Approver');
		$activeSheet->setCellValue('J1', 'Status');
		$activeSheet->setCellValue('K1', 'Last modified date');
		$activeSheet->mergeCells("K1:L1");

		if (countArray($fetch) > 0) {
			$numrow = 2;
			$num = 1;
			foreach ($fetch as $key => $value) {


				$fetch_createby = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id = "' . $value['sv_createby'] . '"');
				$sv_title = "";
				$sv_lang_txt = "";

				$sv_lang = explode(',', $value['sv_lang']);
				$value['isTH'] = in_array('th', $sv_lang) ? "1" : "0";
				$value['isENG'] = in_array('eng', $sv_lang) ? "1" : "0";
				$value['isJP'] = in_array('jp', $sv_lang) ? "1" : "0";
				if ($lang == "thai") {

					$value['select_lang'] = 'th';
					$value['is_lang_user_th'] = 'selected';
					if ($value['isTH'] == "1") {
						$sv_title = $value['sv_title_th'];
					} else {
						if ($sv_title == "" && $value['isENG'] == "1") {
							$sv_title = $value['sv_title_eng'];
						}
						if ($sv_title == "" && $value['isJP'] == "1") {
							$sv_title = $value['sv_title_jp'];
						}
					}
				} else if ($lang == "english") {

					$value['select_lang'] = 'eng';
					$value['is_lang_user_eng'] = 'selected';
					if ($value['isENG'] == "1") {
						$sv_title = $value['sv_title_eng'];
					} else {
						if ($sv_title == "" && $value['isTH'] == "1") {
							$sv_title = $value['sv_title_th'];
						}
						if ($sv_title == "" && $value['isJP'] == "1") {
							$sv_title = $value['sv_title_jp'];
						}
					}
				} else {
					$value['select_lang'] = 'jp';
					$value['is_lang_user_jp'] = 'selected';
					if ($value['isJP'] == "1") {
						$sv_title = $value['sv_title_jp'];
					} else {
						if ($sv_title == "" && $value['isENG'] == "1") {
							$sv_title = $value['sv_title_eng'];
						}
						if ($sv_title == "" && $value['isTH'] == "1") {
							$sv_title = $value['sv_title_th'];
						}
					}
				}
				$sv_lang_txt = "";
				if ($value['isENG'] == "1") {
					$sv_lang_txt = $sv_lang_txt != "" ? $sv_lang_txt . "," : "";
					$sv_lang_txt .= "EN";
				}
				if ($value['isTH'] == "1") {
					$sv_lang_txt = $sv_lang_txt != "" ? $sv_lang_txt . "," : "";
					$sv_lang_txt .= "TH";
				}
				if ($value['isJP'] == "1") {
					$sv_lang_txt = $sv_lang_txt != "" ? $sv_lang_txt . "," : "";
					$sv_lang_txt .= "JP";
				}

				$sv_period = 'Unlimited time';
				if ($value['sv_open'] != "0000-00-00 00:00:00" && $value['sv_end'] != "0000-00-00 00:00:00") {
					if ($lang == "thai") {
						$sv_open = $value['sv_open'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['sv_open'])) . (date('Y', strtotime($value['sv_open'])) + 543) . " " . date('H:i', strtotime($value['sv_open'])) : "";
						$sv_end = $value['sv_end'] != "0000-00-00 00:00:00" ? date('d/m/', strtotime($value['sv_end'])) . (date('Y', strtotime($value['sv_end'])) + 543) . " " . date('H:i', strtotime($value['sv_end'])) : "";
					} else {
						$sv_open = $value['sv_open'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['sv_open'])) : "";
						$sv_end = $value['sv_end'] != "0000-00-00 00:00:00" ? date('d/m/Y H:i', strtotime($value['sv_end'])) : "";
					}
					$sv_period = $sv_open . " - " . $sv_end;
				}

				if (intval($value['sv_public']) == 0) {
					$sv_approve = 'In progress';
				} else {
					$sv_approve = 'Pending approval';
					if ($user['u_id'] != "1") {
						$update = "";
						if ($user['ug_id'] != "1") {
							$delete = "";
						}
						$question = "";
						//$list_user = "";
					}
					if ($value['sv_approve'] == "1") {
						$sv_approve = 'Approved';
					} else if ($value['sv_approve'] == "2") {
						$sv_approve = 'Rejected';
					}
				}

				$sv_userapprove = explode(",", $value['sv_userapprove']);
				$sv_approve = 'Pending approval';
				$fetch_approve = $this->func_query->query_row('lms_sv_approve', '', '', '', 'sv_id ="' . $value['sv_id'] . '"', 'sva_id DESC');
				if (countArray($fetch_approve) > 0) {
					if ($fetch_approve['sva_approve'] == "1") {
						$sv_approve = 'Approved';
						$approve = "";
					} else if ($fetch_approve['sva_approve'] == "2") {
						if (!in_array($user['emp_id'], $sv_userapprove)) {
							$sv_approve = 'Pending approval';
						}
					} else if ($fetch_approve['sva_approve'] == "3") {
						$approve = "";
						$sv_approve = 'In progress';
					} else {
						$approve = "";
						$sv_approve = 'Rejected';
					}
				} else {
					if (intval($value['sv_public']) == 0) {
						$sv_approve = 'In progress';
					}
				}

				$status_cos = 'Open';
				if ($value['sv_end'] != "0000-00-00 00:00:00" && date('Y-m-d H:i', strtotime($value['sv_end'])) < date('Y-m-d H:i')) {
					$status_cos = 'Close';
				}
				if ($sv_approve == 'In progress' || $sv_approve == 'Pending approval') {
					$status_cos = "-";
				}
				$numrechk_svde = $this->func_query->numrows('lms_svde', '', '', '', 'sv_id = "' . $value['sv_id'] . '" and svde_isDelete="0"');
				if ($numrechk_svde == 0) {
					$status_cos = "-";
				}
				$sv_approveby = "-";
				$sv_approvedate = "";
				$sv_approvedateori = "";
				if ($value['sv_approveby'] != "") {
					$fetch_approver = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.u_id = "' . $value['sv_approveby'] . '"');
					if (countArray($fetch_approver) > 0) {
						$sv_approveby = $lang == "thai" ? $fetch_approver['fullname_th'] : $fetch_approver['fullname_en'];
					}
					$sv_approvedateori = $value['sv_approvedate'] != "0000-00-00 00:00:00" ? $value['sv_approvedate'] : "";
				}
				$sv_approvedate = $value['sv_approvedate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['sv_approvedate'])) : "";
				$sv_modifieddate = $value['sv_modifieddate'] != "0000-00-00 00:00:00" ? date('Y-m-d H:i:s', strtotime($value['sv_modifieddate'])) : "";
				$activeSheet->setCellValue('A' . $numrow, $num);
				$num++;
				$activeSheet->setCellValue('B' . $numrow, $sv_lang_txt);
				$activeSheet->setCellValue('C' . $numrow, $sv_title);
				$activeSheet->setCellValue('D' . $numrow, $lang == "thai" ? $fetch_createby['fullname_th'] : $fetch_createby['fullname_en']);
				$activeSheet->setCellValue('E' . $numrow, $sv_period);
				$activeSheet->setCellValue('F' . $numrow, $sv_approve);
				$activeSheet->setCellValue('G' . $numrow, $sv_approvedate);
				if ($sv_approvedate != "") {
					$activeSheet->setCellValue('G' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($sv_approvedate));
					$activeSheet->getStyle('G' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('H' . $numrow, date('H:i', strtotime($sv_approvedate)));
				} else {
					$activeSheet->setCellValue('G' . $numrow, '');
					$activeSheet->setCellValue('H' . $numrow, '');
				}
				$activeSheet->setCellValue('I' . $numrow, $sv_approveby);
				$activeSheet->setCellValue('J' . $numrow, $status_cos);

				if ($sv_modifieddate != "") {
					$activeSheet->setCellValue('K' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($sv_modifieddate));
					$activeSheet->getStyle('K' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('L' . $numrow, date('H:i', strtotime($sv_modifieddate)));
				} else {
					$activeSheet->setCellValue('K' . $numrow, '');
					$activeSheet->setCellValue('L' . $numrow, '');
				}
				$numrow++;
			}
		}

    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:L'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "L") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Data_allsurvey_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function export_logview($com_id = "", $date_start_var = "", $time_start = "", $date_end_var = "", $time_end = "")
	{
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

		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

		$date_start = isset($date_start_var) && $date_start_var != "" ? $date_start_var . " " . $time_start : "0000-00-00 00:00:00";
		$date_end = isset($date_end_var) && $date_end_var != "" ? $date_end_var . " " . $time_end : "0000-00-00 00:00:00";
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');
		$var_and = "lms_emp.emp_isDelete='0'";
		$var_and .= " and lms_emp.com_id='" . $com_id . "'";
		if (($date_start != "0000-00-00 00:00:00" && $date_end != "0000-00-00 00:00:00")) {
			$var_and .= " and (lms_lg.log_time between '" . $date_start . "' and '" . $date_end . "')";
		}
		/*if($sday!=""&&$eday!=""){
            $this->db->where('lms_lg.log_time >=', date('Y-m-d H:i',strtotime($sday)));
            $this->db->where('lms_lg.log_time <=', date('Y-m-d H:i',strtotime($eday)));
            //$var_and = " and (lms_lg.log_time between '".date('Y-m-d 00:00:00',strtotime($sday))."' and '".date('Y-m-d 23:59:59',strtotime($eday))."')";
          } */
		$this->db->where($var_and);
		$this->db->join('lms_emp', 'lms_lg.emp_id = lms_emp.emp_id');
		$this->db->join('lms_usp', 'lms_usp.emp_id = lms_emp.emp_id');
		$this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', 'LEFT');
		$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
		$this->db->from("lms_lg");
		$this->db->order_by("lms_lg.id DESC");
		$query = $this->db->get();
		$fetch = $query->result_array();

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
		$activeSheet->setCellValue('A1', 'Username');
		$activeSheet->setCellValue('B1', 'Name - last name');
		$activeSheet->setCellValue('C1', 'User group');
		$activeSheet->setCellValue('D1', 'Department');
		$activeSheet->setCellValue('E1', 'IP Address');
		$activeSheet->setCellValue('F1', 'Device');
		$activeSheet->setCellValue('G1', 'Action');
		$activeSheet->setCellValue('H1', 'Date log');
		$activeSheet->setCellValue('I1', 'Time log');

		$numrow = 2;
		if (countArray($fetch) > 0) {
			$num = 1;
			foreach ($fetch as $key => $value) {


				$string_msg = "";
				$fetch_usp =
					/*$pos = strpos($value['massage'], 'logged in website');
                  if($pos === false){
                    $pos = strpos($value['massage'], 'logged in fail');
                    if($pos){
                      $string_msg = "logged in fail";
                    }
                  }else{
                    $string_msg = "logged in website";
                  }*/
					$string_msg = $value['massage'];
				$fullname = "";
				$fullname = $lang == "thai" ? $value['fullname_th'] : $value['fullname_en'];
				$ug_name = $lang == "thai" ? $value['ug_name_th'] : $value['ug_name_en'];
				$dep_name = $lang == "thai" ? $value['dep_name_th'] : $value['dep_name_en'];

				$log_time = $value['log_time'] != "0000-00-00 00:00:00" ? $value['log_time'] : "";
				$activeSheet->setCellValue('A' . $numrow, $value['emp_c']);
				$activeSheet->setCellValue('B' . $numrow, $fullname);
				$activeSheet->setCellValue('C' . $numrow, $ug_name);
				$activeSheet->setCellValue('D' . $numrow, $dep_name);
				$activeSheet->setCellValue('E' . $numrow, $value['ip']);
				$activeSheet->setCellValue('F' . $numrow, $value['device']);
				$activeSheet->setCellValue('G' . $numrow, $string_msg);

				if ($log_time != "") {
					$activeSheet->setCellValue('H' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($log_time));
					$activeSheet->getStyle('H' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('I' . $numrow, date('H:i', strtotime($log_time)));
				} else {
					$activeSheet->setCellValue('H' . $numrow, '');
					$activeSheet->setCellValue('I' . $numrow, '');
				}
				$numrow++;
			}
		}
    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:I'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "I") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Data_logusers_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function export_reportcoursedetail($cos_id = "", $com_id = "")
	{
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

		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');

		$where = '';
		if ($com_id != '') {
			$where = ' and lms_emp.com_id = "' . $com_id . '"';
		}

		$fetch = $this->func_query->query_result('lms_cos_enroll', 'lms_emp', 'lms_cos_enroll.emp_id = lms_emp.emp_id', '', 'cos_id="' . $cos_id . '" and cosen_isDelete="0" and lms_emp.emp_isDelete="0"' . $where);
		//$this->db->where('lms_company.com_admin','com_associated');
		$user = $this->session->userdata('user');
		if (intval($user['ug_id']) > 3) {
			$com_id = $user['com_id'];
		}
		$num = 1;
		$count = 0;
		$fetch_arr = array();
		$fetch_cug = $this->func_query->query_row('lms_cug', '', '', '', 'course_id="' . $cos_id . '"');
		$fetch_qiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_isDelete="0"');
		$fetch_pretest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_type="1" and quiz_status="1" and quiz_isDelete="0"');
		$fetch_posttest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and quiz_type="2" and quiz_status="1" and quiz_isDelete="0"');
		$fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

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
		$activeSheet->setCellValue('A1', 'Username');
		$activeSheet->setCellValue('B1', 'Name - last name');
		$activeSheet->setCellValue('C1', 'Company');
		$activeSheet->setCellValue('D1', 'Department');
		$activeSheet->setCellValue('E1', 'Learning status');
		$activeSheet->setCellValue('F1', 'Pre-test');
		$activeSheet->setCellValue('G1', 'Full score Pre-test');
		$activeSheet->setCellValue('H1', 'Post-test');
		$activeSheet->setCellValue('I1', 'Full score Post-test');
		$activeSheet->setCellValue('J1', 'Test results');
		$activeSheet->setCellValue('K1', 'Completed date');
		$activeSheet->setCellValue('M1', 'Inactive');
		$activeSheet->mergeCells("K1:L1");

		if (countArray($fetch) > 0) {
			$numrow = 2;
			$num = 1;
			foreach ($fetch as $key => $value) {
				$fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id = "' . $value['com_id'] . '"');
				$fetch_user = $this->func_query->query_row('lms_usp', 'lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', '', 'lms_usp.emp_id = "' . $value['emp_id'] . '"');

				$inactive_check = $fetch_user['inactivedate'] != "0000-00-00" && date("Y-m-d") >= date("Y-m-d", strtotime($fetch_user['inactivedate'])) ? "Inactive" : "";

				$cosen_status_sub = "";
				if ($value['cosen_status_sub'] == "0") {
					$cosen_status_sub = "Not start";
				} else if ($value['cosen_status_sub'] == "1") {
					$cosen_status_sub = "Completed";
				} else if ($value['cosen_status_sub'] == "2") {
					if(checkDatetimeIsNull($value['cosen_firsttime'])){
						$cosen_status_sub = "Not start";
					}else{
						$cosen_status_sub = "Ongoing";
					}
				} else {
					$cosen_status_sub = "Not start";
				}
				$score_pretest = 0;
				$score_posttest = 0;
				$score_pretest_full = 0;
				$score_posttest_full = 0;
				if (countArray($fetch_pretest) > 0) {
					foreach ($fetch_pretest as $key_pretest => $value_pretest) {
						$sum_score_all = 0;
						$sum_score_quesall = 0;
						$fetch_chkpretest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_pretest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
						if (countArray($fetch_chkpretest) > 0) {

							$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_pretest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
							if (floatval($fetch_tc['tc_score']) > 0) {
								$score_pretest += floatval($fetch_tc['tc_score']);
							} else {
								$score_pretest += floatval($fetch_chkpretest['sum_score']);
							}
							$sum_score_all += floatval($fetch_tc['ques_score']);
						} else {
							$fetch_chkques = $this->func_query->query_row('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(lms_ques.ques_score) as ques_score');
							if (countArray($fetch_chkques) > 0) {
								$sum_score_quesall += floatval($fetch_chkques['ques_score']);
							}
						}

						$fetch_sum = countArray($fetch_chkpretest) > 0 ? $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score') : array();
						if (countArray($fetch_sum) > 0) {
							$score_pretest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
						} else {
							$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
						}
					}
				}
				if (countArray($fetch_posttest) > 0) {
					foreach ($fetch_posttest as $key_posttest => $value_posttest) {
						$sum_score_all = 0;
						$sum_score_quesall = 0;

						$fetch_chkposttest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_posttest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
						if (countArray($fetch_chkposttest) > 0) {

							$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_posttest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
							if (floatval($fetch_tc['tc_score']) > 0) {
								$score_posttest += floatval($fetch_tc['tc_score']);
							} else {
								$score_posttest += floatval($fetch_chkposttest['sum_score']);
							}
							$sum_score_all += floatval($fetch_tc['ques_score']);
						} else {
							$fetch_chkques = $this->func_query->query_row('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(lms_ques.ques_score) as ques_score');
							if (countArray($fetch_chkques) > 0) {
								$sum_score_quesall += floatval($fetch_chkques['ques_score']);
							}
						}

						$fetch_sum = countArray($fetch_chkposttest) > 0 ? $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score') : array();
						if (countArray($fetch_sum) > 0) {
							$score_posttest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
						} else {
							$score_posttest_full += $sum_score_quesall;
						}
					}
				}

				if ($fetch_qiz == 0) {
					$score_posttest = 0;
					$score_posttest_full = 0;
				}
				$preReport = '-';
				$var_rechk = 1;
				$fetch_chkques_shlo = $this->func_query->query_result('lms_ques', '', '', '', 'ques_type in ("sub","sa") and qiz_id in (select lms_qiz.qiz_id from lms_qiz where lms_qiz.cos_id = "' . $value['cos_id'] . '") and ques_isDelete="0"');
				if (countArray($fetch_chkques_shlo) > 0) {
					foreach ($fetch_chkques_shlo as $key_chkques_shlo => $value_chkques_shlo) {
						$fetch_chktc = $this->func_query->query_row('lms_ques_tc', '', '', '', 'lms_ques_tc.ques_id="' . $value_chkques_shlo['ques_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '"', 'lms_ques_tc.tc_id DESC');
						if (countArray($fetch_chktc) > 0) {
							if ($fetch_chktc['tc_isSavescore'] == "0") {
								$var_rechk = 0;
							}
						}
					}
				}
				if ($value['cosen_status_sub'] == "1") {

					if ($fetch_cos['cos_typegrading'] == "1") {
						$cosen_score_per = (floatval($value['cosen_score']) / $score_posttest_full * 100);
						if ($cosen_score_per >= floatval($fetch_cug['mina'])) {
							$cosen_grade = "A";
						} else if ($cosen_score_per >= floatval($fetch_cug['minb'])) {
							$cosen_grade = "B";
						} else if ($cosen_score_per >= floatval($fetch_cug['minc'])) {
							$cosen_grade = "C";
						} else if ($cosen_score_per >= floatval($fetch_cug['mind'])) {
							$cosen_grade = "D";
						} else {
							$cosen_grade = "F";
						}
						$preReport = $value['cosen_grade'] != "" ? $value['cosen_grade'] : $cosen_grade;
					} else {
						if ($score_posttest_full == 0 || (floatval($value['cosen_score']) / $score_posttest_full * 100) >= intval($fetch_cos['goal_score'])) {
							$preReport = "Pass";
						} else {
							$preReport = "Fail";
						}
					}
				}

				$cosen_finishtime = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? $value['cosen_finishtime'] : "";

				$activeSheet->setCellValue('A' . $numrow, $value['emp_c']);
				$activeSheet->setCellValue('B' . $numrow, $lang == "thai" ? $value['fullname_th'] : $value['fullname_en']);
				$activeSheet->setCellValue('C' . $numrow, $lang == "thai" ? $fetch_company['com_name_th'] : $fetch_company['com_name_eng']);
				$activeSheet->setCellValue('D' . $numrow, $lang == "thai" ? $fetch_user['dep_name_th'] : $fetch_user['dep_name_en']);
				$activeSheet->setCellValue('E' . $numrow, $cosen_status_sub);
				$activeSheet->setCellValue('F' . $numrow, $score_pretest);
				$activeSheet->setCellValue('G' . $numrow, $score_pretest_full);
				$activeSheet->setCellValue('H' . $numrow, $score_posttest);
				$activeSheet->setCellValue('I' . $numrow, $score_posttest_full);
				$activeSheet->setCellValue('J' . $numrow, $preReport);

				if ($cosen_finishtime != "") {
					$activeSheet->setCellValue('K' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cosen_finishtime));
					$activeSheet->getStyle('K' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('L' . $numrow, date('H:i', strtotime($cosen_finishtime)));
				} else {
					$activeSheet->setCellValue('K' . $numrow, '');
					$activeSheet->setCellValue('L' . $numrow, '');
				}


				$activeSheet->setCellValue('M' . $numrow, $inactive_check);
				$numrow++;
			}
		}
    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:M'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "M") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:M1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Data_reportcoursedetail_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function export_reportpersonal($course_status = "", $cosen_status_sub = "", $date_start = "", $date_end = "", $time_start = "", $time_end = "")
	{
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

		$date_start = isset($date_start) && $date_start != "" ? $date_start . " " . $time_start : "";
		$date_end = isset($date_end) && $date_end != "" ? $date_end . " " . $time_end : "";
		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');

		$date_start = $date_start != "" && $date_start != "0000-00-00 00:00" ? $date_start : '';
		$date_end = $date_end != "" && $date_end != "0000-00-00 23:59" ? $date_end : date('Y-m-d H:i');
		$this->db->from('lms_cos_enroll');
		$this->db->join('lms_cos', 'lms_cos_enroll.cos_id = lms_cos.cos_id');
		$this->db->join('lms_company', 'lms_cos.com_id = lms_company.com_id');
		$this->db->join('lms_emp', 'lms_cos_enroll.emp_id = lms_emp.emp_id');
		$this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
		$this->db->where('lms_cos.cos_isDelete', '0');
		$this->db->where('lms_emp.emp_isDelete', '0');
		$this->db->where('lms_company.com_isDelete', '0');
		$this->db->where('lms_cos_enroll.cosen_isDelete', '0');
		$this->db->where('lms_cos.cos_public', '1');
		$this->db->where('lms_cos.cos_approve', '1');
		if ($course_status != "" && $course_status != "4") {
			if ($course_status == "1") {
				//$this->db->where('lms_cos.cos_status','1');
				$where = 'lms_cos.cos_id in (select lms_cos_detail.cos_id from lms_cos_detail where ((lms_cos_detail.date_end="0000-00-00 00:00:00") or (lms_cos_detail.date_end >= "' . $date_end . '")) and cos_status="1" and cosde_isDelete="0")';
				$this->db->where($where);
			} else {
				//$this->db->where('lms_cos.cos_status','0');
				$where = 'lms_cos.cos_id in (select lms_cos_detail.cos_id from lms_cos_detail where lms_cos_detail.date_end!="0000-00-00 00:00:00" and lms_cos_detail.date_end < "' . $date_end . '" and cosde_status="1" and cosde_isDelete="0")';
				$this->db->where($where);
			}
		}
		if ($cosen_status_sub != "" && $cosen_status_sub != "4") {
			if ($cosen_status_sub == "0") {
				$this->db->where('lms_cos_enroll.cosen_status_sub', '0');
				//$this->db->where('lms_cos_enroll.cosen_firsttime','0000-00-00 00:00:00');
			} else if ($cosen_status_sub == "2") {
				// $this->db->where('lms_cos_enroll.cosen_firsttime!=','0000-00-00 00:00:00');
				$this->db->where('lms_cos_enroll.cosen_status_sub', '2');
			} else if ($cosen_status_sub == "1") {
				$this->db->where('lms_cos_enroll.cosen_status_sub', $cosen_status_sub);
			}
		}
		if ($date_start != "" && $date_end != "") {
			$where = "(lms_cos_enroll.cosen_finishtime BETWEEN '" . $date_start . "' AND '" . $date_end . "')";
			$this->db->where($where);
		}
		$this->db->order_by('lms_cos_enroll.cosen_id DESC');
		$query = $this->db->get();
		$fetch = $query->result_array();

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
		$activeSheet->setCellValue('A1', '');
		$activeSheet->setCellValue('B1', 'Course name');
		$activeSheet->setCellValue('C1', 'Course status');
		$activeSheet->setCellValue('D1', 'Learning status');
		$activeSheet->setCellValue('E1', 'Pre-test');
		$activeSheet->setCellValue('F1', 'Full score Pre-test');
		$activeSheet->setCellValue('G1', 'Post-test');
		$activeSheet->setCellValue('H1', 'Full score Post-test');
		$activeSheet->setCellValue('I1', 'Test results');
		$activeSheet->setCellValue('J1', 'Completed date');
		$activeSheet->mergeCells("J1:K1");

		/*
          if($course_status!=""){
            if($course_status=="1"){
              foreach ($fetch as $key => $value) {
                $result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
                if($result_chkcg==0){
                  unset($fetch[$key]);
                }
              }
            }
          }
*/
		$numrow = 2;
		$num = 1;
		if (countArray($fetch) > 0) {
			foreach ($fetch as $key => $value) {
				$fetch_qiz = $this->func_query->numrows('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_isDelete="0"');
				$fetch_pretest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_type="1" and quiz_status="1" and quiz_isDelete="0"');
				$fetch_posttest = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $value['cos_id'] . '" and quiz_type="2" and quiz_status="1" and quiz_isDelete="0"');
				$average_score = 0;
				if ($lang == "thai") {
					$cname = $value['cname_th'] != "" ? $value['cname_th'] : $value['cname_eng'];
					$cname = $cname != "" ? $cname : $value['cname_jp'];
				} else if ($lang == "english") {
					$cname = $value['cname_eng'] != "" ? $value['cname_eng'] : $value['cname_th'];
					$cname = $cname != "" ? $cname : $value['cname_jp'];
				} else {
					$cname = $value['cname_jp'] != "" ? $value['cname_jp'] : $value['cname_eng'];
					$cname = $cname != "" ? $cname : $value['cname_th'];
				}
				$where_shlg = 'cos_id = "' . $value['cos_id'] . '" and qiz_id in (select lms_ques.qiz_id from lms_ques where ques_type in ("sa","sub") and ques_isDelete="0")';
				$fetch_chk_shlg = $this->func_query->numrows('lms_qiz', '', '', '', $where_shlg);

				$output['cname'] = $cname;
				$fetch_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $value['cos_id'] . '" and cosde_isDelete="0"');
				$cos_status = "Open";
				if (countArray($fetch_detail) > 0) {
					if ($fetch_detail['date_end'] != "0000-00-00 00:00:00" && date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($fetch_detail['date_end']))) {
						$cos_status = "Close";
					}
				}
				if ($value['cos_status'] == "0") {
					$cos_status = "Close";
				}
				if ($value['cosen_status_sub'] == "0") {
					$cosen_status_sub = "Not start";
				} else if ($value['cosen_status_sub'] == "1") {
					$cosen_status_sub = "Completed";
				} else if ($value['cosen_status_sub'] == "2") {
					if(checkDatetimeIsNull($value['cosen_firsttime'])){
						$cosen_status_sub = "Not start";
					}else{
						$cosen_status_sub = "Ongoing";
					}
				} else {
					$cosen_status_sub = "Not start";
				}
				$score_pretest = 0;
				$score_posttest = 0;
				$score_pretest_full = 0;
				$score_posttest_full = 0;
				if (countArray($fetch_pretest) > 0) {
					foreach ($fetch_pretest as $key_pretest => $value_pretest) {
						$sum_score_all = 0;
						$sum_score_quesall = 0;
						$fetch_chkpretest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_pretest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
						if (countArray($fetch_chkpretest) > 0) {

							// $fetch_chkques = $this->func_query->query_result('lms_ques','','','','lms_ques.qiz_id="'.$value_pretest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
							// if(countArray($fetch_chkques)>0){
							//     foreach ($fetch_chkques as $key_chkques => $value_chkques) {
							//         $fetch_tc = $this->func_query->query_row('lms_ques_tc','','','','lms_ques_tc.ques_id="'.$value_chkques['ques_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkpretest['qiztc_id'].'"');
							//         if(countArray($fetch_tc)>0){
							//         $score_pretest+=floatval($fetch_tc['tc_score']);
							//         }else{
							//         $score_pretest+=0;
							//         }
							//         $sum_score_all+=floatval($value_chkques['ques_score']);
							//     }
							// }
							$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_pretest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkpretest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
							if (floatval($fetch_tc['tc_score']) > 0) {
								$score_pretest += floatval($fetch_tc['tc_score']);
							} else {
								$score_pretest += floatval($fetch_chkpretest['sum_score']);
							}
						} else {
							$fetch_chkques = $this->func_query->query_result('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_pretest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0"');
							if (countArray($fetch_chkques) > 0) {
								foreach ($fetch_chkques as $key_chkques => $value_chkques) {
									$sum_score_quesall += floatval($value_chkques['ques_score']);
								}
							}
						}
						$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_pretest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_pretest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
						if (countArray($fetch_sum) > 0) {
							$score_pretest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
						} else {
							$score_pretest_full += $value_pretest['quiz_numofshown'] == countArray($fetch_chkques) ? $sum_score_all : $sum_score_quesall;
						}
					}
				}
				if (countArray($fetch_posttest) > 0) {
					foreach ($fetch_posttest as $key_posttest => $value_posttest) {
						$sum_score_all = 0;
						$sum_score_quesall = 0;
						$fetch_chkposttest = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'lms_qiz_tc.cosen_id="' . $value['cosen_id'] . '" and lms_qiz_tc.qiz_id="' . $value_posttest['qiz_id'] . '" and qiztc_isDelete="0" and qiz_status="3"', 'qiztc_id DESC');
						if (countArray($fetch_chkposttest) > 0) {

							// $fetch_chkques = $this->func_query->query_result('lms_ques','','','','lms_ques.qiz_id="'.$value_posttest['qiz_id'].'" and ques_status="1" and ques_isDelete="0"');
							// if(countArray($fetch_chkques)>0){
							//     foreach ($fetch_chkques as $key_chkques => $value_chkques) {
							//         $fetch_tc = $this->func_query->query_row('lms_ques_tc','','','','lms_ques_tc.ques_id="'.$value_chkques['ques_id'].'"  and lms_ques_tc.cosen_id="'.$value['cosen_id'].'" and lms_ques_tc.qiztc_id="'.$fetch_chkposttest['qiztc_id'].'"');
							//         if(countArray($fetch_tc)>0){
							//         $score_posttest+=floatval($fetch_tc['tc_score']);
							//         }else{
							//         $score_posttest+=0;
							//         }
							//         $sum_score_all+=floatval($value_chkques['ques_score']);
							//     }
							// }
							$fetch_tc = $this->func_query->query_row('lms_ques_tc', 'lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id', '', 'lms_ques_tc.qiz_id="' . $value_posttest['qiz_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '" and lms_ques_tc.qiztc_id="' . $fetch_chkposttest['qiztc_id'] . '"', '', 'SUM(lms_ques_tc.tc_score) as tc_score, SUM(lms_ques.ques_score) as ques_score');
							if (floatval($fetch_tc['tc_score']) > 0) {
								$score_posttest += floatval($fetch_tc['tc_score']);
							} else {
								$score_posttest += floatval($fetch_chkposttest['sum_score']);
							}
						} else {
							$fetch_chkques = $this->func_query->query_result('lms_ques', '', '', '', 'lms_ques.qiz_id="' . $value_posttest['qiz_id'] . '" and ques_status="1" and ques_isDelete="0"');
							if (countArray($fetch_chkques) > 0) {
								foreach ($fetch_chkques as $key_chkques => $value_chkques) {
									$sum_score_quesall += floatval($value_chkques['ques_score']);
								}
							}
						}
						$fetch_sum = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $value_posttest['qiz_id'] . '" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="' . $value_posttest['qiz_id'] . '" and cosen_id="' . $value['cosen_id'] . '") and ques_status="1" and ques_isDelete="0"', '', 'SUM(ques_score) as total_score');
						if (countArray($fetch_sum) > 0) {
							$score_posttest_full += countArray($fetch_sum) > 0 && floatval($fetch_sum['total_score']) > 0 ? floatval($fetch_sum['total_score']) : $sum_score_quesall;
						} else {
							$score_posttest_full += $sum_score_quesall;
						}
					}
				}

				if ($fetch_qiz == 0) {
					$score_posttest = 0;
					$score_posttest_full = 0;
				}
				$preReport = '-';
				$var_rechk = 1;
				$fetch_chkques_shlo = $this->func_query->query_result('lms_ques', '', '', '', 'ques_type in ("sub","sa") and qiz_id in (select lms_qiz.qiz_id from lms_qiz where lms_qiz.cos_id = "' . $value['cos_id'] . '") and ques_isDelete="0"');
				if (countArray($fetch_chkques_shlo) > 0) {
					foreach ($fetch_chkques_shlo as $key_chkques_shlo => $value_chkques_shlo) {
						$fetch_chktc = $this->func_query->query_row('lms_ques_tc', '', '', '', 'lms_ques_tc.ques_id="' . $value_chkques_shlo['ques_id'] . '"  and lms_ques_tc.cosen_id="' . $value['cosen_id'] . '"', 'lms_ques_tc.tc_id DESC');
						if (countArray($fetch_chktc) > 0) {
							if ($fetch_chktc['tc_isSavescore'] == "0") {
								$var_rechk = 0;
							}
						}
					}
				}
				if ($value['cosen_status_sub'] == "1" && $var_rechk == 1) {

					if ($value['cos_typegrading'] == "1") {
						$preReport = $value['cosen_grade'] != "" ? $value['cosen_grade'] : '-';
					} else {
						if ($score_posttest_full == 0 || (floatval($value['cosen_score']) / $score_posttest_full * 100) >= intval($value['goal_score'])) {
							$preReport = "Pass";
						} else {
							$preReport = "Fail";
						}
					}
				}
				$cosen_finishtime = $value['cosen_finishtime'] != "0000-00-00 00:00:00" ? $value['cosen_finishtime'] : "";

				$activeSheet->setCellValue('A' . $numrow, $num);
				$num++;
				$activeSheet->setCellValue('B' . $numrow, $cname);
				$activeSheet->setCellValue('C' . $numrow, $cos_status);
				$activeSheet->setCellValue('D' . $numrow, $cosen_status_sub);
				$activeSheet->setCellValue('E' . $numrow, $score_pretest);
				$activeSheet->setCellValue('F' . $numrow, $score_pretest_full);
				$activeSheet->setCellValue('G' . $numrow, $score_posttest);
				$activeSheet->setCellValue('H' . $numrow, $score_posttest_full);
				$activeSheet->setCellValue('I' . $numrow, $preReport);

				if ($cosen_finishtime != "") {
					$activeSheet->setCellValue('J' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cosen_finishtime));
					$activeSheet->getStyle('J' . $numrow)
						->getNumberFormat()
						->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$activeSheet->setCellValue('K' . $numrow, date('H:i', strtotime($cosen_finishtime)));
				} else {
					$activeSheet->setCellValue('J' . $numrow, '');
					$activeSheet->setCellValue('K' . $numrow, '');
				}
				$numrow++;
			}
		}
    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:K'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "K") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Data_reportcoursepersonal_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}


	public function export_user($com_id = "")
	{
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

		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

		$date_start = isset($date_start_var) && $date_start_var != "" ? $date_start_var . " " . $time_start : "0000-00-00 00:00:00";
		$date_end = isset($date_end_var) && $date_end_var != "" ? $date_end_var . " " . $time_end : "0000-00-00 00:00:00";
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');
		$this->db->from('lms_usp');
		$this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
		//$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id','RIGHT');
		$this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
		$this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
		//$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id','RIGHT');
		$this->db->where('lms_emp.emp_isDelete', '0');

		$user = $this->session->userdata('user');
		//if(!in_array($user['useri'], array('admin_verztec','support_verztec'))){
		$this->db->where('lms_usp.useri not in ("admin_verztec","support_verztec")');
		// }
		if ($com_id != "") {

			$this->db->where('lms_company.com_id', $com_id);
		}
		$query = $this->db->get();
		$fetch = $query->result_array();
		$arr_ug = array();
		$arr_depart = array();
		$arr_posi = array();

		$query_ug = $this->func_query->query_result('lms_usp_gp', '', '', '', '');
		foreach ($query_ug as $key => $fetch_ug) {
			$arr_ug[$fetch_ug['ug_id']]['name_th'] = $fetch_ug['ug_name_th'];
			$arr_ug[$fetch_ug['ug_id']]['name_en'] = $fetch_ug['ug_name_en'];
		}
		$query_dep = $this->func_query->query_result('lms_depart', '', '', '', '');
		foreach ($query_dep as $key => $fetch_dep) {
			$arr_depart[$fetch_dep['dep_id']]['name_th'] = $fetch_dep['dep_name_th'];
			$arr_depart[$fetch_dep['dep_id']]['name_en'] = $fetch_dep['dep_name_en'];
		}
		$query_posi = $this->func_query->query_result('lms_position', '', '', '', '');
		foreach ($query_posi as $key => $fetch_posi) {
			$arr_posi[$fetch_posi['posi_id']]['name_th'] = $fetch_posi['posi_name_th'];
			$arr_posi[$fetch_posi['posi_id']]['name_en'] = $fetch_posi['posi_name_en'];
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
		$activeSheet->getColumnDimension('N')->setAutoSize(true);
		$activeSheet->getColumnDimension('O')->setAutoSize(true);
		$activeSheet->getColumnDimension('P')->setAutoSize(true);
		$activeSheet->getColumnDimension('Q')->setAutoSize(true);
		$activeSheet->getColumnDimension('R')->setAutoSize(true);
		$activeSheet->getColumnDimension('S')->setAutoSize(true);
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
		$activeSheet->setCellValue('N1', 'Create date');
		$activeSheet->setCellValue('O1', 'Create time');
		$activeSheet->setCellValue('P1', 'Create name');
		$activeSheet->setCellValue('Q1', 'Updated date');
		$activeSheet->setCellValue('R1', 'Updated time');
		$activeSheet->setCellValue('S1', 'Updated name');

		if (countArray($fetch) > 0) {
			$numrow = 2;
			$num = 1;
			foreach ($fetch as $key => $value) {
				if (isset($arr_ug[$value['ug_id']]) && isset($arr_depart[$value['dep_id']]) && isset($arr_posi[$value['posi_id']])) {
					$u_firstdate = $value['u_firstdate'] != "0000-00-00 00:00:00" && date('Y', strtotime($value['u_firstdate'])) != "1900" ? date('Y-m-d', strtotime($value['u_firstdate'])) : "";
					$u_createdate = $value['u_createdate'] != "0000-00-00 00:00:00" && date('Y', strtotime($value['u_createdate'])) != "1900" ? date('Y-m-d', strtotime($value['u_createdate'])) : "";
					$u_modifieddate = $value['u_modifieddate'] != "0000-00-00 00:00:00" && date('Y', strtotime($value['u_modifieddate'])) != "1900" ? date('Y-m-d', strtotime($value['u_modifieddate'])) : "";
					$inactivedate = $value['inactivedate'] != "0000-00-00" ? date('Y-m-d', strtotime($value['inactivedate'])) : "";
					$activeSheet->setCellValue('A' . $numrow, $value['useri']);
					$activeSheet->setCellValue('B' . $numrow, $arr_ug[$value['ug_id']]['name_en']);
					$activeSheet->setCellValue('C' . $numrow, $value['com_code']);
					$activeSheet->setCellValue('D' . $numrow, $arr_depart[$value['dep_id']]['name_en']);
					$activeSheet->setCellValue('E' . $numrow, $arr_posi[$value['posi_id']]['name_en']);
					$activeSheet->setCellValue('F' . $numrow, $value['emp_manage_a']);
					$activeSheet->setCellValue('G' . $numrow, $value['emp_manage_b']);
					$activeSheet->setCellValue('H' . $numrow, $value['fname_th']);
					$activeSheet->setCellValue('I' . $numrow, $value['lname_th']);
					$activeSheet->setCellValue('J' . $numrow, $value['fname_en']);
					$activeSheet->setCellValue('K' . $numrow, $value['lname_en']);
					if ($u_firstdate != "" && $u_firstdate != "0000-00-00") {
						$activeSheet->setCellValue('L' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($u_firstdate));
						$activeSheet->getStyle('L' . $numrow)
							->getNumberFormat()
							->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					} else {
						$activeSheet->setCellValue('L' . $numrow, '');
					}
					if ($inactivedate != "" && $inactivedate != "0000-00-00") {
						$activeSheet->setCellValue('M' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($inactivedate));
						$activeSheet->getStyle('M' . $numrow)
							->getNumberFormat()
							->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					} else {
						$activeSheet->setCellValue('M' . $numrow, '');
					}
					if ($u_createdate != "" && $u_createdate != "0000-00-00") {
						$activeSheet->setCellValue('N' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($u_createdate));
						$activeSheet->getStyle('N' . $numrow)
							->getNumberFormat()
							->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					} else {
						$activeSheet->setCellValue('N' . $numrow, '');
					}
					$u_createtime = $value['u_createdate'] != "0000-00-00 00:00:00" && date('Y', strtotime($value['u_createdate'])) != "1900" ? date('H:i', strtotime($value['u_createdate'])) : "";
					$activeSheet->setCellValue('O' . $numrow, $u_createtime);
					$fetch_create = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_usp.u_id = "' . $value['u_createby'] . '"');
					$namecreate = $fetch_create['useri'];
					$activeSheet->setCellValue('P' . $numrow, $namecreate);

					if ($u_modifieddate != "" && $u_modifieddate != "0000-00-00") {
						$activeSheet->setCellValue('Q' . $numrow, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($u_modifieddate));
						$activeSheet->getStyle('Q' . $numrow)
							->getNumberFormat()
							->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					} else {
						$activeSheet->setCellValue('Q' . $numrow, '');
					}
					$u_createtime = $value['u_modifieddate'] != "0000-00-00 00:00:00" && date('Y', strtotime($value['u_modifieddate'])) != "1900" ? date('H:i', strtotime($value['u_modifieddate'])) : "";
					$activeSheet->setCellValue('R' . $numrow, $u_createtime);
					$fetch_modified = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_usp.u_id = "' . $value['u_modifiedby'] . '"');
					$namemodified = isset($fetch_modified['useri']) ? $fetch_modified['useri'] : "";
					$activeSheet->setCellValue('S' . $numrow, $namemodified);

					$numrow++;
				}
			}
		}
    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:S'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "S") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:S1')->getAlignment()->setHorizontal('center');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="User_Information_' . date('Ymd') . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function exportLogEmail($comId = "", $statusEvent = "", $dateStart = "", $timeStart = "", $dateEnd = "", $timeEnd = "") {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Function_query_model', 'funcQuery', false);
		$this->funcQuery->loadDB();

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
		$activeSheet->setCellValue('A1', label('m_company'));
		$activeSheet->setCellValue('B1', label('username'));
		$activeSheet->setCellValue('C1', label('name'));
		$activeSheet->setCellValue('D1', label('m_usergroup'));
		$activeSheet->setCellValue('E1', label('m_department'));
		$activeSheet->setCellValue('F1', label('ip_add'));
		$activeSheet->setCellValue('G1', label('email_subject'));
		$activeSheet->setCellValue('H1', label('com_createdate'));
		$activeSheet->setCellValue('I1', label('statusLogEmail'));
		
		$numrow = 2;
		$arrLogEmail = array();
		$arrEmployees = array();
		$arrUsergroup = array();
		$arrDepartment = array();

		$fetchUsergroups = $this->funcQuery->query_result("lms_usp_gp");
		if (!empty($fetchUsergroups)) {
			foreach ($fetchUsergroups as $keyUsergroup) {
				$arrUsergroup[$keyUsergroup["ug_id"]] = $lang == "thai" ? $keyUsergroup["ug_name_th"] : $keyUsergroup["ug_name_en"];
			}
		}

		$fetchDepartments = $this->funcQuery->query_result("lms_depart");
		if (!empty($fetchDepartments)) {
			foreach ($fetchDepartments as $keyDepartment) {
				$arrDepartment[$keyDepartment["dep_id"]] = $lang == "thai" ? $keyDepartment["dep_name_th"] : $keyDepartment["dep_name_en"];
			}
		}

		$arrCompany = array();
		$fetchCompanys = $this->funcQuery->query_result("lms_company");
		if (!empty($fetchCompanys)) {
		  foreach ($fetchCompanys as $keyCompany) {
			  $arrCompany[$keyCompany["com_id"]] = $keyCompany["com_code"];
		  }
		}
		$where = "";
		if ($comId != "" && $comId != "All") {
			$where = "lms_emp.com_id = ".$comId;
		}
		$fetchEmps = $this->funcQuery->query_result(
			"lms_emp",
			"lms_usp",
			"lms_emp.emp_id = lms_usp.emp_id", "", $where, "",
			"lms_emp.emp_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri, lms_usp.ug_id, lms_usp.dep_id"
		);
		if (!empty($fetchEmps)) {
			foreach ($fetchEmps as $keyEmp) {
				if (isset($arrCompany[$keyEmp["com_id"]])) {
					$arrEmployees[$keyEmp["useri"]] = array(
						"company"   => $arrCompany[$keyEmp["com_id"]],
						"username"  => $keyEmp["useri"],
						"fullname"  => $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"],
						"depName"   => isset($arrDepartment[$keyEmp["dep_id"]]) ? $arrDepartment[$keyEmp["dep_id"]] : "-",
						"ugName"    => isset($arrUsergroup[$keyEmp["ug_id"]]) ? $arrUsergroup[$keyEmp["ug_id"]] : "-"
					);
				}
			}
		}

		$fetchLogEmail = $this->funcQuery->query_result(
			"lms_lg_email", "", "", "",
			"lgm_date between '".$dateStart."' and '".$dateEnd."'"
		);
		if (!empty($fetchLogEmail)) {
			foreach ($fetchLogEmail as $keyLogEmail) {
				if ($keyLogEmail["lgm_json"] != "") {
					$arrJson = (array) json_decode($keyLogEmail["lgm_json"]);
					if (!empty($arrJson)) {
						foreach ($arrJson as $keyJson) {
							$dataRow = (array) $keyJson;
							if (isset($dataRow["date"])) {
								$dataRow["date"] = date("Y-m-d H:i:s", strtotime($dataRow["date"]));

								$isPass = true;
								if ($statusEvent != "" && $statusEvent != "All") {
									if ($statusEvent != $dataRow["event"]) {
										$isPass = false;
									}
								}

								if (!(date("Y-m-d H:i:00", strtotime($dateStart." ".$timeStart)) <= $dataRow["date"] && date("Y-m-d H:i:00", strtotime($dateEnd." ".$timeEnd)) >= $dataRow["date"])) {
									$isPass = false;
								}

								if (isset($arrEmployees[$dataRow["email"]]) && $isPass) {
									$dataEmp = $arrEmployees[$dataRow["email"]];

									$dateDisplay = date("d/m/Y H:i:s", strtotime($dataRow["date"]));
									if ($lang == "thai") {
										$dateDisplay = date('d/m',strtotime($dataRow['date']))."/".(date('Y',strtotime($dataRow['date']))+543)." ".date('H:i:s',strtotime($dataRow['date']));
									}

									$activeSheet->setCellValue('A'.$numrow, $dataEmp["company"]);
									$activeSheet->setCellValue('B'.$numrow, $dataRow["email"]);
									$activeSheet->setCellValue('C'.$numrow, $dataEmp["fullname"]);
									$activeSheet->setCellValue('D'.$numrow, $dataEmp["ugName"]);
									$activeSheet->setCellValue('E'.$numrow, $dataEmp["depName"]);
									$activeSheet->setCellValue('F'.$numrow, isset($dataRow["ip"]) ? $dataRow["ip"] : '');
									$activeSheet->setCellValue('G'.$numrow, $dataRow["subject"]);
									$activeSheet->setCellValue('H'.$numrow, $dateDisplay);
									$activeSheet->setCellValue('I'.$numrow, label('email_'.$dataRow["event"]));
									$numrow++;
								}
							}
						}
					}
				}
			}
		}
		
    
		$styleArray = array(
			"borders" => array(
				"allBorders" => array(
					"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$activeSheet->getStyle('A1:I'.($numrow-1))->applyFromArray($styleArray);
		foreach (range("A", "I") as $columnID) {
			$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$activeSheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');

		$periodText = $dateStart." - ".$dateEnd;
		if ($dateStart == $dateEnd) {
			$periodText = $dateStart;
		}

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Log_email_(' . $periodText . ').xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save("php://output");
	}

	public function exportLogImportUser($lgiId = "", $comId = "") {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'funcQuery', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');

		$fetchArr = array();
		$arrEmployees = array();
		$arrCompanys = array();
		if (isset($user["emp_id"])) {
			$fetchCompanys = $this->funcQuery->query_result("lms_company");
			if (!empty($fetchCompanys)) {
				foreach ($fetchCompanys as $keyCompany) {
					$isPassCompany = true;
					if ($comId != "" && $comId != "All" && $comId != $keyCompany["com_id"]) {
						$isPassCompany = false;
					}
					if ($isPassCompany) {
						$arrCompanys[$keyCompany["com_id"]] = $lang == "thai" ? $keyCompany["com_name_th"] : $keyCompany["com_name_eng"];
					}
				}
			}
			$fetchEmps = $this->funcQuery->query_result(
				"lms_emp",
				"lms_usp",
				"lms_emp.emp_id = lms_usp.emp_id", "", "", "",
				"lms_emp.emp_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri"
			);
			if (!empty($fetchEmps)) {
				foreach ($fetchEmps as $keyEmp) {
					if (isset($arrCompanys[$keyEmp["com_id"]])) {
						$arrEmployees[$keyEmp["emp_id"]] = array(
							"company"	=> $arrCompanys[$keyEmp["com_id"]],
							"username"  => $keyEmp["useri"],
							"fullname"  => $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"]
						);
					}
				}
			}
			$fetchLogDetail = $this->funcQuery->query_result("lms_lg_import_detail", "", "", "", "lgi_id = ".$lgiId);
			if (!empty($fetchLogDetail)) {
				foreach ($fetchLogDetail as $keyLogDetail) {
					if (isset($arrEmployees[$keyLogDetail["emp_id"]])) {
						$dataEmp = $arrEmployees[$keyLogDetail["emp_id"]];
						$statusImport = label("newUser");
						if ($keyLogDetail["lgid_status"] == 2) {
							$statusImport = label("duplicateUser");
						} else if ($keyLogDetail["lgid_status"] == 3) {
							$statusImport = label("removeUser");
						}
						$dateDisplay = date("d/m/Y H:i:s", strtotime($keyLogDetail["lgid_datetime"]));
						if ($lang == "thai") {
						  $dateDisplay = date('d/m',strtotime($keyLogDetail['lgid_datetime']))."/".(date('Y',strtotime($keyLogDetail['lgid_datetime']))+543)." ".date('H:i:s',strtotime($keyLogDetail['lgid_datetime']));
						}

						array_push($fetchArr, array(
							"username" 		=> $dataEmp["username"],
							"fullname" 		=> $dataEmp["fullname"],
							"company" 		=> $dataEmp["company"],
							"statusImport" 	=> $statusImport,
							"logdate" 		=> $dateDisplay,
						));
					}
				}
			}
		}

		if (!empty($fetchArr)) {
			$objPHPExcel = new Spreadsheet();
			$objPHPExcel->setActiveSheetIndex(0);
			$activeSheet = $objPHPExcel->getActiveSheet();
	
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->setCellValue('A1', label('username'));
			$activeSheet->setCellValue('B1', label('m_name'));
			$activeSheet->setCellValue('C1', label('m_company'));
			$activeSheet->setCellValue('D1', label('m_status'));
			$activeSheet->setCellValue('E1', label('com_createdate'));
			
			$numrow = 2;
			foreach ($fetchArr as $keyArr) {
				$activeSheet->setCellValue('A'.$numrow, $keyArr["username"]);
				$activeSheet->setCellValue('B'.$numrow, $keyArr["fullname"]);
				$activeSheet->setCellValue('C'.$numrow, $keyArr["company"]);
				$activeSheet->setCellValue('D'.$numrow, $keyArr["statusImport"]);
				$activeSheet->setCellValue('E'.$numrow, $keyArr["logdate"]);
				$numrow++;
			}
    
			$styleArray = array(
				"borders" => array(
					"allBorders" => array(
						"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				),
			);

			$activeSheet->getStyle('A1:E'.($numrow-1))->applyFromArray($styleArray);
			foreach (range("A", "E") as $columnID) {
				$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			$activeSheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

			$fetchLog = $this->funcQuery->query_row("lms_lg_import", "", "", "", "lgi_id = ".$lgiId);

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Log_import_users_(' . date("Y-m-d", strtotime($fetchLog["lgi_datetime"])) . ').xlsx"');
			header('Cache-Control: max-age=0');
			$writer->save("php://output");
		}
	}

	public function exportExcelAnswerReport($cosId, $comId = "") {
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'funcQuery', false);
		$this->manage->loadDB();
		date_default_timezone_set("Asia/Bangkok");
		$user = $this->session->userdata('user');

		if (!empty($user) && isset($user["emp_id"])) {
			$fetchCheckCos = $this->funcQuery->query_row("lms_cos", "", "", "", "cos_id = ".$cosId." and cos_isDelete = 0");
			if (isset($fetchCheckCos["cos_id"])) {
				$where = 'lms_qiz.cos_id = '.$cosId.' and lms_ques_tc.tc_answer != ""';
				if ($comId != "") {
					$where .= ' and lms_company.com_id = '.$comId;
				}
				$arrEmp = array();
				$arrCompany = array();
				$fetchCompanys = $this->funcQuery->query_result("lms_company");
				if (!empty($fetchCompanys)) {
					foreach ($fetchCompanys as $keyCompany) {
						$arrCompany[$keyCompany["com_id"]] = $keyCompany["com_code"];
					}
				}

				$fetchEmps = $this->funcQuery->query_result("lms_emp");
				if (!empty($fetchEmps)) {
					foreach ($fetchEmps as $keyEmp) {
						if (isset($arrCompany[$keyEmp["com_id"]])) {
							$arrEmp[$keyEmp["emp_id"]] = array(
								"companyCode" 	=> $arrCompany[$keyEmp["com_id"]],
								"empCode" 		=> $keyEmp["emp_c"],
								"empFullname" 	=> $lang=="thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"]
							);
						}
					}
				}

				$arrQuestion = array();

				$fetchQuestions = $this->funcQuery->query_result(
					"lms_ques", "lms_ques_mul", "lms_ques.ques_id = lms_ques_mul.ques_id", "LEFT",
					"lms_ques.qiz_id in (select lms_qiz.qiz_id from lms_qiz where lms_qiz.cos_id = ".$cosId.")", "",
					"lms_ques.ques_id,lms_ques.ques_name_th,lms_ques.ques_name_eng,lms_ques.ques_name_jp,lms_ques_mul.mul_answer,
					(CASE
					WHEN lms_ques_mul.mul_answer = 'mul_c1' THEN lms_ques_mul.mul_c1_th
					WHEN lms_ques_mul.mul_answer = 'mul_c2' THEN lms_ques_mul.mul_c2_th
					WHEN lms_ques_mul.mul_answer = 'mul_c3' THEN lms_ques_mul.mul_c3_th
					WHEN lms_ques_mul.mul_answer = 'mul_c4' THEN lms_ques_mul.mul_c4_th
					WHEN lms_ques_mul.mul_answer = 'mul_c5' THEN lms_ques_mul.mul_c5_th
					ELSE '' END) as ques_choice_answer_th,
					(CASE
					WHEN lms_ques_mul.mul_answer = 'mul_c1' THEN lms_ques_mul.mul_c1_eng
					WHEN lms_ques_mul.mul_answer = 'mul_c2' THEN lms_ques_mul.mul_c2_eng
					WHEN lms_ques_mul.mul_answer = 'mul_c3' THEN lms_ques_mul.mul_c3_eng
					WHEN lms_ques_mul.mul_answer = 'mul_c4' THEN lms_ques_mul.mul_c4_eng
					WHEN lms_ques_mul.mul_answer = 'mul_c5' THEN lms_ques_mul.mul_c5_eng
					ELSE '' END) as ques_choice_answer_eng,
					(CASE
					WHEN lms_ques_mul.mul_answer = 'mul_c1' THEN lms_ques_mul.mul_c1_jp
					WHEN lms_ques_mul.mul_answer = 'mul_c2' THEN lms_ques_mul.mul_c2_jp
					WHEN lms_ques_mul.mul_answer = 'mul_c3' THEN lms_ques_mul.mul_c3_jp
					WHEN lms_ques_mul.mul_answer = 'mul_c4' THEN lms_ques_mul.mul_c4_jp
					WHEN lms_ques_mul.mul_answer = 'mul_c5' THEN lms_ques_mul.mul_c5_jp
					ELSE '' END) as ques_choice_answer_jp,
					lms_ques_mul.mul_c1_th,
					lms_ques_mul.mul_c2_th,
					lms_ques_mul.mul_c3_th,
					lms_ques_mul.mul_c4_th,
					lms_ques_mul.mul_c5_th,
					lms_ques_mul.mul_c1_eng,
					lms_ques_mul.mul_c2_eng,
					lms_ques_mul.mul_c3_eng,
					lms_ques_mul.mul_c4_eng,
					lms_ques_mul.mul_c5_eng,
					lms_ques_mul.mul_c1_jp,
					lms_ques_mul.mul_c2_jp,
					lms_ques_mul.mul_c3_jp,
					lms_ques_mul.mul_c4_jp,
					lms_ques_mul.mul_c5_jp
					"
				);
				if (!empty($fetchQuestions)) {
					foreach ($fetchQuestions as $keyQuestion) {
						if ($lang=="thai") {
							$questionName = !checkValueIsNullTypeString($keyQuestion['ques_name_th']) ?
											$keyQuestion['ques_name_th']:$keyQuestion['ques_name_eng'];
							$questionName = !checkValueIsNullTypeString($questionName) ? $questionName : $keyQuestion['ques_name_jp'];

							$choiceAnswer = !checkValueIsNullTypeString($keyQuestion['ques_choice_answer_th']) ?
											$keyQuestion['ques_choice_answer_th']:$keyQuestion['ques_choice_answer_eng'];
							$choiceAnswer = !checkValueIsNullTypeString($choiceAnswer) ?
											$choiceAnswer : $keyQuestion['ques_choice_answer_jp'];

							$mulC1 = !checkValueIsNullTypeString($keyQuestion['mul_c1_th']) ?
											$keyQuestion['mul_c1_th']:$keyQuestion['mul_c1_eng'];
							$mulC1 = !checkValueIsNullTypeString($mulC1) ?
											$mulC1 : $keyQuestion['mul_c1_jp'];

							$mulC2 = !checkValueIsNullTypeString($keyQuestion['mul_c2_th']) ?
											$keyQuestion['mul_c2_th']:$keyQuestion['mul_c2_eng'];
							$mulC2 = !checkValueIsNullTypeString($mulC2) ?
											$mulC2 : $keyQuestion['mul_c2_jp'];

							$mulC3 = !checkValueIsNullTypeString($keyQuestion['mul_c3_th']) ?
											$keyQuestion['mul_c3_th']:$keyQuestion['mul_c3_eng'];
							$mulC3 = !checkValueIsNullTypeString($mulC3) ?
											$mulC3 : $keyQuestion['mul_c3_jp'];

							$mulC4 = !checkValueIsNullTypeString($keyQuestion['mul_c4_th']) ?
											$keyQuestion['mul_c4_th']:$keyQuestion['mul_c4_eng'];
							$mulC4 = !checkValueIsNullTypeString($mulC4) ?
											$mulC4 : $keyQuestion['mul_c4_jp'];

							$mulC5 = !checkValueIsNullTypeString($keyQuestion['mul_c5_th']) ?
											$keyQuestion['mul_c5_th']:$keyQuestion['mul_c5_eng'];
							$mulC5 = !checkValueIsNullTypeString($mulC5) ?
											$mulC5 : $keyQuestion['mul_c5_jp'];
						} elseif ($lang=="english") {
							$questionName = !checkValueIsNullTypeString($keyQuestion['ques_name_eng']) ?
											$keyQuestion['ques_name_eng']:$keyQuestion['ques_name_th'];
							$questionName = !checkValueIsNullTypeString($questionName) ? $questionName : $keyQuestion['ques_name_jp'];

							$choiceAnswer = !checkValueIsNullTypeString($keyQuestion['ques_choice_answer_eng']) ?
											$keyQuestion['ques_choice_answer_eng']:$keyQuestion['ques_choice_answer_th'];
							$choiceAnswer = !checkValueIsNullTypeString($choiceAnswer) ?
											$choiceAnswer : $keyQuestion['ques_choice_answer_jp'];
							
							$mulC1 = !checkValueIsNullTypeString($keyQuestion['mul_c1_eng']) ?
											$keyQuestion['mul_c1_eng']:$keyQuestion['mul_c1_th'];
							$mulC1 = !checkValueIsNullTypeString($mulC1) ?
											$mulC1 : $keyQuestion['mul_c1_jp'];
							
							$mulC2 = !checkValueIsNullTypeString($keyQuestion['mul_c2_eng']) ?
											$keyQuestion['mul_c2_eng']:$keyQuestion['mul_c2_th'];
							$mulC2 = !checkValueIsNullTypeString($mulC2) ?
											$mulC2 : $keyQuestion['mul_c2_jp'];
							
							$mulC3 = !checkValueIsNullTypeString($keyQuestion['mul_c3_eng']) ?
											$keyQuestion['mul_c3_eng']:$keyQuestion['mul_c3_th'];
							$mulC3 = !checkValueIsNullTypeString($mulC3) ?
											$mulC3 : $keyQuestion['mul_c3_jp'];
							
							$mulC4 = !checkValueIsNullTypeString($keyQuestion['mul_c4_eng']) ?
											$keyQuestion['mul_c4_eng']:$keyQuestion['mul_c4_th'];
							$mulC4 = !checkValueIsNullTypeString($mulC4) ?
											$mulC4 : $keyQuestion['mul_c4_jp'];
							
							$mulC5 = !checkValueIsNullTypeString($keyQuestion['mul_c5_eng']) ?
											$keyQuestion['mul_c5_eng']:$keyQuestion['mul_c5_th'];
							$mulC5 = !checkValueIsNullTypeString($mulC5) ?
											$mulC5 : $keyQuestion['mul_c5_jp'];
						} else {
							$questionName = !checkValueIsNullTypeString($keyQuestion['ques_name_jp']) ?
											$keyQuestion['ques_name_jp']:$keyQuestion['ques_name_eng'];
							$questionName = !checkValueIsNullTypeString($questionName) ? $questionName : $keyQuestion['ques_name_th'];

							$choiceAnswer = !checkValueIsNullTypeString($keyQuestion['ques_choice_answer_jp']) ?
											$keyQuestion['ques_choice_answer_jp']:$keyQuestion['ques_choice_answer_eng'];
							$choiceAnswer = !checkValueIsNullTypeString($choiceAnswer) ?
											$choiceAnswer : $keyQuestion['ques_choice_answer_th'];

							$mulC1 = !checkValueIsNullTypeString($keyQuestion['mul_c1_jp']) ?
											$keyQuestion['mul_c1_jp']:$keyQuestion['mul_c1_eng'];
							$mulC1 = !checkValueIsNullTypeString($mulC1) ?
											$mulC1 : $keyQuestion['mul_c1_th'];

							$mulC2 = !checkValueIsNullTypeString($keyQuestion['mul_c2_jp']) ?
											$keyQuestion['mul_c2_jp']:$keyQuestion['mul_c2_eng'];
							$mulC2 = !checkValueIsNullTypeString($mulC2) ?
											$mulC2 : $keyQuestion['mul_c2_th'];

							$mulC3 = !checkValueIsNullTypeString($keyQuestion['mul_c3_jp']) ?
											$keyQuestion['mul_c3_jp']:$keyQuestion['mul_c3_eng'];
							$mulC3 = !checkValueIsNullTypeString($mulC3) ?
											$mulC3 : $keyQuestion['mul_c3_th'];

							$mulC4 = !checkValueIsNullTypeString($keyQuestion['mul_c4_jp']) ?
											$keyQuestion['mul_c4_jp']:$keyQuestion['mul_c4_eng'];
							$mulC4 = !checkValueIsNullTypeString($mulC4) ?
											$mulC4 : $keyQuestion['mul_c4_th'];

							$mulC5 = !checkValueIsNullTypeString($keyQuestion['mul_c5_jp']) ?
											$keyQuestion['mul_c5_jp']:$keyQuestion['mul_c5_eng'];
							$mulC5 = !checkValueIsNullTypeString($mulC5) ?
											$mulC5 : $keyQuestion['mul_c5_th'];
						}
						$arrQuestion[$keyQuestion["ques_id"]] = array(
							"questionName" 	=> $questionName,
							"choiceAnswer" 	=> $choiceAnswer,
							"mul_answer" 	=> $keyQuestion["mul_answer"],
							"mul_c1" 		=> $mulC1,
							"mul_c2" 		=> $mulC2,
							"mul_c3" 		=> $mulC3,
							"mul_c4" 		=> $mulC4,
							"mul_c5" 		=> $mulC5,
						);
					}
				}

				$fetchRawQuery = $this->funcQuery->raw_query(
					'select lms_cos_enroll.emp_id,
					lms_cos_enroll.cosen_firsttime,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_score_per,lms_cos_enroll.cosen_grade,
					lms_qiz.quiz_name_th,lms_qiz.quiz_name_eng,lms_qiz.quiz_name_jp,
					lms_qiz_tc.sum_score,lms_qiz_tc.per_score,lms_qiz_tc.time_finish,lms_qiz_tc.limit_val,
					lms_ques_tc.tc_answer,lms_ques_tc.tc_score,lms_ques_tc.tc_finish,lms_ques_tc.ques_id
					from lms_ques_tc
					inner join lms_qiz_tc on lms_ques_tc.qiztc_id = lms_qiz_tc.qiztc_id
					inner join lms_qiz on lms_ques_tc.qiz_id = lms_qiz.qiz_id
					inner join lms_cos_enroll on lms_ques_tc.cosen_id = lms_cos_enroll.cosen_id
					where '.$where
				);
				// print_r($fetchRawQuery);

				if (!empty($fetchRawQuery)) {
					$objPHPExcel = new Spreadsheet();
					$objPHPExcel->setActiveSheetIndex(0);
					$activeSheet = $objPHPExcel->getActiveSheet();
					
					$activeSheet->setCellValue("A1", "E-Mail");
					$activeSheet->setCellValue("B1", "Name - Lastname");
					$activeSheet->setCellValue("C1", "Company Nick Name");
					$activeSheet->setCellValue("D1", "Learning Start Date");
					$activeSheet->setCellValue("E1", "Learning Complete Date");
					$activeSheet->setCellValue("F1", "Grade");
					$activeSheet->setCellValue("G1", "Test Name");
					$activeSheet->setCellValue("H1", "Score");
					$activeSheet->setCellValue("I1", "Percent");
					$activeSheet->setCellValue("J1", "Test No.");
					$activeSheet->setCellValue("K1", "Question");
					$activeSheet->setCellValue("L1", "Correct Choice");
					$activeSheet->setCellValue("M1", "Correct Answer");
					$activeSheet->setCellValue("N1", "User Choice");
					$activeSheet->setCellValue("O1", "User Answer");
					$activeSheet->setCellValue("P1", "Question Score");
					$activeSheet->setCellValue("Q1", "Complete DateTime");

					$numRow = 2;
					foreach ($fetchRawQuery as $keyRawQuery) {
						if (isset($arrEmp[$keyRawQuery["emp_id"]]) && isset($arrQuestion[$keyRawQuery["ques_id"]])) {
							$dataEmp = $arrEmp[$keyRawQuery["emp_id"]];
							$dataQuestion = $arrQuestion[$keyRawQuery["ques_id"]];
							if ($lang=="thai") { 
								$quizName = !checkValueIsNullTypeString($keyRawQuery['quiz_name_th']) ?
												$keyRawQuery['quiz_name_th']:$keyRawQuery['quiz_name_eng'];
								$quizName = !checkValueIsNullTypeString($quizName) ? $quizName : $keyRawQuery['quiz_name_jp'];
							} elseif ($lang=="english") { 
								$quizName = !checkValueIsNullTypeString($keyRawQuery['quiz_name_eng']) ?
												$keyRawQuery['quiz_name_eng']:$keyRawQuery['quiz_name_th'];
								$quizName = !checkValueIsNullTypeString($quizName) ? $quizName :$keyRawQuery['quiz_name_jp'];
							} else {
								$quizName = !checkValueIsNullTypeString($keyRawQuery['quiz_name_jp']) ?
												$keyRawQuery['quiz_name_jp']:$keyRawQuery['quiz_name_eng'];
								$quizName = !checkValueIsNullTypeString($quizName) ? $quizName :$keyRawQuery['quiz_name_th'];
							}
							$userChoiceAnswer = isset($dataQuestion[$keyRawQuery["tc_answer"]]) ? $dataQuestion[$keyRawQuery["tc_answer"]] : "";
	
							$activeSheet->setCellValue("A".$numRow, $dataEmp["empCode"]);
							$activeSheet->setCellValue("B".$numRow, $dataEmp["empFullname"]);
							$activeSheet->setCellValue("C".$numRow, $dataEmp["companyCode"]);
							$activeSheet->setCellValue("D".$numRow, $keyRawQuery["cosen_firsttime"]);
							$activeSheet->setCellValue("E".$numRow, $keyRawQuery["cosen_finishtime"]);
							$activeSheet->setCellValue("F".$numRow, $keyRawQuery["cosen_grade"]);
							$activeSheet->setCellValue("G".$numRow, strip_tags($quizName));
							$activeSheet->setCellValue(
								"H".$numRow,
								!checkValueIsNullTypeNumber($keyRawQuery["cosen_score"]) ?
									number_format($keyRawQuery["cosen_score"]) : 0
							);
							$activeSheet->setCellValue(
								"I".$numRow,
								!checkValueIsNullTypeNumber($keyRawQuery["cosen_score_per"]) ?
									number_format($keyRawQuery["cosen_score_per"]) : 0
							);
							$activeSheet->setCellValue("J".$numRow, $keyRawQuery["limit_val"]);
							$activeSheet->setCellValue("K".$numRow, strip_tags($dataQuestion["questionName"]));
							$activeSheet->setCellValue("L".$numRow, $dataQuestion["mul_answer"]);
							$activeSheet->setCellValue("M".$numRow, strip_tags($dataQuestion["choiceAnswer"]));
							$activeSheet->setCellValue("N".$numRow, $keyRawQuery["tc_answer"]);
							$activeSheet->setCellValue("O".$numRow, strip_tags($userChoiceAnswer));
							$activeSheet->setCellValue(
								"P".$numRow,
								!checkValueIsNullTypeNumber($keyRawQuery["tc_score"]) ?
									number_format($keyRawQuery["tc_score"], 2) : 0
							);
							$activeSheet->setCellValue(
								"Q".$numRow,
								!checkDatetimeIsNull($keyRawQuery["tc_finish"]) ?
									date("d/m/Y H:i", strtotime($keyRawQuery["tc_finish"])) : ""
							);
							$numRow++;
						}
					}
		
					$styleArray = array(
						"borders" => array(
							"allBorders" => array(
								"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							),
						),
					);

					$activeSheet->getStyle('C1:C'.($numRow-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$activeSheet->getStyle('F1:F'.($numRow-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$activeSheet->getStyle('L1:L'.($numRow-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$activeSheet->getStyle('N1:N'.($numRow-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
					$activeSheet->getStyle('A1:Q'.($numRow-1))->applyFromArray($styleArray);
					
					$activeSheet->getStyle('A1:Q'.($numRow-1))->getAlignment()->setWrapText(true);
					$activeSheet->getStyle('A1:Q'.($numRow-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
					// foreach (range("A", "Q") as $columnID) {
					// 	$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
					// }
	
					$activeSheet->getColumnDimension("A")->setAutoSize(true);
					$activeSheet->getColumnDimension("B")->setAutoSize(true);
					$activeSheet->getColumnDimension("C")->setAutoSize(true);
					$activeSheet->getColumnDimension("D")->setAutoSize(true);
					$activeSheet->getColumnDimension("E")->setAutoSize(true);
					$activeSheet->getColumnDimension("F")->setAutoSize(true);
					$activeSheet->getColumnDimension('G')->setWidth(80);
					$activeSheet->getColumnDimension("H")->setAutoSize(true);
					$activeSheet->getColumnDimension("I")->setAutoSize(true);
					$activeSheet->getColumnDimension("J")->setAutoSize(true);
					$activeSheet->getColumnDimension('K')->setWidth(80);
					$activeSheet->getColumnDimension("L")->setAutoSize(true);
					$activeSheet->getColumnDimension('M')->setWidth(80);
					$activeSheet->getColumnDimension("N")->setAutoSize(true);
					$activeSheet->getColumnDimension('O')->setWidth(80);
					$activeSheet->getColumnDimension("P")->setAutoSize(true);
					$activeSheet->getColumnDimension("Q")->setAutoSize(true);
					
					$activeSheet->getStyle('A1:Q1')->getAlignment()->setHorizontal('center');
					$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="Answer_Report_(' . date("Y-m-d") . ').xlsx"');
					header('Cache-Control: max-age=0');
					$writer->save("php://output");
				}
			} else {
				redirect(base_url().'dashboard');
			}
		} else {
			redirect(base_url().'dashboard');
		}
	}

	public function exportLearnerReport() {
		date_default_timezone_set("Asia/Bangkok");
		$sess = $this->session->userdata('user');
		
		$thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);

		$this->load->model('Manage_model', 'manage', FALSE);
		$this->load->model('Function_query_model', 'funcQuery', FALSE);
		$this->manage->loadDB();

		$comId = isset($_GET["comId"]) ? $_GET["comId"] : "";
		$cosId = isset($_GET["cosId"]) ? $_GET["cosId"] : "";
		$cosenStatusSub = isset($_GET["cosenStatusSub"]) ? $_GET["cosenStatusSub"] : "";
		$timeStart = isset($_GET["timeStart"]) ? $_GET["timeStart"] : "00:00";
		$timeEnd = isset($_GET["timeEnd"]) ? $_GET["timeEnd"] : "00:00";
		$dateStart = isset($_GET["dateStart"]) && !checkValueIsNullTypeString($_GET["dateStart"]) ? $_GET["dateStart"]." ".$timeStart : "";
		$dateEnd = isset($_GET["dateEnd"]) && !checkValueIsNullTypeString($_GET["dateEnd"]) ? $_GET["dateEnd"]." ".$timeEnd : "";

		$arrCompany = array();
		$arrEmp = array();
		$arrEmpUID = array();
		$arrCourses = array();
		$whereCompany = "com_isDelete = 0";
		$whereCosen = "cosen_isDelete = 0";

		if (!checkValueIsNullTypeNumber($comId)) {
			$whereCompany .= " and lms_company.com_id = ".$comId;
			$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$comId.")";
		} else {
			if ($sess['com_admin'] == "com_associated") {
				$whereCompany .= " and lms_company.com_id = ".$sess['com_id'];
				$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$sess['com_id'].")";
			} else {
				if ($sess['ug_viewdata'] != "1") {
					$whereCompany .= " and lms_company.com_id = ".$sess['com_id'];
					$whereCosen .= " and lms_cos_enroll.emp_id in (select lms_emp.emp_id from lms_emp where emp_isDelete = 0 and lms_emp.com_id = ".$sess['com_id'].")";
				}
			}
		}

		if (!checkValueIsNullTypeString($cosId)) {
			$whereCosen .= " and lms_cos_enroll.cos_id = ".$cosId;
		}
		if (!checkValueIsNullTypeString($cosenStatusSub)) {
			if ($cosenStatusSub == "0") {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = 0";
			} elseif ($cosenStatusSub == "2") {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = 2";
			} else {
				$whereCosen .= " and lms_cos_enroll.cosen_status_sub = ".$cosenStatusSub;
			}
		}
		if (!checkValueIsNullTypeString($dateStart) && !checkValueIsNullTypeString($dateEnd)) {
			$whereCosen .= " and (lms_cos_enroll.cosen_finishtime BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "')";
		}
		
		$fetchCosens = $this->funcQuery->query_result(
			"lms_cos_enroll", "", "", "", $whereCosen,
			"cosen_id ASC", "cos_id, emp_id, cosen_finishtime"
		);
		
		if (!empty($fetchCosens)) {
			$fetchCompanys = $this->funcQuery->query_result(
				"lms_company", "", "", "",
				$whereCompany, "",
				"com_id, com_code"
			);
			if (!empty($fetchCompanys)) {
				foreach ($fetchCompanys as $keyCompany) {
					$arrCompany[$keyCompany["com_id"]] = $keyCompany["com_code"];
				}
			}
			$fetchEmps = $this->funcQuery->query_result(
				"lms_emp", "lms_usp", "lms_usp.emp_id = lms_emp.emp_id", "", "", "",
				"lms_usp.u_id, lms_emp.emp_id, com_id, emp_c, fullname_th, fullname_en"
			);
			if (!empty($fetchEmps)) {
				foreach ($fetchEmps as $keyEmp) {
					if (isset($arrCompany[$keyEmp["com_id"]])) {
						$arrEmp[$keyEmp["emp_id"]] = array(
							"company" 		=> $arrCompany[$keyEmp["com_id"]],
							"username" 		=> $keyEmp["emp_c"],
							"fullname_th" 	=> $keyEmp["fullname_th"],
							"fullname_en" 	=> $keyEmp["fullname_en"],
						);
						$arrEmpUID[$keyEmp["u_id"]] = array(
							"company" 		=> $arrCompany[$keyEmp["com_id"]],
							"username" 		=> $keyEmp["emp_c"],
							"fullname" 		=> $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"],
						);
					}
				}
			}
			$whereCos = "cos_isDelete = 0";
			if (!checkValueIsNullTypeNumber($cosId)) {
				$whereCos .= " and cos_id = ".$cosId;
			}
			if ($sess['ug_viewdata'] == "3") {
				$whereCos .= " and cos_createby = ".$sess['u_id'];
			}
			$arrCosDetail = array();
			$fetchCourseDetails = $this->funcQuery->query_result(
				"lms_cos_detail", "", "", "", "cos_id in (select lms_cos.cos_id from lms_cos where ".$whereCos.")", "",
				"cos_id, date_start, date_end"
			);
			if (!empty($fetchCourseDetails)) {
				foreach ($fetchCourseDetails as $keyCosDetail) {
					$arrCosDetail[$keyCosDetail["cos_id"]] = array(
						"date_start" => $keyCosDetail["date_start"],
						"date_end" => $keyCosDetail["date_end"]
					);
				}
			}
			$fetchCourses = $this->funcQuery->query_result(
				"lms_cos", "", "", "", $whereCos, "",
				"cos_id, cname_th, cname_eng, cname_jp, cos_hour, cos_createby, cos_createdate"
			);
			if (!empty($fetchCourses)) {
				foreach ($fetchCourses as $keyCourse) {
					$period = '-';
					if(isset($arrCosDetail[$keyCourse["cos_id"]])){
						$fetch_detail = $arrCosDetail[$keyCourse["cos_id"]];
						if ($fetch_detail['date_start']!="0000-00-00 00:00:00"&&$fetch_detail['date_end']!="0000-00-00 00:00:00"){
						  if ($lang=="thai") {
							$periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/',strtotime($fetch_detail['date_start'])).(date('Y',strtotime($fetch_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_detail['date_start'])):"";
							$periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/',strtotime($fetch_detail['date_end'])).(date('Y',strtotime($fetch_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_detail['date_end'])):"";
						  } else {
							$periodstart = $fetch_detail['date_start']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_start'])):"";
							$periodend = $fetch_detail['date_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_detail['date_end'])):"";
						  }
						
						  if($periodstart!=""&&$periodend!=""){
							  $period = $periodstart." - ".$periodend;
						  }
						}
					}
					$arrCourses[$keyCourse["cos_id"]] = array(
						"cname_th" 		=> $keyCourse["cname_th"],
						"cname_eng" 	=> $keyCourse["cname_eng"],
						"cname_jp" 		=> $keyCourse["cname_jp"],
						"cos_hour"		=> $keyCourse["cos_hour"],
						"period" 		=> $period,
						"createdate" 	=> !checkDatetimeIsNull($keyCourse["cos_createdate"]) ? ($lang=="thai" ? date('d/m/',strtotime($keyCourse['cos_createdate'])).(date('Y',strtotime($keyCourse['cos_createdate']))+543)." ".date('H:i',strtotime($keyCourse['cos_createdate'])) : date("d/m/Y H:i", strtotime($keyCourse["cos_createdate"]))) : "",
						"createby" 		=> isset($arrEmpUID[$keyCourse["cos_createby"]]) ? $arrEmpUID[$keyCourse["cos_createby"]]["fullname"] : "",
					);
				}
			}
			$objPHPExcel = new Spreadsheet();
			$objPHPExcel->setActiveSheetIndex(0);
			$activeSheet = $objPHPExcel->getActiveSheet();
			
			$activeSheet->setCellValue("A1", label('ceCname')." TH");
			$activeSheet->setCellValue("B1", label('ceCname')." ENG");
			$activeSheet->setCellValue("C1", label('ceCname')." JP");
			$activeSheet->setCellValue("D1", label('cos_hour'));
			$activeSheet->setCellValue("E1", label('perioddate'));
			$activeSheet->setCellValue("F1", label('da_approve_creator'));
			$activeSheet->setCellValue("G1", label('create_date'));
			$activeSheet->setCellValue("H1", label('com_name'));
			$activeSheet->setCellValue("I1", label('m_username'));
			$activeSheet->setCellValue("J1", label('m_name')." TH");
			$activeSheet->setCellValue("K1", label('m_name')." ENG");
			$activeSheet->setCellValue("L1", label('r_finish_emp'));
			$numRow = 2;
			foreach ($fetchCosens as $keyCosen) {
				if (isset($arrCourses[$keyCosen["cos_id"]]) && isset($arrEmp[$keyCosen["emp_id"]])) {
					$dataEmp = $arrEmp[$keyCosen["emp_id"]];
					$dataCourse = $arrCourses[$keyCosen["cos_id"]];
					
					$activeSheet->setCellValue("A".$numRow, !checkValueIsNullTypeString($dataCourse["cname_th"]) ? $dataCourse["cname_th"] : "");
					$activeSheet->setCellValue("B".$numRow, !checkValueIsNullTypeString($dataCourse["cname_eng"]) ? $dataCourse["cname_eng"] : "");
					$activeSheet->setCellValue("C".$numRow, !checkValueIsNullTypeString($dataCourse["cname_jp"]) ? $dataCourse["cname_jp"] : "");
					$activeSheet->setCellValue("D".$numRow, !checkValueIsNullTypeString($dataCourse["cos_hour"]) ? $dataCourse["cos_hour"] : "");
					$activeSheet->setCellValue("E".$numRow, !checkValueIsNullTypeString($dataCourse["period"]) ? $dataCourse["period"] : "");
					$activeSheet->setCellValue("F".$numRow, !checkValueIsNullTypeString($dataCourse["createby"]) ? $dataCourse["createby"] : "");
					$activeSheet->setCellValue("G".$numRow, !checkValueIsNullTypeString($dataCourse["createdate"]) ? $dataCourse["createdate"] : "");
					$activeSheet->setCellValue("H".$numRow, !checkValueIsNullTypeString($dataEmp["company"]) ? $dataEmp["company"] : "");
					$activeSheet->setCellValue("I".$numRow, !checkValueIsNullTypeString($dataEmp["username"]) ? $dataEmp["username"] : "");
					$activeSheet->setCellValue("J".$numRow, !checkValueIsNullTypeString($dataEmp["fullname_th"]) ? $dataEmp["fullname_th"] : "");
					$activeSheet->setCellValue("K".$numRow, !checkValueIsNullTypeString($dataEmp["fullname_en"]) ? $dataEmp["fullname_en"] : "");
					$activeSheet->setCellValue("L".$numRow, $keyCosen["cosen_finishtime"] != "0000-00-00 00:00:00" && $keyCosen["cosen_finishtime"] != "" ? date("Y-m-d", strtotime($keyCosen["cosen_finishtime"])) : "");
					$numRow++;
				}
			}
			$styleArray = array(
				"borders" => array(
					"allBorders" => array(
						"borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				),
			);

			$activeSheet->getStyle('A1:L'.($numRow-1))->applyFromArray($styleArray);
			foreach (range("A", "L") as $columnID) {
				$activeSheet->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			$activeSheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');


			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Learner_report_(' . date("Y-m-d") . ').xlsx"');
			header('Cache-Control: max-age=0');
			$writer->save("php://output");
		} else {
			redirect(base_url().'dashboard');
		}
	}
}
