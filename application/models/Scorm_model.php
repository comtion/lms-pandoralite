<?php
class Scorm_model extends CI_Model {

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

  public function getCode()
  {
    $this->db->select_max('id');
    $this->db->from('lms_scm');
    $query = $this->db->get();
    $row = $query->row();

    return ($row->scmcode) == '' ? 1 : ($row->scmcode) + 1;
  }

  private function create($table, $data)
  {
    $this->db->insert($table, $data);
  }

  private function update($table, $data, $where)
  {
    $this->db->update($table, $data, $where);
  }

  private function delete($keys, $table)
  {
    $this->db->where($keys[0], $keys[1]);
    $this->db->delete($table);
  }

  public function deleteScorm($lcode)
  {
    $sco = $this->get($lcode);
    $this->delete(array('scm_id', $sco['id']), 'lms_scm_val');

    $this->delete(array('lessons_id', $lcode), 'lms_scm');
  }

  public function createScorm($lcode, $scmcode)
  {
    $data = array(
      'lcode' => $lcode,
      'scmcode' => $scmcode,
      'path' => "uploads/scorm/scorm_".$lcode."_".$scmcode
    );
    $this->create('lms_scm', $data);
  }

  public function get($lcode)
  {
    $this->db->from('lms_scm');
    $this->db->where('lessons_id', $lcode);
    $query = $this->db->get();
    $result = $query->result_array();
    return ($query->num_rows() > 0)? $result[0]: FALSE;
  }

  public function getScmCode($lcode)
  {
    $scm = $this->get($lcode);
    return $scm['id'];
  }

  public function load($path)
  {
    $root_path = ROOT_DIR.$path;
    $path = REAL_PATH."/".$path;
    /*if (file_exists($root_path."/story.html")) {
      $path = $path."/story.html";
    } else */ if (file_exists($root_path."/index_lms.html")){
      $path = $path."/index_lms.html";
    } else if (file_exists($root_path."/index_scorm.html")){
      $path = $path."/index_scorm.html";
    }
    return $path;
  }

  public function getScorm($scoCode)
  {
    $this->db->select('path');
    $this->db->from('lms_scm');
    $this->db->where('id', $scoCode);
    $query = $this->db->get();
    $result = $query->result_array();
    return $this->load('uploads/scorm/'.$result[0]['path']);
  }

  public function getlCode($scmCode)
  {
    $this->db->from('lms_scm');
    $this->db->where('id', $scmCode);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['lessons_id'];
  }

  public function createTC($code)
  {
    $user = $this->session->userdata("user");
    $lcode = $this->getlCode($code);
    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;

    $this->db->from('lms_les');
    $this->db->where('les_id',$lcode);
    $query_les = $this->db->get();
    $fetch_les =$query_les->row_array();
    
    $this->db->select('lms_cos_enroll.cosen_id');
    $this->db->from('lms_cos_enroll');
    $this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
    $this->db->where('lms_cos_enroll.cos_id', $fetch_les['cos_id']);
    $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
    $query_chk = $this->db->get();
    $fetch_chk = $query_chk->row_array();

    $this->db->select('lms_log_enroll.id_log');
    $this->db->from('lms_log_enroll');
    $this->db->where('cosen_id', $fetch_chk['cosen_id']);
    $queryCheckLogEnroll = $this->db->get();
    $fetchCheckLogEnroll = $queryCheckLogEnroll->row_array();
    if (!isset($fetchCheckLogEnroll)) {
      $fetch_chk['id_log'] = $this->db->insert(
                                  'lms_log_enroll', 
                                  array('cosen_id' => $fetch_chk['cosen_id']));
    } else {
      $fetch_chk['id_log'] = $fetchCheckLogEnroll['id_log'];
    }

    $data = array(
			'emp_id' => $user['emp_id'] ,
			'les_id' => $lcode,
			'learn_status' => '1',
      'cosen_id' => $fetch_chk['cosen_id']
		);
    $this->createNewTC($data);
  }

  public function updateTC($code, $status='')
  {
    $user = $this->session->userdata("user");
    $lcode = $this->getlCode($code);
    if($lcode==""){
      $lcode = $code;
    }
    $this->db->from('lms_les');
    $this->db->where('les_id',$lcode);
    $query_les = $this->db->get();
    $fetch_les =$query_les->row_array();
    
    $this->db->select('lms_cos_enroll.cosen_id');
    $this->db->from('lms_cos_enroll');
    $this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
    $this->db->where('lms_cos_enroll.cos_id', $fetch_les['cos_id']);
    $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
    $query_chk = $this->db->get();
    $fetch_chk = $query_chk->row_array();

    $this->db->select('lms_log_enroll.id_log');
    $this->db->from('lms_log_enroll');
    $this->db->where('cosen_id', $fetch_chk['cosen_id']);
    $queryCheckLogEnroll = $this->db->get();
    $fetchCheckLogEnroll = $queryCheckLogEnroll->row_array();
    if (!isset($fetchCheckLogEnroll)) {
      $fetch_chk['id_log'] = $this->db->insert(
                                  'lms_log_enroll', 
                                  array('cosen_id' => $fetch_chk['cosen_id']));
    } else {
      $fetch_chk['id_log'] = $fetchCheckLogEnroll['id_log'];
    }

    $this->db->from('lms_les_tc');
    $this->db->where('les_id', $lcode);
    $this->db->where('emp_id', $user['emp_id']);
    $this->db->where('cosen_id', $fetch_chk['cosen_id']);
    $query = $this->db->get();
    // $result = $query->result_array();
    if($status=="completed"||$status=="passed"){
      $status='2';
    }else if($status=="failed"){
      $status='3';
    }else{
      $status='1';
    }
    if ($query->num_rows() <= 0){

      $this->db->from('lms_les');
      $this->db->where('les_id',$lcode);
      $query_les = $this->db->get();
      $fetch_les =$query_les->row_array();
      $this->db->select('lms_cos_enroll.cosen_id');
      $this->db->from('lms_cos_enroll');
      $this->db->where('emp_id',$user['emp_id']);
      $this->db->where('cos_id',$fetch_les['cos_id']);
      $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
      $query_chk = $this->db->get();
      $fetch_chk = $query_chk->row_array();
      $data = array(
        'emp_id' => $user['emp_id'] ,
        'les_id' => $lcode,
        'learn_status' => $status
      );
      $data['cosen_id'] = $fetch_chk['cosen_id'];
      $this->db->insert('lms_les_tc', $data);
    }else{
      $fetch_chk = $query->row_array();
      if ($fetch_chk['learn_status'] != "2") {
        $data = array('learn_status' => $status );
        $this->update('lms_les_tc', $data, array('emp_id'=>$user['emp_id'], 'les_id'=>$lcode, 'cosen_id'=>$fetch_chk['cosen_id']));
      }
    }
  }
  public function updateTC_cos($code, $status='')
  {
    $user = $this->session->userdata("user");
    $this->db->from('lms_les_tc');
    $this->db->where('les_id', $code);
    $this->db->where('emp_id', $user['emp_id']);
    $query = $this->db->get();
    $result = $query->result_array();
      if($status=="completed"||$status=="passed"){
        $status='2';
      }else if($status=="failed"){
        $status='3';
      }else{
        $status='1';
      }
    if ($query->num_rows() <= 0){
      $this->db->from('lms_les');
      $this->db->where('les_id',$code);
      $query_les = $this->db->get();
      $fetch_les =$query_les->row_array();
      $this->db->select('lms_cos_enroll.cosen_id');
      $this->db->from('lms_cos_enroll');
      $this->db->where('emp_id',$user['emp_id']);
      $this->db->where('cos_id',$fetch_les['cos_id']);
      $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
      $query_chk = $this->db->get();
      $fetch_chk = $query_chk->row_array();

      $data = array(
        'emp_id' => $user['emp_id'] ,
        'les_id' => $code,
        'learn_status' => $status
      );
      $data['cosen_id'] = $fetch_chk['cosen_id'];
      $this->db->insert('lms_les_tc', $data);
    }else{
      $data = array('learn_status' => $status );
      $this->update('lms_les_tc', $data, array('emp_id'=>$user['emp_id'], 'les_id'=>$code));
    }
  }

  public function createNewTC($data)
  {
    $this->db->from('lms_les_tc');
    $this->db->where('les_id', $data['les_id']);
    $this->db->where('emp_id', $data['emp_id']);
    $this->db->where('cosen_id', $data['cosen_id']);
    $query = $this->db->get();
    // $result = $query->result_array();
    if ($query->num_rows() <= 0){


          // $this->db->from('lms_les');
          // $this->db->where('les_id',$data['les_id']);
          // $query_les = $this->db->get();
          // $fetch_les =$query_les->row_array();
          // $this->db->select('lms_cos_enroll.cosen_id');
          // $this->db->from('lms_cos_enroll');
          // $this->db->where('emp_id',$data['emp_id']);
          // $this->db->where('cos_id',$fetch_les['cos_id']);
          // $query_chk = $this->db->get();
          // $fetch_chk = $query_chk->row_array();
        // $data['cosen_id'] = $data['cosen_id'];
      $this->db->insert('lms_les_tc', $data);
    }
    return $query->num_rows() <= 0;
  }

  public function readElement($name, $scm_id)
  {
    $user = $this->session->userdata("user");
    $this->db->select('var_value');
    $this->db->from('lms_scm_val');
    $this->db->join('lms_log_enroll','lms_scm_val.id_log=lms_log_enroll.id_log');
    $this->db->join('lms_cos_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
    $this->db->where('lms_scm_val.scm_id', $scm_id);
    $this->db->where('lms_scm_val.emp_id', $user['emp_id']);
    $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
    $this->db->where('lms_scm_val.var_name', $name);
    $query = $this->db->get();
    return ($query->num_rows() > 0)? $query->result_array(): FALSE;
  }

  private function setElement($name, $scm_id)
  {
    $user = $this->session->userdata("user");
    if (isset($user['emp_id'])) {
      $this->db->from('lms_les');
      $this->db->join('lms_scm','lms_les.les_id=lms_scm.lessons_id');
      $this->db->where('lms_scm.id',$scm_id);
      $query_les = $this->db->get();
      $fetch_les =$query_les->row_array();
      
      $this->db->select('lms_cos_enroll.cosen_id');
      $this->db->from('lms_cos_enroll');
      $this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
      $this->db->where('lms_cos_enroll.cos_id', $fetch_les['cos_id']);
      $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
      $query_chk = $this->db->get();
      $fetch_chk = $query_chk->row_array();
  
      if (isset($fetch_chk['cosen_id'])) {
        $this->db->select('lms_log_enroll.id_log');
        $this->db->from('lms_log_enroll');
        $this->db->where('cosen_id', $fetch_chk['cosen_id']);
        $queryCheckLogEnroll = $this->db->get();
        $fetchCheckLogEnroll = $queryCheckLogEnroll->row_array();
        if (!isset($fetchCheckLogEnroll)) {
          $fetch_chk['id_log'] = $this->db->insert(
                                      'lms_log_enroll', 
                                      array('cosen_id' => $fetch_chk['cosen_id']));
        } else {
          $fetch_chk['id_log'] = $fetchCheckLogEnroll['id_log'];
        }
      }
    }
    
    $data = isset($user['emp_id']) ? array(
      'scm_id' => $scm_id,
      'emp_id' => $user['emp_id'],
      'id_log' => isset($fetch_chk['id_log']) ? $fetch_chk['id_log'] : "",
      "var_name" => $name,
    ) : array();
    return $data;
  }

  public function writeElement($name, $scm_id, $val=NULL)
  {
    $cond = $this->setElement($name, $scm_id);
    $data = $cond;
    $data['var_value'] = $val;
    $this->update('lms_scm_val', $data, $cond);
  }

  public function initializeElement($name, $scm_id, $val=NULL)
  {
    $user = $this->session->userdata('user');
    $viewcourse = $this->session->userdata("viewcourse");
    if($viewcourse!="demo" && isset($user['emp_id'])){
        $cond = $this->setElement($name, $scm_id);
        $data = $cond;
        $data['var_value'] = $val;
        if ($name == 'cmi_core_lesson_status' && $val == 'passed'){
          $this->createTC($scm_id);
        }
        $row_bef = $this->readElement($name, $scm_id);
        if ($row_bef){
          $lcode = $this->getlCode($scm_id);
          $this->db->from('lms_les_tc');
          $this->db->join('lms_cos_enroll','lms_cos_enroll.cosen_id = lms_les_tc.cosen_id');
          $this->db->where('lms_les_tc.les_id', $lcode);
          $this->db->where('lms_les_tc.emp_id', $data['emp_id']);
          $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
          $query = $this->db->get();
          $isOnGoing = 1;
          if ($query->num_rows() > 0){
              $fetchLesTc =$query->row_array();
              if ($fetchLesTc['learn_status'] == "2") {
                $isOnGoing = 0;
              }
          }
          
          if ($isOnGoing == 1) {
              $where_au = "(var_value = 'completed' OR var_value = 'passed')";
              $this->db->select('var_value');
              $this->db->from('lms_scm_val');
              $this->db->join('lms_log_enroll','lms_scm_val.id_log=lms_log_enroll.id_log');
              $this->db->join('lms_cos_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
              $this->db->where('lms_scm_val.scm_id', $scm_id);
              $this->db->where('lms_scm_val.emp_id', $user['emp_id']);
              $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
              $this->db->where('lms_scm_val.var_name', 'cmi_core_lesson_status');
              $this->db->where($where_au);
              $query = $this->db->get();
              $fetch = $query->result_array();
              if(countArray($fetch)==0){
                if($name == 'cmi_core_lesson_status' && $val == 'failed'){
                  $this->updateTC($scm_id, $val);
                }else{
                  if($name == 'cmi_core_lesson_status' && $val != 'incomplete'){
                    $this->updateTC($scm_id, $val);
                    $this->update('lms_scm_val', $data, $cond);
                  }else{
                    $this->updateTC($scm_id, $val);
                  }
                }
              }
              if($name == 'cmi_suspend_data' ){
                $this->update('lms_scm_val', $data, $cond);
              }
          }
          //
        } else {
          $this->db->from('lms_les');
          $this->db->join('lms_scm','lms_les.les_id=lms_scm.lessons_id');
          $this->db->where('lms_scm.id',$scm_id);
          $query_les = $this->db->get();
          $fetch_les =$query_les->row_array();
    
          $this->db->select('lms_cos_enroll.cosen_id');
          $this->db->from('lms_cos_enroll');
          $this->db->where('lms_cos_enroll.emp_id', $user['emp_id']);
          $this->db->where('lms_cos_enroll.cos_id', $fetch_les['cos_id']);
          $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
          $query_chk = $this->db->get();
          $fetch_chk = $query_chk->row_array();

          if (isset($fetch_chk['cosen_id'])) {
            $this->db->select('lms_log_enroll.id_log');
            $this->db->from('lms_log_enroll');
            $this->db->where('cosen_id', $fetch_chk['cosen_id']);
            $queryCheckLogEnroll = $this->db->get();
            $fetchCheckLogEnroll = $queryCheckLogEnroll->row_array();
            if (!isset($fetchCheckLogEnroll)) {
              $fetch_chk['id_log'] = $this->db->insert(
                                          'lms_log_enroll', 
                                          array('cosen_id' => $fetch_chk['cosen_id']));
            } else {
              $fetch_chk['id_log'] = $fetchCheckLogEnroll['id_log'];
            }
  
            $data['id_log'] = $fetch_chk['id_log'];
            $this->create('lms_scm_val', $data);
          }
        }
    }
  }

  public function lms_scm_val($dataSet)
  {
    $scmCode = $dataSet['scoid'];
    foreach ($dataSet as $key => $value) {
      $name = $key;
      if (strpos($key, '_cmi')){
        $keyName = explode('_cmi', $key);
        $name = 'cmi'.$keyName[1];
      }
      $this->initializeElement($name, $scmCode, $value);
    } return TRUE;
  }

  public function createTransaction($dataSet)
  {
    $scmCode = $dataSet['scoid'];
    foreach ($dataSet as $key => $value) {
      $name = $key;
      if (strpos($key, '_cmi')){
        $keyName = explode('_cmi', $key);
        $name = 'cmi'.$keyName[1];
      }
      $this->initializeElement($name, $scmCode, $value);
    } return TRUE;
  }

  public function initializeSCO($scm_id)
  {
    $user = $this->session->userdata("user");
    $this->db->from('lms_scm_val');
    $this->db->join('lms_log_enroll','lms_scm_val.id_log=lms_log_enroll.id_log');
    $this->db->join('lms_cos_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
    $this->db->where('lms_scm_val.scm_id', $scm_id);
    $this->db->where('lms_scm_val.emp_id', $user['emp_id']);
    $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
    $count = $this->db->count_all_results();

    if ($count == 0){
      // elements that tell the SCO which other elements are supported by this API
  		$this->initializeElement('cmi_core__children', $scm_id, ' student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,exit,session_time');
  		$this->initializeElement('cmi_core_score._children', $scm_id, 'raw');

  		// student information
  		$this->initializeElement('cmi_core_student_name', $scm_id, $this->getFromLMS($scm_id, 'cmi_core_student_name'));
  		$this->initializeElement('cmi_core_student_id', $scm_id, $this->getFromLMS($scm_id, 'cmi_core_student_id'));

  		// test score
  		$this->initializeElement('cmi_core_score.raw', $scm_id, '');
  		$this->initializeElement('adlcp:masteryscore', $scm_id, $this->getFromLMS($scm_id, 'adlcp:masteryscore'));

  		// SCO launch and suspend data
  		$this->initializeElement('cmi_launch_data', $scm_id, $this->getFromLMS($scm_id, 'cmi_launch_data'));
  		$this->initializeElement('cmi_suspend_data', $scm_id, '');

  		// progress and completion tracking
  		$this->initializeElement('cmi_core_lesson_location', $scm_id, '');
  		$this->initializeElement('cmi_core_credit', $scm_id, 'credit');
  		$this->initializeElement('cmi_core_lesson_status', $scm_id, 'not attempted');
  		$this->initializeElement('cmi_core_entry', $scm_id, 'ab-initio');
  		$this->initializeElement('cmi_core_exit', $scm_id, '');

  		// seat time
  		$this->initializeElement('cmi_core_total_time', $scm_id, '0000:00:00');
    }
    $this->initializeElement('cmi_core_session_time', $scm_id, '');
    // create the javascript code that will be used to set up the javascript cache,
  	$initializeCache = "var cache = new Object();\n";
    $sco_datas = $this->getScmVars($scm_id);
    foreach ($sco_datas as $row) {
      $varname = $row['var_name'];
      $varvalue = $row['var_value'];
      // make the value safe by escaping quotes and special characters
  		$jvarvalue = addslashes($varvalue);

  		// javascript to set the initial cache value
  		$initializeCache .= "cache['$varname'] = '$jvarvalue';\n";
    }
    // return javascript for cache initialization to the calling program
  	return $initializeCache;
  }

  public function getScmVars($scm_id)
  {
    $user = $this->session->userdata("user");
    $viewcourse = $this->session->userdata("viewcourse");
    $this->db->from('lms_scm_val');
    $this->db->join('lms_log_enroll','lms_scm_val.id_log=lms_log_enroll.id_log');
    $this->db->join('lms_cos_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
    $this->db->where('lms_scm_val.scm_id', $scm_id);
    $this->db->where('lms_scm_val.emp_id', $user['emp_id']);
    $this->db->where('lms_cos_enroll.cosen_isDelete', '0');
    $query = $this->db->get();
    if($viewcourse=="demo"){
      return FALSE;
    }else{
      return ($query->num_rows() > 0) ? $query->result_array(): FALSE;
    }
  }

  public function getFromLMS($scm_id, $varname) {

  	switch ($varname) {

  		case 'cmi_core_student_name':
        $user = $this->session->userdata("name");
        $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
        $fullname = $user['fullname_th'];
        if($lang!="thai"){
          $fullname = $user['fullname_en'];
        }
  			$varvalue = $fullname;
  			break;

  		case 'cmi_core_student_id':
        $user = $this->session->userdata("user");
  			$varvalue = $user['emp_id'];
  			break;

  		case 'adlcp:masteryscore':
  			$varvalue = $this->getMastScore($scm_id);
  			break;

  		case 'cmi_launch_data':
  			$varvalue = "";
  			break;

  		default:
  			$varvalue = '';

  	}

  	return $varvalue;

  }

  private function getMastScore($scm_id)
  {
    $dir = $this->getDir($scm_id);
    $path = ROOT_DIR.$dir.'/adlcp_rootv1p2.xsd';
    return isset($mastScore)? $mastScore : 0;
  }

  private function getDir($scm_id)
  {
    $this->db->from('lms_scm');
    $this->db->where('id', $scm_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['path'];
  }

  public function getManifest($path)
  {
    $manifest = ROOT_DIR.$path.'/imsmanifest.xml';
    return $this->getXmlData($manifest, array('schema', 'schemaversion', 'title'));
  }

  public function getAdlcp($path)
  {
    $adlcp = ROOT_DIR.$path.'/adlcp_rootv1p2.xsd';
    return $this->getXmlData($adlcp, NULL);
  }

  private function getXmlData($file, $objective)
  {
    $xml=simplexml_load_file($file) or die("Error: Cannot create object");
    $scormData = array();
    $metaData = $xml->metadata;
    if (!empty($objective)){
      foreach ($objective as $row) {
        switch ($row) {
          case 'title':
            $scormData[$row] = $metaData->lom->general->title->langstring;
            break;

          default:
            $scormData[$row] = $metaData->$row;
            break;
        }
      }
    } else {
      //Xsd get parameter
    }
    return $scormData;
  }

  public function reconstitute_array_element($userdata, $elementname, $children) {
      // Reconstitute comments_from_learner and comments_from_lms.
      $current = '';
      $currentsubelement = '';
      $currentsub = '';
      $count = 0;
      $countsub = 0;
      $scormseperator = '_';
      $return = '';
      // Filter out the ones we want.
      $elementlist = array();
      foreach ($userdata as $element => $value) {
          if (substr($element, 0, strlen($elementname)) == $elementname) {
              $elementlist[$element] = $value;
          }
      }

      // Sort elements in .n array order.
      uksort($elementlist, "scorm_element_cmp");

      // Generate JavaScript.
      foreach ($elementlist as $element => $value) {
              $element = preg_replace('/\.(\d+)\./', "_\$1.", $element);
              preg_match('/\_(\d+)\./', $element, $matches);
          if (countArray($matches) > 0 && $current != $matches[1]) {
              if ($countsub > 0) {
                  $return .= '    '.$elementname.$scormseperator.$current.'.'.$currentsubelement.'._count = '.$countsub.";\n";
              }
              $current = $matches[1];
              $count++;
              $currentsubelement = '';
              $currentsub = '';
              $countsub = 0;
              $end = strpos($element, $matches[1]) + strlen($matches[1]);
              $subelement = substr($element, 0, $end);
              $return .= '    '.$subelement." = new Object();\n";
              // Now add the children.
              foreach ($children as $child) {
                  $return .= '    '.$subelement.".".$child." = new Object();\n";
                  $return .= '    '.$subelement.".".$child."._children = ".$child."_children;\n";
              }
          }

          // Now - flesh out the second level elements if there are any.
              $element = preg_replace('/(.*?\_\d+\..*?)\.(\d+)\./', "\$1_\$2.", $element);
              preg_match('/.*?\_\d+\.(.*?)\_(\d+)\./', $element, $matches);

          // Check the sub element type.
          if (countArray($matches) > 0 && $currentsubelement != $matches[1]) {
              if ($countsub > 0) {
                  $return .= '    '.$elementname.$scormseperator.$current.'.'.$currentsubelement.'._count = '.$countsub.";\n";
              }
              $currentsubelement = $matches[1];
              $currentsub = '';
              $countsub = 0;
              $end = strpos($element, $matches[1]) + strlen($matches[1]);
              $subelement = substr($element, 0, $end);
              $return .= '    '.$subelement." = new Object();\n";
          }

          // Now check the subelement subscript.
          if (countArray($matches) > 0 && $currentsub != $matches[2]) {
              $currentsub = $matches[2];
              $countsub++;
              $end = strrpos($element, $matches[2]) + strlen($matches[2]);
              $subelement = substr($element, 0, $end);
              $return .= '    '.$subelement." = new Object();\n";
          }

          $return .= '    '.$element.' = '.json_encode($value).";\n";
      }
      if ($countsub > 0) {
          $return .= '    '.$elementname.$scormseperator.$current.'.'.$currentsubelement.'._count = '.$countsub.";\n";
      }
      if ($count > 0) {
          $return .= '    '.$elementname.'._count = '.$count.";\n";
      }
      return $return;
  }

  public function initScorm($args)
  {
    $jscode = "<script language='JavaScript'>";
    $jscode = $jscode.($this->genJsString('M.scorm_api.init', $args));
    $jscode = $jscode."</script>";
    return $jscode;
  }

  public function genJsString($function, $object)
  {
    $object = array_map('json_encode', $this->convert_to_array($object));
    $args = implode(', ', $object);
    return "$function($args);\n";
  }

  private function convert_to_array($var) {
      $result = array();

      // Loop over elements/properties.
      foreach ($var as $key => $value) {
          // Recursively convert objects.
          if (is_object($value) || is_array($value)) {
              $result[$key] = $this->convert_to_array($value);
          } else {
              // Simple values are untouched.
              $result[$key] = $value;
          }
      }
      return $result;
  }

  private function issetDefault($data, $param, $value='')
  {
    if (isset($data[$param])){
      return $data[$param];
    } else {
      return $value;
    }
  }

  public function newScorm($code)
  {
    $user = $this->session->userdata("user");
    $usn = $this->session->userdata("name");
    $lang = ($this->session->userdata("lang") == NULL) ? 'thai': $this->session->userdata("lang");
    $fullname = $user['fullname_th'];
    if($lang!="thai"){
      $fullname = $user['fullname_en'];
    }
    $def = array(
      'cmi_core_student_id' => $user['emp_id'],
      'cmi_core_student_name' => $fullname,
      'cmi_core_lesson_location' => '',
      'cmi_core_credit' => 'credit',
      'cmi_core_lesson_status' => '',
      'cmi_core_entry' => 'ab-initio',
      'cmi_core_score_raw' => '',
      'cmi_core_score_max' => '',
      'cmi_core_score_min' => '',
      'cmi_core_total_time' => "00:00:00",
      'cmi_core_lesson_mode' => 'normal',
      'cmi_core_exit' => '',
      'cmi_core_session_time' => "00:00:00.0",
      'cmi_suspend_data' => '',
      'cmi_launch_data' => '',
      'cmi_comments' => '',
      'cmi_evaluation_comments_n_time' => "00:00:00.0",
      'cmi_student_data_mastery_score' => '',
      'cmi_student_data_max_time_allowed' => "",
      'cmi_student_data_time_limit_action' => "",
      'cmi_student_preference_audio' => '0',
      'cmi_student_preference_language' => '',
      'cmi_student_preference_speed' => '0',
      'cmi_student_preference_text' => '0',
      'cmi_success_status' => '',
    );
    return $def;
  }

}
