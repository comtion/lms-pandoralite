<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends CI_Controller {

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
		
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function detail($lcode)
	{
		$arr['page'] = 'lesson/detail/'.$lcode;
		$this->load->model('User_model', 'login', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		$arr['emp_c'] = $user['emp_c'];
		$arr['role'] = $user['role'];
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$arr['lang'] = $lang;
		//$arr['emp'] = $this->manage->getUser($arr['emp_c'], $lang);
		//print_r($arr['emp_c']);
		$this->load->model('Course_model', 'course', FALSE);
		$this->load->model('Lesson_model', 'lesson', FALSE);
		$this->load->model('Enroll_model', 'enroll', FALSE);
		$this->load->model('Date_model', 'date', FALSE);
		$this->lesson->loadDB();

		$arr['llang'] = $this->lesson->getLessLang($lcode);
		$ccode = $this->lesson->getCcode($lcode);
		$this->enroll->openFirst($ccode);
		$course = $this->course->getCourse($ccode, $arr['llang']);
		$enStatus = $this->enroll->isApproved($arr['emp_c'], $ccode);

		if(!$this->lesson->checkCode($lcode) || !($enStatus || in_array($user['role'], array("superadmin","admintis", "admin", "manager")))){
			$this->lesson->closeDB();
			echo"<script language='JavaScript'>";
			echo"alert('".label('no_lesson')."');";
			echo"window.location='".base_url()."dashboard';";
			echo"</script>";
		}

		$arr['lcode'] = $lcode;
		$arr['ccode'] = $ccode;

		$arr['enrollStatus'] = $enStatus;
		$arr['isCreater'] = $course['emp_c'] == $arr['emp_c'] ? TRUE: FALSE;

		$arr['lesson'] = $this->lesson->getLesson($lcode, $arr['llang']);
		if (isset($arr['lesson'])){
			if ($arr['lesson']['time_start'] == '0000-00-00 00:00:00')
				$arr['lesson']['time_start'] = label('infinity').label('time');
			else{
				$arr['lesson']['time_start'] = $this->date->convertToView($arr['lesson']['time_start']);
			}
			if ($arr['lesson']['time_end'] == '0000-00-00 00:00:00')
				$arr['lesson']['time_end'] = label('infinity').label('time');
			else{
				$arr['lesson']['time_end'] = $this->date->convertToView($arr['lesson']['time_end']);
			}
			$arr['lesson']['time_create'] = $this->date->convertToView($arr['lesson']['time_create']);
			$arr['lesson']['time_mod'] = $this->date->convertToView($arr['lesson']['time_mod']);
		}
		$lessons = $this->lesson->getAllLesson($ccode, $lang);

		$arr['nextHop'] = $this->lesson->getNextLess($lessons, $lcode);
		$arr['backHop'] = $this->lesson->getBackLess($lessons, $lcode);
		
		$learn_status = 'done';
		if ($this->lesson->isScorm($lcode)){
			$this->load->model('Scorm_model', 'scm', FALSE);
			$arr['scorm'] = $this->scm->get($lcode);
			$arr['callPath'] = REAL_PATH.'/scorm/loadScorm/'.$arr['scorm']['scmcode'];
			$learn_status = 'noProgress';
		}
		else {
			$arr['videos'] = $this->lesson->getMedia($lcode);
			if ($arr['videos']){
				$learn_status = 'noProgress';
			}
			$arr['files'] = $this->lesson->getFiles($lcode);
			$arr_count = array();
			//print_r($arr['files']);
			if(isset($arr['files'])&&countArray($arr['files'])>0){
				//print_r($arr['files']);
				foreach ($arr['files'] as $key => $value) {
					$data['count'] = $this->lesson->countrow_fillog($value['id']);
					array_push($arr_count, $data);
				}
			}
			$arr['arr_count'] = $arr_count;
			//print_r($arr['arr_count']);
		}
		$data = array(
			'emp_c' => $arr['emp_c'] ,
			'lessons_id' => $lcode,
			'learn_video' => 0,
			'learn_status' => $learn_status,
			'lang' => $arr['llang']
		);
		$hidden = $this->course->isHidden($ccode);
		if (!$hidden && $enStatus && !in_array($user['role'], array("superadmin","admintis", "admin", "manager"))) {
			$this->lesson->createTC($data) ;
		}

		$this->lesson->closeDB();

		//Record Log activity
		$this->load->model('Log_model', 'lg', FALSE);
		$this->lg->loadDB();
		$this->lg->record('lesson', 'enter to lesson id '.$lcode.'in '.$lang.'.');
		$this->lg->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();

		$this->load->view('frontend/lesson', $arr );

	}

	public function fetch_detail_status($id ,$lang){
		$this->load->model('Lesson_model', 'lesson', false);
		$this->lesson->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->lesson->fetch_report_status($id, $lang) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
			"draw" 				=> $draw,
			"recordsTotal" 		=> $count,
			"recordsFiltered" 	=> $count,
			"data" 				=> $query,
			"error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	private function getYoutubeEmbedUrl($url)
	{
		$shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
		$longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

		if (preg_match($longUrlRegex, $url, $matches)) {
			$youtube_id = $matches[countArray($matches) - 1];
		}

		if (preg_match($shortUrlRegex, $url, $matches)) {
			$youtube_id = $matches[countArray($matches) - 1];
		}
		return '//www.youtube.com/embed/' . $youtube_id ;
	}

	public function create($ccode = ""){
		$arr['page'] = 'lesson/create/'.$ccode;
		$this->load->model('User_model', 'login', TRUE);
		$this->load->model('Course_model', 'course', TRUE);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];
		$user = $this->session->userdata("user");
		in_array($user['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$saveDis = $this->input->post('saveBT');
		$saveRet = $this->input->post('saveRBT');
		$cancel =  $this->input->post('cancelBT');

		$button = array(
			'dis' => $saveDis ,
			'ret' => $saveRet ,
			'can' => $cancel
		);

		$page = $this->input->post('page');
		$lcode = $this->input->post('code');
		$emp_c = $this->input->post('emp_code');
		$ccode = $this->input->post('course_id') == '' ? $ccode : $this->input->post('course_id') ;
		$lesson_name = $this->input->post('lesson_name');
		$lesson_info = $this->input->post('lesson_info');
		$lang = $this->input->post('lang');
		$tOpen = $this->input->post('time_open_'.$lang);
		$tClose = $this->input->post('time_close_'.$lang);
		$hidden = $saveDis != null ? '1' : $this->input->post('visRadio');
		$vid_type = $this->input->post('vid_type');
		$this->load->model('Date_model', 'date', FALSE);

		$data = array(
			'lcode' => $lcode,
			'courses_id' => $ccode,
			'les_name' => $lesson_name,
			'les_info' => $lesson_info,
			'time_start' => $this->date->convertDate( $tOpen ),
			'time_end' => $this->date->convertDate( $tClose ),
			'hidden' => $hidden,
			'lang' => $lang
		);
		$datas = array('normal' => $data);
		$media = array();
		$files = array();

		if (isset($_POST['vid_url']) || isset($_FILES['vid_file']) ){
			if(isset($_POST['vid_url'])){
				foreach ($_POST['vid_url'] as $url) {
					$url = $this->getYoutubeEmbedUrl($url);
					$each = array(
						'lessons_id' => $lcode,
						'type' => 'url',
						'video' => $url
					);
					array_push($media, $each);
				}
			}
			if(isset($_FILES['vid_file'])){
				$fileInfo = $_FILES['vid_file'];
				$num = countArray($fileInfo['name']);
				for ($i=0; $i < $num ; $i++) {
					$imageSourcePath = $fileInfo['tmp_name'][$i];
					$imageTargetPath = ROOT_DIR."uploads/".basename($fileInfo['name'][$i]);
					if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
						$each = array(
							'lessons_id' => $lcode,
							'type' => 'upload',
							'video' => $fileInfo['name'][$i]
						);
						array_push($media, $each);
					}
				}
			}
		}

		if (isset($_FILES['files'])){
			$fileInfo = $_FILES['files'];
			$num = countArray($fileInfo['name']);
			for ($i=0; $i < $num ; $i++) {
				$imageSourcePath = $fileInfo['tmp_name'][$i];
				$imageTargetPath = ROOT_DIR."uploads/".basename($fileInfo['name'][$i]);
				if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
					$each = array(
						'lessons_id' => $lcode,
						'path_file' => $fileInfo['name'][$i]
					);
					array_push($files, $each);
				}
			}
		}

		$datas['media'] = $media;
		$datas['files'] = $files;
		$this->actionButton($button, $datas, $page);
	}

	public function update_log($id='',$emp_c=''){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		$this->load->model('Lesson_model', 'lesson', TRUE);
		$this->lesson->loadDB();
		$this->lesson->update_log_fil($id,$emp_c);
	}

	public function actionButton($setBt, $datas, $page)
	{
		$data = $datas['normal'];
		$media = $datas['media'];
		$files = $datas['files'];
		$this->load->model('Lesson_model', 'lesson', TRUE);
		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Course_model', 'course', TRUE);

		if ($setBt['can'] != null){
			redirect(base_url().'dashboard');
		}
		else if ($setBt['dis'] != null || $setBt['ret'] != null){
			$this->lesson->loadDB();
			if (strpos($page, 'create') || strpos($page, 'edit')){
				$this->lesson->create($data);
				$this->lesson->createFil($files);
				$this->lesson->createMed($media);
			}
			$this->lesson->closeDB();

			redirect(base_url().'course/detail/'.$data['courses_id']);
		}
		else{
			$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
			$this->lang->load($lang,$lang);
			$sess = $this->session->userdata("user");
			$this->langM->loadDB();
			$this->lesson->loadDB();
			$this->user->loadDB();
			$this->course->loadDB();

			$arr['lang'] = $lang;
			$arr['page'] = 'lesson/create';
			$arr['langs'] = $this->langM->getAllLangs();
			$arr['lang_tab'] = $lang;
			$arr['emp_c'] = $sess['emp_c'];
			$arr['course_id'] = $data['courses_id'];
			$arr['role'] = $sess['role'];
			$arr['lcode'] = $this->lesson->getCode();
			$arr['course'] = $this->course->getAllData($data['courses_id']);
			$arr['clang'] = $this->course->getCosLang($arr['course_id']);
			$arr['action'] = REAL_PATH.'/lesson/create/'.$arr['course_id'];


			$this->course->closeDB();
			$this->user->closeDB();
			$this->lesson->closeDB();
			$this->langM->closeDB();

			$arr['course'] = $arr['course'][$arr['clang']];
			$arr['course']['emp_c'] == $arr['emp_c'] ? : redirect(base_url().'dashboard');

			//Record Log activity
			$this->load->model('Log_model', 'lg', FALSE);
			$this->lg->loadDB();
			$this->lg->record('lesson', 'create new lesson in course id '.$arr['course_id'].'.');
			$this->lg->closeDB();

			$this->load->model('Footer_model', 'foot', FALSE);
			$this->foot->loadDB();
			$arr['foote'] = $this->foot->getfooter();
			$this->foot->closeDB();

			$this->load->view('frontend/lessonCreate', $arr);
		}
	}

	public function edit($lcode)
	{
		$this->load->model('Lesson_model', 'lesson', TRUE);
		$this->load->model('Lang_model', 'langM', TRUE);
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Course_model', 'course', TRUE);
		$arr['page'] = 'lesson/edit/'.$lcode;
		$this->load->model('Lesson_model', 'lesson', FALSE);
		$this->lesson->loadDB();
		if (!$this->lesson->checkCode($lcode)){
			$this->lesson->closeDB();
			redirect(base_url().'dashboard');
		}
		$ccode = $this->lesson->getCcode($lcode);

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$sess = $this->session->userdata("user");
		$arr['emp_c'] = $sess['emp_c'];
		$arr['role'] = $sess['role'];
		$this->langM->loadDB();
		$this->lesson->loadDB();
		$this->user->loadDB();
		$this->course->loadDB();

		$arr['lang'] = $lang;
		$arr['langs'] = $this->langM->getAllLangs();
		$arr['lang_tab'] = $lang;
		$arr['lcode'] = $lcode;
		$arr['course_id'] = $ccode;
		$arr['course'] = $this->course->getAllData($ccode);
		$arr['clang'] = $this->course->getCosLang($ccode);
		$llang = (isset($arr['course'][$lang])) ? $lang: $arr['clang'];
		$arr['course'] = $arr['course'][$llang];

		$arr['lessons'] = $this->lesson->getLessons($lcode);

		$this->load->model('Date_model', 'date', FALSE);
		foreach ($arr['lessons'] as $key => $row) {
			$arr['lessons'][$key]['time_start'] = $this->date->convertSqlDate($row['time_start']);
			$arr['lessons'][$key]['time_end'] = $this->date->convertSqlDate($row['time_end']);
		}
		$arr['actions'] = array();
		$arr['editIntro'] = $this->lesson->getEditIntro($lcode);
		foreach ($arr['langs'] as $row) {
			if (in_array($row['lang'], $arr['editIntro'])){
				$arr['actions'][$row['lang']] = REAL_PATH.'/lesson/edited';
			} else {
				$arr['actions'][$row['lang']] = REAL_PATH.'/lesson/create';
			}
		}

		$arr['videos'] = $this->lesson->getMedia($lcode);
		$arr['files'] = $this->lesson->getFiles($lcode);

		$this->course->closeDB();
		$this->user->closeDB();
		$this->lesson->closeDB();
		$this->langM->closeDB();
		(in_array($arr['role'], array("superadmin","admintis", "admin", "manager"))) ? : redirect(base_url().'dashboard');
		//Record Log activity
		$this->load->model('Log_model', 'lg', FALSE);
		$this->lg->loadDB();
		$this->lg->record('lesson', 'enter to edit lesson id '.$lcode.'.');
		$this->lg->closeDB();

		$this->load->model('Footer_model', 'foot', FALSE);
		$this->foot->loadDB();
		$arr['foote'] = $this->foot->getfooter();
		$this->foot->closeDB();

		$this->load->view('frontend/lessonCreate', $arr);
	}

	public function edited()
	{
		$saveDis = $this->input->post('saveBT');
		$saveRet = $this->input->post('saveRBT');
		$cancel =  $this->input->post('cancelBT');

		$lcode = $this->input->post('code');
		$ccode = $this->input->post('course_id');
		$lang = $this->input->post('lang');
		$name = $this->input->post('lesson_name');
		$info = $this->input->post('lesson_info');
		$tOpen = $this->input->post('time_open_'.$lang);
		$tClose = $this->input->post('time_close_'.$lang);
		$hidden = $saveDis != null ? '1' : $this->input->post('visRadio');
		$this->load->model('Date_model', 'date', FALSE);
		$data = array(
			'lcode' => $lcode,
			'courses_id' => $ccode,
			'les_name' => $name,
			'les_info' => $info,
			'hidden' => $hidden,
			'lang' => $lang
		);

		if( !empty($tOpen ) ){
			$data['time_start'] = $this->date->convertDate( $tOpen );
		}
		if (!empty($tClose )){
			$data['time_end'] = $this->date->convertDate( $tClose );
		}
		$media = array();
		$files = array();

		if (isset($_POST['vid_old_up'])){
			foreach ($_POST['vid_old_up'] as $path) {
				$each = array(
					'lessons_id' => $lcode,
					'type' => 'upload',
					'video' => $path
				);
				array_push($media, $each);
			}
		}

		if (isset($_POST['file_old'])){
			foreach ($_POST['file_old'] as $path) {
				$each = array(
					'lessons_id' => $lcode,
					'path_file' => $path
				);
				array_push($files, $each);
			}
		}

		if (isset($_POST['vid_url']) || isset($_FILES['vid_file']) ){
			if(isset($_POST['vid_url'])){
				foreach ($_POST['vid_url'] as $url) {
					$url = $this->getYoutubeEmbedUrl($url);
					$each = array(
						'lessons_id' => $lcode,
						'type' => 'url',
						'video' => $url
					);
					array_push($media, $each);
				}
			}
		}
		if(isset($_FILES['vid_file'])){
			$fileInfo = $_FILES['vid_file'];
			$num = countArray($fileInfo['name']);
			for ($i=0; $i < $num ; $i++) {
				$imageSourcePath = $fileInfo['tmp_name'][$i];
				$imageTargetPath = ROOT_DIR."uploads/".basename($fileInfo['name'][$i]);
				if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
					$each = array(
						'lessons_id' => $lcode,
						'type' => 'upload',
						'video' => $fileInfo['name'][$i]
					);
					array_push($media, $each);
				}
			}
		}

		if (isset($_FILES['files'])){
			$fileInfo = $_FILES['files'];
			$num = countArray($fileInfo['name']);
			for ($i=0; $i < $num ; $i++) {
				$imageSourcePath = $fileInfo['tmp_name'][$i];
				$imageTargetPath = ROOT_DIR."uploads/".basename($fileInfo['name'][$i]);
				if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
					$each = array(
						'lessons_id' => $lcode,
						'path_file' => $fileInfo['name'][$i]
					);
					array_push($files, $each);
				}
			}
		}
		$this->load->model('Lesson_model', 'lesson', TRUE);

		if ($cancel != null){
			redirect(base_url().'dashboard');
		}
		else if ($saveDis != null || $saveRet != null){
			$this->lesson->loadDB();
			$this->lesson->edit($data);
			$this->lesson->editFil($files, $lcode);
			$this->lesson->editMed($media, $lcode);
			$this->lesson->closeDB();
			redirect(base_url().'course/detail/'.$ccode);
		}
	}

	public function delete($lcode)
	{
		$this->load->model('Lesson_model', 'lesson', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		$this->load->model('Scorm_model', 'scm', FALSE);
		$this->lesson->loadDB();
		if (!$this->lesson->checkCode($lcode)){
			$this->lesson->closeDB();
			redirect(base_url().'dashboard');
		}
		$ccode = $this->lesson->getCcode($lcode);
		$sess = $this->session->userdata("user");
		in_array($sess['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
		$this->lesson->loadDB();
		$this->lesson->deleteLesson($lcode);
		$this->lesson->closeDB();

		$this->scm->loadDB();
		$this->scm->deleteScorm($lcode);
		$this->scm->closeDB();

		//Record Log activity
		$this->load->model('Log_model', 'lg', FALSE);
		$this->lg->loadDB();
		$this->lg->record('lesson', 'delete lesson id '.$lcode.'.');
		$this->lg->closeDB();

		redirect(base_url().'course/detail/'.$ccode);
	}

	public function updateTrans($lcode)
	{
		$user = $this->session->userdata("user");
		$data = array(
			'emp_c' => $user['emp_c'] ,
			'lessons_id' => $lcode
		);

		if (in_array($user['role'], array("superadmin","admintis", "admin") ) ) {
			$arr['rs'] = false;
		} else {
			$this->load->model('Lesson_model', 'lesson', FALSE);
			$this->lesson->loadDB();
			$this->lesson->updateTrans($data);
			$this->lesson->closeDB();
			$arr['rs'] = true;
		}
		echo json_encode($arr);
	}
}
