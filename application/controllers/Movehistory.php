<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Movehistory extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Function_query_model', 'funcQuery', FALSE);
		$this->funcQuery->loadDB();
	}

    public function runCheck() {
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
        date_default_timezone_set("Asia/Bangkok");
        

        $path = "./runfetch/" . basename("listUpdateEmailDomainMain.xlsx");
        $objPHPExcel = PHPExcel_IOFactory::load($path);

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($row = 2; $row <= $highestRow; ++$row) {
                $arrRecord = array();
                if ($highestColumnIndex == 4) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        if ($col == 0) {
                            $arrRecord["comCodeOld"] = strval($val);
                        }
                        if ($col == 1) {
                            $arrRecord["empCodeOld"] = str_replace(" ", "", strtolower($val));
                        }
                        if ($col == 2) {
                            $arrRecord["comCodeNew"] = strval($val);
                        }
                        if ($col == 3) {
                            $arrRecord["empCodeNew"] = str_replace(" ", "", strtolower($val));
                        }
                    }
                    if ($arrRecord["comCodeOld"] != "") {
                        $empIdOld = "";
                        $userIdOld = "";
                        $empIdNew = "";
                        $userIdNew = "";
                        $fetchEmpNew = $this->funcQuery->query_row(
                            "lms_emp", "", "", "",
                            "emp_c = '".$arrRecord["empCodeNew"]."'"
                        );
                        if (isset($fetchEmpNew["emp_id"]) && $fetchEmpNew["emp_id"] != "") {
                            $empIdNew = $fetchEmpNew["emp_id"];
                            $fetchUserNew = $this->funcQuery->query_row(
                                "lms_usp", "", "", "",
                                "emp_id = '".$empIdNew."'"
                            );
                            if (isset($fetchUserNew["u_id"])) {
                                $userIdNew = $fetchUserNew["u_id"];
                            }
                        }
                        $fetchCompanyOld = $this->funcQuery->query_row(
                            "lms_company", "", "", "",
                            "com_code = '".$arrRecord["comCodeOld"]."'"
                        );
                        if (isset($fetchCompanyOld["com_id"])) {
                            $fetchEmpOld = $this->funcQuery->query_row(
                                "lms_emp", "", "", "",
                                "emp_c = '".$arrRecord["empCodeOld"]."'"
                            );
                            if (isset($fetchEmpOld["emp_id"]) && $fetchEmpOld["emp_id"] != "") {
                                $empIdOld = $fetchEmpOld["emp_id"];
                                $fetchUserOld = $this->funcQuery->query_row(
                                    "lms_usp", "", "", "",
                                    "emp_id = '".$empIdOld."'"
                                );
                                if (isset($fetchUserOld["u_id"])) {
                                    $userIdOld = $fetchUserOld["u_id"];
                                }
                                
                                $fetchCompanyNew = $this->funcQuery->query_row(
                                    "lms_company", "", "", "",
                                    "com_code = '".$arrRecord["comCodeNew"]."'"
                                );
                                if ($empIdNew != "") {
                                    $this->updateEmpIdNew($empIdOld, $empIdNew);

                                    $this->funcQuery->updateData(
                                        "lms_emp",
                                        "emp_id = ".$empIdNew,
                                        array(
                                            "com_id" => isset($fetchCompanyNew["com_id"]) ? $fetchCompanyNew["com_id"] : $fetchCompanyOld["com_id"],
                                        )
                                    );
                                    
                                    $data = array(
                                        'u_id'                  => $userIdOld,
                                        'logusp_status'         => 'Updated',
                                        'logusp_firstdate'      => $fetchUserOld["u_firstdate"],
                                        'logusp_inactivedate'   => date('Y-m-d'),
                                        'logusp_createby'       => $userIdOld,
                                        'logusp_createdate'     => date('Y-m-d H:i')
                                    );
                                    $this->db->insert('lms_log_updateusp', $data);
                                    
                                    $this->funcQuery->updateData(
                                        "lms_usp",
                                        "u_id = ".$userIdOld,
                                        array(
                                            "inactivedate" => date('Y-m-d')
                                        )
                                    );

                                    echo "Update New User,";
                                }

                                if ($empIdNew == "") {
                                    $this->funcQuery->updateData(
                                        "lms_emp",
                                        "emp_id = ".$fetchEmpOld["emp_id"],
                                        array(
                                            "emp_c" => $arrRecord["empCodeNew"],
                                            "email" => $arrRecord["empCodeNew"],
                                            "com_id" => isset($fetchCompanyNew["com_id"]) ? $fetchCompanyNew["com_id"] : $fetchCompanyOld["com_id"],
                                        )
                                    );
                                    
                                    $this->funcQuery->updateData(
                                        "lms_usp",
                                        "emp_id = ".$fetchEmpOld["emp_id"],
                                        array(
                                            "useri" => $arrRecord["empCodeNew"]
                                        )
                                    );

                                    echo "Update Old User,";
                                }
                                    
                                $this->funcQuery->updateData(
                                    "lms_emp",
                                    " emp_manage_a = '".$arrRecord["empCodeOld"]."'",
                                    array(
                                        "emp_manage_a" => $arrRecord["empCodeNew"]
                                    )
                                );
                                
                                $this->funcQuery->updateData(
                                    "lms_emp",
                                    " emp_manage_b = '".$arrRecord["empCodeOld"]."'",
                                    array(
                                        "emp_manage_b" => $arrRecord["empCodeNew"]
                                    )
                                );

                            } else {
                                echo "User Not Found,";
                            }
                        }
                    }
                    echo $arrRecord["comCodeOld"].",".$arrRecord["empCodeOld"].",".$arrRecord["comCodeNew"].",".$arrRecord["empCodeNew"]."<br>";
                }
            }
        }

    }

    public function runCheckSpecial() {
		require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
        date_default_timezone_set("Asia/Bangkok");
        

        $path = "./runfetch/" . basename("listUpdateEmailDomainSpecial.xlsx");
        $objPHPExcel = PHPExcel_IOFactory::load($path);

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($row = 2; $row <= $highestRow; ++$row) {
                $arrRecord = array();
                if ($highestColumnIndex == 4) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        if ($col == 0) {
                            $arrRecord["comCodeOld"] = strval($val);
                        }
                        if ($col == 1) {
                            $arrRecord["empCodeOld"] = str_replace(" ", "", strtolower($val));
                        }
                        if ($col == 2) {
                            $arrRecord["comCodeNew"] = strval($val);
                        }
                        if ($col == 3) {
                            $arrRecord["empCodeNew"] = str_replace(" ", "", strtolower($val));
                        }
                    }
                    if ($arrRecord["comCodeOld"] != "") {
                        $empIdOld = "";
                        $userIdOld = "";
                        $empIdNew = "";
                        $userIdNew = "";
                            $fetchCompanyNew = $this->funcQuery->query_row(
                                "lms_company", "", "", "",
                                "com_code = '".$arrRecord["comCodeNew"]."'"
                            );
                            $fetchEmpNew = $this->funcQuery->query_row(
                                "lms_emp", "", "", "",
                                "emp_c = '".$arrRecord["empCodeNew"]."' and com_id = '".$fetchCompanyNew["com_id"]."'"
                            );
                            if (isset($fetchEmpNew["emp_id"]) && $fetchEmpNew["emp_id"] != "") {
                                $empIdNew = $fetchEmpNew["emp_id"];
                                $fetchUserNew = $this->funcQuery->query_row(
                                    "lms_usp", "", "", "",
                                    "emp_id = '".$empIdNew."'"
                                );
                                if (isset($fetchUserNew["u_id"])) {
                                    $userIdNew = $fetchUserNew["u_id"];
                                }
                            }
                            $fetchCompanyOld = $this->funcQuery->query_row(
                                "lms_company", "", "", "",
                                "com_code = '".$arrRecord["comCodeOld"]."'"
                            );
                            if (isset($fetchCompanyOld["com_id"])) {
                                $fetchEmpOld = $this->funcQuery->query_row(
                                    "lms_emp", "", "", "",
                                    "emp_c = '".$arrRecord["empCodeOld"]."' and com_id = '".$fetchCompanyOld["com_id"]."'"
                                );
                                if (isset($fetchEmpOld["emp_id"]) && $fetchEmpOld["emp_id"] != "") {
                                    $empIdOld = $fetchEmpOld["emp_id"];
                                    $fetchUserOld = $this->funcQuery->query_row(
                                        "lms_usp", "", "", "",
                                        "emp_id = '".$empIdOld."'"
                                    );
                                    if (isset($fetchUserOld["u_id"])) {
                                        $userIdOld = $fetchUserOld["u_id"];
                                    }

                                    if ($arrRecord["empCodeNew"] == "removeuser") {
                                        $this->funcQuery->updateData(
                                            "lms_usp",
                                            "u_id = ".$userIdOld,
                                            array(
                                                "inactivedate" => date('Y-m-d')
                                            )
                                        );
                                        echo "Remove User,";
                                    } else {
                                        if ($empIdNew != "") {
                                            $this->updateEmpIdNew($empIdOld, $empIdNew);

                                            $this->funcQuery->updateData(
                                                "lms_emp",
                                                "emp_id = ".$empIdNew,
                                                array(
                                                    "com_id" => isset($fetchCompanyNew["com_id"]) ? $fetchCompanyNew["com_id"] : $fetchCompanyOld["com_id"],
                                                )
                                            );
                                            
                                            $data = array(
                                                'u_id'                  => $userIdOld,
                                                'logusp_status'         => 'Updated',
                                                'logusp_firstdate'      => $fetchUserOld["u_firstdate"],
                                                'logusp_inactivedate'   => date('Y-m-d'),
                                                'logusp_createby'       => $userIdOld,
                                                'logusp_createdate'     => date('Y-m-d H:i')
                                            );
                                            $this->db->insert('lms_log_updateusp', $data);
                                            
                                            $this->funcQuery->updateData(
                                                "lms_usp",
                                                "u_id = ".$userIdOld,
                                                array(
                                                    "inactivedate" => date('Y-m-d')
                                                )
                                            );

                                            echo "Update New User,";
                                        }

                                        if ($empIdNew == "") {
                                            $this->funcQuery->updateData(
                                                "lms_emp",
                                                "emp_id = ".$fetchEmpOld["emp_id"],
                                                array(
                                                    "emp_c" => $arrRecord["empCodeNew"],
                                                    "email" => $arrRecord["empCodeNew"],
                                                    "com_id" => isset($fetchCompanyNew["com_id"]) ? $fetchCompanyNew["com_id"] : $fetchCompanyOld["com_id"],
                                                )
                                            );
                                            
                                            $this->funcQuery->updateData(
                                                "lms_usp",
                                                "emp_id = ".$fetchEmpOld["emp_id"],
                                                array(
                                                    "useri" => $arrRecord["empCodeNew"]
                                                )
                                            );

                                            echo "Update Old User,";
                                        }
                                            
                                        $this->funcQuery->updateData(
                                            "lms_emp",
                                            " emp_manage_a = '".$arrRecord["empCodeOld"]."'",
                                            array(
                                                "emp_manage_a" => $arrRecord["empCodeNew"]
                                            )
                                        );
                                        
                                        $this->funcQuery->updateData(
                                            "lms_emp",
                                            " emp_manage_b = '".$arrRecord["empCodeOld"]."'",
                                            array(
                                                "emp_manage_b" => $arrRecord["empCodeNew"]
                                            )
                                        );
                                    }
    
                                } else {
                                    echo "User Not Found,";
                                }
                            }
                    }
                    echo $arrRecord["comCodeOld"].",".$arrRecord["empCodeOld"].",".$arrRecord["comCodeNew"].",".$arrRecord["empCodeNew"]."<br>";
                }
            }
        }

    }

    public function updateEmpIdNew($empIdOld, $empIdNew) {
        $this->funcQuery->updateData(
            "lms_cos_enroll",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_fil_log",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_les_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_med_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_qiz_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_ques_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_certificate",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_qn_user",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_scm_val",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_sv_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
        $this->funcQuery->updateData(
            "lms_svde_tc",
            "emp_id = '".$empIdOld."'",
            array("emp_id" => $empIdNew)
        );
    }

}
