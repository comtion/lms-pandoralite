<?php
class Enroll_model extends CI_Model {

  //  public $title;
  //  public $content;
  //  public $date;

  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function loadDB()
  {
    $this->load->database();
  }

  public function closeDB()
  {
    $this->db->close();
  }

  /*public function getEmp($topLevel, $allLevel, $cons)
  {
  $this->db->from('lms_usp');
  $this->db->join('lms_emp', 'lms_emp.emp_c = lms_usp.emp_c');
  $this->db->where('lms_emp.lead', $topLevel['emp_c']);
  $this->db->where('lms_emp.lang', 'thailand');

  $cons['comp'] == '0' || $cons['comp'] == '' ? : $this->db->where('lms_emp.compcode', $cons['comp']);
  $cons['org'] == '0' || $cons['org'] == '' ? : $this->db->where('lms_emp.org_code', $cons['org']);
  $cons['position'] == '0' || $cons['position'] == '' ? : $this->db->where('lms_emp.plcode', $cons['position']);
  $cons['orggroup'] == 'ไม่กำหนด' || $cons['orggroup'] == '' ? : $this->db->where('lms_emp.orggroup', $cons['orggroup']);
  $cons['empgroup'] == '0' || $cons['empgroup'] == '' ? : $this->db->where('lms_emp.empgrpdesc', $cons['empgroup']);
  $query = $this->db->get();
  if($query->num_rows() >= 1)
  {
  $result = $query->result_array();
  foreach ($result as $each) {
  $allLevel = $this->getEmp($each, $allLevel , $cons);
}
}
array_push($allLevel, $topLevel);
return $allLevel;
}

public function getEmpNow($code, $lang)
{
$this->db->from('lms_emp');
$this->db->where('emp_c', $code);
$this->db->where('lang', $lang);
$query = $this->db->get();
if ($query->num_rows() > 0){
$result = $query->result_array();
return $result[0];
}
}*/

public function getDetail($head, $lang) {
  $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
  , lms_emp.lname, lms_emp.org_desc, lms_emp.postname, lms_usp.role, lms_usp.last_act');
  $this->db->from('lms_emp');
  $this->db->join('lms_usp', 'lms_usp.emp_c = lms_emp.emp_c', 'left');
  $this->db->where('lms_emp.emp_c', $head);
  $this->db->where('lang', $lang);
  $query = $this->db->get();
  return $row = $query->row_array();
}

public function getEmps($subordinates, $head, $filter, $lang) {
  $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
  , lms_emp.lname, lms_emp.org_desc, lms_emp.postname, lms_usp.role, lms_usp.last_act');
  $this->db->from('lms_emp');
  $this->db->join('lms_usp', 'lms_usp.emp_c = lms_emp.emp_c', 'left');

  empty($filter['comp']) ?: $this->db->where('compcode', $filter['comp']);
  empty($filter['org']) ?: $this->db->where('org_code', $filter['org']);
  empty($filter['pl']) ?: $this->db->where('plcode', $filter['pl']);
  empty($filter['orgGroup']) ?: $this->db->where('orggroup', $filter['orgGroup']);
  empty($filter['empGrpDesc']) ?: $this->db->where('empgrpdesc', $filter['empGrpDesc']);

  $this->db->where('lead', $head);
  $this->db->where('depart_date', '');
  $this->db->where('lang', $lang);
  $query = $this->db->get();
  $rows = $query->result_array();

  if(empty($rows)) {
    return $subordinates;
  }

  foreach($rows as $row) {
    $subordinates[] = $row;
    $subordinates = $this->getEmps($subordinates, $row['emp_c'], $filter, $lang);
  }
  return $subordinates;
}

public function getAllEmps($filter, $lang) {
  //$this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
  //, lms_emp.lname, lms_emp.org_desc, lms_emp.postname, lms_usp.role, lms_usp.last_act');
  $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
  , lms_emp.lname , lms_emp.org1 , lms_emp.main_pos , lms_usp.role, lms_usp.last_act');
  $this->db->from('lms_emp');
  $this->db->join('lms_usp', 'lms_usp.emp_c = lms_emp.emp_c', 'left');
  //$this->db->join('lms_org1', 'lms_org1.code = lms_emp.org1', 'left');
  //$this->db->join('lms_pos', 'lms_pos.pos_code = lms_emp.main_pos', 'left');
  //$this->db->join('lms_usp', 'lms_usp.emp_c = lms_emp.emp_c', 'left');

  empty($filter['comp']) ?: $this->db->where('org1', $filter['comp']);
  //empty($filter['org']) ?: $this->db->where('org_code', $filter['org']);
  empty($filter['pl']) ?: $this->db->where('main_pos', $filter['pl']);
  //empty($filter['orgGroup']) ?: $this->db->where('orggroup', $filter['orgGroup']);
  //empty($filter['empGrpDesc']) ?: $this->db->where('empgrpdesc', $filter['empGrpDesc']);

  //$this->db->where('depart_date', '');
  $this->db->where('lms_emp.lang', $lang);
  //$this->db->limit(5);
  $query = $this->db->get();
  return $query->result_array();
}


public function isEnrolled($emp_c, $course_id) {
  $this->db->select('emp_c');
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $course_id);
  $query = $this->db->get();
  $row = $query->row_array();

  return ($query->num_rows() > 0);
}

public function isCanceled($emp_c, $course)
{
  $ccode = (is_array($course))?$course['ccode']:$course;
  $this->db->select('emp_c');
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $ccode);
  $this->db->where('ens_status', 0);
  $query = $this->db->get();
  $row = $query->row_array();
  echo ($query->num_rows() > 0);
  return ($query->num_rows() > 0);
}

public function isApproved($emp_c, $course) {
  $code = (is_array($course))?$course['ccode']:$course;
  $this->db->select('approve_pp');
  $this->db->from('lms_cos');
  $this->db->where('ccode', $code);
  $query = $this->db->get();
  $row = $query->row_array();
  $approved = $row['approve_pp'];
  if($approved == 0) return TRUE;

  //$level = $this->getLevel($emp_c);
  $this->db->select('emp_c');
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $code);
  $this->db->where('ens_status', 1);
  $this->db->where('enroll_status1', 'yes');
  /*if($level == '2') {
    $this->db->where('enroll_status2', 'yes');
  }*/
  $query = $this->db->get();
  $row = $query->row_array();

  /*if(empty($row)) {
  return FALSE;
}
return TRUE;*/
// if($approved == 0 && $query->num_rows() > 0) return TRUE;
return ($query->num_rows() > 0);
}

public function getComp($lang)
{
  $this->db->select('compcode, company');
  $this->db->distinct();
  $this->db->from('lms_emp');
  $this->db->where('lang', $lang);
  $query = $this->db->get();
  return $query->result_array();
}

public function getOrg($lang)
{
  $this->db->select('org_code, org_desc, org_abbr_code');
  $this->db->distinct();
  $this->db->from('lms_emp');
  $this->db->where('lang', $lang);
  $this->db->where('org_code !=', 9999);
  $this->db->where('workstatus', 'active');
  $query = $this->db->get();
  return $query->result_array();
}

public function getOrgGroup($lang)
{
  $this->db->select('orggroup');
  $this->db->distinct();
  $this->db->from('lms_emp');
  $this->db->where('lang', $lang);
  $this->db->where('workstatus', 'active');
  $this->db->where('org_code !=', 9999);
  $this->db->where('orggroup !=', '');
  $query = $this->db->get();
  return $query->result_array();
}

public function getPl($lang)
{
  $this->db->select('plcode, pldesc');
  $this->db->distinct();
  $this->db->from('lms_emp');
  $this->db->where('lang', $lang);
  $this->db->where('workstatus', 'active');
  $this->db->where('org_code !=', 9999);
  $query = $this->db->get();
  return $query->result_array();
}

public function getEmpGrpDesc($lang)
{
  $this->db->select('empgrpdesc');
  $this->db->distinct();
  $this->db->from('lms_emp');
  $this->db->where('lang', $lang);
  $this->db->where('workstatus', 'active');
  $this->db->where('org_code !=', 9999);
  $this->db->where('empgrpdesc !=', '');
  $query = $this->db->get();
  return $query->result_array();
}

public function getCourse($ccode)
{
  $this->db->select('ccode, approve_pp');
  $this->db->distinct();
  $this->db->from('lms_cos');
  $this->db->where('ccode', $ccode);
  $this->db->where('status', 1);
  $query = $this->db->get();
  return $query->row_array();
}

public function enrollUser($course, $user, $head, $role, $status)
{
  date_default_timezone_set("Asia/Bangkok");
  $time = date('Y-m-d H:i');
  //$level = $this->getLevel($user);

  $this->db->set('emp_c', $user);
  $this->db->set('course_id', $course);
  //$this->db->set('approver_id1', $head);
  $this->db->set('enroll_status1', 'yes');
  $this->db->set('ens_status', $status);
  /*if($level == '2') {
    if(in_array($role, array("superadmin", "admin")) || $level == '') $this->db->set('enroll_status2', 'yes');
    else $this->db->set('enroll_status2', 'no');
  }*/
  $this->db->set('time_request', $time);
  $this->db->insert('lms_ens');
}

public function rejectUser($ccode, $user, $data)
{
  $this->db->where('course_id', $ccode);
  $this->db->where('emp_c', $user);
  $this->db->delete('lms_ens');

  $this->db->select('id');
  $this->db->from('lms_bad');
  $this->db->where('courses_id', $ccode);
  $query = $this->db->get();
  $badges_id = $query->result_array();

  foreach($badges_id as $id) {
    $this->db->where('badges_id', $id['id']);
    $this->db->where('emp_c', $user);
    $this->db->delete('lms_uhb_tc');
  }
}

public function enrollSelf($course, $user) {
  $status = 1;
  if (!$this->checkSelfEnroll($user, $course['ccode'])){
    date_default_timezone_set("Asia/Bangkok");
    $time = date('Y-m-d H:i');
    //$level = $this->getLevel($user);
    $this->db->set('emp_c', $user);
    $this->db->set('course_id', $course['ccode']);
    $status = $this->isSubtitute($course['ccode']);
    $this->db->set('ens_status', $status);

    if($course['approve_format'] == 0) {
      $this->db->set('enroll_status1', 'yes');
    } elseif($this->getLevel($user) == '') {
      $this->db->set('enroll_status1', 'yes');
      $this->db->set('enroll_status2', 'yes');
    } elseif($level == '1') {
      $this->db->set('enroll_status1', 'no');
    } elseif($level == '2') {
      $this->db->set('enroll_status1', 'no');
      $this->db->set('enroll_status2', 'no');
    }
    $this->db->set('time_request', $time);
    $this->db->insert('lms_ens');
  }
  return $status;
}

public function isSubtitute($ccode)
{
  $c_seats = $this->getSeats($ccode, 'lms_cos');
  $seats = $this->getSeats($ccode, 'lms_ens');
  if ($c_seats > 0 && ($seats >= $c_seats || $this->hasSubtitute($ccode))) {
    return 2;
  } else {
    return 1;
  }
}

public function hasSubtitute($ccode)
{
  $this->db->from('lms_ens');
  $this->db->where('course_id', $ccode);
  $this->db->where('ens_status', 2);
  $query = $this->db->get();
  return ($query->num_rows() > 0);
}

public function getSeats($ccode, $table)
{
  $this->db->from($table);
  if ($table == 'lms_ens'){
    $this->db->where('ens_status', 1);
    $this->db->where('course_id', $ccode);
    $query = $this->db->get();
    return $query->num_rows();
  } else {
    $this->db->where('ccode', $ccode);
    $query = $this->db->get();
    $result = $query->result_array();
    return empty($result[0]['seat'])?0:intval($result[0]['seat']);
  }
}

public function checkSelfEnroll($emp_c, $ccode)
{
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $ccode);

  $query = $this->db->get();
  return ($query->num_rows() > 0);
}

public function getHead($emp_c, $lang) {
  $this->db->select('lead');
  $this->db->from('lms_emp');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('lang', $lang);
  $query = $this->db->get();
  $row = $query->row_array();
  return $row['lead'];
}

public function getHeads($emp_c, $heads, $level, $lang) {
  $this->db->select('emp_c, prefix, fname, lname, email, lead');
  $this->db->from('lms_emp');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('lang', $lang);
  $query = $this->db->get();
  $row = $query->row_array();

  $heads[] = $row;
  if($level == '2'){
    $heads = $this->getHeads($row['lead'], $heads, '1', $lang);
  }

  return $heads;
}

public function getLevel($emp_c) {
  $this->db->select('level');
  $this->db->where('emp_c', $emp_c);
  $query = $this->db->get('lms_emp');
  $row = $query->row_array();
  return $row['level'];
}

public function getStudents($type, $ccode)
{
  $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
  $this->db->from('lms_ens');
  $this->db->join('lms_emp', 'lms_ens.emp_c = lms_emp.emp_c');
  $this->db->where('lms_ens.course_id', $ccode);
  $this->db->where('lms_ens.ens_status', $type);
  $this->db->where('lms_emp.lang', $lang);
  $query = $this->db->get();
  return $query->result_array();
}

private function getmyGrade($ccode,$emp_c)
{
  $this->db->from('lms_gbk');
  $this->db->where('ccode', $ccode);
  $this->db->where('emp_c', $emp_c);
  $query = $this->db->get();
  $myGrade = $query->result_array();
  return $myGrade[0];
}

public function finishCos($ccode, $emp_c=NULL)
{
  date_default_timezone_set("Asia/Bangkok");
  if (empty($emp_c)){
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
  }
  $data = array(
    'finish_time'=> date('Y-m-d H:i'),
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

public function openFirst($ccode)
{
  date_default_timezone_set("Asia/Bangkok");
  $sess = $this->session->userdata("user");
  $emp_c = $sess['emp_c'];
  if ($this->isExits($ccode, $emp_c) && $this->checkFirst($ccode, $emp_c)){
    $data['first_time'] = date('Y-m-d H:i');
    $this->db->update('lms_ens', $data ,array('emp_c' => $emp_c, 'course_id'=>$ccode));
  }
}

public function checkFirst($ccode, $emp_c)
{
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $ccode);
  $this->db->where('first_time IS NULL');
  $query = $this->db->get();
  return ($query->num_rows() > 0);
}

public function isExits($ccode, $emp_c)
{
  $this->db->from('lms_ens');
  $this->db->where('emp_c', $emp_c);
  $this->db->where('course_id', $ccode);
  $query = $this->db->get();
  return ($query->num_rows() > 0);
}

public function isTis($emp_c)
{
  $this->db->from('lms_emp');
  $this->db->join('lms_pos', 'lms_emp.main_pos = lms_pos.pos_code');
  $this->db->where('lms_emp.emp_c', $emp_c);
  $this->db->where('lms_pos.pos_for', 'tis');
  $query = $this->db->get();
  return $query->num_rows();
}
}
