<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    class Duplicatecourse extends CI_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->model("Function_query_model", "funcQuery", false);
            $this->funcQuery->loadDB();
        }

        public function index() {
            date_default_timezone_set("Asia/Bangkok");
            $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
            $this->lang->load($lang, $lang);
            $sess = $this->session->userdata("user");

            $cosId = isset($_POST["cos_id_duplicate"]) ? $_POST["cos_id_duplicate"] : "";
            $comId = isset($_POST["com_id_duplicate"]) ? $_POST["com_id_duplicate"] : "";
            $instructorId = isset($_POST["instructor_id_duplicate"]) ? $_POST["instructor_id_duplicate"] : "";
            $suffixCourseName = isset($_POST["suffix_course_name"]) ? $_POST["suffix_course_name"] : "";

            $output = array("status" => 0);
            $fetchCheckCourse = $this->funcQuery->query_row(
                "lms_cos", "", "", "",
                "cos_isDelete = 0 and cos_id = ".$cosId
            );
            $fetchCheckInstructor = $this->funcQuery->query_row(
                "lms_usp", "", "", "",
                "u_id = ".$instructorId, "", "u_id"
            );
            if (isset($fetchCheckCourse["cos_id"]) &&
                !checkValueIsNullTypeNumber($comId) &&
                isset($fetchCheckInstructor["u_id"])) {
                $cname = "";
                $cos_lang = explode(",", $fetchCheckCourse["cos_lang"]);
                $isTH = in_array("th",$cos_lang)?"1":"0";
                $isENG = in_array("eng",$cos_lang)?"1":"0";
                $isJP = in_array("jp",$cos_lang)?"1":"0";
                if($lang=="thai"){
                    if($isTH=="1"){
                        $cname = $fetchCheckCourse["cname_th"];
                    }else{
                        if($cname==""&&$isENG=="1"){
                            $cname = $fetchCheckCourse["cname_eng"];
                        }
                        if($cname==""&&$isJP=="1"){
                            $cname = $fetchCheckCourse["cname_jp"];
                        }
                    }
                }else if($lang=="english"){
                    if($isENG=="1"){
                        $cname = $fetchCheckCourse["cname_eng"];
                    }else{
                        if($cname==""&&$isTH=="1"){
                            $cname = $fetchCheckCourse["cname_th"];
                        }
                        if($cname==""&&$isJP=="1"){
                            $cname = $fetchCheckCourse["cname_jp"];
                        }
                    }
                }else{
                    if($isJP=="1"){
                        $cname = $fetchCheckCourse["cname_jp"];
                    }else{
                        if($cname==""&&$isENG=="1"){
                            $cname = $fetchCheckCourse["cname_eng"];
                        }
                        if($cname==""&&$isTH=="1"){
                            $cname = $fetchCheckCourse["cname_th"];
                        }
                    }
                }
                
                $fetchCompany = $this->funcQuery->query_row(
                    "lms_company", "", "", "",
                    "com_id = ".$comId." and com_isDelete = 0"
                );
                if (isset($fetchCompany["com_id"])) {
                    $arrCourse = $fetchCheckCourse;
                    
                    $fetch_id = $this->funcQuery->query_row("lms_cos", "", "", "", "", "cos_id desc");
                    $id2 = "";
                    if (countArray($fetch_id) > 0) {
                    $id1 = intval(substr($fetch_id["ccode"], -4));
                    $id1++;
                    if ($id1 == 0 || $id1 < 10) {
                        $id2 = $fetchCompany["com_code"] . "000" . $id1;
                    } elseif ($id1 == 10 || $id1 < 100) {
                        $id2 = $fetchCompany["com_code"] . "00" . $id1;
                    } elseif ($id1 == 100 || $id1 < 1000) {
                        $id2 = $fetchCompany["com_code"] . "0" . $id1;
                    } else {
                        $id2 = $fetchCompany["com_code"] . $id1;
                    }
                    } else {
                        $id2 = $fetchCompany["com_code"] . "0001";
                    }
                    unset($arrCourse["cos_id"]);
                    
                    if (!checkValueIsNullTypeString($arrCourse["cos_pic"]) &&
                        is_file(ROOT_DIR."uploads/course/".$arrCourse["cos_pic"])) {
                            $pathCosPIC = $arrCourse["cos_pic"];
                            $arrayPathextCosPIC = explode(".", $pathCosPIC);
                            $extensionCosPIC = end($arrayPathextCosPIC);
                            $imageFileNameCosPIC = "cos_".date("YmdHis").".".$extensionCosPIC;
                            $imageTargetCosPICPath = ROOT_DIR."uploads/course/".$imageFileNameCosPIC;
                            audit_copy(ROOT_DIR."uploads/course/".$arrCourse["cos_pic"], $imageTargetCosPICPath);
                            $arrCourse["cos_pic"] = $imageFileNameCosPIC;
                    }

                    $arrCourse["ccode"] = $id2;
                    $arrCourse["cname_th"] = !checkValueIsNullTypeString($arrCourse["cname_th"]) ? $arrCourse["cname_th"].$suffixCourseName : "";
                    $arrCourse["cname_eng"] = !checkValueIsNullTypeString($arrCourse["cname_eng"]) ? $arrCourse["cname_eng"].$suffixCourseName : "";
                    $arrCourse["cname_jp"] = !checkValueIsNullTypeString($arrCourse["cname_jp"]) ? $arrCourse["cname_jp"].$suffixCourseName : "";
                    $arrCourse["com_id"] = $comId;
                    $arrCourse["cos_status"] = 1;
                    $arrCourse["cos_public"] = 0;
                    $arrCourse["cos_approve"] = 0;
                    $arrCourse["cos_approveby"] = "";
                    $arrCourse["cos_approvedate"] = "0000-00-00 00:00:00";
                    $arrCourse["cos_createby"] = $instructorId;
                    $arrCourse["cos_createdate"] = date("Y-m-d H:i:s");
                    $arrCourse["cos_modifiedby"] = $instructorId;
                    $arrCourse["cos_modifieddate"] = date("Y-m-d H:i:s");
                    
                    $this->db->insert("lms_cos", $arrCourse);
                    $newCosID = $this->db->insert_id();
                    if (!checkValueIsNullTypeNumber($newCosID)) {

                        // Duplicate COSINCG
                        $fetchCheckCOGInCourse = $this->funcQuery->query_result(
                            "lms_cosincg", "", "", "",
                            "course_id = ".$cosId
                        );
                        if (!empty($fetchCheckCOGInCourse)) {
                            foreach ($fetchCheckCOGInCourse as $keyCogInCourse) {
                                $arrNewCogInCourse = $keyCogInCourse;
                                $fetchCheckCogInCourseNew = $this->funcQuery->numrows(
                                    "lms_cosincg", "", "", "",
                                    "course_id = ".$newCosID." and cg_id = ".$arrNewCogInCourse["cg_id"]
                                );
                                if ($fetchCheckCogInCourseNew == 0) {
                                    $arrNewCogInCourse["course_id"] = $newCosID;
                                    $this->db->insert("lms_cosincg", $arrNewCogInCourse);
                                }
                            }
                        }

                        // Duplicate CUG
                        $fetchCheckCUG = $this->funcQuery->query_result(
                            "lms_cug", "", "", "",
                            "course_id = ".$cosId
                        );
                        if (!empty($fetchCheckCUG)) {
                            foreach ($fetchCheckCUG as $keyCUG) {
                                $arrNewCUG = $keyCUG;
                                $fetchCheckCUGNew = $this->funcQuery->numrows(
                                    "lms_cug", "", "", "",
                                    "course_id = ".$newCosID
                                );
                                if ($fetchCheckCUGNew == 0) {
                                    unset($arrNewCUG["id"]);
                                    $arrNewCUG["course_id"] = $newCosID;
                                    $this->db->insert("lms_cug", $arrNewCUG);
                                }
                            }
                        }

                        // Duplicate Certificate of course
                        $fetchCheckBAD = $this->funcQuery->query_result(
                            "lms_bad", "", "", "",
                            "courses_id = ".$cosId
                        );
                        if (!empty($fetchCheckBAD)) {
                            foreach ($fetchCheckBAD as $keyBAD) {
                                $arrNewBAD = $keyBAD;
                                $fetchCheckBADNew = $this->funcQuery->numrows(
                                    "lms_bad", "", "", "",
                                    "courses_id = ".$newCosID
                                );
                                if ($fetchCheckBADNew == 0) {
                    
                                    if (!checkValueIsNullTypeString($keyBAD["badges_img"]) &&
                                        is_file(ROOT_DIR."uploads/badges/".$keyBAD["badges_img"])) {
                                            $pathBAD = $keyBAD["badges_img"];
                                            $arrayPathextBAD = explode(".", $pathBAD);
                                            $extensionBAD = end($arrayPathextBAD);
                                            $imageFileNameBAD = "cosCert_".date("YmdHis").".".$extensionBAD;
                                            $imageTargetBADPath = ROOT_DIR."uploads/badges/".$imageFileNameBAD;
                                            audit_copy(ROOT_DIR."uploads/badges/".$keyBAD["badges_img"], $imageTargetBADPath);
                                            $arrNewBAD["badges_img"] = $imageFileNameBAD;
                                    } else {
                                        $arrNewBAD["badges_img"] = "";
                                    }

                                    unset($arrNewBAD["badges_id"]);
                                    $arrNewBAD["courses_id"] = $newCosID;
                                    $arrNewBAD["time_create"] = date("Y-m-d H:i:s");
                                    $this->db->insert("lms_bad", $arrNewBAD);
                                }
                            }
                        }

                        // Duplicate Document of course
                        $fetchCheckDocCos = $this->funcQuery->query_result(
                            "lms_cos_fil", "", "", "",
                            "cos_id = ".$cosId
                        );
                        if (!empty($fetchCheckDocCos)) {
                            foreach ($fetchCheckDocCos as $keyDocCos) {
                                $arrNewDocCos = $keyDocCos;
                    
                                if (!checkValueIsNullTypeString($keyDocCos["path_file"]) &&
                                    is_file(ROOT_DIR."uploads/document/".$keyDocCos["path_file"])) {
                                        $pathDocCos = $keyDocCos["path_file"];
                                        $arrayPathextDocCos = explode(".", $pathDocCos);
                                        $extensionDocCos = end($arrayPathextDocCos);
                                        $imageFileNameDocCos = "cosdoc_".date("YmdHis")."_".$keyDocCos["fil_cos_id"].".".$extensionDocCos;
                                        $imageTargetDocCosPath = ROOT_DIR."uploads/document/".$imageFileNameDocCos;
                                        audit_copy(ROOT_DIR."uploads/document/".$keyDocCos["path_file"], $imageTargetDocCosPath);
                                        $arrNewDocCos["path_file"] = $imageFileNameDocCos;

                                        unset($arrNewDocCos["fil_cos_id"]);
                                        $arrNewDocCos["cos_id"] = $newCosID;
                                        $arrNewDocCos["fil_createby"] = $instructorId;
                                        $arrNewDocCos["fil_createdate"] = date("Y-m-d H:i:s");
                                        $arrNewDocCos["fil_modifiedby"] = $instructorId;
                                        $arrNewDocCos["fil_modifieddate"] = date("Y-m-d H:i:s");
                                        $this->db->insert("lms_cos_fil", $arrNewDocCos);
                                }
                            }
                        }


                        // Duplicate Lesson of course
                        $fetchCheckLesson = $this->funcQuery->query_result(
                            "lms_les", "", "", "",
                            "cos_id = ".$cosId
                        );
                        if (!empty($fetchCheckLesson)) {
                            foreach ($fetchCheckLesson as $keyLES) {
                                $arrNewLES = $keyLES;

                                unset($arrNewLES["les_id"]);
                                $arrNewLES["cos_id"] = $newCosID;
                                $arrNewLES["time_start"] = "0000-00-00 00:00:00";
                                $arrNewLES["time_end"] = "0000-00-00 00:00:00";
                                $arrNewLES["les_createby"] = $instructorId;
                                $arrNewLES["les_createdate"] = date("Y-m-d H:i:s");
                                $arrNewLES["les_modifiedby"] = $instructorId;
                                $arrNewLES["les_modifieddate"] = date("Y-m-d H:i:s");
                                $this->db->insert("lms_les", $arrNewLES);
                                $newLesID = $this->db->insert_id();

                                if ($arrNewLES["les_type"] == "1") {
                                    // Media
                                    // Media File VDO
                                    $fetchMEDs = $this->funcQuery->query_result(
                                        "lms_med", "", "", "",
                                        "lessons_id = ".$keyLES["les_id"]
                                    );
                                    if (!empty($fetchMEDs)) {
                                        foreach ($fetchMEDs as $keyMEDIndex => $keyMED) {
                                                $arrNewMED = $keyMED;
                                                if (!checkValueIsNullTypeString($keyMED["thumbnail_med"]) &&
                                                    is_file(ROOT_DIR."uploads/thumbnail/".$keyMED["thumbnail_med"])) {
                                                        $pathThumbnail = $keyMED["thumbnail_med"];
                                                        $arrayPathextThumbnail = explode(".", $pathThumbnail);
                                                        $extensionThumbnail = end($arrayPathextThumbnail);
                                                        $imageFileNameThumbnail = "thumbnail_".date("YmdHis").$arrNewMED["id"].".".$extensionThumbnail;
                                                        $imageTargetThumbnailPath = ROOT_DIR."uploads/thumbnail/".$imageFileNameThumbnail;
                                                        audit_copy(ROOT_DIR."uploads/thumbnail/".$keyMED["thumbnail_med"], $imageTargetThumbnailPath);
                                                        $arrNewMED["thumbnail_med"] = $imageFileNameThumbnail;
                                                }
                                    
                                                if (!checkValueIsNullTypeString($keyMED["video"]) &&
                                                    is_file(ROOT_DIR."uploads/media/".$keyMED["video"])) {
                                                        $pathVDO = $keyMED["video"];
                                                        $arrayPathexhVDO = explode(".", $pathVDO);
                                                        $extensiohVDO = end($arrayPathexhVDO);
                                                        $fileNameVDO = "MediaLesson_".date("YmdHis").$arrNewMED["id"].".".$extensiohVDO;
                                                        $targetVDOPath = ROOT_DIR."uploads/media/".$fileNameVDO;
                                                        audit_copy(ROOT_DIR."uploads/media/".$keyMED["video"], $targetVDOPath);
                                                        $arrNewMED["video"] = $fileNameVDO;

                                                        unset($arrNewMED["id"]);
                                                        $arrNewMED["lessons_id"] = $newLesID;

                                                        $this->db->insert("lms_med", $arrNewMED);
                                                }
                                        }
                                    }

                                    // File Document Of Lesson
                                    $fetchFILs = $this->funcQuery->query_result(
                                        "lms_fil", "", "", "",
                                        "lessons_id = ".$keyLES["les_id"]
                                    );
                                    if (!empty($fetchFILs)) {
                                        foreach ($fetchFILs as $keyFIL) {
                                            if (!checkValueIsNullTypeNumber($newLesID)) {
                                                $arrNewFIL = $keyFIL;
                                    
                                                if (!checkValueIsNullTypeString($keyFIL["path_file"]) &&
                                                    is_file(ROOT_DIR."uploads/thumbnail/".$keyFIL["path_file"])) {
                                                        $pathFIL = $keyFIL["path_file"];
                                                        $arrayPathextFIL = explode(".", $pathFIL);
                                                        $extensionFIL = end($arrayPathextFIL);
                                                        $fileNameFIL = "DocumentLesson_".date("YmdHis")."_".$keyFIL["id"].".".$extensionFIL;
                                                        $targetFILPath = ROOT_DIR."uploads/thumbnail/".$fileNameFIL;
                                                        audit_copy(ROOT_DIR."uploads/thumbnail/".$keyFIL["path_file"], $targetFILPath);
                                                        $arrNewFIL["path_file"] = $fileNameFIL;

                                                        if (!checkValueIsNullTypeString($arrNewFIL["path_file"]) &&
                                                            is_file(ROOT_DIR."uploads/thumbnail/".$arrNewFIL["path_file"])) {
                                                            unset($arrNewFIL["id"]);
                                                            $arrNewFIL["lessons_id"] = $newLesID;
                                                            $this->db->insert("lms_fil", $arrNewFIL);
                                                        }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // Scorm

                                    $fetch_scm = $this->funcQuery->query_row(
                                        "lms_scm", "", "", "",
                                        "lessons_id = ".$keyLES["les_id"]
                                    );
                                    if(isset($fetch_scm["path"]) && !checkValueIsNullTypeString($fetch_scm["path"])){
                                        $datascm = array(
                                            "lessons_id" => $newLesID
                                        );
                                        $fetchchkscm = $this->funcQuery->numrows(
                                            "lms_scm", "", "", "",
                                            "lessons_id = ".$newLesID
                                        );
                                        if($fetchchkscm==0){
                                            $this->db->insert("lms_scm",$datascm);
                                            $scmCode = $this->db->insert_id();
                                            $pathnew = "scorm_".$newLesID."_".$scmCode;
                                            $newDir = ROOT_DIR."uploads/scorm/".$pathnew;
                                            $oriDir = ROOT_DIR."uploads/scorm/".$fetch_scm["path"];
                                            mkdir($newDir);
                                            $targetPath = $oriDir."/".$fetch_scm["path"].".zip";
                                            if (is_dir($newDir) && is_file($oriDir."/".$fetch_scm["path"].".zip")) {
                                                audit_copy($targetPath, $newDir."/".$pathnew.".zip");
                                                $zip = new ZipArchive;
                                                $zip->open($targetPath);
                                                $zip->extractTo($newDir);
                                                $zip->close();
                                                $data_scm = array(
                                                    "path" => $pathnew
                                                );
                                                $this->db->where("id", $scmCode);
                                                $this->db->update("lms_scm", $data_scm);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        
                        // Duplicate Quiz of course
                        $fetchCheckQuiz = $this->funcQuery->query_result(
                            "lms_qiz", "", "", "",
                            "cos_id = ".$cosId
                        );
                        if (!empty($fetchCheckQuiz)) {
                            foreach ($fetchCheckQuiz as $keyQIZ) {
                                $arrNewQIZ = $keyQIZ;

                                unset($arrNewQIZ["qiz_id"]);
                                $arrNewQIZ["cos_id"] = $newCosID;
                                $arrNewQIZ["period_open"] = "0000-00-00 00:00:00";
                                $arrNewQIZ["period_end"] = "0000-00-00 00:00:00";
                                $arrNewQIZ["quiz_createby"] = $instructorId;
                                $arrNewQIZ["quiz_createdate"] = date("Y-m-d H:i:s");
                                $arrNewQIZ["quiz_modifiedby"] = $instructorId;
                                $arrNewQIZ["quiz_modifieddate"] = date("Y-m-d H:i:s");
                                $this->db->insert("lms_qiz", $arrNewQIZ);
                                $newQIZID = $this->db->insert_id();

                                // Question in Quiz
                                $fetchQUESs = $this->funcQuery->query_result(
                                    "lms_ques", "", "", "",
                                    "qiz_id = ".$keyQIZ["qiz_id"]
                                );
                                if (!empty($fetchQUESs)) {
                                    foreach ($fetchQUESs as $keyQUES) {
                                        $fetchQUESMuls = $this->funcQuery->query_row(
                                            "lms_ques_mul", "", "", "",
                                            "ques_id = ".$keyQUES["ques_id"]
                                        );
                                        if (!checkValueIsNullTypeNumber($newQIZID)) {
                                            $arrNewQUES = $keyQUES;

                                            unset($arrNewQUES["ques_id"]);
                                            $arrNewQUES["qiz_id"] = $newQIZID;
                                            $arrNewQUES["ques_createby"] = $instructorId;
                                            $arrNewQUES["ques_createdate"] = date("Y-m-d H:i:s");
                                            $arrNewQUES["ques_modifiedby"] = $instructorId;
                                            $arrNewQUES["ques_modifieddate"] = date("Y-m-d H:i:s");
                                            $this->db->insert("lms_ques", $arrNewQUES);
                                            $newQUESID = $this->db->insert_id();

                                            // Question in Quiz
                                            if (!checkValueIsNullTypeNumber($newQUESID) &&
                                                isset($fetchQUESMuls["mul_id"])) {
                                                $arrNewQUESMul = $fetchQUESMuls;

                                                unset($arrNewQUESMul["mul_id"]);
                                                $arrNewQUESMul["ques_id"] = $newQUESID;
                                                $arrNewQUESMul["mul_createby"] = $instructorId;
                                                $arrNewQUESMul["mul_createdate"] = date("Y-m-d H:i:s");
                                                $arrNewQUESMul["mul_modifiedby"] = $instructorId;
                                                $arrNewQUESMul["mul_modifieddate"] = date("Y-m-d H:i:s");
                                                $this->db->insert("lms_ques_mul", $arrNewQUESMul);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Duplicate Survey of course
                        $fetchCheckSurvey = $this->funcQuery->query_result(
                            "lms_survey", "", "", "",
                            "cos_id = ".$cosId
                        );
                        if (!empty($fetchCheckSurvey)) {
                            foreach ($fetchCheckSurvey as $keySurvey) {
                                $arrNewSV = $keySurvey;

                                unset($arrNewSV["sv_id"]);
                                $arrNewSV["cos_id"] = $newCosID;
                                $arrNewSV["survey_open"] = "0000-00-00 00:00:00";
                                $arrNewSV["survey_end"] = "0000-00-00 00:00:00";
                                $arrNewSV["sv_createby"] = $instructorId;
                                $arrNewSV["sv_createdate"] = date("Y-m-d H:i:s");
                                $arrNewSV["sv_modifiedby"] = $instructorId;
                                $arrNewSV["sv_modifieddate"] = date("Y-m-d H:i:s");
                                $this->db->insert("lms_survey", $arrNewSV);
                                $newSVID = $this->db->insert_id();

                                // Question in Survey
                                $fetchQUESSVs = $this->funcQuery->query_result(
                                    "lms_survey_de", "", "", "",
                                    "sv_id = ".$keySurvey["sv_id"]
                                );
                                if (!empty($fetchQUESSVs)) {
                                    foreach ($fetchQUESSVs as $keyQUESSV) {
                                        if (!checkValueIsNullTypeNumber($newSVID)) {
                                            $arrNewQUESSV = $keyQUESSV;

                                            unset($arrNewQUESSV["svde_id"]);
                                            $arrNewQUESSV["sv_id"] = $newSVID;
                                            $arrNewQUESSV["svde_createby"] = $instructorId;
                                            $arrNewQUESSV["svde_createdate"] = date("Y-m-d H:i:s");
                                            $arrNewQUESSV["svde_modifiedby"] = $instructorId;
                                            $arrNewQUESSV["svde_modifieddate"] = date("Y-m-d H:i:s");
                                            $this->db->insert("lms_survey_de", $arrNewQUESSV);
                                        }
                                    }
                                }
                            }
                        }
                        $output["status"] = 2;
                    }
                }
            }
            echo json_encode($output);
        }

    }
?>
