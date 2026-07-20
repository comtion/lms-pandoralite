<?php
class Quiz_model extends CI_Model {
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  /*These Function is Open/Close the database.*/
  public function loadDB(){
    $this->load->database();
  }

  public function closeDB(){
    $this->db->close();
  }

  public function edit($data)
  {
    $this->db->set('time_mod', 'NOW()', FALSE);
    $data['max_score'] = $this->updateScore($data['qcode']);
    $cond = array("qcode" => $data['qcode'] , 'lang' => $data['lang']);
    $this->db->update('lms_qiz', $data, $cond);
  }

  private function updateStatus()
  {
    $this->db->select('id, time_open, time_end, hidden');
    $this->db->from('lms_qiz');
    $query = $this->db->get();
    $current_time = date('Y-m-d H:i');
    foreach ($query->result_array() as $row) {
      if($current_time >= $row['time_open'] && $current_time < $row['time_end'] || $row['time_end'] == '0000-00-00 00:00:00' ){
        $data = array(
          'hidden' => 1
        );
      } else {
        $data = array(
          'hidden' => 0
        );
      }
      $data['max_score'] = $this->updateScore($row['qcode']);
      $this->db->where('id', $row['id']);
      $this->db->update('lms_qiz', $data);
    }
  }

  public function create($data){
    $this->db->set('time_create', 'NOW()', FALSE);
    $this->db->set('time_mod', 'NOW()', FALSE);
    $data['max_score'] = $this->updateScore($data['qcode']);
    $this->db->insert('lms_qiz', $data);
    return $this->db->insert_id();
  }

  public function updateScore($qcode)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $this->db->from('lms_qst');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $sum = 0;
    foreach ($query->result_array() as $row) {
      $sum += intval($row['score']);
    }
    return $sum;
  }

  public function deleteQiz($code)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->db->where('quiz_id', $code);
    $this->db->delete('lms_qiz_tc');

    $this->db->where('qcode', $code);
    $this->db->where('lang', $lang);
    $this->db->delete('lms_qiz');
  }

  public function getAllData( $code )
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $code);
    $query = $this->db->get();
    $rows = $query->result_array();
    foreach ($rows as $row) {
      $result[$row['lang']] = $row ;
    }
    return $result;
  }

  public function getCode()
  {
    $this->db->select_max('qcode');
    $this->db->from('lms_qiz');
    $query = $this->db->get();
    $row = $query->row();

    return ($row->qcode) == '' ? 1 : ($row->qcode) + 1;
  }

  public function getAllQuiz($ccode, $lang)
  {
      $this->db->distinct();
    $this->db->from('lms_qiz');
    $this->db->where('courses_id', $ccode);
    $this->db->where('lang', $lang);
    $this->db->where('hidden', '1');
    $query = $this->db->get();
    return $query->result_array();
  }


    public function count_Quiz($ccode, $lang)
    {
      $this->db->select('qcode,quiz_name,lang');
                    $this->db->from('lms_qiz');
                    $this->db->where('courses_id', $ccode);
                    $this->db->where('lang', $lang);
                    $this->db->where('hidden', '1');
                    $query = $this->db->get();
                    $result = $query->result_array();
                    return $query->num_rows();
    }

  public function getLangQiz($qcode)
  {
    $this->db->select('lang');
    $this->db->from('lms_qiz');
    $this->db->where('id', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return $query->num_rows() > 0 ? $result : array('thailand');
  }

  public function getCusGrade($qcode)
  {
    $this->db->from('lms_cug');
    $this->db->where('quizs_id', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return $query->num_rows() <= 0 ? : $result[0];
  }

  public function createCug($data)
  {
    $this->db->insert('lms_cug', $data);
  }

  public function checkCode($code)
  {
    $this->db->select('id');
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $code);
    $query = $this->db->get();
    return $query->num_rows() > 0 ? TRUE: FALSE;
  }

  public function getQcode($q_id)
  {
    $this->db->select('quiz_id');
    $this->db->from('lms_qst');
    $this->db->where('id', $q_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $query->num_rows() <= 0 ? : $result[0]['quiz_id'];
  }

  public function createTC($qcode, $emp_c)
  {
    $this->db->from('lms_qiz_tc');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('emp_c', $emp_c);
    $query = $this->db->get();
    $tcNum = $query->num_rows();

    $this->db->set('time_mod', 'NOW()', FALSE);
    $this->db->set('time_finish', 'NOW()', FALSE);
    if ($tcNum <= 0){
      $this->db->set('time_start', 'NOW()', FALSE);
      $this->db->insert('lms_qiz_tc', array('emp_c' => $emp_c, 'quiz_id' => $qcode, 'sum_score' => '0', 'currentpage' => '0', 'layout' => '0', 'submit' => 'noProgress'));
    } else {
      $result = $query->result_array();
      $data = $result[$tcNum-1];
      $data['submit'] = 'noProgress';
      unset($data['id']);
      unset($data['time_start']);
      unset($data['time_finish']);
      unset($data['time_mod']);
      $this->db->set('time_start', 'NOW()', FALSE);
      $this->db->insert('lms_qiz_tc', $data);
    }
  }

  public function getAllStatus($quizes, $emp_c)
  {
    $status = array();
    foreach ($quizes as $quiz) {
      $this->db->from('lms_qiz_tc');
      $this->db->where('quiz_id', $quiz['qcode']);
      $this->db->where('emp_c', $emp_c);
      $this->db->order_by("sum_score", "desc");
      $query = $this->db->get();
      $result = $query->result_array();
      if ($query->num_rows() > 0){
        $status[$quiz['qcode']] = $result[0];
        $status[$quiz['qcode']]['label'] = 'fail';
        $status[$quiz['qcode']]['ent'] = $this->checkEntrance($quiz['qcode']);
        $cond = $this->getCondition($quiz['qcode']);
        if ($status[$quiz['qcode']]['submit'] == 'done' && $status[$quiz['qcode']]['sum_score'] >= $cond && $status[$quiz['qcode']]['sum_score'] != 0){
          $status[$quiz['qcode']]['label'] = 'pass';
        }
      }
    }
    return $status;
  }

  public function getAllStatusTopic($quizes, $emp_c)
  {
    $status = array();
    foreach ($quizes as $quiz) {
      $this->db->from('lms_qiz_tc');
      $this->db->where('quiz_id', $quiz['qcode']);
      $this->db->where('emp_c', $emp_c);
      $this->db->order_by("sum_score", "desc");
      $query = $this->db->get();
      $result = $query->result_array();
      if ($query->num_rows() > 0){
        $status[$quiz['qcode']] = $result[0];
        $status[$quiz['qcode']]['label'] = 'fail';
        $status[$quiz['qcode']]['ent'] = $this->checkEntrance($quiz['qcode']);
        $cond = $this->getCondition($quiz['qcode']);
        if ($status[$quiz['qcode']]['submit'] == 'done' && $status[$quiz['qcode']]['sum_score'] >= $cond && $status[$quiz['qcode']]['sum_score'] != 0){
          $status[$quiz['qcode']]['label'] = 'pass';
        }
      }
    }
    return $status;
  }

  public function checkEntrance($qcode)
  {
    $sess = $this->session->userdata("user");
    $this->db->from('lms_qiz_tc');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('emp_c', $sess['emp_c']);
    $this->db->where('submit', 'done');
    $this->db->order_by("id", "desc");
    $query = $this->db->get();
    $tcNum = $query->num_rows();

    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return (intval($result[0]['attempts']) == 0 || (intval($result[0]['n_attempts']) > $tcNum) && intval($result[0]['n_attempts']) != $tcNum);
  }

  public function getCcode($qcode)
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['courses_id'];
  }

  public function getQizNumber($qcode)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $ccode = $this->getCcode($qcode);
    $this->db->from('lms_qiz');
    $this->db->where('courses_id', $ccode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    return sizeof($result);
  }

  public function countTC($trans)
  {
    $count = 0;$qizNum=NULL;
    foreach ($trans as $each) {
      if (empty($qizNum)) {
        $qizNum = $this->getQizNumber($each['quiz_id']);
      }
      $cond = $this->getCondition($each['quiz_id']);
      if ($each['submit'] == 'done' /*&& floatval($each['sum_score']) >= $cond*/ && floatval($each['sum_score']) >= 0){ // add >, = 0
        $count++;
      }
    }

    if ($qizNum == $count && $count != 0) {
      return 'true';
    } else if ($count > 0) {
      return 'half';
    }
    return 'false';
  }

  public function getCondition($qcode)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    return ($result[0]['condition'] == NULL) ? 0: (floatval($result[0]['condition'])/100)*floatval($result[0]['max_score']);
  }

  public function isLimited($qcode)
  {
    $user = $this->session->userdata("user");
		$emp_c = $user['emp_c'];
    $this->db->from('lms_qiz_tc');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('emp_c', $emp_c);
    $this->db->where('submit', 'done');
    $query = $this->db->get();
    $time = $query->num_rows();
    $limit = $this->getLimit($qcode);
    return ($limit && ($time >= $limit));
  }
  public function getLimit($qcode)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $this->db->select('attempts, n_attempts');
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    $result = $result[0];
    if (intval($result['attempts']) == 0){
      return FALSE;
    } else{
      return intval($result['n_attempts']);
    }
  }

  public function getPreQuiz($ccode)
  {
    $pre = array();
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $user = $this->session->userdata("user");
		$emp_c = $user['emp_c'];
    $this->db->from('lms_qiz');
    $this->db->where('courses_id', $ccode);
    $this->db->where('lang', $lang);
    $this->db->where('quiz_type', '0');
    $query = $this->db->get();

    foreach ($query->result_array() as $row) {
      $code = $row['qcode'];
      $this->db->from('lms_qiz_tc');
      $this->db->where('quiz_id', $code);
      $this->db->where('emp_c', $user['emp_c']);
      $this->db->where('submit', 'done');
      $this->db->order_by("id", "desc");
      $query = $this->db->get();
      $result = $query->result_array();
      $tcNum = $query->num_rows();
      $score = isset($result[0]['sum_score'])?$result[0]['sum_score']: 0;

      $cond = $this->getCondition($code);
      if ($score < $cond){
        if ($row['attempts'] == 0){
          array_push($pre, $code);
        } else {
          if ($tcNum < $row['n_attempts']) {
            array_push($pre, $code);
          }
        }
      }
    }
    return $pre;
  }

  public function setScore($qcode, $emp_c, $score)
  {
    $this->db->select('max_score');
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    $max_score = $result[0]['max_score'];
    $this->createTC($qcode, $emp_c);

    if ($score > $max_score)
      $score = $max_score;

    $this->db->update('lms_qiz_tc', array('sum_score' => $score, 'submit' => 'done'),  array('emp_c' => $emp_c, 'quiz_id' => $qcode));
  }

  public function getScore($emp_c)
  {
    $this->db->from('lms_qiz_tc');
    $this->db->where('emp_c', $emp_c);
    $query = $this->db->get();
    $temp = array();
    foreach ($query->result_array() as $row) {
      $temp[$row['quiz_id']] = $row['sum_score'];
    }
    return $temp;
  }

  public function updateMaxScore($qcode, $score)
  {
    $this->db->update('lms_qiz', array('max_score' => $score),  array('qcode' => $qcode));
  }

  public function getQuiz($code, $lang=NULL)
  {
    if ($lang==NULL){
      $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    }
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $code);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    return ($query->num_rows() > 0)? $result[0]:FALSE;
  }

  public function getQuizes($code)
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $code);
    $query = $this->db->get();
    $result = $query->result_array();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      $quizes = array();
      foreach ($result as $row) {
        $quizes[$row['lang']] = $row;
      }
      return $quizes;
    }
  }

  public function getEditIntro($code)
  {
    $this->db->from('lms_lag');
    $query = $this->db->get();
    $langs = $query->result_array();
    $editIntro = array();
    foreach ($langs as $row) {
      $lang = $row['lang'];
      $quiz = $this->getQuiz($code, $lang);
      if ($quiz){
        array_push($editIntro, $lang);
      }
    }
    return $editIntro;
  }

  public function setAllScore($ccode, $qcode, $score, $emps)
  {
    $check=false;
    if (empty($emps)){
      $this->db->from('lms_ens');
      $this->db->where('course_id', $ccode);
      $this->db->where('ens_status', 1);
      $query = $this->db->get();
      $emps = $query->result_array();
      $check=true;
    } else {
      $emps = explode(',',$emps);
    }
    foreach ($emps as $row) {
      $code = ($check)?$row['emp_c']:$row;
      $this->setScore($qcode, $code,$score);
      $this->finishCos($ccode, $code);
    }

  }

  private function getmyGrade($ccode,$emp_c)
  {
    $this->db->from('lms_gbk');
    $this->db->where('ccode', $ccode);
    $this->db->where('emp_c', $emp_c);
    $query = $this->db->get();
    $myGrade =  $query->result_array();
    return $myGrade[0];
  }

  public function finishCos($ccode, $emp_c)
  {
    $data = array(
      'finish_time'=>'NOW()'
    );
    $cond = array(
      'emp_c' => $emp_c,
      'course_id' => $ccode
    );
    $myGrade = $this->getmyGrade($ccode,$emp_c);
    if($myGrade['grade'] != 'F'){
      $this->db->update('lms_ens', $data, $cond);
    }
  }

  public function getTitle($qcode, $lang)
  {
    $this->db->from('lms_qiz');
    $this->db->where('lang', $lang);
    $this->db->where('qcode', $qcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return array('name'=>$result[0]['quiz_name'], 'type'=>$result[0]['quiz_type']);
  }
}
?>
