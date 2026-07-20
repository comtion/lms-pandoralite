<?php
class Coursetype_model extends CI_Model {

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

        public function create_coursetype($data)
        {
          date_default_timezone_set("Asia/Bangkok");

          $this->db->from('lms_typecos');
          $this->db->where('tc_name_th', $data['tc_name_th']);
          $this->db->where('tc_name_en', $data['tc_name_en']);
          $this->db->where('com_id', $data['com_id']);
          $this->db->where('tc_status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data['tc_createdate'] = date("Y-m-d H:i");
            $this->db->insert('lms_typecos', $data);
            $id = $this->db->insert_id();
            if($id!=""){
              return "2";
            }else{
              return "3";
            }
          }else{
            return "1";
          }
        }

        public function update_coursetype($data,$tc_id)
        {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('tc_id', $tc_id);
          $this->db->update('lms_typecos', $data);
          return "2";
        }

        public function fetch_data_coursetype() {
          $this->db->from('lms_typecos');
          $this->db->where('lms_typecos.tc_status', '1');
          //$this->db->where('lang', $lang);
          $query = $this->db->get();
          return $query->result_array();
        }

        public function chk_chkbox()
        {
          date_default_timezone_set("Asia/Bangkok");
            $data = array(
              $_REQUEST['field_sql'] => $_REQUEST['value_chk']
            );
            $this->db->where('tc_id', $_REQUEST['tc_id_chk']);
            $this->db->update('lms_typecos', $data);
        }


}
