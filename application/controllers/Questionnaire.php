<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questionnaire extends CI_Controller {
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
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url','array'));
		
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function create(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'questionnaire/create';

		$this->load->model('User_model', 'user', true);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Questionnaire_model', 'questionnaire', true);
		$this->questionnaire->loadDB();
		$this->manage->loadDB();
		$this->user->loadDB();
		$this->foot->loadDB();

		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;


		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Function_query_model', 'func_query', false);
        $this->manage->loadDB();
        $arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
		$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
		$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
        $arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
        $arr['arr_permission'] = $this->manage->chk_permission_page();
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
			$arr['company_arr'] = $this->func_query->query_result('lms_company','','','','com_isDelete="0" and com_status="1" and com_id != "2"');
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
	    if($this->user->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			$arr['user'] = $user;
	    }
		$arr['foote'] = $this->foot->getfooter();
		$arr['company_select'] = $this->manage->getCompany();
		$arr['data_fetch'] = $this->questionnaire->fetch_data_questionnaire();

		$this->load->view('frontend/questionnairemanage', $arr );
	}


	public function fetch_questionnaire(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Questionnaire_model', 'questionnaire', true);
		$this->questionnaire->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->questionnaire->fetch_data_questionnaire($_REQUEST['com_id']) : array();
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

	public function fetch_questionnaire_detail(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
		$this->lang->load($lang, $lang);
		$user = $this->session->userdata('user');
		$this->load->model('Questionnaire_model', 'questionnaire', true);
		$this->questionnaire->loadDB();
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->questionnaire->fetch_data_questionnaire_detail($_REQUEST['qn_id']) : array();
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

	public function update_questionnaire_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_questionnaire','qn_id');

			$qn_lang = explode(',', $result['qn_lang']);
		    $result['isTH'] = in_array('th',$qn_lang)?"1":"0";
		    $result['isENG'] = in_array('eng',$qn_lang)?"1":"0";
		    $result['isJP'] = in_array('jp',$qn_lang)?"1":"0";
                  if($lang=="thai"){ 
                    $qn_title_ = $result['qn_title_th']!=""?$result['qn_title_th']:$result['qn_title_eng'];
                    $qn_title_ = $qn_title_!=""?$qn_title_:$result['qn_title_jp'];
                  }else if($lang=="english"){ 
                    $qn_title_ = $result['qn_title_eng']!=""?$result['qn_title_eng']:$result['qn_title_th'];
                    $qn_title_ = $qn_title_!=""?$qn_title_:$result['qn_title_jp'];
                  }else{
                    $qn_title_ = $result['qn_title_jp']!=""?$result['qn_title_jp']:$result['qn_title_eng'];
                    $qn_title_ = $qn_title_!=""?$qn_title_:$result['qn_title_th'];
                  }
                  $result['txt_head_detail'] = $qn_title_;
			echo json_encode($result);
		}
	}

	public function update_questionnaire_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_questionnaire_de','qnde_id');
			echo json_encode($result);
		}
	}

  	public function insert_questionnaire(){
  		require_once(APPPATH.'libraries/FPDF/Classes/PHPExcel.php');
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Questionnaire_model', 'questionnaire', true);
		$this->questionnaire->loadDB();
		if(countArray($_REQUEST)>0){
        	$qn_lang = isset($_REQUEST['qn_lang'])?implode(',', $_REQUEST['qn_lang']):"";
			$data = array(
				'com_id' => $_REQUEST['com_id'],
				'qn_lang' => $qn_lang,
				'qn_title_th' => isset($_REQUEST['qn_title_th'])?$_REQUEST['qn_title_th']:"",
				'qn_explanation_th' => isset($_REQUEST['qn_explanation_th'])?$_REQUEST['qn_explanation_th']:"",
				'qn_title_eng' => isset($_REQUEST['qn_title_eng'])?$_REQUEST['qn_title_eng']:"",
				'qn_explanation_eng' => isset($_REQUEST['qn_explanation_eng'])?$_REQUEST['qn_explanation_eng']:"",
				'qn_title_jp' => isset($_REQUEST['qn_title_jp'])?$_REQUEST['qn_title_jp']:"",
				'qn_explanation_jp' => isset($_REQUEST['qn_explanation_jp'])?$_REQUEST['qn_explanation_jp']:"",
				'qn_suggestion_status' => isset($_REQUEST['qn_suggestion_status'])?$_REQUEST['qn_suggestion_status']:"0",
	            'qn_modifieddate' => date('Y-m-d H:i'),
	            'qn_modifiedby' => $user['u_id'],
			);

			if($_REQUEST['operation']=="Add"){
	            $data['qn_createdate'] = date('Y-m-d H:i');
	            $data['qn_createby'] = $user['u_id'];

            
	            /*if(isset($_FILES['qn_filename'])&&$_FILES['qn_filename']!=""){
	              if( isset( $_FILES['qn_filename']) ){
	                $imageSourcePath = $_FILES['qn_filename']['tmp_name'];
	                $path_parts = pathinfo($_FILES['qn_filename']['name']);
	                if(isset($path_parts['extension'])){
	                  $qn_filename = "qn_".date('YmdHis').".".$path_parts['extension'];

	                  $imageTargetPath = ROOT_DIR."uploads/excel/".$qn_filename;
	                  if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
	                    $data['qn_filename'] = $qn_filename;
	                  }
	                }
	              }
	            }*/

				$id = $this->questionnaire->create_questionnaire($data);
                if (isset($_FILES['qn_filename']))
                {
				$imageSourcePath = $_FILES['qn_filename']['tmp_name'];
	            $pathBG = $_FILES['qn_filename']['name'];
	                  $array_pathext = explode('.', $pathBG);
	                  $extension = end($array_pathext);
	                  $qn_filename = "importxlsxsurvey_".date('YmdHis').".".$extension;
	                  $imageTargetPath = ROOT_DIR."uploads/excel/".$qn_filename;
	                  if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
							$path = './uploads/excel/'.$qn_filename;
							$objPHPExcel = PHPExcel_IOFactory::load($path);
							foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
								$worksheetTitle     = $worksheet->getTitle();
								$highestRow         = $worksheet->getHighestRow(); // e.g. 10
								$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
								$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
								//$nrColumns = ord($highestColumn) - 64;

								for ($row = 2; $row <= $highestRow; ++ $row) {
									$dep_name_th = '';
									$dep_name_en = '';
									$posi_name_th = '';
									$posi_name_en = '';
									$arr_svde = array();
									$arr_svde['qn_id'] = $id;

									for ($col = 0; $col < $highestColumnIndex; ++ $col) {
								        $cell = $worksheet->getCellByColumnAndRow($col, $row);
								        $val = $cell->getValue();
								        $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);

										if($col==0){ $arr_svde['qnde_heading_eng'] = $val; }
										if($col==1){ $arr_svde['qnde_heading_th'] = $val; }
										if($col==2){ $arr_svde['qnde_heading_jp'] = $val; }
										if($col==3){ $arr_svde['qnde_detail_eng'] = $val; }
										if($col==4){ $arr_svde['qnde_detail_th'] = $val; }
										if($col==5){ $arr_svde['qnde_detail_jp'] = $val; }
									}
									if(($arr_svde['qnde_heading_th']!=""&&$arr_svde['qnde_detail_th']!="")||($arr_svde['qnde_heading_eng']!=""&&$arr_svde['qnde_detail_eng']!="")||($arr_svde['qnde_heading_jp']!=""&&$arr_svde['qnde_detail_jp']!="")){

							                  if($lang=="thai"){ 
							                    $svde_heading = $arr_svde['qnde_heading_th']!=""?$arr_svde['qnde_heading_th']:$arr_svde['qnde_heading_eng'];
							                    $svde_heading = $svde_heading!=""?$svde_heading:$arr_svde['qnde_heading_jp'];
							                    $svde_detail = $arr_svde['qnde_detail_th']!=""?$arr_svde['qnde_detail_th']:$arr_svde['qnde_detail_eng'];
							                    $svde_detail = $svde_detail!=""?$svde_detail:$arr_svde['qnde_detail_jp'];
							                  }else if($lang=="english"){ 
							                    $svde_heading = $arr_svde['qnde_heading_eng']!=""?$arr_svde['qnde_heading_eng']:$arr_svde['qnde_heading_th'];
							                    $svde_heading = $svde_heading!=""?$svde_heading:$arr_svde['qnde_heading_jp'];
							                    $svde_detail = $arr_svde['qnde_detail_eng']!=""?$arr_svde['qnde_detail_eng']:$arr_svde['qnde_detail_th'];
							                    $svde_detail = $svde_detail!=""?$svde_detail:$arr_svde['qnde_detail_jp'];
							                  }else{
							                    $svde_heading = $arr_svde['qnde_heading_jp']!=""?$arr_svde['qnde_heading_jp']:$arr_svde['qnde_heading_eng'];
							                    $svde_heading = $svde_heading!=""?$svde_heading:$arr_svde['qnde_heading_th'];
							                    $svde_detail = $arr_svde['qnde_detail_jp']!=""?$arr_svde['qnde_detail_jp']:$arr_svde['qnde_detail_eng'];
							                    $svde_detail = $svde_detail!=""?$svde_detail:$arr_svde['qnde_detail_th'];
							                  }

										if($data['qn_title_eng']==""){
											$arr_svde['qnde_heading_eng'] = "";
											$arr_svde['qnde_detail_eng'] = "";
										}
										if($data['qn_title_th']==""){
											$arr_svde['qnde_heading_th'] = "";
											$arr_svde['qnde_detail_th'] = "";
										}
										if($data['qn_title_jp']==""){
											$arr_svde['qnde_heading_jp'] = "";
											$arr_svde['qnde_detail_jp'] = "";
										}
										if(($arr_svde['qnde_heading_th']!=""&&$arr_svde['qnde_detail_th']!="")||($arr_svde['qnde_heading_eng']!=""&&$arr_svde['qnde_detail_eng']!="")||($arr_svde['qnde_heading_jp']!=""&&$arr_svde['qnde_detail_jp']!="")){
											$this->db->from('lms_questionnaire_de');
											$this->db->where('qn_id',$id);
											$this->db->where('qnde_heading_th',htmlentities($arr_svde['qnde_heading_th']));
											$this->db->where('qnde_heading_eng',htmlentities($arr_svde['qnde_heading_eng']));
											$this->db->where('qnde_heading_jp',htmlentities($arr_svde['qnde_heading_jp']));
											$this->db->where('qnde_detail_th',htmlentities($arr_svde['qnde_detail_th']));
											$this->db->where('qnde_detail_eng',htmlentities($arr_svde['qnde_detail_eng']));
											$this->db->where('qnde_detail_jp',htmlentities($arr_svde['qnde_detail_jp']));
											$this->db->where('qnde_isDelete','0');
											$query_chk = $this->db->get();
											$num_chk = $query_chk->num_rows();
											if($num_chk==0){
												$arr_svde['qnde_createby'] = $user['u_id'];
												$arr_svde['qnde_createdate'] = date('Y-m-d H:i');
												$arr_svde['qnde_modifiedby'] = $user['u_id'];
												$arr_svde['qnde_modifieddate'] = date('Y-m-d H:i');
												$this->db->insert('lms_questionnaire_de',$arr_svde);
												$svde_id = $this->db->insert_id();
												/*if($svde_id!=""){
								                    $result_arr['success_count']++;
								                    array_push($result_arr['success_data'], $svde_detail);
												}else{
													$result_arr['error_count']++;
					                    			array_push($result_arr['error_data'], $output_array[$i]);
												}*/
											}/*else{
							                    $result_arr['duplicate_count']++;
							                    array_push($result_arr['duplicate_data'], $svde_detail);
											}*/
										}
									}
								}
							}
	                  }
				}else{
					$msg = "error";
				}	
			}else{
				$this->questionnaire->update_questionnaire($data,$_REQUEST['qn_id']);
			}
			$msg = "2";
		}
		echo $msg;
	}
	public function insert_questionnaire_detail(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Questionnaire_model', 'questionnaire', true);
		$this->questionnaire->loadDB();
		if(countArray($_REQUEST)>0){
			$data = array(
				'qn_id' => $_REQUEST['qn_id_detail'],
				'qnde_heading_th' => $_REQUEST['qnde_heading_th'],
				'qnde_detail_th' => $_REQUEST['qnde_detail_th'],
				'qnde_heading_eng' => $_REQUEST['qnde_heading_eng'],
				'qnde_detail_eng' => $_REQUEST['qnde_detail_eng'],
				'qnde_heading_jp' => $_REQUEST['qnde_heading_jp'],
				'qnde_detail_jp' => $_REQUEST['qnde_detail_jp'],
			);

	        $data['qnde_modifiedby'] = $user['u_id'];
	        $data['qnde_modifieddate'] = date('Y-m-d H:i');
			if($_REQUEST['operation_detail']=="Add"){
		        $data['qnde_createby'] = $user['u_id'];
		        $data['qnde_createdate'] = date('Y-m-d H:i');
				$this->questionnaire->create_questionnaire_detail($data);
			}else{
				$this->questionnaire->update_questionnaire_detail($data,$_REQUEST['qnde_id']);
			}
			$msg = "2";
		}
		echo $msg;
	}

}
