<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller {

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

	public function __construct()
	{
		parent::__construct();
		error_reporting(0);
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function create_template(){

		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "quiz/create_template";

		$this->load->model('User_model', 'login', false);
		if($this->login->checkSession($arr['page'])){
			$sess = $this->session->userdata("user");
			
			$arr['emp_c'] = $sess['emp_c'];
			$arr['com_admin'] = $sess['com_admin'];
			$arr['com_id'] = $sess['com_id'];
			$arr['user'] = $sess;
			if($lang=="thai"){
				$arr['com_name'] = $sess['com_name_th'];
			}else{
				$arr['com_name'] = $sess['com_name_eng'];
			}
			$this->load->model('Footer_model', 'foot', false);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->model('Manage_model', 'manage', false);
			$this->load->model('Function_query_model', 'func_query', false);
			$this->manage->loadDB();
			$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
        	$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
			$arr['main_menu'] = $this->manage->checkmenu();
			$arr['title'] = $this->manage->get_namemenu($arr['page']);
			$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
			$arr['submenu'] = array();
			$arr['submenu_b'] = array();
			foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
				$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
				if(countArray($li_arr_sub)){
					$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
					foreach ($li_arr_sub as $key_sub => $value_sub) {
						$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
						if(countArray($li_arr_sub_b)>0){
							$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
						}
					}
				}
			}

			$arr['company_select'] = $this->manage->getCompany();
			$this->manage->closeDB();
			$this->load->view('frontend/quiz_template', $arr );
		}else{
			redirect(base_url().'dashboard/login');
		}
	}


	public function fetch_course_question(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Quizex_model', 'quizex', true);
		$this->quizex->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->quizex->fetch_course_question($user,$_REQUEST['qize_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
			"draw"            => $draw,
			"recordsTotal"    => $count,
			"recordsFiltered" => $count,
			"data"            => $query,
			"error"           => $isError
		);
		echo json_encode($result);
		exit();
	}

	public function insert_quiz_ex(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
        date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Quizex_model', 'quizex', false);
		$this->quizex->loadDB();
    	$sess = $this->session->userdata("user");
		if(countArray($_REQUEST)>0){
        	$qize_lang = isset($_REQUEST['qize_lang'])?implode(',', $_REQUEST['qize_lang']):"";
			$data = array(
				'qize_lang' => $qize_lang,
				'qize_name_th' => isset($_REQUEST['qize_name_th'])?$_REQUEST['qize_name_th']:"",
				'qize_info_th' => isset($_REQUEST['qize_info_th'])?$_REQUEST['qize_info_th']:"",
				'qize_name_eng' => isset($_REQUEST['qize_name_eng'])?$_REQUEST['qize_name_eng']:"",
				'qize_info_eng' => isset($_REQUEST['qize_info_eng'])?$_REQUEST['qize_info_eng']:"",
				'qize_name_jp' => isset($_REQUEST['qize_name_jp'])?$_REQUEST['qize_name_jp']:"",
				'qize_info_jp' => isset($_REQUEST['qize_info_jp'])?$_REQUEST['qize_info_jp']:"",
				'com_id' => $_REQUEST['com_id'],
	            'qize_modifieddate' => date('Y-m-d H:i'),
	            'qize_modifiedby' => $sess['u_id'],
			);
			if($_REQUEST['operation']=="Add"){
	            $data['qize_createdate'] = date('Y-m-d H:i');
	            $data['qize_createby'] = $sess['u_id'];
				$msg = $this->quizex->create_quiz_ex($data);
			}else{
				$msg = $this->quizex->update_quiz_ex($data,$_REQUEST['qize_id']);
			}
		}
		echo $msg;
	}

	public function fetch_detail_quizex(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Quizex_model', 'quizex', true);
		$this->quizex->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->quizex->fetch_data_quizex($_REQUEST['com_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
			"draw"            => $draw,
			"recordsTotal"    => $count,
			"recordsFiltered" => $count,
			"data"            => $query,
			"error"           => $isError
		);
		echo json_encode($result);
		exit();
	}
	

	public function update_quizex_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['qize_id_update'],'lms_qiz_exp','qize_id');
			$qize_lang = explode(',', $result['qize_lang']);
		    $result['isTH'] = in_array('th',$qize_lang)?"1":"0";
		    $result['isENG'] = in_array('eng',$qize_lang)?"1":"0";
		    $result['isJP'] = in_array('jp',$qize_lang)?"1":"0";
			echo json_encode($result);
		}
	}



  	public function insert_question(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata('user');
		$this->load->model('Quizex_model', 'quizex', false);
		$this->quizex->loadDB();
		if(countArray($_REQUEST)>0){
			$data = array(
				'qize_id' => $_REQUEST['qize_id_question'],
				'quese_type' => $_REQUEST['quese_type'],
				'quese_name_th' => isset($_REQUEST['quese_name_th'])?$_REQUEST['quese_name_th']:"",
				'quese_info_th' => isset($_REQUEST['quese_info_th'])?$_REQUEST['quese_info_th']:"",
				'quese_name_eng' => isset($_REQUEST['quese_name_eng'])?$_REQUEST['quese_name_eng']:"",
				'quese_info_eng' => isset($_REQUEST['quese_info_eng'])?$_REQUEST['quese_info_eng']:"",
				'quese_name_jp' => isset($_REQUEST['quese_name_jp'])?$_REQUEST['quese_name_jp']:"",
				'quese_info_jp' => isset($_REQUEST['quese_info_jp'])?$_REQUEST['quese_info_jp']:"",
				'quese_score' => $_REQUEST['quese_score'],
				'quese_show' => isset($_REQUEST['quese_show'])?$_REQUEST['quese_show']:"0",
	            'quese_modifieddate' => date('Y-m-d H:i'),
	            'quese_modifiedby' => $sess['u_id'],
			);
			$mule_answer = "";
			$num_arr = 0;
			if(isset($_REQUEST['mule_answer'])&&countArray($_REQUEST['mule_answer'])>0){
				foreach ($_REQUEST['mule_answer'] as $key) {
					$mule_answer .= $key;
					$num_arr++;
					if($num_arr<countArray($_REQUEST['mule_answer'])){
						$mule_answer .= ",";
					}
				}
			}
			$data_detail = array(
				'mule_c1_th' => isset($_REQUEST['mule_c1_th'])?$_REQUEST['mule_c1_th']:"",
				'mule_c2_th' => isset($_REQUEST['mule_c2_th'])?$_REQUEST['mule_c2_th']:"",
				'mule_c3_th' => isset($_REQUEST['mule_c3_th'])?$_REQUEST['mule_c3_th']:"",
				'mule_c4_th' => isset($_REQUEST['mule_c4_th'])?$_REQUEST['mule_c4_th']:"",
				'mule_c5_th' => isset($_REQUEST['mule_c5_th'])?$_REQUEST['mule_c5_th']:"",
				'mule_c1_eng' => isset($_REQUEST['mule_c1_eng'])?$_REQUEST['mule_c1_eng']:"",
				'mule_c2_eng' => isset($_REQUEST['mule_c2_eng'])?$_REQUEST['mule_c2_eng']:"",
				'mule_c3_eng' => isset($_REQUEST['mule_c3_eng'])?$_REQUEST['mule_c3_eng']:"",
				'mule_c4_eng' => isset($_REQUEST['mule_c4_eng'])?$_REQUEST['mule_c4_eng']:"",
				'mule_c5_eng' => isset($_REQUEST['mule_c5_eng'])?$_REQUEST['mule_c5_eng']:"",
				'mule_c1_jp' => isset($_REQUEST['mule_c1_jp'])?$_REQUEST['mule_c1_jp']:"",
				'mule_c2_jp' => isset($_REQUEST['mule_c2_jp'])?$_REQUEST['mule_c2_jp']:"",
				'mule_c3_jp' => isset($_REQUEST['mule_c3_jp'])?$_REQUEST['mule_c3_jp']:"",
				'mule_c4_jp' => isset($_REQUEST['mule_c4_jp'])?$_REQUEST['mule_c4_jp']:"",
				'mule_c5_jp' => isset($_REQUEST['mule_c5_jp'])?$_REQUEST['mule_c5_jp']:"",
				'mule_answer' => $mule_answer
			);
			if($_REQUEST['operation_question']=="Add"){
	            $data['quese_createdate'] = date('Y-m-d H:i');
	            $data['quese_createby'] = $sess['u_id'];
				$id = $this->quizex->create_question($data);
				if($_REQUEST['quese_type']=="multi"||$_REQUEST['quese_type']=="2choice"){
					$data_detail['quese_id'] = $id;
					$this->quizex->create_question_multi($data_detail);
				}
			}else{
				$this->quizex->update_question($data,$_REQUEST['quese_id']);
				if($_REQUEST['quese_type']=="multi"||$_REQUEST['quese_type']=="2choice"){
					$data_detail['quese_id'] = $_REQUEST['quese_id'];
					$this->quizex->create_question_multi($data_detail);
				}
			}
			//$this->quizex->cal_score_quiz($_REQUEST['qiz_id_question']);
			$msg = "2";
		}
		echo $msg;
	}


	public function update_question_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['quese_id_update'],'lms_quese','quese_id');
			if($result['quese_type']=="multi"||$result['quese_type']=="2choice"){
				$result_multi = $this->manage->query_data_onupdate($_REQUEST['quese_id_update'],'lms_quese_mul','quese_id');
				$result['multi'] = $result_multi;
			}
			echo json_encode($result);
		}
	}

}
