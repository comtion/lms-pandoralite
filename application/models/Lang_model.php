<?php
class Lang_model extends CI_Model {

      //  public $title;
      //  public $content;
      //  public $date;

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

        public function getAllLangs()
        {
          $query = $this->db->get('lms_lag');
          return $query->result_array();
        }

}
