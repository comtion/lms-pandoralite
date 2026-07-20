<?php
class LearningMap_model extends CI_Model {

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
        
        public function getPath() {
          $this->checkFtype('img');
          $this->checkFtype('pdf');
          
          $query = $this->db->get('lms_lmap');
      		return $query->result_array();
        }
        
        public function updatePath($f_type, $data) {
          $this->checkFtype($f_type);
          
          date_default_timezone_set("Asia/Bangkok");
          
          $data['last_update'] = date('Y-m-d H:i');
          $this->db->where('f_type', $f_type);
      		$this->db->update('lms_lmap', $data);
        }
        
        private function checkFtype($f_type) {
          if(!$this->countFtype($f_type)) {
            $this->db->insert('lms_lmap', array('f_type' => $f_type));
          }
        }
        
        private function countFtype($f_type) {
          $this->db->where('f_type', $f_type);
          $query = $this->db->get('lms_lmap');
          return ($query->num_rows() > 0);
        }
}
