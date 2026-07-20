<?php
class Log_model extends CI_Model {

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

  public function record($activity, $message)
  {
    date_default_timezone_set("Asia/Bangkok");
                      $device = '';
                      $platform = '';
                      $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
                        if($u_agent !== '' && preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|up\.browser|up\.link|webos|wos)/i", $u_agent)){
                          $device = 'Mobile';
                          if (preg_match('/Mac OS/i', $u_agent)) {
                              $platform = 'Apple';
                          }
                          elseif (preg_match('/Android/i', $u_agent)) {
                              $platform = 'Android';
                          }
                        }else if($u_agent !== '' && preg_match("/(tablet|iPad)/i", $u_agent)){
                          $device = 'Tablet';
                        }else{
                          $device = 'PC';
                          if (preg_match('/linux/i', $u_agent)) {
                              $platform = 'linux';
                          }
                          elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                              $platform = 'mac';
                          }
                          elseif (preg_match('/windows|win32/i', $u_agent)) {
                              $platform = 'windows';
                          }
                        }
    $sess = $this->session->userdata("user");
    $ip = $this->get_client_ip();
    $data = array(
      'log_time' => date('Y-m-d H:i:s'),
      'log_type' => $activity,
      'massage' => $message,
      'ip' => $ip,
      'device' => trim($device.($platform !== '' ? " : ".$platform : ''))
    );
    !isset($sess['emp_id'])?:$data['emp_id'] = $sess['emp_id'];
    $this->db->insert('lms_lg', $data);
  }
function get_client_ip()
 {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';

      return $ipaddress;
 }
  public function getRecords($date)
  {
    //
    $this->db->from('lms_lg');
    //$this->db->where('lms_emp.lang', $lang);
	    if(isset($date['s'])&&isset($date['e'])){
	    	if($date['s']!="0000-00-00"&&$date['e']!="0000-00-00"){
			      $this->db->where('lms_lg.log_time >=', date('Y-m-d H:i:00',strtotime($date['s'])));
			      $this->db->where('lms_lg.log_time <=', date('Y-m-d H:i:00',strtotime($date['e'])));
	    	}
	    }
    //$where = "(lms_lg.massage LIKE '%logged in website%' OR lms_lg.massage LIKE '%logged in fail%')";
   	//$this->db->where($where);
    //$this->db->limit(10);
    $this->db->order_by('log_time', 'DESC');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getAllEmp($search="")
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $user = $this->session->userdata("user");
    $this->db->select('lms_usp.u_id,lms_emp.emp_c,lms_emp.emp_id,lms_emp.fullname_en,lms_emp.fullname_th,lms_usp.useri, lms_usp_gp.ug_id, lms_usp_gp.ug_name_th, lms_usp_gp.ug_name_en,lms_usp_gp.ug_for,lms_usp.dep_id, lms_depart.dep_name_th, lms_depart.dep_name_en,lms_company.com_id, lms_company.com_name_th, lms_company.com_name_eng,lms_emp.status, lms_emp.lang,lms_emp.is_manager,lms_usp.login ,lms_usp.last_act,lms_usp.firsttime,lms_usp.expiredate,lms_usp.img_profile');
    $this->db->from('lms_usp');
    $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
    $this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id','LEFT');
    $this->db->join('lms_company','lms_emp.com_id = lms_company.com_id');
    $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
    $this->db->where('lms_emp.status', '1');
    if($user['ug_for']=="CUSTOMER"){
      $this->db->where('lms_company.com_id', $user['com_id']);
    }
    if(isset($search['com_id'])&&$search['com_id']!=""){
      $this->db->where('lms_company.com_id', $search['com_id']);
    }
    $query = $this->db->get();
    $all = $query->result_array();
    $emps = array();
    foreach ($all as $row) {
      $emps[$row['emp_id']]['fullname_th'] = $row['fullname_th'];
      $emps[$row['emp_id']]['fullname_en'] = $row['fullname_en'];
      if($lang=="thai"){
        $emps[$row['emp_id']]['com_name'] = $row['com_name_th'];
        $emps[$row['emp_id']]['ug_name'] = $row['ug_name_th'];
        $emps[$row['emp_id']]['dep_name'] = $row['dep_name_th'];
      }else{
        $emps[$row['emp_id']]['com_name'] = $row['com_name_eng'];
        $emps[$row['emp_id']]['ug_name'] = $row['ug_name_en'];
        $emps[$row['emp_id']]['dep_name'] = $row['dep_name_en'];
      }
      $emps[$row['emp_id']]['emp_id'] = $row['emp_id'];
      $emps[$row['emp_id']]['emp_c'] = $row['emp_c'];
      $emps[$row['emp_id']]['useri'] = $row['useri'];
    } return $emps;
  }

  public function getLogImportUserID() {
    $user = $this->session->userdata("user");
    date_default_timezone_set("Asia/Bangkok");
    
    $data = array(
      "lgi_import_by" => isset($user["emp_id"]) ? $user["emp_id"] : "",
      "lgi_datetime"  => date("Y-m-d H:i:s")
    );
    $this->db->insert('lms_lg_import', $data);
    $lgiId = $this->db->insert_id();
    return $lgiId;
  }

  public function insertLogImportUser ($lgiId, $empIdImport, $status) {
    date_default_timezone_set("Asia/Bangkok");
    
    if (isset($lgiId) && $lgiId != "" && $empIdImport != "") {
        // status (	1 = new, 2 = duplicate, 3 = remove )
        $this->db->insert("lms_lg_import_detail", array(
          "lgi_id"          => $lgiId,
          "emp_id"          => $empIdImport,
          "lgid_datetime"   => date("Y-m-d H:i:s"),
          "lgid_status"     => $status
        ));
    }
  }
}
