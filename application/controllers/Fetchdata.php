<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetchdata extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      $this->load->model('Fetchdata_model', 'fetch', false);
      $this->fetch->loadDB();
      
      if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
        $this->session->set_userdata('lang', $_GET["lang"]);
      }
  }

  public function fetch_public_survey_report(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_publicsurvey_report($_REQUEST['com_id']) : array();
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

  public function fetch_detail_coursegroup(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_coursegroup() : array();
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

  public function fetch_detail_ongoing(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_courseongoing($_REQUEST['com_id'], $_REQUEST['type']) : array();
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

  public function fetch_detail_incoming(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_courseincoming($_REQUEST['com_id'], $_REQUEST['type']) : array();
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

  public function fetch_detail_course(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_course($_REQUEST['com_id']) : array();
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

  public function fetch_public_survey(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_publicsurvey($_REQUEST['com_id']) : array();
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

  public function fetch_public_survey_detail(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_publicsurvey_detail($_REQUEST['sv_id']) : array();
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

  public function fetch_public_survey_detail_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_publicsurvey_detail_view($_REQUEST['sv_id']) : array();
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

  public function fetch_public_survey_listuser(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_publicsurvey_listuser($_REQUEST['sv_id']) : array();
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

  public function fetch_cos_document(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_cos_document($_REQUEST['cos_id']) : array();
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

  public function fetch_cos_document_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_cos_document_view($_REQUEST['cos_id']) : array();
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

  public function fetch_course_detail(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_course_detail($_REQUEST['cos_id']) : array();
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

  public function fetch_videocourse(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_videocourse($_REQUEST['cos_id']) : array();
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
  
  public function fetch_course_enroll(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_enroll_detail($_REQUEST['cos_id']) : array();
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
  
  public function fetch_course_enroll_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_data_enroll_detail_view($_REQUEST['cos_id']) : array();
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

  public function fetch_course_lesson(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_lesson($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_lesson_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_lesson_view($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_quiz(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_quiz($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_quiz_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_quiz_view($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_question(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_question($_REQUEST['quiz']) : array();
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

  public function fetch_course_question_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_question_view($_REQUEST['quiz']) : array();
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

  public function fetch_quiz_question_check(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_quiz_question_check($_REQUEST['ques_id']) : array();
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

  public function fetch_course_survey(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_survey($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_survey_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_survey_view($_REQUEST['cos_id'], $status_user) : array();
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

  public function fetch_course_survey_detail(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_survey_detail($_REQUEST['sv_id']) : array();
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

  public function fetch_course_survey_detail_view(){
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
    $query = isset($user["u_id"]) ? $this->fetch->fetch_course_survey_detail_view($_REQUEST['sv_id']) : array();
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

  public function fetchLogEmail() {
      $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang, $lang);

      $comId = isset($_GET['comId']) ? $_GET['comId'] : '';
      $statusEvent = isset($_GET['statusEvent']) ? $_GET['statusEvent'] : '';
      $dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : '';
      $timeStart = isset($_GET['timeStart']) ? $_GET['timeStart'] : '';
      $dateEnd = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '';
      $timeEnd = isset($_GET['timeEnd']) ? $_GET['timeEnd'] : '';
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->fetch->fetchLogEmail($comId, $statusEvent, $dateStart, $timeStart, $dateEnd, $timeEnd) : array();
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

  public function fetchLogImportUsers() {
      $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
      $user = $this->session->userdata('user');
      $this->lang->load($lang, $lang);

      $comId = isset($_GET['comId']) ? $_GET['comId'] : '';
      $dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : '';
      $timeStart = isset($_GET['timeStart']) ? $_GET['timeStart'] : '';
      $dateEnd = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '';
      $timeEnd = isset($_GET['timeEnd']) ? $_GET['timeEnd'] : '';
      $isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
      $query = isset($user["emp_id"]) ? $this->fetch->fetchLogImportUsers($comId, $dateStart, $timeStart, $dateEnd, $timeEnd) : array();
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
}