<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SaveEmailLog extends CI_Controller {

    public function saveLog() {
      var_dump($_POST);
      // $data = array(
      //   // 'email' => $this->input->post('email'),
      //   'time' => date('Y-m-d H:i:s')
      // );

      // var_dump($data);
      // $this->load->model('SaveEmailLog_model', 'saveEmailLog', FALSE);
      // $this->saveEmailLog->loadDB();
      // $this->saveEmailLog->saveLog($data);
      // echo "<script>alert('Successfully Saved');</script>";
    }

    public function updateLog() {
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("Function_query_model", "funcQuery", FALSE);
        $this->funcQuery->loadDB();
        $output = array("status" => 0);
        $dateCurrent = date("Y-m-d");
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

            $fetchEmps = $this->funcQuery->query_result("lms_emp", "", "", "", "status = 1 and emp_isDelete = 0", "", "emp_c");
            if (!empty($fetchEmps)) {
                foreach ($fetchEmps as $keyEmp => $valueEmp) {
                    if (!in_array($valueEmp["emp_c"], $arrEmployees)) {
                        array_push($arrEmployees, $valueEmp["emp_c"]);
                    }
                }
            }
    
            $dataConvert = array();
            if (count($data) > 0 && isset($data['events'])) {
                $dataConvert = (array) $data['events'];
                foreach($dataConvert as $key => $input){
                    $arrOfRecord = (array) $input;
                    if (in_array($arrOfRecord["email"], $arrEmployees)) {
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
            if ($sendAll > 0) {
                $fetchCheckDuplicate = $this->funcQuery->query_row("lms_lg_email", "", "", "", "lgm_date = '".$dateCurrent."'");
                if (!isset($fetchCheckDuplicate["lgm_id"])) {
                    $this->db->insert('lms_lg_email', $dataOutput);
                } else {
                    $where = "lgm_date = '".$dateCurrent."'";
                    $this->db->where($where);
                    $this->db->update('lms_lg_email', $dataOutput);
                }
            }
            $output["status"] = 2;
        }
        curl_close($ch);
        echo json_encode($output);
    }
}


?>