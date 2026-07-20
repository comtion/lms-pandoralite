<?php
class Survey_model extends CI_Model {
      public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
      }
      public function loadDB(){
        $this->load->database();
      }
      public function closeDB(){
        $this->db->close();
      }

      public function getCourse($ccode, $lang)
      {
        $this->db->from('lms_cos');
        $this->db->where('ccode', $ccode);
        $this->db->where('lang', $lang);
        $this->db->where('hidden', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          $result = $query->result_array();
          return $result[0];
        }
      }

      public function isApproved($emp_c, $course, $ccode) {
        $this->db->select('emp_c');
        $this->db->from('lms_ens');
        $this->db->where('emp_c', $emp_c);
        $this->db->where('course_id', $ccode);
        $this->db->where('enroll_status1', 'yes');
        if($course['approve_pp'] == 2) {
          $this->db->where('enroll_status2', 'yes');
        }
        $query = $this->db->get();
        $row = $query->row_array();

        if(empty($row)) {
          return FALSE;
        }
        return TRUE;
      }

      public function create($data){
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->set('time_create', $time);
        $this->db->set('time_mod', $time);
        $this->db->insert('lms_sv', $data);
        $survey_id = $this->db->insert_id();
        if($data['questionnaire_id']!=""){
          $this->db->from('lms_que_de');
          $this->db->where('questionnaire_id', $data['questionnaire_id']);
          $query = $this->db->get();
          $result = $query->result_array();
          foreach ($result as $each) {
            $data = array(
              'title_svq'     => $each['heading'],
              'question' => $each['detail'],
              'survey_id' => $survey_id,
              'type' => '1'
            );
            $this->db->insert('lms_svq', $data);
          }
        }

      }

      public function edit($data){
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->set('time_mod', $time);
        $this->db->where('scode', $data['scode']);
        $this->db->where('lang', $data['lang']);
        $this->db->update('lms_sv', $data);
      }

      public function delete($scode)
      {
        $this->db->where('scode', $scode);
        $this->db->delete('lms_sv');
        $this->db->where('survey_id', $scode);
        $this->db->delete('lms_svq');
      }

      public function deleteSQ($buttDSQ,$scode)
      {
        $this->db->where('id', $buttDSQ);
        $this->db->where('survey_id', $scode);
        $this->db->delete('lms_svq');
      }

      public function createSQ($data)
      {
        $this->db->insert('lms_svq', $data);
      }

      public function getLang($scode)
      {
        $this->db->select('lang');
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $query = $this->db->get();
        $result = $query->result_array();
        $lang = 'thailand';
        foreach ($result as $each) {
          $lang = $each['lang'];
        }
        return $lang;
      }

      public function getAllData( $scode )
      {
        $this->updateTimeOut();
        $survey = array();
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $row) {
          $survey[$row['lang']] = $row;
        }
        return $survey;
      }

      public function getSQ($scode)
      {
        $this->updateTimeOut();
        $this->db->from('lms_svq');
        $this->db->where('survey_id', $scode);
        $query = $this->db->get();
        return $query->result_array();
      }
      public function getSQtitleHead($scode)
      {
        $this->updateTimeOut();
        $this->db->select('lms_svq.title_svq');
        $this->db->distinct();
        $this->db->from('lms_svq');
        $this->db->where('survey_id', $scode);
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getSVQ1($scode,$num)
      {
        $this->db->select('lms_sva_tc.sq_id, lms_sva_tc.ans');
        $this->db->from('lms_svq');
        $this->db->join('lms_sva_tc', 'lms_svq.id = lms_sva_tc.sq_id', 'right');
        $this->db->join('lms_sv', 'lms_svq.survey_id = lms_sv.id', 'inner');
        $this->db->where('lms_sv.scode', $scode);
        $this->db->where('lms_svq.type', '1');
        $this->db->where('lms_sva_tc.ans', $num);
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getSVQ2($scode)
      {
        $this->db->select('lms_sva_tc.sq_id, lms_sva_tc.ans');
        $this->db->from('lms_svq');
        $this->db->join('lms_sva_tc', 'lms_svq.id = lms_sva_tc.sq_id', 'right');
        $this->db->where('lms_svq.survey_id', $scode);
        $this->db->where('lms_svq.type', '0');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getEmpdid($scode)
      {
        $this->db->from('lms_sv_tc');
        $this->db->where('survey_id', $scode);
        $query = $this->db->get();
        $e=0;
        foreach ($query->result_array() as $row) {
          $e++;
        }
        return $e;
      }

      public function saveSQA($sqans)
      {
        $this->updateTimeOut();
        $this->db->insert('lms_sva_tc', $sqans);
      }

      public function savedoSV($emp_c,$scode,$Suggestion_head)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->set('time', $time);
        $data = array(
          'emp_c'     => $emp_c,
          'survey_id' => $scode,
          'Suggestion_head' => $Suggestion_head
        );
          $this->db->insert('lms_sv_tc', $data);
      }

      public function checkdoSV($scode,$emp_c)
      {
        $this->updateTimeOut();
        $this->db->from('lms_sv_tc');
        $this->db->where('survey_id', $scode);
        $this->db->where('emp_c', $emp_c);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? TRUE: FALSE;
      }

      public function getSurvey($scode, $lang)
      {
        $this->updateTimeOut();
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $this->db->where('lang', $lang);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          $result = $query->result_array();
          return $result[0];
        }
      }

      public function checkCode($scode)
      {
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? TRUE: FALSE;
      }

      public function checkHide($scode)
      {
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $this->db->where('hidden', 0);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? TRUE: FALSE;
      }

      public function getCode()
      {
        $this->db->select_max('scode');
        $this->db->from('lms_sv');
        $query = $this->db->get();
        $row = $query->row();
        return ($row->scode) == '' ? 1 : ($row->scode) + 1;
      }
      public function getcCode($scode)
      {
        $this->db->select('course_id');
        $this->db->distinct();
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query->num_rows() > 0 ? $result[0]['course_id'] : FALSE;
      }
      public function getsId($scode)
      {
        $this->db->select('id');
        $this->db->distinct();
        $this->db->from('lms_sv');
        $this->db->where('scode', $scode);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query->num_rows() > 0 ? $result[0]['id'] : FALSE;
      }

      public function getAllSurvey($ccode, $lang, $role)
      {
        $this->updateTimeOut();
        $this->db->from('lms_sv');
        $this->db->where('course_id', $ccode);
        if(!in_array($role, array("superadmin","admintis", "admin", "manager")))
        {
        $this->db->where('hidden', 1);
        }
        $this->db->where('lang', $lang);
        $query = $this->db->get();
        return $query->result_array();
      }


      public function getQuestionnaire()
      {
        $this->updateTimeOut();
        $this->db->from('lms_que');
        $this->db->where('hidden', '1');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function updateTimeOut()
      {
        $this->db->select('id, scode, time_open, time_end, hidden');
        $this->db->from('lms_sv');
        $query = $this->db->get();
        $dt = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
        $current_time = $dt->format('Y-m-d H:i');
        foreach ($query->result_array() as $row) {
          if($row['time_end'] == '0000-00-00 00:00:00' || $row['time_open'] == '0000-00-00 00:00:00'){}
          else{
          if(($current_time > $row['time_end'] || $current_time < $row['time_open'] )){
            $data = array(
              'hidden' => 0
            );
            $this->db->where('scode', $row['scode']);
            $this->db->update('lms_sv', $data);
          }
         }
        }
      }

      public function getAllStatus($surveys)
      {
        $sess = $this->session->userdata("user");
    		$emp_c = $sess['emp_c'];
        $allStatus = array();
        foreach ($surveys as $survey) {
          $allStatus[$survey['scode']] = ($this->checkdoSV($survey['scode'], $emp_c)) ? 'done' : 'noProgress';
        }
        return $allStatus;
      }

      public function getDataQuestionnaire($questionnaire_id)
      {
        $this->db->select('title, explanation, suggestion_status');
        $this->db->from('lms_que');
        $this->db->where('id', $questionnaire_id);
        $query = $this->db->get();
        $allStatus = $query->result_array();
        $data = array();
        foreach ($allStatus as $key => $value) {
          $data[$key] = $value;
        }
        return $data;
      }

      function fetch_Suggestion($sq_id){
        $this->db->select("Suggestion"); 
        $this->db->from('lms_sva_tc');
        $this->db->where('sq_id', $sq_id);
        $this->db->where_not_in('Suggestion', "");
        $query = $this->db->get();
        return $query->result();
      }


      function fetch_Suggestion_head($survey_id){
        $this->db->select("Suggestion_head"); 
        $this->db->from('lms_sv_tc');
        $this->db->where('survey_id', $survey_id);
        $this->db->where_not_in('Suggestion_head', "");
        $query = $this->db->get();
        return $query->result();
      }
}
?>
