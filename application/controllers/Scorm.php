<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scorm extends CI_Controller {

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
  public function create($ccode)
  {
    $arr['page'] = 'scorm/create';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    in_array($arr['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
    $arr['lang'] = $lang;

    $this->load->model('Lang_model', 'langM', TRUE);
    $this->load->model('Course_model', 'course', TRUE);
    $this->load->model('Lesson_model', 'lesson', TRUE);
    $this->langM->loadDB();
    $this->course->loadDB();
    $this->lesson->loadDB();
    $arr['langs'] = $this->langM->getAllLangs();
    $arr['lang_tab'] = $lang;
    $arr['action'] = REAL_PATH.'/scorm/created';
    $arr['ccode'] = $ccode;
    $arr['lcode'] = $this->lesson->getCode();
    $arr['clang'] = $this->course->getCosLang($ccode);
    $arr['course'] = $this->course->getAllData($ccode);
    $this->langM->closeDB();
    $this->course->closeDB();
    $this->lesson->closeDB();

    $arr['course'] = $arr['course'][$arr['clang']];
    $arr['course']['emp_c'] == $arr['emp_c'] ? : redirect(base_url().'dashboard');

    //Record Log activity
    $this->load->model('Log_model', 'lg', FALSE);
    $this->lg->loadDB();
    $this->lg->record('lesson', 'create new lesson in course id '.$ccode.' by SCORM.');
    $this->lg->closeDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();

    $this->foot->closeDB();
    $this->load->view('frontend/scormCreate', $arr );
  }

  public function created()
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    !empty($arr['role']) || in_array($arr['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

    $saveDis = $this->input->post('saveBT');
    $saveRet = $this->input->post('saveRBT');
    $cancel =  $this->input->post('cancelBT');

    if ($this->input->post('cancelBT') != null) {
      redirect(base_url().'dashboard');
    }

    $lang = $this->input->post('lang');
    $lcode = $this->input->post('code');
    $ccode = $this->input->post('course_id');
    $lname = $this->input->post('lesson_name');
    $linfo = $this->input->post('lesson_info');
    $hidden = $saveDis != null ? '1' : $this->input->post('visRadio');
    $tOpen = $this->input->post('time_open');
    $tClose = $this->input->post('time_close');

    $this->load->model('Date_model', 'date', FALSE);

    $data = array(
      'lcode' => $lcode,
      'courses_id' => $ccode,
      'les_name' => $lname,
      'les_info' => $linfo,
      'time_start' => $this->date->convertDate( $tOpen ),
      'time_end' => $this->date->convertDate( $tClose ),
      'hidden' => $hidden,
      'lang' => $lang
    );

    if ($this->input->post('saveRBT') != null || $this->input->post('saveBT') != null){
      $this->load->model('Lesson_model', 'lesson', FALSE);
      $this->lesson->loadDB();
      $this->lesson->create($data);
      $this->lesson->closeDB();

      if ( isset( $_FILES['scorm'] ) ) {
        $this->load->model('Scorm_model', 'scm', FALSE);
        $this->scm->loadDB();
        $scmCode = $this->scm->getCode();
        $newDir = ROOT_DIR."uploads/scorm_".$lcode."_".$scmCode;
        mkdir($newDir);
        $scormFile = $_FILES['scorm'];
        $sourcePath = $scormFile['tmp_name'];
        $targetPath = $newDir."/".basename($scormFile['name']);
        if( audit_move_uploaded_file( $sourcePath,$targetPath ) ){
          $zip = new ZipArchive;
          $openZip = $zip->open($targetPath);
          $zip->extractTo($newDir);
          $zip->close();
          //create new data in db
          $this->scm->createScorm($lcode, $scmCode);
        }
        $this->scm->closeDB();
      }
      redirect(base_url().'course/detail/'.$ccode);
    }
  }

  public function edit($lcode)
  {
    $arr['page'] = 'scorm/edit';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    in_array($arr['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;
    $arr['lang'] = $lang;

    $this->load->model('Scorm_model', 'scm', FALSE);
    $this->load->model('Lesson_model', 'les', FALSE);
    $this->les->loadDB();

    $scmCode = $this->scm->getScmCode($lcode);
    $arr['lcode'] = $lcode;
    $arr['ccode'] = $this->les->getCcode($lcode);
    $arr['lesson'] = $this->les->getAllData($lcode)[$lang];
    $arr['scm'] = $this->scm->getScorm($scmCode);
    $arr['action'] = REAL_PATH.'/scorm/edited';

    $this->load->model('Date_model', 'date', FALSE);
    $arr['lesson']['time_start'] = $this->date->convertSqlDate($arr['lesson']['time_start']);
    $arr['lesson']['time_end'] = $this->date->convertSqlDate($arr['lesson']['time_end']);

    $this->les->closeDB();
    //Record Log activity
    $this->load->model('Log_model', 'lg', FALSE);
    $this->lg->loadDB();
    $this->lg->record('lesson', 'edit lesson id '.$lcode.' by SCORM.');
    $this->lg->closeDB();

    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();

    $this->foot->closeDB();
    $this->load->view('frontend/scormCreate', $arr );
  }

  public function edited()
  {
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    !empty($arr['role']) || in_array($arr['role'], array("superadmin","admintis", "admin", "manager")) ? : redirect(base_url().'dashboard') ;

    $saveDis = $this->input->post('saveBT');
    $saveRet = $this->input->post('saveRBT');
    $cancel =  $this->input->post('cancelBT');

    if ($this->input->post('cancelBT') != null) {
      redirect(base_url().'dashboard');
    }

    $lang = $this->input->post('lang');
    $lcode = $this->input->post('code');
    $ccode = $this->input->post('course_id');
    $lname = $this->input->post('lesson_name');
    $linfo = $this->input->post('lesson_info');
    $hidden = $saveDis != null ? '1' : $this->input->post('visRadio');
    $tOpen = $this->input->post('time_open');
    $tClose = $this->input->post('time_close');

    $this->load->model('Date_model', 'date', FALSE);

    $data = array(
      'lcode' => $lcode,
      'courses_id' => $ccode,
      'les_name' => $lname,
      'les_info' => $linfo,
      'time_start' => $this->date->convertDate( $tOpen ),
      'time_end' => $this->date->convertDate( $tClose ),
      'hidden' => $hidden,
      'lang' => $lang
    );

    if ($this->input->post('saveRBT') != null || $this->input->post('saveBT') != null){
      $this->load->model('Lesson_model', 'lesson', FALSE);
      $this->lesson->loadDB();
      $this->lesson->edit($data);
      $this->lesson->closeDB();

      if ( isset( $_FILES['scorm'] ) ) {
        $this->load->model('Scorm_model', 'scm', FALSE);
        $this->scm->loadDB();
        $newDir = ROOT_DIR."uploads/scorm_".$lcode."_".$scmCode;
        mkdir($newDir);
        $scormFile = $_FILES['scorm'];
        $sourcePath = $scormFile['tmp_name'];
        $targetPath = $newDir."/".basename($scormFile['name']);
        if( audit_move_uploaded_file( $sourcePath,$targetPath ) ){
          $zip = new ZipArchive;
          $openZip = $zip->open($targetPath);
          $zip->extractTo($newDir);
          $zip->close();
        }
        $this->scm->closeDB();
      }
      redirect(base_url().'course/detail/'.$ccode);
    }
  }

  public function loadScorm($scmCode)
  {
    $arr['page'] = 'loadScorm';
    $arr['scmCode'] = $scmCode;
    $user = $this->session->userdata("user");
    $arr['emp_id'] = $user['emp_id'];

    $this->load->model('Scorm_model', 'scm', FALSE);
    $this->scm->loadDB();

    $sessionKey = session_id();
    if(empty($sessionKey)){
      session_start();
    }

    $arr['scolaunchurl'] = $this->scm->getScorm($scmCode);

    $def = array();
    $cmiobj = array();
    $cmiint = array();
    if (!isset($currentorg)) {
      $currentorg = '';
    }

    if ($scoes = $this->scm->getScmVars($scmCode)){
      foreach ($scoes as $sco) {
        $def[$scmCode][$sco['var_name']] = $sco['var_value'];
      }
      if (isset($def[$scmCode]['cmi_core_exit']) && ($def[$scmCode]['cmi_core_exit'] == 'suspend')) {
        $def[$scmCode]['cmi_core_entry'] = 'resume';
      } else {
        $def[$scmCode]['cmi_core_entry'] = '';
      }

      if (isset($def[$scmCode]['cmi_core_lesson_status']) && ($def[$scmCode]['cmi_core_lesson_status'] == 'passed' || $def[$scmCode]['cmi_core_lesson_status'] == 'completed')) {
        $this->scm->updateTC($scmCode,$def[$scmCode]['cmi_core_lesson_status']);
      }

    } else {
      $def[$scmCode] = $this->scm->newScorm($scmCode);
    }
    $this->scm->closeDB();
    $cmistring256 = '^[\\u0000-\\uFFFF]{0,255}$';
    $cmistring4096 = '^[\\u0000-\\uFFFF]{0,4096}$';

    //Call init
    $attr = array($def, $cmiobj, $cmiint, $cmistring256, $cmistring4096, 0, 1, $scmCode, base_url(), $sessionKey, $scmCode, 1, 0, $scmCode, $currentorg, 1,1,3);
    $arr['init'] = $this->scm->initScorm($attr);
    $this->load->view('frontend/loadScorm', $arr );
  }

  public function play($scmCode)
  {
    $arr['page'] = 'loadScorm';
    $arr['callPath'] = REAL_PATH.'/scorm/loadScorm/'.$scmCode;
    $this->load->view('frontend/scormPlayer', $arr );
  }

  public function datamodel()
  {
    $dataSet = $_POST;
    $this->load->model('Scorm_model', 'scm', FALSE);
    $this->scm->loadDB();
    $this->scm->createTransaction($dataSet);
    $this->scm->closeDB();
    $arr['rs'] = true;
    echo json_encode($arr);
  }
}
