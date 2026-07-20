<?php
class Faq_model extends CI_model {
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

  public function saveTitle($data)
  {
    date_default_timezone_set("Asia/Bangkok");
    $time = date('Y-m-d H:i');
    $this->db->set('time_created', $time);
    $this->db->set('time_edit', $time);
    $this->db->insert('lms_faq', $data);
  }

  public function saveFAQ($fquestion,$fanswer,$tit,$lang,$emp_c)
  {
    date_default_timezone_set("Asia/Bangkok");
    $time = date('Y-m-d H:i');
    $data = array(
      'tid' => $tit,
      'question' => $fquestion,
      'answer' => $fanswer,
      'emp_c' => $emp_c,
      'lang' => $lang);
    $this->db->set('time_created', $time);
    $this->db->set('time_edit', $time);
    $this->db->insert('lms_faq_q', $data);
  }

  public function deleteTitle($ft)
  {
    $this->db->where('id', $ft);
    $this->db->delete('lms_faq');
    $this->db->where('tid', $ft);
    $this->db->delete('lms_faq_q');
  }

  public function deletefQA($ffqa)
  {
    $this->db->where('id', $ffqa);
    $this->db->delete('lms_faq_q');
  }

  public function editTitle($eft,$neft,$lang)
  {
    date_default_timezone_set("Asia/Bangkok");
    $time = date('Y-m-d H:i');
    $data = array('title' => $neft );
    $this->db->where('id', $eft);
    $this->db->where('lang', $lang);
    $this->db->set('time_edit', $time);
    $this->db->update('lms_faq', $data);
  }

  public function editFAQ($efq,$nefq,$nefa)
  {
    date_default_timezone_set("Asia/Bangkok");
    $time = date('Y-m-d H:i');
    $data = array(
      'question' => $nefq,
      'answer' => $nefa
   );
    $this->db->where('id', $efq);
    $this->db->set('time_edit', $time);
    $this->db->update('lms_faq_q', $data);
  }

  public function getTitle($lang)
  {
    $this->db->from('lms_faq');
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getQA($lang)
  {
    $this->db->from('lms_faq_q');
    $this->db->where('lang', $lang);
    $query = $this->db->get();
    return $query->result_array();
  }



}
