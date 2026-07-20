<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends CI_Controller {
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
		$this->load->helper(array('form', 'url'));  
	}

	public function createfile(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Certificate_model', 'cartificate', TRUE);
		$this->cartificate->loadDB();
		$query = $this->cartificate->createfile($user,$_REQUEST['cos_id']);
      	echo json_encode($query);
	}
	public function createfilebyuser($cos_id,$emp_id){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Certificate_model', 'cartificate', TRUE);
		$this->cartificate->loadDB();
		$this->load->model('Function_query_model','func_query', TRUE);
		$user = $this->func_query->query_row('lms_emp','lms_company','lms_emp.com_id = lms_company.com_id','','emp_id = "'.$emp_id.'"');
		$query = $this->cartificate->createfile($user,$cos_id,'2020-08-17');
      	echo json_encode($query);
	}

	public function createfilebyuseronly(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		if(!isset($user['u_id']) || $user['u_id'] != "1"){
			echo json_encode(array('status' => '0', 'msg' => 'permission denied'));
			exit();
		}
		$this->load->model('Certificate_model', 'cartificate', TRUE);
		$this->cartificate->loadDB();
		$this->load->model('Function_query_model','func_query', TRUE);
		$cos_id = isset($_REQUEST['cos_id'])?$_REQUEST['cos_id']:"";
		$emp_id = isset($_REQUEST['emp_id'])?$_REQUEST['emp_id']:"";
		$user = $this->func_query->query_row('lms_emp','lms_company','lms_emp.com_id = lms_company.com_id','','emp_id = "'.$emp_id.'"');
		$fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','emp_id = "'.$emp_id.'" and cos_id="'.$cos_id.'"');
		if(isset($fetch_enroll)){
			$fetch_cert = $this->func_query->query_row('lms_certificate','','','','emp_id = "'.$emp_id.'" and cos_id="'.$cos_id.'"');
			if(isset($fetch_cert)){
				if(is_file(ROOT_DIR."uploads/certificate/".$fetch_cert['cert_file'])){
					audit_unlink(ROOT_DIR."uploads/certificate/".$fetch_cert['cert_file']);
				}
				$this->db->where('emp_id',$emp_id);
				$this->db->where('cos_id',$cos_id);
				$this->db->delete('lms_certificate');
			}
			$query = $this->cartificate->createfile($user,$cos_id,date('Y-m-d',strtotime($fetch_enroll['cosen_finishtime'])));
		}else{
			$query = array();
		}
      	echo json_encode($query);
	}

	public function createfilebycourseonly(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		if(!isset($user['u_id']) || $user['u_id'] != "1"){
			echo json_encode(array('status' => '0', 'msg' => 'permission denied'));
			exit();
		}

		$this->load->model('Certificate_model', 'cartificate', TRUE);
		$this->cartificate->loadDB();
		$this->load->model('Function_query_model','func_query', TRUE);

		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";
		$fetch_cert_list = $this->func_query->query_result('lms_certificate','','','','cos_id="'.$cos_id.'"');
		$count_success = 0;

		if(countArray($fetch_cert_list) > 0){
			foreach($fetch_cert_list as $value_cert){
				$emp_id = $value_cert['emp_id'];
				$fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','emp_id = "'.$emp_id.'" and cos_id="'.$cos_id.'" and cosen_status="1" and cosen_status_sub="1"');
				if(!isset($fetch_enroll['emp_id'])){
					continue;
				}

				if(is_file(ROOT_DIR."uploads/certificate/".$value_cert['cert_file'])){
					audit_unlink(ROOT_DIR."uploads/certificate/".$value_cert['cert_file']);
				}
				$this->db->where('emp_id',$emp_id);
				$this->db->where('cos_id',$cos_id);
				$this->db->delete('lms_certificate');

				$fetch_user = $this->func_query->query_row('lms_emp','lms_company','lms_emp.com_id = lms_company.com_id','','emp_id = "'.$emp_id.'"');
				$query = $this->cartificate->createfile($fetch_user,$cos_id,date('Y-m-d',strtotime($fetch_enroll['cosen_finishtime'])));
				if(!empty($query)){
					$count_success++;
				}
			}
		}

		echo json_encode(array(
			'status' => '1',
			'total' => countArray($fetch_cert_list),
			'success' => $count_success
		));
	}

	public function certificateall() {
		$arr['page'] = "certificate/certificateall";

		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('Certificate_model', 'certificate', TRUE);
		$this->load->model('Log_model', 'lg', FALSE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->load->model('Manage_model', 'manage', FALSE);
        $this->manage->loadDB();
		$this->foot->loadDB();
		$this->lg->loadDB();
		$this->certificate->loadDB();
		$this->login->loadDB();
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			$arr['user'] = $user;
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_eng'];
			}
			$arr['arr_permission'] = $this->manage->chk_permission_page();
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
			//Record Log activity
			$this->lg->record('Certificate', 'enter create certificate');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/certificateall', $arr );
		}
	}


	public function generate()
	{
		$image_data = array();
		$document_data = array();
		$nameFileImage = "";
		$nameFileExcel = "";

		if(isset($_FILES['cert_image'])&&$_FILES['cert_image']!=""){
			if( isset( $_FILES['cert_image']) ){
				$imageSourcePath = $_FILES['cert_image']['tmp_name'];
				$nameFileImage = "certificate_img".date("YmdHis").".jpg";
				$imageTargetPath = ROOT_DIR."uploads/temp/".$nameFileImage;
				if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
				}
			}
		}
		if(isset($_FILES['excel'])&&$_FILES['excel']!=""){
			if( isset( $_FILES['excel']) ){
				$imageSourcePath = $_FILES['excel']['tmp_name'];
				$nameFileExcel = "certificate_excel".date("YmdHis").".xlsx";
				$imageTargetPath = ROOT_DIR."uploads/temp/".$nameFileExcel;
				if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
				}
			}
		}
		
		$arr = array(
			"nameFileImage" => $nameFileImage,
			"nameFileExcel" => $nameFileExcel,
		);
		
		if (is_file(ROOT_DIR . "uploads/temp/" . $nameFileExcel)) {
			if (empty($_FILES['cert_image']['name'])) {
				$this->load->view('frontend/certificateViewDefault', $arr);
			} else {
				$this->load->view('frontend/certificateViewUpload', $arr);
			}
		}
	}
}
