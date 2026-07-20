<?php
class Question_model extends CI_Model {

  //  public $title;
  //  public $content;
  //  public $date;

  //$data = Array('qst' => (Datas in QST), 'subQst' => (Data in type))

  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  //Main Model Part
  public function loadDB()
  {
    $this->load->database();
  }

  public function closeDB()
  {
    $this->db->close();
  }

  private function create($data, $table)
  {
    $table = strtoupper($table);
    if (strpos($table, 'QST_TC')){
      $this->db->set('time_finish', 'NOW()', FALSE);
    } else if (!strpos($table, 'QST_')){
      $this->db->set('time_create', 'NOW()', FALSE);
      $this->db->set('time_mod', 'NOW()', FALSE);
    }
    $this->db->insert($table, $data);
    $last = $this->db->insert_id();
    return $last;
  }

  private function update($data, $table, $where)
  {
    $table = strtoupper($table);
    if (strpos($table, 'QST_TC')){
      $this->db->set('time_finish', 'NOW()', FALSE);
    } else if (!strpos($table, 'QST_')){
      $this->db->set('time_mod', 'NOW()', FALSE);
    }
    $this->db->update($table, $data, $where);
  }

  private function delete($qstcode, $table)
  {
    $table = strtoupper($table);
    if (strpos($table, '_DD_'))
    $this->db->where('q_d_id', $qstcode);
    else if($table == 'lms_qst')
    $this->db->where('id', $qstcode);
    else
    $this->db->where('questions_id', $qstcode);
    $this->db->delete($table);
  }

  public function createdQuestion($data)
  {
    $data['type']['questions_id'] = $this->create($data['qst'], 'lms_qst');
    //Switch by Question type Part.
    $this->switchType($data, 'create');
    return $data['type']['questions_id'];
  }

  public function updateQuestion($data)
  {
    $this->update($data['qst'], 'lms_qst', 'id='.$data['qst']['id']);
    $data['type']['questions_id'] = $data['qst']['id'];
    //Switch by Question type Part.
    $method = 'edit';
    $qst = $this->getQuestion($data['qst']['id']);
    if ( sizeof($qst['type']) <= 3 ){
      $method = 'create';
    }
    $this->switchType($data, $method);
  }

  public function deleteQuestion($qst)
  {
    //Switch by Question type Part.
    $this->switchType($qst, 'delete');

    $this->delete($qst['qst']['id'], 'lms_qst_tc');
    $this->delete($qst['qst']['id'], 'lms_qst');
  }

  //Switch by Question type Part.
  private function switchType($data, $method)
  {
    $type = $data['qst']['type'];
    switch ($type) {
      case 'match':
      if ($method == 'create') $this->createTypeMat($data['type']);
      if ($method == 'edit') $this->updateTypeMat($data);
      if ($method == 'delete') $this->deleteTypeMat($data);
      break;
      case 'multi':
      if ($method == 'create') $this->createTypeMul($data['type']) ;
      if ($method == 'edit') $this->updateTypeMul($data);
      if ($method == 'delete') $this->deleteTypeMul($data);
      break;
      case 'twoChoice':
      if ($method == 'create') $this->createTypeTwo($data['type']);
      if ($method == 'edit') $this->updateTypeTwo($data);
      if ($method == 'delete') $this->deleteTypeTwo($data);
      break;
      case 'sa':
      if ($method == 'create') $this->createTypeSAns($data['type']);
      if ($method == 'delete') $this->deleteTypeSAns($data);
      break;
      case 'sub':
      if ($method == 'create') $this->createTypeSubAns($data['type']);
      if ($method == 'delete') $this->deleteTypeSubAns($data);
      break;
      default:
      if ($method == 'create') $this->createTypedd($data);
      if ($method == 'edit') $this->updateTypedd($data);
      if ($method == 'delete') $this->deleteTypedd($data);
      break;
    }
  }

  private function createTypeSAns($data)
  {
    $this->create($data, 'lms_qst_sa');
  }

  private function createTypeSubAns($data)
  {
    $this->create($data, 'lms_qst_sub');
  }

  private function createTypedd($data)
  {
    $normal = array(
      'questions_id' => $data['type']['questions_id'],
      'que_info' => $data['type']['que_info'],
      'limit5' => $data['type']['limit5']
    );
    $id = $this->create($normal, 'lms_qst_dd');
    $images = $data['images']['i'];
    foreach ($images as $img) {
      $ddi = array(
        'q_d_id' => $id,
        'image' => $img
      );
      $this->create($ddi, 'lms_qst_dd_i');
    }
    $dda = array(
      'q_d_id' => $id,
      'ans' => $data['images']['a']
    );
    $this->create($dda, 'lms_qst_dd_a');
  }

  private function updateTypeDD($data)
  {
    $this->update($data['type'], 'lms_qst_dd', 'questions_id='.$data['qst']['id']);
    $dd_id = $this->getDDId($data['qst']['id']);
    if (isset($data['images']['i'])){
      $images = $data['images']['i'];
      $old_c = $this->getTypeDDImg($dd_id);
      foreach ($old_c as $old) {
        $this->delete($dd_id, 'lms_qst_dd_i');
      } foreach ($images as $img) {
        $ddi = array(
          'q_d_id' => $dd_id,
          'image' => $img
        );
        $this->create($ddi, 'lms_qst_dd_i');
      }
    }

    if (isset($data['images']['a'])){
      $this->delete($dd_id, 'lms_qst_dd_a');
      $dda = array(
        'q_d_id' => $dd_id,
        'ans' => $data['images']['a']
      );
      $this->create($dda, 'lms_qst_dd_a');
    }
  }

  public function getDDId($qst_id)
  {
    $this->db->select('id');
    $this->db->from('lms_qst_dd');
    $this->db->where('questions_id', $qst_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['id'];
  }

  private function createTypeMat($data)
  {
    $this->create($data, 'lms_qst_mat');
  }

  private function updateTypeMat($data)
  {
    $this->update($data['type'], 'lms_qst_mat', 'questions_id='.$data['qst']['id']);
  }

  private function createTypeMul($data)
  {
    $this->create($data, 'lms_qst_mul');
  }

  private function updateTypeMul($data)
  {
    $this->update($data['type'], 'lms_qst_mul', 'questions_id='.$data['qst']['id']);
  }

  private function createTypeTwo($data)
  {
    $this->create($data, 'lms_qst_tf');
  }

  private function updateTypeTwo($data)
  {
    $this->update($data['type'], 'lms_qst_tf', 'questions_id='.$data['qst']['id']);
  }

  private function deleteTypeSAns($data)
  {
    $this->delete($data['qst']['id'], 'lms_qst_sa');
  }

  private function deleteTypeSubAns($data)
  {
    $this->delete($data['qst']['id'], 'lms_qst_sub');
  }

  private function deleteTypedd($data)
  {
    $dd_id = $this->getDDId($data['qst']['id']);
    $this->delete($dd_id, 'lms_qst_dd_a');
    $this->delete($dd_id, 'lms_qst_dd_i');
    $this->delete($data['qst']['id'], 'lms_qst_dd');
  }

  private function deleteTypeMat($data)
  {
    $this->delete($data['qst']['id'], 'lms_qst_mat');
  }

  private function deleteTypeMul($data)
  {
    $this->delete($data['qst']['id'], 'lms_qst_mul');
  }

  private function deleteTypeTwo($data)
  {
    $this->delete($data['qst']['id'], 'lms_qst_tf');
  }

  private function getField($table)
  {
    $result = $this->db->list_fields($table);
    foreach($result as $field) {
      $data[] = $field;
    }
    return $data;
  }

  public function getAllQstAdmin($qcode)
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $this->db->where('random', '1');
    $query = $this->db->get();
    $num_chk = $query->num_rows();
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $this->db->from('lms_qst');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('lang', $lang);

    $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }
  }
  public function getAllQst($qcode)
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $this->db->where('random', '1');
    $query = $this->db->get();
    $num_chk = $query->num_rows();
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $this->db->from('lms_qst');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('lang', $lang);
    //echo $num_chk;
    if ($num_chk > 0){
      $this->db->order_by('id', 'RANDOM');
    }else{
      $this->db->order_by('id', 'ASC');
    }
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }
  }

  private function getTypeSAns($id)
  {
    $this->db->from('lms_qst_sa');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypeSubAns($id)
  {
    $this->db->from('lms_qst_sub');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypeMat($id)
  {
    $this->db->from('lms_qst_mat');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypeMul($id)
  {
    $this->db->from('lms_qst_mul');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypetwoC($id)
  {
    $this->db->from('lms_qst_tf');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypeDD($id)
  {
    $this->db->from('lms_qst_dd');
    $this->db->where('questions_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  private function getTypeDDImg($id)
  {
    $this->db->from('lms_qst_dd_i');
    $this->db->where('q_d_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      return $query->result_array();
    }
  }

  private function getTypeDDA($id)
  {
    $this->db->from('lms_qst_dd_a');
    $this->db->where('q_d_id', $id);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0]['ans'];
    }
  }

  private function getData($q_id, $lang=NULL)
  {
    if (empty($lang)){
      $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    }
    $this->db->from('lms_qst');
    $this->db->where('id', $q_id);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
    return FALSE;
  }

  private function getTypeData($qst_id, $type)
  {
    if ($type == 'sa'){
      return $this->getTypeSAns($qst_id);
    }
    else if ($type == 'sub'){
      return $this->getTypeSubAns($qst_id);
    }
    else if ($type == 'match'){
      return $this->getTypeMat($qst_id);
    }
    else if ($type == 'multi'){
      return $this->getTypeMul($qst_id);
    }
    else if ($type == 'twoChoice'){
      return $this->getTypetwoC($qst_id);
    }
    else{
      $typeDD = $this->getTypeDD($qst_id);
      $id = $typeDD['id'];
      $typeDD['imgs'] = $this->getTypeDDImg($id);
      $typeDD['ans'] = $this->getTypeDDA($id);
      return $typeDD;
    }
  }

  private function checkTransaction($emp_c, $quest_id)
  {
    $this->db->select('id');
    $this->db->from('lms_qst_tc');
    $this->db->where('emp_c', $emp_c);
    $this->db->where('questions_id', $quest_id);
    $query = $this->db->get();
    return $query->num_rows() > 0 ? TRUE : FALSE;
  }

  private function getAnswer($quest_id, $emp_c)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $this->db->from('lms_qst_tc');
    $this->db->where('emp_c', $emp_c);
    $this->db->where('questions_id', $quest_id);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }
  private function getTransaction($qst_id, $emp_c)
  {
    $this->db->from('lms_qst_tc');
    $this->db->where('questions_id', $qst_id);
    $this->db->where('emp_c', $emp_c);
    $query = $this->db->get();
    $result = $query->result_array();
    if ($query->num_rows() > 0)
    return $result[0];
  }
  //Main Model Part

  //Support Controller Part
  public function getCcode($quizId)
  {
    $this->db->select('courses_id');
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $quizId);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $result = $query->result_array();
      return $result[0];
    }
  }

  public function classifyType($type, $data)
  {
    if ($type == 'match') $fields = $this->getField('lms_qst_mat');
    else if ($type == 'multi') $fields = $this->getField('lms_qst_mul');
    else if ($type == 'twoChoice') $fields = $this->getField('lms_qst_tf');
    else if ($type == 'sa') $fields = $this->getField('lms_qst_sa');
    else if ($type == 'sub') $fields = $this->getField('lms_qst_sub');
    else $fields = $this->getField('lms_qst_dd');
    $newType = array();
    foreach ($fields as $field) {
      if ($field == 'id' || !isset($data[$field])) continue;
      else if ($field == 'questions_id') $newType[$field] = '';
      else $newType[$field] = $data[$field];
    }
    return $newType;
  }

  public function checkCode($code)
  {
    $this->db->select('id');
    $this->db->from('lms_qst');
    $this->db->where('id', $code);
    $query = $this->db->get();
    return $query->num_rows() > 0 ? TRUE: FALSE;
  }

  public function getAll($qcode, $code=NULL)
  {
    if(!empty($qcode)){
      $qst = $this->getAllQst($qcode['qcode']);
    } else {
      $qst = $this->getAllQst($code);
    }
    $all = array();
    if (!empty($qst)){
      foreach ($qst as $each) {
        $type = $this->getTypeData($each['id'], $each['type']);
        array_push($all, array('qst' => $each, 'type' => $type));
      }
      return $all;
    }
    return false;
  }
  public function getAllADmin($qcode, $code=NULL)
  {
    if(!empty($qcode)){
      $qst = $this->getAllQstAdmin($qcode['qcode']);
    } else {
      $qst = $this->getAllQstAdmin($code);
    }
    $all = array();
    if (!empty($qst)){
      foreach ($qst as $each) {
        $type = $this->getTypeData($each['id'], $each['type']);
        array_push($all, array('qst' => $each, 'type' => $type));
      }
      return $all;
    }
    return false;
  }

  public function createTransaction($emp_c, $quest_id, $answers, $flag)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $data = array(
      'emp_c' => $emp_c,
      'questions_id' => $quest_id,
      'flag' => 'true',
      'lang' => $lang
    );
    for ($i = 1; $i <= countArray($answers); $i++){
      $data['ans'.$i] = $answers['ans'.$i];
    }
    if (!$this->checkTransaction($emp_c, $quest_id)){
      $this->create($data, 'lms_qst_tc');
    } else{
      $where = array(
        'questions_id' => $quest_id,
        'emp_c' => $emp_c,
        'lang' => $lang
      );
      $this->update($data, 'lms_qst_tc', $where);
    }
  }

  public function submitQuestion($quiz_id, $emp_c)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
    $questions = $this->getAllQst($quiz_id);
    foreach ($questions as $question) {
      if ($question['type'] == 'sa' || $question['type'] == 'sub')
      continue;
      else{
        $data = array(
          'save' => 'true'
        );
        $where = array(
          'questions_id' => $question['id'],
          'emp_c' => $emp_c,
          'lang' => $lang
        );
        $this->update($data, 'lms_qst_tc', $where);
      }
    }
  }

  public function getAllAnswer($qcode, $emp_c)
  {
    $qst = $this->getAllQst($qcode);
    $answers = array();
    if (!empty($qst)){
      foreach ($qst as $each) {
        $answers[$each['id']] = $this->getAnswer($each['id'], $emp_c);
      }
      return $answers;
    }
  }

  public function checkAnswer($question, $emp_c)
  {
    $data = $this->getTypeData($question['id'], $question['type']);
    $userAnswer = $this->getTransaction($question['id'], $emp_c);
    $len = 5;
    $correct = 0;
    if(in_array($question['type'], array('sa', 'sub'))){
      return 0;
    }
    if (!empty($data['limit5'])){
      $len = $data['limit5'];
    }


    if( isset($data['ans']) ){
      if ( is_numeric($data['ans']) || is_string($data['ans']) ){
        $len = 1;
        $data['ans'] != $userAnswer['ans1'] ? : $correct++;
      }
    }else {
      $q_ans = array($data['ans1'],$data['ans2'], $data['ans3'],$data['ans4'], $data['ans5'] );
      for ($i = 1; $i <= $len; $i++){
        if ($question['type'] == 'match'){
          ($userAnswer['ans'.$i] == $data['ans'.$i]) ? $correct++ : $correct ;
        } else {
          (in_array($userAnswer['ans'.$i], $q_ans) && $userAnswer['ans'.$i] != NULL) ? $correct++ : $correct ;
        }
      }
    }
    $percent = $correct / $len;
    return $question['score'] * $percent;
  }

  public function submitQuiz($quiz_id, $emp_c)
  {
    $questions = $this->getAllQst($quiz_id);
    $sumScore = 0;
    foreach ($questions as $each) {
      $sumScore += $this->checkAnswer($each, $emp_c);
    }
    $data = array(
      'sum_score' => $sumScore,
      'submit' => 'done'
    );
    $id = $this->getLastId($quiz_id);
    $this->db->set('time_finish', 'NOW()', FALSE);
    $this->db->update('lms_qiz_tc', $data,  array('id'=>$id));

  }

  public function getLastId($qcode)
  {
    $user = $this->session->userdata('user');
    $this->db->from('lms_qiz_tc');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('emp_c', $user['emp_c']);
    // $this->db->where('submit', 'noProgress');
    $this->db->order_by("id", "desc");
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['id'];
  }

  public function getQuestion($quest_id, $lang=NULL)
  {
    if (empty($lang)){
      $qst = $this->getData($quest_id);
    }
    else{
      $qst = $this->getData($quest_id, $lang);
    }
    if ($qst){
      $type = $this->getTypeData($quest_id, $qst['type']);
      return array('qst' => $qst, 'type' => $type);
    }
    return FALSE;
  }

  public function getAnswersQ($quest_id)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $qst = $this->getData($quest_id);
    $emp = $this->getEmp();
    if ($qst['type'] == 'sa' || $qst['type'] == 'sub'){
      $this->db->select('emp_c, ans1');
      $this->db->from('lms_qst_tc');
      $this->db->where('questions_id', $quest_id);
      $this->db->where('save', NULL);
      $this->db->where('lang', $lang);
      $query = $this->db->get();
      $result = array();

      if( sizeof( $query->result_array() > 0 ) ){
        foreach ( $query->result_array() as $row ) {
          array_push($result, array('emp_c' => $row['emp_c'], 'emp_name' => $emp[$row['emp_c']][$lang], 'ans' => $row['ans1']));
        }
      }
      return $result;
      //return ($query->num_rows() > 0) ? $result : FALSE;
    }
    return $result;

  }

  private function getEmp()
  {
    $this->db->select('emp_c, prefix, fname, lname, lang');
    $this->db->from('lms_emp');
    $query = $this->db->get();
    foreach ($query->result_array() as $row) {
      $result[$row['emp_c']][$row['lang']] = $row['fname'].' '.$row['lname'];;
    }
    return $result;
  }

  public function getQuizId($quest_id)
  {
    $this->db->select('quiz_id');
    $this->db->from('lms_qst');
    $this->db->where('id', $quest_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['quiz_id'];
  }
  public function getQuizDetail($quiz_code , $lang )
  {
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $quiz_code);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    if( sizeof($result) > 0 ){
      return $result[0];
    }else{
      return array();
    }
  }
  public function getCourseDetail($ccode , $lang )
  {
    $this->db->from('lms_cos');
    $this->db->where('ccode', $ccode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    if( sizeof($result) > 0 ){
      return $result[0];
    }else{
      return array();
    }
  }

  public function saveEach($quest_id, $emp_c)
  {
    $this->update(array('save' => 'true'), 'lms_qst_tc', array('questions_id' => $quest_id,'emp_c' => $emp_c));
  }

  private function getSumScore($quiz_id, $emp_c)
  {
    $this->db->select('sum_score');
    $this->db->from('lms_qiz_tc');
    $this->db->where('quiz_id', $quiz_id);
    $this->db->where('emp_c', $emp_c);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['sum_score'];
  }

  public function updateScore($quiz_id, $emp_c, $score)
  {
    $score += $this->getSumScore($quiz_id, $emp_c);
    $this->db->update('lms_qiz_tc', array('sum_score' => $score),  array('quiz_id'=>$quiz_id, 'emp_c'=>$emp_c));
  }

  public function updateQizScore($qcode, $score)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->db->select('max_score');
    $this->db->from('lms_qiz');
    $this->db->where('qcode', $qcode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    $max_score = $result[0]['max_score'];
    $score = $this->getQizScore($qcode);
    $this->db->update('lms_qiz', array('max_score' => $score ), array('qcode' => $qcode));
  }

  private function getQizScore($qcode)
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->db->select_sum('score');
    $this->db->from('lms_qst');
    $this->db->where('quiz_id', $qcode);
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    $result = $query->result_array();
    $sum = 0;
    foreach ($result as $row) {
      $sum = floatval($row['score']);
    }

    return round($sum);
  }

  public function getActions($qst_id=NULL)
  {
    $langs = $this->getAllLangs();
    $actions = array();
    foreach ($langs as $row) {
      $lang = $row['lang'];
      if (isset($qst_id) && $this->getQuestion($qst_id, $lang)){
        $actions[$lang] = 'quiz_edit';
      } else {
        $actions[$lang] = 'quiz_create';
      }
    }
    return $actions;
  }

  function isComplete($qcode)
  {
    $user = $this->session->userdata("user");
		$emp_c = $user['emp_c'];
    $qst = $this->getAll(array(), $qcode);
    $ans = $this->getAllAnswer($qcode, $emp_c);
    $count_ans = 0;
    foreach ($ans as $row) {
      if ($row['ans1']!='undefined'){
        $count_ans++;
      }
    }
    return (sizeof($qst) == $count_ans);
  }

  //Support Controller Part

  private function getAllLangs()
  {
    $this->db->from('lms_lag');
    $query = $this->db->get();
    return $query->result_array();
  }

}
