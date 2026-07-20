<?php

class SaveEmailLog_model  extends CI_Model{
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

    public function saveLog($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i:s');
      $this->db->set('time_created', $time);
      $this->db->set('time_updated', $time);
      $this->db->insert('lms_log_email', $data);
    }
}


?>