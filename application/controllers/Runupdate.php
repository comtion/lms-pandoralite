<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Runupdate extends CI_Controller {

    public function runUpdateDatalearnerSuccessButNewContent(){
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('Function_query_model', 'funcQuery', false);
        $this->funcQuery->loadDB();
        $arrCourses = array();
        $date_now = date('Y-m-d H:i');
        $fetchCourses = $this->funcQuery->query_result("lms_cos", "", "", "", "cos_id in (477,225,226,450,475,476)", "", "cos_id,lms_cos.cname_th,lms_cos.cname_eng,lms_cos.cname_jp");
        if (!empty($fetchCourses)) {
            foreach ($fetchCourses as $keyCourse) {
                $cname = $keyCourse["cname_th"];
                if (checkValueIsNullTypeString($cname)) {
                    $cname = $keyCourse["cname_eng"];
                    if (checkValueIsNullTypeString($cname)) {
                        $cname = $keyCourse["cname_jp"];
                    }
                }
                $lesson_arr = $this->funcQuery->query_result(
                    'lms_les','','','',
                    'cos_id="'.$keyCourse["cos_id"].'" and les_isDelete="0" and les_status="1" and ((time_start="0000-00-00 00:00:00" and time_end="0000-00-00 00:00:00") or 
                    (time_start <= "'.$date_now.'" and  time_end >= "'.$date_now.'"))','les_sequences ASC');
                $survey_arr = $this->funcQuery->query_result(
                    'lms_survey','','','',
                    'cos_id="'.$keyCourse["cos_id"].'" and sv_isDelete="0" and sv_status="1" and ((survey_open="0000-00-00 00:00:00" and survey_end="0000-00-00 00:00:00")
                        or (survey_open <= "'.$date_now.'" and  survey_end >= "'.$date_now.'"))',
                    'sv_id ASC');
                $arrCourses[$keyCourse["cos_id"]] = array(
                    "name" => $cname,
                    "lesson"    => $lesson_arr,
                    "survey"    => $survey_arr
                );
            }
        }
        

        $fetchCosen = $this->funcQuery->raw_query("SELECT 
    lms_cos_enroll.cosen_id,
    lms_cos_enroll.emp_id,
    lms_cos_enroll.cosen_finishtime,
    lms_emp.fullname_th,
    lms_emp.fullname_en,
    lms_emp.email,
    lms_cos_enroll.cos_id,
    lms_cos.cname_th,
    lms_cos.cname_eng,
    lms_cos.cname_jp,
    COUNT(CASE WHEN lms_cos_enroll.cosen_status_sub = 1 THEN 1 END) AS completed_count,
    COUNT(CASE WHEN lms_cos_enroll.cosen_status_sub IN (0, 2) THEN 1 END) AS in_progress_count,
    (SELECT COUNT(CASE WHEN lms_les.les_createdate < '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_les 
     WHERE lms_cos_enroll.cos_id = lms_les.cos_id 
     AND lms_les.les_isDelete = 0) AS old_lesson,
    (SELECT COUNT(CASE WHEN lms_les.les_createdate >= '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_les 
     WHERE lms_cos_enroll.cos_id = lms_les.cos_id 
     AND lms_les.les_isDelete = 0) AS new_lesson,
    (SELECT COUNT(lms_les.les_id) 
     FROM lms_les 
     WHERE lms_cos_enroll.cos_id = lms_les.cos_id 
     AND lms_les.les_isDelete = 0) AS total_lesson,
    (SELECT COUNT(CASE WHEN lms_les_tc.learn_status = 2 THEN 1 END) 
     FROM lms_les_tc 
     WHERE lms_cos_enroll.cosen_id = lms_les_tc.cosen_id 
     AND lms_les_tc.les_id IN 
         (SELECT lms_les.les_id 
          FROM lms_les 
          WHERE lms_les.cos_id = lms_cos_enroll.cos_id 
          AND lms_les.les_isDelete = 0)) AS learn_lesson,
    (SELECT COUNT(CASE WHEN lms_qiz.quiz_createdate < '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_qiz 
     WHERE lms_cos_enroll.cos_id = lms_qiz.cos_id 
     AND lms_qiz.quiz_isDelete = 0) AS old_quiz,
    (SELECT COUNT(CASE WHEN lms_qiz.quiz_createdate >= '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_qiz 
     WHERE lms_cos_enroll.cos_id = lms_qiz.cos_id 
     AND lms_qiz.quiz_isDelete = 0) AS new_quiz,
    (SELECT COUNT(lms_qiz.qiz_id) 
     FROM lms_qiz 
     WHERE lms_cos_enroll.cos_id = lms_qiz.cos_id 
     AND lms_qiz.quiz_isDelete = 0) AS total_quiz,
    (SELECT COUNT(DISTINCT CASE WHEN lms_qiz_tc.qiz_status = 3 THEN lms_qiz_tc.qiz_id END) 
     FROM lms_qiz_tc 
     WHERE lms_cos_enroll.cosen_id = lms_qiz_tc.cosen_id 
     AND lms_qiz_tc.qiz_id IN 
         (SELECT lms_qiz.qiz_id 
          FROM lms_qiz 
          WHERE lms_qiz.cos_id = lms_cos_enroll.cos_id 
          AND lms_qiz.quiz_isDelete = 0)) AS test_quiz,
    (SELECT COUNT(CASE WHEN lms_survey.sv_createdate < '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_survey 
     WHERE lms_cos_enroll.cos_id = lms_survey.cos_id 
     AND lms_survey.sv_isDelete = 0) AS old_survey,
    (SELECT COUNT(CASE WHEN lms_survey.sv_createdate >= '2025-01-01 00:00:00' THEN 1 END) 
     FROM lms_survey 
     WHERE lms_cos_enroll.cos_id = lms_survey.cos_id 
     AND lms_survey.sv_isDelete = 0) AS new_survey,
    (SELECT COUNT(lms_survey.sv_id) 
     FROM lms_survey 
     WHERE lms_cos_enroll.cos_id = lms_survey.cos_id 
     AND lms_survey.sv_isDelete = 0) AS total_survey,
    (SELECT COUNT(CASE WHEN lms_qn_user.qnu_status = 1 THEN 1 END) 
     FROM lms_qn_user 
     WHERE lms_cos_enroll.cosen_id = lms_qn_user.cosen_id 
     AND lms_qn_user.sv_id IN 
         (SELECT lms_survey.sv_id 
          FROM lms_survey 
          WHERE lms_survey.cos_id = lms_cos_enroll.cos_id 
          AND lms_survey.sv_isDelete = 0) 
     GROUP BY lms_qn_user.sv_id) AS test_survey
FROM 
    lms_cos_enroll
INNER JOIN 
    lms_cos ON lms_cos_enroll.cos_id = lms_cos.cos_id
INNER JOIN 
    lms_emp ON lms_cos_enroll.emp_id = lms_emp.emp_id
WHERE 
    lms_cos_enroll.cos_id IN (477, 225, 226, 450, 475, 476)
    AND lms_cos_enroll.cosen_status = 1
    AND lms_emp.emp_isDelete = 0
    AND lms_emp.emp_id IN (
        SELECT lms_usp.emp_id
        FROM lms_usp
        WHERE lms_usp.inactivedate IS NULL 
        OR lms_usp.inactivedate > '2025-03-12'
    )
GROUP BY lms_cos_enroll.cosen_id;
");
        if (!empty($fetchCosen)) {
            foreach ($fetchCosen as $keyCosen) {
                if (isset($arrCourses[$keyCosen["cos_id"]])) {
                    $updateLesson = 0;
                    $updateSurvey = 0;

                    if (intval($keyCosen["completed_count"]) == 1) {
                        if (intval($keyCosen["old_lesson"]) == intval($keyCosen["learn_lesson"])) {
                            $lesson_arr = $arrCourses[$keyCosen["cos_id"]]["lesson"];
                            if(countArray($lesson_arr)>0){
                                foreach ($lesson_arr as $key_lesson => $value_lesson) {
                                    $fetch_chktc = $this->funcQuery->query_row(
                                        'lms_les_tc','','','',
                                        'les_id = '.$value_lesson["les_id"].' and cosen_id="'.$keyCosen["cosen_id"].'"'
                                    );
                                    if (isset($fetch_chktc["lestc_id"])) {
                                        if (intval($fetch_chktc["learn_status"]) != 2) {
                                            $this->funcQuery->updateData(
                                                "lms_les_tc",
                                                "lestc_id = ".$fetch_chktc["lestc_id"],
                                                array(
                                                    "learn_status" => 2
                                                )
                                            );
                                            $updateLesson++;
                                        }
                                    } else {
                                        $this->funcQuery->insertData(
                                            "lms_les_tc",
                                            array(
                                                "cosen_id"      => $keyCosen["cosen_id"],
                                                "les_id"        => $value_lesson["les_id"],
                                                "emp_id"        => $keyCosen["emp_id"],
                                                "learn_status"  => 2,
                                            )
                                        );
                                        $updateLesson++;
                                    }
                                }
                            }
                        }
                        if (intval($keyCosen["old_survey"]) == 1 && intval($keyCosen["test_survey"]) == 0) {
                            $survey_arr = $arrCourses[$keyCosen["cos_id"]]["survey"];
                            
                            if(countArray($survey_arr)>0){
                                foreach ($survey_arr as $key_sv => $value_sv) {
                                    $fetch_status = $this->funcQuery->query_row('lms_qn_user','','','','sv_id="'.$value_sv['sv_id'].'" and cosen_id="'.$keyCosen["cosen_id"].'"');
                                    if(!isset($fetch_status["qnu_id"])){
                                        $updateSurvey++;
                                        $this->funcQuery->insertData(
                                            "lms_qn_user",
                                            array(
                                                "cosen_id"          => $keyCosen["cosen_id"],
                                                "sv_id"             => $value_sv["sv_id"],
                                                "emp_id"            => $keyCosen["emp_id"],
                                                "qnu_status"        => 1,
                                                "qnu_suggestion"    => "",
                                                "qnu_datetime"      => $keyCosen["cosen_finishtime"]
                                            )
                                        );
                                    }
                                }
                            }
                        }
                    } else {
                        if (intval($keyCosen["old_lesson"]) == intval($keyCosen["learn_lesson"])) {
                            $lesson_arr = $arrCourses[$keyCosen["cos_id"]]["lesson"];
                            if(countArray($lesson_arr)>0){
                                foreach ($lesson_arr as $key_lesson => $value_lesson) {
                                    $fetch_chktc = $this->funcQuery->query_row(
                                        'lms_les_tc','','','',
                                        'les_id = '.$value_lesson["les_id"].' and cosen_id="'.$keyCosen["cosen_id"].'"'
                                    );
                                    if (isset($fetch_chktc["lestc_id"])) {
                                        if (intval($fetch_chktc["learn_status"]) != 2) {
                                            $this->funcQuery->updateData(
                                                "lms_les_tc",
                                                "lestc_id = ".$fetch_chktc["lestc_id"],
                                                array(
                                                    "learn_status" => 2
                                                )
                                            );
                                            $updateLesson++;
                                        }
                                    } else {
                                        $this->funcQuery->insertData(
                                            "lms_les_tc",
                                            array(
                                                "cosen_id"      => $keyCosen["cosen_id"],
                                                "les_id"        => $value_lesson["les_id"],
                                                "emp_id"        => $keyCosen["emp_id"],
                                                "learn_status"  => 2,
                                            )
                                        );
                                        $updateLesson++;
                                    }
                                }
                            }
                        }
                    }
                    echo $keyCosen["cosen_id"].",".$arrCourses[$keyCosen["cos_id"]]["name"].",".$keyCosen["fullname_th"].",".$keyCosen["email"].",".(intval($keyCosen["completed_count"]) == 1 ? "completed" : "inprogress").",".$updateLesson.",".$updateSurvey."<br>";
                }
            }
        }
    }
}
