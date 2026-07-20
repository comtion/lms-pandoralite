<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorize extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$arr['lang'] = $lang;
		$arr['page'] = "home";

	    $this->load->model('Manage_model', 'manage', FALSE);
	    $this->manage->loadDB();
	    $this->load->model('Dashboard_model', 'dashboard', FALSE);
	    $this->dashboard->loadDB();
	    $this->load->model('Function_query_model', 'func_query', FALSE);
	    $this->func_query->loadDB();
	    $arr['arr_permission'] = $this->manage->chk_permission_page();
		$this->load->model('User_model', 'login', FALSE);
		/*if($this->login->checkSession($arr['page'])){
			$sess = $this->session->userdata("user");
			//redirect(base_url().'dashboard') ;
		}else{*/
			function getContentUrl_userdata($url,$code,$val_setting) {
                $ch = curl_init($url);
                $fields = array(
                    'code' => $code, //IxwqjvibNUq4j5xRrPrgRf6U3X3UNquR-7XzOvGV6oc=
                    'redirect_uri' => $val_setting['sso_redirect_url'],
                    'grant_type' => 'authorization_code',
                    'client_id' => $val_setting['sso_client_id'],
                );

                $data = http_build_query($fields);
                $decdata = $val_setting['sso_client_id'].":".$val_setting['sso_password'];
                $decdata = utf8_encode($decdata);
                //$sha1 = sha1($decdata, TRUE);
                //$hash = hash_hmac( "sha256", $decdata, true );
                $raw = base64_encode($decdata);
                //echo $raw;
                $headers = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer '.$val_setting['sso_access_token']
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
            }

            function getContentUrl_sessionthree($url,$val_setting) {
                $ch = curl_init($url);
                $decdata = $val_setting['sso_client_id'].":".$val_setting['sso_password'];
                $decdata = utf8_encode($decdata);
                //$sha1 = sha1($decdata, TRUE);
                //$hash = hash_hmac( "sha256", $decdata, true );
                $raw = base64_encode($decdata);
                //echo $raw;
                $headers = array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer '.$val_setting['sso_access_token']
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $file = curl_exec($ch);
                if($file === false) trigger_error(curl_error($ch));
                curl_close ($ch);
                return $file;
            }

            		function getContentUrl($url,$arr_sso) {
		                $ch = curl_init($url);
		                $fields = array(
		                    'Username' => 'yupontee.k@verztec.com', // array key corresponds to the name of a field on your form
		                    'Password' => 'Verztec123',
		                    'grant_type' => 'client_credentials',
		                    'scope' => 'token,user',
		                );

		                $data = http_build_query($fields);
                		$decdata = $arr_sso['sso_client_id'].":".$arr_sso['sso_password'];
		                $decdata = utf8_encode($decdata);
		                //$sha1 = sha1($decdata, TRUE);
		                //$hash = hash_hmac( "sha256", $decdata, true );
		                $raw = base64_encode($decdata);
		                //echo $raw;
		                $headers = array(
		                    'Content-type: application/x-www-form-urlencoded',
		                    'Authorization: Basic '.$raw,
		                );
		                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
		                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
		                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
		                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		                curl_setopt($ch, CURLOPT_HEADER, 0);
		                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		                $file = curl_exec($ch);
		                if($file === false) trigger_error(curl_error($ch));
		                curl_close ($ch);
		                return $file;
		            }

    		date_default_timezone_set("Asia/Bangkok");
            $code = isset($_REQUEST['code'])?$_REQUEST['code']:"";
            /*	$arr_sso = $this->func_query->query_row('lms_setting_sso','','','','sso_id=1');
		            $arr_check = getContentUrl('https://sso-api.thaihealth.or.th:9100/oauth2/token',$arr_sso);
		            print_r($arr_check);
            $arr_user = getContentUrl_userdata('https://sso.thaihealth.or.th/api/token/validate',$code,$arr_sso);
           	$output_user = json_decode($arr_user,true);
		            $arr_user_detail = getContentUrl_sessionthree('https://sso.thaihealth.or.th/api/userrole/'.$output_user['uid'].'/client/THELearning',$arr_sso);
            print_r($code);
            print_r($arr_user);
            print_r($arr_user_detail);*/
            if($code!=""){
            	$arr_sso = $this->func_query->query_row('lms_setting_sso','','','','sso_id=1');
            	$arr_user = getContentUrl_userdata('https://sso.thaihealth.or.th/api/token/validate',$code,$arr_sso);
            	$output_user = json_decode($arr_user,true);
            	if(isset($output_user['uid'])&&$output_user['uid']!=""){
		            $arr_user_detail = getContentUrl_sessionthree('https://sso.thaihealth.or.th/api/userrole/'.$output_user['uid'].'/client/THELearning',$arr_sso);
		            $output_user_detail = json_decode($arr_user_detail,true);
		            if(countArray($output_user_detail)>0){
		            	$arr_emp = array(
			            	'emp_id' => $output_user['uid'],
			            	'emp_c' => $output_user['uid'],
			            	'prefix_th' => 'คุณ',
			            	'fname_th' => $output_user['firstname'],
			            	'lname_th' => $output_user['lastname'],
			            	'fullname_th' => 'คุณ'.$output_user['firstname']." ".$output_user['lastname'],
			            	'prefix_en' => 'K.',
			            	'fname_en' => $output_user['firstname'],
			            	'lname_en' => $output_user['lastname'],
			            	'fullname_en' => 'K.'.$output_user['firstname']." ".$output_user['lastname'],
			            	'gender' => $output_user['gender'],
			            	'birthdate' => date('Y-m-d',strtotime($output_user['date_of_birth'])),
			            	'employ_date' => date('Y-m-d',strtotime($output_user['created_at'])),
			            	'lang' => 'thai',
			            	'com_id' => '3',
			            	'phone' => $output_user['tel'],
			            	'email' => $output_user['email'],
			            	'u_date' => date('Y-m-d H:i')
			            );
			            $chkemp = $this->func_query->query_row('lms_emp','','','','emp_id="'.$arr_emp['emp_id'].'"');
			            if(countArray($chkemp)>0){
			            	$chkemp_update = $this->func_query->query_row('lms_emp','','','','emp_id='.$arr_emp['emp_id'].' and (fullname_th!="'.$arr_emp['fullname_th'].'" or fullname_en!="'.$arr_emp['fullname_en'].'" or birthdate!="'.$arr_emp['birthdate'].'" or phone!="'.$arr_emp['phone'].'" or email!="'.$arr_emp['email'].'")');
			            	if(countArray($chkemp_update)>0){
			            		$this->db->where('emp_id',$arr_emp['emp_id']);
			            		$this->db->update('lms_emp',$arr_emp);
			            	}
			            }else{
			            	$arr_emp['c_date'] = date('Y-m-d H:i');
			            	$this->db->insert('lms_emp',$arr_emp);
			            }
			            $Is_admin = 0;
			            if (strpos($output_user_detail[0]['role_data']['code'], 'Admin') !== false) {
						    $Is_admin = 1;
						}
			            $arr_usergroup = array(
			            	'ug_id' => $output_user_detail[0]['roleID'],
			            	'ug_name_th' => $output_user_detail[0]['role_data']['description'],
			            	'ug_name_en' => $output_user_detail[0]['role_data']['name'],
			            	'ug_code' => $output_user_detail[0]['role_data']['code'],
			            	'ug_for' => 'CUSTOMER',
			            	'Is_admin' => $Is_admin,
			            	'ug_createdate' => date('Y-m-d',strtotime($output_user_detail[0]['role_data']['createdAt']))
			            );
			            $chkug = $this->func_query->query_row('lms_usp_gp','','','','ug_code="'.$arr_usergroup['ug_code'].'"');
			            if(countArray($chkug)>0){
			            	$chkug_update = $this->func_query->query_row('lms_usp_gp','','','','ug_code="'.$arr_usergroup['ug_code'].'" and (ug_name_th!="'.$arr_usergroup['ug_name_th'].'" or ug_name_en!="'.$arr_usergroup['ug_name_en'].'" )');
			            	if(countArray($chkug_update)>0){
			            		$this->db->where('ug_code',$arr_usergroup['ug_code']);
			            		$this->db->update('lms_usp_gp',$arr_usergroup);
			            	}
			            }else{
			            	$this->db->insert('lms_usp_gp',$arr_usergroup);
			            }
			            $password_enc = hash('sha256', 'Init1234');
			            $arr_user = array(
			            	'emp_id' => $output_user['uid'],
			            	'useri' => $output_user['email'],
			            	'userp' => $password_enc,
			            	'dummy_status' => '0',
			            	'ug_id' => $arr_usergroup['ug_id'],
			            	'code' => $code
			            );
			            $chkuser = $this->func_query->query_row('lms_usp','','','','emp_id="'.$arr_user['emp_id'].'"');
			            if(countArray($chkuser)>0){
			            	$chkuser_update = $this->func_query->query_row('lms_usp','','','','emp_id="'.$arr_user['emp_id'].'"');
			            	if(countArray($chkuser_update)>0){
			            		$this->db->where('emp_id',$arr_user['emp_id']);
			            		$this->db->update('lms_usp',$arr_user);
			            		$this->manage->rechk_role($chkuser['u_id'],$output_user_detail[0]['roleID']);
			            	}
			            }else{
			            	$this->db->insert('lms_usp',$arr_user);
			            	$id = $this->db->insert_id();
			            	$this->manage->rechk_role($id,$output_user_detail[0]['roleID']);
			            }

					    $this->db->from('lms_usp');
					    $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
					    //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
					    $this->db->join('lms_company','lms_emp.com_id = lms_company.com_id');
					    $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
					    //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
					    $this->db->where('lms_usp.emp_id', $arr_user['emp_id']);
					    $this->db->where('lms_emp.status', '1');
					    $query = $this->db->get();

					    if($query->num_rows() > 0)
					    {
					      	$result = $query->row_array();
					        $session_data = $result;
					        $this->session->set_userdata('user', $session_data);
					        $this->changeLogs($session_data['useri']);
					        if($session_data['lang']=="thai"){
					          $name = $session_data['fullname_th'];
					        }else{
					          $name = $session_data['fullname_en'];
					        }
					        
					        $this->session->set_userdata('name', $name);
							$this->load->model('Log_model', 'lg', FALSE);
							$this->lg->loadDB();
							$this->lg->record('home', 'user id '.$result['emp_c'].' logged in website.');
							$this->lg->closeDB();
					    }
		            }
            	}else{
            		$arr_sso = $this->func_query->query_row('lms_setting_sso','','','','sso_id=1');
		            $arr_check = getContentUrl('https://sso-api.thaihealth.or.th:9100/oauth2/token',$arr_sso);
		            $output = json_decode($arr_check,true);
		            $data_sso = array(
		            	'sso_access_token' => $output['access_token']
		            );
		            $this->db->where('sso_id','1');
		            $this->db->update('lms_setting_sso',$data_sso);

		           	$arr_sso = $this->func_query->query_row('lms_setting_sso','','','','sso_id=1');
	            	$arr_user = getContentUrl_userdata('https://sso.thaihealth.or.th/api/token/validate',$code,$arr_sso);
	            	$output_user = json_decode($arr_user,true);
	            	if(isset($output_user['uid'])&&$output_user['uid']!=""){
			            $arr_user_detail = getContentUrl_sessionthree('https://sso.thaihealth.or.th/api/userrole/'.$output_user['uid'].'/client/THELearning',$arr_sso);
			            $output_user_detail = json_decode($arr_user_detail,true);
			            $arr_emp = array(
			            	'emp_id' => $output_user['uid'],
			            	'emp_c' => $output_user['uid'],
			            	'prefix_th' => 'คุณ',
			            	'fname_th' => $output_user['firstname'],
			            	'lname_th' => $output_user['lastname'],
			            	'fullname_th' => 'คุณ'.$output_user['firstname']." ".$output_user['lastname'],
			            	'prefix_en' => 'K.',
			            	'fname_en' => $output_user['firstname'],
			            	'lname_en' => $output_user['lastname'],
			            	'fullname_en' => 'K.'.$output_user['firstname']." ".$output_user['lastname'],
			            	'gender' => $output_user['gender'],
			            	'birthdate' => date('Y-m-d',strtotime($output_user['date_of_birth'])),
			            	'employ_date' => date('Y-m-d',strtotime($output_user['created_at'])),
			            	'lang' => 'thai',
			            	'com_id' => '3',
			            	'phone' => $output_user['tel'],
			            	'email' => $output_user['email'],
			            	'u_date' => date('Y-m-d H:i')
			            );
			            $chkemp = $this->func_query->query_row('lms_emp','','','','emp_id="'.$arr_emp['emp_id'].'"');
			            if(countArray($chkemp)>0){
			            	$chkemp_update = $this->func_query->query_row('lms_emp','','','','emp_id='.$arr_emp['emp_id'].' and (fullname_th!="'.$arr_emp['fullname_th'].'" or fullname_en!="'.$arr_emp['fullname_en'].'" or birthdate!="'.$arr_emp['birthdate'].'" or phone!="'.$arr_emp['phone'].'" or email!="'.$arr_emp['email'].'")');
			            	if(countArray($chkemp_update)>0){
			            		$this->db->where('emp_id',$arr_emp['emp_id']);
			            		$this->db->update('lms_emp',$arr_emp);
			            	}
			            }else{
			            	$arr_emp['c_date'] = date('Y-m-d H:i');
			            	$this->db->insert('lms_emp',$arr_emp);
			            }
			            $Is_admin = 0;
			            if (strpos($output_user_detail[0]['role_data']['code'], 'Admin') !== false) {
						    $Is_admin = 1;
						}
			            $arr_usergroup = array(
			            	'ug_id' => $output_user_detail[0]['roleID'],
			            	'ug_name_th' => $output_user_detail[0]['role_data']['description'],
			            	'ug_name_en' => $output_user_detail[0]['role_data']['name'],
			            	'ug_code' => $output_user_detail[0]['role_data']['code'],
			            	'ug_for' => 'CUSTOMER',
			            	'Is_admin' => $Is_admin,
			            	'ug_createdate' => date('Y-m-d',strtotime($output_user_detail[0]['role_data']['createdAt']))
			            );
			            $chkug = $this->func_query->query_row('lms_usp_gp','','','','ug_code="'.$arr_usergroup['ug_code'].'"');
			            if(countArray($chkug)>0){
			            	$chkug_update = $this->func_query->query_row('lms_usp_gp','','','','ug_code="'.$arr_usergroup['ug_code'].'" and (ug_name_th!="'.$arr_usergroup['ug_name_th'].'" or ug_name_en!="'.$arr_usergroup['ug_name_en'].'" )');
			            	if(countArray($chkug_update)>0){
			            		$this->db->where('ug_code',$arr_usergroup['ug_code']);
			            		$this->db->update('lms_usp_gp',$arr_usergroup);
			            	}
			            }else{
			            	$this->db->insert('lms_usp_gp',$arr_usergroup);
			            }
			            $password_enc = hash('sha256', 'Init1234');
			            $arr_user = array(
			            	'emp_id' => $output_user['uid'],
			            	'useri' => $output_user['email'],
			            	'userp' => $password_enc,
			            	'dummy_status' => '0',
			            	'ug_id' => $arr_usergroup['ug_id'],
			            	'code' => $code
			            );
			            $chkuser = $this->func_query->query_row('lms_usp','','','','emp_id="'.$arr_user['emp_id'].'"');
			            if(countArray($chkuser)>0){/*
			            	$chkuser_update = $this->func_query->query_row('lms_usp','','','','emp_id="'.$arr_user['emp_id'].'" and (useri!="'.$arr_user['useri'].'")');
			            	if(countArray($chkuser_update)>0){*/
			            		$this->db->where('emp_id',$arr_user['emp_id']);
			            		$this->db->update('lms_usp',$arr_user);
			            		//$this->manage->rechk_role($chkuser['u_id'],$output_user_detail[0]['roleID']);
			            	//}
			            }else{
			            	$this->db->insert('lms_usp',$arr_user);
			            	$id = $this->db->insert_id();
			            	$this->manage->rechk_role($id,$output_user_detail[0]['roleID']);
			            }

					    $this->db->from('lms_usp');
					    $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
					    //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
					    $this->db->join('lms_company','lms_emp.com_id = lms_company.com_id');
					    $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
					    //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
					    $this->db->where('lms_usp.emp_id', $arr_user['emp_id']);
					    $this->db->where('lms_emp.status', '1');
					    $query = $this->db->get();

					    if($query->num_rows() > 0)
					    {
					      	$result = $query->row_array();
					        $session_data = $result;
					        $this->session->set_userdata('user', $session_data);
					        $this->changeLogs($session_data['useri']);
					        if($session_data['lang']=="thai"){
					          $name = $session_data['fullname_th'];
					        }else{
					          $name = $session_data['fullname_en'];
					        }
					        
					        $this->session->set_userdata('name', $name);
							$this->load->model('Log_model', 'lg', FALSE);
							$this->lg->loadDB();
							$this->lg->record('home', 'user id '.$result['emp_c'].' logged in website.');
							$this->lg->closeDB();
					    }
	            	}
            	}
            }
		    //$this->load->view('frontend/authorize', $arr );
			redirect(base_url().'home') ;
		//}
	}

	
	  public function changeLogs($code)
	  {
	    $data = array(
	      'st_on' => 'online'
	    );
	    $this->update($data, $code);
	  }
	  
	  private function update($data, $code)
	  {
	    $this->db->set('last_act', 'NOW()', FALSE);
	    $this->db->set('login', 'true', FALSE);
	    $this->db->where('useri', $code);
	    $this->db->update('lms_usp', $data);
	  }

}
