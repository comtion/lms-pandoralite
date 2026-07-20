<?php
class Manage_model extends CI_Model {

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

        public function getUser($emp_c) {
          $this->db->select('lms_usp.emp_c, lms_usp.useri, lms_usp.role, lms_emp.lead');
          $this->db->from('lms_usp');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_usp.emp_c', 'right');
          $this->db->where('lms_usp.status', '1');
          $this->db->where('lms_usp.emp_c', $emp_c);
          $query = $this->db->get();
          return $query->row_array();
        }

        public function getUsers($role, $lang) {
          $this->db->select('lms_usp.emp_c, lms_usp.useri, lms_usp.role, lms_emp.prefix, lms_emp.fname, lms_emp.lname, lms_emp.org_desc');
          $this->db->from('lms_usp');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_usp.emp_c');
          if($role != 'superadmin') $this->db->where('lms_usp.role !=', 'admin');
          $this->db->where('lms_usp.role !=', 'superadmin');
          $this->db->where('lms_usp.status', '1');
          $this->db->where('lang', $lang);
          $query = $this->db->get();
          return $query->result_array();
        }

        public function editUser($emp_c, $useri, $userp) {
          $this->db->set('useri', $useri);
          $this->db->set('userp', $userp);
          $this->db->where('emp_c', $emp_c);
          $this->db->where('status', '1');
          $this->db->update('lms_usp');
        }

        public function editLeader($emp_c, $lead) {
          $this->db->set('lead', $lead);
          $this->db->where('emp_c', $emp_c);
          $this->db->update('lms_emp');
        }

        public function addUser($emp_c, $useri, $userp, $role) {
          $this->db->set('emp_c', $emp_c);
          $this->db->set('useri', $useri);
          $this->db->set('userp', $userp);
          $this->db->set('role', $role);
          $this->db->where('status', '1');
          $this->db->insert('lms_usp');
        }

        public function applyUser($emp_c, $role) {
          $this->db->set('role', $role);
          $this->db->where('emp_c', $emp_c);
          $this->db->where('status', '1');
          $this->db->update('lms_usp');
        }

        public function removeUser($emp_c) {
          $this->db->where('emp_c', $emp_c);
          $this->db->where('status', '1');
          $this->db->delete('lms_usp');
        }

        public function checkEmpC($emp_c) {
          $this->db->where('emp_c', $emp_c);
          $this->db->where('status', '1');
          $query = $this->db->get('lms_usp');
          $rowUSP = $query->row_array();

          $this->db->distinct();
          $this->db->where('emp_c', $emp_c);
          $query = $this->db->get('lms_emp');
          $rowEMP = $query->row_array();

          if(empty($rowUSP) && !empty($rowEMP)) {
            return TRUE;
          }
          return FALSE;
        }

        public function checkUser($useri, $emp_c) {
          $this->db->where('useri', $useri);
          $this->db->where('status', '1');
          $query = $this->db->get('lms_usp');
          $row = $query->row_array();

          if(empty($row) || $row['emp_c'] == $emp_c) {
            return TRUE;
          }
          return FALSE;
        }
}
