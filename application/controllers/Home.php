<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	private function redirectBack()
	{
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
		redirect($referer);
	}

	public function change_lang($lang){
		$this->config->set_item('language', $lang);
		$this->session->set_userdata('lang', $lang);
		$this->redirectBack();
	}
	public function index()
	{
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, private, max-age=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
		$arr['page'] = 'home';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
    	date_default_timezone_set("Asia/Bangkok");
    	if(!empty($sess) && $sess["firsttime"] == 0){
			redirect(base_url().'dashboard', 'location', 302);
    	}
		$arr['dest'] = isset( $_GET['redirect'] ) ? $_GET['redirect'] : 'dashboard';
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
    	$this->load->model('Coursegroup_model', 'coursegroup', TRUE);
		$this->home->loadDB();
    	$this->coursegroup->loadDB();
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();
		/*$arr['testimonials'] = $this->home->gettestimonials();*/
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['detail_about'] = $this->course->query_data_onupdate('1', 'lms_about','da_id');
		$arr['main_menu'] = $this->home->getmenu();
		/*$arr['sample_course'] = $this->home->get_samplecourse();
				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cos();
					foreach ($arr['sample_course'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['sample_course'][$key_cg]);
						}
					}*/
		/*$arr['thefirstofcourse'] = $this->home->get_thefirstofcourse();

		//$arr['highlight_course'] = $this->func_query->query_result("lms_cos","","","","com_id='3' and cos_public='1' and status = '1'","id DESC");
		$arr['highlight_course'] = $this->func_query->query_result("lms_usp_gp","","","","Is_admin='0' and ug_for='CUSTOMER' and ug_status = '1'","ug_id ASC");
					foreach ($arr['highlight_course'] as $key_cg => $value_cg) {
					    $where = "((lms_cos_detail.date_start <= '".date('Y-m-d H:i')."' and lms_cos_detail.date_end >='".date('Y-m-d H:i')."') OR (lms_cos_detail.date_start = '0000-00-00 00:00:00' and lms_cos_detail.date_end = '0000-00-00 00:00:00'))";
					    $this->db->where($where);
						$this->db->where('lms_cos_detail_ug.ug_id',$value_cg['ug_id']);
						$this->db->where('lms_cos.status','1');
						$this->db->from('lms_cos');
						$this->db->join('lms_cos_detail','lms_cos_detail.cos_id = lms_cos.id');
						$this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
						$this->db->order_by('lms_cos.cos_public','DESC');
						$this->db->limit(3);
						$query_get = $this->db->get();
						$num_get = $query_get->num_rows();
						$arr['highlight_course'][$key_cg]['course'] = array();
						if($num_get>0){
							$fetch_get = $query_get->result_array();
							$arr['highlight_course'][$key_cg]['course'] = $fetch_get;
							foreach ($fetch_get as $key_get => $value_get) {
								if(!in_array($value_get['id'], $arr['rechk_permission_cg'])){
									$arr['highlight_course'][$key_cg]['course'][$key_get]['isHidden'] = '1';
								}else{
									$arr['highlight_course'][$key_cg]['course'][$key_get]['isHidden'] = '0';
								}
							}
						}
					}*/

		/*$arr['event_query'] = $this->func_query->query_result("lms_content","","","","con_IsDelete='0' and con_status='1' and ('".date('Y-m-d H:i')."' between con_datestart and con_dateend)","con_id DESC","4");*/

		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/home', $arr );
	}
	public function backoffice()
	{
		$arr['page'] = 'home';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
    	$this->load->model('Coursegroup_model', 'coursegroup', TRUE);
		$this->home->loadDB();
    	$this->coursegroup->loadDB();
			$this->load->model('Manage_model', 'manage', FALSE);
			$this->manage->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();
		$arr['testimonials'] = $this->home->gettestimonials();
        	$arr['arr_permission'] = $this->manage->chk_permission_page();
		$arr['detail_about'] = $this->course->query_data_onupdate('1', 'lms_about','da_id');
		$arr['main_menu'] = $this->home->getmenu();
		$arr['sample_course'] = $this->home->get_samplecourse();
				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cos();
					foreach ($arr['sample_course'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['sample_course'][$key_cg]);
						}
					}
		$arr['thefirstofcourse'] = $this->home->get_thefirstofcourse();

		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		if(empty($sess)){
			$this->load->view('frontend/home_backend', $arr );
		}else{
			$this->load->view('frontend/home', $arr );
		}
	}
	public function about()
	{
		$arr['page'] = 'home/about';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();


		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/about', $arr );
	}
	public function faq()
	{
		$arr['page'] = 'home/faq';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
			$this->load->model('Manage_model', 'manage', FALSE);
			$this->manage->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();


        	$arr['arr_permission'] = $this->manage->chk_permission_page();
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
		$arr['faq'] = $this->home->getfaq();
		$arr['faq_detail'] = $this->home->getfaq_detail();
		$num=0;
                      foreach ($arr['faq'] as $key) {
                        if($key['lang']==$lang){ 
                          $num++;
                        }
                      }
                      if($num==0){
                         redirect(base_url().'home', 'refresh');
                      }

		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/faq', $arr );
	}
	public function privacy_policy()
	{
		$arr['page'] = 'home/privacy_policy';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();

		if (!empty($sess)) {
			$this->load->model('Manage_model', 'manage', FALSE);
			$this->manage->loadDB();
			$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['main_menu'] = $this->manage->checkmenu();
			$arr['title'] = $this->manage->get_namemenu($arr['page']);
			$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
			$arr['submenu'] = array();
			$arr['submenu_b'] = array();
			foreach ($arr['main_menu'] as $valueMainMenu) {
				$menuId = $valueMainMenu['mu_id'];
				$children = $this->manage->checkmenu_sub($menuId);
				if (countArray($children)) {
					$arr['submenu'][$menuId] = $children;
					foreach ($children as $child) {
						$grandchildren = $this->manage->checkmenu_sub($child['mu_id']);
						if (countArray($grandchildren)) {
							$arr['submenu_b'][$child['mu_id']] = $grandchildren;
						}
					}
				}
			}
			$this->manage->closeDB();
		}


		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/privacy_policy', $arr );
	}
	public function contact_us()
	{
		$arr['page'] = 'home/contact_us';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->home->loadDB();
		$arr['useron'] = $this->home->onlineUser();
		$arr['pic'] = $this->home->getpic();


		$this->home->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();
		$this->load->view('frontend/contact_us', $arr );
	}

	public function send_message()
	{
		$arr['page'] = 'home/send_message';
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		//echo $lang;
   		$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = isset($sess['emp_c']) ? $sess['emp_c'] : "";
		$arr['lang'] = $lang;
		$arr['user'] = $sess;
		$arr['com_admin'] = isset($sess['com_admin']) ? $sess['com_admin'] : "";
		$arr['com_id'] = isset($sess['com_id']) ? $sess['com_id'] : "";
		$this->load->model('Home_model', 'home', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->load->model('User_model', 'login', TRUE);
		$this->login->loadDB();
		$this->home->loadDB();

		$contact_name = $this->input->post('contact_name');
		$contact_tel = $this->input->post('contact_tel');
		$contact_mail = $this->input->post('contact_mail');
		$contact_msg = $this->input->post('contact_msg');
		$contact_about = $this->input->post('contact_about');
		$emp_id = $this->input->post('emp_id');
		$fetch_chk =  $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_emp.emp_c="'.$contact_mail.'" and lms_emp.emp_isDelete="0"');
		$fetch_setmail = $this->func_query->query_row('lms_setting_mail','','','','sm_id="1"');
		$message = "Dear  ISUZU E-Learning System Administrator<br><br><br>Please use below information to unlock account.<br><br>Contact name: ".$contact_name."<br>Contact number: ".$contact_tel."<br>E-Mail: ".$contact_mail."<br>Message: ".$contact_msg;
		$output = array();
		if(countArray($fetch_chk)>0){
			$fetch_formatmail = $this->func_query->query_row('lms_sendmail_form','','','','smf_show="1" and smf_type="19"');
			if($fetch_chk['ug_id']=="1"){
					$fetch_super = $this->func_query->query_result('lms_emp','','','','emp_id in (select lms_usp.emp_id from lms_usp where ug_id="1" and (lms_usp.inactivedate > "' . date('Y-m-d') . '" or lms_usp.inactivedate = "0000-00-00")) and emp_isDelete="0" and emp_id != "'.$fetch_chk['emp_id'].'"','');
					if(countArray($fetch_super)){
						foreach ($fetch_super as $key_super => $value_super) {
							if($value_super['email']!=""){
				              	$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
				              	if($lang!="thai"){
				                 	$date = date('d F Y');
				              	}
                  				$fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_chk['com_id'].'"');
								if(countArray($fetch_formatmail)>0){
				                    $subject_th = $fetch_formatmail['smf_subject_th'];
				                    $subject_en = $fetch_formatmail['smf_subject_en'];
				                    $message_th = $fetch_formatmail['smf_message_th'];
				                    $message_en = $fetch_formatmail['smf_message_en'];
				                    if($subject_th!=""){
				                        $subject_th = str_replace("#fullname",$value_super['fullname_th'],$subject_th);
				                        $subject_th = str_replace("#username",$fetch_chk['useri'],$subject_th);
				                        $subject_th = str_replace("#email",$value_super['email'],$subject_th);
				                        $subject_th = str_replace("#coursename","",$subject_th);
				                        $subject_th = str_replace("#password","",$subject_th);
		                          		$subject_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_th);
				                        $subject_th = str_replace("#date",$date,$subject_th);
				                        $subject_th = str_replace("#time",date('H:i'),$subject_th);
						          		$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
				                    }
				                    if($subject_en!=""){
				                        $subject_en = str_replace("#fullname",$value_super['fullname_en'],$subject_en);
				                        $subject_en = str_replace("#username",$fetch_chk['useri'],$subject_en);
				                        $subject_en = str_replace("#email",$value_super['email'],$subject_en);
				                        $subject_en = str_replace("#coursename","",$subject_en);
				                        $subject_en = str_replace("#password","",$subject_en);
		                          		$subject_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_en);
				                        $subject_en = str_replace("#date",$date,$subject_en);
				                        $subject_en = str_replace("#time",date('H:i'),$subject_en);
						          		$subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
				                    }
				                    if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
				                        $img_val = '<img src="'.base_url().'/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
				                    }else{
				                        $img_val = '';
				                    }
				                    if($message_th!=""){
				                        $message_th = str_replace("#fullname",$value_super['fullname_th'],$message_th);
				                        $message_th = str_replace("#username",$fetch_chk['useri'],$message_th);
				                        $message_th = str_replace("#email",$value_super['email'],$message_th);
				                        $message_th = str_replace("#coursename","",$message_th);
				                        $message_th = str_replace("#password","",$message_th);
		                          		$message_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_th);
				                        $message_th = str_replace("#date",$date,$message_th);
				                        $message_th = str_replace("#time",date('H:i'),$message_th);
		                          		$message_th = str_replace("#image",$img_val,$message_th);
						          		$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
						          		$message_th = str_replace("#contactname",$contact_name,$message_th);
						          		$message_th = str_replace("#contacttel",$contact_tel,$message_th);
						          		$message_th = str_replace("#contacemail",$contact_mail,$message_th);
						          		$message_th = str_replace("#contactmessage",$contact_msg,$message_th);
				                    }
				                   	if($message_en!=""){
				                        $message_en = str_replace("#fullname",$value_super['fullname_en'],$message_en);
				                        $message_en = str_replace("#username",$fetch_chk['useri'],$message_en);
				                        $message_en = str_replace("#email",$value_super['email'],$message_en);
				                        $message_en = str_replace("#coursename","",$message_en);
				                        $message_en = str_replace("#password","",$message_en);
		                          		$message_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_en);
				                        $message_en = str_replace("#date",$date,$message_en);
				                        $message_en = str_replace("#time",date('H:i'),$message_en);
		                          		$message_en = str_replace("#image",$img_val,$message_en);
						          		$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
						          		$message_en = str_replace("#contactname",$contact_name,$message_en);
						          		$message_en = str_replace("#contacttel",$contact_tel,$message_en);
						          		$message_en = str_replace("#contacemail",$contact_mail,$message_en);
						          		$message_en = str_replace("#contactmessage",$contact_msg,$message_en);
				                    }
				                    if($lang == "thai") {
				                        $this->db->sendEmail( $value_super['email'] , $message_th, $subject_th,$fetch_setmail);
				                    }else{
				                        $this->db->sendEmail( $value_super['email'] , $message_en, $subject_en,$fetch_setmail);
				                    }
								}
								//$this->db->sendEmail( $value_super['email'] , $message,'Please unlock account in ISUZU E-Learning System',$fetch_setmail);
							}
						}
						$output['status'] = "2";
					}else{
						$output['status'] = "0";
					}
			}else{
				if(in_array($fetch_chk['ug_id'], array('2','6'))){
					$fetch_super = $this->func_query->query_result('lms_emp','','','','emp_id in (select lms_usp.emp_id from lms_usp where ug_id="1") and emp_isDelete="0"','');
					if(countArray($fetch_super)){
						foreach ($fetch_super as $key_super => $value_super) {
							if($value_super['email']!=""){
			              	$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
			              	if($lang!="thai"){
			                 	$date = date('d F Y');
			              	}
							  $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_chk['com_id'].'"');
								if(countArray($fetch_formatmail)>0){
				                    $subject_th = $fetch_formatmail['smf_subject_th'];
				                    $subject_en = $fetch_formatmail['smf_subject_en'];
				                    $message_th = $fetch_formatmail['smf_message_th'];
				                    $message_en = $fetch_formatmail['smf_message_en'];
				                    if($subject_th!=""){
				                        $subject_th = str_replace("#fullname",$value_super['fullname_th'],$subject_th);
				                        $subject_th = str_replace("#username",$fetch_chk['useri'],$subject_th);
				                        $subject_th = str_replace("#email",$value_super['email'],$subject_th);
				                        $subject_th = str_replace("#coursename","",$subject_th);
				                        $subject_th = str_replace("#password","",$subject_th);
		                          		$subject_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_th);
				                        $subject_th = str_replace("#date",$date,$subject_th);
				                        $subject_th = str_replace("#time",date('H:i'),$subject_th);
						          		$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
				                    }
				                    if($subject_en!=""){
				                        $subject_en = str_replace("#fullname",$value_super['fullname_en'],$subject_en);
				                        $subject_en = str_replace("#username",$fetch_chk['useri'],$subject_en);
				                        $subject_en = str_replace("#email",$value_super['email'],$subject_en);
				                        $subject_en = str_replace("#coursename","",$subject_en);
				                        $subject_en = str_replace("#password","",$subject_en);
		                          		$subject_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_en);
				                        $subject_en = str_replace("#date",$date,$subject_en);
				                        $subject_en = str_replace("#time",date('H:i'),$subject_en);
						          		$subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
				                    }
				                    if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
				                        $img_val = '<img src="'.base_url().'/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
				                    }else{
				                        $img_val = '';
				                    }
				                    if($message_th!=""){
				                        $message_th = str_replace("#fullname",$value_super['fullname_th'],$message_th);
				                        $message_th = str_replace("#username",$fetch_chk['useri'],$message_th);
				                        $message_th = str_replace("#email",$value_super['email'],$message_th);
				                        $message_th = str_replace("#coursename","",$message_th);
				                        $message_th = str_replace("#password","",$message_th);
		                          		$message_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_th);
				                        $message_th = str_replace("#date",$date,$message_th);
				                        $message_th = str_replace("#time",date('H:i'),$message_th);
		                          		$message_th = str_replace("#image",$img_val,$message_th);
						          		$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
						          		$message_th = str_replace("#contactname",$contact_name,$message_th);
						          		$message_th = str_replace("#contacttel",$contact_tel,$message_th);
						          		$message_th = str_replace("#contacemail",$contact_mail,$message_th);
						          		$message_th = str_replace("#contactmessage",$contact_msg,$message_th);
				                    }
				                   	if($message_en!=""){
				                        $message_en = str_replace("#fullname",$value_super['fullname_en'],$message_en);
				                        $message_en = str_replace("#username",$fetch_chk['useri'],$message_en);
				                        $message_en = str_replace("#email",$value_super['email'],$message_en);
				                        $message_en = str_replace("#coursename","",$message_en);
				                        $message_en = str_replace("#password","",$message_en);
		                          		$message_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_en);
				                        $message_en = str_replace("#date",$date,$message_en);
				                        $message_en = str_replace("#time",date('H:i'),$message_en);
		                          		$message_en = str_replace("#image",$img_val,$message_en);
						          		$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
						          		$message_en = str_replace("#contactname",$contact_name,$message_en);
						          		$message_en = str_replace("#contacttel",$contact_tel,$message_en);
						          		$message_en = str_replace("#contacemail",$contact_mail,$message_en);
						          		$message_en = str_replace("#contactmessage",$contact_msg,$message_en);
				                    }
				                    if($lang == "thai") {
				                        $this->db->sendEmail( $value_super['email'] , $message_th, $subject_th,$fetch_setmail);
				                    }else{
				                        $this->db->sendEmail( $value_super['email'] , $message_en, $subject_en,$fetch_setmail);
				                    }
								}
								/*$this->db->sendEmail( $value_super['email'] , $message,'Please unlock account in ISUZU E-Learning System',$fetch_setmail);*/
							}
						}
						$output['status'] = "2";
					}else{
						$output['status'] = "0";
					}
				}else{
					$fetch_grcom = $this->func_query->query_result('lms_emp','','','','emp_id in (select lms_usp.emp_id from lms_usp where ug_id in (select lms_usp_gp.ug_id from lms_usp_gp where ug_name_en="Gr.Com Admin")) and com_id="'.$fetch_chk['com_id'].'" and emp_isDelete="0"','');
					if(countArray($fetch_grcom)>0){
						foreach ($fetch_grcom as $key_grcom => $value_grcom) {
							if($value_grcom['email']!=""){
			              	$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
			              	if($lang!="thai"){
			                 	$date = date('d F Y');
			              	}
                  				$fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_chk['com_id'].'"');
								if(countArray($fetch_formatmail)>0){
				                    $subject_th = $fetch_formatmail['smf_subject_th'];
				                    $subject_en = $fetch_formatmail['smf_subject_en'];
				                    $message_th = $fetch_formatmail['smf_message_th'];
				                    $message_en = $fetch_formatmail['smf_message_en'];
				                    if($subject_th!=""){
				                        $subject_th = str_replace("#fullname",$value_grcom['fullname_th'],$subject_th);
				                        $subject_th = str_replace("#username",$fetch_chk['useri'],$subject_th);
				                        $subject_th = str_replace("#email",$value_grcom['email'],$subject_th);
				                        $subject_th = str_replace("#coursename","",$subject_th);
				                        $subject_th = str_replace("#password","",$subject_th);
		                          		$subject_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_th);
				                        $subject_th = str_replace("#date",$date,$subject_th);
				                        $subject_th = str_replace("#time",date('H:i'),$subject_th);
						          		$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
				                    }
				                    if($subject_en!=""){
				                        $subject_en = str_replace("#fullname",$value_grcom['fullname_en'],$subject_en);
				                        $subject_en = str_replace("#username",$fetch_chk['useri'],$subject_en);
				                        $subject_en = str_replace("#email",$value_grcom['email'],$subject_en);
				                        $subject_en = str_replace("#coursename","",$subject_en);
				                        $subject_en = str_replace("#password","",$subject_en);
		                          		$subject_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_en);
				                        $subject_en = str_replace("#date",$date,$subject_en);
				                        $subject_en = str_replace("#time",date('H:i'),$subject_en);
						          		$subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
				                    }
				                    if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
				                        $img_val = '<img src="'.base_url().'/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
				                    }else{
				                        $img_val = '';
				                    }
				                    if($message_th!=""){
				                        $message_th = str_replace("#fullname",$value_grcom['fullname_th'],$message_th);
				                        $message_th = str_replace("#username",$fetch_chk['useri'],$message_th);
				                        $message_th = str_replace("#email",$value_grcom['email'],$message_th);
				                        $message_th = str_replace("#coursename","",$message_th);
				                        $message_th = str_replace("#password","",$message_th);
		                          		$message_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_th);
				                        $message_th = str_replace("#date",$date,$message_th);
				                        $message_th = str_replace("#time",date('H:i'),$message_th);
		                          		$message_th = str_replace("#image",$img_val,$message_th);
						          		$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
						          		$message_th = str_replace("#contactname",$contact_name,$message_th);
						          		$message_th = str_replace("#contacttel",$contact_tel,$message_th);
						          		$message_th = str_replace("#contacemail",$contact_mail,$message_th);
						          		$message_th = str_replace("#contactmessage",$contact_msg,$message_th);
				                    }
				                   	if($message_en!=""){
				                        $message_en = str_replace("#fullname",$value_grcom['fullname_en'],$message_en);
				                        $message_en = str_replace("#username",$fetch_chk['useri'],$message_en);
				                        $message_en = str_replace("#email",$value_grcom['email'],$message_en);
				                        $message_en = str_replace("#coursename","",$message_en);
				                        $message_en = str_replace("#password","",$message_en);
		                          		$message_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_en);
				                        $message_en = str_replace("#date",$date,$message_en);
				                        $message_en = str_replace("#time",date('H:i'),$message_en);
		                          		$message_en = str_replace("#image",$img_val,$message_en);
						          		$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
						          		$message_en = str_replace("#contactname",$contact_name,$message_en);
						          		$message_en = str_replace("#contacttel",$contact_tel,$message_en);
						          		$message_en = str_replace("#contacemail",$contact_mail,$message_en);
						          		$message_en = str_replace("#contactmessage",$contact_msg,$message_en);
				                    }
				                    if($lang == "thai") {
				                        $this->db->sendEmail( $value_grcom['email'] , $message_th, $subject_th,$fetch_setmail);
				                    }else{
				                        $this->db->sendEmail( $value_grcom['email'] , $message_en, $subject_en,$fetch_setmail);
				                    }
								}
								//$this->db->sendEmail( $value_grcom['email'] , $message,'Please unlock account in ISUZU E-Learning System',$fetch_setmail);
							}
						}
						$output['status'] = "2";
					}else{
						$fetch_super = $this->func_query->query_result('lms_emp','','','','emp_id in (select lms_usp.emp_id from lms_usp where ug_id="1") and emp_isDelete="0"','');
						if(countArray($fetch_super)){
							foreach ($fetch_super as $key_super => $value_super) {
								if($value_super['email']!=""){
				              	$date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
				              	if($lang!="thai"){
				                 	$date = date('d F Y');
				              	}
                  					$fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_chk['com_id'].'"');
									if(countArray($fetch_formatmail)>0){
					                    $subject_th = $fetch_formatmail['smf_subject_th'];
					                    $subject_en = $fetch_formatmail['smf_subject_en'];
					                    $message_th = $fetch_formatmail['smf_message_th'];
					                    $message_en = $fetch_formatmail['smf_message_en'];
					                    if($subject_th!=""){
					                        $subject_th = str_replace("#fullname",$value_super['fullname_th'],$subject_th);
					                        $subject_th = str_replace("#username",$fetch_chk['useri'],$subject_th);
					                        $subject_th = str_replace("#email",$value_super['email'],$subject_th);
					                        $subject_th = str_replace("#coursename","",$subject_th);
					                        $subject_th = str_replace("#password","",$subject_th);
			                          		$subject_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_th);
					                        $subject_th = str_replace("#date",$date,$subject_th);
					                        $subject_th = str_replace("#time",date('H:i'),$subject_th);
							          		$subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
					                    }
					                    if($subject_en!=""){
					                        $subject_en = str_replace("#fullname",$value_super['fullname_en'],$subject_en);
					                        $subject_en = str_replace("#username",$fetch_chk['useri'],$subject_en);
					                        $subject_en = str_replace("#email",$value_super['email'],$subject_en);
					                        $subject_en = str_replace("#coursename","",$subject_en);
					                        $subject_en = str_replace("#password","",$subject_en);
			                          		$subject_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$subject_en);
					                        $subject_en = str_replace("#date",$date,$subject_en);
					                        $subject_en = str_replace("#time",date('H:i'),$subject_en);
							          		$subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
					                    }
					                    if(isset($fetch_formatmail['smf_importimage'])&&$fetch_formatmail['smf_importimage']!=""){
					                        $img_val = '<img src="'.base_url().'/uploads/formatmail_img/'.$fetch_formatmail['smf_importimage'].'" style="max-width:800px">';
					                    }else{
					                        $img_val = '';
					                    }
					                    if($message_th!=""){
					                        $message_th = str_replace("#fullname",$value_super['fullname_th'],$message_th);
					                        $message_th = str_replace("#username",$fetch_chk['useri'],$message_th);
					                        $message_th = str_replace("#email",$value_super['email'],$message_th);
					                        $message_th = str_replace("#coursename","",$message_th);
					                        $message_th = str_replace("#password","",$message_th);
			                          		$message_th = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_th);
					                        $message_th = str_replace("#date",$date,$message_th);
					                        $message_th = str_replace("#time",date('H:i'),$message_th);
			                          		$message_th = str_replace("#image",$img_val,$message_th);
							          		$message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
							          		$message_th = str_replace("#contactname",$contact_name,$message_th);
							          		$message_th = str_replace("#contacttel",$contact_tel,$message_th);
							          		$message_th = str_replace("#contacemail",$contact_mail,$message_th);
							          		$message_th = str_replace("#contactmessage",$contact_msg,$message_th);
					                    }
					                   	if($message_en!=""){
					                        $message_en = str_replace("#fullname",$value_super['fullname_en'],$message_en);
					                        $message_en = str_replace("#username",$fetch_chk['useri'],$message_en);
					                        $message_en = str_replace("#email",$value_super['email'],$message_en);
					                        $message_en = str_replace("#coursename","",$message_en);
					                        $message_en = str_replace("#password","",$message_en);
			                          		$message_en = str_replace("#link_frontend",base_url().'dashboard/unlockAcc',$message_en);
					                        $message_en = str_replace("#date",$date,$message_en);
					                        $message_en = str_replace("#time",date('H:i'),$message_en);
			                          		$message_en = str_replace("#image",$img_val,$message_en);
							          		$message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
							          		$message_en = str_replace("#contactname",$contact_name,$message_en);
							          		$message_en = str_replace("#contacttel",$contact_tel,$message_en);
							          		$message_en = str_replace("#contacemail",$contact_mail,$message_en);
							          		$message_en = str_replace("#contactmessage",$contact_msg,$message_en);
					                    }
					                    if($lang == "thai") {
					                        $this->db->sendEmail( $value_super['email'] , $message_th, $subject_th,$fetch_setmail);
					                    }else{
					                        $this->db->sendEmail( $value_super['email'] , $message_en, $subject_en,$fetch_setmail);
					                    }
									}
									/*$this->db->sendEmail( $value_super['email'] , $message,'Please unlock account in ISUZU E-Learning System',$fetch_setmail);*/
								}
							}
							$output['status'] = "2";
						}else{
							$output['status'] = "0";
						}
					}
				}
			}
		}else{
			$output['status'] = "11";
		}


		$this->home->closeDB();
		echo json_encode($output);
	}

	public function change( $type ){
		$this->session->set_userdata('lang',$type);
		$this->redirectBack();
	}
}
