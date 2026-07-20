<?php
class Calendar_model extends CI_Model {

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
        
        public function getAdjacentM($date, $btn) {
          if($btn == 'n') {
            $date = date('M Y', strtotime('+1 month', strtotime($date)));
          } elseif($btn == 'p') {
            $date = date('M Y', strtotime('-1 month', strtotime($date)));
          }
          return $date;
        }
        
        public function getAllCos($filter) {
          $this->db->select('ccode, cname, time_open, time_end, coursetype, lang');
          $this->db->from('lms_cos');
          empty($filter) ?: $this->db->where('coursetype', $filter);
          $this->db->where('status', 1);
          $this->db->order_by('time_open', 'ASC');
          $this->db->order_by('time_end', 'ASC');
          $query = $this->db->get();
          return $query->result_array();
        }
        
        public function getMyCos($emp_c, $filter) {
          $this->db->select('lms_cos.ccode, lms_cos.cname, lms_cos.time_open, lms_cos.time_end, lms_cos.coursetype, lang');
          $this->db->from('lms_cos');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->where('lms_ens.emp_c', $emp_c);
          empty($filter) ?: $this->db->where('lms_cos.coursetype', $filter);
          $this->db->where('lms_cos.status', 1);
          $this->db->order_by('lms_ens.time_request', 'DESC');
          $query = $this->db->get();
          return $query->result_array();
        }
        
        public function cc($courses) {
          $cc = array();
          foreach($courses as $key=>$course) {
            $cc[$course['ccode']] = $this->getColor($course['coursetype']);
          }
          return $cc;
        }
        
        public function getColor($coursetype) {
          switch($coursetype) {
            case "Core Programme":
              return 0;
            case "Professional Programme":
              return 3;
            case "Leadership Programme":
              return 2;
            default:
              return 5;
          }
        }
}
