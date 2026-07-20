<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compliance extends CI_Controller {
	//compliance
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url','array'));
		
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function create(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/create';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;


		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];

			$arr['id'] = '';
			$arr['topic_name_th'] = '';
			$arr['topic_name_en'] = '';
			$arr['name_the_executive_th'] = '';
			$arr['name_the_executive_en'] = '';
			$arr['chkbox_showtopic'] = '0';
			$arr['position_the_executive_th'] = '';
			$arr['position_the_executive_en'] = '';
			$arr['message_from_the_executive_th'] = '';
			$arr['message_from_the_executive_en'] = '';
			$arr['time_start'] = '';
			$arr['time_end'] = '';
			$arr['recommendation_th'] = '';
			$arr['recommendation_en'] = '';
			$arr['image_the_executive'] = '';
			$arr['org_code'] = '';
		$arr['foote'] = $this->foot->getfooter();
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		$arr['company_level'] = $this->compliance->rechkcompany_level();
		$this->load->view('frontend/complianceCreate', $arr );
	}


	public function createcompliance(){

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->load->model('Date_model', 'date', FALSE);
			$this->compliance->loadDB();

		$image_file = str_replace("&","",$_FILES["image_the_executive"]["name"]);
		$image_file = str_replace(" ","_",$image_file);
		//echo $image_file;
		$topic_name_th = $this->input->post('topic_name_th');
		$topic_name_en = $this->input->post('topic_name_en');
		$name_the_executive_th = $this->input->post('name_the_executive_th');
		$name_the_executive_en = $this->input->post('name_the_executive_en');
		$chkbox_showtopic = $this->input->post('chkbox_showtopic');
		$time_start = $this->input->post('time_start');
		$time_end = $this->input->post('time_end');
		$message_from_the_executive_th = $this->input->post('message_from_the_executive_th');
		$message_from_the_executive_en = $this->input->post('message_from_the_executive_en');
		$recommendation_th = $this->input->post('recommendation_th');
		$recommendation_en = $this->input->post('recommendation_en');
		$position_the_executive_th = $this->input->post('position_the_executive_th');
		$position_the_executive_en = $this->input->post('position_the_executive_en');
		$company_level = $this->input->post('company_level');
		$image = $this->input->post('image');
		$time_start =$this->date->convertDate( $time_start );
		$time_end =$this->date->convertDate( $time_end );

		$id = $this->compliance->insertComplianceHead($topic_name_th,$topic_name_en,$name_the_executive_th,$name_the_executive_en,$message_from_the_executive_th,$message_from_the_executive_en,$recommendation_th,$recommendation_en,$position_the_executive_th,$position_the_executive_en,$image_file,$lang,$time_start,$time_end,$company_level,$chkbox_showtopic);
		$document_config['upload_path'] = './uploads/image/';
		$document_config['allowed_types'] = 'gif|jpg|png';		
		$document_config['overwrite'] = TRUE;
		$document_config['file_name'] = $image_file;

		
		$this->load->library('upload');
		$this->upload->initialize($document_config);
		   
		// process excel upload
		$this->upload->do_upload('image_the_executive');
		$document_data = $this->upload->data();

			$arr['page'] = 'compliance/topic';

			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

			$arr['lang'] = $lang;
			$arr['comp_id'] = $id;
			$arr['topic_name_th'] = $topic_name_th;
			$arr['topic_name_en'] = $topic_name_en;

			$arr['emp_c'] = $user['emp_c'];
			$arr['role'] = $user['role'];
			$arr['foote'] = $this->foot->getfooter();
			if($id=="0"){
				echo "<meta http-equiv='refresh' content='0'>" ;
				echo"<script language='JavaScript'>";
				echo"alert('Error!!!');";
				echo"window.location='".base_url()."compliance/create';";
				echo"</script>";
			}else{
				redirect(base_url().'compliance/topic/'.$id);
			}
	}

	public function topic($id){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/topic';

		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->langM->loadDB();
		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['langs'] = $this->langM->getAllLangs();
		$arr['lang_tab'] = $lang;
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		//cc = category choice
		$arr['comp_id'] = $id;
		//cc = category choice
		$arr['topic_head'] = $this->compliance->selectTopic($id);

		//cn = category name
		//$arr['topicdetail'] = $this->compliance->selectTopicDetail($id);

		$this->load->view('frontend/complianceTopic', $arr );
	}

	public function addTopic(){

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/topic';

		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->langM->loadDB();
		$this->compliance->loadDB();
		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['langs'] = $this->langM->getAllLangs();
		$arr['lang_tab'] = $lang;
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		// lms_ptq
		$comp_id = $this->input->post('comp_id');
		$arr['comp_id'] = $this->input->post('comp_id');
		$ctop_id = $this->input->post('ctop_id[]');
		$TopicNameNo = $this->input->post('TopicNameNo[]');
		$title_name_th = $this->input->post('title_name_th[]');
		$title_name_en = $this->input->post('title_name_en[]');
		$explanation_begins_th = $this->input->post('explanation_begins_th[]');
		$explanation_begins_en = $this->input->post('explanation_begins_en[]');
		$end_quote_th = $this->input->post('end_quote_th[]');
		$end_quote_en = $this->input->post('end_quote_en[]');
		$type_media = $this->input->post('type_media[]');
		$media_original = $this->input->post('media_original[]');
		$media = $this->input->post('media[]');
        $preNo = $this->input->post('preNo[]');
        $preckeQ_th = $this->input->post('preckeQ_th[]');
        $preckeA1_th = $this->input->post('preckeA1_th[]');
        $preckeA2_th = $this->input->post('preckeA2_th[]');
        $preckeQ_en = $this->input->post('preckeQ_en[]');
        $preckeA1_en = $this->input->post('preckeA1_en[]');
        $preckeA2_en = $this->input->post('preckeA2_en[]');
        $suggestionth = $this->input->post('suggestionth[]');
        $suggestionen = $this->input->post('suggestionen[]');
        $ques_id = $this->input->post('ques_id[]');
        $ans = $this->input->post('preA[]');

        $count_size = $this->input->post('preNo');
        //print_r($title_name_th);
		$id_count = 0;
        foreach($title_name_th as $key=>$value) {
        	if($value!=""&&$title_name_en[$key]!=""){
		        $data_topic = array(
		            'comp_id'	=> $comp_id,
		            'title_name_th'	=> $value,
		            'title_name_en'	=> $title_name_en[$key],
		            'explanation_begins_th'	=> $explanation_begins_th[$key],
		            'explanation_begins_en'	=> $explanation_begins_en[$key],
		            'end_quote_th'	=> $end_quote_th[$key],
		            'end_quote_en'	=> $end_quote_en[$key],
		            'time_create'	=> date('Y-m-d H:i')
		        );
		        if($ctop_id[$key]!="0"){
			    	$id = $this->compliance->updateComplianceTOP($data_topic,$ctop_id[$key]);
			    	$id = $ctop_id[$key];
        		}else{
			    	$id = $this->compliance->insertComplianceTOP($data_topic);
        		}
        		$id_count++;
        	}
		}
		$countQuestion = $this->compliance->rechkCountQuestion($comp_id);
		//echo $countQuestion;
		if($countQuestion>0){
			redirect(base_url().'compliance/topic/'.$comp_id);
		}else{
			redirect(base_url().'compliance/question/'.$comp_id);
		}
		
	}

	public function question($id){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/question';

		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->langM->loadDB();
		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['langs'] = $this->langM->getAllLangs();
		$arr['lang_tab'] = $lang;
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		//cc = category choice
		$arr['comp_id'] = $id;
		//cc = category choice
		$arr['topic_head'] = $this->compliance->selectTopic($id);
		$arr['topic_select'] = $this->compliance->rechktopic_incompliance($id);
		//cn = category name
		$arr['topicdetail'] = $this->compliance->selectTopicDetail($id);

		$this->load->view('frontend/complianceQuestion', $arr );
	}

	public function addQuestion(){

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/topic';

		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->langM->loadDB();
		$this->compliance->loadDB();
		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['langs'] = $this->langM->getAllLangs();
		$arr['lang_tab'] = $lang;
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		// lms_ptq
		$comp_id = $this->input->post('comp_id');
		$arr['comp_id'] = $this->input->post('comp_id');
		$ctop_id = $this->input->post('ctop_id[]');
		$detailid = $this->input->post('detailid[]');
		$type_media = $this->input->post('type_media[]');
		$media_original = $this->input->post('media_original[]');
		$numeral = $this->input->post('numeral[]');
		$media = $this->input->post('media[]');
		$preckeQ_th = $this->input->post('preckeQ_th[]');
		$preckeQ_en = $this->input->post('preckeQ_en[]');
		$preckeA1_th = $this->input->post('preckeA1_th[]');
		$preckeA1_en = $this->input->post('preckeA1_en[]');
		$image_choice_a = $this->input->post('image_choice_a[]');
		$preckeA2_th = $this->input->post('preckeA2_th[]');
		$preckeA2_en = $this->input->post('preckeA2_en[]');
		$image_choice_b = $this->input->post('image_choice_b[]');
		$image_choice_a_original = $this->input->post('image_choice_a_original[]');
		$image_choice_b_original = $this->input->post('image_choice_b_original[]');

		$preA = $this->input->post('preA[]');
		$suggestionth = $this->input->post('suggestionth[]');
		$suggestionen = $this->input->post('suggestionen[]');
        //print_r($title_name_th);
		$id_count = 0;
        foreach($preckeQ_th as $key=>$value) {
        	if($value!=""&&$preckeQ_en[$key]!=""){
			    					$image_file = "";
        							if($_FILES["media"]["name"][$key]!=""){
										$image_file = $_FILES["media"]["name"][$key];
								        $_FILES['uploadedimage']['name'] = $_FILES["media"]["name"][$key];
								        $_FILES['uploadedimage']['type'] = $_FILES["media"]['type'][$key];
								        $_FILES['uploadedimage']['tmp_name'] = $_FILES["media"]['tmp_name'][$key];
								        $_FILES['uploadedimage']['error'] = $_FILES["media"]['error'][$key];
								        $_FILES['uploadedimage']['size'] = $_FILES["media"]['size'][$key];
										$document_config['upload_path'] = './uploads/media/';
										$document_config['allowed_types'] = '*';		
										$document_config['overwrite'] = TRUE;
										$document_config['file_name'] = $image_file;

										
										$this->load->library('upload');
										$this->upload->initialize($document_config);
										   
										// process excel upload
										$this->upload->do_upload('uploadedimage');
										$document_data = $this->upload->data();
			    					}else{
			    						$image_file = $media_original[$key];
			    					}

			    					$image_file_a = "";
        							if($_FILES["image_choice_a"]["name"][$key]!=""){
										$image_file_a = $_FILES["image_choice_a"]["name"][$key];
								        $_FILES['uploadedimage']['name'] = $_FILES["image_choice_a"]["name"][$key];
								        $_FILES['uploadedimage']['type'] = $_FILES["image_choice_a"]['type'][$key];
								        $_FILES['uploadedimage']['tmp_name'] = $_FILES["image_choice_a"]['tmp_name'][$key];
								        $_FILES['uploadedimage']['error'] = $_FILES["image_choice_a"]['error'][$key];
								        $_FILES['uploadedimage']['size'] = $_FILES["image_choice_a"]['size'][$key];
										$document_config['upload_path'] = './uploads/image/';
										$document_config['allowed_types'] = '*';		
										$document_config['overwrite'] = TRUE;
										$document_config['file_name'] = $image_file_a;

										
										$this->load->library('upload');
										$this->upload->initialize($document_config);
										   
										// process excel upload
										$this->upload->do_upload('uploadedimage');
										$document_data = $this->upload->data();
			    					}else{
			    						$image_file_a = $image_choice_a_original[$key];
			    					}
			    					
			    					$image_file_b = "";
        							if($_FILES["image_choice_b"]["name"][$key]!=""){
										$image_file_b = $_FILES["image_choice_b"]["name"][$key];
								        $_FILES['uploadedimage']['name'] = $_FILES["image_choice_b"]["name"][$key];
								        $_FILES['uploadedimage']['type'] = $_FILES["image_choice_b"]['type'][$key];
								        $_FILES['uploadedimage']['tmp_name'] = $_FILES["image_choice_b"]['tmp_name'][$key];
								        $_FILES['uploadedimage']['error'] = $_FILES["image_choice_b"]['error'][$key];
								        $_FILES['uploadedimage']['size'] = $_FILES["image_choice_b"]['size'][$key];
										$document_config['upload_path'] = './uploads/image/';
										$document_config['allowed_types'] = '*';		
										$document_config['overwrite'] = TRUE;
										$document_config['file_name'] = $image_file_b;

										
										$this->load->library('upload');
										$this->upload->initialize($document_config);
										   
										// process excel upload
										$this->upload->do_upload('uploadedimage');
										$document_data = $this->upload->data();
			    					}else{
			    						$image_file_b = $image_choice_b_original[$key];
			    					}
			   	$answer = "choice_a";
			    if($preA[$key]!="1"){
			   		$answer = "choice_b";
			    }
		        $data_topic = array(
		            'ctop_id'	=> $ctop_id[$key],
		            'type_media'	=> $type_media[$key],
		            'numeral'	=> $numeral[$key],
		            'media'	=> $image_file,
		            'question_th'	=> $preckeQ_th[$key],
		            'choice_a_th'	=> $preckeA1_th[$key],
		            'choice_b_th'	=> $preckeA2_th[$key],
		            'question_en'	=> $preckeQ_en[$key],
		            'choice_a_en'	=> $preckeA1_en[$key],
		            'choice_b_en'	=> $preckeA2_en[$key],
		            'suggestion_th'	=> $suggestionth[$key],
		            'suggestion_en'	=> $suggestionen[$key],
		            'correct_answer'	=> $answer,
		            'image_choice_a'	=> $image_file_a,
		            'image_choice_b'	=> $image_file_b,
		            'time_create'	=> date('Y-m-d H:i')
		        );
		        if($detailid[$key]!="0"){
			    	$id = $this->compliance->updateComplianceQUES($data_topic,$detailid[$key]);
			    	$id = $ctop_id[$key];
        		}else{
			    	$id = $this->compliance->insertComplianceQUES($data_topic);
        		}
        		$id_count++;
        	}
		}
		redirect(base_url().'compliance/question/'.$comp_id);
		
	}

	public function remove_detail($id,$ques_id){
		$arr['page'] = 'compliance/topic';

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->compliance->loadDB();
			$this->compliance->removeFromQUE_DE($ques_id);

			redirect(base_url().'compliance/question/'.$id);
	}

	public function remove_head($id,$top_id){
		$arr['page'] = 'compliance/topic';

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->compliance->loadDB();
			$this->compliance->removeFromTopic($top_id);

			redirect(base_url().'compliance/topic/'.$id);
	}

	public function select(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/select';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$arr['datas'] = $this->compliance->get_data();
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/complianceSelect', $arr );
	}

	public function send_email(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/send_email';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$arr['datas'] = $this->compliance->get_data_sendmail();
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/complianceMail', $arr );
	}

	public function add_send_email($sm_id=''){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/add_sent_email';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();
		$arr['compliance'] = $this->compliance->getCompliance_select();
		$arr['sendmail'] = $this->compliance->getData_sendmail($sm_id);

		$this->load->view('frontend/complianceMailCreate', $arr );
	}


	public function create_sendmail(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->load->model('Date_model', 'date', FALSE);
			$this->compliance->loadDB();

		$sm_id = $this->input->post('sm_id');
		$sm_subject = $this->input->post('sm_subject');
		$sm_desc = $this->input->post('sm_desc');
		$com_p = $this->input->post('com_p');
		$time_create =date('Y-m-d H:i');
		$time_modified =date('Y-m-d H:i');
		if($sm_id!=""){
			$this->compliance->updateSendmail($sm_id,$sm_subject,$sm_desc,$com_p,$time_create,$time_modified);
			$id = $sm_id;
		}else{
			$id = $this->compliance->insertSendmail($sm_subject,$sm_desc,$com_p,$time_create,$time_modified);
		}
		

		$arr['page'] = 'compliance/sendmail';

			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

			$arr['emp_c'] = $user['emp_c'];
			$arr['role'] = $user['role'];
			$arr['foote'] = $this->foot->getfooter();
				redirect(base_url().'compliance/send_email');
	}

	public function edit($comp_id){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/edit';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

		$arr['lang'] = $lang;

		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$arr['company_level'] = $this->compliance->rechkcompany_level();
		$topic_head = $this->compliance->selectCompliance($comp_id);
		foreach ($topic_head as $key => $value) {
			$arr['id'] = $value['id'];
			$arr['topic_name_th'] = $value['topic_name_th'];
			$arr['topic_name_en'] = $value['topic_name_en'];
			$arr['name_the_executive_th'] = $value['name_the_executive_th'];
			$arr['name_the_executive_en'] = $value['name_the_executive_en'];
			$arr['chkbox_showtopic'] = $value['chkbox_showtopic'];
			$arr['position_the_executive_th'] = $value['position_the_executive_th'];
			$arr['position_the_executive_en'] = $value['position_the_executive_en'];
			$arr['message_from_the_executive_th'] = $value['message_from_the_executive_th'];
			$arr['message_from_the_executive_en'] = $value['message_from_the_executive_en'];
			$arr['recommendation_th'] = $value['recommendation_th'];
			$arr['recommendation_en'] = $value['recommendation_en'];
			$arr['image_the_executive'] = $value['image_the_executive'];
			$arr['org_code'] = $value['org_code'];
			if($value['time_start']=="0000-00-00 00:00:00"){
				$arr['time_start'] = "";
			}else{
				$arr['time_start'] = date('d/m/Y H:i',strtotime($value['time_start']));
			}
			if($value['time_end']=="0000-00-00 00:00:00"){
				$arr['time_end'] = "";
			}else{
				$arr['time_end'] = date('d/m/Y H:i',strtotime($value['time_end']));
			}
		}
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		$this->load->view('frontend/complianceCreate', $arr );
	}
	public function editcompliance(){

		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->compliance->loadDB();

			$comp_id = $this->input->post('comp_id');
			$topic_name_th = $this->input->post('topic_name_th');
			$topic_name_en = $this->input->post('topic_name_en');
			$chkbox_showtopic = $this->input->post('chkbox_showtopic');
			$name_the_executive_th = $this->input->post('name_the_executive_th');
			$name_the_executive_en = $this->input->post('name_the_executive_en');
			$message_from_the_executive_th = $this->input->post('message_from_the_executive_th');
			$message_from_the_executive_en = $this->input->post('message_from_the_executive_en');
			$recommendation_th = $this->input->post('recommendation_th');
			$recommendation_en = $this->input->post('recommendation_en');
			$position_the_executive_th = $this->input->post('position_the_executive_th');
			$position_the_executive_en = $this->input->post('position_the_executive_en');
			$company_level = $this->input->post('company_level');
			$image = $this->input->post('image');
			$this->load->model('Date_model', 'date', FALSE);

			$time_start = $this->input->post('time_start');
			$time_end = $this->input->post('time_end');

			$time_start =$this->date->convertDate( $time_start );
			$time_end =$this->date->convertDate( $time_end );
			if($_FILES["image_the_executive"]["name"]!=""){
				$image_file = str_replace("&","",$_FILES["image_the_executive"]["name"]);
				$image_file = str_replace(" ","_",$image_file);
				$document_config['upload_path'] = './uploads/image/';
				$document_config['allowed_types'] = 'gif|jpg|png';		
				$document_config['overwrite'] = TRUE;
				$document_config['file_name'] = $image_file;

				
				$this->load->library('upload');
				$this->upload->initialize($document_config);
				   
				// process excel upload
				$this->upload->do_upload('image_the_executive');
				$document_data = $this->upload->data();
			}else{
				$image_file = $this->input->post('image_original');
			}
			//echo $image_file;
			$this->compliance->updateComplianceHead($comp_id,$topic_name_th,$topic_name_en,$name_the_executive_th,$name_the_executive_en,$message_from_the_executive_th,$message_from_the_executive_en,$recommendation_th,$recommendation_en,$position_the_executive_th,$position_the_executive_en,$image_file,$lang,$time_start,$time_end,$company_level,$chkbox_showtopic);


			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;
			redirect(base_url().'compliance/select');
	}

	public function delete($id){

			$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
			$this->lang->load($lang,$lang);
			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->compliance->loadDB();
			$this->compliance->removeFromCOMP($id);

			redirect(base_url().'compliance/select');
	}


	public function delete_sendmail($id){

			$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
			$this->lang->load($lang,$lang);
			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);

			$this->user->loadDB();
			$this->foot->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;

			$this->load->model('Compliance_model', 'compliance', TRUE);
			$this->compliance->loadDB();
			$this->compliance->removeFromSendmail($id);

			redirect(base_url().'compliance/send_email');
	}

	public function sendto_email($id){
			$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
			$this->lang->load($lang,$lang);
			$this->load->model('User_model', 'user', TRUE);
			$this->load->model('Footer_model', 'foot', FALSE);
			$this->load->model('Compliance_model', 'compliance', TRUE);

			$this->user->loadDB();
			$this->foot->loadDB();
			$this->compliance->loadDB();

			$user = $this->session->userdata("user");
			in_array($user['role'], array("superadmin","admintis")) ? : redirect(base_url().'dashboard') ;
			$arr_list = $this->compliance->getlist_sendmail($id);
			$sendmail = $this->compliance->getData_sendmail($id);
			foreach ($arr_list as $key) {
				//echo $key;
				$this->db->sendEmail( $key , $sendmail['sm_desc'],$sendmail['sm_subject']);
			}
			redirect(base_url().'compliance/send_email');
	}

	public function activity(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/activity';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['datas'] = $this->compliance->get_data_activity_person($user['emp_c']);
		$arr['role'] = $user['role'];
		$arr['org1'] = $user['org1'];
		$arr['foote'] = $this->foot->getfooter();
		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/complianceActivity', $arr );
	}

	public function activity_detail($id){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/activity_detail';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->compliance->loadDB();
		$this->foot->loadDB();

		$arr['datas'] = $this->compliance->get_data_activity();
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();
		$arr['datas_user'] = $this->compliance->get_data_activity_person($user['emp_c']);
		$arr['finish_msg'] = $this->compliance->get_data_finish_msg();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		//print_r($arr['datas_user']);
		foreach ($arr['datas_user'] as $data) {
			$arr['status'] = $data->status;
		}
		$topic_head = $this->compliance->selectCompliance($id);
		foreach ($topic_head as $key => $value) {
			$arr['id'] = $value['id'];
			$arr['topic_name_th'] = $value['topic_name_th'];
			$arr['topic_name_en'] = $value['topic_name_en'];
			$arr['name_the_executive_th'] = $value['name_the_executive_th'];
			$arr['name_the_executive_en'] = $value['name_the_executive_en'];
			$arr['position_the_executive_th'] = $value['position_the_executive_th'];
			$arr['position_the_executive_en'] = $value['position_the_executive_en'];
			$arr['message_from_the_executive_th'] = $value['message_from_the_executive_th'];
			$arr['message_from_the_executive_en'] = $value['message_from_the_executive_en'];
			$arr['recommendation_th'] = $value['recommendation_th'];
			$arr['recommendation_en'] = $value['recommendation_en'];
			$arr['image_the_executive'] = $value['image_the_executive'];
			$arr['chkbox_showtopic'] = $value['chkbox_showtopic'];
			if($value['time_start']=="0000-00-00 00:00:00"){
				$arr['time_start'] = "";
			}else{
				$arr['time_start'] = date('d/m/Y H:i',strtotime($value['time_start']));
			}
			if($value['time_end']=="0000-00-00 00:00:00"){
				$arr['time_end'] = "";
			}else{
				$arr['time_end'] = date('d/m/Y H:i',strtotime($value['time_end']));
			}
		}
		$com_p_val = $this->input->post('com_p');
		$ques_id_val = $this->input->post('ques_id');
		$ctop_id_val = $this->input->post('ctop_id');
		if($com_p_val==""){
			$com_p_val = $arr['id'];
		}
		$arr['requestStartActivity'] = $this->requestStartActivity( $arr['emp_c'] , $com_p_val , $arr['chkbox_showtopic']);
		$ques_id = "";
		$ctop_id = "";
		//echo "ctop_id_val:".$arr['requestStartActivity']['ctop_id']."&ques_id_val:".$arr['requestStartActivity']['ques_id'];
			$ques_id_val = $arr['requestStartActivity']['ques_id'];
			$ctop_id_val = $arr['requestStartActivity']['ctop_id'];
		$arr['requestQuestion'] = $this->requestQuestion( $ques_id_val , $ctop_id_val , $com_p_val , $arr['emp_c']);
		//print_r($arr['requestQuestion']);
		//topic_head
		$arr['topic_head'] = $this->compliance->selectTopic($id);
		//topic_detail
		$arr['topicdetail'] = $this->compliance->selectTopicDetail($id);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		$this->load->view('frontend/complianceActivityDetail', $arr );
	}

	public function demo($id){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/demo';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->compliance->loadDB();
		$this->foot->loadDB();

		$arr['datas'] = $this->compliance->get_data_activity();
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();
		//$arr['datas_user'] = $this->compliance->get_data_activity_person($user['emp_c']);
		$arr['finish_msg'] = $this->compliance->get_data_finish_msg();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		//print_r($arr['datas_user']);
		/*foreach ($arr['datas_user'] as $data) {
			$arr['status'] = $data->status;
		}*/
		$arr['status'] = "3";
		$topic_head = $this->compliance->selectCompliance($id);
		foreach ($topic_head as $key => $value) {
			$arr['id'] = $value['id'];
			$arr['topic_name_th'] = $value['topic_name_th'];
			$arr['topic_name_en'] = $value['topic_name_en'];
			$arr['name_the_executive_th'] = $value['name_the_executive_th'];
			$arr['name_the_executive_en'] = $value['name_the_executive_en'];
			$arr['position_the_executive_th'] = $value['position_the_executive_th'];
			$arr['position_the_executive_en'] = $value['position_the_executive_en'];
			$arr['message_from_the_executive_th'] = $value['message_from_the_executive_th'];
			$arr['message_from_the_executive_en'] = $value['message_from_the_executive_en'];
			$arr['recommendation_th'] = $value['recommendation_th'];
			$arr['recommendation_en'] = $value['recommendation_en'];
			$arr['image_the_executive'] = $value['image_the_executive'];
			$arr['chkbox_showtopic'] = $value['chkbox_showtopic'];
			if($value['time_start']=="0000-00-00 00:00:00"){
				$arr['time_start'] = "";
			}else{
				$arr['time_start'] = date('d/m/Y H:i',strtotime($value['time_start']));
			}
			if($value['time_end']=="0000-00-00 00:00:00"){
				$arr['time_end'] = "";
			}else{
				$arr['time_end'] = date('d/m/Y H:i',strtotime($value['time_end']));
			}
		}

		//topic_head
		$arr['topic_head'] = $this->compliance->selectTopic($id);
		//topic_detail
		$arr['topicdetail'] = $this->compliance->selectTopicDetail($id);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();

		$this->load->view('frontend/complianceDemo', $arr );
	}

	public function requestStartActivity( $emp_c , $com_p , $chkbox_showtopic){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->getDataActivity($emp_c , $com_p , $chkbox_showtopic);
		return $data_resend;
	}

	public function requestStartActivityDemo(){

          $emp_c = $this->input->post('emp_c');
          $com_p = $this->input->post('com_p');
          $chkbox_showtopic = $this->input->post('chkbox_showtopic');
          $arr_finish = $this->input->post('arr_finish');
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->getDataActivityDemo($emp_c , $com_p , $chkbox_showtopic , $arr_finish);
		$data_resend['arr_finish'] = $arr_finish;
		echo json_encode($data_resend);
	}

	public function requestQuestion( $ques_id , $ctop_id , $com_p , $emp_c){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->getDataQuestion($ques_id , $ctop_id , $com_p , $emp_c);
		return $data_resend;
	}


	public function requestQuestionjson( $ques_id , $ctop_id , $com_p , $emp_c){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->getDataQuestion($ques_id , $ctop_id , $com_p , $emp_c);
		echo json_encode($data_resend);
	}

	public function requestQuestionDemo(){

          $ques_id = $this->input->post('ques_id');
          $ctop_id = $this->input->post('ctop_id');
          $com_p = $this->input->post('com_p');
          $emp_c = $this->input->post('emp_c');
          $arr = $this->input->post('arr');
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->getDataQuestionDemo($ques_id , $ctop_id , $com_p , $emp_c ,$arr);
		echo json_encode($data_resend);
	}

	public function answerQuestion( $emp_c , $ques_id , $ctop_id , $type_answer ,$correct_answer){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->rechkDataAnswer($emp_c , $ques_id , $ctop_id , $type_answer,$correct_answer);
		echo json_encode($data_resend);
	}

	public function answerQuestionDemo( $emp_c , $ques_id , $ctop_id , $type_answer){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->rechkDataAnswerDemo($emp_c , $ques_id , $ctop_id , $type_answer);
		echo json_encode($data_resend);
	}

	public function queryreommendation( $ques_id , $ctop_id ){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->chkreommendation($ques_id , $ctop_id);
		echo json_encode($data_resend);
	}


	public function selectComplianceObject( $comp_id,$lang ){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->chkdataCompliance( $comp_id,$lang );
		echo $data_resend;
	}

	public function selectComplianceObjectStatus( $comp_id ,$lang){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->chkdataComplianceStatus( $comp_id ,$lang);
		return $data_resend;
	}

	public function countReportStaff( $comp_id,$lang){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->countReportStaff($comp_id,$lang);
		echo json_encode($data_resend);
	}

	public function countReportStatus( $comp_id,$lang){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$data_resend = $this->compliance->countReportStaff($comp_id,$lang);
		return $data_resend;
	}


	public function finish(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/finish';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$arr['emp_c'] = $user['emp_c'];
		$arr['finish_data'] = $this->compliance->getFinishMSG();
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/finish', $arr );
	}
	public function reportstaff(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/reportstaff';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$arr['emp_c'] = $user['emp_c'];
		$arr['datasselected'] = $this->compliance->getCompliance();
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/reportstaff', $arr );
	}

	public function reportquestion(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/reportquestion';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$arr['emp_c'] = $user['emp_c'];
		$arr['datasselected'] = $this->compliance->getCompliance();
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/reportquestion', $arr );
	}

	public function reportbystatus(){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);

		$arr['page'] = 'compliance/reportbystatus';

		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Footer_model', 'foot', FALSE);

		$this->user->loadDB();
		$this->foot->loadDB();

		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$com_p_select = $this->input->post('comp_id');
			$arr['selectComplianceObjectStatus'] = "";
			$arr['countReportStaff'] = "";
			$arr['fetch_detail_status'] = "";
		if($com_p_select!=""){
			$arr['selectComplianceObjectStatus'] = $this->selectComplianceObjectStatus($com_p_select,$lang);
			$arr['countReportStaff'] = $this->countReportStatus($com_p_select,$lang);
			$arr['fetch_detail_status'] = $this->fetch_detail_status($com_p_select,$lang);
		}
		$arr['emp_c'] = $user['emp_c'];
		$arr['datasselected'] = $this->compliance->getCompliance();
		$arr['role'] = $user['role'];
		$arr['foote'] = $this->foot->getfooter();

		!$this->user->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");

		$arr['lang'] = $lang;

		$arr['thaimonth'] = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

		$this->load->view('frontend/reportstatus', $arr );
	}

	public function fetch_detail_head( $comp_id , $lang ){
		$this->load->model('Compliance_model', 'compliance', true);
		$this->compliance->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->compliance->fetch_report_staff($comp_id, $lang) : array();
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

	public function fetch_detail_status( $comp_id ,$lang){
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->compliance->fetch_report_status($comp_id, $lang) : array();
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

	public function fetch_detail_head_question( $comp_id ){
		$lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Compliance_model', 'compliance', TRUE);
		$this->compliance->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->compliance->fetch_report_question($comp_id, $lang) : array();
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
	public function editfinish()
	{
		$arr['page'] = 'compliance/finish';
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;


		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$finish_id = $this->input->post('finish_id');
		$title_th = $this->input->post('title_th');
		$title_en = $this->input->post('title_en');
		$message_th = $this->input->post('message_th');
		$message_en = $this->input->post('message_en');

		$data = array(
			'id' => $finish_id,
			'title_th' => $title_th,
			'title_en' => $title_en,
			'message_th' => $message_th,
			'message_en' => $message_en,
			'time_mod' => date('Y-m-d H:i')
		);
		$this->load->model('Compliance_model', 'compliance', TRUE);

		$this->compliance->loadDB();
		$this->compliance->create_finish($data);
		$this->compliance->closeDB();
		redirect(base_url().'compliance/finish/');
	}
}
