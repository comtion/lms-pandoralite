<?php
    
    include('config_db.php');
	date_default_timezone_set("Asia/Bangkok");
    $dateCurrent = date('Y-m-d');
    
    $current = strtotime( date('Y-m-d', strtotime('-1 day')) );
    $last = strtotime( date('Y-m-d', strtotime('-1 day')) );

    while( $current <= $last ) {

        $daterunLoop = date('Y-m-d', $current );
        runSaveLog($daterunLoop, $db);
        $current = strtotime( '+1 day', $current );
    }

    function runSaveLog($dateCurrent, $db) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/statistics/events?limit=5000&offset=0&startDate='.$dateCurrent.'&endDate='.$dateCurrent.'&sort=desc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array();
        $headers[] = 'Accept: application/json';
        $brevoApiKey = getenv('BREVO_API_KEY');
        if ($brevoApiKey !== false && $brevoApiKey !== '') {
            $headers[] = 'api-key: ' . $brevoApiKey;
        }
        $headers[] = 'Content-Type: application/json';  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (isset($result)) {
            $data = (array) json_decode($result);
            $sendAll = 0;
            $sendSuccess = 0;
            $sendError = 0;
            $arrSaveBeforeJson = array();
    
            $arrEmployees = array();
            $db->where("status = 1 and emp_isDelete = 0");
            $fetchEmps = $db->get('lms_emp', null, 'emp_c');
            if (!empty($fetchEmps)) {
                foreach ($fetchEmps as $keyEmp => $valueEmp) {
                    if (!in_array($valueEmp["emp_c"], $arrEmployees)) {
                        array_push($arrEmployees, $valueEmp["emp_c"]);
                    }
                }
            }
    
            if (count($data) > 0) {
                $dataConvert = (array) $data['events'];
                foreach($dataConvert as $key => $input){
                    $arrOfRecord = (array) $input;
                    if ($arrOfRecord["from"] == "noreply@elearning.isuzu.co.th" && in_array($arrOfRecord["email"], $arrEmployees)) {
                        array_push( $arrSaveBeforeJson, $arrOfRecord);
                        if ($arrOfRecord["event"] == "requests") {
                            $sendAll++;
                        } else if ($arrOfRecord["event"] == "delivered") {
                            $sendSuccess++;
                        } else if (!in_array($arrOfRecord["event"], array("opened", "clicks"))) {
                            $sendError++;
                        }
                    }
                }
            }
            $dataOutput = array(
                "lgm_date"          => $dateCurrent,
                "lgm_send"          => $sendAll,
                "lgm_send_complete" => $sendSuccess,
                "lgm_send_error"    => $sendError,
                "lgm_json"          => json_encode($dataConvert)
            );
            print_r($dataOutput);
            if ($sendAll > 0) {
                
                $where = 'lgm_date = "'.$dateCurrent.'"';
                $db->where($where);
                $fetchCheckDuplicate = $db->getOne('lms_lg_email');
                if (!isset($fetchCheckDuplicate["lgm_id"])) {
                    $db->insert('lms_lg_email', $dataOutput);
                } else {
                    $where = 'lgm_date = "'.$dateCurrent.'"';
                    $db->where($where);
                    $db->update("lms_lg_email", $dataOutput);
                }
            }
        }
        curl_close($ch);
    }
?>